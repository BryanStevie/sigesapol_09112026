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
        
	function reporteExportProducto(){
		
        var id_producto = $('#nombre_producto').val();
        var codigo = id_producto.slice(0, 8);
		location.href='exportar_producto_establecimiento/'+codigo;
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
                                </span> B&uacute;squeda de Producto por Establecimiento
                            </a>
                        </h4>
                    </div>

                    <div class="panel-body">
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
                                                            <th style="border:1px solid #999999">Establecimiento</th>
                                                            <th style="border:1px solid #999999">Farmacia</th>
                                                            <th style="border:1px solid #999999">Tipo de Producto</th>
                                                            <th style="border:1px solid #999999">C&oacute;digo</th>
                                                            <th style="border:1px solid #999999">Producto</th>
                                                            <th style="border:1px solid #999999">Unidad</th>
                                                            <th style="border:1px solid #999999">Stock Actual</th>
                                                            <th style="border:1px solid #999999">Covid</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (isset($result)):
                                                            foreach ($result as $key => $rowKardex):
                                                                ?>
                                                                <tr  style='font-size: 11px;'>
                                                                    <td align="center" class="con0"><?= ($key + 1) ?></td>	
                                                                    <td align="left" class="con0"><?php if( $rowKardex->ESTABLECIMIENTO == 'DIRSAPOL' ):
                                                                                                        echo 'DROGUERIA UNIDAD EJECUTORA 020: SANIDAD DE LA PNP';
                                                                                                     else:
                                                                                                        echo $rowKardex->ESTABLECIMIENTO;
                                                                                                     endif; 
                                                                     ?></td>
                                                                    <td align="left" class="con1"><?= $rowKardex->FARMACIAS ?></td>
                                                                    <td class="left" class="con1"><?= $rowKardex->Tipo_producto ?></td>
                                                                    <td align="left" class="con1"><?= $rowKardex->codigo_producto ?></td>
                                                                    <td class="left" class="con1"><?= $rowKardex->nombre_producto ?></td>
                                                                    <td class="left" class="con0"><?= $rowKardex->unidad ?></td>                                                                    
                                                                    <td class="con1" align="right"><?= $rowKardex->stock_actual ?></td>
                                                                    <td class="con1" align="right"><?= $rowKardex->covid ?></td>
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
