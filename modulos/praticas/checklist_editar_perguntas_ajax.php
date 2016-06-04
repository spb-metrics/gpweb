<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
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

function mudar_posicao_pergunta_ajax($checklist_lista_ordem, $checklist_lista_id, $direcao, $checklist_lista_checklist_id=0){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $checklist_lista_id) {
		$novo_ui_checklist_lista_ordem = $checklist_lista_ordem;
		$sql->adTabela('checklist_lista');
		$sql->adOnde('checklist_lista_id != '.$checklist_lista_id);
		$sql->adOnde('checklist_lista_checklist_id = '.$checklist_lista_checklist_id);
		$sql->adOrdem('checklist_lista_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_checklist_lista_ordem;
			$novo_ui_checklist_lista_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_checklist_lista_ordem;
			$novo_ui_checklist_lista_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_checklist_lista_ordem;
			$novo_ui_checklist_lista_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_checklist_lista_ordem;
			$novo_ui_checklist_lista_ordem = count($membros) + 1;
			}
		if ($novo_ui_checklist_lista_ordem && ($novo_ui_checklist_lista_ordem <= count($membros) + 1)) {
			$sql->adTabela('checklist_lista');
			$sql->adAtualizar('checklist_lista_ordem', $novo_ui_checklist_lista_ordem);
			$sql->adOnde('checklist_lista_id = '.$checklist_lista_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_checklist_lista_ordem) {
					$sql->adTabela('checklist_lista');
					$sql->adAtualizar('checklist_lista_ordem', $idx);
					$sql->adOnde('checklist_lista_id = '.$acao['checklist_lista_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('checklist_lista');
					$sql->adAtualizar('checklist_lista_ordem', $idx + 1);
					$sql->adOnde('checklist_lista_id = '.$acao['checklist_lista_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_perguntas($checklist_lista_checklist_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perguntas","innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_pergunta_ajax");		
	

function incluir_pergunta_ajax($checklist_lista_checklist_id, $checklist_lista_id, $checklist_lista_peso='', $checklist_lista_descricao='', $checklist_lista_legenda=false){
	$sql = new BDConsulta;

	$checklist_lista_peso=previnirXSS(utf8_decode($checklist_lista_peso));
	$checklist_lista_descricao=previnirXSS(utf8_decode($checklist_lista_descricao));
	
	//verificar se já existe
	$sql->adTabela('checklist_lista');
	$sql->adCampo('count(checklist_lista_id) AS soma');
	$sql->adOnde('checklist_lista_checklist_id ='.(int)$checklist_lista_checklist_id);	
	$sql->adOnde('checklist_lista_id ='.(int)$checklist_lista_id);	
  $ja_existe = (int)$sql->Resultado();
  $sql->Limpar();

	if ($ja_existe){
		$sql->adTabela('checklist_lista');
		$sql->adAtualizar('checklist_lista_peso', $checklist_lista_peso);
		$sql->adAtualizar('checklist_lista_descricao', $checklist_lista_descricao);
		$sql->adAtualizar('checklist_lista_legenda', ($checklist_lista_legenda ? 1 : 0));
		$sql->adOnde('checklist_lista_id ='.$checklist_lista_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('checklist_lista');
		$sql->adCampo('count(checklist_lista_id) AS soma');
		$sql->adOnde('checklist_lista_checklist_id ='.$checklist_lista_checklist_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('checklist_lista');
		$sql->adInserir('checklist_lista_checklist_id', $checklist_lista_checklist_id);
		$sql->adInserir('checklist_lista_ordem', $soma_total);
		$sql->adInserir('checklist_lista_id', $checklist_lista_id);
		$sql->adInserir('checklist_lista_peso', $checklist_lista_peso);
		$sql->adInserir('checklist_lista_descricao', $checklist_lista_descricao);
		$sql->adInserir('checklist_lista_legenda', ($checklist_lista_legenda ? 1 : 0));
		$sql->exec();
		$sql->Limpar();
		}
	$saida=atualizar_perguntas($checklist_lista_checklist_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perguntas","innerHTML", $saida);
	
	
	$sql->adTabela('checklist_lista');
	$sql->adCampo('count(checklist_lista_id)');
	$sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_lista_checklist_id);
	$quantidade=$sql->Resultado();
	$sql->limpar();
	$objResposta->assign("perguntas_quantidade","value", $quantidade);
	
	
	
	return $objResposta;
	}
$xajax->registerFunction("incluir_pergunta_ajax");	


function excluir_pergunta_ajax($checklist_lista_id, $checklist_lista_checklist_id){
	$objResposta = new xajaxResponse();
	
	$sql = new BDConsulta;
	$sql->setExcluir('checklist_lista');
	$sql->adOnde('checklist_lista_id='.$checklist_lista_id);
	$sql->exec();
	$sql->Limpar();
	$saida=atualizar_perguntas($checklist_lista_checklist_id);
	$objResposta->assign("perguntas","innerHTML", $saida);
	
	$sql->adTabela('checklist_lista');
	$sql->adCampo('count(checklist_lista_id)');
	$sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_lista_checklist_id);
	$quantidade=$sql->Resultado();
	$sql->limpar();
	$objResposta->assign("perguntas_quantidade","value", $quantidade);
	
	return $objResposta;
	}

$xajax->registerFunction("excluir_pergunta_ajax");	


function atualizar_perguntas($checklist_lista_checklist_id){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('checklist_lista');
	$sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_lista_checklist_id);
	$sql->adCampo('checklist_lista.*');
	$sql->adOrdem('checklist_lista_ordem');
	$perguntas=$sql->ListaChave('checklist_lista_id');
	$sql->limpar();
	$saida='';
	$FPTITipoFluxo=getSisValor('FPTITipoFluxo');
	$FPTIFomento=getSisValor('FPTIFomento','','','sisvalor_id');
	if (count($perguntas)) {
		$saida.= '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>Peso</th><th>Pergunta</th><th width=32></th></tr>';
		foreach ($perguntas as $checklist_lista_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
			$saida.= '</td>';
			if (!$linha['checklist_lista_legenda']) $saida.= '<td align="left" nowrap="nowrap">'.utf8_encode((int)$linha['checklist_lista_peso']).'</td>';
			$saida.= '<td align="left" '.($linha['checklist_lista_legenda'] ? 'colspan=2' : '').'>'.utf8_encode($linha['checklist_lista_descricao']).'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="editar_pergunta('.$linha['checklist_lista_id'].');">'.imagem('icones/editar.gif').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este pergunta de entrada?\')) {excluir_pergunta('.$linha['checklist_lista_id'].');}">'.imagem('icones/remover.png').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table></td></tr></table>';
		}
	return $saida;
	}	

$xajax->registerFunction("atualizar_perguntas");		
	
function editar_pergunta($checklist_lista_id){
	global $config;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('checklist_lista');
	$sql->adCampo('checklist_lista.*');
	$sql->adOnde('checklist_lista_id = '.(int)$checklist_lista_id);
	$sql->adOrdem('checklist_lista_ordem');
	$linha=$sql->Linha();
	$sql->limpar();
	$saida='';	
	$objResposta->assign("checklist_lista_id","value", $checklist_lista_id);
	$objResposta->assign("checklist_lista_peso","value", utf8_encode((int)$linha['checklist_lista_peso']));
	$objResposta->assign("texto_apoio","value", utf8_encode($linha['checklist_lista_descricao']));	
	$objResposta->assign("checklist_lista_legenda","checked", ($linha['checklist_lista_legenda'] ? true : false));	
	return $objResposta;
	}	

$xajax->registerFunction("editar_pergunta");	


$xajax->processRequest();


?>