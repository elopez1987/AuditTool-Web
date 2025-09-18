<?php

namespace App\Http\Controllers\Security\Okta;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\RequestException;
use App\Models\Configuration\Okta\EdiWsToken;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OktaController extends BaseController
{

//    Obtener el token de conexion a okta
    public function obtener_token()
    {
        $datawsokta = EdiWsToken::where('servicetype', 'OKTA')->first();
        $client = new Client([
            'base_uri' => $datawsokta->baseuri,
            'timeout' => 30.0,
        ]);
        try {
            $response = $client->request('POST', '/oauth/token', [
                'json' => [
                    'client_id' => $datawsokta->client_id,
                    'client_secret' => $datawsokta->client_secret,
                    'audience' => $datawsokta->baseuri . 'api/v2/',
                    'grant_type' => 'client_credentials',
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            return $data;
        } catch (RequestException $e) {
            echo "HTTP Request failed:\n";
            echo $e->getRequest();
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }
        }
    }

    function createOktaUser($id)
    {
        $token = $this->obtener_token();
        $datawsokta = EdiWsToken::where('servicetype', 'OKTA')->first();
        $client = new Client();
        $url = $datawsokta->baseuri . 'api/v2/users';
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token['access_token'],
        ];

        $user = \App\User::where('id', $id)->first();
        $body = json_encode([
            'email' => $user->email,
            'name' => $user->name,
            'nickname' => $user->username,
            'connection' => $datawsokta->audience,
			'password' => 'Temporal12##',
            'verify_email' => true,
            'username' => $user->username,
        ]);
        try {
            $response = $client->request('POST', $url, ['headers' => $headers, 'body' => $body]);
            $data = json_decode((string)$response->getBody(), true);
            $user->auth0 = $data['user_id'];
            $user->save();
            return $this->redirectBackSuccessMessage('Usuario creado con exito.');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $error = $e->getMessage();
            return response()->json(['status' => 'Error de envio.']);
        }
    }


//    Borrar un usuaroi
    public function deleteuser($id)
    {
        $client = new Client();
        $user = User::where('id', $id)->first();
        $userId = $user->auth0;
        $token = $this->obtener_token();
        $datawsokta = EdiWsToken::where('servicetype', 'OKTA')->first();
        try {
            // Enviar la solicitud DELETE con el encabezado de autorización
            $response = $client->delete($datawsokta->baseuri . 'api/v2/users/' . "{$userId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token['access_token'],
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            // Obtener el cuerpo de la respuesta
            $body = $response->getBody();
            $content = $body->getContents();

            $user->statusid = 'D';
            $user->auth0 = '';
            $user->save();
            return $this->redirectBackSuccessMessage('Usuario eliminado con exito.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $this->errorResponse('Error al eliminar el usuario, intente mas tarde.');
        }

    }


    public function consultarusuario()
    {
        $token = $this->obtener_token();
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token['access_token'],
        ];
        $options = [
            'multipart' => [
                [
                    'name' => 'id',
                    'contents' => 'auth0|66e78bdb6f4aef2455d21019'
                ]
            ]];
        $request = new Request('GET', 'https://dev-obb4s5lk3a3zgxzl.us.auth0.com/api/v2/users/auth0|66e78bdb6f4aef2455d21019', $headers);
        $res = $client->sendAsync($request, $options)->wait();
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        \Log::info($data);
    }
}
