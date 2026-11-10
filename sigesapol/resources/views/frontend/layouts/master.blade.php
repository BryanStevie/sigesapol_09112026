<!doctype html>
<html class="no-js" lang="">

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
    @stack('style')
    <meta name="description" content="@yield('meta_description', 'Default Description')">
    <meta name="author" content="@yield('author', 'Anthony Rappa')">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @yield('meta')

    @yield('before-styles-end')
    {!! HTML::style(elixir('css/frontend.css')) !!}
    @yield('after-styles-end')
   {!! HTML::style(('css/general.css')) !!}
    {!! Html::style('js/select2-4/dist/css/select2.css') !!}
    <!--{!! Html::style('js/select2/select2.css') !!}-->
    {!! Html::style('css/datatables/dataTables.bootstrap.min.css') !!}
    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        .solomayusculas {
            text-transform: uppercase;
        }

        .container {
            min-height: 590px
        }

        .tag_disabled {
            background-color: #EBEBE4;
        }

        html {
            position: relative;
            min-height: 100%;
        }

        body {
            margin-bottom: 40px;
            /* Margin bottom by footer height */
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 40px;
            /* Set the fixed height of the footer here */
        }

        /*************Styles de sweetAlert2*************/
        .titulo-padding {
            padding: 1rem 0 0 0;
        }

        .titulo-padding p {
            color: rgb(255, 255, 255);
            margin: 0;
            font-size: 2.5rem;
        }

        .descripcion-alert {
            font-size: 1.5rem;
            padding: 0 1rem;
            margin: 0;
        }

        .swal2-popup {
            width: 50em !important;
        }

        .swal2-popup,
        .swal2-header {
            padding: 0 !important;
        }

        .swal2-header-generar {
            background: #3c763d;
        }

        .swal2-html-container {
            margin: 0 !important;
            padding: 1rem 0 !important;
        }

        .descripcion-alert span {
            font-weight: 700;
        }

        .swal2-modal {
            min-height: auto;
        }

        .swal2-actions {
            margin-bottom: 2rem;
        }

        .custom-swal-button-cerrar {
            background-color: #3c763d !important;
            color: white;
        }

        .custom-swal-button-cerrar:hover {
            -webkit-box-shadow: 0px 0px 10px -1px rgba(0, 0, 0, 0.75);
            -moz-box-shadow: 0px 0px 10px -1px rgba(0, 0, 0, 0.75);
            box-shadow: 0px 0px 10px -1px rgba(0, 0, 0, 0.75);
        }

        /*********************************************/
        /***************Efecto blur para Alert********/
        #blur-container {
            position: relative;
        }

        .blur-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Color de fondo semitransparente */
            backdrop-filter: blur(5px);
            /* Efecto de desenfoque */
            z-index: 999;
            /* Asegúrate de que el desenfoque esté sobre otros elementos */
        }

        .custom-header-class {
            background-color: #3c763d;
            color: white;
        }

        /*********************************************/
    </style>
    <!-- Icons-->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="icon" type="image/png" href="{{ asset('images/logo_sanidad.png') }}" />
    {!! HTML::script('js/vendor/modernizr-2.8.3.min.js') !!}

    <!-- Datepicker Files -->
    <link rel="stylesheet" href="{{ asset('datePicker/css/bootstrap-datepicker3.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('datePicker/css/bootstrap-standalone.css') }}"> -->

    {!! HTML::style('js/chosen/chosen.min.css') !!}

    @yield('rendercss')

    {!! HTML::style('assets/css/app.css') !!}
</head>

<!--<body style="background-image: url('../../images/fondo.jpg'); background-repeat: no-repeat; background-attachment: fixed; ">-->

<body style="background-color: rgb(238, 246, 241);">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <?php //$bk=25;
    ?>
    @include('frontend.includes.nav')
    <!--@include('includes.fecha_hora_actual.actualidad')-->
    <div class="container-fluid">
        @include('includes.partials.messages')
        @yield('content')
    </div><!-- container -->
    <footer class="footer">
    <div class="col-sm-12 panel panel-primary" style="z-index:1000;margin:0px;padding:0px;border:0!important;background:transparent!important">
      <div class="panel-heading" align="right" style="background:transparent!important;border:0!important; color: #000!important;">DIRSAPOL  Copyright (C) <?php echo date('Y') ?> - Dirección de Sanidad Policial - DIRSAPOL</div>
    </div>
  </footer>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script>
    window.jQuery || document.write('<script src="{{asset('js/vendor/jquery-1.11.2.min.js')}}"><\/script>')
  </script>

  {!! HTML::script('js/vendor/bootstrap.min.js') !!}
  {!! HTML::script("js/highcharts.js") !!}
  {!! HTML::script("js/exporting.js") !!}
  {!! HTML::script("js/select2-4/dist/js/select2.js") !!}
  <!--{!! HTML::script("js/select2/select2.js") !!}-->
  {!! HTML::script("js/chosen/chosen.jquery.min.js") !!}
  {!! HTML::script("js/datatables/datatables.min.js") !!}
  {!! HTML::script("js/moment.min.js") !!}
  <script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script>
  <script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>
  <script src="{{asset('js/vendor/bootbox.min.js')}}"></script>
  {!! HTML::script("js/general.js") !!}

  <script type="text/javascript">
    //$('#id_farmacia').select2();
    $('#id_usuario').select2();
  </script>

<script src="{{asset('sweetalert/sweetalert.min.js')}}"></script> 

    <script>
        $('.datepicker').datepicker({
            format: "dd-mm-yyyy",
            language: "es",
            autoclose: true,
            weekStart: 0,
            todayBtn: "linked"
        });

        function esEntero(numero) {
            if (isNaN(numero)) {
                return false
            } else {
                if (numero % 1 == 0) {
                    return true
                } else {
                    return false
                }
            }
        }
  </script>
  <script type="text/javascript">
    $.ajaxSetup({
        complete: function (xhr) {
            if (xhr.status === 401 || xhr.status === 419) {
            // Redirigir al login
            alert('La sesion ha expirado, inicie sesion de nuevo.');
            window.location.href = '/auth/login'; // Cambiar a la URL de tu página de inicio de sesión
            }
        }
    });
</script>  


  @yield('before-scripts-end')

  {!! HTML::script(elixir('js/frontend.js')) !!}

  @yield('after-scripts-end')

  @yield('renderjs')

  @include('includes.partials.ga')
</body>

</html>
