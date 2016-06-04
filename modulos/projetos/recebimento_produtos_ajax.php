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
	
function inserir_tipo($projeto_recebimento_lista_id, $projeto_recebimento_lista_recebimento_id, $projeto_recebimento_lista_produto=null){
	global $Aplic;
	$sql = new BDConsulta;
	
	if (!$projeto_recebimento_lista_id){
	 	$sql->adTabela('projeto_recebimento_lista');
		$sql->adCampo('count(projeto_recebimento_lista_id) AS soma');
		$sql->adOnde('projeto_recebimento_lista_recebimento_id ='.$projeto_recebimento_lista_recebimento_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		$sql->adTabela('projeto_recebimento_lista');
		$sql->adInserir('projeto_recebimento_lista_ordem', $soma_total);
		$sql->adInserir('projeto_recebimento_lista_recebimento_id', $projeto_recebimento_lista_recebimento_id);
		$sql->adInserir('projeto_recebimento_lista_data', date('Y-m-d H:i:s'));
		$sql->adInserir('projeto_recebimento_lista_produto', previnirXSS(utf8_decode($projeto_recebimento_lista_produto)));
		$sql->exec();
		$sql->Limpar();
		}
	else{
		$sql->adTabela('projeto_recebimento_lista');
		$sql->adAtualizar('projeto_recebimento_lista_data', date('Y-m-d H:i:s'));
		$sql->adAtualizar('projeto_recebimento_lista_produto', previnirXSS(utf8_decode($projeto_recebimento_lista_produto)));
		$sql->adOnde('projeto_recebimento_lista_id = '.$projeto_recebimento_lista_id);
		$sql->exec();
		$sql->Limpar();
		}
	return true;
	}
$xajax->registerFunction("inserir_tipo");	

function lista_artefatos($projeto_recebimento_lista_recebimento_id){
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('projeto_recebimento_lista');
	$sql->adOnde('projeto_recebimento_lista_recebimento_id='.(int)$projeto_recebimento_lista_recebimento_id);
	$sql->adOrdem('projeto_recebimento_lista_ordem ASC');
	$tipos=$sql->Lista();
	if ($tipos && count($tipos)) {
		$saida.= '<table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="100%"><tr><th></th>';
		$saida.='<td style="background-color:#a6a6a6; width:50px;"><b>Item</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>'.utf8_encode('Descrição').'</b></td>';
		$saida.='<th></th></tr>';
		}
	$qnt=0;
	foreach ($tipos as $tipo) {
		$qnt++;
		$saida.='<tr>';
		$saida.='<td nowrap="nowrap" width="40" align="center">';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$tipo['projeto_recebimento_lista_ordem'].', '.$tipo['projeto_recebimento_lista_id'].',\'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$tipo['projeto_recebimento_lista_ordem'].', '.$tipo['projeto_recebimento_lista_id'].',\'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$tipo['projeto_recebimento_lista_ordem'].', '.$tipo['projeto_recebimento_lista_id'].',\'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$tipo['projeto_recebimento_lista_ordem'].', '.$tipo['projeto_recebimento_lista_id'].',\'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
		$saida.='</td>';
		$saida.='<td>'.($qnt < 100 ? '0' : '').($qnt < 10 ? '0' : '').$qnt.'</td>';
		$saida.='<td>'.($tipo['projeto_recebimento_lista_produto'] ? utf8_encode($tipo['projeto_recebimento_lista_produto']) : '&nbsp;').'</td>';
		$saida.='<td width="32" align="center"><a href="javascript: void(0);" onclick="editar_tipo('.$tipo['projeto_recebimento_lista_id'].');">'.imagem('icones/editar.gif').'</a>';
		$saida.='<a href="javascript: void(0);" onclick="if (confirm(\''.utf8_encode('Tem certeza que deseja excluir este produto/serviço?').'\')) {excluir_tipo('.$tipo['projeto_recebimento_lista_id'].');}">'.imagem('icones/remover.png').'</a></td>';
		$saida.='</tr>';
		}
	if ($tipos && count($tipos)) $saida.='</table>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_tipos',"innerHTML", $saida);
	return $objResposta;	
	}

$xajax->registerFunction("lista_artefatos");	
function mudar_ordem($ordem, $projeto_recebimento_lista_id, $direcao, $projeto_recebimento_lista_recebimento_id){
	
		$sql = new BDConsulta;
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_recebimento_lista');
		$sql->adOnde('projeto_recebimento_lista_id != '.(int)$projeto_recebimento_lista_id);
		$sql->adOnde('projeto_recebimento_lista_recebimento_id = '.(int)$projeto_recebimento_lista_recebimento_id);
		$sql->adOrdem('projeto_recebimento_lista_ordem');
		$tipos = $sql->Lista();
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
			$novo_ui_ordem = count($tipos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($tipos) + 1)) {
			$sql->adTabela('projeto_recebimento_lista');
			$sql->adAtualizar('projeto_recebimento_lista_ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_recebimento_lista_id = '.(int)$projeto_recebimento_lista_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($tipos as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_recebimento_lista');
					$sql->adAtualizar('projeto_recebimento_lista_ordem', $idx);
					$sql->adOnde('projeto_recebimento_lista_id = '.(int)$acao['projeto_recebimento_lista_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_recebimento_lista');
					$sql->adAtualizar('projeto_recebimento_lista_ordem', $idx + 1);
					$sql->adOnde('projeto_recebimento_lista_id = '.(int)$acao['projeto_recebimento_lista_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
	return true;
	}	
$xajax->registerFunction("mudar_ordem");		
		
function excluir_tipo($projeto_recebimento_lista_id){
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_recebimento_lista');
	$sql->adOnde('projeto_recebimento_lista_id='.(int)$projeto_recebimento_lista_id);
	$sql->exec();
	$sql->limpar();	
	return true;
	}	
$xajax->registerFunction("excluir_tipo");	

function editar_tipo($projeto_recebimento_lista_id){
	$saida='';
	
	$sql = new BDConsulta;
	$sql->adTabela('projeto_recebimento_lista');
	$sql->adOnde('projeto_recebimento_lista_id='.(int)$projeto_recebimento_lista_id);
	$tipo=$sql->linha();
	$sql->limpar();	
	$saida.= '<table class="std" cellspacing=0 cellpadding=0  width="100%"><tr>';
	$saida.='<td ><b>'.utf8_encode('Descrição').'</b></td>';
	$saida.='<td></td></tr><tr>';
	$saida.='<td valign=top><textarea name="projeto_recebimento_lista_produto" id="projeto_recebimento_lista_produto" class="textarea" style="width:100%">'.utf8_encode($tipo['projeto_recebimento_lista_produto']).'</textarea></td>';
	$saida.='<td><a href="javascript:void(0);" onclick="javascript:inserir_tipo('.$projeto_recebimento_lista_id.');">'.imagem('icones/ok.png').'</a><a href="javascript:void(0);" onclick="javascript:cancelar_edicao();">'.imagem('icones/cancelar.png').'</a></td></tr>';
	$saida.='</table>';
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;	
	}	
	
$xajax->registerFunction("editar_tipo");		


function cancelar_edicao(){
	$saida=utf8_encode('<table cellpadding=0 cellspacing="2" width="100%"><tr><td><b>Produto/Serviço</b></td></tr><tr><td valign=top><textarea name="projeto_recebimento_lista_produto" id="projeto_recebimento_lista_produto" class="textarea" style="width:100%"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_tipo(0);">'.imagem('icones/adicionar.png').'</a></td></tr></table>');
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;	
	}	

$xajax->registerFunction("cancelar_edicao");
$xajax->processRequest();

?>