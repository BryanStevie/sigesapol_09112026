@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<style>

.title-dialog {
  font-size: 18px;
  font-family:Arial, Helvetica, sans-serif;
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

input[readonly]{
    background-color:#eeeeee;
    text-align: center
}


span:focus,
textarea:focus,
input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus,
.uneditable-input:focus{   
  border-color: #2196F3;
  box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
  outline: 0 none;
}

/*
.select2-container:focus {
	border-color: rgba(126, 239, 104, 0.8);
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6);
	outline: 0 none;
}
*/
/*
.select2-container-active .select2-choice {
  border: 1px solid #999;
  box-shadow: inset 0 2px 3px 0 rgba(0,0,0,.06);
  border-radius: 4px;
} 
 
.select2-container-active.select2-dropdown-open .select2-choice {
  border-bottom: 2px solid #ccc;
  border-left: 1px solid #ccc;
  border-right: 1px solid #ccc;
  border-top: 1px solid #ccc;
  box-shadow: none;
}
*/

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

    $(':checkbox').click(function(){
       $("#fecha_salida").attr('disabled',!this.checked)
    });
    
    $rotacion = $("#frmReceta").find("#id_opcion");
    $rotacion.select2({
        width: 'resolve' // need to override the changed default
    });

    $consul = $("#frmReceta").find("#id_consultorio");
    $consul.select2({
        width: 'resolve' // need to override the changed default
    });
    
    $tur = $("#frmReceta").find("#turno");
    //$tur.select2();
    $tur.select2({
        width: 'resolve' // need to override the changed default
    });

    $orig = $("#frmReceta").find("#origen");
    $orig.select2({
        width: 'resolve' // need to override the changed default
    });
    
    $tipo = $("#frmReceta").find("#tipo_beneficiario");
    //$tipo.select2();
    $tipo.select2({
        width: 'resolve' // need to override the changed default
    });
   
    $medicos = $('#frmReceta').find("#id_medico")
    //$medicos.select2();
    $medicos.select2({
        width: 'resolve' // need to override the changed default
    });

    $pabellon = $("#frmReceta").find("#id_pabellon");
    $pabellon.select2({
        width: 'resolve' // need to override the changed default
    });

    $piso = $("#frmReceta").find("#id_piso");
    $piso.select2({
        width: 'resolve' // need to override the changed default
    });
   $asignacion_servicio = $("#frmReceta").find("#id_asignacion_servicio");
    $asignacion_servicio.select2({
        width: 'resolve' // need to override the changed default
    });
    $cuarto = $("#frmReceta").find("#id_cuarto");
    $cuarto.select2({
        width: 'resolve' // need to override the changed default
    });
    $cama = $("#frmReceta").find("#id_cama");
    $cama.select2({
        width: 'resolve' // need to override the changed default
    });

    $cama2 = $("#frmReceta").find("#id_cama2");
    $cama2.select2({
        width: 'resolve' // need to override the changed default
    });

    
	jQuery(document).on('focus', '.select2', function() {
    	jQuery(this).siblings('select').select2('open');
	});


// return the id of the next focusable field
function getNextFocusableFieldId(idIn) {
    var focusables = jQuery("input, select, textarea");
    var reachedId = false;
    var id = '';
    var nextId = '';
    jQuery.each(focusables, function(index, value) {
        id = jQuery(this).attr('id');
        // if we reached the id last time set the nextId and exit each
        if (reachedId) {
            nextId = id;
            return false;
        }
        // if the ids match set the flag for the next iteration
        if (id == idIn) {
            reachedId = true;
        }
    });
    return nextId;
}

	
	
    function sumarCantidadLotes(opc){
        
        trs=$('#tabla_lote tbody').children('tr');                
        total=0;
        lote_lote=[];
	lote_registro_sanitario=[];
        lote_precio_unitario=[];
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
            precio_unitario=$(oo).find('#precio_unitario').val();
            fecha_vencimiento=$(oo).find('#fecha_vencimiento').val();
            lote=$(oo).find('#lote').val();
            if(valor!=0 && valor!=''){
                total+=parseFloat(valor);
                lote_lote.push(lote);
		lote_registro_sanitario.push(registro_sanitario);
                lote_precio_unitario.push(precio_unitario);
		lote_fecha_vencimiento.push(fecha_vencimiento);
                lote_cantidad.push(valor);
            }            
        });
        //alert(total);                
        fila_medicamentos=$('#example tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        fila_medicamento=fila_medicamentos[rowIndex];
        cantidad_prescrita=$(fila_medicamento).find('.nro_med_solictados').val();
        if(total>cantidad_prescrita)
        {
            //alert("Error: el Total digitado("+total+") es mayor a la cantidad prescrita("+cantidad_prescrita+")");
			bootbox.alert("Error: el Total digitado("+total+") es mayor a la cantidad prescrita("+cantidad_prescrita+")");
            return false;
        }        
        $(fila_medicamento).find('#nro_med_entregados').val(total);
        cadena_lote_lote=lote_lote.join("||");
        cadena_lote_registro_sanitario=lote_registro_sanitario.join("||");
        cadena_lote_precio_unitario=lote_precio_unitario.join("||");
	cadena_lote_fecha_vencimiento=lote_fecha_vencimiento.join("||");
	cadena_lote_cantidad=lote_cantidad.join("||");
		
		
        $(fila_medicamento).find('#lotes_lote').val(cadena_lote_lote);
	$(fila_medicamento).find('#lotes_registro_sanitario').val(cadena_lote_registro_sanitario);
        $(fila_medicamento).find('#lotes_precio_unitario').val(cadena_lote_precio_unitario);
	$(fila_medicamento).find('#lotes_fecha_vencimiento').val(cadena_lote_fecha_vencimiento);
        $(fila_medicamento).find('#lotes_cantidad').val(cadena_lote_cantidad);
        //alert(cadena_lote_lote);
        //alert(cadena_lote_cantidad);
        $('#squarespaceModal').modal('hide');
		$(fila_medicamento).find('.nro_med_solictados').focus();
		if(opc==1)AddFila();
		setTimeout(function(){$(fila_medicamento).find('.nro_med_solictados').focus();}, 10);
		
    }

    function verlotes(id_farmacia, btn){
        objTr = $(btn).closest("tr");        
        rowIndex = $('#example tbody').children('tr').index(objTr);
        $('#hidden_rowIndex').val(rowIndex);
        //codigo=objTr.find("#cmbMedicamento").val();
		codigo=objTr.find(".cmbMedicamento").val();
        cantidad_prescrita=objTr.find(".nro_med_solictados").val();
        if(cantidad_prescrita==""){
            //alert('Es necesario digitar la cantidad prescrita'); return false;
			bootbox.alert('Es necesario digitar la cantidad prescrita'); return false;
        }
        if(cantidad_prescrita==0){
            //alert('La Cantidad prescrita debe ser mayor a cero'); return false;
			bootbox.alert('La Cantidad prescrita debe ser mayor a cero'); return false;
        }
        tiempo_periodo=objTr.find(".tiempo_periodo").val();
        if(tiempo_periodo==""){
            //alert('La Cantidad prescrita debe ser mayor a cero'); return false;
			bootbox.alert('Es necesario digitar el Tiempo(Días)'); return false;
        }
        
        cantidad_stock_actual=objTr.find("#nro_stocks").val();
        analisis=cantidad_stock_actual-cantidad_prescrita;
        if(cantidad_stock_actual==0){ // Aqui no toco nada 
            $("#alerta").html("");
            $("#alerta").append(
                'El Producto no contiene stock. Dar click en cualquier <b style="color:green">botón</b> para continuar con la atención.'
            );
            $.ajax({
                url: '/obtener_lotes_por_idprod_idfarma/' + codigo+ '/' + {{Auth::user()->id_farmacia}}+'/'+cantidad_prescrita, 
                async: false,
                success: function(result){
                    $("#tabla_lote tbody").html("");
                    $(result).each(function (ii, oo) {                       
                        $("#tabla_lote tbody").append(
                            '<tr style="height:30px"> \n\
                                <td style="width:15%"><input type="hidden" value="'+ oo.lote +'" id="lote"><input type="hidden" value="'+ oo.registro_sanitario +'" id="registro_sanitario"><input type="hidden" value="'+ oo.precio_unitario +'" id="precio_unitario"><input type="hidden" value="'+ oo.fecha_vencimiento +'" id="fecha_vencimiento">' + oo.codigo_producto + '</td>\n\
                                <td style="width:33%">' + oo.prod_farmaceutico + '</td>\n\
                                <td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
                                <td style="width:13%">' + oo.registro_sanitario + '</td>\n\
                                <td style="width:12%">' + oo.precio_unitario + '</td>\n\
                                <td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
                                <td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="'+ oo.cantidad +'" style="width:90px"></td>\n\
                                <td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="" style="width:90px" class="text-center"></td>\n\
                            </tr>'
                        );
                    });                                         
                },
                complete: function () {
                    setTimeout(function(){sumarCantidadLotes(1)}, 150);
                }
            });
        }else{//Aqui si tengo sacar las sumas empecemos
			//if(analisis<0){
				//alert('La cantidad prescrita de '+cantidad_prescrita+' unidades es mayor al stock disponible.'); return false;
			//}else{
			 $.ajax({
					url: '/obtener_lotes_por_idprod_idfarma/' + codigo+ '/' + {{Auth::user()->id_farmacia}}+'/'+cantidad_prescrita, 
                                        async: false,
					success: function(result){                    
						$("#tabla_lote tbody").html("");
                                                var cntxlote = 0;
                                                var canting = 0;
                                                var cantactu = 0;
                                                cntxlote = cantidad_prescrita;
						$(result).each(function (ii, oo) {
                                                        if(cntxlote != 0){
                                                            cantactu = oo.cantidad;
                                                            if (cantactu >= cntxlote){
                                                                canting = cntxlote;
                                                                cntxlote = 0;
                                                            } else {
                                                                canting = cantactu;
                                                                cntxlote = cntxlote - cantactu;
                                                            }
                                                        } else {
                                                            canting = 0;
                                                        }
							$("#tabla_lote tbody").append(
								'<tr style="height:30px"> \n\
									<td style="width:15%"><input type="hidden" value="'+ oo.lote +'" id="lote"><input type="hidden" value="'+ oo.registro_sanitario +'" id="registro_sanitario"><input type="hidden" value="'+ oo.precio_unitario +'" id="precio_unitario"><input type="hidden" value="'+ oo.fecha_vencimiento +'" id="fecha_vencimiento">' + oo.codigo_producto + '</td>\n\
									<td style="width:33%">' + oo.prod_farmaceutico + '</td>\n\
									<td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
									<td style="width:13%">' + oo.registro_sanitario + '</td>\n\
                                                                        <td style="width:12%">' + oo.precio_unitario + '</td>\n\
									<td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
									<td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="' + oo.cantidad + '" style="width:90px"></td>\n\
									<td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="'+canting+'" style="width:90px"></td>\n\
								</tr>'
							);
						});
						if(cantidad_prescrita>=10){
							$("#alerta").html("");
							$("#alerta").append(
								//'Cantidad Prescrita: '+cantidad_prescrita+' unidades.<br>Advertencia: La cantidad prescrita es superior a 10.'--Wilian
                                                                'Cantidad Prescrita: '+cantidad_prescrita+' unidad(es).'
							);
						}else{
							$("#alerta").html("");
							$("#alerta").append(
								'Cantidad Prescrita: '+cantidad_prescrita+' unidad(es).'
							);                        
						}
					},
                                        complete: function () {
                                            setTimeout(function(){sumarCantidadLotes(1)}, 150);         
                                        }
				});
			
        }
        
             /*       $('#squarespaceModal').modal({
                        show: true,
                        backdrop: 'static',
                    });
               */                                             
        //sumarCantidadLotes(1);
        
        
    } 
	
	function verlotes2(cod_producto, id_farmacia, btn){
        objTr = $(btn).closest("tr");        
        rowIndex = $('#example tbody').children('tr').index(objTr);
        $('#hidden_rowIndex').val(rowIndex);
        //alert(cod_producto); return false;
        //codigo_real=objTr.find("#cmbMedicamento").val();
		codigo_real=objTr.find(".cmbMedicamento").val();
        //alert(codigo_real); return false;
        cantidad_prescrita=objTr.find(".nro_med_solictados").val();
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
    
    
        $('#squarespaceModal').modal({
            show: true,
            backdrop: 'static',
        });
        
    }
	
	   
    //-------------------AGREGAR PRODUCTOS--------------------------------------
    $(document).ready(function () {
		
		__autocomplete__AjaxBusqueda();
		//$('[data-toggle="tooltip"]').tooltip(); 

        var counter = 1;

        $('#example tbody').on('click', 'button.deleteFila', function () {
            var obj = $(this);
            //bootbox.confirm("¿Desea eliminar el Producto Farmacéutico o Dispositivo Médico?", function(result) {
            //if (result==true) {
                obj.parent().parent().remove();
            //}
            //});
            //
            //r = confirm("¿Desea eliminar el Producto Farmacéutico o Dispositivo Médico?");//Anterior
            //if (r) {
            //t.row(obj.closest('tr')).remove().draw();
			
        });
        
        $('#addRow').on('click', function () {
			AddFila();
			
        });

        // Automatically add a first row of data
        //$('#addRow').click();
		
		
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
 
    
    
    $("#nro_historia").on("keydown", function (e) {
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        nro_documento=$('#frmReceta').find("#dni_beneficiario").val();
        nro_historia=$('#frmReceta').find("#nro_historia").val();
        var code = e.keyCode || e.which;
        if (code == '9' || code == '13') {
            validar_hc_duplicada(id_farmacia, nro_documento, nro_historia);
        }
    });

    function validar_hc_duplicada(id_farmacia, nro_documento, nro_historia){
            if(nro_historia == "") return false;
            option = {              
                url: '/validar_hc_duplicada/' + id_farmacia+ '/' + nro_documento+ '/' + nro_historia,
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                existe=data;
                if(existe>0){
                    nro_historia=$('#frmReceta').find("#nro_historia").val();
                    bootbox.alert("El Número de Historia Clínica: " + nro_historia + " se encuentra actualmente registrado con otro Beneficiario");
                }
            });     
        }


    //--------------------------------------------------------------------------
    
    function validar(nro_receta, id_farmacia){
            var consultorio = $("#id_consultorio option:selected").text();
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
                    /*$('#frmReceta').find("#digitos_adicionales").val("");
                    $('#frmReceta').find("#nro_receta").val("");
                    bootbox.alert("La receta: " + nro_receta+ " se encuentra actualmente registrada");
                    */
                    bootbox.confirm({
                         message: "Aviso: La receta: " + nro_receta+ " se encuentra actualmente registrada. ¿Desea Continuar?.",
                         className: 'title-dialog',
                         buttons: {
                             confirm: {
                                 label: 'Si',
                                 className: 'btn-success btn-lg'
                             },
                             cancel: {
                                 label: 'No',
                                 className: 'btn-danger btn-lg'
                             }
                         },
                         callback: function (result) {
                             if (result==true) {
                                 genera_receta_duplicado();
                             } else {
                                 $('#frmReceta').find("#nro_receta").val("");
                                 $('#nro_receta').focus();
                             }
                         }
                     });
                }
            });     
        }
        
    function validar_receta_fisanulada(nro_receta, id_farmacia){
            option = {
                url: '/evitar_registro_recetas_anuladas_by_fiscalizador/' + nro_receta+ '/' + id_farmacia,
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                existe=data; 
                if(existe==0){
                    $('#frmReceta').find("#digitos_adicionales").val("");
                    $('#frmReceta').find("#nro_receta").val("");
                    $('#frmReceta').find("#nro_receta").focus();
                    //alert("La receta: " + nro_receta+ " se encuentra actualmente registrada");
                    bootbox.alert("La receta: " + nro_receta+ " no fue anulada por su persona o no existe dicho número");
                    e.preventDefault();
                }
            });     
        }

    //-------------------------------------------------------------
    function validar_rec(nro_receta, id_farmacia){
        var consultorio = $("#id_consultorio option:selected").text();
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
                    $('#frmReceta').find("#nro_receta").focus();
                    bootbox.alert("La receta: " + nro_receta+ " se encuentra actualmente registrada");
                    $('#btnReg').prop('disabled', false);
                    return false;
                } else {
                    validate_otros();
                }
            });     
        }
    
    function validateFrm(){
        val_nro_rec=$('#frmReceta').find("#valid_nro_receta").val();
        $('#btnReg').prop('disabled', true);
        nro_receta='P-'+$('#frmReceta').find("#nro_receta").val()+'-'+$('#frmReceta').find("#digitos_adicionales").val();
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        id_consultorio=$('#frmReceta').find("#id_consultorio").val();
        
        validar_rec(nro_receta, id_farmacia);
        
    }
    
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
    function validate_otros(){
         
        opcion=$('#frmReceta').find("#id_opcion").val();

        if(opcion=='2'){
            if ($('#frmReceta').find("#id_consultorio").val() == "") {
                bootbox.alert("Falta seleccionar la Sala");
                $('#btnReg').prop('disabled', false);
                return false;
            }
            if ($('#frmReceta').find("#turno").val() == "") {
                bootbox.alert("Falta seleccionar el Turno");
                $('#btnReg').prop('disabled', false);
                return false;
            }

            if ($('#frmReceta').find("#id_medico").val() == "") {
                bootbox.alert("Falta seleccionar el Medico");
                $('#btnReg').prop('disabled', false);
                return false;
            }
            if ($('#frmReceta').find("#id_cama").val() == "") {
                bootbox.alert("Falta seleccionar la Cama");
                $('#btnReg').prop('disabled', false);
                return false;
            }
            
        }
        else{
            if(opcion=='1'){
                if ($('#frmReceta').find("#id_cama2").val() == "") {
                    bootbox.alert("Falta ingresar la Cama");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }
            }else
            {
                bootbox.alert("Falta seleccionar el tipo de rotacion de cama");
                $('#btnReg').prop('disabled', false);
                return false;
            }

        }
        
        

        submitFrm();
        return false;
    };
    
    function submitFrm() {
      bootbox.confirm({
          message: "Revise que los datos se encuentren correctos antes de registrar. ¿Desea Grabar?.",
          className: 'title-dialog',
          buttons: {
              confirm: {
                  label: 'Si',
                  className: 'btn-success btn-lg'
              },
              cancel: {
                  label: 'No',
                  className: 'btn-danger btn-lg'
              }
          },
          callback: function (result) {
              if (result==true) {
                  $("#frmReceta").submit();
              } else {
                  $('#btnReg').prop('disabled', false);
              }
          }
      });
    }
    //--------------------------------------------------------------------------
	
	
	
	 
	 function AddFilaMed(){
            if($('#id_medico').val()=="") return false;
            var newRow = "";
            var ind = $('#example tbody tr').length;
            if(ind == 0){
               AddFila();	
            } 
         }
	function AddFila(){
                var tipo_receta = $('#tipo_receta').val();
                var id_consultorio = $('#id_consultorio').val();
		
                if(tipo_receta == ""){
                    bootbox.alert("Seleccione el tipo atención");
                    return false;
                }		
                if(id_consultorio == ""){
                    bootbox.alert("Seleccione un servicio");
                    return false;
                }
		var newRow = "";
		var ind = $('#example tbody tr').length;
		var tabindex = 11;
                var nuevalperiodo = "";
		if(ind > 0){
			$('.cmbMedicamento').each(function(){
				var ind_tmp = $(this).attr("ind");
				if(ind_tmp => ind){
                                    ind=Number(ind_tmp)+1;
                                    tabindex+=3;
				}
			});
                        
                    fila_medicamentos=$('#example tbody').children('tr');
                    rowIndex=$('#hidden_rowIndex').val();
                    var idval = ind - 1;
                    fila_medicamento=fila_medicamentos[idval];
                    var valante = $(fila_medicamento).find('#nro_med_entregados').val();
                    if(valante == ""){
                        bootbox.alert("Ingrese cantidad entregada en el producto anterior");
                        return false;
                    }
                    nuevalperiodo = document.getElementById('tiempo_periodo'+idval).value;
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
		newRow +='<td><select class="form-control cmbMedicamento" id="cmbMedicamento'+ind+'" ind="'+ind+'" tabindex="'+tabindex+'" name="id_producto[]" style="width: 500px">'+item_producto+'</select></td>';
		newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
		<?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
		newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="'+(tabindex+1)+'" required="" id="tiempo_periodo'+ind+'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="'+nuevalperiodo+'"/>';
		<?php endif;?>
		newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
		newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
		newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
                newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
		newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
		newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="'+(tabindex+2)+'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados'+ind+'" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';
		newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
		newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';
		//newRow +='<td><button data-toggle="modal" class="btn btn-primary btn-xs" type="button" onclick="verlotes({{Auth::user()->id_farmacia}},this)">Add</button></td>'; //Wilian
                //newRow +='<td><button data-toggle="modal" class="btn btn-primary btn-xs" type="button" onclick="validproductoseleccionado({{Auth::user()->id_farmacia}},this)">Add</button></td>';//Jose
		newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
		newRow +='</tr>';
		//alert(newRow);
		$('#example tbody').append(newRow);
		$("#cmbMedicamento"+ind).select2({max_selected_options: 4}).focus();
		
		//$("#cmbMedicamento"+ind).on("focus");
		
		/*
		$(document).keypress(function(e) {
			if(e.which == 13) {
				AddFila();
			}
		});
		*/
		$("#nro_med_solictados"+ind).keypress(function(e){
			if(e.which == 13) {
				validproductoseleccionado({{Auth::user()->id_farmacia}},this);
			}
		});
		
		//$("#nro_med_solictados"+ind).tooltip();
		
		/*
		var bootstrapTooltip = $.fn.tooltip.noConflict();
	 	$.fn.bstooltip = bootstrapTooltip;
	 	$("#nro_med_solictados"+ind).bstooltip();
		*/
		
		//$('[data-toggle="tooltip"]').tooltip();
		
		$("#cmbMedicamento"+ind).on("change", function (e) {
			var flagx = 0;
			cmb = $(this);
			id_producto = $("#cmbMedicamento"+ind).val();
			id_user={{Auth::user()->id}};
			/*
			var ru = $("#cmbMedicamento"+ind+ " option:selected").attr("ru");
			//alert(ru);
			if(ru != ""){
				//bootbox.alert("El producto farmaceutico es de uso restringido", function(){ $("#tiempo_periodo"+ind).focus(); });
				
				bootbox.dialog({
					message: "El producto farmaceutico es de uso restringido",
					title: 'Mensaje',
					buttons: {
						danger: {
							label: 'ok',
							className: "btn-primary",
							 callback: function () {
								 setTimeout(function(){
									 $("#tiempo_periodo"+ind).focus();
								 }, 10);
							 }
						}
					}
				});
				
				//bootbox.alert(msg, function(){ location.href="/formulario_importar_stock/"+id_usuario; });
				//$("#tiempo_periodo"+ind).focus();
			}
			*/
			
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
                                $(cmb).closest("tr").find(".limpia_text").val("");
                                
				$(cmb).closest("tr").find("#nro_stocks").val(data);
				<?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
				//$(cmb).closest("tr").find(".tiempo_periodo").val("");
                                $(cmb).closest("tr").find(".tiempo_periodo").focus();
				<?php endif;?>
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
        
        function show_Atenciones(dni){
            var dni_beneficiario = dni;
            var newOption = "";
            $.ajax({
                url: '/obtenerRecetasMesActualByBeneficiario/' + dni_beneficiario,
                success: function (result) {
                    var newOption = "";
                    var i = 1;
                    var colspan = "";
                    var nroaten = 1;
                    var mesactu = parseInt("<?php echo date("m")?>");
                    $(result).each(function (ii, oo) {
                        var colspan = "rowspan="+oo.colspan;
                        var fecexpe = oo.fecha_registro;
                        var res = fecexpe.split("-");
                        var mesexpe = parseInt(res[1]);
                        
                        var btn = "<button type=\"button\" data-toggle=\"modal\" class=\"btn btn-primary btn-xs\" onclick=\"show_Det_Atenciones('" + oo.id_receta_vale + "')\"><i class=\"fa fa-list\"></i></button>";
                        newOption += "<tr><td><small>" + oo.nro_receta + "</small></td><td><small>" + oo.fecha_expedicion + "</small></td><td><small>" + oo.fecha_registro + "</small></td><td><small>" + oo.nom_consultorio + "</small></td><td><small>" + oo.nom_farmacia + "</small></td><td><small>" + oo.apepat_medico + " " + oo.apemat_medico + " " + oo.nom_medico + "</small></td><td><small>" + oo.nom_diagnostico + "</small></td>";
                        if(i == 1){
                            if (mesactu > mesexpe){
                                newOption += "<td class=\"text-center\" "+ colspan +" style=\"vertical-align: middle\"><small>-</small>";
                            } else {
                                newOption += "<td class=\"text-center\" "+ colspan +" style=\"vertical-align: middle\"><small>" + nroaten + "</small>";
                                nroaten = nroaten + 1;                                
                            }
                        }
                        
                        newOption += "<td class=\"text-center\" style=\"vertical-align: middle\">" + btn + "</td></tr>";
                        
                        if(i == oo.colspan){
                                i=1;
                                colspan = "";
                        }else{
                                i = i + 1;
                        };
                        
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
          
        
        
            function select2Focus() {
                    var select2 = $(this).data('select2');
                    setTimeout(function() {
                            if (!select2.opened()) {
                                    select2.open();
                            }
                    }, 0);  
            }

	function limpiaDatos()
  {
            
            $("#turno").val('').trigger("change");
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");

  }
	function getMedico(){
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");
            
            if($('#turno').val()=="") return false;
        
		var id_ipress 		= $('#id_ipress').val();
    var id_origen   = $('#origen').val();
		//var id_consultorio 	= $('#id_consultorio').val();
		var turno 		= $('#turno').val();
		var fecha_admision 	= $('#fecha_atencion').val();
		
		$.ajax({
			url: '/obtener_medico_por_idipress_idconsultorio_turno_origen/' + id_ipress+ '/' + id_origen+'/'+turno + '/' + fecha_admision,
			success: function(result){                    
				var newOption = "";
				newOption = "<option value=''>--Seleccionar-</option>";
				$(result).each(function (ii, oo) {
					newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
				});                 
				$('#id_medico').html(newOption);
				$("#id_medico").select2({max_selected_options: 4});
			
			}
		});
        
    }
	
	function getProducto(){
            $("#turno").val('').trigger("change");
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");
            
            $('#example tbody').html('');
            
            if($('#id_consultorio').val()=="") return false;
        
		var id_ipress 		= $('#id_ipress').val();
		var id_consultorio 	= $('#id_consultorio').val();
		var tipo_receta		= $('#tipo_receta').val();
		
		if(tipo_receta == "AMBULATORIO"){
		
			$.ajax({
				url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
				success: function(result){                    
					var newOption = "";
					newOption = "<option value=''>--Seleccionar-</option>";
					$(result).each(function (ii, oo) {
						newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
					});                 
					$('#cmbMedicamentoTemp').html(newOption);
					//$("#id_medico").select2({max_selected_options: 4});
				
				}
			});
		
        }
    }

    
  function getSala(){
      var id_consultorio = $('#id_consultorio').val();  

      var id_ipress     = $('#id_ipress').val();  
      $.ajax({
        url: '/obtener_cama_disponible_sala/'+ id_ipress,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            if(id_consultorio==oo.id_consultorio){
              newOption += "<option selected value='"+oo.id_consultorio+"'>"+oo.nombre+"</option>";

            }
            else
            {
              newOption += "<option value='"+oo.id_consultorio+"'>"+oo.nombre+"</option>";  
            }
          });                 
          $('#id_sala').html(newOption);
        }
      });

      
      var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");
            
            if($('#turno').val()=="") return false;
        
      var id_ipress     = $('#id_ipress').val();
      var id_consultorio  = $('#id_consultorio').val();
      var fecha_admision  = $('#fecha_atencion').val();
      
      $.ajax({
            url: '/obtener_medico_por_idipress_idconsultorio_solo_emergencia/' + id_ipress+ '/' + 76,
            success: function(result){                    
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
              });                 
              $('#id_medico').html(newOption);
              $("#id_medico").select2({max_selected_options: 4});
            
            }
          });
      
      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
      $('#id_cama').html(newOption);
      $("#id_cama").val('').trigger("change");
      
      if($('#id_consultorio').val()=="") return false;

      var id_consultorio= $('#id_consultorio').val();
      
      $.ajax({
        url: '/obtener_cama_por_idcuarto/' + id_consultorio,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"'>"+oo.nombre_cuarto+" -  "+oo.nombre_cama+"</option>";
          });                
          $('#id_cama').html(newOption);
        
        }
      });      
    }

	
    $(document).ready(function () {

        modalHistorial();

        $('#fecha_atencion').click(function(){
            var fec_expedicion = $('#fecha_expedicion').val();
            if(fec_expedicion == ""){
                alert('ingrese la fecha de expedición.');
                $('#fecha_expedicion').focus();
            }
        });
        
        $('#fecha_expedicion').click(function(){
            var fecha_atencion = $('#fecha_atencion').val();
            if(fecha_atencion == ""){
                alert('ingrese la fecha de atención.');
                $('#fecha_atencion').focus();
            }
        });
 
 
 
         $('#fecha_atencion').datepicker().on('hide', function (ev) {
            var fechaFinIni = $('#fecha_expedicion').val();
            var fechaIni = $('#fecha_atencion').val();
            f1 = fechaIni.split("-");
            var fechaFin = $('#fecha_expedicion').val();
            f2 = fechaFin.split("-");
            f3 = new Date();
            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
            
            var diasdif= f2.getTime()-f1.getTime();
            var contdias = Math.round(diasdif/(1000*60*60*24));
            
            if (f1 > f2) {
                bootbox.alert('La Fecha de Expedición debe ser menor o igual a la fecha Dispensación.');
            }
            
            if (f1 > f3) {
                 bootbox.alert('La Fecha de Expedición debe ser menor o igual a la fecha Actual.');
            }
            
            if (contdias >= 30){
                bootbox.alert('Existe una diferencia mayor a 30 días entre la fecha de Expedición y la fecha de Dispensación, Verifique por favor.');
            }
            
            $("#id_consultorio").val('').trigger("change");
            $("#turno").val('').trigger("change");
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");
           
            $('#fecha_expedicion').focus();
            
        });
       
        $('#fecha_expedicion').change(function(){
            var fec_atencion = $('#fecha_atencion').val();
            var fec_expedicion = $('#fecha_expedicion').val();
            
            f1 = fec_atencion.split("-");
            f2 = fec_expedicion.split("-");
            f3 = new Date();

            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014
            
            var diasdif= f2.getTime()-f1.getTime();
            var contdias = Math.round(diasdif/(1000*60*60*24));

            if (f2 < f1) {
                 alert('La Fecha de Dispensación debe ser mayor o igual a la fecha Expedición.');
                 $('#fecha_expedicion').val('');
            }
            
            if (f2 > f3) {
                 alert('La Fecha de Dispensación debe ser menor o igual a la fecha Actual.');
                 $('#fecha_expedicion').val('');
            }

            if (contdias >= 30){
                alert('Existe una diferencia mayor a 30 días entre la fecha de Expedición y la fecha de Dispensación, Verifique por favor.');
            }
            
            $('#fecha_expedicion').focus();
       });
    });
	
    function ocultar_titu(){
        if($("#muestra-datos-titu").is(':hidden')){
            $('#muestra-datos-titu').show();
        } else {
            $('#muestra-datos-titu').hide();
        }
    }
    
    /*esta es la función estática*/
    function calcular(fecini,fecfin){
            var fechaini = new Date(fecini);
            var fechafin = new Date(fecfin);
            var diasdif= fechafin.getTime()-fechaini.getTime();
            var contdias = Math.round(diasdif/(1000*60*60*24));
            alert(contdias);
    }


    function getPiso(){
      var id_pabellon = $('#id_pabellon').val();
      $.ajax({
        url: '/obtener_piso_por_idpabellon/' + id_pabellon,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
          });                 
          $('#id_piso').html(newOption);
          //$("#turno").select2({max_selected_options: 4});
        
        }
      });
    }
  
  function getConsultorio(){
      var id_piso = $('#id_piso').val();    
      $.ajax({
        url: '/obtener_consultorio_por_idpiso/' + id_piso,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
          });                 
          $('#id_asignacion_servicio').html(newOption);
        
        }
      });
    }
  
  function getCuarto(){
    var id_asignacion_servicio = $('#id_consultorio').val();
    //var id_asignacion_servicio = 1;
      $.ajax({
        url: '/obtener_cuarto_por_idasignacionservicio/' + id_asignacion_servicio,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
          });                 
          $('#id_cuarto').html(newOption);
        
        }
      });
    }
  
  function getCama(){

    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";
    $('#id_cama').html(newOption);
    $("#id_cama").val('').trigger("change");
    
    if($('#id_consultorio').val()=="") return false;

    var id_consultorio= $('#id_consultorio').val();
    
    $.ajax({
      url: '/obtener_cama_por_idcuarto/' + id_consultorio,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"'>"+oo.nombre_cuarto+" -  "+oo.nombre_cama+"</option>";
        });                
        $('#id_cama').html(newOption);
      
      }
    });
        
    }

