@extends('layouts.app')

@section('title', 'Sách bán chạy nhất')

@section('content')
    <livewire:category-manager :pageType="'top-selling'" />
@endsection