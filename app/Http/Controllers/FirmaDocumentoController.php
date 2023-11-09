<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FirmaDocumento;
use App\FirmaDocumentoDTO;
use GuzzleHttp\Client;

class FirmaDocumentoController extends Controller
{

    public function subirDocumento(Request $request)
    {
        // Verificar los datos enviados por el usuario
        $hasData = FirmaDocumento::validateDataToUpload($request);
        if($hasData!=null){
            return $hasData;
        }

        $dataFirma = new FirmaDocumentoDTO();

        // preparar los datos para el request al API Firma
        $archivo = $request->file('documento');
        $dataFirma->nombre = $archivo->getClientOriginalName();
        $dataFirma->documentoBase64 = FirmaDocumento::getBase64($archivo);

        // Realizar más acciones según tus necesidades
        $client = new Client();
        $url = 'http://localhost:8080/api/documentos/upload'; // Cambia por la URL correcta
        /*$data = [
            'key' => 'value',
            'another_key' => 'another_value',
        ];*/

        $response = $client->post($url, [
            'json' => $dataFirma,
        ]);

        return json_decode($response->getBody(), true);
    }

    
    public function firmaDocumentoExistente(Request $request)
    {
        // Verificar los datos enviados por el usuario
        $hasData = FirmaDocumento::validateDataDocExistente($request);
        if($hasData!=null){
            return $hasData;
        }

        $dataFirma = new FirmaDocumentoDTO();

        // preparar los datos para el request al API Firma
        $dataFirma->llaveBase64 = FirmaDocumento::getBase64($request->file('archivo_key'));
        $dataFirma->certificadoBase64 = FirmaDocumento::getBase64($request->file('archivo_cer'));
        $dataFirma->contrasena = $request->input('contrasena');
        $dataFirma->documentoId = $request->input('documentoId');
        

        // Realizar más acciones según tus necesidades
        $client = new Client();
        $url = 'http://localhost:8080/api/firmas/firmar-documento-existente'; // Cambia por la URL correcta
        /*$data = [
            'key' => 'value',
            'another_key' => 'another_value',
        ];*/

        $response = $client->post($url, [
            'json' => $dataFirma,
        ]);

        return json_decode($response->getBody(), true);
    }


    public function firmaDocumento(Request $request)
    {
        // Verificar los datos enviados por el usuario
        $hasData = FirmaDocumento::validateData($request);
        if($hasData!=null){
            return $hasData;
        }

        $dataFirma = new FirmaDocumentoDTO();

        // preparar los datos para el request al API Firma
        $archivo = $request->file('documento');
        $dataFirma->nombre = $archivo->getClientOriginalName();
        $dataFirma->documentoBase64 = FirmaDocumento::getBase64($archivo);
        $dataFirma->llaveBase64 = FirmaDocumento::getBase64($request->file('archivo_key'));
        $dataFirma->certificadoBase64 = FirmaDocumento::getBase64($request->file('archivo_cer'));
        $dataFirma->contrasena = $request->input('contrasena');

        // Realizar más acciones según tus necesidades
        $client = new Client();
        $url = 'http://localhost:8080/api/firmas/firmar-documento'; // Cambia por la URL correcta
        /*$data = [
            'key' => 'value',
            'another_key' => 'another_value',
        ];*/

        $response = $client->post($url, [
            'json' => $dataFirma,
        ]);

        return json_decode($response->getBody(), true);
    }
}
