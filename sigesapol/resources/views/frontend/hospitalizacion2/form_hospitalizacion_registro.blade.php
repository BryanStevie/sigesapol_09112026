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

</style>

<script>

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
    
    
    $tur = $("#frmReceta").find("#turno");
    $tur.select2({
        width: 'resolve' // need to override the changed default
    });
    
  jQuery(document).on('focus', '.select2', function() {
      jQuery(this).siblings('select').select2('open');
  });

  $id_sala = $("#frmReceta").find("#id_sala");
  $id_sala.select2({
        width: 'resolve' // need to override the changed default
    });

  $id_cama = $("#frmReceta").find("#id_cama");
  $id_cama.select2({
        width: 'resolve' // need to override the changed default
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

  
  
    
     
    //-------------------AGREGAR PRODUCTOS--------------------------------------
    $(document).ready(function () {
    
    __autocomplete__AjaxBusqueda();
    
        var counter = 1;

        $('#example tbody').on('click', 'button.deleteFila', function () {
            var obj = $(this);
                obj.parent().parent().remove();
        
        });
        
        $('#addRow').on('click', function () {
      AddFila();
      
        });

        
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
      bootbox.alert('Codigo Cie10 que digitó no es correcto, intente nuevamente');
            $('#frmReceta').find("#cies").val("");
            $('#frmReceta').find("#cies").focus();
            $select.html('');
        }
    }
    //-------------------------------------------------------------------------
 
    $("#nro_historia").on("keydown", function (e) {
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        nro_documento=$('#frmReceta').find("#dni_beneficiario").val();
        nro_historia=$('#frmReceta').find("#nro_historia").val();
        var code = e.keyCode || e.which;
        if (code == '9' || code == '13') {
            validar_hc_duplicada(id_farmacia, nro_documento, nro_historia);
        }
    });

    //-------------------------------------------------------------
    
    
    function validateFrm(){
        validate_otros();        
    }
    
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
    function validate_otros(){
         
        if ($('#frmReceta').find("#fecha_atencion").val() == "") {
            bootbox.alert("Falta ingresar la fecha de ingreso");
            $('#btnReg').prop('disabled', false);
            $('#fecha_atencion').focus();
            return false;
        }
        if ($('#frmReceta').find("#id_consultorio").val() == "") {
            bootbox.alert("Falta seleccionar la Sala");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmReceta').find("#turno").val() == "") {
            bootbox.alert("Falta seleccionar el Turno");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        /*if ($('#frmReceta').find("#tarifa").val() == "") {
            bootbox.alert("Falta seleccionar la Tarifa");           
            $('#btnReg').prop('disabled', false);
            $('#tarifa').focus();
            return false;
        }*/
        if ($('#frmReceta').find("#id_sala").val() == "") {
            bootbox.alert("Falta seleccionar la Sala");           
            $('#btnReg').prop('disabled', false);
            $('#id_sala').focus();
            return false;
        }
          
        
        if ($('#frmReceta').find("#id_cama").val() == "") {
            bootbox.alert("Falta seleccionar la Cama");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmReceta').find("#nro_historia").val() == "") {
            bootbox.alert("Falta ingresar la Historia Clínica");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmReceta').find("#grado").val() == "") {
            bootbox.alert("Falta seleccionar el grado");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmReceta').find("#id_cama").val() == "") {
            bootbox.alert("Falta ingresar el diagnostico");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        /*if ($('#frmReceta').find("#id_diagnostico").val() == "") {
            bootbox.alert("Falta seleccionar el diagnostico (CIE10)");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        */
        if ($('#frmReceta').find("#fecha_expedicion").val() == "") {
            bootbox.alert("Falta seleccionar la fecha de ingreso del paciente");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        
        submitFrm();
        return false;
    };
    
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
                  $("#frmReceta").submit();
              } else {
                  $('#btnReg').prop('disabled', false);
              }
          }
      });
    }
    //--------------------------------------------------------------------------
  
  
  
    $(document).ready(function () {
      //getSala();
      /*var nivel =  document.getElementById('nivel').value;
      if(nivel=="2"){
        getCama();
      }else{
         getCama2();
      }*/
      
     
        var idgrado = "<?php echo trim($id_grado);?>";
        document.getElementById('grado').value=idgrado;

        if(document.getElementById('grado').value==""){
          const option = document.createElement('option');
          option.value = idgrado;
          option.text = idgrado;                   
          document.getElementById('grado').appendChild(option);
          document.getElementById('grado').value=idgrado;
        }
 
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
            
            /*$("#id_consultorio").val('').trigger("change");
            $("#turno").val('').trigger("change");
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");*/
           
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


  function getSala(){
      var id_consultorio = $('#id_consultorio').val();  
      var id_ipress     = $('#id_ipress').val();  
      $.ajax({
        url: '/obtener_cama_disponible_sala/'+ id_ipress,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          var id_consultorio_hosp= "<?php echo $id_consultorio_hosp ?>";

          $(result).each(function (ii, oo) {
            if(id_consultorio==oo.id_consultorio){
              newOption += "<option selected value='"+oo.id_consultorio+"'>"+oo.nombre+"</option>";
            }else if(id_consultorio_hosp==oo.id_consultorio){
              newOption += "<option selected value='"+oo.id_consultorio+"'>"+oo.nombre+"</option>";
            }else{
              newOption += "<option value='"+oo.id_consultorio+"'>"+oo.nombre+"</option>";  
            }
          });                 
          $('#id_sala').html(newOption);
        }
      });

      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
            //$('#id_medico').html(newOption);
           // $("#id_medico").val('').trigger("change");
            
       if($('#turno').val()=="") return false;
        
      var id_ipress     = $('#id_ipress').val();
      var id_consultorio  = $('#id_consultorio').val();
      var fecha_admision  = $('#fecha_atencion').val();     
      
      
      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
      $('#id_cama').html(newOption);
      $("#id_cama").val('').trigger("change");
      
      if($('#id_consultorio').val()=="") return false;

      var id_consultorio= $('#id_consultorio').val();
      
      var id_cama = "<?php echo $id_cama ?>";
      $.ajax({
        url: '/obtener_cama_por_idcuarto_todos/' + id_consultorio,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            //
            if(id_cama==oo.id){
              newOption += "<option selected value='"+oo.id+"'>"+oo.nombre_cuarto+" -  "+oo.nombre_cama+"</option>";
            }else if(oo.estado == "0"){
              newOption += "<option value='"+oo.id+"'>"+oo.nombre_cuarto+" -  "+oo.nombre_cama+"</option>"; 
            }
          });                
          $('#id_cama').html(newOption);
        
        }
      });      
    }
  
  function getCama(){

    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";
    $('#id_cama').html(newOption);
    $("#id_cama").val('').trigger("change");
    
    if($('#id_consultorio').val()=="") return false;

    var id_consultorio= $('#id_consultorio').val();
    
    $.ajax({
      url: '/obtener_cama_por_idcuarto/' + id_consultorio,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"'>"+oo.nombre_cuarto+" -  "+oo.nombre_cama+"</option>";
        });                
        $('#id_cama').html(newOption);
      
      }
    });
        
  }

  function getCama2(){

    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";
    $('#id_cama').html(newOption);
    $("#id_cama").val('').trigger("change");    
    
    if($('#id_consultorio').val()=="") return false;

    var id_consultorio= $('#id_sala').val();
    //alert(id_consultorio);
    $.ajax({
      url: '/obtener_cama_por_idcuarto/' + id_consultorio,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          //newOption += "<option value='"+oo.id+"'>"+oo.nombre_cuarto+" -  "+oo.nombre_cama+"</option>";
          if(id_cama==oo.id){
             newOption += "<option selected value='"+oo.id+"'>"+oo.nombre_cuarto+" -  "+oo.nombre_cama+"</option>";
            }
            else
            {
              newOption += "<option value='"+oo.id+"'>"+oo.nombre_cuarto+" -  "+oo.nombre_cama+"</option>";  
            }
        });                
        $('#id_cama').html(newOption);
      
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
}

