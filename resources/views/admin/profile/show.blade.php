@extends('admin.admin_layout')

@section('title', 'My Profile | Lumina')

@section('content')
<div class="max-w-3xl mx-auto">
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-gray-900">My Profile</h1>
                <p class="text-gray-600 mt-2">Review your account details first, then choose edit when needed.</p>
            </div>
            <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-2.5 text-black font-semibold hover:bg-amber-300 transition-colors">
                Edit Profile
            </a>
        </div>

        <div class="space-y-4">
            <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Full Name</p>
                <p class="mt-1 text-gray-900 font-semibold">{{ $user->name }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Email (Read-only)</p>
                <p class="mt-1 text-gray-700 font-semibold">{{ $user->email }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Password & Security</p>
                <p class="mt-1 text-gray-700 text-sm">Password updates require your current password and must include at least 8 characters with uppercase, lowercase, and a number.</p>
            </div>
        </div>
    </div>
</div>
@endsection
