@extends('frontend.layouts.master')
@section('renderjs')

<style>
  #detalleModal .modal-dialog {
    width: 95%;
    max-width: 1200px;
  }

  #programacionAsistencial {
    padding: 15px;
    background: #fff;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 4px 4px;
  }

  /* Estilos para las pestañas */
  .tab-content {
    padding: 20px 0;
  }
  .nav-tabs {
    border-bottom: 2px solid #ddd;
  }
  .nav-tabs > li > a {
    margin-right: 2px;
    line-height: 1.42857143;
    border: 1px solid transparent;
    border-radius: 4px 4px 0 0;
    color: #555;
  }
  .nav-tabs > li > a:hover {
    border-color: #eee #eee #ddd;
  }
  .nav-tabs > li.active > a,
  .nav-tabs > li.active > a:hover,
  .nav-tabs > li.active > a:focus {
    color: #555;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
    font-weight: bold;
  }

  .styled-checkbox {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    vertical-align: middle;
    cursor: pointer;
    -webkit-appearance: checkbox;
    appearance: checkbox;
    transition: background-color 0.2s;
  }

  .error-field {
      border: 1px solid #E55050 !important;
      border-radius: 4px;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.solo-letras');

    inputs.forEach(input => {
      input.addEventListener('keydown', e => {
        const teclasPermitidas = [
          'Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End'
        ];

        const esLetra = /^[a-zA-ZÀ-ÿ\u00f1\u00d1\s]$/.test(e.key);

        if (!esLetra && !teclasPermitidas.includes(e.key)) {
          e.preventDefault();
        }
      });

      input.addEventListener('input', e => {
        e.target.value = e.target.value.replace(/[^a-zA-ZÀ-ÿ\u00f1\u00d1\s]/g, '');
      });

      input.addEventListener('paste', e => {
        e.preventDefault();
        const textoPegado = (e.clipboardData || window.clipboardData).getData('text');

        const textoLimpio = textoPegado.replace(/[^a-zA-ZÀ-ÿ\u00f1\u00d1\s]/g, '');

        const inicio = e.target.selectionStart;
        const fin = e.target.selectionEnd;
        const valor = e.target.value;

        e.target.value = valor.substring(0, inicio) + textoLimpio + valor.substring(fin);

        const nuevaPos = inicio + textoLimpio.length;
        e.target.setSelectionRange(nuevaPos, nuevaPos);
      });

      input.addEventListener('drop', e => e.preventDefault());
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.solo-numeros');

    inputs.forEach(input => {
      input.addEventListener('keydown', e => {
        const teclasPermitidas = [
          'Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End'
        ];
        const esNumero = /^[0-9]$/.test(e.key);
        if (!esNumero && !teclasPermitidas.includes(e.key)) {
          e.preventDefault();
        }
      });

      input.addEventListener('input', e => {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
      });

      input.addEventListener('paste', e => {
        e.preventDefault();
        let textoPegado = (e.clipboardData || window.clipboardData).getData('text');

        const soloNumeros = textoPegado.replace(/[^0-9]/g, '');

        const inicio = e.target.selectionStart;
        const fin = e.target.selectionEnd;
        const valor = e.target.value;

        e.target.value = valor.substring(0, inicio) + soloNumeros + valor.substring(fin);

        const nuevaPosicion = inicio + soloNumeros.length;
        e.target.setSelectionRange(nuevaPosicion, nuevaPosicion);
      });

      input.addEventListener('drop', e => e.preventDefault());
    });
  });

  function jsDecimals(e) {
    var evt = (e) ? e : window.event;
    var key = (evt.keyCode) ? evt.keyCode : evt.which;
    if (key != null) {
        key = parseInt(key, 10);
        if ((key < 48 || key > 57) && (key < 96 || key > 105)) {
            if (!jsIsUserFriendlyChar(key, "Decimals")) {
                return false;
            }
        } else {
            if (evt.shiftKey) {
                return false;
            }
        }
    }
    return true;
  }
    
  $(document).ready(function () {
    $(".js-example-basic-multiple").select2({
        width: 'resolve',
        max_selected_options: 4
    });
        
    var selectedValues = new Array();
    <?php 
        $n = 0;
         foreach ($consultoriomedico as $rowConsul):
             ?>
                selectedValues[<?php echo $n?>] = "<?php echo $rowConsul->id_consultorio?>";
             <?php
             $n++;
         endforeach;
    ?>
    $('.js-example-basic-multiple').val(selectedValues).trigger('change')
  });

  function validateFrm() {
    let esValido = true;
    let firstError = null;
    
    $('#frmlotizar input, #frmlotizar select, #frmlotizar [data-required-radio]')
        .removeClass('error-field');
    
    $('#errorSelect').hide();
    $('#errorTexto').hide();
    
    $('#frmlotizar [data-required="true"]').each(function() {

      const type = $(this).attr('type');
      const tag = $(this).prop('tagName').toLowerCase();
      const value = $(this).val()?.trim() ?? "";

      if (tag === 'input') {
        if (type === 'text' || type === 'date' || type === 'email' || type === 'number') {
          if (value === '') {
            $(this).addClass('error-field');
            if (!firstError) $(this).focus();
            firstError = true;
          }
        } 
        else if (type === 'file') {
          if ($(this)[0].files.length === 0) {
            $(this).addClass('error-field');
            if (!firstError) $(this).focus();
            firstError = true;
          }
        }
      }

      if (tag === 'select') {
        if (value === '') {
          $(this).addClass('error-field');
          if (!firstError) $(this).focus();
          firstError = true;
        }
      }

      if (this.id === 'id_grado') {
        if (value === '') $('#errorSelect').show();
        else $('#errorSelect').hide();
      }

      if (this.id === 'txtNroCip') {
        if (value === '') $('#errorTexto').show();
        else $('#errorTexto').hide();
      }

      if (this.id === 'paterno') {
        if ($(this).val().trim() === '') $('#errorPaterno').show();
        else $('#errorPaterno').hide();
      }

      if (this.id === 'materno') {
        if ($(this).val().trim() === '') $('#errorMaterno').show();
        else $('#errorMaterno').hide();
      }

      if (this.id === 'nombre') {
        if ($(this).val().trim() === '') $('#errorNombres').show();
        else $('#errorNombres').hide();
      }

      if (this.id === 'fechaNacimiento') {
        if ($(this).val().trim() === '') $('#errorFechaNac').show();
        else $('#errorFechaNac').hide();
      }

      if (this.id === 'id_estadocivil') {
        if ($(this).val().trim() === '') $('#errorEstadoCivil').show();
        else $('#errorEstadoCivil').hide();
      }

      if (this.id === 'nro-hijos') {
        if ($(this).val().trim() === '') $('#errorNroHijos').show();
        else $('#errorNroHijos').hide();
      }

      if (this.id === 'id_sexo') {
        if ($(this).val().trim() === '') $('#errorSexo').show();
        else $('#errorSexo').hide();
      }

      if (this.id === 'txtCelular') {
        if ($(this).val().trim() === '') $('#errorCelular').show();
        else $('#errorCelular').hide();
      }

      if (this.id === 'txtEmergencia') {
        if ($(this).val().trim() === '') $('#errorCelEmer').show();
        else $('#errorCelEmer').hide();
      }

      if (this.id === 'email') {
        if ($(this).val().trim() === '') $('#errorEmail').show();
        else $('#errorEmail').hide();
      }

      if (this.id === 'email-inst') {
        if ($(this).val().trim() === '') $('#errorEmailInstitucional').show();
        else $('#errorEmailInstitucional').hide();
      }

      if (this.id === 'txtDomicilio') {
        if ($(this).val().trim() === '') $('#errorDomicilio').show();
        else $('#errorDomicilio').hide();
      }

      if (this.id === 'id_departamento') {
        if ($(this).val().trim() === '') $('#errorDpto').show();
        else $('#errorDpto').hide();
      }

      if (this.id === 'id_provincia') {
        if ($(this).val().trim() === '') $('#errorProvincia').show();
        else $('#errorProvincia').hide();
      }

      if (this.id === 'id_distrito') {
        if ($(this).val().trim() === '') $('#errorDistrito').show();
        else $('#errorDistrito').hide();
      }

      if (this.id === 'txtInicioLabo') {
        if ($(this).val().trim() === '') $('#errorInicioLabo').show();
        else $('#errorInicioLabo').hide();
      }
      
      if (this.id === 'id_situacion_actual') {
        if ($(this).val().trim() === '') $('#errorSitAct').show();
        else $('#errorSitAct').hide();
      }

      if (this.id === 'id_condicion_laboral') {
        if ($(this).val().trim() === '') $('#errorCondicionLab').show();
        else $('#errorCondicionLab').hide();
      }

      if (this.id === 'cargo') {
        if ($(this).val().trim() === '') $('#errorCargo').show();
        else $('#errorCargo').hide();
      }

      if (this.id === 'id_establecimiento') {
        if ($(this).val().trim() === '') $('#errorEstablecimiento').show();
        else $('#errorEstablecimiento').hide();
      }

      if (this.id === 'id_unidad') {
        if ($(this).val().trim() === '') $('#errorUnidad').show();
        else $('#errorUnidad').hide();
      }

      if (this.id === 'id_sub_unidad') {
        if ($(this).val().trim() === '') $('#errorSubUnidad').show();
        else $('#errorSubUnidad').hide();
      }

      var celular = $('#txtCelular').val().trim();
      var emergencia = $('#txtEmergencia').val().trim();

      if (this.id === 'txtEmergencia') {
        if (celular !== "" && emergencia !== "" && celular === emergencia) $('#errorCelEmerIguales').show();
        else $('#errorCelEmerIguales').hide();
      }

      let hoy = new Date();
      hoy.setHours(0,0,0,0);
      let fechaNacimiento = $('#fechaNacimiento').val();

      let fn = new Date(fechaNacimiento);
      let edad = hoy.getFullYear() - fn.getFullYear();
      let mes = hoy.getMonth() - fn.getMonth();
      let dia = hoy.getDate() - fn.getDate();

      if (mes < 0 || (mes === 0 && dia < 0)) {
          edad--;
      }

      if (this.id === 'fechaNacimiento') {
        if (edad < 18) {
          $('#errorFechaNacMenorEdad').show();
          $(this).addClass('error-field');
          if (!firstError) $(this).focus();
          firstError = true;
        }
        else $('#errorFechaNacMenorEdad').hide();
      }
      
      let inicioLabo = $('#txtInicioLabo').val();
      let fechaInicio = new Date(inicioLabo);
      fechaInicio.setHours(0,0,0,0);
      
      if (this.id === 'txtInicioLabo') {
        if (fechaInicio >= hoy) {
          $('#errorInicioLaboMenorHoy').show();
          $(this).addClass('error-field');
          if (!firstError) $(this).focus();
          firstError = true;
        }
        else $('#errorInicioLaboMenorHoy').hide();
      }
    });

    $('#frmlotizar input, #frmlotizar select').on('input change', function() {
      $(this).removeClass('error-field');

      if (this.id === 'id_grado') $('#errorSelect').hide();
      if (this.id === 'txtNroCip') $('#errorTexto').hide();
      if (this.id === 'paterno') $('#errorPaterno').hide();
      if (this.id === 'materno') $('#errorMaterno').hide();
      if (this.id === 'nombre') $('#errorNombres').hide();
      if (this.id === 'fechaNacimiento') $('#errorFechaNac').hide();
      if (this.id === 'id_estadocivil') $('#errorEstadoCivil').hide();
      if (this.id === 'nro-hijos') $('#errorNroHijos').hide();
      if (this.id === 'id_sexo') $('#errorSexo').hide();
      if (this.id === 'txtCelular') $('#errorCelular').hide();
      if (this.id === 'txtEmergencia') $('#errorCelEmer').hide();
      if (this.id === 'email') $('#errorEmail').hide();
      if (this.id === 'email-inst') $('#errorEmailInstitucional').hide();
      if (this.id === 'txtDomicilio') $('#errorDomicilio').hide();
      if (this.id === 'id_departamento') $('#errorDpto').hide();
      if (this.id === 'id_provincia') $('#errorProvincia').hide();
      if (this.id === 'id_distrito') $('#errorDistrito').hide();
      if (this.id === 'txtInicioLabo') $('#errorInicioLabo').hide();
      if (this.id === 'id_situacion_actual') $('#errorSitAct').hide();
      if (this.id === 'id_condicion_laboral') $('#errorCondicionLab').hide();
      if (this.id === 'cargo') $('#errorCargo').hide();
      if (this.id === 'id_establecimiento') $('#errorEstablecimiento').hide();
      if (this.id === 'id_unidad') $('#errorUnidad').hide();
      if (this.id === 'id_sub_unidad') $('#errorSubUnidad').hide();

      if (this.id === 'txtEmergencia') $('#errorCelEmerIguales').hide();
      if (this.id === 'txtInicioLabo') $('#errorInicioLaboMenorHoy').hide();
      if (this.id === 'fechaNacimiento') $('#errorFechaNacMenorEdad').hide();
    });

    var est = $('#id_establecimiento').val();

    if (!est) {
      $('#id_establecimiento').next('.select2-container').addClass('error-field');
      if (!firstError) {
        $('#id_establecimiento').select2('focus');
        firstError = true;
      }
    } else {
      $('#id_establecimiento').next('.select2-container').removeClass('error-field');
    }

    $('#id_establecimiento').on('change', function() {
      $(this).next('.select2-container').removeClass('error-field');
    });

    const chkCeseLab = document.getElementById('chk_cese_lab');
    const txtCeseLab = document.getElementById('txtCeseLab');

    if (chkCeseLab.checked) {
      if (txtCeseLab.value.trim() === '') {
        $('#errorCeseLab').show();
        $(txtCeseLab).addClass('error-field');
        if (!firstError) txtCeseLab.focus();
        firstError = true;
      } else {
        $('#errorCeseLab').hide();
        $(txtCeseLab).removeClass('error-field');
      }
      
      let inicioLabo = $('#txtInicioLabo').val();
      let fechaInicio = new Date(inicioLabo);
      fechaInicio.setHours(0,0,0,0);

      let ceseLabo = $('#txtCeseLab').val();
      let fechaCese = new Date(ceseLabo);
      fechaCese.setHours(0,0,0,0);

      if (fechaCese < fechaInicio) {
        $('#errorCeseLabMayInicio').show();
        $(txtCeseLab).addClass('error-field');
        if (!firstError) txtCeseLab.focus();
        firstError = true;
      } else {
        $('#errorCeseLabMayInicio').hide(); 
        $(txtCeseLab).removeClass('error-field');
      }
    } else {
      $('#errorCeseLab').hide();
      $(txtCeseLab).removeClass('error-field');

      $('#errorCeseLabMayInicio').hide();
      $(txtCeseLab).removeClass('error-field');
    }

    $('<style>')
      .prop('type', 'text/css')
      .html('.error-field { border: 1px solid #E55050 !important; }')
      .appendTo('head');

    let especialidadesActivas = especialidades.filter(esp => esp.id_estado_reg === 1);
    if (especialidadesActivas.length === 0) {
      $('#errorTabla').show();
      $('#tblEspecialidades').addClass('error-table');
      
      $('html, body').animate({
        scrollTop: $('#tblEspecialidades').offset().top - 120
      }, 500);

      esValido = false;

    } else {
      $('#errorTabla').hide();
      $('#tblEspecialidades').removeClass('error-table');
    }

    const radioSeleccionado = tblEspecialidades.querySelector('input[type="radio"]:checked');
    if (!radioSeleccionado) {
      alert('Seleccione una de las profesiones como activa.');
      esValido = false;
    }

    const condicionSeleccionada = document.querySelector('input[name="condicion"]:checked');
    if (!condicionSeleccionada) {
      alert('Debe seleccionar una condicion (CIVIL O POLICIAL) al inicio del formulario, en informacion personal.');
      esValido = false;
    }

    const grupoSeleccionado = document.querySelector('input[name="grupoTrabajo"]:checked');
    if (!grupoSeleccionado) {
      alert('Debe seleccionar un grupo de trabajo (ASISTENCIAL O ADMINISTRATIVO) al inicio del formulario, en informacion laboral.');
      esValido = false;
    }

    if (firstError) {
      esValido = false; 
    }

    return esValido;
  }

  $('#chk_cese_lab').on('change', function() {
    const txtCeseLab = $('#txtCeseLab');

    if (this.checked) {
        txtCeseLab.prop('disabled', false);

        if (txtCeseLab.val().trim() === '') {
            $('#errorCeseLab').show();
            txtCeseLab.addClass('error-field');
        }

        

    } else {
        txtCeseLab.prop('disabled', true);
        txtCeseLab.val('');
        $('#errorCeseLab').hide();
        txtCeseLab.removeClass('error-field');
        
    }
});

  function submitFrm() {
    $('#especialidades_json').val(JSON.stringify(especialidades));
    $("#frmlotizar").submit();
  }

  $('#txtCeseLab').on('input change', function() {
    $(this).removeClass('error-field');
    $('#errorCeseLab').hide();
  });

  $('#addRow').on('click', function () {
    AddFila();
  });

  $('#example tbody').on('click', 'button.deleteFila', function () {
    var obj = $(this);
    bootbox.confirm("Desea eliminar la disponibilidad?", function(result) {
        if (result==true) {
            var id_del = obj.parent().parent().find('.id').val();
            var newDelete = '<input type="hidden" name="ids_delete[]" value="'+id_del+'" />';
            $('#example tbody').append(newDelete);
            obj.parent().parent().remove();
        }
    });
  });

  document.getElementById("frmlotizar").addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
      e.preventDefault();
    }
  });
		
  function AddFila(){
    var newRow = "";
    var ind = $('#example tbody tr').length;
    
    if(ind > 0){
        $('.cmbDia').each(function(){
            var ind_tmp = $(this).attr("ind");
            if(ind_tmp => ind)ind=Number(ind_tmp)+1;
        });
    }
    
    var item_dia = "";
    $('#diaTemp option').each(function(){
        item_dia += "<option value="+$(this).val()+">"+$(this).html()+"</option>"	
    });
    
    var item_turno = "";
    $('#turnoTemp option').each(function(){
        item_turno += "<option value="+$(this).val()+">"+$(this).html()+"</option>"	
    });
    
    newRow +='<tr>';
    newRow +='<td><select class="form-control cmbDia" id="dia'+ind+'" ind="'+ind+'" tabindex="12" name="dia[]" style="width:200px" >'+item_dia+'</select><input type="hidden" name="id[]" class="id" value=""/></td>';
    newRow +='<td><select class="form-control cmbTurno" id="turno'+ind+'" ind="'+ind+'" tabindex="12" name="turno[]" style="width:200px">'+item_turno+'</select></td>';
    newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="numero_atencion[]" required="" id="numero_atencion" class="nro_solicitado " style="margin-left:4px; width:100px" value=""/>';
    newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
    
    $('#example tbody').append(newRow);
  }

  $est = $("#frmlotizar").find("#id_establecimiento");
  $est.select2({
      width: 'resolve'
  });

  document.addEventListener('DOMContentLoaded', () => {
    const radio1 = document.getElementById('civil');
    const radio2 = document.getElementById('policial');
    const select = document.getElementById('id_grado');
    const nroCip = document.getElementById('txtNroCip');

    function actualizarCampos () {
      if (radio2.checked) {
        select.disabled = false;
        nroCip.disabled = false;

        select.dataset.required = "true";
        nroCip.dataset.required = "true";
        
        document.getElementById('gradorequired').hidden = false;
        document.getElementById('ciprequired').hidden = false;
      } else {
        select.disabled = true;
        nroCip.disabled = false;

        delete select.dataset.required;
        // delete nroCip.dataset.required;

        select.value = '';
        // nroCip.value = '';

        errorSelect.style.display = "none";
        errorTexto.style.display = "none";

        select.classList.remove("error-field");
        nroCip.classList.remove("error-field");

        document.getElementById('gradorequired').hidden = true;
        document.getElementById('ciprequired').hidden = true;
      }
    }

    document.querySelectorAll('input[name="condicion"]').forEach(radio => {
      radio.addEventListener('change', actualizarCampos);
    });

    actualizarCampos();
  });

  const inputFecha = document.getElementById('fechaNacimiento');
  const inputEdad = document.getElementById('edad');

  function calcularEdad() {
    const fechaNacimiento = new Date(inputFecha.value);
    if (isNaN(fechaNacimiento)) return; 

    const hoy = new Date();

    let años = hoy.getFullYear() - fechaNacimiento.getFullYear();
    let meses = hoy.getMonth() - fechaNacimiento.getMonth();
    let dias = hoy.getDate() - fechaNacimiento.getDate();

    if (dias < 0) {
      meses--;
      const ultimoDiaMesAnterior = new Date(hoy.getFullYear(), hoy.getMonth(), 0).getDate();
      dias += ultimoDiaMesAnterior;
    }
    if (meses < 0) {
      años--;
      meses += 12;
    }

    inputEdad.value = `${años} años, ${meses} meses, ${dias} días`;
  }

  document.addEventListener('DOMContentLoaded', calcularEdad);

  inputFecha.addEventListener('change', calcularEdad);

  function getProvincia() {
  var $departamento = $('#id_departamento');
  var $provincia = $('#id_provincia');
  var $distrito = $('#id_distrito');
  var departamentoId = $departamento.val();

  $provincia.html("<option value=''>CARGANDO...</option>");
  $provincia.prop('disabled', true);

  $distrito.empty().append("<option value=''>SELECCIONE</option>");
  $distrito.prop('disabled', true);
  $distrito.val('').trigger('change');

  if (!departamentoId) {
    $provincia.html("<option value=''>SELECCIONE</option>");
    $provincia.prop('disabled', false);
    
    return;
  }

  $.ajax({
    url: '/obtener_provincia_por_departamento/' + departamentoId,
    type: 'GET',
    dataType: 'json',
    success: function(result) {
      var options = "<option value=''>SELECCIONE</option>";
      $(result).each(function (i, provincia) {
        options += "<option value='" + provincia.id_provincia + "'>" + provincia.descripcion + "</option>";
      });
      $provincia.html(options);
    },
    error: function() {
      $provincia.html("<option value=''>ERROR AL CARGAR</option>");
    },
    complete: function() {
      $provincia.prop('disabled', false);
    }
  });
}

function getDistrito() {
  var $provincia = $('#id_provincia');
  var $distrito = $('#id_distrito');
  var provinciaId = $provincia.val();

  $distrito.html("<option value=''>CARGANDO...</option>");
  $distrito.prop('disabled', true);

  if (!provinciaId) {
    $distrito.html("<option value=''>SELECCIONE</option>");
    $distrito.prop('disabled', false);
    return;
  }

  $.ajax({
    url: '/obtener_distrito_por_provincia/' + provinciaId,
    type: 'GET',
    dataType: 'json',
    success: function(result) {
      var options = "<option value=''>SELECCIONE</option>";
      $(result).each(function (i, distrito) {
        options += "<option value='" + distrito.id_distrito + "'>" + distrito.descripcion + "</option>";
      });
      $distrito.html(options);
    },
    error: function() {
      $distrito.html("<option value=''>ERROR AL CARGAR</option>");
    },
    complete: function() {
      $distrito.prop('disabled', false);
    }
  });
}

function getDivisionSubUnidad () {
  var $unidad = $('#id_unidad');
  var $subunidad = $('#id_sub_unidad');
  var unidadId = $unidad.val();

  $subunidad.html("<option value''>CARGANDO...</option>");
  $subunidad.prop('disabled', true);

  if (!unidadId) {
    $subunidad.html("<option value=''>SELECCIONE</option>");
    $subunidad.prop('disabled', false);
    return;
  }

  $.ajax({
    url: '/obtener_subunidad_por_unidad/' + unidadId,
    type: 'GET',
    dataType: 'json',
    success: function (result) {
      var options = "<option value=''>SELECCIONE</option>";
      $(result).each(function (i, subunidad) {
        options += "<option value='" + subunidad.id + "'>" + subunidad.nombre + "</option>";
      });
      $subunidad.html(options);
    },
    error: function () {
      $subunidad.html("<option value=''>ERROR AL CARGAR</option>");
    },
    complete: function () {
      $subunidad.prop('disabled', false);
    }
  });
}

