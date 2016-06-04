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

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
	
function mudar_usuario_ajax($cia_id=0){
	global $Aplic, $config;

	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$sql = new BDConsulta;
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->esqUnir('cias','cias','contatos.contato_cia=cias.cia_id');
	$sql->adCampo('cia_nome');
	$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
	$sql->adOnde('contato_cia='.(int)$cia_id);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$linhas=$sql->Lista();
	$sql->limpar();
	$vetor=array();	
	$vetor[0]='';
	foreach((array)$linhas as $linha) {
		$vetor[$linha['usuario_id']]=utf8_encode($linha['nome_usuario'].($linha['contato_funcao'] ? ' - '.$linha['contato_funcao']: '').($linha['cia_nome'] ? ' - '.$linha['cia_nome']: ''));
		}
		
	if (count($vetor)==1) $vetor[-1]='';
	$saida=selecionaVetor($vetor, 'ListaDE', 'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover();"', 0);
	$saida2=selecionaVetor($vetor, 'ListaDE2', 'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover3();"', 0);

	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_usuario","innerHTML", $saida);
	$objResposta->assign("combo_usuario2","innerHTML", $saida2);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_ajax");			
$xajax->registerFunction("selecionar_om_ajax");
$xajax->processRequest();

?>