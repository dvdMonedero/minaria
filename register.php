<?php
session_start();
require_once 'config.php';
require_once 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['username'];
    $password = $_POST['password'];
	
	//Temporalmente: Si metes el mismo nombre que contraseña creamos una cuenta nueva
	if($email == $password){
		try {
			$pdo->beginTransaction();

			//Introducimos el usuario en la bd
			$sql_nuevo_usuario = "INSERT INTO `aa_usuario` 
									(`idusuario`, `email`, `password`, `confirmado`, `nombre`, `fecha_actualizado`) 
									VALUES 
									(NULL, ?, ?, '1', ?, ?)";
			$stmt_nuevo_usuario = $pdo->prepare($sql_nuevo_usuario);
			$stmt_nuevo_usuario->execute([$email, hash('sha256', $password), $email, time()]);
			$idusuario = $pdo->lastInsertId();

			// Creamos una región asociada al usuario, ya descubierta

			$pdo->commit();

			// Redirigir a login.php
			header("Location: /minaria/login.php");
			exit();
		} catch (Exception $e) {
			if ($pdo->inTransaction()) {
				$pdo->rollBack();
			}
			$error = "Error al crear la cuenta: " . $e->getMessage();
		}
	}
	else{
		$error = "El nombre de usuario ha de ser igual que la contraseña.";
	}
}
$modo = $_COOKIE['modo_estilo'] ?? 'visual';
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Registro de usuario</title>
		<link rel="stylesheet" type="text/css" href="css/style-comun.css">
		<link id="estilo-css" rel="stylesheet" type="text/css" href="<?= cargarHojaEstilos($modo); ?>">
	</head>
	<body>
		<div class="main-container" style="justify-content: center; align-items: center;">
			<div class="section-content" style="max-width: 400px; width: 100%;">
				<h1 class="title">Registro de usuario</h1>
				<h2 class="title">Introduce un nombre para el usuario y el mismo como contraseña (estamos en pruebas)</h2>
				<?php if (!empty($error)): ?>
					<div class="caja-gris-punteada" style="color: #ff6b6b; margin-top: 15px; text-align: center;">
						<?= htmlspecialchars($error) ?>
					</div>
				<?php endif; ?>
				<form action="/minaria/register.php" method="POST" style="margin-top: 20px;">
					<div class="section">
						<label for="username">Nombre de usuario (sin espacios):</label>
						<input type="text" id="username" name="username" class="input-basic" style="width: 100%; margin-top: 5px; padding: 10px;" required>
					</div>
					<div class="section" style="margin-top: 15px;">
						<label for="password">Contraseña:</label>
						<input type="password" id="password" name="password" class="input-basic" style="width: 100%; margin-top: 5px; padding: 10px;" required>
					</div>
					<div class="section" style="margin-top: 20px; text-align: center;">
						<button type="submit" class="button-basic">Registrar</button>
					</div>
				</form>
				<div class="section" style="margin-top: 20px; text-align: center;">
					<a href="/minaria/login.php">¿Ya tienes cuenta? <span class="verdefluor">Accede aquí</span></a>
				</div>
			</div>
		</div>
	</body>
</html>

