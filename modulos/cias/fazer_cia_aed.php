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

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
transforma_vazio_em_nulo($_REQUEST);
$del = getParam($_REQUEST, 'del', 0);
$obj = new CCia();
$msg = '';
$cia_id = getParam($_REQUEST, 'cia_id', null);
$nao_eh_novo =$cia_id;
$sql= new BDConsulta;
$_REQUEST['cia_ativo']=(isset($_REQUEST['cia_ativo']) ? 1 : 0);
require_once BASE_DIR.'/modulos/projetos/projetos.class.php';

if ($del) {
	if (!$podeExcluir) $Aplic->redirecionar('m=publico&a=acesso_negado');
	//excluir cia
	$sql->adTabela('depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('dept_cia='.(int)$cia_id);
	$depts=$sql->Lista();
	$sql->limpar();
	
	foreach($depts AS $dept){
		$sql->setExcluir('dept_contatos');
		$sql->adOnde('dept_contato_dept='.$dept['dept_id']);
		if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela dept_contatos!'.$bd->stderr(true));
		$sql->limpar();
		}
	
	$sql->setExcluir('depts');
	$sql->adOnde('dept_cia='.(int)$cia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela depts!'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->adTabela('projetos');
	$sql->adCampo('projeto_id');
	$sql->adOnde('projeto_cia='.(int)$cia_id);
	$projetos=$sql->Lista();
	$sql->limpar();

	foreach($projetos AS $projeto){
		$obj = new CProjeto();
		$podeExcluir = $obj->podeExcluir($msg, $projeto['projeto_id']);
		if (!$podeExcluir) {
			$Aplic->setMsg($msg, UI_MSG_ERRO);
			$Aplic->redirecionar('m=cias&a=index');
			}
		if (($msg = $obj->excluir())) {
			$Aplic->setMsg($msg, UI_MSG_ERRO);
			$Aplic->redirecionar('m=cias&a=index');
			} 
		}
	$sql->setExcluir('cias');
	$sql->adOnde('cia_id='.(int)$cia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela depts!'.$bd->stderr(true));
	$sql->limpar();
		
	///fazer depois exclus�o de plano de gest�o
	$Aplic->setMsg(ucfirst($config['organizacao']).' exclu�da', UI_MSG_OK);
	$Aplic->redirecionar('m=cias&a=index');
	} 
elseif ($nao_eh_novo) {
	$obj = New CCia();
	$obj->load($cia_id);
	$permiteEditar=permiteEditarCia($cia_id, $obj->cia_acesso);
	if ($cia_id) $podeEditar = ($podeEditar && permiteEditarCia($cia_id, $obj->cia_acesso));
	if (!(($podeEditar && $permiteEditar) || $Aplic->usuario_super_admin || ($cia_id==$Aplic->usuario_cia && $Aplic->usuario_admin))) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif (!$Aplic->checarModulo('cias', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=cias&a=index');
	}

$Aplic->setMsg($config['organizacao']);
if ($del) {
	if (!$obj->podeExcluir($msg)) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=cias&a=index');
		}
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=cias&a=index');
		} 
	else {
		$Aplic->setMsg(ucfirst($config['organizacao']).' exclu�da', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=cias&a=index');
		}
	} 
else {
	if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else {
		$Aplic->setMsg($_REQUEST['cia_id'] ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
		}
	$Aplic->redirecionar('m=cias&a=ver&cia_id='.(int)$obj->cia_id);
	}
$Aplic->redirecionar('m=cias');	
?>