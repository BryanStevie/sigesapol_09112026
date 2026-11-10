

function solo_letra(e){
	
 	var key = (document.all) ? e.keyCode : e.which;
 	return ((key>=97 && key<=122) || (key>=64 && key<=90) || (key==209 || key==241) || key==0 || key==8 || key==32);
 
}
function esnumero(campo){ 
	return (!(isNaN( campo )));
}

function esnulo(campo){ 
	return (campo == null||campo=="");
}

function esemail(email){
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(email)) {
		return true;
	}
	return false;
}

function IsNumber(e,IsDecimal){
	
  var key = (document.all) ? e.keyCode : e.which;
  if(!IsDecimal)var IsDecimal=false;

   if(IsDecimal==false)
   return (key <= 13 || (key >= 48 && key <= 57) || key==45);
 else
   return (key <= 13 || (key >= 48 && key <= 57) || key == 46 || key==45);
   
}

function Buscar(page,nav){
	if(typeof page =="undefined")page = 1;
	if(typeof nav == "undefined")nav = 0;
	$("#AjaxLoading").css("display","block");
	$('#busqueda_fichas').attr('disabled',true).val('Procesando ...').addClass("w100");
	 $.ajax({
		type:'POST',
		url: $("#"+gform).attr('action'),
		data:$("#"+gform).serialize()+"&page="+page+"&nav="+nav,
		success: function(response) {
			$("#AjaxLoading").css("display","none");
			$('#busqueda_fichas').attr('disabled',false).val('Buscar').removeClass("w100");
			$("#resultado_busqueda").html(response);
		}
	 });
}


function limpiar_formulario(){

	$('form').find(':input').each(function() {
		switch(this.type) {
			case 'text':
			case 'textarea':
			case 'hidden':
			$(this).val('');
			break;
			case 'select-one':
			case 'select-multiple':
			$(this).html('');
			break;
			//this.selectedIndex = 0;
		}
	});	
	
}

function limpieza(form){
	$('#'+form).find(':input,select').each(function() {
					switch(this.type) {
						case 'text':
						case 'textarea':
						case 'password':
						$(this).val('');
						break;
						case 'select-one':
						this.selectedIndex = 0;
						break;
						case 'checkbox':
						case 'radio':
						$(this).prop('checked',false);
						break;
					}
	});	
}


function guardar_serialize(form){
	
	if (validacion() > 0){
			dialogo_zebra("Falta Ingresar Datos Obligatorios",'warning','Alerta de '+gtitle,500,'');
			return false;	
	}
	//alert($("#"+form).serialize());return false;
	$("#AjaxLoading").css("display","block");
	$('#button_agregar').attr('disabled',true).val('Procesando ...').addClass("w100");
	$.ajax({
		type:'POST',
		url: $("#"+form).attr('action'),
		data:$("#"+form).serialize(),
		success: function(data) {
			//alert(data);return false;
			if(data == 'success'){
				dialogo_zebra("Se Guardo Satisfactoriamente",'confirmation','Alerta de '+gtitle,500,gurl+gcontroller+'/'+gmethod);
			}else{
				dialogo_zebra("Error Al Grabar Registro",'error','Alerta de '+gtitle,500);
			}
			$("#AjaxLoading").css("display","none");
			$('#button_agregar').attr('disabled',false).val('Buscar').removeClass("w100");
		}
	 });	
	
}

function guardar_serialize_array(form){
	
	if (cajas() > 0){
		for(var i = detalle.length - 1; i>=0 ;i--){
			detalle.splice(i,1);
		}
		for(var i = sub_detalle.length - 1; i>=0 ;i--){
				sub_detalle.splice(i,1);
		}
		for(var i = sub_detalle2.length - 1; i>=0 ;i--){
				sub_detalle2.splice(i,1);
		}
		dialogo_zebra("Falta Ingresar Datos Obligatorios",'warning','Alerta de '+gtitle,500,'');
		return false;	
	}
	
	if (data_array() > 0){
		for(var i = detalle.length - 1; i>=0 ;i--){
				detalle.splice(i,1);
		}
		for(var i = sub_detalle.length - 1; i>=0 ;i--){
				sub_detalle.splice(i,1);
		}
		for(var i = sub_detalle2.length - 1; i>=0 ;i--){
				sub_detalle2.splice(i,1);
		}
		dialogo_zebra("Debe ingresar una Muestra",'warning','Alerta de '+gtitle,500,'');
		return false;	
	}
	
	desabilitar();
	
	var serializedformdata = $('#'+form).serialize();
	
	serializedformdata += adicionar_form(serializedformdata);
	
	//alert(serializedformdata); return false;
	$("#AjaxLoading").css("display","block");
	$('#button_agregar').attr('disabled',true).val('Procesando ...').addClass("w100");
	//return false;
	$.post($("#"+form).attr('action'),{'formdata': serializedformdata,'detalle':detalle,'sub_detalle':sub_detalle,'sub_detalle2':sub_detalle2},function(data){
		//alert(data);return false;
		
		for(var i = detalle.length - 1; i>=0 ;i--){
				detalle.splice(i,1);
		}
		for(var i = sub_detalle.length - 1; i>=0 ;i--){
				sub_detalle.splice(i,1);
		}
		for(var i = sub_detalle2.length - 1; i>=0 ;i--){
				sub_detalle2.splice(i,1);
		}
		
		if(data == 'success'){
			dialogo_zebra("Se Guardo Satisfactoriamente",'confirmation','Alerta de '+gtitle,500,gurl+gcontroller+'/'+gmethod);
		}else{
			//alert_error(400, 200, "error", "");
			dialogo_zebra("Error Al Grabar Registro",'error','Alerta de '+gtitle,500,'');
		}
		$("#AjaxLoading").css("display","none");
		$('#button_agregar').attr('disabled',false).val('Agregar').removeClass("w100");
		
	});
	
}


function alert_success(ancho, alto, mensaje, enlace) {

    $("#mensaje_success").remove();
    $('body').append("<div id='mensaje_success'>");
    $("#mensaje_success").dialog({
        autoOpen: false,
        title: "Software de Gestión de Ingresos y Salidas de Transporte y Personas",
        width: ancho,
        height: alto,
        modal: true,
        draggable: true,
        show: "slide",
        hidden: "fade",
        buttons: {
            "Aceptar": function() {
                if (enlace.length == 0) {
                    $(this).dialog("close");
                    $("#mensaje_success").remove();
                } else {
                    location.href = enlace;
                }
            }
        }
    });
    var html = "";
    html += "<div class=\"success\">";
    html += "</div>";
    html += "<div class=\"message\">";
    html += mensaje;
    html += "</div>";
    $("#mensaje_success").html(html)
    $("#mensaje_success").dialog("open");
	
	if (enlace.length > 0) {
		setTimeout(
		function(){
			location.href = enlace;
		}
		,1500);
	}
	
}
function alert_error(ancho, alto, mensaje, enlace) {

    $("#mensaje_error").remove();
    $('body').append("<div id='mensaje_error'>");
    $("#mensaje_error").dialog({
        autoOpen: false,
        title: "SISTEMA DE GESTION",
        width: ancho,
        height: alto,
        modal: true,
        draggable: true,
        show: "slide",
        hidden: "fade",
        buttons: {
            "Aceptar": function() {
                if (enlace.length == 0) {
                    $(this).dialog("close");
                    $("#mensaje_error").remove();
                } else {
                    location.href = enlace;
                }
            }
        }
    });
    var html = "";
    html += "<div class=\"error_msg\">";
    html += "</div>";
    html += "<div class=\"message\">";
    html += mensaje;
    html += "</div>";
    $("#mensaje_error").html(html)
    $("#mensaje_error").dialog("open");

}


function alert_alerta(ancho, alto, mensaje, enlace) {

    $("#mensaje_error").remove();
    $('body').append("<div id='mensaje_error'>");
    $("#mensaje_error").dialog({
        autoOpen: false,
        title: "SEGUIMIENTO",
        width: ancho,
        height: alto,
        modal: true,
        draggable: true,
        show: "slide",
        hidden: "fade",
        buttons: {
            "Aceptar": function() {
                if (enlace.length == 0) {
                    $(this).dialog("close");
                    $("#mensaje_error").remove();
                } else {
                    location.href = enlace;
                }
            }
        }
    });
    var html = "";
    html += "<div class=\"warning\">";
    html += "</div>";
    html += "<div class=\"message\">";
    html += mensaje;
    html += "</div>";
    $("#mensaje_error").html(html)
    $("#mensaje_error").dialog("open");

}


function confirmacion(ancho, alto, mensaje, url, id) {

    var html = "";

    $("#confirmacion").remove();
    $('body').append("<div id='confirmacion'>");

    $("#confirmacion").dialog({
        title: "SISTEMA DE GESTION",
        width: ancho,
        height: alto,
        modal: true,
        draggable: true,
        show: "blind",
        hidden: "fade",
        buttons: {
            "SI": function() { 
                	$('.selected').remove();
					$('textarea[val_textarea="selected_obs"]').remove();
					cont --;
                    $(this).dialog("close");
					
					$('.itemm').each(function(j){
						$(this).html(j + 1);
					});
               
            },
            "NO ": function() {
                $(this).dialog("close");
            }
        }

    });

    html += "<div class=\"warning\">";
    html += "</div>";
    html += "<div class=\"message\">";
    html += mensaje;
    html += "</div>";

    $("#confirmacion").html(html)
    $("#confirmacion").dialog("open");

}




function devolver_tabla_id(origen,destino,tabla,seleccionado){
	
	var id = $('#'+origen).val();
	
	$.post($('#'+origen).attr('ruta'),{id:id,tabla:tabla},function(data){
			
			$('#'+destino).attr('disabled',false);
			$('#'+destino).html('');
			$('#'+destino).append(
								 $('<option></option>')
										.val('0')
										.html('[ --Seleccionar-- ]'));

			$.each(data, function(i,obj)
			{
				var option = '';
				var selected = '';
				if (seleccionado == obj['id']) { selected = "selected='selected'";}
				option = "<option "+selected+" ></option>";
				$('#'+destino).append($(option).val(obj['id']).html(obj['vNombre']));
			}); 
		}, 'json');
		
}



function recuperar_select_id(origen,destino,seleccionado){

	var id = $('#'+origen).val();
	
	$.post($('#'+origen).attr('ruta'),{id:id},function(data){
		
			$('#'+destino).attr('disabled',false);
			$('#'+destino).html('');
			$('#'+destino).append(
								 $('<option></option>')
										.val('0')
										.html('[ --Seleccionar-- ]'));

			$.each(data, function(i,obj)
			{
				var option = '';
				var selected = '';
				if (seleccionado == obj['id']) { selected = "selected='selected'";}
				option = "<option "+selected+"></option>";
				$('#'+destino).append($(option).val(obj['id']).html(obj['vNombre']));
			}); 
		}, 'json');

}

function recuperar_select_id2(origen,destino,op,seleccionado){
	
	var id = $('#variedad option:selected').attr('id2');
	
	$.post($('#'+origen).attr('ruta'),{id:id},function(data){
		
			$('#'+destino).attr('disabled',false);
			$('#'+destino).html('');
			$('#'+destino).append(
								 $('<option></option>')
										.val('0')
										.html('[ --Seleccionar-- ]'));
										
			$.each(data, function(i,obj)
			{
				var option = "<option></option>";
				var selected = '';
				if (seleccionado == obj['id']) { selected = "selected='selected'";}
				option = "<option "+selected+" id2='"+obj['id2']+"'></option>";
				$('#'+destino).append($(option).val(obj['id']).html(obj['vNombre']));
			}); 
			
		}, 'json');
}


function recuperar_select_controller_id___(idorigen,controlador,destino){
	
	var id = idorigen;
	
	$.post(url_absoluta+controlador,{id:id},function(data){
		
			$('#'+destino).html('');
			$('#'+destino).append($('<option></option>').val('0').html('[ --Seleccionar-- ]'));
			$.each(data, function(i,obj)
			{
				selected = '';
				option = "<option "+selected+" '></option>";
				$('#'+destino).append($(option).val(obj['id']).html(obj['vNombre']));
				
			}); 
			
		}, 'json');
}

function recuperar_select_controller_name(idorigen,controlador,destino){
	
	var id = idorigen;
	
	$.post(url_absoluta+controlador,{id:id},function(data){
		
			$('select[name="'+destino+'"]').html('');
			$('select[name="'+destino+'"]').append($('<option></option>').val('0').html('[ --Seleccionar-- ]'));
			$.each(data, function(i,obj)
			{
				selected = '';
				option = "<option "+selected+" '></option>";
				$('select[name="'+destino+'"]').append($(option).val(obj['id']).html(obj['vNombre']));
				
			}); 
			
		}, 'json');
}


