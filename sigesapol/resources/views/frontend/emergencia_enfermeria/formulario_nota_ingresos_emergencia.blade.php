@extends('frontend.layouts.master3')
@section('renderjs')

<script src='../../funciones/function.js'></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script> 
@include('frontend.hospitalizacion.enfermeria.funciones_utilidades.calculo_edad.function_edad')

@include('frontend.hospitalizacion.enfermeria.nota_ingreso.function_nota_ingreso.parametros.listar_parametros')
@include('frontend.hospitalizacion.enfermeria.nota_ingreso.function_nota_ingreso.puntos_grafico.control_de_puntos')

@include('frontend.hospitalizacion.enfermeria.nota_ingreso.function_nota_ingreso.escalas.glasgow')
@include('frontend.hospitalizacion.enfermeria.nota_ingreso.function_nota_ingreso.escalas.lpp')
@include('frontend.hospitalizacion.enfermeria.nota_ingreso.function_nota_ingreso.escalas.caidas')

<script>


  //LLENAMOS LOS COMBOS DESDE LA TABLA DE PARAMETROS
  lista_parametros('CARDIOLOGICO_NOT_ENF','cardiologico','combo_multiple','TABLA_PARAMETROS',0);
  lista_parametros('INGRESA_POR','ingresa_por','combo','TABLA_PARAMETROS',0);
  lista_parametros('CONDICION_INGRESO','condicion_ingreso','combo','TABLA_PARAMETROS',0);
  lista_parametros('ESTADO_CONCIENCIA','estado_conciencia','combo','TABLA_PARAMETROS',0);
  lista_parametros('FUNCION_MOTORA','funcion_motora','combo','TABLA_PARAMETROS',0);
  lista_parametros('RESPIRATORIO','respiratorio_duracion','combo','TABLA_PARAMETROS',0);
  lista_parametros('SECRECIONES','secreaciones_bronquiales','combo','TABLA_PARAMETROS',0);
  lista_parametros('OXIGENOTERAPIA','oxigenoterapia_detalle_seleccion  ','combo','TABLA_PARAMETROS',0);
  lista_parametros('PIEL','piel_colores','combo','TABLA_PARAMETROS',0);
  lista_parametros('INTEGRIDAD','integridad','combo','TABLA_PARAMETROS',0);
  lista_parametros('ABDOMEN','abdomen','combo','TABLA_PARAMETROS',0);
  lista_parametros('TIPO_PROCESO_PUNTO','tipo_proceso','combo','TABLA_PARAMETROS',0);
  lista_parametros('TAMANIO_PUNTOS','tamanio_proceso','combo','TABLA_PARAMETROS',0); 
   
  function cambiar_de_PestanaTab(descripcion_de_tab) {  
    var tab_seleccionado = document.querySelector('a[href="#' + descripcion_de_tab + '"]'); 
    tab_seleccionado.click();
  }
  // RESTRINGIR Y ENVIAR UN MENSAJE
  
  $(document).ready(function() {
        $('#observacion_detallada').on('input', function() {
            var maxCaracteres = 200;
            var caracteresIngresados = $(this).val().length;

            if (caracteresIngresados > maxCaracteres) {
                $(this).val($(this).val().substring(0, maxCaracteres));
                $('#mensajeCaracteresRestantes').text('Se ALCANZO el límite de caracteres (' + maxCaracteres + ')');
            } else {
                $('#mensajeCaracteresRestantes').text('');

            }
        });

        const talla = $("#talla").val();
        const peso = $("#peso").val();
        if (talla.trim() != "" || peso.trim() != "") {
          compruebaIMC();
        }
    });
    

    async function capturarContenidoCelda(celdaId) { 
      const celda = document.getElementById(celdaId);

       //Oculta el botón temporalmente para la versión de impresión
      document.querySelector('button').style.visibility = 'hidden';

      // Captura el contenido de la celda y conviértelo en una imagen
      html2canvas(celda).then(function (canvas) {
        const imgData = canvas.toDataURL('image/png');
        const imagenCapturada = document.getElementById('imagenCapturada');
        $('#img_puntos_base').val(imgData.replace(/^data:image\/[a-z]+;base64,/, ""));  
 
        imagenCapturada.src = imgData;
        imagenCapturada.style.display = 'block';

        // Reemplaza el contenido de la celda con la nueva imagen
        celda.innerHTML = '';
        celda.appendChild(imagenCapturada); 
        // Example usage 
 
      });
    }

    function evitarLetras(event) {
    var charCode = event.which ? event.which : event.keyCode;
    var inputValue = String.fromCharCode(charCode);

    // Expresión regular para permitir solo números y un punto decimal
    var pattern = /^[\d.]+$/;

    if (!pattern.test(inputValue)) {
      event.preventDefault();
    }

    return true;
  }



  function compruebaIMC() {
    
    var peso = $('#peso').val(); 
    if(peso==''){
      peso=0;
    }
    var talla =$('#talla').val(); 
    if(talla==''){
      talla=0;
    }
    var temperatura = $('#temperatura').val(); 
    
    if (talla != "") {
      //PESO POR ALTURA
      var sexo = '{{$datos_emergencia[0]->sexo}}'; //MASCULINO , FEMENINO
      var edad_anos = $("#edad").val(); 
       
      var pesoIdeal = 50 + ((talla - 150) / 4) * 3 + (edad_anos - 20) / 4
      if (sexo == "M") {
        pesoIdeal = pesoIdeal * 0.9;
      }
      $('#peso_x_altura').val(pesoIdeal.toFixed(2))  ;
    } else {
      $('#peso_x_altura').val("") ;  
    }

    
    if (peso != "" && talla != "") {
      var talla_en_metros = talla * 0.01;
      //IMC
      var imcx = (peso / (talla_en_metros * talla_en_metros)).toFixed(2);
      $('#imc').val(imcx) ; 

      if (imcx < 18.5) {
        $('#imc').addClass('borde-imc-bajopeso');
        $('#imc-aceptado').html("<strong>" + 'Paciente Bajo de Peso' + "</strong>");

      } else if (imcx >= 18.5 && imcx< 24.9) {
        $('#imc').addClass('borde-imc-normal');
        $('#imc-aceptado').html("<strong>" + 'Paciente Peso Normal' + "</strong>");
        //NORMAL
      } else if (imcx >= 25.0 && imcx< 29.9){
        $('#imc').addClass('borde-imc-sobrepeso');
        $('#imc-aceptado').html("<strong>" + 'Paciente Sobrepeso' + "</strong>");
        //SOBREPESO
      } else if (imcx >= 30.0 && imcx< 34.9){
        $('#imc').addClass('borde-imc-obesidad1');
        $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad I' + "</strong>");
        //OBESIDAD I
      } else if (imcx >= 35.0 && imcx< 39.9){
        $('#imc').addClass('borde-imc-obesidad2');
        $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad II' + "</strong>");
        // OBESIDAD II
      } else if (imcx >= 40.0){
        $('#imc').addClass('borde-imc-obesidad3');
        $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad III' + "</strong>");
        // OBESIDAD III
      }

    } else {
      $('#imc').val("") ;
    }
  }


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
    
  function seleccionar_radio_por_texto($nombre_campo, $radio_afectado, $campo_extra, $tipo){  
    const radio = document.getElementById($radio_afectado);
      radio.checked = true;
      if($tipo=='show'){
        $('#'+$campo_extra).show(); 
      }else if($tipo=='hide'){
        $('#'+$campo_extra).hide(); 
      } 
  }
  // function seleccionar_radio_por_texto_hemodialisis($nombre_campo, $radio_afectado, $campo_extra, $tipo){  
  //   const radio = document.getElementById($radio_afectado);
  //     radio.checked = true;
  //     if($tipo=='show'){
  //       $('#'+$campo_extra).show(); 
  //     }else if($tipo=='hide'){
  //       $('#'+$campo_extra).hide(); 
  //     } 
  // }
 

  const mallampati_1 = document.querySelector('#mallampati_1_div'); 
  const mallampati_2 = document.querySelector('#mallampati_2_div'); 
  const mallampati_3 = document.querySelector('#mallampati_3_div'); 
  const mallampati_4 = document.querySelector('#mallampati_4_div');  
  
  mallampati_1.addEventListener('click', () => { 
    $("#evaluacion_mallampaty_seleccion").val("1").change();
  });
  mallampati_2.addEventListener('click', () => {  
    $("#evaluacion_mallampaty_seleccion").val("2").change();
  });
  mallampati_3.addEventListener('click', () => {  
    $("#evaluacion_mallampaty_seleccion").val("3").change();
  });
  mallampati_4.addEventListener('click', () => {  
    $("#evaluacion_mallampaty_seleccion").val("4").change();
  });
 

 const escala_dolor1 = document.querySelector('#escala_dolor1_div'); 
 const escala_dolor2 = document.querySelector('#escala_dolor2_div'); 
 const escala_dolor3 = document.querySelector('#escala_dolor3_div'); 
 const escala_dolor4 = document.querySelector('#escala_dolor4_div');  
  
 escala_dolor1.addEventListener('click', () => { 
    $("#escalas").val("0").change();
  });
  escala_dolor2.addEventListener('click', () => {  
    $("#escalas").val("1").change();
  });
  escala_dolor3.addEventListener('click', () => {  
    $("#escalas").val("2").change();
  });
  escala_dolor4.addEventListener('click', () => {  
    $("#escalas").val("3").change();
  });

  document.getElementById('plano').addEventListener('click', function(event) {
     // alert("Le has dado click");
      var rect = document.getElementById('plano').getBoundingClientRect();
      var x = event.clientX - rect.left;
      var y = event.clientY - rect.top;
      var id_atencion=$('#id_atencion').val();
      var id_tipo_atenciones=$('#id_tipo_atenciones').val();
      var tipo_color =  document.getElementById('tipo_proceso');
      var valorSeleccionado = tipo_color.value;
  
     // guardarpuntos_jhon(x,y,valorSeleccionado);
      guardarPunto();  

      pintar_puntos_by_hospitalizacion(id_atencion, "control_div_puntos", "control_div_puntos_leyenda", "NOTA_ENFERMERIA",'REGISTRO',id_tipo_atenciones); 
  });
    

  $(document).ready(function() {  
    var select2Element = $('#cardiologico');

    // Inicializa Select2
    select2Element.select2({
      theme: 'classic', // 
    });

    // Agrega un evento de cambio para verificar cuando se selecciona/deselecciona un valor
    select2Element.on('change', function() {
      // Verifica si no se ha seleccionado ningún valor
      if ($(this).val() === null || $(this).val().length === 0) {
        // Agrega la clase de borde de alerta
        $('.select2-selection--multiple').addClass('borde-alerta');
      } else {
        // Quita la clase de borde de alerta
        $('.select2-selection--multiple').removeClass('borde-alerta');
      }
    });
    

    
    $("#seccion_de_responsable").hide();
    $("#seccion_de_apoderado").hide();
    calcular_edad_detallada();
    
    $("#grado_dependencia").select2({ width: 'resolve'});
    $("#funcion_motora").select2({ width: 'resolve'});
    // $("#via_periferica").select2({ width: 'resolve'});
    $("#via_central").select2({ width: 'resolve'});
    $("#caracter_alto_flujo").select2({ width: 'resolve'}); 
    $("#sng_gavedad").select2({ width: 'resolve'});
    $("#linea_arterial").select2({ width: 'resolve'}); 

    pintar_puntos_by_hospitalizacion({{$id_atencion}}, "control_div_puntos", "control_div_puntos_leyenda", "NOTA_ENFERMERIA",'REGISTRO', {{$id_tipo_atenciones}});  
     
    $("#hta_tratamiento").hide();
    $("#hta1").change(function() {   
        $('#hta_tratamiento').show(); 
    }); 

    $("#hta2").change(function() {   
        $("#hta_tratamiento").hide();
    }); 
   
   $("#diabetes_mellitus_observacion").hide();
   $("input[name=diabetes_mellitus]").click(function () {      
       if($(this).val()=='SI'){
         $('#diabetes_mellitus_observacion').show(); 
       }else{
         $("#diabetes_mellitus_observacion").hide();
       }
   });
    
    $("#ram_descripcion").hide();
    $("input[name=ram]").click(function () {      
        if($(this).val()=='SI'){
          $('#ram_descripcion').show(); 
        }else{
          $("#ram_descripcion").hide();
        }
    });
//HEMODIALISIS
    $("#ram_descripcion_hemodialisis").hide();
    $("input[name=ram_hemodialisis]").click(function () {      
        if($(this).val()=='SI'){
          $('#ram_descripcion_hemodialisis').show(); 
        }else{
          $("#ram_descripcion_hemodialisis").hide();
        }
    });
    
    $("#oxigenoterapia_detalle").hide();
    $("#oxigenoterapia_detalle_seleccion").select2({ width: 'resolve'}); 
    $("input[name=oxigenoterapia]").click(function () {      
        if($(this).val()=='SI'){
          $('#oxigenoterapia_detalle').show(); 
        }else{
          $("#oxigenoterapia_detalle").hide();
        }
    });
    $('#orientado_form').hide();
    $("#estado_conciencia").change(function() {   
      var estado_conciencia = $('#estado_conciencia').val();
      if(estado_conciencia==1){
        $('#orientado_form').show(); 
      }else{
        $('#orientado_form').hide();
        $("#orientado_si").hide();
        $('.orientado_input_no').prop('checked', true);
      }
      
    });
    
   


    $("#orientado_si").hide();
    //$("#oxigenoterapia_detalle_seleccion").select2({ width: 'resolve'}); 
    $("input[name=orientado]").click(function () {      
        if($(this).val()=='SI'){
          $('#orientado_si').show(); 
        }else{
          $("#orientado_si").hide();
        }
    });
    
    // $("#miccion_espontanea_detalle").hide();
    // $("input[name=miccion_espontanea]").click(function () {      
    //     if($(this).val()=='SI'){
    //       $('#miccion_espontanea_detalle').show(); 
    //     }else{
    //       $("#miccion_espontanea_detalle").hide();
    //     }
    // });

    $("input[name=acompaniado_por]").click(function () {      
        if($(this).val()=='FAMILIAR'){
          $('#acompaniado_por_detalle').show(); 
        }else{
          $("#acompaniado_por_detalle").hide();
        }
    });

     
     $("#dispositivos_invasivos_detalle").show();
     $("input[name=dispositivos_invasivos]").click(function () {      
         if($(this).val()=='SI'){
           $('#dispositivos_invasivos_detalle').show(); 
         }else{
           $("#dispositivos_invasivos_detalle").hide();
         }
     });
     
     $("#sondas_descripcion").hide();
     $("input[name=sondas]").click(function () {      
         if($(this).val()=='SI'){
           $('#sondas_descripcion').show(); 
         }else{
           $("#sondas_descripcion").hide();
         }
     });
     
     $("#drenaje_descripcion").hide();
     $("input[name=drenaje]").click(function () {      
         if($(this).val()=='SI'){
           $('#drenaje_descripcion').show(); 
         }else{
           $("#drenaje_descripcion").hide();
         }
     }); 
     
    $("#evaluacion_mallampaty_opcion").hide();
    $("#evaluacion_mallampaty_seleccion").select2({ width: 'resolve'}); 
     $("input[name=evaluacion_mallampaty]").click(function () {      
         if($(this).val()=='SI'){
           $('#evaluacion_mallampaty_opcion').show(); 
         }else{
           $("#evaluacion_mallampaty_opcion").hide();
         }
     });

    $("#evaluacion_mallampaty_seleccion").change(function(e) {
      var evaluacion_mallampaty_seleccion= $('#evaluacion_mallampaty_seleccion').val();
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
    }); 
     

     $("#brazalete_opcion").hide();
     $("#brazalete_seleccion").select2({ width: 'resolve'}); 
      $("input[name=brazalete]").click(function () {      
          if($(this).val()=='SI'){
            $('#brazalete_opcion').show(); 
          }else{
            $("#brazalete_opcion").hide();
          }
      });
 
      

      $(document).ready(function() {
    // Inicializar select2 en el menú desplegable
    $('#tipo_proceso').select2({ width: 'resolve' });

    // Llenar las opciones dinámicamente

    // Añadir un event listener para detectar cambios en el select
    $('#tipo_proceso').on('change', function() {
        toggleOtrosInput($(this).val());
    });
});

// Función para mostrar/ocultar el campo de entrada adicional
function toggleOtrosInput(selectedValue) {
    const otrosInputContainer = document.getElementById('otros_input_container');
    if (selectedValue === 'blue') {
        otrosInputContainer.style.display = 'block';
    } else {
        otrosInputContainer.style.display = 'none';
    }
}
    $("#tamanio_proceso").select2({ width: 'resolve'}); 
    $("#condicion_ingreso").select2({ width: 'resolve'}); 
    $("#respiratorio_duracion").select2({ width: 'resolve'}); 
    // $("#secreaciones_bronquiales").select2({ width: 'resolve'});   
    $("#piel_colores").select2({ width: 'resolve'});   

    
    $("#abdomen").select2({ width: 'resolve'});  
    $("#integridad").select2({ width: 'resolve'});  
     
    
    $('#escala_dolor1').removeClass('img_bajar_color'); 
    $('#escala_dolor2').addClass('img_bajar_color'); 
    $('#escala_dolor3').addClass('img_bajar_color'); 
    $('#escala_dolor4').addClass('img_bajar_color'); 
    $("#escalas").select2({ width: 'resolve'}); 
    $("#escalas").change(function(e) {
      var escalas= $('#escalas').val(); 
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
    }); 

    $("#deambulacion").select2({ width: 'resolve'});  
    $("#deambulacion").change(function(e) {
      calcular_riesgo_caidas();
    }); 
    
    
    $("#estado_mental").select2({ width: 'resolve'}); 
    $("#estado_mental").change(function(e) {
      calcular_riesgo_caidas();
    }); 


    $("#deficit").select2({ width: 'resolve'});  
    $("#deficit").change(function(e) {
      calcular_riesgo_caidas();
    }); 

    $("#medicamentos").select2({ width: 'resolve'}); 
    $("#medicamentos").change(function(e) {
      calcular_riesgo_caidas();
    });
     
 

    $("#caida").select2({ width: 'resolve'});    
    $("#caida").change(function(e) { 
      calcular_riesgo_caidas();
    });
 
   // $("#cardiologico").select2({ width: 'resolve'});  
    $("#estado_conciencia").select2({ width: 'resolve'});
    $("#estado_conciencia_otro").hide();
    $("#estado_conciencia").change(function(e) {
      var estado_conciencia= $('#estado_conciencia').val();
      if(estado_conciencia=="GLASGOW"){
        $('#estado_conciencia_otro').show(); 
      }else{
        $('#estado_conciencia_otro').hide(); 
      }
    });
//
      //    // Inicianlizando  select2
    $("#ingresa_por").select2({ width: 'resolve' });
    $("#ingresa_por_transferencia").select2({ width: 'resolve' });
    // Función para establecer el valor predeterminado y activar el cambio
    function setDefaultIngresaPor() {
        $('#ingresa_por').val('12').trigger('change');
        $('#ingresa_por').prop('disabled', true);
    }
    //UtiliZAR el evento select2:open para garantizar que select2 esté inicializado y que las opciones estén disponibles
    $('#ingresa_por').on('select2:open', function() {
        setDefaultIngresaPor();
    });
   // Respaldo en caso de que select2:open no se active
    setTimeout(function() {
        setDefaultIngresaPor();
    }, 2500);
// Cambiar manejo de eventos
    $("#ingresa_por").change(function(e) {
        var ingresa_por = $('#ingresa_por').val();
        console.log("ingresando por"+ingresa_por);
        if (ingresa_por == "14") {
            lista_parametros('NULL', 'ingresa_por_transferencia', 'combo', 'TABLA_ESPECIALIDADES', 0); 
            $('#ingresa_por_de').show();  
        } else {
            $('#ingresa_por_de').hide();
        }
    });

     
		$('#fecha_atencion').datepicker(); 
 
    //-------------------------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------------------------
     
    


    //------------//-------------------------------------------------------------------------------------
    $("#temperatura").on('blur', function (event) {
      var value = $(this).val();
      //console.log('blur');  
      if (Validar.esVacio(value) == false ) {
        if( !Validar.decimalEnRango(value, 35.0, 40.0)) {
          $('#temperatura').addClass('borde-temperatura-hipertermia');
          $('#texto-aceptado').html("<p><strong>" + 'La temperatura debe estar entre 35.00 a 40.00 ( °C )' + "</strong></p>");
          $('#texto-aceptado strong').addClass('borde-temperatura-valida');

        } else if(parseFloat(value) >= 35.0 && parseFloat(value) < 36.0) { //! AZUL
            $('#temperatura').addClass('borde-temperatura-hipotermia');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Hipotermia' + "</strong>");

        } else if(parseFloat(value) >= 36.0  && parseFloat(value) < 37.5) { //! VERDE
            $('#temperatura').addClass('borde-temperatura-normal');
            $('#texto-aceptado').html("<strong>" + 'Paciente con temperatura Normal' + "</strong>");

        } else if(parseFloat(value) >= 37.5  && parseFloat(value) < 39.5) { // AMARILLO
            $('#temperatura').addClass('borde-temperatura-fiebre');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre' + "</strong>");

        } else if(parseFloat(value) >= 39.5  && parseFloat(value) < 41.0) { // NARANJA
            $('#temperatura').addClass('borde-temperatura-fiebre-alta');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre Alta' + "</strong>");

        } else if(parseFloat(value) >= 41.0  && parseFloat(value) <= 45.0) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Hipertermia' + "</strong>");
        }
      } else if (Validar.esVacio(value) ) {
          $('#temperatura').addClass('borde-temperatura-hipertermia');
          $('#texto-aceptado').html("<p><strong>" + 'Ingrese el valor de la temperatura ( °C )' + "</strong></p>");
          $('#texto-aceptado strong').addClass('borde-temperatura-valida');
        }
    });

    $("#temperatura").on('input',function(event) {
      
//if (event.type === "click" || event.key === "Tab") {
  
    var value = $(this).val();
    //console.log('input');
    if (Validar.esVacio(value) ) {
        $('#temperatura').addClass('borde-temperatura-hipertermia');
        $('#texto-aceptado').html("<p><strong>" + 'Ingrese el valor de la temperatura ( °C )' + "</strong></p>");
        $('#texto-aceptado strong').addClass('borde-temperatura-valida');
    } else if ( !Validar.decimalEnRango(value, 35.0, 40.0)) {
        $('#temperatura').addClass('borde-temperatura-hipertermia');
        $('#texto-aceptado').html("<p><strong>" + 'La temperatura debe estar entre 35.00 a 40.00 ( °C )' + "</strong></p>");
        $('#texto-aceptado strong').addClass('borde-temperatura-valida');
    } else if(parseFloat(value) >= 35.0 && parseFloat(value) < 36.0) { //! AZUL
        $('#temperatura').addClass('borde-temperatura-hipotermia');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Hipotermia' + "</strong>");

    } else if(parseFloat(value) >= 36.0  && parseFloat(value) < 37.5) { //! VERDE
        $('#temperatura').addClass('borde-temperatura-normal');
        $('#texto-aceptado').html("<strong>" + 'Paciente con temperatura Normal' + "</strong>");

    } else if(parseFloat(value) >= 37.5  && parseFloat(value) < 39.5) { // AMARILLO
        $('#temperatura').addClass('borde-temperatura-fiebre');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre' + "</strong>");

    } else if(parseFloat(value) >= 39.5  && parseFloat(value) < 41.0) { // NARANJA
        $('#temperatura').addClass('borde-temperatura-fiebre-alta');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre Alta' + "</strong>");

    } else if(parseFloat(value) >= 41.0  && parseFloat(value) <= 45.0) {
        $('#temperatura').addClass('borde-temperatura-hipertermia');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Hipertermia' + "</strong>");

    } 
//}
});

    $("#temperatura").on('input',function() {
    $(this).removeClass('borde-temperatura borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
    $('#texto-aceptado').html('');
    });
//------------//-------------------------------------------------------------------------------------

    //-------------------------------------------------------------------------------------------------
    $("#sa02_porcentaje").on('blur', function (event) {
      var value = $(this).val();
      //console.log('blur');  
      if (Validar.esVacio(value) == false ) {
        if (!Validar.enteroEnRango(value, 60, 100)) {
            $('#sa02_porcentaje').addClass('borde-temperatura-hipertermia');
            $('#saturacion-aceptado').html("<p><strong>" + 'El valor de la Saturación debe ser entre 60 a 100 ( % )' + "</strong></p>");
            $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');

        } else if (value >= 60 && value <= 85){
          //HIPOXIA GRAVE  - ROJO
          $('#sa02_porcentaje').addClass('borde-saturacion-hipoxia-grave');
          $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Grave' + "</strong>");
        } else if (value >= 86 && value <= 90){
          //HIPOXIA MODERADA - NARANJA
          $('#sa02_porcentaje').addClass('borde-saturacion-hipoxia-moderada');
          $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Moderada' + "</strong>");
        } else if (value >= 91 && value <= 94){
          // HIPOXIA LEVE - AMARILLO
          $('#sa02_porcentaje').addClass('borde-saturacion-hipoxia-leve');
          $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Leve' + "</strong>");
        } else if (value >= 95 && value <= 100){
          //NORMAL - VERDE
          $('#sa02_porcentaje').addClass('borde-saturacion-normal');
          $('#saturacion-aceptado').html("<strong>" + 'Saturación Normal' + "</strong>");
        }
      } else if (Validar.esVacio(value) ) {
          $('#sa02_porcentaje').addClass('borde-temperatura-hipertermia');
          $('#saturacion-aceptado').html("<p><strong>" + 'Ingrese el valor de la Saturación ( % )' + "</strong></p>");
          $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');
        }
    
    });

    $("#sa02_porcentaje").on('input',function(event) {

      var value = $(this).val();

      if (Validar.esVacio(value) ) {
        $('#sa02_porcentaje').addClass('borde-temperatura-hipertermia');
        $('#saturacion-aceptado').html("<p><strong>" + 'Ingrese el valor de la Saturación ( % )' + "</strong></p>");
        $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');

      } else if (!Validar.enteroEnRango(value, 60, 100)) {
        $('#sa02_porcentaje').addClass('borde-temperatura-hipertermia');
        $('#saturacion-aceptado').html("<p><strong>" + 'El valor de la Saturación debe ser entre 60 a 100 ( % )' + "</strong></p>");
        $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');
      
      } else if (value >= 60 && value <= 85){
        //HIPOXIA GRAVE  - ROJO
        $('#sa02_porcentaje').addClass('borde-saturacion-hipoxia-grave');
        $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Grave' + "</strong>");
      } else if (value >= 86 && value <= 90){
        //HIPOXIA MODERADA - NARANJA
        $('#sa02_porcentaje').addClass('borde-saturacion-hipoxia-moderada');
        $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Moderada' + "</strong>");
      } else if (value >= 91 && value <= 94){
        // HIPOXIA LEVE - AMARILLO
        $('#sa02_porcentaje').addClass('borde-saturacion-hipoxia-leve');
        $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Leve' + "</strong>");
      } else if (value >= 95 && value <= 100){
        //NORMAL - VERDE
        $('#sa02_porcentaje').addClass('borde-saturacion-normal');
        $('#saturacion-aceptado').html("<strong>" + 'Saturación Normal' + "</strong>");
      }
    });

    $("#sa02_porcentaje").on('input',function() {
      $('#sa02_porcentaje').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal borde-temperatura-hipertermia');
      $('#saturacion-aceptado').html('');
    });
    
//-------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------

    $("#talla").click(function() {
      $('#imc').removeClass('borde-imc-bajopeso borde-imc-normal borde-imc-sobrepeso borde-imc-obesidad1 borde-imc-obesidad2 borde-imc-obesidad3');
        $('#imc-aceptado').html('');
      });
    
    $("#talla").on('blur',function(event) {  
      var value = $(this).val();
      if (Validar.esVacio(value) == false) {
        if (!Validar.enteroEnRango(value, 30, 200)) {
          $('#talla').addClass('borde-temperatura-hipertermia');
          $('#talla-aceptado').html("<p><strong>" + 'El valor de la talla debe ser entre 30 a 200 ( cm )' + "</strong></p>");
          $('#talla-aceptado strong').addClass('borde-temperatura-valida');
        }else {
          compruebaIMC();
        }
      }else if (Validar.esVacio(value)) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'Ingrese el valor de la Talla ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');
      } 
    });

    $("#talla").on('input',function(event) {  
      var value = $(this).val();
      if (Validar.esVacio(value) ) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'Ingrese el valor de la Talla ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');

      } else if (!Validar.enteroEnRango(value, 30, 200)) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'El valor de la talla debe ser entre 30 a 200 ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');
      }else {
        compruebaIMC();
      } 
    });

    $("#talla").on('input',function() {
      $('#talla').removeClass('borde-temperatura-hipertermia');
      $('#talla-aceptado').html('');
    });
    
