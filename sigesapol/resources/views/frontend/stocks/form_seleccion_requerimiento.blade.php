@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .table-sortable tbody tr {
        cursor: move;
    }
    #global {        
        width: 95%;        
        margin: 15px 15px 15px 15px;     
        height: 200px !important;        
        border: 1px solid #ddd;
        overflow-y: scroll !important;
    }
    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .row{
        margin-top:10px;
        padding: 0 10px;
    }
    .clickable{
        cursor: pointer;   
    }

    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;        
    }
    .panel-heading div span{
        margin-left:5px;
    }
    .panel-body{
        display: block;
    }
</style>

<script>
    $consul = $("#frmRequerimiento").find("#id_requerimiento");
    $consul.select2();
    //--------------------------------------------------------------------------
    $(document).ready(function () {
        var activeSystemClass = $('.list-group-item.active');

        //something is entered in search form
        $('#system-search').keyup(function () {
            var that = this;
            // affect all table rows on in systems table
            var tableBody = $('.table-list-search tbody');
            var tableRowsClass = $('.table-list-search tbody tr');
            $('.search-sf').remove();
            tableRowsClass.each(function (i, val) {

                //Lower text for case insensitive
                var rowText = $(val).text().toLowerCase();
                var inputText = $(that).val().toLowerCase();
                if (inputText != '')
                {
                    $('.search-query-sf').remove();
                    
                } else
                {
                    $('.search-query-sf').remove();
                }

                if (rowText.indexOf(inputText) == -1)
                {
                    //hide rows
                    tableRowsClass.eq(i).hide();

                } else
                {
                    $('.search-sf').remove();
                    tableRowsClass.eq(i).show();
                }
            });
            //all tr elements are hidden
            if (tableRowsClass.children(':visible').length == 0)
            {
                tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No existe el requerimiento que busca.</td></tr>');
            }
        });
    });
</script>
@endsection
@section('content')
<div class="container">

    <h4>
        {{$farmacia}}
        <a href="{{ url('/stocks/create') }}" class="btn btn-primary pull-right btn-sm" style="display:none">
            <i class="fa fa-plus"></i> Agregar Stock
        </a>
    </h4>
    <div class="row col-md-12">
        <div class="panel panel-primary filterable" >
            <div class="panel-heading">
                <h3 class="panel-title">INGRESO REGULAR DE PRODUCTOS FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS</h3>
            </div>  
            <div class="panel-body">
                <legend>Paso 1. Seleccionar el Requerimiento</legend>
                <div class="col-md-12">
                    <input class="form-control" id="system-search" name="q" placeholder="Buscar Requerimiento">                        
                </div>
                <div class="col-md-12" style="height: 50px" id="global">
                    <table class="table table-list-search">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Nro. Requerimiento</th>
                                <th>Fecha</th>                                                        
                                <th>Descargar</th>
                                <th>Ingresar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 0; ?>
                            @foreach($requerimientos as $r)
                            <tr>
                                <td><?php
                                    $n++;
                                    echo $n;
                                    ?></td>
                                <td>{{$r->numero}}</td>
                                <td>
                                    <?php
                                    $originalDate2 = $r->fecha;
                                    $newDate2 = date("d-m-Y", strtotime($originalDate2));
                                    echo $newDate2;
                                    ?> 
                                </td>
                                <td><a href="/imprimir_requerimientos/{{$usuario}}/{{$r->id}}" class="btn btn-success"><i class="fa fa-file-excel-o "></i> Descargar</a></td>                            
                                @if($r->estado=="atendido")
                                <td><a href="/formulario_add_guia/{{ Auth::user()->id }}/{{$r->id}}" class="btn btn-info"><i class="fa fa-check "></i> Atendido</a></td>
                                @else
                                <td><a href="/formulario_add_guia/{{ Auth::user()->id }}/{{$r->id}}" class="btn btn-warning"><i class="fa fa-plus "></i> Seleccionar</a></td>
                                @endif
                            </tr>
                            @endforeach                            
                        </tbody>
                    </table>   
                </div>
            </div>
        </div>

    </div>
</div>
@include('frontend.includes.footer')
@endsection
