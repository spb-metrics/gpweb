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

class CLink extends CAplicObjeto {
	var $link_id = null;
	var $link_cia = null;
	var $link_dept = null;
	var $link_projeto = null;
	var $link_url = null;
	var $link_tarefa = null;
	var $link_pratica = null;
	var $link_acao = null;
	var $link_indicador = null;
	var $link_usuario = null;
	var $link_objetivo = null;
	var $link_perspectiva = null;
	var $link_tema = null;
	var $link_estrategia = null;
	var $link_fator = null;
	var $link_meta = null;
	var $link_canvas = null;
	var $link_nome = null;
	var $link_superior = null;
	var $link_descricao = null;
	var $link_dono = null;
	var $link_data = null;
	var $link_categoria = null;
	var $link_acesso = null;
	var $link_ativo = null;
	var $link_principal_indicador = null;
	
	function __construct() {
		parent::__construct('links', 'link_id');
		}
		
	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->link_id) {
			$ret = $sql->atualizarObjeto('links', $this, 'link_id', false);
			$sql->limpar();
			} 
		else {
			$ret = $sql->inserirObjeto('links', $this, 'link_id');
			$sql->limpar();
			}
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('links', $this->link_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->link_id);
		
		$link_usuarios=getParam($_REQUEST, 'link_usuarios', '');
		$link_usuarios=explode(',', $link_usuarios);
		$sql->setExcluir('link_usuarios');
		$sql->adOnde('link_id = '.$this->link_id);
		$sql->exec();
		$sql->limpar();
		foreach($link_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('link_usuarios');
				$sql->adInserir('link_id', $this->link_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}
			
		$link_depts=getParam($_REQUEST, 'link_depts', null);
		$link_depts=explode(',', $link_depts);
		$sql->setExcluir('link_dept');
		$sql->adOnde('link_dept_link = '.$this->link_id);
		$sql->exec();
		$sql->limpar();
		foreach($link_depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('link_dept');
				$sql->adInserir('link_dept_link', $this->link_id);
				$sql->adInserir('link_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}	
		
		if ($Aplic->profissional){
			$sql->setExcluir('link_cia');
			$sql->adOnde('link_cia_link='.(int)$this->link_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'link_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('link_cia');
						$sql->adInserir('link_cia_link', $this->link_id);
						$sql->adInserir('link_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}	
			}
			
		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('link_gestao');
			$sql->adAtualizar('link_gestao_link', (int)$this->link_id);
			$sql->adAtualizar('link_gestao_uuid', null);
			$sql->adOnde('link_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}	
			
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}	
		
		
	function check() {
		$this->link_id = intval($this->link_id);
		return null; 
		}
	function excluir($id=null) {
		global $Aplic;
		$this->_mensagem = "excluido";
		if ($Aplic->getEstado('link_id', null)==$this->link_id) $Aplic->setEstado('link_id', null);
		parent::excluir();
		return null;
		}
	
	
	function podeAcessar() {
		$valor=permiteAcessarLink($this->link_acesso, $this->link_id);
		return $valor;
		}
	
	function podeEditar() {
		$valor=permiteEditarLink($this->link_acesso, $this->link_id);
		return $valor;
		}
		
	}
	
	

?>