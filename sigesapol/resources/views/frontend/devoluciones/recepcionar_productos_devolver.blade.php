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
    $(document).ready(function () {
        $cmbid_lote = $("#frm_transferencia").find("#id_lote");
        $cmbid_lote.select2();
        id_farmacia = $("#frm_transferencia").find("#id_farmacia").val();
        $cmbid_lote.change(function () {
            $this = $(this);
            cmbid_lote = $this.val();
            option = {
                url: '/obtener_lote_byIdlote_Idfarmacia/' + cmbid_lote + '/' + id_farmacia,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                //alert(data);
                cargarData(data);
            });
        });

    });

    //-------------------------------------------------------------------------
    function cargarData(data) {
        $(data).each(function (ii, oo) {
            $("#frm_transferencia").find("#lote").val(oo.lote);
            $("#frm_transferencia").find("#codigo").val(oo.codigo);
            $("#frm_transferencia").find("#producto").val(oo.nombre);
            $("#frm_transferencia").find("#registro_sanitario").val(oo.registro_sanitario);
            $("#frm_transferencia").find("#fecha_vencimiento").val(oo.fecha_vencimiento);
            $("#frm_transferencia").find("#cantidad_actual").val(oo.cantidad);
            $("#frm_transferencia").find("#id_producto").val(oo.id_producto);
            $("#frm_transferencia").find("#id_stock_farmacia").val(oo.id_stock_farmacia);
        });
    }
    //-------------------------------------------------------------------------
    
    function validateFrm() {
        var msg = "";
        var sw = true;
        var nota_entrada = $('#nota_entrada').val();
        var nota_entrada_actu = $('#nota_entrada_actu').val();
        
        if (nota_entrada == "0") {
            msg += "La nota de entrada debe ser diferente a 0.<br/>";
            sw = false
        }
        
        if (esEntero(nota_entrada) == false) {
            msg += "La nota de entrada debe ser un número entero.<br/>";
            sw = false
        }
        if (parseInt(nota_entrada) < parseInt(nota_entrada_actu)) {
            msg += "La nota de entrada no debe ser menor al último correlativo generado.<br/>";
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
            message: "Se recepcionarán todos los productos mostrados, ¿Está seguro de continuar?",
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result == true) {
                    document.frm_transferencia.submit();
                }
            }
        });
    }
</script>
<style>
    .etiqueta{margin-top: 5px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
    .alert-message
    {
        margin: 10px 0;
        padding: 10px;
        border-left: 3px solid #eee;
    }
    .alert-message h4
    {
        margin-top: 0;
        margin-bottom: 5px;
    }
    .alert-message p:last-child
    {
        margin-bottom: 0;
    }
    .alert-message code
    {
        background-color: #fff;
        border-radius: 3px;
    }
    .alert-message-success
    {
        background-color: #F4FDF0;
        border-color: #3C763D;
    }
    .alert-message-success h4
    {
        color: #3C763D;
    }
    .alert-message-danger
    {
        background-color: #fdf7f7;
        border-color: #d9534f;
    }
    .alert-message-danger h4
    {
        color: #d9534f;
    }
    .alert-message-warning
    {
        background-color: #fcf8f2;
        border-color: #f0ad4e;
    }
    .alert-message-warning h4
    {
        color: #f0ad4e;
    }
    .alert-message-info
    {
        background-color: #f4f8fa;
        border-color: #5bc0de;
    }
    .alert-message-info h4
    {
        color: #5bc0de;
    }
    .alert-message-default
    {
        background-color: #EEE;
        border-color: #B4B4B4;
    }
    .alert-message-default h4
    {
        color: #000;        
    }
    .alert-message-notice
    {
        background-color: #FCFCDD;
        border-color: #BDBD89;
    }
    .alert-message-notice h4
    {
        color: #444;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Recibir Productos del {{$nombre_farmacia_origen->nombre}}<br>                        
                    </h3>      
                    <a class="btn btn-default btn-xs pull-right" href="/all_transferencias_recibidas/{{ Auth::user()->id_farmacia }}">
                        <i class="fa fa-list"></i> Ver Transferencias recibidas
                    </a>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <p>
                            Nro. Acta de transferencia: {{$transferencia->nro_acta}}<br>
                            Fecha: {{$transferencia->fecha}}
                        </p>
                        <div class="alert-message alert-message-success" style="font-size:12px">
                            <h4>
                                Aviso importante
                            </h4>
                            <p>
                                Es importante verificar los productos que se van a recepcionar antes de actualizar al Stock.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:15px">
                        <form name="frm_transferencia" id="frm_transferencia" method="POST" action="{{ route('enviar_productos_al_stock')}}" onsubmit="return validateFrm();">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id_transferencia" value="{{ $id_transferencia }}">
                            <input type="hidden" name="id_farmacia_destino" value="{{ $id_farmacia_destino }}">
                            <input type="hidden" name="id_farmacia_origen" value="{{ $id_farmacia_origen }}">  
                            <input type="hidden" name="nombre_farmacia_origen" value="{{ $nombre_farmacia_origen->nombre }}">
                            <input type="hidden" name="nombre_farmacia_destino" value="{{ $nombre_farmacia_destino->nombre }}">
                            <input type="hidden" name="nota_entrada_actu" id="nota_entrada_actu" value="{{$nea->nro_nea}}"/>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        Productos seleccionados
                                        @if($transferencia->estado<4)
                                        <div class="pull-right">
                                            Nota de Entrada &nbsp;&nbsp;
                                            <input autofocus onkeypress="return justNumbers(event);" type="text" name="nota_entrada" id="nota_entrada" style="width: 100px; text-align: center;" required="" value="{{$nea->nro_nea}}" autocomplete="OFF">
                                            -<?php echo date("Y") ?>&nbsp;
                                            <button class="btn btn-default btn-xs pull-right" type="submit">
                                                <i class="fa fa-folder-open"></i> Recepcionar productos
                                            </button>
                                        </div>
                                        @else
                                        <div class="pull-right">Nota de Entrada: {{$transferencia->nota_entrada}}</div>
                                        @endif
                                    </h3>       
                                </div>
                                <div class="panel-body">
                                    <table class="table" style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th>N</th>
                                                <th>Código</th>
                                                <th>Producto</th>
                                                <th>Unidad</th>
                                                <th>Lote</th>                                    
                                                <th>R.S.</th>
                                                <th>Fecha Vencimiento</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $n = 0;
                                            $total = 0; ?>
                                            @foreach($productos as $p)
                                            <tr>
                                                <td><?php
                                                    $n = $n + 1;
                                                    echo $n;
                                                    ?></td>
                                                <td>{{$p->codigo_producto}}</td>
                                                <td>{{$p->nombre_producto}}</td>
                                                <td>{{$p->unidad}}</td>
                                                <td>{{$p->lote}}</td>
                                                <td>{{$p->registro_sanitario}}</td>
                                                <td>{{$p->fecha_vencimiento}}</td>
                                                <td>{{$p->cantidad}}</td>
                                                <td>{{$p->precio_unitario}}</td>
                                                <td class="text-center">
                                                    <?php
                                                    $cantidad = $p->cantidad;
                                                    $precio_unitario = $p->precio_unitario;
                                                    $subtotal = $cantidad * $precio_unitario;
                                                    $total += $subtotal;
                                                    echo $subtotal;
                                                    ?>
                                                </td>
                                            </tr> 
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9"></td>
                                                <td class="text-center"><?php echo $total ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </form>

<?php if ($transferencia->estado == 4): ?>
                            <a href="/imprimir_nota_entrada/<?php echo $transferencia->id ?>" class="btn btn-danger pull-right" target="_blank">
                                Imprimir NEA <i class="fa fa-file-pdf-o"></i>
                            </a>
<?php endif; ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@include('frontend.includes.footer')
@endsection