<?php
function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception(".env file not found");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse KEY=VALUE
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Remove quotes if exists
        $value = trim($value, '"\'');

        // Set to environment
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

// Load .env file
loadEnv(__DIR__ . '/.env');

$servername = $_ENV["DB_SERVERNAME"];
$username = $_ENV["DB_USERNAME"];
$password = $_ENV["DB_PASSWORD"];
$dbname = $_ENV["DB_NAME"];
$port = $_ENV["DB_PORT"];

// Koneksi
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Cek Koneksi
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
