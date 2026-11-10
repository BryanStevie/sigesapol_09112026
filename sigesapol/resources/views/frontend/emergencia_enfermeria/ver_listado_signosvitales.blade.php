@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<style>
        .grafico-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Espacio entre gráficos */
            justify-content: center; /* Centrar los gráficos */
            padding: 20px;
        }
        .grafico-item {
            width: 45%; /* Ancho de cada gráfico */
            height: 300px; /* Alto de cada gráfico */
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
#grafico {
            width: 600px;
            height: 400px;
            margin: 0 auto;
        }
</style>

<script>

$(document).ready(function() {

  $("#btnFirmar").on("click", function () {
    Swal.fire({
        title: 'SIGNOS VITALES',
        text: "¿Está seguro que desea firmar los signos vitales?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, firmar!',
        cancelButtonText: 'No, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            firmarPdf();
        }
    })    
  });

  $("#btnDownloadFirmar").on("click", function () {
          const signos_ruta_firma = $("#signos_ruta_firma").val();
          if (!signos_ruta_firma) {
              Swal.fire({
                  icon: 'error',
                  title: "SIGNOS VITALES CONSOLIDADO DE ENFERMERÍA",
                  html: `<p style="font-size: 13px">No pudimos encontrar el archivo firmado. Por favor, verifica el enlace y vuelve a intentarlo.!</p>`,
                  confirmButtonText: "ENTENDIDO",
              });
              return null;
          }
          window.location.href = `/${moment().format("YYYY/MM/DD")}/${signos_ruta_firma}`
      });

  function firmarPdf() {
    Swal.fire({
        html: '<h5>FIRMANDO SIGNOS VITALES</h5>',
        onOpen: () => Swal.showLoading()
    });
    $('#btnFirmar').attr("disabled", true);
    const enfermeria_id = $('#id_enfermeria').val();
    const tipo_atencion = 2;
    const data = {
        enfermeria_id,
        tipo_atencion
    }
    $.ajax({
        url: '/firmar-signos',
        type: "POST",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        data: data,
        dataType: "json",
        success: function (resultado) {
            if (resultado.sw) {
                const csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
                $("#doc_firmar").val(`${resultado.csv}.7z`);
                $("#opt_firma").val(`${resultado.opt}`);
                initInvoker('W');
            }
            Swal.close();
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: "NOTA DE INGRESO DE ENFERMERÍA",
                html: `<p style="font-size: 13px">Ocurrió un error al firmar la nota de enfermería. Por favor, intente nuevamente.!</p>`,
                confirmButtonText: "ENTENDIDO",
            });
        },
        complete: function(xhr, status) {
            $('#btnFirmar').attr("disabled", false);
        }
    });
  }

  window.addEventListener('getArguments', async function(e) {
      await ObtieneArgumentosParaFirmaDesdeLaWeb();
    });

    window.addEventListener('invokerOk', function(e) {
      MiFuncionOkWeb();
    });

    window.addEventListener('invokerCancel', function(e) {
      MiFuncionCancel();
    });

    async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
      const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
      const entidad_firma = $("#entidad_firma").val();
      $.post("{{route('postArgumentsNotaEnfermeria')}}", {
          _token: CSRF_TOKEN,
          type: "W",
          documentName: $('#doc_firmar').val(),
          entidad: entidad_firma,
      }, function(data, status) {
          $("#argumentos").val(data);
          getArgumentsConsultaExternaEnfermeria();

      });
    }

    async function getArgumentsConsultaExternaEnfermeria() {
      const arg = document.getElementById("argumentos").value;
      await dispatchEventClient('sendArguments', arg);
    }

    async function MiFuncionOkWeb() {
      const enfermeria_id = $('#id_enfermeria').val();
      $.ajax({
          type: "put",
          url: `/actualizarEstadoFirmaDeSignosVitales/${enfermeria_id}`,
          data: JSON.stringify({}),
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          },
          success: function (response) {
              Swal.fire({
                  icon: "success",
                  title: "NOTAS DE ENFERMERIA",
                  text: `${response.message || "SE FIRMO EXITOSAMENTE EL PDF"}`,
                  timer: 2000,
                  timerProgressBar: true,
                  showConfirmButton: false
              });
              window.location.reload();
          },
      });
    }

    function MiFuncionCancel() {
      Swal.fire({
        icon: 'warning',
        title: "NOTA DE INGRESO DE ENFERMERÍA",
        html: '<h5>PROCESO DE FIRMA CANCELADO</h5>',
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false
      });
  }

  if ($("#flag_firmar").val() == 0) {
    $("#btnfirmar").hide();
  } else {
    $("#btnfirmar").show();
  }

  
  $(document).on('click', '#btnfirmar', function(e) {
    e.preventDefault();
    firmar();
  })

  $('#fecha_atencion').datepicker();

  $('#btnPrestacion').on("click", function(e) {
    guardarAltaMedica();
  });

  $('#addDiagnostico').on('click', function() {
    AddDiagnostico();
  });

  $('#addDerivarServicio').on('click', function() {
    var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
    $("#divServicio").hide();
    if (addDerivarServicio == "false") {
      $("#divServicio").show();
    }
  });

  $('#addRecetaVale').on('click', function() {
    var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
    $("#fsRecetaVale").hide();
    if (addRecetaVale == "false") {
      $("#fsRecetaVale").show();
    }
  });

  $('#addExamenLab').on('click', function() {
    var addExamenLab = $('#addExamenLab').attr("aria-pressed");
    $("#fsExamenLab").hide();
    if (addExamenLab == "false") {
      $("#fsExamenLab").show();
    }
  });

  $('#addExamenRad').on('click', function() {
    var addExamenRad = $('#addExamenRad').attr("aria-pressed");
    $("#fsExamenRad").hide();
    if (addExamenRad == "false") {
      $("#fsExamenRad").show();
    }
  });

  $('#addAlta').on('click', function() {
    var addAlta = $('#addAlta').attr("aria-pressed");
    $("#divAlta2").hide();
    if (addAlta == "false") {
      $("#divAlta2").show();
    }
  });



  $('#example tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblExamenLab tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  
  $('#tblExamenRad tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $("#id_servicio_derivar").select2({
    width: 'resolve'
  });

  $("#id_farmacia_receta").select2({
    width: 'resolve'
  });

  $("#id_farmacia_receta").val($("#id_farmacia").val()).trigger("change");



});

