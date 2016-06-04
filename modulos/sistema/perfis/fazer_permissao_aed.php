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
$perfil_id=getParam($_REQUEST, 'perfil_id', 0);
$del=getParam($_REQUEST, 'del', 0);
$sqlAcao2=getParam($_REQUEST, 'sqlAcao2', '');
$permissao_modulo=getParam($_REQUEST, 'permissao_modulo', '');
$permissao_submodulo=getParam($_REQUEST, 'permissao_submodulo', '');
$perfil_acesso_id=getParam($_REQUEST, 'perfil_acesso_id', 0);
$permissao_tipo=getParam($_REQUEST, 'permissao_tipo', array());


$perfil_acesso_negar=getParam($_REQUEST, 'perfil_acesso_negar', 0);

$sql = new BDConsulta;

if (!$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');

if ($sqlAcao2=='adicionar'){
	if (count($permissao_tipo)){
		
		//verificar se j� existe
		$sql->adTabela('perfil_acesso');
		$sql->adCampo('count(perfil_acesso_id)');
		$sql->adOnde('perfil_acesso_perfil='.(int)$perfil_id);
		$sql->adOnde('perfil_acesso_modulo=\''.$permissao_modulo.'\'');
		if ($permissao_submodulo) $sql->adOnde('perfil_acesso_objeto=\''.$permissao_submodulo.'\'');
		else $sql->adOnde('perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\'');
		$existe=$sql->Resultado();
		$sql->limpar();
		
		if ($existe){
			$sql->adTabela('perfil_acesso');
			$sql->adAtualizar('perfil_acesso_acesso', (in_array('perfil_acesso_acesso', $permissao_tipo) ? 1 : 0));
			$sql->adAtualizar('perfil_acesso_editar', (in_array('perfil_acesso_editar', $permissao_tipo) ? 1 : 0));
			$sql->adAtualizar('perfil_acesso_adicionar', (in_array('perfil_acesso_adicionar', $permissao_tipo) ? 1 : 0));
			$sql->adAtualizar('perfil_acesso_excluir', (in_array('perfil_acesso_excluir', $permissao_tipo) ? 1 : 0));
			$sql->adAtualizar('perfil_acesso_aprovar', (in_array('perfil_acesso_aprovar', $permissao_tipo) ? 1 : 0));
			$sql->adAtualizar('perfil_acesso_negar', $perfil_acesso_negar);		
			$sql->adOnde('perfil_acesso_perfil='.(int)$perfil_id);
			$sql->adOnde('perfil_acesso_modulo=\''.$permissao_modulo.'\'');
			if ($permissao_submodulo) $sql->adOnde('perfil_acesso_objeto=\''.$permissao_submodulo.'\'');
			$sql->exec();
	  	$sql->Limpar();
			}
		else {
			$sql->adTabela('perfil_acesso');
			$sql->adInserir('perfil_acesso_perfil', $perfil_id);
			$sql->adInserir('perfil_acesso_modulo', $permissao_modulo);
			$sql->adInserir('perfil_acesso_negar', $perfil_acesso_negar);	
			if ($permissao_submodulo) $sql->adInserir('perfil_acesso_objeto', $permissao_submodulo);
			foreach($permissao_tipo as $campo) $sql->adInserir($campo, 1);
			$sql->exec();
			$sql->limpar();
			}
		}
	$Aplic->setMsg('Permiss�o editada', UI_MSG_OK, true);	
	}

if ($del){
	$sql->setExcluir('perfil_acesso');
	$sql->adOnde('perfil_acesso_id='.$perfil_acesso_id);
	$sql->exec();
	$sql->limpar();
	$Aplic->setMsg('Permiss�o exclu�da', UI_MSG_OK, true);	
	}

$Aplic->redirecionar('m=sistema&u=perfis&a=ver_perfil&perfil_id='.$perfil_id.'&permissao_modulo='.$permissao_modulo.'&permissao_submodulo='.$permissao_submodulo);

?>