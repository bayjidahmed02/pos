@extends('layout.sidenav-layout')
@section('page_title', 'Invoice')
@section('content')
    @include('components.invoice.invoice-list')
    @include('components.invoice.invoice-delete')
    @include('components.invoice.invoice-details')
@endsection
