<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
require_once 'funciones-div.php';
error_reporting(E_ALL);ini_set('display_errors', 1);


//Todas las páginas requieren que tenga sesión, salvo las indicadas
$paginas_sin_sesion = ['login.php', 'registro.php', 'register.php', 'recuperar.php'];

// Verificamos la sesión solo si la página actual no está en la lista de exclusión
if (!in_array(basename($_SERVER['PHP_SELF']), $paginas_sin_sesion) && !isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit();
}

    // Determinar y persistir el estilo visual activo (visual, simple, ultrasimple)
    // Prioridad: parámetro GET > sesión > cookie > valor por defecto
    if (isset($_GET['modo'])) {
        $validModes = ['visual', 'simple', 'ultrasimple'];
        $modoParam = $_GET['modo'];
        if (in_array($modoParam, $validModes, true)) {
            $_SESSION['modo_estilo'] = $modoParam;
            setcookie('modo_estilo', $modoParam, time() + 365 * 24 * 60 * 60, '/');
        }
    }
    $modo = $_SESSION['modo_estilo'] ?? $_COOKIE['modo_estilo'] ?? 'visual';
    $_SESSION['modo_estilo'] = $modo;


function cargarHojaEstilos($modo){
    switch ($modo) {
        case 'ultrasimple':
            $css = 'css/style-ultrasimple.css';
            break;
        case 'simple':
            $css = 'css/style-simple.css';
            break;
        default:
            $css = 'css/style-visual.css';
    }
    return $css;
}

/**
 * Calcula la duración (segundos) de la exploración para el usuario.
 * - Primera exploración: 30 segundos.
 * - Segunda exploración: base de 15 minutos (900 segundos).
 * - Siguientes exploraciones: crecimiento exponencial a partir del tiempo base de la segunda.
 */
function calcularTiempoExploracion(PDO $pdo, int $idUsuario): int {
    $PRIMERA_EXPLORACION_SEGUNDOS = 30;         // 30 segundos para la primera exploración
    $BASE_SEGUNDA_EXPLORACION     = 15 * 60;    // 15 minutos (900 s) base para la segunda
    $FACTOR_CRECIMIENTO           = 5.0;        // Crecimiento exponencial x5 por región adicional

    // Cuántas regiones tiene ya descubiertas el usuario
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM aa_usuario_region WHERE idusuario = :id"
    );
    $stmt->execute([':id' => $idUsuario]);
    $numRegiones = (int)$stmt->fetchColumn();

    // 1.ª exploración (0 regiones descubiertas)
    if ($numRegiones === 0) {
        return $PRIMERA_EXPLORACION_SEGUNDOS;
    }

    // 2.ª exploración en adelante (Opción D, factor x5):
    // - Con 1 región (para descubrir la 2.ª): pow(5, 0) * 900  = 900 s (15 min)
    // - Con 2 regiones (para descubrir la 3.ª): pow(5, 1) * 900 = 4.500 s (1 h 15 min)
    // - Con 3 regiones (para descubrir la 4.ª): pow(5, 2) * 900 = 22.500 s (6 h 15 min)
    // - Con 4 regiones (para descubrir la 5.ª): pow(5, 3) * 900 = 112.500 s (31 h 15 min)
    $exponente = $numRegiones - 1;
    $tiempo = (int)round($BASE_SEGUNDA_EXPLORACION * pow($FACTOR_CRECIMIENTO, $exponente));

    return $tiempo;
}

/**
 * Recarga la lista de regiones del usuario en la sesión para mantenerla sincronizada.
 */
function recargarRegionesSesion(PDO $pdo, int $idUsuario): void {
    $queryRegiones = "
        SELECT r.idregion, r.nombre, tr.nombre AS tipo 
        FROM aa_region r
        LEFT JOIN aa_usuario_region ur 
            ON ur.idregion = r.idregion 
        LEFT JOIN aa_tipo_region tr 
            ON tr.idtipo_region = r.idtipo_region 
        WHERE ur.idusuario = :idusuario;
    ";
    $stmtRegiones = $pdo->prepare($queryRegiones);
    $stmtRegiones->execute([':idusuario' => $idUsuario]);
    $resultados = $stmtRegiones->fetchAll(PDO::FETCH_ASSOC);
    $regiones = [];
    foreach ($resultados as $region) {
        $regiones[$region['idregion']] = $region;
    }
    $_SESSION['regiones'] = $regiones;
}

