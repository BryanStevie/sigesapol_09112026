@extends('frontend.layouts.master')
@section('renderjs')
<style>
  .radio-group-error {
    border: 2px solid #ff6161;
    padding: 5px;
    border-radius: 5px;
  }
  .input-error {
    border: 2px solid #ff6161 !important;
    box-shadow: 0 0 5px #f20000;
  }
  #periodoEspecificoMsg,
  #periodoEspecificoCampos,
  #condicionPsico {
    display: none;
  }
  #tbldpersonals tbody td {
    vertical-align: middle !important;
  }
  .col-bloque {
    padding-left: 10px !important;
    padding-right: 10px !important;
    height: 500px;
  }
  .box {
        border: 2px solid #a9cf8a;
        padding: 20px;
        margin-bottom: 15px;
        height: 500px;
    }
  .modal-title-left {
        text-align: left;
        margin-left: 10px;
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

  #detalleModal .modal-dialog {
    width: 95%;
    max-width: 1200px;
  }

  .text-verde-oscuro {
    color: #00592D;
    font-weight: bold;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 14px; 
}
</style>

<script>
  $(document).ready(function () {
    $('#slcEstado').change(function () {
      var valor = $(this).val();

      $('#periodoEspecificoMsg').hide();
      $('#periodoEspecificoCampos').hide();
      $('#condicionPsico').hide();

      if (valor !== '') {
        if (valor == '2' || valor == '3' || valor == '4' || valor == '5' || valor == '6') {
          $('#periodoEspecificoMsg').show();
          $('#periodoEspecificoCampos').show();
        }
        if (valor == '7') {
          $('#periodoEspecificoMsg').show();
          $('#periodoEspecificoCampos').show();
          $('#condicionPsico').show();
        }
        if (valor == '8' || valor == '9' || valor == '10') {
          $('#periodoEspecificoMsg').hide();
          $('#periodoEspecificoCampos').hide();
          $('#condicionPsico').hide();
        }
      }
    });
  });

  $(document).ready(function () {
      var tblPersonals = $('#tbldpersonals').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": {
              "url": "/listar_medicos_by_ipress_saenz",
              "type": "POST",
              "dataType": "json",
              "data": function (d) { 
                d._token = "{{csrf_token()}}";
                d.id_establecimiento = $("#id_establecimiento").val();
                d.dni_beneficiario = $("#dni_beneficiario").val();
                d.nombre_beneficiario = $("#nombre_beneficiario").val();
              }
          },
          "deferLoading": 0,
          "columnDefs": [
            { "orderable": false, "targets": [1,2] }
          ],
          "pageLength": 10,
          "lengthChange": false,
          "dom": 'Bfrtip',
          "filter":false,
          "columns": [
              { "data": "tipo_documento_nro_doc", "className": "text-left", "name": "Tipo y N de Doc" },
              { "data": "apellidos_nombres", "className": "text-left", "name":"Apellidos y Nombres"},
              { 
                "data": null,
                "className": "text-left",
                "name": "Profesión",
                "render": function (data, type, row) {
                    return row.profesion + '<br /> CMP: ' + row.colegiatura;
                }
              },
              { 
                "data": null,
                "className": "text-left",
                "name": "Especialidad",
                "render": function (data, type, row) {
                    return row.especializacion + '<br /> RNE: ' + row.rne;
                }
              },
              {
                "data": "descripcion_estado", "name":"Estado", "className": "text-left" 
              },
              {
                "data": null,
                "className": "text-left",
                "name": "Motivo Estado",
                "render": function (data, type, row) {
                    return row.motivo_estado;
                }
              },
              {
                "data": null,
                "name": "Acciones",
                "className": "text-left",
                "render": function(data, type, row, meta) {
                  let html = '';

                  @if ($permiso->lectura == 1)
                  html += `<a class='btn btn-success btn-xs detalle-btn' style="margin-right:8px;" data-id='${row['id']}' href='/detalle_medico/${row['id']}/{{ Auth::user()->id_farmacia }}'><i class='fa fa-eye' title="ver detalle"></i></a>`;
                  @endif

                  @if ($permiso->modificar == 1)
                  if (row['estado'] == 1) {
                      html += `<a class='btn btn-warning btn-xs' style="margin-right:8px;" href='/editar_medico_saenz/${row['id']}/{{ Auth::user()->id_farmacia }}'><i class='fa fa-edit' title="editar"></i></a>`;
                  } else {
                      html += `<button type="button" class="btn btn-default btn-xs" style="margin-right:8px;"><i class="fa fa-edit" title="active para editar"></i></button>`;
                  }
                  @endif

                  @if ($permiso->eliminar == 1)
                  html += row['estado'] == 1
                      ? `<a class="btn btn-xs" style="margin-right:8px;" href="javascript:void(0)" onclick="abrirModalDesactivar(${row['id']},${row['estado']})"><img src="{{ asset('images/boton_encender.png') }}" style="width:45px; height:45px;" title="desactivar estado"></a>`
                      : `<a class="btn btn-xs" style="margin-right:8px;" href="javascript:void(0)" onclick="abrirModalDesactivar(${row['id']},${row['estado']})"><img src="{{ asset('images/boton_apagar.png') }}" style="width:45px; height:45px;" title="activar estado"></a>`;
                  @endif

                  @if ($permiso->lectura == 1)
                  html += `
                    <a href="javascript:void(0)"
                      class="btn btn-success btn-xs descargar-detalle-btn"
                      data-id="${row['id']}"
                      style="margin-right:8px;">
                      <i class="glyphicon glyphicon-download-alt" title="descargar"></i>
                    </a>`;
                  @endif

                  return html;
                }
              }
          ]
      }); 

      $('#btnBuscar').on('click', function() {
        tblPersonals.ajax.reload(null, true);
      });
      $('#dni_beneficiario, #nombre_beneficiario').on('keypress', function(e) {
        if (e.which === 13) { 
          $('#btnBuscar').click();
        }
      });

      let currentUrl = "/listar_medicos_by_ipress";
      tblPersonals.on('search.dt', function () {
        var searchValue = tblPersonals.search().trim();
        if (searchValue === "") {
          if (currentUrl !== "/listar_medicos_by_ipress") {
              currentUrl = "/listar_medicos_by_ipress";
              tblPersonals.ajax.url(currentUrl).load();
          }
        } else {
          if (currentUrl !== "/listar_medicos_ajax_general") {
              currentUrl = "/listar_medicos_ajax_general";
              tblPersonals.ajax.url(currentUrl).load();
          }
        }
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
            
      var $input =  $(this),
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

  $(document).on('click', '.descargar-detalle-btn', function () {
    let id = $(this).data('id');
    $('#detalle_id').val(id);
    $('#modalDocumentos').modal('show');
  });

  $('#modalDocumentos').on('click', '.btn-primary', function() {
    let detalleId = $('#detalle_id').val();
    let documentoSeleccionado = $('input[name="documento"]:checked').val();
    
    if (!documentoSeleccionado) {
      alert('Por favor seleccione un tipo de documento');
      return;
    }
    
    if (documentoSeleccionado == '1') {
      let url = '/descargar_detalle_medico/' + detalleId + '/{{ Auth::user()->id_farmacia }}';
      window.open(url, '_blank');
      $('#modalDocumentos').modal('hide');
    } else if (documentoSeleccionado == '2') {
      let url = '/descargar_constancia_trabajo/' + detalleId + '/{{ Auth::user()->id_farmacia }}';
      window.open(url, '_blank');
      $('#modalDocumentos').modal('hide');
    }
  });

  $('#modalDocumentos').on('click', '.btn-secondary', function() {
    let detalleId = $('#detalle_id').val();
    let documentoSeleccionado = $('input[name="documento"]:checked').val();
    
    if (!documentoSeleccionado) {
      alert('Por favor seleccione un tipo de documento');
      return;
    }

    alert('Funcionalidad de imprimir pendiente');
  });

  $('#modalDocumentos').on('hidden.bs.modal', function () {
    $('input[name="documento"]').prop('checked', false);
    $('#detalle_id').val('');
  });

  function abrirModalDesactivar (id, estado) {
    $('#btnCambiarEstado').attr('data-id', id);
    $('#btnCambiarEstado').attr('data-estado', estado);
    
    $.ajax({
        url: '/obtener_estados_historial',
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            estado: estado,
            _token: '{{ csrf_token() }}'
        },
        success: function(data) {

            var select = $('#slcEstado');
            select.empty();
            select.append('<option value="">Seleccionar un estado</option>');
            
            $.each(data, function(index, item) {
                select.append(
                    '<option value="'+item.id+'">'+item.descripcion+'</option>'
                );
            });
        }
    });

    $('#estadoModal').modal('show');
  }

  $(document).on('change input',
    '#periodoRadios input[type="radio"], ' +
    '#periodoEspecificoCampos input, ' +
    '#periodoEspecificoCampos select, ' +
    '#condicionPsico input, ' +
    '#condicionPsico select, ' +
    '#slcEstado',
    function () {
        if ($(this).val()) {
            $(this).removeClass('input-error');
        }

        if ($(this).attr('type') === 'radio') {
            var group = $(this).closest('.radio-group');
            if (group.find('input[type="radio"]:checked').length > 0) {
                group.removeClass('radio-group-error');
            }
        }
    }
  );

  function validateEstadoFrm() {
    var esValido = true;

    $('.input-error').removeClass('input-error');
    $('.radio-group-error').removeClass('radio-group-error');

    if (!$('#slcEstado').val()) {
        $('#slcEstado').addClass('input-error');
        esValido = false;
    }

    if ($('#periodoEspecificoCampos').is(':visible')) {

      $('#periodoEspecificoCampos input[type="text"], ' + '#periodoEspecificoCampos select, ' + '#periodoEspecificoCampos textarea').each(function () {
          
        if ($(this).is(':disabled')) {
          return;
        }

        if (
          $(this).attr('id') === 'numero_periodo' &&
          $('#idIndeterminado').is(':checked')
        ) {
          return;
        }

        if (!$(this).val()) {
          $(this).addClass('input-error');
          esValido = false;
        }
      });

      if ($('#periodoRadios input[type="radio"]:checked').length === 0) {
        $('#periodoRadios').addClass('radio-group-error');
        esValido = false;
      }

    }

    if ($('#condicionPsico').is(':visible')) {

        $('#condicionPsico input[type="text"], ' +
          '#condicionPsico select, ' +
          '#condicionPsico textarea').each(function () {

            if (!$(this).val()) {
                $(this).addClass('input-error');
                esValido = false;
            }
        });

        $('#condicionPsico .radio-group').each(function () {
            if ($(this).find('input[type="radio"]:checked').length === 0) {
                $(this).addClass('radio-group-error');
                esValido = false;
            }
        });
    }

    return esValido;
  }

  $('#motivo-desactivar').on('input change', function () {
    if ($(this).val().trim() !== '') {
      $(this).removeClass('input-error');
    }
  });


  function guardarEstado () {
    var esValidoEstado = validateEstadoFrm();
    if (!esValidoEstado) {
      return;
    }
    else {
      let motivo = $('#motivo-desactivar').val().trim();
      let id_estado_historial = $('#slcEstado').val();
      let total_periodo = $('#numero_periodo').is(':disabled') ? null : $('#numero_periodo').val();
      total_periodo = total_periodo === '' ? null : total_periodo;
      let periodo = $('input[name="periodo"]:checked').val() ?? null;
      let codigo_condicion = $('#condicionPsico select').val();
      codigo_condicion = codigo_condicion === '' ? null : codigo_condicion;

      let id = $('#btnCambiarEstado').data('id');
      let estado = $('#btnCambiarEstado').data('estado');

      if(estado == 0){       

        if(!motivo){
          $('#motivo-desactivar').addClass('input-error');
          return;
        }   

        $('#campo-motivo-desactivar').val(motivo);
        
        $.ajax({
          url: '/desactivar_medico_saenz/'+id+'/'+estado,
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            motivo: 'activar: ' + motivo,
            id_estado_historial: id_estado_historial
          },
          success: function(result){
            location.reload();
          }
        });
      } else {  

        if(!motivo){
          $('#motivo-desactivar').addClass('input-error');
          return;
        }

        $('#campo-motivo-desactivar').val(motivo);     

        $.ajax({
          url: '/desactivar_medico_saenz/'+id+'/'+estado,
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            motivo: 'desactivar: ' + motivo,
            id_estado_historial: id_estado_historial,
            total_periodo: total_periodo,
            periodo: periodo,
            codigo_condicion: codigo_condicion
          },
          success: function(result){
            location.reload();
          }
        });
      }
    }
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

  function normalizar(v) {
    return v === null || v === undefined ? '' : v;
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

          const profesion = valorTexto(data.profesion);
          const filtroProfesion = profesion.substring(0, 3);
          const esAuxiliar = (filtroProfesion === "AUX" || filtroProfesion === "AX." || 
                             filtroProfesion === "CHO" || filtroProfesion === "TÉC" || 
                             filtroProfesion === "OTR" || filtroProfesion === "POL");

          $('#modal-condicion').text(condicionTexto[data.condicion] || '-');
          $('#modal-grado').text(valorTexto(data.gradoDescr));
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
          $('#modal-correo-institucional').text(valorTexto(data.email_institucional));
          $('#model-domicilio').text(valorTexto(data.domicilio));
          $('#dep-prov-dist').text([data.departamento, data.provincia, data.distrito].filter(Boolean).join(' / ') || '-');

          $('#modal-grupo-trabajo').text(grupoTrabajo[data.grupo_trabajo] || '-');
          $('#modal-fecha-iniciofin-laboral').text(convertirFecha(data.fecha_inicio) + ' | ' + convertirFecha(data.fecha_cese));
          $('#modal-situacion-actual').text(valorTexto(data.situacionPersonal));
          $('#modal-condicion-laboral').text(valorTexto(data.condicionLaboral));
          $('#modal-profesion').text(profesion);
          
          if (esAuxiliar) {
              $('#modal-especializacion').text('-');
              $('#modal-col-profesional').text('-');
              $('#modal-ncmp-nrne').text('- / -');
              $('#modal-estado-colegiatura').text('-');
              $('#modal-iniciofin-habilidad').text('- | -');
          } else {
              $('#modal-especializacion').text(valorTexto(data.especializacion));
              $('#modal-col-profesional').text(valorTexto(data.colegioProfesional));
              $('#modal-ncmp-nrne').text(valorTexto(data.colegiatura) + ' / ' + valorTexto(data.rne));
              $('#modal-estado-colegiatura').text(estadoColegiatura[data.estado_colegiatura] || '-');
              $('#modal-iniciofin-habilidad').text(convertirFecha(data.fecha_inicio_habilidad) + ' | ' + convertirFecha(data.fecha_termino_habilidad));
          }

          var $tabla = $('#tabla-profesiones');
          $tabla.empty();

          if (data.profesiones && data.profesiones.length > 0) {

            var profesionesFiltradas = data.profesiones.filter(function(p) {
              return !(
                normalizar(p.profesion)    === normalizar(data.profesion) &&
                normalizar(p.especialidad) === normalizar(data.especializacion) &&
                normalizar(p.colegiatura)  === normalizar(data.colegiatura) &&
                normalizar(p.rne)          === normalizar(data.rne)
              );
            });

            if (profesionesFiltradas.length > 0) {
              profesionesFiltradas.forEach(function(p) {
                var fila = '<tr>' +
                            '<td>' + valorTexto(p.profesion) + '</td>' +
                            '<td>' + valorTexto(p.especialidad) + '</td>' +
                            '<td>' + valorTexto(p.colegiatura) + '</td>' +
                            '<td>' + valorTexto(p.rne) + '</td>' +
                          '</tr>';
                $tabla.append(fila);
              });
            } else {
              $tabla.append('<tr><td colspan="4" class="text-center">No hay profesiones extra</td></tr>');
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
        },
        error: function() {
          alert('Error al obtener los detalles del médico.');
        }
      });
  });

  document.addEventListener('DOMContentLoaded', () => {
  const inputs = document.querySelectorAll('.solo-numeros');

  inputs.forEach(input => {
    input.addEventListener('keydown', e => {
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

function abrirModalReporte() {
  $('#reporteModal').modal('show');
}

$('#reporteModal').on('hidden.bs.modal', function () {
  $(this).find('input[type="checkbox"]').prop('checked', false);
  $('#campos_reporte').val('');
});

$('#btnDeseleccionarTodo').on('click', function () {
  $('#reporteModal').find('input[type="checkbox"]').prop('checked', false);
});

$('#btnSeleccionarTodo').on('click', function () {
  $('#reporteModal').find('input[type="checkbox"]').prop('checked', true);
});

function generarReporte() {
    var campos = [];

    $("input[name='campos[]']:checked").each(function() {
        campos.push($(this).val());
    });

    if (campos.length === 0) {
        alert("Seleccione al menos un campo");
        return;
    }

    $("#campos_reporte").val(JSON.stringify(campos));

    document.getElementById("frmDescargarReporte").submit();
}

$('#estadoModal').on('hidden.bs.modal', function () {

  $('#slcEstado').val('');

  $(this).find('input[type="text"]').val('');

  $(this).find('input[type="radio"]').prop('checked', false);

  $(this).find('select').prop('selectedIndex', 0);

  $('#periodoEspecificoCampos').hide();
  $('#periodoEspecificoMsg').hide();
  $('#condicionPsico').hide();

  $(this).find('.input-error').removeClass('input-error');
  $(this).find('.radio-group-error').removeClass('radio-group-error');

  $('#btnCambiarEstado')
    .data('id', '')
    .data('estado', '');

});

$('input[name="periodo"]').on('change', function () {

  if ($('#idIndeterminado').is(':checked')) {
    $('#numero_periodo')
      .val('')
      .prop('disabled', true)
      .removeClass('input-error');
  } else {
    $('#numero_periodo').prop('disabled', false);
  }

});

</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="panel panel-primary filterable">
      <div class="panel-heading">
        <h3 class="panel-title">Médicos</h3>
      </div>
      <div class="panel-body">
        
        <form class="form-horizontal" method="post" action="{{ route('listar_medicos_by_ipress')}}" name="frm_listar_medicos_by_ipress" >
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">

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
              </div>
            </div>
            <div class="col-sm-6 text-right" style="margin-top:1.2em;">
              <a id="btnBuscar" class="btn btn-success">BUSCAR</a>
              
              <?php if ($permiso->crear == 1): ?>
                  <a href="/buscar_medico" class="btn btn-warning"><i class="fa fa-plus"></i> NUEVO </a>
              <?php endif; ?> 
              
              <a href="#" class="btn btn-success" id="btn_exportar_repo" name="btn_exportar_repo" onclick="abrirModalReporte(); return false;">
                  <i class="fa fa-file-excel-o" aria-hidden="true"></i> REPORTE
              </a>
            </div>
          </div>
        </form>

        <div class="box-body">  
          <div class="table-responsive">
            <input type="hidden" id="id_establecimiento" name="id_establecimiento" value="{{Auth::user()->id_establecimiento}}">
            <table class="table" id="tbldpersonals">
              <thead>
                <tr class="filters">
                  <th style="width:150px">Tipo y Nº de Doc</th>
                  <th>Apellidos y Nombres</th>
                  <th>Profesión</th>
                  <th>Especialidad</th>
                  <th>Estado</th>
                  <th>Motivo Estado</th>
                  @if ($permiso->lectura == 1 || $permiso->modificar == 1 || $permiso->eliminar == 1 || $permiso->lectura == 1)
                  <th>Acciones</th>
                  @endif
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
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
                      <td><b>CORREO ELECTRONICO</b></td>
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="reporteModal" tabindex="-1" role="dialog" aria-labelledby="reporteModalLabel">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="reporteModalLabel" style="text-align: left;">GENERADOR DE REPORTE</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="margin-top:15px;">
          <p class="text-center" style="color:red"><b>Debe seleccionar los items para generar sus reportes:</b></p>
          <div class="col-md-6 col-bloque">
            <div class="box">
              <p class="text-center" style="color:#1B5E20; font-weight:bold; margin-top:0;">Información Personal</p>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="policial"> POLICIAL</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="civil"> CIVIL</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="gradodescr"> GRADO</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="cip"> N° DE CIP</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="tipodoc"> TIPO Y N° DE DOC</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="nombre_completo"> APELLIDOS Y NOMBRES</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="fecha_nacimiento"> FECHA DE NACIMIENTO</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="edad"> EDAD</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="estadocivil"> ESTADO CIVIL</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="nro_hijos"> N° HIJOS</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="sexo"> SEXO</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="celular"> N° DE CEL</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="email"> CORREO PERSONAL</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="email_institucional"> CORREO INSTITUCIONAL</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="departamento"> DEPARTAMENTO</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="provincia"> PROVINCIA</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="distrito"> DISTRITO</label></div>
            </div>
          </div>
          <div class="col-md-6 col-bloque">
            <div class="box">
              <p class="text-center" style="color:#1B5E20; font-weight:bold; margin-top:0;">Información Laboral</p>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="asistencial"> ASISTENCIAL</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="administrativo"> ADMINISTRATIVO</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="fecha_inicio"> F. INICIO DE LABORES</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="fecha_cese"> F. CESE DE LABORES</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="situacionpersonal"> SITUACION ACTUAL</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="condicionlaboral"> CONDICION LABORAL</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="profesion"> PROFESIÓN</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="especializacion"> ESPECIALIDAD</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="colegioprofesional"> COLEGIO PROFESIONAL</label></div>
              <div class="checkbox">
                <label><input type="checkbox" name="campos[]" value="colegiatura"> N° CMP</label>
                &nbsp;&nbsp;
                <label><input type="checkbox" name="campos[]" value="rne"> N° RNE</label>
              </div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="estado_colegiatura"> ESTADO DE COLEGIATURA</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="fecha_inicio_habilidad"> F. INICIO DE HABILIDAD</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="fecha_termino_habilidad"> F. FIN DE HABILIDAD</label></div>
              <div class="checkbox"><label><input type="checkbox" name="campos[]" value="ipress"> ESTABLECIMIENTO</label></div>
              <div class="checkbox">
                <label><input type="checkbox" name="campos[]" value="unidad"> UNIDAD</label>
                &nbsp;&nbsp;
                <div class="checkbox"><label><input type="checkbox" name="campos[]" value="subunidad"> SUB UNIDAD</label></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row" style="margin-top:15px; margin-bottom:10px; text-align:center;">
        <button type="button" class="btn btn-success" id="btnSeleccionarTodo">Seleccionar todo</button>
        &nbsp;
        <button type="button" class="btn btn-default" id="btnDeseleccionarTodo">Limpiar</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGenerarReporte" onclick="generarReporte()">Generar Reporte</button>
      </div>

      <form id="frmDescargarReporte" action="/reporte-excel" method="POST" target="_blank">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="campos" id="campos_reporte">
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="estadoModal" tabindex="-1" role="dialog" aria-labelledby="desactivarModalLabel">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="reporteModalLabel" style="text-align: left;">ACTUALIZACION DE ESTADO</h4>
      </div>

      <div class="modal-body">
        <p class="text-center" style="color:red"><b>¡RECUERDE! que al actualizar el estado el usuario prodia verse afectado</b></p>
        <div class="row">
          <div class="col-md-12 text-center" style="margin-top:5px;">
            <select id="slcEstado" class="form-control" style="width:80%; display:inline-block;">
              <option value="">Seleccionar un estado</option>
            </select>
          </div>
        </div>
        <div class="row" id="periodoEspecificoMsg" style="margin-top:15px;">
          <div class="col-md-12">
            <p class="text-left" style="color:red">(*)Registre los dias o periodos de: lo que esta en amarillo</p>
          </div>
        </div>
        <div class="row" id="periodoEspecificoCampos">
          <div class="col-md-5">
            <input type="text" name="numero_periodo" id="numero_periodo" class="form-control input-sm solo-numeros">
          </div>
          <div class="col-md-6 radio-group" id="periodoRadios">
            <input type="radio" name="periodo" id="idDias" value="1"><label for="idDias" style="margin-left: 5px">DIAS</label>
            <input type="radio" name="periodo" id="idMeses" value="2" style="margin-left: 10px"><label for="idMeses" style="margin-left: 5px">MESES</label>
            <input type="radio" name="periodo" id="idIndeterminado" value="0" style="margin-left: 10px"><label for="idIndeterminado" style="margin-left: 5px">INDETERMINADO</label>
          </div>
        </div>
        <div class="row" id="condicionPsico" style="margin-top:15px;">
          <div class="col-md-4">
            <b>Condición: APTO</b>
          </div>
          <div class="col-md-6">
            <b>Código:</b>
            <select name="" id="" style="margin-left: 15px;">
              <option value="">Seleccionar</option>
              <option value="1">1</option>
              <option value="2">2</option>
            </select>
          </div>
          <div class="col-md-2">

          </div>
        </div>
        <p style="color:red; margin-top:15px;">(*)Registre el motivo:</p>
        <input type="text" name="motivo-desactivar" id="motivo-desactivar" style="width:100%">
      </div>
      <div class="modal-footer" style="margin-top:15px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnCambiarEstado" data-id="" data-estado="" onclick="guardarEstado()">Actualizar Estado</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDocumentos" tabindex="-1" role="dialog" aria-labelledby="modalDocumentos">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modalDocumentosLabel" style="text-align: left;">FORMATOS ESPECIALES</h4>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-12">
            <input class="form-check-input" type="radio" name="documento" id="fichaPersonal" value="1">
            <label class="form-check-label" for="fichaPersonal">
              FICHA INFORMACIÓN PERSONAL
            </label>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <input class="form-check-input" type="radio" name="documento" id="constanciaTrabajo" value="2">
            <label class="form-check-label" for="constanciaTrabajo">
              CONSTANCIA DE TRABAJO
            </label>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="margin-top:15px;">
        <button type="button" class="btn btn-primary">
          <i class="glyphicon glyphicon-print"></i> PDF / IMPRIMIR
        </button>
        {{-- <button type="button" class="btn btn-secondary">
          <i class="glyphicon glyphicon-print"></i> IMPRIMIR
        </button> --}}
      </div>
      <input type="hidden" id="detalle_id">
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection