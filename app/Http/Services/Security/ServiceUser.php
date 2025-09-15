<?php

namespace App\Http\Services\Security;

use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessCustomer;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ServiceUser
{

    public function createNewUser($request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'username' => $request['username'],
            'auxiliary01' => $request['auxiliary01'],
            'auxiliary02' => $request['auxiliary02'],
            'auxiliary03' => $request['auxiliary03'],
            'auxiliary04' => $request['auxiliary04'],
            'auxiliary05' => $request['auxiliary05'],
        ]);

        SecurityAccessWarehouse::create([
            'centerid' => $request['centerid'],
            'warehouseid' => $request['warehouseid'],
            'username' => $user->username,
        ]);

        return $user;
    }


    /**
     * Actualiza un usuario con validaciones de seguridad mejoradas
     *
     * @param string $uuiduser
     * @param \Illuminate\Http\Request $request
     * @return User
     * @throws \Exception
     */
    public function updateUser($uuiduser, $request)
    {
        $securityuser = User::where('uuiduser', $uuiduser)->first();
        
        if (!$securityuser) {
            throw new \Exception('Usuario no encontrado');
        }

        try {
            // Actualizar campos básicos
            $securityuser->name = $request['name'];
            $securityuser->email = $request['email'];
            $securityuser->auxiliary01 = $request['auxiliary01'] ?? $securityuser->auxiliary01;
            $securityuser->auxiliary02 = $request['auxiliary02'] ?? $securityuser->auxiliary02;
            $securityuser->auxiliary03 = $request['auxiliary03'] ?? $securityuser->auxiliary03;
            $securityuser->auxiliary04 = $request['auxiliary04'] ?? $securityuser->auxiliary04;
            $securityuser->auxiliary05 = $request['auxiliary05'] ?? $securityuser->auxiliary05;
            
            // Manejar cambio de contraseña de forma segura
            if (!empty($request['password'])) {
                // Verificar que la nueva contraseña sea diferente a la actual
                if (Hash::check($request['password'], $securityuser->password)) {
                    throw new \Exception('La nueva contraseña debe ser diferente a la actual');
                }
                
                $securityuser->password = Hash::make($request['password']);
                $securityuser->password_changed_at = now();
                
                // Log del cambio de contraseña
                Log::info('Contraseña de usuario actualizada por administrador', [
                    'target_user_id' => $securityuser->id,
                    'admin_user_id' => Auth::id(),
                    'ip' => request()->ip(),
                    'timestamp' => now()
                ]);
            }
            
            $securityuser->save();
            
            // Log de actualización de perfil
            Log::info('Perfil de usuario actualizado', [
                'user_id' => $securityuser->id,
                'updated_by' => Auth::id(),
                'fields' => array_keys($request->only([
                    'name', 'email', 'auxiliary01', 'auxiliary02', 
                    'auxiliary03', 'auxiliary04', 'auxiliary05'
                ])),
                'timestamp' => now()
            ]);
            
            return $securityuser;
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario', [
                'user_id' => $securityuser->id,
                'error' => $e->getMessage(),
                'updated_by' => Auth::id()
            ]);
            throw $e;
        }
    }

    public function assignCarrierToUser($request, $centerid)
    {
        AccessCarrier::create([
            'carrierid' => $request['carrierid'],
            'username' => $request['username'],
            'centerid' => $centerid
        ]);
    }


    public function assignCustomerToUser($request, $centerid)
    {
        SecurityAccessCustomer::create([
            'centerid' => $centerid,
            'username' => $request['username'],
            'customerid' => $request['customerid']
        ]);
    }

}
