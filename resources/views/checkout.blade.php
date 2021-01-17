@extends('elements.master')

@prepend('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('theme/shoesshop/css/checkout.css') }}">

@endprepend

@prepend('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <script src="{{ asset('js/checkout.js') }}"></script>
@endprepend

@section('title')
    <title>Check out</title>
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="col-12">
                <a href="{{ route('home') }}" class="logo py-4 p-l-50">
                    <img src="{{ asset('theme/shoesshop/images/icons/logo-01.png') }}" alt="IMG-LOGO">
                </a>
            </div>
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="fs-25 m-b-15 text-black px-auto">
                        <i class="fa fa-address-card" aria-hidden="true"></i> Receipt Info
                    </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    <form id="user-checkout-info" action="{{ route('transaction') }}" method="POST">
                        @csrf
                        <label for="name">Name</label>
                        <input type="text" class="checkout-input" name="name" id="name" placeholder="Enter Name">
                        <label for="email">Email</label>
                        <input type="email" class="checkout-input" name="email" id="email" placeholder="Enter Email">
                        <label for="phone">Phone</label>
                        <input type="text" class="checkout-input" name="phone" id="phone" placeholder="Enter Phone">
                        <label for="address">Address</label>
                        <textarea name="address" class="checkout-input" id="address" placeholder="Enter Address" cols="30" rows="3"></textarea>
                        <label for="note">Note (option)</label>
                        <textarea type="text" class="checkout-input" name="note" id="note" placeholder="Note..." cols="30" rows="10"></textarea>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="fs-25 text-black">
                        <i class="fa fa-truck" aria-hidden="true"></i> Shipping
                    </div>

                    <div class="list-method-shipping d-flex align-items-center justify-content-between">
                        <div class="custom-control form-control-lg custom-checkbox">
                            <input disabled checked type="checkbox" class="custom-control-input" id="method_shipping">
                            <label class="custom-control-label" for="method_shipping">&nbsp; Deliver</label>
                        </div>
                        <div>
                            <span class="align-middle">$</span><span class="fs-18">1.3</span>
                        </div>
                    </div>

                    <div class="fs-25 text-black m-t-50">
                        <i class="fa fa-credit-card" aria-hidden="true"></i> Payment
                    </div>
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="custom-control form-control-lg custom-checkbox">
                                <input disabled checked type="checkbox" class="custom-control-input" id="method_payment">
                                <label class="custom-control-label" for="method_payment">&nbsp;Payment on delivery (COD)</label>
                            </div>
                            <div>
                                <i class="far fa-money-bill-alt"></i>
                            </div>
                        </div>
                        <div class="card-footer bg-silver">
                            <p class="stext-16 fs-17">You only pay when you receive the item product</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 bg-light">
            <div class="order-box py-3 px-2">
                <h3 class="font-weight-bold">Orders ({{ session('totalQuanity') }} product)</h3>
                <hr>
                <div class="list-order-products">
                    <ul class="order-wrapitem w-full" id="orderWrapItem">
                        @if (session('cart'))
                            @foreach (session('cart') as $key => $cart)
                                <li data-id="{{ $key }}" class="header-order-item d-flex justify-content-start" id="order-item-{{ $key }}">
                                    <div class="header-cart-order-img">
                                        <img src="{{ $cart['photo'] }}" alt="IMG" width="70">
                                    </div>

                                    <div class="header-order-item-txt ml-3 p-t-8">
                                        <a href="#" class="header-cart-item-name m-b-10 hov-cl1 trans-04">
                                            {{ $cart['name'] }}
                                        </a>

                                        <span class="header-cart-item-info">
                                            <p class="m-b-10">{{ $cart['color'] }} <i>Size: {{ $cart['size'] }}</i></p>
                                            <p class="text-primary">{{ $cart['qty'] }} x ${{ number_format($cart['price'], '2', ',', '.') }}</p>
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <p>No product</p>
                        @endif
                    </ul>
                    <hr>
                    <div class="w-full">
                        <div class="header-order-total py-2 d-flex justify-content-between text-secondary fs-18" id="orderTotalPrice">
                            @if (session()->has('totalPrice'))
                                @if (session('totalPrice') === 0)
                                    <span> Provisional:</span>
                                    <span>$0.00</span>
                                @else
                                    <span>Provisional:</span>
                                    <span>${{ number_format(session('totalPrice'), '2', ',', '.') }}</span>
                                @endif
                            @endif
                        </div>
                        <div class="header-order-shipping-fee py-2 d-flex justify-content-between text-secondary fs-18">
                            <span> Shipping fee:</span>
                            <span>$1.3</span>
                        </div>
                        <hr>
                        <div class="header-order-total-fee pb-3 d-flex justify-content-between text-secondary fs-18">
                            <span class="fs-23 "> Total:</span>
                            <span class="font-weight-bold text-info fs-25">${{ session('totalPrice') + 1.3 }}</span>
                        </div>
                        <div class="header-order-buttons pt-3 d-flex justify-content-between align-items-center">
                            <a href="{{ route('cart') }}" class="stext-101 text-primary ">
                                <i class="fa fa-angle-left" aria-hidden="true"></i> Back to cart
                            </a>
                            <a href="javascript:;" id="checkout" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3">
                                Check Out
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
