@extends('template')

@section('content')

<form class="box-form validate-form" id="formLogin" method="POST" action="{{ route('verification.send') }}">
    @csrf
    <span class="box-form-title">
        <p>You must verify your email address, please check your email for a verification link.</p>
    </span>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            <small>{{ session('status') }}</small>
        </div>
    @endif
    <div class="container-box-form-btn">
        <button class="box-form-btn login-form-btn">
            Resend
        </button>
    </div>

    <div class="text-center p-t-136">
        <a class="txt2" href="{{ route('login') }}">
            Login here
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
