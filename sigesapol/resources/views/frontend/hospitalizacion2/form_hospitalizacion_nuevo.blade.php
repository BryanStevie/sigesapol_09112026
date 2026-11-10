@extends('frontend.layouts.master')
@section('renderjs')
<script src='funciones/function.js'></script>
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script src='datePicker/js/bootstrap-datepicker.js' charset="utf-8"></script>
<link href='datePicker/css/bootstrap-datepicker.min.css' rel='stylesheet' />
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
  $consul = $("#frmHospitalizacion").find("#id_consultorio");
    $consul.select2({
        width: 'resolve' // need to override the changed default
    });
    
    $tur = $("#frmHospitalizacion").find("#turno");
    $tur.select2({
        width: 'resolve' // need to override the changed default
    });
    
    $orig = $("#frmHospitalizacion").find("#origen");
    $orig.select2({
        width: 'resolve' // need to override the changed default
    });

    $id_servicio = $("#frmHospitalizacion").find("#id_servicio");
    $id_servicio.select2({
        width: 'resolve' // need to override the changed default
    });

    $sala = $("#frmHospitalizacion").find("#id_sala");
    $sala.select2({
        width: 'resolve' // need to override the changed default
    });
    
    $medicos = $('#frmHospitalizacion').find("#id_medico")
    $medicos.select2({
        width: 'resolve' // need to override the changed default
    });

    $cama = $("#frmHospitalizacion").find("#id_cama");
    $cama.select2({
        width: 'resolve' // need to override the changed default
    });

  $(document).ready(function() {
    $('.fecha').datepicker({
        language: "es",
        format: "dd/mm/yyyy",
        autoclose: true
    });
  });

   function limpiar_paciente(){
    //alert("limpiar");
    $('#beneficiario').val("");
    $('#tipo_doc_ident').val("");
    $('#nro_doc_ident').val("");
    $('#nombre').val("");
    $('#paterno').val("");
    $('#materno').val("");
    $('#sexo').val("");
    $('#fecha_nac').val("");
    $('#domicilio').val('');
  }

  

