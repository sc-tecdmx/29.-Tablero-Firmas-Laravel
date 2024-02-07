<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\CatExpedientes;
use App\Models\Catalogos\CatInstruccionDest;
use App\Models\Catalogos\CatNivelModulo;
use Illuminate\Http\Request;

use App\Models\Catalogos\CatAreas;
use App\Models\Catalogos\CatPuesto;
use App\Models\Catalogos\CatSexo;
use App\Models\Catalogos\CatUAdscripcion;
use App\Models\Catalogos\CatFirmaAplicada;
use App\Models\Catalogos\CatInstruccion;
use App\Models\Catalogos\CatTipoFirma;
use App\Models\Catalogos\CatEstadousurio;
use App\Models\Catalogos\CatRoles;
use App\Models\Catalogos\CatDestinoDocumento;
use App\Models\Catalogos\CatDocConfiguracion;
use App\Models\Catalogos\CatEtapaDoc;
use App\Models\Catalogos\CatPrioridad;
use App\Models\Catalogos\CatTipoNotificacion;
use App\Models\Catalogos\CatTipoDocumento;

class CatalogoEliminarController extends Controller
{    public function eliminarItemCatalogo($catalogo, $id, Request $request)
    {

        $user = $this->hasPermission($request);
        if (empty($user->idEmpleado)) {
            return response()->json(['message' => $user->error_msj], 400);
        }

        if ($catalogo == "sexo") {
            try {
                $catSexo = CatSexo::find($id);
                // Verificar si el registro existe
                if (!$catSexo) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $catSexo->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente',
                        'data' => [
                            'id' => $catSexo->id_sexo,
                            'sexo' => $catSexo->sexo_desc,
                            'abreviatura' => $catSexo->sexo,
                        ]
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "tipoDoc") {
            try {
                $cat = CatTipoDocumento::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "areas") {
            try {
                $cat = CatAreas::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "puesto") {
            try {
                $cat = CatPuesto::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "unidadAds") {
            try {
                $cat = CatUAdscripcion::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "tipoFirma") {
            try {
                $cat = CatTipoFirma::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "notificacion") {
            try {
                $cat = CatTipoNotificacion::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "instFirmantes") {
            try {
                $cat = CatInstruccion::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "insDestinatarios") {
            try {
                $cat = CatInstruccionDest::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "firmaAplicada") {
            try {
                $cat = CatFirmaAplicada::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "destinoDoc") {
            try {
                $cat = CatDestinoDocumento::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "docConfig") {
            try {
                $cat = CatDocConfiguracion::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "etapaDocumento") {
            try {
                $cat = CatEtapaDoc::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "prioridad") {
            try {
                $cat = CatPrioridad::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "expedientes") {
            try {
                $cat = CatExpedientes::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "nivelModulo") {
            try {
                $cat = CatNivelModulo::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "roles") {
            try {
                $cat = CatRoles::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
        if ($catalogo == "estadoUsuario") {
            try {
                $cat = CatEstadousurio::find($id);
                // Verificar si el registro existe
                if (!$cat) {
                    return response()->json(['mensaje' => 'Registro no encontrado'], 404);
                }
                // Eliminar el registro
                $cat->delete();
                return response()->json(
                    [
                        'status' => "OK",
                        'mensaje' => 'Se eliminó el item satisfactoriamente'
                    ]
                    ,
                    200
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Maneja la excepción de violación de integridad
                return response()->json(['message' => 'Este registro no puede ser eliminado porque está en uso.'], 400);
            }
        }
    }


}
