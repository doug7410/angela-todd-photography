<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Angela Todd Photography</title>
  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.6.0/tiny-slider.css">
  <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
</head>
<body>
<div id="app">
@include('partials.header')

<div class="container">
  @yield('content')
</div>

@include('partials.footer')
</div>
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>

