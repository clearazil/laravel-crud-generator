<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="pt-5">
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('home') }}">Document</a>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

      <main class="container">

        @yield('content')

      </main><!-- /.container -->

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
