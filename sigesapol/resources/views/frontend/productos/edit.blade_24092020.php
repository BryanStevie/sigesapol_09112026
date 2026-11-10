@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $cmbUni = $("#frmcrear_prod").find("#id_unidad");
    $cmbUni.select2();
    $cmbTip = $("#frmcrear_prod").find("#id_tipo");
    $cmbTip.select2();
    $cmbTip = $("#frmcrear_prod").find("#id_nivel");
    $cmbTip.select2();
    $cmbPet = $("#frmcrear_prod").find("#petitorio");
    $cmbPet.select2();
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_productos"><i class="fa fa-table fa-fw"></i>Productos</a></li>
                <li><a href="/create_producto"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-10 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar Producto</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmcrear_prod" class="form-horizontal" role="form" method="post"  action="{{ route('update_producto') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-md-3" style="margin-top: 10px; margin-bottom: 10px">
                                <input value="{{$productos->id}}" name="id" type="hidden">
                                <input value="{{$productos->codigo}}" name="codigo_actual" type="hidden">
                                <label>Código Petitorio</label>
                                <input  readonly="" type="text" class="form-control" name="codigo" value="{{$productos->codigo}}"  placeholder="CÓDIGO" required="" maxlength="8">
                            </div>
                            <div class="col-md-3" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Nuevo Código</label>
                                <input  type="text" class="form-control" name="codigo_nuevo" placeholder="CÓDIGO NUEVO" maxlength="8">
                            </div>
                            <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Descripción</label>
                                <input  type="text" class="form-control solomayusculas" name="nombre" value="{{$productos->nombre}}" placeholder="Nombre" required="" >
                            </div>
                            <div class="col-md-3" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Código SIGA</label>
                                <input  type="text" class="form-control" name="codigo_siga" value="{{$productos->codigo_siga}}" placeholder="CÓDIGO SIGA" maxlength="15">
                            </div>
                            <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Descripción SIGA</label>
                                <input  type="text" class="form-control solomayusculas" name="nombre_siga" value="{{$productos->descripcion_siga}}" placeholder="Descripcion" >
                            </div>
                            <div class="col-md-3" style="margin-top: 10px; margin-bottom: 10px">
                                Activo:  <input type="checkbox" value="1" name="estado" <?php if($productos->estado == 1)echo 'checked="checked"';?>/>
                            </div>                            
                        </div>                        
                        <div class="row">
                            <div class="col-md-4" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Tipo de Producto</label>
                                <select class="form-control" required="" name="id_tipo" id="id_tipo">
                                    <option value="">--Seleccionar Tipo de Producto--</option>
                                    @foreach($datos[1]  as $t)
                                    <option value="{{$t->id}}" <?php if($productos->id_tipo==$t->id) echo 'selected';?> >{{$t->nombre}}</option>
                                    @endforeach
                                </select>                                       
                            </div>
                            <div class="col-md-2" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Unidad Medida</label>
                                <select class="form-control" required="" name="id_unidad" id="id_unidad">          
                                    <option value="">--Seleccionar Unidad--</option>
                                    @foreach($datos[0] as $u)
                                    <option value="{{$u->id}}" <?php if($productos->id_unidad==$u->id) echo 'selected';?>>{{$u->nombre}}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="col-md-2" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Nivel</label>
                                <select class="form-control" required="" name="id_nivel" id="id_nivel">

                                    <option value="">--Nivel--</option>                                    
                                    <option value="1" <?php if($productos->nivel==1) echo 'selected';?> >1</option>
                                    <option value="2" <?php if($productos->nivel==2) echo 'selected';?> >2</option>
                                    <option value="3" <?php if($productos->nivel==3) echo 'selected';?> >3</option>
                                </select> 
                            </div>
                            <div class="col-md-2" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Petitorio</label>
                                <select class="form-control" required="" name="petitorio" id="petitorio">                                    
                                    <option value="SI" <?php if($productos->petitoio=='SI') echo 'selected';?> >SI</option>
                                    <option value="NO" <?php if($productos->petitorio=='NO') echo 'selected';?> >NO</option>                                    
                                </select> 
                            </div>
                            <div class="col-md-2" style="margin-top: 10px; margin-bottom: 10px">
                                <label>RU</label>
                                <input  type="text" class="form-control solomayusculas" name="ru" value="{{$productos->ru}}" placeholder="R 2, 6" > 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Concentración</label>
                                <input  type="text" class="form-control" name="concentracion" value="{{$productos->concentracion}}" placeholder="CONCENTRACION">
                            </div>
                            <div class="col-md-4" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Form Farmaceutica</label>
                                <input  type="text" class="form-control" name="form_farm" value="{{$productos->form_farm}}" placeholder="FORM FARM">
                            </div>
                            <div class="col-md-4" style="margin-top: 10px; margin-bottom: 10px">
                                <label>Presentación</label>
                                <input  type="text" class="form-control" name="presentacion" value="{{$productos->presentacion}}" placeholder="PRESENTACION">
                            </div>
							<div class="col-md-3" style="margin-top: 10px; margin-bottom: 10px"><label>Presentación Comercial</label><br/>
							   	<input type="checkbox" value="1" name="presentacion_comercial" <?php if($productos->presentacion_comercial == 1)echo 'checked="checked"';?>/>
						   	</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="margin-top:10px">  
                                <label>Observaci&oacute;n</label>
                                <textarea id="observacion" name="observacion" class="form-control input-md" ><?php echo $productos->observacion; ?></textarea>
                            </div>
                        </div>
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Actualizar" class="btn btn-success">
                                <a href="/all_productos" class="btn btn-danger">Cancelar</a>
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