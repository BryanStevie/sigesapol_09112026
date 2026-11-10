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
		else echo "Gestión de Farmacias";
		?>
		</title>
        <meta name="description" content="@yield('meta_description', 'Default Description')">
        <meta name="author" content="@yield('author', 'Anthony Rappa')">
        @yield('meta')

        @yield('before-styles-end')
        {!! HTML::style(elixir('css/frontend.css')) !!}
        @yield('after-styles-end')

        {!! Html::style('js/select2-4/dist/css/select2.css') !!}
        <!--{!! Html::style('js/select2/select2.css') !!}-->
        {!! Html::style('css/datatables/dataTables.bootstrap.min.css') !!}
        
        <!-- Fonts -->
        <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
        <style>.solomayusculas{text-transform:uppercase;}</style>
        <!-- Icons-->
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
		
		<link rel="icon" type="image/png" href="images/logo_sanidad.png" />
        {!! HTML::script("js/vendor/modernizr-2.8.3.min.js") !!}

        <!-- Datepicker Files -->
		<!--
        <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
        <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-standalone.css')}}">
		-->
		<link rel="stylesheet" href="{{asset('css/jquery.simple-dtpicker.css')}}">
		
        {!! HTML::style('js/chosen/chosen.min.css') !!}
    </head>

    <!--<body style="background-image: url('../../images/fondo.jpg'); background-repeat: no-repeat; background-attachment: fixed; ">-->

    <body style="background-image: url('../../images/pharmacy1.jpg'); background-repeat: no-repeat; background-attachment: fixed; height: auto; width: auto">
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
        <script>window.jQuery || document.write('<script src="{{asset('js / vendor / jquery - 1.11.2.min.js')}}"><\/script>')</script>        
        
        {!! HTML::script('js/vendor/bootstrap.min.js') !!}
        {!! HTML::script("js/highcharts.js") !!}
        {!! HTML::script("js/exporting.js") !!}
        {!! HTML::script("js/select2-4/dist/js/select2.js") !!}
        <!--{!! HTML::script("js/select2/select2.js") !!}-->
        {!! HTML::script("js/chosen/chosen.jquery.min.js") !!}
        {!! HTML::script("js/datatables/datatables.min.js") !!}
		
		<!--
        <script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script> 
        <script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>
		-->
		
		<script src="{{asset('js/jquery.simple-dtpicker.js')}}"></script>
		
		<script src="{{asset('js/vendor/bootbox.min.js')}}"></script>
		{!! HTML::script("js/general.js") !!}
		
        <script>
        /*$('.datepicker').datepicker({
            format: "dd-mm-yyyy",
            language: "es",
            autoclose: true,
            weekStart: 0,
            todayBtn: "linked"
        });*/
        </script>


        @yield('before-scripts-end')
        {!! HTML::script(elixir('js/frontend.js')) !!}
        @yield('after-scripts-end')
        @yield('renderjs') 
        
        @include('includes.partials.ga')
       
    </body>
</html>
