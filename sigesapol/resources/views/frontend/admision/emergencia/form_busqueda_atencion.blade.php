
@extends('frontend.layouts.master2')
@push('style')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@section('renderjs')
<script src='../funciones/function.js'></script>  
@include('frontend.admision.emergencia.busqueda_atencion.listar_asegurados')
@include('frontend.admision.emergencia.busqueda_atencion.listar_atenciones')
   
<script> 

    $(document).ready(function() {
        $('#campo_buscar_input').show();
        $('#campo_buscar_fecha').hide();
          
        $("#campo_de_busqueda").change(function() { 
            $('#campo_buscar_input').show();
            $('#campo_buscar_fecha').hide();

            var campo_de_busqueda = $("#campo_de_busqueda").val(); 
            if(campo_de_busqueda==3){ 
                $("#valor_de_busqueda_fecha").val('{{\Carbon\Carbon::now()->format('d-m-Y')}}');   

                $("#listaAsegurados").empty();
                $("#listaAtenciones_by_asegurado").empty();
                $("#valor_de_busqueda_fecha").focus();


                $('#valor_de_busqueda_fecha').appendDtpicker({
                    "dateOnly": true,
                    "autodateOnStart": false,
                    "minuteInterval": 5,
                    "amPmInTimeList": false,
                    "locale": "es",
                    "closeOnSelected": true,
                    "dateFormat": "DD-MM-YYYY"
                });

                $('#campo_buscar_input').hide();
                $('#campo_buscar_fecha').show();
            }else{
                $('#campo_buscar_input').show();
                $('#campo_buscar_fecha').hide();

                $("#valor_de_busqueda").val('');
                $("#listaAsegurados").empty();
                $("#listaAtenciones_by_asegurado").empty();
                $("#valor_de_busqueda").focus();
            }  
        });
 
            $('#valor_de_busqueda_fecha').change(function() {
                $("#listaAsegurados").empty();
                $("#listaAtenciones_by_asegurado").empty();
                $("#valor_de_busqueda").focus(); 
                
                listar_asegurados_consultados();
            }); 
    });
    
</script>
 
@endsection 

<style>
  #miTabla thead th {
     position: sticky;
     top: 0;
     background-color: white;
     z-index: 1;
 }
     #miTablaAtenciones thead th {
        position: sticky;
        top: 0;
        background-color: white;
        z-index: 1;
    }
  /*************Styles de sweetAlert2*************/

  .swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen)>.swal2-modal {
    margin: 15rem auto auto auto !important;
  }

  .swal2-popup {
    width: 50em !important;
  }

  .swal2-popup,
  .swal2-header {
    padding: 0 !important;
  }

  .swal2-header {
    background: #3c763d;
  }

  .swal2-html-container {
    margin: 0 !important;
    padding: 1rem 0 !important;
  }

  .titulo-padding {
    padding: 1rem 0 0 0;
  }

  .titulo-padding p {
    color: white;
    margin: 0;
    font-size: 2.5rem;
  }

  .descripcion-alert {
    font-size: 1.5rem;
    padding: 0 1rem;
  }

  .descripcion-alert {
    margin: 0;
  }

  .descripcion-alert span {
    font-weight: 700;
  }

  .swal2-actions {
    margin: 0 !important;
    padding: 1rem;
    justify-content: space-evenly;
  }

  .swal2-actions button {
    margin: 0 !important;
  }

  .borde-alerta {
    border-color: red !important;
    border: 2px red solid !important;
    outline: 0;
    border-radius: 4px;
    box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important;
  }
</style> 
@section('content')
 
