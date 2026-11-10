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

        $("#id_categoria").select2({
            width: 'resolve' // need to override the changed default
        });

        $("#btnReg").on("click", function () {

            if ($("#id_categoria").val() == "") {
                bootbox.alert("Falta seleccionar la Categoria");
                return false;
            }
            $("#frmReceta").submit();
        });

        $("#bloque-nivel").hide();
    });

    //-------------------------------------------------------------------------
    function desactivar(id, estado){
            if(estado == 0){
                    bootbox.confirm("¿Se activará el Nivel, desea continuar?", function(result) {
                    if (result==true) {				
                        $.ajax({
                            url: '/desactivar_nivel_categoria/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            } else {
                    bootbox.confirm("¿Se desactivará el Nivel, desea continuar?", function(result) {
                    if (result==true) {				
                        $.ajax({
                            url: '/desactivar_nivel_categoria/'+id+'/'+estado,
                            success: function(result){
                                location.reload();
                            }
                         });
                    }
                });
            }
	}

    $("#tipo_atencion").change(function (e) { 
        e.preventDefault();
        let nivel = $("#nivel").val();
        let id_consultorio = $("#id_consultorio").val();
        let tipo_atencion = $("#tipo_atencion").val();
            $.ajax({
                type: "GET",
                url: "/add_categoria_rua_modal/" + nivel + "/" + id_consultorio + "/" + tipo_atencion,
                dataType: "html",
                success: function (response) {
                    $("#seccion_categoria").html(response);
                }
            });
    });

    $("#id_establecimiento").change(function (e) { 
        e.preventDefault();
        $("#seccion_categoria").html("");
        let id_establecimiento = $("#id_establecimiento").val();
        let nivel = "";
        $.ajax({
            url: "/obtener_nivel_establecimiento/" + id_establecimiento,
            success: function (response) {
                nivel = response[0].nivel;
                $("#nivel").val(nivel);
                $.ajax({
                    url: "/obtener_tipo_atencion/" + nivel,
                    success: function (result) {
                        let res = result[2];
                        let newOption = "";
                        newOption += "<option value='0'>--SELECCIONAR--</option>";
                        for (const key in res) {
                            if (res.hasOwnProperty(key)) {
                                newOption += "<option value='" + key + "'>" + res[key] + "</option>";
                            }
                        }
                        $('#tipo_atencion').html(newOption);
                    }
                });
                $("#bloque-nivel").show();
            }
        });
        
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
                    <h3 class="panel-title">{{$consultorio->nombre}}</h3>
                    
                    <input type="hidden" name="id_consultorio" id="id_consultorio" value="{{$consultorio->id}}">
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/all_servicios">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
					<div class="col-md-12" style="margin-top:15px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Seleccione el establecimiento</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row" style="padding-bottom: 1rem;">
                                    <label for="tipo_atencion" class="col-lg-3 control-label">Establecimientos</label>
                                    <div class="col-lg-3">
                                        <select name="id_establecimiento" id="id_establecimiento" class="form-control">
                                            <option value="0">--SELECCIONAR--</option>
                                            @foreach ($establecimientos as $value)
                                                <option value="{{$value->id}}">{{$value->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="bloque-nivel">
                                    <input type="hidden" name="nivel" id="nivel" value="">
                                    <label for="tipo_atencion" class="col-lg-3 control-label">Tipo de Atención</label>
                                    <div class="col-lg-3">
                                        <select name="tipo_atencion" id="tipo_atencion" class="form-control">
                                        
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12" id="seccion_categoria">
            
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection