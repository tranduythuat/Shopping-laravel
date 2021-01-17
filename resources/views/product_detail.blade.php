@extends('elements.master')

@prepend('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('theme/shoesshop/css/product_detail.css') }}">

@endprepend

@prepend('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <script src="{{ asset('js/product_detail.js') }}"></script>
@endprepend

@section('title')
    <title></title>
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

<div class="container">
    <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
        <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
            Home
            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
        </a>
        <span class="stext-109 cl4">
            {{ $product->name }}
        </span>
    </div>
</div>

<section class="sec-product-detail bg0 p-t-65 p-b-60">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-7 p-b-30">
                <div class="p-l-25 p-r-30 p-lr-0-lg">
                    <div class="wrap-slick3 flex-sb flex-w">
                        <div class="wrap-slick3-dots"></div>
                        <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>
                        <div class="slick3 gallery-lb">
                            @foreach ($images as $image)
                                <div class="item-slick3" data-thumb="{{ $image }}">
                                    <div class="wrap-pic-w pos-relative">
                                    <img src="{{ $image }}" alt="IMG-PRODUCT">
                                    <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="{{ $image }}">
                                        <i class="fa fa-expand"></i>
                                    </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-5 p-b-30">
                <div class="p-r-50 p-t-5 p-lr-0-lg">
                    <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                        {{ $product->name }}
                    </h4>
                    <span class="mtext-106 cl2">
                        ${{ number_format($product->price, '2', ',', '.') }}
                    </span>
                    <p class="stext-102 cl3 p-t-23">
                        {{ $product->description }}
                    </p>

                    <div class="p-t-33">
                        <div class="list-button-color">
                            <ul>
                                @foreach ($colors as $color)
                                    <li>
                                        <input {{ $color['disabled']==='true'?'disabled':'' }} type="radio" name="btn-color-item" id="btn_color_{{ $color['id'] }}" value="{{ $color['id'] }}">
                                        <label class="btn-tick" class="btn--disabled" for="btn_color_{{ $color['id'] }}">{{ $color['name'] }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="list-button-size">
                            <ul>
                                @foreach ($sizes as $key => $size)
                                    <li>
                                        <input type="radio" name="btn-size-item" id="btn_size_{{ $key }}" value="{{ $key }}">
                                        <label class="btn-tick" for="btn_size_{{ $key }}">{{ $size }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="flex-w flex-r-m p-b-10">
                            <div class="size-204 flex-w flex-m respon6-next">
                                <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                    <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                        <i class="fs-16 zmdi zmdi-minus"></i>
                                    </div>
                                    <input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product" value="1">
                                    <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                        <i class="fs-16 zmdi zmdi-plus"></i>
                                    </div>
                                </div>
                                <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
                                    Add to cart
                                </button>
                            </div>
                        </div>
                        <div class="flex-w flex-l-m p-b-10">
                            <p class="stext-102 cl3" id="product-quanity">Quanity: {{ $product->quanity }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bor10 m-t-50 p-t-43 p-b-40">
            <div class="tab01">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item p-b-10">
                        <a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a>
                    </li>
                    <li class="nav-item p-b-10">
                        <a class="nav-link" data-toggle="tab" href="#information" role="tab">Additional information</a>
                    </li>
                    <li class="nav-item p-b-10">
                        <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews (1)</a>
                    </li>
                </ul>

                <div class="tab-content p-t-43">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <div class="how-pos2 p-lr-15-md">
                            <p class="stext-102 cl6">
                                {!! $product->content !!}
                            </p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="information" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                <ul class="p-lr-28 p-lr-15-sm">
                                    {{-- <li class="flex-w flex-t p-b-7">
                                        <span class="stext-102 cl3 size-205">
                                            Weight
                                        </span>
                                        <span class="stext-102 cl6 size-206">
                                            0.79 kg
                                        </span>
                                    </li>
                                    <li class="flex-w flex-t p-b-7">
                                        <span class="stext-102 cl3 size-205">
                                            Dimensions
                                        </span>
                                        <span class="stext-102 cl6 size-206">
                                            110 x 33 x 100 cm
                                        </span>
                                    </li>
                                    <li class="flex-w flex-t p-b-7">
                                        <span class="stext-102 cl3 size-205">
                                            Materials
                                        </span>
                                        <span class="stext-102 cl6 size-206">
                                            60% cotton
                                        </span>
                                    </li> --}}
                                    <li class="flex-w flex-t p-b-7">
                                        <span class="stext-102 cl3 size-205">
                                            Color
                                        </span>
                                        <span class="stext-102 cl6 size-206">
                                            @foreach ($colors as $key => $color)
                                                @if ($key == count($colors)-1)
                                                    {{ $color['name'] }}.
                                                @else
                                                    {{ $color['name'] }},
                                                @endif
                                            @endforeach
                                        </span>
                                    </li>
                                    <li class="flex-w flex-t p-b-7">
                                        <span class="stext-102 cl3 size-205">
                                            Size
                                        </span>
                                        <span class="stext-102 cl6 size-206">
                                            @foreach ($sizes as $key => $size)

                                                @if ($key == count($sizes)-1)
                                                    {{ $size }}.
                                                @else
                                                    {{ $size }},
                                                @endif
                                            @endforeach
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                <div class="p-b-30 m-lr-15-sm">

                                    <div class="flex-w flex-t p-b-68">
                                        <div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
                                            <img src="images/avatar-01.jpg" alt="AVATAR">
                                        </div>
                                        <div class="size-207">
                                            <div class="flex-w flex-sb-m p-b-17">
                                                <span class="mtext-107 cl2 p-r-20">
                                                Ariana Grande
                                                </span>
                                                <span class="fs-18 cl11">
                                                    <i class="zmdi zmdi-star"></i>
                                                    <i class="zmdi zmdi-star"></i>
                                                    <i class="zmdi zmdi-star"></i>
                                                    <i class="zmdi zmdi-star"></i>
                                                    <i class="zmdi zmdi-star-half"></i>
                                                </span>
                                            </div>
                                            <p class="stext-102 cl6">
                                                Quod autem in homine praestantissimum atque optimum est, id deseruit. Apud ceteros autem philosophos
                                            </p>
                                        </div>
                                    </div>

                                    <form class="w-full">
                                        <h5 class="mtext-108 cl2 p-b-7">
                                            Add a review
                                        </h5>
                                        <p class="stext-102 cl6">
                                            Your email address will not be published. Required fields are marked *
                                        </p>
                                        <div class="flex-w flex-m p-t-50 p-b-23">
                                            <span class="stext-102 cl3 m-r-16">
                                                Your Rating
                                            </span>
                                            <span class="wrap-rating fs-18 cl11 pointer">
                                                <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                <input class="dis-none" type="number" name="rating">
                                            </span>
                                        </div>
                                        <div class="row p-b-25">
                                            <div class="col-12 p-b-5">
                                                <label class="stext-102 cl3" for="review">Your review</label>
                                                <textarea class="size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10" id="review" name="review"></textarea>
                                            </div>
                                            <div class="col-sm-6 p-b-5">
                                                <label class="stext-102 cl3" for="name">Name</label>
                                                <input class="size-111 bor8 stext-102 cl2 p-lr-20" id="name" type="text" name="name">
                                            </div>
                                            <div class="col-sm-6 p-b-5">
                                                <label class="stext-102 cl3" for="email">Email</label>
                                                    <input class="size-111 bor8 stext-102 cl2 p-lr-20" id="email" type="text" name="email">
                                            </div>
                                        </div>
                                        <button class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
                                            Submit
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="bg6 flex-c-m flex-w size-302 m-t-73 p-tb-15">
        <span class="stext-107 cl6 p-lr-25">
            Supplier: {{ $product->supplier->name }}
        </span>
        <span class="stext-107 cl6 p-lr-25">
            Categories: {{ $product->category->name }}
        </span>
    </div>
</section>

@include('elements.footer')

@endsection