function recuperar_select_controller_id(idorigen,controlador,destino,seleccionado){
	
	var id = idorigen;
	
	$.post(controlador,{id:id},function(data){
		
			$('#'+destino).html('');
			$('#'+destino).append($('<option></option>').val('').html('[ --Seleccionar-- ]'));
			$.each(data, function(i,obj)
			{
				var option = '';
				var selected = '';
				if (seleccionado == obj['id']) { selected = "selected='selected'";}
				option = "<option "+selected+"></option>";
				$('#'+destino).append($(option).val(obj['id']).html(obj['vNombre']));
				
			}); 
			
		}, 'json');
}

/*
	function recuperar_informacion(id){
	
	var id = $(id).val();
	
	$.post(url_absoluta+'c_modulo_logistica/recuperar_informacion',{id:id},function(data){//alert(data);
					
					$.each(data, function(i,obj)
					{
						//$('#nombre').val(obj['vContacto'])
						$('#ruc').val(obj['vRuc']);
						//$('#direccion').val(obj['vDireccion']);
					});
		
	}, 'json');
}


function recuperar_informacion(id){
	var iIdFacturaCliente = $('#'+id).val();
	$.post(gurl+'c_modulo_logistica/recuperar_info_facturacliente',{iIdFacturaCliente:iIdFacturaCliente},function(data){//alert(data);
					
					$.each(data, function(i,obj)
					{
						$('#cliente').val(obj['cliente'])
						$('#vDireccion').val(obj['vDireccion']);
						$('#vRuc').val(obj['vRuc']);
						$('#dFecha_FacturaCliente').val(obj['dFecha_FacturaCliente']);
					});
		
	}, 'json');
}

function recuperar_informacion(origen){
	
	var id = $('#'+origen).val();
	var op = 0;
	if(origen == 'iIdContenedor')op=1;
	if(origen == 'iIdClienteNew')op=2;
	
	$.post(url_absoluta+'c_modulo_logistica/recuperar_info_contenedor',{id:id,op:op},function(data){
					$.each(data, function(i,obj)
					{
						$('#vNombre').val(obj['vNombre']);
						$('#vDireccion').val(obj['vDireccion'])
						$('#vContacto').val(obj['vContacto'])
						$('#vEmail').val(obj['vEmail'])
						$('#vTelefono').val(obj['vTelefono']);
						$('#vFax').val(obj['vFax']);
						$('#iIdCliente').val(obj['iIdCliente']);
						
						if(op == 1){
							$('#producto').html(obj['producto']);
							$('#variedad').html(obj['variedad']);
						}
					});
		
	}, 'json');
}

*/

function abrir_dialog(opc,cod){

  var alto,ancho,title;
  if(opc=='cotizacion_hilo') { alto=340;  ancho=380;if(cod > 0)title='Editar Cotizacion de Hilo';else title='Guardar Cotizacion de Hilo';}
  if(opc=='cajachica') { alto=250;  ancho=500; cod = $('#iIdCajaChica').val();if(cod > 0)title='Editar Caja Chica';else title='Guardar Caja Chica';}
  if(opc=='factura_comercial') { alto=650;  ancho=850; if(cod > 0)title='Editar Factura Comercial';else title='Guardar Factura Comercial';}
  if(opc=='prenda_estilo') { alto=240;  ancho=650;if(cod > 0)title='Editar Ingreso de Prendas a Acabados';else title='Guardar Ingreso de Prendas a Acabados';}
  if(opc=='dua') { alto=270;  ancho=350; if(cod > 0)title='Editar Dua';else title='Guardar Dua';}
  if(opc=='pagares') { alto=450;  ancho=450; if(cod > 0)title='Editar Pagares';else title='Guardar Pagares';}
  
   $("#cargan").css("display","block");
   $.post(gurl+gcontroller+"/frm_agregar_editar_registro/" ,  {  "opc" : opc , "cod" : cod   } ,
        function(data){
             guardar_editar_frm(ancho,alto,data,opc,title);
             $("#cargan").css("display","none");
             //$.getScript(gurl+"assets/js/mantenedor/borrar_css_frm.js");
        }
   );

}

function abrir_dialog_array(opc,cod){

  var alto,ancho,title;
  if(opc=='factura_nacional') { alto=650;  ancho=850; if(cod > 0)title='Editar Factura Nacional';else title='Guardar Factura Nacional';}
  if(opc=='factura_comercial') { alto=700;  ancho=1020; if(cod > 0)title='Editar Factura Comercial';else title='Guardar Factura Comercial';}
  if(opc=='personal') { alto=300;  ancho=320; if(cod > 0)title='Editar Personal';else title='Guardar Personal';}

   $("#cargan").css("display","block");
   $.post(gurl+gcontroller+"/frm_agregar_editar_registro/" ,  {  "opc" : opc , "cod" : cod   } ,
        function(data){
             guardar_editar_frm_array(ancho,alto,data,opc,title);
             $("#cargan").css("display","none");
             //$.getScript(gurl+"assets/js/mantenedor/borrar_css_frm.js");
        }
   );

}

function abrir_dialog_busqueda(opc){

  var alto,ancho,title;  
  if(opc=='busqueda_asistencia') { alto=400;  ancho=1000; title='Busqueda de Asistencia';}
  if(opc=='busqueda_personal') { alto=500;  ancho=700; title='Busqueda de Asistencia';}
  if(opc=='busqueda_planilla') { alto=190;  ancho=620; title='Busqueda de Planilla';}
  
   $("#cargan").css("display","block");
   $.post(gurl+gcontroller+"/frm_agregar_editar_registro/" , {"opc" : opc} ,
        function(data){
             guardar_editar_frm_busqueda(ancho,alto,data,opc,title);
             $("#cargan").css("display","none");
             //$.getScript(gurl+"assets/js/mantenedor/borrar_css_frm.js");
        }
   );

}

