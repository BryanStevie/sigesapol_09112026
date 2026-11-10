<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = false;
    
  public function buscar_ticket_by_id($id_ticket) { 
    $cad = "select * FROM tickets WHERE id ='".$id_ticket."'"; 
    $data = DB::select($cad);
    return $data;
  }  
  public static function registrar_HistorialLlamado($id_ticket, $id_usuario)
  {
    $cad = "SELECT sp_generar_hitorial_ticket(?,?)";
    DB::SELECT($cad, array($id_ticket, $id_usuario)); 
    return 1;
  }
 
}