let especialidades = [];
let rne_medico = "{{ $rne_medico }}";
let id_profesion_medico = "{{ $id_profesion_medico }}";

function normalizarEspecialidad(raw) {
    return {
        id: raw.id ?? null,
        id_profesion: raw.id_profesion ?? null,
        nombre_profesion: raw.nombre_profesion ?? "",
        id_especialidad: raw.id_especialidad ?? null,
        nombre_especialidad: raw.nombre_especialidad ?? "",
        id_colegio_profesional: raw.id_colegio_profesional ?? null,
        nom_colegio: raw.nom_colegio ?? "",
        colegiatura: raw.colegiatura ?? "",
        rne: raw.rne ?? "",
        fecha_inicio_habilidad: raw.fecha_inicio_habilidad ?? "",
        fecha_termino_habilidad: raw.fecha_termino_habilidad ?? "",
        id_estado_colegiatura: raw.id_estado_colegiatura ?? 0,
        estado_colegiatura: raw.estado_colegiatura ?? "",
        id_estado_reg: raw.id_estado_reg ?? 1,
        especialidadPrincipal: raw.especialidadPrincipal ?? false
    };
}

document.addEventListener("DOMContentLoaded", function () {
    let tbody = document.getElementById("tblProfesionBody");
    
    especialidades = JSON.parse(tbody.dataset.especialidades || '[]').map(normalizarEspecialidad);

    actualizarTabla();
});

function recalcularEspecialidadPrincipal() {
    let principalIndex = especialidades.findIndex(
        esp => esp.rne && rne_medico && String(esp.rne) === String(rne_medico)
    );

    if (principalIndex === -1) {
        principalIndex = especialidades.findIndex(
            esp => String(esp.id_profesion) === String(id_profesion_medico)
        );
    }

    especialidades.forEach((esp, i) => {
        esp.especialidadPrincipal = (i === principalIndex);
    });
}

function actualizarTabla() {

    checkTablaEspecialidades();
    recalcularEspecialidadPrincipal();

    let tbody = document.getElementById("tblProfesionBody");
    tbody.innerHTML = '';
    
    especialidades.forEach(function(esp, index) {
        let tr = document.createElement('tr');
        
        if (esp.id_estado_reg === 0) {
            tr.style.opacity = '0.7';
            tr.style.backgroundColor = '#E8E8E8';
            tr.style.textDecoration = 'line-through';
        }

        let tdProfesion = document.createElement('td');
        tdProfesion.textContent = esp.nombre_profesion;
        tr.appendChild(tdProfesion);

        let tdNombre = document.createElement('td');
        tdNombre.textContent = esp.nombre_especialidad;
        tr.appendChild(tdNombre);

        let tdColegiatura = document.createElement('td');
        tdColegiatura.textContent = esp.colegiatura || '';
        tr.appendChild(tdColegiatura);

        let tdRNE = document.createElement('td');
        tdRNE.textContent = esp.rne || '';
        tr.appendChild(tdRNE);

        let tdActivo = document.createElement('td');
        tdActivo.className = 'text-center';

        let radio = document.createElement('input');
        radio.type = 'radio';
        radio.name = 'especialidadPrincipal';
        radio.checked = esp.especialidadPrincipal === true && esp.id_estado_reg === 1;
        
        radio.disabled = (esp.id_estado_reg === 0);

        radio.addEventListener('change', function () {
            especialidades.forEach(e => e.especialidadPrincipal = false);
            esp.especialidadPrincipal = true;
            especialidadPrincipalEscogida = esp;
            tbody.dataset.especialidades = JSON.stringify(especialidades);
        });

        tdActivo.appendChild(radio);
        tr.appendChild(tdActivo);

        let tdEliminar = document.createElement('td');
        tdEliminar.className = 'text-center';

        let btnEliminar = document.createElement('button');
        btnEliminar.className = 'btn btn-xs';
        
        if (esp.id_estado_reg === 0) {
          btnEliminar.textContent = 'Eliminado';
          btnEliminar.disabled = true;
          btnEliminar.className += ' btn-secondary';
        } else {
            btnEliminar.textContent = 'Eliminar';
            btnEliminar.className += ' btn-danger';
        }

        btnEliminar.addEventListener('click', function() {
            
          if (esp.id_estado_reg === 1) {
            if (esp.id !== null && esp.id !== undefined) {
              esp.id_estado_reg = 0;
              if (esp.especialidadPrincipal === true) {
                esp.especialidadPrincipal = false;
                especialidadPrincipalEscogida = null;
              }
            } 
            else {
              especialidades.splice(index, 1);
            }
          }
          
          actualizarTabla();
        });

        tdEliminar.appendChild(btnEliminar);
        tr.appendChild(tdEliminar);
        tbody.appendChild(tr);
    });

    tbody.dataset.especialidades = JSON.stringify(especialidades);
}

document.addEventListener('DOMContentLoaded', function() {
    function manejarCamposSegunProfesion() {
        var profesionSelect = document.getElementById('id_profesion');
        if (!profesionSelect) return;

        var profesionTexto = profesionSelect.options[profesionSelect.selectedIndex].text.trim();

        var esAuxiliar = (
            profesionTexto.startsWith("AUX") ||
            profesionTexto.startsWith("AX.") ||
            profesionTexto.startsWith("CHO") ||
            profesionTexto.startsWith("TÉC") ||
            profesionTexto.startsWith("OTR") ||
            profesionTexto.startsWith("POL")
        );

        function toggleCampo(idCampo, mostrar) {
            var campo = document.getElementById(idCampo);
            if (!campo) return;

            var container = campo.closest('.form-group') || campo.parentNode;
            container.style.display = mostrar ? 'block' : 'none';

            if (!mostrar) {
                if (campo.tagName === 'SELECT') {
                    campo.selectedIndex = 0;
                } else {
                    campo.value = '';
                }
            }
        }

        toggleCampo('id_especializacion', !esAuxiliar);
        toggleCampo('id_colegio_profesional', !esAuxiliar);
        toggleCampo('colegiatura', !esAuxiliar);
        toggleCampo('rne', !esAuxiliar);
        toggleCampo('slcEstadoColegiatura', !esAuxiliar);
        toggleCampo('fechInicioHab', !esAuxiliar);
        toggleCampo('fechFinHab', !esAuxiliar);
    }

    manejarCamposSegunProfesion();
});

