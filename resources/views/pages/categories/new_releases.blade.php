@extends('layouts.app')

@section('title', 'Sách mới phát hành')

@section('content')
    <livewire:category-manager :pageType="'new-releases'" />
@endsection