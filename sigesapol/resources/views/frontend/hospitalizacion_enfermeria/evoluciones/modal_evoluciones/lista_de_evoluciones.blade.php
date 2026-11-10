<!-- Modal -->
@include('frontend.emergencia_enfermeria.evoluciones.function_evoluciones.puntos_grafico.control_de_puntos_evoluciones')
<script src='../../../funciones/function.js'></script> 
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script> --}}

<style>
  .is-invalid {
    border: 2px solid red; /* Ajusta el grosor y color del borde según tus necesidades */
  }

  /* Estilos para el div con scroll */
  #scrollableDiv {
      width: 600px; /* Ancho del div */
      height: 600px; /* Alto del div */
      overflow-y: scroll; /* Habilita el scroll vertical */
      overflow-x: hidden; /* Oculta el scroll horizontal si existe */
      border: 1px solid #ccc; /* Borde del div */
      padding: 10px; /* Espaciado interno del div */
  }
</style>

<style>
  #example thead th {
    position: sticky;
    top: 0;
    background-color: white;
    z-index: 1;
  }
  .disabled {
    pointer-events: none;
    cursor: default;
    color: gray;
  }
</style>
<style>
  .input_general {
      /* Estilo de un campo input */
      border: 1px solid #ccc;
      padding: 5px;
      width: 100px;
      height: 30px;
      box-sizing: border-box;
      border-radius: 2px;
      outline: none;
      font-size: 10px;
  }
  .input_documento {
      /* Estilo de un campo input */
      border: 1px solid #ccc;
      padding: 5px;
      width: 200px;
      height: 30px;
      box-sizing: border-box;
      border-radius: 2px;
      outline: none;
      font-size: 10px;
  }
  .input_paciente {
      /* Estilo de un campo input */
      border: 1px solid #ccc;
      padding: 5px;
      width: 250px;
      height: 30px;
      box-sizing: border-box;
      border-radius: 2px;
      outline: none;
      font-size: 10px;
  }
  .input_hc {
      /* Estilo de un campo input */
      border: 1px solid #ccc;
      padding: 5px;
      width: 70px;
      height: 30px;
      box-sizing: border-box;
      border-radius: 2px;
      outline: none;
      font-size: 10px;
  }
  .input_m {
      /* Estilo de un campo input */
      border: 1px solid #ccc;
      padding: 5px;
      width: 130px;
      height: 30px;
      box-sizing: border-box;
      border-radius: 2px;
      outline: none;
      font-size: 10px;
  }
  .input_p {
      /* Estilo de un campo input */
      border: 1px solid #ccc;
      padding: 5px;
      width: 40px;
      height: 30px;
      box-sizing: border-box;
      border-radius: 2px;
      outline: none;
      font-size: 10px;
  }
  .input_p_2 {
      /* Estilo de un campo input */
      border: 1px solid #ccc;
      padding: 5px;
      width: 90px;
      height: 30px;
      box-sizing: border-box;
      border-radius: 2px;
      outline: none;
      font-size: 10px;
  }
  .input_300 {
      /* Estilo de un campo input */
      border: 1px solid #ccc;
      padding: 5px;
      width: 400px;
      height: 30px;
      box-sizing: border-box;
      border-radius: 2px;
      outline: none;
      font-size: 10px;
  }
</style>
<div class="modal fade" id="modalListarEvoluciones" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false"
  data-backdrop="static">
  <div class="modal-dialog modal-lg" style="width: 1000px;" role="document">
    <div class="modal-content">

      <div class="modal-header" style="font-size: 20px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            >&times;</span></button>
        <b id="tituloFormularioListarEvoluciones"></b>
      </div>

      <div class="modal-body" style="font-size: 11px !important">
        <form id="FormularioListarEvoluciones" name="FormularioListarEvoluciones" enctype="multipart/form-data"
          method="POST">
          <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id="id_asegurado" name="id_asegurado">
          <input type="hidden" id="servicio">
          <input type="hidden" id="area">
          <input type="hidden" id="nombre_cama">
          <input type="hidden" id="nombre_cuarto">
          <input type="hidden" id="id_enfermeria_ver">
          <input type="hidden" id="id_atencion_evolucion_listado" name="id_atencion_evolucion_listado">
          <input type="hidden" id="id_prestacion_listado" name="id_prestacion_listado">
          <input type="hidden" id="id_tipo_atenciones_listado" name="id_tipo_atenciones_listado">
          <input type="hidden" id="id_enfermeria" name="id_enfermeria">
          <input type="hidden" id="id_ext_list" name="id_ext_list">
          <input type="hidden" id="nombre_tipo_atencion" name="nombre_tipo_atencion">
          <input type="hidden" id="prioridad" name="prioridad">
          
          <div class="nav-tabs-custom">
            <div class="tab-content">
              <div class="tab-pane active">
                <fieldset>
                  <!-- Cabecera de pacientes -->
                  <div class="form-group">
                    <div class="form-group">
                      <div class="col-md-6">
                        <label for="tipo">DNI - PACIENTE: </label> <br>
                        <span style="font-size: 14px;"> 
                          <span id="num_documento_evolucion" ></span> -
                          <span id="nom_paciente_evolucion"></span>
                        </span>
                      </div>
                      <div class="col-md-1">
                        <label for="tipo">G.S.: </label> <br>
                        <label style="font-size: 14px;"><span id="tipo_sangre_evolucion" ></span></label>
                      </div>
                      <div class="col-md-1">
                        <label for="tipo">ALERGIA: </label> <br>
                        <a href="javascript:void(0)"  data-toggle="tooltip" style="cursor: pointer;"
                          title="Alergia" class="" id="">
                          <label id="alergia_evol" style="cursor: pointer;"></label>
                        </a>
                      </div>
                      <div class="col-md-1">
                        <label for="tipo">EDAD: </label> <br>
                        <label style="font-size: 14px;" id="edad_evolucion"></label>
                      </div>
                      <div class="col-md-1">
                        <label for="tipo">SEXO: </label> <br>
                        <label style="font-size: 14px;" id="sexo_evolucion"></label>
                      </div>
                      <div class="col-md-2">
                        <label for="tipo">HC: </label> <br>
                        <label style="font-size: 14px;" id="hc_evolucion"></label>
                      </div>
                    </div>
                  </div>
                  <!-- Cabecera de pacientes -->
                  
                  <div class="col-md-12">
                    <hr>
                  </div>
                  <div class="form-group">
                    <div class="col-md-2">
                      <label for="tipo">FECHA INICIAL</label>
                      <input type="text" class="form-control"
                        style="height: 28px !important;font-size: 12px !important"
                        name="fechaIni" id="fechaIni" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                    </div>
                    <div class="col-md-2">
                      <label for="tipo">FECHA FINAL</label>
                      <input type="text" class="form-control"
                        style="height: 28px !important;font-size: 12px !important"
                        name="fechaFin" id="fechaFin" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                      {{-- <input type="text" class="form-control datepicker"
                        style="height: 28px !important;font-size: 12px !important"
                        name="fecha_evolucion_bus_final_listado" id="fecha_evolucion_bus_final_listado"
                        autocomplete="off" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}"> --}}
                      {{-- <input type="text" class="form-control datepicker"
                        style="height: 28px !important;font-size: 12px !important"
                        name="fecha_evolucion_bus_final_listado" id="fecha_evolucion_bus_final_listado"
                        autocomplete="off" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}"> --}}
                    </div>
                    <div class="col-md-3">
                      <label for="tipo">TIPO</label>
                      <select class="form-control input-sm w-full" style="width: 100%;" name="tipo_atenciones" id="tipo_atenciones_listado">
                        <option value="0">---Seleccionar---</option>
                        <option value="3" selected>HOSPITALIZACION</option>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label for="tipo">TURNO</label>
                      <select class="form-control" style="width: 100%" id="id_turno" name="id_turno">
                        <option value="">TODOS</option>
                        @if ($id_establecimiento == 76) 
                        <option value="5">GUARDIA DIURNA</option>
                        <option value="6">GUARDIA NOCTURNA</option>
                        @else
                        <option value="1">MADRUGADA</option>
                        <option value="2">MAÑANA</option>
                        <option value="3">TARDE</option>
                        <option value="4">NOCHE</option>
                        @endif
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label>PROCESO</label><br>
                      <a href="javascript:void(0)"  data-toggle="tooltip" title="Nueva Evolución"
                        class="btn btn-success btn-sm crearEvoluciones" id="crearEvoluciones"> NUEVA EVOLUCIÓN
                      </a>
                    </div>
                  </div>
                </fieldset>
                <fieldset><br>
                  <div class="col-md-12">
                    <div class="table-responsive" style=" height: 350px; ">
                      <table class="table table-bordered dt-responsive" id="example"
                        style="overflow: auto; width:100%;text-align: left;">
                        <thead>
                          <tr>
                          <th style="width: 130px; text-align: center;">FECHA / HORA EVOLUCIÓN</th>
                            <th style="width: 130px; text-align: center;">DATOS DE LA ATENCIÓN</th>
                            <th style="width: 130px; text-align: center;">UBICACIÓN</th>
                            <th style="width: 130px; text-align: center;">PROFESIONAL DE SALUD</th>
                            <th style="text-align: center;">OBSERVACIONES</th>
                            <th style="width: 160px; text-align: center;">VER / EDITAR</th>
                          </tr>
                        </thead>
                        <tbody id="listaDatosEvoluciones" class="listaDatosEvoluciones">
                        </tbody>
                      </table>
                    </div>
                  </div>
                </fieldset>
              </div>
            </div><br>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>