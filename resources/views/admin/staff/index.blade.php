<!-- staff index -->
@extends('admin.admin_layout')

@section('title', 'Staff Management | Lumina Admin')

@section('content')
<header class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Staff Management</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Create and assign personnel to departments.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.staff.create') }}" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
            + Add Staff
        </a>
    </div>
</header>

@if(session('success'))
    <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-200 dark:border-white/5">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-white/5 text-gray-700 dark:text-white border-b border-gray-200 dark:border-white/10 text-sm">
                <th class="p-4">Name</th>
                <th class="p-4">Email</th>
                <th class="p-4">Role</th>
                <th class="p-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
        @forelse($staff as $user)
            <tr class="hover:bg-amber-300/10 transition duration-300">
                <td class="p-4 text-gray-900 dark:text-white">{{ $user->name }}</td>
                <td class="p-4 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                <td class="p-4 text-gray-600 dark:text-gray-300">{{ optional($user->roles->first())->name ? \Illuminate\Support\Str::headline($user->roles->first()->name) : '-' }}</td>
                <td class="p-4">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.staff.edit', $user) }}" class="inline-flex items-center px-3 py-1 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors text-sm">Edit</a>
                        <form action="{{ route('admin.staff.destroy', $user) }}" method="POST" onsubmit="return confirm('Remove this staff member?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="p-6 text-center text-gray-500">No staff found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $staff->links() }}
</div>

@endsection