function cambiar_tipo_atencio_origen(){
  var origen=  $("#origen").val();

  $.getJSON("/listar_consultorios_ajax/"+origen, function(json){  
        document.getElementById("id_servicio").innerHTML = "";
        $('#id_servicio').append($('<option>').text("Seleccione").attr('value', ""));   
        //$('#id_servicio').append($('<option>').text("OTRO").attr('value', "OTRO"));  

        $.each(json, function(i, obj){      
           var len= obj.length;
           for(j=0;j<len;j++){
            $('#id_servicio').append($('<option>').text( obj[j].nombre).attr('value', obj[j].id));  
           }                        
                                 
        });           
      });
}

    $(document).ready(function() {

      $("#selectSexo").hide();
      $("#fecha_nacimiento_editable").hide();

       $("#dni_beneficiario").keypress(function (e) { 
          if (e.which == 13) {  
            getReniecByDni();
          }
        });

        $("#dni_medico").keypress(function (e) { 
          if (e.which == 13) {  
            getMedicoByDni();
          }
        });     

       $("#sexo").change(function(e) {
          var sexo= $('#sexo').val();
          if(sexo=="F"){
            $('#frecuencia_fetal').show();
          }else{
            $('#frecuencia_fetal').hide(); 
          }
       });

      /* $("#nro_doc_ident").change(function (e) {             
            ValidarDni();          
        }); */

         $('#fecha_nac2').change(function(){
          //var f = new Date();
            //var fechaFinIni = f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate();

            var fechaIni = $('#fecha_nac2').val();
            f1 = fechaIni.split("/");
            f3 = new Date();
            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
            
            if (f1 > f3) {
                 alert('La Fecha de Nacimiento debe ser menor o igual a la fecha Actual.');
                 $('#fecha_nac2').val("");
                 $('#fecha_nac2').focus();
            }      
           
            
        });

   });  


  function getMedicoByDni() {
    var tipodoc_beneficiario = $("#tipodoc_medico").val();
    var dni_medico = $("#dni_medico").val();

    if(dni_medico==""){
       alert("Falta ingresar el Documento del medico");
       $('#dni_beneficiario').focus();
       return 0;
    }
    
    if (tipodoc_medico == "1") {
      if (dni_medico.length != 8) {
        bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>")
        $('#dni_beneficiario').focus();
        return 0;
      }
    }

     //$('#content').html('<div class="loading"><img src="images/loader.gif" alt="loading" /><br/>Espere un momento, por favor...</div>');
     $('#btnBuscarMedico').prop('disabled', true);

    var medico_no_existe=false;
    $.ajax({
      url: '/buscar_medico_ipress/' + dni_medico, 
      dataType: "json",  
      success: function(result) { //alert(result.id);
        if(result!=null){
            $('#nro_doc_medico').val(result.dni);
            $('#nombre_medico').val(result.nombre);
            $('#paterno_medico').val(result.paterno);
            $('#materno_medico').val(result.materno);
            $('#cmp_medico').val(result.colegiatura);
            $('#especialidad_medico').val(result.especializacion);
        }else{
            //alert("Paciente NO encontrado");
            medico_no_existe=true;
            bootbox.alert("Ha ocurrido un error al consultar el N° Documento del medico, No existe.");
        }
        //
        $('#content').html('');
        $('#btnBuscarMedico').prop('disabled', false);
      },
      complete : function(xhr, status) {
        $('#btnBuscarMedico').prop('disabled', false);
      }
    });    

  }

  function getReniecByDni() {
    limpiar_paciente();
    var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    var dni_paciente = $("#dni_beneficiario").val();

    if(dni_paciente==""){
       alert("Falta ingresar el Documento del paciente");
       $('#dni_beneficiario').focus();
       return 0;
    }
    
    if (tipodoc_beneficiario == "1") {
      if (dni_paciente.length != 8) {
        bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>")
        $('#dni_beneficiario').focus();
        return 0;
      }
    }

     $('#content').html('<div class="loading"><img src="images/loader.gif" alt="loading" /><br/>Espere un momento, por favor...</div>');
     $('#btnBuscar').prop('disabled', true);

    var paciente_no_existe=false;
    $.ajax({
      //url: '/validar_documento/' + dni_paciente,
      url: "{{ route('obtener_datos_paciente_ajax') }}",         
      type: "GET",
      dataType: "JSON",
      data: {           
        dni: dni_paciente,
        tipo_documento:tipodoc_beneficiario      
      },  
      success: function(result) { //alert(result.id);
         
        if(result!=null){
          if (result[0] != undefined) { 
             paciente_no_existe=true;

          }else if(result.id>0 && result.id_tipo_doc_ident>0){ //tabla asegurados
            paciente = result.paterno + ' ' + result.materno + ', ' + result.nombre;
            $('#beneficiario').val(paciente);            
            $('#id_tipo_doc_ident').val(tipodoc_beneficiario);
            $('#tipo_doc_ident').val(result.tipo_doc_ident);
            $('#nro_doc_ident').val(result.nro_doc_ident);
            $('#nombre').val(result.nombre);
            $('#paterno').val(result.paterno);
            $('#materno').val(result.materno);
            $('#sexo').val(result.sexo);
            $('#fecha_nac').val(result.fecha_nac);
            $('#domicilio').val(result.domicilio);
            $('#nro_historia').val(result.nro_historia);

          }else if (tipodoc_beneficiario=="1" && result.codigoMensaje == '00') { //reniec
            $('#tipo_registro').val('1');
            paciente = result.paterno + ' ' + result.materno + ', ' + result.nombres;
            $('#beneficiario').val(paciente);            
            
            $('#id_tipo_doc_ident').val(tipodoc_beneficiario);
            $('#tipo_doc_ident').val("DNI");
            $('#nro_doc_ident').val(result.dni);
            $('#nombre').val(result.nombres);
            $('#paterno').val(result.paterno);
            $('#materno').val(result.materno);
            $('#sexo').val(result.id_sexo);
            $('#fecha_nac').val(result.vfecha_nac);
            $('#domicilio').val(result.direccionDomicilio);

          }else if(result.nompaisdelafiliado!=""){ //tabla Saludpol
            $('#tipo_registro').val('1');
            paciente = result.apepatafiliado + ' ' + result.apematafiliado + ', ' + result.nomafiliado;
            $('#beneficiario').val(paciente);      
            
            $('#id_tipo_doc_ident').val(tipodoc_beneficiario);
            $('#tipo_doc_ident').val(result.nomtipdocafiliado);
            $('#nro_doc_ident').val(result.nrodocafiliado);
            $('#nombre').val(result.nomafiliado);
            $('#paterno').val(result.apepatafiliado);
            $('#materno').val(result.apematafiliado);
            $('#sexo').val(result.nomsexo);
            $('#fecha_nac').val(result.fecnacafiliado);
            $('#domicilio').val('');

          }else{
            //alert("Paciente NO encontrado");
            paciente_no_existe=true;
          }
        } else {
            //alert("Paciente NO encontrado");
            paciente_no_existe=true;
        }

        if(paciente_no_existe==true){ //habilitar para nuevo registro
          bootbox.confirm({
            message: "Paciente NO encontrado. <b>¿Desea registrarlo manualmente?</b>",
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
              if (result==true) {
                //habilitar campos para registro
                var combo = document.getElementById("tipodoc_beneficiario_bus");
                var tipo_doc_ident = combo.options[combo.selectedIndex].text;
                document.getElementById("tipo_doc_ident").value= tipo_doc_ident;

                var dni_beneficiario = $("#dni_beneficiario").val();           
                $("#nro_doc_ident").val(dni_beneficiario);
                llenar_manual_datos_paciente(true);
              } else {
                document.getElementById("dni_beneficiario").focus(); 
              }
            }
          }); 

        }else{ //paciente existe
          llenar_manual_datos_paciente(false);
        }     
        //
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);

      },
      error: function(xhr, status) {
        bootbox.alert("Ha ocurrido un error al consultar el N° Documento del paciente.");
      },
      complete : function(xhr, status) {
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
      }
    });    

  }

  function getGestacionesByPacienteDni() {
    var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    var dni_paciente = $("#dni_beneficiario").val();
    $.ajax({
      url: "{{ route('obtener_gestaciones_by_dni_paciente') }}",         
      type: "GET",
      dataType: "JSON",
      data: {           
        dni: dni_paciente,
        tipo_documento:tipodoc_beneficiario      
      },  
      success: function(result) {
         console.log({result})
    }});    
  }

  function llenar_manual_datos_paciente(valor){
  if(valor==true){    
    $('#tipo_registro').val('0');
        //$("#nro_doc_ident").val("").attr("readonly", false);
        $("#paterno").val("").attr("readonly", false);
        $("#materno").val("").attr("readonly", false);
        $("#nombre").val("").attr("readonly", false);  
        // $("#sexo").val("").attr("readonly", false);  
        // $("#fecha_nac").val("").attr("readonly", false);  
        // $("#sexo").val("").attr("disabled", false); 
        $("#sexo").hide();
        $("#selectSexo").val("");
        $("#selectSexo").show();
        $("#fecha_nac").hide();
        $("#fecha_nac2").val("");
        $("#fecha_nacimiento_editable").show();
        //$("#dni_beneficiario").val("").attr("readonly", true);
        document.getElementById("paterno").focus();

  }else{
    $("#nro_doc_ident").attr("readonly", true);
    $("#paterno").attr("readonly", true);
    $("#materno").attr("readonly", true);
    $("#nombre").attr("readonly", true);  
    // $("#sexo").attr("readonly", true);  
    $("#fecha_nac").attr("readonly", true);
    $("#sexo").show();
    $("#selectSexo").hide();
    $("#fecha_nac").show();
    $("#fecha_nacimiento_editable").hide();
    $("#dni_beneficiario").attr("readonly", false); 
    document.getElementById("dni_beneficiario").focus(); 

  }  

}


  
/*
  function guardarTriaje(){
     var dni_beneficiario = document.frmHospitalizacion.dni_beneficiario.value;
     var beneficiario = document.frmHospitalizacion.beneficiario.value;

     var nro_doc_ident = document.frmHospitalizacion.nro_doc_ident.value;
     var paterno = document.frmHospitalizacion.paterno.value;
     var materno = document.frmHospitalizacion.materno.value;
     var nombre = document.frmHospitalizacion.nombre.value;
     var sexo = document.frmHospitalizacion.sexo.value;
     var fecha_nac = document.frmHospitalizacion.fecha_nac.value;
     var fecha_nac2 = document.frmHospitalizacion.fecha_nac2.value;
     var tipo_registro = document.frmHospitalizacion.tipo_registro.value;
     var selectSexo = document.frmHospitalizacion.selectSexo.value;

     var fechax= (tipo_registro == "1") ? fecha_nac.split("/") : fecha_nac2.split("/");
     var cant_array_fecha= fechax.length;

     var id_prioridad = document.frmHospitalizacion.id_prioridad.value;
     var id_topico = document.frmHospitalizacion.id_topico.value;
        
     if($('#paterno').attr('readonly') == 'readonly'){
        if(dni_beneficiario==""){
         alert("Falta buscar un paciente");
         $('#dni_beneficiario').focus();
         return 0;

       }else if(beneficiario==""){
         alert("Falta buscar un paciente");
         $('#dni_beneficiario').focus();
         return 0;
       } 

     }else{
      //alert("false");
         if(nro_doc_ident==""){
           alert("Falta ingresar el Documento del paciente");
           $('#nro_doc_ident').focus();
           return 0;

         }else if(paterno==""){
           alert("Falta ingresar el apellido paterno del paciente");
           $('#paterno').focus();
           return 0;

         }else if(materno==""){
           alert("Falta ingresar el apellido materno del paciente");
           $('#materno').focus();
           return 0;

         }else if(nombre==""){
           alert("Falta ingresar el/los nombres del paciente");
           $('#nombre').focus();
           return 0;
           
         }else if(tipo_registro=="0"){
           if (selectSexo=="") {
            alert("Falta seleccionar el sexo del paciente");
            $('#selectSexo').focus();
            return 0;
           }
           
         }
         
         if(tipo_registro == "1"){
          if (fecha_nac=="") {
            alert("Falta ingresar la fecha de nacimiento del paciente");
            $('#fecha_nac').focus();
            return 0;
          } else if(cant_array_fecha!=3 || fecha_nac.length!=10){
            alert("La fecha de nacimiento NO tiene un formato válido");
            $('#fecha_nac').focus();
            return 0;
          }
           
         }else if(tipo_registro == "0"){
          if (fecha_nac2=="") {
          alert("Falta ingresar la fecha de nacimiento del paciente");
          $('#fecha_nac2').focus();
          return 0;
          } else if(cant_array_fecha!=3 || fecha_nac2.length!=10){
            alert("La fecha de nacimiento NO tiene un formato válido");
            $('#fecha_nac2').focus();
            return 0;
          }
        }
     }

    if(id_prioridad=="" || id_prioridad=="0" ){
       alert("Falta seleccionar la Prioridad");
       $('#id_prioridad').focus();
       return 0;

     }else if(id_topico=="" || id_topico=="0"){
       alert("Falta seleccionar el Tópico");
       $('#id_topico').focus();
       return 0;

     }else{  
      submitFrm();
      return 0;
     }
     
  }
*/
$(document).ready(function () {
      getSala();
      getCama();
      getCama2();
        /*var idgrado = "";
        if (idgrado == ""){
            $("#grado").val('NINGUNO').trigger("change");
        } else {
            $("#grado").val(idgrado).trigger("change");
        }*/
 
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

        //
        $("#buscar_dni").keypress(function (e) { 
          //alert("Pulsaste la tecla con codigo: "+e.which);      
          if (e.which == 13) {  
            buscar_persona();
          }
        });//FIN dni_titular  
    });


  function validate_otros(){
         
        if ($('#frmHospitalizacion').find("#fecha_atencion").val() == "") {
            bootbox.alert("Falta ingresar la fecha de ingreso");
            $('#btnReg').prop('disabled', false);
            $('#fecha_atencion').focus();
            return false;
        }
      
        var dni_beneficiario = document.frmHospitalizacion.dni_beneficiario.value;
         var beneficiario = document.frmHospitalizacion.paciente_beneficiario.value;

         var nro_doc_ident = document.frmHospitalizacion.buscar_dni.value;
         var paterno = document.frmHospitalizacion.paterno_beneficiario.value;
         var materno = document.frmHospitalizacion.materno_beneficiario.value;
         var nombre = document.frmHospitalizacion.nombre_beneficiario.value;
         var sexo = document.frmHospitalizacion.nomsexo.value;
         var fecha_nac = document.frmHospitalizacion.fecha_nacimiento_beneficiario.value;
         var tipo_beneficiario = document.frmHospitalizacion.tipo_beneficiario.value;

         var fechax= fecha_nac.split("/");
         var cant_array_fecha= fechax.length;

        if(document.getElementById("chk_manual").checked==true){//registro manual
          if(dni_beneficiario==""){
             alert("Falta ingresar el Documento del paciente");
             $('#btnReg').prop('disabled', false);
             $('#dni_beneficiario').focus();
             return 0;

           }else if(paterno==""){
             alert("Falta ingresar el apellido paterno del paciente");
             $('#btnReg').prop('disabled', false);
             $('#paterno_beneficiario').focus();
             return 0;

           }else if(materno==""){
             alert("Falta ingresar el apellido materno del paciente");
             $('#btnReg').prop('disabled', false);
             $('#materno_beneficiario').focus();
             return 0;

           }else if(nombre==""){
             alert("Falta ingresar el/los nombres del paciente");
             $('#btnReg').prop('disabled', false);
             $('#nombre_beneficiario').focus();
             return 0;
             
           }else if(sexo==""){
             alert("Falta seleccionar el sexo del paciente");
             $('#btnReg').prop('disabled', false);
             $('#nomsexo').focus();
             return 0;
             
           }else if(fecha_nac==""){
             alert("Falta ingresar la fecha de nacimiento del paciente");
             $('#btnReg').prop('disabled', false);
             $('#fecha_nacimiento_beneficiario').focus();
             document.getElementById('edadafiliado').value= 0;
             return 0;
             
           } else if(cant_array_fecha!=3 || fecha_nac.length!=10){
             alert("La fecha de nacimiento NO tiene un formato válido");
             $('#btnReg').prop('disabled', false);
             $('#fecha_nacimiento_beneficiario').focus();
             return 0;

           }else if(tipo_beneficiario==""){
             alert("Falta seleccionar el Parentesco");
             $('#btnReg').prop('disabled', false);
             $('#tipo_beneficiario').focus();
             return 0;
           }

           //edad
            var fecha_nac = $('#fecha_nacimiento_beneficiario').val();      
            var edad_afiliado_x = calcularEdad(gfecha(fecha_nac)); //return anos + "|" + meses + "|" + dias;
            var edad_afiliado_array= edad_afiliado_x.split("|");
            if(edad_afiliado_array[0]!=undefined){
              var edad = edad_afiliado_array[0];
            }else  if(edad_afiliado_array[1]!=undefined){
              var edad = edad_afiliado_array[1];
            }else  if(edad_afiliado_array[2]!=undefined){
              var edad = edad_afiliado_array[2];
            }
            document.getElementById('edadafiliado').value= edad;           

       }else{ //busqueda
          if(dni_beneficiario==""){
             alert("Falta buscar un paciente");
             $('#btnReg').prop('disabled', false);
             $('#buscar_dni').focus();
             return 0;

           }else if(paciente_beneficiario==""){
             alert("Falta buscar un paciente");
             $('#btnReg').prop('disabled', false);
             $('#buscar_dni').focus();
             return 0;

           } 
       }

        if(tipo_beneficiario!="CIVIL"){
         var grado= document.frmHospitalizacion.grado.value;         
           if(grado=="SELECCIONE"){
             alert("Falta seleccionar el Grado del Titular");
             $('#btnReg').prop('disabled', false);
             $('#grado').focus();
             return 0;
           }

        } 


        if ($('#frmHospitalizacion').find("#id_consultorio").val() == "") {
            bootbox.alert("Falta seleccionar Consultorio");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmHospitalizacion').find("#turno").val() == "") {
            bootbox.alert("Falta seleccionar el Turno");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        /*if ($('#frmHospitalizacion').find("#tarifa").val() == "") {
            bootbox.alert("Falta seleccionar la Tarifa");           
            $('#btnReg').prop('disabled', false);
            $('#tarifa').focus();
            return false;
        }*/
        if ($('#frmHospitalizacion').find("#id_sala").val() == "") {
            bootbox.alert("Falta seleccionar la Sala");           
            $('#btnReg').prop('disabled', false);
            $('#id_sala').focus();
            return false;
        }
        if ($('#frmHospitalizacion').find("#id_medico").val() == "") {
            bootbox.alert("Falta seleccionar el Medico");
            $('#btnReg').prop('disabled', false);
            return false;
        }    
        
        if ($('#frmHospitalizacion').find("#id_cama").val() == "") {
            bootbox.alert("Falta seleccionar la Cama");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        /*if ($('#frmHospitalizacion').find("#nro_historia").val() == "") {
            bootbox.alert("Falta ingresar la Historia Clínica");
            $('#btnReg').prop('disabled', false);
            return false;
        }*/
        if ($('#frmHospitalizacion').find("#grado").val() == "") {
            bootbox.alert("Falta seleccionar el grado");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmHospitalizacion').find("#id_cama").val() == "") {
            bootbox.alert("Falta ingresar el diagnostico");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frmHospitalizacion').find("#id_diagnostico").val() == "") {
            bootbox.alert("Falta seleccionar el diagnostico (CIE10)");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        
        if ($('#frmHospitalizacion').find("#fecha_expedicion").val() == "") {
            bootbox.alert("Falta seleccionar la fecha de ingreso del paciente");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frmHospitalizacion').find("#id_medico").val() == "") {
            //alert("Falta seleccionar el Profesional de Salud");
            bootbox.alert("Falta seleccionar el Profesional de Salud");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        submitFrm();
        return false;
    };
  function submitFrm() {
    bootbox.confirm({
        message: "¿Seguro de Guardar el Triaje de Emergencia?",
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
            if (result==true) {
                $("#frmHospitalizacion").submit();
            } else {
                $('#btnReg').prop('disabled', false);
            }
        }
    });
  }

function maxlength_doc_usu() {
  $('#dni_beneficiario').val('');
   limpiar_paciente();
   limpiar_signos_vitales();
   limpiar_datos_adicional();

  //llenar texto tipodocumento
  var combo = document.getElementById("tipodoc_beneficiario_bus");
  var tipo_doc_ident = combo.options[combo.selectedIndex].text;

  document.getElementById("tipo_doc_ident").value= tipo_doc_ident;
  document.getElementById("id_tipo_doc_ident").value= combo.value;

  if ($("#tipodoc_beneficiario_bus").val() == "1") {
    $("#dni_beneficiario").attr('maxlength', '8');
    $("#nro_doc_ident").attr('maxlength', '8');

  } else {
    $("#dni_beneficiario").attr('maxlength', '12');
    $("#nro_doc_ident").attr('maxlength', '12');      
  }
  document.getElementById("dni_beneficiario").focus();
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
          $('#id_sala').html(newOption);$("#id_sala").select2({max_selected_options: 4});
        }
      });

       

      var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            //$('#id_medico').html(newOption);
           // $("#id_medico").val('').trigger("change");
            
            if($('#turno').val()=="") return false;
        
      var id_ipress     = $('#id_ipress').val();
      var id_consultorio  = $('#id_consultorio').val();
      var fecha_admision  = $('#fecha_atencion').val();
      
      /*$.ajax({
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
          });*/
      
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



///////////---------GESTACIONES ..




            $('#esta_gestando').on('click', function () {
                var esta_gestando = $('#esta_gestando').attr("aria-pressed");
                $("#frsgetante").hide();
                if(esta_gestando === "false"){
                    $("#frsgetante").show();
                }
                // else {
                //     clearValuesFormGestante();
                //     $("#formPurperio").hide();
                //     $('#tblPuerperio tbody').empty();
                //     $('#addPuerpera').removeClass('active');
                //     $('#addPuerpera').attr('aria-pressed', 'false');
                // }
        });
        $('#fecha_atencion').datepicker();



        
</script>
<style>
  .example .btn-toggle {
            top: 50%;
            transform: translateY(-50%);
        }

        .btn-toggle {
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

        .btn-toggle:focus,
        .btn-toggle.focus,
        .btn-toggle:focus.active,
        .btn-toggle.focus.active {
            outline: none;
        }

        .btn-toggle:before,
        .btn-toggle:after {
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

        .btn-toggle:before {
            content: 'NO';
            left: -4rem;
        }

        .btn-toggle:after {
            content: 'SI';
            right: -4rem;
            opacity: 0.5;
        }

        .btn-toggle>.handle {
            position: absolute;
            top: 0.1875rem;
            left: 0.1875rem;
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 1.125rem;
            background: #fff;
            transition: left 0.25s;
        }

        .btn-toggle.active {
            transition: background-color 0.25s;
        }

        .btn-toggle.active>.handle {
            left: 1.6875rem;
            transition: left 0.25s;
        }

        .btn-toggle.active:before {
            opacity: 0.5;
        }

        .btn-toggle.active:after {
            opacity: 1;
        }

        .btn-toggle.btn-sm:before,
        .btn-toggle.btn-sm:after {
            line-height: -0.5rem;
            color: #fff;
            letter-spacing: 0.75px;
            left: 0.4125rem;
            width: 2.325rem;
        }

        .btn-toggle.btn-sm:before {
            text-align: right;
        }

        .btn-toggle.btn-sm:after {
            text-align: left;
            opacity: 0;
        }

        .btn-toggle.btn-sm.active:before {
            opacity: 0;
        }

        .btn-toggle.btn-sm.active:after {
            opacity: 1;
        }

        .btn-toggle.btn-xs:before,
        .btn-toggle.btn-xs:after {
            display: none;
        }

        .btn-toggle:before,
        .btn-toggle:after {
            color: #6b7381;
        }

        .btn-toggle.active {
            background-color: #29b5a8;
        }

        .btn-toggle.btn-lg {
            margin: 0 5rem;
            padding: 0;
            position: relative;
            border: none;
            height: 2.5rem;
            width: 5rem;
            border-radius: 2.5rem;
        }

        .btn-toggle.btn-lg:focus,
        .btn-toggle.btn-lg.focus,
        .btn-toggle.btn-lg:focus.active,
        .btn-toggle.btn-lg.focus.active {
            outline: none;
        }

        .btn-toggle.btn-lg:before,
        .btn-toggle.btn-lg:after {
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

        .btn-toggle.btn-lg:before {
            content: 'NO';
            left: -5rem;
        }

        .btn-toggle.btn-lg:after {
            content: 'SI';
            right: -5rem;
            opacity: 0.5;
        }

        .btn-toggle.btn-lg>.handle {
            position: absolute;
            top: 0.3125rem;
            left: 0.3125rem;
            width: 1.875rem;
            height: 1.875rem;
            border-radius: 1.875rem;
            background: #fff;
            transition: left 0.25s;
        }

        .btn-toggle.btn-lg.active {
            transition: background-color 0.25s;
        }

        .btn-toggle.btn-lg.active>.handle {
            left: 2.8125rem;
            transition: left 0.25s;
        }

        .btn-toggle.btn-lg.active:before {
            opacity: 0.5;
        }

        .btn-toggle.btn-lg.active:after {
            opacity: 1;
        }

        .btn-toggle.btn-lg.btn-sm:before,
        .btn-toggle.btn-lg.btn-sm:after {
            line-height: 0.5rem;
            color: #fff;
            letter-spacing: 0.75px;
            left: 0.6875rem;
            width: 3.875rem;
        }

        .btn-toggle.btn-lg.btn-sm:before {
            text-align: right;
        }

        .btn-toggle.btn-lg.btn-sm:after {
            text-align: left;
            opacity: 0;
        }

        .btn-toggle.btn-lg.btn-sm.active:before {
            opacity: 0;
        }

        .btn-toggle.btn-lg.btn-sm.active:after {
            opacity: 1;
        }

        .btn-toggle.btn-lg.btn-xs:before,
        .btn-toggle.btn-lg.btn-xs:after {
            display: none;
        }

        .btn-toggle.btn-sm {
            margin: 0 0.5rem;
            padding: 0;
            position: relative;
            border: none;
            height: 1.5rem;
            width: 3rem;
            border-radius: 1.5rem;
        }

        .btn-toggle.btn-sm:focus,
        .btn-toggle.btn-sm.focus,
        .btn-toggle.btn-sm:focus.active,
        .btn-toggle.btn-sm.focus.active {
            outline: none;
        }

        .btn-toggle.btn-sm:before,
        .btn-toggle.btn-sm:after {
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

        .btn-toggle.btn-sm:before {
            content: 'NO';
            left: -0.5rem;
        }

        .btn-toggle.btn-sm:after {
            content: 'SI';
            right: -0.5rem;
            opacity: 0.5;
        }

        .btn-toggle.btn-sm>.handle {
            position: absolute;
            top: 0.1875rem;
            left: 0.1875rem;
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 1.125rem;
            background: #fff;
            transition: left 0.25s;
        }

        .btn-toggle.btn-sm.active {
            transition: background-color 0.25s;
        }

        .btn-toggle.btn-sm.active>.handle {
            left: 1.6875rem;
            transition: left 0.25s;
        }

        .btn-toggle.btn-sm.active:before {
            opacity: 0.5;
        }

        .btn-toggle.btn-sm.active:after {
            opacity: 1;
        }

        .btn-toggle.btn-sm.btn-sm:before,
        .btn-toggle.btn-sm.btn-sm:after {
            line-height: -0.5rem;
            color: #fff;
            letter-spacing: 0.75px;
            left: 0.4125rem;
            width: 2.325rem;
        }

        .btn-toggle.btn-sm.btn-sm:before {
            text-align: right;
        }

        .btn-toggle.btn-sm.btn-sm:after {
            text-align: left;
            opacity: 0;
        }

        .btn-toggle.btn-sm.btn-sm.active:before {
            opacity: 0;
        }

        .btn-toggle.btn-sm.btn-sm.active:after {
            opacity: 1;
        }

        .btn-toggle.btn-sm.btn-xs:before,
        .btn-toggle.btn-sm.btn-xs:after {
            display: none;
        }

        .btn-toggle.btn-xs {
            margin: 0 0;
            padding: 0;
            position: relative;
            border: none;
            height: 1rem;
            width: 2rem;
            border-radius: 1rem;
        }

        .btn-toggle.btn-xs:focus,
        .btn-toggle.btn-xs.focus,
        .btn-toggle.btn-xs:focus.active,
        .btn-toggle.btn-xs.focus.active {
            outline: none;
        }

        .btn-toggle.btn-xs:before,
        .btn-toggle.btn-xs:after {
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

        .btn-toggle.btn-xs:before {
            content: 'NO';
            left: 0;
        }

        .btn-toggle.btn-xs:after {
            content: 'SI';
            right: 0;
            opacity: 0.5;
        }

        .btn-toggle.btn-xs>.handle {
            position: absolute;
            top: 0.125rem;
            left: 0.125rem;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 0.75rem;
            background: #fff;
            transition: left 0.25s;
        }

        .btn-toggle.btn-xs.active {
            transition: background-color 0.25s;
        }

        .btn-toggle.btn-xs.active>.handle {
            left: 1.125rem;
            transition: left 0.25s;
        }

        .btn-toggle.btn-xs.active:before {
            opacity: 0.5;
        }

        .btn-toggle.btn-xs.active:after {
            opacity: 1;
        }

        .btn-toggle.btn-xs.btn-sm:before,
        .btn-toggle.btn-xs.btn-sm:after {
            line-height: -1rem;
            color: #fff;
            letter-spacing: 0.75px;
            left: 0.275rem;
            width: 1.55rem;
        }

        .btn-toggle.btn-xs.btn-sm:before {
            text-align: right;
        }

        .btn-toggle.btn-xs.btn-sm:after {
            text-align: left;
            opacity: 0;
        }

        .btn-toggle.btn-xs.btn-sm.active:before {
            opacity: 0;
        }

        .btn-toggle.btn-xs.btn-sm.active:after {
            opacity: 1;
        }

        .btn-toggle.btn-xs.btn-xs:before,
        .btn-toggle.btn-xs.btn-xs:after {
            display: none;
        }

        .btn-toggle.btn-secondary {
            color: #6b7381;
            background: #bdc1c8;
        }

        .btn-toggle.btn-secondary:before,
        .btn-toggle.btn-secondary:after {
            color: #6b7381;
        }

        .btn-toggle.btn-secondary.active {
            background-color: #ff8300;
        }
  .loader {
    width: 100%;
    height: 100%;
    /*height: 1500px;*/
    overflow: hidden;
    top: 0px;
    left: 0px;
    z-index: 10000;
    text-align: center;
    position: absolute;
    background-color: #000;
    opacity: 0.6;
    filter: alpha(opacity=40);
    display: none;
  } 

  .dataTables_processing {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 500px !important;
    font-size: 1.7em;
    border: 0px;
    margin-left: -17% !important;
    text-align: center;
    background: #3c8dbc;
    color: #FFFFFF;
  }

  .modal-dialog {
    width: 90%;
  }

  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
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
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }

  .ui-autocomplete>li>div {
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
    color: #FFFFFF !important;
    background: #1d71b8 !important;
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

  input[readonly] {
    background-color: #eeeeee;
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
  .uneditable-input:focus {
    border-color: #2196F3;
    box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
    outline: 0 none;
  }

  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    /*width:150px;*/
    margin: 10px;
    text-align: center;
    margin-top: 40px;
    /*background:#5cb85c*/
    background: transparent;
    color: #009966
  }

  .fc-day-grid-event :hover {
    color: #009966
  }

  .fc-content {
    padding: 5px !important;
  }

  .fc-title {
    text-align: center;
    font-size: 12px
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

@endsection
@section('content')
<div class="loader"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MODULO DE ADMISION DE HOSPITALIZACIÓN</h3>
          </div>
          <div style="font-size: 11px" class="panel-body">
            <div class="panel panel-info panel-bordered panel-sm panel-body">
              <form class="form-horizontal" method="post" action="{{ route('store_emergencia_triaje') }}" id="frmHospitalizacion" name="frmHospitalizacion"
                autocomplete="off">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <!-- <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user"> -->
                <!-- <input value="" type="hidden" name="id_asegurado" id="id_asegurado"> -->
                <input type="hidden" value="1" name="id_tipo_doc_ident" id="id_tipo_doc_ident" class="form-control">
                <input type="hidden" value="DNI" name="tipo_doc_ident" id="tipo_doc_ident" class="form-control">            
                <input type="hidden" class="form-control" value="" name="domicilio" id="domicilio" />
                <!--  -->
                <div class="form-group">
                    <div class="col-md-4 text-center">
                        <img src="../../images/logo_sanidad.png" width="50" height="auto">
                        <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                        <input type="hidden" value="dni" name="tipo">
                        <input type="hidden" name="valid_nro_receta" id="valid_nro_receta" value="0"/>
                        <input type="hidden" value="<?php echo $id_ipress ?>" name="id_ipress" id="id_ipress">
                        <input type="hidden" value="<?php echo $nivel ?>" name="nivel" id="nivel">
                    </div>
                    <div class="col-md-4" style="text-align: center">
                        <label>{{$establecimiento->nombre}}</label><br>
                        <?php if(isset($id_hospitalizacion)){  ?>
                        <label style="font-size: 24px">MODIFICAR HOSPITALIZACIÓN</label><br>
                        <input type="hidden" value="<?php echo $id_hospitalizacion ?>" name="id_hospitalizacion" id="id_hospitalizacion">
                        <?php }else {  ?>
                        <label style="font-size: 24px">ADMISIÓN A HOSPITALIZACIÓN</label><br>
                        <?php } ?>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="../../images/logo_sanidad.png" width="50" height="auto">
                    </div>
                </div>
                <fieldset>
                        <legend>
                            <b>DATOS DE LA ATENCI&Oacute;N</b>
                        </legend>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>TIPO ATENCIÓN</label>
                                <input type="text" readonly="" value="<?php echo $tipo_receta ?>" name="tipo_receta" id="tipo_receta" class="form-control" style="text-align:left">
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-2">
                                <label>FEC. INGRESO</label>
                                <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_atencion" id="fecha_atencion" tabindex="1"  data-date-end-date="0d" autocomplete="off"/>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-2">
                                <label>HORA DE INGRESO &nbsp;</label>
                                <input type="time" name="hora" id="hora" value="{{}}" class="form-control" tabindex="2" required>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <input type="hidden" class="form-control" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_expedicion" id="fecha_expedicion" tabindex="2"  readonly=""/>
                            
                        </div>
                      </fieldset>
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>INFORMACIÓN DEL PACIENTE</b>
                              <div id="content"></div>
                            </div>
                          </div>
                          <div class="panel-body" id="collapseprestacionespaciente">
                            <div class="form-group">
                              <div class="col-md-12">
                                <fieldset>
                                  <div class="panel-body" id="collapseprestacionespaciente">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                        <label for="tipo">TIPO DOC.</label>
                                        <select class="form-control input-sm" id="tipodoc_beneficiario_bus"
                                          name="tipodoc_beneficiario_bus" onchange="maxlength_doc_usu()" tabindex="1">
                                          <option value="1">DNI</option>
                                          <option value="2">CARNET DE EXTRANJERIA</option>
                                          <option value="3">PASAPORTE</option>
                                          <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                        </select>
                                      </div>
                                      <div class="col-md-3">
                                        <label for="dni_beneficiario">NRO DOC.</label>
                                        <div class="input-group">
                                          <input id="dni_beneficiario" name="dni_beneficiario" type="text" class="form-control input-sm text-uppercase" maxlength="8" onkeypress="return esEntero(event);" tabindex="2" />
                                          <span class="input-group-btn" style="height:28px!important;">
                                            <button type="button" id="btnBuscar" onclick="getReniecByDni();getGestacionesByPacienteDni();" class="btn btn-info" tabindex="3" style="height:28px!important;line-height:10px">Buscar</button>
                                          </span>&nbsp;&nbsp;&nbsp;
                                          <input type="hidden" name="tipo_registro" id="tipo_registro">
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <label>Nro Documento</label>
                                        <input value="" tabindex="5" type="text" name="nro_doc_ident" id="nro_doc_ident" class="form-control" maxlength="8" onkeypress="return esEntero(event);" readonly=""  >
                                      </div>
                                      <div class="col-md-1">
                                        <label>SEXO &nbsp;</label>
                                        <input value="" tabindex="5" type="text" name="sexo" id="sexo" class="form-control text-uppercase" readonly=""  >
                                        <select class="form-control input-sm" id="selectSexo" name="selectSexo" tabindex="5">
                                          <option value="" selected=""></option>
                                          <option value="M">M</option>
                                          <option value="F">F</option>
                                        </select>
                                      </div>
                                      <div class="col-md-2">
                                        <label>F.NACIMIENTO &nbsp;</label>
                                        <input value="" type="text" name="fecha_nac" id="fecha_nac" class="form-control" readonly=""  >
                                        <div id="fecha_nacimiento_editable">
                                          <div class="input-group date fecha">
                                            <input readonly="" type="text" name="fecha_nac2" id="fecha_nac2" class="form-control" placeholder="dd/mm/aaaa"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-3">
                                        <label>AP.PATERNO</label>
                                        <input readonly="" type="hidden" class="form-control" value="" name="beneficiario"
                                          id="beneficiario" style="text-align:left" tabindex="4">
                                        <input value="" tabindex="5" type="text" name="paterno" id="paterno" class="form-control text-uppercase" readonly=""  >                         
                                      </div>
                                      <div class="col-md-3">
                                        <label>AP.MATERNO</label>
                                         <input value="" tabindex="5" type="text" name="materno" id="materno" class="form-control text-uppercase" readonly=""  >                        
                                      </div>
                                      <div class="col-md-3">
                                        <label>NOMBRES</label>                        
                                        <input value="" tabindex="5" type="text" name="nombre" id="nombre" class="form-control text-uppercase" readonly="" >                        
                                      </div>
                                      <div class="col-md-2">
                                          <label>HISTORIA CLINICA</label>
                                          <input type="text" class="form-control" value="" id="nro_historia" name="nro_historia" tabindex="5" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                      </div>
                                      
                                      <input value="" type="hidden" name="id_tipo_beneficiario" id="id_tipo_beneficiario" class="form-control" >             
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>INFORMACIÓN DEL PROFESIONAL QUE ORDENA LA HOSPITALIZACION</b>
                              <div id="content"></div>
                            </div>
                          </div>
                          <div class="panel-body" id="collapseprestacionespaciente">
                            <div class="form-group">
                              <div class="col-md-12">
                                <fieldset>
                                  <div class="panel-body" id="collapseprestacionespaciente">
                                    <div class="form-group">
                                      <div class="col-md-3">
                                        <label for="tipo">TIPO DOC.</label>
                                        <select class="form-control input-sm" id="tipodoc_medico"
                                          name="tipodoc_medico" onchange="maxlength_doc_usu()" tabindex="1">
                                          <option value="1">DNI</option>
                                          <option value="2">CARNET DE EXTRANJERIA</option>
                                          <option value="3">PASAPORTE</option>
                                          <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                        </select>
                                      </div>
                                      <div class="col-md-3">
                                        <label for="dni_beneficiario">NRO DOC.</label>
                                        <div class="input-group">
                                          <input id="dni_medico" name="dni_medico" type="text" class="form-control input-sm text-uppercase" maxlength="8" onkeypress="return esEntero(event);" tabindex="2" />
                                          <span class="input-group-btn" style="height:28px!important;">
                                            <button type="button" id="btnBuscarMedico" onclick="getMedicoByDni();" class="btn btn-info" tabindex="3" style="height:28px!important;line-height:10px">Buscar</button>
                                          </span>&nbsp;&nbsp;&nbsp;
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <label>Nro Documento</label>
                                        <input value="" tabindex="5" type="text" name="nro_doc_medico" id="nro_doc_medico" class="form-control" maxlength="8" onkeypress="return esEntero(event);" readonly=""  >
                                      </div>
                                      <div class="col-md-3">
                                        <label>AP.PATERNO</label>
                                        <input readonly="" type="hidden" class="form-control" value="" name="beneficiario"
                                          id="beneficiario" style="text-align:left" tabindex="4">
                                        <input value="" tabindex="5" type="text" name="paterno_medico" id="paterno_medico" class="form-control text-uppercase" readonly=""  >                         
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-3">
                                        <label>AP.MATERNO</label>
                                         <input value="" tabindex="5" type="text" name="materno_medico" id="materno_medico" class="form-control text-uppercase" readonly=""  >                        
                                      </div>
                                      <div class="col-md-3">
                                        <label>NOMBRES</label>                        
                                        <input value="" tabindex="5" type="text" name="nombre_medico" id="nombre_medico" class="form-control text-uppercase" readonly="" >                        
                                      </div>
                                      <div class="col-md-3">
                                        <label>ESPECIALIDAD &nbsp;</label>
                                        <input value="" tabindex="5" type="text" name="especialidad_medico" id="especialidad_medico" class="form-control text-uppercase" readonly="" >  
                                      </div>
                                      <div class="col-md-2">
                                        <label>CMP &nbsp;</label>
                                        <input value="" tabindex="5" type="text" name="cmp_medico" id="cmp_medico" class="form-control text-uppercase" readonly="" >  
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center">
                                <b>DATOS DE INGRESO</b>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <fieldset>
                                        <div class="panel-body" id="collapseprestacionespaciente">
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="tipo">ORIGEN</label>
                                                    <select class="form-control" name="origen" id="origen"  tabindex="3" style="width: 100%" onchange="cambiar_tipo_atencio_origen()" >
                                                      <option value="">-Seleccionar-</option>                        
                                                      <option value="1">AMBULATORIO</option>
                                                      <option value="2">EMERGENCIA</option>
                                                      <option value="3">HOSPITALIZACION</option>
                                                      @if($nivel==3)
                                                      <option value="4">CENTRO QUIRURGICO</option>
                                                      @endif
                                                      <option value="5">REFERENCIA</option>
                                                  </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="dni_beneficiario">TÓPICO</label>
                                                    <select class="form-control input-sm" id="id_servicio" name="id_servicio" style="width: 100%">
                                                       <option value="">Elegir</option>
                                                    </select>
                                                </div>
                                                <input type="hidden" class="form-control" value="2" name="origen" id="origen" readonly=""/>
                                                
                                                
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                          <b>REGISTRO DE UBICACIÓN</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-md-12">
                          <fieldset>
                            <div class="form-group">                          
                              @if($nivel==2)                        
                              <div class="col-md-3">
                                <label>SALA</label>
                                <select id="id_consultorio" name="id_consultorio" class="form-control" onchange="getCama()" style="width: 100%">
                                  <option value="">--Seleccionar--</option>
                                    @foreach($consultorio as $e)
                                      <option value="{{$e->id}}" >{{$e->nombre}}</option>
                                    @endforeach                                            
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label>TURNO</label>
                                <select class="form-control" name="turno" id="turno"  tabindex="3" style="width: 100%" onchange="getMedico()">
                                  <option value="">-Seleccionar-</option>
                                  <option value="MD">MADRUGADA</option>
                                  <option value="M">MAÑANA</option>
                                  <option value="T">TARDE</option>
                                  <option value="N">NOCHE</option>
                                </select>
                              </div>
                              @else  
                              <div class="col-md-4">
                                <label>ESPECIALIDAD</label>
                                <select id="id_consultorio" name="id_consultorio" class="form-control" onchange="getSala()" style="width: 100%" >
                                    <option value="">--Seleccionar--</option>
                                    @foreach($consultorio as $e)
                                        <option value="{{$e->id}}" <?php if($e->id==$id_consultorio_destino){?>selected <?php } ?> >{{$e->nombre}}</option>
                                    @endforeach                                            
                                </select>
                              </div>
                              <div class="col-md-4">
                                <label>SALA</label>
                                <select id="id_sala" name="id_sala" class="form-control" onchange="getCama2()" style="width: 100%">
                                    <option value="">--Seleccionar--</option>
                                </select>
                              </div>                                              
                              @endif
                              <div class="col-md-4">
                                <label>CAMA</label>
                                <select id="id_cama" name="id_cama" class="form-control" style="width: 100%">
                                    <option value="">--Seleccionar--</option>
                                </select>
                              </div>
                            </div>
                          </fieldset>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                          <b>DIAGNOSTICO</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-md-12">
                          <fieldset>
                            <div class="panel-body">
                                <input type="text"  class="form-control typeahead tt-query" id="nombre_diagnostico" name="nombre_diagnostico" tabindex="10">
                                <input type="hidden" class="form-control typeahead tt-query" id="id_diagnostico" name="id_diagnostico" value="">
                            </div>
                          </fieldset>
                        </div>
                      </div>
                    </div>
                  </div>

                  @if (
                    Auth::user()->id_consultorio==77 //&& 
                    //strtoupper($cita->sexo) === 'F' && 
                    //intval($cita->asegurado_edad) >= 12 && 
                    //intval($cita->asegurado_edad) <= 55 
                  )
                    <div id="ocultar1" class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title text-center">
                            <b>SALUD MATERNA</b>
                        </div>
                      </div>
                  <div id="gestacion_hosp" class="panel panel-info panel-bordered panel-sm panel-body">
                      <div class="form-group">
                              <div class="row" style="margin-left:4px">
                                  <div class="col-sm-2"
                                      style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>GESTANTE</label>
                                  </div>
                              </div>
                          </div>
                          <fieldset id="frsgetante" style="">
                                  
                                  <div class="panel-body">
                                      <div class="row">
                                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                              <div class="form-group">
                                                <label for="ultimaMestruacion">ULTIMA MESTRUACIÓN:</label>
                                                <input readonly type="text" id="ultimaMestruacion"
                                                    name="ultimaMestruacion"
                                                    class="form-control input-sm typeahead tt-query datepicker"
                                                    style="width: 90%;">
                                              </div>
                                          </div>
                                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                            <div class="form-group">
                                              <label for="parto_probable">FECHA PROBABLE DE PARTO:</label>
                                              <input readonly type="text" id="parto_probable"
                                                  name="parto_probable"
                                                  class="form-control input-sm datepicker"
                                                  style="width: 90%;">
                                            </div>
                                          </div>
                                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                            <div class="form-group">
                                              <label for="semana_gesta">SEMANAS DE GESTACIÓN:</label>
                                              <input readonly type="text" id="semana_gesta"
                                                      name="semana_gesta" class="form-control input-sm"
                                                      style="width: 90%;">
                                            </div>
                                          </div>



                                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                            <div class="form-group">
<label for="control_prenatal">N° DE CONTROLES PRENATALES</label>
<input type="text" id="control_prenatal" name="control_prenatal" pattern="[0-9]" 
 class="form-control input-sm" style="width: 90%;" placeholder="Ingresar desde n° 0 hasta 9" 
 title="Ingresa solo números del 0 al 9" required maxlength="1">
<small class="form-text text-muted">Ingresar un valor valido -DE 0 A 9</small>
</div>
</div>


<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                              <div class="form-group">
                                                  <label>DUDA:</label><br>
                                                  <label class="radio-inline"><input type="radio" name="duda" value="1"> SI</label>
                                                  <label class="radio-inline"><input type="radio" name="duda" value="0" checked>NO</label>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              
                          </fieldset>
                      </div>
                  @endif

                </fieldset>
                <div class="form-group">
                  <div class="col-md-6">
                      <a href="" class="form-control btn btn-danger" tabindex="99922" style="text-align:center!important" tabindex="24" >CANCELAR</a>
                  </div>
                  <div class="col-md-6">
                     <a class="form-control btn btn-success" onclick="guardarTriaje()" style="text-align:center!important" tabindex="23">GUARDAR</a>
                  </div>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

@include('frontend.includes.footer')
@endsection