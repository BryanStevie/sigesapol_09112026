@extends('frontend.layouts.master')
@section('renderjs')
<script type="text/javascript">
    
    $cmbid_atencion = $("#frmHis").find("#id_atencion");
    $cmbid_atencion.select2({
        width: 'resolve' // need to override the changed default
    });
    $cmbid_servicio = $("#frmHis").find("#id_servicio");
    $cmbid_servicio.select2({
        width: 'resolve' // need to override the changed default
    });

    $("#divver").hide();

    $(document).ready(function () {
        $('#fecha_atencion').datepicker().on('hide', function (ev) {
            var fechaFinIni = $('#fecha_inicio').val();
            var fechaIni = $('#fecha_inicio').val();
            f1 = fechaIni.split("-");
            var fechaFin = $('#fecha_atencion').val();
            f2 = fechaFin.split("-");
            f3 = new Date();
            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
            if (f1 > f2) {
                $('#fecha_atencion').val(fechaFinIni);
                $('#fecha_atencion').change();
                bootbox.alert("La Fecha Fin no debe ser menor a la Fecha de Inicio");
                return false;
            }
        });

        $cmbid_atencion.change(function () {
            $this = $(this);
            cmbid_atencion = $cmbid_atencion.val(); 
            
            if(cmbid_atencion==1 || cmbid_atencion==3){
                var valida_medico = {{Auth::user()->flagservicios}};

                if(valida_medico != 1){ //consultorios del medico x atencion
                    option = {
                        url: '/obtener_consultorio_tipo_atencion_medico/' + {{ Auth::user()->id_establecimiento }} + '/'+ {{$id_medico}},
                        type: 'GET',
                        dataType: 'json',
                        data: {}
                    };

                }
                else{
                    option = {
                        url: '/obtener_consultorio_tipo_atencion/' + {{ Auth::user()->id_establecimiento }} + '/'+ cmbid_atencion,
                        type: 'GET',
                        dataType: 'json',
                        data: {}
                    };
                }
                
                $.ajax(option).done(function (data) {             
                        $('#id_servicio').prop('disabled', false); 
                        cargarComboDestino($cmbid_servicio, data);                    
                        $cmbid_servicio.val(null).trigger("change"); 
                });
                $("#divver").show();
            }
            else{
                if(cmbid_atencion==2){
                    getMedico();
                    $("#divver").hide();
                    
                }
            }
            
        });
    });

    function cargarComboDestino($select, data) {
        $select.html('');
        $(data).each(function (ii, oo) {
            $select.append('<option value="' + oo.id + '">' + oo.nombre + '</option>')
        });
    }

    function getMedico() {

        var id_establecimiento = $('#id_establecimiento').val();
        var id_atencion = $('#id_atencion').val();
        if(id_atencion == 2){
            var id_consultorio = 76;
        }
        else{
            var id_consultorio = $('#id_servicio').val();    
        }
        
        
        
        if(id_atencion==2){
            id_consultorio = 76;
        }

        $.ajax({
          url: '/obtener_medico_por_id_establecimiento_and_id_servicio/' + id_establecimiento + '/' + id_consultorio,
          success: function(result) {
            var newOption = "";
            var reg = result.length;
            if (reg >= 2) {
              newOption = "<option value=''>--Seleccionar-</option>";
            }
            $(result).each(function(ii, oo) {
              var medico = oo.paterno + " " + oo.materno + ", " + oo.nombre;
              newOption += "<option dni='" + oo.dni + "' email='" + oo.email + "' colegiatura='" + oo.colegiatura + "' value='" + oo.id + "'>" + medico + "</option>";
            });
            $('#id_medico').html(newOption);
            $("#id_medico").select2({
              max_selected_options: 4
            });

          }
        });
    }

    function getServicio() {

        var id_establecimiento = $('#id_establecimiento').val();
        var id_atencion = $('#id_atencion').val();
        
        if($id_atencion==1 || $id_atencion==3){
            $.ajax({
              url: '/obtener_consultorio_tipo_atencion/' + id_establecimiento + '/' + id_atencion,
              success: function(result) {
                var newOption = "";
                var reg = result.length;
                if (reg >= 2) {
                  newOption = "<option value=''>--Seleccionar-</option>";
                }
                $(result).each(function(ii, oo) {
                  newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                });
                $('#id_servicio').html(newOption);
                $("#id_servicio").select2({
                  max_selected_options: 4
                });

              }
            });    
        }
    }

    function generarHojaHis() {

            var id_atencion = $('#id_atencion').val();
            var id_medico = $('#id_medico').val();
            var fecha_atencion = $('#fecha_atencion').val();
            var id_servicio = $('#id_servicio').val();
            
            if (fecha_atencion == "") fecha_atencion = 0;
            if (id_servicio == "") id_servicio = 0;
            if (id_medico == "") id_medico = 0;
            if (id_atencion == "") id_atencion = 0;
            
            location.href = 'exportar_reporte_hoja_his/' + id_atencion + '/' + id_medico + '/' + id_servicio + '/' + fecha_atencion;
        
    }
    

</script>
@endsection
@section('content')
<div class="container col-md-9 col-md-offset-1">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Atenciones Médicas: Hoja Hiss - {{$nombre_farmacia}}
            </div>
        </div>
        <div class="panel-body">
            <form class="" method="post" action="{{ route('ver_hoja_his')}}" id="frmHis" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{Auth::user()->id_establecimiento}}">
                <div class="col-md-3">
                    <label class="help-block">Fecha Atención <i class="fa fa-calendar"></i></label>
                    <input id="fecha_atencion" name="fecha_atencion" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_actual}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"/>
                </div>
                @if (Auth::user()->flagservicios == 1)
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <label class="help-block">Atenciones <i class="fa fa-edit"></i></label>
                        <select class="form-control input-sm" name="id_atencion" id="id_atencion" required="">
                          <option value="0">-Seleccionar-</option>
                          <option value="1">CONSULTA EXTERNA</option>
                          <option value="2">EMERGENCIA</option>
                          <option value="3">HOSPITALIZACION</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3" id="divver" style="display:none">
                        <label class="help-block">SERVICIO</label>
                        <select class="form-control" name="id_servicio" id="id_servicio" onchange="getMedico();" required="" style="width: 100%">
                        <option value="0">--Seleccionar--</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="help-block">MEDICO</label>
                        <select class="form-control" id="id_medico" name="id_medico" tabindex="6" style="width: 100%">
                          <option value="0">--Seleccionar--</option>
                        </select>
                    </div>
                @else
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <label class="help-block">Atenciones <i class="fa fa-edit"></i></label>
                        <select class="form-control input-sm" name="id_atencion" id="id_atencion">
                          <option value="0">-Seleccionar-</option>
                          <option value="1">CONSULTA EXTERNA</option>
                          <option value="2">EMERGENCIA</option>
                          <option value="3">HOSPITALIZACION</option>
                        </select>
                    </div>
                    <?php if(count($atenciones)>1)  : ?>
                        <div  id="divver" style="display:none">
                    <?php else: ?>
                        <div>
                    <?php endif;?>
                        <div class="col-md-3">
                        @if(Auth::user()->id_establecimiento==76 && Auth::user()->id_consultorio==76)
                            <input type="hidden" name="id_servicio" id="id_servicio" value="76">

                        @else
                            <label class="help-block">SERVICIO</label>
                            <select class="form-control" name="id_servicio" id="id_servicio" required="" style="width: 100%">
                                <?php if(count($consultorio_medico)>1) : ?>
                                    <option value="0">--Seleccionar--</option>
                                <?php endif; ?>
                                @foreach($consultorio_medico as $c)
                                    <option value="{{$c->id_consultorio}}">{{$c->nom_consultorio}}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    </div>
                    <input type="hidden" name="id_medico" id="id_medico" value="{{$id_medico}}">
                @endif
		        <div class="col-md-3 pull-right" style="margin-top: 10px"><br/>
                    <button class="form-control btn btn-info" type="submit">Consultar</button>
                    <a href="javascript:void(0)" class="btn btn-success form-control input-sm" target="_blank"
                        onclick="generarHojaHis()">Exportar<i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
