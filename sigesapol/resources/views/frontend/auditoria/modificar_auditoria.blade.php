@extends('frontend.layouts.master2')
@section('renderjs')
<style>
.fila-eliminada {
    text-decoration: line-through;
    background-color: #ffe6e6;
}
.select2-container--default .select2-selection--single {
    white-space: normal;
    height: auto;
  }

  .select2-container--default .select2-results__option {
    white-space: normal;
  }

  .select2-dropdown {
    width: auto !important;
    max-width: 962px;
  }

  .select2-container {
    width: 100% !important;
  }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Incluir jQuery -->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script>
  var diagnosticos_insertar = {!! isset($diagnosticos_insertar) ? json_encode($diagnosticos_insertar) : '[]' !!};
  var procedimientos_insertar = {!! isset($procedimientos_insertar) ? json_encode($procedimientos_insertar) : '[]' !!};
  var productos_insertar = {!! isset($productos_insertar) ? json_encode($productos_insertar) : '[]' !!};

</script>
<script>
$(document).ready(function() {

$('#btnAgregarDiagnostico').click(function() {
  if (!Array.isArray(diagnosticos_insertar)) {
    console.error("diagnosticos_insertar no está definido o no es un array");
    return;
  }

  var opciones = '<option value="">-- Seleccione --</option>';
  diagnosticos_insertar.forEach(function(d) {
    opciones += `<option value="${d.codigo}" data-nombre="${d.nombre}">${d.codigo} - ${d.nombre}</option>`;
  });

  var nuevaFila = `
    <tr class="fila-diagnostico nueva" style="margin-top:5px;">
      <td>
        <select name="codigo_cie10[]" class="form-control select-codigo-cie10">
          ${opciones}
        </select>
      </td>
      <td><input type="text" name="nombre_cie10[]" class="form-control input-nombre-cie10" placeholder="Nombre diagnóstico" readonly></td>
      <td style="text-align: center;">
        <select class="form-control" name="tipo_diagnostico[]">
          <option value="1">PRESUNTIVO</option>
          <option value="2">DEFINITIVO</option>
          <option value="3">REITERATIVO</option>
        </select>
      </td>
      <td style="text-align: center;">
        <select class="form-control" name="tipo_atencion[]">
          <option value="1">ATENCION</option>
          <option value="2">EVALUACION</option>
        </select>
      </td>
      <td style="text-align: center;">
        <select class="form-control" name="id_identificador_diag[]">
          <option value="E">EGRESO</option>
          <option value="I">INGRESO</option>
        </select>
      </td>
      <td>
        <button type="button" class="btn btn-danger deleteFilaDiagnostico btn-xs" style="margin-left:4px">
          <i class="fa fa-times"></i> Eliminar
        </button>
      </td>
      <input type="hidden" name="eliminado_diag[]" value="0" class="flag-eliminado-diag">
    </tr>
  `;

  $('#tblDiagnostico_anteriores tbody').append(nuevaFila);

  var $nuevoSelect = $('#tblDiagnostico_anteriores tbody tr:last .select-codigo-cie10');
  $nuevoSelect.select2({
    width: '100%',
    templateSelection: function (data) {
      return data.id || ''; // Solo mostrar el código cuando se ha seleccionado
    }
  });
});

$(document).on('click', '.deleteFilaDiagnostico', function() {
  var fila = $(this).closest('tr');
  if (fila.hasClass('nueva')) {
    fila.remove();
  } else {
    var inputFlag = fila.find('.flag-eliminado-diag');
    if (inputFlag.val() === '0') {
      inputFlag.val('1');
      fila.addClass('fila-eliminada');
    } else {
      inputFlag.val('0');
      fila.removeClass('fila-eliminada');
    }
  }
});

$(document).on('change', '.select-codigo-cie10', function() {
  var nombre = $(this).find(':selected').data('nombre');
  var fila = $(this).closest('tr');
  fila.find('.input-nombre-cie10').val(nombre || '');
});

$('#btnGuardarDiagnosticos').click(function() {
  var formData = {
    id_trama: [],
    renipress: [],
    num_lote: [],
    num_correlativo: [],
    id_trama_diagnostico: [],
    codigo_cie10: [],
    nombre_cie10: [],
    tipo_diagnostico: [],
    tipo_atencion: [],
    id_identificador_diag: [],
    eliminado_diag: []
  };

  $('#tblDiagnostico_anteriores tbody tr').each(function() {
    var fila = $(this);
    var id_trama = fila.find('[name="id_trama[]"]').val() || '0';
    var renipress = fila.find('[name="renipress[]"]').val() || '0';
    var num_lote = fila.find('[name="num_lote[]"]').val() || '0';
    var num_correlativo = fila.find('[name="num_correlativo[]"]').val() || '0';
    var id_trama_diagnostico = fila.find('[name="id_trama_diagnostico[]"]').val()|| '0';
    var codigo = fila.find('[name="codigo_cie10[]"]').val() || fila.find('[name="codigo_cie10[]"]').attr('value');
    var nombre = fila.find('[name="nombre_cie10[]"]').val();
    var tipo_diag = fila.find('[name="tipo_diagnostico[]"]').val();
    var tipo_atencion = fila.find('[name="tipo_atencion[]"]').val();
    var identificador = fila.find('[name="id_identificador_diag[]"]').val();
    var eliminado = fila.find('[name="eliminado_diag[]"]').val();

    formData.id_trama.push(id_trama);
    formData.renipress.push(renipress);
    formData.num_lote.push(num_lote);
    formData.num_correlativo.push(num_correlativo);
    formData.id_trama_diagnostico.push(id_trama_diagnostico);
    formData.codigo_cie10.push(codigo);
    formData.nombre_cie10.push(nombre);
    formData.tipo_diagnostico.push(tipo_diag);
    formData.tipo_atencion.push(tipo_atencion);
    formData.id_identificador_diag.push(identificador);
    formData.eliminado_diag.push(eliminado);
  });

  $.ajax({
  url: '/guardar_diagnostico_auditoria',
  method: 'POST',
  contentType: 'application/json',
  data: JSON.stringify({
    _token: $('meta[name="csrf-token"]').attr('content'),
    data: formData
  }),
  success: function (response) {
    Swal.fire('Guardado', 'Los diagnosticos fueron guardados correctamente.', 'success')
      .then(() => {
        location.reload();
      });
  },
  error: function () {
    Swal.fire('Error', 'Ocurrió un error al guardar los diagnosticos.', 'error');
  }
});
});

const diagnosticos = <?php echo json_encode($diagnosticos_prestacion); ?>;
const procedimientos = <?php echo json_encode($procedimientos_insertar); ?>;

if (!Array.isArray(procedimientos_insertar)) {
  console.error("procedimientos_insertar no está definido o no es un array");
  return;
}

$('#btnAgregarProcedimiento').click(function () {
  let diagnosticoOptions = '<option value="">-- Seleccione --</option>';
  diagnosticos.forEach(function (d) {
    diagnosticoOptions += `<option value="${d.codigo_cie10}">${d.codigo_cie10}</option>`;
  });

  let procedimientoOptions = '<option value="">-- Seleccione --</option>';
  procedimientos.forEach(function (p) {
    procedimientoOptions += `<option value="${p.codigo}" data-nombre="${p.descripcion}" data-precio="${p.t_nivel3}" data-tipo="${p.tipo_procedimiento}">
      ${p.codigo} - ${p.descripcion}
    </option>`;
  });

  let nuevaFila = $(`
    <tr class="fila-procedimiento nueva">
      <td style="text-align: center;">
        <select class="form-control select-procedimiento" style="width: 100%">
          ${procedimientoOptions}
        </select>
      </td>
      <td>
        <input type="text" name="nombre_procedimiento[]" class="form-control nombre-procedimiento" readonly>
      </td>
      <td style="text-align: center;">
        <input type="number" name="cantidad_indicada[]" class="form-control" min="1" value="1">
      </td>
      <td style="text-align: center;">
        <select name="diagnostico_relacionado[]" class="form-control">
          ${diagnosticoOptions}
        </select>
      </td>
      <td style="text-align: center;" class="precio-procedimiento">-</td>
      <td style="text-align: center;" class="tipo-procedimiento">-</td>
      <td>
        <button type="button" class="btn btn-danger deleteFilaProcedimiento btn-xs" style="margin-left:4px">
          <i class="fa fa-times"></i> Eliminar
        </button>
      </td>
      <input type="hidden" name="eliminado_proc[]" value="0" class="flag-eliminado-proc">
      <input type="hidden" name="id_procedimiento[]" value="0">
    </tr>
  `);

  $('#tblProcedimiento tbody').append(nuevaFila);

  // Inicializar select2 con formato personalizado
  nuevaFila.find('.select-procedimiento').select2({
    placeholder: "Buscar código...",
    width: 'resolve',
    templateResult: function (data) {
      if (!data.id) return data.text;
      const option = $(data.element);
      const codigo = option.val();
      const nombre = option.data('nombre') || '';
      return `${codigo} - ${nombre}`;
    },
    templateSelection: function (data) {
      return data.id || data.text; // Mostrar solo el código al seleccionar
    }
  });

  nuevaFila.find('.select-procedimiento').trigger('change');
});

$('#tblProcedimiento tbody').on('change', '.select-procedimiento', function () {
  const selected = $(this).find('option:selected');
  const nombre = selected.data('nombre') || '';
  const tipo = selected.data('tipo') || '';
  const precio = selected.data('precio') || '';

  let tipoTexto = '';
  switch (String(tipo)) {
    case '1': tipoTexto = 'MÉDICO'; break;
    case '2': tipoTexto = 'LABORATORIO'; break;
    case '3': tipoTexto = 'IMAGENOLOGÍA'; break;
    default: tipoTexto = '';
  }

  const fila = $(this).closest('tr');
  fila.find('.nombre-procedimiento').val(nombre);
  fila.find('.precio-procedimiento').text(precio);
  fila.find('.tipo-procedimiento').text(tipoTexto);
});

$('#btnGuardarProcedimientos').click(function () {
  var formData = {
    id_trama: [],
    id_procedimiento: [],
    renipress: [],
    num_lote: [],
    numero_correlativo: [],
    eliminado_proc: [],
    codigo_procedimiento: [],
    cantidad_indicada: [],
    diagnostico_relacionado: [],
    precio_procedimiento: [],
    tipo_procedimiento: []
    
  };

  // Recorrer solo la tabla tblProcedimiento
  $('#tblProcedimiento tbody tr').each(function () {
    var fila = $(this);
    var id_trama = fila.find('[name="id_trama[]"]').val() || '0';
    var id = fila.find('[name="id_procedimiento[]"]').val() || '0';
    var eliminado = fila.find('[name="eliminado_proc[]"]').val() || '0';
    var numero_correlativo = fila.find('[name="numero_correlativo[]"]').val() || '0';
    var num_lote = fila.find('[name="num_lote[]"]').val() || '0';
    var renipress = fila.find('[name="renipress[]"]').val() || '0';
    var codigo = fila.find('.select-procedimiento').val() || '';
    var cantidad = fila.find('[name="cantidad_indicada[]"]').val() || '1';
    var diagnostico = fila.find('[name="diagnostico_relacionado[]"]').val() || '';
    var precio = fila.find('.precio-procedimiento').text().trim() || '0';
    var tipo = fila.find('.tipo-procedimiento').text().trim() || '';

    formData.id_procedimiento.push(id)
    formData.id_trama.push(id_trama);
    formData.numero_correlativo.push(numero_correlativo);
    formData.num_lote.push(num_lote);
    formData.renipress.push(renipress);
    formData.eliminado_proc.push(eliminado);
    formData.codigo_procedimiento.push(codigo);

    formData.cantidad_indicada.push(cantidad);
    formData.diagnostico_relacionado.push(diagnostico);
    formData.precio_procedimiento.push(precio);
    formData.tipo_procedimiento.push(tipo);
  });

  $.ajax({
    url: '/guardar_procedimiento_auditoria',
    method: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      data: formData
    },
    success: function (response) {
      Swal.fire('Guardado', 'Los procedimientos fueron guardados correctamente.', 'success')
        .then(() => {
          location.reload();
        });
    },
    error: function (xhr) {
      Swal.fire('Error', 'Ocurrió un problema al guardar los procedimientos.', 'error');
    }
  });
});

$(document).on('click', '.deleteFilaProcedimiento', function () {
  const row = $(this).closest('tr');
  const flagInput = row.find('.flag-eliminado-proc');

  if (row.hasClass('nueva')) {
    row.remove(); // si es nueva, simplemente se borra
  } else {
    if (flagInput.val() === '0') {
      flagInput.val('1');
      row.addClass('fila-eliminada');
    } else {
      flagInput.val('0');
      row.removeClass('fila-eliminada');
    }
  }
});

const productos_disponibles = <?php echo json_encode($productos_insertar); ?>;
const diagnosticos_productos = <?php echo json_encode($diagnosticos_prestacion); ?>;
 
$('#btnAgregarProducto').click(function () {
  let cie10Options = '<option value="">-- Seleccione --</option>';
  diagnosticos_productos.forEach(function (d) {
    cie10Options += `<option value="${d.codigo_cie10}">${d.codigo_cie10}</option>`;
  });

  let productoOptions = '<option value="">-- Seleccione --</option>';
  productos_disponibles.forEach(function (p) {
productoOptions += `<option value="${p.codigo}" data-id="${p.codigo}" data-nombre="${p.nombre}" data-precio_trama="${p.precio_trama}">
  ${p.codigo} - ${p.nombre}
    </option>`;
  });

  const id_trama = $('#id_trama').val() || '0';
  const renipress = $('#codigo_renipress').val() || '';
  const num_lote = $('#numero_lote').val() || '';
  const num_correlativo = $('#numero_correlativo').val() || '';

  let nuevaFila = $(`
    <tr class="fila-producto nueva">
      <td>
        <select class="form-control select-producto" style="width: 100%">
          ${productoOptions}
        </select>
      </td>
      <td><input type="text" name="nombre[]" class="form-control nombre-producto" readonly></td>
      <td><input type="number" name="cantidad_prescrita[]" class="form-control" min="1" value="1"></td>
      <td><input type="number" name="cantidad_entregada[]" class="form-control" min="1" value="1"></td>
      <td class="precio-producto" style="text-align: center;">-</td>
      <td class="valorizacion-producto" style="text-align: center;">-</td>
      <td>
        <select name="id_cie10[]" class="form-control select-cie10">
          ${cie10Options}
        </select>
      </td>
      <td>
        <button type="button" class="btn btn-danger deleteFilaProducto btn-xs">
          <i class="fa fa-times"></i> Eliminar
        </button>
      </td>

      <!-- Hidden inputs -->
      <td style="display:none;">
        <input type="hidden" name="id_prod_med[]" value="0">
        <input type="hidden" name="codigo_cie10[]" class="codigo_cie10_input" value="">
        <input type="hidden" name="id_trama[]" value="${id_trama}">
        <input type="hidden" name="renipress[]" value="${renipress}">
        <input type="hidden" name="num_lote[]" value="${num_lote}">
        <input type="hidden" name="num_correlativo[]" value="${num_correlativo}">
        <input type="hidden" name="id_trama_diagnostico[]" value="">
        <input type="hidden" name="eliminado_prod[]" value="0" class="flag-eliminado">
      </td>
    </tr>
  `);

  $('#tblProductos tbody').append(nuevaFila);

  nuevaFila.find('.select-producto').select2({
    placeholder: "Buscar código...",
    width: 'resolve',
    templateResult: function (data) {
      if (!data.id) return data.text;
      const option = $(data.element);
      const codigo = option.val();
      const nombre = option.data('nombre') || '';
      return `${codigo} - ${nombre}`;
    },
    templateSelection: function (data) {
      return data.id || data.text;
    }
  });

  // Al seleccionar producto
  nuevaFila.find('.select-producto').on('change', function () {
    const selected = $(this).find('option:selected');
    const nombre = selected.data('nombre') || '';
    const precio = parseFloat(selected.data('precio_trama')) || 0;
    const id_prod_med = selected.data('id') || '0';
    const fila = $(this).closest('tr');

    fila.find('.nombre-producto').val(nombre);
    fila.find('.precio-producto').text(precio.toFixed(2));

    const cant = parseFloat(fila.find('[name="cantidad_entregada[]"]').val() || 1);
    fila.find('.valorizacion-producto').text((cant * precio).toFixed(2));

    
  fila.find('[name="id_prod_med[]"]').val(id_prod_med);
  });

  // Recalcular valorización al cambiar cantidad
  nuevaFila.find('[name="cantidad_entregada[]"]').on('input', function () {
    const fila = $(this).closest('tr');
    const precio = parseFloat(fila.find('.precio-producto').text()) || 0;
    const cantidad = parseFloat($(this).val()) || 1;
    fila.find('.valorizacion-producto').text((cantidad * precio).toFixed(2));
  });

  // Sincronizar select id_cie10 con input oculto
  nuevaFila.find('.select-cie10').on('change', function () {
    const fila = $(this).closest('tr');
    const codigoSeleccionado = $(this).val();
    fila.find('.codigo_cie10_input').val(codigoSeleccionado);
  });
});

// Eliminar fila
$(document).on('click', '.deleteFilaProducto', function () {
  const fila = $(this).closest('tr');
  if (fila.hasClass('nueva')) {
    fila.remove();
  } else {
    const flagInput = fila.find('.flag-eliminado');
    if (flagInput.val() === '0') {
      flagInput.val('1');
      fila.addClass('fila-eliminada');
    } else {
      flagInput.val('0');
      fila.removeClass('fila-eliminada');
    }
  }
});

// Guardar productos
$('#btnGuardarProductos').click(function () {
  var formData = {
    id_producto: [],
    id_prod_med: [],
    nombre: [],
    cantidad_prescrita: [],
    cantidad_entregada: [],
    precio: [],
    valorizacion: [],
    id_cie10: [],
    codigo_cie10: [],
    eliminado: [],
    id_trama: [],
    renipress: [],
    num_lote: [],
    num_correlativo: []
  };

  $('#tblProductos tbody tr').each(function () {
    var fila = $(this);
    const precio = parseFloat(fila.find('.precio-producto').text()) || 0;
    const cantidad_entregada = parseFloat(fila.find('[name="cantidad_entregada[]"]').val()) || 1;

    formData.id_producto.push(fila.find('[name="id_trama_diagnostico[]"]').val() || '0');
    formData.id_prod_med.push(fila.find('[name="id_prod_med[]"]').val() || '0');

    formData.cantidad_prescrita.push(fila.find('[name="cantidad_prescrita[]"]').val() || '0');
    formData.cantidad_entregada.push(cantidad_entregada);
    formData.precio.push(precio.toFixed(2));
    formData.valorizacion.push((cantidad_entregada * precio).toFixed(2));
    formData.id_cie10.push(fila.find('[name="id_cie10[]"]').val() || '');
    formData.codigo_cie10.push(fila.find('[name="codigo_cie10[]"]').val() || '');
    formData.eliminado.push(fila.find('[name="eliminado_prod[]"]').val() || '0');
    formData.id_trama.push(fila.find('[name="id_trama[]"]').val() || '0');
    formData.renipress.push(fila.find('[name="renipress[]"]').val() || '');
    formData.num_lote.push(fila.find('[name="num_lote[]"]').val() || '');
    formData.num_correlativo.push(fila.find('[name="num_correlativo[]"]').val() || '');
  });

  $.ajax({
    url: '/guardar_producto_auditoria',
    method: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      data: formData
    },
    success: function (response) {
      Swal.fire('Guardado', 'Los productos fueron guardados correctamente.', 'success')
        .then(() => {
          location.reload();
        });
    },
    error: function () {
      Swal.fire('Error', 'Ocurrió un error al guardar los productos.', 'error');
    }
  });
});


});
</script>

@endsection
@section('content')

<div class="loader"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-md-12">
      <div class="panel with-nav-tabs panel-default">
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active" id="tab2default">
              <fieldset>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                      <div class="row">
                        <div class="panel panel-primary filterable">
                          <div class="panel-heading">
                            <h3 class="panel-title">EDITAR PRESTACION</h3>
                          </div>
                          <div class="panel-body">
                            <form class="form-horizontal" method="post" action=" " id="frmPrestacion"
                              autocomplete="off">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user"> 
                              <input type="hidden" value="{{ $prestacion->id_prestacion }}" name="id_prestacion" id="id_prestacion">                      
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>TRAMA PRESTACION</b>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="panel-body" id="collapseprestacionespaciente">
                                        <div class="form-group">
                                          <div class="row">
                                            <div class="col-md-2">
                                              <label>Codigo Renipress</label>
                                              <input type="text" class="form-control" style="text-align:left" name="codigo_renipress" id="codigo_renipress" value="{{ $prestacion->codigo_renipress }}">
                                              </span>
                                              </div>
                                              <div class="col-md-2">
                                                <label>Numero Lote</label>
                                                <input type="text" class="form-control" style="text-align:left" name="numero_lote" id="numero_lote" value="{{ $prestacion->numero_lote }}">
                                                </div>
                                            <div class="col-md-2">
                                              <label>Numero Correlativo</label>
                                              <input type="text" class="form-control" style="text-align:left" name="numero_correlativo" id="numero_correlativo" value="{{ $prestacion->numero_correlativo }}">    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Procedencia Prestacion</label>  
                                              <input type="text" class="form-control" style="text-align:left" name="procedencia_prestacion" id="procedencia_prestacion" value="{{ $prestacion->procedencia_prestacion }}">  
                                            </div>
                                            <div class="col-md-2">
                                              <label>IPRESS Referencia</label>
                                              <input type="text" class="form-control" style="text-align:left" name="ipress_referencia_fups" id="ipress_referencia_fups" value="{{ $prestacion->ipress_referencia_fups }}">    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Hoja Referencia FUPS</label> 
                                              <input type="text" class="form-control" style="text-align:left" name="hoja_referencia_fups" id="hoja_referencia_fups" value="{{ $prestacion->hoja_referencia_fups }}">  
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">                                          
                                            <div class="col-md-2">
                                              <label>Tipo Documento Identidad Pac.</label> 
                                                <select class="form-control" name="tipo_doc_identidad" id="tipo_doc_identidad" style="text-align:left">
                                                    <option value="1" {{ $prestacion->tipo_doc_identidad == 1 ? 'selected' : '' }}>1 - DNI</option>
                                                    <option value="2" {{ $prestacion->tipo_doc_identidad == 2 ? 'selected' : '' }}>2 - Carnet de Extranjeria</option>
                                                    <option value="3" {{ $prestacion->tipo_doc_identidad == 3 ? 'selected' : '' }}>3 - Pasaporte</option>
                                                    <option value="4" {{ $prestacion->tipo_doc_identidad == 4 ? 'selected' : '' }}>4 - Documento de Identidad Extranjero</option>
                                                    <option value="5" {{ $prestacion->tipo_doc_identidad == 5 ? 'selected' : '' }}>5 - Codigo Unico de Identificacion(CUI)</option>
                                                    <option value="6" {{ $prestacion->tipo_doc_identidad == 6 ? 'selected' : '' }}>6 - Codigo Nacido Vivo(CNV)</option>
                                                    <option value="7" {{ $prestacion->tipo_doc_identidad == 7 ? 'selected' : '' }}>7 - Sin Documento de Identidad</option>
                                                    <option value="8" {{ $prestacion->tipo_doc_identidad == 8 ? 'selected' : '' }}>8 - Permiso Temporal de Permanencia PTP</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Documento Identidad Pac.</label>                                                
                                              <input type="text" class="form-control" style="text-align:left" name="paciente_nro_doc_identidad" id="paciente_nro_doc_identidad" value="{{ $prestacion->paciente_nro_doc_identidad }}"> 
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Historia Clinica Pac.</label>
                                             <input type="text" class="form-control" style="text-align:left" name="id_hist_clinica" id="id_hist_clinica" value="{{ $prestacion->id_hist_clinica }}">  
                                            </div>
                                            <div class="col-md-2">
                                              <label>Apellido Paterno Paciente</label>                                            
                                              <input type="text" class="form-control" style="text-align:left" name="paciente_apellido_paterno" id="paciente_apellido_paterno" value="{{ $prestacion->paciente_apellido_paterno }}">   
                                            </div>
                                            <div class="col-md-2">
                                              <label>Apellido Materno Paciente</label>                                             
                                              <input type="text" class="form-control" style="text-align:left" name="paciente_apellido_materno" id="paciente_apellido_materno" value="{{ $prestacion->paciente_apellido_materno }}">     
                                            </div>
                                            <div class="col-md-2">
                                              <label>Nombres Paciente</label>                                             
                                              <input type="text" class="form-control" style="text-align:left" name="paciente_nombres" id="paciente_nombres" value="{{ $prestacion->paciente_nombres }}">    
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                            <div class="col-md-2">
                                              <label>Tipo Cobertura</label>
                                              <select class="form-control" name="id_tipo_cobert" id="id_tipo_cobert" style="text-align:left">
                                                    <option value="6" {{ $prestacion->id_tipo_cobert == 6 ? 'selected' : '' }}>6 - Emergencia</option>
                                                    <option value="5" {{ $prestacion->id_tipo_cobert == 5 ? 'selected' : '' }}>5 - Hospitalización</option>
                                                    <option value="4" {{ $prestacion->id_tipo_cobert == 4 ? 'selected' : '' }}>4 - Consulta Externa</option>
                                              </select>
                                            </div>
                                            <div class="col-md-4">                                            
                                                <div class = "col-md-4">  
                                                  <label>UPSS Fups</label>   <br>                                                                                     
                                                  <input type="text" class="form-control" style="text-align:left" name="id_upss_fups" id="id_upss_fups" value="{{$prestacion->id_upss_fups}}">                                                    
                                                </div>
                                                <div class = "col-md-8">
                                                  <label> <br>  </label> <br>                                                                                                 
                                                  <span class="form-control" style="text-align:left" name="id_upss_fups_nombre" id="id_upss_fups_nombre"><?php echo $nombre_upss ?>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Atencion Fups</label>                                             
                                              <input type="text" class="form-control" style="text-align:left" name="fecha_atencion_fups" id="fecha_atencion_fups" value="{{ $prestacion->fecha_atencion_fups }}">   
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Ingreso Fups</label>                                              
                                              <input type="text" class="form-control" style="text-align:left" name="fecha_ingreso_fups" id="fecha_ingreso_fups" value="{{ $prestacion->fecha_ingreso_fups }}">   
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Inicio Administrativo Fups</label>                                            
                                              <input type="text" class="form-control" style="text-align:left" name="fecha_inicio_adm" id="fecha_inicio_adm" value="{{ $prestacion->fecha_inicio_adm }}">   
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                            <div class="col-md-2">
                                              <label>Fecha Cierre Administrativo Fups</label>
                                              <input type="text" class="form-control" style="text-align:left" name="fecha_cierre_adm_fups" id="fecha_cierre_adm_fups" value="{{ $prestacion->fecha_cierre_adm_fups }}">                                                 
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Egreso Fups</label>
                                              <input type="text" class="form-control" style="text-align:left" name="fecha_egreso_fups" id="fecha_egreso_fups" value="{{ $prestacion->fecha_egreso_fups }}">                                                   
                                            </div>
                                            <div class="col-md-2">
                                              <label>Renipress Vincula Fups</label>
                                              <input type="text" class="form-control" style="text-align:left" name="renipress_vincula_fups" id="renipress_vincula_fups" value="{{ $prestacion->renipress_vincula_fups }}">                                                  
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Lote Vincula Fups</label>
                                              <input type="text" class="form-control" style="text-align:left" name="num_lote_vincula_fups" id="num_lote_vincula_fups" value="{{ $prestacion->num_lote_vincula_fups }}">                                                
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Vincula Fups</label>
                                              <input type="text" class="form-control" style="text-align:left" name="num_vincula_fups" id="num_vincula_fups" value="{{ $prestacion->num_vincula_fups }}">                                                 
                                            </div>
                                            <div class="col-md-2">
                                              <label>Estado Paciente Mujer Fups</label>
                                              <input type="text" class="form-control" style="text-align:left" name="fecha_inicio_adm" id="fecha_inicio_adm" value="{{ $prestacion->fecha_inicio_adm }}">                                                 
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                          <div class="col-md-2">
                                              <label>Fecha Probable Parto Fups</label>                                              
                                              <input type="text" class="form-control" style="text-align:left" name="fecha_probable_parto_fups" id="fecha_probable_parto_fups" value="{{ $prestacion->fecha_probable_parto_fups }}">  
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Parto Fups</label>                                                 
                                              <input type="text" class="form-control" style="text-align:left" name="fecha_parto_fups" id="fecha_parto_fups" value="{{ $prestacion->fecha_parto_fups }}">
                                            </div>
                                            <div class="col-md-2">
                                              <label>Destino Asegurado Fups</label>                                                 
                                              <input type="text" class="form-control" style="text-align:left" name="id_destino_aseg_fups" id="id_destino_aseg_fups" value="{{ $prestacion->id_destino_aseg_fups }}"> 
                                            </div>
                                            <div class="col-md-2">
                                              <label>IPRESS Destino Fups</label>                                              
                                              <input type="text" class="form-control" style="text-align:left" name="ipress_destino_fups" id="ipress_destino_fups" value="{{ $prestacion->ipress_destino_fups }}">
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Hoja Destino Fups</label>                                               
                                              <input type="text" class="form-control" style="text-align:left" name="num_hoja_destino_fups" id="num_hoja_destino_fups" value="{{ $prestacion->num_hoja_destino_fups }}">   
                                            </div>
                                            <div class="col-md-2">
                                            <label>Fecha Fallecimiento</label>                                              
                                            <input type="text" class="form-control" style="text-align:left" name="fecha_fallece" id="fecha_fallece" value="{{ $prestacion->fecha_fallece }}">
                                          </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Numero Acreditacion Siteds</label>
                                            <input type="text" class="form-control" style="text-align:left" name="numero_acreditacion_siteds" id="numero_acreditacion_siteds" value="{{ $prestacion->numero_acreditacion_siteds }}"> 
                                          </div>
                                          <div class="col-md-2">
                                            <label>Tipo Documento Identidad Med.</label>                                            
                                            <select class="form-control" name="id_tipodoc_ident" id="id_tipodoc_ident" style="text-align:left">
                                                <option value="1" {{ $prestacion->id_tipodoc_ident == 1 ? 'selected' : '' }}>1 - DNI</option>
                                                <option value="2" {{ $prestacion->id_tipodoc_ident == 2 ? 'selected' : '' }}>2 - Carnet de Extranjeria</option>
                                                <option value="3" {{ $prestacion->id_tipodoc_ident == 3 ? 'selected' : '' }}>3 - Pasaporte</option>
                                                <option value="4" {{ $prestacion->id_tipodoc_ident == 4 ? 'selected' : '' }}>4 - Documento de Identidad Extranjero</option>
                                                <option value="5" {{ $prestacion->id_tipodoc_ident == 5 ? 'selected' : '' }}>5 - Codigo Unico de Identificacion(CUI)</option>
                                                <option value="6" {{ $prestacion->id_tipodoc_ident == 6 ? 'selected' : '' }}>6 - Codigo Nacido Vivo(CNV)</option>
                                                <option value="7" {{ $prestacion->id_tipodoc_ident == 7 ? 'selected' : '' }}>7 - Sin Documento de Identidad</option>
                                                <option value="8" {{ $prestacion->id_tipodoc_ident == 8 ? 'selected' : '' }}>8 - Permiso Temporal de Permanencia PTP</option>
                                            </select>   
                                          </div>
                                          <div class="col-md-2">
                                            <label>Numero Documento Identidad Med.</label>                                          
                                            <input type="text" class="form-control" style="text-align:left" name="numdoc_ident" id="numdoc_ident" value="{{ $prestacion->numdoc_ident }}">  
                                          </div>
                                          <div class="col-md-2">
                                            <label>Apellido Paterno Medico</label>                                            
                                            <input type="text" class="form-control" style="text-align:left" name="doc_ape_paterno" id="doc_ape_paterno" value="{{ $prestacion->doc_ape_paterno }}">   
                                          </div>
                                          <div class="col-md-2">
                                            <label>Apellido Materno Medico</label>                                            
                                            <input type="text" class="form-control" style="text-align:left" name="doc_ape_materno" id="doc_ape_materno" value="{{ $prestacion->doc_ape_materno }}">   
                                          </div>
                                          <div class="col-md-2">
                                            <label>Nombres Medico</label>                                            
                                            <input type="text" class="form-control" style="text-align:left" name="doc_nombres" id="doc_nombres" value="{{ $prestacion->doc_nombres }}">  
                                          </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Tipo Profesional</label>                                               
                                            <input type="text" class="form-control" style="text-align:left" name="id_tipo_prof" id="id_tipo_prof" value="{{ $prestacion->id_tipo_prof }}">   
                                          </div>
                                          <div class="col-md-2">
                                            <label>Numero Colegiatura</label>                                             
                                            <input type="text" class="form-control" style="text-align:left" name="num_coleg" id="num_coleg" value="{{ $prestacion->num_coleg }}">    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Especialidad Profecional</label>                                        
                                            <input type="text" class="form-control" style="text-align:left" name="id_especialidad_prof" id="id_especialidad_prof" value="{{ $prestacion->id_especialidad_prof }}">     
                                          </div>
                                          <div class="col-md-2">
                                            <label>Numero RNE</label>                                            
                                            <input type="text" class="form-control" style="text-align:left" name="num_rne" id="num_rne" value="{{ $prestacion->num_rne }}">   
                                          </div>
                                          <div class="col-md-2">
                                            <label>RUC IPRESS</label>                                             
                                            <input type="text" class="form-control" style="text-align:left" name="ruc_ipress" id="ruc_ipress" value="{{ $prestacion->ruc_ipress }}">     
                                          </div>
                                          <div class="col-md-2">
                                            <label>Tipo Documento Pago</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">                                                
                                          </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                          <div class="col-md-3">
                                            <label>Codigo CUPS</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo "<b>".$nombre_cups. "</b>"?>    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Estado</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $nombre_estado?>    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Estado del Asegurado</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo "<b>".$estado_asegurado. "</b>"?>    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Situacion del Asegurado</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $estado_situacion?>    
                                          </div>
                                          <div class="col-md-3">
                                            <label>Fecha de Caducidad del Seguro</label>
                                            <span class="form-control" style=" background-color: lightgreen;" name="consultorio" id="consultorio"><?php echo $dia_caducidad. '-' .$mes_caducidad. '-'.$año_caducidad?>    
                                          </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                          <div class="col-md-3">
                                            <label>CODIGO EMPAQUETADO</label>
                                            <span class="form-control" style=" background-color: lightgreen;" name="consultorio" id="consultorio"><?php echo $prestacion->observaciones?>
                                            </div>
                                          </div>
                                      </div>
                                      
                                      <!--a href="/ficha_auditoria_consulta_externa/{{ $prestacion->id }}" class="btn btn-primary pull-right">Ficha Auditoria</a>
                                      <a href="/ficha_auditoria_emergencia/{{ $prestacion->id }}" class="btn btn-primary pull-right">Ficha Auditoria</a>
                                      <a href="/ficha_auditoria_hospitalizacion/{{ $prestacion->id }}" class="btn btn-primary pull-right">Ficha Auditoria</a-->
                                      @if ($prestacion->id_tipo_cobert == 6)
    <a href="/ficha_auditoria_emergencia/{{ $prestacion->id }}" class="btn btn-primary pull-right">Ficha Auditoria Emergencia</a>
@elseif ($prestacion->id_tipo_cobert == 4)
    <a href="/ficha_auditoria_consulta_externa/{{ $prestacion->id }}" class="btn btn-primary pull-right">Ficha Auditoria Consulta Externa</a>
@elseif ($prestacion->id_tipo_cobert == 5)
    <a href="/ficha_auditoria_hospitalizacion/{{ $prestacion->id }}" class="btn btn-primary pull-right">Ficha Auditoria Hospitalizacion</a>
@endif
                                      <a target="_blank" href="/hc_electronica/{{ $detalle_prestacion->id_asegurado }}" class="btn btn-primary pull-right" id="btnPrestacion">Ver H.C.</a>
                                </fieldset>
                                <br>
                                <!------------CIE10---------------->
                                <fieldset>
  <div class="panel panel-info panel-bordered panel-sm">
    <div class="panel-heading">
      <div class="panel-title text-center">
        <b>DIAGNOSTICOS DE ATENCION (CIE10)</b>
      </div>
    </div>
    <div class="panel-body">               

      <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" >
        <thead>
          <tr>
            <th width="10%">Codigo</th>
            <th width="55%">Nombre</th>
            <th width="10%">Tipo</th>
            <th width="10%">Clasificación</th>
            <th width="15%" style="text-align: center;">Ingreso/Egreso</th>
            <th width="5%" style="text-align: center;">Eliminar</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($diagnosticos_prestacion as $index => $row): ?>
            <?php
                $resaltar = false; 
                if (!empty($cod_diag)) {
                    foreach ($cod_diag as $dato) {
                        if ($dato == $row->codigo_cie10) {
                            $resaltar = true; 
                            break; 
                        }
                    }
                }
            ?>
            <tr class="fila-diagnostico existente" style="margin-top:5px; <?= $resaltar ? 'background-color: yellow;' : '' ?>">
                <td><?php echo $row->codigo_cie10 ?></td>
                <td><?php echo $row->nombre_cie10 ?></td>

                <td style="text-align: center;">
                    <select class="form-control" name="tipo_diagnostico[]" style="text-align:left">
                        <option value="1" <?= $row->tipo_diagnostico == 1 ? 'selected' : '' ?>>PRESUNTIVO</option>
                        <option value="2" <?= $row->tipo_diagnostico == 2 ? 'selected' : '' ?>>DEFINITIVO</option>
                        <option value="3" <?= $row->tipo_diagnostico == 3 ? 'selected' : '' ?>>REITERATIVO</option>
                    </select>
                </td>

                <td style="text-align: center;">
                    <select class="form-control" name="tipo_atencion[]" style="text-align:left">
                        <option value="1" <?= $row->tipo_atencion == 1 ? 'selected' : '' ?>>ATENCION</option>
                        <option value="2" <?= $row->tipo_atencion == 2 ? 'selected' : '' ?>>EVALUACION</option>
                    </select>
                </td>

                <td style="text-align: center;">
                    <select class="form-control" name="id_identificador_diag[]" style="text-align:left">
                        <option value="E" <?= $row->id_identificador_diag == 'E' ? 'selected' : '' ?>>EGRESO</option>
                        <option value="I" <?= $row->id_identificador_diag == 'I' ? 'selected' : '' ?>>INGRESO</option>
                    </select>
                </td>

                <td>
                    <button type="button" class="btn btn-danger deleteFilaDiagnostico btn-xs" style="margin-left:4px">
                        <i class="fa fa-times"></i> Eliminar
                    </button>
                </td>
                <input type="hidden" name="eliminado_diag[]" value="0" class="flag-eliminado-diag">
                <input type="hidden" name="codigo_cie10[]" value="<?= $row->codigo_cie10 ?>">
                <input type="hidden" name="id_trama_diagnostico[]" value="<?= $row->id ?>">
                <input type="hidden" name="id_trama[]" value="<?= $prestacion->id ?>">
                <input type="hidden" name="renipress[]" value="<?= $prestacion->codigo_renipress ?>">
                <input type="hidden" name="num_lote[]" value="<?= $prestacion->numero_lote ?>">
                <input type="hidden" name="num_correlativo[]" value="<?= $prestacion->numero_correlativo ?>">
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <button type="button" id="btnAgregarDiagnostico" class="btn btn-success btn-sm">
        <i class="fa fa-plus"></i> Agregar Diagnóstico
      </button>
      <button type="button" id="btnGuardarDiagnosticos" class="btn btn-primary btn-sm">
    <i class="fa fa-save"></i> Guardar Diagnósticos
  </button>               
    </div>
  </div>
</fieldset>
<!--------CPMS--------->
                             
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                   <div class="panel-heading">
                                   <div class="panel-title text-center" style="position: relative">
                                  <b>PROCEDIMIENTOS MEDICOS (CPMS)</b>
                                  <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0;display:none;">Editar</button>
                                </div>
                              </div>
                              <div class="panel-body">      
                              <table class="display" id="tblProcedimiento" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                        <th width="10%" style="text-align: center;">Codigo</th>
                                        <th width="50%" style="text-align: center;">Nombre</th>
                                        <th width="8%" style="text-align: center;">Cantidad</th>
                                        <th width="8%" style="text-align: center;">Diagnostico</th>
                                        <th width="8%" style="text-align: center;">Precio</th>
                                        <th width="8%" style="text-align: center;">Tipo Proc</th>
                                        <th width="8%" style="text-align: center;">Eliminar</th>

                                        </tr>
                                    </thead>
                                    <tbody>
<?php foreach($procedimientos_trama as $index => $row): ?>
<tr class="fila-procedimiento" data-id="<?= $row->id; ?>">
    <td style="text-align: center;"><?php echo $row->codigo_procedimiento ?></td>
    <td><?php echo $row->nombre_procedimiento ?></td>
    <td style="text-align: center;">
  <input type="number" name="cantidad_indicada[]" class="form-control" value="<?php echo $row->cantidad_indicada ?>" min="1">
</td>

    <td style="text-align: center;">
        <select name="diagnostico_relacionado[]" class="form-control">
            <?php foreach ($diagnosticos_prestacion as $diagnostico): ?>
                <option value="<?= $diagnostico->codigo_cie10 ?>"
                    <?= $diagnostico->codigo_cie10 === $row->diagnostico_relacionado ? 'selected' : '' ?>>
                    <?= $diagnostico->codigo_cie10 ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>

    <td style="text-align: center;"><?php echo $row->precio_procedimiento ?></td>

    <td style="text-align: center;
    <?php
        if ($row->tipo_procedimiento == 1) {
            echo 'background-color: #00b100; color: white;'; 
        } elseif ($row->tipo_procedimiento == 2) {
            echo 'background-color: #FFD700;'; 
        } elseif ($row->tipo_procedimiento == 3) {
            echo 'background-color: #D8BFD8;'; 
        }
    ?>
">
        <?php
        if ($row->tipo_procedimiento == 1) {
            echo 'Medico';
        } elseif ($row->tipo_procedimiento == 2) {
            echo 'Laboratorio';
        } elseif ($row->tipo_procedimiento == 3) {
            echo 'Imagenes';
        } else {
            echo '';
        }
        ?>
    </td>

    <td>
        <button type="button" class="btn btn-danger deleteFilaProcedimiento btn-xs" style="margin-left:4px">
            <i class="fa fa-times"></i> Eliminar
        </button>
    </td>
                
                <input type="hidden" name="id_trama[]" value="<?= $prestacion->id ?>">
                <input type="hidden" name="renipress[]" value="<?= $prestacion->codigo_renipress ?>">
                <input type="hidden" name="num_lote[]" value="<?= $prestacion->numero_lote ?>">
                <input type="hidden" name="num_correlativo[]" value="<?= $prestacion->numero_correlativo ?>">
                <input type="hidden" name="eliminado_proc[]" value="0" class="flag-eliminado-proc">
                <input type="hidden" name="id_procedimiento[]" value="<?= $row->id ?>">
