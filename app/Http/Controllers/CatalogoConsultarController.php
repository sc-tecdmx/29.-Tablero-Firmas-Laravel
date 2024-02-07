<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\CatExpedientes;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Catalogo;

class CatalogoConsultarController extends Controller
{

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
        if ($catalogo == "aplicacion") {
            $catalogo = Catalogo::getCatAplicaciones();
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
