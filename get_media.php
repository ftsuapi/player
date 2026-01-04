<?php
/**
 * Media Scanner API
 * Scans directories for music and video files and returns JSON.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow access from your frontend

$config = [
    'music' => [
        'path' => __DIR__ . '/music',
        'extensions' => ['mp3', 'wav', 'ogg', 'm4a']
    ],
    'video' => [
        'path' => __DIR__ . '/video',
        'extensions' => ['mp4', 'webm', 'ogg', 'mov']
    ]
];

$response = [
    'music' => [],
    'video' => []
];

foreach ($config as $type => $settings) {
    if (is_dir($settings['path'])) {
        $files = scandir($settings['path']);
        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $settings['extensions'])) {
                // Generate a clean title from the filename
                $title = str_replace(['_', '-'], ' ', pathinfo($file, PATHINFO_FILENAME));
                $title = ucwords($title);

                $response[$type][] = [
                    'title' => $title,
                    'file' => $file,
                    'artist' => ucfirst($type) . " File",
                    'type' => ($type === 'music' ? 'audio' : 'video')
                ];
            }
        }
    }
}

echo json_encode($response);
?>

