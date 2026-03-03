@extends('admin.admin_layout')

@section('title', "Edit User: {$user->name} | Lumina Admin")

@section('content')
<div class="max-w-2xl w-full">
    <header class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-black">Edit User</h1>
                <p class="text-gray-600 text-sm mt-1">{{ $user->name }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors">
                &larr; Back to Users
            </a>
        </div>
    </header>

    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-gray-900 border border-white/5 rounded-2xl p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Role</label>
                <select name="role" required id="roleSelect" class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
                    <option value="" {{ $user->roles->isEmpty() ? 'selected' : '' }}>Customer (no role)</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Admin = full access. Staff = limited access (inventory, sales). Customer = store access only.</p>
            </div>

            {{--  @if($permissions->isNotEmpty())
            <div id="permissionsSection" class="border-t border-white/10 pt-6">
                <label class="block text-sm font-medium text-gray-400 mb-3">Extra permissions for this user</label>
                <p class="text-xs text-gray-500 mb-4">Grant additional permissions beyond their role. Useful for giving specific staff members access to more areas.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @php
                        $permissionLabels = [
                            'users.manage' => 'Manage Users & Roles',
                            'inventory.view' => 'View Products',
                            'inventory.create' => 'Create Products',
                            'inventory.update' => 'Edit Products',
                            'inventory.delete' => 'Delete Products',
                            'sales.view' => 'View Sales',
                            'deliveries.manage' => 'Manage Deliveries',
                        ];
                    @endphp
                    @foreach($permissions as $permission)
                    <label class="flex items-center space-x-3 bg-gray-800 border border-white/5 p-3 rounded-lg cursor-pointer hover:bg-gray-800/80 transition-colors">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                            {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-amber-300 focus:ring-amber-300">
                        <span class="text-gray-300 text-sm">{{ $permissionLabels[$permission->name] ?? $permission->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif--}}

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-white/5 text-gray-300 font-semibold rounded-lg hover:bg-white/10 border border-white/10 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
