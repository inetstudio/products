<li class="{{ isActiveRoute('back.products-package.*', 'mm-active') }}">
    <a href="#" aria-expanded="false">
        <i class="fa fa-shopping-cart"></i> <span class="nav-label">Продукты</span><span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        @include('admin.module.products-package.products::back.includes.package_navigation')
    </ul>
</li>
