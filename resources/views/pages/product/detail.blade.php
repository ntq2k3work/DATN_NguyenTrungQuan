@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
    <livewire:product-detail :slug="$slug" />
@endsection
