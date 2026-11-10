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
            bootbox.confirm("¿Desea desactivar el Corredor Detalle seleccionado?", function(result) {
                if (result == true) {
                    // return false;
                    $.ajax({
                        url: '/corredores_detalle_actualizar_estado/' + id + '/' + estado,
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            });
        } else if (estado == "1") {
            bootbox.confirm("¿Desea activar el Corredor Detalle seleccionado?", function(result) {
                if (result == true) {
                    // return false;
                    $.ajax({
                        url: '/corredores_detalle_actualizar_estado/' + id + '/' + estado,
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            });
        }
    }

    function liberar_modulo(id_corredor_detalle){
        $.ajax({
            url: '/liberar_modulo/' + id_corredor_detalle,
            success: function(result) {
                location.reload();
            }
        });
    }
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title">Corredor Detalle</h3>
            </div>
            <div class="panel-body">
                
                <a href="/corredor_detalle_create" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo </a>
                
                <!-- <br /><br /> -->
                <div class="table-responsive">

                    <table class="table" id="example">
                        <thead>
                            <tr class="filters">
                                <th>#</th>
                                <th>Corredor</th>
                                <th>Usuario</th>
                                <th>Servicio</th>
                                <th>Módulo</th>
                                <th>Estado</th>
                                <th>Editar</th>
                                <th>Desactivar</th>
                                <th>Liberar Módulo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;?>
                            @foreach($listado as $r)
                            <?php $i++; ?>
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$r->nombre}}</td>
                                <td>{{$r->usuario}}</td>
                                <td>{{$r->serv_ticket}}</td>
                                <td>{{$r->nom_modulo}}</td>
                                <td>{{$r->des_estado}}</td>
                                <td><a href="/corredor_detalle_edit/{{$r->id}}" class="btn btn-sm btn-success">Editar</a></td>
                                <td>
                                    @if($r->estado == '1')
                                    <a onclick="actualizar_estado({{$r->id}},'0')" class="btn btn-sm btn-warning">Deshabilitar</a>
                                    @else
                                    <a onclick="actualizar_estado({{$r->id}},'1')" class="btn btn-sm btn-warning">Habilitar</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($r->usuario == '')
                                    <a class="btn btn-sm btn-danger" disabled >Liberar<a> 
                                    @else
                                    <a onclick="liberar_modulo({{$r->id}})" class="btn btn-sm btn-danger">Liberar<a> 
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