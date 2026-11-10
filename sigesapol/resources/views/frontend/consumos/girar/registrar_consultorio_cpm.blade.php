@extends('frontend.layouts.master')
@section('renderjs')
<script>

    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }

    $(document).ready(function () {

        $("#id_establecimiento").select2({
            width: 'resolve' // need to override the changed default
        });

        $("#btnReg").on("click", function () {

            if ($("#id_producto").val() == "") {
                bootbox.alert("Falta seleccionar el Producto Farmaceutico");
                return false;
            }

            $("#frmReceta").submit();

        });

    });

    function getConsultorios() {

        var id_establecimiento = $('#id_establecimiento').val();
		var id_servicio = $('#id_servicio').val();
		//$('#tblConsultorio tbody').html("");
        $.ajax({
            url: '/obtener_consultorio_por_id_establecimiento/' + id_establecimiento + '/' + id_servicio,
            success: function (result) {
                var html = "";
                $(result).each(function (ii, oo) {
					html += "<tr>";
					html += "<td>"+oo.establecimiento+"</td>";
					html += "<td>"+oo.descripcion+"</td>";
					html += "<td>"+oo.abreviatura+"</td>";
					html += '<td class="text-center"><a class="btn btn-primary" href="/add_consultorio_turnos/'+oo.id+'"><i class="fa fa-medkit"></i></a></td>';
					<?php if ($existe == 1): ?>
					html += '<td class="text-center">';
						if(oo.id_estado_reg == "1"){ 
							html += '<a class="btn btn-success btn-xs" href="javascript:void(0)" onclick="desactivar('+oo.id+','+oo.id_estado_reg+')"><i class="fa fa-thumbs-up"></i></a>';
						} else {
							html += '<a class="btn btn-danger btn-xs" href="javascript:void(0)" onclick="desactivar('+oo.id+','+oo.id_estado_reg+')"><i class="fa fa-thumbs-down"></i></a>';
						}
					html += "</td>";
					<?php endif; ?>
                    html += "</tr>";
                });										
                $('#tblConsultorio tbody').html(html);
                //$("#id_farmacia").select2({max_selected_options: 4});
            }
        });

    }

    //-------------------------------------------------------------------------
    function cargarData(data) {
        $(data).each(function (ii, oo) {
            $("#frm_transferencia").find("#lote").val(oo.lote);
            $("#frm_transferencia").find("#codigo").val(oo.codigo_producto);
            $("#frm_transferencia").find("#producto").val(oo.prod_farmaceutico);
            $("#frm_transferencia").find("#registro_sanitario").val(oo.registro_sanitario);
            $("#frm_transferencia").find("#fecha_vencimiento").val(oo.fecha_vencimiento);
            $("#frm_transferencia").find("#cantidad_actual").val(oo.cantidad);
            $("#frm_transferencia").find("#id_producto").val(oo.id_producto);
            $("#frm_transferencia").find("#id_stock_farmacia").val(oo.id_stock_farmacia);
        });
    }
    //-------------------------------------------------------------------------
    
	function desactivar(id, estado){
            if(estado == 3){
                    bootbox.confirm("¿Se activará el Consultorio para este servicio, desea continuar?", function(result) {
                    if (result==true) {
                        $.ajax({
                            url: '/desactivar_consultorio_servicio/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            } else {
                    bootbox.confirm("¿Se desactivará el Consultorio para este servicio, desea continuar?", function(result) {
                    if (result==true) {
                        $.ajax({
                            url: '/desactivar_consultorio_servicio/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            }
	}
    
</script>
<style>
    .etiqueta{margin-top: 5px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;
    }
    .panel-heading div span{
        margin-left:5px;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Servicio - {{$consultorio->nombre}}</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/all_servicios">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
					<?php if ($existe == 1): ?>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Agregar Consultorio</h3>
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_transferencia" class="form-horizontal" method="POST" action="{{ route('agregar_consultorio_servicio')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $id_servicio ?>">

                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">IPRESS</label>
                                        <div class="col-lg-9">
                                            <select class="form-control" name="id_establecimiento" id="id_establecimiento" onchange="getConsultorios()" required="" placeholder="Seleccionar un Hospital" style="width: 100%">
                                                <option value="0">-Seleccionar-</option>
                                                @foreach($establecimientos as $f)
                                                <option value="{{$f->id}}">{{$f->codigo}} - {{$f->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Consultorio</label>
                                        <div class="col-lg-9">
                                        	<input name="descripcion" type="text" class="form-control" required="" placeholder="Consultorio" autocomplete="off">  
                                        </div>
                                    </div>
									<div class="form-group">
										<label class="col-lg-2 control-label">Abreviatura</label>
										<div class="col-lg-9">
											<input name="abreviatura" type="text" class="form-control" required="" placeholder="Abreviatura" autocomplete="off">
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-2 control-label">Tiempo Atenci&oacute;n</label>
										<div class="col-lg-9">
											<input name="tiempo_atencion" type="text" class="form-control" required="" placeholder="Tiempo Atenci&oacute;n" autocomplete="off">
										</div>
									</div>
                                    <div class="col-md-offset-7 col-md-4">
                                        <input id="btnReg" class="btn btn-success form-control" value="Agregar" type="submit">
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
					<?php endif; ?>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Consultorios seleccionados</h3>  
                            </div>
                            <div class="panel-body">
                                <table id="tblConsultorio" class="table" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th width="30%">IPRESS</th>
                                            <th width="25%">Consultorio</th>
											<th width="15%">Abreviatura</th>
											<th width="15%" class="text-center">Agregar Turnos</th>
                                            <?php if ($existe == 1): ?>
                                            <th width="15%" class="text-center">Estado</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subconsultorio_servicio as $e)
                                        <tr>
                                            <td>{{$e->establecimiento}}</td>
                                            <td>{{$e->descripcion}}</td>
											<td>{{$e->abreviatura}}</td>
                                            <td class="text-center">
												<a class="btn btn-primary" href="/add_consultorio_turnos/{{ $e->id }}"><i class="fa fa-medkit"></i></a>
                                            </td>
											<?php if ($existe == 1): ?>
											<td class="text-center">
                                                <?php if($e->id_estado_reg == "1"){ ?>
                                                    <a class="btn btn-success btn-xs" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id ?>,<?php echo $e->id_estado_reg ?>)"><i class="fa fa-thumbs-up"></i></a>                                    
                                                <?php } else {?>
                                                    <a class="btn btn-danger btn-xs" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id ?>,<?php echo $e->id_estado_reg ?>)"><i class="fa fa-thumbs-down"></i></a>
                                                <?php }?>
                                            </td>
											<?php endif; ?>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@include('frontend.includes.footer')
@endsection