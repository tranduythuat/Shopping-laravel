@extends('elements.master')

@prepend('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('theme/shoesshop/css/checkout.css') }}">

@endprepend

@prepend('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
@endprepend

@section('title')
    <title>Thank you</title>
@endsection

@section('content')
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
    <div class="container m-t-100 m-b-150">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }} <a href="{{ route('home') }}"> Continue shopping</a>
            </div>
        @else
            <a href="{{ route('home') }}"> Continue shopping</a>
        @endif
    </div>

    @include('elements.footer')

@endsection
