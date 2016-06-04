<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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