//====================================================================================================

    $("#peso").on('blur',function(event) {
        var value = $(this).val();
        if (Validar.esVacio(value) == false) {
          if (!Validar.decimalEnRango(value, 1.0, 180.0)) {
            $('#peso').addClass('borde-temperatura-hipertermia');
            $('#peso-aceptado').html("<p><strong>" + 'El valor del peso debe ser entre 1.0 a 180 ( Kg )' + "</strong></p>");
            $('#peso-aceptado strong').addClass('borde-temperatura-valida');

          } else {
            compruebaIMC();
          }
        }else if (Validar.esVacio(value)) {
          $('#peso').addClass('borde-temperatura-hipertermia');
          $('#peso-aceptado').html("<p><strong>" + 'Ingrese el valor del Peso ( Kg )' + "</strong></p>");
          $('#peso-aceptado strong').addClass('borde-temperatura-valida');
        }
    });

    $("#peso").on('input',function(event) {
        var value = $(this).val();
        if (Validar.esVacio(value) ) {
          $('#peso').addClass('borde-temperatura-hipertermia');
          $('#peso-aceptado').html("<p><strong>" + 'Ingrese el valor del Peso ( Kg )' + "</strong></p>");
          $('#peso-aceptado strong').addClass('borde-temperatura-valida');

        } else if (!Validar.decimalEnRango(value, 1.0, 180.0)) {
            $('#peso').addClass('borde-temperatura-hipertermia');
            $('#peso-aceptado').html("<p><strong>" + 'El valor del peso debe ser entre 1.0 a 180 ( Kg )' + "</strong></p>");
            $('#peso-aceptado strong').addClass('borde-temperatura-valida');

        } else {
          compruebaIMC();
        }
    });

    $("#peso").on('input',function() {
      $('#peso').removeClass('borde-temperatura-hipertermia');
      $('#peso-aceptado').html('');
    });

    $("#peso").click(function() {
      $('#imc').removeClass('borde-imc-bajopeso borde-imc-normal borde-imc-sobrepeso borde-imc-obesidad1 borde-imc-obesidad2 borde-imc-obesidad3');
      $('#imc-aceptado').html('');
    });
//-------------------------------------------------------------------

// $("#tamanio_cintura").keydown(function(event) {
//       if (event.key === "Tab" ) { 
//         var value = $('#tamanio_cintura').val();
//         if (Validar.esVacio(value) ) {
//                 alert('Ingrese el tamanio de la cintura ( cm ) ');
//                 event.preventDefault(); 
//                 $('#tamanio_cintura').focus();
//                 return 0;

//             } else if (!Validar.enteroEnRango(value, 50, 150)) {
//                 alert('El valor del tamanio de la cintura debe ser entre 50 a 150 ( cm )');
//                 event.preventDefault(); 
//                 $('#tamanio_cintura').focus();
//                 return 0;
                
//             } 
//       }
//     }); 

 

// $("#tamanio_cintura").on('blur',function(event) { 
//   var value = $('#tamanio_cintura').val();
//   if(value.length==0){
//         $('#tamanio_cintura').addClass('borde-alerta');
//         $('#tamanio_cintura-aceptado').html("<p><strong>" + 'El valor del Tamaño de Cintura debe ser entre 50 a 150 ( /min )' + "</strong></p>"); 
//         $('#tamanio_cintura-aceptado strong').addClass('borde-temperatura-valida');
//   }else{
//     if (Validar.esVacio(value) == false ) {
//       if (!Validar.enteroEnRango(value, 50, 150)) {
//         $('#tamanio_cintura').addClass('borde-temperatura-valida');
//         $('#tamanio_cintura-aceptado').html("<p><strong>" + 'El valor del Tamaño de Cintura debe ser entre 50 a 150 ( /min )' + "</strong></p>"); 
//         $('#tamanio_cintura-aceptado strong').addClass('borde-temperatura-valida');
//       }
//     }else {
//       $('#tamanio_cintura').removeClass('borde-temperatura-valida');
//       $('#tamanio_cintura-aceptado').html("");
//       $('#tamanio_cintura-aceptado strong').removeClass('borde-temperatura-valida');
//     } 
//   }
// });

