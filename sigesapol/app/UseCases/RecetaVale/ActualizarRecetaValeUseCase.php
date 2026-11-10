<?php 

namespace App\UseCases\RecetaVale;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Exceptions\SigesapolException;
use App\Models\Kardex_lote;
use App\Models\Kardexe;
use App\Models\Producto_receta;
use App\Models\Receta_diagnostico;
use App\Models\Receta_vale;
use App\Models\Producto;
use App\Models\Farmacia;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActualizarRecetaValeUseCase
{
  /**
   *
   * @param request $request
   * @return array
   * @throws Exception
   */
  public function execute(Request $request)
  {
    DB::beginTransaction();
    try {
      $typesRecipe = array('DIGITAL' => 0, 'FISICA' => 1);
      
      $recetaVale = Receta_vale::find($request->recetaValeId);
      if (!$recetaVale) {
        throw new SigesapolException("No existe la receta vale indicada.", JsonResponse::HTTP_NOT_FOUND);
      }
      if (intval($request->typeRecipeId) === $typesRecipe['FISICA']) {
        $recetaVale->update([
          'grado' => isset($request->grado) && $request->grado !== '' ? $request->grado : null,
          'nro_guia' => isset($request->nroGuia) && $request->nroGuia !== '' ? $request->nroGuia : null,
          'interconsulta' => isset($request->interconsulta) && $request->interconsulta !== '' ? $request->interconsulta : null,
          'interconsulta_dias' => isset($request->interconsulta_dias) && $request->interconsulta_dias !== '' ? $request->interconsulta_dias : null,
          'id_consultorio' => isset($request->servicios) && $request->servicios !== '' ? $request->servicios : null,
          'turno' => isset($request->turno) && $request->turno !== '' ? $request->turno : null,
          'id_medico' => isset($request->medico) && $request->medico !== '' ? $request->medico : null,
          'updated_at' => Carbon::now(),
          'updated_us' => Auth::user()->id,
          'fecha_expedicion' => isset($request->fechaExpedicion) && $request->fechaExpedicion !== '' ? $request->fechaExpedicion : null,
          'fecha_expiracion' => isset($request->fechaExpiracion) && $request->fechaExpiracion !== '' ? $request->fechaExpiracion : null,
        ]);
      }
      if (intval($request->typeRecipeId) === $typesRecipe['DIGITAL']) {
        $recetaVale->update([
          'fecha_expedicion' => isset($request->fechaExpedicion) && $request->fechaExpedicion !== '' ? $request->fechaExpedicion : null,
          'fecha_expiracion' => isset($request->fechaExpiracion) && $request->fechaExpiracion !== '' ? $request->fechaExpiracion : null,
          'updated_at' => Carbon::now(),
          'updated_us' => Auth::user()->id,
        ]);
      }

      if (intval($request->typeRecipeId) === $typesRecipe['FISICA']) {
        if (isset($request->diagnosticos) && is_array($request->diagnosticos) && count($request->diagnosticos) > 0) {
          foreach ($request->diagnosticos as $key => $diagnostico) {
            if (isset($diagnostico['idRecetaDiagnostico']) && !empty($diagnostico['idRecetaDiagnostico'])) {
              $recetaDiagnostico = Receta_diagnostico::find($diagnostico['idRecetaDiagnostico']);
              $recetaDiagnostico->update([
                'id_diagnostico' => isset($diagnostico['idDiagnostico']) && $diagnostico['idDiagnostico'] !== '' ? $diagnostico['idDiagnostico'] : null,
                'id_tipo_diagnostico' => isset($diagnostico['idTipoDiagnostico']) && $diagnostico['idTipoDiagnostico'] !== '' ? $diagnostico['idTipoDiagnostico'] : null,
                'updated_at' => Carbon::now(),
                'updated_us' => Auth::user()->id
              ]);
            } else {
              Receta_diagnostico::create([
                "id_receta_vale" => $request->recetaValeId,
                "id_diagnostico" => $diagnostico['idDiagnostico'],
                "id_tipo_diagnostico" => $diagnostico['idTipoDiagnostico'],
                "estado" => 1,
                "id_prestacion" => $diagnostico['idPrestacion'],
                "created_at" => Carbon::now(),
                "created_us" => Auth::user()->id,
                "id_evaluacion" => 0,
                "id_interconsulta" => 0,
                "id_consejeria" => 0
              ]);
            }
          }
        }
      }

      if (isset($request->medicamentos) && is_array($request->medicamentos) && count($request->medicamentos) > 0) {
        foreach ($request->medicamentos as $key => $medicamento) {
          $productoReceta = Producto_receta::find($medicamento['idProductoReceta']);

          $dataUpdated = [
            'cantidad_prescrita' => $medicamento['cantidadPrescrita'],
            'cantidad_dispensada' => $medicamento['cantidadDispensada'],
            'id_observacion' => !empty($medicamento['idObservacion']) ? $medicamento['idObservacion'] : null,
            'updated_at' => Carbon::now(),
            'updated_us' => Auth::user()->id
          ];

          if(intval($request->typeRecipeId) === $typesRecipe['FISICA']){
            $dataUpdated['tiempo_periodo'] = $medicamento['tiempoPeriodo'];
            $dataUpdated['id_via_administracion'] = $medicamento['idViaAdministracion'];
            $dataUpdated['id_diagnosticos'] = $medicamento['idDiagnosticoProducto'];
            $dataUpdated['posologia'] = $medicamento['posologia'];
          }

          $productoReceta->update($dataUpdated);

          $kardex = Kardexe::where('id_nro_movimiento', $request->recetaValeId)->where('id_tipo_movimiento', 4)->where('id_producto',$productoReceta->id_producto)->first();
          if ($kardex) {
            
            //Si cantidad es 0, actualizamos el estado de kardexes y el kardex_lotes con 0
            if($medicamento['cantidadDispensada']==0): 
              $kardex->update([
                  "estado" => 0
              ]);
              
              Kardex_lote::where('id_kardex', $kardex->id)
                            ->update([
                                "estado" => 0
                              ]);
            endif;

            $cantidad_actual = $kardex->cantidad; //30
            //Si cantidad es <> a la cantidad actual
            
            if($cantidad_actual!=$medicamento['cantidadDispensada']){

              //Si la cantidad es < a la cantidad actual, solo hay que buscar el lote y cambiar la cantidad
              if($medicamento['cantidadDispensada']<$cantidad_actual){
                //buscamos si hay mas de un lote
                $ContarkardexLote = Kardex_lote::where('id_kardex', $kardex->id)->where('estado', 1)->count();
                
                if($ContarkardexLote==1){
                  $kardexLote = Kardex_lote::where('id_kardex', $kardex->id)->where('estado', 1)->first();
                  $kardexLote->update([
                      "cantidad" => $medicamento['cantidadDispensada']
                    ]);
                }
                else //Si hay mas de un lote
                {
                  //Restamos para saber cuanto vamos a descontar a uno de los lotes
                  $nueva_cantidad= $cantidad_actual - $medicamento['cantidadDispensada'];
                  $cantidad_faltante = 0 ;
                  //Se tiene que buscar si hay stock 
                  $model_kardex = new Kardexe();
                  $listar_kardex_lotes = $model_kardex->GetKardexLotesByIdKardex($kardex->id); // Existe 2 lotes
                  
                  //Existe kardex_lotes
                  if($listar_kardex_lotes){
                      //Recorremos todos los lotes hasta completar
                      foreach ($listar_kardex_lotes as $keyLotes => $rowLotes) :
                        $kardexLote = Kardex_lote::where('id', $rowLotes->id)->first();
                        
                        if ($nueva_cantidad > 0) :
                          if ($nueva_cantidad <= $rowLotes->cantidad) :
                            if ($nueva_cantidad == $rowLotes->cantidad) :
                              $kardexLote->update(["estado" => 0]);
                            else:
                              $cantidad_cambiar = $rowLotes->cantidad - $nueva_cantidad;
                              $kardexLote->update([
                                "cantidad" => $cantidad_cambiar
                              ]);
                              $nueva_cantidad = 0;
                            endif;
                          else:
                            $kardexLote->update(["estado" => 0]);
                            $nueva_cantidad -= $rowLotes->cantidad;
                          endif;
                        endif;
                      endforeach;
                  }
                }
                //actualizo cantidad del kardexes que deberia ser la cantidad dispensada
                $kardex->update([
                    "cantidad" => $medicamento['cantidadDispensada'] 
                ]);
              }
              else
              { 
                //Si la cantidad es > a la cantidad actual, hay que buscar el lote y aumentar la cantidad o insertar otro lote 
                $kardexLote = Kardex_lote::where('id_kardex', $kardex->id)->first();
                $fechaFormateada = Carbon::parse($kardexLote->fecha_vencimiento)->format('m-Y');
              
                //Calculamos cuanto mas falta para completar el nuevo stock
                $nueva_cantidad = $medicamento['cantidadDispensada'] - $cantidad_actual;
                
                if($nueva_cantidad>0){

                  $cantidad_faltante = 0 ;
                  //Se tiene que buscar si hay stock 
                  $model_kardex = new Kardexe();
                  $existe_stop_lotes = $model_kardex->GetKardexLotesByIdProductoAndIdFarmaciaAndCantidad($productoReceta->id_producto,$kardex->id_farmacia,$medicamento['cantidadDispensada']); // Existe 2 lotes
                  
                  //Existe alguna cantidad de stock
                  if($existe_stop_lotes){
                    
                    foreach ($existe_stop_lotes as $keyLotes => $rowLotes) :

                      if ($nueva_cantidad > 0) :
                        if ($nueva_cantidad <= $rowLotes->cantidad) :

                          //Validamos si existe el mismo lote ya registrado anteriormente para solo actualizar la cantidad
                          if($kardexLote->lote == $rowLotes->lote && $kardexLote->registro_sanitario == $rowLotes->registro_sanitario && $kardexLote->fecha_vencimiento == $fechaFormateada && $kardexLote->precio_unitario == $rowLotes->precio_unitario){
                            //sumar
                            $cantidad_faltante = $cantidad_actual + $nueva_cantidad;  
                            $kardexLote->update(["cantidad" => $total]);

                          }
                          else{
                            // grabamos una fila mas
                            $kardex_lote = new \App\Models\Kardex_lote;
                            $kardex_lote->id_kardex = $kardex->id;
                            $kardex_lote->lote = $rowLotes->lote;
                            $kardex_lote->fecha_vencimiento = "01-" . $rowLotes->fecha_vencimiento;
                            $kardex_lote->registro_sanitario = $rowLotes->registro_sanitario;
                            $kardex_lote->cantidad = $nueva_cantidad;
                            $kardex_lote->precio_unitario = $rowLotes->precio_unitario;
                            $kardex_lote->fecha_registro = Carbon::now()->format('Y-m-d');
                            $kardex_lote->save();
                            $cantidad_faltante += $nueva_cantidad;
                            $nueva_cantidad = 0;

                          }
                        else : //no hay mas stock
                        
                          //Validamos si existe el mismo lote ya registrado anteriormente para solo actualizar la cantidad
                          if($kardexLote->lote == $rowLotes->lote && $kardexLote->registro_sanitario == $rowLotes->registro_sanitario && $kardexLote->fecha_vencimiento == $fechaFormateada && $kardexLote->precio_unitario == $rowLotes->precio_unitario){
                            $kardexLote->update(["cantidad" => $rowLotes->cantidad]);
                          }
                          else
                           {
                              // graba 3 nada mas 1 lote
                              $kardex_lote = new \App\Models\Kardex_lote;
                              $kardex_lote->id_kardex = $kardex->id;
                              $kardex_lote->lote = $rowLotes->lote;
                              $kardex_lote->fecha_vencimiento = "01-" . $rowLotes->fecha_vencimiento;
                              $kardex_lote->registro_sanitario = $rowLotes->registro_sanitario;
                              $kardex_lote->cantidad = $rowLotes->cantidad;
                              $kardex_lote->precio_unitario = $rowLotes->precio_unitario;
                              $kardex_lote->fecha_registro = Carbon::now()->format('Y-m-d');
                              $kardex_lote->save();
                              $nueva_cantidad -= $rowLotes->cantidad;

                           } 

                           $cantidad_faltante += $rowLotes->cantidad;

                        endif;
                      endif;
                    endforeach;
                  }
                  //actualizo cantidad entregada en kardex_lotes
                  $cantidad_total = $cantidad_actual + $cantidad_faltante;
                  $kardex->update([
                      "cantidad" => $cantidad_total, 
                      "estado" => 1
                  ]);
                }
              }
            }
          }
          else{
            if($medicamento['cantidadDispensada']>0): 
            
              //No hay producto, buscamos el producto
              $model_producto = new Producto();
              $model_farmacia = new Farmacia;
              //----------------------------------------------------------------------
              $farma = $model_farmacia->GetFarmaByIdfarma(Auth::user()->id_farmacia);
              $nombrefarmacia = $farma->nombre;
              
              $productos = $model_producto->GetCodByIdProducto($medicamento['idProducto']);
              
              $model_kardex = new Kardexe();
              $existe_stop_lotes = $model_kardex->GetKardexLotesByIdProductoAndIdFarmaciaAndCantidad($productoReceta->id_producto,$farma->id,$medicamento['cantidadDispensada']); // Existe 2 lotes

              //Calculamos cuanto mas falta para completar el nuevo stock
              $nueva_cantidad = $medicamento['cantidadDispensada'];
              
              if($existe_stop_lotes):

                //------Insertamos el Kardexes--------------------------------------
                $kardex_new = new Kardexe;
                $kardex_new->codigo_producto = $productos->codigo;
                $kardex_new->nombre_producto = $productos->nombre;
                $kardex_new->tipo_movimiento = 'RC';
                $kardex_new->numero_movimiento = "P-" . $recetaVale->nro_receta . "-" . Carbon::now()->format('Y');
                $kardex_new->origen = $nombrefarmacia;
                $kardex_new->destino = 'BENEFICIARIO';
                $kardex_new->estado = 1;
                $kardex_new->cantidad = $medicamento['cantidadDispensada'];
                $kardex_new->nombre_farmacia = $nombrefarmacia;
                $kardex_new->fecha_movimiento = Carbon::now()->format('Y-m-d');
                $kardex_new->id_tipo_movimiento = 4;
                $kardex_new->id_producto = $productos->id;
                $kardex_new->id_nro_movimiento = $recetaVale->id;
                $kardex_new->id_origen = $farma->id;
                $kardex_new->id_farmacia = $farma->id;
                $kardex_new->id_user_created = Auth::user()->id;
                $kardex_new->created_at = Carbon::now();
                $kardex_new->save();
                $idkardex = $kardex_new->id;
                //------------------------------------
                foreach ($existe_stop_lotes as $keyLotes => $rowLotes) :
                  if ($nueva_cantidad > 0) :
                    // grabamos una fila mas
                    $kardex_lote = new \App\Models\Kardex_lote;
                    $kardex_lote->id_kardex = $kardex_new->id;
                    $kardex_lote->lote = $rowLotes->lote;
                    $kardex_lote->fecha_vencimiento = "01-" . $rowLotes->fecha_vencimiento;
                    $kardex_lote->registro_sanitario = $rowLotes->registro_sanitario;
                    $kardex_lote->precio_unitario = $rowLotes->precio_unitario;
                    $kardex_lote->fecha_registro = Carbon::now()->format('Y-m-d');
                    
                    if ($nueva_cantidad <= $rowLotes->cantidad) :
                     $kardex_lote->cantidad = $nueva_cantidad;
                      $nueva_cantidad = 0;
                    else : //no hay mas stock
                      $kardex_lote->cantidad = $rowLotes->cantidad;
                      $nueva_cantidad -= $rowLotes->cantidad;
                    endif;
                    $kardex_lote->save();
                  endif;
                endforeach;
                
                //actualizo cantidad entregada en kardex_lotes
                if($nueva_cantidad == 0 ):
                  $cantidad_total = $medicamento['cantidadDispensada'];
                else:
                  $cantidad_total = $medicamento['cantidadDispensada'] - $nueva_cantidad;
                endif;

                $actualizarkardex = Kardexe::where('id', $idkardex)->first();
                $actualizarkardex->update([
                    "cantidad" => $cantidad_total, 
                    "estado" => 1
                ]);
              endif;
            endif;
          }
        }
      }

      DB::commit();
      return $request->recetaValeId;
    } catch (SigesapolException $e) {
        DB::rollBack();
        Log::warning("ActualizarRecetaValeUseCase - WARNING", [
          "error" => $e->getMessage(),
          "status" => $e->getStatus(),
          "user" => Auth::id()
        ]);
        throw $e;
    } catch (Exception $e) {
      DB::rollBack();
      Log::error("ActualizarRecetaValeUseCase - ERROR", [
        "error" => $e->getMessage(),
        "file"  => $e->getFile(),
        "line"  => $e->getLine(),
        "user"  => Auth::id()
      ]);
      throw new SigesapolException("Error al actualizar receta vale.", 500);
    }
  }
}