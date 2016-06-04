<?php
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
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
	
	
	
	
function selecionar_om_ajax($cia_id=null, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso,  $vazio, 1, 1);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");		
		
function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script){
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}		
$xajax->registerFunction("selecionar_cidades_ajax");		
		
function selecionar_secao_ajax($dept_id=1){
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	$sql->adCampo('depts.dept_id, depts.dept_nome');
	$sql->adOnde('depts.dept_cia='.(int)$dept_id);
	$sql->adOnde('depts.dept_superior IS NULL OR dept_superior=0');
	$sql->adOrdem('dept_nome ASC');
	$linhas=$sql->Lista();
	$sql->limpar();

	$vetor=array();
	$vetor[0]='';
	foreach($linhas as $linha) {
		$vetor[$linha['dept_id']]=utf8_encode($linha['dept_nome']);
		$sql->adTabela('depts');
		$sql->adCampo('depts.dept_id, depts.dept_nome');
		$sql->adOnde('depts.dept_cia='.(int)$dept_id);
		$sql->adOnde('depts.dept_superior='.(int)$linha['dept_id']);
		$sql->adOrdem('dept_nome ASC');
		$subordinadas=$sql->Lista();
		$sql->limpar();
		foreach($subordinadas as $subordinada) $vetor[$subordinada['dept_id']]=utf8_encode('&nbsp;&nbsp;&nbsp;&nbsp;'.$subordinada['dept_nome']);	
		}
		
		
	$saida=selecionaVetor($vetor, 'contato_dept', 'class=texto size=1 style="width:300px;"', $dept_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_secao',"innerHTML", $saida);
	return $objResposta;
	}		
$xajax->registerFunction("selecionar_secao_ajax");

function existe_login_ajax($login=''){
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->adCampo('count(usuario_id)');
	$sql->adOnde('usuario_login=\''.previnirXSS(utf8_decode($login)).'\'');
	$existe=$sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_login","value", (int)$existe);
	return $objResposta;
	}	
$xajax->registerFunction("existe_login_ajax");		

function existe_identidade_ajax($identidade=''){
	if (!$identidade) {
		$objResposta = new xajaxResponse();
		$objResposta->assign("existe_identidade","value", 0);
		return $objResposta;
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('contatos');
		$sql->adCampo('count(contato_id)');
		$sql->adOnde('contato_identidade=\''.previnirXSS(utf8_decode($identidade)).'\'');
		$existe=$sql->Resultado();
		$sql->limpar();
		$objResposta = new xajaxResponse();
		$objResposta->assign("existe_identidade","value", (int)$existe);
		return $objResposta;
		}
	}	
$xajax->registerFunction("existe_identidade_ajax");	

$xajax->processRequest();

?>