//-------------------------------------------------------------------------------------------------
$("#presion_arterial_x").on('blur',function(event) {
      
      var value = $(this).val();
        if (Validar.esVacio(value) == false ) {
          if (!Validar.enteroEnRango(value, 0, 250)) {
            $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
            $('#presion_arterial_x-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 250' + "</strong></p>");
            $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');
          }
        } else if(Validar.esVacio(value)){
            $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
            $('#presion_arterial_x-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
            $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');
        } 
});

$("#presion_arterial_x").on('input',function(event) { 
        var value = $('#presion_arterial_x').val();
        if (Validar.esVacio(value) ) {
            $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
            $('#presion_arterial_x-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
            $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');

        } else if (!Validar.enteroEnRango(value, 0, 250)) {
            $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
            $('#presion_arterial_x-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 250' + "</strong></p>");
            $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');
        }  
});

$("#presion_arterial_x").on('input',function() {
$('#presion_arterial_x').removeClass('borde-temperatura-hipertermia');
$('#presion_arterial_x-aceptado').html('');
});
//-------------------------------------------------------------------------------------------------

$("#presion_arterial_y").on('blur',function(event) {

      var value = $(this).val();
        if (Validar.esVacio(value) == false ) {
          if (!Validar.enteroEnRango(value, 0, 140)) {
            $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
            $('#presion_arterial_y-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 140' + "</strong></p>");
            $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');
          }
        } else if(Validar.esVacio(value)){
            $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
            $('#presion_arterial_y-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
            $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');
        } 
});

$("#presion_arterial_y").on('input',function(event) {
      
    var value = $('#presion_arterial_y').val();
    if (Validar.esVacio(value) ) {
            $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
            $('#presion_arterial_y-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
            $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');

        } else if (!Validar.enteroEnRango(value, 0, 140)) {
            $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
            $('#presion_arterial_y-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 140' + "</strong></p>");
            $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');
        }  
});

$("#presion_arterial_y").on('input',function() {
$('#presion_arterial_y').removeClass('borde-temperatura-hipertermia');
$('#presion_arterial_y-aceptado').html('');
});
//-------------------------------------------------------------------------------------------------

// Manejo del evento 'blur' para #frecuencia_respiratoria
$("#frecuencia_respiratoria").on('blur', function(event) {
    var value = $('#frecuencia_respiratoria').val();
    
    // Limpiar mensajes y estilos antes de aplicar nuevas validaciones
    $('#frecuencia_respiratoria').removeClass('borde-temperatura-hipertermia');
    $('#frecuencia_respiratoria-aceptado').html('');
    $('#frecuencia_respiratoria-aceptado strong').removeClass('borde-temperatura-valida');

    if (Validar.esVacio(value)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>Ingrese el valor de la Frecuencia Respiratoria ( /min )</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
    } else if (isNaN(value)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>El valor debe ser un número</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
    } else if (!Validar.enteroEnRango(value, 15, 35)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>El valor de la Frecuencia Respiratoria debe estar entre 15 y 35 ( /min )</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
    }
});

$("#frecuencia_respiratoria").on('input', function(event) {
    var value = $('#frecuencia_respiratoria').val();
    
    $('#frecuencia_respiratoria').removeClass('borde-temperatura-hipertermia');
    $('#frecuencia_respiratoria-aceptado').html('');
    $('#frecuencia_respiratoria-aceptado strong').removeClass('borde-temperatura-valida');

    if (Validar.esVacio(value)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>Ingrese el valor de la Frecuencia Respiratoria ( /min )</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
    } else if (isNaN(value)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>El valor debe ser un número</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
    } else if (!Validar.enteroEnRango(value, 15, 35)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>El valor de la Frecuencia Respiratoria debe estar entre 15 y 35 ( /min )</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
    }
});

// Limpiar mensajes y estilos al cambiar el valor
$("#frecuencia_respiratoria").on('input', function() {
    $('#frecuencia_respiratoria').removeClass('borde-temperatura-hipertermia');
    $('#frecuencia_respiratoria-aceptado').html('');
    $('#frecuencia_respiratoria-aceptado strong').removeClass('borde-temperatura-valida');
});
//-------------------------------------------------------------------------------------------------
$("#frec_cardiaca").on('blur', function(event) {
    var value = $('#frec_cardiaca').val();
    $('#frec_cardiaca').removeClass('borde-temperatura-hipertermia');
    $('#frec_cardiaca-aceptado').html('');
    $('#frec_cardiaca-aceptado strong').removeClass('borde-temperatura-valida');

    if (Validar.esVacio(value)) {
        $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html("<p><strong>Ingrese el valor de la Frecuencia Cardiaca ( /min )</strong></p>");
        $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    } else if (isNaN(value)) {
        $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html("<p><strong>El valor debe ser un número</strong></p>");
        $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    } else if (!Validar.enteroEnRango(value, 50, 150)) {
        $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html("<p><strong>El valor de la Frecuencia Cardiaca debe estar entre 50 y 150 ( /min )</strong></p>");
        $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    }
});

$("#frec_cardiaca").on('input', function(event) {
    var value = $('#frec_cardiaca').val();

    $('#frec_cardiaca').removeClass('borde-temperatura-hipertermia');
    $('#frec_cardiaca-aceptado').html('');
    $('#frec_cardiaca-aceptado strong').removeClass('borde-temperatura-valida');

    if (Validar.esVacio(value)) {
        $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html("<p><strong>Ingrese el valor de la Frecuencia Cardiaca ( /min )</strong></p>");
        $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    } else if (isNaN(value)) {
        $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html("<p><strong>El valor debe ser un número</strong></p>");
        $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    } else if (!Validar.enteroEnRango(value, 50, 150)) {
        $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html("<p><strong>El valor de la Frecuencia Cardiaca debe estar entre 50 y 150 ( /min )</strong></p>");
        $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    }
});
//-------------------------------------------------------------------------------------------------
$("#oxigenacion1").on('input', function() {
    var value = $(this).val();
    var errorMessage = "";

    // Limpiar el mensaje de error y estilos previos
    $('#oxigenacion1').removeClass('borde-temperatura-hipertermia');
    $('#oxigenacion1-aceptado').html('');

    // Verificar si el valor es numérico y está en el rango adecuado
    if (value === "" || isNaN(value)) {
        // Mostrar mensaje de error si el valor no es un número
        errorMessage = "Debe ser un número";
    } else {
        // Convertir el valor a número entero
        var numericValue = parseInt(value, 10);

        // Verificar si el valor está dentro del rango permitido
        if (numericValue < 21 || numericValue > 100) {
            errorMessage = "El valor debe estar entre 21 y 100";
        }
    }

    // Mostrar el mensaje de error si existe
    if (errorMessage) {
        $('#oxigenacion1').addClass('borde-temperatura-hipertermia');
        $('#oxigenacion1-aceptado').html("<p><strong>" + errorMessage + "</strong></p>");
        $('#oxigenacion1-aceptado strong').addClass('borde-temperatura-valida');
    }
});
//------------------------------------------------------------------------------------

  $("#oxigenacion").on('blur',function(event) {
           
      var value = $('#oxigenacion').val();
      if (Validar.esVacio(value) == false ){
        // if (!Validar.enteroEnRango(value, 21, 100)) {
        if(value.length==1) {
          $('#oxigenacion').addClass('borde-temperatura-hipertermia');
          $('#oxigenacion-aceptado').html("<p><strong>" + 'El valor de la fIO2 debe ser mayor a un dígito' + "</strong></p>");
          $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
        } 
      }else if (Validar.esVacio(value)) {
        $('#oxigenacion').addClass('borde-temperatura-hipertermia');
        $('#oxigenacion-aceptado').html("<p><strong>" + 'Ingrese el valor de la Oxigenacion ( % )' + "</strong></p>");
        $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
      } 
  });

  $("#oxigenacion").on('input',function(event) {
          
    var value = $('#oxigenacion').val();
      if (Validar.esVacio(value) ) {
          $('#oxigenacion').addClass('borde-temperatura-hipertermia');
          $('#oxigenacion-aceptado').html("<p><strong>" + 'Ingrese el valor de la Oxigenacion ( % )' + "</strong></p>");
          $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
      } else if (!Validar.enteroEnRango(value, 21, 100)) {
          $('#oxigenacion').addClass('borde-temperatura-hipertermia');
          $('#oxigenacion-aceptado').html("<p><strong>" + 'El valor de la fIO2 debe estar entre 21 a 100 ( % )' + "</strong></p>");
          $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
      }  
  });

$("#oxigenacion").on('input',function() {
  $('#oxigenacion').removeClass('borde-temperatura-hipertermia');
  $('#oxigenacion-aceptado').html('');
});
//-------------------------------------------------------------------------------------------------
}); 

$('#btnNota').on("click", function (e) {
guardarNota();  
}); 


function calcular_riesgo_caidas(){
var caida= parseInt($('#caida').val());
var medicamentos= parseInt($('#medicamentos').val());
var deficit= parseInt($('#deficit').val());
var estado_mental= parseInt($('#estado_mental').val());
var deambulacion= parseInt($('#deambulacion').val()); 

var riesgo_calculo=(caida+medicamentos+deficit+estado_mental+deambulacion);
$('#caida_calculado').val(riesgo_calculo);
$('#caida_calculo').val(riesgo_calculo);
}

// const checkbox_es_gestante = document.getElementById("checkbox_es_gestante");
// checkbox_es_gestante.addEventListener("click", function() {  
//   if (checkbox_es_gestante.checked) {    
//     $('#tamanio_de_cintura').show(); 
//     $('#frecuencia_fetal').show(); 
//     $('#seccion_gestante').show(); 
//     $('#es_gestante').val('1');  
//   }else{
//     $('#tamanio_de_cintura').hide(); 
//     $('#frecuencia_fetal').hide(); 
//     $('#seccion_gestante').hide(); 
//     $('#es_gestante').val('0'); 
//   } 
// });  

document.addEventListener('DOMContentLoaded', function() {
    var datojhon = [];
    var canvas = document.getElementById('plano');
    var ctx = canvas.getContext('2d');
    var tipo_color = document.getElementById('tipo_proceso');
    var otros_input = document.getElementById('otros_input');
    var puntos = [];
    var contadorPuntos = 0;
    var permitirDispositivos = true;

    function obtenerEtiqueta(color) {
        switch(color) {
            case 'blue':
                return 'Otros';
            case 'orange':
                return 'PIEL DIABÉTICO';
            case 'red': 
                return 'LPP';
            case 'green': 
                return 'DISPOSITIVOS';
            case 'yellow': 
                return 'DAI';  
            default:
                return 'Desconocido';
        }
    }

    function dibujarPuntos() {
        ctx.clearRect(0, 0, canvas.width, canvas.height); // Limpiar el canvas
        puntos.forEach(function(punto) {
            var etiqueta = obtenerEtiqueta(punto.color);
            var detalleotro = punto.detalleotro;

            if (etiqueta === 'Otros' && (detalleotro === null || detalleotro.trim() === '')) {
                return;
            }

            var textoEtiqueta = etiqueta;
            if (etiqueta === 'Otros') {
                textoEtiqueta = `${etiqueta}: ${detalleotro}`;
            }

            ctx.fillStyle = punto.color;
            ctx.beginPath();
            ctx.arc(punto.x, punto.y, 5, 0, 2 * Math.PI);
            ctx.fill();

            ctx.fillStyle = '#000000';
            ctx.font = '12px Arial';
            ctx.textAlign = 'left'; 
            ctx.textBaseline = 'middle';
            ctx.fillText(textoEtiqueta, punto.x + 10, punto.y);

            let yOffset = 15; // Inicializa el desplazamiento en y para las etiquetas adicionales

            // Solo mostrar las opciones de dispositivos si el tipo de proceso es "DISPOSITIVOS"
            if (punto.color === 'green') {
                if (punto.viaPeriferica === 'SI') {
                    ctx.fillText("VÍA PERIFÉRICA", punto.x + 10, punto.y + yOffset);
                    yOffset += 15;
                }

                if (punto.viaCentral === 'SI') {
                    ctx.fillText("VÍA CENTRAL", punto.x + 10, punto.y + yOffset);
                    yOffset += 15;
                }
                if (punto.caracterAltoFlujo === 'SI') {
                    ctx.fillText("CATÉTER ALTO FLUJO", punto.x + 10, punto.y + yOffset);
                    yOffset += 15;
                }
                if (punto.sondas === 'SI') {
                    ctx.fillText("SONDAS", punto.x + 10, punto.y + yOffset);
                    yOffset += 15; 
                    if (punto.descripcionSondas) {
                        ctx.fillText("Descripción: " + punto.descripcionSondas, punto.x + 10, punto.y + yOffset);
                        yOffset += 15;
                    }
                }
                if (punto.lineaArterial === 'SI') {
                    ctx.fillText("LÍNEA ARTERIAL", punto.x + 10, punto.y + yOffset);
                    yOffset += 15;
                }
                if (punto.drenaje === 'SI') {
                    ctx.fillText("DRENAJE", punto.x + 10, punto.y + yOffset);
                    yOffset += 15; 
                    if (punto.descripcionDrenaje) {
                        ctx.fillText("Descripción: " + punto.descripcionDrenaje, punto.x + 10, punto.y + yOffset);
                        yOffset += 15;
                    }
                }
            }
        });
    }

    function encontrarPunto(x, y) {
        return puntos.find(function(punto) {
            return Math.sqrt((punto.x - x) ** 2 + (punto.y - y) ** 2) < 10; 
        });
    }

    function actualizarDatos() {
        datojhon = puntos.map(punto => ({
            x: punto.x,
            y: punto.y,
            color: punto.color,
            detalleotro: punto.detalleotro,
            numero: punto.numero,
            viaPeriferica: punto.viaPeriferica,
            viaCentral: punto.viaCentral,
            caracterAltoFlujo: punto.caracterAltoFlujo,
            sondas: punto.sondas,
            descripcionSondas: punto.descripcionSondas,
            lineaArterial: punto.lineaArterial,
            drenaje: punto.drenaje,
            descripcionDrenaje: punto.descripcionDrenaje
        }));
        document.getElementById('datojhon').value = JSON.stringify(datojhon);
    }

    canvas.addEventListener('click', function(event) {
        var rect = canvas.getBoundingClientRect();
        var x = event.clientX - rect.left;
        var y = event.clientY - rect.top;
        var valorSeleccionado = tipo_color.value;
        var detalleotro = otros_input.value.trim();
        var viaPerifericaSeleccionada = document.querySelector('input[name="via_periferica"]:checked').value;
        var viaCentralSeleccionada = document.querySelector('input[name="via_central"]:checked').value;
        var caracterAltoFlujoSeleccionado = document.querySelector('input[name="caracter_alto_flujo"]:checked').value;
        var sondasSeleccionado = document.querySelector('input[name="sondas"]:checked').value;
        var descripcionSondas = document.getElementById('descripcion_sonda').value.trim();
        var lineaArterialSeleccionada = document.querySelector('input[name="linea_arterial"]:checked').value;
        var drenajeSeleccionado = document.querySelector('input[name="drenaje"]:checked').value;
        var descripcionDrenaje = document.getElementById('descripcion_drenaje').value.trim();

        if (!permitirDispositivos && valorSeleccionado === 'green') {
            alert('NO se puede seleccionar "DISPOSITIVOS" mientras esté marcado "NO" en Dispositivos Invasivos.');
            return;
        }

        var puntoSeleccionado = encontrarPunto(x, y);

        if (puntoSeleccionado) {
            puntos = puntos.filter(punto => punto !== puntoSeleccionado); // Eliminar el punto seleccionado
            contadorPuntos--; // Disminuir el contador de puntos
            dibujarPuntos(); // Volver a dibujar los puntos
            actualizarDatos(); // Actualizar los datos
        } else {
            var etiqueta = obtenerEtiqueta(valorSeleccionado);

            if (etiqueta === 'Otros' && (detalleotro === null || detalleotro === '')) {
                alert('POR FAVOR, DETALLE EL OTRO PROCESO SELECCIONADO.');
                return; 
            }
      
            contadorPuntos++; // Incrementar el contador de puntos
        
            var nuevoPunto = {
                x: x,
                y: y,
                color: valorSeleccionado,
                detalleotro: detalleotro,
                numero: contadorPuntos,
                viaPeriferica: valorSeleccionado === 'green' ? viaPerifericaSeleccionada : undefined,
                viaCentral: viaCentralSeleccionada === 'SI' ? viaCentralSeleccionada : undefined,
                caracterAltoFlujo: caracterAltoFlujoSeleccionado === 'SI' ? caracterAltoFlujoSeleccionado : undefined,
                sondas: sondasSeleccionado === 'SI' ? sondasSeleccionado : undefined,
                descripcionSondas: sondasSeleccionado === 'SI' ? descripcionSondas : undefined,
                lineaArterial: lineaArterialSeleccionada === 'SI' ? lineaArterialSeleccionada : undefined,
                drenaje: drenajeSeleccionado === 'SI' ? drenajeSeleccionado : undefined,
                descripcionDrenaje: drenajeSeleccionado === 'SI' ? descripcionDrenaje : undefined
            };

            puntos.push(nuevoPunto); // Agregar el nuevo punto sin eliminar los anteriores

            dibujarPuntos(); // Volver a dibujar los puntos
            actualizarDatos(); // Actualizar los datos
        }
    });

    document.querySelectorAll('input[name="dispositivos_invasivos"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (document.getElementById('dispositivos_invasivos2').checked) {
                permitirDispositivos = false;
            } else {
                permitirDispositivos = true;
            }
        });
    });
});



