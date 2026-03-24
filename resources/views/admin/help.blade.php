@extends('admin.admin_layout')

@section('title', 'Help & Guide | Lumina Admin')

@section('content')
@php $isAdmin = auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false); @endphp

<header class="mb-8">
    @include('partials.favicon')
    <h1 class="text-3xl font-playfair font-bold text-gray-900">Help & Guide</h1>
    <p class="text-gray-500 mt-1 text-sm">A walkthrough of every section in the Lumina admin panel.</p>
</header>

{{-- Quick Nav --}}
<div class="flex flex-wrap gap-2 mb-10">
    @foreach([
        ['#dashboard',    'Dashboard'],
        ['#products',     'Products'],
        ['#orders',       'Orders'],
        ['#analytics',    'Analytics'],
        ['#deliveries',   'Deliveries'],
        ['#feedback',     'Feedback'],
        ['#users',        'Users'],
        ['#roles',        'Roles & Permissions'],
    ] as [$href, $label])
    <a href="{{ $href }}" class="px-4 py-1.5 rounded-full text-sm font-semibold bg-white border border-gray-200 text-gray-700 hover:bg-amber-300 hover:text-black hover:border-amber-300 transition-colors">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="space-y-6">

    {{-- DASHBOARD --}}
    <div id="dashboard" class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 bg-amber-50">
            <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Dashboard</h2>
                <p class="text-xs text-gray-500">Admin only</p>
            </div>
        </div>
        <div class="px-6 py-5 grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">The Dashboard is your bird's-eye view of the entire store. It loads real-time numbers so you can spot issues at a glance without digging through individual sections.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex gap-2"><span class="text-amber-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Revenue card</strong> — total earnings this month with a % change vs. last month.</span></li>
                    <li class="flex gap-2"><span class="text-amber-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Orders card</strong> — total orders with a quick count of pending and processing.</span></li>
                    <li class="flex gap-2"><span class="text-amber-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Products card</strong> — total products with in-stock and low-stock counts.</span></li>
                    <li class="flex gap-2"><span class="text-amber-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Users card</strong> — total registered users split by staff and customers.</span></li>
                    <li class="flex gap-2"><span class="text-amber-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Donut charts</strong> — visual breakdown of Inventory, Sales, and Delivery statuses.</span></li>
                    <li class="flex gap-2"><span class="text-amber-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Recent Orders table</strong> — the last 5 orders with customer name, status, and amount.</span></li>
                </ul>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-500 space-y-2">
                <p class="font-semibold text-gray-700 mb-1">Tips</p>
                <p>• The dashboard refreshes on every page load — no manual refresh needed.</p>
                <p>• Red/green % on the Revenue card reflects the trend vs. the previous month.</p>
                <p>• Click <strong class="text-gray-700">View All</strong> in the Recent Orders section to go directly to the full Orders list.</p>
            </div>
        </div>
    </div>

    {{-- PRODUCTS --}}
    <div id="products" class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 bg-emerald-50">
            <div class="w-10 h-10 rounded-xl bg-emerald-400/20 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Products</h2>
                <p class="text-xs text-gray-500">Inventory Department</p>
            </div>
        </div>
        <div class="px-6 py-5 grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">The Products section is managed by the <strong>Inventory Department</strong>. It covers the full lifecycle of a product — from creation to archiving.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex gap-2"><span class="text-emerald-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Add Product</strong> — fill in name, description, price, stock quantity, category, and upload images via Cloudinary.</span></li>
                    <li class="flex gap-2"><span class="text-emerald-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Edit Product</strong> — update any product detail. Stock changes are logged automatically in the Inventory Log.</span></li>
                    <li class="flex gap-2"><span class="text-emerald-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Archive</strong> — hides a product from the storefront without deleting it. Archived products can be restored.</span></li>
                    <li class="flex gap-2"><span class="text-emerald-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Delete</strong> — permanently removes the product. Only available on archived products.</span></li>
                    <li class="flex gap-2"><span class="text-emerald-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Stock status</strong> — automatically calculated: <em>In Stock</em> (&gt;10), <em>Low Stock</em> (1–10), <em>Out of Stock</em> (0).</span></li>
                </ul>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-500 space-y-2">
                <p class="font-semibold text-gray-700 mb-1">Tips</p>
                <p>• When a customer places an order, stock is automatically decremented.</p>
                <p>• When an order is <strong class="text-gray-700">cancelled</strong>, stock is automatically restored — no manual adjustment needed.</p>
                <p>• All stock changes (orders, manual edits, restores) are recorded in the Inventory Log for auditing.</p>
            </div>
        </div>
    </div>

    {{-- ORDERS --}}
    <div id="orders" class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 bg-blue-50">
            <div class="w-10 h-10 rounded-xl bg-blue-400/20 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Orders</h2>
                <p class="text-xs text-gray-500">Sales Department</p>
            </div>
        </div>
        <div class="px-6 py-5 grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">Every customer purchase appears here. The Sales team is responsible for moving orders through the fulfillment pipeline.</p>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Order Status Flow</p>
                <div class="flex flex-wrap items-center gap-1.5 mb-4 text-xs font-semibold">
                    <span class="px-2 py-1 rounded-full bg-yellow-500/10 text-yellow-600">Pending</span>
                    <span class="text-gray-400">→</span>
                    <span class="px-2 py-1 rounded-full bg-blue-500/10 text-blue-600">Confirmed</span>
                    <span class="text-gray-400">→</span>
                    <span class="px-2 py-1 rounded-full bg-indigo-500/10 text-indigo-600">Processing</span>
                    <span class="text-gray-400">→</span>
                    <span class="px-2 py-1 rounded-full bg-purple-500/10 text-purple-600">Shipped</span>
                    <span class="text-gray-400">→</span>
                    <span class="px-2 py-1 rounded-full bg-green-500/10 text-green-600">Delivered</span>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex gap-2"><span class="text-blue-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">View</strong> — see full order details: items, quantities, shipping address, contact, and tracking info.</span></li>
                    <li class="flex gap-2"><span class="text-blue-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Update Status</strong> — move the order to the next stage. Each change triggers an email to the customer.</span></li>
                    <li class="flex gap-2"><span class="text-blue-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Add Tracking</strong> — when marking as Shipped, add courier name and tracking URL so the customer can track their package.</span></li>
                    <li class="flex gap-2"><span class="text-blue-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Cancel</strong> — cancelling an order automatically restores the product stock quantities.</span></li>
                    <li class="flex gap-2"><span class="text-blue-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Loyalty Points</strong> — automatically awarded to the customer when an order is marked <em>Delivered</em> (1 point per ₱100 spent).</span></li>
                </ul>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-500 space-y-2">
                <p class="font-semibold text-gray-700 mb-1">Tips</p>
                <p>• Use the status filter tabs at the top of the Orders page to quickly see only orders in a specific stage.</p>
                <p>• Always confirm an order before processing — this lets the customer know their order was received.</p>
                <p>• A customer email is sent automatically on every status change — no need to contact them manually.</p>
            </div>
        </div>
    </div>

    {{-- ANALYTICS --}}
    <div id="analytics" class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 bg-violet-50">
            <div class="w-10 h-10 rounded-xl bg-violet-400/20 text-violet-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Analytics</h2>
                <p class="text-xs text-gray-500">Sales Department</p>
            </div>
        </div>
        <div class="px-6 py-5 grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">Analytics gives the Sales team a deeper look at revenue trends and order volumes over time.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex gap-2"><span class="text-violet-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Revenue Chart</strong> — bar chart showing revenue over time. Switch between Day, Week, Month, and Year views.</span></li>
                    <li class="flex gap-2"><span class="text-violet-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Orders Chart</strong> — tracks the number of orders placed in the same time periods.</span></li>
                    <li class="flex gap-2"><span class="text-violet-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Satisfaction Trend</strong> — area chart showing average customer review ratings over recent months.</span></li>
                    <li class="flex gap-2"><span class="text-violet-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Export</strong> — download order data as a CSV for external reporting.</span></li>
                </ul>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-500 space-y-2">
                <p class="font-semibold text-gray-700 mb-1">Tips</p>
                <p>• Hover over any bar or point on the chart to see the exact peso amount or count for that period.</p>
                <p>• Use the <strong class="text-gray-700">Year</strong> view to identify seasonal trends across 12 months.</p>
                <p>• The Satisfaction Trend chart skips months with no reviews to keep the line accurate.</p>
            </div>
        </div>
    </div>

    {{-- DELIVERIES --}}
    <div id="deliveries" class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 bg-orange-50">
            <div class="w-10 h-10 rounded-xl bg-orange-400/20 text-orange-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Deliveries</h2>
                <p class="text-xs text-gray-500">Delivery Department</p>
            </div>
        </div>
        <div class="px-6 py-5 grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">The Delivery section is a focused view for the Delivery team — it only shows orders that need shipping attention.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex gap-2"><span class="text-orange-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">To Ship</strong> — orders that have been confirmed and are ready to be handed over to the courier.</span></li>
                    <li class="flex gap-2"><span class="text-orange-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">In Transit</strong> — orders that have been shipped and are currently on the way to the customer.</span></li>
                    <li class="flex gap-2"><span class="text-orange-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Update Delivery</strong> — add or edit the courier name and tracking URL. Mark as Delivered when the package arrives.</span></li>
                    <li class="flex gap-2"><span class="text-orange-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Delivered</strong> — marking as Delivered triggers a confirmation email and loyalty points for the customer.</span></li>
                </ul>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-500 space-y-2">
                <p class="font-semibold text-gray-700 mb-1">Tips</p>
                <p>• Always add a tracking URL when marking an order as Shipped — customers will receive this link in their email.</p>
                <p>• The Delivery team only sees delivery-related statuses. Full order management is in the Orders section (Sales team).</p>
            </div>
        </div>
    </div>

    {{-- FEEDBACK --}}
    <div id="feedback" class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 bg-pink-50">
            <div class="w-10 h-10 rounded-xl bg-pink-400/20 text-pink-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6M7 4h10a2 2 0 012 2v12l-3-2-3 2-3-2-3 2V6a2 2 0 012-2z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Feedback</h2>
                <p class="text-xs text-gray-500">Feedback Department</p>
            </div>
        </div>
        <div class="px-6 py-5 grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">Feedback lets the moderation team manage product reviews submitted by customers, keeping the storefront trustworthy.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex gap-2"><span class="text-pink-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Pending Reviews</strong> — new reviews waiting to be approved or rejected before appearing on the product page.</span></li>
                    <li class="flex gap-2"><span class="text-pink-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Approve</strong> — makes the review visible to all customers on the product page.</span></li>
                    <li class="flex gap-2"><span class="text-pink-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Reject</strong> — hides the review from the storefront. The customer is not notified.</span></li>
                    <li class="flex gap-2"><span class="text-pink-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Satisfaction Trend</strong> — a chart showing average star ratings submitted over time to track overall product sentiment.</span></li>
                </ul>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-500 space-y-2">
                <p class="font-semibold text-gray-700 mb-1">Tips</p>
                <p>• Reviews are hidden from the storefront by default until approved — no risk of unwanted content going live.</p>
                <p>• Monitor the Satisfaction Trend chart regularly to catch sudden drops in ratings, which might indicate a product issue.</p>
            </div>
        </div>
    </div>

    {{-- USERS --}}
    <div id="users" class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 bg-cyan-50">
            <div class="w-10 h-10 rounded-xl bg-cyan-400/20 text-cyan-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Users</h2>
                <p class="text-xs text-gray-500">Admin only</p>
            </div>
        </div>
        <div class="px-6 py-5 grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">The Users page gives the Admin full visibility and control over all accounts — staff and customers — in one place.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex gap-2"><span class="text-cyan-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">All tab</strong> — shows Staff & Admin accounts separated from Customers in two distinct tables.</span></li>
                    <li class="flex gap-2"><span class="text-cyan-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">View</strong> — see the full profile of any user.</span></li>
                    <li class="flex gap-2"><span class="text-cyan-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Edit</strong> (staff only) — update name, email, role, or reset password for staff accounts.</span></li>
                    <li class="flex gap-2"><span class="text-cyan-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Verify</strong> — manually mark a staff account as email-verified after confirming their identity.</span></li>
                    <li class="flex gap-2"><span class="text-cyan-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Archive</strong> — disables a user account immediately. The user cannot log in while archived.</span></li>
                    <li class="flex gap-2"><span class="text-cyan-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Delete</strong> — permanently removes the account. Only available for archived users.</span></li>
                </ul>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-500 space-y-2">
                <p class="font-semibold text-gray-700 mb-1">Tips</p>
                <p>• Archiving is reversible — always archive before deleting to avoid accidental data loss.</p>
                <p>• Admin accounts cannot be edited, archived, or deleted through this interface as a safety measure.</p>
                <p>• Staff accounts that registered but haven't verified their email will show a <strong class="text-gray-700">Pending</strong> badge — use the Verify button to manually approve them.</p>
            </div>
        </div>
    </div>

    {{-- ROLES --}}
    <div id="roles" class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 bg-amber-50">
            <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Roles & Permissions</h2>
                <p class="text-xs text-gray-500">Admin only</p>
            </div>
        </div>
        <div class="px-6 py-5 grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">Roles define what sections a staff member can access. Each role is a department (e.g. <em>Inventory Manager</em>, <em>Sales Staff</em>) with a specific set of permissions.</p>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Available Permissions</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach(['inventory.view', 'inventory.create', 'inventory.edit', 'inventory.delete', 'sales.view', 'deliveries.manage', 'reviews.moderate'] as $perm)
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-amber-600">{{ $perm }}</span>
                    @endforeach
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex gap-2"><span class="text-amber-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Create Role</strong> — click "+ Add Role" and type a department name. The system formats it automatically.</span></li>
                    <li class="flex gap-2"><span class="text-amber-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Edit Permissions</strong> — check or uncheck permissions for a role. Changes apply to all staff with that role instantly.</span></li>
                    <li class="flex gap-2"><span class="text-amber-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Archive</strong> — hides a role from active use. Staff with that role lose access until restored.</span></li>
                    <li class="flex gap-2"><span class="text-amber-400 font-bold mt-0.5">→</span><span><strong class="text-gray-900">Delete</strong> — permanently removes the role. Only available after archiving. The <em>Admin</em> role cannot be deleted.</span></li>
                </ul>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-500 space-y-2">
                <p class="font-semibold text-gray-700 mb-1">Tips</p>
                <p>• Assign roles to staff through <strong class="text-gray-700">Users → Edit</strong>, not here. This page only manages what permissions each role has.</p>
                <p>• The <strong class="text-gray-700">Admin</strong> role is a system role — its permissions and existence are protected and cannot be modified.</p>
                <p>• Be careful when editing permissions on a role that has active staff — changes take effect immediately.</p>
            </div>
        </div>
    </div>

</div>
@endsection
