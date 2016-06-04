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

class CDept extends CAplicObjeto {
	var $dept_id = null;
	var $dept_superior = null;
	var $dept_cia = null;
	var $dept_nome = null;
	var $dept_codigo = null;
	var $dept_tel = null;
	var $dept_fax = null;
	var $dept_endereco1 = null;
	var $dept_endereco2 = null;
	var $dept_cidade = null;
	var $dept_estado = null;
	var $dept_cep = null;
	var $dept_pais = null;
	var $dept_url = null;
	var $dept_descricao = null;
	var $dept_responsavel = null;
	var $dept_email = null;
	var $dept_tipo = null;
	var $dept_contatos = null;
	var $dept_acesso = null;
	var $dept_nup = null;
	var $dept_qnt_nr = null;
	var $dept_prefixo = null;
	var $dept_sufixo = null;
	var $dept_ordem = null;
	var $dept_ativo = null;
	
	function __construct() {
		parent::__construct('depts', 'dept_id');
		}



	function join($hash) {
		if (!is_array($hash)) return get_class($this)."::unir falhou";
		else {
			$sql = new BDConsulta;
			$sql->unirLinhaAoObjeto($hash, $this);
			$sql->limpar();
			return null;
			}
		}

	function check() {
		if ($this->dept_id && $this->dept_id == $this->dept_superior) return 'N�o pode fazer a si mesmo seu superior('.$this->dept_id.'='.$this->dept_superior.')';
		return null; 
		}

	function armazenar($atualizarNulos = false) {
		global $config, $Aplic;
		$msg = $this->check();
		$sql = new BDConsulta;
		if ($msg)	return get_class($this).'::checagem para armazenar falhou - '.$msg;
		if ($this->dept_id) {
			$ret = $sql->atualizarObjeto('depts', $this, 'dept_id', false);
			$sql->limpar();
			} 
		else {
			
			$ret = $sql->inserirObjeto('depts', $this, 'dept_id');
			$sql->limpar();
			}
		$sql->setExcluir('dept_contatos');
		$sql->adOnde('dept_contato_dept='.(int)$this->dept_id);
		$sql->exec();
		$sql->limpar();
		$dept_contatos=getParam($_REQUEST, 'dept_contatos', null);
		if ($dept_contatos) {
			$contatos = explode(',', $dept_contatos);
			foreach ($contatos as $contato) {
				if ($contato){
					$sql->adTabela('dept_contatos');
					$sql->adInserir('dept_contato_dept', $this->dept_id);
					$sql->adInserir('dept_contato_contato', $contato);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
			
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('depts', $this->dept_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->dept_id);	
			
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}
	
	
	function podeExcluir(&$msg='', $oid = null, $unioes = null) {
		global $config;
		$tabelas[] = array('rotulo' => 'sub'.strtolower($config['departamentos']), 'nome' => 'depts', 'apelido'=>'ds', 'campo_id' => 'dept_id', 'campo_uniao' => 'dept_superior');
		$tabelas[] = array('rotulo' => 'contatos', 'nome' => 'dept_contatos', 'campo_id' => 'dept_contato_dept', 'campo_uniao' => 'dept_contato_dept');
		$tabelas[] = array('rotulo' => 'integrantes', 'nome' => 'contatos', 'campo_id' => 'contato_id', 'campo_uniao' => 'contato_dept');
		return CAplicObjeto::podeExcluir($msg, $oid, $tabelas);
		}
	}

function mostrarDeptSubordinado(&$a, $nivel = 1) {
	global $secao;
	$s='';
	if (permiteAcessarDept($a['dept_acesso'], $a['dept_id'])){
		$s = '<option value="'.$a['dept_id'].'"'.(isset($secao) && $secao == $a['dept_id'] ? 'selected="selected"' : '').'>';
		for ($y = 0; $y < $nivel; $y++) $s .=($y + 1 == $nivel ? '': '&nbsp;&nbsp;');
		$s .= '&nbsp;&nbsp;'.$a['dept_nome'].'</option>';
		}
	return $s;
	}

function acharDeptSubordinado(&$tarr, $superior, $nivel = 1) {
	$nivel = $nivel + 1;
	$n = count($tarr);
	$s='';
	for ($x = 0; $x < $n; $x++) {
		if ($tarr[$x]['dept_superior'] == $superior && $tarr[$x]['dept_superior'] != $tarr[$x]['dept_id']) {
			$s.=mostrarDeptSubordinado($tarr[$x], $nivel);
			$s.=acharDeptSubordinado($tarr, $tarr[$x]['dept_id'], $nivel);
			}
		}
	return $s;	
	}

function adDeptId($dataset, $superior) {
	global $dept_ids;
	foreach ($dataset as $data) {
		if ($data['dept_superior'] == $superior) {
			$dept_ids[] = $data['dept_id'];
			adDeptId($dataset, $data['dept_id']);
			}
		}
	}
?>