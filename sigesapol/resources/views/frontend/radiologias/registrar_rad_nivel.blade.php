@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .tr-desactivar {
        /*text-decoration: line-through;*/
        background-color: #ffe6e6;
    }
</style>
<script>

    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) )
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }

    $(document).ready(function () {
		
		$('#id_procedimiento').select2();

		$("#btnReg").on("click", function () {
			
			if ($("#id_procedimiento").val() == "") {
				bootbox.alert("Falta seleccionar el Procedimiento de Radiología");
				return false;
			}
			
			$("#frmReceta").submit();
			
		});
				
    });

    $(document).ready(function () {
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
    });
	//==============================================================================================
    function desactivar(id, estado) {
        let accion = (estado == 0) ? "activar" : "desactivar";

        if (estado == 1) {
            $.ajax({
                url: '/listar_examenes_relacionados/' + id,
                success: function(data) {
                    let mensaje = "";

                    if (data.cantidad > 0) {
                        mensaje += '<p>Hay <span style="font-weight:bold; font-size:15px;">' + data.cantidad + '</span> exámenes solicitados con este id</p>'
                                + '<p style="color:red; margin-top:10px;">'
                                + ' Al desactivar este examen, los registros vinculados podrían verse afectados en RUA, reportes y auditorías.<br>'
                                + '<span style="font-size:17px;font-weight:bold;">Toda consecuencia de esta acción será responsabilidad del usuario.</span>'
                                + '</p>';
                    }

                    mostrarConfirmacion(id, estado, accion, mensaje);
                }
            });
        } else {
            mostrarConfirmacion(id, estado, accion, "");
        }
    }

    function mostrarConfirmacion(id, estado, accion, mensajeExtra) {
        let mensaje = ''
            + '<div class="form-group">'
            + '<label>Motivo de ' + accion + ':</label>'
            + '<input type="text" class="form-control" id="motivo_desactivacion" placeholder="Ingrese el motivo" required>'
            + '</div>'
            + (mensajeExtra ? mensajeExtra : "")
            + '<p>¿Está seguro que desea <b>' + accion + '</b> este examen?</p>';

        bootbox.confirm({
            message: mensaje,
            buttons: {
                confirm: {
                    label: 'Sí, continuar',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'Cancelar',
                    className: 'btn-danger'
                }
            },
            callback: function(result) {
                if (result) {
                    let motivo = $('#motivo_desactivacion').val().trim();
                    if (motivo === "") {
                        Swal.fire({
                            icon: "warning",
                            title: "Motivo requerido",
                            text: "Debe ingresar un motivo para " + accion + " el examen.",
                            confirmButtonText: "Entendido"
                        });
                        return false;
                    }
                    $.ajax({
                        url: '/desactivar_examen_niv_cat_rad/' + id + '/' + estado,
                        type: 'POST',
                        data: { motivo: motivo },
                        success: function(response) {
                            location.reload();
                            setTimeout(function() {
                                Swal.fire({
                                    icon: "success",
                                    title: "Estado actualizado",
                                    text: response.mensaje || "Se cambió el estado correctamente"
                                });
                            }, 500);

                        }
                    });
                }
            }
        });
    }
    
    $(document).ready(function() {
        $(document).on('click', 'a[data-target="#modalConfig"]', function() {
            //console.log('click editar -> data-id:', $(this).data('id'), 'data-descr:', $(this).data('descr'));
        });

        $('#modalConfig').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');    
            var descr = button.data('descr');

            var modal = $(this);
            modal.find('#radnivl_id').val(id);
            modal.find('#update_descr_radnivl').val(descr);

        });

        $('#modalConfig').on('hidden.bs.modal', function () {
            $(this).find('#radnivl_id, #update_descr_radnivl').val('');
        });

        $('#btnEditarExamenRad').click(function (e) {
            e.preventDefault();
            let descripcion = $('#update_descr_radnivl').val().trim();
            let motivo = $('#motivo').val().trim();

            if (descripcion === "") {
                Swal.fire({
                    icon: "warning",
                    title: "Falta la descripción",
                    text: "Debe ingresar la nueva descripción",
                    confirmButtonText: "Entendido"
                });
                $('#update_descr_radnivl').focus();
                return;
            }

            if (motivo === "") {
                Swal.fire({
                    icon: "warning",
                    title: "Falta el motivo",
                    text: "Debe ingresar el motivo de la modificación",
                    confirmButtonText: "Entendido"
                });
                $('#motivo').focus();
                return;
            }
            $.ajax({
                url: "<?php echo url()?>/editar_examen_niv_cat_rad",
                type: "post",
                data: $("#formEditarExamen").serialize(),
                success: function(response) {
                    if (response == "1") {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "El registro fue actualizado correctamente",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                location.reload();
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Hubo un error",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        willClose: () => {
                            location.reload();
                        }
                    });
                }
            });
        });
    });
    
    let examenParams = {};
    //let currentFila = null;

    function eliminar(element) {
        let $el = $(element);

        //currentFila = $el.closest('tr');
        examenParams = {
            id: $el.data('id'),
            tipo: $el.data('tipo'),
            nivel: $el.data('nivel'),
            categoria: $el.data('categoria'),
            consultorio: $el.data('consultorio')
        };

        $('#motivoEliminar').val("");
        $('#modalEliminar').modal('show');
    }

    $(document).on('click', '#btnConfirmarEliminar', function () {
        let motivo = $('#motivoEliminar').val().trim();
        if (motivo === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Debe ingresar un motivo antes de eliminar.',             
                confirmButtonText: "Entendido"
            });
            return;
        }

        $.ajax({
            url: '/eliminar_rad_nivel_tipo_atencion/' 
                + examenParams.id + '/' 
                + examenParams.tipo + '/' 
                + examenParams.nivel + '/' 
                + examenParams.categoria + '/' 
                + examenParams.consultorio,
            type: 'POST',
            data: {
                motivo: motivo,
                _token: "{{ csrf_token() }}"
            },
            success: function () {
                $('#modalEliminar').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Examen eliminado',
                    text: 'Se elimina por: ' + motivo,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    willClose: () => {
                        location.reload();
                    }
                });

                // if (currentFila) {
                //     currentFila.addClass('tr-eliminada');
                //     currentFila.find('td').eq(2).html('<a class="btn btn-danger btn-xs" disabled aria-disabled="true"><i class="fa fa-edit"></i> Editar</a>');
                //     currentFila.find('td').eq(3).html('<a class="btn btn-danger" disabled aria-disabled="true"><i class="fa fa-thumbs-down"></i></a>');
                //     currentFila.find('td').eq(4).html('<a class="btn btn-danger btn-xs" disabled aria-disabled="true"><i class="fa fa-times"></i> Eliminar</a>');
                // }
                if (currentFila) {
                    $('#examen_laboratorio').DataTable().row(currentFila).remove().draw(false);
                }

            },
            error: function () {
                $('#modalEliminar').modal('hide');

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un problema al eliminar el examen.',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        });
    });
    
