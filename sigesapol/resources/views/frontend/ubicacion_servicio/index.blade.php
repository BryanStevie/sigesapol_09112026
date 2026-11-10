@extends('frontend.layouts.master')

@section('renderjs')
<script>
    $(document).ready(function () {
        
        $('.select2').select2();

        // Mostrar modal de clase
        $('#btnNuevaClase').click(function () {
            $('#modalClase').modal('show');
        });

        // Mostrar modal de tipo
        $('#btnNuevoTipo').click(function () {
            $('#modalTipo').modal('show');
        });

        // Mostrar listado de clases
        $('#btnListarClases').click(function() {
            $('#panelClases').show();
            $('#panelTipos').hide();
            $('#btnNuevaClase').show();
            $('#btnNuevoTipo').hide();
        });

        // Mostrar listado de tipos
        $('#btnListarTipos').click(function() {
            // Cargar todos los tipos
            $.get('/ubicacion_servicio/listar_todos_tipos', function (data) {
                var html = '';
                $.each(data, function (i, item) {
                    html += '<tr>';
                    html += '<td>' + (i + 1) + '</td>';
                    
                    html += '<td>' + item.clase.nombre_clase + '</td>';
                    
                    html += '<td>' + item.nombre_tipo + '</td>';
                    html += '<td>';
                    html += '<button class="btn btn-warning btn-sm" onclick="editarTipo(' + item.id_tipo + ', \'' + item.nombre_tipo + '\', ' + item.id_clase + ')"><i class="fa fa-pencil"></i></button> ';
                    if(item.estado == 1) {
                        html += '<button class="btn btn-danger btn-sm" onclick="eliminarTipo(' + item.id_tipo + ')"><i class="fa fa-times"></i></button>';
                    } else {
                        html += '<button class="btn btn-success btn-sm" onclick="activarTipo(' + item.id_tipo + ')"><i class="fa fa-check"></i></button>';
                    }
                    
                    html += '</td>';
                    html += '</tr>';
                });
                $('#tablaTipos tbody').html(html);
                $('#panelTipos').show();
                $('#panelClases').hide();
                $('#btnNuevaClase').hide();
                $('#btnNuevoTipo').show();
            });
        });

        // Cargar tipos por clase seleccionada
        $('.btn-listar-tipos').click(function () {
            var id_clase = $(this).data('id');
            $.get('/ubicacion_servicio/listar_tipos/' + id_clase, function (data) {
                var html = '';
                $.each(data, function (i, item) {
                    html += '<tr>';
                    html += '<td>' + (i + 1) + '</td>';
                    html += '<td>' + item.nombre_tipo + '</td>';
                    html += '<td>' + item.clase.nombre_clase + '</td>';
                    html += '<td>';
                    html += '<button class="btn btn-warning btn-sm" onclick="editarTipo(' + item.id_tipo + ', \'' + item.nombre_tipo + '\', ' + item.id_clase + ')"><i class="fa fa-pencil"></i></button> ';
                    html += '<button class="btn btn-danger btn-sm" onclick="eliminarTipo(' + item.id_tipo + ')"><i class="fa fa-times"></i></button>';
                    html += '</td>';
                    html += '</tr>';
                });
                $('#tablaTipos tbody').html(html);
                $('#panelTipos').show();
                $('#panelClases').hide();
                $('#btnNuevaClase').hide();
                $('#btnNuevoTipo').show();
            });
        });
    });

    function editarClase(id, nombre) {
        $('#modalEditClase input[name="id_clase"]').val(id);
        $('#modalEditClase input[name="nombre_clase"]').val(nombre);
        $('#modalEditClase').modal('show');
    }

    function editarTipo(id, nombre, id_clase) {
        $('#modalEditTipo input[name="id_tipo"]').val(id);
        $('#modalEditTipo input[name="nombre_tipo"]').val(nombre);
        $('#modalEditTipo select[name="id_clase"]').val(id_clase).trigger('change');
        $('#modalEditTipo').modal('show');
    }

   function eliminarClase(id) {
    if (confirm('¿Deseas eliminar esta clase?')) {
        $.ajax({
            url: '/ubicacion_servicio/clase/delete/' + id,
            type: 'GET', // o 'POST'/'DELETE' según lo que espere tu backend
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    alert('La clase se eliminó correctamente');
                    location.reload();
                } else {
                    alert('No se pudo eliminar la clase');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Ocurrió un error al intentar eliminar el tipo de programación');
            }
        });
    }
}

function activarClase(id) {
    if (confirm('¿Deseas activar la clase?')) {
        $.ajax({
            url: '/ubicacion_servicio/clase/delete/' + id,
            type: 'GET', // o 'POST'/'DELETE' según lo que espere tu backend
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    alert('La clase se activó correctamente');
                    location.reload();
                } else {
                    alert('No se pudo activar la clase');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Ocurrió un error al intentar activar el tipo de programación');
            }
        });
    }
}


function eliminarTipo(id) {
    if (confirm('¿Deseas eliminar este tipo de programación?')) {
        $.ajax({
            url: '/ubicacion_servicio/tipo/delete/' + id,
            type: 'GET', // o 'POST'/'DELETE' según lo que espere tu backend
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    alert('El tipo de programación se eliminó correctamente');
                    location.reload();
                } else {
                    alert('No se pudo eliminar el tipo de programación');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Ocurrió un error al intentar eliminar el tipo de programación');
            }
        });
    }
}

