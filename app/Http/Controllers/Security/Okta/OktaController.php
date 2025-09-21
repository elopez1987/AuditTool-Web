<?php

namespace App\Http\Controllers\Security\Okta;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\RequestException;
use App\Models\Configuration\Okta\EdiWsToken;
use App\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\DB;
use Log;

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
                    'Content-Type' => 'application/json',
                ],
            ]);
            $body = $response->getBody()->getContents();
            return json_decode($body, true);
        } catch (RequestException $e) {
            echo "HTTP Request failed:\n";
            echo $e->getRequest();
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }
        }
    }

    public function createOktaUser($id)
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

        $user = User::where('id', $id)->first();
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
            $data = json_decode((string) $response->getBody(), true);
            $user->auth0 = $data['user_id'];
            $user->save();
            return $this->redirectBackSuccessMessage('Usuario creado con exito.');
        } catch (ClientException $e) {
            Log::error($e->getMessage());
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
        DB::beginTransaction();
        try {
            $client->delete($datawsokta->baseuri . 'api/v2/users/' . $userId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token['access_token'],
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);
            $user->statusid = 'D';
            $user->auth0 = '';
            $user->save();
            DB::commit();
            return $this->redirectBackSuccessMessage('Usuario eliminado con exito.');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return $this->errorResponse('Error al eliminar el usuario, intente mas tarde.');
        }
    }
}
