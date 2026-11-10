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
        
		$('#addRow').on('click', function () {
            AddFila();
        });

        $('#example tbody').on('click', 'button.deleteFila', function () {
            var obj = $(this);
                obj.parent().parent().remove();
        });

        
		//var id_farmacia = $('#frmfarmacia').find("#id_farmacia").val();
		//for (var i = 0; i < 3; i++) {
		//	AddFila();
         //   }
		//alert(id_farmacia);
		//AddFila();	
		
    });

    function AddFila(){
		var newRow = "";	
		var ind = $('#example tbody tr').length;	
        var item_tipo 	= "";
		
        $('#id_tipo_atenciontemp option').each(function(){
			item_tipo += "<option value="+$(this).val()+">"+$(this).html()+"</option>"	
        });
		newRow +='<tr>';
		newRow +='<td class="col-sm-9"><select class="form-control tipo_atencion" id="tipo_atencion'+ind+'" name="id_tipo_atencion[]" style="width:100%" >'+item_tipo+'</select></td>';
		newRow +='<td class="col-sm-3"><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
		newRow +='</tr>';
		
		$('#example tbody').append(newRow);
        $("#tipo_atencion"+ind).select2({max_selected_options: 4}).focus();
        
        var nind = 0;
        $("#tipo_atencion"+ind).on("change", function (e) {
            var flagx = 0;
            cmb = $(this);
            id_tipo_atencion = $("#tipo_atencion"+ind).val();

            $('.tipo_atencion').each(function(){
                    var ind_tmp = $(this).val();
                    if($(this).val() == id_tipo_atencion)flagx++;
            });

            if(flagx > 1){
                    bootbox.alert("El servicio de atención, ya ha sido seleccionado");
                    $("#tipo_atencion"+ind).val("").trigger("change");
                    return false;
            }
        });
    }	

    function cargarComboNombre($select, data) {
        $select.html('');
        $(data).each(function (ii, oo) {
            $select.append('<option value="" required="required" >' + "Selecciona el nombre de la Farmacia"+ '</option>')
            $select.append('<option value="' + "Farmacia del "+ oo.nombre + '">' + "Farmacia del "+ oo.nombre + '</option>')
        });
    }

	
    function validateFrm(){
        $('#btnReg').prop('disabled', true);

        var ind = $('#example tbody tr').length;
        if(ind > 0){
            var filas=$('#example tbody').children('tr');            
            var fila = "";
            var valmedentre = "";
            var nind = 0;
            for (var i = 0; i < ind; i++) {
                fila=filas[i];
                valmedentre = $("#tipo_atencion"+nind).val();
                if(valmedentre == ""){
                    bootbox.alert("No selecciono uno de los Tipo de Atencion agregados");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }
                nind++;
            }
        }

        submitFrm();
        return false;
    }

    function submitFrm() {
        bootbox.confirm({
            message: "Aviso: Se guardarán los registros ingresados. ¿Desea continuar?.",
            className: 'title-dialog',
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success btn-lg'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger btn-lg'
                }
            },
            callback: function (result) {
                if (result==true) {
                    $("#frmfarmacia").submit();
                } else {
                    $('#btnReg').prop('disabled', false);
                }
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
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_farmacia_config') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input value="<?php echo $farmacias->id?>" type="hidden" name="id" id="id_farmacia">
						
						<div class="form-group">
							<label class="col-lg-4 control-label">Centro de Atenci&oacute;n</label>
							<label class="col-lg-8 control-label" style="text-align:left"><?php echo $farmacias->nombre?></label>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Rango de d&iacute;as para registrar una receta atrazada</label>
							<div class="col-lg-2">
								<input type="text" value="<?php echo $farmacias->rango_registro?>" name="rango_registro" required="" class="form-control" placeholder="Rango de Registro Receta" onkeypress="return IsNumber(event)" maxlength="3">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Rango de d&iacute;as para anular una receta registrada</label>
							<div class="col-lg-2">
								<input type="text" value="<?php echo $farmacias->rango_anulado?>" name="rango_anulado" required="" class="form-control" placeholder="Rango para Anular Receta" onkeypress="return IsNumber(event)" maxlength="3">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-4 control-label">Modo de Acreditaci&oacute;n del Asegurado</label>
							<div class="col-lg-3">
								<select class="form-control" name="modo_validacion" id="modo_validacion">
									<option value="1" <?php if($farmacias->modo_validacion == 1)echo "selected='selected'";?>>Por Nro. de Documento</option>
									<option value="2" <?php if($farmacias->modo_validacion == 2)echo "selected='selected'";?>>Por RUA</option>
									<option value="3" <?php if($farmacias->modo_validacion == 3)echo "selected='selected'";?>>Por  Nro. de Documento Y Admisi&oacute;n</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-4 control-label">Alerta de Validaci&oacute;n en receta de los Dias de duracion de producto por Beneficiario y Farmacia</label>
							<div class="col-lg-2">
								<input type="checkbox" style="margin-top:28px" value="1" name="flag_receta_dias" <?php if($farmacias->flag_receta_dias == 1)echo 'checked="checked"';?> />
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-4 control-label">Modo de Registro de la Receta</label>
							<div class="col-lg-3">
								<select class="form-control" id="modo_registro" name="modo_registro" tabindex="5" style="width: 100%" required="">
									<option value="" selected>-Seleccionar-</option>
									<option value="1" <?php if($farmacias->modo_registro == 1)echo "selected='selected'";?>>PRESCIPCIÓN</option>
									<option value="2" <?php if($farmacias->modo_registro == 2)echo "selected='selected'";?>>DISPENDIO</option>
								</select>
							</div>
						</div>

<!--					<div class="form-group">
							<label class="col-lg-4 control-label">Tipo de Atenci&oacute;n</label>
							<div class="col-lg-3">
								<select class="form-control" id="id_tiporeceta" name="id_tiporeceta" tabindex="5" style="width: 100%">
									<option value="" selected>-Seleccionar-</option>
									<option value="1" <?php if($farmacias->id_tiporeceta == 1)echo "selected='selected'";?>>AMBULATORIO</option>
									<option value="2" <?php if($farmacias->id_tiporeceta == 2)echo "selected='selected'";?>>EMERGENCIA</option>
									<option value="3" <?php if($farmacias->id_tiporeceta == 3)echo "selected='selected'";?>>HOSPITALIZACION</option>
									<option value="4" <?php if($farmacias->id_tiporeceta == 4)echo "selected='selected'";?>>CENTRO QUIRÚRGICO</option>
									<option value="5" <?php if($farmacias->id_tiporeceta == 5)echo "selected='selected'";?>>SERVICIO NUTRICIONAL</option>
								</select>
							</div>
						</div> -->

						<div class="form-group">
							<label class="col-lg-4 control-label">Ingreso de pecosa</label>
							<div class="col-lg-3">
								<select class="form-control" id="flag_pecosa" name="flag_pecosa" tabindex="6" style="width: 100%" required="">
									<option value="" selected>-Seleccionar-</option>
									<option value="1" <?php if($farmacias->flag_pecosa == 1)echo "selected='selected'";?>>MANUAL</option>
									<option value="2" <?php if($farmacias->flag_pecosa == 2)echo "selected='selected'";?>>AUTOMATICO</option>
								</select>
							</div>
						</div>
						
						<hr/>
						<div class="col-md-12">
							<div class="col-md-12" style="text-align: left; font-size: 18px">
							Tipo de Atención 
							</div>                                
							<div id="tipo_atencion" class="row col-md-12" style="padding-top:10px; padding-bottom:0px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
								<button type="button" id="addRow" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar Tipo(s)</button>
								
								<div style="display: none;">									
									<select class="form-control" name="id_tipo_atenciontemp" id="id_tipo_atenciontemp">									
										<option value="">Seleccionar</option>
											@foreach($tipo_atencion as $ta)
												<option value="{{$ta->id}}">{{$ta->nombre}}</option>
											@endforeach 
									</select>
								</div>
								<table id="example" class="display" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Descripción</th>
											<th>Eliminar</th>
										</tr>
									</thead>
									<tfoot></tfoot>
									<tbody>
									<?php foreach($farmacia_tipo_atencion as $key=>$row):?>
									<tr>
										<td class="col-sm-9">
											<select class="form-control tipo_atencion" id="tipo_atencion<?php echo $key?>" name="id_tipo_atencion[]" style="width:100%" >
											<?php foreach($tipo_atencion as $keyta=>$ta):?>
												<option value="<?php echo $ta->id?>" <?php if($row->id_tipo_atencion==$ta->id)echo "selected='selected'";?>><?php echo $ta->nombre?></option>
											<?php endforeach;?>
											</select>
										</td>
										<td class="col-sm-3">
											<button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>
										</td>
									</tr>
									<script>
									$(document).ready(function () {//alert("11");
										$("#tipo_atencion<?php echo $key?>").select2();
										$("#tipo_atencion<?php echo $key?>").on("change", function (e) {
											var flagx = 0;
											cmb = $(this);
											id_tipo_atencion = $("#tipo_atencion<?php echo $key?>").val();

											$('.tipo_atencion').each(function(){
													var ind_tmp = $(this).val();
													if($(this).val() == id_tipo_atencion)flagx++;
											});

											if(flagx > 1){
													bootbox.alert("El servicio de atención, ya ha sido seleccionado");
													$("#tipo_atencion<?php echo $key?>").val("").trigger("change");
													return false;
											}
										
										});
										
										
									});

									</script>
									
									<?php endforeach;?>
									</tbody>
								</table>

							</div>
						</div>
						
						<div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
							<a href="/all_farmacias" class="form-control btn btn-danger" >Cancelar</a>
						</div>
						<div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px" >
							<button id="btnReg" class="form-control btn btn-success" type="submit" tabindex="99921">Guardar</button>
						</div>				
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection