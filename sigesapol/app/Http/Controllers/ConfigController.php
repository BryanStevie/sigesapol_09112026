<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ConfigOption;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
  /**
     * Obtener todas las opciones de configuración
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllOptions()
    {
        try {
            $options = ConfigOption::orderBy('option_name')->get();
            return response()->json($options);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener opciones por prefijo
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOptionsByPrefix(Request $request)
    {
        try {
            $prefix = $request->input('prefix', '');
            
            $query = ConfigOption::query();
            
            if (!empty($prefix)) {
                $query->where('option_name', 'like', $prefix . '%');
            }
            
            $options = $query->orderBy('option_name')->get();
            return response()->json($options);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener opciones que son grupos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupOptions()
    {
        try {
            $groups = ConfigOption::where('option_grupo', true)
                ->orderBy('option_name')
                ->get();
            
            return response()->json($groups);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Guardar múltiples opciones
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveOptions(Request $request)
    {
        try {
            $options = $request->input('options', []);
            
            if (empty($options)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se proporcionaron opciones para guardar'
                ]);
            }
            
            // Iniciar transacción
            DB::beginTransaction();
            
            foreach ($options as $id => $value) {
                $option = ConfigOption::find($id);
                
                if ($option) {
                    $option->option_value = $value;
                    $option->save();
                }
            }
            
            // Confirmar transacción
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Opciones guardadas correctamente'
            ]);
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar las opciones: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Actualizar una opción
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOption(Request $request)
    {
        try {
         
           
            
            $option = ConfigOption::find($request->input('id'));
            
            if (!$option) {
                return response()->json([
                    'success' => false,
                    'message' => 'Opción no encontrada'
                ]);
            }
            
            // Verificar que el nombre no esté duplicado (excepto para esta misma opción)
            if ($option->option_name != $request->input('option_name')) {
                $existingOption = ConfigOption::where('option_name', $request->input('option_name'))
                    ->where('id', '!=', $option->id)
                    ->first();
                    
                if ($existingOption) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ya existe una opción con ese nombre'
                    ]);
                }
            }
            
            // Actualizar los datos
            $option->option_name = $request->input('option_name');
            $option->option_label = $request->input('option_label');
            $option->option_type = $request->input('option_type');
            $option->option_value = $request->input('option_value');
            $option->autoload = $request->input('autoload', false);
            $option->option_grupo = $request->input('option_grupo', false);
            
            // Si es un grupo, no puede depender de otro grupo
            if ($option->option_grupo) {
                $option->option_dependiente = null;
            } else {
                $option->option_dependiente = $request->input('option_dependiente');
            }
            
            $option->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Opción actualizada correctamente',
                'option' => $option
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la opción: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Crear una nueva opción
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOption(Request $request)
    {
        try {
           
            // Crear la nueva opción
            $option = new ConfigOption();
            $option->option_name = $request->input('option_name');
            $option->option_label = $request->input('option_label');
            $option->option_type = $request->input('option_type');
            $option->option_value = $request->input('option_value');
            $option->autoload = $request->input('autoload', true);
            $option->option_grupo = $request->input('option_grupo', false);
            
            // Si es un grupo, no puede depender de otro grupo
            if ($option->option_grupo) {
                $option->option_dependiente = null;
            } else {
                $option->option_dependiente = $request->input('option_dependiente');
            }
            
            $option->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Opción creada correctamente',
                'option' => $option
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la opción: ' . $e->getMessage()
            ]);
        }
    }


    public function mantenimiento(){
      return view('frontend.error.mantenimientos');
    }
}
