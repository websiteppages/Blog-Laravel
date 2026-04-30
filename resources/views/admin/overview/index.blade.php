@extends('admin.layouts.app')

@section('title', config('app.name', 'Inkwell'))

{{-- ── Page-level meta tags ─────────────────────────────── --}}
@push('styles')

@endpush

@section('admin-content')



@endsection


{{-- ── Page-level scripts ────────────────────────────────── --}}
@push('after-scripts')

@endpush
