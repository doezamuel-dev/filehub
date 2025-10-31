<?php

$publicIndexPath = __DIR__ . '/public/index.php';

if (!file_exists($publicIndexPath)) {
    http_response_code(500);
    echo 'Application bootstrap file not found.';
    exit;
}

require $publicIndexPath;
