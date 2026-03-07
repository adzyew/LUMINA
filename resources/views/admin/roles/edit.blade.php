@extends('admin.admin_layout')

@section('title', "Edit Permissions: {$role->name} | Lumina Admin")

@section('content')
<div class="max-w-4xl w-full">
    <header class="mb-8">
        <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Edit Permissions</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Manage permissions for {{ Str::headline($role->name) }} role.</p>
    </header>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($permissions as $permission)
                    <label class="flex items-center space-x-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/5 p-4 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800/80 transition-colors">
                    <input type="checkbox"
                           name="permissions[]"
                           value="{{ $permission->name }}"
                               {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-700 text-amber-300 focus:ring-amber-300">
                        <span class="text-gray-700 dark:text-gray-300">{{ ucwords(str_replace('.', ' ', $permission->name)) }}</span>
                </label>
            @endforeach
        </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="px-6 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
            Save Changes
        </button>
                <a href="{{ route('admin.roles.index') }}" class="px-6 py-2.5 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10 transition-colors">
                    Cancel
                </a>
            </div>
    </form>
    </div>
</div>
@endsection
