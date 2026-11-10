@extends('frontend.layouts.master')
@section('renderjs')
<script>
    
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) || (keynum == 45))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
    
    $cmbtipo = $("#frm_transferencia").find("#tipo");
    $cmbtipo.select2();
    $cmbid_origen = $("#frm_transferencia").find("#id_origen");
    $cmbid_origen.select2();
    $cmbid_lugar = $("#frm_transferencia").find("#id_lugar");
    $cmbid_lugar.select2();
    $cmbid_farmacia = $("#frm_transferencia").find("#id_farmacia");
    //--------------------------------------------------------------------------
    $(document).ready(function () {
        $cmbtipo.change(function () {            
            $this = $(this);
            cmbid_farmacia=$cmbid_farmacia.val();            
            cmbtipo = $cmbtipo.val();
            option = {
                url: '/cargardestino_almacen/' + cmbtipo+'/'+cmbid_farmacia,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                //alert(data);
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
    //-------------------AGREGAR PRODUCTOS--------------------------------------    
    //----------------REGISTRAR LA TRANSFERENCIA---------------------------------

</script>
<style>
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">REGISTRAR</h3>                    
                    <!--<a href="/all_transferencias/{{ Auth::user()->id_farmacia }}" class="btn btn-default pull-right">
                        <i class="fa fa-list"></i> Listado de Transferencias
                    </a>-->
                </div>
                <div class="panel-body">
                    <form id="frm_transferencia" method="POST" action="{{ route('registrar_documento_transferencia')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <legend>
                            Generar Pecosa
                        </legend>
                        <!------- Fila 01------------------------------------------>                        
                        <div class="col-md-4">
                            <label>Tipo</label>
                            <select name="tipo" id="tipo" class="form-control" required="">
                                <option value="" selected="">Seleccionar</option>
                                <option value="3">Farmacia Internas</option>
                                <option value="2">Transferencia Interna</option> 
                                <option value="1">Transferencia Externa</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Nro Pecosa</label>
                            <input onkeypress="return justNumbers(event);" name="nro_acta" value="" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">     
                            <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                            <input id="id_farmacia" name="id_farmacia" value="{{ Auth::user()->id_farmacia }}" class="form-control" type="hidden"> 
                        </div>                        
                        <div class="col-md-4">
                            <label>Fecha</label>
                            <input name="fecha" value="" required="" class="form-control datepicker">
                        </div>                        
                        <!------- Fila 02------------------------------------------>                        
                        <div class="col-md-6 etiqueta">
                            <label>Lugar Origen</label>
                            <select class="form-control" required="" name="id_origen" id="id_origen">
                                <option value="{{$farmacia->id}}">{{$farmacia->nombre}}</option>
                            </select>
                        </div>
                        <div class="col-md-6 etiqueta">
                            <label>Responsable (Lugar Origen)</label>
                            <input name="responsable_origen" value="{{ Auth::user()->paterno }} {{ Auth::user()->materno }}, {{ Auth::user()->name }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                        <!------- Fila 03------------------------------------------>                        
                        
                        <!------- Fila 04------------------------------------------>                        
                        <div class="col-md-6 etiqueta">
                            <label>Lugar Destino</label>
                            <select id="id_lugar" name="id_lugar" class="form-control"  required="">                                                                
                                <option value="" disabled selected style="color: #888888">Seleccionar uno</option>      
                            </select>
                        </div>
                        <div class="col-md-6 etiqueta">
                            <label>Responsable (Lugar Destino)</label>
                            <input name="responsable_destino" value="" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                        <div class="col-md-4 etiqueta pull-right">
                            <input class="btn btn-success form-control" value="Registrar y continuar" type="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection