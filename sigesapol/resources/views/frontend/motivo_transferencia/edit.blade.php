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

    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8 || key == 45)
        }
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_motivo_transferencia"><i class="fa fa-table fa-fw"></i> Motivo de Transferencia </a></li>
                <li class="active"><a href="/create_motivo_transferencia"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar {{$motivo->nombre}}</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_motivo_transferencia') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">                        
                        <input type="hidden" class="form-control" name="id"  value="{{$motivo->id}}">
                        <fieldset>
                            <div class="col-md-8">
                                <label>Motivo de Transferencia</label>
                                <input id="nombre" name="nombre" type="text" value="{{$motivo->nombre}}" placeholder="Motivo de Transferencia" class="form-control input-md" required="" style="text-transform: uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">                                    
                            </div>
                            <div class="col-md-4">
                                <label>Abreviatura</label>
                                <input id="abreviatura" name="abreviatura" type="text" value="{{$motivo->abreviatura}}" placeholder="Abreviatura" class="form-control input-md" required="" style="text-transform: uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                            </div>
                            <div class="col-md-8">
                                <label>Tipo de Transferencia</label>
                                    <select class="form-control" id="tipo" name="tipo" style="width: 100%" required="">                                        
                                        <option value="">--Seleccionar--</option>
                                        <option value="N" <?php if($motivo->tipo_motivo=='N')echo "selected='selected'";?> >NOTA DE ENTRADA</option>
                                        <option value="A" <?php if($motivo->tipo_motivo=='A')echo "selected='selected'";?> > ACTA</option>
                                    </select>                                     
                            </div> 
                            <div class="col-md-4">
                                <label>Orden</label>
                                <input autofocus onkeypress="return soloNumeros(event);" name="orden" value="{{$motivo->orden}}" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">  									                                
                            </div>
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                <a href="/all_motivo_transferencia" class="form-control btn btn-danger" >Cancelar</a>
                            </div>
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                <input type="submit" class="form-control btn btn-success" value="Guardar">
                            </div>
                        </fieldset> 
                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection