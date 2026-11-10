@extends('frontend.layouts.master')
@section('renderjs')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.function_edad')


{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!} 

<style>
  .form-control:focus {
    border-color: #66afe9;
    outline: 0;
    box-shadow: inset 0 1px 1px white, 0 0 8px white;
  }
  .select2-container { 
      font-size: 11px !important;
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

  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }
 
  .marginTop15px {
    margin-top: 15px;
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

</style>

<script>

  $('body').on('click', '#copiar_telefono', function () {   
    $('#telefono').val($('#telefono').val().replace(/[-\s]/g, "").replace(/-/g, "").replace(/[-\s\/]/g, ""));
    $('#telfconducido').val($('#telefono').val().replace(/[-\s]/g, "").replace(/-/g, "").replace(/[-\s\/]/g, "")); 
    $('#observacion_admision').focus();   
  });

  function getobtener_departamento_by_unidad_policial(){ 
    var id_unidad_policial = $('#id_unidad_policial').val();
    if(id_unidad_policial==''||id_unidad_policial==null){
      $('#id_departamento_evac').val("").change();
    }else{
      $.ajax({    
        url: '/obtener_departamento_by_unidad_policial/' + id_unidad_policial,
        success: function(result){                    
          console.log(result);
          $('#id_departamento_evac').val(result.id_departamento).change();
          $('#numero_oficio').focus();
        }
      });
    }
  }
  
  function getobtener_ubigeo_by_comisaria(){ 
    var id_comisaria = $('#id_comisaria').val(); 
    if(id_comisaria==''||id_comisaria==null){
      $('#DepartamentoAc').val("").change();
    }else{
      $.ajax({    
        url: '/obtener_ubigeo_by_comisaria/' + id_comisaria,
        success: function(result){                    
          //console.log(result[0].id_departamento);
          $('#DepartamentoAc').val(result[0].id_departamento).change(); 
          setTimeout(function() { 
            $('#ProvinciaAc').val(result[0].id_provincia).change();  
            setTimeout(function() { 
              $('#DistritoAc').val(result[0].id_distrito).change();  
            }, 600);
          }, 600);
        }
      });
    }
  }

  $("#dni_conducido_buscar").keydown(function(e) {
      if (e.which == 13 || e.which == 9 || event.key === "Tab") {
        getReniecByDni();
      }
    });

  function calcular_edad_paciente(){ 
    var fecha_nac='{{ $admision_emergencia->fecha_nac }}';
    if(fecha_nac != null && fecha_nac != undefined && fecha_nac.length>8 ){
      var oldDate = fecha_nac; //20/04/1946
      var values = oldDate.split(/[-\s\/]/g);
      if(values.length==1){
        var values = oldDate.split(/-/g);
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

      calcularEdadPersona(ano,mes,dia,'input','edad_paciente','')

    }
  }
 
  function getTipo_beneficiario(id_tipo_beneficiario){ 
    console.log('---'+id_tipo_beneficiario);

    var tipo_beneficiario_saludpol='';
    if(id_tipo_beneficiario=='1'){
      tipo_beneficiario_saludpol = "PADRES";
    }else if(id_tipo_beneficiario=='2'){
      tipo_beneficiario_saludpol = "HIJO MENOR DE EDAD";
    }else if(id_tipo_beneficiario=='3'){
      tipo_beneficiario_saludpol = "CONCUBINA(O)";
    }else if(id_tipo_beneficiario=='4'){
      tipo_beneficiario_saludpol = "CONYUGE";
    }else if(id_tipo_beneficiario=='5'){
      tipo_beneficiario_saludpol = "HIJO MAYOR DE EDAD";
    }else if(id_tipo_beneficiario=='6'){
      tipo_beneficiario_saludpol = "HIJO INCAPACITADO";
    }else if(id_tipo_beneficiario=='7'){
      tipo_beneficiario_saludpol = "DERECHOHABIENTE";
    }else if(id_tipo_beneficiario=='8'){
      tipo_beneficiario_saludpol = "TITULAR";
    }else if(id_tipo_beneficiario=='9'){
      tipo_beneficiario_saludpol = "PADRES SOBREVIVIENTE";
    }else if(id_tipo_beneficiario=='10'){
      tipo_beneficiario_saludpol = "ESPOSA";
    }else if(id_tipo_beneficiario=='11'){
      tipo_beneficiario_saludpol = "HIJO NO INCAPACITADO";
    }else if(id_tipo_beneficiario=='12'){
      tipo_beneficiario_saludpol = "OTROS";
    }else if(id_tipo_beneficiario=='13'){
      tipo_beneficiario_saludpol = "CIVIL";
    } else{
      tipo_beneficiario_saludpol = "CIVIL";
    } 
    console.log(tipo_beneficiario_saludpol);
    $("#tipo_beneficiario").val(tipo_beneficiario_saludpol); 
  }

  function valiar_estado_parentesco(){  
    $tipo_parentesco = $('select[name="id_tipo_parentesco"] option:selected').text(); 
    $('#dni_conducido_buscar').val('');
    $('#conducido').val(''); 
    if($tipo_parentesco == "SOLO"){ 
      $('#seccion_de_acompaniante').hide();  
      $('#div_contacto').hide(); 
    }else{   
      $('#seccion_de_acompaniante').show();
      $('#div_contacto').show();  
      
      setTimeout(function() {   
        $('#dni_conducido_buscar').focus();
      }, 500);    
    }
  }
  
  $(document).ready(function() {

    window.onbeforeunload = function(e) {
      var id_ticket_=$('#id_ticket_').val(); 
      if(id_ticket_ != null){
        llamada_triaje_amergencia(id_ticket_, 2,0,0,'cancelar');
      }
    };

    calcular_edad_paciente();
    ocultar_datos();
    generarCodigoSiteds(); 
    
    $('#seccion_datos_titular').hide();
    $('#seccion_de_acompaniante').hide();  
    $('#div_contacto').hide();  

    if (("{{old('id_tipo_parentesco')}}").length > 0) {
      $("#id_tipo_parentesco").val("{{old('id_tipo_parentesco')}}").change();
      if(parseInt("{{old('id_tipo_parentesco')}}") == 8){ 
        $('#seccion_de_acompaniante').hide();  
        $('#div_contacto').hide(); 
      }else{   
        $('#seccion_de_acompaniante').show();
        $('#div_contacto').show();  
        
        setTimeout(function() {   
          $('#dni_conducido_buscar').focus();
        }, 500);    
      }
    }

    ////obtenemos la foto
    var foto=$('#foto_paciente').val();
    console.log('-'+foto+'-');
    if(foto != undefined && foto!=null && foto!='' && foto.length!=0 && foto!='[object Object]'){   
      var imagen = document.getElementById("foto_imagen_paciente"); 
      //console.log(imagen);  
      imagen.src = "data:image/jpeg;base64," + foto;
    }
    var id_tipo_beneficiario = '{{ $objAsegurado->id_tipo_beneficiario }}';  
                if (id_tipo_beneficiario == 13) {
                  parentesco = "CIVIL";
                  bootbox.alert("El paciente no está asegurado, se admisionará como <b>CIVIL</b>.");
                  $('#div_oficial_guardia').show();
                  $('#div_numero_boleta').show();
                  
                  //$("#cod_siteds_susalud").attr("readonly", "readonly");
                  $('#observacion_admision').focus();
 
                  $('#seccion_datos_titular').hide();
                  
                } else{
                  $('#seccion_datos_titular').show();
                  
                  $('#div_oficial_guardia').hide();
                  $('#div_numero_boleta').hide();
                  //$("#cod_siteds_susalud").removeAttr("readonly")
                  //$('#cod_siteds_susalud').focus();
                } 
                getTipo_beneficiario(id_tipo_beneficiario);
                document.getElementById('id_parentesco').value = id_tipo_beneficiario;
 
    
    $('#telefono').val('{{$admision_emergencia->telefono}}');
    if($('#telefono').val()!=null){
      $('#telefono').val($('#telefono').val().replace(/[-\s]/g, "").replace(/-/g, "").replace(/[-\s\/]/g, "")); 
    } 

    if (('{{old("telefono")}}').length != 0) $("#telefono").val('{{old("telefono")}}');
    var id_ticket_=$('#id_ticket_').val();
    if(id_ticket_!=null){
      llamada_triaje_amergencia(id_ticket_, 4,0,0,'seleccionado');
    }
  });

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

  async function getProvincia(){
    var newOption = "";
    newOption = "<option value=''>---SELECCIONAR--</option>";
    $('#Provincia').html(newOption);
    //$("#id_anestesiologo").val('').trigger("change");

    var Departamento = $('#Departamento').val();
    let result;

    try {
      result = await $.ajax({
        url: '/obtener_provincia_por_departamento/' + Departamento,  
      });

      var newOption = ""; 
      newOption = "<option value=''>---SELECCIONAR--</option>";
      $(result).each(function (ii, oo) {
        newOption += "<option value='"+oo.id_provincia+"'>"+oo.descripcion+"</option>";
      });                 
      $('#Provincia').html(newOption);
      $("#Provincia").select2({max_selected_options: 4});
    } catch (error) {
      console.log(error);
    }
  }

  function getDistrito(){
    var newOption = "";
    newOption = "<option value=''>---SELECCIONAR--</option>";
    $('#Distrito').html(newOption);
    //$("#id_anestesiologo").val('').trigger("change");

    var Provincia = $('#Provincia').val();

    $.ajax({    
      url: '/obtener_distrito_por_provincia/' + Provincia,
      success: function(result){                    
      var newOption = "";
      newOption = "<option value=''>---SELECCIONAR--</option>";
      $(result).each(function (ii, oo) {
        newOption += "<option value='"+oo.id_distrito+"'>"+oo.descripcion+"</option>";
      });                 
      $('#Distrito').html(newOption);
      $("#Distrito").select2({max_selected_options: 4});      
      }
    });
  }
  


  /*
  function getEstadoIngreso(){
    var newOption = "";
    newOption = "<option value=''>---SELECCIONAR--</option>";
    $('#id_estado_ingreso').html(newOption);
    //$("#id_anestesiologo").val('').trigger("change");

    var idFormaIngreso = $('#id_forma_ingreso').val();

    $.ajax({    
      url: '/obtener_estadoingreso_por_formaingreso/' + idFormaIngreso,
      success: function(result){                    
      var newOption = "";
      newOption = "<option value=''>---SELECCIONAR--</option>";
      $(result).each(function (ii, oo) {
        newOption += "<option value='"+oo.id+"'>"+oo.descripcion+"</option>";
      });                 
      $('#id_estado_ingreso').html(newOption);
      //$("#id_estado_ingreso").select2({max_selected_options: 4});
      $("#id_estado_ingreso").select2();         
      }
    });
  }*/

  function obtener_Tipo_Violencia(){
    var newOption = "";
    newOption = "<option value=''>---SELECCIONAR--</option>";
    $('#id_tipo_violencia').html(newOption);

    var Violencia = $('#id_violencia').val();

    if(Violencia == 3){
      $('#seccion_comisaria').hide();
      $('#seccion_comisaria2').hide();
      $('#seccion_comisaria3').hide();
    }
    else
    {
      $('#seccion_comisaria').show();
      $('#seccion_comisaria2').show();
      $('#seccion_comisaria3').show();
    }

    $.ajax({    
      url: '/obtener_tipo_violencia/' + Violencia,
      success: function(result){                    
      var newOption = "";
      newOption = "<option value=''>---SELECCIONAR--</option>";
      $(result).each(function (ii, oo) {
        newOption += "<option value='"+oo.id_tipo_violencia+"'>"+oo.descripcion+"</option>";
      });                 
      $('#id_tipo_violencia').html(newOption);
      $("#id_tipo_violencia").select2({max_selected_options: 4});      
      }
    });
  }

   function ocultar_datos(){
    var id_motivo_ingreso='{{$admision_emergencia->id_motivo_ingreso}}';
        if(id_motivo_ingreso==3){
            $('#muestra-datos-violencia').show();
            $('#muestra-datos-evacuado').hide();
        } else if(id_motivo_ingreso==4){
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
      url: '../../../obtener_diagnostico/' + request.term,
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
  
  $tipo_beneficiario = $("#frmAdmision").find("#id_tipo_beneficiario");
  $tipo_beneficiario.select2({
      width: 'resolve' // need to override the changed default
  });
  $id_tipo_violencia = $("#frmAdmision").find("#id_tipo_violencia");
  $id_tipo_violencia.select2({
      width: 'resolve' // need to override the changed default
  });
  $ofical_emergencia = $("#frmAdmision").find("#oficial_guardia");
  $ofical_emergencia.select2({
      width: 'resolve' // need to override the changed default
  });
  $tipodoc_conducido = $("#frmAdmision").find("#tipodoc_conducido");
  $tipodoc_conducido.select2({
      width: 'resolve' // need to override the changed default
  });
  $id_tipo_parentesco = $("#frmAdmision").find("#id_tipo_parentesco");
  $id_tipo_parentesco.select2({
      width: 'resolve' // need to override the changed default
  });

    
  jQuery(document).on('focus', '.select2', function() {
      jQuery(this).siblings('select').select2('open');
  });

  function AddDiagnostico(){
    let newRow = "";
    let ind = $('#tblDiagnostico tbody tr').length;
    
    if(ind > 0){
        $('.id_diagnostico').each(function(){
            let ind_tmp = $(this).data("id");
            if(ind_tmp => ind){
                ind=Number(ind_tmp)+1;
            }
        });
                    
        fila_medicamentos=$('#tblDiagnostico tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        let idval = ind - 1;
        fila_medicamento=fila_medicamentos[idval];
        
    }
    
    let item_tipo_diagnostico = '';
    item_tipo_diagnostico += '<option value="0">---SELECCIONAR---</option>';
    item_tipo_diagnostico += '<option value="1">PRESUNTIVO</option>';
    item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';
    item_tipo_diagnostico += '<option value="3">REITERATIVO</option>';
    newRow += '<tr>';
    newRow +=
      '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico' +
      ind + '" class="form-control input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow += '<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico' + ind +
      '" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
    newRow += '<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico' + ind +
      '" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px;font-size: 12px;">' + item_tipo_diagnostico + '</select></td>';
    newRow +=
      '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';

    $('#tblDiagnostico tbody').append(newRow);
    $('#nombre_diagnostico' + ind).focus();
    
    let sexo = $('#nomsexo').val();
    if (sexo == 'M') {
      genero = 1;
    } else {
      if (sexo == 'F') {
        genero = 2;
      }
    }
    $("#nombre_diagnostico" + ind).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          url: '../../../obtener_diagnostico_genero/' + request.term + '/' + genero,
          dataType: "json",
          success: function(data) {
            let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
            let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
            response(filtro);
          }
        })
      },
      select: function(e, ui) {
        let id_diagnostico = ui.item.id;
        $("#id_diagnostico" + ind).val(id_diagnostico);
        $("#nombre_diagnostico" + ind).prop("readonly", true);
        $("#cmbTipoDiagnostico" + ind).focus(); 
      }
    });

    $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      obj.parent().parent().remove();
      $('#addDiagnostico').focus();
    });
  
    $("#cmbTipoDiagnostico" + ind).change(function(){ 
      $('#addDiagnostico').focus();
    }); 
    
    $("#nombre_diagnostico"+ind).on('blur', function() {
        let id_diagnostico = $("#id_diagnostico"+ind).val();
        if(id_diagnostico == ""){
            bootbox.alert("Falta ingresar el diagnostico en esta fila");
            $("#nombre_diagnostico"+ind).val("");
            return false;
        }
    });
    
}

