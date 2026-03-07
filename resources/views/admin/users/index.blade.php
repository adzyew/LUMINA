@extends('admin.admin_layout')

@section('title', 'Users | Lumina Admin')

@section('content')
<header class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Users</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Manage accounts and create staff users.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
        + Add Staff
    </a>
</header>

{{-- Filter tabs: All | Customers | Staff | Admin --}}
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.users.index', ['filter' => 'all']) }}"
       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? 'all') === 'all' ? 'bg-amber-300 text-black' : 'bg-gray-100 dark:bg-white/5 text-gray-600 hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10' }}">
        All Users
    </a>
    <a href="{{ route('admin.users.index', ['filter' => 'customer']) }}"
       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? '') === 'customer' ? 'bg-amber-300 text-black' : 'bg-gray-100 dark:bg-white/5 text-gray-600 hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10' }}">
        Customers
    </a>
    <a href="{{ route('admin.users.index', ['filter' => 'staff']) }}"
       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? '') === 'staff' ? 'bg-amber-300 text-black' : 'bg-gray-100 dark:bg-white/5 text-gray-600 hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10' }}">
        Staff
    </a>
    <a href="{{ route('admin.users.index', ['filter' => 'admin']) }}"
       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? '') === 'admin' ? 'bg-amber-300 text-black' : 'bg-gray-100 dark:bg-white/5 text-gray-600 hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10' }}">
        Admin
    </a>
    <a href="{{ route('admin.users.index', ['filter' => 'archived']) }}"
       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? '') === 'archived' ? 'bg-amber-300 text-black' : 'bg-gray-100 dark:bg-white/5 text-gray-600 hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10' }}">
        Archived
    </a>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-900 rounded-xl overflow-hidden border border-gray-200 dark:border-white/5">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-white/5 text-gray-700 dark:text-white border-b border-gray-200 dark:border-white/10 text-sm">
                <th class="p-4">Name</th>
                <th class="p-4">Email Address</th>
                <th class="p-4">Role</th>
                <th class="p-4">Verified</th>
                <th class="p-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
            @foreach($users as $user)
            <tr class="hover:bg-amber-300/10 transition duration-300">
                <td class="p-4 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                <td class="p-4 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                <td class="p-4">
                    @forelse($user->roles as $role)
                        @if($role->name === 'admin')
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-500/30 text-amber-300">Admin</span>
                        @elseif($role->name === 'staff')
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">Staff</span>
                        @elseif($role->name === 'customer')
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400">Customer</span>
                        @else
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-300 capitalize">{{ $role->name }}</span>
                        @endif
                    @empty
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400">Customer</span>
                    @endforelse
                </td>
                <td class="p-4">
                    @if($user->is_verified ?? $user->email_verified_at)
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Verified</span>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-400">Pending</span>
                    @endif
                </td>
                <td class="p-4">
                    <div class="flex justify-center gap-3">
                        @if($user->hasRole('admin'))
                            <span class="text-gray-500 text-sm">—</span>
                        @else
                            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 hover:text-white transition-colors text-sm">Edit</a>
                            @if($user->archived_at)
                                <form action="{{ route('admin.users.unarchive', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-400 transition-colors text-sm">Unarchive</button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Permanently delete this user? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-colors text-sm">Delete</button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.archive', $user) }}" method="POST" onsubmit="return confirm('Archive this user? They will be prevented from logging in.');">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-300 hover:text-black transition-colors text-sm">Archive</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($users->isEmpty())
        <div class="p-12 text-center text-gray-500">No users found.</div>
    @endif
</div>

@if($users->hasPages())
    <div class="mt-6">{{ $users->links() }}</div>
@endif
@endsection
