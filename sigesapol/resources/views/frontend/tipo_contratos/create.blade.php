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
                url: 'cargarnombre/' + cmbEst,
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
    function cargarComboNombre($select, data) {
        $select.html('');
        $(data).each(function (ii, oo) {
            $select.append('<option value="" required="required" >' + "Selecciona el nombre de la Farmacia"+ '</option>')
            $select.append('<option value="' + oo.nombre + '">' + oo.nombre + '</option>')
        });
    }
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_tipo_contrato"><i class="fa fa-table fa-fw"></i> Tipo de Contrato</a></li>
                <li class="active"><a href="/create_tipo_contrato"><i class="fa fa-plus"></i> Nuevo </a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Nuevo Tipo de Contrato</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('store_tipo_contrato') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tipo de Contrato</label>
                            <div class="col-lg-10">
                                <input name="nombre" type="text" class="form-control" required="" placeholder="Nombre del Tipo de Contrato" onkeypress="return solo_letra(event)" autocomplete="OFF">
                            </div>
                        </div>

                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
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