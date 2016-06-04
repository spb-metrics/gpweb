<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


class CSuperintendencia extends CAplicObjeto {

	var $social_superintendencia_id = null;
	var $social_superintendencia_responsavel = null;
  var $social_superintendencia_nome = null;
  var $social_superintendencia_cia = null;
  var $social_superintendencia_estado = null;
  var $social_superintendencia_municipio = null;
  var $social_superintendencia_endereco1 = null;
  var $social_superintendencia_endereco2 = null;
  var $social_superintendencia_cep = null;
  var $social_superintendencia_email = null;
  var $social_superintendencia_dddtel = null;
  var $social_superintendencia_tel = null;
  var $social_superintendencia_dddtel2 = null;
  var $social_superintendencia_tel2 = null;
  var $social_superintendencia_dddcel = null;
  var $social_superintendencia_observacao = null;
  var $social_superintendencia_cel = null;
  var $social_superintendencia_cor = null;
  var $social_superintendencia_ativo = null;
	
		
	function __construct() {
		parent::__construct('social_superintendencia', 'social_superintendencia_id');
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->social_superintendencia_id) {
			$ret = $sql->atualizarObjeto('social_superintendencia', $this, 'social_superintendencia_id', false);
			$sql->limpar();
			} 
		else {
			$ret = $sql->inserirObjeto('social_superintendencia', $this, 'social_superintendencia_id');
			$sql->limpar();
			}
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		
		$campos_customizados = new CampoCustomizados('social_superintendencia', $this->social_superintendencia_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->social_superintendencia_id);

		$sql->setExcluir('social_superintendencia_membros');
		$sql->adOnde('social_superintendencia_id = '.$this->social_superintendencia_id);
		$sql->exec();
		$sql->limpar();
		$vetor=getParam($_REQUEST, 'social_superintendencia_membros', '');
		$vetor=explode(',', $vetor);
		foreach ($vetor as $chave => $contato_id){
			if ($contato_id){
				$sql->adTabela('social_superintendencia_membros');
				$sql->adInserir('social_superintendencia_id', $this->social_superintendencia_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		
		
		$sql->setExcluir('social_superintendencia_municipios');
		$sql->adOnde('social_superintendencia_id='.(int)$this->social_superintendencia_id);
		$sql->exec();
		$sql->limpar();
		
		$vetor=getParam($_REQUEST, 'superintendencia_municipios', '');
		if ($vetor) {
			$municipios = explode(',', $vetor);
			foreach ($municipios as $municipio_id) {
				if ($municipio_id){
					$sql->adTabela('social_superintendencia_municipios');
					$sql->adInserir('social_superintendencia_id', $this->social_superintendencia_id);
					$sql->adInserir('municipio_id', $municipio_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		
		
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	
	function excluir($oid = NULL){
		global $Aplic;
		if ($Aplic->getEstado('social_superintendencia_id', null)==$this->social_superintendencia_id) $Aplic->setEstado('social_superintendencia_id', null);
		parent::excluir();
		return null;
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

class CSuperintendenciaLog extends CAplicObjeto {
	var $social_superintendencia_log_id = null;
	var $social_superintendencia_log_social = null;
	var $social_superintendencia_log_nome = null;
	var $social_superintendencia_log_descricao = null;
	var $social_superintendencia_log_criador = null;
	var $social_superintendencia_log_horas = null;
	var $social_superintendencia_log_data = null;
	var $social_superintendencia_log_nd = null;
	var $social_superintendencia_log_categoria_economica = null;	
	var $social_superintendencia_log_grupo_despesa = null;	
	var $social_superintendencia_log_modalidade_aplicacao = null;	
	var $social_superintendencia_log_problema = null;
	var $social_superintendencia_log_referencia = null;
	var $social_superintendencia_log_url_relacionada = null;
	var $social_superintendencia_log_custo = null;
	var $social_superintendencia_log_acesso = null;	
		
	function __construct() {
		parent::__construct('social_superintendencia_log', 'social_superintendencia_log_id');
		$this->social_superintendencia_log_problema = intval($this->social_superintendencia_log_problema);
		}

	
	function arrumarTodos() {
		$descricaoComEspacos = $this->social_superintendencia_log_descricao;
		parent::arrumarTodos();
		$this->social_superintendencia_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->social_superintendencia_log_horas = (float)$this->social_superintendencia_log_horas;
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