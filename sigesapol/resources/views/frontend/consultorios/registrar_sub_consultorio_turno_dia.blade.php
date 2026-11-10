@extends('frontend.layouts.master')
@section('renderjs')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

	function desactivar(id, estado){
            <?php 
                $json = json_encode($subconsultorioturno);
            ?>

            const subconsultorioturno = eval( <?php echo $json; ?> );
            const itemDia = subconsultorioturno.find(element => element.id == id);            

            if(estado == 3 && itemDia.dia !== "Todos"){
                    bootbox.confirm("¿Se activará el Turno para este Consultorio, desea continuar?", function(result) {
                    if (result==true) {
                        $.ajax({
                            url: '/desactivar_consultorio_turno_con_dia/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                    location.reload();
                    });
            }else if(estado == 3 && itemDia.dia == "Todos"){
                    bootbox.confirm("¿Se activará el Turno y se desactivará los otros turnos, desea continuar?", function(result) {
                    if (result==true) {
                        $.ajax({
                            url: '/desactivar_consultorio_turno_con_dia/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                    location.reload();
                    });
            }else if(estado == 1 && itemDia.dia == "Todos"){
                    bootbox.confirm("¿Se desactivará el Turno y se activará los otros turnos, desea continuar?", function(result) {
                    if (result==true) {
                        $.ajax({
                            url: '/desactivar_consultorio_turno_con_dia/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                    location.reload();
                    });
            }else {
                    bootbox.confirm("¿Se desactivará el Turno para este Consultorio, desea continuar?", function(result) {
                    if (result==true) {
                        $.ajax({
                            url: '/desactivar_consultorio_turno_con_dia/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                    location.reload();
                    });
            }

	}
    
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
					html += '<td class="text-center"><a class="btn btn-primary" href=""><i class="fa fa-medkit"></i></a></td>';
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
    
    
</script>

    @if (session('agregar') == 'ok')
        <script>
        Swal.fire(
            'Registrado!',
            'El turno se agregó con éxito.',
            'success'
            ).then((ok)=>{
                location.reload();
            });
        
        </script>
    @endif
    <script>

        $('.form_agregar').submit(function (e) { 
            e.preventDefault();
            const dia = $('#dia').val();
            const turno = $('#id_turno').val();
            if (dia == 1) {
                Swal.fire({
                title: '¿Estás seguro?',
                text: "Si seleccionas la opción todos, los otros turnos se desactivarán!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Si, agregar!',
                cancelButtonText: '¡Cancelar!'
                }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
                });
            }else if (dia == 0) {
                Swal.fire(
                'Incompleto!',
                'Debe selecionar un día o todos.',
                'warning'
                ).then((ok)=>{
                    location.reload();
                });
            }else if(turno == 0){
                Swal.fire(
                'Incompleto!',
                'Debe selecionar el turno.',
                'warning'
                ).then((ok)=>{
                    location.reload();
                });
            }else {
                this.submit();
            }
            
        });
        
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
                    <h3 class="panel-title">Servicio - {{$consultorio->nombre}} : Consultorio - {{$subconsultorio->descripcion}}</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/add_consultorios_servicio/<?php echo $subconsultorio->id_consultorio ?>">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
					<?php if ($existe == 1): ?>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Agregar Dia</h3>
                            </div>
                            <?php //exit();?>
                            <div class="panel-body">
                                <form id="frm_transferencia" class="form-horizontal form_agregar" method="POST" action="{{ route('agregar_consultorio_turno_dia')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
									<input type="hidden" name="id_sub_consultorio" id="id_sub_consultorio" value="<?php echo $id_sub_consultorio ?>">
                                    <input type="hidden" name="id_servicio" id="id_servicio" value="{{ $id_servicio }}">
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Dia</label>
                                        <div class="col-lg-9">
                                            <select class="form-control" name="dia" id="dia" required="" style="width: 100%">
                                                @foreach ($diasDeLaSemana as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option> 
                                                @endforeach
                                            </select>
                                        </div>
									</div>

                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Turno</label>
                                        <div class="col-lg-9">
                                        	<select class="form-control" name="id_turno" id="id_turno" required="" style="width: 100%">
                                                <option value="0">-Seleccionar-</option>
                                                @foreach($turno as $f)
                                                <option value="{{$f->id}}">{{$f->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
									<div class="form-group">
										<label class="col-lg-2 control-label">Hora Inicio</label>
										<div class="col-lg-9">
											<input name="hora_ini" class="form-control" required="" placeholder="Hora Inicio" type="time" maxlength="8">
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-2 control-label">Hora Fin</label>
										<div class="col-lg-9">
											<input name="hora_fin" class="form-control" required="" placeholder="Hora Fin" type="time" maxlength="8">
										</div>
									</div>
                                    <div class="form-group">
										<label class="col-lg-2 control-label">Tiempo Atenci&oacute;n</label>
										<div class="col-lg-9">
											<input name="tiempo_atencion" type="text" class="form-control" required="" placeholder="Tiempo Atenci&oacute;n" maxlength="3" autocomplete="off" onKeyPress="return justNumbers(event)">
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Fecha de Inicio</label>
                                        <div class="col-lg-4">
                                        <input name="fecha_inicio" id= "fecha_inicio" type="text" data-date-start-date="0d" class="form-control datepicker" placeholder="Fecha Inicial" value="">
                                                        
                                        </div>
                                        <label class="col-lg-2 control-label">Fecha de Final</label>
                                        <div class="col-lg-4">
                                        <input name="fecha_final" type="text" data-date-start-date="0d"  class="form-control datepicker" placeholder="Fecha Finalizar"  autocomplete="off" value="">
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
                                <h3 class="panel-title">Turnos seleccionados</h3>  
                            </div>
                            <div class="panel-body">
                                <table id="tblConsultorio" class="table" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th width="10%">Dia</th>
                                            <th width="10%">Turno</th>
                                            <th width="15%">Hora Inicio</th>
											<th width="15%">Hora Fin</th>
                                            <th width="15%">Tiempo Atención</th>
                                            <th width="15%">Fecha de Apertura</th>
                                            <th width="15%">Fecha de Cierre</th>
                                            <?php if ($existe == 1): ?>

                                            <th width="15%" class="text-center">Editar</th>
                                            <th width="15%" class="text-center">Estado</th>
                                            
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subconsultorioturno as $e)
                                        <tr>
                                            <td>{{$e->dia}}</td>
                                            <td>{{$e->turno}}</td>
                                            <td>{{$e->hora_ini}}</td>
											<td>{{$e->hora_fin}}</td>
                                            <td>{{$e->tiempo_atencion_sct}} min.</td>
                                            <td>{{$e->fecha_inicio_turno}}</td>
                                            <td>{{$e->fecha_fin_turno}}</td>
											<?php if ($existe == 1): ?>
                                            <?php $ddia = $e->dia == '' ? '--Seleccionar--' : $e->dia; ?>
											<td class="text-center">
                                                <a class="btn btn-success" href="{{route('editar_subconsultorio_dia', ['id_subconsultorio_turno' => $e->id, 'dia'=>$ddia, 'turno'=>$e->turno,'hora_ini'=>$e->hora_ini,'hora_fin'=>$e->hora_fin,'tiem_atencion'=>$e->tiempo_atencion_sct,'idservicio'=>$e->idservicio,'id_sub_consultorio'=>$e->id_sub_consultorio,'fecha_inicio_turno'=>$e->fecha_inicio_turno,'fecha_fin_turno'=>$e->fecha_fin_turno])}}"><i class="fa fa-edit"></i></a>                                    
                                            </td>
                                            <td class="text-center">    
                                                <?php if($e->id_estado_reg == "1"){ ?>
                                                    <a id="botonEstado" class="btn btn-success" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id ?>,<?php echo $e->id_estado_reg ?>)" ><i class="fa fa-thumbs-up"></i></a>                                                                        
                                                <?php } else {?>
                                                    <a id="botonEstado" class="btn btn-danger" href="javascript:void(0)" onclick="desactivar(<?php echo $e->id ?>,<?php echo $e->id_estado_reg ?>)"><i class="fa fa-thumbs-down"></i></a>
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
    
