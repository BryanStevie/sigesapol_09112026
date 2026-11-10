<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Turno extends Model 
{
    protected $table = 'turnos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'descripcion',
        'hora_ini', 
        'hora_fin',
        'hora_fin_turno',
        'abreviatura',
        'id_estado_reg',
        'created_at', 
        'created_us',
        'updated_at',
        'updated_us',
        'deleted_at',
        'deleted_us'
    ];

    protected $hidden = ['created_at', 'created_us', 'updated_at', 'updated_us', 'deleted_at', 'deleted_us'];
    
    
	public function ShowAll(){
        $datos=DB::table('turnos') 
                ->select('turnos.*')
                ->where("turnos.flag_enfermeria_hospitalizacion", null)
                ->get();
        return $datos;
        
    }

	public function ShowAllHospiEvol(){
        $datos=DB::table('turnos') 
                ->select('turnos.*')
                ->where("turnos.flag_enfermeria_hospitalizacion", 1)
                ->get();
        return $datos;
        
    }
	
}
