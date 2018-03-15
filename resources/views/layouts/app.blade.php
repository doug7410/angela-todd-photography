<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Angela Todd Photography</title>
  <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
  <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
</head>
<body>
@include('partials.header')

<div id="app" class="container">
  @yield('content')
</div>

@include('partials.footer')

<script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>

