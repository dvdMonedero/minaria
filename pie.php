<?php $modo = $modo ?? $_SESSION['modo_estilo'] ?? $_COOKIE['modo_estilo'] ?? 'visual'; ?>
<div id="pie">
	<button id="btn-visual" class="button-basic" onclick="cambiarEstilo('visual')" <?=($modo=='visual')?'style="display:none;"':''?>>Modo Visual</button>
	<button id="btn-simple" class="button-basic" onclick="cambiarEstilo('simple')"<?=($modo=='simple')?'style="display:none;"':''?>>Modo Simple</button>
	<button id="btn-ultrasimple" class="button-basic" onclick="cambiarEstilo('ultrasimple')"<?=($modo=='ultrasimple')?'style="display:none;"':''?>>Modo Ultrasimple</button>
</div>