@extends('frontend.layouts.master')
@section('renderjs')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.function_edad')


<script src='../funciones/function.js'></script> 
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
{!! HTML::script("datePicker/js/bootstrap-datepicker.js") !!}
{!! HTML::script("datePicker/css/bootstrap-datepicker.min.css") !!} 

<script>
  $id_forma_ingreso = $("#frmTriajeEmergencia").find("#id_forma_ingreso");
  $id_forma_ingreso.select2({
    width: 'resolve' // need to override the changed default
  });
  $id_estado_ingreso = $("#frmTriajeEmergencia").find("#id_estado_ingreso");
  $id_estado_ingreso.select2({
    width: 'resolve' // need to override the changed default
  });
  $id_motivo_ingreso = $("#frmTriajeEmergencia").find("#id_motivo_ingreso");
  $id_motivo_ingreso.select2({
    width: 'resolve' // need to override the changed default
  });
  $id_prioridad = $("#frmTriajeEmergencia").find("#id_prioridad");
  $id_prioridad.select2({
    width: 'resolve' // need to override the changed default
  });
  $id_topico = $("#frmTriajeEmergencia").find("#id_topico");
  $id_topico.select2({
    width: 'resolve' // need to override the changed default
  });

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


$('#fecha_registro_ingreso_select').datepicker({
  useCurrent: false,
  format: 'dd/mm/yyyy', 
  todayHighlight: true,
  autoclose: true,  
  orientation: "bottom left" 
}).on('changeDate',function(e){  
    console.log('click sobre fecha_registro_ingreso_select');
    var oldDate =  $('#fecha_registro_ingreso_select').val();
    if(oldDate!=null){
      var values = oldDate.split("/"); 
      var dia = values[0];
      var mes = values[1];
      var ano = values[2]; 
      $('#fecha_registro_ingreso').val(ano+'-'+mes+'-'+dia);
    }
});


  function getArea_topico (){ 
    var id_prioridad = $('#id_prioridad').val();
    var sexo = $('#sexo').val();
    var edad = $('#edad_pac').val();
    if(id_prioridad!=''){
      if(id_prioridad=='4'){
        $('#topico_toggle').hide(); 
      }else{
        $('#topico_toggle').show(); 
      }

      var id_topico={{$data->triaje_id}};
      var url_consulta='/get_topico_por_id_prioridad/' + id_prioridad; 
        ///validamos que sea de prioridad 2 y 3 para colocar por defecto gineco  
      if(id_prioridad=='2'||id_prioridad=='3'){
        url_consulta='/get_topico_por_id_prioridad_sexo_edad/'+id_prioridad+'/'+sexo+'/'+edad;
      }  

      $.ajax({
        url: url_consulta,
        success: function(result) { 
          $('#id_topico').empty();  
          $('#id_topico').append("<option value=''>--SELECCIONAR--</option>");  

          var new_id=0;
          $(result).each(function(ii, oo) {   
              if(oo.id==id_topico){
                new_id=oo.id;
              }
              $('#id_topico').append("<option value='" + oo.id + "'>" + oo.descripcion + "</option>");  
          }); 
  
          if(id_prioridad=='1'){
            $('#id_topico').val("1").change(); 
            $('#observacion').focus(); 
          }else if(id_prioridad=='4'){
            $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
            $('#id_topico').val("19").change(); 
            $('#observacion').focus(); 
          }else{
            if(id_topico!='' && new_id==id_topico){
              $('#id_topico').val(id_topico).change();  
            }else{ 
              $('#id_topico').val("").change();
            }
          }  

        },
          error: function(jqXHR, textStatus, errorThrown) {
            if(id_prioridad!='4'){
              $('#id_topico').empty();  
              $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
              $('#id_topico').val("").change(); 
              $('#observacion').focus(); 
            }else{
              $('#id_topico').empty();  
              $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
              $('#id_topico').val("19").change(); 
              $('#observacion').focus(); 
            }
          }
      });
    }else{ 
        $('#id_topico').empty();  
        $('#id_topico').append("<option value='''>--SELECCIONAR--</option>"); 
        $('#id_topico').val("").change();
    }
  }
  function getTipo_beneficiario(){ 
    var id_tipo_beneficiario="{{$data->id_tipo_beneficiario}}";
    console.log(id_tipo_beneficiario);
    switch (id_tipo_beneficiario) {
      case 1:                tipo_beneficiario_saludpol = "PADRES";   break;
      case 2:    tipo_beneficiario_saludpol = "HIJO MENOR DE EDAD";   break;
      case 3:          tipo_beneficiario_saludpol = "CONCUBINA(O)";   break;
      case 4:               tipo_beneficiario_saludpol = "CONYUGE";   break;
      case 5:    tipo_beneficiario_saludpol = "HIJO MAYOR DE EDAD";   break;
      case 6:     tipo_beneficiario_saludpol = "HIJO INCAPACITADO";   break;
      case 7:       tipo_beneficiario_saludpol = "DERECHOHABIENTE";   break;
      case 8:               tipo_beneficiario_saludpol = "TITULAR";   break;
      case 9:  tipo_beneficiario_saludpol = "PADRES SOBREVIVIENTE";   break;
      case 10:                tipo_beneficiario_saludpol = "ESPOSA";  break;
      case 11:  tipo_beneficiario_saludpol = "HIJO NO INCAPACITADO";  break;
      case 12:                 tipo_beneficiario_saludpol = "OTROS";  break;
      case 13:                 tipo_beneficiario_saludpol = "CIVIL";  break;
      default: tipo_beneficiario_saludpol = "CIVIL";  break;
    }   
    console.log(tipo_beneficiario_saludpol);
    $("#descripcion_tipo_beneficiario_saludpol").val(tipo_beneficiario_saludpol); 
  }

  function calcular_edad_paciente(){ 
    var fecha_nac='{{ $data->fecha_nac }}';
    if(fecha_nac != null && fecha_nac != undefined && fecha_nac.length>8 ){
      var oldDate = fecha_nac; //20/04/1946
      var values = oldDate.split("/");
      if(values.length==1){
        var values = oldDate.split("-");
        var dia = values[2];
        var mes = values[1];
        var ano = values[0];
        $('#fecha_nac').val(dia+'/'+mes+'/'+ano);
      }else{
        var dia = values[0];
        var mes = values[1];
        var ano = values[2];
      } 
      //$('#fecha_nacimiento_beneficiario').val(ano+'-'+mes+'-'+dia);

      calcularEdadPersona(ano,mes,dia,'input','edad_paciente','edad_pac')

    }
  }

  function getEstado_ingreso (){ 
    var id_forma_ingreso = $('#id_forma_ingreso').val();
    console.log(id_forma_ingreso);
    if(id_forma_ingreso!=''){
      $.ajax({
        url: '/obtener_estadoingreso_por_formaingreso/' + id_forma_ingreso,
        success: function(result) {
          $('#id_estado_ingreso').empty();   
          $('#id_estado_ingreso').append("<option value=''>--SELECCIONAR--</option>");   
          $(result).each(function(ii, oo) { 
            $('#id_estado_ingreso').append("<option value='" + oo.id + "'>" + oo.descripcion + "</option>");    
          }); 

          if({{$data->id_estado_ingreso}}!='' && id_forma_ingreso=={{$data->id_forma_ingreso}}){
            $('#id_estado_ingreso').val({{$data->id_estado_ingreso}}).change();
          }else{
            $('#id_estado_ingreso').val('').change();
          }
        }
      });
    }else{
        $('#id_estado_ingreso').empty();  
        $('#id_estado_ingreso').append("<option value=''>--SELECCIONAR--</option>"); 
        $('#id_estado_ingreso').val("").change();
    }
  } 

  const habilitar_registro_manual = document.getElementById("habilitar_registro_manual");
  habilitar_registro_manual.addEventListener("click", function() { 
    if (habilitar_registro_manual.checked) {    
      $('#seccion_habilitra_manual').show();  
      $('#registro_manual').val('1'); 
      
    }else{
      $('#seccion_habilitra_manual').hide();  
      $('#registro_manual').val('0'); 
    } 
  });   

function validar_edad(){  
    var sexo = $('#sexo').val(); 
    var edad = $('#edad_pac').val();  
    if(edad!=''){ 
      if(edad>=7){
        if (sexo == "F") {
          $('#habilitar_es_gestante').show(); 
        } else {
          $('#habilitar_es_gestante').hide(); 
        }
      }else{ 
        $('#habilitar_es_gestante').hide(); 
      }
    }
  }

  const checkbox_es_gestante = document.getElementById("checkbox_es_gestante");
  checkbox_es_gestante.addEventListener("click", function() {  
    if (checkbox_es_gestante.checked) {    
      $('#tamanio_cintura').show(); 
      $('#frecuencia_fetal').show(); 
      $('#seccion_gestante').show(); 
      $('#es_gestante').val('1');  
      var tamano_cintura_cm= '{{ $data->tamano_cintura_cm  }}';
      var frecuencia_cardiaca_fetal= '{{ $data->frecuencia_cardiaca_fetal }}';
      $('#tamano_cintura_cm').val(tamano_cintura_cm);  
      $('#frecuencia_cardiaca_fetal').val(frecuencia_cardiaca_fetal);  
    }else{
      $('#tamanio_cintura').hide(); 
      $('#frecuencia_fetal').hide(); 
      $('#seccion_gestante').hide(); 
      $('#es_gestante').val('0'); 
      $('#tamano_cintura_cm').val('0');  
      $('#frecuencia_cardiaca_fetal').val('0');  
    } 
  }); 


  $(document).ready(function() { 
    
 
    $('#fecha_registro_ingreso').val('{{ $data->fecha_registro_ingreso_orig }}');
    $('#fecha_registro_ingreso_select').val('{{ $data->fecha_registro_ingreso }}');

    var hora_registro_ingreso_val= '{{ $data->hora_registro_ingreso }}';
    var fraccion = hora_registro_ingreso_val.slice(0, 8); 
    $('#hora_registro_ingreso').val(fraccion);

    $('#seccion_habilitra_manual').hide(); 
    var registro_manual_check= '{{ $data->registro_manual }}';
    
    $('#registro_manual').val(registro_manual_check);
    if(registro_manual_check==1){
      $('#seccion_habilitra_manual').show(); 
      var checkbox = document.getElementById('habilitar_registro_manual');
      checkbox.checked = true;
    } 
    
    var es_gestante= '{{ $data->es_gestante }}'; 
    $('#es_gestante').val(es_gestante);
    if(es_gestante==1){ 
      $('#tamanio_cintura').show(); 
      $('#frecuencia_fetal').show(); 
      $('#seccion_gestante').show(); 
      $('#es_gestante').val('1'); 
      var checkbox = document.getElementById('checkbox_es_gestante');
      checkbox.checked = true;

    } else{ 

      $('#tamanio_cintura').hide(); 
      $('#frecuencia_fetal').hide(); 
      $('#es_gestante').val('0'); 
      $('#seccion_gestante').hide();

    }
    getEstado_ingreso(); 
    getTipo_beneficiario();
    getArea_topico();
    calcular_edad_paciente();
    validar_edad();
    
    ////obtenemos la foto
    var foto=$('#foto_paciente').val();
    if(foto != undefined && foto!=null){   
      var imagen = document.getElementById("foto_imagen_paciente"); 
      //console.log(imagen);  
      imagen.src = "data:image/jpeg;base64," + foto;
    }

    var valueSa02 = $('#sa02_porcentaje').val();
    //alert('El valor de la Saturación debe ser entre 60 a 100 ( % )');
    if (valueSa02 >= 60 && valueSa02 <= 85){
    //HIPOXIA GRAVE  - ROJO
    $('#sa02_porcentaje').addClass('borde-saturacion-hipoxia-grave');
    $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Grave' + "</strong>");
    } else if (valueSa02 >= 86 && valueSa02 <= 90){
      //HIPOXIA MODERADA - NARANJA
      $('#sa02_porcentaje').addClass('borde-saturacion-hipoxia-moderada');
      $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Moderada' + "</strong>");
    } else if (valueSa02 >= 91 && valueSa02 <= 94){
      // HIPOXIA LEVE - AMARILLO
      $('#sa02_porcentaje').addClass('borde-saturacion-hipoxia-leve');
      $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Leve' + "</strong>");
    } else if (valueSa02 >= 95 && valueSa02 <= 100){
      //NORMAL - VERDE
      $('#sa02_porcentaje').addClass('borde-saturacion-normal');
      $('#saturacion-aceptado').html("<strong>" + 'Saturación Normal' + "</strong>");
    }
    var valueTemperatura = $('#temperatura').val();
    if(parseFloat(valueTemperatura) >= 35.0 && parseFloat(valueTemperatura) < 36.0) { //! AZUL
      $('#temperatura').addClass('borde-temperatura-hipotermia');
      $('#texto-aceptado').html("<strong>" + 'Paciente con Hipotermia' + "</strong>");
    } else if(parseFloat(valueTemperatura) >= 36.0  && parseFloat(valueTemperatura) < 37.5) { //! VERDE
        $('#temperatura').addClass('borde-temperatura-normal');
        $('#texto-aceptado').html("<strong>" + 'Paciente con temperatura Normal' + "</strong>");
    } else if(parseFloat(valueTemperatura) >= 37.5  && parseFloat(valueTemperatura) < 39.5) { // AMARILLO
        $('#temperatura').addClass('borde-temperatura-fiebre');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre' + "</strong>");
    } else if(parseFloat(valueTemperatura) >= 39.5  && parseFloat(valueTemperatura) < 41.0) { // NARANJA
        $('#temperatura').addClass('borde-temperatura-fiebre-alta');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre Alta' + "</strong>");
    } else if(parseFloat(valueTemperatura) >= 41.0  && parseFloat(valueTemperatura) <= 45.0) {
        $('#temperatura').addClass('borde-temperatura-hipertermia');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Hipertermia' + "</strong>");
    } 
    var valueImc = $('#imc').val();
    if (valueImc < 18.5) {
        $('#imc').addClass('borde-imc-bajopeso');
        $('#imc-aceptado').html("<strong>" + 'Paciente Bajo de Peso' + "</strong>");
    } else if (valueImc >= 18.5 && valueImc< 24.9) {
      $('#imc').addClass('borde-imc-normal');
      $('#imc-aceptado').html("<strong>" + 'Paciente Peso Normal' + "</strong>");
      //NORMAL
    } else if (valueImc >= 25.0 && valueImc< 29.9){
      $('#imc').addClass('borde-imc-sobrepeso');
      $('#imc-aceptado').html("<strong>" + 'Paciente Sobrepeso' + "</strong>");
      //SOBREPESO
    } else if (valueImc >= 30.0 && valueImc< 34.9){
      $('#imc').addClass('borde-imc-obesidad1');
      $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad I' + "</strong>");
      //OBESIDAD I
    } else if (valueImc >= 35.0 && valueImc< 39.9){
      $('#imc').addClass('borde-imc-obesidad2');
      $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad II' + "</strong>");
      // OBESIDAD II
    } else if (valueImc >= 40.0){
      $('#imc').addClass('borde-imc-obesidad3');
      $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad III' + "</strong>");
      // OBESIDAD III
    }
    var id_prioridad_state = $('#id_prioridad').val();
    if (id_prioridad_state == '' || id_prioridad_state == '4' ){ 
      $('#col_3_hidden').show();
    } else if(id_prioridad_state == "1" || id_prioridad_state == "2" || id_prioridad_state == "3"){ 
      $('#col_3_hidden').hide();
    }
      
    $('#id_prioridad').change(function(){
    var id_prioridad_state = $('#id_prioridad').val();
    if (id_prioridad_state == "") {
      $('#id_topico').val(''); 
      $('#col_3_hidden').show();
    } else if(id_prioridad_state == "1" || id_prioridad_state == "2" || id_prioridad_state == "3"){ 
      $('#col_3_hidden').hide();
    }
});
    $('#id_prioridad').change(function() {
      $('#observacion_triaje').val('');
    });

    $("form").bind("keypress", function (e) {
      if (e.keyCode == 13) {
        $("#btnReg").attr('value');
        //add more buttons here
        return false;
      }
    });
    $("#temperatura").on('keydown input',function(event) {
      if (event.key === "Tab" || event.key === "Tab") {
        var value = $('#temperatura').val();
        if (Validar.esVacio(value) ) {
          alert('Ingrese el valor de la temperatura ( °C ) ');
          event.preventDefault();
          $(this).focus();
        } else if ( !Validar.decimalEnRango(value, 35.0, 40.0)) {
            alert('La temperatura debe estar entre 35.00 a 40.00 ( °C )');
            event.preventDefault();
            $(this).focus();
            
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
      }
    });

    $("#temperatura").click(function() {
      $(this).removeClass('borde-temperatura borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
      $('#texto-aceptado').html('');
    });
//------------//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
    $("#sa02_porcentaje").on('keydown',function(event) {
      if (event.key === "Tab" ) {
          var value = $('#sa02_porcentaje').val();
          if (Validar.esVacio(value) ) {
            alert('Ingrese el valor de la Saturación ( % ) ');
            event.preventDefault(); 
            $('#sa02_porcentaje').focus();
            return 0;
          } else if (!Validar.enteroEnRango(value, 60, 100)) {
            alert('El valor de la Saturación debe ser entre 60 a 100 ( % )');
            event.preventDefault(); 
            $('#sa02_porcentaje').focus();
            return 0;
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
      }
    });
    $("#sa02_porcentaje").click(function() {
      $('#sa02_porcentaje').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal');
      $('#saturacion-aceptado').html('');
    });
    
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
    $("#talla").click(function() {
      $('#imc').removeClass('borde-imc-bajopeso borde-imc-normal borde-imc-sobrepeso borde-imc-obesidad1 borde-imc-obesidad2 borde-imc-obesidad3');
      $('#imc-aceptado').html('');
    });
    $("#talla").keydown(function(event) {
        if (event.key === "Tab" ) {
          var value = $('#talla').val();
          if (Validar.esVacio(value) ) {
            alert('Ingrese el valor de la Talla ( cm ) ');
            $('#temperatura').focus();
            return 0;
          } else if (!Validar.enteroEnRango(value, 30, 200)) {
            alert('El valor de la talla debe ser entre 30 a 200 ( cm )');
            $('#temperatura').focus();
            return 0;
          } 
        }
    });
    $("#peso").keydown(function(event) {
      if (event.key === "Tab" ) {
        var value = $('#peso').val();
        if (Validar.esVacio(value) ) {
          alert('Ingrese el valor del Peso ( Kg ) ');
          $('#talla').focus();
          return 0;
        } else if (!Validar.decimalEnRango(value, 1.0, 180.0)) {
          alert('El valor del peso debe ser entre 1.0 a 180 ( Kg )');
          $('#talla').focus();
          return 0; 
        } else {
          compruebaIMC();
        }
      }
    });
    $("#peso").click(function() {
      $('#imc').removeClass('borde-imc-bajopeso borde-imc-normal borde-imc-sobrepeso borde-imc-obesidad1 borde-imc-obesidad2 borde-imc-obesidad3');
      $('#imc-aceptado').html('');
    });
//-------------------------------------------------------------------------------------------------
    $("#tamanio_cintura").keydown(function(event) {
      if (event.key === "Tab" ) {
        var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
        var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;
        var value = $('#tamanio_cintura').val();
        if (Validar.esVacio(value) ) {
          alert('Ingrese el tamanio de la cintura ( cm ) ');
          event.preventDefault(); 
          $('#tamanio_cintura').focus();
          return 0;
        } else if (!Validar.enteroEnRango(value, 50, 150)) {
          alert('El valor del tamanio de la cintura debe ser entre 50 a 150 ( cm )');
          event.preventDefault(); 
          $('#tamanio_cintura').focus();
          return 0;
        } 
      }
    });
    
//-------------------------------------------------------------------------------------------------
  /*  $("#frecuencia_cardiaca_fetal").keydown(function(event) {
      if (event.key === "Tab" ) {
        var beneficiario = document.frmTriajeEmergencia.beneficiario.value;
        var nro_doc_ident = document.frmTriajeEmergencia.nro_doc_ident.value;
        var value = $('#frecuencia_cardiaca_fetal').val();
        if (Validar.esVacio(value) ) {
          alert('Ingrese el valor de la Frecuencia Fetal ( / min ) ');
          event.preventDefault(); 
          $('#frecuencia_cardiaca_fetal').focus();
          return 0;
        } else if (!Validar.enteroEnRango(value, 0, 170)) {
          alert('El valor de la Frec. C. Fetal debe ser entre 0 a 170 ( / min )');
          event.preventDefault(); 
          $('#frecuencia_cardiaca_fetal').focus();
          return 0;
        } 
      }
    });*/

//-------------------------------------------------------------------------------------------------
    $("#presion_arterial_x").keydown(function(event) {
      if (event.key === "Tab" ) {
      var value = $('#presion_arterial_x').val();
        if (Validar.esVacio(value) ) {
          alert('Ingrese el valor de la Presion arterial ');
          event.preventDefault(); 
          $('#presion_arterial_x').focus();
          return 0;
        } else if (!Validar.enteroEnRango(value, 0, 250)) {
          alert('El valor debe estar entre 0 a 250');
          event.preventDefault(); 
          $('#presion_arterial_x').focus();
          return 0;
        }
      }
    });
//-------------------------------------------------------------------------------------------------
    $("#presion_arterial_y").keydown(function(event) {
          if (event.key === "Tab" ) {
            var value = $('#presion_arterial_y').val();
            if (Validar.esVacio(value) ) {
              alert('Ingrese el valor de la Presion arterial');
              $('#presion_arterial_x').focus();
              return 0;
            } else if (!Validar.enteroEnRango(value, 0, 140)) {
              alert('El valor debe estar entre 0 a 140');
              $('#presion_arterial_x').focus();
              return 0;
            }
          } 
    });
//-------------------------------------------------------------------------------------------------
    $("#frecuencia_respiratoria").keydown(function(event) {
      if (event.key === "Tab" ) {
        var value = $('#frecuencia_respiratoria').val();
        if (Validar.esVacio(value) ) {
          alert('Ingrese el valor de la Frecuencia Respiratoria ( /min )');
          event.preventDefault();
          $('#frecuencia_respiratoria').focus();
          return 0;
        } else if (!Validar.enteroEnRango(value, 0, 60)) {
          alert('El valor de la Frec. Respiratoria  debe estar entre 0 a 60 ( /min )');
          event.preventDefault();
          $('#frecuencia_respiratoria').focus();
          return 0;
        }
      } 
    });
//-------------------------------------------------------------------------------------------------
    $("#frecuencia_cardiaca").keydown(function(event) {
      if (event.key === "Tab" ) {
               
        var value = $('#frecuencia_cardiaca').val();
        if (Validar.esVacio(value) ) {
          alert('Ingrese el valor de la Frecuencia Cardiaca ( /min )');
          event.preventDefault();
          $('#frecuencia_cardiaca').focus();
          return 0;
        } else if (!Validar.enteroEnRango(value, 0, 170)) {
          alert('El valor de la Frecuencia Cardiaca debe ser entre 0 a 170 ( /min )');
          event.preventDefault();
          $('#frecuencia_cardiaca').focus();
          return 0;
        } 
      } 
    });
//-------------------------------------------------------------------------------------------------
      $("#fi02").keydown(function(event) {
        if (event.key === "Tab" ) {
          var value = $('#fi02').val();
          if (Validar.esVacio(value) ) {
            alert('Ingrese el valor de la Oxigenacion ( % )');
            event.preventDefault();
            $('#fi02').focus();
            return 0;
          } else if (!Validar.enteroEnRango(value, 21, 100)) {
            alert('El valor de la fIO2 debe estar entre 21 a 100 ( % )');
            event.preventDefault();
            $('#fi02').focus();
            return 0;
          }
        } 
      });
 
  });
 

  function getTopico() {
    var id_prioridad = $('#id_prioridad').val();
    $.ajax({
        url: '/get_topico_por_id_prioridad/' + id_prioridad,
        success: function (result) {
            var newOption = "";
            if ($("#id_prioridad").val() == "2" || $("#id_prioridad").val() == "3") {
              newOption = "<option value=''>---SELECCIONAR---</option>";
              if ($("#id_prioridad").val() == "1") {
                $('#id_topico').val('1');
              }
            }
            $(result).each(function (ii, oo) {
                newOption += "<option value='" + oo.id + "'>" + oo.descripcion +"</option>";
            });
            $('#id_topico').html(newOption);
            // $("#id_topico").select2({max_selected_options: 4}); 
            $('#id_topico').attr('required', true);

        }
    });
  }

  function compruebaIMC(){ 
    peso = document.frmTriajeEmergencia.peso.value;
    talla = document.frmTriajeEmergencia.talla.value;

    var beneficiario = document.frmTriajeEmergencia.beneficiario.value;

    if(beneficiario==""){
       alert("Falta buscar un paciente");
       document.frmTriajeEmergencia.peso.value="";
       document.frmTriajeEmergencia.talla.value="";
       $('#dni_beneficiario').focus();
       return 0;
    }

    if (talla != "") { 
        //PESO POR ALTURA
        var sexo = document.frmTriajeEmergencia.sexo.value; //MASCULINO , FEMENINO
        //edad
        var fecha_nac = document.frmTriajeEmergencia.fecha_nac.value; //12/05/1992
        var fechax= gfecha(fecha_nac);
        var datos_edad= calcularEdad(fechax);
        var edad_anos= 0;

        if(datos_edad!=""){
          var arregloedad= datos_edad.split("|");
          if(arregloedad.length>0){
            var edad_anos= arregloedad[0];
          }

        }

        var pesoIdeal=50+((talla-150)/4)*3+(edad_anos-20)/4
        if(sexo=="M") {
              pesoIdeal=pesoIdeal*0.9;
        }
        document.frmTriajeEmergencia.peso_x_altura.value = pesoIdeal.toFixed(2);
    }else{
       document.frmTriajeEmergencia.peso_x_altura.value = "";
    }

    if (peso != "" && talla != "") { 
        var talla_en_metros =  talla*0.01;     
        //IMC
        var imcx=  ( peso / (talla_en_metros * talla_en_metros) ).toFixed(2);
        document.frmTriajeEmergencia.imc.value = imcx;
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
        document.frmTriajeEmergencia.imc.value = '';
    }
  }

  function guardarTriaje(){
    var id_forma_ingreso = document.frmTriajeEmergencia.id_forma_ingreso.value;
    if (id_forma_ingreso == "") { 
      $('#id_forma_ingreso').focus(); 
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Recuerde que tiene que seleccionar un TIPO DE INGRESO VÁLIDO</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
      }); 
      return;
    }
    var id_estado_ingreso = document.frmTriajeEmergencia.id_estado_ingreso.value;
    if (id_estado_ingreso == "") { 
      $('#id_estado_ingreso').focus(); 
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Recuerde que tiene que seleccionar un ESTADO DE INGRESO VÁLIDO</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
      });
      return;
    }
    var id_motivo_ingreso = document.frmTriajeEmergencia.id_motivo_ingreso.value;
    if (id_motivo_ingreso == "") { 
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Recuerde que tiene que seleccionar un MOTIVO DE INGRESO VÁLIDO</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
      });
      $('#id_estado_ingreso').focus(); 
      return;
    }
    
    var id_prioridad = document.frmTriajeEmergencia.id_prioridad.value;
    if (id_prioridad == "") { 
      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert">Recuerde que tiene que seleccionar una PRIORIDAD VÁLIDA.</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
      });
      $('#id_prioridad').focus(); 
      return;
    }

    
    var id_topico = document.frmTriajeEmergencia.id_topico.value; 
    if(id_prioridad!='4'){
      if (id_topico == "") { 
        Swal.fire({
          title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
          html: '<p class="descripcion-alert">Recuerde que tiene que seleccionar un ÁREA Y/O SERVICIO VÁLIDO.</p>',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
        });
        $('#id_topico').focus(); 
        return;
      }
    } 


     var id_prioridad = document.frmTriajeEmergencia.id_prioridad.value;
     var id_topico = document.frmTriajeEmergencia.id_topico.value;
    /*
    if(id_prioridad=="" || id_prioridad=="0" ){
       alert("Falta seleccionar la Prioridad");
       $('#id_prioridad').focus();
       return 0;
     }else if(id_topico=="" || id_topico=="0"){
       alert("Falta seleccionar el Tópicoe");
       $('#id_topico').focus();
       return 0;

     }*/
    var prioridad_4 = $('#id_prioridad').val();
    
    if (prioridad_4 == "4") { 
      document.frmTriajeEmergencia.prioridadSeleccionada.value = 4;
      document.frmTriajeEmergencia.topicoSeleccionado.value =document.frmTriajeEmergencia.id_topico.value;
      document.frmTriajeEmergencia.observacionSeleccionado.value= document.frmTriajeEmergencia.observacion_triaje.value;

    } else if( prioridad_4 == "1" || prioridad_4 == "2" || prioridad_4 == "3"){
      document.frmTriajeEmergencia.prioridadSeleccionada.value = document.frmTriajeEmergencia.id_prioridad.value;
      document.frmTriajeEmergencia.topicoSeleccionado.value = document.frmTriajeEmergencia.id_topico.value;
      document.frmTriajeEmergencia.observacionSeleccionado.value= document.frmTriajeEmergencia.observacion_triaje.value;
    }
        // Validar información de signos vitales
    // ---------------------- TEMPERATURA --------------------------------
    /* var value = '';
    value = $('#temperatura').val()  //!                                         
    if (!Validar.esVacio(value) && !Validar.decimalEnRango(value, 35.0, 40.0)) {
      alert('La temperatura debe estar entre 35.00 a 40.00 ( °C )');
      return 0;
    }
    // ---------------------- TALLA --------------------------------
    value = $('#talla').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 30, 200)) {
      alert('La talla debe estar entre 30 a 200 ( cm )');
      return 0;
    }
    // ---------------------- PESO --------------------------------
    value = $('#peso').val()
    if (!Validar.esVacio(value) && !Validar.decimalEnRango(value, 1.0, 180.0)) {
      alert('El peso debe estar entre 1 a 180 ( Kg ) ');
      return 0;
    }
    // ---------------------- SATURACION --------------------------------
    value = $('#sa02_porcentaje').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 60, 100)) {
      alert('El valor de la Saturación debe ser entre 60 a 100 ( % )');
      return 0;
    }*/
     // ---------------------- TAMANIO CINTURA --------------------------------
    /*value = $('#tamano_cintura_cm').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 50, 150)) {
      alert('El valor del tamanio de la cintura debe ser entre 50 a 150 ( cm )');
      return 0;
    }
     // ---------------------- PRESION ARTERIAL X --------------------------------
    value = $('#presion_arterial_x').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 250)) {
      alert('La presión arterial debe estar entre 0 a 250');
      return 0;
    }
    // ---------------------- PRESION ARTERIAL Y --------------------------------
    value = $('#presion_arterial_y').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 140)) {
      alert('La presión arterial debe estar entre 0 a 140');
      return 0;
    }
    // ---------------------- FRECUENCIA RESPIRATORIA  (/min)  --------------------------------
    value = $('#frecuencia_respiratoria').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 60)) {
      alert('El valor de la Frec. Respiratoria  debe estar entre 0 a 60 ( /min )');
      return 0;
    }
    // ---------------------- FRECUENCIA CARDIACA  --------------------------------
    value = $('#frecuencia_cardiaca').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 170)) {
      alert('El valor de la Frec. C. debe ser entre 0 a 170 ( / min )');
      return 0;
    }
    // ---------------------- FRECUENCIA CARDIACA FETAL  --------------------------------
    if ($('#sexo').val()=="F") {
        value = $('#frecuencia_cardiaca_fetal').val()
      if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 170) ) {
        alert('La frecuencia cardiac fetal debe estar entre 0 a 170');
        return 0;
      }
    }
    // ---------------------- OXIGENACIÓN  --------------------------------
    value = $('#fi02').val()
    if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 21, 100)) {
      alert('El valor de la fIO2 debe estar entre 21 a 100 ( % )');
      return 0;
    }*/
    var temperatura = $('#temperatura').val();
    var talla = $('#talla').val();
    var saturacion = $('#sa02_porcentaje').val();
    //var tamanio_cintura = $('#tamano_cintura_cm').val();
    var presion_arterial_x = $('#presion_arterial_x').val();
    var presion_arterial_y = $('#presion_arterial_y').val();
    var frecuencia_respiratoria = $('#frecuencia_respiratoria').val();
    var frec_cardiaca = $('#frecuencia_cardiaca').val();
    var oxigenacion = $('#fi02').val();
    var frecuencia_cardiaca_fetal = $('#frecuencia_cardiaca_fetal').val();
    var sexo_paciente = $('#sexo').val();
                  
    if (!talla || !temperatura || !saturacion /*|| !tamanio_cintura*/ || !presion_arterial_x || !presion_arterial_y
    || !frecuencia_respiratoria || !frec_cardiaca || !oxigenacion) {
      
      alert('Ingresa todos los valores de signos vitales');
      return false;
    }else{      
        var r = confirm("¿Seguro de Actualizar el Tríaje de Emergencia?");
        if (r == true) {
          $('#frmTriajeEmergencia').submit();
        } 

     }

     
  }

    // function cargarComboDestino($select, data) {
    //     $select.html('');
    //     $(data).each(function (ii, oo) {
    //         $select.append('<option value="' + oo.id + '">' + oo.descripcion + '</option>')
    //     });
    // } 
      
