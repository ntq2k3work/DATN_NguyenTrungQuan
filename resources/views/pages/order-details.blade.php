@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <livewire:order-manager :pageType="'show'" :orderNumber="$orderNumber" />
@endsection
