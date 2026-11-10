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
    @yield('meta')

    @yield('before-styles-end')
    {!! HTML::style(elixir('css/frontend.css')) !!}
    @yield('after-styles-end')

    {!! Html::style('js/select2-4/dist/css/select2.css') !!} 
    {!! Html::style('css/datatables/dataTables.bootstrap.min.css') !!}
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <!-- Icons-->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="{{asset('images/logo_sanidad.png')}}"> 
    {!! HTML::script("js/vendor/modernizr-2.8.3.min.js") !!}
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
    {!! HTML::style('js/chosen/chosen.min.css') !!}
</head>

<body
    style="background-image: url('../../images/pharmacy1.jpg'); background-repeat: no-repeat; background-attachment: fixed; height: auto; width: auto">

    @include('frontend.includes.nav')
    <!--@include('includes.fecha_hora_actual.actualidad')-->
    <div class="container-fluid">
        @yield('content')
    </div><!-- container -->
    
    <script>  
        window.jQuery || document.write('<script src="{{asset('js / vendor / jquery - 1.11.2.min.js')}}"><\/script>')
    </script>

    {!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
    {!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
    {!! HTML::script('js/vendor/bootstrap.min.js') !!}
    {!! HTML::script("js/select2-4/dist/js/select2.js") !!} 
   
</body>

</html>