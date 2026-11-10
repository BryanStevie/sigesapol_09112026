@extends('frontend.layouts.master')
@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script src="{{ asset('datePicker/js/bootstrap-datepicker.js') }}" charset="utf-8"></script>
<link href="{{ asset('datePicker/css/bootstrap-datepicker.min.css') }}" rel='stylesheet' />
<style>
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

  .input-detectado,
  .campo-resaltado {
    background-color: #dff0d8 !important;   
    border: 1px solid #3c763d !important;
    transition: background-color 0.3s ease;
   }

  .form-control.campo-resaltado,
  .form-control.input-detectado {
    background-color: #dff0d8 !important;
    border-color: #3c763d !important;
  }

  input.campo-resaltado[readonly],
  textarea.campo-resaltado[readonly],
  select.campo-resaltado:disabled,
  input.input-detectado[readonly],
  textarea.input-detectado[readonly],
  select.input-detectado:disabled {
    background-color: #dff0d8 !important;
    border-color: #3c763d !important;
    opacity: 1 !important; 
  }

</style>

<script>

function calcularEdad(fecha) {
    if (!fecha) return NaN;

    let anio;

    if (fecha.includes("/")) {
        const partes = fecha.split("/");
        anio = partes[2];
    } else {
        const partes = fecha.split("-");
        anio = partes[0];
    }

    const anioActual = new Date().getFullYear();
    return anioActual - parseInt(anio);
}


$(document).ready(function() {
    $('.fecha').datepicker({
        language: "es",
        format: "dd/mm/yyyy",
        autoclose: true
    });

    $("#id_establecimiento_nuevo").select2({
	    width: 'resolve'
	});
    
	$('#panel_paciente_titular').hide();
	$('#panel_paciente_familiar').hide();
    //$('#panel_tutor').hide();
    cambiar_tipo_beneficiario();
    modalHistorial();

    const fechaInicial = $('#fecha_nac').val();
    if (fechaInicial) {
        const edadInicial = calcularEdad(fechaInicial);
        $('#edad').val(edadInicial);
        controlarBeneficiario(edadInicial); 
    }

    $('#fecha_nac').on('change', function () {
        const fechaNac = $(this).val();
        const edad = calcularEdad(fechaNac);
        $('#edad').val(edad);
        controlarBeneficiario(edad); 
    });
});

function activarToggle() {
	var asignarTitular = $('#asignarTitular').attr("aria-pressed");
	$("#fs_paciente_familiar").hide();
	if (asignarTitular == "false") {
		$("#fs_paciente_familiar").show();
	}
}


function activarToggleTutor() {
    const btn = $('#asignarTutor');

    if (btn.hasClass('active')) {
        btn.removeClass('active').attr('aria-pressed', 'false');
        $('#fs_tutor').hide();
    } else {
        btn.addClass('active').attr('aria-pressed', 'true');
        $('#fs_tutor').show();
    }
}

let tipoBeneficiarioAnterior = $("#id_tipo_beneficiario").val();
let bloqueadoCambioTipo = false;

$('#id_tipo_beneficiario').on('focus', function () {
    tipoBeneficiarioAnterior = $(this).val();
});

$('#id_tipo_beneficiario').on('change', cambiar_tipo_beneficiario);

function cambiar_tipo_beneficiario() {
    if (bloqueadoCambioTipo) return;
    const tipoBeneficiarioNuevo = $("#id_tipo_beneficiario").val();

    if (tipoBeneficiarioAnterior !== "" && tipoBeneficiarioAnterior !== tipoBeneficiarioNuevo) {
        const textoAnterior = $("#id_tipo_beneficiario option[value='" + tipoBeneficiarioAnterior + "']").text();
        const textoNuevo = $("#id_tipo_beneficiario option[value='" + tipoBeneficiarioNuevo + "']").text();

        // Realizamos la seleccion antes de confirmar
        bloqueadoCambioTipo = true;
        $("#id_tipo_beneficiario").val(tipoBeneficiarioAnterior);
        bloqueadoCambioTipo = false;

        bootbox.confirm({
            message: `¿Estás seguro de cambiar de '<strong>${textoAnterior}</strong>' a '<strong>${textoNuevo}</strong>'?`,
            title: "TIPO DE BENEFICIARIO",
            className: 'title-dialog',
            buttons: {
                confirm: {
                    label: 'Sí',
                    className: 'btn-success btn-sm'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger btn-sm'
                }
            },
            callback: function(result) {
                if (result) {
                    // Si se confirma ejecutamos la funcion
                    bloqueadoCambioTipo = true;
                    $("#id_tipo_beneficiario").val(tipoBeneficiarioNuevo);
                    bloqueadoCambioTipo = false;
                    tipoBeneficiarioAnterior = tipoBeneficiarioNuevo;

                    Tipos_Beneficiarios(tipoBeneficiarioNuevo);
                    controlarBeneficiario();
                } 
            }
        });
    } else {
        tipoBeneficiarioAnterior = tipoBeneficiarioNuevo;
        Tipos_Beneficiarios(tipoBeneficiarioNuevo);
        controlarBeneficiario();
    }
}

function Tipos_Beneficiarios(tipoBeneficiarioNuevo) {
    mostrarPanelDiscapacidad(parseInt(tipoBeneficiarioNuevo)); 
    switch (tipoBeneficiarioNuevo) {
        case "8": // TITULAR
            const tipoDocIdentText = $("#id_tipo_doc_ident option:selected").text().trim();
            $('#tipo_doc_ident_titular').val(tipoDocIdentText);
            $('#nro_doc_ident_titular').val($('#nro_doc_ident').val());

            const nombresCompletos = `${$('#paterno').val()} ${$('#materno').val()}, ${$('#nombre').val()}`;
            $('#apellidos_nombres_titular').val(nombresCompletos);

            $('#panel_paciente_titular').show();
            $('#panel_paciente_familiar').hide();
            break;

        case "13":
        case "": // CIVIL 
            $('#panel_paciente_titular').hide();
            $('#panel_paciente_familiar').hide();
            break;
        
        default: // OTROS FAMILIARES
            $('#panel_paciente_titular').hide();
            $('#panel_paciente_familiar').show();

            $('#asignarTitular').addClass('active');
            $('#asignarTitular').attr('aria-pressed', 'false');
            $('#fs_paciente_familiar').show();
            break;
    }
    controlarBeneficiario();
}

