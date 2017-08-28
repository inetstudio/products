@extends('admin::layouts.app')

@php
    $title = ($item->id) ? 'Просмотр продукта' : '';
@endphp

@section('title', $title)

@section('content')

    @push('breadcrumbs')
        @include('admin.module.products::partials.breadcrumbs')
        <li>
            <a href="{{ route('back.products.index') }}">Продукты</a>
        </li>
    @endpush

    <div class="wrapper wrapper-content form-horizontal">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel-group float-e-margins" id="mainAccordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <a data-toggle="collapse" data-parent="#mainAccordion" href="#collapseMain" aria-expanded="true">Основная информация</a>
                            </h5>
                        </div>
                        <div id="collapseMain" class="panel-collapse collapse in" aria-expanded="true">
                            <div class="panel-body">

                                {!! Form::string('title', $item->title, [
                                    'label' => [
                                        'title' => 'Заголовок',
                                    ],
                                ]) !!}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