</script>
<style>
    .etiqueta{margin-top: 5px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;
    }
    .panel-heading div span{
        margin-left:5px;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">RAD - {{$descripcion_tipo}} - {{$descripcion_nivel}} - {{$categoria->nombre}} </h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/add_categoria_radiologias/{{ $id_consultorio }}">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Agregar Examen de Radiología</h3>                    
                                </div>
                                <?php //exit();?>
                                <div class="panel-body">
                                    <form id="frm_transferencia" method="POST" action="{{ route('agregar_rad_nivel_tipo_atencion')}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="<?php echo $tipo ?>">
                                        <input value="<?php echo $nivel ?>" name="id_nivel" id="id_nivel" type="hidden">
                                        <input value="<?php echo $id_categoria ?>" name="id_categoria" id="id_categoria" type="hidden">
                                        <input value="{{$id_consultorio}}" name="id_consultorio" id="id_consultorio" type="hidden">
                                        <div class="col-md-6">
                                            <label>Seleccionar el Exámen</label>
                                            <select class="form-control" name="id_procedimiento" id="id_procedimiento">
                                                <option value="">--Seleccionar--</option>
                                                @foreach($listar_procedimientos_radiologias as $pro)
                                                <option value="<?php echo $pro->id ?>"><?php echo $pro->codigo . " " . $pro->descripcion ?></option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Descripción corta</label>
                                            <input name="descripcion_corta" id="descripcion_corta" type="text" class="form-control" required="" style="text-align:left">
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <input id="btnReg" class="btn btn-success form-control" value="Agregar" type="submit">
                                        </div>
                                    </form>
                                </div>
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
                                            <th>Examen</th>
                                            <th>Descripción Corta</th>
                                            <th>Editar</th>
                                            <th>Estado</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listar_examenes as $p)
                                            <tr data-id="{{ $p->id }}" class="{{ $p->estado == 3 ? 'tr-eliminada' : '' }} {{ $p->estado == 0 ? 'tr-desactivar' : '' }}">
                                                <td>{{ $p->codigo }} - {{ $p->descripcion }}</td>
                                                <td>{{ $p->descripcion_corta }}</td>
                                                <td>
                                                    @if($p->estado == 1)
                                                        <a class="btn btn-success btn-xs" href="#" data-toggle="modal" data-target="#modalConfig" data-id="{{ $p->id }}" data-descr="{{ trim($p->descripcion_corta) }}">
                                                            <i class="fa fa-edit"></i> Editar
                                                        </a>
                                                    @else
                                                        <a class="btn btn-danger btn-xs" disabled><i class="fa fa-edit"></i> Editar</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($p->estado == 1)
                                                        <a class="btn btn-success" href="javascript:void(0)" onclick="desactivar({{ $p->id }}, {{ $p->estado }})">
                                                            <i class="fa fa-thumbs-up"></i>
                                                        </a>
                                                    @elseif($p->estado == 3)
                                                        <a class="btn btn-danger" disabled><i class="fa fa-thumbs-down"></i></a>
                                                    @else
                                                        <a class="btn btn-danger" href="javascript:void(0)" onclick="desactivar({{ $p->id }}, {{ $p->estado }})">
                                                            <i class="fa fa-thumbs-down"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($p->estado == 3)
                                                        <a class="btn btn-danger deleteFilaDiagnostico btn-xs" disabled>
                                                            <i class="fa fa-times"></i> Eliminar
                                                        </a>
                                                    @else
                                                        <a href="#" class="btn btn-danger deleteFilaDiagnostico btn-xs" data-id="{{ $p->id }}" data-tipo="{{ $tipo }}" data-nivel="{{ $nivel }}" data-categoria="{{ $id_categoria }}" data-consultorio="{{ $id_consultorio }}" onclick="event.preventDefault(); eliminar(this)"><i class="fa fa-times"></i> Eliminar</a>
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
<div class="modal fade" id="modalConfig" tabindex="-1" role="dialog" aria-labelledby="modalConfigLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document"> 
    <div class="modal-content">
      <form form class="" method="post" id="formEditarExamen">
        <div class="modal-header">
          <h5 class="modal-title" id="modalConfigLabel">EDITAR - DESCRIPCIÓN CORTA</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id" id="radnivl_id">
          <input type="hidden" value="<?php echo $nivel ?>" name="id_nivel" id="id_nivel">
          <input type="hidden" value="<?php echo $id_categoria ?>" name="id_categoria" id="id_categoria">
          <input type="hidden" value="{{$id_consultorio}}" name="id_consultorio" id="id_consultorio">
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Descripción Nueva:</label>
            <div class="col-lg-12">
                <input type="text" name="update_descr_radnivl" id="update_descr_radnivl" required class="form-control" placeholder="Ingrese la nueva descripción">
            </div>
            <label class="col-lg-4 col-form-label" style="padding-top: 9px;">Motivo:</label>
            <div class="col-lg-12">
                <input type="text" name="motivo" id="motivo" required class="form-control" placeholder="Ingrese el motivo de la modificación a realizar...">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <input id="btnEditarExamenRad" class="btn btn-primary" type="submit" value="Guardar">
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEliminar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Eliminar examen</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <label>Motivo de eliminación:</label>
        <input type="text" id="motivoEliminar" class="form-control">
        <p class="mt-3 text" style="color:red; margin-top:10px; font-size:13px;">Al eliminar este examen, los registros vinculados podrían verse afectados en RUA, reportes y auditorías.</p>
        <p class="mt-3 text" style="color:red; margin-top:10px; font-size:17px;font-weight:bold;">Toda consecuencia de esta acción será responsabilidad del usuario.</p>
        <p class="mt-3 text">¿Está seguro de eliminar este examen?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection