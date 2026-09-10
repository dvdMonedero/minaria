<?php
require_once 'config.php';
require_once 'funciones.php';

session_start();
$idUsuario = $_SESSION['idusuario'] ?? null;
if (!$idUsuario) {
    header('Location: login.php');
    exit;
}

// Comprobar si ya hay una exploración activa
$pdo = getPDO();
$stmt = $pdo->prepare(
    "SELECT idexploracion FROM aa_exploracion WHERE idusuario = :id AND estado = 'activa' LIMIT 1"
);
$stmt->execute([':id' => $idUsuario]);
if ($stmt->fetch()) {
    // Ya está explorando – redirigimos con mensaje
    header('Location: reino.php?msg=exploracion_activa');
    exit;
}

// Check cooldown: ensure enough time has passed since last finished exploration
    $stmtCooldown = $pdo->prepare(
        "SELECT fecha_fin FROM aa_exploracion WHERE idusuario = :id AND estado = 'finalizada' ORDER BY fecha_fin DESC LIMIT 1"
    );
    $stmtCooldown->execute([':id' => $idUsuario]);
    $lastFin = $stmtCooldown->fetchColumn();
    if ($lastFin) {
        $cooldownSeconds = 60; // 1 minute cooldown
        $readyTime = strtotime($lastFin) + $cooldownSeconds;
        if (time() < $readyTime) {
            // Still in cooldown period
            header('Location: reino.php?msg=exploracion_cooldown');
            exit;
        }
    }

$segundos = calcularTiempoExploracion($pdo, $idUsuario);

// Insertar nueva exploración
$ins = $pdo->prepare(
    "INSERT INTO aa_exploracion (idusuario, fecha_inicio, fecha_fin, estado)
     VALUES (:id, NOW(), DATE_ADD(NOW(), INTERVAL :seg SECOND), 'activa')"
);
$ins->execute([':id' => $idUsuario, ':seg' => $segundos]);

// Resetear flag de recarga en cliente
echo "<script>sessionStorage.removeItem('exploreReloaded'); window.location.href = 'reino.php?msg=exploracion_iniciada';</script>";
exit;
?>