function guardarNota(){
//VALIDACION DE CAMPOS OBLIGATORIOS EN SECCION SIGNOS VITALES
// var presion_arterial_x = $('#presion_arterial_x').val();
// var presion_arterial_y = $('#presion_arterial_y').val();
// //var frec_cardiaca = $('#frec_cardiaca').val();
// //var frecuencia_respiratoria = $('#frecuencia_respiratoria').val();
// var oxigenacion = $('#oxigenacion').val();
// var sa02_porcentaje = $('#sa02_porcentaje').val();
// var temperatura = $('#temperatura').val();
// se comenta porqu3e estos campos no seran OBLIGATORIOS SEGUN ACUERDO CON LAS ENFERMERAS TALLA Y PESO
// var talla = $('#talla').val();
// var peso = $('#peso').val();
//var tamanio_cintura = $('#tamanio_cintura').val();
// var frecuencia_cardiaca_fetal = $('#frecuencia_cardiaca_fetal').val();

if(presion_arterial_x == "0" || presion_arterial_x == '' || presion_arterial_x == '*' || presion_arterial_x.length==0){
swal({
  buttons: false,
  title: "Mensaje de Error",
  text: "Recuerde completar este campo con datos válidos.",
  icon: "error",
  timer: 2500,
});    
cambiar_de_PestanaTab("tab0default");
$('#presion_arterial_x').addClass('borde-alerta'); 
setTimeout(function() {
  $('#presion_arterial_x').focus(); 
}, 1000);
return;
}else{
$('#presion_arterial_x').removeClass('borde-alerta');
}

if(presion_arterial_y == "0" || presion_arterial_y == '' || presion_arterial_y == '*' || presion_arterial_y.length==0){
swal({
  buttons: false,
  title: "Mensaje de Error",
  text: "Recuerde completar este campo con datos válidos.",
  icon: "error",
  timer: 2500,
}); 
cambiar_de_PestanaTab("tab0default");   
$('#presion_arterial_y').addClass('borde-alerta'); 
setTimeout(function() {
  $('#presion_arterial_y').focus(); 
}, 1000);
return;
}else{
$('#presion_arterial_y').removeClass('borde-alerta');
}

// if(frec_cardiaca == "0" || frec_cardiaca == '1' || frec_cardiaca.length>3){
//   swal({
//     buttons: false,
//     title: "Mensaje de Error",
//     text: "Recuerde completar este campo con datos válidos.",
//     icon: "error",
//     timer: 2500,
//   });    
//   cambiar_de_PestanaTab("tab0default");
//   $('#frec_cardiaca').addClass('borde-alerta'); 
//   setTimeout(function() {
//     $('#frec_cardiaca').focus(); 
//   }, 1000);
//   return;
// }else{
//   $('#frec_cardiaca').removeClass('borde-alerta');
// }

// if(frecuencia_respiratoria == "0" || frecuencia_respiratoria == '' || frecuencia_respiratoria == '*' || frecuencia_respiratoria.length==0){
//   swal({
//     buttons: false,
//     title: "Mensaje de Error",
//     text: "Recuerde completar este campo con datos válidos.",
//     icon: "error",
//     timer: 2500,
//   });  
//   cambiar_de_PestanaTab("tab0default");  
//   $('#frecuencia_respiratoria').addClass('borde-alerta'); 
//   setTimeout(function() {
//     $('#frecuencia_respiratoria').focus(); 
//   }, 1000);
//   return;
// }else{
//   $('#frecuencia_respiratoria').removeClass('borde-alerta');
// }

// if(oxigenacion == "0" || oxigenacion == '' || oxigenacion == '*' || oxigenacion.length==0){
//   swal({
//     buttons: false,
//     title: "Mensaje de Error",
//     text: "Recuerde completar este campo con datos válidos.",
//     icon: "error",
//     timer: 2500,
//   });    
//   cambiar_de_PestanaTab("tab0default");
//   $('#oxigenacion').addClass('borde-alerta'); 
//   setTimeout(function() {
//     $('#oxigenacion').focus(); 
//   }, 1000);
//   return;
// }else{
//   $('#oxigenacion').removeClass('borde-alerta');
// }

// if(sa02_porcentaje == "0" || sa02_porcentaje == '' || sa02_porcentaje == '*' || sa02_porcentaje.length==0){
//   swal({
//     buttons: false,
//     title: "Mensaje de Error",
//     text: "Recuerde completar este campo con datos válidos.",
//     icon: "error",
//     timer: 2500,
//   }); 
//   cambiar_de_PestanaTab("tab0default");   
//   $('#sa02_porcentaje').addClass('borde-alerta'); 
//   setTimeout(function() {
//     $('#sa02_porcentaje').focus(); 
//   }, 1000);
//   return;
// }else{
//   $('#sa02_porcentaje').removeClass('borde-alerta');
// }

if(temperatura == "0" || temperatura == '' || temperatura == '*' || temperatura.length==0){
swal({
  buttons: false,
  title: "Mensaje de Error",
  text: "Recuerde completar este campo con datos válidos.",
  icon: "error",
  timer: 2500,
});  
cambiar_de_PestanaTab("tab0default");  
$('#temperatura').addClass('borde-alerta'); 
setTimeout(function() {
  $('#temperatura').focus(); 
}, 1000);
return;
}else{
$('#temperatura').removeClass('borde-alerta');
}


if(talla == "0" || talla == '' || talla == '*' || talla.length==0){
swal({
  buttons: false,
  title: "Mensaje de Error",
  text: "Recuerde completar este campo con datos válidos.",
  icon: "error",
  timer: 2500,
});  
cambiar_de_PestanaTab("tab0default");  
$('#talla').addClass('borde-alerta'); 
setTimeout(function() {
  $('#talla').focus(); 
}, 1000);
return;
}else{
$('#talla').removeClass('borde-alerta');
}

if(peso == "0" || peso == '' || peso == '*' || peso.length==0){
swal({
  buttons: false,
  title: "Mensaje de Error",
  text: "Recuerde completar este campo con datos válidos.",
  icon: "error",
  timer: 2500,
}); 
cambiar_de_PestanaTab("tab0default");   
$('#peso').addClass('borde-alerta'); 
setTimeout(function() {
  $('#peso').focus(); 
}, 1000);
return;
}else{
$('#peso').removeClass('borde-alerta');
}

// const checkbox_es_gestante = document.getElementById("checkbox_es_gestante");
// if (checkbox_es_gestante.checked) {      
//   if(tamanio_cintura == "0" || tamanio_cintura == '' || tamanio_cintura == '*' || tamanio_cintura.length==0){
//     swal({
//       buttons: false,
//       title: "Mensaje de Error",
//       text: "Recuerde completar este campo con datos válidos.",
//       icon: "error",
//       timer: 2500,
//     });    
//     $('#tamanio_cintura').addClass('borde-alerta'); 
//     setTimeout(function() {
//       $('#tamanio_cintura').focus(); 
//     }, 1000);
//     return;
//   }else{
//     $('#tamanio_cintura').removeClass('borde-alerta');
//   }
//   if(frecuencia_cardiaca_fetal == "0" || frecuencia_cardiaca_fetal == '' || frecuencia_cardiaca_fetal == '*' || frecuencia_cardiaca_fetal.length==0){
//     swal({
//       buttons: false,
//       title: "Mensaje de Error",
//       text: "Recuerde completar este campo con datos válidos.",
//       icon: "error",
//       timer: 2500,
//     });  
//     cambiar_de_PestanaTab("tab0default");  
//     $('#frecuencia_cardiaca_fetal').addClass('borde-alerta'); 
//     setTimeout(function() {
//       $('#frecuencia_cardiaca_fetal').focus(); 
//     }, 1000);
//     return;
//   }else{
//     $('#frecuencia_cardiaca_fetal').removeClass('borde-alerta');
//   }
// }
//FIN VALIDACION DE CAMPOS OBLIGATORIOS EN SECCION SIGNOS VITALES

//ANTECEDENTES NOTA DE INGRESO
var hta = $('input:radio[name=hta]:checked').val()

/* var descripcion_ao = $('#descripcion_antecedente_observacion').val();
var descripcion_otros_antecedentes = $('#descripcion_otros_antecedentes').val(); */


if(hta == 'SI'){ 
var tratamiento_hta= $('#tratamiento_hta').val();
if(tratamiento_hta==''){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Ha seleccionado que cuenta con HTA, recuerde ingresar la descripción del TRATAMIENTO.",
    icon: "error",
    timer: 2500,
  });  
  cambiar_de_PestanaTab("tab1default");
  $('#tratamiento_hta').addClass('borde-alerta');

  setTimeout(function() {
    $('#tratamiento_hta').focus();  
  }, 1000);

  return;
}else{
  $('#tratamiento_hta').removeClass('borde-alerta');
}
}else{
$('#tratamiento_hta').removeClass('borde-alerta');
}

var diabetes_mellitus = $('input:radio[name=diabetes_mellitus]:checked').val()
if(diabetes_mellitus == 'SI'){ 
var diabetes_mellitus_descripcion= $('#diabetes_mellitus_descripcion').val();
if(diabetes_mellitus_descripcion==''){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Ha seleccionado que cuenta con DM, recuerde ingresar la descripción deL tratamiento.",
    icon: "error",
    timer: 2500,
  });  
  cambiar_de_PestanaTab("tab1default");
  $('#diabetes_mellitus_descripcion').addClass('borde-alerta');
  
  setTimeout(function() {
    $('#diabetes_mellitus_descripcion').focus(); 
  }, 1000);

  return;
}else{
  $('#diabetes_mellitus_descripcion').removeClass('borde-alerta');
}
}else{
$('#diabetes_mellitus_descripcion').removeClass('borde-alerta');
}

var ram = $('input:radio[name=ram]:checked').val()
if(ram == 'SI'){ 
var detalle_ram_alergia= $('#detalle_ram_alergia').val();
if(detalle_ram_alergia==''){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Ha seleccionado que cuenta con ALERGIAS, recuerde ingresar la descripción de DETALLE (ALERGIA).",
    icon: "error",
    timer: 2500,
  });  
  cambiar_de_PestanaTab("tab1default");
  $('#detalle_ram_alergia').addClass('borde-alerta');
  
  setTimeout(function() {
    $('#detalle_ram_alergia').focus(); 
  }, 1000);

  return;
}else{
  $('#detalle_ram_alergia').removeClass('borde-alerta');
}
}else{
$('#detalle_ram_alergia').removeClass('borde-alerta');
}



// SECION HEMODIALISIS


var ram_hemodialisis = $('input:radio[name=ram_hemodialisis]:checked').val()
if(ram_hemodialisis == 'SI'){ 
var detalle_ram_hemo= $('#detalle_ram_hemo').val();
if(detalle_ram_hemo==''){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Ha seleccionado que cuenta con ALERGIAS, recuerde ingresar la descripción de DETALLE (ALERGIA).",
    icon: "error",
    timer: 2500,
  });  
  cambiar_de_PestanaTab("tab1default");
  $('#detalle_ram_hemo').addClass('borde-alerta');
  
  setTimeout(function() {
    $('#detalle_ram_hemo').focus(); 
  }, 1000);

  return;
}else{
  $('#detalle_ram_hemo').removeClass('borde-alerta');
}
}else{
$('#detalle_ram_hemo').removeClass('borde-alerta');
}




var cardiologico = $('#cardiologico').val(); 
/*if( cardiologico == null){
swal({
  buttons: false,
  title: "Mensaje de Error",
  text: "Recuerde seleccionar un TIPO CARDIOLÓGICO válido.",
  icon: "error",
  timer: 2500,
});    
cambiar_de_PestanaTab("tab1default"); 
  
    // Agrega la clase de borde de alerta
    $('.select2-selection--multiple').addClass('borde-alerta');
  

setTimeout(function() {
  $('#cardiologico').focus(); 
}, 1000);
return;
}else{
// $('#cardiologico').removeClass('borde-alerta');
$('.select2-selection--multiple').removeClass('borde-alerta');
}
*/
/* if(descripcion_ao == "0" || descripcion_ao == '' || descripcion_ao == '*' || descripcion_ao.length==0){
swal({
  buttons: false,
  title: "Mensaje de Error",
  text: "Recuerde completar este campo con datos válidos.",
  icon: "error",
  timer: 2500,
});  
cambiar_de_PestanaTab("tab1default");  
$('#descripcion_antecedente_observacion').addClass('borde-alerta'); 
setTimeout(function() {
  $('#descripcion_antecedente_observacion').focus(); 
}, 1000);
return;
}else{
$('#descripcion_antecedente_observacion').removeClass('borde-alerta');
} */

/* if(descripcion_otros_antecedentes == "0" || descripcion_otros_antecedentes == '' || descripcion_otros_antecedentes == '*' || descripcion_otros_antecedentes.length==0){
swal({
  buttons: false,
  title: "Mensaje de Error",
  text: "Recuerde completar este campo con datos válidos.",
  icon: "error",
  timer: 2500,
});  
cambiar_de_PestanaTab("tab1default");  
$('#descripcion_otros_antecedentes').addClass('borde-alerta'); 
setTimeout(function() {
  $('#descripcion_otros_antecedentes').focus(); 
}, 1000);
return;
}else{
$('#descripcion_otros_antecedentes').removeClass('borde-alerta');
} */

var condicion_ingreso = $('#condicion_ingreso').val(); 
if( condicion_ingreso == "0" || condicion_ingreso == '' || condicion_ingreso == '*' || condicion_ingreso.length==0){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Recuerde seleccionar una CONDICIÓN DE INGRESO válido.",
    icon: "error",
    timer: 2500,
  });    
  cambiar_de_PestanaTab("tab2default"); 
  $('#condicion_ingreso').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#condicion_ingreso').focus(); 
    }, 1000);
  return;
}else{
  $('#condicion_ingreso').next('.select2-container').removeClass('borde-alerta');
}

var estado_conciencia = $('#estado_conciencia').val(); 
if( estado_conciencia == "0" || estado_conciencia == '' || estado_conciencia == '*' || estado_conciencia.length==0){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Recuerde seleccionar un ESTADO DE CONCIENCIA válido.",
    icon: "error",
    timer: 2500,
  });    
  cambiar_de_PestanaTab("tab2default"); 
  $('#estado_conciencia').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#estado_conciencia').focus(); 
    }, 1000);
  return;
} else{
  $('#estado_conciencia').next('.select2-container').removeClass('borde-alerta');
}

var orientado = $('#orientado').val();
if($('#orientado').is(':checked') && orientado=='SI'){
  var vehicle1 = $("#vehicle1").is(":checked");
  var vehicle2 = $("#vehicle2").is(":checked");
  var vehicle3 = $("#vehicle3").is(":checked");

    if (!vehicle1 && !vehicle2 && !vehicle2 && orientado!='NO') {
          swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "Recuerde seleccionar mínimo un detalle.",
              icon: "error",
              timer: 2500,
            });    
            cambiar_de_PestanaTab("tab2default"); 
            $('#detalle').addClass('borde-alerta');
            //$('#vehicle1').addClass('borde-alerta');
              setTimeout(function() {
                $('#vehicle1').focus(); 
              }, 1000);
            return;
    } else{
        $('#detalle').removeClass('borde-alerta');
        //$('#vehicle1').removeClass('borde-alerta');
    }
    
}



var grado_dependencia = $('#grado_dependencia').val(); 
if( grado_dependencia == "0" || grado_dependencia == '' || grado_dependencia == '*' || grado_dependencia.length==0){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Recuerde seleccionar un GRADO DE DEPENDENCIA válido.",
    icon: "error",
    timer: 2500,
  });    
  cambiar_de_PestanaTab("tab2default"); 
  $('#grado_dependencia').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#grado_dependencia').focus(); 
    }, 1000);
  return;
} else{
  $('#grado_dependencia').next('.select2-container').removeClass('borde-alerta');
} 

var funcion_motora = $('#funcion_motora').val(); 
if( funcion_motora == "0" || funcion_motora == '' || funcion_motora == '*' || funcion_motora.length==0){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Recuerde seleccionar una FUNCIÓN MOTORA válida.",
    icon: "error",
    timer: 2500,
  });    
  cambiar_de_PestanaTab("tab2default"); 
  $('#funcion_motora').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#funcion_motora').focus(); 
    }, 1000);
  return;
} else{
  $('#funcion_motora').next('.select2-container').removeClass('borde-alerta');
}

var respiratorio_duracion = $('#respiratorio_duracion').val(); 
if( respiratorio_duracion == "0" || respiratorio_duracion == '' || respiratorio_duracion == '*' || respiratorio_duracion.length==0){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Recuerde seleccionar una FUNCIÓN RESPIRATORIA válida.",
    icon: "error",
    timer: 2500,
  });    
  cambiar_de_PestanaTab("tab2default"); 
  $('#respiratorio_duracion').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#respiratorio_duracion').focus(); 
    }, 1000);
  return;
}else{
  $('#respiratorio_duracion').next('.select2-container').removeClass('borde-alerta');
} 

var oxigenoterapia = $('input:radio[name=oxigenoterapia]:checked').val();
console.log(oxigenoterapia);
if(oxigenoterapia == 'SI'){  
  var oxigenoterapia_detalle_seleccion= $('#oxigenoterapia_detalle_seleccion').val();
  console.log('oxigenoterapia_detalle_seleccion: '+oxigenoterapia_detalle_seleccion);
  if( oxigenoterapia_detalle_seleccion == "0" || oxigenoterapia_detalle_seleccion == '' || oxigenoterapia_detalle_seleccion == '*' ||
   oxigenoterapia_detalle_seleccion.length==0){
    swal({
      buttons: false,
      title: "Mensaje de Error",
      text: "Ha seleccionado que cuenta con OXIGENACIÓN, recuerde seleccionar el (DETALLE (OXIGENOTERAPIA)).",
      icon: "error",
      timer: 2500,
    });  
    cambiar_de_PestanaTab("tab2default"); 
    $('#oxigenoterapia_detalle_seleccion').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#oxigenoterapia_detalle_seleccion').focus(); 
    }, 1000);
    return;
  }else{ 
    $('#oxigenoterapia_detalle_seleccion').next('.select2-container').removeClass('borde-alerta');
  }

}else{ 
  $('#oxigenoterapia_detalle_seleccion').next('.select2-container').removeClass('borde-alerta');
 // $('#descripcion_oxigenacion').removeClass('borde-alerta');
}



var miccion_espontanea = $('input:radio[name=miccion_espontanea]:checked').val()
if(miccion_espontanea == 'SI'){  
  var miccion_espontanea_tratamiento= $('#miccion_espontanea_tratamiento').val();
  if(miccion_espontanea_tratamiento==''){
    swal({
      buttons: false,
      title: "Mensaje de Error",
      text: "Ha seleccionado que cuenta con MICCIÓN ESPONTANEA, recuerde ingresar la descripción de DETALLE (TRATAMIENTO RENAL).",
      icon: "error",
      timer: 2500,
    });  
    cambiar_de_PestanaTab("tab2default"); 
    $('#miccion_espontanea_tratamiento').addClass('borde-alerta');
    setTimeout(function() {
      $('#miccion_espontanea_tratamiento').focus(); 
    }, 1000); 
    return;
  }else{
    $('#miccion_espontanea_tratamiento').removeClass('borde-alerta');
  }

  // var miccion_espontanea_hd= $('#miccion_espontanea_hd').val();
  // if(miccion_espontanea_hd==''){
  //   swal({
  //     buttons: false,
  //     title: "Mensaje de Error",
  //     text: "Ha seleccionado que cuenta con MICCIÓN ESPONTANEA, recuerde ingresar la descripción de DETALLE (HD).",
  //     icon: "error",
  //     timer: 2500,
  //   });  
  //   cambiar_de_PestanaTab("tab2default"); 
  //   $('#miccion_espontanea_hd').addClass('borde-alerta');
  //   setTimeout(function() {
  //     $('#miccion_espontanea_hd').focus(); 
  //   }, 1000); 
  //   return;
  // }else{
  //   $('#miccion_espontanea_hd').removeClass('borde-alerta');
  // }

  var miccion_espontanea_fav= $('#miccion_espontanea_fav').val();
  if(miccion_espontanea_fav==''){
    swal({
      buttons: false,
      title: "Mensaje de Error",
      text: "Ha seleccionado que cuenta con MICCIÓN ESPONTANEA, recuerde ingresar la descripción de DETALLE (FAV).",
      icon: "error",
      timer: 2500,
    });  
    cambiar_de_PestanaTab("tab2default"); 
    $('#miccion_espontanea_fav').addClass('borde-alerta');
    setTimeout(function() {
      $('#miccion_espontanea_fav').focus(); 
    }, 1000); 
    return;
  }else{
    $('#miccion_espontanea_fav').removeClass('borde-alerta');
  } 
}else{
  $('#miccion_espontanea_tratamiento').removeClass('borde-alerta');
  $('#miccion_espontanea_hd').removeClass('borde-alerta');
  $('#miccion_espontanea_fav').removeClass('borde-alerta');
}

