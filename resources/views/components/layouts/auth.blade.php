<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="{{ asset('admin') }}/img/icons/icon-48x48.png" />

	<title>{{ $title ?? '' }}</title>

	<link href="{{ asset('admin') }}/css/app.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
	<div class="wrapper">
		<div class="main">

			<main class="content">
                {{ $slot }}
			</main>
		</div>
	</div>

	<script src="{{ asset('admin') }}/js/app.js"></script>

</body>

</html>
