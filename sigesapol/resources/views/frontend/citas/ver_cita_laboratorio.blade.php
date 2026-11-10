<style>
  table td{
    text-align: left;
    font-size: 13px;
  }
</style>


<div id="citaMedica" class="panel panel-primary">
  <div class="panel-heading">
    <b>DATOS DE LA CITA</b>
  </div>
  <div class="panel-body">
    <!-- Content -->
    <table class="table ">
      <tr>
        <td><b>ESTABLECIMIENTO</b></td>
        <td>: {{ $cita->establecimiento }}</td>
      </tr>
      <tr>
        <td><b>FECHA Y HORA DE CITA</b></td>
        <td>: {{ \App\Helpers\Util::GetFechaTexto($cita->dia) }} – {{ $cita->hora }}</td>
      </tr>
      <tr>
        <td><b>SERVICIO</b></td>
        <td>: {{ $cita->servicio }}</td>
      </tr>
      <tr>
        <td><b>N° DE DOCUMENTO</b></td>
        <td>: {{ $cita->nro_doc_ident }}</td>
      </tr>
      <tr>
        <td><b>NOMBRES Y APELLIDOS</b></td>
        <td>: {{ $cita->asegurado_nombre . " " . $cita->asegurado_paterno . " " . $cita->asegurado_materno }}</td>
      </tr>
      <tr>
        <td><b>N° DE CELULAR</b></td>
        <td>: {{ $cita->telefono }}</td>
      </tr>
      <tr>
        <td><b>RESPONSABLE DE CITA</b></td>
        <td>: {{ $cita->user_lab_paterno }} {{ $cita->user_lab_materno }}, {{ $cita->user_lab_nombre }}</td>
      </tr>
    </div>
  </div>
</div>

