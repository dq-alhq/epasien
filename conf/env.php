<?php

function loadEnv($path)
{
    if (!file_exists($path)) {
        return false;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);

        $_ENV[trim($key)] = trim($value);
    }

    return true;
}

function env($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

// auto load .env dari root project
loadEnv(dirname(__DIR__) . '/.env');
