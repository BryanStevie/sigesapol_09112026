@extends('frontend.layouts.master')
@section('renderjs')
<style>
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
</style>

<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });

    function actualizar_estado(id, estado) {
        if (estado == "0") {
            bootbox.confirm("¿Desea desactivar el registro seleccionado?", function(result) {
                if (result == true) {
                    $.ajax({
                        url: '/corredores/desactivar/' + id,
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            });
        } else if (estado == "1") {
            bootbox.confirm("¿Desea activar el registro seleccionado?", function(result) {
                if (result == true) {
                    $.ajax({
                        url: '/corredores/activar/' + id,
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            });
        }
    }
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title">Corredores</h3>
            </div>
            <div class="panel-body">
                
                <a href="/corredores/create" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo </a>
                
                <!-- <br /><br /> -->
                <div class="table-responsive">

                    <table class="table" id="example">
                        <thead>
                            <tr class="filters">
                                <th>#</th>
                                <th>Corredor</th>
                                <th>Establecimiento</th>
                                <th>Estado</th>
                                <th>Editar</th>
                                <th>Desactivar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;?>
                            @foreach($corredores as $corredor)
                            <?php $i++; ?>
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$corredor->nombre}}</td>
                                <td>{{$corredor->establecimiento}}</td>
                                <td>{{$corredor->estado=='1' ? 'Activo': 'Inactivo'}}</td>
                                <td><a href="/corredores/edit/{{$corredor->id}}" class="btn btn-sm btn-success">Editar</a></td>
                                <td>
                                    @if($corredor->estado=='1')
                                    <a onclick="actualizar_estado({{$corredor->id}},'0')" class="btn btn-sm btn-warning">Deshabilitar</a>
                                    @else
                                    <a onclick="actualizar_estado({{$corredor->id}},'1')" class="btn btn-sm btn-warning">Habilitar</a>
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
@include('frontend.includes.footer')
@endsection