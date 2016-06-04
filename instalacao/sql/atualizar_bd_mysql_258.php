<?php
if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$sql->adTabela('msg');
	$sql->adCampo('msg_id, 
	msg_projeto,
  msg_tarefa,
  msg_pratica,
  msg_acao,
  msg_perspectiva,
  msg_tema,
  msg_objetivo,
  msg_fator,
  msg_estrategia,
  msg_meta,
  msg_indicador,
  msg_monitoramento,
  msg_operativo,
  msg_canvas');
	$sql->adOnde('
	msg_projeto IS NOT NULL OR 
  msg_tarefa IS NOT NULL OR 
  msg_pratica IS NOT NULL OR 
  msg_acao IS NOT NULL OR 
  msg_perspectiva IS NOT NULL OR 
  msg_tema IS NOT NULL OR 
  msg_objetivo IS NOT NULL OR 
  msg_fator IS NOT NULL OR 
  msg_estrategia IS NOT NULL OR 
  msg_meta IS NOT NULL OR 
  msg_indicador IS NOT NULL OR 
  msg_monitoramento IS NOT NULL OR 
  msg_operativo IS NOT NULL OR 
  msg_canvas IS NOT NULL
	');
	$lista=$sql->lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('msg_gestao');
		$sql->adInserir('msg_gestao_msg', $linha['msg_id']);
		if (isset($linha['msg_projeto']) && $linha['msg_projeto']) $sql->adInserir('msg_gestao_projeto', $linha['msg_projeto']); 
		if (isset($linha['msg_tarefa']) && $linha['msg_tarefa']) $sql->adInserir('msg_gestao_tarefa', $linha['msg_tarefa']); 
		if (isset($linha['msg_pratica']) && $linha['msg_pratica']) $sql->adInserir('msg_gestao_pratica', $linha['msg_pratica']); 
		if (isset($linha['msg_acao']) && $linha['msg_acao']) $sql->adInserir('msg_gestao_acao', $linha['msg_acao']); 
		if (isset($linha['msg_perspectiva']) && $linha['msg_perspectiva']) $sql->adInserir('msg_gestao_perspectiva', $linha['msg_perspectiva']); 
		if (isset($linha['msg_tema']) && $linha['msg_tema']) $sql->adInserir('msg_gestao_tema', $linha['msg_tema']); 
		if (isset($linha['msg_objetivo']) && $linha['msg_objetivo']) $sql->adInserir('msg_gestao_objetivo', $linha['msg_objetivo']); 
		if (isset($linha['msg_fator']) && $linha['msg_fator']) $sql->adInserir('msg_gestao_fator', $linha['msg_fator']); 
		if (isset($linha['msg_estrategia']) && $linha['msg_estrategia']) $sql->adInserir('msg_gestao_estrategia', $linha['msg_estrategia']); 
		if (isset($linha['msg_meta']) && $linha['msg_meta']) $sql->adInserir('msg_gestao_meta', $linha['msg_meta']); 
		if (isset($linha['msg_indicador']) && $linha['msg_indicador']) $sql->adInserir('msg_gestao_indicador', $linha['msg_indicador']); 
		if (isset($linha['msg_monitoramento']) && $linha['msg_monitoramento']) $sql->adInserir('msg_gestao_monitoramento', $linha['msg_monitoramento']); 	
		if (isset($linha['msg_operativo']) && $linha['msg_operativo']) $sql->adInserir('msg_gestao_operativo', $linha['msg_operativo']); 
		if (isset($linha['msg_canvas']) && $linha['msg_canvas']) $sql->adInserir('msg_gestao_canvas', $linha['msg_canvas']); 
		$sql->exec();
		$sql->Limpar();
		}	
		
	$sql->adTabela('ata');
	$sql->adCampo('ata_id, 
	ata_projeto,
  ata_tarefa,
  ata_pratica,
  ata_meta,
  ata_perspectiva,
  ata_tema,
  ata_objetivo,
  ata_fator,
  ata_indicador,
  ata_estrategia,
  ata_calendario,
  ata_monitoramento,
  ata_acao,
  ata_canvas
	');
	$sql->adOnde('
	ata_projeto IS NOT NULL OR 
  ata_tarefa IS NOT NULL OR 
  ata_pratica IS NOT NULL OR 
  ata_meta IS NOT NULL OR 
  ata_perspectiva IS NOT NULL OR 
  ata_tema IS NOT NULL OR 
  ata_objetivo IS NOT NULL OR 
  ata_fator IS NOT NULL OR 
  ata_indicador IS NOT NULL OR 
  ata_estrategia IS NOT NULL OR 
  ata_calendario IS NOT NULL OR 
  ata_monitoramento IS NOT NULL OR 
  ata_acao IS NOT NULL OR 
  ata_canvas IS NOT NULL
	');
	$lista=$sql->lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('ata_gestao');
		$sql->adInserir('ata_gestao_ata', $linha['ata_id']);
		if (isset($linha['ata_indicador']) && $linha['ata_indicador']) $sql->adInserir('ata_gestao_indicador', $linha['ata_indicador']); 
		if (isset($linha['ata_projeto']) && $linha['ata_projeto']) $sql->adInserir('ata_gestao_projeto', $linha['ata_projeto']); 
		if (isset($linha['ata_tarefa']) && $linha['ata_tarefa']) $sql->adInserir('ata_gestao_tarefa', $linha['ata_tarefa']); 
		if (isset($linha['ata_perspectiva']) && $linha['ata_perspectiva']) $sql->adInserir('ata_gestao_perspectiva', $linha['ata_perspectiva']); 
		if (isset($linha['ata_tema']) && $linha['ata_tema']) $sql->adInserir('ata_gestao_tema', $linha['ata_tema']); 
		if (isset($linha['ata_objetivo']) && $linha['ata_objetivo']) $sql->adInserir('ata_gestao_objetivo', $linha['ata_objetivo']); 
		if (isset($linha['ata_fator']) && $linha['ata_fator']) $sql->adInserir('ata_gestao_fator', $linha['ata_fator']); 
		if (isset($linha['ata_estrategia']) && $linha['ata_estrategia']) $sql->adInserir('ata_gestao_estrategia', $linha['ata_estrategia']); 
		if (isset($linha['ata_meta']) && $linha['ata_meta']) $sql->adInserir('ata_gestao_meta', $linha['ata_meta']); 
		if (isset($linha['ata_acao']) && $linha['ata_acao']) $sql->adInserir('ata_gestao_acao', $linha['ata_acao']); 
		if (isset($linha['ata_pratica']) && $linha['ata_pratica']) $sql->adInserir('ata_gestao_pratica', $linha['ata_pratica']); 
		if (isset($linha['ata_canvas']) && $linha['ata_canvas']) $sql->adInserir('ata_gestao_canvas', $linha['ata_canvas']); 
		if (isset($linha['ata_calendario']) && $linha['ata_calendario']) $sql->adInserir('ata_gestao_calendario', $linha['ata_calendario']); 
		if (isset($linha['ata_monitoramento']) && $linha['ata_monitoramento']) $sql->adInserir('ata_gestao_monitoramento', $linha['ata_monitoramento']); 
		$sql->exec();
		$sql->Limpar();
		}	
		
		
	$sql->adTabela('links');
	$sql->adCampo('link_id, 
	link_projeto,
  link_tarefa,
  link_pratica,
  link_acao,
  link_perspectiva,
  link_tema,
  link_objetivo,
  link_fator,
  link_estrategia,
  link_meta,
  link_indicador,
  link_canvas');
	$sql->adOnde('
	link_projeto IS NOT NULL OR 
  link_tarefa IS NOT NULL OR 
  link_pratica IS NOT NULL OR 
  link_acao IS NOT NULL OR 
  link_perspectiva IS NOT NULL OR 
  link_tema IS NOT NULL OR 
  link_objetivo IS NOT NULL OR 
  link_fator IS NOT NULL OR 
  link_estrategia IS NOT NULL OR 
  link_meta IS NOT NULL OR 
  link_indicador IS NOT NULL OR 
  link_canvas IS NOT NULL
	');
	$lista=$sql->lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('link_gestao');
		$sql->adInserir('link_gestao_link', $linha['link_id']);
		if (isset($linha['link_projeto']) && $linha['link_projeto']) $sql->adInserir('link_gestao_projeto', $linha['link_projeto']); 
		if (isset($linha['link_tarefa']) && $linha['link_tarefa']) $sql->adInserir('link_gestao_tarefa', $linha['link_tarefa']); 
		if (isset($linha['link_pratica']) && $linha['link_pratica']) $sql->adInserir('link_gestao_pratica', $linha['link_pratica']); 
		if (isset($linha['link_acao']) && $linha['link_acao']) $sql->adInserir('link_gestao_acao', $linha['link_acao']); 
		if (isset($linha['link_perspectiva']) && $linha['link_perspectiva']) $sql->adInserir('link_gestao_perspectiva', $linha['link_perspectiva']); 
		if (isset($linha['link_tema']) && $linha['link_tema']) $sql->adInserir('link_gestao_tema', $linha['link_tema']); 
		if (isset($linha['link_objetivo']) && $linha['link_objetivo']) $sql->adInserir('link_gestao_objetivo', $linha['link_objetivo']); 
		if (isset($linha['link_fator']) && $linha['link_fator']) $sql->adInserir('link_gestao_fator', $linha['link_fator']); 
		if (isset($linha['link_estrategia']) && $linha['link_estrategia']) $sql->adInserir('link_gestao_estrategia', $linha['link_estrategia']); 
		if (isset($linha['link_meta']) && $linha['link_meta']) $sql->adInserir('link_gestao_meta', $linha['link_meta']); 
		if (isset($linha['link_indicador']) && $linha['link_indicador']) $sql->adInserir('link_gestao_indicador', $linha['link_indicador']); 
		if (isset($linha['link_canvas']) && $linha['link_canvas']) $sql->adInserir('link_gestao_canvas', $linha['link_canvas']); 
		$sql->exec();
		$sql->Limpar();
		}	
		
	$sql->adTabela('foruns');
	$sql->adCampo('forum_id, 
	forum_projeto,
  forum_tarefa,
  forum_pratica,
  forum_acao,
  forum_perspectiva,
  forum_tema,
  forum_objetivo,
  forum_fator,
  forum_estrategia,
  forum_meta,
  forum_indicador,
  forum_canvas');
	$sql->adOnde('
	forum_projeto IS NOT NULL OR 
  forum_tarefa IS NOT NULL OR 
  forum_pratica IS NOT NULL OR 
  forum_acao IS NOT NULL OR 
  forum_perspectiva IS NOT NULL OR 
  forum_tema IS NOT NULL OR 
  forum_objetivo IS NOT NULL OR 
  forum_fator IS NOT NULL OR 
  forum_estrategia IS NOT NULL OR 
  forum_meta IS NOT NULL OR 
  forum_indicador IS NOT NULL OR 
  forum_canvas IS NOT NULL
	');
	$lista=$sql->lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('forum_gestao');
		$sql->adInserir('forum_gestao_forum', $linha['forum_id']);
		if (isset($linha['forum_projeto']) && $linha['forum_projeto']) $sql->adInserir('forum_gestao_projeto', $linha['forum_projeto']); 
		if (isset($linha['forum_tarefa']) && $linha['forum_tarefa']) $sql->adInserir('forum_gestao_tarefa', $linha['forum_tarefa']); 
		if (isset($linha['forum_pratica']) && $linha['forum_pratica']) $sql->adInserir('forum_gestao_pratica', $linha['forum_pratica']); 
		if (isset($linha['forum_acao']) && $linha['forum_acao']) $sql->adInserir('forum_gestao_acao', $linha['forum_acao']); 
		if (isset($linha['forum_perspectiva']) && $linha['forum_perspectiva']) $sql->adInserir('forum_gestao_perspectiva', $linha['forum_perspectiva']); 
		if (isset($linha['forum_tema']) && $linha['forum_tema']) $sql->adInserir('forum_gestao_tema', $linha['forum_tema']); 
		if (isset($linha['forum_objetivo']) && $linha['forum_objetivo']) $sql->adInserir('forum_gestao_objetivo', $linha['forum_objetivo']); 
		if (isset($linha['forum_fator']) && $linha['forum_fator']) $sql->adInserir('forum_gestao_fator', $linha['forum_fator']); 
		if (isset($linha['forum_estrategia']) && $linha['forum_estrategia']) $sql->adInserir('forum_gestao_estrategia', $linha['forum_estrategia']); 
		if (isset($linha['forum_meta']) && $linha['forum_meta']) $sql->adInserir('forum_gestao_meta', $linha['forum_meta']); 
		if (isset($linha['forum_indicador']) && $linha['forum_indicador']) $sql->adInserir('forum_gestao_indicador', $linha['forum_indicador']); 
		if (isset($linha['forum_canvas']) && $linha['forum_canvas']) $sql->adInserir('forum_gestao_canvas', $linha['forum_canvas']); 
		$sql->exec();
		$sql->Limpar();
		}			
					
	}									
?>