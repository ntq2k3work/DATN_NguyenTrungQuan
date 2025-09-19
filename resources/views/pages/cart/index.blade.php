@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Giỏ hàng của bạn</h1>

    @livewire('cart-manager')
</div>

@endsection
