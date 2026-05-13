<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\RecoveryPasswordCode;
use App\Models\User;
use Keygen\Keygen;

class VerificationRepository extends Repository
{
    public static function model()
    {
        return RecoveryPasswordCode::class;
    }

    public static function storeByRequest(User $user): RecoveryPasswordCode
    {
        return self::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'code' => random_int(100000, 999999),
        ]);
    }

    public static function updateByRequest(RecoveryPasswordCode $recoveryPasswordCode): RecoveryPasswordCode
    {
        $token = Keygen::token(64)->generate();
        self::update($recoveryPasswordCode, [
            'token' => $token
        ]);

        return $recoveryPasswordCode;
    }
}