const selectProfesion= document.getElementById("id_profesion");

selectProfesion.addEventListener("change", function () {
  const selectEstadoColegiatura = document.getElementById("slcEstadoColegiatura");
  const selectColegioProfesional = document.getElementById("id_colegio_profesional");
  const inputNroColegiatura = document.getElementById("colegiatura");
  const inputNroEspecialidad = document.getElementById("rne");
  const inputCargo = document.getElementById("cargo");
  const fechInicioHabilidad = document.getElementById("fechInicioHab");
  const fechaFinHabilidad = document.getElementById("fechFinHab");

  const texto = selectProfesion.options[selectProfesion.selectedIndex].text;
  const divs = document.querySelectorAll(".filtroProfesion");
  const primerasTres = texto.substring(0,3);

  selectEstadoColegiatura.value = "";
  $('#id_especializacion').val(null).trigger('change');
  $('#id_colegio_profesional').val(null).trigger('change');
  inputNroColegiatura.value = "";
  inputNroEspecialidad.value = "";
  fechInicioHabilidad.value = "";
  fechaFinHabilidad.value = "";


  if (primerasTres === "AUX" || primerasTres === "AX." || primerasTres === "CHO" || primerasTres === "TÉC" || primerasTres === "OTR" || primerasTres === "POL") {
    divs.forEach(div => div.style.display = "none");
  } else {
    divs.forEach(div => div.style.display = "block");
  }
});

function checkTablaEspecialidades() {
    let especialidadesActuales = especialidades.filter(esp => esp.id_estado_reg === 1).length;

    if (especialidadesActuales > 0) {
        $('#tblEspecialidades').removeClass('error-table');
        $('#errorTabla').hide();
    }
}

document.getElementById('addRowProfesion').addEventListener('click', () => {

  const especialidadesActuales = especialidades.filter(esp => esp.id_estado_reg === 1).length;

  const getVal = id => document.getElementById(id).value;
  const getTxt = id => {
    const el = document.getElementById(id);
    return el.options[el.selectedIndex]?.text || "";
  };

  const id_profesion           = getVal('id_profesion');
  const nombre_profesion       = getTxt('id_profesion');
  const filtroProfesion        = nombre_profesion.substring(0,3);
  const esAuxiliar             = ["AUX", "AX.", "CHO", "TÉC", "OTR", "POL"].includes(filtroProfesion);

  const id_especialidad        = getVal('id_especializacion');
  const nombre_especialidad    = getTxt('id_especializacion');
  const id_colegio_profesional = getVal('id_colegio_profesional');
  const nom_colegio            = getTxt('id_colegio_profesional');
  const colegiatura            = getVal('colegiatura');
  const rne                     = getVal('rne');
  const id_estado_colegiatura   = getVal('slcEstadoColegiatura');
  const estado_colegiatura      = getTxt('slcEstadoColegiatura');
  const fecha_inicio_habilidad  = getVal('fechInicioHab');
  const fecha_termino_habilidad = getVal('fechFinHab');

  if (!id_profesion) return alert("Debe seleccionar profesión.");

  if (esAuxiliar) {

    var existeAux = false;

    for (let i = 0; i < especialidades.length; i++) {
      if (especialidades[i].id_profesion == id_profesion) {
        existeAux = true;
      }
    }

    console.log("nuevo" + id_profesion);
    console.log('existe?' + existeAux);

    if (existeAux)
        return alert(`DUPLICADO (AUX/TEX/AX/OTROS). Ya existe esta profesion en la tabla: ${nombre_profesion}.`);

    especialidades.push({
      id: null,
      id_profesion,
      nombre_profesion,
      id_especialidad: null,
      nombre_especialidad: null,
      id_colegio_profesional: null,
      nom_colegio: null,
      colegiatura: null,
      rne: null,
      id_estado_colegiatura: null,
      estado_colegiatura: null,
      fecha_inicio_habilidad: null,
      fecha_termino_habilidad: null,
      especialidadPrincipal: especialidadesActuales === 0,
      id_estado_reg: 1
    });

    actualizarTabla();
    limpiarCamposEditar();
    return;
  }

  if (!id_especialidad) {
    alert("Debe seleccionar especialidad.");
    return;
  }

  if (!id_colegio_profesional) return alert("Debe seleccionar colegio.");

  if (id_colegio_profesional != "1") {
    if (!colegiatura) return alert("Ingrese número de colegiatura.");
    if (!rne) return alert("Ingrese número de especialidad.");
    if (!id_estado_colegiatura) return alert("Seleccione estado de colegiatura.");

    if (id_estado_colegiatura == "1") {
      if (!fecha_inicio_habilidad || !fecha_termino_habilidad)
        return alert("Complete las fechas de habilidad.");
      if (fecha_termino_habilidad < fecha_inicio_habilidad)
        return alert("La fecha fin debe ser mayor a la fecha inicio.");
    }
  }

  const existe = especialidades.some(e =>
    e.rne === rne && e.nombre_especialidad === nombre_especialidad
  );
  if (existe) return alert(`Especialidades iguales. ${rne}`);

  const profesion = {
    id: null,
    id_profesion,
    nombre_profesion,
    id_especialidad: id_especialidad || null,
    nombre_especialidad: nombre_especialidad || null,
    id_colegio_profesional: id_colegio_profesional || null,
    nom_colegio: nom_colegio || null,
    colegiatura: id_colegio_profesional == "1" ? null : colegiatura,
    rne: id_colegio_profesional == "1" ? null : rne,
    id_estado_colegiatura: id_colegio_profesional == "1" ? null : id_estado_colegiatura,
    estado_colegiatura: id_colegio_profesional == "1" ? null : estado_colegiatura,
    fecha_inicio_habilidad: id_colegio_profesional == "1" ? null : fecha_inicio_habilidad,
    fecha_termino_habilidad: id_colegio_profesional == "1" ? null : fecha_termino_habilidad,
    especialidadPrincipal: especialidadesActuales === 0,
    id_estado_reg: 1
  };

  especialidades.push(profesion);

  actualizarTabla();
  limpiarCamposEditar();

  function limpiarCamposEditar() {
    $("#id_profesion").val("").trigger('change');
    $("#id_especializacion").val(null).trigger('change');
    $("#id_colegio_profesional").val(null).trigger('change');
    $("#slcEstadoColegiatura").val(null).trigger('change');
    $("#colegiatura").val("");
    $("#rne").val("");
    $("#fechInicioHab").val("");
    $("#fechFinHab").val("");
  }

});


$("#id_colegio_profesional").on("change", function () {
  const idColegio = $(this).val();

  const campos = [
    "#colegiatura",
    "#rne",
    "#slcEstadoColegiatura",
    "#fechInicioHab",
    "#fechFinHab"
  ];

  if (idColegio == "1") {
    campos.forEach(campo => {
      $(campo).prop("disabled", true);
      if (campo === "#slcEstadoColegiatura") $(campo).val(null).trigger("change");
      else $(campo).val("");
    });
  } else {
    campos.forEach(campo => $(campo).prop("disabled", false));
  }
});


$(document).ready(function() {
    $('#id_distrito').select2({ width: '100%' });
    $('#id_especializacion').select2({ width: '100%' });
    $('#id_colegio_profesional').select2({ width: '100%' });
});

function actualizarFechasHabilidad() {
  var estado = document.getElementById('slcEstadoColegiatura').value;
  
  if (estado == 0) {
    document.getElementById('fechainiciorequired').hidden = true;
    document.getElementById('fechaterminorequired').hidden = true;
  } else {
    document.getElementById('fechainiciorequired').hidden = false;
    document.getElementById('fechaterminorequired').hidden = false;
  }
  
  var disabled = (estado === "0");

  document.getElementById('fechInicioHab').disabled = disabled;
  document.getElementById('fechFinHab').disabled = disabled;
}

document.getElementById('slcEstadoColegiatura').addEventListener('change', actualizarFechasHabilidad);
document.addEventListener('DOMContentLoaded', actualizarFechasHabilidad);


const chkCeseLab = document.getElementById('chk_cese_lab');
const txtCeseLab = document.getElementById('txtCeseLab');

txtCeseLab.disabled = !chkCeseLab.checked;

chkCeseLab.addEventListener('change', function() {
  if (this.checked) {
    txtCeseLab.disabled = false;
    document.getElementById("ceselaboresrequired").hidden = false;
  } else {
    txtCeseLab.disabled = true;
    txtCeseLab.value = '';
    document.getElementById("ceselaboresrequired").hidden = true;
  }
});

