@extends('layouts.app')

@section('title', 'Sách đề xuất')

@section('content')
    <livewire:category-manager :pageType="'recommendations'" />
@endsection