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
            bootbox.confirm("¿Desea desactivar el Totem seleccionado?", function(result) {
                if (result == true) {
                    // return false;
                    $.ajax({
                        url: '/totem_actualizar_estado/' + id + '/' + estado,
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            });
        } else if (estado == "1") {
            bootbox.confirm("¿Desea activar el Totem seleccionado?", function(result) {
                if (result == true) {
                    // return false;
                    $.ajax({
                        url: '/totem_actualizar_estado/' + id + '/' + estado,
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
                <h3 class="panel-title">Totems</h3>
            </div>
            <div class="panel-body">
                
                <a href="/totem_create" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo </a>
                
                <!-- <br /><br /> -->
                <div class="table-responsive">

                    <table class="table" id="example">
                        <thead>
                            <tr class="filters">
                                <th>#</th>
                                <th>Establecimiento</th>
                                <th>Corredor</th>
                                <th>Descripción</th>
                                <th>N° IP</th>
                                <th>Servicios</th>
                                <th>Estado</th>
                                <th>Editar</th>
                                <th>Desactivar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;?>
                            @foreach($listado as $r)
                            <?php $i++; ?>
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$r->establecimiento}}</td>
                                <td>{{$r->corredor}}</td>
                                <td>{{$r->nombre}}</td>
                                <td>{{$r->ip}}</td>
                                <td>{{$r->servicios}}</td>
                                <td>{{$r->des_estado}}</td>
                                <td><a href="/totem_edit/{{$r->id}}" class="btn btn-sm btn-success">Editar</a></td>
                                <td>
                                    @if($r->id_estado == 1)
                                    <a onclick="actualizar_estado({{$r->id}},'0')" class="btn btn-sm btn-warning">Deshabilitar</a>
                                    @else
                                    <a onclick="actualizar_estado({{$r->id}},'1')" class="btn btn-sm btn-warning">Habilitar</a>
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