function getCama2(){

    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";
    $('#id_cama').html(newOption);
    $("#id_cama").val('').trigger("change");
    
    if($('#id_consultorio').val()=="") return false;

    var id_consultorio= $('#id_sala').val();
    
    $.ajax({
      url: '/obtener_cama_por_idcuarto/' + id_consultorio,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"'>"+oo.nombre_cuarto+" -  "+oo.nombre_cama+"</option>";
        });                
        $('#id_cama').html(newOption);
      
      }
    });
  }

  function getCama3(){

    var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
      $('#id_cama2').html(newOption);
      $("#id_cama2").val('').trigger("change");
      
      if($('#id_consultorio').val()=="") return false;

      var id_consultorio= $('#id_consultorio').val();
      
      $.ajax({
        url: '/obtener_cama_por_idcuarto/' + id_consultorio,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"'>"+oo.nombre_cuarto+" -  "+oo.nombre_cama+"</option>";
          });                
          $('#id_cama2').html(newOption);
        
        }
      });

  }

    function getRotacion(){
        var id_opcion=  $("#id_opcion").val();

        if(id_opcion=='1'){ //Rotacion
            $("#ver_cama").show();
            $("#ver_cambio").hide();
            getCama3();
        
        }else{ //HOSPITALIZACIÓN  
            $("#ver_cama").hide();
            $("#ver_cambio").show();
        }
    }

