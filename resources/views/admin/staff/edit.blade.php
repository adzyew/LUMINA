@extends('admin.admin_layout')

@section('title', 'Edit Staff | Lumina Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <header>
        <h1 class="text-4xl font-playfair font-bold text-gray-900">Edit Staff Member</h1>
        <p class="text-sm text-gray-600 mt-1">Update staff details and department assignment.</p>
    </header>

    <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
        <form action="{{ route('admin.staff.update', $user) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">New Password (leave blank to keep)</label>
                    <input type="password" name="password" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role / Department</label>
                <select name="role" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3">
                    <option value="">-- Select role --</option>
                    @foreach($roles as $label => $value)
                        <option value="{{ $value }}" @if(old('role', $currentRole) == $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-2 flex gap-3">
                <button class="px-5 py-2.5 bg-amber-300 rounded-xl font-bold hover:bg-amber-400">Save Changes</button>
                <a href="{{ route('admin.staff.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-100">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