function activarTipo(id) {
    if (confirm('¿Deseas Activar este tipo de programación?')) {
        $.ajax({
            url: '/ubicacion_servicio/tipo/delete/' + id,
            type: 'GET', // o 'POST'/'DELETE' según lo que espere tu backend
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    alert('El tipo de programación se activó correctamente');
                    location.reload();
                } else {
                    alert('No se pudo activar el tipo de programación');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Ocurrió un error al intentar activar el tipo de programación');
            }
        });
    }
}

</script>
@endsection

@section('content')
<div class="container" style="margin-top:20px">
    <div class="row">
        <!-- Panel izquierdo -->
        <div class="col-md-3">
            <div class="panel panel-success">
                <div class="panel-heading">UBICACION DEL SERVICIO</div>
                <div class="panel-body">
                    <button id="btnListarClases" class="btn btn-default btn-block">Listado de Clases</button>
                    <button id="btnNuevaClase" class="btn btn-success btn-block" style="display:none"><i class="fa fa-plus"></i> Nuevo</button>
                    <hr>
                    <button id="btnListarTipos" class="btn btn-default btn-block">Listado de Tipo</button>
                    <button id="btnNuevoTipo" class="btn btn-success btn-block" style="display:none"><i class="fa fa-plus"></i> Nuevo</button>
                </div>
            </div>
        </div>

        <!-- Panel derecho -->
        <div class="col-md-9">
            <!-- Panel clases -->
            <div id="panelClases" style="display:none">
                <div class="panel panel-info">
                    <div class="panel-heading">Listado de Categorías Seleccionadas</div>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped" style="font-size: 13px">
                            <thead>
                                <tr>
                                    <th style="width: 50px">N°</th>
                                    <th>CLASE</th>
                                    <th style="width: 100px">Tipos</th>
                                    <th style="width: 100px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clases as $i => $clase)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $clase->nombre_clase }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm btn-listar-tipos" data-id="{{ $clase->id_clase }}">
                                            <i class="fa fa-list"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editarClase({{ $clase->id_clase }}, '{{ $clase->nombre_clase }}')">
                                            <i class="fa fa-pencil"></i>
                                        </button>
 @if($clase->estado == 1)
                <!-- Botón rojo (eliminar) si estado es 1 -->
                <button class="btn btn-danger btn-sm" onclick="eliminarClase({{ $clase->id_clase }})">
                    <i class="fa fa-times"></i>
                </button>
            @else
                <!-- Botón verde (check) si estado NO es 1 -->
                <button class="btn btn-success btn-sm" onclick="activarClase({{ $clase->id_clase }})">
                    <i class="fa fa-check"></i>
                </button>
            @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Panel tipos -->
            <div id="panelTipos" style="display:none">
                <div class="panel panel-warning">
                    <div class="panel-heading">Tipos de Programación</div>
                    <div class="panel-body">
                        <table class="table table-bordered" id="tablaTipos">
                            <thead>
                                <tr>
                                    <th style="width: 50px">N°</th>
                                    <th>Clase</th>
                                    <th>Tipo de Programación</th>
                                    
                                    <th style="width: 100px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- AJAX aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Clase -->
<div class="modal fade" id="modalClase">
    <div class="modal-dialog">
        <form action="{{ route('ubicacion_servicio.clase.store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Registrar Nueva Clase</h4>
                </div>
                <div class="modal-body">
                    <label>Nombre de Clase</label>
                    <input type="text" name="nombre_clase" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tipo -->
<div class="modal fade" id="modalTipo">
    <div class="modal-dialog">
        <form action="{{ route('ubicacion_servicio.tipo.store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Registrar Nuevo Tipo</h4>
                </div>
                <div class="modal-body">
                    <label>Clase</label>
                    <select name="id_clase" class="form-control select2" required>
                        <option value="">Seleccione</option>
                        @foreach($clases as $c)
                            <option value="{{ $c->id_clase }}">{{ $c->nombre_clase }}</option>
                        @endforeach
                    </select>
                    <br>
                    <label>Nombre de Tipo</label>
                    <input type="text" name="nombre_tipo" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditClase">
    <div class="modal-dialog">
        <form action="{{ route('ubicacion_servicio.clase.update') }}" method="POST">
            {!! csrf_field() !!}
            <input type="hidden" name="id_clase">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">Editar Clase</h4>
                </div>
                <div class="modal-body">
                    <label>Nombre de Clase</label>
                    <input type="text" name="nombre_clase" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditTipo">
    <div class="modal-dialog">
        <form action="{{ route('ubicacion_servicio.tipo.update') }}" method="POST">
            {!! csrf_field() !!}
            <input type="hidden" name="id_tipo">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">Editar Tipo</h4>
                </div>
                <div class="modal-body">
                    <label>Clase</label>
                    <select name="id_clase" class="form-control select2" required>
                        <option value="">Seleccione</option>
                        @foreach($clases as $c)
                            <option value="{{ $c->id_clase }}">{{ $c->nombre_clase }}</option>
                        @endforeach
                    </select>
                    <br>
                    <label>Nombre de Tipo</label>
                    <input type="text" name="nombre_tipo" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@include('frontend.includes.footer')
@endsection