function generarData () {
  let profesionesData = [];
  const tbodyEspecialidades = document.getElementById('tblProfesionBody');
  
  if (tbodyEspecialidades && tbodyEspecialidades.hasAttribute('data-especialidades')) {
    try {
      const jsonData = tbodyEspecialidades.getAttribute('data-especialidades');
      profesionesData = JSON.parse(jsonData);
    } catch (e) {
      console.error('Error al parsear especialidades:', e);
      profesionesData = [];
    }
  } else if (typeof especialidades !== 'undefined') {
    profesionesData = especialidades;
  }

  let data = {
    condicion: parseInt(document.querySelector('input[name="condicion"]:checked').value),
    dni: document.getElementById('dni').value,
    tipo_documento_id: parseInt(document.getElementById('tipo_documento_id').value),
    tipoDoc: document.getElementById('tipo_documento').value,
    id_grado: parseInt(document.getElementById('id_grado').value) || null,
    gradoDescr: document.querySelector('#id_grado option:checked')?.text || null,
    cip: document.getElementById('txtNroCip').value || null,
    nombre: document.getElementById('nombre').value,
    paterno: document.getElementById('paterno').value,
    materno: document.getElementById('materno').value,
    fecha_nacimiento: document.getElementById('fechaNacimiento').value,
    sexo: document.getElementById('id_sexo').value,
    id_estado_civil: parseInt(document.getElementById('id_estadocivil').value),
    estadoCivil: document.querySelector('#id_estadocivil option:checked')?.text || null,
    nro_hijos: parseInt(document.getElementById('nro_hijos').value),
    nroHijos: document.getElementById('nro_hijos').value,
    celular: document.getElementById('txtCelular').value,
    cel_emergencia: document.getElementById('txtEmergencia').value,
    email: document.getElementById('email').value,
    emailInst: document.getElementById('email_inst').value,
    domicilio: document.getElementById('txtDomicilio').value,
    id_departamento: parseInt(document.getElementById('id_departamento').value),
    departamento: document.querySelector('#id_departamento option:checked')?.text || null,
    id_provincia: parseInt(document.getElementById('id_provincia').value),
    provincia: document.querySelector('#id_provincia option:checked')?.text || null,
    id_distrito: parseInt(document.getElementById('id_distrito').value),
    distrito: $('#id_distrito').select2('data')[0]?.text || null,
    
    grupo_trabajo: parseInt(document.querySelector('input[name="grupoTrabajo"]:checked').value),
    fecha_inicio: document.getElementById('txtInicioLabo').value,
    fecha_cese: document.getElementById('txtCeseLab').value || null,
    id_situacion_personal: parseInt(document.getElementById('id_situacion_actual').value),
    situacionPersonal: document.querySelector('#id_situacion_actual option:checked')?.text || null,
    id_condicion_laboral: parseInt(document.getElementById('id_condicion_laboral').value),
    condicionLaboral: document.querySelector('#id_condicion_laboral option:checked')?.text || null,
    cargo: document.getElementById('cargo').value,
    id_establecimiento: parseInt(document.getElementById('id_establecimiento').value),
    ipress: $('#id_establecimiento').select2('data')[0]?.text || null,
    id_unidad: parseInt(document.getElementById('id_unidad').value),
    unidad: document.querySelector('#id_unidad option:checked')?.text || null,
    id_sub_unidad: parseInt(document.getElementById('id_sub_unidad').value),
    subUnidad: document.querySelector('#id_sub_unidad option:checked')?.text || null,
    
    flag_rrhh: "{{ addslashes($flag_rrhh) }}",

    nombre_firma: document.getElementById('archivo_firma')?.value || null,
    
    profesiones: profesionesData,
    
    servicio_atencion: typeof obtenerServiciosDeTabla === 'function' ? obtenerServiciosDeTabla() : []
  };

  return data;
}

function obtenerProfesionPrincipal (listaProfesiones) {
  let data = {};
  for (let i = 0; i < listaProfesiones.length; i++) {
    if (listaProfesiones[i].especialidadPrincipal == true) {
      if(listaProfesiones[i].id != null) {
        data = {
          profesion: listaProfesiones[i].nombre_profesion || null,
          especialidad: listaProfesiones[i].nombre_especialidad || null,
          colegioProfesional: listaProfesiones[i].nom_colegio || null,
          colegiatura: listaProfesiones[i].colegiatura || null,
          rne: listaProfesiones[i].rne || null,
          id_estado_colegiatura: listaProfesiones[i].estado_colegiatura,
          fecha_inicio_habilidad: listaProfesiones[i].fecha_inicio_habilidad || null,
          fecha_termino_habilidad: listaProfesiones[i].fecha_termino_habilidad || null
        }
      } else {
        data = {
          profesion: listaProfesiones[i].nombre_profesion || null,
          especialidad: listaProfesiones[i].nombre_especialidad || null,
          colegioProfesional: listaProfesiones[i].nom_colegio || null,
          colegiatura: listaProfesiones[i].colegiatura || null,
          rne: listaProfesiones[i].rne || null,
          id_estado_colegiatura: listaProfesiones[i].id_estado_colegiatura || null,
          fecha_inicio_habilidad: listaProfesiones[i].fecha_inicio_habilidad || null,
          fecha_termino_habilidad: listaProfesiones[i].fecha_termino_habilidad || null
        }
      }
    }
  }
  return data;
}

function normalizar(v) {
  return v === null || v === undefined ? '' : v;
}

