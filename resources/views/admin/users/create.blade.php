@extends('admin.admin_layout')

@section('title', 'Add Staff | Lumina Admin')

@section('content')
<div class="max-w-2xl w-full">
    <header class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Add User</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Create a new staff or customer account.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-amber-500 transition-colors">
                &larr; Back to Users
            </a>
        </div>
    </header>

    @if ($errors->any())
        <div class="mb-6 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-500/50 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 sm:p-8 shadow-sm dark:shadow-none">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 dark:focus:border-amber-300 outline-none transition-colors"
                    placeholder="Full name">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 dark:focus:border-amber-300 outline-none transition-colors"
                    placeholder="email@example.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Password</label>
                <input type="password" name="password" required minlength="8"
                    class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 dark:focus:border-amber-300 outline-none transition-colors"
                    placeholder="Minimum 8 characters">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" required minlength="8"
                    class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 dark:focus:border-amber-300 outline-none transition-colors"
                    placeholder="Repeat password">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Role</label>
                <select name="role" id="roleSelect" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 dark:focus:border-amber-300 outline-none transition-colors">
                    <option value="staff" {{ old('role', 'staff') === 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Staff = inventory &amp; sales access. Customer = store access only.</p>
            </div>

            @if($permissions->isNotEmpty())
            <div id="permissionsSection" class="border-t border-gray-200 dark:border-white/10 pt-6">
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Extra permissions</label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Grant additional permissions beyond the role (optional).</p>
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
                    <label class="flex items-center space-x-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/5 p-3 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800/80 transition-colors">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                            {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-700 text-amber-500 focus:ring-amber-400">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $permissionLabels[$permission->name] ?? $permission->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
                    Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
