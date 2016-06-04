<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


class CComunidade extends CAplicObjeto {
	
	var $social_comunidade_id = null;
	var $social_comunidade_municipio = null;
	var $social_comunidade_estado = null;
	var $social_comunidade_nome = null;
	var $social_comunidade_responsavel = null;
	var $social_comunidade_descricao = null;
	var $social_comunidade_cor = null;
	var $social_comunidade_uuid = null;
	
	function __construct() {
		parent::__construct('social_comunidade', 'social_comunidade_id');
		}

	
	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->social_comunidade_id) {
			$ret = $sql->atualizarObjeto('social_comunidade', $this, 'social_comunidade_id', false);
			$sql->limpar();
			} 
		else {
			$ret = $sql->inserirObjeto('social_comunidade', $this, 'social_comunidade_id');
			$sql->limpar();
			}
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		
		$campos_customizados = new CampoCustomizados('social_comunidade', $this->social_comunidade_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->social_comunidade_id);
			
		$social_usuarios=getParam($_REQUEST, 'social_comunidade_usuarios', null);
		$social_usuarios=explode(',', $social_usuarios);
		$sql->setExcluir('social_comunidade_usuarios');
		$sql->adOnde('social_comunidade_id = '.$this->social_comunidade_id);
		$sql->exec();
		$sql->limpar();
		foreach($social_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('social_comunidade_usuarios');
				$sql->adInserir('social_comunidade_id', $this->social_comunidade_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		
		$depts_selecionados=getParam($_REQUEST, 'social_comunidade_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('social_comunidade_depts');
		$sql->adOnde('social_comunidade_id = '.$this->social_comunidade_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('social_comunidade_depts');
				$sql->adInserir('social_comunidade_id', $this->social_comunidade_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		
		

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	function check() {
		return null;
		}

	
	function podeAcessar() {
		$valor = $Aplic->checarModulo('social', 'acesso');
		return $valor;
		}
	
	function podeEditar() {
		$valor = $Aplic->checarModulo('social', 'editar');
		return $valor;
		}
		

	function notificar($post=array()){

		}
	
	}

class CSocialComunidadeLog extends CAplicObjeto {
	var $social_comunidade_log_id = null;
	var $social_comunidade_log_comunidade = null;
	var $social_comunidade_log_nome = null;
	var $social_comunidade_log_descricao = null;
	var $social_comunidade_log_criador = null;
	var $social_comunidade_log_horas = null;
	var $social_comunidade_log_data = null;
	var $social_comunidade_log_nd = null;
	var $social_comunidade_log_categoria_economica = null;	
	var $social_comunidade_log_grupo_despesa = null;	
	var $social_comunidade_log_modalidade_aplicacao = null;	
	var $social_comunidade_log_problema = null;
	var $social_comunidade_log_referencia = null;
	var $social_comunidade_log_url_relacionada = null;
	var $social_comunidade_log_custo = null;
	var $social_comunidade_log_acesso = null;	
		
	function __construct() {
		parent::__construct('social_comunidade_log', 'social_comunidade_log_id');
		$this->social_comunidade_log_problema = intval($this->social_comunidade_log_problema);
		}

	
	function arrumarTodos() {
		$descricaoComEspacos = $this->social_comunidade_log_descricao;
		parent::arrumarTodos();
		$this->social_comunidade_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->social_comunidade_log_horas = (float)$this->social_comunidade_log_horas;
		return null;
		}

	
	function podeAcessar() {
		$valor = $Aplic->checarModulo('social', 'acesso');
		return $valor;
		}
	
	function podeEditar() {
		$valor = $Aplic->checarModulo('social', 'editar');
		return $valor;
		}
	
	function notificar($post=array()){
		}
	
	
	}
	

	
?>