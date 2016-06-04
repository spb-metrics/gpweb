<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


class CComite extends CAplicObjeto {

	var $social_comite_id = null;
	var $social_comite_responsavel = null;
  var $social_comite_nome = null;
  var $social_comite_tipo = null;
  var $social_comite_estado = null;
  var $social_comite_municipio = null;
  var $social_comite_comunidade = null;
  var $social_comite_endereco1 = null;
  var $social_comite_endereco2 = null;
  var $social_comite_cep = null;
  var $social_comite_email = null;
  var $social_comite_dddtel = null;
  var $social_comite_tel = null;
  var $social_comite_dddtel2 = null;
  var $social_comite_tel2 = null;
  var $social_comite_dddcel = null;
  var $social_comite_observacao = null;
  var $social_comite_cel = null;
  var $social_comite_cor = null;
  var $social_comite_ativo = null;
	
		
	function __construct() {
		parent::__construct('social_comite', 'social_comite_id');
		}

	
	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->social_comite_id) {
			$ret = $sql->atualizarObjeto('social_comite', $this, 'social_comite_id', false);
			$sql->limpar();
			} 
		else {
			$ret = $sql->inserirObjeto('social_comite', $this, 'social_comite_id');
			$sql->limpar();
			}
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		
		$campos_customizados = new CampoCustomizados('social_comite', $this->social_comite_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->social_comite_id);

		
		
		$sql->setExcluir('social_comite_membros');
		$sql->adOnde('social_comite_id = '.$this->social_comite_id);
		$sql->exec();
		$sql->limpar();
		$vetor=getParam($_REQUEST, 'social_comite_membros', '');
		$vetor=explode(',', $vetor);
		foreach ($vetor as $chave => $contato_id){
			if ($contato_id){
				$sql->adTabela('social_comite_membros');
				$sql->adInserir('social_comite_id', $this->social_comite_id);
				$sql->adInserir('contato_id', $contato_id);
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
		global $perms;
		//$valor=permiteAcessarSocial($this->social_acesso, $this->social_id);
		$valor = $Aplic->checarModulo('social', 'acesso');
		return $valor;
		}
	
	function podeEditar() {
		//$valor=permiteEditarSocial($this->social_acesso, $this->social_id);
		$valor = $Aplic->checarModulo('social', 'editar');
		return $valor;
		}
		

	function notificar($post=array()){

		}
	
	}

class CComiteLog extends CAplicObjeto {
	var $social_comite_log_id = null;
	var $social_comite_log_social = null;
	var $social_comite_log_nome = null;
	var $social_comite_log_descricao = null;
	var $social_comite_log_criador = null;
	var $social_comite_log_horas = null;
	var $social_comite_log_data = null;
	var $social_comite_log_nd = null;
	var $social_comite_log_categoria_economica = null;	
	var $social_comite_log_grupo_despesa = null;	
	var $social_comite_log_modalidade_aplicacao = null;	
	var $social_comite_log_problema = null;
	var $social_comite_log_referencia = null;
	var $social_comite_log_url_relacionada = null;
	var $social_comite_log_custo = null;
	var $social_comite_log_acesso = null;	
		
	function __construct() {
		parent::__construct('social_comite_log', 'social_comite_log_id');
		$this->social_comite_log_problema = intval($this->social_comite_log_problema);
		}

	
	function arrumarTodos() {
		$descricaoComEspacos = $this->social_comite_log_descricao;
		parent::arrumarTodos();
		$this->social_comite_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->social_comite_log_horas = (float)$this->social_comite_log_horas;
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