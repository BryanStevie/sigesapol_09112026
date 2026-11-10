<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
<style>
    .table-sortable tbody tr {
        cursor: move;
    }
    /*
    #global {        
        width: 95%;        
        margin: 15px 15px 15px 15px;     
        height: 380px !important;        
        border: 1px solid #ddd;
        overflow-y: scroll !important;
    }
    */
    #global {
        min-height: 650px !important;
        /*width: auto;*/
        width: 100%;
        border: 1px solid #ddd;
        margin:15px
            /* background: #f1f1f1;*/
            /*overflow-y: scroll !important;*/
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

    .dataTables_filter {
        display: none;
    }
    
    .help-block {
        display: block;
        margin-top: 1px;
        margin-bottom: 10px;
        color: #737373;
    }
    
    .modal-dialog{
        /* width: 70% !important; */
    }
    
    #tarjetas .panel {
        text-align: center;
        font-weight: bold;
    }
</style>

<script>

    $(document).ready(function () {
        $('#example').DataTable({
            "language": {
                "emptyTable": "No se encontraron resultados"
            }
        });
        // $("#system-search").keyup(function() {
        //     var dataTable = $('#example').dataTable();
        //     dataTable.fnFilter(this.value);
        // });

        function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }

        $("#establecimiento").select2({
            width: 'resolve'
        });
        $("#tipo_atencion").select2({
            width: 'resolve'
        });

        $('#servicio_se').select2();
        @if(isset($id_servicio))
            var servicioSelect = '{{ $id_servicio }}';
        @else
            var servicioSelect = '';
        @endif
        console.log(servicioSelect);
        function getFiltros(){
            // Solicitud AJAX
            $.ajax({
                url: "/visor_camas_filtro/getFiltros",  // Cambia por la URL de tu servidor o endpoint
                type: "POST",               // Método de la solicitud (puede ser 'GET' o 'POST')
                data: { atencion: $("#tipo_atencion").val(),establecimiento:$("#establecimiento").val() },   // Datos enviados al servidor
                dataType: "json",           // Tipo de datos esperados como respuesta (puede ser 'json', 'html', 'text', etc.)
                success: function (response) {
                    // Manejar la respuesta exitosa
                    console.log(response);
                    $('#servicio_se').empty().append(
                        $('<option>', {
                            value: '',
                            text: 'Todo'
                        })
                    );
                    response.forEach(function (item) {
                        $('#servicio_se').append(
                            $('<option>', {
                                value: item.id,
                                text: item.nombre
                                })
                            );
                        });
                            // Verificar si la variable tiene un valor válido
                    if (servicioSelect) {
                        $('#servicio_se').val(servicioSelect).trigger('change');
                    }
                    //$("#respuesta").html(`<p>Respuesta del servidor: ${response.mensaje}</p>`);
                },
                error: function (xhr, status, error) {
                    // Manejar errores
                    console.error("Error en la solicitud:", status, error);
                    //$("#respuesta").html("<p>Ocurrió un error. Inténtalo de nuevo.</p>");
                }
            });
        }
        getFiltros();

    
        $( "#tipo_atencion" ).on( "change", function() {
          getFiltros();
        } );


    });

    function getAtencion(){

        $('#tipo_atencion').empty();
        $('#servicio').empty();
        $('#pabellon').empty();

        var id_establecimiento = $('#establecimiento').val();
        if (id_establecimiento != "") {
            $.ajax({
                url: '/obtener_atencion_por_idestablecimiento/' + id_establecimiento,
                success: function(result){                    
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    $(result).each(function (ii, oo) {
                        if (oo.nombre_atencion == "EMERGENCIA") {
                            newOption += "<option value='"+oo.nombre_atencion+"'>"+oo.nombre_atencion+"</option>";
                        }
                    });                 
                    $('#tipo_atencion').html(newOption);
                }
            });
        }
    }

    function getServicio(){
        
        $('#servicio').empty();
        $('#pabellon').empty();

        var tipo_atencion = $('#tipo_atencion').val();
        var id_establecimiento = $('#id_establecimiento').val();

        if (tipo_atencion != "") {
            $.ajax({
                url: '/obtener_servicio_por_idestablecimiento_tipoatencion/' + id_establecimiento + '/' + tipo_atencion,
                success: function(result){                    
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    newOption += "<option value='0'>TODOS</option>";
                    $(result).each(function (ii, oo) {
                        newOption += "<option value='"+oo.id+"'>"+oo.servicio+"</option>";
                    });                 
                    $('#servicio').html(newOption);
                    if (tipo_atencion == "EMERGENCIA") {
                        $('#servicio').val("76");
                        $('#servicio').change();
                    }
                }
            });
        }
    }

    function getPabellon(){

        $('#pabellon').empty();

        var id_servicio = $('#servicio').val();
        
        if (id_servicio != "" && id_servicio != '0') {
            
            var id_establecimiento = $('#id_establecimiento').val();
            var tipo_atencion = $('#tipo_atencion').val();

            if (tipo_atencion == "HOSPITALIZACION") {
                $.ajax({
                    url: '/obtener_pabellon_por_idestablecimiento_tipoatencion_idservicio/' + id_establecimiento + '/' + tipo_atencion + '/' + id_servicio,
                    success: function(result) {
                        $('#titulo_pabellon_cuarto').text("Pabellón");
                        var newOption = "";
                        newOption = "<option value=''>--Seleccionar-</option>";
                        $(result).each(function (ii, oo) {
                            newOption += "<option value='"+oo.id_pabellon+"'>"+oo.pabellon+"</option>";
                        });                 
                        $('#pabellon').html(newOption);
                    }
                });
            } else if (tipo_atencion == "EMERGENCIA") {
                $.ajax({
                    url: '/obtener_cuarto_por_idestablecimiento_tipoatencion/' + id_establecimiento + '/' + tipo_atencion,
                    success: function(result){
                        $('#titulo_pabellon_cuarto').text("Área");
                        var newOption = "";
                        newOption = "<option value=''>--Seleccionar-</option>";
                        $(result).each(function (ii, oo) {
                            newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
                        });                 
                        $('#pabellon').html(newOption);
                    }
                });
            }
        }
    }

    function validarFormulario() {
        var validado = false;

        var id_establecimiento = $("#establecimiento").val();
        if (id_establecimiento == '') {
            bootbox.alert("Debe seleccionar un <b>Establecimiento</b>.");
            return;
        }
        var tipo_atencion = $("#tipo_atencion").val();
        if (tipo_atencion == '') {
            bootbox.alert("Debe seleccionar un <b>Tipo de Atención</b>.");
            return;
        }
        // var id_servicio = $("#servicio").val();
        // if (id_servicio == '') {
        //     bootbox.alert("Debe seleccionar un <b>Servicio</b>.");
        //     return;
        // }
        validado = true
        if (validado) {
            $("#frmVisorFiltro").submit();
        }
    }

    function detalle_cama(id_establecimiento,nombre_atencion,id_servicio,servicio,nombre_cuarto) {
        $.ajax({
            dataType: 'html',
            type: 'POST',
            url: '/visor_camas_detalle2',
            data: {
                "_token": "{{ csrf_token() }}",
                id_establecimiento: id_establecimiento,
                nombre_atencion: nombre_atencion,
                nombre_cuarto:nombre_cuarto,
                id_servicio: id_servicio
            },
            success: function(result) {
                if(result!=""){
                    $('#spanServicio').text(nombre_cuarto);
                    $('.modal-dialog').css('width', '70%');
                    $('#modal1 .modal-body').css('height', 'auto');
                    $('#divVisorDetalle').html(result);
                    $('#modal1').modal('show');
                }
            },
            error: function(xhr, status, error) {
                bootbox.alert("Ha ocurrido un error.");
            }
        });
    }

    function detalle_cama_hospitalizacion(id_establecimiento,nombre_atencion,id_servicio,servicio,nombre_cuarto,idpiso,idpabellon,idservicio) {
        $.ajax({
            dataType: 'html',
            type: 'POST',
            url: '/visor_camas_detalle2',
            data: {
                "_token": "{{ csrf_token() }}",
                id_establecimiento: id_establecimiento,
                nombre_atencion: nombre_atencion,
                nombre_cuarto:nombre_cuarto,
                id_servicio: id_servicio,
                idPiso:idpiso,
                idPabellon:idpabellon,
                idServicio:idservicio
            },
            success: function(result) {
                if(result!=""){
                    $('#spanServicio').text(nombre_atencion);
                    $('.modal-dialog').css('width', '70%');
                    $('#modal1 .modal-body').css('height', 'auto');
                    $('#divVisorDetalle').html(result);
                    $('#modal1').modal('show');
                }
            },
            error: function(xhr, status, error) {
                bootbox.alert("Ha ocurrido un error.");
            }
        });
    }

    function cerrarModal1() {
        $("#modal1").modal("hide");
    }

    function cerrarModal2() {
        $("#modal2").modal("hide");
    }

