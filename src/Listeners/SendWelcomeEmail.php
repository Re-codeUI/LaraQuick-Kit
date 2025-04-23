<?php

namespace MagicBox\LaraQuickKit\Listeners;

use MagicBox\LaraQuickKit\Events\UserRegistered;
use MagicBox\LaraQuickKit\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Exception;

class SendWelcomeEmail
{
    /**
     * Handle the event.
     *
     * @param  \MagicBox\LaraQuickKit\Events\UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $user = $event->user;

        // Kirim Email jika diaktifkan
        if (Config::get('laraquick.notifications.email.enabled', false)) {
            $this->sendEmail($user);
        }

        // Kirim WhatsApp jika diaktifkan
        if (Config::get('laraquick.notifications.whatsapp.enabled', false)) {
            $this->sendWhatsAppNotification($user);
        }

        // Kirim SMS jika diaktifkan
        if (Config::get('laraquick.notifications.sms.enabled', false)) {
            $this->sendSmsNotification($user);
        }
    }

    /**
     * Kirim Email Selamat Datang
     */
    protected function sendEmail($user)
    {
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
            Log::info("Email selamat datang berhasil dikirim ke {$user->email}");
        } catch (Exception $e) {
            Log::error("Gagal mengirim email ke {$user->email}: " . $e->getMessage());
        }
    }

    /**
     * Kirim Notifikasi WhatsApp
     */
    protected function sendWhatsAppNotification($user)
    {
        try {
            // Implementasikan API WhatsApp yang digunakan (Twilio, Meta API, dll.)
            Log::info("WhatsApp selamat datang dikirim ke {$user->phone}");
        } catch (Exception $e) {
            Log::error("Gagal mengirim WhatsApp ke {$user->phone}: " . $e->getMessage());
        }
    }

    /**
     * Kirim Notifikasi SMS
     */
    protected function sendSmsNotification($user)
    {
        try {
            // Implementasikan API SMS yang digunakan (Twilio, Vonage, dll.)
            Log::info("SMS selamat datang dikirim ke {$user->phone}");
        } catch (Exception $e) {
            Log::error("Gagal mengirim SMS ke {$user->phone}: " . $e->getMessage());
        }
    }
}
