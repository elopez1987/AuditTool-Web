<?php

namespace App\Http\Controllers\Security\Okta;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Okta\EdiWsToken;
use App\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\DB;
use Log;

class OktaController extends BaseController
{
    private ?EdiWsToken $oktaConfig = null;

    private ?array $cachedToken = null;

    private function getOktaConfig(): EdiWsToken
    {
        if ($this->oktaConfig === null) {
            $this->oktaConfig = EdiWsToken::where('servicetype', 'OKTA')->first();
        }

        return $this->oktaConfig;
    }

    public function obtener_token(): array
    {
        if ($this->cachedToken !== null) {
            return $this->cachedToken;
        }

        $config = $this->getOktaConfig();

        try {
            $client = new Client([
                'base_uri' => $config->baseuri,
                'timeout' => $config->timeout ?? 30.0,
            ]);

            $response = $client->request('POST', '/oauth/token', [
                'json' => [
                    'client_id' => $config->client_id,
                    'client_secret' => $config->client_secret,
                    'audience' => $config->baseuri.'api/v2/',
                    'grant_type' => 'client_credentials',
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            $this->cachedToken = json_decode($response->getBody()->getContents(), true);

            return $this->cachedToken;
        } catch (ClientException $e) {
            Log::error('Okta token request failed: '.$e->getMessage());
            throw $e;
        }

    }

    public function createOktaUser($id)
    {
        $user = User::where('id', $id)->first();

        if (! $user) {
            return $this->errorResponse('El usuario no existe.');
        }

        if (! empty($user->auth0)) {
            return $this->errorResponse('El usuario ya se encuentra registrado en Okta.');
        }

        $token = $this->obtener_token();
        $config = $this->getOktaConfig();

        DB::beginTransaction();
        try {
            $client = new Client([
                'base_uri' => $config->baseuri,
                'timeout' => $config->timeout ?? 30.0,
            ]);

            $response = $client->request('POST', 'api/v2/users', [
                'headers' => $this->buildAuthHeaders($token),
                'json' => [
                    'email' => $user->email,
                    'name' => $user->name,
                    'nickname' => $user->username,
                    'connection' => $config->audience,
                    'password' => 'Temporal12##',
                    'verify_email' => true,
                    'username' => $user->username,
                ],
            ]);

            if ($response->getStatusCode() == 201) {
                $data = json_decode((string) $response->getBody(), true);
                $user->auth0 = $data['user_id'];
                $user->save();

                DB::commit();

                return $this->redirectBackSuccessMessage('Usuario creado con exito.');
            }

            DB::rollBack();
            Log::warning('Okta user creation returned status: '.$response->getStatusCode());

            return $this->errorResponse('Error al crear el usuario, intente mas tarde.');
        } catch (ClientException $e) {
            DB::rollBack();
            Log::error('Okta user creation failed: '.$e->getMessage());

            return $this->errorResponse('Error al crear el usuario en Okta.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Okta user creation failed: '.$e->getMessage());

            return $this->errorResponse('Error al crear el usuario, intente mas tarde.');
        }
    }

    private function buildAuthHeaders(array $token): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ];
    }

    public function deleteuser($id)
    {
        $user = User::where('id', $id)->first();

        if (! $user) {
            return $this->errorResponse('El usuario no existe.');
        }

        if (empty($user->auth0)) {
            return $this->errorResponse('El usuario no tiene cuenta de Okta asociada.');
        }

        $token = $this->obtener_token();
        $config = $this->getOktaConfig();

        DB::beginTransaction();
        try {
            $client = new Client([
                'base_uri' => $config->baseuri,
                'timeout' => $config->timeout ?? 30.0,
            ]);

            $client->delete('api/v2/users/'.$user->auth0, [
                'headers' => $this->buildAuthHeaders($token),
            ]);

            $user->statusid = 'D';
            $user->auth0 = null;
            $user->save();
            DB::commit();

            return $this->redirectBackSuccessMessage('Usuario eliminado con exito.');
        } catch (ClientException $e) {
            DB::rollBack();
            Log::error('Okta user deletion failed: '.$e->getMessage());

            return $this->errorResponse('Error al eliminar el usuario en Okta.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Okta user deletion failed: '.$e->getMessage());

            return $this->errorResponse('Error al eliminar el usuario, intente mas tarde.');
        }
    }
}
