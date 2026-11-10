@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<style>

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

	function __autocomplete__AjaxBusqueda(){
		
		$("#nombre_diagnostico").autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
		$.ajax({
			url: '/obtener_diagnostico/' + request.term,
			dataType: "json",
			success: function(data)  {
			response(data);
		  }
			
		})
		
		  },select:  function(e, ui) {
				var id_diagnostico = ui.item.id;
				$('#id_diagnostico').val(id_diagnostico);
		  }
		});
		
		
	}
	
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) && (keynum == 9))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
    
    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }
        
    function soloNumerosMenosCero(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)       
        }
    
    $consul = $("#frmReceta").find("#id_consultorio");
    //$consul.select2();
    $tur = $("#frmReceta").find("#turno");
    //$tur.select2();
    $grado = $("#frmReceta").find("#grado");
    //$grado.select2();
    $tipo = $("#frmReceta").find("#tipo_beneficiario");
    //$tipo.select2();
   
    $medicos = $('#frmReceta').find("#id_medico")
    $medicos.select2();
    $tipo_receta = $('#frmReceta').find("#tipo_receta")
    //$tipo_receta.select2();
    
    function sumarCantidadLotes(){
        trs=$('#tabla_lote tbody').children('tr');                
        total=0;
        lote_lote=[];
		lote_registro_sanitario=[];
		lote_fecha_vencimiento=[];
        lote_cantidad=[];
        $(trs).each(function(ii,oo){            
            valor=$(oo).find('#cant').val(); 
            valor_lote_actual=$(oo).find('#lote_actual').val();                       
            valor_actual=$(oo).find('#cant_actual').val();            
            if(parseFloat(valor_actual)<parseFloat(valor)){
                //alert("La Cantidad digitada ("+valor+") del lote "+valor_lote_actual+" es mayor a su cantidad actual ("+valor_actual+")");
				bootbox.alert("La Cantidad digitada ("+valor+") del lote "+valor_lote_actual+" es mayor a su cantidad actual ("+valor_actual+")");
                return false;
            }
            lote=$(oo).find('#lote').val();
			registro_sanitario=$(oo).find('#registro_sanitario').val();
			fecha_vencimiento=$(oo).find('#fecha_vencimiento').val();
			lote=$(oo).find('#lote').val();
            if(valor!=0 && valor!=''){
                total+=parseFloat(valor);
                lote_lote.push(lote);
				lote_registro_sanitario.push(registro_sanitario);
				lote_fecha_vencimiento.push(fecha_vencimiento);
                lote_cantidad.push(valor);
            }            
        });
        //alert(total);                
        fila_medicamentos=$('#example tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        fila_medicamento=fila_medicamentos[rowIndex];
        cantidad_prescrita=$(fila_medicamento).find('#nro_med_solictados').val();
        if(total>cantidad_prescrita)
        {
            //alert("Error: el Total digitado("+total+") es mayor a la cantidad prescrita("+cantidad_prescrita+")");
			bootbox.alert("Error: el Total digitado("+total+") es mayor a la cantidad prescrita("+cantidad_prescrita+")");
            return false;
        }        
        $(fila_medicamento).find('#nro_med_entregados').val(total);
        cadena_lote_lote=lote_lote.join("||");
        cadena_lote_registro_sanitario=lote_registro_sanitario.join("||");
		cadena_lote_fecha_vencimiento=lote_fecha_vencimiento.join("||");
		cadena_lote_cantidad=lote_cantidad.join("||");
		
		
        $(fila_medicamento).find('#lotes_lote').val(cadena_lote_lote);
		$(fila_medicamento).find('#lotes_registro_sanitario').val(cadena_lote_registro_sanitario);
		$(fila_medicamento).find('#lotes_fecha_vencimiento').val(cadena_lote_fecha_vencimiento);
        $(fila_medicamento).find('#lotes_cantidad').val(cadena_lote_cantidad);
        //alert(cadena_lote_lote);
        //alert(cadena_lote_cantidad);
        $('#squarespaceModal').modal('hide');        
    }

    function verlotes(id_farmacia, btn){
        objTr = $(btn).closest("tr");        
        rowIndex = $('#example tbody').children('tr').index(objTr);
        $('#hidden_rowIndex').val(rowIndex);
        //codigo=objTr.find("#cmbMedicamento").val();
		codigo=objTr.find(".cmbMedicamento").val();
        cantidad_prescrita=objTr.find("#nro_med_solictados").val();
        if(cantidad_prescrita==""){
            //alert('Es necesario digitar la cantidad prescrita'); return false;
			bootbox.alert('Es necesario digitar la cantidad prescrita'); return false;
        }
        if(cantidad_prescrita==0){
            //alert('La Cantidad prescrita debe ser mayor a cero'); return false;
			bootbox.alert('La Cantidad prescrita debe ser mayor a cero'); return false;
        }
        
        cantidad_stock_actual=objTr.find("#nro_stocks").val();
        analisis=cantidad_stock_actual-cantidad_prescrita;
		$(".loader").css("display","block");
        if(cantidad_stock_actual==0){
            $("#alerta").html("");
            $("#alerta").append(
                'El Producto no contiene stock. Dar click en el boton <b style="color:green">Agregar</b> para continuar con la atención.'
            );
			
            $.ajax({
                url: '/obtener_lotes_por_idprod_idfarma/' + codigo+ '/' + {{Auth::user()->id_farmacia}}+'/'+cantidad_prescrita, 
                success: function(result){                    
                    $("#tabla_lote tbody").html("");
                    $(result).each(function (ii, oo) {
						$(".loader").css("display","none");
                        $("#tabla_lote tbody").append(
                            '<tr style="height:30px"> \n\
                                <td style="width:15%"><input type="hidden" value="'+ oo.lote +'" id="lote"><input type="hidden" value="'+ oo.registro_sanitario +'" id="registro_sanitario"><input type="hidden" value="'+ oo.fecha_vencimiento +'" id="fecha_vencimiento">' + oo.codigo_producto + '</td>\n\
                                <td style="width:33%">' + oo.prod_farmaceutico + '</td>\n\
                                <td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
                                <td style="width:13%">' + oo.registro_sanitario + '</td>\n\
                                <td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
                                <td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="'+ oo.cantidad +'" style="width:90px"></td>\n\
                                <td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="" style="width:90px"></td>\n\
                            </tr>'
                        );
                    });
					$('#squarespaceModal').modal();                                         
                }
            });            
        }else{
			//if(analisis<0){
				//alert('La cantidad prescrita de '+cantidad_prescrita+' unidades es mayor al stock disponible.'); return false;
			//}else{
			 $.ajax({
					url: '/obtener_lotes_por_idprod_idfarma/' + codigo+ '/' + {{Auth::user()->id_farmacia}}+'/'+cantidad_prescrita, 
					success: function(result){                    
						$("#tabla_lote tbody").html("");
						$(result).each(function (ii, oo) {
							$(".loader").css("display","none");
							$("#tabla_lote tbody").append(
								'<tr style="height:30px"> \n\
									<td style="width:15%"><input type="hidden" value="'+ oo.lote +'" id="lote"><input type="hidden" value="'+ oo.registro_sanitario +'" id="registro_sanitario"><input type="hidden" value="'+ oo.fecha_vencimiento +'" id="fecha_vencimiento">' + oo.codigo_producto + '</td>\n\
									<td style="width:33%">' + oo.prod_farmaceutico + '</td>\n\
									<td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
									<td style="width:13%">' + oo.registro_sanitario + '</td>\n\
									<td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
									<td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="' + oo.cantidad + '" style="width:90px"></td>\n\
									<td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="" style="width:90px"></td>\n\
								</tr>'
							);
						});
						if(cantidad_prescrita>=10){
							$("#alerta").html("");
							$("#alerta").append(
								'Cantidad Prescrita: '+cantidad_prescrita+' unidades.<br>Advertencia: La cantidad prescrita es superior a 10.'
							);
						}else{
							$("#alerta").html("");
							$("#alerta").append(
								'Cantidad Prescrita: '+cantidad_prescrita+' unidades.'
							);                        
						}
						$('#squarespaceModal').modal();
					}
				});
			//}
			
			
        }
        //$('#squarespaceModal').modal();
    } 
	
	function verlotes2(cod_producto, id_farmacia, btn){        
        objTr = $(btn).closest("tr");        
        rowIndex = $('#example tbody').children('tr').index(objTr);
        $('#hidden_rowIndex').val(rowIndex);
        //alert(cod_producto); return false;
        //codigo_real=objTr.find("#cmbMedicamento").val();
		codigo_real=objTr.find(".cmbMedicamento").val();
        //alert(codigo_real); return false;
        cantidad_prescrita=objTr.find("#nro_med_solictados").val();
        if(cantidad_prescrita==""){
            //alert('Es necesario digitar la cantidad prescrita'); return false;
			bootbox.alert('Es necesario digitar la cantidad prescrita'); return false;
        }
        if(cantidad_prescrita==0){
            //alert('La Cantidad prescrita debe ser mayor a cero'); return false;
			bootbox.alert('La Cantidad prescrita debe ser mayor a cero'); return false;
        }
        cantidad_stock_actual=objTr.find("#nro_stocks").val();
        analisis=cantidad_stock_actual-cantidad_prescrita;
        if(cantidad_stock_actual==0){            
            $("#alerta").html("");
            $("#alerta").append(
                'El Producto no contiene stock. Dar click en el boton <b style="color:green">Agregar</b> para continuar con la atención.'
            );
            $.ajax({
                url: '/obtener_lotes_por_idprod_idfarma/' + codigo_real+ '/' + id_farmacia+'/'+cantidad_prescrita,
                success: function(result){                    
                    $("#tabla_lote tbody").html("");
                    $(result).each(function (ii, oo) {                       
                        $("#tabla_lote tbody").append(
                            '<tr style="height:30px"> \n\
                                <td style="width:15%"><input type="hidden" value="'+ oo.lote +'" id="lote"><input type="hidden" value="'+ oo.registro_sanitario +'" id="registro_sanitario"><input type="hidden" value="'+ oo.fecha_vencimiento +'" id="fecha_vencimiento">' + oo.codigo_producto + '</td>\n\
                                <td style="width:33%">' + oo.prod_farmaceutico + '</td>\n\
                                <td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
                                <td style="width:13%">' + oo.registro_sanitario + '</td>\n\
                                <td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
                                <td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="'+ oo.cantidad +'" style="width:90px"></td>\n\
                                <td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="" style="width:90px"></td>\n\
                            </tr>'
                        );
                    });                                         
                }
            });
            
        }
        else{            
			//if(analisis<0){
				//alert('La cantidad prescrita de '+cantidad_prescrita+' unidades es mayor al stock disponible.'); return false;
			//}else{                
				$.ajax({
					url: '/obtener_lotes_por_idprod_idfarma/' + codigo_real+ '/' + id_farmacia+'/'+cantidad_prescrita,
					success: function(result){
						$("#tabla_lote tbody").html("");
						$(result).each(function (ii, oo) {                       
							$("#tabla_lote tbody").append(
								'<tr style="height:30px"> \n\
									<td style="width:15%"><input type="hidden" value="'+ oo.lote +'" id="lote"><input type="hidden" value="'+ oo.registro_sanitario +'" id="registro_sanitario"><input type="hidden" value="'+ oo.fecha_vencimiento +'" id="fecha_vencimiento">' + oo.codigo_producto + '</td>\n\
									<td style="width:33%">' + oo.prod_farmaceutico + '</td>\n\
									<td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
									<td style="width:13%">' + oo.registro_sanitario + '</td>\n\
									<td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
									<td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="' + oo.cantidad + '" style="width:90px"></td>\n\
									<td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="" style="width:90px"></td>\n\
								</tr>'
							);
						});
						if(cantidad_prescrita>=10){
						$("#alerta").html("");
						$("#alerta").append(
								'Cantidad Prescrita: '+cantidad_prescrita+' unidades.<br>Advertencia: La cantidad prescrita es superior a 10.'
						);
						}else{
							$("#alerta").html("");
							$("#alerta").append(
								'Cantidad Prescrita: '+cantidad_prescrita+' unidades.'
							);                        
						}
					}
				});
			//}
    }
        $('#squarespaceModal').modal();
    }
	
	   
    //-------------------AGREGAR PRODUCTOS--------------------------------------
    $(document).ready(function () {
		
		__autocomplete__AjaxBusqueda();
		
		/*
        var t = $('#example').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            bFilter: false,
            bPaginate: false,
            bSort: false,
            "createdRow": function (row, data, index) {
                cmbMedicamento = $("#cmbMedicamento").clone();
                $(cmbMedicamento).attr('name', 'id_producto[]');
                $(cmbMedicamento).on("change", function (e) {
                    cmb = $(this);
                    id_producto = $(cmbMedicamento).val();
                    id_user={{Auth::user()->id}};
                    option = {
                        url: 'cargarstock_farmacia/' + id_producto+ '/' + id_user,
                        type: 'GET',
                        dataType: 'json',
                        data: {}
                    };
                    $.ajax(option).done(function (data) {
                        $(cmb).closest("tr").find("#nro_stocks").val(data);                        
                        $(cmb).closest("tr").find("#nro_med_solictados").val("");  
                        $(cmb).closest("tr").find("#nro_med_entregados").val("");
                        $(cmb).closest("tr").find("#lotes_lote").val("");
                        $(cmb).closest("tr").find("#lotes_cantidad").val("");
						$(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
						$(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
                    });
                });
                $(row).find('#dvMedicamento').append(cmbMedicamento);
                $(cmbMedicamento).select2({max_selected_options: 4});
            },
            "language": {
                "emptyTable": "Agregar Productos Farmacéuticos o Dispositivos Médicos"
            }
        });
		*/
		
        var counter = 1;

        $('#example tbody').on('click', 'button.deleteFila', function () {
            //r = confirm("¿Desea eliminar el Producto Farmacéutico o Dispositivo Médico?");
			var obj = $(this);
			bootbox.confirm("¿Desea eliminar el Producto Farmacéutico o Dispositivo Médico?", function(result) {
            //if (r) {
			if (result==true) {
                //t.row(obj.closest('tr')).remove().draw();
				obj.parent().parent().remove();
            }
			
			});

        });
        $('#addRow').on('click', function () {
			AddFila();
			/*
            t.row.add([
                '<div id="dvMedicamento"></div>',                                
                '<input type="text" name="nro_stocks[]" required id="nro_stocks" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>               	<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/><input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/><input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/><input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/>',
                '<input onKeyPress="return soloNumerosMenosCero(event)" type="text" name="nro_med_solictados[]" required="" id="nro_med_solictados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px"/>',                
                '<input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>',
                '<input type="hidden" name="exit" id="exit" id="" style="width:10px" value="<?php //$exit=1; echo $exit?>"/>',
                '<button data-toggle="modal" class="btn btn-primary btn-xs" type="button" onclick="verlotes({{Auth::user()->id_farmacia}},this)">Add</button>',                                  
                '<button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>'
            ]).draw(false);
			*/
			
        });
		
		

        // Automatically add a first row of data
        //$('#addRow').click();
		
        $("#frmReceta").submit(function (e) {
			
			$.ajax({
				url: '/validacion_beneficiario_dias_productos/', 
				type:'POST',
				data:$("#frmReceta").serialize(),
				context: this,
				success: function(result){
					var msg 	= "";
					var n 		= 0;
					var flag 	= true;
					for(var key in result) {
						if(n == 0){
							msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta menor a 30 dias</h1>";							
							msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                        	msg += "<thead>";
                            msg += "<tr>";
							msg += "<th>Farmacia</th>";
							msg += "<th>Nro. Receta</th>";
							msg += "<th>Fecha Expedicion</th>";
							msg += "<th>Producto</th>";
							msg += "<th>Tiempo(Dias)</th>";
							msg += "<th>Dias Transcurridos</th>";
							msg += "<th>Dias Pendientes</th>";
							msg += "<th>Cantidad Prescrita</th>";
							msg += "<th>Cantidad Entregada</th>";
                            msg += "</tr>";
                        	msg += "</thead>";
							msg += "<tbody>";
						}
						
						msg += "<tr><td>"+(result[key].farmacia)+"</td>";
						msg += "<td>"+(result[key].nro_receta)+"</td>";
						msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
						msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
						msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
						msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
						msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
						msg += "<td>"+(result[key].cantidad_prescrita)+"</td>";
						msg += "<td>"+(result[key].cantidad_dispensada)+"</td></tr>";
						n++;
					}
					if($.trim(msg) == ""){
						
						rows = $("#example tbody").children('tr');            
						//---- Validación de las cantidades prescritas y dispensadas-------------------------------------
						var i=0;
						$.each(rows, function (ii, oo) {                       
							medicina = $(oo).find(".cmbMedicamento").select2("data");                                              
							i=i+1;
							nuevaCantidad = parseFloat($(oo).find("#nro_med_entregados").val());
							cantidadPrescrita = parseFloat($(oo).find("#nro_med_solictados").val());
							stockActual = parseFloat($(oo).find("#nro_stocks").val());
							tiempo_periodo = parseFloat($(oo).find("#tiempo_periodo").val());
							
							if(tiempo_periodo==0){
								bootbox.alert("La medicina: " +medicina[0].text+ ", tiene que tener periodo(dias) mayor a cero");
								flag = false;
							}else if(cantidadPrescrita==0){
								bootbox.alert("La medicina: " +medicina[0].text+ ", tiene que tener cantidad prescrita mayor a cero");
								flag = false;
							}else{                 
								if (stockActual == 0) {                    
									if (nuevaCantidad != 0) {                           
										bootbox.alert("La medicina: " +medicina[0].text+ ", tiene que ser cantidad cero");
										flag = false;
									}
								} else {
									if (nuevaCantidad > stockActual) {
										bootbox.alert("La medicina: " + medicina[0].text + ", la cantidad no tiene que ser mayor que el stock");
										flag = false;
									} else if (nuevaCantidad > cantidadPrescrita) {
										bootbox.alert("La medicina: " + medicina[0].text + ", la cantidad no tiene que ser mayor que la prescrita");
										flag = false;
										
									}
								}                        
							}
							
						});
						
						if(flag == true){
							this.submit();
						}
						
					}else{
						flag = false;
						bootbox.alert(msg);
					}
					
				}
			});
			return false;
            
        });
        
        
		
		/*
		$("#frmReceta").submit(function (e) {
            rows = $("#example tbody").children('tr');            
                var i=0;
                $.each(rows, function (ii, oo) {                          
					medicina = $(oo).find(".cmbMedicamento").select2("data");
                    i=i+1;
                    nuevaCantidad = parseFloat($(oo).find("#nro_med_entregados").val());
                    cantidadPrescrita = parseFloat($(oo).find("#nro_med_solictados").val());
                    stockActual = parseFloat($(oo).find("#nro_stocks").val());

                    if(cantidadPrescrita==0){
                        alert("La medicina: " +medicina[0].text+ ", tiene que tener cantidad prescrita mayor a cero");
                        e.preventDefault();
                        return false;                     
                    }else{                 
                        if (stockActual == 0) {                    
                            if (nuevaCantidad != 0) {                           
                                alert("La medicina: " +medicina[0].text+ ", tiene que ser cantidad cero");
                                e.preventDefault();
                                return false;
                            }
                        } else {
                            if (nuevaCantidad > stockActual) {
                                alert("La medicina: " + medicina[0].text + ", la cantidad no tiene que ser mayor que el stock");                        
                                e.preventDefault();
                                return false;
                            } else if (nuevaCantidad > cantidadPrescrita) {
                                alert("La medicina: " + medicina[0].text + ", la cantidad no tiene que ser mayor que la prescrita");                        
                                e.preventDefault();
                                return false;
                            }
                        }                        
                    }
                });                
        });
		*/
		
		
		
		
        $id_diagnostico = $('#frmReceta').find("#id_diagnostico");
        $cies = $('#frmReceta').find("#cies");
        //$cies.select2();
        //----------------------------------------------------------------------
        $cies.change(function () {
            $this = $(this);           
            cie = $this.val();
            option = {
                url: 'buscar_diagnostico/' + cie,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                cargarComboUbigeo($id_diagnostico, data);

            });
        });
        
        $dispensado = $('#frmReceta').find("#nro_med_solictados");
        //$cies.select2();
        //----------------------------------------------------------------------
        $dispensado.change(function () {
            $this = $(this);           
            dispensado = $this.val();
            if(dispensado==0){
                //alert("La cantidad prescrita no puede ser cero"); exit;
				bootbox.alert("La cantidad prescrita no puede ser cero"); exit;
                return false;
            }
        });
        
    });
    //-------------------------------------------------------------------------
    function cargarComboUbigeo($select, data) {
        if((data.length)>0){
            $select.html('');
            $(data).each(function (ii, oo) {
                $select.append('<option value="' + oo.id + '">'+oo.codigo  +' - ' + oo.nombre + '</option>')
            });
            $('#frmReceta').find("#id_medico").focus();
        }else{
            //alert('Codigo Cie10 que digitó no es correcto, intente nuevamente');
			bootbox.alert('Codigo Cie10 que digitó no es correcto, intente nuevamente');
            $('#frmReceta').find("#cies").val("");
            $('#frmReceta').find("#cies").focus();
            $select.html('');
        }
    }
    //-------------------------------------------------------------------------
    
    $("#digitos_adicionales").on("keyup", function () {
        nro_receta='P-'+$('#frmReceta').find("#nro_receta").val()+'-'+$('#frmReceta').find("#digitos_adicionales").val();
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        //alert(id_farmacia);
        validar(nro_receta, id_farmacia);
    });
    //-------------------------------------------------------------
    $("#btnReg").on("click", function () {
          
        if ($('#frmReceta').find("#nro_receta").val() == "") {
            //alert("Falta Digitar el Número de Receta");
			bootbox.alert("Falta Digitar el Número de Receta");
            return false;
        }                
        if ($('#frmReceta').find("#digitos_adicionales").val() == "") {
            //alert("Falta Digitar el Número Receta");
			bootbox.alert("Falta Digitar el Número Receta");
            return false;
        }        
        if ($('#frmReceta').find("#id_consultorio").val() == "") {
            //alert("Falta seleccionar el Consultorio");
			bootbox.alert("Falta seleccionar el Consultorio");
            return false;
        }
		/*
        if ($('#frmReceta').find("#grado").val() == "") {
            //alert("Falta seleccionar el grado");
			bootbox.alert("Falta seleccionar el grado");
            return false;
        }
		*/
        farmacos=$('#frmReceta').find("#nro_stocks").val();
        if(farmacos==undefined){                    
            //alert("La receta no tiene productos agregados, corregir o cancelar el proceso");
			bootbox.alert("La receta no tiene productos agregados, corregir o cancelar el proceso");
            return false;        
        }
        
        if ($('#frmReceta').find("#fecha_expedicion").val() == "") {
            //alert("Falta seleccionar la fecha de expedición de la receta");
			bootbox.alert("Falta seleccionar la fecha de expedición de la receta");
            return false;
        }
        
        //----------------------------------------------------------------------
        fecha_hace_treinta_dias=$('#frmReceta').find("#fecha_pasada").val();                        
        fecha_dispensar=$('#frmReceta').find("#fecha_expedicion").val();   
        //alert(fecha_hace_treinta_dias);
        var xMonth=fecha_hace_treinta_dias.substring(3, 5);                 
        var xDay=fecha_hace_treinta_dias.substring(0, 2);  
        var xYear=fecha_hace_treinta_dias.substring(6,10);         
        var yMonth=fecha_dispensar.substring(3, 5);          
        var yDay=fecha_dispensar.substring(0, 2);  
        var yYear=fecha_dispensar.substring(6,10);         
        if (xYear> yYear) {  
                //alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php //echo $rango_registro?> días atrás");
				bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                //alert("comparacion año");
                return false; 
            }  
        else{  
            if (xYear == yYear){                   
                if (xMonth> yMonth){  
                    //alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php //echo $rango_registro?> días atrás");
					bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                    //alert("comparacion mes");
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            //alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php //echo $rango_registro?> días atrás");
							bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                            //alert("comparacion dia");
                            return false;  
                        }
                    }
                }  
            }
        }
        //------------------------------------------------------------------------
        var hoy = new Date();
        var dd = hoy.getDate();
        var mm = hoy.getMonth()+1; //hoy es 0!
        var yyyy = hoy.getFullYear();        
        if(dd<10) {
            dd='0'+dd
        } 
        if(mm<10) {
            mm='0'+mm
        } 
        hoy = dd+'-'+mm+'-'+yyyy;        
        fecha_dispensar=$('#frmReceta').find("#fecha_expedicion").val();                        
        var xMonth=fecha_dispensar.substring(3, 5);                 
        var xDay=fecha_dispensar.substring(0, 2);  
        var xYear=fecha_dispensar.substring(6,10);  
        var yMonth=hoy.substring(3, 5);          
        var yDay=hoy.substring(0, 2);  
        var yYear=hoy.substring(6,10);         
        if (xYear> yYear) {  
                //alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php //echo $rango_registro?> días atrás");
				bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                return false; 
            }  
        else{  
            if (xYear == yYear){   
                if (xMonth> yMonth){  
                    //alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php //echo $rango_registro?> días atrás");
					bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            //alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php //echo $rango_registro?> días atrás");
							bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                            return false;  
                        }
                    }
                }  
            }
        }
        //----------------------------------------------------------------------
               
        if ($('#frmReceta').find("#id_diagnostico").val() == "") {
			bootbox.alert("Falta seleccionar el diagnostico (CIE10)");
            return false;
        }
        
        if ($('#frmReceta').find("#id_medico").val() == "") {
            //alert("Falta seleccionar el Profesional de Salud");
			bootbox.alert("Falta seleccionar el Profesional de Salud");
            return false;
        }
                
        
        if ($('#frmReceta').find("#tiempo_periodo").val() == "") {
            //alert("Falta digitar la cantidad prescrita");
			bootbox.alert("Falta digitar el tiempo(dias)");
            return false;
        }
		
        if ($('#frmReceta').find("#nro_med_solictados").val() == "") {
            //alert("Falta digitar la cantidad prescrita");
			bootbox.alert("Falta digitar la cantidad prescrita");
            return false;
        }
		
        
        if ($('#frmReceta').find("#nro_med_entregados").val() == "") {
            //alert("Falta digitar la cantidad dispensada");
			bootbox.alert("Falta digitar la cantidad dispensada");
            return false;
        }
        
        if ($('#frmReceta').find("#nro_historia").val() == "") {
			bootbox.alert("Falta digitar el Nro. de historia");
            return false;
        }                 
        
        if ($('#frmReceta').find("#nro_med_solictados").val() >= 10) {
            //if (confirm("Aviso: Revise la(s) cantidad(s) prescrita(s) y dispensada(s) antes de registrar. ¿Desea Grabar?.") == true) {
			bootbox.confirm("Aviso: Revise la(s) cantidad(s) prescrita(s) y dispensada(s) antes de registrar. ¿Desea Grabar?.", function(result) {
                //$("#frmReceta").submit();
			if (result==true) {
				$("#frmReceta").submit();
                /*return true;
            } else {
                return false;*/
            }
			
			});
			return false;
        }else{
            //if (confirm("Revise que los datos de la receta se encuentren correctos antes de registrar. ¿Desea Grabar?.") == true) {
			bootbox.confirm("Revise que los datos de la receta se encuentren correctos antes de registrar. ¿Desea Grabar?.", function(result) {
			if (result==true) {
                $("#frmReceta").submit();
                //return true;
            /*} else {
                return false;*/
            }
			});
			return false;
			
        }
    });
    //--------------------------------------------------------------------------
	/*
    $(".chosen-select").chosen({
		//max_selected_options: 4
		no_results_text: "My language message.", 
		placeholder_text : "My language message.", 
		search_contains: true,
		disable_search_threshold: 10
	});
	*/
	
	$(".chosen-select").select2(
		{	//placeholder: "Select a Color",
			minimumInputLength: 3,
  			tags: true,
			cache: true,
			language: { inputTooShort: function () { return 'Ingresar 3 caracteres para buscar el diagnostico'; } },

		}
	);
	
	$('.chosen-select').on("select2:select", function (e) {
	 	if(e.params.data.id > 0){
		
		}else{
			$(this).select2('val', '');
		}
		
	 });
 
    //--------------------------------------------------------------------------
    
    function validar(nro_receta, id_farmacia){
            option = {                
                url: '/evitar_duplicados_rectas/' + nro_receta+ '/' + id_farmacia,
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                existe=data; 
                if(existe>0){
                    $('#frmReceta').find("#digitos_adicionales").val("");
                    $('#frmReceta').find("#nro_receta").val("");
                    $('#frmReceta').find("#nro_receta").focus();
                    //alert("La recta: " + nro_receta+ " se encuentra actualmente registrada");
					bootbox.alert("La recta: " + nro_receta+ " se encuentra actualmente registrada");
                    e.preventDefault();
                }
            });     
        } 
      
	  
	function AddFila(){
		
		var newRow 				= "";
		var ind = $('#example tbody tr').length;
		
		if(ind > 0){
			$('.cmbMedicamento').each(function(){
				var ind_tmp = $(this).attr("ind");
				if(ind_tmp => ind)ind=Number(ind_tmp)+1;
			});
		}
		/*var bGraba 				= true;
		var bExiste 			= false;
		var sValidaciones 		= "";
		var sumaSubTotal 		= 0;
		var igv 				= 0*/
		
		/*
		newRow = "<tr class='fila' onclick='ver(this)' iIdProducto='"+iIdProducto+"' dPrecio='"+TxtPrecio+"' dDescuento='"+TxtDescuento+"' iCantidad='"+TxtCantidad+"' subtotal="+subtotal+" iIdSucursal='"+iIdSucursal+"' iIdAlmacen='"+iIdAlmacen+"' iIdProveedor='"+iIdProveedor+"' >";
		newRow += "<td align='center'><input name='chk' type='checkbox' onchange=ver(this);ValidarUnSoloCheck(this,'chk') /></td>";
		newRow += "<td class='itemm'>"+TxtCodigo+"</td>";
		newRow += "<td><img src='galeria/producto/"+vImagen+"' width='80' height='50' alt='' /></td>";
		newRow += "<td>"+TxtProducto+"</td>";
		newRow += "<td align='right'>"+TxtCantidad+"</td>";
		newRow += "<td align='right'>"+TxtPrecio+"</td>";
		newRow += "<td align='right'>"+TxtDescuento+"</td>";
		newRow += "<td align='right'>"+subtotal+"</td>";
		newRow += "</tr>";
		*/
		var item_producto 	= "";
		$('#cmbMedicamentoTemp option').each(function(){
			item_producto += "<option value="+$(this).val()+" ru='"+$(this).attr("ru")+"'>"+$(this).html()+"</option>"	
		});
		
		newRow +='<tr>';
		//newRow +='<div id="dvMedicamento"></div>';
		newRow +='<td><select class="form-control cmbMedicamento" id="cmbMedicamento'+ind+'" ind="'+ind+'" name="id_producto[]" style="width: 500px">'+item_producto+'</select></td>';
		newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
		newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" required="" id="tiempo_periodo" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" value=""/>';
		newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
		newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
		newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
		newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
		newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" name="nro_med_solictados[]" required="" id="nro_med_solictados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" onkeyup=calcularCantidadEntregada(this) /></td>';
		newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
		newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';
		//newRow +='<td><button data-toggle="modal" class="btn btn-primary btn-xs" type="button" onclick="verlotes({{Auth::user()->id_farmacia}},this)">Add</button></td>';
		newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
		newRow +='</tr>';
		//alert(newRow);
		$('#example tbody').append(newRow);
		$("#cmbMedicamento"+ind).select2({max_selected_options: 4});
		
		$("#cmbMedicamento"+ind).on("change", function (e) {
			var flagx = 0;
			cmb = $(this);
			id_producto = $("#cmbMedicamento"+ind).val();
			id_user={{Auth::user()->id}};
			
			var ru = $("#cmbMedicamento"+ind+ " option:selected").attr("ru");
			
			if(ru != ""){
				bootbox.alert("El producto farmaceutico es de uso restringido");
			}
			
			$('.cmbMedicamento').each(function(){
				var ind_tmp = $(this).val();
				if($(this).val() == id_producto)flagx++;
			});
			
			if(flagx > 1){
			
				bootbox.alert("El producto farmaceutico ya ha sido ingresado");
				$("#cmbMedicamento"+ind).val("").trigger("change");
                return false;
			}
			
			option = {
				url: 'cargarstock_farmacia/' + id_producto+ '/' + id_user,
				type: 'GET',
				dataType: 'json',
				data: {}
			};
			$.ajax(option).done(function (data) {
				$(cmb).closest("tr").find("#nro_stocks").val(data);
				$(cmb).closest("tr").find("#tiempo_periodo").val("");  
				$(cmb).closest("tr").find("#nro_med_solictados").val("");  
				$(cmb).closest("tr").find("#nro_med_entregados").val("");
				$(cmb).closest("tr").find("#lotes_lote").val("");
				$(cmb).closest("tr").find("#lotes_cantidad").val("");
				$(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
				$(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
			});
			
			
		});
		/*
		$('#TBodyDetalleMovimientoVenta').append(newRow);
		$('#vImagen').val("");
		$('#TxtCodigo').val("");
		$('#TxtProducto').val("");
		*/
	}
	
	
	function calcularCantidadEntregada(obj){
		
		var total		= 0;
		var nro_stocks = $(obj).parent().parent().find('#nro_stocks').val();
		
		var rs = $(obj).val().toString()
		if(rs != "0" && rs != ""){
			total = parseFloat(nro_stocks) - parseFloat($(obj).val());
			cantidad_prescrita = parseFloat($(obj).val());
		
		//alert(total);
		if(total >= 0 && nro_stocks > 0){
			$(obj).parent().parent().find('#nro_med_entregados').val(Math.round(cantidad_prescrita*100)/100);
		}else if(total <= 0 && nro_stocks > 0){
			$(obj).parent().parent().find('#nro_med_entregados').val(Math.round(nro_stocks*100)/100);
		}else{
			
			$(obj).parent().parent().find('#nro_med_entregados').val(0);
			//$(obj).val(0);
		}
		
		}
	}
        
        function show_Atenciones(dni){
            var dni_beneficiario = dni;
            $.ajax({
                url: '/obtenerRecetasMesActualByBeneficiario/' + dni_beneficiario,
                success: function (result) {
                    var newOption = "";
                    $(result).each(function (ii, oo) {
                        var btn = "<button type=\"button\" data-toggle=\"modal\" class=\"btn btn-primary btn-xs\" onclick=\"show_Det_Atenciones('" + oo.id_receta_vale + "')\"><i class=\"fa fa-list\"></i></button>";
                        newOption += "<tr><td><small>" + oo.nro_receta + "</small></td><td><small>" + oo.fecha_expedicion + "</small></td><td><small>" + oo.nom_consultorio + "</small></td><td><small>" + oo.nom_farmacia + "</small></td><td><small>" + oo.apepat_medico + " " + oo.apemat_medico + " " + oo.nom_medico + "</small></td><td><small>" + oo.nom_diagnostico + "</small></td><td class=\"text-center\">" + btn + "</td></tr>";
                    });
                    $('#muestra-atenciones').html(newOption);
                }
            });
            $('#showAtencionesMensual').modal();
        }
        
        function show_Det_Atenciones(id_receta_vale){
            $.ajax({
                url: '/obtenerProductobyRecetasVale/' + id_receta_vale,
                success: function (result) {
                    var newOption = "";
                    $(result).each(function (ii, oo) {
                        newOption += "<tr><td class=\"text-center\"><small>" + oo.codigo + "</small></td><td><small>" + oo.nombre + "</small></td><td class=\"text-center\"><small>" + oo.abreviatura + "</small></td><td class=\"text-center\"><small>" + oo.cantidad_prescrita + "</small></td><td class=\"text-center\"><small>" + oo.cantidad_dispensada + "</small></td>";
                    });
                    $('#muestra-productos').html(newOption);
                }
            });
            $('#showProductosReceta').modal();
        }
 
</script>
<style>
    .modal-dialog{
        width: 850px !important;
    }    
    .suggest-element{
        margin-left:5px;
        margin-top:5px;
        width:350px;
        cursor:pointer;
    }
    #suggestions {
        width:350px;
        height:150px;
        overflow: auto;
    }
    
        
    .alert {
background-position: 2% 7px;
background-repeat: no-repeat;
background-size: auto 35px;
background-color: rgba(0, 0, 0, 0);
border: 0;
min-width: auto !important;
text-align: left;
padding-left: 68px;
}
.alert.alert-danger {
background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAARVBMVEX////7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0NAQECdhUHYsUKGdEG1l0JLSEBuYkHkukPKU8NPAAAADnRSTlMA4EDAoCAQ8IBgkDDQUFItelQAAAC0SURBVHherZBLDsMwCAVD7Nj5gvPr/Y/a0FpGLTibdnbozQJN829agPZu7/CiuxE8C76+T/hiqgruLbjqh5ip/TljZrb3AQuDKYAIYO09L3TBRq/3EHk4shCDEkZkTqITmdGMLIIO7r8FbzfaiDappSNjIkpZcCqyCDo4WAJYkR8prTp4ADSBUCIX9uPY5epL5AJdyBWDRDYFHCWyCiXBObKwrh+nl4g2TiLVhSXW97g0v/MEHIQbCYeFmYAAAAAASUVORK5CYII=);
border-top: 1px solid rgba(140, 0, 0, 0.4);
border-bottom: 1px solid rgba(140, 0, 0, 0.4);
}
.alert.alert-info {
background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAADBklEQVR42u3YyWsTURwHcP8aQUWtWlu7JEbtpngQvIqXevDWm1fXehO12BTErdQiVMFQ8ODBusWkmSa1lVrTxdrG0FA9WJtkljfL19/LTDKMibl0EorkwReSCQ8+fN+bl2G2AdhSqYH+P1CpAS0DfXUI+ufzUCfaoQptYMIxsMhRsE/d0L4/BNR0qcnug4y159CmTkOLdkKNdnBQAaNEjkAZ90EOH4YcOQ51dQQVBRnLN6DHuqDFOqGZGEpbaVDICynkAVvoRUVAxuqQE8PjXC7C/AX64KG0gq3ch6sgQ1zkEBtTph2FY8JeApkYKdgCMdgMPTMP10D64kUOsTDtDsw/27FAIge9b4Y8e8EdELS0jTAh5TF2Ow5Q9l0TDDWNTYOMjSkLQREcEAdGCRfvHdFcLgI1EegQtPXY5kFactAE2JDyGHvvONrhILZ81w3QIxNQCEfw2JvYxpRYKrMdSqNLoNSzPMBGTJwEmz4HuSSGMnkWYrgjv1Rm3jZATQ5vHqT//sgRVhtmI8p0NwADbOF6EUaOX4GiZCHGzhAkD2ogUD20XxNw5baXhRN2G2EvR+QwhqFDme91YJgiQfpyCTamkTAHaQm97p1DauIBR1jxWI14CHMNuqZCmbsKmcKYBLkYQzkA9m3APRA/P6TxrvztbCfYkoOoqgKVydTQ5ZIYMdgKQ91wD8SHvj7JAYXb2T5jmqEkhiEnHnNIESb7po72joCK/Ntray8sRD7UBkVeGoBEIQilwYFRUwFU9HmIrdzjCHtpKNJXP48FqSfIfspe2jf+Sj4P2UOMnOKQfCOE6Ye0eMdqZR+yr/fwc8icXA2QmnzCD7pCI+JiH8SFPt4KYXYjO7YTLDFYPZCensu3QakjzC2I8zcJsguZsR3IvNoOfWO2eiA+tJ9jfI9Q+qEkn1JGoCz1UW5D+/HSnFhNUCqVQjwezyUajSIUChW+02/VB42OjqJMaqCtB5qZmUEgEIDf73eEXxMEofogPtLpNHp6euDz+Xhyn+lauUm1tx81UMXzBw5wT9JYWi53AAAAAElFTkSuQmCC);
border-top:1px solid rgba(255, 165, 0,0.4);
border-bottom:1px solid rgba(255, 165, 0,0.4);
color:  rgb(225, 75, 0);
}
.alert.alert-success {
background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
border-top:1px solid limegreen;
border-bottom:1px solid limegreen;
}

.alert1{
padding: 4px 8px;
margin-bottom: 2px;
border: 1px solid transparent;
border-radius: 4px;
background:#D9EDF7!important;
height:auto;
font-weight:bold
}

.loader {
	border: 16px solid #f3f3f3;
	border-radius: 50%;
	border-top: 16px solid blue;
	border-right: 16px solid green;
	border-bottom: 16px solid red;
	border-left: 16px solid pink;
	width: 120px;
	height: 120px;
	-webkit-animation: spin 2s linear infinite;
	animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
		}
		
		@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
		}
		
</style>
@endsection
@section('content')

<div class="loader" style="position:absolute;top:366px;left:45%;z-index:2000;display:none"></div>

<div class="container">
    <div class="row">        
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">MODULO DE GESTION DE RECETAS</h3>      
						<!--
                        <div class="pull-right">
                            <a class="btn btn-default" href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia}}">Nueva Receta</a>
                            <a class="btn btn-default" href="/buscar_receta/{{ Auth::user()->id }}">Buscar Receta</a>
                            <a class="btn btn-default" href="/recetas_atendidas/{{ Auth::user()->id }}">Recetas Atendidas</a>  
                            <a class="btn btn-default" href="/stocksbyFamarcia/{{ Auth::user()->id }}">Ir Stock</a>  
                        </div>
						-->
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('registrar_receta_rua')}}" id="frmReceta" autocomplete="off">
						<!--  action="{{ route('registrar_receta_rua')}}" -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="ruta" value="<?php echo $ruta?>">
                            <div class="form-group">
                                <div class="col-md-1" align="center" style="position:relative">
                                    <!--<img src="../../images/logo.png" width="180" height="auto">-->
									<img src="../../images/logo_sanidad.png" width="70" style="position:absolute;top:-20px">
                                </div>
								
								<div class="col-md-2" style="margin-top:0px;margin-left:40px">
									<label>N° ADMISI&Oacute;N</label>
									<input type="text" value="<?php echo $admision->nro_admision?>" name="nro_admision" class="form-control alert1 alert-info" readonly="readonly" style="width:100px" >
								</div>
								
                                <div class="col-md-4" style="text-align:center">
                                    <label style="font-size: 15px">{{$establecimiento->nombre}} <?php //echo "Nivel : ".$nivel?></label><br>
                                    <label style="font-size: 24px">RECETA</label><br>
                                </div>

                                <div class="col-md-4 pull-right">
                                    <label>N° RECETA: </label>
                                    P-
                                    <input onKeyPress="return soloNumeros(event)" type="text" class="" name="nro_receta" autofocus="" id="nro_receta" tabindex="1"  style="text-transform:uppercase;"> -
                                    <input onKeyPress="return soloNumeros(event)"type="text" class="" value="" id="digitos_adicionales" name="digitos_adicionales"  tabindex="2"  style="width: 70px;">              
                                    <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">                                    
                                    <input type="hidden" value="{{$fecha_pasada}}" name="fecha_pasada" id="fecha_pasada">
                                    <input type="hidden" value="dni" name="tipo">
									
									<div style="clear:both"></div>
									<label>Total Receta : </label>&nbsp;
									<input onKeyPress="return soloNumeros(event)" type="text" value="<?php echo (isset($admision->actual_receta) && $admision->actual_receta > 0)?($admision->actual_receta+1):1?>" name="actual_receta" id="actual_receta" style="width:30px;" readonly="readonly" class="alert1 alert-info"> De
                                    <input onKeyPress="return soloNumeros(event)" type="text" value="<?php echo (isset($admision->cantidad_receta) && $admision->cantidad_receta > 0)?$admision->cantidad_receta:1?>" name="cantidad_receta"  id="cantidad_receta" style="width:30px;" tabindex="3">
									
									
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 26px">
							
								<div class="col-md-2">
                                    <label>FECHA ATENCI&Oacute;N</label>
									
									<input type="text" class="form-control alert1 alert-info" value="<?php echo $admision->fecha_admision?>" name="fecha_admision" id="fecha_admision" readonly="">
                                </div>
								
                                <div class="col-md-3">
                                    <label>TIPO ATENCI&Oacute;N</label>
									
									<input type="text" class="form-control alert1 alert-info" value="<?php echo $admision->tipo_admision?>" name="tipo_receta" id="tipo_receta"  readonly="">
                                </div>
                                <div class="col-md-5">
                                    <label>SERVICIO</label>
									<input type="text" class="form-control alert1 alert-info" value="<?php echo $admision->consultorio?>" name="consultorio"  readonly="">
									<input type="hidden" value="<?php echo $admision->id_consultorio?>" name="id_consultorio">
									
                                </div>
                                <div class="col-md-2">
                                    <label>TURNO</label>
									<input type="text" class="form-control alert1 alert-info" value="<?php echo $admision->turnos?>" name="turnos" id="turnos"  readonly="">
									<input type="hidden" value="<?php echo $admision->turno?>" name="turno" id="turno">
                                </div>
                            </div>
                            <div class="form-group">
								<?php if($admision->tipo_beneficiario!="TITULAR"):?>
                                <div class="col-md-2">
                                    <label>DNI (TITULAR)</label>
                                    <input readonly="" type="text" class="form-control alert1 alert-info" value="{{$beneficiario[0]->nrodoctitular}}" name="dni_titular"   maxlength="8">
                                </div>
                                <div class="col-md-10">
                                    <label>APELLIDOS Y NOMBRES (TITULAR)</label>
                                    <input readonly="" type="text" class="form-control alert1 alert-info" value="{{$beneficiario[0]->apepattitular}} {{$beneficiario[0]->apemattitular}}, {{$beneficiario[0]->nomtitular}}" name="paterno_beneficiario" >
                                </div>
								<!--
                                <div class="col-md-2">
                                    <label>HISTORIA CLINICA</label>
                                    <input readonly="" type="text" class="form-control alert1 alert-info" value="<?php //echo $numero_historia ?>" id="nro_historia" name="nro_historia" tabindex="16" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                </div>
								-->
                                 <div class="col-md-2">
                                    <!--<label>GRADO</label>-->
                                    <input type="hidden" class="form-control alert1 alert-info" value="<?php echo $admision->grado?>" name="grado" id="grado"  readonly="">
                                </div> 
                                <?php endif;?>
								
                            </div>
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>TIPO DE BENEFICARIO</label>
                                    <input readonly="" value="<?php echo $admision->tipo_beneficiario?>" type="text" name="tipo_beneficiario" id="" class="form-control alert1 alert-info" >
                                </div>
                                <div class="col-md-2">
                                    <label>DNI</label>
                                    <input readonly="" type="text" class="form-control alert1 alert-info" value="<?php echo $admision->dni_beneficiario?>" name="dni_beneficiario" maxlength="8">
									
                                </div>
                                <?php if($admision->tipo_beneficiario=="TITULAR"):?>
                                <div class="col-md-6">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <input readonly="" type="text" class="form-control alert1 alert-info" value="<?php echo utf8_decode($admision->paterno_beneficiario." ".$admision->materno_beneficiario.", ".$admision->nombre_beneficiario)?>" name="paterno_beneficiario" >
                                    <input type="hidden" class="form-control" value="<?php echo $admision->nombre_beneficiario?>" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="<?php echo $admision->paterno_beneficiario?>" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="<?php echo $admision->materno_beneficiario?>" name="materno_beneficiario">
                                </div> 
								
                                <div class="col-md-2">
                                    <label>HISTORIA CLINICA</label>
                                    <input <?php if($admision->nro_historia!="")echo 'readonly=""';?> type="text" class="form-control <?php if($admision->nro_historia!="")echo 'alert1 alert-info';?>" value="<?php echo $admision->nro_historia?>" id="nro_historia" name="nro_historia"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                </div>
								
                                <div class="col-md-2">
                                    <!--<label>GRADO</label>-->
                                    <input type="hidden" class="form-control alert1 alert-info" value="<?php echo $admision->grado?>" name="grado" id="grado" readonly="">
                                </div> 
                                <?php else:?>
                                <div class="col-md-6">
                                    <label>APELLIDOS Y NOMBRES</label>
									
                                    <input readonly="" type="text" class="form-control alert1 alert-info" value="<?php echo utf8_decode($admision->paterno_beneficiario." ".$admision->materno_beneficiario.", ".$admision->nombre_beneficiario)?>" name="paterno_beneficiario" >
                                    <input type="hidden" class="form-control" value="<?php echo $admision->nombre_beneficiario?>" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="<?php echo $admision->paterno_beneficiario?>" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="<?php echo $admision->materno_beneficiario?>" name="materno_beneficiario">
                                </div>
								
								<div class="col-md-2">
									<label>HISTORIA CLINICA</label>
									<input <?php if($admision->nro_historia!="")echo 'readonly=""';?> type="text" class="form-control <?php if($admision->nro_historia!="")echo 'alert1 alert-info';?>" value="<?php echo $admision->nro_historia?>" id="nro_historia" name="nro_historia" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
								</div>
								
                                <?php endif;?>
                            </div>  
							
							
							<div class="form-group" style="margin-top: 20px">
								<div class="col-md-6">
									<label>MEDICO (DNI | CMP | Nombre y Apellidos)</label>
									<input type="text" class="form-control alert1 alert-info" value="<?php echo "DNI: ".$admision->dni." - CMP: ".$admision->colegiatura." - ".$admision->nombre." ".$admision->paterno." ".$admision->materno?>" name="medico" readonly="">
									<input type="hidden" value="<?php echo $admision->id_medico?>" name="id_medico">
									
								</div>
								
								<div class="col-md-6">
                                    <label>DIAGNOSTICO</label>
									<?php if(isset($existe_cie) && $existe_cie == 1):?>
                                    <select class="form-control" name="id_diagnostico" id="id_diagnostico" required="" tabindex="4">
                                        <option value="{{$cie->id}}">{{$cie->codigo}} - {{$cie->nombre}}</option>                                        
                                    </select>
                                    <?php else:?>
									<!--
                                    <select class="form-control chosen-select" name="id_diagnostico" id="id_diagnostico" tabindex="14">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($diagnostico as $d)
                                        <option value="{{$d->id}}">{{$d->codigo}} - {{$d->nombre}}</option>
                                        @endforeach
                                    </select>
									<?php endif;?>
									-->
									<input type="text" class="form-control typeahead tt-query" id="nombre_diagnostico" name="nombre_diagnostico" tabindex="4">
									<input type="hidden" class="form-control typeahead tt-query" id="id_diagnostico" name="id_diagnostico">
									
									
                                </div>
								
								
							</div>	
								
                            <?php $exit=0;
							if(isset($flag) && $flag == 1)$exit=1;
							?>
                            <input type="hidden" class="form-control" value="<?php echo $exit ?>" name="exit" id="exit">
							<div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12" style="text-align: left; font-size: 23px">
                                    PRESCRIPCION 
                                </div>                                
                            <div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 20px; margin-left: 3px">
								<?php if(!isset($flag)):?>
                                <button type="button" id="addRow" class="btn btn-primary btn-xs" tabindex="5">
                                    <i class="fa fa-plus"></i> Agregar producto(s)
                                </button>
                                <div style="display: none">
                                   <select class="form-control" id="cmbMedicamentoTemp" style="width: 500px">
                                        <option value="">Seleccionar Producto</option>
										@foreach($producto as $p)                                                                                                
                                        	<option value="{{$p->id}}" ru="{{$p->ru}}">{{$p->codigo}} - {{$p->nombre}}, {{$p->abreviatura}}</option>         
                                        @endforeach
                                    </select>  
                                </div>
								<?php endif;?>
                                <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                <table id="example" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Producto farmacéutico o dispositivo médico</th>
                                            <th>Stock Actual</th>
											<th>Tiempo(Dias)</th>
                                            <th>Cantidad Prescrita</th>
                                            <th>Cantidad Entregada</th>
											<?php if(isset($flag) && $flag == 1):?>
											<!--<th>Add</th>-->
											<?php else:?>
                                            <th></th>
                                            <!--<th>Add</th>-->
                                            <th>Eliminar</th>
											<?php endif;?>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
									<?php if(isset($productos))://print_r($productos);?>
									@foreach($productos as $med)                                        
									<tr>
										<td>                                                
											<input type="text" class="form-control" name="" id="medicina" value="{{$med[0]}} - {{$med[1]}} - {{$med[2]}}">                                                
											<input type="hidden" class="form-control" name="id_producto[]" id="cmbMedicamento" value="{{$med[3]->id_producto}}">
										</td>
										<td>
											<input type="text" name="nro_stocks[]" required id="nro_stocks" class="form-control" style="margin-left:4px; width:100px" readonly="" value="{{$med[3]->cantidad}}"/>
											<input type="hidden" name="lotes_id[]" id="lotes_id" value="">
											<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value="">
										</td>
										<td>
											<input type="text" name="nro_med_solictados[]" required=""  id="nro_med_solictados" class="form-control" style="margin-left:4px; width:100px" value="<?php echo $med[4]?>" onKeyPress="return soloNumeros(event)"/>
										</td>
										<!--<td>
											<input type="number" name="nro_med_entregados[]" required id="nro_med_entregados" class="form-control" style="margin-left:4px; width:100px" readonly=""/>                                                
										</td>-->
										<td>
											<input type="text" name="nro_med_entregados[]"  id="nro_med_entregados" class="form-control" style="margin-left:4px; width:100px" readonly="" required=""/>                                                
										</td>
										<!--<td>
											<button data-toggle="modal" class="btn btn-success btn-xs" type="button" onclick="verlotes2({{$med[0]}},{{$id_farmacia[0]}},this)">Add</button>
										</td>-->
									</tr>
									@endforeach
									<?php endif;?>
                                    </tbody>
                                </table>
                            </div>
							</div>
							
							
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>FEC. EXPENDIO</label>
                                    <input type="text" class="form-control alert1 alert-info" value="<?php echo date("d-m-Y", strtotime($fechaServidor));?>" name="fecha_expedicion" id="fecha_expedicion">
                                </div>
								
                                <div class="col-md-4">
                                    <label>DISPENSADOR</label>
                                    <input type="text" class="form-control alert1 alert-info" value="<?php echo Auth::user()->name.", ".Auth::user()->paterno." ".Auth::user()->materno;?>" name="dispensador" id="dispensador" readonly="readonly" >
                                </div>
                                <div class="col-md-6 text-center">
                                    <label>Estado: Generado</label><br/>
                                    <label>Nro. Atenciones en el mes: <span id="cant-atenciones" class="text-danger" style="font-size: 14px;"> {{$nro_atenciones}} </span></label>&nbsp;&nbsp;
                                    <button type="button" data-toggle="modal" class="btn btn-primary btn-xs" onclick="show_Atenciones('<?php echo $admision->dni_beneficiario?>')"><i class="fa fa-list"></i> Detalle</button>

                                </div>
                                <div>
									<!--
                                    <label>N° RUA</label>
				<?php if(isset($nro_rua) && $nro_rua != ""):?>
                                    <input type="text" value="{{$nro_rua}}" name="nro_rua" class="form-control" tabindex="17" readonly="readonly" >
				<?php else:?>
				<input type="hidden" value="" name="nro_rua" class="form-control" tabindex="17" >
				<?php endif;?>
				-->
					<input type="hidden" value="<?php echo (isset($admision->id))?$admision->id:0?>" name="id_admision" class="form-control" >
					<input type="hidden" value="" name="nro_rua" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:25px">
                                <div class="col-md-6">
                                    <a href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia }}" class="form-control btn btn-danger" tabindex="8">CANCELAR</a>
                                </div>
                                <div class="col-md-6">
                                    <input id="btnReg" class="form-control btn btn-success" type="submit" value="GUARDAR" tabindex="7">                                    
                                </div>
                            </div>
							
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">            
            <form method="post" action="{{ route('reasignar_lotes')}}" id="frmlotizar">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header" id="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Dispensar</h4>
                    <div id="alerta" class="alert alert-danger"></div>
                </div>
                <div class="modal-body">
                    <table border="0px" id="tabla_lote">
                        <thead>
                            <tr>
                                <th style="width:10%">Código</th>   
                                <th style="width:40%">Nombre</th> 
                                <th style="width:10%">Lote</th> 
                                <th style="width:10%">Reg. San.</th> 
                                <th style="width:10%">Fec. ven.</th> 
                                <th style="width:10%">Cant. Actual.</th> 
                                <th style="width:10%">Cant. Entregar</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>                        
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">                        
                        <div class="btn-group" role="group">
                            <button type="button" onclick="sumarCantidadLotes()" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Agregar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    
<div class="modal fade" id="showAtencionesMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Número de atenciones en el mes actual</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>N° Receta</small></th>
                                <th><small>Fecha</small></th>
                                <th><small>Consultorio</small></th>
                                <th><small>Farmacia</small></th>
                                <th><small>Médico</small></th>
                                <th><small>Diagnóstico</small></th>
                                <th><small>Detalle</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-atenciones"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
    
<div class="modal fade" id="showProductosReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalle de Recetas</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Código</small></th>
                                <th><small>Descipción</small></th>
                                <th><small>Unidad</small></th>
                                <th><small>Pedido</small></th>
                                <th><small>Despachado</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-productos"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
    
@include('frontend.includes.footer')
@endsection