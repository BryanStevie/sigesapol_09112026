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
    
    function getConsultorio(){
        
        var id_piso = $('#id_piso').val();
        
        $.ajax({
            url: '/obtener_consultorio_por_idpiso/' + id_piso,
            success: function(result){                    
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
                });                 
                $('#id_asignacion_servicio').html(newOption);
                //$("#turno").select2({max_selected_options: 4});
            }
        });
    }
	
	function getCuarto(){
        
        var id_asignacion_servicio = $('#id_asignacion_servicio').val();
        
        $.ajax({
            url: '/obtener_cuarto_por_idasignacionservicio/' + id_asignacion_servicio,
            success: function(result){                    
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
                });                 
                $('#id_cuarto').html(newOption);
            }
        });
    }

    function getUpss(){
        var id_asignacion_servicio = $('#id_asignacion_servicio').val();

        $.ajax({
            url: '/obtener_cama_upsses/' + id_asignacion_servicio,
            success: function(result){                    
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.codigo+"'>"+oo.codigo+ " - " +oo.descripcion_upss+"</option>";
                });                 
                $('#codigo_upss').html(newOption);
            }
        });
        
    }

</script>
<script src='../../funciones/function.js'></script> 
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
                    <li><a href="/all_cuarto/"><i class="fa fa-table fa-fw"></i> Cuarto</a></li>
                    <li class="active"><a href="/create_cama/"><i class="fa fa-table fa-fw"></i> Nueva Cama</a></li>
                    <li><a href="/all_cama/1"><i class="fa fa-plus"></i> Cama Emergencia</a></li>
                    <li><a href="/all_cama/2"><i class="fa fa-plus"></i> Cama Hospitalización</a></li>
                </ul>
            </div>
        </div>        
        <div class="col-md-10">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_cama') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" class="form-control" name="id"  value="{{$cama->id}}"> 
                        <input type="hidden" name="id_establecimiento" value="<?php echo $id_establecimiento ?>">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Atención</label>
                            <div class="col-lg-10">
                                <select id="nombre_atencion" name="nombre_atencion" onchange="getPabellon()" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                    <option value="HOSPITALIZACION" <?php if ($cama->nombre_atencion == 'HOSPITALIZACION') echo "selected='selected'"; ?> >HOSPITALIZACION</option>
                                    <option value="EMERGENCIA" <?php if ($cama->nombre_atencion == 'EMERGENCIA') echo "selected='selected'"; ?>>EMERGENCIA</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Pabellon</label>
                            <div class="col-lg-10">
                                <select id="id_pabellon" name="id_pabellon" class="form-control" onchange="getPiso()" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($pabellon as $e)
                                    <option value="{{$e->id}}" <?php if ($cama->id_pabellon == $e->id) echo "selected='selected'"; ?>>{{$e->nombre}}</option>
                                    @endforeach     
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Piso</label>
                            <div class="col-lg-10">
                                <select id="id_piso" name="id_piso" class="form-control" onchange="getConsultorio()" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($piso as $e)
                                    <option value="{{$e->id}}" <?php if ($cama->id_piso == $e->id) echo "selected='selected'"; ?>>{{$e->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Servicio</label>
                            <div class="col-lg-10">
                                <select id="id_asignacion_servicio" name="id_asignacion_servicio" class="form-control" onchange="getCuarto(); getUpss();" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($consultorio as $e)
                                    <option value="{{$e->id}}" <?php if ($cama->id_asignacion_servicio == $e->id) echo "selected='selected'"; ?>>{{$e->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Cuarto</label>
                            <div class="col-lg-10">
                                <select id="id_cuarto" name="id_cuarto" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($cuarto as $e)
                                    <option value="{{$e->id}}" <?php if ($cama->id_cuarto == $e->id) echo "selected='selected'"; ?>>{{$e->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <?php if ($id_establecimiento == 76): ?>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo</label>
                            <div class="col-lg-10">
                                <select id="tipo" name="tipo" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($tipos as $e)
                                    <option value="{{$e->id}}" <?php if ($cama->tipo == $e->id) echo "selected='selected'"; ?> >{{$e->descripcion}}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Situación</label>
                            <div class="col-lg-10">
                                <select id="situacion" name="situacion" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($situaciones as $e)
                                    <option value="{{$e->id}}" <?php if ($cama->situacion == $e->id) echo "selected='selected'"; ?> >{{$e->descripcion}}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Clase</label>
                            <div class="col-lg-10">
                                <select id="clase" name="clase" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($clase_camas as $e)
                                    <option value="{{$e->id}}" <?php if ($cama->id_clase_cama == $e->id) echo "selected='selected'"; ?> >{{$e->descripcion}}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Cama</label>
                            <div class="col-lg-10">
                                <input name="nombre" id="nombre" onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'nombre');"  type="text" class="form-control" required="" value="<?php echo $cama->nombre ?>" placeholder="Nombre de la Cama" autocomplete="OFF">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">UPSS</label>
                            <div class="col-lg-10">
                                <select id="codigo_upss" name="codigo_upss" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($upsses as $up)
                                    <option value="{{$up->codigo}}" <?php if ($cama->camas_upsses == $up->codigo) echo "selected='selected'"; ?>>{{$up->codigo}} - {{$up->descripcion_upss}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        

                        @if(Auth::user()->flagfiscalizador==1)
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Disponible</label>
                            <div class="col-lg-10">                        
                                <input type="checkbox" value="1" name="estado" <?php if($cama->estado == 1)echo '  checked="checked"';?>/>
                            </div>
                        </div>
                        @endif


                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_cama" class="btn btn-danger">Cancelar</a>
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