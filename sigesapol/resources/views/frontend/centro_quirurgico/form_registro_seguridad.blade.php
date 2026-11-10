<style>
    .intro-contenedor {
        padding-top: 1rem;
    }
</style>
<script>
  $(document).ready(function() {
    // INICIO REGISTRO DE SEGURIDAD
    $('#addFase1Seguridad').on('click', function () {
      console.log("CLic fase 1");
      const addFase1Seguridad = $('#addFase1Seguridad').attr("aria-pressed");
      $("#body-fase-1-seg").hide();
      if(addFase1Seguridad == "false"){
        $("#body-fase-1-seg").show();
      }
    }); 
    $('#addFase2Seguridad').on('click', function () {
      const addFase2Seguridad = $('#addFase2Seguridad').attr("aria-pressed");
      $(".body-fase-2-seg").hide();
      if(addFase2Seguridad == "false"){
        $(".body-fase-2-seg").show();
      }
    }); 
    $('#addFase3Seguridad').on('click', function () {
      const addFase3Seguridad = $('#addFase3Seguridad').attr("aria-pressed");
      $(".body-fase-3-seg").hide();
      if(addFase3Seguridad == "false"){
        console.log('FAse 3 visible');
        $(".body-fase-3-seg").show();
      }
    }); 
    $('#addRawSeguridad').on('click', function () {
      const addRawSeguridad = $('#addRawSeguridad').attr("aria-pressed");
      $(".rawDescrip").hide();
      if(addRawSeguridad == "false"){
        $(".rawDescrip").show();
      }
    });
    // FIN REGISTRO DE SEGURIDAD
  });
</script>

              <div style=" font-size: 11px" class="panel-body">
                  <form name="frmIntraoperario" id="frmIntraoperario" class="form-horizontal" role="form" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">
                                INFORMACIÓN DEL PACIENTE
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row intro-contenedor">
                                <div class="col-lg-3">
                                    <label>DOCUMENTO DE IDENTIDAD</label>
                                    <input type="text" readonly="" value="DNI: 09924042" name="documento_identidad" id="documento_identidad" class="form-control">
                                </div>
                                <div class="col-lg-4">
                                    <label class="control-label">APELLIDO Y NOMBRES</label>
                                    <input type="text" class="form-control" readonly=""  value="CONDOR" name="asegurado_nombres_completos" id="asegurado_nombres_completos">
                                </div>
                                <div class="col-lg-3">
                                    <label>PARENTESCO</label>
                                    <input type="text" class="form-control" readonly="" value="TITULAR"  name="parentesco" id="parentesco">
                                </div>
                                <div class="col-lg-2">
                                    <label>FECHA</label>
                                    <input type="text" class="form-control" readonly="" value="19-08-2024"  name="" id="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info contenedor-datos-generales">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">
                                INTERVENCIÓN / PROCEDIMIENTO
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="col-lg-6">
                                <div class="row intro-contenedor">
                                    <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                        <label style="margin-left: 2rem;">PROGRAMADO :</label>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="90" type="button" id="addProgramado" class="btn btn-lg btn-toggle"
                                            data-toggle="button" aria-pressed="false" autocomplete="off">
                                            <div class="handle"></div>
                                        </button>
                                    </div>
                                </div>
                                <div class="row intro-contenedor">
                                    <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                        <label style="margin-left: 2rem;">CIRUJANO :</label>
                                    </div>
                                </div>
                                <div class="row intro-contenedor">
                                    <div class="col-lg-6">
                                        <label>DOCUMENTO DE IDENTIDAD</label>
                                        <input type="text" readonly="" value="" name="" id="" class="form-control">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>APELLIDOS Y NOMBRES</label>
                                        <input type="text" readonly="" value="" name="" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="row intro-contenedor">
                                    <div class="col-lg-6">
                                        <label>HORA DE INGRESO C. QUIRÚRGICO</label>
                                        <input type="text" readonly="" value="" name="" id="" class="form-control">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>N° QUIROFANO</label>
                                        <input type="text" readonly="" value="" name="" id="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row intro-contenedor">
                                    <div class="col-lg-3" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                        <label style="margin-left: 2rem;">EMERGENCIA</label>
                                    </div>
                                    <div class="col-sm-3" style="margin-left:0px;padding-left:0px">
                                        <label style="margin-left: 2rem;">(&nbsp;&nbsp;<span>X</span>&nbsp;&nbsp;)</label>
                                    </div>
                                    <div class="col-lg-3" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                        <label style="margin-left: 2rem;">URGENCIA</label>
                                    </div>
                                    <div class="col-sm-3" style="margin-left:0px;padding-left:0px">
                                        <label style="margin-left: 2rem;">(&nbsp;&nbsp;<span></span>&nbsp;&nbsp;)</label>
                                    </div>
                                </div>
                                <div class="row intro-contenedor">
                                    <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
                                        <label style="margin-left: 2rem;">ANASTESIÓLOGO :</label>
                                    </div>
                                </div>
                                <div class="row intro-contenedor">
                                    <div class="col-lg-6">
                                        <label>DOCUMENTO DE IDENTIDAD</label>
                                        <input type="text" readonly="" value="" name="" id="" class="form-control">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>APELLIDOS Y NOMBRES</label>
                                        <input type="text" readonly="" value="" name="" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="row intro-contenedor">
                                    <div class="col-lg-6">
                                        <label>HORA DE TERMINO</label>
                                        <input type="time" class="form-control" style="width: 100%;" id="" name="" min="08:00" max="18:00" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info contenedor-datos-generales">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo" style="display: inline;">
                                FASE 1 : ANTES DE LA INDUCCIÓN ANESTÉSICA
                            </h3>
                            <button tabindex="90" type="button" id="addFase1Seguridad" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                            </button>
                        </div>
                        <div class="panel-body body-fase-1-seg" id="body-fase-1-seg" style="display: none;">
                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <label>RAW</label>
                                </div>
                                <div class="col-lg-2">
                                    <button tabindex="90" type="button" id="addRawSeguridad" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-6 rawDescrip" style="display: none;">
                                <label>DESCRIPCIÓN</label>
                                <input type="text" readonly="" value="" name="" id="" class="form-control">
                            </div>

                            <div class="col-lg-12" style="margin-top: 2rem;">
                                <div class="panel panel-default contenedor-datos-generales">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo" style="display: inline;">
                                            IDENTIFICACIÓN DEL PACIENTE Y ZONA OPERATORIA
                                        </h3>
                                    </div>
                                    <div class="panel-body body-fase-1-seg" style="display: none;">
                                        <div class="col-lg-12">
                                            <table class="table">
                                                <tr>
                                                    <td>- EL ANESTESIÓLOGO CONFIRMA LA SEGURIDAD DE LA ANESTESIA (EQUIPOS, INSUMOS Y MEDICAMENTOS)</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- EL ANESTESIÓLOGO CONFIRMA LA SEGURIDAD DE LA ANESTESIA</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- SE EVALÚA EL RIESGO DE VÍA AÉREA DIFÍCIL</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- PARA LOS CASOS DE VÍA AÉREA DIFÍCIL SE CONFIRMA LA EXISTENCIA DE EQUIPOS ASISTENCIA DISPONIBLE</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- SE EVALÚA PÉRDIDA DE SANGRE 500 ML. (7ML / KG EN NIÑOS)</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- PARA LOS CASOS DE RIESGO DE PÉRDIDA DE SANGRE SE CONFIRMA LA EXISTENCIA DE ACCESO ADECUADO Y FLUIDOS NECESARIOS</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- SE CONFIRMA LA ADMINISTRACIÓN DE ANTIBIÓTICOS PROFILÁCTICOS EN LOS ÚLTIMO 60 MINUTOS</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- SE VERIFICA OXÍMETRO DE PULSO FUNCIONANDO</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default contenedor-datos-generales">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo" style="display: inline;">
                                            MÉDICO ANESTESIÓLOGO
                                        </h3>
                                    </div>
                                    <div class="panel-body body-fase-1-seg" style="display: none;">
                                        <div class="col-lg-12">
                                            <table class="table">
                                                <tr>
                                                    <td>- EL ANESTESIÓLOGO CONFIRMA LA SEGURIDAD DE LA ANESTESIA (EQUIPOS, INSUMOS Y MEDICAMENTOS)</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- EL ANESTESIÓLOGO CONFIRMA LA SEGURIDAD DE LA ANESTESIA</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- SE EVALÚA EL RIESGO DE VÍA AÉREA DIFÍCIL</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- PARA LOS CASOS DE VÍA AÉREA DIFÍCIL SE CONFIRMA LA EXISTENCIA DE EQUIPOS ASISTENCIA DISPONIBLE</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- SE EVALÚA PÉRDIDA DE SANGRE 500 ML. (7ML / KG EN NIÑOS)</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- PARA LOS CASOS DE RIESGO DE PÉRDIDA DE SANGRE SE CONFIRMA LA EXISTENCIA DE ACCESO ADECUADO Y FLUIDOS NECESARIOS</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- SE CONFIRMA LA ADMINISTRACIÓN DE ANTIBIÓTICOS PROFILÁCTICOS EN LOS ÚLTIMO 60 MINUTOS</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- SE VERIFICA OXÍMETRO DE PULSO FUNCIONANDO</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default contenedor-datos-generales">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo" style="display: inline;">
                                            ENFERMERA INSTRUMENTISTA
                                        </h3>
                                    </div>
                                    <div class="panel-body body-fase-1-seg" style="display: none;">
                                        <div class="col-lg-12">
                                            <table class="table">
                                                <tr>
                                                    <td>- CONFIRMA LA EXISTENCIA DE EQUIPOS DISPONIBLES Y OPERATIVOS</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- CONFIRMAR LA EXISTENCIA DE MATERIALES E INSUMOS</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- CONFIRMA LA ADECUADA ESTERILIZACIÒN DE MATERIALES</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- PARA LOS CASOS DE VÌA AÈREA DIFICIL SE CONFIRMA LA EXISTENCIA DE EQUIPOS Y ASISTENCIA DISPONIBLE</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default contenedor-datos-generales">
                                    <div class="panel-heading">
                                        <h3 class="panel-title size-titulo" style="display: inline;">
                                            ESTUDIO DE IMÀGENES
                                        </h3>
                                    </div>
                                    <div class="panel-body body-fase-1-seg" style="display: none;">
                                        <div class="col-lg-12">
                                            <table class="table">
                                                <tr>
                                                    <td>- SE VERIFICA QUE IMÀGENES READIOGRÀFICAS ESTÀN EXHIBIDAS</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                                <tr>
                                                    <td>- Nª HC / AUTOGENERADO</td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                    <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel panel-info contenedor-datos-generales">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo" style="display: inline;">
                                FASE 2 : ANTES DE LA INCISIÒN
                            </h3>
                            <button tabindex="90" type="button" id="addFase2Seguridad" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                            </button>
                        </div>
                        <div class="panel-body body-fase-2-seg" style="display: none;">
                            <div class="panel panel-default contenedor-datos-generales">
                                <div class="panel-heading">
                                    <h3 class="panel-title size-titulo" style="display: inline;">
                                        IDENTIFICACIÒN DEL PACIENTE, PROCEDIMINETO Y ZONA OPERATORIA POR EL MÈDICO CIRUJANO
                                    </h3>
                                </div>
                                <div class="panel-body body-fase-2-seg">
                                    <div class="col-lg-12">
                                        <table class="table">
                                            <tr>
                                                <td>- EL MÈDICO CIRUJANO CONFIRMA LA IDENTIDAD DEL PACIENTE</td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                            </tr>
                                            <tr>
                                                <td>- EL MÈDICO CIRUJANO CONFIRMA EL PROCEDIMINETO A REALIZARSE</td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                            </tr>
                                            <tr>
                                                <td>- EL MÈDICO CIRUJANO CONFIRMA LA ZONA OPERATORIA</td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                            </tr>
                                            <tr>
                                                <td>- EL MÈDICO CIRUJANO NIEGA O CONFIRMA LA EXISTENCIA DE FACTORES DE RIESGO</td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info contenedor-datos-generales">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo" style="display: inline;">
                                FASE 3 : ANTES DE LA SALIDA DE SALA DE OPERACIONES
                            </h3>
                            <button tabindex="90" type="button" id="addFase3Seguridad" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                            </button>
                        </div>
                        <div class="panel-body body-fase-3-seg" style="display: none;">
                            <div class="panel panel-default contenedor-datos-generales">
                                <div class="panel-heading">
                                    <h3 class="panel-title size-titulo" style="display: inline;">
                                        PREPARACIÒN PARA SALIDA DEL PACIENTE
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <table class="table">
                                            <tr>
                                                <td>- ENFERMERA(O) INSTRUMENTISTA Y LA CIRCULANTE CONFIRMAN VERBALMENTE A TODO EL EQUIPO QUE EL RECUENTO DE GASAS Y MATERIAL QUIRURGICO SON CORRECTOS.</td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                            </tr>
                                            <tr>
                                                <td>- MÈDICO CIRUHANO, ANESTESIÒLOGO Y ENFERMERA INSTRUMENTISTA COORDINAN EL POST OPERATORIO INMEDIATO DEL PACIENTE.</td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                            </tr>
                                            <tr>
                                                <td>- SE VERIFICA QUE EL ESPÈCIMEN O MUESTRA ES ETIQUETADO CON NOMBRE Y Nª DE HISTORIA CLINICA, TPS DEL PACIENTE. MUESTRA PATOLÒGICA - ORDEN REGISTRARLO EN EL LIBRO A.P.</td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                            </tr>
                                            <tr>
                                                <td>- CONSTATAR EL CD.</td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> SI</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> NO</label></td>
                                                <td><label><input type="radio" name="opcion" value="opcion1"> N.A</label></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info contenedor-datos-generales">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo" style="display: inline;">
                                OBSERVACIONES
                            </h3>
                            <button tabindex="90" type="button" id="addFase4" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                            </button>
                        </div>
                        <div class="panel-body body-fase-4" style="display: none;">
                            
                        </div>
                    </div>
                    
                    <div class="row" style="padding: 1.5rem 0;">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2">
                            <input onclick="validarFormulario();" type="button" value="GUARDAR" class="form-control btn btn-success" id="btnReg" style="text-align:center!important">
                        </div>
                    </div>
                  </form>
              </div>  
