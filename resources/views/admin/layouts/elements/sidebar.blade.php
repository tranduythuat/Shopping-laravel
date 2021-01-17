<nav id="sidebar">
    <div class="sidebar-header">
        <h3>Admin</h3>
    </div>

    <ul class="list-unstyled components">
        <span class="mx-2">Welcome </span>
        <div class="user-name d-flex justify-content-end pr-2 mb-3">{{ Auth::user()->name }}</div>
        <li>
            <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i>&nbsp; Dashboard</a>
        </li>
        <li>
            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fa fa-shopping-cart"></i>&nbsp; Product
            </a>
            <ul class="collapse list-unstyled" id="homeSubmenu">
                <li>
                    <a href="{{ route('admin.product.list') }}"><i class="fa fa-location-arrow"></i>&nbsp; List Product</a>
                </li>
                <li>
                    <a href="{{ route('admin.color_product.list') }}"><i class="fa fa-location-arrow"></i>&nbsp; List Color</a>
                </li>
                <li>
                    <a href="{{ route('admin.size_product.list') }}"><i class="fa fa-location-arrow"></i>&nbsp; List Size</a>
                </li>
                <li>
                    <a href="{{ route('admin.product.trash') }}"><i class="fa fa-location-arrow"></i>&nbsp; Trash Product</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('admin.category.list') }}"><i class="fa fa-bars"></i>&nbsp; Category</a>
        </li>
        <li>
            <a href="{{ route('admin.supplier.list') }}"><i class="fa fa-life-ring"></i>&nbsp; Supplier</a>
        </li>
        <li>
            <a href="{{ route('admin.slider.list') }}"><i class="fa fa-sort"></i>&nbsp; Slider</a>
        </li>
    </ul>
</nav>
