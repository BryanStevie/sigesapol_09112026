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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
  //LLENAMOS LOS COMBOS DESDE LA TABLA DE PARAMETROS
  lista_parametros('CARDIOLOGICO_NOT_ENF','cardiologico','combo_multiple','TABLA_PARAMETROS',0);
  lista_parametros('INGRESA_POR','ingresa_por','combo','TABLA_PARAMETROS',0);
  //lista_parametros('CONDICION_INGRESO','condicion_ingreso','combo','TABLA_PARAMETROS',0);
  //console.log("")
  // lista_parametros('ESTADO_CONCIENCIA','estado_conciencia','combo','TABLA_PARAMETROS',0);
  // lista_parametros('FUNCION_MOTORA','funcion_motora','combo','TABLA_PARAMETROS',0);
  // lista_parametros('RESPIRATORIO','respiratorio_duracion','combo','TABLA_PARAMETROS',0);
  lista_parametros('SECRECIONES','secreaciones_bronquiales','combo','TABLA_PARAMETROS',0);
  // lista_parametros('OXIGENOTERAPIA','oxigenoterapia_detalle_seleccion  ','combo','TABLA_PARAMETROS',0);
  // lista_parametros('PIEL','piel_colores','combo','TABLA_PARAMETROS',0);
  // lista_parametros('INTEGRIDAD','integridad','combo','TABLA_PARAMETROS',0);
  // lista_parametros('ABDOMEN','abdomen','combo','TABLA_PARAMETROS',0);
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



  function compruebaindice_masa_corporal() {
    
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
      var sexo = '{{$datos_atencion[0]->sexo}}'; //MASCULINO , FEMENINO
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
      //indice_masa_corporal
      var indice_masa_corporalx = (peso / (talla_en_metros * talla_en_metros)).toFixed(2);
      $('#indice_masa_corporal').val(indice_masa_corporalx) ; 

      if (indice_masa_corporalx < 18.5) {
        $('#indice_masa_corporal').addClass('borde-indice_masa_corporal-bajopeso');
        $('#indice_masa_corporal-aceptado').html("<strong>" + 'Paciente Bajo de Peso' + "</strong>");

      } else if (indice_masa_corporalx >= 18.5 && indice_masa_corporalx< 24.9) {
        $('#indice_masa_corporal').addClass('borde-indice_masa_corporal-normal');
        $('#indice_masa_corporal-aceptado').html("<strong>" + 'Paciente Peso Normal' + "</strong>");
        //NORMAL
      } else if (indice_masa_corporalx >= 25.0 && indice_masa_corporalx< 29.9){
        $('#indice_masa_corporal').addClass('borde-indice_masa_corporal-sobrepeso');
        $('#indice_masa_corporal-aceptado').html("<strong>" + 'Paciente Sobrepeso' + "</strong>");
        //SOBREPESO
      } else if (indice_masa_corporalx >= 30.0 && indice_masa_corporalx< 34.9){
        $('#indice_masa_corporal').addClass('borde-indice_masa_corporal-obesidad1');
        $('#indice_masa_corporal-aceptado').html("<strong>" + 'Paciente Obesidad I' + "</strong>");
        //OBESIDAD I
      } else if (indice_masa_corporalx >= 35.0 && indice_masa_corporalx< 39.9){
        $('#indice_masa_corporal').addClass('borde-indice_masa_corporal-obesidad2');
        $('#indice_masa_corporal-aceptado').html("<strong>" + 'Paciente Obesidad II' + "</strong>");
        // OBESIDAD II
      } else if (indice_masa_corporalx >= 40.0){
        $('#indice_masa_corporal').addClass('borde-indice_masa_corporal-obesidad3');
        $('#indice_masa_corporal-aceptado').html("<strong>" + 'Paciente Obesidad III' + "</strong>");
        // OBESIDAD III
      }

    } else {
      $('#indice_masa_corporal').val("") ;
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
 

  const mallampati_1 = document.querySelector('#mallampati_1_div'); 
  const mallampati_2 = document.querySelector('#mallampati_2_div'); 
  const mallampati_3 = document.querySelector('#mallampati_3_div'); 
  const mallampati_4 = document.querySelector('#mallampati_4_div');  
  
  mallampati_1.addEventListener('click', () => { 
    $("#descripcion_evaluacion_mallampaty").val("1").change();
  });
  mallampati_2.addEventListener('click', () => {  
    $("#descripcion_evaluacion_mallampaty").val("2").change();
  });
  mallampati_3.addEventListener('click', () => {  
    $("#descripcion_evaluacion_mallampaty").val("3").change();
  });
  mallampati_4.addEventListener('click', () => {  
    $("#descripcion_evaluacion_mallampaty").val("4").change();
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
      //alert("Le has dado click");
      var rect = document.getElementById('plano').getBoundingClientRect();
      var x = event.clientX - rect.left;
      var y = event.clientY - rect.top;
      $('#x').val(x)
      $('#y').val(y)
      var id_atencion=$('#id_atencion').val();
      var id_tipo_atenciones=$('#id_tipo_atenciones').val();
 
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

    pintar_puntos_by_hospitalizacion({{$id_tipo_atenciones}}, "control_div_puntos", "control_div_puntos_leyenda", "NOTA_ENFERMERIA",'REGISTRO', {{$id_tipo_atenciones}});  
     

    // SE MODIFICA LA LOGICA PARA TRAER EL HTA 
    if ($('#hta1').is(':checked')) {
        $('#hta_tratamiento').show();
    } else {
        $('#hta_tratamiento').hide();
    }

    $("#hta1").change(function() {
        if ($(this).is(':checked')) {
            $('#hta_tratamiento').show();
        }
    });

    $("#hta2").change(function() {
        if ($(this).is(':checked')) {
            $('#hta_tratamiento').hide();
        }
    }); 
   
    if ($('input[name="hta"]:checked').val() === 'SI') {
        $('#hta_tratamiento').show();
    } else {
        $('#hta_tratamiento').hide();
    }
// se modifica la logica para diabetes mellitus
    if ($('input[name="diabetes_mellitus"]:checked').val() === 'SI') {
        $('#diabetes_mellitus_observacion').show();
    } else {
        $('#diabetes_mellitus_observacion').hide();
    }

    // Maneja los cambios en los campos de radio
    // $("input[name=hta]").click(function () {
    //     if ($(this).val() === 'SI') {
    //         $('#hta_tratamiento').show();
    //     } else {
    //         $('#hta_tratamiento').hide();
    //     }
    // });

    $("input[name=diabetes_mellitus]").click(function () {
        if ($(this).val() === 'SI') {
            $('#diabetes_mellitus_observacion').show();
        } else {
            $('#diabetes_mellitus_observacion').hide();
        }
    });
    
//MODIFICO LA LOGICA PARA RAM DESCRIPCION O ALERGIAS DESCRIPCION

    if ($('input[name="ram"]:checked').val() === 'SI') {
        $('#ram_descripcion').show();
    } else {
        $('#ram_descripcion').hide();
    }

    // Maneja los cambios en los campos de radio
    $("input[name=ram]").click(function () {
        if ($(this).val() === 'SI') {
            $('#ram_descripcion').show();
        } else {
            $('#ram_descripcion').hide();
        }
    });
    



    


    
    $("#oxigenoterapia_detalle_seleccion").select2({ width: 'resolve' });

// Mostrar/ocultar detalle de oxigenoterapia basado en el valor seleccionado inicialmente
function toggleOxigenoterapiaDetalle() {
    if ($('input[name="oxigenoterapia"]:checked').val() === 'SI') {
        $('#oxigenoterapia_detalle').show();
    } else {
        $('#oxigenoterapia_detalle').hide();
    }
}

// Llamar a la función para establecer el estado inicial
toggleOxigenoterapiaDetalle();

// Manejar el cambio de valor de los radio buttons
$("input[name=oxigenoterapia]").click(function () {
    toggleOxigenoterapiaDetalle();
});

    $('#orientado_form').hide();
    $("#estado_conciencia").change(function() {   
      var estado_conciencia = $('#estado_conciencia').val();
      if(estado_conciencia==1){
        $('#orientado_form').show(); 
      }else{
        $('#orientado_form').hide();
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

    if ($('input[name="acompaniado_por"]:checked').val() === 'FAMILIAR') {
        $('#acompaniado_por_detalle').show();
    } else {
        $('#acompaniado_por_detalle').hide();
    }

    $("input[name=acompaniado_por]").click(function () {
        if ($(this).val() === 'FAMILIAR') {
            $('#acompaniado_por_detalle').show();
        } else {
            $('#acompaniado_por_detalle').hide();
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
     
     if ($('input[name="drenaje"]:checked').val() === 'SI') {
    $('#drenaje_descripcion').show();
  } else {
    $('#drenaje_descripcion').hide();
  }

  // Añade el evento click para los radio buttons
  $('input[name="drenaje"]').click(function() {
    if ($(this).val() === 'SI') {
      $('#drenaje_descripcion').show();
    } else {
      $('#drenaje_descripcion').hide();
    }
  }); 
    // Configura Select2
    $("#descripcion_evaluacion_mallampaty").select2({ width: 'resolve' });

    // Mostrar u ocultar el campo de detalle basado en el valor inicial del radio button
    if ($('input[name="evaluacion_mallampaty"]:checked').val() === 'SI') {
        $('#evaluacion_mallampaty_opcion').show();
    } else {
        $('#evaluacion_mallampaty_opcion').hide();
    }

    // Manejar clics en los radio buttons
    $("input[name=evaluacion_mallampaty]").click(function () {
        if ($(this).val() === 'SI') {
            $('#evaluacion_mallampaty_opcion').show();
        } else {
            $('#evaluacion_mallampaty_opcion').hide();
        }
    });



    $("#descripcion_evaluacion_mallampaty").change(function(e) {
      var descripcion_evaluacion_mallampaty= $('#descripcion_evaluacion_mallampaty').val();
      if(descripcion_evaluacion_mallampaty=='*'){ 
        $('#mallampati_1').addClass('img_blanco_negro');
        $('#mallampati_2').addClass('img_blanco_negro'); 
        $('#mallampati_3').addClass('img_blanco_negro'); 
        $('#mallampati_4').addClass('img_blanco_negro'); 
      }else if(descripcion_evaluacion_mallampaty=='1'){ 
        $('#mallampati_1').removeClass('img_blanco_negro');
        $('#mallampati_2').addClass('img_blanco_negro'); 
        $('#mallampati_3').addClass('img_blanco_negro'); 
        $('#mallampati_4').addClass('img_blanco_negro'); 
      }else if(descripcion_evaluacion_mallampaty=='2'){
        $('#mallampati_1').addClass('img_blanco_negro'); 
        $('#mallampati_2').removeClass('img_blanco_negro');
        $('#mallampati_3').addClass('img_blanco_negro'); 
        $('#mallampati_4').addClass('img_blanco_negro');  
      }else if(descripcion_evaluacion_mallampaty=='3'){
        $('#mallampati_1').addClass('img_blanco_negro'); 
        $('#mallampati_2').addClass('img_blanco_negro'); 
        $('#mallampati_3').removeClass('img_blanco_negro');
        $('#mallampati_4').addClass('img_blanco_negro'); 
      }else if(descripcion_evaluacion_mallampaty=='4'){
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
 
      

    $("#tipo_proceso").select2({ width: 'resolve'}); 
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
    }, 1500);
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
      $('#indice_masa_corporal').removeClass('borde-indice_masa_corporal-bajopeso borde-indice_masa_corporal-normal borde-indice_masa_corporal-sobrepeso borde-indice_masa_corporal-obesidad1 borde-indice_masa_corporal-obesidad2 borde-indice_masa_corporal-obesidad3');
        $('#indice_masa_corporal-aceptado').html('');
      });
    
    $("#talla").on('blur',function(event) {  
      var value = $(this).val();
      if (Validar.esVacio(value) == false) {
        if (!Validar.enteroEnRango(value, 30, 200)) {
          $('#talla').addClass('borde-temperatura-hipertermia');
          $('#talla-aceptado').html("<p><strong>" + 'El valor de la talla debe ser entre 30 a 200 ( cm )' + "</strong></p>");
          $('#talla-aceptado strong').addClass('borde-temperatura-valida');
        }else {
          compruebaindice_masa_corporal();
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
        compruebaindice_masa_corporal();
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
            compruebaindice_masa_corporal();
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
          compruebaindice_masa_corporal();
        }
    });

    $("#peso").on('input',function() {
      $('#peso').removeClass('borde-temperatura-hipertermia');
      $('#peso-aceptado').html('');
    });

    $("#peso").click(function() {
      $('#indice_masa_corporal').removeClass('borde-indice_masa_corporal-bajopeso borde-indice_masa_corporal-normal borde-indice_masa_corporal-sobrepeso borde-indice_masa_corporal-obesidad1 borde-indice_masa_corporal-obesidad2 borde-indice_masa_corporal-obesidad3');
      $('#indice_masa_corporal-aceptado').html('');
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

$("#frecuencia_respiratoria").on('blur',function(event) {

  var value = $('#frecuencia_respiratoria').val();
  if (Validar.esVacio(value) == false ) {
    // if (!Validar.enteroEnRango(value, 10, 35)) {
    if (value.length==1) {
      $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
      $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'El valor de la Frec. Respiratoria  debe ser mayor a un dígito' + "</strong></p>");
      $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
    } 
          
  }else if (Validar.esVacio(value)) {
      $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
      $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Respiratoria ( /min )' + "</strong></p>");
      $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
  } 
});

$("#frecuencia_respiratoria").on('input',function(event) {
      
    var value = $('#frecuencia_respiratoria').val();
    if (Validar.esVacio(value) ) {
          $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
          $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Respiratoria ( /min )' + "</strong></p>");
          $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
          
    } else if (!Validar.enteroEnRango(value, 10, 35)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'El valor de la Frec. Respiratoria  debe estar entre 10 a 35 ( /min )' + "</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');

    }  
});

$("#frecuencia_respiratoria").on('input',function() {
$('#frecuencia_respiratoria').removeClass('borde-temperatura-hipertermia');
$('#frecuencia_respiratoria-aceptado').html('');
});
//-------------------------------------------------------------------------------------------------
$("#frec_cardiaca").on('blur',function(event) {
     
    var value = $('#frec_cardiaca').val();
    if (Validar.esVacio(value) == false ) {
      // if (!Validar.enteroEnRango(value, 60, 100)) {
      if (value.length==1) {
        $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser mayor a un dígito' + "</strong></p>");
        $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
      }
    }else if (Validar.esVacio(value)) {
      $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
      $('#frec_cardiaca-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Cardiaca ( /min )' + "</strong></p>");
      $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    } 
    
});

$("#frec_cardiaca").on('input',function(event) {
   
    var value = $('#frec_cardiaca').val();
    if (Validar.esVacio(value) ) {
        $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Cardiaca ( /min )' + "</strong></p>");
        $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
        
    } else if (!Validar.enteroEnRango(value, 50, 150)) {
        $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 50 a 150 ( /min )' + "</strong></p>");
        $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    }  
    
});

$("#frec_cardiaca").on('input',function() {
  $('#frec_cardiaca').removeClass('borde-temperatura-hipertermia');
  $('#frec_cardiaca-aceptado').html('');
});
//-------------------------------------------------------------------------------------------------

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
            $('#btnNota').on("click", function (e) {
                e.preventDefault();
                guardarNota();
            });

            function guardarNota() {
              //signos VISTALES
    let presion_arterial_x = $('#presion_arterial_x').val();
    let presion_arterial_y = $('#presion_arterial_y').val();
    let frecuencia_cardiaca = $('#frecuencia_cardiaca').val();
    let frecuencia_respiratoria = $('#frecuencia_respiratoria').val();
    let fi02 = $('#fi02').val();
    let sa02_porcentaje = $('#sa02_porcentaje').val();
    let temperatura = $('#temperatura').val();
    let talla = $('#talla').val();
    let peso = $('#peso').val();
    let indice_masa_corporal = $('#indice_masa_corporal').val();
    let peso_x_altura = $('#peso_x_altura').val();
    let id_nota_ingreso = $('#id_nota_ingreso').val();
    let id_tipo_atenciones = $('#id_tipo_atenciones').val();
    //ANTECEDENTES
    let hta = $('input[name="hta"]:checked').val();
    let descripcion_tratamiento_hta = hta === 'SI' ? $('#descripcion_tratamiento_hta').val() : '';
    let diabetes_mellitus = $('input[name="diabetes_mellitus"]:checked').val();
    let diabetes_mellitus_descripcion = diabetes_mellitus === 'SI' ? $('#diabetes_mellitus_descripcion').val() : '';
    let ram = $('input[name="ram"]:checked').val();
    let detalle_ram_alergia = ram === 'SI' ? $('#detalle_ram_alergia').val() : '';
    let descripcion_antecedente_observacion = $('#descripcion_antecedente_observacion').val();
    let descripcion_otros_antecedentes = $('#descripcion_otros_antecedentes').val();
    
    // let estado_conciencia = $('#estado_conciencia').val(); // Obtener el valor seleccionado
    // let secreaciones_bronquiales_si = $('#secreaciones_bronquiales_si').val();
    let condicion_ingreso = $('#condicion_ingreso').val(); // Obtener el valor seleccionado del select
    let estado_conciencia = $('#estado_conciencia').val(); // Obt  
    let funcion_motora=$('#funcion_motora').val();
    let respiratorio_duracion=$('#respiratorio_duracion').val();       
    let miccion_espontanea = $('input[name="miccion_espontanea"]:checked').val();
    let oxigenoterapia = $('input[name="oxigenoterapia"]:checked').val();
    let oxigenoterapia_detalle_seleccion=$('#oxigenoterapia_detalle_seleccion').val();   
    let acompaniado_por = $('input[name="acompaniado_por"]:checked').val();
    let descripcion_acompaniado_por=$('#descripcion_acompaniado_por').val();   
    let emoptisis = $('input[name="emoptisis"]:checked').val();
    let piel_colores=$('#piel_colores').val();   
    let integridad=$('#integridad').val(); 
    let abdomen=$('#abdomen').val(); 
    let via_periferica = $('input[name="via_periferica"]:checked').val();
    let via_central = $('input[name="via_central"]:checked').val();
    let caracter_alto_flujo = $('input[name="caracter_alto_flujo"]:checked').val();
    let linea_arterial = $('input[name="linea_arterial"]:checked').val();
    let escalas=$('#escalas').val();   
    let brazalete_seleccion=$('#brazalete_seleccion').val();  
    let brazalete = $('input[name="brazalete"]:checked').val();
    let observacion_detallada=$('#observacion_detallada').val();  
    let tiene_evaluacion_mallampaty = $('input[name=evaluacion_mallampaty]:checked').val();
    let descripcion_evaluacion_mallampaty = tiene_evaluacion_mallampaty === 'SI' ? $('#descripcion_evaluacion_mallampaty').val() : '*';
    let puntaje_calculado_glasgow=$('#puntaje_calculado_glasgow').val();  
    let descripcion_detalle_Glasgow=$('#descripcion_detalle_Glasgow').val();  
    let puntaje_calculado_lpp=$('#puntaje_calculado_lpp').val();  
    let descripcion_detalle_riesgolpp=$('#descripcion_detalle_riesgolpp').val();  
    // let evaluacion_mallampaty = $('input[name="evaluacion_mallampaty"]:checked').val();
    // let descripcion_evaluacion_mallampaty = evaluacion_mallampaty === 'SI' ? $('#descripcion_evaluacion_mallampaty').val() : '';
    let puntaje_calculado_caidas=$('#puntaje_calculado_caidas').val();  
    let descripcion_detalle_caidas=$('#descripcion_detalle_caidas').val();  
    $.ajax({
        url: '/update-nota-enfermeria',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            //SIGNOS VISTALES
            id_nota_ingreso: id_nota_ingreso,
            presion_arterial_x: presion_arterial_x,
            presion_arterial_y: presion_arterial_y,
            frecuencia_cardiaca: frecuencia_cardiaca,
            frecuencia_respiratoria: frecuencia_respiratoria,
            fi02: fi02,
            sa02_porcentaje: sa02_porcentaje,
            temperatura: temperatura,
            talla: talla,
            peso: peso,
            indice_masa_corporal: indice_masa_corporal,
            peso_x_altura: peso_x_altura,
            //ANTECEDENTES
            hta: hta,
            descripcion_tratamiento_hta: descripcion_tratamiento_hta,
            diabetes_mellitus: diabetes_mellitus,
            diabetes_mellitus_descripcion: diabetes_mellitus_descripcion,
            ram: ram,
            detalle_ram_alergia: detalle_ram_alergia,
            descripcion_antecedente_observacion:descripcion_antecedente_observacion,
            descripcion_otros_antecedentes: descripcion_otros_antecedentes,
            // secreaciones_bronquiales_si: secreaciones_bronquiales_si,
            condicion_ingreso: condicion_ingreso,
            estado_conciencia:estado_conciencia, // Pasar el valor de la condición de ingreso
            funcion_motora:funcion_motora, // Pasar el valor de la función motora
            respiratorio_duracion:respiratorio_duracion, // Pasar el valor de la función motora
            miccion_espontanea: miccion_espontanea,
            oxigenoterapia:oxigenoterapia,
            oxigenoterapia_detalle_seleccion:oxigenoterapia_detalle_seleccion,
            acompaniado_por:acompaniado_por,
            descripcion_acompaniado_por:descripcion_acompaniado_por,
            emoptisis:emoptisis,
            piel_colores:piel_colores,
            integridad:integridad,
            abdomen:abdomen,
            via_periferica:via_periferica,
            via_central:via_central,
            caracter_alto_flujo:caracter_alto_flujo,
            linea_arterial:linea_arterial,
            escalas:escalas,
            brazalete_seleccion:brazalete_seleccion,
            brazalete:brazalete,
            observacion_detallada:observacion_detallada,
            // evaluacion_mallampaty:evaluacion_mallampaty
            // descripcion_evaluacion_mallampaty:descripcion_evaluacion_mallampaty
            evaluacion_mallampaty: tiene_evaluacion_mallampaty,
            descripcion_evaluacion_mallampaty: descripcion_evaluacion_mallampaty,
            // VALORACIONES
            puntaje_calculado_glasgow:puntaje_calculado_glasgow,
            descripcion_detalle_Glasgow:descripcion_detalle_Glasgow,
            puntaje_calculado_lpp:puntaje_calculado_lpp,
            descripcion_detalle_riesgolpp:descripcion_detalle_riesgolpp,
            puntaje_calculado_caidas:puntaje_calculado_caidas,
            descripcion_detalle_caidas:descripcion_detalle_caidas
          },
    success: function(response) {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Nota de ingreso actualizada correctamente',
                showConfirmButton: true,
                confirmButtonColor: '#28a745',
                timer: 3000
            }).then(() => {
                window.location.href = `/ver_nota_ingreso_enfermeria_emergencia/${id_nota_ingreso}/${id_tipo_atenciones}`;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al actualizar la presión arterial',
                confirmButtonColor: '#d33'
            });
        }
    },
    error: function(xhr) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al actualizar la nota de enfermería',
            confirmButtonColor: '#d33'
        });
    }
});
}

        


function guardarNota1(){
//VALIDACION DE CAMPOS OBLIGATORIOS EN SECCION SIGNOS VITALES
var presion_arterial_x = $('#presion_arterial_x').val();
var presion_arterial_y = $('#presion_arterial_y').val();
//var frec_cardiaca = $('#frec_cardiaca').val();
//var frecuencia_respiratoria = $('#frecuencia_respiratoria').val();
var oxigenacion = $('#oxigenacion').val();
var sa02_porcentaje = $('#sa02_porcentaje').val();
var temperatura = $('#temperatura').val();
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

var descripcion_ao = $('#descripcion_antecedente_observacion').val();
var descripcion_otros_antecedentes = $('#descripcion_otros_antecedentes').val();


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

var cardiologico = $('#cardiologico').val(); 
if( cardiologico == null){
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

if(descripcion_ao == "0" || descripcion_ao == '' || descripcion_ao == '*' || descripcion_ao.length==0){
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
}

if(descripcion_otros_antecedentes == "0" || descripcion_otros_antecedentes == '' || descripcion_otros_antecedentes == '*' || descripcion_otros_antecedentes.length==0){
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
}

var ingresa_por = $('#ingresa_por').val(); 
if( ingresa_por == "0" || ingresa_por == '' || ingresa_por == '*' || ingresa_por.length==0){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Recuerde seleccionar un TIPO DE INGRESO válido.",
    icon: "error",
    timer: 2500,
  });    
  cambiar_de_PestanaTab("tab2default"); 
  $('#ingresa_por').next('.select2-container').addClass('borde-alerta'); 
  setTimeout(function() {
    $('#ingresa_por').focus(); 
  }, 1000);
  return;
}else{
  $('#ingresa_por').next('.select2-container').removeClass('borde-alerta');
}
 
if( ingresa_por == "14"){ 
  var ingresa_por_transferencia = $('#ingresa_por_transferencia').val(); 
  if( ingresa_por_transferencia == "0" || ingresa_por_transferencia == '' || ingresa_por_transferencia == '*' || ingresa_por_transferencia.length==0){
    swal({
      buttons: false,
      title: "Mensaje de Error",
      text: "Recuerde seleccionar un TIPO DE INGRESO POR TRASFERENCIA válido.",
      icon: "error",
      timer: 2500,
    });    
    cambiar_de_PestanaTab("tab2default"); 
    $('#ingresa_por_transferencia').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#ingresa_por_transferencia').focus(); 
    }, 1000);
    return;
  }  else{
    $('#ingresa_por_transferencia').next('.select2-container').removeClass('borde-alerta');
  }
}else{
  $('#ingresa_por_transferencia').next('.select2-container').removeClass('borde-alerta');
}

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
    }, 2000);
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

var puntaje_calculado_glasgow= $('#puntaje_calculado_glasgow').val();
if(puntaje_calculado_glasgow==''){
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
      $('#puntaje_calculado_glasgow').focus(); 
    }, 1000); 
  return;
}else{
  $('#puntaje_calculado_glasgow').removeClass('borde-alerta');
} 

var descripcion_detalle_glasgow= $('#descripcion_detalle_glasgow').val();
if(descripcion_detalle_glasgow==''){
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
}



// calculando valortotal LPP

var puntaje_calculado_lpp= $('#puntaje_calculado_lpp').val();
    if(puntaje_calculado_lpp==''){
      swal({
        buttons: false,
        title: "Mensaje de Error",
        text: "Recuerde realizar el cálculo DE LA ESCALA DE GLASGOW válida.",
        icon: "error",
        timer: 2500,
      });  
      cambiar_de_PestanaTab("tab2default"); 
      $('#puntaje_calculado_lpp').addClass('borde-alerta');
        setTimeout(function() {
          $('#puntaje_calculado_lpp').focus(); 
        }, 1000); 
      return;
    }else{
      $('#puntaje_calculado_lpp').removeClass('borde-alerta');
    } 

// calculando DE LPP 
var descripcion_detalle_riesgolpp= $('#descripcion_detalle_riesgolpp').val();
if(descripcion_detalle_riesgolpp==''){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Recuerde realizar el cálculo DE LA ESCALA DE detalle riesgo lpp válida.",
    icon: "error",
    timer: 2500,
  });  
  cambiar_de_PestanaTab("tab2default"); 
  $('#puntaje_calculado_lpp').addClass('borde-alerta');
    setTimeout(function() {
      $('#descripcion_detalle_riesgolpp').focus(); 
    }, 1000); 
  return;
}else{
  $('#puntaje_calculado_lpp').removeClass('borde-alerta');
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



var ingresa_por = $('#ingresa_por').val(); 
if( ingresa_por == "0" || ingresa_por == '' || ingresa_por == '*' || ingresa_por.length==0){
  swal({
    buttons: false,
    title: "Mensaje de Error",
    text: "Recuerde seleccionar un TIPO DE INGRESO válido.",
    icon: "error",
    timer: 2500,
  });    
  cambiar_de_PestanaTab("tab2default"); 
  $('#ingresa_por').next('.select2-container').addClass('borde-alerta'); 
  setTimeout(function() {
    $('#ingresa_por').focus(); 
  }, 1000);
  return;
}else{
  $('#ingresa_por').next('.select2-container').removeClass('borde-alerta');
}
 

  var piel_colores = $('#piel_colores').val(); 
  if( piel_colores == "0" || piel_colores == '' || piel_colores == '*' || piel_colores.length==0){
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
  var descripcion_evaluacion_mallampaty = $('#descripcion_evaluacion_mallampaty').val(); 
  if( descripcion_evaluacion_mallampaty == "0" || descripcion_evaluacion_mallampaty == '' || descripcion_evaluacion_mallampaty == '*' || descripcion_evaluacion_mallampaty.length==0){
    swal({
      buttons: false,
      title: "Mensaje de Error",
      text: "Recuerde seleccionar un TIPO EVALUACIÓN MALLAMPATY válido.",
      icon: "error",
      timer: 2500,
    });    
    cambiar_de_PestanaTab("tab2default"); 
    $('#descripcion_evaluacion_mallampaty').next('.select2-container').addClass('borde-alerta');
    setTimeout(function() {
      $('#descripcion_evaluacion_mallampaty').focus(); 
    }, 1000);
    return;
  }  else{
    $('#descripcion_evaluacion_mallampaty').next('.select2-container').removeClass('borde-alerta');
  }
}


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
    var descripcion_evaluacion_mallampaty= $('#descripcion_evaluacion_mallampaty').val();
    if(descripcion_evaluacion_mallampaty==''){
      swal({
        buttons: false,
        title: "Mensaje de Error",
        text: "Recuerde seleccionar una EVALUACIÓN MALLAMPATY válida.",
        icon: "error",
        timer: 2500,
      });   
      cambiar_de_PestanaTab("tab4default"); 
      $('#descripcion_evaluacion_mallampaty').next('.select2-container').addClass('borde-alerta');
      setTimeout(function() {
        $('#descripcion_evaluacion_mallampaty').focus(); 
      }, 1000);
      return;
    } else{
      $('#descripcion_evaluacion_mallampaty').next('.select2-container').removeClass('borde-alerta');
    } 
  } else{
    $('#descripcion_evaluacion_mallampaty').next('.select2-container').removeClass('borde-alerta');
  } 
}else{
  $('#descripcion_sonda').removeClass('borde-alerta');
  $('#descripcion_drenaje').removeClass('borde-alerta');
  $('#descripcion_evaluacion_mallampaty').next('.select2-container').removeClass('borde-alerta');
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
var tipo_proceso = $('#tipo_proceso').val();
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
}


