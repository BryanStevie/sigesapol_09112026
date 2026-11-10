<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>
        <?php 
		if(Session::get('nombreSistema'))echo Session::get('nombreSistema');
		else echo "Sistema de Gestión Hospitalaria de la Sanidad Policial";
		?>
    </title>
    <meta name="description" content="@yield('meta_description', 'Default Description')">
    <meta name="author" content="@yield('author', 'Anthony Rappa')">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.min.css">
    @yield('meta')

    @yield('before-styles-end')
    {!! HTML::style(elixir('css/frontend.css')) !!}
    @yield('after-styles-end')
    @yield('styles')
    {!! Html::style('js/select2-4/dist/css/select2.css') !!} 
    {!! Html::style('css/datatables/dataTables.bootstrap.min.css') !!}
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <style>
        /* td{
            border-top: 0px solid #ddd !important;
        }
        .form-control:focus { 
            box-shadow: inset 0 1px 1px white, 0 0 8px white !important;
        }
        .solomayusculas {
            text-transform: uppercase;
        } 
        .uneditable-input:focus { 
            box-shadow: 0 1px 1px #FFF inset, 0 0 8px #FFF !important; 
        }
        a:link { text-decoration:none  !important;  } 
        a:visited { text-decoration:none  !important;}

        
        .ui-autocomplete {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 10000000;
            display: none;
            float: left;
            min-width: 400px;
            padding: 5px 7px;
            margin: 2px 0 0;
            list-style: none;
            font-size: 15px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            background-clip: padding-box;
        }

        .ui-autocomplete>li>div {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: normal;
            line-height: 1.42857143;
            color: #333333;
            white-space: nowrap;
        }   */

    </style>
    <!-- Icons-->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="{{asset('images/logo_sanidad.png')}}"> 
    {!! HTML::script("js/vendor/modernizr-2.8.3.min.js") !!}

    <!-- Datepicker Files -->
    <!--
        <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
        <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-standalone.css')}}">
		-->
  <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">

    {!! HTML::style('js/chosen/chosen.min.css') !!}
</head>

<!--<body style="background-image: url('../../images/fondo.jpg'); background-repeat: no-repeat; background-attachment: fixed; ">-->
<body style="background-color: rgb(238, 246, 241);">
<!--body
    style="background-image: url('../../images/pharmacy1.jpg'); background-repeat: no-repeat; background-attachment: fixed; height: auto; width: auto"-->
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    @include('frontend.includes.nav')
    <!--@include('includes.fecha_hora_actual.actualidad')-->
    <div class="container-fluid">
        @include('includes.partials.messages')
        @yield('content')
    </div><!-- container -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>  
        window.jQuery || document.write('<script src="{{asset('js / vendor / jquery - 1.11.2.min.js')}}"><\/script>')
    </script>

    {!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
    {!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
    {!! HTML::script('js/vendor/bootstrap.min.js') !!}
    {!! HTML::script("js/highcharts.js") !!}
    {!! HTML::script("js/exporting.js") !!}
    {!! HTML::script("js/select2-4/dist/js/select2.js") !!} 
    {!! HTML::script("js/chosen/chosen.jquery.min.js") !!}
    {!! HTML::script("js/datatables/datatables.min.js") !!}

    {!! HTML::script('js/moment.min.js') !!}
  
    <script src="{{asset('js/jquery.simple-dtpicker.js')}}"></script>
    <script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script> 
    <script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>
   
    <script src="{{asset('Flot/chart.min.js')}}"></script>
    <script src="{{asset('Flot/chartjs-plugin-datalabels.min.js')}}"></script> 

    <script src="{{asset('sweetalert/sweetalert.min.js')}}"></script> 

    <script src="{{asset('js/vendor/bootbox.min.js')}}"></script>
    {!! HTML::script("js/general.js") !!}
 
    <!-- FUNCIONES GENERALES DE USO COMUN -->
    @include('frontend.hospitalizacion.enfermeria.funciones_utilidades.utilidades.utilidades')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"
    crossorigin="anonymous">
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