function mostrarPanelDiscapacidad(tipo) {
    const discapacidad = document.getElementById("panel-discapacitado");
    if (tipo === 6 || tipo === 16) {
        discapacidad.style.display = "flex"; 
    } else {
        discapacidad.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const tipoActual = parseInt(document.getElementById("id_tipo_beneficiario")?.value);
    mostrarPanelDiscapacidad(tipoActual);
});

   function mostrarPanelTutor() {
        $('#panel_tutor').show();
        $('#grupoAsignarTutor').show();

        const btnTutor = $('#asignarTutor');

        btnTutor.removeClass('active').attr('aria-pressed', 'false');
        $('#fs_tutor').hide();

        // Evento click para alternar SÍ / NO
        btnTutor.off('click').on('click', function () {
            const isPressed = $(this).attr('aria-pressed') === "true";
            $(this).attr('aria-pressed', !isPressed);
            $(this).toggleClass('active', !isPressed);
            activarToggleTutor();
        });
    }

    function controlarBeneficiario() {
        const tipoBeneficiario = $('#id_tipo_beneficiario').val();
        const edad = parseInt($('#edad').val()) || 0;

        if (!["4", "8"].includes(tipoBeneficiario)) {
            if (edad < 18 || edad > 75) {
                $('#panel_tutor').show();
                $('#grupoAsignarTutor').show();
            } else {
                $('#panel_tutor').hide();
                $('#grupoAsignarTutor').hide();
                $('#fs_tutor').hide();
                $('#asignarTutor').removeClass('active').attr('aria-pressed', 'false');
            }
        }else{
            // Si es Titular o Cónyuge, ocultamos el tutor
            $('#panel_tutor').hide();
            $('#grupoAsignarTutor').hide();
        }
    }

    function getProvincia(){
        var newOption = "";
        newOption = "<option value=''>SELECCIONE</option>";
        $('#id_provincia').html(newOption);
        

        var Departamento = $('#id_departamento').val();

        $.ajax({    
            url: '/obtener_provincia_por_departamento/' + Departamento,
            async: false,
            success: function(result){                    
                var newOption = ""; //var cant=0;
                newOption = "<option value=''>SELECCIONE</option>";
                $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id_provincia+"'>"+oo.descripcion+"</option>";
                });                 
                $('#id_provincia').html(newOption);
            }
        });
    }

    function getDistrito(){
        var newOption = "";
        newOption = "<option value=''>SELECCIONE</option>";
        $('#id_distrito').html(newOption);

        var Provincia = $('#id_provincia').val();

        $.ajax({    
            url: '/obtener_distrito_por_provincia/' + Provincia,
            async: false,
            success: function(result){
                var newOption = "";
                newOption = "<option value=''>SELECCIONE</option>";
                $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id_distrito+"'>"+oo.descripcion+"</option>";
                });                 
                $('#id_distrito').html(newOption);    
            }
        });
    }


    function validateFrm() {
        let isValid = true;
        let errorMessage = "";
        let nameFieldToFocus = "";

        // $('#btnReg').prop('disabled', true);
        // $('#btnRegistrarHistoriaClinica').prop('disabled', true);

        const form = $('#frm_paciente');

        if (form.find("#id_tipo_doc_ident").val() == "") {
            isValid = false;
            errorMessage = "El Tipo de Documento del paciente no puede estar en vacío.";
        }

        if (isValid) {
            if (form.find("#nro_doc_ident").val() == "") {
                isValid = false;
                errorMessage = "El Documento de Identidad del paciente no puede estar en vacío.";
                nameFieldToFocus = "nro_doc_ident";
            }
        }

        if (isValid) {
            if (form.find("#id_tipo_beneficiario").val() == "") {
                isValid = false;
                errorMessage = "Debe seleccionar el Tipo de beneficiario del paciente.";
                nameFieldToFocus = "id_tipo_beneficiario";
            }
        }

        if (isValid) {
            if (form.find("#nombre").val() == "") {
                isValid = false;
                errorMessage = "El nombre del paciente no puede estar en vacío.";
                nameFieldToFocus = "nombre";
            }
        }

        if (isValid) {
            if (form.find("#paterno").val() == "") {
                isValid = false;
                errorMessage = "El apellido paterno del paciente no puede estar vacío.";
            }
        }

        if (isValid) {
            if (form.find("#materno").val() == "") {
                isValid = false;
                errorMessage = "El apellido materno del paciente no puede estar vacío.";
            }
        }

        if (isValid) {
            if (form.find("#sexo").val() == "") {
                isValid = false;
                errorMessage = "El sexo del paciente no puede estar vacío.";
            }
        }

        if (isValid) {
            if (form.find("#fecha_nac").val() == "") {
                isValid = false;
                errorMessage = "La fecha de nacimiento del paciente no puede estar vacío.";
            }
        }

        if (isValid) {
            if (form.find("#id_tipo_sangre").val() == "") {
                isValid = false;
                errorMessage = "Debe seleccionar el Tipo de Sangre del paciente.";
            }
        }

        if (isValid) {
            if (form.find("#id_departamento").val() == "") {
                isValid = false;
                errorMessage = "Debe seleccionar el Departamento del domicilio del paciente.";
            }
        }

        if (isValid) {
            if (form.find("#id_provincia").val() == "") {
                isValid = false;
                errorMessage = "Debe seleccionar la Provincia del domicilio del paciente.";
            }
        }

        if (isValid) {
            if (form.find("#id_distrito").val() == "") {
                isValid = false;
                errorMessage = "Debe seleccionar el Distrito del domicilio del paciente.";
            }
        }

        if (isValid) {
            if (form.find("#domicilio").val() == "") {
                isValid = false;
                errorMessage = "La Dirección del domicilio del paciente no puede estar vacío.";
            }
        }

        if (isValid) {
            if (form.find("#id_grado_instruccion").val() == "") {
                isValid = false;
                errorMessage = "El Grado de instrucción del paciente no puede estar vacío.";
            }
        }

        if (isValid) {
            if (form.find("#id_ocupacion").val() == "") {
                isValid = false;
                errorMessage = "La Ocupación del paciente no puede estar vacío.";
            }
        }

        if (isValid) {
            if (form.find("#id_estado_civil").val() == "") {
                isValid = false;
                errorMessage = "El Estado civil del paciente no puede estar vacío.";
            }
        }

        if (isValid) {
            if (form.find("#id_religion").val() == "") {
                isValid = false;
                errorMessage = "La Religión del paciente no puede estar vacío.";
            }
        }

        if (isValid) {
            if (form.find("#id_etnia").val() == "") {
                isValid = false;
                errorMessage = "La Etnia del paciente no puede estar vacío.";
            }
        }

        const beneficiario = form.find("#id_tipo_beneficiario").val();

        if (beneficiario !== "8" && beneficiario !=="13") {

            if (isValid) {
                if (form.find("#dni_titular").val() == "") {
                    isValid = false;
                    errorMessage = "El Documento de Identidad del titular no puede estar en vacío.";
                }
            }

            if (isValid) {
                if (form.find("#nombre_titular").val() == "") {
                    isValid = false;
                    errorMessage = "El nombre del titular no puede estar en vacío.";
                }
            }

            if (isValid) {
                if (form.find("#paterno_titular").val() == "") {
                    isValid = false;
                    errorMessage = "El apellido paterno del titular no puede estar en vacío.";
                }
            }

            if (isValid) {
                if (form.find("#materno_titular").val() == "") {
                    isValid = false;
                    errorMessage = "El apellido materno del titular no puede estar en vacío.";
                }
            }

            if (isValid) {
                if (form.find("#cips").val() == "") {
                    isValid = false;
                    errorMessage = "El N° CIP no puede estar vacío.";
                }
            }

            if (isValid) {
                if (form.find("#grado_familiar").val() == "") {
                    isValid = false;
                    errorMessage = "Debe seleccionar el Grado del titular.";
                }
            }

            if (isValid) {
                if (form.find("#situacion_familiar").val() == "") {
                    isValid = false;
                    errorMessage = "Debe seleccionar la Situación del titular.";
                }
            }

        } else if (beneficiario == "8") {

            if (isValid) {
                if (form.find("#dni_titular2").val() == "") {
                    isValid = false;
                    errorMessage = "El Documento de Identidad del titular no puede estar en vacío.";
                }
            }

            if (isValid) {
                if (form.find("#nombre_titular2").val() == "") {
                    isValid = false;
                    errorMessage = "El nombre del titular no puede estar en vacío.";
                }
            }

            if (isValid) {
                if (form.find("#paterno_titular2").val() == "") {
                    isValid = false;
                    errorMessage = "El apellido paterno del titular no puede estar en vacío.";
                }
            }

            if (isValid) {
                if (form.find("#materno_titular2").val() == "") {
                    isValid = false;
                    errorMessage = "El apellido materno del titular no puede estar en vacío.";
                }
            }

            if (isValid) {
                if (form.find("#cips2").val() == "") {
                    isValid = false;
                    errorMessage = "El N° CIP del Titular no puede estar vacío.";
                }
            }

            if (isValid) {
                if (form.find("#grado2").val() == "") {
                    isValid = false;
                    errorMessage = "Debe seleccionar el Grado del titular.";
                }
            }

            if (isValid) {
                if (form.find("#situacion_paciente2").val() == "") {
                    isValid = false;
                    errorMessage = "Debe seleccionar la Situación del titular.";
                }
            }
        }

        const tipo = form.find('#id_tipo_beneficiario').val();
        const edad = parseInt($('#edad').val());

        // condicion de edad 
        const requiereTutor = (edad < 18 || edad > 75);

        if (!["4", "8"].includes(tipo)) {

            if (!requiereTutor) {
                //  NO necesita tutor → saltar validaciones
            } else {
                //  SÍ necesita tutor → validar todo
            console.log(id_parentesco_tutor);
                if (isValid && form.find('#id_parentesco_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe seleccionar el tipo de parentesco.";
                }

                if (isValid && form.find('#tipo_documento_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe seleccionar el tipo de documento.";
                }

                if (isValid && form.find('#numero_documento_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe ingresar el numero de documento.";
                }

                if (isValid && form.find('#nombres_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe ingresar los nombres del tutor.";
                }

                if (isValid && form.find('#apellidos_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe ingresar los apellidos del tutor.";
                }

                if (isValid && form.find('#telefono_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe ingresar el numero de telefono.";
                }
            }
        }

        if (isValid) {
            // Validacion de Nro de Historias clinicas
            $('input[name="nro_historias[]"]').each(function(index) {
                if ($(this).val().trim() === '') {
                    isValid = false;
                    $(this).css('border', '2px solid red');
                    errorMessage = 'Todos los números de historia son obligatorios';
                } else {
                    $(this).css('border', '');
                }
            });
        }

        if (isValid) {
            let establecimientoNuevo = $('#id_establecimiento_nuevo').val();
            let historiaNueva = $('input[name="nro_historia_nuevo"]').val().trim();
            
            if (establecimientoNuevo !== '0') {
                if (historiaNueva === '') {
                    isValid = false;
                    $('input[name="nro_historia_nuevo"]').css('border', '2px solid red');
                    errorMessage = 'Debe completar el número de historia del nuevo establecimiento.';
                } else {
                    $('input[name="nro_historia_nuevo"]').css('border', '');
                }
            }
        }

        if (!isValid) {
            bootbox.alert({
                message: errorMessage,
                callback: function() {
                    if (nameFieldToFocus !== "") {
                        setTimeout(function() {
                            document.getElementById(nameFieldToFocus).focus();
                        }, 100);
                    }
                }
            });
            
            $('#btnReg').prop('disabled', false);
            $('#btnRegistrarHistoriaClinica').prop('disabled', false);
            return false;
        }
        
        submitFrm();
    }


    function submitFrm() {
        
        bootbox.confirm({
            message: "¿Desea guardar los datos?.",
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
            callback: function(result) {
                if (result == true) {
                    $("#frm_paciente").submit();
                } else {
                    $('#btnReg').prop('disabled', false);
                }
            }
        });
    }

function getDirrehumByDni(tipo) {

    var dniInput, dniDestinoId, nombreDestinoId, paternoDestinoId, maternoDestinoId,
    cipsDestinoId, gradoDestinoId, situacionDestinoId, $btnBuscar;

    if (tipo === 'titular') {
        dniInput           = $('#panel_paciente_titular input[name="dni_paciente"]');
        $btnBuscar         = $('#panel_paciente_titular #buscar_titular');
        dniDestinoId       = '#dni_titular2';
        nombreDestinoId    = '#nombre_titular2';
        paternoDestinoId   = '#paterno_titular2';
        maternoDestinoId   = '#materno_titular2';
        cipsDestinoId      = '#cips2';
        gradoDestinoId     = '#grado2';
        situacionDestinoId = '#situacion_paciente2';
    } else {
        dniInput           = $('#fs_paciente_familiar input[name="dni_paciente"]');
        $btnBuscar         = $('#fs_paciente_familiar #buscar_titular_familiar');
        dniDestinoId       = '#dni_titular';
        nombreDestinoId    = '#nombre_titular';
        paternoDestinoId   = '#paterno_titular';
        maternoDestinoId   = '#materno_titular';
        cipsDestinoId      = '#cips';
        gradoDestinoId     = '#grado_familiar';
        situacionDestinoId = '#situacion_familiar';
    }

    var dni_paciente = dniInput.val()?.trim();

    if (!dni_paciente || dni_paciente === "") {
        bootbox.alert("Falta ingresar el Documento del paciente");
        dniInput.focus();
        return 0;
    }

    if (dni_paciente.length != 8) {
        bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>");
        dniInput.focus();
        return 0;
    }

    $btnBuscar.prop('disabled', true);

    $.ajax({
        url: "/ws_dirrehum/1/" + dni_paciente,
        type: "GET",
        dataType: "JSON",

        success: function (result) {

            // let data = null;
            let data = result;
          
            if (!data) {
                dniInput.val('');
                dniInput.focus();
                bootbox.alert("No hay coincidencia con el N° de DNI ingresado.");
                return;
            }

            let html = `
                <table class="table table-bordered table-sm">
                    <tr><th>DNI</th><td>${data.DNI}</td></tr>
                    <tr><th>Nombre</th><td>${data.NOMBRES}</td></tr>
                    <tr><th>Paterno</th><td>${data.PATERNO}</td></tr>
                    <tr><th>Materno</th><td>${data.MATERNO}</td></tr>
                    <tr><th>CIP</th><td>${data.CARNE}</td></tr>
                    <tr><th>Grado</th><td>${data.GRADO}</td></tr>
                    <tr><th>Situación</th><td>${data.SITUACION}</td></tr>
                </table>
            `;

            bootbox.alert({
                title: "Datos del Titular",
                message: html,
                callback: function () {
                    $(dniDestinoId).val(data.DNI);
                    $(nombreDestinoId).val(data.NOMBRES);
                    $(paternoDestinoId).val(data.PATERNO);
                    $(maternoDestinoId).val(data.MATERNO);
                    $(cipsDestinoId).val(data.CARNE);
                    $(gradoDestinoId).val(data.GRADO);
                    $(situacionDestinoId).val(data.SITUACION);
                    dniInput.val('');
                }
            });
        },

        error: function (xhr, status) {
            dniInput.val('');
            dniInput.focus();
            bootbox.alert("Ha ocurrido un error al consultar el N° Documento del paciente.");
        },

        complete: function () {
            $btnBuscar.prop('disabled', false);
        }
    });
}

function pintarCampo($campo) {
  const valor = $campo.val();
  if (typeof valor === 'string' && valor.trim() !== '') {
     $campo.addClass('input-detectado');
  }else{
     $campo.removeClass('input-detectado');
  }
}

function LimpiarCampos(){
  $('#paterno, #materno, #nombre, #sexo, #fecha_nac, #fecha_caducidad, #distrito_nacimiento, #distrito_domicilio, #localidad_domicilio, #direccion_domicilio')
    .val('')
    .removeClass('input-detectado');
}

function getSaludpolByTipoDocAndNroDoc() {
  var nro_doc_ident = $("#nro_doc_ident").val().trim().replace(/\s+/g, '');
  var id_tipo_doc_ident = $("#id_tipo_doc_ident").val();

  if (nro_doc_ident == "") {
    bootbox.alert("Falta ingresar el Documento del paciente");
    $('#nro_doc_ident').focus();
    return 0;
  }

  if (nro_doc_ident.length > 0) {
    if (id_tipo_doc_ident == '1' && nro_doc_ident.length != 8) {
      bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>");
      $('#nro_doc_ident').focus();
      return 0;
    }
  }

  LimpiarCampos();

  $('#content').html('<div class="loading"><img src="images/loader.gif" alt="loading" /><br/>Consultando ...</div>');
  $('#btnBuscar').prop('disabled', true);

  $.ajax({
    url: "/ws_saludpol/" + id_tipo_doc_ident + "/" + nro_doc_ident.toUpperCase(),
    type: "GET",
    dataType: "JSON",
    success: function (result) {
      // console.log(result);
      if (result && result.nompaisdelafiliado && result.nompaisdelafiliado.trim() !== '') {
        pintarCampo($('#id_tipo_doc_ident').val("1"));
        pintarCampo($('#paterno').val(result.apepatafiliado || ''));
        pintarCampo($('#materno').val(((result.apematafiliado || '') + ' ' + (result.apecasafiliado || '')).trim()));
        pintarCampo($('#nombre').val(result.nomafiliado || ''));
        pintarCampo($('#sexo').val(result.nomsexo || ''));
        pintarCampo($('#fecha_nac').val(result.fecnacafiliado || ''));

        pintarCampo($('#fecha_caducidad').val(result.caducidad && result.caducidad.length === 8 ?
          result.caducidad.substring(6, 8) + '/' +
          result.caducidad.substring(4, 6) + '/' +
          result.caducidad.substring(0, 4) : ''
        ));

        pintarCampo($('#distrito_nacimiento').val(''));
        pintarCampo($('#distrito_domicilio').val(''));
        pintarCampo($('#localidad_domicilio').val(''));
        pintarCampo($('#direccion_domicilio').val(''));

        $("#divreniec").hide();
        $("#divdirrehum").hide();
        $("#divdirrehum_saludpol").show();

      } else {
        bootbox.alert("No se encontraron datos del asegurado.");
        $("#divdirrehum_saludpol").hide();
      }
    },
    error: function (xhr, status) {
      bootbox.alert("En estos momentos la Webservice se encuentra en mantenimiento.");
    },
    complete: function (xhr, status) {
      $('#content').html('');
      $('#btnBuscar').prop('disabled', false);
    }
  });
}

function modalHistorial(){
    var dni_beneficiario = $("#dni_beneficiario").val();
    $.ajax({
        url: "../modal_historial_atenciones/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divHistorial").html(result);
            $('#tablemodalm').DataTable({
                "order": [],
                "sPaginationType": "full_numbers",
                "language": {"url": "/js/Spanish.json"},
            });
        }
    });
}


$(document).ready(function () {
    function actualizarGrupoYRH() {
        const optionSelected = $('#id_tipo_sangre option:selected');
        const grupo = optionSelected.data('grupo') || '';
        const rh = optionSelected.data('rh') || '';
        $('#grupo_sanguineo').val(grupo);
        $('#factor_rh').val(rh);
    }

    $('#id_tipo_sangre').on('change', actualizarGrupoYRH);
    actualizarGrupoYRH();
});

    @if(request()->has('tutor_registrado') && request()->tutor_registrado == 1)
        const tutorYaRegistrado = true;
    @else
        const tutorYaRegistrado = false;
    @endif    

    document.addEventListener('DOMContentLoaded', function () {
        inicializarEstadoTutorRegistrado();
        controlarBeneficiario();
        cambiar_tipo_beneficiario();

        $('#id_tipo_beneficiario').on('change', function () {
            cambiar_tipo_beneficiario();
            controlarBeneficiario();
        });
    });

    function inicializarEstadoTutorRegistrado() {
        const btnTutor = document.getElementById('asignarTutor');
        const panelTutor = document.getElementById('panel_tutor');
        const grupoAsignar = document.getElementById('grupoAsignarTutor');
        const fieldsetTutor = document.getElementById('fs_tutor');

        if (btnTutor && panelTutor && grupoAsignar && fieldsetTutor) {
            panelTutor.style.display = 'block';
            grupoAsignar.style.display = 'block';

            btnTutor.classList.remove('active');
            btnTutor.setAttribute('aria-pressed', 'false');
            fieldsetTutor.style.display = 'none';
        }
    }
// $(document).ready(function() {
//     $('#id_unidad, #id_unidad2').select2({
//         placeholder: "SELECCIONE",
//         allowClear: true,
//         tags: false,
//         width: '100%'
//     });
// });

$(document).ready(function () {
    $('#fecha_ini_hosp, #fecha_fin_hosp, #fecha_ini_rec, #fecha_fin_rec').datepicker({
        useCurrent: false,
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    });

    $('#btnBuscarHospitalizados').click(function () {
         datatableHospitalizados();
    });

    $('#btnBuscarRecetas').click(function () {
        datatableRecetas();
    });

    datatableHospitalizados();
    datatableRecetas();
});


$(document).on('click', '.btn-actualizar', function () {
    let $tr = $(this).closest('tr');
    let id_hospitalizacion = $(this).data('id');
    let id_asegurado = $(this).data('idasegurado');
    let datos = {};

    $tr.find('input, select').each(function () {
        let name = $(this).attr('name');
        let value = $(this).val();
        if (name) {
            datos[name] = value;
        }
    });

    $.ajax({
        url: "{{ route('UpdateAsegurados') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id_hospitalizacion: id_hospitalizacion,
            id_asegurado: id_asegurado
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
                html: `Se ha realizado un cambio; sin embargo, la hoja de hospitalización ya cuenta con la firma.<br>
                    Será necesario informar al médico para que proceda a elaborarla nuevamente.`,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                datatableHospitalizados();
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error al actualizar',
                text: xhr.responseText,
                confirmButtonText: 'Cerrar'
            });
        }
   });
});

function datatableHospitalizados() {
    $('#tblHospitalizados').DataTable({
        "bServerSide": true,
        "sAjaxSource": "{{ route('all_hospitalizados_asegurados') }}",
        "bProcessing": true,
        "bFilter": false,
        "bSort": false,
        "destroy": true,
        "lengthMenu": [[10, 50, 100], [10, 50, 100]],
        "language": { "url": "/js/Spanish.json" },
        "aoColumns": [
            { "mData": "id", "render": (d) => `<span>${d}</span>` },
            { "mData": "id_asegurado", "render": (d) => `<span>${d}</span>` },
            { "mData": "fecha_atencion", "render": (d) => `<span>${d}</span>` },
            { "mData": "dni_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "nombre_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "paterno_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "materno_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "nombre_titular", "render": (d) => `<span>${d}</span>` },
            { "mData": "paterno_titular", "render": (d) => `<span>${d}</span>` },
            { "mData": "materno_titular", "render": (d) => `<span>${d}</span>` },
            { "mData": "fecha_nacimiento_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "sexo", "render": (d) => `<span>${d}</span>` },
            { "mData": "tipo_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "nro_historia", "render": (d) => `<span>${d}</span>` },
            { "mData": "grado", "render": (d) => `<span>${d}</span>` },
            {
                "mData": null,
                "render": function (data, type, row) {
                    return `<button type="button" class="btn btn-sm btn-success btn-actualizar" 
                                    data-id="${row.id}" 
                                    data-idasegurado="${row.id_asegurado}">
                                Actualizar
                            </button>`;
                }
            }
        ],
        "fnServerData": function (sSource, aoData, fnCallback) {
            let fecha_ini = $('#fecha_ini_hosp').val();
            let fecha_fin = $('#fecha_fin_hosp').val();
            let id_asegurado = $('#id_asegurado_hosp').val();

            let datos = {
                sEcho: aoData[0].value,
                NumeroPagina: Math.floor(aoData[3].value / aoData[4].value) + 1,
                NumeroRegistros: aoData[4].value,
                fecha_ini: fecha_ini,
                fecha_fin: fecha_fin,
                id_asegurado: id_asegurado,
                _token: "{{ csrf_token() }}"
            };

            $.ajax({
                dataType: 'json',
                type: "POST",
                url: sSource,
                data: datos,
                success: fnCallback,
                error: function () {
                    alert("Error al cargar la tabla.");
                }
            });
        }
    });
}


$(document).on('click', '.btn-actualizar-receta', function () {
    let $tr = $(this).closest('tr');
    let id_receta_vale = $(this).data('id');
    let id_asegurado = $(this).data('idasegurado');
    let datos = {};

    $tr.find('input, select').each(function () {
        let name = $(this).attr('name');
        let value = $(this).val();
        if (name) {
            datos[name] = value;
        }
    });

    $.ajax({
        url: "{{ route('UpdateRecetaVale') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id_receta_vale: id_receta_vale,
            id_asegurado: id_asegurado
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
                html: `Se actualizó la receta médica correctamente.`,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                datatableRecetas();
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error al actualizar',
                text: xhr.responseText,
                confirmButtonText: 'Cerrar'
            });
        }
   });
});

