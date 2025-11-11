<?php

function app_redirect(string $path): void
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host   = $_SERVER['HTTP_HOST']; // includes domain + port automatically
    header("Location: {$scheme}://{$host}/{$path}");
    exit;
}

function generateAppKeyToken()
{
    $timestamp = microtime(true);
    $randomString = bin2hex(random_bytes(16));
    $rawToken = $timestamp . $randomString;
    $token = hash('sha256', $rawToken);
    return $token;
}
