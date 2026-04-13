@extends('admin.admin_layout')

@section('title', 'Staff Management | Lumina Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <header class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-4xl font-playfair font-bold text-gray-900">Staff Management</h1>
            <p class="text-sm text-gray-600 mt-1">Create and assign personnel to departments.</p>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors">
            + Add Staff
        </a>
    </header>

    <div class="bg-white rounded-3xl overflow-hidden border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[760px] border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                        <th class="p-4 font-semibold">Name</th>
                        <th class="p-4 font-semibold">Email</th>
                        <th class="p-4 font-semibold">Role</th>
                        <th class="p-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse($staff as $user)
                    <tr class="hover:bg-amber-50/70 transition-colors">
                        <td class="p-4 text-gray-900 font-semibold">{{ $user->name }}</td>
                        <td class="p-4 text-gray-600">{{ $user->email }}</td>
                        <td class="p-4 text-gray-600">{{ optional($user->roles->first())->name ? \Illuminate\Support\Str::headline($user->roles->first()->name) : '-' }}</td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.staff.edit', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors text-sm">Edit</a>
                                <form action="{{ route('admin.staff.destroy', $user) }}" method="POST" onsubmit="return confirm('Remove this staff member?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-500">No staff found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $staff->links() }}</div>
</div>
@endsection
