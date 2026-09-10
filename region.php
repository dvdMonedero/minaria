<?php
require_once 'funciones.php';
//Vamos a ver qué hoja de estilo cargaremos (por defecto: visual)
$pdo = getPDO();
$idUsuario = $_SESSION['idusuario'] ?? null;

//Comprobamos que la región en la que estamos pertenezca al usuario
$es_mia = 0;
foreach($_SESSION['regiones'] as $region){
	//echo "GET".$_GET['idr']."<br/>";
	//echo "id Region:".$region['idregion']."<br/>";
	if($region['idregion'] == $_GET['idr']){
		//echo "ES MIA";
		$es_mia = 1;
		$idregion = $region['idregion'];
		$nombre_region = $region['nombre'];
		// Obtener producción de la región y guardarla en sesión
require_once __DIR__ . '/cache.php';
$regionProd = getRegionProduction($idregion, $conexion);
$_SESSION['region_produccion'] = $regionProd;
	}
}
if($es_mia == 0){
	header("Location: reino.php");
    exit();
}

//echo "<PRE>";print_r($_SESSION);echo "</PRE>";
?>

<!DOCTYPE html>

<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Región: <?=$nombre_region." (".$tipo_region.")"?></title>
		<link rel="stylesheet" type="text/css" href="css/style-comun.css">
		<link id="estilo-css" rel="stylesheet" type="text/css" href="<?= cargarHojaEstilos($modo); ?>">
	</head>

	<body class="<?= $modo ?>">
	
		<?php include 'barra-recursos.php'; ?>
		<?php echo divExploracion($pdo, $idUsuario); ?>

		<!-- Contenedor de todo lo que hay debajo del menú -->
		<div class="main-container">
			<!-- Contenido principal -->
			<div class="content">
				<!-- Ejércitos -->
				<div class="section">
					<?php
					//echo "<PRE>";print_r(edificiosConstruibles($_SESSION['idusuario'], $conexion));echo "</PRE>";
					echo divEdificios(edificiosConstruibles($_SESSION['idusuario'], $idregion, $conexion));
					?>
				
				
					<h2>Ejércitos Actuales</h2>
					<div class="caja-gris-punteada">
						Espadachines:          50<br/>
						Arqueros:              30<br/>
						Magos de batalla:      10<br/>
						Catapultas:             3<br/>
						Guardianes élficos:     5<br/>
						<br/>
						Refuerzos en camino:<br/>
						  <span class="no-en-ultrasimple">- </span>20 Arqueros (Llegan en 3 días)<br/>
						  <span class="no-en-ultrasimple">- </span>10 Magos (Llegan en 5 días)<br/>
						<br/>
						Misiones activas:<br/>
						  <span class="no-en-ultrasimple">- </span>Exploración del Valle Sombrío (Tiempo restante: 2 días)<br/>
						  <span class="no-en-ultrasimple">- </span>Recuperación de reliquia en el Bosque Perdido (Tiempo restante: 7 días)
					</div>
				</div>

				<!-- Eventos Recientes -->
				<div class="section">
					<h2>Eventos Recientes</h2>
					<div class="caja-gris-punteada">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br/>
						Aenean a augue orci. Donec eros justo, posuere vitae eros in, auctor maximus tortor.<br/>
						Morbi in ligula vitae enim facilisis imperdiet viverra in eros. Phasellus sit amet odio dolor.<br/>
						Fusce sed malesuada diam. Maecenas egestas velit vel pharetra ultricies. Morbi consectetur nibh.
					</div>
				</div>

				<!-- Construcciones en Proceso -->
				<div class="section">
					<h2>Construcciones en Proceso</h2>
					<div class="caja-gris-punteada">
					  <span class="no-en-ultrasimple">- </span>Duis eget rhoncus ante (Progreso: 60%)<br/>
					  <span class="no-en-ultrasimple">- </span>Mauris ut massa turpis (Progreso: 35%)<br/>
					  <span class="no-en-ultrasimple">- </span>Etiam eu enim et nulla interdum laoreet (Progreso: 90%)
					</div>
				</div>

				<!-- Economía Local -->
				<div class="section">
					<h2>Economía Local</h2>
					<div class="caja-gris-punteada">
						Ingresos diarios:      350 oro<br/>
						Gastos diarios:        200 oro<br/>
						Saldo actual:          150 oro<br/>
						<br/>
						Comercio activo:<br/>
						  <span class="no-en-ultrasimple">- </span>Exportación de 100 Madera a la región vecina (+50 oro)<br/>
						  <span class="no-en-ultrasimple">- </span>Importación de 30 Magia desde las Montañas Eternas (-30 oro)
					</div>
				</div>
			</div>

			<div class="section">
				<?php 
				// Listado de regiones
				echo divRegiones(0);
				?>
			</div>
		</div>

<!-- Script de actualización interno eliminado; la lógica de actualización en vivo se maneja en js/funciones.js -->
		
		<?php include 'pie.php';?>
		
		<script src="js/funciones.js"></script>
		<script src="js/funciones-visuales.js"></script>
	</body>
</html>