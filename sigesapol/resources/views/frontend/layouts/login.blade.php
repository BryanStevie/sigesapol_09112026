<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>
    <?php
    if (Session::get('nombreSistema'))
      echo Session::get('nombreSistema');
    else
      echo "Sistema de Gestión Hospitalaria de la Sanidad Policial";
    ?>
  </title>
  <meta name="description" content="@yield('meta_description', 'Default Description')">
  <meta name="author" content="@yield('author', 'Anthony Rappa')">

  @yield('meta')
  
  {!! HTML::style('assets/bootstrap/css/bootstrap.min.css') !!}
  {!! HTML::style('assets/bootstrap/signin.css') !!}

  <!-- Icons-->
  <link rel="apple-touch-icon" href="apple-touch-icon.png">

  <!-- Place favicon.ico in the root directory -->
  <link rel="icon" type="image/png" href="images/logo_sanidad.png" />
</head>

<body class="text-center" style="background-color: rgb(238, 246, 241); ">
  
  @yield('content')

  @yield('renderjs')

  @include('includes.partials.ga')

</body>

</html>
