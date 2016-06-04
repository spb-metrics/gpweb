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
		$Aplic->setMsg('M�dulo de configura��o de arquivo n�o foi encontrado', UI_MSG_ERRO);
		if ($cmd == 'remover') {
			$q = new BDConsulta;
			$q->setExcluir('modulos');
			$q->adOnde('mod_id = '.(int)$mod_id);
			$q->exec();
			$q->limpar();
			echo db_error();
			$Aplic->setMsg('M�dulo foi removido da lista de m�dulos - verifique a base de dados por tabelas que necessitem serem exclu�das', UI_MSG_ERRO);
			}
		$Aplic->redirecionar('m=sistema&a=vermods');
		}
	}
	
$classeConfig = (isset($configuracao['mod_classe_configurar']) ? $configuracao['mod_classe_configurar'] : '');

if (!$classeConfig) {
	if ($obj->mod_tipo != 'core') {
		$Aplic->setMsg('M�dulo n�o tem uma classe de configua��o v�lida', UI_MSG_ERRO);
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
		$Aplic->setMsg('M�dulo reordenado', UI_MSG_OK);
		break;
	case 'ativar':
		$obj->mod_ativo = 1 - $obj->mod_ativo;
		$obj->armazenar();
		$Aplic->setMsg('Estado do m�dulo mudou', UI_MSG_OK);
		break;
	case 'ativarMenu':
		$obj->mod_ui_ativo = 1 - $obj->mod_ui_ativo;
		$obj->armazenar();
		$Aplic->setMsg('Menu de estado do m�dulo mudou', UI_MSG_OK);
		break;
	case 'instalar':
		$Aplic->setMsg($setup->instalar());
		$obj->join($configuracao);
		$obj->instalar();
		$Aplic->setMsg('M�dulo instalado', UI_MSG_OK, true);
		break;
	case 'remover':
		$Aplic->setMsg($setup->remover());
		$obj->remover();
		$Aplic->setMsg('M�dulo removido', UI_MSG_ALERTA, true);
		break;
	case 'atualizar':
		$Aplic->setMsg($setup->atualizar($obj->mod_versao));
		$Aplic->setMsg('Atualiza��o efetuada', UI_MSG_OK, true);
		break;
	case 'exemplo':
		$Aplic->setMsg($setup->exemplo());
		$Aplic->setMsg('Exemplo carregado', UI_MSG_OK, true);
		break;	
	case 'configurar':
		if ($setup->configurar()){
			//fazer o que?
			} 
		else $Aplic->setMsg('Configura��o do m�dulo falhou', UI_MSG_ERRO);
		break;
	default:
		$Aplic->setMsg('Comando desconhecido', UI_MSG_ERRO);
		break;
	}
$Aplic->redirecionar('m=sistema&a=vermods');
?>