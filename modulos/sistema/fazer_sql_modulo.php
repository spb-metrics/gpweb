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

if (!$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');
$cmd = getParam($_REQUEST, 'cmd', '0');
$mod_id = intval(getParam($_REQUEST, 'mod_id', '0'));
$mod_diretorio = getParam($_REQUEST, 'mod_diretorio', '0');



$obj = new CModulo();
if ($mod_id) $obj->load($mod_id);
else $obj->mod_diretorio = $mod_diretorio;
$ok = include_once(BASE_DIR.'/modulos/'.$obj->mod_diretorio.'/setup.php');

if (!$ok) {
	if ($obj->mod_tipo != 'core') {
		$Aplic->setMsg('Módulo de configuração de arquivo não foi encontrado', UI_MSG_ERRO);
		if ($cmd == 'remover') {
			$q = new BDConsulta;
			$q->setExcluir('modulos');
			$q->adOnde('mod_id = '.(int)$mod_id);
			$q->exec();
			$q->limpar();
			echo db_error();
			$Aplic->setMsg('Módulo foi removido da lista de módulos - verifique a base de dados por tabelas que necessitem serem excluídas', UI_MSG_ERRO);
			}
		$Aplic->redirecionar('m=sistema&a=vermods');
		}
	}
	
$classeConfig = (isset($configuracao['mod_classe_configurar']) ? $configuracao['mod_classe_configurar'] : '');

if (!$classeConfig) {
	if ($obj->mod_tipo != 'core') {
		$Aplic->setMsg('Módulo não tem uma classe de configuação válida', UI_MSG_ERRO);
		$Aplic->redirecionar('m=sistema&a=vermods');
		}
	}
else $setup = new $classeConfig();

switch ($cmd) {
	case 'moverParaCima':
	case 'moverParaBaixo':
	case 'moverPrimeiro':
	case 'moverUltimo':
		$obj->mover($cmd);
		$Aplic->setMsg('Módulo reordenado', UI_MSG_OK);
		break;
	case 'ativar':
		$obj->mod_ativo = 1 - $obj->mod_ativo;
		$obj->armazenar();
		$Aplic->setMsg('Estado do módulo mudou', UI_MSG_OK);
		break;
	case 'ativarMenu':
		$obj->mod_ui_ativo = 1 - $obj->mod_ui_ativo;
		$obj->armazenar();
		$Aplic->setMsg('Menu de estado do módulo mudou', UI_MSG_OK);
		break;
	case 'instalar':
		$Aplic->setMsg($setup->instalar());
		$obj->join($configuracao);
		$obj->instalar();
		$Aplic->setMsg('Módulo instalado', UI_MSG_OK, true);
		break;
	case 'remover':
		$Aplic->setMsg($setup->remover());
		$obj->remover();
		$Aplic->setMsg('Módulo removido', UI_MSG_ALERTA, true);
		break;
	case 'atualizar':
		$Aplic->setMsg($setup->atualizar($obj->mod_versao));
		$Aplic->setMsg('Atualização efetuada', UI_MSG_OK, true);
		break;
	case 'exemplo':
		$Aplic->setMsg($setup->exemplo());
		$Aplic->setMsg('Exemplo carregado', UI_MSG_OK, true);
		break;	
	case 'configurar':
		if ($setup->configurar()){
			//fazer o que?
			} 
		else $Aplic->setMsg('Configuração do módulo falhou', UI_MSG_ERRO);
		break;
	default:
		$Aplic->setMsg('Comando desconhecido', UI_MSG_ERRO);
		break;
	}
$Aplic->redirecionar('m=sistema&a=vermods');
?>