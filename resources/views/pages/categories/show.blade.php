@extends('layouts.app')

@section('title', 'Danh mục sách')

@section('content')
    <livewire:category-manager :slug="$slug" />
@endsection