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
$xajax->configure( 'defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);


function incluir_problema_ajax($acao_id=0,$familia=0, $problema=0, $observacao=''){
	global $Aplic;
	$sql = new BDConsulta;	
	$sql->adTabela('social_superintendencia_problema');
	$sql->adInserir('social_superintendencia_problema_superintendencia', (int)$familia);
	$sql->adInserir('social_superintendencia_problema_acao', (int)$acao_id);
	$sql->adInserir('social_superintendencia_problema_tipo', (int)$problema);
	$sql->adInserir('social_superintendencia_problema_observacao', previnirXSS(utf8_decode($observacao)));
	$sql->adInserir('social_superintendencia_problema_data_insercao', date('Y-m-d H:i:s'));
	$sql->adInserir('social_superintendencia_problema_usuario_insercao', $Aplic->usuario_id);
	$sql->exec();
	$sql->limpar();
	
	$objResposta = new xajaxResponse();
	return $objResposta;
	}

function excluir_problema_ajax($acao_id=0, $problema_id=0){
	global $Aplic;
	$sql = new BDConsulta;	

	$sql->setExcluir('social_superintendencia_problema');
	$sql->adOnde('social_superintendencia_problema_id = '.(int)$problema_id);
	$sql->exec();
	$sql->limpar();

	$objResposta = new xajaxResponse();
	return $objResposta;
	}


function exibir_problema_ajax($acao_id=0, $social_superintendencia_id=0){
	global $Aplic;



	$sql = new BDConsulta;
	
	$sql->adTabela('social_acao_problema');
	$sql->adCampo('social_acao_problema_id, social_acao_problema_descricao');
	$sql->adOnde('social_acao_problema_acao_id='.(int)$acao_id);
	$sql->adOnde('social_acao_problema_tipo=5');
	$sql->adOrdem('social_acao_problema_ordem ASC');
	$lista_problemas=$sql->listaVetorChave('social_acao_problema_id', 'social_acao_problema_descricao');
	$status=getSisValor('StatusProblema');
	
	$sql->adTabela('social_superintendencia_problema');
	$sql->adCampo('social_superintendencia_problema_id, social_superintendencia_problema_tipo, social_superintendencia_problema_status, social_superintendencia_problema_observacao, social_superintendencia_problema_usuario_insercao, social_superintendencia_problema_data_insercao');
	$sql->adOnde('social_superintendencia_problema_acao='.(int)$acao_id);
	$sql->adOnde('social_superintendencia_problema_superintendencia='.(int)$social_superintendencia_id);
	$sql->adOrdem('social_superintendencia_problema_data_insercao ASC');
	$lista=$sql->Lista();
	$saida='';
	foreach ($lista as $linha) {
		$saida.='<tr>';
		$saida.='<td>'.(isset($lista_problemas[$linha['social_superintendencia_problema_tipo']]) ? utf8_encode($lista_problemas[$linha['social_superintendencia_problema_tipo']]) : '&nbsp;').'</td>';
		$saida.='<td>'.($linha['social_superintendencia_problema_observacao'] ? utf8_encode($linha['social_superintendencia_problema_observacao']) : '&nbsp;').'</td>';
		$saida.='<td>'.retorna_data($linha['social_superintendencia_problema_data_insercao'], false).'</td>';
		$saida.='<td>'.utf8_encode(link_usuario($linha['social_superintendencia_problema_usuario_insercao'], '','','esquerda','','',false)).'</td>';
		$saida.='<td>'.(isset($status[$linha['social_superintendencia_problema_status']]) ? utf8_encode($status[$linha['social_superintendencia_problema_status']]) : '&nbsp;').'</td>';
		$saida.='<td><a href="javascript: void(0);" onclick="excluir_problema('.$acao_id.','.$linha['social_superintendencia_problema_id'].');">'.imagem('icones/remover.png').'</a></td>';
		$saida.='</tr>';
		}

	if ($saida) $saida='<table cellpadding=0 cellspacing=0 class="tbl1"><tr><th>Problema</th><th>'.utf8_encode('Observação').'</th><th>Data</th><th>'.utf8_encode('Responsável').'</th><th>Status</th><th></th></tr>'.$saida.'</table>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_problema_'.$acao_id, "innerHTML", $saida);
	return $objResposta;
	
	}


function acao_ajax($posicao='', $campo='', $script='', $social_id=0, $acao_id=0){
	$saida=selecionar_acao_para_ajax($social_id, $campo, $script);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
	

function superintendencia_lista_ajax($social_superintendencia_id=0, $lista_id=0, $checado=false){
	global $Aplic;
	$sql = new BDConsulta;	
	if ($checado){
		$sql->adTabela('social_superintendencia_lista');
		$sql->adInserir('social_superintendencia_lista_superintendencia', (int)$social_superintendencia_id);
		$sql->adInserir('social_superintendencia_lista_lista', (int)$lista_id);
		$sql->adInserir('social_superintendencia_lista_data', date('Y-m-d H:i:s'));
		$sql->adInserir('social_superintendencia_lista_usuario', $Aplic->usuario_id);
		$sql->exec();
		$sql->limpar();
		}
	else {
		$sql->setExcluir('social_superintendencia_lista');
		$sql->adOnde('social_superintendencia_lista_superintendencia = '.(int)$social_superintendencia_id);
		$sql->adOnde('social_superintendencia_lista_lista = '.(int)$lista_id);
		$sql->exec();
		$sql->limpar();
		}	
	}


$xajax->registerFunction("exibir_problema_ajax");
$xajax->registerFunction("excluir_problema_ajax");
$xajax->registerFunction("incluir_problema_ajax");	
$xajax->registerFunction("superintendencia_lista_ajax");	
$xajax->registerFunction("acao_ajax");			
$xajax->processRequest();

?>