<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class UserManagementController extends Controller
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

    /**
     * Display users list, filterable by role (all, customer, staff, admin).
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $roles = Role::whereIn('name', $this->staffRoleNames())->orderBy('name')->get();

        // "All" tab: return staff and customers as separate groups (no pagination)
        if ($filter === 'all') {
            $staffUsers = User::with('roles')
                ->whereNull('archived_at')
                ->whereHas('roles')
                ->latest()
                ->get();

            $customerUsers = User::with('roles')
                ->whereNull('archived_at')
                ->whereDoesntHave('roles')
                ->latest()
                ->get();

            return view('admin.users.index', compact('staffUsers', 'customerUsers', 'filter', 'roles'));
        }

        $query = User::with('roles')->latest();

        if ($filter === 'archived') {
            $query->whereNotNull('archived_at');
        } else {
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

        return view('admin.users.index', compact('users', 'filter', 'roles'));
    }

    /**
     * Show form to add a new staff user.
     */
    public function create()
    {
        return redirect()->route('admin.users.index', [
            'filter' => 'staff',
            'openAddStaff' => 1,
        ]);
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
     * Show form to edit a staff user.
     */
    public function edit(User $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot edit admin users.');
        }

        $roles = Role::whereIn('name', $this->staffRoleNames())->orderBy('name')->get();
        $currentRole = $user->roles->first()?->name;

        return view('admin.users.edit', compact('user', 'roles', 'currentRole'));
    }

    /**
     * Update a staff user's details and role.
     */
    public function update(Request $request, User $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot edit admin users.');
        }

        $staffRoleNames = $this->staffRoleNames();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
            'role'     => ['required', 'string', Rule::in($staffRoleNames)],
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, and a number.',
        ]);

        $nameParts = $this->splitNameParts($request->name);

        $user->name  = $nameParts['name'];
        $user->first_name = $nameParts['first_name'];
        $user->middle_name = $nameParts['middle_name'];
        $user->last_name = $nameParts['last_name'];
        $user->suffix = $nameParts['suffix'];
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index', ['filter' => 'staff'])
            ->with([
                'toast_type' => 'success',
                'toast_message' => "'{$user->name}' has been updated successfully.",
            ]);
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
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
            'role' => ['required', 'string', Rule::in($staffRoleNames)],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, and a number.',
        ]);

            $nameParts = $this->splitNameParts($request->name);

            $user = User::create([
            'name' => $nameParts['name'],
            'first_name' => $nameParts['first_name'],
            'middle_name' => $nameParts['middle_name'],
            'last_name' => $nameParts['last_name'],
            'suffix' => $nameParts['suffix'],
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.users.index')
            ->with([
                'toast_type' => 'success',
                'toast_message' => 'User created successfully.',
            ]);
    }

    /**
     * Display roles list (table format like products).
     */
    public function rolesIndex()
    {
        $this->ensureArchivePermissionExists();
        $tab = request('tab', 'active');
        $activeRoles = Role::with('permissions')->whereNull('archived_at')->get();
        $archivedRoles = Role::with('permissions')->whereNotNull('archived_at')->get();
        $permissions = Permission::query()->orderBy('name')->get();
        return view('admin.roles.index', compact('activeRoles', 'archivedRoles', 'tab', 'permissions'));
    }
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        // Convert "Marketing Staff" to "marketing_staff"
        $formattedName = Str::snake(strtolower($request->name));

        Role::create(['name' => $formattedName]);

        return redirect()->back()->with([
            'toast_type' => 'success',
            'toast_message' => 'New role created successfully!',
        ]);
    }

    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);

        // 1. SAFETY CHECK: Prevent deleting core system roles
        if (in_array(strtolower($role->name), ['admin'])) {
            return redirect()->back()->with([
                'toast_type' => 'error',
                'toast_message' => 'System protection: You cannot delete the core Admin role.',
            ]);
        }

        // 2. SAFETY CHECK: Prevent deleting roles that users are still using
        if ($role->users()->count() > 0) {
            return redirect()->back()->with([
                'toast_type' => 'error',
                'toast_message' => 'Cannot delete this role because staff are still assigned to it.',
            ]);
        }

        // 3. Safe to delete!
        $role->delete();

        return redirect()->back()->with([
            'toast_type' => 'success',
            'toast_message' => Str::headline($role->name) . ' role was successfully deleted!',
        ]);
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
            ->with([
                'toast_type' => 'success',
                'toast_message' => 'Permissions updated successfully.',
            ]);
    }

    public function showRole(Role $role)
    {
        $role->load('permissions');
        return view('admin.roles.show', compact('role'));
    }

    public function archiveRole(Role $role)
    {
        if (in_array(strtolower($role->name), ['admin'])) {
            return redirect()->back()->with([
                'toast_type' => 'error',
                'toast_message' => 'The Admin role cannot be archived.',
            ]);
        }
        $role->archived_at = Carbon::now();
        $role->save();
        return redirect()->back()->with([
            'toast_type' => 'success',
            'toast_message' => Str::headline($role->name) . ' role has been archived.',
        ]);
    }

    public function restoreRole(Role $role)
    {
        $role->archived_at = null;
        $role->save();
        return redirect()->back()->with([
            'toast_type' => 'success',
            'toast_message' => Str::headline($role->name) . ' role has been restored.',
        ]);
    }

    /**
     * Archive a user account (admin only cannot be archived)
     */
    public function archive(User $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->back()->with([
                'toast_type' => 'error',
                'toast_message' => 'Cannot archive admin users.',
            ]);
        }

        $user->archived_at = Carbon::now();
        $user->save();

        return redirect()->route('admin.users.index')->with([
            'toast_type' => 'success',
            'toast_message' => 'User archived.',
        ]);
    }

    /**
     * Unarchive a user account
     */
    public function unarchive(User $user)
    {
        $user->archived_at = null;
        $user->save();

        return redirect()->route('admin.users.index')->with([
            'toast_type' => 'success',
            'toast_message' => 'User unarchived.',
        ]);
    }

    /**
     * Manually verify a staff user's email (admin only).
     */
    public function verify(User $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->back()->with([
                'toast_type' => 'error',
                'toast_message' => 'Cannot manually verify admin users.',
            ]);
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect()->back()->with([
            'toast_type' => 'success',
            'toast_message' => "'{$user->name}' has been verified.",
        ]);
    }

    /**
     * Permanently delete an archived user account
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->back()->with([
                'toast_type' => 'error',
                'toast_message' => 'Cannot delete admin users.',
            ]);
        }

        $user->delete();

        return redirect()->route('admin.users.index', ['filter' => 'archived'])
            ->with([
                'toast_type' => 'success',
                'toast_message' => 'User permanently deleted.',
            ]);
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
        Permission::firstOrCreate(['name' => 'returns.manage']);
    }
}
