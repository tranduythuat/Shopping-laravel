<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login </title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{{ asset("theme/form/images/icons/favicon.ico") }}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("theme/form/vendor/bootstrap/css/bootstrap.min.css") }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("theme/form/fonts/font-awesome-4.7.0/css/font-awesome.min.css") }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("theme/form/vendor/animate/animate.css") }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("theme/form/vendor/css-hamburgers/hamburgers.min.css") }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("theme/form/vendor/select2/select2.min.css") }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("theme/form/css/util.css") }}">
	<link rel="stylesheet" type="text/css" href="{{ asset("theme/form/css/main.css") }}">
<!--===============================================================================================-->
</head>
<body>
	<div class="limiter">
		<div class="container-box">
			<div class="wrap-box">
				<div class="box-pic js-tilt" data-tilt>
					<img src="{{ asset('theme/form/images/img-01.png') }}" alt="IMG">
				</div>

				@yield('content')
			</div>
		</div>
	</div>
<!--===============================================================================================-->
	<script src="{{ asset("theme/form/vendor/jquery/jquery-3.2.1.min.js") }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset("theme/form/vendor/bootstrap/js/popper.js") }}"></script>
	<script src="{{ asset("theme/form/vendor/bootstrap/js/bootstrap.min.js") }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset("theme/form/vendor/select2/select2.min.js") }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset("theme/form/vendor/tilt/tilt.jquery.min.js") }}"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
    </script>
    @stack('js')
<!--===============================================================================================-->
	{{-- <script src="js/main.js"></script> --}}

</body>
</html>
