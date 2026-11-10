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
    }
    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333;
    }
    #global {
        height: 600px !important;
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

    .panel{
        margin-bottom:0px
    }

</style>

<script>
    $(document).ready(function () {
        $("#id_establecimiento").select2({
            width: 'resolve' // need to override the changed default
        });
        $("#id_consultorio").trigger('change');

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
    $(document).ready(function () {
        function armarTabla(r, i) {
            const farmaciasId = document.querySelectorAll('.farmacia_id');
            const ids = [...farmaciasId].map((farmacia) => {
                return (farmacia.innerHTML);
            });
            const total = r['stock_actual'];
            const prev1Total = total.replace('{', '');
            const prev2Total = prev1Total.replace('}', '');
            const arr = prev2Total.split(',');
            let total_fila = 0;
            let tableContent = `
                                    <tr>
                                    <td>${r['codigo_producto']}</a></td>
                                    <td>${r['nombre_producto']}</td>
                                    <!--td>${r['unidad']}</td-->`;
            const tds1 = ids.map((farm_id, ii) => {
                if(parseInt(farm_id)!=1 && parseInt(farm_id)!=2 && parseInt(farm_id)!=4 &&  parseInt(farm_id)!=11 && parseInt(farm_id)!=12 && parseInt(farm_id)!=13 &&  parseInt(farm_id)!=15 && parseInt(farm_id)!=20 && parseInt(farm_id)!=24 &&  parseInt(farm_id)!=27 && parseInt(farm_id)!=30 && parseInt(farm_id)!=35 && parseInt(farm_id)!=45 && parseInt(farm_id)!=46 && parseInt(farm_id)!=47 && parseInt(farm_id)!=52 && parseInt(farm_id)!=122 ) {
                    let cuenta = true;
                    let stock = 0;
                    for (i = 0; i < arr.length; i++) {
                        if(arr != null){
                            let arrF = arr[i].split('-');
                            if(parseInt(arrF[0]) == parseInt(farm_id)){
                                stock = parseInt(arrF[1]);
                                if(r['codigo_producto']=='PF00718'){
                                    stock = stock/1000;
                                }
                                total_fila = total_fila + parseInt(stock);
                            }
                        }
                    } 
                    return `<td>${stock}</td>`
                }
            });
            tds1.forEach((td1) => {
                tableContent = `${tableContent}${td1}`
            });
            tableContent = `${tableContent}<td>${total_fila}</td></tr>`
            const table = $('#finalTable');
            table.append(tableContent);
        }

        Swal.fire({
            title:
            "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando Stock por establecimiento</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });

        $.ajax({
            url: '/obtener_reporte_kardex_establecimiento_prueba',
            type: 'GET',
            dataType: 'json',
            data: {
                id_establecimiento: '{{$id_establecimiento}}',
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                /* const emptyResultDiv = document.querySelector('.stock-empty');
                const notEmptyResultDiv = document.querySelector('.stock-not-empty'); */
                console.log(response);
                Swal.fire({
                    icon: "success",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                    confirmButtonText: "ENTENDIDO",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: "ENTENDIDO",
                });
                /* if (response.length > 0) {
                    const tableCSS = document.querySelector('#example');
                    if (!tableCSS.classList.contains('active')) {
                        notEmptyResultDiv.classList.toggle('active');
                    }
                } else {
                emptyResultDiv.classList.toggle('active');
                } */
                response.forEach((row, i) => {
                    armarTabla(row, i);
                });
                $('#example').dataTable({
                    "language": {
                        "sProcessing":     "Procesando...",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "Ningún dato disponible en esta tabla",
                        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix":    "",
                        "sSearch":         "Filtrar:",
                        "sUrl":            "",
                        "sInfoThousands":  ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst":    "Primero",
                            "sLast":     "Último",
                            "sNext":     "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error:', textStatus, errorThrown);
                Swal.fire({
                    icon: "error",
                    title: "ERROR ",
                    text: "Servicio no disponible. intentelo en un minutos.",
                });
            },
            complete: function(jqXHR, textStatus) {
                Swal.hideLoading();
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
        /* $('#example').dataTable({
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Filtrar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        }); */
    });

    /* function exportarExcel() {

        var id_establecimiento = $('#id_establecimiento').val();
        location.href = 'exportar_stockFarmaciaByEstablecimiento/' + id_establecimiento+'';
    } */
    
    
</script>
<style>
</style>
@endsection
@section('content')

        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title" style="font-size: 13px !important">STOCK DE CENTRO DE ATENCIÓN POR ESTABLECIMIENTO</h3>
            </div>
            <div class="panel-body">
                <div hidden>
                    @foreach ($ff as $f)
                        <span class="farmacia_id">{{$f->id}}</span>
                    @endforeach
                </div>
                <div class="row col-sm-12">
                    <form id="frmStock" class="form-horizontal" method="post" action="stockFarmaciaByEstablecimiento">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-sm-5">
                            <label><small>IPRESS</small></label>
                            <select class="form-control" name="id_establecimiento" id="id_establecimiento" style="width: 100%">
                                <option value="0">--Seleccionar--</option>
                                <?php
                                foreach ($establecimientos as $d) {
                                    echo '<option value="' . $d->id . '"';
                                    if (isset($id_establecimiento)) {
                                        if ($id_establecimiento == $d->id)
                                            echo " selected";
                                    }
                                    echo '>' . $d->codigo . " " . $d->nombre . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <br/>
                            <input class="btn btn-info form-control" value="Buscar" type="submit">
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <br/>
                            <a id="btnExportarEnBaseFecha" class="btn btn-success form-control" href="/exportar_stockFarmaciaByEstablecimiento_prueba/{{$id_establecimiento}}"{{-- onclick="exportarExcel()" --}} >
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                            </a>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="responsive">
                    <div class="col-sm-12">
                        <br/>
                        <table id="example" class="table table-hover" style="font-size: 12px !important;">
                            <thead>
                                <tr class="filters">
                                    <th>Código</th>
                                    <th>Producto Farmacéutico o Dispositivo Médico</th>
                                    <!--th>Unidad</th-->
                                    <?php
                                    $ff = App\Models\Farmacia::GetFarmaciaByIdEstablecimiento($id_establecimiento);
                                    if (isset($ff)) {
                                        foreach ($ff as $f1) {

                                            if($f1->id!=1 and $f1->id!=2 and $f1->id!=4 and  $f1->id!=11 and $f1->id!=12 and $f1->id!=13 and  $f1->id!=15 and $f1->id!=20 and $f1->id!=24 and  $f1->id!=27 and $f1->id!=30 and $f1->id!=35 and $f1->id!=45 and $f1->id!=46 and $f1->id!=47 and $f1->id!=52 and $f1->id!=122){
                                            ?>
                                            <th><?php $name=explode("del",$f1->nombre); echo $name[0]; ?></th>
                                            <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="finalTable" style="font-size: 11px !important;">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
		</div>
    </div>

@include('frontend.includes.footer')
@endsection
