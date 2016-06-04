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
require_once BASE_DIR.'/classes/BDConsulta.class.php';

class CAplicObjeto {
	var $_prefixo_tabela=null;
	var $_tbl=null;
	var $_chave_tabela = '';
	var $_chave_tabela2=null;
	var $_erro=null;
	var $_consulta=null;

	function __construct($tabela, $chave=null, $chave2=null) {
		$this->_tbl = $tabela;
		$this->_chave_tabela = $chave;
		$this->_chave_tabela2 = $chave2;
		$this->_prefixo_tabela = config('prefixoBd', '');
		$this->_consulta = new BDConsulta;
		}

	function getErro() {
		return $this->_erro;
		}

	function join($hash) {
		if (!is_array($hash)) {
			$this->_erro = get_class($this).'::anexar falhou.';
			return false;
			} 
		else {
			$filtro_hash=array();
			foreach ($hash as $k => $v) {
				if (!(is_object($hash[$k]))) $filtro_hash[$k] = ($v || $v=='0' || $v===0 || $v=='' ? previnirXSS($v) : null);
				}
				
			$this->_consulta->unirLinhaAoObjeto($filtro_hash, $this);
			
				
			$this->_consulta->limpar();
			return true;
			}
		}

	function load($id = null, $tira = true, $id2=null) {
		if ($id === null) return false;
		$this->_consulta->limpar();
		$this->_consulta->adTabela($this->_tbl);
		$this->_consulta->adOnde($this->_chave_tabela.' = \''.$id.'\'');
		if ($id2) $this->_consulta->adOnde($this->_chave_tabela2.' = \''.$id2.'\'');
		$hash = $this->_consulta->Linha();
		if (!$hash) return false;
		$this->_consulta->unirLinhaAoObjeto($hash, $this, null, $tira);
		$this->_consulta->limpar();
		return $this;
		}

	function carregarTudo($ordem = null, $onde = null) {
		$this->_consulta->limpar();
		$this->_consulta->adTabela($this->_tbl);
		if ($ordem) $this->_consulta->adOrdem($ordem);
		if ($onde) $this->_consulta->adOnde($onde);
		$resultado = $this->_consulta->ListaChave($this->_chave_tabela);
		$this->_consulta->limpar();
		return $resultado;
		}

	function &getConsulta($apelido = null) {
		$this->_consulta->limpar();
		$this->_consulta->adTabela($this->_tbl, $apelido);
		return $this->_consulta;
		}

	function check() {
		return null;
		}

	function duplicar() {
		$_chave = $this->_chave_tabela;
		if (version_compare(phpversion(), '5') >= 0) $novoObj = clone($this);
		else $novoObj = $this;
		$novoObj->$_chave = '';
		return $novoObj;
		}

	function arrumarTodos() {
		$vetor_arrumar = get_object_vars($this);
		foreach ($vetor_arrumar as $chave_arrumar => $valor_arrumado) {
			if (!(strcasecmp(gettype($valor_arrumado), 'string'))) $this->{$chave_arrumar} = trim($valor_arrumado);
			}
		}

	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$this->arrumarTodos();
		$msg = $this->check();
		if ($msg) return get_class($this).'::checagem para armazenar falhou '.$msg;
		$k = $this->_chave_tabela;
		if ($this->$k) {
			$tipo_armazenagem = 'atualizado';
			$q = new BDConsulta;
			$ret = $q->atualizarObjeto($this->_tbl, $this, $this->_chave_tabela, $atualizarNulos);
			$q->limpar();
			} 
		else {
			$tipo_armazenagem = 'adicionado';
			$q = new BDConsulta;
			$ret = $q->inserirObjeto($this->_tbl, $this, $this->_chave_tabela);
			$q->limpar();
			}
		return ((!$ret) ? (get_class($this).'::armazenagem falhou '.db_error()) : null);
		}

	function podeExcluir(&$msg='', $oid = null, $unioes = null) {
		global $Aplic;
		$k = $this->_chave_tabela;
		if ($oid) $this->$k = intval($oid);
		if (is_array($unioes)) {
			$selecionar = $k;
			$unir = '';
			$q = new BDConsulta;
			$q->adTabela($this->_tbl);
			$q->adOnde($k.' = \''.$this->_tbl.$this->$k.'\'');
			$q->adGrupo($k);
			foreach ($unioes as $tabela) {
				$q->adCampo('COUNT(DISTINCT '.$tabela['campo_id'].') AS '.$tabela['campo_id']);
				$q->adUnir($tabela['nome'], (isset($tabela['apelido']) && $tabela['apelido'] ? $tabela['apelido'] : $tabela['nome']), $tabela['campo_uniao'].' = '.$k);
				}
			$obj = null;
			$q->carregarObjeto($obj);
			$q->limpar();
			if (!$obj) {
				$msg = db_error();
				return false;
				}
			$msg = array();
			foreach ($unioes as $tabela) {
				$k = $tabela['campo_id'];
				if ($obj->$k)	$msg[] = $tabela['rotulo'];
				}
			if (count($msg)) {
				$msg = 'Você não pode eliminar este registro. Há '.implode(', ', $msg).' associados a ele. Elimine estas associações.';
				return false;
				} 
			else return true;
			}
		return true;
		}

	function excluir($oid = null){
		global $msg;
		$k = $this->_chave_tabela;
		if ($oid) $this->$k = intval($oid);
		if (!$this->podeExcluir($msg)) return $msg;
		$q = new BDConsulta;
		$q->setExcluir($this->_tbl);
		$q->adOnde($this->_chave_tabela.' = \''.$this->$k.'\'');
		$resultado = ((!$q->exec()) ? db_error() : null);
		$q->limpar();
		if(!$resultado && isset($this->template_projeto)){
			$q->adTabela('projetos');
			$q->adAtualizar('projeto_template', 0);
			$q->adOnde('projeto_id = '.(int)$this->template_projeto);
			$q->exec();
			$q->limpar();
			}
		
		return $resultado;
		}

	function htmlDecodificar() {
		foreach (get_object_vars($this) as $k => $v) {
			if (is_array($v) or is_object($v) or $v == null) continue;
			if ($k[0] == '_') continue;
			$this->$k = htmlspecialchars_decode($v);
			}
		}
	}
?>