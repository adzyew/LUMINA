@extends('admin.admin_layout')

@section('title', "View User: {$user->name} | Lumina Admin")

@section('content')
<div class="max-w-4xl w-full">
    <header class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">User Details</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Complete account information for {{ $user->name }}.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors">
            &larr; Back to Users
        </a>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Account</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">Name</span><span class="text-gray-900 dark:text-white font-semibold text-right">{{ $user->name }}</span></div>
                <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">Email</span><span class="text-gray-900 dark:text-white font-semibold text-right">{{ $user->email }}</span></div>
                <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">Phone</span><span class="text-gray-900 dark:text-white font-semibold text-right">{{ $user->phone ?: 'Not set' }}</span></div>
                <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">Verified</span><span class="text-right">@if($user->is_verified ?? $user->email_verified_at)<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Verified</span>@else<span class="px-2 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-400">Pending</span>@endif</span></div>
                <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">Status</span><span class="text-right">@if($user->archived_at)<span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400">Archived</span>@else<span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">Active</span>@endif</span></div>
                <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">Created</span><span class="text-gray-900 dark:text-white font-semibold text-right">{{ optional($user->created_at)->format('M d, Y h:i A') ?: '—' }}</span></div>
                <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">Last Updated</span><span class="text-gray-900 dark:text-white font-semibold text-right">{{ optional($user->updated_at)->format('M d, Y h:i A') ?: '—' }}</span></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Roles & Permissions</h2>
            <div class="mb-4">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">Roles</p>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->roles as $role)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-300">{{ \Illuminate\Support\Str::headline($role->name) }}</span>
                    @empty
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400">Customer</span>
                    @endforelse
                </div>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">Direct Permissions</p>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->getDirectPermissions() as $permission)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">{{ $permission->name }}</span>
                    @empty
                        <span class="text-sm text-gray-500 dark:text-gray-400">No direct permissions assigned.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Address & Notifications</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-white/10 p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">Shipping Address</p>
                    <p class="text-gray-900 dark:text-white font-semibold">{{ $user->shipping_address ?: 'Not set' }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-white/10 p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">Notifications</p>
                    <ul class="space-y-1 text-gray-900 dark:text-white font-semibold">
                        <li>Order Updates: {{ $user->notify_order_updates ? 'Enabled' : 'Disabled' }}</li>
                        <li>Promotions: {{ $user->notify_promotions ? 'Enabled' : 'Disabled' }}</li>
                        <li>Loyalty: {{ $user->notify_loyalty ? 'Enabled' : 'Disabled' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
