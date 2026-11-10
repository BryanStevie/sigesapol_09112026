<?php


namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class SignosVitales extends Model
{
    protected $table = 'signo_vitales';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_atencion',
        'id_enfermeria',
        'dias_estancia',
        'created_us',
        'fecha_atencion',
        'created_at'
    ];

    public function enfermeria()
    {
        return $this->belongsTo(Enfermeria::class, 'id');
    }

    // Relación con Emergencia

    public function signoVitalDetalle()
    {
        return $this->hasMany(signosVitalesDetalles::class, 'id_signo_vital');
    }

    public function GetUltimoRegistroSignoVital($id_tipo_atencion, $id_atencion)
    {
        $registro = DB::table('enfermerias as e')
            ->join('signo_vitales as sv', 'e.id', '=' ,'sv.id_enfermeria')
            ->where('e.id_ext_enfermeria', '=', $id_atencion)
            ->where('e.id_tipo_atencion', '=', $id_tipo_atencion)
            ->select('sv.*')
            ->orderBy('sv.created_at', 'desc')
            ->first();

        return $registro;
    }

    public function GetSignosVitalesDetalleById($id_signo_vital)
    {
        $signos_vitales = DB::table('signo_vitales_detalles as svd')
            ->join('caracter_fisicos as cf', 'svd.id_caracter_fisico', '=' ,'cf.id')
            ->where('svd.id_signo_vital', '=', $id_signo_vital)
            ->select('svd.id_caracter_fisico', 'cf.descripcion as signo_vital', 'svd.descripcion as valor')
            ->orderBy('svd.id_caracter_fisico', 'asc')
            ->get();
        return $signos_vitales;
    }
}
