@extends('frontend.layouts.master')
@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script>
    
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) || (keynum == 45))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }

    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8 )
        }

    function soloNumeros2(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8 || key == 45 )
        }
    
    
        /**/
        
        
/***********************************************************/
var x=2;

    $cmbtipo = $("#frm_transferencia").find("#tipo");
    $cmbtipo.select2();
    $cmbid_origen = $("#frm_transferencia").find("#id_origen");
    $cmbid_origen.select2();
    $cmbid_lugar = $("#frm_transferencia").find("#id_lugar");
    $cmbid_lugar.select2();
    $cmbmotivo = $("#frm_transferencia").find("#motivo");
    $cmbmotivo.select2();
    //--------------------------------------------------------------------------
    $(document).ready(function () {
        $cmbtipo.change(function () {
            $this = $(this);
            cmbtipo = $cmbtipo.val();           
            option = {
                url: '/cargardestino/' + cmbtipo + '/'+ {{ Auth::user()->id_establecimiento }},
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            
            $.ajax(option).done(function (data) {             
                    $('#id_lugar').prop('disabled', false); 
                    cargarComboDestino($cmbid_lugar, data);                    
                    $cmbid_lugar.val(null).trigger("change"); 
            });
        });
              
    });

    function cargarComboDestino($select, data) {
        $select.html('');
        $(data).each(function (ii, oo) {
            $select.append('<option value="' + oo.id + '">' + oo.nombre + '</option>')
        });
    }    

    function cargarComboResponsable($select, data) {
        $select.html('');
        $(data).each(function (ii, oo) {
            $select.append('<option value="' + oo.name + ' '+ oo.paterno_usuario+ ' '+oo.materno_usuario+ '">' + oo.name + ' '+ oo.paterno_usuario+ ' '+oo.materno_usuario+'</option>')
        });
    }
/********************************************************/


</script>
<style>
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">REGISTRAR NOTA DE ENTRADA</h3>
                    <a href="/all_notas_entradas/{{ Auth::user()->id_farmacia }}" class="btn btn-info pull-right">
                        <i class="fa fa-list"></i> Listado de Nota Entrada 
                    </a>
                </div>
                <div class="panel-body">
                    <form id="frm_transferencia" method="POST" action="{{ route('registrar_documento_nota_entrada')}}">   <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!------- Fila 01--------------------------------------->
                        <div class="row">                            
                                <input name="nro_nota_entrada" value="<?php echo $nro_nota_entrada?>" class="form-control" type="hidden">
                                <input name="id_farmacia" value="{{ $id_farmacia }}" class="form-control" type="hidden"> 
                                <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                                <input name="fecha_hace_treinta_dias" id="fecha_hace_treinta_dias" value="{{ $fecha_hace_treinta_dias }}" class="form-control" type="hidden"> 
                                <input type="hidden" name="ano_actual" value="<?php echo date("Y") ?>" id="ano_actual" class="form-control">
                            
                            <div class="col-md-2 etiqueta">
                                    <label>FEC. REGISTRO (R)</label>
                                    <input type="text" class="form-control" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_registro" id="fecha_registro"  readonly=""/>
                            </div>
                            <div class="col-md-5 etiqueta">
                                <label>Registrador </label>
                                <input readonly="" name="responsable_origen" value="{{ Auth::user()->paterno }} {{ Auth::user()->materno }}, {{ Auth::user()->name }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>
                            <div class="col-md-2 etiqueta">
                                <label>Fecha Origen</label>
                                <input type="text" tabindex="3" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_entrada" id="fecha_entrada"   data-date-end-date="0d" autocomplete="off"/>
                            </div>
                            <div class="col-md-3 etiqueta">
                                <label>Motivo</label>
                                <select class="form-control" required="" tabindex="2" name="motivo" id="motivo">
                                    <option value="" disabled selected style="color: #888888">- Seleccione -</option>
                                    @foreach($motivo as $con)
                                    <option value="{{$con->id}}">{{$con->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            
                        </div>
                        <!------- Fila 02--------------------------------------->
                        <div class="row">
                            <div class="col-md-2 etiqueta">
                                <label>Nro Documento o Pecosa</label> 
                                <input  onKeyPress="return soloNumeros2(event)" id="nota_entrada" name="nota_entrada" required="" tabindex="4" class="form-control" type="text" style="text-transform:uppercase;">
                            </div>
                            <div class="col-md-4 etiqueta">
                                <label>Tipo</label>
                                <select name="tipo" id="tipo" class="form-control" required="">
                                    <option value="0" selected="">Seleccionar</option>
                                    <option value="4">DEPENDENCIAS EXTERNAS</option>
                                    <option value="5">EESS PNP</option>
                                    <option value="2">SERVICIOS</option> 
                                </select>
                            </div>
                            <div class="col-md-6 etiqueta">
                                <label>Lugar Origen</label>
                                <select id="id_lugar" name="id_lugar" class="form-control"  required="">
                                    <option value="" disabled selected style="color: #888888">Seleccionar uno</option>      
                                </select>
                            </div>
                        </div>
                        <!------- Fila 03--------------------------------------->
                        <div class="row">
                            <div class="col-md-6 etiqueta">
                                <label id="responsable_label">Responsable (Lugar Origen)</label>
                                    <input name="responsable_destino"  required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>
                            <div class="col-md-6 etiqueta">
                                <label>Observacion</label>
                                <textarea id="referencia" name="referencia" class="form-control input-md"></textarea>
                            </div>
                        </div>
                        <!------- Fila 04--------------------------------------->
                        <div class="col-md-4 etiqueta pull-right">
                            <input tabindex="13"     class="btn btn-success form-control" value="Registrar y continuar" type="submit" id="btnReg" onclick="validateFrm()">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection