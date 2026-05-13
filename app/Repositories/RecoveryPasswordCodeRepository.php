<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\RecoveryPasswordCode;
use App\Models\User;

class RecoveryPasswordCodeRepository extends Repository
{
    public static function model()
    {
        return RecoveryPasswordCode::class;
    }

    public static function storeUpByRequest(User $user): RecoveryPasswordCode
    {
        return self::create([
            'user_id' => $user->id,
            'code' => random_int(100000, 999999),
            'status' => 'Pending',
        ]);
    }

    public static function updateByRequest(RecoveryPasswordCode $recoveryPasswordCode): RecoveryPasswordCode
    {
        self::update($recoveryPasswordCode, [
            'status' => 'Approved'
        ]);

        return $recoveryPasswordCode;
    }
}
