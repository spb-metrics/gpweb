<?php

$sql = new BDConsulta;

$sql->adTabela('arquivos');
$sql->adCampo('arquivos.*');
$lista=$sql->lista();
$sql->Limpar();
foreach($lista as $linha){
	$sql->adTabela('arquivos');
	if ($linha['arquivo_projeto']) $sql->adAtualizar('arquivo_local', 'projetos/'.$linha['arquivo_projeto'].'/'); 
	elseif ($linha['arquivo_pratica']) $sql->adAtualizar('arquivo_local', 'praticas/'.$linha['arquivo_pratica'].'/'); 
	elseif ($linha['arquivo_acao']) $sql->adAtualizar('arquivo_local', 'planos_acao/'.$linha['arquivo_acao'].'/'); 
	elseif ($linha['arquivo_indicador']) $sql->adAtualizar('arquivo_local', 'indicadores/'.$linha['arquivo_indicador'].'/'); 
	elseif ($linha['arquivo_usuario']) $sql->adAtualizar('arquivo_local', 'usuarios/'.$linha['arquivo_usuario'].'/'); 
	elseif ($linha['arquivo_objetivo']) $sql->adAtualizar('arquivo_local', 'objetivos/'.$linha['arquivo_objetivo'].'/'); 
	elseif ($linha['arquivo_perspectiva']) $sql->adAtualizar('arquivo_local', 'perspectivas/'.$linha['arquivo_perspectiva'].'/'); 
	elseif ($linha['arquivo_tema']) $sql->adAtualizar('arquivo_local', 'temas/'.$linha['arquivo_tema'].'/'); 
	elseif ($linha['arquivo_fator']) $sql->adAtualizar('arquivo_local', 'fatores/'.$linha['arquivo_fator'].'/'); 
	elseif ($linha['arquivo_estrategia']) $sql->adAtualizar('arquivo_local', 'estrategias/'.$linha['arquivo_estrategia'].'/'); 
	elseif ($linha['arquivo_meta']) $sql->adAtualizar('arquivo_local', 'metas/'.$linha['arquivo_meta'].'/'); 
	elseif ($linha['arquivo_demanda']) $sql->adAtualizar('arquivo_local', 'projetos/'.$linha['arquivo_demanda'].'/'); 
	elseif ($linha['arquivo_instrumento']) $sql->adAtualizar('arquivo_local', 'demandas/'.$linha['arquivo_instrumento'].'/'); 
	elseif ($linha['arquivo_calendario']) $sql->adAtualizar('arquivo_local', 'calendarios/'.$linha['arquivo_calendario'].'/'); 
	elseif ($linha['arquivo_ata']) $sql->adAtualizar('arquivo_local', 'atas/'.$linha['arquivo_ata'].'/'); 
	elseif ($linha['arquivo_canvas']) $sql->adAtualizar('arquivo_local', 'canvas/'.$linha['arquivo_canvas'].'/'); 
	else $sql->adAtualizar('arquivo_local', 'generico/'.$linha['arquivo_cia'].'/'); 
	$sql->adOnde('arquivo_id='.$linha['arquivo_id']);
	$sql->exec();
	$sql->limpar();
	}	
			
							
?>