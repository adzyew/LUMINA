@extends('admin.admin_layout')

@section('title', 'Promos | Lumina Admin')

@section('content')
<div id="promos-autofilter-content" data-admin-autofilter-root="1">
<header class="flex flex-wrap items-start justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-playfair font-bold text-gray-900">Promo Management</h1>
        <p class="text-gray-600 text-sm mt-1">Create and manage checkout discount codes for users.</p>
    </div>
    <button type="button" onclick="openCreatePromoModal()" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-lg transition-colors shadow-lg">
        + New Promo
    </button>
</header>

<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <p class="text-sm uppercase tracking-wide text-gray-500 font-semibold">Total Promos</p>
        <p class="mt-2 text-4xl font-bold text-gray-900">{{ $stats['total'] }}</p>
    </div>
    <div class="rounded-xl border border-green-200 bg-green-50 p-5 shadow-sm">
        <p class="text-sm uppercase tracking-wide text-green-700 font-semibold">Active</p>
        <p class="mt-2 text-4xl font-bold text-green-700">{{ $stats['active'] }}</p>
    </div>
    <div class="rounded-xl border border-red-200 bg-red-50 p-5 shadow-sm">
        <p class="text-sm uppercase tracking-wide text-red-700 font-semibold">Expired</p>
        <p class="mt-2 text-4xl font-bold text-red-700">{{ $stats['expired'] }}</p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 shadow-sm">
        <p class="text-sm uppercase tracking-wide text-gray-600 font-semibold">Inactive</p>
        <p class="mt-2 text-4xl font-bold text-gray-700">{{ $stats['inactive'] }}</p>
    </div>
</section>

<section class="bg-white border border-gray-200 rounded-xl p-4 mb-6">
    <form method="GET" action="{{ route('admin.promos.index') }}" class="js-admin-auto-filter grid grid-cols-1 md:grid-cols-[minmax(0,1.4fr)_minmax(200px,0.8fr)_auto] gap-3" data-autofilter-container="#promos-autofilter-content">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search promo code or name" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
        <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
        <div class="flex gap-2">
            <a href="{{ route('admin.promos.index') }}" data-autofilter-reset="1" data-autofilter-container="#promos-autofilter-content" class="px-5 py-3 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold rounded-lg transition-colors">Reset</a>
        </div>
    </form>
</section>