$(document).on('click', '.detalle-btn', function(e) {
  let esValido = validateFrm();
  if (!esValido) {
    return;
  }
  else {
    let data = generarData();
    let profesionPrincipal = obtenerProfesionPrincipal(data.profesiones);

    e.preventDefault();
    
    $('#detalleModal').modal('show');

    function convertirFecha(fechaStr) {
      if (!fechaStr) return '-';
        const [anio, mes, dia] = fechaStr.split("-");
        return `${dia.padStart(2,'0')}/${mes.padStart(2,'0')}/${anio}`;
    }

    function valorTexto(valor, fallback = '-') {
      return (valor !== null && valor !== undefined && valor !== '' && !Number.isNaN(valor)) ? valor : fallback;
    }

    var condicionTexto = {1: 'CIVIL', 2: 'POLICIA'};
    var condicionSexo = {'M': 'MASCULINO', 'F': 'FEMENINO'};
    var grupoTrabajo = {1: 'ASISTENCIAL', 2: 'ADMINISTRATIVO'};
    var estadoColegiatura = {1: 'HABILITADO', 0: 'INHABILITADO'};

    const fechaNac = convertirFecha(data.fecha_nacimiento);
    const fechaInioLab = convertirFecha(data.fecha_inicio);
    const fechaFinLab = convertirFecha(data.fecha_cese);
    const fechaInicioHab = convertirFecha(data.fecha_inicio_habilidad);
    const fechaFinHab = convertirFecha(data.fecha_termino_habilidad);
    const edad = document.getElementById('edad').value;

    const profesion = valorTexto(profesionPrincipal.profesion);
    const filtroProfesion = profesion.substring(0, 3);
    const esAuxiliar = (filtroProfesion === "AUX" || filtroProfesion === "AX." || 
                        filtroProfesion === "CHO" || filtroProfesion === "TÉC" || 
                        filtroProfesion === "OTR");

    $('#modal-condicion').text(condicionTexto[data.condicion] || '-');
    $('#modal-grado').text(data.gradoDescr === "SELECCIONE" ? "-" : valorTexto(data.gradoDescr));
    $('#modal-tipo-numdoc').text((data.tipoDoc && data.dni) ? data.tipoDoc + ' - ' + data.dni : '-');
    $('#modal-nrocip').text(valorTexto(data.cip));
    $('#modal-nombre').text([data.nombre, data.paterno, data.materno].filter(Boolean).join(' ') || '-');
    $('#modal-fechaNacimiento').text(convertirFecha(data.fecha_nacimiento));
    $('#modal-edad-sexo').text((valorTexto(edad) + ' / ' + (condicionSexo[data.sexo] || '-')));
    $('#modal-estado-civil').text(valorTexto(data.estadoCivil));
    $('#modal-nro-hijos').text(valorTexto(data.nro_hijos));
    $('#modal-num-celular').text(valorTexto(data.celular));
    $('#modal-num-emergencia').text(valorTexto(data.cel_emergencia));
    $('#modal-correo-electronico').text(valorTexto(data.email));
    $('#modal-correo-institucional').text(valorTexto(data.emailInst));
    $('#model-domicilio').text(valorTexto(data.domicilio));
    $('#dep-prov-dist').text([data.departamento, data.provincia, data.distrito].filter(Boolean).join(' / ') || '-');

    $('#modal-grupo-trabajo').text(grupoTrabajo[data.grupo_trabajo] || '-');
    $('#modal-fecha-iniciofin-laboral').text(convertirFecha(data.fecha_inicio) + ' | ' + convertirFecha(data.fecha_cese));
    $('#modal-situacion-actual').text(valorTexto(data.situacionPersonal));
    $('#modal-condicion-laboral').text(valorTexto(data.condicionLaboral));
    
    $('#modal-profesion').text(profesionPrincipal.profesion);
            
    if (esAuxiliar) {
      $('#modal-especializacion').text('-');
      $('#modal-col-profesional').text('-');
      $('#modal-ncmp-nrne').text('- / -');
      $('#modal-estado-colegiatura').text('-');
      $('#modal-iniciofin-habilidad').text('- | -');
    } else {
      $('#modal-especializacion').text(valorTexto(profesionPrincipal.especialidad));
      $('#modal-col-profesional').text(valorTexto(profesionPrincipal.colegioProfesional));
      $('#modal-ncmp-nrne').text(valorTexto(profesionPrincipal.colegiatura) + ' / ' + valorTexto(profesionPrincipal.rne));
      $('#modal-estado-colegiatura').text(estadoColegiatura[profesionPrincipal.id_estado_colegiatura] || '-');
      $('#modal-iniciofin-habilidad').text(convertirFecha(profesionPrincipal.fecha_inicio_habilidad) + ' | ' + convertirFecha(profesionPrincipal.fecha_termino_habilidad));
    }

    var $tabla = $('#tabla-profesiones');
    $tabla.empty();

    if (data.profesiones && data.profesiones.length > 0) {
      let hayFilas = false;

      data.profesiones.forEach(function(p) {
        if (p.id_estado_reg == 1 && p.especialidadPrincipal === false) {
          hayFilas = true;

          var fila = '<tr>' +
            '<td>' + valorTexto(p.nombre_profesion) + '</td>' +
            '<td>' + valorTexto(p.nombre_especialidad) + '</td>' +
            '<td>' + valorTexto(p.colegiatura) + '</td>' +
            '<td>' + valorTexto(p.rne) + '</td>' +
            '</tr>';

          $tabla.append(fila);
        }
      });

      if (!hayFilas) {
        $tabla.append('<tr><td colspan="4" class="text-center">No hay profesiones adicionales</td></tr>');
      }
    } else {
      $tabla.append('<tr><td colspan="4" class="text-center">No hay profesiones extras registradas</td></tr>');
    }

            
    $('#modal-cargo').text(valorTexto(data.cargo));

    $('#modal-ipress').text(valorTexto(data.ipress));
    $('#modal-unidad').text(valorTexto(data.unidad));
    $('#modal-subunidad').text(valorTexto(data.subUnidad));

    $('#modal-loading').hide();
    $('#modal-content-data').fadeIn(200);

    $('#modal-servicio-atencion').text(data.servicio_atencion && data.servicio_atencion.length > 0 ? data.servicio_atencion.join(' / ') : '-');
    $('#modal-firma').text(data.nombre_firma && data.nombre_firma.trim() !== '' ? 'SI' : 'NO');

    if (data.flag_rrhh == 1) {
      $('#fila_rrhh').show();
    } else {
      $('#fila_rrhh').hide();
    }  
  }
});
</script>
@endsection

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Modulo Personal</h3> 
        </div>
        <div class="panel-body">
          
          <!-- Pestañas -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
              <a href="#editarPersonal" aria-controls="editarPersonal" role="tab" data-toggle="tab">EDITAR PERSONAL</a>
            </li>
            <li role="presentation">
              <a href="#programacionAsistencial" aria-controls="programacionAsistencial" role="tab" data-toggle="tab">PROGRAMACIÓN ASISTENCIAL</a>
            </li>
          </ul>
          
          <!-- Contenido de las pestañas -->
          <div class="tab-content">
            <!-- Pestaña EDITAR PERSONAL -->
            <div role="tabpanel" class="tab-pane active" id="editarPersonal">
              <form method="post" action="{{ route('update_medico_saenz')}}" id="frmlotizar" name="frmlotizar" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h5 class="panel-title">INFORMACION PERSONAL</h5> 
                  </div>
                  <div class="panel-body">

                    <input id="id_medico" name="id_medico" value="{{$medico->id}}" type="hidden">
                    <input id="id_farmacia" name="id_farmacia" value="{{$id_farmacia}}" type="hidden">
                    
                    <div class="row" style="margin-top:10px">
                      <div class="col-md-1">
                        <label>Condición<span style="color:red;">*</span></label>
                      </div>
                      <div class="col-md-3" data-required-radio>
                        <input class="form-check-input" value="1" type="radio"  {{ $medico->condicion == 1 ? 'checked=checked' : '' }} name="condicion" id="civil">
                        <label class="form-check-label" for="civil">CIVIL</label>

                        <input class="form-check-input" value="2" type="radio" {{ $medico->condicion == 2 ? 'checked=checked' : '' }} name="condicion" id="policial">
                        <label class="form-check-label" for="policial">POLICIAL</label>
                      </div>
                      <div class="col-md-2">
                        <label for="dni">N° de documento</label>
                      </div>
                      <div class="col-md-2">
                        <input value="{{$medico->dni}}" id="dni" name="dni" class="form-control input-sm" readonly="" onkeypress="return IsNumber(event)">
                      </div>
                      <div class="col-md-2">
                        <label>Tipo Documento</label>
                      </div>
                      <div class="col-md-2">
                        <input value="{{$tipo_documento_text}}" id="tipo_documento" name="tipo_documento" class="form-control input-sm" readonly="" onkeypress="return IsNumber(event)">
                        <input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{$tipo_documento_id}}">
                      </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                      <div class="col-md-1">
                          <label class="form-label">Grado<span id="gradorequired" style="color:red;" hidden>*</span></label>
                      </div>
                      <div class="col-md-3">
                          <select class="form-control input-sm" name="id_grado" id="id_grado" disabled>
                            <option value="">SELECCIONE</option>
                            @foreach ($grados_lista as $gl)
                            <option value="{{ $gl->id }}" <?php if($gl->id == $medico->id_grado) echo "selected='selected'"; ?>>
                              {{ $gl->descripcion }}
                            </option>  
                            @endforeach
                          </select>
                          <span id="errorSelect" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                            Debe seleccionar un grado
                          </span>
                      </div>
                      <div class="col-md-1">
                        <label class="form-label" for="txtNroCip" style="font-size: 13px;">N° de CIP<span id="ciprequired" style="color:red;" hidden>*</span></label>
                      </div>
                      <div class="col-md-3">
                        <input type="text" name="txtNroCip" id="txtNroCip" class="form-control input-sm solo-numeros" value="{{ $medico->cip }}" maxlength="8">
                        <span id="errorTexto" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Debe ingresar número CIP
                        </span>
                      </div>
                    </div> 

                    <div class="row" style="margin-top:10px">
                      
                      <div class="col-md-3">
                        <label>Nombres<span style="color:red;">*</span></label>
                        <input type="text" value="{{$medico->nombre}}" class="form-control input-sm solo-letras text-uppercase min-2" id="nombre" name="nombre" maxlength="50" data-required="true">
                        <span id="errorNombres" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Debe ingresar nombre(s)
                        </span>
                      </div>
                      <div class="col-md-2">
                        <label>Ap. Paterno<span style="color:red;">*</span></label>
                        <input type="text" value="{{$medico->paterno}}" class="form-control input-sm solo-letras text-uppercase min-2" id="paterno" name="paterno" maxlength="50" data-required="true">
                        <span id="errorPaterno" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Debe ingresar ap. paterno
                        </span>
                      </div>
                      <div class="col-md-3">
                        <label>Ap. Materno<span style="color:red;">*</span></label>
                        <input type="text" value="{{$medico->materno}}" class="form-control input-sm solo-letras text-uppercase min-2" id="materno" name="materno" maxlength="50" data-required="true">
                        <span id="errorMaterno" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Debe ingresar ap. materno
                        </span>
                      </div>
                      <div class="col-md-2" style="position: relative; height: 75px;">
                        <div>
                          <label for="fechaNacimiento">Fecha de nacimiento<span style="color:red;">*</span></label>
                          <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control input-sm" value="{{ $medico->fecha_nacimiento }}" data-required="true">
                          <span id="errorFechaNac" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                            Ingrese fecha de nac.
                          </span>
                          <span id="errorFechaNacMenorEdad" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                            No es mayor de edad
                          </span>
                        </div>
                      </div>
                      <div class="col-md-2" style="position: relative; height: 75px;">
                        <div>
                          <label>Edad</label>
                          <input type="text" name="edad" id="edad" class="form-control input-sm" style="" readonly>
                        </div>
                      </div>
                      
                    </div>

                    <div class="row">
                      <div class="col-md-2" style="position: relative; height: 60px;">
                        <div style="position: absolute; bottom: 0;">
                          <label>Sexo<span style="color:red;">*</span></label>
                          <select class="form-control input-sm" name="id_sexo" id="id_sexo" data-required="true">
                            <option value="">SELECCIONE</option>
                            <option value="M" {{ $medico->sexo == 'M' ? 'selected' : '' }} >MASCULINO</option>
                            <option value="F" {{ $medico->sexo == 'F' ? 'selected' : '' }}>FEMENINO</option>
                          </select>
                          <span id="errorSexo" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                            Ingrese sexo
                          </span>
                        </div>
                      </div>
                      <div class="col-md-2" style="position: relative; height: 60px;">
                        <div style="position: absolute; bottom: 0;">
                          <label>Estado Civil<span style="color:red;">*</span></label>
                          <select id="id_estadocivil" name="id_estadocivil" class="form-control input-sm" data-required="true">
                            <option value="">SELECCIONE</option>
                            @foreach($estado_civil as $ec)
                            <option value="{{$ec->id}}" <?php if($ec->id == $medico->id_estado_civil) echo "selected='selected'"; ?> >{{$ec->nombre}}</option>
                            @endforeach
                          </select>
                          <span id="errorEstadoCivil" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                            Ingrese estado civil
                          </span>
                        </div>
                      </div>
                      <div class="col-md-1" style="position: relative; height: 60px;">
                        <div style="position: absolute; bottom: 0;">
                          <label>N° de Hijos<span style="color:red;">*</span></label>
                          <input id="nro_hijos" name="nro_hijos" class="form-control input-sm" type="number" min="0" max="29" step="1" value="{{ $medico->nro_hijos }}" data-required="true">
                          <span id="errorNroHijos" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                            Falta hijos
                          </span>
                        </div>
                      </div>
                      <div class="col-md-2" style="position: relative; height: 60px;">
                        <div style="position: absolute; bottom: 0;">
                          <label>N° de Celular<span style="color:red;">*</span></label>
                          <input type="text" name="txtCelular" id="txtCelular" class="form-control input-sm solo-numeros min-8" value="{{ $medico->celular }}" maxlength="20" data-required="true">
                          <span id="errorCelular" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                            Debe ingresar celular
                          </span>
                        </div>
                      </div>
                      <div class="col-md-2" style="position: relative; height: 60px;">
                        <div style="position: absolute; bottom: 0;">
                          <label>Contacto de emergencia<span style="color:red;">*</span></label>
                          <input type="text" name="txtEmergencia" id="txtEmergencia" style="width: 153px" class="form-control input-sm solo-numeros min-8" value="{{ $medico->cel_emergencia }}" maxlength="20" data-required="true">
                          <span id="errorCelEmer" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                            Ingrese contacto emer.
                          </span>
                          <span id="errorCelEmerIguales" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                            Emerg. no es diferente a cel.
                          </span>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <label for="email">Correo Electronico Personal<span style="color:red;">*</span></label>
                        <input name="email" id="email" type="email" value="{{$medico->email}}" class="form-control input-sm" data-required="true">
                        <span id="errorEmail" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Debe ingresar email
                        </span>
                      </div>
                    </div>

                    <div class="row" style="margin-top:10px">
                      <div class="col-md-4">
                        <label for="email_inst">Correo Electronico Institucional<span style="color:red;">*</span></label>
                        <input name="email_inst" id="email_inst" type="email" value="{{ $medico->email_institucional }}" class="form-control input-sm" data-required="true">
                        <span id="errorEmailInstitucional" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Debe ingresar email institucional
                        </span>
                      </div>
                      <div class="col-md-8">
                        <label>Domicilio actual<span style="color:red;">*</span></label>
                        <input type="text" name="txtDomicilio" id="txtDomicilio" class="form-control input-sm text-uppercase min-10" value="{{ $medico->domicilio }}" maxlength="200" data-required="true">
                        <span id="errorDomicilio" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Ingrese domicilio
                        </span>
                      </div>
                    </div>

                    <div class="row" style="margin-top:10px; margin-bottom:10px">
                      <div class="col-md-4">
                        <label>Departamento<span style="color:red;">*</span></label>
                        <select class="form-control input-sm" name="id_departamento" id="id_departamento" data-required="true" onchange="getProvincia();">
                          <option>SELECCIONE</option>
                          @foreach ($departamento as $depto)
                            <option value="{{ $depto->id_departamento }}" <?php if($depto->id_departamento == $medico->id_departamento) echo "selected == 'selected'"; ?> onchange="getProvincia();">{{ $depto->descripcion }}</option>
                          @endforeach
                        </select>
                        <span id="errorDpto" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Seleccione departamento
                        </span>
                      </div>
                      <div class="col-md-4">
                        <label>Provincia<span style="color:red;">*</span></label>
                        <select class="form-control input-sm" name="id_provincia" id="id_provincia" data-required="true" onchange="getDistrito();">
                          <option value="">SELECCIONE</option>
                          @if($provincia != "")
                            @foreach($provincia as $e)
                            <option value="{{$e->id_provincia}}" @if ($e->id_provincia == $medico->id_provincia) selected @endif>
                              {{$e->descripcion}}
                            </option>
                            @endforeach
                          @endif
                        </select>
                        <span id="errorProvincia" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Seleccione provincia
                        </span>
                      </div>
                      <div class="col-md-4">
                        <label>Distrito<span style="color:red;">*</span></label>
                        <select class="form-control" name="id_distrito" id="id_distrito" data-required="true">
                          <option value="">SELECCIONE</option>
                          @if($distrito != "")
                            @foreach($distrito as $e)
                            <option value="{{$e->id_distrito}}" @if ($e->id_distrito == $medico->id_distrito) selected @endif>
                              {{$e->descripcion}}
                            </option>
                            @endforeach
                          @endif
                        </select>
                        <span id="errorDistrito" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Seleccione distrito
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h5 class="panel-title">INFORMACION LABORAL</h5> 
                  </div>
                  <div class="panel-body">

                    <div class="row" style="margin-top:10px">
                      <div class="col-md-1">
                        <label>Grupo de Trabajo<span style="color:red;">*</span></label>
                      </div>
                      <div class="col-md-3" data-required-radio>
                        <input class="form-check-input" value="1" type="radio" {{ $medico->grupo_trabajo == 1 ? 'checked=checked' : '' }} name="grupoTrabajo" id="asistencial" >
                        <label class="form-check-label" for="asistencial">ASISTENCIAL</label>

                        <input class="form-check-input" value="2" type="radio" {{ $medico->grupo_trabajo == 2 ? 'checked=checked' : '' }} name="grupoTrabajo" id="administrativo">
                        <label class="form-check-label" for="administrativo">ADMINISTRATIVO</label>
                      </div>
                      <div class="col-md-1">
                          <label class="form-label" for="txtInicioLabo" style="font-size: 13px;">Ingreso a la IPREES<span style="color:red;">*</span></label>
                      </div>
                      <div class="col-md-2">
                        <input type="date" name="txtInicioLabo" id="txtInicioLabo" class="form-control input-sm" value="{{ $medico->fecha_inicio }}" data-required="true">
                        <span id="errorInicioLabo" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Ingrese inicio lab.
                        </span>
                        <span id="errorInicioLaboMenorHoy" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Debe ser menor a hoy
                        </span>
                      </div>
                      <div class="col-md-1 text-right" style="margin: 0; padding: 0;">
                        <input type="checkbox" id="chk_cese_lab" class="styled-checkbox">
                      </div>
                      <div class="col-md-1">
                        <label class="form-label" for="txtCeseLab">Cese de Labores<span id="ceselaboresrequired" style="color:red;" hidden>*</span></label>
                      </div>
                      <div class="col-md-3">
                        <input type="date" name="txtCeseLab" id="txtCeseLab" class="form-control input-sm"  value="{{ $medico->fecha_cese }}">
                        <span id="errorCeseLab" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Debe ingresar una fecha de cese
                        </span>
                        <span id="errorCeseLabMayInicio" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Debe ser mayor al inicio
                        </span>
                      </div>
                    </div> 

                    <div class="row" style="margin-top:5px">
                      
                      <div class="col-md-4" style="margin-top:10px">
                        <label>Situacion Actual<span style="color:red;">*</span></label>
                        <select class="form-control input-sm" name="id_situacion_actual" id="id_situacion_actual" data-required="true">
                          <option value="">SELECCIONE</option>
                          @foreach ($situacion_actual as $sa)
                          <option value="{{ $sa->id }}" <?php if ($sa->id == $medico->id_situacion_personal) echo "selected='selected'"; ?>>
                            {{ $sa->descripcion }}
                          </option>
                          @endforeach
                        </select>
                        <span id="errorSitAct" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Ingrese sit. actual
                        </span>
                      </div>
                      <div class="col-md-4" style="margin-top:10px">
                        <label>Condicion Laboral<span style="color:red;">*</span></label>
                        <select class="form-control input-sm" name="id_condicion_laboral" id="id_condicion_laboral" data-required="true">
                          <option value="">SELECCIONE</option>
                          @foreach ($condicion_laboral as $cl)
                          <option value="{{ $cl->id }}" <?php if($cl->id == $medico->id_condicion_laboral) echo "selected='selected'"; ?>>
                            {{ $cl->descripcion }}  
                          </option>
                          @endforeach
                        </select>
                        <span id="errorCondicionLab" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Ingrese condicion lab.
                        </span>
                      </div>
                      <div class="col-md-4" style="margin-top:10px">
                        <label>Cargo<span style="color:red;">*</span></label>
                        <input id="cargo" name="cargo" type="text" value="{{ $medico->cargo }}" class="form-control input-sm text-uppercase min-5" maxlength="100" data-required="true">
                        <span id="errorCargo" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Ingrese cargo
                        </span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4" style="margin-top:15px">
                        <label for="id_profesion">Profesi&oacute;n<span style="color:red;">*</span></label>
                        <select name="id_profesion" id="id_profesion" class="form-control input-sm">
                          <option value="">SELECCIONE</option>
                          @foreach($profesion as $e)
                          <option value="{{$e->id}}">
                            {{$e->nombre}}</option>
                          @endforeach
                        </select>
                        <span id="errorProfesion" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Ingrese profesion
                        </span>
                      </div>
                      <div class="filtroProfesion col-md-4" style="margin-top:15px">
                        <label>Especialidad del personal de salud<span style="color:red;">*</span></label>
                        <select name="id_especializacion" id="id_especializacion" class="form-control input-sm" required="">
                          <option value="">SELECCIONE</option>
                          @foreach($especializaciones as $esp)
                          <option value="{{$esp->id}}">{{$esp->nombre}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="filtroProfesion col-md-4" style="margin-top:15px">
                        <label>Colegio Profesional<span style="color:red;">*</span></label>
                        <select id="id_colegio_profesional" name="id_colegio_profesional" class="form-control input-sm" required="">
                          <option value="">SELECCIONE</option>
                          @foreach($colegio_profesional as $col)
                          <option value="{{$col->id}}">{{$col->descripcion}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="filtroProfesion col-md-2" style="margin-top:20px">
                        <label>N° de Colegiatura<span style="color:red;">*</span></label>
                        <input id="colegiatura" name="colegiatura" value="" class="form-control input-sm solo-numeros min-6" maxlength="8">
                      </div>
                      <div class="filtroProfesion col-md-2" style="margin-top:20px">
                        <label>N° de Especialidad<span style="color:red;">*</span></label>
                        <input id="rne" name="rne" type="text" placeholder="" class="form-control input-sm solo-numeros" maxlength="8">
                      </div>
                      <div class="filtroProfesion col-md-2" style="margin-top:20px">
                        <label>Estado de Colegiatura<span style="color:red;">*</span></label>
                        <select class="form-control input-sm" id="slcEstadoColegiatura">
                          <option value="">Seleccionar</option>
                          <option value="1" >HABILITADO</option>
                          <option value="0">INHABILITADO</option>
                        </select>
                      </div>
                      <div class="filtroProfesion col-md-2" style="margin-top:20px">
                        <label for="txtInicioHabilidad" style="font-size:12px;">Fecha de inicio de habilidad<span id="fechainiciorequired" style="color:red;" hidden>*</span></label>
                        <input name="txtInicioHabilidad" id="fechInicioHab" type="date" @if($medico->fecha_inicio_habilidad) value="{{ $medico->fecha_inicio_habilidad }}" @endif {{ $medico->estado == 0 ? 'disabled' : '' }} class="form-control input-sm">
                        <span id="errorInicioHab" style="display:none;color:#E55050;font-size:14px;">
                          Ingresar campo
                        </span>
                        <span id="errorInicioHabHoy" style="display:none;color:#E55050;font-size:14px;">
                          Debe ser menor a hoy
                        </span>
                      </div>
                      <div class="filtroProfesion col-md-2" style="margin-top:20px">
                        <label for="fechFinHab" style="font-size:11px;">Fecha de termino de habilidad<span id="fechaterminorequired" style="color:red;" hidden>*</span></label>
                        <input name="fechFinHab" id="fechFinHab" type="date" @if($medico->fecha_termino_habilidad) value="{{ $medico->fecha_termino_habilidad }}" @endif {{ $medico->estado == 0 ? 'disabled' : '' }} class="form-control input-sm">
                        <span id="errorFinHab" style="display:none;color:#E55050;font-size:14px;">
                          Ingresar campo
                        </span>
                        <span id="errorFinHabInicio" style="display:none;color:#E55050;font-size:14px;">
                          Debe ser mayor a inicio hab.
                        </span>
                      </div>
                      <div class="col-md-2" style="margin-top:45px">
                        <button type="button" id="addRowProfesion" class="btn btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-plus"></i>AGREGAR PROFESION</button>            
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12" style="text-align: left; font-size: 18px; margin-top:15px; margin-bottom: 10px;">
                      <b>Profesiones<span style="color:red;">*</span></b>
                      </div>
                    </div>
                    <span id="errorTabla" style="display:none;color:#E55050;font-size:14px;">
                      Debe agregar al menos un registro.
                    </span>
                    <div class="row">
                      <div class="col-md-12" style="padding-top:15px; padding-bottom:15px; border: 1px solid gray; border-radius:6px; margin-bottom: 10px; margin-left: 15px; width:90%">
                        <table id="tblEspecialidades" class="display table table-condensed" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Profesión</th>
                              <th>Especialidad</th>
                              <th class="text-left">N° de Colegiatura</th>
                              <th class="text-left">N° de Especialidad</th>
                              <th class="text-center">Activo</th>
                              <th class="text-center">Eliminar</th>
                            </tr>
                          </thead>
                          <tbody id="tblProfesionBody" data-especialidades='{{ json_encode($especialidades_array) }}'>
                          </tbody>
                        </table>
                      </div>
                    </div>
                      
                    <div class="row" style="margin-top:10px; margin-bottom:10px">
                      <div class="col-md-4">
                        <label>Establecimiento<span style="color:red;">*</span></label>
                        <select name="id_establecimiento" id="id_establecimiento" class="form-control " data-required="true" style="width:100%">
                          <option value="">SELECCIONE</option>
                          @foreach($establecimiento_data as $ed)
                          <option value="{{$ed->id}}" @if ($ed->id == $medico->id_establecimiento) selected="selected" @endif >
                            {{$ed->nombre}}
                          </option>
                          @endforeach
                        </select>
                        <span id="errorEstablecimiento" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Seleccione establecimiento
                        </span>
                      </div>
                      <div class="col-md-4">
                        <label>Unidad<span style="color:red;">*</span></label>
                        <select class="form-control" name="id_unidad" id="id_unidad" onchange="getDivisionSubUnidad();" data-required="true">
                          <option value="">SELECCIONE</option>
                          @foreach ($unidad_lista as $ul)
                          <option value="{{ $ul->id }}" <?php if($ul->id == $medico->id_unidad) echo "selected == 'selected'"; ?>>
                            {{ $ul->nombre }}
                          </option>
                          @endforeach
                        </select>
                        <span id="errorUnidad" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Seleccione unidad
                        </span>
                      </div>
                      <div class="col-md-4">
                        <label>Sub Unidad<span style="color:red;">*</span></label>
                        <select class="form-control" name="id_sub_unidad" id="id_sub_unidad" data-required="true">
                          <option value="">SELECCIONE</option>
                          @if($sub_unidad_lista != "")
                          @foreach($sub_unidad_lista as $sul)
                          <option value="{{$sul->id}}" @if ($sul->id == $medico->id_sub_unidad) selected @endif>
                            {{$sul->nombre}}
                          </option>
                          @endforeach
                          @endif
                        </select>
                        <span id="errorSubUnidad" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                          Seleccione sub-unidad
                        </span>
                      </div>
                    </div>

                  </div>    
                </div>
                <div class="clearfix"></div>

                @if(isset($flag_rrhh) && $flag_rrhh == 1)
                <div class="espacio_rrhh" id="espacio_rrhh">
                    <div class="col-md-4" style="margin-top:30px">
                      
                    </div>
                    <div class="col-md-4" style="margin-top:10px">
                  
                    </div>
                    <div class="col-md-4" style="margin-top:10px">
                      <label>Firma (Foto)</label>
                      <input type="file" name="archivo_firma" id="archivo_firma" class="form-control ">
                      @if($medico->nombre_firma != null)
                        <div style="text-align:center">
                          <img src="/images/profesionales/{{$medico->nombre_firma}}" alt="" width="220px" class="img-thumbnail">
                        </div>
                        @endif
                    </div>
                    <div class="col-md-12" style="margin-top:10px">
                      <label>Servicio de Atenci&oacute;n</label>
                      <select class="form-control js-example-basic-multiple" name="id_consultorio[]" required="" style="width: 100%" multiple="multiple">
                        @foreach($consultorio as $e)
                        <option value="{{$e->id}}" <?php if ($e->id == $medico->id_consultorio) echo "selected='selected'"; ?>>
                          {{$e->nombre}}
                        </option>
                        @endforeach
                      </select>
                      <p class="text-danger"><b>(*)</b> Si elimina un Servicio, se desactivará y se cancelará la programación de
                        sus atenciones a partir de mañana.</p>
                    </div>
                </div>
                @endif

                <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                  <a href="/gestion_medicos" class="form-control btn btn-danger">Cancelar</a>
                </div>

                <input type="hidden" id="especialidades_json" name="especialidades_json" value="">
                
                <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                  <button id="btnReg" class="form-control btn btn-success detalle-btn" type="button" tabindex="99921">Actualizar</button>
                </div>

              </form>
            </div>
            
            <!-- Pestaña PROGRAMACIÓN ASISTENCIAL -->
            <div role="tabpanel" class="tab-pane" id="programacionAsistencial">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Programación Asistencial</h3>
                </div>
                <div class="panel-body" style="padding:0; overflow-x: auto;">
                @include('frontend.programacion_asistencial.index')
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="detalleModal" tabindex="-1" role="dialog" aria-labelledby="detalleModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="detalleModalLabel">MODULO DE PERSONAL</h4>
      </div>
      <div class="modal-body">
        <div id="modal-loading" style="display:none; text-align:center; padding:20px;">
          <i class="fa fa-spinner fa-spin fa-2x"></i>
          <p>Cargando datos...</p>
        </div>
        <div id="modal-content-data" style="display:none;">
          <div class="row">
            <div class="col-md-6">
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h5 class="panel-tittle text-center text-verde-oscuro">INFORMACION PERSONAL</h5>
                </div>
                <div class="panel-body">
                  <table style="border-collapse: separate; border-spacing: 10px 5px;">
                    <tr>
                      <td><b>CONDICION</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-condicion"></span></td>
                    </tr>
                    <tr>
                      <td><b>GRADO</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-grado"></span></td>
                    </tr>
                    <tr>
                      <td><b>TIPO Y N° DE DOC</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-tipo-numdoc"></span></td>
                    </tr>
                    <tr>
                      <td><b>N° CIP</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-nrocip"></span></td>
                    </tr>
                    <tr>
                      <td><b>NOMBRES Y APELLIDOS</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-nombre"></span></td>
                    </tr>
                    <tr>
                      <td><b>FECHA DE NACIMIENTO</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-fechaNacimiento"></span></td>
                    </tr>
                    <tr>
                      <td><b>EDAD / SEXO</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-edad-sexo"></span></td>
                    </tr>
                    <tr>
                      <td><b>ESTADO CIVIL</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-estado-civil"></span></td>
                    </tr>
                    <tr>
                      <td><b>N° HIJOS</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-nro-hijos"></span></td>
                    </tr>
                    <tr>
                      <td><b>N° DE CELULAR</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-num-celular"></span></td>
                    </tr>
                    <tr>
                      <td><b>CONTACTO DE EMERGENCIA</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-num-emergencia"></span></td>
                    </tr>
                    <tr>
                      <td><b>CORREO PERSONAL</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-correo-electronico"></span></td>
                    </tr>
                    <tr>
                      <td><b>CORREO INSTITUCIONAL</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-correo-institucional"></span></td>
                    </tr>
                    <tr>
                      <td><b>DOMICILIO</b></td>
                      <td><b>:</b></td>
                      <td><span id="model-domicilio"></span></td>
                    </tr>
                    <tr>
                      <td><b>DEP/PROV/DIST</b></td>
                      <td><b>:</b></td>
                      <td><span id="dep-prov-dist"></span></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="panel panel-success" style="height:468px;">
                <div class="panel-heading">
                  <h5 class="panel-tittle text-center text-verde-oscuro">INFORMACION LABORAL</h5>
                </div>
                <div class="panel-body">
                  <table style="border-collapse: separate; border-spacing: 10px 5px;">
                    <tr>
                      <td><b>GRUPO DE TRABAJO</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-grupo-trabajo"></span></td>
                    </tr>
                    <tr>
                      <td><b>F DE INICIO Y FIN DE LABORES</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-fecha-iniciofin-laboral"></span></td>
                    </tr>
                    <tr>
                      <td><b>SITUACION ACTUAL</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-situacion-actual"></span></td>
                    </tr>
                    <tr>
                      <td><b>CONDICION LABORAL</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-condicion-laboral"></span></td>
                    </tr>
                    <tr>
                      <td><b>PROFESION</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-profesion"></span></td>
                    </tr>
                    <tr>
                      <td><b>ESPECIALIDAD</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-especializacion"></span></td>
                    </tr>
                    <tr>
                      <td><b>COLEGIO PROFESIONAL</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-col-profesional"></span></td>
                    </tr>
                    <tr>
                      <td><b>N° CMP / N° RNE</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-ncmp-nrne"></span></td>
                    </tr>
                    <tr>
                      <td><b>ESTADO DE COLEGIATURA</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-estado-colegiatura"></span></td>
                    </tr>
                    <tr>
                      <td><b>F. INICIO Y FIN DE HABILIDAD</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-iniciofin-habilidad"></span></td>
                    </tr>
                    <tr>
                      <td><b>CARGO</b></td>
                      <td><b>:</b></td>
                      <td><span id="modal-cargo"></span></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h5 class="panel-title text-center text-verde-oscuro">OTRAS PROFESIONES</h5>
                </div>
                <div class="panel-body">
                  <table class="table w-100">
                    <thead>
                      <tr>
                        <th>Profesion</th>
                        <th>Especialidad</th>
                        <th>N° Colegiatura</th>
                        <th>N° de Especialidad</th>
                      </tr>
                    </thead>
                    <tbody id="tabla-profesiones">

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h5 class="panel-title text-center text-verde-oscuro">LUGAR DE TRABAJO</h5>
                </div>
                <div class="panel-body">
                  <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                      <td style="width: 182px; height: 25px;">
                        <b>ESTABLECIMIENTO</b>
                      </td>
                      <td style="width: 5px; height: 25px;">
                        <b>:</b>
                      </td>
                      <td style="width: 435px; height: 25px; padding:3px;">
                        <span id="modal-ipress"></span>
                      </td>
                      <td style="width: 200px; height: 25px;">
                        <b>UNIDAD</b>
                      </td>
                      <td style="width: 5; height: 25px;">
                        <b>:</b>
                      </td>
                      <td style="width: 360px; height: 25px; padding:3px;">
                        <span id="modal-unidad"></span>
                      </td>
                    </tr>
                    <tr>
                      <td><b>SUB UNIDAD</b></td>
                      <td><b>:</b></td>
                      <td style="padding:3px;">
                        <span id="modal-subunidad"></span>
                      </td>
                    </tr>
                    <tr id="fila_rrhh" style="width: 182px; height: 25px; height:50px;">
                      <td><b>SERVICIO DE ATENCION</b></td>
                      <td><b>:</b></td>
                      <td>
                        <span id="modal-servicio-atencion"></span>
                      </td>
                      <td><b>FIRMA</b></td>
                      <td><b>:</b></td>
                      <td>
                        <span id="modal-firma"></span>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGenerarReporte" onclick="submitFrm()">Actualizar</button>
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection