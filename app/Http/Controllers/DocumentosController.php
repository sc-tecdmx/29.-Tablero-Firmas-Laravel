<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documentos;

class DocumentosController extends Controller
{
    public function getDocumentsByQuery(Request $request)
    {
        $query = $request->input('query');
        $documentsPaginated = Documentos::search($query)->paginate(10); // 10 documentos por página

        // Transformar cada documento individualmente
        $transformedDocuments = $documentsPaginated->getCollection()->map(function ($document) {
            // Aquí se agregan todas las transformaciones para cada documento
            $transformedConfiguración = $document->docConfiguracion->map(function ($confi) {
                return [
                    'valor' => $confi->configuracion->s_valor
                ];
            });

            $transformedFirmantes = $document->firmantes->map(function ($firmante) {
                return [
                    'numEmpleado' => $firmante->empleado->n_id_num_empleado,
                    'nombre' => $firmante->empleado->nombre,
                    'apellido1' => $firmante->empleado->apellido1,
                    'apellido2' => $firmante->empleado->apellido2,
                    'instruccion' => $firmante->instruccion->desc_instr_firmante,
                    'area' => optional($firmante->empleadoPuesto->area)->s_desc_area,
                    'puesto' => optional($firmante->empleadoPuesto->puesto)->s_desc_nombramiento,
                ];
            });

            $transformedDestinatarios = $document->destinatarios->map(function ($destinatario) {
                return [
                    'numEmpleado' => $destinatario->empleado->n_id_num_empleado,
                    'nombre' => $destinatario->empleado->nombre,
                    'apellido1' => $destinatario->empleado->apellido1,
                    'apellido2' => $destinatario->empleado->apellido2,
                    'instruccion' => $destinatario->instruccion->desc_inst_dest,
                    'area' => optional($destinatario->empleadoPuesto->area)->s_desc_area,
                    'puesto' => optional($destinatario->empleadoPuesto->puesto)->s_desc_nombramiento,
                ];
            });

            $transformedDocumentosAdjuntos = $document->documentosAdjuntos->map(function ($adjunto) {
                return [
                    'documentoPath' => $adjunto->documento_path,
                    'docBase64' => $adjunto->documento_base64,
                    'fileType' => $adjunto->documento_filetype,
                ];
            });
            // Retornar el documento con los campos renombrados
            return [
                'idDocumento' => $document->n_id_documento,
                'tipoDestino' => optional($document->destino)->desc_destino_documento,
                'tipoDocumento' => optional($document->tipoDocumento)->desc_tipo_documento,
                'folioDocumento' => optional($document)->folio_documento,
                'folioEspecial' => optional($document)->folio_especial,
                'idEmpleadoCreador' => optional($document)->n_id_num_empleado_creador,
                'nombreEmpleado' => optional($document)->empleado->nombre,
                'apellido1Empleado' => optional($document->empleado)->apellido1,
                'apellido2Empleado' => optional($document->empleado)->apellido2,
                'numExpediente' => optional($document->expediente)->s_num_expediente,
                'expedienteDes' => optional($document->expediente)->s_descripcion,
                'prioridad' => optional($document->prioridad)->desc_prioridad,
                'asunto' => optional($document)->s_asunto,
                'contenido' => optional($document)->s_contenido,
                'fechaCreacion' => \Carbon\Carbon::parse($document->creacion_documento_fecha)->toDateTimeString(),
                'fechaLimiteFirma' => \Carbon\Carbon::parse($document->d_fecha_limite_firma)->toDateTimeString(),
                'notas' => optional($document)->s_notas,
                'configuracion' => $transformedConfiguración,
                'firmantes' => $transformedFirmantes,
                'destinatarios' => $transformedDestinatarios,
                'documentosAdjuntos' => $transformedDocumentosAdjuntos
            ];
        });

        // Agregar la paginación y la colección transformada a la respuesta JSON
        return response()->json([
            'documents' => $transformedDocuments,
            'pagination' => [
                'total' => $documentsPaginated->total(),
                'perPage' => $documentsPaginated->perPage(),
                'currentPage' => $documentsPaginated->currentPage(),
                'lastPage' => $documentsPaginated->lastPage(),
                'from' => $documentsPaginated->firstItem(),
                'to' => $documentsPaginated->lastItem(),
            ]
        ]);
    }

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
            'firmantes.instruccion',
            'destinatarios.empleado',
            'destinatarios.instruccion',
            'firmantes.empleadoPuesto.area',
            'firmantes.empleadoPuesto.puesto',
            'destinatarios.empleadoPuesto.area',
            'destinatarios.empleadoPuesto.puesto',
            'documentosAdjuntos',
            'docConfiguracion.configuracion'
        ])->find($documentoId);

        if ($document) {
            $transformedConfiguración = $document->docConfiguracion->map(function ($confi) {
                return [
                    'valor' => optional($confi->configuracion)->s_valor
                ];
            });
            // Transformar los datos de firmantes
            $transformedFirmantes = $document->firmantes->map(function ($firmante) {
                return [
                    'numEmpleado' => optional($firmante->empleado)->n_id_num_empleado,
                    'nombre' => optional($firmante->empleado)->nombre,
                    'apellido1' => optional($firmante->empleado)->apellido1,
                    'apellido2' => optional($firmante->empleado)->apellido2,
                    'instruccion' => optional($firmante->instruccion)->desc_instr_firmante,
                    'area' => optional($firmante->empleadoPuesto->area)->s_desc_area,
                    'puesto' => optional($firmante->empleadoPuesto->puesto)->s_desc_nombramiento,
                ];
            });

            // Transformar los datos de destinatarios
            $transformedDestinatarios = $document->destinatarios->map(function ($destinatario) {
                return [
                    'numEmpleado' => optional($destinatario->empleado)->n_id_num_empleado,
                    'nombre' => optional($destinatario->empleado)->nombre,
                    'apellido1' => optional($destinatario->empleado)->apellido1,
                    'apellido2' => optional($destinatario->empleado)->apellido2,
                    'instruccion' => optional($destinatario->instruccion)->desc_inst_dest,
                    'area' => optional($destinatario->empleadoPuesto->area)->s_desc_area,
                    'puesto' => optional($destinatario->empleadoPuesto->puesto)->s_desc_nombramiento,
                ];
            });

            $transformedDocumentosAdjuntos = $document->documentosAdjuntos->map(function ($adjunto) {
                // Aquí simplemente estamos extrayendo el documento_path, pero puedes ajustarlo como necesites
                return [
                    'documentoPath' => optional($adjunto)->documento_path,
                    'docBase64' => optional($adjunto)->documento_base64,
                    'fileType' => optional($adjunto)->documento_filetype,
                ];
            });

            // Construir la respuesta
            $renamedDocument = [
                'idDocumento' => $document->n_id_documento,
                'tipoDestino' => optional($document->destino)->desc_destino_documento,
                'tipoDocumento' => optional($document->tipoDocumento)->desc_tipo_documento,
                'folioDocumento' => optional($document)->folio_documento,
                'folioEspecial' => optional($document)->folio_especial,
                'idEmpleadoCreador' => optional($document)->n_id_num_empleado_creador,
                'nombreEmpleado' => optional($document)->empleado->nombre,
                'apellido1Empleado' => optional($document->empleado)->apellido1,
                'apellido2Empleado' => optional($document->empleado)->apellido2,
                'numExpediente' => optional($document->expediente)->s_num_expediente,
                'expedienteDes' => optional($document->expediente)->s_descripcion,
                'prioridad' => optional($document->prioridad)->desc_prioridad,
                'asunto' => optional($document)->s_asunto,
                'contenido' => optional($document)->s_contenido,
                'fechaCreacion' => \Carbon\Carbon::parse($document->creacion_documento_fecha)->toDateTimeString(),
                'fechaLimiteFirma' => \Carbon\Carbon::parse($document->d_fecha_limite_firma)->toDateTimeString(),
                'notas' => optional($document)->s_notas,
                'configuracion' => $transformedConfiguración,
                'firmantes' => $transformedFirmantes,
                'destinatarios' => $transformedDestinatarios,
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
