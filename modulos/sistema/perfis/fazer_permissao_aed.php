<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
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
		
		//verificar se já existe
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
	$Aplic->setMsg('Permissão editada', UI_MSG_OK, true);	
	}

if ($del){
	$sql->setExcluir('perfil_acesso');
	$sql->adOnde('perfil_acesso_id='.$perfil_acesso_id);
	$sql->exec();
	$sql->limpar();
	$Aplic->setMsg('Permissão excluída', UI_MSG_OK, true);	
	}

$Aplic->redirecionar('m=sistema&u=perfis&a=ver_perfil&perfil_id='.$perfil_id.'&permissao_modulo='.$permissao_modulo.'&permissao_submodulo='.$permissao_submodulo);

?>