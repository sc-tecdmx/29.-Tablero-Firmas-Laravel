<?php

namespace App\Http\Controllers;

use App\Models\EmpleadoPuesto;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Http\Request;

use App\Models\GrupoFirma;
use App\Models\GrupoFirmaPersonas;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GruposController extends Controller
{
    public $APP_SEGURIDAD;
    public $APP_ENV;
    public function __construct()
    {
        $this->APP_SEGURIDAD = config('services.seguridad.url');
        $this->APP_ENV = config('services.env.config');
    }

    public function hasPermission(Request $request){
        $user = new \stdClass();
        $user->idEmpleado = null;
        $user->idUsuario = null;
        $user->error_msj = null;
        $user->token = null;

        $header_name = $this->APP_ENV=='prod'?'bearertoken':'Authorization';
        $token = $request->header($header_name);
        if (!empty($token)) {
            $header_request_name = $this->APP_ENV=='prod'?'Bearer ':'';
            $user->token = $header_request_name.$token;

            $response = Http::withHeaders([
                'Authorization' => $user->token,
            ])->post($this->APP_SEGURIDAD. '/api/seguridad/userinfo');
            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']) && isset($data['data']['idEmpleado'])) {
                    $user->idEmpleado = $data['data']['idEmpleado'];
                    $user->idUsuario = $data['data']['idUsuario'];
                } else {
                    $user->error_msj = 'idEmpleado no está presente en la respuesta';
                }
            } else {
                $user->error_msj = 'Error al comunicarse con el servicio de userinfo: '.$response->status();
            }
        }
        return $user;
    }

    public function crearGrupo(Request $request)
    {

        $user = $this->hasPermission($request);
        if (empty($user->idEmpleado)) {
            return response()->json(['message' => $user->error_msj], 400);
        }

        $empleadoPuesto = EmpleadoPuesto::where('n_id_num_empleado', $user->idEmpleado)->first();

        if (!$empleadoPuesto) {
            return response()->json(['message' => 'Empleado no encontrado ' . $user->idEmpleado], 404);
        }
        $idArea = $empleadoPuesto->n_id_cat_area;
        //////////////////

        // Validación de la solicitud
        $validator = Validator::make($request->all(), [
            'tipoGrupo' => 'required|string|max:20',
            'nombreGrupo' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tab_doc_grupos_firmas', 's_nombre_gpo_firmante')->where(function ($query) use ($idArea) {
                    return $query->where('n_id_cat_area', $idArea);
                }),
            ],
            'personas' => 'required|array',
            'personas.*.idEmpleado' => 'required|integer',
            'personas.*.idInstFirmante' => 'nullable|integer',
            'personas.*.idInstDest' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Crear un nuevo grupo
            $grupo = GrupoFirma::create([
                'n_id_cat_area' => $idArea,
                'c_tipo_grupo' => $request->tipoGrupo,
                's_nombre_gpo_firmante' => $request->nombreGrupo,
            ]);

            // Guardar personas en el grupo
            foreach ($request->personas as $persona) {
                GrupoFirmaPersonas::create([
                    'n_id_grupo_personas' => $grupo->n_id_grupo_firmas,
                    'n_id_num_empleado' => $persona['idEmpleado'],
                    'n_id_inst_firmante' => $persona['idInstFirmante'] ?? null,
                    'n_id_inst_destinatario' => $persona['idInstDest'] ?? null,
                ]);
            }

            // Comprometer la transacción
            DB::commit();

            return response()->json(['message' => 'Grupo y personas creados con éxito'], 201);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            return response()->json(['error al crear el grupo' => $e->getMessage()], 500);
        }
    }

    public function consultarGrupos($tipoGrupo, Request $request)
    {
        $user = $this->hasPermission($request);
        if (empty($user->idEmpleado)) {
            return response()->json(['message' => $user->error_msj], 400);
        }

        $empleadoPuesto = EmpleadoPuesto::where('n_id_num_empleado', $user->idEmpleado)->first();

        if (!$empleadoPuesto) {
            return response()->json(['message' => 'Empleado no encontrado ' . $user->idEmpleado], 404);
        }
        $idArea = $empleadoPuesto->n_id_cat_area;

        $grupos = GrupoFirma::with([
            'personas.empleado',
            'personas.instruccionFirmante',
            'personas.instruccionDestinatario',
            'area'
        ])->where('c_tipo_grupo', $tipoGrupo)->whereHas('area', function ($query) use ($idArea) {
            $query->where('n_id_cat_area', $idArea);
        })->get();

        $gruposTransformados = $grupos->map(function ($grupo) use ($tipoGrupo) {
            return [
                'id' => $grupo->n_id_grupo_firmas,
                'nombreGrupo' => $grupo->s_nombre_gpo_firmante,
                'area' => $grupo->area->s_desc_area,
                'personas' => $grupo->personas->map(function ($persona) use ($tipoGrupo) {
                    $instruccion = $tipoGrupo == 'Firmantes'
                        ? $persona->instruccionFirmante->desc_instr_firmante ?? null
                        : $persona->instruccionDestinatario->desc_inst_dest ?? null;
                    return [
                        'idEmpleado' => $persona->n_id_num_empleado,
                        'nombre' => $persona->empleado->nombre,
                        'apellido1' => $persona->empleado->apellido1,
                        'apellido2' => $persona->empleado->apellido2,
                        'instruccion' => $instruccion
                    ];
                }),
            ];
        });

        return response()->json($gruposTransformados);
    }

    public function editarGrupo($idGrupo, Request $request)
    {
        $user = $this->hasPermission($request);
        if (empty($user->idEmpleado)) {
            return response()->json(['message' => $user->error_msj], 400);
        }

        $empleadoPuesto = EmpleadoPuesto::where('n_id_num_empleado', $user->idEmpleado)->first();

        if (!$empleadoPuesto) {
            return response()->json(['message' => 'Empleado no encontrado ' . $user->idEmpleado], 404);
        }
        $idArea = $empleadoPuesto->n_id_cat_area;
        // Validar la solicitud
        $validatedData = $request->validate([
            'nombreGrupo' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tab_doc_grupos_firmas', 's_nombre_gpo_firmante')
                    ->where('n_id_cat_area', $idArea)
                    ->ignore($request->input('idGrupo')), // Asume que 'idGrupo' es el ID del grupo que se está editando
            ],
            'personas' => 'required|array',
            'personas.*.idEmpleado' => 'required|integer',
            'personas.*.idInstFirmante' => 'nullable|integer',
            'personas.*.idInstDest' => 'nullable|integer'
        ]);

        try {
            DB::beginTransaction();

            // Buscar y actualizar el grupo
            $grupo = GrupoFirma::findOrFail($idGrupo);
            $grupo->n_id_cat_area = $idArea;
            $grupo->s_nombre_gpo_firmante = $validatedData['nombreGrupo'];
            $grupo->save();

            // Actualizar o añadir personas
            foreach ($validatedData['personas'] as $personaData) {
                GrupoFirmaPersonas::updateOrCreate(
                    ['n_id_grupo_personas' => $idGrupo,
                     'n_id_num_empleado' => $personaData['idEmpleado']],
                    [
                        'n_id_inst_firmante' => $personaData['idInstFirmante'],
                        'n_id_inst_destinatario' => $personaData['idInstDest']
                    ]
                );
            }

            DB::commit();
            return response()->json(['message' => 'Grupo y personas actualizados con éxito']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function eliminarGrupo($idGrupo, Request $request)
    {
        $user = $this->hasPermission($request);
        if (empty($user->idEmpleado)) {
            return response()->json(['message' => $user->error_msj], 400);
        }

        try {
            DB::beginTransaction();

            // Eliminar personas asociadas al grupo
            GrupoFirmaPersonas::where('n_id_grupo_personas', $idGrupo)->delete();

            // Eliminar el grupo
            $grupo = GrupoFirma::findOrFail($idGrupo);
            $grupo->delete();

            DB::commit();
            return response()->json(['message' => 'Grupo y personas eliminados con éxito']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
