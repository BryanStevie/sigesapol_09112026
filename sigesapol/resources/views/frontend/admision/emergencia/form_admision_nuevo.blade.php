@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
<link rel="stylesheet" type="text/css" href="plugins/jQuery/jquery-ui.css" />

<style>
  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }
 
  .select2-container { 
    font-size: 11px !important;
  }

  .marginTop15px {
    margin-top: 15px;
  }
</style>

<script>
  function getProvincia(){
    var newOption = "";
    newOption = "<option value=''>--SELECCIONAR--</option>";
    $('#Provincia').html(newOption);
    //$("#id_anestesiologo").val('').trigger("change");

    var Departamento = $('#Departamento').val();

    $.ajax({    
      url: '/obtener_provincia_por_departamento/' + Departamento,
      success: function(result){                    
      var newOption = ""; //var cant=0;
      /*cant= result.length;
        if(cant>1){
        newOption = "<option value=''>--SELECCIONAR--</option>";
      }*/
      newOption = "<option value=''>--SELECCIONAR--</option>";
      $(result).each(function (ii, oo) {
        newOption += "<option value='"+oo.id_provincia+"'>"+oo.descripcion+"</option>";
      });                 
      $('#Provincia').html(newOption);
      $("#Provincia").select2({max_selected_options: 4});
      
      }
    });
  }

  function getDistrito(){
    var newOption = "";
    newOption = "<option value=''>--SELECCIONAR--</option>";
    $('#Distrito').html(newOption);
    //$("#id_anestesiologo").val('').trigger("change");

    var Provincia = $('#Provincia').val();

    $.ajax({    
      url: '/obtener_distrito_por_provincia/' + Provincia,
      success: function(result){                    
      var newOption = "";
      newOption = "<option value=''>--SELECCIONAR--</option>";
      $(result).each(function (ii, oo) {
        newOption += "<option value='"+oo.id_distrito+"'>"+oo.descripcion+"</option>";
      });                 
      $('#Distrito').html(newOption);
      $("#Distrito").select2({max_selected_options: 4});      
      }
    });
  }  

  function obtener_Tipo_Violencia(){
    var newOption = "";
    newOption = "<option value=''>--SELECCIONAR--</option>";
    $('#id_tipo_violencia').html(newOption);

    var Violencia = $('#id_violencia').val();

    $.ajax({    
      url: '/obtener_tipo_violencia/' + Violencia,
      success: function(result){                    
        console.log(result);
      var newOption = "";
      newOption = "<option value=''>--SELECCIONAR--</option>";
      $(result).each(function (ii, oo) {
        newOption += "<option value='"+oo.id_tipo_violencia+"'>"+oo.descripcion+"</option>";
      });                 
      $('#id_tipo_violencia').html(newOption);
      $("#id_tipo_violencia").select2({max_selected_options: 4});      
      }
    });
  }

   function ocultar_datos(){
        if(document.getElementById('id_categoria').value==3){
            $('#muestra-datos-violencia').show();
            $('#muestra-datos-evacuado').hide();
        } else if(document.getElementById('id_categoria').value==4){
            $('#muestra-datos-evacuado').show();
            $('#muestra-datos-violencia').hide();
        } else {
            $('#muestra-datos-violencia').hide();
            $('#muestra-datos-evacuado').hide();
        }
    }

  function __autocomplete__AjaxBusqueda(){
    
    $("#nombre_diagnostico").autocomplete({
    minLength: 1,
    delay : 400,
    source: function(request, response) {
    $.ajax({
      url: '../../obtener_diagnostico/' + request.term,
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

    function filterFloat(evt,input){
    // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
    var key = window.Event ? evt.which : evt.keyCode;    
    var chark = String.fromCharCode(key);
    var tempValue = input.value+chark;
    if(key >= 48 && key <= 57){
        if(filter(tempValue)=== false){
            return false;
        }else{       
            return true;
        }
    }else{
          if(key == 8 || key == 13 || key == 0) {     
              return true;              
          }else if(key == 46){
                if(filter(tempValue)=== false){
                    return false;
                }else{       
                    return true;
                }
          }else{
              return false;
          }
    }
}
function filter(__val__){
    var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
    if(preg.test(__val__) === true){
        return true;
    }else{
       return false;
    }
    
}
    
    $consul = $("#frmAdmision").find("#id_forma_ingreso");
    $consul.select2({
        width: 'resolve' // need to override the changed default
    });
    
    $tur = $("#frmAdmision").find("#id_estado_ingreso");
    $tur.select2({
        width: 'resolve' // need to override the changed default
    });
    
    $orig = $("#frmAdmision").find("#id_motivo_ingreso");
    $orig.select2({
        width: 'resolve' // need to override the changed default
    });

    $provincia = $("#frmAdmision").find("#Provincia");
    $provincia.select2({
        width: 'resolve' // need to override the changed default
    });
    
    $departamento = $("#frmAdmision").find("#Departamento");
    $departamento.select2({
        width: 'resolve' // need to override the changed default
    });
   
    $distrito = $('#frmAdmision').find("#Distrito")
    $distrito.select2({
        width: 'resolve' // need to override the changed default
    });

    $categoria = $("#frmAdmision").find("#id_categoria");
    $categoria.select2({
        width: 'resolve' // need to override the changed default
    });

    $violencia = $("#frmAdmision").find("#id_violencia");
    $violencia.select2({
        width: 'resolve' // need to override the changed default
    });

    $tipo_violencia = $("#frmAdmision").find("#id_tipo_violencia");
    $tipo_violencia.select2({
        width: 'resolve' // need to override the changed default
    });

    $comisaria = $("#frmAdmision").find("#id_comisaria");
    $comisaria.select2({
        width: 'resolve' // need to override the changed default
    });

    $situacion = $("#frmAdmision").find("#id_situacion");
    $situacion.select2({
        width: 'resolve' // need to override the changed default
    });
    $provinciaac = $("#frmAdmision").find("#ProvinciaAc");
    $provinciaac.select2({
        width: 'resolve' // need to override the changed default
    });
    $departamentoac = $("#frmAdmision").find("#DepartamentoAc");
    $departamentoac.select2({
        width: 'resolve' // need to override the changed default
    });
    $distritoac = $("#frmAdmision").find("#DistritoAc");
    $distritoac.select2({
        width: 'resolve' // need to override the changed default
    });
    $unidad = $("#frmAdmision").find("#id_unidad");
    $unidad.select2({
        width: 'resolve' // need to override the changed default
    });
    $unidad_policial = $("#frmAdmision").find("#id_unidad_policial");
    $unidad_policial.select2({
        width: 'resolve' // need to override the changed default
    });
    $departamentoev = $("#frmAdmision").find("#id_departamento_evac");
    $departamentoev.select2({
        width: 'resolve' // need to override the changed default
    });
    $diagnostico = $("#frmAdmision").find("#id_diagnostico_inicial");
    $diagnostico.select2({
        width: 'resolve' // need to override the changed default
    });
    $situacion = $("#frmAdmision").find("#id_situacion_evac");
    $situacion.select2({
        width: 'resolve' // need to override the changed default
    });
    $tipo_beneficiario = $("#frmAdmision").find("#id_tipo_beneficiario");
    $tipo_beneficiario.select2({
        width: 'resolve' // need to override the changed default
    });
    $tipo_violencia = $("#frmAdmision").find("#id_tipo_violencia");
    $tipo_violencia.select2({
        width: 'resolve' // need to override the changed default
    });
    $oficial_guardia = $("#frmAdmision").find("#oficial_guardia");
    $oficial_guardia.select2({
        width: 'resolve' // need to override the changed default
    });
    $tipodoc_beneficiario_bus = $("#frmAdmision").find("#tipodoc_beneficiario_bus");
    $tipodoc_beneficiario_bus.select2({
        width: 'resolve' // need to override the changed default
    });
    
  
  jQuery(document).on('focus', '.select2', function() {
      jQuery(this).siblings('select').select2('open');
  });

  function AddDiagnostico(){
    var newRow = "";
    var ind = $('#tblDiagnostico tbody tr').length;
    
    if(ind > 0){
        $('.id_diagnostico').each(function(){
            var ind_tmp = $(this).data("id");
            if(ind_tmp => ind){
                ind=Number(ind_tmp)+1;
            }
        });
                    
        fila_medicamentos=$('#tblDiagnostico tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento=fila_medicamentos[idval];
        
    }
    
    var item_tipo_diagnostico = '';
    newRow +='<tr>';
    newRow +='<td><input tabindex="39" type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow +='<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico'+ind+'" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
    newRow +='</tr>';
    $('#tblDiagnostico tbody').append(newRow);
    
    $("#nombre_diagnostico"+ind).autocomplete({
        minLength: 1,
        delay : 400,
        source: function(request, response) {
            $.ajax({
                url: '../../obtener_diagnostico/' + request.term,
                //url: '../obtener_diagnostico_genero/' + request.term + '/'+ genero,
                dataType: "json",
                success: function(data)  {
                    let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
          response(filtro);
                }                   
            })
    
        },select:  function(e, ui) {
            var id_diagnostico = ui.item.id;
            $("#id_diagnostico"+ind).val(id_diagnostico);
            $("#nombre_diagnostico"+ind).prop("readonly",false);
        }
    });
    
    $("#nombre_diagnostico"+ind).on('blur', function() {
        var id_diagnostico = $("#id_diagnostico"+ind).val();
        if(id_diagnostico == ""){
            bootbox.alert("Falta ingresar el diagnostico en esta fila");
            $("#nombre_diagnostico"+ind).val("");
            return false;
        }
    });
    
}

AddDiagnostico();


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
 
    

    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }    

    //-------------------------------------------------------------
    
    function validateFrm(){     

        //Datos Personales
        if ($('#frmAdmision').find("#id_forma_ingreso").val() == "") {
            bootbox.alert("Falta seleccionar la Forma de Ingreso", function(){            
              document.getElementById("id_forma_ingreso").click();
               setTimeout(function (){
                  $('#id_forma_ingreso').focus();
              }, 500);
            });
            $('#btnReg').prop('disabled', false);           
            return false;
        }
        if ($('#frmAdmision').find("#id_estado_ingreso").val() == "") {
            bootbox.alert("Falta seleccionar el Estado de Ingreso", function(){            
              document.getElementById("id_estado_ingreso").click();
               setTimeout(function (){
                  $('#id_estado_ingreso').focus();
              }, 500);
            });
            $('#btnReg').prop('disabled', false);           
            return false;
        }
        

        //conducido
        if ($('#frmAdmision').find("#id_tipo_beneficiario").val() == "TITULAR") {
            if ($('#frmAdmision').find("#dni_conducido").val() == "") {
              bootbox.alert("Falta ingresar dni del conducido", function(){            
                document.getElementById("dni_conducido").click();                   
                   setTimeout(function (){
                   $('#dni_conducido').focus();
                   }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#conducido").val() == "") {
              bootbox.alert("Falta ingresar datos del conducido", function(){            
                document.getElementById("conducido").click();                   
                   setTimeout(function (){
                   $('#conducido').focus();
                   }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#id_tipo_beneficiario").val() == "") {
              bootbox.alert("Falta seleccionar el Parentesco", function(){            
                document.getElementById("id_tipo_beneficiario").click();                   
                   setTimeout(function (){
                   $('#id_tipo_beneficiario').focus();
                   }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
        }

        if ($('#frmAdmision').find("#Distrito").val() == "") {
            bootbox.alert("Debe Seleccionar el Departamento, Provincia y Distrito", function(){     
              document.getElementById("Distrito").click();
               setTimeout(function (){
                  $('#Distrito').focus();
              }, 500);
            });
            $('#btnReg').prop('disabled', false);           
            return false;
        }
        if ($('#frmAdmision').find("#direccion_referencial").val() == "") {
            bootbox.alert("Falta Ingresar la Direccion Referencial", function(){            
              document.getElementById("direccion_referencial").click();
               setTimeout(function (){
                  $('#direccion_referencial').focus();
              }, 500);
            });
            $('#btnReg').prop('disabled', false);           
            return false;
        }
        if ($('#frmAdmision').find("#telefono").val() == "") {
            bootbox.alert("Falta Ingresar un telefono para contacto", function(){            
              document.getElementById("telefono").click();
               setTimeout(function (){
                  $('#telefono').focus();
              }, 500);
            });
            $('#btnReg').prop('disabled', false);           
            return false;
        }
        //violencia
        if ($('#frmAdmision').find("#id_categoria").val() == "3") {
            if ($('#frmAdmision').find("#id_violencia").val() == "") {
              bootbox.alert("Falta seleccionar Tipo de Violencia", function(){            
                document.getElementById("id_violencia").click();
                 setTimeout(function (){
                    $('#id_violencia').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#id_tipo_violencia").val() == "") {
              bootbox.alert("Falta seleccionar la Modalidad de Violencia", function(){            
                document.getElementById("id_tipo_violencia").click();
                 setTimeout(function (){
                    $('#id_tipo_violencia').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#id_comisaria").val() == "") {
              bootbox.alert("Falta seleccionar la Comisaria", function(){            
                document.getElementById("id_comisaria").click();
                 setTimeout(function (){
                    $('#id_comisaria').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#id_situacion").val() == "") {
              bootbox.alert("Falta Seleccionar la Situación del Paciente", function(){            
                document.getElementById("id_situacion").click();
                 setTimeout(function (){
                    $('#id_situacion').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#fecha_accidente").val() == "") {
              bootbox.alert("Falta ingresar la fecha de accidente", function(){            
                document.getElementById("fecha_accidente").click();
                 setTimeout(function (){
                    $('#fecha_accidente').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#hora_accidente").val() == "") {
              bootbox.alert("Falta ingresar la fecha de accidente", function(){            
                document.getElementById("hora_accidente").click();
                 setTimeout(function (){
                    $('#hora_accidente').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#DistritoAc").val() == "") {
              bootbox.alert("Debe Seleccionar el Departamento, Provincia y Distrito", function(){            
                document.getElementById("DistritoAc").click();
                 setTimeout(function (){
                    $('#DistritoAc').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#lugar_accidente").val() == "") {
              bootbox.alert("Falta ingresar el lugar del accidente", function(){            
                document.getElementById("lugar_accidente").click();
                 setTimeout(function (){
                    $('#lugar_accidente').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#id_unidad").val() == "") {
              bootbox.alert("Falta Seleccionar la Unidad Policial", function(){            
                document.getElementById("id_unidad").click();
                 setTimeout(function (){
                    $('#id_unidad').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }

        }
        //evacuado 
        if ($('#frmAdmision').find("#id_categoria").val() == "4") {

            if ($('#frmAdmision').find("#id_departamento_evac").val() == "") {
              bootbox.alert("Falta Seleccionar el Departamento", function(){            
                document.getElementById("id_departamento_evac").click();
                 setTimeout(function (){
                    $('#id_departamento_evac').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#numero_oficio").val() == "") {
              bootbox.alert("Falta Ingresar el Número Oficio", function(){            
                document.getElementById("numero_oficio").click();
                 setTimeout(function (){
                    $('#numero_oficio').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            if ($('#frmAdmision').find("#id_diagnostico_inicial").val() == "") {
              bootbox.alert("Falta Seleccionar el Diagnóstico inicial", function(){            
                document.getElementById("pills-contact-tab").click();
                 setTimeout(function (){
                    $('#id_diagnostico_inicial').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }

            if ($('#frmAdmision').find("#id_unidad_policial").val() == "") {
              bootbox.alert("Falta Seleccionar la Unidad Policial", function(){            
                document.getElementById("id_unidad_policial").click();
                 setTimeout(function (){
                    $('#id_unidad_policial').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }

            if ($('#frmAdmision').find("#id_situacion_evac").val() == "") {
              bootbox.alert("Falta Seleccionar la Unidad Policial", function(){            
                document.getElementById("id_situacion_evac").click();
                 setTimeout(function (){
                    $('#id_situacion_evac').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }

            if ($('#frmAdmision').find("#observacion_evac").val() == "") {
              bootbox.alert("Falta Ingresar la observación", function(){            
                document.getElementById("observacion_evac").click();
                 setTimeout(function (){
                    $('#observacion_evac').focus();
                }, 500);
              });
              $('#btnReg').prop('disabled', false);           
              return false;
            }
            
        }
        
        submitFrm();
        return false;
    }
    
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
                  $("#frmAdmision").submit();
              } else {
                  $('#btnReg').prop('disabled', false);
              }
          }
      });
    }
    //--------------------------------------------------------------------------
  
  

function limpiaDatos()
  {
            
    var nivel     = $('#nivel').val();        

    if(nivel==2){
      $("#turno").val('').trigger("change");
      var newOption = "";
      newOption = "<option value=''>--SELECCIONAR--</option>";
      $('#id_medico').html(newOption);
      $("#id_medico").val('').trigger("change");
    }

  }


    function getProvinciaAc(){
        var newOption = "";
        newOption = "<option value=''>--SELECCIONAR--</option>";
        $('#ProvinciaAc').html(newOption);
        //$("#id_anestesiologo").val('').trigger("change");

        var Departamento = $('#DepartamentoAc').val();

        $.ajax({    
          url: '/obtener_provincia_por_departamento/' + Departamento,
          success: function(result){                    
          var newOption = ""; //var cant=0;
          newOption = "<option value=''>--SELECCIONAR--</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id_provincia+"'>"+oo.descripcion+"</option>";
          });                 
          $('#ProvinciaAc').html(newOption);
          $("#ProvinciaAc").select2({max_selected_options: 4});
          
          }
        });
    }

    function getDistritoAc(){
        var newOption = "";
        newOption = "<option value=''>--SELECCIONAR--</option>";
        $('#DistritoAc').html(newOption);
        //$("#id_anestesiologo").val('').trigger("change");

        var Provincia = $('#ProvinciaAc').val();

        $.ajax({    
          url: '/obtener_distrito_por_provincia/' + Provincia,
          success: function(result){
          var newOption = "";
          newOption = "<option value=''>--SELECCIONAR--</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id_distrito+"'>"+oo.descripcion+"</option>";
          });                 
          $('#DistritoAc').html(newOption);
          $("#DistritoAc").select2({max_selected_options: 4});      
          }
        });
    }

    $("#dni_conducido").blur(function(event) {
      getReniecByDni();
    });

    $("#dni_beneficiario").blur(function(event) {
      //getPacienteReniecByDni();
      getBeneficiarioDocumento();
    });

    function getReniecByDni() {
      $('#btnBuscar').prop('disabled', false);
      $('#dni_conducido_buscar').prop('disabled', false);
      var dni_conducido = $('#dni_conducido').val();

      $.ajax({
        url: '/validar_documento/' + dni_conducido,
        success: function(result) {
      
          
          if (result.codigoMensaje != '00') {
            alert('documento no existe');
            $('#btnReg').prop('disabled', true);
          } else {
            
            conducido = result.nombres + ' , ' + result.paterno + ' ' + result.materno;
            $('#conducido').val(conducido).attr("readonly", true);
            $('#btnReg').prop('disabled', false);
          }
        }
      });

    }

    function getBeneficiarioDocumento() {
      let tipo_documento = $('#tipodoc_beneficiario_bus').val();
      let nro_documento = $('#dni_beneficiario').val();
      $.ajax({
        url: '/buscar_persona_ajax/' + tipo_documento +'/'+nro_documento,
        success: function(result) {
          persona = JSON.parse(result);
          alert(persona);
          $('#foto_paciente').attr('src', `data:image/png;base64,${persona.foto}`);
          $('#nombre_beneficiario').val(persona.nombre).attr("readonly", false);
          $('#paterno_beneficiario').val(persona.paterno).attr("readonly", false);
          $('#materno_beneficiario').val(persona.materno).attr("readonly", false);
          $('#nomsexo').val(persona.sexo).attr("readonly", false);
          $('#direccion_referencial').val(persona.domicilio).attr("readonly", false);
          $('#fnacimiento').val(persona.fecha_nac).attr("readonly", false);
          $('#btnReg').prop('disabled', false);
          $('#hd_foto').val(persona.foto);
          $('#hd_tipo_beneficiario').val(persona.id_tipo_beneficiario);
          $('#carne').val(persona.carne);
          $('#grado').val(persona.grado);
          $('#situacion').val(persona.situacion);
          $('#sangre').val(persona.sangre);

          if (persona.id_tipo_beneficiario == 13) {
            $('#panel_oficial_guardia').removeClass('hidden');
          } else {
            $('#panel_oficial_guardia').addClass('hidden');
          }

        }
      });
    }

    $('#id_violencia').on('change', function(){
      obtener_Tipo_Violencia();
    })

    function maxlength_doc_usu() {

        $('#dni_beneficiario').val('');
        limpiar_paciente();
        
        if ($("#tipodoc_beneficiario_bus").val() == "1") {
            $("#dni_beneficiario").attr('maxlength', '8');
            llenar_manual(0);
        } else {
            $("#dni_beneficiario").attr('maxlength', '12');
            llenar_manual(1);
        }
        document.getElementById("dni_beneficiario").focus();
    }

    function limpiar_paciente(){
        
        $('#beneficiario').val("");    
        $('#dni_beneficiario').val("");
        $('#nombre_beneficiario').val("");
        $('#paterno_beneficiario').val("");
        $('#materno_beneficiario').val("");
        $('#nomsexo').val("");
        $('#fnacimiento').val("");
        
   }

    function llenar_manual(rpta){
        
        if (rpta == 1) {
            $("#paterno_beneficiario").val("").attr("readonly", false);
            $("#materno_beneficiario").val("").attr("readonly", false);
            $("#nombre_beneficiario").val("").attr("readonly", false);  
            $("#nomsexo").val("").attr("readonly", false);  
            $("#fnacimiento").val("").attr("readonly", false);
            
        }else{
            
            $("#paterno_beneficiario").val("").attr("readonly", true);
            $("#materno_beneficiario").val("").attr("readonly", true);
            $("#nombre_beneficiario").val("").attr("readonly", true);  
            $("#nomsexo").val("").attr("readonly", true);  
            $("#fnacimiento").val("").attr("readonly", true);
            
        }   
        
        document.getElementById("dni_beneficiario").focus();

    }

</script>
<style>
  .modal-dialog {
    width: 850px !important;
  }

  .suggest-element {
    margin-left: 5px;
    margin-top: 5px;
    width: 350px;
    cursor: pointer;
  }

  #suggestions {
    width: 350px;
    height: 150px;
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
    border-top: 1px solid rgba(255, 165, 0, 0.4);
    border-bottom: 1px solid rgba(255, 165, 0, 0.4);
    color: rgb(225, 75, 0);
  }

  .alert.alert-success {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
    border-top: 1px solid limegreen;
    border-bottom: 1px solid limegreen;
  }

  .form-control {
    height: 28px;
  }

  .form-group {
    margin-bottom: 10px;
  }

  .nav-pills .nav-link.active,
  .nav-pills .show>.nav-link {
    color: #fff;
    background-color: #0d6efd;
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
            <h3 class="panel-title">MÓDULO DE ADMISION DE PACIENTES A EMERGENCIA</h3>
          </div>
          <div style=" font-size: 11px" class="panel-body">
            <form class="form-horizontal" method="post" action="{{ route('registrar_admision_emergencia')}}"
              id="frmAdmision" autocomplete="off">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="form-group">
                <div class="col-md-4 text-center">
                </div>
                <div class="col-md-4" style="text-align: center">
                  <label style="font-size: 16px">{{$nombre_establecimiento}}</label><br>
                </div>
                <div class="col-md-4 text-center">

                </div>
              </div>
              <!--informacion de paciente-->
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL PACIENTE</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                    <div class="col-md-9">
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="tipo">TIPO DE DOCUMENTO</label>
                          <select tabindex="1"  class="form-control input-sm" id="tipodoc_beneficiario_bus"
                            name="tipodoc_beneficiario_bus" onchange="maxlength_doc_usu()" tabindex="3">
                            <option value="">--SELECCIONAR---</option>
                            <option value="1">DNI</option>
                            <option value="2">CARNET DE EXTRANJERIA</option>
                            <option value="3">PASAPORTE</option>
                            <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                            <option value="5">NN</option>
                          </select>
                          <input type="hidden" value="" name="tipodoc_beneficiario" id="tipodoc_beneficiario"
                            class="form-control">
                        </div>
                        <div class="col-md-3">
                          <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                          <input tabindex="2" onKeyPress="return soloNumeros(event)" id="dni_beneficiario" name="dni_beneficiario"
                            type="text" placeholder="" class="form-control input-sm text-uppercase" maxlength="8"
                            autofocus="">
                        </div>
                        <div class="col-md-3">
                          <label>APELLIDO PATERNO</label>
                          <input tabindex="3" type="text" readonly="" class="form-control" value="" name="paterno_beneficiario"
                            id="paterno_beneficiario" />
                        </div>
                        <div class="col-md-3">
                          <label>APELLIDO MATERNO</label>
                          <input readonly="" tabindex="4" type="text" class="form-control" value="" name="materno_beneficiario"
                            id="materno_beneficiario" />
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-3">
                          <label>NOMBRES</label>
                          <input readonly="" tabindex="5" type="text" class="form-control" value="" name="nombre_beneficiario"
                            id="nombre_beneficiario" />
                        </div>
                        <div class="col-md-3">
                          <!--input readonly="" tabindex="6" type="text" class="form-control" value="" name="nomsexo" id="nomsexo" /-->
                          <label>SEXO &nbsp;</label>
                          <select class="form-control input-sm" id="nomsexo" name="nomsexo" readonly tabindex="6" >
                            <option value="" selected=""></option>
                            <option value="M">M</option>
                            <option value="F">F</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <label for="dni_beneficiario">FECHA NAC.</label>
                          <input readonly="" tabindex="7" type="text" class="form-control" value="" name="fnacimiento" placeholder="dd/mm/YY" id="fnacimiento" />
                        </div>
                        
                      </div>
                    </div>
                    <div class="col-md-3 float-right">
                      <img id="foto_paciente" width="100px">
                    </div>
                  </div>
                </div>
              </fieldset>
              <!--#informacion de paciente-->

              <!--informacion de ingreso-->
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL INGRESO</b>
                    </div>
                  </div>
                  <div class="panel-body" id="">
                    <div class="col-md-2"><br />
                      <label>FORMA INGRESO</label>
                      <select tabindex="8" class="form-control" style="width: 100%" name="id_forma_ingreso"
                        id="id_forma_ingreso">
                        <option value="">--SELECCIONAR---</option>
                        @foreach($forma_ingreso as $f)
                        <option value="{{$f->id}}">{{$f->descripcion}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-2"><br />
                      <label>ESTADO INGRESO</label>
                      <select tabindex="9" class="form-control" style="width: 100%" name="id_estado_ingreso"
                        id="id_estado_ingreso">
                        <option value="">--SELECCIONAR---</option>
                        @foreach($estado_ingreso as $f)
                        <option value="{{$f->id}}">{{$f->descripcion}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-2"><br />
                      <label>MOTIVO DE INGRESO</label>
                      <select tabindex="10" class="form-control" style="width: 100%" name="id_categoria" id="id_categoria"
                        onchange="ocultar_datos();">
                        <option value="">--SELECCIONAR---</option>
                        @foreach($categoria as $f)
                        <option value="{{$f->id}}">{{$f->descripcion}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-md-12"><br />
                      <label>CONDUCIDO POR</label>
                      <div class="row">
                        <div class="col-md-2">
                          <input tabindex="11" type="text" class="form-control" value="" id="dni_conducido" name="dni_conducido"
                            tabindex="" style="text-transform:uppercase;" maxlength="8" placeholder="dni">
                        </div>
                        <div class="col-md-6">
                          <input tabindex="12" type="text" class="form-control" value="" id="conducido" name="conducido" tabindex=""
                            style="text-transform:uppercase;"
                            onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-md-2">
                          <select  tabindex="13" class="form-control select2" style="width: 100%" name="id_tipo_beneficiario"
                            id="id_tipo_beneficiario">
                            <option value="">--Tipo--</option>
                            @foreach($tipo_beneficiario as $f)
                            <option value="{{$f->id}}">{{$f->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-2">
                          <input tabindex="14" type="text" class="form-control" value="" id="telfconducido" name="telfconducido"
                            tabindex="" style="text-transform:uppercase;" placeholder="telefono" maxlength="9">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 hidden" id="panel_oficial_guardia"><br />
                      <label>OFICIAL DE GUARDIA</label>
                      <select tabindex="15" class="form-control select2 " style="width: 100%" name="oficial_guardia"
                        id="oficial_guardia">
                        <option value="">--SELECCIONAR---</option>
                        @foreach($personal_guardia as $f)
                        <option value="{{$f->id}}">{{$f->nombre}}{{$f->paterno}}{{$f->materno}}</option>
                        @endforeach
                      </select><br />
                    </div>
                  </div>
                </div>
              </fieldset>
              <!--#informacion de ingreso-->

              <!--informacion de referencial-->
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center">
                    <b>DATOS REFERENCIALES</b>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-md-2"><br />
                    <label>DEPARTAMENTO</label>
                    <select tabindex="16" class="form-control select2" style="width: 100%" name="Departamento" id="Departamento"
                      onchange="getProvincia();">
                      <option value="">--SELECCIONAR---</option>
                      @foreach($departamento as $f)
                      <option value="{{$f->id_departamento}}">{{$f->descripcion}}</option>
                      @endforeach
                    </select><br />
                  </div>
                  <div class="col-md-2"><br />
                    <label>PROVINCIA</label>
                    <select tabindex="16" class="form-control select2" style="width: 100%" name="Provincia" id="Provincia"
                      onchange="getDistrito();">
                      <option value="">--SELECCIONAR---</option>
                    </select><br />
                  </div>
                  <div class="col-md-2"><br />
                    <label>DISTRITO</label>
                    <select tabindex="17" class="form-control select2" style="width: 100%" name="Distrito" id="Distrito">
                      <option value="">--SELECCIONAR---</option>
                    </select><br />
                  </div>
                  <div class="col-md-4"><br />
                    <label>DIRECCIÓN REFERENCIAL</label>
                    <input tabindex="18" type="text" class="form-control" value="" id="direccion_referencial"
                      name="direccion_referencial" tabindex="" style="text-transform:uppercase;"
                      onkeyup="javascript:this.value = this.value.toUpperCase();"><br />
                  </div>
                  <div class="col-md-2"><br />
                    <label>TELÉFONO</label>
                    <input tabindex="18" type="text" class="form-control" value="" id="telefono" name="telefono" tabindex=""
                      style="text-transform:uppercase;" maxlength="9"
                      onkeyup="javascript:this.value = this.value.Number();"><br />
                  </div>
                </div>
              </div>
              <!--#informacion de referencial-->

              <!--informacion de violencia-->
              <div id="muestra-datos-violencia" style="display: none;">
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>REGISTROS DE VIOLENCIA</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="col-md-3"><br />
                        <label>TIPO DE VIOLENCIA</label>
                        <select tabindex="24" class="form-control select2" style="width: 100%" name="id_violencia"
                          id="id_violencia" />
                        <option value="">--SELECCIONAR---</option>
                        @foreach($violencia as $item)
                        <option value="{{$item->id}}">{{$item->descripcion}}</option>
                        @endforeach
                        </select>
                      </div>
                      <div class="col-md-3"><br />
                        <label>MODALIDAD</label>
                        <select tabindex="25" class="form-control select2" style="width: 100%" name="id_tipo_violencia"
                          id="id_tipo_violencia">
                          <option value="">--SELECCIONAR---</option>
                          <!--@foreach($modoviolencia as $item)
                          <option value="{{$item->id_tipo_violencia}}">{{$item->descripcion}}</option>
                          @endforeach-->
                        </select>
                      </div>
                      <div class="col-md-4"><br />
                        <label>COMISARIA</label>
                        <select tabindex="26" class="form-control select2" style="width: 100%" name="id_comisaria" id="id_comisaria">
                          <option value="">--SELECCIONAR---</option>
                          @foreach($comisaria as $item)
                          <option value="{{$item->id}}">{{$item->comisaria}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2"><br />
                        <label>SITUACIÓN</label>
                        <select tabindex="27" class="form-control select2" style="width: 100%" name="id_situacion" id="id_situacion">
                          <option value="">--SELECCIONAR---</option>
                          @foreach($situacion as $item)
                          <option value="{{$item->id}}">{{$item->descripcion}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2"><br />
                        <label>FEC. ACCIDENTE</label>
                        <input tabindex="28" type="text" class="form-control datepicker" value="<?php echo date("d-m-Y",
                          strtotime($fechaServidor)); ?>" name="fecha_accidente" id="fecha_accidente" tabindex=""
                        autocomplete="off"/>
                      </div>
                      <div class="col-md-2"><br />
                        <label>HORA ACCIDENTE</label>
                        <input tabindex="29" type="text" class="form-control" value="<?php echo date("H:i:s"); ?>"
                        name="hora_accidente" id="hora_accidente" tabindex="1" data-date-end-date="0d"
                        autocomplete="off"/>
                      </div>
                      <div class="col-md-2"><br />
                        <label>DEPARTAMENTO</label>
                        <select tabindex="30" class="form-control select2" style="width: 100%" name="DepartamentoAc"
                          id="DepartamentoAc" onchange="getProvinciaAc();">
                          <option value="">--SELECCIONAR---</option>
                          @foreach($departamento as $f)
                          <option value="{{$f->id_departamento}}">{{$f->descripcion}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-3"><br />
                        <label>PROVINCIA</label>
                        <select tabindex="31" class="form-control select2" style="width: 100%" name="ProvinciaAc" id="ProvinciaAc"
                          onchange="getDistritoAc();">
                          <option value="">--SELECCIONAR---</option>
                        </select>
                      </div>
                      <div class="col-md-3"><br />
                        <label>DISTRITO</label>
                        <select tabindex="32" class="form-control select2" style="width: 100%" name="DistritoAc" id="DistritoAc">
                          <option value="">--SELECCIONAR---</option>
                        </select>
                      </div>
                      <div class="col-md-6"><br />
                        <label>LUGAR ACCIDENTE</label>
                        <input type="text" tabindex="33" class="form-control" value="" id="lugar_accidente" name="lugar_accidente"
                          tabindex="" style="text-transform:uppercase;"
                          onkeyup="javascript:this.value = this.value.toUpperCase();">
                      </div>
                      <div class="col-md-6"><br />
                        <label>UNIDAD POLICIAL</label>
                        <select tabindex="34" class="form-control select2" style="width: 100%" name="id_unidad" id="id_unidad">
                          <option value="">--SELECCIONAR---</option>
                          @foreach($unidad as $item)
                          <option value="{{$item->id}}">{{$item->descripcion_larga}}</option>
                          @endforeach
                        </select>
                      </div>

                    </div>
                  </div>
                </fieldset>
              </div>
              <!--#informacion de violencia-->

              <!--informacion de evacuados-->
              <div id="muestra-datos-evacuado" style="display: none;">
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>REGISTROS DE EVACUADO</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="col-md-2"><br />
                        <label>DEPARTAMENTO</label>
                        <select tabindex="35" class="form-control select2" style="width: 100%" name="id_departamento_evac"
                          id="id_departamento_evac">
                          <option value="">--SELECCIONAR---</option>
                          @foreach($departamento as $f)
                          <option value="{{$f->id_departamento}}">{{$f->descripcion}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2"><br />
                        <label>NUMERO DE OFICIO</label>
                        <input type="text" tabindex="36" class="form-control" value="" id="numero_oficio" name="numero_oficio"
                          tabindex="" style="text-transform:uppercase;"
                          onkeyup="javascript:this.value = this.value.toUpperCase();">
                      </div>
                      <div class="col-md-5"><br />
                        <label>UNIDAD POLICIAL</label>
                        <select tabindex="37" class="form-control select2" style="width: 100%" name="id_unidad_policial"
                          id="id_unidad_policial">
                          <option value="">--SELECCIONAR---</option>
                          @foreach($unidad as $item)
                          <option value="{{$item->id}}">{{$item->descripcion_larga}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-3"><br />
                        <label>SITUACION</label>
                        <select tabindex="38" class="form-control select2" style="width: 100%" name="id_situacion_evac"
                          id="id_situacion_evac">
                          <option value="">--SELECCIONAR---</option>
                          @foreach($situacion as $item)
                          <option value="{{$item->id}}">{{$item->descripcion}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-12"><br />
                        <label>DIAGNOSTICO INICIAL</label>
                        <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                            <tr>
                              <th width="90%"></th>
                              <th width="10%"></th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-md-12"><br />
                        <label>OBSERVACION EVACUADO</label>
                        <input tabindex="40" type="text" class="form-control" value="" id="observacion_evac" name="observacion_evac"
                          tabindex="" style="text-transform:uppercase;"
                          onkeyup="javascript:this.value = this.value.toUpperCase();">
                      </div>
                    </div>
                  </div>
                </fieldset>
              </div>
              <!--#informacion de evacuados-->

              <div class="form-group">
                <div class="col-md-6">
                  <a href="/all_triajes_atendidas_by_emergencia/{{ Auth::user()->id_farmacia }}"
                    class="form-control btn btn-danger" tabindex="99922">CANCELAR</a>
                </div>
                <div class="col-md-6">
                  <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921"
                    onclick="validateFrm()">GUARDAR</button>
                    <input type="hidden" name="hd_action" value="nuevo">
                    <input type="hidden" name="hd_tipo_beneficiario" id="hd_tipo_beneficiario" value="">
                    <input type="hidden" name="hd_foto" id="hd_foto" value="">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<script>
  $('#pills-tab a').on('click', function (e) {
  e.preventDefault()
  $(this).tab('show')
})
</script>



@include('frontend.includes.footer')
@endsection