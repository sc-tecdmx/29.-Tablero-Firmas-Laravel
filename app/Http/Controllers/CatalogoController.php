<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\CatExpedientes;
use App\Models\Catalogos\CatInstruccionDest;
use App\Models\Catalogos\CatInstruccionDoc;
use App\Models\Catalogos\CatNivelModulo;
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

    public function eliminarItemCatalogo($catalogo, $id, Request $request)
    {
        $token = $request->header('Authorization');
        if (empty($token)) {
            return response()->json(['message' => 'No cuenta con permisos para realizar esta operación'], 400);
        }

        if ($catalogo == "sexo") {
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
        }

    }

    public function editarItemCatalogo($catalogo, $id, Request $request)
    {
        $token = $request->header('Authorization');
        if (empty($token)) {
            return response()->json(['message' => 'No cuenta con permisos para realizar esta operación'], 400);
        }
        //////
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
        //////
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
         //////
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
    }

    public function agregarItemCatalogo($catalogo, Request $request)
    {
        $token = $request->header('Authorization');
        if (empty($token)) {
            return response()->json(['message' => 'No cuenta con permisos para realizar esta operación'], 400);
        }
        $data = Http::withHeaders([
            'Authorization' => $token,
        ])->get('http://localhost:8080/api/seguridad/userinfo');

        $response = json_decode($data, true);
        if (isset($response['data']) && isset($response['data']['idUsuario'])) {
            $idUsuario = $response['data']['idUsuario'];
        } else {
            $idUsuario = null;
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
                        'n_id_usuario_creador' => $idUsuario
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
            $descripcion = $request->get('descripcion');
            $areaId = $request->get('areaId');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatTipoDocumento::where('desc_tipo_documento', $descripcion)->first();

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
            // $unidad = $request->get('idUnidadAds');
            $descripcion = $request->get('descripcion');
            $abreviatura = $request->get('abreviatura');
            //  $areaPadre = $request->get('idAreaPadre');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatAreas::where('s_desc_area', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        //  'n_id_u_adscripcion' => $unidad,
                        's_desc_area' => $descripcion,
                        's_abrev_area' => $abreviatura,
                        //  'n_id_cat_area_padre'=>$areaPadre
                    ];
                    $result = CatAreas::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_cat_area,
                            // 'unidadAds' => $result->n_id_u_adscripcion,
                            'descripcion' => $result->s_desc_area,
                            'abreviatura' => $result->s_abrev_area,
                            // 'areaPadre'=> $result->n_id_cat_area_padre,
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

    }

    public function getCatalogoPantalla($pantalla, Request $request)
    {
        $token = $request->header('Authorization');
        if (empty($token)) {
            return response()->json(['message' => 'No cuenta con permisos para realizar esta operación'], 400);
        }

        if ($pantalla == 'nuevo-documento') {
            $catDestino = Catalogo::getCatDestino();
            $catTipoDocumento = Catalogo::getCatTipoDocumento();
            $catInstruccionFirmantes = Catalogo::getCatInstruccion();
            $catInstruccionDestinatarios = Catalogo::getCatInstruccionDest();
            $catTipoFirma = Catalogo::getCatTipoFirma();
            $catPrioridad = Catalogo::getCatPrioridad();

            /*$catAreas = Catalogo::getCatAreas();
            $catPuesto = Catalogo::getCatPuesto();
            $catSexo = Catalogo::getCatSexo();
            $catUAdscripcion = Catalogo::getCatUAdscripcion();
            $catFirmaAplicada = Catalogo::getCatFirmaAplicada();
            $catEstadoUsuario = Catalogo::getCatEstadoUsuario();
            $catEmpleados = Catalogo::getCatEmpleados();
            $catRoles = Catalogo::getCatRoles();
            $catConfiguracion = Catalogo::getCatConfiguracion();
            $catEtapa = Catalogo::getCatEtapa();
            $catNotificacion = Catalogo::getCatNotificacion();*/

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
                        'catPrioridad' => $catPrioridad,
                        /* 'catUAdscripcion' => $catUAdscripcion,
                         'catFirmaAplicada' => $catFirmaAplicada,
                         'catEstadoUsuario' => $catEstadoUsuario,
                         'catEmpleados' => $catEmpleados,
                         'catRoles' => $catRoles,
                         'catConfiguracion' => $catConfiguracion,
                         'catEtapa' => $catEtapa,
                         'catNotificacion' => $catNotificacion,*/
                    ]
                ]
                ,
                200
            );
        }



    }

    public function getCatalogo($catalogo, Request $request)
    {
        $token = $request->header('Authorization');
        if (empty($token)) {
            return response()->json(['message' => 'No cuenta con permisos para realizar esta operación'], 400);
        }
        $data = Http::withHeaders([
            'Authorization' => $token,
        ])->get('http://localhost:8080/api/seguridad/get-menu');

        $response = json_decode($data, true);
        $urlBuscada = "/documentos/seguimiento/completados";
        $permisosUrlBuscada = []; // Aquí almacenaremos los permisos cuando los encontremos
        $respData = Catalogo::findMenuByName($response['menu'], 'Faltantes');

        //consulta el catalogo por nombre
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
            $catalogo = Catalogo::getCatTipoDocumento();
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
