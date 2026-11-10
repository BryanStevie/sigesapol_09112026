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
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_cama_emergencia"><i class="fa fa-table fa-fw"></i> Cama de Emergencia </a></li>
                <li class="active"><a href="/create_cama_emergencia"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar {{$cama->nombre}}</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_cama_emergencia') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" class="form-control" name="id"  value="{{$cama->id}}"> 
						<input type="hidden" name="id_establecimiento" value="<?php echo $id_establecimiento?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Topico</label>
							<div class="col-lg-10">
								<select id="id_topico" name="id_topico" class="form-control" required="">
									<option value="">--Seleccionar--</option>
									@foreach($topico as $e)
									<option value="{{$e->id}}"  <?php if($cama->id_topico == $e->id)echo "selected='selected'";?>>{{$e->nombre}}</option>
									@endforeach                                            
								</select>
							</div>
                        </div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Cama</label>
							<div class="col-lg-10">
                            	<input name="nombre" type="text" class="form-control" value="{{$cama->nombre}}" required="" placeholder="Nombre de la cama">             
							</div>
                        </div>
                        
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_cama_emergencia" class="btn btn-danger">Cancelar</a>
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