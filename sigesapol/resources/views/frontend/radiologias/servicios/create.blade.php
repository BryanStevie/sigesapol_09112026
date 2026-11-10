@extends('frontend.layouts.master')
@section('renderjs')
<link rel="stylesheet" href="../../sceditor/minified/themes/office.min.css" id="theme-style" />
<style>
  .obligatorio{
      color: red;
  }
  .borde-alerta {
  border-color: red !important;
  border: 2px red solid !important;
  outline: 0;
  border-radius: 4px;
  box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important; 
  }
</style>
<script>
    $tipo = $("#frmservicio").find("#id_servicio");
    $tipo.select2();

    function validateFrm(){
        $('#btnReg').prop('disabled', true);

        if ( $('#id_servicio').val() == '' ) {
            $('#id_servicio').addClass('borde-alerta');
            $("#id_servicio").focus();
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ( $('#descripcion_servicio').val() == '' ) {
            $('#descripcion_servicio').addClass('borde-alerta');
            $("#descripcion_servicio").focus();
            $('#btnReg').prop('disabled', false);
            return false;
        }
        
       submitFrm();
       return false;
    }
    
    function submitFrm() {
            bootbox.confirm({
                message: "Aviso: Se guardaran los datos ingresados. ¿Desea continuar?.",
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
                        $("#frmservicio").submit();
                    } else {
                        $('#btnReg').prop('disabled', false);
                    }
                }
            });
    }

    $(document).ready(function () {
        $('#example').DataTable({
            "language": {
                "emptyTable": "No se encontraron resultados"
            }
        });
        $("#system-search").keyup(function() {
            var dataTable = $('#example').dataTable();
            dataTable.fnFilter(this.value);
        });

        $('#id_servicio').change(function() {
            var selectedOption = $(this).find('option:selected');
            var descripcion = selectedOption.data('nombre');
            console.log(descripcion);
            $('#descripcion_servicio').val(descripcion);
        });
        
    });

    function desactivar(id){
        bootbox.confirm("¿Se desactivará este servicio, desea continuar?", function(result) {
            if (result==true) {				
                $.ajax({
                url: '/desactivar_categoria_radiologia/'+id,
                success: function(result){
                    location.reload();
                }
                });
            }
        })
	}

</script>
<script src='../../funciones/function.js'></script> 
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_plantilla_radiologias"><i class="fa fa-table fa-fw"></i> Plantillas de Radiología</a></li>
                <li class="active"><a href="/create_servicio_radiologia"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                <li><a href="/create_plantilla_radiologia"><i class="fa fa-plus"></i> Nuevo Plantilla</a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Nuevo Servicio</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmservicio" class="form-horizontal" role="form" method="post"  action="{{ route('registrar_categoria_radiologia') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="nivel" id="nivel" value="{{$id_nivel}}">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <div class="col-md-12" style="margin-bottom: 1rem;">
                                    <label>SERVICIO <span class="obligatorio">(*)</span></label>
                                    <select class="form-control" name="id_servicio" id="id_servicio" required="">
                                        <option value="">Seleccionar</option>
                                        @foreach ($categorias as $ss)
                                            <option value="{{ $ss->id }}" data-nombre="{{ $ss->nombre }}">{{ $ss->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 1rem;">
                                    <label>DESCRIPCIÓN <span class="obligatorio">(*)</span></label>
                                    <input name="descripcion_servicio" id="descripcion_servicio" type="text" class="form-control" required="" placeholder="Descripción del servicio" >
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6">
                                <input id="btnReg" class="btn btn-success" style="width: 100%;" tabindex="12" value="Agregar" type="button" onclick="validateFrm()">
                            </div>
                        </div> 
                    </form> 
                    <div class="col-sm-12 col-md-12" style="margin-top: 3rem;">
                        <table class="table"  id="example">
                            <thead>
                                <tr class="filters">
                                    <th>N</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>SERVICIO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $n = 1;
                                ?>
                                @foreach ($lista_categorias as $lc)    
                                    <tr>
                                        <td>{{ $n }}</td>
                                        <td>{{ $lc->nombre }}</td>
                                        <td>{{ $lc->nombre_consultorio }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-success" href="javascript:void(0)" onclick="desactivar(<?php echo $lc->id ?>)"><i class="fa fa-thumbs-up"></i></a>
                                        </td>
                                    </tr>
                                    <?php $n++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>                     
            </div>
        </div>
    </div>
</div>
@endsection