<?php
// cache.php – Capa de caché simple para los valores de producción de región
// Usa APCu si está disponible; de lo contrario, recurre a caché basada en archivos.

if (!function_exists('getRegionProduction')) {
    function getRegionProduction(int $idregion, PDO $conexion): array {
        $cacheKey = "region_prod_{$idregion}";
        // Intentar obtener de la caché APCu
        if (function_exists('apcu_fetch')) {
            $cached = apcu_fetch($cacheKey, $success);
            if ($success && is_array($cached)) {
                foreach ($cached as $k => $v) {
                    $cached[$k] = (int)round((float)$v);
                }
                return $cached;
            }
        }
        // Alternativa: caché en archivo (en el directorio temporal)
        $cacheFile = sys_get_temp_dir() . "/region_prod_{$idregion}.json";
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 60)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            if (is_array($data)) {
                foreach ($data as $k => $v) {
                    $data[$k] = (int)round((float)$v);
                }
                if (function_exists('apcu_store')) {
                    apcu_store($cacheKey, $data, 60);
                }
                return $data;
            }
        }
        // Si no está en caché – consultar la base de datos
        $stmt = $conexion->prepare(
            "SELECT 
                tr.produccion_piedra_hora * COALESCE(ar.mult_piedra, 1)   AS produccion_piedra_hora,
                tr.produccion_metal_hora  * COALESCE(ar.mult_metal,  1)   AS produccion_metal_hora,
                tr.produccion_madera_hora * COALESCE(ar.mult_madera, 1)   AS produccion_madera_hora,
                tr.produccion_comida_hora * COALESCE(ar.mult_comida, 1)   AS produccion_comida_hora,
                tr.produccion_oro_hora    * COALESCE(ar.mult_oro,    1)   AS produccion_oro_hora,
                tr.produccion_mana_hora   * COALESCE(ar.mult_mana,   1)   AS produccion_mana_hora
            FROM aa_tipo_region tr 
            JOIN aa_region r ON r.idtipo_region = tr.idtipo_region 
            LEFT JOIN aa_adjetivos_region ar ON ar.id = r.adjetivo_id 
            WHERE r.idregion = :idregion"
        );
        $stmt->execute([':idregion' => $idregion]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            $row = [
                'produccion_piedra_hora' => 0,
                'produccion_metal_hora' => 0,
                'produccion_madera_hora' => 0,
                'produccion_comida_hora' => 0,
                'produccion_oro_hora' => 0,
                'produccion_mana_hora' => 0,
            ];
        } else {
            foreach ($row as $k => $v) {
                $row[$k] = (int)round((float)$v);
            }
        }
        // Guardar en cachés
        if (function_exists('apcu_store')) {
            apcu_store($cacheKey, $row, 60);
        }
        file_put_contents($cacheFile, json_encode($row));
        return $row;
    }
}

// Invalidar la producción en caché de una región
function invalidateRegionCache(int $idregion): void {
    $cacheKey = "region_prod_{$idregion}";
    if (function_exists('apcu_delete')) {
        apcu_delete($cacheKey);
    }
    $cacheFile = sys_get_temp_dir() . "/region_prod_{$idregion}.json";
    if (file_exists($cacheFile)) {
        @unlink($cacheFile);
    }
}
?>
