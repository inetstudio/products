@extends('admin::back.layouts.app')

@php
    $title = \Request::route('brand');
@endphp

@section('title', $title)

@section('content')

    @push('breadcrumbs')
        @include('admin.module.products::back.partials.breadcrumbs.analytics.brand')
    @endpush

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Привязанные товары <span class="label label-primary float-right">{{ $linkedCount }}</span></h5>
                            </div>
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    {{ $table->table(['class' => 'table table-striped table-bordered table-hover dataTable']) }}
                                </div>
                            </div>
                        </div>
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Непривязанные товары <span class="label label-warning float-right">{{ $unlinkedCount }}</span></h5>
                            </div>
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    {{ $tableUnlinked->table(['class' => 'table table-striped table-bordered table-hover dataTable']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@pushonce('scripts:datatables_products')
    {!! $table->scripts() !!}
    {!! $tableUnlinked->scripts() !!}
@endpushonce