var  acompaniado_por = $("#acompaniado_por2").val();
if($('#acompaniado_por2').is(':checked') && acompaniado_por=='FAMILIAR'){
 
    console.log('acompaniado_por');
    var descripcion_acompaniado_por= $('#descripcion_acompaniado_por').val();
    if(descripcion_acompaniado_por==''){
      swal({
        buttons: false,
        title: "Mensaje de Error",
        text: "Recuerde ingresar la DESCRIPCIÓN DEL ACOMPAÑANTE válido.",
        icon: "error",
        timer: 2500,
      });  
      cambiar_de_PestanaTab("tab2default"); 
      $('#descripcion_acompaniado_por').addClass('borde-alerta');
        setTimeout(function() {
          $('#descripcion_acompaniado_por').focus(); 
        }, 1000); 
      return;
    }else{
      $('#descripcion_acompaniado_por').removeClass('borde-alerta');
    }
  
}


//! YMC
// var puntaje_calculado_glasgow= parseInt($('#puntaje_calculado_glasgow').val());
// if(!puntaje_calculado_glasgow){
//   swal({
//     buttons: false,
//     title: "Mensaje de Error",
//     text: "Recuerde realizar el cálculo DE LA ESCALA DE GLASGOW válida.",
//     icon: "error",
//     timer: 2500,
//   });  
//   cambiar_de_PestanaTab("tab5default"); 
//   $('#puntaje_calculado_glasgow').addClass('borde-alerta');
//     setTimeout(function() {
//       $('#puntaje_calculado_glasgow').focus(); 
//     }, 1000); 
//   return;
// }else{
//   $('#puntaje_calculado_glasgow').removeClass('borde-alerta');
// } 
/* var descripcion_detalle_glasgow= $('#descripcion_detalle_glasgow').val();
if(descripcion_detalle_glasgow===''){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Recuerde realizar el cálculo DE LA ESCALA DE GLASGOW válida.",
    icon: "error",
    timer: 2500,
  });  
  cambiar_de_PestanaTab("tab2default"); 
  $('#puntaje_calculado_glasgow').addClass('borde-alerta');
    setTimeout(function() {
      $('#descripcion_detalle_glasgow').focus(); 
    }, 1000); 
  return;
}else{
  $('#puntaje_calculado_glasgow').removeClass('borde-alerta');
} */

//! YMC
// calculando valortotal LPP
// var puntaje_calculado_lpp= $('#puntaje_calculado_lpp').val();
//     if(puntaje_calculado_lpp==''){
//       swal({
//         buttons: false,
//         title: "Mensaje de Error",
//         text: "Recuerde realizar el cálculo DE LA ESCALA DE LPP válida.",
//         icon: "error",
//         timer: 2500,
//       });  
//       cambiar_de_PestanaTab("tab5default"); 
//       $('#puntaje_calculado_lpp').addClass('borde-alerta');
//         setTimeout(function() {
//           $('#puntaje_calculado_lpp').focus(); 
//         }, 1000); 
//       return;
//     }else{
//       $('#puntaje_calculado_lpp').removeClass('borde-alerta');
//     } 

//! YMC
// calculando DE LPP 
// var descripcion_detalle_riesgolpp= $('#descripcion_detalle_riesgolpp').val();
// if(descripcion_detalle_riesgolpp==''){
//   swal({
//     buttons: false,
//     title: "Mensaje de Error",
//     text: "Recuerde realizar el cálculo DE LA ESCALA DE detalle riesgo lpp válida.",
//     icon: "error",
//     timer: 2500,
//   });  
//   cambiar_de_PestanaTab("tab2default"); 
//   $('#puntaje_calculado_lpp').addClass('borde-alerta');
//     setTimeout(function() {
//       $('#descripcion_detalle_riesgolpp').focus(); 
//     }, 1000); 
//   return;
// }else{
//   $('#puntaje_calculado_lpp').removeClass('borde-alerta');
// }






  var piel_colores = $('#piel_colores').val(); 
  /*if( piel_colores == "0" || piel_colores == '' || piel_colores == '*' || piel_colores.length==0){
    swal({
      buttons: false,
      title: "Mensaje de Error",
      text: "Recuerde seleccionar un TIPO PIEL (Color) válido.",
      icon: "error",
      timer: 2500,
    });    
    cambiar_de_PestanaTab("tab2default"); 
    $('#piel_colores').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#piel_colores').focus(); 
    }, 1000);
    return;
  }  else{
    $('#piel_colores').next('.select2-container').removeClass('borde-alerta');
  }

  var integridad = $('#integridad').val(); 
  if( integridad == "0" || integridad == '' || integridad == '*' || integridad.length==0){
    swal({
      buttons: false,
      title: "Mensaje de Error",
      text: "Recuerde seleccionar un TIPO Integridad válido.",
      icon: "error",
      timer: 2500,
    });    
    cambiar_de_PestanaTab("tab2default"); 
    $('#integridad').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#integridad').focus(); 
    }, 1000);
    return;
  }  else{
    $('#integridad').next('.select2-container').removeClass('borde-alerta');
  }

  var abdomen = $('#abdomen').val(); 
  if( abdomen == "0" || abdomen == '' || abdomen == '*' || abdomen.length==0){
    swal({
      buttons: false,
      title: "Mensaje de Error",
      text: "Recuerde seleccionar un TIPO Abdomen válido.",
      icon: "error",
      timer: 2500,
    });    
    cambiar_de_PestanaTab("tab2default"); 
    $('#abdomen').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#abdomen').focus(); 
    }, 1000);
    return;
  }  else{
    $('#abdomen').next('.select2-container').removeClass('borde-alerta');
  }

  var  evaluacion_mallampaty1 = $("#evaluacion_mallampaty1").val();
if($('#evaluacion_mallampaty1').is(':checked') && evaluacion_mallampaty1=='SI'){
  var evaluacion_mallampaty_seleccion = $('#evaluacion_mallampaty_seleccion').val(); 
  if( evaluacion_mallampaty_seleccion == "0" || evaluacion_mallampaty_seleccion == '' || evaluacion_mallampaty_seleccion == '*' || evaluacion_mallampaty_seleccion.length==0){
    swal({
      buttons: false,
      title: "Mensaje de Error",
      text: "Recuerde seleccionar un TIPO EVALUACIÓN MALLAMPATY válido.",
      icon: "error",
      timer: 2500,
    });    
    cambiar_de_PestanaTab("tab2default"); 
    $('#evaluacion_mallampaty_seleccion').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#evaluacion_mallampaty_seleccion').focus(); 
    }, 1000);
    return;
  }  else{
    $('#evaluacion_mallampaty_seleccion').next('.select2-container').removeClass('borde-alerta');
  }
}
*/

var dispositivos_invasivos = $('input:radio[name=dispositivos_invasivos]:checked').val();
if(dispositivos_invasivos == 'SI'){  
  var sondas = $('input:radio[name=sondas]:checked').val()
  if(sondas == 'SI'){  
    var descripcion_sonda= $('#descripcion_sonda').val();
    if(descripcion_sonda==''){
      swal({
        buttons: false,
        title: "Mensaje de Error",
        text: "Recuerde ingresar una DESCRIPCIÓN SONDAS válida.",
        icon: "error",
        timer: 2500,
      });  
      cambiar_de_PestanaTab("tab4default"); 
      $('#descripcion_sonda').addClass('borde-alerta');
      setTimeout(function() {
        $('#descripcion_sonda').focus(); 
      }, 1000); 
      return;
    }else{
      $('#descripcion_sonda').removeClass('borde-alerta');
    }
  }else{
    $('#descripcion_sonda').removeClass('borde-alerta');
  }

  
  var drenaje = $('input:radio[name=drenaje]:checked').val()
  if(drenaje == 'SI'){  
    var descripcion_drenaje= $('#descripcion_drenaje').val();
    if(descripcion_drenaje==''){
      swal({
        buttons: false,
        title: "Mensaje de Error",
        text: "Recuerde ingresar una DESCRIPCIÓN DRENAJES válida.",
        icon: "error",
        timer: 2500,
      });  
      cambiar_de_PestanaTab("tab4default"); 
      $('#descripcion_drenaje').addClass('borde-alerta');
      setTimeout(function() {
        $('#descripcion_drenaje').focus(); 
      }, 1000); 
      return;
    }else{
      $('#descripcion_drenaje').removeClass('borde-alerta');
    }
  }else{
    $('#descripcion_drenaje').removeClass('borde-alerta');
  }

  var evaluacion_mallampaty = $('input:radio[name=evaluacion_mallampaty]:checked').val()
  if(evaluacion_mallampaty == 'SI'){  
    var evaluacion_mallampaty_seleccion= $('#evaluacion_mallampaty_seleccion').val();
    if(evaluacion_mallampaty_seleccion==''){
      swal({
        buttons: false,
        title: "Mensaje de Error",
        text: "Recuerde seleccionar una EVALUACIÓN MALLAMPATY válida.",
        icon: "error",
        timer: 2500,
      });   
      cambiar_de_PestanaTab("tab4default"); 
      $('#evaluacion_mallampaty_seleccion').next('.select2-container').addClass('borde-alerta');
      setTimeout(function() {
        $('#evaluacion_mallampaty_seleccion').focus(); 
      }, 1000);
      return;
    } else{
      $('#evaluacion_mallampaty_seleccion').next('.select2-container').removeClass('borde-alerta');
    } 
  } else{
    $('#evaluacion_mallampaty_seleccion').next('.select2-container').removeClass('borde-alerta');
  } 
}else{
  $('#descripcion_sonda').removeClass('borde-alerta');
  $('#descripcion_drenaje').removeClass('borde-alerta');
  $('#evaluacion_mallampaty_seleccion').next('.select2-container').removeClass('borde-alerta');
}

  
var brazalete = $('input:radio[name=brazalete]:checked').val()
if(brazalete == 'SI'){  
var brazalete_seleccion= $('#brazalete_seleccion').val();
if(brazalete_seleccion==''||brazalete_seleccion=='*'||brazalete_seleccion=='0'){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Recuerde seleccionar un COLOR DE PULSERAS válida.",
    icon: "error",
    timer: 2500,
  });  
  cambiar_de_PestanaTab("tab5default"); 
  $('#brazalete_seleccion').next('.select2-container').addClass('borde-alerta');
      setTimeout(function() {
        $('#brazalete_seleccion').focus(); 
      }, 1000);
  return;
}else{
  $('#brazalete_seleccion').next('.select2-container').removeClass('borde-alerta');
}
}else{
$('#brazalete_seleccion').next('.select2-container').removeClass('borde-alerta');
}
/* var tipo_proceso = $('#tipo_proceso').val();
if(tipo_proceso == "0" || tipo_proceso == '' || tipo_proceso == '*' || tipo_proceso.length==0){
swal({
  buttons: false,
  title: "Mensaje de Error",
  text: "Recuerde seleccionar un tipo de Proceso válido.",
  icon: "error",
  timer: 2500,
});  
cambiar_de_PestanaTab("tab4default");  
$('#tipo_proceso').next('.select2-container').addClass('borde-alerta'); 
setTimeout(function() {
  $('#tipo_proceso').focus(); 
}, 1000);
return;
}else{
$('#tipo_proceso').next('.select2-container').removeClass('borde-alerta');
} */


//cambiar_de_PestanaTab("tab4default");

setTimeout(function() {
capturarContenidoCelda('celda1'); 
}, 1500);

swal({
buttons: false,
title: "Proceso de Registro",
text: "Espere mientras se genera la vista previa de registro de la Nota de nfermería.",
icon: "success",
timer: 2000,
}); 

setTimeout(function() {
$("#frmNotaIngreso").submit();
}, 2500);

} 

function soloNumerosMenosCero(e){ 
var key = window.Event ? e.which : e.keyCode;
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
// Función para actualizar el valor del segundo input
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
});

// Obtener el valor oculto de presion_arterial_x
// let presionArterialXHiddenValue = document.getElementById('presion_arterial_x_hidden').value;
// console.log("Valor de presion_arterial_x:", presionArterialXHiddenValue);

//     // Obtener y mostrar el valor de presion_arterial_y en la consola
//     let presionArterialYValue = document.getElementById('presion_arterial_y_hidden').value;
//     console.log("Valor de presion_arterial_y:", presionArterialYValue);

//     let frecuenciaCardiaca = document.getElementById('frec_cardiaca_hidden').value;
//     console.log("frec_cardiaca_hidden:", frecuenciaCardiaca);

//     let frecuenciaRespiratoria = document.getElementById('frecuencia_respiratoria_hidden').value;
//     console.log("frecuencia_respiratoria:", frecuenciaRespiratoria);

//     let oxigenacionFio2 = document.getElementById('oxigenacion1_hidden').value;
//     console.log("oxigenacion1_hidden:", oxigenacionFio2);

//     let sa02_porcentaje_hidden = document.getElementById('sa02_porcentaje_hidden').value;
//     console.log("sa02_porcentaje_hidden:", sa02_porcentaje_hidden);

//     let temperatura_hidden =document.getElementById('temperatura_hidden').value;
//     console.log("temperatura_hidden:", temperatura_hidden);

//     let talla_hidden =document.getElementById('talla_hidden').value;
//     console.log("talla_hidden:", talla_hidden);

//     let peso_hidden =document.getElementById('peso_hidden').value;
//     console.log("peso_hidden:", peso_hidden);

//     let imc_hidden =document.getElementById('imc_hidden').value;
//     console.log("imc_hidden:", imc_hidden);

//     let peso_x_altura_hidden =document.getElementById('peso_x_altura_hidden').value;
//     console.log("peso_x_altura_hidden:", peso_x_altura_hidden);

//     //ver que campo me esta trayendo si el id_emergencia o el id_atencion
//     document.addEventListener("DOMContentLoaded", function() {
//             // Obtener el valor del campo oculto id_emergencia
//             var idEmergencia = document.getElementById("id_emergencia").value;
//             // Hacer console.log del valor
//             console.log("ID Emergencia:", idEmergencia);

//             // Obtener el valor del campo oculto id_atencion
//             var idAtencion = document.getElementById("id_atencion").value;
//             // Hacer console.log del valor
//             console.log("ID Atencion:", idAtencion);
//         });


</script>


<style>
  .borde-alerta {
    border-color: red !important;
    border: 2px red solid !important;
    outline: 0;
    border-radius: 4px;
    box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important;
  }
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
               <input type="hidden" name="datojhon" id="datojhon">
              <input type="hidden" name="fecha_nacimiento_beneficiario" id="fecha_nacimiento_beneficiario"
                class="form-control" readonly="">
                 <input type="hidden" name="id_emergencia" id="id_emergencia" value="{{ isset($id_emergencia) ? $id_emergencia : '' }}">
            
            <!-- Campo oculto para id_atencion -->
            <input type="hidden" name="id_atencion" id="id_atencion" value="{{ $id_atencion }}">
            <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{ $prestacion_id }}">
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
                                  <div tabindex="19" readonly="" class="form-control" style="text-align:left;">EMERGENCIA
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <label for="tipo_doc_medico">FECHA DE LA ATENCION</label>
                                  <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->fecha_creacion}}
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_materno">HORA DE LA ATENCION</label>
                                  <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->hora_creacion}}" name="M" id="M" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">PRIORIDAD</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{isset($datos_emergencia[0]->des_prioridades) ? $datos_emergencia[0]->des_prioridades : ''}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                  <label for="medico_nombre">AREA</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{isset($datos_emergencia[0]->topico) ? $datos_emergencia[0]->topico : ''}}" name="M1" id="M1" style="text-align:left">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-2">
                                  <label for="medico_nombre">CAMA</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{isset($datos_emergencia[0]->cama) ? $datos_emergencia[0]->cama : ''}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">N° ORDEN</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="2" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">N° HISTORIA CLINICA</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->nro_doc_ident}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">CODIGO UPS</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->codigo_ups}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                  <label for="medico_nombre">NOMBRE DE LA IPRESS</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->nombre_estab}}" name="M1" id="M1" style="text-align:left">
                                </div>
                              </div>
                              
                            </div>
                          </div>
                        </fieldset>
                      </div>
                     
                        <!--SI id_tipo_beneficiario es igual a 7 mostrar esto si no ocultarlo todo el div -->

                      <fieldset>
                        <!--
                        <div id="container" data-id-tipo-beneficiario="{{isset($datos_emergencia[0]->id_tipo_beneficiario) ? $datos_emergencia[0]->id_tipo_beneficiario : ''}}">
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
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->dni_beneficiario}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->paterno_beneficiario}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="medico_materno">APELLIDO MATERNO</label>
                                <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->materno_beneficiario}}" name="M" id="M" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">NOMBRES</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->nombre_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-3">
                                <label for="medico_nombre">BENEFICIARIO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->tipo_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="medico_nombre">F. DE NACIMIENTO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->fecha_nac_paciente}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">CIP</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->cip_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">GRADO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->grado_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">SITUACION</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->situacion_paciente}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">SEXO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->sexo}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">T. SANGRE</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->sangre_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">EDAD</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->edad_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>


                            <div class="form-group">
                              <div class="col-md-2" style="margin-bottom: 10px;">
                                  <label for="tipo_doc_medico">DATOS DEL ACOMPAÑANTE</label>
                              </div>
                          </div>
                           Línea de separación 
                          <hr style="border-top: 2px solid rgb(75, 68, 68); margin-top: 0;">
                            Sección de EDAD  
                          <div class="form-group">
                              <div class="col-md-2">
                                  <label for="medico_nombre">CONDUCIDO POR</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{isset($datos_emergencia[0]->conducio_por) ? $datos_emergencia[0]->conducio_por : ''}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              @if (isset($datos_emergencia[0]->conducio_por) && $datos_emergencia[0]->conducio_por === 'SOLO')

                              <div class="col-md-4" style="display: none;">
                                <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_emergencia[0]->dni_conducido . ' - ' . $datos_emergencia[0]->nombre_conducido }}" name="M1" id="M1" style="text-align:left">
                            </div>
                            <div class="col-md-2" style="display: none;">
                              <label for="medico_nombre">TELEFONO</label>
                              <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_emergencia[0]->telf_conducido}}" name="M1" id="M1" style="text-align:left">
                          </div>
                          @else
                         Mostrar DOC IDENTIDAD Y NOMBRES  
                              <div class="col-md-4">
                                  <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{isset($datos_emergencia[0]->dni_conducido) ? $datos_emergencia[0]->dni_conducido : '' . ' - ' . isset($datos_emergencia[0]->nombre_conducido) ? $datos_emergencia[0]->nombre_conducido : '' }}" name="M1" id="M1" style="text-align:left">
                              </div>

                              Mostrar TELEFONO  
                              <div class="col-md-2">
                                  <label for="medico_nombre">TELEFONO</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{isset($datos_emergencia[0]->telf_conducido) ? $datos_emergencia[0]->telf_conducido : ''}}" name="M1" id="M1" style="text-align:left">
                              </div>
                          @endif

                          @if (isset($datos_emergencia[0]->conducio_por) && $datos_emergencia[0]->conducio_por === 'SOLO')
                               Mostrar DESCRIPCION  
                              <div class="col-md-10">
                                  <label for="medico_nombre">DESCRIPCION</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_emergencia[0]->observaciones_benefi}}" name="M1" id="M1" style="text-align:left">
                              </div>
                          @endif
                          </div>
                          
                          
                          </div>
                          </div>
                        </div>
                         DERECHO HABIENTE
                         -->

                       <div id="container" data-id-tipo-beneficiario="{{ $datos_emergencia[0]->id_tipo_beneficiario }}">
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
                                <div tabindex="21" readonly="" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->dni_beneficiario}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                <div tabindex="21" readonly="" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->paterno_beneficiario}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="medico_materno">APELLIDO MATERNO</label>
                                <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->materno_beneficiario}}" name="M" id="M" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">NOMBRES</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->nombre_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-3">
                                <label for="medico_nombre">BENEFICIARIO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->tipo_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="medico_nombre">F. DE NACIMIENTO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->fecha_nac_paciente}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              
                              <div class="col-md-2">
                                <label for="medico_nombre">SEXO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->sexo}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">EDAD</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->edad_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">T. SANGRE</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->sangre_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-3">
                                <label for="medico_nombre">DIRECCION</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->domicilio}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            <div class="form-group">
                               <div class="col-md-2">
                                <label for="medico_nombre">CIP</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->cip_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">GRADO</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->grado_beneficiario}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">SITUACION</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->situacion_paciente}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>

                            @if($asegurado_titular)
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
                            @endif
                          
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
                                <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_emergencia[0]->conducio_por}}" name="M1" id="M1" style="text-align:left">
                            </div>
                            @if ($datos_emergencia[0]->conducio_por === 'SOLO')

                            <div class="col-md-4" style="display: none;">
                              <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                              <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_emergencia[0]->dni_conducido . ' - ' . $datos_emergencia[0]->nombre_conducido }}" name="M1" id="M1" style="text-align:left">
                          </div>
                          <div class="col-md-2" style="display: none;">
                            <label for="medico_nombre">TELEFONO</label>
                            <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_emergencia[0]->telf_conducido}}" name="M1" id="M1" style="text-align:left">
                        </div>
                        @else
      <!-- Mostrar DOC IDENTIDAD Y NOMBRES -->
                            <div class="col-md-4">
                                <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_emergencia[0]->dni_conducido . ' - ' . $datos_emergencia[0]->nombre_conducido }}" name="M1" id="M1" style="text-align:left">
                            </div>

                            <!-- Mostrar TELEFONO -->
                            <div class="col-md-2">
                                <label for="medico_nombre">TELEFONO</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_emergencia[0]->telf_conducido}}" name="M1" id="M1" style="text-align:left">
                            </div>
                        @endif

                        @if ($datos_emergencia[0]->conducio_por === 'SOLO')
                            <!-- Mostrar DESCRIPCION -->
                            <div class="col-md-4">
                                <label for="medico_nombre">DESCRIPCION</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_emergencia[0]->observaciones_benefi}}" name="M1" id="M1" style="text-align:left">
                            </div>
                        @endif
                        </div>
                          </div>
                          
                          </div>
                        </div>






                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>INFORMACIÓN DEL RESPONSABLE DEL TRIAJE EN EMERGENCIA</b>
                            </div>
                          </div>
                          <div class="panel-body" id="collapseprestacionespaciente">
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">DOCUMENTO DE IDENTIDAD</label>
                                <div tabindex="21" readonly="" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->profesional_dni}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                <div tabindex="21" readonly="" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->profesional_paterno}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="medico_materno">APELLIDO MATERNO</label>
                                <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->profesional_materno}}" name="M" id="M" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">NOMBRES</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->profesional_nombre}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">ESPECIALIDAD</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->especialidad_medico}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2">
                                <label for="medico_nombre">PROFESION</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->profesion_medico}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">N° COLEGIATURA</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->colegiatura}}" name="M1" id="M1" style="text-align:left">
                              </div>
                            </div>
                            
                          </div>
                        </div>
                        <div id="container2" data-id-salud_materna="{{ $datos_emergencia[0]->id_gestacion }}">
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
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->fech_ult_regla}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">FECHA_PROBABLE DE PARTO</label>
                                <div tabindex="19" readonly="" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->fech_prob_parto}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="medico_materno">SEMANAS GESTACION</label>
                                <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->semanas_gestacion}}" name="M" id="M" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">N° CONTROLES PRENATALES</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_emergencia[0]->num_controles}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              
                            </div>
                          </div>
                          </div>
                        </div>
                        
                        <div id="container1" data-id-motivo-ingreso="{{ $datos_emergencia[0]->id_motivo_ingreso }}">
                          <div id="referencia" class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                  <div class="panel-title text-center">
                                      <b>INFORMACION REFERENCIA</b>
                                  </div>
                              </div>
                              <div class="panel-body" id="collapseprestacionespaciente">
                                  <div class="form-group">
                                      <div class="col-md-3">
                                          <label for="tipo_doc_medico">ESTABLECIMIENTO DE REFERENCIA</label>
                                          <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->esta_referencia}}</div>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="tipo_doc_medico">DOCUMENTO DE LA REFERENCIA</label>
                                          <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_emergencia[0]->numero_oficio}}</div>
                                      </div>
                                      <div class="col-md-2">
                                          <label for="medico_nombre">FECHA DE LA REFERENCIA</label>
                                          <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_emergencia[0]->fecha_referencia}}" name="M1" id="M1" style="text-align:left">
                                      </div>
                                      
                                  </div>
                                  <div class="form-group">
                                    <div class="col-md-12">
                                      <label for="medico_nombre">DIAGNOSTICO DE LA REFERENCIA</label>
                                      <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_emergencia[0]->nom_diagnostico}}" name="M1" id="M1" style="text-align:left">
                                  </div>
                                      <div class="col-md-12">
                                          <label for="medico_nombre">OBSERVACION</label>
                                          <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_emergencia[0]->observacion_evacuados}}" name="M1" id="M1" style="text-align:left">
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      

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
                                  <label>DIAGNÓSTICOS DE ATENCION Y EVALUACION</label>
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
                                                          @if ($diagHosp->id_evaluacion == 0)
                                                              ATENCION
                                                          @else
                                                              EVALUACION
                                                          @endif
                                                      </td>
                                                  </tr>
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
                    <!--li class=""><a href="#tab4default" data-toggle="tab" style="font-size:12px"
                        aria-expanded="false"><b><i class="fa fa-male" aria-hidden="true"></i> PIEL &
                          DISPOSITIVOS</b></a></li-->
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
                        <div class="col-lg-2 col-md-4">
                            <label>PRESIÓN ARTERIAL</label>
                            <div class="row">
                                <div class="col-xs-5">
                                    <!-- Campo oculto para almacenar el valor -->
                                    <input type="hidden" name="presion_arterial_x_hidden" id="presion_arterial_x_hidden" value="{{ isset($caracteresFisicoArray[11]) ? htmlspecialchars($caracteresFisicoArray[11]) : '' }}">
                                    
                                    <!-- Campo de entrada visible sin valor inicial -->
                                    <input tabindex="31" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" onkeypress="return esEntero(event);" maxlength="3" value="{{ $datos_emergencia[0]->presion_arterial_x }}">
                                    <span id="presion_arterial_x-aceptado"></span>
                                </div>
                                <div class="col-xs-2" style="text-align: center;">
                                    <label>/</label>
                                </div>
                                <div class="col-xs-5">
                                    <!-- Campo oculto para almacenar el valor -->
                                    <input type="hidden" name="presion_arterial_y_hidden" id="presion_arterial_y_hidden" value="<?php echo isset($caracteresFisicoArray[12]) ? htmlspecialchars($caracteresFisicoArray[12]) : ''; ?>">
                        
                                    <input tabindex="32" type="text" name="presion_arterial_y" id="presion_arterial_y" class="form-control" onkeypress="return esEntero(event);" maxlength="3" value="{{ $datos_emergencia[0]->presion_arterial_y }}">
                                    <span id="presion_arterial_y-aceptado"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-2 col-md-4">
                            <label>Pulso/frec. Cardiaca</label>
                            <div class="row">
                                <div class="col-xs-12">
                                    <!-- Campo oculto para almacenar el valor -->
                                    <input type="hidden" name="frec_cardiaca_hidden" id="frec_cardiaca_hidden" value="<?php echo isset($caracteresFisicoArray[14]) ? htmlspecialchars($caracteresFisicoArray[14]) : ''; ?>">
                                    <!-- Campo de entrada visible sin valor inicial -->
                                    <input tabindex="33 " type="text" name="frec_cardiaca" id="frec_cardiaca" class="form-control" value="{{ number_format($datos_emergencia[0]->frecuencia_cardiaca, 1) }}" onkeypress="return esEntero(event);" maxlength="3">
                                    <span id="frec_cardiaca-aceptado"></span>
                                </div>
                            </div>
                        </div>
                        

                          <div class="col-lg-2 col-md-4">
                            <label>FREC. RESPIRATORIA (/min)</label>
                            <input type="hidden" name="frecuencia_respiratoria_hidden" id="frecuencia_respiratoria_hidden" value="{{ isset($caracteresFisicoArray[16]) ? htmlspecialchars($caracteresFisicoArray[16]) : '' }}">

                            <input value="{{ number_format($datos_emergencia[0]->frecuencia_respiratoria, 1) }}" type="text" name="frecuencia_respiratoria" tabindex="34"
                              id="frecuencia_respiratoria" class="form-control" onkeypress="return esEntero(event);"
                              maxlength="2">
                            <span id="frecuencia_respiratoria-aceptado"></span>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>Fi02 (%)</label>
                            <input type="hidden"   name="oxigenacion1_hidden" id="oxigenacion1_hidden" value="{{ isset($caracteresFisicoArray[17]) ? htmlspecialchars($caracteresFisicoArray[17]) : '' }}">

                            <input value="{{ $datos_emergencia[0]->fi02 }}" type="text" tabindex="35" name="oxigenacion1" id="oxigenacion1" 
                                class="form-control" onkeypress="return esEntero(event);" maxlength="3"> 
                            <span id="oxigenacion1-aceptado"></span>
                        </div>
                        {{-- <!--ESTO ES EL SEGUNDO INPUT-->
                        <div class="col-lg-2 col-md-4">
                            <label>Fi03 (%)</label>
                            <input value="" tabindex="36"  type="text" name="oxigenacion2" id="oxigenacion2" 
                                class="form-control" oninput="updateFirstInput(this.value);" maxlength="3"> 
                            <span id="oxigenacion2-aceptado"></span>
                        </div> --}}
                        
                          <div class="col-lg-2 col-md-4">
                            <label>Sa02 (%)</label>
                            <input type="hidden" name="sa02_porcentaje_hidden" id="sa02_porcentaje_hidden" value="{{ isset($caracteresFisicoArray[18]) ? htmlspecialchars($caracteresFisicoArray[18]) : '' }}">

                            <input value="{{ $datos_emergencia[0]->sa02_porcentaje }}" type="text" tabindex="37"  name="sa02_porcentaje" id="sa02_porcentaje" tabindex="8"
                              class="form-control" onkeypress="return esEntero(event);" maxlength="3">
                            <span id="saturacion-aceptado"></span>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>TEMPERATURA (°C)</label>
                            <input type="hidden" name="temperatura_hidden" id="temperatura_hidden" value="{{ isset($caracteresFisicoArray[19]) ? htmlspecialchars($caracteresFisicoArray[19]) : '' }}">

                            <input placeholder="00.00"  tabindex="38"  value="{{ number_format($datos_emergencia[0]->temperatura, 1) }}" type="text" name="temperatura" id="temperatura"
                              class="form-control" class="form-control" maxlength="5" onkeypress="return evitarLetras(event)" />
                            <span id="texto-aceptado"></span>
                          </div>
                        </div>

                        <div class="form-group"> 
                          <div class="col-lg-2 col-md-4">
                            <label>TALLA (cm) &nbsp;</label>
                            <input type="hidden" name="talla_hidden" id="talla_hidden" value="{{ isset($caracteresFisicoArray[13]) ? htmlspecialchars($caracteresFisicoArray[13]) : '' }}">
                            <input placeholder="{{ $datos_emergencia[0]->talla_cm }}" value="{{ number_format($datos_emergencia[0]->talla_cm, 1) }}" type="text"  tabindex="39" name="talla" id="talla" class="form-control"
                              onkeyup="compruebaIMC()" maxlength="5" onkeypress="return evitarLetras(event)">
                            <span id="talla-aceptado"></span>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>PESO (Kg) &nbsp;</label>
                            <input type="hidden" name="peso_hidden" id="peso_hidden" value="{{ isset($caracteresFisicoArray[15]) ? htmlspecialchars($caracteresFisicoArray[15]) : '' }}">
                            <input placeholder="00.00" value="{{ number_format($datos_emergencia[0]->peso_kg, 1) }}" tabindex="40" type="text" name="peso" id="peso"
                              class="form-control" onkeyup="compruebaIMC()" onkeypress="return evitarLetras(event)"
                              maxlength="6">
                            <span id="peso-aceptado"></span>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>IMC &nbsp;</label> 
                            <input type="hidden" name="imc_hidden" id="imc_hidden" value="{{ isset($caracteresFisicoArray[20]) ? htmlspecialchars($caracteresFisicoArray[20]) : '' }}">
                            <input value="" type="text" name="imc" id="imc" class="form-control" readonly="">
                            <span id="imc-aceptado"></span>
                          </div> 

                          <div class="col-sm-2">
                            <label>PESO IDEAL</label>
                            <input type="hidden" name="peso_x_altura_hidden" id="peso_x_altura_hidden" value="{{ isset($caracteresFisicoArray[21]) ? htmlspecialchars($caracteresFisicoArray[21]) : '' }}">
                            <input value="" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control"  readonly="">
                          </div>

                         
                        </div> 

                      </fieldset> 
                    </div>
 
 
  

                    <div class="tab-pane fade" id="tab1default">
                      <fieldset>
                        <!--div class="form-group">
                          <div class="col-md-6">
                          <br>
                                <div class="col-md-4">
                                  <label>HIPERTENSION ARTERIAL</label> <br>
                                  <input type="radio" checked="" class="regular-radio" name="hta" id="hta1" value="SI"
                                    style="cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)" id="click_hta_si" name="click_hta_si"
                                      onclick="seleccionar_radio_por_texto('click_hta_si','hta1','hta_tratamiento','show')">SI</a>
                                  </b>
                                  <input type="radio" checked="" class="regular-radio" name="hta" id="hta2" value="NO"
                                    style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)" id="click_hta_no" name="click_hta_no"
                                      onclick="seleccionar_radio_por_texto('click_hta_no','hta2','hta_tratamiento','hide')">NO</a>
                                  </b>
                                </div>
                            
                                <div id="hta_tratamiento" name="hta_tratamiento"  class="col-md-8">
                                  <div class="col-md-12">
                                    <label>TRATAMIENTO</label>
                                      <input value="" type="text" id="tratamiento_hta" name="tratamiento_hta"
                                        class="form-control" />
                                    </div>
                                  </div>
                          </div>
                          <br>
                          <div class="col-md-6">
                          <br>
                              <div class="col-md-4">
                                  <label>DIABETES MELLITUS</label> <br>
                                  <input type="radio" checked="" class="regular-radio" name="diabetes_mellitus" id="diabetes_mellitus1" value="SI"
                                    style="cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)" id="click_diabetes_mellitus_si"
                                      name="click_diabetes_mellitus_si"
                                      onclick="seleccionar_radio_por_texto('click_diabetes_mellitus_si','diabetes_mellitus1','diabetes_mellitus_observacion','show')">SI</a>
                                  </b>

                                  <input type="radio" checked="" class="regular-radio" name="diabetes_mellitus"
                                    id="diabetes_mellitus2" value="NO" style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)" id="click_diabetes_mellitus_no"
                                      name="click_diabetes_mellitus_no"
                                      onclick="seleccionar_radio_por_texto('click_diabetes_mellitus_no','diabetes_mellitus2','diabetes_mellitus_observacion','hide')">NO</a>
                                  </b>
                              </div>
                              <div id="diabetes_mellitus_observacion" name="diabetes_mellitus_observacion"  class="col-md-8">
                                <div class="col-md-12">
                                  <label>TRATAMIENTO</label>
                                  <input value="" type="text" id="diabetes_mellitus_descripcion"
                                    name="diabetes_mellitus_descripcion" class="form-control" />
                                </div>
                              </div>
                          </div>
                          <br>
                          <div class="col-md-6">
                          <br>
                              <div class="col-md-4">
                                <label style="color: red;">ALERGIA</label><br>
                                <input type="radio" checked="" class="regular-radio" name="ram" id="ram1" value="SI"
                                  style="cursor: pointer;">
                                <b>
                                  <a href="javascript:void(0)" id="click_ram_si" name="click_ram_si"
                                    onclick="seleccionar_radio_por_texto('click_ram_si','ram1','ram_descripcion','show')">SI</a>
                                </b>
                                <input type="radio" checked="" class="regular-radio" name="ram" id="ram2" value="NO"
                                  style="margin-left: 10px;cursor: pointer;">
                                <b>
                                  <a href="javascript:void(0)" id="click_ram_no" name="click_ram_no"
                                    onclick="seleccionar_radio_por_texto('click_ram_no','ram2','ram_descripcion','hide')">NO</a>
                                </b>
                              </div>
                              <div class="col-md-8" id="ram_descripcion" name="ram_descripcion">
                                <label style="color: red;">DETALLE (ALERGIA)</label>
                                <input value="" type="text" id="detalle_ram_alergia" name="detalle_ram_alergia"
                                  class="form-control" />
                              </div>
                          </div>
                                                    {{--!SECCION DETALLES HEMODIALISIS--}}
                          <br>
                          <div class="col-md-6">
                          <br>
                            <div class="col-md-4">
                                <label style="color: red;">HEMODIALISIS</label><br>
                                <input type="radio" checked="" class="regular-radio" name="ram_hemodialisis" id="ram1_h" value="SI"
                                  style="cursor: pointer;">
                                <b>
                                  <a href="javascript:void(0)" id="click_ram_si_h" name="click_ram_si_h"
                                    onclick="seleccionar_radio_por_texto('click_ram_si_h','ram1_h','ram_descripcion_hemodialisis','show')">SI</a>
                                </b>
                                <input type="radio" checked="" class="regular-radio" name="ram_hemodialisis" id="ram2_h" value="NO"
                                  style="margin-left: 10px;cursor: pointer;">
                                <b>
                                  <a href="javascript:void(0)" id="click_ram_no_h" name="click_ram_no_h"
                                    onclick="seleccionar_radio_por_texto('click_ram_no_h','ram2_h','ram_descripcion_hemodialisis','hide')">NO</a>
                                </b>
                              </div>
                              <div class="col-md-8" id="ram_descripcion_hemodialisis" name="ram_descripcion_hemodialisis">
                                <label style="color: rgb(39, 37, 37);">DETALLE (HEMODIAISIS)</label>
                                <input value="" type="text" id="detalle_ram_hemo" name="detalle_ram_hemo"
                                  class="form-control" />
                              </div>
                            </div>
                          </div-->

                        <div class="form-group">
                          <div class="col-md-4">
                            <label for="tipo">CARDIOLÓGICO</label>
                            <select name="cardiologico[]" id="cardiologico" class="js-example-basic-multiple"  style="width: 100%;" multiple="multiple">
                            </select>
                          </div>
                          <div class="col-md-3">
                            <label>OBSERVACIÓN</label>
                            <input value="" type="text" class="form-control" id="descripcion_antecedente_observacion" name="descripcion_antecedente_observacion"/>
                          </div>
                          <div class="col-md-3">
                            <label>OTROS ANTECEDENTES</label>
                            <input value="" type="text" class="form-control" id="descripcion_otros_antecedentes" name="descripcion_otros_antecedentes" />
                          </div>
                          {{-- <div class="col-lg-2 col-md-2">
                            <label>VACUNA COVID-19</label><br>
                            <input type="radio" checked="" class="regular-radio" name="vacuna_covid" id="vacuna_covid1"
                              value="SI" style="cursor: pointer;">
                            <b>
                              <a href="javascript:void(0)" id="click_vacuna_covid_si" name="click_vacuna_covid_si"
                                onclick="seleccionar_radio_por_texto('click_vacuna_covid_si','vacuna_covid1','','')">SI</a>
                            </b>
                            <input type="radio" checked="" class="regular-radio" name="vacuna_covid" id="vacuna_covid2"
                              value="NO" style="margin-left: 10px;cursor: pointer;">
                            <b>
                              <a href="javascript:void(0)" id="click_vacuna_covid_no" name="click_vacuna_covid_no"
                                onclick="seleccionar_radio_por_texto('click_vacuna_covid_no','vacuna_covid2','','')">NO</a>
                            </b>
                          </div> --}}
                        </div>
                        




                        {{-- <div class="form-group"> <br>
                          <div class="col-md-12">
                            <label>DIAGNÓSTICOS DE EVALUCION</label><br> 
                              <div class="table-responsive">
                                  <table id="example" class="table table-striped">
                                      <thead>
                                          <tr>
                                              <th>CIE10</th>
                                              <th>DESCRIPCIÓN</th>
                                              <th style="text-align: center;">TIPO</th>
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
                      </fieldset>
                    </div>

                    <div class="tab-pane fade" id="tab2default">
                      <fieldset>
                        <div class="form-group">
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">INGRESA POR</label><br>
                            <input tabindex="22" readonly type="text" class="form-control" value="{{$ingresapor_emergencia->nombre}}" name="ingre" id="ingre" style="text-align:left">
                            
                        </div>
                     
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">CONDICIÓN DE INGRESO</label>
                            <select class="form-control input-sm" id="condicion_ingreso" name="condicion_ingreso"
                              style="width: 100%;">
                            </select>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">ESTADO DE CONCIENCIA</label>
                            <select class="form-control input-sm" id="estado_conciencia" name="estado_conciencia"
                              style="width: 100%;">
                            </select>
                          </div>

                          <div id="orientado_form">
                            <div class="col-lg-1 col-md-1">
                              <label for="tipo">Orientado</label><br>
                              <input type="radio" checked="" class="regular-radio" name="orientado"
                                style="cursor: pointer;" id="orientado" value="SI">
                              <b>
                                <a href="javascript:void(0)" id="click_orientado_si" name="click_orientado_si"
                                  onclick="seleccionar_radio_por_texto('click_orientado_si','orientado','orientado_si','show')">SI</a>
                              </b>
                               <input type="radio" checked="" class="regular-radio orientado_input_no" name="orientado"
                                id="orientado" value="NO" style="margin-left: 10px;cursor: pointer;">
                              <b>
                                <a href="javascript:void(0)" id="click_orientado_no" name="click_orientado_no"
                                  onclick="seleccionar_radio_por_texto('click_orientado_no','orientado','orientado_si','hide')">NO</a>
                              </b>
                            </div>
                          </div>
                          <div id="orientado_si" name="orientado_si">
                            <div class="col-lg-3 col-md-6" id="detalle">
                              <label for="tipo">DETALLE</label><br>
                              <input type="checkbox" id="vehicle1" name="vehicle1" style="cursor: pointer;"> 
                              <label for="vehicle1" style="cursor: pointer;"> TIEMPO </label>
                              <input type="checkbox" id="vehicle2" name="vehicle2" style="margin-left: 10px;cursor: pointer;">
                              <label for="vehicle2" style="cursor: pointer;"> ESPACIO</label>
                              <input type="checkbox" id="vehicle3" name="vehicle3" style="margin-left: 10px;cursor: pointer;">
                              <label for="vehicle3" style="cursor: pointer;"> PERSONA</label>
                            </div>
                          </div>


                          <div class="col-lg-2 col-md-2">
                            <label style="color: red;">GRADO DE DEPENDENCIA</label>
                            <select class="form-control input-sm" id="grado_dependencia" name="grado_dependencia"
                              style="width: 100%;">
                              <option value="GRADO I">GRADO I</option>
                              <option value="GRADO II">GRADO II</option>
                              <option value="GRADO III">GRADO III</option>
                              <option value="GRADO IV">GRADO IV</option>
                            </select>
                          </div>
                            <!--ESTO ES EL SEGUNDO INPUT-->
                        <div class="col-lg-2 col-md-4">
                          <label>Fi02 (%)</label>
                          <input value="" type="text" name="oxigenacion2" id="oxigenacion2" 
                              class="form-control" onkeypress="return esEntero(event);" maxlength="3"> 
                          <span id="oxigenacion2-aceptado"></span>
                      </div>
                        </div>

                        <div class="form-group">

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">FUNCIÓN MOTORA</label>
                            <select class="form-control input-sm" id="funcion_motora" name="funcion_motora"
                              style="width: 100%;">
                            </select>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">RESPIRATORIO (Duración)</label>
                            <select class="form-control input-sm" id="respiratorio_duracion"
                              name="respiratorio_duracion" style="width: 100%;">
                            </select>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">SECRECIONES BRONQUIALES (tos)</label><br>
                            <input type="radio" checked="" class="regular-radio" name="secreaciones_bronquiales_si"
                              style="cursor: pointer;" id="secreaciones_bronquiales_si" value="SI">
                            <b>
                              <a href="javascript:void(0)">SI</a>
                            </b>
                            <input type="radio" checked="" class="regular-radio" name="secreaciones_bronquiales_si"
                              id="secreaciones_bronquiales_si" value="NO" style="margin-left: 10px;cursor: pointer;">
                            <b>
                              <a href="javascript:void(0)" >NO</a>
                            </b>
                            
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">OXIGENOTERAPIA</label><br>
                            <input type="radio" checked="" class="regular-radio" name="oxigenoterapia"
                              style="cursor: pointer;" id="oxigenoterapia1" value="SI">
                            <b>
                              <a href="javascript:void(0)" id="click_oxigenoterapia_si" name="click_oxigenoterapia_si"
                                onclick="seleccionar_radio_por_texto('click_oxigenoterapia_si','oxigenoterapia1','oxigenoterapia_detalle','show')">SI</a>
                            </b>
                            <input type="radio" checked="" class="regular-radio" name="oxigenoterapia"
                              id="oxigenoterapia2" value="NO" style="margin-left: 10px;cursor: pointer;">
                            <b>
                              <a href="javascript:void(0)" id="click_oxigenoterapia_no" name="click_oxigenoterapia_no"
                                onclick="seleccionar_radio_por_texto('click_oxigenoterapia_no','oxigenoterapia2','oxigenoterapia_detalle','hide')">NO</a>
                            </b>
                          </div>

                          <div id="oxigenoterapia_detalle" name="oxigenoterapia_detalle">
                            <div class="col-lg-2 col-md-4">
                              <label style="color: red;">DETALLE (OXIGENOTERAPIA)</label>
                              <select class="form-control input-sm" id="oxigenoterapia_detalle_seleccion"
                                name="oxigenoterapia_detalle_seleccion" style="width: 100%;">
                              </select>
                            </div>
                         
                          </div>
                        </div>


                        <div class="form-group">
                          <div class="col-lg-2 col-md-2">
                            <label for="tipo">MICCIÓN ESPONTÁNEA</label><br>
                            <input type="radio" checked="" class="regular-radio" name="miccion_espontanea"
                              style="cursor: pointer;" id="miccion_espontanea1" value="SI">
                            <b>
                              <a href="javascript:void(0)">SI</a>
                            </b>
                            <input type="radio" checked="" class="regular-radio" name="miccion_espontanea"
                              id="miccion_espontanea2" value="NO" style="margin-left: 10px;cursor: pointer;">
                            <b>
                              <a href="javascript:void(0)">NO</a>
                            </b>
                          </div>
                          
                          
                        </div>

                        <div class="form-group"> <br>
                          <div class="col-lg-2 col-md-2">
                            <label for="tipo">ACOMPAÑADO</label><br>
                            <input type="radio" checked="" class="regular-radio" name="acompaniado_por"
                              style="cursor: pointer;" id="acompaniado_por1" value="SOLO">
                            <label for="acompaniado_por1">SOLO</label>
                            {{-- <b>
                              <a href="javascript:void(0)" id="click_miccion_acompaniado_por_si"
                                name="click_miccion_acompaniado_por_si"
                                onclick="seleccionar_radio_por_texto('click_miccion_acompaniado_por_si','acompaniado_por1','acompaniado_por_detalle','show')">SOLO</a>
                            </b> --}}
                            <input type="radio" checked="" class="regular-radio" name="acompaniado_por"
                              id="acompaniado_por2" value="FAMILIAR" style="margin-left: 10px;cursor: pointer;">
                            <label for="acompaniado_por2">FAMILIAR</label>
                            {{-- <b>
                              <a href="javascript:void(0)" id="click_miccion_acompaniado_por_no"
                                name="click_miccion_acompaniado_por_no"
                                onclick="seleccionar_radio_por_texto('click_miccion_acompaniado_por_no','acompaniado_por2','acompaniado_por_detalle','hide')">FAMILIAR</a>
                            </b> --}}
                          </div>
                         
                          <div class="col-md-6" id="acompaniado_por_detalle" name="acompaniado_por_detalle">
                            <div class="col-md-7" id="acompaniado_por_detalle" name="acompaniado_por_detalle">
                              <label>ACOMPAÑADO POR:</label>
                                <input value="" type="text" class="form-control" 
                                id="descripcion_acompaniado_por" name="descripcion_acompaniado_por"/> 
                            </div>
                            <div class="col-md-5" id="acompaniado_por_detalle" name="acompaniado_por_detalle">
                                <label>TELEFONO DEL ACOMPAÑANTE:</label>
                                <input value="" type="text" class="form-control" 
                                id="celular_acompaniado"onkeypress="return esEntero(event);" maxlength="9" name="celular_acompaniado"/> 
                            </div>
                          </div>
                      
                            <div class="col-md-4">
                              <label for="tipo">HEMOPTISIS</label><br>
                              <input type="radio"  class="regular-radio" name="emoptisis"
                                style="cursor: pointer;" id="emoptisis" value="SI">
                                <b>
                                  <a href="javascript:void(0)">SI</a>
                                </b>
                              <input type="radio" checked class="regular-radio" name="emoptisis"
                                id="emoptisis" value="NO" style="margin-left: 10px;cursor: pointer;">
                                <b>
                                  <a href="javascript:void(0)">NO</a>
                                </b>
                            </div>

                          <div class="col-lg-2 col-md-4" id="acompaniado_por_contacto" name="acompaniado_por_contacto" style="display:none">
                            <label># DE CONTACTO</label>
                            <input value="" type="text" class="form-control" name="contacto_acompaniado_por" id="contacto_acompaniado_por"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <hr>
                          <div class="col-md-3">
                            <label for="tipo">PIEL (COLOR)</label> <br>
                            <select class="form-control input-sm" id="piel_colores" name="piel_colores"
                              style="width: 100%;">
                            </select>
                          </div>
                          <div class="col-lg-2 col-md-2">
                            <label for="tipo">EDEMA</label> <br>
                            <input type="radio" checked="" class="regular-radio" name="edema" id="edema1"
                              style="cursor: pointer;" value="SI">
                            <b>
                              <a href="javascript:void(0)" id="click_edema_si" name="click_edema_si"
                                onclick="seleccionar_radio_por_texto('click_edema_si','edema1','','')">SI</a>
                            </b>
                            <input type="radio" checked="" class="regular-radio" name="edema" id="edema2" value="NO"
                              style="margin-left: 10px;cursor: pointer;">
                            <b>
                              <a href="javascript:void(0)" id="click_edema_no" name="click_edema_no"
                                onclick="seleccionar_radio_por_texto('click_edema_no','edema2','','')">NO</a>
                            </b>
                          </div>
                          <div class="col-md-3">
                            <label for="tipo">INTEGRIDAD</label>
                            <select class="form-control input-sm" id="integridad" name="integridad"
                              style="width: 100%;">
                            </select>
                          </div>

                          <div class="col-md-4">
                            <label for="tipo">ABDOMEN</label>
                            <select class="form-control input-sm" id="abdomen" name="abdomen" style="width: 100%;">
                            </select>
                          </div>
                        </div>
                        <div class="form-group">

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">EVALUACIÓN MALLAMPATY </label> <br>
                            <input type="radio" checked="" class="regular-radio" name="evaluacion_mallampaty"
                              style="cursor: pointer;" id="evaluacion_mallampaty1" value="SI">
                            <b>
                              <a href="javascript:void(0)" id="click_evaluacion_mallampaty_si"
                                name="click_evaluacion_mallampaty_si"
                                onclick="seleccionar_radio_por_texto('click_evaluacion_mallampaty_si','evaluacion_mallampaty1','evaluacion_mallampaty_opcion','show')">SI</a>
                            </b>
                            <input type="radio" checked="" class="regular-radio" name="evaluacion_mallampaty"
                              id="evaluacion_mallampaty2" value="NO" style="margin-left: 10px;cursor: pointer;">
                            <b>
                              <a href="javascript:void(0)" id="click_evaluacion_mallampaty_no"
                                name="click_evaluacion_mallampaty_no"
                                onclick="seleccionar_radio_por_texto('click_evaluacion_mallampaty_no','evaluacion_mallampaty2','evaluacion_mallampaty_opcion','hide')">NO</a>
                            </b>
                          </div>

                          <div class="col-lg-10 col-md-12" id="evaluacion_mallampaty_opcion"
                            name="evaluacion_mallampaty_opcion">
                            <label for="tipo">EVALUACIÓN MALLAMPATY</label>
                            <select class="form-control input-sm evaluacion_mallampaty_seleccion"
                              id="evaluacion_mallampaty_seleccion" name="evaluacion_mallampaty_seleccion"
                              style="width: 100%;">
                              <option VALUE="*">
                                --SELECCIONAR--
                              </option>
                              <option VALUE="1">
                                <b>CLASE I:</b> VISIBILIDAD DEL PALADAR BLANDO, ÚVULA Y PILARES
                              </option>
                              <option VALUE="2">
                                <b>CLASE II:</b> VISIBILIDAD DEL PALADAR BLANDO Y ÚVULA
                              </option>
                              <option VALUE="3">
                                <b>CLASE III:</b> VISIBILIDAD DEL PALADAR BLANDO BASE DE LA ÚVULA
                              </option>
                              <option VALUE="4">
                                <b>CLASE IV:</b> IMPOSIBILIDAD PARA VER EL PALADAR BLANDO
                              </option>
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
                        </div>
                      </fieldset>
                    </div>

                    <div class="tab-pane fade" id="tab4default">
                      <fieldset>
                        <div class="row">
                          <!--div class="col-lg-6 col-md-12">
                            <div class="form-group">
                              <div class="col-md-3">
                                <label for="tipo">DISPOSITIVOS INVASIVOS</label><br>
                                <input type="radio" checked class="regular-radio" name="dispositivos_invasivos"
                                  style="cursor: pointer;" id="dispositivos_invasivos1" value="SI">
                                <b>
                                  <a href="javascript:void(0)" id="click_dispositivos_invasivos_si"
                                    name="click_dispositivos_invasivos_si"
                                    onclick="seleccionar_radio_por_texto('click_dispositivos_invasivos_si','dispositivos_invasivos1','dispositivos_invasivos_detalle','show')">SI</a>
                                </b>
                                <input type="radio" class="regular-radio" name="dispositivos_invasivos"
                                  id="dispositivos_invasivos2" value="NO" style="margin-left: 10px;cursor: pointer;">
                                <b>
                                  <a href="javascript:void(0)" id="click_dispositivos_invasivos_no"
                                    name="click_dispositivos_invasivos_no"
                                    onclick="seleccionar_radio_por_texto('click_dispositivos_invasivos_no','dispositivos_invasivos2','dispositivos_invasivos_detalle','hide')">NO</a>
                                </b>
                              </div>

                              <div id="dispositivos_invasivos_detalle" name="dispositivos_invasivos_detalle">
                                <div class="col-md-3">
                                  <label for="tipo">VÍA PERIFÉRICA</label><br>
                                  <input type="radio" checked class="regular-radio" name="via_periferica"
                                    style="cursor: pointer;"  value="SI">
                                  <b>
                                    <a href="javascript:void(0)">SI</a>
                                  </b>
                                  <input type="radio" class="regular-radio" name="via_periferica" 
                                  value="NO" style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)">NO</a>
                                  </b>
                                </div>

                                <div class="col-md-3">
                                  <label for="tipo">VÍA CENTRAL</label><br>
                                  <input type="radio" checked class="regular-radio" name="via_central"
                                    style="cursor: pointer;"  value="SI">
                                  <b>
                                    <a href="javascript:void(0)">SI</a>
                                  </b>
                                  <input type="radio" class="regular-radio" name="via_central" 
                                  value="NO" style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)">NO</a>
                                  </b>
                                </div>

                                <div class="col-md-3">
                                  <label for="tipo">CATÉTER ALTO FLUJO</label><br>
                                  <input type="radio" checked class="regular-radio" name="caracter_alto_flujo"
                                    style="cursor: pointer;"  value="SI">
                                  <b>
                                    <a href="javascript:void(0)">SI</a>
                                  </b>
                                  <input type="radio" class="regular-radio" name="caracter_alto_flujo" 
                                  value="NO" style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)">NO</a>
                                  </b>
                                </div>

                                {{-- <div class="col-md-3">
                                  <br>
                                  <label for="tipo">SNG GRAVEDAD</label><br>
                                  <input type="radio" checked class="regular-radio" name="sng_gavedad"
                                    style="cursor: pointer;"  value="SI">
                                  <b>
                                    <a href="javascript:void(0)">SI</a>
                                  </b>
                                  <input type="radio" class="regular-radio" name="sng_gavedad" 
                                  value="NO" style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)">NO</a>
                                  </b>

                                </div> --}}

                                <div class="col-md-3">
                                  <br>
                                  <label for="tipo">SONDAS</label> <br>
                                  <input type="radio" checked="" class="regular-radio" name="sondas" id="sondas1"
                                    style="cursor: pointer;" value="SI">
                                  <b>
                                    <a href="javascript:void(0)" id="click_sondas_si" name="click_sondas_si"
                                      onclick="seleccionar_radio_por_texto('click_sondas_si','sondas1','sondas_descripcion','show')">SI</a>
                                  </b>
                                  <input type="radio" checked="" class="regular-radio" name="sondas" id="sondas2"
                                    value="NO" style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)" id="click_sondas_no" name="click_sondas_no"
                                      onclick="seleccionar_radio_por_texto('click_sondas_no','sondas2','sondas_descripcion','hide')">NO</a>
                                  </b>
                                </div>

                                <div class="col-md-6" id="sondas_descripcion" name="sondas_descripcion">
                                  <br>
                                  <label for="tipo">DESCRIPCIÓN SONDAS</label>
                                  <input value="" type="text" class="form-control" id="descripcion_sonda" name="descripcion_sonda"/>
                                </div>

                                <div class="col-md-3"><br>
                                  <label for="tipo">LÍNEA ARTERIAL</label><br>
                                  <input type="radio" checked class="regular-radio" name="linea_arterial"
                                    style="cursor: pointer;"  value="SI">
                                  <b>
                                    <a href="javascript:void(0)">SI</a>
                                  </b>
                                  <input type="radio" class="regular-radio" name="linea_arterial" 
                                  value="NO" style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)">NO</a>
                                  </b>

                                </div>

                                <div class="col-md-3">
                                  <br>
                                  <label for="tipo">DRENAJES</label> <br>
                                  <input type="radio" checked="" class="regular-radio" name="drenaje" id="drenaje1"
                                    style="cursor: pointer;" value="SI">
                                  <b>
                                    <a href="javascript:void(0)" id="click_drenaje_si" name="click_drenaje_si"
                                      onclick="seleccionar_radio_por_texto('click_drenaje_si','drenaje1','drenaje_descripcion','show')">SI</a>
                                  </b>
                                  <input type="radio" checked="" class="regular-radio" name="drenaje" id="drenaje2"
                                    value="NO" style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)" id="click_drenaje_no" name="click_drenaje_no"
                                      onclick="seleccionar_radio_por_texto('click_drenaje_no','drenaje2','drenaje_descripcion','hide')">NO</a>
                                  </b>
                                </div>

                                <div class="col-md-6" id="drenaje_descripcion" name="drenaje_descripcion">
                                  <br>
                                  <label for="tipo">DESCRIPCIÓN DRENAJES</label>
                                  <input value="" type="text" class="form-control" name="descripcion_drenaje" id="descripcion_drenaje"/>
                                </div>
                              </div>
                            </div>
                          </div-->

                          <div class="col-lg-6 col-md-12">
                            <form id="FormularioPunto" name="FormularioPunto" enctype="multipart/form-data"
                              method="GET">
                              <div class="col-md-10">
                              <div class="form-group">
                                    <div class="col-md-8">
                                      <label for="tipo">SELECCIONE PROCESO</label>
                                        <select class="form-control input-sm" id="tipo_proceso" name="tipo_proceso" style="width: 100%;">
                                        </select>
                                        <div id="otros_input_container" style="display: none; margin-left: 10px; flex-shrink: 0;">
                                          <label for="otros_input">Especifique Otros</label>
                                          <input type="text" class="form-control input-sm" id="otros_input" name="otros_input" style="width: 100%;">
                                        </div>
                                        <input type="hidden" name="id_atencion" id="id_atencion" value="{{$id_atencion}}">
                                        <input type="hidden" name="id_tipo_atenciones" id="id_tipo_atenciones" value="{{$id_tipo_atenciones}}">
                                        <input type="hidden" name="tipo_registro" id="tipo_registro" value="NOTA_ENFERMERIA">
                                    </div>
                                </div>
                              <input type="hidden" name="img_puntos_base" id="img_puntos_base" class="form-control">
                              <div class="form-group">
                                <div class="col-md-12" style="cursor: pointer;"  id="celda1" name="celda1">
                                  <div class="form-group">
                                    <div class="grid-block"
                                      style="background-image: url('/images/hospitalizacion_enfermeria/imagen2.jpg'); background-repeat: no-repeat;">
                                      <canvas id="plano" name="plano" width="552" height="552"></canvas>
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
                          <div class="col-lg-2 col-md-3">
                            <input type="hidden" value="4,5,6" id="puntaje_calculado_glasgow_memoria"
                              name="puntaje_calculado_glasgow_memoria">
                            <label for="tipo">ESCALA DE GLASGOW</label>
                            <div class="input-group">
                              <input type="text" id="puntaje_calculado_glasgow" name="puntaje_calculado_glasgow"
                                class="form-control puntaje_calculado_glasgow" style="height: 30px !important;"
                                readonly>
                              <span class="input-group-btn">
                                <button class="btn btn-success btn-xs calcularEscalaGlasgow" id="calcularEscalaGlasgow"
                                  name="calcularEscalaGlasgow" style="height: 30px !important;" type="button">
                                  <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                                </button>
                              </span>
                            </div>
                          </div>
                          @if ($establecimiento[0]->id != 77)
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">VALORACIÓN DE GLASGOW</label>
                            <input type="text" class="form-control descripcion_detalle_Glasgow"
                              id="descripcion_detalle_Glasgow" name="descripcion_detalle_Glasgow"
                              style="height: 30px !important;" readonly="">
                          </div>
                          @endif
                          <div class="col-lg-2 col-md-4">
                            <input type="hidden" value="0,0,0,0,0,0" id="puntaje_calculado_lpp_memoria"
                              name="puntaje_calculado_lpp_memoria">
                            <label for="tipo">ESCALA DE RIESGO LESION POR PRESION</label>
                            <div class="input-group">
                              <input type="text" id="puntaje_calculado_lpp" name="puntaje_calculado_lpp"
                                class="form-control puntaje_calculado_lpp" style="height: 30px !important;" readonly>
                              <span class="input-group-btn">
                                <button class="btn btn-success btn-xs calcularEscalaLPP" id="calcularEscalaLPP"
                                  name="calcularEscalaLPP" style="height: 30px !important;" type="button">
                                  <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                                </button>
                              </span>
                            </div>
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">VALORACIÓN DE ESCALA LESION POR PRESION
                            </label>
                            <input type="text" class="form-control descripcion_detalle_riesgolpp"
                              id="descripcion_detalle_riesgolpp" name="descripcion_detalle_riesgolpp"
                              style="height: 30px !important;" readonly="">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-lg-2 col-md-4">
                            <input type="hidden" value="0,FF0,FA0,0,FAAS0" id="puntaje_calculado_caidas_memoria"
                              name="puntaje_calculado_caidas_memoria">
                            <label for="tipo">ESCALA DE RIESGO DE CAÍDAS(J.H DONWTON)</label>
                            <div class="input-group">
                              <input type="text" id="puntaje_calculado_caidas" name="puntaje_calculado_caidas"
                                class="form-control puntaje_calculado_caidas" style="height: 30px !important;" readonly>
                              <span class="input-group-btn">
                                <button class="btn btn-success btn-xs calcularEscalaCAIDAS" id="calcularEscalaCAIDAS"
                                  name="calcularEscalaCAIDAS" style="height: 30px !important;" type="button">
                                  <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                                </button>
                              </span>
                            </div>
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">VALORACIÓN DE ESCALA DE RIESGO DE CAÍDAS</label>
                            <input type="text" class="form-control descripcion_detalle_caidas"
                              id="descripcion_detalle_caidas" name="descripcion_detalle_caidas"
                              style="height: 30px !important;" readonly="">
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">ESCALA DEL DOLOR</label>
                            <select class="form-control input-sm" id="escalas" name="escalas" style="width: 100%;">
                              <option value="0">SIN DOLOR (0)</option>
                              <option value="1">DOLOR LEVE (1-2-3)</option>
                              <option value="2">DOLOR MODERADO (4-5-6-7)</option>
                              <option value="3">DOLOR SEVERO (8-9-10)</option>
                            </select>
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
                            <!--div class="col-lg-2 col-md-4">
                              <label for="tipo" style="color: red;">BRAZALETE DE IDENTIFICACIÓN</label> <br>
                              <input type="radio" checked="" class="regular-radio" name="brazalete" id="brazalete1"
                                style="cursor: pointer;" value="SI">
                              <b>
                                <a href="javascript:void(0)" id="click_brazalete_si" name="click_brazalete_si"
                                  onclick="seleccionar_radio_por_texto('click_brazalete_si','brazalete1','brazalete_opcion','show')">SI</a>
                              </b>
                              <input type="radio" checked="" class="regular-radio" name="brazalete" id="brazalete2"
                                value="NO" style="margin-left: 10px;cursor: pointer;">
                              <b>
                                <a href="javascript:void(0)" id="click_brazalete_no" name="click_brazalete_no"
                                  onclick="seleccionar_radio_por_texto('click_brazalete_no','brazalete2','brazalete_opcion','hide')">NO</a>
                              </b>
                            </div>
                            <div class="col-lg-2 col-md-4" id="brazalete_opcion" name="brazalete_opcion">
                              <label for="tipo" style="color: red;">COLOR DE BRAZALETE</label>
                              <select class="form-control input-sm" id="brazalete_seleccion" name="brazalete_seleccion"
                                style="width: 100%;">
                                <option value="BLANCO">BLANCO</option>
                                <option value="ROJO">ROJO</option>
                                <option value="NARANJA">NARANJA</option>
                                <option value="AMARILLO">AMARILLO</option>

                              </select>
                            </div-->
                           
                           
                          <!--div class="col-lg-2 col-md-4">
                            <label for="tipo">LEYENDA DE BRAZALETES</label>

                            <ul class="list-unstyled" style="margin-top: 10px; font-size: 11px;">
                              
                              <li style="margin-bottom: 5px; white-space: nowrap;">
                                <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(255, 255, 255); border: 1px solid #000; margin-right: 10px;"></span>
                                SIN CRITERIO
                            </li>
                              <li style="margin-bottom: 5px; white-space: nowrap;">
                                  <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(230, 59, 59); border: 1px solid #000; margin-right: 10px;"></span>
                                  MUY URGENTE - REQUIERE ATENCION INMEDIATA
                              </li>
                              <li style="margin-bottom: 5px; white-space: nowrap;">
                                  <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(223, 123, 77); border: 1px solid #000; margin-right: 10px;"></span>
                                  URGENTE - MAXIMO 10 MIN(RIESGO DE VIDA)
                              </li>
                              <li style="margin-bottom: 5px; white-space: nowrap;">
                                  <span style="display: inline-block; width: 25px; height: 17px; background-color: rgb(248, 244, 19); border: 1px solid #000; margin-right: 10px;"></span>
                                  GRAVE-ATENCION MAX EN 1 HRS(SIN RIESGO DE VIDA)
                              </li>
                          </ul>
                          
                          </div-->
                          
                          </div>  

                      </fieldset>
                    </div>

                    <div class="tab-pane fade" id="tab6default">
                      <fieldset>
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>DETALLE</label>
                            <textarea class="form-control" rows="10" id="observacion_detallada" name="observacion_detallada"
                                      placeholder="Observaciones Generales"></textarea>
                            <span id="mensajeCaracteresRestantes" style="color: red;"></span>
                        </div>
                        </div>
                      </fieldset>
                    </div>

                  </div>
                </div>
              </div>




              <img id="imagenCapturada" style="display: none;" />
              <input class="btn btn-primary pull-right" value="GUARDAR" name="crea" tabindex="18" type="button"
                form="prestacionescrea" id="btnNota" />
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
<!--fdfdfdfd -->

@include('frontend.hospitalizacion.enfermeria.nota_ingreso.modal_nota_ingreso.escalas.glasgow')
@include('frontend.hospitalizacion.enfermeria.nota_ingreso.modal_nota_ingreso.escalas.lpp')
@include('frontend.hospitalizacion.enfermeria.nota_ingreso.modal_nota_ingreso.escalas.caidas')

@include('frontend.includes.footer')
@endsection