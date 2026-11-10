@extends('frontend.layouts.master3')
@section('renderjs')
<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
@if (session()->has('success'))
    <script>
        $(document).ready(function () {
            Swal.fire({
                title: "¡Éxito!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
        });
    </script>
@endif
<script>
    $(document).ready(function() {
        $("#id_servicio_destino").select2({
            width: 'resolve'
        });

        $('#fecha_ini').datepicker({
            useCurrent: false,
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            orientation: "bottom left"
        });

        $('#fecha_fin').datepicker({
            useCurrent: false,
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            orientation: "bottom left"
        });

        $('#btnBuscar').click(function() {
            datatablenew();
        });

        datatablenew();

    });

    function datatablenew() {
        const auth = {!! json_encode(Auth::user()) !!};
        const establecimiento = {!! json_encode($establecimiento) !!};
        const nivelEstablecimiento = establecimiento && establecimiento.nivel ? parseInt(establecimiento.nivel) : 3;
        var oTable1 = $('#tblHistorialConsejeria').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/all_historial_consejeria_ajax",
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            "bFilter": false,
            "bSort": false,
            "info": true,
            "language": {
                "url": "/js/Spanish.json"
            },
            "autoWidth": false,
            "bLengthChange": true,
            "destroy": true,
            "lengthMenu": [
                [10, 50, 100, 200, 60000],
                [10, 50, 100, 200, "Todos"]
            ],
            "aoColumns": [{}, ],
            "dom": '<"top">rt<"bottom"flpi><"clear">',
            "fnDrawCallback": function(json) {
                $('[data-toggle="tooltip"]').tooltip();
            },

            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                var sEcho = aoData[0].value;
                var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                var iCantMostrar = aoData[4].value;
                var fecha_ini = $('#fecha_ini').val();
                var fecha_fin = $('#fecha_fin').val();
                var id_consejeria = $('#id_consejeria').val();
                var id_hospitalizacion = $('#id_hospitalizacion').val();
                var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": {
                        NumeroPagina: iNroPagina,
                        NumeroRegistros: iCantMostrar,
                        fecha_ini: fecha_ini,
                        fecha_fin: fecha_fin,
                        id_consejeria:id_consejeria,
                        id_hospitalizacion: id_hospitalizacion,
                        _token: _token
                    },
                    "success": function(result) {
                        fnCallback(result);
                    },
                    "error": function(msg, textStatus, errorThrown) {
                        //location.href="login";
                    }
                });
            },

            "aoColumnDefs": [{
                    "mRender": function(data, type, row) {
                        return row.id;
                    },
                    "bSortable": false,
                    "aTargets": [0]
                },
                {
                    "mRender": function(data, type, row) {
                        return row.fecha_hora_atencion;
                    },
                    "bSortable": false,
                    "aTargets": [1]
                },
                {
                    "mRender": function(data, type, row) {
                        let profesional = row.paterno + " " + row.materno + " " + row.nombre;
                        return profesional;
                    },
                    "bSortable": false,
                    "aTargets": [2]
                },
                {
                    "mRender": function(data, type, row) {
                        return row.upss_cargo_especialidad;
                    },
                    "bSortable": false,
                    "aTargets": [3]
                },
                {
                    "mRender": function(data, type, row) {
                        return row.servicio;
                    },
                    "bSortable": false,
                    "aTargets": [4]
                },
                {
                    "mRender": function(data, type, row) {
                        if (!row.deleted_us) {
                            return '';
                        }
                        return `<span>${row.deleted_paterno} ${row.deleted_materno} ${row.deleted_name} </span>`;
                    },
                    "bSortable": false,
                    "aTargets": [5],
                    "visible": nivelEstablecimiento !== 2,
                },
                {
                    "mRender": function(data, type, row) {
                        if (!row.deleted_at) {
                            return '';
                        }
                        return `<span>${moment(row.deleted_at).format("DD/MM/YYYY HH:mm")}</span>`;
                    },
                    "bSortable": false,
                    "aTargets": [6],
                    "visible": nivelEstablecimiento !== 2,
                },
                {
                    "mRender": function(data, type, row) {
                        let html = '';
                        html += '<div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true" title="<b>Ver Atención</b>">';
                        /*html += ' <a href="" class="btn btn-success btn-sm">';
                        html += ' <i class="fa fa-eye"></i></a>';*/
                        html += '<a href="/ver_atencion_consejeria/' + row.id + '/' + row.id_hospitalizacion + 
                            '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>';
                        html += '</div>';
                        return html;
                    },
                    "bSortable": false,
                    "aTargets": [7]
                },
                {
                    "mRender": function(data, type, row) {
                        if (row.flag_firma || parseInt(row.estado) === 0 || parseInt(auth.id) !== parseInt(row.created_us)) {   
                            return '';
                        }
                        return `
                            <div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true" title="<b>Editar</b>">
                                <a href="/consejeria/${row.id}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                            </div>
                        `;
                    },
                    "bSortable": false,
                    "aTargets": [8],
                    "visible": nivelEstablecimiento !== 2,
                },
                {
                    "mRender": function(data, type, row) {
                        if (parseInt(row.estado) === 0) {
                            return '<span class="label label-danger">Anulado</span>';
                        }
                        if (row.flag_firma || parseInt(auth.id) !== parseInt(row.created_us)) {
                            return '';
                        }
                        return `
                          <div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true" title="<b>Anular</b>">
                            <button class="btn btn-danger btn-xs" data-id="${row.id}" id="btnAnular"><i class="fa fa-trash"></i></button>
                          </div>
                        `;
                    },
                    "bSortable": false,
                    "aTargets": [9],
                    "visible": nivelEstablecimiento !== 2,
                },
                {
                    "mRender": function(data, type, row) {
                        if (parseInt(row.estado) === 0) {
                            return '';
                        }
                        let url = `/consejeria/generar-pdf/${row.id}`
                        let tooltipLabel = "PDF";
                        if (row.flag_firma && row.ruta_firma) {
                            url = `/${moment().format("YYYY/MM/DD")}/${row.ruta_firma}`;
                            tooltipLabel = "PDF Firmado";
                        }
                        let html = `
                            <div data-toggle="tooltip" data-placement="top" title="${tooltipLabel}" style="text-align: center;">
                                <a href="${url}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                            </div>
                        `;
                        return html;
                    },
                    "bSortable": false,
                    "aTargets": [10]
                }
            ]
        });

        $(document).on("click", "#btnAnular", function() {
          const id = $(this).data("id");
          Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción anulará la consejería y no se podrá deshacer.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, anular",
            cancelButtonText: "Cancelar"
          }).then((result) => {
            if (result.isConfirmed) {
            
              onShowFormAnulacion(id);
            }
          });
        }); 

        function onShowFormAnulacion(id) {
            Swal.fire({
                title: 'Anulación de Consejería',
                html: '<label for="swal-textarea">Ingrese el motivo:</label><textarea id="swal-textarea" class="swal2-textarea" placeholder="Escribe el motivo..." style="width:100%; height:100px;"></textarea>',
                showCancelButton: true,
                confirmButtonText: 'Anular',
                cancelButtonText: 'Cancelar',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                preConfirm: () => {
                    const motivo = document.getElementById('swal-textarea').value.trim();
                    if (!motivo) {
                        Swal.showValidationMessage('Debe ingresar un motivo para la anulación');
                        return false;
                    }
                    return motivo;
                }
                }).then((result) => {
                    if (result.isConfirmed) {
                        onAnularConsejeria(id, result.value);
                        // console.log('Motivo de anulación:', result.value);
                        // Swal.fire('Consejería anulada', 'El motivo ha sido registrado.', 'success');
                    }
                });
        }

        function onAnularConsejeria(consejeriaId, motivo) {
            const body = { estado: 0, deleted_reason: motivo};
          $.ajax({
            type: "PUT",
            url: `/api/consejeria/cambio-estado/${consejeriaId}`,
            contentType: 'application/json',
            data: JSON.stringify(body),
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
              Swal.fire({
                html: '<h5>ANULANDO CONSEJERIA...</h5>',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onOpen: () => {
                    Swal.showLoading();
                }
              });
            },
            success: (response) => {
              Swal.fire({
                icon: 'success',
                title: "CONSEJERIA",
                html: `<p>Consejeria anulada correctamente</p>`,
                confirmButtonText: "ENTENDIDO",
              });
              datatablenew();
            },
            error: (error) => {
              Swal.fire({
                icon: 'error',
                title: "CONSEJERIA",
                html: `<p>Ocurrio un error al actualizar la consejeria, intentelo en unos minutos</p>`,
                confirmButtonText: "ENTENDIDO",
              });
            },
            complete: function() {
              Swal.hideLoading();
            }
          });
        }
    }
</script>
@endsection
@section('content')
<style>
    .form-group {
        margin-bottom: auto;
    }
</style>
<div class="container-fluid">
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title">Historial</h3>
        </div>
        <div class="panel-body">
            
            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        <b>INFORMACIÓN DEL PACIENTE</b>
                    </div>
                </div>
                <div class="panel-body" id="collapseprestacionespaciente">
                    <form>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <label>NOMBRE DE LA IPRESS</label>
                                    <p>{{$paciente->establecimiento}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label>DOCUMENTO</label>
                                    <p>{{$paciente->tipo_doc_ident}} - {{$paciente->nro_doc_ident}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label>N° HISTORIA CLINICA</label>
                                    <p>{{$paciente->nro_historia}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label>APELLIDO PATERNO</label>
                                    <p>{{$paciente->asegurado_paterno}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label>APELLIDO MATERNO</label>
                                    <p>{{$paciente->asegurado_materno}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label>NOMBRES</label>
                                    <p>{{$paciente->asegurado_nombre}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label>PARENTESCO</label>
                                    <p>{{$paciente->parentesco}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label for="dni_beneficiario">SEXO</label>
                                    <p>{{$paciente->sexo}}</p>
                                </div>
                            </div>
                            @if (isset($cama))
                                <div class="form-group">
                                    <div class="col-lg-2 col-md-2 col-sm-4">
                                        <label for="tipo">SALA</label>
                                        <p>{{$cama->consultorio}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-2 col-md-2 col-sm-4">
                                    <label for="tipo">PABELLON</label>
                                    <p>{{$cama->pabellon}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-1 col-md-1 col-sm-4">
                                    <label for="dni_beneficiario">PISO</label>
                                    <p>{{$cama->piso}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-1 col-md-1 col-sm-4">
                                    <label>HABITACION</label>
                                    <p>{{$cama->cuarto}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-1 col-md-1 col-sm-4">
                                    <label>CAMA</label>
                                    <p>{{$cama->cama}}</p>
                                    </div>
                              </div>
                            @endif
                            
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-body">
                    <div class="row">
                        
                        <input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion" value="{{$id_hospitalizacion}}">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Fecha Desde (Atención)</label>
                                <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde" value="{{date('d-m-Y')}}" maxlength="10" data-date-end-date="0d">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Fecha Desde (Atención)</label>
                                <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta" value="{{date('d-m-Y')}}" maxlength="10" data-date-end-date="0d">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">&nbsp;</label>
                                <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                            </div>
                        </div>
                       @if($origen == 'consejeria' && $estado != 6)
                            <div class="col-md-offset-4 col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <a href="/formulario_atencion_hospitalizacion_consejeria/{{$id_hospitalizacion}}"
                                    class="form-control btn btn-primary input-sm">
                                    <i class="fa fa-plus"></i> Nuevo Registro
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if($origen == 'centro' && isset($consultoriomedico[0]) && $consultoriomedico[0]->id_consultorio == 125)
                            <div class="col-md-offset-4 col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <a href="/formulario_atencion_hospitalizacion_consejeria/{{$id_hospitalizacion}}"
                                    class="form-control btn btn-success input-sm">
                                    <i class="fa fa-plus"></i> Nuevo Registro
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <br>
            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="tblHistorialConsejeria" class="table table-bordered table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha y Hora Atencion</th>
                                        <th>Profesional</th>
                                        <th>Upss Cargo Especialidad</th>
                                        <th>{{ isset($establecimiento) && isset($establecimiento->nivel) && intval($establecimiento->nivel) === 2 ? "Servicio" : "Servicio Cargo" }}</th>
                                        <th>Responsable Anulación</th>
                                        <th>Fecha y Hora Anulación</th>
                                        <th style="text-align:center">Ver Atención</th>
                                        <th style="text-align:center">Editar</th>
                                        <th style="text-align:center">Anular</th>
                                        <th style="text-align:center">Pdf Atención</th>
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
</div>
@include('frontend.includes.footer')
@endsection