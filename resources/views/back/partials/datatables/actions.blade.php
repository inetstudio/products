<div class="btn-group">
    <a href="{{ route('back.products.edit', [$id]) }}" class="btn btn-xs btn-default m-r"><i class="fa fa-pencil-alt"></i></a>
    <a href="#" class="btn btn-xs btn-danger delete" data-url="{{ route('back.products.destroy', [$id]) }}"><i class="fa fa-times"></i></a>
</div>