.form-group{
    margin-bottom: 10px;
}
</style>
@endsection
@section('content')

<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE REGISTRO DE PACIENTES A HOSPITALIZACIÓN</h3>
          </div>
          <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{ route('registrar_hospitalizaciones')}}" id="frmReceta" autocomplete="off">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <div class="form-group">
                    <div class="col-md-4 text-center">
                        <img src="../../images/logo_sanidad.png" width="50" height="auto">
                        <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                        <input type="hidden" value="dni" name="tipo">
                        <input type="hidden" name="valid_nro_receta" id="valid_nro_receta" value="0"/>
                        <input type="hidden" value="<?php echo $id_ipress ?>" name="id_ipress" id="id_ipress">
                        <input type="hidden" value="<?php echo $nivel ?>" name="nivel" id="nivel">
                        <input type="hidden" value="<?php echo $id_emergencia ?>" name="id_emergencia" id="id_emergencia"> 
                                                   
                    </div>
                    <div class="col-md-4" style="text-align: center">
                          <label>{{$establecimiento->nombre}}</label><br>
                          <?php if(isset($id_hospitalizacion)){  ?>
                          <label style="font-size: 24px">MODIFICAR HOSPITALIZACIÓN</label><br>
                           <input type="hidden" value="<?php echo $id_hospitalizacion ?>" name="id_hospitalizacion" id="id_hospitalizacion">
                          <?php }else {  ?>
                          <label style="font-size: 24px">ADMISIÓN A HOSPITALIZACIÓN</label><br>
                          <?php } ?>
                    </div>
                    <div class="col-md-4 text-center">
                          <img src="../../images/logo_sanidad.png" width="50" height="auto">
                    </div>
                </div>
                <fieldset>
                  <legend>
                    <b>DATOS DE LA ATENCI&Oacute;N</b>
                  </legend>
                  <div>
                    <div class="form-group">
                      <div class="col-md-3">
                        <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                        <p>HOSPITALIZACIÓN</p>
                      </div>
                      <div class="col-md-1">
                      </div>
                      <div class="col-md-2">
                        <label for="dni_beneficiario">FECHA DE INGRESO</label>
                        <input type="text" class="form-control datepicker" value="{{$fecha_atencion}}" name="fecha_atencion" id="fecha_atencion" tabindex="1"  data-date-end-date="0d" autocomplete="off"/>
                      </div>
                      <div class="col-md-1">
                      </div>
                      <div class="col-md-2">
                        <label>HORA DE INGRESO &nbsp;</label>
                        <input type="time" name="hora" id="hora" value="{{$hora_ingreso}}" class="form-control" tabindex="2" required>
                      </div>
                      <div class="col-md-1">
                      </div>
                      <div class="col-md-2">
                        <label>N° HISTORIA CLINICA</label>
                        <p>{{$asegurado->nro_historia}}</p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-6">
                        <label for="tipo">NOMBRE DE LA IPRESS</label><br/>
                        <label style="font-size: 24px">{{$establecimiento->nombre}}</label>
                      </div>
                    </div>
                  </div>
                </fieldset>
              </div>
              
              <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1default" data-toggle="tab" style="font-size:12px">INFORMACIÓN DEL PACIENTE</a></li>
                    <!--li><a href="#tab2default" data-toggle="tab" style="font-size:12px">TITULAR</a></li-->
                    <li><a href="#tab3default" data-toggle="tab" style="font-size:12px">MEDICO PROFESIONAL</a></li>
                    <li><a href="#tab4default" data-toggle="tab" style="font-size:12px">INGRESO</a></li>
                  </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                    <div class="panel-title text-center">
                                        <b>INFORMACIÓN DEL PACIENTE</b>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <fieldset>
                                                <div class="panel-body" id="collapseprestacionespaciente">
                                                    <div class="form-group">
                                                        <div class="col-md-3">
                                                            <label for="tipo">TIPO DE DOCUMENTO</label>
                                                            <p>{{$asegurado->tipo_doc_ident}}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="dni_beneficiario">N° DE DOCUMENTO</label>
                                                            <p>{{$asegurado->nro_doc_ident}}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="dni_beneficiario">SEXO</label>
                                                            <p>{{$asegurado->sexo}}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>FECHA DE NACIMIENTO</label>
                                                            <p>{{$asegurado->fecha_nac}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-3">
                                                            <label>APELLIDO PATERNO</label>
                                                            <p>{{$asegurado->paterno}}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>APELLIDO MATERNO</label>
                                                            <p>{{$asegurado->materno}}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>NOMBRES</label>
                                                            <p>{{$asegurado->nombre}}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>PARENTESCO &nbsp;</label>
                                                            <p>{{$tipo_beneficiario}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-3">
                                                            <label>GRADO &nbsp;</label>
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>TELÉFONO &nbsp;</label>
                                                            <p>{{$asegurado->telefono}}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>DIRECCIÓN &nbsp;</label>
                                                            <p>{{$asegurado->domicilio}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <!--div class="tab-pane fade in" id="tab2default">
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>TITULAR</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <div id='divAtencion'></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div-->
                    <div class="tab-pane fade in" id="tab3default">
                        <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                    <div class="panel-title text-center">
                                        <b>INFORMACIÓN DEL PROFESIONAL QUE ORDENA HOSPITALIZACIÓN</b>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <fieldset>
                                                <div class="panel-body" id="collapseprestacionespaciente">
                                                    <div class="form-group">
                                                        <div class="col-md-3">
                                                            <label for="tipo">TIPO DE DOCUMENTO</label>
                                                            <p>DNI</p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="dni_beneficiario">N° DE DOCUMENTO</label>
                                                            <p>{{$medico_dni}}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="dni_beneficiario">MEDICO</label>
                                                            <p>{{$medico_egreso}}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="dni_beneficiario">ESPECIALIDAD</label>
                                                            <p>{{$especializacion}} , {{$profesion}}</p>
                                                        </div>
                                                    </div>   
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> 
                    </div>
                    <div class="tab-pane fade in" id="tab4default">
                        <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                    <div class="panel-title text-center">
                                        <b>REGISTRO DE INGRESO</b>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <fieldset>
                                                <div class="panel-body" id="collapseprestacionespaciente">
                                                    <div class="form-group">
                                                        <div class="col-md-3">
                                                            <label for="tipo">ORIGEN</label>
                                                            <p>EMERGENCIA</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="dni_beneficiario">TÓPICO</label>
                                                            <p>{{ $descripcion_triaje}}</p>
                                                        </div>
                                                        <input type="hidden" class="form-control" value="2" name="origen" id="origen" readonly=""/>
                                                        
                                                        
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                  </div>
                </div>
              </div>
                <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center">
                                <b>REGISTRO DE UBICACIÓN Y DIAGNÓSTICO</b>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <fieldset>
                                        <div class="panel-body" id="collapseprestacionespaciente">
                                            <div class="form-group">
                                                @if($nivel==2)
                                                    <div class="col-md-3">
                                                        <label for="dni_beneficiario">SALA</label>
                                                        <select id="id_consultorio" name="id_consultorio" class="form-control" onchange="getCama()" style="width: 100%">
                                                            <option value="">--Seleccionar--</option>
                                                            @foreach($consultorio as $e)
                                                              <option value="{{$e->id}}" >{{$e->nombre}}</option>
                                                            @endforeach  
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>TURNO</label>
                                                          <?php 
                                                            $turno="";
                                                            if(isset($turno)){
                                                              $turno=$turno;
                                                            } 
                                                          ?>
                                                        <select class="form-control" name="turno" id="turno"  tabindex="3" style="width: 100%">
                                                          <option value="">-Seleccionar-</option>
                                                          <option value="MD" <?php if($turno=="MD"){?>selected <?php } ?> >MADRUGADA</option>
                                                          <option value="M" <?php if($turno=="M"){?>selected <?php } ?>>MAÑANA</option>
                                                          <option value="T" <?php if($turno=="T"){?>selected <?php } ?>>TARDE</option>
                                                          <option value="N" <?php if($turno=="N"){?>selected <?php } ?>>NOCHE</option>
                                                        </select>
                                                    </div>
                                                @else  
                                                    <div class="col-md-3">
                                                        <label for="tipo">ESPECIALIDAD</label><br/>
                                                        @foreach($consultorio as $e)
                                                            <?php if($e->id==$id_consultorio_destino){ echo $e->nombre;  } ?>
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-1">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="dni_beneficiario">SALA</label>
                                                        <select id="id_sala" name="id_sala" class="form-control" onchange="getCama2()" style="width: 100%">
                                                            <option value="">--Seleccionar--</option>
                                                            @foreach($sala as $s)
                                                                <option value="{{$s->id_consultorio}}" <?php if($s->id_consultorio==$id_sala_reg){?>selected <?php } ?> >{{$s->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                    </div>
                                                @endif
                                                @if($opcion>0)
                                                <div class="col-md-3">
                                                    <label for="dni_beneficiario">CAMA</label>
                                                    <select id="id_cama" name="id_cama" class="form-control" style="width: 100%">
                                                        <option value="">--Seleccionar--</option>
                                                        
                                                            @foreach($cuarto as $cu)
                                                                <option value="{{$cu->id}}" <?php if($cu->id==$id_cuarto){?>selected <?php } ?> >{{$cu->nombre_cuarto}}  -  {{$cu->nombre_cama}}</option>
                                                            @endforeach
                                                        
                                                    </select>
                                                </div>
                                                @else
                                                <div class="col-md-3">
                                                    <label for="dni_beneficiario">CAMA</label>
                                                    <select id="id_cama" name="id_cama" class="form-control" style="width: 100%">
                                                        <option value="">--Seleccionar--</option>
                                                        
                                                    </select>
                                                </div>
                                                @endif
                                                <div class="col-md-1">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label>DIAGNOSTICO</label>
                                                    <?php if (isset($diagnostico)){ ?>
                                                        <div class="panel panel-info panel-bordered panel-sm">
                                 
                                                            <div class="panel-body">

                                                                <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" style="margin-top:5px;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="15%">Codigo</th>
                                                                            <th width="50%">Nombre</th>
                                                                            <th width="15%">Tipo</th>
                                                                            <th width="15%">Clasificación</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tfoot>
                                                                    </tfoot>
                                                                    <tbody>
                                                                        <?php 
                                                                         $id_diagnostico_inicial_actual="";
                                                                        foreach($diagnostico as $row):
                                                                          $tipo_diagnostico = ""; 
                                                                          if($row->id_tipo_diagnostico==1)$tipo_diagnostico = "PRESUNTIVO";
                                                                          if($row->id_tipo_diagnostico==2)$tipo_diagnostico = "DEFINITIVO";
                                                                          if($row->id_tipo_diagnostico==3)$tipo_diagnostico = "REITERATIVO";

                                                                          $clase_diagnostico="";
                                                                          if($row->id_clasificacion_diagnosticos==1){
                                                                            $clase_diagnostico = "Atencion";
                                                                          }else if($row->id_clasificacion_diagnosticos==2){
                                                                            $clase_diagnostico = "Ingreso";
                                                                          }else if($row->id_clasificacion_diagnosticos==3){
                                                                            $clase_diagnostico = "Egreso";
                                                                            $id_diagnostico_inicial_actual=$row->id_diagnostico;
                                                                          }else if($row->id_clasificacion_diagnosticos==4){
                                                                            $clase_diagnostico = "Mortalidad";
                                                                          }else{
                                                                            $clase_diagnostico = "Atencion";
                                                                          }

                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $row->codigo?></td>
                                                                            <td><?php echo $row->nombre?></td>
                                                                            <td><?php echo $tipo_diagnostico?></td>
                                                                            <td><?php echo $clase_diagnostico?></td>
                                                                        </tr>
                                                                    <?php endforeach;?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="id_diagnostico" name="id_diagnostico" value="{{ $id_diagnostico_inicial }}">
                                                        
                                                        <?php } else if (isset($id_diagnostico_inicial)){ ?>
                                                            <div class="panel panel-info panel-bordered panel-sm">
                                 
                                                            <div class="panel-body">

                                                                <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" style="margin-top:5px;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="15%">Codigo</th>
                                                                            <th width="50%">Nombre</th>
                                                                            <th width="15%">Tipo</th>
                                                                            <th width="15%">Clasificación</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tfoot>
                                                                    </tfoot>
                                                                    <tbody>
                                                                        <?php 
                                                                         $id_diagnostico_inicial_actual="";
                                                                        foreach($diagnostico as $row):
                                                                          $tipo_diagnostico = ""; 
                                                                          if($row->id_tipo_diagnostico==1)$tipo_diagnostico = "PRESUNTIVO";
                                                                          if($row->id_tipo_diagnostico==2)$tipo_diagnostico = "DEFINITIVO";
                                                                          if($row->id_tipo_diagnostico==3)$tipo_diagnostico = "REITERATIVO";

                                                                          $clase_diagnostico="";
                                                                          if($row->id_clasificacion_diagnosticos==1){
                                                                            $clase_diagnostico = "Atencion";
                                                                          }else if($row->id_clasificacion_diagnosticos==2){
                                                                            $clase_diagnostico = "Ingreso";
                                                                          }else if($row->id_clasificacion_diagnosticos==3){
                                                                            $clase_diagnostico = "Egreso";
                                                                            $id_diagnostico_inicial_actual=$row->id_diagnostico;
                                                                          }else if($row->id_clasificacion_diagnosticos==4){
                                                                            $clase_diagnostico = "Mortalidad";
                                                                          }else{
                                                                            $clase_diagnostico = "Atencion";
                                                                          }

                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $row->codigo?></td>
                                                                            <td><?php echo $row->nombre?></td>
                                                                            <td><?php echo $tipo_diagnostico?></td>
                                                                            <td><?php echo $clase_diagnostico?></td>
                                                                        </tr>
                                                                    <?php endforeach;?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <input type="text"  class="form-control typeahead tt-query" id="nombre_diagnostico" name="nombre_diagnostico" tabindex="10" value="{{ $nombre_diagnostico_inicial }}">
                                                        <input type="hidden" class="form-control typeahead tt-query" id="id_diagnostico" name="id_diagnostico2" value="{{ $id_diagnostico_inicial }}">
                                                        <?php } else { ?>

                                                        <input type="text"  class="form-control typeahead tt-query" id="nombre_diagnostico" name="nombre_diagnostico" tabindex="10">
                                                        <input type="hidden" class="form-control typeahead tt-query" id="id_diagnostico" name="id_diagnostico3" value="{{ $id_diagnostico_inicial }}">
                                                        <?php }?> 
                                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($asegurado->nombre)) ?>" name="nombre_beneficiario">
                                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($asegurado->paterno)) ?>" name="paterno_beneficiario">
                                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($asegurado->materno)) ?>" name="materno_beneficiario">
                                                        <input type="hidden" class="form-control" value="{{$asegurado->edadafiliado}}" name="edadafiliado" id="edadafiliado" readonly=""/>
                                                        <input type="hidden" class="form-control" value="{{$asegurado->sexo}}" name="nomsexo" id="nomsexo" readonly=""/>
                                                        <input type="hidden" class="form-control" value="{{$asegurado->tipo_doc_ident}}" name="tipdoc_beneficiario" id="tipdoc_beneficiario" readonly=""/>
                                                        <input type="hidden" class="form-control" value="{{$asegurado->id_tipo_doc_ident}}" name="id_tipo_doc_ident" id="id_tipo_doc_ident" readonly=""/>
                                                        <input type="hidden" class="form-control" value="2" name="origen" id="origen" readonly=""/>
                                                        <input type="hidden" class="form-control" value="{{$asegurado->cip}}" name="cip_beneficiario" id="cip_beneficiario" readonly=""/>
                                                        <input type="hidden" class="form-control" value="{{$asegurado->nro_doc_ident}}" name="dni_beneficiario" id="dni_beneficiario" readonly=""/>
                                                        <input class="form-control" type="hidden" value="{{ $id_triaje }}" id="id_servicio" name="id_servicio" style="width: 100%" readonly="" />
                                                        <input type="hidden" value="{{$nivel}}" name="nivel" id="nivel" class="form-control" readonly="readonly" >
                                                        <input type="hidden" value="{{$asegurado->fecha_nac}}" name="fecha_nacimiento_beneficiario" class="form-control" readonly="readonly" >
                                                        <input type="hidden" id="nro_historia" name="nro_historia" value="{{ $asegurado->nro_historia }}">
                                                        <input type="hidden" id="tipo_beneficiario" name="tipo_beneficiario" value="{{ $tipo_beneficiario }}">
                                                        <input type="hidden" id="tipo_beneficiario" name="tipo_beneficiario" value="{{ $tipo_beneficiario }}">
                                                        <input type="hidden" id="id_medico" name="id_medico" value="{{ $id_medico_egreso }}">
                                                        <input class="form-control" type="hidden" value="{{ $id_triaje }}" id="id_servicio" name="id_servicio" style="width: 100%" readonly="" />
                                                        <input class="form-control" type="hidden" value="{{ $id_consultorio_destino }}" id="id_consultorio" name="id_consultorio" style="width: 100%" readonly="" />
                                                        
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="form-group">
                    <?php if(isset($id_hospitalizacion)){  ?>
                    <div class="col-md-6">
                        <a href="/all_hospitalizado_by_establecimiento" class="form-control btn btn-danger" tabindex="99922">CANCELAR</a>
                    </div>
                    <?php }else{  ?>
                      <div class="col-md-6">
                        <a href="/all_pendientes_hospitalizacion/{{ Auth::user()->id }}" class="form-control btn btn-danger" tabindex="99922">CANCELAR</a>
                    </div>
                    <?php }  ?>

                    <div class="col-md-6">
                        <!--<input id="btnReg" class="form-control btn btn-success" type="submit" value="GUARDAR" tabindex="99921">-->
                        <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()">GUARDAR</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



@include('frontend.includes.footer')
@endsection
