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

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');
transforma_vazio_em_nulo($_REQUEST);

$del = isset($_REQUEST['del']) ? getParam($_REQUEST, 'del', null) : 0;
$nao_eh_novo = getParam($_REQUEST, 'dept_id', null);
$dept_id = getParam($_REQUEST, 'dept_id', null);
$_REQUEST['dept_ativo']=(isset($_REQUEST['dept_ativo']) ? 1 : 0);


$sql = new BDConsulta;
if ($del && !$Aplic->checarModulo('depts', 'excluir')) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($nao_eh_novo && !$Aplic->checarModulo('depts', 'editar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$Aplic->checarModulo('depts', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$dept = new CDept();
if (($msg = $dept->join($_REQUEST))) {
	$Aplic->setMsg($msg, UI_MSG_ERRO);
	$Aplic->redirecionar('m=depts');
	}
$Aplic->setMsg($config['departamento']);
if ($del) {
	
	$sql->adTabela('depts');
	$sql->adCampo('dept_cia');
	$sql->adOnde('dept_id='.(int)$dept_id);
	$dept_cia=$sql->Resultado();
	$sql->limpar();
	
	$sql= new BDConsulta;
	$sql->setExcluir('dept_contatos');
	$sql->adOnde('dept_contato_dept='.(int)$dept_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela dept_contatos!'.$bd->stderr(true));
	$sql->limpar();
	
	
	$sql->setExcluir('depts');
	$sql->adOnde('dept_id='.(int)$dept_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela depts!'.$bd->stderr(true));
	$sql->limpar();
	
	
	
	$Aplic->setMsg('excluid'.$config['genero_dept'], UI_MSG_OK, true);
	$Aplic->redirecionar('m=cias&a=ver&cia_id='.(int)$dept_cia);

	} 
else {
	if (($msg = $dept->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else {
		$Aplic->setMsg($nao_eh_novo ? 'atualizad'.$config['genero_dept'] : 'inserid'.$config['genero_dept'], UI_MSG_OK, true);
		$Aplic->redirecionar('m=depts&a=ver&dept_id='.$dept->dept_id);
		}
	$Aplic->redirecionar('m=depts');
	}
$Aplic->redirecionar('m=depts');	
?>