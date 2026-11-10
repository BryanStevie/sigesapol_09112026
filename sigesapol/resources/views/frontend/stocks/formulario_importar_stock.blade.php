@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<script>
    $cmbPro = $("#id_producto");
    $cmbPro.select2();
	
	function validacionCargaStockInicial(id,id_usuario, idbtn){
            
            $("#btCargaIni"+idbtn).prop("disabled", true);
		//alert("ok");
                //return false;
		$.ajax({
			url: '/validacion_add_importacion_medicos/'+id+'/'+id_usuario, 
			success: function(result){
				
				var obj = eval("("+result+")");
				var msg 	= "";
				var n = 0;
				for(var key in obj) {
					//alert(obj[key].codigo);
					if(n == 0)msg += "<h1 style='font-size:19px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>No se pudo importar el archivo por los siguentes errores</h1>";
					msg += "Fila "+(Number(key) + 2)+" Error: "+(obj[key].msg).replace('{"',"").replace('."]}',"").replace('":["',"").replace('field is required','es requerido').replace('The ',' la ');
					var error = "";
					if(obj[key].tipo == 1)error = obj[key].cantidad;
					if(obj[key].tipo == 2)error = obj[key].codigo;
					if(obj[key].tipo == 3)error = obj[key].fecha_vencimiento;
					msg += " "+error+"<br>";
					n++;
				}
				
				
				if($.trim(msg) == ""){
					location.href="/add_importacion_stocks/"+id+"/"+id_usuario;
				}else{
					
					//bootbox.alert(msg);
					bootbox.alert(msg, function(){ location.href="/formulario_importar_stock/"+id_usuario; });
					
				}
				
				
				
				
				/*
				$(result).each(function (ii, oo) {
				});
				*/
				//$("#tabla_lote tbody").html("");
				/*$(result).each(function (ii, oo) {	
					$("#tabla_lote tbody").append(
						'<tr style="height:30px"> \n\
							<td style="width:15%"><input type="hidden" value="'+ oo.id +'" id="id_lote">' + oo.codigo_producto + '</td>\n\
							<td style="width:33%">' + oo.nombre_producto + '</td>\n\
							<td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
							<td style="width:13%">' + oo.registro_sanitario + '</td>\n\
							<td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
							<td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="' + oo.cantidad + '" style="width:90px"></td>\n\
							<td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="" style="width:90px"></td>\n\
						</tr>'
					);
				});
				*/
				
			}
		});
		
	}
	
</script>

	@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-primary">            
            <div class="panel-heading" style="margin-bottom: 30px">
                <h3 class="panel-title">Importar Stock</h3>                
            </div><?php //echo count($cargainicialact)."|".count($cargainicial)."|".count($cargainicialact)?>
            @if(count($cargainicial)==0)
                <div>
                    <form class="form-horizontal"  id="frmimportacion" method="post" action="{{route('subir_stock_farmacias')}}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <fieldset>
                            <!-- File Button --> 
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="stock">Archivo:</label>
                                <div class="col-md-3" style="border: 1px solid black; padding: 4px 4px 4px 4px">
                                    <input id="url_data" name="url_data" class="input-file" type="file" <?php if(count($cargainicialpen)<>0 || count($importacionesvalid)<>0) echo " disabled"?>  >
                                    <input type="hidden" name="id_user" value="{{$usuarios}}">
                                    <input type="hidden" name="id_establecimiento" value="{{$establecimiento->id}}"> 
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary" type="submit" <?php if(count($cargainicialpen)<>0 || count($importacionesvalid)<>0) echo " disabled"?> >Subir</button>
                                </div>
                            </div>
                                    <?php if(count($cargainicialpen)<>0) {?>
                                        <div class="row">
                                                <div class="col-xs-10 col-xs-offset-1">
                                                        <div class="alert alert-danger">
                                                        La farmarcia actual ya tiene un Stock inicial...
                                                        </div>
                                                </div>
                                        </div>
                                    <?php }?>
									
                              <?php if(count($importacionesvalid)<>0){?>
                                                <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <div class="alert alert-danger">
                                Existe un archivo que está por validar. Por favor dar clic en el botón &nbsp; <a class="btn btn-warning"><i class="fa fa-info"></i></a>
                            </div>
                        </div>
                    </div>
                            <?php }?>
                        </fieldset>
                    </form>
                </div>
                @if(count($importaciones)>0)
                <table class="table">
                    <thead>
                        <tr class="filters">
                            <th>N</th>
                            <!--<th><input type="text" class="form-control" placeholder="RUC" disabled></th>-->
                            <th>Archivo</th>
                            <th>Fecha Importación</th>
                            <th>Importar</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($importaciones as $i)
                        <tr>
                            <td><?php
                                $n++;
                                echo $n;
                                ?></td>
                            <!--<td></td>-->
                            <td>{{$i->url_data}}</td>

                            @if($i->estado==0)
                            <td>Sin importar datos</td>
                            <td>
                                <!--<a class="btn btn-warning" href="/add_importacion_stocks/{{$i->id}}/{{$usuarios}}"><i class="fa fa-info"></i></a>-->
                                <button type="button" class="btn btn-warning" onclick="validacionCargaStockInicial({{$i->id}},{{$usuarios}}, <?php echo $n?>)" id="btCargaIni<?php echo $n?>"><i class="fa fa-info"></i></button>
                            </td>
                            @else
                            <td>{{$i->fecha_importacion}}</td>
                            <td>
                                <i class="fa fa-check"></i>                                  
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                @else
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <div class="alert alert-danger">
                                La famarcia actual ya tiene un Stock inicial...
                            </div>
                        </div>
                    </div>
            @endif
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection