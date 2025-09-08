@extends('layouts.app')

@section('title', 'Sách bán chạy')

@section('content')
    <livewire:category-manager :pageType="'best-sellers'" />
@endsection
