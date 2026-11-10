@extends('frontend.layouts.master')
@section('renderjs')
    <script>
        function justNumbers(e) {
            var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum == 46))
                return true;
            return /\d/.test(String.fromCharCode(keynum));
        }

        $(document).ready(function() {
          $('#id_procedimiento').select2({ width: '100%' });
          $('#id_proveedor').select2({ width: '100%' });

          $("#btnReg").on("click", function() {
            if ($("#id_procedimiento").val() == "") {
                bootbox.alert("Falta seleccionar el Procedimiento de Laboratorio");
                return false;
            }
            if ($("#descripcion_corta").val() == "") {
                bootbox.alert("Falta ingresar la descripción corta");
                return false;
            }
            onSubmit();
            // $("#frmReceta").submit();
          });

          $(document).on('click', '#btnEliminar', function () {
            const id_labnivelatencion = $(this).data('id');
            Swal.fire({
              title: '¿Estás seguro?',
              text: "Esta acción eliminará la RUA.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#6c757d',
              confirmButtonText: 'Sí, eliminar',
              cancelButtonText: 'Cancelar'
            }).then((result) => {
              if (result.isConfirmed) {
                deleteRua(id_labnivelatencion);
              }
            });
          });

          $('#examen_laboratorio').dataTable({
            "language": {
              "sProcessing": "Procesando...",
              "sLengthMenu": "Mostrar _MENU_ registros",
              "sZeroRecords": "No se encontraron resultados",
              "sEmptyTable": "Ningún dato disponible en esta tabla",
              "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
              "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
              "sInfoPostFix": "",
              "sSearch": "Filtrar:",
              "sUrl": "",
              "sInfoThousands": ",",
              "sLoadingRecords": "Cargando...",
              "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
              },
              "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
              }
            }
          });

          function onSubmit() {
            const data = {
              "id_nivel": $("#id_nivel").val(),
              "id_categoria": $("#id_categoria").val(),
              "id_procedimiento": $("#id_procedimiento").val(),
              "descripcion_corta": $("#descripcion_corta").val(),
              "descripcion_corta": $("#descripcion_corta").val(),
              "id_tipo_atencion": $("#id_tipo_atencion").val()
            };
            $.ajax({
              url: '/api/agregar_rua_nivel_tipo_atencion_nivel_1_2',
              method: 'POST',
              dataType: 'json',
              data: data,
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              beforeSend: function () {
                Swal.fire({
                  title: 'Procesando...',
                  text: 'Por favor espera un momento',
                  allowOutsideClick: false,
                  didOpen: () => {
                    Swal.showLoading();
                  }
                });
              }
            })
            .done(function (response) {
              if (response && response.success) {
                const message = response.message || "Los datos fueron registrados correctamente.";
                Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: message,
                  timer: 1500,
                  showConfirmButton: false
                }); 
                location.reload();
              }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
              const messageError = jqXHR.responseJSON && jqXHR.responseJSON.message || "Error inesperado. Por favor intenta nuevamente.";
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: messageError
              });
            })
            .always(function () {
              // if (Swal.isVisible()) {
              //   Swal.close();
              // }
            });
          }

          function deleteRua(id_labnivelatencion) {
            const data = {
              nuevo_estado: 0
            };
            $.ajax({
              url: `/api/nivel_1_2/rua/${id_labnivelatencion}/cambiar-estado`,
              method: "PUT",
              dataType: "json",
              data: data,
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              beforeSend: function () {
                Swal.fire({
                  title: 'Procesando...',
                  text: 'Por favor espera un momento',
                  allowOutsideClick: false,
                  didOpen: () => {
                    Swal.showLoading();
                  }
                });
              }
            })
            .done(function(response) {
              if (response && response.success) {
                const message = response.message || "Operación Exitosa.";
                Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: message,
                  timer: 1500,
                  showConfirmButton: false
                }); 
                location.reload();
              }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
              const messageError = jqXHR.responseJSON && jqXHR.responseJSON.message || "Error inesperado. Por favor intenta nuevamente.";
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: messageError
              });
            })
            .always(function(){
            });
          }
        });
    </script>
    <style>
        .etiqueta {
            margin-top: 5px
        }

        .panel_medicamentos {
            border: 1px solid gray;
            padding-top: 10px
        }

        .panel-heading div {
            margin-top: -18px;
            font-size: 15px;
        }

        .panel-heading div span {
            margin-left: 5px;
        }

        .swal-wide {
          width: 500px !important;
        }

        .swal2-icon.swal2-warning {
          font-size: 15px;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">RUA - {{ $descripcion_tipo }} - {{ $descripcion_nivel }} -
                            {{ $categoria->nombre }} </h3>
                        <div class="pull-right">
                            {{-- <a class="btn btn-default btn-xs" href="/add_categoria_rua2/{{ $id_consultorio }}"> --}}
                            <a class="btn btn-default btn-xs" href="/add_categoria_rua/{{$nivel}}/{{ $id_categoria }}">
                                <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12" style="margin-top:15px">
                          <div class="panel panel-info">
                              <div class="panel-heading">
                                  <h3 class="panel-title">Agregar Examen de Laboratorio</h3>
                              </div>
                              <div class="panel-body">
                                  {{-- <form id="frm_transferencia" method="POST" action="{{ route('agregar_rua_nivel_tipo_atencion') }}"> --}}
                                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                      <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="{{ $tipo }}">
                                      <input value="{{ $nivel }}" name="id_nivel" id="id_nivel" type="hidden">
                                      <input value="{{ $id_categoria }}" name="id_categoria" id="id_categoria" type="hidden">
                                      {{-- <input value="{{ $id_consultorio }}" name="id_consultorio" id="id_consultorio" type="hidden"> --}}
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                            <label>Seleccionar el Exámen de Laboratorio</label>
                                            <select class="form-control" name="id_procedimiento" id="id_procedimiento">
                                                <option value="">--Seleccionar--</option>
                                                @foreach ($listar_procedimientos_laboratorios as $pro)
                                                    <option value="<?php echo $pro->id; ?>" data-codigo="{{ $pro->codigo }}"><?php echo $pro->codigo . ' ' . $pro->descripcion; ?></option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                            <label>Descripción corta</label>
                                            <input name="descripcion_corta" id="descripcion_corta" type="text" class="form-control input-sm" required="">
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                            <label>&nbsp;</label>
                                            <input type="button" id="btnReg" class="btn btn-success form-control" value="Agregar">
                                        </div>
                                      </div>
                                  {{-- </form> --}}
                              </div>
                          </div>
                        </div>

                        <div class="col-md-12" style="margin-top:15px">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Exámenes seleccionados</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table" id="examen_laboratorio" style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th style="width: 60%">Examen</th>
                                                <th style="width: 30%">Descripción Corta</th>
                                                {{-- <th>Código Personalizado</th> --}}
                                                {{-- <th>Proveedor</th> --}}
                                                <th style="width: 10%">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($listar_examenes as $p)
                                                <tr>
                                                    <td><b>{{ $p->codigo }}</b> - {{ $p->descripcion }}</td>
                                                    <td>{{ $p->descripcion_corta }}</td>
                                                    {{-- <td>{{ $p->cpms_personalizado }}</td> --}}
                                                    {{-- <td>{{ $p->descripcion_proveedor }}</td> --}}
                                                    {{-- <td><a class="btn btn-danger btn-xs" href="/eliminar_rua_nivel_tipo_atencion/{{$p->id}}/{{$tipo}}/{{$nivel}}/{{$id_categoria}}/{{$id_consultorio}}">Eliminar</a></td> --}}
                                                    <td style="text-align: center">
                                                      <button
                                                        class="btn btn-danger btn-xs"
                                                        id="btnEliminar"
                                                        name="btnEliminar"
                                                        data-toggle="tooltip"
                                                        data-placement="bottom"
                                                        title="Eliminar"
                                                        data-id={{ $p->id }}
                                                      >
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                      </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @include('frontend.includes.footer')
@endsection
