@extends('layout.sidenav-layout')
@section('page_title', 'Customers')
@section('content')
    @include('components.customer.customer-list')
    @include('components.customer.customer-delete')
    @include('components.customer.customer-create')
    @include('components.customer.customer-update')
@endsection
