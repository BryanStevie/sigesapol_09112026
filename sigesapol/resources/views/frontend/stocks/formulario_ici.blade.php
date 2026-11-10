@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .filterable {
        margin-top: 0px;
    }
    .filterable .panel-heading .pull-right {
        margin-top: -20px;
    }
    .filterable .filters input[disabled] {
        background-color: transparent;
        border: none;
        cursor: auto;
        box-shadow: none;
        padding: 0;
        height: auto;
        font-size: 10px
    }
    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333; font-size: 10px
    }
    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333; font-size: 10px
    }
    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333; font-size: 10px
    }
    #global {
        height: 640px !important;
        width: auto;
        border: 1px solid #ddd;
        background: #f1f1f1;
        overflow-y: scroll !important;
    }
    .margin{
        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }
</style>
<script>
        $(document).ready(function () {
        $('.filterable .btn-filter').click(function () {
            var $panel = $(this).parents('.filterable'),
                    $filters = $panel.find('.filters input'),
                    $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function (e) {
            /* Ignore tab key */
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            /* Useful DOM data and selectors */
            var $input = $(this),
                    inputContent = $input.val().toLowerCase(),
                    $panel = $input.parents('.filterable'),
                    column = $panel.find('.filters th').index($input.parents('th')),
                    $table = $panel.find('.table'),
                    $rows = $table.find('tbody tr');
            /* Dirtiest filter function ever ;) */
            var $filteredRows = $rows.filter(function () {
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });
            /* Clean previous no-result if exist */
            $table.find('tbody .no-result').remove();
            /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
            $rows.show();
            $filteredRows.hide();
            /* Prepend no-result row if all rows are filtered */
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el laboratorio</td></tr>'));
            }
        });
        
        
    });

    //function reporteProductos(tipo){
    //    location.href = 'exportar_reporte_ici/' + '<?php echo $fecha_inicio ?>' + '/' + '<?php echo $fecha_fin ?>' + '/' + '<?php echo $id_farmacia ?>' + '/' + tipo;
    //}

    function reporteProductos(tipo) {

        Swal.fire({
            title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                var fecha_inicio = $('#fecha_inicio').val();
                var fecha_fin = $('#fecha_fin').val();
                var id_farmacia = $('#id_farmacia').val();
                

                if (fecha_inicio == "")fecha_inicio = 0;
                if (fecha_fin == "")fecha_fin = 0;
                if (id_farmacia == "")id_farmacia = 0;
                if (tipo == "")tipo = 0;
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "exportar_reporte_ici",
                    data: {
                        "fecha_inicio" : fecha_inicio,
                        "fecha_fin" : fecha_fin,
                        "id_farmacia" : id_farmacia,
                        "tipo" : tipo,
                    },
                    success: function (response) {
                    
                        if (response.excelNameResponse) {
                            // Obtener la URL del archivo del response
                            let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                            // Crear un enlace de descarga
                            let link = document.createElement('a');
                            console.log(fileUrl);
                            link.href = fileUrl;
                            link.download = 'Exportar_Reportes_del_ICI.xlsx';
                            link.innerText = 'Descargar archivo';
                            // Simular un clic en el enlace para iniciar la descarga
                            let divLinkReporte = document.getElementById('link-reporte');
                            divLinkReporte.appendChild(link);
                            link.click();
                            divLinkReporte.removeChild(link);
                            Swal.hideLoading();
                            Swal.fire({
                                icon: 'success',
                                title: 'El reporte se ha generado correctamente!',
                                showConfirmButton: false,
                                showConfirmButton: false,
                                timer: 1500,
                                onClose: () => {
                                    $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                                } 
                            });
                        }
                    },
                    error: function() {
                        Swal.hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al generar el reporte',
                            text: 'Por favor, inténtelo de nuevo más tarde',
                            confirmButtonText: 'Cerrar',
                            customClass: {
                                confirmButton : 'custom-swal-button-cerrar'
                            },
                            onClose: () => {
                                $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                            }
                        });
                    }
                });
            }
        });

        }
    
</script>
@endsection
@section('content')
<div id="blur-container">
<div class="container">
    <h3>
        {{$nombre_farmacia}}  
    </h3>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">Reporte ICI del {{$fecha_inicio}} Al {{$fecha_fin}}</h3>
                <div class="pull-right">
                    <form id="frm_ICI" class="" method="post" action="#">
                        <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filtar</button>
                        <a class="btn btn-default btn-xs" href="/form_generar_rerporte_ici">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                        <a class="btn btn-success btn-xs" href="javascript:void(0)" onclick="reporteProductos(1)" >
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar Medicamentos
                        </a>
                        <a class="btn btn-primary btn-xs" href="javascript:void(0)" onclick="reporteProductos(2)" >
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar Dispositivos
                        </a>
                        <input type="hidden" id="fecha_inicio"  name="fecha_inicio" value="<?php echo $fecha_inicio ?>" />
                        <input type="hidden" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin ?>" />
                        <input type="hidden" id="id_farmacia" name="id_farmacia" value="<?php echo $id_farmacia ?>" />
                    </form>
                </div>
            </div>
            <div id="global">
                <table class="table" style="font-size: 11px !important;">
                    <thead>
                        <tr class="filters">
                            <th>Nro</th>                            
                            <th>Código</th>
                            <th><input type="text" class="form-control input-sm" placeholder="Productos Farmacéuticos y Disp. Médicos" disabled></th>
                            <th>Precio</th>
                            <th>CPMA</th>
                            <th>Stock Inicial</th>
                            <th><span style="color: blue"><i class="fa fa-plus-circle"></i>Ingreso Regular</span></th>
                            <th><span style="color: blue"><i class="fa fa-plus-circle"></i> Ingreso Transferencia</span></th>
                            <th>Total Ingreso</th>                                                   
                            <th> <span style="color: red"><i class="fa fa-minus-circle"></i> Consumo</span></th>
                            <th> <span style="color: red"><i class="fa fa-minus-circle"></i> Transferencia</span></th>
                            <th>Total Salida</th>                            
                            <th>Saldo Final</th>
                            <th>Fecha Vencimiento</th>                                 
                            <th>Meses Provisión</th>
                            <th>Situaci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 11px !important;">
                        {{-- */$x=0;/* --}}
                        @foreach($resultado as $r)
                        {{-- */$x++;/* --}}
                        <?php
                        $cpm = "";
                        $cpma = "";
                        $meses_provicion = "";
                        if ($r['cantidad'] > 0 && ($r['egresos'] - $r['recetas_devueltas']) == 0) {
                            $cpma = -1; //-1
                        } else {
                            if ($r['nro_mes'] == 0) {
                                $cpma = -1;
                            } else {
                                if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                                    $cpma = 0;
                                } else {
                                    $cpma = ($r['egresos'] - $r['recetas_devueltas']) / $r['nro_mes'];
                                    $cpma = round($cpma, 1);
                                }
                            }
                        }
                        
                        if($cpma>0){
                            $provision = round(($r['cantidad'] / $cpma), 1);
                            $meses_provicion = $provision;
                        }else
                        {
                            if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                                $meses_provicion = 0;
                            } else {                                
                                $meses_provicion = $r['cantidad'];
                            }
                        }
                        
                        /************************* */
                        $situac = "";
                        $situacionTemp = "";
                        if ($r['cantidad'] <= 0):
                            $situac = "Desabastecido";
                            $situacionTemp = 1;
                        else:
                            if ($cpma <= 0):
                                $situac = "Sin Rotacion";
                                $situacionTemp = 5;
                            endif;
                            if ($meses_provicion > 0 && $meses_provicion < 2):
                                $situac = "SubStock";
                                $situacionTemp = 4;
                            endif;
                            if ($meses_provicion >= 2 && $meses_provicion <= 6):
                                $situac = "NormoStock";
                                $situacionTemp = 3;
                            endif;
                            if ($meses_provicion > 6):
                                $situac = "SobreStock";
                                $situacionTemp = 2;
                            endif;
                        endif;
                        ?>
                        <tr>
                            <td>{{$x}}</td>
                            <td>
                                {{$r['codigo']}}
                            </td>
                            <td>{{$r['nombre']}} - {{$r['unidad']}}</td>  
                            <td>{{$r['precio']}}</td>
                            <td>                                   
                                <?php
                                if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                                    echo "0";
                                } else {
                                    $f = ($r['egresos'] - $r['recetas_devueltas']) / $r['nro_mes'];
                                    echo round($f, 0);
                                }
                                ?>
                            </td> 
                            <?php //dd($r['movimiento']); ?>
                            <td bgcolor="##337ab7"><span style="color: white">{{$r['movimiento'][0]['total']}}</span></td>
                            <td><span style="color: blue">{{$r['movimiento'][1]['total']}}</span></td>
                            <td><span style="color: blue">{{$r['movimiento'][8]['total']}}</span></td>
                            <td bgcolor="#D4E6F1"><span style="color: blue">{{$r['movimiento'][1]['total']+$r['movimiento'][8]['total']}}</span></td>
                            <td><span style="color: red">{{$r['movimiento'][3]['total']-$r['movimiento'][6]['total']}}</span></td>
                            <td><span style="color: red">{{$r['movimiento'][7]['total']}}</span></td>
                            <td bgcolor="#D4EFDF"><span style="color: green">{{($r['movimiento'][7]['total']+($r['movimiento'][3]['total']-$r['movimiento'][6]['total']))}}</span></td>
                            <td bgcolor="#337ab7"><span style="color: white">{{$r['cantidad']}}</span></td><td>{{$r['fecha']}}</td>
                            <td>
                                <?php
                                    echo $meses_provicion;
                                ?>
                            </td>
                            <td><?php echo $situac ?></td>
                        </tr>
                        @endforeach                                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="link-reporte"></div>
@include('frontend.includes.footer')
@endsection
