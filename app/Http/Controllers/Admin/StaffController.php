<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // Add this!
use Illuminate\Support\Str; // Add this!

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorizeAdmin();

        // 🔄 UPDATED: Fetch users who have Spatie roles assigned to them
        $staff = User::whereHas('roles')->orderBy('name')->paginate(25);

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
            'password' => 'required|string|min:6|confirmed',
            'role' => 'nullable|string|exists:roles,name', // Validates against the roles table
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
            // 'role' => $data['role'] ?? null, // Keep this if you still have the old column
        ]);

        // 🔄 UPDATED: Attach the Spatie Role to the new user
        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }

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
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'nullable|string|exists:roles,name',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        // 🔄 UPDATED: Sync the Spatie Roles (Removes old role, adds the new one)
        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        } else {
            // If no role is selected, clear their roles
            $user->syncRoles([]); 
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        // prevent deleting self
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff removed.');
    }

    // 🔄 UPDATED: Automatically grab roles directly from the database!
    protected function availableRoles(): array
    {
        // Get all roles except 'Admin' (to prevent accidentally making someone a full admin)
        $roles = Role::where('name', '!=', 'admin')->get();
        
        $formattedRoles = [];
        foreach ($roles as $role) {
            // Converts 'sales_staff' into 'Sales Staff' for the dropdown menu UI
            $formattedRoles[Str::headline($role->name)] = $role->name; 
        }

        return $formattedRoles;
    }

    protected function authorizeAdmin()
    {
        if (!auth()->user() || (!auth()->user()->is_admin && !auth()->user()->hasRole('admin'))) {
            abort(403, 'Unauthorized access.');
        }
    }
}