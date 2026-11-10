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

</style>

<script>
    $(document).ready(function () {
	
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
		
		
    });
	
	function __autocomplete__AjaxBusqueda(){
		
		$("#nombre_producto").autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
		$.ajax({
			url: '/obtener_producto/' + request.term,
			dataType: "json",
			success: function(data)  {
			response(data);
		  }
			
		})
		
		  },select:  function(e, ui) {
				var id_producto = ui.item.id;
				$('#id_producto').val(id_producto);
		  }
		});
		
		
	}
        
	function reporteKadexLote(){
		
        var id_producto = $('#id_producto').val();
		var fechaDesde = $('#fechaDesde').val();
		var fechaHasta = $('#fechaHasta').val();
        var id_user = $('#idUser').val();
        
		location.href='exportar_reporte_kardex_lote/'+fechaDesde+'/'+fechaHasta+'/'+id_producto+'/'+id_user;
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

    });
	
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                <span class="glyphicon glyphicon-th">
                                </span> B&uacute;squeda de Kardex
                            </a>
                        </h4>
                    </div>

                    <div class="panel-body">

                        <form id="frmReceta" class="form-horizontal" method="post" action="{{ route('listar_kardex')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="pull-right col-md-12" style="margin-bottom:20px; text-align: right">
                                <!--
    @if(Auth::user()->id_farmacia!=2 &&  Auth::user()->id_farmacia!=3 && Auth::user()->id_farmacia!=4)
    <a class="btn btn-default btn-xs" href="/stocksbyFamarcia/{{ Auth::user()->id }}"><i class="fa fa-medkit"></i> Ver Stock General</a>
    @endif
                                -->
                            </div>
                            <!--
    <div class="col-md-2" style="margin-bottom: 20px !important">
        <label>Código</label>
        <input class="form-control" value="">
    </div>
                            -->
                            <div class="col-md-4" style="margin-bottom: 20px !important">
                                <label>Producto Farmaceútico</label>
                                <input type="text" id="nombre_producto" name="nombre_producto" class="form-control" value="<?php echo $nombre_producto ?>">
                                <input type="hidden" id="id_producto" name="id_producto" value="<?php echo $id_producto ?>" placeholder="Seleccionar producto farmaceutico">
                            </div>


                            <div class="col-sm-3 col-md-2" style="margin-bottom: 20px !important">
                                <label>Fecha Desde</label>
                                <input type="hidden" name="idUser" id="idUser" value="<?php echo Auth::user()->id?>"/>
                                <input class="form-control datepicker" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10">
                            </div>

                            <div class="col-sm-3 col-md-2" style="margin-bottom: 20px !important">
                                <label>Fecha Hasta</label>
                                <input class="form-control datepicker" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10">
                            </div>

                            <div class="col-md-2">
                                <label></label>
                                <input class="form-control btn btn-info" value="Buscar" type="submit" id="btnReg">
                            </div>
                            <div class="col-md-12">
                                <a class="btn btn-success pull-right" href="javascript:void(0)" onclick="reporteKadexLote()" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                                </a>
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
                                                        
                                                        <tr style='font-size: 11px;'>
                                                            <th style="border:1px solid #999999">N.</th>
                                                            <th style="border:1px solid #999999">Fecha Movimiento</th>
                                                            <th style="border:1px solid #999999">Hora</th>
                                                            <th style="border:1px solid #999999">Tipo Documento</th>
                                                            <th style="border:1px solid #999999">Num Documento</th>
                                                            <th style="border:1px solid #999999">C&oacute;digo</th>
                                                            <th style="border:1px solid #999999">Producto</th>
                                                            <th style="border:1px solid #999999">Lote</th>
                                                            <th style="border:1px solid #999999">Fecha de Vencimiento</th>
                                                            <th style="border:1px solid #999999">Saldo Anterior</th>
                                                            <th style="border:1px solid #999999">Ingreso</th>
                                                            <th style="border:1px solid #999999">Salida</th>
                                                            <th style="border:1px solid #999999">Nuevo Saldo</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if($id_producto==11045 || $id_producto==15938 || $id_producto==14607 || $id_producto==14608){
                                                            $saldo = (isset($kardex_inicial->total)) ? $kardex_inicial->total/1000 : 0;
                                                        }
                                                        else{
                                                            $saldo = (isset($kardex_inicial->total)) ? $kardex_inicial->total : 0;
                                                        }
                                                        if (isset($kardex)):
                                                            
                                                            foreach ($kardex as $key => $rowKardex):
                                                                if($id_producto==11045 || $id_producto==15938 || $id_producto==14607 || $id_producto==14608){
                                                                    $cantidad_kardex = $rowKardex->cantidad/1000;
                                                                }
                                                                else{
                                                                    $cantidad_kardex =$rowKardex->cantidad;
                                                                }
                                                                //$rowKardex->cantidad
                                                                
                                                                ?>
                                                                <tr  style='font-size: 11px;'>
                                                                    <td align="center" class="con0"><?= ($key + 1) ?></td>	
                                                                    <td align="left" class="con0"><?= $rowKardex->fecha_movimiento ?></td>
                                                                    <td align="left" class="con0"><?php
                                                                        $originalDate3 = $rowKardex->created_at;
                                                                        $horaR = date("H:i:s", strtotime($originalDate3));
                                                                        echo $horaR;
                                                                        ?> 
                                                                    </td>              
                                                                    <td align="left" class="con0"><?= $rowKardex->tipo_movimiento ?></td>
                                                                    <td align="left" class="con0"><?= $rowKardex->numero_movimiento ?></td>
                                                                    <!--<td align="left" class="con0"></td>-->
                                                                    <td align="left" class="con1"><?= $rowKardex->codigo_producto ?></td>
                                                                    <td class="left" class="con0"><?= $rowKardex->nombre_producto ?></td>
                                                                    <td align="left" class="con0"><?= $rowKardex->lote ?></td>
                                                                    <td align="left" class="con0"><?= $rowKardex->fecha_vencimiento ?></td>

                                                                    <td class="con1" align="right"><?php echo $saldo ?></td>
                                                                    <?php
                                                                    if ($rowKardex->id_tipo_movimiento == 1 || $rowKardex->id_tipo_movimiento == 2 || $rowKardex->id_tipo_movimiento == 3 || $rowKardex->id_tipo_movimiento == 8 || $rowKardex->id_tipo_movimiento == 9):
                                                                        $saldo += $cantidad_kardex;
                                                                        
                                                                        ?>
                                                                        <td class="con1" align="right">
                                                                        @if($rowKardex->presentacion_comercial==1)
                                                                            @if($id_producto==11045 || $id_producto==15938 || $id_producto==14607 || $id_producto==14608)
                                                                                <?php echo $cantidad_kardex; ?>       
                                                                              @else
                                                                                <?php echo number_format($cantidad_kardex, 0, '.', ','); ?>							
                                                                              @endif
                                                                        @else
                                                                            <?php echo number_format($cantidad_kardex, 3, '.', ','); ?>
                                                                        @endif
                                                                        
                                                                        </td>
                                                                        <td class="con1" align="right"></td>
                                                                        
                                                                    <?php endif; ?>

                                                                    <?php
                                                                    if ($rowKardex->id_tipo_movimiento == 4 || $rowKardex->id_tipo_movimiento == 5 || $rowKardex->id_tipo_movimiento == 6 || $rowKardex->id_tipo_movimiento == 7):
                                                                        $saldo -= $cantidad_kardex;
                                                                        ?>
                                                                        <td class="con1" align="right"></td>
                                                                        <td class="con1" align="right">
                                                                            @if($rowKardex->presentacion_comercial==1)
                                                                              @if($id_producto==11045 || $id_producto==15938 || $id_producto==14607 || $id_producto==14608)
                                                                                <?php echo $cantidad_kardex; ?>       
                                                                              @else
                                                                                <?php echo number_format($cantidad_kardex, 0, '.', ','); ?>							
                                                                              @endif
                                                                            @else
                                                                              <?php echo number_format($cantidad_kardex, 3, '.', ','); ?>
                                                                            @endif
                                                                        </td>
                                                                    <?php endif; ?>

                                                                    <td class="con1" align="right">
                                                                        @if($rowKardex->presentacion_comercial==1)
                                                                            @if($id_producto==11045 || $id_producto==15938 || $id_producto==14607 || $id_producto==14608)
                                                                                <?php echo $saldo; ?>       
                                                                            @else
                                                                                <?php echo number_format($saldo, 0, '.', ','); ?>							
                                                                            @endif
                                                                        @else
                                                                            <?php echo number_format($saldo, 3, '.', ','); ?>
                                                                        @endif				
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <?php /* if(count($lotes)>0):?>
                                              <table class="table">
                                              <thead>
                                              <tr>
                                              <th>Código - Producto Farmaceútico</th>
                                              <th>Unidad</th>
                                              <th>Reg. Sanitario</th>
                                              <th>Lote</th>
                                              <th>Fec. Venc.<br><span style="font-size:10px">(mm/aaaa)</span></th>
                                              <th>Fecha de Creación</th>
                                              <th>Cantidad</th>
                                              @permission('direoss_saludpol')
                                              @endauth
                                              <!--@role('Almacenero_recetas')
                                              <th>Editar</th>
                                              @endauth-->
                                              </tr>
                                              </thead>
                                              <tbody>
                                              <?php $c = 0; ?>
                                              @foreach($lotes as $l)
                                              <tr>
                                              <td>{{$l->codigo_producto}} - {{$l->prod_farmaceutico}}</td>
                                              <td>{{$l->abreviatura}}</td>
                                              <td>{{$l->registro_sanitario}}</td>
                                              <td>{{$l->lote}}</td>
                                              <td>
                                              <?php
                                              $originalDate2 = $l->fecha_vencimiento;
                                              //$fecha = date("m-Y", strtotime($originalDate2));
                                              //echo $fecha;
                                              echo $originalDate2;
                                              ?>
                                              </td>
                                              <td>
                                              <?php
                                              $originalDate5 = $l->created_at;
                                              $fecha_creacion = date("d-m-Y (H:i:s)", strtotime($originalDate5));
                                              echo $fecha_creacion;
                                              ?>
                                              </td>
                                              <td style="text-align:center">
                                              {{$l->cantidad}}
                                              <?php $c = $c + $l->cantidad; ?>
                                              </td>


                                              </tr>
                                              @endforeach
                                              </tbody>
                                              </table>
                                              <div class="col-md-11" style="text-align: right; margin-top: 15px"><label>Total</label></div>
                                              <div class="col-md-1" style="text-align: right; margin-top: 15px">
                                              <input class="form-control" value="<?php echo $c; ?>">
                                              </div>
                                              <?php else: */ ?>
                                            <!--<div class="col-md-12">
                                                <label>Es necesario lotizar el  medicamento selecionado.</label>                                                
                                            </div>
                                            -->
                                            <?php //endif; ?>
                                        </div>
                                    </div>
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
