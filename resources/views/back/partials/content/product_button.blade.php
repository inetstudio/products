@inject('productsService', 'InetStudio\Products\Contracts\Services\Front\ItemsServiceContract')

@php
    $product = $productsService->getProduct($id);
@endphp

@if ($product->id && $product->links->first())
    <a href="{{ $product->links->first()->link }}" target="_blank">{{ $product->title }}</a>
@endif
