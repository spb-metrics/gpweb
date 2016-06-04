<?php
global $bd;

$sql = new BDConsulta;
$sql->adTabela('eventos');
$sql->adCampo('eventos.*');
$sql->adOnde('evento_nr_recorrencias > 0');
$sql->adOnde('evento_recorrencias > 0');
$lista = $sql->lista();
$sql->limpar();

$novos=array();
foreach($lista as $linha){
	
	// 1 = hora, 2 = dia , 3 = semana, 4 = 15 dias, 5 = mes, 6 = quadrimestre, 7 = semestral, 8 =anual
	
	$data=substr($linha['evento_inicio'], 0, 10);
	
	$hora_inicio=substr($linha['evento_inicio'], 10, 8);
	$hora_fim=substr($linha['evento_fim'], 10, 8);
	
	$sql->adTabela('evento_usuarios');
	$sql->adCampo('evento_usuarios.*');
	$sql->adOnde('evento_id='.(int)$linha['evento_id']);
	$designados = $sql->lista();
	$sql->limpar();
	
	
	for ($i=0; $i < $linha['evento_nr_recorrencias'] ; $i++){

		if ($linha['evento_recorrencias']==2){
			$data=strtotime('+1 day', strtotime($data));
			$data=date('Y-m-d', $data);
			}

		if ($linha['evento_recorrencias']==3){
			$data=strtotime('+1 week', strtotime($data));
			$data=date('Y-m-d', $data);
			}
		
		if ($linha['evento_recorrencias']==4){
			$data=strtotime('+15 day', strtotime($data));
			$data=date('Y-m-d', $data);
			}
		
		if ($linha['evento_recorrencias']==5){
			$data=strtotime('+1 month', strtotime($data));
			$data=date('Y-m-d', $data);
			}

		if ($linha['evento_recorrencias']==6){
			$data=strtotime('+4 month', strtotime($data));
			$data=date('Y-m-d', $data);
			}

		if ($linha['evento_recorrencias']==7){
			$data=strtotime('+6 month', strtotime($data));
			$data=date('Y-m-d', $data);
			}
			
		if ($linha['evento_recorrencias']==8){
			$data=strtotime('+1 year', strtotime($data));
			$data=date('Y-m-d', $data);
			}	
		
		$sql->adTabela('eventos');
		$sql->adInserir('evento_cia', $linha['evento_cia']);
		$sql->adInserir('evento_dono', $linha['evento_dono']);
		$sql->adInserir('evento_projeto', $linha['evento_projeto']);
		$sql->adInserir('evento_tarefa', $linha['evento_tarefa']);
		$sql->adInserir('evento_pratica', $linha['evento_pratica']);
		$sql->adInserir('evento_acao', $linha['evento_acao']);
		$sql->adInserir('evento_tema', $linha['evento_tema']);
		$sql->adInserir('evento_objetivo', $linha['evento_objetivo']);
		$sql->adInserir('evento_fator', $linha['evento_fator']);
		$sql->adInserir('evento_estrategia', $linha['evento_estrategia']);
		$sql->adInserir('evento_meta', $linha['evento_meta']);
		$sql->adInserir('evento_indicador', $linha['evento_indicador']);
		$sql->adInserir('evento_calendario', $linha['evento_calendario']);
		$sql->adInserir('evento_titulo', $linha['evento_titulo']);
		$sql->adInserir('evento_inicio', $data.' '.$hora_inicio);
		$sql->adInserir('evento_fim', $data.' '.$hora_fim);
		$sql->adInserir('evento_descricao', $linha['evento_descricao']);
		$sql->adInserir('evento_url', $linha['evento_url']);
		$sql->adInserir('evento_lembrar', $linha['evento_lembrar']);
		$sql->adInserir('evento_icone', $linha['evento_icone']);
		$sql->adInserir('evento_privado', $linha['evento_privado']);
		$sql->adInserir('evento_tipo', $linha['evento_tipo']);
		$sql->adInserir('evento_diautil', $linha['evento_diautil']);
		$sql->adInserir('evento_notificar', $linha['evento_notificar']);
		$sql->adInserir('evento_localizacao', $linha['evento_localizacao']);
		$sql->adInserir('evento_acesso', $linha['evento_acesso']);
		$sql->adInserir('evento_cor', $linha['evento_cor']);
		$sql->adInserir('evento_nr_recorrencias', 0);
		$sql->adInserir('evento_recorrencias', 0);
		$sql->adInserir('evento_recorrencia_pai', $linha['evento_id']);
		$sql->exec();
		$evento_id=$bd->Insert_ID('eventos','evento_id');
		$sql->limpar();
		//designados
		foreach($designados as $linha2){
			$sql->adTabela('evento_usuarios');
			$sql->adInserir('usuario_id', $linha2['usuario_id']);
			$sql->adInserir('aceito', $linha2['aceito']);
			$sql->adInserir('data', $linha2['data']);
			$sql->adInserir('duracao', $linha2['duracao']);
			$sql->adInserir('percentual', $linha2['percentual']);
			$sql->adInserir('evento_id', $evento_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	}	

?>