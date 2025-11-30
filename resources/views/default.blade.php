@extends('layout')

@section('content')

{{-- Gradient examples: from-blue-500 to-purple-600, from-green-500 to-teal-600, from-pink-500 to-red-600 --}}

    <div class="w-full max-w-4xl mx-auto">
        <h1 class="mb-4 text-4xl font-bold tracking-tight text-heading md:text-5xl lg:text-6xl">
            {{ $page->title }}
        </h1>
        <livewire:carousel />
@endsection
