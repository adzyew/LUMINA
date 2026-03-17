@extends('admin.admin_layout')

@section('title', 'Users | Lumina Admin')

@section('content')
<header class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-playfair font-bold text-gray-900">Users</h1>
        <p class="text-gray-600 text-sm mt-1">Manage accounts and create staff users.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-lg transition-colors shadow-lg">
        + Add Staff
    </a>
</header>

{{-- Filter tabs: All | Customers | Staff | Admin --}}
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.users.index', ['filter' => 'all']) }}"
       class="px-5 py-2 rounded-xl font-semibold text-sm transition-colors {{ ($filter ?? 'all') === 'all' ? 'bg-amber-400 text-black shadow' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200 border border-gray-200' }}">
        All Users
    </a>
    <a href="{{ route('admin.users.index', ['filter' => 'customer']) }}"
       class="px-5 py-2 rounded-xl font-semibold text-sm transition-colors {{ ($filter ?? '') === 'customer' ? 'bg-amber-400 text-black shadow' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200 border border-gray-200' }}">
        Customers
    </a>
    <a href="{{ route('admin.users.index', ['filter' => 'staff']) }}"
       class="px-5 py-2 rounded-xl font-semibold text-sm transition-colors {{ ($filter ?? '') === 'staff' ? 'bg-amber-400 text-black shadow' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200 border border-gray-200' }}">
        Staff
    </a>
    <a href="{{ route('admin.users.index', ['filter' => 'admin']) }}"
       class="px-5 py-2 rounded-xl font-semibold text-sm transition-colors {{ ($filter ?? '') === 'admin' ? 'bg-amber-400 text-black shadow' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200 border border-gray-200' }}">
        Admin
    </a>
    <a href="{{ route('admin.users.index', ['filter' => 'archived']) }}"
       class="px-5 py-2 rounded-xl font-semibold text-sm transition-colors {{ ($filter ?? '') === 'archived' ? 'bg-amber-400 text-black shadow' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200 border border-gray-200' }}">
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
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-500">Staff & Admin</h2>
            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-medium">{{ $staffUsers->count() }}</span>
        </div>
        <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                        <th class="p-4">Name</th>
                        <th class="p-4">Email Address</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Verified</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($staffUsers as $user)
                        @include('admin.users._row')
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-500">No staff users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Customers Section --}}
    <div>
        <div class="flex items-center gap-2 mb-3">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-500">Customers</h2>
            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-medium">{{ $customerUsers->count() }}</span>
        </div>
        <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                        <th class="p-4">Name</th>
                        <th class="p-4">Email Address</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Verified</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($customerUsers as $user)
                        @include('admin.users._row')
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-500">No customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@else

    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                    <th class="p-4">Name</th>
                    <th class="p-4">Email Address</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Verified</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
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

<div id="verifyUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/75" onclick="hideVerifyModal()"></div>
        <div class="relative z-10 bg-white border border-gray-200 rounded-2xl shadow-2xl p-8 max-w-md w-full">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.745 3.745 0 0 1 3.296-1.043A3.745 3.745 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 0 1 3.296 1.043 3.745 3.745 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Confirm Verification</h3>
                    <p class="text-gray-500 text-sm">This will grant staff access.</p>
                </div>
            </div>
            <p class="text-gray-600 mb-6">You are about to verify <span id="verifyUserName" class="font-bold text-gray-900"></span>. They will be granted access as a verified staff member.</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideVerifyModal()" class="px-5 py-2.5 bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" onclick="confirmVerifyUser()" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors">
                    Verify User
                </button>
            </div>
        </div>
    </div>
</div>

<div id="deleteUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
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
                    <h3 class="text-lg font-bold text-gray-900">Delete User</h3>
                    <p class="text-gray-500 text-sm">This action cannot be undone.</p>
                </div>
            </div>
            <p class="text-gray-600 mb-2">You are about to permanently delete <span id="deleteUserName" class="font-bold text-gray-900"></span>.</p>
            <p class="text-sm text-gray-500 mb-6">Please wait <span id="deleteCountdown" class="font-semibold text-red-500">10</span> seconds to confirm.</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideDeleteModal()" class="px-5 py-2.5 bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium">
                    Cancel
                </button>
                <button id="deleteConfirmBtn" type="button" onclick="confirmDeleteUser()" disabled class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors opacity-50 cursor-not-allowed">
                    Delete Permanently
                </button>
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
