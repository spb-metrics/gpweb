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

/********************************************************************************************

Classe CPerfil para manipulação dos perfis de acesso ao Sistema
		
gpweb\modulos\sistema\perfis\perfis.class.php																																		
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class CPerfil {
	var $perfil_id = null;
	var $perfil_nome = null;
	var $perfil_descricao = null;
	var $perms = null;
	
	function __construct($nome = '', $descricao = '') {
		$this->perfil_nome = $nome;
		$this->perfil_descricao = $descricao;
		$this->perms = &$GLOBALS['Aplic']->acl();
		}
		
	function join($hash) {
		if (!is_array($hash)) return get_class($this)."::unir falhou";
		else {
			$q = new BDConsulta;
			$q->unirLinhaAoObjeto($hash, $this);
			$q->limpar();
			return null;
			}
		}
		
	function check() {
		return null; 
		}
		
	function armazenar($atualizarNulos = false) {
		$msg = $this->check();
		if ($msg) return get_class($this).'::checagem para armazenar falhou '.$msg;
		if ($this->perfil_id) $ret = $this->perms->atualizarPerfil($this->perfil_id, $this->perfil_nome, $this->perfil_descricao);
		else $ret = $this->perms->inserirPerfil($this->perfil_nome, $this->perfil_descricao);
		if (!$ret) return get_class($this).'::armazenar falhou';
		else return null;
		}
		
	function excluir($oid = NULL) {
		if ($this->perms->$Aplic->checarModulo('perfis', 'excluir')) {
			$this->perms->excluirPerfil($this->perfil_id);
			return null;
			} 
		else return get_class($this).'::excluir falhou - Você não tem permissão para excluir esta função.';
		}
		
	function __sleep() {
		return array('perfil_id', 'perfil_nome', 'perfil_descricao');
		}
		
	function __wakeup() {
		$this->perms = &$GLOBALS['Aplic']->acl();
		}
		
	function getPerfis() {
		$perfil_superior = $this->perms->get_grupo_id('perfil');
		$perfis = $this->perms->getSubordinada($perfil_superior);
		return $perfis;
		}
		
	function renomear_vertor(&$perfis, $de, $para) {
		if (count($de) != count($para)) return false;
		foreach ($perfis as $chave => $val) {
			if (($k = array_search($k, $de)) !== false && $k !== null) {
				unset($perfis[$chave]);
				$perfis[$para[$k]] = $val;
				}
			}
		return true;
		}
		
	}
?>