function openDialog(form,title,width,height,op,id,obj){
	
		limpiar_formulario();
		$('#iIdProveedor').remove();
		if (op==2){
			var vNombre = $(obj).parent('td').parent('tr').find('.vNombre').html();
			var vRuc = $(obj).parent('td').parent('tr').find('.vRuc').html();
			var vContacto = $(obj).parent('td').parent('tr').find('.vContacto').html();
			var vDireccion = $(obj).parent('td').parent('tr').find('.vDireccion').html();
			var vTelefono = $(obj).parent('td').parent('tr').find('.vTelefono').html();
			var vEmail = $(obj).parent('td').parent('tr').find('.vEmail').html();
			var vNumCuenta = $(obj).parent('td').parent('tr').find('.vNumCuenta').html();
			
			$('#vNombre').val(vNombre);
			$('#vRuc').val($.trim(vRuc));
			$('#vContacto').val($.trim(vContacto));
			$('#vDireccion').val(vDireccion);
			$('#vTelefono').val(vTelefono);
			$('#vEmail').val(vEmail);
			$('#vNumCuenta').val(vNumCuenta);
			$('#'+form).append("<input type='hidden' id='iIdProveedor' name='iIdProveedor' value='"+id+"' />");
		}
		$("#"+form).dialog({
			title : title,
			autoOpen : true,
			modal : true,
			draggable : true,
			width : width,
			height : height,
			show : "blind",
			hidden : "fade",
			buttons:{
			 "Guardar":function(){  
					guardar_serialize(form);
			 }
		   }
	  	});
	
}

function agregar_elemento(elemento){
	var option = 0;	
	option = $('#'+elemento).val();

	if (option != ''){
		$('select[name='+elemento+'s]').append(
									 $('<option></option>')
										.val(0)
										.html(option));
		$('#'+elemento).val('');
	}
}

function eliminar_elemento(elemento){
		
		$('#'+elemento+'s option:selected').each(function(){
			var id 			= $(this).val();
			var	obj 		= $(this);
			$.post(url_absoluta+'c_modulo_mantenedores/validar_calibre',{id:id},function(data){
				if(data=='success'){
						detalle_delete.push({"iIdCalibre": id});
						$(obj).remove();
				}else{
					//alert("Campo tiene registros ...");	
					//alert_error(400, 200, "Campo tiene registros ...", "");
					dialogo_zebra("Campo tiene registros ...",'error','Alerta de '+gtitle,500);
				}
			});
		});
		
}


function ver(id){
			
	$('.selected').attr('class','fila');
	$(id).attr('class','selected');
	
}	

function eliminar_fila(){
	
	if (confirm("Seguro que deseas Eliminar")){
		$('.selected').remove();
	}	
	
}


function cargarPag(div,pag,idformato,id){
	$.post(pag,{idformato:idformato,id:id},function(data){
		$('#'+div).html(data);
	});
}

function cargarPag(div,pag,idformato,id){
	$.post(pag,{idformato:idformato,id:id},function(data){
		$('#'+div).html(data);
		
		$('select').each(function(){
		recuperar_select_simple($(this).attr('id'),$(this).attr('tabla'));
		});
		
		
		$("input[fecha='ok']").each(function(){
			$( "#"+$(this).attr('id')).datepicker({ dateFormat: 'dd/mm/yy' });
		 });
		
	});
}



function devolver_datos(form,id,obj){
	
	var iIdCliente = $(obj).parent('td').parent('tr').find('.item').attr('idcliente');
	var iIdProducto = $(obj).parent('td').parent('tr').find('.product').attr('idproducto');
	var iIdPuerto = $(obj).parent('td').parent('tr').find('.item').attr('idpuerto');
	var iIdTipoCaja = $(obj).parent('td').parent('tr').find('.item').attr('iIdTipoCaja');
	var iCantidadContenedor = $(obj).parent('td').parent('tr').find('.item').attr('cantidad_contenedor');
	var cFlagPalletCandado = $(obj).parent('td').parent('tr').find('.item').attr('cFlagPalletCandado');
	var cFlagPorcentajecantidad = $(obj).parent('td').parent('tr').find('.item').attr('cFlagPorcentajecantidad');
	var tDescripcion = $(obj).parent('td').parent('tr').find('.item').attr('descripcion');
	var dFecha_solicitada = $(obj).parent('td').parent('tr').find('.fecha_solicitada').html();
	var iIdVariedad = $(obj).parent('td').parent('tr').find('.variedad').attr('idvariedad');
	var iIdEstadoProduccion = $(obj).parent('td').parent('tr').find('.estado_ordenproduccion').attr('iIdEstadoProduccion');
	$('#guardar').prop('disabled',false);
	if(iIdEstadoProduccion == 3){
		$('#guardar').prop('disabled',true);
	}
	$('#iIdCliente').val(iIdCliente);
	$('#iIdProducto').val(iIdProducto);
	$('#iIdPuerto').val(iIdPuerto);
	$('#iCantidadContenedor').val(iCantidadContenedor);
	$('#tDescripcion').val(tDescripcion);
	$('#dFecha_solicitada').val(dFecha_solicitada);
	$('#iIdVariedad').attr('disabled',false);
	$('#iIdTipoCaja').attr('disabled',false);
	$("#FlagPalletCandado").prop('checked', false);
	
	//alert(iIdTipoCaja);
	
	if (cFlagPalletCandado == 1 || cFlagPalletCandado == 2){
		
		$('#div_candado').show();
		if(cFlagPalletCandado == 1)$("#FlagPalletCandadoAutomatico").prop('checked', true);
		if(cFlagPalletCandado == 2)$("#FlagPalletCandadoManual").prop('checked', true);
		
	}
	$('#iIdOrdenproduccion').remove();
	var newRow = "<input type='hidden' name='iIdOrdenproduccion' id='iIdOrdenproduccion' value='"+id+"'/>";
	$('#form_ordenproduccion').append(newRow);
	var op;
	recuperar_select('iIdProducto','iIdVariedad',op,iIdVariedad);
	recuperar_select_tipoCaja('iIdProducto','c_modulo_logistica/recuperar_TipoCaja','iIdTipoCaja',iIdTipoCaja);
	//$('#iIdTipoCaja').val(iIdTipoCaja);
	
	if ($('input[calibre="ok"]').prop('disabled') == false){
		cFlagPorcentajecantidad = 1;
	}
	
	if ($('input[calibre_cant="ok"]').prop('disabled') == false){
		cFlagPorcentajecantidad = 2;
	}
	
	setTimeout(function() {
		recuperar_select2('iIdVariedad','calibre',1,id,cFlagPorcentajecantidad);
		recuperar_calibre_manual(id);
		habilitar_candado('iIdTipoCaja');
	}, 500);
	
	
		
}



/*
function ver(id){
	$('.selected').attr('class','fila');
	$(id).attr('class','selected');
}
*/

function form_editar(){
	
	 $("input[habilitar='ok'],select[habilitar='ok']").each(function(){
		  $(this).attr('disabled',false);
	});
	
	$('#guardar').attr('disabled',false);
	$('#editar').attr('disabled',true);
	
}

function validar_combo(){
	
	var bandera=true;
	 $("select[validar='ok']").each(function(){
		 if($(this).val()==0){
			 $(this).removeClass("bordercaja").addClass("error");
			 bandera=false;
		 }else{
			 $(this).removeClass("error").addClass("bordercaja");
		 }
	 });
	 
	 return bandera;
}


function validar_cajas(){
	
	var bandera=true;
	 $("input[validar='ok'] , textarea[validar='ok']").each(function(){
		  if($(this).val()==0){
			 $(this).removeClass("bordercaja").addClass("error");
			 bandera=false;
		 }else{
			 $(this).removeClass("error").addClass("bordercaja");
		 }
	 });
	 
	 return bandera;
}


function cajas(){
	
	//validar_cajas(); return false;
	validar_combo();
	validar_cajas()
	
    var contador=0;
	
    if(!(validar_combo()) || !(validar_cajas())){
	
		contador=1;
	}
	return contador;
	
  
}


/****************validacion decimales**********************/


var objeto2;     
function dosDecimales(){    

var objeto = objeto2;
var posicion = objeto.value.indexOf('.');
var decimal = 2;

	if(objeto.value.length - posicion < decimal){
		objeto.value = objeto.value.substr(0,objeto.value.length-1);                                        
	}else {
		objeto.value = objeto.value.substr(0,posicion+decimal+1);                                            
	}
	return;
}

function soloDinero(objeto, e){

var keynum
var keychar
var numcheck

	if(window.event){ /*/ IE*/
  		keynum = e.keyCode
  	}
	else if(e.which){ /*/ Netscape/Firefox/Opera/*/
  		keynum = e.which
  	}

  	if((keynum>=35 && keynum<=37) ||keynum==8||keynum==9||keynum==46||keynum==39) {
	  return true;
	}
   
  	if(keynum==190||keynum==110||(keynum>=95&&keynum<=105)||(keynum>=48&&keynum<=57)){	
	  posicion = objeto.value.indexOf('.');   

	  if(posicion==-1) {
		return true;
	  }else {                           
		  if(!(keynum==190||keynum==110)){
			objeto2=objeto;
			t = setTimeout('dosDecimales()',150);
			return true;
			}else{
			objeto2=null;
			return false;
			}
		 }
	  }else {
			return false;
	  }
	  
  	}

/*  Geenral dialogo guardar  */

function guardar_editar_frm(ancho, alto, html , mantenedor,title) {
	
	if(title == null)title="Editar Registro";
	
    $("#cuerpo_frm").remove();
    $('body').append("<div id='cuerpo_frm'>");
    $("#cuerpo_frm").dialog({
        autoOpen: false,
        title: title,
        width: ancho,
        height: alto,
        modal: true,
        draggable: true,
        show: "slide",
        hidden: "fade",
		open: function(event, ui) { 
									//$(".ui-dialog-titlebar-close", ui.dialog).hide(); 
									$(".ui-dialog-titlebar-close", ui.dialog).hide();
									var cerrar = "<div class='cerrar_dialog' onclick=$('#cuerpo_frm').dialog('close')>Cerrar</div>";
									$(".ui-dialog-titlebar", ui.dialog).append(cerrar);
								  },
        buttons: {
            "Aceptar": function() {
	

                var r_validation=validar_formulario(mantenedor);
                if(r_validation>0){ 
                //alert_error(400, 200, 'Datos Obligatorios', '')
				dialogo_zebra("Datos Obligatorios ...",'error','Alerta de '+gtitle,500,'');
                return false;
                }

              $.ajax({

                   type : 'POST',
                   url : gurl+gcontroller+"/guardar_datos/",
                   data : $("#"+mantenedor).serialize(),
                   success:function(data){

                      var obj=eval('('+data+')');
                      $("#"+mantenedor).find("#mensaje_mantenedor").html(obj.msg);
                      $("#"+mantenedor).find("#mensaje_mantenedor").fadeIn();

                      setTimeout(function(){
                             $("#cuerpo_frm").dialog('close');
                      },1000);
     				  Buscar();
	 				  //if(mantenedor=='unic_ciudad' || mantenedor=='unic_clasificacion')Listar_data2();
                      //else Listar_data();

                   },beforeSend:function(){
                        $("#cargan").css('display','block');
                   },complete:function(){
                        $("cargan").css("display",'none');
                   },error:function(){
                      $("#"+mantenedor).find("#mensaje_mantenedor").html('Error de ejecucion');
                      $("#"+mantenedor).find("#mensaje_mantenedor").fadeIn();
                      $("#cargan").css("display",'none');
                   }

              });


                       
            },"Cancelar" : function(){
                 $(this).dialog('close');
            }
        }
    });	
	
	//$.getScript(gurl+'js/mantenedor/upload.js');
    $("#cuerpo_frm").html(html)
    $("#cuerpo_frm").dialog("open");
}


function guardar_editar_frm_busqueda(ancho, alto, html , mantenedor,title) {
	
	if(title == null)title="Editar Registro";
	
    $("#cuerpo_frm").remove();
    $('body').append("<div id='cuerpo_frm'>");
    $("#cuerpo_frm").dialog({
        autoOpen: false,
        title: title,
        width: ancho,
        height: alto,
        modal: true,
        draggable: true,
        show: "slide",
        hidden: "fade",
		open: function(event, ui) { 
									//$(".ui-dialog-titlebar-close", ui.dialog).hide(); 
									$(".ui-dialog-titlebar-close", ui.dialog).hide();
									var cerrar = "<div class='cerrar_dialog' onclick=$('#cuerpo_frm').dialog('close')>Cerrar</div>";
									$(".ui-dialog-titlebar", ui.dialog).append(cerrar);
								  },
        buttons: {
            "Cancelar" : function(){
                 $(this).dialog('close');
            }
        }
    });	
	
	//$.getScript(gurl+'js/mantenedor/upload.js');
    $("#cuerpo_frm").html(html)
    $("#cuerpo_frm").dialog("open");
}

/* Mantenedores */


