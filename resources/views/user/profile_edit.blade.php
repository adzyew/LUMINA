@extends('layouts.customer')

@section('title', 'Settings | Lumina')

@section('content')
    @php
        $nameParts = preg_split('/\s+/', trim($user->name ?? ''), 2);
        $firstName = old('first_name', $user->first_name ?? ($nameParts[0] ?? ''));
        $middleName = old('middle_name', $user->middle_name ?? '');
        $lastName = old('last_name', $user->last_name ?? ($nameParts[1] ?? ''));
        $suffix = old('suffix', $user->suffix ?? '');
    @endphp

    <div class="container mx-auto px-4 sm:px-6 lg:px-4 py-10 max-w-7xl">
        <div class="flex items-center justify-between gap-3 mb-6">
            <h1 class="text-3xl font-playfair font-bold text-gray-900">Settings</h1>
            <button id="editProfileBtn" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-amber-300 bg-amber-100 hover:bg-amber-100 text-amber-700 font-semibold transition-colors">
                Edit Profile
            </button>
        </div>

        @if($errors->any())
            <div class="mb-5 p-4 bg-red-100 border border-red-200 rounded-xl text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="profileSettingsForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl p-5 sm:p-6 border border-gray-200 shadow-sm space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="active_tab" value="all">

            <fieldset id="profileSettingsFields" disabled class="space-y-6">
                <section id="personal-details-section" class="rounded-xl border border-gray-200 p-5 sm:p-6 space-y-5">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Personal Details</h2>
                        <p class="text-sm text-gray-500 mt-1">Update your basic account information and profile photo.</p>
                    </div>

                    <div class="rounded-xl border border-gray-200 overflow-hidden">
                        <button id="personalDetailsToggle" type="button" class="w-full flex items-center justify-between px-4 py-4 text-left hover:bg-gray-50 transition-colors" data-edit-only>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Account information</p>
                                <p class="text-xs text-gray-500 mt-1">Name, phone number, email, and profile photo.</p>
                            </div>
                            <svg id="personalDetailsChevron" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m9 6 6 6-6 6"/>
                            </svg>
                        </button>

                        <div id="personalDetailsPanel" class="hidden border-t border-gray-200 p-4 sm:p-5 bg-gray-50">
                            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                                <div class="xl:col-span-2 space-y-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600 mb-2">First Name</label>
                                            <input type="text" name="first_name" value="{{ $firstName }}" required class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600 mb-2">Middle Name (Optional)</label>
                                            <input type="text" name="middle_name" value="{{ $middleName }}" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600 mb-2">Last Name</label>
                                            <input type="text" name="last_name" value="{{ $lastName }}" required class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600 mb-2">Suffix (Optional)</label>
                                            <input type="text" name="suffix" value="{{ $suffix }}" maxlength="20" placeholder="Jr., Sr., III" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">Email</label>
                                        <input type="text" id="email_display" value="{{ $user->email }}" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                                        <p class="text-xs text-gray-500 mt-1">Email cannot be changed.</p>
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-600 mb-2">Phone</label>
                                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required autocomplete="tel" placeholder="09XXXXXXXXX" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Profile Photo</h3>
                                    <div class="rounded-lg border border-gray-200 bg-white p-4 h-full min-h-70 flex flex-col">
                                        <div class="flex-1 flex justify-center items-center gap-3">
                                            @if($user->profile_photo_url)
                                                <img src="{{ $user->profile_photo_url }}" alt="Profile" class="w-40 h-40 rounded-full object-cover border border-amber-300/40">
                                            @else
                                                <div class="w-40 h-40 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center text-black text-2xl font-bold">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <label class="mt-3 self-end inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border border-gray-300 text-sm text-gray-700 cursor-pointer hover:bg-gray-100 transition-colors" data-edit-only>
                                            Choose File
                                            <input type="file" name="profile_photo" accept="image/*" class="hidden">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="address-details-section" class="rounded-xl border border-gray-200 p-5 sm:p-6 space-y-5">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Address</h2>
                        <p class="text-sm text-gray-500 mt-1">Set your default delivery address details.</p>
                    </div>

                    <div class="rounded-xl border border-gray-200 overflow-hidden">
                        <button id="addressDetailsToggle" type="button" class="w-full flex items-center justify-between px-4 py-4 text-left hover:bg-gray-50 transition-colors" data-edit-only>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Delivery address and preferences</p>
                                <p class="text-xs text-gray-500 mt-1">Address, notifications, and rewards settings.</p>
                            </div>
                            <svg id="addressDetailsChevron" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m9 6 6 6-6 6"/>
                            </svg>
                        </button>

                        <div id="addressDetailsPanel" class="hidden border-t border-gray-200 p-4 sm:p-5 bg-gray-50">
                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                <div class="space-y-4" id="address-section">
                                    <input type="text" name="shipping_street" value="{{ old('shipping_street', $user->shipping_street) }}" placeholder="Street, building, house no." class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                    <input type="text" name="shipping_secondary_address" value="{{ old('shipping_secondary_address', $user->shipping_secondary_address) }}" placeholder="Secondary address (optional)" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <select id="city" name="shipping_city" class="w-full bg-white border border-gray-300 rounded-xl p-3 text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                            <option value="">Loading cities...</option>
                                        </select>
                                        <select id="barangay" name="shipping_barangay" class="w-full bg-white border border-gray-300 rounded-xl p-3 text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                            <option value="">Select Barangay</option>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <input type="text" id="zip" name="shipping_postal_code" value="{{ old('shipping_postal_code', $user->shipping_postal_code) }}" readonly class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-gray-500 cursor-not-allowed">
                                        <input type="text" name="shipping_country" value="Philippines" readonly class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                                    </div>
                                    <input type="hidden" name="shipping_region" value="National Capital Region (NCR)">
                                </div>

                                <div class="space-y-4" id="notifications-section">
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Notifications & Rewards</h3>
                                    <div class="rounded-xl border border-gray-200 bg-white p-4 space-y-3">
                                        <label class="flex items-center gap-3 text-sm text-gray-700">
                                            <input type="hidden" name="notify_order_updates" value="0">
                                            <input type="checkbox" name="notify_order_updates" value="1" class="h-4 w-4 rounded border-gray-300 text-amber-500" {{ old('notify_order_updates', $user->notify_order_updates) ? 'checked' : '' }}>
                                            Notify me about order updates
                                        </label>
                                        <label class="flex items-center gap-3 text-sm text-gray-700">
                                            <input type="hidden" name="notify_promotions" value="0">
                                            <input type="checkbox" name="notify_promotions" value="1" class="h-4 w-4 rounded border-gray-300 text-amber-500" {{ old('notify_promotions', $user->notify_promotions) ? 'checked' : '' }}>
                                            Send me promotions and offers
                                        </label>
                                        <label class="flex items-center gap-3 text-sm text-gray-700">
                                            <input type="hidden" name="notify_loyalty" value="0">
                                            <input type="checkbox" name="notify_loyalty" value="1" class="h-4 w-4 rounded border-gray-300 text-amber-500" {{ old('notify_loyalty', $user->notify_loyalty) ? 'checked' : '' }}>
                                            Notify me about loyalty points updates
                                        </label>
                                    </div>

                                    <div id="rewards-section" class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-amber-700 font-medium">Points Balance</span>
                                            <span class="text-amber-700 font-bold">{{ $user->points_balance }} pts</span>
                                        </div>
                                        <p class="text-xs text-amber-700 mt-2">Referral link:</p>
                                        <input type="text" readonly value="{{ url('/register') }}?ref={{ $user->id }}" class="mt-1 w-full px-3 py-2 bg-white border border-amber-200 rounded-lg text-xs text-gray-600">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="security-section" class="rounded-xl border border-gray-200 p-5 sm:p-6 space-y-5">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Password and Security</h2>
                        <p class="text-sm text-gray-500 mt-1">Manage your login and account security settings.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-xl border border-gray-200 overflow-hidden">
                            <button id="securityChangePasswordToggle" type="button" class="w-full flex items-center justify-between px-4 py-4 text-left hover:bg-gray-50 transition-colors" data-edit-only>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Change password</p>
                                    <p class="text-xs text-gray-500 mt-1">Update your password with strong validation.</p>
                                </div>
                                <svg id="securityChangePasswordChevron" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m9 6 6 6-6 6"/>
                                </svg>
                            </button>

                            <div id="securityChangePasswordPanel" class="hidden border-t border-gray-200 p-4 sm:p-5 space-y-4 bg-gray-50">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Current Password</label>
                                    <div class="relative">
                                        <input id="profile-current-password" type="password" name="current_password" autocomplete="current-password" maxlength="72" class="w-full px-4 py-3 pr-11 bg-white border border-gray-300 rounded-xl text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                        <button type="button" onclick="togglePasswordField('profile-current-password', 'profile-current-eye-open', 'profile-current-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility" data-edit-only>
                                            <svg id="profile-current-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 0 1 6 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            <svg id="profile-current-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 0 1 1.563-3.029m5.858.908a3 3 0 1 1 4.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532 3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0 1 12 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 0 1-4.132 5.411m0 0L21 21" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">New Password</label>
                                        <div class="relative">
                                            <input id="profile-new-password" type="password" name="new_password" autocomplete="new-password" maxlength="72" minlength="8" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,72}" title="Use 8 to 72 characters with uppercase, lowercase, and a number." class="w-full px-4 py-3 pr-11 bg-white border border-gray-300 rounded-xl text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                            <button type="button" onclick="togglePasswordField('profile-new-password', 'profile-new-eye-open', 'profile-new-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility" data-edit-only>
                                                <svg id="profile-new-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 0 1 6 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                <svg id="profile-new-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 0 1 1.563-3.029m5.858.908a3 3 0 1 1 4.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532 3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0 1 12 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 0 1-4.132 5.411m0 0L21 21" /></svg>
                                            </button>
                                        </div>
                                        <p id="profile-password-strength" class="hidden text-xs mt-2 text-gray-500">Password strength: Weak</p>
                                        <p id="profile-password-rules" class="hidden text-xs mt-1 text-gray-500">Use 8 to 72 characters with uppercase, lowercase, and a number.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">Confirm New Password</label>
                                        <div class="relative">
                                            <input id="profile-confirm-password" type="password" name="new_password_confirmation" autocomplete="new-password" maxlength="72" minlength="8" class="w-full px-4 py-3 pr-11 bg-white border border-gray-300 rounded-xl text-gray-900 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                                            <button type="button" onclick="togglePasswordField('profile-confirm-password', 'profile-confirm-eye-open', 'profile-confirm-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility" data-edit-only>
                                                <svg id="profile-confirm-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 0 1 6 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                <svg id="profile-confirm-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 0 1 1.563-3.029m5.858.908a3 3 0 1 1 4.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532 3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0 1 12 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 0 1-4.132 5.411m0 0L21 21" /></svg>
                                            </button>
                                        </div>
                                        <p id="profile-password-match" class="hidden text-xs mt-2">Passwords match.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-4 py-4 border-b border-gray-200 bg-gray-50">
                                <p class="text-sm font-semibold text-gray-900">Security checks</p>
                                <p class="text-xs text-gray-500 mt-1">Review your account security status.</p>
                            </div>
                            <div class="divide-y divide-gray-200">
                                <div class="px-4 py-3 flex items-center justify-between gap-3">
                                    <span class="text-sm text-gray-700">Email verification</span>
                                    <span class="text-sm font-semibold {{ $user->email_verified_at ? 'text-green-600' : 'text-amber-600' }}">{{ $user->email_verified_at ? 'Verified' : 'Pending' }}</span>
                                </div>
                                <div class="px-4 py-3 flex items-center justify-between gap-3">
                                    <span class="text-sm text-gray-700">Connected provider</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $user->provider_name ? ucfirst($user->provider_name) : 'Email and password' }}</span>
                                </div>
                                <div class="px-4 py-3 flex items-center justify-between gap-3">
                                    <span class="text-sm text-gray-700">Member since</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ optional($user->created_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </fieldset>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button id="saveProfileBtn" type="submit" class="hidden px-6 py-3 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors">Save Changes</button>
                <button id="cancelEditBtn" type="button" class="hidden px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 border border-gray-200 transition-colors">Cancel</button>
            </div>
        </form>

        <form id="deactivateAccountForm" method="POST" action="{{ route('account.deactivate') }}" class="hidden">
            @csrf
        </form>

    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.3/build/js/intlTelInput.min.js"></script>
<script>
function normalizePhilippineMobile(value) {
    const digits = (value || '').replace(/\D/g, '');

    if (/^09\d{9}$/.test(digits)) return digits;
    if (/^9\d{9}$/.test(digits)) return `0${digits}`;
    if (/^639\d{9}$/.test(digits)) return `0${digits.slice(2)}`;

    return digits;
}

function togglePasswordField(inputId, eyeOpenId, eyeClosedId) {
    const input = document.getElementById(inputId);
    const eyeOpen = document.getElementById(eyeOpenId);
    const eyeClosed = document.getElementById(eyeClosedId);
    if (!input || !eyeOpen || !eyeClosed) return;

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

(function () {
    const form = document.getElementById('profileSettingsForm');
    const fieldset = document.getElementById('profileSettingsFields');
    const editBtn = document.getElementById('editProfileBtn');
    const saveBtn = document.getElementById('saveProfileBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const personalDetailsToggle = document.getElementById('personalDetailsToggle');
    const personalDetailsPanel = document.getElementById('personalDetailsPanel');
    const personalDetailsChevron = document.getElementById('personalDetailsChevron');
    const addressDetailsToggle = document.getElementById('addressDetailsToggle');
    const addressDetailsPanel = document.getElementById('addressDetailsPanel');
    const addressDetailsChevron = document.getElementById('addressDetailsChevron');
    const securityPasswordToggle = document.getElementById('securityChangePasswordToggle');
    const securityPasswordPanel = document.getElementById('securityChangePasswordPanel');
    const securityPasswordChevron = document.getElementById('securityChangePasswordChevron');
    const hasValidationErrors = @json($errors->any());
    const editOnlyEls = document.querySelectorAll('[data-edit-only]');

    const phoneInput = document.getElementById('phone');
    const firstNameInput = form?.querySelector('input[name="first_name"]');
    const middleNameInput = form?.querySelector('input[name="middle_name"]');
    const lastNameInput = form?.querySelector('input[name="last_name"]');
    const suffixInput = form?.querySelector('input[name="suffix"]');
    const hasPersonalErrors = @json(
        $errors->has('first_name') ||
        $errors->has('middle_name') ||
        $errors->has('last_name') ||
        $errors->has('suffix') ||
        $errors->has('phone') ||
        $errors->has('profile_photo')
    );
    const hasAddressErrors = @json(
        $errors->has('shipping_street') ||
        $errors->has('shipping_secondary_address') ||
        $errors->has('shipping_city') ||
        $errors->has('shipping_barangay') ||
        $errors->has('shipping_region') ||
        $errors->has('shipping_postal_code') ||
        $errors->has('notify_order_updates') ||
        $errors->has('notify_promotions') ||
        $errors->has('notify_loyalty')
    );
    const hasSecurityErrors = @json(
        $errors->has('current_password') ||
        $errors->has('new_password') ||
        $errors->has('new_password_confirmation')
    );

    const sanitizeNameInput = (value) => (value || '')
        .replace(/[^A-Za-z\s]/g, '')
        .replace(/\s{2,}/g, ' ');

    const sanitizeSuffixInput = (value) => (value || '')
        .replace(/[^A-Za-z0-9.\-\s]/g, '')
        .replace(/\s{2,}/g, ' ');
    if (phoneInput && window.intlTelInput) {
        window.intlTelInput(phoneInput, {
            initialCountry: 'ph',
            onlyCountries: ['ph'],
            separateDialCode: false,
            nationalMode: false,
            autoPlaceholder: 'polite',
            dropdownContainer: document.body,
        });
    }

    function setEditMode(enabled) {
        if (!fieldset || !editBtn || !saveBtn || !cancelBtn) return;

        fieldset.disabled = !enabled;
        editBtn.classList.toggle('hidden', enabled);
        saveBtn.classList.toggle('hidden', !enabled);
        cancelBtn.classList.toggle('hidden', !enabled);

        editOnlyEls.forEach((el) => {
            el.classList.toggle('opacity-60', !enabled);
            el.classList.toggle('pointer-events-none', !enabled);
        });

        const emailDisplay = document.getElementById('email_display');
        if (emailDisplay) emailDisplay.disabled = true;
    }

    function setSecurityPasswordPanel(open) {
        if (!securityPasswordPanel || !securityPasswordChevron) return;
        securityPasswordPanel.classList.toggle('hidden', !open);
        securityPasswordChevron.classList.toggle('rotate-90', open);
    }

    function setCollapsiblePanel(panel, chevron, open) {
        if (!panel || !chevron) return;
        panel.classList.toggle('hidden', !open);
        chevron.classList.toggle('rotate-90', open);
    }

    setEditMode(Boolean(hasValidationErrors));
    setCollapsiblePanel(personalDetailsPanel, personalDetailsChevron, Boolean(hasPersonalErrors));
    setCollapsiblePanel(addressDetailsPanel, addressDetailsChevron, Boolean(hasAddressErrors));
    setSecurityPasswordPanel(Boolean(hasSecurityErrors));

    editBtn?.addEventListener('click', function () {
        setEditMode(true);
    });

    securityPasswordToggle?.addEventListener('click', function () {
        if (!securityPasswordPanel) return;
        const willOpen = securityPasswordPanel.classList.contains('hidden');
        setSecurityPasswordPanel(willOpen);
    });

    personalDetailsToggle?.addEventListener('click', function () {
        if (!personalDetailsPanel) return;
        const willOpen = personalDetailsPanel.classList.contains('hidden');
        setCollapsiblePanel(personalDetailsPanel, personalDetailsChevron, willOpen);
    });

    addressDetailsToggle?.addEventListener('click', function () {
        if (!addressDetailsPanel) return;
        const willOpen = addressDetailsPanel.classList.contains('hidden');
        setCollapsiblePanel(addressDetailsPanel, addressDetailsChevron, willOpen);
    });

    cancelBtn?.addEventListener('click', function () {
        window.location.reload();
    });

    form?.addEventListener('submit', function (event) {
        if (firstNameInput) firstNameInput.value = sanitizeNameInput(firstNameInput.value).trim();
        if (middleNameInput) middleNameInput.value = sanitizeNameInput(middleNameInput.value).trim();
        if (lastNameInput) lastNameInput.value = sanitizeNameInput(lastNameInput.value).trim();
        if (suffixInput) suffixInput.value = sanitizeSuffixInput(suffixInput.value).trim();
        if (phoneInput) {
            phoneInput.value = normalizePhilippineMobile(phoneInput.value);
        }

        if (!currentPassword || !newPassword || !confirmPassword) return;

        const currentValue = currentPassword.value.trim();
        const newValue = newPassword.value.trim();
        const confirmValue = confirmPassword.value.trim();
        const wantsPasswordChange = currentValue.length > 0 || newValue.length > 0 || confirmValue.length > 0;

        currentPassword.setCustomValidity('');
        newPassword.setCustomValidity('');
        confirmPassword.setCustomValidity('');

        if (!wantsPasswordChange) return;

        if (!currentValue) {
            currentPassword.setCustomValidity('Current password is required to change your password.');
        }
        if (!newValue) {
            newPassword.setCustomValidity('New password is required.');
        }
        if (!confirmValue) {
            confirmPassword.setCustomValidity('Please confirm your new password.');
        }

        updateProfilePasswordStrength(true);
        updateProfilePasswordMatch();

        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
        }
    });

    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');
    const zipInput = document.getElementById('zip');
    const currentCity = @json(old('shipping_city', $user->shipping_city));
    const currentBarangay = @json(old('shipping_barangay', $user->shipping_barangay));

    const zipCodes = {
        "City of Manila": "1000",
        "Quezon City": "1100",
        "City of Caloocan": "1400",
        "City of Makati": "1200",
        "City of Taguig": "1630",
        "City of Pasig": "1600",
        "City of Parañaque": "1700",
        "City of Las Piñas": "1740",
        "City of Mandaluyong": "1550",
        "City of Marikina": "1800",
        "City of Navotas": "1485",
        "City of Malabon": "1470",
        "City of Valenzuela": "1440",
        "City of San Juan": "1500",
        "City of Muntinlupa": "1770",
        "Pasay City": "1300",
        "Pateros": "1620"
    };

    if (citySelect && barangaySelect && zipInput) {
        fetch('https://psgc.gitlab.io/api/regions/130000000/cities-municipalities/')
            .then(response => response.json())
            .then(data => {
                citySelect.innerHTML = '<option value="">Select City</option>';
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.name;
                    option.textContent = city.name;
                    option.dataset.code = city.code;
                    citySelect.appendChild(option);
                });

                if (currentCity) {
                    citySelect.value = currentCity;
                    if (zipCodes[currentCity]) zipInput.value = zipCodes[currentCity];
                    citySelect.dispatchEvent(new Event('change'));
                }
            });

        citySelect.addEventListener('change', function () {
            const selectedOption = this.selectedOptions[0];
            if (!selectedOption) return;

            const cityCode = selectedOption.dataset.code;
            const cityName = selectedOption.value;

            barangaySelect.innerHTML = '<option value="">Loading barangays...</option>';
            zipInput.value = zipCodes[cityName] ?? '';

            if (!cityCode) {
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                return;
            }

            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
                .then(response => response.json())
                .then(data => {
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    data.forEach(brgy => {
                        const option = document.createElement('option');
                        option.value = brgy.name;
                        option.textContent = brgy.name;
                        barangaySelect.appendChild(option);
                    });

                    if (currentBarangay) {
                        const hasOption = Array.from(barangaySelect.options).some(opt => opt.value === currentBarangay);
                        if (!hasOption) {
                            const fallback = document.createElement('option');
                            fallback.value = currentBarangay;
                            fallback.textContent = currentBarangay;
                            barangaySelect.appendChild(fallback);
                        }
                        barangaySelect.value = currentBarangay;
                    }
                })
                .catch(() => {
                    barangaySelect.innerHTML = '<option value="">Failed to load</option>';
                });
        });
    }

    const currentPassword = document.getElementById('profile-current-password');
    const newPassword = document.getElementById('profile-new-password');
    const confirmPassword = document.getElementById('profile-confirm-password');
    const strengthLabel = document.getElementById('profile-password-strength');
    const rulesLabel = document.getElementById('profile-password-rules');
    const matchLabel = document.getElementById('profile-password-match');

    function updateProfilePasswordStrength(showOutput = false) {
        if (!newPassword || !strengthLabel || !rulesLabel) return;

        const value = newPassword.value || '';
        const hasMaxLength = value.length <= 72;
        const hasMinLength = value.length >= 8;
        const hasLower = /[a-z]/.test(value);
        const hasUpper = /[A-Z]/.test(value);
        const hasNumber = /\d/.test(value);
        const isStrong = hasMinLength && hasLower && hasUpper && hasNumber && hasMaxLength;

        if (!showOutput || value.length === 0) {
            strengthLabel.classList.add('hidden');
            rulesLabel.classList.add('hidden');
            newPassword.setCustomValidity('');
            return;
        }

        strengthLabel.classList.remove('hidden');
        rulesLabel.classList.remove('hidden');

        if (isStrong) {
            strengthLabel.textContent = 'Password strength: Strong';
            strengthLabel.className = 'text-xs mt-2 text-green-600';
            rulesLabel.textContent = 'Good password. Requirements complete.';
            rulesLabel.className = 'text-xs mt-1 text-green-600';
            newPassword.setCustomValidity('');
        } else {
            strengthLabel.textContent = 'Password strength: Weak';
            strengthLabel.className = 'text-xs mt-2 text-red-500';
            rulesLabel.textContent = 'Missing requirement: 8-72 chars, uppercase, lowercase, and number.';
            rulesLabel.className = 'text-xs mt-1 text-red-500';
            newPassword.setCustomValidity('Please use 8 to 72 characters with uppercase, lowercase, and a number.');
        }
    }

    function updateProfilePasswordMatch() {
        if (!newPassword || !confirmPassword || !matchLabel) return;

        const password = newPassword.value || '';
        const confirm = confirmPassword.value || '';

        if (confirm.length === 0) {
            matchLabel.classList.add('hidden');
            confirmPassword.setCustomValidity('');
            return;
        }

        matchLabel.classList.remove('hidden');

        if (password === confirm) {
            matchLabel.textContent = 'Passwords match.';
            matchLabel.className = 'text-xs mt-2 text-green-600';
            confirmPassword.setCustomValidity('');
        } else {
            matchLabel.textContent = 'Passwords do not match.';
            matchLabel.className = 'text-xs mt-2 text-red-500';
            confirmPassword.setCustomValidity('Passwords do not match.');
        }
    }

    if (newPassword) {
        newPassword.addEventListener('input', function () {
            updateProfilePasswordStrength(true);
            updateProfilePasswordMatch();
        });
        newPassword.addEventListener('blur', function () {
            updateProfilePasswordStrength(false);
        });
    }

    if (confirmPassword) {
        confirmPassword.addEventListener('input', updateProfilePasswordMatch);
        confirmPassword.addEventListener('blur', updateProfilePasswordMatch);
    }
})();
</script>
@endpush
