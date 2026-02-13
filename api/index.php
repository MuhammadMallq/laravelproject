<?php
/**
 * Vercel serverless function entry point.
 * Creates required /tmp directories for Laravel on serverless.
 */

// Ensure /tmp directories exist for serverless environment
$tmpDirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/framework/views',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

require __DIR__.'/../public/index.php';