function design_frm(ancho, alto, html , mantenedor,title) {
	
	if(title == null)title="Editar Registro";
	
    $("#cuerpo_frm").remove();
    $('body').append("<div id='cuerpo_frm'>");
    $("#cuerpo_frm").dialog({
        autoOpen: false,
        title: title,
        width: ancho,
        height: alto,
        modal: true,
        draggable: true,
        show: "slide",
        hidden: "fade",
		open: function(event, ui) { 
									//$(".ui-dialog-titlebar-close", ui.dialog).hide(); 
									$(".ui-dialog-titlebar-close", ui.dialog).hide();
									var cerrar = "<div class='cerrar_dialog' onclick=$('#cuerpo_frm').dialog('close')>Cerrar</div>";
									$(".ui-dialog-titlebar", ui.dialog).append(cerrar);
								  },
        buttons: {
            "Aceptar": function() {
	

                var r_validation=validar_formulario(mantenedor);
                if(r_validation>0){ 
                alert_error(400, 200, 'Datos Obligatorios', '')
                return false;
                }

              $.ajax({

                   type : 'POST',
                   url : gurl+"c_mantenedor/guardar_datos/",
                   data : $("#"+mantenedor).serialize(),
                   success:function(data){

                      var obj=eval('('+data+')');
                      $("#"+mantenedor).find("#mensaje_mantenedor").html(obj.msg);
                      $("#"+mantenedor).find("#mensaje_mantenedor").fadeIn();

                      setTimeout(function(){
                             $("#cuerpo_frm").dialog('close');
                      },1000);
     
	 				  if(mantenedor=='unic_ciudad' || mantenedor=='unic_clasificacion')Listar_data2();
                      else Listar_data();

                   },beforeSend:function(){
                        $("#cargan").css('display','block');
                   },complete:function(){
                        $("#cargan").css("display",'none');
                   },error:function(){
                      $("#"+mantenedor).find("#mensaje_mantenedor").html('Error de ejecucion');
                      $("#"+mantenedor).find("#mensaje_mantenedor").fadeIn();
                      $("cargan").css("display",'none');
                   }

              });


                       
            },"Cancelar" : function(){
                 $(this).dialog('close');
            }
        }
    });	
	
	//$.getScript(gurl+'js/mantenedor/upload.js');
    $("#cuerpo_frm").html(html)
    $("#cuerpo_frm").dialog("open");
}


function agregar_frm(ancho, alto, html , mantenedor,title) {
	
	if(title == null)title="Editar Registro";
	
    $("#cuerpo_frm").remove();
    $('body').append("<div id='cuerpo_frm'>");
    $("#cuerpo_frm").dialog({
        autoOpen: false,
        title: title,
        width: ancho,
        height: alto,
        modal: true,
        draggable: true,
        show: "slide",
        hidden: "fade",
		open: function(event, ui) { 
									//$(".ui-dialog-titlebar-close", ui.dialog).hide(); 
									$(".ui-dialog-titlebar-close", ui.dialog).hide();
									var cerrar = "<div class='cerrar_dialog' onclick=$('#cuerpo_frm').dialog('close')>Cerrar</div>";
									$(".ui-dialog-titlebar", ui.dialog).append(cerrar);
								  },
        buttons: {
            "Aceptar": function() {
	

                var r_validation=validar_formulario(mantenedor);
                if(r_validation>0){ 
                alert_error(400, 200, 'Datos Obligatorios', '')
                return false;
                }

              $.ajax({

                   type : 'POST',
                   url : gurl+"c_mantenedor/guardar_datos_registro/",
                   data : $("#"+mantenedor).serialize(),
                   success:function(data){
						
                      var obj=eval('('+data+')');
                      $("#"+mantenedor).find("#mensaje_mantenedor").html(obj.msg);
                      $("#"+mantenedor).find("#mensaje_mantenedor").fadeIn();

                      setTimeout(function(){
                             $("#cuerpo_frm").dialog('close');
                      },1000);
     
					  if(mantenedor == 'unic_empresa'){
					  	var option_empresa = "<option value='"+obj.id+"'>"+obj.texto+"</option>";
					  	$('#iIdEmpresa').append(option_empresa);
					  }
					  
					  if(mantenedor == 'unic_marca'){
					  	var option_marca = "<option value='"+obj.id+"'>"+obj.texto+"</option>";
					  	$("select[name='iIdMarca']").append(option_marca);
						$("select[name='iIdMarcaDemo']").append(option_marca);
					  }
						
						
                   },beforeSend:function(){
                        $("#cargan").css('display','block');
                   },complete:function(){
                        $("#cargan").css("display",'none');
                   },error:function(){
                      $("#"+mantenedor).find("#mensaje_mantenedor").html('Error de ejecucion');
                      $("#"+mantenedor).find("#mensaje_mantenedor").fadeIn();
                      $("#cargan").css("display",'none');
                   }

              });


                       
            },"Cancelar" : function(){
                 $(this).dialog('close');
            }
        }
    });
    
	
	
    $("#cuerpo_frm").html(html)
    $("#cuerpo_frm").dialog("open");
}




function validar_formulario(mantenedor){

        var error=0;

        $("#"+mantenedor+" input[validar=ok],#"+mantenedor+" select[validar='ok']").each(function() {
             if($(this).val().length==0 || $(this).val()=='0'){
                 error=error+1;
                 $(this).addClass("error");
             }else{
                $(this).removeClass("error");
             }
        });  

        return error;
}



/* Fin de mantenedores */

function precarga(css) {
    if (typeof css == "undefined")
        css = "";
    if (css == "none")
        HideLoadingIndicator();
    else
        ShowLoadingIndicator();

}

/* LOADING GIF */
var disableLoadingIndicator;
disableLoadingIndicator = false;
function ShowLoadingIndicator() {
    if (typeof(disableLoadingIndicator) != 'undefined' && disableLoadingIndicator) {
        return;
    }
    var windowWidth = $(window).width();

    var scrollTop;
    if (self.pageYOffset) {
        scrollTop = self.pageYOffset;
    }
    else if (document.documentElement && document.documentElement.scrollTop) {
        scrollTop = document.documentElement.scrollTop;
    }
    else if (document.body) {
        scrollTop = document.body.scrollTop;
    }
    $('#AjaxLoading').css('position', 'absolute');
    $('#AjaxLoading').css('top', (scrollTop + 150) + 'px');

    $('#AjaxLoading').css('left', parseInt((windowWidth - 150) / 2) + "px");
    $('#AjaxLoading').show();
//  $('body').css('cursor', 'wait');
    $("#cargan").css("display", "block");

}

function HideLoadingIndicator() {
    $('#AjaxLoading').hide();
//  $('body').css('cursor', 'default');
    $("#cargan").css("display", "none");
}
function navegarmodulo(url, obj) {
    id = ($(obj).attr('id'));
    location.href = local + url + '/modulo/' + id;

}


