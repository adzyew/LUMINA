<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role; // Add this!
use Illuminate\Support\Str; // Add this!

class StaffController extends Controller
{
    private function splitNameParts(?string $fullName): array
    {
        $normalized = trim(preg_replace('/\s+/u', ' ', (string) $fullName) ?? '');
        if ($normalized === '') {
            return [
                'name' => '',
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null,
                'suffix' => null,
            ];
        }

        $tokens = preg_split('/\s+/u', $normalized) ?: [];
        $suffix = null;
        $knownSuffixes = ['jr', 'jr.', 'sr', 'sr.', 'ii', 'iii', 'iv', 'v', 'phd', 'md'];

        if (count($tokens) > 1) {
            $lastToken = strtolower((string) end($tokens));
            if (in_array($lastToken, $knownSuffixes, true)) {
                $suffix = (string) array_pop($tokens);
            }
        }

        $first = array_shift($tokens);
        $last = count($tokens) > 0 ? (string) array_pop($tokens) : null;
        $middle = count($tokens) > 0 ? trim(implode(' ', $tokens)) : null;

        return [
            'name' => $normalized,
            'first_name' => $first ?: null,
            'middle_name' => $middle !== '' ? $middle : null,
            'last_name' => $last ?: null,
            'suffix' => $suffix ?: null,
        ];
    }

    public function index()
    {
        $this->authorizeAdmin();

        // Show only non-admin role users in staff management.
        $staff = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'admin');
        })->orderBy('name')->paginate(25);

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $roles = $this->availableRoles();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
            'role' => 'required|string|exists:roles,name',
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, and a number.',
        ]);

        $nameParts = $this->splitNameParts($data['name']);

        $user = User::create([
            'name' => $nameParts['name'],
            'first_name' => $nameParts['first_name'],
            'middle_name' => $nameParts['middle_name'],
            'last_name' => $nameParts['last_name'],
            'suffix' => $nameParts['suffix'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
            // 'role' => $data['role'] ?? null, // Keep this if you still have the old column
        ]);

        $user->assignRole($data['role']);

        return redirect()->route('admin.staff.index')->with('success', 'Staff account created successfully.');
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();

        $roles = $this->availableRoles();
        
        // Find their current Spatie role to pre-select it in the dropdown
        $currentRole = $user->roles->first()->name ?? null;

        return view('admin.staff.edit', compact('user', 'roles', 'currentRole'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
            'role' => 'required|string|exists:roles,name',
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, and a number.',
        ]);

        $nameParts = $this->splitNameParts($data['name']);

        $user->name = $nameParts['name'];
        $user->first_name = $nameParts['first_name'];
        $user->middle_name = $nameParts['middle_name'];
        $user->last_name = $nameParts['last_name'];
        $user->suffix = $nameParts['suffix'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        // prevent deleting self
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff removed.');
    }

    // 🔄 UPDATED: Automatically grab roles directly from the database!
    protected function availableRoles(): array
    {
        // Exclude admin role so this page remains staff-only.
        $roles = Role::whereNotIn('name', ['admin', 'customer'])->orderBy('name')->get();
        
        $formattedRoles = [];
        foreach ($roles as $role) {
            // Converts 'sales_staff' into 'Sales Staff' for the dropdown menu UI
            $formattedRoles[Str::headline($role->name)] = $role->name; 
        }

        return $formattedRoles;
    }

    protected function authorizeAdmin()
    {
        /** @var \App\Models\User|null $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser || (!$currentUser->is_admin && !$currentUser->hasRole('admin'))) {
            abort(403, 'Unauthorized access.');
        }
    }
}
