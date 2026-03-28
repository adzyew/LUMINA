@extends('layouts.public')
@section('title', 'Shipping Information')
@section('content')
<div class="max-w-2xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-4 text-amber-900">Shipping Information</h1>
    <p class="mb-3">We strive to process and ship your order as quickly as possible. Orders are typically processed within 1-2 business days. Delivery times may vary depending on your location and chosen shipping method.</p>
    <ul class="list-disc pl-6 mb-3">
        <li>Standard shipping: 3-7 business days</li>
        <li>Express shipping: 1-3 business days</li>
        <li>Free shipping on orders over ₱2,000</li>
    </ul>
    <p class="mb-3">You will receive a tracking number via email once your order has shipped. For any shipping inquiries, please contact our support team.</p>
</div>
@endsection
