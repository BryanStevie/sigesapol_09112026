@extends('frontend.layouts.master')
@section('renderjs')
<script>
    function getPabellon(){
        
        var nombre_atencion = $('#nombre_atencion').val();
        $.ajax({
            url: '/obtener_pabellon/' + nombre_atencion,
            success: function(result){                    
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
                });                 
                $('#id_pabellon').html(newOption);
                //$("#turno").select2({max_selected_options: 4});
            
            }
        }); 
    }
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-sm-2">
            <div class="col-sm-12 pull-right">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/all_pabellon/"><i class="fa fa-table fa-fw"></i> Pabell&oacute;n</a></li>
                    <li><a href="/all_piso"><i class="fa fa-table fa-fw"></i> Pisos</a></li>
                    <li class="active"><a href="/create_piso"><i class="fa fa-plus"></i> Nuevo </a></li>
                    <li><a href="/all_asignacion_servicio/"><i class="fa fa-table fa-fw"></i> Servicio</a></li>
                    <li><a href="/all_cuarto/"><i class="fa fa-table fa-fw"></i> Cuarto</a></li>
                    <li><a href="/create_cama/"><i class="fa fa-table fa-fw"></i> Cama</a></li>
                </ul>
            </div>
        </div>        
        <div class="col-md-10">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Nuevo Piso</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('store_piso') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_establecimiento" value="<?php echo $id_establecimiento ?>">

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Atención</label>
                            <div class="col-lg-10">
                                <select id="nombre_atencion" name="nombre_atencion" onchange="getPabellon()" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                    <option value="HOSPITALIZACION">HOSPITALIZACION</option>
                                    <option value="EMERGENCIA">EMERGENCIA</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Pabellon</label>
                            <div class="col-lg-10">
                                <select id="id_pabellon" name="id_pabellon" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Piso</label>
                            <div class="col-lg-10">
                                <input name="nombre" type="text" class="form-control" required="" placeholder="Nombre del Piso" autocomplete="OFF">
                            </div>
                        </div>
                        <!--<div style="margin-bottom: 25px">
                            <select id="nombre" name="nombre" class="form-control" tabindex="11"  style="margin-bottom: 10px" disabled="true" required="">
                                <option value=""  selected style="color: #888888">Selecionar nombre de la Farmacia</option>      
                            </select>
                        </div>-->                        
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                            </div>
                        </div> 
                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection