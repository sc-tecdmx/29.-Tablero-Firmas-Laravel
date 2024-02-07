<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public $APP_SEGURIDAD;
    public $APP_ENV;
    public function __construct()
    {
        $this->APP_SEGURIDAD = config('services.seguridad.url');
        $this->APP_ENV = config('services.env.config');
    }
    public function hasPermission(Request $request)
    {
        $user = new \stdClass();
        $user->idEmpleado = null;
        $user->idUsuario = null;
        $user->error_msj = null;
        $user->token = null;

        $header_name = $this->APP_ENV == 'prod' ? 'bearertoken' : 'Authorization';
        $token = $request->header($header_name);
        if (!empty($token)) {
            $header_request_name = $this->APP_ENV == 'prod' ? 'Bearer ' : '';
            $user->token = $header_request_name . $token;

            $response = Http::withHeaders([
                'Authorization' => $user->token,
            ])->post($this->APP_SEGURIDAD . '/api/seguridad/userinfo');
            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']) && isset($data['data']['idEmpleado'])) {
                    $user->idEmpleado = $data['data']['idEmpleado'];
                    $user->idUsuario = $data['data']['idUsuario'];
                } else {
                    $user->error_msj = 'idEmpleado no está presente en la respuesta';
                }
            } else {
                $user->error_msj = 'Error al comunicarse con el servicio de userinfo: ' . $response->status();
            }
        }
        return $user;
    }
}