//AddDiagnostico();



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

function completarConCeros(numero, longitud) {
  let numeroString = numero.toString();
  while (numeroString.length < longitud) {
    numeroString = '0' + numeroString;
  }
  return numeroString;
}
//-------------------------------------------------------------
function validateFrm(){   


  var id_tipo_beneficiario = '{{ $objAsegurado->id_tipo_beneficiario }}';  
  if (id_tipo_beneficiario != 13) {
    var cod_siteds= $('#cod_siteds_susalud').val();
    if ($('#frmAdmision').find("#cod_siteds_susalud").val() == "") {
        bootbox.alert("Recuerde que tiene que Ingresar el CÓDIGO SITEDS", function(){     
        document.getElementById("cod_siteds_susalud").click();
          setTimeout(function (){ 
            $('#cod_siteds_susalud').focus();
        }, 500);
      });
      $('#btnReg').prop('disabled', false);           
      return false;
    }else{
      ///tomamos el numero ingresado en el codigo siteds
      $("#cod_siteds_susalud").val(completarConCeros(cod_siteds,12));  
    }
  }

    if ($('#frmAdmision').find("#Departamento").val() == "") {
        bootbox.alert("Debe Seleccionar el Departamento, Provincia y Distrito", function(){     
        document.getElementById("Departamento").click();
          setTimeout(function (){
            $('#Departamento').next('.select2-container').addClass('borde-alerta');
            $('#Departamento').focus();
        }, 500);
      });
      $('#btnReg').prop('disabled', false);           
      return false;
    }else{
      $('#Departamento').next('.select2-container').removeClass('borde-alerta');
    }
    

    if ($('#frmAdmision').find("#Provincia").val() == "") {
          bootbox.alert("Debe Seleccionar el Departamento, Provincia y Distrito", function(){     
            document.getElementById("Provincia").click();
             setTimeout(function (){
                $('#Provincia').next('.select2-container').addClass('borde-alerta');
                $('#Provincia').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
      }else{
        $('#Provincia').next('.select2-container').removeClass('borde-alerta');
      }
    

  if ($('#frmAdmision').find("#Distrito").val() == "") {
        bootbox.alert("Debe Seleccionar el Departamento, Provincia y Distrito", function(){     
          document.getElementById("Distrito").click();
           setTimeout(function (){
              $('#Distrito').next('.select2-container').addClass('borde-alerta');
              $('#Distrito').focus();
          }, 500);
        });
        $('#btnReg').prop('disabled', false);           
        return false;
    }else{
      $('#Distrito').next('.select2-container').removeClass('borde-alerta');
    }


  if ($('#frmAdmision').find("#direccion_referencial").val() == "") {
      bootbox.alert("Falta Ingresar la Direccion Referencial", function(){            
        document.getElementById("direccion_referencial").click();
        setTimeout(function (){
            $('#direccion_referencial').addClass('borde-alerta');
            $('#direccion_referencial').focus();
        }, 500);
      });
      $('#btnReg').prop('disabled', false);           
      return false;
  }else{
    $('#direccion_referencial').removeClass('borde-alerta');
  }

    if ($('#frmAdmision').find("#telefono").val() == "") {
        bootbox.alert("Falta Ingresar un telefono para contacto", function(){            
          document.getElementById("telefono").click();
           setTimeout(function (){ 
              $('#telefono').addClass('borde-alerta');
              $('#telefono').focus();
          }, 500);
        });
        $('#btnReg').prop('disabled', false);           
        return false;
    }else{
      
      var telefono = $('#telefono').val();   
      var trimmedStr_telefono = '';
      if(telefono != null && telefono !=''){
        trimmedStr_telefono = telefono.trim().replace(/\s+/g, '').replace(/[-\s]/g, "").replace(/-/g, "").replace(/[-\s\/]/g, "");
      }   
      $("#telefono").val(trimmedStr_telefono); 
      $('#telefono').removeClass('borde-alerta');
    }

    $tipo_parentesco = $('select[name="id_tipo_parentesco"] option:selected').text(); 
      //////valida el tipo de acompañanate
      //////valida el tipo de acompañanate 
    if($tipo_parentesco!='SOLO'){
      if ($('#frmAdmision').find("#id_tipo_parentesco").val() == "") {
          bootbox.alert("Falta seleccionar el TIPO DE PARENTESCO", function(){            
            document.getElementById("id_tipo_parentesco").click();
            setTimeout(function (){
                $('#id_tipo_parentesco').next('.select2-container').addClass('borde-alerta');
                $('#id_tipo_parentesco').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
      }else{
        $('#id_tipo_parentesco').next('.select2-container').removeClass('borde-alerta');
      }
    }
      //////valida el tipo de acompañanate
      //////valida el tipo de acompañanate

    if ($('#frmAdmision').find("#id_forma_ingreso").val() == "") {
        bootbox.alert("Falta seleccionar la Forma de Ingreso", function(){            
          document.getElementById("id_forma_ingreso").click();
           setTimeout(function (){
              $('#id_forma_ingreso').next('.select2-container').addClass('borde-alerta');
              $('#id_forma_ingreso').focus();
          }, 500);
        });
        $('#btnReg').prop('disabled', false);           
        return false;
    }else{
      $('#id_forma_ingreso').next('.select2-container').removeClass('borde-alerta');
    }

    if ($('#frmAdmision').find("#id_estado_ingreso").val() == "") {
        bootbox.alert("Falta seleccionar el Estado de Ingreso", function(){            
          document.getElementById("id_estado_ingreso").click();
          setTimeout(function (){
              $('#id_estado_ingreso').next('.select2-container').addClass('borde-alerta');
              $('#id_estado_ingreso').focus();
          }, 500);
        });
        $('#btnReg').prop('disabled', false);           
        return false;
    }else{
      $('#id_estado_ingreso').next('.select2-container').removeClass('borde-alerta');
    }

    if ($('#frmAdmision').find("#id_categoria").val() == "") {
        bootbox.alert("Falta seleccionar el Motivo de Ingreso", function(){            
          document.getElementById("id_categoria").click();
          setTimeout(function (){
              $('#id_categoria').next('.select2-container').addClass('borde-alerta');
              $('#id_categoria').focus();
          }, 500);
        });
        $('#btnReg').prop('disabled', false);           
        return false;
    }else{
      $('#id_categoria').next('.select2-container').removeClass('borde-alerta');
    }

    $('#dni_conducido').val($('#dni_conducido_buscar').val()); 
    
    if ($('#frmAdmision').find("#dni_conducido").val() != "") {
      if ($('#frmAdmision').find("#id_tipo_beneficiario").val() == "0") {
        bootbox.alert("Falta seleccionar el <b>PARENTESCO DEL ACOMPAÑANTE.</b>", function(){            
          document.getElementById("id_tipo_beneficiario").click();                   
              setTimeout(function (){
              $('#id_tipo_beneficiario').next('.select2-container').addClass('borde-alerta');
              $('#id_tipo_beneficiario').focus();
              }, 500);
        });
        $('#btnReg').prop('disabled', false);           
        return false;
      }else{
        $('#oficial_guardia').next('.select2-container').removeClass('borde-alerta');
      }
    }

    if ($('#frmAdmision').find("#id_tipo_beneficiario").val() != "0") {
  
      //////valida el tipo de acompañanate
      //////valida el tipo de acompañanate
      if($tipo_parentesco!='SOLO'){
        if ($('#frmAdmision').find("#dni_conducido").val() == "") {
          bootbox.alert("Falta ingresar DNI y los datos del Acompañante", function(){            
            document.getElementById("dni_conducido_buscar").click();                   
                setTimeout(function (){
                $('#dni_conducido_buscar').addClass('borde-alerta');
                $('#dni_conducido_buscar').focus();
                }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
        }else{
          $('#dni_conducido_buscar').removeClass('borde-alerta');
        }
      }
      //////valida el tipo de acompañanate
      //////valida el tipo de acompañanate

    }

    /*
    */ 
    if ($('#frmAdmision').find("#id_parentesco").val() == "13") {
      if (($('#frmAdmision').find("#oficial_guardia").val() == "" || $('#frmAdmision').find("#oficial_guardia").val() == "0" ) && 
      ($('#frmAdmision').find("#numero_boleta").val() == "" || $('#frmAdmision').find("#numero_boleta").val().length<=5 )) {
        bootbox.alert("El paciente está siendo admisionado como <b>CIVIL</b><br>Seleccione a un oficial de Guardia ó Agregue un número de Boleta válido.", function(){            
          document.getElementById("dni_conducido_buscar").click();                   
              setTimeout(function (){
              $('#oficial_guardia').next('.select2-container').addClass('borde-alerta');
              $('#oficial_guardia').focus();
              }, 500);
        });
        $('#btnReg').prop('disabled', false);           
        return false;
      }else{
        $('#oficial_guardia').next('.select2-container').removeClass('borde-alerta');
      } 
    }
 
    //violencia
              
      
    if ($('#frmAdmision').find("#id_categoria").val() == "3") {
        if ($('#frmAdmision').find("#id_violencia").val() == "") {
          bootbox.alert("Falta seleccionar Tipo de Violencia", function(){            
            document.getElementById("id_violencia").click();
             setTimeout(function (){
                $('#id_violencia').next('.select2-container').addClass('borde-alerta');
                $('#id_violencia').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
        }else{
          $('#id_violencia').next('.select2-container').removeClass('borde-alerta');
        }


        if ($('#frmAdmision').find("#id_tipo_violencia").val() == "") {
          bootbox.alert("Falta seleccionar la Modalidad de Violencia", function(){            
            document.getElementById("id_tipo_violencia").click();
             setTimeout(function (){
                $('#id_tipo_violencia').next('.select2-container').addClass('borde-alerta');
                $('#id_tipo_violencia').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
        }else{
          $('#id_tipo_violencia').next('.select2-container').removeClass('borde-alerta');
        }


        var id_violencia_valida = $("#id_violencia").val();
        if (id_violencia_valida != 3) {
          if ($('#frmAdmision').find("#id_comisaria").val() == "") {
            bootbox.alert("Falta seleccionar la Comisaria", function(){            
              document.getElementById("id_comisaria").click();
               setTimeout(function (){
                  $('#id_comisaria').next('.select2-container').addClass('borde-alerta');
                  $('#id_comisaria').focus();
              }, 500);
            });
            $('#btnReg').prop('disabled', false);           
            return false;
          }else{
            $('#id_comisaria').next('.select2-container').removeClass('borde-alerta');
          }
        }


        if ($('#frmAdmision').find("#id_parentesco").val() != "13") {
          if ($('#frmAdmision').find("#id_situacion").val() == "") {
            bootbox.alert("Falta Seleccionar la Situación del Paciente", function(){            
              document.getElementById("id_situacion").click();
              setTimeout(function (){
                  $('#id_situacion').next('.select2-container').addClass('borde-alerta');
                  $('#id_situacion').focus();
              }, 500);
            });
            $('#btnReg').prop('disabled', false);           
            return false;
          }else{
            $('#id_situacion').next('.select2-container').removeClass('borde-alerta');
          }
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

        if (id_violencia_valida != 3) {
          if ($('#frmAdmision').find("#DepartamentoAc").val() == "") {
            bootbox.alert("Debe Seleccionar el Departamento, Provincia y Distrito", function(){            
              document.getElementById("DepartamentoAc").click();
               setTimeout(function (){
                  $('#DepartamentoAc').next('.select2-container').addClass('borde-alerta');
                  $('#DepartamentoAc').focus();
              }, 500);
            });
            $('#btnReg').prop('disabled', false);           
            return false;
          }else{
            $('#DepartamentoAc').next('.select2-container').removeClass('borde-alerta');
          }


          if ($('#frmAdmision').find("#ProvinciaAc").val() == "") {
            bootbox.alert("Debe Seleccionar el Departamento, Provincia y Distrito", function(){            
              document.getElementById("ProvinciaAc").click();
               setTimeout(function (){
                  $('#ProvinciaAc').next('.select2-container').addClass('borde-alerta');
                  $('#ProvinciaAc').focus();
              }, 500);
            });
            $('#btnReg').prop('disabled', false);           
            return false;
          }else{
            $('#ProvinciaAc').next('.select2-container').removeClass('borde-alerta');
          }


          if ($('#frmAdmision').find("#DistritoAc").val() == "") {
            bootbox.alert("Debe Seleccionar el Departamento, Provincia y Distrito", function(){            
              document.getElementById("DistritoAc").click();
               setTimeout(function (){
                  $('#DistritoAc').next('.select2-container').addClass('borde-alerta');
                  $('#DistritoAc').focus();
              }, 500);
            });
            $('#btnReg').prop('disabled', false);           
            return false;
          }else{
            $('#DistritoAc').next('.select2-container').removeClass('borde-alerta');
          }
        }


        if ($('#frmAdmision').find("#lugar_accidente").val() == "") {
          bootbox.alert("Falta ingresar el lugar del accidente", function(){            
            document.getElementById("lugar_accidente").click();
             setTimeout(function (){ 
                $('#lugar_accidente').addClass('borde-alerta');
                $('#lugar_accidente').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
        }else{
          $('#lugar_accidente').removeClass('borde-alerta');
        }

      if (id_violencia_valida != 3) {
        if ($('#frmAdmision').find("#id_unidad").val() == "") {
          bootbox.alert("Falta Seleccionar la Unidad Policial", function(){            
            document.getElementById("id_unidad").click();
             setTimeout(function (){
                $('#id_unidad').next('.select2-container').addClass('borde-alerta');
                $('#id_unidad').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
        }else{
          $('#id_unidad').next('.select2-container').removeClass('borde-alerta');
        }
      }
    }
    //evacuado
    if ($('#frmAdmision').find("#id_categoria").val() == "4") {

      if ($('#frmAdmision').find("#id_unidad_policial").val() == "") {
          bootbox.alert("Falta Seleccionar la Unidad Policial", function(){            
            document.getElementById("id_unidad_policial").click();
             setTimeout(function (){
                $('#id_unidad_policial').next('.select2-container').addClass('borde-alerta');
                $('#id_unidad_policial').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
        }else{
          $('#id_unidad_policial').next('.select2-container').removeClass('borde-alerta');
        }

        if ($('#frmAdmision').find("#id_departamento_evac").val() == "") {
          bootbox.alert("Falta Seleccionar el Departamento de Referencia", function(){            
            document.getElementById("id_departamento_evac").click();
             setTimeout(function (){
                $('#id_departamento_evac').next('.select2-container').addClass('borde-alerta');
                $('#id_departamento_evac').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
        }else{
          $('#id_departamento_evac').next('.select2-container').removeClass('borde-alerta');
        }


        if ($('#frmAdmision').find("#numero_oficio").val() == "") {
          bootbox.alert("Falta Ingresar el Número Oficio", function(){            
            document.getElementById("numero_oficio").click();
             setTimeout(function (){
                $('#numero_oficio').addClass('borde-alerta');
                $('#numero_oficio').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
        }else{
          $('#numero_oficio').removeClass('borde-alerta');
        }
        /*
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
        */


        if ($('#frmAdmision').find("#id_situacion_evac").val() == "") {
          bootbox.alert("Falta Seleccionar la Unidad Policial", function(){            
            document.getElementById("id_situacion_evac").click();
             setTimeout(function (){
                $('#id_situacion_evac').next('.select2-container').addClass('borde-alerta');
                $('#id_situacion_evac').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
        }else{
          $('#id_situacion_evac').next('.select2-container').removeClass('borde-alerta');
        }

        if ($('#frmAdmision').find("#observacion_evac").val() == "") {
          bootbox.alert("Falta Ingresar la observación", function(){            
            document.getElementById("observacion_evac").click();
             setTimeout(function (){
                $('#observacion_evac').addClass('borde-alerta');
                $('#observacion_evac').focus();
            }, 500);
          });
          $('#btnReg').prop('disabled', false);           
          return false;
        }else{
          $('#observacion_evac').removeClass('borde-alerta');
        }

        let ind_diag = $('#tblDiagnostico tbody tr').length;
        if(ind_diag > 0){
          fila_dignosticos=$('#tblDiagnostico tbody').children('tr');
          for (i = 0; i < ind_diag; i++) {
            fila_dignostico=fila_dignosticos[i];
            let id_diagnostico = $(fila_dignostico).find('.id_diagnostico').val();
            let id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico').val();
            if(id_diagnostico == ""){
              bootbox.alert("Debe ingresar el nombre de diagnostico");
              return false;
            }
            if(id_tipo_diagnostico == "0") {
              bootbox.alert("Debe ingresar el tipo de diagnostico"); 
              return false;
            }
          }
        }else{
          bootbox.alert("Debe ingresar minimo un diagnostico");      
          return false;
        }
        
    }

    
    ///////////////////////////VALIDAR CODIGO DE SITEDS COLOCAR EL EL CAMPO///////////////////////////
    ///////////////////////////VALIDAR CODIGO DE SITEDS COLOCAR EL EL CAMPO/////////////////////////// 
    var id_tipo_beneficiario = '{{ $objAsegurado->id_tipo_beneficiario }}';  
    if (id_tipo_beneficiario != 13) { 
      var codSiteds= $('#cod_siteds_susalud').val();  
      $.ajax({
        url:'{{url('validar_dato_siteds_emergencia/')}}/'+codSiteds,
        type:'GET',
        dataType:'JSON', 
          success: function(data){   
            if(data!=null){ 
              console.log('data[0].cantidad_siteds:'+ data.cantidad_siteds); 
              if(data.cantidad_siteds!=0){ 
                
                
                  Swal.fire({
                    title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
                    html: '<p class="descripcion-alert"><span>EL código SITEDS <b style="color:red;">'+cod_siteds+'</b> YA EXISTE, verifique que sea correcto y vuelva a intentarlo.<br><br></span></p>', 
                    allowOutsideClick: false,
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                  }); 
                  $('#cod_siteds_susalud').val(''); 
                  setTimeout(function (){ 
                      $('#cod_siteds_susalud').focus();
                  }, 2500);


              } else{ 
                submitFrm();
                return false;
              } 
            } else{ 
              submitFrm();
              return false;
            }
          }
      });
    } else{
      submitFrm();
      return false;
    }
    ///////////////////////////VALIDAR CODIGO DE SITEDS COLOCAR EL EL CAMPO///////////////////////////
    ///////////////////////////VALIDAR CODIGO DE SITEDS COLOCAR EL EL CAMPO///////////////////////////

}
    
    function submitFrm() {
      bootbox.confirm({
          message: "Revise que los datos se encuentren correctos antes de registrar. ¿Desea Grabar?.",
          //className: 'title-dialog',
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
                  $("#frmAdmision").submit();
              } else {
                  $('#btnReg').prop('disabled', false);
              }
          }
      });
    }
    //--------------------------------------------------------------------------
  
  

  function limpiaDatos() { 
    var nivel     = $('#nivel').val();     
    if(nivel==2){
      $("#turno").val('').trigger("change");
      var newOption = "";
      newOption = "<option value=''>---SELECCIONAR--</option>";
      $('#id_medico').html(newOption);
      $("#id_medico").val('').trigger("change");
    } 
  }


    function getProvinciaAc(){
        var newOption = "";
        newOption = "<option value=''>---SELECCIONAR--</option>";
        $('#ProvinciaAc').html(newOption); 
        var Departamento = $('#DepartamentoAc').val();

        $.ajax({    
          url: '/obtener_provincia_por_departamento/' + Departamento,
          success: function(result){                    
          var newOption = ""; //var cant=0;
          newOption = "<option value=''>---SELECCIONAR--</option>";
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
        newOption = "<option value=''>---SELECCIONAR--</option>";
        $('#DistritoAc').html(newOption); 
        var Provincia = $('#ProvinciaAc').val();

        $.ajax({    
          url: '/obtener_distrito_por_provincia/' + Provincia,
          success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>---SELECCIONAR--</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id_distrito+"'>"+oo.descripcion+"</option>";
          });                 
          $('#DistritoAc').html(newOption);
          $("#DistritoAc").select2({max_selected_options: 4});      
          }
        });
    }

    /*$("#dni_conducido_buscar").blur(function(event) {
      getReniecByDni();
    });*/

    $('#tipodoc_conducido').change(function (e) { 
      e.preventDefault();
      let tipodoc_conducido = $(this).val();
        $('#dni_conducido_buscar').val('');
        $('#conducido').val('');
        $('#conducido').prop('readonly', false);
        $('#dni_conducido_buscar').focus();
      if (tipodoc_conducido == '1') {
        $('#conducido').prop('readonly', true);
        $('#dni_conducido_buscar').focus();
      }
    });

    function getReniecByDni() { 
      let dni_conducido_buscar = $('#dni_conducido_buscar').val();     
      let dni_beneficiario = $('#dni_beneficiario').val();  
      
      if (dni_conducido_buscar == '') {
            Swal.fire({
              title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
              html: '<p class="descripcion-alert"><span>Falta ingresar el Documento del Conducido</span></p>',
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'OK',
              preConfirm: function(){
                $('#dni_conducido_buscar').addClass('borde-alerta');
                $('#dni_conducido_buscar').focus();
                $('#dni_conducido').val('');
                $('#conducido').val('');
              }
            });
      } 
      
      if (dni_conducido_buscar == dni_beneficiario) {
            Swal.fire({
              title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
              html: '<p class="descripcion-alert"><span>EL DOCUMENTO INGRESADO COMO ACOMPAÑANTE, NO PUEDE SER EL MISMO DEL PACIENTE.<br><br></span></p>', 
              allowOutsideClick: false,
              timer: 1500,
              timerProgressBar: true,
              showConfirmButton: false,
              allowOutsideClick: false,
            }); 
                setTimeout(function (){ 
                  $('#dni_conducido_buscar').addClass('borde-alerta');
                  $('#dni_conducido_buscar').val('');
                  $('#dni_conducido_buscar').focus();
                }, 2100);
            return;
      }

      Swal.fire({
        title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
        html: '<p class="descripcion-alert"><span>ESPERE UN MOMENTO POR FAVOR, VALIDANDO DOCUMENTO DE IDENTIDAD.<br><br></span></p>', 
        allowOutsideClick: false,
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false,
        allowOutsideClick: false,
      });
      $.ajax({
        url: '/validar_documento/' + dni_conducido_buscar,
        success: function(result) { 
        
            if (result.codigoMensaje != '00') {
              Swal.fire({
              title: '<div class="titulo-padding"><p>Alerta Triaje</p></div>',
              html: '<p class="descripcion-alert"><span>Documento no existe. ¿Desea registrar manualmente?</span></p>',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'si',
              }).then((result) => {
                if (result.isConfirmed) {
                  $('#dni_conducido_buscar').removeClass('borde-alerta');
                  $('#btnReg').prop('disabled', false);
                  $('#conducido').prop('readonly', false);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                  $('#dni_conducido_buscar').addClass('borde-alerta');
                  $('#btnReg').prop('disabled', true);
                  $('#conducido').prop('readonly', true);
                }
              });
          } else {
              conducido = result.nombres + ' ' + result.paterno + ' ' + result.materno; 
              $('#dni_conducido_buscar').removeClass('borde-alerta');
              $('#dni_conducido').val(result.dni).attr("readonly", true);
              $('#conducido').val(conducido).attr("readonly", true);
              $('#btnReg').prop('disabled', false);
              $('#tipodoc_conducido').val('1');
            } 
        },
        error: function(xhr, status, error) {
          // Lógica para manejar el error
          //console.log(xhr.responseText);
          try {
            var errorMessage = JSON.parse(xhr.responseText);
            console.log(errorMessage);
          } catch (e) {
            console.log('Error de web services de reniec');
            Swal.fire({
              title: '<div class="titulo-padding"><p>Alerta Admisión</p></div>',
              html: '<p class="descripcion-alert"><span>Documento no existe. ¿Desea registrar manualmente?</span></p>',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'si',
              }).then((result) => {
                if (result.isConfirmed) {
                  $('#dni_conducido_buscar').removeClass('borde-alerta');
                  $('#btnReg').prop('disabled', false);
                  $('#conducido').prop('readonly', false);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                  $('#dni_conducido_buscar').addClass('borde-alerta');
                  $('#btnReg').prop('disabled', true);
                  $('#conducido').prop('readonly', true);
                }
              });
          }
        }
      });

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

    $('#addDiagnostico').on('click', function() {
    AddDiagnostico();
    });

    $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
      let obj = $(this);
      obj.parent().parent().remove();
    });

    // TAGSITEDS
    function generarCodigoSiteds() {
      let message = "";
      const data = {!! json_encode($admision_emergencia) !!};
      const session = {!! json_encode(Auth::user()) !!}
      const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;

     /*  if (data.id_tipo_beneficiario && parseInt(data.id_tipo_beneficiario) === 13) { */
        const created_at = moment(data.created_at).startOf('day');
        const currentDate = moment().startOf('day');

        if (currentDate.isAfter(created_at) || currentDate.isBefore(created_at)){
          message = `Lamentamos informarle que el plazo para la acreditación del paciente ha expirado. La fecha máxima para completar la acreditación era <b>${created_at.format('DD-MM-YYYY')}<b/>.`;
          if (hasPermissionUpdateSitedsManual) {
            message += "<br><br><span style='font-size: 15px; color: #d9534f'>Sin embargo, puedes ingresar manualmente al SITEDS, pero recuerda que cualquier acción será bajo tu responsabilidad.</span>";
            $("#cod_siteds_susalud").val("");
            $("#cod_siteds_susalud").prop("readonly", false); 
          }
          Swal.fire({
            icon: 'warning',
            title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
            html: `<div style='padding: 20px'><span style='color: #000000; font-size: 18px'>${message}</span></div>`,
            confirmButtonText: 'ENTENDIDO',
            allowOutsideClick: false,
            allowEscapeKey: false,
          });
          return ;
        }
      /* } */

      //$("#btn_generate_siteds").prop("disabled", true);
      $("#cod_siteds_susalud").prop("readonly", true); 
      Swal.fire ({
        title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
          Swal.showLoading ()
        },
      })
      const body = {
        "idRemitente": $("#codigo_ipress").val(),
        "nuRucRemitente": "", 
        "idCorrelativo": data.id,
        "tiDoPaciente": data.id_tipo_doc_ident || 1,
        "nuDoPaciente": data.dni_beneficiario,
        "noResponsableAut": "",
        "noPaResponsableAut": "",
        "noMaResponsableAut": "",
        "nuDoResponsableAut": "",
        "coAdmisionista": data.dni_beneficiario,
        "beMaxInicial": "0",
        "coTiMoneda": "1",
        "coPagoFijo": "0",
        "coCalServicio": "VS",
        "coPagoVariable": "100",
        "flagCaGarantia": "0",
        "deFlagCartaGarantia": "Sigesapol 1.0", 
        "tipoAtencionId": "2",
        "servicioId": data.id_servicio
      };
      $.ajax({
          url:'/ws-dirsapol-siteds',
          type:'POST',
          contentType: "application/json",
          dataType: "json",
          data: JSON.stringify(body),
          success: (result, textStatus, jqXHR) => {
            $("#cod_siteds_susalud").val(result.nuAutorizacion)
            if (result.nuAutorizacion.endsWith("T") || String(result.nuAutorizacion).length <= 6) {
              message = result.message;
              if (hasPermissionUpdateSitedsManual) {
                  $("#cod_siteds_susalud").prop("readonly", false);
                  message = "Puede generar su c&oacute;digo válido en la web oficial de SITEDS y reemplazar el c&oacute;digo temporal en este sistema.";
              }
              Swal.fire({
                icon: "warning",
                title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
                html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${message}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
              });
            } else {
              Swal.fire({
                icon: (parseInt(jqXHR.status) === 201 || parseInt(jqXHR.status) === 200) ? "success" : 'warning',
                title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
                html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>${message || " Operación Exitosa"}</span></div>`,
                showConfirmButton: false,
                timer: '1500'
              });
          }
          },
          error: function(xhr, status, error){
            $("#cod_siteds_susalud").val("");
            $("#cod_siteds_susalud").prop("readonly", false); 
            message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. Por favor, inténtelo de nuevo más tarde o utilice SISTEDS a través del cliente o la web. Gracias.";
            Swal.fire({
              icon: 'error',
              title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
              html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${message}</span></div>`,
              confirmButtonText: 'ENTENDIDO',
            });
          }, 
          complete: function(xhr, status) {
            Swal.hideLoading();
          }
        })
    }

    function mantenerSituacionPacienteAlTenerObservacionesEnElFormulario () {
      const idSituacion = "{{old('id_situacion')}}";
      if (idSituacion.length > 0) $("#id_situacion").val(idSituacion).change();
    }
    /* function mantenerDistritoPacienteAlTenerObservacionesEnElFormulario () {
      const idDistrito = "{{old('Distrito')}}";
      console.log({distrito: idDistrito});
      if (idDistrito.length > 0) $("#Distrito").val(idDistrito).change();
    } */

    $(document).ready(function() {
          function handlerErrorsController() {
            const inputsConError = [];
            let message = "";
            @foreach ($errors->keys() as $input)
                inputsConError.push('{{ $input }}');
            @endforeach
           

            if (inputsConError && inputsConError.length > 0) {
              inputsConError.forEach(function(inputName) {
                $(`#${inputName}`).addClass("borde-alerta");
                $(`#${inputName}`).next('.select2-container').addClass('borde-alerta');
                if (inputName == 'edad_pac') {
                  $(`#edad_paciente2`).addClass("borde-alerta");
                }
              });
              @foreach ($errors->all() as $error)
                message += `<span style="color: red; font-size: 1.5rem;font-weight: 700px;">- {{ $error }}</span> <br/>`;
              @endforeach
              Swal.fire({
                title: '<div class="titulo-padding"><p>Campos incorrectos</p></div>',
                html: `<div style="text-align: left; margin: 0px 20px 0px 20px;">${message}</div>`,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ENTENDIDO',
                allowOutsideClick: false,
              });
            }
          };
          handlerErrorsController();
        })
        mantenerSituacionPacienteAlTenerObservacionesEnElFormulario();

        if (("{{count(trim(old('dni_conducido_buscar')))}}") > 0) {
          const currentDniConducido = "{{old('dni_conducido_buscar')}}";
          const inputDniConducido = document.getElementById("dni_conducido_buscar");
          inputDniConducido.value = currentDniConducido;
        }

</script>
<style>
  /* .modal-dialog {
    width: 850px !important;
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
  }

  .form-group {
    margin-bottom: 10px;
  }

  .nav-pills .nav-link.active,
  .nav-pills .show>.nav-link {
    color: #fff;
    background-color: #0d6efd;
  }

.borde-alerta {
  border-color: red !important;
  border: 2px red solid !important;
  outline: 0;
  border-radius: 4px;
  box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important; 
}

.swal2-icon.swal2-warning {
    font-size: 15px !important;
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
              id="frmAdmision" name="frmAdmision" autocomplete="off">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="codigo_ipress" id="codigo_ipress" value="{{$ipress_codigo}}">
              <input type="hidden" name="id" value="{{ $admision_emergencia->id }}">
              <input type="hidden" name="id_asegurado" value="{{ $admision_emergencia->id_asegurado }}"> 
              <input type="hidden" name="id_parentesco" id="id_parentesco" value=""> 
              <input type="hidden" id="id_ticket_" name="id_ticket_" value="{{ $admision_emergencia->id_ticket_admision }}">

              <fieldset>
                <div class="panel with-nav-tabs panel-default"
                  style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                  <div class="panel-heading">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab0default" data-toggle="tab" style="font-size:12px"
                          aria-expanded="true"><b><i class="fa fa-user" aria-hidden="true"></i>
                            INFORMACIÓN DE ADMISIÓN</b></a></li>
                    </ul>
                  </div>
                  <div class="panel-body" style="padding-bottom: 0px !important; margin-top: -20px !important;">
                    <div class="tab-content"> 
                      <div class="tab-pane fade active in" id="tab0default">
 
                        <fieldset style="margin-bottom: -10px !important;">
                          <div class="row">
                            <div class="col-md-2"><br />
                              <label>TIPO DE ATENCIÓN</label>
                              <input readonly="" type="text" class="form-control" value="EMERGENCIA" name="tipo_atencion"
                                id="tipo_atencion" />
                            </div>
                            <div class="col-md-2"><br />
                              <label>FEC.REGISTRO</label>
                              <input readonly="" type="text" class="form-control" value="<?php echo date("d/m/Y",
                                strtotime($fechaServidor)); ?>" name="fecha_atencion" id="fecha_atencion" style="text-align:left">
                            </div>
                            <div class="col-md-2"><br />
                              <label style="color:#3c763d !important;">N° DE ORDEN</label>
                              <input readonly="" type="text" class="form-control" value="{{str_pad($admision_emergencia->secuencia, 8, '0', STR_PAD_LEFT)}}" name="secuencia"
                                id="secuencia" style="text-transform: uppercase;border-color: #3c763d  !important; font-size: 20px;
                                border: 2px #3c763d solid !important; font-weight: bold; text-align: center; color: #3c763d" />
                            </div>
                            <div class="col-md-3"><br />
                              <label for="tipo">ESTABLECIMIENTO</label>
                              <input readonly="" type="text" class="form-control" value="{{$nombre_establecimiento}}" style="text-transform: uppercase;"/>
                            </div>
                            <div class="col-md-3"><br />
                              <label for="tipo" style="color: red;"><b>COD.SITEDS SUSALUD</b></label>
                              <div class="{{intval($admision_emergencia->id_tipo_beneficiario) != 13 ? 'input-group' : ''}}">
                                <input 
                                  type="text" 
                                  class="form-control" 
                                  id="cod_siteds_susalud" 
                                  name="cod_siteds_susalud" 
                                  onkeypress="return esEntero(event);"
                                  style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important" 
                                  maxlength="12" 
                                  pattern="[0-9]*"
                                  <?php if (intval($admision_emergencia->id_tipo_beneficiario) !== 13) echo 'readonly' ?>
                                />
                                @if (intval($admision_emergencia->id_tipo_beneficiario) != 13)
                                  <!--span class="input-group-btn">
                                    <button type="button" id="btn_generate_siteds" onclick="generarCodigoSiteds();" class="btn btn-sm btn-info" style="margin-top: -1px">Generar</button>
                                  </span-->
                                @endif
                              </div>
                              {{-- <input type="text" class="form-control"  id="cod_siteds_susalud" name="cod_siteds_susalud"  onkeypress="return esEntero(event);"
                              style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important"   maxlength="12"  pattern="[0-9]*"> --}}
                            </div>

                          </div>
                        </fieldset> 
 
                        <fieldset>
                          <div class="row">
                            <div class="col-md-4"><br />
                              <label>PRIORIDAD</label>
                              <input readonly="" type="text" class="form-control" value="{{$admision_emergencia->prioridad}}" name="prioridad"
                                id="tipo_atencion" />
                            </div>
                            <div class="col-md-3"><br />
                              <label>AREA/SERVICIO</label>
                              <input readonly="" type="text" class="form-control" value="{{$admision_emergencia->descripcion}}" name="topico" id="topico" style="text-align:left">
                            </div>
                            <div class="col-md-5"><br />
                              <label>CÓDIGO UPSS</label>
                              <input readonly="" type="text" class="form-control" value="{{$upss}} - {{$descripcion_upss}}" name="upss"
                                id="upss" /><br />
                            </div>
                          </div>
                        </fieldset>  

                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
 




              <!--informacion de paciente-->
              
              <fieldset>
                <div class="panel with-nav-tabs panel-default"
                  style="font-size: 11px;">
                  <div class="panel-heading">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                          aria-expanded="true"><b><i class="fa fa-user" aria-hidden="true"></i>
                            INFORMACIÓN DEL PACIENTE</b></a></li>
                    </ul>
                  </div>
                  <div class="panel-body" style="padding-bottom: 0px !important; margin-top: -20px !important;">
                    <div class="tab-content">
                      <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">



                        <fieldset> 
                            <div class="col-lg-10 col-md-8 col-sm-12">
                              <div class="form-group" > 
                                <fieldset style="">
                                  <div class="row">
                                    <div class="col-md-2"><br />
                                      <label for="tipo">TIPO DE DOCUMENTO</label>
                                      @if ($admision_emergencia->id_tipo_doc_ident == 1)
                                      <input readonly="" id="tipo_doc_ident" name="tipo_doc_ident" value="DNI" type="text" class="form-control">
                                      @elseif ($admision_emergencia->id_tipo_doc_ident == 2)
                                      <input readonly="" id="tipo_doc_ident" name="tipo_doc_ident" value="CARNET DE EXTRANJERIA" type="text" class="form-control">
                                      @elseif ($admision_emergencia->id_tipo_doc_ident == 3)
                                      <input readonly="" id="tipo_doc_ident" name="tipo_doc_ident" value="PASAPORTE" type="text" class="form-control">
                                      @elseif ($admision_emergencia->id_tipo_doc_ident == 4)
                                      <input readonly="" id="tipo_doc_ident" name="tipo_doc_ident" value="DOCUMENTO DE IDENTIDAD EXTRANJERO" type="text" class="form-control">
                                      @elseif ($admision_emergencia->id_tipo_doc_ident == 5)
                                      <input readonly="" id="tipo_doc_ident" name="tipo_doc_ident" value="NN" type="text" class="form-control">
                                      @endif 
                                      <input type="hidden" name="tipodoc_beneficiario_bus" id="tipodoc_beneficiario_bus" value="{{$admision_emergencia->id_tipo_doc_ident }}">
                                    </div>
                                    <div class="col-md-2"><br />
                                      <label for="dni_beneficiario">N° DE DOCUMENTO</label>
                                      <input readonly="" onKeyPress="return soloNumeros(event)" id="dni_beneficiario"  maxlength="8"  pattern="[0-9]*"
                                        name="dni_beneficiario" value="{{$admision_emergencia->dni_beneficiario}}" type="text"
                                        class="form-control text-uppercase"  autofocus="">

                                        <input type="hidden" name="dni_beneficiario" id="dni_beneficiario" value="{{$admision_emergencia->dni_beneficiario}}">
                                    </div>
                                    <div class="col-md-4"><br />
                                      <label>PACIENTE</label>
                                      <input type="text" readonly="" class="form-control" 
                                      value="{{$admision_emergencia->paterno}} {{$admision_emergencia->materno}} {{$admision_emergencia->nombre}}"
                                      name="beneficiario" id="beneficiario" />

                                        <input type="hidden" readonly="" class="form-control" value="{{$admision_emergencia->paterno}}"
                                          name="paterno_beneficiario" id="paterno_beneficiario" />
                                        <input readonly="" type="hidden" class="form-control" value="{{$admision_emergencia->materno}}"
                                          name="materno_beneficiario" id="materno_beneficiario" />
                                        <input readonly="" type="hidden" class="form-control" value="{{$admision_emergencia->nombre}}"
                                            name="nombre_beneficiario" id="nombre_beneficiario" />
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-12"><br />
                                      <label>SEXO</label>
                                      <input readonly="" tabindex="4" value="{{ $admision_emergencia->sexo }}" type="text" name="nomsexo"
                                        id="nomsexo" class="form-control">
                                    </div>


                                    <div class="col-md-3"><br />
                                      <label for="dni_beneficiario">TIP.BENEFICIARIO</label>
                                      <input readonly="" type="text" class="form-control"  name="tipo_beneficiario" id="tipo_beneficiario" />
                                    </div>
                                  </div>
                                </fieldset>   


                                <fieldset  style="margin-top: -8px !important;">
                                  <div class="row">  
                                    <div class="col-md-4"><br />
                                          <label>SITUACIÓN</label>
                                          <select class="form-control select2" style="width: 100%" name="id_situacion"
                                            id="id_situacion">
                                            <option value="">--SELECCIONAR--</option>
                                            @foreach($situacion as $item)
                                              @if($situacion_paciente!=null)
                                                @if ($situacion_paciente->id == $item->id)
                                                <option value="{{$item->id}}" selected>{{$item->descripcion}}</option>
                                                @else
                                                <option value="{{$item->id}}">{{$item->descripcion}}</option>  
                                                @endif
                                              @else
                                                <option value="{{$item->id}}">{{$item->descripcion}}</option>  
                                              @endif
                                            @endforeach
                                          </select>
                                        </div>  

                                        <div class="col-md-2"><br />
                                          <label>F.NACIMIENTO</label>
                                          <input readonly type="text" class="form-control" value="{{$admision_emergencia->fecha_nac}}" name="fnacimiento" id="fnacimiento" />
                                        </div> 
                                        <div class="col-md-4"><br />
                                          <label>EDAD</label> 
                                          <input readonly value="" type="text" name="edad_paciente" id="edad_paciente" class="form-control"> 
                                        </div>  
                                        <div class="col-md-2"><br />
                                          <label>TELÉFONO</label>
                                          <input type="text" class="form-control" id="telefono"
                                            name="telefono" value="{{old('telefono')}}" tabindex="" onkeypress="return esEntero(event);" maxlength="9" >
                                        </div>
                                </fieldset>


                                <fieldset  style="margin-top: -8px !important;">
                                  <div class="row">
                                    <div class="col-md-2"><br />
                                      <label>DEPARTAMENTO</label>
                                      <select class="form-control select2" style="width: 100%" name="Departamento" id="Departamento"
                                        onchange="getProvincia();">
                                        <option value="">---SELECCIONAR---</option>
                                        @foreach($departamento as $f)
                                          <option value="{{$f->id_departamento}}" {{ ($admision_emergencia->id_departamento_paciente == $f->id_departamento) ? 'selected': '' }} >{{$f->descripcion}}</option>
                                        @endforeach
                                      </select><br />
                                    </div>
                                    <div class="col-md-2"><br />
                                      <label>PROVINCIA</label>
                                      <select class="form-control select2" style="width: 100%" name="Provincia" id="Provincia"
                                        onchange="getDistrito();">
                                        <option value="">---SELECCIONAR---</option>
                                        @if($provincia!='')
                                          @foreach($provincia as $p)
                                            <option value="{{$p->id_provincia}}" {{ ($admision_emergencia->id_provincia_paciente == $p->id_provincia) ? 'selected': '' }} >{{$p->descripcion}}</option>
                                          @endforeach
                                        @endif
                                      </select><br />
                                    </div>
                                    <div class="col-md-2"><br />
                                      <label>DISTRITO</label>
                                      <select class="form-control select2" style="width: 100%" name="Distrito" id="Distrito">
                                        <option value="">---SELECCIONAR---</option>
                                        @if($distrito!='')
                                          @foreach($distrito as $d)
                                            <option value="{{$d->id_distrito}}" {{ ($admision_emergencia->id_distrito_paciente == $d->id_distrito) ? 'selected': '' }} >{{$d->descripcion}}</option>
                                          @endforeach
                                        @endif
                                      </select><br />
                                    </div> 
                                    <div class="col-md-6"><br />
                                      <label>DIRECCIÓN REFERENCIAL</label>
                                      <input type="text" class="form-control" @if(old('direccion_referencial') != null) value="{{old('direccion_referencial')}}"@else value="{{$admision_emergencia->domicilio}}"@endif
                                        id="direccion_referencial" name="direccion_referencial" tabindex="" 
                                        onkeyup="javascript:this.value = this.value.toUpperCase();"><br />
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                            </div> 

                            
                            <div class="col-lg-2 col-md-4 col-sm-12" style="text-align: center;  margin-top: 20px !important;">
                              <input id="foto_paciente" name="foto_paciente" type="hidden" value="{{ $admision_emergencia->foto }}">
                              @if($admision_emergencia->foto)
                              <img id="foto_imagen_paciente" src="" style="width: 72%;">
                              </img>
                              @endif
                              </input>
                            </div>
 
                            <div class="row">
                              <div class="col-lg-12 col-md-8 col-sm-12">
                                <div class="form-group"> 
                                  <fieldset id="seccion_datos_titular" name="seccion_datos_titular">  
                                      <hr style="margin-top: 0px !important; ">
                                      <div class="col-lg-2 col-md-6 col-sm-12" >
                                        <label>DOC.TITULAR</label>
                                        <input value="{{$admision_emergencia->nrodoctitular}}" type="text" name="documento_titular" id="documento_titular" class="form-control" readonly="">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12" >
                                        <label>NOM.TITULAR</label>
                                        <input value="{{$admision_emergencia->apepattitular}} {{$admision_emergencia->apemattitular}} {{$admision_emergencia->nomtitular}}" type="text" name="descripcion_titular" id="descripcion_titular" class="form-control" readonly="">
                                      </div>
                                      <div class="col-lg-2 col-md-6 col-sm-12" >
                                        <label>CIP.TITULAR</label>
                                        <input value="{{$admision_emergencia->ciptitular}}" type="text" name="cip_titular" id="cip_titular" class="form-control" readonly="">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12" >
                                        <label>GRADO.TITULAR</label>
                                        <input value="{{$admision_emergencia->gradotitular}}" type="text" name="grado_titular" id="grado_titular" class="form-control" readonly="">
                                      </div> 
                                  </fieldset>
                                </div> 
                              </div>
                            </div>
                            
                        </fieldset> 


                      </div>
                    </div>
                  </div>
                </div>
              </fieldset> 
              <!--#informacion de paciente-->
             
              <!--informacion de ingreso-->
              <fieldset>
                <div class="panel with-nav-tabs panel-default"
                  style="font-size: 11px; ">
                  <div class="panel-heading">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                          aria-expanded="true"><b><i class="fa fa-file-text" aria-hidden="true"></i>
                            INFORMACIÓN DEL INGRESO</b></a></li>
                    </ul>
                  </div>
                  <div class="panel-body" style="padding-bottom: 15px !important; margin-top: -20px !important;">
                    <div class="tab-content">

                      <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                        <fieldset>
                          <div class="row">
                            <div class="col-md-4"><br />
                              <label>FORMA INGRESO</label>
                              <input readonly type="text" class="form-control" value="{{ $admision_emergencia->descripcion_forma_ingreso }}">
                              <div style="display: none;">
                                <select class="form-control select2"name="id_forma_ingreso" id="id_forma_ingreso">
                                  <option value="{{$admision_emergencia->id_forma_ingreso}}">{{$admision_emergencia->descripcion_forma_ingreso}}</option>
                                </select>  
                              </div>  
                            </div>

                            <div class="col-md-4"><br />
                              <label>ESTADO INGRESO</label>
                              <input readonly type="text" class="form-control" value="{{ $admision_emergencia->descripcion_estado_ingreso }}">
                              <div style="display: none;">
                                <select class="form-control select2" name="id_estado_ingreso" id="id_estado_ingreso" readonly>
                                  <option value="{{$admision_emergencia->id_estado_ingreso}}" selected="selected">{{$admision_emergencia->descripcion_estado_ingreso}}</option>
                                </select>   
                              </div>
                            </div>
                            
                            <div class="col-md-4"><br />
                              <label>MOTIVO DE INGRESO</label> 
                              <input readonly type="text" class="form-control" value="{{ $admision_emergencia->descripcion_motivo_ingreso }}">
                              <div style="display: none;">
                                <select class="form-control select2" name="id_categoria" id="id_categoria" readonly> 
                                  <option value="{{$admision_emergencia->id_motivo_ingreso}}" selected="selected">{{$admision_emergencia->descripcion_motivo_ingreso}}</option> 
                                </select>
                              </div>
                            </div>

                          </div>
                        </fieldset>

                        
                        <fieldset  style="margin-top: -7px !important;">
                          <div class="row">
                            
                            <div class="col-md-2"><br />
                              <label>CONDUCIDO POR</label>
                              <select class="form-control input-sm" id="id_tipo_parentesco" name="id_tipo_parentesco" onchange="valiar_estado_parentesco()">
                                <option value="" selected="">---SELECCIONAR---</option>
                                @foreach($tipo_parentesco as $ti_pa) 
                                  <option value="{{$ti_pa->id}}" <?php if ($admision_emergencia->id_tipo_parentesco == $ti_pa->id) echo ' selected="selected";' ?> >{{$ti_pa->descripcion}}</option> 
                                @endforeach
                              </select>   
                            </div>


                            <div id="seccion_de_acompaniante" name="seccion_de_acompaniante">
                              <div class="col-md-3"><br /> 
                                <label for="tipo">TIPO DOC</label>
                                <select class="form-control input-sm" id="tipodoc_conducido" name="tipodoc_conducido" tabindex="1">
                                  <option @if(old('tipodoc_conducido') == '1') selected @endif value="1">DNI</option>
                                  <option @if(old('tipodoc_conducido') == '2') selected @endif value="2">CARNET DE EXTRANJERIA</option>
                                  <option @if(old('tipodoc_conducido') == '3') selected @endif value="3">PASAPORTE</option>
                                  <option @if(old('tipodoc_conducido') == '4') selected @endif value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                </select>
                              </div>
  
                              <div class="col-md-3"><br />
                                <label>N° DOCUMENTO</label>
                                <div class="input-group">
                                  <input type="hidden" name="dni_conducido" id="dni_conducido">
                                  <input type="text" id="dni_conducido_buscar" name="dni_conducido_buscar"
                                   class="form-control input-sm text-uppercase" maxlength="12" pattern="[0-9]*" maxlength="8"
                                   onkeypress="return esEntero(event);" tabindex="2" placeholder="NRO DOCUMENTO">
                                  <span class="input-group-btn">
                                    <button type="button" id="btnBuscar" onclick="getReniecByDni();" class="btn btn-info btn-sm" 
                                    style="margin-top: -1px !important;">Buscar</button>
                                  </span>
                                </div>
                              </div>
  
                              <div class="col-md-4"><br />
                                <label>NOMBRES</label>
                                <input readonly type="text" class="form-control" value="{{ $admision_emergencia->nombre_conducido }}" id="conducido" name="conducido"
                                  tabindex="" style="text-transform:uppercase;"
                                  onkeyup="javascript:this.value = this.value.toUpperCase();">
                              </div> 
                            </div>
 
                          </div>
                        </fieldset>

                        
                        <fieldset  style="margin-top: -10px !important;">
                          <div class="row">
                            <div class="col-md-3"  name="div_contacto" id="div_contacto"><br />
                              <label>TELÉFONO:</label>
                              <div class="input-group margin">
                                <input type="text" class="form-control"  value="@if(old('telfconducido')!= null){{trim(old('telfconducido'))}}@else{{$admision_emergencia->telf_conducido}}@endif"id="telfconducido"name="telfconducido"
                                style="text-transform:uppercase;" placeholder="telefono" maxlength="9" onkeypress="return esEntero(event);">
                                <span class="input-group-btn">
                                <button type="button" class="btn btn-success btn-flat btn-sm" id="copiar_telefono" name="copiar_telefono" style="height: 28px;"> 
                                  <i class="fa fa-check" aria-hidden="true"></i></button>
                                </span>
                              </div>
                            </div>

                            <div class="col-md-9"><br />
                              <label>OBSERVACIÓN:</label>
                              <input type="text" class="form-control" value="{{ $admision_emergencia->observacion_admision }}" id="observacion_admision"
                              name="observacion_admision" tabindex="" style="text-transform:uppercase;" placeholder="observacion" maxlength="500">
                            </div>

                            <div class="col-md-4" id="div_oficial_guardia" name="div_oficial_guardia"><br />
                              <label>OFICIAL DE GUARDIA</label>
                              <select class="form-control select2" style="width: 100%" name="oficial_guardia"
                                id="oficial_guardia"> 
                                <option value="0" selected="">---SELECCIONAR---</option>
                                @foreach($personal_guardia as $f)
                                  <option value="{{$f->id}}">{{$f->nombre}} {{$f->paterno}} {{$f->materno}}</option>
                                @endforeach
                              </select>
                            </div>
                            
                            <div class="col-md-3" id="div_numero_boleta" name="div_numero_boleta"><br />
                              <label># DE BOLETA:</label>
                              <input type="text" class="form-control" id="numero_boleta" name="numero_boleta" style="text-transform:uppercase;" placeholder="EB01-122" >
                            </div>

                          </div>
                        </fieldset> 

                      </div>

                    </div>
                  </div>
                </div>
              </fieldset>
 
              <!--#informacion de ingreso-->

              

              <!--informacion de violencia-->
              <fieldset  id="muestra-datos-violencia" 
                @if ($data_violencia->id_emergencia_violencia == null) style="display: none;" @endif>
                <div class="panel with-nav-tabs panel-default"
                  style="font-size: 11px; ">
                  <div class="panel-heading">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                          aria-expanded="true"><b><i class="fa fa-file-text" aria-hidden="true"></i>
                            INFORMACIÓN DE VIOLENCIA</b></a></li>
                    </ul>
                  </div>
                  <div class="panel-body" style="padding-top: 0px !important;">
                    <div class="tab-content">
                      <fieldset>
                        <div class="row">
                          <div class="col-md-3"><br />
                            <label>TIPO DE VIOLENCIA</label>
                              <select class="form-control select2" style="width: 100%" name="id_violencia"
                                id="id_violencia" onchange="obtener_Tipo_Violencia();"/> 
                              <option value="">---SELECCIONAR---</option>
                              @foreach($violencia as $item)
                              @if ($data_violencia->id_violencia == $item->id)
                              <option value="{{$item->id}}" selected>{{$item->descripcion}}</option>
                              @else
                              <option value="{{$item->id}}">{{$item->descripcion}}</option>  
                              @endif                            
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-3"><br />
                            <label>MODALIDAD</label>
                            <select class="form-control select2" style="width: 100%" name="id_tipo_violencia"
                              id="id_tipo_violencia">
                              <option value="">---SELECCIONAR---</option>
                              @if($modoviolencia!='')
                                @foreach($modoviolencia as $item2)
                                @if ($data_violencia->id_modalidad == $item2->id_tipo_violencia)
                                <option value="{{$item2->id_tipo_violencia}}" selected>{{$item2->descripcion}}</option>
                                @else
                                <option value="{{$item2->id_tipo_violencia}}">{{$item2->descripcion}}</option>  
                                @endif
                                @endforeach
                              @endif
                            </select>
                          </div>
                          <div id="seccion_comisaria" name="seccion_comisaria">
                            <div class="col-md-6"><br />
                              <label>COMISARIA</label>
                              <select class="form-control select2" style="width: 100%" name="id_comisaria"
                                id="id_comisaria"  onchange="getobtener_ubigeo_by_comisaria();">
                                <option value="">---SELECCIONAR---</option>
                                @foreach($comisaria as $item)
                                @if ($data_violencia->id_comisaria == $item->id)
                                <option value="{{$item->id}}" selected>{{$item->comisaria}}  <b>({{$item->region_pol}})</b></option>
                                @else
                                <option value="{{$item->id}}">{{$item->comisaria}}  <b>({{$item->region_pol}})</b></option>  
                                @endif
                                  @endforeach
                              </select> 
                            </div> 
                          </div>
                        </div>
                      </fieldset>


                      <fieldset style="margin-top: -10px !important;">
                        <div class="row">
                          <div class="col-md-2"><br />
                            <label>FEC. ACCIDENTE</label>
                            <input type="text" class="form-control datepicker" value="<?php echo date("d/m/Y",
                              strtotime($fechaServidor)); ?>" name="fecha_accidente" id="fecha_accidente" tabindex=""
                            autocomplete="off"/>
                          </div>
                          <div class="col-md-2"><br />
                            <label>HORA ACCIDENTE</label>
                            <input type="text" class="form-control" value="<?php echo date("H:i:s"); ?>"
                            name="hora_accidente" id="hora_accidente" tabindex="1" data-date-end-date="0d"
                            autocomplete="off"/>
                          </div>
                          <div id="seccion_comisaria2" name="seccion_comisaria2">
                            <div class="col-md-2"><br />
                              <label>DEPARTAMENTO</label>
                              <select class="form-control select2" style="width: 100%" name="DepartamentoAc"
                                id="DepartamentoAc" onchange="getProvinciaAc();">
                                <option value="">---SELECCIONAR---</option>
                                @foreach($departamento_violencia as $f)
                                  <option value="{{$f->id_departamento}}" {{ ($data_violencia->id_dpto == $f->id_departamento) ? 'selected': '' }} >{{$f->descripcion}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-3"><br />
                              <label>PROVINCIA</label>
                              <select class="form-control select2" style="width: 100%" name="ProvinciaAc" id="ProvinciaAc"
                                onchange="getDistritoAc();">
                                <option value="">---SELECCIONAR---</option>
                                @if($provincia_violencia!='')
                                  @foreach($provincia_violencia as $p)
                                    <option value="{{$p->id_provincia}}" {{ ($data_violencia->id_prov == $p->id_provincia) ? 'selected': '' }} >{{$p->descripcion}}</option>
                                  @endforeach
                                @endif
                              </select>
                            </div>
                            <div class="col-md-3"><br />
                              <label>DISTRITO</label>
                              <select class="form-control select2" style="width: 100%" name="DistritoAc" id="DistritoAc">
                                <option value="">---SELECCIONAR---</option>
                                @if($distrito_violencia!='')
                                  @foreach($distrito_violencia as $d)
                                    <option value="{{$d->id_distrito}}" {{ ($data_violencia->id_dist == $d->id_distrito) ? 'selected': '' }} >{{$d->descripcion}}</option>
                                  @endforeach
                                @endif
                              </select>
                            </div>
                          </div>
                        </div> 
                      </fieldset>


                      <fieldset style="margin-top: -10px !important;"> 
                        <div class="row">
                          <div class="col-md-6"><br />
                            <label>LUGAR ACCIDENTE</label>
                            <input type="text" class="form-control" value="{{ $data_violencia->lugar_accidente }}" id="lugar_accidente" name="lugar_accidente"
                              style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                          </div>
                          <div id="seccion_comisaria3" name="seccion_comisaria3">
                            <div class="col-md-6"><br />
                              <label>UNIDAD POLICIAL</label>
                              <select class="form-control select2" style="width: 100%" name="id_unidad" id="id_unidad">
                                <option value="">---SELECCIONAR---</option>
                                @foreach($unidad as $item)
                                <option value="{{$item->id}}" 
                                  {{ ($data_violencia->id_unidad == $item->id) ? 'selected' : '' }} >
                                  {{$item->descripcion_larga}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      
                  </div>
                </div>
              </div>
            </fieldset> 
              <!--#informacion de violencia-->

              <!--informacion de evacuados-->
              <fieldset id="muestra-datos-evacuado"
              @if ($data_evacuado->id_evacuado == null) style="display: none;" @endif >
              <div class="panel with-nav-tabs panel-default"
                style="font-size: 11px; ">
                <div class="panel-heading">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                        aria-expanded="true"><b><i class="fa fa-file-text" aria-hidden="true"></i>
                          INFORMACIÓN DEL EVACUADO</b></a></li>
                  </ul>
                </div>
                <div class="panel-body" style="padding-top: 0px !important;">
                  <div class="tab-content">
                      
                    <fieldset>
                      <div class="row">
                        <div class="col-md-5"><br />
                          <label>ESTABLECIMIENTO</label>
                          <select class="form-control select2" style="width: 100%;" name="id_unidad_policial"
                            id="id_unidad_policial" onchange="getobtener_departamento_by_unidad_policial();">
                            <option value="">---SELECCIONAR---</option> 
                            @foreach($ipress as $item)
                              @if ($data_evacuado->unidad_policial == $item->id)
                                <option value="{{$item->id}}" @if(old('id_unidad_policial') == null) selected @endif>{{$item->nombre}}</option>
                              @else
                                @if($nombre_establecimiento != $item->nombre) 
                                  <option @if(old('id_unidad_policial') == $item->id) selected @endif value="{{$item->id}}">{{$item->nombre}}</option>  
                                @endif
                              @endif 
                            @endforeach
                          </select>
                        </div>

                        <div class="col-md-2"><br />
                          <label>DEPARTAMENTO</label>
                          <select class="form-control select2" style="width: 100%" name="id_departamento_evac"
                            id="id_departamento_evac">
                            <option value="">---SELECCIONAR---</option>
                            @foreach($departamento as $f)
                            @if ($data_evacuado->id_departamento == $f->id_departamento)
                            <option value="{{$f->id_departamento}}" @if(old('id_departamento_evac') == null) selected @endif>{{$f->descripcion}}</option>    
                            @else
                            <option @if(old('id_departamento_evac') == $f->id_departamento) selected @endif value="{{$f->id_departamento}}">{{$f->descripcion}}</option>  
                            @endif
                            @endforeach
                          </select>
                        </div>

                        
                        <div class="col-md-2"><br />
                          <label>NUMERO DE OFICIO</label>
                          <input type="text" class="form-control" value="@if(old('numero_oficio') != null){{old('numero_oficio')}}@else{{ $data_evacuado->numero_oficio }}@endif" id="numero_oficio" name="numero_oficio"
                            tabindex="" style="text-transform:uppercase;"
                            onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>


                      </div>
                    </fieldset>
                    <fieldset style="padding-top: 8px;">
                      <div class="row">
                        <div class="col-md-12"> 
                          <label>OBSERVACION EVACUADO</label>
                          <input type="text" class="form-control" value="@if(old('observacion_evac') != null){{old('observacion_evac')}}@else{{ $data_evacuado->observacion }}@endif" id="observacion_evac" name="observacion_evac"
                            tabindex="" style="text-transform:uppercase;"
                            onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12"><br /> 
                          {{ $data_evacuado->diagnostico }} 
                          <button type="button" id="addDiagnostico" class="btn btn-primary"
                          data-toggle="modal" data-target="#addClassModal">Agregar Diagnostico</button> 

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
                  </div>
                </div>
              </div>
            </fieldset> 
 
              <!--#informacion de evacuados-->

              <div class="form-group" style="display: flex; justify-content: flex-end; gap: 1rem;">
                <div>
                  <a href="/all_triajes_atendidas_by_emergencia" id="cancelar_registro_admision" name="cancelar_registro_admision"
                    class="btn btn-danger" tabindex="99922">CANCELAR</a>
                </div>
                <div>
                  <input 
                    id="btnReg" 
                    class="btn btn-success" 
                    type="button" 
                    tabindex="99921"
                    onclick="validateFrm()" 
                    value="GUARDAR"
                  />
                    <input type="hidden" name="hd_action" value="actualizar">
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


  $('body').on('click', '#cancelar_registro_admision', function () { 
    var id_ticket_=$('#id_ticket_').val(); 
    if(id_ticket_ != null){
      llamada_triaje_amergencia(id_ticket_, 2,0,0,'cancelar');
    }
  });

  
 
 function llamada_triaje_amergencia(id_ticket, id_estado_tv, estado_llamada, cantidad_llamada,tipo_proceso){ 
     $.ajax({
       url: '/llamada_triaje_amergencia/' + id_ticket+'/'+ id_estado_tv+'/'+ estado_llamada+'/'+ cantidad_llamada,
       dataType: "json",
       success: function(result){ 
         if(result.sw == true){  
            console.log('llamada_triaje_amergencia EJECUTADO');
         }
       }
     });
   }
</script>



@include('frontend.includes.footer')
@endsection