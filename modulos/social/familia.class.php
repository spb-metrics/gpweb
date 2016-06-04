<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


class CFamilia extends CAplicObjeto {
	var $social_familia_id = null;
	var $social_familia_municipio = null;
	var $social_familia_comunidade = null;
	var $social_familia_nome = null;
	var $social_familia_conjuge = null;
	var $social_familia_conjuge_cpf = null;
	var $social_familia_conjuge_rg = null;
	var $social_familia_latitude = null;
	var $social_familia_longitude = null;
	var $social_familia_distancia = null;
	var $social_familia_nascimento = null;
	var $social_familia_cpf = null;
	var $social_familia_cnpj = null;
	var $social_familia_cnes = null;
	var $social_familia_inep = null;
	var $social_familia_nis = null;
	var $social_familia_beneficio_inss = null;
	var $social_familia_rg = null;
	var $social_familia_orgao = null;
	var $social_familia_estado_civil = null;
	var $social_familia_escolaridade = null;
	var $social_familia_filhos = null;
	var $social_familia_nr_dependentes = null;
	var $social_familia_tipo_residencia = null;
	var $social_familia_tipo_coberta = null;
	var $social_familia_comprimento = null;
	var $social_familia_largura = null;
	var $social_familia_lixo = null;
	var $social_familia_esgoto = null;
	var $social_familia_eletrificacao = null;
	var $social_familia_sanitario = null;
	var $social_familia_tratamento_agua = null;
	var $social_familia_tratamento_agua_frequencia = null;
	var $social_familia_distancia_agua = null;
	var $social_familia_ocupacao = null;
	var $social_familia_principal_renda = null;
	var $social_familia_renda_periodo = null;
	var $social_familia_renda_valor = null;
	var $social_familia_renda_capita = null;
	var $social_familia_uso_terra = null;
	var $social_familia_mao_familiar = null;
	var $social_familia_mao_contratada = null;
	var $social_familia_area_propriedade = null;
	var $social_familia_area_producao = null;
	var $social_familia_nr_familias_trabalhar = null;
	var $social_familia_irrigacao = null;
	var $social_familia_tipo_irrigacao = null;
	var $social_familia_assistencia_tecnica = null;
	var $social_familia_observacao = null;
	var $social_familia_data = null;
	var $social_familia_endereco1 = null;
	var $social_familia_endereco2 = null;
	var $social_familia_estado = null;
	var $social_familia_cep = null;
	var $social_familia_pais = null;
	var $social_familia_email = null;
	var $social_familia_dddtel = null;
	var $social_familia_tel = null;
	var $social_familia_dddtel2 = null;
	var $social_familia_tel2 = null;
	var $social_familia_dddcel = null;
	var $social_familia_cel = null;
	var $social_familia_cor = null;
	var $social_familia_ativo = null;
	var $social_familia_sexo = null;
	var $social_familia_chefe = null;
	var $social_familia_sessenta_cinco = null;
	var $social_familia_deficiente_mental = null;
	var $social_familia_bolsa = null;
	var $social_familia_necessita_bolsa = null;
	var $social_familia_sexo_chefe = null;
	var $social_familia_nome_chefe = null;
	var $social_familia_crianca_seis = null;
	var $social_familia_crianca_escola = null;
	var $social_familia_cadastrador = null;
	var $social_familia_uuid = null;
	var $social_familia_via_acesso_casa = null;
	var $social_familia_entrevistado = null;
	var $social_familia_grau_parentesco = null;
	var $social_familia_condicao_casa = null;
	var $social_familia_tipo_coberta_material = null;
	var $social_familia_tipo_energia = null;
	var $social_familia_cisterna = null;

	
	
	
	
