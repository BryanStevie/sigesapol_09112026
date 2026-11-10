@extends('frontend.layouts.master')
@section('renderjs')
<style>
  #detalleModal .modal-dialog {
    width: 95%;
    max-width: 1200px;
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

function validateFrm() {
  let firstError = null;
  let hasErrors = false;
  let faltaRadioProfesion = false;

  $('#frmMedico input, #frmMedico select, #frmMedico [data-required-radio]')
      .removeClass('error-field');

  $('#errorSelect').hide();
  $('#errorTexto').hide();

  $('#frmMedico [data-required="true"]').each(function() {

    const type = $(this).attr('type');
    const tag = $(this).prop('tagName').toLowerCase();
    const value = $(this).val()?.trim() ?? "";

    if (tag === 'input') {
      if (type === 'text' || type === 'date' || type === 'email' || type === 'number') {
        if (value === '') {
          $(this).addClass('error-field');
          if (!firstError) {
            firstError = this;
          }
          hasErrors = true;
        }
      } 
      else if (type === 'file') {
        if ($(this)[0].files.length === 0) {
          $(this).addClass('error-field');
          if (!firstError) {
            firstError = this;
          } 
          hasErrors = true;
        }
      }
    }

    if (tag === 'select') {
      if (value === '') {
        $(this).addClass('error-field');
        if (!firstError) firstError = this;
        hasErrors = true;
      }
    }

    if (this.id === 'id_grado') {
      if (value === '') {
        $('#errorSelect').show();
      } else $('#errorSelect').hide();
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

    if (this.id === 'nro_hijos') {
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

    if (this.id === 'email_inst') {
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
      if (celular !== "" && emergencia !== "" && celular === emergencia) {
        $('#errorCelEmerIguales').show();
        $(this).addClass('error-field');
        if (!firstError) firstError = this;
        hasErrors = true;
      } else {
        $('#errorCelEmerIguales').hide();
      }
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
        if (!firstError) firstError = this;
        hasErrors = true;
      } else {
        $('#errorFechaNacMenorEdad').hide();
      }
    }
    
    let inicioLabo = $('#txtInicioLabo').val();
    let fechaInicio = new Date(inicioLabo);
    fechaInicio.setHours(0,0,0,0);
    
    if (this.id === 'txtInicioLabo') {
      if (fechaInicio >= hoy) {
        $('#errorInicioLaboMenorHoy').show();
        $(this).addClass('error-field');
        if (!firstError) firstError = this;
        hasErrors = true;
      } else {
        $('#errorInicioLaboMenorHoy').hide();
      }
    }
  });

  $('#frmMedico input, #frmMedico select').not('#id_distrito, #id_especializacion, #id_colegio_profesional, #id_establecimiento').on('input change', function() {
    $(this).removeClass('error-field');

    if (this.id === 'id_grado') $('#errorSelect').hide();
    if (this.id === 'txtNroCip') $('#errorTexto').hide();
    if (this.id === 'paterno') $('#errorPaterno').hide();
    if (this.id === 'materno') $('#errorMaterno').hide();
    if (this.id === 'nombre') $('#errorNombres').hide();
    if (this.id === 'fechaNacimiento') $('#errorFechaNac').hide();
    if (this.id === 'id_estadocivil') $('#errorEstadoCivil').hide();
    if (this.id === 'nro_hijos') $('#errorNroHijos').hide();
    if (this.id === 'id_sexo') $('#errorSexo').hide();
    if (this.id === 'txtCelular') $('#errorCelular').hide();
    if (this.id === 'txtEmergencia') $('#errorCelEmer').hide();
    if (this.id === 'email') $('#errorEmail').hide();
    if (this.id === 'email_inst') $('#errorEmailInstitucional').hide();
    if (this.id === 'txtDomicilio') $('#errorDomicilio').hide();
    if (this.id === 'id_departamento') $('#errorDpto').hide();
    if (this.id === 'id_provincia') $('#errorProvincia').hide();
    if (this.id === 'txtInicioLabo') $('#errorInicioLabo').hide();
    if (this.id === 'id_situacion_actual') $('#errorSitAct').hide();
    if (this.id === 'id_condicion_laboral') $('#errorCondicionLab').hide();
    if (this.id === 'id_profesion') $('#errorProfesion').hide();
    if (this.id === 'cargo') $('#errorCargo').hide();
    if (this.id === 'id_unidad') $('#errorUnidad').hide();
    if (this.id === 'id_sub_unidad') $('#errorSubUnidad').hide();
    if (this.id === 'txtEmergencia') $('#errorCelEmerIguales').hide();
    if (this.id === 'txtInicioLabo') $('#errorInicioLaboMenorHoy').hide();
    if (this.id === 'fechaNacimiento') $('#errorFechaNacMenorEdad').hide();
  });

  $('#id_distrito, #id_especializacion, #id_colegio_profesional, #id_establecimiento').on('select2:select', function() {
    $(this).next('.select2-container').removeClass('error-field');
    
    if (this.id === 'id_distrito') $('#errorDistrito').hide();
    if (this.id === 'id_establecimiento') $('#errorEstablecimiento').hide();
  });

  var est = $('#id_establecimiento').val();

  if (!est) {
    $('#id_establecimiento').next('.select2-container').addClass('error-field');
    if (!firstError) {
      firstError = $('#id_establecimiento')[0];
    }
    hasErrors = true;
  } else {
    $('#id_establecimiento').next('.select2-container').removeClass('error-field');
  }

  const chkCeseLab = document.getElementById('chk_cese_lab');
  const txtCeseLab = document.getElementById('txtCeseLab');

  if (chkCeseLab.checked) {
    if (txtCeseLab.value.trim() === '') {
      $('#errorCeseLab').show();
      $(txtCeseLab).addClass('error-field');
      if (!firstError) firstError = txtCeseLab;
      hasErrors = true;
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
      if (!firstError) firstError = txtCeseLab;
      hasErrors = true;
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

  if (!$('head style:contains(".error-field")').length) {
    $('<style>')
      .prop('type', 'text/css')
      .html('.error-field { border: 1px solid #E55050 !important; }')
      .appendTo('head');
  }

  const tablaProfesion = document.getElementById('tblEspecialidades');
  const filas = tablaProfesion.getElementsByTagName('tbody')[0]?.getElementsByTagName('tr') || [];

  if (filas.length === 0) {
    $('#errorTabla').show();
    $('#tblEspecialidades').addClass('error-table');

    if (!firstError) {
      $('html, body').animate({
        scrollTop: $('#tblEspecialidades').offset().top - 120
      }, 500);
    }

    hasErrors = true;
  } else {
    $('#errorTabla').hide();
    $('#tblEspecialidades').removeClass('error-table');

    const radioSeleccionado = tablaProfesion.querySelector('input[type="radio"]:checked');
    if (!radioSeleccionado) {
      faltaRadioProfesion = true;
      hasErrors = true;
    }
  }

  if (firstError) {
    $(firstError).focus();
  }

  if (faltaRadioProfesion) {
    setTimeout(function() {
      alert('Debe seleccionar una especialidad principal antes de guardar.');
    }, 100);
  }

  return !hasErrors;
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

$('#txtCeseLab').on('input change', function() {
    $(this).removeClass('error-field');
    $('#errorCeseLab').hide();
});

document.getElementById("frmMedico").addEventListener("submit", function(e) {
  let valido = true;

  if (!select.disabled && select.value === "") {
    errorSelect.style.display = "inline";
    valido = false;
  } else {
    errorSelect.style.display = "none";
  }

  if (!texto.disabled && texto.value.trim() === "") {
    errorTexto.style.display = "inline";
    valido = false;
  } else {
    errorTexto.style.display = "none";
  }

  if (!valido) e.preventDefault();
});

function submitFrm() {
  $('#especialidades_json').val(JSON.stringify(especialidades));
  $("#frmMedico").submit();
}

document.getElementById("frmMedico").addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
    }
});

function AddFila() {

  var newRow = "";

  var ind = $('#example tbody tr').length;

  var item_servicio = "";
  $('#id_consultoriotemp option').each(function() {
    item_servicio += "<option value=" + $(this).val() + ">" + $(this).html() + "</option>"
  });
  newRow += '<tr>';
  newRow += '<td class="col-sm-9" style="padding:6px"><select class="form-control consultorio" id="consultorio' + ind +
    '" name="id_consultorio[]">' + item_servicio + '</select></td>';
  newRow +=
    '<td class="col-sm-3"><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';
  $('#example tbody').append(newRow);
  $("#consultorio" + ind).select2({
    max_selected_options: 4
  });

  var nind = 0;
  $("#consultorio" + ind).on("change", function(e) {
    var flagx = 0;
    cmb = $(this);
    id_consultorio = $("#consultorio" + ind).val();

    $('.consultorio').each(function() {
      var ind_tmp = $(this).val();
      if ($(this).val() == id_consultorio) flagx++;
    });

    if (flagx > 1) {
      bootbox.alert("El servicio de atención, ya ha sido seleccionado");
      $("#consultorio" + ind).val("").trigger("change");
      return false;
    }
  });
}

function getProvincia() {
  var newOption = "";
  newOption = "<option value=''>SELECCIONE</option>";
  $('#id_provincia').html(newOption);
        
  var Departamento = $('#id_departamento').val();

  var $provincia = $('#id_provincia');
  var $distrito = $('#id_distrito');
  var departamentoId = $('#id_departamento').val();

  $distrito.html("<option value=''>SELECCIONE</option>");
  $distrito.prop('disabled', true);

  $provincia.html("<option value=''>CARGANDO...</option>");
  $provincia.prop('disabled', true);

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
  
  if ($distrito.hasClass('select2-hidden-accessible')) {
    $distrito.trigger('change');
  }

  if (!provinciaId) {
    $distrito.html("<option value=''>SELECCIONE</option>");
    $distrito.prop('disabled', false);
    
    if ($distrito.hasClass('select2-hidden-accessible')) {
      $distrito.trigger('change');
    }
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
      
      if ($distrito.hasClass('select2-hidden-accessible')) {
        $distrito.trigger('change');
      }
    },
    error: function() {
      $distrito.html("<option value=''>ERROR AL CARGAR</option>");
      
      if ($distrito.hasClass('select2-hidden-accessible')) {
        $distrito.trigger('change');
      }
    },
    complete: function() {
      $distrito.prop('disabled', false);
    }
  });
}

