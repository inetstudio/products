@extends('admin::back.layouts.app')

@php
    $title = 'Продукты';
@endphp

@section('title', $title)

@section('content')

    @push('breadcrumbs')
        @include('admin.module.products::back.partials.breadcrumbs.analytics')
    @endpush

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
