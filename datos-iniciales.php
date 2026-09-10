<?php
session_start();
header('Content-Type: application/json');

$data = [
    'recursosIniciales' => [
        'piedra' => $_SESSION['piedra'] ?? 0,
        'metal'  => $_SESSION['metal'] ?? 0,
        'madera'=> $_SESSION['madera'] ?? 0,
        'comida'=> $_SESSION['comida'] ?? 0,
        'oro'   => $_SESSION['oro'] ?? 0,
        'mana'  => $_SESSION['mana'] ?? 0,
    ],
    'produccionPorHora' => [
        'piedra' => $_SESSION['produccion_piedra_hora'] ?? 0,
        'metal'  => $_SESSION['produccion_metal_hora'] ?? 0,
        'madera'=> $_SESSION['produccion_madera_hora'] ?? 0,
        'comida'=> $_SESSION['produccion_comida_hora'] ?? 0,
        'oro'   => $_SESSION['produccion_oro_hora'] ?? 0,
        'mana'  => $_SESSION['produccion_mana_hora'] ?? 0,
    ],
    'fechaUltimaActualizacion' => $_SESSION['fecha_actualizado'] ?? 0,
];

echo json_encode($data);
?>