</script>
@endsection
@section('content')
<div class="container">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title" style="text-align: center">Visor de Camas</h3>
        </div>  
        <div class="panel-body">
            <div id="tarjetas">
                <div class="row">
                    <div class="col-md-12 ">
                        <h5><b>Actualizado al {{$fecha_formato}} a las {{$hora_formato}}</b></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-3 col-md-2">
                        <div class="panel panel-primary" style="background-color: rgb(223, 240, 216)">
                            <div class="panel-body">
                                Total<br>camas<br>
                                <i class="fa fa-bed fa-2x" aria-hidden="true"></i>&emsp;{{$sumas['total']}}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-2">
                      <div class="panel panel-default">
                          <div class="panel-body">
                              Camas<br>Libres<br>
                              <span class="glyphicon glyphicon-bed" style="font-size: 25px" aria-hidden="true"></span>&emsp;{{$sumas['c_libres']}}
                          </div>
                      </div>
                  </div>
        
                    <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="panel panel-info" style="background-color: rgb(217, 237, 247)">
                            <div class="panel-body">
                                Camas<br>ocupadas <br>
                                <i class="fa fa-bed fa-2x" aria-hidden="true"></i>&emsp;{{$sumas['c_ocupadas']}}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-3 col-md-2">
                        <div class="panel panel-danger" style="background-color: rgb(242, 222, 222)">
                            <div class="panel-body">
                                Camas<br>Reparacion<br>
                                <i class="fa fa-wrench fa-2x" aria-hidden="true"></i>&emsp;{{$sumas['c_bloqueadas']}}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3 col-md-2">
                      <div class="panel panel-danger" style="background-color: rgb(176, 176, 176)">
                          <div class="panel-body">
                              Camas<br>Sin Situacion<br>
                              <i class="fa fa-wrench fa-2x" aria-hidden="true"></i>&emsp;{{$sumas['c_sin_situacion']}}
                          </div>
                      </div>
                  </div>
                </div>
            </div>
            <div id="filtros">
                <div class="row">
                    <form id="frmVisorFiltro" class="form-horizontal" method="post" action="{{ route('visor_camas_filtro')}}">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id_user" value="{{ $id_user }}">
                            <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{ $id_establecimiento_user}}">
                            <div class="col-md-4">
                                <b>Establecimiento</b>
                                <select name="establecimiento" id="establecimiento" class="form-control input-sm" onchange="getAtencion()" style="width:100%">
                                    <option value="">Seleccione ...</option>
                                    @if (isset($id_establecimiento))
                                        @foreach ($establecimientos as $item)
                                            <option value="{{$item->id}}" @if($item->id==$id_establecimiento) selected @endif>{{$item->establecimiento}}</option>
                                        @endforeach
                                    @else
                                        @foreach ($establecimientos as $item)
                                            <option value="{{$item->id}}" @if($item->id==$id_establecimiento_user) selected @endif>{{$item->establecimiento}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <b>Atención</b>
                                <select name="tipo_atencion" id="tipo_atencion" class="form-control input-sm" onchange="getServicio()" style="width:100%">
                                    <option value="">Seleccione ...</option>
                                    <?php $i=0; ?>
                                   
                                    @if (isset($tipo_atencion))
                                        @foreach ($tipo_atenciones as $item)
                                            <option value="{{$item->nombre_atencion}}" @if($item->nombre_atencion==$tipo_atencion) selected @endif>{{$item->nombre_atencion}}</option>
                                            <?php $i++; ?>
                                        @endforeach
                                    @else
                                        @foreach ($tipo_atenciones as $item)
                                            {{-- @if($item->nombre_atencion=="EMERGENCIA") --}}
                                            <option value="{{$item->nombre_atencion}}" @if($i==0) selected @endif>{{$item->nombre_atencion}}</option>
                                            {{-- @endif --}}
                                            <?php $i++; ?>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                              <b>Servicio</b>
                              <select class="form-control input-sm" id="servicio_se" name="servicio_se" style="width:100%">
                                <option value="">Todo</option>

                              </select>
                            </div>

                            {{-- <div class="col-md-3">
                                <b>Servicio</b>
                                <select name="servicio" id="servicio" class="form-control input-sm" onchange="getPabellon()" style="width:100%">
                                    <option value="">Seleccione ...</option>
                                    @if (isset($id_servicio))
                                        <option value="0" selected>TODOS</option>
                                        @foreach ($servicios as $item)
                                            <option value="{{$item->id}}" @if($item->id==$id_servicio) selected @endif>{{$item->servicio}}</option>
                                        @endforeach
                                    @else
                                        <option value="0" selected>TODOS</option>
                                        @foreach ($servicios as $item)
                                            <option value="{{$item->id}}">{{$item->servicio}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div> --}}
                            
                            {{-- <div class="col-md-2">
                                <b><span id="titulo_pabellon_cuarto">Pabellón</span></b>
                                <select name="pabellon" id="pabellon" class="form-control input-sm">
                                    <option value="">Seleccione ...</option>
                                    @if (isset($id_pabellon) && ($pabellones != ""))
                                        @foreach ($pabellones as $item)
                                            <option value="{{$item->id_pabellon}}" @if($item->id_pabellon==$id_pabellon) selected @endif>{{$item->pabellon}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div> --}}

                            <div class="col-md-4">
                                <b>&nbsp;</b>
                                <input onclick="validarFormulario();" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
            <br>
            
            <div class="row">
                <div class="panel panel-primary filterable" >
                    <div class="panel-heading">
                        <h3 class="panel-title" style="text-align: center">{{$tipo_atencion_seleccionado}} - {{$nombre_servicio}}</h3>
                    </div>  
                    <div class="panel-body">
                        <div class="col-md-12" id="">
                            <div class="table-responsive">
                                <table id="example" class="table table-list-search table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>                                          
                                            <th style="text-align:center">Pabellon</th>
                                            @if($tipo_atencion_seleccionado=="EMERGENCIA")
                                            <th style="text-align:center">Cuarto</th>
                                            @else
                                            <th style="text-align:center">Servicio</th>
                                            @endif
                                            <th style="text-align:center">Total Camas</th>
                                            <th style="text-align:center">Camas Ocupadas</th>
                                            <th style="text-align:center">Camas Libres</th>
                                            <th style="text-align:center">Camas Reparacion</th>
                                            <th style="text-align:center">Detalle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($estadistica_camas_todos_servicios as $item)
                                        <?php ?>
                                        <tr>
                                            <td>{{$i++}}</td>                                         
                                            <td style="text-align:center">{{$item->pabellon}}</td>
                                            @if($item->nombre_atencion=="EMERGENCIA")
                                            <td>{{$item->cuarto}}</td>
                                            @else
                                            <td style="text-align:center">
                                              @foreach(explode(',', $item->nombre_atencion) as $atencion)
                                              
                                                <span class="label label-info" style="max-width: 100px;">{{ trim($atencion) }}</span>
                                              
                                              @endforeach
                                            </td>
                                            @endif
                                            <td style="text-align:center">{{$item->total}}</td>
                                            <td style="text-align:center">{{$item->c_ocupadas}}</td>
                                            <td style="text-align:center">{{$item->c_libres}}</td>
                                            <td style="text-align:center">{{$item->c_bloqueadas}}</td>
                                            @if($item->nombre_atencion=="EMERGENCIA")
                                            <td><button onclick="detalle_cama({{$item->id_establecimiento}},'{{$item->nombre_atencion}}',{{$item->id_cuarto}},'{{$item->consultorio}}','{{$item->cuarto}}')" class="btn btn-sm btn-success">Detalle</button></td>
                                            @else
                                            <td><button onclick="detalle_cama_hospitalizacion({{$item->id_establecimiento}},'{{$item->nombre_atencion}}',{{$item->id}},'{{$item->consultorio}}','','{{$item->idpiso}}','{{$item->idpabellon}}','{{$item->idservicios}}')" class="btn btn-sm btn-success">Detalle</button></td>
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
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-2" id="modal1" role="dialog" style="overflow-y: scroll;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" aria-label="Close" onclick="cerrarModal1()">&times;</button>
                <h5 class="modal-title"><span id="spanServicio"></span></h5>
            </div>
            <div class="modal-body">
                <div id="divVisorDetalle"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal1()">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
