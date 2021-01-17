@extends('elements.master')

@prepend('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('theme/shoesshop/css/checkout.css') }}">

@endprepend

@prepend('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
@endprepend

@section('title')
    <title>Shopping Cart</title>
@endsection

@section('content')
<div class="container">
    <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-50 p-lr-0-lg">
        <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
            Home
            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
        </a>

        <span class="stext-109 cl4">
            Shoping Cart
        </span>
    </div>
</div>
<form class="bg0 p-t-15 p-b-85">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                <div class="m-l-25 m-r--38 m-lr-0-xl">
                    <div class="wrap-table-shopping-cart">
                        <table class="table-shopping-cart">
                            <tr class="table_head">
                                <th class="column-1">Product</th>
                                <th class="column-2"></th>
                                <th class="column-3">Price</th>
                                <th class="column-4">Quantity</th>
                                <th class="column-5">Total</th>
                            </tr>
                            @if (count($cart) === 0)
                                <tr>
                                    <td colspan="5" class="p-4">No product</td>
                                </tr>
                            @else
                                @foreach ($cart as $key => $cart_item)
                                    <tr class="table_row">
                                        <td class="column-1">
                                            <div class="how-itemcart1">
                                                <img src="{{ $cart_item['photo'] }}" alt="IMG">
                                            </div>
                                        </td>
                                        <td class="column-2">{{ $cart_item['name'] }}</td>
                                        <td class="column-3">$ {{ number_format($cart_item['price'], '2', ',', '.') }}</td>
                                        <td class="column-4">
                                            <div class="wrap-num-product flex-w m-l-auto m-r-0">
                                                <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                    <i class="fs-16 zmdi zmdi-minus"></i>
                                                </div>

                                                <input data-cart="{{ $key }}" id="num-product-{{ $key }}" class="mtext-104 cl3 txt-center num-product" type="number" name="num-product1" value="{{ $cart_item['qty'] }}">

                                                <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                    <i class="fs-16 zmdi zmdi-plus"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td id="total-item-{{ $key }}" class="column-5">$ {{ number_format($cart_item['price']*$cart_item['qty'], '2', ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>

                    <div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
                        <div class="flex-w flex-m m-r-20 m-tb-5">
                            <input class="stext-104 cl2 plh4 size-117 bor13 p-lr-20 m-r-10 m-tb-5" type="text" name="coupon" placeholder="Coupon Code">

                            <div class="flex-c-m stext-101 cl2 size-118 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-5">
                                Apply coupon
                            </div>
                        </div>

                        <div class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
                            Update Cart
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                    <h4 class="mtext-109 cl2 p-b-30">
                        Cart Totals
                    </h4>

                    <div class="flex-w flex-t bor12 p-b-13">
                        <div class="size-208">
                            <span class="stext-110 cl2">
                                Subtotal:
                            </span>
                        </div>

                        <div class="size-209">
                            <span class="mtext-110 cl2" id="sub_total">
                                @if ($totalPrice !== '')
                                    $ {{ number_format($totalPrice, '2', ',', '.') }}
                                @else
                                    $ 0.00
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                        <div class="size-208 w-full-ssm">
                            <span class="stext-110 cl2">
                                Shipping:
                            </span>
                        </div>

                        <div class="size-208 p-r-18 p-r-0-sm w-full-ssm">
                            <span class="mtext-110 cl2">
                                    $ 1.30
                            </span>
                        </div>
                    </div>

                    <div class="flex-w flex-t p-t-27 p-b-33">
                        <div class="size-208">
                            <span class="mtext-101 cl2">
                                Total:
                            </span>
                        </div>

                        <div class="size-209 p-t-1">
                            <span class="mtext-110 cl2" id="totalPrice">
                                @if ($totalPrice !== '')
                                    $ {{ number_format($totalPrice + 1.3, '2', ',', '.') }}
                                @else
                                    $ 0.00
                                @endif
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}" class="flex-c-m stext-101 m-b-15 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('home') }}" class="flex-c-m stext-101 m-b-15 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">Continue shopping</a>
                </div>
            </div>
        </div>
    </div>
</form>

@include('elements.footer')

@endsection