<div class="container" style="width: 1300px !important;">
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <div class="row">
          <div class="col-lg-8 col-md-8 col-sm-12">
            <h3 class="panel-title" style="padding-top: 5px;">MÓDULO DE BÚSQUEDA DE ATENCIONES </h3>
          </div> 
        </div>
      </div>
      <div class="panel-body"> 
 

          <fieldset>
            <div class="panel with-nav-tabs panel-default"
              style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
              <div class="panel-heading">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                      aria-expanded="true"><b><i class="fa fa-search" aria-hidden="true"></i>
                         BUSCAR PACIENTE</b></a></li>
                </ul>
              </div>
              <div class="panel-body" >
                <div class="tab-content"> 
                  <!----------------------------- DATOS PACIENTE --------------------------------->
                  <!----------------------------- DATOS PACIENTE --------------------------------->
                  <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                    <fieldset>
                        <div class="row"> 
                            <div class="col-lg-3 col-md-6 col-sm-12"> 
                                <label>TIPO DE BÚSQUEDA</label> 
                                <select class="form-control input-sm" name="campo_de_busqueda" id="campo_de_busqueda">
                                    <option value="1">NÚMERO DE DNI</option>
                                    <option value="2">APELLIDOS</option> 
                                    <option value="3">FECHA</option> 
                                    <option value="4">NÚMERO DE CARNET</option>
                                    <option value="5">HC GENERAL</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label id="titulo_valor_de_busqueda" name="titulo_valor_de_busqueda">TIPO DE BÚSQUEDA</label> 

                                <div id="campo_buscar_input" name="campo_buscar_input">
                                    <input  type="text" name="valor_de_busqueda" id="valor_de_busqueda"  style="text-transform: uppercase;"
                                    class="form-control input-sm"  autocomplete="off">
                                </div>

                                <div id="campo_buscar_fecha" name="campo_buscar_fecha" hidden> 
                                    <input class="form-control input-sm" type="text" name="valor_de_busqueda_fecha" id="valor_de_busqueda_fecha" > 
                                </div>
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-12"> 
                                <label style="visibility: hidden;">CONSULTAR</label> <br>
                                <a class="btn btn-success btn-sm btn-block" id="consultar_busqueda" name="consultar_busqueda">CONSULTAR</a> 
                            </div> 
                        </div>
                    </fieldset>
                    <!----------------------------- DATOS PACIENTE --------------------------------->
                    <!----------------------------- DATOS PACIENTE ---------------------------------> 
                  </div>
                </div>
              </div>
          </fieldset>
          <!----------------------------- DATOS       MADRE      PACIENTE display:none --------------------------------->
          <!----------------------------- DATOS       MADRE      PACIENTE display:none --------------------------------->
 
 

          <!------------------    lista de pecientes    ------------------> 
          <fieldset >
            <div class="row"> 
                <div class="col-sm-12"> 
                    <div class="box-body">
                        <b><h5 style="font-weight: 600;">RESULTADOS DE BÚSQUEDA</h5></b>
                        <table class="table table-bordered dt-responsive "  id="miTabla" style="position: relative; height: 200px; 
                            overflow: auto;display: block; width:100%; font-size: 15px; text-align: center">
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-size: 12px; width: 10%;">DOCUMENTO</th> 
                                    <th class="text-center" style="font-size: 12px; width: 10%;">CIP</th> 
                                    <th class="text-center" style="font-size: 12px; width: 20%;">GRADO</th> 
                                    <th class="text-center" style="font-size: 12px; width: 10%;">HC GENERAL</th> 
                                    <th class="text-center" style="font-size: 12px; width: 10%;">PARENTESCO</th> 
                                    <th class="text-center" style="font-size: 12px; width: 35%;">NOMBRE Y APELLIDO</th> 
                                    <th class="text-center" style="font-size: 12px; width: 15%;">ESTADO</th> 
                                </tr>
                            </thead>
                            <tbody id="listaAsegurados" class="listaAsegurados">
                            </tbody> 
                        </table>
                    </div>
                </div>
            </div>
          </fieldset> 
          <!------------------    lista de pecientes    ------------------> 

 

          <!------------------    lista de atenciones    ------------------> 
          <fieldset >
            <div class="row"> 
                <div class="col-sm-12"> 
                    <div class="box-body">
                        <b><h5 style="font-weight: 600;">ATENCIONES EN EMERGENCIA DEL PACIENTE SELECCIONADO</h5></b>
                        <table class="table table-bordered dt-responsive "  id="miTablaAtenciones" style="position: relative; height: 200px; 
                            overflow: auto;display: block; width:100%; font-size: 15px;  text-align: center "> 
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-size: 12px; width: 15%;">FECHA Y HORA INGRESO</th> 
                                    <th class="text-center" style="font-size: 12px; width: 7%;">Nª DE ORDEN</th> 
                                    <th class="text-center" style="font-size: 12px; width: 17%;">ÀREA DE ATENCIÓN</th> 
                                    <th class="text-center" style="font-size: 12px; width: 10%;">DESTINO</th> 
                                    <th class="text-center" style="font-size: 12px; width: 15%;">FECHA Y HORA SALIDA</th> 
                                    <th class="text-center" style="font-size: 12px; width: 20%;">CORONEL DE GUARDIA</th> 
                                    <th class="text-center" style="font-size: 12px; width: 10%;">BOLETA DE PAGO</th> 
                                    <th class="text-center" style="font-size: 12px; width: 17%;">OBSERVACION</th> 
                                </tr>
                            </thead>
                            <tbody id="listaAtenciones_by_asegurado" class="listaAtenciones_by_asegurado">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          </fieldset> 
          <!------------------    lista de atenciones    ------------------> 

 
          
      </div>
    </div>
  </div>
</div>
 
@include('frontend.includes.footer')
@endsection