const datosBarras = <?php echo json_encode($datosBarras); ?>;
console.log(datosBarras)
document.addEventListener('DOMContentLoaded', function() {

            const datosArray = datosBarras.flat(); 
            
            const labels = datosArray.map(d => d.label);
            const startValues = datosArray.map(d => parseFloat(d.start));
            const rangeValues = datosArray.map(d => parseFloat(d.end) - parseFloat(d.start));

            const ctx = document.getElementById('graficoBarras').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Inicio',
                            data: startValues,
                            backgroundColor: 'rgba(255, 255, 255, 0.2)', 
                            borderColor: 'rgba(255, 255, 255, 1)',
                            borderWidth: 1,
                            barThickness: 15,
                            stack: 'stack0'
                        },
                        {
                            label: 'Rango',
                            data: rangeValues,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            barThickness: 15,
                            stack: 'stack0'
                        }
                    ]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Rangos de Productos',
                            font: {
                                size: 16
                            },
                            color: '#333',
                            padding: {
                                top: 10,
                                bottom: 10
                            }
                        },
                        legend: {
                            display: true
                        }
                    },
                    layout: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            left: 0,
                            right: 0
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            stacked: true, 
                            title: {
                                display: true,
                                text: 'Categorías'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: true,
                            title: {
                                display: true,
                                text: 'Valores'
                            }
                        }
                    }
                }
            });
        });


        /////////////////

const datosPuntos3 = <?php echo json_encode($datosPuntos3); ?>;
const datosPuntos4 = <?php echo json_encode($datosPuntos4); ?>;
const datosPuntos5 = <?php echo json_encode($datosPuntos5); ?>;
const datosPuntos6 = <?php echo json_encode($datosPuntos6); ?>;

function crearGrafico(id, datos, titulo, configY = {beginAtZero: true}) {
    const ctx = document.getElementById(id).getContext('2d');

    new Chart(ctx, {
        type: 'line',  // Tipo de gráfico cambiado a 'line'
        data: {
            labels: datos.map(d => d.x),  // Configura las etiquetas del eje x usando `datoturo`
            datasets: [{
                label: titulo,
                data: datos.map(d => ({ x: d.x, y: d.y })),
                backgroundColor: 'lightgreen',
                borderColor: 'lightblue',
                borderWidth: 2,
                pointRadius: 5,  // Tamaño de los puntos
                showLine: true   // Muestra la línea conectando los puntos
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: titulo, 
                    font: {
                        size: 16
                    },
                    color: '#333',
                    padding: {
                        top: 10, 
                        bottom: 10 
                    }
                },
                legend: {
                    display: false 
                }
            },
            layout: {
                padding: {
                    top: 0, 
                    bottom: 0, 
                    left: 0, 
                    right: 0 
                }
            },
            scales: {
                x: {
                    type: 'category',  // Tipo de eje x para datos categóricos
                    beginAtZero: true,
                    ticks: {
                        autoSkip: false // Para mostrar todas las etiquetas del eje x
                    }
                },
                y: configY
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {

    crearGrafico('grafico3', datosPuntos3, 'FRECUENCIA CARDIACA', {beginAtZero: false, min: 20, max: 200, stepSize: 5});
    crearGrafico('grafico4', datosPuntos4, 'FRECUENCIA RESPIRATORIA', {beginAtZero: false, min: 10, max: 65, stepSize: 5});
    crearGrafico('grafico5', datosPuntos5, 'TEMPERATURA', {beginAtZero: false, min: 35, max: 41, stepSize: 5});
    crearGrafico('grafico6', datosPuntos6, 'PRESION ARTERIAL MEDIA', {beginAtZero: false, min: 0, max: 300, stepSize: 20});
});

function guardarAltaMedica() {

  var msg = "";
  
  /*var ind_diag = $('#tblDiagnostico tbody tr').length;
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
  }*/

  var fecha_egreso = $('#fecha_egreso_administrativa').val();
  if(fecha_egreso==""){msg+="Debe ingresar la Fecha de egreso medico<br>";}

  var hora_egreso = $('#hora_egreso_administrativa').val();  
  if(hora_egreso==""){msg+="Debe ingresar la Hora de egreso medico<br>";}


  if (msg != "") {
    bootbox.alert(msg);
    return false;
  } else {   
    //$("#frmAltaMedica").submit();
    var r = confirm("¿Seguro de Concluir el alta medica al Paciente?");
    if (r == true) {
      $('#frmAltaMedica').submit();
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

  item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';

  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico'+ind+'" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
  newRow +='<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico'+ind+'" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">'+item_tipo_diagnostico+'</select></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblDiagnostico tbody').append(newRow);

  var sexo  = $('#nomsexo').val();
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
        url: '../../obtener_diagnostico_genero/' + request.term + '/'+ genero,
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

$(document).ready(function() {
            function toggleDetalleOxi() {
                if ($('input[name="opcion"]:checked').val() === '1') {
                    $('#detalle_oxi').show();
                } else {
                    $('#detalle_oxi').hide();
                }
            }

            // Initial state
            toggleDetalleOxi();

            // Event listeners
            $('input[name="opcion"]').change(toggleDetalleOxi);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const presionArteriax = document.getElementById('presionarteriax');
            const presionArteriaY = document.getElementById('presionarteriay');
            const presionArteriaxMedia = document.getElementById('presionarteriax_media');

            function calcularPresionArterialMedia() {
                const pas = parseFloat(presionArteriax.value);
                const pad = parseFloat(presionArteriaY.value);
                
                if (!isNaN(pas) && !isNaN(pad) && pad <= pas) {
                    const pam = (pas + (pad * 2)) / 3;
                    presionArteriaxMedia.value = pam.toFixed(2);
                } else {
                    presionArteriaxMedia.value = '';
                }
            }

            // Attach event listeners to the inputs
            presionArteriax.addEventListener('input', calcularPresionArterialMedia);
            presionArteriaY.addEventListener('input', calcularPresionArterialMedia);
        });
function soloNumerosMenosCero(e) {

  var key = window.Event ? e.which : e.keyCode;
  //alert(key);
  return ((key >= 48 && key <= 57) || key == 0 || key == 8)
}

function firmar() {

  //var rowindex = $('#Grd2 tbody tr td').length;
  //$('#btnLiquidacionFirmarPdf').attr("disabled",true);
  //if (rowindex > 0) {

  $.ajax({
      url: "<?php echo url()?>/genera_alta_emergencia_csv",
      type: "post",
      //data: p,
      data: $("#frmBloque").serialize(),
      //timeout: 30000,
      dataType: 'json'
    })
    .done(function(resultado) {
      console.log(resultado);
      if (resultado.sw) {
        var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
        $("#doc_firmar").val(`${resultado.csv}.7z`);
        initInvoker('W');
      } else {
        //$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
        //dialogError(function () {});
      }
      $('#btnLiquidacionFirmarPdf').attr("disabled", false);

    })
    .fail(function(jqXHR, textStatus) {
      //$("#dialogError").html('<p>Error de Coneccion, verifique si tiene Internet</p>');
      //dialogError(function () {});
      console.log("Request failed: " + textStatus)
    })
    .always(function() {
      //bootbox.hideAll();
    });

  //} else {

  //dialogWarning("Debe hacer check en minimo una solicitud");
  //}	


}

function firmarsintokens() {
  var batchSignatureProcessPoller = setInterval(function() {
    $.ajax({
      url: "<?php echo url()?>/sinTokens",
      type: "post",
      cache: false,
      dataType: 'json',
      data: $("#frmBloque").serialize(),
      success: function(completed) {
        if (completed) {
          location.href = '/all_atenciones_by_emergencia';
          clearInterval(batchSignatureProcessPoller);
        }
      }
    });
  }, 3000);
}

  $('#addreferencia').on('click', function() {
                var addreferencia = $('#addreferencia').attr("aria-pressed");
                console.log(addreferencia)
                $('#referencia_activo').val(addreferencia);
                $("#divreferencia").hide();
                if (addreferencia == "false") {
                    $('#referencia_activo').val(addreferencia);
                    $("#divreferencia").show();
                    $("#divcontrareferencia").hide();
                    $('#addcontrareferencia').prop('disabled', true);
                } else {
                    $('#addcontrareferencia').prop('disabled', false);
                }
            });

            $( "#guarda" ).click(function() {

               $('#frm').submit();

            });

            function validatefrecrespiratoria(event) {
    const input = event.target;
    let value = parseFloat(input.value);
    let borderColor = '';
    let comment = '';
    if (isNaN(value)) {
        input.style.border = '';
        let commentElement = document.getElementById('frecrespiratoria-comment');
        if (commentElement) {
            commentElement.remove();
        }
        return;
    }
    if (value < 10) { 
        value = 10; 
        input.value = value; 
        borderColor = 'red'; 
        comment = "El valor no puede ser menor a 10 respiraciones por minuto.";
    } else if (value > 30) { 
        value = 35; 
        input.value = value; 
        borderColor = 'red'; 
        comment = "El valor no puede ser mayor a 35 respiraciones por minuto.";
    }
    input.style.border = `2px solid ${borderColor}`;
    let commentElement = document.getElementById('frecrespiratoria-comment');
    if (commentElement) {
        commentElement.remove();
    }
    if (comment) {
        const commentDiv = document.createElement('div');
        commentDiv.id = 'frecrespiratoria-comment';
        commentDiv.style.color = 'darkgrey'; 
        commentDiv.innerText = comment;
        input.parentNode.appendChild(commentDiv);
    }
}

window.addEventListener('DOMContentLoaded', () => {
    /* const frecrespiratoriaInput = document.getElementById('frecrespiratoria');
    if (frecrespiratoriaInput) {
        frecrespiratoriaInput.addEventListener('blur', validatefrecrespiratoria);
    } */
});

/////////
function validatePresionArterialX(event) {
        const input = event.target;
        const errorMessagex = document.getElementById('error-messagex');
        let value = parseFloat(input.value);
        let borderColor = '';
        let comment = '';

        // Verifica si el valor es un número
        if (isNaN(value)) {
            input.style.border = ''; // Limpia el borde
            errorMessagex.style.display = 'none'; // Oculta el mensaje de error
            return;
        }

        // Validación para un rango de presión arterial (en mmHg)
        if (value < 0) { 
            value = 0; 
            input.value = value; 
            borderColor = 'red'; 
            comment = "El valor presion arterial Y  no puede ser menor a 0.";
        } else if (value > 250) { 
            value = 250; 
            input.value = value; 
            borderColor = 'red'; 
            comment = "El valor presion arterial Y no puede ser mayor a 250.";
        } else {
            // Si el valor es válido, restablece el borde y el título
            borderColor = '';
            comment = '';
        }
        
        input.style.border = `2px solid ${borderColor}`;
        errorMessagex.textContent = comment; // Muestra el mensaje de error
        errorMessagex.style.display = comment ? 'block' : 'none'; // Muestra u oculta el mensaje de error
    }

    // Asegúrate de que el código se ejecute después de que el DOM se haya cargado completamente
    window.addEventListener('DOMContentLoaded', () => {
        const presionarteriaxInput = document.getElementById('presionarteriax');
        if (presionarteriaxInput) {
            presionarteriaxInput.addEventListener('blur', validatePresionArterialX);
        }
    });
///////
function validatePresionArterialY(event) {
        const input = event.target;
        const errorMessagey = document.getElementById('error-messagey');
        let value = parseFloat(input.value);
        let borderColor = '';
        let comment = '';

        // Verifica si el valor es un número
        if (isNaN(value)) {
            input.style.border = ''; // Limpia el borde
            errorMessagey.style.display = 'none'; // Oculta el mensaje de error
            return;
        }

        // Validación para un rango de presión arterial (en mmHg)
        if (value < 0) { 
            value = 0; 
            input.value = value; 
            borderColor = 'red'; 
            comment = "El valor presion arterial Y  no puede ser menor a 0.";
        } else if (value > 140) { 
            value = 140; 
            input.value = value; 
            borderColor = 'red'; 
            comment = "El valor presion arterial Y no puede ser mayor a 140.";
        } else {
            // Si el valor es válido, restablece el borde y el título
            borderColor = '';
            comment = '';
        }
        
        input.style.border = `2px solid ${borderColor}`;
        errorMessagey.textContent = comment; // Muestra el mensaje de error
        errorMessagey.style.display = comment ? 'block' : 'none'; // Muestra u oculta el mensaje de error
    }

    // Asegúrate de que el código se ejecute después de que el DOM se haya cargado completamente
    window.addEventListener('DOMContentLoaded', () => {
        const presionarteriayInput = document.getElementById('presionarteriay');
        if (presionarteriayInput) {
            presionarteriayInput.addEventListener('blur', validatePresionArterialY);
        }
    });
///////
function validatefreccard(event) {
    const input = event.target;
    let value = parseFloat(input.value);
    let borderColor = '';
    let comment = '';

    if (isNaN(value)) {
        input.style.border = '';
        let commentElement = document.getElementById('freccardiaca-comment');
        if (commentElement) {
            commentElement.remove();
        }
        return;
    }

    if (value < 50) { // Ajusta el límite mínimo si es necesario
        value = 50; 
        input.value = value; 
        borderColor = 'red'; 
        comment = "El valor no puede ser menor a 50%.";
    } else if (value > 150) { // Ajusta el límite máximo si es necesario
        value = 150; 
        input.value = value; 
        borderColor = 'red'; 
        comment = "El valor no puede ser mayor a 150%.";
    }

    input.style.border = `2px solid ${borderColor}`;
    
    let commentElement = document.getElementById('freccardiaca-comment');
    if (commentElement) {
        commentElement.remove();
    }

    if (comment) {
        const commentDiv = document.createElement('div');
        commentDiv.id = 'freccardiaca-comment';
        commentDiv.style.color = 'darkgrey'; 
        commentDiv.innerText = comment;
        input.parentNode.appendChild(commentDiv);
    }
}

window.addEventListener('DOMContentLoaded', () => {
    /* const freccardiacaInput = document.getElementById('freccardiaca');
    if (freccardiacaInput) {
        freccardiacaInput.addEventListener('blur', validatefreccard);
    } */
});
//////
            function validateSA02(event) {
    const input = event.target;
    let value = parseFloat(input.value);
    let borderColor = '';
    let comment = '';

    if (isNaN(value)) {
        input.style.border = '';
        let commentElement = document.getElementById('sa02-comment');
        if (commentElement) {
            commentElement.remove();
        }
        return;
    }

    if (value < 60) {
        value = 60; 
        input.value = value; 
        borderColor = 'red'; 
        comment = "El valor no puede ser menor a 60%.";
    } else if (value < 85) {
        borderColor = 'red'; 
        comment = "Hipoxia severa";
    } else if (value < 90) {
        borderColor = 'red'; 
        comment = "Hipoxia moderada";
    } else if (value <= 94) {
        borderColor = 'yellow'; 
        comment = "Hipoxia leve";
    } else if (value <= 99) {
        borderColor = 'lightgreen'; 
        comment = "Normal";
    }else if(value >100){
      value = 100; 
      input.value = value; 
      borderColor = 'red'; 
        comment = "El valor no puede ser mayor a 100%.";
    }

    input.style.border = `2px solid ${borderColor}`;
    
    let commentElement = document.getElementById('sa02-comment');
    if (commentElement) {
        commentElement.remove();
    }

    const commentDiv = document.createElement('div');
    commentDiv.id = 'sa02-comment';
    commentDiv.style.color = 'darkgrey'; 
    commentDiv.innerText = comment;
    input.parentNode.appendChild(commentDiv);
}

window.addEventListener('DOMContentLoaded', () => {
    const sa02Input = document.getElementById('sa02');
    if (sa02Input) {
        sa02Input.addEventListener('blur', validateSA02);
    }
});
///////////
            function validateTemperature(event) {
                const input = event.target;
                let value = parseFloat(input.value);
                let borderColor = '';
                let comment = '';
                if (isNaN(value)) {
                    input.style.border = '';
                    let commentElement = document.getElementById('temperature-comment');
                    if (commentElement) {
                        commentElement.remove();
                    }
                    return;
                }
                if (value > 50) {
                    value = 50; 
                    input.value = value; 
                }
                if (value > 50) {
                    borderColor = 'red'; 
                    comment = "El valor no puede ser mayor a 100°C.";
                } else if (value < 30 || value > 45) {
                    borderColor = 'red'; 
                    comment = "Hipertermia";
                } else if (value >= 30 && value <= 35.5) {
                    borderColor = 'lightblue'; 
                    comment = "Hipotermia";
                } else if (value > 35.5 && value <= 37.5) {
                    borderColor = 'lightgreen'; 
                    comment = "Normal";
                } else if (value > 37.5 && value <= 39.5) {
                    borderColor = 'yellow';
                    comment = "Fiebre";
                } else if (value > 39.5 && value <= 41) {
                    borderColor = 'orange'; 
                    comment = "Fiebre Alta";
                } else if (value > 41 && value <= 45) {
                    borderColor = 'red'; 
                    comment = "Hipertermia";
                }
                input.style.border = `2px solid ${borderColor}`;
                let commentElement = document.getElementById('temperature-comment');
                if (commentElement) {
                    commentElement.remove();
                }
                const commentDiv = document.createElement('div');
                commentDiv.id = 'temperature-comment';
                commentDiv.style.color = 'darkgrey'; 
                commentDiv.innerText = comment;
                input.parentNode.appendChild(commentDiv);
            }

window.addEventListener('DOMContentLoaded', () => {
    const temperatureInput = document.getElementById('temperatura');
    if (temperatureInput) {
        temperatureInput.addEventListener('blur', validateTemperature);
    }
});

document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('sa02');
            const errorMessage = document.getElementById('error-message');

            input.addEventListener('input', function() {
                const value = parseFloat(input.value);
                if (isNaN(value) || value < 60) {
                    errorMessage.textContent = 'El valor debe ser mayor o igual a 60.';
                    input.classList.add('invalid');
                } else if (value > 100) {
                    errorMessage.textContent = 'El valor no puede ser mayor a 100.';
                    input.classList.add('invalid');
                    input.value = 100; // Ajusta el valor al máximo permitido
                } else {
                    errorMessage.textContent = '';
                    input.classList.remove('invalid');
                }
            });
        });

function cambiar_derivacion(){
    var derivar= $("#derivar").val();
    if(derivar=='SI'){ //SI
       $.getJSON("/listar_consultorios_ajax/1", function(json){  
        document.getElementById("id_servicio_consulta_externa").innerHTML = "";
        $('#id_servicio_consulta_externa').append($('<option>').text("Seleccione").attr('value', ""));   
        $('#id_servicio_consulta_externa').append($('<option>').text("OTRO").attr('value', "OTRO"));  

        $.each(json, function(i, obj){ 
           //alert(obj[0].nombre);      alert(obj.length);   
           var len= obj.length;
           for(j=0;j<len;j++){
            $('#id_servicio_consulta_externa').append($('<option>').text( obj[j].nombre).attr('value', obj[j].id));  
           }                        
                                 
        });           
      }); 
    }else{
       document.getElementById("id_servicio_consulta_externa").innerHTML = "";
    }

} 



function getSexoGenero(){
  return ($('#nomsexo').val() == 'M') ? 1 : 2;
}

</script>
<style>
 .form-group-spacing {
        margin-bottom: 20px; 
    }

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
    font-family: 'Montserrat', 'Lato', 'Open Sans', 'Helvetica Neue', Helvetica, Calibri, Arial, sans-serif;
    color: #6b7381;
    background: #f2f2f2;
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
            <h3 class="panel-title">LISTA DE SIGNOS VITALES</h3>
                      </div>

          <div class="panel-body">


            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->


            <form id="frm" action="{{route('guarda_signos_vitales_vista')}}" method="post">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="id_asegurado" id="id_asegurado" value="{{$id_asegurado}}">
              <input type="hidden" name="id_enfermeria" id="id_enfermeria" value="{{$id_enfermeria}}">
              <input type="hidden" name="fecha_actual" id="fecha_actual" value="{{$fecha_actual}}">
              <input type="hidden" name="diasTranscurridos" id="diasTranscurridos" value="{{$diasTranscurridos}}">
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>

                  <legend>
                    <b>DATOS DE LA ATENCI&Oacute;N ENFERMERIA</b>
                  </legend>

                  <div>

                    <div class="form-group">

                      <div class="col-md-4">
                        <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                        <p>EMERGENCIA</p>
                      </div>

                      <div class="col-md-4">
                        <label for="dni_beneficiario">FECHA DE INGRESO</label>
                        <p><?php echo $dato_cabecera_jhon[0]->fecha_registro_ingreso?></p>
                      </div>
                  
                      <div class="col-md-4">
                        <label for="nombre_ipres">AREA</label>
                        <p><?php echo $dato_cabecera_jhon[0]->servicio?></p>
                      </div>

                      <div class="col-md-4">
                        <label for="nombre_ipres">PABELLON</label>
                        <p><?php echo $dato_cabecera_jhon[0]->pabellon?></p>
                      </div>

                      <div class="col-md-4">
                        <label for="nombre_ipres">CUARTO</label>
                        <p><?php echo $dato_cabecera_jhon[0]->cuarto?></p>
                      </div>

                      <div class="col-md-4">
                        <label for="nombre_ipres">CAMA</label>
                        <p><?php echo $dato_cabecera_jhon[0]->cama?></p>
                      </div>

                    </div>

                </fieldset>
              </div>

              <fieldset>

                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL PACIENTE</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                 
                    <div class="form-group">
  
                      <div class="col-md-3">
                        <label for="dni_beneficiario">TIPO DE DOCUMENTO</label>
                        <p><?php echo $datos_nota_ingreso[0]->nomtipdoctitular?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $dato_cabecera_jhon[0]->dni?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">SEXO</label>
                        <p><?php   
                            if ($dato_cabecera_jhon[0]->sexo == 'M') {
                                echo 'MASCULINO';
                            } else {
                                echo 'FEMENINO';
                            }
                            ?></p>
                      </div>

                      <div class="col-md-3">
                        <label>N° HISTORIA CLINICA</label>
                        <p><?php echo $dato_cabecera_jhon[0]->historia_clinica?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <label>NOMBRES Y APELLIDOS</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_nombre . ', ' . $dato_cabecera_jhon[0]->asegurado_paterno . ' ' . $dato_cabecera_jhon[0]->asegurado_materno; ?></p>

                      </div>
                      <div class="col-md-3">
                        <label>FECHA DE NACIMIENTO</label>
                          <p><?php echo $dato_cabecera_jhon[0]->asegurado_fecha_nacimiento?></p>
                      </div>

                      <div class="col-md-3">
                        <label>EDAD</label>
                          <p><?php echo $dato_cabecera_jhon[0]->asegurado_edad_anios . ' años, ' . $dato_cabecera_jhon[0]->asegurado_edad_meses . ' meses, ' .$dato_cabecera_jhon[0]->asegurado_edad_dias . ' días. '?></p>
                      </div>
                    </div>

                    <div class="form-group">
                  
                      <div class="col-md-4">
                        <label>PARENTESCO &nbsp;</label>
                          <p><?php echo $dato_cabecera_jhon[0]->asegurado_tipo_beneficiario?></p>
                      </div>
                      
                      <div class="col-md-4">
                        <label>GRADO &nbsp;</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_grado?></p>
                      </div>

                      <div class="col-md-4">
                        <label>CIP &nbsp;</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_carnet?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label>TIPO DE SANGRE &nbsp;</label>
                          <p><?php echo $dato_cabecera_jhon[0]->asegurado_tipo_sangre?></p>
                      </div>
                      
                      <div class="col-md-4">
                        <label>ALERGIA &nbsp;</label>
                        <p><?php  
                            if ($antecedentes->id_alergia == '1') {
                                    echo 'SI';
                                } else {
                                    echo 'NO';
                                }
                          ?></p>
                      </div>
                    @if($antecedentes->id_alergia == '1')
                      <div class="col-md-4">
                        <label>DETALLE DE ALERGIA &nbsp;</label>
                        <p><?php echo $antecedentes->descripcion_alergia?></p>
                      </div>
                      @endif
                    </div>

                  </div>
                </div>
             </fieldset>

             <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b> SIGNOS VITALES EMERGENCIA</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                 
                    <div class="form-group">
  
                      <div class="col-md-3">
                        <label for="dni_beneficiario">PRESION ARTERIAL</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_presion_arterial_x .'/'. $dato_cabecera_jhon[0]->asegurado_presion_arterial_y?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">FREC. CARDIACA</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_frecuencia_cardiaca?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">FREC. RESPIRATORIA (/min)</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_frecuencia_respiratoria?></p>
                      </div>

                      <div class="col-md-3">
                        <label>Fi02 (%)</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_fi02?></p>
                      </div>
                    </div>

                    <div class="form-group">
  
                      <div class="col-md-3">
                        <label for="dni_beneficiario">Sa02 (%) </label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_sa02_porcentaje?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">TEMPERATURA (°C)</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_temperatura?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">TALLA (cm)</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_talla?></p>
                      </div>

                      <div class="col-md-3">
                        <label>PESO (Kg)</label>
                        <p><?php echo $dato_cabecera_jhon[0]->asegurado_peso?></p>
                      </div>
                    </div>
                </div>

              </fieldset>

              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b> SIGNOS VITALES NOTA DE INGRESO</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                 
                    <div class="form-group">
  
                      <div class="col-md-3">
                        <label for="dni_beneficiario">PRESION ARTERIAL</label>
                        <p><?php echo $signoVitales_nota[0]->descripcion .'/'. $signoVitales_nota[1]->descripcion?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">FREC. CARDIACA</label>
                        <p><?php echo $signoVitales_nota[3]->descripcion?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">FREC. RESPIRATORIA (/min)</label>
                        <p><?php echo $signoVitales_nota[5]->descripcion?></p>
                      </div>

                      <div class="col-md-3">
                        <label>Fi02 (%)</label>
                        <p><?php echo $signoVitales_nota[6]->descripcion?></p>
                      </div>
                    </div>

                    <div class="form-group">
  
                      <div class="col-md-3">
                        <label for="dni_beneficiario">Sa02 (%) </label>
                        <p><?php echo $signoVitales_nota[7]->descripcion?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">TEMPERATURA (°C)</label>
                        <p><?php echo $signoVitales_nota[8]->descripcion?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">TALLA (cm)</label>
                        <p><?php echo $signoVitales_nota[2]->descripcion?></p>
                      </div>

                      <div class="col-md-3">
                        <label>PESO (Kg)</label>
                        <p><?php echo $signoVitales_nota[4]->descripcion?></p>
                      </div>
                    </div>
                </div>

              </fieldset>

              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b> AGREGAR SIGNOS VITALES </b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                  <div class="col-md-12 form-group-spacing">    
                      <div class="col-sm-6" style="margin-left:0px;padding-left:0px">
                                        <button type="button" id="addreferencia" class="btn btn-lg btn-toggle"
                                          data-toggle="button" aria-pressed="false" autocomplete="off">
                                          <div class="handle"></div>
                                        </button>
                      </div>
                  </div>
                  <div id="divreferencia" style="display:none">

                        <!-------->
                        <div class="col-md-12 form-group-spacing">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label><center> TIPO ATENCION</center></label></br>
                                        <div class="col-md-12">
                                        <select name="tipo_atencion" id="tipo_atencion" disabled class="form-control">
                                              <option value="1">EMERGENCIA</option>
                                          </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >TURNO</label>
                                        <div class="col-md-12"> 
                                            <select name="turno" id="turno" class="form-control">
                                                <option value="" disabled selected>Selecciona un turno</option>
                                                <option value="1" <?php echo $madrugada == 1 ? 'disabled ' : ''; ?> >MADRUGADA</option>
                                                <option value="2" <?php echo $mañana == 1 ? 'disabled ' : ''; ?> >MAÑANA</option>
                                                <option value="3" <?php echo $tarde == 1 ? 'disabled ' : ''; ?> >TARDE</option>
                                                <option value="4" <?php echo $noche == 1 ? 'disabled ' : ''; ?> >NOCHE</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>DIAS DE ESTANCIA</center></label></br>
                                        <div class="col-md-12"><input value="{{$diasTranscurridos}}" disabled type="number" name="dias_estancia" id="dias_estancia" placeholder="" class="form-control"></div>
                                    </div>
                                </div>
                                <br>
                         </div>
                        
                        <div class="col-md-12 form-group-spacing">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label><center>PRESIÓN ARTERIAL (MM/HG)</center></label></br>                                        
                                        <div class="col-md-6"><input value="" type="number" name="presionarteriax" id="presionarteriax" placeholder="presion arterial x" class="form-control"></div>
                                        <div id="error-messagex" style="color: red; display: none;"></div>
                                        <div class="col-md-6"><input value="" type="number" name="presionarteriay" id="presionarteriay" placeholder="presion arterial y" class="form-control"></div>
                                        <div id="error-messagey" style="color: red; display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >FREC. RESPIRATORIA (EN UN MIN)</label>
                                        <div class="col-md-12">  <input value="" type="number" name="frecrespiratoria" id="frecrespiratoria" placeholder="frec. respiratoria (por min)" class="form-control" tabindex=""></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >FREC. CARDIACA (LPM/MIN)</label>
                                        <div class="col-md-12">  <input value="" type="number" name="freccardiaca" id="freccardiaca" placeholder="frec. cardiaca (lpm/min)" class="form-control" placeholder="" tabindex=""> </div> 
                                    </div>
                                </div>
                                <br>
                         </div>
                        
                         <div class="col-md-12 form-group-spacing">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >TEMPERATURA</label>
                                        <div class="col-md-12"> <input type="number" name="temperatura" id="temperatura" placeholder="temperatura C°" class="form-control" ></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>PRESIÓN ARTERIAL MEDIA</center></label></br>
                                        <div class="col-md-12"><input value="" disabled type="number" name="presionarteriax_media" id="presionarteriax_media"  class="form-control"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><center>SA02</center></label></br>
                                        <div class="col-md-12"><input value="" type="number" name="sa02" id="sa02" placeholder=" SA02" class="form-control"></div>
                                    </div>
                                </div>

                          </div>
                          <div class="col-md-12 form-group-spacing">
                              
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="">OXIGENOTERAPIA</label>
                                          <label>
                                              <input type="radio" name="opcion" value="1" checked> SI
                                          </label>
                                          <label>
                                              <input type="radio" name="opcion" value="2"> NO
                                          </label>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <div class="col-md-12">
                                              <input value="" type="text" name="detalle_oxi" id="detalle_oxi" placeholder="detalle de oxigenoterapia" class="form-control">
                                          </div>
                                      </div>
                                  </div>
                            
                          </div>    

                          <div class="col-md-12 form-group-spacing">
                            @if(($mañana == 1 && $madrugada == 1 && $tarde == 1 && $noche == 1))
                              <input id="guarda" class="btn btn-primary pull-right" value="Guardar Signos Vitales" disabled >
                            @else
                              <input id="guarda" class="btn btn-primary pull-right" value="Guardar Signos Vitales"  >
                            @endif
                          </div>
                     
                    </div>

                </div>

              </fieldset>

              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>LISTADO DE SIGNOS VITALES </b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                 
                    <div class="form-group">
                        <!-------->
                              <div class="col-md-12"  id="global">
                                   <div class="table-responsive">
                                        <table id="tblSuperUsuario" class="table table-bordered ">
                                            <thead>
                                                <tr>
                                                    <th>N</th>
                                                    <th>FECHA/HORA REGISTRO</th>
                                                    <th>PERSONAL DE SALUD RESPONSABLE</th>
                                                    <th>UBICACION</th>
                                                    <th>RESP</th>
                                                    <th>PULSO </th>
                                                    <th>PRESION ARTERIAL</th>
                                                    <th>TEMP</th>
                                                    <th>PESO</th>
                                                    <th>DIURESIS</th>
                                                    <th>DEPOSICIONES</th>
                                                    <th>DRENAJE</th>
                                                    <th>TURNO</th>

                                                    <th>BH POR TURNO</th>
                                                    <th>BH 24 HORAS</th>
                                                    <th>EDITAR/VER</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                          @if($estado == 1)
                                              @foreach( $arreglo_lista as $dato)
                                              <tr>
                                                    <th>{{$dato->numero}}</th>
                                                    <th>{{$dato->fecha}}</th>
                                                    <th>{{$dato->responsable}}</th>
                                                    <th>{{$dato->ubica}}</th>
                                                    <th>{{$dato->respiratorio}}</th>
                                                    <th>{{$dato->cardiaca}}</th>
                                                    <th>{{$dato->presionx}} / {{$dato->presiony}}</th>
                                                    <th>{{$dato->temperatura}}</th>
                                                    <th style="color: #003366;"> {{$dato->peso}}</th>
                                                    
                                                    <th style="color: #003366;">{{$dato->diuresis}}</th>
                                                    <th style="color: #003366;">{{$dato->deposisiones}}</th>
                                                    <th style="color: #003366;">{{$dato->drenajes}}</th>
                                                    <th>{{$dato->turno}}</th>
                                                    <th style="color: #003366;">{{$dato->balance_turno}}</th>
                                                    <th style="color: #003366;">{{$dato->balance_x24}}</th>
                                                    @if($dato->seactualizo == 0)
                                                        <th style="color: #003366;"> 
                                                          <a href="/editar_signovital/<?php echo $dato->id_signo_vital?>" 
                                                          class="btn btn-primary btn-xs" title="editar_formulario"><i class="fa fa-edit"></i></a>
                                                          <br>
                                                          <br>
                                                          <a href="/ver_pdf_signos_vitales_unidad/<?php echo $dato->id_signo_vital?>" 
                                                          class="btn btn-primary btn-xs" title="ver_pdf"><i class="fa fa-eye"></i></a>
                                                        </th>
                                                    @else
                                                        <th style="color: #003366;"> 
                                                          <a href="/ver_edicion_signo_vitales/<?php echo $dato->id_signo_vital?>" 
                                                          class="btn btn-warning btn-xs" title="ver_modificacion"><i class="fa fa-eye"></i></a>
                                                          <br>
                                                          <br>
                                                          <a href="/ver_pdf_signos_vitales_unidad/<?php echo $dato->id_signo_vital?>" 
                                                          class="btn btn-primary btn-xs" title="ver_pdf"><i class="fa fa-eye"></i></a>
                                                        </th>
                                                    @endif
                                                      
                                              </tr>
                                              @endforeach
                                          @else
                                                  <tr>
                                                    <td colspan="11" style="text-align: center; color: #999;">No existen datos</td>
                                                  </tr>
                                          @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                          <!-------->
                    </div>

                </div>
              </fieldset>
              <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>VISTA GRAFICA DE SIGNOS VITALES </b>
                          </div>
                      </div>
                      <div class="panel-body" id="collapseprestacionespaciente">
                        <div class="panel-title text-center">
                            <b>GRAFICA PUNTOS</b>
                        </div>
                        <div class="form-group">
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                          <div class="grafico-container">
                                <!-- Gráfico 3 -->
                                <div class="grafico-item">
                                    <canvas id="grafico3"></canvas>
                                </div>
                                <!-- Gráfico 4 -->
                                <div class="grafico-item">
                                    <canvas id="grafico4"></canvas>
                                </div>
                                <!-- Gráfico 5 -->
                                <div class="grafico-item">
                                    <canvas id="grafico5"></canvas>
                                </div>
                                <!-- Gráfico 6 -->
                                <div class="grafico-item">
                                    <canvas id="grafico6"></canvas>
                                </div>
                            </div>
                        </div>
                        
                     
                      <div>  
                      <div  class="panel-body" id="collapseprestacionespaciente">
                          <div class="panel-title text-center">
                            <b>GRAFICA BARRAS</b>
                          </div>
                          <div class="form-group">
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <canvas id="graficoBarras" width="500" height="300"></canvas>
                          </div>
                      </div>
                  </div>
              </fieldset>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">

                  <div class="panel-body" id="collapseprestacionespaciente">
                 
                    <div class="form-group">
                        <!-------->
                        
                        @if($estado == 1)
                                <div class="col-md-12"  id="global">
                                   <div class="table-responsive">
                                    @if ($status_token == 0)
                                      @if (isset($datos_nota_ingreso[0]->tiene_firma) && $datos_nota_ingreso[0]->tiene_firma == 1)
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                          <button type="button" class="btn btn-primary btn-block"  id="btnDownloadFirmar" ><i class="fa fa-download"></i> DESCARGAR PDF FIRMADO</button>
                                        </div>
                                      @else
                                        <button type="button" class="btn btn-primary pull-right"  id="btnFirmar" ><i class="fa fa-pencil"></i> FIRMAR SIGNOS VITALES</button>
                                      @endif
                                      <a href="/ver_pdf_signos_vitales/{{$id_asegurado}}/{{$id_enfermeria}}" id="listarHojaGrafica" name="listarHojaGrafica" class="btn btn-success">
                                          <i class="fa fa-line-chart" aria-hidden="true"></i> Signos Vitales PDF
                                      </a>
                                    @elseif ($status_token == 1)
                                      <a href="/ver_pdf_signos_vitales/{{$id_asegurado}}/{{$id_enfermeria}}" id="listarHojaGrafica" name="listarHojaGrafica" class="btn btn-success">
                                          <i class="fa fa-line-chart" aria-hidden="true"></i> Signos Vitales PDF
                                      </a>
                                    @endif
                                   </div>
                                </div>
                                @else
                                <div class="col-md-12"  id="global">
                                   <div class="table-responsive">
                                      <a  disabled id="listarHojaGrafica" name="listarHojaGrafica" class="btn btn-success">
                                          <i class="fa fa-line-chart" aria-hidden="true"></i> Signos Vitales PDF
                                      </a>
                                   </div>
                                </div>
                                @endif
                          <!-------->
                    </div>

                </div>

              </fieldset>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<input type="hidden" id="argumentos" value="" />
<input type="hidden" id="doc_firmar" value="" />
<input type="hidden" name="opt_firma" id="opt_firma">
<input type="hidden" name="entidad_firma" id="entidad_firma" value="{{Auth::user()->dni}}">

<div id="addComponent"></div>


@include('frontend.includes.footer')
@endsection