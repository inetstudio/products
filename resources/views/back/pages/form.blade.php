@extends('admin::back.layouts.app')

@php
    $title = ($item->id) ? 'Редактирование продукта' : 'Создание продукта';
@endphp

@section('title', $title)

@section('content')

    @push('breadcrumbs')
        @include('admin.module.products::back.partials.breadcrumbs.form')
    @endpush

    <div class="row m-sm">
        <a class="btn btn-white" href="{{ route('back.products.index') }}">
            <i class="fa fa-arrow-left"></i> Вернуться назад
        </a>
    </div>

    <div class="wrapper wrapper-content">
        {!! Form::info() !!}

        {!! Form::open(['url' => (! $item->id) ? route('back.products.store') : route('back.products.update', [$item->id]), 'id' => 'mainForm', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) !!}

            @if ($item->id)
                {{ method_field('PUT') }}
            @endif

            {!! Form::hidden('product_id', (! $item->id) ? '' : $item->id, ['id' => 'object-id']) !!}

            {!! Form::hidden('product_type', get_class($item), ['id' => 'object-type']) !!}

            {!! Form::buttons('', '', ['back' => 'back.products.index']) !!}

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

                                    {!! Form::radios('update', (! $item->update && ! $item->id) ? 1 : $item->update, [
                                        'label' => [
                                          'title' => 'Обновлять продукт',
                                        ],
                                        'radios' => [
                                          [
                                              'label' => 'Да',
                                              'value' => 1,
                                              'options' => [
                                                  'class' => 'i-checks',
                                              ],
                                          ],
                                          [
                                              'label' => 'Нет',
                                              'value' => 0,
                                              'options' => [
                                                  'class' => 'i-checks',
                                              ],
                                          ],
                                        ],
                                    ]) !!}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::buttons('', '', ['back' => 'back.products.index']) !!}

        {!! Form::close()!!}
    </div>
@endsection
