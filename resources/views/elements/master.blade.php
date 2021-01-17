<!DOCTYPE html>
<html lang="en">
<head>
	@yield('title')
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    @include('elements.head')
    @stack('css')
</head>
<body class="animsition">
	<!-- Header -->
    @include('elements.header')

    @yield('content')

    @include('elements.script')
    @stack('js')
</body>
</html>
