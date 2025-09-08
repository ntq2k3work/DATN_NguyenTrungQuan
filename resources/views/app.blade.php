@extends('layouts.app')
@section('title', 'Home Page')
@section('content')
@include('partials.slider')

<!-- Book Recommendations Section -->
@livewire('book-recommendations')

<!-- Best Sellers Section -->
@livewire('best-sellers')

<!-- New Releases Section -->
@livewire('new-releases')


@endsection
