

<style>
  .modal-title-info{
    font-size: 19px;
  }
  .separador-contenedor{
    border:1px solid; 
    padding: 12px 9px;
    margin-top:7px; 
  }
</style>

<div class="">
  <!-- Sección de datos del titular -->
  <div class="form-section separador-contenedor" >
      <div class="section-title "><strong class="modal-title-info">DATOS DEL TITULAR CUANDO FUE INTERNADO EL PACIENTE</strong> </div>
      <div class="row">    
        <div class="col-md-4">
            <label>NRO. DE CIP.</label>
            <div>{{ isset($titular->cip) ? $titular->cip : 'No disponible' }}</div>
        </div>
        <div class="col-md-4">
            <label>GRADO</label>
            <div>{{ isset($titular->grado) ? $titular->grado : 'No disponible' }}</div>
        </div>
        <div class="col-md-4">
            <label>SITUACION</label>
            <div>{{ isset($titular->situacion) ? $titular->situacion : 'No disponible' }}</div>
        </div>      
      </div>
      <div class="row" style="margin-top: 10px;">
        <div class="col-md-4">
          <label>Nro. Doc</label>
          <div>{{$info->dni_titular}}</div>
        </div>
          <div class="col-md-6">
              <label>APELLIDOS Y NOMBRES</label>
              <div>{{$info->paterno_titular}} {{$info->materno_titular}}, {{$info->nombre_titular}}</div>
          </div>
      </div>
  </div>

  <!-- Sección de datos del ingreso del hospitalizado -->
  <div class="form-section separador-contenedor" >
      <div class="section-title" style=""><strong class="modal-title-info">DATOS DE INGRESO DEL HOSPITALIZADO</strong> </div>
      <div class="row">
        <div class="col-md-4">
          <label>Nro. Doc</label>
          <div>{{$info->nro_doc_ident}}</div>
        </div>
        <div class="col-md-4">
          <label>PACIENTE</label>
          <div>{{$info->apellidos_paciente}}{{$info->nombre_paciente}}</div>
        </div>
        <div class="col-md-4">
          <label>NRO.HISTORIA</label>
          <div>{{$info->nro_historia}}</div>
        </div>
      </div>
      <div class="row">
          <div class="col-md-4">
              <label>Edad</label>
              <div>Anios: {{$info->edad_anios}} Meses: {{$info->edad_meses}} Días: {{$info->edad_dias}}</div>
          </div>
          <div class="col-md-4">
            <label>SEXO</label>
            <div>{{$info->sexo}}</div>
        </div>
          <div class="col-md-4">
              <label>PARENTESCO</label>
              <div>{{$info->tipo_beneficiario}}</div>
          </div>
         
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-4">
              <label>FECHA INGRESO</label>
              <div>
                  {{ !empty($info->fecha_ingreso_emergencia) ? \Carbon\Carbon::parse($info->fecha_ingreso_emergencia)->format('d/m/Y') : 'No disponible' }}
              </div>
          </div>
          
          <div class="col-md-4">
              <label>HORA INGRESO</label>
              <div>
                  {{ !empty($info->hora_ingreso) ? \Carbon\Carbon::parse($info->hora_ingreso)->format('h:i A') : 'No disponible' }}
              </div>
          </div>
          <div class="col-md-4">
            <label>TIPO INGRESO</label>
            <div>{{$info->lugar_pide}}</div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
        <div class="col-md-4">
            <label>A CARGO</label>
            <div>{{$info->descripcion_upss}}</div>
        </div>
        <div class="col-md-4">
            <label>CAMA</label>
            <div>{{$info->nombre_cama}}</div>
        </div>         
    </div>
      <div class="row" style="margin-top: 10px;">
        
          <div class="col-md-8">
              <label>DIAGNOSTICO INICIAL</label>
              <div>{{$info->diagnostico_inicial}}</div>
          </div>
      </div>


  </div>


  @if($tipo_atencion == "HOSPITALIZACION" && $info->id_tipo_acompaniante != null)
  <div class="form-section separador-contenedor" style="margin-top: 15px;">
      <div class="section-title">
          <strong class="modal-title-info">Acompañante: {{$info->tipo_parentesco}}</strong>
      </div>

      <div class="row">
          <div class="col-md-4">
              <label>Documento</label>
              <div>{{$info->tipo_doc_acompaniante}}</div>
          </div>
          <div class="col-md-4">
              <label>Nombre Acompañante</label>
              <div>{{$info->nombre_acompaniante}}</div>
          </div>
          <div class="col-md-4">
              <label>Teléfono Acompañante</label>
              <div>{{$info->telefono_acompaniante}}</div>
          </div>
      </div>
  </div>
  @endif

  @if($tipo_atencion == "EMERGENCIA" && $info->tipo_parentesco != null)
  <div class="form-section separador-contenedor" style="margin-top: 15px;">
      <div class="section-title">
          <strong class="modal-title-info">Acompañante: {{$info->tipo_parentesco}}</strong>
      </div>

      <div class="row">
          <div class="col-md-4">
              <label>Documento</label>
              <div>{{$info->dni_conducido}}</div>
          </div>
          <div class="col-md-4">
              <label>Nombre Acompañante</label>
              <div>{{$info->nombre_conducido}}</div>
          </div>
          <div class="col-md-4">
              <label>Teléfono Acompañante</label>
              <div>{{$info->telf_conducido}}</div>
          </div>
      </div>
  </div>
  @endif

</div>