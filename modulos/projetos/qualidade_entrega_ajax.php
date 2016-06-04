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
	
function inserir_entrega($projeto_qualidade_entrega_id, $projeto_id, $projeto_qualidade_entrega_entrega, $projeto_qualidade_entrega_criterio ){
	global $Aplic;
	$sql = new BDConsulta;
	if (!$projeto_qualidade_entrega_id){
	 	$sql->adTabela('projeto_qualidade_entrega');
		$sql->adCampo('count(projeto_qualidade_entrega_id) AS soma');
		$sql->adOnde('projeto_qualidade_entrega_projeto ='.$projeto_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		$sql->adTabela('projeto_qualidade_entrega');
		$sql->adInserir('projeto_qualidade_entrega_projeto', $projeto_id);
		$sql->adInserir('projeto_qualidade_entrega_entrega', previnirXSS(utf8_decode($projeto_qualidade_entrega_entrega)));
		$sql->adInserir('projeto_qualidade_entrega_ordem', $soma_total);
		$sql->adInserir('projeto_qualidade_entrega_criterio', previnirXSS(utf8_decode($projeto_qualidade_entrega_criterio)));
		$sql->adInserir('projeto_qualidade_entrega_data', date('Y-m-d H:i:s'));
		$sql->adInserir('projeto_qualidade_entrega_usuario', $Aplic->usuario_id);
		$sql->exec();
		$sql->Limpar();
		}
	else{
		$sql->adTabela('projeto_qualidade_entrega');
		$sql->adAtualizar('projeto_qualidade_entrega_entrega', previnirXSS(utf8_decode($projeto_qualidade_entrega_entrega)));
		$sql->adAtualizar('projeto_qualidade_entrega_criterio', previnirXSS(utf8_decode($projeto_qualidade_entrega_criterio)));
		$sql->adAtualizar('projeto_qualidade_entrega_data', date('Y-m-d H:i:s'));
		$sql->adAtualizar('projeto_qualidade_entrega_usuario', $Aplic->usuario_id);
		$sql->adOnde('projeto_qualidade_entrega_id = '.$projeto_qualidade_entrega_id);
		$sql->exec();
		$sql->Limpar();
		}
	return true;
	}
$xajax->registerFunction("inserir_entrega");	

function lista_artefatos($projeto_id){
	$saida='';

	$sql = new BDConsulta;
	$sql->adTabela('projeto_qualidade_entrega');
	$sql->adCampo('*');
	$sql->adOnde('projeto_qualidade_entrega_projeto='.(int)$projeto_id);
	$sql->adOrdem('projeto_qualidade_entrega_ordem ASC');
	$entregas=$sql->Lista();

	if ($entregas && count($entregas)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="100%"><tr><th></th><th>&nbsp;Entrega'.(count($entregas)>1 ? 's':'').'&nbsp;</th><th>&nbsp;'.utf8_encode('Critério').(count($entregas)>1 ? 's':'').' de qualidade&nbsp;</th><th></th></tr>';
	foreach ($entregas as $entrega) {
		$saida.='<tr>';
		$saida.='<td nowrap="nowrap" width="40" align="center">';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$entrega['projeto_qualidade_entrega_ordem'].', '.$entrega['projeto_qualidade_entrega_id'].',\'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$entrega['projeto_qualidade_entrega_ordem'].', '.$entrega['projeto_qualidade_entrega_id'].',\'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$entrega['projeto_qualidade_entrega_ordem'].', '.$entrega['projeto_qualidade_entrega_id'].',\'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$entrega['projeto_qualidade_entrega_ordem'].', '.$entrega['projeto_qualidade_entrega_id'].',\'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
		$saida.='</td>';
		$saida.='<td>&nbsp;'.utf8_encode($entrega['projeto_qualidade_entrega_entrega']).'&nbsp;</td><td>&nbsp;'.utf8_encode($entrega['projeto_qualidade_entrega_criterio']).'&nbsp;</td>';
		$saida.='<td width="32" align="center"><a href="javascript: void(0);" onclick="editar_entrega('.$entrega['projeto_qualidade_entrega_id'].');">'.imagem('icones/editar.gif').'</a>';
		$saida.='<a href="javascript: void(0);" onclick="if (confirm(\''.utf8_encode('Tem certeza que deseja excluir esta entrega e critérios de qualidade?').'\')) {excluir_entrega('.$entrega['projeto_qualidade_entrega_id'].');}">'.imagem('icones/remover.png').'</a></td>';
		$saida.='</tr>';
		}
	if ($entregas && count($entregas)) $saida.='</table>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_entregas',"innerHTML", $saida);
	return $objResposta;	
	}

$xajax->registerFunction("lista_artefatos");	
		
function mudar_ordem($ordem, $projeto_qualidade_entrega_id, $direcao, $projeto_id){
	
		$sql = new BDConsulta;
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_qualidade_entrega');
		$sql->adOnde('projeto_qualidade_entrega_id != '.(int)$projeto_qualidade_entrega_id);
		$sql->adOnde('projeto_qualidade_entrega_projeto = '.(int)$projeto_id);
		$sql->adOrdem('projeto_qualidade_entrega_ordem');
		$entregas = $sql->Lista();
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
			$novo_ui_ordem = count($entregas) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($entregas) + 1)) {
			$sql->adTabela('projeto_qualidade_entrega');
			$sql->adAtualizar('projeto_qualidade_entrega_ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_qualidade_entrega_id = '.(int)$projeto_qualidade_entrega_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($entregas as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_qualidade_entrega');
					$sql->adAtualizar('projeto_qualidade_entrega_ordem', $idx);
					$sql->adOnde('projeto_qualidade_entrega_id = '.(int)$acao['projeto_qualidade_entrega_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_qualidade_entrega');
					$sql->adAtualizar('projeto_qualidade_entrega_ordem', $idx + 1);
					$sql->adOnde('projeto_qualidade_entrega_id = '.(int)$acao['projeto_qualidade_entrega_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
	return true;
	}	
$xajax->registerFunction("mudar_ordem");		

function excluir_entrega($projeto_qualidade_entrega_id){
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_qualidade_entrega');
	$sql->adOnde('projeto_qualidade_entrega_id='.(int)$projeto_qualidade_entrega_id);
	$sql->exec();
	$sql->limpar();	
	return true;
	}	
$xajax->registerFunction("excluir_entrega");	

function editar_entrega($projeto_qualidade_entrega_id){
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('projeto_qualidade_entrega');
	$sql->adCampo('projeto_qualidade_entrega_entrega, projeto_qualidade_entrega_criterio');
	$sql->adOnde('projeto_qualidade_entrega_id='.$projeto_qualidade_entrega_id);
	$entrega=$sql->Linha();
	$saida.='<table cellpadding=0 cellspacing="2"><tr><td><b>Entrega</b></td><td><b>'.utf8_encode('Critérios').' de qualidade</b></td><td></td></tr>';
	$saida.='<tr><td valign=top><input type="text" name="projeto_qualidade_entrega_entrega" id="projeto_qualidade_entrega_entrega" size="50" class="texto" value="'.utf8_encode($entrega['projeto_qualidade_entrega_entrega']).'" /></td>';
	$saida.='<td valign=top><textarea name="projeto_qualidade_entrega_criterio" id="projeto_qualidade_entrega_criterio" style="width:500px;" class="textarea">'.utf8_encode($entrega['projeto_qualidade_entrega_criterio']).'</textarea></td>';
	$saida.='<td><a href="javascript:void(0);" onclick="javascript:inserir_entrega('.$projeto_qualidade_entrega_id.');">'.imagem('icones/ok.png').'</a><a href="javascript:void(0);" onclick="javascript:cancelar_edicao();">'.imagem('icones/cancelar.png').'</a></td></tr>';
	$saida.='</table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;	
	}	
	
$xajax->registerFunction("editar_entrega");		



function cancelar_edicao(){
	$saida=utf8_encode('<table cellpadding=0 cellspacing="2"><tr><td><b>Entrega</b></td><td><b>Critérios de qualidade</b></td><td></td></tr><tr><td valign=top><input type="text" name="projeto_qualidade_entrega_entrega" id="projeto_qualidade_entrega_entrega" value="" size="50" class="texto" /></td><td valign=top><textarea name="projeto_qualidade_entrega_criterio" id="projeto_qualidade_entrega_criterio" style="width:500px;" class="textarea"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_entrega(0);">'.imagem("icones/adicionar.png").'</a></td></tr></table>');
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;	
	}		
$xajax->registerFunction("cancelar_edicao");

$xajax->processRequest();

?>