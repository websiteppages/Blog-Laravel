@extends('customer.layouts.app')

@section('title', config('app.name', 'Inkwell'))

{{-- ── Page-level meta tags ─────────────────────────────── --}}
@push('styles')

@endpush

@section('customer-content')




@endsection


{{-- ── Page-level scripts ────────────────────────────────── --}}
@push('after-scripts')

@endpush
