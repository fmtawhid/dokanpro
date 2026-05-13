<?php

namespace App\Listeners;

use App\Events\MailSendEvent;
use App\Mail\SendMail;
use App\Mail\SignUpMailSend;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class MailSendListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MailSendEvent $event): void
    {
        if (
            config('app.mail_mailer') && config('app.mail_host') && config('app.mail_port')
            && config('app.mail_username') && config('app.mail_password') && config('app.mail_encryption')
        ) {

            if ($event->type == 'signup') {
                Mail::to($event->user['email'])->send(new SignUpMailSend($event->user, $event->code));
            } else {
                Mail::to($event->user['email'])->send(new SendMail($event->user, $event->code));
            }
        }
    }
}
