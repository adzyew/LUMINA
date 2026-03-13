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
                        <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-4 py-2 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 hover:text-white transition-colors text-sm">View</a>
                        @if(!$user->hasRole('admin'))
                            @if($user->archived_at)
                                <form action="{{ route('admin.users.unarchive', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-400 transition-colors text-sm">Unarchive</button>
                                </form>
                                <form id="delete-user-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="showDeleteModal('{{ $user->id }}', '{{ $user->name }}')" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-colors text-sm">Delete</button>
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

<div id="deleteUserModal" class="fixed inset-0 z-100 hidden" aria-labelledby="delete-user-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="hideDeleteModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-200 dark:border-white/10">
                <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white" id="delete-user-title">Confirm Permanent Delete</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">You are about to permanently delete <span id="deleteUserName" class="font-semibold"></span>. This cannot be undone.</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Please wait <span id="deleteCountdown" class="font-semibold text-red-500">10</span> seconds to confirm.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button id="deleteConfirmBtn" type="button" onclick="confirmDeleteUser()" disabled class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white opacity-50 cursor-not-allowed sm:ml-3 sm:w-auto transition-colors">
                        Delete
                    </button>
                    <button type="button" onclick="hideDeleteModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteTimer = null;
    let selectedDeleteFormId = null;

    function showDeleteModal(userId, userName) {
        selectedDeleteFormId = 'delete-user-form-' + userId;

        const modal = document.getElementById('deleteUserModal');
        const nameEl = document.getElementById('deleteUserName');
        const countdownEl = document.getElementById('deleteCountdown');
        const confirmBtn = document.getElementById('deleteConfirmBtn');

        nameEl.textContent = userName;
        confirmBtn.disabled = true;
        confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
        countdownEl.textContent = '10';
        modal.classList.remove('hidden');

        let secondsLeft = 10;
        if (deleteTimer) {
            clearInterval(deleteTimer);
        }

        deleteTimer = setInterval(() => {
            secondsLeft--;
            countdownEl.textContent = String(secondsLeft);

            if (secondsLeft <= 0) {
                clearInterval(deleteTimer);
                deleteTimer = null;
                confirmBtn.disabled = false;
                confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }, 1000);
    }

    function hideDeleteModal() {
        const modal = document.getElementById('deleteUserModal');
        modal.classList.add('hidden');
        selectedDeleteFormId = null;

        if (deleteTimer) {
            clearInterval(deleteTimer);
            deleteTimer = null;
        }
    }

    function confirmDeleteUser() {
        if (!selectedDeleteFormId) {
            return;
        }

        const form = document.getElementById(selectedDeleteFormId);
        if (form) {
            form.submit();
        }
    }
</script>
@endsection
