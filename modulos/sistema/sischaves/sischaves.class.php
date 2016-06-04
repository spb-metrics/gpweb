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

include_once ($Aplic->getClasseSistema('aplic'));

/*********************************************************************************************
Classe CSisValor para manipulação dos valores das constantes do Sistema
		
gpweb\modulos\sistema\sischaves\sischaves.class.php																																		
																																												
********************************************************************************************/
class CSisValor extends CAplicObjeto {
	var $sisvalor_id = null;
	var $sisvalor_titulo = null;
	var $sisvalor_valor = null;
	var $sisvalor_valor_id = null;
	var $sisvalor_chave_id_pai = null;
	
	function __construct($titulo = null, $valor = null) {
		parent::__construct('sisvalores', 'sisvalor_id');
		$this->sisvalor_titulo = $titulo;
		$this->sisvalor_valor = $valor;
		}
	
	function check() {
		if (!$this->sisvalor_titulo) return 'Nome da Chave não pode ser vazio';
		return null;
		}

	function armazenar($atualizarNulos = false) {
		$this->arrumarTodos();
		$msg = $this->check();
		if ($msg) return get_class($this).'::checagem para armazenar falhou - '.$msg;
		$valores = formatarSisValor($this->sisvalor_valor);
		

		$sql = new BDConsulta;
		if ($this->sisvalor_titulo) {
			$sql->setExcluir('sisvalores');
			$sql->adOnde('sisvalor_projeto IS NULL');
			$sql->adOnde('sisvalor_titulo = \''.$this->sisvalor_titulo.'\'');
			if (!$sql->exec()) {
				$sql->limpar();
				return get_class($this).'::armazenar falhou: '.db_error();
				}
			}
		foreach ($valores as $chave => $valor) {
			$sql->adTabela('sisvalores');
			$sql->adInserir('sisvalor_titulo', $this->sisvalor_titulo);
			$sql->adInserir('sisvalor_valor_id', $chave);
			$sql->adInserir('sisvalor_valor', $valor[0]);
			if ($valor[1]) $sql->adInserir('sisvalor_chave_id_pai', $valor[1]);
			if (!$sql->exec()) {
				$sql->limpar();
				return get_class($this).'::armazenar falhou: '.db_error();
				}
			$sql->limpar();
			}
		return null;
		}
	function excluir($oid = NULL) {
		$sql = new BDConsulta;
		if ($this->sisvalor_titulo) {
			$sql->setExcluir('sisvalores');
			$sql->adOnde('sisvalor_projeto IS NULL');
			$sql->adOnde('sisvalor_titulo = \''.$this->sisvalor_titulo.'\'');
			if (!$sql->exec()) {
				$sql->limpar();
				return get_class($this).'::excluir falhou <br />'.db_error();
				}
			}
		return null;
		}
	}
	
function formatarSisValor($texto) {
	$sql = new BDConsulta;

	$sep1 = ''; 
	$sep2 = '|'; 
	$sep1 = "\n";
	
	$temp = explode($sep1, $texto);
	$vetor = array();
	foreach ($temp as $item) {
		if ($item) {
			$sep2 = empty($sep2) ? "\n" : $sep2;
			$temp2 = explode($sep2, $item);
			if (isset($temp2[1])) $vetor[trim($temp2[0])] = array(trim($temp2[1]), (isset($temp2[2]) ? trim($temp2[2]) : ''));
			else $vetor[trim($temp2[0])] = trim($temp2[0]);
			}
		}
	return $vetor;
	}
?>