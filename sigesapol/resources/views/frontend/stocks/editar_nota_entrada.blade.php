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

</script>
<style>
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
<?php 
    if($pecosa->estado<=2):
        $disabled = "";
        $title = "EDITAR";
        $msg = " Modificar Nota de Entrada de Productos Farmacéuticos ";
    else:
        $disabled = "disabled='disabled'";
        $title = "VER";
        $msg = "Ver Nota de Enrada Culminada de Productos Farmacéuticos";
    endif;
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">EDITAR / MODIFICAR NOTA DE ENTRADA</h3>
                    <a href="/all_notas_entradas/{{ Auth::user()->id_farmacia }}" class="btn btn-info pull-right">
                        <i class="fa fa-list"></i> Listado de Nota Entrada
                    </a>
                </div>
                <div class="panel-body">
                    <form id="frm_transferencia" method="POST" action="{{ route('editar_documento_nota_entrada')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!------- Fila 01--------------------------------------->
                        <div class="row">
                            
                                <input eadonly rname="nro_nota_entrada" value="<?php echo $pecosa->nota_entrada?>" class="form-control" type="hidden">
                                <input name="id_farmacia" value="{{ $id_farmacia }}" class="form-control" type="hidden"> 
                                <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                                <input name="id" value="{{ $pecosa->id }}" id="id" class="form-control" type="hidden"> 
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
                                <input name="fecha_entrada"  tabindex="3" value="<?php echo date("d-m-Y", strtotime($pecosa->fecha_entrada)); ?>" id="fecha_entrada" class="form-control datepicker" required="" <?php echo $disabled ?>>
                            </div>
                            <div class="col-md-3 etiqueta">
                                <label>Motivo</label>
                                <select class="form-control" required="" tabindex="2"  name="motivo" id="motivo">
                                    <?php $idConcepto = $pecosa->motivo;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione -</option>
                                    @foreach($motivo as $con)
                                    <?php $idCo = $con->id;?>
                                        <option value="{{$con->id}}" <?php if($idConcepto == $idCo) {echo " selected";}?>>{{$con->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        <!------- Fila 02--------------------------------------->
                        <div class="row">
                            <div class="col-md-2 etiqueta">
                                <label>Nro Documento o Pecosa</label> 
                                <input  
                                tabindex="4" onKeyPress="return soloNumeros2(event)" id="nota_entrada" name="nota_entrada" required="" value="<?php echo $pecosa->nota_entrada; ?>" class="form-control" type="text" style="text-transform:uppercase;">
                            </div>
                            <div class="col-md-4 etiqueta">
                                <label>Tipo</label>
                                <select name="tipo" id="tipo" class="form-control" required="" <?php echo $disabled ?>>
                                    <option value="" selected="">Seleccionar</option>
                                    <option value="4" <?php if ($pecosa->tipo == 4) echo "selected='selected'" ?>>Transferencia NO PNP</option>
                                    <option value="5" <?php if ($pecosa->tipo == 5) echo "selected='selected'" ?>>Transferencia Externa EESS PNP</option>
                                    <option value="2" <?php if ($pecosa->tipo == 2) echo "selected='selected'" ?>>Transferencia Interna Servicios</option> 
                                </select>
                            </div>
                            <div class="col-md-6 etiqueta">
                                <label>Lugar Origen</label>
                                <select name="id_lugar" autofocus  tabindex="1" id="id_lugar" class="form-control" required="">
                                    <?php $idOrigen = $pecosa->lugar_origen;?>
                                    <option value="" disabled selected style="color: #888888">Seleccionar uno</option>
                                    @foreach($destinos as $dep)
                                    <?php $idO = $dep->id;?>
                                        <option value="{{$dep->id}}" <?php if($idOrigen == $idO) {echo " selected";}?>>{{$dep->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!------- Fila 03--------------------------------------->
                        <div class="row">
                            <div class="col-md-6 etiqueta">
                                <label id="responsable_label">Responsable (Lugar Origen)</label>
                                    <input name="responsable_destino"  required="" class="form-control" type="text" value="<?php echo $pecosa->responsable; ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>
                            <div class="col-md-6 etiqueta">
                                <label id="referencia_label">Observacion</label>
                                <textarea style="text-transform:uppercase;" id="referencia" name="referencia" class="form-control input-md"><?php echo $pecosa->referencia; ?>
                                </textarea>
                            </div>
                        </div>
                        <!---------------------------------------------------------------------------------------->
                        
                        <!-------------------------------------------------------------------------------------------->                        
                        <div class="clearfix" style="margin-bottom:15px"></div>
                        <!--div class="col-md-6" style="margin-top:10px">  
                            <label>Observaci&oacute;n</label>
                            <textarea id="observacion" name="observacion" class="form-control input-md" ></textarea>
                        </div-->
                        <a href="/print_nota_entrada/<?php echo $pecosa->id ?>" class="btn btn-danger pull-right" target="_blank">
                            Imprimir<i class="fa fa-file-pdf-o"></i>
                        </a>

                        <div class="col-md-3 pull-right">
                            <input tabindex="13"     class="btn btn-success form-control" value="Editar" type="submit" id="btnReg" onclick="validateFrm()">
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection