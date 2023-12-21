<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\CatExpedientes;
use App\Models\Catalogos\CatInstruccionDest;
use App\Models\Catalogos\CatInstruccionDoc;
use App\Models\Catalogos\CatNivelModulo;
use App\Models\EmpleadoPuesto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use App\Models\Catalogo;

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
use App\CatalogoDTO;

class CatalogoController extends Controller
{
    public $APP_SEGURIDAD;
    public $APP_ENV;
    public function __construct()
    {
        $this->APP_SEGURIDAD = config('services.seguridad.url');
        $this->APP_ENV = config('services.env.config');
    }

    public function hasPermission(Request $request)
    {
        $user = new \stdClass();
        $user->idEmpleado = null;
        $user->idUsuario = null;
        $user->error_msj = null;
        $user->token = null;

        $header_name = $this->APP_ENV == 'prod' ? 'bearertoken' : 'Authorization';
        $token = $request->header($header_name);
        if (!empty($token)) {
            $header_request_name = $this->APP_ENV == 'prod' ? 'Bearer ' : '';
            $user->token = $header_request_name . $token;

            $response = Http::withHeaders([
                'Authorization' => $user->token,
            ])->post($this->APP_SEGURIDAD . '/api/seguridad/userinfo');
            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']) && isset($data['data']['idEmpleado'])) {
                    $user->idEmpleado = $data['data']['idEmpleado'];
                    $user->idUsuario = $data['data']['idUsuario'];
                } else {
                    $user->error_msj = 'idEmpleado no está presente en la respuesta';
                }
            } else {
                $user->error_msj = 'Error al comunicarse con el servicio de userinfo: ' . $response->status();
            }
        }
        return $user;
    }

    public function eliminarItemCatalogo($catalogo, $id, Request $request)
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

    }

    public function editarItemCatalogo($catalogo, $id, Request $request)
    {
        $user = $this->hasPermission($request);
        if (empty($user->idEmpleado)) {
            return response()->json(['message' => $user->error_msj], 400);
        }
        if ($catalogo == "empleadoPuesto") {
            try {
                $catEmPues = EmpleadoPuesto::findOrFail($id);
                $catEmPues->n_id_cat_area = $request->get('area');
                $catEmPues->n_id_puesto = $request->get('puesto');
                $catEmPues->fecha_alta = $request->get('fechaAlta');
                $catEmPues->fecha_conclusion = $request->get('fechaConclusion');

                $catEmPues->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catEmPues->n_id_empleado_puesto,
                            'area' => $catEmPues->n_id_cat_area,
                            'puesto' => $catEmPues->n_id_puesto,
                            'fechaAlta' => $catEmPues->fecha_alta,
                            'fechaConclusion' => $catEmPues->fecha_conclusion
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validación fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "sexo") {
            try {
                $validatedData = $request->validate([
                    'nombreItem' => [Rule::unique('inst_cat_sexo', 'sexo_desc')->ignore($id, 'id_sexo')],
                    'abreviatura' => [Rule::unique('inst_cat_sexo', 'sexo')->ignore($id, 'id_sexo')],
                ]);
                $catSexo = CatSexo::findOrFail($id);
                $catSexo->sexo_desc = $validatedData['nombreItem'];
                $catSexo->sexo = $validatedData['abreviatura'];
                $catSexo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catSexo->id_sexo,
                            'descripcion' => $catSexo->sexo_desc,
                            'abreviacion' => $catSexo->sexo,
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validacion fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "expedientes") {
            try {
                $validatedData = $request->validate([
                    'numExpediente' => [Rule::unique('tab_expedientes', 's_num_expediente')->ignore($id, 'n_num_expediente')],
                    'descripcion' => [Rule::unique('tab_expedientes', 's_descripcion')->ignore($id, 'n_num_expediente')],
                ]);
                $catEdit = CatExpedientes::findOrFail($id);
                $catEdit->s_num_expediente = $validatedData['numExpediente'];
                $catEdit->s_descripcion = $validatedData['descripcion'];
                $catEdit->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catEdit->n_num_expediente,
                            'numExpediente' => $catEdit->s_num_expediente,
                            'descripcion' => $catEdit->s_descripcion,
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validacion fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "instFirmante") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('tab_cat_inst_firmantes', 'desc_instr_firmante')->ignore($id, 'n_id_inst_firmante')],
                ]);
                $catEdit = CatInstruccion::findOrFail($id);
                $catEdit->desc_instr_firmante = $validatedData['descripcion'];
                $catEdit->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catEdit->n_id_inst_firmante,
                            'descripcion' => $catEdit->desc_instr_firmante,
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validacion fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "instDestinatario") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('tab_cat_inst_dest', 'desc_inst_dest')->ignore($id, 'n_id_inst_dest')],
                ]);
                $catEdit = CatInstruccionDest::findOrFail($id);
                $catEdit->desc_inst_dest = $validatedData['descripcion'];
                $catEdit->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catEdit->n_id_inst_dest,
                            'descripcion' => $catEdit->desc_inst_dest,
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validacion fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "tipoDoc") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('tab_cat_tipo_documento', 'desc_tipo_documento')->ignore($id, 'n_id_tipo_documento')],
                ]);
                $catEdit = CatTipoDocumento::findOrFail($id);
                $catEdit->desc_tipo_documento = $validatedData['descripcion'];
                $catEdit->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catEdit->n_id_tipo_documento,
                            'descripcion' => $catEdit->desc_tipo_documento,
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validacion fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "unidadAds") {
            try {
                $validatedData = $request->validate([
                    'nombreItem' => [Rule::unique('inst_u_adscripcion', 's_desc_unidad')->ignore($id, 'n_id_u_adscripcion')],
                    'abreviatura' => [Rule::unique('inst_u_adscripcion', 's_abrev_unidad')->ignore($id, 'n_id_u_adscripcion')],
                ]);
                $catalogo = CatUAdscripcion::findOrFail($id);
                $catalogo->s_desc_unidad = $validatedData['nombreItem'];
                $catalogo->s_abrev_unidad = $validatedData['abreviatura'];
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->n_id_u_adscripcion,
                            'descripcion' => $catalogo->s_desc_unidad,
                            'abreviacion' => $catalogo->s_abrev_unidad,
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validacion fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "puesto") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('inst_cat_puestos', 's_desc_nombramiento')->ignore($id, 'n_id_puesto')],
                ]);
                $catalogo = CatPuesto::findOrFail($id);
                $catalogo->s_desc_nombramiento = $validatedData['descripcion'];
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->n_id_puesto,
                            'descripcion' => $catalogo->s_desc_nombramiento,
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validación fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "areas") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [
                        'required',
                        'string',
                        'max:255',
                        Rule::unique('inst_cat_areas', 's_desc_area')
                            ->where(function ($query) use ($request) {
                                return $query->where('n_id_u_adscripcion', $request->unidadA);
                            })
                            ->ignore($id, 'n_id_cat_area')
                    ],
                    'unidadA' => 'required|integer',
                    // Añadir validación para 'abreviatura' y 'areaPadre' según se necesite
                    // ...
                ]);
                $catalogo = CatAreas::findOrFail($id);
                $catalogo->n_id_u_adscripcion = $validatedData['unidadA'];
                $catalogo->s_desc_area = $validatedData['descripcion'];
                $catalogo->s_abrev_area = $request->input('abreviatura');
                $catalogo->n_id_cat_area_padre = $request->input('areaPadre');
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->n_id_cat_area,
                            'unidadA' => $catalogo->n_id_u_adscripcion,
                            'descripcion' => $catalogo->s_desc_area,
                            'abreviatura' => $catalogo->s_abrev_area,
                            'areaPadre' => $catalogo->n_id_cat_area_padre,
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validación fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "tipoFirma") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('pki_cat_tipo_firma', 'desc_tipo_firma')->ignore($id, 'id_tipo_firma')],
                ]);
                $catalogo = CatTipoFirma::findOrFail($id);
                $catalogo->desc_tipo_firma = $validatedData['descripcion'];
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->id_tipo_firma,
                            'descripcion' => $catalogo->desc_tipo_firma,
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validación fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "notificacion") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('tab_cat_tipo_notificacion', 'desc_tipo')->ignore($id, 'n_id_tipo_notif')],
                ]);
                $catalogo = CatTipoNotificacion::findOrFail($id);
                $catalogo->desc_tipo = $validatedData['descripcion'];
                $catalogo->icon_tipo_notif = $request->icono;
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->n_id_tipo_notif,
                            'descripcion' => $catalogo->desc_tipo,
                            'icono'=> $catalogo->icon_tipo_notif
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validación fallida', 'errors' => $e->errors()], 422);
            }
        }
        if ($catalogo == "firmaAplicada") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('pki_cat_firma_aplicada', 'desc_firma_aplicada')->ignore($id, 'id_firma_aplicada')],
                ]);
                $catalogo = CatFirmaAplicada::findOrFail($id);
                $catalogo->desc_firma_aplicada = $validatedData['descripcion'];
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->id_firma_aplicada,
                            'descripcion' => $catalogo->desc_firma_aplicada,
                        ]
                    ],
                    200
                );
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Item no encontrado'], 404);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validación fallida', 'errors' => $e->errors()], 422);
            }
        }
    }

    public function agregarItemCatalogo($catalogo, Request $request)
    {
        $user = $this->hasPermission($request);
        if (empty($user->idEmpleado)) {
            return response()->json(['message' => $user->error_msj], 400);
        }

        ////////////////////

        if ($catalogo == "sexo") {
            $abreviatura = $request->get('abreviatura');
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esos datos
            $existe = CatSexo::where('sexo_desc', $descripcion)
                ->first();

            if (!$existe) {
                // Si no existe, crea un nuevo registro
                $result = CatSexo::create([
                    'sexo_desc' => $descripcion,
                    'sexo' => $abreviatura
                ]);

                return response()->json([
                    'status' => "OK",
                    'mensaje' => 'Se agregó el item satisfactoriamente',
                    'data' => [
                        'id' => $result->id_sexo,
                        'sexo' => $result->sexo_desc,
                        'abreviatura' => $result->sexo,
                    ]
                ], 200);

            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        /////////
        if ($catalogo == "expedientes") {
            $numExpediente = $request->get('numExpediente');
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con ese número de expediente
            $existe = CatExpedientes::where('s_num_expediente', $numExpediente)
                ->where('s_descripcion', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        's_num_expediente' => $numExpediente,
                        's_descripcion' => $descripcion,
                        'n_id_usuario_creador' => $user->idUsuario
                    ];
                    $result = CatExpedientes::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_num_expediente,
                            'numExpediente' => $result->s_num_expediente,
                            'descripcion' => $result->s_descripcion,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con alguno de los datos proporcionados.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El expediente con ese número ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "instFirmante") {
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatInstruccion::where('desc_instr_firmante', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $result = CatInstruccion::create([
                        'desc_instr_firmante' => $descripcion,
                    ]);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_inst_firmante,
                            'descripcion' => $result->desc_instr_firmante,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "instDestinatario") {
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatInstruccionDest::where('desc_inst_dest', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        'desc_inst_dest' => $descripcion,
                    ];
                    $result = CatInstruccionDest::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_inst_dest,
                            'descripcion' => $result->desc_inst_dest,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ///
        if ($catalogo == "etapaDocumento") {
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatEtapaDoc::where('s_desc_etapa', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        's_desc_etapa' => $descripcion,
                    ];
                    $result = CatEtapaDoc::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->id_etapa_documento,
                            'descripcion' => $result->s_desc_etapa,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ///
        if ($catalogo == "tipoNotificacion") {
            $descripcion = $request->get('descripcion');
            $icono = $request->get('icono');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatTipoNotificacion::where('desc_tipo', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        'desc_tipo' => $descripcion,
                        'icon_tipo_notif' => $icono,
                    ];
                    $result = CatTipoNotificacion::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_tipo_notif,
                            'descripcion' => $result->desc_tipo,
                            'icono' => $result->icon_tipo_notif,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ///
        if ($catalogo == "docConfig") {
            $descripcion = $request->get('descripcion');
            $abreviacion = $request->get('abreviacion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatDocConfiguracion::where('s_valor', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        's_valor' => $descripcion,
                        's_atributo' => $abreviacion
                    ];
                    $result = CatDocConfiguracion::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_doc_config,
                            'descripcion' => $result->s_valor,
                            'abreviatura' => $result->s_atributo,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "prioridad") {
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatPrioridad::where('desc_prioridad', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        'desc_prioridad' => $descripcion,
                    ];
                    $result = CatPrioridad::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_prioridad,
                            'descripcion' => $result->desc_prioridad,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "destinoDoc") {
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatDestinoDocumento::where('desc_destino_documento', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        'desc_destino_documento' => $descripcion,
                    ];
                    $result = CatDestinoDocumento::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_tipo_destino,
                            'descripcion' => $result->desc_destino_documento,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "tipoDoc") {

            $empleadoPuesto = EmpleadoPuesto::where('n_id_num_empleado', $user->idEmpleado)->first();

            if (!$empleadoPuesto) {
                return response()->json(['message' => 'Empleado no encontrado ' . $user->idEmpleado], 404);
            }
            $idArea = $empleadoPuesto->n_id_cat_area;

            $descripcion = $request->get('descripcion');
            $areaId = $idArea;

            // Verificar si ya existe un registro con esa descripción
            $existe = CatTipoDocumento::where('desc_tipo_documento', $descripcion)
                ->where('n_id_cat_area', $areaId)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        'desc_tipo_documento' => $descripcion,
                        'n_id_cat_area' => $areaId
                    ];
                    $result = CatTipoDocumento::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_tipo_documento,
                            'descripcion' => $result->desc_tipo_documento,
                            'areaId' => $result->n_id_cat_area,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "puesto") {
            $descripcion = $request->get('descripcion');
            $tipo = $request->get('tipo');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatPuesto::where('s_desc_nombramiento', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        's_desc_nombramiento' => $descripcion,
                        'n_tipo_usuario' => $tipo
                    ];
                    $result = CatPuesto::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_puesto,
                            'descripcion' => $result->s_desc_nombramiento,
                            'tipo' => $result->n_tipo_usuario,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "unidadAds") {
            $descripcion = $request->get('descripcion');
            $abreviatura = $request->get('abreviatura');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatUAdscripcion::where('s_desc_unidad', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        's_desc_unidad' => $descripcion,
                        's_abrev_unidad' => $abreviatura
                    ];
                    $result = CatUAdscripcion::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_u_adscripcion,
                            'descripcion' => $result->s_desc_unidad,
                            'abreviatura' => $result->s_abrev_unidad,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        //// esta faltando resolver dettalles
        if ($catalogo == "area") {
            $unidad = $request->get('idUnidadAds');
            $descripcion = $request->get('descripcion');
            $abreviatura = $request->get('abreviatura');
            $areaPadre = $request->get('idAreaPadre');

            // Verificar si ya existe un registro con esa descripción
            $query = CatAreas::where('s_desc_area', $descripcion);
            /*  if ($areaPadre !== null) {
                  // Si se proporcionó un área padre, inclúyela en la consulta
                  $query->where('n_id_cat_area_padre', $areaPadre);
              }*/

            $existe = $query->first();
            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        'n_id_u_adscripcion' => $unidad,
                        's_desc_area' => $descripcion,
                        's_abrev_area' => $abreviatura,
                        'n_id_cat_area_padre' => $areaPadre
                    ];
                    $result = CatAreas::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_cat_area,
                            'unidadAds' => $result->n_id_u_adscripcion,
                            'descripcion' => $result->s_desc_area,
                            'abreviatura' => $result->s_abrev_area,
                            'areaPadre' => $result->n_id_cat_area_padre,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al intentar crear el registro.' . $ex,
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "nivelModulo") {
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatNivelModulo::where('desc_nivel', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        'desc_nivel' => $descripcion,
                    ];
                    $result = CatNivelModulo::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_nivel,
                            'descripcion' => $result->desc_nivel,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "estadoUsuario") {
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatEstadousurio::where('s_descripcion', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        's_descripcion' => $descripcion,
                    ];
                    $result = CatEstadousurio::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_estado_usuario,
                            'descripcion' => $result->s_descripcion,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "firmaAplicada") {
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatFirmaAplicada::where('desc_firma_aplicada', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        'desc_firma_aplicada' => $descripcion,
                    ];
                    $result = CatFirmaAplicada::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->id_firma_aplicada,
                            'descripcion' => $result->desc_firma_aplicada,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        ////
        if ($catalogo == "instrucDoc") {
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatInstruccionDoc::where('desc_instruccion_doc', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        'desc_instruccion_doc' => $descripcion,
                    ];
                    $result = CatInstruccionDoc::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->id_instruccion_doc,
                            'descripcion' => $result->desc_instruccion_doc,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }
        if ($catalogo == "tipoFirma") {
            $descripcion = $request->get('descripcion');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatTipoFirma::where('desc_tipo_firma', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        'desc_tipo_firma' => $descripcion,
                    ];
                    $result = CatTipoFirma::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->id_tipo_firma,
                            'descripcion' => $result->desc_tipo_firma,
                        ]
                    ], 200);
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Manejar la excepción si se produce una violación de la restricción de integridad
                    return response()->json([
                        'status' => "Error",
                        'mensaje' => 'Error al crear el item, puede que ya exista uno con la descripción proporcionada.',
                    ], 409);
                }
            } else {
                // Si el registro ya existe, devuelve un mensaje de error
                return response()->json([
                    'status' => "Error",
                    'mensaje' => 'El item con esa descripción ya existe',
                ], 409); // Código de estado 409 Conflict
            }
        }

    }

    public function getCatalogoPantalla($pantalla, Request $request)
    {
        $user = $this->hasPermission($request);
        if (empty($user->idEmpleado)) {
            return response()->json(['message' => $user->error_msj], 400);
        }

        if ($pantalla == 'nuevo-documento') {
            $catDestino = Catalogo::getCatDestino();
            $catTipoDocumento = Catalogo::getCatTipoDocumento($user->idEmpleado);
            $catInstruccionFirmantes = Catalogo::getCatInstruccion();
            $catInstruccionDestinatarios = Catalogo::getCatInstruccionDest();
            $catTipoFirma = Catalogo::getCatTipoFirma();
            $catPrioridad = Catalogo::getCatPrioridad();

            return response()->json(
                [
                    'status' => "OK",
                    'mensaje' => 'Solicitud exitosa',
                    'data' => [

                        'catDestino' => $catDestino,
                        'catTipoDocumento' => $catTipoDocumento,
                        'catInstruccionFirmantes' => $catInstruccionFirmantes,
                        'catInstruccionDestinatarios' => $catInstruccionDestinatarios,
                        'catTipoFirma' => $catTipoFirma,
                        'catPrioridad' => $catPrioridad
                    ]
                ]
                ,
                200
            );
        }
        if ($pantalla == 'catalogos') {


        }
        if ($pantalla == 'administracion') {
            $catalogo = Catalogo::getCatAreas();
            $catalogo = Catalogo::getCatPuesto();
            $catalogo = Catalogo::getCatUAdscripcion();
            $catalogo = Catalogo::getCatEmpleados();



        }



    }

    public function getCatalogo($catalogo, Request $request)
    {
        $user = $this->hasPermission($request);
        if (empty($user->idEmpleado)) {
            return response()->json(['message' => $user->error_msj], 400);
        }

        $data = Http::withHeaders([
            'Authorization' => $user->token,
        ])->get($this->APP_SEGURIDAD . '/api/seguridad/get-menu');

        $response = json_decode($data, true);
        $urlBuscada = "/documentos/seguimiento/completados";
        $permisosUrlBuscada = []; // Aquí almacenaremos los permisos cuando los encontremos
        $respData = Catalogo::findMenuByName($response['menu'], 'Faltantes');

        //consulta el catalogo por nombre
        if ($catalogo == "empleadoPuesto") { //areas mostrar arbol
            $catalogo = Catalogo::getEmpleadoPuesto();
            return $catalogo;
        }
        if ($catalogo == "areas") { //areas mostrar arbol
            $catalogo = Catalogo::getCatAreas();
            return $catalogo;
        }
        if ($catalogo == "nivelModulo") { //areas mostrar arbol
            $catalogo = Catalogo::getNivelModulo();
            return $catalogo;
        }
        if ($catalogo == "expedientes") { //areas mostrar arbol
            $catalogo = Catalogo::getCatExpedientes();
            return $catalogo;
        }
        if ($catalogo == "puestos") {
            $catalogo = Catalogo::getCatPuesto();
            return $catalogo;
        }
        if ($catalogo == "sexo") {
            $catalogo = Catalogo::getCatSexo();
            return $catalogo;
        }
        if ($catalogo == "unidad-adscripcion") { //unidad adscripcion
            $catalogo = Catalogo::getCatUAdscripcion();
            return $catalogo;
        }
        if ($catalogo == "firma-aplicada") {
            $catalogo = Catalogo::getCatFirmaAplicada();
            return $catalogo;
        }
        if ($catalogo == "instruccion") { //instruccion documento []
            $catalogo = Catalogo::getCatInstruccion();
            return $catalogo;
        }
        if ($catalogo == "instDestinatario") { //instruccion documento []
            $catalogo = Catalogo::getCatInstruccionDest();
            return $catalogo;
        }
        if ($catalogo == "tipo-firma") {
            $catalogo = Catalogo::getCatTipoFirma();
            return $catalogo;
        }
        if ($catalogo == "estado-usuario") {
            $catalogo = Catalogo::getCatEstadoUsuario();
            return $catalogo;
        }
        if ($catalogo == "empleados") {
            $catalogo = Catalogo::getCatEmpleados();
            return $catalogo;
        }
        if ($catalogo == "roles") {
            $catalogo = Catalogo::getCatRoles();
            return $catalogo;
        }
        if ($catalogo == "destino") {
            $catalogo = Catalogo::getCatDestino();
            return $catalogo;
        }
        if ($catalogo == "configuracion") {
            $catalogo = Catalogo::getCatConfiguracion();
            return $catalogo;
        }
        if ($catalogo == "etapa") {
            $catalogo = Catalogo::getCatEtapaDoc();
            return $catalogo;
        }
        if ($catalogo == "prioridad") {
            $catalogo = Catalogo::getCatPrioridad();
            return $catalogo;
        }
        if ($catalogo == "notificacion") { //[]
            $catalogo = Catalogo::getCatNotificacion();
            return $catalogo;
        }
        if ($catalogo == "tipo-documento") { //tipo del documento agregar arbol
            $catalogo = Catalogo::getCatTipoDocumento($user->idEmpleado);
            return $catalogo;
        }
        return null;
    }

    public function autocompletado(Request $request)
    {
        $query = $request->get('query');
        $results = CatExpedientes::where('s_num_expediente', 'like', '%' . $query . '%')->get(['n_num_expediente', 's_num_expediente', 's_descripcion']);

        return response()->json($results);

    }
}
