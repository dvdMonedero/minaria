<?php
// Load environment variables from .env if present
if (file_exists(__DIR__ . '/.env')) {
    $envLines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        // Skip comments and empty lines
        $trim = trim($line);
        if ($trim === '' || strpos($trim, '#') === 0) {
            continue;
        }
        // Set into environment
        putenv($trim);
        $kv = explode('=', $trim, 2);
        if (count($kv) === 2) {
            $_ENV[$kv[0]] = $kv[1];
        }
    }
}

// Retrieve DB connection parameters from environment
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo = $conexion;
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

function getPDO(): PDO {
    global $pdo;
    return $pdo;
}
?>
