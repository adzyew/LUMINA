@extends('layouts.customer')

@section('title', 'Profile | Lumina')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-10 py-12 max-w-7xl">
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-playfair font-bold text-white">Profile Settings</h1>
                    <p class="text-gray-400">Manage your personal details and preferences.</p>
                </div>
                
            </div>

            @if(session('success'))
                <div class="p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 rounded-3xl p-6 sm:p-8 border border-white/10 shadow-xl text-white space-y-6">
                @csrf
                @method('PUT')

                @php
                    $nameParts = preg_split('/\s+/', trim($user->name ?? ''), 2);
                    $firstName = old('first_name', $nameParts[0] ?? '');
                    $lastName = old('last_name', $nameParts[1] ?? '');
                @endphp

                <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                    <aside class="border-b border-white/10 pb-4 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-4">
                        <nav class="space-y-1" role="tablist">
                            <button type="button" class="tab-btn w-full flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-amber-300 bg-white/5 transition" data-tab="general">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                                Account Information
                            </button>
                            <button type="button" class="tab-btn w-full flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-gray-400 hover:bg-white/5 transition" data-tab="security">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                                Change Password
                            </button>
                            <button type="button" class="tab-btn w-full flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-gray-400 hover:bg-white/5 transition" data-tab="address">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                                Address
                            </button>
                            <button type="button" class="tab-btn w-full flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-gray-400 hover:bg-white/5 transition" data-tab="notifications">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                                Notification
                            </button>
                            <button type="button" class="tab-btn w-full flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-gray-400 hover:bg-white/5 transition" data-tab="rewards">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                            </svg>
                                Rewards
                            </button>
                            <button type="button" class="tab-btn w-full flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-gray-400 hover:bg-white/5 transition" data-tab="deactivate">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.25-8.25-3.286Zm0 13.036h.008v.008H12v-.008Z" />
                            </svg>
                                Deactivate
                            </button>
                        </nav>
                    </aside>

                    <div class="space-y-6">
                        <div class="tab-panel" data-panel="general">
                            <div class="space-y-4">
                                <div class="flex flex-col items-start gap-6 sm:flex-row sm:items-center">
                                    <div class="relative shrink-0">
                                        @if($user->profile_photo_url)
                                            <img src="{{ $user->profile_photo_url }}" alt="Profile" class="w-24 h-24 rounded-full object-cover border border-amber-300/40">
                                        @else
                                            <div class="w-24 h-24 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center text-black text-2xl font-bold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            
                                        @endif
                                        <label class="absolute -bottom-1 -right-1 w-9 h-9 bg-amber-300/80 rounded-full flex items-center justify-center cursor-pointer hover:bg-amber-400 transition-colors shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                            <input type="file" name="profile_photo" accept="image/*" class="hidden" id="profile_photo">
                                        </label>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Upload a new profile photo.</p>
                                        <p class="text-xs text-gray-500">JPG or PNG, max 2MB.</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400 mb-2">First Name</label>
                                        <input type="text" name="first_name" value="{{ $firstName }}" required
                                            class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-amber-300 focus:ring-1 focus:ring-amber-300 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400 mb-2">Last Name</label>
                                        <input type="text" name="last_name" value="{{ $lastName }}" required
                                            class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-amber-300 focus:ring-1 focus:ring-amber-300 transition-colors">
                                    </div>
                                </div>

                                <div>
                                    <label for="email_display" class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                                    <input type="text" id="email_display" value="{{ $user->email }}" disabled
                                        class="w-full px-4 py-3 bg-black/30 border border-white/10 rounded-xl text-gray-500 cursor-not-allowed">
                                    <p class="text-xs text-gray-500 mt-1">Email cannot be changed.</p>
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-400 mb-2">Phone</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required autocomplete="tel"
                                        class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-amber-300 focus:ring-1 focus:ring-amber-300 transition-colors"
                                        placeholder="+63 917 123 4567">
                                    <p class="text-xs text-gray-500 mt-1">Use Philippine format: <code>09XXXXXXXXX</code>.</p>
                                </div>
                            </div>
                        </div>

                        <div class="tab-panel hidden" data-panel="security">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Current Password</label>
                                    <div class="relative">
                                        <input id="profile-current-password" type="password" name="current_password" autocomplete="current-password"
                                            class="w-full px-4 py-3 pr-11 bg-black/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-amber-300 focus:ring-1 focus:ring-amber-300 transition-colors">
                                        <button type="button" onclick="togglePasswordField('profile-current-password', 'profile-current-eye-open', 'profile-current-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility">
                                            <svg id="profile-current-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 0 1 6 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg id="profile-current-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 0 1 1.563-3.029m5.858.908a3 3 0 1 1 4.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532 3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0 1 12 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 0 1-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400 mb-2">New Password</label>
                                        <div class="relative">
                                            <input id="profile-new-password" type="password" name="new_password" autocomplete="new-password"
                                                class="w-full px-4 py-3 pr-11 bg-black/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-amber-300 focus:ring-1 focus:ring-amber-300 transition-colors">
                                            <button type="button" onclick="togglePasswordField('profile-new-password', 'profile-new-eye-open', 'profile-new-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility">
                                                <svg id="profile-new-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 0 1 6 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <svg id="profile-new-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 0 1 1.563-3.029m5.858.908a3 3 0 1 1 4.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532 3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0 1 12 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 0 1-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p id="profile-password-strength" class="hidden text-xs mt-2 text-gray-500">Password strength: Weak</p>
                                        <p id="profile-password-rules" class="hidden text-xs mt-1 text-gray-500">Use at least 8 characters with uppercase, lowercase, and a number.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400 mb-2">Confirm New Password</label>
                                        <div class="relative">
                                            <input id="profile-confirm-password" type="password" name="new_password_confirmation" autocomplete="new-password"
                                                class="w-full px-4 py-3 pr-11 bg-black/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-amber-300 focus:ring-1 focus:ring-amber-300 transition-colors">
                                            <button type="button" onclick="togglePasswordField('profile-confirm-password', 'profile-confirm-eye-open', 'profile-confirm-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility">
                                                <svg id="profile-confirm-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 0 1 6 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <svg id="profile-confirm-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 0 1 1.563-3.029m5.858.908a3 3 0 1 1 4.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532 3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0 1 12 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 0 1-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p id="profile-password-match" class="hidden text-xs mt-2">Passwords match.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-panel hidden" data-panel="address">
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-400">Default Shipping Address (Metro Manila Only)</label>
                                <input type="text" name="shipping_street"
                                    value="{{ old('shipping_street', $user->shipping_street) }}"
                                    placeholder="Street, building, house no."
                                    class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-xl text-white">

                                <input type="text" name="shipping_secondary_address"
                                    value="{{ old('shipping_secondary_address', $user->shipping_secondary_address) }}"
                                    placeholder="Secondary address (optional: unit, landmark, floor)"
                                    class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-xl text-white">

                                <select id="city" name="shipping_city" required
                                    class="w-full bg-black/50 border border-white/10 rounded-xl p-3 text-white">
                                    <option value="">Loading cities...</option>
                                </select>

                                <select id="barangay" name="shipping_barangay" required
                                    class="w-full bg-black/50 border border-white/10 rounded-xl p-3 text-white">
                                    <option value="">Select Barangay</option>
                                </select>

                                <input type="text" id="zip" name="shipping_postal_code" value="{{ old('shipping_postal_code', $user->shipping_postal_code) }}" readonly class="w-full bg-black/50 border border-white/10 rounded-xl p-3 text-white">

                                <input type="hidden" name="shipping_region" value="National Capital Region (NCR)">
                                <input type="hidden" name="shipping_province" value="Metro Manila">

                                <input type="text" name="shipping_country"
                                    value="Philippines"
                                    readonly
                                    class="w-full mt-3 px-4 py-3 bg-black/30 border border-white/10 rounded-xl text-gray-500">
                            </div>
                        </div>

                        <div class="tab-panel hidden" data-panel="notifications">
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 text-sm text-gray-300">
                                    <input type="hidden" name="notify_order_updates" value="0">
                                    <input type="checkbox" name="notify_order_updates" value="1" class="h-4 w-4 rounded border-gray-600 bg-gray-900 text-amber-300" {{ old('notify_order_updates', $user->notify_order_updates) ? 'checked' : '' }}>
                                    Notify me about order updates (shipped or delivered)
                                </label>
                                <label class="flex items-center gap-3 text-sm text-gray-300">
                                    <input type="hidden" name="notify_promotions" value="0">
                                    <input type="checkbox" name="notify_promotions" value="1" class="h-4 w-4 rounded border-gray-600 bg-gray-900 text-amber-300" {{ old('notify_promotions', $user->notify_promotions) ? 'checked' : '' }}>
                                    Send me promotions and offers
                                </label>
                                <label class="flex items-center gap-3 text-sm text-gray-300">
                                    <input type="hidden" name="notify_loyalty" value="0">
                                    <input type="checkbox" name="notify_loyalty" value="1" class="h-4 w-4 rounded border-gray-600 bg-gray-900 text-amber-300" {{ old('notify_loyalty', $user->notify_loyalty) ? 'checked' : '' }}>
                                    Notify me about loyalty points updates
                                </label>
                            </div>
                        </div>

                        <div class="tab-panel hidden" data-panel="rewards">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between bg-black/50 rounded-xl border border-white/10 px-4 py-3">
                                    <span class="text-sm text-gray-400">Points Balance</span>
                                    <span class="text-amber-300 font-bold">{{ $user->points_balance }} pts</span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Referral Link</label>
                                    <input type="text" readonly value="{{ url('/register') }}?ref={{ $user->id }}" class="w-full px-4 py-3 bg-black/30 border border-white/10 rounded-xl text-gray-400">
                                    <p class="text-xs text-gray-500 mt-1">Share this link to earn future rewards.</p>
                                </div>
                            </div>
                        </div>

                        <div class="tab-panel hidden" data-panel="deactivate">
                            <div class="space-y-3">
                                <h3 class="text-lg font-semibold text-white">Deactivate Account</h3>
                                <p class="text-sm text-gray-400">Temporarily disable your account. You can contact support to reactivate it later.</p>
                                <button type="button" onclick="showDeactivateModal()" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors font-semibold">
                                    Deactivate Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-4 sm:flex-row sm:justify-end">
                    <button type="submit" class="px-6 py-3 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('profile.show') }}" class="px-6 py-3 bg-white/5 text-gray-300 font-semibold rounded-xl hover:bg-white/10 border border-white/10 text-center transition-colors">
                        Cancel
                    </a>
                </div>
            </form>

            <form id="deactivateAccountForm" method="POST" action="{{ route('account.deactivate') }}" class="hidden">
                @csrf
            </form>

            <div id="deactivateModal" class="fixed inset-0 z-50 hidden" aria-labelledby="deactivate-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-black/60" onclick="hideDeactivateModal()"></div>
                <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                    <div class="w-full max-w-md rounded-2xl bg-gray-900 text-white border border-white/10 shadow-xl">
                        <div class="p-6">
                            <h3 id="deactivate-title" class="text-lg font-semibold">Are you sure you want to deactivate your account?</h3>
                            <p class="mt-2 text-sm text-gray-400">This will disable your account. You can contact support to reactivate it later.</p>
                            <p class="mt-4 text-xs text-gray-500">Please wait <span id="deactivateCountdown" class="text-red-600 font-semibold">10</span> seconds to confirm.</p>
                        </div>
                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-white/10">
                            <button type="button" onclick="hideDeactivateModal()" class="px-4 py-2 rounded-lg bg-white/5 text-gray-300 hover:bg-white/10">Cancel</button>
                            <button id="deactivateConfirmBtn" type="submit" form="deactivateAccountForm" class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold opacity-50 cursor-not-allowed" disabled>
                                Deactivate
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.3/build/js/intlTelInput.min.js"></script>
<script>
function normalizePhilippineMobile(value) {
    const digits = (value || '').replace(/\D/g, '');

    if (/^09\d{9}$/.test(digits)) {
        return digits;
    }

    if (/^9\d{9}$/.test(digits)) {
        return `0${digits}`;
    }

    if (/^639\d{9}$/.test(digits)) {
        return `0${digits.slice(2)}`;
    }

    return digits;
}

const phoneInput = document.getElementById('phone');
if (phoneInput && window.intlTelInput) {
    window.intlTelInput(phoneInput, {
        initialCountry: 'ph',
        onlyCountries: ['ph'],
        separateDialCode: false,
        nationalMode: false,
        autoPlaceholder: 'polite',
        dropdownContainer: document.body,
    });

    const profileForm = phoneInput.closest('form');
    if (profileForm) {
        profileForm.addEventListener('submit', function () {
            phoneInput.value = normalizePhilippineMobile(phoneInput.value);
        });
    }
}

const citySelect = document.getElementById('city');
const barangaySelect = document.getElementById('barangay');
const zipInput = document.getElementById('zip');
const currentCity = @json(old('shipping_city', $user->shipping_city));
const currentBarangay = @json(old('shipping_barangay', $user->shipping_barangay));

// ZIP Mapping for Metro Manila
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

// 🔹 Load Metro Manila Cities (NCR region code: 130000000)
fetch('https://psgc.gitlab.io/api/regions/130000000/cities-municipalities/')
    .then(response => response.json())
    .then(data => {

        citySelect.innerHTML = '<option value="">Select City</option>';

        data.forEach(city => {
            const option = document.createElement('option');

            option.value = city.name;        // Save city NAME to DB
            option.textContent = city.name;
            option.dataset.code = city.code; // Store PSGC code for API use

            citySelect.appendChild(option);
        });

        if (currentCity) {
            citySelect.value = currentCity;
            if (zipCodes[currentCity]) {
                zipInput.value = zipCodes[currentCity];
            }
            citySelect.dispatchEvent(new Event('change'));
        }
    });

// 🔹 When City Changes → Load Barangays
citySelect.addEventListener('change', function () {

    const selectedOption = this.selectedOptions[0];
    if (!selectedOption) return;

    const cityCode = selectedOption.dataset.code;  // ← Already included here
    const cityName = selectedOption.value;

    barangaySelect.innerHTML = '<option value="">Loading barangays...</option>';

    // Auto fill ZIP
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
        .catch(error => {
            console.error('Error loading barangays:', error);
            barangaySelect.innerHTML = '<option value="">Failed to load</option>';
        });

});
</script>
<script>
    (function () {
        const buttons = document.querySelectorAll('.tab-btn');
        const panels = document.querySelectorAll('.tab-panel');

        function activateTab(tab) {
            buttons.forEach(btn => {
                const isActive = btn.dataset.tab === tab;
                btn.classList.toggle('text-amber-300', isActive);
                btn.classList.toggle('bg-white/5', isActive);
                btn.classList.toggle('text-gray-400', !isActive);
                if (!isActive) btn.classList.remove('bg-white/5');
            });

            panels.forEach(panel => {
                panel.classList.toggle('hidden', panel.dataset.panel !== tab);
            });
        }

        buttons.forEach(btn => {
            btn.addEventListener('click', () => activateTab(btn.dataset.tab));
        });

        activateTab('general');
    })();
</script>
<script>
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
        const newPassword = document.getElementById('profile-new-password');
        const confirmPassword = document.getElementById('profile-confirm-password');
        const strengthLabel = document.getElementById('profile-password-strength');
        const rulesLabel = document.getElementById('profile-password-rules');
        const matchLabel = document.getElementById('profile-password-match');

        function updateProfilePasswordStrength(showOutput = false) {
            if (!newPassword || !strengthLabel || !rulesLabel) return;

            const value = newPassword.value || '';
            const hasMinLength = value.length >= 8;
            const hasLower = /[a-z]/.test(value);
            const hasUpper = /[A-Z]/.test(value);
            const hasNumber = /\d/.test(value);
            const isStrong = hasMinLength && hasLower && hasUpper && hasNumber;

            if (!showOutput || value.length === 0) {
                strengthLabel.classList.add('hidden');
                rulesLabel.classList.add('hidden');
                strengthLabel.textContent = 'Password strength: Weak';
                strengthLabel.className = 'text-xs mt-2 text-gray-500';
                rulesLabel.textContent = 'Use at least 8 characters with uppercase, lowercase, and a number.';
                rulesLabel.className = 'text-xs mt-1 text-gray-500';
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
                rulesLabel.textContent = 'Missing requirement: 8+ chars, uppercase, lowercase, and number.';
                rulesLabel.className = 'text-xs mt-1 text-red-500';
                newPassword.setCustomValidity('Please use at least 8 characters with uppercase, lowercase, and a number.');
            }
        }

        function updateProfilePasswordMatch() {
            if (!newPassword || !confirmPassword || !matchLabel) return;

            const password = newPassword.value || '';
            const confirm = confirmPassword.value || '';

            if (confirm.length === 0) {
                matchLabel.classList.add('hidden');
                matchLabel.textContent = 'Passwords match.';
                matchLabel.className = 'hidden text-xs mt-2';
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
<script>
    let deactivateTimerId = null;

    function showDeactivateModal() {
        const modal = document.getElementById('deactivateModal');
        const countdownEl = document.getElementById('deactivateCountdown');
        const confirmBtn = document.getElementById('deactivateConfirmBtn');
        if (!modal || !countdownEl || !confirmBtn) return;

        let seconds = 10;
        countdownEl.textContent = String(seconds);
        confirmBtn.disabled = true;
        confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');

        modal.classList.remove('hidden');

        if (deactivateTimerId) {
            clearInterval(deactivateTimerId);
        }

        deactivateTimerId = setInterval(function () {
            seconds -= 1;
            countdownEl.textContent = String(seconds);
            if (seconds <= 0) {
                clearInterval(deactivateTimerId);
                deactivateTimerId = null;
                confirmBtn.disabled = false;
                confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }, 1000);
    }

    function hideDeactivateModal() {
        const modal = document.getElementById('deactivateModal');
        if (modal) modal.classList.add('hidden');
        if (deactivateTimerId) {
            clearInterval(deactivateTimerId);
            deactivateTimerId = null;
        }
    }
</script>
@endpush
