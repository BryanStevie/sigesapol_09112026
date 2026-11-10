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

  .inline-field {
    display: flex;
    align-items: center;
    margin-top: 5px;
  }

  .inline-field label {
    width: 180px; 
    margin-bottom: 0;
    font-size: 12px;
  }

  .inline-field input {
    flex: 1;
    font-size: 10px;
  }
  a {
    color: #3c763d;
    text-decoration: none;
  }

#modalResumen.modal .modal-dialog {
    width: 95% !important;
    max-width: 1200px !important;
    margin: 30px auto !important;
}

@media (min-width: 768px) {
    #modalResumen .modal-dialog {
        width: 95% !important;
        max-width: 1200px !important;
    }
}

.bootbox.modal .modal-dialog {
    width: 95% !important;
    max-width: 1200px !important;
}

.swal-afiliacion {
  width: 350px !important;
  padding: 1rem !important;
}

.swal-afiliacion .swal2-icon {
  transform: scale(0.5);
  margin: 0;
  font-size: 24px!important;
}

.swal-afiliacion .swal2-title {
  font-size: 21px !important;
  margin: 0.3em 0;
}

.swal-afiliacion .swal2-html-container {
  font-size: 18px !important;
  margin: 0.3em 0 1em 0;
}

.swal-afiliacion .swal2-confirm {
  padding: 6px 50px !important;
  font-size: 13px !important;
}

.bloqueado-css{
    pointer-events: none;
    background: #eee;
}

.btn-info-custom {
    background-color: #67BD15;
    border-color: #67BD15;
    color: #fff;
}

.btn-info-custom:hover,
.btn-info-custom:focus,
.btn-info-custom:active {
    background-color: #559C13;
    border-color: #559C13;
    color: #fff;
}

.marca_agua_container {
    position: relative;
}
.marca_agua_overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none;
    z-index: 999;
    overflow: hidden;
}
.marca_agua_overlay span {
    position: absolute;
    color: rgba(5, 98, 24, 0.12);
    font-size: 14px;
    font-weight: bold;
    white-space: nowrap;
    transform: rotate(-35deg);
    user-select: none;
    letter-spacing: 1px;
}
@media print {
    .marca_agua_overlay span {
        display: none !important;
    }
    .marca_agua_overlay {
        z-index: 0 !important;
        bottom: 0 !important;
        height: auto !important;
        background-image: var(--marca-agua-print) !important;
        background-repeat: no-repeat !important;
        background-size: 100% 100% !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .marca_agua_container > :not(.marca_agua_overlay) {
        position: relative;
        z-index: 1;
    }
    .marca_agua_container .panel,
    .marca_agua_container .panel-heading,
    .marca_agua_container .panel-body,
    .marca_agua_container .modal-content,
    .marca_agua_container .modal-body,
    .marca_agua_container .well,
    .marca_agua_container .form-control,
    .marca_agua_container table,
    .marca_agua_container tr,
    .marca_agua_container td,
    .marca_agua_container th {
        background: transparent !important;
    }
    .container:has(.marca_agua_container) {
        width: 1200px !important;
        max-width: none !important;
        zoom: 0.6;
    }
    .marca_agua_container a[href]:after {
        content: none !important;
    }
    .marca_agua_container .table-responsive {
        overflow: visible !important;
    }
    .container:has(.marca_agua_container) .col-sm-1, .container:has(.marca_agua_container) .col-sm-2, .container:has(.marca_agua_container) .col-sm-3,
    .container:has(.marca_agua_container) .col-sm-4, .container:has(.marca_agua_container) .col-sm-5, .container:has(.marca_agua_container) .col-sm-6,
    .container:has(.marca_agua_container) .col-sm-7, .container:has(.marca_agua_container) .col-sm-8, .container:has(.marca_agua_container) .col-sm-9,
    .container:has(.marca_agua_container) .col-sm-10, .container:has(.marca_agua_container) .col-sm-11, .container:has(.marca_agua_container) .col-sm-12,
    .container:has(.marca_agua_container) .col-md-1, .container:has(.marca_agua_container) .col-md-2, .container:has(.marca_agua_container) .col-md-3,
    .container:has(.marca_agua_container) .col-md-4, .container:has(.marca_agua_container) .col-md-5, .container:has(.marca_agua_container) .col-md-6,
    .container:has(.marca_agua_container) .col-md-7, .container:has(.marca_agua_container) .col-md-8, .container:has(.marca_agua_container) .col-md-9,
    .container:has(.marca_agua_container) .col-md-10, .container:has(.marca_agua_container) .col-md-11, .container:has(.marca_agua_container) .col-md-12 {
        float: left;
    }
    .container:has(.marca_agua_container) .col-sm-1 { width: 8.33333333%; }
    .container:has(.marca_agua_container) .col-sm-2 { width: 16.66666667%; }
    .container:has(.marca_agua_container) .col-sm-3 { width: 25%; }
    .container:has(.marca_agua_container) .col-sm-4 { width: 33.33333333%; }
    .container:has(.marca_agua_container) .col-sm-5 { width: 41.66666667%; }
    .container:has(.marca_agua_container) .col-sm-6 { width: 50%; }
    .container:has(.marca_agua_container) .col-sm-7 { width: 58.33333333%; }
    .container:has(.marca_agua_container) .col-sm-8 { width: 66.66666667%; }
    .container:has(.marca_agua_container) .col-sm-9 { width: 75%; }
    .container:has(.marca_agua_container) .col-sm-10 { width: 83.33333333%; }
    .container:has(.marca_agua_container) .col-sm-11 { width: 91.66666667%; }
    .container:has(.marca_agua_container) .col-sm-12 { width: 100%; }
    .container:has(.marca_agua_container) .col-md-1 { width: 8.33333333%; }
    .container:has(.marca_agua_container) .col-md-2 { width: 16.66666667%; }
    .container:has(.marca_agua_container) .col-md-3 { width: 25%; }
    .container:has(.marca_agua_container) .col-md-4 { width: 33.33333333%; }
    .container:has(.marca_agua_container) .col-md-5 { width: 41.66666667%; }
    .container:has(.marca_agua_container) .col-md-6 { width: 50%; }
    .container:has(.marca_agua_container) .col-md-7 { width: 58.33333333%; }
    .container:has(.marca_agua_container) .col-md-8 { width: 66.66666667%; }
    .container:has(.marca_agua_container) .col-md-9 { width: 75%; }
    .container:has(.marca_agua_container) .col-md-10 { width: 83.33333333%; }
    .container:has(.marca_agua_container) .col-md-11 { width: 91.66666667%; }
    .container:has(.marca_agua_container) .col-md-12 { width: 100%; }
}

</style>
<script>
  (function () {
    if (!window.matchMedia || window.marcaAguaImpresionRegistrada) return;
    window.marcaAguaImpresionRegistrada = true;
    var mq = window.matchMedia('print');
    var ctx = document.createElement('canvas').getContext('2d');
    ctx.font = '14px Arial, sans-serif';
    function svg_marca_agua(W, H, texto) {
      var txt = String(texto).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
      var anchoTexto = ctx.measureText(texto).width + texto.length;
      var textos = '';
      for (var r = 0; r < Math.ceil(H / 100) + 2; r++) {
        for (var c = 0; c < Math.ceil(W / 220) + 2; c++) {
          var x = c * 220 - 40 + anchoTexto / 2;
          var y = r * 100 - 20 + 9;
          textos += '<text x="' + x + '" y="' + y + '" text-anchor="middle" dominant-baseline="middle" transform="rotate(-35 ' + x + ' ' + y + ')">' + txt + '</text>';
        }
      }
      var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '">' +
        '<g font-family="Arial, sans-serif" font-size="14" letter-spacing="1" fill="#e6e6e6">' + textos + '</g></svg>';
      return 'url("data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg) + '")';
    }
    function al_cambiar_impresion(e) {
      if (!e.matches) return;
      var overlays = document.querySelectorAll('.marca_agua_overlay');
      for (var i = 0; i < overlays.length; i++) {
        var span = overlays[i].querySelector('span');
        if (!span || !overlays[i].offsetWidth) continue;
        overlays[i].style.setProperty('--marca-agua-print', svg_marca_agua(overlays[i].offsetWidth, overlays[i].offsetHeight, span.textContent));
      }
    }
    if (mq.addEventListener) mq.addEventListener('change', al_cambiar_impresion);
    else if (mq.addListener) mq.addListener(al_cambiar_impresion);
  })();
</script>

<script>
var texto_marca_agua_editar_paciente2 = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';

function generar_marca_agua_editar_paciente2() {
    var overlay = document.getElementById('marca_agua_overlay_editar_paciente2');
    if (!overlay) return;

    overlay.innerHTML = '';

    var container = overlay.parentElement;
    var activePane = container ? container.querySelector('.tab-pane.active') : null;
    var W = (container ? container.offsetWidth : 0) || overlay.offsetWidth || 800;
    var H = (activePane ? activePane.scrollHeight : 0) || overlay.offsetHeight || 600;
    overlay.style.height = H + 'px';

    var cols = Math.ceil(W / 220) + 1;
    var rows = Math.ceil(H / 100) + 1;

    for (var r = 0; r < rows; r++) {
        for (var c = 0; c < cols; c++) {
            var span = document.createElement('span');
            span.textContent = texto_marca_agua_editar_paciente2;
            span.style.left = (c * 220 - 40) + 'px';
            span.style.top  = (r * 100 - 20) + 'px';
            overlay.appendChild(span);
        }
    }
}

$(document).ready(function () {
    generar_marca_agua_editar_paciente2();

    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        generar_marca_agua_editar_paciente2();
    });

    var resizeMarcaAguaEditarPaciente2Timer = null;
    $(window).on('resize', function () {
        clearTimeout(resizeMarcaAguaEditarPaciente2Timer);
        resizeMarcaAguaEditarPaciente2Timer = setTimeout(function () {
            generar_marca_agua_editar_paciente2();
        }, 250);
    });

    if (window.ResizeObserver) {
        var observadorMarcaAguaEditarPaciente2Timer = null;
        var observadorMarcaAguaEditarPaciente2 = new ResizeObserver(function () {
            clearTimeout(observadorMarcaAguaEditarPaciente2Timer);
            observadorMarcaAguaEditarPaciente2Timer = setTimeout(function () {
                generar_marca_agua_editar_paciente2();
            }, 150);
        });
        $('#marca_agua_overlay_editar_paciente2').parent().children('.tab-pane').each(function () {
            observadorMarcaAguaEditarPaciente2.observe(this);
        });
    }

    $.ajax({
        url: '/auditoria-hc/registrar',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            origen: 'Asegurados Editar',
            doc_buscado: $('#nro_doc_ident').val(),
            id_tipo_atencion: null
        }
    });
});
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// CALCULAR EDAD ASEGURADO
function calcularEdad(fecha) {
    if (!fecha || typeof fecha !== 'string') return '';
    let dia, mes, anio;
    if (fecha.includes('/')) {
        [dia, mes, anio] = fecha.split('/');
    } else if (fecha.includes('-')) {
        let partes = fecha.split('-');
        if (partes[0].length === 4) {
            [anio, mes, dia] = partes;
        } else {
            [dia, mes, anio] = partes;
        }
    } else {
        return '';
    }
    dia = parseInt(dia, 10);
    mes = parseInt(mes, 10) - 1;
    anio = parseInt(anio, 10);
    let fechaNacimiento = new Date(anio, mes, dia);
    if (isNaN(fechaNacimiento.getTime())) return '';
    let hoy = new Date();
    let años = hoy.getFullYear() - anio;
    let meses = hoy.getMonth() - mes;
    let dias = hoy.getDate() - dia;
    if (dias < 0) {
        meses--;
        let ultimoMes = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
        dias += ultimoMes.getDate();
    }
    if (meses < 0) {
        años--;
        meses += 12;
    }
    if (años < 0) return '';
    return `${años} años, ${meses} meses, ${dias} días`;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////// DATEPICKER PARA LA FECHA DE NACIMIENTO
$(document).ready(function() {
    $('.fecha').datepicker({
        language: "es",
        format: "dd/mm/yyyy",
        autoclose: true
    });

    $("#id_establecimiento_nuevo").select2({
	    width: 'resolve'
	});

    const inputNroHistoriaNuevo = $('input[name="nro_historia_nuevo"]');

    // Seleccionar establecimiento para nueva historia
    $('#id_establecimiento_nuevo').on('change', function () {
        if ($(this).val() == 76) {
            inputNroHistoriaNuevo.val('').prop('readonly', true);
            $('#grupo_apertura_hc').show();
            $('#btn_aperturar_hc, #btn_reaperturar_hc').removeClass('active');
        } else {
            $('#grupo_apertura_hc').hide();
            inputNroHistoriaNuevo.prop('readonly', false);
        }
    });

    inputNroHistoriaNuevo.prop('readonly', true);

    // Boton Aperturar Historia
    $('#btn_aperturar_hc').on('click', function () {
        bootbox.confirm({
            size: "small",
            message: '¿Desea generar N° de Historia Clínica?',
            buttons: {
                confirm: { label: 'Sí, generar', className: 'btn-success' },
                cancel: { label: 'Cancelar', className: 'btn-danger' }
            },
            callback: function (confirmado) {
                if (!confirmado) return;

                Swal.fire({
                    title: 'Generando...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => Swal.showLoading()
                });

                $.post('/api/asegurado-historias/aperturar', {
                    _token: "{{ csrf_token() }}",
                    id_asegurado: $('#id_asegurado_hosp').val(),
                    id_establecimiento: $('#id_establecimiento_nuevo').val()
                })
                .done(function (res) {
                    Swal.fire({
                        title: 'Historia Clínica generada',
                        text: 'N° ' + res.nro_historia,
                        icon: 'success',
                        allowOutsideClick: false
                    }).then(function () { location.reload(); });
                })
                .fail(function (xhr) {
                    const msg = (xhr.responseJSON && xhr.responseJSON.error) || 'No se pudo generar la Historia Clínica.';
                    Swal.fire('Error', msg, 'error');
                });
            }
        });
    });

    // Botones por default para historias clinicas indivuales
    function cerrarEdicionFila(fila, valor) {
        fila.find('.input-nro-historia').val(valor).prop('readonly', true).css('border', '');
        fila.find('.btn-guardar-historia, .btn-cancelar-historia').hide();
        fila.find('.btn-editar-historia').show();
    }

    // Boton editar historia individualmente
    $(document).on('click', '.btn-editar-historia', function () {
        const fila = $(this).closest('.fila-historia');
        fila.find('.input-nro-historia').prop('readonly', false).focus();
        $(this).hide();
        fila.find('.btn-guardar-historia, .btn-cancelar-historia').show();
    });

    // Boton cancelar historia individualmente
    $(document).on('click', '.btn-cancelar-historia', function () {
        const fila = $(this).closest('.fila-historia');
        cerrarEdicionFila(fila, fila.data('original'));
    });

    // Boton guardar cambio de historia individualmente
    $(document).on('click', '.btn-guardar-historia', function () {
        const fila = $(this).closest('.fila-historia');
        const input = fila.find('.input-nro-historia');
        const nuevo = input.val().trim();
        const original = String(fila.data('original'));

        if (nuevo === '') {
            input.css('border', '2px solid red');
            bootbox.alert('El número de historia es obligatorio.');
            return;
        }

        if (nuevo === original) {
            cerrarEdicionFila(fila, original);
            return;
        }

        const btn = $(this);

        bootbox.confirm({
            size: "small",
            message: '¿Desea guardar los cambios del N° de Historia Clínica?',
            buttons: {
                confirm: { label: 'Sí, guardar', className: 'btn-success' },
                cancel: { label: 'Cancelar', className: 'btn-danger' }
            },
            callback: function (confirmado) {
                if (!confirmado) return;

                btn.prop('disabled', true);

                $.post('/api/asegurado-historias/actualizar', {
                    _token: "{{ csrf_token() }}",
                    id_asegurado_historia: fila.data('id'),
                    nro_historia: nuevo
                })
                .done(function (res) {
                    fila.data('original', res.nro_historia).attr('data-original', res.nro_historia);
                    cerrarEdicionFila(fila, res.nro_historia);
                    bootbox.alert('N° de Historia Clínica actualizado.');
                })
                .fail(function (xhr) {
                    let msg = 'No se pudo actualizar la Historia Clínica.';
                    if (xhr.responseJSON) {
                        msg = xhr.responseJSON.error || (xhr.responseJSON.nro_historia && xhr.responseJSON.nro_historia[0]) || msg;
                    }
                    input.css('border', '2px solid red');
                    bootbox.alert(msg);
                })
                .always(function () {
                    btn.prop('disabled', false);
                });
            }
        });
    });

    // Boton de Reaperturar HC
    $('#btn_reaperturar_hc').on('click', function () {
        $(this).addClass('active');
        inputNroHistoriaNuevo.prop('readonly', false).focus();
    });
    
	$('#panel_paciente_titular').hide();
    cambiar_tipo_beneficiario();
    modalHistorial();

    const fechaInicial = $('#fecha_nac_aseg').val();
    // console.log(fechaInicial);
    if (fechaInicial) {
        const edadInicial = calcularEdad(fechaInicial);
        // console.log(edadInicial);
        $('#edad').val(edadInicial);
        controlarBeneficiario(edadInicial); 
    }

    $('#fecha_nac_aseg').on('change', function () {
        const fechaNac = $(this).val();
        console.log(fechaNac);
        const edad = calcularEdad(fechaNac);
        console.log(edad);
        $('#edad').val(edad);
        controlarBeneficiario(edad); 
    });
});
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////// ACTIVAR EL TOGGLE TUTOR SI LA EDAD ES < 18 && > 75
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////// DETECTAR CAMBIO DE PARENTESCO Y MOSTRAR ADVERTENCIA
let tipoBeneficiarioAnterior = $("#prtc_aseg").val();
let bloqueadoCambioTipo = false;
let esActualizacionApiSaludpol = false;

$('#prtc_aseg').on('focus', function () {
    tipoBeneficiarioAnterior = $(this).val();
});

$('#prtc_aseg').on('change', cambiar_tipo_beneficiario);

function cambiar_tipo_beneficiario() {
    if (bloqueadoCambioTipo) return;
    if (esActualizacionApiSaludpol) return;
    const tipoBeneficiarioNuevo = $("#prtc_aseg").val();
    if (tipoBeneficiarioAnterior !== "" && tipoBeneficiarioAnterior !== tipoBeneficiarioNuevo) {
        const textoAnterior = $("#prtc_aseg option[value='" + tipoBeneficiarioAnterior + "']").text();
        const textoNuevo = $("#prtc_aseg option[value='" + tipoBeneficiarioNuevo + "']").text();
        bloqueadoCambioTipo = true;
        $("#prtc_aseg").val(tipoBeneficiarioAnterior);
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
                    bloqueadoCambioTipo = true;
                    $("#prtc_aseg").val(tipoBeneficiarioNuevo);
                    bloqueadoCambioTipo = false;
                    tipoBeneficiarioAnterior = tipoBeneficiarioNuevo;

                    Tipos_Beneficiarios(tipoBeneficiarioNuevo);
                    controlarBeneficiario();
                } else{
                    esActualizacionApiSaludpol = false;
                }
            }
        });
    } else {
        tipoBeneficiarioAnterior = tipoBeneficiarioNuevo;
        esActualizacionApiSaludpol = false;
        Tipos_Beneficiarios(tipoBeneficiarioNuevo);
        controlarBeneficiario();
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////// MOSTRAR PANEL TITULAR SEGUN CORRESPONDA
function Tipos_Beneficiarios(tipoBeneficiarioNuevo) {
    // console.log(tipoBeneficiarioNuevo);
    switch (tipoBeneficiarioNuevo) {
        case "8": // TITULAR
            const tipoDocIdentText = $("#id_tipo_doc_ident option:selected").text().trim();
            $('#tipo_doc_ident_titular').val(tipoDocIdentText);
            $('#nro_doc_ident_titular').val($('#nro_doc_ident').val());

            const nombresCompletos = `${$('#paterno').val()} ${$('#materno').val()}, ${$('#nombre').val()}`;
            $('#apellidos_nombres_titular').val(nombresCompletos);

            $('#panel_paciente_titular').show();
            break;

        case "13":
        case "": // CIVIL 
            $('#panel_paciente_titular').hide();
            break;
        
        default: // OTROS FAMILIARES
            $('#panel_paciente_titular').show();
            break;
    }
    controlarBeneficiario();
}

//////////////////////////////////////////////////////////////////////////////////////////////////// MOSTRAR EL PANEL DE TUTOR Y CUANDO LA EDAD < 18 && > 75
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
/////////////////////////////////////////////////////////////////////////////////////////
function controlarBeneficiario() {
    const edad = parseInt($('#edad').val()) || 0;
    if (edad < 18 || edad > 75) {
        $('#panel_tutor').show();
        $('#grupoAsignarTutor').show();
    } else {
        $('#panel_tutor').hide();
        $('#grupoAsignarTutor').hide();
        $('#fs_tutor').hide();
        $('#asignarTutor').removeClass('active').attr('aria-pressed', 'false');
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// BUSCAR DEPARTAMENTO NACIMIENTO
function getProvincia(){
    var newOption = "";
    newOption = "<option value=''>-- Seleccionar --</option>";
    $('#id_provincia').html(newOption);
    

    var Departamento = $('#id_departamento').val();

    $.ajax({    
        url: '/obtener_provincia_por_departamento/' + Departamento,
        async: false,
        success: function(result){                    
            var newOption = ""; //var cant=0;
            newOption = "<option value=''>-- Seleccionar --</option>";
            $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id_provincia+"'>"+oo.descripcion+"</option>";
            });                 
            $('#id_provincia').html(newOption);
        }
    });
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// BUSCAR DISTRITO NACIMIENTO
function getDistrito(){
    var newOption = "";
    newOption = "<option value=''>-- Seleccionar --</option>";
    $('#id_distrito').html(newOption);

    var Provincia = $('#id_provincia').val();

    $.ajax({    
        url: '/obtener_distrito_por_provincia/' + Provincia,
        async: false,
        success: function(result){
            var newOption = "";
            newOption = "<option value=''>-- Seleccionar --</option>";
            $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id_distrito+"'>"+oo.descripcion+"</option>";
            });                 
            $('#id_distrito').html(newOption);    
        }
    });
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// BUSCAR PROVINCIA DOMICILIO
function getProvinciaDOM(){
    var newOption = "";
    newOption = "<option value=''>-- Seleccionar --</option>";
    $('#provdom_aseg').html(newOption);
    

    var Departamento = $('#depdom_aseg').val();

    $.ajax({    
        url: '/obtener_provincia_por_departamento/' + Departamento,
        async: false,
        success: function(result){                    
            var newOption = ""; //var cant=0;
            newOption = "<option value=''>-- Seleccionar --</option>";
            $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id_provincia+"'>"+oo.descripcion+"</option>";
            });                 
            $('#provdom_aseg').html(newOption);
        }
    });
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// BUSCAR DISTRITO DOMICILIO
function getDistritoDOM(){
    var newOption = "";
    newOption = "<option value=''>-- Seleccionar --</option>";
    $('#distdom_aseg').html(newOption);

    var Provincia = $('#provdom_aseg').val();

    $.ajax({    
        url: '/obtener_distrito_por_provincia/' + Provincia,
        async: false,
        success: function(result){
            var newOption = "";
            newOption = "<option value=''>-- Seleccionar --</option>";
            $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id_distrito+"'>"+oo.descripcion+"</option>";
            });                 
            $('#distdom_aseg').html(newOption);    
        }
    });
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////// VALIDAR QUE TODOS LOS CAMPOS SEAN COMPLETADOS
function validateFrmDatosAsegurado() {
    let isValid = true;
    let errorMessage = "";
    let nameFieldToFocus = "";
    const form = $('#frm_paciente');
    const nroDocOriginal = "{{ $personal->nro_doc_ident }}";
    if (isValid) {
        if (form.find("#nro_doc_ident").val() == "") {
            isValid = false;
            errorMessage = "El campo Documento de Identidad del paciente no puede estar vacío.";
            nameFieldToFocus = "nro_doc_ident";
        }
    }

    if (form.find("#id_tipo_doc_ident").val() == "") {
        isValid = false;
        errorMessage = "Debe seleccionar el Tipo de Documento del paciente";
    }

    // if (isValid) {
    //     if (form.find("#nro_doc_ident").val() == "") {
    //         isValid = false;
    //         errorMessage = "El campo Documento de Identidad del paciente no puede estar vacío.";
    //         nameFieldToFocus = "nro_doc_ident";
    //     }
    // }

    if (isValid) {
        if (form.find("#tipo_doc_aseg").val() == "") {
            isValid = false;
            errorMessage = "Debe seleccionar el Tipo de Documento del asegurado";
            nameFieldToFocus = "tipo_doc_aseg";
        }
    }

    if (isValid) {
        if (form.find("#nro_doc_ident_aseg").val() == "") {
            isValid = false;
            errorMessage = "El campo Documento de Identidad del asegurado no puede estar vacío.";
            nameFieldToFocus = "nro_doc_ident_aseg";
        }
    }

    if (isValid) {
        if (form.find("#cond_asegurado").val() == "") {
            isValid = false;
            errorMessage = "El campo Condición de Asegurado no puede estar vacío.";
            nameFieldToFocus = "cond_asegurado";
        }
    }

    if (isValid) {
        if (form.find("#prtc_aseg").val() == "") {
            isValid = false;
            errorMessage = "Debe seleccionar el Parentesco del asegurado.";
            nameFieldToFocus = "prtc_aseg";
        }
    }

    if (isValid) {
        if (form.find("#nombre_aseg").val() == "") {
            isValid = false;
            errorMessage = "El campo nombre del asegurado no puede estar vacío.";
            nameFieldToFocus = "nombre_aseg";
        }
    }

    if (isValid) {
        if (form.find("#paterno_aseg").val() == "") {
            isValid = false;
            errorMessage = "El campo apellido paterno del asegurado no puede estar vacío.";
            nameFieldToFocus = "paterno_aseg";
        }
    }

    /*if (isValid) {
        if (form.find("#materno_aseg").val() == "") {
            isValid = false;
            errorMessage = "El campo apellido materno del asegurado no puede estar vacío.";
            nameFieldToFocus = "materno_aseg";
        }
    }*/

    if (isValid) {
        if (form.find("#sexo_aseg").val() == "") {
            isValid = false;
            errorMessage = "El campo sexo del asegurado no puede estar vacío.";
            nameFieldToFocus = "sexo_aseg";
        }
    }

    if (isValid) {
        if (form.find("#fecha_nac_aseg").val() == "") {
            isValid = false;
            errorMessage = "El campo fecha de nacimiento del asegurado no puede estar vacío.";
            nameFieldToFocus = "fecha_nac_aseg";
        }
    }

    if (isValid) {
        if (form.find("#tipo_sang_aseg").val() == "") {
            isValid = false;
            errorMessage = "Debe seleccionar el Tipo de Sangre del asegurado.";
            nameFieldToFocus = "tipo_sang_aseg";
        }
    }

    if (isValid) {
        if (form.find("#id_departamento").val() == "") {
            isValid = false;
            errorMessage = "Debe seleccionar el Departamento de nacimiento del asegurado.";
            nameFieldToFocus = "id_departamento";
        }
    }

    if (isValid) {
        if (form.find("#id_provincia").val() == "") {
            isValid = false;
            errorMessage = "Debe seleccionar la Provincia de nacimiento del asegurado.";
            nameFieldToFocus = "id_provincia";
        }
    }

    if (isValid) {
        if (form.find("#id_distrito").val() == "") {
            isValid = false;
            errorMessage = "Debe seleccionar el Distrito de nacimiento del asegurado.";
            nameFieldToFocus = "id_distrito";
        }
    }

    if (isValid) {
        if (form.find("#domicilio").val() == "") {
            isValid = false;
            errorMessage = "El campo Dirección del domicilio del asegurado no puede estar vacío.";
            nameFieldToFocus = "domicilio";
        }
    }

    if (isValid) {
        if (form.find("#ec_aseg").val() == "") {
            isValid = false;
            errorMessage = "El campo Estado civil del asegurado no puede estar vacío.";
            nameFieldToFocus = "ec_aseg";
        }
    }

    if (isValid) {
        if (form.find("#id_religion").val() == "") {
            isValid = false;
            errorMessage = "El campo Religión del asegurado no puede estar vacío.";
            nameFieldToFocus = "id_religion";
        }
    }

    if (isValid) {
        if (form.find("#id_ocupacion").val() == "") {
            isValid = false;
            errorMessage = "El campo Ocupación del asegurado no puede estar vacío.";
            nameFieldToFocus = "id_ocupacion";
        }
    }

    if (isValid) {
        if (form.find("#id_grado_instruccion").val() == "") {
            isValid = false;
            errorMessage = "El campo Grado de instrucción del asegurado no puede estar vacío.";
            nameFieldToFocus = "id_grado_instruccion";
        }
    }

    if (isValid) {
        if (form.find("#id_etnia").val() == "") {
            isValid = false;
            errorMessage = "El campo Etnia del asegurado no puede estar vacío.";
            nameFieldToFocus = "id_etnia";
        }
    }

    if (isValid) {
        if (form.find("#discapacidad_aseg").val() == "") {
            isValid = false;
            errorMessage = "El campo Discapacidad del asegurado no puede estar vacío.";
            nameFieldToFocus = "discapacidad_aseg";
        }
    }

    if (isValid) {
        if (form.find("#depdom_aseg").val() == "") {
            isValid = false;
            errorMessage = "Debe seleccionar el Departamento de domicilio del asegurado.";
            nameFieldToFocus = "depdom_aseg";
        }
    }

    if (isValid) {
        if (form.find("#provdom_aseg").val() == "") {
            isValid = false;
            errorMessage = "Debe seleccionar la Provincia de domicilio del asegurado.";
            nameFieldToFocus = "provdom_aseg";
        }
    }

    if (isValid) {
        if (form.find("#distdom_aseg").val() == "") {
            isValid = false;
            errorMessage = "Debe seleccionar el Distrito de domicilio del asegurado.";
            nameFieldToFocus = "distdom_aseg";
        }
    }

        if (isValid) {
            if (form.find("#tipo_doc_titu").val() == "") {
                isValid = false;
                errorMessage = "El campo Tipo de Documento del titular no puede estar vacío.";
                nameFieldToFocus = "tipo_doc_titu";
            }
        }
        
        // if (isValid) {
        //     if (form.find("#nro_doc_titu").val() == "") {
        //         isValid = false;
        //         errorMessage = "El campo Documento de Identidad del titular no puede estar vacío.";
        //         nameFieldToFocus = "tipo_doc_titu";
        //     }
        // }

        if (isValid) {
            if (form.find("#nombre_titu").val() == "") {
                isValid = false;
                errorMessage = "El campo nombre del titular no puede estar vacío.";
                nameFieldToFocus = "nombre_titu";
                
            }
        }

        if (isValid) {
            if (form.find("#paterno_titu").val() == "") {
                isValid = false;
                errorMessage = "El campo apellido paterno del titular no puede estar vacío.";
                nameFieldToFocus = "paterno_titu";
            }
        }

        if (isValid) {
            if (form.find("#materno_titu").val() == "") {
                isValid = false;
                errorMessage = "El campo apellido materno del titular no puede estar vacío.";
                nameFieldToFocus = "materno_titu";
            }
        }

        if (isValid) {
            if (form.find("#nro_cip_titu").val() == "") {
                isValid = false;
                errorMessage = "El campo N° CIP no puede estar vacío.";
                nameFieldToFocus = "nro_cip_titu";
            }
        }

        if (isValid) {
            const situacionTitular = form.find("#situacion_titu").val().trim();
            if (situacionTitular !== "FALLECIDO" && form.find("#grado_titu").val() == "") {
                isValid = false;
                errorMessage = "El campo Grado del titular no puede estar vacío.";
                nameFieldToFocus = "grado_titu";
            }
        }

        if (isValid) {
            if (form.find("#situacion_titu").val() == "") {
                isValid = false;
                errorMessage = "El campo Situación del titular no puede estar vacío.";
                nameFieldToFocus = "situacion_titu";
            }
        }

        /*if (isValid) {
            if (form.find("#unidad_titu").val() == "") {
                isValid = false;
                errorMessage = "El campo Unidad del titular no puede estar vacío.";
                nameFieldToFocus = "unidad_titu";
            }
        }*/

        const tipo = form.find('#prtc_aseg').val();
        const edad = parseInt($('#edad').val());

        // condicion de edad 
        const requiereTutor = (edad < 18 || edad > 75);

        // if (!["4", "8"].includes(tipo)) {

            if (!requiereTutor) {
                //  NO necesita tutor → saltar validaciones
            } else {
                //  SÍ necesita tutor → validar todo

                if (isValid && form.find('#id_parentesco_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe seleccionar el tipo de parentesco del tutor.";
                    nameFieldToFocus = "id_parentesco_tutor";
                }

                if (isValid && form.find('#tipo_documento_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe seleccionar el tipo de documento del tutor.";
                    nameFieldToFocus = "tipo_documento_tutor";
                }

                if (isValid && form.find('#numero_documento_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe ingresar el numero de documento del tutor.";
                    nameFieldToFocus = "numero_documento_tutor";
                }

                if (isValid && form.find('#nombres_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe ingresar los nombres del tutor.";
                    nameFieldToFocus = "nombres_tutor";
                }

                if (isValid && form.find('#apellidos_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe ingresar los apellidos del tutor.";
                    nameFieldToFocus = "apellidos_tutor";
                }

                if (isValid && form.find('#telefono_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe ingresar el numero de telefono del tutor.";
                    nameFieldToFocus = "telefono_tutor";
                }

                if (isValid && form.find('#direccion_tutor').val() == "") {
                    isValid = false;
                    errorMessage = "Debe ingresar la direccion de domicilio del tutor.";
                    nameFieldToFocus = "direccion_tutor";
                }
            }
        //}

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
        // validar que sea el mismo asegurado
        const nroDocActual = $('#nro_doc_ident').val().trim();
        if (nroDocActual !== nroDocOriginal) {
          Swal.fire({
            icon: 'error',
            title: 'Acción no Permitida',
            text: 'No se pudo actualizar el registro porque la información proporcionada pertenece a otro paciente.',
            confirmButtonText: 'Aceptar',
            customClass: {
              popup: 'swal-afiliacion'
            }
          });

          $('#btnReg').prop('disabled', false);
          $('#btnRegistrarHistoriaClinica').prop('disabled', false);
          return false;
        }

        mostrarResumen()
        
    }

    function validateFrmHistoriasClinicaNueva() {
        let isValid = true;
        let errorMessage = "";
        const nroDocOriginal = "{{ $personal->nro_doc_ident }}";
        
        const establecimientoNuevo = $('#id_establecimiento_nuevo').val();
        const inputHistoriaNueva = $('input[name="nro_historia_nuevo"]');
        const historiaNueva = (inputHistoriaNueva.val() || '').trim();

        // Establecimiento seleccionado
        if (!establecimientoNuevo || establecimientoNuevo === '0') {
            isValid = false;
            errorMessage = 'Debe seleccionar un establecimiento.';
            $('#id_establecimiento_nuevo').next('.select2-container').css('border', '2px solid red');
        } else {
            $('#id_establecimiento_nuevo').next('.select2-container').css('border', '');
        }

        // N° de historia no vacío
        if (isValid) {
            if (historiaNueva === '') {
                isValid = false;
                errorMessage = 'Debe completar el número de historia del nuevo establecimiento.';
                inputHistoriaNueva.css('border', '2px solid red');
            } else {
                inputHistoriaNueva.css('border', '');
            }
        }

        if (!isValid) {
            bootbox.alert(errorMessage);
            
            $('#btnReg').prop('disabled', false);
            $('#btnRegistrarHistoriaClinica').prop('disabled', false);
            return false;
        }
        // validar que sea el mismo asegurado
        const nroDocActual = $('#nro_doc_ident').val().trim();
        if (nroDocActual !== nroDocOriginal) {
          Swal.fire({
            icon: 'error',
            title: 'Acción no Permitida',
            text: 'No se pudo actualizar el registro porque la información proporcionada pertenece a otro paciente.',
            confirmButtonText: 'Aceptar',
            customClass: {
              popup: 'swal-afiliacion'
            }
          });

          $('#btnReg').prop('disabled', false);
          $('#btnRegistrarHistoriaClinica').prop('disabled', false);
          return false;
        }

        bootbox.confirm({
            size: "small",
            message: '¿Desea registrar el nuevo N° de Historia Clínica?',
            buttons: {
                confirm: { label: 'Sí, registrar', className: 'btn-success' },
                cancel: { label: 'Cancelar', className: 'btn-danger' }
            },
            callback: function (confirmado) {
                if (confirmado) {
                    submitFrm("historia_clinica_nueva");
                }
            }
        });

    }

    //////////////////////////////////////////////////////////////////////////////////////////////// VISTA REGISTRO PRE GUARDADO
    function mostrarResumen() {

        const form = $('#frm_paciente');
        const mostrarTutor =
            form.find("#numero_documento_tutor").val() ||
            form.find("#nombres_tutor").val() ||
            form.find("#apellidos_tutor").val();

        function fila(label, valor){
            return `
                <tr>
                    <td style="font-weight:bold; white-space:nowrap; padding:2px 5px;">${label}</td>
                    <td style="padding:2px 10px;">:</td>
                    <td style="padding:2px 5px;">${valor || '-'}</td>
                </tr>
            `;
        }

        let html = `
        <div class="panel-body">
            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading text-center" style="font-size:12px;">
                    <b>DATOS DE AFILIACIÓN</b>
                </div>
                <div class="panel-body" style="font-size:12px;">
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                ${fila('FECHA INICIO DE AFILIACIÓN', form.find("#fecha_ini_afl").val())}
                                ${fila('FECHA FIN DE AFILIACIÓN', form.find("#fecha_fin_afl").val())}
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table>
                                ${fila('ESTADO DE AFILIACIÓN', form.find("#estado_afl").val())}
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading text-center" style="font-size:12px;">
                    <b>DATOS DEL ASEGURADO</b>
                </div>
                <div class="panel-body" style="font-size:12px;">
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                ${fila('TIPO Y N° DOC', form.find("#id_tipo_doc_ident option:selected").text() + ' - ' + form.find("#nro_doc_ident").val())}
                                ${fila('NOMBRES Y APELLIDOS', form.find("#nombre_aseg").val() + ' ' + form.find("#paterno_aseg").val() + ' ' + form.find("#materno_aseg").val())}
                                ${fila('CONDICIÓN DEL ASEGURADO', form.find("#cond_asegurado").val())}
                                ${fila('PARENTESCO DEL ASEGURADO', form.find("#prtc_aseg option:selected").text())}
                                ${fila('FECHA DE NACIMIENTO', form.find("#fecha_nac_aseg").val())}
                                ${fila('EDAD/SEXO', form.find("#edad").val()+ ' / ' +form.find("#sexo_aseg option:selected").text())}
                                ${fila('DEP/PROV/DIST DE NACIMIENTO', form.find("#id_departamento option:selected").text() + ' / ' +form.find("#id_provincia option:selected").text() + ' / ' +form.find("#id_distrito option:selected").text())}
                                ${fila('GRUPO Y FACTOR SANGUINEO', form.find("#tipo_sang_aseg option:selected").text())}
                                ${fila('N° DE CELULAR', form.find("#cel_aseg").val())}
                                ${fila('DIRECCIÓN ACTUAL', form.find("#domicilio").val())}
                                ${fila('DEP/PROV/DIST DE DOMICILIO', form.find("#depdom_aseg option:selected").text() + ' / ' +form.find("#provdom_aseg option:selected").text() + ' / ' +form.find("#distdom_aseg option:selected").text())}
                                
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table>
                                ${fila('ESTADO CIVIL', form.find("#ec_aseg option:selected").text())}
                                ${fila('CORREO ELECTRÓNICO', form.find("#email_aseg").val())}
                                ${fila('RELIGIÓN', form.find("#id_religion option:selected").text())}
                                ${fila('OCUPACIÓN', form.find("#id_ocupacion option:selected").text())}
                                ${fila('ETNIA', form.find("#id_etnia option:selected").text())}
                                ${fila('GRADO DE INSTRUCCIÓN', form.find("#id_grado_instruccion option:selected").text())}
                                ${fila('DISCAPACIDAD', form.find("#discapacidad_aseg").val())}
                                ${fila('N° DE CARNET CONADIS', form.find("#conadis").val())}
                                ${fila('UBIGEO', form.find("#ubig_aseg").val())}
                                ${fila('SITUACIÓN', form.find("#sit_aseg").val())}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            ${mostrarTutor ? `
            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading text-center" style="font-size:12px;">
                    <b>DATOS DEL TUTOR</b>
                </div>
                <div class="panel-body" style="font-size:12px;">
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                ${fila('TIPO Y N° DOC', form.find("#tipo_documento_tutor option:selected").text() + ' - ' + form.find("#numero_documento_tutor").val())}
                                ${fila('PARENTESCO', form.find("#id_parentesco_tutor option:selected").text())}
                                ${fila('DOMICILIO ACTUAL', form.find("#direccion_tutor").val())}
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table>
                                ${fila('NOMBRES Y APELLIDOS', form.find("#nombres_tutor").val() + ' ' + form.find("#apellidos_tutor").val())}
                                ${fila('N° DE CELULAR', form.find("#telefono_tutor").val())}
                            </table>
                        </div>
                    </div>
                </div>
            </div>` : ''}
            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading text-center" style="font-size:12px;">
                    <b>DATOS DEL TITULAR</b>
                </div>
                <div class="panel-body" style="font-size:12px;">
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                ${fila('TIPO Y N° DOC', form.find("#tipo_doc_titu").val() + ' - ' + form.find("#nro_doc_titu_drh").val())}
                                ${fila('N° DE CIP', form.find("#nro_cip_titu").val())}
                                ${fila('SITUACIÓN', form.find("#situacion_titu").val())}
                                ${fila('UNIDAD', form.find("#unidad_titu").val())}
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table>
                                ${fila('NOMBRES Y APELLIDOS', form.find("#nombre_titu").val() + ' ' + form.find("#paterno_titu").val() + ' ' + form.find("#materno_titu").val())}
                                ${fila('GRADO', form.find("#grado_titu").val())}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;

        $('#contenidoResumen').html(html);
        $('#modalResumen').modal({ backdrop: 'static', keyboard: false });
        $('#modalResumen').modal('show');
    }
    
    function submitFrm(accion) {

        $('#btnReg').prop('disabled', true);
        $('#btnRegistrarHistoriaClinica').prop('disabled', true);
        $('#frm_paciente').find('select:disabled').prop('disabled', false);

        Swal.fire({
            title: 'Actualizando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        if (accion == "historia_clinica_nueva") {
            $("#frm_paciente").attr("action", "{{ route('registrar_historia_clinica') }}");
        }

        $("#frm_paciente").submit();
    }

    function getDirrehumByDni(tipo) {
        var dniInput, unidad_titu, $btnBuscar;
        if (tipo === 'titular') {
            dniInput    = $('#panel_paciente_titular input[name="nro_doc_titu_drh"]');
            $btnBuscar  = $('#panel_paciente_titular #buscar_titular_familiar');
            unidad_titu = '#unidad_titu';
        }
        if ($btnBuscar.prop('disabled')) return;
        var dni_paciente = dniInput.val()?.trim();
        if (!dni_paciente) {
            bootbox.alert("Falta ingresar el Documento del paciente");
            dniInput.focus();
            return;
        }
        if (dni_paciente.length != 8) {
            bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>");
            dniInput.focus();
            return;
        }
        $btnBuscar.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i><strong style="font-size:12px;"> CONSULTANDO... </strong>');
        $.ajax({
            url: "/ws_dirrehum/1/" + dni_paciente,
            type: "GET",
            dataType: "JSON",
            success: function (result) {
                let data = result;
                if (!data) {
                    bootbox.alert("No hay coincidencia con el N° de DNI ingresado.");
                    return;
                }
                $(unidad_titu).val((data.UNIDAD || '').trim()).trigger('change');
                pintarCampo($(unidad_titu));
            },

            error: function () {
                // bootbox.alert("Ha ocurrido un error al consultar el N° Documento del paciente.");
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo completar la consulta',
                    text: 'En este momento no es posible consultar la información en DIRREHUM. Si el inconveniente persiste, comuníquese con el área de soporte.',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        popup: 'swal-dirrehum'
                    }
                });
            },
            complete: function () {

                $btnBuscar.prop('disabled', false).html('<i class="fa fa-search"></i><strong style="font-size:12px;"> BUSCAR UNIDAD DE TITULAR</strong>');
            }
        });
    }

    function pintarCampo($campo) {
        const val = $campo.prop('tagName') === 'SELECT' ? $campo.find('option:selected').val()  : $campo.val();
        if (typeof val === 'string' && val.trim() !== '' && val !== '') {
            $campo.addClass('input-detectado');
        } else {
            $campo.removeClass('input-detectado');
        }
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
            const optionSelected = $('#tipo_sang_aseg option:selected');
            const grupo = optionSelected.data('grupo') || '';
            const rh = optionSelected.data('rh') || '';
            $('#grupo_sanguineo').val(grupo);
            $('#factor_rh').val(rh);
        }

        $('#tipo_sang_aseg').on('change', actualizarGrupoYRH);
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

        $('#prtc_aseg').on('change', function () {
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
                        return `<button type="button" class="btn btn-sm btn-success btn-actualizar" data-id="${row.id}" data-idasegurado="${row.id_asegurado}"> Actualizar </button>`;
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

    document.addEventListener("DOMContentLoaded", function () {
        let ahora = new Date();
        let fecha = String(ahora.getDate()).padStart(2, '0')+ '/' + String(ahora.getMonth() + 1).padStart(2, '0') + '/' + ahora.getFullYear();
        let hora = String(ahora.getHours()).padStart(2, '0') + ':' + String(ahora.getMinutes()).padStart(2, '0') + ':' + String(ahora.getSeconds()).padStart(2, '0');
        document.getElementById("fecha_actual").value = fecha + ' - ' + hora;
    });
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////// BLOQUEAR TODOS LOS CAMPOS AL CARGAR LA PÁGINA
    const camposTodos = [
        // Asegurado
        'tipo_doc_aseg', 'ec_aseg', 'nro_doc_ident_aseg', 'cond_asegurado', 'prtc_aseg','nombre_aseg', 'paterno_aseg', 'materno_aseg', 'sexo_aseg', 'fecha_nac_aseg','tipo_sang_aseg', 'discapacidad_aseg', 'conadis', 'cel_aseg', 'email_aseg',
        'ubig_aseg', 'domicilio', 'id_departamento', 'id_provincia', 'id_distrito','depdom_aseg', 'provdom_aseg', 'distdom_aseg', 'id_religion', 'id_ocupacion','id_grado_instruccion', 'id_etnia',
        // Tutor
        'tipo_documento_tutor', 'numero_documento_tutor', 'id_parentesco_tutor','nombres_tutor', 'apellidos_tutor', 'telefono_tutor', 'direccion_tutor',
        // Titular
        'tipo_doc_titu', 'nro_doc_titu_drh', 'nro_cip_titu','paterno_titu', 'materno_titu', 'nombre_titu', 'grado_titu','situacion_titu', 'unidad_titu', 'buscar_titular_familiar','btnReg'
    ];
    const camposDesbloqueables = [
        'ec_aseg', 'tipo_sang_aseg', 'id_religion', 'id_ocupacion','id_grado_instruccion', 'id_etnia', 'conadis', 'cel_aseg', 'email_aseg','id_departamento', 'id_provincia', 'id_distrito','depdom_aseg', 'provdom_aseg', 'distdom_aseg', 'domicilio',
        'tipo_documento_tutor', 'numero_documento_tutor', 'id_parentesco_tutor', 'nombres_tutor', 'apellidos_tutor', 'telefono_tutor', 'direccion_tutor','buscar_titular_familiar','btnReg','sexo_aseg','ubig_aseg','prtc_aseg'
    ];

    function bloquearCampo(id) {
        let $el = $('#' + id);
        if (!$el.length) return;
        let tag = $el.prop('tagName');
        if (tag === 'SELECT') {
            $el.prop('disabled', true);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.prop('disabled', true).trigger('change');
            }
        } else if (tag === 'BUTTON') {
            $el.prop('disabled', true);
        } else {
            $el.prop('readonly', true);
            $el.css('background-color', '#eeeeee');
            $el.css('pointer-events', 'none');
        }
    }

    function desbloquearCampo(id) {
        let $el = $('#' + id);
        if (!$el.length) return;
        let tag = $el.prop('tagName');
        if (tag === 'SELECT') {
            $el.prop('disabled', false);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.prop('disabled', false).trigger('change');
            }
        } else if (tag === 'BUTTON') {
            $el.prop('disabled', false);
        } else {
            $el.prop('readonly', false);
            $el.css('background-color', '');
            $el.css('pointer-events', '');
        }
    }

    $(document).ready(function () {
        camposTodos.forEach(id => bloquearCampo(id));
    });
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////// BLOQUEAR CAMPOS AL ACTUALIZAR CON DATOS DEL API SALUDPOL
    const camposReadonly = ['fecha_ini_afl','fecha_fin_afl','estado_afl','tipo_doc_aseg','nro_doc_ident_aseg','cond_asegurado','prtc_aseg','nombre_aseg','paterno_aseg','materno_aseg','sexo_aseg','fecha_nac_aseg','discapacidad_aseg','tipo_doc_titu','nro_doc_titu_drh','nro_cip_titu','materno_titu','paterno_titu','nombre_titu','grado_titu','situacion_titu'];
    function setCampo(id, valor, esFecha = false) {
        let $campo = $('#' + id);
        let tipo = $campo.prop('tagName'); 
        if (esFecha && valor && /^\d{4}-\d{2}-\d{2}$/.test(valor)) {
            let partes = valor.split('-');
            valor = partes[2] + '/' + partes[1] + '/' + partes[0];
        }
        let esReadonly = camposReadonly.includes(id);
        if (valor === null || valor === undefined) {
            if ($campo.val()) return;
            $campo.val('').trigger('change');
            if (esReadonly) {
                if (tipo === 'SELECT') {
                    $campo.css('pointer-events', '');
                    $campo.css('background-color', '');
                    if ($campo.hasClass('select2-hidden-accessible')) {
                        $campo.next('.select2-container').css('pointer-events', '');
                        $campo.next('.select2-container').css('background-color', '');
                    }

                } else {
                    $campo.prop('readonly', false);
                    if ($campo.hasClass('datepicker')) {
                        $campo.off('focus').off('click');
                    }
                }
            }

            pintarCampo($campo);
            return;
        }
        $campo.val(valor).trigger('change');
        if (esReadonly) {
            if (tipo === 'SELECT') {
                $campo.css('pointer-events', 'none');
                $campo.css('background-color', '#e9ecef');
                if ($campo.hasClass('select2-hidden-accessible')) {
                    $campo.next('.select2-container').css('pointer-events', 'none');
                    $campo.next('.select2-container').css('background-color', '#e9ecef');
                }
            } else {
                $campo.prop('readonly', true);
                if ($campo.hasClass('datepicker')) {
                    $campo.off('focus').on('focus', function(e) {
                        e.preventDefault();
                        $(this).blur();
                    });
                    $campo.off('click').on('click', function(e) {
                        e.preventDefault();
                    });
                }
            }
        }
        pintarCampo($campo);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function seleccionarPorTexto(selectId, textoBuscado) {
        let select = document.getElementById(selectId);
        if (!select || !textoBuscado) return;
        textoBuscado = textoBuscado.trim().toUpperCase();
        for (let i = 0; i < select.options.length; i++) {
            let option = select.options[i];
            let textoOption = option.text.trim().toUpperCase();
            if (textoOption.includes(textoBuscado)) {
                select.selectedIndex = i;
                let $campo = $('#' + selectId);
                $campo.val(option.value).trigger('change');
                pintarCampo($campo);
                return;
            }
        }
        console.warn("No se encontró coincidencia");
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    const MAPA_TIPO_DOC = {
        'DNI': 'DNI',
        'CE':  'CARNET EXTRANJERIA',
        'PAS': 'PASAPORTE',
        'DIE': 'DOCUMENTO DE IDENTIDAD EXTRANJERO',
        'SDI': 'SDI'
    };

    function seleccionarCuandoEsteListo(selectId, textoBuscado, callback) {
        let intervalo = setInterval(() => {
            let select = document.getElementById(selectId);
            if (select && select.options.length > 1) {
                clearInterval(intervalo);
                seleccionarPorTexto(selectId, textoBuscado);
                if (selectId === 'prtc_aseg') {
                    esActualizacionApiSaludpol = false;
                    tipoBeneficiarioAnterior = $("#prtc_aseg").val();
                }
                if (typeof callback === 'function') callback();
            }
        }, 50);
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////// PARA ADAPTAR EL TIPO DE SANGRE API A VISTA 
    function seleccionarTipoSangre(valorApi) {
    if (!valorApi) return;
    valorApi = valorApi.toUpperCase();
    let grupo = null;
    let rh = null;
    if (valorApi.includes('AB')) grupo = 'AB';
    else if (valorApi.includes('A')) grupo = 'A';
    else if (valorApi.includes('B')) grupo = 'B';
    else if (valorApi.includes('O')) grupo = 'O';
    if (valorApi.includes('+')) rh = '+';
    else if (valorApi.includes('-')) rh = '-';
    else rh = 'ND';

    let $select = $('#tipo_sang_aseg');
    let nuevoValor = null;

    $select.find('option').each(function () {
        if (
            $(this).data('grupo') === grupo &&
            $(this).data('rh') === rh
        ) {
            nuevoValor = $(this).val();
            return false;
        }
    });

    if (nuevoValor) {
        $select.val(nuevoValor).trigger('change');
        setTimeout(() => {
            pintarCampo($select); 
        }, 50); 
    }
}
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////// BUSCAR EN EL API SALUDPOL
    document.getElementById('btnBuscarSaludpol').addEventListener('click', function() {
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
        //LimpiarCampos();
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i><strong style="font-size:12px;"> CONSULTANDO...</strong>';
        fetch('{{ url("/saludpol/consultar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                tipoDocumento: document.getElementById('id_tipo_doc_ident').value,
                numeroDocumento: document.getElementById('nro_doc_ident').value
            })
        })
        .then(function(response) {
            return response.json().then(function(result) {
                return { status: response.status, body: result };
            });
        })
        .then(function(resp) {
            const status = resp.status;
            const result = resp.body;

            if (result.success) {
                esActualizacionApiSaludpol = true;
                let data = result.data.data;

                // Fechas
                setCampo('fecha_ini_afl', data.fechaInicioAfiliacion, true);
                setCampo('fecha_fin_afl', data.fechaFinAfiliacion, true);
                setCampo('fecha_nac_aseg', data.fechaNacimientoAsegurado, true);

                // Asegurado
                setCampo('estado_afl', data.estadoAsegurado);
                let textoDocAseg = MAPA_TIPO_DOC[data.documentoAsegurado] || data.documentoAsegurado;
                seleccionarCuandoEsteListo('tipo_doc_aseg', textoDocAseg);
                setCampo('nro_doc_ident_aseg', data.numeroDocumentoAsegurado);
                setCampo('cond_asegurado', data.condicionAsegurado);
                // seleccionarCuandoEsteListo('prtc_aseg', data.parentescoAsegurado);
                seleccionarCuandoEsteListo('prtc_aseg', data.parentescoAsegurado, function () {
                    const tipo = $("#prtc_aseg").val();
                    Tipos_Beneficiarios(tipo); 
                    tipoBeneficiarioAnterior = tipo;
                });
                setCampo('nombre_aseg', data.nombreAsegurado);
                setCampo('paterno_aseg', data.apellidoPaternoAsegurado);
                setCampo('materno_aseg', data.apellidoMaternoAsegurado);
                seleccionarCuandoEsteListo('sexo_aseg', data.sexo);
                seleccionarTipoSangre(data.tipoSangre);
                setCampo('cel_aseg', data.telefonoAsegurado);
                setCampo('email_aseg', data.emailAsegurado);
                seleccionarCuandoEsteListo('ec_aseg', data.estadoCivilAsegurado);
                setCampo('discapacidad_aseg', data.discapacidad);
                setCampo('ubig_aseg', data.ubigeoDomicilio);
                setCampo('sit_aseg', data.situacionAsegurado);
                seleccionarCuandoEsteListo('depdom_aseg', data.departamentoDomicilio);
                seleccionarCuandoEsteListo('provdom_aseg', data.provinciaDomicilio);
                seleccionarCuandoEsteListo('distdom_aseg', data.distritoDomicilio);
                setCampo('domicilio', data.direccionDomicilio);

                // Titular
                setCampo('tipo_doc_titu', data.documentoTitular);
                // setCampo('nro_doc_titu', data.numeroDocumentoTitular);
                setCampo('nro_doc_titu_drh', data.numeroDocumentoTitular);
                setCampo('nro_cip_titu', data.cipTitular);
                setCampo('nombre_titu', data.nombreTitular);
                setCampo('paterno_titu', data.apellidoPaternoTitular);
                setCampo('materno_titu', data.apellidoMaternoTitular);
                setCampo('grado_titu', data.gradoTitular);
                setCampo('situacion_titu', data.situacionTitular);

                const campos = {
                    estado_afl: 'estadoAsegurado',
                    tipo_doc_aseg: 'documentoAsegurado',
                    nro_doc_ident_aseg: 'numeroDocumentoAsegurado',
                    cond_asegurado: 'condicionAsegurado',
                    prtc_aseg: 'parentescoAsegurado',
                    nombre_aseg: 'nombreAsegurado',
                    paterno_aseg: 'apellidoPaternoAsegurado',
                    materno_aseg: 'apellidoMaternoAsegurado',
                    sexo_aseg: 'sexo',
                    cel_aseg: 'telefonoAsegurado',
                    email_aseg: 'emailAsegurado',
                    ec_aseg: 'estadoCivilAsegurado',
                    discapacidad_aseg: 'discapacidad',
                    ubig_aseg: 'ubigeoDomicilio',
                    sit_aseg: 'situacionAsegurado',
                    depdom_aseg: 'departamentoDomicilio',
                    provdom_aseg: 'provinciaDomicilio',
                    distdom_aseg: 'distritoDomicilio',
                };

                Object.keys(campos).forEach(id => {
                    setCampo(id, data[campos[id]]);
                });
                setTimeout(() => {
                    const tipo = $("#prtc_aseg").val();
                    Tipos_Beneficiarios(tipo);

                    esActualizacionApiSaludpol = false;  
                    tipoBeneficiarioAnterior = tipo;  
                }, 100);

                camposDesbloqueables.forEach(id => desbloquearCampo(id));
                validarDiscapacidad();
                validarSituacionTitular(); 
                validarapmaternoasegurado().then(() => {
                if (data.situacionTitular !== "FALLECIDO") {
                    // Buscar automáticamente unidad titular
                    getDirrehumByDni('titular');
                }
                });

            } else {
                //alert('Error: ' + result.message);
                if (status === 404) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'ALERTA',
                        html: `
                            <div style="font-size:16px; line-height:1.6; text-align:center;">
                                El paciente <strong>NO CUENTA CON COBERTURA DE SEGURO VIGENTE.</strong>
                                <br>
                                Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación
                                antes de continuar con la atención.
                            </div>
                        `,
                        confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                        confirmButtonColor: '#3085d6',
                        width: '38em',
                        customClass: {
                            popup: 'swal-afiliacion'
                        }
                    });
                } else if (status === 401) {
                    Swal.fire({
                        icon: 'error',
                        title: 'CREDENCIALES INVÁLIDAS',
                        html: `
                            <div style="font-size:16px; line-height:1.6; text-align:center;">
                                No se pudo autenticar con el servicio de <strong>SALUDPOL</strong>.
                                <br>
                                Comuníquese con el área de soporte técnico.
                            </div>
                        `,
                        confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                        confirmButtonColor: '#3c763d',
                        width: '38em',
                        customClass: {
                            popup: 'swal-afiliacion'
                        }
                    });
                } else if (status === 400) {
                    Swal.fire({
                        icon: 'error',
                        title: 'DATOS INVÁLIDOS',
                        text: result.message,
                        confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                        confirmButtonColor: '#3c763d',
                        customClass: {
                            popup: 'swal-afiliacion'
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ERROR AL CONSULTAR SALUDPOL',
                        html: `
                            <div style="font-size:16px; line-height:1.6; text-align:center;">
                                Ocurrió un error al consultar el servicio de <strong>SALUDPOL</strong>.
                                <br>
                                Intente nuevamente en unos minutos. Si el problema persiste,
                                comuníquese con soporte técnico.
                            </div>
                        `,
                        confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                        confirmButtonColor: '#3c763d',
                        width: '38em',
                        customClass: {
                            popup: 'swal-afiliacion'
                        }
                    });
                }
            }
        })
        .catch(function(error) {
            // alert('Error de conexión: ' + error);
            Swal.fire({
                icon: 'error',
                title: 'ERROR DE CONEXIÓN',
                text: 'No se pudo establecer conexión con el servidor: ' + error.message
            });
        })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-edit"></i><strong style="font-size:12px;"> ACTUALIZAR </strong>';
        });
    });

    //////////////////////////////////////////////////////////////////////////////////////////////VALIDACIONES ADICIONALES PARA EL CAMPO DISCAPACIDAD
    function validarDiscapacidad() {
        let valor = $('#discapacidad_aseg').val().trim().toUpperCase();
        let $conadis = $('#conadis');
        if (valor === 'NO') {
            $conadis.prop('readonly', true);
            $conadis.val('');
        } else if (valor === 'SI') {
            $conadis.prop('readonly', false);
        }
    }
    /////////////////////////////////////////////////////////////////////
    $('#discapacidad_aseg').on('keyup change', validarDiscapacidad);
    /////////////////////////////////////////////////////////////////////
    $('#discapacidad_aseg').on('input', function () {
        let valor = $(this).val();
        valor = valor.replace(/[^a-zA-Z]/g, '');
        valor = valor.toUpperCase().slice(0, 2);
        $(this).val(valor);
    });
    /////////////////////////////////////////////////////////////////////
    $('#discapacidad_aseg').on('blur', function () {
        let valor = $(this).val();
        if (valor !== 'SI' && valor !== 'NO' && valor !== '') {
            alert('DEBE ESCRIBIR SI / NO SEGUN CORRESPONDA ');
            $(this).val('');
        }
    });
    /////////////////////////////////////////////////////////////////////
    $('form').on('submit', function () {
        let $campo = $('#discapacidad_aseg');
        if ($campo.val() === 'SI') $campo.val('1');
        else if ($campo.val() === 'NO') $campo.val('0');
    });
    /////////////////////////////////////////////////////////////////////
    $(document).ready(function () {
        validarDiscapacidad();
    });
    ////////////////////////////////////////////////////////////////////// ALERTA
    let tiene_afiliacion = {{ $tiene_afiliacion ? 'true' : 'false' }};
    if (!tiene_afiliacion) {
        Swal.fire({
            icon: 'warning',
            title: 'Alerta',
            text: 'Se recomienda actualizar información',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'swal-afiliacion'
            }
        });
    }
    ///////////////////////////////////////////////////////////////////// FUNCION LIMPIAR CAMPOS

    function LimpiarCampos() {
      const campos = [
          'tipo_doc_aseg', 'ec_aseg', 'nro_doc_ident_aseg', 'cond_asegurado','prtc_aseg', 'nombre_aseg', 'paterno_aseg', 'materno_aseg','sexo_aseg', 'fecha_nac_aseg', 'tipo_sang_aseg', 'discapacidad_aseg','edad',
          'conadis', 'cel_aseg', 'email_aseg', 'ubig_aseg', 'domicilio','id_departamento', 'id_provincia', 'id_distrito', 'depdom_aseg','provdom_aseg', 'distdom_aseg', 'id_religion', 'id_ocupacion','grupo_sanguineo','factor_rh',
          'id_grado_instruccion', 'id_etnia', 'tipo_documento_tutor','numero_documento_tutor', 'id_parentesco_tutor', 'nombres_tutor','apellidos_tutor', 'telefono_tutor', 'direccion_tutor','tipo_doc_titu', 'sit_aseg',
          'nro_doc_titu_drh', 'nro_cip_titu','paterno_titu', 'materno_titu', 'nombre_titu','grado_titu', 'situacion_titu', 'unidad_titu'
      ];
      campos.forEach(id => {
          $('#' + id).val('').removeClass('input-detectado');
      });
    }
    //////////////////////////////////////////////////////////////////// FUNCIÓN VALIDAR SITUACIÓN TITULAR
    function validarSituacionTitular() {
      let situacion = $('#situacion_titu').val().trim().toUpperCase();
      if (situacion === 'CADETE ESCUELA OFICIALES PNP' || situacion === 'ALUMNO DE ESCUELA TECNICA PNP') {
        desbloquearCampo('nro_cip_titu');
        desbloquearCampo('grado_titu');
      } else {
        bloquearCampo('nro_cip_titu');
        bloquearCampo('grado_titu');
      }
    }

    function validarapmaternoasegurado() {
        let ap_maternoaseg = ($('#materno_aseg').val() || '').trim();
        if (ap_maternoaseg === '') {
            desbloquearCampo('materno_aseg');
            return Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                html: 'La información obtenida del asegurado no incluye el <b>apellido materno</b>.<br>Verifique la identidad del paciente antes de continuar.',
                confirmButtonText: 'Continuar',
                customClass: {
                    popup: 'swal-afiliacion'
                }
            });
        }
        bloquearCampo('materno_aseg');
        return Promise.resolve();
    }
</script>

@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>MÓDULO DEL ASEGURADO</strong></h3>
                    </div>
                    @if(count($personal)>0)
                    <div style="margin-top: 20px">
                        <form id="frm_paciente" class="form-horizontal" role="form" method="post" action="{{route('update_paciente_new')}}">
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
                                    <li role="presentation" class="active"><a href="#tab7default" aria-controls="tab7default" role="tab" data-toggle="tab" style="font-size: 12px;"><strong>DATOS DEL ASEGURADO</strong></a></li>
                                    <li role="presentation"><a href="#tab8default" aria-controls="tab8default" role="tab" data-toggle="tab" style="font-size: 12px;"><strong>HISTORIA CLINICA</strong></a></li>
                                    <li role="presentation"><a href="#tab9default" aria-controls="tab9default" role="tab" data-toggle="tab" style="font-size: 12px;"><strong>LISTADO DE HOSPITALIZACIONES</strong></a></li>
                                    <li role="presentation"><a href="#tab10default" aria-controls="tab10default" role="tab" data-toggle="tab" style="font-size: 12px;"><strong>LISTADO DE RECETAS MEDICAS</strong></a></li>
                                </ul>
                                <div class="tab-content marca_agua_container" style="position:relative;">
                                    <div class="marca_agua_overlay" id="marca_agua_overlay_editar_paciente2"></div>
                                    <div role="tabpanel" class="tab-pane fade in active" id="tab7default">
                                        <div class="panel panel-info panel-bordered panel-sm" style="margin: 20px 20px; border:none;">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label style="font-size: 12px;">Tipo Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select class="form-control input-sm" id="id_tipo_doc_ident" name="id_tipo_doc_ident" onchange="maxlength_doc()" tabindex="3" style ="font-size:10px;">
                                                                    @foreach($tipo_documento_identidad as $e)
                                                                    <option value="{{$e->id}}" @if($e->id == $personal->id_tipo_doc_ident) selected @endif>{{$e->descripcion}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label style="font-size: 12px;">N° de Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="nro_doc_ident" name="nro_doc_ident" type="text" value="{{$personal->nro_doc_ident}}" class="form-control input-sm"  style ="font-size:10px;" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label></label>
                                                                {{-- <button type="button" class="form-control btn-primary" id="btnBuscarSaludpol" name="btnBuscarSaludpol" style="border-radius: 10px;" onclick="getSaludpolByTipoDocAndNroDoc();"><strong style="font-size: 12px;">BUSCAR ASEGURADO</strong></button> --}}
                                                                <button type="button" class="form-control btn-primary" id="btnBuscarSaludpol" name="btnBuscarSaludpol" style="border-radius: 10px;"><i class="fa fa-edit"></i><strong style="font-size:12px;"> ACTUALIZAR </strong></button>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label style="font-size: 12px;">Fecha y hora de Consulta</label>
                                                                <input id="fecha_actual" name="fecha_actual" type="text" class="form-control input-sm" style ="font-size:10px;" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="inline-field">
                                                                        <label style="margin-right:5px;">Fecha Inicio de Afiliación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                        <input type="text" class="form-control input-sm" id="fecha_ini_afl" value='{{$personal->fechaini_afiliacion}}' name="fechaini_afiliacion" style="width:180px;" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="inline-field">
                                                                        <label style="margin-right:5px;">Fecha Fin de Afiliación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                        <input type="text" class="form-control input-sm" id="fecha_fin_afl" value='{{$personal->fechafin_afiliacion}}' name="fechafin_afiliacion" style="width:180px;" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="inline-field">
                                                                        <label style="margin-right:5px;">Estado de Afiliación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                        <input type="text" class="form-control input-sm" id="estado_afl" value='{{$personal->estado_afiliacion}}' name="estado_afiliacion" style="width:180px; text-transform:uppercase;" readonly>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <fieldset>
                                                            <div class="panel panel-info panel-bordered panel-sm" style="margin-top:10px !important;">
                                                                <div class="panel-heading" style="padding-bottom: 5px;padding-top: 5px;">
                                                                    <div class="panel-title text-center" style="font-size: 12px;">
                                                                    <b>DATOS DEL ASEGURADO</b>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field" style="margin:0;">
                                                                                <label>Tipo de Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                {{-- <input class="form-control input-sm" id="tipo_doc_aseg" name="tipo_doc_aseg" type="text" value=""> --}}
                                                                                <select class="form-control input-sm" id="tipo_doc_aseg" name="tipo_doc_aseg" style="flex: 1; font-size:10px;" tabindex="3">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @foreach($tipo_documento_identidad as $e)
                                                                                        <option value="{{$e->id}}" @if($e->id == $personal->id_tipo_doc_ident) selected @endif>{{$e->descripcion}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field" style="margin:0;">
                                                                                <label style="margin: 0;">Edad &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" id="edad" name='edad' value="" class="form-control input-sm" style="width: auto; padding: 5px; text-align:center;" onkeyup="this.value = this.value.toUpperCase();" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field" style="margin:0;">
                                                                                <label style="margin: 0;">Estado Civil &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                {{-- <input type="text" id="ec_aseg" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();"> --}}
                                                                                <select id="ec_aseg" name="estado_civil_aseg" class="form-control input-sm" style="width: auto; flex: 1; font-size:10px" required="">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @foreach($estado_civil as $e)
                                                                                        <option value="{{$e->id}}" @if($e->id == $personal->id_estado_civil) selected @endif>{{$e->nombre}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">N° de documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                {{-- <input type="text" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();"> --}}
                                                                                <input id="nro_doc_ident_aseg" name="nro_doc_ident_aseg" type="text" value="{{$personal->nro_doc_ident}}" class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Departamento de Nac. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <select class="form-control input-sm" id="id_departamento" name="id_departamento_nacimiento" style="width: auto; flex: 1; font-size:10px;"  required="" onchange="getProvincia();">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @foreach($departamento as $e)
                                                                                        <option value="{{$e->id_departamento}}" @if($e->id_departamento == $personal->id_departamento_nacimiento) selected @endif>{{$e->descripcion}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Religión &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <select id="id_religion" name="id_religion" class="form-control input-sm" style="flex: 1; font-size:10px;" required="">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @foreach($religion as $e)
                                                                                        <option value="{{$e->id}}" @if($e->id == $personal->id_religion) selected @endif>{{$e->nombre}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Condición de Asegurado &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text"  id="cond_asegurado" name="condicion_asegurado" value="{{$personal->condicion_asegurado}}" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Provincia de Nac. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <select class="form-control input-sm" id="id_provincia" name="id_provincia_nacimiento" style="flex: 1; font-size:10px;" required="" onchange="getDistrito();">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @if($provincia_nac != "")
                                                                                        @foreach($provincia_nac as $e)
                                                                                            <option value="{{$e->id_provincia}}" @if($e->id_provincia == $personal->id_provincia_nacimiento) selected @endif>{{$e->descripcion}}</option>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Ocupación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>                                                                            
                                                                                <select id="id_ocupacion" name="id_ocupacion" class="form-control input-sm" style="flex: 1; font-size:10px;" required="">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @foreach($ocupacion as $e)
                                                                                        <option value="{{$e->id}}" @if($e->id == $personal->id_ocupacion) selected @endif>{{$e->nombre}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Parentesco del Asegurado &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                {{-- <input type="text"  id="prtc_aseg" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();"> --}}
                                                                                <select id="prtc_aseg" name="prtc_aseg" class="form-control input-sm" style="flex: 1; font-size:10px !important; margin-left: 0" required="">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @foreach($tipo_beneficiario as $e)
                                                                                        <option value="{{$e->id}}" @if ($e->id == $personal->id_tipo_beneficiario) selected @endif >{{$e->descripcion}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Distrito de Nac. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <select id="id_distrito" name="id_distrito_nacimiento" class="form-control input-sm" style="flex: 1; font-size:10px;" required="">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @if($distrito_nac != "")
                                                                                        @foreach($distrito_nac as $e)
                                                                                            <option value="{{$e->id_distrito}}" @if($e->id_distrito == $personal->id_distrito_nacimiento) selected @endif >{{$e->descripcion}}</option>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Grado de Instrucción &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <select id="id_grado_instruccion" name="id_grado_instruccion" class="form-control input-sm" style="flex: 1; font-size:10px;" required="">
                                                                                    <option value="">-- Seleccionar --</option> 
                                                                                    @foreach($grado_inst as $e)
                                                                                        <option value="{{$e->id}}" @if($e->id == $personal->id_grado_instruccion) selected @endif>{{$e->nombre}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Nombres &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input id="nombre_aseg" name="nombre_aseg" type="text" value="{{$personal->nombre}}" class="form-control input-sm" required="" style="text-transform:uppercase;flex: 1; font-size:10px; padding: 5px;" onkeyup="javascript:this.value = this.value.toUpperCase();" autofocus="" onkeypress="return solo_letra(event)">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Tipo de Sangre &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                {{-- <input id="tipo_sang_aseg" name="tipo_sang_aseg" type="text" value="" class="form-control input-sm" required="" style="text-transform:uppercase;flex: 1; font-size:10px; padding: 5px;" onkeyup="javascript:this.value = this.value.toUpperCase();" autofocus=""> --}}
                                                                                <select id="tipo_sang_aseg" name="tipo_sang_aseg" class="form-control input-sm" style="flex: 1; font-size:10px;" required>
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                        @foreach($tipo_sangre as $e)
                                                                                            <option value="{{ $e->id }}" data-grupo="{{ $e->grupo_sanguineo }}" data-rh="{{ $e->factor_rh }}" @if($e->id == $personal->id_tipo_sangre) selected @endif>{{ $e->descripcion }}</option>
                                                                                        @endforeach
                                                                                </select>

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Etnia &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <select id="id_etnia" name="id_etnia" class="form-control input-sm" style="flex: 1; font-size:10px;" required="">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @foreach($etnia as $e)
                                                                                        <option value="{{ $e->id }}" @if ($e->id == $personal->id_etnia) selected @endif>{{ $e->codigo_etnia }} - {{ $e->nombre_etnia }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Apellido Paterno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input id="paterno_aseg" name="paterno_aseg" type="text" value="{{$personal->paterno}}" class="form-control input-sm" required="" style="text-transform:uppercase; flex: 1; font-size:10px; padding: 5px;" onkeyup="javascript:this.value = this.value.toUpperCase();" onkeypress="return solo_letra(event)">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">G. S. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" value="" id="grupo_sanguineo"  name="grupo_sanguineo" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px; font-size:10px !important;" onkeyup="this.value = this.value.toUpperCase();" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Discapacidad &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" id="discapacidad_aseg" name="discapacidad_aseg" value="{{ $personal->discapacidad == 1 ? 'SI' : ($personal->discapacidad == 0 ? 'NO' : '') }}" maxlength="2" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Apellido Materno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input id="materno_aseg" name="materno_aseg" type="text" value="{{$personal->materno}}" class="form-control input-sm" required="" style="text-transform:uppercase; flex: 1; font-size:10px; padding: 5px;" onkeyup="javascript:this.value = this.value.toUpperCase();" onkeypress="return solo_letra(event)">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">R. H. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" value="" id="factor_rh"  name="factor_rh" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">N° carnet CONADIS </label>
                                                                                <input type="text" id="conadis" name="nro_conadis" value="{{$personal->nro_carnet_conadis}}" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                           <div class="inline-field">
                                                                                <label style="margin: 0;">Sexo &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                {{-- <input type="text" id="sexo_aseg" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();"> --}}
                                                                                <select id="sexo_aseg" name="sexo_aseg" class="form-control input-sm" style="flex: 1; font-size:10px;" required="">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    <option @if($personal->sexo == "M") selected @endif value="M">MASCULINO</option>
                                                                                    <option @if($personal->sexo == "F") selected @endif value="F">FEMENINO</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">N° de celular</label>
                                                                                <input type="text" value="{{$personal->telefono}}" id="cel_aseg" name="cel_aseg" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Ubigeo </label>
                                                                                <input type="text" id="ubig_aseg" name="ubig_aseg" value="{{$personal->codigo_ubig}}" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Fecha de Nacimiento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                {{-- <input type="text" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();"> --}}
                                                                                <input class="form-control input-sm datepicker" type="text" name="fecha_nac_aseg" id="fecha_nac_aseg" value="{{$personal->fecha_nac}}" style="width: auto; padding: 5px; font-size:10px;" placeholder="Fecha Nacimiento" maxlength="10">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">E-mail</label>
                                                                                <input type="text" id="email_aseg" name="email_aseg" value="{{$personal->correo}}" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Situación</label>
                                                                                <input type="text" id="sit_aseg" name="sit_aseg" value="{{$personal->situacion_paciente}}" class="form-control input-sm bloqueado-css" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Domicilio &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" id="domicilio"  name="domicilio" value="{{$personal->domicilio}}" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Departamento Dom. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <select class="form-control input-sm" id="depdom_aseg" name="dep_domicilio_aseg" style="width: auto; flex: 1; font-size:10px;"  required="" onchange="getProvinciaDOM();">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @foreach($departamento as $e)
                                                                                        <option value="{{$e->id_departamento}}" @if ($e->id_departamento == $personal->id_departamento) selected @endif>{{$e->descripcion}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Provincia Dom. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <select class="form-control input-sm" id="provdom_aseg" name="prov_domicilio_aseg" style="flex: 1; font-size:10px;" required="" onchange="getDistritoDOM();">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @if($provincia != "")
                                                                                        @foreach($provincia as $e)
                                                                                            <option value="{{$e->id_provincia}}" @if ($e->id_provincia == $personal->id_provincia) selected @endif>{{$e->descripcion}}</option>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Distrito Dom. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <select id="distdom_aseg" name="dist_domicilio_aseg" class="form-control input-sm" style="flex: 1; font-size:10px;" required="">
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @if($distrito != "")
                                                                                        @foreach($distrito as $e)
                                                                                            <option value="{{$e->id_distrito}}" @if ($e->id_distrito == $personal->id_distrito) selected @endif>{{$e->descripcion}}</option>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <div class="form-group" id="grupoAsignarTutor" style="display: none;">
                                                            <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                                <label style="font-size: 12px;" >Asignar Tutor</label>
                                                            </div>
                                                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                                <button type="button" id="asignarTutor" class="btn btn-lg btn-toggle" aria-pressed="false" autocomplete="off" onclick="activarToggleTutor()"><div class="handle"></div></button>
                                                            </div>
                                                        </div>
                                                        <fieldset id="fs_tutor" style="display:none">
                                                            <div class="panel panel-info panel-bordered panel-sm" style="margin-top:10px !important;">
                                                                <div class="panel-heading" style="padding-bottom: 5px;padding-top: 5px;">
                                                                    <div class="panel-title text-center" style="font-size: 12px;">
                                                                    <b>DATOS DEL TUTOR</b>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field" style="margin:0;">
                                                                                <label>Tipo de Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                {{-- <input type="text" class="form-control input-sm" style="text-transform:uppercase;" onkeyup="this.value = this.value.toUpperCase();"> --}}
                                                                                <select id="tipo_documento_tutor" name="tipo_documento" class="form-control input-sm" style=" flex: 1; font-size:10px;" required>
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @foreach(['DNI', 'CARNET EXTRANJERIA', 'PASAPORTE', 'DOCUMENTO DE IDENTIDAD EXTRANJERO', 'SDI'] as $tipo)
                                                                                        <option value="{{ $tipo }}" @if(old('tipo_documento') == $tipo) selected @elseif(isset($registro_tutor) && $registro_tutor->tipo_documento == $tipo) selected @endif>{{ $tipo }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field" style="margin:0;">
                                                                                <label style="margin: 0;">N° de documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input id="numero_documento_tutor" name="numero_documento" type="text" value="{{ old('numero_documento', isset($registro_tutor) ? $registro_tutor->numero_documento : '') }}" class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field" style="margin:0;">
                                                                                <label style="margin: 0;">Parentesco &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                {{-- <input type="text" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();"> --}}
                                                                                <select id="id_parentesco_tutor" name="id_parentesco" class="form-control input-sm" style="width: auto; flex: 1; font-size:10px;" required>
                                                                                    <option value="">-- Seleccionar --</option>
                                                                                    @foreach($parentesco as $e)
                                                                                        <option value="{{ $e->id }}" @if(old('id_parentesco') == $e->id) selected @elseif(isset($registro_tutor) && $registro_tutor->id_parentesco == $e->id)selected @endif>{{ $e->descripcion }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Nombres &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input id="nombres_tutor" name="nombres" type="text" value="{{ old('nombres', isset($registro_tutor) ? $registro_tutor->nombres : '') }}" style="text-transform:uppercase;" class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();" autofocus="" onkeypress="return solo_letra(event)">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Apellidos &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input id="apellidos_tutor" name="apellidos" type="text" value="{{ old('apellidos', isset($registro_tutor) ? $registro_tutor->apellidos : '') }}" style="text-transform:uppercase;" class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();" autofocus="" onkeypress="return solo_letra(event)">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">N° de celular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input id="telefono_tutor" name="telefono_tutor" type="text" value="{{ old('telefono_tutor', isset($registro_tutor) ? $registro_tutor->telefono_tutor : '') }}" style="text-transform:uppercase;" class="form-control input-sm" onkeypress="return IsNumber(event)">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Domicilio &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input id="direccion_tutor" name="direccion" type="text" value="{{ old('direccion', isset($registro_tutor) ? $registro_tutor->direccion : '') }}" style="text-transform:uppercase;" class="form-control input-sm">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset>
                                                            <div class="panel panel-info panel-bordered panel-sm" style="margin-top:10px !important;" id="panel_paciente_titular">
                                                                <div class="panel-heading" style="padding-bottom: 5px;padding-top: 5px;">
                                                                    <div class="panel-title text-center" style="font-size: 12px;">
                                                                    <b>DATOS DEL TITULAR</b>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="font-size: 12px;">Tipo Documento</label>
                                                                                <input type="text" id="tipo_doc_titu" name="tipo_doc_titu"  value="{{ !empty($personal->tipo_doc_titular) ? $personal->tipo_doc_titular : 'DNI' }}" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="font-size: 12px;">N° de Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input id="nro_doc_titu_drh" name="nro_doc_titu_drh" type="text" value="{{$personal->dni_titular}}" class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                                                            </div>
                                                                        </div>
                                                                        {{-- <div class="col-md-3">
                                                                            <div class="inline-field">
                                                                                <label></label>
                                                                                <button type="button" class="form-control btn-primary" id="buscar_titular_familiar" name="buscar_titular" style="border-radius: 10px;" onclick="getDirrehumByDni('titular')"><i class="fa fa-search"></i><strong style="font-size:12px;"> BUSCAR UNIDAD DE TITULAR</strong></button>
                                                                            </div>
                                                                        </div> --}}
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">N° de CIP &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" id="nro_cip_titu" name="nro_cip_titu" value="{{$personal->cip}}" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();" onkeypress="return IsNumber(event)"> 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    {{-- <div class="row"> --}}
                                                                        {{-- <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">N° de documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" id="nro_doc_titu" name="nro_doc_titu" value="{{$personal->dni_titular}}" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div> --}}
                                                                    {{-- </div> --}}
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Apellido Paterno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" id="paterno_titu" name="paterno_titu" value="{{$personal->paterno_titular}}" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Apellido Materno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" id="materno_titu" name="materno_titu" value="{{$personal->materno_titular}}" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Nombres &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" id="nombre_titu" name="nombre_titu" value="{{$personal->nombre_titular}}" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Grado &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" id="grado_titu" name="grado_titu" value="{{$personal->grado}}" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-4">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Situación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                                <input type="text" id="situacion_titu" name="situacion_titu" value="{{$personal->situacion_titular}}" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="inline-field">
                                                                                <label style="margin: 0;">Unidad </label>
                                                                                <input type="text" id="unidad_titu" name="unidad_titu" value="{{$personal->unidad_pnptitular}}" class="form-control input-sm" style="text-transform:uppercase;" onkeyup="this.value = this.value.toUpperCase();">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <a href="/all_asegurados/{{ Auth::user()->id }}" class="form-control btn btn-danger" style="border-radius: 10px;" >Cancelar</a>
                                        </div> --}}
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <a href="/all_asegurados/{{ Auth::user()->id }}" class="form-control btn {{ Session::has('flash_message') ? 'btn-info-custom' : 'btn-danger' }}" style="border-radius: 10px;"> @if(Session::has('flash_message'))<i class="fa fa-arrow-left"></i> Regresar @else Cancelar @endif </a>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921"  style="border-radius: 10px;" onclick="validateFrmDatosAsegurado()">Guardar</button>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 10px; margin-bottom: 0; font-size: 10px;">
                                            <label style="margin: 0; display: inline-block;">PERSONAL QUE ACTUALIZA INFORMACIÓN: </label>
                                            <p class="form-control-static" style="margin: 0; display: inline-block;"> DNI - {{ Auth::user()->dni }} / {{Auth::user()->paterno}} {{ Auth::user()->materno}} {{ Auth::user()->name}}</p>
                                        </div>
                                    </div> <!-- .tab-pane -->
                                    <div role="tabpanel" class="tab-pane fade" id="tab8default">
                                        <div class="panel panel-info panel-bordered panel-sm" style="margin: 20px 20px">
                                            <div class="panel-body">
                                                
                                                <?php $readonly = $permiso->modificar === "1" ? "" : "readonly"; ?>

                                                @foreach($historias_por_establecimiento as $es)
                                                    <div class="fila-historia" data-id="{{ $es->id_asegurado_historia }}" data-original="{{ $es->nro_historia }}">
                                                        <div class="col-md-9">
                                                            <label>Establecimiento (IPRESS PNP)</label>
                                                            <input value="{{$es->establecimiento}}" type="text" class="form-control input-sm" readOnly>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label>Historia</label>
                                                            <div class="input-group input-group-sm">
                                                                <input value="{{$es->nro_historia}}" type="text" class="form-control input-nro-historia" maxlength="15" readonly>
                                                                @if ($permiso->modificar === "1")
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="btn btn-default btn-editar-historia" title="Editar"><i class="fa fa-pencil"></i></button>
                                                                        <button type="button" class="btn btn-success btn-guardar-historia" title="Guardar" style="display:none"><i class="fa fa-check"></i></button>
                                                                        <button type="button" class="btn btn-danger btn-cancelar-historia" title="Cancelar" style="display:none"><i class="fa fa-times"></i></button>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                @if ($permiso->crear === "1")
                                                    <div class="col-md-12">
                                                        <hr>
                                                    </div>
                                                    
                                                    <div id="historia_clinica_nueva">
                                                        <div class="col-md-6">
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

                                                        <div class="col-md-3" id="grupo_apertura_hc" style="display:none">
                                                            <label>&nbsp;</label>
                                                            <div class="btn-group btn-group-sm btn-group-justified" role="group" style="display:block">
                                                                <div class="btn-group" role="group">
                                                                    <button type="button" id="btn_aperturar_hc" class="btn btn-default">Aperturar</button>
                                                                </div>
                                                                <div class="btn-group" role="group">
                                                                    <button type="button" id="btn_reaperturar_hc" class="btn btn-default">Reaperturar</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label>Historia</label>
                                                            <input id="" name="nro_historia_nuevo" value="" type="text" class="form-control input-sm" maxlength="15">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <a href="/all_asegurados/{{ Auth::user()->id }}" class="form-control btn btn-danger">Cancelar</a>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <button id="btnRegistrarHistoriaClinica" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrmHistoriasClinicaNueva()">Guardar</button>
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

<!-- MODAL DE VISTA PRE GUARDADO -->
<div class="modal fade" id="modalResumen" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document" style="width:95%; max-width:1200px; margin:30px auto;">
        <div class="modal-content">

            <div class="modal-header" style="background:#3c763d; color:white; padding: 10px !important; font-size: 20px !important;">
                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                <h4 class="modal-title" style="font-size: 20px;">CONFIRMACIÓN DE DATOS REGISTRADOS</h4>
            </div>

            <div class="modal-body" id="contenidoResumen" style="max-height:70vh; overflow-y:auto;"></div>

            <div class="modal-footer d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-warning" data-dismiss="modal"> REGRESAR </button>
                    <button type="button" class="btn btn-success" onclick="submitFrm()"> ACEPTAR </button>
                </div>
                <div style="font-size: 10px; text-align: left;">
                    <label style="margin: 0; display: inline;"> PERSONAL QUE ACTUALIZA INFORMACIÓN: </label>
                    <span> DNI - {{ Auth::user()->dni }} / {{ Auth::user()->paterno }} {{ Auth::user()->materno }} {{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </div>
</div>


@include('frontend.includes.footer')
@endsection