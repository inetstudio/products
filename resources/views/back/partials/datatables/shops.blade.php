<ul class="list-group clear-list m-t">
    @foreach ($shops as $shop => $products)
        <li class="list-group-item-action">
            <span class="label label-primary m-r-xs">{{ $products->sum('count') }}</span>{{ $shop }}
        </li>
    @endforeach
</ul>
