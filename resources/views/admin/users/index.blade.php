@extends('admin.admin_layout')

@section('title', 'Users | Lumina Admin')

@section('content')
<header class="flex justify-between items-center mb-8">
    @include('partials.favicon')
    <div>
        <h1 class="text-3xl font-playfair font-bold text-gray-900">User Management</h1>
    </div>
    <button type="button" onclick="showAddStaffModal()" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-lg transition-colors shadow-lg">
        + Add Staff
    </button>
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

<div id="viewUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/75" onclick="hideUserViewModal()"></div>
        <div class="relative z-10 bg-white border border-gray-200 rounded-2xl shadow-2xl p-6 sm:p-8 max-w-3xl w-full">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-playfair font-bold text-gray-900">User Details</h3>
                    <p class="text-gray-500 text-sm mt-1">Complete account information.</p>
                </div>
                <button type="button" onclick="hideUserViewModal()" class="text-gray-400 hover:text-gray-600" aria-label="Close view user modal">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">Account</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><span class="text-gray-500">Name</span><span id="viewUserName" class="text-gray-900 font-semibold text-right"></span></div>
                        <div class="flex justify-between gap-4"><span class="text-gray-500">Email</span><span id="viewUserEmail" class="text-gray-900 font-semibold text-right"></span></div>
                        <div class="flex justify-between gap-4"><span class="text-gray-500">Phone</span><span id="viewUserPhone" class="text-gray-900 font-semibold text-right"></span></div>
                        <div class="flex justify-between gap-4"><span class="text-gray-500">Verified</span><span id="viewUserVerified" class="text-right"></span></div>
                        <div class="flex justify-between gap-4"><span class="text-gray-500">Status</span><span id="viewUserStatus" class="text-right"></span></div>
                        <div class="flex justify-between gap-4"><span class="text-gray-500">Created</span><span id="viewUserCreated" class="text-gray-900 font-semibold text-right"></span></div>
                        <div class="flex justify-between gap-4"><span class="text-gray-500">Last Updated</span><span id="viewUserUpdated" class="text-gray-900 font-semibold text-right"></span></div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">Roles</h4>
                    <div id="viewUserRoles" class="flex flex-wrap gap-2"></div>
                    <div class="mt-6">
                        <h5 class="text-sm font-semibold text-gray-700 mb-2">Address & Notifications</h5>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p><span class="text-gray-500">Shipping Address:</span> <span id="viewUserAddress"></span></p>
                            <p><span class="text-gray-500">Order Updates:</span> <span id="viewUserNotifyOrders"></span></p>
                            <p><span class="text-gray-500">Promotions:</span> <span id="viewUserNotifyPromotions"></span></p>
                            <p><span class="text-gray-500">Loyalty:</span> <span id="viewUserNotifyLoyalty"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="editUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/75"></div>
        <div class="relative z-10 bg-white border border-gray-200 rounded-2xl shadow-2xl p-6 sm:p-8 max-w-2xl w-full">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-playfair font-bold text-gray-900">Edit Staff User</h3>
                    <p id="editUserSubtitle" class="text-gray-500 text-sm mt-1">Update details and role assignment.</p>
                </div>
                
            </div>

            <form id="editUserForm" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_context" value="edit_user">
                <input id="editUserIdInput" type="hidden" name="edit_user_id" value="">

                <div>
                    <label for="editUserNameInput" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                    <input id="editUserNameInput" type="text" name="name" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300 transition">
                </div>

                <div>
                    <label for="editUserEmailInput" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <input id="editUserEmailInput" type="email" name="email" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300 transition">
                </div>

                <div>
                    <label for="editUserRoleInput" class="block text-sm font-semibold text-gray-700 mb-1.5">Role / Department</label>
                    <select id="editUserRoleInput" name="role" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300 transition">
                        <option value="">-- Select role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ Str::headline($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="border-t border-gray-100"></div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password <span class="font-normal text-gray-400">(leave blank to keep current)</span></label>
                    <div class="relative">
                        <input id="editUserPassword" type="password" name="password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 pr-12 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300 transition" placeholder="New password">
                        <button type="button" onclick="togglePasswordField('editUserPassword', 'edit-pass-eye-open', 'edit-pass-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="edit-pass-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="edit-pass-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                    <div class="relative">
                        <input id="editUserPasswordConfirmation" type="password" name="password_confirmation" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 pr-12 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300 transition" placeholder="Confirm new password">
                        <button type="button" onclick="togglePasswordField('editUserPasswordConfirmation', 'edit-confirm-eye-open', 'edit-confirm-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="edit-confirm-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="edit-confirm-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="hideUserEditModal()" class="px-5 py-2.5 bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-xl transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

<div id="addStaffModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/75" onclick="hideAddStaffModal()"></div>
        <div class="relative z-10 bg-white border border-gray-200 rounded-2xl shadow-2xl p-6 sm:p-8 max-w-2xl w-full">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-2xl font-playfair font-bold text-gray-900">Add Staff User</h3>
                    <p class="text-gray-500 text-sm mt-1">Create a new staff account and assign a staff role.</p>
                </div>
                <button type="button" onclick="hideAddStaffModal()" class="text-gray-400 hover:text-gray-600" aria-label="Close add staff modal">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="form_context" value="add_staff">

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-900 focus:border-amber-400 outline-none transition-colors"
                        placeholder="Full name">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-900 focus:border-amber-400 outline-none transition-colors"
                        placeholder="email@example.com">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Password</label>
                        <div class="relative">
                            <input id="modal-add-user-password" type="password" name="password" required minlength="8"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 pr-11 text-gray-900 focus:border-amber-400 outline-none transition-colors"
                                placeholder="Minimum 8 characters">
                            <button type="button" onclick="togglePasswordField('modal-add-user-password', 'modal-pass-eye-open', 'modal-pass-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg id="modal-pass-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="modal-pass-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Confirm Password</label>
                        <div class="relative">
                            <input id="modal-add-user-cpassword" type="password" name="password_confirmation" required minlength="8"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 pr-11 text-gray-900 focus:border-amber-400 outline-none transition-colors"
                                placeholder="Repeat password">
                            <button type="button" onclick="togglePasswordField('modal-add-user-cpassword', 'modal-confirm-eye-open', 'modal-confirm-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg id="modal-confirm-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="modal-confirm-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Role</label>
                    <select name="role" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-900 focus:border-amber-400 outline-none transition-colors" required>
                        <option value="">Select staff role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ \Illuminate\Support\Str::headline($role->name) }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="hideAddStaffModal()" class="px-5 py-2.5 bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-xl transition-colors">
                        Create Staff
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let selectedVerifyFormId = null;
    const userUpdateUrlTemplate = @js(url('admin/users/__USER__'));

    function togglePasswordField(inputId, eyeOpenId, eyeClosedId) {
        const input = document.getElementById(inputId);
        const eyeOpen = document.getElementById(eyeOpenId);
        const eyeClosed = document.getElementById(eyeClosedId);

        if (!input || !eyeOpen || !eyeClosed) {
            return;
        }

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }
    }

    function showAddStaffModal() {
        document.getElementById('addStaffModal').classList.remove('hidden');
    }

    function hideAddStaffModal() {
        document.getElementById('addStaffModal').classList.add('hidden');
    }

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

    function showUserViewModal(user) {
        const modal = document.getElementById('viewUserModal');
        const verifiedBadge = user.verified
            ? '<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">Verified</span>'
            : '<span class="px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200">Pending</span>';
        const statusBadge = user.archived
            ? '<span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">Archived</span>'
            : '<span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">Active</span>';

        document.getElementById('viewUserName').textContent = user.name || '';
        document.getElementById('viewUserEmail').textContent = user.email || '';
        document.getElementById('viewUserPhone').textContent = user.phone || 'Not set';
        document.getElementById('viewUserCreated').textContent = user.created_at || '—';
        document.getElementById('viewUserUpdated').textContent = user.updated_at || '—';
        document.getElementById('viewUserAddress').textContent = user.shipping_address || 'Not set';
        document.getElementById('viewUserNotifyOrders').textContent = user.notify_order_updates ? 'Enabled' : 'Disabled';
        document.getElementById('viewUserNotifyPromotions').textContent = user.notify_promotions ? 'Enabled' : 'Disabled';
        document.getElementById('viewUserNotifyLoyalty').textContent = user.notify_loyalty ? 'Enabled' : 'Disabled';
        document.getElementById('viewUserVerified').innerHTML = verifiedBadge;
        document.getElementById('viewUserStatus').innerHTML = statusBadge;

        const rolesWrap = document.getElementById('viewUserRoles');
        rolesWrap.innerHTML = '';
        const roles = Array.isArray(user.roles) ? user.roles : [];
        if (roles.length === 0) {
            rolesWrap.innerHTML = '<span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">Customer</span>';
        } else {
            roles.forEach((roleName) => {
                const chip = document.createElement('span');
                chip.className = 'px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200';
                chip.textContent = roleName.replaceAll('_', ' ').replace(/\b\w/g, c => c.toUpperCase());
                rolesWrap.appendChild(chip);
            });
        }

        modal.classList.remove('hidden');
    }

    function hideUserViewModal() {
        document.getElementById('viewUserModal').classList.add('hidden');
    }

    function showUserEditModal(user) {
        const modal = document.getElementById('editUserModal');
        const form = document.getElementById('editUserForm');
        document.getElementById('editUserSubtitle').textContent = `Update ${user.name}'s details and role assignment.`;
        document.getElementById('editUserIdInput').value = user.id || '';
        document.getElementById('editUserNameInput').value = user.name || '';
        document.getElementById('editUserEmailInput').value = user.email || '';
        document.getElementById('editUserRoleInput').value = user.role || '';
        document.getElementById('editUserPassword').value = '';
        document.getElementById('editUserPasswordConfirmation').value = '';

        form.action = userUpdateUrlTemplate.replace('__USER__', String(user.id));
        modal.classList.remove('hidden');
    }

    function hideUserEditModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }

    @if(request()->boolean('openAddStaff') || old('form_context') === 'add_staff')
    showAddStaffModal();
    @endif

    @if(old('form_context') === 'edit_user' && old('edit_user_id'))
    showUserEditModal({
        id: @js(old('edit_user_id')),
        name: @js(old('name')),
        email: @js(old('email')),
        role: @js(old('role')),
    });
    @endif
</script>
@endsection
