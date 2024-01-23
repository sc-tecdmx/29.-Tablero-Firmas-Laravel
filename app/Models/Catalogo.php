<?php

namespace App\Models;

use App\Models\Catalogos\CatExpedientes;
use App\Models\Catalogos\CatNivelModulo;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Catalogos\CatAreas;
use App\Models\Catalogos\CatPuesto;
use App\Models\Catalogos\CatSexo;
use App\Models\Catalogos\CatUAdscripcion;
use App\Models\Catalogos\CatFirmaAplicada;
use App\Models\Catalogos\CatInstruccion;
use App\Models\Catalogos\CatInstruccionDest;
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
    //***************Catálogo de áreas en formato de arbol***********************+ */
    public static function getCatAreas()
    {
        // Obtenemos todas las áreas con las relaciones necesarias
        $areas = CatAreas::with('adscripcion', 'areaPadre', 'children')->get();

        // Construimos el árbol de áreas
        $tree = self::buildTree($areas, null, false);

        return $tree;
    }
    //***************Método recursivo que construlle el arbol
    private static function buildTree($areas, $parentId = null, $isChild = false)
    {
        $branch = [];

        foreach ($areas as $area) {
            if ($area->n_id_cat_area_padre === $parentId) {
                $children = self::buildTree($areas, $area->n_id_cat_area, true);
                $node = [
                    'id' => $area->n_id_cat_area,
                    'area' => $area->s_desc_area,
                    'abreviatura' => $area->s_abrev_area
                ];

                if (!$isChild) {
                    $node['unidadAdscripcion'] = optional($area->adscripcion)->s_desc_unidad;
                    $node['AreasHijas'] = $children;
                } else {
                    // Para las áreas hijas, solo agregamos 'AreasHijas' si hay hijos
                    if (!empty($children)) {
                        $node['AreasHijas'] = $children;
                    }
                }

                $branch[] = $node;
            }
        }

        return $branch;
    }

    //***************Termina Catálogo de áreas en formato de arbol***********************+ */
    public static function getNivelModulo()
    {
        $catalogo = CatNivelModulo::all()->map(function ($item) {
            return [
                'id' => $item->n_id_nivel,
                'descripcion' => $item->desc_nivel
            ];
        });
        return $catalogo;
    }
    public static function getCatPuesto()
    {
        $catalogo = CatPuesto::all()->map(function ($item) {
            return [
                'id' => $item->n_id_puesto,
                'nombramiento' => $item->s_desc_nombramiento,
                'tipoUsuario' => $item->n_tipo_usuario
            ];
        });
        return $catalogo;
    }
    public static function getCatExpedientes()
    {
        $catalogo = CatExpedientes::all()->map(function ($item) {
            return [
                'id' => $item->n_num_expediente,
                'numExpediente' => $item->s_num_expediente,
                'descripcion' => $item->s_descripcion,
            ];
        });
        return $catalogo;
    }

    public static function getCatSexo()
    {
        $catalogo = CatSexo::all()->map(function ($item) {
            return [
                'id' => $item->id_sexo,
                'sexo' => $item->sexo_desc,
                'abreviatura' => $item->sexo
            ];
        });
        return $catalogo;
    }

    public static function getCatUAdscripcion()
    {
        $catalogo = CatUAdscripcion::all()->map(function ($item) {
            return [
                'id' => $item->n_id_u_adscripcion,
                'unidad' => $item->s_desc_unidad,
                'abreviatura' => $item->s_abrev_unidad
            ];
        });
        return $catalogo;
    }

    public static function getCatFirmaAplicada()
    {
        $catalogo = CatFirmaAplicada::all()->map(function ($item) {
            return [
                'id' => $item->id_firma_aplicada,
                'firmaAplicada' => $item->desc_firma_aplicada
            ];
        });
        return $catalogo;
    }

    public static function getCatInstruccion()
    {
        $catalogo = CatInstruccion::all()->map(function ($item) {
            return [
                'id' => $item->n_id_inst_firmante,
                'instruccion' => $item->desc_instr_firmante
            ];
        });
        return $catalogo;
    }

    public static function getCatInstruccionDest()
    {
        $catalogo = CatInstruccionDest::all()->map(function ($item) {
            return [
                'id' => $item->n_id_inst_dest,
                'instruccion' => $item->desc_inst_dest
            ];
        });
        return $catalogo;
    }

    public static function getCatTipoFirma()
    {
        $catalogo = CatTipoFirma::all()->map(function ($item) {
            return [
                'id' => $item->id_tipo_firma,
                'tipoFirma' => $item->desc_tipo_firma
            ];
        });
        return $catalogo;
    }

    public static function getCatEstadoUsuario()
    {
        $catalogo = CatEstadousurio::all()->map(function ($item) {
            return [
                'id' => $item->n_id_estado_usuario,
                'descripcion' => $item->s_descripcion
            ];
        });
        return $catalogo;
    }

    public static function getCatEmpleados()
    {
        $catalogo = CatEmpleados::with('sexo', 'empleadoPuesto.puesto', 'empleadoPuesto.area')
        ->where('activo', 1)
        ->orderBy('nombre', 'asc')
        ->orderBy('apellido1', 'asc')
        ->orderBy('apellido2', 'asc')
        ->get()->map(function ($item) {
            return [
                'id' => $item->n_id_num_empleado,
                'nombre' => $item->nombre,
                'apellido1' => $item->apellido1,
                'apellido2' => $item->apellido2,
                'area' => optional($item->empleadoPuesto->area)->s_desc_area,
                'puesto' => optional($item->empleadoPuesto->puesto)->s_desc_nombramiento,
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

    public static function getCatRoles()
    {
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

    public static function getCatDestino()
    {
        $catalogo = CatDestinoDocumento::all()->map(function ($item) {
            return [
                'id' => $item->n_id_tipo_destino,
                'destino' => $item->desc_destino_documento
            ];
        });
        return $catalogo;
    }

    public static function getCatConfiguracion()
    {
        $catalogo = CatDocConfiguracion::all()->map(function ($item) {
            return [
                'id' => $item->n_id_doc_config,
                'atributo' => $item->s_atributo,
                'valor' => $item->s_valor
            ];
        });
        return $catalogo;
    }

    public static function getCatEtapaDoc()
    {
        $catalogo = CatEtapaDoc::all()->map(function ($item) {
            return [
                'id' => $item->id_etapa_documento,
                'etapa' => $item->s_desc_etapa
            ];
        });
        return $catalogo;
    }

    public static function getCatPrioridad()
    {
        $catalogo = CatPrioridad::all()->map(function ($item) {
            return [
                'id' => $item->n_id_prioridad,
                'prioridad' => $item->desc_prioridad
            ];
        });
        return $catalogo;
    }

    public static function getCatNotificacion()
    {
        $catalogo = CatTipoNotificacion::all()->map(function ($item) {
            return [
                'id' => $item->n_id_tipo_notif,
                'tipo' => $item->desc_tipo,
                'icono' => $item->icon_tipo_notif
            ];
        });
        return $catalogo;
    }
    public static function getCatTipoDocumento($idEmpleado)
    {
        $empleadoPuesto = EmpleadoPuesto::where('n_id_num_empleado', $idEmpleado)->first();

        if (!$empleadoPuesto) {
            return response()->json(['message' => 'Empleado no encontrado ' . $idEmpleado], 404);
        }
        $idArea = $empleadoPuesto->n_id_cat_area;

        $catalogo = CatTipoDocumento::whereHas('area', function ($query) use ($idArea) {
            $query->where('n_id_cat_area', $idArea);
        })
            ->orWhereDoesntHave('area') // Aquí se añade la condición para incluir documentos sin área
            ->with('area')
            ->get()
            ->map(function ($item) {
                $areaDesc = $item->area ? $item->area->s_desc_area : 'No definida';

                return [
                    'id' => $item->n_id_tipo_documento,
                    'area' => $areaDesc,
                    'tipoDocumento' => $item->desc_tipo_documento
                ];
            });

        return $catalogo;
    }

    public static function getEmpleadoPuesto()
    {
        $empleadoPuesto = EmpleadoPuesto::with([
            'empleado', // Relación con CatEmpleados
            'puesto', // Relación con CatPuesto
            'area.adscripcion' // Relación con CatAreas y luego con CatUAdscripcion
            ])->whereHas('empleado', function ($query) {
                $query->where('activo', 1);
            })->get();

        // Transformando los resultados para incluir la información deseada
        $empleadosPuesto = $empleadoPuesto->map(function ($item) {
            return [
                "id"=>$item->n_id_empleado_puesto,
                'empleado' => $item->empleado->nombre,
                'apellido1'=>$item->empleado->apellido1,
                'apellido2'=>$item->empleado->apellido2,
                'unidadA' => optional($item->area->adscripcion)->s_desc_unidad,
                'area' => optional($item->area)->s_desc_area,
                'puesto' => optional($item->puesto)->s_desc_nombramiento,
                'fechaAlta' => optional($item)->fecha_alta,
                'fechaConclusion' => optional($item)->fecha_conclusion
            ];
        });

        // Devolviendo la respuesta como JSON
        return response()->json($empleadosPuesto);
    }

    /**----------------- */

    public static function findMenuByName($menus, $menuName)
    {
        foreach ($menus as $menu) {
            if ($menu['nombreModulo'] == $menuName) {
                return $menu;
            }

            if (isset($menu['modulos']) && is_array($menu['modulos'])) {
                $result = self::findMenuByName($menu['modulos'], $menuName);
                if ($result) {
                    return $result;
                }
            }
        }
        return null;
    }
}

//Privacidad y seguridad, Apariencia, Documentos, Mis Documentos, Faltantes
