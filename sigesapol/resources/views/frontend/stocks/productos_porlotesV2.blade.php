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
	.panel{
		margin-bottom:0px
	}

    .counts-loading {
    display: inline-block;
    width: 12px;
    height: 12px;
    }

    .counts-loading > img {
        width: 100%;
        height: 100%;
    }
    
    .imageloader {
        display: none;
    }

    .imageloader.active {
        display: block;
    }

    .btn-exportar-not-empty, .btn-exportar-empty {
        display: none;
    }

    .btn-exportar-not-empty.active, .btn-exportar-empty.active {
        display: inline-block;
    }
    
    .stock-not-empty, .stock-empty {
        display: none;
    }

    .stock-not-empty.active, .stock-empty.active {
        display: inline-block;
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
    $(document).ready(function () {
        const exportar = document.getElementById('btnExportarEnBaseFecha');
        const fechaCargada = $('#fechaCorte').val();
        const urlObj = new URL(exportar.href);
        const path = urlObj.pathname;
        const nuevoPath = path.concat(fechaCargada);
        urlObj.pathname = nuevoPath;
        exportar.href = urlObj.href;

        const inputFecha = document.querySelector('#fechaCorte');
        inputFecha.addEventListener('input', (e) => {
            console.log('object');
        });

        const btnReporte = document.getElementById('btnReporte');

        function armarTabla(r, i) {
            const tableContent = `
                                        <tr>
                                            <td>${++i}</td>
                                            <td>${r['codigo_producto']}</a></td>
                                            <td>${r['prod_farmaceutico']}</td>                            
                                            <td>${r['abreviatura']}</td>
                                            <td>${r['nombre_tipo']}</td>
                                            <td>${r['lote']}</td>
                                            <td>${r['registro_sanitario']}</td>
                                            <td>${r['fecha_vencimiento']}</td>
                                            <td>${r['cantidad']}</td>
                                            <td>${r['precio_unitario']}</td>
                                            <td>${parseInt(r['precio_unitario'])*parseInt(r['cantidad'])}</td>
                                        </tr>`;
            const table = $('#finalTable');
            table.append(tableContent);
        }

        btnReporte.addEventListener('click', (e) => {
            $("#example").dataTable().fnDestroy();
            const table = document.getElementById('finalTable');
            table.innerHTML = '';
            Swal.fire({
        title:
          "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando Stock</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
          Swal.showLoading();
        },
  });
            const inputFecha = $('#fechaCorte').val();
            $.ajax({
          url: '/obtener_reporte_kardex_lotes',
          type: 'GET',
          dataType: 'json',
          data: {
            fechaCorte: inputFecha,
              _token: '{{ csrf_token() }}',
          },
          success: function(response) {
            const emptyResultDiv = document.querySelector('.stock-empty');
            const notEmptyResultDiv = document.querySelector('.stock-not-empty');
            console.log(response);
            Swal.fire({
                icon: "success",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                confirmButtonText: "ENTENDIDO",
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "ENTENDIDO",
            });
            if (response.length > 0) {
                const tableCSS = document.querySelector('#example');
                if (!tableCSS.classList.contains('active')) {
                    notEmptyResultDiv.classList.toggle('active');
                }
            } else {
              emptyResultDiv.classList.toggle('active');
            }
            response.forEach((row, i) => {
              armarTabla(row, i);
            });
            $('#example').dataTable( {
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
        } );
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
        });
        $('#fechaCorte').datepicker({
            format: 'dd-mm-yyyy'
        }).on('changeDate', function(e) {
                const exportar = document.getElementById('btnExportarEnBaseFecha');
                const urlObj = new URL(exportar.href);
                const path = urlObj.pathname;
                const nuevoPath = [path.split('/')[0], path.split('/')[1]].join('').concat(`/{{$id_farmacia}}/${e.delegateTarget.value}`);
                urlObj.pathname = nuevoPath;
                exportar.href = urlObj.href;
            });;
  Swal.fire({
        title:
          "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando Stock por tipo de producto</span></div>",
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
    url: '/obtener_cantidades_para_leyenda_stock',
    type: 'GET',
    dataType: 'json',
    data: {
        _token: '{{ csrf_token() }}',
    },
    success: function(response) {
        const allLoadersGif = document.querySelectorAll('.imageloader');
        allLoadersGif.forEach((loader) => {
          loader.classList.remove('active');
          const spanForRenderCountRes = loader.parentNode;
          spanForRenderCountRes.innerHTML = `(${response[spanForRenderCountRes.id]})`;
        });
        Swal.fire({
                icon: "success",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                confirmButtonText: "ENTENDIDO",
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "ENTENDIDO",
        });
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log('Error:', textStatus, errorThrown);
        console.log(jqXHR);
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
                        link.download = 'Reporte de Productos Por Lotes'; // Puedes establecer el nombre del archivo aquí si lo deseas
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
<div class="container">
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                {{$farma->nombre}}  
            </div>
			
			<?php
			
			$pagRoute = "/stocksbyFamarcias/".$id_farmacia;
			/*
			if($farmaciaActual == 1):
				$pagRoute = "/stocksbyFamarcia/".Auth::user()->id;
			endif;
			
			if($farmaciaActual == 2):
				$pagRoute = "/stocksbyFamarcias/".$id_farmacia;
			endif;
			*/
			?>
			
            <div class="panel-body">   
                <div class="col-md-12">
                    <b>Leyenda:</b>
                </div>
				
				<div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Stock Local del Centro de Atenci&oacute;n</em><br>">
                    <i class="fa fa-medkit" style="color:#000000"></i> Stock en General
                </div>
				<div class="col-md-2" style="padding-left:18px">
                    <a href="<?php echo $pagRoute?>" class="btn btn-default btn-xs">ver</a>
                </div>
				
				
				<div class="row">
				
				<div class="col-md-11 col-md-offset-1">
				
				<div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                    <i class="fa fa-flag" style="color:#FF0000"></i> Desabastecido
                </div>
                <div class="col-md-2">
                    <a href="<?php echo $pagRoute?>/1" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                    <i class="fa fa-flag" style="color:#ED7D31"></i> SobreStock
                </div>
                <div class="col-md-2">
                     <a href="<?php echo $pagRoute?>/2" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                    <i class="fa fa-flag" style="color:#70AD47"></i> NormoStock
                </div>
                <div class="col-md-2">
                    <a href="<?php echo $pagRoute?>/3" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                    <i class="fa fa-flag" style="color:#FFC000"></i> SubStock
                </div>
                <div class="col-md-2">
                    <a href="<?php echo $pagRoute?>/4" class="btn btn-default btn-xs">ver</a>
                </div>
				<div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                    <i class="fa fa-flag" style="color:#4472C4"></i> Sin Rotaci&oacute;n
                </div>
                <div class="col-md-2">
                    <a href="<?php echo $pagRoute?>/5" class="btn btn-default btn-xs">ver</a>
                </div>
				
				</div>
				
				</div>
				
				
				
                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Stock por lotes</em>">
                    <i class="fa fa-medkit" style="color: orange"></i> Stock por Lotes
                </div>
                <div class="col-md-2" style="padding-left:18px">
                    <a href="/productos_porlotes/" class="btn btn-default btn-xs">ver</a>
                </div>
				
            </div>
			
			
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                Clasificación de Stock por Tipo de Producto 
            </div>            
            <div class="panel-body" style="font-size:11px">
                <div class="col-md-12">
                  <b>Leyenda:</b>
                </div>
                <div class="col-md-10">
                  <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Medicamentos <span class="counts-loading active" id="countMedicamento"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span>
                </div>
                <div class="col-md-2">
                  <a href="/productos_por_tipo/{{ $id_farmacia }}/1" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                  <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Biomédicos <span class="counts-loading active" id="countbiomedico"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span>
                </div>
                <div class="col-md-2">
                  <a href="/productos_por_tipo/{{ $id_farmacia }}/3" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                  <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Material Dental <span class="counts-loading active" id="countMaterialDental"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span>
                </div>
                <div class="col-md-2">
                  <a href="/productos_por_tipo/{{ $id_farmacia }}/5" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                  <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Material de Laboratorio
                  <span class="counts-loading active" id="countMaterialLaboratorio"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span>
                </div>
                <div class="col-md-2">
                  <a href="/productos_por_tipo/{{ $id_farmacia }}/4" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                  <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Insumos de Laboratorio
                  <span class="counts-loading active" id="countinsumolaboratorio"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span>
                </div>
                <div class="col-md-2">
                  <a href="/productos_por_tipo/{{ $id_farmacia }}/2" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                  <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Mat. Fotográfico y Fonot.
                  <span class="counts-loading active" id="countMatFotograficoAndFonotecnico"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span>
                </div>
                <div class="col-md-2">
                  <a href="/productos_por_tipo/{{ $id_farmacia }}/6" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="loader-count col-md-10">
                  <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Mat. Quirurgico
                  <span class="counts-loading active" id="countMaterialQuirurgico"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span>
                </div>
                <div class="loader-count col-md-2">
                  <a href="/productos_por_tipo/{{ $id_farmacia }}/7" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="loader-count col-md-10">
                  <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Productos Afines <span class="counts-loading active" id="countProductosAfines"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span>
                </div>
                <div class="loader-count col-md-2">
                  <a href="/productos_por_tipo/{{ $id_farmacia }}/8" class="btn btn-default btn-xs">ver</a>
                </div>
              </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                Alertas de Vencimiento 
            </div>            
            <div class="panel-body" style="font-size:11px">
                <div class="col-md-12">
                  <b>Leyenda:</b>
                </div>
                <div class="loader-count col-md-10">
                  <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Prod. próximos a vencer (En los Proximos 06 meses)
                  <span class="counts-loading active" id="cantidad_proximos_vencer"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span>
                </div>
                <div class="col-md-2">
                  <a href="/productos_proximos_vencer/{{ $id_farmacia }}/6" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="loader-count col-md-10" style="margin-top: 5px">
                  <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Productos vencidos
                  <span class="counts-loading active" id="cantidad_productos_vencidos"><img class="imageloader active" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt=""></span>
                </div>
                <div class="col-md-2" style="margin-top: 5px">
                  <a href="/productos_vencidos/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                </div>
              </div>
        </div>
    </div>
    <div class="row col-md-9">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">STOCK LOCAL DE CENTRO DE ATENCIÓN - STOCK POR LOTES</h3>
                <div class="pull-right">
                    {{-- AGREGAR FECHA A ESTE HREF (EN BASE AL CAMBIO DE INPUT FECHA Y POR DEFAULT CARGA LA FECHA ACTUAL) --}}
                    <a class="btn btn-success btn-xs" id="btnExportarEnBaseFecha" href="/imprimir_productos_porlotes/{{ $id_farmacia }}/"><i class="fa fa-file-excel-o"></i> Exportar</a> 
                    <a class="btn btn-default btn-xs" href="/stocksbyFamarcias/{{$id_farmacia}}">
                        <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                    </a>
                </div>
            </div>
            <div class="panel-body">         
                <div class="panel-body">
                    <div class="col-sm-12">
                        <form id="frm_transferencia" class="form-horizontal" method="post" action="{{ route('productos_porlotes')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div clas="row">       
                                <div class="row">          
                                    <div class="col-md-3">
                                        <label class="help-block">Fecha Corte</label>
                                        <input class="form-control" type="text" name="fechaCorte" id="fechaCorte" placeholder="Fecha Hasta" value="<?php echo $fecha ?>" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10">
                                    </div>
                                    <div class="col-md-3">
                                        <br/><br/>
                                        <input class="form-control btn btn-info input-sm" value="Buscar Stock" type="button" id="btnReporte" title="Recuerde que puede generar el reporte EXCEL, dando click en el boton 'EXPORTAR'">
                                    </div>
                                </div>
                            </div>                    
                            <div class="row">                        
                            </div>
                        </form>
                    </div>
                </div>
            </div>            
            <div id="global">
                <br/>
                <table id="example" class="table stock-not-empty" style="font-size: 11px !important;" >
                    <thead>
                        <tr class="filters">
                            <th>Nro</th>
                            <th>Código</th>
                            <th>Producto Farmacéutico o Dispositivo Médico</th>                            
                            <th>Unidad</th>
                            <th>Tipo</th>
                            <th>Lote</th>
                            <th>R.S.</th>
                            <th>Fec. Vencimiento <br> (mm-aaaa)</th>
                            <th>Stock Actual</th>
                            <th>Precio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="finalTable" style="font-size: 11px !important;">
                    </tbody>
                </table>
                <div class="stock-empty" class="col-sm-12 col-md-12" style="margin-top:15px">
                    <div class="alert alert-warning">
                        <span class="glyphicon glyphicon-info-sign"></span> <strong>Aviso</strong>
                        <hr class="message-inner-separator">
                        <p>No existe registros de productos proximos a vencer...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
