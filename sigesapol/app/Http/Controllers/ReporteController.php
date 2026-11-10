<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{

  function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function programacion_medicos() 
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $id_user = Auth::user()->id;
    //
    $model_usuario = new \App\Models\User;
    $usuario = $model_usuario->GetById($id_user);

    $existe = $usuario->flagestablecimientos;
    $flagservicios = $usuario->flagservicios;

    $usuarios = $model_usuario->GetById($id_user);
    $model_consultorio = new \App\Models\Consultorio;

    $consultorio = null;
    if ($flagservicios == 1) {
      $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($usuario->id_establecimiento);
    } else {
      $consultorio = $model_consultorio->getConsultorioPorEstablecimiento($usuario->id_establecimiento, $usuarios->id_consultorio);
    }
    $tipo_atenciones = \App\Models\Cita::GetTipoDeAtension();
    return View('frontend.reportes.programacion_medicos', compact('tipo_atenciones', 'consultorio', 'flagservicios'));
  }	

  public function exportar_programacion_medicos(Request $request) 
  { 
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_usuario = (new \App\Models\User())->GetById(Auth::user()->id);

    $tipo_atencion = \App\Helpers\Util::DBClear($request->tipoatencion);
    $servicio = intval($request->servicio);
    $aFecha = isset($request->fecha) ? explode('/',$request->fecha) : null;
    if (empty($aFecha) || count($aFecha) != 2 || empty($tipo_atencion)) {
      $aFecha = explode('/', date('m/Y'));
    }

    $aFecha[0] = intval($aFecha[0]); $aFecha[1] = intval($aFecha[1]); 
    $aHorario = [];

    $reporte = new \App\Models\Reporte();
    $result_horario = $reporte->getProgramacionPersonalSaludExport($model_usuario->id_establecimiento, $tipo_atencion, $servicio, $aFecha[1], $aFecha[0]);

    $nombre_mes = strtoupper(\App\Helpers\Util::GetNombreMes($aFecha[0]));

    foreach($result_horario as $item) {
      $aHorario[$item->id_consultorio]['servicio'] = $item->consultorio;
      $aHorario[$item->id_consultorio]['medicos'][$item->id_medico]['nombre'] = $item->nombre . ' ' . $item->paterno . ' ' . $item->materno;
      $aHorario[$item->id_consultorio]['medicos'][$item->id_medico]['dni'] = $item->dni;
      $aux = '';
      if (!empty($aHorario[$item->id_consultorio]['medicos'][$item->id_medico]['horario'][intval($item->dia)])) {
        $aux = $aHorario[$item->id_consultorio]['medicos'][$item->id_medico]['horario'][intval($item->dia)];
      }
      if (strrpos($aux, $item->turno) === false) { 
        $aHorario[$item->id_consultorio]['medicos'][$item->id_medico]['horario'][intval($item->dia)] = $item->turno . $aux;
      }
    }

    $aData = [
      'establecimiento' => (new \App\Models\Establecimiento())->GetNombre($model_usuario->id_establecimiento),
      'descripcion' => 'ROL DE SERVICIO ' . $nombre_mes . ' '. $aFecha[1],
      'year' => $aFecha[1],
      'month' => $aFecha[0],
      'servicios' => $aHorario
    ];

    Excel::create('reporte-programacion-personal-'.date('Ymd'), function($excel) use($aData) {
      $excel->sheet('Sheetname', function($sheet) use($aData) {
        // Configuración de columnas
        $aColW = [3.20];
        $sheet->setWidth([
          'A'=> 3.40, 'B' => 10.40, 'C' => 30.40,
          'D'=> $aColW[0], 'E'=> $aColW[0], 'F'=> $aColW[0], 'G'=> $aColW[0], 'H'=> $aColW[0],
          'I'=> $aColW[0], 'J'=> $aColW[0], 'K'=> $aColW[0], 'L'=> $aColW[0], 'M'=> $aColW[0],
          'N'=> $aColW[0], 'O'=> $aColW[0], 'P'=> $aColW[0], 'Q'=> $aColW[0], 'R'=> $aColW[0],
          'S'=> $aColW[0], 'T'=> $aColW[0], 'U'=> $aColW[0], 'V'=> $aColW[0], 'W'=> $aColW[0],
          'X'=> $aColW[0], 'Y'=> $aColW[0], 'Z'=> $aColW[0], 'AA'=> $aColW[0], 'AB'=> $aColW[0],
          'AC'=> $aColW[0], 'AD'=> $aColW[0], 'AE'=> $aColW[0], 'AF'=> $aColW[0], 'AG'=> $aColW[0],
          'AH'=> $aColW[0]
        ]);

        // Configuración del encabezado de servicios
        $aDiaNombre = [1 => 'LUNES', 2 => 'MARTES', 3 => 'MIERCOLES', 4 => 'JUEVES', 5 => 'VIERNES', 6 => 'SABADO', 7 => 'DOMINGO'];
        $aConfig = explode(':', date('N:t', mktime(0, 0, 0, $aData['month'], 1, $aData['year'])));
        $aDia['key'] = [];
        $iDiaNombre = $aConfig[0];
        for($i=1; $i<=31; $i++) {
          if ($i <= $aConfig[1]) {
            $aDia['dia_numero'][] = $i;
            if($iDiaNombre > 7) {
              $iDiaNombre = 1;
            }
            $aDia['dia_nombre'][] = $aDiaNombre[$iDiaNombre];
            $iDiaNombre++;
          }
        }

        // Configuración del encabezado de hoja
        $sheet
          ->row(1, [$aData['establecimiento']])
          ->mergeCells('A1:AH1')
          ->cells('A1:A1', function($cells) {
              $cells->setFont(['size' => '14', 'bold' => true])->setAlignment('center');
          });

        $sheet
          ->row(2, [$aData['descripcion']])
          ->mergeCells('A2:AH2')
          ->cells('A2:A2', function($cells) {
            $cells->setFont(['size' => '11', 'bold' => true])->setAlignment('center');
          });

        // 

        $iFila = 4;

        if (empty($aData['servicios'])) {
          $sheet
            ->row($iFila, ['No se encontraron registros'])
            ->mergeCells('A4:AH4')
            ->cells('A4:A4', function($cells) {
                $cells->setFont(['size' => '14', 'bold' => true])->setAlignment('center');
            });
            return;
        }
        
        foreach($aData['servicios'] as $itemServicio) {
          $aRows = [];
          $iMedico = 1;
          foreach($itemServicio['medicos'] as $kKey => $kItem) {
            $aTmpDia = array_fill(0, 31, '');
            foreach($kItem['horario'] as $k2Key => $k2Item) {
              $aTmpDia[$k2Key - 1] = $k2Item;
            }
            $aRows[] = array_merge([$iMedico, $kItem['dni'], $kItem['nombre']], $aTmpDia);  
            $iMedico++;
          }

          $nRows = count($aRows);
          // 4
          $sheet
            ->row($iFila, [$itemServicio['servicio']])
            ->cells('A'.$iFila.':A'.$iFila, function($cells) {
              $cells->setFont(['bold' => true]);
            });

          $iFila++; // 5
          $sheet
            ->row($iFila, array_merge(['N', 'DNI', 'APELLIDOS Y NOMBRES'], $aDia['dia_numero']))
            ->setBorder('A'.$iFila.':AH' .($iFila + $nRows + 1), 'thin')
            ->cells('A'.$iFila.':C'.($iFila+1), function($cells) {
              $cells->setFont(['family' => 'Calibri', 'size' => '10', 'bold' => true])->setAlignment('center')->setValignment('center');
            })
            ->cells('D'.$iFila.':AH'.($iFila+1), function($cells) {
              $cells->setFont(['family' => 'Calibri', 'size' => '8', 'bold' => true])->setAlignment('center');
            })
            ->mergeCells('A'.$iFila.':A'.($iFila+1))
            ->mergeCells('B'.$iFila.':B'.($iFila+1))
            ->mergeCells('C'.$iFila.':C'.($iFila+1));

          $iFila++; // 6
          $sheet
            ->row($iFila, array_merge(['', '', ''], $aDia['dia_nombre']))
            ->cells('D'.$iFila.':AH'.$iFila, function($cells) {
              $cells->setTextRotation(90)->setValignment('center');
            })
            ->cells('D'.$iFila.':AH'.($iFila+$nRows), function($cells) {
              $cells->setFont(['family' => 'Calibri', 'size' => '8', 'bold' => false])->setAlignment('center');
            });

          $iFila++; // 9
          $sheet
            ->cells('A'.$iFila.':C'.($iFila+$nRows), function($cells) {
              $cells->setFont(['family' => 'Calibri', 'size' => '10', 'bold' => false]);
            })
            ->cells('A'.$iFila.':B'.$nRows, function($cells) {
              $cells->setAlignment('center');
            });

          foreach($aRows as $value) {
            $sheet->row($iFila, $value); $iFila++;
          }
          $iFila++;
        }
      });
    })->export('xlsx');
  }
}
