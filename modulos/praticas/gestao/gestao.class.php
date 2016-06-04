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

class CGestao extends CAplicObjeto {

	var $pg_id = null;
  var $pg_nome = null;
	var $pg_usuario = null;
  var $pg_cia = null;
  var $pg_dept = null;
  var $pg_usuario_ultima_alteracao = null;
  var $pg_descricao = null;
  var $pg_ano = null;
  var $pg_inicio = null;
  var $pg_fim = null;
  var $pg_modelo = null;
  var $pg_estrut_org = null;
  var $pg_fornecedores = null;
  var $pg_ultima_alteracao = null;
  var $pg_processos_apoio = null;
  var $pg_processos_finalistico = null;
  var $pg_produtos_servicos = null;
  var $pg_clientes = null;
  var $pg_posgraduados = null;
  var $pg_graduados = null;
  var $pg_nivelmedio = null;
  var $pg_nivelfundamental = null;
  var $pg_semescolaridade = null;
  var $pg_pessoalinterno = null;
  var $pg_programas_acoes = null;
  var $pg_premiacoes = null;
	var $pg_acesso = null;
	var $pg_cor = null;
	var $pg_ativo = null;
	
	
	
	function __construct() {
		parent::__construct('plano_gestao', 'pg_id');
		}
		
	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->pg_id) {
			$ret = $sql->atualizarObjeto('plano_gestao', $this, 'pg_id', false);
			$sql->limpar();
			} 
		else {
			$ret = $sql->inserirObjeto('plano_gestao', $this, 'pg_id');
			$sql->limpar();
			}
		
	
		
		$pg_usuarios=getParam($_REQUEST, 'pg_usuarios', '');
		$pg_usuarios=explode(',', $pg_usuarios);
		$sql->setExcluir('plano_gestao_usuario');
		$sql->adOnde('plano_gestao_usuario_plano = '.$this->pg_id);
		$sql->exec();
		$sql->limpar();
		foreach($pg_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('plano_gestao_usuario');
				$sql->adInserir('plano_gestao_usuario_plano', $this->pg_id);
				$sql->adInserir('plano_gestao_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}
			
		$pg_depts=getParam($_REQUEST, 'pg_depts', null);
		$pg_depts=explode(',', $pg_depts);
		$sql->setExcluir('plano_gestao_dept');
		$sql->adOnde('plano_gestao_dept_plano = '.$this->pg_id);
		$sql->exec();
		$sql->limpar();
		foreach($pg_depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('plano_gestao_dept');
				$sql->adInserir('plano_gestao_dept_plano', $this->pg_id);
				$sql->adInserir('plano_gestao_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}	
			
		if ($Aplic->profissional){
			$sql->setExcluir('plano_gestao_cia');
			$sql->adOnde('plano_gestao_cia_plano='.(int)$this->pg_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'plano_gestao_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('plano_gestao_cia');
						$sql->adInserir('plano_gestao_cia_plano', $this->pg_id);
						$sql->adInserir('plano_gestao_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}	
			}
			
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}	
		
		
	function check() {
		$this->pg_id = intval($this->pg_id);
		return null; 
		}
		
	function excluir($id=null) {
		global $Aplic;
		$this->_mensagem = "excluido";
		if ($Aplic->getEstado('pg_id', null)==$this->pg_id) $Aplic->setEstado('pg_id', null);
		parent::excluir();
		return null;
		}
	
	
	function podeAcessar() {
		$valor=permiteAcessarPlanoGestao($this->pg_acesso, $this->pg_id);
		return $valor;
		}
	
	function podeEditar() {
		$valor=permitePlanoGestao($this->pg_acesso, $this->pg_id);
		return $valor;
		}
		
	}
	
	

?>