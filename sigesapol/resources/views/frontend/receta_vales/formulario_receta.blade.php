@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $consul = $("#frmReceta").find("#id_consultorio");
    $consul.select2();
    $tur = $("#frmReceta").find("#turno");
    $tur.select2();
    $grado = $("#frmReceta").find("#grado");
    $grado.select2();
    $tipo = $("#frmReceta").find("#tipo_beneficiario");
    $tipo.select2();
    $cies = $('#frmReceta').find("#id_diagnostico")
    $cies.select2();
    $medicos = $('#frmReceta').find("#id_medico")
    $medicos.select2();
    $tipo_receta = $('#frmReceta').find("#tipo_receta")
    $tipo_receta.select2();
    //-------------------------------------------------------------
    $("#btnReg").on("click", function () {
        if (confirm("¿Desea Grabar?") == true) {
            //$("#frmReceta").submit();
            return true;
        }else{
            return false;
        }
    });
    //-------------------AGREGAR PRODUCTOS--------------------------------------
    $(document).ready(function () {
        var t = $('#example').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            bFilter: false,
            bPaginate: false,
            bSort: false,
            "createdRow": function (row, data, index) {
                cmbMedicamento = $("#cmbMedicamento").clone();
                $(cmbMedicamento).attr('name', 'id_producto[]');
                $(row).find('#dvMedicamento').append(cmbMedicamento);
                $(cmbMedicamento).select2({max_selected_options: 4});
                //alert(.html());
            },
            "language": {
                "emptyTable": "Agregar Productos Farmacéuticos o Dispositivos Médicos"
            }
        });
        var counter = 1;

        $('#example tbody').on('click', 'button.deleteFila', function () {
            r = confirm("¿Desea eliminar el Producto Farmacéutico o Dispositivo Médico?");
            if (r) {
                t.row($(this).closest('tr')).remove().draw();
            }

        });
        $('#addRow').on('click', function () {
            t.row.add([
                '<div id="dvMedicamento"></div>',
                '<input type="number" name="nro_med_solictados[]" required id="nro_med_solictados[]" class="form-control nro_solicitado input-sm"/>',
                '<button type="button"  class="btn btn-danger deleteFila btn-xs"><i class="fa fa-times"></i> Eliminar</button>'
            ]).draw(false);
        });

        // Automatically add a first row of data
        //$('#addRow').click();
    });
    //--------------------------------------------------------------------------
    $(".chosen-select").chosen({max_selected_options: 4});
    //--------------------------------------------------------------------------
    (function ($) {

        $(function () {

            var addFormGroup = function (event) {
                event.preventDefault();
                $(".chosen-select").chosen("destroy");
                var $formGroup = $(this).closest('.form-group');
                var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
                var $formGroupClone = $formGroup.clone();
                $(this)
                        .toggleClass('btn-success btn-add btn-danger btn-remove')
                        .html('–');
                $formGroupClone.find('select').val('');
                $formGroupClone.find('.nro_solicitado').val('');
                //$formGroupClone.find('.concept').text('Phone');
                $formGroupClone.insertAfter($formGroup);

                var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
                if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
                    $lastFormGroupLast.find('.btn-add').attr('disabled', true);

                }
                $(".chosen-select").chosen();

                //$(".chosen-select").trigger("chosen:updated");
            };
            var removeFormGroup = function (event) {
                event.preventDefault();
                var $formGroup = $(this).closest('.form-group');
                var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
                var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
                if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
                    $lastFormGroupLast.find('.btn-add').attr('disabled', false);
                }
                $formGroup.remove();
            };
            var selectFormGroup = function (event) {
                event.preventDefault();
                var $selectGroup = $(this).closest('.input-group-select');
                var param = $(this).attr("href").replace("#", "");
                var concept = $(this).text();
                $selectGroup.find('.concept').text(concept);
                $selectGroup.find('.input-group-select-val').val(param);
            }
            var countFormGroup = function ($form) {
                return $form.find('.form-group').length;
            };
            $(document).on('click', '.btn-add', addFormGroup);
            $(document).on('click', '.btn-remove', removeFormGroup);
        });
    })(jQuery);
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <div class="panel-group" id="accordion">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-th">
                                </span>Atención de Recetas</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-plus text-primary"></span>
                                        <a href="/nueva_receta/{{ Auth::user()->id }}"> Nueva Receta</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-search text-primary"></span>
                                        <a href="/buscar_receta/{{ Auth::user()->id }}"> Buscar Receta</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list text-primary"></span>
                                        <a href="/recetas_atendidas/{{ Auth::user()->id }}"> Receta atendidas</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-arrow-left text-primary"></span>
                                        <a href="/stocksbyFamarcia/{{ Auth::user()->id }}"> Ir al Stock</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 col-md-9">
            <div class="row">
                <div class="panel panel-primary">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">MODULO DE GESTION DE RECETAS</h3>                
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('registrar_receta')}}" id="frmReceta">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <img src="../../images/logo.png" width="180" height="auto">
                                </div>
                                <div class="col-md-7" style="text-align: center">
                                    <label>{{$establecimiento->nombre}}</label><br>
                                    <label style="font-size: 24px">RECETA</label><br>
                                </div>
                                
                                <div class="col-md-3">
                                    <label>N° RECETA</label>
                                    <input type="text" class="form-control" value="" name="nro_receta" autofocus="" tabindex="1" required="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                    <input type="hidden" value="{{$id_farmacia[0]}}" name="id_farmacia">
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                    <input type="hidden" value="dni" name="tipo">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label>TIPO RECETA</label>
                                    <select class="form-control" id="tipo_receta" name="tipo_receta" required="">
                                        <!--<option value="">-Seleccionar-</option>-->
                                        <option value="AMBULATORIO">AMBULATORIO</option>
                                        <option value="EMERGENCIA">EMERGENCIA</option>
                                        <option value="EMERGENCIA">HOSPITALIZACION</option>                                        
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>CONSULTORIO</label>
                                    <select class="form-control" id="id_consultorio" name="id_consultorio" required="" tabindex="2">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($consultorio as $c)
                                        <option value="{{$c->id}}">{{$c->nombre}}</option>
                                        @endforeach                                        
                                    </select>
                                </div>
                                <!--<div class="col-md-3">
                                    <label>NRO. TPS</label>
                                    <input type="text" class="form-control" value="" name="tps_beneficiario" tabindex="3">
                                </div>-->
                                <div class="col-md-3">
                                    <label>TURNO</label>
                                    <select class="form-control" name="turno" id="turno" required="">
                                        <!--<option value="">-Seleccionar-</option>-->
                                        <option value="M">MAÑANA</option>
                                        <option value="T">TARDE</option>
                                        <option value="N">NOCHE</option>
                                    </select>
                                </div>
                                <!--<div class="col-md-2">
                                    <label>NRO. CIP</label>
                                    <input type="text" class="form-control" value="" name="cip_beneficiario" tabindex="4">
                                </div>-->
                            </div>
                            <div class="form-group">
                                @if($beneficiario[0]->parentesco!="TITULAR")
                                <div class="col-md-2">
                                    <label>DNI (TITULAR)</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->nrodoctitular}}" name="dni_titular" tabindex="5" required="" maxlength="8">
                                </div>
                                <div class="col-md-10">
                                    <label>APELLIDOS Y NOMBRES (TITULAR)</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->apepattitular}} {{$beneficiario[0]->apemattitular}}, {{$beneficiario[0]->nomtitular}}" name="paterno_beneficiario" tabindex="7">
                                </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>TIPO DE BENEFICARIO</label>
                                    <input value="{{$beneficiario[0]->parentesco}}" type="text" name="tipo_beneficiario" id="" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label>DNI</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->nrodocafiliado}}" name="dni_beneficiario" tabindex="5" required="" maxlength="8">
                                </div>
                                @if($beneficiario[0]->parentesco=="TITULAR")
                                   
                                <div class="col-md-5">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}, {{$beneficiario[0]->nomafiliado}}" name="paterno_beneficiario" tabindex="7">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nomafiliado}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apepatafiliado}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apematafiliado}}" name="materno_beneficiario">
                                </div> 
                                <div class="col-md-3">
                                    <label>GRADO</label>
                                   
                                    <select class="form-control" id="grado" name="grado" tabindex="8">                                        
                                        <option value="">--Seleccionar--</option>
                                        <option value="NINGUNO">NINGUNO</option>
                                        <option value="TENIENTE GENERAL">TENIENTE GENERAL</option>
                                        <option value="GENERAL">GENERAL</option>
                                        <option value="CORONEL">CORONEL</option>
                                        <option value="COMANDANTE">COMANDANTE</option>
                                        <option value="MAYOR">MAYOR</option>
                                        <option value="CAPITAN">CAPITAN</option>
                                        <option value="TENIENTE">TENIENTE</option>
                                        <option value="ALFEREZ">ALFEREZ</option>
                                        <option value="SUB OFICIAL SUPERIOR">SUB OFICIAL SUPERIOR</option>
                                        <option value="SUB OFICIAL BRIGADIER">SUB OFICIAL BRIGADIER</option>
                                        <option value="SUB OFICIAL TECNICO DE 1ERA">SUB OFICIAL TÉCNICO DE 1ERA</option>
                                        <option value="SUB OFICIAL TECNICO DE 2DA">SUB OFICIAL TÉCNICO DE 2DA</option>                                        
                                        <option value="SUB OFICIAL TECNICO DE 3ERA">SUB OFICIAL TÉCNICO DE 3ERA</option>
                                        <option value="SUB OFICIAL DE 1ERA">SUB OFICIAL DE 1ERA</option>
                                        <option value="SUB OFICIAL DE 2DA">SUB OFICIAL DE 2DA</option>
                                        <option value="SUB OFICIAL DE 3ERA">SUB OFICIAL DE 3ERA</option>
                                    </select>                                     
                                </div> 
                                @else
                                <div class="col-md-8">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}, {{$beneficiario[0]->nomafiliado}}" name="paterno_beneficiario" tabindex="7">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nomafiliado}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apepatafiliado}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apematafiliado}}" name="materno_beneficiario">
                                </div>
                                @endif                                 
                            </div>  
                             
                            <div class="form-group">
                                <div class="col-md-12" style="text-align: left; font-size: 23px">
                                    PRESCRIPCION 
                                </div>                                
                            </div>                            
                            <!--<div class="form-group">
                                <div class="col-md-8">
                                    <label>PRODUCTO</label>
                                </div>
                                <div class="col-md-3">
                                    <label>CANTIDAD PRESCRITA</label>
                                </div>                                
                            </div>-->
                            <div class="col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 20px">
                                <button type="button" id="addRow" class="btn btn-primary btn-xs">
                                    <i class="fa fa-plus"></i> Agregar producto(s)
                                </button>
                                <div style="display: none">
                                    <select class="form-control" id="cmbMedicamento" tabindex="9" name="tmp_id_producto[]" style="width: 500px" required="">
                                        <option value="">Seleccionar Producto</option>                                                                                                                                                            
                                        @foreach($producto as $p)                                                                                                
                                        <option value="{{$p->id}}">{{$p->codigo}} - {{$p->nombre}}, {{$p->abreviatura}}</option>         
                                        @endforeach
                                    </select> 
                                </div>
                                <table id="example" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Producto farmacéutico o dispositivo médico</th>
                                            <th>Cantidad Prescrita</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <div class="col-md-5">
                                    <label>FEC. EXPEDICIÓN (R)</label>
                                    <input type="text" class="form-control datepicker" value="" name="fecha_expedicion" tabindex="12" required="">
                                </div>
                                <div class="col-md-7">
                                    <label>DIAGNOSTICO</label>                                    
                                    <select class="form-control" name="id_diagnostico" id="id_diagnostico" required="" tabindex="14">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($diagnostico as $d)
                                        <option value="{{$d->id}}">{{$d->codigo}} - {{$d->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8" style="margin-top: 20px">
                                    <label>MEDICO (DNI | CMP | Nombre y Apellidos)</label>
                                    <select class="form-control" name="id_medico" required="" id="id_medico">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($medico as $m)
                                        <option value="{{$m->id}}">DNI: {{$m->dni}} - CMP: {{$m->colegiatura}} - {{$m->nombre}} {{$m->paterno}} {{$m->materno}}</option>
                                        @endforeach
                                    </select>
                                </div>   
                                <div class="col-md-2" style="text-align: center; margin-top: 20px">
                                    <label>HISTORIA CLINICA</label>
                                    <input type="text" class="form-control" value="" name="nro_historia" tabindex="15" required="">
                                </div>
                                <div class="col-md-2" style="text-align: center; margin-top: 20px">
                                    <label>N° RUA</label>
                                    <input type="text" value="" name="nro_rua" class="form-control" tabindex="16" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <a href="/nueva_receta/{{ Auth::user()->id }}" class="form-control btn btn-danger" tabindex="18">CANCELAR</a>
                                </div>
                                <div class="col-md-6">
                                    <input id="btnReg" class="form-control btn btn-success" type="submit" value="REGISTRAR RECETA Y CONTINUAR" tabindex="17">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection