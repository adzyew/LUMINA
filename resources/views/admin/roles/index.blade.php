@extends('admin.admin_layout')

@section('title', 'Roles & Permissions | Lumina Admin')

@section('content')
<header class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Roles & Permissions</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Manage role permissions for your team.</p>
    </div>
    <button onclick="document.getElementById('addRoleModal').classList.remove('hidden')" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-lg transition-colors shadow-lg">
    + Add Role
    </button>

    <div id="addRoleModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-black/75 transition-opacity" aria-hidden="true" onclick="document.getElementById('addRoleModal').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative z-10 inline-block align-bottom bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2" id="modal-title">Create New Role</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Enter a department or job title. It will automatically be formatted for the system.</p>

            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role Name (e.g., Marketing Staff)</label>
                    <input type="text" name="name" id="name" required placeholder="Type role name..." class="w-full bg-gray-50 dark:bg-black/50 border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="document.getElementById('addRoleModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 dark:bg-white/5 border border-gray-300 dark:border-white/10 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-700 dark:text-white rounded-xl transition-colors font-medium">
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
</header>

@if(session('success'))
    <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-200 dark:border-white/5">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-white/5 text-gray-700 dark:text-white border-b border-gray-200 dark:border-white/10 text-sm">
                <th class="p-4">Role</th>
                <th class="p-4">Permissions</th>
                <th class="p-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
    @foreach($roles as $role)
            <tr class="hover:bg-amber-300/10 transition duration-300">
                <td class="p-4">
                    <span class="font-bold text-gray-900 dark:text-white capitalize">{{ Str::headline($role->name) }}</span>
                </td>
                <td class="p-4">
                    <div class="flex flex-wrap gap-2">
                        @forelse($role->permissions as $permission)
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-300">{{ $permission->name }}</span>
                        @empty
                            <span class="text-gray-500 text-sm">No permissions assigned</span>
                        @endforelse
                    </div>
                </td>
                <td class="p-4">
                   <div class="flex items-center gap-3">
                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="px-5 py-2 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-xl transition-colors text-sm shadow-md">
                        Edit Permissions
                    </a>

                    @if(strtolower($role->name) !== 'admin' && strtolower($role->name) !== 'staff')
                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the {{ Str::headline($role->name) }} role? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-5 py-2 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 hover:border-red-500 font-bold rounded-xl transition-all text-sm shadow-md">
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
                </td>
            </tr>
                @endforeach
        </tbody>
    </table>

    @if($roles->isEmpty())
        <div class="p-12 text-center text-gray-500">No roles found.</div>
    @endif
</div>
@endsection
