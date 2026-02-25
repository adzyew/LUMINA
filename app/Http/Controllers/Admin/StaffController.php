<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorizeAdmin();

        $staff = User::whereNotNull('role')->orderBy('name')->paginate(25);

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
            'role' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? null,
            'is_admin' => false,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff account created.');
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();

        $roles = $this->availableRoles();
        return view('admin.staff.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'nullable|string|max:100',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->role = $data['role'] ?? null;
        $user->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated.');
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

    protected function availableRoles(): array
    {
        return [
            'Inventory Staff' => 'inventory',
            'Sales Staff' => 'sales',
            'Delivery Staff' => 'delivery',
            'Customer Support' => 'support',
            'Manager' => 'manager',
            'Other' => 'other',
        ];
    }

    protected function authorizeAdmin()
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403);
        }
    }
}
