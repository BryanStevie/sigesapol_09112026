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
        width: 70% !important;
    }
    
</style>

<script>

    //--------------------------------------------------------------------------
    $(document).ready(function () {

    $consul = $("#frmRequerimiento").find("#id_requerimiento");
    $consul.select2();
    //$('#fechaDesde').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true/*"timeOnly": true*/});
    //$('#fechaHasta').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true});

    $('#fechaDesde').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
    });
    $('#fechaHasta').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
            //"dateFormat": "YYYY-MM-DD hh:mm",
    });
    $('#fechaDesde').click(function () {
    var fechaDesdeIni = $('#fechaDesde').val();
    $('#fechaDesde').change(function () {
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    f1 = fechaDesde.split("-");
    f2 = fechaHasta.split("-");
    f3 = new Date();
    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

    if (f1 > f2) {
    bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
    $('#fechaDesde').val(fechaDesdeIni);
    return false;
    }
    });
    });
    $('#fechaHasta').click(function () {
    var fechaHastaIni = $('#fechaHasta').val();
    $('#fechaHasta').change(function () {
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    f1 = fechaDesde.split("-");
    f2 = fechaHasta.split("-");
    f3 = new Date();
    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

    if (f2 < f1) {
    bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
    $('#fechaHasta').val(fechaHastaIni);
    return false;
    }
    /*
    if (f2 > f3) {
    bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
    $('#fechaHasta').val(fechaHastaIni);
    return false;
    }
    */
    });
    });
    $('#fechaHasta').trigger('change');
    $('#fechaDesde').trigger('change');
    var activeSystemClass = $('.list-group-item.active');
    });
    $(document).ready(function () {
    $('#example').DataTable({
    "language": {
    "emptyTable": "No se encontraron resultados"
    }
    });
    $("#system-search").keyup(function() {
    var dataTable = $('#example').dataTable();
    dataTable.fnFilter(this.value);
    });

    function soloNumeros(e) {
    var key = window.Event ? e.which : e.keyCode;
    //alert(key);
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

    });

    function mofificarPruebasEnviadasLNS(id_laboratorio){
    
        bootbox.confirm({ 
        size: "small",
        //size: "short",
        message: "&iquest;Desea modificar la toma de muestra de las pruebas de Laboratorio?", 
        callback: function(result){
            if (result==true) {
                location.href="formulario_edicion_atencion_laboratorio_lns/"+id_laboratorio;
            }
        }
        });
        
    }

</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Buscar Examenes de Laboratorio - {{$farmacia->nombre}}</h3>
        </div>  
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{ route('listar_examenes_laboratorios')}}">
                <div class="col-sm-10">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_user" value="{{ $id_user }}">
                    <label class="col-md-2 control-label" for="textinput">Nro. Documento</label>  
                    <div class="col-md-2">
                        <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                    </div>
                    <label class="col-md-2 control-label" for="textinput">Nro. Prestacion</label>  
                    <div class="col-md-2">
                        <input id="nro_prestacion" name="nro_prestacion" type="text" placeholder="" class="form-control input-sm" value="<?php echo $nro_prestacion ?>" onKeyPress="return soloNumeros(event)" maxlength="12">
                    </div>
                    <label class="col-md-2 control-label" for="textinput">Nro. Ex Laboratorio</label>  
                    <div class="col-md-2">
                        <input id="nro_examen" name="nro_examen" type="text" placeholder="" class="form-control input-sm" value="<?php echo $nro_examen ?>" onKeyPress="return soloNumeros(event)" maxlength="12">
                    </div>
                    
                    <div style="clear:both;margin-bottom:10px"></div>
                    <label class="col-md-2 control-label">Fecha Desde</label>
                    <div class="col-md-2">
                        <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                        <p class="help-block">Fecha Orden</p>
                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                    </div>
                    <label class="col-md-2 control-label">Fecha Hasta</label>
                    <div class="col-md-2">
                        <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                        <p class="help-block">Fecha Orden</p>
                    </div>
                    
                
                </div>
                <div class="col-sm-2">
                    <div class="col-md-10">
                            <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                        </div>
                </div>
            </form>
            <div class="col-md-12" style="margin-top: 10px">
                <legend>
                    <div class="pull-right">Total: {{$cantidad}} examen(es) encontrado(s)
                     </div>
                </legend>
            </div>                
            <div class="col-md-12">
                <input class="form-control input-sm" id="system-search" name="q" placeholder="Buscar examen">
            </div>

            <div class="col-md-12"  id="global">
                <div class="table-responsive">
                <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Nro. Prestacion</th>
                            <th>Nro. Ex. Lab</th>
                            <th>Tipo de Atención</th>
                            <th>Especialidad</th>
                            <th>Hora<br/>Registro</th>
                            <th>Fecha<br/>Orden</th>
                            <th>Tipo Doc.</th>
                            <th>Nro. Doc.</th>
                            <th>Beneficiario</th>
                            <th>Medico</th>
                            <th style="text-align:center">Pdf Examen</th>
                            @if (Auth::user()->id_establecimiento != 76):
                            <th style="text-align:center">Atender ln</th>
                            @else
                            <th style="text-align:center">Atender</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($listados as $r)
                        <tr>
                            <td><?php
                                $n++;
                                echo $n;
                                ?></td>
                            <td>{{$r->id_prestacion}}</td>
                            <td>{{$r->id_lab}}</td>
                            <td>{{$r->descripcion_tipo_atencion}}</td>
                            <td>{{$r->consultorio}}</td>
                            <td>
                                <?php
                                $originalDate3 = $r->created_at;
                                $horaR = date("H:i:s", strtotime($originalDate3));
                                echo $horaR;
                                ?> 
                            </td>
                            <td>
                                <?php
                                $originalDate1 = $r->firma_laboratorio;
                                $fechaE = date("d-m-Y", strtotime($originalDate1));
                                echo $fechaE;
                                ?> 
                            </td>
                            <td>{{$r->tipo_doc_ident}}</td> 
                            <td>{{$r->nro_doc_ident}}</td> 
                            <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>   
                            <!--td></td--> 
                            <td>{{$r->paterno_medico}} {{$r->materno_medico}}, {{$r->nombre_medico}}</td>
                            <td class="text-center">
                              <?php 
                              if ($r->ruta_laboratorio == "") {
                              ?>
                                <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                              <?php 
                              } else {
                              ?>
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Examen</b>">
                                  <a href="/{{$r->flag}}/{{$r->ruta_laboratorio}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                </div>
                              <?php 
                              }
                              ?>
                            </td>
                            <?php if ($r->id_establecimiento == 76): ?>
                            <!-- LABORATORIO LNS -->
                            <td class="text-center">
                                <?php 
                                    if ($r->ruta_laboratorio == ""):
                                ?>
                                        <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-check-square"></i></button>
                                <?php 
                                    else:
                                        if($r->fecha_registro_toma_muestra == null): 
                                ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar Toma<br>de muestra</b>">
                                                <a href="/formulario_atencion_laboratorio_lns/{{$r->id_lab}}" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i></a>
                                            </div>
                                <?php 
                                        else: 
                                ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar Toma<br>de muestra</b>">
                                                <button onClick="mofificarPruebasEnviadasLNS({{$r->id_lab}})" <?php echo ($r->diff_horas_desde_toma_muestra != null && $r->diff_horas_desde_toma_muestra >= 24) ? "disabled" : ""; ?> class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></button>
                                            </div>
                                <?php 
                                        endif;
                                    endif;
                                
                                ?>
                            </td>
                            <!-- FIN LABORATORIO LNS -->
                        <?php endif;
                                if ($r->id_establecimiento != 76):
                        ?>

                            <td class="text-center">
                                
                                <?php 
                                
                                if ($r->ruta_laboratorio == "") {
                                ?>
                                    <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-edit"></i></button>
                                <?php 
                                } else {
                                ?>      <table>
                                        <tr><td>
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b> Bioquimica</b>">
                                        <?php 
                                            if($r->tipo_bioquimica=='SI'):
                                                if($r->rpta_bioquimica=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/1" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/1" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Hematologia</b>">
                                        <?php
                                            if($r->tipo_hematologia=='SI'):
                                                if($r->rpta_hematologia=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/2" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/2" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Inmunologia</b>">
                                        <?php
                                            if($r->tipo_inmunologia=='SI'):
                                                if($r->rpta_inmunologia=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/3" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a  href="/formulario_atencion_laboratorio/{{$r->id_lab}}/3" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Microbiologia</b>">
                                        <?php
                                            if($r->tipo_microbiologia=='SI'):
                                                if($r->rpta_microbiologia=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/4" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a  href="/formulario_atencion_laboratorio/{{$r->id_lab}}/4" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anatomia Patologica</b>">
                                        <?php
                                            if($r->tipo_anatomia=='SI'):
                                                if($r->rpta_anatomia=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/5" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/5" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Citopatologia</b>">
                                        <?php
                                            if($r->tipo_citopatologia=='SI'):
                                                if($r->rpta_citopatologia=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/6" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/6" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Citogenetico</b>">
                                        <?php
                                            if($r->tipo_citogenetico=='SI'):
                                                if($r->rpta_citogenetico=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/7" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a  href="/formulario_atencion_laboratorio/{{$r->id_lab}}/7" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Quirurgica</b>">
                                        <?php
                                            if($r->tipo_quirurgica=='SI'):
                                                if($r->rpta_quirurgica=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/8" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a  href="/formulario_atencion_laboratorio/{{$r->id_lab}}/8" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Otro</b>">
                                        <?php
                                            if($r->tipo_otro=='SI'):
                                                if($r->rpta_otro=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/9" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a  href="/formulario_atencion_laboratorio/{{$r->id_lab}}/9" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Reproductivo</b>">
                                        <?php
                                            if($r->tipo_reproductiva=='SI'):
                                                if($r->rpta_reproductiva=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/10" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/10" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Covid-19</b>">
                                        <?php
                                            if($r->tipo_covid=='SI'):
                                                if($r->rpta_covid=='SI'):
                                        ?>
                                                    <a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/11" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <?php 
                                                else: ?>
                                                    <a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/11" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php   endif;
                                            endif;
                                        ?>
                                        </div>
                                        </td>
                                        </tr>
                                        </table>
                                        <?php
                                    }
                                    
                                ?>
                            </td>
                            <?php 
                                endif;
                            ?>
                            
                        </tr>
                        @endforeach                      
                    </tbody>
                </table>   
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalDetRV" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE RECETA VALE NRO. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-receta"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
