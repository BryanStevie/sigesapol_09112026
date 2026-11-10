<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master')
@section('renderjs')

{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<style>
    body {
        background-color: #eee;
    }

    *[role="form"] {
        max-width: 530px;
        padding: 15px;
        margin: 0 auto;
        background-color: #fff;
        border-radius: 0.3em;
    }

    *[role="form"] h2 {
        margin-left: 5em;
        margin-bottom: 1em;
    }

    .ui-autocomplete {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 7px;
        margin: 2px 0 0;
        list-style: none;
        font-size: 15px;
        font-family:Arial, Helvetica, sans-serif;
        text-align: left;
        background-color: #ffffff;
        border: 1px solid #cccccc;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        background-clip: padding-box;
    }

    .ui-autocomplete > li > div {
        display: block;
        padding: 3px 20px;
        clear: both;
        font-weight: normal;
        line-height: 1.42857143;
        color: #333333;
        white-space: nowrap;
    }

    .ui-state-hover,
    .ui-state-active,
    .ui-state-focus {
        text-decoration: none;
        color: #FFFFFF!important;
        background: #1d71b8!important;
        cursor: pointer;
    }

    .ui-helper-hidden-accessible {
        border: 0;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }

    @media print{

      .oculto-impresion, .oculto-impresion *{

        display: none !important;

      }

    }

</style>

<script>
    function imprimir(){
        window.print();
    }

    $(document).ready(function () {

        $("#id_anaquel").val('<?php echo $id_anaquel ?>')

        __autocomplete__AjaxBusqueda();
        /*
         $("#frmlotizar").submit(function (e) {
         disponible = parseFloat($("#frmlotizar").find("#disponible").val());
         cantidad = parseFloat($("#frmlotizar").find("#cantidad").val());
         if (cantidad > disponible) {
         alert("La cantidad (" + cantidad + ") no debe ser mayor a la cantidad disponible por lotizar (" + disponible + ")");
         e.preventDefault();
         return false;
         }
         });
         */

        $("#btnReg").on("click", function () {

            if ($('#frmReceta').find("#id_producto").val() == "") {
                bootbox.alert("Falta seleccionar el Producto");
                return false;
            }

            //return false;

        });
        
        $("#id_consultorio").select2({
            width: 'resolve' // need to override the changed default
        });
        $("#id_consultorio").trigger('change');


    });

    function __autocomplete__AjaxBusqueda() {

        $("#nombre_producto").autocomplete({
            minLength: 1,
            delay: 400,
            source: function (request, response) {
                $.ajax({
                    url: '/obtener_producto/' + request.term,
                    dataType: "json",
                    success: function (data) {
                        response(data);
                    }

                })

            }, select: function (e, ui) {
                var id_producto = ui.item.id;
                $('#id_producto').val(id_producto);
            }
        });


    }

    function reporteConsolidadoStock() {

        var fechaDesde = $('#fechaDesde').val();
        var fechaHasta = $('#fechaHasta').val();
        var id_user = $('#idUser').val();
        var id_anaquel = $('#id_anaquel').val();
        var id_consultorio = $('#id_consultorio').val();
        if (fechaDesde == "")
            fechaDesde = 0;
        if (fechaHasta == "")
            fechaHasta = 0;
        location.href = 'exportar_consolidado_stock/' + fechaDesde + '/' + fechaHasta + '/' + id_user + '/' + id_anaquel + '/' + id_consultorio;
    }
    function reporteConsolidadoStockToSet() {
        const btnExportar = document.getElementById('btnExportarEnBaseFecha');
        var fechaDesde = $('#fechaDesde').val();
        var fechaHasta = $('#fechaHasta').val();
        var id_user = $('#idUser').val();
        var id_anaquel = $('#id_anaquel').val();
        var id_consultorio = $('#id_consultorio').val();
        if (fechaDesde == "")
            fechaDesde = 0;
        if (fechaHasta == "")
            fechaHasta = 0;
        const path = 'exportar_consolidado_stock/' + fechaDesde + '/' + fechaHasta + '/' + id_user + '/' + id_anaquel + '/' + id_consultorio;
        btnExportar.setAttribute('href', path);
    }

    reporteConsolidadoStockToSet();

    function reporteConsolidadoStockPDF() {

        var fechaDesde = $('#fechaDesde').val();
        var fechaHasta = $('#fechaHasta').val();
        var id_user = $('#idUser').val();
        var id_anaquel = $('#id_anaquel').val();
        var id_consultorio = $('#id_consultorio').val();
        if (fechaDesde == "")
            fechaDesde = 0;
        if (fechaHasta == "")
            fechaHasta = 0;

        window.open('imprimir_consolidado_stock_pdf/' + fechaDesde + '/' + fechaHasta + '/' + id_user + '/' + id_anaquel + '/' + id_consultorio,'_blank');        
    }

    function reporteConsolidadoStockPrint() {

        var fechaDesde = $('#fechaDesde').val();
        var fechaHasta = $('#fechaHasta').val();
        var id_user = $('#idUser').val();
        var id_anaquel = $('#id_anaquel').val();
        var id_consultorio = $('#id_consultorio').val();
        if (fechaDesde == "")
            fechaDesde = 0;
        if (fechaHasta == "")
            fechaHasta = 0;
        location.href = 'imprimir_consolidado_stock_print/' + fechaDesde + '/' + fechaHasta + '/' + id_user + '/' + id_anaquel + '/' + id_consultorio;
    }

    $(document).ready(function () {

        $('#fechaDesde').datepicker().on('hide', function (ev) {
            var fechaFinIni = $('#fechaHasta').val();
            var fechaIni = $('#fechaDesde').val();
            f1 = fechaIni.split("-");
            var fechaFin = $('#fechaHasta').val();
            f2 = fechaFin.split("-");
            f3 = new Date();
            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
            if (f1 > f2) {
                $('#fechaDesde').val(fechaFinIni);
                $('#fechaDesde').change();
                bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
                return false;
            }
        });


        $('#fechaHasta').datepicker().on('hide', function (ev) {
            var fechaFinIni = $('#fechaDesde').val();
            var fechaIni = $('#fechaDesde').val();
            f1 = fechaIni.split("-");
            var fechaFin = $('#fechaHasta').val();
            f2 = fechaFin.split("-");
            f3 = new Date();
            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
            if (f1 > f2) {
                $('#fechaHasta').val(fechaFinIni);
                $('#fechaHasta').change();
                bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
                return false;
            }
        });

        $('#btnExportarEnBaseFecha').on('click', function(event) {
                event.preventDefault(); // Prevenir el comportamiento por defecto del enlace
                
                // Mostrar el modal de carga
                Swal.fire({
        title:
          "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Reporte EXCEL</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
          Swal.showLoading();
        },
  });
                // Iniciar la descarga
                var url = $(this).attr('href');
                
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        // Crear un enlace temporal para la descarga
                        var downloadUrl = URL.createObjectURL(blob);
                        var link = document.createElement('a');
                        link.href = downloadUrl;
                        link.download = 'Reporte de Productos Por Establecimientos'; // Puedes establecer el nombre del archivo aquí si lo deseas
                        document.body.appendChild(link);
                        link.click();

                        // Remover el enlace temporal
                        document.body.removeChild(link);
                        URL.revokeObjectURL(downloadUrl);

                        // Ocultar el modal de carga
                        Swal.fire({
                icon: "success",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se descargo el archivo correctamente, revisar la carpeta de descargas.</span></div>",
                confirmButtonText: "ENTENDIDO",
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "ENTENDIDO",
        });
                    })
                    .catch(error => {
                        console.error('Hubo un problema con la descarga:', error);
                        Swal.fire({
            icon: "error",
            title: "ERROR ",
            text: "Servicio no disponible. intentelo en un minutos.",
        });
                    });
            });

    });


