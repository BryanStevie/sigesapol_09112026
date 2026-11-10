@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $("#divSubCons2").show();
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
    function getPiso(){
        
        var id_pabellon = $('#id_pabellon').val();
        $.ajax({
            url: '/obtener_piso_por_idpabellon/' + id_pabellon,
            success: function(result){                    
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
                });                 
                $('#id_piso').html(newOption);
                //$("#turno").select2({max_selected_options: 4});
            }
        });
    }

    function getPrioridad(){
        var nombre_atencion = $('#nombre_atencion').val();
        
        if(nombre_atencion=='EMERGENCIA'){
            $("#divSubCons").show();
            $("#divSubCons2").show();
            $.ajax({
                url: '/obtener_prioridad/',
                success: function(result){                    
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    $(result).each(function (ii, oo) {
                        newOption += "<option value='"+oo.id+"'>"+oo.prioridad+"</option>";
                    });                 
                    $('#id_prioridad').html(newOption);
                }
            });

        }
        else{ //HOSPITALIZACION
            $("#divSubCons").hide();
            $("#divSubCons2").hide();
            getConsultorio();
        }
    }

    function getConsultorio(){
        valor = 2;
            $.ajax({
                url: '/obtener_servicios_atencion/' + valor,
                success: function(result){                    
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    $(result).each(function (ii, oo) {
                        newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
                    });                 
                    $('#id_consultorio').html(newOption);
                }
            });
    }

    function getTopico(){
        var id_prioridad = $('#id_prioridad').val();
            $.ajax({
                url: '/get_topico_por_id_prioridad/' + id_prioridad,
                success: function(result){                    
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    $(result).each(function (ii, oo) {
                        newOption += "<option value='"+oo.id_servicio+"'>"+oo.descripcion+"</option>";
                    });                 
                    $('#id_consultorio').html(newOption);
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
                    <li><a href="/all_asignacion_servicio"><i class="fa fa-table fa-fw"></i> Servicio</a></li>
                    <li class="active"><a href="/create_asignacion_servicio"><i class="fa fa-plus"></i> Nuevo Servicio </a></li>
                    <li><a href="/all_cuarto/"><i class="fa fa-table fa-fw"></i> Cuarto</a></li>
                    <li><a href="/create_cama/"><i class="fa fa-table fa-fw"></i> Cama</a></li>
                </ul>
            </div>
        </div>        
        <div class="col-md-10">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar Servicio</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_asignacion_servicio') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" class="form-control" name="id"  value="{{$asignacion->id}}"> 
                        <input type="hidden" name="id_establecimiento" value="<?php echo $id_establecimiento ?>">

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Atención</label>
                            <div class="col-lg-10">
                                <select id="nombre_atencion" name="nombre_atencion" onchange="getPabellon()" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                    <option value="HOSPITALIZACION" <?php if ($asignacion->nombre_atencion == 'HOSPITALIZACION') echo "selected='selected'"; ?> >HOSPITALIZACION</option>
                                    <option value="EMERGENCIA" <?php if ($asignacion->nombre_atencion == 'EMERGENCIA') echo "selected='selected'"; ?>>EMERGENCIA</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Pabellon</label>
                            <div class="col-lg-10">
                                <select id="id_pabellon" name="id_pabellon" class="form-control" onchange="getPiso()" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($pabellon as $e)
                                    <option value="{{$e->id}}" <?php if ($asignacion->id_pabellon == $e->id) echo "selected='selected'"; ?>>{{$e->nombre}}</option>
                                    @endforeach                                            
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Piso</label>
                            <div class="col-lg-10">
                                <!--select id="id_piso" name="id_piso" class="form-control" onchange="getPrioridad()" required=""-->
                                <select id="id_piso" name="id_piso" class="form-control" onchange="getConsultorio()" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($piso as $e)
                                    <option value="{{$e->id}}" <?php if ($asignacion->id_piso == $e->id) echo "selected='selected'"; ?>>{{$e->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if($asignacion->nombre_atencion =='EMERGENCIA')
                            <!--div class="form-group" id="divSubCons2" style="display:none">
                                <label class="col-lg-2 control-label">Prioridad</label>
                                <div class="col-lg-10">
                                    <select id="id_prioridad" name="id_prioridad" onchange="getTopico()" class="form-control" required="">
                                        @foreach($prioridades as $e)
                                        <option value="{{$e->id}}" <?php if ($asignacion->id_prioridad == $e->id) echo "selected='selected'"; ?>>{{$e->prioridad}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div-->
                        @else
                            <!--div class="form-group" id="divSubCons" style="display:none">
                                <label class="col-lg-2 control-label">Prioridad</label>
                                <div class="col-lg-10">
                                    <select id="id_prioridad" name="id_prioridad" onchange="getTopico()" class="form-control" required="">
                                    </select>
                                </div>
                            </div-->
                        @endif
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Servicio</label>
                            <div class="col-lg-10">
                                <select id="id_consultorio" name="id_consultorio" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($consultorio as $e)
                                    <option value="{{$e->id}}" <?php if ($asignacion->id_consultorio == $e->id) echo "selected='selected'"; ?>>{{$e->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_asignacion_servicio" class="btn btn-danger">Cancelar</a>
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