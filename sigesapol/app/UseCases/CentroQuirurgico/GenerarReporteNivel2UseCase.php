<?php 

namespace App\UseCases\CentroQuirurgico;

use App\Models\Centro_quirurgico;
use App\Models\Establecimiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class GenerarReporteNivel2UseCase
{
  /**
   *
   * @param request $request
   * @return array
   * @throws Exception
   */
  public function execute($request)
  {
    $model_centro_quirurgico = new Centro_quirurgico();

    $offset = ($request->pagina - 1) * $request->cantidad_pagina;
    $limit = $request->cantidad_pagina;

    $id_establecimiento = Auth::user()->id_establecimiento;

    $fecha_solicitud_inicio = null;
    if (isset($request->fecha_solicitud_inicio) && !empty($request->fecha_solicitud_inicio)) {
      $fecha_solicitud_inicio = Carbon::createFromFormat('d-m-Y', $request->fecha_solicitud_inicio)->format('Y-m-d');
    }

    $fecha_solicitud_fin = null;
    if (isset($request->fecha_solicitud_fin) && !empty($request->fecha_solicitud_fin)) {
      $fecha_solicitud_fin = Carbon::createFromFormat('d-m-Y', $request->fecha_solicitud_fin)->format('Y-m-d');
    }

    $p = [];
    $p[0] = $fecha_solicitud_inicio;
    $p[1] = $fecha_solicitud_fin;
    $p[2] = isset($request->nro_doc_ident) && !empty($request->nro_doc_ident) ? $request->nro_doc_ident : null;
    $p[3] = $id_establecimiento;
    $p[4] = isset($request->id_tipo_atencion) && !empty($request->id_tipo_atencion) ? $request->id_tipo_atencion : null;
    $p[5] = isset($request->id_servicio) && !empty($request->id_servicio) ? $request->id_servicio : null;
    $p[6] = isset($request->id_estado) && !empty($request->id_estado) ? $request->id_estado : null;
    $p[7] = isset($request->nombres_apellido) && !empty($request->nombres_apellido) ? $request->nombres_apellido : null;
    $p[8] = $limit;
    $p[9] = $offset;

    $result = $model_centro_quirurgico->obtenerIntervencionesNivel2($p);
    $result = is_array($result) && count($result) > 0 ? $result : [];

    $model_establecimiento = new Establecimiento();
    $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
    $nombre_establecimiento = $establecimiento[0]->nombre;

    $descriptions = [
      [$nombre_establecimiento],
      ["REPORTE DE ÓRDENES DE LABORATORIO"],
      ["Fecha Solicitud Desde: " . $request->fecha_atencion_inicio],
      ["Fecha Solicitud Hasta: " . $request->fecha_atencion_fin],
      ["Total: " . count($result) . " órdenes de laboratorio"],
      ["Usuario: " . Auth::user()->name],
      [""]
    ];

    $headers = [
      'COD.',
      'TIPO ATENCIÓN',
      'FECHA ADMISIÓN',
      'FECHA DE INTERVENCIÓN',
      'NRO. DOC. IDENTIDAD',
      'PACIENTE NOMBRES',
      'PACIENTE EDAD',
      'PACIENTE SEXO',
      'SERVICIO',
      'ESTADO'
    ];

    $data = array();
    $estadosCqx = array(
      1 => array('label' => 'PENDIENTE'),
      3 => array('label' => 'ATENDIDO'),
      4 => array('label' => 'ALTA'),
      5 => array('label' => 'SUSPENDIDO',)
    );
    if ($result && count($result) > 0) {
      foreach ($result as $key => $orden) {
        $beneficiario  = !empty($orden->asegurado_nombre)  ? $orden->asegurado_nombre  : '';
        $beneficiario .= !empty($orden->asegurado_paterno) ? ' ' . $orden->asegurado_paterno : '';
        $beneficiario .= !empty($orden->asegurado_materno) ? ' ' . $orden->asegurado_materno : '';
        
        $flagIntervencion = isset($orden->flag_intervencion) ? intval($orden->flag_intervencion) : 1;
        $estado = isset($estadosCqx[$flagIntervencion]) ? $estadosCqx[$flagIntervencion] : null;

        $data[] = [
            !empty($orden->cqx_intervencio_id) ? $orden->cqx_intervencio_id : "",
            !empty($orden->tipo_atencion) ? $orden->tipo_atencion : "",
            !empty($orden->fecha_admision) ? $orden->fecha_admision : "",
            !empty($orden->fecha_intervencion) ? Carbon::parse($orden->fecha_intervencion, 'Y-m-d')->format('d-m-Y') : "",
            !empty($orden->asegurado_nro_doc_ident) ? $orden->asegurado_nro_doc_ident : "",
            $beneficiario,
            !empty($orden->edad) ? $orden->edad : "",
            !empty($orden->asegurado_sexo) ? $orden->asegurado_sexo : "",
            !empty($orden->servicio) ? $orden->servicio : "",
            $estado['label'],
        ];
      }
    }

    $exportData = array_merge($descriptions, [$headers], $data);

    Excel::create('REPORTE INTERVENCIONES QUIRURGICAS', function ($excel) use ($exportData, $descriptions) {
      $excel->sheet('GENERAL', function ($sheet) use ($exportData, $descriptions) {
        $sheet->rows($exportData);
        $headerRow = count($descriptions) + 1;
        $sheet->row($headerRow, function ($row) {
            $row->setBackground('#d9edf7');
            $row->setFontWeight('bold');
        });
        $sheet->setAutoSize(true);
      });
    })->download('xlsx');
  }
}