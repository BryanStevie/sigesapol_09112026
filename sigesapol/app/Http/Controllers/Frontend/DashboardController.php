<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmacia;
use Carbon\Carbon;
use Auth;
use DB;
/**
 * Class DashboardController
 * @package App\Http\Controllers\Frontend
 */
class DashboardController extends Controller {

	/**
	 * @return mixed
	 */
	public function index()
	{
		if ((Auth::user()->id_establecimiento) === 76):
			$id_user = Auth::user()->id;
			$model_farma = new Farmacia;
			$farma = $model_farma->GetFarmaByUser($id_user);
			$id_farmacia = $farma->id;

			$id_medico = null;
			$dias_restantes = null;

			if (Auth::user()->dni) {
				$id_medico = DB::table('medicos')
					->where('dni', Auth::user()->dni)->where('estado', 1)
					->first();

				if ($id_medico && $id_medico->actualiza_datos_alerta == 0) {

					$fechaCreacion = Carbon::parse($id_medico->created_at)->startOfDay();
					$hoy           = Carbon::now()->startOfDay();
					$diasPasados   = $fechaCreacion->diffInDays($hoy);

					if ($diasPasados == 0) {
						// Nuevo usuario
						return redirect('/editar_medico_saenz/' . $id_medico->id . '/' . $id_farmacia . '?actualizar=1')
							->withFlashDanger('⚠️ Debe actualizar sus datos antes de continuar.');
					} else {
						// Usuario antiguo
						$fechaInicioAlerta = $id_medico->fecha_inicio_alerta
							? Carbon::parse($id_medico->fecha_inicio_alerta)->startOfDay()
							: $hoy;

						if (!$id_medico->fecha_inicio_alerta) {
							DB::table('medicos')
								->where('id', $id_medico->id)
								->update(['fecha_inicio_alerta' => $hoy]);
						}

						$diasDesdeAlerta = $fechaInicioAlerta->diffInDays($hoy);
						$dias_restantes  = max(0, 7 - $diasDesdeAlerta);

						if ($dias_restantes == 0) {
							return redirect('/editar_medico_saenz/' . $id_medico->id . '/' . $id_farmacia . '?actualizar=1')
							->withFlashDanger('⚠️ Debe actualizar sus datos antes de continuar.');
						}
					}
				}
			}

			return view('frontend.user.dashboard', compact('id_farmacia', 'id_medico', 'dias_restantes'))->withUser(Auth::user());
		else:
			return view('frontend.user.dashboard')->withUser(auth()->user());
		endif;
	}
	
}