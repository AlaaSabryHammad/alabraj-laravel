<?php

require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';

try {
    // Get Laravel application instance
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "Testing database connection...\n";

    $projects = \App\Models\Project::select('id', 'name')->take(3)->get();

    echo "Found " . $projects->count() . " projects:\n";

    foreach ($projects as $project) {
        echo "- ID: {$project->id}, Name: {$project->name}\n";
    }

    if ($projects->count() > 0) {
        $firstProject = $projects->first();
        echo "\nTesting route for project {$firstProject->id}:\n";
        echo "URL: http://127.0.0.1:8000/projects/{$firstProject->id}/images\n";

        // Check if project has existing images
        if ($firstProject->images) {
            $images = json_decode($firstProject->images, true);
            echo "Current images: " . count($images) . " images\n";
        } else {
            echo "No images currently stored\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
