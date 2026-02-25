<!-- staff edit -->
@extends('admin.admin_layout')

@section('title', 'Edit Staff | Lumina Admin')

@section('content')
<header class="mb-6">
    <h1 class="text-2xl font-bold">Edit Staff Member</h1>
    <p class="text-gray-600 text-sm">Update staff details and department assignment.</p>
</header>

<div class="bg-white p-6 rounded-lg shadow-sm">
    <form action="{{ route('admin.staff.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4">
            <label class="block">
                <span class="text-sm font-medium">Name</span>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full mt-1 p-2 border rounded" required>
            </label>

            <label class="block">
                <span class="text-sm font-medium">Email</span>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full mt-1 p-2 border rounded" required>
            </label>

            <label class="block">
                <span class="text-sm font-medium">New Password (leave blank to keep)</span>
                <input type="password" name="password" class="w-full mt-1 p-2 border rounded">
            </label>

            <label class="block">
                <span class="text-sm font-medium">Confirm Password</span>
                <input type="password" name="password_confirmation" class="w-full mt-1 p-2 border rounded">
            </label>

            <label class="block">
                <span class="text-sm font-medium">Role / Department</span>
                <select name="role" class="w-full mt-1 p-2 border rounded">
                    <option value="">-- Select role --</option>
                    @foreach($roles as $label => $value)
                        <option value="{{ $value }}" @if(old('role', $user->role) == $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </label>

            <div class="pt-2">
                <button class="px-4 py-2 bg-amber-300 rounded font-bold">Save Changes</button>
                <a href="{{ route('admin.staff.index') }}" class="ml-3 text-sm text-gray-600">Cancel</a>
            </div>
        </div>
    </form>
</div>

@endsection
