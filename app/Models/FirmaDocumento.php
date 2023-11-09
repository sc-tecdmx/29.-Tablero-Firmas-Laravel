<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirmaDocumento extends Model
{
    use HasFactory;

    
    public static function validateDataDocExistente(Request $request)
    {
        if (!$request->hasFile('archivo_key')) {
            return response()->json(['error' => 'No se proporcionó un archivo archivo_key'], 400);
        }else if (!$request->hasFile('archivo_cer')) {
            return response()->json(['error' => 'No se proporcionó un archivo archivo_cer'], 400);
        }else if (!$request->has('contrasena')) {
            return response()->json(['error' => 'Campo contrasena no proporcionado'], 400);
        }else if (!$request->has('documentoId')) {
            return response()->json(['error' => 'Campo documentoId no proporcionado'], 400);
        }
        
        return null;
    }


    public static function validateData(Request $request)
    {
        if (!$request->hasFile('documento')) {
            return response()->json(['error' => 'No se proporcionó un documento'], 400);
        }else if (!$request->hasFile('archivo_key')) {
            return response()->json(['error' => 'No se proporcionó un archivo archivo_key'], 400);
        }else if (!$request->hasFile('archivo_cer')) {
            return response()->json(['error' => 'No se proporcionó un archivo archivo_cer'], 400);
        }else if (!$request->has('contrasena')) {
            return response()->json(['error' => 'Campo contrasena no proporcionado'], 400);
        }
        return null;
    }

    public static function validateDataToUpload(Request $request)
    {
        if (!$request->hasFile('documento')) {
            return response()->json(['error' => 'No se proporcionó un documento'], 400);
        }
        
        return null;
    }

    public static function getBase64($file){
        return base64_encode(file_get_contents($file->path()));
    }
}
