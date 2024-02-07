<?php

use App\Http\Controllers\GruposController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\FirmaDocumentoController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\CatalogoConsultarController;
use App\Http\Controllers\CatalogoCrearController;
use App\Http\Controllers\CatalogoEditarController;
use App\Http\Controllers\CatalogoEliminarController;
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

Route::get('get-catalogo/{catalogo}', [CatalogoConsultarController::class, 'getCatalogo']);

Route::post('agregar-item-catalogo/{catalogo}', [CatalogoCrearController::class, 'agregarItemCatalogo']);

Route::put('editar-item-catalogo/{catalogo}/{id}', [CatalogoEditarController::class, 'editarItemCatalogo']);

Route::delete('eliminar-item-catalogo/{catalogo}/{id}', [CatalogoEliminarController::class, 'eliminarItemCatalogo']);

Route::get('get-catalogo-pantalla/{pantalla}', [CatalogoConsultarController::class, 'getCatalogoPantalla']);

// Grupos
Route::post('grupos/agregar-grupo', [GruposController::class, 'crearGrupo']);

Route::get('grupos/{tipoGrupo}', [GruposController::class, 'consultarGrupos']);

Route::put('grupos/editar-grupo/{idGrupo}', [GruposController::class, 'editarGrupo']);

Route::delete('grupos/eliminar-grupo/{idGrupo}', [GruposController::class, 'eliminarGrupo']);

//Documentos

Route::get('documentos-usuario/{userId}', [DocumentosController::class, 'getDocumentsByUser']);

Route::get('documento/{documentoId}', [DocumentosController::class, 'getDocumentsByDocumentId']);

Route::get('busqueda-general', [DocumentosController::class, 'getDocumentsByQuery']);

//autocompletado
Route::get('/autocompletado', [CatalogoConsultarController::class, 'autocompletado']);

Route::get('/test', function () {
    return response()->json(['message' => 'Test route works']);
//no agregar ninguna ruta despues de este , por que no se ejecutará  **
});
