@extends('layouts.base')

@section('title', 'Web Page')

@push('styles')
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

@endpush

@push('scripts')

@endpush

@section('body-class', '')

@section('content')
    <div class=" ">

        @include('web.layouts.navbar')

        {{-- Page content --}}
        <div id="page-content">
            @yield('web-content')
        </div>


            @include('web.layouts.footer')
    </div>
 @endsection
