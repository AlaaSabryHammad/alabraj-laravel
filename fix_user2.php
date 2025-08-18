<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user2 = \App\Models\User::find(2);
$generalManagerRole = \DB::table('roles')->where('name', 'general_manager')->first();

echo "قبل التحديث:\n";
echo "role_id: " . ($user2->role_id ?? 'null') . "\n";
echo "isGeneralManager: " . ($user2->isGeneralManager() ? 'نعم' : 'لا') . "\n";

$user2->role_id = $generalManagerRole->id;
$user2->save();

echo "\nبعد التحديث:\n";
echo "role_id: " . ($user2->role_id ?? 'null') . "\n";
echo "isGeneralManager: " . ($user2->fresh()->isGeneralManager() ? 'نعم' : 'لا') . "\n";
