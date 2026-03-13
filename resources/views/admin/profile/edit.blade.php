@extends('admin.admin_layout')

@section('title', 'My Profile | Lumina')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Edit Profile</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Update your display name and password. Your email is locked.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-300">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full rounded-xl border border-gray-300 dark:border-white/10 bg-white dark:bg-gray-800 px-4 py-3 text-gray-900 dark:text-white outline-none focus:border-amber-400"
                >
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email (Read-only)</label>
                <input
                    id="email"
                    type="email"
                    value="{{ $user->email }}"
                    readonly
                    disabled
                    class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-100 dark:bg-gray-800/60 px-4 py-3 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                >
            </div>

            <div class="border-t border-gray-200 dark:border-white/10 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Change Password</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Leave these fields blank if you do not want to change your password.</p>

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                        <input
                            id="current_password"
                            type="password"
                            name="current_password"
                            autocomplete="current-password"
                            class="w-full rounded-xl border border-gray-300 dark:border-white/10 bg-white dark:bg-gray-800 px-4 py-3 text-gray-900 dark:text-white outline-none focus:border-amber-400"
                        >
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                        <input
                            id="new_password"
                            type="password"
                            name="new_password"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-gray-300 dark:border-white/10 bg-white dark:bg-gray-800 px-4 py-3 text-gray-900 dark:text-white outline-none focus:border-amber-400"
                        >
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                        <input
                            id="new_password_confirmation"
                            type="password"
                            name="new_password_confirmation"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-gray-300 dark:border-white/10 bg-white dark:bg-gray-800 px-4 py-3 text-gray-900 dark:text-white outline-none focus:border-amber-400"
                        >
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-3 text-black font-semibold hover:bg-amber-300 transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 dark:border-white/10 px-6 py-3 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-white/5 transition-colors ml-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
