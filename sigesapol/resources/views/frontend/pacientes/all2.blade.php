@extends('frontend.layouts.master')
@section('renderjs')

<style>

  .modal-xl {
    width: 55%;
  }

  .filterable {
    margin-top: 0px;
  }

  .filterable .panel-heading .pull-right {
    margin-top: -20px;
  }

  .filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
  }

  .filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
  }

  .filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
  }

  .filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
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

  .modal-header .close {
    position: absolute;
    right: 1rem;
    top: 0.8rem;  
  }
  
  #modalNoRegistros .modal-content {
      width: 100%;
      max-height: calc(100vh - 60px);
      overflow-y: auto;
  } 

  /* Asegurar que los paneles ocultos no afecten el layout */
  .panel[style*="display: none"],
  fieldset[style*="display: none"],
  .form-group[style*="display: none"] {
      display: none !important;
      height: 0 !important;
      margin: 0 !important;
      padding: 0 !important;
  }

  #detalleModal .modal-dialog {
    width: 95%;
    max-width: 1200px;
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

  .is-invalid {
    border: 1px solid #dc3545 !important;
  }

  .is-valid-custom {
    border: 1px solid #007bff !important;
  }

  .text-error {
    color: #dc3545;
    font-size: 11px;
  }

  /**************************** MARCA DE AGUA **************************************/

    .marca_agua_container {
        position: relative;
    }
    
    .marca_agua_overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        pointer-events: none;
        z-index: 10;
        overflow: hidden;
    }
    .marca_agua_overlay span {
        position: absolute;
        color: rgba(5, 98, 24, 0.1);
        font-size: 15px;
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
    }
    .marca_agua_container,
.marca_agua_container * {
    user-select: none !important;
    -webkit-user-select: none !important;
    -moz-user-select: none !important;
}
    /*************************** btn exportar ************************************/
    .btn-exportar {
        color: #fff;
        background-color: #5BC0DE;
        border-color: #5BC0DE;
    }
    .btn-exportar:hover {
        color: #fff;
        background-color: #1C6E87;
        border-color: #1C6E87;
    }
    /*************************** bt toggle desactivar ****************************/
    .toggle-wrap { display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }

    .toggle-track {
        width: 40px; height: 22px; border-radius: 11px;
        position: relative; transition: background 0.2s;
        border: 1px solid #ccc;
    }
    .toggle-thumb {
        position: absolute; top: 3px; left: 3px;
        width: 16px; height: 16px; border-radius: 50%;
        background: #fff; transition: transform 0.2s;
    }

    .toggle-track.on  { background: #3e9a3e; border-color: #4cae4c; }
    .toggle-track.off { background: #b3b3b3; }
    .toggle-track.toggle-bloqueo-atencion.on  { background: #d9534f; border-color: #d43f3a; }
    .toggle-track.toggle-bloqueo-atencion.off { background: #3e9a3e; border-color: #4cae4c; }
    .toggle-track.on .toggle-thumb { transform: translateX(18px); }
    
    /*****************************************************************************/
    .dataTables_wrapper {
        width: 100%;
        overflow-x: auto; 
    }


    #tblprocedimientos {
        width: 100% !important;
    }


    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
    table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
        background-color: #5cb85c;
    }
    /****************************************************************************/
    .swal-afiliacion {
        width: 700px !important;
        padding: 1rem !important;
    }

    .swal-afiliacion .swal2-icon {
        transform: scale(0.5);
        margin: 0;
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
        padding: 6px 70px !important;
        font-size: 13px !important;
    }
    .swal2-backdrop-show {
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px); 
        background: rgba(0, 0, 0, 0.4) !important; 
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
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////  validar dni antes de registrar
    let timerDoc = null;
    $('#nro_doc_ident').on('input', function () {
        let input = $(this);
        let nro_doc = input.val();
        clearTimeout(timerDoc);
        input.removeClass('is-valid-custom is-invalid');
        limpiarErrorDoc();
        $('#btnReg').prop('disabled', true);

        if (nro_doc.length !== 8) {
            return;
        }
        timerDoc = setTimeout(() => {
            $.ajax({
                url: '/validar-documento',
                type: 'POST',
                data: {
                    nro_doc_ident: nro_doc,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.existe) {
                        input.addClass('is-invalid');
                        mostrarErrorDoc("Documento ya registrado");
                        $('#btnReg').prop('disabled', true);
                        $('#btnBuscarSaludpol').prop('disabled', true);
                        $('#buscar_titular_familiar').prop('disabled', true);
                    } else {
                        input.addClass('is-valid-custom');
                        limpiarErrorDoc();
                        $('#btnReg').prop('disabled', false);
                        $('#btnBuscarSaludpol').prop('disabled', false);
                        $('#buscar_titular_familiar').prop('disabled', false);
                    }
                }
            });
        }, 400);
    });
    function mostrarErrorDoc(msg) {
        if (!$('#error-doc').length) {
            $('#nro_doc_ident').after('<small id="error-doc" style="color:red;"></small>');
        }
        $('#error-doc').text(msg);
    }
    function limpiarErrorDoc() {
        $('#error-doc').remove();
    }
    
    $('#btnBuscar').on('click', function () {
            $('#spinnerAsegurados').show();
            tblProcedimientos.ajax.reload(function () {
                $('#spinnerAsegurados').hide();
        });
    });
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    var busquedaRealizada = false; // se activa recien cuando el usuario da clic en Buscar
    $('#btnBuscar').on('click', function () {
            busquedaRealizada = true;
            $('#spinnerAsegurados').show();
            tblProcedimientos.ajax.reload(function () {
                $('#spinnerAsegurados').hide();
        });
    });
   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    var tblProcedimientos;
    $(document).ready(function () {
        $('#tblprocedimientos').on('preXhr.dt', function () {
            if (busquedaRealizada) {
                $('#spinnerAsegurados').show();
            }
        }).on('draw.dt', function () {
            $('#spinnerAsegurados').hide();
        });
        let alreadyAlerted = false;
        tblProcedimientos = $('#tblprocedimientos').DataTable({
            "processing": false,
            "serverSide": true,
            "ajax": function (data, callback, settings) {
                if (!busquedaRealizada) {
                    callback({
                        "draw": data.draw,
                        "recordsTotal": 0,
                        "recordsFiltered": 0,
                        "data": []
                    });
                    return;
                }

                data._token = "{{csrf_token()}}";
                data.dni_beneficiario = $("#dni_beneficiario").val();
                data.nombre_beneficiario = $("#nombre_beneficiario").val();

                $.ajax({
                    url: "/listar_pacientes_ajax",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function (json) {
                        callback(json);
                    }
                });
            },
            "columnDefs": [
                // { "orderable": false, "targets": [10, 11, 12, 13] }
                { "responsivePriority": 1, "targets": 0 },  
                { "responsivePriority": 2, "targets": 1 },  
                { "responsivePriority": 3, "targets": 2 },  
                { "responsivePriority": 4, "targets": 10 }, 
                { "responsivePriority": 10, "targets": 4 }, 
                { "responsivePriority": 11, "targets": 5 }, 
                { "responsivePriority": 12, "targets": 6 },
                { "orderable": false, "targets": [-1, -2, -3, -4] }, 
            ],
            "pageLength": 10,
            // "dom": 'Bfrtip',
            "dom": 'lrtip',
            "filter": true,
            "language": {
                "emptyTable": `
                  <div class="text-center">
                      <p><b>No se encontraron pacientes registrados.</b></p>
                  </div>
                `
            },
            "columns": [
                { "data": "row_number", "name": "N" },
                { "data": "nro_doc_ident", "name": "DNI" },
                { "data": "nombres_paciente", "name": "Apellidos y Nombres" },
                { "data": "sexo", "name": "Sexo" },
                { "data": "fecha_nac", "name": "F.Nac." },
                { "data": "correo", "name": "Correo", "className": "text-center" },
                { "data": "telefono", "name": "Telefono" },
                // { "data": "domicilio", "name": "Domicilio", "className": "text-center" },
                { "data": "descripcion_estado", "name": "Estado", "className": "text-center" },
                { "data": "condicion_asegurado", "name": "Condicion"},
                { "data": "id_tipo_beneficiario", "name": "Parentesco"},
                {
                    "data": null,
                    "name": "Ver registro",
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        if (row['estado'] == 1) {
                            return `<a class='btn btn-exportar btn-xs detalle-btn' style="margin-right:8px;" data-id='${row['id']}' href='/detalle_asegurado/${row['id']}'><i class='fa fa-eye' title="ver registro"></i></a>`;
                        } else {
                            return `<button type="button" class="btn btn-default btn-xs" disabled><i class='fa fa-eye'></i></button>`
                        }
                    }
                }
                @if ($permiso->modificar == 1)
                , {
                    "data": null,
                    "name": "Editar",
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        if (row['estado'] == 1) {
                            return `<a class='btn btn-success btn-xs' href='/editar_paciente/${row['id']}'><i class='fa fa-edit'></i></a>`;
                        } else {
                            return `<button type="button" class="btn btn-default btn-xs" disabled><i class="fa fa-edit"></i></button>`
                        }
                    }
                }
                @endif
                @if ($permiso->eliminar == 1)
                , {
                    "data": null,
                    "name": "Desactivar",
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        const isActive = row['estado'] == 1;
                        return `
                            <label class="toggle-wrap" style="display:inline-flex; align-items:center; gap:8px; cursor:pointer;">
                                <div class="toggle-track ${isActive ? 'on' : 'off'}" onclick="eliminar(${row['id']}, ${row['estado']})"><div class="toggle-thumb"></div></div>
                            </label>
                        `;
                    }
                }
                @if ($is_personal_unitic)
                , {
                    "data": null,
                    "name": "Bloquear Atenciones",
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        const bloqueado = row['flag_bloqueo_atencion'] == 1;
                        return `
                            <label class="toggle-wrap" style="display:inline-flex; align-items:center; gap:8px; cursor:pointer;">
                                <div class="toggle-track toggle-bloqueo-atencion ${bloqueado ? 'on' : 'off'}" title="${bloqueado ? 'Atenciones bloqueadas' : 'Atenciones visibles'}" onclick="bloquearAtenciones(${row['id']}, ${bloqueado ? 1 : 0})"><div class="toggle-thumb"></div></div>
                            </label>
                        `;
                    }
                }
                @endif
                @endif
                , {
                    "data": null,
                    "name": "Ver Detalle",
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        if (row['estado'] == 1) {                     
                            return `<a class='btn btn-xs' style="border:1px solid #007bff; color:#007bff; background-color:transparent; cursor:pointer;" onclick="verDetalle(${row['id']})" onmouseover="this.style.backgroundColor='#007bff'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#007bff';"><i class='fa fa-eye'></i></a>`;                       
                        } else {
                            return `<button type="button" class="btn btn-default btn-xs" disabled><i class="fa fa-eye"></i></button>`
                        }
                    }
                }
            ]
        });
    });

    $(document).ready(function () {
        $('.filterable .btn-filter').click(function () {
            var $panel = $(this).parents('.filterable'),
                    $filters = $panel.find('.filters input'),
                    $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function (e) {
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            var $input = $(this),
                    inputContent = $input.val().toLowerCase(),
                    $panel = $input.parents('.filterable'),
                    column = $panel.find('.filters th').index($input.parents('th')),
                    $table = $panel.find('.table'),
                    $rows = $table.find('tbody tr');
            var $filteredRows = $rows.filter(function () {
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });
            $table.find('tbody .no-result').remove();
            $rows.show();
            $filteredRows.hide();
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el Tipo de Proceso</td></tr>'));
            }
        });
    });
	
	function eliminar(id, estado){
        let mensaje = estado == 0 ? "¿Se activará el personal de salud, desea continuar?" : "¿Se desactivará el paciente, desea continuar?";
        bootbox.confirm(mensaje, function(result) {
            if (result == true) {				
                $.ajax({
                    url: '/desactivar_paciente/' + id + '/' + estado,
                    success: function(response){
                        tblProcedimientos.ajax.reload(null, false);
                    }
                });
            }
        });
    }

    // Bloqueo de atenciones de un paciente
    function bloquearAtenciones(id, flagActual){
        let flag_nuevo = (flagActual == 1) ? 0 : 1;
        let mensaje = (flag_nuevo == 1)
                        ? "¿Se bloquearán las atenciones (PDF firmados) del paciente, desea continuar?"
                        : "¿Se desbloquearán las atenciones (PDF firmados) del paciente, desea continuar?";
        let titulo = (flag_nuevo == 1) ? "Bloquear atenciones" : "Desbloquear atenciones";
        let dialogo = bootbox.dialog({
            title: titulo,
            message: `<div class="form-group">
                        <label for="motivo_bloqueo_atencion">Motivo</label>
                        <textarea id="motivo_bloqueo_atencion" class="form-control" rows="4" maxlength="100" placeholder="Ingrese el motivo (máximo 100 caracteres)"></textarea>
                        <span id="error_motivo_bloqueo" class="text-danger" style="display:none;">El motivo es obligatorio.</span>
                    </div>`,
            buttons: {
                cancel: {
                    label: 'Cancelar',
                    className: 'btn-default'
                },
                ok: {
                    label: 'Aceptar',
                    className: 'btn-primary',
                    callback: function(){
                        let motivo = $.trim($('#motivo_bloqueo_atencion').val());
                        if (motivo === '') {
                            $('#error_motivo_bloqueo').show();
                            return false;
                        }
                        bootbox.confirm(mensaje, function(result) {
                            if (result == true) {
                                $.ajax({
                                    url: '/bloquear_atencion_paciente/' + id + '/' + flag_nuevo,
                                    type: 'POST',
                                    data: {
                                        motivo: motivo,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    dataType: 'json',
                                    success: function(response){
                                        tblProcedimientos.ajax.reload(null, false);
                                    },
                                    error: function(){
                                        bootbox.alert("Ocurrió un error al actualizar el bloqueo de atenciones.");
                                    }
                                });
                            }
                        });
                    }
                }
            }
        });
    }
    
    $(document).on('click', '#btnAbrirModal', function () {
        $('#modalNoRegistros').modal('show');

        let $select = $("#id_tipo_doc_ident");
        $select.find("option").filter(function() {
            return $(this).text() === "DNI";
        }).prop("selected", true);

        let id_tipo_documento_asegurado = $select.find("option:selected").val();   
        let tipo_documento_asegurado = $select.find("option:selected").text(); 

        $("#id_tipo_doc_ident_real").val(id_tipo_documento_asegurado); 
        $("#tipo_doc_ident").val(tipo_documento_asegurado);         
    });

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
        cambiar_tipo_beneficiario();

        const fechaInicial = $('#fecha_nac_aseg').val();
        if (fechaInicial) {
            const edadInicial = calcularEdad(fechaInicial);
            $('#edad').val(edadInicial);
            controlarBeneficiario(edadInicial); 
        }

        $('#fecha_nac_aseg').on('change', function () {
            const fechaNac = $(this).val();
            const edad = calcularEdad(fechaNac);
            $('#edad').val(edad);
            controlarBeneficiario(edad); 
        });
    });

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
    // let bloqueadoCambioTipo = false;
    let esActualizacionApiSaludpol = false;

    $('#prtc_aseg').on('focus', function () {
        tipoBeneficiarioAnterior = $(this).val();
    });

    $('#prtc_aseg').on('change', cambiar_tipo_beneficiario);

    function cambiar_tipo_beneficiario() {
        // if (bloqueadoCambioTipo) return;
        if (esActualizacionApiSaludpol) return;
        const tipoBeneficiarioNuevo = $("#prtc_aseg").val();
        if (tipoBeneficiarioAnterior !== "" && tipoBeneficiarioAnterior !== tipoBeneficiarioNuevo) {
            const textoAnterior = $("#prtc_aseg option[value='" + tipoBeneficiarioAnterior + "']").text();
            const textoNuevo = $("#prtc_aseg option[value='" + tipoBeneficiarioNuevo + "']").text();
            // bloqueadoCambioTipo = true;
            $("#prtc_aseg").val(tipoBeneficiarioAnterior);
            // bloqueadoCambioTipo = false;

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
                        // bloqueadoCambioTipo = true;
                        $("#prtc_aseg").val(tipoBeneficiarioNuevo);
                        // bloqueadoCambioTipo = false;
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
  

  document.addEventListener("DOMContentLoaded", function () {
      const tipoActual = parseInt(document.getElementById("prtc_aseg")?.value);
  });

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

  document.getElementById('id_tipo_doc_ident').addEventListener('change', function() {
      let tipo_documento = this.options[this.selectedIndex].text; 
      document.getElementById('tipo_doc_ident').value = tipo_documento; 
  });

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


    function validateFrm() {
        let isValid = true;
        let errorMessage = "";
        let nameFieldToFocus = "";
        const form = $('#frm_paciente');
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

        // if (isValid) {
        //     if (form.find("#materno_aseg").val() == "") {
        //         isValid = false;
        //         errorMessage = "El campo apellido materno del asegurado no puede estar vacío.";
        //         nameFieldToFocus = "materno_aseg";
        //     }
        // }

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

        //if (!["4", "8"].includes(tipo)) {

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
            bootbox.alert(errorMessage);
            $('#btnReg').prop('disabled', false);
            if (nameFieldToFocus) {
                form.find('#' + nameFieldToFocus).focus();
            }
            return false;
        }
        mostrarResumen()
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

    $('#modalResumen').modal({
        backdrop: 'static',
        keyboard: false
    }).modal('show');
        }

    function submitFrm() {

        $('#btnReg').prop('disabled', true);
        $('#frm_paciente').find('select:disabled').prop('disabled', false);

        let $discp = $('#discapacidad_aseg');
        let val = $discp.val().toUpperCase();

        if (val === 'SI') $discp.val('1');
        else if (val === 'NO') $discp.val('0');

        var form = $("#frm_paciente");

        Swal.fire({
            title: 'Registrando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        form.off("submit");
        form.submit();
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
        const valor = $campo.val();
        if (typeof valor === 'string' && valor.trim() !== '') {
            $campo.addClass('input-detectado');
        }else{
            $campo.removeClass('input-detectado');
        }
    }

  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////// BLOQUEAR TODOS LOS CAMPOS AL CARGAR LA PÁGINA
    const camposTodos = [
        // Asegurado
        'tipo_doc_aseg', 'ec_aseg', 'nro_doc_ident_aseg', 'cond_asegurado', 'prtc_aseg',
        'nombre_aseg', 'paterno_aseg', 'materno_aseg', 'sexo_aseg', 'fecha_nac_aseg',
        'tipo_sang_aseg', 'discapacidad_aseg', 'conadis', 'cel_aseg', 'email_aseg',
        'ubig_aseg', 'domicilio', 'id_departamento', 'id_provincia', 'id_distrito',
        'depdom_aseg', 'provdom_aseg', 'distdom_aseg', 'id_religion', 'id_ocupacion',
        'id_grado_instruccion', 'id_etnia',
        // Tutor
        'tipo_documento_tutor', 'numero_documento_tutor', 'id_parentesco_tutor',
        'nombres_tutor', 'apellidos_tutor', 'telefono_tutor', 'direccion_tutor',
        // Titular
        'tipo_doc_titu', 'nro_doc_titu_drh', 'nro_cip_titu',
        'paterno_titu', 'materno_titu', 'nombre_titu', 'grado_titu',
        'situacion_titu', 'unidad_titu', 'buscar_titular_familiar','btnReg'
    ];
    const camposDesbloqueables = [
        'ec_aseg', 'tipo_sang_aseg', 'id_religion', 'id_ocupacion',
        'id_grado_instruccion', 'id_etnia', 'conadis', 'cel_aseg', 'email_aseg',
        'id_departamento', 'id_provincia', 'id_distrito',
        'depdom_aseg', 'provdom_aseg', 'distdom_aseg', 'domicilio',
        'tipo_documento_tutor', 'numero_documento_tutor', 'id_parentesco_tutor',
        'nombres_tutor', 'apellidos_tutor', 'telefono_tutor', 'direccion_tutor',
         'buscar_titular_familiar',
        /*'unidad_titu',*/'btnReg','sexo_aseg','ubig_aseg','prtc_aseg'
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

        rh = valorApi.includes('+') ? '+' : (valorApi.includes('-') ? '-' : 'ND');

        let $select = $('#tipo_sang_aseg');

        let valorActual = $select.val();
        let nuevoValor = null;

        $select.find('option').each(function () {

            let optGrupo = ($(this).attr('data-grupo') || '').toString().trim().toUpperCase();
            let optRh = ($(this).attr('data-rh') || '').toString().trim();

            if (optGrupo === grupo && optRh === rh) {
                nuevoValor = $(this).val();
                return false;
            }
        });

        if (nuevoValor && nuevoValor !== valorActual) {
            $select.val(nuevoValor).trigger('change');

            setTimeout(() => {
                pintarCampo($select);
            }, 0);
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

        $.ajax({
            url: '/auditoria-hc/registrar',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                origen: 'Asegurados nuevo',
                doc_buscado: nro_doc_ident,
                id_tipo_atencion: null
            }
        });

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
                console.log(data.documentoAsegurado);
                let textoDocAseg = MAPA_TIPO_DOC[data.documentoAsegurado] || data.documentoAsegurado;
                console.log(textoDocAseg);
                seleccionarCuandoEsteListo('tipo_doc_aseg', textoDocAseg);
                setCampo('nro_doc_ident_aseg', data.numeroDocumentoAsegurado);
                setCampo('cond_asegurado', data.condicionAsegurado);
                //seleccionarCuandoEsteListo('prtc_aseg', data.parentescoAsegurado);
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
                //setCampo('nro_doc_titu', data.numeroDocumentoTitular);
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
                    distdom_aseg: 'distritoDomicilio'
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
                // alert('Error: ' + result.message);
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
            //alert('Error de conexión: ' + error);
            Swal.fire({
                icon: 'error',
                title: 'ERROR DE CONEXIÓN',
                text: 'No se pudo establecer conexión con el servidor: ' + error.message
            });
        })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-search"></i><strong style="font-size:12px;"> BUSCAR ASEGURADO</strong>';
        });
    });


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

$('#modalNoRegistros').on('hidden.bs.modal', function () { 
  $(this).find('form')[0].reset(); 
  $(this).find('select').val(''); 
  $(this).find('.input-detectado').removeClass('input-detectado'); 
}); 
////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// VER DETALLE - JHON
function verDetalle(id) {

    $('#modalDetalle').modal('show');
    $('#contenidoDetalle').html('Cargando...');

    $.ajax({
        url: '/detalle_paciente_creacion/' + id,
        type: 'GET',
        success: function(response) {

            if (response.length === 0) {
                $('#contenidoDetalle').html('Sin datos');
                return;
            }

            let data = response[0];

            let html = `
                <div class="row">

                    <div class="col-md-6">
                        <h4>📌 Paciente</h4>
                        <div class="row">
                            <div class="col-md-5"><b>Fecha creación:</b></div>
                            <div class="col-md-7">${data.fecha_creacion ?? '-'}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-5"><b>Creador:</b></div>
                            <div class="col-md-7">${data.dato_creador ?? '-'}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-5"><b>Fecha actualización:</b></div>
                            <div class="col-md-7">${data.fecha_actualizacion ?? '-'}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-5"><b>Actualizador:</b></div>
                            <div class="col-md-7">${data.dato_actualizador ?? '-'}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h4>📁 Historia Clínica - IPRESS Actual </h4>
                        <div class="row">
                            <div class="col-md-5"><b>N° Historia:</b></div>
                            <div class="col-md-7">${data.nro_historia ?? '-'}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-5"><b>Fecha creación:</b></div>
                            <div class="col-md-7">${data.fecha_creacion_historia ?? '-'}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-5"><b>Creador:</b></div>
                            <div class="col-md-7">${data.dato_creador_historia ?? '-'}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-5"><b>Fecha actualización:</b></div>
                            <div class="col-md-7">${data.fecha_actualizacion_historia ?? '-'}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-5"><b>Actualizador:</b></div>
                            <div class="col-md-7">${data.dato_actualizador_historia ?? '-'}</div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <hr>
                        <h4>Bloqueo de atenciones (último movimiento)</h4>
                        ${data.bloqueo_accion ? `
                            <div class="row">
                                <div class="col-md-2"><b>Acción:</b></div>
                                <div class="col-md-9">${data.bloqueo_accion == 1 ? 'Bloqueo' : 'Desbloqueo'}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"><b>Fecha y hora:</b></div>
                                <div class="col-md-9">${data.bloqueo_fecha ?? '-'}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"><b>Usuario:</b></div>
                                <div class="col-md-9">${$('<div>').text(data.bloqueo_usuario ?? '-').html()}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"><b>Motivo:</b></div>
                                <div class="col-md-9">${$('<div>').text(data.bloqueo_motivo ?? '-').html()}</div>
                            </div>
                        ` : '<p>Sin registros de bloqueo o desbloqueo.</p>'}
                    </div>
                </div>
            `;

            $('#contenidoDetalle').html(html);
        },
        error: function() {
            $('#contenidoDetalle').html('Error al cargar datos');
        }
    });
}
////////////////////////////////////////////////////////////////////////////////////////////////
function exportarExcel() {
    let filtro = $('#filtroExport').val();

    window.location.href = '/exportar_asegurados_ipress/' + filtro;
}
////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// MOSTRAR REGISTRO
function parsearFecha(str) {
    if (!str) return null;
    const partes = str.includes('/') 
        ? str.split('/').reverse()   
        : str.split('-');         
    const [año, mes, dia] = partes.map(Number);
    const fecha = new Date(año, mes - 1, dia);
    return isNaN(fecha.getTime()) ? null : fecha;
}

function calcularEdad(fechaNacimiento) {
    if (!fechaNacimiento) return '-';
    if (typeof fechaNacimiento === 'string') {
        fechaNacimiento = parsearFecha(fechaNacimiento);
    }
    if (!fechaNacimiento) return '-';
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

$(document).on('click', '.detalle-btn', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');

    $('#modal-content-data').hide();
    $('#modal-loading').show();
    $('#detalleModal').modal('show');

    $.ajax({
      url: url,
      type: 'GET',
      dataType: 'json',
      success: function(data) {
            function convertirFecha(fechaStr) {
                if (!fechaStr) return '-';
                const [anio, mes, dia] = fechaStr.split("-");
                return `${dia.padStart(2,'0')}/${mes.padStart(2,'0')}/${anio}`;
            }
            function valorTexto(valor, fallback = '-') {
                return (valor !== null && valor !== undefined && valor !== '') ? valor : fallback;
            }

            function tieneValor(val) {
                return val !== null && val !== undefined && val.toString().trim() !== '' && val !== 'null';
            }

            var tipoDoc = data.tipo_numdoc_tutor;
            var parentesco = data.parentesco_tutor;
            var domicilio = data.domicilio_tutor;
            var nombres = data.nombres_tutor;
            var celular = data.celular_tutor;

            $('#panel-tutor').hide();

            if (tieneValor(tipoDoc) || tieneValor(parentesco) || tieneValor(domicilio) || tieneValor(nombres) || tieneValor(celular)) {
                $('#panel-tutor').show();
            }
            
            Swal.fire({
                // icon: 'warning',
                title: '⚠️ CONFIDENCIALIDAD DE DATOS DEL PACIENTE',
                html: `
                    Este sistema contiene información sensible de salud protegida por ley.<br>
                    Está prohibido compartir, copiar o divulgar estos datos sin autorización.<br><br>
                    <b style="color:red;">Infringir esta norma puede conllevar sanciones legales.</b>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'swal-afiliacion'
                }
            });
            
            var condicionSexo = {'M': 'MASCULINO', 'F': 'FEMENINO'};
            const fechaNac = data.fecha_nac ? parsearFecha(data.fecha_nac) : null;
            const edad = fechaNac ? calcularEdad(fechaNac) : '-';

            function esFechaValida(fecha) {
                return fecha && fecha !== 'null' && fecha !== '0000-00-00';
            }
            //fecha inicio afiliación
            $('#modal-fechainiafl').text(esFechaValida(data.fechaini_afiliacion) ? convertirFecha(valorTexto(data.fechaini_afiliacion)): ' - ');
            //fecha fin afiliación
            $('#modal-fechafinafl').text(esFechaValida(data.fechafin_afiliacion) ? convertirFecha(valorTexto(data.fechafin_afiliacion)): ' - ');
            //estado afiliación
            $('#modal-estadoafl').text(valorTexto(data.estado_afiliacion) || '-');
            //tipo y doc de identidad
            $('#modal-tipo-numdoc').text((data.tipo_doc_ident && data.nro_doc_ident) ? data.tipo_doc_ident + ' - ' + data.nro_doc_ident : '-' );
            //nombres y apellidos asegurado
            // $('#modal-nombre').text([data.nombre, data.paterno, data.materno].filter(Boolean).join(' ') || '-');
            $('#modal-nombre').text(valorTexto(data.nombres_asegurado) || '-');
            //condición asegurado
            $('#modal-condicion').text(valorTexto(data.condicion_asegurado) || '-');
            //fecha nacimiento asegurado
            $('#modal-fechaNacimiento').text(valorTexto(data.fecha_nac) || '-');
            //edad /sexo asegurado
            $('#modal-edad-sexo').text(edad + ' / ' + (condicionSexo[data.sexo] || '-'));
            //Dep/Prov/Dist de nacimiento
            $('#modal-deprovdinac').text(valorTexto(data.dep_nac) || '-');
            //Grupo y factor sanguineo
            $('#modal-grupo_sanguineo').text(valorTexto(data.tipo_sangre) || '-');
            //nro de celular asegurado
            $('#modal-num-celular').text(valorTexto(data.telefono) || '-');
            //domicilio actual asegurado
            $('#modal-domicilio').text(valorTexto(data.domicilio) || '-');
            //Dep/Prov/Dist actual
            $('#modal-deprovdidom').text(valorTexto(data.dep_dom) || '-');
            //estado civil
            $('#modal-estado-civil').text(valorTexto(data.estado_civil) || '-');
            //correo electrónico
            $('#modal-correo-electronico').text(valorTexto(data.correo) || '-');
            //Religion
            $('#modal-religion').text(valorTexto(data.religion) || '-');
            //ocupación
            $('#modal-profesion').text(valorTexto(data.ocupacion) || '-');
            //etnia
            $('#modal-etnia').text(valorTexto(data.etnia) || '-');
            //Grado de instrucción
            $('#modal-gradoinst').text(valorTexto(data.grado_instruccion) || '-');
            //Discapacidad
            $('#modal-discapacidad').text(valorTexto(data.discapacidad) || '-');
            //Nro de carnet Conadis
            $('#modal-conadis').text(valorTexto(data.nro_carnet_conadis) || '-');
            //Ubigeo
            $('#modal_ubigeo').text(valorTexto(data.codigo_ubig) || '-');
            //Situacion_paciente
            $('#modal_sit_aseg').text(valorTexto(data.situacion_paciente) || '-');
            // tipo y nro doc tutor
            $('#modal-tipo-numdoc_tutor').text((data.tipo_documento_tutor && data.nro_doc_tutor) ? data.tipo_documento_tutor + ' - ' + data.nro_doc_tutor : '-' );
            // nombres tutor
            $('#modal-nombres_tutor').text(valorTexto(data.nombres_tutor) || '-');
            //parentesco tutor
            $('#modal-parentesco_tutor').text(valorTexto(data.parentesco_tutor) || '-');
            // N° celular tutor
            $('#modal-celular_tutor').text(valorTexto(data.telefono_tutor) || '-');
            //domicilio tutor
            $('#modal-domicilio_tutor').text(valorTexto(data.domicilio_tutor) || '-');
            //tipo y nro doc titular
            $('#modal-tipo-numdoc_titu').text((data.tipo_doc_titular && data.dni_titular) ? data.tipo_doc_titular + ' - ' + data.dni_titular : '-' );
            //nombres titular
            $('#modal-nombres_titu').text(valorTexto(data.nombres_titu) || '-');
            // CIP titular
            $('#modal-cip_titu').text(valorTexto(data.cip_titular) || '-');
            // Grado titular
            $('#modal-grado_titu').text(valorTexto(data.grado) || '-');
            // situacion titular
            $('#modal-situacion_titu').text(valorTexto(data.situacion_titular) || '-');
            //unidad titular
            $('#modal-unidad_titu').text(valorTexto(data.unidad_pnptitular) || '-');

            $('#modal-loading').hide();
            $('#modal-content-data').fadeIn(200, function () {
                if (window.generar_marca_agua_detalle) generar_marca_agua_detalle();
            });

            //Usuario que creó el registro
            let usuarioCrea = (data.dni_user_crea && data.nombres_user_crea) ? 'DNI - ' + data.dni_user_crea + ' / ' + data.nombres_user_crea : '';
            //Usuario que actualizó el registro
            let usuarioActualiza = (data.dni_user_actualiza && data.nombres_user_actualiza) ? 'DNI - ' + data.dni_user_actualiza + ' / ' + data.nombres_user_actualiza : '';

            // Si no hay usuario que actualizó, usar el que creó
            $('#modal-user-actualiza').text(usuarioActualiza || usuarioCrea || ' - ');

        },
        error: function() {
          alert('Error al obtener los detalles del médico.');
        }
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

    document.addEventListener("DOMContentLoaded", function () {
        let ahora = new Date();
        let fecha = String(ahora.getDate()).padStart(2, '0')+ '/' + String(ahora.getMonth() + 1).padStart(2, '0') + '/' + ahora.getFullYear();
        let hora = String(ahora.getHours()).padStart(2, '0') + ':' + String(ahora.getMinutes()).padStart(2, '0') + ':' + String(ahora.getSeconds()).padStart(2, '0');
        document.getElementById("fecha_actual").value = fecha + ' - ' + hora;
    });


    $('#btnBuscar').on('click', function() {
        tblProcedimientos.ajax.reload(null, true);
    });

    // $('#dni_beneficiario, #nombre_beneficiario').on('keypress', function (e) {
    //     if (e.which == 13) { 
    //         e.preventDefault();
    //         $('#btnBuscar').click();
    //         return false;
    //     }
    // });

    function cerrarModal() {
        $('#modalNoRegistros').modal('hide');
    }


    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('.solo-numeros');

        inputs.forEach(input => {
            input.addEventListener('keydown', e => {
            if (e.key === 'F5' || (e.ctrlKey && e.key.toLowerCase() === 'r')) {
                return;
            }
            const teclasPermitidas = [
                'Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End', 'Enter'
            ];

            if (e.ctrlKey || e.metaKey) {
                const teclasCtrl = ['c', 'v', 'x', 'a'];
                if (teclasCtrl.includes(e.key.toLowerCase())) return;
            }

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

    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('.solo-letras');
        inputs.forEach(input => {
            input.addEventListener('keydown', e => {
            if (e.key === 'F5' || (e.ctrlKey && e.key.toLowerCase() === 'r')) {
                return;
            }
            const teclasPermitidas = [
                'Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End', 'Enter'
            ];

            if (e.ctrlKey || e.metaKey) {
                const teclasCtrl = ['c', 'v', 'x', 'a'];
                if (teclasCtrl.includes(e.key.toLowerCase())) return;
            }

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
    

    (function () {
        var texto_marca_agua = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';

        function generar_marca_agua() {
            var overlay = document.getElementById('marca_agua_overlay');
            if (!overlay) return;

            overlay.innerHTML = '';

            var W = overlay.offsetWidth  || 800;
            var H = overlay.offsetHeight || 600;

            var cols = Math.ceil(W / 220) + 1;
            var rows = Math.ceil(H / 100) + 1;

            for (var r = 0; r < rows; r++) {
                for (var c = 0; c < cols; c++) {
                    var span = document.createElement('span');
                    span.textContent = texto_marca_agua;
                    span.style.left = (c * 220 - 40) + 'px';
                    span.style.top  = (r * 100 - 20) + 'px';
                    overlay.appendChild(span);
                }
            }
        }

        $('#detalleModal').on('shown.bs.modal', function () {
            setTimeout(function () {
                generar_marca_agua();
            }, 100); 
        });

        $(window).on('resize', function () {
            if ($('#detalleModal').hasClass('in') || $('#detalleModal').is(':visible')) {
                generar_marca_agua();
            }
        });

        window.generar_marca_agua_detalle = generar_marca_agua;
    })();

    (function () {
        var texto_marca_agua_datos_paciente_nuevo = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';

        function generar_marca_agua_datos_paciente_nuevo() {
            var overlay = document.getElementById('marca_agua_overlay_datos_paciente_nuevo');
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
                    span.textContent = texto_marca_agua_datos_paciente_nuevo;
                    span.style.left = (c * 220 - 40) + 'px';
                    span.style.top  = (r * 100 - 20) + 'px';
                    overlay.appendChild(span);
                }
            }
        }

        $('#modalNoRegistros').on('shown.bs.modal', function () {
            setTimeout(function () {
                generar_marca_agua_datos_paciente_nuevo();
            }, 100);
        });

        $('#modalNoRegistros a[data-toggle="tab"]').on('shown.bs.tab', function () {
            generar_marca_agua_datos_paciente_nuevo();
        });

        $(window).on('resize', function () {
            if ($('#modalNoRegistros').hasClass('in') || $('#modalNoRegistros').is(':visible')) {
                generar_marca_agua_datos_paciente_nuevo();
            }
        });
    })();
    ///////////////////////////////////////////////////////////////////// VALIDACIÓN PARA NO PERMITIR SELECCIONAR NI HACER COPIADO DEL MODAL DETALLE
    $('#detalleModal').on('shown.bs.modal', function () {
        document.addEventListener('contextmenu', bloquear);
        document.addEventListener('keydown', bloquearTeclado);
        document.addEventListener('selectstart', bloquear);
        document.addEventListener('copy', bloquear);
    });

    $('#detalleModal').on('hidden.bs.modal', function () {
        document.removeEventListener('contextmenu', bloquear);
        document.removeEventListener('keydown', bloquearTeclado);
        document.removeEventListener('selectstart', bloquear);
        document.removeEventListener('copy', bloquear);
    });
    function bloquear(e) {
        var modal = document.getElementById('detalleModal');
        if (modal.contains(e.target)) {
            e.preventDefault();
        }
    }
    function bloquearTeclado(e) {
        var modal = document.getElementById('detalleModal');
        if (modal.contains(e.target) && e.ctrlKey) {
            e.preventDefault();
        }
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
<div class="container-fluid">
  <div class="row">
    <div class="panel panel-primary filterable">
      <div class="panel-heading">
        <h3 class="panel-title"><strong> MÓDULO DE ASEGURADOS</strong></h3>     
      </div>
      <div class="panel-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group" style="margin:0.2em;">
                            <label for="dni_beneficiario">N° de Documento</label>
                            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control text-uppercase solo-numeros" maxlength="15" value="">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group" style="margin:0.2em;">
                            <label for="nombre_beneficiario">Apellidos y Nombres</label>
                            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder="" class="form-control text-uppercase solo-letras" maxlength="60" value="">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label></label>
                        <select id="filtroExport" class="form-control" style="margin-top: 7px;">
                            <option value="1">Último mes</option>
                            <option value="2">Últimos 3 meses</option>
                            <option value="0">Total (IPRESS)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right" style="margin-top:1.8em;">
                <a id="btnBuscar" class="btn btn-success" style="width: 250px; margin-right: 5px;" ><strong> BUSCAR</strong></a>
                <?php if ($permiso->crear == 1): ?>
                    <a id="btnAbrirModal" class="btn btn-warning" style="width: 250px; margin-right: 5px;"><strong><i class="fa fa-plus"></i> NUEVO </strong></a>
                <?php endif; ?> 
                <a href="#" class="btn btn-exportar" onclick="exportarExcel()" style="width: 250px; margin-right: 5px;"><strong><i class="fa fa-file-excel-o" aria-hidden="true"></i> REPORTE</strong></a></div>
            </div>
        </div>
        <?php if ($permiso->crear == 1): ?>
        <!--<a href="/formulario_procedimiento" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo </a> -->
        <?php endif; ?>
        
        <br /><br />
        <div class="table-responsive" style="margin-left:10px; margin-right:10px;">
            <table class="table" id="tblprocedimientos">
                <thead>
                <tr class="filters">
                    <th>N</th>
                    <th>DNI</th>
                    <th>Apellidos y Nombres</th>
                    <th>Sexo</th>
                    <th style="width: 70px">F.Nac.</th>
                    <th>Correo</th>
                    <th class="text-center">Telefono</th>
                    {{-- <th class="text-center">Domicilio</th> --}}
                    <th>Estado</th>
                    <th>Condición</th>
                    <th>Parentesco</th>
                    <th>Ver registro</th>
                    @if ($permiso->modificar == 1)
                    <th>Editar</th>
                    @endif
                    @if ($permiso->eliminar == 1)
                    <th style="width: 60px">Activar/Desactivar</th>
                    @if ($is_personal_unitic)
                    <th style="width: 60px">Bloquear Atenciones</th>
                    @endif
                    @endif
                    <th>Detalle</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div id="spinnerAsegurados" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;backdrop-filter:blur(2px);z-index:9999;text-align:center;">
                <div style="position:relative;top:40%;">
                    <i class="fa fa-spinner fa-spin fa-5x" style="color:#414241;"></i>
                    <p style="margin-top:15px;font-size:18px;color:#414241;font-weight:bold;"> Cargando asegurados, por favor espere... </p>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalNoRegistros" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h3 class="panel-title"  style="font-size:20px"><strong>Datos del Paciente</strong></h3>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            
            <form id="frm_paciente" class="form-horizontal" method="post" action="{{ route('registrar_paciente2') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="tipo_doc_ident" name="tipo_doc_ident" value="">
                <input type="hidden" id="id_tipo_doc_ident_real" name="id_tipo_doc_ident">
                <input type="hidden" name="id_asegurado_titular" id="id_asegurado_titular" value="">

                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab7default" aria-controls="tab7default" role="tab" data-toggle="tab">DATOS DEL PACIENTE</a></li>
                    <li role="presentation"><a href="#tab8default" aria-controls="tab8default" role="tab" data-toggle="tab">HISTORIA CLINICA</a></li>
                </ul>
                <div class="tab-content marca_agua_container" style="position:relative;">
                    <div class="marca_agua_overlay" id="marca_agua_overlay_datos_paciente_nuevo"></div>
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
                                                    <option value="{{$e->id}}">{{$e->descripcion}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="font-size: 12px;">N° de Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                <input id="nro_doc_ident" name="nro_doc_ident" type="text" value="" class="form-control input-sm"  style ="font-size:10px;" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                            </div>
                                            <div class="col-md-3">
                                                <label></label>
                                                {{-- <button type="button" class="form-control btn-primary" id="btnBuscarSaludpol" name="btnBuscarSaludpol" style="border-radius: 10px;" onclick="getSaludpolByTipoDocAndNroDoc();"><strong style="font-size: 12px;">BUSCAR ASEGURADO</strong></button> --}}
                                                <button type="button" class="form-control btn-primary" id="btnBuscarSaludpol" name="btnBuscarSaludpol" style="border-radius: 10px;"><i class="fa fa-search"></i><strong style="font-size:12px;"> BUSCAR ASEGURADO</strong></button>
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
                                                        <input type="text" class="form-control input-sm" id="fecha_ini_afl" value='' name="fechaini_afiliacion" style="width:180px;" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="inline-field">
                                                        <label style="margin-right:5px;">Fecha Fin de Afiliación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <input type="text" class="form-control input-sm" id="fecha_fin_afl" value='' name="fechafin_afiliacion" style="width:180px;" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="inline-field">
                                                        <label style="margin-right:5px;">Estado de Afiliación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                        <input type="text" class="form-control input-sm" id="estado_afl" value='' name="estado_afiliacion" style="width:180px; text-transform:uppercase;" readonly>
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
                                                                <select class="form-control input-sm" id="tipo_doc_aseg" name="tipo_doc_aseg" style="flex: 1; font-size:10px;" tabindex="3">
                                                                    <option value="">-- Seleccionar --</option>
                                                                    @foreach($tipo_documento_identidad as $e)
                                                                        <option value="{{$e->id}}">{{$e->descripcion}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field" style="margin:0;">
                                                                <label style="margin: 0;">Edad &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input type="text" id="edad" value="" class="form-control input-sm" style="width: auto; padding: 5px; text-align:center;" onkeyup="this.value = this.value.toUpperCase();" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field" style="margin:0;">
                                                                <label style="margin: 0;">Estado Civil &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="ec_aseg" name="estado_civil_aseg" class="form-control input-sm" style="width: auto; flex: 1; font-size:10px" required="">
                                                                    <option value="">-- Seleccionar --</option>
                                                                    @foreach($estado_civil as $e)
                                                                        <option value="{{$e->id}}">{{$e->nombre}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">N° de documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="nro_doc_ident_aseg" name="nro_doc_ident_aseg" type="text" value="" class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Departamento de Nac. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select class="form-control input-sm" id="id_departamento" name="id_departamento_nacimiento" style="width: auto; flex: 1; font-size:10px;"  required="" onchange="getProvincia();">
                                                                    <option value="">-- Seleccionar --</option>
                                                                    @foreach($departamento as $e)
                                                                        <option value="{{$e->id_departamento}}">{{$e->descripcion}}</option>
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
                                                                        <option value="{{$e->id}}">{{$e->nombre}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Condición de Asegurado &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input type="text"  id="cond_asegurado" name="condicion_asegurado" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Provincia de Nac. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select class="form-control input-sm" id="id_provincia" name="id_provincia_nacimiento" style="flex: 1; font-size:10px;" required="" onchange="getDistrito();">
                                                                    <option value="">-- Seleccionar --</option>
                                                                    @if($provincia_nac != "")
                                                                        @foreach($provincia_nac as $e)
                                                                            <option value="{{$e->id_provincia}}">{{$e->descripcion}}</option>
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
                                                                        <option value="{{$e->id}}">{{$e->nombre}}</option>
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
                                                                        <option value="{{$e->id}}">{{$e->descripcion}}</option>
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
                                                                            <option value="{{$e->id_distrito}}" >{{$e->descripcion}}</option>
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
                                                                        <option value="{{$e->id}}">{{$e->nombre}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Nombres &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="nombre_aseg" name="nombre_aseg" type="text" value="" class="form-control input-sm" required="" style="text-transform:uppercase;flex: 1; font-size:10px; padding: 5px;" onkeyup="javascript:this.value = this.value.toUpperCase();" autofocus="" onkeypress="return solo_letra(event)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Tipo de Sangre &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="tipo_sang_aseg" name="tipo_sang_aseg" class="form-control input-sm" style="flex: 1; font-size:10px;" required>
                                                                    <option value="">-- Seleccionar --</option>
                                                                        @foreach($tipo_sangre as $e)
                                                                            <option value="{{ $e->id }}" data-grupo="{{ $e->grupo_sanguineo }}" data-rh="{{ $e->factor_rh }}">{{ $e->descripcion }}</option>
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
                                                                        <option value="{{ $e->id }}">{{ $e->codigo_etnia }} - {{ $e->nombre_etnia }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Apellido Paterno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="paterno_aseg" name="paterno_aseg" type="text" value="" class="form-control input-sm" required="" style="text-transform:uppercase; flex: 1; font-size:10px; padding: 5px;" onkeyup="javascript:this.value = this.value.toUpperCase();" onkeypress="return solo_letra(event)">
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
                                                                <input type="text" id="discapacidad_aseg" name="discapacidad_aseg" value="" maxlength="2" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Apellido Materno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="materno_aseg" name="materno_aseg" type="text" value="" class="form-control input-sm" required="" style="text-transform:uppercase; flex: 1; font-size:10px; padding: 5px;" onkeyup="javascript:this.value = this.value.toUpperCase();" onkeypress="return solo_letra(event)">
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
                                                                <input type="text" id="conadis" name="nro_conadis" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Sexo &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="sexo_aseg" name="sexo_aseg" class="form-control input-sm" style="flex: 1; font-size:10px;" required="">
                                                                    <option value="">-- Seleccionar --</option>
                                                                    <option value="M">MASCULINO</option>
                                                                    <option value="F">FEMENINO</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">N° de celular</label>
                                                                <input type="text" value="" id="cel_aseg" name="cel_aseg" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Ubigeo </label>
                                                                <input type="text" id="ubig_aseg" name="ubig_aseg" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Fecha de Nacimiento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input class="form-control input-sm datepicker" type="text" name="fecha_nac_aseg" id="fecha_nac_aseg" value="" style="width: auto; padding: 5px; font-size:10px;" placeholder="Fecha Nacimiento" maxlength="10">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">E-mail</label>
                                                                <input type="text" id="email_aseg" name="email_aseg" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Situación</label>
                                                                <input type="text" id="sit_aseg" name="sit_aseg" value="" class="form-control input-sm bloqueado-css" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Domicilio &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input type="text" id="domicilio"  name="domicilio" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto; padding: 5px;" onkeyup="this.value = this.value.toUpperCase();">
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
                                                                        <option value="{{$e->id_departamento}}">{{$e->descripcion}}</option>
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
                                                                            <option value="{{$e->id_provincia}}">{{$e->descripcion}}</option>
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
                                                                            <option value="{{$e->id_distrito}}">{{$e->descripcion}}</option>
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
                                                                <select id="tipo_documento_tutor" name="tipo_documento" class="form-control input-sm" style=" flex: 1; font-size:10px;" required>
                                                                    <option value="">-- Seleccionar --</option>
                                                                    @foreach(['DNI', 'CARNET EXTRANJERIA', 'PASAPORTE', 'DOCUMENTO DE IDENTIDAD EXTRANJERO', 'SDI'] as $tipo)
                                                                        <option value="{{ $tipo }}">{{ $tipo }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field" style="margin:0;">
                                                                <label style="margin: 0;">N° de documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="numero_documento_tutor" name="numero_documento" type="text" value="" class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field" style="margin:0;">
                                                                <label style="margin: 0;">Parentesco &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <select id="id_parentesco_tutor" name="id_parentesco" class="form-control input-sm" style="width: auto; flex: 1; font-size:10px;" required>
                                                                    <option value="">-- Seleccionar --</option>
                                                                    @foreach($parentesco as $e)
                                                                        <option value="{{ $e->id }}">{{ $e->descripcion }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Nombres &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="nombres_tutor" name="nombres" type="text" value="" style="text-transform:uppercase;" class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();" autofocus="" onkeypress="return solo_letra(event)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Apellidos &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="apellidos_tutor" name="apellidos" type="text" value="" style="text-transform:uppercase;" class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();" autofocus="" onkeypress="return solo_letra(event)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">N° de celular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="telefono_tutor" name="telefono_tutor" type="text" value="" style="text-transform:uppercase;" class="form-control input-sm" onkeypress="return IsNumber(event)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Domicilio &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="direccion_tutor" name="direccion" type="text" value="" style="text-transform:uppercase;" class="form-control input-sm">
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
                                                                <input type="text" id="tipo_doc_titu" name="tipo_doc_titu"  value="" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="font-size: 12px;">N° de Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input id="nro_doc_titu_drh" name="nro_doc_titu_drh" type="text" value="" class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
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
                                                                <input type="text" id="nro_cip_titu" name="nro_cip_titu" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();" onkeypress="return IsNumber(event)"> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">N° de documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input type="text" id="nro_doc_titu" name="nro_doc_titu" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Apellido Paterno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input type="text" id="paterno_titu" name="paterno_titu" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Apellido Materno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input type="text" id="materno_titu" name="materno_titu" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Nombres &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input type="text" id="nombre_titu" name="nombre_titu" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Grado &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input type="text" id="grado_titu" name="grado_titu" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Situación &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                                                <input type="text" id="situacion_titu" name="situacion_titu" value="" class="form-control input-sm" style="text-transform:uppercase; width: auto;" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="inline-field">
                                                                <label style="margin: 0;">Unidad </label>
                                                                <input type="text" id="unidad_titu" name="unidad_titu" value="" class="form-control input-sm" style="text-transform:uppercase;" onkeyup="this.value = this.value.toUpperCase();">
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
                        
                    </div> <!-- .tab-pane -->
                    <div role="tabpanel" class="tab-pane fade" id="tab8default">
                        <div class="panel panel-info panel-bordered panel-sm" style="margin: 20px 20px">
                            <div class="panel-body">
                                @foreach($establecimiento as $es)
                                    <div class="col-md-9">
                                        <label>Establecimiento (IPRESS PNP)</label>
                                        <input id="nombre_est" name="nombre_est" value="{{ $es->nombre }}" type="text" class="form-control input-sm" readonly>
                                        <input id="id_establecimiento" name="id_establecimiento" value="{{ $es->id }}" type="hidden">
                                    </div>
                                @endforeach
                                <div class="col-md-3">
                                    <label>Historia &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                    <input id="nro_historia" name="nro_historia" type="text" class="form-control input-sm" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row w-100">
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                <button type="button" class="form-control btn btn-danger" onclick="cerrarModal()">
                                    Cancelar
                                </button>
                            </div>
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()">Guardar</button>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 10px; margin-bottom: 0; font-size: 10px; text-align:left !important;">
                            <label style="margin: 0; display: inline-block;">PERSONAL QUE REGISTRA INFORMACIÓN: </label>
                            <p class="form-control-static" style="margin: 0; display: inline-block;"> DNI - {{ Auth::user()->dni }} / {{Auth::user()->paterno}} {{ Auth::user()->materno}} {{ Auth::user()->name}}</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--=========================================== MODAL VISTA REGISTRO ===================================================================================-->
<div class="modal fade" id="detalleModal" tabindex="-1">
  <div class="modal-dialog" style="width:95%; max-width:1200px;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title text-center" style="font-size:20px;">
                <strong>REGISTRO DE ASEGURADOS</strong>
            </h4>
        </div>

        <div class="modal-body marca_agua_container" style="background:#f5f5f5; font-size:12px; position:relative;">

            <!-- MARCA DE AGUA -->
            <div class="marca_agua_overlay" id="marca_agua_overlay"></div>

            <div id="modal-loading" style="display:none; text-align:center; padding:20px;">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
                <p>Cargando datos...</p>
            </div>
            <div id="modal-content-data" style="display:none; position:relative; z-index:1;">

                <!-- DATOS DE AFILIACIÓN -->
                <div class="panel" style="border:1px solid #9fbf9f;">
                    <div class="panel-heading text-center" style="background:#cfe3cf;">
                        <strong>DATOS DE AFILIACIÓN</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table-condensed" style="width:60%;">
                                <tr><td><b>FECHA INICIO DE AFILIACIÓN</b></td><td>:</td><td><span id="modal-fechainiafl"></span></td></tr>
                                <tr><td><b>FECHA FIN DE AFILIACIÓN</b></td><td>:</td><td><span id="modal-fechafinafl"></span></td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table-condensed" style="width:55%;">
                                <tr><td><b>ESTADO DE AFILIACIÓN</b></td><td>:</td><td><span id="modal-estadoafl"></span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DATOS DEL ASEGURADO -->
                <div class="panel" style="border:1px solid #9fbf9f;">
                    <div class="panel-heading text-center" style="background:#cfe3cf;">
                        <strong>DATOS DEL ASEGURADO</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table-condensed" style="width:100%;">
                                <tr><td><b>TIPO Y N° DE DOC</b></td><td>:</td><td><span id="modal-tipo-numdoc"></span></td></tr>
                                <tr><td><b>NOMBRES Y APELLIDOS</b></td><td>:</td><td><span id="modal-nombre"></span></td></tr>
                                <tr><td><b>CONDICIÓN DEL ASEGURADO</b></td><td>:</td><td><span id="modal-condicion"></span></td></tr>
                                <tr><td><b>FECHA NACIMIENTO</b></td><td>:</td><td><span id="modal-fechaNacimiento"></span></td></tr>
                                <tr><td><b>DEP/PROV/DIST DE NACIMIENTO</b></td><td>:</td><td><span id="modal-deprovdinac"></span></td></tr>
                                <tr><td><b>EDAD / SEXO</b></td><td>:</td><td><span id="modal-edad-sexo"></span></td></tr>
                                <tr><td><b>GRUPO Y FACTOR SANGUÍNEO</b></td><td>:</td><td><span id="modal-grupo_sanguineo"></span></td></tr>
                                <tr><td><b>N° CELULAR</b></td><td>:</td><td><span id="modal-num-celular"></span></td></tr>
                                <tr><td><b>DOMICILIO ACTUAL</b></td><td>:</td><td><span id="modal-domicilio"></span></td></tr>
                                <tr><td><b>DEP/PROV/DIST DE DOMICILIO</b></td><td>:</td><td><span id="modal-deprovdidom"></span></td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table-condensed" style="width:100%;">
                                <tr><td><b>ESTADO CIVIL</b></td><td>:</td><td><span id="modal-estado-civil"></span></td></tr>
                                <tr><td><b>CORREO ELECTRÓNICO</b></td><td>:</td><td><span id="modal-correo-electronico"></span></td></tr>
                                <tr><td><b>RELIGIÓN</b></td><td>:</td><td><span id="modal-religion">-</span></td></tr>
                                <tr><td><b>OCUPACIÓN</b></td><td>:</td><td><span id="modal-profesion"></span></td></tr>
                                <tr><td><b>ETNIA</b></td><td>:</td><td><span id="modal-etnia"></span></td></tr>
                                <tr><td><b>GRADO DE INSTRUCCION</b></td><td>:</td><td><span id="modal-gradoinst"></span></td></tr>
                                <tr><td><b>DISCAPACIDAD</b></td><td>:</td><td><span id="modal-discapacidad"></span></td></tr>
                                <tr><td><b>N° CONADIS</b></td><td>:</td><td><span id="modal-conadis">-</span></td></tr>
                                <tr><td><b>UBIGEO</b></td><td>:</td><td><span id="modal_ubigeo"></span></td></tr>
                                <tr><td><b>SITUACIÓN</b></td><td>:</td><td><span id="modal_sit_aseg"></span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TUTOR -->
                <div class="panel" id="panel-tutor" style="border:1px solid #9fbf9f;">
                    <div class="panel-heading text-center" style="background:#cfe3cf;">
                        <strong>DATOS DEL TUTOR</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table-condensed" style="width:100%;">
                                <tr><td><b>TIPO Y N° DE DOC</b></td><td>:</td><td><span id="modal-tipo-numdoc_tutor"></span></td></tr>
                                <tr><td><b>PARENTESCO</b></td><td>:</td><td><span id="modal-parentesco_tutor"></span></td></tr>
                                <tr><td><b>DOMICILIO ACTUAL</b></td><td>:</td><td><span id="modal-domicilio_tutor"></span></td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table-condensed" style="width:100%;">
                                <tr><td><b>NOMBRES Y APELLIDOS</b></td><td>:</td><td><span id="modal-nombres_tutor"></span></td></tr>
                                <tr><td><b>N° DE CELULAR</b></td><td>:</td><td><span id="modal-celular_tutor"></span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TITULAR -->
                <div class="panel" style="border:1px solid #9fbf9f;">
                    <div class="panel-heading text-center" style="background:#cfe3cf;">
                        <strong>DATOS DEL TITULAR</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table-condensed" style="width:100%;">
                                <tr><td><b>TIPO Y N° DE DOC</b></td><td>:</td><td><span id="modal-tipo-numdoc_titu"></span></td></tr>
                                <tr><td><b>N° CIP</b></td><td>:</td><td><span id="modal-cip_titu"></span></td></tr>
                                <tr><td><b>SITUACIÓN</b></td><td>:</td><td><span id="modal-situacion_titu"></span></td></tr>
                                <tr><td><b>UNIDAD</b></td><td>:</td><td><span id="modal-unidad_titu"></span></td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table-condensed" style="width:100%;">
                                <tr><td><b>NOMBRES Y APELLIDOS</b></td><td>:</td><td><span id="modal-nombres_titu"></span></td></tr>
                                <tr><td><b>GRADO</b></td><td>:</td><td><span id="modal-grado_titu"></span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal-footer d-flex justify-content-between align-items-center">
            <button class="btn btn-danger" data-dismiss="modal"><strong>CERRAR</strong></button>
            <div style="font-size: 10px; text-align: left;">
                <label style="margin: 0; display: inline;">
                    PERSONAL QUE ACTUALIZÓ INFORMACIÓN:
                </label><span id="modal-user-actualiza"> - </span>
            </div>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDetalle" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detalle del Paciente</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body" id="contenidoDetalle">
        Cargando...
      </div>
    </div>
  </div>
</div>

<div id="modalResumen" class="modal fade" tabindex="-1">
  <div class="modal-dialog" style="width:90%; max-width:1000px;">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" style="font-size:18px"><strong>Resumen del Registro</strong></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body" id="contenidoResumen"></div>

      <div class="modal-footer">

        <button type="button" class="btn btn-danger" data-dismiss="modal">
          Cancelar
        </button>
        <button type="button" class="btn btn-success" onclick="submitFrm()">
          Confirmar y Guardar
        </button>
      </div>

    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection