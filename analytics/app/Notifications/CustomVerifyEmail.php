<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Config;


class CustomVerifyEmail extends VerifyEmail
{

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        $adminAddress = Config::get('mail.from.address');

        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $verificationUrl)
            ->bcc($adminAddress)
            ->line('If you did not create an account, no further action is required.');
    }
}
