@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
    <livewire:order-manager :pageType="'my-orders'" />
@endsection