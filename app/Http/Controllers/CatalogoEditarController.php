<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\CatExpedientes;
use App\Models\Catalogos\CatInstruccionDest;
use App\Models\Catalogos\CatNivelModulo;
use App\Models\EmpleadoPuesto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

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

class CatalogoEditarController extends Controller
{
    public function editarItemCatalogo($catalogo, $id, Request $request)
    {
        $user = $this->hasPermission($request);
        if (empty($user->idEmpleado)) {
            return response()->json(['message' => $user->error_msj], 400);
        }
        if ($catalogo == "empleadoPuesto") {
            // Validación de campos no vacíos
            if (
                empty($request->get('area')) ||
                empty($request->get('puesto')) ||
                empty($request->get('fechaAlta'))
            ) {
                return response()->json(['message' => 'Los campos no pueden estar vacíos'], 422);
            }

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
        if ($catalogo == "destinoDoc") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('tab_cat_destino_documento', 'desc_destino_documento')->ignore($id, 'n_id_tipo_destino')],
                ]);
                $catalogo = CatDestinoDocumento::findOrFail($id);
                $catalogo->desc_destino_documento = $validatedData['descripcion'];
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->n_id_tipo_destino,
                            'descripcion' => $catalogo->desc_destino_documento,
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
        if ($catalogo == "docConfig") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('tab_cat_doc_config', 's_valor')->ignore($id, 'n_id_doc_config')],
                ]);
                $catalogo = CatDocConfiguracion::findOrFail($id);
                $catalogo->s_valor = $validatedData['descripcion'];
                $catalogo->s_atributo = $request->abreviacion;
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->n_id_doc_config,
                            'descripcion' => $catalogo->s_valor,
                            'abreviatura' => $catalogo->s_atributo,
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
        if ($catalogo == "etapaDocumento") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('tab_cat_etapa_documento', 's_desc_etapa')->ignore($id, 'id_etapa_documento')],
                ]);
                $catalogo = CatEtapaDoc::findOrFail($id);
                $catalogo->s_desc_etapa = $validatedData['descripcion'];
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->id_etapa_documento,
                            'descripcion' => $catalogo->s_desc_etapa,
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
        if ($catalogo == "prioridad") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('tab_cat_prioridad', 'desc_prioridad')->ignore($id, 'n_id_prioridad')],
                ]);
                $catalogo = CatPrioridad::findOrFail($id);
                $catalogo->desc_prioridad = $validatedData['descripcion'];
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->n_id_prioridad,
                            'descripcion' => $catalogo->desc_prioridad,
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
        if ($catalogo == "nivelModulo") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('seg_cat_nivel_modulo', 'desc_nivel')->ignore($id, 'n_id_nivel')],
                ]);
                $catalogo = CatNivelModulo::findOrFail($id);
                $catalogo->desc_nivel = $validatedData['descripcion'];
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->n_id_nivel,
                            'descripcion' => $catalogo->desc_nivel,
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
        if ($catalogo == "roles") {
            try {
                $validatedData = $request->validate([
                    'abreviatura' => [Rule::unique('seg_org_roles', 's_etiqueta_rol')->ignore($id, 'n_id_rol')],
                    'descripcion' => [Rule::unique('seg_org_roles', 's_descripcion')->ignore($id, 'n_id_rol')],
                ]);
                $catalogo = CatRoles::findOrFail($id);
                $catalogo->s_descripcion = $validatedData['descripcion'];
                $catalogo->s_etiqueta_rol = $validatedData['abreviatura'];
                $catalogo->n_id_rol_padre = $request->rolPadre;
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->n_id_rol,
                            'descripcion' => $catalogo->s_descripcion,
                            'abreviatura' => $catalogo->s_etiqueta_rol
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
        if ($catalogo == "estadoUsuario") {
            try {
                $validatedData = $request->validate([
                    'descripcion' => [Rule::unique('seg_cat_estado_usuario', 's_descripcion')->ignore($id, 'n_id_estado_usuario')],
                ]);
                $catalogo = CatEstadousurio::findOrFail($id);
                $catalogo->s_descripcion = $validatedData['descripcion'];
                $catalogo->update();
                return response()->json(
                    [
                        'status' => "OK",
                        'message' => 'Se editó correctamente el item',
                        'data' => [
                            'id' => $catalogo->n_id_estado_usuario,
                            'descripcion' => $catalogo->s_descripcion,
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

}
