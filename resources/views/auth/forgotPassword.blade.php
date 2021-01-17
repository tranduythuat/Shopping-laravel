@extends('template')

@section('content')

<form class="box-form validate-form" id="formForgotPass" method="POST" action="{{ route('password.request') }}">
    @csrf
    <span class="box-form-title">
        Reset Password
    </span>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            <small>{{ session('status') }}</small>
        </div>
    @endif

    <div class="wrap-input100">
        <input class="input100" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-envelope" aria-hidden="true"></i>
        </span>
    </div>
    @error('email')
        <div class="text-danger ml-2"><small>{{ $message }}</small></div>
    @enderror
    <div class="container-box-form-btn">
        <button class="box-form-btn forgot-form-btn">
            Reset
        </button>
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
            $(".forgot-form-btn").click(function(){
                $("#formForgotPass").submit();
            })
        });
    </script>
@endprepend
