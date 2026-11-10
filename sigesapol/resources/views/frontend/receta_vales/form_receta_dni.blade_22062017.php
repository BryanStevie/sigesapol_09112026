@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<script>
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
    $consul.select2();
    $tur = $("#frmReceta").find("#turno");
    $tur.select2();
    $grado = $("#frmReceta").find("#grado");
    $grado.select2();
    $tipo = $("#frmReceta").find("#tipo_beneficiario");
    $tipo.select2();
   
    $medicos = $('#frmReceta').find("#id_medico")
    $medicos.select2();
    $tipo_receta = $('#frmReceta').find("#tipo_receta")
    $tipo_receta.select2();
    
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
        codigo=objTr.find("#cmbMedicamento").val();
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
                url: '/obtener_lotes_por_idprod_idfarma/' + codigo+ '/' + {{Auth::user()->id_farmacia}}+'/'+cantidad_prescrita, 
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
        }else{
			//if(analisis<0){
				//alert('La cantidad prescrita de '+cantidad_prescrita+' unidades es mayor al stock disponible.'); return false;
			//}else{
			 $.ajax({
					url: '/obtener_lotes_por_idprod_idfarma/' + codigo+ '/' + {{Auth::user()->id_farmacia}}+'/'+cantidad_prescrita, 
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
                    //alert(id_user);
                    option = {
                        url: 'cargarstock_farmacia/' + id_producto+ '/' + id_user,
                        type: 'GET',
                        dataType: 'json',
                        data: {}
                    };
                    $.ajax(option).done(function (data) {
                        //alert(data);                        
                        $(cmb).closest("tr").find("#nro_stocks").val(data);                        
                        $(cmb).closest("tr").find("#nro_med_solictados").val("");  
                        $(cmb).closest("tr").find("#nro_med_entregados").val("");
                        $(cmb).closest("tr").find("#lotes_lote").val("");
                        $(cmb).closest("tr").find("#lotes_cantidad").val("");
						
						$(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
						$(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
                        //alert($(cmb).closest("tr").html());
                    });
                    //alert($id_producto);
                });
                $(row).find('#dvMedicamento').append(cmbMedicamento);
                $(cmbMedicamento).select2({max_selected_options: 4});
                //alert(.html());
            },
            "language": {
                "emptyTable": "Agregar Productos Farmacéuticos o Dispositivos Médicos"
            }
        });
        var counter = 1;

        $('#example tbody').on('click', 'button.deleteFila', function () {
            //r = confirm("¿Desea eliminar el Producto Farmacéutico o Dispositivo Médico?");
			var obj = $(this);
			bootbox.confirm("¿Desea eliminar el Producto Farmacéutico o Dispositivo Médico?", function(result) {
            //if (r) {
			if (result==true) {
                t.row(obj.closest('tr')).remove().draw();
            }
			
			});

        });
        $('#addRow').on('click', function () {
            t.row.add([
                '<div id="dvMedicamento"></div>',                                
                '<input type="text" name="nro_stocks[]" required id="nro_stocks" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>               	<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/><input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/><input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/><input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/>',
                '<input onKeyPress="return soloNumerosMenosCero(event)" type="text" name="nro_med_solictados[]" required="" id="nro_med_solictados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px"/>',                
                '<input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>',
                '<input type="hidden" name="exit" id="exit" id="" style="width:10px" value="<?php $exit=1; echo $exit?>"/>',
                '<button data-toggle="modal" class="btn btn-primary btn-xs" type="button" onclick="verlotes({{Auth::user()->id_farmacia}},this)">Add</button>',                                  
                '<button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>'
            ]).draw(false);
        });

        // Automatically add a first row of data
        //$('#addRow').click();
		/*
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
							msg += "<th>Nro. Receta</th>";
							msg += "<th>Fecha Expedicion</th>";
							msg += "<th>Producto</th>";
							msg += "<th>Farmacia</th>";
                            msg += "</tr>";
                        	msg += "</thead>";
							msg += "<tbody>";
						}
						
						msg += "<tr><td>"+(result[key].nro_receta)+"</td>";
						msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
						msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
						msg += "<td>"+(result[key].farmacia)+"</td></tr>";
						n++;
					}
					if($.trim(msg) == ""){
					
						rows = $("#example tbody").children('tr');            
						//---- Validación de las cantidades prescritas y dispensadas-------------------------------------
						var i=0;
						$.each(rows, function (ii, oo) {                       
							medicina = $(oo).find("#cmbMedicamento").select2("data");                                              
							i=i+1;
							nuevaCantidad = parseFloat($(oo).find("#nro_med_entregados").val());
							cantidadPrescrita = parseFloat($(oo).find("#nro_med_solictados").val());
							stockActual = parseFloat($(oo).find("#nro_stocks").val());
							if(cantidadPrescrita==0){
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
        */
        
		$("#frmReceta").submit(function (e) {
            //rows = $("#frmReceta").children(".row");            
            rows = $("#example tbody").children('tr');            
            //e.preventDefault();             
            //---- Validación de las cantidades prescritas y dispensadas-------------------------------------
                var i=0;
                $.each(rows, function (ii, oo) {                          
                    medicina = $(oo).find("#cmbMedicamento").select2("data");
                                        
                    /*if(medicina[i].text==medicina[p].text)
                    {
                        alert("Duplicados"); exit;
                        e.preventDefault();
                    } */                                              
                    i=i+1;
                    nuevaCantidad = parseFloat($(oo).find("#nro_med_entregados").val());
                    cantidadPrescrita = parseFloat($(oo).find("#nro_med_solictados").val());
                    stockActual = parseFloat($(oo).find("#nro_stocks").val());
                    //alert(stockActual);                    
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
                //--------------------------------------------------------------
        });
		
		
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
        if ($('#frmReceta').find("#grado").val() == "") {
            //alert("Falta seleccionar el grado");
			bootbox.alert("Falta seleccionar el grado");
            return false;
        }
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
            //alert("Falta seleccionar el diagnostico (CIE10)");
			bootbox.alert("Falta seleccionar el diagnostico (CIE10)");
            return false;
        }
        
        if ($('#frmReceta').find("#id_medico").val() == "") {
            //alert("Falta seleccionar el Profesional de Salud");
			bootbox.alert("Falta seleccionar el Profesional de Salud");
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
            //alert("Falta digitar el Nro. de historia");
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
		{
			minimumInputLength: 2,
  			tags: true,
			language: { inputTooShort: function () { return 'Ingresar 2 caracteres para buscar el diagnostico'; } },

		}
	);
	
	  
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
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">        
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">MODULO DE GESTION DE RECETAS</h3>      
                        <div class="pull-right">
                            <a class="btn btn-default" href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia}}">Nueva Receta</a>
                            <a class="btn btn-default" href="/buscar_receta/{{ Auth::user()->id }}">Buscar Receta</a>
                            <a class="btn btn-default" href="/recetas_atendidas/{{ Auth::user()->id }}">Recetas Atendidas</a>  
                            <a class="btn btn-default" href="/stocksbyFamarcia/{{ Auth::user()->id }}">Ir Stock</a>  
                        </div>
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('registrar_receta_rua')}}" id="frmReceta" autocomplete="off">
						<!--  action="{{ route('registrar_receta_rua')}}" -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <img src="../../images/logo.png" width="180" height="auto">
                                </div>
                                <div class="col-md-6" style="text-align: center">
                                    <label>{{$establecimiento->nombre}}</label><br>
                                    <label style="font-size: 24px">RECETA</label><br>
                                </div>

                                <div class="col-md-4">
                                    <label>N° RECETA: </label>
                                    <!--<input type="text" name="inicial" style="width: 20px; text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">-->
                                    P-
                                    <input onKeyPress="return soloNumeros(event)" type="text" class="" name="nro_receta" autofocus="" id="nro_receta" tabindex="1"  style="text-transform:uppercase;"> -
                                    <input onKeyPress="return soloNumeros(event)"type="text" class="" value="" id="digitos_adicionales" name="digitos_adicionales"  tabindex="2"  style="width: 70px;">                                                                                                     
                                    <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">                                    
                                    <input type="hidden" value="{{$fecha_pasada}}" name="fecha_pasada" id="fecha_pasada">
                                    <input type="hidden" value="dni" name="tipo">
                                   
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label>TIPO RECETA</label>
                                    <select class="form-control" id="tipo_receta" name="tipo_receta" tabindex="3">
                                        <!--<option value="">-Seleccionar-</option>-->
                                        <option value="AMBULATORIO" selected>AMBULATORIO</option>
                                        <option value="EMERGENCIA">EMERGENCIA</option>
                                        <option value="HOSPITALIZACION">HOSPITALIZACION</option>                                        
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>SERVICIOS</label>
                                    <select class="form-control" id="id_consultorio" name="id_consultorio" tabindex="4">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($consultorio as $c)                                        
                                        <option value="{{$c->id}}">{{$c->nombre}}</option>
                                        @endforeach                                        
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>TURNO</label>
                                    <select class="form-control" name="turno" id="turno"  tabindex="5">
                                        <!--<option value="">-Seleccionar-</option>-->
                                        <option value="M">MAÑANA</option>
                                        <option value="T">TARDE</option>
                                        <option value="N">NOCHE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                @if($beneficiario[0]->parentesco!="TITULAR")
                                <div class="col-md-2">
                                    <label>DNI (TITULAR)</label>
                                    <input readonly="" type="text" class="form-control" value="{{$beneficiario[0]->nrodoctitular}}" name="dni_titular" tabindex="6"  maxlength="8">
                                </div>
                                <div class="col-md-7">
                                    <label>APELLIDOS Y NOMBRES (TITULAR)</label>
                                    <input readonly="" type="text" class="form-control" value="{{$beneficiario[0]->apepattitular}} {{$beneficiario[0]->apemattitular}}, {{$beneficiario[0]->nomtitular}}" name="paterno_beneficiario" tabindex="7">
                                </div>
                                 <div class="col-md-3">
                                    <label>GRADO</label>                                   
                                    <select class="form-control" id="grado" name="grado" tabindex="8">
                                        <option value="">--Seleccionar--</option>
                                        <option value="NINGUNO" selected="selected">NINGUNO</option>
                                        <option value="ALUMNO">ALUMNO E.T.S.</option>
                                        <option value="CADETE">CADETE E.O.</option>
                                        <option value="TENIENTE GENERAL">TENIENTE GENERAL</option>
                                        <option value="GENERAL">GENERAL</option>
                                        <option value="CORONEL">CORONEL</option>
                                        <option value="COMANDANTE">COMANDANTE</option>
                                        <option value="MAYOR">MAYOR</option>
                                        <option value="CAPITAN">CAPITAN</option>
                                        <option value="TENIENTE">TENIENTE</option>
                                        <option value="ALFEREZ">ALFEREZ</option>
                                        <option value="SUB OFICIAL SUPERIOR">SUB OFICIAL SUPERIOR</option>
                                        <option value="SUB OFICIAL BRIGADIER">SUB OFICIAL BRIGADIER</option>
                                        <option value="SUB OFICIAL TECNICO DE 1ERA">SUB OFICIAL TÉCNICO DE 1ERA</option>
                                        <option value="SUB OFICIAL TECNICO DE 2DA">SUB OFICIAL TÉCNICO DE 2DA</option>                                        
                                        <option value="SUB OFICIAL TECNICO DE 3ERA">SUB OFICIAL TÉCNICO DE 3ERA</option>
                                        <option value="SUB OFICIAL DE 1ERA">SUB OFICIAL DE 1ERA</option>
                                        <option value="SUB OFICIAL DE 2DA">SUB OFICIAL DE 2DA</option>
                                        <option value="SUB OFICIAL DE 3ERA">SUB OFICIAL DE 3ERA</option>
                                    </select>                                  
                                </div> 
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>TIPO DE BENEFICARIO</label>
                                    <input readonly="" value="{{$beneficiario[0]->parentesco}}" type="text" name="tipo_beneficiario" id="" class="form-control" tabindex="9">
                                </div>
                                <div class="col-md-2">
                                    <label>DNI</label>
                                    <input readonly="" type="text" class="form-control" value="{{$beneficiario[0]->nrodocafiliado}}" name="dni_beneficiario" tabindex="10" maxlength="8" readonly="">
                                </div>
                                @if($beneficiario[0]->parentesco=="TITULAR")<?php //print_r($beneficiario);?>
                                <div class="col-md-5">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <input readonly="" type="text" class="form-control" value="{{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}, {{$beneficiario[0]->nomafiliado}}" name="paterno_beneficiario" tabindex="9">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nomafiliado}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apepatafiliado}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apematafiliado}}" name="materno_beneficiario">
                                </div> 
                                <div class="col-md-3">
                                    <label>GRADO</label>
                                    <select class="form-control" id="grado" name="grado" tabindex="10">                                        
                                        <option value="">--Seleccionar--</option>
                                        <option value="NINGUNO" selected="selected">NINGUNO</option>
                                        <option value="ALUMNO">ALUMNO E.T.S.</option>
                                        <option value="CADETE">CADETE E.O.</option>
                                        <option value="TENIENTE GENERAL">TENIENTE GENERAL</option>
                                        <option value="GENERAL">GENERAL</option>
                                        <option value="CORONEL">CORONEL</option>
                                        <option value="COMANDANTE">COMANDANTE</option>
                                        <option value="MAYOR">MAYOR</option>
                                        <option value="CAPITAN">CAPITAN</option>
                                        <option value="TENIENTE">TENIENTE</option>
                                        <option value="ALFEREZ">ALFEREZ</option>
                                        <option value="SUB OFICIAL SUPERIOR">SUB OFICIAL SUPERIOR</option>
                                        <option value="SUB OFICIAL BRIGADIER">SUB OFICIAL BRIGADIER</option>
                                        <option value="SUB OFICIAL TECNICO DE 1ERA">SUB OFICIAL TÉCNICO DE 1ERA</option>
                                        <option value="SUB OFICIAL TECNICO DE 2DA">SUB OFICIAL TÉCNICO DE 2DA</option>                                        
                                        <option value="SUB OFICIAL TECNICO DE 3ERA">SUB OFICIAL TÉCNICO DE 3ERA</option>
                                        <option value="SUB OFICIAL DE 1ERA">SUB OFICIAL DE 1ERA</option>
                                        <option value="SUB OFICIAL DE 2DA">SUB OFICIAL DE 2DA</option>
                                        <option value="SUB OFICIAL DE 3ERA">SUB OFICIAL DE 3ERA</option>
                                    </select>                                     
                                </div> 
                                @else
                                <div class="col-md-8">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <input readonly="" type="text" class="form-control" value="{{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}, {{$beneficiario[0]->nomafiliado}}" name="paterno_beneficiario" tabindex="11">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nomafiliado}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apepatafiliado}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apematafiliado}}" name="materno_beneficiario">
                                </div>
                                @endif                                 
                            </div>  
                            <?php $exit=0; ?>
                            <input type="hidden" class="form-control" value="<?php echo $exit ?>" name="exit" id="exit">
							<div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12" style="text-align: left; font-size: 23px">
                                    PRESCRIPCION 
                                </div>                                
                            <div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 20px; margin-left: 3px">
                                <button type="button" id="addRow" class="btn btn-primary btn-xs">
                                    <i class="fa fa-plus"></i> Agregar producto(s)
                                </button>
                                <div style="display: none">
                                    <select class="form-control" id="cmbMedicamento" tabindex="12" name="tmp_id_producto[]" style="width: 500px">
                                        <option value="">Seleccionar Producto</option>                                                                                                                                                            
                                        @foreach($producto as $p)                                                                                                
                                        <option value="{{$p->id}}">{{$p->codigo}} - {{$p->nombre}}, {{$p->abreviatura}}</option>         
                                        @endforeach
                                    </select> 
                                </div>
                                <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                <table id="example" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Producto farmacéutico o dispositivo médico</th>
                                            <th>Stock Actual</th>
                                            <th>Cantidad Prescrita</th>
                                            <th>Cantidad Entregada</th>
                                            <th></th>
                                            <th>Add</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
							</div>
							
							
                            <div class="form-group">
                                <div class="col-md-5">
                                    <label>FEC. EXPEDICIÓN (R)</label>
                                    <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor));?>" name="fecha_expedicion" id="fecha_expedicion" tabindex="13">
                                </div>
								
                                <div class="col-md-7">
                                    <label>DIAGNOSTICO</label>                                    
                                    <select class="form-control chosen-select" name="id_diagnostico" id="id_diagnostico" tabindex="14">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($diagnostico as $d)
                                        <option value="{{$d->id}}">{{$d->codigo}} - {{$d->nombre}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>

                                <div class="col-md-8" style="margin-top: 20px">
                                    <label>MEDICO (DNI | CMP | Nombre y Apellidos)</label>
                                    <select class="form-control" name="id_medico"  id="id_medico" tabindex="15">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($medico as $m)
                                        <option value="{{$m->id}}">DNI: {{$m->dni}} - CMP: {{$m->colegiatura}} - {{$m->nombre}} {{$m->paterno}} {{$m->materno}}</option>
                                        @endforeach
                                    </select>
                                </div>  
                                @if($numero_historia!="")
                                <div class="col-md-2" style="text-align: center; margin-top: 20px">
                                    <label>HISTORIA CLINICA</label>
                                    <input readonly="" type="text" class="form-control" value="{{$numero_historia}}" id="nro_historia" name="nro_historia" tabindex="16" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                </div>
                                @else
                                <div class="col-md-2" style="text-align: center; margin-top: 20px">
                                    <label>HISTORIA CLINICA</label>
                                    <input type="text" class="form-control" value="" id="nro_historia" name="nro_historia" tabindex="16" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                </div>
                                @endif
                                <div class="col-md-2" style="text-align: center; margin-top: 20px">
                                    <label>N° RUA</label>
                                    <input type="text" value="" name="nro_rua" class="form-control" tabindex="17" >
                                </div>
                            </div>
							
							
                            <div class="form-group">
                                <div class="col-md-6">
                                    <a href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia }}" class="form-control btn btn-danger" tabindex="19">CANCELAR</a>
                                </div>
                                <div class="col-md-6">
                                    <input id="btnReg" class="form-control btn btn-success" type="submit" value="REGISTRAR RECETA Y CONTINUAR" tabindex="18">                                    
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
    <div class="modal-dialog">
        <div class="modal-content">            
            <form method="post" action="{{ route('reasignar_lotes')}}" id="frmlotizar">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header" id="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="lineModalLabel">Dispensar</h4>
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
@endsection