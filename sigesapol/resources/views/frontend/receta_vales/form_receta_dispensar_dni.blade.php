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

.swal2-icon.swal2-warning {
    font-size: 15px;
  }


</style>

<script>
$(document).ready(function() {
  generarCodigoSiteds();
  validarTutorPaciente();
});

    // TAGSITEDS
    async function generarCodigoSiteds() {
      const receta = {!! json_encode($receta) !!};
      if (receta && (receta.codigo_siteds === null || receta.codigo_siteds === "" || receta.codigo_siteds === undefined)) {
        Swal.fire ({
            title:  "<div style='margin-top: 20px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading ()
            },
        })

        const id_ipress = {!! json_encode($id_ipress)!!};
        const dni_beneficiario = $("#dni_beneficiario").val();
        const codigoSitedsRecetaVale = await BuscarSiteds(dni_beneficiario, id_ipress);
        if (codigoSitedsRecetaVale && codigoSitedsRecetaVale.codigo_siteds) {
            $("#codigo_siteds").val(codigoSitedsRecetaVale.codigo_siteds);
            Swal.fire({
                icon: "success",
                title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS</span></div>",
                showConfirmButton: false,
                timer: '1500'
            })
            Swal.hideLoading();
            return codigoSitedsRecetaVale;
        }

        $("#codigo_siteds").prop("readonly", true);
        const establecimiento = {!! json_encode($establecimiento) !!}
        const tipodoc_ben = {!! json_encode($tipodoc_ben) !!}
        const codigo_ipress = establecimiento && establecimiento.codigo ? establecimiento.codigo : '00013591';
        const id_tiporeceta = $('#id_tiporeceta').val();
        const body = {
          "idRemitente": codigo_ipress,
          "nuRucRemitente": "",
          "idCorrelativo": receta.id,
          "tiDoPaciente": tipodoc_ben,
          "nuDoPaciente": receta.dni_beneficiario,
          "noResponsableAut": "",
          "noPaResponsableAut": "",
          "noMaResponsableAut": "",
          "nuDoResponsableAut": "",
          "coAdmisionista": receta.dni_beneficiario,
          "beMaxInicial": "0", 
          "coTiMoneda": "1", 
          "coPagoFijo": "0", 
          "coCalServicio": "VS", 
          "coPagoVariable": "100",
          "flagCaGarantia": "0",
          "deFlagCartaGarantia": "Sigesapol 0.1",
          "tipoAtencionId": id_tiporeceta,
          "servicioId": receta.id_consultorio
        }
        return new Promise((resolve, reject) => {
          $.ajax({
            url:'/ws-dirsapol-siteds',
            type:'POST',
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(body),
            success: (result, textStatus, jqXHR) => {
              if (result && result.nuAutorizacion) {
                $("#codigo_siteds").val(result.nuAutorizacion)
                if (result.nuAutorizacion.endsWith("T") || String(result.nuAutorizacion).length <= 6) {
                  Swal.fire({
                      icon: "warning",
                      title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>REGISTRO DE RECETA</span></div>",
                      html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Puede generar su código válido en la web oficial de SITEDS y reemplazar el código temporal en el sistema.</span></div>`,
                      confirmButtonText: 'ENTENDIDO',
                  });
                  $("#codigo_siteds").prop("readonly", false);
                  // 
                } else {
                  removeBtnGenerateSiteds();
                  Swal.fire({
                    icon: (parseInt(jqXHR.status) === 201 || parseInt(jqXHR.status) === 200) ? "success" : 'warning',
                      title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS</span></div>",
                      showConfirmButton: false,
                      timer: '1500'
                  });
                }
              }
              resolve(result);
            },
            error: function(xhr, status, error){
              $("#codigo_siteds").val("");
              $("#codigo_siteds").prop("readonly", false);
              //$("#btn_generate_siteds").prop("disabled", false);
              //addBtnGenerateSiteds();
              const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
              Swal.fire({
                  icon: 'error',
                  title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                  html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                  confirmButtonText: 'ENTENDIDO',
              });
              reject(xhr.responseJSON);
            }, 
            complete: function(xhr, status) {
              Swal.hideLoading();
            }
          })
        })
      }
    }

    function BuscarSiteds(dni_beneficiario,id_press) {
      return new Promise((resolve, reject) => {
          $.ajax({
              url: `/buscar_siteds_generado_hoy/${dni_beneficiario}/${id_press}`,
              dataType: "json",
              success: function(data) {
                  resolve(data);
              },
              error: function(xhr, status, error) {
                  reject(xhr.responseJSON);
              }
          });
      });
    }

    function updateCodeSiteds(recetaValeId, codigo_siteds) {
      const body = { codigo_siteds: codigo_siteds };
      $.ajax({
        url: `/receta-vale-actualizar-siteds/${recetaValeId}`,
        type:'PUT',
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(body),
        success: (result) => {
          console.log("result");
        }
      })
    }

    function addBtnGenerateSiteds() {
      if (!$('#btn_generate_siteds').length) {
        htmlBtn = `<span id="btnGenerateSiteds" class="input-group-btn">
          <button type="button" id="btn_generate_siteds" class="btn btn-sm btn-info" style="margin-top: -1px">Generar</button>
          </span>`;
        $('#divSiteds').addClass('input-group');
        $("#divSiteds").append(htmlBtn);
        
        $('#divSiteds').on('click', '#btn_generate_siteds', function() {
            generarCodigoSiteds();
        });
      }
    }

    function removeBtnGenerateSiteds() {
      $('#btnGenerateSiteds').remove();
      $('#btn_generate_siteds').off('click', generarCodigoSiteds);
      $('#divSiteds').removeClass('miClase');
    } 

    //Oficio 660-2024 DIVADT.DEPFAR DEL 03 AGOSTO 2024 - CASO Juan Carlos DI NATALE CHAVEZ
    //Oficio 15-2025 OFAD-AREREHUM-SOYS DEL 16 ENERO 2025 - CASO Daniel Gustavo PIMENTEL FAJARDO
    function validarTutorPaciente() {
      let receta = {!! json_encode($receta) !!};
      let paciente_nro_documentos = ["48995792", "43518991"];
      let paciente_documentos = ["DNI 48995792", "DNI 43518991"];
      let paciente_nombres = ["Juan Carlos DI NATALE CHAVEZ", "Daniel Gustavo PIMENTEL FAJARDO"];

      let tutor_nombres = ["Daniel Gustavo DI NATALE CHAVEZ", "Milagros Aurora PIMENTEL FAJARDO"];
      let tutor_documentos = ["DNI 09535691", "DNI 42577518"];

      for (let index = 0; index < paciente_nro_documentos.length; index++) {
        let nro_documento = paciente_nro_documentos[index];
        if (receta.dni_beneficiario == nro_documento) {
          bootbox.alert({
              message: "El tutor legal Autorizado para el recojo de Medicamentos del paciente <b>" + paciente_nombres[index] + "</b> con <b>" + paciente_documentos[index] + "</b> es <b style='color:red'>" + tutor_nombres[index] + "</b> identificado con <b style='color:red'>" + tutor_documentos[index] + "</b> (AUTORIZADO PARA EL RECOJO DE MEDICAMENTOS).",
              buttons: {
                  ok: {
                      label: 'VERIFICAR'
                  }
              }
          });
          break;
        }
      }
    }

    function completarConCeros(numero, longitud) {
      let numeroString = numero.toString();
      while (numeroString.length < longitud) {
        numeroString = '0' + numeroString;
      }
      return numeroString;
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
    $consul.select2({
        width: 'resolve' // need to override the changed default
    });
    
    $tur = $("#frmReceta").find("#turno");
    $tur.select2({
        width: 'resolve' // need to override the changed default
    });
    
  $id_tiporeceta = $("#frmReceta").find("#id_tiporeceta");
  $id_tiporeceta.select2({
        width: 'resolve' // need to override the changed default
    });
  
    $grado = $("#frmReceta").find("#grado");
  /*
    $grado.select2({
        width: 'resolve' // need to override the changed default
    });
    */
  
    $tipo = $("#frmReceta").find("#tipo_beneficiario");
    //$tipo.select2();
  /*
    $tipo.select2({
        width: 'resolve' // need to override the changed default
    });
   */
   
    $medicos = $('#frmReceta').find("#id_medico")
    //$medicos.select2();
    $medicos.select2({
        width: 'resolve' // need to override the changed default
    });

    //$tipo_receta = $('#frmReceta').find("#tipo_receta")
    //$tipo_receta.select2();
  /*
    $tipo_receta.select2({
        width: 'resolve' // need to override the changed default
    });
    */
  /*
  .on("select2:close", function(e) {
        var nextId = getNextFocusableFieldId(jQuery(this).attr('id'));
        jQuery('#' + nextId).focus().select();
    })
  */
  
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
    
    
    //obtenerReceta();
    
    //__autocomplete__AjaxBusqueda();
    var id_farmacia = $('#id_farmacia').val();
    //alert("oik");
    <?php //if ($modo_registro == 2 && $tipo_registro == 2){?>
    /*if(id_farmacia == 3){
      getTipoAtencion(1);
    }
    if(id_farmacia == 20){
      getTipoAtencion(3);
    }
    if(id_farmacia == 19){
      getTipoAtencion(2);
    }*/
    <?php //}?>
    
    //AddDiagnostico();
    //$('[data-toggle="tooltip"]').tooltip(); 

    /*
    $(document).keypress(function(e) {
      if(e.which == 13) {
        AddFila();
      }
    });
    */
    
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
            var obj = $(this);
            obj.parent().parent().remove();
        });
        
        $('#addRow').on('click', function () {
      AddFila();
        });
    
    $('#addDiagnostico').on('click',function(){
      AddDiagnostico();
    });
    
    <?php //if ($modo_registro != 1 && $tipo_registro != 2):?>
      //AddDiagnostico();
    <?php //endif;?>
    
    $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function () {
            var obj = $(this);
            obj.parent().parent().remove();
        });
    
    
    <?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
        $("#frmReceta").submit(function (e) {//Jose
      $.ajax({
        url: '/validacion_beneficiario_dias_productos/', 
        type:'POST',
        data:$("#frmReceta").serialize(),
        context: this,
        success: function(result){
          var msg   = "";
          var n     = 0;
          var flag  = true;
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
              msg += "<th>Cantidad entregada</th>";
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
          /*
            rows = $("#example tbody").children('tr');            
            //---- Validación de las cantidades prescritas y dispensadas-------------------------------------
            var i=0;
            $.each(rows, function (ii, oo) {                       
              medicina = $(oo).find("#cmbMedicamento").select2("data");                                              
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
            */
                                               this.submit();
          }else{
            /*flag = false;
            bootbox.alert(msg);*/
    this.submit();
          }
          
        }
      });
      return false;
            
        });
        <?php endif;?>
        
    
    <?php if(!isset($flag_receta_dias) || (isset($flag_receta_dias) && $flag_receta_dias == "")):?>
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
    <?php endif;?>
    
    
    
    
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
 /*
    function valida_existe_receta(){
        nro_receta='P-'+$('#frmReceta').find("#nro_receta").val()+'-'+$('#frmReceta').find("#digitos_adicionales").val();
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        id_consultorio=$('#frmReceta').find("#id_consultorio").val();
        var flagfiscalizador = "<?php echo $flagfiscalizador?>";
        if(flagfiscalizador != 1){
            validar(nro_receta, id_farmacia, id_consultorio);
        } else {
            validar_receta_fisanulada(nro_receta, id_farmacia, id_consultorio);
            validar(nro_receta, id_farmacia, id_consultorio);
        }
    }
*/
    $("#nro_receta").on("keydown", function (e) {
        nro_receta='P-'+$('#frmReceta').find("#nro_receta").val()+'-'+$('#frmReceta').find("#digitos_adicionales").val();
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        //alert(id_farmacia);
        var code = e.keyCode || e.which;
        if (code == '9' || code == '13') {
            var flagfiscalizador = "<?php echo $flagfiscalizador?>";
            if(flagfiscalizador != 1){
                validar(nro_receta, id_farmacia);
            } else {
                validar_receta_fisanulada(nro_receta, id_farmacia);
                validar(nro_receta, id_farmacia);
            }
        }
    });
    
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
            //var consultorio = $("#id_consultorio option:selected").text();
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
        //var consultorio = $("#id_consultorio option:selected").text();
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
        
    function validar_receta_fisanulada_rec(nro_receta, id_farmacia){
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
                    $('#frmReceta').find("#nro_receta").focus();
                    bootbox.alert("La receta: " + nro_receta+ " no fue anulada por su persona o no existe dicho número");
                    $('#btnReg').prop('disabled', false);
                    return false;
                } else {
                    validate_otros();
                }
            });     
        }
        
    function genera_receta_duplicado(){
            nro_receta='P-'+$('#frmReceta').find("#nro_receta").val();
            id_farmacia=$('#frmReceta').find("#id_farmacia").val();
            option = {                
                url: '/genera_receta_duplicado/' + nro_receta+ '/' + id_farmacia,
                type: 'GET',
                async:false,
                //dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                $('#frmReceta').find("#nro_receta").val($('#frmReceta').find("#nro_receta").val() + "." + data);
                $('#frmReceta').find("#valid_nro_receta").val('1');
                $('#nro_receta').prop('readonly', true);
                $('#digitos_adicionales').prop('readonly', true);
                $('#fecha_atencion').focus();
            });
    }
    
    //$("#btnReg").on("click", function () {//Jose
    function validateFrm(){

       var msg = "";
       /* var cod_siteds= $('#codigo_siteds').val();  
      
      if ($('#frmReceta').find("#codigo_siteds").val() == "") {
        bootbox.alert("Debe ingresar un Codigo siteds");
        $('#btnReg').prop('disabled', false);
        return false;
      }else{
        $("#codigo_siteds").val(completarConCeros(cod_siteds,12));  
      }
              
      var codSiteds= $('#codigo_siteds').val();  
      $.ajax({
        url:'{{url('validar_dato_siteds_ambulatorio/')}}/'+codSiteds,
        type:'GET',
        dataType:'JSON', 
          success: function(data){   
            if(data!=null){ 
              console.log('data[0].cantidad_siteds:'+ data.cantidad_siteds); 
              if(data.cantidad_siteds!=0){ 
                bootbox.alert("EL código SITEDS YA EXISTE, verifique que sea correcto y vuelva a intentarlo");
                $('#btnReg').prop('disabled', false);
                return false;
              } 
            }
          }
      }); */


        val_nro_rec=$('#frmReceta').find("#valid_nro_receta").val();
        val_nro_receta = $('#frmReceta').find("#nro_receta").val();
        var arrayval = val_nro_receta.split(".");
    
        if (val_nro_rec == "0") {
            if (isNumber(val_nro_receta)== false){
                bootbox.alert("El Número de Receta debe ser numérico");
                $('#btnReg').prop('disabled', false);
                return false;
            }
        }

        if (arrayval.length > 2) {
            bootbox.alert("El Número de Receta no puede tener más de un punto");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        
        $('#btnReg').prop('disabled', true);
        nro_receta='P-'+$('#frmReceta').find("#nro_receta").val()+'-'+$('#frmReceta').find("#digitos_adicionales").val();
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        id_consultorio=$('#frmReceta').find("#id_consultorio").val();
        var flagfiscalizador = "<?php echo $flagfiscalizador?>";
        if(flagfiscalizador != 1){
      <?php if ($modo_registro != 1 && $tipo_registro != 2):?>
            validar_rec(nro_receta, id_farmacia);
      <?php else:?>
      validate_otros();
      <?php endif;?>
        } else {
            validar_receta_fisanulada_rec(nro_receta, id_farmacia);
             //validar_rec(nro_receta, id_farmacia);
        }
    }
    
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
    //});
    function validate_otros(){
         
        if ($('#frmReceta').find("#nro_receta").val() == "") {
            //alert("Falta Digitar el Número de Receta");
            bootbox.alert("Falta Digitar el Número de Receta");
            $('#btnReg').prop('disabled', false);
            return false;
        }                
        if ($('#frmReceta').find("#digitos_adicionales").val() == "") {
            //alert("Falta Digitar el Número Receta");
            bootbox.alert("Falta Digitar el Número Receta");
            $('#btnReg').prop('disabled', false);
            return false;
        }   

        //if ($('#frmReceta').find("#id_consultorio").val() == "") {
    if ($('#frmReceta').find("input[name='id_consultorio']").val() == "") {
            //alert("Falta seleccionar el Consultorio");
            bootbox.alert("Falta seleccionar el Consultorio");
            $('#btnReg').prop('disabled', false);
            return false;
        }
    
    if ($('#frmReceta').find("input[name='dni_beneficiario']").val() == "") {
            //alert("Falta seleccionar el Consultorio");
            bootbox.alert("Falta buscar un Beneficiario");
            $('#btnReg').prop('disabled', false);
            return false;
        }
    
    var id_farmacia = $('#id_farmacia').val();
        if ($('#frmReceta').find("#nro_historia").val() == "" && id_farmacia!=19) {
            //alert("Falta seleccionar el grado");
            bootbox.alert("Falta ingresar la Historia Clínica");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        /*if ($('#frmReceta').find("#grado").val() == "") {
            bootbox.alert("Falta seleccionar el grado");
            $('#btnReg').prop('disabled', false);
            return false;
        }*/
    
    //if ($('#frmReceta').find("#id_diagnostico").val() == "") {
    var cant_diagnostico = $("input[name='id_diagnostico[]']").length;
    if (cant_diagnostico == 0) {
            bootbox.alert("Debe agregar minimo un diagnostico (CIE10)");
            $('#btnReg').prop('disabled', false);
            return false;
        }
    
    //alert(cant_diagnostico);
    var count_diag = 0;
    $("input[name='id_diagnostico[]']").each(function(){
      if(this.value=="")count_diag++;
    });
    
    if (count_diag > 0) {
            bootbox.alert("Falta agregar el diagnostico (CIE10)");
            $('#btnReg').prop('disabled', false);
            return false;
        }
    
    /*
    if ($('#frmReceta').find("#id_diagnostico").val() == "") {
            bootbox.alert("Falta agregar el diagnostico (CIE10)");
            $('#btnReg').prop('disabled', false);
            return false;
        }
    */
    
        farmacos=$('#frmReceta').find("#nro_stocks").val();
        if(farmacos==undefined){                    
            //alert("La receta no tiene productos agregados, corregir o cancelar el proceso");
            bootbox.alert("La receta no tiene productos agregados, corregir o cancelar el proceso");
            $('#btnReg').prop('disabled', false);
            return false;        
        }
        
        if ($('#frmReceta').find("#fecha_expedicion").val() == "") {
            //alert("Falta seleccionar la fecha de expedición de la receta");
            bootbox.alert("Falta seleccionar la fecha de expedición de la receta");
            $('#btnReg').prop('disabled', false);
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
                $('#btnReg').prop('disabled', false);
                //alert("comparacion año");
                return false; 
            } else{
            if (xYear == yYear){
                if (xMonth> yMonth){
                    //alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php //echo $rango_registro?> días atrás");
                    bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                    $('#btnReg').prop('disabled', false);
                    //alert("comparacion mes");
                    return false;  
                } else {
                    if (xMonth == yMonth){
                        if (xDay> yDay){
                            //alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php //echo $rango_registro?> días atrás");
                            bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                            $('#btnReg').prop('disabled', false);
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
    bootbox.alert("La fecha de atención solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                $('#btnReg').prop('disabled', false);
                return false; 
            }  
        else{  
            if (xYear == yYear){   
                if (xMonth> yMonth){  
                    //alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php //echo $rango_registro?> días atrás");
                    bootbox.alert("La fecha de atención solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                    $('#btnReg').prop('disabled', false);
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            //alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php //echo $rango_registro?> días atrás");
                            bootbox.alert("La fecha de atención solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                            $('#btnReg').prop('disabled', false);
                            return false;  
                        }
                    }
                }  
            }
        }
        //----------------------------------------------------------------------
        
        if ($('#frmReceta').find("#id_medico").val() == "") {
            //alert("Falta seleccionar el Profesional de Salud");
            bootbox.alert("Falta seleccionar el Profesional de Salud");
            $('#btnReg').prop('disabled', false);
            return false;
        }
                
        <?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
        if ($('#frmReceta').find(".tiempo_periodo").val() == "") {
            //alert("Falta digitar la cantidad prescrita");
    bootbox.alert("Falta digitar el tiempo(dias)");
                $('#btnReg').prop('disabled', false);
            return false;
        }
  <?php endif;?>
    //alert($('#frmReceta').find("#nro_med_solictados").val());
        if ($('#frmReceta').find("#nro_med_solictados").val() == "") {
            //alert("Falta digitar la cantidad prescrita");
            bootbox.alert("Falta digitar la cantidad prescrita");
            $('#btnReg').prop('disabled', false);
            return false;
        }
    
    /*
    var count_prescrito = 0;
    $("input[name='nro_med_solictados[]']").each(function(){
      if(this.value=="")count_prescrito++;
    });
    
    if (count_prescrito > 0) {
            bootbox.alert("Falta digitar la cantidad prescrita");
            $('#btnReg').prop('disabled', false);
            return false;
        }
    */
    
        <?php if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
        if ($('#frmReceta').find("#nro_med_entregados").val() == "") {
            //alert("Falta digitar la cantidad dispensada");
            bootbox.alert("Falta digitar la cantidad entregada");
            $('#btnReg').prop('disabled', false);
            return false;
        }
    <?php endif;?>
        /*
        if ($('#frmReceta').find("#nro_historia").val() == "") {
      bootbox.alert("Falta digitar el Nro. de historia");
            return false;
        }                 
        */
       
        var ind = $('#example tbody tr').length;
        
        if(ind > 0){
            //alert(ind);
            //alert($('#frmReceta').find("#cmbMedicamento1").val());
            var nind = ind-1;
            //alert(document.getElementById("cmbMedicamento"+nind).value);
            var cmbMedicamento = $("#cmbMedicamento"+nind).val();
            if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == ""){
                $('#example .deleteFila').eq(ind - 1).parent().parent().remove();
                ind = ind-1;
            }
            fila_medicamentos=$('#example tbody').children('tr');            
            for (i = 0; i < ind; i++) {
                fila_medicamento=fila_medicamentos[i];
                /*var valmedtiem = document.getElementById('nro_med_entregados'+i).value;
                if(valmedtiem == ""){
                    bootbox.alert("Uno de los productos no cuenta con la cantidad días");
                    return false;
                }
                var valmedpres = document.getElementById('nro_med_solictados'+i).value;
                if(valmedpres == ""){
                    bootbox.alert("Uno de los productos no cuenta con la cantidad prescrita");
                    return false;
                }*/
        <?php if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
                var valmedentre = $(fila_medicamento).find('#nro_med_entregados').val();
                if(valmedentre == ""){
                    bootbox.alert("Uno de los productos no cuenta con la cantidad entregada");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }
        <?php endif;?>
            }
        }
        submitFrm();
        return false;
    };
    
    function submitFrm() {
    
        if ($('#frmReceta').find("#nro_med_solictados").val() >= 10) {
            bootbox.confirm({
                message: "Aviso: Revise la(s) cantidad(s) prescrita(s) y dispensada(s) antes de registrar. ÓDesea Grabar?.",
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
                      //generarCodigoSiteds()
                      //.then((result) => {
                      //    if (result && result.nuAutorizacion) {
                              $("#frmReceta").submit();
                      //    }
                      //}).catch((err) => {
                      //    $('#btnReg').prop('disabled', false);
                      //});
                    } else {
                        $('#btnReg').prop('disabled', false);
                    }
                }
            });
        } else {
            bootbox.confirm({
                message: "Revise que los datos de la receta se encuentren correctos antes de registrar. ¿Desea Grabar?.",
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
    }
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
        <?php if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>    
                    fila_medicamento=fila_medicamentos[idval];
                    var valante = $(fila_medicamento).find('#nro_med_entregados').val();
                    if(valante == ""){
                        bootbox.alert("Ingrese cantidad entregada en el producto anterior");
                        return false;
                    }
        <?php endif;?>
          
                nuevalperiodo = document.getElementById('tiempo_periodo'+idval).value;
    }
    
    var item_producto   = "";
    $('#cmbMedicamentoTemp option').each(function(){
      item_producto += "<option value="+$(this).val()+" ru='"+$(this).attr("ru")+"'>"+$(this).html()+"</option>"  
    });
    
    newRow +='<tr>';
    //newRow +='<div id="dvMedicamento"></div>';
    
    var id_farmacia = $('#id_farmacia').val();
    if(id_farmacia == 18 || id_farmacia == 25)newRow +='<td>'+(ind+1)+'</td>';
    
    newRow +='<td><select class="form-control cmbMedicamento" id="cmbMedicamento'+ind+'" ind="'+ind+'" tabindex="'+tabindex+'" name="id_producto[]" style="width: 500px">'+item_producto+'</select></td>';
    
    //newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
    newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
    
    newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
    <?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
    newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="'+(tabindex+1)+'" required="" id="tiempo_periodo'+ind+'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="'+nuevalperiodo+'"/>';
    <?php endif;?>
    newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
    newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
                newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
    newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="'+(tabindex+2)+'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados'+ind+'" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:50px" /></td>';
    
    <?php if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
    newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
    <?php endif;?>
    
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
        <?php if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
          AddFila();
        <?php else:?>
          validproductoseleccionado({{Auth::user()->id_farmacia}},this);
        <?php endif;?>
      }
    });
    
    
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
        
        var cantidad = data.cantidad;
        var cantidadEstablecimiento = data.cantidadEstablecimiento;
        var cantidadAlmacen = data.cantidadAlmacen;
                $(cmb).closest("tr").find(".limpia_text").val("");                
        //$(cmb).closest("tr").find("#nro_stocks").val(data);
        $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
        $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
        $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
        <?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
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
                
  }
    
  function AddDiagnostico(){
    var newRow = "";
    var ind = $('#tblDiagnostico tbody tr').length;
    
    if(ind > 0){
      $('.cmbMedicamento').each(function(){
        var ind_tmp = $(this).attr("ind");
        if(ind_tmp => ind){
          ind=Number(ind_tmp)+1;
        }
      });
                        
      fila_medicamentos=$('#tblDiagnostico tbody').children('tr');
      rowIndex=$('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento=fila_medicamentos[idval];
      /*
      var valante = $(fila_medicamento).find('#nro_med_entregados').val();
      if(valante == ""){
        bootbox.alert("Ingrese cantidad entregada en el producto anterior");
        return false;
      }
      */
    }
    
    newRow +='<tr>';
    newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="id_diagnostico'+ind+'" name="id_diagnostico[]" value=""></td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
    $('#tblDiagnostico tbody').append(newRow);
    /*
    $("#cmbMedicamento"+ind).select2({max_selected_options: 4}).focus();
    
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
      
    });
        */
    $("#nombre_diagnostico"+ind).autocomplete({
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
        $("#id_diagnostico"+ind).val(id_diagnostico);
        $("#nombre_diagnostico"+ind).prop("readonly",true);
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
  
     
        function show_Atenciones(dni_beneficiario){
            //var dni_beneficiario = dni;
      //var dni_beneficiario = $('#dni_beneficiario').val();
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
                        newOption += "<tr><td><small>" + oo.nro_receta + "</small></td><td><small>" + oo.fecha_expedicion + "</small></td><td><small>" + oo.fecha_registro + "</small></td><td><small>" + oo.nom_consultorio + "</small></td><td><small>" + oo.nom_farmacia + "</small></td><td><small>" + oo.apepat_medico + " " + oo.apemat_medico + " " + oo.nom_medico + "</small></td><!--td><small>" + oo.nom_diagnostico + "</small></td-->";
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
                        newOption += "<tr><td class=\"text-center\"><small>" + oo.codigo + "</small></td><td><small>" + oo.nombre + "</small></td><td class=\"text-center\"><small>" + oo.abreviatura + "</small></td><td class=\"text-center\"><small>" + oo.cantidad_prescrita + "</small></td><td class=\"text-center\"><small>" + oo.cantidad_dispensada + "</small></td><td class=\"text-center\"><small>" + oo.tiempo_periodo + "</small></td>";
                    });
                    $('#muestra-productos').html(newOption);
                }
            });
            $('#showProductosReceta').modal();
        }
        
        function valid_servicios_by_mes(){
        if($('#id_medico').val()=="") return false;
        var dni_beneficiario = $('#dni_beneficiario').val();
        var id_consultorio = $('#id_consultorio').val();
        var fecha_atencion = $('#fecha_atencion').val();
        var id_medico = $('#id_medico').val();
            $.ajax({
                url: '/obtenerCantAtencionesMesActualbyBeneficiario/' + dni_beneficiario+ '/' + id_consultorio+ '/' + fecha_atencion+ '/' + id_medico,
                success: function (result) {
                    result = result.split('|', 5);
                    maxnrousu = Number(result[0]);
                    maxnrores = Number(result[1]);
                    validaten = Number(result[2]);
                    totaten = Number(result[3]);
                    totmismaesp = Number(result[4]);
                    
                    var newOption = "";
                    newOption += "Existen <b><a href='#' onclick=valid_det_servicios_by_mes() style='text-decoration: none;'>" + totaten + "</a></b> atenciones en el mes de la fecha de expedición seleccionada<br/>";
                    newOption += "Existen <b>" + totmismaesp + "</b> recetas con la misma especialidad en el mes de la fecha de expedición seleccionada<br/>";
                    
                    $('#msj-atenciones').removeClass("alert alert-success").addClass("alert alert-danger");
                    $('#det-msj-atenciones').html(newOption);
                  
                    if (validaten >= maxnrousu){
                      if(id_consultorio!=104){
                            if (validaten == 3 && totmismaesp >= 1){
                                $("#addRow").prop("disabled", false);
                                $("#btnReg").prop("disabled", false);  
                            } else{
                                var flagresponsablefarmacia = "<?php echo $flagresponsablefarmacia?>";
                                if(flagresponsablefarmacia != 1){
                                    $("#addRow").prop("disabled", true);
                                    $("#btnReg").prop("disabled", true);
                                } else {
                                    if (validaten < maxnrores){//maxnrores sacar del procedimiento almacenado
                                        $("#addRow").prop("disabled", false);
                                        $("#btnReg").prop("disabled", false);                                
                                    } else {
                                        $("#addRow").prop("disabled", true);
                                        $("#btnReg").prop("disabled", true);  
                                    }
                                }
                            }
                       }  
                      $('#msj-atenciones').show();
                    } else {
                        $('#msj-atenciones').removeClass("alert alert-danger").addClass("alert alert-success");
                        $('#det-msj-atenciones').html(newOption);
                        $("#addRow").prop("disabled", false);
                        $("#btnReg").prop("disabled", false);
                        $('#msj-atenciones').show();
                    }
                }
               
            });
        }
        
    function ShowByServicioAndId_farmacia(){
      var dni_beneficiario = $('#dni_beneficiario').val();
      var id_farmacia = $('#id_farmacia').val();
    
            $.ajax({
                url: '/obtenerCantInterconsultas/' + dni_beneficiario+ '/' + id_farmacia,
                success: function (result) {
          if(result != null && result != ""){
            var newOption = "";
            newOption += "Existen interconsulta(s), en<br/>";
            for(var key in result) {
              newOption += "<a href='#' onclick='valid_det_interconsultas_servicios("+result[key].id_consultorio+")' style='text-decoration: none;'>"+result[key].consultorio+"</a>  ( "+result[key].dif_dias+" dias) ";
            }
            $('#msj-atenciones').removeClass("alert alert-success").addClass("alert alert-danger");
            $('#det-msj-atenciones').html(newOption);
          } else {
                        //$('#msj-atenciones').removeClass("alert alert-danger").addClass("alert alert-success");
                        //$('#det-msj-atenciones').html(newOption);
                        $('#msj-atenciones').hide();
                    }
                }
               
            });
        }
    
    function valid_det_interconsultas_servicios(id_consultorio){
            var dni_beneficiario = $('#dni_beneficiario').val();
            var fecha_atencion = $('#fecha_atencion').val();
            var id_medico = $('#id_medico').val();
            var newOption = "";
            $.ajax({
                url: '/obtenerDetAtencionesInterconsultabyBeneficiario/' + dni_beneficiario+ '/' + id_consultorio,
                success: function (result) {
                    var newOption = "";
                    var i = 1;
                    var colspan = "";
                    var nroaten = 1;
                    $(result).each(function (ii, oo) {
                        var colspan = "rowspan="+oo.cant_receta;
                        newOption += "<tr>";
                        if(i == 1){
                            newOption += "<td class=\"text-center\" "+ colspan +" style=\"vertical-align: middle\"><b>" + nroaten + "</b>";
                            nroaten = nroaten + 1;
                        }
                        var btn = "<button type=\"button\" data-toggle=\"modal\" class=\"btn btn-primary btn-xs\" onclick=\"show_Det_Atenciones('" + oo.id_receta + "')\"><i class=\"fa fa-list\"></i></button>";
                        newOption += "<td><small>" + oo.nom_farmacia + "</small></td><td><small>" + oo.fec_expedicion + "</small></td><td><small>" + oo.nom_consultorio + "</small></td><td><small>" + oo.apepat_medico + " " + oo.apemat_medico + " " + oo.nom_medico + "</small></td><td>"+oo.nro_receta+"</td><td>"+oo.fec_dispensacion+"</td><td class=\"text-center\" style=\"vertical-align: middle\">"+btn+"</td>";
                        newOption += "</tr>";
                        
                        if(i == oo.cant_receta){
                                i=1;
                                colspan = "";
                        }else{
                                i = i + 1;
                        };
                        
                    });
                    $('#muestra-det-atenciones').html(newOption);
                }
            });
            $('#showAtencionesDetMensual').modal();
            return false;
        }
    
    
        function valid_det_servicios_by_mes(){
            var dni_beneficiario = $('#dni_beneficiario').val();
            var id_consultorio = $('#id_consultorio').val();
            var fecha_atencion = $('#fecha_atencion').val();
            var id_medico = $('#id_medico').val();
            var newOption = "";
            $.ajax({
                url: '/obtenerDetAtencionesMesActualbyBeneficiario/' + dni_beneficiario+ '/' + id_consultorio+ '/' + fecha_atencion+ '/' + id_medico,
                success: function (result) {
                    var newOption = "";
                    var i = 1;
                    var colspan = "";
                    var nroaten = 1;
                    $(result).each(function (ii, oo) {
                        var colspan = "rowspan="+oo.cant_receta;
                        newOption += "<tr>";
                        if(i == 1){
                            newOption += "<td class=\"text-center\" "+ colspan +" style=\"vertical-align: middle\"><b>" + nroaten + "</b>";
                            nroaten = nroaten + 1;
                        }
                        var btn = "<button type=\"button\" data-toggle=\"modal\" class=\"btn btn-primary btn-xs\" onclick=\"show_Det_Atenciones('" + oo.id_receta + "')\"><i class=\"fa fa-list\"></i></button>";
                        newOption += "<td><small>" + oo.nom_farmacia + "</small></td><td><small>" + oo.fec_expedicion + "</small></td><td><small>" + oo.nom_consultorio + "</small></td><td><small>" + oo.apepat_medico + " " + oo.apemat_medico + " " + oo.nom_medico + "</small></td><td>"+oo.nro_receta+"</td><td>"+oo.fec_dispensacion+"</td><td class=\"text-center\" style=\"vertical-align: middle\">"+btn+"</td>";
                        newOption += "</tr>";
                        
                        if(i == oo.cant_receta){
                                i=1;
                                colspan = "";
                        }else{
                                i = i + 1;
                        };
                        
                    });
                    $('#muestra-det-atenciones').html(newOption);
                }
            });
            $('#showAtencionesDetMensual').modal();
            return false;
        }
 
    
    function show_stock_establecimiento(id_producto){

      var id_user={{Auth::user()->id}};
            var newOption = "";
            $.ajax({
                url: '/cargarstock_establecimiento/' + id_producto + '/' + id_user,
                success: function (result) {
                    var newOption = "";
                    $(result).each(function (ii, oo) {
                        
                        newOption += "<tr><td>" + (ii + 1) + "</td>";
            newOption += "<td>" + oo.farmacia + "</td>";
                        newOption += "<td>" + oo.codigo_producto + "</td>";
            newOption += "<td>" + oo.nombre_producto + "</td>";
            newOption += "<td>" + oo.abreviatura + "</td>";
                        newOption += "<td>" + oo.total + "</td></tr>";
                        
                    });
                    $('#muestra-stock').html(newOption);
                }
            });
            $('#showStockEstablecimiento').modal();
        }
    
 
        function validproductoseleccionado(id_producto) {//Jose
                        var dni_beneficiario = $('#dni_beneficiario').val();
                        var nro_dias = 30;
                        //var objTr = $(btn).closest("tr");
                        //var id_producto = objTr.find(".cmbMedicamento").val();
                        $.ajax({
                        <?php if(Auth::user()->id_farmacia==128):?>
                          url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
                        <?php else:?>
                          url: '/validacion_beneficiario_dias_by_producto/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
                        <?php endif;?>
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
              <?php if(Auth::user()->id_farmacia==128):?>
              msg += "<th>Farmacia(Dias)</th>";
              <?php endif;?>
              msg += "<th>Dias Transcurridos</th>";
              msg += "<th>Dias Pendientes</th>";
              msg += "<th>Cantidad Prescrita</th>";
              msg += "<th>Cantidad entregada</th>";
                            msg += "</tr>";
                          msg += "</thead>";
              msg += "<tbody>";
            }
            
            msg += "<tr><td>"+(result[key].farmacia)+"</td>";
            msg += "<td>"+(result[key].nro_receta)+"</td>";
            msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
            msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
            msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
            <?php if(Auth::user()->id_farmacia==128):?>
            msg += "<td>"+(result[key].dia_farmacia)+"</td>";
            <?php endif;?>
            msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
            <?php if(Auth::user()->id_farmacia==128):?>
              if(result[key].dia_farmacia!=0){
                msg += "<td>"+(result[key].dia_farmacia - result[key].dias_transcurridos)+"</td>";
              }
              else{
                msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
              }
            <?php else:?>
              msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
            <?php endif;?>
            msg += "<td>"+(result[key].cantidad_prescrita)+"</td>";
            msg += "<td>"+(result[key].cantidad_dispensada)+"</td></tr>";
            n++;
                                            }
                                if(msg == ""){
                                    //verlotes(id_farmacia, btn);      
                                } else {
                                            //bootbox.alert(msg);
                                            bootbox.confirm({
                                                title: "Alerta Producto Encontrado",
                                                message: msg,
                                                callback: function (result) {
                                                    if(result==true){
                                                        //verlotes(id_farmacia, btn);
                                                    }
                                                }
                                            });
                                }
                        }
                        });
                    }
                    
            function show_producto_temp(){
                var tipo_receta = $('#tipo_receta').val();
                var id_farmacia = $('#id_farmacia').val();
                var id_consultorio = $('#id_consultorio').val();
                $.ajax({
                    url: '/obtenerProductosFarmaciasByNivel/'+id_farmacia+'/' + tipo_receta+'/'+id_consultorio,
                    success: function (result) {
                        var newOption = "";
                        $(result).each(function (ii, oo) {
                            newOption += '<option value="'+oo.id+'" ru="'+oo.ru+'">'+oo.codigo+ '-' +oo.nombre+', '+oo.abreviatura+'</option>';
                        });
                        $('#cmbMedicamentoTemp').html(newOption)
                    }
                });
            }
      
            function select2Focus() {
                    var select2 = $(this).data('select2');
                    setTimeout(function() {
                            if (!select2.opened()) {
                                    select2.open();
                            }
                    }, 0);  
            }

function mostrarproductofarmacia(id_producto) {
    
    $.ajax({
    url: '/producto_por_farmacia/' + id_producto,
    success: function (result) {
      var msg   = "";
      var n     = 0;
      var flag  = true;
      for(var key in result) {
        if(n == 0){
          msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>Hay stock del producto en otra farmacia</h1>";              
          msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                      msg += "<thead>";
                        msg += "<tr>";
          msg += "<th>Farmacia</th>";
          msg += "<th>Producto</th>";
          msg += "<th>Cantidad</th>";
                        msg += "</tr>";
                      msg += "</thead>";
          msg += "<tbody>";
        }
              
        msg += "<tr><td>"+(result[key].farmacia)+"</td>";
        msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
        msg += "<td>"+(result[key].stock)+"</td></tr>";
        n++;
      }
      if(msg == ""){
          //verlotes(id_farmacia, btn);      
      } 
      else {
        bootbox.confirm({
            title: "Stock en el establecimiento",
            message: msg,
            callback: function (result) {
                if(result==true){
                    
                }
            }
        });
      }
    }
  });
  
  }
  function getMedico(){
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");
            
            if($('#turno').val()=="") return false;
        
    var id_ipress     = $('#id_ipress').val();
    var id_consultorio  = $('#id_consultorio').val();
    var turno     = $('#turno').val();
    var fecha_admision  = $('#fecha_atencion').val();
    
    $.ajax({
      url: '/obtener_medico_por_idipress_idconsultorio_turno/' + id_ipress+ '/' + id_consultorio+'/'+turno + '/' + fecha_admision,
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
        
    var id_ipress     = $('#id_ipress').val();
    var id_consultorio  = $('#id_consultorio').val();
    //var tipo_receta   = $('#tipo_receta').val();
    var id_tiporeceta   = $('#id_tiporeceta').val();
    
    var id_farmacia     = $('#id_farmacia').val();
      var nivel     = $('#id_nivel').val();
    var x  = $('#nro_interconsultas').val();
    var dni = $('#dni_beneficiario').val();
      var interconsulta = $('#interconsulta').val();
    //if(tipo_receta == "AMBULATORIO"){
    
    /******************************/
    /*
    if(id_tiporeceta == "3" && nivel == "3"){ 
    
      //if(x==0){ //SIN INTERCONSULTA
      if(interconsulta=='SI'){ //SIN INTERCONSULTA
         $.ajax({
             url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
             success: function(result){                    
             var newOption = "";
             newOption = "<option value=''>--Seleccionar--</option>";
             $(result).each(function (ii, oo) {
             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
             });                
             $('#cmbMedicamentoTemp').html(newOption);
             }
           });
      }
      else
      {
         $.ajax({
             url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + id_consultorio+ '/' + dni,
             success: function(result){                    
             var newOption = "";
             newOption = "<option value=''>--Seleccionar--</option>";
             $(result).each(function (ii, oo) {
             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
             });                
             $('#cmbMedicamentoTemp').html(newOption);
             }
         });
      
      }
    
    }else if(id_tiporeceta == "3" && nivel != "3"){
    */
    
    if(id_tiporeceta == "3"){ 
      
      if(interconsulta=='SI'){
        if (id_consultorio==104){
        id_consultorio2=104
        }
        else
        {
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
          $('#cmbMedicamentoTemp').html(newOption);
          }
        }); 
  
      }
      else
      {  
        
        if(x==0){ //SIN INTERCONSULTA
        if (id_consultorio==104){
          $.ajax({
          url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 104,
          //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
          success: function(result){                    
            var newOption = "";
            newOption = "<option value=''>--Seleccionar--</option>";
            $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
            });                 
            $('#cmbMedicamentoTemp').html(newOption);
          }
          });
        }
        else
        {
          if (id_consultorio==21){
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
            //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
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
          else
          {
          if (id_consultorio==72){
            $.ajax({
              //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
              url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 72,
              success: function(result){                    
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });                 
              $('#cmbMedicamentoTemp').html(newOption);
              }
            });
          }
          else
          {
            $.ajax({
              url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
              //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
              success: function(result){                    
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });                 
              $('#cmbMedicamentoTemp').html(newOption);
              }
            });  
          }
          
          }
        }
        }
        else
        {
        if (id_consultorio==104){
          $.ajax({
          url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
          success: function(result){                    
            var newOption = "";
            newOption = "<option value=''>--Seleccionar--</option>";
            $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
            });                 
            $('#cmbMedicamentoTemp').html(newOption);
          }
          });
        }
        else
        {
          if (id_consultorio==21){
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 21 + '/' + dni,
            success: function(result){                    
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $(result).each(function (ii, oo) {
              newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
            });                 
            $('#cmbMedicamentoTemp').html(newOption);
            }
          });
          }
          else
          {
          if (id_consultorio==72){
            $.ajax({
              //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
              url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 72,
              success: function(result){                    
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });                 
              $('#cmbMedicamentoTemp').html(newOption);
              }
            });
          }
          else
          {
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 105+ '/' + dni,
              success: function(result){                    
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });                 
              $('#cmbMedicamentoTemp').html(newOption);
              }
            });  
          }
          
          }
        }  
        }          
      }       
    }else{
    
      //alert(id_tiporeceta);
      if(id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia!=18){
      
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
      
      if(id_tiporeceta == 5){
      
        $.ajax({
          url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/110',
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
      
      //}
      
     }
     
     
     if(id_tiporeceta != 1 || id_farmacia == 22){
  
      $.ajax({
      url: '/obtener_medico_por_idipress_idconsultorio_solo_emergencia/' + id_ipress+ '/' + id_consultorio,
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


}


  function getLimpia(){
  
    var interconsulta = $('#interconsulta').val();
    
    $('#dia_tto').val('');
    if(interconsulta=='SI')
      $('#dia_tto').prop('disabled', false);
    else
      $('#dia_tto').prop('disabled', true);
    
    $("#id_consultorio").val('').trigger("change");
    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";
    $('#id_medico').html(newOption);
    $("#id_medico").val('').trigger("change");    
    $('#example tbody').html('');    
    if($('#id_consultorio').val()=="") return false;        
    var id_ipress     = $('#id_ipress').val();
    var nivel     = $('#nivel').val();
    var id_consultorio  = $('#id_consultorio').val();
    var tipo_receta   = $('#tipo_receta').val();    
    var dni = $('#dni_beneficiario').val();
    
  }
  
  
  function getMedicoAnestesiologo(){
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_anestesiologo').html(newOption);
            //$("#id_anestesiologo").val('').trigger("change");

            var id_ipress     = $('#id_ipress').val();
            //var id_consultorio  = $('#id_consultorio2').val();

            $.ajax({
              //url: '/obtener_medico_por_idipress_idconsultorio_turno_anestesiologo/' + id_ipress+ '/' + id_consultorio,
        url: '/obtener_medico_por_idipress_idconsultorio_turno_anestesiologo/' + id_ipress+ '/72',
              success: function(result){                    
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                  newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
                });                 
                $('#id_anestesiologo').html(newOption);
                $("#id_anestesiologo").select2({max_selected_options: 4});
              
              }
            });
        
    }
  
  function getTipoAtencion(id_tiporeceta){
    
    if(id_tiporeceta == undefined || id_tiporeceta == "undefined" || id_tiporeceta == "" || id_tiporeceta == "null" || id_tiporeceta == null)id_tiporeceta  = $('#id_tiporeceta').val();
    else $('#id_tiporeceta').val(id_tiporeceta).trigger("change");
    
    var id_farmacia   = $("#id_farmacia").val();
    var nivel         = $('#id_nivel').val();
    $('#divTurno').show();
    $('#divMedico').attr("class","col-md-4").show();
    $('#divServicio').attr("class","col-md-6").show();
    $('#divCentroQuirurgico').hide();
    $('#divReceta').attr("class","col-md-3");
    $('#divAno').attr("class","col-md-2");
    $('#divNroGuia').attr("class","col-md-2");
    $('#divIntercon').attr("class","col-md-2");
    $('#divGuia').hide();
    $('#divVia').hide();
    $('#divFinalVia').hide();
    $('#divInterconsulta').hide();
    $('#nro_historia').attr("readonly",true);
    
    if(id_tiporeceta==2){
      $('#divTurno').hide();
      $('#divMedico').attr("class","col-md-6");
    }
    if(id_tiporeceta==3 && nivel == 3){
      $('#divGuia').show();
      $('#divReceta').attr("class","col-md-2");
      $('#divAno').attr("class","col-md-1");
    }
    if(id_tiporeceta==4){
      $('#divCentroQuirurgico').show();
      $('#divTurno').hide();
      $('#divServicio').attr("class","col-md-4");
      getMedicoAnestesiologo();
    }
    if(id_tiporeceta==5 || id_farmacia==22){
      $('#divTurno').hide();
      $('#divMedico').attr("class","col-md-6");
      $('#nro_historia').attr("readonly",false);
      $('#nro_historia').attr("tabindex",4);
    }
    
    if(id_tiporeceta==3 && nivel == 2){
      //$('#divGuia').show();
      //$('#divReceta').attr("class","col-md-2");
      //$('#divAno').attr("class","col-md-1");
      
      //$('#divInterconsulta').show();
      $('#divTurno').hide();
      $('#divServicio').attr("class","col-md-4");
      $('#divMedico').attr("class","col-md-5");
    }
    
    if(id_tiporeceta==3 && nivel == 3){
      $('#divGuia').show();
      $('#divNroGuia').attr("class","col-md-2");
      $('#divReceta').attr("class","col-md-2");
      $('#divIntercon').attr("class","col-md-2");
      $('#divAno').attr("class","col-md-1");
      
      $('#divInterconsulta').show();
      $('#divTurno').hide();
      //$('#divMedico').attr("class","col-md-6");
      $('#divServicio').attr("class","col-md-4");
      $('#divMedico').attr("class","col-md-5");
    }
    
    if(id_tiporeceta==6){
      $('#divVia').show();
      $('#divTurno').hide();
      $('#divServicio').attr("class","col-md-3");
      $('#divMedico').attr("class","col-md-3");
      $('#nro_historia').attr("readonly",false);
      $('#nro_historia').attr("tabindex",4);
    }
    
    //if(id_tiporeceta==8){
      //$('#divTurno').hide();
      //$('#divMedico').attr("class","col-md-6");
    //}
    
    //if(id_tiporeceta == 3){
      /*
      var inputConsultorio = "";
      inputConsultorio += '<input type="text" readonly="" value="" name="consultorio" id="consultorio" class="form-control" style="text-align:left">';
      inputConsultorio += '<input type="hidden" value="" name="id_consultorio" id="id_consultorio">';
      inputConsultorio += '<input type="hidden" value="" name="id_admision" id="id_admision">';
      $("#divServicioCol").html(inputConsultorio);
      
      var inputMedico = "";
      inputMedico += '<input type="text" readonly="" value="" name="medico" id="medico" class="form-control" style="text-align:left">';
      inputMedico += '<input type="hidden" value="" name="id_medico" id="id_medico">';
      $("#divMedicoCol").html(inputMedico);
      
      var inputTurno = "";
      inputTurno += '<input type="text" readonly="" value="" name="nombre_turno" id="nombre_turno" class="form-control" style="text-align:left">';
      inputTurno += '<input type="hidden" value="" name="turno" id="turno">';
      $("#divTurnoCol").html(inputTurno);
      */
    //}else{
    
      var selectConsultorio = "";
      var item_consultorio = "";
      if(id_tiporeceta==2 && id_farmacia!=22){
        selectConsultorio += '<input type="text" readonly="" value="EMERGENCIA" name="consultorio" id="consultorio" class="form-control" style="text-align:left">';
        selectConsultorio += '<input type="hidden" name="id_consultorio" id="id_consultorio" value="76">';
        $('#divServicioCol').html(selectConsultorio);
        getProducto();
      }else if(id_tiporeceta==8){
        selectConsultorio += '<input type="text" readonly="" value="ONCOHEMA" name="consultorio" id="consultorio" class="form-control" style="text-align:left">';
        selectConsultorio += '<input type="hidden" name="id_consultorio" id="id_consultorio" value="104">';
        $('#divServicioCol').html(selectConsultorio);
        getProducto();
      }else{
        $('#id_consultorio_temp option').each(function(){
          item_consultorio += "<option value='"+$(this).val()+"'>"+$(this).html()+"</option>" 
        });
        
        var metodo = "";
        //if(id_tiporeceta == 1 || id_tiporeceta == 3 || id_tiporeceta == 5)
        metodo = "onchange='getProducto();'";
        
        selectConsultorio += '<select class="form-control" id="id_consultorio" name="id_consultorio" tabindex="6" '+metodo+' style="width:100%">'+item_consultorio+'</select>';
        $('#divServicioCol').html(selectConsultorio);
        $("#id_consultorio").select2({width: 'resolve'});
        
      }
      
      var selectMedico = "";
      
      <?php if ($modo_registro == 1 && $tipo_registro == 1):?>
        selectMedico += '<input type="text" readonly="" value="<?php echo (isset($turno->paterno))?$turno->paterno." ".$turno->materno." ".$turno->nombre:""?>" name="medico" id="medico" class="form-control" style="text-align:left">';
        selectMedico += '<input type="hidden" name="id_medico" value="<?php echo (isset($turno->id_medico))?$turno->id_medico:""?>">';
        $('#divMedicoCol').html(selectMedico);
      <?php else:?>
        var method = "";
        if(id_tiporeceta == 1)method = "valid_servicios_by_mes();";
        var item_medico = '<option value="">--Seleccionar--</option>';
        selectMedico += '<select class="form-control" id="id_medico" name="id_medico" tabindex="8" style="width:100%" onchange="'+method+'AddFilaMed();">'+item_medico+'</select>';
        $('#divMedicoCol').html(selectMedico);
        $("#id_medico").select2({width: 'resolve'});
      <?php endif;?>
      
      var selectTurno = "";
      
      <?php if ($modo_registro == 1 && $tipo_registro == 1):?>
        selectTurno += '<input type="text" readonly="" value="<?php echo (isset($turno->nombre_turno))?$turno->nombre_turno:""?>" name="nombre_turno" id="nombre_turno" class="form-control" style="text-align:left">';
        selectTurno += '<input type="hidden" name="turno" value="<?php echo (isset($turno->turno))?$turno->turno:""?>">';
        $('#divTurnoCol').html(selectTurno);
      <?php else:?>
        var item_turno = '<option value="">--Seleccionar--</option>';
        item_turno += '<option value="M">MAÑANA</option>';
        item_turno += '<option value="T">TARDE</option>';
        item_turno += '<option value="N">NOCHE</option>';
        selectTurno += '<select class="form-control" id="turno" name="turno" tabindex="7" style="width:100%" onchange="getMedico()">'+item_turno+'</select>';
        $('#divTurnoCol').html(selectTurno);
        $("#turno").select2({width: 'resolve'});
      <?php endif;?>
      
    //}
    
    
  }
  
    $(document).ready(function () {
    /*
        var idgrado = "<?php //echo trim($beneficiario->grado);?>";
        if (idgrado == ""){
            $("#grado").val('NINGUNO').trigger("change");
        } else {
            $("#grado").val(idgrado).trigger("change");
        }
    */
    
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
            
      /*
            $("#id_consultorio").val('').trigger("change");
            $("#turno").val('').trigger("change");
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");
            */
      
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
    
  function obtenerReceta(){
  
        //var nro_receta = $("#nro_receta").val();
    //var id_farmacia = $("#id_farmacia").val();
    
    var nro_receta = 37;
    var id_farmacia = 3;
    
    $.ajax({
      url: '/obtener_receta/' + id_farmacia + '/' + nro_receta,
      dataType: "json",
      success: function(result){
        var medico = result.receta.paterno+" "+result.receta.materno+" "+result.receta.nombre;
        var beneficiario = result.receta.paterno_beneficiario+" "+result.receta.materno_beneficiario+", "+result.receta.nombre_beneficiario;
        $('#id_receta_vale_bus').val(result.receta.id);
        $('#consultorio').val(result.receta.consultorio);
        $('#nombre_turno').val(htmlEntities(result.receta.nombre_turno));
        $('#medico').val(medico);
        $('#grado').val(result.receta.grado);
        $('#tipodoc_beneficiario').val(result.receta.tipodoc_beneficiario);
        $('#dni_beneficiario').val(result.receta.dni_beneficiario);
        $('#tipo_beneficiario').val(result.receta.tipo_beneficiario);
        $('#beneficiario').val(beneficiario);
        $('#nro_historia').val(result.receta.nro_historia);
        /*
        $('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
        $('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
        $('#materno_beneficiario').val(result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado);
        */
        
        var productos = result.productos;
        var stock = result.stock;
        var stockAlmacen = result.stockAlmacen;
        var stockEstablecimiento = result.stockEstablecimiento;
        var diagnosticos = result.diagnosticos;
        var stock_principal = result.stock_principal;
        
        var id_user={{Auth::user()->id}};
        var newRow = "";
        var nombre_farmacia="";
        $('#example tbody').html("");
        for(var ind in productos) {
                  
          newRow = "";
          newRow +='<tr>';
          //newRow +='<td>'+productos[ind].nombre+'</td>';
          //newRow +='<td><input type="text" class="form-control input-sm" style="text-align:left;width:95%" readonly="" value="'+productos[ind].codigo + ' - ' + productos[ind].nombre+'" />';
          newRow +='<td>'+productos[ind].codigo + ' - ' + productos[ind].nombre+'</td>';
          
          //newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="form-control input-sm" style="margin-left:4px; width:100px" readonly="" value="'+stockAlmacen[productos[ind].id_producto]+'" />';
          newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="form-control input-sm" style="margin-left:4px; width:100px" readonly="" value="'+stockEstablecimiento[productos[ind].id_producto]+'" />';
          
          newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="form-control input-sm" style="margin-left:4px; width:100px" readonly="" value="'+stock[productos[ind].id_producto]+'" />';
          newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" required="" id="tiempo_periodo'+ind+'" class="form-control input-sm" style="margin-left:4px; width:50px" value="'+productos[ind].tiempo_periodo+'" readonly="" />';
          newRow +='<input type="hidden" name="id_producto[]" id="id_producto" value="'+productos[ind].id_producto+'"/>';
          newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
          newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
          newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados'+ind+'" class="form-control input-sm" style="margin-left:4px; width:50px" value="'+productos[ind].cantidad_prescrita+'" readonly="" /></td>';
          newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="form-control input-sm" style="margin-left:4px; width:50px" value="'+productos[ind].cantidad_dispensada+'" /></td>';
          newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';
          
          nombre_farmacia="";
          boton_farmacia="";
          <?php if ($modo_registro == 1 && $tipo_registro == 2):?>
            if(productos[ind].cantidad_dispensada==0){
              if(stock_principal[productos[ind].id_producto]["cantidad"] > 0)nombre_farmacia = stock_principal[productos[ind].id_producto]["farmacia"]+" ("+stock_principal[productos[ind].id_producto]["cantidad"]+")";
              boton_farmacia = '<button type="button" style="margin-left:8px;" onclick="show_stock_establecimiento('+productos[ind].id_producto+')" class="btn btn-primary btn-xs">+ Farmacias con Stock</button>';
            }
            newRow +='<td><input type="text" class="form-control input-sm" style="text-align:left;" readonly="" value="'+nombre_farmacia+'" />';
            newRow +='<td>'+boton_farmacia+'</td>';
          <?php endif;?>
          newRow +='</tr>';
          $('#example tbody').append(newRow);
        }
        
        var newRowDiag = "";
        $('#tblDiagnostico tbody').html("");
        for(var ind in diagnosticos) {
        
          newRowDiag +='<tr>';
          //newRowDiag +='<td>'+diagnosticos[ind].codigo+" - "+diagnosticos[ind].nombre;
          newRowDiag +='<td><input type="text" class="form-control input-sm" style="text-align:left;width:95%" readonly="" value="'+diagnosticos[ind].codigo+" - "+diagnosticos[ind].nombre+'" />';
          newRowDiag +='<input type="hidden" name="id_diagnostico[]" id="id_diagnostico" value="'+diagnosticos[ind].id+'"/></td>';
          newRowDiag +='</tr>';
        }
        $('#tblDiagnostico tbody').append(newRowDiag);
        
      }
      
    });
        
    }
  
  function obtenerBeneficiario(){
    
    var id_tiporeceta = $("#id_tiporeceta").val();
        var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    var dni_beneficiario = $("#dni_beneficiario").val();
    var msg = "";
    
    if(id_tiporeceta == "")msg += "Debe seleccionar el tipo de receta <br>";
    if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";
    
    if (msg != "") {
            bootbox.alert(msg);
            return false;
        }
    
    $('#nro_historia').prop('readonly', true);
    $('#nro_historia').prop("tabindex","-1");
    $.ajax({
      url: '/obtener_beneficiario/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
      dataType: "json",
      success: function(result){
        //nro_historia,grado
        if(result.sw == false){
          bootbox.alert(result.msg);
          $('#beneficiario').val("");
          $('#tipo_beneficiario').val("");
          $('#nombre_beneficiario').val("");
          $('#paterno_beneficiario').val("");
          $('#materno_beneficiario').val("");
          $('#grado').val("");
          $('#nro_historia').val("");
          $('#tipodoc_beneficiario').val("");
          $('#cip_beneficiario').val("");
          $('#id_admision').val("");
          return false;
        }
        
        var beneficiario = result.beneficiario.apepatafiliado+" "+result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado+", "+result.beneficiario.nomafiliado;
        $('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
        $('#beneficiario').val(beneficiario);
        $('#tipo_beneficiario').val(result.beneficiario.parentesco);
        $('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
        $('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
        $('#materno_beneficiario').val(result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado);
        
        $('#tipodoc_titular').val(result.beneficiario.nomtipdoctitular);
        $('#nrodoctitular').val(result.beneficiario.nrodoctitular);
        $('#nombre_titular').val(result.beneficiario.nomtitular);
        $('#paterno_titular').val(result.beneficiario.apepattitular);
        $('#materno_titular').val(result.beneficiario.apemattitular+" "+result.beneficiario.apecastitular);
        
        var grado=result.beneficiario.grado;
        if(grado!=null && grado!=""){
          $('#grado').val(grado);
        }else{
          var selectGrado = "";
          var item_grado = "";
          var sel="";
          $('#gradoTemp option').each(function(){
            sel="";
            if($(this).val()=='NINGUNO')sel="selected='selected'";
            item_grado += "<option value='"+$(this).val()+"' "+sel+" >"+$(this).html()+"</option>"  
          });
          selectGrado += '<select class="form-control" id="grado" name="grado" style="width:100%">'+item_grado+'</select>';
          $('#divGrado').html(selectGrado);
          $("#grado").select2({width: 'resolve'});
        }
        $('#nro_historia').val(result.numero_historia);
        $('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
        $('#cip_beneficiario').val(result.beneficiario.cip);
        
        var id_farmacia = $('#id_farmacia').val();
        //alert(id_farmacia);
        if((result.numero_historia=="" || result.numero_historia==null || id_farmacia==21) && id_farmacia!=19){
          $('#nro_historia').prop('readonly', false);
          $('#nro_historia').prop("tabindex",4);
        }
        
        if(id_tiporeceta == 3){
          
          ShowByServicioAndId_farmacia();
          /*
          var inputConsultorio = "";
          inputConsultorio += '<input type="text" readonly="" value="'+result.hospitalizacion.consultorio+'" name="consultorio" id="consultorio" class="form-control" style="text-align:left">';
          inputConsultorio += '<input type="hidden" value="'+result.hospitalizacion.id_consultorio+'" name="id_consultorio" id="id_consultorio">';
          $("#divServicioCol").html(inputConsultorio);
          
          var medico = result.hospitalizacion.paterno_medico+" "+result.hospitalizacion.materno_medico+", "+result.hospitalizacion.nombre_medico;
          var inputMedico = "";
          inputMedico += '<input type="text" readonly="" value="'+medico+'" name="medico" id="medico" class="form-control" style="text-align:left">';
          inputMedico += '<input type="hidden" value="'+result.hospitalizacion.id_medico+'" name="id_medico" id="id_medico">';
          $("#divMedicoCol").html(inputMedico);
          
          var nombre_turno="";
          if(result.hospitalizacion.turno == "M")nombre_turno="MAÑANA";
          if(result.hospitalizacion.turno == "T")nombre_turno="TARDE";
          if(result.hospitalizacion.turno == "N")nombre_turno="NOCHE";
          if(result.hospitalizacion.turno == "MD")nombre_turno="MADRUGADA";
          var inputTurno = "";
          inputTurno += '<input type="text" readonly="" value="'+nombre_turno+'" name="nombre_turno" id="nombre_turno" class="form-control" style="text-align:left">';
          inputTurno += '<input type="hidden" value="'+result.hospitalizacion.turno+'" name="turno" id="turno">';
          $("#divTurnoCol").html(inputTurno);
          */
          
          /******verificar*********/
          /*var medico = result.hospitalizacion.paterno_medico+" "+result.hospitalizacion.materno_medico+", "+result.hospitalizacion.nombre_medico;
          var nombre_turno="";
          if(result.hospitalizacion.turno == "M")nombre_turno="MAÑANA";
          if(result.hospitalizacion.turno == "T")nombre_turno="TARDE";
          if(result.hospitalizacion.turno == "N")nombre_turno="NOCHE";
          if(result.hospitalizacion.turno == "MD")nombre_turno="MADRUGADA";
          $('#consultorio').val(result.hospitalizacion.consultorio);
          $('#id_consultorio').val(result.hospitalizacion.id_consultorio);
          $('#medico').val(medico);
          $('#id_medico').val(result.hospitalizacion.id_medico);
          $('#nombre_turno').val(nombre_turno);
          $('#turno').val(result.hospitalizacion.turno);*/
          $('#id_admision').val(result.hospitalizacion.id);
        }/*else{
          var selectConsultorio = "";
          var item_consultorio = "";
          $('#id_consultorio_temp option').each(function(){
            item_consultorio += "<option value='"+$(this).val()+"' "+sel+" >"+$(this).html()+"</option>"  
          });
          selectConsultorio += '<select class="form-control" id="id_consultorio" name="id_consultorio" tabindex="6" onchange="getProducto();" style="width:100%">'+item_consultorio+'</select>';
          $('#divServicioCol').html(selectConsultorio);
          $("#id_consultorio").select2({width: 'resolve'});
          
          var selectMedico = "";
          var item_medico = '<option value="">--Seleccionar--</option>';
          selectMedico += '<select class="form-control" id="id_medico" name="id_medico" tabindex="8" style="width:100%" onchange="valid_servicios_by_mes();AddFilaMed();">'+item_medico+'</select>';
          $('#divMedicoCol').html(selectMedico);
          $("#id_medico").select2({width: 'resolve'});
          
          var selectTurno = "";
          var item_turno = '<option value="">--Seleccionar--</option>';
          item_turno += '<option value="M">MAÑANA</option>';
          item_turno += '<option value="T">TARDE</option>';
          item_turno += '<option value="N">NOCHE</option>';
          selectTurno += '<select class="form-control" id="turno" name="turno" tabindex="7" style="width:100%" onchange="getMedico()">'+item_turno+'</select>';
          $('#divTurnoCol').html(selectTurno);
          $("#turno").select2({width: 'resolve'});
          
        }*/
      },
        error: function(response) {
        location.reload();
      }
      
    });
        
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
  
  function getProducto_Via(){
       
    $.ajax({
        url: '/carga_consultorios/' + 20,
        success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"'>" +oo.nombre+"</option>";
        });                 
        $('#id_consultorio').html(newOption);           
        
        }
      });
  
      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
      $('#id_consultorio').html(newOption);
      $("#id_consultorio").val('').trigger("change");
      
      
      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";              
      $('#id_medico').html(newOption);
      $("#medico").val('').trigger("change");
      
      
      if($('#tipo_via').val()=="") return false;
      var id_ipress     = $('#id_ipress').val();
      var tipo_via  = $('#tipo_via').val();
  
      $.ajax({
      url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + tipo_via,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
        newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
        });                 
        $('#cmbMedicamentoTemp').html(newOption);
      }
      });
  
  
  
      if(tipo_via==111){
        /*$('#sub_via').remove();
        $('#sub_via_label').remove();
        $('#final_via').remove();
        $('#final_via_label').remove();*/
        
        /*var label = document.createElement("label"); 
        var text = document.createTextNode("SUB VIA"); 
        label.setAttribute("name", "sub_via_label");
        label.setAttribute("id", "sub_via_label");
        label.appendChild(text);
        $('#cargaSubVia').append(label);*/
        
        /*var select = document.createElement("select");
        select.setAttribute("name", "sub_via");
        select.setAttribute("class", "form-control");
        select.setAttribute("id", "sub_via");
        select.setAttribute("tabindex", "5");
        select.setAttribute("onchange","getCargaFinalVia()");
        select.style.width = "100%";          
        $('#cargaSubVia').append(select);*/
        
        var newOption = "";
        newOption = "<option value=''>--Seleccionar Sub Via-</option>";              
        newOption += "<option value='PERIFERICA'>PERIFERICA</option>";  
        newOption += "<option value='TOTAL'>TOTAL</option>";  
        $('#sub_via').html(newOption);
  
      }
      else
      {
        /*$('#sub_via').remove();
        $('#sub_via_label').remove();
        $('#final_via').remove();
        $('#final_via_label').remove();
        
        var label = document.createElement("label"); 
        var text = document.createTextNode("SUB VIA"); 
        label.setAttribute("name", "sub_via_label");
        label.setAttribute("id", "sub_via_label");
        label.appendChild(text);
        $('#cargaSubVia').append(label);
        
        var select = document.createElement("select");
        select.setAttribute("name", "sub_via");
        select.setAttribute("class", "form-control");
        select.setAttribute("id", "sub_via");
        select.setAttribute("tabindex", "5");
        select.style.width = "100%";          
        $('#cargaSubVia').append(select);*/
        
        var newOption = "";
        newOption = "<option value=''>--Seleccionar Sub Via-</option>";              
        newOption += "<option value='VIA_ORAL'>VIA ORAL</option>";  
        newOption += "<option value='SONDA'>SONDA</option>";  
        $('#sub_via').html(newOption);
  
      }
    
    
  }

    function getCargaFinalVia(){
    
    if($('#sub_via').val()=="") return false;      
    var sub_via  = $('#sub_via').val();

    //$('#final_via').remove();
    //$('#final_via_label').remove();

    $('#divFinalVia').hide();
    if(sub_via=='TOTAL'){
      /*
      var label = document.createElement("label"); 
      label.setAttribute("name", "final_via_label");
      label.setAttribute("id", "final_via_label");
      var text = document.createTextNode("VIA FINAL"); 
      label.appendChild(text);
      $('#cargaFinalVia').append(label);
      
      var select = document.createElement("select");
      select.setAttribute("name", "final_via");
      select.setAttribute("class", "form-control");
      select.setAttribute("id", "final_via");
      select.setAttribute("tabindex", "6");
      select.style.width = "100%";
      $('#cargaFinalVia').append(select);
      */
      var newOption = "";
      newOption = "<option value=''>--Seleccionar --</option>";
      newOption += "<option value='2_1'>2:1</option>";
      newOption += "<option value='3_1'>3:1</option>";
      $('#final_via').html(newOption);
      $('#divFinalVia').show();
    }
    /*else
    {
      $('#final_via').remove();
      $('#final_via_label').remove();
    }*/
    
    
  }
    
  function limpiarBeneficiario(){
    
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nrodocafiliado = $('#nrodocafiliado').val();
    if(dni_beneficiario!=nrodocafiliado){
      $('#beneficiario').val("");
      $('#tipo_beneficiario').val("");
      $('#nombre_beneficiario').val("");
      $('#paterno_beneficiario').val("");
      $('#materno_beneficiario').val("");
      $('#grado').val("");
      $('#nro_historia').val("");
      $('#tipodoc_beneficiario').val("");
      $('#cip_beneficiario').val("");
      $('#id_admision').val("");
    }
    
    obtenerBeneficiario();
  
  }

  function ver_det_evaluacion(id){
    
    $.ajax({
    
            url: /mostrar_evolucion/ + id+"/3",
            async: false,
            success: function(result){
            $("#muestra-det-evaluacion").html(result);
            },
            complete: function () {
            $("#myModalEvolucion").modal();
            }
    });
  }

  function ver_det_indicaciones_terapeutica(id_hospitalizacion){
    $.ajax({
    
            url: "../../modal_historial_indicaciones_terapeuticas/"+id_hospitalizacion,
            async: false,
            success: function(result){
            $("#muestra-det-indicaciones-terapeuticas").html(result);
            },
            complete: function () {
            $("#myModalIndicacionTerapeutica").modal();
            }
    });
  }

  function ver_det_atencion_evaluacion(id){
    $('#nrorecmodal').text(id);
    $.ajax({
      url: '/mostrar_atencion_hospitalizacion/' + id,
      async: false,
      success: function(result){
        $("#muestra-det-atencion").html(result);
      },
      complete: function () {
        $("#myModalAtencionEvaluacion").modal();
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

.form-control{
    height: 28px;
  text-align:left!important;
}

.form-group{
    margin-bottom: 10px;
}

</style>
@endsection
@section('content')

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>

<div class="container" style="width:90%">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading">
                        <h3 class="panel-title">MÓDULO DE GESTIÓN DE RECETAS</h3>      
                    </div>
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('registrar_receta_rua')}}" id="frmReceta" autocomplete="off">
                            <!--  action="{{ route('registrar_receta_rua')}}" -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id_nivel" id="id_nivel" value="<?php echo $id_nivel?>">
                            <div class="form-group">
                                <div class="col-md-4 text-center">
                                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                                    <?php if ($opcReceta == 1): ?>
                                        <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                        <input type="hidden" value="{{$fecha_pasada}}" name="fecha_pasada" id="fecha_pasada">
                                        <input type="hidden" value="dni" name="tipo">
                                        <input type="hidden" name="valid_nro_receta" id="valid_nro_receta" value="0"/>
                                    <?php endif; ?>

                                    <?php if ($opcReceta == 2): ?>
                                        <input type="hidden" value="{{$id_farmacia[0]}}" name="id_farmacia" id="id_farmacia">
                                        <input type="hidden" value="{{$nro_rua}}" name="nro_rua">
                                        <input type="hidden" value="rua" name="tipo">
                                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                                        <input type="hidden" value="{{$fecha_pasada}}" name="fecha_pasada" id="fecha_pasada">
                                    <?php endif; ?>
                                    <input type="hidden" value="<?php echo $id_ipress ?>" name="id_ipress" id="id_ipress">
                                </div>
                                <div class="col-md-4" style="text-align: center">
                                    <label>{{$establecimiento->nombre}}</label><br>
                                    <label style="font-size: 24px">DISPENSACIÓN DE RECETA</label><br>
                                </div>
                                <div class="col-md-4 text-center">
                                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                                </div>
                            </div>
                            @if(session('loading'))
                              <div>
                                <p>CARGANDO...</p>
                              </div>
                            @endif
                            <div class="form-group">
                                <div class="col-md-2 text-center"></div>
                                <div class="col-md-2 text-center"></div>
                                <div class="col-md-2 text-center"></div>
                                <div class="col-md-2 text-center"></div>
                                <div class="col-md-2 text-center"></div>
                                <div class="col-md-2 text-center">
                                    <label class="col-lg-12 control-label">NUMERO DE SITEDS</label>
                                    <div id="divSiteds">
                                      <input  tabindex="1" autofocus="" type="text" class="form-control"  id="codigo_siteds" name="codigo_siteds"  onkeypress="return esEntero(event);" 
                                        value="{{$receta->codigo_siteds}}" style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important;"   
                                        maxlength="12"  pattern="[0-9]*" <?php echo isset($receta->codigo_siteds) ? 'readonly' : ""   ?> >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                            <?php 
                                $disabled_receta = "";
                                if($receta->id_farmacia!=Auth::user()->id_farmacia):

                                    
                                            //$disabled_receta = "disabled='disabled'";
                                            echo '  <div class="col-sm-12">
                                                        <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
                                                            La receta fue prescrita por el medico en la '.$receta->nombre_farmacia.' 
                                                        </div>
                                                    </div>';
                                    
                                endif;

                                if($receta->ruta_receta==""){
                                    $disabled_receta = "disabled='disabled'";
                                    echo '  <div class="col-sm-12">
                                                <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
                                                    La receta aun no ha sido firmada por el m&eacute;dico
                                                </div>
                                            </div>';
                                }

                                if($receta->alerta_producto>0):
                                  
                                  if($tipo_atencion[0]->id_tipo_atencion==2 || $tipo_atencion[0]->id_tipo_atencion==3):
                                    echo '  <div class="col-sm-12">
                                                <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px"><h3>
                                                    El paciente tiene otra receta con '.$receta->alerta_producto.' producto(s) en los ultimos 5 dias. Verificar dicho producto(s) antes de atender, haciendo clic en boton Ver, al costado del nombre del producto de color rojo.  </h3>
                                                </div>
                                            </div>';
                                  else:
                                            echo '  <div class="col-sm-12">
                                                        <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px"><h3>
                                                            El paciente tiene otra receta con '.$receta->alerta_producto.' producto(s) en los ultimos 30 dias. Verificar dicho producto(s) antes de atender, haciendo clic en boton Ver, al costado del nombre del producto de color rojo.  </h3>
                                                        </div>
                                                    </div>';
                                  endif;
                                    
                                endif;
                            ?>
                                <div class="col-sm-12">
                                    <div id="msj-atenciones">
                                        <div id="det-msj-atenciones"></div>
                                    </div>
                                </div>
                            
                                
                            
                                <div class="col-md-2">
                                    <label>TIPO ATENCIÓN</label>
                                    <input type="text" readonly="" value="<?php echo $receta->tipo_receta ?>" name="tipo_receta" id="tipo_receta" class="form-control" style="text-align:left">
                                </div>
                                <?php

                                    $selected = 'selected';
                                    switch ($receta->tipo_receta) {
                                        case 'AMBULATORIO': $day_expiracion = 29; break;
                                        case 'EMERGENCIA': $day_expiracion = 1; break;
                                        case 'HOSPITALIZACION': if($receta->id_farmacia==18):$day_expiracion = 30; else: $day_expiracion = 2;endif; break;
                                        case 'CENTRO QUIRURGICO': $day_expiracion = 30; break;
                                        case 'SERVICIO NUTRICIONAL - AMBULATORIO': $day_expiracion = 29; break;
                                        case 'SERVICIO NUTRICIONAL - HOSPITALIZACION': $day_expiracion = 2; break;
                                        case 'URGENCIA': $day_expiracion = 29; break;
                                        case 'ONCOLOGICO - HOSPITALIZACION': $day_expiracion = 2; break;
                                    }
                                ?>
                                <div class="col-md-2" id="divReceta">
                  <label>N° RECETA</label>
                                      <input class="form-control" readonly="" value="<?php echo $receta->nro_receta?>"  onKeyPress="return soloNumeros(event)" type="text" name="nro_receta" id="nro_receta" tabindex="1"  style="text-transform:uppercase;">
                    <input type="hidden" value="<?php echo $receta->id?>" name="id_receta_vale_bus" id="id_receta_vale_bus">
                    <input type="hidden" value="<?php echo $receta->estado?>" name="estado_receta" id="estado_receta">
                </div>
                              @if (Auth::user()->id_farmacia==128)
                                <div class="col-md-1"  id="divNroGuia">
                                    <label>N° GUIA </label>
                                    <input class="form-control" onKeyPress="return soloNumeros(event)"type="text" value="" id="nro_guia" name="nro_guia" tabindex="1" style="text-transform:uppercase;">
                                </div>
                                <div class="col-md-1" id="divAno">
                                    <label>AÑO RECETA</label>
                                    <input class="form-control" onKeyPress="return soloNumeros(event)"type="text" value="<?php echo date("Y") ?>" id="digitos_adicionales" name="digitos_adicionales" maxlength="4" readonly="">
                                </div>
                              @else
                                <div class="col-md-2" id="divAno">
                                    <label>AÑO RECETA</label>
                                    <input class="form-control" onKeyPress="return soloNumeros(event)"type="text" value="<?php echo date("Y") ?>" id="digitos_adicionales" name="digitos_adicionales" maxlength="4" readonly="">
                                </div>
                              @endif
                                
                                <div class="col-md-2">
                                    <label>FEC. EXPEDICIÓN (A)</label>
                                    <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?>" name="fecha_atencion" id="fecha_atencion" tabindex="2"  data-date-end-date="0d" autocomplete="off" readonly=""/>
                                </div>
                                <div class="col-md-2">
                                    <label>FEC. EXPIRACI&Oacute;N (E)</label>
                                    <input data-date-start-date="0d"  type="text" class="form-control datepicker"
                                        value="<?php echo date('d-m-Y', strtotime("{$receta->fecha_registro} + {$day_expiracion} day")); ?>" name="fecha_expiracion" id="fecha_expiracion"
                                        tabindex="2" data-date-end-date="+<?php echo $day_expiracion; ?>d" />
                                </div>
                                <div class="col-md-2">
                                    <label>FEC. DISPENSACIÓN (R)</label>
                                    <input type="text" class="form-control" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_expedicion" id="fecha_expedicion" tabindex="-1" readonly=""/>
                                </div>
                            </div>
              <div class="form-group">
              
                <div class="col-md-2">
                  <label for="tipo">DOCUMENTO</label>
                    <input type="text" readonly="" value="<?php echo $receta->tipodoc_beneficiario ?> - <?php echo $receta->dni_beneficiario ?> " name="tipodoc_beneficiario" id="tipodoc_beneficiario" class="form-control">
                    <input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular" class="form-control">
                    <input type="hidden" class="form-control" value="" name="nrodoctitular" id="nrodoctitular" readonly=""/>
                    <input type="hidden" class="form-control" value="" name="cip_beneficiario" id="cip_beneficiario" readonly=""/>
                    <input type="hidden" class="form-control" value="" name="nrodocafiliado" id="nrodocafiliado" readonly=""/>
                    <input type="hidden" class="form-control" value="" name="nrodoctitular" id="nrodoctitular" readonly=""/>
                    <input type="hidden" class="form-control" value="{{$id_tiporeceta}}" name="id_tiporeceta" id="id_tiporeceta" readonly=""/>
                    <input type="hidden" class="form-control" value="{{$tipodoc_ben}}" name="tipodoc_beneficiario_bus" id="tipodoc_beneficiario_bus" readonly=""/>
                    <input type="hidden" value="<?php echo $receta->dni_beneficiario ?>" name="dni_beneficiario" id="dni_beneficiario" class="form-control"/>
                </div>
                <!--div class="col-md-2">
                  <label for="dni_beneficiario">NRO DOC.</label>
                  <input type="text" readonly="" value="<?php echo $receta->dni_beneficiario ?>" name="dni_beneficiario" id="dni_beneficiario" class="form-control"/>
                  
                </div-->
                
                <!--
                                <div class="col-md-2">
                                    <label></label>
                                    <?php if ($opcReceta == 1): ?>
                                        <input type="hidden" class="form-control" value="" name="tipdoc_beneficiario" id="tipdoc_beneficiario" readonly=""/>
                                        <input type="hidden" class="form-control" value="" name="cip_beneficiario" id="cip_beneficiario" readonly=""/>
                                        <input type="text" class="form-control" value="" name="dni_beneficiario" id="dni_beneficiario" readonly=""/>
                                    <?php endif; ?>

                                    <?php if ($opcReceta == 2): ?>
                                        <input type="hidden" class="form-control" value="" name="tipodoc_beneficiario" id="tipo_beneficiario" readonly="">
                                        <input type="hidden" class="form-control" value="" name="cip_beneficiario" id="cip_beneficiario" readonly="">
                                        <input type="text" class="form-control" value="" name="dni_beneficiario" id="dni_beneficiario" required="" readonly="">
                                    <?php endif; ?>

                                </div>
                -->
                                <div class="col-md-4">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <?php if ($opcReceta == 1): ?>
                                        <input readonly="" type="text" class="form-control" value="<?php echo $receta->paterno_beneficiario." ".$receta->materno_beneficiario.", ".$receta->nombre_beneficiario ?>" name="beneficiario" id="beneficiario" style="text-align:left">
                                        <input type="hidden" class="form-control" value="" name="nombre_beneficiario" id="nombre_beneficiario"/>
                                        <input type="hidden" class="form-control" value="" name="paterno_beneficiario" id="paterno_beneficiario"/>
                                        <input type="hidden" class="form-control" value="" name="materno_beneficiario" id="materno_beneficiario"/>
                    
                    <input type="hidden" class="form-control" value="" name="nombre_titular" id="nombre_titular"/>
                    <input type="hidden" class="form-control" value="" name="paterno_titular" id="paterno_titular"/>
                    <input type="hidden" class="form-control" value="" name="materno_titular" id="materno_titular"/>
                                    <?php endif; ?>

                                    <?php if ($opcReceta == 2): ?>
                                        <input type="text" class="form-control" value="" name="beneficiario" id="beneficiario" readonly="">
                                        <input type="hidden" class="form-control" value="" name="nombre_beneficiario" id="nombre_beneficiario"/>
                                        <input type="hidden" class="form-control" value="" name="paterno_beneficiario" id="paterno_beneficiario"/>
                                        <input type="hidden" class="form-control" value="" name="materno_beneficiario" id="materno_beneficiario"/>
                    
                    <input type="hidden" class="form-control" value="" name="nombre_titular" id="nombre_titular"/>
                    <input type="hidden" class="form-control" value="" name="paterno_titular" id="paterno_titular"/>
                    <input type="hidden" class="form-control" value="" name="materno_titular" id="materno_titular"/>
                                    <?php endif; ?>
                                </div> 
                
                <div class="col-md-2">
                                    <label>PARENTESCO &nbsp;
                    <!--
                                        <?php 
                                        //if($parentesco<>"TITULAR") {
                                            ?>
                                        <a href="#" onclick="ocultar_titu()">Datos Titular</a>
                                        <?php
                                        //}
                    ?>
                    -->
                    </label>
                                    <input readonly="" value="<?php echo $receta->tipo_beneficiario ?>" type="text" name="tipo_beneficiario" id="tipo_beneficiario" class="form-control" >
                                </div>
                
                <div class="col-md-2">
                  <label>NRO HC</label>
                  <input type="text" class="form-control" value="<?php echo $receta->nro_historia ?>" id="nro_historia" name="nro_historia" tabindex="-1" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                </div>
                
                <div class="col-md-2">
                  <label>GRADO</label>
                  
                  <?php //if ($modo_registro == 1 && $tipo_registro == 2):?>
                    <div id="divGrado"><input readonly="" class="form-control" value="<?php echo $receta->grado ?>" type="text" name="grado" id="grado"></div>
                    
                    <select class="form-control" id="gradoTemp" name="gradoTemp" style="width:100%;display:none">                                        
                      <option value="">--Seleccionar--</option>
                      <?php foreach($grados as $gra):?>
                      <option value="<?php echo $gra?>" <?php if("NINGUNO"==$gra)echo "selected='selected'"?> ><?php echo $gra?></option>
                      <?php endforeach;?>
                    </select>
                    
                  <?php //else:?>
                  <?php //endif;?>
                
                </div> 
                                               
                            </div>
              
              
                            <div class="form-group">
              
                <div class="col-md-4" id="divVia" style="display:none;padding-left:0px">
                  <div class="col-md-6">
                    <label>TIPO VIA</label>
                    <select class="form-control select2" name="tipo_via" id="tipo_via" tabindex="8" style="width: 100%" onchange="getProducto_Via();">
                      <option value="">--Seleccionar--</option>
                      <option value="110">ENTERAL</option>
                      <option value="111">PARENTERAL</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>SUB VIA</label>
                    <select class="form-control select2" name="sub_via" id="sub_via" tabindex="8" style="width: 100%" onchange="getCargaFinalVia();">
                    </select>
                  </div>
                </div>
                
                <div class="col-md-2" id="divFinalVia" style="display:none">
                  <label>SUB VIA</label>
                  <select class="form-control select2" name="final_via" id="final_via" tabindex="8" style="width: 100%">
                  </select>
                </div>
                
                
                @if (Auth::user()->id_farmacia==128)
                  <div class="col-md-2">
                    <label>INTERCONS.</label>
                    <select autofocus="" class="form-control" id="interconsulta" name="interconsulta" tabindex="5" style="width: 100%">
                        <option value="" selected>-Elige-</option>
                        <option value="SI">SI</option>
                        <option value="NO" selected="selected">NO</option>
                    </select>
                  </div>
                  <div class="col-md-1">
                    <label>DIAS TTO</label>
                                    <input class="form-control" onKeyPress="return soloNumeros(event)" type="text" name="dia_tto" autofocus="" id="dia_tto" tabindex="5">
                  </div>
                @endif
                @if (Auth::user()->id_farmacia==128 || Auth::user()->id_farmacia==19)
                  <div class="col-md-2">
                    <label>EDAD</label>
                    <input class="form-control" onKeyPress="return soloNumeros(event)" type="text" name="edad" autofocus="" id="edad" tabindex="5" value="{{$receta->edad}}" readonly="">
                  </div>
                  @if (Auth::user()->id_farmacia==128)
                    <div class="col-md-3">
                  @else
                    <div class="col-md-4">
                  @endif
                    <label>CAMA</label>
                    <input class="form-control" onKeyPress="return soloNumeros(event)" type="text" name="cama" autofocus="" id="cama" tabindex="5" value="{{$receta->cama}}" readonly="">
                  </div>
                  <div class="col-md-2">
                    <label>PESO</label>
                    <input class="form-control" onKeyPress="return soloNumeros(event)" type="text" name="peso" autofocus="" id="peso" tabindex="5" value="{{$receta->peso}}" readonly="">
                  </div>
                  <div class="col-md-2">
                    <label>TALLA</label>
                    <input class="form-control" onKeyPress="return soloNumeros(event)" type="text" name="talla" autofocus="" id="talla" tabindex="5" value="{{$receta->talla}}" readonly="">
                    <br>
                  </div>
                  <div class="col-md-2">
                    <label>SEXO.</label>
                    <input class="form-control" onKeyPress="return soloNumeros(event)" type="text" name="sexo" autofocus="" id="sexo" value="{{$receta->sexo_beneficiario}}" tabindex="5" readonly="">
                    
                  </div>
                @endif
                
                                <div class="col-md-4" id="divServicio">
                                    <label>SERVICIOS</label>
                  <input type="hidden" value="<?php echo $tipo_registro?>" name="tipo_registro" id="tipo_registro">
                  <input type="hidden" value="<?php echo $modo_registro?>" name="modo_registro" id="modo_registro">
                  
                  <div id="divServicioCol">
                  <?php //if ($modo_registro == 1 && $tipo_registro == 1):?>
                    <!--<input type="text" readonly="" value="<?php //echo (isset($turno->consultorio))?$turno->consultorio:""?>" name="consultorio" id="consultorio" class="form-control" style="text-align:left">-->
                    <input type="hidden" name="id_consultorio" value="<?php echo $receta->id_consultorio ?>">
                    <input type="text" readonly="" value="<?php echo $receta->consultorio ?>" name="consultorio" id="consultorio" class="form-control" style="text-align:left">
                  </div>
                  
                  <select class="form-control" id="id_consultorio_temp" name="id_consultorio_temp" tabindex="6" style="width:100%;display:none">
                    <option value="">--Seleccionar--</option>
                    @foreach($consultorio as $c)
                    <option value="{{$c->id}}">{{$c->nombre}}</option>
                    @endforeach                                        
                  </select>
                    
                                </div>
                <!--
                <div class="col-md-6" id="divServicioHosp" style="display:none">
                                    <label>SERVICIOS</label>
                  <input type="text" readonly="" value="" name="consultorio" id="consultorio" class="form-control" style="text-align:left">
                  <input type="text" value="" name="id_consultorio" id="id_consultorio">
                </div>
                -->
                
                                <div class="col-md-2" id="divTurno">
                                    <label>TURNO</label>
                  <div id="divTurnoCol">
                    <input type="text" readonly="" value="<?php echo $receta->nombre_turno ?>" name="nombre_turno" id="nombre_turno" class="form-control" style="text-align:left">
                  </div>
                                </div>
                
                <!--
                <div class="col-md-2" id="divTurnoHosp" style="display:none">
                                    <label>TURNO</label>
                  <input type="text" readonly="" value="" name="nombre_turno" id="nombre_turno" class="form-control" style="text-align:left">
                  <input type="text" value="" name="turno" id="turno">
                </div>
                -->
                
                                <div class="col-md-4" id="divMedico">
                                    <label>MEDICO (DNI | CMP | Nombre y Apellidos)</label>
                  <div id="divMedicoCol">
                    <input type="text" readonly="" value="<?php echo $receta->paterno." ".$receta->materno.", ".$receta->nombre ?>" name="medico" id="medico" class="form-control" style="text-align:left">
                    <input type="hidden" name="id_medico" id="id_medico" value="{{$receta->id_medico}}" />
                  </div>
                  
                                </div>
                
                <!--
                <div class="col-md-4" id="divMedicoHosp" style="display:none">
                                    <label>MEDICO (DNI | CMP | Nombre y Apellidos)</label>
                  <input type="text" readonly="" value="" name="medico" id="medico" class="form-control" style="text-align:left">
                  <input type="text" name="id_medico" id="id_medico" value="" />
                </div>
                              -->
              
              
                            <!--
                            <div id="muestra-datos-titu" style="display: none;">
                                <div class="form-group">
                                     <div class="col-sm-12">
                                        <label><a href="#" onclick="ocultar_titu()">Ocultar Datos del Titular</a></label>
                                     </div>
                                    <div class="col-md-2">
                                        <label>DNI (TITULAR)</label>
                                        <?php if ($opcReceta == 1): ?>
                                            <input type="hidden" class="form-control" value="" name="tipdoc_titular" id="tipdoc_titular" readonly=""/>
                                            <input readonly="" type="text" class="form-control" value="" name="dni_titular">
                                        <?php endif; ?>
                                        <?php if ($opcReceta == 2): ?>
                                            <input type="hidden" class="form-control" value="" name="tipdoc_titular" id="tipdoc_titular" readonly=""/>
                                            <input type="text" class="form-control" value="" name="dni_titular" readonly="">
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-7">
                                        <label>APELLIDOS Y NOMBRES (TITULAR)</label>
                                        <?php if ($opcReceta == 1): ?>
                                            <input type="text" class="form-control" value="" name="paterno_beneficiario"  readonly="">
                                        <?php endif; ?>

                                        <?php if ($opcReceta == 2): ?>
                                            <input type="text" class="form-control" value="" name="paterno_beneficiario" readonly="">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            -->
              
                <div class="col-md-4" id="divCentroQuirurgico" style="display:none">
                  <label>ANESTESIOLOGO (DNI | CMP | Nombre y Apellidos)</label>
                  <?php if (isset($exite_medico_anestesiologo) && $exite_medico_anestesiologo == 1): ?>
                    <input name="exite_medico_anestesiologo" value="{{$existe_medico_anestesiologo}}" type="hidden">
                  <?php endif; ?>
                  <select class="form-control" name="id_anestesiologo" id="id_anestesiologo" tabindex="8" style="width: 100%" onchange="valid_servicios_by_mes();AddFilaMed();">
                    <option value="">--Seleccionar--</option>
                  </select>
                </div>
                
                
              </div>
              
              
                            
              
              
                            <!--<div class="form-group">-->
                                <div class="col-md-12" style="text-align: left; font-size: 18px">
                  DIAGNOSTICO
                  <!--<button type="button" id="addDiagnostico" style="margin-left:18px" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Añadir Diagnostico</button>-->
                </div>
                
                
                                    <!--<label>DIAGNOSTICO</label>-->
                                    <?php if (isset($existe_cie) && $existe_cie == 1): ?>
                                        <select class="form-control" name="id_diagnostico" id="id_diagnostico" required="" tabindex="10">
                                            <option value="{{$cie->id}}">{{$cie->codigo}} - {{$cie->nombre}}</option>                                        
                                        </select>
                                    <?php else: ?>
                      
                      <div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                        @if(count($diagnosticos_egreso) > 0)
                          <table id="tblDiagnosticoEgreso" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                            <thead>
                              <tr>
                                <th width="90%"></th>
                                <th width="10%"></th>
                              </tr>
                            </thead>
                            <tfoot></tfoot>
                            <tbody>
                            <?php foreach($diagnosticos_egreso as $key=>$row):?>
                              <tr>
                                <td>
                                  <input type="text" class="form-control input-sm" style="text-align:left;width:95%" readonly="" value="<?php echo $row->codigo." - ".$row->nombre?>" />
                                  <input type="hidden" name="id_diagnostico[]" id="id_diagnostico" value="<?php echo $row->id?>"/>
                                </td>
                                <td>
                                  <select disabled tabindex="45" class="form-control id_tipo_diagnostico" id="cmbTipoDiagnosticoEgreso<?php echo $key?>" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">
                                        <option @if($row->id_tipo_diagnostico == '0') {{'selected'}} @endif value="0">--Seleccionar--</option>
                                        <option @if($row->id_tipo_diagnostico == '1') {{'selected'}} @endif value="1">PRESUNTIVO</option>
                                        <option @if($row->id_tipo_diagnostico == '2') {{'selected'}} @endif value="2">DEFINITIVO</option>
                                        <option @if($row->id_tipo_diagnostico == '3') {{'selected'}} @endif value="3">REITERATIVO</option>
                                  </select>
                                </td>
                              </tr>
                            <?php endforeach;?>
                            </tbody>
                          </table>
                        @else

                        <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                            <tr>
                              <th width="90%"></th>
                              <th width="10%"></th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                          <?php foreach($diagnostico as $key=>$row):?>
                          <tr>
                            <td>
                              <input type="text" class="form-control input-sm" style="text-align:left;width:95%" readonly="" value="<?php echo $row->codigo." - ".$row->nombre?>" />
                              <input type="hidden" name="id_diagnostico[]" id="id_diagnostico" value="<?php echo $row->id?>"/>
                            </td>
                            <td>
                              <select disabled tabindex="45" class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico<?php echo $key?>" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">
                                    <option @if($row->id_tipo_diagnostico == '0') {{'selected'}} @endif value="0">--Seleccionar--</option>
                                    <option @if($row->id_tipo_diagnostico == '1') {{'selected'}} @endif value="1">PRESUNTIVO</option>
                                    <option @if($row->id_tipo_diagnostico == '2') {{'selected'}} @endif value="2">DEFINITIVO</option>
                                    <option @if($row->id_tipo_diagnostico == '3') {{'selected'}} @endif value="3">REITERATIVO</option>
                              </select>
                            </td>
                          </tr>
                          <?php endforeach;?>
                          </tbody>
                        </table>
                       @endif
                      </div>

                                    <?php endif; ?>
                                <!--</div>-->
                            <!--</div>-->
                            <div class="">
                                <?php if (isset($nro_rua) && $nro_rua != ""): ?>
                                    <div class="col-md-2" style="text-align: center; margin-top: 20px;float:right">
                                        <label>N° RUA</label>
                                        <input type="text" value="{{$nro_rua}}" name="nro_rua" class="form-control" readonly="readonly" >
                                    </div>
                                <?php else: ?>
                                    <input type="hidden" value="" name="nro_rua" class="form-control" >
                                <?php endif; ?>

                            </div>
                        <!--</form>-->
                        <?php
                        $exit = 0;
                        if (isset($flag) && $flag == 1)
                            $exit = 1;
                        ?>
                        <input type="hidden" class="form-control" value="<?php echo $exit ?>" name="exit" id="exit">
                                <div class="col-md-12" style="text-align: left; font-size: 18px">
                  PRESCRIPCIÓN 
                  <!--<button type="button" id="addRow" style="margin-left:10px" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Agregar producto(s)</button>-->
                </div>
                
                                <div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                
                  <table id="example" class="display" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Producto farmacéutico o dispositivo médico</th>
                        <!--th style="width:10%">Stock Almacen</th-->
                        <th>Stock Establecimiento</th>
                                                <th>Stock Farmacia</th>
                                                <?php if (isset($flag_receta_dias) && $flag_receta_dias == 1): ?>
                                                    <th>Dias</th>
                                                <?php endif; ?>
                                                <th id="thViaAdmin">Via Adm.</th>
                                                <th>Diagnostico</th>
                                                <th>Posologia</th>
                                                <th>Cant. Presc.</th>
                                                <th>Cant. Entreg.</th>
                                                <th>Observacion</th>
                                                <?php if($receta->id_farmacia==128): ?>   
                                                <th>Edit dia</th>
                                                <th>Nro Receta</th>
                                                                                                <?php endif; ?>

                                            </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                    <?php foreach($productos as $key=>$row):
                    //print_r($stock_principal);
                    ?>
                      <tr>
                        <td>@if($row->contiene>0)
                              <span style="color:red";><b><?php echo $row->codigo." - ".$row->nombre?></b></span>
                              <button type="button" data-toggle="modal" class="btn btn-primary btn-xs" onclick="validproductoseleccionado('<?php echo $row->id_producto ?>')"><i class="fa fa-eye"></i> ver</button>
                            @else
                              <?php echo $row->codigo." - ".$row->nombre?>
                              @if($stockEstablecimiento[$row->id_producto]>0 && $stock_principal[$row->id_producto]["cantidad"]==0)
                                <button type="button" data-toggle="modal" class="btn btn-warning btn-xs" onclick="mostrarproductofarmacia('<?php echo $row->id_producto ?>')"><i class="fa fa-eye"></i> ver stock</button>
                              @endif
                            @endif      
                        </td>
                        
                        <!--td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="form-control input-sm" style="margin-left:4px; width:100px" readonly="" value="<?php //echo $stockAlmacen[$row->id_producto]?>" /-->
                        <td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="form-control input-sm" style="margin-left:4px; width:100px" readonly="" value="<?php echo $stockEstablecimiento[$row->id_producto]?>" />
                        
                        <td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="form-control input-sm" style="margin-left:4px; width:90px" readonly="" value="<?php echo $stock_principal[$row->id_producto]["cantidad"]?>" />
                        <td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" required="" id="tiempo_periodo<?php echo $key?>" class="form-control input-sm" style="margin-left:4px; width:50px" value="<?php echo $row->tiempo_periodo?>" readonly="" />
                        <input type="hidden" name="id_via_administracion[]" id="cmbTipoViaAdministracion<?php echo $key ?>" value="<?php echo $row->id_via_administracion?>"/>
                        <input type="hidden" name="posologia[]" id="posologia<?php echo $key ?>" value="<?php echo $row->posologia?>"/>
                        <input type="hidden" name="id_producto[]" id="id_producto" value="<?php echo $row->id_producto?>"/>
                        <input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>
                        <input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>
                        
                        <td><select disabled class="form-control input-sm via_administracion" style="width:110px" id="cmbTipoViaAdministracion<?php echo $key ?>" name="id_via_administracion[]">
                          <?php if ($row->id_via_administracion != "") {?>
                          <option <?php if($row->id_via_administracion == "0") echo 'selected' ?> value="0">--Seleccionar--</option>
                          <option <?php if($row->id_via_administracion == "1") echo 'selected' ?> value="1">Oral</option>
                          <option <?php if($row->id_via_administracion == "2") echo 'selected' ?> value="2">Sublingual</option>
                          <option <?php if($row->id_via_administracion == "3") echo 'selected' ?> value="3">Rectal</option>
                          <option <?php if($row->id_via_administracion == "4") echo 'selected' ?> value="4">Intravenosa</option>
                          <option <?php if($row->id_via_administracion == "5") echo 'selected' ?> value="5">Intramuscular</option>
                          <option <?php if($row->id_via_administracion == "6") echo 'selected' ?> value="6">Subcutánea</option>
                          <option <?php if($row->id_via_administracion == "7") echo 'selected' ?> value="7">Dérmica</option>
                          <option <?php if($row->id_via_administracion == "8") echo 'selected' ?> value="8">Nasal</option>
                          <option <?php if($row->id_via_administracion == "9") echo 'selected' ?> value="9">Oftalmológica</option>
                          <option <?php if($row->id_via_administracion == "10") echo 'selected' ?> value="10">Inhalatoria</option>
                          <option <?php if($row->id_via_administracion == "11") echo 'selected' ?> value="11">Epidural</option>
                          <option <?php if($row->id_via_administracion == "12") echo 'selected' ?> value="12">Intratecal</option>
                          <option <?php if($row->id_via_administracion == "13") echo 'selected' ?> value="13">Vaginal</option>
                          <option <?php if($row->id_via_administracion == "14") echo 'selected' ?> value="14">Intraarticular</option>
                          <option <?php if($row->id_via_administracion == "17") echo 'selected' ?> value="17">Parenteral</option>
                          <option <?php if($row->id_via_administracion == "15") echo 'selected' ?> value="15">No Aplica</option>
                          <option <?php if($row->id_via_administracion == "16") echo 'selected' ?> value="16">Otros</option>
                          <?php }?>
                        </select>
                        </td>
                        
                        <td>
                            <select disabled style="width:100px" class="form-control select-diagnostico id_produccion"  id="miSelectProd<?php echo $key?>" name="miSelectProd[]">
                                <option value="<?php if(isset($row->id_diagnosticos)) echo $row->id_diagnosticos?>">{{$row->cod_diagnostico}}</option>
                            </select>
                        </td>
                        <td><input name="posologia[]" id="posologia<?php echo $key ?>" readonly type="text" class="form-control input-sm posologia" style="margin-left:4px; width:150px" value="<?php echo $row->posologia?>"/>
                        <td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" data-toggle="tooltip" data-placement="top" name="nro_med_solictados[]" required="" id="nro_med_solictados<?php echo $key?>" class="form-control input-sm" style="margin-left:4px; width:60px" value="<?php echo $row->cantidad_prescrita?>" readonly="" /></td>
                        <td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="form-control input-sm" style="margin-left:4px; width:60px" value="<?php echo $row->cantidad_dispensada?>" /></td>
                        <td>
                          @if (Auth::user()->id_farmacia!=128)
                            <select class="form-control input-sm id_observacion" style="width:110px" id="cmbObservacion<?php echo $key ?>" name="id_observacion[]">
                              <option value="0"> </option>
                              <option value="1">Sin stock</option>
                              <!--option value="2">Atendido</option-->
                              <option value="18">Atendido</option>
                              <option value="3">No requiere</option>
                              <option value="4">No corresponde</option>
                              <option value="5">Mal prescrito</option>
                              <option value="6">Alerta DIGEMID</option>
                              @if (Auth::user()->id_farmacia==18)
                              <option value="19">Por Recoger</option>
                              @endif
                              <option value="20">No Petitorio</option>
                            </select>
                          @else
                            <select class="form-control input-sm id_observacion" style="width:110px" id="cmbObservacion<?php echo $key ?>" name="id_observacion[]">
                              <option value="0"> </option>
                              <option value="1">Sin stock</option>
                              <option value="18" <?php if(isset($row->nro_receta) && $row->nro_receta!=''): echo 'selected'; endif;?>>Atendido</option>
                              <option value="2">Paciente tiene</option>
                              <option value="3">No requiere</option>
                              <option value="4">No corresponde</option>
                              <option value="6">Alerta DIGEMID</option>
                              <option value="7">Suspendido</option>
                              <option value="17">RP</option>
                              <option value="8">Mal prescrito - A</option>
                              <option value="9">Mal prescrito - B</option>
                              <option value="10">Mal prescrito - C</option>
                              <option value="11">Mal prescrito - D</option>
                              <option value="12">Mal prescrito - E</option>
                              <option value="13">Mal prescrito - F</option>
                              <option value="14">Mal prescrito - G</option>
                              <option value="15">Mal prescrito - H</option>
                              <option value="16">Mal prescrito - I</option>
                              <option value="20">No Petitorio</option>
                            </select>
                          @endif
                        </td>
                        <?php if($receta->id_farmacia==128): ?>    
                          <td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="dia_farmacia[]" required="" id="dia_farmacia<?php echo $key?>" maxlength="2" class="form-control input-sm" style="margin-left:4px; width:50px" value="" /></td>
                        <?php else: ?>
                          <td><input type="text" name="receta_nro[]" id="receta_nro<?php echo $key?>" class="form-control input-sm" style="margin-left:4px; width:80px" value="<?php echo $row->nro_receta?>" readonly="" /></td>
                          <!--input type="hidden" name="receta_nro[]" id="receta_nro<?php echo $key?>" value="" /-->
                        <?php endif; ?>    
                      </tr>
                    <?php endforeach;?>
                    </tbody>
                                    </table>
                
                
                                </div>
                            <?php if($receta->id_farmacia==128): ?>          
                                <div class="form-group">
                                     <div class="col-md-12">
                                      <label>LEYENDA MAL PRESCRITO</label>
                                      <div class="col-md-12">
                                        <div class="col-md-4">
                                          A = CIE 10 (DIAGNÓSTICO)</br>
                                          B = N DE CAMA </br>
                                          C = NOMBRE DEL SERVICIO</br>
                                          D = CONCENTRACION DEL PRODUCTO FARMACEÚTICO
                                        </div>
                                        <div class="col-md-4">
                                          E = FORMA FARMACEUTICA DEL PRODUCTO FARMACEÚTICO </br>
                                          F = VIA DE ADMINISTRACIÓN </br>
                                          G = CANTIDAD PRESCRITA</br>
                                          H = FIRMA DEL MÉDICO PRESCRIPTOR
                                        </div>
                                        <div class="col-md-4">
                                          I = ERROR DE DOSIFICACIÓN</br>
                                          RP = SEGÚN INDICACIÓN MÉDICA
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                            <div id="ver-obs-regreceta" style="<?php if ($flagfiscalizador == "1" || $flagresponsablefarmacia == "1") {
                                                
                                            } else {
                                                echo "display: none;";
                                            } ?>">
                                
                                <div class="form-group">
                                     <div class="col-md-12">
                                    <label>OBSERVACIÓN DE REGISTRO</label>
                                    <textarea class="form-control" cols="5" id="obs_registro" name="obs_registro" tabindex="11"></textarea>
                                     </div>
                                </div>
                            </div>

                            <div class="col-md-12 text-center">
                                <label>Nro. Recetas dispensadas y atenciones en el mes actual: </label>&nbsp;&nbsp;
                                <button type="button" data-toggle="modal" class="btn btn-primary btn-xs" onclick="show_Atenciones('<?php echo $receta->dni_beneficiario ?>')"><i class="fa fa-list"></i> Detalle</button>
                            </div>
                            <div class="col-md-12 text-center">
                                <?php if($receta->id_farmacia==128 && $receta->tipo_receta=='HOSPITALIZACION'):
                                        if($receta->id_evaluacion  == null): ?>
                                          <label>Ver atencion: </label>&nbsp;&nbsp;
                                          <button type="button" data-toggle="modal" class="btn btn-primary btn-xs" onclick="ver_det_atencion_evaluacion({{$receta->id_prestacion}})"><i class="fa fa-eye"></i> </button>
                                        <?php else: ?>
                                          <label>Ver Evolución: </label>&nbsp;&nbsp;
                                          <button type="button" data-toggle="modal" class="btn btn-primary btn-xs" onclick="ver_det_evaluacion({{$receta->id_evaluacion}})"><i class="fa fa-eye"></i> </button>
                                        <?php endif; ?>
                                        <label>Ver HC: </label>&nbsp;&nbsp;
                                        <a href="/ver_atenciones_evaluacion_farmacia/{{$receta->id_ext}}/4/3" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a>
                                        <label>Ver Terapeutica: </label>&nbsp;&nbsp;
                                        <a href="javascript:void(0)" onClick="ver_det_indicaciones_terapeutica({{$receta->id_ext}})" class="btn btn-danger btn-xs"> <i class="fa fa-edit"></i></a>
                                <?php endif; ?>

                                <?php /*if(count($dato_hoja_terapeutica)>0): ?>
                                  <br>
                                    <label>Esta Receta contiene su Hoja de Tratamiento Terapeutico: </label>&nbsp;&nbsp;
                                    <?php    if($dato_hoja_terapeutica->fecha_firma_terapeutica!=""): ?>
                                      <a href="/{{$dato_hoja_terapeutica->flag_terapeutica}}/{{$dato_hoja_terapeutica->ruta_terapeutica}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                    <?php    else: ?>
                                      <a href="#" target="_blank" disabled="disabled" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                    
                                  
                                <?php endif;
                                    endif;*/
                                 ?>
                                
                            </div>


                            <div class="form-group">
                                <div class="col-md-6">
                                    <a href="/all_recetas_prescritas_by_farmacia" class="form-control btn btn-danger" tabindex="99922" style="text-align:center!important">CANCELAR</a>
                                </div>
                                <div class="col-md-6">
                                    <!--<input id="btnReg" class="form-control btn btn-success" type="submit" value="GUARDAR" tabindex="99921">-->
                                    <button id="btnReg" <?php echo $disabled_receta?> class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()" style="text-align:center!important">GUARDAR</button>
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
                    <!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title" id="lineModalLabel">Dispensar</h4>
                    <div id="alerta" class="alert alert-danger"></div>
                </div>
                <div class="modal-body">
                    <table border="0px" id="tabla_lote">
                        <thead>
                            <tr>
                                <th style="width:10%">CÓdigo</th>   
                                <th style="width:40%">Nombre</th> 
                                <th style="width:10%">Lote</th> 
                                <th style="width:10%">Reg. San.</th> 
                                <th style="width:10%">Precio</th>
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
                            <div class="col-md-6 text-center">
                                <button type="button" onclick="sumarCantidadLotes(0)" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Guardar</button>
                            </div>
                            <div class="col-md-6 text-center">
                                <button type="button" onclick="sumarCantidadLotes(1)" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Guardar y Agregar Nueva Fila</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="showAtencionesMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><small style="color: #fff">Nro. Recetas dispensadas y atenciones en el mes actual</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Nro. Receta</small></th>
                                <th><small>Fecha <br/>Dispensación</small></th>
                                <th><small>Fecha <br/>Expedición</small></th>
                                <th><small>Consultorio</small></th>
                                <th><small>Farmacia</small></th>
                                <th><small>Médico</small></th>
                                <!--th><small>Diagnóstico</small></th-->
                                <th><small>Nro. Aten-<br>ciones</small></th>
                                <th><small>Detalle<br/>Producto</small></th>
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

<div class="modal fade" id="showAtencionesDetMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 22px; color: #fff">Nro. Atenciones según fecha de expedición</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th><small>Nro. <br/>Atención</small></th>
                                <th><small>Farmacia</small></th>
                                <th><small>Fecha <br/>Expedición</small></th>
                                <th><small>Consultorio</small></th>
                                <th><small>Médico</small></th>
                                <th><small>Nro Receta</small></th>
                                <th><small>Fecha <br/>Dispensación</small></th>
                                <th><small>Detalle<br/>Producto</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-det-atenciones"></tbody>
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
                                <th><small>Tiempo</small></th>
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

<div class="modal fade" id="showStockEstablecimiento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><small style="color: #fff">PROD. FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Nro</small></th>
                                <th><small>Farmacia</small></th>
                                <th><small>Código</small></th>
                                <th><small>Producto Farmacéutico o Dispositivo Médico</small></th>
                                <th><small>Unidad</small></th>
                                <th><small>Stock Actual</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-stock"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->
<div class="modal fade" id="myModalEvolucion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE LA EVALUACION MEDICA: <span id="nrorecmodal2"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-evaluacion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalAtencionEvaluacion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE ATENCION. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-atencion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- /.content-wrapper -->
<div class="modal fade" id="myModalIndicacionTerapeutica" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Indicaciones Terapeuticas <span id="nrorecmodal3"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-indicaciones-terapeuticas"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection
