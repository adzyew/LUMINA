<tr class="hover:bg-amber-300/10 transition duration-300">
    <td class="p-4 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
    <td class="p-4 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
    <td class="p-4">
        @forelse($user->roles as $role)
            @if($role->name === 'admin')
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-500/30 text-amber-300">Admin</span>
            @elseif($role->name === 'staff')
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">Staff</span>
            @elseif($role->name === 'customer')
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400">Customer</span>
            @else
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-300 capitalize">{{ Str::headline($role->name) }}</span>
            @endif
        @empty
            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400">Customer</span>
        @endforelse
    </td>
    <td class="p-4">
        @if($user->hasVerifiedEmail())
            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Verified</span>
        @else
            <span class="px-2 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-400">Pending</span>
        @endif
    </td>
    <td class="p-4">
        <div class="flex justify-center items-center gap-2">
            {{-- View --}}
            <a href="{{ route('admin.users.show', $user) }}" title="View" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-300 text-black hover:bg-amber-400 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </a>

            {{-- Edit (staff only) --}}
            @if(!$user->hasRole('admin') && $user->roles->isNotEmpty())
                <a href="{{ route('admin.users.edit', $user) }}" title="Edit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                </a>
            @endif

            {{-- Verify (staff only, unverified only) --}}
            @if(!$user->hasRole('admin') && $user->roles->isNotEmpty() && !$user->email_verified_at && !$user->archived_at)
                <form id="verify-user-form-{{ $user->id }}" action="{{ route('admin.users.verify', $user) }}" method="POST">
                    @csrf
                    <button type="button" title="Verify" onclick="showVerifyModal('{{ $user->id }}', '{{ $user->name }}')" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-green-600 text-white hover:bg-green-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.745 3.745 0 0 1 3.296-1.043A3.745 3.745 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 0 1 3.296 1.043 3.745 3.745 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                    </button>
                </form>
            @endif

            @if(!$user->hasRole('admin'))
                @if($user->archived_at)
                    {{-- Unarchive --}}
                    <form action="{{ route('admin.users.unarchive', $user) }}" method="POST">
                        @csrf
                        <button type="submit" title="Unarchive" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-500 text-white hover:bg-gray-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </button>
                    </form>
                    {{-- Delete --}}
                    <form id="delete-user-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" title="Delete" onclick="showDeleteModal('{{ $user->id }}', '{{ $user->name }}')" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-600 text-white hover:bg-red-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </form>
                @else
                    {{-- Archive --}}
                    <form action="{{ route('admin.users.archive', $user) }}" method="POST" onsubmit="return confirm('Archive this user? They will be prevented from logging in.');">
                        @csrf
                        <button type="submit" title="Archive" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-500 text-white hover:bg-gray-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </td>
</tr>
