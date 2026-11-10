<?php
use Carbon\Carbon;

        if(Auth::user()->flagresponsablefarmacia==1){         
            //Calcular Fecha limite para las modificaciones
            $date = Carbon::now();
            $fecha = $date->format('d-m-Y');   
            $fechaIngreso = Carbon::parse($movimiento_historias->created_at);
            $fechaActual = Carbon::parse($fecha);
            $tiempo = $fechaActual->diffInDays($fechaIngreso);
        }
        else{
            $tiempo=4;
        }

?>
@extends('frontend.layouts.master2')
@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<style>
    .etiqueta{margin-top: 5px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
    .ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 7px;
    margin: 2px 0 0;
    list-style: none;
    font-size: 15px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }

  .ui-autocomplete>li>div {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333333;
    white-space: nowrap;
  }

  .ui-state-hover,
  .ui-state-active,
  .ui-state-focus {
    text-decoration: none;
    color: #FFFFFF !important;
    background: #1d71b8 !important;
    cursor: pointer;
  }

  .ui-helper-hidden-accessible {
    border: 0;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>

    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
	
	function escapeRegExp(string){
		return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
	}

	function replaceAll(str, term, replacement) {
	  return str.replace(new RegExp(escapeRegExp(term), 'g'), replacement);
	}

    $(document).ready(function () {

        
    });

    function validateFrm() {
        var msg = "";
        var sw = true;
        var nro_entrada = $('#nro_entrada').val();
        var nro_entrada_actu = $('#nro_entrada_actu').val();
        
        if (nro_entrada == "0") {
            msg += "EL número de entrada debe ser diferente a 0.<br/>";
            sw = false
        }
        
        if (esEntero(nro_entrada) == false) {
            msg += "EL número de entrada debe ser un número entero.<br/>";
            sw = false
        }
        if (parseInt(nro_entrada) < parseInt(nro_entrada_actu)) {
            msg += "La numero de entrada no debe ser menor al último correlativo generado.<br/>";
            sw = false
        }        
        

        if (sw == false) {
            bootbox.alert(msg);
            return sw;
        } else {
            submitFrm();
        }
        return false;
    }
    
    function submitFrm() {
        bootbox.confirm({
            message: "Se recepcionarán todas las historias mostrados, ¿Está seguro de continuar?",
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result == true) {
                    document.frm_transferencia.submit();
                }
            }
        });
    }


</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-2">
      <div class="col-sm-12 pull-right">
          <ul class="nav nav-pills nav-stacked">
            <li><a href="/all_movimiento_hc/"><i class="fa fa-table fa-fw"></i> Listar Salidas de Historias</a></li>
            <li><a href="/add_hc_registro_salida/{{ Auth::user()->id}}"><i class="fa fa-table fa-fw"></i> Registrar Salidas</a></li>
            <li  class="active"><a href="/all_historias_recibidas/{{ Auth::user()->id}}"><i class="fa fa-table fa-fw"></i> Ingreso de Historias</a></li>
            <li><a href="/nueva_historia/"><i class="fa fa-table fa-fw"></i> Nueva Historia</a></li>
            <li><a href="/buscar_movimiento_historia/"><i class="fa fa-table fa-fw"></i> Buscar Historias</a></li>
          </ul>
        
      </div>
    </div>
    <div class="col-sm-10">
      <div class="panel panel-body">
          <legend style="text-align:center">{{$establecimiento->nombre}} <br/> {{$nombre_consultorio->nombre}}</legend>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Movimiento de Historia Clínica: {{$origen->nombre}} - {{$nombre_consultorio->nombre}} <br>
                    <span style="font-size:12px">Nro. Orden: <b style="font-size:17px">{{$movimiento_historias->nro_movimiento}}</b></span><br>
                        
                    <span style="font-size:12px">Fecha: {{$movimiento_historias->fecha}}</span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="col-md-12" style="margin-top:15px">
                    <form name="frm_transferencia" id="frm_transferencia" method="POST" action="{{ route('recepcionar_hc_servicio')}}" onsubmit="return validateFrm();">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="row">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    Historias Clínicas por Recepcionar
                                    @if($movimiento_historias->estado==2)
                                    <div class="pull-right">
                                        Nota de Entrada &nbsp;&nbsp;
                                        <input autofocus onkeypress="return justNumbers(event);" type="text" name="nro_entrada" id="nro_entrada" style="width: 100px; text-align: center;" required="" value="{{$hc->nro_hc}}" autocomplete="OFF">
                                        -<?php echo date("Y") ?>&nbsp;
                                        <button class="btn btn-success btn-md pull-right" type="submit">
                                            <i class="fa fa-folder-open"></i> Recepcionar Historias
                                        </button>
                                        <input type="hidden" name="id_nro_movimiento" value="{{ $movimiento_historias->id }}">
                                        <input type="hidden" name="nro_entrada_actu" id="nro_entrada_actu" value="{{$hc->nro_hc}}"/>
                                        <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$movimiento_historias->id_ipress}}"/>
                                        <input type="hidden" name="id_servicio" id="id_servicio" value="{{$movimiento_historias->id_consultorio}}"/>
                                    </div>
                                    @else
                                    <div class="pull-right">Nro de Ingreso: {{$movimiento_historias->nro_entrada}}</div>
                                    @endif
                                </h3>     
                                <br/>  
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table color" style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tipo Documento</th>
                                                <th>Nro Documento</th>
                                                <th>Nro Historia</th>
                                                <th>Paciente</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php $x=1; ?>
                                            @foreach($historias_asignadas as $hc)
                                            <tr>
                                                <td><?php echo $x++; ?></td>
                                                <td>{{$hc->tipo_doc_ident}}</td>
                                                <td>{{$hc->nro_doc_ident}}</td>
                                                <td>{{$hc->nro_historia}}</td>
                                                <td>{{$hc->nombre}}, {{$hc->paterno}} {{$hc->materno}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
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