function JSfileUpload(form, action_url, div_id, hidden_id,img_id,folder) {
   if(typeof div_id == "undefined")div_id="";
   if(typeof hidden_id == "undefined")hidden_id="";
  
    // Create the iframe...
    var iframe = document.createElement("iframe");
    iframe.setAttribute("id", "upload_iframe");
    iframe.setAttribute("name", "upload_iframe");
    iframe.setAttribute("width", "0");
    iframe.setAttribute("height", "0");
    iframe.setAttribute("border", "0");
    iframe.setAttribute("style", "width: 0; height: 0; border: none;");
 
    // Add to document...
    form.parentNode.appendChild(iframe);
    window.frames['upload_iframe'].name = "upload_iframe";
 
    iframeId = document.getElementById("upload_iframe");
 
    // Add event...
    var eventHandler = function () {
 
            if (iframeId.detachEvent) iframeId.detachEvent("onload", eventHandler);
            else iframeId.removeEventListener("load", eventHandler, false);
 
            // Message from server...
            if (iframeId.contentDocument) {
                content = iframeId.contentDocument.body.innerHTML;
            } else if (iframeId.contentWindow) {
                content = iframeId.contentWindow.document.body.innerHTML;
            } else if (iframeId.document) {
                content = iframeId.document.body.innerHTML;
            }
            
            
            if(!esnulo(div_id) || !esnulo(hidden_id)){
              var x = eval("("+content+")");
              if(x[0].modo==0){
  (!esnulo(div_id))?document.getElementById(div_id).innerHTML = x[0].msg:"";
              }else if(x[0].modo==1){
 (!esnulo(div_id))?document.getElementById(div_id).innerHTML = x[0].msg:"";
 (!esnulo(hidden_id))?$("#"+hidden_id).val(x[0].file):"";
  				$("#"+img_id).attr("src",gurl+"files/"+folder+"/"+x[0].file);
              }          
              
            }
 
            // Del the iframe...
            setTimeout('iframeId.parentNode.removeChild(iframeId)', 250);
        }
 
    if (iframeId.addEventListener) iframeId.addEventListener("load", eventHandler, true);
    if (iframeId.attachEvent) iframeId.attachEvent("onload", eventHandler);
 
    // Set properties of form...
    form.setAttribute("target", "upload_iframe");
    form.setAttribute("action", action_url);
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data");
    form.setAttribute("encoding", "multipart/form-data");
 
    // Submit the form...
    form.submit();
 
    document.getElementById(div_id).innerHTML = "Subiendo archivo...";
}


function stopRKey(evt) {
	var evt = (evt) ? evt : ((event) ? event : null);
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
}


function dialogo_zebra(msg,type,title,width,enlace){
	$.Zebra_Dialog(msg, {
		'type':  type,
		'title':    title,
		'buttons': [{caption:'Aceptar', callback: function() { 
					if (enlace.length > 0)location.href = enlace;
		}}],
		'width': width,
		'overlay_opacity': 0.5
	});
}
		  

function guardar_editar_frm_array(ancho, alto, html , mantenedor,title) {
	
	if(title == null)title="Editar Registro";
	
    $("#cuerpo_frm").remove();
    $('body').append("<div id='cuerpo_frm'>");
    $("#cuerpo_frm").dialog({
        autoOpen: false,
        title: title,
        width: ancho,
        height: alto,
        modal: true,
        draggable: true,
        show: "slide",
        hidden: "fade",
		open: function(event, ui) { 
									//$(".ui-dialog-titlebar-close", ui.dialog).hide(); 
									$(".ui-dialog-titlebar-close", ui.dialog).hide();
									var cerrar = "<div class='cerrar_dialog' onclick=$('#cuerpo_frm').dialog('close')>Cerrar</div>";
									$(".ui-dialog-titlebar", ui.dialog).append(cerrar);
								  },
        buttons: {
            "Aceptar": function() {
				
                var r_validation=validar_formulario(mantenedor);
                if(r_validation>0){ 
                //alert_error(400, 200, 'Datos Obligatorios', '')
				dialogo_zebra("Datos Obligatorios ...",'error','Alerta de '+gtitle,500,'');
                return false;
                }
				
				guardar_serialize_array(mantenedor);
				$(this).dialog("close");
				Buscar();
                       
            },"Cancelar" : function(){
                 $(this).dialog('close');
            }
        }
    });	
	
	//$.getScript(gurl+'js/mantenedor/upload.js');
    $("#cuerpo_frm").html(html)
    $("#cuerpo_frm").dialog("open");
}

/**************************/

function dialogo_zebra_rutaprenda(msg,type,title,width,iId,iIdTipoDocumento){
	$.Zebra_Dialog(msg, {
		'type':  type,
		'title':    false,
		'buttons': [{caption:'Ver Actualización', callback: function() {
					window.open(gurl+'c_desarrollo/ver_rutaprenda_vista/'+iId+'/'+iIdTipoDocumento,'_blank');
		}}],
		'width': width,
		'overlay_opacity': 0.5
	});
}

function recuperar_rutaprenda(){
	
	$.post(gurl+'c_desarrollo/recuperar_rutaprenda',{},function(data){

		if(data){
			var documento = '';
			if(data['iIdTipoDocumento'] == 1)documento = 'del Req.';
			if(data['iIdTipoDocumento'] == 2)documento = 'de la Proforma de Muestrario';
			if(data['iIdTipoDocumento'] == 3)documento = 'de la Proforma de Producción';
			dialogo_zebra_rutaprenda("Se actualizó la Ruta de prenda "+documento+" "+data['cCodigoRequerimientoMuestra'],'information','',250,data['iId'],data['iIdTipoDocumento']);
			
		}
		
	}, 'json');
	
}

function dialogo_zebra_consumotela(msg,type,title,width,iId){
	$.Zebra_Dialog(msg, {
		'type':  type,
		'title':    false,
		'buttons': [{caption:'Ver Actualización', callback: function() {
					//window.open(gurl+'c_desarrollo/ver_consumo_telas/'+iId,'_blank');
					window.open(gurl+'c_desarrollo/consumo_telas','_blank');
		}}],
		'width': width,
		'overlay_opacity': 0.5
	});
}

