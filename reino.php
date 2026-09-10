<?php
require_once 'funciones.php';
//Vamos a ver qué hoja de estilo cargaremos (por defecto: visual)
$pdo = getPDO();
$idUsuario = $_SESSION['idusuario'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">    
		<meta name="viewport" content="width=device-width, initial-scale=1.0">    
		<title>Vista general del reino</title>	
		<link rel="stylesheet" type="text/css" href="css/style-comun.css">
		<link id="estilo-css" rel="stylesheet" type="text/css" href="<?= cargarHojaEstilos($modo); ?>">
	</head>
	
	<body class="<?php echo $modo; ?>">
	<?php include 'barra-recursos.php'; ?>
	
	
		<!-- Contenedor de todo lo que hay debajo del menú -->
		<div class="main-container">
			<!-- Contenido principal -->
			<div class="content">
				<!-- Regiones -->
				<div class="section">	

				<?php echo divExploracion($pdo, $idUsuario); ?>
				
					<?php 
					// Tabla de regiones ancha full screen (escritorio)
					echo divRegiones(1);
					
					// Tabla de regiones lite (para móvil o lateral de escritorio)
					echo divRegiones(0);
					?>
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
			</div>
		</div>
		
		<?php include 'pie.php';?>
		
		<script src="js/funciones.js"></script>
		<script src="js/funciones-visuales.js"></script>
	</body>
</html>

<?php
//echo "<PRE>";print_r($_SESSION);echo "</PRE>";