<section class="bg-white border border-gray-200 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                    <th class="p-4">Code</th>
                    <th class="p-4">Name</th>
                    <th class="p-4">Discount</th>
                    <th class="p-4">Window</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($promos as $promo)
                    @php
                        $started = !$promo->starts_at || $promo->starts_at->lte(now());
                        $expired = $promo->expires_at && $promo->expires_at->lte(now());
                        $isActive = $promo->is_active && $started && !$expired;
                    @endphp
                    <tr class="hover:bg-amber-50/40 transition-colors">
                        <td class="p-4">
                            <p class="font-bold text-gray-900">{{ $promo->code }}</p>
                        </td>
                        <td class="p-4">
                            <p class="text-gray-900 font-semibold">{{ $promo->name ?: '—' }}</p>
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-amber-600">{{ number_format((float) $promo->discount_percent, 2) }}%</p>
                        </td>
                        <td class="p-4">
                            <p class="text-xs text-gray-500">Starts: {{ optional($promo->starts_at)->format('M d, Y h:i A') ?: 'Now' }}</p>
                            <p class="text-xs text-gray-700 mt-1">Ends: {{ optional($promo->expires_at)->format('M d, Y h:i A') }}</p>
                        </td>
                        <td class="p-4">
                            @if($isActive)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">Active</span>
                            @elseif($expired)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">Expired</span>
                            @elseif(!$promo->is_active)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">Inactive</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">Scheduled</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    type="button"
                                    title="Edit Promo"
                                    onclick="openEditPromoModal({
                                        id: {{ $promo->id }},
                                        code: @js($promo->code),
                                        name: @js($promo->name),
                                        discount_percent: @js((float) $promo->discount_percent),
                                        starts_at: @js(optional($promo->starts_at)->format('Y-m-d\\TH:i')),
                                        expires_at: @js(optional($promo->expires_at)->format('Y-m-d\\TH:i')),
                                        is_active: {{ $promo->is_active ? 'true' : 'false' }}
                                    })"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-100 hover:bg-blue-500 text-blue-600 hover:text-white transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil size-6">
                                        <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/>
                                    </svg>
                                </button>

                                <form method="POST" action="{{ route('admin.promos.toggle', $promo) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $promo->is_active ? 'Deactivate' : 'Activate' }}" class="w-10 h-10 flex items-center justify-center rounded-xl {{ $promo->is_active ? 'bg-yellow-100 hover:bg-yellow-500 text-yellow-700 hover:text-white' : 'bg-green-100 hover:bg-green-500 text-green-700 hover:text-white' }} transition-all duration-200">
                                        @if($promo->is_active)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-x-icon lucide-square-x">
                                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.promos.destroy', $promo) }}" onsubmit="return confirm('Delete promo {{ $promo->code }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Promo" class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-100 hover:bg-red-500 text-red-600 hover:text-white transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-500">
                            No promo codes found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-200">
        {{ $promos->links() }}
    </div>
</section>
</div>

<div id="createPromoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="fixed inset-0 bg-black/75" onclick="closeCreatePromoModal()"></div>
        <div class="relative z-10 bg-white border border-gray-200 rounded-2xl shadow-2xl max-w-xl w-full p-6">
            <h3 class="text-2xl font-bold text-gray-900">Create Promo</h3>
            <p class="text-sm text-gray-500 mt-1 mb-5">Minimum discount is 5%.</p>
            <form method="POST" action="{{ route('admin.promos.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                    <input type="text" name="code" required placeholder="LUMIPRO" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name (optional)</label>
                    <input type="text" name="name" placeholder="Daily Promo" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discount %</label>
                        <input type="number" name="discount_percent" required min="5" max="100" step="0.01" value="5" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
                    </div>
                    <div class="flex items-center gap-2 mt-8">
                        <input type="checkbox" id="create_is_active" name="is_active" value="1" checked class="w-4 h-4 rounded text-amber-500 border-gray-300 focus:ring-amber-400">
                        <label for="create_is_active" class="text-sm font-medium text-gray-700">Active</label>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="create_send_announcement" name="send_announcement" value="1" checked class="w-4 h-4 rounded text-amber-500 border-gray-300 focus:ring-amber-400">
                    <label for="create_send_announcement" class="text-sm font-medium text-gray-700">Send announcement email to verified users now</label>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Starts At</label>
                        <input type="datetime-local" name="starts_at" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expires At</label>
                        <input type="datetime-local" name="expires_at" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeCreatePromoModal()" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 rounded-xl font-medium transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-xl transition-colors">Create Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editPromoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="fixed inset-0 bg-black/75" onclick="closeEditPromoModal()"></div>
        <div class="relative z-10 bg-white border border-gray-200 rounded-2xl shadow-2xl max-w-xl w-full p-6">
            <h3 class="text-2xl font-bold text-gray-900">Edit Promo</h3>
            <p class="text-sm text-gray-500 mt-1 mb-5">Update promo details and validity window.</p>
            <form id="editPromoForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                    <input id="edit_code" type="text" name="code" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name (optional)</label>
                    <input id="edit_name" type="text" name="name" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discount %</label>
                        <input id="edit_discount_percent" type="number" name="discount_percent" required min="5" max="100" step="0.01" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
                    </div>
                    <div class="flex items-center gap-2 mt-8">
                        <input id="edit_is_active" type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded text-amber-500 border-gray-300 focus:ring-amber-400">
                        <label for="edit_is_active" class="text-sm font-medium text-gray-700">Active</label>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Starts At</label>
                        <input id="edit_starts_at" type="datetime-local" name="starts_at" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expires At</label>
                        <input id="edit_expires_at" type="datetime-local" name="expires_at" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEditPromoModal()" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 rounded-xl font-medium transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-black font-bold rounded-xl transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const updatePromoUrlTemplate = @js(url('admin/promos/__PROMO__'));

    function openCreatePromoModal() {
        document.getElementById('createPromoModal').classList.remove('hidden');
    }

    function closeCreatePromoModal() {
        document.getElementById('createPromoModal').classList.add('hidden');
    }

    function openEditPromoModal(promo) {
        const form = document.getElementById('editPromoForm');
        form.action = updatePromoUrlTemplate.replace('__PROMO__', String(promo.id));

        document.getElementById('edit_code').value = promo.code || '';
        document.getElementById('edit_name').value = promo.name || '';
        document.getElementById('edit_discount_percent').value = promo.discount_percent || 5;
        document.getElementById('edit_starts_at').value = promo.starts_at || '';
        document.getElementById('edit_expires_at').value = promo.expires_at || '';
        document.getElementById('edit_is_active').checked = !!promo.is_active;

        document.getElementById('editPromoModal').classList.remove('hidden');
    }

    function closeEditPromoModal() {
        document.getElementById('editPromoModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
