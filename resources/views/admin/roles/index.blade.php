@extends('admin.admin_layout')

@section('title', 'Roles & Permissions | Lumina Admin')

@section('content')
<header class="flex justify-between items-center mb-8">
    @include('partials.favicon')
    <div>
        <h1 class="text-3xl font-playfair font-bold text-gray-900">Roles & Permissions</h1>
        <p class="text-gray-600 text-sm mt-1">Manage role permissions for your team.</p>
    </div>
    <button onclick="document.getElementById('addRoleModal').classList.remove('hidden')" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-lg transition-colors shadow-lg">
        + Add Role
    </button>
</header>

{{-- Add Role Modal --}}
<div id="addRoleModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/75 transition-opacity" aria-hidden="true" onclick="document.getElementById('addRoleModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative z-10 inline-block align-bottom bg-white border border-gray-200 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-2" id="modal-title">Create New Role</h3>
            <p class="text-gray-500 text-sm mb-6">Enter a department or job title. It will automatically be formatted for the system.</p>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name (e.g., Marketing Staff)</label>
                    <input type="text" name="name" id="name" required placeholder="Type role name..." class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="document.getElementById('addRoleModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-black font-bold rounded-xl transition-colors shadow-lg">
                        Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteRoleModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/75" onclick="hideDeleteModal()"></div>
        <div class="relative z-10 bg-white border border-gray-200 rounded-2xl shadow-2xl p-8 max-w-md w-full">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Delete Role</h3>
                    <p class="text-gray-500 text-sm">This action cannot be undone.</p>
                </div>
            </div>
            <p class="text-gray-600 mb-6">Are you sure you want to permanently delete the <span id="deleteRoleName" class="font-bold text-gray-900"></span> role?</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideDeleteModal()" class="px-5 py-2.5 bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" onclick="confirmDeleteRole()" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors">
                    Delete Permanently
                </button>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-6 bg-red-100 text-red-800 p-4 rounded-lg border border-red-200">
        {{ session('error') }}
    </div>
@endif

{{-- Filter Tabs --}}
<div class="flex gap-2 mb-6">
    <a href="{{ route('admin.roles.index', ['tab' => 'active']) }}"
       class="px-5 py-2 rounded-xl font-semibold text-sm transition-colors
              {{ $tab !== 'archived' ? 'bg-amber-400 text-black shadow' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200 border border-gray-200' }}">
        Active
        <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs {{ $tab !== 'archived' ? 'bg-amber-600/20' : 'bg-gray-200' }}">{{ $activeRoles->count() }}</span>
    </a>
    <a href="{{ route('admin.roles.index', ['tab' => 'archived']) }}"
       class="px-5 py-2 rounded-xl font-semibold text-sm transition-colors
              {{ $tab === 'archived' ? 'bg-amber-400 text-black shadow' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200 border border-gray-200' }}">
        Archived
        <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs {{ $tab === 'archived' ? 'bg-amber-600/20' : 'bg-gray-200' }}">{{ $archivedRoles->count() }}</span>
    </a>
</div>

@php $roles = $tab === 'archived' ? $archivedRoles : $activeRoles; @endphp

<div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                <th class="p-4">Role</th>
                <th class="p-4">Permissions</th>
                @if($tab === 'archived')
                    <th class="p-4 text-gray-400 text-xs">Archived</th>
                @endif
                <th class="p-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($roles as $role)
                <tr class="hover:bg-amber-300/5 transition duration-300">
                    <td class="p-4">
                        <span class="font-bold text-gray-900 capitalize">{{ Str::headline($role->name) }}</span>
                        @if(strtolower($role->name) === 'admin')
                            <span class="ml-2 px-2 py-0.5 bg-amber-500/10 text-amber-400 border border-amber-500/10 text-sm rounded-full font-semibold">System</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @forelse($role->permissions as $permission)
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-amber-500/10 text-amber-400 border border-amber-500/1">{{ $permission->name }}</span>
                            @empty
                                <span class="text-gray-500 text-sm">No permissions assigned</span>
                            @endforelse
                        </div>
                    </td>
                    @if($tab === 'archived')
                        <td class="p-4 text-gray-400 text-sm">{{ $role->archived_at ? $role->archived_at->format('M d, Y') : '—' }}</td>
                    @endif
                    <td class="p-4">
                        <div class="flex items-center justify-center gap-2">

                            {{-- VIEW --}}
                            <a href="{{ route('admin.roles.show', $role->id) }}"
                               title="View Role"
                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-400/10 hover:bg-amber-400 text-amber-400 hover:text-black transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.578-3.007-9.964-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </a>

                            @if($tab !== 'archived')
                                {{-- EDIT (active, non-admin only) --}}
                                @if(strtolower($role->name) !== 'admin')
                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                   title="Edit Permissions"
                                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-500/10 hover:bg-blue-500 text-blue-400 hover:text-white transition-all duration-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                    </svg>
                                </a>
                                @endif

                                {{-- ARCHIVE (active, non-admin only) --}}
                                @if(strtolower($role->name) !== 'admin')
                                    <form id="archive-role-form-{{ $role->id }}" action="{{ route('admin.roles.archive', $role->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                title="Archive Role"
                                                onclick="return confirm('Archive the {{ Str::headline($role->name) }} role? It will be hidden from active use.')"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-500/10 hover:bg-gray-500 text-gray-400 hover:text-white transition-all duration-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                            @else
                                {{-- RESTORE (archived only) --}}
                                <form action="{{ route('admin.roles.restore', $role->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            title="Restore Role"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-500/10 hover:bg-gray-500 text-gray-400 hover:text-white transition-all duration-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                                        </svg>
                                    </button>
                                </form>

                                {{-- DELETE (archived, non-admin only) --}}
                                @if(strtolower($role->name) !== 'admin')
                                    <form id="delete-role-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                title="Delete Role"
                                                onclick="showDeleteModal('{{ $role->id }}', '{{ Str::headline($role->name) }}')"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white transition-all duration-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-12 text-center text-gray-500">
                        {{ $tab === 'archived' ? 'No archived roles.' : 'No active roles found.' }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    let pendingDeleteFormId = null;

    function showDeleteModal(roleId, roleName) {
        pendingDeleteFormId = 'delete-role-form-' + roleId;
        document.getElementById('deleteRoleName').textContent = roleName;
        document.getElementById('deleteRoleModal').classList.remove('hidden');
    }

    function hideDeleteModal() {
        pendingDeleteFormId = null;
        document.getElementById('deleteRoleModal').classList.add('hidden');
    }

    function confirmDeleteRole() {
        if (pendingDeleteFormId) {
            document.getElementById(pendingDeleteFormId).submit();
        }
    }
</script>
@endsection
