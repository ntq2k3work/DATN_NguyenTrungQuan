@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
    <livewire:order-manager :pageType="'checkout'" />
@endsection
