@extends('admin.admin_layout')

@section('title', 'Roles & Permissions | Lumina Admin')

@section('content')
<header class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-playfair font-bold text-black">Roles & Permissions</h1>
        <p class="text-gray-600 text-sm mt-1">Manage role permissions for your team.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
            + Add Staff
        </a>
    </div>
</header>

@if(session('success'))
    <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">
        {{ session('success') }}
    </div>
@endif

<div class="bg-gray-900 rounded-2xl overflow-hidden border border-white/5">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-white/5 text-white border-b border-white/10 text-sm">
                <th class="p-4">Role</th>
                <th class="p-4">Permissions</th>
                <th class="p-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/10">
    @foreach($roles as $role)
            <tr class="hover:bg-amber-300/10 transition duration-300">
                <td class="p-4">
                    <span class="font-bold text-white capitalize">{{ $role->name }}</span>
                </td>
                <td class="p-4">
                    <div class="flex flex-wrap gap-2">
                        @forelse($role->permissions as $permission)
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-300">{{ $permission->name }}</span>
                        @empty
                            <span class="text-gray-500 text-sm">No permissions assigned</span>
                        @endforelse
                    </div>
                </td>
                <td class="p-4">
                    <div class="flex justify-center">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center px-4 py-2 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors text-sm">
                    Edit Permissions
                </a>
            </div>
                </td>
            </tr>
                @endforeach
        </tbody>
    </table>

    @if($roles->isEmpty())
        <div class="p-12 text-center text-gray-500">No roles found.</div>
    @endif
</div>
@endsection
