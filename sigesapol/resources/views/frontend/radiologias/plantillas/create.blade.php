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
    $tipo = $("#frmplantilla").find("#id_servicio");
    $tipo.select2();

    function validateFrm(){
        let msj = '';
        $('#btnReg').prop('disabled', true);

        if($('#nombre_plantilla').val() =='') {
          $('#nombre_plantilla').addClass('borde-alerta');
          $("#nombre_plantilla").focus();
          $('#btnReg').prop('disabled', false);
          return false;
        } else {
          $('#nombre_plantilla').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }

        if($('#id_servicio').val() =='') {
          $('#id_servicio').next('.select2-container').find('.select2-selection--single').addClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
          return false;
        } else {
          $('#id_servicio').next('.select2-container').find('.select2-selection--single').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }

        let titulo = sceditor.instance($('#titulo').get(0)).val();
        let cuerpoPlantilla = sceditor.instance($('#cuerpo_plantilla').get(0)).val();
        if ($.trim(titulo) === '<p><br></p>' || $.trim(cuerpoPlantilla) === '<p><br></p>') {
            msj = 'Falta completar titulo o el cuerpo de la plantilla';
            
            Swal.fire({
                icon: 'error',
                title: 'Alerta',
                html: msj,
                showConfirmButton: true,
            });
            $('#btnReg').prop('disabled', false);
            return false;
        } else {
            $('#titulo').val(sceditor.instance($('#titulo').get(0)).val());
            $('#cuerpo_plantilla').val(sceditor.instance($('#cuerpo_plantilla').get(0)).val());
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
                    $("#frmplantilla").submit();
                } else {
                    $('#btnReg').prop('disabled', false);
                }
            }
        });
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
                <li><a href="/create_servicio_radiologia"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                <li class="active"><a href="/create_plantilla_radiologia"><i class="fa fa-plus"></i> Nuevo Plantilla</a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Nuevo Tipo de Plantilla</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmplantilla" class="form-horizontal" role="form" method="post"  action="{{ route('store_plantilla_radiologia') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12" style="margin-bottom: 1rem;">
                            <label class="control-label">Tipo de Plantilla <span class="obligatorio">(*)</span> </label>
                            <input name="nombre_plantilla" id="nombre_plantilla" onkeypress="return noEspaciosInicioSoloLetras(event,'nombre_plantilla');" type="text" class="form-control" required="" placeholder="Nombre de la Plantilla" autocomplete="OFF">
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>SERVICIO <span class="obligatorio">(*)</span></label>
                                    <select class="form-control" name="id_servicio" id="id_servicio" required="">
                                        <option value="">Seleccionar</option>
                                        @foreach ( $lista_categorias as $lc )
                                        <option value="{{ $lc->id }}">{{ $lc->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>Título de Plantilla</label>
                              <textarea id="titulo" required="" name="titulo" rows="2" style="height:300px;width:100%;" class="borde-alerta"></textarea>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>Descripción de Plantilla</label>
                              <textarea id="cuerpo_plantilla" required="" name="cuerpo_plantilla" rows="2" style="height:300px;width:100%;"></textarea>
                            </div>
                          </div>
                        </div>
                        <!--div class="col-sm-12 col-md-12">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>Footer de Plantilla</label>
                              <textarea id="footer_plantilla" required="" name="footer_plantilla" rows="2" style="height:300px;width:100%;"></textarea>
                            </div>
                          </div>
                        </div-->
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input id="btnReg" class="btn btn-success" tabindex="12" value="Guardar" type="button" onclick="validateFrm()">
                            </div>
                        </div> 
                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('../../sceditor/minified/sceditor.min.js') }}"></script>
<script src="{{ asset('../../sceditor/languages/es.js') }}"></script>
<script src="{{ asset('../../sceditor/minified/icons/monocons.js') }}"></script>
<script src="{{ asset('../../sceditor/minified/formats/bbcode.js') }}"></script>
<script>
    var textarea = document.getElementById('titulo');
    sceditor.create(textarea, {
        icons: 'monocons',
        locale: 'es-ES',
        style: '../../sceditor/minified/themes/content/default.min.css'
    });
    var textarea = document.getElementById('cuerpo_plantilla');
    sceditor.create(textarea, {
        icons: 'monocons',
        locale: 'es-ES',
        style: '../../sceditor/minified/themes/content/default.min.css'
    });
    // var textarea = document.getElementById('footer_plantilla');
    // sceditor.create(textarea, {
    //     icons: 'monocons',
    //     locale: 'es-ES',
    //     style: '../../sceditor/minified/themes/content/default.min.css'
    // });
</script>
@include('frontend.includes.footer')
@endsection