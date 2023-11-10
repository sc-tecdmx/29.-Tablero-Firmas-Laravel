<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\CatExpedientes;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
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
use App\Models\Catalogos\CatTEtapaDoc;
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

        if ($catalogo == "sexo") {
            $nombreItem = $request->get('nombreItem');
            $abreviatura = $request->get('abreviatura');
            $data = [
                'sexo_desc' => $nombreItem,
                'sexo' => $abreviatura
            ];

            $catSexo = CatSexo::findOrFail($id);
            $catSexo->update($data);
            return response()->json(
                [
                    'status' => "OK",
                    'mensaje' => 'Se editó el item satisfactoriamente',
                    'data' => [
                        'id' => $id,
                        'sexo' => $nombreItem,
                        'abreviatura' => $abreviatura,
                    ]
                ]
                ,
                200
            );
        }
    }

    public function agregarItemCatalogo($catalogo, Request $request)
    {
        $token = $request->header('Authorization');
        if (empty($token)) {
            return response()->json(['message' => 'No cuenta con permisos para realizar esta operación'], 400);
        }

        if ($catalogo == "sexo") {
            $nombreItem = $request->get('nombreItem');
            $descripcion = $request->get('descripcion');
            $data = [
                'sexo_desc' => $nombreItem,
                'sexo' => $descripcion
            ];
            $result = CatSexo::create($data);
            return response()->json(
                [
                    'status' => "OK",
                    'mensaje' => 'Se agregó el item satisfactoriamente',
                    'data' => [
                        'id' => $result->id_sexo,
                        'sexo' => $result->sexo_desc,
                        'abreviatura' => $result->sexo,
                    ]
                ]
                ,
                200
            );
        }
        if ($catalogo == "expedientes") {
            $numExpediente = $request->get('numExpediente');
            $descripcion = $request->get('descripcion');
            $data = [
                's_num_expediente' => $numExpediente,
                's_descripcion' => $descripcion,
                'n_id_usuario_creador' => 2
            ];
            $result = CatExpedientes::create($data);
            return response()->json(
                [
                    'status' => "OK",
                    'mensaje' => 'Se agregó el item satisfactoriamente',
                    'data' => [
                        'id' => $result->n_num_expediente,
                        'numExpediente' => $result->s_num_expediente,
                        'descripcion' => $result->s_descripcion,
                    ]
                ]
                ,
                200
            );
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
            $catInstruccion = Catalogo::getCatInstruccion();
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
                        'catInstruccion' => $catInstruccion,
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
        $results = CatExpedientes::where('s_num_expediente', 'like', '%' . $query . '%')->pluck('s_num_expediente');

        return response()->json($results);

    }
}
