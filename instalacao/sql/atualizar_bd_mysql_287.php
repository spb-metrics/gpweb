<?php
global $config;
$sql = new BDConsulta;
$sql->adTabela('metas');	
$sql->adCampo('pg_meta_id, pg_meta_perspectiva, pg_meta_tema, pg_meta_objetivo_estrategico, pg_meta_fator, pg_meta_estrategia');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha){
	if ($linha['pg_meta_perspectiva'] || $linha['pg_meta_tema'] || $linha['pg_meta_objetivo_estrategico'] || $linha['pg_meta_fator'] || $linha['pg_meta_estrategia']){
		$sql->adTabela('meta_gestao');
		$sql->adInserir('meta_gestao_meta', $linha['pg_meta_id']);
		if ($linha['pg_meta_perspectiva']) $sql->adInserir('meta_gestao_perspectiva', $linha['pg_meta_perspectiva']);
		elseif ($linha['pg_meta_tema']) $sql->adInserir('meta_gestao_tema', $linha['pg_meta_tema']);
		elseif ($linha['pg_meta_objetivo_estrategico']) $sql->adInserir('meta_gestao_objetivo', $linha['pg_meta_objetivo_estrategico']);
		elseif ($linha['pg_meta_fator']) $sql->adInserir('meta_gestao_fator', $linha['pg_meta_fator']);
		elseif ($linha['pg_meta_estrategia']) $sql->adInserir('meta_gestao_estrategia', $linha['pg_meta_estrategia']);
		$sql->exec();
		$sql->limpar();
		}
	}


?>
