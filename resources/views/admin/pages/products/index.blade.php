@extends('admin::layouts.app')

@php
    $title = 'Продукты';
@endphp

@section('title', $title)

@section('styles')
    <!-- DATATABLES -->
    <link href="{!! asset('admin/css/plugins/datatables/datatables.min.css') !!}" rel="stylesheet">
@endsection

@section('content')

    @include('admin.module.articles::partials.breadcrumb_index', ['title' => $title])

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            {{ $table->table(['class' => 'table table-striped table-bordered table-hover dataTable']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- DATATABLES -->
    <script src="{!! asset('admin/js/plugins/datatables/datatables.min.js') !!}"></script>

    {!! $table->scripts() !!}
@endsection
