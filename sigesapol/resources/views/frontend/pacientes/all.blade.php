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

      $(document).ready(function () {
        let alreadyAlerted = false; 

        var tblProcedimientos = $('#tblprocedimientos').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/listar_pacientes_ajax",
                "type": "POST",
                "dataType": "json",
                "data": {
                    _token: "{{csrf_token()}}"
                },
                "dataSrc": function (json) {
                    // Solo mostrar el alert si hay búsqueda sin resultados
                    if (json.recordsFiltered === 0 && !alreadyAlerted) {
                        alreadyAlerted = true;
                        bootbox.alert("No se encontraron datos del asegurado.");
                    } else if (json.recordsFiltered > 0) {
                        // Resetear si hay resultados
                        alreadyAlerted = false;
                    }

                    return json.data;
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": [9, 10] }
            ],
            "pageLength": 10,
            "dom": 'Bfrtip',
            "filter": true,
            "language": {
                "emptyTable": `
                  <div class="text-center">
                      <p><b>No se encontraron pacientes registrados.</b></p>
                      <button type="button" class="btn btn-primary btn-sm" id="btnAbrirModal">
                          <i class="fa fa-plus"></i> Registrar Asegurado
                      </button>
                      <a href="/all_asegurados" class="btn btn-danger btn-sm">
                          Cancelar
                      </a>
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
                { "data": "domicilio", "name": "Domicilio", "className": "text-center" },
                { "data": "descripcion_estado", "name": "Estado", "className": "text-center" }
                @if ($permiso->modificar == 1)
                , {
                    "data": null,
                    "name": "Editar",
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        if (row['estado'] == 1) {
                            return `<a class='btn btn-success btn-xs' href='/editar_paciente/${row['id']}'><i class='fa fa-edit'></i></a>`;
                        } else {
                            return `<button type="button" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button>`
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
                        if (row['estado'] == 1) {
                            return `<a class="btn btn-warning btn-xs" href="javascript:void(0)"
                                onclick="eliminar(${row['id']},${row['estado']})"><i class="fa fa-thumbs-up"></i></a>`;
                        } else {
                            return `<a class="btn btn-success btn-xs" href="javascript:void(0)"
                                onclick="eliminar(${row['id']},${row['estado']})"><i class="fa fa-thumbs-up"></i></a>`
                        }
                    }
                }
                @endif
                , {
                    "data": null,
                    "name": "Ver Detalle",
                    "className": "text-center",
                    "render": function (data, type, row, meta) {                     
                            return `<a class='btn btn-xs'
                                    style="border:1px solid #007bff; color:#007bff; background-color:transparent; cursor:pointer;"
                                    onclick="verDetalle(${row['id']})"
                                    onmouseover="this.style.backgroundColor='#007bff'; this.style.color='#fff';"
                                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='#007bff';">
                                        <i class='fa fa-eye'></i>
                                    </a>`;                       
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
    if(estado == 0){
            bootbox.confirm("¿Se activará el personal de salud, desea continuar?", function(result) {
            if (result==true) {				
                $.ajax({
                    url: '/desactivar_paciente/'+id+'/'+estado,
                    success: function(result){
                        //$(".alert-danger").html("sddsssfc").show();
                        location.reload();
                    }
                  });
            }
        });
    } else {
            bootbox.confirm("¿Se desactivará el paciente, desea continuar?", function(result) {
            if (result==true) {				
                $.ajax({
                    url: '/desactivar_paciente/'+id+'/'+estado,
                    success: function(result){
                        //$(".alert-danger").html("sddsssfc").show();
                        location.reload();
                    }
                  });
            }
        });
    }
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
      
    $('#panel_paciente_titular').hide();
    $('#panel_paciente_familiar').hide();
    cambiar_tipo_beneficiario();

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

          // Revertimos el cambio antes de confirmar
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

//   function controlarBeneficiario() {
//     const tipoBeneficiario = $('#id_tipo_beneficiario').val();
//     const edad = parseInt($('#edad').val()) || 0;

//     if (!["4", "8"].includes(tipoBeneficiario)) {
//         if (edad < 18 || edad > 75) {
//             $('#panel_tutor').show();
//             $('#grupoAsignarTutor').show();
//         } else {
//             $('#panel_tutor').hide();
//             $('#grupoAsignarTutor').hide();
//             $('#fs_tutor').hide();
//             $('#asignarTutor').removeClass('active').attr('aria-pressed', 'false');
//         }
//     }else{
//         // Si es Titular o Cónyuge, ocultamos el tutor
//         $('#panel_tutor').hide();
//         $('#grupoAsignarTutor').hide();
//     }
//   }
  
    function controlarBeneficiario() {
        const tipoBeneficiario = $('#id_tipo_beneficiario').val();

        if (tipoBeneficiario === "4" || tipoBeneficiario === "8") {
            $('#panel_tutor').hide();
            $('#grupoAsignarTutor').hide();
            $('#fs_tutor').hide();
            $('#asignarTutor').removeClass('active').attr('aria-pressed', 'false');
            return; 
        }

        const edadVal = $('#edad').val();

        if (!edadVal || edadVal.trim() === '') {
            $('#panel_tutor').hide();
            $('#grupoAsignarTutor').hide();
            $('#fs_tutor').hide();
            $('#asignarTutor').removeClass('active').attr('aria-pressed', 'false');
            return;
        }

        const edad = parseInt(edadVal);

        if (edad < 18 || edad > 75) {
            mostrarPanelTutor();
        } else {
            $('#panel_tutor').hide();
            $('#grupoAsignarTutor').hide();
            $('#fs_tutor').hide();
            $('#asignarTutor').removeClass('active').attr('aria-pressed', 'false');
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
    $('#btnReg').prop('disabled', true);
    const form = $('#frm_paciente');

    if (form.find("#id_tipo_doc_ident").val() == "") {
        bootbox.alert("El Tipo de Documento del paciente no puede estar en vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#nro_doc_ident").val() == "") {
        bootbox.alert("El Documento de Identidad del paciente no puede estar en vacío.");
        $('#btnReg').prop('disabled', false);
        document.getElementById("nro_doc_ident").focus();
        return false;
    }

    if (form.find("#id_tipo_beneficiario").val() == "") {
        bootbox.alert("Debe seleccionar el Tipo de beneficiario del paciente.");
        $('#btnReg').prop('disabled', false);
        document.getElementById("id_tipo_beneficiario").focus();
        return false;
    }

    if (form.find("#nombre").val() == "") {
        bootbox.alert("El nombre del paciente no puede estar en vacío.");
        $('#btnReg').prop('disabled', false);
        document.getElementById("nombre").focus();
        return false;
    }

    if (form.find("#paterno").val() == "") {
        bootbox.alert("El apellido paterno del paciente no puede estar vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#materno").val() == "") {
        bootbox.alert("El apellido materno del paciente no puede estar vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#sexo").val() == "") {
        bootbox.alert("El sexo del paciente no puede estar vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#fecha_nac").val() == "") {
        bootbox.alert("La fecha de nacimiento del paciente no puede estar vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#id_tipo_sangre").val() == "") {
        bootbox.alert("Debe seleccionar el Tipo de Sangre del paciente.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#id_departamento").val() == "") {
        bootbox.alert("Debe seleccionar el Departamento del domicilio del paciente.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#id_provincia").val() == "") {
        bootbox.alert("Debe seleccionar la Provincia del domicilio del paciente.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#id_distrito").val() == "") {
        bootbox.alert("Debe seleccionar el Distrito del domicilio del paciente.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#domicilio").val() == "") {
        bootbox.alert("La Dirección del domicilio del paciente no puede estar vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#id_grado_instruccion").val() == "") {
        bootbox.alert("El Grado de instrucción del paciente no puede estar vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#id_ocupacion").val() == "") {
        bootbox.alert("La Ocupación del paciente no puede estar vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#id_estado_civil").val() == "") {
        bootbox.alert("El Estado civil del paciente no puede estar vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#id_religion").val() == "") {
        bootbox.alert("La Religión del paciente no puede estar vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    if (form.find("#id_etnia").val() == "") {
        bootbox.alert("La Etnia del paciente no puede estar vacío.");
        $('#btnReg').prop('disabled', false);
        return false;
    }

    const beneficiario = form.find("#id_tipo_beneficiario").val();

    if (beneficiario !== "8" && beneficiario !=="13") {

        if (form.find("#dni_titular").val() == "") {
            bootbox.alert("El Documento de Identidad del titular no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#nombre_titular").val() == "") {
            bootbox.alert("El nombre del titular no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#paterno_titular").val() == "") {
            bootbox.alert("El apellido paterno del titular no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#materno_titular").val() == "") {
            bootbox.alert("El apellido materno del titular no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#cips").val() == "") {
            bootbox.alert("El N° CIP no puede estar vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#grado_familiar").val() == "") {
            bootbox.alert("Debe seleccionar el Grado del titular.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#situacion_familiar").val() == "") {
            bootbox.alert("Debe seleccionar la Situación del titular.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

    } else if (beneficiario == "8") {

        if (form.find("#dni_titular2").val() == "") {
            bootbox.alert("El Documento de Identidad del titular no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#nombre_titular2").val() == "") {
            bootbox.alert("El nombre del titular no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#paterno_titular2").val() == "") {
            bootbox.alert("El apellido paterno del titular no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#materno_titular2").val() == "") {
            bootbox.alert("El apellido materno del titular no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#cips2").val() == "") {
            bootbox.alert("El N° CIP del Titular no puede estar vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#grado2").val() == "") {
            bootbox.alert("Debe seleccionar el Grado del titular.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if (form.find("#situacion_paciente2").val() == "") {
            bootbox.alert("Debe seleccionar la Situación del titular.");
            $('#btnReg').prop('disabled', false);
            return false;
        }
    }

    const tipo = form.find('#id_tipo_beneficiario').val();
    if (!["4", "8"].includes(tipo)) {
        if ($('#asignarTutor').prop('disabled')) {
            // continuar
        } else {
            if (form.find('#id_parentesco_tutor').val() == "") {
                bootbox.alert("Debe seleccionar el tipo de parentesco.");
                $('#btnReg').prop('disabled', false);
                return false;
            }

            if (form.find('#tipo_documento_tutor').val() == "") {
                bootbox.alert("Debe seleccionar el tipo de documento.");
                $('#btnReg').prop('disabled', false);
                return false;
            }

            if (form.find('#numero_documento_tutor').val() == "") {
                bootbox.alert("Debe ingresar el numero de documento.");
                $('#btnReg').prop('disabled', false);
                return false;
            }

            if (form.find('#nombres_tutor').val() == "") {
                bootbox.alert("Debe ingresar sus nombres del tutor.");
                $('#btnReg').prop('disabled', false);
                return false;
            }

            if (form.find('#apellidos_tutor').val() == "") {
                bootbox.alert("Debe ingresar sus apellidos del tutor.");
                $('#btnReg').prop('disabled', false);
                return false;
            }

            if (form.find('#telefono_tutor').val() == "") {
                bootbox.alert("Debe ingresar su numero de telefono.");
                $('#btnReg').prop('disabled', false);
                return false;
            }
        }
    }

    if (form.find('#nro_historia').val() == ""){
       bootbox.alert("Debe ingresar un numero de historia");
       $('#btnReg').prop('disabled', false);
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
          if (result) {
            var form = $("#frm_paciente");
            var nro_doc = form.find("#nro_doc_ident").val();

            $.ajax({
              url: '/validar-documento',
              type: 'POST',
              data: {
                nro_doc_ident: nro_doc,
                _token: $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                if (response.existe) {
                  bootbox.alert("El Documento de Identidad ingresado ya está registrado.");
                  $('#btnReg').prop('disabled', false);
                  $("#nro_doc_ident").focus();
                } else {
                  form.off("submit"); 
                  form.submit();
                }
              },
              error: function() {
                bootbox.alert("Error al validar el Documento. Intente nuevamente.");
                $('#btnReg').prop('disabled', false);
              }
            });
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

    var id_tipo_doc_ident = $("#id_tipo_doc_ident_real").val();  

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
        success: function(result) {
            //console.log("Resultado WS:", result);
            if (result && result.nompaisdelafiliado && result.nompaisdelafiliado.trim() !== '') {
                pintarCampo($('#paterno').val(result.apepatafiliado));
                pintarCampo($('#materno').val(result.apematafiliado + ' ' + result.apecasafiliado));
                pintarCampo($('#nombre').val(result.nomafiliado));
                pintarCampo($('#sexo').val(result.nomsexo));
                pintarCampo($('#fecha_nac').val(result.fecnacafiliado));

                pintarCampo($('#fecha_caducidad').val(result.caducidad != "" ? 
                    result.caducidad.substring(6, 8) + '/' + 
                    result.caducidad.substring(4, 6) + '/' + 
                    result.caducidad.substring(0, 4) : ""
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
        error: function(xhr, status) {
            bootbox.alert("En estos momentos la Webservice se encuentra en mantenimiento.");
        },
        complete: function(xhr, status) {
            $('#content').html('');
            $('#btnBuscar').prop('disabled', false);
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

$('#modalNoRegistros').on('hidden.bs.modal', function () { 
  $(this).find('form')[0].reset(); 
  $(this).find('select').val(''); 
  $(this).find('.input-detectado').removeClass('input-detectado'); 
}); 

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
                        <p><strong>Fecha creación:</strong> ${data.fecha_creacion ?? '-'}</p>
                        <p><strong>Creador:</strong> ${data.dato_creador ?? '-'}</p>
                        <p><strong>Fecha actualización:</strong> ${data.fecha_actualizacion ?? '-'}</p>
                        <p><strong>Actualizador:</strong> ${data.dato_actualizador ?? '-'}</p>
                    </div>

                    <div class="col-md-6">
                        <h4>📁 Historia Clínica - IPRESS Actual </h4>
                        <p><strong>N° Historia:</strong> ${data.nro_historia ?? '-'}</p>
                        <p><strong>Fecha creación:</strong> ${data.fecha_creacion_historia ?? '-'}</p>
                        <p><strong>Creador:</strong> ${data.dato_creador_historia ?? '-'}</p>
                        <p><strong>Fecha actualización:</strong> ${data.fecha_actualizacion_historia ?? '-'}</p>
                        <p><strong>Actualizador:</strong> ${data.dato_actualizador_historia ?? '-'}</p>
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

function exportarExcel() {
    let filtro = $('#filtroExport').val();

    window.location.href = '/exportar_asegurados_ipress/' + filtro;
}
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="panel panel-primary filterable">
      <div class="panel-heading">
        <h3 class="panel-title">Asegurados</h3>     
      </div>
      <div class="panel-body">
        <?php if ($permiso->crear == 1): ?>
        <!--<a href="/formulario_procedimiento" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo </a> -->
        <?php endif; ?>
        <br /><br />
        <div class="table-responsive">
<div class="row" style="margin-bottom:10px;">
    <div class="col-md-3">
        <select id="filtroExport" class="form-control">
            <option value="1">Último mes</option>
            <option value="2">Últimos 3 meses</option>
            <option value="0">Total (IPRESS)</option>
        </select>
    </div>

    <div class="col-md-3">
        <button class="btn btn-success" onclick="exportarExcel()">
            <i class="fa fa-file-excel-o"></i> Exportar Excel
        </button>
    </div>
</div>
          <table class="table" id="tblprocedimientos">
            <thead>
              <tr class="filters">
                <th>N</th>
                <th>DNI</th>
                <th>Apellidos y Nombres</th>
                <th>Sexo</th>
                <th style="width: 300px">F.Nac.</th>
                <th>Correo</th>
                <th class="text-center">Telefono</th>
                <th class="text-center">Domicilio</th>
                <th>Estado</th>
                @if ($permiso->modificar == 1)
                <th>Editar</th>
                @endif
                @if ($permiso->eliminar == 1)
                <th>Desactivar</th>
                @endif
                <th>Detalle</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalNoRegistros" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h3 class="panel-title">Datos del Paciente</h3>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      
      <form id="frm_paciente" class="form-horizontal" method="post" action="{{ route('registrar_paciente') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="tipo_doc_ident" name="tipo_doc_ident" value="">
        <input type="hidden" id="id_tipo_doc_ident_real" name="id_tipo_doc_ident">
        <input type="hidden" name="id_asegurado_titular" id="id_asegurado_titular" value="">

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#tab7default" aria-controls="tab7default" role="tab" data-toggle="tab">DATOS DEL PACIENTE</a></li>
            <li role="presentation"><a href="#tab8default" aria-controls="tab8default" role="tab" data-toggle="tab">HISTORIA CLINICA</a></li>
        </ul>
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane fade in active" id="tab7default">
            <div class="panel panel-info panel-bordered panel-sm" style="margin: 20px 20px">
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-3">
                    <label>Tipo Documento</label>
                    <select class="form-control input-sm" id="id_tipo_doc_ident" name="id_tipo_doc_ident" tabindex="3" disabled>
                      <option value="">SELECCIONE</option>
                      @foreach($tipo_documento_identidad as $e)
                        <option value="{{ $e->id }}">{{ $e->descripcion }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="num_doc_ident">Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                    <input id="nro_doc_ident" name="nro_doc_ident" type="text" class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
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
                            class="form-control input-sm" required="">
                            <option value="">SELECCIONE</option>
                            @foreach($tipo_beneficiario as $e)
                            <option value="{{$e->id}}">{{$e->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                      <label>Nombre &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                      <input id="nombre" name="nombre" type="text"
                          class="form-control input-sm" required=""
                          style="text-transform:uppercase;"
                          onkeyup="javascript:this.value = this.value.toUpperCase();"
                          autofocus="" onkeypress="return solo_letra(event)">
                    </div>
                    <div class="col-md-3">
                        <label>Apellido Paterno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                        <input id="paterno" name="paterno" type="text"
                            class="form-control input-sm" required=""
                            style="text-transform:uppercase;"
                            onkeyup="javascript:this.value = this.value.toUpperCase();"
                            onkeypress="return solo_letra(event)">
                    </div>
                    <div class="col-md-3">
                      <label>Apellido Materno &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                      <input id="materno" name="materno" type="text"
                          class="form-control input-sm" required=""
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
                            <option value="M">MASCULINO</option>
                            <option value="F">FEMENINO</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                      <label>Fecha Nac. &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                        <div id="fecha_nacimiento_editable">
                            <div class="input-group date fecha">
                                <input readonly="" type="text" name="fecha_nac" id="fecha_nac" class="form-control input-sm" placeholder="dd/mm/aaaa"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label>Edad</label>
                        <input type="text" id="edad" class="form-control input-sm" readonly>
                    </div>
                    <div class="col-md-2">
                        <label>Telefono</label>
                        <input id="telefono" name="telefono" type="text" placeholder="" class="form-control input-sm">
                    </div>
                    <div class="col-md-2">
                        <label>Tipo de Sangre &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                        <select id="id_tipo_sangre" name="id_tipo_sangre" class="form-control input-sm" required>
                            <option value="">SELECCIONE</option>
                            @foreach($tipo_sangre as $e)
                                <option value="{{ $e->id }}" 
                                  data-grupo="{{ $e->grupo_sanguineo }}" 
                                  data-rh="{{ $e->factor_rh }}">
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
                        <input id="correo" name="correo" type="email" placeholder=""
                            class="form-control input-sm">
                    </div>
                    <div class="col-md-6">
                        <label>Observacion</label>
                        <input id="observacion" name="observacion" type="text" placeholder=""
                            class="form-control input-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>Departamento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                          <select id="id_departamento" name="id_departamento"
                              class="form-control input-sm" required="" onchange="getProvincia();">
                              <option value="">SELECCIONE</option>
                              @foreach($departamento as $e)
                              <option value="{{$e->id_departamento}}">
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
                            <option value="{{$e->id_provincia}}">
                                {{$e->descripcion}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Distrito &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                        <select id="id_distrito" name="id_distrito"
                            class="form-control input-sm" required="">
                            <option value=""></option>
                            @if(!empty($distrito))
                                @foreach($distrito as $e)
                                    <option value="{{ $e->id_distrito }}">
                                        {{ $e->descripcion }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Domicilio &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                        <input id="domicilio" name="domicilio" type="text" placeholder="" style="text-transform:uppercase;" class="form-control input-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>Grado Instrucción <span style="font-weight: bold; color: red;">(*)</span></label>
                        <select id="id_grado_instruccion" name="id_grado_instruccion"
                            class="form-control input-sm" required="">
                            <option value="">SELECCIONE</option> 
                            @foreach($grado_inst as $e)
                            <option value="{{$e->id}}">
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
                            <option value="{{$e->id}}">
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
                            <option value="{{$e->id}}">
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
                            <option value="{{$e->id}}">
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
                                <option value="{{ $e->id }}">
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
                          <option value="1">SI</option>
                          <option value="0">NO</option>
                      </select>
                  </div>
                  <div class="col-md-2">
                      <label>Otro Seguro</label>
                      <input id="otro_seguro" name="otro_seguro" type="text" class="form-control input-sm" required="">
                  </div>
                  <div class="col-md-2">
                      <label>Discapacidad Carnet</label>
                      <select id="discapacidad_carnet" name="discapacidad_carnet" class="form-control input-sm" required>
                          <option value="">SELECCIONE</option>
                          <option value="1">SI</option>
                          <option value="0">NO</option>
                      </select>
                  </div>
                  <div class="col-md-3">
                      <label>Nro de Carnet Conadis</label>
                      <input id="nro_carnet_conadis" name="nro_carnet_conadis" type="text" class="form-control input-sm" required="" onkeypress="return IsNumber(event)">
                  </div>
                  <div class="col-md-3">
                      <label>Color de Carnet Conadis</label>
                      <select id="color_carnet_conadis" name="color_carnet_conadis" class="form-control input-sm" required>
                          <option value="">SELECCIONE</option>
                          <option value="0">NO ESPECIFICA</option>
                          <option value="1">AMARILLO</option>
                          <option value="2">CELESTE</option>
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
                                          <option value="{{ $e->id }}" {{ old('id_parentesco') == $e->id ? 'selected' : '' }}>
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
                                          <option value="{{ $tipo }}" {{ old('tipo_documento') == $tipo ? 'selected' : '' }}>
                                              {{ $tipo }}
                                          </option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="col-md-2">
                                  <label>Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                  <input id="numero_documento_tutor" name="numero_documento" type="text" 
                                      value="{{ old('numero_documento') }}"
                                      class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                              </div>
                              <div class="col-md-3">
                                  <label>Nombres &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                  <input id="nombres_tutor" name="nombres" type="text" value="{{ old('nombres') }}"
                                  style="text-transform:uppercase;" class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                  autofocus="" onkeypress="return solo_letra(event)">
                              </div>
                              <div class="col-md-3">
                                  <label>Apellidos &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                  <input id="apellidos_tutor" name="apellidos" type="text" value="{{ old('apellidos') }}"
                                  style="text-transform:uppercase;" class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                  autofocus="" onkeypress="return solo_letra(event)">
                              </div>
                              <div class="col-md-2">
                                  <label>Teléfono &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                  <input id="telefono_tutor" name="telefono_tutor" type="text" value="{{ old('telefono_tutor') }}"
                                  style="text-transform:uppercase;" class="form-control input-sm" onkeypress="return IsNumber(event)">
                              </div>
                              <div class="col-md-4">
                                  <label>Domicilio</label>
                                  <input id="direccion_tutor" name="direccion" type="text" value="{{ old('direccion') }}"
                                  style="text-transform:uppercase;" class="form-control input-sm">
                              </div>
                              <div class="col-md-6">
                                  <label>Observación</label>
                                  <input id="observacion_tutor" name="observacion_tutor" type="text"
                                  class="form-control input-sm">
                              </div>
                          </div>
                      </div>
                  </div>
              </fieldset>
            </div>
            <!-- TITULARES -->
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
                            <input id="dni_titular2" name="dni_titular2" type="text"
                                  class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                        </div>
                        <div class="col-md-3">
                            <label>Nombre Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                            <input id="nombre_titular2" name="nombre_titular2" type="text"
                                  class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                  autofocus="" onkeypress="return solo_letra(event)">
                        </div>
                        <div class="col-md-3">
                            <label>Paterno Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                            <input id="paterno_titular2" name="paterno_titular2" type="text"
                                  class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                  autofocus="" onkeypress="return solo_letra(event)">
                        </div>
                        <div class="col-md-3">
                            <label>Materno Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                            <input id="materno_titular2" name="materno_titular2" type="text"
                                  class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                  autofocus="" onkeypress="return solo_letra(event)">
                        </div>
                        <div class="col-md-3">
                            <label>N° Carnet &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                            <input id="cips2" name="cips2" type="text" placeholder=""
                                    class="form-control input-sm" onkeypress="return IsNumber(event)">
                        </div>
                        <div class="col-md-3">
                            <label>Grado &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                            <select id="grado2" name="grado2" class="form-control input-sm">
                                <option value="">SELECCIONE</option>
                                @foreach($grados_lista as $e)
                                    @if(!empty($e->grado))
                                        <option value="{{ $e->grado }}">
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
                                        <option value="{{ $e->situacion_paciente }}">
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
                                  value="DNI" type="text" class="form-control input-sm" readonly>
                          </div>
                          <div class="col-md-3">
                              <label>Documento &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                              <input id="dni_titular" name="dni_titular" type="text"
                                    class="form-control input-sm" maxlength="8" pattern="[0-9]*" onkeypress="return IsNumber(event)">
                          </div>
                          <div class="col-md-3">
                              <label>Nombre Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                              <input id="nombre_titular" name="nombre_titular" type="text"
                                    class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                    autofocus="" onkeypress="return solo_letra(event)">
                          </div>
                          <div class="col-md-3">
                              <label>Paterno Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                              <input id="paterno_titular" name="paterno_titular" type="text"
                                    class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                    autofocus="" onkeypress="return solo_letra(event)">
                          </div>
                          <div class="col-md-3">
                              <label>Materno Titular &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                              <input id="materno_titular" name="materno_titular" type="text"
                                    class="form-control input-sm" onkeyup="javascript:this.value = this.value.toUpperCase();"
                                    autofocus="" onkeypress="return solo_letra(event)">
                          </div>
                          <div class="col-md-3">
                            <label>N° Carnet &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                            <input id="cips" name="cips" type="text" 
                                  class="form-control input-sm" onkeypress="return IsNumber(event)">
                          </div>
                          <div class="col-md-3">
                              <label>Grado &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                              <select id="grado_familiar" name="grado" class="form-control input-sm">
                                  <option value="">SELECCIONE</option>
                                  @foreach($grados_lista as $e)
                                      @if(!empty($e->grado))
                                          <option value="{{ $e->grado }}">
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
                                              <option value="{{ $e->situacion_paciente }}">
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
          </div>
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
                      <a href="/all_asegurados" class="form-control btn btn-danger">Cancelar</a>
                  </div>
                  <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                      <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()">Guardar</button>
                  </div>
              </div>
          </div>
        </div>
      </form>
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
@include('frontend.includes.footer')
@endsection