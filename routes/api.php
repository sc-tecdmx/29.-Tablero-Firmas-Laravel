<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\FirmaDocumentoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\DocumentosController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('version', [VersionController::class, 'index']);


Route::post('firmar-documento', [FirmaDocumentoController::class, 'firmaDocumento']);

Route::post('firmar-documento-existente', [FirmaDocumentoController::class, 'firmaDocumentoExistente']);

Route::post('upload-documento', [FirmaDocumentoController::class, 'subirDocumento']);

// Catálogos

Route::get('get-catalogo/{catalogo}', [CatalogoController::class, 'getCatalogo']);

Route::post('agregar-item-catalogo/{catalogo}', [CatalogoController::class, 'agregarItemCatalogo']);

Route::put('editar-item-catalogo/{catalogo}/{id}', [CatalogoController::class, 'editarItemCatalogo']);

Route::delete('eliminar-item-catalogo/{catalogo}/{id}', [CatalogoController::class, 'eliminarItemCatalogo']);

Route::get('get-catalogo-pantalla/{pantalla}', [CatalogoController::class, 'getCatalogoPantalla']);

//Documentos

Route::get('documentos-usuario/{userId}', [DocumentosController::class, 'getDocumentsByUser']);

Route::get('documento/{documentoId}', [DocumentosController::class, 'getDocumentsByDocumentId']);

Route::get('busqueda-general', [DocumentosController::class, 'getDocumentsByQuery']);

//autocompletado
Route::get('/autocompletado', [CatalogoController::class, 'autocompletado']);

Route::get('/test', function () {
    return response()->json(['message' => 'Test route works']);
});
