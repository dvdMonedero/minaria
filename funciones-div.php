<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
error_reporting(E_ALL);ini_set('display_errors', 1);




//Crear el div de regiones (ancho o estrecho)
function divRegiones($fullscreen){
	$html_regiones = '';
	require_once __DIR__ . '/cache.php';
	if(empty($_SESSION['regiones'])){
		$html_regiones .= "no hay regiones";
		$html_regiones .= '		<div class="section">';
		$html_regiones .= '			<button class="button-basic"><a href="reino.php">&lt;<span style="text-decoration:underline">D</span>escrubrir regiones&gt;</a></button>';
		$html_regiones .= '		</div>';
	}
	else{
		//Tabla ancha para escritorio
		if($fullscreen == 1){
			$html_regiones .= '<div class="solo-escritorio">';
			$html_regiones .= '<table class="info-table">';
			$html_regiones .= '<tr>';
			$html_regiones .= '<th>Región</th>';
			$html_regiones .= '<th>Tipo</th>';
			$html_regiones .= '<th>Piedra</th>';
			$html_regiones .= '<th>Metal</th>';
			$html_regiones .= '<th>Madera</th>';
			$html_regiones .= '<th>Comida</th>';
			$html_regiones .= '<th>Oro</th>';
			$html_regiones .= '<th>Mana</th>';
			$html_regiones .= '</tr>';
			$i = 1;
			foreach($_SESSION['regiones'] as $region){
				$prod = getRegionProduction($region['idregion'], getPDO());
				$html_regiones .= '<tr>';
				$html_regiones .= "<td><span class='verdefluor'>[{$i}]</span> <a id='region-{$i}-{$region['idregion']}' href='region.php?idr={$region['idregion']}'>{$region['nombre']}</a></td>";
				$html_regiones .= "<td>{$region['tipo']}</td>";
				$html_regiones .= "<td>" . round((float)$prod['produccion_piedra_hora']) . "</td>";
				$html_regiones .= "<td>" . round((float)$prod['produccion_metal_hora']) . "</td>";
				$html_regiones .= "<td>" . round((float)$prod['produccion_madera_hora']) . "</td>";
				$html_regiones .= "<td>" . round((float)$prod['produccion_comida_hora']) . "</td>";
				$html_regiones .= "<td>" . round((float)$prod['produccion_oro_hora']) . "</td>";
				$html_regiones .= "<td>" . round((float)$prod['produccion_mana_hora']) . "</td>";
				$html_regiones .= '</tr>';
				$i++;
			}
			$html_regiones .= '</table>';
			$html_regiones .= '<div style="text-align: center; margin-top: 15px;">';
			$html_regiones .= '<button class="button-basic"><a href="explorar.php">&lt;E<span style="text-decoration:underline">x</span>plorar&gt;</a></button>';
			$html_regiones .= '</div>';
			$html_regiones .= '</div>';
			
			$html_regiones .= '	<div style="text-align: center; margin-top: 15px;">';
			$html_regiones .= '		<button class="button-basic"><a href="explorar.php">&lt;E<span style="text-decoration:underline">x</span>plorar&gt;</a></button>';
			$html_regiones .= '	</div>';
			$html_regiones .= '</div>';

		}
		//Tabla estrecha (para móvil o lateral en escritorio)
		else{
			//En la pantalla de reino no se muestra
			if (basename($_SERVER['PHP_SELF']) == 'reino.php')
				$html_regiones .= '<div class="solo-movil">';
			else
				$html_regiones .= '<div>';
			$html_regiones .= '		<h2>Regiones</h2>';
			$i = 1;
			
			foreach($_SESSION['regiones'] as $region){
				if(isset($_GET['idr']) && $i == $_GET['idr']){
					$html_regiones .= '		<div class="region caja-gris-punteada no-en-escritorio">';
				}else{
					$html_regiones .= '		<div class="region caja-gris-punteada">';
				}
				$html_regiones .= '			<div class="region-header">';
				$html_regiones .= '				<span><span class="verdefluor">['.$i.']</span> '.$region["nombre"].'</span></span>';
				$html_regiones .= '				<span class="toggle alreves">^</span>';
				$html_regiones .= '			</div>';
				$html_regiones .= '			<div class="region-content">';
				$html_regiones .= '				<p>('.$region["tipo"].')</p>';
				$html_regiones .= '				<p>Produciendo: 50<span class="icono-recurso" data-text="piedra">🪨️</span>/h</p>';
				$html_regiones .= '				<p>Capacidad: 5000 Metal</p>';
				$html_regiones .= '				<p>Población: 120 Trabajadores</p>';
				$html_regiones .= '				<p>Construyendo: Puerto (12%)</p>';
				$html_regiones .= '				<p><a id="region-'.$i.'-'.$region["idregion"].'" href="region.php?idr='.$region["idregion"].'">&lt;Ir a la región&gt;</a></p>';
				$html_regiones .= '			</div>';
				$html_regiones .= '		</div>';
				$i++;				
			}									
			$html_regiones .= '			<button class="button-basic"><a href="reino.php">&lt;Ir al <span style="text-decoration:underline">r</span>eino&gt;</a></button>';
			$html_regiones .= '			<button class="button-basic"><a href="logout.php">&lt;<span style="text-decoration:underline">S</span>alir&gt;</a></button>';
			$html_regiones .= '			<button class="button-basic"><a href="explorar.php">&lt;E<span style="text-decoration:underline">x</span>plorar&gt;</a></button>';
			$html_regiones .= '	</div>';	
		}
	}
	return ($html_regiones);
}

//Crear el div de edificios (ancho o estrecho)
function divEdificios($arr_edificios){
	$html_edificios = '';
	foreach ($arr_edificios as $edificio) {
		/* Comprobamos si el edificio tiene dependencias y si se puede construir */
		$puedeConstruir = true;        
		$mensajeBoton = "Construir";
		$html_requeridos = "";
		//Si hay edificios requeridos...
		if (!empty($edificio['edificios_requeridos'])) {
			foreach ($edificio['edificios_requeridos'] as $requerido) {
				//1.- Vamos rellenando el html de los edificios requeridos a mostrar
				$html_requeridos .= "<p>".$requerido['nombre_edificio_requerido']." nivel ".$requerido['nivel_requerido'].".</p>";
				//2.- Comprobamos si cumplimos requisito
				if ($requerido['nivel_requerido'] > 0) {
					$puedeConstruir = false;          
					$mensajeBoton = "No disponible";               
				}        
			}
		}
		// Si no puede ser construido, añadimos la clase "no-construible"
		$clase = $puedeConstruir ? '' : 'no-construible';                        
		$html_edificios .= '<div class="caja-gris-punteada $clase">';
		$html_edificios .= '	<h3>'.$edificio["nombre"].'</h3>';
		$html_edificios .= '	<p>Necesario para subir a nivel '.(intval($edificio["nivel"])+1).':</p>';
		$html_edificios .= $html_requeridos;
		if($edificio["coste_piedra"] > 0)
			$html_edificios .= '	<p>Piedra: '.round((float)$edificio["coste_piedra"]).' <span class="icono-recurso">🪨</span></p>';
		if($edificio["coste_metal"] > 0)
			$html_edificios .= '	<p>Metal: '.round((float)$edificio["coste_metal"]).' <span class="icono-recurso">⛏️</span></p>';
		if($edificio["coste_madera"] > 0)
			$html_edificios .= '	<p>Madera: '.round((float)$edificio["coste_madera"]).' <span class="icono-recurso">🌲</span></p>';
		if($edificio["coste_comida"] > 0)
			$html_edificios .= '	<p>Comida: '.round((float)$edificio["coste_comida"]).' <span class="icono-recurso">🍗</span></p>';
		if($edificio["coste_oro"] > 0)
			$html_edificios .= '	<p>Oro: '.round((float)$edificio["coste_oro"]).' <span class="icono-recurso">💰</span></p>';
		if($edificio["coste_magia"] > 0)
			$html_edificios .= '	<p>Mana: '.round((float)$edificio["coste_magia"]).'<span class="icono-recurso">✨</span></p>';
		// Mostrar el botón si se puede construir
		if ($puedeConstruir) {
			$html_edificios .= "<button class='button-basic'><a href='reino.php'>&lt;".($mensajeBoton === 'Construir' ? 'Construir' : 'Subir a nivel ' . ($requerido["nivel"] + 1))."&gt;</a></button>";
		}                
		$html_edificios .= '</div>';    	
	}

	return ($html_edificios);
}

/**
 * Genera el contenedor HTML y el script JS de la barra de progreso de exploración
 * si el usuario tiene una exploración activa en curso.
 */
function divExploracion(PDO $pdo, ?int $idUsuario): string {
	$explor = procesarExploracionUsuario($pdo, $idUsuario);
	if (!$explor) {
		return '';
	}

	$fechaFin = $explor['fecha_fin'];
	$totalSeg = strtotime($explor['fecha_fin']) - strtotime($explor['fecha_inicio']);

	$html = "<div id='exploration-container' style='margin-top:10px;'>";
	$html .= "	<span id='exploration-message'>Explorando nueva región: </span>";
	$html .= "	<span id='timer'></span> restante ";
	$html .= "	<span class='percentage-text'></span>";
	$html .= "	<div class='progress-bar-container'><div class='progress-bar'></div></div>";
	$html .= "</div>";

	$html .= "<script>
	(function(){
		const timerEl = document.getElementById('timer');
		const bar = document.querySelector('.progress-bar');
		const percEl = document.querySelector('.percentage-text');
		const total = " . (int)$totalSeg . ";
		const end = new Date('" . addslashes($fechaFin) . "').getTime();
		function update(){
			const now = Date.now();
			const remaining = Math.max(0, Math.floor((end - now)/1000));
			const percent = total ? Math.round((remaining/total)*100) : 0;
			if(timerEl) timerEl.textContent = Math.floor(remaining/60) + ' min ' + (remaining%60) + 's';
			if(bar) bar.style.width = percent + '%';
			if(percEl) percEl.textContent = '(' + percent + '%)';
			if (remaining <= 0) {
				clearInterval(iv);
				if (!sessionStorage.getItem('exploreReloaded')) {
					sessionStorage.setItem('exploreReloaded', '1');
					location.reload();
				}
			}
		}
		const iv = setInterval(update, 1000);
		update();
	})();
	</script>";

	return $html;
}

?>