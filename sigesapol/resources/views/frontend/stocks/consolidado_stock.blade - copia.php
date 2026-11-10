@extends('frontend.layouts.master2')
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
        
		$('#fechaDesde').appendDtpicker({
			"autodateOnStart": false,
			"minuteInterval": 5,
			"amPmInTimeList": true,
			"locale": "es",
			"dateFormat": "YYYY-MM-DD hh:mm",
		});
		
		
		$('#fechaHasta').appendDtpicker({
			"autodateOnStart": false,
			"minuteInterval": 5,
			"amPmInTimeList": true,
			"locale": "es",
			"dateFormat": "YYYY-MM-DD hh:mm",
		});
                
		$('#fechaHasta').trigger('change');
		$('#fechaDesde').trigger('change');
        
        $cmbEst = $("#frmReceta").find("#id_anaquel");
        $cmbEst.select2();
        
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
        
	function reporteConsolidadoStock(){
		
            var fechaDesde = $('#fechaDesde').val();
            var fechaHasta = $('#fechaHasta').val();
            var id_user = $('#idUser').val();
            if(fechaDesde=="")fechaDesde=0;
            if(fechaHasta=="")fechaHasta=0;
            location.href='exportar_consolidado_stock/'+fechaDesde+'/'+fechaHasta+'/'+id_user;
	}
	
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
                                </span> Consolidado de Movimiento por Producto
                            </a>
                        </h4>
                    </div>

                    <div class="panel-body">

                        <form id="frmReceta" class="form-horizontal" method="post" action="{{ route('consolidado_stock')}}">
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
                            <div class="col-md-2" style="margin-bottom: 20px !important">
                                <label>Fecha Desde</label>
                                <input type="hidden" name="idUser" id="idUser" value="<?php echo Auth::user()->id ?>"/>
                                <input class="form-control datepicker" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>">
                            </div>

                            <div class="col-md-2" style="margin-bottom: 20px !important">
                                <label>Fecha Hasta</label>
                                <input class="form-control datepicker" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>">
                            </div>
                            <div class="col-md-3" style="margin-bottom: 20px !important">
                                <label>Anaquel</label>
                            <select class="form-control" name="id_anaquel" id="id_anaquel">
                                <option value="" selected="">--Todos los anaqueles--</option>
                                @foreach($anaqueles as $e)
                                <option value="{{$e->id}}">{{$e->nombre}}</option>
                                @endforeach
                            </select>
                            </div>

                            <div class="col-md-2">
                                <label></label>
                                <input class="form-control btn btn-info" value="Buscar" type="submit" id="btnReg">
                            </div>

                            <!--
    <div class="col-md-2" style="margin-bottom: 20px !important">
        <label>Unidad</label>
        <input class="form-control" value="">
    </div>
    <div class="col-md-2" style="margin-bottom: 20px !important">
        <label>Cantidad Total</label>
        <input class="form-control" value="">
    </div>
                            -->
                            <!--@if(Auth::user()->id_farmacia==1)
                            <div class="col-md-2" style="margin-bottom: 20px !important">
                                <button data-toggle="modal" data-target="#squarespaceModal" class="form-control btn btn-success center-block">Lotizar</button>
                            </div>
                            @endif-->
                            <div class="col-md-12">
                                <a class="btn btn-success pull-right" href="javascript:void(0)" onclick="reporteConsolidadoStock()" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                                </a>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-group">
                                    <br/>
                                    <div class="panel panel-info">
                                        <!--<div class="panel-heading">
                                                                                        
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                    <span class="glyphicon glyphicon-th">
                                                    </span> LOTES ASIGNADOS
                                                </a>
                                            </h4>
                                                                                        
                                        </div>
                                        -->

                                        <div class="panel-body"> 
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <!--
                                                        <tr style='font-size: 11px;'>
                                                                <th colspan="6" style="border:0"></th>
                                                                <th colspan="3" style="border:1px solid #999999;text-align:center">Ingresos</th>
                                                                <th colspan="3" style="border:1px solid #999999;text-align:center">Salidas</th>
                                                                <th colspan="3" style="border:1px solid #999999;text-align:center">Saldos</th>
                                                        </tr>
                                                        -->
                                                        <tr style='font-size: 11px;'>
                                                            <th style="border:1px solid #999999">N.</th>
                                                            <th style="border:1px solid #999999">C&oacute;digo</th>
                                                            <th style="border:1px solid #999999">Detalle de Producto</th>
                                                            <th style="border:1px solid #999999">U.Medida</th>
                                                            <th style="border:1px solid #999999">Tipo de Producto</th>
                                                            <th style="border:1px solid #999999">Saldo Anterior</th>
                                                            <th style="border:1px solid #999999">Entradas</th>
                                                            <th style="border:1px solid #999999">Salidas</th>
                                                            <th style="border:1px solid #999999">Stock Actual</th>
                                                            <th style="border:1px solid #999999">P.Unit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $saldo = (isset($kardex_inicial->total)) ? $kardex_inicial->total : 0;
                                                        if (isset($kardex)):
                                                            foreach ($kardex as $key => $rowKardex):
                                                                ?>
                                                                <tr  style='font-size: 11px;'>
                                                                    <td align="center" class="con0"><?php echo ($key + 1) ?></td>	
                                                                    <td align="left" class="con0"><?php echo $rowKardex->codigo_producto ?></td>
                                                                    <td align="left" class="con0"><?php echo $rowKardex->nombre_producto ?></td>
                                                                    <td align="left" class="con0"><?php echo $rowKardex->unidad ?></td>
                                                                    <td align="left" class="con1"><?php echo $rowKardex->nombre_tipoproducto ?></td>
                                                                    <td align="right" class="con0"><?php echo $rowKardex->stock_desde ?></td>
                                                                    <td align="right" class="con0"><?php echo $rowKardex->ingresos ?></td>
                                                                    <td align="right" class="con0"><?php echo $rowKardex->egresos ?></td>
                                                                    <td class="con1" align="right"><?php echo $rowKardex->stock_hasta ?></td>
                                                                    <td class="con1" align="right"><?php echo number_format($rowKardex->precio_unitario, 2, '.', ','); ?></td>
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
