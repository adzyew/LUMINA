<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{
    /**
     * Display users list, filterable by role (all, customer, staff, admin).
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = User::with('roles')->latest();

        // By default show only non-archived users in the "All" list.
        if ($filter === 'archived') {
            $query->whereNotNull('archived_at');
        } else {
            // exclude archived for all other filters (including 'all')
            $query->whereNull('archived_at');

            if ($filter === 'admin') {
                $query->role('admin');
            } elseif ($filter === 'staff') {
                $query->role('staff');
            } elseif ($filter === 'customer') {
                $query->whereDoesntHave('roles');
            }
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'filter'));
    }

    /**
     * Show form to add a new staff user.
     */
    public function create()
    {
        $roles = Role::whereIn('name', ['staff', 'customer'])->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a new user (staff or customer).
     * Admins cannot be created via this form.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:staff,customer',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

            $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->filled('role') && Role::where('name', $request->role)->exists()) {
            $user->assignRole($request->role);
        }

        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show form to edit user (assign role + additional permissions).
     * Admins cannot be edited.
     */
    public function edit(User $user)
    {
        if ($user->hasRole('admin')) {
            abort(403, 'Admin users cannot be edited.');
        }

        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Update user (name, email, role, permissions).
     * Admins cannot be edited.
     */
    public function update(Request $request, User $user)
    {
        if ($user->hasRole('admin')) {
            abort(403, 'Admin users cannot be edited.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('role') && Role::where('name', $request->role)->exists()) {
            $user->syncRoles([$request->role]);
        } else {
            $user->syncRoles([]);
        }

        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Display roles list (table format like products).
     */
    public function rolesIndex()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show form to edit role permissions.
     */
    public function editRole(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update role permissions.
     */
    public function updateRole(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions ?? []);
        return redirect()->route('admin.roles.index')
            ->with('success', 'Permissions updated successfully.');
    }

    /**
     * Archive a user account (admin only cannot be archived)
     */
    public function archive(User $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Cannot archive admin users.');
        }

        $user->archived_at = now();
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User archived.');
    }

    /**
     * Unarchive a user account
     */
    public function unarchive(User $user)
    {
        $user->archived_at = null;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User unarchived.');
    }
}
