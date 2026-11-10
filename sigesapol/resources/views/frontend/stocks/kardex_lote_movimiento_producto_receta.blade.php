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
        
	function reporteKardexProducto(){
		
        var id_producto = $('#id_producto').val();
		var fechaDesde = $('#fechaDesde').val();
		var fechaHasta = $('#fechaHasta').val();
        var id_user = $('#idUser').val();
        
		location.href='exportar_reporte_movimiento_producto_receta/'+fechaDesde+'/'+fechaHasta+'/'+id_producto+'/'+id_user;
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
                                </span> B&uacute;squeda de Producto por Receta
                            </a>
                        </h4>
                    </div>

                    <div class="panel-body">

                        <form id="frmReceta" class="form-horizontal" method="post" action="{{ route('listar_kardex_lote_movimiento_producto_receta')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="pull-right col-md-12" style="margin-bottom:20px; text-align: left">
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
                                <a class="btn btn-success pull-right" href="javascript:void(0)" onclick="reporteKardexProducto()" >
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
                                                            <th style="border:1px solid #999999">Fecha Registro</th>
                                                            <th style="border:1px solid #999999">Fecha Expedición</th>
                                                            <th style="border:1px solid #999999">Fecha Firma</th>
                                                            <th style="border:1px solid #999999">Num Receta</th>
                                                            <th style="border:1px solid #999999">DNI Paciente</th>
                                                            <th style="border:1px solid #999999">C&oacute;digo</th>
                                                            <th style="border:1px solid #999999">Producto</th>
                                                            <th style="border:1px solid #999999">Dispensado</th>
                                                            <th style="border:1px solid #999999">Cantidad Prescrita</th>
                                                            <th style="border:1px solid #999999">Cantidad Dispensada</th>
                                                            <th style="border:1px solid #999999">Saldo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        
                                                        if (isset($kardex)):
                                                            
                                                            foreach ($kardex as $key => $rowKardex):
                                                                if($id_producto==11045 || $id_producto==15938 || $id_producto==14607 || $id_producto==14608){
                                                                    $cantidad_kardex_prescrita = $rowKardex->cantidad_prescrita/1000;
                                                                    $cantidad_kardex_dispensada = $rowKardex->cantidad_dispensada/1000;
                                                                }
                                                                else{
                                                                    $cantidad_kardex_prescrita =$rowKardex->cantidad_prescrita;
                                                                    $cantidad_kardex_dispensada =$rowKardex->cantidad_dispensada;
                                                                }
                                                                //$rowKardex->cantidad
                                                                
                                                                ?>
                                                                <tr  style='font-size: 11px;'>
                                                                    <td align="center" class="con0"><?= ($key + 1) ?></td>	
                                                                    <td align="left" class="con0"><?= $rowKardex->fecha_registro ?></td>
                                                                    <td align="left" class="con0"><?php if($rowKardex->estado == 1):  echo $rowKardex->fecha_expedicion; else: ''; endif; ?></td>             
                                                                    <td align="left" class="con0"><?= $rowKardex->fecha_firma ?></td>
                                                                    <td align="left" class="con0"><?= $rowKardex->nro_receta ?></td>
                                                                    <td align="left" class="con0"><?= $rowKardex->dni_beneficiario ?></td>
                                                                    <td align="left" class="con1"><?= $rowKardex->codigo_producto ?></td>
                                                                    <td class="left" class="con0"><?= $rowKardex->nombre_producto ?></td>
                                                                    <td align="left" class="con0"><?php if($rowKardex->estado == 1): echo 'SI'; else: echo 'NO'; endif; ?>
                                                                    </td>
                                                                    <td align="left" class="con0"><?= $cantidad_kardex_prescrita ?></td>
                                                                    <td align="left" class="con0"><?= $cantidad_kardex_dispensada ?></td>
                                                                    <td class="con1" align="right"><?php echo $rowKardex->saldo ?></td>
                                                                </tr>
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
            </div>
        </div>
    </div>
</div> <!-- ./container -->

<!-- line modal -->

@include('frontend.includes.footer')
@endsection
