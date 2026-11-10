
@extends('frontend.layouts.master2')
@section('renderjs')

<!-- Incluir jQuery -->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script>
 $(document).ready(function() {
  $('#btnTramaProductos').click(function() {
    Swal.fire({
    title: '¿Estás seguro?',
    text: '¿Deseas proceder con la inclusión de los productos en la trama?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, proceder',
    cancelButtonText: 'No, cancelar',
    customClass: {
        icon: 'custom-icon-size'
    },
    didOpen: () => {
        // Aplicar estilos en línea al ícono
        const icon = Swal.getIcon();
        if (icon) {
            icon.style.fontSize = '1.5em'; // Ajusta este valor según tus necesidades
        }
    }
}).then((result) => {
    if (result.isConfirmed) {
        // El usuario confirmó, proceder con la operación
        Swal.fire({
            title: '<div class="titulo-padding"><p>Procesando el envío...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header: 'swal2-header-generar'
            }
        });
        Swal.showLoading();

        var data = {
            id_trama_prestacion: <?php echo json_encode($prestacion->id); ?>,
            dni: <?php echo json_encode($rf_dni); ?>,
            tipo_atencion: <?php echo json_encode($rf_tipo_atencion); ?>,
            fecha_registro: <?php echo json_encode($rf_fecha_ingreso); ?>,
            fecha_alta_medica: <?php echo json_encode($rf_fecha_alta_medica); ?>,
            productos: [],
            _token: '{{ csrf_token() }}'
        };
        var table = document.getElementById('tblProductosRecetaFisica');

        if (table) {
            var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var index = i; 

                // Construir los IDs de los elementos basados en el índice
                var codigoProductoId = 'codigo_producto_' + index;
                var cantidadPrescritaId = 'cantidad_prescrita_' + index;
                var cantidadDispensadaId = 'cantidad_dispensada_' + index;
                var precioId = 'precio_' + index;
                var valorizacionId = 'valorizacion_' + index;
                var selectDiagnosticoId = 'select_diagnostico_' + index;
                var codigo_diagnosticoId = 'codigo_diagnostico_' + index; 

                // Obtener los elementos por su ID y verificar su existencia
                var codigoProductoElem = document.getElementById(codigoProductoId);
                var cantidadPrescritaElem = document.getElementById(cantidadPrescritaId);
                var cantidadDispensadaElem = document.getElementById(cantidadDispensadaId);
                var precioElem = document.getElementById(precioId);
                var valorizacionElem = document.getElementById(valorizacionId);
                var selectDiagnosticoElem = document.getElementById(selectDiagnosticoId);
                var codigo_diagnosticoElem = document.getElementById(codigo_diagnosticoId);
                

                // Inicializar variables para almacenar los valores
                var codigoProducto = codigoProductoElem ? codigoProductoElem.value : null;
                var cantidadPrescrita = cantidadPrescritaElem ? cantidadPrescritaElem.value : null;
                var cantidadDispensada = cantidadDispensadaElem ? cantidadDispensadaElem.value : null;
                var precio = precioElem ? precioElem.value : null;
                var valorizacion = valorizacionElem ? valorizacionElem.value : null;
                var codigoDiagnostico = null;

                // Verificar si el elemento del input codigo_diagnostico_ tenga valor 
                if (codigo_diagnosticoElem) {
                  codigoDiagnostico = codigo_diagnosticoElem.value;
                } else {
                    // Si no existe el select, buscar el input hidden con el código de diagnóstico
                    var codigoDiagnosticoElem = selectDiagnosticoElem.value;
                    if (codigoDiagnosticoElem) {
                      codigoDiagnostico = codigoDiagnosticoElem;
                    }
                }

                // Agregar los valores al array de productos en el objeto data
                data.productos.push({
                    codigo_producto: codigoProducto,
                    cantidad_prescrita: cantidadPrescrita,
                    cantidad_dispensada: cantidadDispensada,
                    precio: precio,
                    valorizacion: valorizacion,
                    codigo_diagnostico: codigoDiagnostico
                });
            }
        }
        //console.log(data);
        $.ajax({
            url: '{{ route('incluir_productos_trama') }}',
            type: 'POST',
            data: data,
            success: function(response) {
                Swal.hideLoading();
                console.log(response);

                if (response) {
                    // MOSTRAR DE MANERA ASINCRONA LA LISTA DE PRODUCTOS ACTUALIZADOS
                    $('#tblProductosRecetaFisica tbody').empty();
                    $('#tblDiagnostico_anteriores tbody').empty();
                    response.productos.forEach(function(producto) {
                        var fila = '<tr>' +
                            '<td>' + producto.id_prod_med + '</td>' +
                            '<td>' + producto.nombre + '</td>' +
                            '<td>' + producto.precio + '</td>' +
                            '<td>' + producto.cantidad_prescrita + '</td>' +
                            '<td>' + producto.cantidad_entregada + '</td>' +
                            '<td>' + producto.valorizacion + '</td>' +
                            '<td>' + (producto.id_cie10 ? producto.id_cie10 : '') + '</td>' +
                            '</tr>';
                        $('#tblDiagnostico_anteriores tbody').append(fila);
                    });
                    $('.dato_precio_producto').html(response.dato_precio_productos);
                    $('.dato_precio_procedimiento').html(response.dato_precio_procedimiento);
                    $('.dato_precio').html(response.dato_precio);

                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Los productos se han incluido correctamente en la trama productos.',
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia!',
                        text: 'Los productos no se han incluido correctamente en la trama productos.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la solicitud.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
        // El usuario canceló la operación
        Swal.fire({
            icon: 'info',
            title: 'Operación cancelada',
            text: 'La inclusión de los productos en la trama ha sido cancelada.',
            confirmButtonText: 'Aceptar',
            customClass: {
                icon: 'custom-icon-size'
            },
            didOpen: () => {
                // Aplicar estilos en línea al ícono
                const icon = Swal.getIcon();
                if (icon) {
                    icon.style.fontSize = '1.5em'; // Ajusta este valor según tus necesidades
                }
            }
        });
    }
});

  });
});

$('#btnguardarObservaciones').click(function () {
    var observacion = $('#observaciones').val();
    var id_trama = $('#id_trama').val();  
    var id_user = $('#id_user').val();  

    if (observacion.trim() !== '') {
       
        $.ajax({
            url: '/guardar_observacion_auditoria', 
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), 
                observacion: observacion,
                id_trama: id_trama,
                id_user: id_user
            },
            success: function (response) {              
                Swal.fire('Guardado', 'La observación fue guardada correctamente.', 'success')
                    .then(() => {                     
                        location.reload();
                    });
            },
            error: function (xhr, status, error) {     
                Swal.fire('Error', 'Ocurrió un problema al guardar la observación.', 'error');
            }
        });
    } else {
        Swal.fire('Advertencia', 'Por favor, ingresa una observación.', 'warning');
    }
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
                            <h3 class="panel-title">MÓDULO DE AUDITORÍA DE PRESTACIONES</h3>
                          </div>
                          <div class="panel-body">

                            <form class="form-horizontal" method="post" action=" " id="frmPrestacion"
                              autocomplete="off">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                              <input type="hidden" value="{{$prestacion->id}}" name="id_trama" id="id_trama">
                        
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
                                              <span class="form-control" style="text-align:left"><?php echo $prestacion->codigo_renipress ?>
                                              </span>
                                              </div>
                                              <div class="col-md-2">
                                                <label>Numero Lote</label>
                                                <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->numero_lote ?>
      
                                              </div>
                                            <div class="col-md-2">
                                              <label>Numero Correlativo</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->numero_correlativo ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Procedencia Prestacion</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->procedencia_prestacion ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>IPRESS Referencia</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->ipress_referencia_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Hoja Referencia FUPS</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->hoja_referencia_fups ?>
    
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                          
                                            <div class="col-md-2">
                                              <label>Tipo Documento Identidad Pac.</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->tipo_doc_identidad.' - '.$nombre_docu?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Documento Identidad Pac.</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->paciente_nro_doc_identidad ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Historia Clinica Pac.</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_hist_clinica ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Apellido Paterno Paciente</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->paciente_apellido_paterno ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Apellido Materno Paciente</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->paciente_apellido_materno ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Nombres Paciente</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->paciente_nombres ?>
    
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                            <div class="col-md-2">
                                              <label>Tipo Cobertura</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_tipo_cobert.' - '.$nombre_cobertura?>
    
                                            </div>
                                            <div class="col-md-4">
                                              <label>UPSS Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_upss_fups.' - '.$nombre_upss?> 
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Atencion Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_atencion_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Ingreso Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_ingreso_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Inicio Administrativo Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_inicio_adm ?>
    
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                            <div class="col-md-2">
                                              <label>Fecha Cierre Administrativo Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_cierre_adm_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Egreso Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_egreso_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Renipress Vincula Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->renipress_vincula_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Lote Vincula Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->num_lote_vincula_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Vincula Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->num_vincula_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Estado Paciente Mujer Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->estado_paci_mujer_fups ?>
    
                                            </div>
                                          </div>
                                          <br />
                                          <div class="row">
                                          <div class="col-md-2">
                                              <label>Fecha Probable Parto Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_probable_parto_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Fecha Parto Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_parto_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Destino Asegurado Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_destino_aseg_fups.' - ' ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>IPRESS Destino Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->ipress_destino_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                              <label>Numero Hoja Destino Fups</label>
                                              <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->num_hoja_destino_fups ?>
    
                                            </div>
                                            <div class="col-md-2">
                                            <label>Fecha Fallecimiento</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->fecha_fallece ?>
    
                                          </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Numero Acreditacion Siteds</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->numero_acreditacion_siteds ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Tipo Documento Identidad Med.</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_tipodoc_ident.' - '.$nombre_docu_m?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Numero Documento Identidad Med.</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->numdoc_ident ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Apellido Paterno Medico</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->doc_ape_paterno ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Apellido Materno Medico</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->doc_ape_materno ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Nombres Medico</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->doc_nombres ?>
    
                                          </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Tipo Profesional</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_tipo_prof ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Numero Colegiatura</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->num_coleg ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Especialidad Profecional</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->id_especialidad_prof ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>Numero RNE</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->num_rne ?>
    
                                          </div>
                                          <div class="col-md-2">
                                            <label>RUC IPRESS</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio"><?php echo $prestacion->ruc_ipress ?>
    
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
                                </fieldset>
                                <fieldset>
                                  <a target="_blank" href="/hc_electronica/{{ $detalle_prestacion->id_asegurado }}" class="btn btn-primary pull-right" id="btnPrestacion">Ver H.C.</a>
                                </fieldset>
                                <br>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>DIAGNOSTICOS DE ATENCION (CIE10)</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">               
                                    <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" >
                                      <thead <?php if($diag_aten != 0): echo 'style="margin-top:5px; background-color: yellow;"';else: 'style="margin-top:5px;"' ;endif; ?>>
                                        <tr>
                                          <th width="10%">Codigo</th>
                                          <th width="55%">Nombre</th>
                                          <th width="10%">Tipo</th>
                                          <th width="10%">Clasificación</th>
                                          <th width="15%" style="text-align: center;">Ingreso/Egreso</th>
                                      </tr>
                                      </thead>
                                      <tfoot>
                                      </tfoot>
                                      <tbody>
                                        <?php foreach($diagnosticos_prestacion as $row): ?>
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
                                                          <tr <?php if ($resaltar) echo 'style="margin-top:5px; background-color: yellow"'; else echo 'style="margin-top:5px"'; ?>>
                                                            <td><?php echo $row->codigo_cie10?></td>
                                                            <td><?php echo $row->nombre_cie10?></td>
                                                            <td><?php echo $row->tipo_diagnostico?></td>
                                                            <td><?php echo $row->tipo_atencion?></td>
                                                            <td style="text-align: center;"> 
                                            <?php
                                                if ($row->id_identificador_diag == 'E') {
                                                    echo 'EGRESO';
                                                } elseif ($row->id_identificador_diag == 'I') {
                                                    echo 'INGRESO';
                                                } else {
                                                    echo ''; 
                                                }
                                            ?>
                                        </td>
                                                          </tr>
                                                      <?php endforeach; ?>
                                      </tbody>
                                    </table>               
                                  </div>
                                </div>
                              </fieldset>
                             
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                   <div class="panel-heading">
                                   <div class="panel-title text-center" style="position: relative">
                                  <b>PROCEDIMIENTOS MEDICOS (CPMS)</b>
                                  <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0;display:none;">Editar</button>
                                </div>
                              </div>
                              <div class="panel-body">
                            
                                  <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" <?php if($proc != 0): echo 'style="margin-top:5px; background-color: yellow;"';else: 'style="margin-top:5px;"' ;endif; ?>>
                                    <thead>
                                      <tr>
                                        <th width="5%" style="text-align: center;">Codigo</th>
                                        <th width="60%" style="text-align: center;">Nombre</th>
                                        <th width="5%" style="text-align: center;">Cantidad</th>
                                        <th width="5%" style="text-align: center;">Diagnostico</th>
                                        <th width="5%" style="text-align: center;">Precio</th>
                                        <th width="10%" style="text-align: center;">Tipo Proc</th>
                                    </tr>
                                    
                                    </thead>
                                    <tbody>
                                        <?php foreach($procedimientos_trama as $row):?>
                                        <tr>
                                        <tr>
                                        <td style="text-align: center;"><?php echo $row->codigo_procedimiento?></td>
                                        <td><?php echo $row->nombre_procedimiento?></td>
                                        <td style="text-align: center;"><?php echo $row->cantidad_indicada?></td>
                                        <td style="text-align: center;"><?php echo $row->diagnostico_relacionado?></td>
                                        <td style="text-align: center;"><?php echo $row->precio_procedimiento?></td>
                                        <td style="text-align: center;">
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
                                        </tr>
                                        </tr>
                                        <?php endforeach;?>
                                        
                                      </tbody>
                                  
                                  </table>
                                
                              </div>
                            </div>
                          </fieldset>
                          <fieldset>
    <div class="panel panel-info panel-bordered panel-sm">
      
        <div class="panel-heading">
            <div class="panel-title text-center" style="position: relative">
                <b>LABORATORIO E IMÁGENES (CPMS)</b>
                <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0; display: none;">Editar</button>
            </div>
        </div>
        
        <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <label>Exámenes de Laboratorio</label>
                <span class="form-control" style="text-align: left;" name="consultorio" id="consultorio">
                  
                </span>
            </div>

            <div class="col-md-6">
                <label>Exámenes de Imágenes</label>
                <span class="form-control" style="text-align: left;" name="consultorio" id="consultorio">
                   
                </span>
            </div>
        </div>

        <div class="panel-body">
            <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top: 5px; <?php echo ($proc != 0) ? 'background-color: yellow;' : ''; ?>">
                <thead>
                <tr>
                        <th width="10%" style="text-align: center;">CPMS</th>
                        <th width="60%" style="text-align: left;">Descripción</th>
                        <th width="10%" style="text-align: center;">Precio</th>
                        <th width="10%" style="text-align: center;">Cantidad</th>
                        <th width="10%" style="text-align: center;">CIE10</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>

        <div class="panel-body">
            <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top: 5px; <?php echo ($proc != 0) ? 'background-color: yellow;' : ''; ?>">
                <thead>
                    <tr>
                        <th width="10%" style="text-align: center;">CPMS</th>
                        <th width="60%" style="text-align: left;">Descripción</th>
                        <th width="10%" style="text-align: center;">Precio</th>
                        <th width="10%" style="text-align: center;">Cantidad</th>
                        <th width="10%" style="text-align: center;">CIE10</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
        </div>
    </div>
</fieldset>
                          <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center" >
                                      <b>PRODUCTOS FARMACEUTICOS Y DISPOSITIVOS MEDICOS (PETITORIO)</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">               
                                    <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" >
                                      <thead <?php if($prod != 0): echo 'style="margin-top:5px; background-color: yellow;"';else: 'style="margin-top:5px;"' ;endif; ?>>
                                        <tr>
                                          <th width="10%">Codigo </th>
                                          <th width="50%">Nombre</th>
                                          <th width="10%">Cantidad Prescrita</th>
                                          <th width="10%">Cantidad Entregada</th>
                                          <th width="10%">Precio</th>
                                          <th width="10%">Valorizacion</th>
                                          <th width="10%">CIE10 </th>
                                      </tr>
                                      </thead>
                                      <tbody>

                                                      <?php foreach($productos_trama as $row): ?>
                                                          <?php
                                                          $resaltar = false; 
                                                          if (!empty($cod_prod)) {
                                                              foreach ($cod_prod as $dato) {
                                                                  if ($dato == $row->id_prod_med) {
                                                                      $resaltar = true; 
                                                                      break; 
                                                                  }
                                                              }
                                                          }
                                                          ?>
                                                          <tr <?php if ($resaltar) echo 'style="margin-top:5px; background-color: yellow"'; else echo 'style="margin-top:5px"'; ?>>
                                                              <td><?php echo $row->id_prod_med?></td>
                                                              <td><?php echo $row->nombre?></td>
                                                              <td><?php echo $row->cantidad_prescrita?></td>
                                                              <td><?php echo $row->cantidad_entregada?></td>
                                                              <td><?php echo $row->precio?></td>
                                                              <td><?php echo $row->valorizacion?></td>
                                                              <td><?php echo $row->id_cie10?></td>
                                                          </tr>
                                                      <?php endforeach; ?>
                                        </tbody>
                                    </table>               
                                  </div>
                                </div>
                              </fieldset>
                              
                              <fieldset>
    <div class="panel panel-info panel-bordered panel-sm">
        <div class="panel-heading">
            <div class="panel-title text-center">
                <b>PRECIOS TRAMA</b>
            </div>
        </div>
        <div class="panel-body">  
            <div class="col-md-2">
                <label>Precio Producto</label>
                <span class="form-control dato_precio_producto" style="background-color: lightpink;" name="consultorio" id="consultorio"><?php echo $dato_precio_productos ?></span>
            </div>
            <div class="col-md-2">
                <label>Precio Procedimiento</label>
                <span class="form-control dato_precio_procedimiento" style="background-color: lightpink;" name="consultorio" id="consultorio"><?php echo $dato_precio_procedimiento ?></span>
            </div>
            <div class="col-md-4">
                <label>Precio Total Trama</label>
                <span class="form-control dato_precio" style="background-color: #eb0303; color: black; font-weight: bold;" name="consultorio" id="consultorio"><?php echo $dato_precio ?></span>
            </div>
        </div>
    </div>
</fieldset>

<div class="panel panel-info panel-bordered panel-sm" id="panelObservaciones">
    <div class="panel-heading">
        <div class="panel-title text-center">
            <b>Observaciones</b>
        </div>
    </div>
    <div class="panel-body">  
        <div class="col-md-12">
            <label>Observación</label>
            <textarea 
                class="form-control" 
                name="observaciones" 
                id="observaciones" 
                rows="1" 
                placeholder="Escriba aquí las observaciones...">
                <?php echo $prestacion->comentario ?>
              </textarea>
        </div>       
    </div>
</div>
<button type="button" class="btn btn-warning pull-left" id="btnguardarObservaciones">Guardar Observación</button>
<a href="/all_auditoria" class="btn btn-primary pull-right" id="btnPrestacion">LISTADO </a>
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