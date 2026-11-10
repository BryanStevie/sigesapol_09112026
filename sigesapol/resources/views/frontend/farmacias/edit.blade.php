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
                <li><a href="/all_farmacias"><i class="fa fa-table fa-fw"></i> Centros de Atención </a></li>
                <li class="active"><a href="/create_farmacia"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar la {{$farmacias->nombre}}</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_farmacia') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						
						
						<div style="margin-bottom: 25px">
                            <input name="nombre" type="text" class="form-control" required="" value="{{$farmacias->nombre}}" placeholder="Nombre del Servicio" onkeypress="return solo_letra(event)">
                        </div>
						
                        <div style="margin-bottom: 25px"> 
                            <input value="{{$farmacias->id}}" type="hidden" name="id">
                            <select class="form-control" name="id_establecimiento" required="" id="id_establecimiento">
                                <option value="" selected="">--Seleccionar IPRESS--</option>
                                @foreach($establecimientos as $e)
                                <option value="{{$e->id}}" <?php if($farmacias->id_establecimiento == $e->id)echo "selected='selected'";?>>{{$e->codigo}} - {{$e->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="margin-bottom: 25px">
                            <div class="col-md-6"> 
                                Almacen:  <input type="checkbox" value="1" name="almacen" <?php if($farmacias->almacen == 1)echo 'checked="checked"';?>/>
                            </div>
                            <div class="col-md-6"> 
                                Almacen Ipress:  <input type="checkbox" value="1" name="almacen" <?php if($farmacias->almacen_ipress == 1)echo 'checked="checked"';?>/> <br/><br/>
                            </div>
                        </div>
                        <!--
                        <div style="margin-bottom: 25px">
                            <select id="nombre" name="nombre" class="form-control" tabindex="11"  style="margin-bottom: 10px" disabled="true" required="">
                                <option value=""  selected style="color: #888888">Selecionar nombre de la Farmacia</option>      
                            </select>
                        </div>
						-->
						
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_farmacias" class="btn btn-danger">Cancelar</a>
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