@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
<!-- Incluir jQuery -->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<style>
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
  .table-row {
    cursor: pointer;
    transition: background-color 0.3s  !important;
  }

  .table-row:hover {
    background-color: lightgreen !important;
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
  .glyphicon {
      font-family: 'Glyphicons Halflings';
      font-style: normal;
      font-weight: normal;
      line-height: 1;
      position: relative;
      top: 1px;
      display: inline-block;
      font-size: inherit;
      text-rendering: auto;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
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

<script>

  const FARMACIA_ONCOLOGICA_LNS = 10;
  const FARMACIA_CENTRAL_GERIATRICO = 14;
  const FARMACIA_EMERGENCIA_LNS = 19;
  const FARMACIA_SOPORTE_NUTRICIONAL_LNS = 21;
  const FARMACIA_CENTRAL_ABL = 22;
  const FARMACIA_CENTRAL_HOSPITAL_AREQUIPA = 107;
  const FARMACIA_FARMACOTECNIA_LNS = 132;

  const SERVICIO_ODONTOLOGIA = 21;
  const SERVICIO_HEMATOLOGIA = 44;
  const SERVICIO_ONCOLOGIA = 70;
  const SERVICIO_ANESTESIOLOGIA = 72;
  const SERVICIO_EMERGENCIA = 76;
  const SERVICIO_ONCOHEMA = 104;
  const SERVICIO_HOSPTIALIZACION = 105;
  const SERVICIO_ENTERAL = 110;
  const SERVICIO_PARENTERAL = 111;

  const TIPO_ATENCION_EMERGENCIA = 2;
  const TIPO_ATENCION_HOSPITALIZACION = 3;
  const TIPO_ATENCION_SERVICIO_NUTRICIONAL_AMBULATORIO = 5;

var fechaHoraCompleto = new Date('{{$interconsulta->created_at}}');
var procedimientosSeleccionados =[];
  var listIdDiagnostico =[];
  var listIdDiagnosticoObjetos =[];

 var filasmedicamentos = 0;

function pad(num) {
    return num < 10 ? '0' + num : num;
}

// Extraer la fecha en formato "día-mes-año"
var dia = pad(fechaHoraCompleto.getDate());
var mes = pad(fechaHoraCompleto.getMonth() + 1); // Los meses en JavaScript empiezan desde 0, así que añadimos 1
var anio = fechaHoraCompleto.getFullYear();
var fecha = `${dia}-${mes}-${anio}`;

// Extraer la hora en formato "hora:minutos" (24 horas)
var horas = pad(fechaHoraCompleto.getHours());
var minutos = pad(fechaHoraCompleto.getMinutes());
var hora = `${horas}:${minutos}`;

// Mostrar la fecha y la hora en la consola (puedes eliminar estas líneas si no las necesitas)

// Mostrar la fecha y la hora en la consola (puedes eliminar estas líneas si no las necesitas)

	$(document).ready(function() {
    modalInterconsulta();
    modalHistorial1();
    modalHistorial2();
    modalHistorial3();
    modalHistorialEvaluaciones();
    modalHistorialEvaluacionesHospitalizacion();
    modalLaboratorio();
    modalRadiologico();
    modalRC();

    $('#datepicker2').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
    //$('#fecha_atencion').datepicker();
    
    /*$(".fc-event-container").on("click", function (e) {
      alert("cssc");
    });*/
    
    
    $('#btnPrestacion').on("click", function (e) {
      guardarPrestacion();
    });
    
    $('#addDiagnostico').on('click',function(){
      AddDiagnostico();
    });
    
    $('#addProcedimiento').on('click',function(){
      AddProcedimiento();
    });

    $('#addProducto').on('click', function () {
      AddProducto();
    });

    $('#addExamenLab2').on('click',function(){
      AddExamenLab();
    });

    // $('#addExamenRad2').on('click',function(){
    //   AddExamenRad();
    // });
    ////////////////////////////////////////////////////////////////////////////// NUEVA MODIFICACION EXAMEN DE IMÁGENES 15-05-26 //ACTUALIZADO 29-05-26
    var nivel = {{ $nivel }};
    if(nivel === 3){
      let enviandoFormulario = false;
      var idImagenologiaActual = null;
      var imagenologiaModalMostrado = false;
      $('#addExamenRad2').off('click').on('click', function() {
        if (!imagenologiaModalMostrado) {
          $('#rad_anamnesis').val('');
          $('#rad_examen_fisico').val('');
          $('#rad_examen_auxiliar').val('');
          $('#detalleModal').modal('show');
        } else {
          AddExamenRad();
        }
      });

      $('#tblExamenRad tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();

        var totalFilas = $('#tblExamenRad tbody tr').length;
        if (totalFilas === 0) {
          imagenologiaModalMostrado = false;
          if (idImagenologiaActual) {
            $.ajax({
              url: '/actualizar_estado_imagenologia',
              type: 'POST',
              data: {
              _token: $('meta[name="csrf-token"]').attr('content'),
              id: idImagenologiaActual 
            }
          });
        }
        }
      });

      $('#btnConfirmarImagenologia').off('click').on('click', function() {
        var anamnesis         = $.trim($('#rad_anamnesis').val());
        var examen_fisico     = $.trim($('#rad_examen_fisico').val());
        var examen_auxiliar   = $.trim($('#rad_examen_auxiliar').val());
        var errores = [];

        function validarCampo(texto, nombreCampo) {
          if (texto === '') {
            errores.push('Debe ingresar ' + nombreCampo + '.');
            return;
          }

          if (texto.length < 20) {
            errores.push(nombreCampo + ' debe tener mínimo 20 caracteres.');
          }

          if (!/[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]/.test(texto)) {
            errores.push(nombreCampo + ' no puede contener solo símbolos.');
          }

          if (/^[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]+$/.test(texto)) {
            errores.push('Ingrese ' + nombreCampo + ' válido(a).');
          }

          if (/^([^\w\s])\1+$/.test(texto)) {
            errores.push(nombreCampo + ' contiene caracteres inválidos.');
          }
        }

        validarCampo(anamnesis, '<b>Anamnesis</b>');
        validarCampo(examen_fisico, '<b>Examen Físico</b>');
        validarCampo(examen_auxiliar, '<b>Examen Auxiliar</b>');

        if (errores.length > 0) {
          Swal.fire({
            icon: 'error',
            title: 'Campos requeridos',
            html: errores.join('<br>'),
            confirmButtonText: 'ENTENDIDO',
          });
          return false;
        }

        var id_emergencia      = 0;
        var id_asegurado       = $('#id_asegurado').val();
        var id_establecimiento = $('#id_establecimiento').val();
        var id_tipo_atencion   = $('#id_tipo_atencion').val();

        $('#btnConfirmarImagenologia').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

        $.ajax({
          url: '/guardar_anamnesis_imagenologia',
          type: 'POST',
          data: {
              _token:             $('meta[name="csrf-token"]').attr('content'),
              id_emergencia:      id_emergencia,
              id_asegurado:       id_asegurado,
              id_establecimiento: id_establecimiento,
              anamnesis:          anamnesis,
              examen_fisico:      examen_fisico,
              examen_auxiliar:    examen_auxiliar,
              id_tipo_atencion:   id_tipo_atencion,
          },
          success: function(response) {
            $('#btnConfirmarImagenologia').prop('disabled', false).html('<i class="fa fa-save"></i> <strong>CONFIRMAR Y AGREGAR EXAMEN</strong>');
            if (response.success) {
              idImagenologiaActual = response.id;
              $('#id_rua_imagenes').val(idImagenologiaActual);
              $('#detalleModal').modal('hide');
              imagenologiaModalMostrado = true;
              Swal.fire({
                icon: 'success',
                title: 'Guardado correctamente',
                showConfirmButton: false,
                timer: 1500,
              }).then(function() {
                  AddExamenRad();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                html: response.message || 'Intente nuevamente.',
                confirmButtonText: 'ENTENDIDO',
              });
            }
          },
          error: function(xhr) {
            $('#btnConfirmarImagenologia').prop('disabled', false).html('<i class="fa fa-save"></i> <strong>CONFIRMAR Y AGREGAR EXAMEN</strong>');

            var msg = 'Ocurrió un error al guardar los datos.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
            Swal.fire({
              icon: 'error',
              title: 'Error inesperado',
              html: msg,
              confirmButtonText: 'ENTENDIDO',
            });
          }
        });
      });

      $('#detalleModal').on('hidden.bs.modal', function() {
          if (!imagenologiaModalMostrado) {
              imagenologiaModalMostrado = false;
          }
      });

      window.addEventListener('beforeunload', function (e) {
        if (!enviandoFormulario && idImagenologiaActual) {
          navigator.sendBeacon('/actualizar_estado_imagenologia', new URLSearchParams({
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: idImagenologiaActual
          }));
        }
      });
      $('#frmPrestacion').on('submit', function() {
          enviandoFormulario = true;
      });
    }else{
    $('#addExamenRad2').on('click',function(){
      AddExamenRad();
    });
    }
    ////////////////////////////////////////////////////////////////////////////// FIN MODIFICACION EXAMEN DE IMÁGENES 15-05-26
    
    $('#addRecetaVale').on('click', function () {
      var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
      $("#fsRecetaVale").hide();
      $("#flag_add_receta_vale").val("0");
      if(addRecetaVale == "false"){
        //adicionado
        // getProducto();
        $("#fsRecetaVale").show();
        $("#flag_add_receta_vale").val("1");
      }
    });

    $('#addExamenLab').on('click', function () {
      var addExamenLab = $('#addExamenLab').attr("aria-pressed");
      $("#fsExamenLab").hide();
      $("#flag_add_orden_lab").val("0");
      if(addExamenLab == "false"){
        $("#fsExamenLab").show();
        $("#flag_add_orden_lab").val("1");
      }
    });

    $('#addExamenRad').on('click', function () {
      var addExamenRad = $('#addExamenRad').attr("aria-pressed");
      $("#fsExamenRad").hide();
      $("#flag_add_orden_rad").val("0");
      if(addExamenRad == "false"){
        $("#fsExamenRad").show();
        $("#flag_add_orden_rad").val("1");
      }
    });

    $('#example tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
    
      // Selecciona la fila completa
      var fila = obj.closest('tr');
      
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      
      // Imprime la posición del elemento 'input' en la consola
      console.log("La posición del input es: " + filaPosition);
      eliminarDiagnosticoXProducto(filaPosition);
      // Elimina la fila
      obj.parent().parent().remove();
      filasmedicamentos--;
    });
    function eliminarDiagnosticoXProducto(posicion) {
      // Se elimina el elemento en la posición dada
      procedimientosSeleccionados.splice(posicion, 1);

      // Se actualiza la posición de los elementos restantes
      procedimientosSeleccionados.forEach((item, index) => {
        item.posicion = index;
      });
      console.log(procedimientosSeleccionados);
    }

    $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      var inputVal = obj.parent().siblings().find('input').val();
      let posicion =inputVal.indexOf("-");
      let codigo = inputVal.substring(0,posicion);
      eliminarDiagnosticosarr(codigo);
      console.log("el codigo es" + codigo);
      obj.parent().parent().remove();
    });
    
    $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });
  
    
    AddDiagnostico();
  });

  $('#tblExamenLab tbody').on('click', 'button.deleteFila', function () {
    let obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblExamenRad tbody').on('click', 'button.deleteFila', function () {
    let obj = $(this);
    obj.parent().parent().remove();
  });

function eliminarDiagnosticosarr(codigon){
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



  procedimientosSeleccionados= procedimientosSeleccionados.map((item) => {
    if (item.codigo === codigo2) {
      return { ...item, id: "" };
    } else {
      return item;
    }
  });

  console.log("la lista con eliminacion de los diagnosticos x productos es : " + JSON.stringify(procedimientosSeleccionados))


   
console.log("la lista con eliminacion es " + JSON.stringify(listIdDiagnosticoObjetos));
updateSelectElements();   

}



  function getProducto() {
    
    $('#example tbody').html('');

    var id_ipress = $('#id_establecimiento').val();
    var id_servicio = $('#id_servicio').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();
    var id_farmacia = $('#id_farmacia_receta').val();
    var nivel = $('#id_nivel').val();
    var dni = $('#dni_beneficiario').val();

    console.log(id_farmacia, id_servicio);

    if (id_tipo_atencion == TIPO_ATENCION_HOSPITALIZACION) {

      if (id_farmacia == FARMACIA_SOPORTE_NUTRICIONAL_LNS) { //nutricional
        
        if (id_tipo_atencion== TIPO_ATENCION_SERVICIO_NUTRICIONAL_AMBULATORIO || id_tipo_atencion == TIPO_ATENCION_HOSPITALIZACION) {
          id_servicio = SERVICIO_ENTERAL;
        } else{
          id_servicio = SERVICIO_PARENTERAL;
        }

        return $.ajax({
          url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+'/'+id_farmacia +'/' + id_servicio,
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
    
      } else {

        //if (id_consultorio==44 || id_consultorio==70){ //onco - hemato
        if (id_farmacia == FARMACIA_ONCOLOGICA_LNS) {
          if (id_servicio == SERVICIO_HEMATOLOGIA || id_servicio == SERVICIO_ONCOLOGIA) {
            return $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_servicio,
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

        } else{
          
          if (id_farmacia == FARMACIA_FARMACOTECNIA_LNS) {
              console.log("estamos en farmacia farmacotecnia");
              $.ajax({
              url: '/obtener_producto_farmacotecnia/' + id_ipress,
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
          else{
          switch (id_servicio) {
	            /*case SERVICIO_HEMATOLOGIA:
	            case SERVICIO_ONCOLOGIA:
	              return $.ajax({
	                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ SERVICIO_ONCOHEMA,
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
	              break;*/
	
	            case SERVICIO_ODONTOLOGIA:
	            case SERVICIO_ANESTESIOLOGIA:
	              return $.ajax({
	                //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
	                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ id_servicio,
	                success: function(result){                    
	                  var newOption = "";
	                  newOption = "<option value=''>--Seleccionar-</option>";
	                  $(result).each(function (ii, oo) {
	                    newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
	                  });                 
	                  $('#cmbMedicamentoTemp').html(newOption);
	                }
	              });
	                break;
	              default:
	                return $.ajax({
	                url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_servicio+ '/' + SERVICIO_HOSPTIALIZACION,
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
    } else if (id_tipo_atencion == TIPO_ATENCION_EMERGENCIA) {
      
      switch (parseInt(id_farmacia)) {
        case FARMACIA_SOPORTE_NUTRICIONAL_LNS: //nutricional
          console.log("estamos en farmacia nutricional");
          if (id_tiporeceta == TIPO_ATENCION_SERVICIO_NUTRICIONAL_AMBULATORIO) {
            id_servicio = SERVICIO_ENTERAL;
          } else {
            id_servicio = SERVICIO_PARENTERAL;
          }

          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +'/' + id_servicio,
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
          break;
      
        case FARMACIA_EMERGENCIA_LNS:
        case FARMACIA_CENTRAL_HOSPITAL_AREQUIPA:
        case FARMACIA_CENTRAL_ABL:
        case FARMACIA_CENTRAL_GERIATRICO:
          
          id_servicio = SERVICIO_EMERGENCIA;

          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + id_servicio,
            success: function(result) {                    
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });                 
              $('#cmbMedicamentoTemp').html(newOption);
              //$("#id_medico").select2({max_selected_options: 4});
            }
          });
          break;

        case FARMACIA_FARMACOTECNIA_LNS: //farmacotecnia
          console.log("estamos en farmacia farmacotecnia");
          $.ajax({
          url: '/obtener_producto_farmacotecnia/' + id_ipress,
            success: function(result){                    
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });                 
              $('#cmbMedicamentoTemp').html(newOption);
            }
          });
          break;
      }
    }
  }

  var texto_marca_agua_historial = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';

function generar_marca_agua_historial(overlayId) {
  var overlay = document.getElementById(overlayId);
  if (!overlay) return;
  overlay.innerHTML = '';
  var W = overlay.offsetWidth  || 800;
  var H = overlay.offsetHeight || 600;
  var cols = Math.ceil(W / 220) + 1;
  var rows = Math.ceil(H / 100) + 1;
  for (var r = 0; r < rows; r++) {
    for (var c = 0; c < cols; c++) {
      var span = document.createElement('span');
      span.textContent = texto_marca_agua_historial;
      span.style.left = (c * 220 - 40) + 'px';
      span.style.top  = (r * 100 - 20) + 'px';
      overlay.appendChild(span);
    }
  }
}

function registrarAuditoriaHistorialInterconsulta(idTipoAtencion) {
  $.ajax({
    url: '/auditoria-hc/registrar',
    type: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      origen: 'Interconsulta(HA)',
      doc_buscado: $('#dni_beneficiario').val(),
      id_tipo_atencion: idTipoAtencion
    }
  });
}

function registrarAuditoriaHistInterconsulta() {
  $.ajax({
    url: '/auditoria-hc/registrar',
    type: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      origen: 'Interconsulta (Hist. Interconsulta)',
      doc_buscado: $('#dni_beneficiario').val(),
      id_tipo_atencion: null
    }
  });
}

$(document).ready(function () {
  $('a[href="#tab4default"]').on('shown.bs.tab', function () {
    Swal.fire({
      icon: "warning",
      html: `<div style="padding: 0px 20px 20px 20px">
              <p style="font-size: 16px"><b>La historia clínica contiene datos sensibles y que su protección es esencial para el bienestar del paciente, así como para el cumplimiento de la normativa legal de privacidad y protección de datos</b></p><br>
              <span style="font-size: 14px; color: #d9534f"><b>Ley N.° 26659</b></span><br>
              <span style="font-size: 14px; color: #d9534f"><b>Ley de Protección de la Historia Clínica</b></span>
            </div>`,
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
      customClass: {
        container: 'swal-confidencialidad-interconsulta-backdrop'
      }
    });
    generar_marca_agua_historial('marca_agua_overlay_hist_ambulatorio');
    registrarAuditoriaHistorialInterconsulta(1);
  });

  $('a[href="#tab10default"]').on('shown.bs.tab', function () {
    generar_marca_agua_historial('marca_agua_overlay_hist_ambulatorio');
    registrarAuditoriaHistorialInterconsulta(1);
  });
  $('a[href="#tab11default"]').on('shown.bs.tab', function () {
    generar_marca_agua_historial('marca_agua_overlay_hist_emergencia');
    registrarAuditoriaHistorialInterconsulta(2);
  });
  $('a[href="#tab12default"]').on('shown.bs.tab', function () {
    generar_marca_agua_historial('marca_agua_overlay_hist_hospitalizacion');
    registrarAuditoriaHistorialInterconsulta(3);
  });

  $('a[href="#tab1007default"]').on('shown.bs.tab', function () {
    Swal.fire({
      icon: "warning",
      html: `<div style="padding: 0px 20px 20px 20px">
              <p style="font-size: 16px"><b>El historial de Interconsulta contiene datos sensibles y que su protección es esencial para el bienestar del paciente, así como para el cumplimiento de la normativa legal de privacidad y protección de datos y Ley.</b></p>
            </div>`,
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
      customClass: {
        container: 'swal-confidencialidad-hist-interconsulta-backdrop'
      }
    });
    modalInterconsulta();
    generar_marca_agua_historial('marca_agua_overlay_hist_interconsulta');
    registrarAuditoriaHistInterconsulta();
  });

  var resizeMarcaAguaHistorialTimer = null;
  $(window).on('resize', function () {
    clearTimeout(resizeMarcaAguaHistorialTimer);
    resizeMarcaAguaHistorialTimer = setTimeout(function () {
      if ($('#tab10default').is(':visible')) generar_marca_agua_historial('marca_agua_overlay_hist_ambulatorio');
      if ($('#tab11default').is(':visible')) generar_marca_agua_historial('marca_agua_overlay_hist_emergencia');
      if ($('#tab12default').is(':visible')) generar_marca_agua_historial('marca_agua_overlay_hist_hospitalizacion');
      if ($('#tab1007default').is(':visible')) generar_marca_agua_historial('marca_agua_overlay_hist_interconsulta');
    }, 250);
  });
});

function modalHistorial1(){
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "../../../modal_historial_atenciones_1/"+dni_beneficiario,
      type: "GET",
      success: function (result) {  
          $("#divHistorial1").html(result);
          $('#tablemodalm1a').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
          generar_marca_agua_historial('marca_agua_overlay_hist_ambulatorio');
      }
  });
}

function modalHistorial2(){
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "../../../modal_historial_atenciones_2/"+dni_beneficiario,
      type: "GET",
      success: function (result) {  
          $("#divHistorial2").html(result);
          $('#tablemodalm21').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
          generar_marca_agua_historial('marca_agua_overlay_hist_emergencia');
      }
  });
}

function modalHistorial3(){
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "../../../modal_historial_atenciones_3/"+dni_beneficiario,
      type: "GET",
      success: function (result) {  
          $("#divHistorial3").html(result);
          $('#tablemodalm3').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
          generar_marca_agua_historial('marca_agua_overlay_hist_hospitalizacion');
      }
  });
}