</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <span class="glyphicon glyphicon-th">
                    </span> Consolidado de Movimiento por Producto
                </a>
            </h4>
        </div>

        <div class="panel-body">
            <form id="frmReceta" method="post" action="{{ route('consolidado_stock')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="row oculto-impresion">
                    <div class="col-sm-2">
                        <label>Fecha Desde</label>
                        <input type="hidden" name="idUser" id="idUser" value="<?php echo Auth::user()->id ?>"/>
                        <input class="form-control input-sm datepicker" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" required="" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"/>
                    </div>

                    <div class="col-sm-2">
                        <label>Fecha Hasta</label>
                        <input class="form-control input-sm datepicker" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" required="" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"/>
                    </div>
                </div>
                <div class="row oculto-impresion">
                    <div class="col-sm-3">
                        <label><small>Servicio</small></label>
                        <select class="form-control input-sm" name="id_consultorio" id="id_consultorio" style="width: 100%">
                            <option value="0">Todos</option>
                            <?php
                            foreach ($consultorio as $d) {
                                echo '<option value="' . $d->id . '"';
                                if (isset($idconsultorio)) {
                                    if ($idconsultorio == $d->id)
                                        echo " selected";
                                }
                                echo '>' . $d->nombre . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-sm-2 oculto-impresion">
                        <label>Anaquel</label>
                        <select class="form-control input-sm" name="id_anaquel" id="id_anaquel">
                            <option value="0">-Todos-</option>
                            @foreach($anaqueles as $f)
                            <option value="{{$f->id}}">{{$f->nombre}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 oculto-impresion">
                        <label>&nbsp;</label>
                        <input class="form-control btn btn-info input-sm" value="Buscar" type="submit" id="btnReg">
                    </div>
                    <div class="col-md-3 oculto-impresion">
                        <label>&nbsp;</label><br/>
                        <a id="btnExportarEnBaseFecha" class="btn btn-success input-sm" href="#" role="button">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel
                        </a>
                        <a class="btn btn-default input-sm" target="_blank" onclick="reporteConsolidadoStockPDF()">
                            <i class="fa fa-print"></i> Imprimir
                        </a>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-group">
                        <br/>
                        <div class="panel panel-info">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr style='font-size: 10px;'>
                                                <th style="border:1px solid #999999">N.</th>
                                                <th style="border:1px solid #999999">C&oacute;digo</th>
                                                <th style="border:1px solid #999999">Detalle de Producto</th>
                                                <th style="border:1px solid #999999">U.Med</th>
                                                <th class="oculto-impresion" style="border:1px solid #999999">Tipo de Producto</th>
                                                <th style="border:1px solid #999999">Anaquel</th>
                                                <th class="oculto-impresion"  style="border:1px solid #999999">Saldo Anterior</th>
                                                <th class="oculto-impresion"  style="border:1px solid #999999">Entradas</th>
                                                <th class="oculto-impresion" style="border:1px solid #999999">Salidas</th>
                                                <th style="border:1px solid #999999">Stock</th>
                                                <th style="border:1px solid #999999">P.Unit</th>
                                                <th style="border:1px solid #999999">Vencimiento</th>
                                            </tr>
                                            <tr></tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            //$saldo = (isset($kardex_inicial->total)) ? $kardex_inicial->total : 0;
                                            if (isset($kardex)):
                                                $x=0;
                                                foreach ($kardex as $key => $rowKardex):
                                                    ?>
                                                    @if($rowKardex->stock_desde>0 or $rowKardex->stock_hasta>0 )
                                                        <?php $x++; ?>
                                                    <tr  style='font-size: 10px;'>
                                                        <td align="center" class="con0"><?php echo $x ?></td>	
                                                        <td align="left" class="con0"><?php echo $rowKardex->codigo_producto ?></td>
                                                        <td align="left" class="con0"><?php echo $rowKardex->nombre_producto ?></td>
                                                        <td align="left" class="con0"><?php echo $rowKardex->unidad ?></td>
                                                        <td align="left" class="con1 oculto-impresion"><?php echo $rowKardex->nombre_tipoproducto ?></td>
                                                        <td align="center" class="con1"><?php echo $rowKardex->anaquel ?></td>
                                                        <?php if($rowKardex->id_producto==11045):?>
                                                            <td align="right" class="con0 oculto-impresion"><?php echo $rowKardex->stock_desde/1000 ?></td>
                                                            <td align="right" class="con0 oculto-impresion"><?php echo $rowKardex->ingresos/1000 ?></td>
                                                            <td align="right" class="con0 oculto-impresion"><?php echo ($rowKardex->egresos/1000 - $rowKardex->devueltas/1000) ?></td>
                                                            <td class="con1" align="right"><?php echo $rowKardex->stock_hasta/1000 ?></td>
                                                        <?php else:?>
                                                            <td align="right" class="con0 oculto-impresion"><?php echo $rowKardex->stock_desde ?></td>
                                                            <td align="right" class="con0 oculto-impresion"><?php echo $rowKardex->ingresos ?></td>
                                                            <td align="right" class="con0 oculto-impresion"><?php echo ($rowKardex->egresos - $rowKardex->devueltas) ?></td>
                                                            <td class="con1" align="right"><?php echo $rowKardex->stock_hasta ?></td>
                                                        <?php endif; ?>
                                                        <td class="con1" align="right"><?php echo number_format($rowKardex->precio_unitario, 2, '.', ','); ?></td>
                                                        <td class="con1" align="center"><?php if($rowKardex->fecha_vencimiento == ""){ echo "-";} else{echo $rowKardex->fecha_vencimiento;} ?></td>
                                                    </tr>
                                                    @endif
                                                <?php endforeach; ?>
                                            <?php endif; ?>
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
</div> <!-- ./container -->

<!-- line modal -->

@include('frontend.includes.footer')
@endsection
