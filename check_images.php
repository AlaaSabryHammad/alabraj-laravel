<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $images = \App\Models\ProjectImage::with('project')->get();
    echo 'Total project images: ' . $images->count() . PHP_EOL;

    foreach ($images as $image) {
        echo 'ID: ' . $image->id . ', Project: ' . $image->project->name . ', Path: ' . $image->image_path . PHP_EOL;

        // Check if file exists
        $fullPath = storage_path('app/public/' . $image->image_path);
        $exists = file_exists($fullPath) ? 'EXISTS' : 'MISSING';
        echo 'File status: ' . $exists . ' at ' . $fullPath . PHP_EOL;
        echo '---' . PHP_EOL;
    }

    if ($images->count() === 0) {
        echo 'No images found. Let\'s create a test image...' . PHP_EOL;

        $project = \App\Models\Project::first();
        if ($project) {
            // Copy our test image to the proper location
            $testImageSource = base_path('test_image.png');
            $targetDir = storage_path('app/public/projects/' . $project->id . '/images');
            $targetFile = $targetDir . '/test_image.png';

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
                echo 'Created directory: ' . $targetDir . PHP_EOL;
            }

            if (file_exists($testImageSource)) {
                copy($testImageSource, $targetFile);
                echo 'Copied test image to: ' . $targetFile . PHP_EOL;

                $testImage = $project->projectImages()->create([
                    'image_path' => 'projects/' . $project->id . '/images/test_image.png',
                    'alt_text' => 'صورة اختبار'
                ]);
                echo 'Test image record created with ID: ' . $testImage->id . PHP_EOL;
            } else {
                echo 'Test image source not found at: ' . $testImageSource . PHP_EOL;
            }
        }
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    echo 'Stack trace: ' . $e->getTraceAsString() . PHP_EOL;
}
