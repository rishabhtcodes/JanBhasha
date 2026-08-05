<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset Your JanBhasha Password')
            ->view('emails.reset-password', [
                'user'     => $notifiable,
                'resetUrl' => $resetUrl,
                'count'    => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'),
            ]);
    }
}
