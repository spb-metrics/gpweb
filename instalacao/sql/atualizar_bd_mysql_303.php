<?php
global $config;
$sql = new BDConsulta;
$sql->adTabela('tema');	
$sql->adCampo('tema_id, tema_perspectiva');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha){
	if ($linha['tema_id'] && $linha['tema_perspectiva']){
		$sql->adTabela('tema_perspectiva');
		$sql->adInserir('tema_perspectiva_tema', $linha['tema_id']);
		$sql->adInserir('tema_perspectiva_perspectiva', $linha['tema_perspectiva']);
		$sql->adInserir('tema_perspectiva_ordem', 1);
		$sql->exec();
		$sql->limpar();
		}
	}

$sql->adTabela('objetivos_estrategicos');	
$sql->adCampo('pg_objetivo_estrategico_id, pg_objetivo_estrategico_perspectiva, pg_objetivo_estrategico_tema');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha){
	if ($linha['pg_objetivo_estrategico_id'] && ($linha['pg_objetivo_estrategico_perspectiva'] || $linha['pg_objetivo_estrategico_tema'])){
		$sql->adTabela('objetivo_perspectiva');
		$sql->adInserir('objetivo_perspectiva_objetivo', $linha['pg_objetivo_estrategico_id']);
		if ($linha['pg_objetivo_estrategico_tema']) $sql->adInserir('objetivo_perspectiva_tema', $linha['pg_objetivo_estrategico_tema']);
		if ($linha['pg_objetivo_estrategico_perspectiva']) $sql->adInserir('objetivo_perspectiva_perspectiva', $linha['pg_objetivo_estrategico_perspectiva']);
		$sql->adInserir('objetivo_perspectiva_ordem', 1);
		$sql->exec();
		$sql->limpar();
		}
	}
	
$sql->adTabela('fatores_criticos');	
$sql->adCampo('pg_fator_critico_id, pg_fator_critico_objetivo');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha){
	if ($linha['pg_fator_critico_id'] && $linha['pg_fator_critico_objetivo']){
		$sql->adTabela('fator_objetivo');
		$sql->adInserir('fator_objetivo_fator', $linha['pg_fator_critico_id']);
		$sql->adInserir('fator_objetivo_objetivo', $linha['pg_fator_critico_objetivo']);
		$sql->adInserir('fator_objetivo_ordem', 1);
		$sql->exec();
		$sql->limpar();
		}
	}	
	


$sql->adTabela('estrategias');	
$sql->adCampo('pg_estrategia_id, pg_estrategia_fator');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha){
	if ($linha['pg_estrategia_id'] && $linha['pg_estrategia_fator']){
		$sql->adTabela('estrategia_fator');
		$sql->adInserir('estrategia_fator_estrategia', $linha['pg_estrategia_id']);
		$sql->adInserir('estrategia_fator_fator', $linha['pg_estrategia_fator']);
		$sql->adInserir('estrategia_fator_ordem', 1);
		$sql->exec();
		$sql->limpar();
		}
	}	
?>
