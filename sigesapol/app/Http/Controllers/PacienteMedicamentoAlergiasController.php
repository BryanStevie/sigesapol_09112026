<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Asegurados_alergias;
use DB;
use Auth;

class PacienteMedicamentoAlergiasController extends Controller
{
	public function registrar(Request $request)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$valor_response = 0;
		try {
			DB::beginTransaction();

			if (!empty($request->id_servicio_Alergia)) {
				foreach ($request->id_servicio_Alergia as $key => $value) {
					$objecto = new Asegurados_alergias();
					$objecto->id_medicamento = $value;
					$objecto->id_tipo_atencion = $request->detectado_en;
					$objecto->id_asegurado = $request->id_asegurado_alergia;
					$objecto->id_usuario_creacion = Auth::user()->id;
					$objecto->causa = $request->motivo_Alergia[$key];
					$objecto->resumen = $request->resumen_Alergia[$key];
					$objecto->estado = false;
					$valor_response = $objecto->save();
				}
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}

		return json_encode($valor_response);
	}
	public function actualizar(Request $request)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$objAseguradosAlergias = new Asegurados_alergias;
		$data = $objAseguradosAlergias::where('id', $request->id_alergia)->update(['id_medicamento' => $request->editar_id_servicio_Alergia, 'causa' => $request->causa, 'resumen' => $request->resumen]);
		return json_encode($data);
	}
	public function listaAlergiasMedicasByFiltro(Request $request)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$objAseguradosAlergias = new Asegurados_alergias;
		$data = $objAseguradosAlergias->listaAlergiasMedicasByFiltro($request->tipoatenciones, $request->estado);
		return json_encode($data);
	}
	public function listaAlergiasMedicasByFiltroV2(Request $request)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$objAseguradosAlergias = new Asegurados_alergias;
		$data = $objAseguradosAlergias->listaAlergiasMedicasByFiltroV2($request->asegurado_id, $request->tipoatenciones, $request->estado);
		return json_encode($data);
	}
	public function verAlergiasMedicasById(Request $request)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$objAseguradosAlergias = new Asegurados_alergias;
		$data = $objAseguradosAlergias->verAlergiasMedicasById($request->id_alergia);
		return json_encode($data);
	}
	public function validarAlergiasMedicas(Request $request)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$objAseguradosAlergias = new Asegurados_alergias;
		$validar = true;
		if ($request->validar == 'true') {
			$validar = false;
		}
		$data = $objAseguradosAlergias::where('id', $request->id)->update(['estado' => $validar]);
		return $data;
	}

	public function registrarAlergia(Request $request)
	{
		$alergia = new Asegurados_alergias();

		$alergia->id_asegurado        = $request->id_asegurado;
		$alergia->id_usuario_creacion = Auth::user()->id;
		$alergia->causa               = $request->motivo_Alergia;
		$alergia->resumen             = $request->resumen_Alergia;
		$alergia->id_medicamento      = $request->id_servicio_Alergia;
		$alergia->id_tipo_atencion    = $request->id_tipo_atencion;
		$alergia->estado              = true;

		$alergia->save();

		return $alergia;
	}

	public function registrarAlergiaV2(Request $request)
	{
		$medicamentos = $request->id_servicio_Alergia;
		$motivos      = $request->motivo_Alergia;
		$resumenes    = $request->resumen_Alergia;

		foreach ($medicamentos as $index => $idMedicamento) {

			$alergia = new Asegurados_alergias();

			$alergia->id_asegurado        = $request->id_asegurado;
			$alergia->id_usuario_creacion = Auth::user()->id;
			$alergia->causa               = $motivos[$index];
			$alergia->resumen             = $resumenes[$index];
			$alergia->id_medicamento      = $idMedicamento;
			$alergia->id_tipo_atencion    = $request->id_tipo_atencion;
			$alergia->estado              = true;

			/* dd([
				'id_asegurado'        => $request->id_asegurado,
				'id_usuario_creacion' => Auth::user()->id,
				'causa'               => $motivos[$index],
				'resumen'             => $resumenes[$index],
				'id_medicamento'      => $idMedicamento,
				'id_tipo_atencion'    => $request->id_tipo_atencion,
				'estado'              => true
			]); */

			$alergia->save();
		}

		return response()->json([
			'success' => true,
			'message' => 'Alergias registradas correctamente'
		]);
	}
}
