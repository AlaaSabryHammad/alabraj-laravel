<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check correspondence 2
$c2 = App\Models\Correspondence::with('replies.user')->find(2);
echo "Correspondence 2:\n";
echo "Replies count: " . $c2->replies->count() . "\n";
foreach($c2->replies as $reply) {
    echo "Reply ID: {$reply->id}, Status: {$reply->status}, Type: " . ($reply->reply_type ?? 'null') . "\n";
}

echo "\n";

// Check correspondence 4
$c4 = App\Models\Correspondence::with('replies.user')->find(4);
echo "Correspondence 4:\n";
echo "Replies count: " . $c4->replies->count() . "\n";
foreach($c4->replies as $reply) {
    echo "Reply ID: {$reply->id}, Status: {$reply->status}, Type: " . ($reply->reply_type ?? 'null') . "\n";
}
