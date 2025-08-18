<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

use Illuminate\Support\Facades\Mail;

Mail::raw('Mailtrap connection test', function ($m) {
    $m->to('test@example.com')->subject('Mailtrap Test');
});

echo "Sent test email (if SMTP creds valid).\n";