</script>

<style>
  .select2-container {
    font-size: 11px !important;
  }

  .modal-dialog {
    width: 80%;
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
</style>

@endsection
@section('content')

<div class="loader"></div>
<div class="container">
  <div class="row">
    <div class="panel panel-primary filterable">
      <div class="panel-heading">
        <div class="row">
          <div class="col-lg-8 col-md-8 col-sm-12">
            <h3 class="panel-title" style="padding-top: 5px;">MÓDULO DE EDICION DE TRIAJE</h3>
          </div>
          <div class="col-lg-4 col-md-8 col-sm-12" style="text-align: right">
            <a href="/all_triajes_atendidos" class="btn btn-default btn-filter btn-sm">LISTA DE PACIENTES CON TRIAJE</a>
          </div>
        </div>
      </div>

      <div class="panel-body">
        <form class="app-form" name="frmTriajeEmergencia" method="post" action="{{ route('update_emergencia_triaje') }}"
          id="frmTriajeEmergencia" autocomplete="off">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="id_emergencia" id="id_emergencia" value="{{ $data->id_emergencia }}">
          <input type="hidden" name="id_asegurado" id="id_asegurado" value="{{ $data->id_asegurado }}">
          <input type="hidden" name="estado_11_triaje" id="estado_11_triaje" value="{{ $data->estado }}">
          <input type="hidden" name="prioridadSeleccionada" id="prioridadSeleccionada">
          <input type="hidden" name="topicoSeleccionado" id="topicoSeleccionado">
          <input type="hidden" name="observacionSeleccionado" id="observacionSeleccionado">

          <input type="hidden" name="codigo_ubig" id="codigo_ubig" value="{{ $data->codigo_ubig }}">
          <input type="hidden" name="id_departamento" id="id_departamento" value="{{ $data->id_departamento }}">
          <input type="hidden" name="id_provincia" id="id_provincia" value="{{ $data->id_provincia }}">
          <input type="hidden" name="id_distrito" id="id_distrito" value="{{ $data->id_distrito }}">

          <fieldset>     
            <div class="form-group col-sm-9" style="text-align: right;">
            </div>
            <div class="form-group col-sm-3" style="text-align: right;">
              <label style="cursor: pointer;"> REGISTRO MANUAL </label> 
              <input type="checkbox" id="habilitar_registro_manual"
                name="habilitar_registro_manual" style="margin-left: 10px;cursor: pointer;">
                <input id="registro_manual" name="registro_manual" type="hidden">
            </div>
          </fieldset>
          
 
          <fieldset>
            <div class="panel with-nav-tabs panel-default"
              style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
              <div class="panel-heading">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                      aria-expanded="true"><b><i class="fa fa-user" aria-hidden="true"></i>
                        INFORMACIÓN DEL PACIENTE I</b></a></li>
                </ul>
              </div>
              <div class="panel-body" style="padding-bottom: 0px !important;">
                <div class="tab-content">

                  <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                    <fieldset>
                      <div class="row">
                        <div class="col-lg-10 col-md-8 col-sm-12">
                          <div class="form-group" style="margin-bottom: 5px;"> 
                            <fieldset>
                              <div class="row">
                                <div class="form-group col-sm-3">
                                  <label for="tipo">TIPO DOC</label>
                                  <input readonly="" value="{{ $data->tipo_doc_ident }}" type="text"
                                    name="tipo_doc_ident" id="tipo_doc_ident" class="form-control  text-uppercase"
                                    tabindex="1">
                                </div>
                                <div class="form-group col-sm-3">
                                  <label for="dni_beneficiario">NRO DOC</label>
                                  <input readonly="" value="{{ $data->nro_doc_ident }}" type="text"
                                    name="dni_beneficiario" id="dni_beneficiario" class="form-control" tabindex="2">
                                </div>
                                <div class="form-group col-sm-2">
                                  <label>F.NACIMIENTO</label>
                                  <input readonly="" value="{{ $data->fecha_nac }}" type="text" name="fecha_nac"
                                    id="fecha_nac" class="form-control" tabindex="5">
                                </div>
                                <div class="form-group col-sm-4">
                                  <label>EDAD</label>
                                  <input readonly="" type="text" name="edad_paciente" id="edad_paciente"
                                    class="form-control">
                                  <input value="" type="hidden" name="edad_pac" id="edad_pac"
                                      class="form-control" readonly="">
                                </div>
                              </div>
                            </fieldset>

                            <fieldset style="margin-top: -3px !important;">
                              <div class="row">
                                <div class="form-group col-sm-2">
                                  <label>SEXO</label>
                                  <input readonly="" tabindex="4" value="{{ $data->sexo }}" type="text" name="sexo"
                                    id="sexo" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                  <label>PACIENTE</label>
                                  <input readonly="" type="text" tabindex="4" type="text" name="paciente" id="paciente"
                                    class="form-control"
                                    value="{{ $data->paterno }} {{ $data->materno }} {{ $data->nombre }}">
                                </div>
                                <div class="form-group col-sm-4">
                                  <label>T.BENEFICIARIO</label>
                                  <input readonly="" type="text" tabindex="4" type="text"
                                    name="descripcion_tipo_beneficiario_saludpol"
                                    id="descripcion_tipo_beneficiario_saludpol" class="form-control">

                                  <input readonly="" type="hidden" tabindex="4" value="{{ $data->paterno }}" type="text"
                                    name="paterno" id="paterno" class="form-control">
                                  <input readonly="" type="hidden" tabindex="4" value="{{ $data->materno }}" type="text"
                                    name="materno" id="materno" class="form-control">
                                  <input readonly="" type="hidden" tabindex="4" value="{{ $data->nombre }}" type="text"
                                    name="nombre" id="nombre" class="form-control">
                                </div>
                              </div>
                            </fieldset>  
                          </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12" style="text-align: center;">
                          <input id="foto_paciente" name="foto_paciente" type="hidden" value="{{ $data->foto }}">
                          @if($data->foto)
                          <img id="foto_imagen_paciente" src="" style="width: 50%;">
                          </img>
                          @endif
                          </input>
                        </div>
                      </div>
                    </fieldset>

                  </div>
                </div>
              </div>
            </div>
          </fieldset>






          <!------------------    INFORMACIÓN DEL INGRESO    ------------------>
          
          <fieldset>
            <div class="panel with-nav-tabs panel-default"
              style="font-size: 11px;padding-bottom: 0px !important; ">
              <div class="panel-heading">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                      aria-expanded="true"><b><i class="fa fa-book" aria-hidden="true"></i>
                        INFORMACIÓN DEL INGRESO</b></a></li>
                </ul>
              </div>
              <div class="panel-body" style="padding-bottom: 0px !important;">
                <div class="tab-content"> 
                  <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">

                    <fieldset style="padding-bottom: 10px !important;">
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label for="prioridad">FORMA DE INGRESO</label>
                          <select class="form-control input-sm" id="id_forma_ingreso" name="id_forma_ingreso"
                            onchange="getEstado_ingreso();">
                            <option value="" selected="">--SELECCIONAR--</option>
                            @foreach($formas_ingreso as $for_ing)
                            <option value="{{$for_ing->id}}" <?php if ($data->id_forma_ingreso == $for_ing->id) echo '
                              selected="selected";' ?> >{{$for_ing->descripcion}}</option>
                            @endforeach
                          </select>
                          </select>
                        </div>
                        <div class="form-group col-sm-3">
                          <label for="prioridad">ESTADO DE INGRESO</label>
                          <select class="form-control input-sm" id="id_estado_ingreso" name="id_estado_ingreso">
                            <option value="" selected="">--SELECCIONAR--</option>
                          </select>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="prioridad">MOTIVO DE INGRESO</label>
                          <select class="form-control input-sm" id="id_motivo_ingreso" name="id_motivo_ingreso">
                            <option value="" selected="">--SELECCIONAR--</option>
                            @foreach($motivo_ingreso as $mot_ing)
                            <option value="{{$mot_ing->id}}" <?php if ($data->id_motivo_ingreso == $mot_ing->id) echo '
                              selected="selected";' ?> >{{$mot_ing->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>
                         
                      </div>
                    </fieldset>

                    <fieldset name="seccion_habilitra_manual" id="seccion_habilitra_manual" style="margin-top: -13px !important;">
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label>FECHA DE INGRESO</label>
                          <input id="fecha_registro_ingreso" name="fecha_registro_ingreso" type="hidden">
                          <input type="text" class="form-control" autocomplete="off" 
                            id="fecha_registro_ingreso_select" name="fecha_registro_ingreso_select"/>
                        </div>
                        <div class="form-group col-sm-3">
                          <label>HORA DE INGRESO</label> 
                          <input type="time"  class="form-control" autocomplete="off" min="00:00:00" max="23:59:59" step="1"
                          id="hora_registro_ingreso" name="hora_registro_ingreso" >
                        </div> 
                      </div>
                    </fieldset>

                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <!------------------    INFORMACIÓN DEL INGRESO    ------------------>


          <!------------------    INFORMACIÓN DEL INGRESO    ------------------>
          <!------------------    INFORMACIÓN DEL INGRESO    ------------------>
          <fieldset>
            <div class="panel with-nav-tabs panel-default"
              style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
              <div class="panel-heading">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                      aria-expanded="true"><b><i class="fa fa-heartbeat" aria-hidden="true"></i> INFORMACIÓN DEL
                        INGRESO</b></a></li>
                </ul>
              </div>
              <div class="panel-body" style="padding-bottom: 0px !important;">
                <div class="tab-content">

                  <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">  
                    <fieldset>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label>PRESIÓN ARTERIAL</label>
                          <div class="row">
                            <div class="col-xs-5">
                              <input value="{{ $data->presion_arterial_x }}" type="text" name="presion_arterial_x"
                                id="presion_arterial_x" class="form-control" tabindex="6"
                                onkeypress="return esEntero(event);" maxlength="3">
                            </div>
                            <div class="col-xs-2 text-center">
                              <label>/</label>
                            </div>
                            <div class="col-xs-5">
                              <input value="{{ $data->presion_arterial_y }}" type="text" name="presion_arterial_y"
                                id="presion_arterial_y" class="form-control" tabindex="7"
                                onkeypress="return esEntero(event);" maxlength="3">
                            </div>
                          </div>
                        </div>

                        <div class="form-group col-sm-3">
                          <label>FREC. CARDIACA (/min)</label>
                          <input value="{{ $data->frecuencia_cardiaca }}" type="text" name="frecuencia_cardiaca"
                            id="frecuencia_cardiaca" class="form-control" tabindex="8"
                            onkeypress="return esEntero(event);" maxlength="3">
                        </div>
                        <div class="form-group col-sm-3">
                          <label>FREC. RESPIRATORIA (/min)</label>
                          <input value="{{ $data->frecuencia_respiratoria }}" type="text" name="frecuencia_respiratoria"
                            id="frecuencia_respiratoria" class="form-control" tabindex="9"
                            onkeypress="return esEntero(event);" maxlength="3">
                        </div>
                        <div class="form-group col-sm-3">
                          <label>Fi02 (%)</label>
                          <input value="{{ $data->fi02 }}" type="text" name="fi02" id="fi02" class="form-control"
                            tabindex="10" onkeypress="return esEntero(event);" maxlength="3">
                        </div>
                      </div>
                    </fieldset>



                    <fieldset style="margin-top: -5px !important;">
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label>Sa02 (%)</label>
                          <input value="{{ $data->sa02_porcentaje }}" type="text" name="sa02_porcentaje"
                            id="sa02_porcentaje" class="form-control" tabindex="11" onkeypress="return esEntero(event);"
                            maxlength="3">
                          <span id="saturacion-aceptado"></span>
                        </div>
                        <div class="form-group col-sm-3">
                          <label>TEMPERATURA (°C)</label>
                          <input placeholder="00.00" value="{{ $data->temperatura }}" type="text" name="temperatura"
                            id="temperatura" class="form-control" tabindex="12"
                            onkeypress="return esNumero(event,this.id)" maxlength="5">
                          <span id="texto-aceptado"></span>
                        </div>
                        <div class="form-group col-sm-3">
                          <label>TALLA (cm)</label>
                          <input value="{{ $data->talla_cm }}" type="text" name="talla" id="talla" class="form-control"
                            tabindex="13" onkeyup="compruebaIMC()" onkeypress="return esEntero(event);" maxlength="3">
                        </div>
                        <div class="form-group col-sm-3">
                          <label>PESO (Kg)</label>
                          <input placeholder="00.00" value="{{ $data->peso_kg }}" type="text" name="peso" id="peso"
                            class="form-control" tabindex="14" onkeyup="compruebaIMC()"
                            onkeypress="return esNumero(event,this.id)" maxlength="6">
                        </div>
                      </div>
                    </fieldset>



                    <fieldset style="margin-top: -5px !important;">
                      <div class="row">
                        <div class="form-group col-sm-2">
                          <label>IMC &nbsp;</label>
                          <input value="{{ $imc }}" type="text" name="imc" id="imc" class="form-control" readonly="">
                          <span id="imc-aceptado"></span>
                        </div>
                        <div class="form-group col-sm-2">
                          <label>PESO POR ALTURA</label>
                          <input value="{{ $pesoIdeal }}" type="text" name="peso_x_altura" id="peso_x_altura"
                            class="form-control" readonly="">
                        </div>

                        <div class="form-group col-sm-2" style="text-align: right;padding-top: 5px;"  id="habilitar_es_gestante"
                        name="habilitar_es_gestante"> <br>
                          <label style="cursor: pointer;"> ES GESTANTE? </label>
                          <input type="checkbox" style="margin-left: 10px;cursor: pointer;" id="checkbox_es_gestante" name="checkbox_es_gestante">
                          <input id="es_gestante" name="es_gestante" type="hidden" value="{{$data->es_gestante}}">
                        </div> 
                       
                        <div id="seccion_gestante" name="seccion_gestante">
                          <div class="form-group col-sm-2" id="tamanio_cintura" style="display:none">
                            <label>TAMAÑO DE CINTURA (cm)</label>
                            <input value="{{ $data->tamano_cintura_cm }}" placeholder="00.00" type="text" tabindex="17" name="tamano_cintura_cm" id="tamano_cintura_cm"
                              class="form-control" onkeypress="return evitarLetras(event)" >
                          </div>
  
                          <div class="form-group col-sm-2" id="frecuencia_fetal" style="display:none">
                            <label>FREC. CARDIACA FETAL (/min)</label> 
                            <input value="{{ $data->frecuencia_cardiaca_fetal }}"  type="text" tabindex="17" placeholder="00.00"
                             name="frecuencia_cardiaca_fetal" id="frecuencia_cardiaca_fetal" class="form-control" onkeypress="return evitarLetras(event)" > 
                          </div>
                        </div>

                      </div>
                    </fieldset>


                  </div>

                </div>
              </div>
            </div>
          </fieldset>
          <!------------------    INFORMACIÓN DEL INGRESO    ------------------>
          <!------------------    INFORMACIÓN DEL INGRESO    ------------------>


          <!------------------    INFORMACIÓN DEL ADICIONAL    ------------------>
          <!------------------    INFORMACIÓN DEL ADICIONAL    ------------------>
          <fieldset>
            <div class="panel with-nav-tabs panel-default"
              style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
              <div class="panel-heading">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                      aria-expanded="true"><b><i class="fa fa-file-text" aria-hidden="true"></i>
                        INFORMACIÓN ADICIONAL</b></a></li>
                </ul>
              </div>
              <div class="panel-body" style="padding-bottom: 0px !important;">
                <div class="tab-content">

                  <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                    <fieldset>
                      <div class="row">
                        
                        <div class="form-group col-sm-3">
                          <label for="prioridad">PRIORIDAD</label>
                          <select class="form-control input-sm" id="id_prioridad" name="id_prioridad"
                            onchange="getArea_topico();"> 
                            <option value="" selected="">--SELECCIONAR--</option>
                            @foreach($prioridades as $p)
                            <option value="{{$p->id}}" <?php if ($data->id_prioridad == $p->id) echo 'selected="selected";' ?> >{{$p->prioridad}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div id="topico_toggle" class="form-group col-sm-3">
                          <label for="topico">AREA/SERVICIO</label>
                          <select class="form-control input-sm" id="id_topico" name="id_topico">
                            <option value="" selected="">--SELECCIONAR--</option>
                          </select> 
                        </div>
                        <div class="form-group col-sm-6">
                          <label>MOTIVO DE LA CONSULTA</label>
                          <input value="{{ $data->observacion_triaje }}" type="text" name="observacion_triaje"
                            id="observacion_triaje" class="form-control" style="text-transform: uppercase;">
                        </div>
                      </div>
                    </fieldset>
                  </div>

                </div>
              </div>
            </div>
          </fieldset>

          <div class="row">
            <div class="form-group col-sm-12" style="display: flex; justify-content: flex-end; gap: 1rem;">
              <a href="/all_triajes_atendidos" class="btn btn-danger">CANCELAR</a>
              <a class="btn btn-success" onclick="guardarTriaje()">GUARDAR CAMBIOS</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection