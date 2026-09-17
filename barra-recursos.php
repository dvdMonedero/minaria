<?php
/**
 * barra-recursos.php – Barra superior de recursos y producción.
 * Muestra el estado actual de los recursos del jugador y su tasa de producción por hora
 * (tanto a nivel de reino completo como para una región específica).
 */

require_once __DIR__ . '/cache.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/recursos.php';

// =============================================================================
// 1. CARGA E INICIALIZACIÓN DE DATOS
// =============================================================================

// Procesar exploración activa si ya ha cumplido su tiempo (entrega región y actualiza sesión)
if (isset($_SESSION['idusuario'])) {
    procesarExploracionUsuario(getPDO(), (int)$_SESSION['idusuario']);
}

// Inicializar recursos en sesión desde la base de datos si aún no existen
if (!isset($_SESSION['piedra']) && isset($_SESSION['idusuario'])) {
    $idUsuario = (int)$_SESSION['idusuario'];
    $recursosDb = getUserResources($idUsuario, getPDO());

    $_SESSION['piedra']             = (int)round($recursosDb['piedra'] ?? 0);
    $_SESSION['metal']              = (int)round($recursosDb['metal'] ?? 0);
    $_SESSION['madera']             = (int)round($recursosDb['madera'] ?? 0);
    $_SESSION['comida']             = (int)round($recursosDb['comida'] ?? 0);
    $_SESSION['oro']                = (int)round($recursosDb['oro'] ?? 0);
    $_SESSION['mana']               = (int)round($recursosDb['mana'] ?? 0);
    $_SESSION['fecha_actualizado']  = $recursosDb['ultimo_actualizacion'] ?? null;
}

// Determinar si estamos viendo una región concreta o la vista general del reino
$idRegionActual = isset($_GET['idr']) ? (int)$_GET['idr'] : null;
$esVistaRegion  = ($idRegionActual !== null && isset($_SESSION['regiones'][$idRegionActual]));

// Si es la vista general, calculamos la producción total sumando todas las regiones
if (!$esVistaRegion && !empty($_SESSION['regiones']) && is_array($_SESSION['regiones'])) {
    $totales = [
        'piedra' => 0,
        'metal'  => 0,
        'madera' => 0,
        'comida' => 0,
        'oro'    => 0,
        'mana'   => 0,
    ];

    foreach ($_SESSION['regiones'] as $idRegion => $info) {
        $prod = getRegionProduction((int)$idRegion, $conexion);
        $totales['piedra'] += (float)($prod['produccion_piedra_hora'] ?? 0);
        $totales['metal']  += (float)($prod['produccion_metal_hora'] ?? 0);
        $totales['madera'] += (float)($prod['produccion_madera_hora'] ?? 0);
        $totales['comida'] += (float)($prod['produccion_comida_hora'] ?? 0);
        $totales['oro']    += (float)($prod['produccion_oro_hora'] ?? 0);
        $totales['mana']   += (float)($prod['produccion_mana_hora'] ?? 0);
    }

    $_SESSION['produccion_total'] = $totales;
}

// Título de la barra
if ($esVistaRegion) {
    $regionInfo  = $_SESSION['regiones'][$idRegionActual];
    $nombreReg   = htmlspecialchars($regionInfo['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
    $tipoReg     = htmlspecialchars($regionInfo['tipo'] ?? '', ENT_QUOTES, 'UTF-8');
    $tituloBarra = "Región: {$nombreReg} ({$tipoReg})";
} else {
    $tituloBarra = "Vista general del reino";
}

// =============================================================================
// 2. CONFIGURACIÓN DE LOS RECURSOS VISIBLES
// =============================================================================

$configRecursos = [
    'piedra' => ['icono' => '🪨', 'etiqueta' => 'Piedra', 'color_clase' => 'rojofluor'],
    'metal'  => ['icono' => '⚙️', 'etiqueta' => 'Metal',  'color_clase' => 'verdefluor'],
    'madera' => ['icono' => '🌲', 'etiqueta' => 'Madera', 'color_clase' => 'rojofluor'],
    'comida' => ['icono' => '🍗', 'etiqueta' => 'Comida', 'color_clase' => ''],
    'oro'    => ['icono' => '💰', 'etiqueta' => 'Oro',    'color_clase' => 'verdefluor'],
    'mana'   => ['icono' => '✨', 'etiqueta' => 'Mana',   'color_clase' => 'verdefluor'],
];
?>

<!-- Menú horizontal de recursos -->
<div class="recursos-superior">
    <div class="title">
        <h1><?= $tituloBarra ?></h1>
    </div>

    <?php foreach ($configRecursos as $clave => $cfg): 
        // Cantidad acumulada actual
        $cantidadActual = (int)($_SESSION[$clave] ?? 0);

        // Producción por hora (de la región actual o total del reino)
        if ($esVistaRegion) {
            $produccionHora = (float)($_SESSION['region_produccion']["produccion_{$clave}_hora"] ?? 0);
        } else {
            $produccionHora = (float)($_SESSION['produccion_total'][$clave] ?? 0);
        }

        // Tasa por segundo para los incrementos en tiempo real mediante JavaScript
        $tasaPorSegundo = $produccionHora / 3600;
        $produccionRedondeada = (int)round($produccionHora);
    ?>
        <div class="recurso">
            <p>
                <span class="icono-recurso"><?= $cfg['icono'] ?></span>
                <span class="recursos-nombre"><?= $cfg['etiqueta'] ?>: </span>
                <span id="recurso-<?= $clave ?>" 
                      data-amount="<?= $cantidadActual ?>" 
                      data-rate="<?= $tasaPorSegundo ?>">
                </span>
            </p>
            <p>
                <span class="recursos-produccion <?= $cfg['color_clase'] ?>">
                    (<?= $produccionRedondeada ?> /h)
                </span>
            </p>
        </div>
    <?php endforeach; ?>
</div>
