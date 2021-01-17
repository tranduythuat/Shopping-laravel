@extends('template')

@section('content')

<form class="box-form validate-form" id="formResetPass" method="POST" action="{{ route('password.update') }}">
    @csrf
    <span class="box-form-title">
        Reset Password
    </span>

    <div class="wrap-input100">
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input class="input100" type="email" name="email" value="{{ $request->email }}">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-envelope" aria-hidden="true"></i>
        </span>
    </div>
    @error('email')
        <div class="text-danger ml-2"><small>{{ $message }}</small></div>
    @enderror

    <div class="wrap-input100">
        <input class="input100" type="password" name="password" placeholder="Password" value="{{ old('password') }}">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </span>
    </div>
    @error('password')
        <div class="text-danger ml-2"><small>{{ $message }}</small></div>
    @enderror

    <div class="wrap-input100">
        <input class="input100" type="password" name="password_confirmation" placeholder="Password Confirm" value="{{ old('password_confirmation') }}">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-check-circle" aria-hidden="true"></i>
        </span>
    </div>
    @error('password_confirmation')
        <div class="text-danger ml-2"><small>{{ $message }}</small></div>
    @enderror

    <div class="container-box-form-btn">
        <button class="box-form-btn reset-form-btn">
            Update
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
            $(".reset-form-btn").click(function(){
                $("#formResetPass").submit();
            })
        });
    </script>
@endprepend
