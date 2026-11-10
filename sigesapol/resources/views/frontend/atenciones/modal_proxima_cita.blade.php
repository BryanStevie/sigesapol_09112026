<title>Sistema de Farmacia</title>
<script>
    function confirmar(id_cita, tipo) {
        switch (tipo) {
            case 1:
                bootbox.confirm({
                    title: "Alerta",
                    message: "El paciente está programado con Usted.<br><br><b>¿Desea atender la cita o continuar la atención como Adicional?</b>",
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Continuar como Adicional'
                        },
                        confirm: {
                            label: '<i class="fa fa-check"></i> Atender la Cita'
                        }
                    },
                    callback: function (result) {
                        if (result == true) {
                            location.href ="/formulario_atencion/" + id_cita;
                        } else {
                            $('#showProximasCitas').modal('hide');
                        }
                    }
                });
                break;
            case 2:
                bootbox.alert({
                    title: "Alerta",
                    message: "El paciente está programado con otro Profesional.<br><br><b>¿Desea atender al paciente en su consultorio?</b>",
                    size: 'small'
                });
                break;
            case 3:
                bootbox.confirm({
                    title: "Alerta",
                    message: "El paciente no tiene Profesional programado.<br><br><b>¿Desea atender la cita en su consultorio o continuar la atención como Adicional?</b>",
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Continuar como Adicional'
                        },
                        confirm: {
                            label: '<i class="fa fa-check"></i> Atender la Cita en mi Consultorio'
                        }
                    },
                    callback: function (result) {
                        if (result == true) {
                            var id_medico = $("#id_medico").val();
                            var id_tipo_atencion = $("#id_tipo_atencion").val();
                            var id_servicio = $("#id_servicio").val();
                            location.href ="/atender_en_consultorio_actual/" + id_cita + "/" + id_medico + "/" + id_tipo_atencion + "/" + id_servicio;
                        } else {
                            $('#showProximasCitas').modal('hide');
                        }
                    }
                });
                break;
        }
    }
</script>
<div class="container" style="width:100%">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable" style="margin-bottom:0px">
                    <div class="panel-heading">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="panel-title">ALERTA ATENCIÓN MÉDICA</h3>      
                    </div>
                    <div class="panel-body" >
                        <div style="font-size:15px!important">El paciente tiene una cita en el mismo servicio</div>
                            <table class="table table-hover table-sm dataTable no-footer" style="margin-top:10px">
                                <thead>
                                    <tr>
                                        <th style="width:30%">Establecimiento</th>
                                        <th style="width:20%">Servicio</th> 
                                        <th style="width:10%">Consultorio</th>   
                                        <th style="width:10%">Fecha</th>
                                        <th style="width:10%">Hora</th>
                                        <th style="width:10%">Estado</th>
                                        <th style="width:10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($horario as $rowHorario):?>
                                        <tr>
                                            <td><?php echo $rowHorario->establecimiento?></td>
                                            <td><?php echo $rowHorario->servicio?></td>
                                            <td><?php echo $rowHorario->nombre_subconsultorio?></td>
                                            <td><?php  echo $fechaE = date("d/m/Y", strtotime($rowHorario->dia)); ?></td>
                                            <td><?php echo $rowHorario->hora?></td>
                                            <td><?php echo $rowHorario->estado_cita?></td>
                                            <td>
                                                @if($rowHorario->id_estado_cita<3)
                                                    <!-- Validacion si cuenta con profesional programado -->
                                                    @if($rowHorario->id_medico != null)
                                                        
                                                        @if($rowHorario->id_establecimiento==$id_establecimiento)
                                                            @if($rowHorario->id_medico == $id_medico)
                                                                <!-- Citado para el profesional actual -->
                                                                <a onclick="confirmar(<?php echo $rowHorario->id?>, 1)" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                                            @else
                                                                <!-- Citado para otro profesional -->
                                                                <a onclick="confirmar(<?php echo $rowHorario->id?>, 2)" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                                            @endif
                                                        @else 
                                                            <a href="#" disabled="disabled" class="btn btn-success btn-xs" data-toggle="tooltip" title="El paciente esta citado en otro Establecimiento"><i class="fa fa-edit"></i></a> 
                                                        @endif
                                                        
                                                    @else
                                                        <a onclick="confirmar(<?php echo $rowHorario->id?>, 3)" class="btn btn-success btn-xs" data-toggle="tooltip" title="No tiene medico programado"><i class="fa fa-edit"></i></a> 
                                                    @endif

                                                @else 
                                                    <a href="#" disabled="disabled" class="btn btn-success btn-xs" data-toggle="tooltip" title="El estado de la cita no es Programado"><i class="fa fa-edit"></i></a> 
                                                @endif
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>                    
                            </table>
                        </div>
                    </div>                                    
                </div>
            </div>
        </div>
    </div>
</div>