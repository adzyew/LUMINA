@extends('layouts.admin')

@section('content')

<header class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-3xl font-playfair font-bold text-black">Overview</h1>
        <p class="text-black text-sm">
            Welcome back, {{ auth()->user()->name }}.
        </p>
    </div>
</header>

@endsection