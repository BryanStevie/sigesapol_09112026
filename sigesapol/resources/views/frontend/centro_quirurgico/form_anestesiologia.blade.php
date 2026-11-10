
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

  let procedimientosSeleccionadosAnestesia =[];
  let filasmedicamentosAnestesia = 0;
  let listIdDiagnosticoAnestesia =[];
  let listIdDiagnosticoObjetosAnestesia =[];
  let listIdDiagnosticoAnestesiaAdicionalReceta =[];
  let listIdDiagnosticoObjetosAnestesiaAdicionalReceta =[];

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
    	
	//--------------------------------------------------------------------------
    $(document).ready(function () {
        $('#modalNuevoAnestesia').on('hidden.bs.modal', function () {
            $('#modalAnestesia').modal('handleUpdate');
            $('body').addClass('modal-open'); 
        });

        let flag_anestesia_quirurgica = $('#flag_anestesia_quirurgica').val();
        
        if (flag_anestesia_quirurgica == '1') {
          $('#addConfirmaIntervencion').click();
          $('.addConfirmaIntervencion').show();
        }

        let flag_observacion = $('#flag_observacion').val();

        if (flag_observacion == '1') {
          $('#addObservacionIntervencionQuirurgica').click();
          $('.addObservacionIntervencionQuirurgica').show();
        }
        //modalRadiologico();

        getProductoAdicionalAnestesia();

        getDiagnosticosDePrestacion();
        
    
    });

    $(document).on("click", ".EditarFilaProductosAnestesia", function() {
      let row = $(this).closest("tr");

      row.find("input, select").prop("disabled", false);

      row.find("input[name='accionRowProductoAnestesia[]']").val("edit");

      $(this).closest('tr').css('background-color', '#87c787');
    });

    function getDiagnosticosDePrestacion() {
      $('.id_diagcq').each(function() {
        let value = $(this).val();
        console.log(`Valor del diagcq :${value}`);
        addNewArrayAdicionalReceta(value)
      });
    }

    function addNewArrayAdicionalReceta(id_diagnostico) {

    console.log(id_diagnostico);

    if(  listIdDiagnosticoObjetosAnestesiaAdicionalReceta.findIndex( e  => e.id == id_diagnostico) ==-1 ){
      listIdDiagnosticoAnestesiaAdicionalReceta.push(id_diagnostico);// solo es para validar si existen datos

      if (listIdDiagnosticoAnestesiaAdicionalReceta.length > 0) {
        let codigo = JSON.stringify(id_diagnostico);
        console.log(codigo);
        
        // Realizar la petición AJAX usando el código id_diagnostico
        $.ajax({
            "dataType": 'json',
            "type": "POST",
            "url": "/obtenerdatosDiagnosticos",
            "data":{
              codigo : codigo,
              _token: "{{ csrf_token() }}"
                  },
            "success": function (result) {
                console.log(`Resultado de obtenerdatosDiagnosticos : ${result.objeto}`);
                
                if (result) {
                  listIdDiagnosticoObjetosAnestesiaAdicionalReceta.push(result.objeto);   
                  console.log(JSON.stringify(listIdDiagnosticoObjetosAnestesiaAdicionalReceta));
                  updateSelectElementsAdicionalReceta();
                } else {
                  console.log(`Retorna vacio ...!!`);
                }
            },
            "error": function (msg, textStatus, errorThrown) {
                //location.href="login";
                console.log(msg, textStatus, errorThrown);
                
            }
        });
      }
    }
    }

    function updateSelectElementsAdicionalReceta() {
    // Obtener todos los elementos <select> con la clase "select-diagnostico"
    let selectElements = document.querySelectorAll("#diagnosticosAdicionalTempAnestesia");
    console.log(selectElements);

    selectElements.forEach((selectElement) => {
      // Almacenar el valor actualmente seleccionado
      let currentValue = selectElement.value;
      console.log(currentValue);
      
      // Eliminar las opciones actuales
      selectElement.innerHTML = "";


      // Agregar las opciones basadas en listIdDiagnosticoObjetos
      listIdDiagnosticoObjetosAnestesiaAdicionalReceta.forEach((item) => {
        let option = document.createElement("option");
        option.value = item.id;
        option.textContent = item.codigo;
        selectElement.appendChild(option);
      });

      // Verificar si el valor seleccionado aún está disponible en las opciones
      let optionExists = Array.from(selectElement.options).some(
        (option) => option.value === currentValue
      );

      // Si el valor 1  e2  ado no existe en las opciones, cambiar a "Seleccione"
      if (!optionExists) {
        selectElement.value = "";
      } else {
        // Si el valor seleccionado aún está disponible en las opciones, restablecer el valor seleccionado
        selectElement.value = currentValue;
        console.log()
      }
    });
    }

    function validarFormulario() {
        
        const observacion = $('#observacion_examen').val();

        let msg = "";
        let sw = true;
        
        if(observacion==""){
            msg+="Debe ingresar una observación.<br>";
            sw = false;
        }

        if (sw == false) {
            Swal.fire({
                icon: 'error',
                title: 'Alerta',
                html: msg,
                showConfirmButton: true,
            });
            $('#btnReg').prop("disabled",false);
            return sw;
        } else {
            submitFrm();
        }

        return false;

    }

  function submitFrm() {
        bootbox.confirm({
            message: "Se registrará todos los examenes ingresados, ¿Está seguro de continuar?",
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result == true) {
                    document.frmRadiologia.submit();
                }
            }
        });
    }
    //--------------------------------------------------------------------------
    
    const resetSelectAndTriggerChange = (selectId) => { // Resetear select
        const selectElement = document.getElementById(selectId);
        if (selectElement) {
            selectElement.value = ''; // Reset value
            const event = new Event('change', { bubbles: true }); // Create a change event
            selectElement.dispatchEvent(event); // Dispatch the event
        }
    }

	function htmlEntities(str) {
      return String(str).replace('&ntilde;', 'ñ')
                        .replace('&Ntilde;', 'Ñ')
                        .replace('&amp;', '&')
                        .replace('&Ntilde;', 'Ñ')
                        .replace('&ntilde;', 'ñ')
                        .replace('&Ntilde;', 'Ñ')
                        .replace('&Agrave;', 'À')
                        .replace('&Aacute;', 'Á')  
                        .replace('&Acirc;','Â')
                        .replace('&Atilde;','Ã')   
                        .replace('&Auml;','Ä')
                        .replace('&Aring;','Å')
                        .replace('&AElig;','Æ')
                        .replace('&Ccedil;','Ç')
                        .replace('&Egrave;','È')
                        .replace('&Eacute;','É')
                        .replace('&Ecirc;', 'Ê')
                        .replace('&Euml;','Ë')
                        .replace(   '&Igrave;', 'Ì')
                        .replace('&Iacute;', 'Í'    )
                        .replace('&Icirc;', 'Î' )
                        .replace(   '&Iuml;', 'Ï')
                        .replace(   '&ETH;', 'Ð')
                        .replace(   '&Ntilde;', 'Ñ')
                        .replace(   '&Ograve;', 'Ò')
                        .replace(   '&Oacute;', 'Ó')
                        .replace('&Ocirc;', 'Ô' )
                        .replace(   '&Otilde;', 'Õ')
                        .replace('&Ouml;', 'Ö'  )
                        .replace('&Oslash;', 'Ø'    )
                        .replace(   '&Ugrave;' ,'Ù')
                        .replace(   '&Uacute;', 'Ú')
                        .replace(   '&Ucirc;', 'Û')
                        .replace(   '&Uuml;', 'Ü')
                        .replace(   '&Yacute;', 'Ý')
                        .replace('&THORN;', 'Þ' )
                        .replace(   '&szlig;', 'ß')
                        .replace(   '&agrave;', 'à')
                        .replace(   '&aacute;', 'á')
                        .replace(   '&acirc;', 'â')
                        .replace(   '&atilde;', 'ã')
                        .replace('&auml;', 'ä'  )
                        .replace(   '&aring;', 'å')
                        .replace(   '&aelig;', 'æ')
                        .replace(   '&ccedil;', 'ç')
                        .replace('&egrave;', 'è'    )
                        .replace('&eacute;', 'é'    )
                        .replace('&ecirc;', 'ê' )
                        .replace('&euml;', 'ë'  )
                        .replace(   '&igrave;', 'ì')
                        .replace('&iacute;', 'í'    )
                        .replace('&icirc;', 'î' )
                        .replace('&iuml;', 'ï'  )
                        .replace('&eth;', 'ð'   )
                        .replace(   '&ntilde;', 'ñ')
                        .replace('&ograve;','ò')
                        .replace('&oacute;','ó')
                        .replace('&ocirc;','ô')
                        .replace('&otilde;','õ')
                        .replace('&ouml;','ö')
                        .replace('&oslash;','ø')
                        .replace('&ugrave;','ù')
                        .replace('&uacute;','ú')
                        .replace('&ucirc;','û')
                        .replace('&uuml;' , 'ü')   
                        .replace('&yacute;', 'ý')  
                        .replace('&thorn;', 'þ')
                        .replace('&yuml;', 'ÿ');
    }

    function esEntero(evt){			
        // code is the decimal ASCII representation of the pressed key.
        var code = (evt.which) ? evt.which : evt.keyCode;
        
        if(code==8) { // backspace.
        return true;
        } else if(code>=48 && code<=57) { // is a number.
        return true;
        } else{ // other keys.
        return false;
        }
    }

    const modalRadiologico = () => {
        const dni_beneficiario = $("#dni_beneficiario").val();
        const url = "{{ url('modal_historial_radiologia') }}";
        $.ajax({
            url: `${url}/${dni_beneficiario}`,
            type: "GET",
            success: function (result) {  
                    $("#divRadiologico").html(result);
                    $('#tablemodalm2').DataTable({
                        "sPaginationType": "full_numbers",
                        
                        "language": {"url": "/js/Spanish.json"},
                    });
            }
        });
    }

    $('#addRecetaValeAnestesia').on('click', function () {
      var addRecetaValeAnestesia = $('#addRecetaValeAnestesia').attr("aria-pressed");
          console.log(addRecetaValeAnestesia);
          
      $("#fsRecetaValeAnestesia").hide();
      if(addRecetaValeAnestesia == "false"){
          getProductoAndAddProductoAnestesia();
          $("#fsRecetaValeAnestesia").show();
          }
    });

    function getProductoAndAddProductoAnestesia() {
        getProductoAnestesia();
        //AddProducto();
    }

    $("#id_farmacia_receta").select2({
        width: 'resolve'
    });

    function getProductoAnestesia(){
	
        $('#tblAnestesia tbody').html('');
        
        if($('#frmCqAnestesia #id_servicio').val()=="") return false;
        
        //var id_ipress 		= $('#id_ipress').val();
        var id_ipress 		= $('#frmCqAnestesia #id_establecimiento').val();
        var id_consultorio 	= $('#frmCqAnestesia #id_servicio').val();
        //var tipo_receta		= $('#tipo_receta').val();
        //var id_tiporeceta 	= $('#id_tiporeceta').val();
        var id_tiporeceta 	= $('#frmCqAnestesia #id_tipo_atencion').val();
        
        var id_farmacia     = $('#frmCqAnestesia #id_farmacia_receta').val();
        console.log(id_farmacia);
        
        var nivel     = $('#id_nivel').val();
        var x  = 0;
        var dni = $('#dni_beneficiario').val();
        var interconsulta = $('#interconsulta').val();
        if(id_tiporeceta == "3"){ 
            
            if(interconsulta=='SI'){
                if (id_consultorio==104){
                    id_consultorio2=104
                } else {
                    id_consultorio2=105
                }
                $.ajax({
                //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + id_consultorio2+ '/' + dni,
                url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +id_consultorio2,
                success: function(result){                    
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar--</option>";
                    $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                    });                 
                    $('#cmbMedicamentoTempAnestesia').html(newOption);
                }
                }); 
            } else {  
            
                if(x==0){ //SIN INTERCONSULTA
                    if (id_consultorio==104){
                        $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 104,
                            //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
                            success: function(result){                    
                            var newOption = "";
                            newOption = "<option value=''>--Seleccionar--</option>";
                            $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                            });                 
                            $('#cmbMedicamentoTempAnestesia').html(newOption);
                            }
                        });
                    } else {
                        if (id_consultorio==21){
                            $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 21,
                            //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
                            success: function(result){                    
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                });                 
                                $('#cmbMedicamentoTempAnestesia').html(newOption);
                                //$("#id_medico").select2({max_selected_options: 4});
                            
                            }
                            });
                        } else {
                            if (id_consultorio==72){
                                $.ajax({
                                //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
                                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 72,
                                success: function(result){                    
                                    var newOption = "";
                                    newOption = "<option value=''>--Seleccionar-</option>";
                                    $(result).each(function (ii, oo) {
                                    newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                    });                 
                                    $('#cmbMedicamentoTempAnestesia').html(newOption);
                                }
                                });
                            } else {
                                $.ajax({
                                url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
                                //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
                                success: function(result){                    
                                    var newOption = "";
                                    newOption = "<option value=''>--Seleccionar-</option>";
                                    $(result).each(function (ii, oo) {
                                    newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                    });                 
                                    $('#cmbMedicamentoTempAnestesia').html(newOption);
                                }
                                });  
                            }
                        
                        }
                    }
                } else {
                    if (id_consultorio==104){
                        $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
                            success: function(result){                    
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar--</option>";
                                $(result).each(function (ii, oo) {
                                    newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                });                 
                                $('#cmbMedicamentoTempAnestesia').html(newOption);
                            }
                        });
                    } else {
                        if (id_consultorio==21){
                            $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 21 + '/' + dni,
                            success: function(result){                    
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                });                 
                                $('#cmbMedicamentoTempAnestesia').html(newOption);
                            }
                            });
                        } else {
                            if (id_consultorio==72){
                                $.ajax({
                                //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
                                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 72,
                                success: function(result){                    
                                    var newOption = "";
                                    newOption = "<option value=''>--Seleccionar-</option>";
                                    $(result).each(function (ii, oo) {
                                    newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                    });                 
                                    $('#cmbMedicamentoTempAnestesia').html(newOption);
                                }
                                });
                            } else {
                                $.ajax({
                                url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 105+ '/' + dni,
                                success: function(result){                    
                                    var newOption = "";
                                    newOption = "<option value=''>--Seleccionar-</option>";
                                    $(result).each(function (ii, oo) {
                                    newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                    });                 
                                    $('#cmbMedicamentoTempAnestesia').html(newOption);
                                }
                                });  
                            }
                        }
                    }  
                }         
            }       
        }else{
            console.log('entro en condición tipo de atención 1 ...');
            
            if(id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia!=18 && id_farmacia!=25){
                console.log('entro en condición tipo de atención 1 ...');
                if(id_tiporeceta==2){
                    id_consultorio=76;
                }
                $.ajax({
                    url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
                    success: function(result){                    
                        var newOption = "";
                        newOption = "<option value=''>--Seleccionar-</option>";
                        $(result).each(function (ii, oo) {
                            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                        });                 
                        $('#cmbMedicamentoTempAnestesia').html(newOption);
                        //$("#id_medico").select2({max_selected_options: 4});
                    
                    }
                });
            
            }
            if((id_tiporeceta == 1 || id_tiporeceta == 5 || id_tiporeceta == 6) && id_farmacia == 21){ //nutricional
                if(id_tiporeceta==5 || id_tiporeceta == 1){
                    id_consultorio = 110;
                } else {
                    id_consultorio = 111;
                }
                    
                $.ajax({
                    url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
                    success: function(result){                    
                        var newOption = "";
                        newOption = "<option value=''>--Seleccionar-</option>";
                        $(result).each(function (ii, oo) {
                            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                        });                 
                        $('#cmbMedicamentoTempAnestesia').html(newOption);
                        //$("#id_medico").select2({max_selected_options: 4});
                    
                    }
                });
            }
            if(id_farmacia==18 || id_farmacia==25 || id_tiporeceta == 4){ //centro quirurgico
                if(id_tiporeceta == 1){
                    if(id_farmacia == 25){
                        $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
                            success: function(result){                    
                            var newOption = "";
                            newOption = "<option value=''>--Seleccionar-</option>";
                            $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                            });                 
                            $('#cmbMedicamentoTempAnestesia').html(newOption);
                            }
                        });
                    } else {
                        if(id_consultorio == 72){
                            $.ajax({
                                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                                '/' + 72,
                                success: function(result) {
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function(ii, oo) {
                                    newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                                    .nombre + ", " + oo.abreviatura + "</option>";
                                });
                                $('#cmbMedicamentoTempAnestesia').html(newOption);
                                }
                            });
                        } else {
                            $.ajax({
                            url: '/obtenerProductosFarmaciasByNivel/'+id_farmacia+'/' + id_tiporeceta+'/'+id_consultorio,
                            success: function(result){                    
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function (ii, oo) {
                                    newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                    });                 
                                    $('#cmbMedicamentoTempAnestesia').html(newOption);
                                }
                            }); 
                        }  
                    }
                    //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
                } else {
                    if(id_tiporeceta == 4){
                        if(id_consultorio == 72){
                            $.ajax({
                                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                                '/' + 72,
                                success: function(result) {
                                    var newOption = "";
                                    newOption = "<option value=''>--Seleccionar-</option>";
                                    $(result).each(function(ii, oo) {
                                        newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                                        .nombre + ", " + oo.abreviatura + "</option>";
                                    });
                                    $('#cmbMedicamentoTempAnestesia').html(newOption);
                                }
                            });
                        } else {
                            $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio_quirurgico/' + id_ipress+ '/' + id_consultorio,
                            success: function(result){                    
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                });                 
                                $('#cmbMedicamentoTempAnestesia').html(newOption);
                                //$("#id_medico").select2({max_selected_options: 4});
                            
                            }
                            });  
                        }
                    }
                }
            }
        }
    }

    $('#tblAnestesia tbody').on('click', 'button.deleteFila', function () {
      console.log('click');
      
		  var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
    
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      
      // Imprime la posición del elemento 'input' en la consola
      console.log("La posición del input es: " + filaPosition);
      eliminarDiagnosticoXProductoAnestesia(filaPosition);
      // Elimina la fila
      obj.parent().parent().remove();
      filasmedicamentosAnestesia--;
    });
  
    function eliminarDiagnosticoXProductoAnestesia(posicion) {
      // Se elimina el elemento en la posición dada
      procedimientosSeleccionadosAnestesia.splice(posicion, 1);

      // Se actualiza la posición de los elementos restantes
      procedimientosSeleccionadosAnestesia.forEach((item, index) => {
        item.posicion = index;
      });
      console.log(procedimientosSeleccionadosAnestesia);
    }
    
    $('#addProductoAnestesia').on('click', function () {
      AddProductoAnestesia();
    });

    function AddProductoAnestesia(){
        console.log('Entro en función AddProductoAnestesia() ... ');
        
        var tipo_receta = 1;
        var id_consultorio = 72;
        if(tipo_receta == ""){
            bootbox.alert("Seleccione el tipo atención");
            return false;
        }		
        if(id_consultorio == ""){
            bootbox.alert("Seleccione un servicio");
            return false;
        }
        var newRow = "";
        var ind = $('#tblAnestesia tbody tr').length;
        var ind = filasmedicamentosAnestesia
        var tabindex = 71;
            var nuevalperiodo = "";
        if(ind > 0){
            $('.cmbMedicamentoAnestesia').each(function(){
                var ind_tmp = $(this).attr("ind");
                if(ind_tmp >= ind){
                                    ind=Number(ind_tmp)+1;
                                    tabindex+=3;
                }
            });
                
            fila_medicamentos=$('#tblAnestesia tbody').children('tr');
            rowIndex=$('#hidden_anestesia_rowIndex').val();
            var idval = ind - 1;
            <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>    
                fila_medicamento=fila_medicamentos[idval];
                var valante = $(fila_medicamento).find('#nro_med_entregados').val();
                if(valante == ""){
                    bootbox.alert("Ingrese cantidad entregada en el producto anterior");
                    return false;
                }
            <?php //endif;?>
                
            //nuevalperiodo = document.getElementById('tiempo_periodo'+idval).value;
        }
        
        var item_producto = "";
        $('#cmbMedicamentoTempAnestesia option').each(function() {
            item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
            "</option>"
        });

        var item_via_administracion = '';
        item_via_administracion+='<option value="0">--Seleccionar--</option>';
        item_via_administracion+='<option value="1">Oral</option>';
        item_via_administracion+='<option value="2">Sublingual</option>';
        item_via_administracion+='<option value="3">Rectal</option>';
        item_via_administracion+='<option value="4">Intravenosa</option>';
        item_via_administracion+='<option value="5">Intramuscular</option>';
        item_via_administracion+='<option value="6">Subcutánea</option>';
        item_via_administracion+='<option value="7">Dérmica</option>';
        item_via_administracion+='<option value="8">Nasal</option>';
        item_via_administracion+='<option value="9">Oftalmológica</option>';
        item_via_administracion+='<option value="10">Inhalatoria</option>';
        item_via_administracion+='<option value="11">Epidural</option>';
        item_via_administracion+='<option value="12">Intratecal</option>';
        item_via_administracion+='<option value="13">Vaginal</option>';
        item_via_administracion+='<option value="14">Intraarticular</option>';
        item_via_administracion+='<option value="15">No Aplica</option>';
        item_via_administracion+='<option value="16">Otros</option>';
        
        newRow +='<tr>';
        newRow +='<td><select class="form-control cmbMedicamentoAnestesia" id="cmbMedicamentoAnestesia' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto[]" style="width: 450px">' + item_producto + '</select></td>';
        
        //newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
        //newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
        newRow +='<input type="hidden" name="nro_stocks_almacen[]" id="nro_stocks_almacen" value=""/>';
        newRow +='<input type="hidden" name="nro_stocks_establecimiento[]" id="nro_stocks_establecimiento" value=""/>';
        newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
        <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
        newRow +='<td><input onkeypress="soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind +'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="' + nuevalperiodo +'"/>';
        newRow +='<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion_anestesia" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion_anestesia[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';
        newRow += '<td><input type="text" name="posologia[]" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
        ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';
        
        <?php //endif;?>
        newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
        newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
        newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
        newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
        newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
        newRow +='<td><input onKeyPress="soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados_anestesia[]" required="" id="nro_med_solictados_anestesia' +ind +'" class="limpia_text nro_solicitado nro_med_solictados_anestesia input-sm" style="margin-left:4px; width:100px" /></td>';
        
        <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
        //newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
        <?php //endif;?>
        
        newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico-anestesia"  id="miSelectProdAnestesia' + ind + '" name="miSelectProdAnestesia[]"></select></td>';  
        newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
        newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';

        newRow +='</tr>';
        $('#tblAnestesia tbody').append(newRow);
        $("#cmbMedicamentoAnestesia" + ind).select2({max_selected_options: 4});
        
        const selectElementProd = document.getElementById('miSelectProdAnestesia' + ind);

        listIdDiagnosticoObjetosAnestesia.forEach((item) => {
            let option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.nombre;
            selectElementProd.appendChild(option);
        });
        $("#abrir-modal-" + ind).click(function(e) {
            e.stopPropagation();
        
            abrirModal(ind);
        });
        
        $("#nro_med_solictados_anestesia"+ind).keypress(function(e){
            if(e.which == 13) {
                <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
                    AddProductoAnestesia();
                <?php //else:?>
                    validproductoseleccionado({{Auth::user()->id_farmacia}},this);
                <?php //endif;?>
            }
        });

        $("#add_producto_anestesia"+ind).click(function(e){
            AddProductoAnestesia();
            validproductoseleccionado({{Auth::user()->id_farmacia}},this);
            btnAgregarProducto = $(this);
            $(btnAgregarProducto).attr('disabled', 'disabled');
        });

        function validproductoseleccionado(id_farmacia, btn) {//Jose
            var dni_beneficiario = $('#dni_beneficiario').val();
            var nro_dias = 30;
            var objTr = $(btn).closest("tr");
            var id_producto = objTr.find(".cmbMedicamentoAnestesia").val();
            $.ajax({
                url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
                success: function (result) {
                var msg   = "";
                var n     = 0;
                var flag  = true;
                    for(var key in result) {
                        if(n == 0){
                            msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";              
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
                        msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
                        n++;
                    }
                    if(msg != "") {
                        bootbox.confirm({
                            title: "Alerta",
                            message: msg,
                            buttons: {
                                cancel: {
                                    label: '<i class="fa fa-times"></i> Cancelar'
                                },
                                confirm: {
                                    label: '<i class="fa fa-check"></i> Continuar'
                                }
                            },
                            callback: function (result) {
                                if(result==true){
                                verlotes(id_farmacia, btn);
                                }
                            }
                        });
                    }
                }
            });
        }
        
        
        $("#cmbMedicamentoAnestesia"+ind).on("change", function (e) {
            var flagx = 0;
            cmb = $(this);
            id_producto = $("#cmbMedicamentoAnestesia"+ind).val();
            id_user={{Auth::user()->id}};
            var listaprueba = [];
            $('.cmbMedicamentoAnestesia').each(function(){
                var ind_tmp = $(this).val();
                listaprueba.push(ind_tmp)
                if($(this).val() == id_producto)flagx++;
            });
            console.log(`El array de productos : ${listaprueba}`);
            
            if(flagx > 1){
                bootbox.alert("El producto farmaceutico ya ha sido ingresado");
                $("#cmbMedicamentoAnestesia"+ind).val("").trigger("change");
                                return false;
            }
            var id_farmacia_receta = $('#id_farmacia').val();
            option = {
              url: '../cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
              type: 'GET',
              dataType: 'json',
              data: {}
            };
            $.ajax(option).done(function (data) {
                
                var cantidad = data.cantidad;
                var cantidadEstablecimiento = data.cantidadEstablecimiento;
                var cantidadAlmacen = data.cantidadAlmacen;
                $(cmb).closest("tr").find(".limpia_text").val("");                
                //$(cmb).closest("tr").find("#nro_stocks").val(data);
                $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
                $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
                $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
                <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
                $(cmb).closest("tr").find(".tiempo_periodo").focus();
                <?php //endif;?>
                $(cmb).closest("tr").find("#nro_med_solictados_anestesia").val("");  
                $(cmb).closest("tr").find("#nro_med_entregados").val("");
                $(cmb).closest("tr").find("#lotes_lote").val("");
                $(cmb).closest("tr").find("#lotes_cantidad").val("");
                $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
                $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
            });
        
        });
        filasmedicamentosAnestesia++;
    }

    $('#tblAnestesia tbody').on('click', 'button.deleteFila', function () {
		  var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
    
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      
      // Imprime la posición del elemento 'input' en la consola
      console.log("La posición del input es: " + filaPosition);
      eliminarDiagnosticoXProductoAnestesia(filaPosition);
      // Elimina la fila
      obj.parent().parent().remove();
      filasmedicamentosAnestesia--;
    });


    function eliminarDiagnosticoXProductoAnestesia(posicion) {
        // Se elimina el elemento en la posición dada
        procedimientosSeleccionadosAnestesia.splice(posicion, 1);

        // Se actualiza la posición de los elementos restantes
        procedimientosSeleccionadosAnestesia.forEach((item, index) => {
            item.posicion = index;
        });
        console.log(procedimientosSeleccionadosAnestesia);
    }

    // Abrir el segundo modal
    function crearAnestesia() {
        
        $('#modalNuevoAnestesia').modal('show');
    }
    // Abrir el segundo modal
    function crearBalanceHidrico() {
        
        $('#modalNuevoBalanceHidrico').modal('show');
    }
    
    $('#addConfirmaIntervencion').on('click', function () {
        const addConfirmaIntervencion = $('#addConfirmaIntervencion').attr("aria-pressed");
        $('.addConfirmaIntervencion').hide();
        if ( addConfirmaIntervencion == "false" ) {
            $('.addConfirmaIntervencion').show();
        }
	});
    
    $('#addObservacionIntervencionQuirurgica').on('click', function () {
        const addObservacionIntervencionQuirurgica = $('#addObservacionIntervencionQuirurgica').attr("aria-pressed");
        $('.addObservacionIntervencionQuirurgica').hide();
        if ( addObservacionIntervencionQuirurgica == "false" ) {
            $('.addObservacionIntervencionQuirurgica').show();
        }
	});

  $('#tblDiagnosticoAnestesia tbody').on('click', 'button.deleteFila', function () {
    var obj = $(this);
    var inputVal = obj.parent().siblings().find('input').val();
    let posicion =inputVal.indexOf("-");
    let codigo = inputVal.substring(0,posicion);
    eliminarDiagnosticosarrAnestesia(codigo);
    console.log("el codigo es" + codigo);
		obj.parent().parent().remove();
	});

  function eliminarDiagnosticosarrAnestesia(codigon){
    let codigo2 =codigon.trim()

    let listIdDiagnosticoObjetos2 =[];
      for (let index = 0; index < listIdDiagnosticoObjetos.length; index++) {
        const element = listIdDiagnosticoObjetos[index];
        if(element.codigo !== codigo2){
          listIdDiagnosticoObjetos2.push(element)
        }
        
      }
      listIdDiagnosticoObjetos=[];
      listIdDiagnosticoObjetos =listIdDiagnosticoObjetos.concat(listIdDiagnosticoObjetos2);



      procedimientosSeleccionadosAnestesia= procedimientosSeleccionadosAnestesia.map((item) => {
        if (item.codigo === codigo2) {
          return { ...item, id: "" };
        } else {
          return item;
        }
      });

      console.log("la lista con eliminacion de los diagnosticos x productos es : " + JSON.stringify(procedimientosSeleccionadosAnestesia))


      
    console.log("la lista con eliminacion es " + JSON.stringify(listIdDiagnosticoObjetos));
    updateSelectElements();   

  }

  $('#addDiagnosticoAnestesia').on('click',function(){
		AddDiagnosticoAnestesia();
	});

  function AddDiagnosticoAnestesia(){
	var newRow = "";
	var ind = $('#tblDiagnosticoAnestesia tbody tr').length;
	
	if(ind > 0){
		$('.id_diagnostico_anestesia').each(function(){
			var ind_tmp = $(this).data("id");
			if(ind_tmp => ind){
				ind=Number(ind_tmp)+1;
			}
		});
					
		fila_medicamentos=$('#tblDiagnosticoAnestesia tbody').children('tr');
		rowIndex=$('#hidden_rowIndex').val();
		var idval = ind - 1;
		fila_medicamento=fila_medicamentos[idval];
		
	}
	
	var item_tipo_diagnostico = '';
	item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
	item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
	item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
	item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';
  newRow += '<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico_anestesia[]" tabindex="8" required="" id="nombre_diagnostico_anestesia' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control id_diagnostico_anestesia typeahead tt-query" id="id_diagnostico_anestesia' + ind +'" name="id_diagnostico_anestesia[]" value="" data-id='+ind+'></td>';
  newRow += '<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico' + ind +'" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblDiagnosticoAnestesia tbody').append(newRow);
	var sexo	= $('#frmCqAnestesia #nomsexo').val();
	if(sexo=='M'){
		genero=1;
	}
	else{
		if(sexo=='F'){
			genero=2;
		}
	}
	
	$("#nombre_diagnostico_anestesia"+ind).autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
			$.ajax({
				//url: '../obtener_diagnostico/' + request.term,
				url: '../obtener_diagnostico_genero/' + request.term + '/'+ genero,
				dataType: "json",
				success: function(data)  {
					let listaDiagnosticoSeleccionado = $(".id_diagnostico_anestesia").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
          response(filtro);
				}					
			})
	
		},select:  function(e, ui) {
			var id_diagnostico_anestesia = ui.item.id;
			$("#id_diagnostico_anestesia"+ind).val(id_diagnostico_anestesia);
			$("#nombre_diagnostico_anestesia"+ind).prop("readonly",true);
		}
	}).autocomplete("widget").css("z-index", 1051);
	
	$("#nombre_diagnostico_anestesia"+ind).on('blur', function() {
		var id_diagnostico_anestesia = $("#id_diagnostico_anestesia"+ind).val();
		if(id_diagnostico_anestesia == ""){
			bootbox.alert("Falta ingresar el diagnostico en esta fila");
			$("#nombre_diagnostico_anestesia"+ind).val("");
			return false;
		}else{
      addNewArray(id_diagnostico_anestesia);
		}
	});
	
  }

  

  function addNewArray(id_diagnostico) {

    console.log(id_diagnostico);
    
    if(  listIdDiagnosticoObjetosAnestesia.findIndex( e  => e.id == id_diagnostico) ==-1 ){
      listIdDiagnosticoAnestesia.push(id_diagnostico);// solo es para validar si existen datos

      if (listIdDiagnosticoAnestesia.length > 0) {
        let codigo = JSON.stringify(id_diagnostico);
        console.log(codigo);
        
        // Realizar la petición AJAX usando el código id_diagnostico
        $.ajax({
            "dataType": 'json',
            "type": "POST",
            "url": "/obtenerdatosDiagnosticos",
            "data":{
              codigo : codigo,
              _token: "{{ csrf_token() }}"
                  },
            "success": function (result) {
                console.log(result);
                
                if (result) {
                  listIdDiagnosticoObjetosAnestesia.push(result.objeto);   
                  console.log(JSON.stringify(listIdDiagnosticoObjetosAnestesia));
                  updateSelectElements();
                } else {
                  console.log(`Retorna vacio ...!!`);
                }
            },
            "error": function (msg, textStatus, errorThrown) {
                //location.href="login";
                console.log(msg, textStatus, errorThrown);
                
            }
        });
      }
    }
  }

  function updateSelectElements() {
    // Obtener todos los elementos <select> con la clase "select-diagnostico"
    let selectElements = document.querySelectorAll(".select-diagnostico-anestesia");
    console.log(selectElements);
    
    selectElements.forEach((selectElement) => {
      // Almacenar el valor actualmente seleccionado
      let currentValue = selectElement.value;
      console.log(currentValue);
      
      // Eliminar las opciones actuales
      selectElement.innerHTML = "";


      // Agregar las opciones basadas en listIdDiagnosticoObjetos
      listIdDiagnosticoObjetosAnestesia.forEach((item) => {
        let option = document.createElement("option");
        option.value = item.id;
        option.textContent = item.nombre;
        selectElement.appendChild(option);
      });

      // Verificar si el valor seleccionado aún está disponible en las opciones
      let optionExists = Array.from(selectElement.options).some(
        (option) => option.value === currentValue
      );

      // Si el valor 1  e2  ado no existe en las opciones, cambiar a "Seleccione"
      if (!optionExists) {
        selectElement.value = "";
      } else {
        // Si el valor seleccionado aún está disponible en las opciones, restablecer el valor seleccionado
        selectElement.value = currentValue;
        console.log()
      }
    });
  }

  $('#tblProcedimientoAnestesia tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
		obj.parent().parent().remove();
	});

  $('#addProcedimientoAnestesia').on('click',function(){
		AddProcedimientoAnestesia();
	});

  function AddProcedimientoAnestesia(){
    var newRow = "";
    var ind = $('#tblProcedimientoAnestesia tbody tr').length;
    
    if(ind > 0){
      $('.id_procedimiento_anestesia').each(function(){
        var ind_tmp = $(this).data("id");
        if(ind_tmp => ind){
          ind=Number(ind_tmp)+1;
        }
      });
            
      fila_medicamentos=$('#tblProcedimientoAnestesia tbody').children('tr');
      rowIndex=$('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento=fila_medicamentos[idval];
      
    }
    
    newRow += '<tr>';
    newRow +=
      '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento_anestesia[]" tabindex="8" required="" id="nombre_procedimiento_anestesia' +
      ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento_anestesia" id="id_procedimiento_anestesia' + ind +
      '" name="id_procedimiento_anestesia[]" value="" data-id='+ind+'></td>';
    newRow +=
      '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad_anestesia typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad_anestesia' +
      ind + '" name="procedimiento_cantidad_anestesia[]" value="1"></td>';
    newRow += '<td><select class="form-control select-diagnostico-anestesia"  id="miSelectAnestesia' + ind + '" name="miSelectAnestesia[]"></select></td>';
    newRow +=
      '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblProcedimientoAnestesia tbody').append(newRow);
    const selectElement = document.getElementById('miSelectAnestesia' + ind);
    let defaultOption = document.createElement('option');
    //si entra la primera vez
    var i = 0;
    listIdDiagnosticoObjetosAnestesia.forEach((item) => {
      let option = document.createElement('option');
      option.value = item.id;
      option.textContent = item.nombre;
      if (i==0){
        option.setAttribute("selected", "selected");  
        i++;
      }
      selectElement.appendChild(option);
    });
    var nivel     = $('#frmCqAnestesia #id_nivel').val();
    var sexo	= $('#frmCqAnestesia #nomsexo').val();
    if(sexo=='M')
      genero=1;
    else
      genero=2;
    
    //tipo = 1
    
    $("#nombre_procedimiento_anestesia"+ind).autocomplete({
      minLength: 1,
      delay : 400,
      source: function(request, response) {
        $.ajax({
    //  	url: '../obtener_procedimiento/' + request.term,
          url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
          dataType: "json",
          success: function(data)  {
            let listaProcedimientoSelecionado = $(".id_procedimiento_anestesia").map(function(){return parseInt($(this).val());}).get();
            let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
            response(filtro);
          }					
        })
    
      },select:  function(e, ui) {
        var id_procedimiento = ui.item.id;
        $("#id_procedimiento_anestesia"+ind).val(id_procedimiento);
        $("#nombre_procedimiento_anestesia"+ind).prop("readonly",true);
      }
    }).autocomplete("widget").css("z-index", 1051);
    
    $("#nombre_procedimiento_anestesia"+ind).on('blur', function() {
      var id_procedimiento = $("#id_procedimiento_anestesia"+ind).val();
      if(id_procedimiento == ""){
        bootbox.alert("Falta ingresar el procedimiento en esta fila");
        $("#nombre_procedimiento_anestesia"+ind).val("");
        return false;
      }
    });
    $("#procedimiento_cantidad_anestesia"+ind).on('blur', function() {
      var id_procedimiento = $("#id_procedimiento_anestesia"+ind).val();
      if(id_procedimiento == ""){
        bootbox.alert("Falta ingresar la cantidad en este procedimiento");
        $("#procedimiento_cantidad_anestesia"+ind).val("");
        return false;
      }
    });
  } 

  $('#tblAnestesiologoAnestesia tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    // Selecciona la fila completa
    var fila = obj.closest('tr');
    // Obtiene la posición de la fila
    var filaPosition = fila.index();
    // Elimina la fila
    obj.parent().parent().remove();
  });

  $('#addAnestesiologoAnestesia').on('click',function(){
    AddAnestesiologoAnestesia();
  });

  function AddAnestesiologoAnestesia() {
    var newRow = "";
    var ind = $('#tblAnestesiologoAnestesia tbody tr').length;
    console.log(`total de filas de anestesiologos : ${ind}`);
    
    if (ind > 0) {
      $('.id_anestesiologo_anestesia').each(function() {
        var ind_tmp = $(this).data("id");
        if (ind_tmp => ind) {
          ind = Number(ind_tmp) + 1;
        }
      });
            
      fila_registros = $('#tblAnestesiologoAnestesia tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_registro = fila_registros[idval];
    }

    newRow += '<tr>';
    newRow +='<td style="width:60%;">'+
              '<input type="hidden" name="flag_personales_anestesiologo_anestesia[]" value="1" />'+
              '<input type="text" data-toggle="tooltip" data-placement="top"  name="nombre_anestesiologo_anestesia[]" '+
              'id="nombre_anestesiologo_anestesia' +ind + '"  class="form-control" style="height: 28px !important;font-weight: 800;" required/>'+
              '<input type="hidden" class="form-control typeahead tt-query id_anestesiologo_anestesia" id="id_anestesiologo_anestesia' + ind +'" name="id_anestesiologo_anestesia[]"  data-id='+ind+'></td>';
    newRow +='<td style="width:20%;"><select id="tipo_profesional_anestesiologo_anestesia' + ind +'" name="tipo_profesional_anestesiologo_anestesia[]" class="form-control" style="height: 28px !important;font-weight: 800;"><option value="">Seleccione un tipo</option></select></td>';
    newRow +='<td style="width:15%;"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblAnestesiologoAnestesia tbody').append(newRow);
    
    var tipo_profesional_interviene = <?php echo json_encode($tipo_profesional_interviene_anestesiologo); ?>;
    var $select = $('#tipo_profesional_anestesiologo_anestesia' + ind +'');
    $.each(tipo_profesional_interviene, function(index, value) {
      $select.append($('<option></option>').val(value.id).text(value.descripcion));
    });
  
    $("#nombre_anestesiologo_anestesia" + ind).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        url_consulta= '{{url('profesional_que_interviene/')}}/'+request.term;    
        $.ajax({
          url: url_consulta,
          dataType: "json",
          success: function(data) {
            let listaSirujanosSeleccionado = $(".id_cirujano").map(function(){
                return parseInt($(this).val());
            }).get();

            let listaAnestesiologosSelecionado = $(".id_anestesiologo_anestesia").map(function(){
                return parseInt($(this).val());
            }).get();

            // Filtro que excluye los elementos que estén en ambas listas
            let filtro = data.filter((element) => {
                return !listaSirujanosSeleccionado.includes(element.id) && 
                      !listaAnestesiologosSelecionado.includes(element.id);
            });

            // Aplicar el límite de los primeros 10 elementos
            let limitedData = filtro.slice(0, 10);
            response(limitedData);
          }
        });
      },
      select: function(e, ui) {
        var id_anestesiologo_anestesia = ui.item.id;
        $("#id_anestesiologo_anestesia" + ind).val(id_anestesiologo_anestesia);
        $("#nombre_anestesiologo_anestesia" + ind).prop("readonly", true);
      }
    });
    
  }

  let anestesiologosSeleccionados = [];

  // Función para actualizar los inputs hidden
  function actualizarInputsHidden() {
      // Primero eliminamos cualquier input anterior para evitar duplicados
      $('#hiddenInputsContainer').empty();

      // Creamos un input hidden por cada ID en el array
      anestesiologosSeleccionados.forEach(function(id) {
          $('#hiddenInputsContainer').append(
              $('<input>', {
                  type: 'hidden',
                  name: 'anestesiologosEliminados[]',
                  value: id
              })
          );
      });
  }

  $('.AnularFilaAnestesiologos').on('click', function() {
    let id_anestesiologo_registro = $(this).data('id_anestesiologo_registro')

    if (!anestesiologosSeleccionados.includes(id_anestesiologo_registro)) {
        anestesiologosSeleccionados.push(id_anestesiologo_registro);
        $(this).closest('tr').css('background-color', '#f8d7da');
    }

    actualizarInputsHidden();
    
    console.log(anestesiologosSeleccionados);
    
  });

  $('#restaurarColores').on('click', function() {
      // Restaurar el color de fondo a su estado original (normalmente blanco)
      anestesiologosSeleccionados = [];
      $('#tblAnestesiologoAnestesia tr').css('background-color', '');
      $('#hiddenInputsContainer').empty();
  });

  $('.AnularFilaProductosAnestesia').on('click', function() {
    let row = $(this).closest("tr");

    row.find("input, select").prop("disabled", true);

    row.find("input[name='accionRowProductoAnestesia[]']").val("delete");

    $(this).closest('tr').css('background-color', '#f8d7da !important');
    
  });

  $('#restaurarColoresMedicamentos').on('click', function() {
      $("input[name='accionRowProductoAnestesia[]']").val("");

      // Deshabilita todos los inputs de tipo 'text' en la tabla
      $("#tblMedicamentosAnestesia").find("input[type='text']").prop("disabled", true);

      // Deshabilita todos los selects en la tabla
      $("#tblMedicamentosAnestesia").find("select").prop("disabled", true);
      
      $('#tblMedicamentosAnestesia tr').css('background-color', '');
  });

  $('#tblMedicamentosAdicionalAnestesia tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    // Selecciona la fila completa
    var fila = obj.closest('tr');
    // Obtiene la posición de la fila
    var filaPosition = fila.index();
    // Elimina la fila
    obj.parent().parent().remove();
  });

  let filasmedicamentosAdicionalAnestesia = 0;

  $('#addProductoAdicionalAnestesia').on('click', function () {
    //getProductoAdicionalAnestesia();
    AddProductoAdicionalAnestesia();
  });

  function AddProductoAdicionalAnestesia(){
      console.log('Entro en función AddProductoAdicionalAnestesia() ... ');
      
      var tipo_receta = 1;
      var id_consultorio = 72;
      if(tipo_receta == ""){
          bootbox.alert("Seleccione el tipo atención");
          return false;
      }		
      if(id_consultorio == ""){
          bootbox.alert("Seleccione un servicio");
          return false;
      }
      var newRow = "";
      var ind = $('#tblMedicamentosAdicionalAnestesia tbody tr').length;
      console.log('Antes de agregar fila, número de filas:', $('#tblMedicamentosAdicionalAnestesia tbody tr').length);
      var ind = filasmedicamentosAdicionalAnestesia
      var tabindex = 71;
      var nuevalperiodo = "";
      if(ind > 0){
          $('.cmbMedicamentoAdicionalAnestesia').each(function(){
              var ind_tmp = $(this).attr("ind");
              if(ind_tmp >= ind){
                ind=Number(ind_tmp)+1;
                tabindex+=3;
              }
          });
              
          fila_medicamentos=$('#tblMedicamentosAdicionalAnestesia tbody').children('tr');
          rowIndex=$('#hidden_adicional_rowIndex').val();
          var idval = ind - 1;
          <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>    
              fila_medicamento=fila_medicamentos[idval];
              var valante = $(fila_medicamento).find('#nro_med_adicional_entregados').val();
              if(valante == ""){
                  bootbox.alert("Ingrese cantidad entregada en el producto anterior");
                  return false;
              }
          <?php //endif;?>
              
          //nuevalperiodo = document.getElementById('tiempo_periodo'+idval).value;
      }
      
      var item_producto = "";
      $('#cmbMedicamentoAdicionalTempAnestesia option').each(function() {
          item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
          "</option>"
      });
      console.log(`El  item de medicamentoTemp : ${item_producto}`);

      var item_diagnostico_option = "";
      $('#diagnosticosAdicionalTempAnestesia option').each(function() {
          item_diagnostico_option += "<option value=" + $(this).val() + ">" + $(this).html() +
          "</option>"
      });
       
      
      var item_via_administracion = '';
      item_via_administracion+='<option value="0">--Seleccionar--</option>';
      item_via_administracion+='<option value="1">Oral</option>';
      item_via_administracion+='<option value="2">Sublingual</option>';
      item_via_administracion+='<option value="3">Rectal</option>';
      item_via_administracion+='<option value="4">Intravenosa</option>';
      item_via_administracion+='<option value="5">Intramuscular</option>';
      item_via_administracion+='<option value="6">Subcutánea</option>';
      item_via_administracion+='<option value="7">Dérmica</option>';
      item_via_administracion+='<option value="8">Nasal</option>';
      item_via_administracion+='<option value="9">Oftalmológica</option>';
      item_via_administracion+='<option value="10">Inhalatoria</option>';
      item_via_administracion+='<option value="11">Epidural</option>';
      item_via_administracion+='<option value="12">Intratecal</option>';
      item_via_administracion+='<option value="13">Vaginal</option>';
      item_via_administracion+='<option value="14">Intraarticular</option>';
      item_via_administracion+='<option value="15">No Aplica</option>';
      item_via_administracion+='<option value="16">Otros</option>';
      
      newRow +='<tr>';
      newRow +='<td><select class="form-control cmbMedicamentoAdicionalAnestesia" id="cmbMedicamentoAdicionalAnestesia' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto_adicional[]" style="width: 400px">' + item_producto + '</select></td>';
      
      //newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
      //newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
      newRow +='<input type="hidden" name="nro_stocks_almacen_adicional[]" id="nro_stocks_almacen_adicional" value=""/>';
      newRow +='<input type="hidden" name="nro_stocks_establecimiento_adicional[]" id="nro_stocks_establecimiento_adicional" value=""/>';
      newRow +='<td><input type="text" name="nro_stocks_adicional[]" required id="nro_stocks_adicional" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
      <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
      newRow +='<td><input onkeypress="soloNumerosMenosCero(event)" type="text" name="tiempo_periodo_adicional[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo_adicional' + ind +'" class="nro_solicitado tiempo_periodo_adicional input-sm" style="margin-left:4px; width:100px" value="' + nuevalperiodo +'"/>';
      newRow +='<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion_anestesia_adicional" id="cmbTipoViaAdministracionAdicional'+ind+'" name="id_via_administracion_anestesia_adicional[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';
      newRow += '<td><input type="text" name="posologiaAdicional[]" tabindex="' + (tabindex + 2) + '" required="" id="posologiaAdicional' +
      ind + '" class="nro_solicitado posologiaAdicional input-sm" style="margin-left:4px; width:200px" value=""/>';
      
      <?php //endif;?>
      newRow +='<input type="hidden" name="lotes_lote_adicional[]" id="lotes_lote_adicional" value=""/>';
      newRow +='<input type="hidden" name="lotes_registro_sanitario_adicional[]" id="lotes_registro_sanitario_adicional" value=""/>';
      newRow +='<input type="hidden" name="lotes_fecha_vencimiento_adicional[]" id="lotes_fecha_vencimiento_adicional" value=""/>';
      newRow +='<input type="hidden" name="lotes_precio_unitario_adicional[]" id="lotes_precio_unitario_adicional" value=""/>';
      newRow +='<input type="hidden" name="lotes_cantidad_adicional[]" id="lotes_cantidad_adicional" value=""/></td>';
      newRow +='<td><input onKeyPress="soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados_anestesia_adicional[]" required="" id="nro_med_solictados_anestesia_adicional' +ind +'" class="limpia_text nro_solicitado nro_med_solictados_anestesia_adicional input-sm" style="margin-left:4px; width:100px" /></td>';
      
      <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
      //newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
      <?php //endif;?>
      
      newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control"  id="miSelectProdAdicionalAnestesia' + ind + '" name="miSelectProdAdicionalAnestesia[]">'+ item_diagnostico_option +'</select></td>';  
      newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
      newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';

      newRow +='</tr>';
      $('#tblMedicamentosAdicionalAnestesia tbody').append(newRow);
      $("#cmbMedicamentoAdicionalAnestesia" + ind).select2({max_selected_options: 4});
      
      const selectElementProd = document.getElementById('miSelectProdAdicionalAnestesia' + ind);

      listIdDiagnosticoObjetosAnestesia.forEach((item) => {
          let option = document.createElement('option');
          option.value = item.id;
          option.textContent = item.nombre;
          selectElementProd.appendChild(option);
      });
      $("#abrir-modal-" + ind).click(function(e) {
          e.stopPropagation();
      
          abrirModal(ind);
      });
      
      $("#nro_med_solictados_anestesia_adicional"+ind).keypress(function(e){
          if(e.which == 13) {
              <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
                  AddProductoAdicionalAnestesia();
              <?php //else:?>
                  validproductoAdicionalseleccionado({{Auth::user()->id_farmacia}},this);
              <?php //endif;?>
          }
      });

      $("#add_producto_anestesia"+ind).click(function(e){
          AddProductoAdicionalAnestesia();
          validproductoAdicionalseleccionado({{Auth::user()->id_farmacia}},this);
          btnAgregarProducto = $(this);
          $(btnAgregarProducto).attr('disabled', 'disabled');
      });

      function validproductoAdicionalseleccionado(id_farmacia, btn) {//Jose
        console.log('Entro en función de validproductoAdicionalseleccionado');
        
          var dni_beneficiario = $('#dni_beneficiario').val();
          var nro_dias = 30;
          var objTr = $(btn).closest("tr");
          var id_producto = objTr.find(".cmbMedicamentoAdicionalAnestesia").val();
          $.ajax({
              url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
              success: function (result) {
              var msg   = "";
              var n     = 0;
              var flag  = true;
                  for(var key in result) {
                      if(n == 0){
                          msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";              
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
                      msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
                      n++;
                  }
                  if(msg != "") {
                      bootbox.confirm({
                          title: "Alerta",
                          message: msg,
                          buttons: {
                              cancel: {
                                  label: '<i class="fa fa-times"></i> Cancelar'
                              },
                              confirm: {
                                  label: '<i class="fa fa-check"></i> Continuar'
                              }
                          },
                          callback: function (result) {
                              if(result==true){
                              verlotes(id_farmacia, btn);
                              }
                          }
                      });
                  }
              }
          });
      }
      
      
      $("#cmbMedicamentoAdicionalAnestesia"+ind).on("change", function (e) {
          var flagx = 0;
          cmb = $(this);
          id_producto = $("#cmbMedicamentoAdicionalAnestesia"+ind).val();
          id_user={{Auth::user()->id}};
      
          $('.cmbMedicamentoAdicionalAnestesia').each(function(){
              var ind_tmp = $(this).val();
              if($(this).val() == id_producto)flagx++;
          });
      
          if(flagx > 1){
              bootbox.alert("El producto farmaceutico ya ha sido ingresado");
              $("#cmbMedicamentoAdicionalAnestesia"+ind).val("").trigger("change");
              return false;
          }
      
          option = {
              url: '../cargarstock_farmacia/' + id_producto+ '/' + id_user,
              type: 'GET',
              dataType: 'json',
              data: {}
          };
          $.ajax(option).done(function (data) {
              
              var cantidad = data.cantidad;
              var cantidadEstablecimiento = data.cantidadEstablecimiento;
              var cantidadAlmacen = data.cantidadAlmacen;
              $(cmb).closest("tr").find(".limpia_text").val("");                
              //$(cmb).closest("tr").find("#nro_stocks").val(data);
              $(cmb).closest("tr").find("#nro_stocks_adicional").val(cantidad);
              $(cmb).closest("tr").find("#nro_stocks_establecimiento_adicional").val(cantidadEstablecimiento);
              $(cmb).closest("tr").find("#nro_stocks_almacen_adicional").val(cantidadAlmacen);
              <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
              $(cmb).closest("tr").find(".tiempo_periodo_adicional").focus();
              <?php //endif;?>
              $(cmb).closest("tr").find("#nro_med_solictados_anestesia_adicional").val("");  
              $(cmb).closest("tr").find("#nro_med_entregados_adicional").val("");
              $(cmb).closest("tr").find("#lotes_lote_adicional").val("");
              $(cmb).closest("tr").find("#lotes_cantidad_adicional").val("");
              $(cmb).closest("tr").find("#lotes_registro_sanitario_adicional").val("");
              $(cmb).closest("tr").find("#lotes_fecha_vencimiento_adicional").val("");
          });
      
      });
      filasmedicamentosAdicionalAnestesia++;
  }

  function getProductoAdicionalAnestesia(){
	
    //$('#tblMedicamentosAdicionalAnestesia tbody').html('');
    
    if($('#frmCqAnestesia #id_servicio').val()=="") return false;
    
    //var id_ipress 		= $('#id_ipress').val();
    var id_ipress 		= 76//$('#frmCqAnestesia #id_establecimiento').val();
    var id_consultorio 	= 72//$('#frmCqAnestesia #id_servicio').val();
    //var tipo_receta		= $('#tipo_receta').val();
    //var id_tiporeceta 	= $('#id_tiporeceta').val();
    var id_tiporeceta 	= 3//$('#frmCqAnestesia #id_tipo_atencion').val();
    
    var id_farmacia     = 18 //$('#frmCqAnestesia #id_farmacia_receta').val();
    console.log(id_farmacia);
    
    var nivel     = $('#id_nivel').val();
    var x  = 0;
    var dni = $('#dni_beneficiario').val();
    var interconsulta = $('#interconsulta').val();
    if(id_tiporeceta == "3"){ 
        
        if(interconsulta=='SI'){
            if (id_consultorio==104){
                id_consultorio2=104
            } else {
                id_consultorio2=105
            }
            $.ajax({
            //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + id_consultorio2+ '/' + dni,
            url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +id_consultorio2,
            success: function(result){                    
                var newOption = "";
                newOption = "<option value=''>--Seleccionar--</option>";
                $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                });                 
                $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
            }
            }); 
        } else {  
        
            if(x==0){ //SIN INTERCONSULTA
                if (id_consultorio==104){
                    $.ajax({
                        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 104,
                        //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
                        success: function(result){                    
                        var newOption = "";
                        newOption = "<option value=''>--Seleccionar--</option>";
                        $(result).each(function (ii, oo) {
                            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                        });                 
                        $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                        }
                    });
                } else {
                    if (id_consultorio==21){
                        $.ajax({
                        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 21,
                        //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
                        success: function(result){                    
                            var newOption = "";
                            newOption = "<option value=''>--Seleccionar-</option>";
                            $(result).each(function (ii, oo) {
                            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                            });                 
                            $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                            //$("#id_medico").select2({max_selected_options: 4});
                        
                        }
                        });
                    } else {
                        if (id_consultorio==72){
                            console.log('Entro en consultorio de anestesiologia ...');
                            $.ajax({
                            //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
                            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 72,
                            success: function(result){   
                                console.log(result);
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                });                 
                                $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                                console.log($('#cmbMedicamentoAdicionalTempAnestesia').val());
                                
                            }
                            });
                        } else {
                            $.ajax({
                            url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
                            //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
                            success: function(result){                    
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                });                 
                                $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                            }
                            });  
                        }
                    
                    }
                }
            } else {
                if (id_consultorio==104){
                    $.ajax({
                        url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
                        success: function(result){                    
                            var newOption = "";
                            newOption = "<option value=''>--Seleccionar--</option>";
                            $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                            });                 
                            $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                        }
                    });
                } else {
                    if (id_consultorio==21){
                        $.ajax({
                        url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 21 + '/' + dni,
                        success: function(result){                    
                            var newOption = "";
                            newOption = "<option value=''>--Seleccionar-</option>";
                            $(result).each(function (ii, oo) {
                            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                            });                 
                            $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                        }
                        });
                    } else {
                        if (id_consultorio==72){
                            $.ajax({
                            //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
                            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 72,
                            success: function(result){                    
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                });                 
                                $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                            }
                            });
                        } else {
                            $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 105+ '/' + dni,
                            success: function(result){                    
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                });                 
                                $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                            }
                            });  
                        }
                    }
                }  
            }         
        }       
    }else{
        console.log('entro en condición tipo de atención 1 ...');
        
        if(id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia!=18 && id_farmacia!=25){
            console.log('entro en condición tipo de atención 1 ...');
            if(id_tiporeceta==2){
                id_consultorio=76;
            }
            $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
                success: function(result){                    
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    $(result).each(function (ii, oo) {
                        newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                    });                 
                    $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                    //$("#id_medico").select2({max_selected_options: 4});
                
                }
            });
        
        }
        if((id_tiporeceta == 1 || id_tiporeceta == 5 || id_tiporeceta == 6) && id_farmacia == 21){ //nutricional
            if(id_tiporeceta==5 || id_tiporeceta == 1){
                id_consultorio = 110;
            } else {
                id_consultorio = 111;
            }
                
            $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
                success: function(result){                    
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    $(result).each(function (ii, oo) {
                        newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                    });                 
                    $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                    //$("#id_medico").select2({max_selected_options: 4});
                
                }
            });
        }
        if(id_farmacia==18 || id_farmacia==25 || id_tiporeceta == 4){ //centro quirurgico
            if(id_tiporeceta == 1){
                if(id_farmacia == 25){
                    $.ajax({
                        url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
                        success: function(result){                    
                        var newOption = "";
                        newOption = "<option value=''>--Seleccionar-</option>";
                        $(result).each(function (ii, oo) {
                            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                        });                 
                        $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                        }
                    });
                } else {
                    if(id_consultorio == 72){
                        $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                            '/' + 72,
                            success: function(result) {
                            var newOption = "";
                            newOption = "<option value=''>--Seleccionar-</option>";
                            $(result).each(function(ii, oo) {
                                newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                                .nombre + ", " + oo.abreviatura + "</option>";
                            });
                            $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                            }
                        });
                    } else {
                        $.ajax({
                        url: '/obtenerProductosFarmaciasByNivel/'+id_farmacia+'/' + id_tiporeceta+'/'+id_consultorio,
                        success: function(result){                    
                            var newOption = "";
                            newOption = "<option value=''>--Seleccionar-</option>";
                            $(result).each(function (ii, oo) {
                                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                                });                 
                                $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                            }
                        }); 
                    }  
                }
                //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
            } else {
                if(id_tiporeceta == 4){
                    if(id_consultorio == 72){
                        $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                            '/' + 72,
                            success: function(result) {
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function(ii, oo) {
                                    newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                                    .nombre + ", " + oo.abreviatura + "</option>";
                                });
                                $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                            }
                        });
                    } else {
                        $.ajax({
                        url: '/obtener_producto_por_idipress_idconsultorio_quirurgico/' + id_ipress+ '/' + id_consultorio,
                        success: function(result){                    
                            var newOption = "";
                            newOption = "<option value=''>--Seleccionar-</option>";
                            $(result).each(function (ii, oo) {
                            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                            });                 
                            $('#cmbMedicamentoAdicionalTempAnestesia').html(newOption);
                            //$("#id_medico").select2({max_selected_options: 4});
                        
                        }
                        });  
                    }
                }
            }
        }
    }
  }

  $('#frmCqAnestesia').on('submit', function() {
    // Habilitar inputs y selectores temporalmente
    $(this).find('input[disabled], select[disabled]').prop('disabled', false);
  });

