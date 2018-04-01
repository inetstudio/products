@extends('admin::back.layouts.app')

@php
    $title = 'Продукты';
@endphp

@section('title', $title)

@section('content')

    @push('breadcrumbs')
        @include('admin.module.products::back.partials.breadcrumbs.analytics.index')
    @endpush

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel-group float-e-margins form-horizontal" id="filterAccordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <a data-toggle="collapse" data-parent="#filterAccordion" href="#collapseFilter" aria-expanded="true">Фильтрация данных</a>
                            </h5>
                        </div>
                        <div id="collapseFilter" class="panel-collapse collapse" aria-expanded="true">
                            <div class="panel-body">

                                {!! Form::datepicker(['startPeriod', 'endPeriod'], ['', ''], [
                                    'label' => [
                                        'title' => 'Период выборки',
                                    ],
                                    'field' => [
                                        'class' => 'datetimepicker form-control',
                                    ],
                                ]) !!}

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <a href="#" class="btn btn-w-m btn-primary m-l-xs" id="submit-analytics-filter">Применить</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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

@pushonce('scripts:datatables_products')
    {!! $table->scripts() !!}
@endpushonce
