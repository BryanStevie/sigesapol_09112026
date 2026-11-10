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
        height: 620px !important;
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

  function roundToDecimals(num, decimals) {
    const factor = Math.pow(10, decimals);
    return Math.round(num * factor) / factor;
  }

  function armarTabla(r, i, situacion) {
    let cpm = "";
	let cpma = "";
	let meses_provicion = "";
							  
	if(parseInt(r['total']) > 0 && (parseInt(r['egresos']) - parseInt(r['recetas_devueltas']))==0){
		cpma = -1;
	} else {
        if (r['nro_mes'] == 0) {
            cpma = -1;
        } else {                                        
            if((parseInt(r['egresos']) - parseInt(r['recetas_devueltas']))==0){
                cpma = 0;
            } else {
                cpma = (parseInt(r['egresos']) - parseInt(r['recetas_devueltas'])) / parseInt(r['nro_mes']);
                console.log(cpma);
            }
        }
    }
    if(parseInt((r['egresos']) - parseInt(r['recetas_devueltas'])) == 0){
        meses_provicion = 0;
    } else {
        meses_provicion = roundToDecimals((parseInt(r['total']) / cpma), 0);
    }
        
    let color="";
    let situacionTemp="";
    if(r['total'] == 0) {
        color="#FF0000";
        situacionTemp=1;
    } else {
        if(cpma <= 0) {
            color="#4472C4";//Sin Rotacion Azul
            situacionTemp=5;
        }
        if(meses_provicion >= 0 && meses_provicion < 2) {
            color="#FFC000";//SubStock amarillo
            situacionTemp=4;
        }
        if(meses_provicion >= 2 && meses_provicion <=6) {
            color="#70AD47";//NormoStock verde
            situacionTemp=3;
        }
        if(meses_provicion > 6) {
            color="#ED7D31";//SobreStock anaranjado
            situacionTemp=2;
        }
    }

    const tableContent = `
    <tr>
        <td>${++i}</td>
        <td>${r['codigo_producto']}</a></td>
        <td>${r['nombre_producto']}</td>
        <td>${r['unidad']}</td>
        <td>${r['total']}</td>
        <td align="center" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
            <i class="fa fa-flag fa-2x" style="color:${color};position:relative">
            </i>
            <div style="color:${color};float:right;margin-right:10%;font-size:11px">${meses_provicion} meses</div>
        </td>
        <td>
            <a href="/asignar_lote_vencimientos/${r['id_producto']}/{{$id_farmacia}}" class="btn btn-primary btn-xs">
                <i class="fa fa-edit"></i>
            </a>
        </td>
    </tr>
    `;
    const table = $('#finalTable');
    table.append(tableContent);
  }
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
  $.ajax({
    url: '/obtener_cantidades_para_leyenda_stock',
    type: 'GET',
    dataType: 'json',
    data: {
        _token: '{{ csrf_token() }}',
    },
    success: function(response) {
        const allLoadersGif = document.querySelectorAll('.imageloader');
        const emptyResultDivButtons = document.querySelector('.btn-exportar-empty');
        const notEmptyResultDivButtons = document.querySelector('.btn-exportar-not-empty');
        allLoadersGif.forEach((loader) => {
          loader.classList.remove('active');
          const spanForRenderCountRes = loader.parentNode;
          spanForRenderCountRes.innerHTML = `(${response[spanForRenderCountRes.id]})`;
        });
        $.ajax({
          url: '/obtener_kardexs_para_stock/{{$tipo}}',
          type: 'GET',
          dataType: 'json',
          data: {
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
            if (response.length >0) {
                $('#example').dataTable({
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
                });
            }
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
                        link.download = 'Reporte de Stock'; // Puedes establecer el nombre del archivo aquí si lo deseas
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
        /* $('#example').dataTable( {
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
        } ); */
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
                  <a href="/productos_proximos_vencer/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
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
                <h3 class="panel-title">STOCK LOCAL DE CENTRO DE ATENCIÓN - {{$nombre}}</h3>
                <div class="pull-right">
                    <a id="btnExportarEnBaseFecha" class="btn btn-success btn-xs" href="/imprimir_stock_tipo_producto/{{ $id_farmacia }}/{{$tipo}}"><i class="fa fa-file-excel-o"></i> Exportar</a> 
                    <a class="btn btn-default btn-xs" href="/stocksbyFamarcias/{{$id_farmacia}}">
                        <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                    </a>
                </div>
            </div>
            <div id="global">
                <br/>
                <table id="example" class="table stock-not-empty"style="font-size: 11px !important;" >
                    <thead>
                        <tr class="filters">
                            <th>Nro</th>
                            <th>Código</th>
                            <th>Producto Farmacéutico o Dispositivo Médico</th>
                            <th>Unidad</th>
                            <th>Stock Actual</th>
                            <th>Situación</th>
                            <th>Ver Lotes</th>  
                        </tr>
                    </thead>
                    <tbody id="finalTable" style="font-size: 11px !important;">
                    </tbody>
                </table>
                <div class="stock-empty col-sm-12 col-md-12" style="margin-top:15px">
                    <div class="alert alert-warning">
                        <span class="glyphicon glyphicon-info-sign"></span> <strong>Aviso</strong>
                        <hr class="message-inner-separator">
                        <p>No existe registros de este tipo de producto...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
