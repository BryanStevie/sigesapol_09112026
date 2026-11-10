<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
<script>
  window.APP_CONFIG = { idFarmacia: {{ Auth::user()->id_farmacia }}, idEstablecimiento: {{ Auth::user()->id_establecimiento }} };
</script>
<style>
    .table-sortable tbody tr {
        cursor: move;
    }
    /*
#global {        
    width: 95%;        
    margin: 15px 15px 15px 15px;     
    height: 380px !important;        
    border: 1px solid #ddd;
    overflow-y: scroll !important;
}
    */
    #global {
        min-height: 650px !important;
        /*width: auto;*/
        width: 100%;
        border: 1px solid #ddd;
        margin:15px
            /* background: #f1f1f1;*/
            /*overflow-y: scroll !important;*/
    }

    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .row{
        margin-top:10px;
        padding: 0 10px;
    }
    .clickable{
        cursor: pointer;   
    }

    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;        
    }
    .panel-heading div span{
        margin-left:5px;
    }
    .panel-body{
        display: block;
    }

    .dataTables_filter {
        display: none;
    }
    
    .help-block {
        display: block;
        margin-top: 1px;
        margin-bottom: 10px;
        color: #737373;
    }
	
	.modal-dialog{
        width: 70% !important;
    }

    .icon-doc-firmado {
        color: #5cb85c;
        font-size: 3rem;
      }
      .swal2-icon.swal2-warning {
    font-size: 15px;
  }

  .swal-wide{
      width: 1000px !important;
      padding: 0px 20px !important;
  }
  .swal-wide-form{
            width: 500px !important;
            padding: 0px 20px !important;
        }
</style>

