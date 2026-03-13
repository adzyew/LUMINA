<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

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
                $query->whereHas('roles', function ($roleQuery) {
                    $roleQuery->where('name', '!=', 'admin')
                        ->where('name', '!=', 'customer');
                });
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
        $this->ensureArchivePermissionExists();
        $roles = Role::whereIn('name', $this->staffRoleNames())->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Display complete user details.
     */
    public function show(User $user)
    {
        $user->load(['roles.permissions', 'permissions']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Store a new user (staff or customer).
     * Admins cannot be created via this form.
     */
    public function store(Request $request)
    {
        $staffRoleNames = $this->staffRoleNames();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in($staffRoleNames)],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

            $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display roles list (table format like products).
     */
    public function rolesIndex()
    {
        $this->ensureArchivePermissionExists();
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        // Convert "Marketing Staff" to "marketing_staff"
        $formattedName = Str::snake(strtolower($request->name));

        Role::create(['name' => $formattedName]);

        return redirect()->back()->with('success', 'New role created successfully!');
    }

    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);

        // 1. SAFETY CHECK: Prevent deleting core system roles
        if (in_array(strtolower($role->name), ['admin', 'staff'])) {
            return redirect()->back()->with('error', 'System protection: You cannot delete the core Admin or Staff roles.');
        }

        // 2. SAFETY CHECK: Prevent deleting roles that users are still using
        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete this role because there are staff members currently assigned to it. Please reassign those users first.');
        }

        // 3. Safe to delete!
        $role->delete();

        return redirect()->back()->with('success', Str::headline($role->name) . ' role was successfully deleted!');
    }

    /**
     * Show form to edit role permissions.
     */
    public function editRole(Role $role)
    {
        $this->ensureArchivePermissionExists();
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

        $user->archived_at = Carbon::now();
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

    /**
     * Permanently delete an archived user account
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Cannot delete admin users.');
        }

        $user->delete();

        return redirect()->route('admin.users.index', ['filter' => 'archived'])
            ->with('success', 'User permanently deleted.');
    }

    /**
     * Allowed roles for admin-created staff users.
     */
    protected function staffRoleNames(): array
    {
        return Role::query()
            ->where('name', '!=', 'admin')
            ->where('name', '!=', 'customer')
            ->pluck('name')
            ->all();
    }

    protected function ensureArchivePermissionExists(): void
    {
        Permission::firstOrCreate(['name' => 'inventory.archive']);
    }
}
