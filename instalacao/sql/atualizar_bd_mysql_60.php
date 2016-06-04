<?php
global $atualizacao;

if ($atualizacao==60){
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao');
	$sql->adCampo('pg_id, pg_cia');
	$lista_pg=$sql->listaVetorChave('pg_id','pg_cia');
	$sql->Limpar();
	$sql->adTabela('projeto_indicadores');
	$sql->adCampo('projeto_id, pratica_indicador_id');
	$lista=$sql->Lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('pratica_indicador');
		$sql->adAtualizar('pratica_indicador_projeto', $linha['projeto_id']);
		$sql->adOnde('pratica_indicador_id = '.$linha['pratica_indicador_id']);
		$retorno=$sql->exec();
		$sql->Limpar();
		}
	$sql->adTabela('perspectivas');
	$sql->adCampo('pg_perspectiva_id, pg_perspectiva_pg_id');
	$lista=$sql->Lista();
	$sql->Limpar();
	foreach($lista as $linha){
		if (isset($lista_pg[$linha['pg_perspectiva_pg_id']])){
			$sql->adTabela('perspectivas');
			$sql->adAtualizar('pg_perspectiva_cia', $lista_pg[$linha['pg_perspectiva_pg_id']]);
			$sql->adOnde('pg_perspectiva_id = '.$linha['pg_perspectiva_id']);
			$retorno=$sql->exec();
			$sql->Limpar();
			}
		}
	$sql->adTabela('objetivos_estrategicos');
	$sql->adCampo('pg_objetivo_estrategico_id, pg_objetivo_estrategico_pg_id');
	$lista=$sql->Lista();
	$sql->Limpar();
	foreach($lista as $linha){
		if (isset($lista_pg[$linha['pg_objetivo_estrategico_pg_id']])){
			$sql->adTabela('objetivos_estrategicos');
			$sql->adAtualizar('pg_objetivo_estrategico_cia', $lista_pg[$linha['pg_objetivo_estrategico_pg_id']]);
			$sql->adOnde('pg_objetivo_estrategico_id = '.$linha['pg_objetivo_estrategico_id']);
			$retorno=$sql->exec();
			$sql->Limpar();
			}
		}
	$sql->adTabela('estrategias');
	$sql->adCampo('pg_estrategia_id, pg_estrategia_pg_id');
	$lista=$sql->Lista();
	$sql->Limpar();
	foreach($lista as $linha){
		if (isset($lista_pg[$linha['pg_estrategia_pg_id']])){
			$sql->adTabela('estrategias');
			$sql->adAtualizar('pg_estrategia_cia', $lista_pg[$linha['pg_estrategia_pg_id']]);
			$sql->adOnde('pg_estrategia_id = '.$linha['pg_estrategia_id']);
			$retorno=$sql->exec();
			$sql->Limpar();
			}
		}
		
	}
?>