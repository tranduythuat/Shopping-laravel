@extends('template')

@section('content')

<form class="box-form validate-form" id="formRegister" method="POST" action="{{ route('register') }}">
    @csrf
    <span class="box-form-title">
        Register
    </span>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger py-0">
                <small>{{$error}}</small>
            </div>
        @endforeach
    @endif
    <div class="wrap-input100">
        <input class="input100" type="text" name="name" placeholder="Name" value="{{ old('name') }}">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-user" aria-hidden="true"></i>
        </span>
    </div>
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

    <div class="wrap-input100">
        <input class="input100" type="password" name="password_confirmation" placeholder="Password Confirm" value="{{ old('password_confirmation') }}">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fa fa-check-circle" aria-hidden="true"></i>
        </span>
    </div>

    <div class="container-box-form-btn">
        <button class="box-form-btn register-form-btn">
            Register
        </button>
    </div>

    <div class="text-center p-t-136">
        <a class="txt2" href="{{ route('login') }}">
            Already an account? Login here
            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
        </a>
    </div>
</form>

@endsection
@prepend('js')
    <script>
        $(document).ready(function () {
            $(".register-form-btn").click(function(){
                $("#formRegister").submit();
            })
        });
    </script>
@endprepend
