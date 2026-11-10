@extends('frontend.layouts.master')
@section('renderjs')
<script>
        
    $(document).ready(function () {
        $cmbEst = $("#frmfarmacia").find("#id_establecimiento");
        $cmbEst.select2();
        $cmbNom = $("#frmfarmacia").find("#nombre");
        $cmbNom.select2();
        $cmbEst.change(function () {
            $this = $(this);
            cmbEst = $cmbEst.val();
            option = {
                url: '/cargarnombre/' + cmbEst,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                cargarComboNombre($cmbNom, data);
                $cmbNom.val(null).trigger("change");
                document.getElementById("nombre").disabled = false;
            });
        });
    });
    function cargarComboNombre($select, data) {
        $select.html('');
        $(data).each(function (ii, oo) {
            $select.append('<option value="" required="required" >' + "Selecciona el nombre de la Farmacia"+ '</option>')
            $select.append('<option value="' + "Farmacia del "+ oo.nombre + '">' + "Farmacia del "+ oo.nombre + '</option>')
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
	
	
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_cama"><i class="fa fa-table fa-fw"></i> Cama </a></li>
                <li class="active"><a href="/create_cama"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
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
                            <label class="col-lg-2 control-label">Pabellon</label>
                            <div class="col-lg-10">
                                <select id="id_pabellon" name="id_pabellon" class="form-control" onchange="getPiso()">
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
                                <select id="id_piso" name="id_piso" class="form-control" onchange="getConsultorio()">
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
                                <select id="id_asignacion_servicio" name="id_asignacion_servicio" class="form-control" onchange="getCuarto()">
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
                                <select id="id_cuarto" name="id_cuarto" class="form-control">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($cuarto as $e)
                                    <option value="{{$e->id}}" <?php if ($cama->id_cuarto == $e->id) echo "selected='selected'"; ?>>{{$e->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Cama</label>
                            <div class="col-lg-10">
                                <input name="nombre" type="text" class="form-control" required="" value="<?php echo $cama->nombre ?>" placeholder="Nombre de la Cama" autocomplete="OFF">
                            </div>
                        </div>


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