/**
 * Comprueba el estado de la exploración activa del usuario.
 * Si ya finalizó (fecha_fin <= NOW()), la marca como finalizada,
 * crea la nueva región para el usuario y recarga la sesión.
 * Si sigue activa, devuelve los datos de la exploración; de lo contrario devuelve null.
 */
function procesarExploracionUsuario(PDO $pdo, ?int $idUsuario): ?array {
    if (!$idUsuario) {
        return null;
    }

    $stmt = $pdo->prepare(
        "SELECT idexploracion, fecha_inicio, fecha_fin 
         FROM aa_exploracion 
         WHERE idusuario = :id AND estado = 'activa' 
         LIMIT 1"
    );
    $stmt->execute([':id' => $idUsuario]);
    $explor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$explor) {
        return null;
    }

    // Comprobar si ya ha terminado el tiempo
    if (strtotime($explor['fecha_fin']) <= time()) {
        // Finalizar la exploración en la base de datos
        $upd = $pdo->prepare(
            "UPDATE aa_exploracion SET estado = 'finalizada' WHERE idexploracion = :idexp"
        );
        $upd->execute([':idexp' => $explor['idexploracion']]);

        // Crear la nueva región descubierta para el usuario
        crearRegionParaUsuario($idUsuario, $pdo);

        // Actualizar la lista de regiones en la sesión
        recargarRegionesSesion($pdo, $idUsuario);

        return null;
    }

    return $explor;
}



function calcularRecursos() {
    // Obtener el tiempo actual en formato UNIX
    $tiempo_actual = time();

    // Calcular el tiempo transcurrido desde la última actualización
    $tiempo_transcurrido = $tiempo_actual - $_SESSION['fecha_actualizado'];

    // Calcular los recursos actualizados
    $recursos_actualizados = [
        'piedra' => $_SESSION['piedra'] + ($_SESSION['produccion_piedra_hora'] / 3600) * $tiempo_transcurrido,
        'metal' => $_SESSION['metal'] + ($_SESSION['produccion_metal_hora'] / 3600) * $tiempo_transcurrido,
        'madera' => $_SESSION['madera'] + ($_SESSION['produccion_madera_hora'] / 3600) * $tiempo_transcurrido,
        'comida' => $_SESSION['comida'] + ($_SESSION['produccion_comida_hora'] / 3600) * $tiempo_transcurrido,
        'oro' => $_SESSION['oro'] + ($_SESSION['produccion_oro_hora'] / 3600) * $tiempo_transcurrido,
        'mana' => $_SESSION['mana'] + ($_SESSION['produccion_mana_hora'] / 3600) * $tiempo_transcurrido,
    ];

    // Redondear los valores a enteros hacia abajo
    foreach ($recursos_actualizados as $recurso => $valor) {
        $recursos_actualizados[$recurso] = floor($valor); // Redondeo hacia abajo
    }

    return $recursos_actualizados;
}



function edificiosConstruibles($idUsuario, $idregion, $pdo) {
    // Query para obtener todos los edificios y sus requisitos
    $sql = "SELECT e.idedificio, e.nombre, e.coste_piedra, e.coste_metal, e.coste_madera, 
                   e.coste_comida, e.coste_oro, e.coste_magia, e.multiplicador_coste,
                   er.idedificio_requerido, er.nivel_edificio_requerido, 
				   ern.nombre AS nombre_edificio_requerido 
            FROM aa_edificio e
            LEFT JOIN aa_edificiorequiere er ON e.idedificio = er.idedificio 
			LEFT JOIN aa_edificio ern ON ern.idedificio = er.idedificio_requerido 
            ORDER BY e.nombre";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Inicializar el array de resultados
    $edificios = [];

    // Recorremos los resultados
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idEdificio = $row['idedificio'];

        // Si no existe el edificio en el array, lo inicializamos
        if (!isset($edificios[$idEdificio])) {
            $edificios[$idEdificio] = [
                'idedificio' => $idEdificio,
                'nombre' => $row['nombre'],
                'coste_piedra' => (int)round((float)$row['coste_piedra'] * (float)$row['multiplicador_coste']),
                'coste_metal' => (int)round((float)$row['coste_metal'] * (float)$row['multiplicador_coste']),
                'coste_madera' => (int)round((float)$row['coste_madera'] * (float)$row['multiplicador_coste']),
                'coste_comida' => (int)round((float)$row['coste_comida'] * (float)$row['multiplicador_coste']),
                'coste_oro' => (int)round((float)$row['coste_oro'] * (float)$row['multiplicador_coste']),
                'coste_magia' => (int)round((float)$row['coste_magia'] * (float)$row['multiplicador_coste']),
                'multiplicador_coste' => $row['multiplicador_coste'],
                'edificios_requeridos' => [],
                'nivel' => 0 // Nivel por defecto si no ha sido construido
            ];
        }

        // Si hay edificios requeridos, los agregamos
        if ($row['idedificio_requerido']) {
            $edificios[$idEdificio]['edificios_requeridos'][] = [
                'idedificio_requerido' => $row['idedificio_requerido'],
                'nivel_requerido' => $row['nivel_edificio_requerido'],
                'nombre_edificio_requerido' => $row['nombre_edificio_requerido']
            ];
        }
    }

    // Ahora obtendremos los edificios ya construidos por el usuario
    $sql = "SELECT re.idregion, re.idedificio, re.nivel
			FROM aa_region_edificio re  
            INNER JOIN aa_usuario_region ur ON ur.idregion = re.idregion 
            WHERE ur.idusuario = :idUsuario 
			AND re.idregion = ".$idregion."
			ORDER BY re.idregion";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();

    $edificiosConstruidos = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $edificiosConstruidos[$row['idedificio']] = $row['nivel'];
    }

    // Ahora podemos verificar cuáles edificios se pueden construir y agregar el nivel actual
    foreach ($edificios as &$edificio) {
        // Añadimos el nivel actual del edificio
        $edificio['nivel'] = $edificiosConstruidos[$edificio['idedificio']] ?? 0;

        // Verificamos si se puede construir
        $puedeConstruir = true;
        foreach ($edificio['edificios_requeridos'] as $requisito) {
            if (!isset($edificiosConstruidos[$requisito['idedificio_requerido']]) || 
                $edificiosConstruidos[$requisito['idedificio_requerido']] < $requisito['nivel_requerido']) {
                $puedeConstruir = false;
                break;
            }
        }

        // Verificar recursos disponibles antes de permitir construir
        $recursosUsuario = getUserResources($idUsuario, $conexion);
        if ($recursosUsuario['piedra'] < $edificio['coste_piedra'] ||
            $recursosUsuario['metal'] < $edificio['coste_metal'] ||
            $recursosUsuario['madera'] < $edificio['coste_madera'] ||
            $recursosUsuario['comida'] < $edificio['coste_comida'] ||
            $recursosUsuario['oro'] < $edificio['coste_oro'] ||
            $recursosUsuario['mana'] < $edificio['coste_magia']) {
            $puedeConstruir = false;
        }

        $edificio['puede_construir'] = $puedeConstruir;
    }

    return array_values($edificios); // Para evitar índices numéricos rotos
}

/**
 * Crea una región aleatoria asociada a un usuario (ya descubierta).
 * El tipo de región se elige aleatoriamente de aa_tipo_region.
 * El adjetivo se elige aleatoriamente ponderando equitativamente grupos y adjetivos individuales,
 * y luego concordando en género y número con el tipo de región si pertenece a un grupo.
 *
 * @param int $idusuario ID del usuario al que pertenecerá la región
 * @param PDO $pdo Conexión PDO a la base de datos
 * @return array Datos de la región creada
 */
function crearRegionParaUsuario($idusuario, $pdo) {
    // 1. Elegir tipo de región aleatorio
    $sql_tipo = "SELECT idtipo_region, nombre, genero, numero FROM `aa_tipo_region` ORDER BY RAND() LIMIT 1";
    $stmt_tipo = $pdo->query($sql_tipo);
    $tipo_region = $stmt_tipo->fetch(PDO::FETCH_ASSOC);

    if (!$tipo_region) {
        throw new Exception("No se pudo obtener un tipo de región de la base de datos.");
    }

    // 2. Elegir adjetivo aleatorio con probabilidad equilibrada entre grupos y adjetivos individuales
    $sql_adjetivo = "SELECT ar.*
                     FROM aa_adjetivos_region ar
                     JOIN (
                         SELECT elemento
                         FROM (
                             SELECT CONCAT('g_', grupo) AS elemento
                             FROM aa_adjetivos_region
                             WHERE grupo IS NOT NULL
                             GROUP BY grupo

                             UNION ALL

                             SELECT CONCAT('i_', id) AS elemento
                             FROM aa_adjetivos_region
                             WHERE grupo IS NULL
                         ) AS elementos
                         ORDER BY RAND()
                         LIMIT 1
                     ) AS elegido
                     ON elegido.elemento = CASE
                         WHEN ar.grupo IS NOT NULL THEN CONCAT('g_', ar.grupo)
                         ELSE CONCAT('i_', ar.id)
                     END
                     ORDER BY RAND()
                     LIMIT 1";
    $stmt_adjetivo = $pdo->query($sql_adjetivo);
    $adjetivo = $stmt_adjetivo->fetch(PDO::FETCH_ASSOC);

    if (!$adjetivo) {
        throw new Exception("No se pudo obtener un adjetivo de la base de datos.");
    }

    // 3. Si el adjetivo pertenece a un grupo, elegir la opción que concuerde en género y número con el tipo de región
    if (!empty($adjetivo['grupo'])) {
        $sql_concordancia = "SELECT * 
                             FROM `aa_adjetivos_region` 
                             WHERE `grupo` = :grupo 
                               AND (`genero` = :genero OR `genero` = 'todos') 
                               AND (`numero` = :numero OR `numero` = 'todos')
                             ORDER BY (`genero` = :genero) DESC, (`numero` = :numero) DESC
                             LIMIT 1";
        $stmt_concordancia = $pdo->prepare($sql_concordancia);
        $stmt_concordancia->execute([
            ':grupo' => $adjetivo['grupo'],
            ':genero' => $tipo_region['genero'],
            ':numero' => $tipo_region['numero']
        ]);
        $adjetivo_concordado = $stmt_concordancia->fetch(PDO::FETCH_ASSOC);

        // Si no hubiera coincidencia exacta con 'todos' en el grupo, recurrir a la mejor opción disponible en el grupo
        if (!$adjetivo_concordado) {
            $sql_fallback = "SELECT * 
                             FROM `aa_adjetivos_region` 
                             WHERE `grupo` = :grupo 
                             ORDER BY (`genero` = :genero) DESC, (`numero` = :numero) DESC
                             LIMIT 1";
            $stmt_fallback = $pdo->prepare($sql_fallback);
            $stmt_fallback->execute([
                ':grupo' => $adjetivo['grupo'],
                ':genero' => $tipo_region['genero'],
                ':numero' => $tipo_region['numero']
            ]);
            $adjetivo_concordado = $stmt_fallback->fetch(PDO::FETCH_ASSOC);
        }

        if ($adjetivo_concordado) {
            $adjetivo = $adjetivo_concordado;
        }
    }

    // 4. Construir el nombre de la región
    $nombre_region = trim($tipo_region['nombre'] . ' ' . $adjetivo['nombre']);

    // 5. Insertar la nueva región en aa_region
    $sql_region = "INSERT INTO `aa_region` (`idtipo_region`, `nombre`) VALUES (:idtipo_region, :nombre)";
    $stmt_region = $pdo->prepare($sql_region);
    $stmt_region->execute([
        ':idtipo_region' => $tipo_region['idtipo_region'],
        ':nombre' => $nombre_region
    ]);
    $idregion = $pdo->lastInsertId();

    // 6. Asociar la región al usuario en aa_usuario_region (ya descubierta)
    if ($idusuario) {
        $sql_usuario_region = "INSERT INTO `aa_usuario_region` (`idusuario`, `idregion`) VALUES (:idusuario, :idregion)";
        $stmt_usuario_region = $pdo->prepare($sql_usuario_region);
        $stmt_usuario_region->execute([
            ':idusuario' => $idusuario,
            ':idregion' => $idregion
        ]);
    }

    return [
        'idregion' => $idregion,
        'nombre' => $nombre_region,
        'idtipo_region' => $tipo_region['idtipo_region'],
        'tipo' => $tipo_region['nombre'],
        'adjetivo' => $adjetivo['nombre']
    ];
}

?>