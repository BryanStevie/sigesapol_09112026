@extends('frontend.layouts.master')
@section('renderjs')
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            <div class="col-sm-12 pull-right">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/all_pabellon"><i class="fa fa-table fa-fw"></i> Pabell&oacute;n</a></li>
                    <li class="active"><a href="/create_pabellon"><i class="fa fa-plus"></i> Nuevo Pabellón</a></li>
                    <li><a href="/all_piso/"><i class="fa fa-table fa-fw"></i> Pisos</a></li>
                    <li><a href="/all_asignacion_servicio/"><i class="fa fa-table fa-fw"></i> Servicio</a></li>
                    <li><a href="/all_cuarto/"><i class="fa fa-table fa-fw"></i> Cuarto</a></li>
                    <li><a href="/create_cama/"><i class="fa fa-table fa-fw"></i> Cama</a></li>
                </ul>
                
            </div>
        </div>        
        <div class="col-md-10">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar Pabell&oacute;n: {{$pabellon->nombre}}</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_pabellon') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_establecimiento" value="<?php echo $id_establecimiento ?>">
                        <input type="hidden" class="form-control" name="id"  value="{{$pabellon->id}}">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Atención</label>
                            <div class="col-lg-10">
                                <select id="nombre_atencion" name="nombre_atencion" class="form-control" required="">
                                    <option value="">--Seleccionar--</option>
                                    <option value="HOSPITALIZACION" <?php if ($pabellon->nombre_atencion == 'HOSPITALIZACION') echo "selected='selected'"; ?> >HOSPITALIZACION</option>
                                    <option value="EMERGENCIA" <?php if ($pabellon->nombre_atencion == 'EMERGENCIA') echo "selected='selected'"; ?>>EMERGENCIA</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Pabell&oacute;n</label>
                            <div class="col-lg-10">
                                <input name="nombre" type="text" class="form-control" value="{{$pabellon->nombre}}" required="" placeholder="Nombre del Servicio" onkeypress="return solo_letra(event)" autocomplete="OFF">             
                            </div>
                        </div>

                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_pabellon" class="btn btn-danger">Cancelar</a>
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