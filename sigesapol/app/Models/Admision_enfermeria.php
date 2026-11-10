<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Admision_enfermeria extends Model
{
	
    public function RegistrarNotaIngresoHospitalizados($id_principal , $id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico , $id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $hora , $id_servicio , $condicion , $estado_general) {
                                
        $cad = "Select sp_insert_nota_ingreso_hopitalizados(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_principal , $id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico , $id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $hora , $id_servicio , $condicion , $estado_general));

        return $data[0]->sp_insert_nota_ingreso_hopitalizados;

    }

  public function GetNotaIngreso($id) {
      
    $cad = "select ad.*, t2.id id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,t1.tipo_beneficiario parentesco,fecha_nac,tipo_doc_ident,pa.nombre as pabellon,p.nombre as piso,c.nombre as cuarto,ca.nombre as cama,
        t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.id_establecimiento,t3.nombre establecimiento, t1.id_consultorio id_servicio, t1.nombre_consultorio servicio, t1.id_consultorio_hosp id_sub_consultorio, t6.nombre consultorio,/* t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,*/ date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente
        FROM admision_enfermerias ad
        inner join hospitalizaciones t1 on t1.id = ad.id_paciente
        inner join asegurados t2 on t1.dni_beneficiario = t2.nro_doc_ident 
        inner join asegurado_historias t9 on t2.id = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        inner join  pabellones pa on t1.id_pabellon=pa.id
        inner join pisos p on t1.id_piso=p.id
        inner join cuartos c on t1.id_cuarto = c.id
        inner join camas ca on t1.id_cama=ca.id
        left join consultorios t6 on t1.id_consultorio=t6.id
            Where ad.id=". $id." and ad.id_estado_reg = 1"; 
    $data = DB::select($cad);
    return $data[0];
      
  }

  public function GetAdmisionEnfermeriaById($id)
  {
    $datos = Admision_enfermeria::where('id', '=', $id)->first();
    return $datos;
  }

  public function GetEnfermeriaAdmisionById($id)
  {

    $cad = "select t1.*, m.nombre nombre, m.paterno as paterno,m.materno as materno
            FROM admision_enfermerias t1
            inner join medicos m on m.id=t1.id_enfermera 
            Where t1.id=" . $id;

    $data = DB::select($cad);
    return $data[0];
  }

}