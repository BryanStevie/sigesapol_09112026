<?php 

namespace App\UseCases\RecetaVale;

use App\Models\Producto_receta;
use App\Models\Receta_diagnostico;
use App\Models\Receta_vale;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ObtenerRecetaValeByIdUseCase
{
  /**
   *
   * @param number $id
   * @return array
   * @throws Exception
   */
  public function execute($id)
  {
    try {
      $model_receta_vale = new Receta_vale();
      $recetaVale = $model_receta_vale->GetRecetaByIdReceta($id);

      $model_productos_receta = new Producto_receta();
      $productos = $model_productos_receta->ShowMedicinasById_receta_vale($id);

      $model_diagnosticos = new Receta_diagnostico();
      $diagnosticos = $model_diagnosticos->DiagnosticosByIdReceta($id);

      return [
        "recetaVale" => $recetaVale,
        "productos" => $productos,
        "diagnosticos" => $diagnosticos
      ];
    } catch (\Exception $e) {
      Log::error('[ObtenerRecetaValeByIdUseCase]', [
          'id_receta_vale' => $id,
          'user_id' => Auth::user()->id,
          'mensaje' => $e->getMessage(),
          'file' => $e->getFile(),
          'line' => $e->getLine(),
      ]);
      $mensaje = !empty($e->getMessage()) ? $e->getMessage() : 'Error al obtener la intervención quirúrgica por ID.';
      throw new Exception($mensaje);
    }
  }
}