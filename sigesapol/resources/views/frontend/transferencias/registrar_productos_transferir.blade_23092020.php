<?php
use Carbon\Carbon;

        if(Auth::user()->flagresponsablefarmacia==1){         
            //Calcular Fecha limite para las modificaciones
            $date = Carbon::now();
            $fecha = $date->format('d-m-Y');   
            $fechaIngreso = Carbon::parse($transferencia->created_at);
            $fechaActual = Carbon::parse($fecha);
            $tiempo = $fechaActual->diffInDays($fechaIngreso);
        }
        else{
            $tiempo=4;
        }

?>
@extends('frontend.layouts.master')
@section('renderjs')
<script>

    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
	
	function escapeRegExp(string){
		return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
	}

	function replaceAll(str, term, replacement) {
	  return str.replace(new RegExp(escapeRegExp(term), 'g'), replacement);
	}

    $(document).ready(function () {
        $cmbid_lote = $("#frm_transferencia").find("#id_lote");
        //$cmbid_lote.select2();
        $("#id_productoBus").select2({
            width: 'resolve'
        });
        var id_farmacia = $("#frm_transferencia").find("#id_farmacia").val();
        var id_transferencia = $("#frm_transferencia").find("#id_transferencia").val();
        $cmbid_lote.change(function () {
            $this = $(this);
            var cmbid_lote = $this.val();
            var datos = cmbid_lote.split("#");
            var codigo_producto = datos[0];
            //var lote = datos[1].replace("/", "|");
			var lote = replaceAll(datos[1],"/","|");
            var fecha_vencimiento = datos[2];            
            //var registro_sanitario = datos[3].replace("/", "|").replace("/", "|");
			var registro_sanitario = replaceAll(datos[3],"/","|");
            
            var precio_unitario = datos[4];
            var nrolote = datos[5];            
            //alert("lote" + lote);
            if(nrolote > 1){
                    bootbox.confirm({
                         message: "Advertencia: Existe un lote con fecha de vencimiento anterior, ¿Desea regresar?.",
                         className: 'title-dialog',
                         buttons: {
                             confirm: {
                                 label: 'Si',
                                 className: 'btn-success btn-lg'
                             },
                             cancel: {
                                 label: 'No',
                                 className: 'btn-danger btn-lg'
                             }
                         },
                         callback: function (result) {
                             if (result==true) {
                                $("#frm_transferencia").find("#lote").val('');
                                $("#frm_transferencia").find("#codigo").val('');
                                $("#frm_transferencia").find("#producto").val('');
                                $("#frm_transferencia").find("#registro_sanitario").val('');
                                $("#frm_transferencia").find("#fecha_vencimiento").val('');
                                $("#frm_transferencia").find("#cantidad_actual").val('');
                                $("#frm_transferencia").find("#id_producto").val('');
                                $("#frm_transferencia").find("#id_stock_farmacia").val('');
                                $("#frm_transferencia").find("#cantidad_transferir").val('');
                                $("#frm_transferencia").find("#precio").val('');
                                $("#frm_transferencia").find("#id_presentacion_comercial").val('');
                                $('#id_lote').val('0');
                                $("#id_lote").select2({max_selected_options: 4}).focus();
                             } else {
                                
                                    if (fecha_vencimiento == "")
                                        fecha_vencimiento = "0";
                                    option = {
                                        url: '/obtener_Cantidad_by_CodigoProducto_Lote_FechaVencimiento_RegistroSanitario_Idfarmacia/' + codigo_producto + '/' + lote + '/' + fecha_vencimiento + '/' + registro_sanitario + '/' + id_farmacia + '/' + precio_unitario + '/' + id_transferencia,
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {}
                                    };
                                    $.ajax(option).done(function (data) {
                                        cargarData(data);
                                    });
                             }
                         }
                     });
            } else {
                
                if (fecha_vencimiento == "")
                fecha_vencimiento = "0";
                option = {
                    url: '/obtener_Cantidad_by_CodigoProducto_Lote_FechaVencimiento_RegistroSanitario_Idfarmacia/' + codigo_producto + '/' + lote + '/' + fecha_vencimiento + '/' + registro_sanitario + '/' + id_farmacia + '/' + precio_unitario + '/' + id_transferencia,
                    type: 'GET',
                    dataType: 'json',
                    data: {}
                };
                $.ajax(option).done(function (data) {
                    
                    cargarData(data);
                });
            }
        });

    });

    //-------------------------------------------------------------------------

    function getValidtransPendientesByProducto() {
        var id_farmacia = $('#id_farmacia').val();
        var id_producto = $('#id_productoBus').val();
        var id_transferencia = $('#id_transferencia').val();

        if (id_producto == "") {
            var newOption = "<option value='0'>--Seleccionar-</option>";
            $('#id_lote').html(newOption);
            $("#id_lote").select2({
                width: 'resolve'
            });
            $('#id_lote').attr('disabled', true);
        } else {
            $('#id_lote').attr('disabled', false);
        }

        $("#frm_transferencia").find("#lote").val('');
        $("#frm_transferencia").find("#codigo").val('');
        $("#frm_transferencia").find("#producto").val('');
        $("#frm_transferencia").find("#registro_sanitario").val('');
        $("#frm_transferencia").find("#fecha_vencimiento").val('');
        $("#frm_transferencia").find("#cantidad_actual").val('');
        $("#frm_transferencia").find("#id_producto").val('');
        $("#frm_transferencia").find("#id_stock_farmacia").val('');
        $("#frm_transferencia").find("#cantidad_transferir").val('');
	$("#frm_transferencia").find("#precio").val('');
        $('#id_lote').attr('disabled', false);

        $.ajax({
            url: '/existeTransferenciaPendienteByProducto/' + id_producto + '/' + id_farmacia + '/' + id_transferencia,
            success: function (result) {
                if (result != ""){
                    var datos = result.split("|");
                    var idtransferencia = parseInt(datos[0]);
                    var nrotransferencia = parseInt(datos[1]);
                    var newOption = "";
                    newOption += "El producto seleccionado ya se encuentra en la Transferencia Nro. <b><u><a href='/registrar_productos_transferir/"+idtransferencia+"/"+id_farmacia+"' class='text-danger'>"+nrotransferencia+"</a></u></b>. Si desea editar dar clic al número";
                    $('#det-msj-atenciones').html(newOption);
                    $("#btnAgregar").prop("disabled", true);
                    var newOption = "<option value='0'>--Seleccionar-</option>";
                    $('#id_lote').html(newOption);
                    $("#id_lote").select2({
                        width: 'resolve'
                    });
                    $('#id_lote').attr('disabled', true);
                    $('#msj-atenciones').show();
                } else {
                    getLotes();
                    $("#btnAgregar").prop("disabled", false);
                    $('#msj-atenciones').hide();
                }
           }
        });
    }

    function getLotes() {
        var id_farmacia = $('#id_farmacia').val();
        var id_producto = $('#id_productoBus').val();

        if (id_producto == "") {
            var newOption = "<option value='0'>--Seleccionar-</option>";
            $('#id_lote').html(newOption);
            $("#id_lote").select2({
                width: 'resolve'
            });
            $('#id_lote').attr('disabled', true);
        } else {
            $('#id_lote').attr('disabled', false);
        }

        $("#frm_transferencia").find("#lote").val('');
        $("#frm_transferencia").find("#codigo").val('');
        $("#frm_transferencia").find("#producto").val('');
        $("#frm_transferencia").find("#registro_sanitario").val('');
        $("#frm_transferencia").find("#fecha_vencimiento").val('');
        $("#frm_transferencia").find("#cantidad_actual").val('');
        $("#frm_transferencia").find("#id_producto").val('');
        $("#frm_transferencia").find("#id_stock_farmacia").val('');
        $("#frm_transferencia").find("#cantidad_transferir").val('');
		$("#frm_transferencia").find("#precio").val('');
        $('#id_lote').attr('disabled', false);

        $.ajax({
            url: '/obtenerLoteByIdProductoAndIdfarmacia/' + id_producto + '/' + id_farmacia,
            success: function (result) {
                var newOption = "";
                newOption = "<option value='0'>--Seleccionar-</option>";
                var cont = 0;
                $(result).each(function (ii, oo) {
                    cont = cont + 1;
                    newOption += "<option value='" + oo.codigo_producto + "#" + oo.lote + "#" + oo.fecha_vencimiento + "#" + oo.registro_sanitario + "#" + oo.precio_unitario + "#"+cont+"'>Nro."+cont+" - Lote: " + oo.lote + " - Vencimiento: " + oo.fecha_vencimiento + " - RS: " + oo.registro_sanitario + " - Precio Unit.: " + oo.precio_unitario + "</option>";
                });
                $('#id_lote').html(newOption);
                $("#id_lote").select2({max_selected_options: 4});

            }
        });
    }

    function cargarData(data) {
        $(data).each(function (ii, oo) {
            $("#frm_transferencia").find("#lote").val(oo.lote);
            $("#frm_transferencia").find("#codigo").val(oo.codigo_producto);
            //$("#frm_transferencia").find("#producto").val(oo.prod_farmaceutico);
            
            if(oo.presentacion==1){
                descripcion=oo.prod_farmaceutico+'(' + oo.cantidad2 + ' x ' + oo.presentacion_comercial + ')';
                $("#frm_transferencia").find("#producto").val(descripcion);
            }else
                $("#frm_transferencia").find("#producto").val(oo.prod_farmaceutico);
            $("#frm_transferencia").find("#registro_sanitario").val(oo.registro_sanitario);
            $("#frm_transferencia").find("#fecha_vencimiento").val(oo.fecha_vencimiento);
            $("#frm_transferencia").find("#cantidad_actual").val(oo.cantidad - oo.cantidadtranspen);
            $("#frm_transferencia").find("#id_producto").val(oo.id_producto);
            $("#frm_transferencia").find("#id_presentacion_comercial").val(oo.presentacion_comercial);
            $("#frm_transferencia").find("#id_stock_farmacia").val(oo.id_stock_farmacia);
            $("#frm_transferencia").find("#precio").val(oo.precio_unitario);
        });
    }

    function validateFrm() {
        var msg = "";
        var sw = true;
        var id_productoBus = $('#id_productoBus').val();
        var cant_trans = $('input[name=cantidad_transferir]').val();
        var id_lote = $('#id_lote').val();
        var cantidad_actual = $('#cantidad_actual').val();
        var cantidad_transferir = $('#cantidad_transferir').val();
        var presentacion_comercial = $('#id_presentacion_comercial').val();

        if (id_productoBus == "") {
            msg += "Seleccione un Producto.<br/>";
            sw = false
        }
        if (id_lote == "0") {
            msg += "Seleccione un Lote.<br/>";
            sw = false
        }
        if (cant_trans == "") {
            msg += "Ingrese cantidad a transferir.<br/>";
            sw = false
        }
        
        if (cant_trans == "0") {
            msg += "Ingrese cantidad a transferir mayor a 0.<br/>";
            sw = false
        }
        
        if (esEntero(cant_trans) == false) {
            msg += "La cantidad a transferir debe ser un número entero.<br/>";
            sw = false
        }
        
        if (parseInt(cantidad_actual) < parseInt(cantidad_transferir*presentacion_comercial)) {
            msg += "La cantidad a transferir debe ser menor o igual a la cantidad actual.<br/>";
            sw = false
        }        

        if (sw == false) {
            bootbox.alert(msg);
            return sw;
        } else {
            submitFrm();
        }
        return false;
    }
    function submitFrm() {
        bootbox.confirm({
            message: "Se registrarán los registros ingresados, ¿Está seguro de continuar?",
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success btn-md'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger btn-md'
                }
            },
            callback: function (result) {
                if (result == true) {
                    document.frm_transferencia.submit();
                }
            }
        });
    }
    //-------------------------------------------------------------------------
</script>
<style>
    .etiqueta{margin-top: 5px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
           <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Transferencia: {{$farmacia_origen}} - {{$destino->nombre}} <br>
                            @if($transferencia->id_motitransferencia==13)
                                <span style="font-size:12px">Nro. Pecosa: <b style="font-size:17px">{{$transferencia->nro_acta}}</b></span><br>
                            @else
                                <span style="font-size:12px">Nro. Nota Salida: <b style="font-size:17px">{{$transferencia->nro_acta}}</b></span><br>
                            @endif
                        <span style="font-size:12px">Fecha de Transferencia: {{$transferencia->fecha}}</span>
                    </h3>      
                    <a class="btn btn-info pull-right" href="/all_transferencias/{{ Auth::user()->id_farmacia }}">
                        <i class="fa fa-list"></i> Ver Transferencias
                    </a>
                </div>
                <div class="panel-body">
                    <div class="col-sm-12">
                        <div id="msj-atenciones" class="alert alert-danger" style="display: none">
                            <div id="det-msj-atenciones"></div>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Agregar Producto</h3>                    
                            </div>
                            <div class="panel-body">
                                <form name="frm_transferencia" id="frm_transferencia" method="POST" action="{{ route('agregar_producto_transferencia')}}" onsubmit="return validateFrm();">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Seleccionar el Producto</label>
                                            <select class="form-control" name="id_productoBus" id="id_productoBus" onchange="getValidtransPendientesByProducto()" style="width: 100%">
                                                <option value="">--Seleccionar--</option>
                                                @foreach($lotes as $lo)
                                                <option value="<?php echo $lo->id_producto ?>">{{$lo->codigo_producto}} - {{$lo->prod_farmaceutico}}</option>
                                                @endforeach
                                            </select>
                                            <input value="{{ Auth::user()->id_farmacia }}" name="id_farmacia" id="id_farmacia" type="hidden">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Seleccionar Lote</label>
                                            <select class="form-control" name="id_lote" id="id_lote" disabled="" style="width: 100%">
                                                <option value="0">--Seleccionar--</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 etiqueta">
                                            <label>Codigo</label>
                                            <input id="codigo" name="codigo" value="" required="" class="form-control" type="text" readonly="">  
                                            <input id="id_producto" name="id_producto"  class="form-control" type="hidden" >
                                            <input id="id_transferencia" name="id_transferencia"  class="form-control" type="hidden" value="{{$transferencia->id}}" >                            
                                            <input id="id_stock_farmacia" name="id_stock_farmacia"  class="form-control" type="hidden" >   
                                            <input id="farmacia_origen" name="farmacia_origen"  class="form-control" type="hidden" value="{{$farmacia_origen}}">
                                            <input id="consultorio_destino" name="consultorio_destino"  class="form-control" type="hidden" value="{{$destino->nombre}}">
                                            <input id="fecha_movimiento" name="fecha_movimiento"  class="form-control" type="hidden" value="{{$transferencia->fecha}}">
                                        </div>                        
                                        <div class="col-md-12 etiqueta">
                                            <label>Producto</label>
                                            <textarea name="producto" id="producto" value="" required="" class="form-control" readonly="">      
                                            </textarea>
                                            <input value="" name="id_presentacion_comercial" id="id_presentacion_comercial" type="hidden">
                                        </div> 
                                        <div class="col-md-6 etiqueta">
                                            <label>lote</label>
                                            <input name="lote" id="lote" value="" required="" class="form-control" type="text" readonly="">     
                                        </div> 
                                        <div class="col-md-6 etiqueta">
                                            <label>Registro Sanitario</label>
                                            <input id="registro_sanitario"  name="registro_sanitario" value="" required="" class="form-control" type="text" readonly="">     
                                        </div>
                                        <div class="col-md-7 etiqueta">
                                            <label>Fecha Vencimiento</label>
                                            <input id="fecha_vencimiento" name="fecha_vencimiento" value="" required="" class="form-control" type="text" readonly="">     
                                        </div>
                                        <div class="col-md-5 etiqueta">
                                            <label>Cant. Actual</label>
                                            <input id="cantidad_actual" name="cantidad_actual" value="" required="" class="form-control" type="text" readonly="">     
                                        </div>
                                        <div class="col-md-5 etiqueta">
                                            <label>Precio</label>
                                            <input id="precio" name="precio" value="" required="" class="form-control" type="text" readonly="">     
                                        </div>
                                        <div class="col-md-12 etiqueta">
                                            <label>Cantidad a transferir</label>
                                            <input autocomplete="off" onkeypress="return justNumbers(event);" id="cantidad_transferir" name="cantidad_transferir" value="" class="form-control" type="text">     
                                        </div>
                                        @if($transferencia->estado<=3)
                                            <div class="col-md-12 etiqueta pull-right">
                                                <input class="btn btn-success form-control" value="Agregar" type="submit" id="btnAgregar">
                                                <input value="1" name="registro" id="registro" type="hidden">
                                            </div>
                                        @else
                                            <div class="col-md-12 etiqueta pull-right">
                                                <input class="btn btn-success form-control" value="Transferencia culminada" type="submit" disabled="">
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8" style="margin-top:15px">
                        <div class="row">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Productos seleccionados</h3>  
                                <?php
                                $total = 0;
                                $cantidad_total=0;
                                if ($transferencia->estado < 2) {
                                    ?>
                                    @if($almacen==1)                                                                                    
                                        <!--a class="btn btn-primary btn-md pull-right" href="/cerrar_carga_transferencia/{{$transferencia->id}}/{{$transferencia->tipo}}/{{ Auth::user()->id_farmacia }}">
                                            <i class="fa fa-ambulance" aria-hidden="true"></i> Descontar Stock
                                        </a-->
                                        <a class="btn btn-success btn-md pull-right" href="/cambiar_estado_envio/{{$transferencia->id}}/{{ Auth::user()->id_farmacia }}">
                                                <i class="fa fa-ambulance" aria-hidden="true"></i> Enviar productos al Destino
                                            </a>
                                    @else
                                        <a class="btn btn-success btn-md pull-right" href="/cerrar_carga_transferencia/{{$transferencia->id}}/{{$transferencia->tipo}}/{{ Auth::user()->id_farmacia }}">
                                            <i class="fa fa-ambulance" aria-hidden="true"></i> Enviar productos al Destino
                                        </a>
                                    @endif
                                    <br/> 
                                    <?php
                                } else {
                                    if ($transferencia->estado ==5) {
                                        if($almacen==1){
                                        ?>
                                            <a class="btn btn-success btn-md pull-right" href="/cambiar_estado_envio/{{$transferencia->id}}/{{ Auth::user()->id_farmacia }}">
                                                <i class="fa fa-ambulance" aria-hidden="true"></i> Enviar productos al Destino
                                            </a>
                                        <?php 
                                        }
                                    }
                                    else
                                    {   ?>
                                        <button class="pull-right btn btn-danger btn-xs" disabled="" type="submit">
                                                <i class="fa fa-info-circle"></i>
                                    
                                    <?php switch ($transferencia->estado) {
                                            case 2:
                                                echo "Productos enviados, pendiente de visto por DIREOSS";
                                                break;
                                            case 3:
                                                echo "Pendiente de recepción de " . $destino->nombre;
                                                break;
                                            case 4:
                                                echo "Transferencia finalizada";
                                                break;
                                            case 6:
                                                echo "Transferencia finalizada";
                                                break;
                                        }
                                    }
                                }
                                ?>
                                </button>
                                <br>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table color" style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Lote</th>
                                                <th>Registro<br/>Sanitario</th>
                                                <th>Fecha<br/>Vencimiento</th>
                                                <th>Cantidad<br/>Transferida</th>
                                                <th>Precio<br/>Unitario</th>
                                                <th>Precio<br/>Total</th>                                                
                                                <th>Editar</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x=1; ?>
                                            @foreach($productos as $p)
                                            <tr>
                                                <td><?php echo $x++; ?></td>
                                                <td>{{$p->codigo_producto}} - {{$p->nombre_producto}} - {{$p->unidad}}
                                                @if($p->presentacion==1)
                                                    <small style="color: red">({{$p->cantidad_transferida}} x {{$p->presentacion_comercial}})
                                                </small>
                                                @endif
                                                </td>
                                                <td>{{$p->lote}}</td>
                                                <td>{{$p->registro_sanitario}}</td>
                                                <td>
                                                    <?php
                                                    echo $p->fecha_vencimiento;
                                                    ?>
                                                </td>
                                                <td class="text-center"><b>{{$p->cantidad_transferida*$p->presentacion_comercial}}</b></td>
                                                <td class="text-center">
                                                    <?php echo number_format($p->precio_unitario, 6, '.', '');?>
                                                </td>
                                                <td class="text-center">
                                                    <b><?php
                                                    $cantidad_transferida = $p->cantidad_transferida*$p->presentacion_comercial;
                                                    $cantidad_total += $cantidad_transferida;
                                                    $precio_unitario = $p->precio_unitario;
                                                    $subtotal = $cantidad_transferida * $precio_unitario;
                                                    $total += $subtotal;
                                                    $subtotal;
                                                    echo number_format($subtotal,6, '.', '');
                                                    ?></b>
                                                </td>
                                                @if($transferencia->estado < 3)
                                                    @if($almacen==1)    
                                                        <td class="text-center"><a class="btn btn-primary" href="/editar_producto_para_transferir_actualizado/{{$p->id_transferencia_producto}}"><i class="fa fa-edit"></i></a></td>
                                                        <td class="text-center"><a class="btn btn-danger" href="/eliminar_producto_para_transferir_actualizado/{{$p->id_transferencia_producto}}/{{ Auth::user()->id_farmacia }}"><i class="fa fa-remove"></i></a></td>
                                                    @else
                                                    <td class="text-center"><a class="btn btn-primary" href="/editar_producto_para_transferir/{{$p->id_transferencia_producto}}"><i class="fa fa-edit"></i></a></td>
                                                    <td class="text-center"><a class="btn btn-danger" href="/eliminar_producto_para_transferir/{{$p->id_transferencia_producto}}/{{ Auth::user()->id_farmacia }}"><i class="fa fa-remove"></i></a></td>
                                                    @endif
                                                @else
                                                    @if(Auth::user()->flagestablecimientos!=1)
                                                        <td class="text-center"><a class="btn btn-primary disabled" href="#"><i class="fa fa-edit"></i></a></td>
                                                        <td class="text-center"><a class="btn btn-danger disabled" href="#"><i class="fa fa-remove"></i></a></td>
                                                    @else
                                                        <td class="text-center"><a class="btn btn-primary" href="/editar_producto_para_transferir_actualizado/{{$p->id_transferencia_producto}}"><i class="fa fa-edit"></i></a></td>
                                                        <td class="text-center"><a class="btn btn-danger" href="/eliminar_producto_para_transferir_actualizado/{{$p->id_transferencia_producto}}/{{ Auth::user()->id_farmacia }}"><i class="fa fa-remove"></i></a></td>
                                                    @endif
                                                @endif                                                          
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5"><strong>TOTAL</strong></td>
                                                <td class="text-center"><b><?php echo $cantidad_total ?></b></td>
                                                <td></td>
                                                <td><b><?php echo number_format($total, 6, '.', ''); ?></b></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
    
@include('frontend.includes.footer')
@endsection
