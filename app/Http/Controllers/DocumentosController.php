<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documentos;



class DocumentosController extends Controller
{
    public function getDocumentsByUser($userId)
    {
        $userDocuments = Documentos::where('user_id', $userId)->get();

        return response()->json($userDocuments);
    }

    public function getDocumentsByDocumentId($documentoId)
    {
        // Utilizando 'with' para precargar las relaciones y encontrar el documento por su clave primaria
        $document = Documentos::with([
            'destino',
            'tipoDocumento',
            'empleado',
            'expediente',
            'prioridad',
            'firmantes.empleado',
            'destinatarios.empleado',
            'firmantes.empleadoPuesto.area',
            'firmantes.empleadoPuesto.puesto',
            'destinatarios.empleadoPuesto.area',
            'destinatarios.empleadoPuesto.puesto',
            'documentosAdjuntos'
        ])->find($documentoId);

        if ($document) {

            // Transformar los datos de firmantes
            $transformedFirmantes = $document->firmantes->map(function ($firmante) {
                return [
                    'numEmpleado' => $firmante->empleado->n_id_num_empleado,
                    'nombre' => $firmante->empleado->nombre,
                    'apellido1' => $firmante->empleado->apellido1,
                    'apellido2' => $firmante->empleado->apellido2,
                    'area'=> optional($firmante->empleadoPuesto->area)->s_desc_area,
                    'puesto' => optional($firmante->empleadoPuesto->puesto)->s_desc_nombramiento,
                ];
            });

            // Transformar los datos de destinatarios
            $transformedDestinatarios = $document->destinatarios->map(function ($destinatario) {
                return [
                    'numEmpleado' => $destinatario->empleado->n_id_num_empleado,
                    'nombre' => $destinatario->empleado->nombre,
                    'apellido1' => $destinatario->empleado->apellido1,
                    'apellido2' => $destinatario->empleado->apellido2,
                    'area'=> optional($destinatario->empleadoPuesto->area)->s_desc_area,
                    'puesto' => optional($destinatario->empleadoPuesto->puesto)->s_desc_nombramiento,
                ];
            });

            $transformedDocumentosAdjuntos = $document->documentosAdjuntos->map(function ($adjunto) {
                // Aquí simplemente estamos extrayendo el documento_path, pero puedes ajustarlo como necesites
                return [
                    'documentoPath' => $adjunto->documento_path
                ];
            });

            // Construir la respuesta
            $renamedDocument = [
                'idDocumento' => $document->n_id_documento,
                'tipoDestino' => $document->destino->desc_destino_documento,
                'tipoDocumento' => $document->tipoDocumento->desc_tipo_documento,
                'folioDocumento'=> $document->folio_documento,
                'folioEspecial'=> $document->folio_especial,
                'fechaCreacion'=> $document->creacion_documento_fecha,
                'idEmpleadoCreador'=> $document->n_id_num_empleado_creador,
                'nombreEmpleado'=> $document->empleado->nombre,
                'apellido1Empleado'=> $document->empleado->apellido1,
                'apellido2Empleado'=> $document->empleado->apellido2,
                'numExpediente'=> $document->expediente->s_num_expediente,
                'expedienteDes'=> $document->expediente->s_descripcion,
                'prioridad'=> $document->prioridad->desc_prioridad,
                'asunto'=> $document->s_asunto,
                'contenido'=> $document->s_contenido,
                'fechaLimiteFirma'=> $document->d_fecha_limite_firma,
                'firmantes'=> $transformedFirmantes,
                'destinatarios'=> $transformedDestinatarios,
                'documentosAdjuntos' => $transformedDocumentosAdjuntos

            ];

            return response()->json($renamedDocument);
        } else {
            // Manejar el caso en que no se encuentre el documento
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }
    }
    //
}
