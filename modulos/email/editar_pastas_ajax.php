<?php

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
  
function mudar_posicao_pasta_ajax($pasta_ordem, $pasta_id, $direcao, $usuario_id=0){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$pasta_id) {
		$novo_ui_pasta_ordem = $pasta_ordem;
		$sql->adTabela('pasta');
		$sql->adOnde('pasta_id != '.$pasta_id);
		$sql->adOnde('usuario_id = '.$usuario_id);
		$sql->adOrdem('pasta_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_pasta_ordem;
			$novo_ui_pasta_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_pasta_ordem;
			$novo_ui_pasta_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_pasta_ordem;
			$novo_ui_pasta_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_pasta_ordem;
			$novo_ui_pasta_ordem = count($membros) + 1;
			}
		if ($novo_ui_pasta_ordem && ($novo_ui_pasta_ordem <= count($membros) + 1)) {
			$sql->adTabela('pasta');
			$sql->adAtualizar('pasta_ordem', $novo_ui_pasta_ordem);
			$sql->adOnde('pasta_id = '.$pasta_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_pasta_ordem) {
					$sql->adTabela('pasta');
					$sql->adAtualizar('pasta_ordem', $idx);
					$sql->adOnde('pasta_id = '.$acao['pasta_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('pasta');
					$sql->adAtualizar('pasta_ordem', $idx + 1);
					$sql->adOnde('pasta_id = '.$acao['pasta_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_pastas($usuario_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("pastas","innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_pasta_ajax");		
	

function incluir_pasta_ajax($usuario_id=0, $pasta_id=0, $nome=''){
	$sql = new BDConsulta;

	$nome=previnirXSS(utf8_decode($nome));
	
	//verificar se já existe
	$sql->adTabela('pasta');
	$sql->adCampo('count(pasta_id) AS soma');
	$sql->adOnde('usuario_id ='.(int)$usuario_id);	
	$sql->adOnde('pasta_id ='.(int)$pasta_id);	
  $ja_existe = (int)$sql->Resultado();
  $sql->Limpar();

	if ($ja_existe){
		$sql->adTabela('pasta');
		$sql->adAtualizar('nome', $nome);
		$sql->adOnde('pasta_id ='.(int)$pasta_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('pasta');
		$sql->adCampo('count(pasta_id) AS soma');
		$sql->adOnde('usuario_id ='.(int)$usuario_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('pasta');
		$sql->adInserir('usuario_id', (int)$usuario_id);
		$sql->adInserir('pasta_ordem', (int)$soma_total);
		$sql->adInserir('pasta_id', (int)$pasta_id);
		$sql->adInserir('nome', $nome);
		$sql->exec();
		}
	$saida=atualizar_pastas($usuario_id);
	$objResposta = new xajaxResponse();
	
	//$saida=$usuario_id.' | '.$pasta_id.'|'.$nome;
	
	$objResposta->assign("pastas","innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("incluir_pasta_ajax");	


function excluir_pasta_ajax($pasta_id, $usuario_id){
	$sql = new BDConsulta;
	$sql->setExcluir('pasta');
	$sql->adOnde('pasta_id='.$pasta_id);
	$sql->exec();
	$saida=atualizar_pastas($usuario_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("pastas","innerHTML", $saida);
	return $objResposta;
	}

$xajax->registerFunction("excluir_pasta_ajax");	


function atualizar_pastas($usuario_id){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('pasta');
	$sql->adCampo('pasta.*');
		$sql->adOnde('usuario_id = '.(int)$usuario_id);
	$sql->adOrdem('pasta_ordem');
	$pastas=$sql->ListaChave('pasta_id');
	$sql->limpar();
	$saida='';
	if (count($pastas)) {
		$saida.= '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellpadding=0 cellspacing=0 class="tbl1" align=left><tr><th></th><th>'.utf8_encode('Nome').'</th><th></th></tr>';
		foreach ($pastas as $pasta_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pasta('.$linha['pasta_ordem'].', '.$linha['pasta_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pasta('.$linha['pasta_ordem'].', '.$linha['pasta_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pasta('.$linha['pasta_ordem'].', '.$linha['pasta_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pasta('.$linha['pasta_ordem'].', '.$linha['pasta_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
			$saida.= '</td>';
			$saida.= '<td align="left">'.utf8_encode($linha['nome']).'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="editar_pasta('.$linha['pasta_id'].');">'.imagem('icones/editar.gif').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta pasta?\')) {excluir_pasta('.$linha['pasta_id'].');}">'.imagem('icones/remover.png').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table></td></tr></table>';
		}
	return $saida;
	}	

$xajax->registerFunction("atualizar_pastas");		
	
function editar_pasta($pasta_id){
	global $config;
	
	$objResposta = new xajaxResponse();
	
	$sql = new BDConsulta;

	$sql->adTabela('pasta');
	$sql->adCampo('nome');
	$sql->adOnde('pasta_id = '.(int)$pasta_id);
	$sql->adOrdem('pasta_ordem');
	$linha=$sql->Linha();
	$sql->limpar();
	$saida='';	
	$objResposta->assign("pasta_id","value", $pasta_id);
	$objResposta->assign("nome","value", utf8_encode($linha['nome']));	
	return $objResposta;
	}	

$xajax->registerFunction("editar_pasta");	

$xajax->processRequest();
?>