function modalHistorial(){
    var id_hospitalizado = $("#id_hospitalizado").val();
    $.ajax({
            url: "../../modal_historial_cama/"+id_hospitalizado,
            type: "GET",
            success: function (result) {  
                    $("#divHistorial").html(result);

                    $('#tablemodalm').DataTable({
                        "sPaginationType": "full_numbers",
                        
                        "language": {"url": "/js/Spanish.json"},
                    });
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
        padding: 10px;
background-position: 2% 7px;
background-repeat: no-repeat;
background-size: auto 35px;
/*background-color: rgba(0, 0, 0, 0);*/
border: 0;
min-width: auto !important;
text-align: left;
padding-left: 68px;
font-size: 14px;
margin-bottom: 10px;
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
                    <div class="panel-heading">
                        <h3 class="panel-title">MÓDULO PARA ROTACION CAMA - PACIENTES HOSPITALIZADOS</h3>
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('rotacion_cama')}}" id="frmReceta" autocomplete="off">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-4 text-center">
                                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                                    <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                    <input type="hidden" value="{{Auth::user()->id_establecimiento}}" name="id_establecimiento" id="id_establecimiento">
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                    <input type="hidden" value="{{$hospitalizado->nro_doc_ident}}" name="dni_beneficiario">
                                    <input type="hidden" value="dni" name="tipo">
                                    <input type="hidden" name="id_hospitalizado" id="id_hospitalizado" value="{{$id_hospitalizado}}"/>
                                    <input type="hidden" value="<?php echo $id_ipress ?>" name="id_ipress" id="id_ipress">
                                    <input type="hidden" value="<?php echo $nivel ?>" name="nivel" id="nivel">
                                </div>
                                <div class="col-md-4" style="text-align: center">
                                    <label>{{$establecimiento[0]->nombre}}</label><br>
                                    <label style="font-size: 24px">CAMA HOSPITALARIA</label><br>
                                </div>
                                <div class="col-md-4 text-center">
                                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <label>TIPO ATENCIÓN</label><br/>
                                    HOSPITALIZACION<br/>
                                </div>
                                <div class="col-md-2">
                                    <label>FEC. INGRESO</label><br/>
                                    <?php echo date("d-m-Y", strtotime($hospitalizado->fecha_ingreso)); ?>
                                    <input type="hidden" class="form-control" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_cambio" id="fecha_cambio" readonly=""/><br/>
                                </div>
                                <div class="col-md-3">
                                    <label>SERVICIO ACTUAL</label><br/>
                                        @foreach($consultorio as $c)                                        
                                        <?php if ($hospitalizado->id_consultorio==$c->id) echo  $c->nombre ?>
                                      @endforeach<br/>
                                </div>
                                <div class="col-md-3">
                                    <label>SALA ACTUAL</label><br/>
                                        @foreach($sala as $c)                                        
                                            <?php if ($hospitalizado->id_consultorio_hosp==$c->id_consultorio) echo $c->nombre ?>
                                        @endforeach<br/>
                                </div>
                                <div class="col-md-2">
                                    <label>CAMA ACTUAL</label><br/>
                                    @foreach($cama as $c)
                                        <?php if($hospitalizado->id_cama==$c->id) echo $c->nombre_cuarto.' - '. $c->nombre_cama; ?>
                                    @endforeach<br/>
                               </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-3"><br/><br/>
                                    <label>DOCUMENTO IDENTIDAD</label><br/>
                                    {{$hospitalizado->nro_doc_ident}}
                                </div>
                                <div class="col-md-6"><br/><br/>
                                    <label>APELLIDOS Y NOMBRES </label><br/>
                                        <?php echo utf8_decode(trim($hospitalizado->asegurado_paterno . " " . $hospitalizado->asegurado_materno . ", " . $hospitalizado->asegurado_nombre)) ?>
                                </div>
                                <div class="col-md-3"><br/><br/>
                                    <label>ROTACION O TRASLADO</label><br/>
                                    <select class="form-control" id="id_opcion" name="id_opcion" onchange="getRotacion();" style="width: 100%">
                                            <option value="">--Seleccionar--</option>
                                              <option value="1">ROTACION</option>
                                              <option value="2">TRASLADO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" id="ver_cama" style="display:none">  
                                <div class="col-md-3">
                                    @if($id_user_consultorio!=17)
                                    <label>CAMA</label>
                                    <select id="id_cama2" name="id_cama2" class="form-control" style="width: 100%">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($cama as $c)
                                            <option value="{{$c->id}}" <?php if($hospitalizado->id_cama==$c->id) echo 'selected'?>>{{$c->nombre_cuarto}} - {{$c->nombre_cama}}</option>
                                        @endforeach                                            
                                    </select>
                                    @endif
                                </div>
                            </div>    
                            <div class="col-md-12" id="ver_cambio" style="display:none">  
                                @if($nivel==2)
                                    <div class="col-md-4">
                                        <label>SALA</label>
                                        <select class="form-control" id="id_consultorio" name="id_consultorio" onchange="getCama();" style="width: 100%">
                                            <option value="">--Seleccionar--</option>
                                            @foreach($consultorio as $c)                                        
                                            <option value="{{$c->id}}" <?php if ($hospitalizado->id_consultorio==$c->id) echo 'selected';?>>{{$c->nombre}}</option>
                                            @endforeach                                        
                                      </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>TURNO</label>
                                        <select class="form-control" name="turno" id="turno"  tabindex="3" style="width: 100%" onchange="getMedico()">
                                            <option value="">-Seleccionar-</option>
                                            <option value="MD" <?php if ($hospitalizado->turno=='MD') echo 'selected';?>>MADRUGADA</option>
                                            <option value="M" <?php if ($hospitalizado->turno=='M') echo 'selected';?>>MAÑANA</option>
                                            <option value="T" <?php if ($hospitalizado->turno=='T') echo 'selected';?>>TARDE</option>
                                            <option value="N" <?php if ($hospitalizado->turno=='N') echo 'selected';?> >NOCHE</option>
                                      </select>
                                    </div>
                                
                                @else
                                    @if($id_user_consultorio!=17)
                                        <div class="col-md-3">
                                            <label>SERVICIO</label>
                                            <select class="form-control" id="id_consultorio" name="id_consultorio" onchange="getSala();" style="width: 100%">
                                                <option value="">--Seleccionar--</option>
                                                    @foreach($consultorio as $c)                                        
                                                <option value="{{$c->id}}" <?php if ($hospitalizado->id_consultorio==$c->id) echo 'selected';?>>{{$c->nombre}}</option>
                                                    @endforeach                                        
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>SALA</label>
                                            <select class="form-control" id="id_sala" name="id_sala" onchange="getCama2();" style="width: 100%">
                                                <option value="">--Seleccionar--</option>
                                                @foreach($sala as $c)                                        
                                                <option value="{{$c->id_consultorio}}" <?php if ($hospitalizado->id_consultorio_hosp==$c->id_consultorio) echo 'selected';?>>{{$c->nombre}}</option>
                                                @endforeach                                        
                                            </select>
                                        </div>
                                    @endif
                                @endif
                                @if($id_user_consultorio!=17)
                                    <div class="col-md-2">
                                        <label>CAMA</label>
                                        <select id="id_cama" name="id_cama" class="form-control" style="width: 100%">
                                            <option value="">--Seleccionar--</option>
                                            @foreach($cama as $c)
                                                <option value="{{$c->id}}" <?php if($hospitalizado->id_cama==$c->id) echo 'selected'?>>{{$c->nombre_cuarto}} - {{$c->nombre_cama}}</option>
                                            @endforeach                                            
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-12">
                                    <div class="col-md-3"><br/>
                                        <label>Nro DOCUMENTO </label>
                                        <input type="text" class="form-control" name="documento" id="documento" style="text-align:left">
                                    </div>
                                    <div class="col-md-6"><br/>
                                        <label>MEDICO QUE AUTORIZA TRASLADO (DNI | CMP | Nombre y Apellidos)</label>
                                        <?php if (isset($existe_medico) && $existe_medico == 1): ?>
                                            <input  name="exite_medico" value="{{$existe_medico}}" type="hidden">
                                        <?php endif; ?>
                                        <select class="form-control" name="id_medico"  id="id_medico" tabindex="5" style="width: 100%">
                                            <option value="">--Seleccionar--</option>
                                            @foreach($medico as $m)
                                                @if(!is_null($hospitalizado->id_medico))
                                                    <option value="{{$m->id}}" <?php if($hospitalizado->id_medico==$m->id) echo 'selected'?>>{{$m->paterno}} {{$m->materno}}, {{$m->nombre}} - {{$m->colegiatura}}</option>
                                                @else
                                                    <option value="{{$m->id}}">{{$m->paterno}} {{$m->materno}}, {{$m->nombre}} - {{$m->colegiatura}}</option>
                                                @endif
                                            @endforeach                                            
                                      </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12"><br/><br/>
                                <div class="col-md-6">
                                    <a href="/all_hospitalizado_by_establecimiento/" class="form-control btn btn-danger" tabindex="99922">CANCELAR</a>
                                </div>
                                <div class="col-md-6">
                                    <!--<input id="btnReg" class="form-control btn btn-success" type="submit" value="GUARDAR" tabindex="99921">-->
                                    <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()">GUARDAR</button>
                                </div>
                            </div>
                        </form>
                        <div class="col-md-12">
                            <br/><br/>
                            <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                <div class="panel-title text-center">
                                  <b>HISTORIAL DE ROTACIONES DE CAMAS</b>
                                </div>
                              </div>
                              <div class="panel-body">
                                <div class="form-group">
                                  <div id='divHistorial'></div>
                                </div>
                              </div>
                            </div>
                          </fieldset>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection