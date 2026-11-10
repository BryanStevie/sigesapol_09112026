@extends('frontend.layouts.master3')
@section('renderjs')
 
@include('frontend.hospitalizacion.enfermeria.funciones_utilidades.calculo_edad.function_edad')  
@include('frontend.hospitalizacion.enfermeria.nota_ingreso.function_nota_ingreso.parametros.listar_parametros')
@include('frontend.hospitalizacion.enfermeria.nota_ingreso.function_nota_ingreso.puntos_grafico.control_de_puntos')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script> 
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<script>
lista_parametros('CARDIOLOGICO_NOT_ENF','cardiologico','combo_multiple','TABLA_PARAMETROS',0);
 

document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('plano');
    var ctx = canvas.getContext('2d');

    // Convierte los datos PHP a JSON y usa json_encode en el HTML
    var datos_puntos = {!! json_encode($puntosenviados) !!};

    console.log(datos_puntos); // Verifica la estructura de datos

    function dibujarPuntos() {
        ctx.clearRect(0, 0, canvas.width, canvas.height); // Limpia el canvas
        
        datos_puntos.forEach(function(punto) {
            // Asegúrate de que los datos tienen las propiedades necesarias
            if (punto.x !== undefined && punto.y !== undefined && punto.color !== undefined) {
                // Establece el color del punto
                ctx.fillStyle = punto.color; // Usa el color del punto
                ctx.strokeStyle = punto.color; // Color del borde del punto
                ctx.lineWidth = 2; // Grosor del borde

                // Convierte las coordenadas `x` e `y` a números
                var x = parseFloat(punto.x);
                var y = parseFloat(punto.y);

                // Dibuja el punto
                ctx.beginPath();
                ctx.arc(x, y, 5, 0, Math.PI * 2); // Dibuja un círculo
                ctx.fill(); // Rellena el círculo con el color
                ctx.stroke(); // Dibuja el borde del círculo

                var textoProceso = '';
                var textoOtros = '';

                if (punto.descripcion_proceso_b) {
                    textoProceso = punto.descripcion_proceso_b; 
                }
                if (punto.descripcion_otros) {
                    textoOtros = punto.descripcion_otros; 
                }

                // Solo dibuja el texto si hay algo que mostrar
                if (textoProceso || textoOtros) {
                    var offsetX = 10; 
                    var offsetY = 5;

                    // Dibuja el texto de `descripcion_proceso_b`
                    if (textoProceso) {
                        ctx.fillStyle = 'black'; 
                        ctx.font = '12px Arial'; 
                        ctx.textAlign = 'left'; 
                        ctx.textBaseline = 'middle';

                        ctx.fillText(textoProceso, x + offsetX, y + offsetY);
                        // Ajusta la posición Y para el siguiente texto
                        y += 15; // Aumenta el valor según el espacio deseado para el salto de línea
                    }

                    // Dibuja el texto de `descripcion_otros` en negrita
                    if (textoOtros) {
                        ctx.fillStyle = 'darkred'; // Color más fuerte para resaltar
                        ctx.font = 'bold 12px Arial'; // Fuente en negrita
                        ctx.textAlign = 'left'; 
                        ctx.textBaseline = 'middle';

                        ctx.fillText(textoOtros, x + offsetX, y + offsetY);
                    }
                }
            }
        });
    }

    dibujarPuntos();
});


  $("#cardiologico").select2({ width: 'resolve'});  
    function calcular_edad_detallada(){
          var fecha_nac=$('#fecha_nacimiento_beneficiario').val();
          if(fecha_nac != null && fecha_nac != undefined && fecha_nac.length>8  ){
              var oldDate = fecha_nac; //20/04/1946 
              var values = oldDate.split("/");
              if(values.length==1){
                  var values = oldDate.split("-");
                  var dia = values[2];
                  var mes = values[1];
                  var ano = values[0];  
              }else{
                  var dia = values[0];
                  var mes = values[1];
                  var ano = values[2];  
              } 
                
              calcularEdadPersona(ano,mes,dia,'label','edad_detallada','edad') 
  
              setTimeout(function() { 
                  var edad = $("#edad").val(); 
                  console.log(edad); 
                  if(edad != null && edad != undefined && edad>0 || edad == '' ){ 
                    if(edad<=16){ 
                      $("#seccion_de_apoderado").show();
                    }else if(edad>=60){ 
                      $("#seccion_de_responsable").show();
                    } 
                  }
              }, 1000); 
          }
    }
      
    $(document).ready(function() {  

      $("#btnFirmar").on("click", function () {
        Swal.fire({
            title: 'NOTA DE ENFERMERIA',
            text: "¿Está seguro que desea firmar la nota de enfermeria?",
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
          const enfermeria_ruta_firma = $("#enfermeria_ruta_firma").val();
          if (!enfermeria_ruta_firma) {
              Swal.fire({
                  icon: 'error',
                  title: "NOTA DE INGRESO DE ENFERMERÍA",
                  html: `<p style="font-size: 13px">No pudimos encontrar el archivo firmado. Por favor, verifica el enlace y vuelve a intentarlo.!</p>`,
                  confirmButtonText: "ENTENDIDO",
              });
              return null;
          }
          window.location.href = `/${moment().format("YYYY/MM/DD")}/${enfermeria_ruta_firma}`
      });

      function firmarPdf() {
        Swal.fire({
            html: '<h5>FIRMANDO NOTA ENFERMERIA</h5>',
            onOpen: () => Swal.showLoading()
        });
        $('#btnFirmar').attr("disabled", true);
        const enfermeria_id = $('#enfermeria_id').val();
        const tipo_atencion = 3;
        const data = {
            enfermeria_id,
            tipo_atencion
        }
        $.ajax({
            url: '/firmar-nota',
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
              $("#argumentos_firma").val(data);
              getArgumentsConsultaExternaEnfermeria();

          });
        }

        async function getArgumentsConsultaExternaEnfermeria() {
          const arg = document.getElementById("argumentos_firma").value;
          await dispatchEventClient('sendArguments', arg);
        }

        async function MiFuncionOkWeb() {
          const enfermeria_id = $('#enfermeria_id').val();
          $.ajax({
              type: "post",
              url: `/api/actualizarEstadoFirmaDeNotaDeEnfermeria/${enfermeria_id}`,
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

      var evaluacion_mallampaty_seleccion= '{{$valoraciones_enfermerias->descripcion_mallampaty}}'; 
      if(evaluacion_mallampaty_seleccion=='*'){ 
        $('#mallampati_1').addClass('img_blanco_negro');
        $('#mallampati_2').addClass('img_blanco_negro'); 
        $('#mallampati_3').addClass('img_blanco_negro'); 
        $('#mallampati_4').addClass('img_blanco_negro'); 
      }else if(evaluacion_mallampaty_seleccion=='1'){ 
        $('#mallampati_1').removeClass('img_blanco_negro');
        $('#mallampati_2').addClass('img_blanco_negro'); 
        $('#mallampati_3').addClass('img_blanco_negro'); 
        $('#mallampati_4').addClass('img_blanco_negro'); 
      }else if(evaluacion_mallampaty_seleccion=='2'){
        $('#mallampati_1').addClass('img_blanco_negro'); 
        $('#mallampati_2').removeClass('img_blanco_negro');
        $('#mallampati_3').addClass('img_blanco_negro'); 
        $('#mallampati_4').addClass('img_blanco_negro');  
      }else if(evaluacion_mallampaty_seleccion=='3'){
        $('#mallampati_1').addClass('img_blanco_negro'); 
        $('#mallampati_2').addClass('img_blanco_negro'); 
        $('#mallampati_3').removeClass('img_blanco_negro');
        $('#mallampati_4').addClass('img_blanco_negro'); 
      }else if(evaluacion_mallampaty_seleccion=='4'){
        $('#mallampati_1').addClass('img_blanco_negro'); 
        $('#mallampati_2').addClass('img_blanco_negro'); 
        $('#mallampati_3').addClass('img_blanco_negro'); 
        $('#mallampati_4').removeClass('img_blanco_negro');
      }


      $("#habilitar_es_gestante").hide(); 
      var sexo='{{$datos_atencion[0]->sexo}}';
      if(sexo == 'F'){
        $("#habilitar_es_gestante").show(); 
      }else{
        $("#habilitar_es_gestante").hide(); 
      } 


      $('#escala_dolor1').removeClass('img_bajar_color'); 
      $('#escala_dolor2').addClass('img_bajar_color'); 
      $('#escala_dolor3').addClass('img_bajar_color'); 
      $('#escala_dolor4').addClass('img_bajar_color');  
      var escalas= '{{$enfermeria_escalas->escala_dolor}}'; 
        if(escalas=='0'){
          $('#escala_dolor1').removeClass('img_bajar_color'); 
          $('#escala_dolor2').addClass('img_bajar_color'); 
          $('#escala_dolor3').addClass('img_bajar_color'); 
          $('#escala_dolor4').addClass('img_bajar_color'); 
          //$("#escala_dolor").attr("src","{{URL::asset('/images/escala_dolor/sin_leve.png')}}");
        }else if(escalas=='1'){
          $('#escala_dolor1').addClass('img_bajar_color'); 
          $('#escala_dolor2').removeClass('img_bajar_color'); 
          $('#escala_dolor3').addClass('img_bajar_color'); 
          $('#escala_dolor4').addClass('img_bajar_color'); 
          //$("#escala_dolor").attr("src","{{URL::asset('/images/escala_dolor/dolor_leve.png')}}");
        }else if(escalas=='2'){
          $('#escala_dolor1').addClass('img_bajar_color'); 
          $('#escala_dolor2').addClass('img_bajar_color'); 
          $('#escala_dolor3').removeClass('img_bajar_color'); 
          $('#escala_dolor4').addClass('img_bajar_color'); 
          //$("#escala_dolor").attr("src","{{URL::asset('/images/escala_dolor/dolor_moderado.png')}}");
        }else if(escalas=='3'){
          $('#escala_dolor1').addClass('img_bajar_color'); 
          $('#escala_dolor2').addClass('img_bajar_color'); 
          $('#escala_dolor3').addClass('img_bajar_color'); 
          $('#escala_dolor4').removeClass('img_bajar_color'); 
          //$("#escala_dolor").attr("src","{{URL::asset('/images/escala_dolor/dolor_severo.png')}}");
        } 
    
      $("#seccion_de_responsable").hide();
      $("#seccion_de_apoderado").hide();
      calcular_edad_detallada();
          
      pintar_puntos_by_hospitalizacion('{{$datos_nota_ingreso[0]->id_tipo_atencion}}', "control_div_puntos", "control_div_puntos_leyenda", "NOTA_ENFERMERIA",'VISTA','{{$id_tipo_atenciones}}'); 
        
  });
  function updateSecondInput(value) {
    document.getElementById('oxigenacion2').value = value;
}

// Función para actualizar el valor del primer input
function updateFirstInput(value) {
    document.getElementById('oxigenacion1').value = value;
}
// este es mi js
//mejorando si es 8 ocultar el 7 si es 7 ocultar el 8 
$(document).ready(function() {
    var tipoBeneficiario = $('#container').data('id-tipo-beneficiario');
    
    if (tipoBeneficiario == 8 || tipoBeneficiario == 12 || tipoBeneficiario == 7) {
        $('#titular').show();
        $('#derechohabiente').hide();
    } else if (tipoBeneficiario == 13) {
        $('#titular').hide();
        $('#derechohabiente').hide();
    } else {
        $('#derechohabiente').show();
        $('#titular').hide();
    }
});
$(document).ready(function() {
        var motivoIngreso = $('#container1').data('id-motivo-ingreso');
        
        if (motivoIngreso == 4) {
            $('#referencia').show();
        } else {
            $('#referencia').hide();
        }
    });
    $(document).ready(function() {
    var motivoIngreso_materna = $('#container2').data('id-salud_materna');
    
    if (motivoIngreso_materna !== null && motivoIngreso_materna != 0) {
        $('#saludmaterna').show();
    } else {
        $('#saludmaterna').hide();
    }
    const id_acompanante = $("#id_acompanante").html();
    $("#acompaniado_por").val(id_acompanante).change();
});

  </script>


<style>
  .form-control:focus {
    border-color: #66afe9;
    outline: 0;
    box-shadow: inset 0 1px 1px white, 0 0 8px white;
  }

  .select2-container {
    font-size: 11px !important;
  }

  .borde-saturacion-hipoxia-grave {
    border-color: red;
    border: 2px red solid;
  }

  .borde-saturacion-hipoxia-moderada {
    border-color: orange;
    border: 2px orange solid;
  }

  .borde-saturacion-hipoxia-leve {
    border-color: yellow;
    border: 2px yellow solid;
  }

  .borde-saturacion-normal {
    border-color: green;
    border: 2px green solid;
  }

  .borde-imc-bajopeso {
    border-color: skyblue;
    border: 2px skyblue solid;
  }

  .borde-imc-normal {
    border-color: green;
    border: 2px green solid;
  }

  .borde-imc-sobrepeso {
    border-color: yellow;
    border: 2px yellow solid;
  }

  .borde-imc-obesidad1 {
    border-color: orange;
    border: 2px orange solid;
  }

  .borde-imc-obesidad2 {
    border-color: orangered;
    border: 2px orangered solid;
  }

  .borde-imc-obesidad3 {
    border-color: red;
    border: 2px red solid;
  }

  .borde-temperatura-hipotermia {
    border-color: blue;
    border: 2px blue solid;
  }

  .borde-temperatura-normal {
    border-color: green;
    border: 2px green solid;
  }

  .borde-temperatura-fiebre {
    border-color: yellow;
    border: 2px yellow solid;
  }

  .borde-temperatura-fiebre-alta {
    border-color: orange;
    border: 2px orange solid;
  }

  .borde-temperatura-hipertermia {
    border-color: red;
    border: 2px red solid;
  }


  .borde-talla {
    border-color: green;
    border: 2px green solid;
  }

  .mensaje-temperatura {
    color: green;
    margin-left: 10px;
  }

  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }

  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    /*width:150px;*/
    /*margin:10px;*/
    text-align: left;
    /*margin-top:20px;*/
    /*background:#5cb85c*/
    background: transparent;
    color: #000000
  }

  .fc-day-grid-event :hover {
    color: #000000
  }

  .fc-content {
    padding: 0px !important;
  }

  .fc-title {
    text-align: left;
    font-size: 10px
  }

  /* .modal-dialog {
    width: 1200px !important;
  } */
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
    /*font-size: 1.7em;*/
    border: 0px;
    margin-left: -17% !important;
    text-align: center;
    background: #3c8dbc;
    color: #FFFFFF;
  }

  // -------

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

  .form-control {
    height: 28px;
    text-align: left !important;
  }

  .img_blanco_negro {
    filter: grayscale(100%);
  }

  .img_bajar_color {
    filter: saturate(0.2);
  }

  .leyenda_grafico {
    list-style-type: none;
    margin: 0;
    padding: 0;
  }

  .leyenda_grafico li {
    margin-bottom: 10px;
    padding-left: 30px;
    position: relative;
  }

  .leyenda_grafico li .icono {
    position: absolute;
    left: 0;
    top: 0;
    height: 20px;
    width: 20px;
  }
  
  /*************Styles de sweetAlert2*************/

  .swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen)>.swal2-modal {
    margin: 15rem auto auto auto !important;
  }

  .swal2-popup {
    width: 50em !important;
  }

  .swal2-popup,
  .swal2-header {
    padding: 0 !important;
  }

  .swal2-header {
    background: #3c763d;
  }

  .swal2-html-container {
    margin: 0 !important;
    padding: 1rem 0 !important;
  }

  .titulo-padding {
    padding: 1rem 0 0 0;
  }

  .titulo-padding p {
    color: white;
    margin: 0;
    font-size: 2.5rem;
  }

  .descripcion-alert {
    font-size: 1.5rem;
    padding: 0 1rem;
  }

  .descripcion-alert {
    margin: 0;
  }

  .descripcion-alert span {
    font-weight: 700;
  }

  .swal2-actions {
    margin: 0 !important;
    padding: 1rem;
    justify-content: space-evenly;
  }

  .swal2-actions button {
    margin: 0 !important;
  }

  .borde-alerta {
    border-color: red !important;
    border: 2px red solid !important;
    outline: 0;
    border-radius: 4px;
    box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important;
  }
</style>


 
@endsection
@section('content')

<div class="loader"></div>
<div class="container" style="width:90%">
  <div id="addComponent"></div>
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <div class="row">
              <div class="col-lg-8 col-md-8 col-sm-12">
                <h3 class="panel-title" style="padding-top: 5px;">NOTA DE INGRESO DE ENFERMERÍA - {{$establecimiento[0]->nombre}}</h3> 
              </div>
              
            </div>   
          </div>
          <div class="panel-body">
            
            <form class="form-horizontal" method="post" action="{{ route('registrar_nota_ingreso_emergencia')}}"
              id="frmNotaIngreso" name="frmNotaIngreso" autocomplete="off">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="enfermeria_id" id="enfermeria_id" value="{{$datos_nota_ingreso[0]->id}}">
              <input type="hidden" name="entidad_firma" id="entidad_firma" value="{{Auth::user()->dni}}">
              <input type="hidden" name="doc_firmar" id="doc_firmar">
              <input type="hidden" name="opt_firma" id="opt_firma">
              <input type="hidden" id="argumentos_firma" value="" />
              <input type="hidden" name="enfermeria_ruta_firma" id="enfermeria_ruta_firma" value="{{$datos_nota_ingreso[0]->ruta_firma}}">
              <input type="hidden" name="fecha_nacimiento_beneficiario" id="fecha_nacimiento_beneficiario"
                class="form-control" readonly="">
              <input type="hidden" name="edad" id="edad" class="form-control text-uppercase" readonly=""
                value="">
              <fieldset>
                <div class="panel with-nav-tabs panel-default"
                  style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                  
                  <div class="panel-body" style="padding-bottom: 0px !important;">
                    <div class="tab-content">
                      <div class="tab-pane fade active in" id="tab0DatosPaciente">
                        <fieldset>
                          
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                              <div class="panel-title text-center">
                                <b>DATOS DE LA ATENCION</b>
                              </div>
                            </div>
                            <div class="panel-body" id="collapseprestacionespaciente">
                              <div class="form-group">
                                <div class="col-md-2">
                                  <label for="tipo_doc_medico">TIPO DE LA ATENCION</label>
                                  <div tabindex="19" readonly="" class="form-control" style="text-align:left;">HOSPITALIZACION
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <label for="tipo_doc_medico">FECHA DE LA ATENCION</label>
                                  <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_atencion[0]->fecha_creacion}}
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_materno">HORA DE LA ATENCION</label>
                                  <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->hora_creacion}}" name="M" id="M" style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                  <label for="medico_nombre">ESPECIALIDAD A CARGO</label>
                                  <span id="servicio_info" hidden>{{isset($datos_atencion[0]->servicio_hospi) ? $datos_atencion[0]->servicio_hospi : ''}}</span>
                                  <span id="edad_info" hidden>{{isset($datos_atencion[0]->edad) ? $datos_atencion[0]->edad : ''}}</span>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{isset($datos_atencion[0]->servicio_hospi) ? $datos_atencion[0]->servicio_hospi : ''}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">CODIGO UPS</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->codigo_ups_2}}" name="M1" id="M1" style="text-align:left">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-2">
                                  <label for="medico_nombre">PABELLON</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{isset($datos_atencion[0]->pabellon) ? $datos_atencion[0]->pabellon : ''}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">PISO</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{isset($datos_atencion[0]->piso_hospi) ? $datos_atencion[0]->piso_hospi : ''}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">CUARTO</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{isset($datos_atencion[0]->cuarto_hospi) ? $datos_atencion[0]->cuarto_hospi : ''}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">CAMA</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{isset($datos_atencion[0]->cama) ? $datos_atencion[0]->cama : ''}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">N° HISTORIA CLINICA</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->nro_historia}}" name="M1" id="M1" style="text-align:left">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-3">
                                  <label for="medico_nombre">NOMBRE DE LA IPRESS</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->nombre_estab}}" name="M1" id="M1" style="text-align:left">
                                </div>
                              </div>
                              
                            </div>
                          </div>
                        </fieldset>
                      </div>

                      <fieldset>
                        <div id="container" data-id-tipo-beneficiario="{{ isset($datos_atencion[0]->id_tipo_beneficiario) ? $datos_atencion[0]->id_tipo_beneficiario : '' }}">
                          <div id="titular" class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>INFORMACIÓN DEL PACIENTE</b>
                            </div>
                          </div>
                          <div class="panel-body" id="collapseprestacionespaciente">
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">DOCUMENTO DE IDENTIDAD</label>
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_atencion[0]->dni_beneficiario}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_atencion[0]->paterno_beneficiario}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="medico_materno">APELLIDO MATERNO</label>
                                <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->materno_beneficiario}}" name="M" id="M" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">NOMBRES</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->nombre_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-3">
                                <label for="medico_nombre">BENEFICIARIO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->tipo_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="medico_nombre">F. DE NACIMIENTO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->fecha_nac_paciente}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">CIP</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->cip_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">GRADO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->grado_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">SITUACION</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->situacion_paciente}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">SEXO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="<?php if($datos_atencion[0]->sexo == 'M'): echo 'MASCULINO';else: echo 'FEMENINO';endif;?>" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">T. SANGRE</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->sangre_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">EDAD</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->edad_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>


                            <div class="form-group">
                              <div class="col-md-2" style="margin-bottom: 10px;">
                                  <label for="tipo_doc_medico">DATOS DEL ACOMPAÑANTE</label>
                              </div>
                          </div>
                          <!-- Línea de separación -->
                          <hr style="border-top: 2px solid rgb(75, 68, 68); margin-top: 0;">
                          <!-- Sección de EDAD -->
                          <div class="form-group">
                              <div class="col-md-2">
                                  <label for="medico_nombre">CONDUCIDO POR</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->conducio_por}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              @if ($datos_atencion[0]->conducio_por === 'SOLO')

                              <div class="col-md-4" style="display: none;">
                                <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_atencion[0]->nro_doc_acompaniante . ' - ' . $datos_atencion[0]->nombre_acompaniante }}" name="M1" id="M1" style="text-align:left">
                            </div>
                            <div class="col-md-2" style="display: none;">
                              <label for="medico_nombre">TELEFONO</label>
                              <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->telefono_acompaniante}}" name="M1" id="M1" style="text-align:left">
                          </div>
                          @else
        <!-- Mostrar DOC IDENTIDAD Y NOMBRES -->
                              <div class="col-md-4">
                                  <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_atencion[0]->nro_doc_acompaniante . ' - ' . $datos_atencion[0]->nombre_acompaniante }}" name="M1" id="M1" style="text-align:left">
                              </div>

                              <!-- Mostrar TELEFONO -->
                              <div class="col-md-2">
                                  <label for="medico_nombre">TELEFONO</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->telefono_acompaniante}}" name="M1" id="M1" style="text-align:left">
                              </div>
                          @endif

                          @if ($datos_atencion[0]->conducio_por === 'SOLO')
                              <!-- Mostrar DESCRIPCION -->
                              {{-- <div class="col-md-10">
                                  <label for="medico_nombre">DESCRIPCION</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->observaciones_benefi}}" name="M1" id="M1" style="text-align:left">
                              </div> --}}
                          @endif
                          </div>
                          
                          
                          </div>
                          </div>
                        </div>
                        <!--DERECHO HABIENTE-->

                       <div id="container" data-id-tipo-beneficiario="{{ isset($datos_atencion[0]->id_tipo_beneficiario) ? $datos_atencion[0]->id_tipo_beneficiario : '' }}">
                        <div id="derechohabiente" class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>INFORMACIÓN DEL PACIENTE</b>
                            </div>
                          </div>
                          <div class="panel-body" id="collapseprestacionespaciente">
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">DOCUMENTO DE IDENTIDAD</label>
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_atencion[0]->dni_beneficiario}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_atencion[0]->paterno_beneficiario}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="medico_materno">APELLIDO MATERNO</label>
                                <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->materno_beneficiario}}" name="M" id="M" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">NOMBRES</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->nombre_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-3">
                                <label for="medico_nombre">BENEFICIARIO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->tipo_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="medico_nombre">F. DE NACIMIENTO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->fecha_nac_paciente}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              
                              <div class="col-md-2">
                                <label for="medico_nombre">SEXO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->sexo}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">EDAD</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->edad_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">T. SANGRE</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->sangre_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-3">
                                <label for="medico_nombre">DIRECCION</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->domicilio}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="col-md-2" style="margin-bottom: 10px;">
                                  <label for="tipo_doc_medico">DATOS DEL TITULAR</label>
                              </div>
                          </div>
                          <!-- Línea de separación -->
                          <hr style="border-top: 2px solid rgb(75, 68, 68); margin-top: 0;">
                          <!-- Sección de EDAD -->
                          <div class="form-group">
                              <div class="col-md-2">
                                  <label for="medico_nombre">DOCUMENTO DE IDENTIDAD</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{$asegurado_titular->nro_doc_ident}}" name="M1" id="M1" style="text-align:left">
                              </div>
        <!-- Mostrar DOC IDENTIDAD Y NOMBRES -->
                                <div class="col-md-4">
                                  <label for="medico_nombre">TITULAR</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{$asegurado_titular->nombre .' , '. $asegurado_titular->paterno.' '. $asegurado_titular->materno}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-1">
                                <label for="medico_nombre">CIP</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="{{$asegurado_titular->cip}}" name="M1" id="M1" style="text-align:left">
                            </div>
                              <div class="col-md-2">
                                  <label for="medico_nombre">GRADO</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{$asegurado_titular->grado}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-1">
                                <label for="medico_nombre">SITUACION</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="{{$asegurado_titular->situacion_paciente}}" name="M1" id="M1" style="text-align:left">
                            </div>
                              <!-- Mostrar TELEFONO -->
                              <!-- Mostrar DESCRIPCION -->
                              <div class="col-md-1">
                                  <label for="medico_nombre">TELEFONO</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{$asegurado_titular->telefono}}" name="M1" id="M1" style="text-align:left">
                              </div>
                          </div>
                          
                          <div class="form-group">
                            <div class="col-md-2" style="margin-bottom: 10px;">
                                <label for="tipo_doc_medico">DATOS DEL ACOMPAÑANTE</label>
                            </div>
                        </div>
                        <!-- Línea de separación -->
                        <hr style="border-top: 2px solid rgb(75, 68, 68); margin-top: 0;">
                        <!-- Sección de EDAD -->
                        <div class="form-group">
                            <div class="col-md-2">
                                <label for="medico_nombre">CONDUCIDO POR</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->conducio_por}}" name="M1" id="M1" style="text-align:left">
                            </div>
                            @if ($datos_atencion[0]->conducio_por === 'SOLO')

                            <div class="col-md-4" style="display: none;">
                              <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                              <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_atencion[0]->nro_doc_acompaniante . ' - ' . $datos_atencion[0]->nombre_acompaniante }}" name="M1" id="M1" style="text-align:left">
                          </div>
                          <div class="col-md-2" style="display: none;">
                            <label for="medico_nombre">TELEFONO</label>
                            <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->telefono_acompaniante}}" name="M1" id="M1" style="text-align:left">
                        </div>
                        @else
      <!-- Mostrar DOC IDENTIDAD Y NOMBRES -->
                            <div class="col-md-4">
                                <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_atencion[0]->nro_doc_acompaniante . ' - ' . $datos_atencion[0]->nombre_acompaniante }}" name="M1" id="M1" style="text-align:left">
                            </div>

                            <!-- Mostrar TELEFONO -->
                            <div class="col-md-2">
                                <label for="medico_nombre">TELEFONO</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->telefono_acompaniante}}" name="M1" id="M1" style="text-align:left">
                            </div>
                        @endif

                        @if ($datos_atencion[0]->conducio_por === 'SOLO')
                            <!-- Mostrar DESCRIPCION -->
                            {{-- <div class="col-md-4">
                                <label for="medico_nombre">DESCRIPCION</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->observaciones_benefi}}" name="M1" id="M1" style="text-align:left">
                            </div> --}}
                        @endif
                        </div>
                          </div>
                          
                          </div>
                        </div>





                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>INFORMACIÓN DEL RESPONSABLE DE ENFERMERIA</b>
                            </div>
                          </div>
                          <div class="panel-body" id="collapseprestacionespaciente">
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">DOCUMENTO DE IDENTIDAD</label>
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_nota_ingreso[0]->dni_medico}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_nota_ingreso[0]->paterno_medico}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="medico_materno">APELLIDO MATERNO</label>
                                <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_nota_ingreso[0]->materno_medico}}" name="M" id="M" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">NOMBRES</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_nota_ingreso[0]->nombre_medico}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">ESPECIALIDAD</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_nota_ingreso[0]->especializacion_medico}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="medico_nombre">PROFESION</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_nota_ingreso[0]->profesion_medico}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">N° COLEGIATURA</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_nota_ingreso[0]->colegiatura_medico}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            
                          </div>
                        </div>
                        {{-- <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>INFORMACIÓN DEL RESPONSABLE DE ENFERMERIA</b>
                            </div>
                          </div>
                          <div class="panel-body" id="collapseprestacionespaciente">
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">DOCUMENTO DE IDENTIDAD</label>
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_atencion[0]->profesional_dni}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_atencion[0]->profesional_paterno}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="medico_materno">APELLIDO MATERNO</label>
                                <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->profesional_materno}}" name="M" id="M" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">NOMBRES</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->profesional_nombre}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">ESPECIALIDAD</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->especialidad_medico}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="medico_nombre">PROFESION</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->profesion_medico}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">N° COLEGIATURA</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->colegiatura}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            
                          </div>
                        </div> --}}
                        {{-- <div id="container2" data-id-salud_materna="{{ $datos_atencion[0]->id_gestacion }}">
                          <div id="saludmaterna" class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>SALUD MATERNA</b>
                            </div>
                          </div>
                          <div class="panel-body" id="collapseprestacionespaciente">
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">ULTIMA MENSTRUACION</label>
                                <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->fech_ult_regla}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">FECHA_PROBABLE DE PARTO</label>
                                <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->fech_prob_parto}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="medico_materno">SEMANAS GESTACION</label>
                                <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->semanas_gestacion}}" name="M" id="M" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">N° CONTROLES PRENATALES</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->num_controles}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              
                            </div>
                          </div>
                          </div>
                        </div> --}}
                        
                        {{-- <div id="container1" data-id-motivo-ingreso="{{ $datos_atencion[0]->id_motivo_ingreso }}">
                          <div id="referencia" class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                  <div class="panel-title text-center">
                                      <b>INFORMACION REFERENCIA</b>
                                  </div>
                              </div>
                              <div class="panel-body" id="collapseprestacionespaciente">
                                  <div class="form-group">
                                      <div class="col-md-2">
                                          <label for="tipo_doc_medico">ESTABLECIMIENTO DE REFERENCIA</label>
                                          <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->esta_referencia}}</div>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="tipo_doc_medico">DOCUMENTO DE LA REFERENCIA</label>
                                          <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->numero_oficio}}</div>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="medico_nombre">FECHA DE LA REFERENCIA</label>
                                          <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->fecha_referencia}}" name="M1" id="M1" style="text-align:left">
                                      </div>
                                      <div class="col-md-2">
                                        <label for="medico_nombre">DIAGNOSTICO DE LA REFERENCIA</label>
                                        <input tabindex="22" readonly type="text" class="form-control" value="" name="M1" id="M1" style="text-align:left">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-md-12">
                                          <label for="medico_nombre">OBSERVACION</label>
                                          <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->observacion_evacuados}}" name="M1" id="M1" style="text-align:left">
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div> --}}
                      

                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>DIAGNOSTICOS</b>
                            </div>
                          </div>
                          <div class="panel-body" id="collapseprestacionespaciente">
                            <div class="form-group">
                              <br>
                              <div class="col-md-12">
                                  <label>DIAGNOSTICOS {{$existenDiagnosticosDeAtencion ? 'DE ATENCION' : 'DE ADMISION'}}</label>
                                  <br>
                                  <div class="table-responsive">
                                      <table id="example" class="table table-striped">
                                          <thead>
                                              <tr>
                                                  <th>CIE10</th>
                                                  <th>DESCRIPCIÓN</th>
                                                  <th style="text-align: center;">TIPO</th>
                                                  <th style="text-align: center;">CLASIFICACION</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @foreach ($diagnosticos as $diagHosp)
                                                @if (isset($diagHosp))
                                                  @if($diagHosp->codigo_diagnostico == null)
                                                  <tr>
                                                      <td></td>
                                                      <td style="text-align: center;"> 
                                                        NO SE ENCONTRO NINGUN DIAGNOSTICOS
                                                      </td>
                                                      <td></td>
                                                  </tr>
                                                  @else
                                                  <tr>
                                                      <td>{{ $diagHosp->codigo_diagnostico }}</td>
                                                      <td>{{ $diagHosp->descripcion_diagnostico }}</td>
                                                      <td style="text-align: center;">{{ $diagHosp->descripcion_tipo_diagnosticos }}</td>
                                                      <td style="text-align: center;">
                                                          @if (isset($diagHosp->id_evaluacion) && $diagHosp->id_evaluacion == 0)
                                                              ATENCION
                                                          @elseif (isset($diagHosp->id_evaluacion) && $diagHosp->id_evaluacion == 1)
                                                            DE ADMISION
                                                          @else
                                                            EVALUACION
                                                          @endif
                                                      </td>
                                                  </tr>
                                                  @endif
                                                @endif
                                              @endforeach
                                          </tbody>
                                      </table>
                                  </div>
                                  <br>
                                  <label>DIAGNOSTICOS DE EVALUACION</label>
                                  <br>
                                  <div class="table-responsive">
                                      <table id="example" class="table table-striped">
                                          <thead>
                                              <tr>
                                                  <th>CIE10</th>
                                                  <th>DESCRIPCIÓN</th>
                                                  <th style="text-align: center;">TIPO</th>
                                                  <th style="text-align: center;">CLASIFICACION</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @foreach ($diagnosticosEvaluacion as $diagHospEv)
                                                @if (isset($diagHospEv))
                                                  @if($diagHospEv->codigo_diagnostico == null)
                                                  <tr>
                                                      <td></td>
                                                      <td style="text-align: center;"> 
                                                        NO SE ENCONTRO NINGUN DIAGNOSTICOS
                                                      </td>
                                                      <td></td>
                                                  </tr>
                                                  @else
                                                  <tr>
                                                      <td>{{ $diagHospEv->codigo_diagnostico }}</td>
                                                      <td>{{ $diagHospEv->descripcion_diagnostico }}</td>
                                                      <td style="text-align: center;">{{ $diagHospEv->descripcion_tipo_diagnosticos }}</td>
                                                      <td style="text-align: center;">
                                                          EVALUACION
                                                      </td>
                                                  </tr>
                                                  @endif
                                                @endif
                                              @endforeach
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          
                         
                            {{-- <div class="form-group"> <br>
                              <div class="col-md-12">
                                <label>DIAGNÓSTICOS DE EVALUCION</label><br> 
                                  <div class="table-responsive">
                                      <table id="example" class="table table-striped">
                                          <thead>
                                              <tr>
                                                  <th>CODIGO</th>
                                                  <th>DESCRIPCIÓN</th>
                                                  <th style="text-left: center;">TIPO</th>
                                                  <th style="text-align: center;">CLASIFICACION</th>


                                              </tr>
                                          </thead>
                                          <tbody>
                                              @foreach ($diagnosticos_evaluacion as $diagHosp)
                                              <tr>
                                                  <td>{{ $diagHosp->codigo_diagnostico}}</td>
                                                  <td>{{ $diagHosp->descripcion_diagnostico}}</td>
                                                  <td style="text-align: center;">{{ $diagHosp->descripcion_tipo_diagnosticos}}
                                                  </td>
                                              </tr>
                                              @endforeach
                                          </tbody>
                                      </table>
                                  </div> 
                              </div>
                            </div> --}}
                            
                          </div>
                        </div>
                      <div class="tab-pane fade" id="tab0DatosPacienteTitular">
                        <fieldset> 
                          <div class="form-group">
                            <div class="col-md-1">
                            </div>
        
                            <div class="col-md-3">
                                <label>TITULAR</label>
                            </div>
        
                            <div class="col-md-1">
                                <label>CIP</label>
                            </div>
        
                            <div class="col-md-3">
                                <label>GRADO </label>
                            </div>
        
                            <div class="col-md-2">
                                <label>SITUACIÓN </label>
                            </div>
                          </div>
                        </fieldset>
                      </div>


                    </div>
                  </div>
                </div>
              </fieldset>


              <!--empieza dato atencion-->
              <div class="panel with-nav-tabs panel-default" style="font-size: 11px;padding-bottom: 0px !important;">
                <div class="panel-heading">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab0default" data-toggle="tab" style="font-size:12px"
                        aria-expanded="true"><b><i class="fa fa-file-text-o" aria-hidden="true"></i>
                          SIGNOS VITALES</b></a></li>
                    <li><a href="#tab1default" data-toggle="tab" style="font-size:12px" aria-expanded="true"><b><i
                            class="fa fa-file-text-o" aria-hidden="true"></i>
                          ANTECEDENTES</b></a></li>
                    <li class=""><a href="#tab2default" data-toggle="tab" style="font-size:12px"
                        aria-expanded="false"><b><i class="fa fa-plus" aria-hidden="true"></i>
                          VALORACIÓN</b></a></li>
                    <li class=""><a href="#tab4default" data-toggle="tab" style="font-size:12px"
                        aria-expanded="false"><b><i class="fa fa-male" aria-hidden="true"></i> PIEL &
                          DISPOSITIVOS</b></a></li>
                    <li class=""><a href="#tab5default" data-toggle="tab" style="font-size:12px"
                        aria-expanded="false"><b><i class="fa fa-bars" aria-hidden="true"></i> ESCALAS</b></a></li>
                    <li class=""><a href="#tab6default" data-toggle="tab" style="font-size:12px"
                        aria-expanded="false"><b><i class="fa fa-book" aria-hidden="true"></i> OBSERVACIONES</b></a>
                    </li>
                    </li>
                  </ul>
                </div>
                <div class="panel-body" style="padding-bottom: 0px !important;">
                  <div class="tab-content">

                    <div class="tab-pane fade" id="tab3default">
                      <fieldset>

                      </fieldset>
                    </div>


                    <div class="tab-pane fade active in" id="tab0default">
                      <fieldset>
                        <div class="form-group">
                          <div class="col-lg-3 col-md-4">
                            <label>PRESIÓN ARTERIAL</label>
                            <div class="row">
                              <div class="col-xs-5"> 
                                <input type="text" class="form-control" value="{{$signos_vitales[0]->descripcion}}" readonly> 
                              </div>
                              <div class="col-xs-1" style="text-align: center;">
                                <label>/</label>
                              </div>
                              <div class="col-xs-5">
                                <input type="text" class="form-control" value="{{$signos_vitales[1]->descripcion}}" readonly>  
                              </div>
                            </div>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>FREC. CARDIACA (/min)</label>
                            <input type="text" class="form-control" value="{{$signos_vitales[3]->descripcion}}" readonly>   
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>FREC. RESPIRATORIA (/min)</label>
                            <input type="text" class="form-control" value="{{$signos_vitales[5]->descripcion}}" readonly>  
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>Fi02 (%)</label>
                            <input type="text" class="form-control" value="{{$signos_vitales[6]->descripcion}}" readonly>   
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>Sa02 (%)</label>
                            <input type="text" class="form-control" value="{{$signos_vitales[7]->descripcion}}" readonly>    
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-lg-3 col-md-4">
                              <div class="row">
                                  <div class="col-xs-8">
                                    <label>TEMPERATURA (°C)</label>
                                    <input type="text" class="form-control" value="{{$signos_vitales[8]->descripcion}}" readonly> 
                                  </div>
                              </div>
                          </div>
 
                          {{-- <div class="col-lg-2 col-md-4">
                            <label>RITMO CARDIACO</label>
                            <input type="text" class="form-control" value="{{$signos_vitales[0]->descripcion}}" readonly>  
                          </div> --}}

                          <div class="col-lg-2 col-md-4">
                            <label>TALLA (cm) &nbsp;</label>
                            <input type="text" class="form-control" value="{{$signos_vitales[2]->descripcion}}" readonly>    
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>PESO (Kg) &nbsp;</label>
                            <input type="text" class="form-control" value="{{$signos_vitales[4]->descripcion}}" readonly>   
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>IMC &nbsp;</label> 
                            <input type="text" class="form-control" value="{{$signos_vitales[9]->descripcion}}" readonly> 
                          </div> 

                          <div class="col-sm-2">
                            <label>PESO IDEAL</label>  
                            <input value="{{$signos_vitales[10]->descripcion}}" type="text" class="form-control" readonly >
                          </div>



                      </fieldset> 
                    </div>
 
  

                    <div class="tab-pane fade" id="tab1default">
                      <fieldset>
                        <div class="form-group">
                            <div class="col-lg-12 ">
                            <br>
                                <div class="col-lg-6">
                                        <div class="col-lg-3">
                                            <label>HIPERTENSION ARTERIAL</label> <br> 
                                            @if($antecedentesenfermeria->id_hipertension == '1')
                                                <input type="radio" name="hta" disabled checked>
                                            @else
                                                <input type="radio" name="hta" disabled >
                                            @endif
                                            <b>
                                                SI
                                            </b>
                                            @if($antecedentesenfermeria->id_hipertension == '0')
                                                <input type="radio" name="hta" disabled checked>
                                            @else
                                                <input type="radio" name="hta" disabled >
                                            @endif
                                            <b>
                                                NO
                                            </b> 
                                        </div>


                                        <div class="col-lg-9">
                                            @if($antecedentesenfermeria->id_hipertension == '1')
                                              <div id="hta_tratamiento" name="hta_tratamiento">
                                                <div class="col-md-12">
                                                  <label>TRATAMIENTO</label>
                                                  <input type="text" class="form-control" value="{{$antecedentesenfermeria->descripcion_hipertension}}" disabled>   
                                                </div>
                                              </div>
                                            @endif
                                        </div>
                                  </div>  
                                  <div class="col-lg-6">
                                          <div class="col-lg-3">
                                                <label>DIABETES MELLITUS</label> <br>
                                              @if($antecedentesenfermeria->id_diabetes_millitus == '1')
                                                  <input type="radio" name="diabetes_mellitus" disabled checked>
                                              @else
                                                  <input type="radio" name="diabetes_mellitus" disabled >
                                              @endif
                                              <b>
                                                  SI
                                              </b>
                                              @if($antecedentesenfermeria->id_diabetes_millitus == '0')
                                                  <input type="radio" name="diabetes_mellitus" disabled checked>
                                              @else
                                                  <input type="radio" name="diabetes_mellitus" disabled >
                                              @endif
                                              <b>
                                                  NO
                                              </b>  
                                          </div>
                                          
                                          <div class="col-lg-9">
                                              @if($antecedentesenfermeria->id_diabetes_millitus == '1')
                                                <div id="hta_tratamiento" name="hta_tratamiento">
                                                  <div class="col-md-12">
                                                    <label>TRATAMIENTO</label>
                                                    <input type="text" class="form-control" value="{{$antecedentesenfermeria->descripcion_diabetes_millitus}}" disabled>   
                                                  </div>
                                                </div>
                                              @endif
                                            </div>
                                    </div>
                                </div>
                          <div class="col-lg-12 ">
                          <br>
                                <div class="col-lg-6">
                                <div class="col-lg-3">
                                            <label style="color: red;">ALERGIA</label> <br>
                                              @if($antecedentesenfermeria->id_alergia == '1')
                                                  <input type="radio" name="alerg" disabled checked>
                                              @else
                                                  <input type="radio" name="alerg" disabled >
                                              @endif
                                              <b>
                                                  SI
                                              </b>
                                              @if($antecedentesenfermeria->id_alergia == '0')
                                                  <input type="radio" name="alerg" disabled checked>
                                              @else
                                                  <input type="radio" name="alerg" disabled >
                                              @endif
                                              <b>
                                                  NO
                                              </b>  
                                          </div>
                                          <div class="col-lg-9">
                                              @if($antecedentesenfermeria->id_alergia == '1')
                                                <div class="col-md-12" id="ram_descripcion" name="ram_descripcion">
                                                    <label style="color: red;">DETALLE (ALERGIA)</label>
                                                    <input type="text" class="form-control" value="{{$antecedentesenfermeria->descripcion_alergia}}" readonly> 
                                                </div>
                                              @endif
                                           </div>
                                  </div>   
                                  <div class="col-lg-6">
                                        <div class="col-lg-3">
                                              <label style="color: red;">HEMODIALISIS</label><br>
                                              @if($antecedentesenfermeria->id_hemodialisis == '1')
                                                  <input type="radio" name="ram" disabled checked>
                                              @else
                                                  <input type="radio" name="ram" disabled >
                                              @endif
                                              <b>
                                                  SI
                                              </b>
                                              @if($antecedentesenfermeria->id_hemodialisis == '0')
                                                  <input type="radio" name="ram" disabled checked>
                                              @else
                                                  <input type="radio" name="ram" disabled >
                                              @endif
                                              <b>
                                                  NO
                                              </b>   
                                         </div>
                                         <div class="col-lg-9">
                                            @if($antecedentesenfermeria->id_hemodialisis == '1')
                                              <div class="col-md-12" id="ram_descripcion" name="ram_descripcion">
                                                  <label style="color: red;">DETALLE (HEMODIALISIS)</label>
                                                  <input type="text" class="form-control" value="{{$antecedentesenfermeria->descripcion_hemodialisis}}" readonly> 
                                              </div>
                                            @endif
                                          </div>
                                      </div>
                                </div>
                                <br>
                        <div class="form-group">
                          <br>
                          <div class="col-md-12">
                          <br>
                              <div class="col-md-4">
                                    <label for="tipo">CARDIOLÓGICO</label>
                                    <input type="text" class="form-control" value="{{ $nombre }}" readonly> 
                                </div>
                                <div class="col-md-4">
                                  <label>OBSERVACIÓN</label> 
                                  <input type="text" class="form-control" value="{{$antecedentesenfermeria->descripcion_observacion}}" readonly> 
                                </div>
                                <div class="col-md-4">
                                    <label>OTROS ANTECEDENTES</label>
                                    <input type="text" class="form-control" value="{{$antecedentesenfermeria->descripcion_otro}}" readonly>  
                                </div>
                              
                              </div>
                            </div>
                          
                      </fieldset>
                    </div>
                    <div class="tab-pane fade" id="tab2default">
                      <fieldset>
                        <div class="form-group">
                          <div class="col-lg-2 col-md-4">
                              <label for="tipo">INGRESA POR</label><br>
                              <input type="text" class="form-control" value="{{$ingresa_por->descrip_larga}}" readonly>  
                          </div>
                          
                          @if($valoraciones_enfermerias->id_ingresa_por == 14)
                            <div class="col-lg-2 col-md-4" id="ingresa_por_de" name="ingresa_por_de">
                              <label>INGRESA DE</label>
                              <input type="text" class="form-control" value="{{$condicion_ingreso->descrip_larga}}" readonly>  
                            </div>
                          @endif

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">CONDICIÓN DE INGRESO</label>
                            <input type="text" class="form-control" value="{{$condicion_ingreso->descrip_larga}}" readonly>  
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">ESTADO DE CONCIENCIA</label>
                            <input type="text" class="form-control" value="{{$estado_conciencia->descrip_larga}}" readonly> 
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">ORIENTADO</label><br>
                            @if($valoraciones_enfermerias->orientado_detalle_tiempo == '1' || $valoraciones_enfermerias->orientado_detalle_espacio == '1' || $valoraciones_enfermerias->orientado_detalle_persona == '1')
                            <input type="radio" checked="true" class="regular-radio" name="orientado"
                            style="cursor: pointer;" id="orientado" value="SI" disabled>
                            <b>
                              SI
                            </b> 
                            <input type="radio" class="regular-radio" name="orientado"
                            style="cursor: pointer;" id="orientado" value="SI" disabled>
                            <b>
                              NO
                            </b>
                            @else
                            <input type="radio" class="regular-radio" name="orientado"
                            style="cursor: pointer;" id="orientado" value="SI" disabled>
                            <b>
                              SI
                            </b> 
                            <input type="radio" checked class="regular-radio" name="orientado"
                            style="cursor: pointer;" id="orientado" value="SI" disabled>
                            <b>
                              NO
                            </b>
                            @endif
                          </div>

                          @if($valoraciones_enfermerias->orientado_detalle_tiempo == '1' || $valoraciones_enfermerias->orientado_detalle_espacio == '1' || $valoraciones_enfermerias->orientado_detalle_persona == '1')
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">DETALLE</label><br> 
                            @if($valoraciones_enfermerias->orientado_detalle_tiempo == '0')
                                <input type="checkbox" style="margin-left: 10px;cursor: pointer;" disabled> 
                            @else
                                <input type="checkbox" style="margin-left: 10px;cursor: pointer;" checked  disabled>
                            @endif
                            <label for="vehicle1" style="cursor: pointer;"> TIEMPO </label>

                            @if($valoraciones_enfermerias->orientado_detalle_espacio == '0')
                                <input type="checkbox" style="margin-left: 10px;cursor: pointer;" disabled> 
                            @else
                                <input type="checkbox" style="margin-left: 10px;cursor: pointer;" checked  disabled>
                            @endif
                            <label for="vehicle1" style="cursor: pointer;"> ESPACIO </label>

                            @if($valoraciones_enfermerias->orientado_detalle_persona == '0')
                                <input type="checkbox" style="margin-left: 10px;cursor: pointer;" disabled> 
                            @else
                                <input type="checkbox" style="margin-left: 10px;cursor: pointer;" checked  disabled>
                            @endif
                            <label for="vehicle1" style="cursor: pointer;"> PERSONA </label> 
                            {{-- @if($estado_conciencia->descrip_larga == 'DESPIERTO')
                            @else
                            @endif --}}
                          </div>
                          @endif


                          <div class="col-lg-2 col-md-4">
                            <label style="color: red;">GRADO DE DEPENDENCIA</label>
                            <input type="text" class="form-control" value="{{$valoraciones_enfermerias->grado_dependencia}}" readonly>  
                          </div>
                        </div>

                        <div class="form-group">

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">FUNCIÓN MOTORA</label>
                            <input type="text" class="form-control" value="{{$funcion_motora->descrip_larga}}" readonly> 
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">RESPIRATORIO (Duración)</label>
                            <input type="text" class="form-control" value="{{$respiratorio->descrip_larga}}" readonly> 
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">SECRECIONES BRONQUIALES</label><br>
                            @if($valoraciones_enfermerias->id_secrecionbronquiales == '1')
                                <input type="radio" name="oxigenoterapia411" disabled checked>
                            @else
                                <input type="radio" name="oxigenoterapia411" disabled >
                            @endif
                            <b>
                                SI
                            </b>
                            @if($valoraciones_enfermerias->id_secrecionbronquiales == '0')
                                <input type="radio" name="oxigenoterapia411" disabled checked>
                            @else
                                <input type="radio" name="oxigenoterapia411" disabled >
                            @endif
                            <b>
                                NO
                            </b>   
                          </div> 

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">OXIGENOTERAPIA</label><br>
                            @if($valoraciones_enfermerias->id_oxigenoterapia == '1')
                                <input type="radio" name="oxigenoterapia" disabled checked>
                            @else
                                <input type="radio" name="oxigenoterapia" disabled >
                            @endif
                            <b>
                                SI
                            </b>
                            @if($valoraciones_enfermerias->id_oxigenoterapia == '0')
                                <input type="radio" name="oxigenoterapia" disabled checked>
                            @else
                                <input type="radio" name="oxigenoterapia" disabled >
                            @endif
                            <b>
                                NO
                            </b>   
                          </div>

                          @if($valoraciones_enfermerias->id_oxigenoterapia == '1')
                            <div id="oxigenoterapia_detalle" name="oxigenoterapia_detalle">
                                <div class="col-lg-2 col-md-4">
                                  <label style="color: red;">DETALLE (OXIGENOTERAPIA)</label>
                                  {{-- <input type="text" class="form-control" value="{{$nombres_oxigeno}}" readonly>  --}} 
                                  {{-- <input type="text" class="form-control" value="{{$detalle_oxigeno->descrip_larga}}" readonly>  --}} 
                                  @if($nombres_oxigeno!=null)
                                  <div class="" style="border: 1px solid #ccc; border-radius: 4px; background-color: #eeeeee; padding: 5px 10px;">
                                    @foreach ($nombres_oxigeno as $nombre)
                                        <span>{{ "- " . $nombre }}</span><br>
                                    @endforeach
                                  </div>
                                  @endif
                                </div>

                                <div class="col-lg-2 col-md-4">
                                  <label style="color: red;">Fi02 (%)</label>  
                                  <input type="text" class="form-control" value="{{$valoraciones_enfermerias->oxigenoterapia_fi02}}" readonly>   
                                </div>
                            </div> 
                           @endif
                        </div>

                        <div class="form-group">
                          <div class="col-lg-2 col-md-2">
                            <label for="tipo">MICCIÓN ESPONTÁNEA</label><br>
                            @if($valoraciones_enfermerias->id_miccion_espontanea == '1')
                                <input type="radio" name="miccion_esp" disabled checked>
                            @else
                                <input type="radio" name="miccion_esp" disabled >
                            @endif
                            <b>
                                SI
                            </b>
                            @if($valoraciones_enfermerias->id_miccion_espontanea == '0')
                                <input type="radio" name="miccion_esp" disabled checked>
                            @else
                                <input type="radio" name="miccion_esp" disabled >
                            @endif
                            <b>
                                NO
                            </b>   
                          </div>

                          @if($valoraciones_enfermerias->id_miccion_espontanea == '2')
                            <div id="miccion_espontanea_detalle" name="miccion_espontanea_detalle">
                              <div class="col-lg-2 col-md-5">
                                <label for="tipo">TRATAMIENTO RENAL</label>
                                <input type="text" class="form-control" value="{{$valoraciones_enfermerias->id_miccion_espontanea}}" readonly>   
                              </div>

                              <div class="col-lg-2 col-md-5">
                                <label for="tipo">HD</label>
                                <input type="text" class="form-control" value="{{$valoraciones_enfermerias->id_miccion_espontanea}}" readonly>    
                              </div>

                              <div class="col-lg-2 col-md-5">
                                <label for="tipo">FAV</label>
                                <input type="text" class="form-control" value="{{$valoraciones_enfermerias->id_miccion_espontanea}}" readonly>    
                              </div>
                            </div>
                          @endif


                          {{-- <div class="col-lg-2 col-md-3">
                            <input type="hidden" value="1,1,1" id="puntaje_calculado_glasgow_memoria"
                              name="puntaje_calculado_glasgow_memoria">
                            <label for="tipo">ESCALA DE GLASGOW</label>
                            <input type="text" class="form-control" value="{{$valoraciones_enfermerias->punto_calculado_glasgow}}" readonly>   
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">VALORACIÓN DE GLASGOW</label>
                            <input type="text" class="form-control" value="{{$valoraciones_enfermerias->descripcion_calculado_glasgow}}" readonly>  
                          </div> --}}
                          <div class="col-lg-2 col-md-3">
                            <input type="hidden" value="1,1,1" id="puntaje_calculado_glasgow_memoria"
                              name="puntaje_calculado_glasgow_memoria">
                            <label for="tipo">ESCALA DE GLASGOW</label>
                            <input type="text" class="form-control" value="{{$valoraciones_enfermerias->punto_calculado_glasgow == 0 ? 'No registrado' : $valoraciones_enfermerias->punto_calculado_glasgow}}" readonly>   
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">VALORACIÓN DE GLASGOW</label>
                            <input type="text" class="form-control" value="{{$valoraciones_enfermerias->punto_calculado_glasgow == 0 ? 'No registrado' : $valoraciones_enfermerias->descripcion_calculado_glasgow}}" readonly>  
                          </div>
                          <div class="col-lg-2 col-md-3">
                            <input type="hidden" value="" id="puntaje_calculado_rass_memoria"
                              name="puntaje_calculado_rass_memoria">
                            <label for="tipo">ESCALA DE RASS</label>
                            <input type="text" id="puntaje_calculado_rass" name="puntaje_calculado_rass"
                            class="form-control" style="height: 30px !important;"
                            readonly value="{{$valoraciones_enfermerias->punto_calculado_rass}}">
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">VALORACIÓN DE RASS</label>
                            <input type="text" class="form-control desc_rass"
                              id="desc_rass" name="desc_rass"
                              style="height: 30px !important;" readonly value="{{$valoraciones_enfermerias->descripcion_calculado_rass}}">
                          </div>

                          <div class="col-lg-2 col-md-2">
                            <label for="tipo">HEMOPTISIS</label><br>
                            @if($valoraciones_enfermerias->id_hemoptisis == '1')
                                <input type="radio" name="miccion_espontanea" disabled checked>
                            @else
                                <input type="radio" name="miccion_espontanea" disabled >
                            @endif
                            <b>
                                SI
                            </b>
                            @if($valoraciones_enfermerias->id_hemoptisis == '0')
                                <input type="radio" name="miccion_espontanea" disabled checked>
                            @else
                                <input type="radio" name="miccion_espontanea" disabled >
                            @endif
                            <b>
                                NO
                            </b>   
                          </div>
                        </div>

                        <div class="form-group">
                          <hr>
                          <div class="col-lg-3 col-md-4">
                            <label>ACOMPAÑADO POR</label>
                            {{-- @if($valoraciones_enfermerias->id_acompanante == '1')
                                <input type="radio" name="miccion_espontanea1" disabled checked>
                            @else
                                <input type="radio" name="miccion_espontanea1" disabled >
                            @endif
                            <b>
                                SOLO
                            </b>
                            @if($valoraciones_enfermerias->id_acompanante == '0')
                                <input type="radio" name="miccion_espontanea1" disabled checked>
                            @else
                                <input type="radio" name="miccion_espontanea1" disabled >
                            @endif
                            <b>
                                FAMILIAR
                            </b>  --}}
                            <span id="id_acompanante" hidden>{{$valoraciones_enfermerias->id_acompanante}}</span>
                            <select disabled class="form-control" name="acompaniado_por" id="acompaniado_por">
                              <option value="0">SOLO</option>
                              <option value="1">FAMILIAR</option>
                              <option value="2">PERSONAL DE SALUD</option>
                              <option value="3">POLICIA</option>
                              <option value="4">OTROS</option>
                            </select>
                          </div>

                          <div class="col-md-4" id="acompaniado_por" name="acompaniado_por">
                              @if($valoraciones_enfermerias->id_acompanante != '0')
                                <label>ACOMPAÑADO POR:</label>
                                <input type="text" class="form-control" value="{{$valoraciones_enfermerias->descrip_acompanante}}" readonly>  
                              @endif
                          </div>
                          <div class="col-md-8">
                           
                          </div>
                            <div class="col-md-12">
                            <div class="form-group">
                              <hr>
                              <div class="col-md-3">
                                <label for="tipo">PIEL (COLOR)</label> <br> 
                                @if($valoraciones_enfermerias->id_piel=='')
                                  <input type="text" class="form-control" value="NO SE SELECCIONO UNA OPCION" readonly> 
                                @else
                                  <input type="text" class="form-control" value="{{$piel->descrip_larga}}" readonly>  
                                @endif 
                              </div>
                              <div class="col-lg-2 col-md-2">
                                <label for="tipo">EDEMA</label> <br>
                                    @if($valoraciones_enfermerias->id_edema == '1')
                                        <input type="radio" name="edema" disabled checked>
                                    @else
                                        <input type="radio" name="edema" disabled >
                                    @endif
                                    <b>
                                        SI
                                    </b>
                                    @if($valoraciones_enfermerias->id_edema == '0')
                                        <input type="radio" name="edema" disabled checked>
                                    @else
                                        <input type="radio" name="edema" disabled >
                                    @endif
                                    <b>
                                        NO
                                    </b>   
                              </div>
                              <div class="col-md-3">
                                <label for="tipo">INTEGRIDAD</label>
                                @if($valoraciones_enfermerias->id_integridad=='')
                                <input type="text" class="form-control" value="NO SE SELECCIONO UNA OPCION" readonly> 
                                @else
                                  <input type="text" class="form-control" value="{{$integridad->descrip_larga}}" readonly>  
                                @endif  
                              </div>

                              <div class="col-md-4">
                                <label for="tipo">ABDOMEN</label>
                                @if($valoraciones_enfermerias->id_abdomen=='')
                                <input type="text" class="form-control" value="NO SE SELECCIONO UNA OPCION" readonly> 
                                @else
                                  <input type="text" class="form-control" value="{{$abdomen->descrip_larga}}" readonly> 
                                @endif  
                              </div>
                            </div>
                            <div class="form-group">

                              <div class="col-lg-2 col-md-4">
                                <label for="tipo">EVALUACION MALLAMPATY</label> <br>
                                @if($valoraciones_enfermerias->id_mallampaty == '1')
                                    <input type="radio" name="evaluacion_mallampaty" disabled checked>
                                @else
                                    <input type="radio" name="evaluacion_mallampaty" disabled >
                                @endif
                                <b>
                                    SI
                                </b>
                                @if($valoraciones_enfermerias->id_mallampaty == '0')
                                    <input type="radio" name="evaluacion_mallampaty" disabled checked>
                                @else
                                    <input type="radio" name="evaluacion_mallampaty" disabled >
                                @endif
                                <b>
                                    NO
                                </b>   
                              </div>

                           
                              @if($valoraciones_enfermerias->id_mallampaty == '1')
                                <div class="col-lg-10 col-md-12" id="evaluacion_mallampaty_opcion"
                                  name="evaluacion_mallampaty_opcion">
                                  <label for="tipo">EVALUACIÓN MALLAMPATY</label>
                                  <select class="form-control input-sm evaluacion_mallampaty_seleccion" disabled
                                    id="evaluacion_mallampaty_seleccion" name="evaluacion_mallampaty_seleccion"
                                    style="width: 100%;"> 
                                    @if($valoraciones_enfermerias->descripcion_mallampaty == '*')
                                      <option VALUE="*" selected>--SELECCIONAR--</option>
                                      <option VALUE="1" ><b>CLASE I:</b> VISIBILIDAD DEL PALADAR BLANDO, ÚVULA Y PILARES</option>
                                      <option VALUE="2"><b>CLASE II:</b> VISIBILIDAD DEL PALADAR BLANDO Y ÚVULA</option>
                                      <option VALUE="3"><b>CLASE III:</b> VISIBILIDAD DEL PALADAR BLANDO BASE DE LA ÚVULA</option>
                                      <option VALUE="4"><b>CLASE IV:</b> IMPOSIBILIDAD PARA VER EL PALADAR BLANDO</option>
                                    @endif
                                    @if($valoraciones_enfermerias->descripcion_mallampaty == '1')
                                      <option VALUE="*">--SELECCIONAR--</option>
                                      <option VALUE="1" selected><b>CLASE I:</b> VISIBILIDAD DEL PALADAR BLANDO, ÚVULA Y PILARES</option>
                                      <option VALUE="2"><b>CLASE II:</b> VISIBILIDAD DEL PALADAR BLANDO Y ÚVULA</option>
                                      <option VALUE="3"><b>CLASE III:</b> VISIBILIDAD DEL PALADAR BLANDO BASE DE LA ÚVULA</option>
                                      <option VALUE="4"><b>CLASE IV:</b> IMPOSIBILIDAD PARA VER EL PALADAR BLANDO</option>
                                    @endif
                                    @if($valoraciones_enfermerias->descripcion_mallampaty == '2')
                                      <option VALUE="*">--SELECCIONAR--</option>
                                      <option VALUE="1" ><b>CLASE I:</b> VISIBILIDAD DEL PALADAR BLANDO, ÚVULA Y PILARES</option>
                                      <option VALUE="2" selected><b>CLASE II:</b> VISIBILIDAD DEL PALADAR BLANDO Y ÚVULA</option>
                                      <option VALUE="3"><b>CLASE III:</b> VISIBILIDAD DEL PALADAR BLANDO BASE DE LA ÚVULA</option>
                                      <option VALUE="4"><b>CLASE IV:</b> IMPOSIBILIDAD PARA VER EL PALADAR BLANDO</option>
                                    @endif
                                    @if($valoraciones_enfermerias->descripcion_mallampaty == '3')
                                      <option VALUE="*">--SELECCIONAR--</option>
                                      <option VALUE="1" ><b>CLASE I:</b> VISIBILIDAD DEL PALADAR BLANDO, ÚVULA Y PILARES</option>
                                      <option VALUE="2" ><b>CLASE II:</b> VISIBILIDAD DEL PALADAR BLANDO Y ÚVULA</option>
                                      <option VALUE="3" selected><b>CLASE III:</b> VISIBILIDAD DEL PALADAR BLANDO BASE DE LA ÚVULA</option>
                                      <option VALUE="4"><b>CLASE IV:</b> IMPOSIBILIDAD PARA VER EL PALADAR BLANDO</option>
                                    @endif
                                    @if($valoraciones_enfermerias->descripcion_mallampaty == '4')
                                      <option VALUE="*">--SELECCIONAR--</option>
                                      <option VALUE="1" ><b>CLASE I:</b> VISIBILIDAD DEL PALADAR BLANDO, ÚVULA Y PILARES</option>
                                      <option VALUE="2" ><b>CLASE II:</b> VISIBILIDAD DEL PALADAR BLANDO Y ÚVULA</option>
                                      <option VALUE="3" ><b>CLASE III:</b> VISIBILIDAD DEL PALADAR BLANDO BASE DE LA ÚVULA</option>
                                      <option VALUE="4" selected><b>CLASE IV:</b> IMPOSIBILIDAD PARA VER EL PALADAR BLANDO</option>
                                    @endif
                                  </select>

                                  <div>
                                    <div class="row">
                                      <br>
                                      <div class="col-md-3" id="mallampati_1_div" name="mallampati_1_div"
                                        style="cursor: pointer;">
                                        <img id="mallampati_1" name="mallampati_1" class="img_blanco_negro"
                                          src="{{URL::asset('/images/mallampati/mallampati_1.png')}}"
                                          style="width: 100%;height: 100%;" />
                                      </div>
                                      <div class="col-md-3" id="mallampati_2_div" name="mallampati_2_div"
                                        style="cursor: pointer;">
                                        <img id="mallampati_2" name="mallampati_2" class="img_blanco_negro"
                                          src="{{URL::asset('/images/mallampati/mallampati_2.png')}}"
                                          style="width: 100%;height: 100%;" />
                                      </div>
                                      <div class="col-md-3" id="mallampati_3_div" name="mallampati_3_div"
                                        style="cursor: pointer;">
                                        <img id="mallampati_3" name="mallampati_3" class="img_blanco_negro"
                                          src="{{URL::asset('/images/mallampati/mallampati_3.png')}}"
                                          style="width: 100%;height: 100%;"/>
                                      </div>
                                      <div class="col-md-3" id="mallampati_4_div" name="mallampati_4_div"
                                        style="cursor: pointer;">
                                        <img id="mallampati_4" name="mallampati_4" class="img_blanco_negro"
                                          src="{{URL::asset('/images/mallampati/mallampati_4.png')}}"
                                          style="width: 100%;height: 100%;" />
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              @endif
                            </div>
                          </div>
                            </div>
                      </fieldset>
                    </div>

                    <div class="tab-pane fade" id="tab4default">
                      <fieldset>
                        <div class="row">

                          <div class="col-lg-6 col-md-12">

                                  <div class="col-lg-3">
                                    <label for="tipo">DISPOSITIVOS INVASIVOS</label><br> 
                                    @if($pieldispositivo_enfermerias->id_dispositivo_invasivos == '1')
                                        <input type="radio" name="dispositivos_invasivos1" disabled checked>
                                    @else
                                        <input type="radio" name="dispositivos_invasivos1" disabled >
                                    @endif
                                    <b>
                                        SI
                                    </b>
                                    @if($pieldispositivo_enfermerias->id_dispositivo_invasivos == '0')
                                        <input type="radio" name="dispositivos_invasivos1" disabled checked>
                                    @else
                                        <input type="radio" name="dispositivos_invasivos1" disabled >
                                    @endif
                                    <b>
                                        NO
                                    </b>    
                                  </div>
                              <div class="col-lg-9">    
                                  @if($pieldispositivo_enfermerias->id_dispositivo_invasivos == '1')
                                    <div class="col-md-12">
                                          <div class="col-md-4">
                                            <label for="tipo">VÍA PERIFÉRICA</label><br>
                                              @if($pieldispositivo_enfermerias->id_via_periferica == '1')
                                                  <input type="radio" name="dispositivos_invasivos2" disabled checked>
                                              @else
                                                  <input type="radio" name="dispositivos_invasivos2" disabled >
                                              @endif
                                              <b>
                                                  SI
                                              </b>
                                              @if($pieldispositivo_enfermerias->id_via_periferica == '0')
                                                  <input type="radio" name="dispositivos_invasivos2" disabled checked>
                                              @else
                                                  <input type="radio" name="dispositivos_invasivos2" disabled >
                                              @endif
                                              <b>
                                                  NO
                                              </b>      
                                          </div>
                                          <div class="col-md-4">
                                            <label for="tipo">VÍA CENTRAL</label><br>
                                                @if($pieldispositivo_enfermerias->id_via_central == '1')
                                                      <input type="radio" name="via_central" disabled checked>
                                                  @else
                                                      <input type="radio" name="via_central" disabled >
                                                  @endif
                                                  <b>
                                                      SI
                                                  </b>
                                                  @if($pieldispositivo_enfermerias->id_via_central == '0')
                                                      <input type="radio" name="via_central" disabled checked>
                                                  @else
                                                      <input type="radio" name="via_central" disabled >
                                                  @endif
                                                  <b>
                                                      NO
                                                  </b>   
                                          </div>
                                          <div class="col-md-4">
                                            <label for="tipo">CATÉTER ALTO FLUJO</label><br>
                                                @if($pieldispositivo_enfermerias->id_caracter_alto_flujo == '1')
                                                          <input type="radio" name="dispositivos_invasivos3" disabled checked>
                                                      @else
                                                          <input type="radio" name="dispositivos_invasivos3" disabled >
                                                      @endif
                                                      <b>
                                                          SI
                                                      </b>
                                                      @if($pieldispositivo_enfermerias->id_caracter_alto_flujo == '0')
                                                          <input type="radio" name="dispositivos_invasivos3" disabled checked>
                                                      @else
                                                          <input type="radio" name="dispositivos_invasivos3" disabled >
                                                      @endif
                                                      <b>
                                                          NO
                                                      </b> 
                                          </div>
                                    </div>
                                    <div class="col-md-12">
                                            <div class="col-md-4">
                                                  <br>
                                                  <label for="tipo">SONDAS</label> <br>
                                                  @if($pieldispositivo_enfermerias->id_sonda == '1')
                                                      <input type="radio" name="sondas" disabled checked>
                                                  @else
                                                      <input type="radio" name="sondas" disabled >
                                                  @endif
                                                  <b>
                                                      SI
                                                  </b>
                                                  @if($pieldispositivo_enfermerias->id_sonda == '0')
                                                      <input type="radio" name="sondas" disabled checked>
                                                  @else
                                                      <input type="radio" name="sondas" disabled >
                                                  @endif
                                                  <b>
                                                      NO
                                                  </b>  
                                            </div>
                                            <div class="col-md-4">
                                              <label for="tipo">CURACION</label><br>
                                                  @if($pieldispositivo_enfermerias->id_caracter_curacion == '1')
                                                            <input type="radio" name="dispositivos_invasivos3" disabled checked>
                                                        @else
                                                            <input type="radio" name="dispositivos_invasivos3" disabled >
                                                        @endif
                                                        <b>
                                                            SI
                                                        </b>
                                                        @if($pieldispositivo_enfermerias->id_caracter_curacion == '0')
                                                            <input type="radio" name="dispositivos_invasivos3" disabled checked>
                                                        @else
                                                            <input type="radio" name="dispositivos_invasivos3" disabled >
                                                        @endif
                                                        <b>
                                                            NO
                                                        </b> 
                                            </div>
                                            <div class="col-md-8">
                                                @if($pieldispositivo_enfermerias->id_sonda == '1')
                                                  <div class="col-md-12" id="sondas_descripcion" name="sondas_descripcion">
                                                    <br>
                                                    <label for="tipo">DESCRIPCIÓN SONDAS</label>
                                                    <input type="text" class="form-control" value="{{$pieldispositivo_enfermerias->descripcion_sonda }}" readonly>   
                                                  </div>
                                                @endif
                                            </div>
                                     </div>
                                     <div class="col-md-12">
                                          <div class="col-md-4">
                                              <br>
                                              <label for="tipo">LÍNEA ARTERIAL</label><br>
                                              @if($pieldispositivo_enfermerias->id_linea_arterial == '1')
                                                  <input type="radio" name="sonda22s" disabled checked>
                                              @else
                                                  <input type="radio" name="sonda22s" disabled >
                                              @endif
                                              <b>
                                                  SI
                                              </b>
                                              @if($pieldispositivo_enfermerias->id_linea_arterial == '0')
                                                  <input type="radio" name="sonda22s" disabled checked>
                                              @else
                                                  <input type="radio" name="sonda22s" disabled >
                                              @endif
                                              <b>
                                                  NO
                                              </b> 
                                          </div>
                                      <br>
                                      </div>
                                      <div class="col-md-12">
                                          <div class="col-md-4">
                                              <br>
                                                <label for="tipo">DRENAJES</label> <br>
                                                @if($pieldispositivo_enfermerias->id_tiene_drenaje == '1')
                                                    <input type="radio" name="drenaje" disabled checked>
                                                @else
                                                    <input type="radio" name="drenaje" disabled >
                                                @endif
                                                <b>
                                                    SI
                                                </b>
                                              @if($pieldispositivo_enfermerias->id_tiene_drenaje == '0')
                                                  <input type="radio" name="drenaje" disabled checked>
                                              @else
                                                  <input type="radio" name="drenaje" disabled >
                                              @endif
                                              <b>
                                                  NO
                                              </b>   
                                          </div>
                                          <div class="col-md-8">
                                              @if($pieldispositivo_enfermerias->id_tiene_drenaje == '1')
                                                <div class="col-md-12" id="drenaje_descripcion" name="drenaje_descripcion">
                                                  <br>
                                                  <label for="tipo">DESCRIPCIÓN DRENAJES</label>
                                                  <input type="text" class="form-control" value="{{$pieldispositivo_enfermerias->descripcion_drenaje}}" readonly> 
                                                </div>
                                              @endif
                                          </div>
                                      </div>
                                  @endif
                                </div>

                            </div>

                          <div class="col-lg-6 col-md-12">
                            <form id="FormularioPunto" name="FormularioPunto" enctype="multipart/form-data"
                              method="GET">
                              <div class="col-md-10">
                                  <div class="form-group">
                                    <div class="col-md-12">
                                      <label for="tipo">SELECCIONE PROCESO</label>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-md-12" style="cursor: pointer;">
                                      <div class="form-group"> 
                                        <div class="grid-block "  id="celda1" name="celda1"
                                          style="background-image: url('/images/hospitalizacion_enfermeria/imagen2.jpg'); background-repeat: no-repeat;">
                                          <canvas id="plano" name="plano" width="512" height="512"></canvas>
                                          <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                          <input type="hidden" name="x" id="x">
                                          <input type="hidden" name="y" id="y">
                                          <div id="control_div_puntos" name="control_div_puntos">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <ul class="leyenda_grafico" id="control_div_puntos_leyenda"
                                        name="control_div_puntos_leyenda">
                                      </ul>
                                    </div>
                                  </div>
                              </div>
                              <div class="col-md-2">
                                        <label for="tipo">LEYENDA</label>

                                        <ul class="list-unstyled" style="margin-top: 10px; font-size: 11px;">
                                              <li style="margin-bottom: 5px; white-space: nowrap;">
                                                <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(255, 255, 255); border: 1px solid #000; margin-right: 10px;"></span>
                                                SIN DATO
                                            </li>
                                              <li style="margin-bottom: 5px; white-space: nowrap;">
                                                  <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(247, 244, 17); border: 1px solid #000; margin-right: 10px;"></span>
                                                  DAI
                                              </li>
                                              <li style="margin-bottom: 5px; white-space: nowrap;">
                                                  <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(76, 187, 23); border: 1px solid #000; margin-right: 10px;"></span>
                                                  DISPOSITIVOS
                                              </li>
                                              <li style="margin-bottom: 5px; white-space: nowrap;">
                                                  <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(255, 0, 0); border: 1px solid #000; margin-right: 10px;"></span>
                                                  LPP
                                              </li>
                                              <li style="margin-bottom: 5px; white-space: nowrap;">
                                                  <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(0, 0, 255); border: 1px solid #000; margin-right: 10px;"></span>
                                                  OTROS
                                              </li>
                                              <li style="margin-bottom: 5px; white-space: nowrap;">
                                                  <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(237, 145, 33); border: 1px solid #000; margin-right: 10px;"></span>
                                                  PIE DIABÉTICO
                                              </li>
                                          </ul>                                    
                                    </div>
                            </form>
                          </div>
                        </div>
                      </fieldset>
                    </div>

                    <div class="tab-pane fade" id="tab5default">
                      <fieldset>
                        <div class="form-group">
                          <div class="col-lg-2 col-md-4">
                            <input type="hidden" value="0,0,0,0,0,0" id="puntaje_calculado_lpp_memoria"
                              name="puntaje_calculado_lpp_memoria">
                            <label for="tipo">ESCALA DE RIESGO LESION POR PRESION (BRADEN)</label> 
                            <input type="text"  value="{{$enfermeria_escalas->escala_rlp}}"
                              class="form-control" style="height: 30px !important;" readonly> 
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">VALORACIÓN DE ESCALA LESION POR PRESION</label>    
                            <input type="text" class="form-control" value="{{$enfermeria_escalas->valoracion_rlp}}" readonly>  
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <input type="hidden" value="0,FF0,FA0,0,FAAS0" id="puntaje_calculado_caidas_memoria"
                              name="puntaje_calculado_caidas_memoria">
                            <label for="tipo">ESCALA DE RIESGO DE CAÍDAS({{($datos_atencion[0]->servicio_hospi) == "PEDIATRÍA" ? 'HUMPTY DUMPTY' : 'J. H. DOWNTON'}})</label>
                              <input type="text"  value="{{$enfermeria_escalas->escala_rc}}"
                                class="form-control" style="height: 30px !important;" readonly> 
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">VALORACIÓN DE ESCALA DE RIESGO DE CAÍDAS</label>
                            <input type="text" class="form-control" value="{{$enfermeria_escalas->valoracion_rc}}" readonly>  
                          </div>
                          
                          @if ($valoraciones_enfermerias->id_dolor != null || $valoraciones_enfermerias->id_dolor == 1)
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">ESCALA DEL DOLOR</label> 
                              @if($enfermeria_escalas->escala_dolor == 0) 
                                <input type="text" class="form-control" value="SIN DOLOR (0)" readonly>   
                              @elseif($enfermeria_escalas->escala_dolor == 1)
                                <input type="text" class="form-control" value="DOLOR LEVE (1-2-3)" readonly>    
                              @elseif($enfermeria_escalas->escala_dolor == 2)
                                <input type="text" class="form-control" value="DOLOR MODERADO (4-5-6-7)" readonly>     
                              @elseif($enfermeria_escalas->escala_dolor == 3)
                                <input type="text" class="form-control" value="DOLOR SEVERO (8-9-10)" readonly>    
                              @endif 
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <div class="col-md-3" id="escala_dolor1_div" name="escala_dolor1_div"
                              style="cursor: pointer;">
                              <img id="escala_dolor1" name="escala_dolor1"
                                src="{{URL::asset('/images/escala_dolor/sin_leve.png')}}"
                                style="width: 50px;height: 50px;margin-top: 10px;" />
                            </div>
                            <div class="col-md-3" id="escala_dolor2_div" name="escala_dolor2_div"
                              style="cursor: pointer;">
                              <img id="escala_dolor2" name="escala_dolor2"
                                src="{{URL::asset('/images/escala_dolor/dolor_leve.png')}}"
                                style="width: 50px;height: 50px;margin-top: 10px;" />
                            </div>
                            <div class="col-md-3" id="escala_dolor3_div" name="escala_dolor3_div"
                              style="cursor: pointer;">
                              <img id="escala_dolor3" name="escala_dolor3"
                                src="{{URL::asset('/images/escala_dolor/dolor_moderado.png')}}"
                                style="width: 50px;height: 50px;margin-top: 10px;" />
                            </div>
                            <div class="col-md-3" id="escala_dolor4_div" name="escala_dolor4_div"
                              style="cursor: pointer;">
                              <img id="escala_dolor4" name="escala_dolor4"
                                src="{{URL::asset('/images/escala_dolor/dolor_severo.png')}}"
                                style="width: 50px;height: 50px;margin-top: 10px;" />
                            </div>
                          </div>
                          @endif

                        </div>
                        <div class="form-group">
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo" style="color: red;">BRAZALETE DE IDENTIFICACIÓN</label> <br>
                            @if($enfermeria_escalas->id_brazalete == '1')
                                <input type="radio" name="brazalete" disabled checked>
                            @else
                                <input type="radio" name="brazalete" disabled >
                            @endif
                            <b>
                                SI
                            </b>
                            @if($enfermeria_escalas->id_brazalete == null)
                                <input type="radio" name="brazalete1" disabled checked>
                            @else
                                <input type="radio" name="brazalete1" disabled >
                            @endif
                            <b>
                                NO
                            </b>   
                          </div>

                          @if($enfermeria_escalas->id_brazalete == '1')
                            <div class="col-lg-2 col-md-4" id="brazalete_opcion" name="brazalete_opcion">
                              <label for="tipo" style="color: red;">COLOR DE BRAZALETE</label> 
                                <input type="text" class="form-control"readonly value="{{$enfermeria_escalas->id_color_brazalete }}">     
                            </div>
                          @endif

                        </div>
                        <div class="col-lg-2 col-md-4">
                          <label for="tipo">LEYENDA DE BRAZALETES</label>

                          <ul class="list-unstyled" style="margin-top: 10px; font-size: 11px;">
                            
                            <li style="margin-bottom: 5px; white-space: nowrap;">
                              <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(255, 255, 255); border: 1px solid #000; margin-right: 10px;"></span>
                              SIN CRITERIO
                          </li>
                            <li style="margin-bottom: 5px; white-space: nowrap;">
                                <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(230, 59, 59); border: 1px solid #000; margin-right: 10px;"></span>
                                ALERGIAS
                            </li>
                            <li style="margin-bottom: 5px; white-space: nowrap;">
                                <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(248, 244, 19); border: 1px solid #000; margin-right: 10px;"></span>
                                CAIDAS
                            </li>
                        </ul>
                        
                        </div>


                    </fieldset>
                  </div>

                  <div class="tab-pane fade" id="tab6default">
                    <fieldset>
                      <div class="form-group">
                        <div class="col-md-12">
                          <label>DETALLE</label>
                          <textarea class="form-control" rows="10" disabled>{{$datos_nota_ingreso[0]->observacion_enfermeria}}</textarea>
                        </div>
                      </div>
                    </fieldset>
                  </div>

                </div>
              </div>
            </div>

            <a class="btn btn-danger" href="/hospitalizacion_by_enfermeria"><i class="fa fa-chevron-left"></i> VOLVER AL LISTADO</a>
            
            @if ($status_token == 1)
              <a href="/pdf_nota_ingreso_enfermeria_hospitalizacion/{{$datos_nota_ingreso[0]->id}}/{{$id_tipo_atenciones}}" 
                target="_blank" class="btn btn-primary pull-right" type="button" >GENERAR SIN FIRMAR</a>
            @elseif ($status_token == 0)
              @if ($datos_nota_ingreso[0]->flag_firma == 1)
                <button type="button" class="btn btn-primary pull-right"  id="btnDownloadFirmar" ><i class="fa fa-download"></i> DESCARGAR PDF FIRMADO</button>
              @else
                <button type="button" class="btn btn-primary pull-right"  id="btnFirmar" ><i class="fa fa-pencil"></i> FIRMAR NOTA DE ENFERMERIA</button>
              @endif
            @endif
          </form>
        </div>
        <!--fin panel body -->
      </div>
      <!--fin col-md-12 -->
    </div>
    <!--fin row -->
  </div>
  <!--fin container -->
</div>
<!--fin row -->
</div>
<!--fin container -->


@include('frontend.includes.footer')
@endsection