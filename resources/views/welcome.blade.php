@extends('elements.master')

@prepend('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
@endprepend

@prepend('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>
@endprepend

@section('title')
    <title>Home</title>
@endsection

@section('content')

<!-- Cart -->
<div class="wrap-header-cart js-panel-cart">
    <div class="s-full js-hide-cart"></div>

    <div class="header-cart flex-col-l p-l-65 p-r-25">
        <div class="header-cart-title flex-w flex-sb-m p-b-8">
            <span class="mtext-103 cl2">
                Your Cart
            </span>

            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>

        <div class="header-cart-content flex-w js-pscroll">
            <ul class="header-cart-wrapitem w-full" id="cartWrapItem">
                @if (session('cart'))
                    @foreach (session('cart') as $key => $cart)
                        <li class="header-cart-item flex-w flex-t m-b-12" id="cart-item-{{ $key }}">
                            <div class="header-cart-item-img" data-id="{{ $key }}">
                                <img src="{{ $cart['photo'] }}" alt="IMG">
                            </div>

                            <div class="header-cart-item-txt p-t-8">
                                <a href="#" class="header-cart-item-name m-b-10 hov-cl1 trans-04">
                                    {{ $cart['name'] }}
                                </a>

                                <span class="header-cart-item-info">
                                    <p class="m-b-10">{{ $cart['color'] }} <i>Size: {{ $cart['size'] }}</i></p>
                                    <p>{{ $cart['qty'] }} x ${{ $cart['price'] }}</p>
                                </span>
                            </div>
                        </li>
                    @endforeach
                @else
                    <p>No product</p>
                @endif
            </ul>

            <div class="w-full">
                <div class="header-cart-total w-full p-tb-40" id="cartTotalPrice">
                    @if (session()->has('totalPrice'))
                        @if (session('totalPrice') === 0)
                            Total: $0.00
                        @else
                            Total: ${{ number_format(session('totalPrice'), '2', ',', '.') }}
                        @endif
                    @endif
                </div>

                <div class="header-cart-buttons flex-w w-full">
                    <a href="{{ route('cart') }}" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                        View Cart
                    </a>

                    <a href="{{ route('checkout') }}" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                        Check Out
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Slider -->
<section class="section-slide">
    <div class="wrap-slick1">
        <div class="slick1">
            @foreach ($sliders as $slider)
            <div class="item-slick1" style="background-image: url('{{ $slider->image_path }}'); background-size: contain;">
                <div class="container h-full">
                    <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                        <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                            <span class="ltext-101 cl2 respon2">
                                {{ $slider->title_01 }}
                            </span>
                        </div>

                        <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                            <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                {{ $slider->title_02 }}
                            </h2>
                        </div>

                        <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                            <a href="product.html" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                Shop Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Product -->
<section class="bg0 p-t-23 p-b-140">
    <div class="container">
        <div class="p-b-10">
            <h3 class="ltext-103 cl5">
                Product Overview
            </h3>
        </div>

        <div class="flex-w flex-sb-m p-b-52">
            <div class="flex-w flex-l-m filter-tope-group m-tb-10">
                <button class="filter-link category stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-id="*">
                    All Products
                </button>
                @if (isset($categories))
                    @foreach ($categories as $category)
                        <button class="filter-link category stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-id="{{ $category->id }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                @endif
            </div>

            <div class="flex-w flex-c-m m-tb-10">
                <div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
                    <i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
                    <i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                     Filter
                </div>

                <div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
                    <i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
                    <i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                    Search
                </div>
            </div>

            <!-- Search product -->
            <div class="dis-none panel-search w-full p-t-10 p-b-15">
                <div class="bor8 dis-flex p-l-15">
                    <button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
                        <i class="zmdi zmdi-search"></i>
                    </button>

                    <input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search-product" placeholder="Search">
                </div>
            </div>

            <!-- Filter -->
            <div class="dis-none panel-filter w-full p-t-10">
                <div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
                    <div class="filter-col1 p-r-15 p-b-27">
                        <div class="mtext-102 cl2 p-b-15">
                            Sort By
                        </div>

                        <ul>
                            <li class="p-b-6">
                                <a href="javascript:;" data-sort="default" class="filter-link sort-by stext-106 trans-04">
                                    Default
                                </a>
                            </li>

                            <li class="p-b-6">
                                <a href="javascript:;" data-sort="low-to-high" class="filter-link sort-by stext-106 trans-04">
                                    Price: Low to High
                                </a>
                            </li>

                            <li class="p-b-6">
                                <a href="javascript:;" data-sort="high-to-low" class="filter-link sort-by stext-106 trans-04">
                                    Price: High to Low
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="filter-col2 p-r-15 p-b-27">
                        <div class="mtext-102 cl2 p-b-15">
                            Price
                        </div>

                        <ul>
                            <li class="p-b-6">
                                <a href="javascript:;" data-min="" data-max="" class="filter-link filter-by-price stext-106 trans-04">
                                    All
                                </a>
                            </li>

                            <li class="p-b-6">
                                <a href="javascript:;" data-min="0" data-max="50" class="filter-link filter-by-price stext-106 trans-04">
                                    $0.00 - $50.00
                                </a>
                            </li>

                            <li class="p-b-6">
                                <a href="javascript:;" data-min="50" data-max="100" class="filter-link filter-by-price stext-106 trans-04">
                                    $50.00 - $100.00
                                </a>
                            </li>

                            <li class="p-b-6">
                                <a href="javascript:;" data-min="100" data-max="150" class="filter-link filter-by-price stext-106 trans-04">
                                    $100.00 - $150.00
                                </a>
                            </li>

                            <li class="p-b-6">
                                <a href="javascript:;" data-min="150" data-max="200" class="filter-link filter-by-price stext-106 trans-04">
                                    $150.00 - $200.00
                                </a>
                            </li>

                            <li class="p-b-6">
                                <a href="javascript:;" data-min="200" data-max="" class="filter-link filter-by-price stext-106 trans-04">
                                    $200.00+
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="filter-col3 p-r-15 p-b-27">
                        <div class="mtext-102 cl2 p-b-15">
                            Color
                        </div>

                        <ul>
                            @if (isset($colors))
                                <li class="p-b-6">
                                    <a href="javascript:;" data-color="*" class="filter-link filter-by-color stext-106 trans-04">
                                        All
                                    </a>
                                </li>
                                @foreach ($colors as $color)
                                    <li class="p-b-6">
                                        <span class="fs-15 lh-12 m-r-6" style="color: {{ $color->code }};">
                                            <i class="zmdi zmdi-circle"></i>
                                        </span>

                                        <a href="javascript:;" data-color="{{ $color->id }}" class="filter-link filter-by-color stext-106 trans-04" data-id="{{ $color->id }}">
                                            {{ ucfirst($color->name) }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="filter-col4 p-b-27">
                        <div class="mtext-102 cl2 p-b-15">
                            Tags
                        </div>

                        <div class="flex-w p-t-4 m-r--5">
                            <a href="javascript:;" data-tag="*" class="filter-link filter-by-tag flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                All
                            </a>
                            @if (isset($tags))
                                @foreach ($tags as $tag)
                                    <a href="javascript:;" data-tag="{{ $tag->id }}" class="filter-link filter-by-tag flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- List product --}}
        <div class="row isotope-grid" id="product_filter" data-filter="false">

        </div>
        <!-- Load more -->
        <div class="flex-c-m flex-w w-full p-t-45">
            <a href="javascript:;" id="loadMore" data-filter="false" class="p-t-12 p-l-45 stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
                Load More
            </a>
            <a href="javascript:;" id="loadMoreSearch" style="display: none" class="p-t-12 p-l-45 stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
                Load More
            </a>
        </div>
    </div>
</section>

@include('elements.footer')

<!-- Back to top -->
<div class="btn-back-to-top" id="myBtn">
    <span class="symbol-btn-back-to-top">
        <i class="zmdi zmdi-chevron-up"></i>
    </span>
</div>

<!-- Modal1 -->
<div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
    <div class="overlay-modal1 js-hide-modal1"></div>

    <div class="container">
        <div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
            <button class="how-pos3 hov3 trans-04 js-hide-modal1">
                <img src="theme/shoesshop/images/icons/icon-close.png" alt="CLOSE">
            </button>

            <div class="row">
                <div class="col-md-6 col-lg-7 p-b-30">
                    <div class="p-l-25 p-r-30 p-lr-0-lg" id="imageList">

                    </div>
                </div>

                <div class="col-md-6 col-lg-5 p-b-30">
                    <div class="p-r-50 p-t-5 p-lr-0-lg">
                        <h4 class="mtext-105 cl2 js-name-detail p-b-14" id="product_name">
                        </h4>

                        <span class="mtext-106 cl2" id="product_price">
                        </span>

                        <p class="stext-102 cl3 p-t-23" id="product_description">
                        </p>
                        <div class="errors_product">

                        </div>
                        <!--  -->
                        <div class="p-t-33">
                            <div class="d-flex align-items-start mb-3">
                                <div class="mr-5 pt-2">
                                    Color
                                </div>
                                <div class="product-color-group">
                                    {{-- color --}}
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="mr-5 pt-2">
                                    Size
                                </div>
                                <div class="product-size-group">
                                    {{-- size --}}
                                </div>
                            </div>

                            <div class="flex-w flex-r-m p-b-10">
                                <div class="flex-w flex-m respon6-next">
                                    <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                        <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                            <i class="fs-16 zmdi zmdi-minus"></i>
                                        </div>

                                        <input class="mtext-104 cl3 txt-center num-product" type="numeric" name="num-product" value="1" min="0">

                                        <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                            <i class="fs-16 zmdi zmdi-plus"></i>
                                        </div>
                                    </div>

                                    <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="mr-5">
                                    Quanity
                                </div>
                                <div class="text-secondary quanity-product">
                                    <i class="display-5" id="quanity"></i>
                                    <input type="hidden" id="quanity_hidden" value="">
                                </div>
                            </div>
                        </div>

                        <!--  -->
                        <div class="flex-w flex-m p-l-100 p-t-40 respon7">

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Google Plus">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
