@extends('frontend.layouts.master')
@section('renderjs')
@include('frontend.procedimientos programados.partials.pp_atencion_assets')
@endsection
@section('content')
<script>if (window.self !== window.top) document.documentElement.className += ' pp-en-iframe';</script>
<style>
  html.pp-en-iframe .navbar,
  html.pp-en-iframe .footer,
  html.pp-en-iframe .pp-encabezado,
  html.pp-en-iframe .pp-contenedor > .panel > .panel-heading { display: none !important; }
  html.pp-en-iframe #tabNuevaAtencion { position: relative; }
  html.pp-en-iframe #marca_agua_overlay_iframe_pp { display: block !important; }
</style>
@include('frontend.procedimientos programados.partials.pp_atencion_contenido')
@include('frontend.includes.footer')
@endsection
