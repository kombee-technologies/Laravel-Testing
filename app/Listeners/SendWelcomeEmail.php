<?php

namespace App\Listeners;

// namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail {
    public function handle(UserRegistered $event) {
        Mail::to($event->user->email)->send(new WelcomeMail($event->user));
    }
}