</tr>
<?php endforeach; ?>
</tbody>
                            </table>   
                            <button type="button" id="btnAgregarProcedimiento" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Agregar Procedimiento
                            </button> 
                            <button type="button" id="btnGuardarProcedimientos" class="btn btn-primary btn-sm" >
                                <i class="fa fa-save"></i> Guardar Procedimientos
                            </button>                      
                              </div>
                              
                            </div>
                          </fieldset>
                          
                          <!--------------------petitorio------------>
                          <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center" >
                                      <b>PRODUCTOS FARMACEUTICOS Y DISPOSITIVOS MEDICOS (PETITORIO)</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">            
                                  <table class="display" id="tblProductos" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th width="10%">Codigo </th>
      <th width="50%">Nombre</th>
      <th width="6%">Cantidad Prescrita</th>
      <th width="6%">Cantidad Entregada</th>
      <th width="6%">Precio</th>
      <th width="7%">Valorizacion</th>
      <th width="10%">CIE10 </th>
      <th width="5%" style="text-align: center;">Eliminar</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($productos_trama as $row): ?>
    <tr>
      <td><?php echo $row->id_prod_med ?></td>
      <input type="hidden" name="id_prod_med[]" value="<?php echo $row->id_prod_med ?>">
      <td><?php echo $row->nombre ?></td>
      <td style="text-align: center;">
        <input type="number" name="cantidad_prescrita[]" class="form-control" value="<?php echo $row->cantidad_prescrita ?>" min="1">
      </td>
      <td style="text-align: center;">
        <input type="number" name="cantidad_entregada[]" class="form-control" value="<?php echo $row->cantidad_entregada ?>" min="1">
      </td>
      <td><?php echo $row->precio ?></td>
      <td><?php echo $row->valorizacion ?></td>
      <td style="text-align: center;">
        <select name="id_cie10[]" class="form-control">
          <?php foreach ($diagnosticos_prestacion as $diagnostico): ?>
            <option value="<?= $diagnostico->codigo_cie10 ?>" <?= $diagnostico->codigo_cie10 === $row->id_cie10 ? 'selected' : '' ?>>
              <?= $diagnostico->codigo_cie10 ?>
            </option>
          <?php endforeach; ?>
        </select>
      </td>
      <input type="hidden" name="eliminado_prod[]" value="0" class="flag-eliminado">
      <input type="hidden" name="codigo_cie10[]" value="<?= $row->id_cie10 ?>">
      <input type="hidden" name="id_trama_diagnostico[]" value="<?= $row->id ?>">
      <input type="hidden" name="id_trama[]" value="<?= $prestacion->id ?>">
      <input type="hidden" name="renipress[]" value="<?= $prestacion->codigo_renipress ?>">
      <input type="hidden" name="num_lote[]" value="<?= $prestacion->numero_lote ?>">
      <input type="hidden" name="num_correlativo[]" value="<?= $prestacion->numero_correlativo ?>">
      <td>
        <button type="button" class="btn btn-danger deleteFilaProducto btn-xs" style="margin-left:4px">
          <i class="fa fa-times"></i> Eliminar
        </button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
                                    <button type="button" class="btn btn-primary" id="btnAgregarProducto">
                                        <i class="fa fa-plus"></i> Agregar Producto
                                    </button>   
                                    <button type="button" class="btn btn-success" id="btnGuardarProductos">
                                        <i class="fa fa-save"></i> Guardar Productos
                                    </button>              
                                  </div>
                                </div>
                              </fieldset>                                 
                             <!-- <a href="/modificar_validacion_auditoria/{{$prestacion->id}}" class="btn btn-primary pull-right"  id="btnPrestacion">Validar Prestacion </a>                            
          -->
          @if($prestacion->actualizacon_trama != 2)
    <a href="/modificar_validacion_auditoria/{{ $prestacion->id }}" class="btn btn-primary pull-right" id="btnPrestacion">
        Validar Prestación
    </a>
@else
    <a href="#" class="btn btn-warning pull-right disabled" id="btnPrestacion" onclick="return false;" style="pointer-events: none; opacity: 0.6;">
        Validar Prestación 
    </a>
@endif
                            </form>
                          </div>
                        </div>
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
  </div>
  @include('frontend.includes.footer')
  @endsection