<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Catalogos\CatAreas;
use App\Models\Catalogos\CatPuesto;
use App\Models\Catalogos\CatSexo;
use App\Models\Catalogos\CatUAdscripcion;
use App\Models\Catalogos\CatFirmaAplicada;
use App\Models\Catalogos\CatInstruccion;
use App\Models\Catalogos\CatTipoFirma;
use App\Models\Catalogos\CatEstadousurio;
use App\Models\Catalogos\CatEmpleados;
use App\Models\Catalogos\CatRoles;
use App\Models\Catalogos\CatDestinoDocumento;
use App\Models\Catalogos\CatDocConfiguracion;
use App\Models\Catalogos\CatEtapaDoc;
use App\Models\Catalogos\CatPrioridad;
use App\Models\Catalogos\CatTipoNotificacion;
use App\Models\Catalogos\CatTipoDocumento;

class Catalogo extends Model
{
    use HasFactory;

    public static function getCatAreas(){
        $catalogo = CatAreas::with('adscripcion', 'areaPadre')->get()->map(function ($item) {
            return [
                'id' => $item->n_id_cat_area,
                'unidadAdscripcion' => $item->adscripcion->s_desc_unidad,
                'areaPadre' => $item->areaPadre->s_desc_area,
                'area' => $item->s_desc_area,
                'abreviatura' => $item->s_abrev_area

            ];
        });
        return $catalogo;
    }
    public static function getCatPuesto(){
        $catalogo = CatPuesto::all()->map(function ($item) {
                return [
                    'id' => $item->n_id_puesto,
                    'nombramiento' => $item->s_desc_nombramiento,
                    'tipoUsuario' => $item->n_tipo_usuario
                ];
            });
            return $catalogo;
    }

    public static function getCatSexo(){
        $catalogo = CatSexo::all()->map(function ($item) {
                return [
                    'id' => $item->id_sexo,
                    'sexo' => $item->sexo_desc,
                    'abreviatura' => $item->sexo
                ];
            });
            return $catalogo;
    }

    public static function getCatUAdscripcion(){
        $catalogo = CatUAdscripcion::all()->map(function ($item) {
                return [
                    'id' => $item->n_id_u_adscripcion,
                    'unidad' => $item->s_desc_unidad,
                    'abreviatura' => $item->s_abrev_unidad
                ];
            });
            return $catalogo;
    }

    public static function getCatFirmaAplicada(){
        $catalogo = CatFirmaAplicada::all()->map(function ($item) {
            return [
                'id' => $item->id_firma_aplicada,
                'firmaAplicada' => $item->desc_firma_aplicada
            ];
        });
        return $catalogo;
    }

    public static function getCatInstruccion(){
        $catalogo = CatInstruccion::all()->map(function ($item) {
            return [
                'id' => $item->id_instruccion_doc,
                'instruccion' => $item->desc_instruccion_doc
            ];
        });
        return $catalogo;
    }

    public static function getCatTipoFirma(){
        $catalogo = CatTipoFirma::all()->map(function ($item) {
            return [
                'id' => $item->id_tipo_firma,
                'tipoFirma' => $item->desc_tipo_firma
            ];
        });
            return $catalogo;
    }

    public static function getCatEstadoUsuario(){
        $catalogo = CatEstadousurio::all()->map(function ($item) {
            return [
                'id' => $item->n_id_estado_usuario,
                'descripcion' => $item->s_descripcion
            ];
        });
        return $catalogo;
    }

    public static function getCatEmpleados(){
        $catalogo = CatEmpleados::with('sexo')->get()->map(function ($item) {
            return [
                'id' => $item->n_id_num_empleado,
                'nombre' => $item->nombre,
                'apellido1' => $item->apellido1,
                'apellido2' => $item->apellido2,
                'sexo' => $item->sexo->sexo_desc,
                'emailP' => $item->s_email_pers,
                'emailI' => $item->s_email_inst,
                'telPers' => $item->tel_pers,
                'telInst' => $item->tel_inst,
                'curp' => $item->curp,
                'rfc' => $item->rfc,
                'fotografia' => $item->path_fotografia,
                'idUsuario' => '/api/get-usuarios/' . $item->n_id_usuario
            ];
        });
        return $catalogo;
    }

    public static function getCatRoles(){
        $catalogo = CatRoles::with('rolPadre')->get()->map(function ($item) {
            return [
                'id' => $item->n_id_rol,
                'rolPadre' => $item->rolPadre->s_descripcion,
                'etiqueta' => $item->s_etiqueta_rol,
                'descripcion' => $item->s_descripcion
            ];
        });
        return $catalogo;
    }

    public static function getCatDestino(){
        $catalogo = CatDestinoDocumento::all()->map(function ($item) {
            return [
                'id' => $item->n_id_tipo_destino,
                'destino' => $item->desc_destino_documento
            ];
        });
            return $catalogo;
    }

    public static function getCatConfiguracion(){
        $catalogo = CatDocConfiguracion::all()->map(function ($item) {
            return [
                'id' => $item->n_id_doc_config,
                'atributo' => $item->s_atributo,
                'valor' => $item->s_valor
            ];
        });
            return $catalogo;
    }

    public static function getCatEtapaDoc(){
        $catalogo = CatEtapaDoc::all()->map(function ($item) {
            return [
                'id' => $item->id_etapa_documento,
                'etapa' => $item->s_desc_etapa
            ];
        });
            return $catalogo;
    }

    public static function getCatPrioridad(){
        $catalogo = CatPrioridad::all()->map(function ($item) {
            return [
                'id' => $item->n_id_prioridad,
                'prioridad' => $item->desc_prioridad
            ];
        });
            return $catalogo;
    }

    public static function getCatNotificacion(){
        $catalogo = CatTipoNotificacion::all()->map(function ($item) {
            return [
                'id' => $item->n_id_tipo_notif,
                'tipo' => $item->desc_tipo,
                'icono' => $item->icon_tipo_notif
            ];
        });
            return $catalogo;
    }

    public static function getCatTipoDocumento(){
        $catalogo = CatTipoDocumento::with('area')->get()->map(function ($item) {
            return [
                'id' => $item->n_id_tipo_documento,
                'area' => $item->area->s_desc_area,
                'tipoDocumento' => $item->desc_tipo_documento

            ];
        });
        return $catalogo;
    }

    /**----------------- */

    public static function findMenuByName($menus, $menuName) {
        foreach ($menus as $menu) {
            if ($menu['nombre'] == $menuName) {
                return $menu;
            }

            if (isset($menu['menu']) && is_array($menu['menu'])) {
                $result = self::findMenuByName($menu['menu'], $menuName);
                if ($result) {
                    return $result;
                }
            }
        }
        return null;
    }
}
//Privacidad y seguridad, Apariencia, Documentos, Mis Documentos, Faltantes
