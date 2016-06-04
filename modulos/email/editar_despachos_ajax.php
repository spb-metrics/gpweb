<?php

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);
  
function mudar_posicao_despacho_ajax($despacho_ordem, $despacho_id, $direcao, $despacho_usuario=0){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$despacho_id) {
		$novo_ui_despacho_ordem = $despacho_ordem;
		$sql->adTabela('despacho');
		$sql->adOnde('despacho_id != '.$despacho_id);
		$sql->adOnde('despacho_usuario = '.$despacho_usuario);
		$sql->adOrdem('despacho_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_despacho_ordem;
			$novo_ui_despacho_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_despacho_ordem;
			$novo_ui_despacho_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_despacho_ordem;
			$novo_ui_despacho_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_despacho_ordem;
			$novo_ui_despacho_ordem = count($membros) + 1;
			}
		if ($novo_ui_despacho_ordem && ($novo_ui_despacho_ordem <= count($membros) + 1)) {
			$sql->adTabela('despacho');
			$sql->adAtualizar('despacho_ordem', $novo_ui_despacho_ordem);
			$sql->adOnde('despacho_id = '.$despacho_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_despacho_ordem) {
					$sql->adTabela('despacho');
					$sql->adAtualizar('despacho_ordem', $idx);
					$sql->adOnde('despacho_id = '.$acao['despacho_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('despacho');
					$sql->adAtualizar('despacho_ordem', $idx + 1);
					$sql->adOnde('despacho_id = '.$acao['despacho_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_despachos($despacho_usuario);
	$objResposta = new xajaxResponse();
	$objResposta->assign("despachos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_despacho_ajax");		
	

function incluir_despacho_ajax($despacho_usuario=0, $despacho_id=0, $despacho_nome='', $despacho_texto='', $despacho_anotacao=false, $despacho_despacho=false, $despacho_resposta=false){
	$sql = new BDConsulta;

	$despacho_nome=previnirXSS(utf8_decode($despacho_nome));
	$despacho_texto=previnirXSS(utf8_decode($despacho_texto));
	
	//verificar se já existe
	$sql->adTabela('despacho');
	$sql->adCampo('count(despacho_id) AS soma');
	$sql->adOnde('despacho_usuario ='.(int)$despacho_usuario);	
	$sql->adOnde('despacho_id ='.(int)$despacho_id);	
  $ja_existe = (int)$sql->Resultado();
  $sql->Limpar();

	if ($ja_existe){
		$sql->adTabela('despacho');
		$sql->adAtualizar('despacho_nome', $despacho_nome);
		$sql->adAtualizar('despacho_texto', $despacho_texto);
		
		$sql->adAtualizar('despacho_anotacao', ($despacho_anotacao ? 1 : 0));
		$sql->adAtualizar('despacho_despacho', ($despacho_despacho ? 1 : 0));
		$sql->adAtualizar('despacho_resposta', ($despacho_resposta ? 1 : 0));
		
		$sql->adOnde('despacho_id ='.(int)$despacho_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('despacho');
		$sql->adCampo('count(despacho_id) AS soma');
		$sql->adOnde('despacho_usuario ='.(int)$despacho_usuario);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('despacho');
		$sql->adInserir('despacho_usuario', (int)$despacho_usuario);
		$sql->adInserir('despacho_ordem', (int)$soma_total);
		$sql->adInserir('despacho_id', (int)$despacho_id);
		$sql->adInserir('despacho_nome', $despacho_nome);
		$sql->adInserir('despacho_texto', $despacho_texto);
		$sql->adInserir('despacho_anotacao', ($despacho_anotacao ? 1 : 0));
		$sql->adInserir('despacho_despacho', ($despacho_despacho ? 1 : 0));
		$sql->adInserir('despacho_resposta', ($despacho_resposta ? 1 : 0));
		$sql->exec();
		}
	$saida=atualizar_despachos($despacho_usuario);
	$objResposta = new xajaxResponse();
	$objResposta->assign("despachos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_despacho_ajax");	


function excluir_despacho_ajax($despacho_id, $despacho_usuario){
	$sql = new BDConsulta;
	$sql->setExcluir('despacho');
	$sql->adOnde('despacho_id='.$despacho_id);
	$sql->exec();
	$saida=atualizar_despachos($despacho_usuario);
	$objResposta = new xajaxResponse();
	$objResposta->assign("despachos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_despacho_ajax");	


function atualizar_despachos($despacho_usuario){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('despacho');
	$sql->adCampo('despacho.*');
	$sql->adOnde('despacho_usuario = '.(int)$despacho_usuario);
	$sql->adOrdem('despacho_ordem');
	$despachos=$sql->ListaChave('despacho_id');
	$sql->limpar();
	$saida='';
	if (count($despachos)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>Nome</th><th>Texto</th><th>Anotação</th><th>Despacho</th><th>Resposta</th><th></th></tr>';
		foreach ($despachos as $despacho_id => $linha) {
			
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_despacho('.$linha['despacho_ordem'].', '.$linha['despacho_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_despacho('.$linha['despacho_ordem'].', '.$linha['despacho_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_despacho('.$linha['despacho_ordem'].', '.$linha['despacho_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_despacho('.$linha['despacho_ordem'].', '.$linha['despacho_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left">'.$linha['despacho_nome'].'</td>';
			$saida.= '<td align="left">'.$linha['despacho_texto'].'</td>';
			
			$saida.= '<td align="center">'.($linha['despacho_anotacao'] ? 'X' : '&nbsp;').'</td>';
			$saida.= '<td align="center">'.($linha['despacho_despacho'] ? 'X' : '&nbsp;').'</td>';
			$saida.= '<td align="center">'.($linha['despacho_resposta'] ? 'X' : '&nbsp;').'</td>';
			
			
			$saida.= '<td width=32><a href="javascript: void(0);" onclick="editar_despacho('.$linha['despacho_id'].');">'.imagem('icones/editar.gif', 'Editar Entrega', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a despacho.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta despacho?\')) {excluir_despacho('.$linha['despacho_id'].');}">'.imagem('icones/remover.png', 'Excluir Entrega', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta despacho.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}	

$xajax->registerFunction("atualizar_despachos");		
	
function editar_despacho($despacho_id){
	global $config;
	
	$objResposta = new xajaxResponse();
	
	$sql = new BDConsulta;

	$sql->adTabela('despacho');
	$sql->adCampo('despacho.*');
	$sql->adOnde('despacho_id = '.(int)$despacho_id);
	$sql->adOrdem('despacho_ordem');
	$linha=$sql->Linha();
	$sql->limpar();
	$saida='';	
	$objResposta->assign("despacho_id","value", $despacho_id);
	
	$objResposta->assign("despacho_anotacao","checked", ($linha['despacho_anotacao'] ? true : false) );
	$objResposta->assign("despacho_despacho","checked", ($linha['despacho_despacho'] ? true : false) );
	$objResposta->assign("despacho_resposta","checked", ($linha['despacho_resposta'] ? true : false) );
	
	$objResposta->assign("despacho_nome","value", utf8_encode($linha['despacho_nome']));
	$objResposta->assign("apoio1","value", utf8_encode($linha['despacho_texto']));		
	return $objResposta;
	}	

$xajax->registerFunction("editar_despacho");	

$xajax->processRequest();
?>