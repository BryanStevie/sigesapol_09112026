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

  .margin {

    margin-bottom: 20px;
  }

  .margin-buscar {
    margin-bottom: 5px;
    margin-top: 5px;
  }

  .panel {
    margin-bottom: 0px
  }
</style>

<script>
  $(document).ready(function() {
  $("#id_consultorio").select2({
    width: 'resolve' // need to override the changed default
  });
  $("#id_consultorio").trigger('change');

  $('.filterable .btn-filter').click(function() {
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

  $('.filterable .filters input').keyup(function(e) {
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
    var $filteredRows = $rows.filter(function() {
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
      $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find(
        '.filters th').length + '">No se encuentra el laboratorio</td></tr>'));
    }
  });
});
$(document).ready(function() {
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
});
</script>
<style>
</style>
@endsection
@section('content')
<div class="container">
  <div class="col-md-3">
    <div class="panel panel-info">
      <div class="panel-heading">
        {{$farma->nombre}}
      </div>
      <?php
            if ($farmaciaActual == 1):
                $pagRoute = "/stocksbyFamarcia/" . Auth::user()->id;
            endif;

            if ($farmaciaActual == 2):
                $pagRoute = "/stocksbyFamarcias/" . $id_farmacia;
            endif;
            ?>

      <div class="panel-body">

        <!--<div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <strong><i class="fa fa-warning"></i> Alerta!</strong> <marquee SCROLLAMOUNT=2 direction="up" Behavior = slide><p style="font-family: arial; font-size: 10pt">Existen Productos que pronto van agotarse</p></marquee>
                </div>-->
        <div class="col-md-12">
          <b>Leyenda:</b>
        </div>
        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
          title="<em>Stock Local del Centro de Atenci&oacute;n</em><br>">
          <i class="fa fa-medkit" style="color:#000000"></i> Stock en General
        </div>
        <div class="col-md-2" style="padding-left:18px">
          <a href="<?php echo $pagRoute ?>" class="btn btn-default btn-xs">ver</a>
        </div>
        <div class="row">
          <div class="col-md-11 col-md-offset-1">
            <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
              title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
              <i class="fa fa-flag" style="color:#FF0000"></i> Desabastecido
            </div>
            <div class="col-md-2">
              <a href="<?php echo $pagRoute ?>/1" class="btn btn-default btn-xs">ver</a>
            </div>
            <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
              title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
              <i class="fa fa-flag" style="color:#ED7D31"></i> SobreStock
            </div>
            <div class="col-md-2">
              <a href="<?php echo $pagRoute ?>/2" class="btn btn-default btn-xs">ver</a>
            </div>
            <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
              title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
              <i class="fa fa-flag" style="color:#70AD47"></i> NormoStock
            </div>
            <div class="col-md-2">
              <a href="<?php echo $pagRoute ?>/3" class="btn btn-default btn-xs">ver</a>
            </div>
            <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
              title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
              <i class="fa fa-flag" style="color:#FFC000"></i> SubStock
            </div>
            <div class="col-md-2">
              <a href="<?php echo $pagRoute ?>/4" class="btn btn-default btn-xs">ver</a>
            </div>
            <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
              title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
              <i class="fa fa-flag" style="color:#4472C4"></i> Sin Rotaci&oacute;n
            </div>
            <div class="col-md-2">
              <a href="<?php echo $pagRoute ?>/5" class="btn btn-default btn-xs">ver</a>
            </div>
          </div>
        </div>
        <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true"
          title="<em>Stock por lotes</em>">
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
          <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Medicamentos ({{$countMedicamento}})
        </div>
        <div class="col-md-2">
          <a href="/productos_por_tipo/{{ $id_farmacia }}/1" class="btn btn-default btn-xs">ver</a>
        </div>
        <div class="col-md-10">
          <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Biomédicos ({{$countbiomedico}})
        </div>
        <div class="col-md-2">
          <a href="/productos_por_tipo/{{ $id_farmacia }}/3" class="btn btn-default btn-xs">ver</a>
        </div>
        <div class="col-md-10">
          <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Material Dental ({{$countMaterialDental}})
        </div>
        <div class="col-md-2">
          <a href="/productos_por_tipo/{{ $id_farmacia }}/5" class="btn btn-default btn-xs">ver</a>
        </div>
        <div class="col-md-10">
          <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Material de Laboratorio
          ({{$countMaterialLaboratorio}})
        </div>
        <div class="col-md-2">
          <a href="/productos_por_tipo/{{ $id_farmacia }}/4" class="btn btn-default btn-xs">ver</a>
        </div>
        <div class="col-md-10">
          <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Insumos de Laboratorio
          ({{$countinsumolaboratorio}})
        </div>
        <div class="col-md-2">
          <a href="/productos_por_tipo/{{ $id_farmacia }}/2" class="btn btn-default btn-xs">ver</a>
        </div>
        <div class="col-md-10">
          <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Mat. Fotográfico y Fonot.
          ({{$countMatFotograficoAndFonotecnico}})
        </div>
        <div class="col-md-2">
          <a href="/productos_por_tipo/{{ $id_farmacia }}/6" class="btn btn-default btn-xs">ver</a>
        </div>
        <div class="col-md-10">
          <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Mat. Quirurgico
          ({{$countMaterialQuirurgico }})
        </div>
        <div class="col-md-2">
          <a href="/productos_por_tipo/{{ $id_farmacia }}/7" class="btn btn-default btn-xs">ver</a>
        </div>
        <div class="col-md-10">
          <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Productos Afines ({{$countProductosAfines}})
        </div>
        <div class="col-md-2">
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
        <div class="col-md-10">
          <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Prod. próximos a vencer
          ({{$count_proximos_vencer}})
          <br> <span style="font-size:9px">(En los Proximos 06 meses)</span>
        </div>
        <div class="col-md-2">
          <a href="/productos_proximos_vencer/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
        </div>
        <div class="col-md-10" style="margin-top: 5px">
          <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Productos vencidos
          ({{$count_productos_vencidos}})
        </div>
        <div class="col-md-2" style="margin-top: 5px">
          <a href="/productos_vencidos/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
        </div>
      </div>
    </div>
  </div>
  <!--<h4>
        Stocks - {{$farma->nombre}}         
        <span class="pull-right">Hoy: {{$fecha}}</span>
    </h4>-->
  <div class="row col-md-9">
    <div class="panel panel-primary filterable">
      <div class="panel-heading">
        <h3 class="panel-title">
          <!--PROD. FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS-->
          STOCK LOCAL DE CENTRO DE ATENCIÓN
          <?php 
                    if ($situacion == "1"){
                        echo " - DESABASTECIDO";
                    } else if ($situacion == "2"){
                        echo " - SOBRESTOCK";
                    } else if ($situacion == "3"){
                        echo " - NORMOSTOCK";
                    } else if ($situacion == "4"){
                        echo " - SUBSTOCK";
                    } else if ($situacion == "5"){
                        echo " - SIN ROTACIÓN";
                    } 
                    ?>
        </h3>
        <div class="pull-right">
          <!--if(count($stocks)>0)-->
          @if($establecimiento->nivel==0)
            @if($almacen==1)
              @if(count($resultado)>0)
                <?php if (isset($farmaciaActual) && $farmaciaActual == 2): ?>
                <a class="btn btn-success btn-xs" href="/imprimir_stock_byidfarmacia/{{ $id_farmacia }}/{{ $situacion }}"><i
                  class="fa fa-file-excel-o"></i> Exportar</a>
                <?php else: ?>
                <a class="btn btn-success btn-xs" href="/imprimir_stock_byiduser/{{ Auth::user()->id }}/{{ $situacion }}"><i
                  class="fa fa-file-excel-o"></i> Exportar </a>
                <?php endif; ?>
              @endif
              @if(count($resultado)==0)
                <a class="btn btn-default btn-xs"
                  href="/from_registrar_cabecera_carga_inicial/{{ Auth::user()->id_farmacia }}"><i class="fa fa-plus"></i>
                  Agregar Stock Inicial</a>
                @if($estado_carga==0)
                  <a class="btn btn-default btn-xs" href="/formulario_importar_stock/{{ Auth::user()->id }}"><i
                    class="fa fa-list"></i> Importar Stock Inicial</a>
                @endif
                <a class="btn btn-default btn-xs" href="/formato_importar_stock/{{ Auth::user()->id }}"><i
                  class="fa fa-download"></i> Formato-importar</a>
              @endif
            @else
              <a href="#" class="btn btn-danger btn-xs"> {{$establecimiento->nombre}} no es una IPRESS</a>
            @endif
          @else
            @if(count($resultado)>0)
              <?php if (isset($farmaciaActual) && $farmaciaActual == 2): ?>
              <a class="btn btn-success btn-xs" href="/imprimir_stock_byidfarmacia/{{ $id_farmacia }}/{{ $situacion }}"><i
                class="fa fa-file-excel-o"></i> Exportar</a>
              <?php else: ?>
              <a class="btn btn-success btn-xs" href="/imprimir_stock_byiduser/{{ Auth::user()->id }}/{{ $situacion }}"><i
                class="fa fa-file-excel-o"></i> Exportar </a>
              <?php endif; ?>
            @endif
            @if(count($resultado)==0)
              <a class="btn btn-default btn-xs"
                href="/from_registrar_cabecera_carga_inicial/{{ Auth::user()->id_farmacia }}"><i class="fa fa-plus"></i>
                Agregar Stock Inicial</a>
              @if($estado_carga==0)
                <a class="btn btn-default btn-xs" href="/formulario_importar_stock/{{ Auth::user()->id }}"><i
                  class="fa fa-list"></i> Importar Stock Inicial</a>
              @endif
              <a class="btn btn-default btn-xs" href="/formato_importar_stock/{{ Auth::user()->id }}"><i
                class="fa fa-download"></i> Formato-importar</a>
            @endif
          @endif
          <?php if ($situacion <> "0"){ ?>
          <a class="btn btn-default btn-xs" href="/stocksbyFamarcias/{{$id_farmacia}}">
            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
          </a>
          <?php } ?>
        </div>
      </div>
      <div id="global" style="background:#fff !important">
        <br />
        <table id="example" class="table margenes" style="font-size: 11px !important;">
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
          <tbody style="font-size: 11px !important;">
            <?php //print_r($resultado); ?>
            {{-- */$x=0;/* --}}
            @foreach($resultado as $r)


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
                                }
                            }
                        }

                        if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                            $meses_provicion = 0;
                        } else {
                            $provision = round(($r['cantidad'] / $cpma), 1);
                            $meses_provicion = $provision;
                        }

                        /*                         * *********************** */
                        $color = "";
                        $situacionTemp = "";
                        if ($r['cantidad'] <= 0):
                            $color = "#FF0000"; //rojo
                            $situacionTemp = 1;
                        else:
                            if ($cpma <= 0):
                                $color = "#4472C4"; //Sin Rotacion Azul
                                $situacionTemp = 5;
                            endif;
                            if ($meses_provicion >= 0 && $meses_provicion < 2):
                                $color = "#FFC000"; //SubStock amarillo
                                $situacionTemp = 4;
                            endif;
                            if ($meses_provicion >= 2 && $meses_provicion <= 6):
                                $color = "#70AD47"; //NormoStock verde
                                $situacionTemp = 3;
                            endif;
                            if ($meses_provicion > 6):
                                $color = "#ED7D31"; //SobreStock anaranjado
                                $situacionTemp = 2;
                            endif;

                        endif;
                        ?>

            <?php if ($situacion == 0): ?>
            {{-- */$x++;/* --}}
            <tr>
              <td>{{$x}}</td>
              <td>{{$r['codigo']}}</td>
              <td>{{$r['nombre']}} </td>
              <td>{{$r['unidad']}}</td>
              <td>
                <?php if($r['id_producto']==11045):
                        echo ($r['cantidad'])/1000;
                      else:
                        if($r['cantidad']<0): 
                          echo '0'; 
                        else: 
                          echo $r['cantidad'];
                        endif;
                      endif;
                ?>
              </td>
              <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                <?php //echo $r['egresos']."|".$r['recetas_devueltas']."|".$cpma;  ?>
                <i class="fa fa-flag fa-2x" style="color:<?php echo $color ?>;position:relative">
                </i>
                <div style="color:<?php echo $color ?>;float:right;margin-right:0%;font-size:11px">
                  {{ $meses_provicion }} meses</div>
              </td>
              <td>
                <a href="/asignar_lote_vencimientos/{{$r['id_producto']}}/{{$id_farmacia}}" class="btn btn-primary btn-xs">
                  <i class="fa fa-edit"></i>
                </a>
              </td>
            </tr>

            <?php elseif ($situacion > 0 && $situacion == $situacionTemp): ?>
            {{-- */$x++;/* --}}
            <tr>
              <td>{{$x}}</td>
              <td>{{$r['codigo']}}</td>
              <td>{{$r['nombre']}} </td>
              <td>{{$r['unidad']}}</td>
              <td>
                <?php if($r['id_producto']==11045):
                        echo ($r['cantidad'])/1000;
                      else:
                        if($r['cantidad']<0): 
                          echo '0'; 
                        else: 
                          echo $r['cantidad'];
                        endif;
                      endif;
                ?>
              </td>
              <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                <i class="fa fa-flag fa-2x" style="color:<?php echo $color ?>;position:relative">
                </i>
                <div style="color:<?php echo $color ?>;float:right;margin-right:0%;font-size:11px">
                  {{ $meses_provicion }} meses</div>
              </td>
              <td>
                <a href="/asignar_lote_vencimientos/{{$r['id_producto']}}/{{$id_farmacia}}" class="btn btn-primary btn-xs">
                  <i class="fa fa-edit"></i>
                </a>
              </td>
            </tr>
            <?php endif; ?>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection