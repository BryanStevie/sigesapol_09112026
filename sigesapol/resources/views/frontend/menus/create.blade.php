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
                url: 'cargarnombre/' + cmbEst,
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
            $select.append('<option value="' + oo.nombre + '">' + oo.nombre + '</option>')
        });
    }

    $("#id_menu").select2({
            width: 'resolve'
        });
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="/all_menu"><i class="fa fa-table fa-fw"></i> Menu</a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Nuevo Menu</div>                    
                </div>
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('store_menu') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Menu Principal</label>
                            <div class="col-lg-10">
        						<select class="form-control" name="id_menu" id="id_menu" required="" placeholder="Seleccionar un Menu" style="width: 100%">
                                            <option value="0">Elemento de menú raíz</option>
                                            @foreach($menus as $m)
                                            <option value="{{$m->id}}">{{$m->nombremenu}}</option>
                                            @endforeach
                                </select>
                            </div>
                        </div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Nombre Menu</label>
							<div class="col-lg-10">
                            	<input name="nombremenu" type="text" class="form-control" required="" placeholder="Nombre del Menu" onkeypress="return solo_letra(event)">             
							</div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Url</label>
                            <div class="col-lg-10">
                                <input name="url" type="text" class="form-control" required="" placeholder="Ingrese ruta" >             
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Orden</label>
                            <div class="col-lg-2">
                                <input name="orden" type="text" class="form-control" required="" placeholder="Orden del Menu" onkeypress="return IsNumber(event)" style="text-align:right">
                            </div>
                        </div>
                        <div style="margin-top:10px" class="form-group">
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