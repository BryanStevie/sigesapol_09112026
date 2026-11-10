<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Asegurado extends Model
{
    protected $table = 'asegurados';
    protected $primaryKey = 'id';

    protected $fillable = [
      'tipo_doc_ident',
      'nro_doc_ident',
      'cip',
      'nombre',
      'paterno',
      'materno',
      'sexo',
      'fecha_nac',
      'id_estado_reg',
      'observacion',
      'created_at',
      'created_us',
      'updated_at',
      'updated_us',
      'deleted_at',
      'deleted_us',
      'telefono',
      'domicilio',
      'correo',
      'id_tipo_sangre',
      'grado',
      'foto',
      'id_tipo_doc_ident',
      'id_tipo_beneficiario',
      'id_asegurado_titular',
      'id_departamento',
      'id_provincia',
      'id_distrito',
      'tipo_sangre',
      'telefono2',
      'fecha_nac_2',
      'bak_fecha_nac',
      'bak_sexo',
      'codigo_ubig',
      'situacion_paciente',
      'unidad_pnptitular',
      'caducidad',
      'dni_titular',
      'id_raza',
      'id_estado_civil',
      'id_religion',
      'id_grado_instruccion',
      'id_ocupacion',
      'id_distrito_procedencia',
      'direccion_procedencia',
      'id_distrito_nacimiento',
      'direccion_nacimiento',
      'flag_referencia',
      'id_establecimiento_referido',
      'id_referido',
      'estadios_salud_materna',
      'update_estadios_salud_materna',
      'id_prestacion_estadios_salud_materna',
      'id_secuencia_estadios_salud_materna',
      'pais_afiliado',
      'ape_casado_asegurado',
      'tipo_doc_titular',
      'paterno_titular',
      'materno_titular',
      'ape_casado_titular',
      'nombre_titular',
      'discapacidad',
      'otro_seguro',
      'discapacidad_carnet',
      'nro_carnet_conadis',
      'color_carnet_conadis',
      'id_etnia',
      'id_tutor',
      'id_tipo_parentesco',
      'id_grado',
      'id_situacion'
    ];

    public function busca_datos_asegurados($dni,$id_establecimiento){        
      $cad = "select tb.descripcion tipo_beneficiario_nombre, nro_historia, domicilio, telefono, correo, nombre, paterno, materno, sexo, fecha_nac, 
              nro_doc_ident, id_tipo_doc_ident, tipo_doc_ident,cip, a1.id_tipo_beneficiario,
              a1.id_tipo_beneficiario as tipo_beneficiario, a1.id_asegurado_titular, a1.telefono2, a1.situacion_paciente, a1.discapacidad, a1.discapacidad_carnet , a1.nro_carnet_conadis , a1.color_carnet_conadis,
              a1.presc_producto_apoyo,a1.usa_product_apoyo,a1.tipo_producto,a1.nro_tipo_producto
              from asegurados a1
              left join asegurado_historias a2 on a1.id=a2.id_asegurado
              left join tipo_beneficiarios tb on tb.id = a1.id_tipo_beneficiario 
              where a1.nro_doc_ident='".$dni."' and a2.id_establecimiento='".$id_establecimiento."'
              and nro_historia!=''
              and a1.id_estado_reg=1
              and a2.id_estado_reg=1";
      $data = DB::select($cad);
      
      return $data;
    }

    public function busca_datos_asegurados2($dni,$id_establecimiento){        
      $cad = "select tb.descripcion tipo_beneficiario_nombre, nro_historia, domicilio, telefono, correo, nombre, paterno, materno, sexo, fecha_nac, 
              nro_doc_ident, id_tipo_doc_ident, tipo_doc_ident,cip, a1.id_tipo_beneficiario,
              a1.id_tipo_beneficiario as tipo_beneficiario, a1.id_asegurado_titular, a1.telefono2, a1.situacion_paciente, a1.discapacidad, a1.discapacidad_carnet , a1.nro_carnet_conadis , a1.color_carnet_conadis
              from asegurados a1
              left join asegurado_historias a2 on a1.id=a2.id_asegurado and a2.id_establecimiento=". $id_establecimiento ." and a2.id_estado_reg=1
              left join tipo_beneficiarios tb on tb.id = a1.id_tipo_beneficiario 
              where a1.nro_doc_ident='". $dni ."'
              and a1.id_estado_reg=1";
      
      $data = DB::select($cad);
      
      return $data;
    }

        public function busca_datos_aseguradosxtiponro($tipo,$dni){        
        $cad = "select a1.id as id_asegurado, tb.descripcion tipo_beneficiario_nombre, nro_historia, domicilio, telefono, correo, nombre, paterno, materno, sexo, fecha_nac, 
                nro_doc_ident, id_tipo_doc_ident, tipo_doc_ident,cip, a1.id_tipo_beneficiario,
                a1.id_tipo_beneficiario as tipo_beneficiario, a1.id_asegurado_titular, a1.telefono2, a1.situacion_paciente, a1.discapacidad, 
                EXTRACT(YEAR FROM age(to_date(a1.fecha_nac, 'DD/MM/YYYY'))) as edad, paterno_titular,materno_titular, nombre_titular, situacion_paciente, grado
                from asegurados a1
                left join asegurado_historias a2 on a1.id=a2.id_asegurado
                left join tipo_beneficiarios tb on tb.id = a1.id_tipo_beneficiario 
                where a1.nro_doc_ident='".$dni."'  and a1.id_tipo_doc_ident='".$tipo."'
                and nro_historia!=''
                and a1.id_estado_reg=1
                and a2.id_estado_reg=1";
        $data = DB::select($cad);
        
        return $data;

    }
        public function busca_datos_aseguradosxtiponroV2($tipo,$dni){        
        $cad = "select a1.id as id_asegurado, tb.descripcion tipo_beneficiario_nombre, domicilio, telefono, correo, nombre, paterno, materno, sexo, fecha_nac, 
                nro_doc_ident, id_tipo_doc_ident, tipo_doc_ident,cip, a1.id_tipo_beneficiario,
                a1.id_tipo_beneficiario as tipo_beneficiario, a1.id_asegurado_titular, a1.telefono2, a1.situacion_paciente, a1.discapacidad, 
                EXTRACT(YEAR FROM age(to_date(a1.fecha_nac, 'DD/MM/YYYY'))) as edad, paterno_titular,materno_titular, nombre_titular, situacion_paciente, grado
                from asegurados a1
                left join tipo_beneficiarios tb on tb.id = a1.id_tipo_beneficiario 
                where a1.nro_doc_ident='".$dni."'  and a1.id_tipo_doc_ident='".$tipo."'
                and a1.id_estado_reg=1";
        $data = DB::select($cad);
        
        return $data;

    }


    public static function findIdAseguradoByDni($dni) {
        $cad = "select id from asegurados where nro_doc_ident = '".$dni."'";
        $data = DB::select($cad);
        return $data;
    }

    public function existe_nro_historia_receta_vale($dni, $id_establecimiento){
        
        $cad = "select nro_historia
                from receta_vales a1
                inner join farmacias a2 on a1.id_farmacia=a2.id
                where a1.dni_beneficiario='".$dni."' 
                and nro_historia!=''
                and a1.estado = 1
                and a2.id_establecimiento=".$id_establecimiento." ";
        $cad.= " group by  nro_historia";
        $data = DB::select($cad);
        
        if(count($data)>0)
        	return $nro_historia=$data[0]->nro_historia;
        else
        	return $nro_historia='';

    }

     public function busca_datos_asegurados_porid($id_asegurado, $id_establecimiento){        
        $cad = "select nro_historia, domicilio, telefono, correo, nombre, paterno, materno, sexo, fecha_nac, 
                nro_doc_ident, id_tipo_doc_ident, tipo_doc_ident,cip, a1.id_tipo_beneficiario,
                a1.id_tipo_beneficiario as tipo_beneficiario,  a1.id_asegurado_titular                
                from asegurados a1
                inner join asegurado_historias a2 on a1.id=a2.id_asegurado
                where a1.id='".$id_asegurado."' 
                and nro_historia!=''
                and a2.id_establecimiento=".$id_establecimiento;
        $data = DB::select($cad);
        
        return $data;

    }

    public function busca_asegurado_como_wssaludpol($tipo_documento, $nro_documento) {
        
        // $datos = DB::select("select asegurados.nro_doc_ident as nrodocafiliado,asegurados.paterno as apepatafiliado,asegurados.materno as apematafiliado,asegurados.nombre as nomafiliado,asegurados.fecha_nac as fecnacafiliado,asegurados.sexo as nomsexo,tipo_beneficiarios.descripcion as parentesco,a2.tipo_doc_ident as nomtipdoctitular,a2.nro_doc_ident as nrodoctitular,a2.paterno as apepattitular,a2.materno as apemattitular,a2.nombre as nomtitular,persona_datos_adicionales.cip,titular_grados.descripcion_saludpol as grado,situaciones.descripcion as situacion,
        // asegurados.tipo_doc_ident as nomtipdocafiliado,asegurados.tipo_doc_ident as nompaisdelafiliado, asegurados.tipo_doc_ident as apecasafiliado,asegurados.tipo_doc_ident as edadafiliado,asegurados.tipo_doc_ident as estado,asegurados.tipo_doc_ident as apecastitular,
        // asegurados.tipo_doc_ident as ubigeo,asegurados.tipo_doc_ident as caducidad,asegurados.tipo_doc_ident as discapacidad,asegurados.tipo_doc_ident as otroseguro,asegurados.tipo_doc_ident as unidad_pnp
        
        // from asegurados
        // inner JOIN tipo_documento_identidad on asegurados.id_tipo_doc_ident=tipo_documento_identidad.id
        // LEFT Join tipo_beneficiarios ON asegurados.id_tipo_beneficiario=tipo_beneficiarios.id
        // LEFT Join persona_datos_adicionales on  asegurados.id_asegurado_titular = persona_datos_adicionales.id
        // LEFT Join titular_grados on persona_datos_adicionales.id_grado = titular_grados.id
        // LEFT Join situaciones on persona_datos_adicionales.id_situacion = situaciones.id
        // LEFT Join asegurados as a2 on persona_datos_adicionales.id_asegurado = a2.id
        // where asegurados.id_tipo_doc_ident = '1' and asegurados.nro_doc_ident ='".$nro_documento."'");

        $datos = DB::table('asegurados')
                ->join('tipo_documento_identidad', 'asegurados.id_tipo_doc_ident', '=', 'tipo_documento_identidad.id')
                ->leftJoin('tipo_beneficiarios', 'asegurados.id_tipo_beneficiario', '=', 'tipo_beneficiarios.id')
                ->leftJoin('persona_datos_adicionales', 'asegurados.id_asegurado_titular', '=', 'persona_datos_adicionales.id_asegurado')
                ->leftJoin('titular_grados', 'persona_datos_adicionales.id_grado', '=', 'titular_grados.id')
                ->leftJoin('situaciones', 'persona_datos_adicionales.id_situacion', '=', 'situaciones.id')
                ->leftJoin('asegurados as a2', 'persona_datos_adicionales.id_asegurado', '=', 'a2.id')
                ->where('asegurados.id_tipo_doc_ident', '=', $tipo_documento)
                ->where('asegurados.nro_doc_ident', '=', $nro_documento)
                ->select('asegurados.nro_doc_ident as nrodocafiliado','asegurados.paterno as apepatafiliado','asegurados.materno as apematafiliado','asegurados.nombre as nomafiliado','asegurados.fecha_nac as fecnacafiliado','asegurados.sexo as nomsexo','tipo_beneficiarios.descripcion as parentesco','a2.tipo_doc_ident as nomtipdoctitular','a2.nro_doc_ident as nrodoctitular','a2.paterno as apepattitular','a2.materno as apemattitular','a2.nombre as nomtitular','persona_datos_adicionales.cip','titular_grados.descripcion_saludpol as grado','situaciones.descripcion as situacion',
                'asegurados.tipo_doc_ident as nomtipdocafiliado','asegurados.tipo_doc_ident as nompaisdelafiliado', 'asegurados.tipo_doc_ident as apecasafiliado','asegurados.tipo_doc_ident as edadafiliado','asegurados.tipo_doc_ident as estado','asegurados.tipo_doc_ident as apecastitular',
                'asegurados.tipo_doc_ident as ubigeo','asegurados.tipo_doc_ident as caducidad','asegurados.tipo_doc_ident as discapacidad','asegurados.tipo_doc_ident as otroseguro','asegurados.tipo_doc_ident as unidad_pnp', "asegurados.fecha_nac")
                ->first();
        return $datos;
        //'asegurados.paterno as apepatafiliado'
    }

    public function Busca_datos_titular($id_asegurado){        
        $cad = "select  nombre, paterno, materno, sexo, fecha_nac, grado,
                nro_doc_ident, tipo_doc_ident, cip, id_tipo_beneficiario as tipo_beneficiario                
                from asegurados a1
                where a1.id='".$id_asegurado."' 
                and a1.id_estado_reg=1";
        $data = DB::select($cad);
        
        return $data;

    }

    public function busca_asegurado_por_dni($dni_asegurado){        
        $cad = "select  id, nombre, paterno, materno, sexo, fecha_nac, grado,
                nro_doc_ident, tipo_doc_ident, cip, id_tipo_beneficiario as tipo_beneficiario
                , id_tipo_doc_ident                 
                from asegurados a1
                where a1.nro_doc_ident='".$dni_asegurado."' 
                and a1.id_estado_reg=1";
        $data = DB::select($cad);
        return $data;
    }
    public function busca_datos_asegurados_porid_nsolicitud($id_asegurado, $id_establecimiento){        
        $cad = "select nro_historia, domicilio, telefono, correo, nombre, paterno, materno, 
            case when sexo = 'M' then 'Masculino' when sexo = 'F' then 'Femenino' end AS sexo, fecha_nac, 
            nro_doc_ident, tipo_doc_ident,cip, a1.id_tipo_beneficiario,
            a1.id_tipo_beneficiario as tipo_beneficiario,  a1.id_asegurado_titular,tb.descripcion as parentesco,a1.cip,a1.grado,de.descripcion as departamento,di.descripcion as ciudad                     
            from asegurados a1
            left join asegurado_historias a2 on a1.id=a2.id_asegurado
            left Join tipo_beneficiarios tb ON a1.id_tipo_beneficiario=tb.id
            left Join departamentos de on a1.id_departamento=de.id_departamento
            left Join distritos di on a1.id_distrito=di.id_distrito 
            where a1.id='".$id_asegurado."' 
            and nro_historia!=''
            and a2.id_establecimiento='".$id_establecimiento."'";
        $data = DB::select($cad);
        return $data[0];
  
    }
   
    /**
     * Actualiza los datos de un asegurado en la tabla asegurados
     * @param string $nro_doc_ident = N�mero de documento de identidad
     * @param object $datosDirrehum = {grado, situacion_paciente} 
     * @return int|null
     */
    public function updateDataDirrehumByNroDocument($nro_doc_ident, $datosDirrehum) {
      DB::beginTransaction();
      try {
        $asegurado = Asegurado::where("nro_doc_ident", $nro_doc_ident)->first();
        if (!$asegurado) {
          return null;
        }
        $grado = isset($datosDirrehum["grado"]) ? $datosDirrehum["grado"] : $asegurado->grado;
        $situacion_paciente = isset($datosDirrehum["situacion_paciente"]) ? $datosDirrehum["situacion_paciente"] : $asegurado->situacion_paciente;
        $sqlUpdate =  DB::update(
          "UPDATE asegurados SET grado = ?, situacion_paciente = ? where id = ?",
          [$grado, $situacion_paciente, $asegurado->id]
        );
        DB::commit();
        return $asegurado->id;
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      } catch (\Throwable $th) {
        DB::rollback();
        throw $th;
      }
    }

    public function registrarDesdeWS($wsData)
    {
      $aseguradoTitular = Asegurado::where("nro_doc_ident", $wsData->nrodoctitular)->first();
      $tipoDoc = Tipo_documento_identidad::where("descripcion", $wsData->nomtipdocafiliado)->first();
      $tipoBen = Tipo_beneficiario::where("descripcion", $wsData->parentesco)->first();
      $asegurado = Asegurado::create([
        'tipo_doc_ident' => $wsData->nomtipdocafiliado,
        'nro_doc_ident' => $wsData->nrodocafiliado,
        'cip' => $wsData->cip,
        'nombre' => $wsData->nomafiliado,
        'paterno' => $wsData->apepatafiliado,
        'materno' => $wsData->apematafiliado,
        'sexo' => $wsData->nomsexo,
        'fecha_nac' => $wsData->fecnacafiliado,
        'id_estado_reg' => 1,
        'created_at' => Carbon::now(),
        'created_us' => Auth::id(),
        'grado' => $wsData->grado,
        'id_tipo_doc_ident' => $tipoDoc->id,
        'id_tipo_beneficiario' => $tipoBen->id,
        'id_asegurado_titular' => $aseguradoTitular->id,
        'caducidad' => $wsData->caducidad,
        'dni_titular' => $wsData->nrodoctitular,
        'tipo_doc_titular' => $wsData->nomtipdoctitular,
        'paterno_titular' => $wsData->apepattitular,
        'materno_titular' => $wsData->apemattitular,
        'nombre_titular' => $wsData->nomtitular,
      ]);
      return $asegurado;
    }
}
