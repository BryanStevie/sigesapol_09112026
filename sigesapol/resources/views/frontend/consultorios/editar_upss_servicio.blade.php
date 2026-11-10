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

        $("#id_atenciones").select2({
            width: 'resolve' // need to override the changed default
        });

        $("#btnReg").on("click", function () {

            if ($("#id_producto").val() == "") {
                bootbox.alert("Falta seleccionar el Tipo de Atención");
                return false;
            }

            $("#frmReceta").submit();

        });
    });

    function getUpss() {

        var id_atenciones = $('#id_atenciones').val();
		var id_servicio = $('#id_servicio').val();
		//$('#tblConsultorio tbody').html("");
        $.ajax({
            url: '/obtener_atenciones_upss/' + id_servicio,
            success: function (result) {
                var html = "";
                $(result).each(function (ii, oo) {
					html += "<tr>";
					html += "<td>"+oo.tipo_atenciones+"</td>";
					html += "<td>"+oo.descripcion+"</td>";
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
            }
        });

    }

    //-------------------------------------------------------------------------
    
	function desactivar(id, estado){
            if(estado == 3){
                    bootbox.confirm("¿Se activará el Código de Upss para este servicio, desea continuar?", function(result) {
                    if (result==true) {
                        $.ajax({
                            url: '/desactivar_upss_servicio/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            } else {
                    bootbox.confirm("¿Se desactivará el Código de Upss para este servicio, desea continuar?", function(result) {
                    if (result==true) {
                        $.ajax({
                            url: '/desactivar_upss_servicio/'+id+'/'+estado,
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
                        <a class="btn btn-default btn-xs" href="/add_upss_servicio/{{$id_servicio}}">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
					<?php if ($existe == 1): ?>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Editar Upss al Servicio</h3>
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_upss" class="form-horizontal" method="POST" action="{{ route('update_upss_servicio')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $id_servicio ?>">
                                    <input type="hidden" name="id" id="id" value="<?php echo $upss_encontrado->id ?>">
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Tipo de Atención</label>
                                        <div class="col-lg-9">
                                            <select class="form-control" name="id_atenciones" id="id_atenciones" onchange="getUpss()" required="" placeholder="Seleccionar" style="width: 100%">
                                                <option value="0">-Seleccionar-</option>
                                                    @foreach($atenciones as $f)
                                                        <option value="{{$f->id}}" <?php if($upss_encontrado->id_tipo_atencion==$f->id) echo 'selected';  ?>> {{$f->nombre}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Código</label>
                                        <div class="col-lg-3">
                                        	<input name="codigo" type="text" class="form-control" required="" value="{{$upss_encontrado->codigo}}" placeholder="Código" autocomplete="off" maxlength="6">  
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Descripción</label>
                                        <div class="col-lg-9">
                                            <input name="descripcion_upss" type="text" class="form-control" required="" value="{{$upss_encontrado->descripcion_upss}}" placeholder="descripción upss" autocomplete="off" maxlength="100">  
                                        </div>
                                    </div>
									<div class="col-md-offset-7 col-md-4">
                                        <input id="btnReg" class="btn btn-success form-control" value="Actualizar" type="submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
					<?php endif; ?>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Lista de Código Upss</h3>  
                            </div>
                            <div class="panel-body">
                                <table id="tblConsultorio" class="table" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th width="30%">Tipo de Atencion</th>
											<th width="30%">Código</th>
                                            <th width="30%">Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upss_servicio as $e)
                                        <tr>
                                            <td>{{$e->nombre}}</td>
                                            <td>{{$e->codigo}}</td>
                                            <td>{{$e->descripcion_upss}}</td>
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