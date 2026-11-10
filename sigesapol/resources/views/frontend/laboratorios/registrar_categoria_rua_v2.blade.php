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
          $("#id_categoria").select2({ width: '100%' });
          $("#id_categoria_editar").select2({ 
            width: '100%',
            dropdownParent: $('#modalEditCategoria'),
            minimumResultsForSearch: 0
          });

          $("#btnReg").on("click", function() {
            if ($("#id_categoria").val() == "" || $("#id_categoria").val() == "0") {
              bootbox.alert("Falta seleccionar la categoria");
              return false;
            }
            if ($("#orden").val() == "") {
              bootbox.alert("Falta Ingresar la orden");
              return false;
            }
            onSubmit();
          });

          function onSubmit() {
            const data = {
              "id_categoria": $("#id_categoria").val(),
              "tipo": $("#tipo").val(),
              "nivel": $("#nivel").val(),
              "orden": $("#orden").val()
            };
            $.ajax({
              url: '/api/nivel_1_2/agregar_categoria_rua_atencion',
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

          $(document).on('click', '#btnShowModalEditCategoria', function () {
            const id = $(this).data('id');
            if (id) {
              $("#id_categoria_rua_nivel_edit").val(id);
            }
            $('#modalEditCategoria').modal('show');
          });

          $('#modalEditCategoria').on('show.bs.modal', function () {
            getCategoria();
          })
          $('#modalEditCategoria').on('shown.bs.modal', function () {
          })
          $('#modalEditCategoria').on('hide.bs.modal', function () {
            clearFormEdit();
          })
          $('#modalEditCategoria').on('hidden.bs.modal', function () {
          });

          $("#btnEditCategoria").on("click", function () {
            onEditCategoria();
          });

          function getCategoria() {
            const id_categoria_rua_nivel_edit = $("#id_categoria_rua_nivel_edit").val();
            $.ajax({
              url: `/api/categoria_rua_nivel/${id_categoria_rua_nivel_edit}`,
              method: 'GET',
              dataType: 'json',
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            })
            .done(function (response) {
              if (response && response.data) {
                setFormEdit(response.data);
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
            });
          }

          function setFormEdit(data) {
            $("#id_categoria_editar").val(data.id_categoria_rua).trigger('change');
            $("#orden_editar").val(data.orden);
          }

          function clearFormEdit() {
            $("#id_categoria_rua_nivel_edit").val("");
            $("#id_categoria_editar").val("");
            $("#orden_editar").val("");
          }

          function onEditCategoria() {
            if ($("#id_categoria_editar").val() == "" || $("#id_categoria_editar").val() == "0") {
              bootbox.alert("Falta seleccionar la categoria");
              return false;
            }
            if ($("#orden_editar").val() == "") {
              bootbox.alert("Falta Ingresar la orden");
              return false;
            }
            const data = {
              "id_categoria": $("#id_categoria_editar").val(),
              "tipo": $("#tipo").val(),
              "nivel": $("#nivel").val(),
              "orden": $("#orden_editar").val()
            };
            const id_categoria_rua_nivel_edit = $("#id_categoria_rua_nivel_edit").val();
            $.ajax({
              url: `/api/categoria_rua_nivel/${id_categoria_rua_nivel_edit}`,
              method: 'PUT',
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
                const message = response.message || "Los datos fueron actualizados correctamente.";
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
        });

        //-------------------------------------------------------------------------
        function desactivar(id, estado) {
            if (estado == 0) {
                bootbox.confirm("¿Se activará el Nivel, desea continuar?", function(result) {
                    if (result == true) {
                        $.ajax({
                            url: '/desactivar_nivel_categoria/' + id + '/' + estado,
                            success: function(result) {
                                location.reload();
                            }
                        });
                    }
                });
            } else {
                bootbox.confirm("¿Se desactivará el Nivel, desea continuar?", function(result) {
                    if (result == true) {
                        $.ajax({
                            url: '/desactivar_nivel_categoria/' + id + '/' + estado,
                            success: function(result) {
                                location.reload();
                            }
                        });
                    }
                });
            }
        }
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
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ $descripcion_nivel }} - {{ $descripcion_tipo }}</h3>
                        <div class="pull-right">
                            <a class="btn btn-default btn-xs" href="/all_rua">
                                <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12" style="margin-top:15px">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Agregar Categoría en RUA</h3>
                                </div>
                                <div class="panel-body">
                                    {{-- <form id="frm_transferencia" method="POST" action="{{ route('agregar_categoria_rua_atencion') }}"> --}}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="nivel" id="nivel" value="{{ $nivel }}">
                                    <input type="hidden" name="tipo" id="tipo" value="{{ $tipo }}">
                                    
                                    <input type="hidden" name="id_categoria_rua_nivel_edit" id="id_categoria_rua_nivel_edit">

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">Categoría</label>
                                        <div class="col-lg-6">
                                            <select class="form-control" name="id_categoria" id="id_categoria"
                                                required="" placeholder="Seleccionar un Hospital" style="width: 100%">
                                                <option value="0">-Seleccionar-</option>
                                                @foreach ($categorias as $f)
                                                    <option value="{{ $f->id }}">{{ $f->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-lg-1 control-label">Orden</label>
                                        <div class="col-lg-2">
                                            <input type="text" name="orden" id="orden" class="form-control" autocomplete="off">
                                        </div>
                                        <div class="col-lg-2">
                                            {{-- <input id="btnReg" class="btn btn-success form-control" value="Agregar" type="submit"> --}}
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
                                    <h3 class="panel-title">Establecimientos seleccionados</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table" style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th class="text-left">Categorías</th>
                                                <th class="text-left">Orden</th>
                                                <th class="text-center">Editar</th>
                                                <th class="text-center">Agregar Examen</th>
                                                <th class="text-center">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categoria_laboratorios as $e)
                                                <tr>
                                                    <td>{{ $e->nombre }}</td>
                                                    <td>{{ $e->orden }}</td>
                                                    <td class="text-center">
                                                      <button 
                                                        class="btn btn-primary"
                                                        id="btnShowModalEditCategoria"
                                                        name="btnShowModalEditCategoria"
                                                        data-id="{{$e->id}}"
                                                      >
                                                        <i class="fa fa-pencil-square-o"></i>
                                                      </button>
                                                    </td>
                                                    <td class="text-center">
                                                      <a class="btn btn-primary" href="/add_rua_nivel/{{ $nivel }}/{{ $tipo }}/{{ $e->categoria }}">
                                                        <i class="fa fa-medkit"></i>
                                                      </a>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($e->estado == '1')
                                                          <a class="btn btn-success" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id; ?>,<?php echo $e->estado; ?>)">
                                                            <i class="fa fa-thumbs-up"></i>
                                                          </a>
                                                        @else
                                                          <a class="btn btn-danger" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id; ?>,<?php echo $e->estado; ?>)">
                                                            <i class="fa fa-thumbs-down"></i>
                                                          </a>
                                                        @endif
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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modalEditCategoria">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Categoría en RUA</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="ol-12 col-sm-12 col-md-6 col-lg-9 col-xl-9 form-group">
                <label for="id_categoria_editar">Categoría</label>
                <select class="form-control" name="id_categoria_editar" id="id_categoria_editar">
                  <option value="0">-Seleccionar-</option>
                  @foreach ($categorias as $f)
                      <option value="{{ $f->id }}">{{ $f->nombre }}</option>
                  @endforeach
                </select>
              </div>
              <div class="ol-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                <label for="orden_editar">Orden</label>
                <input type="text" class="form-control input-sm" name="orden_editar" id="orden_editar"  autocomplete="off">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="btnEditCategoria">GUARDAR</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
          </div>
        </div>
      </div>
    </div>

    @include('frontend.includes.footer')
@endsection
