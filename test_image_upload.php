<?php
echo "<?php\n";
?>
// Test file to debug image upload
// Run this to check if upload works

// Simulate a POST request to test the route
$project_id = 1; // Change to an existing project ID
$url = "http://127.0.0.1:8000/projects/{$project_id}/images";

echo "Testing image upload to: $url\n";

// Check if we can access the route
$headers = get_headers($url);
print_r($headers);

// Create a test multipart form data
$image_data = base64_encode('fake image data for testing');
$boundary = '----formdata-polyfill-' . microtime(true);

$post_data = "--$boundary\r\n";
$post_data .= "Content-Disposition: form-data; name=\"_token\"\r\n\r\n";
$post_data .= "test_token\r\n";
$post_data .= "--$boundary\r\n";
$post_data .= "Content-Disposition: form-data; name=\"images[]\"; filename=\"test.jpg\"\r\n";
$post_data .= "Content-Type: image/jpeg\r\n\r\n";
$post_data .= $image_data . "\r\n";
$post_data .= "--$boundary--\r\n";

$context = stream_context_create([
'http' => [
'method' => 'POST',
'header' => [
"Content-Type: multipart/form-data; boundary=$boundary",
"Content-Length: " . strlen($post_data),
"X-Requested-With: XMLHttpRequest",
"Accept: application/json"
],
'content' => $post_data
]
]);

echo "Sending request...\n";
$result = file_get_contents($url, false, $context);

if ($result === false) {
echo "Failed to send request\n";
print_r(error_get_last());
} else {
echo "Response received:\n";
echo $result . "\n";

// Try to decode as JSON
$json = json_decode($result, true);
if ($json !== null) {
echo "JSON Response:\n";
print_r($json);
} else {
echo "Response is not JSON. HTML response:\n";
echo htmlspecialchars($result);
}
}
?>