$(document).ready(function() {

  $("#id_establecimiento").select2({
    width: 'resolve',
    max_selected_options: 4
  });

  $("#id_establecimiento").trigger('change');

  $('#addRow').on('click', function() {
    AddFila();
  });

  $('#example tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  AddFila();

  function actualizarFechasHabilidad() {
    const fechInicioHabilidad = document.getElementById("fechInicioHab");
    const fechaFinHabilidad = document.getElementById("fechFinHab");

    fechInicioHabilidad.value = "";
    fechaFinHabilidad.value = "";

    var estado = $('#slcEstadoColegiatura').val();
    if (estado === "0") {
      $('.fechasHabilidad').hide();
    } else {
      $('.fechasHabilidad').show();
    }
  }

  $('#slcEstadoColegiatura').on('change', actualizarFechasHabilidad);
  actualizarFechasHabilidad();
});

const radio1 = document.getElementById('civil');
const radio2 = document.getElementById('policial');
const select = document.getElementById('id_grado');
const nroCip = document.getElementById('txtNroCip');
const errorSelect = document.getElementById('errorSelect');
const errorTexto = document.getElementById('errorTexto');

document.querySelectorAll('input[name="condicion"]').forEach(radio => {
  radio.addEventListener('change', () => {

    if (radio2.checked) {
      select.disabled = false;
      nroCip.disabled = false;

      select.dataset.required = "true";
      nroCip.dataset.required = "true";

      document.getElementById("condicionpolicialgrado").hidden = false;
      document.getElementById("condicionpolicialcip").hidden = false;
    } else {
      select.disabled = true;
      nroCip.disabled = false;

      delete select.dataset.required;
      delete nroCip.dataset.required;

      select.value = "";
      nroCip.value = "";

      errorSelect.style.display = "none";
      errorTexto.style.display = "none";

      select.classList.remove("error-field");
      nroCip.classList.remove("error-field");

      document.getElementById("condicionpolicialgrado").hidden = true;
      document.getElementById("condicionpolicialcip").hidden = true;
    }
  });
});


const inputFecha = document.getElementById('fechaNacimiento');
const inputEdad = document.getElementById('edad');

inputFecha.addEventListener('change', () => {
  const fechaNacimiento = new Date(inputFecha.value);
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
});

const selectProfesion= document.getElementById("id_profesion");

selectProfesion.addEventListener("change", function () {
  const selectEstadoColegiatura = document.getElementById("slcEstadoColegiatura");
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


const especialidades = [];

function validarBotonAgregar() {
  const select_profesion = document.getElementById('id_profesion');
  const nombre_profesion = select_profesion.options[select_profesion.selectedIndex].text;
  const boton = document.getElementById('addRowProfesion');

  if (especialidades.length === 0) {
    boton.disabled = false;
    return;
  }
}

function checkTablaEspecialidades() {
    especialidadesActuales = especialidades.length;

    if (especialidadesActuales > 0) {
        $('#tblEspecialidades').removeClass('error-table');
        $('#errorTabla').hide();
    }
}

document.getElementById('addRowProfesion').addEventListener('click', () => {

  const getVal = id => document.getElementById(id).value;
  const getTxt = id => {
    const el = document.getElementById(id);
    return el.options[el.selectedIndex]?.text || "";
  };

  const id_profesion           = getVal('id_profesion');
  const nombre_profesion       = getTxt('id_profesion');
  const id_especializacion     = getVal('id_especializacion');
  const especialidad           = getTxt('id_especializacion');
  const id_colegio_profesional = getVal('id_colegio_profesional');
  const nom_colegio            = getTxt('id_colegio_profesional');
  const nro_colegiatura        = getVal('colegiatura');
  const nro_especialidad       = getVal('rne');
  const id_estado_colegiatura  = getVal('slcEstadoColegiatura');
  const estado_colegiatura     = getTxt('slcEstadoColegiatura');
  const fechaInicioHabilidad   = getVal('fechInicioHab');
  const fechaFinHabilidad      = getVal('fechFinHab');

  const profesionesSimple = ["AUX", "AX.", "CHO", "TÉC", "OTR", "POL"];
  const filtroProfesion = nombre_profesion.substring(0, 3);
  const esSimple = profesionesSimple.includes(filtroProfesion);

  if (!id_profesion)
     return alert("Debe seleccionar profesión.");

  if (esSimple) {

    const existeAux = especialidades.some(e =>
        e.id_profesion === id_profesion
      );
    if (existeAux)
        return alert(`Profesion duplicada (AUX/TEX/AX/OTROS). Ya existe esta profesion en la tabla: ${nombre_profesion}.`);

    especialidades.push({
      id_profesion, nombre_profesion,
      id_especializacion: null,
      especialidad: null,
      id_colegio_profesional: null,
      nom_colegio: null,
      nro_colegiatura: null,
      nro_especialidad: null,
      id_estado_colegiatura: null,
      estado_colegiatura: null,
      fechaInicioHabilidad: null,
      fechaFinHabilidad: null,
      especialidadPrincipal: false,
      estado: 1
    });

    validarBotonAgregar();
  }

  else {

    if (!id_especializacion)
      return alert("Debe seleccionar especialización.");

    if (!id_colegio_profesional)
      return alert("Debe seleccionar colegio profesional.");

    const esColegioLibre = id_colegio_profesional == "1";

    if (esColegioLibre) {

      especialidades.push({
        id_profesion,
        nombre_profesion,
        id_especializacion,
        especialidad,
        id_colegio_profesional,
        nom_colegio,
        nro_colegiatura: null,
        nro_especialidad: null,
        id_estado_colegiatura: null,
        estado_colegiatura: null,
        fechaInicioHabilidad: null,
        fechaFinHabilidad: null,
        especialidadPrincipal: false,
        estado: 1
      });
    }

    else {

      if (!nro_colegiatura)
        return alert("Ingrese número de colegiatura.");

      if (!nro_especialidad)
        return alert("Ingrese número de especialidad.");

      if (!id_estado_colegiatura)
        return alert("Seleccione estado de colegiatura.");

      if (id_estado_colegiatura == 1) {
        if (!fechaInicioHabilidad || !fechaFinHabilidad)
          return alert("Complete fechas de habilidad.");

        if (fechaFinHabilidad < fechaInicioHabilidad)
          return alert("Fecha fin debe ser mayor a fecha inicio.");
      }

      const existe = especialidades.some(e =>
        e.nro_especialidad === nro_especialidad &&
        e.especialidad === especialidad
      );

      if (existe)
        return alert(`Especialidad duplicada. Ya existe la especialidad con número ${nro_especialidad}.`);

      especialidades.push({
        id_profesion,
        nombre_profesion,
        id_especializacion,
        especialidad,
        id_colegio_profesional,
        nom_colegio,
        nro_colegiatura,
        nro_especialidad,
        id_estado_colegiatura,
        estado_colegiatura,
        fechaInicioHabilidad: id_estado_colegiatura == 1 ? fechaInicioHabilidad : "",
        fechaFinHabilidad: id_estado_colegiatura == 1 ? fechaFinHabilidad : "",
        especialidadPrincipal: false,
        estado: 1
      });

    }

  }

  actualizarTabla();

  const resetInput = id => document.getElementById(id).value = "";

  resetInput("id_profesion");
  resetInput("colegiatura");
  resetInput("rne");
  resetInput("slcEstadoColegiatura")
  resetInput("fechInicioHab");
  resetInput("fechFinHab");

  $("#id_especializacion").val(null).trigger('change');
  $("#id_colegio_profesional").val(null).trigger('change');

});

$("#id_colegio_profesional").on("change", function () {
    
    let idColegio = $(this).val();

    const inputNroColegiatura = $("#colegiatura");
    const inputNroEspecialidad = $("#rne");
    const fechInicioHabilidad = $("#fechInicioHab");
    const fechaFinHabilidad = $("#fechFinHab");

    const selectEstadoColegiatura = $("#slcEstadoColegiatura");

    if (idColegio == "1") {
        inputNroColegiatura.prop("disabled", true).val("");
        inputNroEspecialidad.prop("disabled", true).val("");
        fechInicioHabilidad.prop("disabled", true).val("");
        fechaFinHabilidad.prop("disabled", true).val("");

        selectEstadoColegiatura.val(null).prop("disabled", true).trigger("change.select2");
    } else {
        inputNroColegiatura.prop("disabled", false);
        inputNroEspecialidad.prop("disabled", false);
        fechInicioHabilidad.prop("disabled", false);
        fechaFinHabilidad.prop("disabled", false);

        selectEstadoColegiatura.prop("disabled", false).trigger("change.select2");
    }

});

function actualizarTabla() {
  checkTablaEspecialidades();
  const tbody = document.querySelector("#tblEspecialidades tbody");

  const select_profesion = document.getElementById('id_profesion');
  const select_colegio_profesional = document.getElementById('id_colegio_profesional');
  const input_colegiatura = document.getElementById('colegiatura');
  const select_estado_colegiatura = document.getElementById('slcEstadoColegiatura');

  tbody.innerHTML = "";

  especialidades.forEach((item, index) => {
    const tr = document.createElement("tr");

    const tdNombreProfesion = document.createElement("td");
    tdNombreProfesion.textContent = item.nombre_profesion;
    tdNombreProfesion.className = "col-sm-3";

    const tdNombreEspecialidad = document.createElement("td");
    tdNombreEspecialidad.textContent = item.especialidad;
    tdNombreEspecialidad.className = "col-sm-3";

    const tdNroColegiatura = document.createElement("td");
    tdNroColegiatura.textContent = item.nro_colegiatura;
    tdNroColegiatura.className = "col-sm-3 text-left";

    const tdNroEspecialidad = document.createElement("td");
    tdNroEspecialidad.textContent = item.nro_especialidad;
    tdNroEspecialidad.className = "col-sm-3 text-left";

    const tdRadioEstado = document.createElement("td");
    const radio = document.createElement("input");
    radio.type = "radio";
    radio.name = "especialidadPrincipal";
    radio.checked = item.especialidadPrincipal === true;
    radio.addEventListener("change", () => {
      especialidades.forEach(obj => obj.especialidadPrincipal = false);
      item.especialidadPrincipal = true;
      actualizarTabla();
    });
    tdRadioEstado.appendChild(radio);
    tdRadioEstado.className = "text-center";

    const tdDelete = document.createElement("td");
    const btnDelete = document.createElement("button");
    btnDelete.textContent = "Eliminar";
    btnDelete.style = "margin-left:4px";
    btnDelete.className = "btn btn-danger deleteFila btn-xs";

    btnDelete.addEventListener("click", () => {
      especialidades.splice(index, 1);
      actualizarTabla();
      validarBotonAgregar();
    });

    tdDelete.appendChild(btnDelete);
    tdDelete.className = "text-center";
    tr.appendChild(tdNombreProfesion);
    tr.appendChild(tdNombreEspecialidad);
    tr.appendChild(tdNroColegiatura);
    tr.appendChild(tdNroEspecialidad);
    tr.appendChild(tdRadioEstado);
    tr.appendChild(tdDelete);
    tbody.appendChild(tr);
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

$(document).ready(function() {
    $('#id_distrito').select2({ width: '100%' });
    $('#id_especializacion').select2({  });
    $('#id_colegio_profesional').select2({ });
});

function generarData () {
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
    distrito: document.querySelector('#id_distrito option:checked')?.text || null,

    grupo_trabajo: parseInt(document.querySelector('input[name="grupoTrabajo"]:checked').value),
    fecha_inicio: document.getElementById('txtInicioLabo').value,
    fecha_cese: document.getElementById('txtCeseLab').value || null,
    id_situacion_personal: parseInt(document.getElementById('id_situacion_actual').value),
    situacionPersonal: document.querySelector('#id_situacion_actual option:checked')?.text || null,
    id_condicion_laboral: parseInt(document.getElementById('id_condicion_laboral').value),
    condicionLaboral: document.querySelector('#id_condicion_laboral option:checked')?.text || null,
    cargo: document.getElementById('cargo').value,
    id_establecimiento: parseInt(document.getElementById('id_establecimiento').value),
    ipress: document.querySelector('#id_establecimiento option:checked')?.text || null,
    id_unidad: parseInt(document.getElementById('id_unidad').value),
    unidad: document.querySelector('#id_unidad option:checked')?.text || null,
    id_sub_unidad: parseInt(document.getElementById('id_sub_unidad').value),
    subUnidad: document.querySelector('#id_sub_unidad option:checked')?.text || null,
    
    flag_rrhh: "{{ addslashes($flag_rrhh) }}",

    nombre_firma: document.getElementById('archivo_firma')?.value || null,
    profesiones: typeof especialidades !== 'undefined' ? especialidades : [], 
    servicio_atencion: typeof obtenerServiciosDeTabla === 'function' ? obtenerServiciosDeTabla() : []
  };

  return data;
}

function obtenerProfesionPrincipal (listaProfesiones) {
  let data = {};
  for (let i = 0; i < listaProfesiones.length; i++) {
    if (listaProfesiones[i].especialidadPrincipal == true) {
      data = {
        profesion: listaProfesiones[i].nombre_profesion || null,
        especialidad: listaProfesiones[i].especialidad || null,
        colegioProfesional: listaProfesiones[i].nom_colegio || null,
        colegiatura: listaProfesiones[i].nro_colegiatura || null,
        rne: listaProfesiones[i].nro_especialidad || null,
        id_estado_colegiatura: listaProfesiones[i].id_estado_colegiatura || null,
        fecha_inicio_habilidad: listaProfesiones[i].fechaInicioHabilidad || null,
        fecha_termino_habilidad: listaProfesiones[i].fechaFinHabilidad || null
      }
    }
  }
  return data;
}

$(document).on('click', '.detalle-btn', function(e) {

  if (!validateFrm()) {
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

    const edad = fechaNac !== '-' ? calcularEdad(new Date(data.fecha_nacimiento)) : '-';

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
        if (p.estado == 1 && p.especialidadPrincipal === false) {
          hayFilas = true;

          var fila = '<tr>' +
            '<td>' + valorTexto(p.nombre_profesion) + '</td>' +
            '<td>' + valorTexto(p.especialidad) + '</td>' +
            '<td>' + valorTexto(p.nro_colegiatura) + '</td>' +
            '<td>' + valorTexto(p.nro_especialidad) + '</td>' +
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

function obtenerServiciosDeTabla() {
    let servicios = [];
    
    let filas = document.querySelectorAll('#example tbody tr');

    filas.forEach(fila => {
        let descripcion = fila.querySelector('td:nth-child(1)').innerText.trim();

        if (descripcion) {
            servicios.push(descripcion);
        }
    });

    return servicios;
}

function calcularEdad(fechaNacimiento) {
    
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

    return `${años} años ${meses} meses ${dias} días`;
  }
</script>
@endsection
@section('content')
<pre id="json-output" style="display:none;"></pre>
<div class="container">
  <ul class="nav nav-pills" style="width: 250px; margin-bottom: 10px;">
    <li><a href="/gestion_medicos/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Personal de Salud</a>
    </li>
    <li class="active"><a href="/buscar_medico"><i class="fa fa-plus"></i> Nuevo </a></li>
  </ul>
  
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Modulo Personal</h3> 
        </div>

        @if(count($medico_registrado)>0)
        <div style="margin: 20px 20px 20px 20px">
          El Profesional Prescriptor <b>{{$medico_registrado->nombre}} {{$medico_registrado->paterno}}
            {{$medico_registrado->materno}}</b> identificado con DNI <b>{{$medico_registrado->dni}}</b> ya
          se encuentra registrado</b>, en estado
          <?php echo $medico_registrado->estado == "1" ? "<b>ACTIVO</b>" : "<b>INACTIVO</b>"; ?>.<br /><br />
          <a href="/buscar_medico" class="btn btn-default"><i class="fa fa-plus-circle"></i> Registrar otro
            prescriptor</a>
          <a class='btn btn-warning' href='/editar_medico_saenz/{{ $medico_registrado->id }}/{{ Auth::user()->id_farmacia }}'><i class='fa fa-edit'>Editar prescriptor</i></a>
        </div>
        @else
        <?php
        if ($existe == 1) {
          $nompersonal = $personal->nombre;
          $patpersonal = $personal->paterno;
          $matpersonal = $personal->materno;
          $colepersonal = $personal->colegiatura;
          $rnepersonal = $personal->rne;
          $readdatosper = " readonly";
        } else if ($existe == 2) {
          
          $nompersonal = $personal[0]->nomafiliado;
          $patpersonal = $personal[0]->apepatafiliado;
          $matpersonal = $personal[0]->apematafiliado;
          
          $colepersonal = "";
          $rnepersonal = "";
          $readdatosper = " readonly";
        } else if ($existe == 3) {
          $nompersonal = $personal["nombres"];
          $patpersonal = $personal["paterno"];
          $matpersonal = $personal["materno"];
          $colepersonal = "";
          $rnepersonal = "";
          $readdatosper = " readonly";
        } else { 
          $nompersonal = "";
          $patpersonal = "";
          $matpersonal = "";
          $colepersonal = "";
          $rnepersonal = "";
          $readdatosper = "";
        }
        ?>

        <div class="panel-body">
          <form id="frmMedico" class="form-horizontal" role="form" method="post" action="{{ route('store_medico_saenz') }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="panel panel-success">
              <div class="panel-heading">
                <h5 class="panel-title">INFORMACION PERSONAL</h5> 
              </div>
              <div class="panel-body">

                <div class="row" style="margin-top:10px">
                  <div class="col-md-1">
                    <label>Condición<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-md-3" data-required-radio>
                    <input class="form-check-input" type="radio" name="condicion" id="civil" value="1" {{ old('condicion', 'civil') == 'civil' ? 'checked' : '' }} checked>
                    <label class="form-check-label" for="civil">CIVIL</label>

                    <input class="form-check-input" type="radio" name="condicion" id="policial" value="2" {{ old('condicion') == 'policial' ? 'checked' : '' }} >
                    <label class="form-check-label" for="policial">POLICIAL</label>
                  </div>
                  <div class="col-md-2">
                    <label for="dni">N° de documento</label>
                  </div>
                  <div class="col-md-2">
                    <input id="dni" name="dni" type="text" value="{{$dni}}" class="form-control input-sm" required="" readonly="" onkeypress="return IsNumber(event)">
                    <input id="id_user" name="id_user" type="hidden" value="{{Auth::user()->id}}">
                  </div>
                  <div class="col-md-2">
                    <label for="tipo_documento">Tipo de documento</label>
                  </div>
                  <div class="col-md-2">
                    <input id="tipo_documento" name="tipo_documento" type="text" value="{{$tipo_documento_text}}" class="form-control input-sm" required="" readonly="">
                    <input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{$tipo_documento_id}}">
                  </div>
                </div>
                <div class="row" style="margin-top:15px">
                  <div class="col-md-1">
                    <label class="form-label">Grado<span id="condicionpolicialgrado" style="color:red;" hidden>*</span></label>
                  </div>
                  <div class="col-md-3">
                    <select class="form-control input-sm" id="id_grado" name="id_grado" disabled>
                      <option value="">SELECCIONE</option>
                      @foreach ($grados_lista as $gl)
                      <option value="{{ $gl->id }}">{{ $gl->descripcion }}</option>
                      @endforeach
                    </select>
                    <span id="errorSelect" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Debe seleccionar un grado
                    </span>
                  </div>
                  <div class="col-md-1">
                    <label class="form-label" for="txtNroCip" style="font-size: 13px;">N° de CIP</label>
                  </div>
                  <div class="col-md-3">
                    <input type="text" name="txtNroCip" id="txtNroCip" class="form-control input-sm solo-numeros" maxlength="8">
                    <span id="errorTexto" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Debe ingresar número CIP
                    </span>
                  </div>
                </div>

                <div class="row" style="margin-top:5px">
                  <div class="col-md-4">
                    <label>Nombres<span style="color:red;">*</span></label>
                    <input type="text" id="nombre" name="nombre" class="form-control input-sm solo-letras text-uppercase min-2" maxlength="50" data-required="true">
                    <span id="errorNombres" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Debe ingresar nombre(s)
                    </span>
                  </div>
                  <div class="col-md-2">
                    <label for="paterno">Ap. Paterno<span style="color:red;">*</span></label>
                    <input type="text" class="form-control input-sm solo-letras text-uppercase min-2" style="position: relative;" id="paterno" name="paterno" maxlength="50" data-required="true">
                    <span id="errorPaterno" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Debe ingresar ap. paterno
                    </span>
                  </div>
                  <div class="col-md-2">
                    <label for="materno">Ap. Materno<span style="color:red;">*</span></label>
                    <input type="text" class="form-control input-sm solo-letras text-uppercase min-2" id="materno" name="materno" maxlength="50" data-required="true">
                    <span id="errorMaterno" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Debe ingresar ap. materno
                    </span>
                  </div>
                  <div class="col-md-2" style="position: relative; height: 62px;">
                    <label for="fechaNacimiento" style="font-size:12px;">Fecha de nacimiento<span style="color:red;">*</span></label>
                    <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control input-sm" data-required="true">
                    <span id="errorFechaNac" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Ingrese fecha de nac.
                    </span>
                    <span id="errorFechaNacMenorEdad" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      No es mayor de edad
                    </span>
                  </div>
                  <div class="col-md-2" style="position: relative; height: 62px;">
                    <label for="edad" style="font-size:12px;">Edad</label>
                    <input type="text" name="edad" id="edad" class="form-control input-sm" readonly>
                  </div>
                </div>

                <div class="row" style="margin-top:8px">
                  <div class="col-md-2" style="position: relative; height: 60px;">
                    <div style="position: absolute; bottom: 0;">
                      <label for="id_sexo">Sexo<span style="color:red;">*</span></label>
                      <select class="form-control input-sm" name="id_sexo" id="id_sexo" style="width:155px" data-required="true">
                        <option value="">SELECCIONE</option>
                        <option value="M">MASCULINO</option>
                        <option value="F">FEMENINO</option>
                      </select>
                      <span id="errorSexo" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Ingrese sexo
                      </span>
                    </div>
                  </div>
                  <div class="col-md-2" style="position: relative; height: 60px;">
                    <div style="position: absolute; bottom: 0;">
                      <label>Estado Civil<span style="color:red;">*</span></label>
                      <select id="id_estadocivil" name="id_estadocivil" class="form-control input-sm" style="width:155px" data-required="true">
                        <option value="">SELECCIONE</option>
                        @foreach($estado_civil as $ec)
                        <option value="{{$ec->id}}">{{$ec->nombre}}</option>
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
                      <input id="nro_hijos" name="nro_hijos" class="form-control input-sm" type="number" min="0" max="29" step="1" data-required="true">
                      <span id="errorNroHijos" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Falta hijos
                      </span>
                    </div>
                  </div>
                  <div class="col-md-2" style="position: relative; height: 60px;">
                    <div style="position: absolute; bottom: 0;">
                      <label>N° de Celular<span style="color:red;">*</span></label>
                      <input type="text" name="txtCelular" id="txtCelular" class="form-control input-sm solo-numeros min-8" maxlength="20" data-required="true">
                      <span id="errorCelular" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Debe ingresar celular
                      </span>
                    </div>
                  </div>
                  <div class="col-md-2" style="position: relative; height: 60px;">
                    <div style="position: absolute; bottom: 0;">
                      <label for="txtEmergencia">Contacto de emergencia<span style="color:red;">*</span></label>
                      <input type="text" name="txtEmergencia" id="txtEmergencia" style="width: 153px" class="form-control input-sm solo-numeros min-8" maxlength="20" data-required="true">
                      <span id="errorCelEmer" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Ingrese contacto emer.
                      </span>
                      <span id="errorCelEmerIguales" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Emerg. no es diferente a cel.
                      </span>
                    </div>
                  </div>
                  <div class="col-md-3" style="position: relative; height: 60px;">
                    <div style="position: absolute; bottom: 0; width: 245px;">
                      <label for="email">Correo Electronico Personal<span style="color:red;">*</span></label>
                      <input id="email" name="email" type="email" class="form-control input-sm" data-required="true">
                      <span id="errorEmail" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                        Debe ingresar email personal
                      </span>
                    </div>
                  </div>
                </div>

                <div class="row" style="margin-top:5px">
                  <div class="col-md-4">
                    <label for="email_inst">Correo Electronico Institucional<span style="color:red;">*</span></label>
                    <input id="email_inst" name="email_inst" type="email" class="form-control input-sm" data-required="true">
                    <span id="errorEmailInstitucional" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Debe ingresar email institucional
                    </span>
                  </div>
                  <div class="col-md-8">
                    <label>Domicilio actual<span style="color:red;">*</span></label>
                    <input type="text" name="txtDomicilio" id="txtDomicilio" class="form-control input-sm text-uppercase min-10" maxlength="200" data-required="true">
                    <span id="errorDomicilio" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Ingrese domicilio
                    </span>
                  </div>
                </div>

                <div class="row" style="margin-top:6px; margin-bottom:3px">
                  <div class="col-md-4">
                    <label>Departamento<span style="color:red;">*</span></label>
                    <select id="id_departamento" name="id_departamento" class="form-control input-sm" data-required="true" onchange="getProvincia();">
                      <option value="">SELECCIONE</option>
                      @foreach($departamento as $depto)
                      <option value="{{$depto->id_departamento}}">{{$depto->descripcion}}</option>
                      @endforeach
                    </select>
                    <span id="errorDpto" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Seleccione departamento
                    </span>
                  </div>
                  <div class="col-md-4">
                    <label>Provincia<span style="color:red;">*</span></label>
                    <select id="id_provincia" name="id_provincia" class="form-control input-sm" data-required="true" onchange="getDistrito();">
                      <option>SELECCIONE</option>
                    </select>
                    <span id="errorProvincia" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Seleccione provincia
                    </span>
                  </div>
                  <div class="col-md-4">
                    <label>Distrito<span style="color:red;">*</span></label>
                    <select id="id_distrito" name="id_distrito" class="form-control" data-required="true">
                      <option>SELECCIONE</option>
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
                    <input class="form-check-input" type="radio" name="grupoTrabajo" id="asistencial" value="1" checked>
                    <label class="form-check-label" for="asistencial">ASISTENCIAL</label>

                    <input class="form-check-input" type="radio" name="grupoTrabajo" id="administrativo" value="2">
                    <label class="form-check-label" for="administrativo">ADMINISTRATIVO</label>

                  </div>
                  <div class="col-md-1">
                    <label class="form-label" for="txtInicioLabo" style="font-size: 13px;">Ingreso a la IPREES<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-md-2">
                    <input type="date" name="txtInicioLabo" id="txtInicioLabo" class="form-control" data-required="true">
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
                    <label class="form-label" for="chk_cese_lab">Cese de Labores<span id="ceselaboresrequired" style="color:red;" hidden>*</span></label>
                  </div>
                  <div class="col-md-3">
                    <input type="date" name="txtCeseLab" id="txtCeseLab" class="form-control">
                    <span id="errorCeseLab" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Debe ingresar una fecha de cese
                    </span>
                    <span id="errorCeseLabMayInicio" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Debe ser mayor al inicio
                    </span>
                  </div>
                </div> 

                <div class="row" style="margin-top:5px">
                  <div class="col-md-4" style="margin-top:5px">
                    <label>Situacion Actual<span style="color:red;">*</span></label>
                    <select class="form-control input-sm" name="id_situacion_actual" id="id_situacion_actual" data-required="true">
                      <option value="">SELECCIONE</option>
                      @foreach($situacion_actual as $sa)
                      <option value="{{$sa->id}}">{{$sa->descripcion}}</option>
                      @endforeach
                    </select>
                    <span id="errorSitAct" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Ingrese sit. actual
                    </span>
                  </div>
                  <div class="col-md-4" style="margin-top:5px">
                    <label>Condicion Laboral<span style="color:red;">*</span></label>
                    <select class="form-control input-sm" name="id_condicion_laboral" id="id_condicion_laboral" data-required="true">
                      <option value="">SELECCIONE</option>
                      @foreach ($condicion_laboral as $cl)
                      <option value="{{ $cl->id }}">{{ $cl->descripcion }}</option>
                      @endforeach
                    </select>
                    <span id="errorCondicionLab" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Ingrese condicion lab.
                    </span>
                  </div>
                  <div class="col-md-4" style="margin-top:5px">
                    <label>Cargo<span style="color:red;">*</span></label>
                    <input name="cargo" id="cargo" type="text" class="form-control input-sm text-uppercase min-5" maxlength="100" data-required="true">
                    <span id="errorCargo" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Ingrese cargo
                    </span>
                  </div>
                </div>
                <div class="row" style="margin-top:2px">
                  <div class="col-md-4" style="margin-top:5px">
                    <label>Profesi&oacute;n<span style="color:red;">*</span></label>
                    <select id="id_profesion" name="id_profesion" class="form-control input-sm">
                      <option value="">SELECCIONE</option>
                      @foreach($profesion as $e)
                      <option value="{{$e->id}}">{{$e->nombre}}</option>
                      @endforeach
                    </select>
                    <span id="errorProfesion" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Ingrese profesion
                    </span>
                  </div>
                  <div class="filtroProfesion col-md-4" style="margin-top:5px">
                    <label>Especialidad del personal de salud<span style="color:red;">*</span></label>
                    <select id="id_especializacion" name="id_especializacion" class="form-control">
                      <option value="">SELECCIONE</option>
                      @foreach($especializaciones as $esp)
                      <option value="{{$esp->id}}">{{$esp->nombre}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="filtroProfesion col-md-4" style="margin-top:5px">
                    <label>Colegio Profesional<span style="color:red;">*</span></label>
                    <select id="id_colegio_profesional" name="id_colegio_profesional" class="form-control">
                      <option value="">SELECCIONE</option>
                      @foreach($colegio_profesional as $col)
                      <option value="{{$col->id}}">{{$col->descripcion}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="row" style="margin-top:7px">
                  <div class="filtroProfesion col-md-2" style="margin-top:5px">
                    <label for="colegiatura">N° de Colegiatura<span style="color:red;">*</span></label>
                    <input type="text" id="colegiatura" name="colegiatura" class="form-control input-sm solo-numeros min-6" maxlength="8">
                    
                  </div>
                  <div class="filtroProfesion col-md-2" style="margin-top:5px">
                    <label for="rne">N° de Especialidad<span style="color:red;">*</span></label>
                    <input id="rne" name="rne" type="text" placeholder="" class="form-control input-sm solo-numeros" maxlength="8">
                    
                  </div>
                  
                      
                  <div class="filtroProfesion col-md-2" style="margin-top:5px">
                    <label for="slcEstadoColegiatura">Estado de Colegiatura<span style="color:red;">*</span></label>
                    <select class="form-control input-sm" id="slcEstadoColegiatura" name="slcEstadoColegiatura">
                      <option value="">SELECCIONE</option>
                      <option value="1">HABILITADO</option>
                      <option value="0">INHABILITADO</option>
                    </select>

                  </div>
                  <div class="filtroProfesion col-md-2 fechasHabilidad" style="font-size:11px; margin-top:7px;">
                    <label for="fechInicioHab">Fecha de inicio de habilidad<span style="color:red;">*</span></label>
                    <input name="fechInicioHab" style="font-size:12px" id="fechInicioHab" type="date" value="" class="form-control input-sm">
                    <span id="errorInicioHab" style="display:none;color:#E55050;font-size:14px;">
                      Ingresar campo
                    </span>
                    <span id="errorInicioHabHoy" style="display:none;color:#E55050;font-size:14px;">
                      Debe ser menor a hoy
                    </span>
                  </div>
                  <div class="filtroProfesion col-md-2 fechasHabilidad" style="font-size:11px; margin-top:6px;">
                    <label for="fechFinHab">Fecha de termino de habilidad<span style="color:red;">*</span></label>
                    <input name="fechFinHab" id="fechFinHab" type="date" value="" class="form-control input-sm" style="font-size:12px">
                    <span id="errorFinHab" style="display:none;color:#E55050;font-size:14px;">
                      Ingresar campo
                    </span>
                    <span id="errorFinHabInicio" style="display:none;color:#E55050;font-size:14px;">
                      Debe ser mayor a inicio hab.
                    </span>
                  </div>
                  <div class="col-md-2" style="margin-top:30px">
                    <button type="button" id="addRowProfesion" class="btn btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-plus"></i>AGREGAR PROFESION</button>
                    
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12" style="text-align: left; font-size: 18px; margin-top:2px; margin-bottom: 0px;">
                  Profesiones<span style="color: red;font-size: 0.75em;">*</span>
                  </div>
                </div>
                <span id="errorTabla" style="display:none;color:#E55050;font-size:14px;">
                  Debe agregar al menos un registro.
                </span>
                <div class="row">
                  <div class="col-md-12" style="padding-top:15px; padding-bottom:15px; border: 1px solid gray; border-radius:6px; margin-left: 15px; width:95%">
                    <table id="tblEspecialidades" class="display table table-condensed" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th class="text-left">Profesión</th>
                          <th class="text-left">Especialidad</th>
                          <th class="text-left">N° de Colegiatura</th>
                          <th class="text-left">N° de Especialidad</th>
                          <th class="text-center">Activo</th>
                          <th class="text-center">Eliminar</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row" style="margin-top:13px; margin-bottom:5px">
                  <div class="col-md-4" style="margin-top:3px;">
                    <label>Establecimiento<span style="color:red;">*</span></label>
                    <select class="form-control" name="id_establecimiento" id="id_establecimiento" data-required="true" style="width: 100%">
                      <option value="">SELECCIONE</option>
                      @foreach($establecimiento_data as $e)
                      <?php $idEst = $e->id; ?>
                      <option value="{{$e->id}}">
                        {{$e->nombre}}
                      </option>
                      @endforeach
                    </select>
                    <span id="errorEstablecimiento" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Seleccione establecimiento
                    </span>
                  </div>
                  <div class="col-md-4">
                    <label>Unidad<span style="color:red;">*</span></label>
                    <select class="form-control" name="id_unidad" id="id_unidad" data-required="true" onchange="getDivisionSubUnidad();">
                      <option value="">SELECCIONE</option>
                      @foreach($unidad_lista as $u)
                      <?php $idEst = $u->id; ?>
                      <option value="{{$u->id}}">
                        {{$u->nombre}}
                      </option>
                      @endforeach
                    </select>
                    <span id="errorUnidad" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                      Seleccione unidad
                    </span>
                  </div>
                  <div class="col-md-4">
                    <label>Sub Unidad<span style="color:red;">*</span></label>
                    <select class="form-control" id="id_sub_unidad" name="id_sub_unidad" data-required="true">
                      <option value="">SELECCIONE</option>
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
              <div class="col-md-6" style="margin-top:10px">
              </div>
              <div class="col-md-6" style="margin-top:10px">
                <label>Firma (Foto)</label>
                <input type="file" name="archivo_firma" id="archivo_firma" class="form-control">
                <span id="errorFirma" class="msg-error" style="display:none;color:#E55050;font-size:13px;">
                  Agregue firma
                </span>
              </div>

              <div class="col-md-12">
                <div class="col-md-12" style="text-align: left; font-size: 18px; margin-top:15px;">
                Servicio de Atención
                </div>
                <div id="entregar_receta" class="row col-md-12" style="padding-top:15px; padding-bottom:15px; border: 1px solid gray; border-radius:6px; margin-bottom: 10px; margin-left: 3px">
                  <button type="button" id="addRow" class="btn btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-plus"></i> Agregar Servicio(s)
                  </button>
                  <div style="display: none;">
                    <select class="form-control" name="id_consultoriotemp" id="id_consultoriotemp">
                      <option value="">SELECCIONE</option>
                      @foreach($consultorio as $e)
                      <option value="{{$e->id}}">{{$e->nombre}}</option>
                      @endforeach
                    </select>
                  </div>
                  <table id="example" class="display" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Descripción</th>
                        <th>Eliminar</th>
                      </tr>
                    </thead>
                    <tfoot></tfoot>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            @endif

            <input type="hidden" name="especialidades_json" id="especialidades_json">

            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
              <a href="/gestion_medicos/{{ Auth::user()->id }}" class="form-control btn btn-danger">Cancelar</a>
            </div>
            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
              <button id="btnReg" class="form-control btn btn-success detalle-btn" type="button" tabindex="99921">Guardar</button>
            </div>
          </form>
        </div>
        @endif
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
        <button type="button" class="btn btn-primary" id="btnGenerarReporte" onclick="submitFrm()">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection