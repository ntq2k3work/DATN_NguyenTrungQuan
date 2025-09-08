@extends('layouts.app')

@section('title', 'Theo dõi đơn hàng')

@section('content')
    <livewire:order-manager :pageType="'track'" />
@endsection