</script>
<style>

    #modalNuevoAnestesia .modal-dialog {
        position: fixed; /* Fija el modal en relación a la ventana */
        top: 70%; /* Mueve hacia abajo el modal */
        left: 64%; /* Mueve hacia la derecha el modal */
        transform: translate(-70%, -70%); /* Mantiene el centro de la caja en la nueva posición */
    }

    #modalNuevoBalanceHidrico .modal-dialog {
        position: fixed; /* Fija el modal en relación a la ventana */
        top: 70%; /* Mueve hacia abajo el modal */
        left: 64%; /* Mueve hacia la derecha el modal */
        transform: translate(-70%, -70%); /* Mantiene el centro de la caja en la nueva posición */
    }

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

  .form-control{
      height: 28px;
    text-align:left!important;
  }

  .form-group{
      margin-bottom: 10px;
  }

    .contenedor-datos-generales input,
    .contenedor-especialista input,
    .contenedor-paciente input,
    .contenedor-informacion-atencion input,
    .contenedor-referencia input{
        text-align: center !important;
    }

    input[type=checkbox]
    {
    /* Doble-tamaño Checkboxes */
    -ms-transform: scale(2); /* IE */
    -moz-transform: scale(2); /* FF */
    -webkit-transform: scale(2); /* Safari y Chrome */
    -o-transform: scale(2); /* Opera */
    padding: 10px;
    }

    .checkboxtexto
    {
    /* Checkbox texto */
    font-size: 130%;
    display: inline;
    }

    .btn-toggle-heading {
        margin: 0 4rem;
        padding: 0;
        position: relative;
        border: none;
        height: 1.5rem;
        width: 3rem;
        border-radius: 1.5rem;
        color: #6b7381;
        background: #bdc1c8;
    }
    .btn-toggle-heading:focus,
    .btn-toggle-heading.focus,
    .btn-toggle-heading:focus.active,
    .btn-toggle-heading.focus.active {
        outline: none;
    }
    .btn-toggle-heading:before,
    .btn-toggle-heading:after {
        line-height: 1.5rem;
        width: 4rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        bottom: 0;
        transition: opacity 0.25s;
    }
    .btn-toggle-heading:before {
        content: 'NO';
        left: -4rem;
    }
    .btn-toggle-heading:after {
        content: 'SI';
        right: -4rem;
        opacity: 0.5;
    }
    .btn-toggle-heading>.handle-heading {
        position: absolute;
        top: 0.3125rem;
        left: 0.1875rem;
        width: 1.125rem;
        height: 1.125rem;
        border-radius: 1.125rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle-heading.active {
        transition: background-color 0.25s;
    }
    .btn-toggle-heading.active>.handle-heading {
        left: 1.6875rem;
        transition: left 0.25s;
    }
    .btn-toggle-heading.active:before {
        opacity: 0.5;
    }
    .btn-toggle-heading.active:after {
        opacity: 1;
    }
    .btn-toggle-heading.btn-sm:before,
    .btn-toggle-heading.btn-sm:after {
        line-height: -0.5rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.4125rem;
        width: 2.325rem;
    }
    .btn-toggle-heading.btn-sm:before {
        text-align: right;
    }
    .btn-toggle-heading.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle-heading.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle-heading.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle-heading.btn-xs:before,
    .btn-toggle-heading.btn-xs:after {
        display: none;
    }
    .btn-toggle-heading:before,
    .btn-toggle-heading:after {
        color: #6b7381;
    }
    .btn-toggle-heading.active {
        background-color: #29b5a8;
    }
    .btn-toggle-heading.btn-lg {
        margin: 0 5rem;
        padding: 0;
        position: relative;
        border: none;
        height: 2.5rem;
        width: 5rem;
        border-radius: 2.5rem;
    }
    .btn-toggle-heading.btn-lg:focus,
    .btn-toggle-heading.btn-lg.focus,
    .btn-toggle-heading.btn-lg:focus.active,
    .btn-toggle-heading.btn-lg.focus.active {
        outline: none;
    }
    .btn-toggle-heading.btn-lg:before,
    .btn-toggle-heading.btn-lg:after {
        line-height: 2.5rem;
        width: 5rem;
        text-align: center;
        font-weight: 600;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        bottom: 0;
        transition: opacity 0.25s;
    }
    .btn-toggle-heading.btn-lg:before {
        content: 'NO';
        left: -5rem;
    }
    .btn-toggle-heading.btn-lg:after {
        content: 'SI';
        right: -5rem;
        opacity: 0.5;
    }
    .btn-toggle-heading.btn-lg>.handle-heading {
        position: absolute;
        top: 0.3125rem;
        left: 0.3125rem;
        width: 1.875rem;
        height: 1.875rem;
        border-radius: 1.875rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle-heading.btn-lg.active {
        transition: background-color 0.25s;
    }
    .btn-toggle-heading.btn-lg.active>.handle-heading {
        left: 2.8125rem;
        transition: left 0.25s;
    }
    .btn-toggle-heading.btn-lg.active:before {
        opacity: 0.5;
    }
    .btn-toggle-heading.btn-lg.active:after {
        opacity: 1;
    }
    .btn-toggle-heading.btn-lg.btn-sm:before,
    .btn-toggle-heading.btn-lg.btn-sm:after {
        line-height: 0.5rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.6875rem;
        width: 3.875rem;
    }
    .btn-toggle-heading.btn-lg.btn-sm:before {
        text-align: right;
    }
    .btn-toggle-heading.btn-lg.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle-heading.btn-lg.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle-heading.btn-lg.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle-heading.btn-lg.btn-xs:before,
    .btn-toggle-heading.btn-lg.btn-xs:after {
        display: none;
    }
    .btn-toggle-heading.btn-sm {
        margin: 0 0.5rem;
        padding: 0;
        position: relative;
        border: none;
        height: 1.5rem;
        width: 3rem;
        border-radius: 1.5rem;
    }
    .btn-toggle-heading.btn-sm:focus,
    .btn-toggle-heading.btn-sm.focus,
    .btn-toggle-heading.btn-sm:focus.active,
    .btn-toggle-heading.btn-sm.focus.active {
        outline: none;
    }
    .btn-toggle-heading.btn-sm:before,
    .btn-toggle-heading.btn-sm:after {
        line-height: 1.5rem;
        width: 0.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.55rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        bottom: 0;
        transition: opacity 0.25s;
    }
    .btn-toggle-heading.btn-sm:before {
        content: 'NO';
        left: -0.5rem;
    }
    .btn-toggle-heading.btn-sm:after {
        content: 'SI';
        right: -0.5rem;
        opacity: 0.5;
    }
    .btn-toggle-heading.btn-sm>.handle-heading {
        position: absolute;
        top: 0.3125rem;
        left: 0.1875rem;
        width: 1.125rem;
        height: 1.125rem;
        border-radius: 1.125rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle-heading.btn-sm.active {
        transition: background-color 0.25s;
    }
    .btn-toggle-heading.btn-sm.active>.handle-heading {
        left: 1.6875rem;
        transition: left 0.25s;
    }
    .btn-toggle-heading.btn-sm.active:before {
        opacity: 0.5;
    }
    .btn-toggle-heading.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle-heading.btn-sm.btn-sm:before,
    .btn-toggle-heading.btn-sm.btn-sm:after {
        line-height: -0.5rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.4125rem;
        width: 2.325rem;
    }
    .btn-toggle-heading.btn-sm.btn-sm:before {
        text-align: right;
    }
    .btn-toggle-heading.btn-sm.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle-heading.btn-sm.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle-heading.btn-sm.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle-heading.btn-sm.btn-xs:before,
    .btn-toggle-heading.btn-sm.btn-xs:after {
        display: none;
    }
    .btn-toggle-heading.btn-xs {
        margin: 0 0;
        padding: 0;
        position: relative;
        border: none;
        height: 1rem;
        width: 2rem;
        border-radius: 1rem;
    }
    .btn-toggle-heading.btn-xs:focus,
    .btn-toggle-heading.btn-xs.focus,
    .btn-toggle-heading.btn-xs:focus.active,
    .btn-toggle-heading.btn-xs.focus.active {
        outline: none;
    }
    .btn-toggle-heading.btn-xs:before,
    .btn-toggle-heading.btn-xs:after {
        line-height: 1rem;
        width: 0;
        text-align: center;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        bottom: 0;
        transition: opacity 0.25s;
    }
    .btn-toggle-heading.btn-xs:before {
        content: 'NO';
        left: 0;
    }
    .btn-toggle-heading.btn-xs:after {
        content: 'SI';
        right: 0;
        opacity: 0.5;
    }
    .btn-toggle-heading.btn-xs>.handle-heading {
        position: absolute;
        top: 0.3125remrem;
        left: 0.125rem;
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 0.75rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle-heading.btn-xs.active {
        transition: background-color 0.25s;
    }
    .btn-toggle-heading.btn-xs.active>.handle-heading {
        left: 1.125rem;
        transition: left 0.25s;
    }
    .btn-toggle-heading.btn-xs.active:before {
        opacity: 0.5;
    }
    .btn-toggle-heading.btn-xs.active:after {
        opacity: 1;
    }
    .btn-toggle-heading.btn-xs.btn-sm:before,
    .btn-toggle-heading.btn-xs.btn-sm:after {
        line-height: -1rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.275rem;
        width: 1.55rem;
    }
    .btn-toggle-heading.btn-xs.btn-sm:before {
        text-align: right;
    }
    .btn-toggle-heading.btn-xs.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle-heading.btn-xs.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle-heading.btn-xs.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle-heading.btn-xs.btn-xs:before,
    .btn-toggle-heading.btn-xs.btn-xs:after {
        display: none;
    }
    .btn-toggle-heading.btn-secondary {
        color: #6b7381;
        background: #bdc1c8;
    }
    .btn-toggle-heading.btn-secondary:before,
    .btn-toggle-heading.btn-secondary:after {
        color: #6b7381;
    }
    .btn-toggle-heading.btn-secondary.active {
        background-color: #ff8300;
    }
    .size-titulo {
        font-size: 1.2rem;
        font-weight: 700;
        text-align: center;
    }
    .panel-body .analisis-clinico{
        overflow-x: scroll;
    }

    .swal2-header-popstate {
        margin-top: 1rem;
    }
</style>
<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>

<div class="panel panel-default filterable">            
    <div class="panel-heading">
        <ul class="nav nav-tabs">
            <li class="active col-lg-6"><a href="#tabNuevoAnestesia" data-toggle="tab" style="font-size:12px;  text-align: center;">NUEVO ATENCIÓN</a></li>
            <li class="col-lg-6"><a href="#tabInformeAnestesia" data-toggle="tab" style="font-size:12px; text-align: center;">INFORME DE ANESTESIA</a></li>
        </ul>      
    </div>
    <div style=" font-size: 11px" class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tabNuevoAnestesia">
                <div class="panel panel-default filterable">            
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tabRegistroImagenes" data-toggle="tab" style="font-size:12px">NUEVO ATENCIÓN</a></li>
                            <li><a href="#tabHistorialImagenes" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE IMAGENOLOGIA</a></li>
                        </ul>      
                    </div>
                    <div style=" font-size: 11px" class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tabRegistroImagenes">
                                <form name="frmCqAnestesia" id="frmCqAnestesia" class="form-horizontal" role="form" method="post"  action="{{ route('cqanestesia.storeOrUpdate') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" value="{{ $id_prestacion }}" name="id_prestacion" id="id_prestacion" />
                                    <input type="hidden" value="{{ $prestacion->id_establecimiento }}" name="id_establecimiento" id="id_establecimiento" />
                                    <input type="hidden" value="{{ $prestacion->id_consultorio }}" name="id_servicio" id="id_servicio" />
                                    <input type="hidden" value="1" name="id_tipo_atencion" id="id_tipo_atencion" />
                                    <input type="hidden" value="{{ $nivel }}" name="id_nivel" id="id_nivel" />

                                    <input type="hidden" name="id_asegurado" id="id_asegurado" value="{{ $prestacion->id_asegurado }}" />
                                    <input type="hidden" name="id_cita" id="id_cita" value="0" />
                                    <input type="hidden" name="id_medico" id="id_medico" value="{{ $prestacion->id_medico }}" />
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user" />
                                    <input type="hidden" value="{{ $prestacion->grado }}" type="hidden" name="grado" id="grado" />
                                    <input type="hidden" value="{{ $prestacion->fecha_nac }}" name="fecnacafiliado" id="fecnacafiliado" />
                                    <input type="hidden" value="{{ $prestacion->cip_asegurado }}" name="cip_beneficiario" id="cip_beneficiario"/>
                                    <input type="hidden" value="{{ $prestacion->tipodoc_titular }}" name="tipodoc_titular" id="tipodoc_titular" />
                                    <input type="hidden" value="{{ $prestacion->nrodoctitular }}" name="nrodoctitular" id="nrodoctitular" />
                                    <input type="hidden" value="{{ $prestacion->nombre_titular }}" name="nombre_titular" id="nombre_titular" />
                                    <input type="hidden" value="{{ $prestacion->paterno_titular }}" name="paterno_titular" id="paterno_titular" />
                                    <input type="hidden" value="{{ $prestacion->materno_titular }}" name="materno_titular" id="materno_titular" />
                                    <input type="hidden" value="{{ $prestacion->domicilio }}" name="domicilio" id="domicilio" />
                                    <input type="hidden" value="{{ $prestacion->telefono }} {{ $prestacion->telefono2 }}" name="telefono" id="telefono" />
                                    <input type="hidden" value="1" name="id_modalidad" id="id_modalidad" />
                                    <input type="hidden" name="id_sub_consultorio" id="id_sub_consultorio" value="6">
                                    <input type="hidden" id="ipress_bene" name="ipress_bene" value="NUEVO">
                                    <input type="hidden" id="upss_bene" name="upss_bene" value="NUEVO">
                                    <input type="hidden" value="{{ isset($id_prestacion_anestesia) }}" name="id_prestacion_anestesia" id="id_prestacion_anestesia" />
                                    <input type="hidden" value="{{ isset($id_anestesia_quirurgica) }}" name="id_anestesia_quirurgica" id="id_anestesia_quirurgica" />
                                    <input type="hidden" value="{{ $flag_anestesia_quirurgica }}" name="flag_anestesia_quirurgica" id="flag_anestesia_quirurgica" />
                                    <input type="hidden" value="{{ $flag_observacion }}" name="flag_observacion" id="flag_observacion" />
                                    
                                    <div class="panel panel-info contenedor-datos-generales">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">
                                                DATOS GENERALES (DATOS DEL PRE ANESTESIA)
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <label>TIPO ATENCION</label>
                                                    <?php
                                                    switch (3) {
                                                    case '1':$tipo_atencion = "AMBULATORIO"; break;
                                                    case '2':$tipo_atencion = "EMERGENCIA"; break;
                                                    case '3':$tipo_atencion = "HOSPITALIZACION"; break;
                                                    case '4':$tipo_atencion = "CENTRO QUIRURGICO"; break;
                                                    case '5':$tipo_atencion = "SERVICIO NUTRICIONAL"; break;
                                                    }
                                                ?>
                                                    <input type="text" readonly="" value="{{ $tipo_atencion }}" name="tipo_atencion" id="tipo_atencion" class="form-control" />
                                                </div>
                                                @if ($id_tipo_atencion == 1)    
                                                    <div class="col-lg-3">
                                                        <label class="control-label">CONSULTORIO DE PROCEDENCIA</label>
                                                        <input type="text" class="form-control" readonly=""  value="{{ $prestacion->servicio }}" name="servicio_procedencia" id="servicio_procedencia">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>FECHA DE ORDEN</label>
                                                        <input type="text" class="form-control" readonly="" value="{{ $prestacion->fecha_atencion }}"  name="fecha_orden" id="fecha_orden" style="text-transform:uppercase;">
                                                    </div>
                                                @endif
                                                @if ($id_tipo_atencion == 2)
                                                    <div class="col-md-3">
                                                        <label>PRIORIDAD</label>
                                                        <input class="form-control" readonly="" value="<?php echo $prestacion->prioridades?>"  name="medico" id="medico" class="form-control" style="text-transform:uppercase;">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>SERVICIO</label>
                                                        <input class="form-control" readonly="" value="<?php echo $prestacion->servicio?>"  name="medico" id="medico" style="text-transform:uppercase;">
                                                    </div>
                                                @endif
                                                <div class="col-lg-3">
                                                    <label for="tipo" style="color: red;"><b>COD.SITEDS SUSALUD</b></label>
                                                    <input type="text" class="form-control"  id="cod_siteds_susalud" name="cod_siteds_susalud"  onkeypress="return esEntero(event);" style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important"   maxlength="12"  pattern="[0-9]*" value="" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if ($id_tipo_atencion == 2)
                                                    <div class="col-md-3">
                                                        <label>CAMA</label>
                                                        <input class="form-control" readonly=""  value="<?php echo $prestacion->nmro_cama?>"  name="medico" id="medico">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>FECHA DE ORDEN</label>
                                                        <input type="text" class="form-control" readonly="" value="{{ $prestacion->fecha_atencion }}"  name="fecha_orden" id="fecha_orden" style="text-transform:uppercase;">
                                                    </div>
                                                @endif
                                                <div class="col-lg-6">
                                                    <label>PROCEDENCIA</label>
                                                    <input type="text" class="form-control" readonly=""  value="{{ $prestacion->establecimiento }}" name="procedencia" id="procedencia">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info contenedor-especialista">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">ESPECIALISTA</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-lg-3">
                                                <label for="nro_dni_paciente">NRO DOC</label>
                                                <input class="form-control" readonly="" value="<?php echo $prestacion->dni?>"  onKeyPress="return soloNumeros(event)" type="text" name="nro_dni_medico" id="nro_dni_medico" style="text-transform:uppercase;">
                                            </div>
                                            <div class="col-lg-5">
                                                <label>NOMBRE Y APELLIDOS</label>
                                                <input type="text" readonly="" value="<?php echo $prestacion->medico_paterno." ".$prestacion->medico_materno.", ".$prestacion->medico_nombre ?>" name="medico" id="medico" class="form-control">
                                            </div>
                                            <div class="col-lg-4">
                                                <label>PROFESION</label>
                                                <input type="text" readonly="" value="<?php echo $prestacion->profesion ?>" name="consultorio" id="consultorio" class="form-control">
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="panel panel-info contenedor-paciente">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">PACIENTE</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="padding-bottom: 1rem;">
                                                <?php 
                                                use Carbon\Carbon;
                                                $fecha = explode("/", $prestacion->fecha_nac);
                                                $Born = Carbon::create($fecha[2],$fecha[1],$fecha[0]);
                                                $age = $Born->diff(Carbon::now())->format('%y Años, %m meses %d dias');
                                                ?>
                                                <div class="col-lg-3">
                                                    <label for="nro_dni_paciente">NRO DOC.</label>
                                                    <input type="text" readonly="" value="<?php echo $prestacion->nro_doc_ident ?>" name="dni_beneficiario" id="dni_beneficiario" class="form-control"/>
                                                    <input type="hidden" value="{{ $prestacion->tipo_doc_ident }}" name="tipo_doc_ident" id="tipo_doc_ident" />
                                                </div>
                                                <div class="col-lg-3">
                                                    <label>APELLIDOS Y NOMBRES</label>
                                                    <input readonly="" type="text" class="form-control" value="<?php echo $prestacion->asegurado_paterno." ".$prestacion->asegurado_materno.", ".$prestacion->asegurado_nombre ?>" name="beneficiario" id="beneficiario">
                                                    <input type="hidden" value="{{ $prestacion->asegurado_paterno }}" name="paterno_beneficiario" id="paterno_beneficiario">
                                                    <input type="hidden" value="{{ $prestacion->asegurado_materno }}" name="materno_beneficiario" id="materno_beneficiario">
                                                    <input type="hidden" value="{{ $prestacion->asegurado_nombre }}" name="nombre_beneficiario" id="nombre_beneficiario">
                                                </div>
                                                @if ($id_tipo_atencion == 1)
                                                    <div class="col-lg-2">
                                                        <label>TIPO BENEFICIARIO</label>
                                                        <input readonly="" type="text" class="form-control" value="{{$prestacion->parentesco}}" id="tipo_beneficiario" name="tipo_beneficiario" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                                    </div> 
                                                @endif
                                                @if ($id_tipo_atencion == 2)
                                                    <div class="col-lg-2">
                                                        <label>TIPO BENEFICIARIO</label>
                                                        <input readonly="" type="text" class="form-control" value="{{$prestacion->descripcion}}" id="tipo_beneficiario" name="tipo_beneficiario" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                                    </div> 
                                                @endif
                                                
                                                <div class="col-lg-2">
                                                    <label>NRO HC</label>
                                                    <input readonly="" type="text" class="form-control" value="<?php echo $prestacion->nro_historia ?>" id="nro_historia" name="nro_historia" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                                </div>
                                                <div class="col-lg-1">
                                                    <label>SEXO</label>
                                                    <input readonly=""type="text" class="form-control" value="<?php echo $prestacion->sexo ?>" id="nomsexo" name="nomsexo" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                                </div>
                                                <div class="col-lg-1">
                                                    <label>EDAD</label>
                                                    <input readonly="" type="text" class="form-control" value="<?php echo $prestacion->edadpaciente ?>" id="edad" name="edad" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if ($id_tipo_atencion == 1)
                                                    <div class="col-md-2">
                                                        <label>REFERIDO</label>
                                                        <input type="text" readonly="" value="{{ $referencia }}" name="referido" id="referido" class="form-control">
                                                    </div>
                                                @endif
                                                @if ($id_tipo_atencion == 2)
                                                    <div class="col-md-2">
                                                        <label>REFERIDO</label>
                                                        <input type="text" readonly="" value="{{ $referencia == 4 ? 'SI' : 'NO' }}" name="referido" id="referido" class="form-control">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>         
                                    </div>
                                    @if ($referencia == 4 || $referencia == 'SI')
                                        <div class="panel panel-info contenedor-referencia" id="contenedorReferencia">
                                            <div class="panel-heading">
                                                <h3 class="panel-title size-titulo">INFORMACIÓN DE REFERENCIA</h3>
                                            </div>
                                            <div class="panel-body">
                                                @if ($id_tipo_atencion == 1)
                                                    @if ($referencia == "SI")
                                                        <div class="col-md-5" id="establecimientoReferencia">
                                                            <label>ESTABLECIMIENTO REFERENCIA</label>
                                                            <input type="text" readonly="" value="<?php echo $prestacion->establecimiento_referencia ?>" name="establecimiento_referencia" id="establecimiento_referencia" class="form-control">
                                                        </div>
                                                        <div class="col-md-3" id="documentoReferencia">
                                                            <label>DOCUMENTO REFERENCIA</label>
                                                            <input type="text" readonly="" value="{{$prestacion->numero_doc}}" name="nro_doc_referencia" id="nro_doc_referencia" class="form-control">
                                                        </div>
                                                    @endif
                                                @endif
                                                @if($id_tipo_atencion == 2)
                                                    @if ($referencia == 4)
                                                        <div class="col-md-5" id="establecimientoReferencia">
                                                            <label>ESTABLECIMIENTO REFERENCIA</label>
                                                            <input type="text" readonly="" value="{{$prestacion->esta_eva}}" id="unidad_policia" name="unidad_policia" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control">
                                                        </div>
                                                        <div class="col-md-3" id="documentoReferencia">
                                                            <label>DOCUMENTO REFERENCIA</label>
                                                            <input type="text" readonly="" value="{{$prestacion->numero_oficio}}" name="consultorio" id="consultorio" class="form-control">
                                                        </div>
                                                    @endif
                                                @endif
                                            </div> 
                                        </div>
                                    @endif
                                    
                                    {{-- HISTORIAL DE DIAGNOSTICOS --}}
                                    <div class="panel panel-info contenedor-paciente">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">DIAGNOSTICOS REGISTRADOS</h3>
                                        </div>
                                        <div class="panel-body">
                                            @if (isset($diagnostico) && !is_null($diagnostico))
                                              <div class="row">
                                                <div class="alert alert-success" role="alert">
                                                  PRE-QUIRÚRGICO
                                                </div>
                                                <table id="tblDiagnostico" class="table table-bordered" cellspacing="0" width="100%" style="margin-top:5px;">
                                                    <thead>
                                                        <tr>
                                                            <th>N°</th>
                                                            <th class="col-lg-1">CÓDIGO</th>
                                                            <th class="col-lg-10">DESCRIPCIÓN</th>
                                                            <th class="col-lg-2">TIPO</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            $i = 1;
                                                            foreach($diagnostico as $row):
                                                        ?>
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td class="col-lg-1">{{$row->codigo}}</td>
                                                            <td class="col-lg-10">{{$row->nombre}}</td>
                                                            <td class="col-lg-2">{{$row->descripcion}}</td>
                                                        </tr>
                                                        <?php 
                                                            $i++;
                                                            endforeach;
                                                        ?>
                                                    </tbody>
                                                </table>
                                              </div>
                                            @endif
                                            @if ($flag_anestesia_quirurgica == true)  
                                              @if (isset($diagnosticocq) && !is_null($diagnosticocq))  
                                                <div class="row">
                                                  <div class="alert alert-success" role="alert">
                                                    QUIRÚRGICO
                                                  </div>
                                                  <table id="tblDiagnostico" class="table table-bordered" cellspacing="0" width="100%" style="margin-top:5px;">
                                                      <thead>
                                                          <tr>
                                                              <th>N°</th>
                                                              <th class="col-lg-1">CÓDIGO</th>
                                                              <th class="col-lg-10">DESCRIPCIÓN</th>
                                                              <th class="col-lg-2">TIPO</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                          <?php 
                                                              $i = 1;
                                                              foreach($diagnosticocq as $row):
                                                          ?>
                                                          <tr>
                                                              <td>{{$i}}</td>
                                                              <input type="hidden" class="id_diagcq" name="id_diagcq" id = "id_diagcq" value="{{$row->id_diagnostico}}" />
                                                              <td class="col-lg-1">{{$row->codigo}}</td>
                                                              <td class="col-lg-10">{{$row->nombre}}</td>
                                                              <td class="col-lg-2">{{$row->descripcion}}</td>
                                                          </tr>
                                                          <?php 
                                                              $i++;
                                                              endforeach;
                                                          ?>
                                                      </tbody>
                                                  </table>
                                                </div>
                                              @endif
                                            @endif
                                        </div>         
                                    </div>

                                    @if ($flag_anestesia_quirurgica == false)  
                                      @if ( is_null($diagnosticocq) )  
                                        <fieldset> {{-- AGREGAR DIAGNOSTICOS --}}
                                          <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading">
                                              <div class="panel-title text-center">
                                                <b>DIAGNÓSTICOS</b>
                                              </div>
                                            </div>
                                            <div class="panel-body">
                                              <table>
                                                <tr>
                                                  <td>
                                                    <button type="button" id="addDiagnosticoAnestesia" class="btn btn-primary"
                                                      data-toggle="modal" data-target="#addClassModal">Agregar
                                                      Diagnostico</button>
                                                  </td>
                                                </tr>
                                              </table>
                                              <br />
                                              <table class="display" id="tblDiagnosticoAnestesia" cellspacing="0" width="100%"
                                                style="margin-top:5px;">
                                                <thead>
                                                  <tr>
                                                    <th style="text-align: center;" width="90%">Descripción</th>
                                                    <th style="text-align: center;" width="10%">Tipo</th>
                                                  </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody>
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                        </fieldset>
                                      @endif
                                    @endif
                                    {{-- HISTORIAL DE PROCEDIMIENTOS --}}
                                    <div class="panel panel-info contenedor-paciente">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">PROCEDIMIENTOS INTERVENCIÓN</h3>
                                        </div>
                                        <div class="panel-body">
                                          @if (isset($procedimiento) && !is_null($procedimiento))
                                            <div class="row">
                                                <div class="alert alert-success" role="alert">
                                                  PRE-QUIRÚRGICO
                                                </div>
                                                <table id="tblDiagnostico" class="table table-bordered" cellspacing="0" width="100%" style="margin-top:5px;">
                                                    <thead>
                                                        <tr>
                                                            <th>N°</th>
                                                            <th class="col-lg-1">CÓDIGO</th>
                                                            <th class="col-lg-10">DESCRIPCIÓN</th>
                                                            <th class="col-lg-2">DIAGNOSTICO</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            $i = 1;
                                                            foreach($procedimiento as $row):
                                                        ?>
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td class="col-lg-1">{{$row->codigo}}</td>
                                                            <td class="col-lg-10">{{$row->nombre}}</td>
                                                            <td class="col-lg-2"><?php $model_diagnosticos = new \App\Models\Producto_receta; $diagnostico_id = $row->id_diagnostico; $diagnostico_nombre = $model_diagnosticos->get_diagnostico_nombrexProcedimiento($diagnostico_id); echo $diagnostico_nombre; ?></td>
                                                        </tr>
                                                        <?php 
                                                            $i++;
                                                            endforeach;
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                          @endif
                                          @if ($flag_anestesia_quirurgica == true)  
                                              @if (isset($procedimientocq) && !is_null($procedimientocq))  
                                                <div class="row">
                                                    <div class="alert alert-success" role="alert">
                                                      QUIRÚRGICO
                                                    </div>
                                                    <table id="tblDiagnostico" class="table table-bordered" cellspacing="0" width="100%" style="margin-top:5px;">
                                                        <thead>
                                                            <tr>
                                                                <th>N°</th>
                                                                <th class="col-lg-1">CÓDIGO</th>
                                                                <th class="col-lg-10">DESCRIPCIÓN</th>
                                                                <th class="col-lg-2">DIAGNOSTICO</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $i = 1;
                                                                foreach($procedimientocq as $row):
                                                            ?>
                                                            <tr>
                                                                <td>{{$i}}</td>
                                                                <td class="col-lg-1">{{$row->codigo}}</td>
                                                                <td class="col-lg-10">{{$row->nombre}}</td>
                                                                <td class="col-lg-2"><?php $model_diagnosticos = new \App\Models\Producto_receta; $diagnostico_id_cq = $row->id_diagnostico; $diagnostico_nombre_cq = $model_diagnosticos->get_diagnostico_nombrexProcedimiento($diagnostico_id_cq); echo $diagnostico_nombre_cq; ?></td>
                                                            </tr>
                                                            <?php 
                                                                $i++;
                                                                endforeach;
                                                            ?>
                                                        </tbody>
                                                    </table> 
                                                </div>
                                              @endif
                                          @endif
                                        </div>         
                                    </div>

                                    @if ($flag_anestesia_quirurgica == false)  
                                      @if ( is_null($procedimientocq) ) 
                                        <fieldset> {{-- AGREGAR PROCEDIMIENTO --}}
                                          <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading">
                                              <div class="panel-title text-center">
                                                <b>PROCEDIMIENTOS</b>
                                              </div>
                                            </div>
                                            <div class="panel-body">
                                              <table>
                                                <tr>
                                                  <td>
                                                    <button type="button" id="addProcedimientoAnestesia" class="btn btn-primary"
                                                      data-toggle="modal" data-target="#addClassModal">Agregar
                                                      Procedimiento</button>
                                                  </td>
                                                </tr>
                                              </table>
                                              <br />
                                              <table class="display" id="tblProcedimientoAnestesia" cellspacing="0" width="100%"
                                                style="margin-top:5px;">
                                                <thead>
                                                  <tr>
                                                    <th width="45%">Procedimiento</th>
                                                    <th width="5%">Cantidad</th>
                                                    <th width="45%">Diagnósticos</th>
                                                    <th width="5%"></th>
                                                  </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody>
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                        </fieldset>
                                      @endif
                                    @endif
                                    
                                    <fieldset id="fsRecetaVale">
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                          <div class="panel-title text-center">
                                            <b>PRODUCTOS MEDICOS</b>
                                          </div>
                                        </div>
                                        <div class="panel-body">
                                          @if (isset($receta) && !is_null($receta))
                                            <div class="row">
                                              <div class="alert alert-success" role="alert">
                                                  PRE-QUIRÚRGICO
                                                </div>
                                              <?php 
                                              foreach($receta as $rowReceta):
                                                if ($rowReceta->fecha_firma == "") {
                                                                $flag_firmar = 1;
                                                              }

                                                $model_productos_receta = new \App\Models\Producto_receta;
                                                $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);
                                              ?>
                                              <div class="panel-body">
                                                <div class="form-group">
                                                  <div class="col-md-12">
                                                    <label class="col-md-2" style="padding-left:0px">RECETA VALE N °</label>
                                                    <div class="col-md-1">
                                                      <p><?php echo $rowReceta->nro_receta; ?></p>
                                                    </div>
                                                    <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                                    <div class="col-md-2">
                                                      <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                                                    </div>
                                                    <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                                    <div class="col-md-4" style="padding:0px">
                                                      <p><?php echo $rowReceta->farmacia; ?></p>
                                                    </div>
                                                  </div>
                                                </div>
                                                <table class="table table-bordered" cellspacing="0" width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th width="10%">Codigo</th>
                                                      <th width="30%">Producto farmacético o dispositivo médico</th>
                                                      <th width="10%">Unidad Medida</th>
                                                      <th width="7%">Tiempo(Dias)</th>
                                                      <th width="15%">Posologia</th>
                                                      <th width="10%">Via Administración</th>
                                                      <th width="8%">Cantidad Prescrita</th>
                                                      <th width="10%">diagnosticos</th>
                                                    </tr>
                                                  </thead>
                                                  <tfoot>
                                                  </tfoot>
                                                  <?php foreach($medicamentos as $row):?>
                                                  <tr>
                                                    <td><?php echo $row->codigo?></td>
                                                    <td><?php echo $row->nombre?>
                                                        <?php 
                                                          if($row->codigo=='59075700'):
                                                            echo "(".$row->cantidad_prescrita."L)";
                                                          endif; 
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row->abreviatura?></td>
                                                    <td><?php echo $row->tiempo_periodo?></td>
                                                    <td><?php echo $row->posologia?></td>
                                                          <td>{{$row->via}}</td>
                                                          <td><?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                                            echo $row->cantidad_prescrita/1000;
                                                            else:
                                                              echo $row->cantidad_prescrita;
                                                            endif; ?>
                                                          </td>
                                                          <td><?php
                                                          $model_productos_receta = new \App\Models\Producto_receta;
                                                                $diagnostico_id = $row->id_diagnosticos;
                                                                $diagnostico_nombre = $model_productos_receta->get_diagnostico_nombre($diagnostico_id);
                                                                echo $diagnostico_nombre; ?>    
                                                            </td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                  <tbody>
                                                  </tbody>
                                                </table>
                                              </div>
                                              <?php endforeach;?>
                                            </div>
                                          @endif
                                          @if ($flag_anestesia_quirurgica == true)  
                                            @if (isset($recetacq) && !is_null($recetacq))
                                              <div class="row">
                                                <div class="alert alert-success" role="alert">
                                                  QUIRÚRGICO
                                                </div>
                                                <?php foreach($recetacq as $rowReceta):
                                                  if ($rowReceta->fecha_firma == "") {
                                                                  $flag_firmar = 1;
                                                                }

                                                  $model_productos_receta = new \App\Models\Producto_receta;
                                                  $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);

                                                ?>
                                                <div class="panel-body">
                                                  <div class="form-group">
                                                    <div class="col-md-12">
                                                      <label class="col-md-2" style="padding-left:0px">RECETA VALE N °</label>
                                                      <div class="col-md-1">
                                                        <p><?php echo $rowReceta->nro_receta; ?></p>
                                                      </div>
                                                      <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                                      <div class="col-md-2">
                                                        <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                                                      </div>
                                                      <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                                      <div class="col-md-4" style="padding:0px">
                                                        <p><?php echo $rowReceta->farmacia; ?></p>
                                                      </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                      <button tabindex="75" type="button" id="addProductoAdicionalAnestesia" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">
                                                        Agregar Producto
                                                      </button>
                                                    </div>
                                                  </div>
                                                  <table class="table table-bordered" id="tblMedicamentosAnestesia" cellspacing="0" width="100%">
                                                    <thead>
                                                      <tr>
                                                        <th width="10%">Codigo</th>
                                                        <th width="30%">Producto farmacético o dispositivo médico</th>
                                                        <th width="10%">Unidad Medida</th>
                                                        <th width="7%">Tiempo(Dias)</th>
                                                        <th width="15%">Posologia</th>
                                                        <th width="10%">Via Administración</th>
                                                        <th width="8%">Cantidad Prescrita</th>
                                                        <th width="5%">diagnosticos</th>
                                                        <th width="10%" colspan="2">
                                                          <div style="width: 100%; display: flex; justify-content: space-around">
                                                            <span>Cancelar Acción</span>
                                                            <button type="button" class="btn btn-danger btn-xs" style="width: auto;" id="restaurarColoresMedicamentos">
                                                              <i class="fa fa-times"></i>
                                                            </button>
                                                          </div>
                                                        </th>
                                                      </tr>
                                                      <tr>
                                                      </tr>
                                                    </thead>
                                                    <tfoot>
                                                    </tfoot>
                                                    <?php foreach($medicamentos as $row): ?>
                                                    <tr>
                                                      <td><?php echo $row->codigo?></td>
                                                      <td><?php echo $row->nombre?>
                                                          <?php 
                                                            if($row->codigo=='59075700'):
                                                              echo "(".$row->cantidad_prescrita."L)";
                                                            endif; 
                                                          ?>
                                                      </td>
                                                      <td>
                                                        <?php echo $row->abreviatura?>
                                                        <input name="id_producto_receta_editar[]" type="hidden" value="{{ $row->id_producto_receta }}"/>
                                                      </td>
                                                      <td><input name="tiempo_periodo_editar[]" type="text" value="<?php echo $row->tiempo_periodo?>" disabled class="form-control" style="height: 28px !important;font-weight: 800;" required/></td>
                                                      <td><input name="posologia_editar[]" type="text" value="<?php echo $row->posologia?>" disabled class="form-control" style="height: 28px !important;font-weight: 800;" required/></td>
                                                      <td>
                                                        <select name="id_via_administracion[]" class="form-control id_via_administracion_anestesia" style="margin:0 20px;width:150px" disabled>
                                                          <option value="0">--Seleccionar--</option>
                                                          @foreach ($via_administracion as $va)
                                                          <option @if ($row->id_via_administracion == $va->id) selected @endif value="{{ $va->id }}">{{ $va->descripcion }}</option>
                                                          @endforeach
                                                        </select>
                                                      </td>
                                                      <td><input name="cantidad_prescrita_editar[]" type="text" value="<?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                                        echo $row->cantidad_prescrita/1000;
                                                        else:
                                                          echo $row->cantidad_prescrita;
                                                        endif; ?>" class="form-control" disabled style="height: 28px !important;font-weight: 800;" required/></td>
                                                      <td><?php
                                                          $model_productos_receta = new \App\Models\Producto_receta;
                                                          $diagnostico_id = $row->id_diagnosticos;
                                                          $diagnostico_nombre = $model_productos_receta->get_diagnostico_nombre($diagnostico_id);
                                                          echo $diagnostico_nombre; ?>    
                                                      </td>
                                                      <td>
                                                        <input type="hidden" value="" class="accionRowProductoAnestesia" id="accionRowProductoAnestesia" name="accionRowProductoAnestesia[]"/>
                                                        <button type="button"  class="btn btn-primary btn-xs EditarFilaProductosAnestesia" data-id-producto="{{ $row->id_producto_receta }}" data-toggle="modal" style="margin-left:4px">
                                                          <i class="fa fa-edit"></i> Editar
                                                        </button>
                                                      </td>
                                                      <td>
                                                        <button type="button"  class="btn btn-danger AnularFilaProductosAnestesia btn-xs" data-id-producto="{{ $row->id_producto_receta }}" data-toggle="modal" style="margin-left:4px">
                                                          <i class="fa fa-times"></i> Eliminar
                                                        </button>
                                                      </td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                    <tbody>
                                                    </tbody>
                                                  </table>
                                                </div>
                                                <?php endforeach;?>
                                              </div>
                                              {{--<div class="row">--}}
                                                <div  class="form-group" style="display: none">
                                                  <select class="form-control" id="cmbMedicamentoAdicionalTempAnestesia" tabindex="16"
                                                    style="width: 500px">
                                                    <option value="">Seleccionar Producto</option>
                                                  </select>
                                                </div>
                                                <div  class="form-group" style="display: none">
                                                  <select class="form-control" id="diagnosticosAdicionalTempAnestesia" tabindex="16"
                                                    style="width: 500px">
                                                    <option value="">Seleccionar Diagnostico</option>
                                                  </select>
                                                </div>
                                                <input type="hidden" value="0" name="hidden_adicional_rowIndex" id="hidden_adicional_rowIndex">
                                                <div class="form-group" style="padding: 0 2rem;">
                                                  <table id="tblMedicamentosAdicionalAnestesia" class="display" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                        <th>Producto farmacéutico o dispositivo médico</th>
                                                        <!--th style="width:10%">Stock UE-020</th>
                                                        <th style="width:10%">Stock Establecimiento</th-->
                                                        <th style="width:10%">Stock Farmacia</th>
                                                        <th>Tiempo(Dias)</th>
                                                        <th> Via Adm. </th>
                                                        <th>Posologia</th>
                                                        <th> Cant. Pres.</th>
                                                        <th> DiagnosticoxProducto </th>
                                                        <th>  </th>
                                                        <th>  </th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                    </tfoot>
                                                    <tbody>
                                                    </tbody>
                                                  </table>
                                                </div>
                                              {{--</div>--}}
                                            @endif
                                          @endif
                                        </div>
                                      </div>
                                    <fieldset>
                                        @if ($flag_anestesia_quirurgica == false)  
                                          @if ( is_null($recetacq) ) 
                                            <div class="panel panel-info panel-sm">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                            <label>PRESCRIBIR RECETA</label>
                                                        </div>
                                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                            <button tabindex="70" type="button" id="addRecetaValeAnestesia" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                            <div class="handle"></div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          @endif
                                        @endif
                                        <fieldset id="fsRecetaValeAnestesia" style="display:none">
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title size-titulo">PRODUCTOS MEDICOS</h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="col-md-2">
                                                            <label>FEC. EXPEDICIÓN (A)</label>
                                                            <input tabindex="71" type="text" class="form-control datepicker"
                                                                value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>"
                                                                name="fecha_expedicion" id="fecha_expedicion" tabindex="2"
                                                                data-date-end-date="0d" autocomplete="off" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label>FARMACIA</label>
                                                            <select tabindex="72" class="form-control" name="id_farmacia_receta" id="id_farmacia_receta"
                                                                style="width:100%" onchange="getProductoAndAddProductoAnestesia()">
                                                                <option value="">-Seleccionar-</option>
                                                                <?php foreach($farmacias as $row):?>
                                                                <option value="<?php echo $row->id?>"
                                                                <?php if($row->id==18) echo "selected='selected'"?>>
                                                                <?php echo $row->nombre?></option>
                                                                <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <table>
                                                                <tr>
                                                                <td>
                                                                    <button tabindex="75" type="button" id="addProductoAnestesia" class="btn btn-primary"
                                                                    data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                                                                </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div  class="form-group" style="display: none">
                                                        <select class="form-control" id="cmbMedicamentoTempAnestesia" tabindex="16"
                                                        style="width: 500px">
                                                        <option value="">Seleccionar Producto</option>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" value="0" name="hidden_anestesia_rowIndex" id="hidden_anestesia_rowIndex">
                                                    <div class="form-group">
                                                        <table id="tblAnestesia" class="display" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                            <th>Producto farmacéutico o dispositivo médico</th>
                                                            <!--th style="width:10%">Stock UE-020</th>
                                                            <th style="width:10%">Stock Establecimiento</th-->
                                                            <th style="width:10%">Stock Farmacia</th>
                                                            <th>Tiempo(Dias)</th>
                                                            <th> Via Adm. </th>
                                                            <th>Posologia</th>
                                                            <th> Cant. Pres.</th>
                                                            <th> DiagnosticoxProducto </th>
                                                            <th>  </th>
                                                            <th>  </th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                        </tfoot>
                                                        <tbody>
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </fieldset>

                                    <div class="panel panel-info contenedor-paciente">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">PROGRAMACIÓN</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                        <label>CONFIRMAR INTERVENCIÓN</label>
                                                    </div>
                                                    <div class="col-lg-3" style="margin-left:0px;padding-left:0px">
                                                        <button tabindex="70" type="button" id="addConfirmaIntervencion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                        <div class="handle"></div>
                                                        </button>
                                                    </div>
                                                    <div class="row addConfirmaIntervencion" style="margin-bottom: 3rem; display: none;">
                                                      <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                          <label>FECHA DE INTERVENCIÓN</label>
                                                      </div>
                                                      <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                          <input class="form-control input-sm" type="date" name="fecha_intervencion" id="fecha_intervencion" placeholder="Fecha Intervención" value="{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->fecha_intervencion : "" }}" maxlength="10">
                                                      </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                  <div class="row addConfirmaIntervencion" style="margin-bottom: 3rem; display: none;">
                                                    <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                        <label style="margin-left: 2rem;">HORA DE INICIO</label>
                                                    </div>
                                                    <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                        <input value="{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->hora_inicio_intervencion : "" }}" type="time" class="form-control" style="width: 100%;" id="hora_inicio_intervencion" name="hora_inicio_intervencion" />
                                                    </div>
                                                    <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                        <label style="margin-left: 2rem;">HORA DE TÉRMINO</label>
                                                    </div>
                                                    <div class="col-lg-3" style="padding-top:5px;margin-right:0px;padding-right:0px;">
                                                        <input value="{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->hora_fin_intervencion : "" }}" type="time" class="form-control" style="width: 100%;" id="hora_fin_intervencion" name="hora_fin_intervencion" />
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="row" id="contenedorAnestesiaProgramacion">
                                                <div class="row addConfirmaIntervencion" style="margin-bottom: 3rem; display: none;">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                                <label style="margin-left: 2rem;">SALA DE INTERVENCIÓN</label>
                                                            </div>
                                                            <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                                <select id="id_sala" name="id_sala" class="form-control" style="width: 100%">
                                                                  <option value="0">-Seleccionar-</option>
                                                                  @foreach ($salas as $sa)
                                                                  <option value="{{ $sa->id }}"
                                                                    @if ($flag_anestesia_quirurgica == '1')
                                                                      @if ($row_anestesia_quirurgica->id_sala_intervencion == $sa->id)
                                                                        selected
                                                                      @endif
                                                                    @endif
                                                                  >{{ $sa->nombre }}</option>
                                                                  @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                                                <label style="margin-left: 2rem;">TIPO DE ANESTESIA</label>
                                                            </div>
                                                            <div class="col-lg-4" style="padding-bottom: 1rem;">
                                                                <select id="id_tipo_anestesia" name="id_tipo_anestesia" class="form-control" style="width: 100%">
                                                                  <option value="0">-Seleccionar-</option>
                                                                  @foreach ($tipo_anestesia as $ta)
                                                                  <option value="{{ $ta->id }}"
                                                                    @if ($flag_anestesia_quirurgica == '1')
                                                                      @if ($row_anestesia_quirurgica->id_tipo_anestesia == $ta->id)
                                                                        selected
                                                                      @endif
                                                                    @endif
                                                                  >{{ $ta->descripcion }}</option>
                                                                  @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="row intro-contenedor">
                                                      <div class="col-lg-3" style="padding-bottom: 1rem;">
                                                        <button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="addAnestesiologoAnestesia">Anestesiologos que Intervienen Operación</button>
                                                        <span class="invalid-feedback" style="display: none; color: red;">
                                                          {{ $errors->first('addAnestesiologoAnestesia') }}
                                                        </span>
                                                      </div>
                                                      <div class="col-lg-9" style="display: flex; justify-content: right; padding-right: 16rem;">
                                                        <div class="col-lg-2" style="padding-bottom: 1rem;">
                                                          <button type="button" class="btn btn-danger btn-xs" style="width: 100%;" id="restaurarColores"><i class="fa fa-times"></i> Filtro</button>
                                                        </div>
                                                      </div>
                                                      <div class="col-lg-12">
                                                        <table class="table table-bordered" id="tblAnestesiologoAnestesia" cellspacing="0" width="100%" style="margin-top:5px;">
                                                          <tbody>
                                                            @if(!empty($anetesiologos))
                                                              @foreach($anetesiologos as $key => $row)
                                                                @if ($row->estado !== 3)
                                                                  <tr>
                                                                    <td>
                                                                      {{$row->nombre}}   {{$row->paterno}}  {{$row->materno}} 
                                                                    </td>
                                                                    <td>
                                                                      {{$row->descripcion}}
                                                                    </td>
                                                                    <td>
                                                                      <button type="button"  class="btn btn-danger AnularFilaAnestesiologos btn-xs" data-id_anestesiologo_registro="{{ $row->id }}" data-toggle="modal" style="margin-left:4px">
                                                                        <i class="fa fa-times"></i> Eliminar
                                                                      </button>
                                                                    </td>
                                                                  </tr>
                                                                @endif
                                                              @endforeach
                                                            @endif
                                                          </tbody>
                                                        </table>
                                                        <div id="hiddenInputsContainer"></div>
                                                      </div>
                                                    </div>
                                                </div>
                                                <fieldset>
                                                    <div class="panel panel-info panel-sm">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-lg-5" style="display: flex; align-items: center;">
                                                                    <div class="col-lg-6" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                                        <label>OBSERVACIÓN DE INTERVENCIÓN QUIRÚRGICA</label>
                                                                    </div>
                                                                    <div class="col-lg-6" style="margin-left:0px;padding-left:0px">
                                                                        <button tabindex="70" type="button" id="addObservacionIntervencionQuirurgica" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                        <div class="handle"></div>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row addObservacionIntervencionQuirurgica" style="margin-bottom: 3rem; display: none;">
                                                                <div class="row" style="padding: 0 3rem;" >
                                                                    <label>MOTIVO DE OBSERVACION PARA INTERVENCION QX</label>
                                                                </div>
                                                                <div class="row" style="padding: 0 3rem;" >
                                                                    <input value="{{ ($flag_anestesia_quirurgica == '1') ? $row_anestesia_quirurgica->observacion : "" }}" type="text" data-toggle="tooltip" data-placement="top" name="observacion" id="observacion" class="input-sm" style="width:100%; text-align:left !important;" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>         
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <br/>
                                        <div class="form-group">
                                            <div class="col-md-10"></div>
                                            <div class="col-md-2">
                                                {{--<a class="form-control btn btn-success" onclick="validarFormulario()" style="text-align:center!important">GUARDAR</a>--}}
                                                <input class="btn btn-primary pull-right" value="GUARDAR" name="crea" type="submit" form="frmCqAnestesia" id="btnPrestacionCqAnestesia" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="tabHistorialImagenes">
                                <fieldset>
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                        <div class="panel-title text-center">
                                            <b>HISTORIAL DE EXAMENES DE IMAGENOLOGIA</b>
                                        </div>
                                        </div>
                                        <div class="panel-body">
                                        <div class="form-group">
                                            <div id='divRadiologico'></div>
                                        </div>
                                        </div>
                                    </div>
                                    </fieldset>
                            </div>        
                        </div>    
                    </div>  
                </div>
            </div>
            <div class="tab-pane fade" id="tabInformeAnestesia">
                <div class="panel panel-default filterable">            
                    <div class="panel-heading">
                        <h3 class="panel-title size-titulo">
                            INFORME DE ANESTESIA
                        </h3>
                    </div>
                    <div style=" font-size: 11px" class="panel-body">
                        <form id="FormularioAnestesia" name="FormularioAnestesia" enctype="multipart/form-data" method="POST">
                            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
                            <div class="nav-tabs-custom">

                                <div class="tab-pane active" style="font-size: 11px;">
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">
                                                DATOS GENERALES
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="d-flex">
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="nombre">FECHA:</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>HORA</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>EDAD</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>PESO</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="telefono">HB</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="direccion">HLO</label>
                                                                <input type="number" class="form-control" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>INR</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>GRUPO FACTOR</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>NNEUMOLOGO</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>CARDIOLOGICO</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="ciudad">GLUCOSA:</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="pais">CREATININA:</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>UREA:</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>ASA:</label>
                                                                <input type="number" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                        <label>COMPLICACIONES PRE OPERATORIO</label>
                                                        <textarea name="" id="" cols="120" rows="2" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 d-flex flex-column justify-content-between">
                                                    <div class="col-md-12">
                                                        <label>PREDITACIÓN</label>
                                                        <textarea name="" id="" cols="50" rows="13" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading d-flex justify-content-between align-items-center">
                                            <h3 class="panel-title size-titulo" style="display: inline;">
                                                INFORMACIÓN ANESTESIAR
                                            </h3>
                                            <button tabindex="70" type="button" id="addRecetaValeAnestesia" class="btn btn-lg btn-toggle-heading" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                <div class="handle-heading"></div>
                                            </button>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="col-lg-6">
                                                        <label for="nombre">INC ANEST :</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="col-lg-6">
                                                        <label for="nombre">INDUCCION :</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="col-lg-6">
                                                        <label for="nombre">TEM ANEST:</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="col-lg-6">
                                                        <label for="nombre">INC CIRUGIA :</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="col-lg-6">
                                                        <label for="nombre">INDUCCION :</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="col-lg-6">
                                                        <label for="nombre">TEM CIRUGIA:</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading d-flex justify-content-between align-items-center">
                                            <h3 class="panel-title size-titulo" style="display: inline;">
                                                METODO DE ANESTESIA
                                            </h3>
                                            <button tabindex="70" type="button" id="addRecetaValeAnestesia" class="btn btn-lg btn-toggle-heading" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                <div class="handle-heading"></div>
                                            </button>
                                        </div>
                                        <div class="panel-body">
                                            <div style="display: flex; flex-direction: column; gap: 1rem; padding-bottom: 3rem;">
                                                <div style="display: flex; gap: 0.5rem;">
                                                    <div class="col-sm-1">
                                                        <label>INC ANEST :</label>
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <input type="checkbox" name="" id="" value="1" class="" checked/>
                                                    </div>
                                                </div>
                                                <div style="display: flex; gap: 0.5rem;">
                                                    <div class="col-sm-1">
                                                        <label>INC CIRUGIA :</label>
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <input type="checkbox" name="" id="" value="1" class="" checked/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="border: 1px solid #d3d3d3; border-radius: 5px; margin-left: 0.2rem; margin-right: 0.2rem;">
                                        <div class="row">
                                            <div class="col-lg-4" style="display: flex; align-items: center;">
                                                <div class="col-lg-6" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                    <label>REGISTRAR SIGNOS VITALES</label>
                                                </div>
                                                <div class="col-lg-6" style="margin-left:0px;padding-left:0px">
                                                    <button tabindex="70" type="button" id="saddConfirmaIntervencion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                    <div class="handle"></div>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-lg-8"></div>
                                        </div>
                                        <br />
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading">
                                                <h3 class="panel-title size-titulo">
                                                    HISTORIAL DE ANESTESIA
                                                </h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-6"></div>
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-6">
                                                            <a href="javascript:void(0)" aria-hidden="true" class="btn btn-info btn-sm"  onClick="verGrafica()" >
                                                                <i class="fa fa-line-chart" aria-hidden="true">
                                                                </i> Ver Graficas
                                                            </a>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <a href="javascript:void(0)" aria-hidden="true" class="btn btn-success btn-sm" onClick="crearAnestesia()" >
                                                                NUEVO ANESTESIA
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">N°</th>
                                                                    <th class="text-center">FECHA</th>
                                                                    <th class="text-center">HORA</th>
                                                                    <th class="text-center">SAT 02</th>
                                                                    <th class="text-center">P.A</th>
                                                                    <th class="text-center">PULSO</th>
                                                                    <th class="text-center">CX</th>
                                                                    <th class="text-center">ANEST.</th>
                                                                    <th class="text-center">PAM.</th>
                                                                    <th class="text-center">PVC</th>
                                                                    <th class="text-center">02/L/Min</th>
                                                                    <th class="text-center">VENTIRACION</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="text-center" >01</td>
                                                                    <td class="text-center" >15/08/2024</td>
                                                                    <td class="text-center" >06:30 AM</td>
                                                                    <td class="text-center" >100</td>
                                                                    <td class="text-center" >34</td>
                                                                    <td class="text-center" >100</td>
                                                                    <td class="text-center" >120</td>
                                                                    <td class="text-center" >47</td>
                                                                    <td class="text-center" >65</td>
                                                                    <td class="text-center" >23</td>
                                                                    <td class="text-center" >45</td>
                                                                    <td class="text-center" >24</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-center" >02</td>
                                                                    <td class="text-center" >15/08/2024</td>
                                                                    <td class="text-center" >09:00 AM</td>
                                                                    <td class="text-center" >98</td>
                                                                    <td class="text-center" >45</td>
                                                                    <td class="text-center" >68</td>
                                                                    <td class="text-center" >140</td>
                                                                    <td class="text-center" >34</td>
                                                                    <td class="text-center" >76</td>
                                                                    <td class="text-center" >43</td>
                                                                    <td class="text-center" >65</td>
                                                                    <td class="text-center" >25</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                {{--<table class="table"  cellspacing="0" width="100%" style="margin-top:5px;">    
                                                <tr>
                                                    <td width="10%">
                                                    FECHA
                                                    <input type="text" class="form-control datepicker"
                                                                style="height: 28px !important;" autocomplete="off"
                                                                name="fecha" id="fecha"
                                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" >
                                                    </td>
                                                    <td width="10%">HORA INICIO
                                                    <input type="time" class="form-control"
                                                            style="height: 28px !important;" autocomplete="off"
                                                            name="hora_inicio" id="hora_inicio"
                                                            value="{{ \Carbon\Carbon::now()->format('H:i') }}">
                                                    </td>
                                                    <td width="10%">HORA FIN
                                                    <input type="time" class="form-control"
                                                            style="height: 28px !important;" autocomplete="off"
                                                            name="hora_fin" id="hora_fin"
                                                            value="{{ \Carbon\Carbon::now()->format('H:i') }}">
                                                    </td>
                                                    <td width="25%"><br>
                                                    <a href="javascript:void(0)" aria-hidden="true"
                                                        class="btn btn-success btn-sm" onClick="crearAnestesia()" >NUEVO ANESTESIA
                                                    </a>
                                                    </td>
                                                </tr>
                                                </table>--}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="border: 1px solid #d3d3d3; border-radius: 5px; margin-left: 0.2rem; margin-right: 0.2rem;">
                                        <div class="row">
                                            <div class="col-lg-4" style="display: flex; align-items: center;">
                                                <div class="col-lg-6" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                    <label>REGISTRAR BALANCE HIDRICO</label>
                                                </div>
                                                <div class="col-lg-6" style="margin-left:0px;padding-left:0px">
                                                    <button tabindex="70" type="button" id="baddConfirmaIntervencion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                    <div class="handle"></div>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-lg-8"></div>
                                        </div>
                                        <br />
                                        <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading">
                                                <h3 class="panel-title size-titulo">
                                                    BALANCE HIDRICO
                                                </h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-6"></div>
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-6">
                                                            <a href="javascript:void(0)" aria-hidden="true" class="btn btn-info btn-sm"  onClick="verGrafica()" >
                                                                <i class="fa fa-line-chart" aria-hidden="true">
                                                                </i> Ver Graficas
                                                            </a>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <a href="javascript:void(0)" aria-hidden="true" class="btn btn-success btn-sm" onClick="crearBalanceHidrico()" >
                                                                NUEVO ANESTESIA
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">N°</th>
                                                                <th class="text-center">FECHA</th>
                                                                <th class="text-center">HORA</th>
                                                                <th class="text-center">AYUNO</th>
                                                                <th class="text-center">REQ. BASAL</th>
                                                                <th class="text-center">PER INSENSIBLE</th>
                                                                <th class="text-center">SANGRADO</th>
                                                                <th class="text-center">DEB URINARIO</th>
                                                                <th class="text-center">SNG</th>
                                                                <th class="text-center">3ER<br/>ESPACIO</th>
                                                                <th class="text-center">OTROS</th>
                                                                <th class="text-center">TOTAL</th>
                                                                <th class="text-center">BALANCE</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center" >01</td>
                                                                <td class="text-center" >15/08/2024</td>
                                                                <td class="text-center" >06:30 AM</td>
                                                                <td class="text-center" >100</td>
                                                                <td class="text-center" >34</td>
                                                                <td class="text-center" >100</td>
                                                                <td class="text-center" >120</td>
                                                                <td class="text-center" >47</td>
                                                                <td class="text-center" >65</td>
                                                                <td class="text-center" >23</td>
                                                                <td class="text-center" >45</td>
                                                                <td class="text-center" >24</td>
                                                                <td class="text-center" >324</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center" >01</td>
                                                                <td class="text-center" >15/08/2024</td>
                                                                <td class="text-center" >06:30 AM</td>
                                                                <td class="text-center" >100</td>
                                                                <td class="text-center" >34</td>
                                                                <td class="text-center" >100</td>
                                                                <td class="text-center" >120</td>
                                                                <td class="text-center" >47</td>
                                                                <td class="text-center" >65</td>
                                                                <td class="text-center" >23</td>
                                                                <td class="text-center" >45</td>
                                                                <td class="text-center" >24</td>
                                                                <td class="text-center" >324</td>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <br/>
                                        <div class="form-group">
                                            <div class="col-md-8"></div>
                                            <div class="col-md-2">
                                                {{--<a class="form-control btn btn-success" onclick="validarFormulario()" style="text-align:center!important">GUARDAR</a>--}}
                                                <a class="form-control btn btn-success" style="text-align:center!important">GUARDAR</a>
                                            </div>
                                            <div class="col-md-2">
                                                {{--<a class="form-control btn btn-success" onclick="validarFormulario()" style="text-align:center!important">GUARDAR</a>--}}
                                                <a class="form-control btn btn-danger" style="text-align:center!important">FIRMAR</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal NUEVO ANESTESIA -->
<div class="modal fade" id="modalNuevoAnestesia" tabindex="-1" role="dialog" aria-labelledby="modalAnestesiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b>NUEVO ANESTESIA</b>
            </div>
            <form id="FormularioAnestesia" name="FormularioAnestesia" enctype="multipart/form-data"  method="POST">
              <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
              <div class="modal-body">
                  <div class="nav-tabs-custom" style="margin-bottom: -40px;">
                      <div class="tab-content">
                          <div class="tab-pane active" style="font-size: 11px;">
                            {{--<fieldset>
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="panel-title text-center">
                                      <b>DATOS GENERALES <p id="id_area_disabled"></p></b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <div class="d-flex">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="nombre">FECHA:</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>HORA</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                              <div class="form-group">
                                                  <label>EDAD</label>
                                                  <input type="number" class="form-control" >
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                            <div class="form-group">
                                                <label>PESO</label>
                                                <input type="number" class="form-control" >
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="telefono">HB</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="direccion">HLO</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                              <div class="form-group">
                                                  <label>INR</label>
                                                  <input type="number" class="form-control">
                                              </div>
                                            </div>
                                            <div class="col-md-2">
                                              <div class="form-group">
                                                  <label>GRUPO FACTOR</label>
                                                  <input type="number" class="form-control">
                                              </div>
                                            </div>
                                            <div class="col-md-2">
                                              <div class="form-group">
                                                  <label>NNEUMOLOGO</label>
                                                  <input type="number" class="form-control">
                                              </div>
                                            </div>
                                            <div class="col-md-2">
                                              <div class="form-group">
                                                  <label>CARDIOLOGICO</label>
                                                  <input type="number" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="ciudad">GLUCOSA:</label>
                                                    <input type="number" class="form-control" id="ciudad">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pais">CREATININA:</label>
                                                    <input type="number" class="form-control" id="pais">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                              <div class="form-group">
                                                  <label>UREA:</label>
                                                  <input type="number" class="form-control">
                                              </div>
                                            </div>
                                            <div class="col-md-3">
                                              <div class="form-group">
                                                  <label>ASA:</label>
                                                  <input type="number" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>COMPLICACIONES PRE OPETATORIO</label>
                                            <textarea name="" id="" cols="120" rows="3" class="form-control"></textarea>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex flex-column justify-content-between">
                                      <div class="col-md-12">
                                        <label>PREDITACIÓN</label>
                                        <textarea name="" id="" cols="50" rows="15" class="form-control"></textarea>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </fieldset>--}}

                            <fieldset>
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="panel-title text-center">
                                      <b>AGREGAR INFORMACION DE ANESTESIA </b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <div class="d-flex">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="nombre">HORA:</label>
                                                    <input type="time" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>SAT 02</label>
                                                    <input type="email" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                              <div class="form-group">
                                                  <label>ETC02</label>
                                                  <input type="email" class="form-control">
                                              </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>P.A</label>
                                                    <input type="email" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="telefono">PULSO</label>
                                                    <input type="tel" class="form-control" id="telefono" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="direccion">CX</label>
                                                    <input type="text" class="form-control" id="direccion" >
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                              <div class="form-group">
                                                  <label>ANEST</label>
                                                  <input type="email" class="form-control">
                                              </div>
                                            </div>
                                            <div class="col-md-2">
                                              <div class="form-group">
                                                  <label>PAM.</label>
                                                  <input type="email" class="form-control">
                                              </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="ciudad">PVC:</label>
                                                    <input type="text" class="form-control" id="ciudad" >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pais">TEMP:</label>
                                                    <input type="text" class="form-control" id="pais" >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                              <div class="form-group">
                                                  <label>02/L/Min:</label>
                                                  <input type="email" class="form-control">
                                              </div>
                                            </div>
                                            <div class="col-md-3">
                                              <div class="form-group">
                                                  <label>VENTILACIÓN:</label>
                                                  <input type="email" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              {{-- INDUCCION --}}
                              <div class="panel panel-bordered panel-success">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">INDUCCION</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addInduccion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddInduccion" style="display:none">
                                    <div class="d-flex">
                                      <div class="col-md-12">
                                          <div class="row">
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label>MIDAZ:</label>
                                                  <input type="text" class="form-control form-control-sm" >
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label >PROFOF:</label>
                                                  <input type="text" class="form-control form-control-sm">
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                <label >ROCUR:</label>
                                                <input type="text" class="form-control form-control-sm">
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label>SENTAN/REMI:</label>
                                                <input type="text" class="form-control form-control-sm" >
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label >PENTOTAL SCO:</label>
                                                <input type="text" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center">
                                              <label >VERUR:</label>
                                              <input type="text" class="form-control form-control-sm">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>
                              {{-- MANTENIMIENTO --}}
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">MANTENIMIENTO</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addMantenimiento" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddMantenimiento" style="display:none">
                                    <div class="d-flex">
                                      <div class="col-md-12">
                                          <div class="row">
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label>SEVOFLUORANC:</label>
                                                  <input type="text" class="form-control form-control-sm" >
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label >REMIFENILO:</label>
                                                  <input type="text" class="form-control form-control-sm">
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                <label >PROPOFOL:</label>
                                                <input type="text" class="form-control form-control-sm">
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label>ISOFLUORANE:</label>
                                                <input type="text" class="form-control form-control-sm" >
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label >FETANILO:</label>
                                                <input type="text" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center">
                                              <label >KETAMAMINA:</label>
                                              <input type="text" class="form-control form-control-sm">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>
                              {{-- FLUIDOTERAPIA --}}
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">FLUIDOTERAPIA</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addFluidoTerapia" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddFluidoTerapia" style="display:none">
                                    <div class="d-flex">
                                      <div class="col-md-12">
                                          <div class="row">
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label>DEXCTROSA:</label>
                                                  <input type="text" class="form-control form-control-sm" >
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label >SANGRE:</label>
                                                  <input type="text" class="form-control form-control-sm">
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                <label >POLIGELINA:</label>
                                                <input type="text" class="form-control form-control-sm">
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label>NACI:</label>
                                                <input type="text" class="form-control form-control-sm" >
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label >PLASMA:</label>
                                                <input type="text" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>
                              {{-- METODO DE ANESTESIA --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">METODO DE ANESTESIA</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addMetodoAnestesia" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                <fieldset id="fsaddMetodoAnestesia" style="display:none">
                                  <div class="col-md-6">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <label for="abierto"><b>GENERAL</b></label>
                                        <div class="custom-form-group">
                                          <label for="abierto">ABIERTO:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">SEMI ABIERTO:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">CERRADO:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">SEMI CERRADO:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">BALANCEADO:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">TIVA:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <label for="abierto"> <b>REGIONAL</b></label>
                                        <div class="custom-form-group">
                                          <label for="abierto">RAQUIDEA:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">EPIDURALCAUDAL:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">BLOQUEO:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">OTRO:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>--}}
                              {{-- TECNICA DE ANESTESIA GENERAL --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">TECNICA DE ANESTESIA GENERAL</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addTecnicaAnestesia" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                <fieldset id="fsaddTecnicaAnestesia" style="display:none">
                                  <div class="col-md-4">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <label for="abierto"><b>RESP. CONTROLADA</b></label>
                                        <div class="custom-form-group">
                                          <label for="abierto">INTUB. ORAL:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">FACIL:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">C. MAC:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">BONSFILS:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">FIBROSCOD:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <br>
                                        <div class="custom-form-group">
                                          <label for="abierto">TUBO NRO:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <br>
                                        <div class="custom-form-group">
                                          <label for="abierto">INTUB. NASAL:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">FACIL:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">A CLEGAS:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">MAGIL:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <br><br><br>
                                        <div class="custom-form-group">
                                          <label for="abierto">TUBO ANILLADO:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <br>
                                       
                                        <div class="custom-form-group">
                                          <label for="abierto">MASCARA:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">AIRE CC:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        <div class="custom-form-group">
                                          <label for="abierto">BLOQ. LARINGEO:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                        
                                        <br><br><br><br>
                                        <div class="custom-form-group">
                                          <label for="abierto">LARINGOS.<br>HOJA NRO.</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>--}}
                              {{-- OBSERVACIONES EN INDUCCION --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">OBSERVACIONES EN INDUCCIÓN</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addObservacionesInduccion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddObservacionesInduccion" style="display:none">
                                    <div class="col-md-4">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group">
                                            <label for="abierto">SATISFACTORIA:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">NRO SATISFACTORIA:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">ASPIRACIÓN:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group">
                                            <label for="abierto">EXCIT</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">NAUSEAS:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">VOMITOS:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group">
                                            <label for="abierto">LARINGOESPASMO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                         <div class="custom-form-group">
                                            <label for="abierto">TOS:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                         <div class="custom-form-group">
                                            <label for="abierto">CIANOSIS:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>--}}
                             
                              {{-- TECNICA DE ANESTESIA EPIDURAL/RAQUIDEA/COMBINADA/CAUDAL/BLOQUEO --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">TECNICA DE ENESTESIA EPIDURAL/RAQUIDEA/COMBINADA/CAUDAL/BLOQUEO</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addTecnicaAnestesiaERCCB" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                <fieldset id="fsaddTecnicaAnestesiaERCCB" style="display:none">
                                  <div class="row">
                                    <div class="col-md-2">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group form-group-sm">
                                            <label for="abierto">POSICIÓN:</label>
                                           <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group form-group-sm">
                                            <label for="abierto">PUNCIÓN:</label>
                                           <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group form-group-sm">
                                            <label for="abierto">NIVEL:</label>
                                         <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-2">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group">
                                            <label for="abierto">SIMPLE:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">CONTINUA:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">VECES:</label>
                                          <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">AGUJA NRO</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="row">
                                            <div class="col-md-12">
                                              <div class="custom-form-group form-group-sm">
                                                <label for="abierto">LIDOCAINA:</label>
                                                <input type="text" id="abierto" class="form-control form-control-sm" style="width: 200px;">
                                              </div>
                                              <div class="custom-form-group form-group-sm">
                                                <label>VOL:</label>
                                                <input type="text" id="abierto" class="form-control form-control-sm" style="width: 70px;">
                                                <label>MLGR:</label>
                                                <input type="text" id="abierto" class="form-control form-control-sm" style="width: 70px;">
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="row">
                                            <div class="col-md-12">
                                              <div class="custom-form-group form-group-sm">
                                                <label for="abierto">BUPIVACAINA:</label>
                                                <input type="text" id="abierto" class="form-control form-control-sm" style="width: 200px;">
                                              </div>
                                              <div class="custom-form-group form-group-sm">
                                                <label>VOL:</label>
                                                <input type="text" id="abierto" class="form-control form-control-sm" style="width: 70px;">
                                                <label>MLGR:</label>
                                                <input type="text" id="abierto" class="form-control form-control-sm" style="width: 70px;">
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group form-group-sm">
                                            <label for="abierto">OTROS:</label>
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    
                                  </div>
                                  <div class="row">
                                    <div class="col-md-4">
                                      
                                    </div>
                                    <div class="col-md-8">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group form-group-sm">
                                            <label for="abierto">BLOQUEO:</label>
                                           <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    
                                  </div>
                                </fieldset>
                              </div>--}}
                              {{-- POSICIÓN DE PACIENTE --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">POSICIÓN DE PACIENTE</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addPosicionPaciente" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddPosicionPaciente" style="display:none">
                                    <div class="col-md-3">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group">
                                            <label for="abierto">DESC. DORSAL:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">TRENDELENBURG:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">TRENDEL INVERTIDO:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">LITOTOMIA:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group">
                                            <label for="abierto">DESC. VENTRAL</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">KRASKE:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">LAMINECTOMIA:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">CREANEAOOTOMIA:</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group">
                                            <label for="abierto">SIDE IZQ.</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                         <div class="custom-form-group">
                                            <label for="abierto">SIDE DER.</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group">
                                            <label for="abierto">SIMS IZQ.</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                         <div class="custom-form-group">
                                            <label for="abierto">SIMS DER.</label>
                                            <input type="checkbox" id="abierto" class="form-check-input">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>--}}
                              {{-- QUIROFANO --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">QUIROFANO</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addQuirofano" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddQuirofano" style="display:none">
                                    <div class="d-flex">
                                      <div class="col-md-12">
                                        <div class="row">
                                          <div class="col-md-4 d-flex align-items-center">
                                              <label>QUIROFANO NRO:</label>
                                              <input type="text" class="form-control" >
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                          <div class="row">
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label>DIAG. PRE. OP:</label>
                                                  <input type="text" class="form-control" >
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                  <label >OP. INDICADA:</label>
                                                  <input type="text" class="form-control">
                                              </div>
                                              <div class="col-md-4 d-flex align-items-center">
                                                <label >CIRUJANO:</label>
                                                <input type="text" class="form-control">
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label>DIAG. POST. OP:</label>
                                                <input type="text" class="form-control" >
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label >OP. REALIZADA:</label>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center">
                                              <label >ANESTESIOLOGO:</label>
                                              <input type="text" class="form-control">
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label>PACIENTE:</label>
                                                <input type="text" class="form-control" >
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center">
                                                <label >EDAD:</label>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center">
                                              <label >SEXO:</label>
                                              <input type="text" class="form-control">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center">
                                              <label >CIP:</label>
                                              <input type="text" class="form-control">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center">
                                              <label >HC:</label>
                                              <input type="text" class="form-control">
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>--}}
                              {{-- COMPLICACIONES INTRA OPERATORIO --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">COMPLICACIONES INTRA OPERATORIO</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addComplicacionesIntraoperatorio" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddComplicacionesIntraoperatorio" style="display:none">
                                    <div class="col-md-3">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group">
                                            <label for="abierto">HIPERTENSIÓN</label>
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">HIPOTENSIÓN:</label>
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">TAQUICARDIA:</label>
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">ARRITMIA:</label>
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">BLOUEO ALTO:</label>
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">BLOUEO TOTAL:</label>
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <label for="abierto">ASPIRACIÓN:</label>
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="custom-form-group">
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                          <div class="custom-form-group">
                                            <input type="text" id="abierto" class="form-control form-control-sm">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>--}}
                              {{-- ANALGESICOS / OTROS --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">ANALGESICOS / OTROS</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addAnalgesicosOtros" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddAnalgesicosOtros" style="display:none">
                                    <div class="d-flex">
                                      <div class="col-md-12">
                                          <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                  <textarea name="" id="" cols="5" rows="5" class="form-control form-control-sm"></textarea>
                                                </div>
                                            </div>
                                          </div>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>--}}
                              {{-- OBSERVACIONES --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">OBSERVACIONES</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addObservaciones" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddObservaciones" style="display:none">
                                    <div class="d-flex">
                                      <div class="col-md-12">
                                          <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                  <textarea name="" id="" cols="5" rows="2" class="form-control form-control-sm"></textarea>
                                                </div>
                                            </div>
                                          </div>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>-}}
                              {{-- BALANCE HIDRICO --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <label>BALANCE HIDRICO</label>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addBalanceHidrico" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddBalanceHidrico" style="display:none">
                                    <div class="d-flex">
                                      <div class="col-md-12"><br>
                                        <table class="table table-bordered table-striped">
                                          <thead class="thead-dark">
                                              <tr>
                                                  <th style="font-size: 14;">EGRESOS</th>
                                                  <th>SOP</th>
                                                  <th>SOP</th>
                                                  <th>SOP</th>
                                                  <th>SOP</th>
                                                  <th>SOP</th>
                                                  <th>SOP</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <!-- 11 filas de ejemplo -->
                                              <tr>
                                                  <td>HORA</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>AYUNO</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>RE. BASAL</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>PER. INSENSIBLE</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>SANGRADO</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>DEB. URINARIO</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>SNG</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>3ER ESPACIO</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>OTROS</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>TOTAL</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                          </tbody>
                                        </table><br>
                                        <table class="table table-bordered table-striped">
                                          <thead class="thead-dark">
                                              <tr>
                                                  <th style="font-size: 14;">INGRESOS</th>
                                                  <th>SOP</th>
                                                  <th>SOP</th>
                                                  <th>SOP</th>
                                                  <th>SOP</th>
                                                  <th>SOP</th>
                                                  <th>SOP</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <!-- 11 filas de ejemplo -->
                                              <tr>
                                                  <td>CINA 9%</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>DEXT. 5%</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>COLOIDE</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>PAQ. GLOBULAR</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>PLASMA</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>PLAQUETAS</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>CRIOPRECIPITADO</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>OTROS</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                              <tr>
                                                  <td>TOTAL</td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                  <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                          </tbody>
                                        </table><br>
                                        <table class="table table-bordered">
                                          <tr>
                                              <td style="font-size: 14; width:10.5%;">BALANCE</td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                          </tr>
                                      </table>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>--}}
                              {{-- INGRESO URPA --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">INGRESO URPA</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addIngresoUrpa" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddIngresoUrpa" style="display:none">
                                    <div class="row">
                                      <div class="col-md-3"><br>
                                        <div class="form-group">
                                            <label>FECHA INGRESO:</label>
                                            <input type="date" class="form-control form-control-sm" >
                                        </div>
                                        <div class="form-group">
                                            <label>HORA INGRESO:</label>
                                            <input type="time" class="form-control form-control-sm" >
                                        </div>
                                        <div class="form-group">
                                          <label>CAMA Nª:</label>
                                          <input type="number" class="form-control form-control-sm" >
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                              <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">SIGNOS SÍNTOMAS</th>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">PALIDEZ</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">CLANOSIS</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">RUBOR</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">RASH CUTÁNEO</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">DIAFORESIS</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">TOS</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">BRONCOESPASMO</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">EXITACIÓN</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">HIPERTERMIA</td>
                                              <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div class="col-md-7">
                                        <br>
                                        <div class="row">
                                          <div class="col-md-3">
                                            <table class="table" style="font-size: 12px; width: 100%;">
                                              <tr>
                                                <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">VÍA AEREA</th>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">LIBRE</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">T. ENDOTRAQUEAL</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">T. OTRO-FARING</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">TRAQUEOSTOMIA</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                            </table>

                                          
                                          </div>
                                          <div class="col-md-3">
                                            <table class="table" style="font-size: 12px; width: 100%;">
                                              <tr>
                                                <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">APOY. VENTILAT.</th>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">VENT. MEC.</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">MASCARILLA</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">VENTURI</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">TUBO EN T.</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                            </table>
                                          </div>
                                          <div class="col-md-3">
                                            <table class="table" style="font-size: 12px; width: 100%;">
                                              <tr>
                                                <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">CATÉTERES.</th>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">VIA PERIF.</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">VIA CENTRAL</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">LINEA ART.</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">CAT. EPIDURI.</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                            </table>
                                          </div>
                                          <div class="col-md-3">
                                            <table class="table" style="font-size: 12px; width: 100%;">
                                              <tr>
                                                <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2"></th>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">S. NASOG</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">S. VESICAL</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">DRENAJES</td>
                                                <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                              </tr>
                                            </table>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-12">
                                            <table class="table" style="font-size: 12px; width: 100%;">
                                              <tr>
                                                <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="10">FUNCIONES VITALES</th>
                                              </tr>
                                              <tr>
                                                <td style="padding: 5px;">PA:</td>
                                                <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                <td style="padding: 5px;">FC:</td>
                                                <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                <td style="padding: 5px;">FR:</td>
                                                <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                <td style="padding: 5px;">SAT 02:</td>
                                                <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                                <td style="padding: 5px;">Tª</td>
                                                <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                              </tr>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                      <div class="col-md-3">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                              <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="4">SCORE ALDERETE</th>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">PARAMETROS</td>
                                              <td style="padding: 5px;">0</td>
                                              <td style="padding: 5px;">1</td>
                                              <td style="padding: 5px;">2</td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">ACTIV. MUSCULAR</td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">RESPIRACIÓN</td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">CIRCULACIÓN (PA)</td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">CONCIENCIA (PA)</td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">COLOR / SET 02</td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;"></td>
                                            <td style="padding: 5px;"></td>
                                            <td style="padding: 5px;"><b>TOTAL</b></td>
                                            <td style="padding: 5px;"></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div class="col-md-5">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                            <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">SCORE ANDERSEN - DOLOR CRITERIOS</th>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;"></td>
                                              <td style="padding: 5px;"><b>PUNTOS</b></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">SIN DOLOR AL REPOSO Y DOLOR LIGERO AL MOVIMIENTO O TOS</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">DOLOR LIGERO EN REPOSO Y MODERADO CON EL MOV. O TOS</td>
                                            <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">DOLOR MOD. EN REPOSO Y SEVERO CON EL MOV. O TOS</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">DOLOR SEVERO EN REPOSO Y EXTREMO CON EL MOV. O TOS</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">DOLOR INTENSO AL REPOSO</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div class="col-md-2">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                            <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">SCORE BROMAGE BLOQUEO MOTOR</th>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">COMPLETO</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">CASI COMPLETO</td>
                                            <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">PARCIAL</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">NULO</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div class="col-md-2">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                            <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">GLASGOW</th>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">AO</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">RV</td>
                                            <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">RM</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;"></td>
                                            <td style="padding: 5px;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">TUBO</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                        </table>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <label>INDICACIONES Y TERAPIA DE DOLOR / OBSERVACIONES</label>
                                        <textarea name="" id="" cols="5" rows="5" class="form-control form-control-sm"></textarea>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>--}}
                              {{-- condicion de alta --}}
                              {{--<div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading2">
                                  <div class="form-group">
                                    <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                                      <div class="panel-title">CONDICION ALTA</div>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addCondicionAlta" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                
                                  <fieldset id="fsaddCondicionAlta" style="display:none">
                                    <div class="row">
                                      <div class="col-md-3">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                              <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="4">SCORE ALDERETE</th>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;">PARAMETROS</td>
                                              <td style="padding: 5px;">0</td>
                                              <td style="padding: 5px;">1</td>
                                              <td style="padding: 5px;">2</td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">ACTIV. MUSCULAR</td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">RESPIRACIÓN</td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">CIRCULACIÓN (PA)</td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">CONCIENCIA (PA)</td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">COLOR / SET 02</td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                            <td style="padding: 5px;"><input type="checkbox" id="abierto" class="form-check-input"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;"></td>
                                            <td style="padding: 5px;"></td>
                                            <td style="padding: 5px;"><b>TOTAL</b></td>
                                            <td style="padding: 5px;"></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div class="col-md-5">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                            <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">SCORE ANDERSEN - DOLOR CRITERIOS</th>
                                          </tr>
                                          <tr>
                                              <td style="padding: 5px;"></td>
                                              <td style="padding: 5px;"><b>PUNTOS</b></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">SIN DOLOR AL REPOSO Y DOLOR LIGERO AL MOVIMIENTO O TOS</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">DOLOR LIGERO EN REPOSO Y MODERADO CON EL MOV. O TOS</td>
                                            <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">DOLOR MOD. EN REPOSO Y SEVERO CON EL MOV. O TOS</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">DOLOR SEVERO EN REPOSO Y EXTREMO CON EL MOV. O TOS</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">DOLOR INTENSO AL REPOSO</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div class="col-md-2">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                            <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">SCORE BROMAGE BLOQUEO MOTOR</th>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">COMPLETO</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">CASI COMPLETO</td>
                                            <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">PARCIAL</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">NULO</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div class="col-md-2">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                            <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="2">GLASGOW</th>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">AO</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">RV</td>
                                            <td style="padding: 5px;"><input type="number"  style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">RM</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;"></td>
                                            <td style="padding: 5px;"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">TUBO</td>
                                            <td style="padding: 5px;"><input type="number" style="width: 40%; height: 24px; font-size: 12px; margin: 0 auto;"></td>
                                          </tr>
                                        </table>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                            <th style="font-size: 14px; padding: 5px; text-align: center;" colspan="10">FUNCIONES VITALES</th>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">PA:</td>
                                            <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                            <td style="padding: 5px;">FC:</td>
                                            <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                            <td style="padding: 5px;">FR:</td>
                                            <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                            <td style="padding: 5px;">SAT 02:</td>
                                            <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                            <td style="padding: 5px;">Tª</td>
                                            <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                            <td style="padding: 5px;">EXTUBADO</td>
                                            <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                            <td style="padding: 5px;">RETIRO DE CATETER EPIDRURAL</td>
                                            <td style="padding: 5px;"><input type="text" id="abierto" class="form-control form-control-sm"></td>
                                          </tr>
                                        </table>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-8">
                                        <label>INDICACIONES DE ALTA URPA / OBSERVACIONES</label>
                                        <textarea name="" id="" cols="5" rows="2" class="form-control form-control-sm"></textarea>
                                      </div>
                                      <div class="col-md-4">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                         
                                          <tr>
                                              <td style="padding: 5px;">FECHA EGRESO:</td>
                                              <td style="padding: 5px;"><input type="date" class="form-control form-control-sm"></td>
                                          </tr>
                                          <tr>
                                            <td style="padding: 5px;">HORA EGRESO:</td>
                                            <td style="padding: 5px;"><input type="time" class="form-control form-control-sm"></td>
                                        </tr>
                                          <tr>
                                            <td style="padding: 5px;">PASA A:</td>
                                            <td style="padding: 5px;"><input type="text" class="form-control form-control-sm" style="width: 70%;"></td>
                                          </tr>
                                        </table>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <table class="table" style="font-size: 12px; width: 100%;">
                                          <tr>
                                            <td style="padding: 5px;">PACIENTE:</td>
                                            <td style="padding: 5px;"><input type="text" class="form-control form-control-sm" style="width: 70%;"></td>
                                            <td style="padding: 5px;">EDAD:</td>
                                            <td style="padding: 5px;"><input type="text" class="form-control form-control-sm"></td>
                                            <td style="padding: 5px;">HC:</td>
                                            <td style="padding: 5px;"><input type="text" class="form-control form-control-sm"></td>
                                          </tr>
                                        </table>
                                      </div>
                                    </div>
                                  </fieldset>
                              </div>--}}

                            </fieldset>
                      </div><br></div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" id="registrarAnestesia"
                    value="create">Guardar</button>
              </div>
            </form>
        </div>
    </div>
</div>            

<!-- Modal NUEVO BALANCE HIDRICO -->
<div class="modal fade" id="modalNuevoBalanceHidrico" tabindex="-1" role="dialog" aria-labelledby="modalAnestesiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b>BALANCE HIDRICO</b>
            </div>
            <form id="FormularioAnestesia" name="FormularioAnestesia" enctype="multipart/form-data"  method="POST">
              <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
              <div class="modal-body">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">EGRESO:</label>
                            </div>
                            <div class="col-lg-6">
                                <label for="ciudad">SOAP</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">HORA:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="time" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">AYUNO:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">REQ. BASAL:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">PER INSENSIBLES:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">SANGRADO:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">DEB. URUBARIO:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">SNG:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">3ER ESPACIO:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">OTROS:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">TOTAL:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">INGRESOS:</label>
                            </div>
                            <div class="col-lg-6">
                                <label for="ciudad">SOAP</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">HORA:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="time" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">AYUNO:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">REQ. BASAL:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">PER INSENSIBLES:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">SANGRADO:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">DEB. URUBARIO:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">SNG:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">3ER ESPACIO:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">OTROS:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="ciudad">TOTAL:</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="ciudad" >
                            </div>
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" id="registrarAnestesia"
                    value="create">Guardar</button>
              </div>
            </form>
        </div>
    </div>
</div>            
