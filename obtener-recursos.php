<?php

// Encabezados para permitir solicitudes AJAX desde cualquier origen
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluir el archivo de configuración
require_once 'config.php';

session_start();
if (!isset($_SESSION['idusuario'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit;
}
else
	$idusuario = $_SESSION['idusuario'];

try {
    // Consultar los recursos y la producción del usuario
    $sql = "SELECT 
                piedra, produccion_piedra_hora,
                metal, produccion_metal_hora,
                madera, produccion_madera_hora,
                comida, produccion_comida_hora,
                oro, produccion_oro_hora,
                mana, produccion_mana_hora,
                fecha_actualizado
            FROM aa_usuario
            WHERE idusuario = :idusuario";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        echo json_encode(["error" => "Usuario no encontrado"]);
        exit;
    }

    // Obtener los datos del usuario
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Calcular el tiempo transcurrido desde la última actualización
    $fechaActual = time();
    $segundosTranscurridos = $fechaActual - $usuario['fecha_actualizado'];

    // Calcular los recursos actuales
    $recursos = [
        "piedra" => $usuario['piedra'] + ($usuario['produccion_piedra_hora'] * $segundosTranscurridos / 3600),
        "metal" => $usuario['metal'] + ($usuario['produccion_metal_hora'] * $segundosTranscurridos / 3600),
        "madera" => $usuario['madera'] + ($usuario['produccion_madera_hora'] * $segundosTranscurridos / 3600),
        "comida" => $usuario['comida'] + ($usuario['produccion_comida_hora'] * $segundosTranscurridos / 3600),
        "oro" => $usuario['oro'] + ($usuario['produccion_oro_hora'] * $segundosTranscurridos / 3600),
        "mana" => $usuario['mana'] + ($usuario['produccion_mana_hora'] * $segundosTranscurridos / 3600),
    ];

    // Actualizar la fecha de última actualización
    $updateSql = "UPDATE aa_usuario SET 
                    piedra = :piedra, 
                    metal = :metal, 
                    madera = :madera, 
                    comida = :comida, 
                    oro = :oro, 
                    mana = :mana, 
                    fecha_actualizado = :fecha_actualizado
                  WHERE idusuario = :idusuario";
    $updateStmt = $conexion->prepare($updateSql);
    $updateStmt->bindParam(':piedra', $recursos['piedra'], PDO::PARAM_STR);
    $updateStmt->bindParam(':metal', $recursos['metal'], PDO::PARAM_STR);
    $updateStmt->bindParam(':madera', $recursos['madera'], PDO::PARAM_STR);
    $updateStmt->bindParam(':comida', $recursos['comida'], PDO::PARAM_STR);
    $updateStmt->bindParam(':oro', $recursos['oro'], PDO::PARAM_STR);
    $updateStmt->bindParam(':mana', $recursos['mana'], PDO::PARAM_STR);
    $updateStmt->bindParam(':fecha_actualizado', $fechaActual, PDO::PARAM_INT);
    $updateStmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
    $updateStmt->execute();

    // Devolver los recursos actuales en formato JSON
    echo json_encode([
        "piedra" => round($recursos['piedra']),
        "metal" => round($recursos['metal']),
        "madera" => round($recursos['madera']),
        "comida" => round($recursos['comida']),
        "oro" => round($recursos['oro']),
        "mana" => round($recursos['mana']),
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
    exit;
}
?>
