<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class HospitalizacionAtencione extends Model
{
    protected $table = 'hospitalizacion_atenciones';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
      'id_prestacion',
      'id_ext',
      'tiempo_enfermedad',
      'forma_inicio',
      'curso',
      'sintomas_signos_principal',
      'relato',
      'tratamiento_recibido_antes',
      'fn_bio_1',
      'fn_bio_2',
      'fn_bio_3',
      'fn_bio_4',
      'fn_bio_5',
      'fn_bio_6',
      'antc_1',
      'antc_2',
      'antc_3',
      'antc_4',
      'antc_5',
      'antc_6',
      'antc_7',
      'antc_8',
      'antc_9',
      'antc_10',
      'antc_11',
      'antc_12',
      'antc_13',
      'antc_14',
      'antc_15',
      'antc_16',
      'antc_17',
      'antc_18',
      'antc_19',
      'antc_20',
      'antc_21',
      'antc_22',
      'antc_23',
      'antc_24',
      'antc_25',
      'antc_26',
      'antc_27',
      'antc_28',
      'antc_29',
      'antc_30',
      'antc_31',
      'antc_32',
      'antc_33',
      'exam_gen_1',
      'exam_gen_2',
      'exam_gen_3',
      'exam_gen_4',
      'exam_gen_5',
      'exam_gen_6',
      'exam_gen_7',
      'exam_gen_8',
      'exam_gen_9',
      'exam_reg_1',
      'exam_reg_2',
      'exam_reg_3',
      'exam_reg_4',
      'exam_reg_5',
      'exam_reg_6',
      'exam_reg_7',
      'exam_reg_8',
      'exam_reg_9',
      'exam_reg_10',
      'exam_reg_11',
      'exam_reg_12',
      'exam_reg_13',
      'exam_reg_14',
      'exam_reg_15',
      'exam_reg_16',
      'exam_reg_17',
      'exam_reg_18',
      'exam_reg_19',
      'exam_reg_20',
      'exam_reg_21',
      'exam_reg_22',
      'exam_reg_23',
      'exam_reg_24',
      'exam_reg_25',
      'exam_reg_26',
      'exam_reg_27',
      'ampliacion_exam_fisico',
      'created_us',
      'created_at',
      'updated_us',
      'ruta_img_exam_gen_9',
      'ruta_img_cabeza_cuello',
      'ruta_img_mama',
      'ruta_img_exam_reg_10',
      'ruta_img_exam_reg_11',
      'ruta_img_exam_reg_12',
      'ruta_img_exam_reg_13',
      'ruta_img_neurologico',
      'id_gestacion',
      'id_puerperio',
      'dieta_terapeutica',
      'complementario_terapeutica',
      'indicacion_terapeutica',
      'hoja_terapeutica',
    ];

    public function GetByIdAsegurado($id_asegurado)
    {
        $data = DB::table("hospitalizacion_atenciones as ha")
            ->join('prestaciones as p', 'ha.id_prestacion', '=', 'p.id')
            ->where('p.id_asegurado', $id_asegurado)
            ->orderBy('ha.created_at', 'desc')
            ->first();
        return $data;
    }
}