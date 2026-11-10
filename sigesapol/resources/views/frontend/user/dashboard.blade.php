<?php
 use Illuminate\Support\Facades\DB;
    use App\Models\Permiso;
    ?>
@extends('frontend.layouts.master')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
.modal-headercorredor, h5, .close {
  background-color: #3c763d;
  color:white !important;
  text-align: center;
  font-size: 30px;
}

.modal-header, h4, .close {
      background-color: #3c763d;
      color:white !important;
      text-align: center;
      font-size: 30px;
    }
    .modal-footer {
      background-color: #f9f9f9;
    }
    .tituloIconflex{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap: 1rem;
    }
    .tituloIconControl{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 3rem;
        height: 100%;
    }
    .iconflex{
        display: flex;
        flex-direction: row;
        justify-content: left;
        align-items: center;
        gap: 1rem;
    }
    .iconControl{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 1rem;
        height: 100%;
    }
    
    #modalAvisoDias .modal-dialog {
        width: 600px;
        margin-top: 31px;
        font-family: 'Segoe UI', Arial, sans-serif; 
        margin-left: 34.4%;
    }
    
    #modalAvisoDias .modal-content {
        border-radius: 4px;
        border: 2px solid #2e6b30;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.35);
    }

    #modalAvisoDias .modal-header-custom {
        background-color: #2e7d32;
        color: #fff;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #modalAvisoDias .modal-header-custom h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        flex: 1;
        text-align: center;
    }

    #modalAvisoDias .modal-body-custom {
        background: #fff;
        padding: 20px 24px 10px;
        font-size: 14px;
        color: #222;
        line-height: 1.6;
        position: relative;
    }

    #modalAvisoDias .modal-body-custom::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, #bbb 1px, transparent 1px);
        background-size: 28px 28px;
        opacity: 0.18;
        pointer-events: none;
    }

    #modalAvisoDias .modal-body-custom > * {
        position: relative;
        z-index: 1;
    }

    #modalAvisoDias .label-title {
        font-weight: 700;
        margin-bottom: 6px;
    }

    #modalAvisoDias .warning-box {
        background-color: #fff8dc;
        border-left: 4px solid #e6a817;
        padding: 8px 12px;
        margin: 10px 0;
        font-size: 13.5px;
        border-radius: 2px;
    }

    #modalAvisoDias .dias-restantes {
        margin: 12px 0;
        font-size: 15px;
    }

    #modalAvisoDias .dias-restantes .numero {
        color: #d32f2f;
        font-weight: 800;
        font-size: 17px;
    }

    #modalAvisoDias .accion-box {
        border: 1px solid #ccc;
        padding: 8px 12px;
        margin: 10px 0;
        border-radius: 2px;
        background: #fafafa;
    }

    #modalAvisoDias .accion-box .label-title {
        margin-bottom: 2px;
    }

    #modalAvisoDias .nota-final {
        font-size: 13px;
        color: #333;
        border-top: 1px solid #e0e0e0;
        padding-top: 10px;
        margin-top: 10px;
    }

    #modalAvisoDias .nota-final p {
        margin-bottom: 4px;
    }

    #modalAvisoDias .modal-footer-custom {
        background: #fff;
        border-top: 1px solid #ddd;
        padding: 12px 20px;
        display: flex;
        justify-content: center;
        gap: 16px;
    }

    #modalAvisoDias .btn-cancelar {
        background-color: #e53935;
        color: #fff;
        border: none;
        padding: 9px 36px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 12px;
        cursor: pointer;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: background 0.2s;
    }

    #modalAvisoDias .btn-cancelar:hover {
        background-color: #b71c1c;
    }

    #modalAvisoDias .btn-aceptar {
        background-color: #2e7d32;
        color: #fff;
        border: none;
        padding: 9px 36px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 12px;
        cursor: pointer;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        text-decoration: none;
        display: inline-block;
        line-height: 1.4;
        transition: background 0.2s;
    }

    #modalAvisoDias .btn-aceptar:hover {
        background-color: #1b5e20;
        color: #fff;
    }

    #modalAvisoDias .close{
        margin-top: -6px;
        color: white;
        opacity: 1;
        font-size: 24px;
    }

</style>
<script>
      $(window).on('load', function() {
    let fpipT = $('#fpipTriaje').val();
    let bolModuLlamaT = $('#bol_corredorTriaje').val();
    //let fpip = $('#fpip').val();
    let fmll = $('#fmll').val();
    let bolModuLlama = $('#bol_moduloLlamado').val();
    let ventanilla = $('#id_ventanilla_user').val();

    if (fpipT == 'true' && bolModuLlamaT == 'true') {
        $('#myModalcorredor').modal();
    }

    //if (fpip == 'true' && bolModuLlama == 'true') {
    if (fmll == 'true' && bolModuLlama == 'true') {
        if (ventanilla == '') {
            $("#myModalModuloLlamada").modal();
            $('#id_corredor').select2();
            $('#id_ventanilla').select2();	     
        } 
    }    
});
    

function editStatusTriaje() {

    var id_corredor = $('#id_corredor').val();
    $.ajax({
        url: '/editar_estado_triaje/' + id_corredor,
        success: function(result) {
            //window.location.reload();
            window.location = '/modulo_triaje';
        }
    });
    
    }

//==============Get Ventanilla por Corredor============================//
    function getCorredores(){
        let id_corredor = $('#id_corredor_m').val();
        $.ajax({
            url: '/obtenerCorredores/' + id_corredor,
            success: function(result){                    
                let newOption = "";
                newOption = "<option value='0'>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id+"'>"+oo.nombre_ventanilla+"</option>";
                });                 
                $('#id_ventanilla').html(newOption);
                //$("#id_ventanilla").select2({max_selected_options: 4});
            }
        });
    }
    
    function getVentanilla(){
        
        //let id_corredor = $('#id_corredor').val();
        let id_corredores_detalle = $('#id_ventanilla').val();
        $.ajax({
            url: '/obtenerVentanillaPorCorredor/' + id_corredores_detalle,
            //url: 'obtenerVentanillaPorCorredor',
            success: function(result){
                //console.log(result);
                location.reload();
            }
        });
    }
    
    $(document).ready(function() {
        // onClickGetUpdateCatalog();
        @if (isset($dias_restantes) && $dias_restantes > 0)
            $('#modalAvisoDias').modal('show');
        @endif
    });

</script>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">Dashboard</div>
            @if(Auth::user()->flagresponsablefarmacia==1 and Auth::user()->id_farmacia!=113) 
            <div class="panel-body">
                <div class="row">

                    <div class="col-lg-2 col-xs-3">
                      <!-- small box -->                        
                            <a href="{!!url('add_producto_cpm')!!}"class="btn btn-primary btn-xs">Editar CPM <i class="fa fa-arrow-circle-right"></i></a>                        
                    </div>  
                    <!-- ./col -->
                    <div class="col-lg-2 col-xs-3">
                      <!-- small box -->                        
                        <a href="{!!url('famarcia_cpm/'.Auth::user()->id.'/4')!!}" class="btn btn-warning btn-xs">Productos Criticos <i class="fa fa-arrow-circle-right"></i></a>
                    </div>  
                    
                    <!-- ./col -->
                    <div class="col-lg-2 col-xs-3">
                      <!-- small box -->
                            <a href="{!!url('famarcia_cpm/'.Auth::user()->id.'/1')!!}" class="btn btn-danger btn-xs">Productos Desabastecido <i class="fa fa-arrow-circle-right"></i></a>
                    </div>  
                    <!-- ./col -->
                    <div class="col-lg-2 col-xs-3">
                      <!-- small box -->                        
                            <a href="{!!url('nuevos_registros_sb')!!}" class="btn btn-default btn-xs">Ingreso Productos San Borja <i class="fa fa-arrow-circle-right"></i></a>                        
                    </div>                    
                    <!-- ./col -->
                    <div class="col-lg-2 col-xs-3">
                      <!-- small box -->
                            <a href="{!!url('productos_proximos_vencer_cpm/'.Auth::user()->id_farmacia)!!}" class="btn btn-success btn-xs">Productos Próximos a Vencer <i class="fa fa-arrow-circle-right"></i></a>
                    </div>  
                    <!-- ./col -->
                    <div class="col-lg-2 col-xs-3">
                      <!-- small box -->                        
                            <a href="{!!url('productos_vencidos_cpm/'.Auth::user()->id_farmacia)!!}" class="btn btn-info btn-xs">Productos Vencidos <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div> 
            </div><!--panel body-->
            @endif
            <div class="panel-body">
                            
                <div role="tabpanel">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">MI PERFIL</a></li>
                    </ul>

                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <table class="table table-striped table-hover table-bordered dashboard-table">
                                <tr>
                                    <th>Documento de Identidad</th>
                                    <td>{!! $user->dni !!}</td>
                                </tr>
                                <tr>
                                    <th>Nombre y Apellidos</th>
                                    <td>{!! $user->name !!} {!! $user->paterno !!} {!! $user->materno !!}</td>
                                </tr>                                
                                <tr>
                                    <th>{{ trans('validation.attributes.email') }}</th>
                                    <td>{!! $user->email !!}</td>
                                </tr>
                                <tr>
                                    <th>Celular</th>
                                    <td>{!! $user->celular !!}</td>
                                </tr>
                                <tr>
                                    <th>Editar</th>
                                    <td>
                                        <!--<a href="{!!route('frontend.profile.edit')!!}" class="btn btn-primary btn-xs">{{ trans('labels.edit_information') }}</a>-->
                                        <!--<a href="/editar_mis_datos/{{$user->id}}"  class="btn btn-primary btn-xs">Editar Información</a>-->
                                        <a href="{!!url('auth/password/change')!!}" class="btn btn-warning btn-xs">{{ trans('navs.change_password') }}</a>
                                    </td>
                                </tr>
                            </table>
                        </div><!--tab panel profile-->

                    </div><!--tab content-->
                </div><!--tab panel-->
            </div><!--panel body-->
        </div><!-- panel -->
    </div><!-- col-md-10 -->
</div><!-- row -->

<div class="modal fade" id="myModalcorredor" role="dialog">
    <div class="modal-dialog">
        <?php
        $model_corredor = new \App\Models\Coredor();
        $corredores = $model_corredor->ShowAll();
        $moduLlamaCorredor= Permiso::where('idusuario', auth()->user()->id)
                    ->where('idmenu', 119)
                    ->where('crear', "1")
                    ->where('modificar', "1")
                    ->where('eliminar', "1")
                    ->where('lectura', "1")->first();
    $moduloLlamadoPermisoCorredor = !empty($moduLlamaCorredor) ? "true" : "false"; 
        ?>
         <input type="hidden" name="bol_corredorTriaje" id="bol_corredorTriaje" value="{{$moduloLlamadoPermisoCorredor}}"/>
         <input type="hidden" name="id_corredor_triaje" id="id_corredor_triaje" value="{{auth()->user()->id_corredor_triaje}}"/>
        <div class="modal-content">
            @if (auth()->user()->id_establecimiento == 76 && auth()->user()->id_farmacia == 121)
            <input type="hidden" name="fpipTriaje" id="fpipTriaje" value="true"/>
        @else
        <input type="hidden" name="fpipTriaje" id="fpipTriaje" value="false"/>
        @endif

            <div class="modal-headercorredor" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5><span class="glyphicon glyphicon-lock"></span> Seleccionar Corredor</h5>
            </div>
            @if (Session::get('flash_danger'))
                <div class="alert alert-danger" style="text-align: center;">
                    @if(is_array(json_decode(Session::get('flash_danger'),true)))
                        {!! implode('', Session::get('flash_danger')->all(':message<br/>')) !!}
                    @else
                        {!! Session::get('flash_danger') !!}
                    @endif
                </div>
            @endif
            <div class="modal-body" style="padding:40px 50px;">
                <form role="form">
                    <div class="form-group">
                        <label for="usrname"><span class="glyphicon glyphicon-home"></span> Corredor</label>
                        <div class="col-lg-12">
                            <select class="form-control" onchange="editStatusTriaje()" name="id_corredor" id="id_corredor" style="width:100%">
                                <option value="0">-Seleccionar-</option>
                                @foreach ($corredores as $f)
                                    <option value="{{ $f->id }}"> {{ $f->nombre }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
    $card = "select c.id, c.nombre, c.estado from corredores c where c.estado = '1'";
    $corredores_modulo_llamada = DB::select($card);
    $moduLlamaPerm = Permiso::where('idusuario', auth()->user()->id)
                    ->where('idmenu', 113)
                    ->where('crear', "1")
                    ->where('modificar', "1")
                    ->where('eliminar', "1")
                    ->where('lectura', "1")->first();
    $moduloLlamadoPermiso = !empty($moduLlamaPerm) ? "true" : "false"; 
?>
<input type="hidden" name="bol_moduloLlamado" id="bol_moduloLlamado" value="{{$moduloLlamadoPermiso}}"/>
<input type="hidden" name="id_ventanilla_user" id="id_ventanilla_user" value="{{auth()->user()->id_ventanilla}}"/>
@if (auth()->user()->flagmodulollamado == 1)
    <input type="hidden" name="fmll" id="fmll" value="true"/>
@else
    <input type="hidden" name="fmll" id="fmll" value="false"/>
@endif
<!-- Modal Modulo Llamada-->
<div class="modal fade" id="myModalModuloLlamada" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="tituloIconflex">
            <div class="tituloIconControl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            Ir a Módulo de Llamada
          </h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form">
            <div class="form-group">
              <label for="usrname" class="iconflex">
                <div class="iconControl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25l-3-3m0 0l-3 3m3-3v7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                Corredor
              </label>
			  <div class="col-lg-12">
                <select class="form-control" name="id_corredor_m" id="id_corredor_m" onchange="getCorredores()" style="width:100%">
                    <option value="0">-Seleccionar-</option>
                    @foreach($corredores_modulo_llamada as $c)
                    <option value="{{$c->id}}">{{$c->nombre}}</option>
                    @endforeach
                </select>
			  </div>
            </div>
			<div class="clearfix" style="margin-bottom:20px"></div>
            <div class="form-group">
              <label for="psw" class="iconflex">
                <div class="iconControl">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                  </svg>
                </div>  
                Ventanilla
              </label>
			  <div class="col-lg-12">
                <select class="form-control" name="id_ventanilla" id="id_ventanilla" style="width:100%" onchange="getVentanilla()" >
                    <option value="0">-Seleccionar-</option>
			    </select>
			  </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

<!-- Modal Aviso Dias Personal-->
@if(isset($dias_restantes) && $dias_restantes > 0)
    <div class="modal fade" id="modalAvisoDias" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header-custom">
                    <h4>🚨 Alerta importante: Plazo de actualización de datos</h4>
                     <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body-custom">
                    <p class="label-title">Atención, usuario:</p>
                    <p>
                        Tu registro requiere una actualización obligatoria de datos en un
                        <b>plazo máximo de 7 días calendario</b>, contados a partir de esta notificación.
                    </p>

                    <div class="warning-box">
                        ⚠️ Si no completas este proceso antes de la fecha límite, tu acceso al SIGESAPOL
                        podría ser suspendido o limitado.
                    </div>

                    <p class="dias-restantes">
                        ⏳ Días restantes: <span class="numero">{{ $dias_restantes }}</span>
                    </p>

                    <div class="accion-box">
                        <p class="label-title">Acción requerida:</p>
                        <p>Da click en el botón "ACEPTAR" para actualizar tu información.</p>
                    </div>

                    <div class="nota-final">
                        <p><b>No dejes pasar el tiempo. La responsabilidad de actualizar tus datos es totalmente tuya.</b></p>
                        <p><b>Actualiza hoy. Evita contratiempos.</b></p>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancelar" data-dismiss="modal">Cancelar</button>
                    @if ($id_medico && $id_farmacia)
                        <a href="{{ url('/editar_medico_saenz/'.$id_medico->id.'/'.$id_farmacia) }}?actualizar=1"
                        target="_blank" rel="noopener noreferrer"
                        class="btn-aceptar">Aceptar</a>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endif

@include('frontend.includes.footer')
@endsection
