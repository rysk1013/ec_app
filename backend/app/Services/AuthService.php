<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Constants\AuthConstants;
use App\Models\User;

class AuthService
{
    /**
     * Create User
     *
     * @param array $request
     * @return void
     */
    public function storeUser($request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'is_admin' => AuthConstants::USER,
            ]);

            DB::commit();

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();

            logError($e->getMessage(), [
                'Error Code' => $e->getCode(),
                'Stack Trace' => $e->getTraceAsString(),
                'Error in' => $e->getFile(),
                'Line' => $e->getLine(),
            ]);
        }
    }
}