function modalHistorialEvaluaciones(){
  
  var id_asegurado = $("#id_asegurado").val();
  
  $.ajax({
      url: "../../../modal_historial_evaluaciones_emergencia/"+id_asegurado+"/2",
      type: "GET",
      success: function (result) {  
          $("#divEvaluacione").html(result);

          $('#tablemodalm1004').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
          generar_marca_agua_historial('marca_agua_overlay_hist_emergencia');
      }
  });
}

function modalHistorialEvaluacionesHospitalizacion(){
  
  var id_asegurado = $("#id_asegurado").val();
  var id_tipo_atencion = $("#id_tipo_atencion").val();

  $.ajax({
      url: "../../../modal_historial_evaluaciones_emergencia/"+id_asegurado+"/3",
      type: "GET",
      success: function (result) {  
          $("#divEvaluacioneHospitalizacion").html(result);

          $('#tablemodalm1005').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
          generar_marca_agua_historial('marca_agua_overlay_hist_hospitalizacion');
      }
  });
}

/*function modalHistorial(){
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "../../../modal_historial_atenciones/"+dni_beneficiario,
      type: "GET",
      success: function (result) {  
          $("#divHistorial").html(result);
          $('#tablemodalm').DataTable({
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}*/

function modalRC(){
  var id_asegurado = $("#id_asegurado").val();
  $.ajax({
    url: "../../../modal_historial_rc/"+id_asegurado,
    type: "GET",
    success: function (result) {
      $("#divRC").html(result);
      $('#tablemodalmRC').DataTable({
        "sPaginationType": "full_numbers",
        
        "language": {"url": "/js/Spanish.json"},
      });
    }
  });
}
function modalLaboratorio(){
  
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "../../../modal_historial_laboratorio/"+dni_beneficiario,
      type: "GET",
      success: function (result) {  
          $("#divLaboratorio").html(result);
          $('#tablemodalm1').DataTable({
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}
function modalRadiologico(){
  
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "../../../modal_historial_radiologia/"+dni_beneficiario,
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
function modalInterconsulta(){
  
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "../../../modal_historial_interconsulta/"+dni_beneficiario,
      type: "GET",
      success: function (result) {  
          $("#divInterconsultaH").html(result);
          $('#tablemodalm5').DataTable({
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
          generar_marca_agua_historial('marca_agua_overlay_hist_interconsulta');
      }
  });
}


async function guardarPrestacion(){

  var msg = "";
  var addExamenLab = $('#addExamenLab').attr("aria-pressed");
  const id_tipo_atencion = $('#id_tipo_atencion').val();

  if (addExamenLab == "true") {
    if (parseInt(id_tipo_atencion) === 3) {
      const tblExamenLab_cantidad_tr1 = $('#tblExamenLab tbody tr').length;
      if (tblExamenLab_cantidad_tr1 > 0) {
        const id_hora_toma_muestra = document.getElementById('id_hora_toma_muestra');
        const lab_orden_observacion = document.getElementById('lab_orden_observacion');
        const lab_orden_toma_muestra = document.getElementById("lab_orden_toma_muestra");

        if (!id_hora_toma_muestra.value) {
          const result = await Swal.fire({
            title: 'ALERTA',
            width: 400,
            confirmButtonText: 'GUARDAR',
            customClass: { confirmButton: 'btn btn-success' },
            buttonsStyling: false,
            html: `
                <div style="text-align: left">
                  <label for="">Debe seleccionar la hora de la toma de muestra</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="hora" value="1">05:00 AM
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="hora" value="2">09:00 AM
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="hora" value="3">16:00 PM
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="hora" value="4">22:00 PM
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="hora" id="radio_otro" value="0">OTRO
                    </label>
                  </div>
                  <textarea id="obs_otro" placeholder="Observación..." class="form-control" style="display:none; width:100%; margin-top:8px;" rows="2">${lab_orden_observacion.value}</textarea>
                </div>
            `,
            didOpen: () => {
              document.getElementById('radio_otro').addEventListener('change', function () {
                document.getElementById('obs_otro').style.display = this.checked ? 'block' : 'none';
              });
            },
            preConfirm: () => {
              const seleccionado = document.querySelector('input[name="hora"]:checked');
              if (!seleccionado) {
                  Swal.showValidationMessage('Seleccione una opción');
                  return false;
              }
              if (seleccionado.value === '0') {
                const obs = document.getElementById('obs_otro').value.trim();
                if (!obs) {
                  Swal.showValidationMessage('Ingrese una observación');
                  return false;
                }
                lab_orden_observacion.value = obs;
                return '0';
              }

              return seleccionado.value;
            }
          }); 
          
          if (!result.isConfirmed) return;
          
          id_hora_toma_muestra.value = result.value;

          const radioPrincipal = document.getElementById('hora_toma_muestra__' + result.value);
          if (radioPrincipal) {
            radioPrincipal.checked = true;
          }

          let descripcionHoraTomaMuestra = "";
          switch (result.value) {
            case "0": descripcionHoraTomaMuestra = "OTRO"; break;
            case "1": descripcionHoraTomaMuestra = "05:00 AM"; break;
            case "2": descripcionHoraTomaMuestra = "09:00 AM"; break;
            case "3": descripcionHoraTomaMuestra = "16:00 PM"; break;
            case "4": descripcionHoraTomaMuestra = "22:00 PM"; break;
          }

          lab_orden_toma_muestra.value = descripcionHoraTomaMuestra;
        } else if (id_hora_toma_muestra.value == "0" && lab_orden_observacion.value.trim() == "") {
          msg += "Al seleccionar la Hora de Toma de Muestra en <b>OTRO</b><br>debe ingresar el detalle en el campo de <b>Observación de la Orden de Laboratorio</b>.<br>";
        }
      }
    }
  }

  Swal.fire({
    title: '<h5><b>REGISTRANDO ...<b></h5>',
    // showLoaderOnConfirm: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
  
	$('#btnPrestacion').prop("disabled",true);
	
    // var msg = "";
    
	var fecha_atencion = $('#fecha_atencion').val();
	var id_diagnostico = $('input[name="id_diagnostico[]"]').map(function(){return $(this).val();}).get();
	// var id_procedimiento = $('input[name="id_procedimiento[]"]').map(function(){return $(this).val();}).get();
	// var procedimiento_cantidad = $('input[name="procedimiento_cantidad[]"]').map(function(){return $(this).val();}).get();
	var id_tipo_diagnostico = $('input[name="id_tipo_diagnostico[]"]').map(function(){return $(this).val();}).get();
	var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
	var cantidadReceta = $('#cantidadReceta').val();
	$('#procedimientosSeleccionados1').val(JSON.stringify(procedimientosSeleccionados));
	let vardatos = $('#procedimientosSeleccionados1').val();
	if(fecha_atencion=="")msg+="Debe ingresar una Fecha de Atencion<br>";

	var ind_diag = $('#tblDiagnostico tbody tr').length;
	if(ind_diag > 0){
		fila_dignosticos=$('#tblDiagnostico tbody').children('tr');
		for (i = 0; i < ind_diag; i++) {
			fila_dignostico=fila_dignosticos[i];
			var id_diagnostico = $(fila_dignostico).find('.id_diagnostico').val();
			var id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico').val();
			if(id_diagnostico == "")msg+="Debe ingresar el nombre de diagnostico<br>";
			if(id_tipo_diagnostico == "0")msg+="Debe ingresar el tipo de diagnostico<br>";
		}
	}else{
		msg+="Debe ingresar minimo un diagnostico<br>";
		msg+="Debe ingresar el tipo de diagnostico<br>";
	}

  // Validacion Procedimientos CPMS
  let tblProcedimiento_cantidad_tr = $('#tblProcedimiento tbody tr').length;
	if (tblProcedimiento_cantidad_tr > 0) {
    let procedimiento_tr = $('#tblProcedimiento tbody').children('tr');

		for (let i = 0; i < tblProcedimiento_cantidad_tr; i++) {
      let fila_procedimiento = procedimiento_tr[i];

      let procedimiento_id = $(fila_procedimiento).find('[name="id_procedimiento[]"]').val();
      // let procedimiento_codigo_cie = $(fila_procedimiento).find('.codigo_cie').val();
      let procedimiento_cantidad = $(fila_procedimiento).find('[name="procedimiento_cantidad[]"]').val();
      let procedimiento_diagnostico_id = $(fila_procedimiento).find('[name="miSelect[]"]').val();

      if (procedimiento_id == "") {
        msg += "Debe ingresar el <b>Procedimiento CPMS</b> y los datos que se solicitan, de lo contrario elimine el campo.<br>";
      } else if (procedimiento_id != "") {
        procedimiento_cantidad = String(procedimiento_cantidad);

        if (procedimiento_cantidad == "") {
          msg += "Debe ingresar la cantidad del <b>Procedimiento CPMS</b> ingresado.<br>";
        } else if (procedimiento_cantidad != "" && procedimiento_cantidad == "0") {
          msg += "Debe ingresar la cantidad del <b>Procedimiento CPMS</b> y debe ser <b>mayor a 0</b>.<br>";
        }

        if (procedimiento_diagnostico_id == "" || procedimiento_diagnostico_id == null) {
          msg += "Debe seleccionar el diagnóstico para el procedimiento.<br>";
        }
      }
      
		}
	} else {
    msg+="Debe ingresar mínimo un procedimiento.<br>";
  }

	// if(id_procedimiento=="")msg+="Debe ingresar minimo un procedimiento<br>";
	// if(procedimiento_cantidad=="")msg+="Debe ingresar una cantidad en un procedimiento<br>";
   	
	if(addRecetaVale == "true"){
		var ind = $('#example tbody tr').length;        
		if(ind > 0){
			var nind = ind-1;
			var cmbMedicamento = $("#cmbMedicamento"+nind).val();
			if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == ""){
				$('#example .deleteFila').eq(ind - 1).parent().parent().remove();
				ind = ind-1;
			}
			fila_medicamentos=$('#example tbody').children('tr');            
			for (i = 0; i < ind; i++) {
				fila_medicamento=fila_medicamentos[i];
				var tiempo_periodo = $(fila_medicamento).find('.tiempo_periodo').val();
				var posologia = $(fila_medicamento).find('.posologia').val();
				var valmedentre = $(fila_medicamento).find('.nro_med_solictados').val();
				if(tiempo_periodo == "")msg+="Uno de los productos no cuenta el tiempo(dias)<br>";
				if(posologia == "")msg+="Uno de los productos no cuenta con la posologia<br>";
				if(valmedentre == "")msg+="Uno de los productos no cuenta con la cantidad prescrita<br>";
			}
		}else{
			msg+="Debe ingresar minimo un producto medico a la Receta Vale<br>";
		}
	}
  var diagnosticosSeleccionados = $('#diagnosticosSeleccionados').val();

  // Laboratorio
  if (addExamenLab == "true") {
    const tblExamenLab_cantidad_tr = $('#tblExamenLab tbody tr').length;
    if (tblExamenLab_cantidad_tr > 0) {
      const ordenlab_tr = $('#tblExamenLab tbody').children('tr');

      let fila_ordenlab, id_examen_lab, cantidad_lab, id_diagnostico_lab, cpms_lab;
      
      for (let i = 0; i < tblExamenLab_cantidad_tr; i++) {
        fila_ordenlab = ordenlab_tr[i];

        id_examen_lab = $(fila_ordenlab).find('input[name="id_examen_lab[]"]').val();
        cantidad_lab = $(fila_ordenlab).find('input[name="examen_lab_cantidad[]"]').val();
        id_diagnostico_lab = $(fila_ordenlab).find('select[name="miSelectLab[]"]').val();
        cpms_lab = $(fila_ordenlab).find('input[name="cod_lab[]"]').val();

        if(id_examen_lab == "") {
          msg += "Debe registrar un examen de laboratorio<br>";
        } else {
          if (cantidad_lab == "") msg += `Debe registrar una cantidad para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
          if (cantidad_lab == 0) msg += `Debe registrar una cantidad mayor a 0 para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
          if (id_diagnostico_lab == "" || id_diagnostico_lab == null) msg += `Debe seleccionar el diagnóstico para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
        }
      }

    }
	} else {
    $('#tblExamenLab tbody').empty();
  }

  //PARA CENTRO QUIRURGICO
    var addCQX2 = $('#addRequiereIntervencionQuirurgica').attr("aria-pressed");
    if(addCQX2 == "true"){
      var id_tipo_cirugia = $('#id_tipo_cirugia').val();
      var id_prioridad_programacion = parseInt($('#id_prioridad_programacion').val()) || 0; //turnos quirurgicos
      if (id_prioridad_programacion <= 0) {
        msg += "Ingrese una <b>hora mayor a 0 </b> (Centro quirúrgico)<br>";
      }
      if(id_tipo_cirugia=='1'){
        let inicio = document.getElementById('hora_inicio_intervencion');
        let fin = document.getElementById('hora_fin_intervencion');

        if (!inicio.value || inicio.value === '00:00') {
            msg += "Debe ingresar <b>una hora válida de inicio de cirugía  (Centro quirurgico)</b><br>";
        }

        if (!fin.value || fin.value === '00:00') {
            msg += "Debe ingresar <b>una hora válida de término de cirugía  (Centro quirurgico)</b><br>";
        }

        if (inicio.value && fin.value && inicio.value !== '00:00' && fin.value !== '00:00') {
            if (fin.value <= inicio.value) {
                msg += "La hora de término <b>debe ser mayor que la hora de inicio (Centro quirurgico)</b><br>";
            }
        }
      }
      if(id_tipo_cirugia=='2' || id_tipo_cirugia=='3'){
        var horas = parseInt($('#horas').val()) || 0;
        if (horas <= 0) {
          msg += "Ingrese una <b>hora</b> mayor a 0 (Centro quirúrgico)<br>";
        }
      }
      var ind_diag = $('#tblDiagnosticoQuirurgico tbody tr').length;
      var ind_proced = $('#table_procedimiento_intervencion tbody tr').length;
      var ind_cirujano = $('#table_medico_cirujano tbody tr').length;

      // Validación de diagnósticos
      if (ind_diag > 0) {
        var errorEnDiagnosticos = false;
        $('#tblDiagnosticoQuirurgico tbody tr').each(function(){
          var id_diagnostico = $(this).find('.id_diagnostico_quirurgico').val();
          var id_tipo_diagnostico = $(this).find('.id_tipo_diagnostico_quirurgico').val();
          
          if (id_diagnostico === "") {
            msg += "Debe ingresar el <b>nombre de diagnóstico (Centro quirurgico)</b><br>";
            errorEnDiagnosticos = true;
          }
          if (id_tipo_diagnostico === "0") {
            msg += "Debe ingresar el <b>tipo de diagnóstico (Centro quirurgico)</b><br>";
            errorEnDiagnosticos = true;
          }
        });
        // Si no hay errores, limpiamos el mensaje de diagnóstico
        if (!errorEnDiagnosticos) {
          msg = msg.replace("Debe ingresar el <b>nombre de diagnóstico (Centro quirurgico)</b><br>", "");
          msg = msg.replace("Debe ingresar el <b>tipo de diagnóstico (Centro quirurgico)</b><br>", "");
        }
      }else{
        msg += "Debe ingresar mínimo <b>un diagnóstico (Centro quirurgico)</b><br>";
      }
      // Validación de procedimientos
      if (ind_proced > 0) {
        var errorEnProcedimientos = false;
        $('#table_procedimiento_intervencion tbody tr').each(function (index) {
          var id_procedimiento = $(this).find('.cmbProcedimientoIntervencion').val();
          if (id_procedimiento === "") {
            msg += "Debe ingresar el <b>nombre del procedimiento (Centro quirurgico)</b><br>";
            errorEnProcedimientos = true;
          }
          // Si no hay errores, limpiamos el mensaje de procedimientos
          if (!errorEnProcedimientos){
            msg = msg.replace("Debe ingresar el <b>nombre del procedimiento (Centro quirurgico)</b><br>", "");
          }        
        });
      }else{
          msg += "Debe ingresar mínimo <b>un procedimiento (Centro quirurgico)</b><br>";
      }
      // Validación de cirujanos
      if (ind_cirujano > 0) {
        var errorEnCirujanos = false;
        $('#table_medico_cirujano tbody tr').each(function (index) {
          var id_cirujano = $(this).find('.cmbMedicoCirujano ').val();
          var id_cargo_medico = $(this).find('.cargoMedicoCirujano').val();
          
          if (id_cirujano === "") {
            msg += "Debe ingresar el nombre del <b>médico cirujano (Centro quirurgico)</b><br>";
            errorEnCirujanos = true;
          }
          if (id_cargo_medico === "" || id_cargo_medico === "0") {
            msg += "Debe de seleccionar el <b>cargo del médico cirujano (Centro quirurgico)</b><br>";
            errorEnCirujanos = true;
          }
          // Si no hay errores, limpiamos el mensaje de procedimientos
          if (!errorEnCirujanos){
            msg = msg.replace("Debe ingresar el <b>nombre del médico cirujano (Centro quirurgico)</b><br>", "");
          }         
        });
      }else{
        msg += "Debe ingresar mínimo <b>un médico cirujano (Centro quirurgico)</b><br>";
      }
    }
    ////FIN PARA CENTRO QUIRURGICO
  
   	
    if(msg!=""){
        // bootbox.alert(msg);
        Swal.hideLoading();
        Swal.close();
        bootbox.alert({
          message: msg,
          size: 'small'
        });
		$('#btnPrestacion').prop("disabled",false);
		// $('.loader').hide();
        return false;
    }
    else{
		$("#frmPrestacion").submit();
    }
    $('#btnPrestacion').prop("disabled", false);
}
function fn_save_prestacion(){
    
    //var fecha_atencion_original = $('#fecha_atencion').val();
    $.ajax({
            url: "../../registrar_prestacion",
            type: "POST",
			//data : $("#frmPrestacion").serialize()+"&id_medico="+id_medico+"&fecha_cita="+fecha_cita,
			data : $("#frmPrestacion").serialize(),
            success: function (result) {  
					//modalTurnos();
					//modalHistorial();
            }
    });
}

function modalDelegar___(start,iIdMovimientoCompra,iIdTipoCompra){
	
	$(".modal-dialog").css("width","80%");
	$('#openOverlayOpc').modal('show');
	//$('#openOverlayOpc').modal();
	$('#openOverlayOpc .modal-body').css('height', 'auto');
	//alert(start);
	$.ajax({
			url: "../../modal_cita_medico/"+start,
			type: "GET",
			success: function (result) {  
					$("#diveditpregOpc").html(result);
			}
	});
}
function modalDelegar(start){
	
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();;
			
	$.ajax({
			url: "../../modal_cita_medico/"+id_ipress+"/"+id_servicio+"/"+start,
			type: "GET",
			success: function (result) {  
					$("#divHorario").html(result);
			}
	});
}

function updateSelectElements() {
  // Obtener todos los elementos <select> con la clase "select-diagnostico"
  let selectElements = document.querySelectorAll(".select-diagnostico");
  
  selectElements.forEach((selectElement) => {
    // Almacenar el valor actualmente seleccionado
    let currentValue = selectElement.value;

    // Eliminar las opciones actuales
    selectElement.innerHTML = "";


    // Agregar las opciones basadas en listIdDiagnosticoObjetos
    listIdDiagnosticoObjetos.forEach((item) => {
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
      // console.log()
    }
  });


}

function addNewArray(id_diagnostico) {
  if(  listIdDiagnosticoObjetos.findIndex( e  => e.id == id_diagnostico) ==-1 ){
  listIdDiagnostico.push(id_diagnostico);// solo es para validar si existen datos

  if (listIdDiagnostico.length > 0) {

    let codigo = JSON.stringify(id_diagnostico);
    // Realizar la petición AJAX usando el código id_diagnostico
    $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": "../../../obtenerdatosDiagnosticos",
                "data":{
                  codigo : codigo
                       },
                "success": function (result) {
                  listIdDiagnosticoObjetos.push(result.objeto);   
                      console.log("agregar nuevo diagnostico:"); console.table(listIdDiagnosticoObjetos);
                      updateSelectElements();
                },
                "error": function (msg, textStatus, errorThrown) {
                    //location.href="login";
                }
            });
  }
  }

 
}

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
	item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
	item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
	item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
	item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';
	newRow +='<tr>';
	newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="44" required="" id="nombre_diagnostico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
	newRow +='<input type="hidden" class="id_diagnostico" id="id_diagnostico'+ind+'" name="id_diagnostico[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="codigo_cie" id="codigo_cie_'+ind+'" value=""></td>';
	newRow +='<td><select tabindex="45" class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico'+ind+'" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">'+item_tipo_diagnostico+'</select></td>';
	newRow +='<td><button type="button" tabindex="46" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
	newRow +='</tr>';
	$('#tblDiagnostico tbody').append(newRow);
	var sexo	= $('#nomsexo').val();
	if(sexo=='M'){
		genero=1;
	}
	else{
		if(sexo=='F'){
			genero=2;
		}
	}
	
	$("#nombre_diagnostico"+ind).autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
			$.ajax({
				//url: '../obtener_diagnostico/' + request.term,
				url: '../../../obtener_diagnostico_genero/' + request.term + '/'+ genero,
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
			$("#nombre_diagnostico"+ind).prop("readonly",true);
      $("#codigo_cie_"+ind).val(ui.item.codigo_cie);
		}
	});
	
	$("#nombre_diagnostico"+ind).on('blur', function() {
		var id_diagnostico = $("#id_diagnostico"+ind).val();
		if(id_diagnostico == ""){
			bootbox.alert("Falta ingresar el diagnostico en esta fila");
			$("#nombre_diagnostico"+ind).val("");
			return false;
		}else{
      addNewArray(id_diagnostico);
    }
	});
	
}
function AddProcedimiento() {
  var newRow = "";
  var ind = $('#tblProcedimiento tbody tr').length;

  if (ind > 0) {
    $('.id_procedimiento').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });
					
    fila_medicamentos = $('#tblProcedimiento tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];
  }

  newRow += '<tr>';
  newRow +=
    '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" required="" id="nombre_procedimiento' +
    ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento' + ind +
    '" name="id_procedimiento[]" value="" data-id='+ind+'></td>';
  newRow +=
    '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad' +
    ind + '" name="procedimiento_cantidad[]" value="1"></td>';
    //lrojas
  newRow += '<td><select class="form-control select-diagnostico"  id="miSelect' + ind + '" name="miSelect[]"></select></td>';
  newRow +=
    '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';

  $('#tblProcedimiento tbody').append(newRow);
  const selectElement = document.getElementById('miSelect' + ind);
  let defaultOption = document.createElement('option');
 //si entra la primera vez
  var i = 0;
  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id;
    option.textContent = item.nombre;
    if (i==0){
      option.setAttribute("selected", "selected");  
      i++;
    }
    selectElement.appendChild(option);
  });

  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  if (sexo == 'M')
    genero = 1;
  else
    genero = 2;
	
  //tipo = 1
  $("#nombre_procedimiento" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '../../../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
        //url: '../obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaProcedimientoSelecionado = $(".id_procedimiento").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
          response(filtro);
        }
      });
    },
    select: function(e, ui) {
      var id_procedimiento = ui.item.id;
      $("#id_procedimiento" + ind).val(id_procedimiento);
      $("#nombre_procedimiento" + ind).prop("readonly", true);
    }
  });
	
  $("#nombre_procedimiento" + ind).on('blur', function() {
    var id_procedimiento = $("#id_procedimiento" + ind).val();
    if (id_procedimiento == "") {
      bootbox.alert("Falta ingresar el procedimiento en esta fila");
      $("#nombre_procedimiento" + ind).val("");
      return false;
    }
  });
  $("#procedimiento_cantidad" + ind).on('blur', function() {
    var id_procedimiento = $("#id_procedimiento" + ind).val();
    if (id_procedimiento == "") {
      bootbox.alert("Falta ingresar la cantidad en este procedimiento");
      $("#procedimiento_cantidad" + ind).val("");
      return false;
    }
  });
	
}
function esNumero(event, id) {
  var charCode = (event.which) ? event.which : event.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
}

//luis rojas
function actualizararray(selectedId,selectedcodigo,ind) {
  if (procedimientosSeleccionados.length < 1) {
  let obj = {
    posicion: ind,
    id: selectedId,
    codigo:selectedcodigo
  }
  procedimientosSeleccionados.push(obj);
} else {
  let newArray = procedimientosSeleccionados.map((elemento) => {
    if (elemento.posicion === ind) {
      elemento.id = selectedId; // si el objeto existe, actualiza el id
      elemento.codigo =selectedcodigo;
    }
    return elemento;
  });
  if (!newArray.some((elemento) => elemento.posicion === ind)) {
    let obj = {
      posicion: ind,
      id: selectedId,
      codigo:selectedcodigo
    }
    newArray.push(obj); // si no existe, agrega un nuevo objeto
  }
  procedimientosSeleccionados = newArray; // actualiza el array original
}
console.log(procedimientosSeleccionados);
 
}
function abrirModal(ind) {
  $('#modal-content').empty();
  let arraydeprocedimientos =listIdDiagnosticoObjetos;
console.log(arraydeprocedimientos);

// Agregando los objetos del array a la tabla del modal
 let tableRows = arraydeprocedimientos.map(function (item) {
return '<tr class="table-row"><td>' + item.id + '</td><td>' + item.codigo + '</td><td>' + item.nombre + '</td></tr>';
}).join('');
$('#modalArrayList .modal-body #procedimientosTable tbody').html(tableRows);

// Agregar controlador de eventos al hacer clic en las filas de la tabla
$('#modalArrayList .modal-body #procedimientosTable tbody tr').on('click', function() {
  let selectedId = $(this).find('td:first').text();//aqui
  let selectedcodigo = $(this).find('td:nth-child(2)').text();
  let selectedName = $(this).find('td:nth-child(3)').text();
  console.log("posicion" + ind + "el id es" +selectedId );
  actualizararray(selectedId,selectedcodigo,ind);
  $('#modalArrayList').modal('hide');
});

$('#modalArrayList').modal('show');

}

function AddProducto(productoParam = null) {
  var id_farmacia = $('#id_farmacia_receta').val();
  var newRow = "";
  var ind = filasmedicamentos;
  if (ind > 0) {
    $('.cmbMedicamento').each(function() {
      var ind_tmp = $(this).attr("ind");
      if (ind_tmp => ind) {
        //ind = Number(ind_tmp) + 1;
        //tabindex += 3;
      }
    });

    fila_medicamentos = $('#example tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];
    var valante = $(fila_medicamento).find('#nro_med_entregados').val();
    if (valante == "") {
      bootbox.alert("Ingrese cantidad entregada en el producto anterior");
      return false;
    }
  }

  var item_producto = "";
  $('#cmbMedicamentoTemp option').each(function() {
    item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() + "</option>"
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
  newRow +='<td><select class="form-control cmbMedicamento" id="cmbMedicamento' + ind + '" ind="' + ind +'" name="id_producto[]" style="width: 450px">' + item_producto + '</select></td>';
  newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" maxlength="3" required="" id="tiempo_periodo' + ind +'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" />';
	newRow +='<td><select class="form-control id_via_administracion" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';
  newRow +='<td><input type="text" name="posologia[]" maxlength="50" required="" id="posologia' + ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';

  newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
  newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
  newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
  newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
  newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
  newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados' +ind +'" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';
	
  newRow +='<td><select class="form-control select-diagnostico"  id="miSelectProd' + ind + '" name="miSelectProd[]"></select></td>';  
  newRow +='<td><button type="button" class="btn btn-primary btn-xs" id="add_producto'+ind+'"><i class="fa fa-plus"></i> </button></td>';
  newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
  newRow +='</tr>';

  $('#example tbody').append(newRow);
  $("#cmbMedicamento" + ind).select2({max_selected_options: 4}).focus();


  const selectElementProd = document.getElementById('miSelectProd' + ind);

  $('.tiempo_periodo').on('input', function () {
    const valor = parseInt($(this).val(), 10);

    if (!isNaN(valor) && valor > 180) {
      Swal.fire({
        icon: 'warning',
        title: '¡Atención!',
        text: 'El tiempo de periodo excede los 180 días.',
        confirmButtonText: 'Cerrar'
      });

    }
  });console.log("lista cie actual:");console.table(listIdDiagnosticoObjetos);

  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id;
    option.textContent = item.nombre;
    selectElementProd.appendChild(option);
  });
  $("#abrir-modal-" + ind).click(function(e) {
    e.stopPropagation();
  
    abrirModal(ind);
  });
	
  $("#nro_med_solictados" + ind).keypress(function(e) {
    if (e.which == 13) {
      AddProducto();
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
    }
  });

  $("#add_producto"+ind).click(function(e){
      AddProducto();
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      btnAgregarProducto = $(this);
      $(btnAgregarProducto).attr('disabled', 'disabled');
	});

  function validproductoseleccionado(id_farmacia, btn) {//Jose
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_dias = 30;
    var objTr = $(btn).closest("tr");
    var id_producto = objTr.find(".cmbMedicamento").val();
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
	

  $("#cmbMedicamento" + ind).on("change", function(e) {
    var flagx = 0;
    cmb = $(this);
    id_producto = $("#cmbMedicamento" + ind).val();
    id_user = {{ Auth::user()-> id }};

    /*if (productoParam.id_producto) {
      id_producto = productoParam.id_producto;
    }*/

    $('.cmbMedicamento').each(function() {
      var ind_tmp = $(this).val();
      if ($(this).val() == id_producto) flagx++;
    });

    if (flagx > 1) {
      bootbox.alert("El producto farmaceutico ya ha sido ingresado");
      $("#cmbMedicamento" + ind).val("").trigger("change");
      return false;
    }
    id_farmacia_receta = $('#id_farmacia_receta').val();

    option = {
      url: '{{ url("/") }}/cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
      type: 'GET',
      dataType: 'json',
      data: {}
    };

    $.ajax(option).done(function(data) {

      var cantidad = data.cantidad;
      var cantidadEstablecimiento = data.cantidadEstablecimiento;
      var cantidadAlmacen = data.cantidadAlmacen;
      $(cmb).closest("tr").find(".limpia_text").val("");
      //$(cmb).closest("tr").find("#nro_stocks").val(data);
      $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
      // $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
      // $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
      <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):
        ?>
      $(cmb).closest("tr").find(".tiempo_periodo").focus();
      <?php //endif;
        ?>
      $(cmb).closest("tr").find("#nro_med_solictados").val("");
      $(cmb).closest("tr").find("#nro_med_entregados").val("");
      $(cmb).closest("tr").find("#lotes_lote").val("");
      $(cmb).closest("tr").find("#lotes_cantidad").val("");
      $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
      $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");

      if (productoParam) {
        $(`#nro_med_solictados${ind}`).val(productoParam.cantidad_prescrita);
      }
    });

  });
  filasmedicamentos++;

  if (productoParam){
    $(`#cmbMedicamento${ind}`).val(productoParam.id_producto).trigger('change');
    $(`#tiempo_periodo${ind}`).val(productoParam.tiempo_periodo); 
    $(`#cmbTipoViaAdministracion${ind}`).val(productoParam.id_via_administracion);
    $(`#posologia${ind}`).val(productoParam.posologia);
  }
}
function soloNumerosMenosCero(e){
	var key = window.Event ? e.which : e.keyCode;
	//alert(key);
	return ((key >= 48 && key <= 57) || key == 0 || key == 8)       
}
function nro_interconsulta(){
	var dni_beneficiario = $('#dni_beneficiario').val();
	var id_farmacia = $('#id_farmacia').val();
	var x = 0;
	if(dni_beneficiario!=""){
        $.ajax({
                url: '/obtenerCantInterconsultas/' + dni_beneficiario+ '/' + id_farmacia,
                success: function (result) {
					if(result != null && result != ""){
		                for(var key in result) {
		                  x++;
		                }
					} 
                }
               
        });
        
        return x;
    }
}
function getModalidad(){
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  
  if (id_tipo_atencion == 1) {
    $("#divModalidad").show();
  } else {
    $("#divSubCons").hide();
    $("#divModalidad").hide();
  }
}
function getConsultorio(){
	
	var id_tipo_atencion 		= $('#id_tipo_atencion').val();
	var nivel     = $('#id_nivel').val();
	var valor;
  var dni_beneficiario = $("#dni_beneficiario").val();

	if(nivel==3){
		switch(id_tipo_atencion){
			case '1': valor=3;  /*$("#divSubCons").show();*/break; //ambulatorio
			case '2': valor=19; break; //emergencia
			case '3': valor=20; break; //ho
		}
		//$("#id_farmacia_receta option[value='"+ valor +"']").attr("selected",false);
		//$("#id_farmacia_receta option[value='"+ valor +"']").attr("selected",true);
		$("#id_farmacia_receta").val(valor);
		$("#id_farmacia_receta").select2();
	}
	else{
		valor={{Auth::user()->id_farmacia}};
		//$("#id_farmacia_receta option[value='"+ valor +"']").attr("selected",false);
		//$("#id_farmacia_receta option[value='"+ valor +"']").attr("selected",true);
		$("#id_farmacia_receta").val(valor);
		$("#id_farmacia_receta").select2();
		
	}

  $.ajax({
    url: '../../obtener_antecedentes_by_id_beneficiario/'+ dni_beneficiario,
    dataType: "json",
    success: function(resultado){
      $("#antecedente").val(resultado.antecedente);
      $("#antecedente_familiar").val(resultado.antecedente_familiar);
    }
  });
	
	//getProducto();
	/*
	if(id_tipo_atencion == 1){
		$("#divSubCons").show();
	}
	else{
		$("#divSubCons").hide();
	}
	*/
}
function llamarTv() {
  var id_cita = document.getElementById("id_cita").value;
  var cant_llamado_tv = document.getElementById("cant_llamado_tv").value;
  $.ajax({
    url: "/tv_llamar/" + id_cita,
    async: false,
    type: "GET",
    // dataType: "JSON",
    success: function(result) {
      
      if (result > 0) {
        // cant_llamado_tv += result + 1;
        document.getElementById("cant_llamado_tv").textContent = result;
        // document.getElementById("cant_llamado_tv").value = result;
        // console.log(result + " cant:" + cant_llamado_tv);
      } else {
        bootbox.alert("Actualmente se está llamando a otro paciente de este consultorio.");
      }
    },
    error: function(xhr, status) {
      console.log("error");
    },
    complete : function(xhr, status) {
      return false;
    }
  });
}
function posponerLlamadoTv() {
  var id_cita = document.getElementById("id_cita").value;
  $.ajax({
    url: "/tv_posponer_llamado/" + id_cita,
    async: false,
    type: "GET",
    success: function(result) {
      if (result=="2") {
        document.getElementById("cant_llamado_tv").textContent = "0";
        // console.log = result;
      } else {
        console.log = "error";
      }
    },
    complete : function(xhr, status) {
      return false;
    }
  });
}

function getProductoAndAddProducto() {
  getProducto();
  AddProducto();
}

// getConsultorio();

$(document).ready(function () {
  function buscarSitedsPorId() {
    const interconsulta = {!! json_encode($interconsulta) !!};
    const codigo_siteds = interconsulta.cod_siteds;
    if (!codigo_siteds) {
      $("#codigo_siteds_container").show();
      generarCodigoSiteds(interconsulta.id, interconsulta.asegurado_id, interconsulta.id_establecimiento, interconsulta.id_servicio, interconsulta.id_tipo_atencion);
    } else {
      $("#codigo_siteds").val(codigo_siteds);
    }
  }

  // TAGSITEDS
  function generarCodigoSiteds(id, aseguradoId, establecimientoId, servicioId, tipoAtencionId) {
    Swal.fire ({
        title:  "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>GENERANDO CÓDIGO SITEDS</span></div>",
        html: "<div style='margin: 0px 0px 20px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento, por favor...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        willOpen: () => {
            Swal.showLoading()
        },
    })
    const body = {
        "idCorrelativo": id,
        "aseguradoId": aseguradoId,
        "establecimientoId": establecimientoId,
        "tipoAtencionId": tipoAtencionId,
        "servicioId": servicioId
    };
    $.ajax({
        url:'/v2/ws-dirsapol-siteds',
        type:'POST',
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(body),
        success: (result, textStatus, jqXHR) => {
          $("#codigo_siteds").val(result.nuAutorizacion);
          Swal.fire({
            icon: (parseInt(jqXHR.status) === 201 || parseInt(jqXHR.status) === 200) ? "success" : 'warning',
              title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>INTERCONSULTA</span></div>",
              html: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>${result.message || "Código SITEDS actualizado correctamente"}</span></div>`,
              confirmButtonText: 'ENTENDIDO',
          });
        },
        error: function(xhr, status, error){
            const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
            Swal.fire({
                icon: 'error',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
        }, 
        complete: function(xhr, status) {
            Swal.hideLoading();
        }
    })
  }

  buscarSitedsPorId();
});

  function obtenerIndiceTblExamenLab() {
    let indice = $('#tblExamenLab tbody tr').length;
    
    $('.id_laboratorio').each(function() {
      let ind_tmp = $(this).data("id");
      if (ind_tmp >= indice) {
        indice = Number(ind_tmp) + 1;
      }
    });

    return indice;
  }

  function AddExamenLab() {
    var newRow = "";
    var ind = $('#tblExamenLab tbody tr').length;
    const id_nivel = $("#id_nivel").val();
    
    if (ind > 0) {
      //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA INICIO =================
      /*console.log('ENTRO EN CONDICIÓN');
      console.log('INDICE : ' + ind);
      let flag_descripcion_tipo_muestra = $('#flag_descripcion_tipo_muestra' + (ind - 1)).val();
      if (flag_descripcion_tipo_muestra == 1) {
        let descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + (ind - 1)).val();
        if (descripcion_tipo_muestra == "") {
          Swal.fire({
              icon: 'error',
              title: 'Debe registrar el tipo de muestra para microbiología. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Tipo de muestra</button>',
              showConfirmButton: false,
              timer: 3000
          });
          return;
        }
      }*/
      //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA FIN =================
      //=== VALIDA SOLICITUD EXAMEN ESPECIALIZADO HEMOCULTIVO INICIO ============================
      /*let descripcion_examen = $('#descripcion_examen' + (ind - 1)).val();
      if (descripcion_examen == 'HEMOCULTIVO NEONATO Y PEDRIATRIA') {
        let flag_solicitud_examen_especializados_hemocultivo_pedriatria = $('#flag_solicitud_examen_especializados_hemocultivo_pedriatria' + (ind - 1)).val();
        if (flag_solicitud_examen_especializados_hemocultivo_pedriatria == 1) {
          let des_pedriatria_respuesta_ocular = $('#des_pedriatria_respuesta_ocular' + (ind - 1)).val();
          if (des_pedriatria_respuesta_ocular == "") {
            Swal.fire({
                icon: 'error',
                title: 'Debe registrar la solicitud del examen especializado Hemocultivo de Neonato y Pedriatría Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs><i class="fa fa-check-square"></i>Solicitud</button>',
                showConfirmButton: false,
                timer: 3000
            });
            return;
          }
        }
      }
      if (descripcion_examen == 'HEMOCULTIVO ADULTO') {
        let flag_solicitud_examen_especializados_hemocultivo_adulto = $('#flag_solicitud_examen_especializados_hemocultivo_adulto' + (ind - 1)).val();
        if (flag_solicitud_examen_especializados_hemocultivo_adulto == 1) {
          let des_adulto_respuesta_ocular = $('#des_adulto_respuesta_ocular' + (ind - 1)).val();
          if (des_adulto_respuesta_ocular == "") {
            Swal.fire({
                icon: 'error',
                title: 'Debe registrar la solicitud del examen especializado Hemocultivo adulto Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs><i class="fa fa-check-square"></i>Solicitud</button>',
                showConfirmButton: false,
                timer: 3000
            });
            return;
          }
        }
      }*/
      //=== VALIDA SOLICITUD EXAMEN ESPECIALIZADO HEMOCULTIVO FIN ===============================
      //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================
      /*if (descripcion_examen == 'CITOQUIMICO LIQUIDOS BIOLOGICOS') {
        let flag_solicitud_citoquimicos_liquidos_biologicos = $('#flag_solicitud_citoquimicos_liquidos_biologicos' + (ind - 1)).val();
        if (flag_solicitud_citoquimicos_liquidos_biologicos == 1) {
          let des_num_frasco_muestra_opcion = $('#des_num_frasco_muestra_opcion' + (ind - 1)).val();
          if (des_num_frasco_muestra_opcion == "") {
            Swal.fire({
                icon: 'error',
                title: 'Debe registrar la solicitud para estudio citoquímico líquido Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs><i class="fa fa-check-square"></i>Solicitud</button>',
                showConfirmButton: false,
                timer: 3000
            });
            return;
          }
        }
      }
      $('.id_laboratorio').each(function() {
        var ind_tmp = $(this).data("id");
        if (ind_tmp => ind) {
          ind = Number(ind_tmp) + 1;
        }
      });
      fila_medicamentos = $('#tblExamenLab tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento = fila_medicamentos[idval];*/
      ind = obtenerIndiceTblExamenLab();
    }

    newRow +='<tr>';
    newRow +='<td>';
      newRow +='<input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="width:100%; text-align:left" />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id='+ind+'>';
      newRow +='<input type="hidden" class="form-control typeahead tt-query cod_lab" id="cod_lab' + ind +'" name="cod_lab[]" value="" data-id='+ind+'>';
      newRow +='<input type="hidden" class="form-control typeahead tt-query id_categoria" id="id_categoria_examen_lab' + ind +'" name="id_categoria_examen_lab[]" value="" data-id='+ind+'>';
    newRow +='</td>';
    if (id_nivel && parseInt(id_nivel) < 3) {
      newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
    }
    newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad typeahead tt-query" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1"></td>';
    newRow +='<td><select class="form-control select-diagnostico"  id="miSelectLab' + ind + '" name="miSelectLab[]"></select></td>';
    // ====================== Tipo de Muestra Inicio ===============================================
    newRow +='<td>';
      newRow +='<button type="button" class="btn btn-success btn-xs tipoMuestraMicrobiologia" id="tipoMuestraMicrobiologia' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Tipo de muestra</button>';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="descripcion_tipo_muestra' + ind +'" name="descripcion_tipo_muestra[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_descripcion_tipo_muestra' + ind +'" name="flag_descripcion_tipo_muestra[]" value="0" />';
      newRow +='<button type="button" class="btn btn-warning btn-xs editarTipoMuestraMicrobiologia" id="editarTipoMuestraMicrobiologia' + ind +'" data-id='+ind+' style="display:none; margin-left:4px"><i class="fa fa-edit">&nbsp;Tipo de muestra</button>';
    newRow +='</td>';
      // ====================== Tipo de Muestra Fin ===============================================
    // ====================== Solicitud de Examenes Especiales HEMOCULTIVO ADULTO Inicio ===============================================
    newRow +='<td>';
      newRow +='<button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoAdulto" id="solicitudExamenEspecializadoHemocultivoAdulto' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="descripcion_examen' + ind +'" name="descripcion_examen[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_fecha_tiempo_enfermedad' + ind +'" name="des_adulto_fecha_tiempo_enfermedad[]" value="" data-id='+ind+' />';
      /** ESCALA DE GLASGOW **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_respuesta_ocular' + ind +'" name="des_adulto_respuesta_ocular[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_respuesta_verbal' + ind +'" name="des_adulto_respuesta_verbal[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_respuesta_motora' + ind +'" name="des_adulto_respuesta_motora[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_total_escala_glasgow' + ind +'" name="des_adulto_total_escala_glasgow[]" value="" data-id='+ind+' />';
      /** SIGNOS VITALES **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_presion_arterial_x' + ind +'" name="des_adulto_presion_arterial_x[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_presion_arterial_y' + ind +'" name="des_adulto_presion_arterial_y[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frecuencia_cardiaca' + ind +'" name="des_adulto_frecuencia_cardiaca[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frecuencia_respiratorio' + ind +'" name="des_adulto_frecuencia_respiratorio[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_temperatura' + ind +'" name="des_adulto_temperatura[]" value="" data-id='+ind+' />';
      /** EXÁMENES DE LABORATORIO **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_creatinina' + ind +'" name="des_adulto_creatinina[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_diuresis' + ind +'" name="des_adulto_diuresis[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_procalcitonina' + ind +'" name="des_adulto_procalcitonina[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_pcr' + ind +'" name="des_adulto_pcr[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_fiO2' + ind +'" name="des_adulto_fiO2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_leucocitos' + ind +'" name="des_adulto_leucocitos[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_plaquetas' + ind +'" name="des_adulto_plaquetas[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_bilirrubina' + ind +'" name="des_adulto_bilirrubina[]" value="" data-id='+ind+' />';
      /** ANTECEDENTES PATOLÓGICOS **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_ima' + ind +'" name="des_adulto_ima[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_icc' + ind +'" name="des_adulto_icc[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_evp' + ind +'" name="des_adulto_evp[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_ecv' + ind +'" name="des_adulto_ecv[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_demencia' + ind +'" name="des_adulto_demencia[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_dm' + ind +'" name="des_adulto_dm[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_diabetes_lesion_organica' + ind +'" name="des_adulto_diabetes_lesion_organica[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_hemiplejia' + ind +'" name="des_adulto_hemiplejia[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_epoc' + ind +'" name="des_adulto_epoc[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_patologia_tejido_conectivo' + ind +'" name="des_adulto_patologia_tejido_conectivo[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_enfermedad_ulcerosa' + ind +'" name="des_adulto_enfermedad_ulcerosa[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_patologia_renal_moderada_o_grave' + ind +'" name="des_adulto_patologia_renal_moderada_o_grave[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_neoplasias' + ind +'" name="des_adulto_neoplasias[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_neoplasias_especifique' + ind +'" name="des_adulto_neoplasias_especifique[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_leucemias' + ind +'" name="des_adulto_leucemias[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_leucemias_especifique' + ind +'" name="des_adulto_leucemias_especifique[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_linfomas_malignos' + ind +'" name="des_adulto_linfomas_malignos[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_metastasis_colida' + ind +'" name="des_adulto_metastasis_colida[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_sida' + ind +'" name="des_adulto_sida[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_1' + ind +'" name="des_adulto_antecedentes_patologicos_otros_1[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_1_especifique' + ind +'" name="des_adulto_antecedentes_patologicos_otros_1_especifique[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_2' + ind +'" name="des_adulto_antecedentes_patologicos_otros_2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_2_especifique' + ind +'" name="des_adulto_antecedentes_patologicos_otros_2_especifique[]" value="" data-id='+ind+' />';
      /** TERAPIA ANTIMICROBIANA **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_terapia_antimicrobiana' + ind +'" name="des_adulto_terapia_antimicrobiana[]" value="" data-id='+ind+' />';
      /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_1' + ind +'" name="des_adulto_frasco_1[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_1_opcion_1' + ind +'" name="des_adulto_frasco_1_opcion_1[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_1_opcion_2' + ind +'" name="des_adulto_frasco_1_opcion_2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_2' + ind +'" name="des_adulto_frasco_2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_2_opcion_1' + ind +'" name="des_adulto_frasco_2_opcion_1[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_2_opcion_2' + ind +'" name="des_adulto_frasco_2_opcion_2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_3' + ind +'" name="des_adulto_frasco_3[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_3_opcion_1' + ind +'" name="des_adulto_frasco_3_opcion_1[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_3_opcion_2' + ind +'" name="des_adulto_frasco_3_opcion_2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_hemocultivo_seguimiento_y_vigilancia' + ind +'" name="des_adulto_hemocultivo_seguimiento_y_vigilancia[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_fecha_primer_resultado_hemocultivo' + ind +'" name="des_adulto_fecha_primer_resultado_hemocultivo[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_solicitud_examen_especializados_hemocultivo_adulto' + ind +'"  name="flag_solicitud_examen_especializados_hemocultivo_adulto[]"  data-id='+ind+' value="0" />';
      newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoAdulto" id="editarSolicitudExamenEspecializadoHemocultivoAdulto' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';
      // ====================== Solicitud de Examenes Especiales HEMOCULTIVO ADULTO Fin ===============================================
      
      // ====================== Solicitud de Examenes Especiales HEMOCULTIVO NEONATO Y PEDRIATRÍA INICIO ===============================================
      newRow +='<button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoPedriatria" id="solicitudExamenEspecializadoHemocultivoPedriatria' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_fecha_tiempo_enfermedad' + ind +'" name="des_pedriatria_fecha_tiempo_enfermedad[]" value="" data-id='+ind+' />';
      /** EDAD GESTIONAL **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_postermino' + ind +'" name="des_pedriatria_postermino[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_termino' + ind +'" name="des_pedriatria_termino[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_prematuro_leve' + ind +'" name="des_pedriatria_prematuro_leve[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_prematuro_moderado' + ind +'" name="des_pedriatria_prematuro_moderado[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_prematuro_extremo' + ind +'" name="des_pedriatria_prematuro_extremo[]" value="" data-id='+ind+' />';
      /** APGAR **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_apariencia' + ind +'" name="des_pedriatria_apariencia[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_pulso' + ind +'" name="des_pedriatria_pulso[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_gesto' + ind +'" name="des_pedriatria_gesto[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_actividad_respiratoria' + ind +'" name="des_pedriatria_actividad_respiratoria[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_tono_muscular' + ind +'" name="des_pedriatria_tono_muscular[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_total_apgar' + ind +'" name="des_pedriatria_total_apgar[]" value="" data-id='+ind+' />';
      /** ESCALA DE GLASGOW **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_respuesta_ocular' + ind +'" name="des_pedriatria_respuesta_ocular[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_respuesta_verbal' + ind +'" name="des_pedriatria_respuesta_verbal[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_respuesta_motora' + ind +'" name="des_pedriatria_respuesta_motora[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_total_escala_glasgow' + ind +'" name="des_pedriatria_total_escala_glasgow[]" value="" data-id='+ind+' />';
      /** SIGNOS VITALES **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_presion_arterial_x' + ind +'" name="des_pedriatria_presion_arterial_x[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_presion_arterial_y' + ind +'" name="des_pedriatria_presion_arterial_y[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frecuencia_cardiaca' + ind +'" name="des_pedriatria_frecuencia_cardiaca[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frecuencia_respiratorio' + ind +'" name="des_pedriatria_frecuencia_respiratorio[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_temperatura' + ind +'" name="des_pedriatria_temperatura[]" value="" data-id='+ind+' />';
      /** EXÁMENES DE LABORATORIO **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_creatinina' + ind +'" name="des_pedriatria_creatinina[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_diuresis' + ind +'" name="des_pedriatria_diuresis[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_procalcitonina' + ind +'" name="des_pedriatria_procalcitonina[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_pcr' + ind +'" name="des_pedriatria_pcr[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_fiO2' + ind +'" name="des_pedriatria_fiO2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_leucocitos' + ind +'" name="des_pedriatria_leucocitos[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_plaquetas' + ind +'" name="des_pedriatria_plaquetas[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_bilirrubina' + ind +'" name="des_pedriatria_bilirrubina[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_glucosa' + ind +'" name="des_pedriatria_glucosa[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_alt' + ind +'" name="des_pedriatria_alt[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_vcg' + ind +'" name="des_pedriatria_vcg[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_tp' + ind +'" name="des_pedriatria_tp[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_sodio' + ind +'" name="des_pedriatria_sodio[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_potasio' + ind +'" name="des_pedriatria_potasio[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_calcio' + ind +'" name="des_pedriatria_calcio[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_pco2' + ind +'" name="des_pedriatria_pco2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_tpta' + ind +'" name="des_pedriatria_tpta[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_examen_laboratorio_otros' + ind +'" name="des_pedriatria_examen_laboratorio_otros[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_examen_laboratorio_otros_especifique' + ind +'" name="des_pedriatria_examen_laboratorio_otros_especifique[]" value="" data-id='+ind+' />';    
      /** TERAPIA ANTIMICROBIANA **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_terapia_antimicrobiana' + ind +'" name="des_pedriatria_terapia_antimicrobiana[]" value="" data-id='+ind+' />';
      /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_1' + ind +'" name="des_pedriatria_frasco_1[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_1_opcion_1' + ind +'" name="des_pedriatria_frasco_1_opcion_1[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_1_opcion_2' + ind +'" name="des_pedriatria_frasco_1_opcion_2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_2' + ind +'" name="des_pedriatria_frasco_2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_2_opcion_1' + ind +'" name="des_pedriatria_frasco_2_opcion_1[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_2_opcion_2' + ind +'" name="des_pedriatria_frasco_2_opcion_2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_3' + ind +'" name="des_pedriatria_frasco_3[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_3_opcion_1' + ind +'" name="des_pedriatria_frasco_3_opcion_1[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_3_opcion_2' + ind +'" name="des_pedriatria_frasco_3_opcion_2[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_hemocultivo_seguimiento_y_vigilancia' + ind +'" name="des_pedriatria_hemocultivo_seguimiento_y_vigilancia[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_fecha_primer_resultado_hemocultivo' + ind +'" name="des_pedriatria_fecha_primer_resultado_hemocultivo[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_solicitud_examen_especializados_hemocultivo_pedriatria' + ind +'"  name="flag_solicitud_examen_especializados_hemocultivo_pedriatria[]"  data-id='+ind+' value="0" />';
      newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoPedriatria" id="editarSolicitudExamenEspecializadoHemocultivoPedriatria' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';
      // ====================== Solicitud de Examenes Especiales HEMOCULTIVO NEONATO Y PEDRIATRÍA FIN ===============================================
      // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================================
      newRow +='<button type="button" class="btn btn-success btn-xs solicitudCitoquimicosLiquidosBiologicos" id="solicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_antecedentes_patologicos_personales' + ind +'" name="des_antecedentes_patologicos_personales[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_antecedentes_patologicos_familiares' + ind +'" name="des_antecedentes_patologicos_familiares[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_cefaloraquideo' + ind +'" name="des_liquido_cefaloraquideo[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_pleural' + ind +'" name="des_liquido_pleural[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_ascitico' + ind +'" name="des_liquido_ascitico[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_sinovial' + ind +'" name="des_liquido_sinovial[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_otro' + ind +'" name="des_liquido_otro[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_otro_especifique' + ind +'" name="des_liquido_otro_especifique[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_num_frasco_muestra_opcion' + ind +'" name="des_num_frasco_muestra_opcion[]" value="" data-id='+ind+' />';
      newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_solicitud_citoquimicos_liquidos_biologicos' + ind +'"  name="flag_solicitud_citoquimicos_liquidos_biologicos[]"  data-id='+ind+' value="0" />';
      newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudCitoquimicosLiquidosBiologicos" id="editarSolicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';
      
      // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS FIN ===============================================
    newRow +='</td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left: 4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
    $('#tblExamenLab tbody').append(newRow);
    // aqui abre el input para agregar el button agregar
    /*$("#examen_lab_cantidad" + ind).on('input', function() {
      var valor = $(this).val();
      if (valor === '0') {
        $(this).val(''); // Si ingresan 0, eliminarlo
      }
    });*/
    const selectElementLab = document.getElementById('miSelectLab' + ind);
    
    listIdDiagnosticoObjetos.forEach((item) => {
      let option = document.createElement('option');
      option.value = item.id;
      option.textContent = item.nombre;
      selectElementLab.appendChild(option);
    });

    var nivel = $('#id_nivel').val();
    var sexo = $('#nomsexo').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();
    console.log("id_tipo_atencion:" + id_tipo_atencion);
    let genero = (sexo == 'M' ? 1 : 2);
    
    let url_lab_route = "";
    switch (parseInt(id_tipo_atencion)) {
      case 2: url_lab_route = "obtener_laboratorio_convenio_emergencia"; break; // Emergencia
      case 3: url_lab_route = "obtener_laboratorio_convenio"; break; // Hospitalizacion
    }

    $("#nombre_examen_lab" + ind).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          url: '{{ url("/") }}/' + url_lab_route + '/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion,
          //url: '../obtener_procedimiento/' + request.term,
          dataType: "json",
          success: function(data) {
            let listaLaboratorioSelecionado = $(".id_laboratorio").map(function(){return parseInt($(this).val());}).get();
            let filtro = data.filter((element)=>!listaLaboratorioSelecionado.includes(element.id));
            response(filtro);
          }
        })
    
      },
      select: function(e, ui) {
        asignarValoresSelect(ui,ind);
      }
    });
    
    $("#nombre_examen_lab" + ind).on('blur', function() {
      var id_examen_lab = $("#id_examen_lab" + ind).val();
      let input_nombre_examen = $("#nombre_examen_lab" + ind).val();

      if (input_nombre_examen.trim() != "" && id_examen_lab == "") {
        bootbox.alert("Falta ingresar el examen laboratorio en esta fila");
        // $("#examen_lab_cantidad" + ind).val("");
        return false;
      }
    });
    /*$("#examen_lab_cantidad" + ind).on('blur', function() {
      var id_examen_lab = $("#id_examen_lab" + ind).val();
      if (id_examen_lab == "") {
        bootbox.alert("Falta ingresar la cantidad en este examen laboratorio");
        //$("#examen_lab_cantidad" + ind).val("");
        return false;
      }
    });*/
  }

  function asignarValoresSelect(ui,ind){
    var id_examen_lab = ui.item.id;
    let codigo_procedimiento = ui.item.codigo_procedimiento;
    let id_categoria_examen_lab = ui.item.id_categoria;
    $("#cod_lab" + ind).val(ui.item.codigo_procedimiento);
    $("#cod_examen" + ind).val(codigo_procedimiento);
    $("#id_categoria_examen_lab" + ind).val(id_categoria_examen_lab);
    $("#nombre_examen_lab" + ind).val(ui.item.value);
    $("#nombre_examen_lab" + ind).prop("readonly", true);
    $("#id_examen_lab" + ind).val(ui.item.id);
    validaExamenSeleccionado($("#id_examen_lab" + ind).val());
    // consultaWsResultExamenSeleccionado($("#id_examen_lab" + ind).val());
    if (id_categoria_examen_lab == "9") {
      mostrarBotonParaTipoDeMuestraMicrobiologia(ind);
    }
    // SOLICITUD DE EXAMEN PARA HEMOCULTIVO
    if (codigo_procedimiento == '87040') {
      let fecnacafiliado = $('#fecnacafiliado').val();
      let partesFecha = fecnacafiliado.split('/');
      let fechaFormateada = `${partesFecha[2]}-${partesFecha[1]}-${partesFecha[0]}`;
      // Convertir la fecha de nacimiento a un objeto Date
      let fechaNacimiento = new Date(fechaFormateada);
      console.log(`Fecha nacimiento : ${fechaNacimiento}`);

      // Obtener la fecha actual
      let fechaActual = new Date();
      console.log(`Fecha actual : ${fechaActual}`);

      // Calcular la diferencia de años entre la fecha de nacimiento y la fecha actual
      let edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
      // Ajustar la edad si todavía no se ha alcanzado el cumpleaños en el año actual
      if (fechaActual.getMonth() < fechaNacimiento.getMonth() || (fechaActual.getMonth() === fechaNacimiento.getMonth() && fechaActual.getDate() < fechaNacimiento.getDate())) {
        edad--;
      }
      console.log(`Edad : ${edad}`);
      // Validar la edad y mostrar los botones correspondientes
      if (edad <= 14) {
        // SOLICITUD DE EXAMEN PARA HEMOCULTIVO NEONATO Y PEDIATRÍA
        mostrarBotonParaSolicitudHemocultivoPedriatria(ind);
      } else {
        // SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO
        mostrarBotonParaSolicitudHemocultivoAdulto(ind);
      }
    }
    // SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS
    if (codigo_procedimiento == '87163') {
      mostrarBotonParaSolicitudCitoquimicosLiquidosBiologicos(ind);
    }

    //valida las examenes depeendidntes
    var seleccionados = $("input[name='cod_lab[]']").map(function () {
      return $(this).val();
    }).get();

    if (verificarFaltantes(seleccionados) != "") {
      alert(verificarFaltantes(seleccionados));
    }
  }

  const validaExamenSeleccionado = (id_examen_lab) => {
    let dni_beneficiario = $('#nrodoctitular').val();
    // console.log({
    //   'dni_beneficiario' : dni_beneficiario,
    //   'id_examen_lab' : id_examen_lab,
    // });
    $.ajax({
      url: '/validacion_beneficiario_dias_por_examen_rua/' + dni_beneficiario + '/' + id_examen_lab,
      success: function (result) {
        let msg = "";
        let n = 0;
        let confirmar_toma = "";
        // console.log(["result",result]);
        if (result.examenes.length > 0) {
          // console.log(["result > 0",result]);
          for (let key of result.examenes) {
            if (n == 0) {
                msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.nombre_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene análisis de laboratorio pendientes del examen seleccionado en los últimos "+result.dias.option_value+" días, CANTIDAD : " + key.total_registros + "</h1>";
                msg += "<table class='table table-list-search' style='font-size: 11px !important;' >"; 
                msg += "<thead>";
                msg += "<tr>";
                msg += "<th>Código</th>";
                msg += "<th>Descripción Corta</th>";
                msg += "<th>Tipo de Atención</th>";
                msg += "<th>Servicio</th>";
                msg += "<th>Especialista</th>";
                msg += "<th>Fecha de Orden</th>";
                msg += "<th>Estado</th>";
                msg += "<th>Dias Transcurridos</th>";
                msg += "</tr>";
                msg += "</thead>";
                msg += "<tbody>";
            }
            msg += "<tr><td>" + key.codigo_lab + "</td>";
            msg += "<td>" + key.descripcion_corta + "</td>";
            if (key.id_tipo_atencion == 1) {
              msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
            }
            if (key.id_tipo_atencion == 2) {
              if (key.id_evaluacion > 0) {
                msg += "<td>" + `EMERGENCIA - EVALUACIÓN` + "</td>";
              } else {
                msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
              }
            }
            if (key.id_tipo_atencion == 1) {
              msg += "<td>" + `${key.nombre_servicio}` + "</td>";
            }
            if (key.id_tipo_atencion == 2) {
              msg += "<td>" + `${key.nombre_servicio_emergencia}` + "</td>";
            }
            msg += "<td>" + `${key.medico_paterno} ${key.medico_materno}, ${key.medico_nombre}` + "</td>";
            msg += "<td>" + (key.fecha_expedicion) + "</td>";
            if (key.confirmar_toma == null || key.confirmar_toma == "") {
              confirmar_toma = "Pendiente";
            } else {
              switch (key.confirmar_toma) {
                case "0":
                  confirmar_toma = "Procesado";
                  break;
                case "1":
                  confirmar_toma = "Cancelado";
                  break;
                case "2":
                  confirmar_toma = "Procesado";
                  break;
                case "3":
                  confirmar_toma = "Reingresado";
                  break;
                case "4":
                  confirmar_toma = "No Enviado";
                  break;
              }
            }
            msg += "<td>" + confirmar_toma + "</td>";
            msg += "<td>" + (key.dias_transcurridos) + "</td></tr>";
            n++;
          }
          msg += "</tbody></table>";
          $("#contenido-table-pendiente").html(msg);
          // Swal.fire({
            //   title: "Alerta Laboratorio",
            //   html: msg,
            //   width: 1000  
            // });
          $("#showDetalleExamenPendiente").modal(); 
        }
      }
    });
  }

  const consultaWsResultExamenSeleccionado = (id_examen_lab) => {
    let paciente_nrodocumento = $('#dni_beneficiario').val();
    // console.log({
    //   'dni_beneficiario' : dni_beneficiario,
    //   'id_examen_lab' : id_examen_lab,
    // });
    $.ajax({
      url: '/consulta_ws_result_beneficiario_examen_rua/' + paciente_nrodocumento + '/' + id_examen_lab,
      success: function (result) {
        let res = result.ResultadosLabView;
        console.log(["result",res]);
        let numerosImpresos = new Set();
        let n = 0;
        let n2 = 0;
        let msg = "";
        if (res && Array.isArray(res) && res.length > 0) {
          console.log("Entro en la condición");
          if (n == 0) {
              // msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + nombreCompleto + " con DNI " + dni_beneficiario + " tiene resultados de la prueba de laboratorio seleccionado de los últimos 3 meses</h1>";
              msg += `<table class="table table-list-search" style="font-size: 11px !important;">`; 
              msg += `<thead class="thead-resultados">`;
              msg += "<tr>";
              msg += `<th class="encabezado"></th>`;
              msg += `<th class="encabezado">Código de orden</th>`;
              msg += `<th class="encabezado">F.Toma de Muestra</th>`;
              msg += `<th class="encabezado">Tipo de Atención</th>`;
              msg += `<th class="encabezado">Establecimiento</th>`;
              msg += "</tr>";
              msg += "</thead>";
              msg += "<tbody>";
          }
          for (let key of res) {
            // console.log( key.p_nombre );
            let numeroActual = key.o_numero;
            let elementoOcultar = "elementoOcultarResult" + n;
            // console.log(numeroActual);
            // Verificar si el número ya se ha impreso
            if (!numerosImpresos.has(numeroActual)) {
              // Si no se ha impreso, imprimir y agregar al conjunto
              console.log('o_numero:', numeroActual);
              
              msg += `<tr><td><a class="toggleExamenesResult" data-registro="${n}"><i class="fa fa-plus-circle icon-visible" aria-hidden="true"></i></a></td>`;
              msg += `<td>${numeroActual}</td>`;
              msg += `<td>${key.o_fecha_toma}</td>`;
              msg += `<td>${key.s_nombre}</td>`;
              msg += `<td>${key.c_nombre}</td>`;
              msg += `</tr>`;
              msg += `<tr class="thead-resultados-detalle ${elementoOcultar} hide-element-result">`;
              msg += `<th class="encabezado">Examen</th>`;
              msg += `<th class="encabezado">Resultado</th>`;
              msg += `<th class="encabezado">Rango Referencial</th>`;
              msg += "</tr>";
              numerosImpresos.add(numeroActual);
              n2 = n;
              n++;
            }
            elementoOcultar = "elementoOcultarResult" + n2;
            msg += `<tr class="tbody-resultados-detalle ${elementoOcultar} hide-element-result">`
            msg += `<td>${key.p_nombre}</td>`;
            msg += `<td>${key.l_resultado}</td>`;
            msg += `<td>${key.l_ref_inf} - ${key.l_ref_sup}</td>`;
            msg += "</tr>";
            
          }
          //console.table(result);
          msg += "</tbody></table>";
          $("#contenido-table-result").html(msg);
          // Swal.fire({
          //   title: "Resultados Laboratorio",
          //   html: msg,
          //   width: 1000  
          // });
          $("#showDetalleExamenResult").modal(); 
        }
      }
    });
  }

  var grupos = [
      ["82803.01", "36600"],
      ["82575", "82565"],
      ["89190", "87999"],
      ["87070", "87184"],
      ["87163", "87184"],
      ["87045", "87184"],
      ["87040.01", "87184"],
      ["87040.02", "87184"]
  ];

  function verificarFaltantes(seleccionados) {
    var mensajes = [];

    $.each(grupos, function (index, grupo) {
      var presentes = grupo.filter(codigo => seleccionados.includes(codigo));

      if (presentes.length === 1) { // Solo uno del grupo está presente
        var faltante = grupo.find(codigo => !seleccionados.includes(codigo));
        var nivel = $('#id_nivel').val();
        var sexo = $('#nomsexo').val();
        var id_tipo_atencion = $('#id_tipo_atencion').val();
        if (sexo == 'M')
          genero = 1;
        else
          genero = 2;
        
        $.ajax({
          url: '{{ url("/") }} /obtener_laboratorio_convenio_emergencia/' + faltante + '/' + nivel + '/' + genero + '/' + id_tipo_atencion,
          //url: '../obtener_procedimiento/' + request.term,
          dataType: "json",
          success: function(data) {
            if(data.length == 0){

            }else{
              AddExamenLab();
              var ind = $('#tblExamenLab tbody tr').length;
              var da = {
                item:data[0]
              };
              console.log(da);
              asignarValoresSelect(da,(ind-1));
            }
            
          }
        });
      }
    });

    return mensajes.length > 0 ? mensajes.join("\n") : "";
  }

  function AddExamenRad() {
    var newRow = "";
    var ind = $('#tblExamenRad tbody tr').length;
    
    if (ind > 0) {
      $('.id_radiologia').each(function() {
        var ind_tmp = $(this).data("id");
        if (ind_tmp => ind) {
          ind = Number(ind_tmp) + 1;
        }
      });
      fila_medicamentos = $('#tblExamenRad tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento = fila_medicamentos[idval];
      
    }

    var item_comparativo = '';
    item_comparativo+='<option value="0">--Seleccionar--</option>';
    item_comparativo+='<option value="1">SI</option>';
    item_comparativo+='<option value="2">NO</option>';
    
    var item_lado = '';
    item_lado+='<option value="0">--Seleccionar--</option>';
    item_lado+='<option value="1">Izquierdo</option>';
    item_lado+='<option value="2">Derecho</option>';
    item_lado+='<option value="3">Ambos</option>';

    newRow +='<tr>';
    newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de Radiografia" name="nombre_examen_rad[]" tabindex="8" required="" id="nombre_examen_rad' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query id_radiologia" id="id_examen_rad' + ind +'" name="id_examen_rad[]" value="" data-id='+ind+'></td>';
    newRow +='<td><select class="form-control id_comparativo" id="cmbComparativo'+ind+'" name="id_comparativo[]" style="width:150px">'+item_comparativo+'</select></td>';
    newRow +='<td><select class="form-control id_lado" id="cmbLado'+ind+'" name="id_lado[]" style="width:150px">'+item_lado+'</select></td>';
    newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_rad_observacion typeahead tt-query" style="width:95%" id="examen_rad_observacion' +ind + '" name="examen_rad_observacion[]"></td>';
    newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_rad_cantidad typeahead tt-query" style="" id="examen_rad_cantidad' +ind + '" name="examen_rad_cantidad[]" value="1"></td>';
    newRow += '<td><select class="form-control select-diagnostico"  id="miSelectRad' + ind + '" name="miSelectRad[]" style="width:95%"></select></td>';
    newRow += `<td>
                <input type="hidden" id="radio_portatil${ind}" name="radio_portatil[]" value="NO" data-id="${ind}" />
                <button tabindex="100" type="button" id="addRadioPortatil${ind}" class="btn btn-lg btn-toggle addRadioPortatil" data-id="${ind}" data-toggle="button" aria-pressed="false" autocomplete="off">
                  <div class="handle"></div>
                </button>
              </td>`;
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style=""><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
    $('#tblExamenRad tbody').append(newRow);
    // $("#examen_rad_cantidad" + ind).on('input', function() {
    //   var valor = $(this).val();
    //   if (valor === '0') {
    //     $(this).val(''); // Eliminar el valor si ingresan 0
    //   }
    // });
    var nivel = $('#id_nivel').val();
    var sexo = $('#nomsexo').val();
    //const id_servicio = $('#id_servicio').val();
    const id_servicio_encargado = $('#id_servicio_encargado').val();
    let genero = (sexo == 'M' ? 1 : 2);
    
    const selectElementRad = document.getElementById('miSelectRad' + ind);
    
    listIdDiagnosticoObjetos.forEach((item) => {
      let option = document.createElement('option');
      option.value = item.id;
      option.textContent = item.nombre;
      selectElementRad.appendChild(option);
    });

    let id_tipo_atencion = $("#id_tipo_atencion").val();
    let id_servicio = 0;
    switch (parseInt(id_tipo_atencion)) {
      case 2: id_servicio = 76; break; // Emergencia
      case 3: id_servicio = 105; break; // Hospitalizacion
    }

    $("#nombre_examen_rad" + ind).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          url: '{{ url("/") }}/obtener_radiologia_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + id_servicio,
          //url: '../obtener_procedimiento/' + request.term,
          dataType: "json",
          success: function(data) {
            let listaRadiologiaSelecionado = $(".id_radiologia").map(function(){return parseInt($(this).val());}).get();
            let filtro = data.filter((element)=>!listaRadiologiaSelecionado.includes(element.id));
            // console.log(filtro);
            response(filtro);
          }
        })
    
      },
      select: function(e, ui) {
        var id_examen_rad = ui.item.id;
        var id_codigo_rad = ui.item.codigo_procedimiento;
        // console.log("=> ver el codigo_procedimiento:" + ui.item.codigo_procedimiento);
        // console.log("=> id_codigo_rad:"+id_codigo_rad);
        $("#id_examen_rad" + ind).val(id_examen_rad);
        $("#cod_examen"+ ind).val(id_examen_rad);
        //$("#id_codigo_rad" + ind).val(id_codigo_rad);
        $("#nombre_examen_rad" + ind).prop("readonly", true);
        validaExamenRadiologiaSeleccionado(id_codigo_rad);
      }
    });
    
    $("#nombre_examen_rad" + ind).on('blur', function() {
      var id_examen_rad = $("#id_examen_rad" + ind).val();
      let input_nombre_examen = $("#nombre_examen_rad" + ind).val();

      if (input_nombre_examen.trim() != "" && id_examen_rad == "") {
        bootbox.alert("Falta ingresar el examen radiografia en esta fila");
        // $("#examen_rad_cantidad" + ind).val("");
        return false;
      }
    });
    // $("#examen_rad_cantidad" + ind).on('blur', function() {
    //   var id_examen_rad = $("#id_examen_rad" + ind).val();
    //   if (id_examen_rad == "") {
    //     bootbox.alert("Falta ingresar la cantidad en este examen radiografia");
    //     $("#examen_rad_cantidad" + ind).val("");
    //     return false;
    //   }
    // });
  }

  const validaExamenRadiologiaSeleccionado = (id_examen_rad) => {
    let dni_beneficiario = $('#dni_beneficiario').val();
    // console.log({
    //   'dni_beneficiario' : dni_beneficiario,
    //   'id_examen_rad' : id_examen_rad,
    // });
    $.ajax({
      url: '/validacion_beneficiario_dias_por_examen_rad/' + dni_beneficiario + '/' + id_examen_rad,
      success: function (result) {
        let msg = "";
        let n = 0;
        
        if (result.length > 0) {
          for (let key of result) {
            if (n == 0) {
                msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.materno_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene análisis de Imagenes pendientes del examen seleccionado en los últimos 3 meses, CANTIDAD : " + key.total_registros + "</h1>";
                msg += "<table class='table table-list-search' style='font-size: 11px !important;' >"; 
                msg += "<thead>";
                msg += "<tr>";
                msg += "<th>Código</th>";
                msg += "<th>Descripción Corta</th>";
                msg += "<th>Tipo de Atención</th>";
                msg += "<th>Servicio</th>";
                msg += "<th>Especialista</th>";
                msg += "<th>Fecha de Orden</th>";
                msg += "<th>Estado</th>";
                msg += "<th>Dias Transcurridos</th>";
                msg += "</tr>";
                msg += "</thead>";
                msg += "<tbody>";
            }
            msg += "<tr><td>" + key.codigo_rad + "</td>";
            msg += "<td>" + key.descripcion_corta + "</td>";
            if (key.id_tipo_atencion == 1) {
              msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
            }
            if (key.id_tipo_atencion == 2) {
              if (key.id_evaluacion > 0) {
                msg += "<td>" + `EMERGENCIA - EVALUACIÓN` + "</td>";
              } else {
                msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
              }
            }
            if (key.id_tipo_atencion == 1) {
              msg += "<td>" + `${key.nombre_servicio}` + "</td>";
            }
            if (key.id_tipo_atencion == 2) {
              msg += "<td>" + `${key.nombre_servicio_emergencia}` + "</td>";
            }
            msg += "<td>" + `${key.medico_paterno} ${key.medico_materno}, ${key.medico_nombre}` + "</td>";
            msg += "<td>" + (key.fecha_expedicion) + "</td>";
            if (key.flag_validated == '1') {
              msg += "<td style='font-weight : 700;'>ATENDIDO</td>";
            } else {
              if (key.flag_informe == '1') {
                msg += "<td style='font-weight : 700;'>PENDIENTE VALIDAR</td>";
              } else {
                if (key.flag_cita_imagenes == 1 ) {
                  msg += "<td style='font-weight : 700;'>PENDIENTE CITADO</td>";
                } else if (key.confirmar_toma == null || key.confirmar_toma == '') {
                  msg += "<td style='font-weight : 700;'>RECIENTE</td>";
                }
              }
            }
            
            msg += "<td>" + (key.dias_transcurridos) + "</td></tr>";
            n++;
          }
          msg += "</tbody></table>";
          $("#contenido-table-pendiente-radiologia").html(msg);
          $("#showDetalleExamenPendienteRadiologia").modal(); 
        }
      }
    });
  }
  /***********OTROS DATOS PARA CENTRO QUIRURGICO**************/
    $(document).ready(function() {
      $('#fecha_probable_pq').datepicker({
        dateFormat: 'dd-mm-yy',
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        minDate: 0
      });

      $('#addRequiereIntervencionQuirurgica').on('click', function () {
        var addCQX = $('#addRequiereIntervencionQuirurgica').attr("aria-pressed");
        $("#requiere_intervencion_quirurgica").val("0");
        $("#fsRequiereIntervencionQuirurgica").hide();
        if(addCQX == "false"){
          $("#fsRequiereIntervencionQuirurgica").show();
          $("#requiere_intervencion_quirurgica").val("1");
        }
        $.ajax({
          url: '/get_procedimientos',
          type: "GET",
          dataType: "json",
          success: function(result){
            let newOption = "";
            newOption = "<option value=''>--Seleccionar--</option>";
            $(result).each(function (ii, oo) {
              newOption += "<option value='"+oo.id+"' >"+oo.codigo+" - "+oo.descripcion+"</option>";
            });                 
            $('#cmbProcedimientoIntervencionTemp').html(newOption);
          }
        });
        //medicos cirujanos
        // LLamar a medicos cirujanos
        let id_establecimiento = $("#id_establecimiento").val();
        let id_servicio = $("#id_servicio_encargado").val();
        $.ajax({
          url: '/get_profesionales_x_especialidad/'+ id_establecimiento + '/' + id_servicio,
          type: "GET",
          dataType: "json",
          success: function(result){
            let newOption = "";
            newOption = "<option value=''>--Seleccionar--</option>";
            $(result).each(function (ii, oo) {
              newOption += "<option value='"+oo.id+"' >"+oo.paterno+" "+oo.materno+", "+oo.nombre+"</option>";
            });                 
            $('#cmbProfesionalesTemp').html(newOption);
          }
        });
      });
    });
   
    $('#addDiagnosticoQuirurgico').on('click', function() {
      AddDiagnosticoQuirurgico();
    });
    
    function AddDiagnosticoQuirurgico(){
      var newRow = "";
      var ind = $('#tblDiagnosticoQuirurgico tbody tr').length;
      
      if(ind > 0){
        $('.id_diagnostico_quirurgico').each(function(){
          var ind_tmp = $(this).data("id");
          if(ind_tmp => ind){
            ind=Number(ind_tmp)+1;
          }
        });
              
        fila_medicamentos=$('#tblDiagnosticoQuirurgico tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento=fila_medicamentos[idval];
        
      }
      
      var item_tipo_diagnostico = '';

      item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';

      newRow +='<tr>';
      newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico_quirurgico[]" tabindex="8" required="" id="nombre_diagnostico_quirurgico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
      newRow +='<input type="hidden" class="form-control id_diagnostico_quirurgico typeahead tt-query" id="id_diagnostico_quirurgico'+ind+'" name="id_diagnostico_quirurgico[]" value="" data-id='+ind+'></td>';

      newRow +='<td><select class="form-control id_tipo_diagnostico_quirurgico" id="cmbTipoDiagnosticoQuirurgico'+ind+'" name="id_tipo_diagnostico_quirurgico[]" style="margin:0 20px;width:150px">'+item_tipo_diagnostico+'</select></td>';
      newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow +='</tr>';
      $('#tblDiagnosticoQuirurgico tbody').append(newRow);

      var sexo  = $('#nomsexo').val();
      if(sexo=='M'){
        genero=1;
      }
      else{
        if(sexo=='F'){
          genero=2;
        }
      }
      
      $("#nombre_diagnostico_quirurgico"+ind).autocomplete({
        minLength: 1,
        delay : 400,
        source: function(request, response) {
          $.ajax({
            url: '/obtener_diagnostico_genero/' + request.term + '/'+ genero,
            dataType: "json",
            success: function(data)  {
              let listaDiagnosticoSeleccionadoCQX = $(".id_diagnostico_quirurgico").map(function(){return parseInt($(this).val());}).get();
              let filtro = data.filter((element)=>!listaDiagnosticoSeleccionadoCQX.includes(element.id));
              response(filtro);
            }         
          })
      
        },select:  function(e, ui) {
          var id_diagnostico = ui.item.id;
          $("#id_diagnostico_quirurgico"+ind).val(id_diagnostico);
          $("#nombre_diagnostico_quirurgico"+ind).prop("readonly",true);
        }
      });
      
      $("#nombre_diagnostico_quirurgico"+ind).on('blur', function() {
        const diagnostico = $("#nombre_diagnostico_quirurgico"+ind).val();
      
        const firstLetterCode = diagnostico.split("-")[0].trim().split("")[0];
        const codigoDiagnostico = diagnostico.split("-")[0].trim();
        var id_diagnostico = $("#id_diagnostico_quirurgico"+ind).val();
        if(id_diagnostico == ""){
          bootbox.alert("Falta ingresar el diagnostico en esta fila");
          $("#nombre_diagnostico_quirurgico"+ind).val("");
          return false;
        } else {
          if (firstLetterCode == "Z" || flag_saludpol == 0) {
            bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado por SALUDPOL.");
          }
          if (codigoDiagnostico == "Y66.X") {
            bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado.");
          }
        }
      });
      
    }

    $('#addProcedimientoIntervencion').on('click', function () {
      AddProcedimientoIntervencion();
    });

    function AddProcedimientoIntervencion() {
      let newRow = "";
      let ind = $('#table_procedimiento_intervencion tbody tr').length;
      
      let item_procedimiento = "";
      $('#cmbProcedimientoIntervencionTemp option').each(function() {
        item_procedimiento += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
      });

      newRow +='<tr>';
      newRow +='<td><select class="form-control cmbProcedimientoIntervencion" id="cmbProcedimientoIntervencion' + ind + '" ind="' + ind + '" name="id_procedimiento_intervencion[]" >' + item_procedimiento + '</select></td>';
      newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:10px;"><i class="fa fa-times"></i> </button></td>';

      newRow +='</tr>';
      $('#table_procedimiento_intervencion tbody').append(newRow);
      $("#cmbProcedimientoIntervencion" + ind).select2({max_selected_options: 4}).focus();
      
      $("#cmbProcedimientoIntervencion"+ind).on("change", function (e) {
        var flagx = 0;
        cmb = $(this);
        id_producto = $("#cmbProcedimientoIntervencion"+ind).val();
        id_user=<?php echo Auth::user()->id;?>;
        
        $('.cmbProcedimientoIntervencion').each(function(){
          var ind_tmp = $(this).val();
          if($(this).val() == id_producto)flagx++;
        });
        
        if(flagx > 1){
          bootbox.alert("El Procedimiento ya ha sido ingresado");
          $("#cmbProcedimientoIntervencion"+ind).val("").trigger("change");
          return false;
        }
      
      });
      item_procedimiento++;
    }

    $('#addMedicoCirujano').on('click', function () {
        AddMedicoCirujano();
    });

    function AddMedicoCirujano() {
        var newRow = "";
        var ind = $('#table_medico_cirujano tbody tr').length;
        
        var item_producto = "";
        $('#cmbProfesionalesTemp option').each(function() {
          item_producto += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
        });

        let item_cargo = "";
        $('#cmbTipoProfesionalTemp option').each(function() {
          item_cargo += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
        });
        
        newRow +='<tr>';
        newRow +='<td><select class="form-control cmbMedicoCirujano" id="cmbMedicoCirujano' + ind + '" ind="' + ind + '" name="id_medico_cirujano[]" >' + item_producto + '</select></td>';
        newRow +='<td><select class="form-control cargoMedicoCirujano" id="cmbMedicoCirujanoCargo'+ind+'" name="id_medico_cirujano_cargo[]" style="margin-left:20px;">'+item_cargo+'</select></td>';
        
        newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:30px;"><i class="fa fa-times"></i> </button></td>';

        newRow +='</tr>';
        $('#table_medico_cirujano tbody').append(newRow);
        $("#cmbMedicoCirujano" + ind).select2({max_selected_options: 4}).focus();
        
        $("#cmbMedicoCirujano"+ind).on("change", function (e) {
          var flagx = 0;
          cmb = $(this);
          id_producto = $("#cmbMedicoCirujano"+ind).val();
          id_user={{Auth::user()->id}};
          
          $('.cmbMedicoCirujano').each(function(){
            var ind_tmp = $(this).val();
            if($(this).val() == id_producto)flagx++;
          });
          
          if(flagx > 1){
            bootbox.alert("El Médico ya ha sido ingresado");
            $("#cmbMedicoCirujano"+ind).val("").trigger("change");
            return false;
          }
        
        });
        filasmedicamentos++;
      }

    $('#tblDiagnosticoQuirurgico tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $('#table_procedimiento_intervencion tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      var fila = obj.closest('tr');
      var filaPosition = fila.index();
      obj.parent().parent().remove();
    });

    $('#table_medico_cirujano tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      var fila = obj.closest('tr');
      var filaPosition = fila.index();
      obj.parent().parent().remove();
    });
    function filtrarNumeroDecimal(input) {
      input.value = input.value
        .replace(/[^0-9.]/g, '')
        .replace(/(\..*?)\..*/g, '$1');
    }

    $('#id_tipo_cirugia').change(function() {
      var electiva = $('#id_tipo_cirugia').val();
      if(electiva=='1'){
        $('#electiva').show();
        $('#es_programacion_quirurgica').val(1);
        $('#fecha_programacion').show();
        $('#duracion_cirugia_calculada').show();
        $('#duracion_cirugia_calculada').prop('disabled', true); 
        $('#hora_minuto').hide();
        $('#duracion_cirugia').val(0);
        $('#horas').val(0);
      }else{
        $('#fecha_probable_pq').val('');
        $('#hora_inicio_intervencion').val('');
        $('#hora_fin_intervencion').val('');
        $('#duracion_cirugia_calculada').val('');
        $('#electiva').hide();
        $('#duracion_cirugia_calculada').hide();
        $('#hora_minuto').show();
        $('#duracion_cirugia').val(0);
        $('#minutos').val(0);
         $('#fecha_programacion').hide();
         $('#es_programacion_quirurgica').val(0);
      }
    });

    $('#horas, #minutos').on('input', function() {
      const horas = parseInt($('#horas').val()) || 0;
      const minutos = parseInt($('#minutos').val()) || 0;
      const total = horas * 60 + minutos;
      $('#duracion_cirugia').val(total);
    });

    $(document).ready(function(){
      $('#horas, #minutos').on('input', function() {
        const horas = parseInt($('#horas').val()) || 0;
        const minutos = parseInt($('#minutos').val()) || 0;
        const total = horas * 60 + minutos;
        $('#duracion_cirugia').val(total);
      });

      let hora_duracion = $('#duracion_cirugia').val() || 0;
      const minutosRaw = parseInt(hora_duracion, 10);

      if (!isNaN(minutosRaw) && minutosRaw > 0) {
          const minutos = minutosRaw < 10 ? minutosRaw * 60 : minutosRaw;
          const horas = Math.floor(minutos / 60);
          const min = minutos % 60;
          const hhmm = (String(horas).padStart(2, '0')) + ':' + (String(min).padStart(2, '0'));
          document.getElementById("duracion_cirugia_calculada").value = hhmm;
      }

      let inicio = document.getElementById('hora_inicio_intervencion');
      let fin = document.getElementById('hora_fin_intervencion');

      if (inicio) inicio.addEventListener('change', calcularDuracion);
      if (fin) fin.addEventListener('change', calcularDuracion);

      function calcularDuracion() {
          const inicioInput = document.getElementById('hora_inicio_intervencion');
          const finInput = document.getElementById('hora_fin_intervencion');
          const inputDuracion = document.getElementById('duracion_cirugia_calculada');
          const inputMinutos = document.getElementById('duracion_cirugia'); 

          if (!inicioInput || !finInput || !inputDuracion) return;

          const inicio = inicioInput.value;
          const termino = finInput.value;

          if (!inicio || inicio === '00:00') {
              Swal.fire({
                  icon: 'warning',
                  title: 'Hora de inicio inválida',
                  text: 'La hora de inicio debe ser mayor a 00:00.'
              });
              inicioInput.focus();
              return;
          }

          if (!termino || termino === '00:00') {
              Swal.fire({
                  icon: 'warning',
                  title: 'Hora de término inválida',
                  text: 'La hora de término debe ser mayor a 00:00.'
              });
              finInput.value = '';
              finInput.focus();
              return;
          }

          const inicioMillis = convertirATiempoEnMilisegundos(inicio);
          let terminoMillis = convertirATiempoEnMilisegundos(termino);

          // Cruce de medianoche
          if (terminoMillis < inicioMillis) {
              terminoMillis += 24 * 60 * 60 * 1000;
          }

          const diferenciaMillis = terminoMillis - inicioMillis;

          if (diferenciaMillis <= 0) {
              Swal.fire({
                  icon: 'warning',
                  title: 'Horario inválido',
                  text: 'La hora de término debe ser mayor que la hora de inicio.'
              });
              finInput.value = '';
              return;
          }

          inputDuracion.value = convertirMilisegundosATiempo(diferenciaMillis);
          const minutosTotales = Math.floor(diferenciaMillis / 60000);
          inputMinutos.value = minutosTotales;
      }
      function convertirATiempoEnMilisegundos(hora) {
          const partes = hora.split(':');
          const horas = parseInt(partes[0], 10);
          const minutos = parseInt(partes[1], 10);
          return (horas * 60 * 60 * 1000) + (minutos * 60 * 1000);
      }
      function convertirMilisegundosATiempo(milis) {
          const horas = Math.floor(milis / (1000 * 60 * 60));
          const minutos = Math.floor((milis % (1000 * 60 * 60)) / (1000 * 60));
          return `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}`;
      } 

    });
  //OTROS DATOS PARA CENTRO QUIRURGICO

  //================================================================================================= Modal chklist laboratorio
  function cargarDiagnosticosTodos() {
    $("#contenedorDX").html("Cargando...");
    var nivel = $('#id_nivel').val();
    var sexo = $('#nomsexo').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();
    var genero = (sexo == 'M') ? 1 : 2;

    $.ajax({
      url: '/obtener_laboratorio_convenio/all/' + nivel + '/' + genero + '/' + id_tipo_atencion,  
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        let total = data.length;
        let clase = 'col1';
        let modalClase = 'modal-col1';

        if (total > 5 && total <= 10) { clase = 'col2'; modalClase = 'modal-col2'; }
        else if (total > 10) { clase = 'col3'; modalClase = 'modal-col3'; }
        // else if (total > 20) { clase = 'col4'; modalClase = 'modal-col4'; }

        $("#contenedorDX").removeClass("col1 col2 col3 col4").addClass(clase);
        $("#modalDX").removeClass("modal-col1 modal-col2 modal-col3 modal-col4").addClass(modalClase);

        let grupos = {};
        data.forEach(function(item) {
            let idCat = item.id_categoria || "0";
            let nombreCat = item.categoria_nombre || "SIN CATEGORÍA";
            if (!grupos[idCat]) {
                grupos[idCat] = { nombre: nombreCat, items: [] };
            }
            grupos[idCat].items.push(item);
        });

        let html = "";
        Object.keys(grupos).sort().forEach(function(idCat) {
            html += `
              <h4 style="margin: 6px 0 4px 0 !important;font-weight: 600;color: #3c763d;border-bottom: 1px solid white;padding-bottom: 2px;padding-top: 12px;font-size: 15px;text-transform: uppercase;">
                  ${grupos[idCat].nombre}
              </h4>
            `;
            grupos[idCat].items.forEach(function(item) {
                html += `
                    <label style="display:block; margin-left:10px;">
                        <input type="checkbox" 
                            class="chk_rualab"  
                            name="chk_rualab[]" 
                            data-id="${item.id}"
                            data-codigo="${item.codigo_procedimiento || ''}"
                            data-categoria="${item.id_categoria || ''}"
                            data-label="${item.label || ''}"
                            style="transform: scale(1.4); margin-right:8px;">
                        ${item.label || ''}
                    </label>
                `;
            });
        });

        $("#contenedorDX").html(html);

        const unionMap = {
          // Bidireccionales
          "82803": "36600",
          "36600": "82803",
          "82575": "82565",
          "82565": "82575",
          "82043.01": "84999.09",
          "84999.09": "82043.01",
          "89190": "87999",
          "87999": "89190",
          "87115": "84999.13",
          "84999.13": "87115",
          // Unidireccionales
          "87070": "82020",
          "87163": "87184",
          "87040.01": "87184",
          "87040.03": "87184",
          "87045": "87184"
        };

        $(".chk_rualab").change(function() {
          const codigo = $(this).data("codigo");
          const union = unionMap[codigo];
          if (union) {
            if (Array.isArray(union)) {
              union.forEach(u => {
                const target = $(`.chk_rualab[data-codigo='${u}']`);
                if (target.length) target.prop('checked', $(this).prop('checked'));
              });
            } else {
              const target = $(`.chk_rualab[data-codigo='${union}']`);
              if (target.length) target.prop('checked', $(this).prop('checked'));
            }
          }
        });

      }
    });
  }

  $('#modalDX').on('show.bs.modal', function () {
    cargarDiagnosticosTodos();
  });

  $("#guardarRUALab").on('click', function () {

    const implementacionSolicitudEspeciales = false;
    const id_nivel = $("#id_nivel").val();
    const checked = $("input[name='chk_rualab[]']:checked");
    if (checked.length === 0) {
      $("#modalDX").modal('hide');
      return;
    }

    checked.each(function () {
      const $this = $(this);
      const id_examen_lab = $this.data('id');
      const codigo = $this.data('codigo');
      const categoria = $this.data('categoria');
      const label = $this.data('label');

      if ($("#tblExamenLab tbody tr[data-id='" + id_examen_lab + "']").length > 0) {
        return true; 
      }

      const ind = obtenerIndiceTblExamenLab();

      let rowHtml = `
          <tr data-id="${id_examen_lab}">
            <td>
                <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required id="nombre_examen_lab${ind}" class="input-sm" style="width:100%; text-align:left" value="${label}" readonly />
                <input type="hidden" class="id_laboratorio" id="id_examen_lab${ind}" name="id_examen_lab[]" data-id="${ind}" value="${id_examen_lab}">
                <input type="hidden" class="cod_lab" id="cod_lab${ind}" name="cod_lab[]" data-id="${ind}" value="${codigo}">
                <input type="hidden" class="id_categoria" id="id_categoria_examen_lab${ind}" name="id_categoria_examen_lab[]" data-id="${ind}" value="${categoria}">
            </td>`;
      if (id_nivel && parseInt(id_nivel) < 3) {
        rowHtml += `
            <td>
              <input type="text" placeholder="Observacion" class="form-control examen_lab_observacion" id="examen_lab_observacion${ind}" name="examen_lab_observacion[]">
            </td>`;
      }
      rowHtml += `
            <td>
              <input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad" id="examen_lab_cantidad${ind}" name="examen_lab_cantidad[]" value="1">
            </td>
            <td>
              <select class="form-control select-diagnostico" id="miSelectLab${ind}" name="miSelectLab[]">
                <option value="">-- Seleccionar --</option>
              </select>
            </td>
      `;

      if (implementacionSolicitudEspeciales) {
        rowHtml += `
            <td>
                <button type="button" class="btn btn-success btn-xs tipoMuestraMicrobiologia" id="tipoMuestraMicrobiologia${ind}" data-id="${ind}" style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Tipo de muestra</button>
                <input type="hidden" id="descripcion_tipo_muestra${ind}" name="descripcion_tipo_muestra[]" data-id="${ind}" value="">
                <input type="hidden" id="flag_descripcion_tipo_muestra${ind}" name="flag_descripcion_tipo_muestra[]" value="0">
                <button type="button" class="btn btn-warning btn-xs editarTipoMuestraMicrobiologia" id="editarTipoMuestraMicrobiologia${ind}" data-id="${ind}" style="display:none; margin-left:4px"><i class="fa fa-edit"></i>&nbsp;Tipo de muestra</button>
            </td>
            <td>
                <button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoAdulto" id="solicitudExamenEspecializadoHemocultivoAdulto${ind}" data-id="${ind}" style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>
                ${crearHiddenFields(camposAdulto, ind)}
                <button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoAdulto" id="editarSolicitudExamenEspecializadoHemocultivoAdulto${ind}" data-id="${ind}" style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>

                <button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoPedriatria" id="solicitudExamenEspecializadoHemocultivoPedriatria' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>
                ${crearHiddenFields(camposPed, ind)}
                <button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoPedriatria" id="editarSolicitudExamenEspecializadoHemocultivoPedriatria' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>

                <button type="button" class="btn btn-success btn-xs solicitudCitoquimicosLiquidosBiologicos" id="solicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>
                ${crearHiddenFields(camposCito, ind)}
                <button type="button" class="btn btn-warning btn-xs editarSolicitudCitoquimicosLiquidosBiologicos" id="editarSolicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>
            </td>
        `;
      } else {
        rowHtml += `
            <td></td>
            <td></td>
        `;
      }

      rowHtml += `
            <td>
              <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px">
                <i class="fa fa-times"></i> Eliminar
              </button>
            </td>
          </tr>
      `;
      $("#tblExamenLab tbody").append(rowHtml);

      /*if (typeof asignarValoresDesdeModal === 'function') {
          asignarValoresDesdeModal({id_examen_lab: id_examen_lab, codigo_procedimiento: codigo, id_categoria: categoria, label: label}, ind);
      } else {
          $(`#id_examen_lab${ind}`).val(id_examen_lab);
          $(`#cod_lab${ind}`).val(codigo || '');
          $(`#id_categoria_examen_lab${ind}`).val(categoria || '');
          $(`#nombre_examen_lab${ind}`).val(label || '');
      }*/

      const select = document.getElementById('miSelectLab' + ind);
      if (Array.isArray(listIdDiagnosticoObjetos) && select) {
          listIdDiagnosticoObjetos.forEach(item => {
              const opt = document.createElement('option');
              opt.value = item.id;
              opt.textContent = item.nombre;
              select.appendChild(opt);
          });
      }
    });

    $("#modalDX").modal('hide');
    $("input[name='chk_rualab[]']").prop('checked', false);
  });

  document.querySelectorAll('input[name="hora_toma_muestra"]').forEach(radio => {
    radio.addEventListener('change', function() {

      let stringIdHoraTomaMuestraSeleccionada = String(this.value);
      let descripcionHoraTomaMuestra = "";
      switch (stringIdHoraTomaMuestraSeleccionada) {
        case "0": descripcionHoraTomaMuestra = "OTRO"; break;
        case "1": descripcionHoraTomaMuestra = "05:00 AM"; break;
        case "2": descripcionHoraTomaMuestra = "09:00 AM"; break;
        case "3": descripcionHoraTomaMuestra = "16:00 PM"; break;
        case "4": descripcionHoraTomaMuestra = "22:00 PM"; break;
      }

      document.getElementById("id_hora_toma_muestra").value = String(stringIdHoraTomaMuestraSeleccionada);
      document.getElementById("lab_orden_toma_muestra").value = descripcionHoraTomaMuestra;
    });
  });

</script>
<style>
  .modal-dialog {
    width: 1200px !important;
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
    text-align: left !important;
  }
  .form-group {
    margin-bottom: 10px;
  }
  #divHorariox {
    /*height: 200px!important;;*/
    /*background-color: #fed9ff; */
    /*width: 600px; */
    width: 100%;
    height: 550px;
    overflow-x: hidden;
    overflow-y: auto;
    text-align: center;
    padding: 0px !important;
  }
  /********************/
  body {
    /* font-family: 'Montserrat', 'Lato', 'Open Sans', 'Helvetica Neue', Helvetica, Calibri, Arial, sans-serif;
    color: #6b7381;
    background: #f2f2f2; */
  }
  .jumbotron {
    background: #6b7381;
    color: #bdc1c8;
  }
  .jumbotron h1 {
    color: #fff;
  }
  .example {
    margin: 4rem auto;
  }
  .example>.row {
    margin-top: 2rem;
    height: 5rem;
    vertical-align: middle;
    text-align: center;
    border: 1px solid rgba(189, 193, 200, 0.5);
  }
  .example>.row:first-of-type {
    border: none;
    height: auto;
    text-align: left;
  }
  .example h3 {
    font-weight: 400;
  }
  .example h3>small {
    font-weight: 200;
    font-size: 0.75em;
    color: #939aa5;
  }
  .example h6 {
    font-weight: 700;
    font-size: 0.65rem;
    letter-spacing: 3.32px;
    text-transform: uppercase;
    color: #bdc1c8;
    margin: 0;
    line-height: 5rem;
  }
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

  .swal-wide{
    width:500px !important;
  }

  .swal2-icon.swal2-warning {
    font-size: 15px;
  }

  /* Estilos para Checklist de RUA Laboratorio */
  .modal-col1 .modal-dialog {
    max-width: 450px !important;
  }

  .modal-col2 .modal-dialog {
    max-width: 950px !important;
  }

  .modal-col3 .modal-dialog {
    max-width: 1200px !important;
  }

  .modal-col4 .modal-dialog {
    max-width: 1400px !important;
  }

  #contenedorDX.col1 { grid-template-columns: 350px; }
  #contenedorDX.col2 { grid-template-columns: repeat(2, 350px); }
  #contenedorDX.col3 { grid-template-columns: repeat(3, 320px); }
  #contenedorDX.col4 { grid-template-columns: repeat(4, 300px); }

  .modal-col5 .modal-dialog {
    max-width: 1400px !important;
  }

  #contenedorDX {
    display: grid;
    justify-content: center;
    gap: 10px;
    max-height: calc((50px + 10px) * 10);
    overflow-y: auto;
    padding-right: 10px;
  }

  #contenedorDX label {
    display: flex;
    align-items: center;
  }

  #contenedorDX.col5 {
    grid-template-columns: repeat(4, 250px); 
  }
  /* Fin estilos para Checklist de RUA Laboratorio */

  /**************************** MARCA DE AGUA - HISTORIAL DE ATENCIONES ***********************/
  .marca_agua_container {
    position: relative;
  }
  .marca_agua_overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none;
    z-index: 999;
    overflow: hidden;
  }
  .marca_agua_overlay span {
    position: absolute;
    color: rgba(5, 98, 24, 0.12);
    font-size: 14px;
    font-weight: bold;
    white-space: nowrap;
    transform: rotate(-35deg);
    user-select: none;
    letter-spacing: 1px;
  }
  @media print {
    .marca_agua_overlay span {
      display: none !important;
    }
    .marca_agua_overlay {
      z-index: 0 !important;
      bottom: 0 !important;
      height: auto !important;
      background-image: var(--marca-agua-print) !important;
      background-repeat: no-repeat !important;
      background-size: 100% 100% !important;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }
    .marca_agua_container > :not(.marca_agua_overlay) {
      position: relative;
      z-index: 1;
    }
    .marca_agua_container .panel,
    .marca_agua_container .panel-heading,
    .marca_agua_container .panel-body,
    .marca_agua_container .modal-content,
    .marca_agua_container .modal-body,
    .marca_agua_container .well,
    .marca_agua_container .form-control,
    .marca_agua_container table,
    .marca_agua_container tr,
    .marca_agua_container td,
    .marca_agua_container th {
      background: transparent !important;
    }
    .container:has(.marca_agua_container) {
      width: 1200px !important;
      max-width: none !important;
      zoom: 0.6;
    }
    .marca_agua_container a[href]:after {
      content: none !important;
    }
    .marca_agua_container .table-responsive {
      overflow: visible !important;
    }
  }

  .swal-confidencialidad-interconsulta-backdrop.swal2-backdrop-show {
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    background: rgba(0, 0, 0, 0.4) !important;
  }

  .swal-confidencialidad-hist-interconsulta-backdrop.swal2-backdrop-show {
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    background: rgba(0, 0, 0, 0.4) !important;
  }
</style>
<script>
  (function () {
    if (!window.matchMedia || window.marcaAguaImpresionRegistrada) return;
    window.marcaAguaImpresionRegistrada = true;
    var mq = window.matchMedia('print');
    var ctx = document.createElement('canvas').getContext('2d');
    ctx.font = '14px Arial, sans-serif';
    function svg_marca_agua(W, H, texto) {
      var txt = String(texto).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
      var anchoTexto = ctx.measureText(texto).width + texto.length;
      var textos = '';
      for (var r = 0; r < Math.ceil(H / 100) + 2; r++) {
        for (var c = 0; c < Math.ceil(W / 220) + 2; c++) {
          var x = c * 220 - 40 + anchoTexto / 2;
          var y = r * 100 - 20 + 9;
          textos += '<text x="' + x + '" y="' + y + '" text-anchor="middle" dominant-baseline="middle" transform="rotate(-35 ' + x + ' ' + y + ')">' + txt + '</text>';
        }
      }
      var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '">' +
        '<g font-family="Arial, sans-serif" font-size="14" letter-spacing="1" fill="#e6e6e6">' + textos + '</g></svg>';
      return 'url("data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg) + '")';
    }
    function al_cambiar_impresion(e) {
      if (!e.matches) return;
      var overlays = document.querySelectorAll('.marca_agua_overlay');
      for (var i = 0; i < overlays.length; i++) {
        var span = overlays[i].querySelector('span');
        if (!span || !overlays[i].offsetWidth) continue;
        overlays[i].style.setProperty('--marca-agua-print', svg_marca_agua(overlays[i].offsetWidth, overlays[i].offsetHeight, span.textContent));
      }
    }
    if (mq.addEventListener) mq.addEventListener('change', al_cambiar_impresion);
    else if (mq.addListener) mq.addListener(al_cambiar_impresion);
  })();
</script>
@endsection
@section('content')

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>
<div class="loader"></div>
<div class="container" style="width:90%">
  @include('frontend.receta_vales.modal_procedimientos')
  
  <div class="row">
    <div class="col-md-12">
      <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
        	<?php 
            $disabled_atencion = "";
            if(!$esta_asegurado){
              $disabled_atencion = "disabled='disabled'";
              echo '<div class="col-sm-12">
                  <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
                    El paciente citado, ya no esta asegurado y por consiguiente no tiene derecho de atención.
                  </div>
                </div>';
            }
          ?>
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">NUEVA ATENCION</a></li>
            <li><a href="#tab4default" data-toggle="tab" style="font-size:12px">HIST. ATENCIONES</a></li>
            <li><a href="#tab5default" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE LABORATORIO</a></li>
            <li><a href="#tab6default" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE IMAGENOLOGIA</a></li>
            <li><a href="#tab1007default" data-toggle="tab" style="font-size:12px">HIST. INTERCONSULTA</a></li>
            <li><a href="#tab_RC" data-toggle="tab" style="font-size:12px">HIST. REFERENCIAS / CONTRAREFERENCIAS</a>
            </li>
          </ul>
        </div>
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active" id="tab2default">
              <fieldset>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-sm-12 col-md-12"> 
                      <div class="row">
                        <div class="panel panel-primary filterable">
                          <div class="panel-heading">
                            <h3 class="panel-title">MÓDULO DE INTERCONSULTA</h3>
                          </div>
                          <div class="panel-body">
                            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->
                            <form class="form-horizontal" method="post" action="{{ route('registrar_interconsulta_rpta')}}"
                              id="frmPrestacion" autocomplete="off">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                              <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{ $interconsulta->id_establecimiento }}">
                              <input type="hidden" value="{{ $nivel }}" name="id_nivel" id="id_nivel">
                              <input type="hidden" name="fecha_atencion" id="fecha_atencion" value="<?php echo $fechaServidor?>">
                              <input type="hidden" name="id_interconsulta" id="id_interconsulta" value="<?php echo $id_interconsulta?>">
                              <input type="hidden" value="{{ $interconsulta->nro_doc_ident }}" name="dni_beneficiario" id="dni_beneficiario">
                              <input type="hidden" name="id_prestacion" id="id_prestacion" value="<?php echo $id_prestacion?>">
                              <input type="hidden" name="id_evaluacion" id="id_evaluacion" value="<?php echo $id_evaluacion?>">
                              <input type="hidden" name="id_asegurado" id="id_asegurado" value="<?php echo $interconsulta->asegurado_id?>">
                              <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="<?php echo $interconsulta->id_tipo_atencion?>">
                              <input type="hidden" name="nomsexo" id="nomsexo" value="<?php echo $interconsulta->sexo?>">
                              <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $interconsulta->id_servicio?>">
                              <input id="procedimientosSeleccionados1" type="hidden" name="diagnosticosSeleccionados[]">
                              <input type="hidden" name="has_profesional_rpta_hospitalizacion" id="has_profesional_rpta_hospitalizacion" value="{{$has_profesional_rpta_hospitalizacion}}">
                              <input type="hidden" name="id_medico_rpta" id="id_medico_rpta" value="{{$interconsulta->id_medico_rpta}}">
                              <input type="hidden" name="nrodocafiliado" value="{{ $interconsulta->nro_doc_ident }}">
                              <input type="hidden" name="tipodoc_beneficiario" value="{{ $interconsulta->tipo_doc_ident }}">
                              <input type="hidden" name="nombre_beneficiario" value="{{ $interconsulta->asegurado_nombre }}">
                              <input type="hidden" name="paterno_beneficiario" value="{{ $interconsulta->asegurado_paterno }}">
                              <input type="hidden" name="materno_beneficiario" value="{{ $interconsulta->asegurado_materno }}">
                              <input type="hidden" name="tipo_beneficiario" value="{{ $interconsulta->parentesco }}">
                              <input type="hidden" name="nro_historia" value="{{ $prestacion->nro_historia }}">
                              <input type="hidden" name="grado" value="{{ $interconsulta->grado }}">
                              <input type="hidden" name="fecnacafiliado" value="{{ $interconsulta->fecha_nac }}">
                              <input type="hidden" name="area_id" value="{{ $area_id }}">
                              <input type="hidden" name="cama_id" value="{{ $cama_id }}">
                              <input type="hidden" name="id_hora_toma_muestra" id="id_hora_toma_muestra" />
                              <input type="hidden" id="id_rua_imagenes" name="id_rua_imagenes" value="">
                              
                              <div class="row">
                                <!-- Columna izquierda: Datos del Paciente -->
                                <div class="col-md-6">
                                  <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo"><b>DATOS DEL PACIENTE</b></h3>
                                    </div>
                                    <div class="panel-body">
                                      <table class="table table-condensed table-borderless" style="width: auto; border: 0px solid;">
                                        <tr>
                                            <td style="text-align: left; border: 0px; width: 40%;"><strong>N° DE DOCUMENTO</strong></td>
                                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                                            <td style="border: 0px;"><b> {{ $interconsulta->tipo_doc_ident }} - {{ $interconsulta->nro_doc_ident }}</b></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; border: 0px;"><strong>N° HISTORIA CLÍNICA</strong></td>
                                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                                            <td style="text-transform: uppercase; border: 0px;"><b>{{ $prestacion->nro_historia }}</b></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; border: 0px;"><strong>APELLIDOS Y NOMBRES</strong></td>
                                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                                            <td style="text-transform: uppercase; border: 0px;"><b>{{ $interconsulta->asegurado_paterno }} {{ $interconsulta->asegurado_materno }}, {{ $interconsulta->asegurado_nombre }}</b></td>
                                        </tr>
                                        <?php
                                          $fecha_nac = DateTime::createFromFormat('d/m/Y', $interconsulta->fecha_nac);
                                          $hoy = new DateTime();
                                          $edad_return = "";

                                          if ($fecha_nac !== false) {
                                            $edad = $fecha_nac->diff($hoy);
                                            $edad_return = $edad->y . ' a ' . $edad->m . ' m y ' . $edad->d . ' d';
                                          } else {
                                            $edad_return = "Formato de fecha inválido";
                                          }
                                        ?>
                                        <tr>
                                            <td style="text-align: left; border: 0px;"><strong>FECHA DE NACIMIENTO</strong></td>
                                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                                            <td style=" border: 0px;">{{ $interconsulta->fecha_nac }} - <b>EDAD ({{ $edad_return }})</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php
                                              $sexo = "";
                                              switch ($interconsulta->sexo) {
                                                case "F": $sexo = "FEMENINO"; break;
                                                case "M": $sexo = "MASCULINO"; break;
                                                default: $sexo = $interconsulta->sexo;
                                              }
                                            ?>
                                            <td style="text-align: left; border: 0px;"><strong>SEXO</strong></td>
                                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                                            <td style="text-transform: uppercase; border: 0px;">{{ $sexo }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; border: 0px;"><strong>GRADO</strong></td>
                                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                                            <td style="text-transform: uppercase; border: 0px;">{{ $interconsulta->grado }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; border: 0px;"><strong>TIPO BENEFICIARIO</strong></td>
                                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                                            <td style="text-transform: uppercase; border: 0px;">{{ $interconsulta->parentesco }}</td>
                                        </tr>
                                        <!-- <tr>
                                            <td style="text-align: left; border: 0px;"><strong>CONDICIÓN</strong></td>
                                            <td style="text-align: left; border: 0px;"><strong>:</strong></td>
                                            <td style="text-transform: uppercase; border: 0px;"></td>
                                        </tr> -->
                                      </table>
                                    </div>
                                  </div>
                                </div>
                                
                                <!-- Columna derecha: Datos Generales -->
                                <div class="col-md-6">
                                  <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo"><b>DATOS GENERALES DE LA ATENCIÓN</b></h3>
                                    </div>
                                    <div class="panel-body">
                                      <table class="table table-condensed table-borderless" style="width: auto; border: 0px solid;">
                                        <tr>
                                          <td style="text-align: left; border: 0px; width: 40%;"><strong>COD.SITEDS SUSALUD</strong></td>
                                          <td style="text-align: left; border: 0px;">:</td>                        
                                          <td style="text-align: left; border: 0px;">
                                            <div id="codigo_siteds_container" style="display: none"><input type="text" class="form-control" id="codigo_siteds" name="codigo_siteds" readonly></div>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td style="text-align: left; border: 0px;"><strong>FECHA DE ATENCIÓN</strong></td>
                                          <td style="text-align: left; border: 0px;">:</td>
                                          <td style="text-align: left; border: 0px;">{{ date('d/m/Y H:i:s', strtotime($prestacion->fecha_hora_atencion)) }}</td>
                                        </tr>
                                        <?php

                                          $tipo_atencion_texto_adicional = "";
                                          $servicio_procedencia = "";
                                          $prioridad = "";
                                          $ubicacion = "";

                                          if (intval($interconsulta->id_tipo_atencion) === 2): // Emergencia
                                            
                                            if ($id_evaluacion > 0):
                                              $tipo_atencion_texto_adicional = "- EVALUACIÓN";
                                            endif;//dd($datos_ubicacion);

                                            $prioridad = $datos_ubicacion["emergencia_prioridad"];
                                            $ubicacion = $datos_ubicacion["emergencia_cuarto"] . " / " . $datos_ubicacion["emergencia_cama"];
                                            $servicio_procedencia = "EMERGENCIA";

                                          elseif (intval($interconsulta->id_tipo_atencion) === 3): // Hospitalizacion

                                            if ($id_evaluacion > 0):
                                              $tipo_atencion_texto_adicional = "- EVOLUCIÓN";
                                            endif;

                                            $servicio_procedencia = $datos_ubicacion["hospitalizacion_servicio_encargado"];
                                            $ubicacion = $datos_ubicacion["hospitalizacion_servicio_fisico"] . " / " . $datos_ubicacion["hospitalizacion_cama"];

                                          endif;
                                        ?>
                                        <tr>
                                          <td style="text-align: left; border: 0px;"><strong>TIPO ATENCIÓN</strong></td>
                                          <td style="text-align: left; border: 0px;">:</td>
                                          <td style="text-align: left; border: 0px;"><b>{{ $interconsulta->tipo_atencion }} {{ $tipo_atencion_texto_adicional }}</b></td>
                                        </tr>
                                        @if ($interconsulta->id_tipo_atencion == 2)
                                        <tr>
                                          <td style="text-align: left; border: 0px;"><strong>PRIORIDAD</strong></td>
                                          <td style="text-align: left; border: 0px;">:</td>
                                          <td style="text-align: left; border: 0px;"><b>{{ $prioridad }}</b></td>
                                        </tr>
                                        @endif
                                        <tr>
                                          <td style="text-align: left; border: 0px;"><strong>SERVICIO DE PROCEDENCIA</strong></td>
                                          <td style="text-align: left; border: 0px;">:</td>
                                          <td style="text-align: left; border: 0px;">{{ $servicio_procedencia }}</td>
                                        </tr>
                                        
                                        <tr>
                                          <td style="text-align: left; border: 0px;"><strong>UBICACIÓN DE PACIENTE</strong></td>
                                          <td style="text-align: left; border: 0px;">:</td>
                                          <td style="text-align: left; border: 0px;">{{ $ubicacion }}</td>
                                        </tr>
                                      
                                          
                                        <tr>
                                          <td style="text-align: left; border: 0px;"><strong>PROFESIONAL DE SALUD</strong></td>
                                          <td style="text-align: left; border: 0px;">:</td>
                                          <td style="text-align: left; border: 0px;">{{ $interconsulta->dni }} - {{ $interconsulta->medico_paterno }} {{ $interconsulta->medico_materno }}, {{ $interconsulta->medico_nombre }} <b>({{ $interconsulta->profesion }})</b>
                                          </td>
                                        </tr>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin nuevo diseño -->
                              <div class="panel panel-info panel-bordered panel-sm panel-body">
                                
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        @if ($id_evaluacion == 0)
                                          <b>DIAGNÓSTICOS</b>
                                        @else
                                          @if ($interconsulta->id_tipo_atencion == 2)
                                            <b>DIAGNÓSTICOS DE EVALUACIÓN</b>
                                          @elseif ($interconsulta->id_tipo_atencion == 3)
                                            <b>DIAGNÓSTICOS DE EVOLUCIÓN</b>
                                          @endif
                                        @endif
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <table class="table table-condensed display" id="tblDiagnostico2" cellspacing="0" width="100%" style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                            <th width="20%">Codigo</th>
                                            <th width="60%">Nombre</th>
                                            <th width="20%">Tipo</th>
                                          </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                          @foreach ($diagnosticos as $row)
                                          <tr>
                                            <td>{{ $row->codigo }}</td>
                                            <td>{{ $row->nombre }}</td>
                                            <td>{{ $row->tipo_diagnostico }}</td>
                                          </tr>
                                          @endforeach
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </fieldset>

                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>PROCEDIMIENTOS MÉDICOS</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <table class="table table-condensed display" id="tblProcedimientoM" cellspacing="0" width="100%" style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                            <th width="20%">Codigo</th>
                                            <th width="60%">Nombre</th>
                                            <th width="10%">Cantidad</th>
                                            <th width="10%">Diagnóstico</th>
                                          </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                          @foreach ($procedimientos as $row)
                                          <tr>
                                            <td>{{ $row->codigo_procedimiento }}</td>
                                            <td>{{ $row->nombre_procedimiento }}</td>
                                            <td>{{ $row->cantidad }}</td>
                                            <td>{{ $row->codigo_diagnostico }}</td>
                                          </tr>
                                          @endforeach
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </fieldset>

                                <fieldset style="display:none">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>INFORMACIÓN DEL RESPONSABLE QUE SOLICITA LA INTERCONSULTA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div class="col-md-4">
                                          <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                          <p>DNI</p>
                                        </div>
                                        <div class="col-md-4">
                                          <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                                          <p><?php echo $interconsulta->dni?></p>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-md-4">
                                          <label>APELLIDO PATERNO</label>
                                          <p><?php echo $interconsulta->medico_paterno?></p>
                                        </div>
                                        <div class="col-md-4">
                                          <label>APELLIDO MATERNO</label>
                                          <p><?php echo $interconsulta->medico_materno?></p>
                                        </div>
                                        <div class="col-md-4">
                                          <label>NOMBRES</label>
                                          <p><?php echo $interconsulta->medico_nombre?></p>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-md-2">
                                          <label>N° COLEGIATURA</label>
                                          <p><?php echo $interconsulta->colegiatura_medico?></p>
                                        </div>
                                        <div class="col-md-2">
                                          <label>N° RNE</label>
                                          <p><?php echo $interconsulta->t5_rne?></p>
                                        </div>
                                        <!--<div class="col-md-4">
                                          <label>PROFESION</label>
                                          <p><?php //echo $interconsulta->profesion?></p>
                                        </div>
                                        <div class="col-md-4">
                                          <label>ESPECIALIDAD</label>
                                          <p><?php //echo $interconsulta->especializacion?></p>
                                        </div> -->
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>

                                <fieldset id="fsInterconsulta">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>MOTIVO DE LA INTERCONSULTA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <!-- <br />
                                      <div style="clear:both"></div>
                                      <fieldset> -->
                                        <div class="col-md-12">
                                          <?php
                                            $is_estable = "";
                                            if (is_null($interconsulta->is_estable)):
                                              $is_estable = "SIN INFORMACIÓN";
                                            else:
                                              if (intval($interconsulta->is_estable) == 1):
                                                $is_estable = "ESTABLE";
                                              elseif (intval($interconsulta->is_estable) == 0):
                                                $is_estable = "INESTABLE";
                                              endif;
                                            endif;
                                          ?>
                                          <label>CONDICIÓN DEL PACIENTE: </label> {{ $is_estable }}
                                        </div>
                                        <div class="col-md-12">
                                          <!-- MOSTRAR INTERCONSULTAS---->
                                          <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="form-group"> 
                                              <div class="panel-body">
                                                <div class="col-md-12">
                                                  <!-- <div class="col-md-12"> -->
                                                    {!! nl2br(e($interconsulta->motivo_interconsulta)) !!}
                                                  <!-- </div> -->
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      <!-- </fieldset> -->
                                    </div>
                                  </div>
                                </fieldset>
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>INFORMACIÓN DEL PROFESIONAL QUE RESPONDE INTERCONSULTA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      @if (!$has_profesional_rpta_hospitalizacion)
                                      <div class="alert alert-info" role="alert">
                                        La interconsulta no tiene profesional asignado para la respuesta, por lo tanto se asignará su usuario como responsable.
                                      </div>
                                      @endif
                                      <div class="form-group">
                                        <div class="col-sm-4 col-md-2">
                                          <label for="">N° DE DOCUMENTO</label>
                                          <p>{{ $interconsulta->documento_tr }}</p>
                                        </div>
                                        <div class="col-sm-8 col-md-4">
                                          <label for="">APELLIDOS Y NOMBRES</label>
                                          <p>{{ $interconsulta->medico_paterno_tr }} {{ $interconsulta->medico_materno_tr }}, {{ $interconsulta->medico_nombre_tr }}</p>
                                        </div>
                                        <div class="col-sm-4 col-md-2">
                                          <label for="">ESPECIALIDAD</label>
                                          <p>{{ $interconsulta->especializacion }}</p>
                                        </div>
                                        <div class="col-sm-4 col-md-2">
                                          <label for="">N° CMP</label>
                                          <p>{{ $interconsulta->colegiatura_medico_tr }}</p>
                                        </div>
                                        <div class="col-sm-4 col-md-2">
                                          <label for="">N° RNE</label>
                                          <p>{{ $interconsulta->tr_rne }}</p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>RESPUESTA INTERCONSULTA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="col-md-12">
                                        <textarea class="form-control" rows="5" id="rpta_interconsulta" name="rpta_interconsulta"
                                          placeholder="Respuesta Interconsulta"></textarea>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                                <fieldset>
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
                                            <button type="button" id="addDiagnostico" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar
                                              Diagnostico</button>
                                          </td>
                                        </tr>
                                      </table>
                                      <br />
                                      <table class="display" id="tblDiagnostico" cellspacing="0" width="100%"
                                        style="margin-top:5px;">
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
                                  </div>
                                </fieldset>
                                <fieldset>
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
                                            <button type="button" id="addProcedimiento" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar
                                              Procedimiento</button>
                                          </td>
                                        </tr>
                                      </table>
                                      <br />
                                      <table class="display" id="tblProcedimiento" cellspacing="0" width="100%"
                                        style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                            <th width="45%"></th>
                                            <th width="5%"></th>
                                            <th width="45%"></th>
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

                                <div class="panel panel-info panel-bordered panel-sm panel-body">
                                  <div class="form-group">
                                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>PRESCRIBIR RECETA</label>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="70" type="button" id="addRecetaVale" class="btn btn-lg btn-toggle"
                                        data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                      </button>
                                      <input type="hidden" id="flag_add_receta_vale" name="flag_add_receta_vale" value="0">
                                    </div>
                                  </div>
                                  <fieldset id="fsRecetaVale" style="display:none">
                                    <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center">
                                          <b>PRODUCTOS MEDICOS</b>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>FEC. EXPEDICIÓN (A)</label>
                                            <input tabindex="71" type="text" class="form-control datepicker" value="{{ date('d-m-Y', strtotime($fechaServidor)) }}"
                                              name="fecha_expedicion" id="fecha_expedicion" tabindex="2" data-date-end-date="0d" autocomplete="off" />
                                          </div>
                                          <div class="col-md-5">
                                            <label>FARMACIA</label>
                                            <select tabindex="72" class="form-control" name="id_farmacia_receta" id="id_farmacia_receta" style="width:100%" onchange="getProducto();">
                                              <option value="">-Seleccionar-</option>
                                              @foreach($farmacias as $row)
                                                <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                              @endforeach
                                            </select>
                                          </div>

                                          <?php
                                            $upss_unico = false;
                                            $upss_codigo = "";
                                            if (!empty($upsses)):
                                              if (count($upsses) == 1):
                                                $upss_codigo = $upsses[0]->codigo;
                                                $upss_unico = true;
                                              endif;
                                            else:
                                              $upss_codigo = "000000";
                                              $upss_unico = true;
                                            endif;
                                          ?>
                                          <div class="col-md-3">
                                            <label for="">CÓDIGO UPSS</label>
                                            @if ($upss_unico)
                                              <input type="text" name="codigo_upss" value="{{ $upss_codigo }}" class="form-control" readonly>
                                            @else
                                              <select name="codigo_upss" id="codigo_upss" class="form-control">
                                                <option value="">Seleccione</option>
                                                @foreach ($upsses as $upss)
                                                  <option value="{{ $upss->codigo }}">{{ $upss->descripcion_upss }}</option>
                                                @endforeach
                                              </select>
                                            @endif
                                          </div>

                                        </div>
                                        <br/>
                                        <div class="form-group">
                                          <div class="col-sm-12">
                                            <table>
                                              <tr>
                                                <td>
                                                  <button tabindex="75" type="button" id="addProducto" class="btn btn-primary"
                                                    data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                                                </td>
                                              </tr>
                                            </table>
                                          </div>
                                        </div>

                                        <div style="display: none">
                                          <select class="form-control" id="cmbMedicamentoTemp" tabindex="73" style="width: 500px">
                                            <option value="">Seleccionar Producto</option>
                                          </select>
                                        </div>
                                        <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                        <input type="hidden" value="0" name="presiono_receta" id="presiono_receta">
                                        <div class="table-responsive">
                                          <table id="example" class="table table-condensed display" cellspacing="0">
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
                                </div>

                                <div class="panel panel-info panel-bordered panel-sm panel-body" style="
                                  <?php
                                    /*if(!$is_profesion_medico){
                                      echo "display:none;";
                                    }*/
                                  ?>
                                  ">
                                  <div class="form-group">
                                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>EXAMEN DE LABORATORIO</label>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="90" type="button" id="addExamenLab" class="btn btn-lg btn-toggle"
                                        data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                      </button>
                                      <input type="hidden" id="flag_add_orden_lab" name="flag_add_orden_lab" value="0">
                                    </div>
                                  </div>
                                  <fieldset id="fsExamenLab" style="display:none">
                                    <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center">
                                          <b>EXAMEN LABORATORIO</b>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="col-md-10">
                                          <div class="form-group">
                                            <div class="col-md-6">
                                              <button tabindex="91" type="button" id="addExamenLab2" class="btn btn-primary"
                                                data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
                                            </div>
                                            @if (intval($interconsulta->id_establecimiento) === 76)
                                              <div class="col-md-6" style="text-align: right;">
                                                  <button type="button" id="RUALab2" class="btn btn-primary" data-toggle="modal" data-target="#modalDX">RUA LABORATORIO</button>
                                              </div>
                                            @endif
                                          </div>
                                          <br/>
                                          <div class="table-responsive">
                                            <table class="table table-condensed display" id="tblExamenLab" cellspacing="0">
                                              <thead>
                                                <tr>
                                                  @if (intval($nivel) == 3)
                                                    <th width="65%">Exámen</th>
                                                    <th width="10%">Cantidad</th>
                                                    <th width="20%">Diagnóstico</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th width="5%">Eliminar</th>
                                                  @elseif (intval($nivel) < 3)
                                                    <th width="45%">Exámen</th>
                                                    <th width="20%">Observación</th>
                                                    <th width="10%">Cantidad</th>
                                                    <th width="20%">Diagnóstico</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th width="5%">Eliminar</th>
                                                  @endif
                                                </tr>
                                              </thead>
                                              <tfoot>
                                              </tfoot>
                                              <tbody>
                                              </tbody>
                                            </table>
                                          </div>
                                          @if (intval($nivel) === 3)
                                            @if (intval($interconsulta->id_tipo_atencion) === 3)
                                            <br/>
                                            <table style="width: 100%;">
                                              <tr>
                                                <th width="5%">Observación</th>
                                                <th width="95%"></th>
                                              </tr>
                                              <tr>
                                                <td><input type="text" class="form-control" id="lab_orden_toma_muestra" maxlength="8" readonly /></td>
                                                <td><input type="text" class="form-control" id="lab_orden_observacion" name="lab_orden_observacion" maxlength="255" /></td>
                                              </tr>
                                            </table>
                                            @elseif (intval($interconsulta->id_tipo_atencion) === 2)
                                            <br/>
                                            <div class="form-group">
                                              <div class="col-xs-12">
                                                <label for="">Observación</label>
                                                <input type="text" class="form-control" name="lab_orden_observacion" maxlength="255">
                                              </div>
                                            </div>
                                            @endif
                                          @endif
                                        </div>
                                        @if (intval($interconsulta->id_tipo_atencion) === 3)
                                          <div class="col-md-2">
                                            <div class="panel panel-default">
                                              <div class="panel-body">
                                                <label for="">Hora de toma de muestra:</label>
                                                <div class="radio">
                                                  <label>
                                                    <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__1" value="1">05:00 AM
                                                  </label>
                                                </div>
                                                <div class="radio">
                                                  <label>
                                                    <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__2" value="2">09:00 AM
                                                  </label>
                                                </div>
                                                <div class="radio">
                                                  <label>
                                                    <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__3" value="3">16:00 PM
                                                  </label>
                                                </div>
                                                <div class="radio">
                                                  <label>
                                                    <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__4" value="4">22:00 PM
                                                  </label>
                                                </div>
                                                <div class="radio">
                                                  <label>
                                                    <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__0" value="0">OTRO
                                                  </label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        @endif
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>
                                <div class="panel panel-info panel-bordered panel-sm panel-body">
                                  <div class="form-group">
                                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>EXAMEN IMAGENOLOGIA</label>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="100" type="button" id="addExamenRad" class="btn btn-lg btn-toggle" 
                                        data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                      </button>
                                      <input type="hidden" id="flag_add_orden_rad" name="flag_add_orden_rad" value="0">
                                    </div>
                                  </div>
                                  <fieldset id="fsExamenRad" style="display:none">
                                    <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center">
                                          <b>EXAMEN IMAGENOLOGIA</b>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <table>
                                          <tr>
                                            <td>
                                              <button tabindex="101" type="button" id="addExamenRad2" class="btn btn-primary"
                                                data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
                                            </td>
                                          </tr>
                                        </table>
                                        <br/>
                                        <div class="table-responsive">
                                        <table class="table table-condensed display" id="tblExamenRad" cellspacing="0">
                                          <thead>
                                            <tr>
                                              <th width="35%">Exámen</th>
                                              <th width="3%">Comparativo</th>
                                              <th width="3%">Lado</th>
                                              <th width="20%">Observación</th>
                                              <th width="5%">Cant</th>
                                              <th width="30%">Diagnóstico</th>
                                              <th width="14%">Radiología Portátil</th>
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
                                </div>

                                <div class="panel panel-info panel-bordered panel-sm panel-body">
                                  <div class="form-group">
                                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>REQUIERE INTERVENCIÓN QUIRÚRGICA</label>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      @if($tiene_flag_quirurgico)
                                        <button tabindex="100" type="button" id="addRequiereIntervencionQuirurgica" class="btn btn-lg btn-toggle"
                                          data-toggle="button" aria-pressed="false" autocomplete="off" >
                                          <div class="handle"></div>
                                        </button>
                                      @else
                                        <span data-toggle="popover"
                                            data-trigger="hover"
                                            data-placement="top"
                                            data-html="true"
                                            title="<strong>Servicio Inactivo</strong>"
                                            data-content="El servicio no está activo en la lista de servicios quirúrgicos">
                                          <button id="btn-servicio-inactivo"
                                                  type="button"
                                                  class="btn btn-lg btn-toggle"
                                                  aria-pressed="false"
                                                  autocomplete="off"
                                                  disabled>
                                              <div class="handle"></div>
                                          </button>
                                      </span>
                                      @endif

                                    </div>
                                  </div>
                                  <fieldset id="fsRequiereIntervencionQuirurgica" style="display:none">
                                        <input type="hidden" name="requiere_intervencion_quirurgica" id="requiere_intervencion_quirurgica" value="0">
                                        <input type="hidden" name="id_cqx_intervencion_pq" id="id_cqx_intervencion_pq">
                                        <input type="hidden" name="es_programacion_quirurgica" id="es_programacion_quirurgica">
                                        <div class="alert alert-warning" role="alert" id="alertVigenciaSeguro" style="padding-left: 12px" hidden></div>
                                        <div class="alert alert-warning" role="alert" style="padding-left: 10px" id="divAlert" hidden></div>
                                        

                                        <div class="panel panel-info panel-bordered panel-sm">
                                              <div class="panel-heading">
                                                <div class="panel-title text-center"><b>REGISTRAR INFORMACIÓN QUIRÚRGICA</b></div>
                                              </div>
                                              <div class="panel-body">

                                                <div class="form-group">
                                                  <div class="col-sm-2">
                                                    <label for="">TURNOS QUIRURGICOS</label>
                                                  </div>
                                                  <div class="col-sm-1">
                                                    <input type="number" name="id_prioridad_programacion" id="id_prioridad_programacion" class="form-control" style="width: 105px;"  value="1">
                                                  </div>

                                                  <div class="col-md-2">
                                                      <label for="">CLASIFICACION DECIRUGIA</label>
                                                  </div>
                                                  <div class="col-md-2">
                                                        <select id="id_clasificacion_cirugia" name="id_clasificacion_cirugia" class="form-control" >
                                                            <option value="2">CIRUGIA MENOR</option>
                                                            <option value="3">CIRUGIA MEDIANA</option>
                                                            <option value="1">CIRUGIA MAYOR</option>
                                                        </select>
                                                  </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-2">
                                                        <label>TIPO DE CIRUGÍA</label>
                                                        <select name="id_tipo_cirugia" id="id_tipo_cirugia" class="form-control">
                                                            @foreach ($tipo_cirugias as $row)
                                                            <option value="{{ $row->id }}" @if($row->id==3) selected  @endif>{{ $row->descripcion }}</option>
                                                            @endforeach
                                                          </select>                                      
                                                    </div>
                                                    <div id="electiva" style="display:none;">
                                                      <div id="fecha_programacion" style="display:none">
                                                        <div class="col-md-2">
                                                          <label for="">FECHA DE PROGRAMACIÓN:</label>
                                                          <input type="text" id="fecha_probable_pq" name="fecha_probable_pq" class="form-control"  readonly style="background-color: #ffffff">
                                                        </div>
                                                      </div>

                                                      <div class="col-md-2">
                                                        <label for="">HORA DE INICIO:</label>
                                                        <input type="time" class="form-control input-sm" name="hora_inicio_intervencion" id="hora_inicio_intervencion">
                                                      </div>
                                                      <div class="col-md-2">
                                                        <label for="">HORA DE FIN:</label>
                                                        <input type="time" class="form-control input-sm" name="hora_fin_intervencion" id="hora_fin_intervencion">
                                                      </div>
                                                    </div>       
                                                    <div class="col-md-4">
                                                        <label>TIEMPO DE DURACIÓN</label>
                                                        <input type="hidden" id="duracion_cirugia" name="duracion_cirugia" class="form-control">
                                                        <input type="time" id="duracion_cirugia_calculada" class="form-control" style="width: 105px; display: none;">
                                                        <div id="hora_minuto">
                                                            <div class="row" style="align-items: center;">
                                                              <div class="col-md-3 d-flex align-items-center" style="display: flex; align-items: center;">
                                                                <label for="horas" style="margin-right: 5px; white-space: nowrap;">Hora:</label>
                                                                <input type="number" id="horas" class="form-control" min="0" max="24" placeholder="0" style="width:80px;">
                                                              </div>

                                                              <div class="col-md-3 d-flex align-items-center" style="display: flex; align-items: center;">
                                                                <label for="minutos" style="margin-right: 5px; white-space: nowrap;">Minuto:</label>
                                                                <input type="number" id="minutos" class="form-control" min="0" max="59" placeholder="0" style="width:80px;">
                                                              </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                              </div>

                                              <div class="panel panel-info panel-bordered panel-sm">
                                                <div class="panel-heading">
                                                  <div class="panel-title text-center">
                                                    <b>DIAGNOSTICO QUIRURGICO</b>
                                                  </div>
                                                </div>
                                                <div class="panel-body">
                                                  <table>
                                                    <tr>
                                                      <td>
                                                        <button type="button" id="addDiagnosticoQuirurgico" class="btn btn-primary"
                                                          data-toggle="modal" data-target="#addClassModal">Agregar
                                                          Diagnostico</button>
                                                      </td>
                                                    </tr>
                                                  </table>
                                                  <br />
                                                  <table class="display" id="tblDiagnosticoQuirurgico" cellspacing="0" width="100%" style="margin-top:5px;">
                                                    <thead>
                                                      <tr>
                                                        <th width="50%">Nombre</th>
                                                        <th width="15%">Tipo</th>
                                                      </tr>
                                                    </thead>
                                                    <tfoot>
                                                    </tfoot>
                                                    <tbody>
                                                    
                                                    </tbody>
                                                  </table>

                                                </div>
                                              </div>

                                              <div class="panel panel-info panel-sm">
                                                <div class="panel-heading">
                                                  <div class="panel-title text-center"><b>OPERACION , PROCEDIMIENTO A REALIZAR</b></div>
                                                </div>
                                                <div class="panel-body">
                                                  <!--  -->
                                                  <div class="form-group">
                                                      <div class="col-sm-2">
                                                        <button tabindex="75" type="button" id="addProcedimientoIntervencion" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClassModal">Agregar Procedimiento</button>
                                                      </div>
                                                  </div>
                                                  <div class="form-group" style="display:none">
                                                    <select id="cmbProcedimientoIntervencionTemp"></select>
                                                  </div>
                                                  <div class="form-group">
                                                    <div class="col-sm-12">
                                                      <table id="table_procedimiento_intervencion" class="display" cellspacing="0" width="100%">
                                                        <thead>
                                                          <tr>
                                                            <th>Código y Descripción</th>
                                                            <th style="width:10%;padding-left:30px;">Eliminar</th>
                                                          </tr>
                                                        </thead>
                                                        <tfoot>
                                                        </tfoot>
                                                        <tbody>
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                  </div>
                                                  <!--  -->
                                                </div>
                                              </div>

                                              <div class="panel panel-info panel-sm">
                                                <div class="panel-heading">
                                                  <div class="panel-title text-center"><b>CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN</b></div>
                                                </div>
                                                <div class="panel-body">
                                                  <div class="form-group">
                                                    <div class="col-sm-2">
                                                      <button tabindex="75" type="button" id="addMedicoCirujano" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClassModal">Agregar Cirujano que intervienen en la operación</button>
                                                    </div>
                                                  </div>
                                                  <div class="form-group" style="display:none">
                                                    <select id="cmbProfesionalesTemp"></select>
                                                    <select id="cmbTipoProfesionalTemp">
                                                      <option value="0">--Seleccionar--</option>
                                                      @foreach ($cqx_tipo_profesionales as $tp)
                                                        @if ($tp->flag_profesional == 1)
                                                        <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                                        @endif
                                                      @endforeach
                                                    </select>
                                                  </div>
                                                  <div class="form-group">
                                                    <div class="col-sm-12">
                                                      <table id="table_medico_cirujano" class="display" cellspacing="0" width="100%">
                                                        <thead>
                                                          <tr>
                                                            <th>Apellidos y Nombres</th>
                                                            <th style="width:30%;padding-left:20px;">Cargo</th>
                                                            <th style="width:10%;padding-left:30px;">Eliminar</th>
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
                                              </div>
                                        </div>
                                      </fieldset>
                                  </div>

                                <input tabindex="140" <?php echo $disabled_atencion?> class="btn btn-primary pull-right" value="GUARDAR" name="crea" type="button" form="prestacionescrea" id="btnPrestacion" />&nbsp;&nbsp;
                              </div>  
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade" id="tab4default">
              <ul class="nav nav-tabs" id="tabs">
                <li class="active"><a href="#tab10default" data-toggle="tab" style="font-size:12px">AMBULATORIO</a></li>
                <li><a href="#tab11default" data-toggle="tab" style="font-size:12px">EMERGENCIA</a></li>
                <li><a href="#tab12default" data-toggle="tab" style="font-size:12px">HOSPITALIZACION</a></li>
              </ul>
              <div class="tab-content">
                <br>
                <div class="tab-pane fade in active marca_agua_container" id="tab10default" style="position:relative;">
                  <div class="marca_agua_overlay" id="marca_agua_overlay_hist_ambulatorio"></div>
                  <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title text-center">
                          <b>HISTORIAL DE ATENCIONES MEDICAS - AMBULATORIO</b>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <div id='divHistorial1'></div>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <div class="tab-pane fade marca_agua_container" id="tab11default" style="position:relative;">
                  <div class="marca_agua_overlay" id="marca_agua_overlay_hist_emergencia"></div>
                  <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title text-center">
                          <b>HISTORIAL DE ATENCIONES MEDICAS - EMERGENCIA</b>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <div id='divHistorial2'></div>
                        </div>
                      </div>
                    </div>
                    <div class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title text-center">
                          <b>HISTORIAL DE EVOLUCIONES MEDICAS</b>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-md-12">
                            <div id='divEvaluacione'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <div class="tab-pane fade marca_agua_container" id="tab12default" style="position:relative;">
                  <div class="marca_agua_overlay" id="marca_agua_overlay_hist_hospitalizacion"></div>
                  <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title text-center">
                          <b>HISTORIAL DE ATENCIONES MEDICAS - HOSPITALIZACION</b>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <div id='divHistorial3'></div>
                        </div>
                      </div>
                    </div>
                    <div class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title text-center">
                          <b>HISTORIAL DE EVOLUCIONES MEDICAS</b>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-md-12">
                            <div id='divEvaluacioneHospitalizacion'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="tab5default">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE EXAMENES DE LABORATORIO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divLaboratorio'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade" id="tab6default">
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
            <div class="tab-pane fade marca_agua_container" id="tab1007default" style="position:relative;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_hist_interconsulta"></div>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE INTERCONSULTA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divInterconsultaH'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
                  <div class="tab-pane fade" id="tab_RC">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE REFERENCIAS / CONTRAREFERENCIAS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divRC'></div>
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

<!-- Modal RUA LABORATORIO -->
<div class="modal fade" id="modalDX" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">RUA LABORATORIO</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="contenedorDX" class="contenedor-checks">Cargando...</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="guardarRUALab">Guardar</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL IMAGENOLOGÍA --}}
<div class="modal fade" id="detalleModal" tabindex="-1">
  <div class="modal-dialog" style="width:95%; max-width:1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center" style="font-size:20px;"><strong>DATOS CLÍNICOS RELACIONADOS A LA SOLICITUD DEL EXAMEN DE IMAGENOLOGÍA</strong></h4>
      </div>
      
      <div class="modal-body" style="background:#f5f5f5; font-size:12px;">
        <div class="panel" style="border:1px solid #9fbf9f;">
          <div class="panel-heading text-center" style="background:#cfe3cf;">
            <strong>ANAMNESIS</strong>
          </div>
          <div class="panel-body">
            <div class="row" style="margin-top:10px;">
              <div class="col-md-12">
                <label><b>ANAMNESIS</b>&nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                <textarea id="rad_anamnesis" name="rad_anamnesis" 
                  class="form-control" rows="3" 
                  placeholder="Ingrese anamnesis..."></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="panel" style="border:1px solid #9fbf9f; margin-top:10px;">
          <div class="panel-heading text-center" style="background:#cfe3cf;">
            <strong>EXAMENES AUXILIARES</strong>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <label><b>EXAMEN FÍSICO RELACIONADO A LA SOLICITUD DEL EXAMEN</b>&nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                <textarea id="rad_examen_fisico" name="rad_examen_fisico" 
                  class="form-control" rows="4" 
                  placeholder="Ingrese el examen físico..."></textarea>
              </div>
              <div class="col-md-6">
                <label><b>EXAMENES AUXILIARES</b>&nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                <textarea id="rad_examen_auxiliar" name="rad_examen_auxiliar" 
                  class="form-control" rows="4" 
                  placeholder="Ingrese el examen auxiliar..."></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-dismiss="modal">
          <i class="fa fa-times"></i> <strong>CERRAR</strong>
        </button>
        <button type="button" class="btn btn-success" id="btnConfirmarImagenologia">
          <i class="fa fa-save"></i> <strong>CONFIRMAR</strong>
        </button>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection