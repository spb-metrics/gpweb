<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);



function mudar_posicao_causa($pratica_indicador_avaliacao_ordem, $pratica_indicador_avaliacao_id, $direcao, $pratica_indicador_id=null){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$pratica_indicador_avaliacao_id) {
		$novo_ui_ordem = $pratica_indicador_avaliacao_ordem;
		$sql->adTabela('pratica_indicador_avaliacao');
		$sql->adOnde('pratica_indicador_avaliacao_id != '.(int)$pratica_indicador_avaliacao_id);
		$sql->adOnde('pratica_indicador_avaliacao_indicador = '.(int)$pratica_indicador_id);
		$sql->adOrdem('pratica_indicador_avaliacao_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('pratica_indicador_avaliacao');
			$sql->adAtualizar('pratica_indicador_avaliacao_ordem', $novo_ui_ordem);
			$sql->adOnde('pratica_indicador_avaliacao_id = '.(int)$pratica_indicador_avaliacao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('pratica_indicador_avaliacao');
					$sql->adAtualizar('pratica_indicador_avaliacao_ordem', $idx);
					$sql->adOnde('pratica_indicador_avaliacao_id = '.(int)$acao['pratica_indicador_avaliacao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('pratica_indicador_avaliacao');
					$sql->adAtualizar('pratica_indicador_avaliacao_ordem', $idx + 1);
					$sql->adOnde('pratica_indicador_avaliacao_id = '.(int)$acao['pratica_indicador_avaliacao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_causas($pratica_indicador_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_causa","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_causa");	

function incluir_causa(
		$pratica_indicador_avaliacao_id=null, 
		$pratica_indicador_avaliacao_indicador,
		$pratica_indicador_avaliacao_sucesso=null, 
		$pratica_indicador_avaliacao_causa='', 
		$pratica_indicador_avaliacao_sanar=''
		){
	$sql = new BDConsulta;
	$pratica_indicador_avaliacao_causa=previnirXSS(utf8_decode($pratica_indicador_avaliacao_causa));
	$pratica_indicador_avaliacao_sanar=previnirXSS(utf8_decode($pratica_indicador_avaliacao_sanar));
	if ($pratica_indicador_avaliacao_id){
		$sql->adTabela('pratica_indicador_avaliacao');
		$sql->adAtualizar('pratica_indicador_avaliacao_sucesso', $pratica_indicador_avaliacao_sucesso);
		$sql->adAtualizar('pratica_indicador_avaliacao_causa', $pratica_indicador_avaliacao_causa);
		$sql->adAtualizar('pratica_indicador_avaliacao_sanar', $pratica_indicador_avaliacao_sanar);
		$sql->adOnde('pratica_indicador_avaliacao_id ='.(int)$pratica_indicador_avaliacao_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('pratica_indicador_avaliacao');
		$sql->adCampo('count(pratica_indicador_avaliacao_id) AS soma');
		$sql->adOnde('pratica_indicador_avaliacao_indicador ='.(int)$pratica_indicador_avaliacao_indicador);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		$sql->adTabela('pratica_indicador_avaliacao');
		$sql->adInserir('pratica_indicador_avaliacao_indicador', $pratica_indicador_avaliacao_indicador);
		$sql->adInserir('pratica_indicador_avaliacao_ordem', $soma_total);
		$sql->adInserir('pratica_indicador_avaliacao_sucesso', $pratica_indicador_avaliacao_sucesso);
		$sql->adInserir('pratica_indicador_avaliacao_causa', $pratica_indicador_avaliacao_causa);
		$sql->adInserir('pratica_indicador_avaliacao_sanar', $pratica_indicador_avaliacao_sanar);
		$sql->adInserir('pratica_indicador_avaliacao_indicador', $pratica_indicador_avaliacao_indicador);
		$sql->exec();
		}
	$saida=atualizar_causas($pratica_indicador_avaliacao_indicador);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_causa","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_causa");

function excluir_causa($pratica_indicador_avaliacao_id=null, $pratica_indicador_avaliacao_indicador=null){
	$sql = new BDConsulta;
	$sql->setExcluir('pratica_indicador_avaliacao');
	$sql->adOnde('pratica_indicador_avaliacao_id='.(int)$pratica_indicador_avaliacao_id);
	$sql->exec();
	$saida=atualizar_causas($pratica_indicador_avaliacao_indicador);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_causa","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("excluir_causa");	


function atualizar_causas($pratica_indicador_avaliacao_indicador=null){
	global $config;
	$sql = new BDConsulta;
	$saida='';
	$sql->adTabela('pratica_indicador_avaliacao');
	$sql->adCampo('pratica_indicador_avaliacao.*');
	$sql->adOnde('pratica_indicador_avaliacao_indicador = '.(int)$pratica_indicador_avaliacao_indicador);
	$sql->adOrdem('pratica_indicador_avaliacao_ordem');
	$causas=$sql->Lista();
	$sql->limpar();
	$saida.= '<tr><td colspan=20 align=left><div id="combo_causa">';
	if (count($causas)) {
		$saida.= '<table cellspacing=0 cellpadding=2 class="tbl1" align=center>';
		$saida.= '<tr><td>&nbsp;</td><td style="font-size:10pt; font-weight:bold" align=center>Sucesso</td><td style="font-size:10pt; font-weight:bold" align=center>Insucesso</td><td style="font-size:10pt; font-weight:bold" align=center>Causa</td><td style="font-size:10pt; font-weight:bold" align=center>Medidas para Sanar</td><td>&nbsp</td></tr>';
		foreach ($causas as $causa) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_causa('.$causa['pratica_indicador_avaliacao_ordem'].', '.$causa['pratica_indicador_avaliacao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_causa('.$causa['pratica_indicador_avaliacao_ordem'].', '.$causa['pratica_indicador_avaliacao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_causa('.$causa['pratica_indicador_avaliacao_ordem'].', '.$causa['pratica_indicador_avaliacao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_causa('.$causa['pratica_indicador_avaliacao_ordem'].', '.$causa['pratica_indicador_avaliacao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td style="font-size:10pt;" align=center>'.($causa['pratica_indicador_avaliacao_sucesso'] ? '<b>X</b>' : '&nbsp;').'</td>';
			$saida.= '<td style="font-size:10pt;" align=center>'.(!$causa['pratica_indicador_avaliacao_sucesso'] ? '<b>X</b>' : '&nbsp;').'</td>';
			$saida.= '<td style="font-size:10pt;" align=left>'.($causa['pratica_indicador_avaliacao_causa'] ? $causa['pratica_indicador_avaliacao_causa'] : '&nbsp;').'</td>';
			$saida.= '<td style="font-size:10pt;" align=left>'.($causa['pratica_indicador_avaliacao_sanar'] ? $causa['pratica_indicador_avaliacao_sanar'] : '&nbsp;').'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="editar_causa('.$causa['pratica_indicador_avaliacao_id'].');">'.imagem('icones/editar.gif', 'Editar Integrante', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o contato causa com '.$config['genero_projeto'].' '.$config['projeto'].'.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_causa('.$causa['pratica_indicador_avaliacao_id'].');}">'.imagem('icones/remover.png', 'Excluir Integrante', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	
	return $saida;
	}


function editar_causa($pratica_indicador_avaliacao_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('pratica_indicador_avaliacao');
	$sql->adCampo('pratica_indicador_avaliacao.*');
	$sql->adOnde('pratica_indicador_avaliacao_id = '.(int)$pratica_indicador_avaliacao_id);
	$linha=$sql->Linha();
	$sql->limpar();
	$objResposta->assign("pratica_indicador_avaliacao_id","value", $pratica_indicador_avaliacao_id);
	if ($linha['pratica_indicador_avaliacao_sucesso']){
		$objResposta->assign("sucesso","value", utf8_encode($linha['pratica_indicador_avaliacao_causa']));
		$objResposta->assign("insucesso","value", '');
		}
	else {
		$objResposta->assign("insucesso","value", utf8_encode($linha['pratica_indicador_avaliacao_causa']));
		$objResposta->assign("sucesso","value", '');
		}
	
	$objResposta->assign("pratica_indicador_avaliacao_sanar","value", utf8_encode($linha['pratica_indicador_avaliacao_sanar']));
	$objResposta->assign("apoio1","value", utf8_encode($linha['pratica_indicador_avaliacao_sanar']));
	
	
	return $objResposta;
	}	
$xajax->registerFunction("editar_causa");	

$xajax->processRequest();

?>