@extends('admin.admin_layout')

@section('title', Str::headline($role->name) . ' | Lumina Admin')

@section('content')
<header class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.roles.index') }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-colors border border-white/10">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white capitalize">{{ Str::headline($role->name) }}</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Role details and assigned permissions.</p>
    </div>
    @if(!$role->archived_at && strtolower($role->name) !== 'admin')
        <a href="{{ route('admin.roles.edit', $role->id) }}" class="ml-auto px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-xl transition-colors shadow-lg text-sm">
            Edit Permissions
        </a>
    @endif
</header>

<div class="max-w-2xl space-y-6">

    {{-- Role Info --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-white/10 p-6">
        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Role Info</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-white/5">
                <span class="text-gray-500 dark:text-gray-400 text-sm">System Name</span>
                <span class="font-mono text-gray-900 dark:text-white text-sm">{{ $role->name }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-white/5">
                <span class="text-gray-500 dark:text-gray-400 text-sm">Display Name</span>
                <span class="font-bold text-gray-900 dark:text-white">{{ Str::headline($role->name) }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-white/5">
                <span class="text-gray-500 dark:text-gray-400 text-sm">Status</span>
                @if($role->archived_at)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-500/10 text-gray-400 border border-gray-500/20">Archived</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Active</span>
                @endif
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-gray-500 dark:text-gray-400 text-sm">Users Assigned</span>
                <span class="font-bold text-gray-900 dark:text-white">{{ $role->users()->count() }}</span>
            </div>
        </div>
    </div>

    {{-- Permissions --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-white/10 p-6">
        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">
            Permissions
            <span class="ml-2 px-2 py-0.5 bg-amber-500/20 text-amber-400 text-xs rounded-full font-semibold">{{ $role->permissions->count() }}</span>
        </h2>
        @if($role->permissions->isEmpty())
            <p class="text-gray-500 text-sm">No permissions assigned to this role.</p>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($role->permissions as $permission)
                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-medium bg-amber-500/20 text-amber-300 border border-amber-500/10">
                        {{ $permission->name }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
