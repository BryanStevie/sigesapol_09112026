@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $(document).ready(function () {
        // Inicializar select2 para los campos existentes
        $("#id_establecimiento").select2();
        $("#nombre").select2();

        // Inicializar select2 para los nuevos select de menú
        $("#id_menu").select2({ width: 'resolve' });
        $("#id_submenu").select2({ width: 'resolve' });

        // Lógica para cargar nombres de farmacia basada en la selección de establecimiento
        $("#id_establecimiento").change(function () {
            var $this = $(this);
            var cmbEst = $this.val();
            var option = {
                url: '/cargarnombre/' + cmbEst,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                cargarComboNombre($("#nombre"), data);
                $("#nombre").val(null).trigger("change");
                document.getElementById("nombre").disabled = false;
            });
        });

        // Lógica para deshabilitar/habilitar el menú principal basado en la selección del submenú
        $("#id_submenu").change(function() {
            var selectedId = $(this).val();
            console.log("ID del submenú seleccionado: " + selectedId);

            if (selectedId != "0") {
                // Disable the main menu select if a submenu is selected
                $("#id_menu").prop('disabled', true);
            } else {
                // Enable the main menu select if no submenu is selected
                $("#id_menu").prop('disabled', false);
            }
        });
    });

    function cargarComboNombre($select, data) {
        $select.html('');
        $select.append('<option value="" required="required">Selecciona el nombre de la Farmacia</option>');
        $(data).each(function (ii, oo) {
            $select.append('<option value="Farmacia del ' + oo.nombre + '">Farmacia del ' + oo.nombre + '</option>');
        });
    }
</script>



@endsection
@section('content')
<div class="container">
    <div class="row">
		<!--
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_pabellon"><i class="fa fa-table fa-fw"></i> Pabell&oacute;n </a></li>
                <li class="active"><a href="/create_pabellon"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
		-->
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="/all_menu"><i class="fa fa-table fa-fw"></i> Menu</a></li>
                <li><a href="/create_menu"><i class="fa fa-plus"></i> Nuevo </a></li>
            </ul>
        </div>
        <div class="col-md-10 well col-md-offset-1">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar {{$menu->nombre}}</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_menu') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" class="form-control" name="id"  value="{{$menu->id}}">
						
						<div class="form-group">
                            <label class="col-lg-2 control-label">Menu Principal</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="id_menu" id="id_menu" required="" placeholder="Seleccionar un Menu" style="width: 100%">
                                            <option value="0">-Seleccionar-</option>
                                            @foreach($menus as $m)
                                            <option value="{{$m->id}}" <?php if($menu->parentid == $m->id)echo "selected='selected'";?>>{{$m->nombremenu}}</option>
                                            @endforeach
                                </select>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Sub Menu</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="id_submenu" id="id_submenu" required="" placeholder="Seleccionar un Sub Menu" style="width: 100%">
                                    <option value="0">-Seleccionar-</option>
                                    @foreach($menus_sub as $sub)
                                        <option value="{{ $sub->id }}" {{ $menu->parentid == $sub->id ? "selected='selected'" : "" }}>
                                            {{ $sub->id }} - {{ $sub->nombremenu }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        


                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nombre Menu</label>
                            <div class="col-lg-10">
                                <input name="nombremenu" type="text" class="form-control" required="" placeholder="Nombre del Menu" value="{{$menu->nombremenu}}" onkeypress="return solo_letra(event)">             
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Url</label>
                            <div class="col-lg-10">
                                <input  readonly="" name="url" type="text" class="form-control" required="" placeholder="Ingrese ruta" value="{{$menu->url}}" >             
                            </div>
                        </div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Orden</label>
							<div class="col-lg-2">
                            	<input name="orden" type="text" class="form-control" value="{{$menu->orden}}" required="" placeholder="Orden del Menu" onkeypress="return IsNumber(event)" style="text-align:right">
							</div>
                        </div>
                        
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_menu" class="btn btn-danger">Cancelar</a>
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