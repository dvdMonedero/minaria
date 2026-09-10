<?php
// recursos.php – Cálculo dinámico de recursos
// Uso: require_once __DIR__ . '/recursos.php';

/**
 * Obtiene los recursos actuales de un usuario a partir del snapshot almacenado.
 *
 * @param int $idusuario ID del usuario.
 * @param PDO $conexion  Conexión PDO.
 * @return array Array asociativo con recursos y timestamp.
 */
function getUserResources(int $idusuario, PDO $conexion): array {
    $stmt = $conexion->prepare(
        "SELECT piedra, metal, madera, comida, oro, mana,
                produccion_piedra_hora, produccion_metal_hora,
                produccion_madera_hora, produccion_comida_hora,
                produccion_oro_hora, produccion_mana_hora,
                COALESCE(fecha_actualizado, 0) AS ts
         FROM aa_usuario
         WHERE idusuario = :id"
    );
    $stmt->execute([':id' => $idusuario]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return [];
    }
    $now = time();
    $elapsed = $now - (int)$row['ts'];
    $calc = function(float $base, float $prod) use ($elapsed): float {
        return $base + ($prod / 3600.0) * $elapsed;
    };
    return [
        'piedra'  => $calc((float)$row['piedra'],  (float)$row['produccion_piedra_hora']),
        'metal'   => $calc((float)$row['metal'],   (float)$row['produccion_metal_hora']),
        'madera'  => $calc((float)$row['madera'],  (float)$row['produccion_madera_hora']),
        'comida'  => $calc((float)$row['comida'],  (float)$row['produccion_comida_hora']),
        'oro'     => $calc((float)$row['oro'],     (float)$row['produccion_oro_hora']),
        'mana'    => $calc((float)$row['mana'],    (float)$row['produccion_mana_hora']),
        'ultimo_actualizacion' => $now,
    ];
}

/**
 * Persiste los recursos calculados en la tabla aa_usuario y actualiza el timestamp.
 *
 * @param int $idusuario
 * @param PDO $conexion
 * @param array $resources Array devuelto por getUserResources (sin redondear).
 */
function persistUserResources(int $idusuario, PDO $conexion, array $resources): void {
    $stmt = $conexion->prepare(
        "UPDATE aa_usuario SET
            piedra = :piedra,
            metal = :metal,
            madera = :madera,
            comida = :comida,
            oro = :oro,
            mana = :mana,
            fecha_actualizado = :ts
         WHERE idusuario = :id"
    );
    $stmt->execute([
        ':piedra' => $resources['piedra'],
        ':metal'  => $resources['metal'],
        ':madera' => $resources['madera'],
        ':comida' => $resources['comida'],
        ':oro'    => $resources['oro'],
        ':mana'   => $resources['mana'],
        ':ts'     => $resources['ultimo_actualizacion'],
        ':id'     => $idusuario,
    ]);
}
?>
