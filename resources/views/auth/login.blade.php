@extends('template')

@section('content')

<form class="box-form validate-form" id="formLogin" method="POST" action="{{ route('login') }}">
    @csrf
    <span class="box-form-title">
        Login
    </span>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger py-0">
                <small>{{$error}}</small>
            </div>
        @endforeach
    @endif

    <div class="wrap-input100">
        <input class="input100" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-envelope" aria-hidden="true"></i>
        </span>
    </div>

    <div class="wrap-input100">
        <input class="input100" type="password" name="password" placeholder="Password" value="{{ old('password') }}">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </span>
    </div>

    <div class="container-box-form-btn">
        <button class="box-form-btn login-form-btn">
            Login
        </button>
    </div>

    <div class="text-center p-t-12">
        <span class="txt1">
            Forgot
        </span>
        <a class="txt2" href="{{ route('password.request') }}">
            Password?
        </a>
    </div>

    <div class="text-center p-t-136">
        <a class="txt2" href="{{ route('register') }}">
            Create your Account
            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
        </a>
    </div>
</form>

@endsection
@prepend('js')
    <script>
        $(document).ready(function () {
            $(".login-form-btn").click(function(){
                $("#formLogin").submit();
            })
        });
    </script>
@endprepend
