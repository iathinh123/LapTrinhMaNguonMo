<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OtpMatKhauMail extends Mailable
{
    public function __construct(public string $otp, public int $soPhut = 5) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Mã OTP đặt lại mật khẩu - '.config('app.name'));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp');
    }
}
