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

@if($filter === 'all')

    {{-- Staff & Admin Section --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-3">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Staff & Admin</h2>
            <span class="text-xs bg-gray-100 dark:bg-white/10 text-gray-500 dark:text-gray-400 px-2 py-0.5 rounded-full font-medium">{{ $staffUsers->count() }}</span>
        </div>
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
                    @forelse($staffUsers as $user)
                        @include('admin.users._row')
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-500 dark:text-gray-400">No staff users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Customers Section --}}
    <div>
        <div class="flex items-center gap-2 mb-3">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Customers</h2>
            <span class="text-xs bg-gray-100 dark:bg-white/10 text-gray-500 dark:text-gray-400 px-2 py-0.5 rounded-full font-medium">{{ $customerUsers->count() }}</span>
        </div>
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
                    @forelse($customerUsers as $user)
                        @include('admin.users._row')
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-500 dark:text-gray-400">No customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@else

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
                    @include('admin.users._row')
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

@endif

<div id="verifyUserModal" class="fixed inset-0 z-100 hidden" aria-labelledby="verify-user-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="hideVerifyModal()"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-200 dark:border-white/10">
                <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-500/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.745 3.745 0 0 1 3.296-1.043A3.745 3.745 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 0 1 3.296 1.043 3.745 3.745 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white" id="verify-user-title">Confirm Verification</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">You are about to verify <span id="verifyUserName" class="font-semibold text-gray-900 dark:text-white"></span>. This will grant them access as a verified staff member.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="confirmVerifyUser()" class="inline-flex w-full justify-center rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto transition-colors">
                        Verify
                    </button>
                    <button type="button" onclick="hideVerifyModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

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
    let selectedVerifyFormId = null;

    function showVerifyModal(userId, userName) {
        selectedVerifyFormId = 'verify-user-form-' + userId;
        document.getElementById('verifyUserName').textContent = userName;
        document.getElementById('verifyUserModal').classList.remove('hidden');
    }

    function hideVerifyModal() {
        document.getElementById('verifyUserModal').classList.add('hidden');
        selectedVerifyFormId = null;
    }

    function confirmVerifyUser() {
        const form = document.getElementById(selectedVerifyFormId);
        if (form) form.submit();
    }

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
