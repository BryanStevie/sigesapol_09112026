<?php
  $pp_hay_solicitudes = count($ordenes_pp['recetas']) + count($ordenes_pp['laboratorios']) + count($ordenes_pp['radiologias']) > 0;
  $pp_informe_subido = !empty($ordenes_pp['informe']) && $ordenes_pp['informe']['subido'];
  $pp_puede_subir_informe = !empty($ordenes_pp['informe']) && $ordenes_pp['informe']['puede_gestionar'] && $ordenes_pp['informe']['disponibles'] > 0;
  $pp_gastro_doc = $ordenes_pp['gastro'];
  $pp_gastro_firma = $pp_gastro_doc ? (intval($pp_gastro_doc->flag) === 1 ? 'Firmado digitalmente' : (intval($pp_gastro_doc->flag) === 2 ? 'Firmado electrónicamente' : 'Sin firma')) : '';
  $pp_gastro_partes = [];
  if ($pp_gastro_doc && intval($pp_gastro_doc->baja) === 1) $pp_gastro_partes['baja'] = 'ENDOSCOPIA BAJA';
  if ($pp_gastro_doc && intval($pp_gastro_doc->alta) === 1) $pp_gastro_partes['alta'] = 'ENDOSCOPIA ALTA';
  $pp_hay_informes = $pp_informe_subido || count($pp_gastro_partes) > 0;
?>

@if($pp_hay_solicitudes)
<h5 style="font-weight:bold; margin-top:0">SOLICITUDES</h5>
<table class="table table-bordered table-condensed" style="font-size:12px; margin-bottom:15px">
  <thead>
    <tr>
      <th width="14%">Tipo</th>
      <th width="14%">Nº</th>
      <th width="12%" class="text-center">Fecha</th>
      <th width="48%">Detalle</th>
      <th width="12%" class="text-center">PDF</th>
    </tr>
  </thead>
  <tbody>
    @foreach($ordenes_pp['recetas'] as $rv)
    <tr>
      <td>RECETA</td>
      <td>{{ $rv->nro_receta }}</td>
      <td class="text-center">{{ $rv->fecha_expedicion ? date("d-m-Y", strtotime($rv->fecha_expedicion)) : '' }}</td>
      <td>
        @foreach($rv->productos as $p)
          {{ $p->codigo }} - {{ $p->nombre }} ({{ $p->cantidad_prescrita }})<br>
        @endforeach
      </td>
      <td class="text-center">
        @if(intval($rv->flag) > 0 && $rv->ruta_receta)
          <a href="/{{ $rv->flag }}/{{ $rv->ruta_receta }}" target="_blank" class="btn {{ intval($rv->flag) === 2 ? 'btn-electronico' : 'btn-primary' }} btn-xs" title="Receta PDF"><i class="fa fa-file-pdf-o"></i></a>
        @else
          <button class="btn btn-primary btn-xs" disabled title="Receta sin firmar"><i class="fa fa-file-pdf-o"></i></button>
        @endif
      </td>
    </tr>
    @endforeach

    @foreach($ordenes_pp['laboratorios'] as $lab)
    <tr>
      <td>LABORATORIO</td>
      <td>{{-- {{ $id_prestacion }} - --}}{{ $lab->id }}</td>
      <td class="text-center">{{ $lab->created_at ? date("d-m-Y", strtotime($lab->created_at)) : '' }}</td>
      <td>
        @foreach($lab->examenes as $e)
          {{ $e->codigo }} - {{ $e->descripcion }}<br>
        @endforeach
      </td>
      <td class="text-center">
        <a href="/ver_laboratorio_imprimir/{{ $lab->id }}" target="_blank" class="btn {{ intval($lab->flag) === 2 ? 'btn-electronico' : 'btn-primary' }} btn-xs" title="Orden de laboratorio PDF"><i class="fa fa-file-pdf-o"></i></a>
      </td>
    </tr>
    @endforeach

    @foreach($ordenes_pp['radiologias'] as $rad)
    <tr>
      <td>IMAGENOLOGÍA</td>
      <td>{{-- {{ $id_prestacion }} - --}}{{ $rad->id }}</td>
      <td class="text-center">{{ $rad->created_at ? date("d-m-Y", strtotime($rad->created_at)) : '' }}</td>
      <td>
        @foreach($rad->examenes as $e)
          {{ $e->codigo }} - {{ $e->descripcion }}<br>
        @endforeach
      </td>
      <td class="text-center">
        <a href="/ver_radiologia_imprimir/{{ $rad->id }}" target="_blank" class="btn {{ intval($rad->flag) === 2 ? 'btn-electronico' : 'btn-primary' }} btn-xs" title="Orden de imagenología PDF"><i class="fa fa-file-pdf-o"></i></a>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endif

@if($pp_hay_informes || $pp_puede_subir_informe)
<h5 style="font-weight:bold; margin-top:0">INFORMES</h5>
@if($pp_hay_informes)
<table class="table table-bordered table-condensed" style="font-size:12px; margin-bottom:0">
  <thead>
    <tr>
      <th width="20%">Tipo</th>
      <th width="12%" class="text-center">Fecha</th>
      <th width="56%">Detalle</th>
      <th width="12%" class="text-center">PDF</th>
    </tr>
  </thead>
  <tbody>
    @if($pp_informe_subido)
    @foreach($ordenes_pp['informe']['archivos'] as $pp_archivo)
    <tr>
      <td>INFORME</td>
      <td class="text-center">{{ date("d-m-Y", strtotime($pp_archivo->created_at)) }}</td>
      <td>
        {{ $ordenes_pp['procedimiento_detalle'] }}
        @if($pp_archivo->nombre_archivo)<br><small class="text-muted">{{ $pp_archivo->nombre_archivo }}</small>@endif
      </td>
      <td class="text-center">
        <a href="/pp_ver_informe/{{ intval($id_solicitud) }}/{{ intval($id_procedimiento) }}/{{ $pp_archivo->id }}" target="_blank" class="btn btn-primary btn-xs" title="Ver el informe subido"><i class="fa fa-file-pdf-o"></i></a>
        @if($ordenes_pp['informe']['puede_gestionar'])
        <button type="button" class="btn btn-danger btn-xs btnEliminarInformePP" data-id-informe="{{ $pp_archivo->id }}" title="Eliminar informe"><i class="fa fa-trash"></i></button>
        @endif
      </td>
    </tr>
    @endforeach
    @endif

    @foreach($pp_gastro_partes as $parte => $titulo)
    <?php $pp_gastro_fecha = $pp_gastro_doc->{$parte . '_fecha_firma'} ?: $pp_gastro_doc->created_at; ?>
    <tr>
      <td>{{ $titulo }}</td>
      <td class="text-center">{{ $pp_gastro_fecha ? date("d-m-Y", strtotime($pp_gastro_fecha)) : '' }}</td>
      <td>{{ $ordenes_pp['procedimiento_detalle'] }}</td>
      <td class="text-center">
        <a href="/pp_informe_gastro_pdf/{{ $pp_gastro_doc->id }}/{{ $parte }}" target="_blank" class="btn btn-ga_{{ $parte }} btn-xs" title="{{ $pp_gastro_firma }}"><i class="fa fa-file-pdf-o"></i></a>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endif

@if($pp_puede_subir_informe)
<div class="form-inline" style="margin-top:12px">
  <label for="archivoInformeDocumentosPP" style="font-size:12px;margin-right:8px">Subir informe (puede agregar {{ $ordenes_pp['informe']['disponibles'] }} PDF más, máximo 10 MB cada uno)</label>
  <input type="file" id="archivoInformeDocumentosPP" class="form-control input-sm" accept="application/pdf,.pdf" multiple style="margin-right:8px">
  <button type="button" id="btnSubirInformeDocumentosPP" class="btn btn-success btn-sm" data-disponibles="{{ $ordenes_pp['informe']['disponibles'] }}"><i class="fa fa-upload"></i> Subir</button>
</div>
@endif
@endif

@if(!$pp_hay_solicitudes && !$pp_hay_informes && !$pp_puede_subir_informe)
<div class="text-center" style="padding:20px; font-size:12px">La atención no tiene documentos registrados.</div>
@endif
