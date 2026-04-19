@extends('admin.admin_layout')

@section('title', 'My Profile | Lumina')

@section('content')
@php
    $nameParts = preg_split('/\s+/', trim($user->name ?? ''), 2);
    $firstName = old('first_name', $user->first_name ?? ($nameParts[0] ?? ''));
    $middleName = old('middle_name', $user->middle_name ?? '');
    $lastName = old('last_name', $user->last_name ?? ($nameParts[1] ?? ''));
    $suffix = old('suffix', $user->suffix ?? '');
@endphp
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-playfair font-bold text-gray-900">Edit Profile</h1>
        <p class="text-gray-600 mt-2">Update your display name and password. Your email is locked.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                    <input
                        id="first_name"
                        type="text"
                        name="first_name"
                        value="{{ $firstName }}"
                        pattern="^(?=.*[A-Za-z])(?!.*-.*-)(?!-)(?!.*-$)[A-Za-z\s-]+$"
                        title="First name may contain letters, spaces, and one hyphen only."
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 outline-none focus:border-amber-400"
                    >
                </div>

                <div>
                    <label for="middle_name" class="block text-sm font-semibold text-gray-700 mb-2">Middle Name (Optional)</label>
                    <input
                        id="middle_name"
                        type="text"
                        name="middle_name"
                        value="{{ $middleName }}"
                        pattern="^$|^(?=.*[A-Za-z])(?!.*-.*-)(?!-)(?!.*-$)[A-Za-z\s-]+$"
                        title="Middle name may contain letters, spaces, and one hyphen only."
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 outline-none focus:border-amber-400"
                    >
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                    <input
                        id="last_name"
                        type="text"
                        name="last_name"
                        value="{{ $lastName }}"
                        pattern="^(?=.*[A-Za-z])(?!.*-.*-)(?!-)(?!.*-$)[A-Za-z\s-]+$"
                        title="Last name may contain letters, spaces, and one hyphen only."
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 outline-none focus:border-amber-400"
                    >
                </div>

                <div>
                    <label for="suffix" class="block text-sm font-semibold text-gray-700 mb-2">Suffix (Optional)</label>
                    <input
                        id="suffix"
                        type="text"
                        name="suffix"
                        value="{{ $suffix }}"
                        maxlength="20"
                        placeholder="Jr., Sr., III"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 outline-none focus:border-amber-400"
                    >
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email (Read-only)</label>
                <input
                    id="email"
                    type="email"
                    value="{{ $user->email }}"
                    readonly
                    disabled
                    class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-3 text-gray-500 cursor-not-allowed"
                >
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">Change Password</h2>
                <p class="text-sm text-gray-600 mb-4">Leave these fields blank if you do not want to change your password.</p>

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                        <input
                            id="current_password"
                            type="password"
                            name="current_password"
                            autocomplete="current-password"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 outline-none focus:border-amber-400"
                        >
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <input
                            id="new_password"
                            type="password"
                            name="new_password"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 outline-none focus:border-amber-400"
                        >
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                        <input
                            id="new_password_confirmation"
                            type="password"
                            name="new_password_confirmation"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 outline-none focus:border-amber-400"
                        >
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-3 text-black font-semibold hover:bg-amber-300 transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-3 text-gray-700 font-semibold hover:bg-gray-100 transition-colors ml-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