function recuperar_consumotela(){
	
	$.post(gurl+'c_desarrollo/recuperar_consumotela',{},function(data){

		if(data){
			var documento = '';
			if(data['iIdTipoDocumento'] == 2)documento = 'de la Proforma de Muestrario';
			if(data['iIdTipoDocumento'] == 3)documento = 'de la Proforma de Producción';
			dialogo_zebra_consumotela("Se actualizó el Consumo de Tela "+documento+" "+data['vNumProformaMuestra'],'information','',250,data['iIdCuadroTela']);
			
		}
		
	}, 'json');
	
}

function dialogo_zebra_cuadroavio(msg,type,title,width,iId){
	$.Zebra_Dialog(msg, {
		'type':  type,
		'title':    false,
		'buttons': [{caption:'Ver Actualización', callback: function() {
					window.open(gurl+'c_desarrollo/ver_cuadro_avio/'+iId,'_blank');
		}}],
		'width': width,
		'overlay_opacity': 0.5
	});
}

function recuperar_cuadroavio(){
	
	$.post(gurl+'c_desarrollo/recuperar_cuadroavio',{},function(data){

		if(data){
			var documento = '';
			if(data['iIdTipoDocumento'] == 2)documento = 'de la Proforma de Muestrario';
			if(data['iIdTipoDocumento'] == 3)documento = 'de la Proforma de Producción';
			dialogo_zebra_cuadroavio("Se actualizó el Cuadro de avio "+documento+" "+data['vNumProformaMuestra'],'information','',250,data['iIdCuadroAvio']);
			
		}
		
	}, 'json');
	
}

/*************************************************************/
// NumeroALetras
// @author   Rodolfo Carmona
/*************************************************************/
function Unidades(num){

  switch(num)
  {
    case 1: return "UN";
    case 2: return "DOS";
    case 3: return "TRES";
    case 4: return "CUATRO";
    case 5: return "CINCO";
    case 6: return "SEIS";
    case 7: return "SIETE";
    case 8: return "OCHO";
    case 9: return "NUEVE";
  }

  return "";
}

function Decenas(num){

  decena = Math.floor(num/10);
  unidad = num - (decena * 10);

  switch(decena)
  {
    case 1:   
      switch(unidad)
      {
        case 0: return "DIEZ";
        case 1: return "ONCE";
        case 2: return "DOCE";
        case 3: return "TRECE";
        case 4: return "CATORCE";
        case 5: return "QUINCE";
        default: return "DIECI" + Unidades(unidad);
      }
    case 2:
      switch(unidad)
      {
        case 0: return "VEINTE";
        default: return "VEINTI" + Unidades(unidad);
      }
    case 3: return DecenasY("TREINTA", unidad);
    case 4: return DecenasY("CUARENTA", unidad);
    case 5: return DecenasY("CINCUENTA", unidad);
    case 6: return DecenasY("SESENTA", unidad);
    case 7: return DecenasY("SETENTA", unidad);
    case 8: return DecenasY("OCHENTA", unidad);
    case 9: return DecenasY("NOVENTA", unidad);
    case 0: return Unidades(unidad);
  }
}//Unidades()

function DecenasY(strSin, numUnidades){
  if (numUnidades > 0)
    return strSin + " Y " + Unidades(numUnidades)

  return strSin;
}//DecenasY()

function Centenas(num){

  centenas = Math.floor(num / 100);
  decenas = num - (centenas * 100);

  switch(centenas)
  {
    case 1:
      if (decenas > 0)
        return "CIENTO " + Decenas(decenas);
      return "CIEN";
    case 2: return "DOSCIENTOS " + Decenas(decenas);
    case 3: return "TRESCIENTOS " + Decenas(decenas);
    case 4: return "CUATROCIENTOS " + Decenas(decenas);
    case 5: return "QUINIENTOS " + Decenas(decenas);
    case 6: return "SEISCIENTOS " + Decenas(decenas);
    case 7: return "SETECIENTOS " + Decenas(decenas);
    case 8: return "OCHOCIENTOS " + Decenas(decenas);
    case 9: return "NOVECIENTOS " + Decenas(decenas);
  }

  return Decenas(decenas);
}//Centenas()

function Seccion(num, divisor, strSingular, strPlural){
  cientos = Math.floor(num / divisor)
  resto = num - (cientos * divisor)

  letras = "";

  if (cientos > 0)
    if (cientos > 1)
      letras = Centenas(cientos) + " " + strPlural;
    else
      letras = strSingular;

  if (resto > 0)
    letras += "";

  return letras;
}//Seccion()

function Miles(num){
  divisor = 1000;
  cientos = Math.floor(num / divisor)
  resto = num - (cientos * divisor)

  strMiles = Seccion(num, divisor, "UN MIL", "MIL");
  strCentenas = Centenas(resto);

  if(strMiles == "")
    return strCentenas;

  return strMiles + " " + strCentenas;

  //return Seccion(num, divisor, "UN MIL", "MIL") + " " + Centenas(resto);
}//Miles()

function Millones(num){
  divisor = 1000000;
  cientos = Math.floor(num / divisor)
  resto = num - (cientos * divisor)

  strMillones = Seccion(num, divisor, "UN MILLON", "MILLONES");
  strMiles = Miles(resto);

  if(strMillones == "")
    return strMiles;

  return strMillones + " " + strMiles;

  //return Seccion(num, divisor, "UN MILLON", "MILLONES") + " " + Miles(resto);
}//Millones()

function NumeroALetras(num){
  var data = {
    numero: num,
    enteros: Math.floor(num),
    centavos: (((Math.round(num * 100)) - (Math.floor(num) * 100))),
    letrasCentavos: "",
    letrasMonedaPlural: "CORDOBAS",
    letrasMonedaSingular: "CORDOBA"
  };

  //if (data.centavos > 0)
    data.letrasCentavos = "Y " + data.centavos + "/100";

  if(data.enteros == 0)
    return "CERO " + data.letrasMonedaPlural + " " + data.letrasCentavos;
  if (data.enteros == 1)
    return Millones(data.enteros) + " " + data.letrasCentavos;
  else
    return Millones(data.enteros) + " " + data.letrasCentavos;
}//NumeroALetras()


function dialogo_zebra_confirmacion(msg,type,title,width,enlace){
	$.Zebra_Dialog(msg, {
		'type':  type,
		'title':    title,
		'buttons': [
					{caption:'Si', callback: function() {location.href = enlace;}}
					,{caption:'No', callback: function() {$('.ZebraDialog').remove();$('.ZebraDialogOverlay').remove();}}
					],
		'width': width,
		'overlay_opacity': 0.5
	});
}


