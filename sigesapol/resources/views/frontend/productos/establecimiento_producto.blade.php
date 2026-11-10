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

    @media screen and (max-width: 600px) {
        .ocultar-col{
            display:none;
        }
    }
    @media screen and (max-width: 980px) {
        .ocultar-col{
            display:none;
        }
        .cantidad {
            font-size: 15px;
        }
    }

</style>

<script>
    $(document).ready(function () {
	
		__autocomplete__AjaxBusqueda();
		
		$("#btnReg").on("click", function () {
			if ($('#frmReceta').find("#id_producto").val() == "") {
				bootbox.alert("Falta seleccionar el Producto");
				return false;
			}
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
		    },
            select: function(e, ui) {
				var id_producto = ui.item.id;
				$('#id_producto').val(id_producto);
		    }
		});
	}
        
	function reporteExportProducto(){
		
        var id_producto = $('#nombre_producto').val();
        var codigo = id_producto.slice(0, 8);
        if (id_producto!="")
		    location.href='exportar_producto_establecimiento/'+codigo;
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
                                </span> B&uacute;squeda de Producto por Establecimiento a Nivel Nacional
                            </a>
                        </h4>
                    </div>

                    <div class="panel-body">

                        <form id="frmReceta" class="form-horizontal" method="post" action="{{ route('listar_establecimiento_producto')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="pull-right col-md-12" style="margin-bottom:20px; text-align: right">                                
                            </div>
                            <div class="col-md-4" style="margin-bottom: 20px !important">
                                <label>Producto Farmaceútico</label>
                                <input type="text" id="nombre_producto" name="nombre_producto" class="form-control" value="<?php echo $nombre_producto ?>">
                                <input type="hidden" id="id_producto" name="id_producto" value="<?php echo $id_producto ?>" placeholder="Seleccionar producto farmaceutico">
                            </div>
                            <div class="col-md-2">
                                <label></label>
                                <input class="form-control btn btn-info" value="Buscar" type="submit" id="btnReg">
                            </div>

                            <div class="col-md-2">
                                <br/>
                                <a class="form-control btn btn-success pull-right" href="javascript:void(0)" onclick="reporteExportProducto()" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                                </a>
                            </div>
                        </form>
                        <div class="row"> <br/>
                            <div class="col-md-12">
                                <div class="panel-group">
                                    <br/>
                                    <div class="panel panel-info">
                                        <div class="panel-body">
                                            @if ($id_producto!="")
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr style='font-size: 11px;'>
                                                                <th style="border:1px solid #999999">N.</th>
                                                                <th class="ocultar-col" style="border:1px solid #999999">C&oacute;digo</th>
                                                                <th class="ocultar-col" style="border:1px solid #999999">Producto</th>
                                                                <th class="ocultar-col" style="border:1px solid #999999">Unidad</th>
                                                                <th class="ocultar-col" style="border:1px solid #999999">Establecimiento</th>
                                                                <th style="border:1px solid #999999">Farmacia</th>
                                                                <th style="border:1px solid #999999">Cantidad</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (isset($result))
                                                                @if (count($result)>0)
                                                                    @foreach ($result as $key => $rowKardex)
                                                                        <tr  style='font-size: 11px;'>
                                                                            <td align="center" class="con0"><?= ($key + 1) ?></td>	
                                                                            <td class="ocultar-col" align="left" class="con1"><?= $rowKardex->codigo_producto ?></td>
                                                                            <td class="ocultar-col" class="con0"><?= $rowKardex->nombre_producto ?></td>
                                                                            <td class="ocultar-col" class="con0"><?= $rowKardex->unidad ?></td>
                                                                            <td class="ocultar-col" class="con0"><?php if( $rowKardex->establecimiento == 'DIRSAPOL' ):
                                                                                                                echo 'DROGUERIA UNIDAD EJECUTORA 020: SANIDAD DE LA PNP';
                                                                                                            else:
                                                                                                                echo $rowKardex->establecimiento;
                                                                                                            endif; 
                                                                            ?></td>
                                                                            <td align="left" class="con0"><?php echo $rowKardex->farmacia; ?></td>
                                                                            <td class="cantidad" align="right"><b>
                                                                                <?php if($rowKardex->codigo_producto=='PF00718' || $rowKardex->codigo_producto=='PF01047' || $rowKardex->codigo_producto=='PF01007' || $rowKardex->codigo_producto=='PF01008'):
                                                                                    $rowKardex->stock = $rowKardex->stock/1000;
                                                                                endif; ?>
                                                                                <?php echo $rowKardex->stock ?></b>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr  style='font-size: 11px;'><td colspan="6"> No se han encontrado ningun resultado. </td></tr>
                                                                @endif
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
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
