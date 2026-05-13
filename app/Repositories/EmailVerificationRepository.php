<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Events\MailSendEvent;
use App\Models\EmailVerification;
use App\Models\User;
use Keygen\Keygen;
use Str;

class EmailVerificationRepository extends Repository
{
    public static function model()
    {
        return EmailVerification::class;
    }

    public static function sendMailByUser(User $user): EmailVerification
    {
        $varificationCode = self::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
        ]);
        MailSendEvent::dispatch($user, $varificationCode, 'signup');

        return $varificationCode;
    }

    public static function updateByRequest(EmailVerification $emailVerification): EmailVerification
    {
        $token = Keygen::token(64)->generate();
        self::update($emailVerification, [
            'token' => $token
        ]);

        return $emailVerification;
    }
}