<script>

    //--------------------------------------------------------------------------
    $(document).ready(function () {

        $('#openModalSiteds').on('hidden.bs.modal', function() {
                location.reload();
            })

    $consul = $("#frmRequerimiento").find("#id_requerimiento");
    $consul.select2();
    //$('#fechaDesde').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true/*"timeOnly": true*/});
    //$('#fechaHasta').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true});

    $('#fechaDesde').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
    });
    $('#fechaHasta').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
            //"dateFormat": "YYYY-MM-DD hh:mm",
    });
    $('#fechaDesde').click(function () {
    var fechaDesdeIni = $('#fechaDesde').val();
    $('#fechaDesde').change(function () {
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    f1 = fechaDesde.split("-");
    f2 = fechaHasta.split("-");
    f3 = new Date();
    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

    if (f1 > f2) {
    bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
    $('#fechaDesde').val(fechaDesdeIni);
    return false;
    }
    });
    });
    $('#fechaHasta').click(function () {
    var fechaHastaIni = $('#fechaHasta').val();
    $('#fechaHasta').change(function () {
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    f1 = fechaDesde.split("-");
    f2 = fechaHasta.split("-");
    f3 = new Date();
    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

    if (f2 < f1) {
    bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
    $('#fechaHasta').val(fechaHastaIni);
    return false;
    }

    if (f2 > f3) {
    bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
    $('#fechaHasta').val(fechaHastaIni);
    return false;
    }
    });
    });
    $('#fechaHasta').trigger('change');
    $('#fechaDesde').trigger('change');
    var activeSystemClass = $('.list-group-item.active');
    /*
     //something is entered in search form
     $('#system-search').keyup(function () {
     var that = this;
     // affect all table rows on in systems table
     var tableBody = $('.table-list-search tbody');
     var tableRowsClass = $('.table-list-search tbody tr');
     $('.search-sf').remove();
     tableRowsClass.each(function (i, val) {
     
     //Lower text for case insensitive
     var rowText = $(val).text().toLowerCase();
     var inputText = $(that).val().toLowerCase();
     if (inputText != '')
     {
     $('.search-query-sf').remove();
     
     } else
     {
     $('.search-query-sf').remove();
     }
     
     if (rowText.indexOf(inputText) == -1)
     {
     //hide rows
     tableRowsClass.eq(i).hide();
     
     } else
     {
     $('.search-sf').remove();
     tableRowsClass.eq(i).show();
     }
     });
     //all tr elements are hidden
     if (tableRowsClass.children(':visible').length == 0)
     {
     tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No existe la receta que esta buscando.</td></tr>');
     }
     });
     
     */
    });
    $(document).ready(function () {
    $('#example').DataTable({
    /*"searching": false*/
    "language": {
    "emptyTable": "No se encontraron resultados"
    }
    });
    $("#system-search").keyup(function() {
    var dataTable = $('#example').dataTable();
    dataTable.fnFilter(this.value);
    });
    $("#iddispensador").select2({
    width: 'resolve' // need to override the changed default
    });
    $("#id_consultorio").select2({
    width: 'resolve' // need to override the changed default
    });
    $("#iddispensador").trigger('change');
    });
    function modalSiteds(id) {

        $(".modal-dialog").css("width", "80%");
        $('#openModalSiteds').modal('show');
        $('#openModalSiteds.modal-body').css('height', 'auto');

        $.ajax({
            url: "modal_siteds_ambulatorio/" + id,
            type: "GET",
            success: function(result) {
                $(".modal-siteds").html(result);
            }
        });

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

      function completarConCeros(numero, longitud) {
          let numeroString = numero.toString();
          while (numeroString.length < longitud) {
            numeroString = '0' + numeroString;
          }
          return numeroString;
        }

      function validateSitedsFrm(){   

            var msg = "";
            var cod_siteds= $('#codigo_siteds').val();  
            if ($('#frmsiteds').find("#codigo_siteds").val() == "") {
                bootbox.alert("Recuerde que tiene que Ingresar el CÓDIGO SITEDS", function(){     
                document.getElementById("codigo_siteds").click();
                  setTimeout(function (){ 
                    $('#codigo_siteds').focus();
                }, 500);
              });
              return false;
            }else{
              ///tomamos el numero ingresado en el codigo siteds
              $("#codigo_siteds").val(completarConCeros(cod_siteds,12));  
            }
            
            $("#frmsiteds").submit();
            /*var codSiteds= $('#codigo_siteds').val();  
              $.ajax({
                url:'{{url('validar_dato_siteds_ambulatorio/')}}/'+codSiteds,
                type:'GET',
                dataType:'JSON', 
                  success: function(data){   
                    if(data!=null){ 
                      console.log('data[0].cantidad_siteds:'+ data.cantidad_siteds); 
                      if(data.cantidad_siteds!=0){ 
                          Swal.fire({
                            title: '<div class="titulo-padding"><p>Alerta de Registro </p></div>',
                            html: '<p class="descripcion-alert"><span>EL código SITEDS <b style="color:red;">'+codSiteds+'</b> YA EXISTE, verifique que sea correcto y vuelva a intentarlo.<br><br></span></p>', 
                            allowOutsideClick: false,
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                          }); 
                          $('#codigo_siteds').val(''); 
                          setTimeout(function (){ 
                              $('#codigo_siteds').focus();
                          }, 2500);
                      } else{ 
                        $("#frmsiteds").submit();
                        return false;
                      } 
                    } else{ 
                      $("#frmsiteds").submit();
                      return false;
                    }
                  }
              });*/
        };

    function reporteReceta(){

    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_receta = $('#nro_receta').val();
    var estado = $('#estado').val();
    var id_dispensador = $('#iddispensador').val();
    var id_consultorio = $('#id_consultorio').val();
    if (fechaDesde == "")fechaDesde = 0;
    if (fechaHasta == "")fechaHasta = 0;
    if (dni_beneficiario == "")dni_beneficiario = 0;
    if (nro_receta == "")nro_receta = 0;
    if (id_dispensador == "")id_dispensador = 0;
    if (id_consultorio == "")id_consultorio = 0;
    location.href = 'exportar_reporte_receta/' + fechaDesde + '/' + fechaHasta + '/<?php echo $id_farmacia ?>/' + dni_beneficiario + '/' + nro_receta + '/' + estado + '/' + id_dispensador + '/' + id_consultorio;
    }

    function ver_det_receta(idUser, nro_receta, id_receta){
    $('#nrorecmodal').text(nro_receta);
    $.ajax({
    url: '/ver_receta_atendida/2/' + idUser + '/' + nro_receta + '/' + id_receta,
            async: false,
            success: function(result){
            $("#muestra-det-receta").html(result);
            },
            complete: function () {
            $("#myModalDetRV").modal();
            }
    });
    }



    function soloNumeros(e) {
    var key = window.Event ? e.which : e.keyCode;
    //alert(key);
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

    function regularizarSiteds(id, id_establecimiento) {
      Swal.fire ({
        title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Regularizando Código SITEDS</span></div>",
        html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false, showConfirmButton: false, allowOutsideClick: false, allowEscapeKey: false,
        onBeforeOpen: () => Swal.showLoading(),
      })
      const body = {
        recordId: id,
        moduleId: 1,
        establecimientoId: id_establecimiento,
        tipoAtencionId: "3",
        servicioId: "152"
      }
      $.ajax({
        type: "POST",
        url: "/regularize-multiple-siteds-temporary",
        contentType: "application/json",
        data: JSON.stringify(body),
        dataType: "json",
        success: function (response) {
          const messageSuccess = response.message || "¡La regularización de los códigos SITEDS temporales se completó con éxito!"
          Swal.fire({
            icon: 'success',
            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
            html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageSuccess}</span></div>`,
            confirmButtonText: 'ENTENDIDO',
          });
          $('#frmBloque').submit();
        },
        error: function(xhr, status, error){
            const session = {!! json_encode(Auth::user()) !!}
            const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
            if (hasPermissionUpdateSitedsManual) {
              showModalSitedsManual().then((resultModal) => {
                if (resultModal && resultModal.isConfirmed && resultModal.value) {
                  handlerSaveCodeSiteds(id, resultModal.value, "Se actualizó correctamente el código siteds.", 200);
                }
              })
            } else {
              const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
              Swal.fire({
                  icon: 'error',
                  title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                  html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                  confirmButtonText: 'ENTENDIDO',
              });
            }
        },
        complete: function(xhr, status) {
            Swal.hideLoading();
        }
      });
    }

    // TAGSITEDS
    function generarCodigoSiteds(id, aseguradoId, servicioId, id_establecimiento) {
      Swal.fire ({
        title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
        html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading()
        },
      })
      const body = {
        "idCorrelativo": id,
        "aseguradoId": aseguradoId,
        "establecimientoId": id_establecimiento,
        "tipoAtencionId": "19",
        "servicioId": servicioId
      };
      $.ajax({
        url:'/v2/ws-dirsapol-siteds',
        type:'POST',
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(body),
        success: (result, textStatus, jqXHR) => {
            if (result && result.nuAutorizacion && (parseInt(jqXHR.status) === 200 || parseInt(jqXHR.status) === 201)) {
              handlerSaveCodeSiteds(id, result.nuAutorizacion, result.message, jqXHR.status);
            }  else {
              Swal.fire({
                  icon: 'error',
                  title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                  html: `<div style='margin: 0px 0px; padding: 0px 20px'>
                          <span style='color: #000000; font-size: 18px'>Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para regularizar el SITEDS manualmente. <a href="https://app8.susalud.gob.pe:8080/wb-siteds/login.htm" target='_blank'>SITEDS WEB</a></span>
                          </div>`,
                  confirmButtonText: 'ENTENDIDO',
              });
            }
        },
        error: function(xhr, status, error){
            const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
            Swal.fire({
                icon: 'error',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
        }, 
        complete: function(xhr, status) {
            Swal.hideLoading();
        }
      })
    }

    function handlerSaveCodeSiteds(id, nuAutorizacion, message = null, status = null) {
      Swal.fire ({
          title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
          html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Guardando el codigo siteds...</span></div>",
          showCancelButton: false,
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          onOpen: () => {
              Swal.showLoading();
          }
      })
      const auth = {!! json_encode(Auth::user()) !!};
      const actionUrl = "{{ route('update_siteds_receta') }}";
      let frmsiteds = $('<form/>', {
          id: 'frmsiteds',
          name: 'frmsiteds',
          method: 'POST',
          action: actionUrl,
      });

      frmsiteds.append($('<input>', { type: 'hidden', name: "_token", value: "{{ csrf_token() }}" }));
      frmsiteds.append($('<input>', { type: 'text', id: 'id', name: 'id', value: id }));
      frmsiteds.append($('<input>', { type: 'text', id: 'id_user', name: 'id_user', value: auth.id }));
      frmsiteds.append($('<input>', { type: 'text', id: 'codigo_siteds', name: 'codigo_siteds', value: nuAutorizacion }));

      $('body').append(frmsiteds);

      frmsiteds.on('submit', function(event) {
          event.preventDefault();
          $.ajax({
              url: frmsiteds.attr('action'),
              type: 'POST',
              data: $(this).serialize(),
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                Swal.fire({
                  icon: (parseInt(status) === 201 || parseInt(status) === 200) ? "success" : 'warning',
                  title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
                  html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>${message || " Operación Exitosa"}</span></div>`,
                  confirmButtonText: 'ENTENDIDO',
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                });
                $('#frmBloque').submit();
              },
              error: function(jqXHR, textStatus, errorThrown) {},
              complete: function(xhr, status) {
                Swal.hideLoading();
                frmsiteds.remove();
              }
          }); 
      });

      frmsiteds.trigger('submit');

    };

      // TAGSITEDS
    function generarCodigoSitedsMassive() {
      Swal.fire ({
          title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZACIÓN DE CÓDIGO SITEDS MASIVO</span></div>",
          html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
          showCancelButton: false,
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          onBeforeOpen: () => {
              Swal.showLoading ()
          },
      })
      $.ajax({
        url:'/generate-siteds-massive-receta',
        type:'POST',
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify({}),
        success: (result) => {
            let htmlString = `
                <p style="font-family: Arial, Helvetica, sans-serif; font-weight: light; font-size: 16px">${result.message || " - "}</p>
                <hr>
                <div style="display: flex; flex-direction: row; justify-content: center; font-size: 16px">
                    <table style="width: 70%; border-collapse: separate; border-spacing: 15px;">
                        <tr style="text-align: left">
                            <td style="border-bottom: 1px solid #060606; width: 55%">
                                <strong><i class="fa fa-list"></i> REGISTROS TOTALES:</strong>
                            </td>
                            <td style="border-bottom: 1px solid #060606; text-align: center; width: 15%">${result.cantidad_total ?? 0}</td>
                        </tr>

                        <tr style="text-align: left">
                            <td style="border-bottom: 1px solid #060606; width: 55%">
                                <strong><i class="fa fa-check-square-o"></i> CASOS EXITOSOS:</strong>
                            </td>
                            <td style="border-bottom: 1px solid #060606; text-align: center; width: 15%">${result.cantidad_generados_exito ?? 0}</td>
                        </tr>

                        <tr style="text-align: left">
                            <td style="border-bottom: 1px solid #060606; width: 55%">
                                <strong><i class="fa fa-exclamation-triangle"></i> CASOS FALLIDOS:</strong>
                            </td>
                            <td style="border-bottom: 1px solid #060606; text-align: center; width: 15%">${result.cantidad_generados_error ?? 0}</td>
                        </tr>
                    </table>
                </div>
                
            `;
            let customClass = "";
            if (result && result.respuesta_error && result.respuesta_error.length > 0) {
              customClass = "swal-wide";
              let tableBodyString = "";
              result.respuesta_error.forEach((element, index) => {
                tableBodyString += `<tr>
                                      <td style="border: 1px solid #ddd; padding: 8px; text-align: center">${index + 1}</td>
                                      <td style="border: 1px solid #ddd; padding: 8px; text-align: center">${element.receta_vale_id || "-"}</td>
                                      <td style="border: 1px solid #ddd; padding: 8px; text-align: center">${element.nroDocIdent || "-"}</td>
                                      <td style="border: 1px solid #ddd; padding: 8px; text-align: center; color: red ">${element.message || "-"}</td>
                                    </tr>`;
              });
              htmlString += `<div id="div_errors" >
                                <a href="#" id="btn_show_errors" style="text-decoration: none; font-family: Arial, Helvetica, sans-serif; font-weight: 500; font-size: 14px; margin-bottom: 20px">
                                  <i class="fa fa-eye"></i> MOSTRAR DETALLES DE LAS OBSERVACIONES
                                </a>
                                <hr>
                                <div style="max-height: 300px; overflow-y: auto;">
                                  <table hidden id="table_errors" style="width: 100%; text-align: center; font-size: 16px; border-collapse: collapse">
                                    <thead style="background-color: #f2f2f2; position: sticky; top: -2px; z-index: 10;">
                                      <tr>
                                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 5%">#</th>
                                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 15%">NRO. CITA</th>
                                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 15%">NRO.DOC.ASEGURADO</th>
                                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 65%">OBSERVACIÓN</th>
                                      </tr>
                                    </thead>
                                      <tbody id="table_errors_body" style="text-align: left">${tableBodyString}</tbody>
                                  </table>
                                </div>
                            </div>`;             
            }
            Swal.fire({
                icon: parseInt(result.cantidad_generados_exito) === 0 ? "warning" : "success",
                title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZACIÓN DE CÓDIGO SITEDS MASIVO</span></div>",
                html: htmlString,
                confirmButtonText: 'ENTENDIDO',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: customClass,
                didOpen: function() {
                  $("#btn_show_errors").click(function() {
                      $("#table_errors").toggle();
                      if ($("#table_errors").is(":visible")) {
                          $(this).html('<i class="fa fa-eye-slash"></i> OCULTAR DETALLES DE LAS OBSERVACIONES');
                      } else {
                          $(this).html('<i class="fa fa-eye"></i> MOSTRAR DETALLES DE LAS OBSERVACIONES');
                      }
                  });
                }
            });
            // $('#frmBloque').submit();
        },
        error: function(xhr, status, error){
            const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
            Swal.fire({
                icon: 'error',
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
        }, 
        complete: function(xhr, status) {
            Swal.hideLoading();
        }
      })
    }

    function showModalSitedsManual() {
      return new Promise((resolve, reject) => {
        Swal.fire({
          title: "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>Regularización Manual del SITEDS</span></div>",
          html: "<div style='padding: 0px 10px 10px 10px'><span style='color: #000000; font-size: 18px'>Le informamos que, para regularizar su código SITEDS, debe generarlo en la página oficial de SITEDS. Una vez generado, ingréselo en este formulario para continuar con el proceso.</span></div>",
          input: 'text',
          inputLabel: 'Código SITEDS',
          inputPlaceholder: "000000000001",
          showCancelButton: true,
          cancelButtonText: 'CANCELAR',
          confirmButtonText: 'GUARDAR',
          allowOutsideClick: false,
          allowEscapeKey: false,
          customClass: { confirmButton: 'btn btn-success', cancelButton: 'btn btn-danger', popup: 'swal-wide-form' },
          inputAttributes: { maxlength: 12, pattern: '[A-Za-z0-9 ]*' },
          buttonsStyling: false,
          inputValidator: (value) => {
              if (!value) {
                  return 'Ingresa el código SITED para continuar';
              } else if (value.length < 12) {
                  return 'El código SITEDS debe tener 12 caracteres';
              }
          }
        }).then((result) => {
            if (result.isConfirmed) {
                resolve(result);
            } else {
                reject(result);
            }
        }).catch((error) => {
            reject(error);
        });
      });
    }

    $(".btnEditRecetaVale").on("click", function () {
        const id = $(this).data('id');
        $('#modalEditRecetaVale')
            .data('id', id)
            .modal({show: true, backdrop: 'static', keyboard: false});
    });
    $('#modalEditRecetaVale').on('shown.bs.modal', function () {
        const id = $(this).data('id');
        const farmacia_id = $("#farmacia_id").val();
        $.ajax(
            {
                url: `/api/receta-vale/farmacia/tipo_atencion/${farmacia_id}`,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    const $select = $("#merv_id_tipo_atencion");

                    $select.find("option:not(:first)").remove();

                    $.each(response, function(index, item) {
                        $select.append(
                            $("<option>", {
                                value: item.id_tipo_atencion,
                                text: item.nombre_tipo_atencion
                            })
                        );
                    });
                }
            }
        );
    });
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Buscar Recetas Registradas - {{$farmacia->nombre}}</h3>
        </div>  
        <div class="panel-body">
            <input type="hidden" value="{{$farmacia->id}}" id="farmacia_id">
            <div class="col-sm-12">
                <form class="form-horizontal" method="post" id="frmBloque" action="{{ route('listar_recetas_nuevo')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <label class="col-md-2 control-label" for="textinput">Nro. Documento</label>  
                        <div class="col-md-2">
                            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                        </div>
                        <label class="col-md-1 control-label" for="textinput">Nro. Receta</label>  
                        <div class="col-md-2">
                            <input id="nro_receta" name="nro_receta" type="text" placeholder="" class="form-control input-sm" value="<?php echo $nro_receta ?>" onKeyPress="return soloNumeros(event)" maxlength="12">
                        </div>
                        <label class="col-md-1 control-label">Estado</label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" name="estado"  id="estado" tabindex="15">
                                <option value="1" <?php if (isset($estado) && $estado == 1) echo "selected='selected'"; ?>>Atendidas</option>
                                <option value="2" <?php if (isset($estado) && $estado == 2) echo "selected='selected'"; ?>>Invalidadas</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                        </div>
                    </div>
                    <div style="clear:both;margin-bottom:10px"></div>
                    <div class="row">
                        <label class="col-md-2 control-label">Registrador</label>
                        <div class="col-md-3">
                            <select class="form-control input-sm" name="iddispensador" id="iddispensador" tabindex="16" style="width: 100%">
                                <option value="">Todos</option>
                                <?php
                                foreach ($dispensador as $d) {
                                    echo '<option value="' . $d->id_user . '"';
                                    if (isset($iddispensador)) {
                                        if ($iddispensador == $d->id_user)
                                            echo " selected";
                                    }
                                    echo '>' . $d->nombre . ' ' . $d->paterno . ' ' . $d->materno . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <label class="col-md-2 control-label">servicio</label>
                        <div class="col-md-3">
                            @if($id_farmacia == 19)
                            <select class="form-control input-sm" name="id_consultorio" id="id_consultorio" tabindex="16" style="width: 100%">
                                <option value="">Todos</option>
                                <?php
                                foreach ($consultorio as $c) {
                                    echo '<option value="' . $c->id . '"';
                                    if (isset($id_consultorio)) {
                                        if ($id_consultorio == $c->id)
                                            echo " selected";
                                    }
                                    echo '>' . $c->codigo.' - '. $c->descripcion. '</option>';
                                }
                                ?>
                            </select>
                            @else
                            <select class="form-control input-sm" name="id_consultorio" id="id_consultorio" tabindex="16" style="width: 100%">
                                <option value="">Todos</option>
                                <?php
                                foreach ($consultorio as $c) {
                                    echo '<option value="' . $c->id . '"';
                                    if (isset($id_consultorio)) {
                                        if ($id_consultorio == $c->id)
                                            echo " selected";
                                    }
                                    echo '>' . $c->nombre. '</option>';
                                }
                                ?>
                            </select>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteReceta()" >
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                            </a>
                        </div>
                    </div>

                    <div style="clear:both;margin-bottom:10px"></div>
                    <label class="col-md-2 control-label">Fecha Desde</label>
                    <div class="col-md-3">
                        <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                        <p class="help-block">Fecha Dispensaci&oacute;n</p>
                        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                    </div>
                    <label class="col-md-2 control-label">Fecha Hasta</label>
                    <div class="col-md-3">
                        <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                        <p class="help-block">Fecha Dispensaci&oacute;n</p>
                    </div>
                    <div class="col-md-2">
                        {{-- TAGSITEDS --}}
                        @if(intval(Auth::user()->flag_editor_siteds) === 1 )
                        
                          <a class="btn btn-warning form-control input-sm" href="javascript:void(0)" onclick="generarCodigoSitedsMassive()">
                          <i class="fa fa-cogs" aria-hidden="true"></i> Regularizar Siteds Masivo
                          </a>
                        
                        @endif
                    </div>
                    <!--		
                    <div class="col-md-2 col-md-offset-5">
                            <input class="form-control btn btn-info" value="Buscar" type="submit">
                    <form class="form-horizontal" method="post" id="frmBloque" action="{{ route('listar_recetas_nuevo')}}"></div>
                    -->
                    <!--
<div class="col-md-6">
    <button class="btn btn-info" type="submit">
        <i class="fa fa-search"></i>
    </button>
</div>
                    -->
                    <!--
<div class="col-md-3">
    <a href="/all_recetas_atendidas_by_farmacia/{{ Auth::user()->id }}" class="btn btn-info" type="submit">
        <i class="fa fa-list"></i> Lista de Recetas atendidas
    </a>
</div>
                    -->
                </form>
            </div>
                          
            <div class="col-md-12">
                <input class="form-control input-sm" id="system-search" name="q" placeholder="Buscar receta">                        
            </div>

            <?php //print_r($recetas);  ?>
            <div class="col-md-12"  id="global">
              <div class="table-responsive">
                <!--<table  class="table table-list-search">-->
                <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >
                    <thead>
                        <tr>
                            <th>N</th>
                            <?php if ($farmacia->id == 21): ?>
                                <th>TIPO USO</th>
                            <?php endif; ?>
                            <th>Nro. Receta</th>
                            <?php if ($farmacia->id == 22 || $farmacia->id == 14): ?>
                                <th>RECETA</th>
                            <?php endif; ?>
                            <?php if ($farmacia->id == 20): ?>
                            <th>Interconsulta</th>
                            <?php endif; ?>
                            <th>Especialidad</th>
                            <th>Hora<br/>Registro</th>
                            <th>Fecha<br/>Dispensación</th>
                            <th>Fecha<br/>Expedición</th>
                            <th>Tipo Doc.</th>
                            <th>Nro. Doc.</th>
                            <th>Beneficiario</th>
                            <th>Tipo</th>
                            <th>Registrador</th>
                            <th>Cod SITEDS</th>
                            @if(Auth::user()->identificativo!=1)	
                              @if(Auth::user()->flagfiscalizador==1)  						
                                <th>Editar</th>
                              @endif
                            <th>Invalidar</th>
                            @endif                            
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($recetas as $r)
                        <tr>
                            <td><?php
                                $n++;
                                echo $n;
                                ?></td>
                            <?php if ($farmacia->id == 21): ?>
                                <td>{{$r->tipo_via}}</td>
                            <?php endif; ?>
                            <td>{{$r->nro_receta}}</td>
                            <?php if ($farmacia->id == 22 || $farmacia->id == 14): ?>
                                <td>{{$r->tipo_receta}}</td>
                            <?php endif; ?>
                            <?php if ($farmacia->id == 20): ?>
                            <td>{{$r->interconsulta}}</td>
                            <?php endif; ?>
                            <td>
                                <?php if(($r->tipo_receta=='EMERGENCIA') && ($r->id_farmacia==19)):?>
                                    {{$r->tipo_receta}}
                                <?php else: ?>
                                    {{$r->consultorio}}
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $originalDate3 = $r->updated_at;
                                if($originalDate3 != null):
                                  $horaR = date("H:i:s", strtotime($originalDate3));
                                else:
                                  $originalDate3 = $r->created_at;
                                  $horaR = date("H:i:s", strtotime($originalDate3));
                                endif;
                                echo $horaR;
                                ?> 
                            </td>
                            <td>
                                <?php
                                $originalDate1 = $r->fecha_expedicion;
                                $fechaE = date("d-m-Y", strtotime($originalDate1));
                                echo $fechaE;
                                ?> 
                            </td>
                            <td>
                                <?php
                                $originalDate2 = $r->fecha_registro;
                                $fecha = date("d-m-Y", strtotime($originalDate2));
                                echo $fecha;
                                ?> 
                            </td>
                            <td>{{$r->tipodoc_beneficiario}}</td> 
                            <td>{{$r->dni_beneficiario}}</td> 
                            <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>   
                            <td>{{$r->tipo_beneficiario}}</td> 
                            <td>{{$r->paterno_usuario}} {{$r->materno_usuario}}, {{$r->nombre_usuario}}</td>
                            <td>
                                <?php
                                  $typeLabel = "label label-success";
                                  $showTooltip = "";
                                  if (substr($r->codigo_siteds, -1) === 'T' || strlen($r->codigo_siteds) <= 6) {
                                    $typeLabel = "label label-warning";
                                    $showTooltip = 'tooltip';
                                  }
                                ?>
                                <p data-toggle={{$showTooltip}} data-placement="top" title="Código Siteds Temporal"><span class="{{$typeLabel}}" style='font-weight: lighter; font-size: 10px'>{{strtoupper($r->codigo_siteds)}}</span></p>
                                <?php 
                                  $createdAt = date("d-m-Y", strtotime($r->fecha_registro));
                                  $currentDate = date("d-m-Y");
                                ?>
                                {{-- @if(strval($createdAt) === strval($currentDate) && (substr($r->codigo_siteds, -1) === 'T' || strlen($r->codigo_siteds) <= 6)) --}}
                                @if(substr($r->codigo_siteds, -1) === 'T' || strlen($r->codigo_siteds) <= 6)
                                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>SITEDS</b>">
                                    {{--<button type="button" class="btn {{intval($r->siteds_expirado) == 0 ? "btn-success" : "btn-warning"}}  btn-xs" onclick="modalSiteds('{{$r->id}}')"><i class="fa fa-edit"></i></button>--}}
                                    <button 
                                      type="button" 
                                      class="btn {{intval($r->siteds_expirado) == 0 ? "btn-success" : "btn-warning"}}  btn-xs" 
                                      onclick="regularizarSiteds('{{$r->id}}', '{{$r->id_establecimiento}}')"
                                    >
                                      <i class="fa fa-edit"></i>
                                    </button>
                                  </div>
                                @endif
                            </td>
                            @if(Auth::user()->identificativo!=1)
                              @if(Auth::user()->flagfiscalizador==1)
                              <?php 

                                  $originalDate3 = $r->fecha_expedicion;
                                  $fecha = date("Y-m-d", strtotime($originalDate3));
                                  $fecha2 = strtotime("$fecha + 2 day");
                                  $fechaHoy = date("Y-m-d"); 
                                  $fechaHoy2 = strtotime("$fechaHoy ");
                                  $diasDiferencia = $fechaHoy2 - $fecha2;
                                  if($diasDiferencia <= 0){
                              ?>
                							  <td class="text-center">
                                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar Receta Vale</b>">
									                  <button type="button" class="btn btn-primary btn-xs btnEditRecetaVale" data-id="{{$r->id}}"><i class="fa fa-edit"></i></button>
                                  </div>
                                </td>
                              <?php
                                }
                                else
                                {
                              ?>
                              <td class="text-center">
                                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar Receta Vale</b>">
                                    <button disabled type="button" class="btn btn-primary btn-xs btnEditRecetaVale" data-id="{{$r->id}}"><i class="fa fa-edit"></i></button>
                                  </div>
                                </td>
                              <?php
                                }
                              ?>

                              @endif
                            <td class="text-center">
                                <?php
                                $id_receta = (isset($r->id_receta) && $r->id_receta > 0) ? $r->id_receta : "";
                                if (isset($estado) && $estado == 2) {
                                    ?>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Invalidar Receta</b>">
                                        <button type="button" class="btn btn-danger btn-xs" disabled=""><i class="fa fa-xing"></i></button>
                                    </div>
                                <?php } else { ?>
                                    <?php if ($farmacia->id == 20){ 
                                            if (Auth::user()->flagresponsablefarmacia == 1){ ?>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Invalidar Receta</b>">
                                                    <a href="/ver_receta_atendida/1/{{ Auth::user()->id }}/{{$r->nro_receta}}/{{$r->id}}" class="btn btn-danger btn-xs"><i class="fa fa-xing"></i></a>
                                                </div>
                                      <?php }
                                            else
                                            { ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Invalidar Receta</b>">
                                                <button type="button" class="btn btn-danger btn-xs" disabled=""><i class="fa fa-xing"></i></button>
                                            </div>                                    
                                    <?php   }
                                        }
                                        else
                                        { ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Invalidar Receta</b>">
                                                <a href="/ver_receta_atendida/1/{{ Auth::user()->id }}/{{$r->nro_receta}}/{{$r->id}}" class="btn btn-danger btn-xs"><i class="fa fa-xing"></i></a>
                                            </div>
                                <?php   } 
                                    }
                                 ?>
                            </td>
                            @endif                            <td class="text-center">
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver detalle receta</b>">
                                    <button type="button" class="btn btn-success btn-xs" onclick="ver_det_receta('{{ Auth::user()->id }}','{{$r->nro_receta}}','{{$r->id}}')"><i class="fa fa-search"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach                      
                    </tbody>
                </table>   
              </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalDetRV" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE RECETA VALE NRO. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-receta"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="openModalSiteds" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-hc">
            <div id="" class="modal-content" style="padding: 0px;margin: 0px;">
                <div class="modal-body modal-siteds">
                </div>
            </div>
        </div>
    </div>
@include('frontend.receta_vales.components.modal_editar_receta_vale')
@include('frontend.includes.footer')
@endsection