function datatableRecetas() {
    $('#tblRecetas').DataTable({
        "bServerSide": true,
        "sAjaxSource": "{{ route('listado_receta_vale') }}",
        "bProcessing": true,
        "bFilter": false,
        "bSort": false,
        "destroy": true,
        "lengthMenu": [[10, 50, 100], [10, 50, 100]],
        "language": { "url": "/js/Spanish.json" },
        "aoColumns": [
            { "mData": "id", "render": (d) => `<span>${d}</span>` },
            { "mData": "servicio", "render": (d) => `<span>${d}</span>` },
            { "mData": "created_at", "render": (d) => `<span>${d}</span>` },
            { "mData": "tipo_receta", "render": (d) => `<span>${d}</span>` },
            { "mData": "nro_receta", "render": (d) => `<span>${d}</span>` },
            { "mData": "id_asegurado", "render": (d) => `<span>${d}</span>` },
            { "mData": "dni_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "nombre_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "paterno_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "materno_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "tipo_beneficiario", "render": (d) => `<span>${d}</span>` },
            { "mData": "grado", "render": (d) => `<span>${d}</span>` },
            {
                "mData": null,
                "render": function (data, type, row) {
                    return `<button type="button" class="btn btn-sm btn-success btn-actualizar-receta" 
                                    data-id="${row.id}" 
                                    data-idasegurado="${row.id_asegurado}">
                                Actualizar
                            </button>`;
                }
            }
        ],
        "fnServerData": function (sSource, aoData, fnCallback) {
            let fecha_ini = $('#fecha_ini_rec').val();
            let fecha_fin = $('#fecha_fin_rec').val();
            let id_asegurado = $('#id_asegurado_rec').val();

            let datos = {
                sEcho: aoData[0].value,
                NumeroPagina: Math.floor(aoData[3].value / aoData[4].value) + 1,
                NumeroRegistros: aoData[4].value,
                fecha_ini: fecha_ini,
                fecha_fin: fecha_fin,
                id_asegurado: id_asegurado,
                _token: "{{ csrf_token() }}"
            };

            $.ajax({
                dataType: 'json',
                type: "POST",
                url: sSource,
                data: datos,
                success: fnCallback,
                error: function () {
                    alert("Error al cargar la tabla.");
                }
            });
        }
    });
}


