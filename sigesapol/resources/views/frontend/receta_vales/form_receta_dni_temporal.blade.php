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
    
    function sumarCantidadLotes(){
        trs=$('#tabla_lote tbody').children('tr');
        total=0;
        lote_id=[];
        lote_cantidad=[];
        $(trs).each(function(ii,oo){
            valor=$(oo).find('#cant').val();
            id=$(oo).find('#id_lote').val();
            if(valor!=0 && valor!=''){
                total+=parseFloat(valor);
                lote_id.push(id);
                lote_cantidad.push(valor);
            }            
        });
        //alert(total);                
        fila_medicamentos=$('#example tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        fila_medicamento=fila_medicamentos[rowIndex];
        $(fila_medicamento).find('#nro_med_entregados').val(total);
        cadena_lote_id=lote_id.join("||");
        cadena_lote_cantidad=lote_cantidad.join("||");
        $(fila_medicamento).find('#lotes_id').val(cadena_lote_id);
        $(fila_medicamento).find('#lotes_cantidad').val(cadena_lote_cantidad);
        //alert(cadena_lote_id);
        //alert(cadena_lote_cantidad);
        $('#squarespaceModal').modal('hide');
        
    }
    
    
    
    function verlotes(id_farmacia, btn){
        objTr = $(btn).closest("tr");        
        rowIndex = $('#example tbody').children('tr').index(objTr);
        $('#hidden_rowIndex').val(rowIndex);
        codigo=objTr.find("#cmbMedicamento").val();
        //alert(codigo);
         $.ajax({
                url: '/obtener_lotes_por_idprod_idfarma/' + codigo+ '/' + {{Auth::user()->id_farmacia}}, 
                success: function(result){
                    $("#tabla_lote tbody").html("");
                    $(result).each(function (ii, oo) {                       
                        $("#tabla_lote tbody").append(
                            '<tr style="height:30px"> \n\
                                <td style="width:15%"><input type="hidden" value="'+ oo.id +'" id="id_lote">' + oo.codigo_producto + '</td>\n\
                                <td style="width:33%">' + oo.nombre_producto + '</td>\n\
                                <td style="width:13%">' + oo.lote + '</td>\n\
                                <td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
                                <td style="width:15%">' + oo.cantidad + '</td>\n\
                                <td style="width:12%"><input type="text" id="cant" name="cant" value="" style="width:90px"></td>\n\
                            </tr>'
                        );
                    });
                }
            });
        $('#squarespaceModal').modal();
    }
    
    //-------------------------------------------------------------
    $("#btnReg").on("click", function () {
        if (confirm("¿Desea Grabar?") == true) {
            //$("#frmReceta").submit();
            return true;
        } else {
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
                $(cmbMedicamento).on("change", function (e) {
                    cmb = $(this);
                    id_producto = $(cmbMedicamento).val();
                    id_user={{Auth::user()->id}};
                    //alert(id_user);
                    option = {
                        url: 'cargarstock_farmacia/' + id_producto+ '/' + id_user,
                        type: 'GET',
                        dataType: 'json',
                        data: {}
                    };
                    $.ajax(option).done(function (data) {
                        //alert(data);                        
                        $(cmb).closest("tr").find("#nro_stocks").val(data);                        
                        //alert($(cmb).closest("tr").html());
                    });
                    //alert($id_producto);
                });
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
                '<input type="number" name="nro_stocks[]" required id="nro_stocks" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" disabled=""/>               <input type="hidden" name="lotes_id[]" id="lotes_id" value=""/>                <input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/>',                
                '<input type="number" name="nro_med_solictados[]" required id="nro_med_solictados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px"/>',
                '<input type="number" name="nro_med_entregados[]" required id="nro_med_entregados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px"/>',
                //'<input type="number" name="nro_med_entregados[]" required id="nro_med_entregados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>',
                //'<button data-toggle="modal" class="btn btn-primary btn-xs" type="button" onclick="verlotes({{Auth::user()->id_farmacia}},this)">Add</button>',                                  
                '<button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>'
            ]).draw(false);
        });

        // Automatically add a first row of data
        //$('#addRow').click();
        $("#frmReceta").submit(function (e) {
            //rows = $("#frmReceta").children(".row");            
            rows = $("#example tbody").children('tr');
            //e.preventDefault();
            $.each(rows, function (ii, oo) {
                medicina = $(oo).find("#cmbMedicamento").select2("data");
                //alert(medicina.text);
                nuevaCantidad = parseFloat($(oo).find("#nro_med_entregados").val());
                cantidadPrescrita = parseFloat($(oo).find("#nro_med_solictados").val());
                stockActual = parseFloat($(oo).find("#nro_stocks").val());
                //alert(stockActual);
                if (stockActual == 0) {                    
                    if (nuevaCantidad != 0) {                           
                        alert("La medicina: " +medicina[0].text+ ", tiene que ser cantidad cero");
                        e.preventDefault();
                        return false;
                    }
                } else {
                    if (nuevaCantidad > stockActual) {
                        alert("La medicina: " + medicina[0].text + ", la cantidad no tiene que ser mayor que el stock");                        
                        e.preventDefault();
                        return false;
                    } else if (nuevaCantidad > cantidadPrescrita) {
                        alert("La medicina: " + medicina[0].text + ", la cantidad no tiene que ser mayor que la prescrita");                        
                        e.preventDefault();
                        return false;
                    }
                }
            });
        });
    });
    //--------------------------------------------------------------------------
    $(".chosen-select").chosen({max_selected_options: 4});
    //--------------------------------------------------------------------------
</script>
<style>
    .modal-dialog{
        width: 850px !important;
    }    
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">        
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">MODULO DE GESTION DE RECETAS</h3>      
                        <div class="pull-right">
                            <a class="btn btn-default" href="/nueva_receta/{{ Auth::user()->id }}">Nueva Receta</a>
                            <a class="btn btn-default" href="/buscar_receta/{{ Auth::user()->id }}">Buscar Receta</a>
                            <a class="btn btn-default" href="/recetas_atendidas/{{ Auth::user()->id }}">Recetas Atendidas</a>  
                            <a class="btn btn-default" href="/stocksbyFamarcia/{{ Auth::user()->id }}">Ir Stock</a>  
                        </div>
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('registrar_receta_rua_ubapol')}}" id="frmReceta">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <img src="../../images/logo.png" width="180" height="auto">
                                </div>
                                <div class="col-md-6" style="text-align: center">
                                    <label>{{$establecimiento->nombre}}</label><br>
                                    <label style="font-size: 24px">RECETA</label><br>
                                </div>

                                <div class="col-md-4">
                                    <label>N° RECETA: </label>
                                    <!--<input type="text" name="inicial" style="width: 20px; text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">-->
                                    P-
                                    <input type="text" class="" name="nro_receta" autofocus="" tabindex="1" required="" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"> -
                                    <input type="text" class="" value="10" name="digitos_adicionales"  tabindex="2" required="" style="width: 70px; text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">                                                                                                     
                                    <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia">
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                    <input type="hidden" value="dni" name="tipo">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label>TIPO RECETA</label>
                                    <select class="form-control" id="tipo_receta" name="tipo_receta" required="" tabindex="3">
                                        <!--<option value="">-Seleccionar-</option>-->
                                        <option value="AMBULATORIO" selected>AMBULATORIO</option>
                                        <option value="EMERGENCIA">EMERGENCIA</option>
                                        <option value="EMERGENCIA">HOSPITALIZACION</option>                                        
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>CONSULTORIO</label>
                                    <select class="form-control" id="id_consultorio" name="id_consultorio" required="" tabindex="4">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($consultorio as $c)
                                        <option value="{{$c->id}}">{{$c->nombre}}</option>
                                        @endforeach                                        
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>TURNO</label>
                                    <select class="form-control" name="turno" id="turno" required="" tabindex="5">
                                        <!--<option value="">-Seleccionar-</option>-->
                                        <option value="M">MAÑANA</option>
                                        <option value="T">TARDE</option>
                                        <option value="N">NOCHE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                @if($beneficiario[0]->parentesco!="TITULAR")
                                <div class="col-md-2">
                                    <label>DNI (TITULAR)</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->nrodoctitular}}" name="dni_titular" tabindex="6" required="" maxlength="8">
                                </div>
                                <div class="col-md-7">
                                    <label>APELLIDOS Y NOMBRES (TITULAR)</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->apepattitular}} {{$beneficiario[0]->apemattitular}}, {{$beneficiario[0]->nomtitular}}" name="paterno_beneficiario" tabindex="7">
                                </div>
                                 <div class="col-md-3">
                                    <label>GRADO</label>                                   
                                    <select class="form-control" id="grado" name="grado" tabindex="8">
                                        <option value="">--Seleccionar--</option>
                                        <option value="NINGUNO">NINGUNO</option>
                                        <option value="ALUMNO">ALUMNO E.T.S.</option>
                                        <option value="CADETE">CADETE E.O.</option>
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
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>TIPO DE BENEFICARIO</label>
                                    <input value="{{$beneficiario[0]->parentesco}}" type="text" name="tipo_beneficiario" id="" class="form-control" tabindex="9">
                                </div>
                                <div class="col-md-2">
                                    <label>DNI</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->nrodocafiliado}}" name="dni_beneficiario" tabindex="10" required="" maxlength="8">
                                </div>
                                @if($beneficiario[0]->parentesco=="TITULAR")
                                <div class="col-md-5">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}, {{$beneficiario[0]->nomafiliado}}" name="paterno_beneficiario" tabindex="9">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nomafiliado}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apepatafiliado}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apematafiliado}}" name="materno_beneficiario">
                                </div> 
                                <div class="col-md-3">
                                    <label>GRADO</label>
                                    <select class="form-control" id="grado" name="grado" tabindex="10">                                        
                                        <option value="">--Seleccionar--</option>
                                        <option value="NINGUNO">NINGUNO</option>
                                        <option value="ALUMNO">ALUMNO E.T.S.</option>
                                        <option value="CADETE">CADETE E.O.</option>
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
                                    <input type="text" class="form-control" value="{{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}, {{$beneficiario[0]->nomafiliado}}" name="paterno_beneficiario" tabindex="11">
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
                            <div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 20px; margin-left: 3px">
                                <button type="button" id="addRow" class="btn btn-primary btn-xs">
                                    <i class="fa fa-plus"></i> Agregar producto(s)
                                </button>
                                <div style="display: none">
                                    <select class="form-control" id="cmbMedicamento" tabindex="12" name="tmp_id_producto[]" style="width: 500px">
                                        <option value="">Seleccionar Producto</option>                                                                                                                                                            
                                        @foreach($producto as $p)                                                                                                
                                        <option value="{{$p->id}}">{{$p->codigo}} - {{$p->nombre}}, {{$p->abreviatura}}</option>         
                                        @endforeach
                                    </select> 
                                </div>
                                <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                <table id="example" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Producto farmacéutico o dispositivo médico</th>
                                            <th>Stock Actual</th>
                                            <th>Cantidad Prescrita</th>
                                            <th>Cantidad Entregada</th>
                                            <!--<th>Add</th>-->
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
                                    <input type="text" class="form-control datepicker" value="" name="fecha_expedicion" tabindex="13" required="">
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
                                    <select class="form-control" name="id_medico" required="" id="id_medico" tabindex="15">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($medico as $m)
                                        <option value="{{$m->id}}">DNI: {{$m->dni}} - CMP: {{$m->colegiatura}} - {{$m->nombre}} {{$m->paterno}} {{$m->materno}}</option>
                                        @endforeach
                                    </select>
                                </div>   
                                <div class="col-md-2" style="text-align: center; margin-top: 20px">
                                    <label>HISTORIA CLINICA</label>
                                    <input type="text" class="form-control" value="" name="nro_historia" tabindex="16">
                                </div>
                                <div class="col-md-2" style="text-align: center; margin-top: 20px">
                                    <label>N° RUA</label>
                                    <input type="text" value="" name="nro_rua" class="form-control" tabindex="17" >
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <a href="/nueva_receta/{{ Auth::user()->id }}" class="form-control btn btn-danger" tabindex="19">CANCELAR</a>
                                </div>
                                <div class="col-md-6">
                                    <input id="btnReg" class="form-control btn btn-success" type="submit" value="REGISTRAR RECETA Y CONTINUAR" tabindex="18">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form method="post" action="{{ route('reasignar_lotes')}}" id="frmlotizar">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header" id="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="lineModalLabel">Dispensar</h4>
                    
                </div>
                <div class="modal-body">
                    <table border="0px" id="tabla_lote">
                        <thead>
                            <tr>
                                <th style="width:15%">Código</th>   
                                <th style="width:33%">Nombre</th> 
                                <th style="width:13%">Lote</th> 
                                <th style="width:12%">Fec. ven.</th> 
                                <th style="width:15%">Cant. Actual.</th> 
                                <th style="width:12%">Cant. Entregar</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>                        
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">                        
                        <div class="btn-group" role="group">
                            <button type="button" onclick="sumarCantidadLotes()" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Agregar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection