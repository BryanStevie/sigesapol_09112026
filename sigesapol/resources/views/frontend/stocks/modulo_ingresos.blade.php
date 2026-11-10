@extends('frontend.layouts.master')
@section('renderjs')

<style>
    /* Blue Flat Button
    ==================================================*/
    .btn-xlarge{
        position: relative;
        vertical-align: center;
        margin: 30px;
        width: 250px;
        height: 50x;
        padding: 28px 28px;
        font-size: 13px;
        color: white;
        text-align: center;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        border: 0;
        border-bottom: 3px solid #9FE8EF;
        cursor: pointer;
        -webkit-box-shadow: inset 0 -3px #9FE8EF;
        box-shadow: inset 0 -3px #9FE8EF;
    }
    .btn-xlarge:active {
        top: 2px;
        outline: none;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    .btn-xlarge:hover {
        background: #999;
    }
</style>

<script>

</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <!--<a href="#"  class="btn btn-xlarge btn-primary" data-toggle="modal" data-target="#squarespaceModal"/>
        Ingresar Productos <br> Farmacéuticos<br> y Dispositivos Médicos<br><i class="fa fa-ambulance fa-2x"></i>
        </a>-->
        <!---Comentado-->
            <!--<a href="/seleccion_requerimiento/{{ Auth::user()->id }}"  class="btn btn-xlarge btn-primary"/>
                Ingresar Productos <br> Farmacéuticos<br> y Dispositivos Médicos<br><i class="fa fa-ambulance fa-2x"></i>
            </a>
            <a href="/seleccion_requerimiento/{{ Auth::user()->id }}"  class="btn btn-xlarge btn-primary"/>
                Ingresar Productos <br> Farmacéuticos<br> y Dispositivos Médicos<br><i class="fa fa-ambulance fa-2x"></i>
            </a>--> 
        <!-- Fin comentado --->               
            <a href="/registrar_pecosa/{{ Auth::user()->id_farmacia}}"  class="btn btn-xlarge btn-primary"/>
                Ingresar Productos <br> Farmacéuticos<br> y Dispositivos Médicos<br><i class="fa fa-ambulance fa-2x"></i>
                <br>desde Almacen Central
            </a>    
        
            <a href="/all_transferencias_recibidas/{{ Auth::user()->id_farmacia }}"  class="btn btn-xlarge btn-success"/>
                Ingresar Productos <br> Farmacéuticos<br> y Dispositivos Médicos<br><i class="fa fa-ambulance fa-2x"></i>
                <br>desde Otros Establecimientos PNP
            </a>
        <!--<a href="/form_generar_rerporte_ici/{{ Auth::user()->id }}"  class="btn btn-xlarge btn-primary" />Generar Requerimientos<br>de Productos <br> a través del Reporte ICI<br><i class="fa fa-ambulance fa-2x"></i></a>-->
    </div>
    <!--<div class="row">
        <a href="#"  class="btn btn-xlarge btn-success" />Reporte de Ingresos <br> de Productos Farmacéuticos<br> y Dispositivos Médicos<br><i class="fa fa fa-file-text-o fa-2x"></i></a>
        <a href="/formulario_add_juntamedica/{{ Auth::user()->id }}"  class="btn btn-xlarge btn-success" />Reporte de Ingresos de Productos<br>Farmacéuticosy Dispositivos<br> Médicos por Junta Médica<br><i class="fa fa fa-file-text-o fa-2x"></i></a>
        <a href="/ver_requerimiento/{{ Auth::user()->id }}""  class="btn btn-xlarge btn-success" />Reporte de Requerimientos<br>de Productos <br> a través del Reporte ICI<br><i class="fa fa fa-file-text-o fa-2x"></i></a>        
    </div>-->
</div>

<!-- line modal -->
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('opcion_ingreso_regular')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="lineModalLabel">Ingresar Productos Farmacéuticos y Dispositivos Médicos</h4>
            </div>
            <div class="modal-body">
                <!-- content goes here -->
                <div class="form-group">
                    <label for="exampleInputEmail1">A travéz de:</label>  
                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                    <select class="form-control" required="" name="opcion">
                        <option value="1">Con Requerimiento</option>
                        <option value="2">Sin Requerimiento</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  role="button">Cerrar</button>
                    </div>
                    <div class="btn-group btn-delete hidden" role="group">
                        <button type="button" id="delImage" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Ingresar</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