cambiar_de_PestanaTab("tab4default");

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

  .borde-indice_masa_corporal-bajopeso {
    border-color: skyblue;
    border: 2px skyblue solid;
  }

  .borde-indice_masa_corporal-normal {
    border-color: green;
    border: 2px green solid;
  }

  .borde-indice_masa_corporal-sobrepeso {
    border-color: yellow;
    border: 2px yellow solid;
  }

  .borde-indice_masa_corporal-obesidad1 {
    border-color: orange;
    border: 2px orange solid;
  }

  .borde-indice_masa_corporal-obesidad2 {
    border-color: orangered;
    border: 2px orangered solid;
  }

  .borde-indice_masa_corporal-obesidad3 {
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
              <input type="hidden" name="fecha_nacimiento_beneficiario" id="fecha_nacimiento_beneficiario"
                class="form-control" readonly=""
                ">
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
                                  <div tabindex="19" class="form-control" style="text-align:left;">EMERGENCIA
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <label for="tipo_doc_medico">FECHA DE LA ATENCION</label>
                                  <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->fecha_creacion}}
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_materno">HORA DE LA ATENCION</label>
                                  <input tabindex="21" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->hora_creacion}}" name="M" id="M" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">PRIORIDAD</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->des_prioridades}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                  <label for="medico_nombre">AREA</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->topico}}" name="M1" id="M1" style="text-align:left">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-2">
                                  <label for="medico_nombre">CAMA</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->cama}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">N° ORDEN</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="2" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">N° HISTORIA CLINICA</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->nro_doc_ident}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                  <label for="medico_nombre">CODIGO UPS</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->codigo_ups}}" name="M1" id="M1" style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                  <label for="medico_nombre">NOMBRE DE LA IPRESS</label>
                                  <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->nombre_estab}}" name="M1" id="M1" style="text-align:left">
                                </div>
                              </div>
                              
                            </div>
                          </div>
                        </fieldset>
                      </div>
                     
                        <!--SI id_tipo_beneficiario es igual a 7 mostrar esto si no ocultarlo todo el div -->

                      <fieldset>
                        <div id="container" data-id-tipo-beneficiario="{{ $datos_atencion[0]->id_tipo_beneficiario }}">
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
                                <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->dni_beneficiario}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->paterno_beneficiario}}
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
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->sexo}}" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">FECHA_NAC</label>
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->fecha_nacimiento_beneficiario}}" name="M1" id="M1" style="text-align:left">
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
                                <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_atencion[0]->dni_conducido . ' - ' . $datos_atencion[0]->nombre_conducido }}" name="M1" id="M1" style="text-align:left">
                            </div>
                            <div class="col-md-2" style="display: none;">
                              <label for="medico_nombre">TELEFONO</label>
                              <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->telf_conducido}}" name="M1" id="M1" style="text-align:left">
                          </div>
                          @else
        <!-- Mostrar DOC IDENTIDAD Y NOMBRES -->
                              <div class="col-md-4">
                                  <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_atencion[0]->dni_conducido . ' - ' . $datos_atencion[0]->nombre_conducido }}" name="M1" id="M1" style="text-align:left">
                              </div>

                              <!-- Mostrar TELEFONO -->
                              <div class="col-md-2">
                                  <label for="medico_nombre">TELEFONO</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->telf_conducido}}" name="M1" id="M1" style="text-align:left">
                              </div>
                          @endif

                          @if ($datos_atencion[0]->conducio_por === 'SOLO')
                              <!-- Mostrar DESCRIPCION -->
                              <div class="col-md-10">
                                  <label for="medico_nombre">DESCRIPCION</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->observaciones_benefi}}" name="M1" id="M1" style="text-align:left">
                              </div>
                          @endif
                          </div>
                          
                          
                          </div>
                          </div>
                        </div>
                        <!--DERECHO HABIENTE-->

                       <div id="container" data-id-tipo-beneficiario="{{ $datos_atencion[0]->id_tipo_beneficiario }}">
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
                                <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->dni_beneficiario}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->paterno_beneficiario}}
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
                                <input tabindex="22" readonly="" type="text" class="form-control" value="{{$datos_atencion[0]->fecha_nacimiento_beneficiario}}" name="M1" id="M1" style="text-align:left">
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
                                  <input tabindex="22" readonly type="text" class="form-control" value="" name="M1" id="M1" style="text-align:left">
                              </div>
        <!-- Mostrar DOC IDENTIDAD Y NOMBRES -->
                                <div class="col-md-2">
                                  <label for="medico_nombre">TITULAR</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">CIP</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="DNI:" name="M1" id="M1" style="text-align:left">
                            </div>
                              <div class="col-md-2">
                                  <label for="medico_nombre">GRADO</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="DNI:" name="M1" id="M1" style="text-align:left">
                              </div>
                              <div class="col-md-2">
                                <label for="medico_nombre">SITUACION</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="DNI:" name="M1" id="M1" style="text-align:left">
                            </div>
                              <!-- Mostrar TELEFONO -->
                              <!-- Mostrar DESCRIPCION -->
                              <div class="col-md-1">
                                  <label for="medico_nombre">TELEFONO</label>
                                  <input tabindex="22" readonly type="text" class="form-control" value="" name="M1" id="M1" style="text-align:left">
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
                              <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_atencion[0]->dni_conducido . ' - ' . $datos_atencion[0]->nombre_conducido }}" name="M1" id="M1" style="text-align:left">
                          </div>
                          <div class="col-md-2" style="display: none;">
                            <label for="medico_nombre">TELEFONO</label>
                            <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->telf_conducido}}" name="M1" id="M1" style="text-align:left">
                        </div>
                        @else
      <!-- Mostrar DOC IDENTIDAD Y NOMBRES -->
                            <div class="col-md-4">
                                <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="DNI: {{$datos_atencion[0]->dni_conducido . ' - ' . $datos_atencion[0]->nombre_conducido }}" name="M1" id="M1" style="text-align:left">
                            </div>

                            <!-- Mostrar TELEFONO -->
                            <div class="col-md-2">
                                <label for="medico_nombre">TELEFONO</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->telf_conducido}}" name="M1" id="M1" style="text-align:left">
                            </div>
                        @endif

                        @if ($datos_atencion[0]->conducio_por === 'SOLO')
                            <!-- Mostrar DESCRIPCION -->
                            <div class="col-md-4">
                                <label for="medico_nombre">DESCRIPCION</label>
                                <input tabindex="22" readonly type="text" class="form-control" value="{{$datos_atencion[0]->observaciones_benefi}}" name="M1" id="M1" style="text-align:left">
                            </div>
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
                                <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->profesional_dni}}
                                </div>
                              </div>
                              <div class="col-md-2">
                                <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                <div tabindex="19" class="form-control" style="text-align:left;">{{$datos_atencion[0]->profesional_paterno}}
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
                        </div>
                        <div id="container2" data-id-salud_materna="{{ $datos_atencion[0]->id_gestacion }}">
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
                        </div>
                        
                        <div id="container1" data-id-motivo-ingreso="{{ $datos_atencion[0]->id_motivo_ingreso }}">
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
                          <div class="col-lg-2 col-md-4">
                            <label>PRESIÓN ARTERIAL</label>
                            <div class="row">
                              <div class="col-xs-5">
                                <input type="hidden" id="id_nota_ingreso" value="{{ $datos_nota_ingreso[0]->id }}">
                                <input value="{{$datos_nota_ingreso[0]->presion_arterial_x}}" tabindex="11" type="text" name="presion_arterial_x"
                                  id="presion_arterial_x" class="form-control"  onkeypress="return esEntero(event);" maxlength="3">
                                <span id="presion_arterial_x-aceptado"></span>
                              </div>
                              
                              <div class="col-xs-2" style="text-align: center;">
                                <label>/</label>
                              </div>
                              <div class="col-xs-5">
                                <input value="{{$datos_nota_ingreso[0]->presion_arterial_y}}" type="text" tabindex="12" name="presion_arterial_y"
                                  id="presion_arterial_y" class="form-control" onkeypress="return esEntero(event);" maxlength="3">
                                <span id="presion_arterial_y-aceptado"></span>
                              </div>
                            </div>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>Pulso/frec.Cardiaca </label>
                            <input value="{{$datos_nota_ingreso[0]->frecuencia_cardiaca}}" type="text" name="frecuencia_cardiaca" id="frecuencia_cardiaca" tabindex="14"
                              class="form-control" onkeypress="return esEntero(event);" maxlength="3">
                            <span id="frec_cardiaca-aceptado"></span>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>FREC. RESPIRATORIA (/min)</label>
                            <input value="{{$datos_nota_ingreso[0]->frecuencia_respiratoria}}" type="text" name="frecuencia_respiratoria" tabindex="13"
                              id="frecuencia_respiratoria" class="form-control" onkeypress="return esEntero(event);"
                              maxlength="2">
                            <span id="frecuencia_respiratoria-aceptado"></span>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>Fi02 (%)</label>
                            <input value="{{$datos_nota_ingreso[0]->fi02}}" type="text" name="fi02" id="fi02" 
                              class="form-control" onkeypress="return esEntero(event);" maxlength="3"> 
                              <span id="oxigenacion-aceptado"></span>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>Sa02 (%)</label>
                            <input value="{{$datos_nota_ingreso[0]->sa02_porcentaje}}" type="text" name="sa02_porcentaje" id="sa02_porcentaje" tabindex="8"
                              class="form-control" onkeypress="return esEntero(event);" maxlength="3">
                            <span id="saturacion-aceptado"></span>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>TEMPERATURA (°C)</label>
                            <input placeholder="00.00" value="{{$datos_nota_ingreso[0]->temperatura}}" type="text" name="temperatura" id="temperatura"
                              class="form-control" class="form-control" maxlength="5" onkeypress="return evitarLetras(event)" />
                            <span id="texto-aceptado"></span>
                          </div>
                        </div>

                        <div class="form-group"> 
                          <div class="col-lg-2 col-md-4">
                            <label>TALLA (cm) &nbsp;</label>
                            <input value="{{$datos_nota_ingreso[0]->talla}}" type="text" tabindex="15" name="talla" id="talla" class="form-control"
                              onkeyup="compruebaindice_masa_corporal()" maxlength="5" onkeypress="return evitarLetras(event)">
                            <span id="talla-aceptado"></span>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>PESO (Kg) &nbsp;</label>
                            <input placeholder="00.00" value="{{$datos_nota_ingreso[0]->peso}}" tabindex="16" type="text" name="peso" id="peso"
                              class="form-control" onkeyup="compruebaindice_masa_corporal()" onkeypress="return evitarLetras(event)"
                              maxlength="6">
                            <span id="peso-aceptado"></span>
                          </div>

                          <div class="col-lg-2 col-md-4">
                            <label>indice_masa_corporal &nbsp;</label> 
                            <input value="{{$datos_nota_ingreso[0]->indice_masa_corporal}}" type="text" name="indice_masa_corporal" id="indice_masa_corporal" class="form-control" readonly="">
                            <span id="indice_masa_corporal-aceptado"></span>
                          </div> 

                          <div class="col-sm-2">
                            <label>PESO IDEAL</label>
                            <input value="{{$datos_nota_ingreso[0]->peso_x_altura}}" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control"  readonly="">
                          </div>

                         
                        </div> 

                      </fieldset> 
                    </div>
 
 
  

                    <div class="tab-pane fade" id="tab1default">
                      <fieldset>
                        <div class="form-group">
                          <div class="form-group">
                            <div class="col-lg-1 col-md-2">
                                <label>HIPERTENSION ARTERIAL</label> <br>
                                <input type="radio" class="regular-radio" name="hta" id="hta1" value="SI" 
                                       @if($datos_nota_ingreso[0]->tiene_hta == 'SI') checked @endif 
                                       style="cursor: pointer;">
                                <b>
                                    <a href="javascript:void(0)" id="click_hta_si" name="click_hta_si"
                                       onclick="seleccionar_radio_por_texto('click_hta_si','hta1','hta_tratamiento','show')">SI</a>
                                </b>
                                <input type="radio" class="regular-radio" name="hta" id="hta2" value="NO" 
                                       @if($datos_nota_ingreso[0]->tiene_hta == 'NO') checked @endif 
                                       style="margin-left: 10px;cursor: pointer;">
                                <b>
                                    <a href="javascript:void(0)" id="click_hta_no" name="click_hta_no"
                                       onclick="seleccionar_radio_por_texto('click_hta_no','hta2','hta_tratamiento','hide')">NO</a>
                                </b>
                            </div>
                            <div id="hta_tratamiento" name="hta_tratamiento" 
                                 @if($datos_nota_ingreso[0]->tiene_hta == 'NO') style="display:none;" @endif>
                                <div class="col-md-3">
                                    <label>TRATAMIENTO</label>
                                    <input type="text" id="descripcion_tratamiento_hta" name="descripcion_tratamiento_hta" class="form-control" 
                                           value="{{$datos_nota_ingreso[0]->descripcion_tratamiento_hta}}" enabled>
                                </div>
                            </div>
                        
                        

                              <div class="col-lg-1 col-md-2">
                                  <label>DIABETES MELLITUS</label> <br>
                                  <input type="radio" class="regular-radio" name="diabetes_mellitus" id="diabetes_mellitus1" value="SI"
                                         @if($datos_nota_ingreso[0]->diabetes_mellitus == 'SI') checked @endif 
                                         style="cursor: pointer;">
                                  <b>
                                      <a href="javascript:void(0)" id="click_diabetes_mellitus_si" name="click_diabetes_mellitus_si"
                                         onclick="seleccionar_radio_por_texto('click_diabetes_mellitus_si','diabetes_mellitus1','diabetes_mellitus_observacion','show')">SI</a>
                                  </b>
                                  <input type="radio" class="regular-radio" name="diabetes_mellitus" id="diabetes_mellitus2" value="NO" 
                                         @if($datos_nota_ingreso[0]->diabetes_mellitus == 'NO') checked @endif 
                                         style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                      <a href="javascript:void(0)" id="click_diabetes_mellitus_no" name="click_diabetes_mellitus_no"
                                         onclick="seleccionar_radio_por_texto('click_diabetes_mellitus_no','diabetes_mellitus2','diabetes_mellitus_observacion','hide')">NO</a>
                                  </b>
                              </div>
                              <div id="diabetes_mellitus_observacion" name="diabetes_mellitus_observacion" 
                                   @if($datos_nota_ingreso[0]->diabetes_mellitus == 'NO') style="display:none;" @endif>
                                  <div class="col-lg-2 col-md-4">
                                      <label>TRATAMIENTO</label>
                                      <input type="text" id="diabetes_mellitus_descripcion" name="diabetes_mellitus_descripcion" class="form-control" 
                                             value="{{$datos_nota_ingreso[0]->diabetes_mellitus_descripcion}}" enabled>
                                  </div>
                          </div>
                          
                            <div class="col-lg-1 col-md-2">
                                <label style="color: red;">ALERGIA</label><br>
                                <input type="radio" class="regular-radio" name="ram" id="ram1" value="SI"
                                       @if($datos_nota_ingreso[0]->tiene_alergia == 'SI') checked @endif 
                                       style="cursor: pointer;">
                                <b>
                                    <a href="javascript:void(0)" id="click_ram_si" name="click_ram_si"
                                       onclick="seleccionar_radio_por_texto('click_ram_si','ram1','ram_descripcion','show')">SI</a>
                                </b>
                                <input type="radio" class="regular-radio" name="ram" id="ram2" value="NO"
                                       @if($datos_nota_ingreso[0]->tiene_alergia == 'NO') checked @endif 
                                       style="margin-left: 10px;cursor: pointer;">
                                <b>
                                    <a href="javascript:void(0)" id="click_ram_no" name="click_ram_no"
                                       onclick="seleccionar_radio_por_texto('click_ram_no','ram2','ram_descripcion','hide')">NO</a>
                                </b>
                            </div>
                            <div class="col-md-4" id="ram_descripcion" name="ram_descripcion" 
                                 @if($datos_nota_ingreso[0]->tiene_alergia == 'NO') style="display:none;" @endif>
                                <label style="color: red;">DETALLE (ALERGIA)</label>
                                <input type="text" id="detalle_ram_alergia" name="detalle_ram_alergia" class="form-control" 
                                       value="{{$datos_nota_ingreso[0]->descripcion_alergia}}">
                            </div>
                        </div>

                        <div class="form-group">
                          <div class="col-md-4">
                            <label for="tipo">CARDIOLÓGICO</label>
                            <select name="cardiologico[]" id="cardiologico" class="form-control" style="width: 100%;" multiple>
                                    @foreach($valores_cardio as $id => $value)
                                        <option value="{{ $id}}" selected>{{ $value}}</option>
                                    @endforeach
                                
                            </select>
                        </div>
                        
                        
                        
                          <div class="col-md-3">
                            <label>OBSERVACIÓN</label>
                            <input value="{{$datos_nota_ingreso[0]->descripcion_antecedente_observacion}}" type="text" class="form-control" id="descripcion_antecedente_observacion" name="descripcion_antecedente_observacion"/>
                          </div>
                          <div class="col-md-3">
                            <label>OTROS ANTECEDENTES</label>
                            <input value="{{$datos_nota_ingreso[0]->descripcion_otros_antecedentes}}" type="text" class="form-control" id="descripcion_otros_antecedentes" name="descripcion_otros_antecedentes" />
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
                        



                        
                      </fieldset>
                    </div>

                    <div class="tab-pane fade" id="tab2default">
                      <fieldset>
                        <div class="form-group">
                         <div class="col-lg-2 col-md-4">
                          <label for="tipo">INGRESA POR</label><br>
                          <input name="x" id="x" type="text" class="form-control" value="{{$datos_nota_ingreso[0]->descripcion_ingreso_por}}"readonly>  
                        </div>
                        
                        @if($datos_nota_ingreso[0]->id_ingresa_por == 14)
                        <div class="col-lg-2 col-md-4" id="ingresa_por_de" name="ingresa_por_de">
                          <label>INGRESA DE</label>
                          <input type="text" class="form-control" value="{{$datos_nota_ingreso[0]->descripcion_ingresa_por_transferencia}}">  
                        </div>
                      @endif

                      <div class="col-lg-2 col-md-4">
                        <label for="tipo">CONDICIÓN DE INGRESO</label>
                        <select name="condicion_ingreso" id="condicion_ingreso" class="form-control" style="width: 100%;">
                           @foreach($condiciones_parametros as $parametro)
                              <option value="{{ $parametro->id}}"@if($parametro->id==$id_condicion_ingreso)selected @endif>{{ $parametro->descrip_corta}}</option>
                          @endforeach
                  </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <label for="tipo">ESTADO CONCIENCIA</label>
                      <select name="estado_conciencia" id="estado_conciencia" class="form-control" style="width: 100%;">
                          @foreach($estados_conciencia as $conciencia)
                              <option value="{{ $conciencia->id}}"@if($conciencia->id==$id_estado_conciencia)@endif selected >{{ $conciencia->descrip_corta }}</option>
                          @endforeach
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
                              <input type="radio" checked="" class="regular-radio" name="orientado"
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
                            <input type="text" class="form-control" value="{{$datos_nota_ingreso[0]->grado_dependencia}}" readonly>  

                          </div>
                        </div>

                        <div class="form-group">

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">FUNCION MOTORA</label>
                            <select name="funcion_motora" id="funcion_motora" class="form-control" style="width: 100%;">
                                @foreach($funciones_motoras as $funcion_motora)
                                    <option value="{{ $funcion_motora->id}}"@if($funcion_motora->id==$id_funcion_motora)@endif selected >{{ $funcion_motora->descrip_corta}}</option>
                                @endforeach
                            </select>
                        </div>

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">RESPIRATORIO(Duracion)</label>
                            <select name="respiratorio_duracion" id="respiratorio_duracion" class="form-control" style="width: 100%;">
                                @foreach($respiratorios as $respiratoria)
                                    <option value="{{ $respiratoria->id}}"@if($respiratoria->id==$id_respiratorio)@endif selected >{{ $respiratoria->descrip_corta}}</option>
                                @endforeach
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
                            <input type="radio" class="regular-radio" name="oxigenoterapia" id="oxigenoterapia1" value="SI"
                                   @if($datos_nota_ingreso[0]->tiene_oxigenoterapia == 'SI') checked @endif 
                                   style="cursor: pointer;">
                            <b>
                                <a href="javascript:void(0)" id="click_oxigenoterapia_si" name="click_oxigenoterapia_si">SI</a>
                            </b>
                            <input type="radio" class="regular-radio" name="oxigenoterapia" id="oxigenoterapia2" value="NO" 
                                   @if($datos_nota_ingreso[0]->tiene_oxigenoterapia == 'NO') checked @endif 
                                   style="margin-left: 10px;cursor: pointer;">
                            <b>
                                <a href="javascript:void(0)" id="click_oxigenoterapia_no" name="click_oxigenoterapia_no">NO</a>
                            </b>
                        </div>
                        
                        <div id="oxigenoterapia_detalle" name="oxigenoterapia_detalle">
                            <div class="col-lg-2 col-md-4">
                                <label style="color: red;">DETALLE (OXIGENOTERAPIA)</label>
                                <select name="oxigenoterapia_detalle_seleccion" id="oxigenoterapia_detalle_seleccion" class="form-control" style="width: 100%;">
                                    @foreach($detalles_oxigenterapias as $oxigenoterapia)
                                        <option value="{{ $oxigenoterapia->id}}" @if($oxigenoterapia->id == $id_oxigenoterapia_detalle_seleccion) selected @endif>
                                            {{ $oxigenoterapia->descrip_corta}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                          
                        </div>


                        <div class="form-group">
                          <div class="col-lg-1 col-md-2">
                            <label>MICCIONES ESPONTANEO</label> <br>
                            <input type="radio" class="regular-radio" name="miccion_espontanea" id="miccion_espontanea1" value="SI"
                                   @if($datos_nota_ingreso[0]->tiene_miccion_espontanea == 'SI') checked @endif 
                                   style="cursor: pointer;">
                            <b>
                                <a href="javascript:void(0)" id="click_miccion_espontanea_si" name="click_miccion_espontanea_si"
                                   onclick="seleccionar_radio_por_texto('click_miccion_espontanea_si','miccion_espontanea1','miccion_espontanea_observacion','show')">SI</a>
                            </b>
                            <input type="radio" class="regular-radio" name="miccion_espontanea" id="miccion_espontanea2" value="NO" 
                                   @if($datos_nota_ingreso[0]->tiene_miccion_espontanea == 'NO') checked @endif 
                                   style="margin-left: 10px;cursor: pointer;">
                            <b>
                                <a href="javascript:void(0)" id="click_miccion_espontanea_no" name="click_miccion_espontanea_no"
                                   onclick="seleccionar_radio_por_texto('click_miccion_espontanea_no','miccion_espontanea2','miccion_espontanea_observacion','hide')">NO</a>
                            </b>
                        </div>
                        <div class="col-lg-2 col-md-3">
                          <input type="hidden" value="1,1,1" id="puntaje_calculado_glasgow_memoria"
                            name="puntaje_calculado_glasgow_memoria">
                          <label for="tipo">ESCALA DE GLASGOW</label>
                          <div class="input-group">
                            <input type="text" id="puntaje_calculado_glasgow" name="puntaje_calculado_glasgow"
                              class="form-control puntaje_calculado_glasgow" style="height: 30px !important;"
                              value="{{$datos_nota_ingreso[0]->puntaje_calculado_glasgow}}" readonly>
                            <span class="input-group-btn">
                              <button class="btn btn-success btn-xs calcularEscalaGlasgow" id="calcularEscalaGlasgow"
                                name="calcularEscalaGlasgow" style="height: 30px !important;" type="button">
                                <i class="fa fa-calculator" style="padding-left: 7px;padding-right: 7px;"></i>
                              </button>
                            </span>
                          </div>
                        </div>
                      <div class="col-lg-2 col-md-4">
                          <label for="tipo">VALORACIÓN DE GLASGOW</label>
                          <input type="text" class="form-control descripcion_detalle_Glasgow" id="descripcion_detalle_Glasgow" name="descripcion_detalle_Glasgow" style="height: 30px !important;" readonly value="{{$datos_nota_ingreso[0]->descripcion_detalle_glasgow}}">
                      </div>
                        </div>

                        <div class="form-group"> <br>
                          
                          <div class="col-lg-2 col-md-2">
                            <label for="tipo">ACOMPAÑADO</label><br>
                            <input type="radio" class="regular-radio" name="acompaniado_por" id="acompaniado_por1" value="SOLO"
                                @if($datos_nota_ingreso[0]->acompaniado_por == 'SOLO') checked @endif 
                                style="cursor: pointer;">
                            <b>
                                <a href="javascript:void(0)" id="click_acompaniado_por_solo"
                                    onclick="seleccionar_radio_por_texto('click_acompaniado_por_solo','acompaniado_por1','acompaniado_por_detalle','hide')">SOLO</a>
                            </b>
                            <input type="radio" class="regular-radio" name="acompaniado_por" id="acompaniado_por2" value="FAMILIAR"
                                @if($datos_nota_ingreso[0]->acompaniado_por == 'FAMILIAR') checked @endif 
                                style="margin-left: 10px; cursor: pointer;">
                            <b>
                                <a href="javascript:void(0)" id="click_acompaniado_por_familiar"
                                    onclick="seleccionar_radio_por_texto('click_acompaniado_por_familiar','acompaniado_por2','acompaniado_por_detalle','show')">FAMILIAR</a>
                            </b>
                        </div>
                        <div class="col-md-4" id="acompaniado_por_detalle" name="acompaniado_por_detalle">
                            <label>ACOMPAÑADO POR:</label>
                            <input type="text" class="form-control" value="{{$datos_nota_ingreso[0]->descripcion_acompaniado_por}}"   
                                id="descripcion_acompaniado_por" name="descripcion_acompaniado_por"/>    
                        </div>
                        
                       

                      <div class="col-md-4">
                        <label for="tipo">EEMOPTISIS</label><br>
                        <input type="radio"  class="regular-radio" name="emoptisis"
                          style="cursor: pointer;" id="emoptisis" value="SI"@if($datos_nota_ingreso[0]->emoptisis=='SI')checked @endif>
                          <b>
                            <a href="javascript:void(0)">SI</a>
                          </b>
                        <input type="radio" checked class="regular-radio" name="emoptisis"
                        style="margin-left: 10px;cursor: pointer;"  id="emoptisis" value="NO" @if($datos_nota_ingreso[0]->emoptisis=='NO')checked @endif>
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
                            <label style="color: rgb(46, 35, 35);">PIEL (COLOR)</label>
                            <select name="piel_colores" id="piel_colores" class="form-control" style="width: 100%;">
                                @foreach($piel_colores as $piel_color)
                                    <option value="{{ $piel_color->id}}" @if($piel_color->id == $id_piel_colores) selected @endif>
                                        {{ $piel_color->descrip_corta}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                          <div class="col-lg-2 col-md-2">
                            <label for="tipo">EDEMA</label> <br>
                            <input type="radio" checked="" class="regular-radio" name="edema" id="edema1"
                              style="cursor: pointer;" value="SI" @if($datos_nota_ingreso[0]->tiene_edema=='SI')checked @endif>
                            <b>
                              <a href="javascript:void(0)" id="click_edema_si" name="click_edema_si"
                                onclick="seleccionar_radio_por_texto('click_edema_si','edema1','','')">SI</a>
                            </b>
                            <input type="radio" checked="" class="regular-radio" name="edema" id="edema2" 
                            style="margin-left: 10px;cursor: pointer;" value="NO"@if($datos_nota_ingreso[0]->tiene_edema=='NO')checked @endif>
                            <b>
                              <a href="javascript:void(0)" id="click_edema_no" name="click_edema_no"
                                onclick="seleccionar_radio_por_texto('click_edema_no','edema2','','')">NO</a>
                            </b>
                          </div>
                          <div class="col-md-3">
                            <label for="tipo">INTEGRIDAD</label>
                            <select name="integridad" id="integridad" class="form-control" style="width: 100%;">
                              @foreach($integridades as $integridad)
                                  <option value="{{ $integridad->id}}" @if($integridad->id == $id_integridad) selected @endif>
                                      {{ $integridad->descrip_corta}}
                                  </option>
                              @endforeach
                          </select>
                          </div>





                          <div class="col-md-4">
                            <label for="tipo">ABDOMEN</label>
                            <select name="abdomen" id="abdomen" class="form-control" style="width: 100%;">
                              @foreach($abdomenes as $abdomen)
                                  <option value="{{ $abdomen->id}}" @if($abdomen->id == $id_abdomen) selected @endif>
                                      {{ $abdomen->descrip_corta}}
                                  </option>
                              @endforeach
                          </select>
                          </div>
                        </div>
                        <div class="form-group">

                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">EVALUACIÓN MALLAMPATY</label> <br>
                            <input type="radio" class="regular-radio" name="evaluacion_mallampaty" id="evaluacion_mallampaty1" value="SI" @if($datos_nota_ingreso[0]->tiene_evaluacion_mallampaty == 'SI') checked @endif style="cursor: pointer;">
                            <b>
                                <a href="javascript:void(0)" id="click_evaluacion_mallampaty_si" name="click_evaluacion_mallampaty_si">SI</a>
                            </b>
                            <input type="radio" class="regular-radio" name="evaluacion_mallampaty" id="evaluacion_mallampaty2" value="NO" @if($datos_nota_ingreso[0]->tiene_evaluacion_mallampaty == 'NO') checked @endif style="margin-left: 10px; cursor: pointer;">
                            <b>
                                <a href="javascript:void(0)" id="click_evaluacion_mallampaty_no" name="click_evaluacion_mallampaty_no">NO</a>
                            </b>
                        </div>
                        <div class="col-lg-10 col-md-12" id="evaluacion_mallampaty_opcion" name="evaluacion_mallampaty_opcion" @if($datos_nota_ingreso[0]->tiene_evaluacion_mallampaty == 'NO') style="display: none;" @endif>                          <label for="tipo">EVALUACIÓN MALLAMPATY</label>
                          <select class="form-control input-sm descripcion_evaluacion_mallampaty" id="descripcion_evaluacion_mallampaty" name="descripcion_evaluacion_mallampaty" style="width: 100%;">
                              <option value="*">--SELECCIONAR--</option>
                              <option value="1" @if($datos_nota_ingreso[0]->descripcion_evaluacion_mallampaty == '1') selected @endif><b>CLASE I:</b> VISIBILIDAD DEL PALADAR BLANDO, ÚVULA Y PILARES</option>
                              <option value="2" @if($datos_nota_ingreso[0]->descripcion_evaluacion_mallampaty == '2') selected @endif><b>CLASE II:</b> VISIBILIDAD DEL PALADAR BLANDO Y ÚVULA</option>
                              <option value="3" @if($datos_nota_ingreso[0]->descripcion_evaluacion_mallampaty == '3') selected @endif><b>CLASE III:</b> VISIBILIDAD DEL PALADAR BLANDO BASE DE LA ÚVULA</option>
                              <option value="4" @if($datos_nota_ingreso[0]->descripcion_evaluacion_mallampaty == '4') selected @endif><b>CLASE IV:</b> IMPOSIBILIDAD PARA VER EL PALADAR BLANDO</option>
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



                          <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                              <div class="col-md-3">
                                <label for="tipo">DISPOSITIVOS INVASIVOS</label><br>
                                <input type="radio" checked class="regular-radio"name="dispositivos_invasivos" id="dispositivos_invasivos1"                      
                                 style="cursor: pointer;" value="SI" @if($datos_nota_ingreso[0]->tiene_dispositivo_invasivos=='SI')checked @endif>

                                <b>
                                  <a href="javascript:void(0)" id="click_dispositivos_invasivos_si"
                                    name="click_dispositivos_invasivos_si"
                                    onclick="seleccionar_radio_por_texto('click_dispositivos_invasivos_si','dispositivos_invasivos1','dispositivos_invasivos_detalle','show')">SI</a>
                                </b>
                                <input type="radio" class="regular-radio" name="dispositivos_invasivos" id="dispositivos_invasivos2" value="NO"
                                 style="margin-left: 10px;cursor: pointer;" value="NO"@if($datos_nota_ingreso[0]->tiene_dispositivo_invasivos=='NO')checked @endif>

                                <b>
                                  <a href="javascript:void(0)" id="click_dispositivos_invasivos_no"
                                    name="click_dispositivos_invasivos_no"
                                    onclick="seleccionar_radio_por_texto('click_dispositivos_invasivos_no','dispositivos_invasivos2','dispositivos_invasivos_detalle','hide')">NO</a>
                                </b>
                              </div>

                              <div id="dispositivos_invasivos_detalle" name="dispositivos_invasivos_detalle">
                                <div class="col-md-3">
                                  <label for="tipo">VÍA PERIFÉRICA</label><br>
                                  <input type="radio" checked class="regular-radio" name="via_periferica" id="via_periferica"
                                    style="cursor: pointer;"  value="SI"value="NO"@if($datos_nota_ingreso[0]->via_periferica=='SI')checked @endif>
                                  <b>
                                    <a href="javascript:void(0)">SI</a>
                                  </b>
                                  <input type="radio" class="regular-radio" name="via_periferica"  id="via_periferica"
                                  value="NO" style="margin-left: 10px;cursor: pointer;"@if($datos_nota_ingreso[0]->via_periferica=='NO')checked @endif>
                                  <b>
                                    <a href="javascript:void(0)">NO</a>
                                  </b>
                                </div>

                                <div class="col-md-3">
                                  <label for="tipo">VÍA CENTRAL</label><br>
                                  <input type="radio" checked class="regular-radio" name="via_central"
                                     value="SI" style="cursor: pointer;" @if($datos_nota_ingreso[0]->via_central=="SI")checked @endif>
                                  <b>
                                    <a href="javascript:void(0)">SI</a>
                                  </b>
                                  <input type="radio" class="regular-radio" name="via_central" 
                                  value="NO" style="margin-left: 10px;cursor: pointer;"@if($datos_nota_ingreso[0]->via_central=="NO")checked @endif>
                                  <b>
                                    <a href="javascript:void(0)">NO</a>
                                  </b>
                                </div>

                                <div class="col-md-3">
                                  <label for="tipo">CATÉTER ALTO FLUJO</label><br>
                                  <input type="radio" checked class="regular-radio" name="caracter_alto_flujo"
                                    style="cursor: pointer;"  value="SI"@if($datos_nota_ingreso[0]->caracter_alto_flujo=="SI")checked @endif>
                                  <b>
                                    <a href="javascript:void(0)">SI</a>
                                  </b>
                                  <input type="radio" class="regular-radio" name="caracter_alto_flujo" 
                                  value="NO" style="margin-left: 10px;cursor: pointer;"@if($datos_nota_ingreso[0]->caracter_alto_flujo=="NO")checked @endif>
                                  <b>
                                    <a href="javascript:void(0)">NO</a>
                                  </b>
                                </div>
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
                                    style="cursor: pointer;"  value="SI"@if($datos_nota_ingreso[0]->linea_arterial=='SI')checked @endif>
                                  <b>
                                    <a href="javascript:void(0)">SI</a>
                                  </b>
                                  <input type="radio" class="regular-radio" name="linea_arterial" 
                                  value="NO" @if($datos_nota_ingreso[0]->linea_arterial=='NO')checked @endif style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)">NO</a>
                                  </b>

                                </div>

                                <div class="col-md-3">
                                  <br>
                                  <label for="tipo">DRENAJES</label> <br>
                                  <input type="radio" checked="" class="regular-radio" name="drenaje" id="drenaje1"
                                    style="cursor: pointer;" value="SI"@if($datos_nota_ingreso[0]->tiene_drenaje=="SI") checked @endif>
                                  <b>
                                    <a href="javascript:void(0)" id="click_drenaje_si" name="click_drenaje_si"
                                      onclick="seleccionar_radio_por_texto('click_drenaje_si','drenaje1','drenaje_descripcion','show')">SI</a>
                                  </b>
                                  <input type="radio" checked="" class="regular-radio" name="drenaje" id="drenaje2"
                                    value="NO" @if($datos_nota_ingreso[0]->tiene_drenaje=="NO")checked @endif style="margin-left: 10px;cursor: pointer;">
                                  <b>
                                    <a href="javascript:void(0)" id="click_drenaje_no" name="click_drenaje_no"
                                      onclick="seleccionar_radio_por_texto('click_drenaje_no','drenaje2','drenaje_descripcion','hide')">NO</a>
                                  </b>
                                </div>

                                <div class="col-md-6" id="drenaje_descripcion" name="drenaje_descripcion" @if($datos_nota_ingreso[0]->tiene_drenaje == 'NO') style="display: none;" @endif>
                                  <br>
                                  <label for="tipo">DESCRIPCIÓN DRENAJES</label>
                                  <input value="{{ $datos_nota_ingreso[0]->descripcion_drenaje }}" type="text" class="form-control" name="descripcion_drenaje" id="descripcion_drenaje"/>
                                </div>
                              </div>
                            </div>
                            
                          </div>



                          <div class="col-lg-6 col-md-12">
                            <form id="FormularioPunto" name="FormularioPunto" enctype="multipart/form-data"
                              method="GET">
                              <div class="form-group">
                                <div class="col-md-6">
                                  <label for="tipo">SELECCIONE PROCESO</label>
                                  <select class="form-control input-sm" id="tipo_proceso" name="tipo_proceso"
                                    style="width: 100%;">
                                  </select>
                                  <input type="hidden" name="id_tipo_atenciones" id="id_tipo_atenciones" value="{{$id_tipo_atenciones}}">
                                  <input type="hidden" name="tipo_registro" id="tipo_registro" value="NOTA_ENFERMERIA">
                                </div>
                                
                              </div>
                              <input type="hidden" name="img_puntos_base" id="img_puntos_base" class="form-control">
                              <div class="form-group">
                                <div class="col-md-9" style="cursor: pointer;"  id="celda1" name="celda1">
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
                            <label for="tipo">ESCALA DE RIESGO LESION POR PRESION</label>
                            <div class="input-group">
                              <input type="text" id="puntaje_calculado_lpp" name="puntaje_calculado_lpp"
                                class="form-control puntaje_calculado_lpp" style="height: 30px !important;" value="{{$datos_nota_ingreso[0]->puntaje_calculado_lpp}}"readonly>
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
                              style="height: 30px !important;" value="{{$datos_nota_ingreso[0]->descripcion_detalle_riesgolpp}}" readonly>
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <input type="hidden" value="0,FF0,FA0,0,FAAS0" id="puntaje_calculado_caidas_memoria"
                              name="puntaje_calculado_caidas_memoria">
                            <label for="tipo">ESCALA DE RIESGO DE CAÍDAS(J.H DONWTON)</label>
                            <div class="input-group">
                              <input type="text" id="puntaje_calculado_caidas" name="puntaje_calculado_caidas"
                                class="form-control puntaje_calculado_caidas" style="height: 30px !important;" value="{{$datos_nota_ingreso[0]->puntaje_calculado_caidas}}"readonly>
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
                              style="height: 30px !important;" value="{{$datos_nota_ingreso[0]->descripcion_detalle_caidas}}" readonly="">
                          </div>
                          <div class="col-lg-2 col-md-4">
                            <label for="tipo">ESCALA DEL DOLOR</label>
                            <select class="form-control input-sm" id="escalas" name="escalas" style="width: 100%;">
                              <option value="0" @if($datos_nota_ingreso[0]->escalas_de_dolor == 0) selected @endif>SIN DOLOR (0)</option>
                              <option value="1" @if($datos_nota_ingreso[0]->escalas_de_dolor == 1) selected @endif>DOLOR LEVE (1-2-3)</option>
                              <option value="2" @if($datos_nota_ingreso[0]->escalas_de_dolor == 2) selected @endif>DOLOR MODERADO (4-5-6-7)</option>
                              <option value="3" @if($datos_nota_ingreso[0]->escalas_de_dolor == 3) selected @endif>DOLOR SEVERO (8-9-10)</option>
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
                        </div>
                          <div class="form-group">
                            <div class="col-lg-2 col-md-4">
                              <label for="tipo" style="color: red;">BRAZALETE DE IDENTIFICACIÓN</label> <br>
                              <input type="radio" checked="" class="regular-radio" name="brazalete" id="brazalete1"
                                style="cursor: pointer;" value="SI" @if($datos_nota_ingreso[0]->tiene_brazalete=="SI")checked @endif>
                              <b>
                                <a href="javascript:void(0)" id="click_brazalete_si" name="click_brazalete_si"
                                  onclick="seleccionar_radio_por_texto('click_brazalete_si','brazalete1','brazalete_opcion','show')">SI</a>
                              </b>
                              <input type="radio" checked="" class="regular-radio" name="brazalete" id="brazalete2"
                                value="NO"  @if($datos_nota_ingreso[0]->tiene_brazalete=="NO")checked @endif style="margin-left: 10px;cursor: pointer;">
                              <b>
                                <a href="javascript:void(0)" id="click_brazalete_no" name="click_brazalete_no"
                                  onclick="seleccionar_radio_por_texto('click_brazalete_no','brazalete2','brazalete_opcion','hide')">NO</a>
                              </b>
                            </div>
                            <div class="col-lg-2 col-md-4" id="brazalete_opcion" name="brazalete_opcion">
                              <label for="tipo" style="color: red;">COLOR DE BRAZALETE</label>
                              <select class="form-control input-sm" id="brazalete_seleccion" name="brazalete_seleccion"
                                style="width: 100%;">
                                <option value="BLANCO"@if($datos_nota_ingreso[0]->brazalete_seleccion == "BLANCO") selected @endif>BLANCO</option>
                                <option value="ROJO"@if($datos_nota_ingreso[0]->brazalete_seleccion == "ROJO") selected @endif>ROJO</option>
                                <option value="NARANJA"@if($datos_nota_ingreso[0]->brazalete_seleccion == "NARANJA") selected @endif>NARANJA</option>
                                <option value="AMARILLO"@if($datos_nota_ingreso[0]->brazalete_seleccion == "AMARILLO") selected @endif>AMARILLO</option>
                              </select>
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
                          
                          </div>
                          
                          </div>  

                      </fieldset>
                    </div>

                    <div class="tab-pane fade" id="tab6default">
                      <fieldset>
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>DETALLE OBSERVACIONES</label>
                            <textarea class="form-control" rows="10" id="observacion_detallada" name="observacion_detallada"
                            placeholder="Observaciones Generales">{{$datos_nota_ingreso[0]->observacion_detallada}}</textarea>
                             
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
