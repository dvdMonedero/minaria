<?php
session_start();
require 'config.php';
require_once __DIR__ . '/recursos.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['username'];
    $password = $_POST['password'];
    // Validar usuario y contraseña
    $query = "SELECT * FROM aa_usuario WHERE email = :email AND password = :password AND confirmado = 1";
    $stmt = $conexion->prepare($query);
    $stmt->execute([
        ':email' => $email,
        ':password' => hash('sha256', $password),
    ]);
	//echo "SELECT idusuario, nombre FROM davidmonwn942.aa_usuario WHERE email = '$email' AND password = '" . hash('sha256', $password) . "' AND confirmado = 1<br>";
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
	//var_dump($user);
    if ($user) {
        // Obtener todas las regiones del usuario
        $queryRegiones = "
							SELECT r.idregion, r.nombre, tr.nombre AS tipo 
							FROM aa_region r
							LEFT JOIN aa_usuario_region ur 
								ON ur.idregion = r.idregion 
							LEFT JOIN aa_tipo_region tr 
								ON tr.idtipo_region = r.idtipo_region 
							WHERE ur.idusuario = :idusuario;
						";
        $stmtRegiones = $conexion->prepare($queryRegiones);
        $stmtRegiones->execute([':idusuario' => $user['idusuario']]);
        $resultados = $stmtRegiones->fetchAll(PDO::FETCH_ASSOC);
		$regiones = [];
		foreach ($resultados as $region) {
			$regiones[$region['idregion']] = $region;
		}
		
        // Guardar datos en sesión
        $_SESSION['idusuario'] = $user['idusuario'];
        $_SESSION['nombre'] = $user['nombre'];
        // Calcular recursos actuales a partir del snapshot
        $rec = getUserResources($user['idusuario'], $conexion);
        $_SESSION['piedra'] = $rec['piedra'];
        $_SESSION['produccion_piedra_hora'] = $user['produccion_piedra_hora'];
        $_SESSION['metal'] = $rec['metal'];
        $_SESSION['produccion_metal_hora'] = $user['produccion_metal_hora'];
        $_SESSION['madera'] = $rec['madera'];
        $_SESSION['produccion_madera_hora'] = $user['produccion_madera_hora'];
        $_SESSION['comida'] = $rec['comida'];
        $_SESSION['produccion_comida_hora'] = $user['produccion_comida_hora'];
        $_SESSION['oro'] = $rec['oro'];
        $_SESSION['produccion_oro_hora'] = $user['produccion_oro_hora'];
        $_SESSION['mana'] = $rec['mana'];
        $_SESSION['produccion_mana_hora'] = $user['produccion_mana_hora'];
        $_SESSION['fecha_actualizado'] = $rec['ultimo_actualizacion'];
        $_SESSION['regiones'] = $regiones;
        // Redirigir a reino.php
        header("Location: /minaria/reino.php");
        exit();
    }
	else{
		$error = "Credenciales inválidas o cuenta no confirmada.";
	}
}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Acceso de usuario</title>
		<link rel="stylesheet" href="css/style-comun.css">
		<link id="estilo-css" rel="stylesheet" type="text/css" href="css/style-visual.css">
	</head>
	<body>
		<div class="main-container" style="justify-content: center; align-items: center;">
			<div class="section-content" style="max-width: 400px; width: 100%;">
				<h1 class="title">Acceso de usuario</h1>
				<form action="/minaria/login.php" method="POST" style="margin-top: 20px;">
					<div class="section">
						<label for="username">Nombre de usuario:</label>
						<input type="text" id="username" name="username" class="input-basic" style="width: 100%; margin-top: 5px; padding: 10px;" required>
					</div>
					<div class="section" style="margin-top: 15px;">
						<label for="password">Contraseña:</label>
						<input type="password" id="password" name="password" class="input-basic" style="width: 100%; margin-top: 5px; padding: 10px;" required>
					</div>
					<div class="section" style="margin-top: 20px; text-align: center;">
						<button type="submit" class="button-basic">Entrar</button>
					</div>
				</form>
				<div class="section" style="margin-top: 20px; text-align: center;">
					<a href="/minaria/registro.php">¿No tienes cuenta? <span class="verdefluor">Regístrate aquí</span></a>
				</div>
			</div>
		</div>
	</body>
</html>

