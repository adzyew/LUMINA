@extends('layouts.customer')

@section('title', 'My Profile | Lumina')

@section('content')
<div class="container mx-auto px-2 sm:px-6 lg:px-3 py-3">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl  sm:p-8 border border-gray-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-gray-900">My Profile</h1>
                <p class="text-gray-600 mt-1">Review your profile details. Click edit when you need to update information.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-[120px_1fr] gap-6 items-start mb-8">
            <div>
                @if($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" alt="Profile" class="w-24 h-24 rounded-full object-cover border border-amber-300/30">
                @else
                    <div class="w-24 h-24 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center text-black text-2xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl border border-gray-200 px-4 py-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Full Name</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $user->name }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl border border-gray-200 px-4 py-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Phone</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $user->phone ?: 'Not set' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl border border-gray-200 px-4 py-3 sm:col-span-2">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Email Address</p>
                    <p class="mt-1 font-semibold text-gray-700">{{ $user->email }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl border border-gray-200 px-4 py-3 sm:col-span-2">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Shipping Address</p>
                    <p class="mt-1 font-semibold text-gray-700">{{ $user->shipping_address ?: 'Not set' }}</p>
                </div>
            </div>
            <div class="sm:col-span-2 flex justify-end">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors">
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

