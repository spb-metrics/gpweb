<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\causa_efeito_ajax.php		

Fun��es Ajax utilizadas em gpweb\modulos\praticas\causa_efeito.php																																							
																																												
********************************************************************************************/
global $Aplic;

	include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
	$xajax = new xajax();
	
	function exibir_combo($posicao, $tabela, $chave='', $campo='', $onde='', $ordem='', $script='', $campo_id='', $campoatual='', $campobranco=true, $tabela2='', $uniao2='', $tabela3='', $uniao3=''){
		$sql = new BDConsulta;
		$sql->adTabela($tabela);
		if ($tabela2) $sql->esqUnir($tabela2, $tabela2, $uniao2);
		if ($tabela3) $sql->esqUnir($tabela3, $tabela3, $uniao3);
		if ($chave) $sql->adCampo($chave);
		if ($campo) $sql->adCampo($campo);
		if ($onde) $sql->adOnde($onde);
		if ($ordem) $sql->adOrdem($onde);
		$linhas=$sql->Lista();
		$sql->limpar();
		$vetor=array();
		$chave=explode('.',$chave); 
		$chave = array_pop($chave);
		if ($campobranco) $vetor[]='';
		foreach($linhas as $linha)$vetor[$linha[$chave]]=utf8_encode($linha[$campo]);
		$saida=selecionaVetor($vetor, $campo_id, $script, $campoatual);
		$objResposta = new xajaxResponse();
		$objResposta->assign($posicao,"innerHTML", $saida);
		return $objResposta;
		}

	function exibir_dept($posicao, $cia_id='', $script='', $campo_id='', $campoatual='', $campobranco=true){
		global $Aplic;
		require_once ($Aplic->getClasseModulo('depts'));
		$sql = new BDConsulta;
		$sql->adTabela('depts');
		$sql->adCampo('dept_id, dept_nome, dept_superior');
		$sql->adOnde('dept_cia='.(int)$cia_id);
		$sql->adOrdem('dept_superior, dept_nome');
		$depts = $sql->carregarListaVetor();
		$sql->limpar();
		$depts['0'] = array(0, '', -1);
		$vetor=array();
		foreach($depts as $dept) $vetor[$dept[0]]=array($dept[0], utf8_encode($dept[1]), $dept[2]);
		$saida=selecionaVetorArvore($vetor, $campo_id, $script, $campoatual);	
		$objResposta = new xajaxResponse();
		$objResposta->assign($posicao,"innerHTML", $saida);
		return $objResposta;
		}
	
function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
	$xajax->registerFunction("selecionar_om_ajax");	
	$xajax->registerFunction("exibir_combo");
	$xajax->registerFunction("exibir_dept");	
	$xajax->processRequest();
?>