<?php ini_set('memory_limit', '-1');
set_time_limit(0); ?>
@extends('frontend.layouts.master3')
<style>
    .input-date {
        background-color: #ffffff !important;
    }
    .input-document-number {
        text-transform: uppercase;
    }
    .dt-center {
        text-align: center;
    }
    .btn-custom {
        margin-top: 24px;
    }

    .btn-xs {
        margin-bottom: 8px !important;
        padding: 4px 6px !important;
    }
    .btn-group-actions {
      display: flex;
      flex-direction: row;
      column-gap: 7px;
      justify-content: center;
    }
    #tblListadoEnfermeria th,
    #tblListadoEnfermeria td {
      vertical-align: middle !important;
    }
    .tooltip-inner {
      max-width: 300px;
      white-space: normal;
      word-wrap: break-word;
    }
    #tblListadoNotasEnfermeria th,
    #tblListadoNotasEnfermeria td {
      vertical-align: middle !important;
    }
</style>
@section('renderjs')
<script>
    $(document).ready(function () {
        const sessionFlashError = {!! json_encode(session('error')) !!};
        const sessionFlashSuccess = {!! json_encode(session('success')) !!};
        if (sessionFlashError || sessionFlashSuccess) {
            Swal.fire({
              icon: sessionFlashSuccess ? 'success' : 'error',
              title: "NOTA DE INGRESO DE ENFERMERÍA",
              html: `<p style="font-size: 13px">${sessionFlashError || sessionFlashSuccess}</p>`,
              timer: 2000,
              timerProgressBar: true,
              showConfirmButton: false
            });
        }

        $("#id_servicio").select2({ width: '100%' });

        const dateSubtractOneWeek =  moment().subtract(1, 'weeks').toDate()
        $('#fecha_atencion_inicio').datepicker({
            useCurrent: false,
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            orientation: "bottom left"
        }).datepicker("setDate", new Date(dateSubtractOneWeek));

        $('#fecha_atencion_fin').datepicker({
            useCurrent: false,
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            orientation: "bottom left"
        }).datepicker("setDate", new Date());

        function getCitasEnfermeria() {
            const auth = {!! json_encode(Auth::user()) !!};
            let id_servicio = $("#id_servicio").val();
            if (!id_servicio) {
              if (auth.flagservicios || auth.flagfiscalizador) {
                id_servicio = "";
              } else if (auth.id_consultorio) {
                id_servicio = auth.id_consultorio;
              }
            }

            const cantidad_pagina = 10;
            if ($.fn.DataTable.isDataTable('#tblListadoEnfermeria')) {
                $('#tblListadoEnfermeria').DataTable().destroy();
                $('#tblListadoEnfermeria tbody').empty();
            }
            $('#tblListadoEnfermeria').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                bAutoWidth: true,
                searching: false,
                paging: true,
                pageLength: cantidad_pagina,
                ordering: false,
                lengthChange: false,
                ajax: {
                    url: "/api/enfermeria/obtener-citas/search",
                    type: 'GET',
                    data: function (d) {
                        let page = Math.ceil(d.start / d.length) + 1;
                        d.fecha_atencion_inicio = moment($("#fecha_atencion_inicio").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
                        d.fecha_atencion_fin = moment($("#fecha_atencion_fin").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
                        d.nro_doc_ident = $("#nro_doc_ident").val() || "";
                        d.estado =  "";
                        d.id_servicio = id_servicio || "";
                        d.cantidad_pagina = cantidad_pagina;
                        d.pagina = page;
                    },
                    dataSrc: function (json) {
                        return json.data;
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error en la carga de datos: ", textStatus, errorThrown);
                    }
                },
                columns: [
                    { 
                        data: 'prestacion_id', width: '5%', className: 'text-center dt-center',
                        render: function (data, type, row) {
                            return `<span>${row.prestacion_id ? row.prestacion_id : row.cita_id}</span>`;
                        }
                    },
                    {
                        data: null, width: '10%',
                        render: function (data, type, row) {
                          if (!row.cita_fecha_hora) {
                            return `-`
                          }
                          return `<span> ${moment(row.cita_fecha_hora, "YYYY-MM-DD HH:mm:ss").format("DD/MM/YYYY HH:mm") || " - "}</span>`;
                        },
                    },
                    {
                        data: null, width: '10%',
                        render: function (data, type, row) {
                          if (!row.prestacion_fecha_atencion) {
                            return `<span class="label label-warning">NO ATENDIDO</span>`;
                          }
                          return `<span> ${moment(row.prestacion_fecha_atencion, "YYYY-MM-DD HH:mm:ss").format("DD/MM/YYYY HH:mm") || " - "}</span>`;
                        },
                    },
                    {
                        data: null, width: '15%',
                        render: function (data, type, row) {
                            return `<span> ${row.consultorio_nombre || " - "}</span>`;
                        },
                    },
                    {
                        data: null, width: '25%',
                        render: function (data, type, row) {
                            let tooltipLabel = `
                            <div style="text-align: left;" class="tooltip-inner">
                              <span><b>EDAD:  </b> ${row.asegurado_edad || " - "}</span></br>
                              <span><b>GENERO:  </b> ${row.asegurado_sexo || " - "}</span></br>
                              <span><b>SITUACIÓN:  </b> ${row.asegurado_situacion || " - "}</span></br>
                              <span><b>GRADO:  </b> ${row.asegurado_grado || " - "}</span></br>
                              <span><b>REFERIDO:  </b> ${row.asegurado_flag_referencia ? "SI" : "NO"}</span>
                            </div>
                            `.replace(/"/g, '&quot;');
                            return `<div data-toggle="tooltip" data-placement="bottom" title="${tooltipLabel}">
                                        <span><b>DNI:  </b> ${row.asegurado_numero_documento || " - "}</span></br>
                                        <span><b>NOMBRES:  </b> ${row.asegurado_nombre || ""} ${row.asegurado_paterno || ""} ${row.asegurado_materno || ""}</span></br>
                                    </div>`;
                        },
                    },
                    {
                        data: null, width: '20%',
                        render: function (data, type, row) {
                          if (!row.prestacion_fecha_atencion) {
                            return `<span class="label label-warning">NO ATENDIDO</span>`;
                          }
                            return `<div>
                                        <span><b>DNI: </b> ${row.medico_dni || " - "}</span></br>
                                        <span><b>Nombre</b> ${row.medico_paterno || ""} ${row.medico_materno || ""} ${row.medico_nombre || ""}</span>
                                    </div>`;
                        },
                    },
                    // {
                    //     data: null, width: '20%',
                    //     render: function (data, type, row) {
                    //         return `<div>
                    //                     <span><b>DNI:  </b> ${row.responsable_numero_documento || " - "}</span></br>
                    //                     <span><b>NOMBRES:  </b> ${row.responsable_nombre || ""} ${row.responsable_paterno || ""} ${row.responsable_materno || ""}</span></br>
                    //                 </div>`;
                    //     },
                    // },
                    // {
                    //     data: null, width: '10%', className: 'dt-center',
                    //     render: function (data, type, row) {
                    //         let htmlString = ""
                    //         if (row.flag_firma) {
                    //             if (row.ruta_firma) {
                    //                 htmlString = `<div class="btn-group-actions">
                    //                                 <a href="/enfermeria/consulta-externa/ver-detalle/${row.enfermeria_id}" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Ver Detalle">
                    //                                     <i class="fa fa-eye"></i>
                    //                                 </a><br>
                    //                                 <a href="/${moment().format("YYYY/MM/DD")}/${row.ruta_firma}"  class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="PDF firmado" target="_blank">
                    //                                     <i class="fa fa-download"></i>
                    //                                 </a>
                    //                             </div>`;
                    //             } else {
                    //                 htmlString = `<a href="/enfermeria/consulta-externa/ver-detalle/${row.enfermeria_id}" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Ver Detalle">
                    //                                 <i class="fa fa-eye"></i>
                    //                             </a>`;
                    //             }
                    //         } else {
                    //             htmlString = `<div class="btn-group-actions">
                    //                             <a href="/enfermeria/consulta-externa/ver-detalle/${row.enfermeria_id}" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Ver Detalle">
                    //                                 <i class="fa fa-eye"></i>
                    //                             </a>
                    //                             <a href="/enfermeria/consulta-externa/editar/${row.enfermeria_id}" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="Editar">
                    //                                 <i class="fa fa-pencil-square-o"></i>
                    //                             </a>
                    //                             <a href="/enfermeria/consulta-externa/pdf/${row.enfermeria_id}" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="Generar PDF" target="_blank">
                    //                                 <i class="fa fa-file-pdf-o"></i>
                    //                             </a>
                    //                         </div>`;
                    //         }
                    //         return htmlString;
                    //     },
                    // }
                    {
                        data: null, width: '10%', className: 'text-center',
                        render: function (data, type, row) {
                            return `<button 
                                      type="button"
                                      class="btn btn-info btn-sm"
                                      data-toggle="tooltip"
                                      title="Notas Enfermería"
                                      id="btnVerListadoAtenciones"
                                      data-cita_id="${row.cita_id}"
                                    ><i class="fa fa-list"></i></button>`;
                        },
                    },
                ],
                language: {
                    url: "/js/Spanish.json"
                },
                drawCallback: function(settings) {
                    $('#totalElements').text(settings._iRecordsTotal);
                }
            });

            $('#tblListadoEnfermeria').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip({
                  container: 'body',
                  html: true
                });
            });
        }

        getCitasEnfermeria();

        $("#btnExportReport").on("click", function () {
          onClickExportReport();
        });

        $("#btnSearch").on("click", function () {
            getCitasEnfermeria();
        });

        $(document).on('click', '#btnVerListadoAtenciones', function() {
          const cita_id = $(this).data("cita_id");
          $('#modalNotasEnfermeria').modal('show');
          renderTableNotasEnfermeria(cita_id);
        });

        $('#modalNotasEnfermeria').on('hidden.bs.modal', function () {
          if ( $.fn.DataTable.isDataTable('#tblListadoNotasEnfermeria') ) {
            $('#tblListadoNotasEnfermeria').DataTable().clear().draw();
          }
        });

        function renderTableNotasEnfermeria(cita_id) {
          if ($.fn.DataTable.isDataTable('#tblListadoNotasEnfermeria')) {
            $('#tableAtencionesEnfermeria').DataTable().clear().destroy();
          }
          let tableNotasEnfermeria = $('#tblListadoNotasEnfermeria').DataTable({
            searching: false,
            ordering: false, 
            paging: true,
            pageLength: 10,
            lengthChange: false,
            destroy: true,
            ajax: {
              url: `/api/atenciones/enfermeria/${cita_id}?id_tipo_atencion=1`,
              type: 'GET'
            },
            beforeSend: function () {
              Swal.fire({
                html: '<h5>BUSCANDO NOTAS ENFERMERIA...</h5>',
                showLoaderOnConfirm: true,
                onOpen: () => {
                    Swal.showLoading();
                }
              });
            },
            complete: function () {
              Swal.close();
            },
            columns: [
              { data: 'enfermeria_id', width: '10%' },
              { data: 'fecha_atencion', width: '20%' },
              { data: 'hora_atencion', width: '20%' },
              { 
                data: null, 
                width: '35%',
                render: function (data, type, row) {
                  return `<div>
                            <span><b>DNI:  </b> ${row.responsable_dni || ""}</span></br>
                            <span><b>NOMBRES:  </b> ${row.responsable_nombre || ""}</span></br>
                            <span><b>APELLIDOS:  </b> ${row.responsable_paterno || ""} ${row.responsable_materno || ""}</span></br>
                          </div>`
                }
              },
              { 
                data: null, 
                width: '15%',
                render: function (data, type, row) {
                  let attributeBtnFirma = {
                    "titleTooltip": "Generar PDF",
                    "icon": "fa fa-file-pdf-o",
                    "href": `/enfermeria/consulta-externa/pdf/${row.enfermeria_id}`,
                  };
                  if (row.flag_firma) {
                    attributeBtnFirma.titleTooltip = "PDF firmado";
                    attributeBtnFirma.icon = "fa fa-download";
                    attributeBtnFirma.href = `/${moment().format("YYYY/MM/DD")}/${row.ruta_firma}`;
                  }
                  return `<div>
                            ${!row.flag_firma ? `<a 
                              href="/enfermeria/consulta-externa/editar/${row.enfermeria_id}"
                              class="btn btn-success btn-sm"
                              data-toggle="tooltip"
                              data-placement="bottom"
                              title="Editar"
                            ><i class="fa fa-pencil"></i></a>` : ``}
                            <a 
                              href="/enfermeria/consulta-externa/ver-detalle/${row.enfermeria_id}"
                              class="btn btn-info btn-sm"
                              data-toggle="tooltip"
                              data-placement="bottom"
                              title="Ver Detalle"
                            ><i class="fa fa-eye"></i></a>
                            <a 
                              href="${attributeBtnFirma.href}"
                              class="btn btn-primary btn-sm"
                              data-toggle="tooltip"
                              data-placement="bottom"
                              target="_blank"
                              title="${attributeBtnFirma.titleTooltip}"
                            ><i class="${attributeBtnFirma.icon}" aria-hidden="true"></i></a>
                          </div>`;
                }
              },
            ],
          });

          $('#tblListadoNotasEnfermeria').on('draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
          });

          $('[data-toggle="tooltip"]').tooltip();
        }

        function onClickExportReport() {
          const auth = {!! json_encode(Auth::user()) !!};
          let id_servicio = $("#id_servicio").val();
          if (!id_servicio) {
            if (auth.flagservicios || auth.flagfiscalizador) {
              id_servicio = "";
            } else if (auth.id_consultorio) {
              id_servicio = auth.id_consultorio;
            }
          }

          const params = $.param({
            fecha_atencion_inicio : moment($("#fecha_atencion_inicio").val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
            fecha_atencion_fin : moment($("#fecha_atencion_fin").val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
            nro_doc_ident: $("#nro_doc_ident").val() || "",
            estado : "",
            id_servicio : id_servicio || "",
            cantidad_pagina : 100000,
            pagina : 1,
          });
          window.open('/api/enfermeria/report?' + params, '_blank');
        }
    });
</script>
@endsection()
@section('content')
<div id="addComponent"></div>
    <div class="container-fluid">
        <div class="row col-md-12">
            <div class="panel panel-primary ">
            <div class="panel-heading" style="padding: 0;">
                <h3 style="display: block; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 6px 9px; background-color: rgb(109, 218, 132); color: green;">CE</span> LISTA DE PACIENTES DE ENFERMERÍA - CONSULTA EXTERNA</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="">
                        <div class="row" style="padding: 0px 10px">
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                <label class="control-label">Fecha Desde</label>
                                <input class="form-control input-sm input-date" type="text" name="fecha_atencion_inicio" id="fecha_atencion_inicio" readonly>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                <label class="control-label">Fecha Hasta</label>
                                <input class="form-control input-sm input-date" type="text" name="fecha_atencion_fin" id="fecha_atencion_fin" readonly>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                <label class="control-label">Nro. Documento</label>
                                <input class="form-control input-sm input-document-number" id="nro_doc_ident" name="nro_doc_ident" autocomplete="off" placeholder="BUSCAR ...">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                <label class="control-label" for="id_servicio">SERVICIO</label>
                                <select class="form-control input-sm" id="id_servicio" name="id_servicio">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($consultorio as $c)
                                        <option value="{{$c->id}}">{{$c->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        <div class="row" style="padding: 0px 10px">
                        </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                <label class="control-label" for="seleccionar_estado">Estado</label>
                                <select class="form-control input-sm" id="seleccionar_estado" name="seleccionar_estado">
                                    <option value="-1">TODOS</option>
                                    <option value="0">PENDIENTES</option>
                                    <option value="1">REGISTRADOS</option>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                              <button type="button" id="btnExportReport" class="btn btn-sm btn-success btn-block btn-custom" ><i class="fa fa-file-pdf-o"></i> EXPORTAR</button>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <button type="button" id="btnSearch" class="btn btn-sm btn-info btn-block btn-custom" ><i class="fa fa-search"></i> BUSCAR</button>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <a href="/enfermeria/consulta-externa/registrar_nota_ingreso" id="btnNuevo" class="btn btn-sm btn-primary btn-block btn-custom"><i class="fa fa-plus-square-o"></i> NUEVO</a>
                            </div>
                        </div>
                    </form>
                    <div class="col-md-12">
                        <div id="global">
                            <div class="table-responsive">
                                <table id="tblListadoEnfermeria" class="table table-striped" style="width: 100%; font-size: 11px;">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>FECHA Y HORA CITA</th>
                                            <th>FECHA Y HORA ATENCION</th>
                                            <th>DATOS ATENCION</th>
                                            <th>PACIENTE</th>
                                            <th>MEDICO</th>
                                            {{-- <th>RESPONSABLE</th> --}}
                                            <th>NOTAS ENFERMERIA</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<!-- rass -->
<div class="modal fade" id="modalNotasEnfermeria" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalNotasEnfermeriaLabel" aria-hidden="true"> 
  <div class="modal-dialog modal-dialog-centered modal-xl" style="width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNotasEnfermeriaLabel">NOTAS DE INGRESO ENFERMERIA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="panel panel-default">
          <div class="panel-body">
            <table class="table table-striped" style="font-size: 11px; width: 100%" id="tblListadoNotasEnfermeria">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Fecha Atención</th>
                  <th>Hora Atención</th>
                  <th>Responsable</th>
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
</div>

	@include('frontend.includes.footer')
@endsection
