@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->

<!--
<link rel="stylesheet" href="{{asset('css/dateTimePicker/bootstrap-datetimepicker.css')}}">
<script src="{{asset('dateTimePicker/bootstrap-datetimepicker.js')}}"></script> 
-->

		
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
				
		$('#fecha_cita').datepicker({
            format: "dd-mm-yyyy",
            language: "es",
            autoclose: true,
            weekStart: 0,
            todayBtn: "linked",
        });
		/*
		$('#datetimepicker3').datetimepicker({
			format: 'LT'
		});
		*/
		$('#fecha_cita').change(function () {
			//alert($('#fecha_cita').val());
			getServicios();
		});
		
		$('#id_medico').on("select2:select", function (e) {
			if(e.params.data.id > 0){
			
			}else{
				$(this).select2('val', '');
				bootbox.alert("El medico seleccionado no tiene atenciones disponibles");
			}
			
		 });
		 
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
          
        if ($('#frmReceta').find("#nro_admision").val() == "") {
			bootbox.alert("Falta Digitar el Número de Admision");
            return false;
        }                
		/*
        if ($('#frmReceta').find("#grado").val() == "") {
			bootbox.alert("Falta seleccionar el grado");
            return false;
        }
		*/
        if ($('#frmReceta').find("#fecha_admision").val() == "") {
			bootbox.alert("Falta seleccionar la fecha de Admision");
            return false;
        }
		if ($('#frmReceta').find("#tipo_admision").val() == "") {
			bootbox.alert("Falta digitar el tipo de Admision");
            return false;
        }
		if ($('#frmReceta').find("#nro_historia").val() == "") {
			bootbox.alert("Falta digitar el Nro. de historia");
            return false;
        }
		if ($('#frmReceta').find("#id_consultorio").val() == "") {
			bootbox.alert("Falta seleccionar el Servicio");
            return false;
        }
		if ($('#frmReceta').find("#turno").val() == "") {
			bootbox.alert("Falta seleccionar el Turno");
            return false;
        }
        if ($('#frmReceta').find("#id_medico").val() == "") {
			bootbox.alert("Falta seleccionar el Profesional de Salud");
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
			item_producto += "<option value="+$(this).val()+">"+$(this).html()+"</option>"	
		});
		
		newRow +='<tr>';
		//newRow +='<div id="dvMedicamento"></div>';
		newRow +='<td><select class="form-control cmbMedicamento" id="cmbMedicamento'+ind+'" ind="'+ind+'" tabindex="12" name="id_producto[]" style="width: 500px">'+item_producto+'</select></td>';
		newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
		newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" required="" id="tiempo_periodo" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" value=""/>';
		newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
		newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
		newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
		newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
		newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" name="nro_med_solictados[]" required="" id="nro_med_solictados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px"/></td>';
		newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
		newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';
		newRow +='<td><button data-toggle="modal" class="btn btn-primary btn-xs" type="button" onclick="verlotes({{Auth::user()->id_farmacia}},this)">Add</button></td>';
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














	function getServicios(){
        
		var id_ipress 	= $('#id_ipress').val();
		var fecha_cita 	= $('#fecha_cita').val();
		$.ajax({
			url: '/obtener_servicio_por_idipress_fecha/' + id_ipress+ '/' + fecha_cita,
			success: function(result){                    
				var newOption = "";
				newOption = "<option value=''>--Seleccionar-</option>";
				$(result).each(function (ii, oo) {
					newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
				});                 
				$('#id_consultorio').html(newOption);
				$("#id_consultorio").select2({max_selected_options: 4});
			
			}
		});
        
    }
	


	function getTurno(){
        
		var id_ipress = $('#id_ipress').val();
		var id_consultorio = $('#id_consultorio').val();
		var fecha_cita 	= $('#fecha_cita').val();
		$.ajax({
			url: '/obtener_turno_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio + '/' + fecha_cita,
			success: function(result){                    
				var newOption = "";
				newOption = "<option value=''>--Seleccionar-</option>";
				$(result).each(function (ii, oo) {
					var turno = "";
					if(oo.turno == "M")turno = "MAÑANA";
					if(oo.turno == "T")turno = "TARDE";
					if(oo.turno == "N")turno = "NOCHE";
					newOption += "<option value='"+oo.turno+"'>"+turno+"</option>";
				});                 
				$('#turno').html(newOption);
				$("#turno").select2({max_selected_options: 4});
			
			}
		});
        
    }
	
	function getMedico(){
        
		var id_ipress 		= $('#id_ipress').val();
		var id_consultorio 	= $('#id_consultorio').val();
		var turno 			= $('#turno').val();
		var fecha_cita 		= $('#fecha_cita').val();
		
		$.ajax({
			url: '/obtener_medico_por_idipress_idconsultorio_turno/' + id_ipress+ '/' + id_consultorio+'/'+turno + '/' + fecha_cita,
			success: function(result){                    
				var newOption = "";
				newOption = "<option value=''>--Seleccionar-</option>";
				$(result).each(function (ii, oo) {
					//newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
					
					/*
					var atenciones_disponibles = oo.numero_atencion - oo.numero_cita;
					if(atenciones_disponibles > 0){
						newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+atenciones_disponibles+" ATENCIONES DISPONIBLE</option>";
					}else{
						newOption += "<option value='-1'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+atenciones_disponibles+" ATENCIONES DISPONIBLE</option>";
					}*/
					newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+"</option>";
					
				});
				$('#id_medico').html(newOption);
				$("#id_medico").select2({max_selected_options: 4});
			
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
		<div class="col-sm-1 col-md-1"></div>
        <div class="col-sm-10 col-md-10">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">MODULO DE GESTION DE CITA</h3>      
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('registrar_cita')}}" id="frmReceta" autocomplete="off">
						<!--  action="{{ route('registrar_receta_rua')}}" -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-2" style="position:relative">
                                    <img src="../../images/logo_sanidad.png" width="70" style="position:absolute;top:-20px">
                                </div>
                                <div class="col-md-6" style="text-align: center">
                                    <label style="font-size: 15px">{{$establecimiento->nombre}}</label><br>
                                    <label style="font-size: 24px">CITA</label><br>
                                </div>
								
                                <div class="col-md-4">
									<!--
                                    <label>N° ADMISI&Oacute;N: </label>
                                    <input onKeyPress="return soloNumeros(event)" type="text" class="" name="nro_admision" autofocus="" id="nro_admision" tabindex="1"  style="text-transform:uppercase;"> 
									-->            
                                    <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
									<input type="hidden" value="<?php echo $id_ipress?>" name="id_ipress" id="id_ipress">
									
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">                                    
                                    <input type="hidden" value="dni" name="tipo">
									
                                </div>
                            </div>
                            
                            <div class="form-group">
                                @if($beneficiario[0]->parentesco!="TITULAR")
                                <div class="col-md-3">
                                    <label>DNI (TITULAR)</label>
									<?php if($opcReceta == 1):?>
                                    <input readonly="" type="text" class="form-control" value="{{$beneficiario[0]->nrodoctitular}}" name="dni_titular" tabindex="6"  maxlength="8">
									<?php endif;?>
									<?php if($opcReceta == 2):?>
									<input type="text" class="form-control" value="{{$beneficiario[0]->dni_titular}}" name="dni_titular" tabindex="6" readonly="" maxlength="8">
									<?php endif;?>
                                </div>
                                <div class="col-md-9">
                                    <label>APELLIDOS Y NOMBRES (TITULAR)</label>
									<?php if($opcReceta == 1):?>
                                    <input readonly="" type="text" class="form-control" value="{{$beneficiario[0]->apepattitular}} {{$beneficiario[0]->apemattitular}}, {{$beneficiario[0]->nomtitular}}" name="paterno_beneficiario" tabindex="7">
									<?php endif;?>
									
									<?php if($opcReceta == 2):?>
									<input type="text" class="form-control" value="{{$beneficiario[0]->ape_paterno_titular}} {{$beneficiario[0]->ape_materno_titular}}, {{$beneficiario[0]->nombre_titular}}" name="paterno_beneficiario" tabindex="7" readonly="">
									<?php endif;?>
                                </div>
                                 
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label>TIPO DE BENEFICARIO</label>
                                    <input readonly="" value="{{$beneficiario[0]->parentesco}}" type="text" name="tipo_beneficiario" id="" class="form-control" tabindex="9">
                                </div>
                                <div class="col-md-3">
                                    <label>DNI</label>
									<?php if($opcReceta == 1):?>
                                    <input readonly="" type="text" class="form-control" value="{{$beneficiario[0]->nrodocafiliado}}" name="dni_beneficiario" tabindex="10" maxlength="8" readonly="">
									<?php endif;?>
									
									<?php if($opcReceta == 2):?>
									<input type="text" class="form-control" value="{{$beneficiario[0]->dni}}" name="dni_beneficiario" tabindex="10" required="" maxlength="8" readonly="">
									<?php endif;?>
									
                                </div>
                                @if($beneficiario[0]->parentesco=="TITULAR")
                                <div class="col-md-6">
                                    <label>APELLIDOS Y NOMBRES</label>
									<?php if($opcReceta == 1):?>
                                    <input readonly="" type="text" class="form-control" value="{{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}, {{$beneficiario[0]->nomafiliado}}" name="paterno_beneficiario" tabindex="9">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nomafiliado}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apepatafiliado}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apematafiliado}}" name="materno_beneficiario">
									<?php endif;?>
									
									<?php if($opcReceta == 2):?>
									<input type="text" class="form-control" value="{{$beneficiario[0]->ape_paterno}} {{$beneficiario[0]->ape_materno}}, {{$beneficiario[0]->nombre}}" name="paterno_beneficiario" tabindex="11" readonly="">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nombre}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->ape_paterno}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->ape_materno}}" name="materno_beneficiario">
									<?php endif;?>
									
                                </div> 
								
                                @else
                                <div class="col-md-6">
                                    <label>APELLIDOS Y NOMBRES</label>
									
									<?php if($opcReceta == 1):?>
                                    <input readonly="" type="text" class="form-control" value="{{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}, {{$beneficiario[0]->nomafiliado}}" name="paterno_beneficiario" tabindex="11">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nomafiliado}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apepatafiliado}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->apematafiliado}}" name="materno_beneficiario">
									<?php endif;?>
									
									<?php if($opcReceta == 2):?>
									<input type="text" class="form-control" value="{{$beneficiario[0]->ape_paterno}} {{$beneficiario[0]->ape_materno}}, {{$beneficiario[0]->nombre}}" name="paterno_beneficiario" id="paterno_beneficiario" tabindex="13" readonly="">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->nombre}}" name="nombre_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->ape_paterno}}" name="paterno_beneficiario">
                                    <input type="hidden" class="form-control" value="{{$beneficiario[0]->ape_materno}}" name="materno_beneficiario">
									<?php endif;?>
									
                                </div>
                                @endif
								
								
                            </div>  
							
							<div class="form-group">
							
								<div class="col-md-3">
                                    <label>PROGRAMAR CITA</label>
                                    <input type="text" class="form-control" value="<?php echo date("d-m-Y", strtotime($fechaServidor));?>" name="fecha_cita" id="fecha_cita" tabindex="13">
									
									
									<!--<input type='text' class="form-control" id="datetimepicker3" name="datetimepicker3" />-->
									<!--
									<div class='input-group date' id='datetimepicker3'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-time"></span>
										</span>
									</div>
									-->
									
									
									
                                </div>
								
                                <div class="col-md-3">
                                    <label>TIPO ATENCI&Oacute;N</label>
                                    <select class="form-control" id="tipo_cita" name="tipo_cita" tabindex="3">
                                        <!--<option value="">-Seleccionar-</option>-->
                                        <option value="AMBULATORIO" selected>AMBULATORIO</option>
                                        <option value="EMERGENCIA">EMERGENCIA</option>
                                        <option value="HOSPITALIZACION">HOSPITALIZACION</option>                                        
                                    </select>
                                </div>
                                
								<div class="col-md-3">
                                    <label>HISTORIA CLINICA</label>
                                    <input type="text" class="form-control" value="<?php echo $numero_historia?>" id="nro_historia" name="nro_historia" tabindex="16" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                </div>
								
								
								<div class="col-md-3">
                                    <label>GRADO</label>
									<?php if($beneficiario[0]->gradotit == ""):?>
                                    <select class="form-control" id="grado" name="grado" tabindex="10">                                        
                                        <option value="NINGUNO">NINGUNO</option>
                                        
										<option value="ALFÉREZ" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ALFÉREZ")echo "selected='selected'"?> >ALFÉREZ</option>
										<option value="ALFÉREZ DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ALFÉREZ DE SERVICIO")echo "selected='selected'"?> >ALFÉREZ DE SERVICIO</option>
										<option value="ALFÉREZ MAESTRO ARMERO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ALFÉREZ MAESTRO ARMERO")echo "selected='selected'"?> >ALFÉREZ MAESTRO ARMERO</option>
										<option value="CAPITÁN" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="CAPITÁN")echo "selected='selected'"?> >CAPITÁN</option>
										<option value="CAPITÁN DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="CAPITÁN DE SERVICIO")echo "selected='selected'"?> >CAPITÁN DE SERVICIO</option>
										<option value="CAPITÁN MAESTRO ARMERO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="CAPITÁN MAESTRO ARMERO")echo "selected='selected'"?> >CAPITÁN MAESTRO ARMERO</option>
										<option value="COMANDANTE" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="COMANDANTE")echo "selected='selected'"?> >COMANDANTE</option>
										<option value="COMANDANTE DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="COMANDANTE DE SERVICIO")echo "selected='selected'"?> >COMANDANTE DE SERVICIO</option>
										<option value="COMANDANTE MAESTRO ARMERO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="COMANDANTE MAESTRO ARMERO")echo "selected='selected'"?> >COMANDANTE MAESTRO ARMERO</option>
										<option value="CORONEL" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="CORONEL")echo "selected='selected'"?> >CORONEL</option>
										<option value="CORONEL DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="CORONEL DE SERVICIO")echo "selected='selected'"?> >CORONEL DE SERVICIO</option>
										<option value="ESPECIALISTA BRIGADIER" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ESPECIALISTA BRIGADIER")echo "selected='selected'"?> >ESPECIALISTA BRIGADIER</option>
										<option value="ESPECIALISTA DE PRIMERA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ESPECIALISTA DE PRIMERA")echo "selected='selected'"?> >ESPECIALISTA DE PRIMERA</option>
										<option value="ESPECIALISTA DE SEGUNDA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ESPECIALISTA DE SEGUNDA")echo "selected='selected'"?> >ESPECIALISTA DE SEGUNDA</option>
										<option value="ESPECIALISTA DE TERCERA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ESPECIALISTA DE TERCERA")echo "selected='selected'"?> >ESPECIALISTA DE TERCERA</option>
										<option value="ESPECIALISTA SUPERIOR" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ESPECIALISTA SUPERIOR")echo "selected='selected'"?> >ESPECIALISTA SUPERIOR</option>
										<option value="ESPECIALISTA TÉCNICO DE 1RA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ESPECIALISTA TÉCNICO DE 1RA")echo "selected='selected'"?> >ESPECIALISTA TÉCNICO DE 1RA</option>
										<option value="ESPECIALISTA TÉCNICO DE 2DA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ESPECIALISTA TÉCNICO DE 2DA")echo "selected='selected'"?> >ESPECIALISTA TÉCNICO DE 2DA</option>
										<option value="ESPECIALISTA TÉCNICO DE 3RA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="ESPECIALISTA TÉCNICO DE 3RA")echo "selected='selected'"?> >ESPECIALISTA TÉCNICO DE 3RA</option>
										<option value="GENERAL" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="GENERAL")echo "selected='selected'"?> >GENERAL</option>
										<option value="GENERAL DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="GENERAL DE SERVICIO")echo "selected='selected'"?> >GENERAL DE SERVICIO</option>
										<option value="MAYOR" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="MAYOR")echo "selected='selected'"?> >MAYOR</option>
										<option value="MAYOR DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="MAYOR DE SERVICIO")echo "selected='selected'"?> >MAYOR DE SERVICIO</option>
										<option value="MAYOR MAESTRO ARMERO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="MAYOR MAESTRO ARMERO")echo "selected='selected'"?> >MAYOR MAESTRO ARMERO</option>
										<option value="SUBOFICIAL BRIGADIER" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL BRIGADIER")echo "selected='selected'"?> >SUBOFICIAL BRIGADIER</option>
										<option value="SUBOFICIAL BRIGADIER DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL BRIGADIER DE SERVICIO")echo "selected='selected'"?> >SUBOFICIAL BRIGADIER DE SERVICIO</option>
										<option value="SUBOFICIAL DE PRIMERA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL DE PRIMERA")echo "selected='selected'"?> >SUBOFICIAL DE PRIMERA</option>
										<option value="SUBOFICIAL DE PRIMERA DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL DE PRIMERA DE SERVICIO")echo "selected='selected'"?> >SUBOFICIAL DE PRIMERA DE SERVICIO</option>
										<option value="SUBOFICIAL DE SEGUNDA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL DE SEGUNDA")echo "selected='selected'"?> >SUBOFICIAL DE SEGUNDA</option>
										<option value="SUBOFICIAL DE SEGUNDA DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL DE SEGUNDA DE SERVICIO")echo "selected='selected'"?> >SUBOFICIAL DE SEGUNDA DE SERVICIO</option>
										<option value="SUBOFICIAL DE TERCERA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL DE TERCERA")echo "selected='selected'"?> >SUBOFICIAL DE TERCERA</option>
										<option value="SUBOFICIAL DE TERCERA DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL DE TERCERA DE SERVICIO")echo "selected='selected'"?> >SUBOFICIAL DE TERCERA DE SERVICIO</option>
										<option value="SUBOFICIAL SUPERIOR" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL SUPERIOR")echo "selected='selected'"?> >SUBOFICIAL SUPERIOR</option>
										<option value="SUBOFICIAL SUPERIOR DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL SUPERIOR DE SERVICIO")echo "selected='selected'"?> >SUBOFICIAL SUPERIOR DE SERVICIO</option>
										<option value="SUBOFICIAL TECNICO DE 1RA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL TECNICO DE 1RA")echo "selected='selected'"?> >SUBOFICIAL TECNICO DE 1RA</option>
										<option value="SUBOFICIAL TECNICO DE 1RA DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL TECNICO DE 1RA DE SERVICIO")echo "selected='selected'"?> >SUBOFICIAL TECNICO DE 1RA DE SERVICIO</option>
										<option value="SUBOFICIAL TECNICO DE 2DA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL TECNICO DE 2DA")echo "selected='selected'"?> >SUBOFICIAL TECNICO DE 2DA</option>
										<option value="SUBOFICIAL TECNICO DE 2DA DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL TECNICO DE 2DA DE SERVICIO")echo "selected='selected'"?> >SUBOFICIAL TECNICO DE 2DA DE SERVICIO</option>
										<option value="SUBOFICIAL TECNICO DE 3RA" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL TECNICO DE 3RA")echo "selected='selected'"?> >SUBOFICIAL TECNICO DE 3RA</option>
										<option value="SUBOFICIAL TECNICO DE 3RA DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="SUBOFICIAL TECNICO DE 3RA DE SERVICIO")echo "selected='selected'"?> >SUBOFICIAL TECNICO DE 3RA DE SERVICIO</option>
										<option value="TENIENTE" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="TENIENTE")echo "selected='selected'"?> >TENIENTE</option>
										<option value="TENIENTE DE SERVICIO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="TENIENTE DE SERVICIO")echo "selected='selected'"?> >TENIENTE DE SERVICIO</option>
										<option value="TENIENTE GENERAL" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="TENIENTE GENERAL")echo "selected='selected'"?> >TENIENTE GENERAL</option>
										<option value="TENIENTE MAESTRO ARMERO" <?php if(isset($beneficiario[0]->gradotit) && $beneficiario[0]->gradotit=="TENIENTE MAESTRO ARMERO")echo "selected='selected'"?> >TENIENTE MAESTRO ARMERO</option>
                                </select>
								
								<script type="text/javascript">
								$grado = $("#frmReceta").find("#grado");
    							$grado.select2();
								</script>
								<?php else:?>
								
								<input readonly="" type="text" class="form-control" value="<?php echo $beneficiario[0]->gradotit?>" name="grado" id="grado" tabindex="6"  maxlength="8">
								
								<?php endif;?>
								
                                </div> 
								
                            </div>
							
							
							<div class="form-group">
                                <div class="col-md-3">
                                    <label>SERVICIOS</label>
                                    <select class="form-control" id="id_consultorio" name="id_consultorio" tabindex="4" onchange="getTurno()">
										<?php if($opcReceta == 1):?>
                                        <option value="">--Seleccionar--</option>
										<?php endif;?>
                                        @foreach($consultorio as $c)                                        
                                        <option value="{{$c->id}}">{{$c->nombre}}</option>
                                        @endforeach                                        
                                    </select>
                                </div>
								
								<div class="col-md-3">
                                    <label>TURNO</label>
                                    <select class="form-control" name="turno" id="turno"  tabindex="5" onchange="getMedico()">
										<option value="">--Seleccionar--</option>
                                    </select>
                                </div>
								
								<div class="col-md-6" >
                                    <label>MEDICO (DNI | CMP | Nombre y Apellidos)</label>
                                    <select class="form-control" name="id_medico"  id="id_medico" tabindex="15">
                                        <option value="">--Seleccionar--</option>
                                    </select>
                                </div>  
								
							</div>
							
							<br />
							
							<div class="form-group">
								<div class="col-md-4"></div>
								<div class="col-md-2">
								<a href="/nueva_cita" class="form-control btn btn-danger" tabindex="19">CANCELAR</a>
								</div>
								<div class="col-md-3">
								<input id="btnReg" class="form-control btn btn-success" type="submit" value="REGISTRAR CITA" tabindex="18"> 
								</div>
								<div class="col-md-4"></div>
							</div>
									
							
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection