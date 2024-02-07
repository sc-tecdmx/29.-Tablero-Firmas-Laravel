<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\CatExpedientes;
use App\Models\Catalogos\CatInstruccionDest;
use App\Models\Catalogos\CatInstruccionDoc;
use App\Models\Catalogos\CatNivelModulo;
use App\Models\EmpleadoPuesto;
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

class CatalogoCrearController extends Controller
{
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
        ///
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
         ////
        if ($catalogo == "roles") {
            $descripcion = $request->get('descripcion');
            $abreviatura = $request->get('abreviatura');
            $rolPadre = $request->get('rolPadre');

            // Verificar si ya existe un registro con esa descripción
            $existe = CatRoles::where('s_etiqueta_rol', $abreviatura)->where('s_descripcion', $descripcion)->first();

            if (!$existe) {
                try {
                    // Si no existe, crea un nuevo registro
                    $data = [
                        's_etiqueta_rol' => $abreviatura,
                        's_descripcion' => $descripcion,
                        'n_id_rol_padre' => $rolPadre,
                        'n_rec_activo' => 1
                    ];
                    $result = CatRoles::create($data);

                    return response()->json([
                        'status' => "OK",
                        'mensaje' => 'Se agregó el item satisfactoriamente',
                        'data' => [
                            'id' => $result->n_id_rol,
                            'descripcion' => $result->s_descripcion,
                            'abreviatura' => $result->s_etiqueta_rol,
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
}
