<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use App\Mail\VerifikasiEmail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Batas maksimal pengaduan baru yang boleh diajukan satu mahasiswa per hari.
     * Cukup longgar untuk kasus wajar, tapi mencegah spam/klik berulang tidak sengaja.
     */
    const MAX_PENGADUAN_PER_HARI = 5;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Registered::class, SendEmailVerificationNotification::class);

        // Ganti email verifikasi default Laravel dengan template kustom SILPM
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new VerifikasiEmail(
                userName:        $notifiable->name,
                verificationUrl: $url,
            ))->to($notifiable->email, $notifiable->name);
        });

        RateLimiter::for('pengaduan-submit', function (Request $request) {
            return Limit::perDay(self::MAX_PENGADUAN_PER_HARI)
                ->by($request->user()->id)
                ->response(function (Request $request, array $headers) {
                    return redirect()
                        ->route('mahasiswa.pengaduan.create')
                        ->withHeaders($headers)
                        ->with('error', 'Anda sudah mencapai batas ' . self::MAX_PENGADUAN_PER_HARI . ' pengaduan untuk hari ini. Silakan coba lagi besok.');
                });
        });
    }
}
