<title>Sistema de Farmacia</title>
<style>
</style>
<script type="text/javascript">
  $(document).ready(function () {
    $("#btnShowProcedures").on("click", function () {
      const idLaboratorio = $(this).data('id_laboratorio');
      const idPrestacion = $(this).data('id_prestacion');

      $('#modalHistoryExamenOrden').modal('show');
      getExamenesOnHistory(idLaboratorio, idPrestacion);
    });

    function getExamenesOnHistory(idLaboratorio, idPrestacion) {
      $('#tbHistoryExamenOrden').DataTable({
        destroy: true,
        paging: false,
        ordering: false,
        searching: false,
        info: false,
        ajax: {
          url: `/api/laboratorio/${idLaboratorio}/prestacion/${idPrestacion}/examenes`,
          type: 'GET',
          dataSrc: 'data',
          error: function (jqXHR) {
            const message = jqXHR.responseJSON?.message || "Error al obtener el historial.";
            Swal.fire({ icon: 'error', title: 'Error', text: message });
          }
        },
        columns: [
          {
            data: null,
            width: '30%',
            render: function (data, type, row) {
              if (parseInt(row.confirmar_toma) === 0 || parseInt(row.confirmar_toma) === 3) {
                return `
                    <div class="row-examen">
                      <i 
                        class="fa fa-check-circle-o fa-2x" 
                        aria-hidden="true" 
                        style="color: #28a745; margin-right: 5px;"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Examen Atendido"
                      ></i>
                      <span><b>${row.codigo}</b> - ${row.nombre}</span>
                    </div>
                    `;  
              }
              return `
                    <div class="row-examen">
                      <i 
                        class="fa fa-clock-o fa-2x" 
                        aria-hidden="true" 
                        style="color: #fd7e14; margin-right: 5px"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Examen Pendiente"
                      ></i>
                      <span><b>${row.codigo}</b> - ${row.nombre}</span>
                    </div>
                    `;  
              
            }
          },
          { data: 'observacion', width: '15%' },
          { data: 'cantidad', width: '5%' },
          { data: 'diagnostico', width: '20%' },
          { 
            data: null, 
            width: '10%',
            render: function (data, type, row) {
              if (parseInt(row.confirmar_toma) === 0 || parseInt(row.confirmar_toma) === 3) {
                return `<span>${row.reembolso ? "SI": "NO"}</span>`
              }
              return "-";
            }
          },
          { 
            data: null,
            width: '20%',
            render: function (data, type, row, meta) {
              let htmlString = '';
              if (row.files && row.files.length > 0) {
                htmlString += `<div style="width: 80%; display: flex; flex-direction: row; gap: 5px; flex-wrap: wrap;">`
                row.files.forEach((file, index) => {
                  htmlString += `<button
                                    type="button"
                                    class="btn btn-default btn-sm"
                                    id="btnDownloadFile"
                                    data-toggle="tooltip"
                                    data-path="${file.uri}"
                                    data-placement="bottom"
                                    title="${file.original_name}"
                                  >
                                    <i class="fa fa-download text-danger" aria-hidden="true" style="color: #198754"></i>
                                  </button>`
                });
                htmlString += `</div>`
                return htmlString;
              }
              return htmlString;
            }  
          }
        ],
        initComplete: function () {
          Swal.close();
        }
      });
      $('#tbHistoryExamenOrden').on('draw.dt', function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
    }

    $(document).on('click', '#btnDownloadFile', function () {
      const path = $(this).data('path');
      if (path) {
        const filename = path.split('/').pop();
        downloadResultFile(filename);
      }
    });

    function downloadResultFile(filename) {
      const nameWithoutExtension = filename.split('.').slice(0, -1).join('.');

      const url = `/api/nivel_1_2/laboratorio/resultado/${nameWithoutExtension}`;
      window.open(url, '_blank');
    }
  });
</script>

<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <br>
                        <div class="box-body">
                            <p><span class="label label-info"><i class="fa fa-info"></i></span> Exámenes registrados antes de la Implementacion del <b>SIGESAPOL</b></p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm" id="tablemodalLaboratorio">
                                    <thead>
                                        <tr>
                                            <th>TIPO DOC</th>
                                            <th>NUMERO DOC</th>
                                            <th>FECHA</th>
                                            <th>HORA</th>
                                            <th>ESTABLECIMIENTO</th>
                                            <th>TIPO ATENCION</th>
                                            <th>CONSULTORIO</th>
                                            <th>MEDICO</th>
                                            <th>VER</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($ordenes))
                                            @foreach ($ordenes as $orden)
                                                <tr>
                                                    <td>{{ $orden->tipo_documento_identidad }}</td>
                                                    <td>{{ $orden->nro_doc_ident }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($orden->fecha_cita_laboratorio)->format('d/m/Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($orden->fecha_cita_laboratorio)->format('H:i') }}</td>
                                                    <td>{{ $orden->establecimiento }}</td>
                                                    <td>{{ $orden->tipo_atencion }}</td>
                                                    <td>{{ $orden->servicio }}</td>
                                                    <td>
                                                        <span><strong>DNI: </strong>{{ $orden->medico_dni }}</span><br>
                                                        <span><strong>Nombre: </strong>{{ $orden->medico_nombre }}</span><br>
                                                        <span><strong>Apellidos: </strong> {{ $orden->medico_paterno }} {{ $orden->medico_materno }}</span>
                                                    </td>
                                                    <td>
                                                        <button 
                                                            type="button"
                                                            class="btn btn-success btn-xs"
                                                            data-toggle="tooltip"
                                                            data-placement="bottom"
                                                            title="Ver Examenes"
                                                            id="btnShowProcedures"
                                                            data-id_laboratorio={{ $orden->id_laboratorio }}
                                                            data-id_prestacion={{ $orden->id_prestacion }}
                                                        >
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="modalHistoryExamenOrden" tabindex="-1" role="dialog" aria-labelledby="modalHistoryExamenOrdenLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">DETALLE DE LABORATORIO</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
             <table id="tbHistoryExamenOrden" class="table table-striped" style="font-size: 11px; width: 100%">
              <thead>
                <tr>
                  <th style="width: 30%">Examen</th>
                  <th style="width: 15%">Observación</th>
                  <th style="width: 5%">Cant</th>
                  <th style="width: 20%">Diagnóstico</th>
                  <th style="width: 10%">Reembolso</th>
                  <th style="width: 20%">Resultados</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</body>
