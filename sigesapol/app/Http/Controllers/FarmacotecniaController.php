<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Carbon\Carbon;
use DateTime;
use DB;
use App;
use Auth;
use Illuminate\Support\Facades\Route;

class FarmacotecniaController extends Controller
{
  public function all_productos_galenicos($id_user = 0)
  {

    $model_usuario = new User;
    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    if ($ruta == "listar_referencia_ajax");
    $ruta = "all_referencias";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    $id_user = Auth::user()->id;

    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('j-m-Y', $fechaDesdey);

    $establecimiento = Auth::user()->id_establecimiento;

    $galenicos = "SELECT p.id,p.codigo,p.nombre,p.estado,p.form_farm,p.presentacion FROM productos p where id_tipo = '12' ORDER BY p.id ASC";
          $galenicos = DB::select($galenicos);

    return view('frontend.farmacotecnia.all_productos_galenicos', compact('fechaDesde','establecimiento','fechaHasta','galenicos','permiso'));
  }

  public function ver_formula_galenico($id_trama = 0)
{
    $sql = "SELECT fm.*, p.nombre,p.codigo, u.nombre as um_nombre
            FROM formula_magistrales fm
            LEFT JOIN productos p ON p.id = fm.producto_insumo
            LEFT JOIN unidades u on u.id = fm.um_formula
            WHERE fm.codigo_producto_galenicos = $id_trama order by id asc";
    $insumos = DB::select($sql);
    $html = '<table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Codigo Insumo</th>
                        <th>Producto Insumo</th>
                        <th>Concentración</th>
                        <th>Cantidad Fórmula</th>
                        <th>Unidad de Medida</th>
                    </tr>
                </thead>
                <tbody">';
$contador = 1;
foreach ($insumos as $i) {
    $html .= "<tr>
                <td>{$contador}</td>
                <td>{$i->nombre}</td>
                <td>{$i->codigo}</td>
                <td>{$i->concentracion_formula} %</td>
                <td>{$i->cantidad_formula}</td>
                <td>{$i->um_nombre}</td>
             </tr>";
    $contador++;
}
    $html .= "</tbody></table>";
    return $html;
}

public function calculo_formula_galenicos($id_trama = 0)
{
    $sql = "SELECT fm.*, p.nombre, p.codigo, p.precio_trama, u.nombre as um_nombre ,u1.nombre as um_nombre_producto, p.um_producto,p.cantidad_producto
            FROM formula_magistrales fm
            LEFT JOIN productos p ON p.id = fm.producto_insumo
            LEFT JOIN unidades u ON u.id = fm.um_formula
            LEFT JOIN unidades u1 ON u1.id = p.um_producto
            WHERE fm.codigo_producto_galenicos = $id_trama
            ORDER BY fm.id ASC";

    $insumos = DB::select($sql);

    $html = '<table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código Insumo</th>
                        <th>Producto Insumo</th>
                        <th style="background-color: #d4f8d4;">Cantidad Producto</th>
                        <th style="background-color: #d4f8d4;">Unidad de Medida Producto</th>
                        <th style="background-color: #fff9c4;">Cantidad Fórmula</th>
                        <th style="background-color: #fff9c4;">Unidad de Medida Formula</th>
                        <th style="background-color: #fff9c4;">Precio Actual del Producto</th>
                        <th style="background-color: #ffedf3;">Calculo Formula</th>
                        <th style="background-color: #ffedf3;">Calculo Conversión</th>
                        <th style="background-color: #ffedf3;">Resultado</th>
                    </tr>
                </thead>
                <tbody>';

    $contador = 1;
    $total = 0;
    foreach ($insumos as $i) {
        $unidad_formula = $i->um_nombre;
        $unidad_producto = $i->um_nombre_producto;
        $texto = ($unidad_formula == $unidad_producto) ? 1 : 1000;
        $cantidad_formula   = floatval($i->cantidad_formula);
        $cantidad_producto  = floatval($i->cantidad_producto);
        $texto              = floatval($texto);
        $precio             = floatval($i->precio_trama);
        if ($cantidad_producto > 0 && $texto > 0) {
            $resultado1 = $cantidad_formula / ($cantidad_producto * $texto);
            $resultado  = $resultado1 * $precio;
            $resultado = round($resultado, 5);
        } else {
            $resultado = 0;
        }
        $total = $total + $resultado;
        $total2 = $total + $total*2/100;
        $html .= "<tr>
                    <td>{$contador}</td>
                    <td>{$i->codigo}</td>
                    <td>{$i->nombre}</td>
                    <td style='background-color: #d4f8d4;'>{$i->cantidad_formula}</td>
                    <td style='background-color: #d4f8d4;'>{$unidad_formula}</td>
                    <td style='background-color: #fff9c4;'>{$i->cantidad_producto}</td>
                    <td style='background-color: #fff9c4;'>{$unidad_producto}</td>
                    <td style='background-color: #fff9c4;'>{$i->precio_trama}</td>
                    <td style='background-color: #ffedf3;'>
                      ( {$i->cantidad_formula} {$unidad_formula} / {$i->cantidad_producto} {$unidad_producto} ) * {$i->precio_trama}
                    </td>
                    <td style='background-color: #ffedf3;'>
                      ( {$i->cantidad_formula}  {$unidad_formula} / {$i->cantidad_producto} * {$texto}  {$unidad_formula}) * {$i->precio_trama}
                    </td>
                                        <td style='background-color: #ffedf3;'>
                    {$resultado} 
                    </td>
                 </tr>";

        $contador++;
    }

 $html .= "
  <tr>
    <td colspan='9' style='text-align:right;'>TOTAL:</td>
    <td colspan='1' style='font-weight:bold; background-color:#97a5f7;'> {$total} + ({$total} * 2 %)</td>
    <td  style='font-weight:bold; background-color:#5d72e8;'> {$total2}</td>
  </tr>";

    return $html;
}

      public function reporte_galenicos_precio(Request $request){
        
        $iduser = Auth::user()->id;
        $id_establecimiento = Auth::user()->id_establecimiento;
        $date = Carbon::now();
        
        $excelName = 'Reporte_Galenicos_Precio'. $iduser. '_' . Carbon::now()->format('Y-m-d_H-i-s');
        $excelNameResponse = 'Reporte_Galenicos_Precio'. $iduser . '_' . Carbon::now()->format('Y-m-d_H-i-s'). '.xlsx';
        $excel = Excel::create($excelName , function($excel) use($date,$iduser,$id_establecimiento ) {
        $excel->sheet('GENERAL', function($sheet) use($date, $iduser ,$id_establecimiento) {
            
            $variable = [];
            $n = 1;

            $galenico = "SELECT p.id FROM productos p WHERE id_tipo = '12' ORDER BY p.id ASC";
            $galenico = DB::select($galenico);

                foreach ($galenico as $individual){
                      $sql = "SELECT fm.*, p.nombre, p.codigo, p.precio_trama, u.nombre as um_nombre ,u1.nombre as um_nombre_producto, p.um_producto,p.cantidad_producto
                              FROM formula_magistrales fm
                              LEFT JOIN productos p ON p.id = fm.producto_insumo
                              LEFT JOIN unidades u ON u.id = fm.um_formula
                              LEFT JOIN unidades u1 ON u1.id = p.um_producto
                              WHERE fm.codigo_producto_galenicos = $individual->id
                              ORDER BY fm.id ASC";

                      $insumos = DB::select($sql);
                       $total = 0;

                      foreach ($insumos as $i) {
                          $unidad_formula = $i->um_nombre;
                          $unidad_producto = $i->um_nombre_producto;
                          $texto = ($unidad_formula == $unidad_producto) ? 1 : 1000;
                          $cantidad_formula   = floatval($i->cantidad_formula);
                          $cantidad_producto  = floatval($i->cantidad_producto);
                          $texto              = floatval($texto);
                          $precio             = floatval($i->precio_trama);
                          if ($cantidad_producto > 0 && $texto > 0) {
                              $resultado1 = $cantidad_formula / ($cantidad_producto * $texto);
                              $resultado1  = round($resultado1, 5);
                              $resultado  = $resultado1 * $precio;
                              $resultado = round($resultado, 5);
                          } else {
                              $resultado = 0;
                          }
                          $total = $total + $resultado;
                          $total = round($total, 5);
                          $total2 = $total + $total*2/100;
                          $total2 = round($total2, 5);
                        }

                        DB::table('actualizacion_precio_galenicos')->insert([
                          'codigo_producto_galenicos' => $individual->id,
                          'precio' => strval($total2),
                          'created_at' => $date,
                          'created_us' => $iduser
                           ]);
                  }

            $resultado = "SELECT DISTINCT ON (p.id)
                                p.id,
                                p.codigo,
                                p.nombre,
                                a.precio,
                                a.created_at
                          FROM productos p
                          INNER JOIN actualizacion_precio_galenicos a 
                                  ON a.codigo_producto_galenicos = p.id
                          WHERE p.id_tipo = '12'
                          ORDER BY p.id ASC, a.created_at DESC";
            $resultado = DB::select($resultado);

            $sheet->mergeCells('A1:D1');
            $sheet->cell('A1', function($cell) {$cell->setValue('REPORTE PRECIO ACTUALIZADO - FARMACOTECNIA');  $cell->setFontSize(15); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });
            
            array_push($variable, array('Reporte Precio Actualizado - FARMACOTECNIA  De ' . $date ));
            array_push($variable, array(""));
            array_push($variable, array("N","Codigo","Nombre", "Precio"));
            foreach ($resultado as $u) {
              
                array_push($variable, array($n++, $u->codigo, $u->nombre,$u->precio )); 
            }

            $sheet->with($variable)->mergeCells('A2:D2'); 
        });
    });
    $excel->store('xlsx', false, true)['full'];
    return response()->json(['excelNameResponse' => $excelNameResponse]);
  }
}