	function __construct() {
		parent::__construct('social_familia', 'social_familia_id');
		}

	
	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->social_familia_id) {
			$ret = $sql->atualizarObjeto('social_familia', $this, 'social_familia_id', false);
			$sql->limpar();
			} 
		else {
			$ret = $sql->inserirObjeto('social_familia', $this, 'social_familia_id');
			$sql->limpar();
			}
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		
		$campos_customizados = new CampoCustomizados('social_familia', $this->social_familia_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->social_familia_id);

		$this->gravar_multiplo ('organizacao_social');
		$this->gravar_multiplo ('agua_beber');
		$this->gravar_multiplo ('agua_banho');
		$this->gravar_multiplo ('agua_cozinhar');
		$this->gravar_multiplo ('agua_lavar');
		$this->gravar_multiplo ('agua_agropecuaria');
		$this->gravar_multiplo ('Social_Responsavel_Auxilio');
		
		$this->gravar_multiplo ('agua_fonte');
		
		
		


		$sql->setExcluir('social_familia_producao');
		$sql->adOnde('social_familia_producao_familia = '.$this->social_familia_id);
		$sql->exec();
		$sql->limpar();
		
		$vetor=getParam($_REQUEST, 'cultura_linhas', '');
		$linhas=explode(';',$vetor);
		foreach ($linhas as $linha){
			if ($linha){
				$campos=explode('*', $linha);
				if (isset($campos[0]) && isset($campos[1]) && isset($campos[2])){
					$sql->adTabela('social_familia_producao');
					$sql->adInserir('social_familia_producao_familia', $this->social_familia_id);
					$sql->adInserir('social_familia_producao_cultura', $campos[0]);
					$sql->adInserir('social_familia_producao_finalidade', $campos[1]);
					$sql->adInserir('social_familia_producao_quantidade', str_replace(",", ".", $campos[2]));
					$sql->exec();
					$sql->limpar();
					}
				}
			}
			
		$vetor=getParam($_REQUEST, 'animal_linhas', '');
		$linhas=explode(';',$vetor);
		foreach ($linhas as $linha){
			if ($linha){
				$campos=explode('*', $linha);
				if (isset($campos[0]) && isset($campos[1]) && isset($campos[2])){
					$sql->adTabela('social_familia_producao');
					$sql->adInserir('social_familia_producao_familia', $this->social_familia_id);
					$sql->adInserir('social_familia_producao_animal', $campos[0]);
					$sql->adInserir('social_familia_producao_finalidade', $campos[1]);
					$sql->adInserir('social_familia_producao_quantidade', $campos[2]);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		
		
		
		
		$sql->setExcluir('social_familia_irrigacao');
		$sql->adOnde('social_familia_irrigacao_familia = '.$this->social_familia_id);
		$sql->exec();
		$sql->limpar();
		$vetor=getParam($_REQUEST, 'irrigacao_linhas', '');
		$linhas=explode(';',$vetor);
		foreach ($linhas as $linha){
			if ($linha){
				$campos=explode('*', $linha);
				if (isset($campos[0]) && isset($campos[1]) && isset($campos[2])){
					$sql->adTabela('social_familia_irrigacao');
					$sql->adInserir('social_familia_irrigacao_familia', $this->social_familia_id);
					$sql->adInserir('social_familia_irrigacao_cultura', $campos[0]);
					$sql->adInserir('social_familia_irrigacao_sistema', $campos[1]);
					$sql->adInserir('social_familia_irrigacao_area', $campos[2]);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		
		
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	function gravar_multiplo ($campo=''){
		$sql = new BDConsulta();
		$valores=getParam($_REQUEST, $campo, array());

		$sql->setExcluir('social_familia_opcao');
		$sql->adOnde('social_familia_opcao_familia = '.$this->social_familia_id);
		$sql->adOnde('social_familia_opcao_campo = "'.$campo.'"');
		$sql->exec();
		$sql->limpar();
		foreach($valores as $chave => $valor){
			if($valor!=null){
				$sql->adTabela('social_familia_opcao');
				$sql->adInserir('social_familia_opcao_familia', $this->social_familia_id);
				$sql->adInserir('social_familia_opcao_campo', $campo);
				$sql->adInserir('social_familia_opcao_valor', $valor);
				$sql->exec();
				$sql->limpar();
				}
			}
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

class CFamiliaLog extends CAplicObjeto {
	var $social_familia_log_id = null;
	var $social_familia_log_social = null;
	var $social_familia_log_nome = null;
	var $social_familia_log_descricao = null;
	var $social_familia_log_criador = null;
	var $social_familia_log_criador_nome = null;
	var $social_familia_log_horas = null;
	var $social_familia_log_data = null;
	var $social_familia_log_nd = null;
	var $social_familia_log_categoria_economica = null;	
	var $social_familia_log_grupo_despesa = null;	
	var $social_familia_log_modalidade_aplicacao = null;	
	var $social_familia_log_problema = null;
	var $social_familia_log_referencia = null;
	var $social_familia_log_url_relacionada = null;
	var $social_familia_log_custo = null;
	var $social_familia_log_acesso = null;	
		
	function __construct() {
		parent::__construct('social_familia_log', 'social_familia_log_id');
		$this->social_familia_log_problema = intval($this->social_familia_log_problema);
		}

	
	function arrumarTodos() {
		$descricaoComEspacos = $this->social_familia_log_descricao;
		parent::arrumarTodos();
		$this->social_familia_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->social_familia_log_horas = (float)$this->social_familia_log_horas;
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