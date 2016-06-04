<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');

$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);	



function incluir_lista_ajax(
		$campo_customizado_lista_campo=null,
		$campo_customizado_lista_uuid=null, 
		$campo_customizado_lista_id=null, 
		$campo_customizado_lista_opcao=null, 
		$campo_customizado_lista_valor=null,
		$campo_customizado_lista_cor=null
		){
			
	global $Aplic;
			
	$sql = new BDConsulta;
	$campo_customizado_lista_opcao=previnirXSS(utf8_decode($campo_customizado_lista_opcao));
	$campo_customizado_lista_valor=previnirXSS(utf8_decode($campo_customizado_lista_valor));
	$campo_customizado_lista_cor=previnirXSS(utf8_decode($campo_customizado_lista_cor));

	if ($campo_customizado_lista_id){
		$sql->adTabela('campo_customizado_lista');
		$sql->adAtualizar('campo_customizado_lista_opcao', $campo_customizado_lista_opcao);	
		$sql->adAtualizar('campo_customizado_lista_valor', $campo_customizado_lista_valor);
		$sql->adAtualizar('campo_customizado_lista_cor', $campo_customizado_lista_cor);
		$sql->adOnde('campo_customizado_lista_id ='.(int)$campo_customizado_lista_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('campo_customizado_lista');
		if ($campo_customizado_lista_campo) $sql->adInserir('campo_customizado_lista_campo', $campo_customizado_lista_campo);
		else $sql->adInserir('campo_customizado_lista_uuid', $campo_customizado_lista_uuid);
		$sql->adInserir('campo_customizado_lista_opcao', $campo_customizado_lista_opcao);	
		$sql->adInserir('campo_customizado_lista_valor', $campo_customizado_lista_valor);
		$sql->adInserir('campo_customizado_lista_cor', $campo_customizado_lista_cor);
		$sql->exec();
		}
	$saida=atualizar_lista($campo_customizado_lista_campo, $campo_customizado_lista_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_lista","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_lista_ajax");

function excluir_lista_ajax($campo_customizado_lista_id, $campo_customizado_lista_campo, $campo_customizado_lista_uuid){
	$sql = new BDConsulta;
	$sql->setExcluir('campo_customizado_lista');
	$sql->adOnde('campo_customizado_lista_id='.(int)$campo_customizado_lista_id);
	$sql->exec();
	$saida=atualizar_lista($campo_customizado_lista_campo, $campo_customizado_lista_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_lista","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_lista_ajax");	

function atualizar_lista($campo_customizado_lista_campo=null, $campo_customizado_lista_uuid=null){
	global $config, $unidade, $Aplic;

	$sql = new BDConsulta;
	$sql->adTabela('campo_customizado_lista');
	$sql->adCampo('campo_customizado_lista.*');
	if ($campo_customizado_lista_campo) $sql->adOnde('campo_customizado_lista_campo ='.(int)$campo_customizado_lista_campo);	
	else $sql->adOnde('campo_customizado_lista_uuid =\''.$campo_customizado_lista_uuid.'\'');	
	$linhas=$sql->Lista();
	$sql->limpar();

	$saida='';
	
	if (count($linhas)){
		$saida.= '<table cellpadding=0 cellspacing=0 class="tbl1">';
		$saida.= '<tr><th></th><th>'.dica('Chave', 'A chave da op��o da lista.').'Chave'.dicaF().'</th><th>'.dica('Valor', 'O valor apresentado da op��o da lista.').'Valor'.dicaF().'</th><th>'.dica('Cor', 'A cor apresentada da op��o da lista.').'Cor'.dicaF().'</th><th></th></tr>';
		}
	$total=0;
	$lista=array();
	foreach ($linhas as $dado) {
		$saida.= '<tr align="center">';
		$saida.= '<td width="16">'.dica('Editar Item', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o item.').'<a href="javascript:void(0);" onclick="javascript:editar_lista('.$dado['campo_customizado_lista_id'].'	);">'.imagem('icones/editar.gif').'</a>'.dicaF().'</td>';
		$saida.= '<td align="center">'.$dado['campo_customizado_lista_opcao'].'</td>';
		$saida.= '<td align="left">'.($dado['campo_customizado_lista_valor'] ? $dado['campo_customizado_lista_valor'] : '&nbsp;').'</td>';
		$saida.= '<td width="16" align="right" style="background-color:#'.($dado['campo_customizado_lista_cor'] ? $dado['campo_customizado_lista_cor'] : 'FFFFFF').'">&nbsp;&nbsp;</td>';
		$saida.= '<td width="16">'.dica('Excluir Item', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir o item.').'<a href="javascript:void(0);" onclick="javascript:excluir_lista('.$dado['campo_customizado_lista_id'].');">'.imagem('icones/remover.png').'</a>'.dicaF().'</td>';	
		$saida.= '</tr>';
		}
	if (count($linhas)) $saida.= '</table>';
	return $saida;
	}

function exibir_listas($campo_customizado_lista_campo=null, $campo_customizado_lista_uuid=null){
	$saida=atualizar_lista($campo_customizado_lista_campo, $campo_customizado_lista_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_lista","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("exibir_listas");
	
	

function editar_lista($campo_customizado_lista_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('campo_customizado_lista');
	$sql->adCampo('campo_customizado_lista.*');
	$sql->adOnde('campo_customizado_lista_id = '.(int)$campo_customizado_lista_id);
	$linha=$sql->Linha();
	$sql->limpar();
	$objResposta->assign("campo_customizado_lista_id","value", $campo_customizado_lista_id);
	$objResposta->assign("campo_customizado_lista_opcao","value", utf8_encode($linha['campo_customizado_lista_opcao']));
	$objResposta->assign("campo_customizado_lista_valor","value", utf8_encode($linha['campo_customizado_lista_valor']));
	$objResposta->assign("campo_customizado_lista_cor","value", utf8_encode($linha['campo_customizado_lista_cor']));
	$objResposta->assign("teste","style.background", '#'.utf8_encode($linha['campo_customizado_lista_cor']));
	return $objResposta;
	}	
$xajax->registerFunction("editar_lista");		
	
$xajax->processRequest();
		
?>