</script>

@endsection
@section('content')
<div class="container">
    <div class="row">
        <!-- <div class="col-md-3">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="/all_asegurados/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Pacientes</a>
        </li>
        <li class="active"><a href="/buscar_paciente"><i class="fa fa-plus"></i> Nuevo </a></li>
      </ul>
    </div> -->
        <div class="col-md-12 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Datos del Paciente</h3>
                    </div>
                    @if(count($personal)>0)
                    <div style="margin-top: 20px">
                        <form id="frm_paciente" class="form-horizontal" role="form" method="post"
                            action="{{ route('update_paciente') }}">
                            <fieldset>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" value="{{$id_asegurado}}" name="id_asegurado" id="id_asegurado">
                                <input type="hidden" value="{{$personal->id_asegurado_titular}}" name="id_asegurado_titular" id="id_asegurado_titular">
                                <input type="hidden" name="id_asegurado_titular_modificado" id="id_asegurado_titular_modificado">
                                <input type="hidden" name="init_id_tipo_beneficiario" id="init_id_tipo_beneficiario" value="{{$personal->id_tipo_beneficiario}}">
                               
                                <?php
                                $tit_tipo_doc_ident = "";
                                $tit_nro_doc_ident = "";
                                $tit_apellidos_nombres = "";
                                $tit_grado = "";
                                $tit_situacion = "";
                                $tit_categoria = "";
                                $fecha_inicio_servicio = "";

                                if ($personadatosadicional != null):
                                    $cip = $personadatosadicional->cip;
                                    $id_grado = $personadatosadicional->id_grado;
                                    $id_categoria = $personadatosadicional->id_categoria;
                                    $id_situacion = $personadatosadicional->id_situacion;
                                    $id_unidad = $personadatosadicional->id_unidad;
                                    $fecha_inicio_servicio = $personadatosadicional->fecha_inicio_servicio;
                                    $tit_tipo_doc_ident = $personadatosadicional->tipo_doc_ident;
                                    $tit_nro_doc_ident = $personadatosadicional->nro_doc_ident;
                                    $tit_apellidos_nombres = $personadatosadicional->paterno." ".$personadatosadicional->materno.", ".$personadatosadicional->nombre;
                                    $tit_grado = $personadatosadicional->id_grado;
                                    $tit_situacion = $personadatosadicional->id_situacion;
                                    $tit_categoria = $personadatosadicional->id_categoria;
                                endif;
                                ?>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#tab7default" aria-controls="tab7default" role="tab" data-toggle="tab">DATOS DEL PACIENTE</a></li>
                                    <li role="presentation"><a href="#tab8default" aria-controls="tab8default" role="tab" data-toggle="tab">HISTORIA CLINICA</a></li>
                                    <li role="presentation"><a href="#tab9default" aria-controls="tab9default" role="tab" data-toggle="tab">LISTADO DE HOSPITALIZACIONES</a></li>
                                    <li role="presentation"><a href="#tab10default" aria-controls="tab10default" role="tab" data-toggle="tab">LISTADO DE RECETAS MEDICAS</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="tab7default">
                                        <div class="panel panel-info panel-bordered panel-sm" style="margin: 20px 20px">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>Tipo Documento</label>
                                                                <select class="form-control input-sm" id="id_tipo_doc_ident" name="id_tipo_doc_ident" onchange="maxlength_doc()" tabindex="3">
                                                                    @foreach($tipo_documento_identidad as $e)
                                                                    <option value="{{$e->id}}" @if($e->id ==
                                                                        $personal->id_tipo_doc_ident) selected @endif>
                                                                        {{$e->descripcion}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="nro_doc_ident" name="nro_doc_ident" type="text" value="{{$personal->nro_doc_ident}}" class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label></label>
                                                                <button type="button" class="form-control btn-primary" id="btnBuscarSaludpol" name="btnBuscarSaludpol" onclick="getSaludpolByTipoDocAndNroDoc();"> Buscar Paciente</button>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>Tipo Beneficiario &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="id_tipo_beneficiario"
                                                                    name="id_tipo_beneficiario"
                                                                    class="form-control input-sm" required=""
                                                                    {{-- onchange="cambiar_tipo_beneficiario();"> --}}
                                                                    <option value="">SELECCIONE</option>
                                                                    @foreach($tipo_beneficiario as $e)
                                                                    <option value="{{$e->id}}" @if ($e->id ==
                                                                        $personal->id_tipo_beneficiario) selected @endif>
                                                                        {{$e->descripcion}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Nombre &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="nombre" name="nombre" type="text"
                                                                    value="{{$personal->nombre}}"
                                                                    class="form-control input-sm" required=""
                                                                    style="text-transform:uppercase;"
                                                                    onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                                    autofocus="" onkeypress="return solo_letra(event)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Apellido Paterno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="paterno" name="paterno" type="text"
                                                                    value="{{$personal->paterno}}"
                                                                    class="form-control input-sm" required=""
                                                                    <?php //echo $readdatosper ?>
                                                                    style="text-transform:uppercase;"
                                                                    onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                                    onkeypress="return solo_letra(event)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Apellido Materno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="materno" name="materno" type="text"
                                                                    value="{{$personal->materno}}"
                                                                    class="form-control input-sm" required=""
                                                                    <?php //echo $readdatosper ?>
                                                                    style="text-transform:uppercase;"
                                                                    onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                                    onkeypress="return solo_letra(event)">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>Sexo &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="sexo" name="sexo" class="form-control input-sm" required="">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option @if($personal->sexo == "M") selected @endif value="M">MASCULINO</option>
                                                                    <option @if($personal->sexo == "F") selected @endif value="F">FEMENINO</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                    <label>Fecha Nac. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <!-- <input id="fecha_nac" name="fecha_nac"
                                                                    value="{{$personal->fecha_nac}}" type="text"
                                                                    placeholder="" class="form-control input-sm"
                                                                    > -->
                                                                    <div id="fecha_nacimiento_editable">
                                                                        <div class="input-group date fecha">
                                                                            <input readonly="" type="text" name="fecha_nac" id="fecha_nac" value="{{$personal->fecha_nac}}" class="form-control input-sm" placeholder="dd/mm/aaaa"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label>Edad</label>
                                                                <input type="text" id="edad" class="form-control input-sm" readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>Telefono</label>
                                                                <input id="telefono" name="telefono"
                                                                    value="{{$personal->telefono}}" type="text"
                                                                    placeholder="" class="form-control input-sm">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>Tipo de Sangre &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="id_tipo_sangre" name="id_tipo_sangre" class="form-control input-sm" required>
                                                                    <option value="">SELECCIONE</option>
                                                                        @foreach($tipo_sangre as $e)
                                                                            <option value="{{ $e->id }}" 
                                                                                data-grupo="{{ $e->grupo_sanguineo }}" 
                                                                                data-rh="{{ $e->factor_rh }}"
                                                                                @if($e->id == $personal->id_tipo_sangre) selected @endif>
                                                                                {{ $e->descripcion }}
                                                                            </option>
                                                                        @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label>G.S</label>
                                                                <input type="text" id="grupo_sanguineo" class="form-control input-sm" readonly>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label>R.H</label>
                                                                <input type="text" id="factor_rh" class="form-control input-sm" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>E-mail</label>
                                                                <input id="correo" name="correo" type="email"
                                                                    value="{{$personal->correo}}" placeholder=""
                                                                    class="form-control input-sm">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Observacion</label>
                                                                <input id="observacion" name="observacion" type="text"
                                                                    value="{{$personal->observacion}}" placeholder=""
                                                                    class="form-control input-sm">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>Departamento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <select id="id_departamento" name="id_departamento"
                                                            class="form-control input-sm" required="" onchange="getProvincia();">
                                                            <option value="">SELECCIONE</option>
                                                            @foreach($departamento as $e)
                                                            <option value="{{$e->id_departamento}}" @if ($e->id_departamento ==
                                                                $personal->id_departamento) selected @endif>
                                                                {{$e->descripcion}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Provincia &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <select id="id_provincia" name="id_provincia"
                                                            class="form-control input-sm" required="" onchange="getDistrito();">
                                                            @if($provincia != "")
                                                            @foreach($provincia as $e)
                                                            <option value="{{$e->id_provincia}}" @if ($e->id_provincia ==
                                                                $personal->id_provincia) selected @endif>
                                                                {{$e->descripcion}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Distrito &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <select id="id_distrito" name="id_distrito"
                                                            class="form-control input-sm" required="">
                                                            @if($distrito != "")
                                                            @foreach($distrito as $e)
                                                            <option value="{{$e->id_distrito}}" @if ($e->id_distrito ==
                                                                $personal->id_distrito) selected @endif>
                                                                {{$e->descripcion}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Domicilio &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <input id="domicilio" name="domicilio" value="{{$personal->domicilio}}" type="text" placeholder="" style="text-transform:uppercase;" class="form-control input-sm">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Grado Instrucción <span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <select id="id_grado_instruccion" name="id_grado_instruccion"
                                                            class="form-control input-sm" required="">
                                                            <option value="">SELECCIONE</option> 
                                                            @foreach($grado_inst as $e)
                                                            <option value="{{$e->id}}" @if($e->id ==
                                                                $personal->id_grado_instruccion) selected @endif>
                                                                {{$e->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Ocupación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <select id="id_ocupacion" name="id_ocupacion"
                                                            class="form-control input-sm" required="">
                                                            <option value="">SELECCIONE</option>
                                                            @foreach($ocupacion as $e)
                                                            <option value="{{$e->id}}" @if($e->id ==
                                                                $personal->id_ocupacion) selected @endif>
                                                                {{$e->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Estado Civil &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <select id="id_estado_civil" name="id_estado_civil"
                                                            class="form-control input-sm" required="">
                                                            <option value="">SELECCIONE</option>
                                                            @foreach($estado_civil as $e)
                                                            <option value="{{$e->id}}" @if($e->id ==
                                                                $personal->id_estado_civil) selected @endif>
                                                                {{$e->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Religión &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <select id="id_religion" name="id_religion" 
                                                            class="form-control input-sm" required="">
                                                            <option value="">SELECCIONE</option>
                                                            @foreach($religion as $e)
                                                            <option value="{{$e->id}}" @if($e->id ==
                                                                $personal->id_religion) selected @endif>
                                                                {{$e->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Etnia &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <select id="id_etnia" name="id_etnia"
                                                            class="form-control input-sm" required="">
                                                            <option value="">SELECCIONE</option>
                                                            @foreach($etnia as $e)
                                                                <option value="{{ $e->id }}" @if ($e->id == $personal->id_etnia) selected @endif>
                                                                    {{ $e->codigo_etnia }} - {{ $e->nombre_etnia }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row" id="panel-discapacitado" style="display: none;">
                                                    <div class="col-md-2">
                                                        <label>Discapacidad</label>
                                                        <select id="discapacidad" name="discapacidad" class="form-control input-sm" required>
                                                            <option value="">SELECCIONE</option>
                                                            <option value="1" {{ $personal->discapacidad == 1 ? 'selected' : '' }}>SI</option>
                                                            <option value="0" {{ $personal->discapacidad == 0 ? 'selected' : '' }}>NO</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Otro Seguro</label>
                                                        <input id="otro_seguro" name="otro_seguro" type="text" value="{{$personal->otro_seguro}}" 
                                                        class="form-control input-sm" required="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Discapacidad Carnet</label>
                                                        <select id="discapacidad_carnet" name="discapacidad_carnet" class="form-control input-sm" required>
                                                            <option value="">SELECCIONE</option>
                                                            <option value="1" {{ $personal->discapacidad_carnet == 1 ? 'selected' : '' }}>SI</option>
                                                            <option value="0" {{ $personal->discapacidad_carnet == 0 ? 'selected' : '' }}>NO</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Nro de Carnet Conadis</label>
                                                        <input id="nro_carnet_conadis" name="nro_carnet_conadis" type="text" value="{{$personal->nro_carnet_conadis}}" 
                                                        class="form-control input-sm" required="" onkeypress="return IsNumber(event)">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Color de Carnet Conadis</label>
                                                        <select id="color_carnet_conadis" name="color_carnet_conadis" class="form-control input-sm" required>
                                                            <option value="">SELECCIONE</option>
                                                            <option value="0" {{ $personal->color_carnet_conadis == 0 ? 'selected' : '' }}>NO ESPECIFICA</option>
                                                            <option value="1" {{ $personal->color_carnet_conadis == 1 ? 'selected' : '' }}>AMARILLO</option>
                                                            <option value="2" {{ $personal->color_carnet_conadis == 2 ? 'selected' : '' }}>CELESTE</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- TUTOR -->
                                        <div class="panel panel-info panel-bordered panel-sm panel-body" style="margin: 20px 20px 0 20px" id="panel_tutor">
                                            <div class="form-group" id="grupoAsignarTutor" style="display: none;">
                                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                    <label>Asignar Tutor</label>
                                                </div>
                                                <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                    <button type="button" id="asignarTutor" class="btn btn-lg btn-toggle"
                                                    aria-pressed="false" autocomplete="off" onclick="activarToggleTutor()">
                                                    <div class="handle"></div>
                                                    </button>
                                                </div>
                                            </div>
                                            <fieldset id="fs_tutor" style="display:none">
                                                <div class="panel panel-info panel-bordered panel-sm" style="margin: 0px 20px 0 20px">
                                                    <div class="panel-heading">
                                                        <div class="panel-title text-center">
                                                            <b>INFORMACION DEL TUTOR</b>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <label>Tipo Parentesco <span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="id_parentesco_tutor" name="id_parentesco" class="form-control input-sm" required>
                                                                    <option value="">SELECCIONE</option>
                                                                    @foreach($parentesco as $e)
                                                                        <option value="{{ $e->id }}"
                                                                            @if(old('id_parentesco') == $e->id)
                                                                                selected
                                                                            @elseif(isset($registro_tutor) && $registro_tutor->id_parentesco == $e->id)
                                                                                selected
                                                                            @endif>
                                                                            {{ $e->descripcion }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>Tipo Documento <span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="tipo_documento_tutor" name="tipo_documento" class="form-control input-sm" required>
                                                                    <option value="">SELECCIONE</option>
                                                                    @foreach(['DNI', 'CARNET EXTRANJERIA', 'PASAPORTE', 'DOCUMENTO DE IDENTIDAD EXTRANJERO', 'SDI'] as $tipo)
                                                                        <option value="{{ $tipo }}"
                                                                            @if(old('tipo_documento') == $tipo)
                                                                                selected
                                                                            @elseif(isset($registro_tutor) && $registro_tutor->tipo_documento == $tipo)
                                                                                selected
                                                                            @endif>
                                                                            {{ $tipo }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                           <div class="col-md-2">
                                                                <label>Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="numero_documento_tutor" name="numero_documento" type="text" 
                                                                    value="{{ old('numero_documento', isset($registro_tutor) ? $registro_tutor->numero_documento : '') }}"
                                                                    class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Nombres &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="nombres_tutor" name="nombres" type="text" 
                                                                value="{{ old('nombres', isset($registro_tutor) ? $registro_tutor->nombres : '') }}"
                                                                style="text-transform:uppercase;" class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                                autofocus="" onkeypress="return solo_letra(event)">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label>Apellidos &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="apellidos_tutor" name="apellidos" type="text"
                                                                value="{{ old('apellidos', isset($registro_tutor) ? $registro_tutor->apellidos : '') }}"
                                                                style="text-transform:uppercase;" class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                                autofocus="" onkeypress="return solo_letra(event)">
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label>Teléfono &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="telefono_tutor" name="telefono_tutor" type="text"
                                                                value="{{ old('telefono_tutor', isset($registro_tutor) ? $registro_tutor->telefono_tutor : '') }}"
                                                                style="text-transform:uppercase;" class="form-control input-sm" onkeypress="return IsNumber(event)">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label>Domicilio</label>
                                                                <input id="direccion_tutor" name="direccion" type="text"
                                                                value="{{ old('direccion', isset($registro_tutor) ? $registro_tutor->direccion : '') }}"
                                                                style="text-transform:uppercase;" class="form-control input-sm">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label>Observación</label>
                                                                <input id="observacion_tutor" name="observacion_tutor" type="text"
                                                                value="{{ old('observacion_tutor', isset($registro_tutor) ? $registro_tutor->observacion_tutor : '') }}"
                                                                class="form-control input-sm">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="panel panel-info panel-bordered panel-sm" style="margin: 20px 20px 0 20px" id="panel_paciente_titular">
                                            <div class="panel-heading">
                                                <div class="panel-title text-center">
                                                    <b>INFORMACION DEL TITULAR</b>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <!-- Datos del Titular -->
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>DNI</label>
                                                        <input autocomplete="off" id="dni_paciente"
                                                            name="dni_paciente" type="text" value=""
                                                            class="form-control input-sm"
                                                            onkeypress="return IsNumber(event)">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label></label>
                                                        <input class="form-control btn-primary" id="buscar_titular"
                                                            name="buscar_titular" type="button" value="Buscar" onclick="getDirrehumByDni('titular')"/>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Tipo Documento</label>
                                                        <input id="tipo_doc_titular2"
                                                            name="tipo_doc_titular2" value="DNI" type="text"
                                                            class="form-control input-sm" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <input id="dni_titular2" name="dni_titular2"
                                                            value="{{$personal->dni_titular}}" type="text"
                                                            class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Nombre Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <input id="nombre_titular2" name="nombre_titular2" type="text"
                                                            value="{{$personal->nombre_titular}}"
                                                            class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                            autofocus="" onkeypress="return solo_letra(event)">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Paterno Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <input id="paterno_titular2" name="paterno_titular2" type="text"
                                                            value="{{$personal->paterno_titular}}"
                                                            class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                            autofocus="" onkeypress="return solo_letra(event)">
                                                    </div>
                                                    <div class="col-md-3">
                                                            <label>Materno Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                            <input id="materno_titular2" name="materno_titular2" type="text"
                                                            value="{{$personal->materno_titular}}"
                                                            class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                            autofocus="" onkeypress="return solo_letra(event)">
                                                    </div>
                                                     <div class="col-md-3">
                                                        <label>N° Carnet &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <input id="cips2" name="cips2" value="{{$cips}}" type="text" placeholder=""
                                                            class="form-control input-sm" onkeypress="return IsNumber(event)">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Grado &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <select id="grado2" name="grado2" class="form-control input-sm">
                                                            <option value="">SELECCIONE</option>
                                                            @foreach($grados_lista as $e)
                                                                @if(!empty($e->grado))
                                                                    <option value="{{ $e->grado }}" {{ ($grado_seleccionado == $e->grado) ? 'selected' : '' }}>
                                                                        {{ $e->grado }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Situación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <select id="situacion_paciente2" name="situacion_paciente2" class="form-control input-sm">
                                                            <option value="">SELECCIONE</option>
                                                            @foreach($situaciones_lista as $e)
                                                                @if(!empty($e->situacion_paciente))
                                                                    <option value="{{ $e->situacion_paciente }}" {{ ($situacion_seleccionada == $e->situacion_paciente) ? 'selected' : '' }}>
                                                                        {{ $e->situacion_paciente }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- FAMILIARES -->
                                        <div class="panel panel-info panel-bordered panel-sm panel-body" id="panel_paciente_familiar" style="margin: 20px 20px 0 20px">
                                            <div class="form-group">
                                                <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                    <label>Asginar Titular</label>
                                                </div>
                                                <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                    <button type="button" id="asignarTitular" class="btn btn-lg btn-toggle"
                                                    data-toggle="button" aria-pressed="false" autocomplete="off" onclick="activarToggle()">
                                                    <div class="handle"></div>
                                                    </button>
                                                </div>
                                            </div>
                                            <fieldset id="fs_paciente_familiar" style="display:none">
                                                <div class="panel panel-info panel-bordered panel-sm" style="margin: 0px 20px 0 20px">
                                                    <div class="panel-heading">
                                                        <div class="panel-title text-center">
                                                            <b>INFORMACION DEL TITULAR</b>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>DNI</label>
                                                                <input autocomplete="off" id="dni_paciente"
                                                                    name="dni_paciente" type="text" value=""
                                                                    class="form-control input-sm"
                                                                    onkeypress="return IsNumber(event)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label></label>
                                                                <input class="form-control btn-primary" id="buscar_titular_familiar"
                                                                    name="buscar_titular" type="button" value="Buscar" onclick="getDirrehumByDni('familiar')"/>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Tipo Documento</label>
                                                                <input id="tipo_doc_titular" name="tipo_doc_titular"
                                                                    value="DNI" type="text" 
                                                                    class="form-control input-sm" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="dni_titular" name="dni_titular"
                                                                    value="{{$personal->dni_titular}}" type="text"
                                                                    class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Nombre Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="nombre_titular" name="nombre_titular" type="text"
                                                                    value="{{$personal->nombre_titular}}"
                                                                    class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                                    autofocus="" onkeypress="return solo_letra(event)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Paterno Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="paterno_titular" name="paterno_titular" type="text"
                                                                    value="{{$personal->paterno_titular}}"
                                                                    class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                                    autofocus="" onkeypress="return solo_letra(event)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Materno Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="materno_titular" name="materno_titular" type="text"
                                                                    value="{{$personal->materno_titular}}"
                                                                    class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                                                    autofocus="" onkeypress="return solo_letra(event)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>N° Carnet &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="cips" name="cips" value="{{$cips}}"
                                                                    type="text" placeholder="" class="form-control input-sm" onkeypress="return IsNumber(event)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Grado &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="grado_familiar" name="grado" class="form-control input-sm">
                                                                    <option value="">SELECCIONE</option>
                                                                    @foreach($grados_lista as $e)
                                                                        @if(!empty($e->grado))
                                                                            <option value="{{ $e->grado }}" {{ ($grado_seleccionado == $e->grado) ? 'selected' : '' }}>
                                                                                {{ $e->grado }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Situación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="situacion_familiar" name="situacion_paciente"
                                                                    class="form-control input-sm" required="">
                                                                        <option value="">SELECCIONE</option>
                                                                        @foreach($situaciones_lista as $e)
                                                                            @if(!empty($e->situacion_paciente))
                                                                                <option value="{{ $e->situacion_paciente }}" {{ ($situacion_seleccionada == $e->situacion_paciente) ? 'selected' : '' }}>
                                                                                    {{ $e->situacion_paciente }}
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <a href="/all_asegurados/{{ Auth::user()->id }}" class="form-control btn btn-danger">Cancelar</a>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()">Guardar</button>
                                        </div>
                                    </div> <!-- .tab-pane -->
                                    <div role="tabpanel" class="tab-pane fade" id="tab8default">
                                        <div class="panel panel-info panel-bordered panel-sm" style="margin: 20px 20px">
                                            <div class="panel-body">
                                                
                                                <?php $readonly = $permiso->modificar === "1" ? "" : "readonly"; ?>

                                                @foreach($historias_por_establecimiento as $es)
                                                    <div class="col-md-9">
                                                        <label>Establecimiento (IPRESS PNP)</label>
                                                        <input type="hidden" name="id_asegurado_historia[]" value="{{ $es->id_asegurado_historia }}">
                                                        <input name="nombre_est" value="{{$es->establecimiento}}" type="text" class="form-control input-sm" readOnly>
                                                        <input name="id_establecimientos[]" value="{{$es->id_establecimiento}}" type="hidden" class="form-control input-sm">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Historia</label>
                                                        <input type="hidden" name="nro_historias_temp[]" value="{{ $es->nro_historia }}">
                                                        <input name="nro_historias[]" value="{{$es->nro_historia}}" type="text" class="form-control input-sm" {{ $readonly }}>
                                                    </div>
                                                @endforeach

                                                @if ($permiso->crear === "1")
                                                    <div class="col-md-12">
                                                        <hr>
                                                    </div>
                                                    
                                                    <div id="historia_clinica_nueva">
                                                        <div class="col-md-9">
                                                            <label>Establecimiento (IPRESS PNP)</label>
                                                            <input type="hidden" name="id_asegurado_historia[]" value="0">
                                                            <select name="id_establecimiento_nuevo" id="id_establecimiento_nuevo" class="form-control input-sm" style="width:100%">
                                                                <option value="0">-- Seleccione --</option>
                                                                @foreach ($establecimientos as $est)
                                                                    <?php $is_establecimiento_registrado = "0"; ?>
                                                                    @foreach ($historias_por_establecimiento as $es)
                                                                        @if ($es->id_establecimiento == $est->id)
                                                                            <?php $is_establecimiento_registrado = "1"; ?>
                                                                            @break
                                                                        @endif
                                                                    @endforeach

                                                                    @if ($is_establecimiento_registrado == "0" && $user_data->id_establecimiento == $est->id)
                                                                        {{-- <option selected value="{{ $est->id }}">{{ $est->nombre }}</option> --}}
                                                                        <option value="{{ $est->id }}">{{ $est->nombre }}</option>
                                                                    @endif
                                                                @endforeach
                                                                {{-- @if (intval($user_data->flagestablecimientos) === 1)
                                                                @else
                                                                    <?php $is_establecimiento_registrado = "0"; ?>
                                                                    @foreach ($historias_por_establecimiento as $es)
                                                                        @if ($es->id_establecimiento == $establecimiento_user_data->id)
                                                                            <?php $is_establecimiento_registrado = "1"; ?>
                                                                            @break
                                                                        @endif
                                                                    @endforeach

                                                                    @if ($is_establecimiento_registrado == "0")
                                                                        <option value="{{ $establecimiento_user_data->id }}">{{ $establecimiento_user_data->nombre }}</option>
                                                                    @endif
                                                                @endif --}}
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label>Historia</label>
                                                            <input id="" name="nro_historia_nuevo" value="" type="text" class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <a href="/all_asegurados/{{ Auth::user()->id }}" class="form-control btn btn-danger">Cancelar</a>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <button id="btnRegistrarHistoriaClinica" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()">Guardar</button>
                                        </div>
                                    </div> <!-- .tab-pane -->
                                   
                                    <div role="tabpanel" class="tab-pane fade" id="tab9default">
                                        <div class="panel panel-info panel-bordered panel-sm" style="margin: 20px 20px">
                                          <div class="container-fluid">
                                            <div class="row col-md-14">
                                              <div class="panel panel-primary filterable">
                                                <div class="panel-heading" style="padding: 0;">
                                                  <h3 class="panel-title" style="padding: 10px 15px;">
                                                    LISTA DE ATENCIONES DE HOSPITALIZACION
                                                  </h3>
                                                </div>
                                                <div class="panel-body">
                                                    <form class="form-horizontal" method="post" action="{{ route('all_hospitalizados_asegurados') }}">
                                                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                                                        <input type="hidden" name="id_asegurado_hosp" id="id_asegurado_hosp" value="{{ $id_asegurado }}">
                                                
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="form-inline">
                                                                    <label for="fecha_ini_hosp" class="mr-2">Fecha Inicio</label>
                                                                    <input class="form-control input-sm" type="text" name="fecha_ini_hosp" id="fecha_ini_hosp"
                                                                        placeholder="Fecha Inicio" maxlength="10" value="{{ $fecha_ini }}" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="form-inline">
                                                                    <label for="fecha_fin_hosp" class="mr-2">Fecha Fin</label>
                                                                    <input class="form-control input-sm" type="text" name="fecha_fin_hosp" id="fecha_fin_hosp"
                                                                        placeholder="Fecha Fin" maxlength="10" value="{{ $fecha_fin }}" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-6">
                                                                {{-- <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button"> --}}
                                                                <input id="btnBuscarHospitalizados" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                                                            </div>
                                                        </div>
                                                        </br>
                                                        <div class="col-md-14">
                                                            <div class="table-responsive">
                                                                <table id="tblHospitalizados" class="table table-bordered table-hover" style="width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>N° Hospitalización</th>
                                                                            <th>N° Asegurado</th>
                                                                            <th>Fecha Atención</th>
                                                                            <th>DNI Beneficiario</th>
                                                                            <th>Nombre Beneficiario</th>
                                                                            <th>Paterno Beneficiario</th>
                                                                            <th>Materno Beneficiario</th>
                                                                            <th>Nombre Titular</th>
                                                                            <th>Paterno Titular</th>
                                                                            <th>Materno Titular</th>
                                                                            <th>Fecha Nac. Beneficiario</th>
                                                                            <th>Sexo</th>
                                                                            <th>Tipo Beneficiario</th>
                                                                            <th>Nro Historia</th>
                                                                            <th>Grado</th>
                                                                            <th>Acción</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </form>
                                                 </div>
                                              </div>
                                            </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab10default">
                                        <div class="panel panel-info panel-bordered panel-sm" style="margin: 20px 20px">
                                          <div class="container-fluid">
                                            <div class="row col-md-14">
                                              <div class="panel panel-primary filterable">
                                                <div class="panel-heading" style="padding: 0;">
                                                <h3 class="panel-title" style="padding: 10px 15px;">
                                                    LISTA DE RECETAS MEDICAS
                                                </h3>
                                                </div>
                                                <div class="panel-body">
                                                    <form class="form-horizontal" method="post" action="{{ route('listado_receta_vale') }}">
                                                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                                                        <input type="hidden" name="id_asegurado_rec" id="id_asegurado_rec" value="{{ $id_asegurado }}">
                                                
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="form-inline">
                                                                    <label for="fecha_ini_rec" class="mr-2">Fecha Inicio</label>
                                                                    <input class="form-control input-sm" type="text" name="fecha_ini_rec" id="fecha_ini_rec"
                                                                        placeholder="Fecha Inicio" maxlength="10" value="{{ $fecha_ini }}" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="form-inline">
                                                                    <label for="fecha_fin_rec" class="mr-2">Fecha Fin</label>
                                                                    <input class="form-control input-sm" type="text" name="fecha_fin_rec" id="fecha_fin_rec"
                                                                        placeholder="Fecha Fin" maxlength="10" value="{{ $fecha_fin }}" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-6">
                                                                {{-- <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button"> --}}
                                                                <input id="btnBuscarRecetas" class="form-control btn btn-primary input-sm" value="Buscar" type="button">
                                                            </div>
                                                        </div>
                                                        </br>
                                                        <div class="col-md-14">
                                                            <div class="table-responsive">
                                                                <table id="tblRecetas" class="table table-bordered table-hover" style="width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>ID</th>
                                                                            <th>Servicio</th>
                                                                            <th>Fecha de Creación</th>
                                                                            <th>Tipo de Receta</th>
                                                                            <th>N° Receta</th>
                                                                            <th>N° Asegurado</th>
                                                                            <th>Dni de Beneficiario</th>
                                                                            <th>Nombre de Beneficiario</th>
                                                                            <th>Paterno Beneficiario</th>
                                                                            <th>Materno Beneficiario</th>
                                                                            <th>Tipo Beneficiario</th>
                                                                            <th>Grado</th>
                                                                            <th>Acción</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                       </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal de carga familiar de un titular -->
<div class="modal fade" id="modalCargaFamiliar" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">CARGA FAMILIAR<span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="divCargaFamiliar">
                
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection