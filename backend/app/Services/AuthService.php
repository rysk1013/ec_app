<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Auth\RegisterFailedException;
use App\Constants\AuthConstants;
use App\Models\User;

class AuthService
{
    /**
     * Create User
     *
     * @param array $request
     * @return User
     * @throws RegisterFailedException
     */
    public function storeUser(array $request): User
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

            throw new RegisterFailedException();
        }
    }
}
