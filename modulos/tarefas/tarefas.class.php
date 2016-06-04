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
global $Aplic;
require_once ($Aplic->getClasseSistema('libmail'));
require_once ($Aplic->getClasseSistema('aplic'));
require_once ($Aplic->getClasseModulo('projetos'));
require_once ($Aplic->getClasseSistema('evento_recorrencia'));
require_once ($Aplic->getClasseSistema('data'));

$percentual = getSisValor('TarefaPorcentagem','','','sisvalor_id');


$filtros = array('meu' => 'Minhas '.ucfirst($config['tarefa']), 'minhasIncompletas' => 'Minh'.$config['genero_tarefa'].'s '.$config['tarefas'].' incompletas', 'todasIncompletas' => 'Tod'.$config['genero_tarefa'].'s '.$config['tarefas'].' incompletas', 'meuProj' => 'Meus '.$config['projetos'], 'minhaCia' => 'Tod'.$config['genero_tarefa'].'s '.$config['tarefas'].' para minh'.$config['genero_organizacao'].' '.$config['organizacao'], 'semDesignado' => 'Tod'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).' (sem '.$config['usuario'].' designado)', 'tarefaCriada' => 'Tod'.$config['genero_tarefa'].'s '.$config['tarefas'].' que eu criei', 'todos' => 'Tod'.$config['genero_tarefa'].'s '.$config['tarefas'], 'todasterminadas7dias' => 'Tod'.$config['genero_tarefa'].'s '.$config['tarefas'].' terminadas nos últimos 7 dias', 'minhasterminadas7dias' => 'Minh'.$config['genero_tarefa'].'s '.$config['tarefas'].' terminadas nos últimos 7 dias');
$status = getSisValor('StatusTarefa');
$prioridade = getSisValor('PrioridadeTarefa');
$tarefa_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$dinamicas_seguidas = array('0' => '0', '1' => '1');

class CTarefa extends CAplicObjeto {
	var $tarefa_id = null;
	var $tarefa_nome = null;
	var $tarefa_cia = null;
	var $tarefa_dept = null;
	var $tarefa_superior = null;
	var $tarefa_marco = null;
	var $tarefa_projeto = null;
	var $tarefa_comunidade = null;
	var $tarefa_social = null;
	var $tarefa_acao = null;
	var $tarefa_principal_indicador = null;
	var $tarefa_dono = null;
	var $tarefa_inicio = null;
	var $tarefa_inicio_manual = null;
	var $tarefa_inicio_calculado = null;
	var $tarefa_duracao = null;
	var $tarefa_duracao_manual = null;
	var $tarefa_duracao_tipo = null;
	var $tarefa_horas_trabalhadas = null;
	var $tarefa_fim = null;
	var $tarefa_fim_manual = null;
	var $tarefa_status = null;
	var $tarefa_prioridade = null;
	var $tarefa_percentagem = null;
	var $tarefa_percentagem_data = null;
	var $tarefa_descricao = null;
	var $tarefa_custo_almejado = null;
	var $tarefa_url_relacionada = null;
	var $tarefa_criador = null;
	var $tarefa_ordem = null;
	var $tarefa_cliente_publicada = null;
	var $tarefa_dinamica = null;
	var $tarefa_acesso = null;
	var $tarefa_notificar = null;
	var $tarefa_customizado = null;
	var $tarefa_tipo = null;
	var $tarefa_adquirido = null;
	var $tarefa_previsto = null;
	var $tarefa_realizado = null;
	var $tarefa_onde = null;
	var $tarefa_porque = null;
	var $tarefa_como = null;
	var $tarefa_custo = null;
	var $tarefa_gasto = null;
	var $tarefa_endereco1 ='';
	var $tarefa_endereco2 ='';
	var $tarefa_cidade ='';
	var $tarefa_estado ='';
	var $tarefa_cep ='';
	var $tarefa_pais ='';
	var $tarefa_latitude ='';
	var $tarefa_longitude ='';
	var $tarefa_emprego_obra = null;
	var $tarefa_emprego_direto = null;
	var $tarefa_emprego_indireto = null;
	var $tarefa_populacao_atendida = null;
	var $tarefa_forma_implantacao = null;
	var $tarefa_codigo = null;
	var $tarefa_sequencial = null;
	var $tarefa_setor = null;
	var $tarefa_segmento = null;
	var $tarefa_intervencao = null;
	var $tarefa_tipo_intervencao = null;
	var $tarefa_ano = null;
	var $tarefa_unidade = null;
	var $tarefa_numeracao = null;
	var $tarefa_gerenciamento = null;
	var $tarefa_situacao_atual = null;
	var $tarefas_subordinadas = null;
	var $tarefa_alerta = null;
	var $tarefa_projetoex_id = null;
	var $tarefa_tarefaex_id = null;
	var $incluir_subordinadas = false;
 	var $baseline_id = null;

	function __construct($baseline_id=null, $incluir_subordinadas=false) {
		$this->incluir_subordinadas=$incluir_subordinadas;
		if ($baseline_id) {
			$this->baseline_id=$baseline_id;
			parent::__construct('baseline_tarefas', 'tarefa_id','baseline_id');
			}
		else {
			parent::__construct('tarefas', 'tarefa_id');
			}
		}

	function load($oid = null, $tira = false, $id2 = null) {
		$carregado = parent::load($oid, $tira);

		if ($this->incluir_subordinadas) {
			$this->subordinadas(null, $this->baseline_id);
			$this->tarefas_subordinadas=implode(',', $this->tarefas_subordinadas);
			}
		else $this->tarefas_subordinadas=$this->tarefa_id;
		return $carregado;
		}

	function armazenar($atualizarNulos = false, $sem_chave_estrangeira=false) {
		global $Aplic;
		$sql = new BDConsulta;
		$this->arrumarTodos();
		$importando_tarefas = false;
		$msg = $this->check();
		if ($msg) {
			$msg_retorno = array(get_class($this).':: checagem de armazenamento', 'falhou', '-');
			if (is_array($msg)) return array_merge($msg_retorno, $msg);
			else {
				array_push($msg_retorno, $msg);
				return $msg_retorno;
				}
			}

    $this->tarefa_inicio_manual = $this->tarefa_inicio;
    $this->tarefa_fim_manual = $this->tarefa_fim;
    $this->tarefa_duracao_manual = $this->tarefa_duracao;
    
		if ($this->tarefa_id) {
			//atualizar

			$sql = new BDConsulta;
			$sql->adTabela('tarefas');
			$sql->adCampo('diferenca_data("'.$this->tarefa_fim.'", tarefa_fim)');
			$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
			$diferenca=$sql->Resultado();
			$sql->limpar();
			if ($this->tarefa_inicio == '') $this->tarefa_inicio = null;
			if ($this->tarefa_fim == '')	$this->tarefa_fim = null;


			$this->_acao = 'atualizada';
			global $oTar;
			$oTar = new CTarefa();
			$oTar->olhar((int)$this->tarefa_id);
			if ($this->tarefa_status != $oTar->tarefa_status) $this->atualizarStatusSubTarefas($this->tarefa_status);
			if ($this->tarefa_projeto != $oTar->tarefa_projeto) $this->atualizarSubTarefasProjeto($this->tarefa_projeto);
			$this->check();
			$ret = $sql->atualizarObjeto('tarefas', $this, 'tarefa_id', true, array('baseline_id', 'tarefas_subordinadas', 'incluir_subordinadas'));
			$sql->limpar();
			}
		else {
			$this->_acao = 'adicionada';
			if ($this->tarefa_inicio == '') $this->tarefa_inicio = null;
			if ($this->tarefa_fim == '') $this->tarefa_fim = null;
			$ret = $sql->inserirObjeto('tarefas', $this, 'tarefa_id');
			$sql->limpar();
			if (!$this->tarefa_superior) {
				$sql->adTabela('tarefas');
				$sql->adAtualizar('tarefa_superior', (int)$this->tarefa_id);
				$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
				$sql->exec();
				$sql->limpar();
				}
			else $importando_tarefas = true;
			$sql->adTabela('tarefa_designados');
			$sql->adInserir('usuario_id', $Aplic->usuario_id);
			$sql->adInserir('tarefa_id', (int)$this->tarefa_id);
			$sql->adInserir('usuario_admin', '0');
			$sql->exec();
			$sql->limpar();
			}

		$sql->setExcluir('tarefa_depts');
		$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
		$sql->exec();
		$sql->limpar();

		$depts=getParam($_REQUEST, 'tarefa_depts', '');
		$depts=explode(',', $depts);
		if (count($depts)) {
			foreach ($depts as $secao) {
				if($secao){
					$sql->adTabela('tarefa_depts');
					$sql->adInserir('tarefa_id', (int)$this->tarefa_id);
					$sql->adInserir('departamento_id', $secao);
					if ($sem_chave_estrangeira) $sql->sem_chave_estrangeira();
					$sql->exec();
					$sql->limpar();
					}
				}
			}

		$sql->setExcluir('municipio_lista');
		$sql->adOnde('municipio_lista_tarefa='.(int)$this->tarefa_id);
		$sql->exec();
		$sql->limpar();

		$municipios=getParam($_REQUEST, 'tarefa_municipios', '');
		$municipios=explode(',', $municipios);
		if (count($municipios)) {
			foreach ($municipios as $municipio_id) {
				if ($municipio_id){
					$sql->adTabela('municipio_lista');
					$sql->adInserir('municipio_lista_tarefa', (int)$this->tarefa_id);
					$sql->adInserir('municipio_lista_projeto', (int)$this->tarefa_projeto);
					$sql->adInserir('municipio_lista_municipio', $municipio_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}

		$sql->setExcluir('tarefa_contatos');
		$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
		$sql->exec();
		$sql->limpar();

		$contatos=getParam($_REQUEST, 'tarefa_contatos', '');
		$contatos=explode(',', $contatos);
		if (count($contatos)) {
			foreach ($contatos as $contato) {
				if($contato){
					$sql->adTabela('tarefa_contatos');
					$sql->adInserir('tarefa_id', (int)$this->tarefa_id);
					$sql->adInserir('contato_id', $contato);
					$sql->exec();
					$sql->limpar();
					}
				}
			}


		if ($Aplic->profissional){
		$sql->setExcluir('tarefa_cia');
		$sql->adOnde('tarefa_cia_tarefa='.(int)$this->tarefa_id);
		$sql->exec();
		$sql->limpar();
		$cias=getParam($_REQUEST, 'tarefa_cias', '');
		$cias=explode(',', $cias);
		if (count($cias)) {
			foreach ($cias as $cia_id) {
				if ($cia_id){
					$sql->adTabela('tarefa_cia');
					$sql->adInserir('tarefa_cia_tarefa', $this->tarefa_id);
					$sql->adInserir('tarefa_cia_cia', $cia_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		}


		if (isset($_REQUEST['uuid']) && $_REQUEST['uuid'] && $Aplic->profissional){
			$sql->adTabela('tarefa_entrega');
			$sql->adAtualizar('tarefa_entrega_tarefa', (int)$this->tarefa_id);
			$sql->adAtualizar('tarefa_entrega_uuid', null);
			$sql->adOnde('tarefa_entrega_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('tarefas', $this->tarefa_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->tarefa_id);

		if (!$ret) return get_class($this).':: armazenamento falhou <br />'.db_error();
		else return null;
		}

	function excluir($oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('tarefa_id', null)==$this->tarefa_id) $Aplic->setEstado('tarefa_id', null);
		parent::excluir();
		$this->_acao = 'excluída';
		return null;
		}

	function verificar_dependencia_circular($tarefa_id=0, $possivel_dependencia=0){
		if (!$this->tarefa_id) $this->tarefa_id=$tarefa_id;
		$achou_circular=0;
		$this->dependentes((int)$this->tarefa_id, $achou_circular, $possivel_dependencia);
		return $achou_circular;
		}


	function dependentes($tarefa_id=0, &$achou_circular, $possivel_dependencia=0){
		if ($achou_circular) return true;
		$sql = new BDConsulta;
		$sql->adTabela('tarefa_dependencias');
		$sql->adCampo('dependencias_req_tarefa_id');
		$sql->adOnde('dependencias_tarefa_id='.(int)$tarefa_id);
		$dependencias=$sql->carregarColuna();
		if ($possivel_dependencia) $dependencias[]=$possivel_dependencia;
		$sql->limpar();
		foreach($dependencias as $chave => $dependente_id) {
			if ($this->tarefa_id==$dependente_id) {
				$achou_circular=1;
				break;
				}
			$this->dependentes($dependente_id, $achou_circular);
			}
		}



	function __toString() {
		return $this->link.'/'.$this->type.'/'.$this->length;
		}

	function check() {
		global $Aplic, $config;
		$this->tarefa_marco = null;
		$this->tarefa_dinamica = intval($this->tarefa_dinamica);
		$this->tarefa_percentagem = intval($this->tarefa_percentagem);
		$this->tarefa_custo_almejado = $this->tarefa_custo_almejado ? $this->tarefa_custo_almejado : 0.00;
		if (!$this->tarefa_criador) $this->tarefa_criador = $Aplic->usuario_id;
		if (!$this->tarefa_duracao_tipo) $this->tarefa_duracao_tipo = 1;
		static $editar;
		if (!isset($editar)) $editar = getParam($_REQUEST, 'fazerSQL', '') == 'fazer_tarefa_aed' ? true : false;
		$esta_dependencias = array();
		return null;
		}



	function mudar_dependencia($tarefas='', $dependencias=''){
		$tarefas=explode(',',$tarefas);
		$dependencias=explode(',',$dependencias);

		$subordinada_inicio=false;
		$subordinada_fim=false;

		$sql = new BDConsulta;
		$sql->setExcluir('tarefa_dependencias');
		$sql->adOnde('dependencias_tarefa_id = '.(int)$this->tarefa_id);
		$sql->exec();
		$sql->limpar();

		if ($this->tarefa_dinamica){

			$sql->adTabela('tarefas');
			$sql->adCampo('min(tarefa_inicio)');
			$sql->adOnde('tarefa_superior = '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_id != '.(int)$this->tarefa_id);
			$inicio=$sql->comando_sql();
			$sql->Limpar();

			$sql->adTabela('tarefas');
			$sql->adCampo('max(tarefa_fim)');
			$sql->adOnde('tarefa_superior = '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_id != '.(int)$this->tarefa_id);
			$fim=$sql->comando_sql();
			$sql->Limpar();


			$sql->adTabela('tarefas');
			$sql->adCampo('tarefa_id');
			$sql->adOnde('tarefa_superior = '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_id != '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_inicio =('.$inicio.')');
			$subordinada_inicio=$sql->Resultado();
			$sql->Limpar();

			$sql->adTabela('tarefas');
			$sql->adCampo('tarefa_id');
			$sql->adOnde('tarefa_superior = '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_id != '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_fim =('.$fim.')');
			$subordinada_fim=$sql->Resultado();
			$sql->Limpar();
			}

		foreach($dependencias AS $chave => $valor){
			$qnt_latencia='';
			$tipo_latencia='';
			$dependencia=0;
			//verifico se tem latencia
			$valor=explode(':',$valor);
			if (isset($valor[1]) && $valor[1]) {
				$tipo_latencia=substr($valor[1],0,1);
				$qnt_latencia=substr($valor[1],1);
				}

			$dependencia_original=$tarefas[$chave];
			$tipo=$valor[0];

			//caso seja tarefa dinamica colocar dependencia filho
			if ($subordinada_inicio || $subordinada_fim){
				if(!$this->verificar_dependencia_circular(($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim), $dependencia_original)){

					//verificarse já existe esta dependencia
					$sql->adTabela('tarefa_dependencias');
					$sql->adCampo('count(dependencias_tarefa_id)');
					$sql->adOnde('dependencias_tarefa_id = '. ($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim));
					$sql->adOnde('dependencias_req_tarefa_id = '.$dependencia_original);
					$existe=$sql->Resultado();
					$sql->Limpar();

					if (!$existe && $dependencia_original && ($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim)){
						$sql->adTabela('tarefa_dependencias');
						$sql->adInserir('dependencias_tarefa_id', ($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim));
						$sql->adInserir('dependencias_req_tarefa_id', $dependencia_original);
						$sql->adInserir('tipo_dependencia', $tipo);
						if ($qnt_latencia) $sql->adInserir('latencia', $qnt_latencia);
						if ($tipo_latencia) $sql->adInserir('tipo_latencia', $tipo_latencia);
						$sql->exec();
						$sql->Limpar();
						}
					}
				}
			else if(!$this->verificar_dependencia_circular((int)$this->tarefa_id, $dependencia_original)){
				if ($dependencia_original){
					$sql->adTabela('tarefa_dependencias');
					$sql->adInserir('dependencias_tarefa_id', (int)$this->tarefa_id);
					$sql->adInserir('dependencias_req_tarefa_id', $dependencia_original);
					$sql->adInserir('tipo_dependencia', $tipo);
					if ($qnt_latencia) $sql->adInserir('latencia', $qnt_latencia);
					if ($tipo_latencia) $sql->adInserir('tipo_latencia', $tipo_latencia);
					$sql->exec();
					$sql->Limpar();
					}
				}
			}
		}



	function getQuantidadeAdquirida($baseline_id=0, $pratica_indicador_id=null){
		return quantidadeAdquirida($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $pratica_indicador_id);
		}

	function getQuantidadePrevista($baseline_id=0, $pratica_indicador_id=null){
		return quantidadePrevista($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $pratica_indicador_id);
		}

	function getQuantidadeRealizada($baseline_id=0, $pratica_indicador_id=null){
		return quantidadeRealizada($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $pratica_indicador_id);
		}

	function getRealizadaPrevista($baseline_id=0, $pratica_indicador_id=null){
		return realizadaPrevista($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $pratica_indicador_id);
		}

	function getAdquiridaPrevista($baseline_id=0, $pratica_indicador_id=null){
		return adquiridaPrevista($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $pratica_indicador_id);
		}

	function getEmpregosObra($baseline_id=false, $indicador_id=false){
		global $Aplic;
		$lista='';
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('tarefa_emprego_obra');
		$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$quantidade=$sql->Resultado();
		$sql->limpar();
		return $quantidade;
		}

	function getEmpregosDiretos($baseline_id=false, $indicador_id=false){
		global $Aplic;
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('tarefa_emprego_direto');
		$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$quantidade=$sql->Resultado();
		$sql->limpar();
		return $quantidade;
		}

	function getEmpregosIndiretos($baseline_id=false, $indicador_id=false){
		global $Aplic;
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('tarefa_emprego_indireto');
		$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$quantidade=$sql->Resultado();
		$sql->limpar();
		return $quantidade;
		}

	function getTotalRecursosFinanceiros($baseline_id=false, $indicador_id=false) {
		global $Aplic;

		$sql = new BDConsulta();
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_tarefas','recurso_tarefas');
		$sql->esqUnir('recursos','recursos','recurso_tarefas.recurso_id=recursos.recurso_id');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','tarefas.tarefa_id=recurso_tarefas.tarefa_id');
		$sql->adCampo('SUM(recurso_tarefas.recurso_quantidade)');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)){
			$sql->adOnde('recurso_tarefas.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$sql->adOnde('tarefas.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			}
		$sql->adOnde('recurso_tarefas.tarefa_id= '.(int)$this->tarefa_id);
		$sql->adOnde('recursos.recurso_tipo=5');
		$total=$sql->Resultado();
		$sql->Limpar();
		return $total;
		}





	function recurso_previsto($data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=false, $indicador_id=false){
		return recurso_previsto($this->tarefa_projeto, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	function mao_obra_previsto($data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return mao_obra_previsto($this->tarefa_projeto, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	function mao_obra_gasto($baseline_id=0){
		return mao_obra_gasto($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id));
		}

	function homem_hora($baseline_id=0, $indicador_id=false){
		return homem_hora($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	function custo_previsto($data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return custo_previsto($this->tarefa_projeto, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	function financeiro_velocidade($data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return financeiro_velocidade($this->tarefa_projeto, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}


	function pagamento($baseline_id=0, $tipo=null, $no_ano=true, $inicio='', $fim=''){
		return pagamento($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $tipo, $no_ano, $inicio, $fim);
		}

	function custo_gasto($baseline_id=0){
		return custo_gasto($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id));
		}

	function fisico_previsto($data='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return fisico_previsto($this->tarefa_projeto, $data, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}


	function fisico_velocidade($data='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return fisico_velocidade($this->tarefa_projeto, $data, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}


	function recurso_gasto($baseline_id=false, $indicador_id=false){
		return recurso_gasto($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $this->tarefas_subordinadas, $indicador_id);
		}

	function recurso_valor_agregado($baseline_id=false, $indicador_id=false){
		return recurso_valor_agregado($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	function recurso_EPT($baseline_id=false, $indicador_id=false){
		return recurso_EPT($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

 function ata_acao($baseline_id=false, $indicador_id=false){
		return ata_acao($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

 	function mao_obra_valor_agregado($baseline_id=0, $indicador_id=false){
		return mao_obra_valor_agregado($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	function mao_obra_EPT($baseline_id=0, $indicador_id=false){
		return mao_obra_EPT($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	function custo_valor_agregado($baseline_id=false, $indicador_id=false){
		return custo_valor_agregado($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	function custo_EPT($baseline_id=false, $indicador_id=false){
		return custo_EPT($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	function getCodigo($completo=true){

		if ($this->tarefa_sequencial) $this->setSequencial();

		$sql = new BDConsulta;
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_ano');
		$sql->adOnde('projeto_id='.$this->tarefa_projeto);
		$ano=$sql->Resultado();
		$sql->limpar();

		if ($this->tarefa_sequencial<10) $sequencial='000'.$this->tarefa_sequencial;
		elseif ($this->tarefa_sequencial<100) $sequencial='00'.$this->tarefa_sequencial;
		elseif ($this->tarefa_sequencial<1000) $sequencial='0'.$this->tarefa_sequencial;
		else $sequencial=$this->tarefa_sequencial;


		if ($this->tarefa_projeto<10) $id='000'.$this->tarefa_projeto;
		elseif ($this->tarefa_projeto<100) $id='00'.$this->tarefa_projeto;
		elseif ($this->tarefa_projeto<1000) $id='0'.$this->tarefa_projeto;
		else $id=$this->tarefa_projeto;

		if ($this->tarefa_setor && $sequencial){
			return $this->tarefa_setor.($completo && $this->tarefa_segmento ? '.' : '').substr($this->tarefa_segmento, 2).($completo && $this->tarefa_intervencao ? '.' : '').substr($this->tarefa_intervencao, 4).($completo && $this->tarefa_tipo_intervencao ? '.' : '').substr($this->tarefa_tipo_intervencao, 6).($completo ? '.' : '').$sequencial.($completo ? '/' : '').$id;
			}
		elseif ($this->tarefa_tipo && $this->tarefa_sequencial){
			return $this->tarefa_tipo.($completo ? '.' : '').$sequencial.($completo  ? '/'.$id : '');
			}
		else return '';
		}


	function setSequencial(){
		if (!$this->tarefa_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('tarefas');
			$sql->adCampo('max(tarefa_sequencial)');
			$sql->adOnde('tarefa_projeto='.(int)$this->tarefa_projeto);
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tarefas');
			$sql->adAtualizar('tarefa_sequencial', ($maior_sequencial+1));
			$sql->adOnde('tarefa_id ='.(int)$this->tarefa_id);
			$retorno=$sql->exec();
			$sql->Limpar();
			$this->tarefa_sequencial=($maior_sequencial+1);
			return $retorno;
			}
		}

	function getSetor(){
		if ($this->tarefa_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TarefaSetor"');
			$sql->adOnde('sisvalor_valor_id="'.$this->tarefa_setor.'"');
			$tarefa_setor= $sql->Resultado();
			$sql->limpar();
			return $tarefa_setor;
			}
		else return '';
		}

	function getSegmento(){
		if ($this->tarefa_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'TarefaSegmento\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->tarefa_segmento.'\'');
			$tarefa_segmento= $sql->Resultado();
			$sql->limpar();
			return $tarefa_segmento;
			}
		else return '';
		}

	function getIntervencao(){
		if ($this->tarefa_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'TarefaIntervencao\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->tarefa_intervencao.'\'');
			$tarefa_intervencao= $sql->Resultado();
			$sql->limpar();
			return $tarefa_intervencao;
			}
		else return '';
		}

	function getTipoIntervencao(){
		if ($this->tarefa_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'TarefaTipoIntervencao\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->tarefa_tipo_intervencao.'\'');
			$tarefa_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $tarefa_tipo_intervencao;
			}
		else return '';
		}

	function subordinadas($tarefa_pai=0, $baseline_id=false){
		if (!$tarefa_pai) $tarefa_pai=(int)$this->tarefa_id;

		$this->tarefas_subordinadas[$tarefa_pai]=(int)$tarefa_pai;

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas', 'tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_superior ='.(int)$tarefa_pai.' AND tarefa_id!='.(int)$tarefa_pai);
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$lista=$sql->carregarColuna();
		$sql->limpar();
		foreach($lista as $chsve => $valor){
      if(!isset($this->tarefas_subordinadas[$valor])){
			  $this->tarefas_subordinadas[$valor]=(int)$valor;
			  $this->subordinadas($valor, ($this->baseline_id ? $this->baseline_id : $baseline_id));
        }
			}
		}

	function custo_estimado($baseline_id=false, $indicador_id=false){
		global $Aplic, $config;
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefa_custos','tarefa_custos');
		$sql->adCampo('SUM((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS total');
		$sql->adOnde('tarefa_custos_tarefa IN ('.($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id).')');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
		$total=$sql->Resultado();
		$sql->Limpar();
		return $total;

		}

	function gasto_efetuado($baseline_id=false, $indicador_id=false){
		global $Aplic, $config;
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefa_gastos', 'tarefa_gastos');
		$sql->adCampo('SUM((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS total');
		$sql->adOnde('tarefa_gastos_tarefa IN ('.($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id).')');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('tarefa_gastos.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
		$total=$sql->Resultado();
		$sql->Limpar();
		return $total;
		}

	function gasto_registro($baseline_id=false, $indicador_id=false){
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefa_log', 'tarefa_log');
		$sql->adCampo('SUM(tarefa_log_custo) AS total');
		$sql->adOnde('tarefa_log_tarefa IN ('.($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id).')');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$total=$sql->Resultado();
		$sql->Limpar();
		return $total;
		}

	function olhar($oid = null, $tira = false) {
		$meCarregue = $this->load($oid, $tira, true);
		return $meCarregue;
		}

	function copiar($destProjeto_id = 0, $destTarefa_id = -1) {
		$novoObj = $this->duplicar();
		if ($destProjeto_id != 0) $novoObj->tarefa_projeto = (int)$destProjeto_id;
		if ($destTarefa_id == 0) $novoObj->tarefa_superior = (int)$novoObj->tarefa_id;
		elseif ($destTarefa_id > 0) $novoObj->tarefa_superior = (int)$destTarefa_id;
		$novoObj->armazenar(false, true);
		return $novoObj;
		}

	function copiaProfunda($destProjeto_id = 0, $destTarefa_id = 0) {
		$subordinada = $this->getSubordinada();
		$novoObj = $this->copiar($destProjeto_id, $destTarefa_id);
		$novo_id = (int)$novoObj->tarefa_id;
		if (!empty($subordinada)) {
			$tempTarefa = new CTarefa();
			foreach ($subordinada as $sub) {
				$tempTarefa->olhar($sub);
				$tempTarefa->htmlDecodificar($sub);
				$novaSubordinada = $tempTarefa->copiaProfunda($destProjeto_id, $novo_id);
				$novaSubordinada->armazenar();
				}
			}
		return $novoObj;
		}

	function mover($destProjeto_id = 0, $destTarefa_id = -1) {
		if ($destProjeto_id != 0) $this->tarefa_projeto = $destProjeto_id;
		if ($destTarefa_id == 0) $this->tarefa_superior = (int)$this->tarefa_id;
		elseif ($destTarefa_id > 0) $this->tarefa_superior = (int)$destTarefa_id;
		}

	function moverProfundo($destProjeto_id = 0, $destTarefa_id = 0) {
		$this->mover($destProjeto_id, $destTarefa_id);
		$subordinada = $this->getSubordinadaProfunda();
		if (!empty($subordinada)) {
			$tempSubordinada = new CTarefa();
			foreach ($subordinada as $sub) {
				$tempSubordinada->olhar($sub);
				$tempSubordinada->htmlDecodificar($sub);
				$tempSubordinada->moverProfundo($destProjeto_id, (int)$this->tarefa_id);
				$tempSubordinada->armazenar();
				}
			}
		}





	function notificarResponsavel($comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;

		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('tarefas');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tarefa_dono');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
		$linha = $sql->linha();
		$sql->limpar();
		$corpo_email='';
		if ($linha['usuario_id']) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['tarefa']).' Excluid'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['tarefa']).' Atualizad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			else $titulo=ucfirst($config['tarefa']).' Criad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi atualizad'.$config['genero_tarefa'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi criad'.$config['genero_tarefa'].'.</b><br>';
			$corpo .= '<br><br>(Você está recebendo este e-mail por ser o responsável pel'.$config['genero_tarefa'].' '.$config['tarefa'].')<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_tarefa($this->tarefa_id,'',true, '', true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Responsável pela exclusão:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;
			$corpo_externo=$corpo;

			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$this->tarefa_id.'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
			$validos=0;


			if ($linha['usuario_id']) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
			if ($email->EmailValido($linha['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
				if ($Aplic->profissional){
					require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
					$email = new Mail;

					$email->De($config['email'], $Aplic->usuario_nome);

                    if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                        $email->ResponderPara($Aplic->usuario_email);
                        }
                    else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                        $email->ResponderPara($Aplic->usuario_email2);
                        }

					if ($email->EmailValido($linha['contato_email'])) {
						$endereco=link_email_externo($linha['usuario_id'], 'm=tarefas&a=ver&tarefa_id='.$this->tarefa_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
						$email->Assunto($titulo, $localidade_tipo_caract);
						$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
						$email->Para($linha['contato_email'], true);
						$email->Enviar();
						}
					}
				else {
					$validos++;
					$email->Para($linha['contato_email'], true);
					}
				}

			if ($validos) $email->Enviar();
			}
		}


	function notificarContatos($comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;

		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('tarefa_contatos');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = tarefa_contatos.contato_id');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
		$usuarios = $sql->Lista();
		$sql->limpar();
		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['tarefa']).' Excluid'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['tarefa']).' Atualizad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			else $titulo=ucfirst($config['tarefa']).' Criad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi atualizad'.$config['genero_tarefa'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi criad'.$config['genero_tarefa'].'.</b><br>';
			$corpo .= '<br><br>(Você está recebendo este e-mail por ser um dos contatos para '.$config['genero_tarefa'].' '.$config['tarefa'].')<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_tarefa($this->tarefa_id,'',true, '', true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Responsável pela exclusão:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$this->tarefa_id.'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
			$validos=0;
			foreach ($usuarios as $linha) {
				$corpo_externo=$corpo;
				if ($linha['usuario_id']) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
				if ($email->EmailValido($linha['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$email = new Mail;

						$email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }

						if ($email->EmailValido($linha['contato_email'])) {
							if ($linha['usuario_id']){
								$endereco=link_email_externo($linha['usuario_id'], 'm=tarefas&a=ver&tarefa_id='.$this->tarefa_id);
								$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
								}
							$email->Assunto($titulo, $localidade_tipo_caract);
							$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
							$email->Para($linha['contato_email'], true);
							$email->Enviar();
							}
						}
					else {
						$validos++;
						$email->Para($linha['contato_email'], true);
						}
					}
				}
			if ($validos) $email->Enviar();
			}
		}


	function notificar($comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;

		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('tarefa_designados');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tarefa_designados.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
		$usuarios = $sql->Lista();
		$sql->limpar();
		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['tarefa']).' Excluid'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['tarefa']).' Atualizad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			else $titulo=ucfirst($config['tarefa']).' Criad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi atualizad'.$config['genero_tarefa'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi criad'.$config['genero_tarefa'].'.</b><br>';
			$corpo .= '<br><br>(Você está recebendo este e-mail por ser um dos designados para '.$config['genero_tarefa'].' '.$config['tarefa'].')<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_tarefa($this->tarefa_id,'',true, '', true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Responsável pela exclusão:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$this->tarefa_id.'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
			$validos=0;
			$email->Corpo($corpo_email, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
			foreach ($usuarios as $linha) {
				$corpo_externo=$corpo;
				if ($linha['usuario_id']) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
				if ($email->EmailValido($linha['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$email = new Mail;
                        $email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }

						if ($email->EmailValido($linha['contato_email'])) {
							$endereco=link_email_externo($linha['usuario_id'], 'm=tarefas&a=ver&tarefa_id='.$this->tarefa_id);
							$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
							$email->Assunto($titulo, $localidade_tipo_caract);
							$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
							$email->Para($linha['contato_email'], true);
							$email->Enviar();
							}
						}
					else {
						$validos++;
						$email->Para($linha['contato_email'], true);
						}
					}
				}
			if ($validos) $email->Enviar();
			}
		}



	function notificar_novos($comentario='', $nao_eh_novo=false, $lista_designados_antigo=array()){
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;

		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('tarefa_designados');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tarefa_designados.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		if (count($lista_designados_antigo)) $sql->adOnde('tarefa_designados.usuario_id NOT IN ('.implode(',', $lista_designados_antigo).')');
		$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
		$usuarios = $sql->Lista();
		$sql->limpar();
		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['tarefa']).' Excluid'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['tarefa']).' Atualizad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			else $titulo=ucfirst($config['tarefa']).' Criad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi atualizad'.$config['genero_tarefa'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi criad'.$config['genero_tarefa'].'.</b><br>';
			$corpo .= '<br><br>(Você está recebendo este e-mail por ser um dos designados para '.$config['genero_tarefa'].' '.$config['tarefa'].')<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_tarefa($this->tarefa_id,'',true, '', true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Responsável pela exclusão:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$this->tarefa_id.'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
			$validos=0;

			foreach ($usuarios as $linha) {
				$corpo_externo=$corpo;
				if ($linha['usuario_id']) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
				if ($email->EmailValido($linha['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$email = new Mail;
                        $email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }

						if ($email->EmailValido($linha['contato_email'])) {
							$endereco=link_email_externo($linha['usuario_id'], 'm=tarefas&a=ver&tarefa_id='.$this->tarefa_id);
							$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
							$email->Assunto($titulo, $localidade_tipo_caract);
							$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
							$email->Para($linha['contato_email'], true);
							$email->Enviar();
							}
						}
					else {
						$validos++;
						$email->Para($linha['contato_email'], true);
						}
					}
				}
			if ($validos) $email->Enviar();
			}
		}


	function email_log(&$log, $designados, $tarefa_contatos, $projeto_contatos, $outros, $extras) {
		global $Aplic, $localidade_tipo_caract, $config;
	  $sem_email_interno=0;
		$email_recipientes = array();
		$sql = new BDConsulta;
		if (isset($designados) && $designados) {
			$sql->adTabela('tarefa_designados', 'ut');
			$sql->esqUnir('usuarios', 'ua', 'ua.usuario_id = ut.usuario_id');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = ua.usuario_contato');
			$sql->adCampo('c.contato_email, c.contato_posto, c.contato_nomeguerra');
			$sql->adOnde('ut.tarefa_id = '.(int)$this->tarefa_id);
			if (!$Aplic->getPref('emailtodos')) $sql->adOnde('ua.usuario_id !='.(int)$Aplic->usuario_id);
			$req = $sql->Lista();
			$sql->limpar();
			foreach($req as $linha)	if ($linha['contato_email'] && !isset($email_recipientes[$linha['contato_email']])) $email_recipientes[$linha['contato_email']] = ($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']);
			}
		if (isset($tarefa_contatos) && $tarefa_contatos) {
			$sql->adTabela('tarefa_contatos', 'tc');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = tc.contato_id');
			$sql->adCampo('c.contato_email, c.contato_posto, c.contato_nomeguerra');
			$sql->adOnde('tc.tarefa_id = '.(int)$this->tarefa_id);
			$req = $sql->Lista();
			$sql->limpar();
			foreach($req as $linha)	if ($linha['contato_email'] && !isset($email_recipientes[$linha['contato_email']])) $email_recipientes[$linha['contato_email']] = ($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']);
			}
		if (isset($projeto_contatos) && $projeto_contatos) {
			$sql->adTabela('projeto_contatos', 'pc');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = pc.contato_id');
			$sql->adCampo('c.contato_email, c.contato_posto, c.contato_nomeguerra');
			$sql->adOnde('pc.projeto_id = '.(int)$this->tarefa_projeto);
			$req = $sql->Lista();
			$sql->limpar();
			foreach($req as $linha)	if ($linha['contato_email'] && !isset($email_recipientes[$linha['contato_email']])) $email_recipientes[$linha['contato_email']] = ($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']);
			}
		if (isset($outros) && $outros) {
			$outros = trim($outros, " \r\n\t,");
			if (strlen($outros) > 0) {
				$sql->adTabela('contatos', 'c');
				$sql->adCampo('c.contato_email, c.contato_posto, c.contato_nomeguerra');
				$sql->adOnde('c.contato_id IN ('.$outros.')');
				$req = $sql->Lista();
				$sql->limpar();
				foreach($req as $linha)	if ($linha['contato_email'] && !isset($email_recipientes[$linha['contato_email']])) $email_recipientes[$linha['contato_email']] = ($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']);
				}
			}
		if (isset($extras) && $extras) {
			$extra_lista = preg_split('/[\s,;]+/', $extras);
			foreach ($extra_lista as $email_extra) {
				if ($email_extra && !isset($email_recipientes[$email_extra])) $email_recipientes[$email_extra] = $email_extra;
				}
			}
		if (count($email_recipientes) == 0) return false;
		$char_set = isset($localidade_tipo_caract) ? $localidade_tipo_caract : '';
		$email = new Mail;
        $email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$email->Assunto($log->tarefa_log_nome, $char_set);
		$titulo=$prefixo.' '.$log->tarefa_log_nome;
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_nome');
		$sql->adOnde('projeto_id='.(int)$this->tarefa_projeto);
		$nomeProjeto = htmlspecialchars_decode($sql->Resultado());
		$sql->limpar();
		$corpo = '<b>'.ucfirst($config['projeto']).':</b> '.$nomeProjeto.'<br>';
		if ($this->tarefa_superior != (int)$this->tarefa_id) {
			$sql->adTabela('tarefas');
			$sql->adCampo('tarefa_nome');
			$sql->adOnde('tarefa_id = '.(int)$this->tarefa_superior);
			$req = $sql->Resultado();
			$sql->limpar();
			if ($req) $corpo .= '<b>'.ucfirst($config['tarefa']).' Superior:</b> '.htmlspecialchars_decode($req).'<br>';

			}
		$corpo .= '<b>Tarefa:</b> '.$this->tarefa_nome.'<br>';
		$corpo .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$this->tarefa_id.'\');">Clique aqui para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</a><br><br>';
		$corpo .= '<b>Registro d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).'</b><br>';
		$corpo .= '<b>Sumário:</b> '.$log->tarefa_log_nome.'<br>';
		$corpo .= '<b>Descrição:</b> '.$log->tarefa_log_descricao;
		$sql->adTabela('usuarios');
		$sql->adCampo('usuario_rodape');
		$sql->adOnde('usuario_id = '.(int)$Aplic->usuario_id);
		$req = $sql->Resultado();
		$sql->limpar();
		if ($req)$corpo .= '<br><br>'.$req;
		$sql->limpar();
		$email->Corpo($corpo, $char_set);
		$lista_recipientes = '';
		foreach ($email_recipientes as $chave => $nome) {
			msg_email_interno($chave, $titulo, $corpo);
			if ($email->EmailValido($chave)) {
				$email->Para($chave);
				$lista_recipientes.= $chave.' ('.$nome.')<br>';
				}
			else $lista_recipientes .= 'e-mail \''.$chave.'\' para '.$nome.' inválido, não foi enviado.<br>';
			}
		if ($config['email_ativo'] && $config['email_externo_auto']) $email->Enviar();

		return false;
		}

	static function getTarefasParaPeriodo($data_inicio, $data_fim, $usuario_id=0, $cia_id = null, $dept_id=null,
		$tarefa_id=null,
		$projeto_id=null,
		$pg_perspectiva_id=null,
		$tema_id=null,
		$pg_objetivo_estrategico_id=null,
		$pg_fator_critico_id=null,
		$pg_estrategia_id=null,
		$pg_meta_id=null,
		$pratica_id=null,
		$pratica_indicador_id=null,
		$plano_acao_id=null,
		$canvas_id=null,
		$risco_id=null,
		$risco_resposta_id=null,
		$calendario_id=null,
		$monitoramento_id=null,
		$ata_id=null,
		$swot_id=null,
		$operativo_id=null,
		$instrumento_id=null,
		$recurso_id=null,
		$problema_id=null,
		$demanda_id=null,
		$programa_id=null,
		$licao_id=null,
		$link_id=null,
		$avaliacao_id=null,
		$tgn_id=null,
		$brainstorm_id=null,
		$gut_id=null,
		$causa_efeito_id=null,
		$arquivo_id=null,
		$forum_id=null,
		$checklist_id=null,
		$agenda_id=null,
		$agrupamento_id=null,
		$patrocinador_id=null,
		$template_id=null,
		$painel_id=null,
		$painel_odometro_id=null,
		$painel_composicao_id=null,
		$tr_id=null,
		$me_id=null) {
		global $Aplic;
		$sql = new BDConsulta;
		$db_inicio= $data_inicio->format(FMT_TIMESTAMP_MYSQL);
		$db_fim = $data_fim->format(FMT_TIMESTAMP_MYSQL);
		$tarefas_filtro = '';
		$sql->adTabela('tarefas', 't');
		if ($usuario_id) $sql->esqUnir('tarefa_designados', 'td', 't.tarefa_id=td.tarefa_id');
		$sql->esqUnir('projetos', 'projetos', 't.tarefa_projeto = projetos.projeto_id');
		if ($Aplic->profissional){
			if ($tarefa_id) $sql->adOnde('tarefas.tarefas_i='.(int)$tarefa_id);
			elseif ($projeto_id) $sql->adOnde('t.tarefa_projeto='.(int)$projeto_id);
			$sql->esqUnir('projeto_gestao','projeto_gestao','projeto_gestao_projeto = projetos.projeto_id');
			if ($pg_perspectiva_id) $sql->adOnde('projeto_gestao_perspectiva='.(int)$pg_perspectiva_id);
			elseif ($tema_id) $sql->adOnde('projeto_gestao_tema='.(int)$tema_id);
			elseif ($pg_objetivo_estrategico_id) $sql->adOnde('projeto_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
			elseif ($pg_fator_critico_id) $sql->adOnde('projeto_gestao_fator='.(int)$pg_fator_critico_id);
			elseif ($pg_estrategia_id) $sql->adOnde('projeto_gestao_estrategia='.(int)$pg_estrategia_id);
			elseif ($pg_meta_id) $sql->adOnde('projeto_gestao_meta='.(int)$pg_meta_id);
			elseif ($pratica_id) $sql->adOnde('projeto_gestao_pratica='.(int)$pratica_id);
			elseif ($pratica_indicador_id) $sql->adOnde('projeto_gestao_indicador='.(int)$pratica_indicador_id);
			elseif ($plano_acao_id) $sql->adOnde('projeto_gestao_acao='.(int)$plano_acao_id);
			elseif ($canvas_id) $sql->adOnde('projeto_gestao_canvas='.(int)$canvas_id);
			elseif ($risco_id) $sql->adOnde('projeto_gestao_risco='.(int)$risco_id);
			elseif ($risco_resposta_id) $sql->adOnde('projeto_gestao_risco_resposta='.(int)$risco_resposta_id);
			elseif ($calendario_id) $sql->adOnde('projeto_gestao_calendario='.(int)$calendario_id);
			elseif ($monitoramento_id) $sql->adOnde('projeto_gestao_monitoramento='.(int)$monitoramento_id);
			elseif ($ata_id) $sql->adOnde('projeto_gestao_ata='.(int)$ata_id);
			elseif ($swot_id) $sql->adOnde('projeto_gestao_swot='.(int)$swot_id);
			elseif ($operativo_id) $sql->adOnde('projeto_gestao_operativo='.(int)$operativo_id);
			elseif ($instrumento_id) $sql->adOnde('projeto_gestao_instrumento='.(int)$instrumento_id);
			elseif ($recurso_id) $sql->adOnde('projeto_gestao_recurso='.(int)$recurso_id);
			elseif ($problema_id) $sql->adOnde('projeto_gestao_problema='.(int)$problema_id);
			elseif ($demanda_id) $sql->adOnde('projeto_gestao_demanda='.(int)$demanda_id);
			elseif ($programa_id) $sql->adOnde('projeto_gestao_programa='.(int)$programa_id);
			elseif ($licao_id) $sql->adOnde('projeto_gestao_licao='.(int)$licao_id);
			elseif ($link_id) $sql->adOnde('projeto_gestao_link='.(int)$link_id);
			elseif ($avaliacao_id) $sql->adOnde('projeto_gestao_avaliacao='.(int)$avaliacao_id);
			elseif ($tgn_id) $sql->adOnde('projeto_gestao_tgn='.(int)$tgn_id);
			elseif ($brainstorm_id) $sql->adOnde('projeto_gestao_brainstorm='.(int)$brainstorm_id);
			elseif ($gut_id) $sql->adOnde('projeto_gestao_gut='.(int)$gut_id);
			elseif ($causa_efeito_id) $sql->adOnde('projeto_gestao_causa_efeito='.(int)$causa_efeito_id);
			elseif ($arquivo_id) $sql->adOnde('projeto_gestao_arquivo='.(int)$arquivo_id);
			elseif ($forum_id) $sql->adOnde('projeto_gestao_forum='.(int)$forum_id);
			elseif ($checklist_id) $sql->adOnde('projeto_gestao_checklist='.(int)$checklist_id);
			elseif ($agenda_id) $sql->adOnde('projeto_gestao_agenda='.(int)$agenda_id);
			elseif ($agrupamento_id) $sql->adOnde('projeto_gestao_agrupamento='.(int)$agrupamento_id);
			elseif ($patrocinador_id) $sql->adOnde('projeto_gestao_patrocinador='.(int)$patrocinador_id);
			elseif ($template_id) $sql->adOnde('projeto_gestao_template='.(int)$template_id);
			elseif ($painel_id) $sql->adOnde('projeto_gestao_painel='.(int)$painel_id);
			elseif ($painel_odometro_id) $sql->adOnde('projeto_gestao_painel_odometro='.(int)$painel_odometro_id);
			elseif ($painel_composicao_id) $sql->adOnde('projeto_gestao_painel_composicao='.(int)$painel_composicao_id);
			elseif ($tr_id) $sql->adOnde('projeto_gestao_tr='.(int)$tr_id);
			elseif ($me_id) $sql->adOnde('projeto_gestao_me='.(int)$me_id);
			}

		if ($Aplic->profissional) $sql->esqUnir('projeto_cia', 'projeto_cia', 'projeto_cia_projeto = projetos.projeto_id');
		$sql->esqUnir('tarefa_depts', '', 't.tarefa_id = tarefa_depts.tarefa_id');
		$sql->esqUnir('depts', '', 'depts.dept_id = tarefa_depts.departamento_id');
		$sql->adCampo('DISTINCT t.tarefa_id, t.tarefa_nome, t.tarefa_acesso, t.tarefa_inicio, t.tarefa_fim, t.tarefa_duracao'.', t.tarefa_duracao_tipo, projetos.projeto_id, projetos.projeto_cor AS cor, projetos.projeto_nome, t.tarefa_marco, tarefa_acao');
		$sql->adOnde('tarefa_status > -1 AND (tarefa_inicio <= \''.$db_fim.'\' AND (tarefa_fim >= \''.$db_inicio. '\' OR tarefa_fim = NULL))');
		if ($usuario_id) $sql->adOnde('(td.usuario_id='.(int)$usuario_id.' OR tarefa_dono='.(int)$usuario_id.')');
		if ($cia_id) $sql->adOnde('projetos.projeto_cia IN ('.$cia_id.')'.($Aplic->profissional ? ' OR projeto_cia_cia  IN ('.$cia_id.')' : ''));
		if ($projeto_id) $sql->adOnde('projetos.projeto_id = '.(int)$projeto_id);
		if ($dept_id) $sql->adOnde('tarefa_depts.departamento_id IN ('.$dept_id.')');
		$sql->adOrdem('t.tarefa_inicio');
		$resultado = $sql->Lista();
		$sql->limpar();
		return $resultado;
		}

	function podeAcessar() {
		$valor=permiteAcessar($this->tarefa_acesso, $this->tarefa_projeto, (int)$this->tarefa_id);
		return $valor;
		}

	function podeEditar() {
		//Se projeto tiver nível de acesso mais restrito, deverá ser utilizado para a tarefa
		$sql = new BDConsulta;
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_acesso');
		$sql->adOnde('projeto_id = '.(int)$this->tarefa_projeto);
		$acesso = $sql->resultado();
		$sql->limpar();
		if ($acesso > $this->tarefa_acesso) $this->tarefa_acesso=$acesso;
		$valor=permiteEditar($this->tarefa_acesso, (int)$this->tarefa_projeto, (int)$this->tarefa_id);
		return $valor;
		}


	function cia_nome() {
		global $Aplic;
		$sql = new BDConsulta;
		$sql->adTabela('tarefas', 't');
		$sql->esqUnir('projetos', 'projetos', 't.tarefa_projeto = projetos.projeto_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = projetos.projeto_cia');
		$sql->adCampo('cia_nome');
		$resultado=$sql->Resultado();
		$sql->limpar();
		return $resultado;
		}




	function getTarefaDuracaoPorDia($usar_percentagem_designado = false) {
		$duracao = $this->tarefa_duracao * ($this->tarefa_duracao_tipo == 24 ? config('horas_trab_diario') : $this->tarefa_duracao_tipo);
		$tarefa_inicio = new CData($this->tarefa_inicio);
		$tarefa_data_termino = new CData($this->tarefa_fim);
		$usuarios_designados = $this->getUsuariosDesignados_Linha();
		if ($usar_percentagem_designado) {
			$numero_usuarios_designados = 0;
			foreach ($usuarios_designados as $u) $numero_usuarios_designados += ($u['perc_designado'] / 100);
			}
		else $numero_usuarios_designados = count($usuarios_designados);
		$dia_diferenca = $tarefa_data_termino->dataDiferenca($tarefa_inicio);
		$numero_dias_trabalhados = 0;
		$data_atual = $tarefa_inicio;
		for ($i = 0; $i <= $dia_diferenca; $i++) {
			if ($data_atual->serDiaUtil())	$numero_dias_trabalhados++;
			$data_atual->adDias(1);
			}
		if ($numero_dias_trabalhados == 0) $numero_dias_trabalhados = 1;
		if ($numero_usuarios_designados == 0) $numero_usuarios_designados = 1;
		return ($duracao / $numero_usuarios_designados) / $numero_dias_trabalhados;
		}

	function getTarefaDuracaoPorSemana($usar_percentagem_designado = false) {
		$duracao = $this->tarefa_duracao * ($this->tarefa_duracao_tipo == 24 ? config('horas_trab_diario') : $this->tarefa_duracao_tipo);
		$tarefa_inicio = new CData($this->tarefa_inicio);
		$tarefa_data_termino = new CData($this->tarefa_fim);
		$usuarios_designados = $this->getUsuariosDesignados_Linha();
		if ($usar_percentagem_designado) {
			$numero_usuarios_designados = 0;
			foreach ($usuarios_designados as $u) $numero_usuarios_designados += ($u['perc_designado'] / 100);
			}
		else $numero_usuarios_designados = count($usuarios_designados);
		$numero_semanas_trabalhadas = $tarefa_data_termino->nrDiasUteisNoEspaco($tarefa_inicio) / count(explode(',', config('cal_dias_uteis')));
		$numero_semanas_trabalhadas = (($numero_semanas_trabalhadas < 1) ? ceil($numero_semanas_trabalhadas) : $numero_semanas_trabalhadas);
		if ($numero_semanas_trabalhadas == 0) $numero_semanas_trabalhadas = 1;
		if ($numero_usuarios_designados == 0) $numero_usuarios_designados = 1;
		return ($duracao / $numero_usuarios_designados) / $numero_semanas_trabalhadas;
		}

	function removerDesignado($usuario_id) {
		$sql = new BDConsulta;
		$sql->setExcluir('tarefa_designados');
		$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id.' AND usuario_id = '.(int)$usuario_id);
		$sql->exec();
		$sql->limpar();
		}

	function atualizarDesignados($cslista, $perc_designado, $del = true, $rmUsuarios = false) {
		$sql = new BDConsulta;
		$tarr = explode(',', $cslista);
		if ($del == true && $rmUsuarios == true) {
			foreach ($tarr as $usuario_id) {
				$usuario_id = (int)$usuario_id;
				if (!empty($usuario_id))	$this->removerDesignado($usuario_id);
				}
			return false;
			}
		elseif ($del == true) {
			$sql->setExcluir('tarefa_designados');
			$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
			$sql->exec();
			$sql->limpar();
			}
		$alocado = $this->getDesignacao('usuario_id');
		$sobrecarregado = false;
		foreach ($tarr as $usuario_id) {
			if (intval($usuario_id) > 0) {
				$perc = $perc_designado[$usuario_id];
				$sql->adTabela('tarefa_designados');
				$sql->adSubstituir('usuario_id', $usuario_id);
				$sql->adSubstituir('tarefa_id', (int)$this->tarefa_id);
				$sql->adSubstituir('perc_designado', $perc);
				$sql->exec();
				$sql->limpar();
				}
			}
		return $sobrecarregado;
		}

	function getUsuariosDesignados_Linha() {
		$sql = new BDConsulta;
		$sql->adTabela('usuarios', 'u');
		$sql->esqUnir('tarefa_designados', 'ut', 'ut.usuario_id = u.usuario_id');
		$sql->esqUnir('contatos', 'co', ' co.contato_id = u.usuario_contato');
		$sql->adCampo('u.*, ut.perc_designado, ut.usuario_tarefa_prioridade, co.contato_nomeguerra, co.contato_posto');
		$sql->adOnde('ut.tarefa_id = '.(int)$this->tarefa_id);
		$resultado = $sql->ListaChave('usuario_id');
		$sql->limpar();
		return $resultado;
		}


	function getResponsavel() {
		$sql = new BDConsulta;
		$sql->adTabela('usuarios', 'u');
		$sql->esqUnir('tarefas', 't', 't.tarefa_dono = u.usuario_id');
		$sql->esqUnir('contatos', 'co', ' co.contato_id = u.usuario_contato');
		$sql->adCampo('u.*, 100 AS perc_designado, t.tarefa_prioridade AS usuario_tarefa_prioridade, co.contato_nomeguerra, co.contato_posto');
		$sql->adOnde('t.tarefa_id = '.(int)$this->tarefa_id);
		$resultado = $sql->ListaChave('usuario_id');
		$sql->limpar();
		return $resultado;
		}

	function getDesignacao($hash = null, $usuarios = null, $get_lista_usuario = false, $cia_id=0) {
		global $Aplic;
		if ($get_lista_usuario) {
			$usuarios_lista = getListaUsuariosaLinha(null,null,'contato_posto_valor, contato_nomeguerra', $cia_id);
			foreach ($usuarios_lista as $chave => $usuario) $usuarios_lista[$chave]['usuarioFC'] = $usuario['contato_nome'];
			$hash = $usuarios_lista;
			}
		else $hash = array();
		return $hash;
		}

	function getPrioridadeTarefaUsuarioEspecifico($usuario_id = 0, $tarefa_id = null) {
		$sql = new BDConsulta;
		$tarefa_id = empty($tarefa_id) ? (int)$this->tarefa_id : $tarefa_id;
		$sql->adTabela('tarefa_designados');
		$sql->adCampo('usuario_tarefa_prioridade');
		$sql->adOnde('usuario_id = '.(int)$usuario_id.' AND tarefa_id = '.(int)$tarefa_id);
		$prioridade = $sql->Linha();
		$sql->limpar();
		return ($prioridade['usuario_tarefa_prioridade'] ? $prioridade['usuario_tarefa_prioridade'] : null);
		}

	function atualizarUsuarioPrioridadeTarefa($usuario_tarefa_prioridade = 0, $usuario_id = 0, $tarefa_id = null) {
		$sql = new BDConsulta;
		$tarefa_id = empty($tarefa_id) ? (int)$this->tarefa_id : $tarefa_id;
		$sql->adTabela('tarefa_designados');
		$sql->adSubstituir('usuario_id', $usuario_id);
		$sql->adSubstituir('tarefa_id', $tarefa_id);
		$sql->adSubstituir('usuario_tarefa_prioridade', $usuario_tarefa_prioridade);
		$sql->exec();
		$sql->limpar();
		}

	function getProjeto() {
		$sql = new BDConsulta;
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_nome, projeto_nome_curto, projeto_cor, projeto_descricao, projeto_id, projeto_cia');
		$sql->adOnde('projeto_id = '.(int)$this->tarefa_projeto);
		$projeto = $sql->Linha();
		$sql->limpar();
		return $projeto;
		}

	function getSubordinada() {
		$sql = new BDConsulta;
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_id != '.(int)$this->tarefa_id.' AND tarefa_superior = '.(int)$this->tarefa_id);
		$resultado = $sql->carregarColuna();
		$sql->limpar();
		return $resultado;
		}

	function getSubordinadaProfunda() {
		$subordinada = $this->getSubordinada();
		if ($subordinada) {
			$subordinada_profunda = array();
			$tempTarefa = new CTarefa();
			foreach ($subordinada as $sub) {
				$tempTarefa->olhar($sub);
				$subordinada_profunda = array_merge($subordinada_profunda, $tempTarefa->getSubordinadaProfunda());
				}
			return array_merge($subordinada, $subordinada_profunda);
			}
		return array();
		}

	function atualizarStatusSubTarefas($novo_status, $tarefa_id = null) {
		$sql = new BDConsulta;
		if (is_null($tarefa_id)) $tarefa_id = (int)$this->tarefa_id;
		$sql->adTabela('tarefas');

		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
		$tarefas_id = $sql->carregarColuna();
		$sql->limpar();
		if (count($tarefas_id) == 0) 	return true;
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_status', $novo_status);
		$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
		$sql->exec();
		$sql->limpar();
		foreach ($tarefas_id as $id) {
			if ($id != $tarefa_id) $this->atualizarStatusSubTarefas($novo_status, $id);
			}
		}

	function atualizarSubTarefasProjeto($novo_projeto, $tarefa_id = null) {
		$sql = new BDConsulta;
		if (is_null($tarefa_id)) $tarefa_id = (int)$this->tarefa_id;
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
		$tarefas_id = $sql->carregarColuna();
		$sql->limpar();
		if (count($tarefas_id) == 0) return true;
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_projeto', $novo_projeto);
		$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
		$sql->exec();
		$sql->limpar();
		foreach ($tarefas_id as $id) {
			if ($id != $tarefa_id) 	$this->atualizarSubTarefasProjeto($novo_projeto, $id);
			}
		}

	function adLembrete() {
		global $Aplic;

		$dia = 86400;
		if ($Apli->profissional || config('tarefa_controle_aviso')) return;
		if (!$this->tarefa_fim) {
			return $this->limparLembrete(true);
			}
		if ($this->tarefa_percentagem >= 100) return $this->limparLembrete(true);

		$eq = new EventoFila;
		$dias_antes = config('tarefa_aviso_dias_antes', 1);
		$repetir = config('tarefa_aviso_repetir', 0);
		$args = null;
		$lembretes_antigos = $eq->procurar('tarefas', 'lembrar', (int)$this->tarefa_id);
		if (count($lembretes_antigos)) {
			foreach ($lembretes_antigos as $antigo_id => $data_antiga) $eq->remover($antigo_id);
			}
		$data = new CData($this->tarefa_fim);
		$hoje = new CData(date('Y-m-d'));
		if ($data->compare($data, $hoje) < 0) $inicio_dia = time();
		else {
			$inicio_dia = $data->getData(DATE_FORMAT_UNIXTIME);
			$inicio_dia -= ($dia * $dias_antes);
			}
		$eq->adicionar(array($this, 'lembrar'), $args, 'tarefas', false, (int)$this->tarefa_id, 'lembrar', $inicio_dia, $dia, $repetir);
		}

	function lembrar($modulo, $tipo, $id, $responsavel, $args) {
		global $Aplic, $localidade_tipo_caract, $config;
		$sql = new BDConsulta;
	  $sem_email_interno=0;
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		if (!$this->load($id)) return - 1;
		$this->htmlDecodificar();
		$hoje = new CData();
		if (!$hoje->serDiaUtil()) return true;
		if ($this->tarefa_percentagem == 100) return - 1;

		$sql->adTabela('tarefa_designados', 'ut');
		$sql->esqUnir('usuarios', 'u', 'u.usuario_id = ut.usuario_id');
		$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->adCampo('c.contato_id, contato_posto, contato_nomeguerra, contato_email, u.usuario_id, cia_nome');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$sql->adOnde('ut.tarefa_id = '.(int)$id);
		$contatos = $sql->ListaChaveSimples('contato_id');
		$sql->limpar();

		$sql->adTabela('usuarios', 'u');
		$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->adCampo('c.contato_id, contato_posto, contato_nomeguerra, contato_email, usuario_id, cia_nome');
		$sql->adOnde('u.usuario_id = '.(int)$this->tarefa_dono);
		$responsavel=$sql->linha();
		$sql->limpar();
		if (!isset($contatos[$responsavel['contato_id']])) {
			$contatos[$responsavel['contato_id']]=$responsavel;
			}


		$inicia = new CData($this->tarefa_inicio);
		$expira = new CData($this->tarefa_fim);
		$agora = new CData();
		$dif = $expira->dataDiferenca($agora);
		$dif *= $agora->compare($expira, $agora);
		$prefixo = ucfirst($config['tarefa']).' para';

		if ($dif == 0) $msg = 'hoje';
		elseif ($dif == 1) $msg = 'amanhã';
		elseif ($dif < 0) {
			$msg = 'atrasadas '.abs($dif).' dias';
			$prefixo = ucfirst($config['tarefa']);
			}
		else $msg = $dif.' dias';

		$projeto_nome = htmlspecialchars_decode(nome_projeto($this->tarefa_projeto));

		$assunto = ($prefixo ? $prefixo.' ' : '').$msg.' '.$this->tarefa_nome.' - '.$projeto_nome;
		$corpo='<b>Tarefas para:</b> '.$msg.'<br><b>'.ucfirst($config['projeto']).':</b> '.$projeto_nome.'<br><b>'.ucfirst($config['tarefa']).':</b> '.$this->tarefa_nome.'<br>';
		if ($this->tarefa_inicio) $corpo.='<b>Data de Início:</b> '.retorna_data($this->tarefa_inicio, true).'<br>';
		if ($this->tarefa_fim) $corpo.='<b>Data de Término:</b> '.retorna_data($this->tarefa_fim, true).'<br>';
		$corpo.='<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$this->tarefa_id.'&lembrar=1\');">Clique aqui para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</a><br><br>';
		$designados='';
		foreach ($contatos as $contato) $designados.= $contato['contato_posto'].' '.$contato['contato_nomeguerra'].($contato['cia_nome'] ? ' - '.$contato['cia_nome'] : '').($contato['contato_email'] ? ' <'.$contato['contato_email'].'>' : '').'<br>';
		if ($designados) $corpo.='<b>Designados:</b><br>'.$designados;
		if ($this->tarefa_descricao) $corpo .= '<br><b>Descrição:</b><br>'.$this->tarefa_descricao.'<br>';
		$email = new Mail;
        $email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$interno_enviado=0;
		foreach ($contatos as $contato) {
			$retorno_interno=msg_email_interno($contato['contato_email'], $assunto, $corpo, '', $contato['usuario_id']);
			if (!$retorno_interno) $interno_enviado++;
			if ($email->EmailValido($contato['contato_email'])) {
				$email->Para($contato['contato_email']);
				}
			}
		$email->Assunto($assunto, $localidade_tipo_caract);
		$email->Corpo($corpo, $localidade_tipo_caract);
		if ($config['email_ativo'] && $config['email_externo_auto']) $retorno_externo=$email->Enviar();
		if ($interno_enviado || $retorno_externo) return true;
		}

	function limparLembrete($nao_checar = false) {
		$ev = new EventoFila;
		$evento_lista = $ev->procurar('tarefas', 'lembrar', (int)$this->tarefa_id);
		if (count($evento_lista)) {
			foreach ($evento_lista as $id => $data) {
				if ($nao_checar || $this->tarefa_percentagem >= 100) $ev->remover($id);
				}
			}
		}

	function &getDesignado() {
		$sql = new BDConsulta;
		$sql->adTabela('usuarios', 'u');
		$sql->adTabela('tarefa_designados', 'ut');
		$sql->adTabela('contatos', 'con');
		$sql->adCampo('u.usuario_id, concatenar_quatro(contato_posto, \' \', contato_nomeguerra, concatenar_dois(perc_designado, \'%\'))');
		$sql->adOnde('ut.tarefa_id = '.(int)$this->tarefa_id);
		$sql->adOnde('usuario_contato = contato_id');
		$sql->adOnde('ut.usuario_id = u.usuario_id');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$designado = $sql->ListaChave();
		return $designado;
		}
	}

/********************************************************************************************

Classe CTarefaLog para manipulação dos registros das tarefa

gpweb\modulos\tarefas\CTarefa.class.php

********************************************************************************************/
class CTarefaLog extends CAplicObjeto {
	var $tarefa_log_id = null;
  var $tarefa_log_tarefa = null;
  var $tarefa_log_correcao = null;
  var $tarefa_log_nome = null;
  var $tarefa_log_descricao = null;
  var $tarefa_log_criador = null;
  var $tarefa_log_horas = null;
  var $tarefa_log_data = null;
  var $tarefa_log_custo = null;
  var $tarefa_log_nd = null;
  var $tarefa_log_categoria_economica = null;
  var $tarefa_log_grupo_despesa = null;
  var $tarefa_log_modalidade_aplicacao = null;
  var $tarefa_log_metodo = null;
  var $tarefa_log_exercicio = null;
  var $tarefa_log_problema = null;
  var $tarefa_log_tipo_problema = null;
  var $tarefa_log_referencia = null;
  var $tarefa_log_url_relacionada = null;
  var $tarefa_log_cia = null;
  var $tarefa_log_reg_mudanca = null;
  var $tarefa_log_reg_mudanca_servidores = null;
  var $tarefa_log_reg_mudanca_paraquem = null;
  var $tarefa_log_reg_mudanca_data = null;
  var $tarefa_log_reg_mudanca_inicio = null;
  var $tarefa_log_reg_mudanca_fim = null;
  var $tarefa_log_reg_mudanca_duracao = null;
  var $tarefa_log_reg_mudanca_expectativa = null;
  var $tarefa_log_reg_mudanca_descricao = null;
  var $tarefa_log_reg_mudanca_plano = null;
  var $tarefa_log_reg_mudanca_percentagem = null;
  var $tarefa_log_reg_mudanca_realizado = null;
	var $tarefa_log_reg_mudanca_status = null;
	var $tarefa_log_acesso = null;
	var $tarefa_log_aprovou = null;
	var $tarefa_log_aprovado = null;
	var $tarefa_log_data_aprovado = null;

	function __construct(){
		parent::__construct('tarefa_log', 'tarefa_log_id');
		$this->tarefa_log_problema = intval($this->tarefa_log_problema);
		}

	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->tarefa_log_id) {
			$ret = $sql->atualizarObjeto('tarefa_log', $this, 'tarefa_log_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('tarefa_log', $this, 'tarefa_log_id');
			$sql->limpar();
			}


		if ($Aplic->profissional && isset($_REQUEST['uuid']) && $_REQUEST['uuid']){
			$sql->adTabela('custo');
			$sql->adAtualizar('custo_tarefa_log', (int)$this->tarefa_log_id);
			$sql->adAtualizar('custo_uuid', null);
			$sql->adOnde('custo_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			}


		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	function getProjeto(){
		$sql = new BDConsulta;
		$sql->adTabela('tarefas', 't');
		$sql->adCampo('t.tarefa_projeto');
		$sql->adOnde('t.tarefa_id = '.$this->tarefa_log_tarefa);
		$resultado = $sql->Resultado();
		return $resultado;
		}

	function arrumarTodos(){
		$descricaoComEspacos = $this->tarefa_log_descricao;
		parent::arrumarTodos();
		$this->tarefa_log_descricao = $descricaoComEspacos;
		}

	function check(){
		$this->tarefa_log_horas = (float)$this->tarefa_log_horas;
		return null;
		}


	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		$sql = new BDConsulta;
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_nome');
		$sql->adOnde('tarefa_id ='.$post['tarefa_log_tarefa']);
		$tarefa_nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();
		$usuarios5=array();
		$usuarios6=array();

		if (isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('tarefa_designados');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tarefa_designados.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tarefa_id='.$post['tarefa_log_tarefa']);
			$usuarios1 = $sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_tarefa_contatos']) && $post['email_tarefa_contatos']){
			$sql->adTabela('tarefa_contatos');
			$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = tarefa_contatos.contato_id');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = contatos.contato_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tarefa_id='.$post['tarefa_log_tarefa']);
			$usuarios2 = $sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_outro']) && $post['email_outro']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_outro'].')');
			$usuarios3=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['tarefa_log_notificar_responsavel']) && $post['tarefa_log_notificar_responsavel']){
			$sql->adTabela('tarefas');
			$sql->esqUnir('usuarios', 'usuarios', 'tarefas.tarefa_dono = usuarios.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tarefa_id='.$post['tarefa_log_tarefa']);
			$usuarios4=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_projeto_responsavel']) && $post['email_projeto_responsavel']){
			$sql->adTabela('projetos');
			$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_projeto = projetos.projeto_id');
			$sql->esqUnir('usuarios', 'usuarios', 'projetos.projeto_responsavel = usuarios.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tarefa_id='.$post['tarefa_log_tarefa']);
			$usuarios5=$sql->Lista();
			$sql->limpar();
			}


		if (isset($post['email_extras']) && $post['email_extras']){
			$extras=explode(',',$post['email_extras']);
			foreach($extras as $chave => $valor) $usuarios6[]=array('usuario_id' => 0, 'nome_usuario' =>'', 'contato_email'=> $valor);
			}

		$usuarios = array_merge((array)$usuarios1, (array)$usuarios2);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios3);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios4);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios5);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios6);

		$usado_usuario=array();
		$usado_email=array();

		if (isset($post['del']) && $post['del'])$tipo='excluido';
		elseif (isset($post['tarefa_log_id']) && $post['tarefa_log_id']) $tipo='atualizado';
		else $tipo='incluido';

		foreach($usuarios as $usuario){
			if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])){

				$usado[$usuario['usuario_id']]=1;
				$usado[$usuario['contato_email']]=1;
				$email = new Mail;
                $email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }

				if ($tipo == 'excluido') {
					$email->Assunto('Excluído registro de ocorrência de '.$config['tarefa'], $localidade_tipo_caract);
					$titulo='Excluído registro de ocorrência de '.$config['tarefa'];
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado registro de ocorrência de '.$config['tarefa'], $localidade_tipo_caract);
					$titulo='Atualizado registro de ocorrência de '.$config['tarefa'];
					}
				else {
					$email->Assunto('Inserido registro de ocorrência de '.$config['tarefa'], $localidade_tipo_caract);
					$titulo='Inserido registro de ocorrência de '.$config['tarefa'];
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado registro de ocorrência d'.$config['genero_tarefa'].' '.$config['tarefa'].': '.$tarefa_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído registro de ocorrência d'.$config['genero_tarefa'].' '.$config['tarefa'].': '.$tarefa_nome.'<br>';
				else $corpo = 'Inserido registro de ocorrência d'.$config['genero_tarefa'].' '.$config['tarefa'].': '.$tarefa_nome.'<br>';


				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorrência:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				if ($Aplic->profissional){

						$corpo.='<br><br><b>Informações sobre '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'</b><br><br><table cellspacing=0 cellpadding=0>';

						//detalhes da tarefa
						$obj = new CTarefa();
						$obj->load($post['tarefa_log_tarefa']);

						$custo_estimado=$obj->custo_estimado();
						$gasto_efetuado=$obj->gasto_efetuado();
						$gasto_registro=$obj->gasto_registro();
						$mao_obra_gasto=$obj->mao_obra_gasto();
						$mao_obra_previsto=$obj->mao_obra_previsto(date('Y-m-d H:i:s'),'', true);
						$mao_obra_previsto_total=$obj->mao_obra_previsto('','', false);
						$recurso_previsto=$obj->recurso_previsto(date('Y-m-d H:i:s'),'', true);
						$recurso_previsto_total=$obj->recurso_previsto('','', false);
						$custo_previsto=$obj->custo_previsto(date('Y-m-d H:i:s'),'', true);
						if ($obj->tarefa_cia) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">'.ucfirst($config['organizacao']).':</td><td>'.nome_cia($obj->tarefa_cia).'</td></tr>';
						if ($obj->tarefa_dono) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Responsável:</td><td>'.nome_usuario($obj->tarefa_dono,'','','esquerda').'</td></tr>';
						$df = '%d/%m/%Y';
						$tf = $Aplic->getPref('formatohora');
						$data_inicio = intval($obj->tarefa_inicio) ? new CData($obj->tarefa_inicio) : null;
						$data_fim = intval($obj->tarefa_fim) ? new CData($obj->tarefa_fim) : null;
						if ($data_inicio) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Início:</td><td width="300">'.$data_inicio->format($df.' '.$tf).'</td></tr>';
						if ($data_fim) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Término:</td><td width="300">'.$data_fim->format($df.' '.$tf).'</td></tr>';
						if ($data_inicio && $data_fim && !$obj->tarefa_marco && $obj->tarefa_percentagem > 0 && $obj->tarefa_percentagem < 100){
							//Quantas horas desde  a data de início da tarefa
							$horas_faltando=((100-$obj->tarefa_percentagem)/100)*$obj->tarefa_duracao;
							$data=calculo_data_final_periodo(date('Y-m-d H:i:s'), $horas_faltando, $obj->tarefa_cia, null, $obj->tarefa_projeto, null, $post['tarefa_log_tarefa']);
							$corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Previsão:</td><td width="300">'.retorna_data($data).'</td></tr>';
							}
						if ($obj->tarefa_duracao) $corpo.='<tr><td align="right" nowrap="nowrap" valign="top" style="width:110px;">Duração:</td><td width="300">'.number_format($obj->tarefa_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8), 2, ',', '.').' dia'.($obj->tarefa_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8) >= 2 ? 's' : '').'</td></tr>';
						if ($obj->tarefa_tipo) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Tipo:</td><td width="300">'.getSisValorCampo('TipoTarefa',$obj->tarefa_tipo).'</td></tr>';
						if ($obj->tarefa_emprego_obra) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Empregos durante a execução:</td><td width="300">'.$obj->tarefa_emprego_obra.'</td></tr>';
						if ($obj->tarefa_emprego_direto) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Empregos diretos após conclusão:</td><td width="300">'.$obj->tarefa_emprego_direto.'</td></tr>';
						if ($obj->tarefa_emprego_indireto) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Empregos indiretos após conclusão:</td><td width="300">'.$obj->tarefa_emprego_indireto.'</td></tr>';
						if ($obj->tarefa_forma_implantacao) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Forma de implantação:</td><td width="300">'.$obj->tarefa_forma_implantacao.'</td></tr>';
						if ($obj->tarefa_populacao_atendida) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">População atendida:</td><td width="300">'.$obj->tarefa_populacao_atendida.'</td></tr>';
						$unidade= getSisValor('TipoUnidade');
						if ($obj->tarefa_adquirido!=0) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Quantidade adquirida:</td><td width="300">'.number_format($obj->tarefa_adquirido, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
						if ($obj->tarefa_previsto!=0) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Quantidade prevista:</td><td width="300">'.number_format($obj->tarefa_previsto, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
						if ($obj->tarefa_realizado!=0) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Quantidade realizada:</td><td width="300">'.number_format($obj->tarefa_realizado, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
						if ($obj->tarefa_url_relacionada) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Endereço URL:</td><td width="300"><a href="'.$obj->tarefa_url_relacionada.'" target="tarefa'.$post['tarefa_log_tarefa'].'">'.$obj->tarefa_url_relacionada.'</a></td></tr>';
						if ($custo_estimado!=0) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Custo estimado:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($custo_estimado, 2, ',', '.').'</td></tr>';
						if ($gasto_efetuado!=0) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Gasto:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($gasto_efetuado, 2, ',', '.').'</td></tr>';
						if ($mao_obra_previsto!=0) $corpo.='<tr><td align="right" nowrap="nowrap">M.O. estimada:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_previsto, 2, ',', '.').'</td></tr>';
						if ($mao_obra_previsto_total!=0) $corpo.='<tr><td align="right" nowrap="nowrap">Total M.O. estimada:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_previsto_total, 2, ',', '.').'</td></tr>';
						if ($mao_obra_gasto!=0) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">M.O. gasta:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_gasto, 2, ',', '.').'</td></tr>';
						if ($recurso_previsto!=0) $corpo.='<tr><td align="right" nowrap="nowrap">Recursos estimados:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($recurso_previsto, 2, ',', '.').'</td></tr>';
						if ($recurso_previsto_total!=0) $corpo.='<tr><td align="right" nowrap="nowrap">Recursos total estimado:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($recurso_previsto_total, 2, ',', '.').'</td></tr>';
						if ($gasto_registro!=0) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Gastos extras:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($gasto_registro, 2, ',', '.').'</td></tr>';
						if ($custo_previsto != 0) $corpo.='<tr><td align="right" nowrap="nowrap">Custo Previsto:</td><td width="100%">'.$config['simbolo_moeda'].' '.number_format($custo_previsto, 2, ',', '.').'</td></tr>';
						$financeiro_velocidade=$obj->financeiro_velocidade(date('Y-m-d H:i:s'),'', true);
						if ($financeiro_velocidade != 0) $corpo.='<tr><td align="right" nowrap="nowrap">Vel. do financeiro:</td><td width="100%">'.number_format($financeiro_velocidade, 2, ',', '.').'</td></tr>';
						$total_estimado=$custo_estimado;
						$total_gasto=($gasto_efetuado+$gasto_registro+$mao_obra_gasto);
						if ($total_estimado!=0) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Total estimado:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($total_estimado, 2, ',', '.').'</td></tr>';
						if ($total_gasto!=0) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Total efetivo:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($total_gasto, 2, ',', '.').'</td></tr>';
						$provavel=($obj->tarefa_percentagem > 0 ? ($gasto_efetuado+$gasto_registro)/($obj->tarefa_percentagem/100) : ($gasto_efetuado+$gasto_registro)/0.01);
						if ($obj->tarefa_percentagem!=100 && $provavel!=0) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Custo provável:</td><td width="300" '.($provavel > $custo_estimado ? 'style="color:#FF0000"' : '').'>'.$config['simbolo_moeda'].' '.number_format($provavel, 2, ',', '.').'</td></tr>';
						if (!$obj->tarefa_marco) $corpo.='<tr><td align="right" nowrap="nowrap" style="width:110px;">Progresso:</td><td width="300">'.number_format($obj->tarefa_percentagem, 2, ',', '.').'%</td></tr>';
						if (!$obj->tarefa_marco){
							$corpo.='<tr><td align="right" nowrap="nowrap">Físico Previsto:</td><td width="100%">'.number_format($obj->fisico_previsto(date('Y-m-d H:i:s')), 2, ',', '.').'%</td></tr>';
							$corpo.='<tr><td align="right" nowrap="nowrap">Velocidade do físico:</td><td width="100%">'.number_format($obj->fisico_velocidade(date('Y-m-d H:i:s')), 2, ',', '.').'</td></tr>';
							}
						if ($obj->tarefa_descricao)	$corpo.='<tr><td align="right" nowrap="nowrap" align="left" width="80">O Que:</td><td>'.$obj->tarefa_descricao.'</td></tr>';
						if ($obj->tarefa_porque)	$corpo.='<tr><td align="right" nowrap="nowrap">Por Que:</td><td>'.$obj->tarefa_porque.'</td></tr>';
						if ($obj->tarefa_como)	$corpo.='<tr><td align="right" nowrap="nowrap">Como:</td><td>'.$obj->tarefa_como.'</td></tr>';
						if ($obj->tarefa_onde)	$corpo.='<tr><td align="right" nowrap="nowrap">Onde:</td><td>'.$obj->tarefa_onde.'</td></tr>';
						$corpo.='</table>';
						}

				$link_interno=($tipo!='excluido' ? '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tab=0&tarefa_id='.$post['tarefa_log_tarefa'].'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>' : '');

				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) msg_email_interno('', $titulo, $corpo.$link_interno,'',$usuario['usuario_id']);
				if ($config['email_ativo'] && $config['email_externo_auto'] && $tipo!='excluido' && $usuario['usuario_id']!=$Aplic->usuario_id) {

					$corpo_externo=$corpo;

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$email = new Mail;
						$email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }

						if ($email->EmailValido($usuario['contato_email'])) {
							$email->Assunto($titulo, $localidade_tipo_caract);
							$endereco=link_email_externo($usuario['usuario_id'], 'm=tarefas&a=ver&tab=0&tarefa_id='.$post['tarefa_log_tarefa']);
							$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
							$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
							$email->Para($usuario['contato_email'], true);
							$email->Enviar();
							}
						}
					else{
						$email = new Mail;
						$email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }

						$email->Assunto($titulo, $localidade_tipo_caract);
						$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
						if ($email->EmailValido($usuario['contato_email'])) $email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}


	function excluir($oid = null){
		global $Aplic;
		if ($Aplic->getEstado('tarefa_log_id', null)==$this->tarefa_log_id) $Aplic->setEstado('tarefa_log_id', null);
		parent::excluir();
		return null;
		}

	function podeExcluir(&$msg='', $oid = null, $unioes = null) {
		global $Aplic;
		if (!$Aplic->checarModulo('tarefa_log', 'excluir')) {
			$msg = 'Sem permissão para excluir';
			return false;
			}
		return true;
		}

	function podeAcessar() {
		$valor=permiteAcessar($this->tarefa_log_acesso, $this->getProjeto(), $this->tarefa_log_tarefa);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditar($this->tarefa_log_acesso, $this->getProjeto(), $this->tarefa_log_tarefa);
		return $valor;
		}

	}









//funcoes gerais





function mostrarTarefaGrande($arr=array(), $projeto_id=0, $editar=true, $tarefa_superior=0, $baseline_id=false){
		global $Aplic, $config;
		$saida='';
		$agora = new CData();
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');

		$AdicionarRegistro = $Aplic->checarModulo('tarefa_log', 'adicionar');
		if ($Aplic->usuario_super_admin || permiteAcessar($arr['tarefa_acesso'], (int)$projeto_id, $arr['tarefa_id'])){
		$permiteEditar=($Aplic->usuario_super_admin || permiteEditar($arr['tarefa_acesso'],(int)$projeto_id, $arr['tarefa_id']));

		$data_inicio = intval($arr['tarefa_inicio']) ? new CData($arr['tarefa_inicio']) : null;
		$data_fim = intval($arr['tarefa_fim']) ? new CData($arr['tarefa_fim']) : null;
		$sinal = 1;
		$estilo = '';
		if ($data_inicio) {
			if (!$data_fim)	$data_fim = new CData();
			if ($agora->after($data_inicio) && $arr['tarefa_percentagem'] == 0 && $agora->before($data_fim)) $estilo = 'background-color:#ffeebb';
			else if ($agora->after($data_inicio) && $arr['tarefa_percentagem'] < 100 && $agora->before($data_fim)) $estilo = 'background-color:#e6eedd';
			else if ($arr['tarefa_percentagem'] == 100) $estilo = 'background-color:#aaddaa; color:#00000';
			else if ($agora->after($data_fim) && $arr['tarefa_percentagem'] < 100 ) $estilo = 'background-color:#cc6666;color:#ffffff';
			if ($agora->after($data_fim)) $sinal = -1;
			$dias = $agora->dataDiferenca($data_fim) * $sinal;
			}
		$saida.='<td>';
		$saida.=($editar ? dica('Editar '.ucfirst($config['tarefa']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=editar&tarefa_id='.$arr['tarefa_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;');
		$saida.='</td>';
		$prefixo_marcada= $arr['tarefa_marcada'] ? '' : 'des';
		$saida.='<td align="center"><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=tarefas&marcada='.($arr['tarefa_marcada'] ? 0 : 1).'&tarefa_id='.$arr['tarefa_id'].'\');">'.dica('Marcar', 'Clique neste ícone '.imagem('icones/'.$prefixo_marcada.'marcada.gif').' para '.($arr['tarefa_marcada'] ? 'des' : '').'marcar '.$config['genero_tarefa'].' '.$config['tarefa'].'.<br><br>'.ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' marcadas '.imagem('icones/marcada.gif').' são uma forma de chamar a atenção.',True).'<img src="'.acharImagem('icones/'.$prefixo_marcada. 'marcada.gif').'" border=0 />'.dicaF().'</a></td>';
		if (isset($arr['tarefa_log_problema']) && $arr['tarefa_log_problema'] > 0) $saida.='<td align="center" valign="middle"><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$arr['tarefa_id'].'&tab=0&problem=1\');">'.imagem('icones/aviso.gif', imagem('icones/aviso.gif').' Problema', 'Foi registrado um problema nest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'<br>Clique neste ícone '.imagem('icones/aviso.gif').' para visualizar o registro.'). dicaF().'</a></td>';
		elseif ($arr['tarefa_dinamica'] != 1 && $permiteEditar && $AdicionarRegistro) $saida.='<td align="center" width="11">'.dica('Adicionar Registro', 'Clique neste ícone '.imagem('icones/adicionar.png').' para criar um novo registro nest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa']).'<a href="javascript:void(0);" onclick="popLog('.$arr['tarefa_id'].');">'.imagem('icones/adicionar.png').'</a>'.dicaF().'</td>';
		else $saida.='<td align="center">&nbsp;</td>';
		$saida.='<td align="center">'.dica('Percentual d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Realizada', 'Neste campo é mostrado quantos por cento d'.$config['genero_tarefa'].' '.$config['tarefa'].' já foi realizado.'). intval($arr['tarefa_percentagem']).'%'.dicaF().'</td>';
		$saida.='<td align="center" width="10px" nowrap="nowrap">'.prioridade($arr['tarefa_prioridade']).'</td>';
		$saida.='<td>';

		if ($arr['tarefa_nr_subordinadas'] &&  $arr['tarefa_id']!=$tarefa_superior) $icone=($tarefa_superior && $arr['tarefa_id']!=$tarefa_superior ? '&nbsp;&nbsp;&nbsp;' : '').($arr['tarefa_superior']!=$arr['tarefa_id'] ? $icone=imagem('icones/subnivel.gif') : '').dica('Expandir '.ucfirst($config['tarefa']),'Clique no ícone '.imagem('icones/expandir.gif').' para expandir as subtarefas.').'<a href="javascript: void(0);" onclick="frm.tarefa_superior.value='.$arr['tarefa_id'].'; frm.submit();">'.imagem('icones/expandir.gif').'</a>'.dicaF();
		elseif ($arr['tarefa_nr_subordinadas'] &&  $arr['tarefa_id']==$tarefa_superior) $icone=($tarefa_superior && $arr['tarefa_id']!=$tarefa_superior ? '&nbsp;&nbsp;&nbsp;' : '').($arr['tarefa_superior']!=$arr['tarefa_id'] ? $icone=imagem('icones/subnivel.gif') : '').dica('Colapsar '.ucfirst($config['tarefa']),'Clique no ícone '.imagem('icones/colapsar.gif').' para colapsar as subtarefas.').'<a href="javascript: void(0);" onclick="frm.tarefa_superior.value='.($arr['tarefa_superior']!=$arr['tarefa_id'] ? $arr['tarefa_superior'] : 0).'; frm.submit();">'.imagem('icones/colapsar.gif').'</a>'.dicaF();
		else $icone=($tarefa_superior && $arr['tarefa_id']!=$tarefa_superior ? '&nbsp;&nbsp;&nbsp;' : '').($arr['tarefa_superior']!=$arr['tarefa_id'] ? $icone=imagem('icones/subnivel.gif') : '');
		$saida.=$icone;

		if ($arr['tarefa_marco'] > 0) $saida.='&nbsp;<b>'.link_tarefa($arr['tarefa_id']).'</b>'.dica('Marco de '.ucfirst($config['projeto']), '<ul><li>O marco pode ser vislumbrado como uma data chave de término de um grupo de  '.$config['tarefas'].'.</li><li>No gráfico Gantt será visualizado como um losângulo <font color="#FF0000">&loz;</font> vermelho.</li></ul>'). '<img src="'.acharImagem('icones/marco.gif').'" border=0 />'.dicaF();
		elseif ($arr['tarefa_dinamica'] == '1') $saida.='&nbsp;<b><i>'.link_tarefa($arr['tarefa_id']).'</i></b>';
		else $saida.='&nbsp;'.link_tarefa($arr['tarefa_id']);

		$saida.=((isset($arr['nr_arquivos']) && $arr['nr_arquivos'] > 0) ? dica('Tem Anexo', 'Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem '.$arr['nr_arquivos'].' anexo'.($arr['nr_arquivos'] > 1 ? 's.':'.')).imagem('icones/clip.png').dicaF() : '');
		$saida.=(isset($arr['tarefa_acao']) && $arr['tarefa_acao'] ? dica('Ação Social', 'Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' é referente a uma ação social.').imagem('../../../modulos/social/imagens/social_p.gif').dicaF() : '');
		$v = new BDConsulta;

		$obj = new CTarefa();
		$obj->tarefa_id=$arr['tarefa_id'];
		$custo=$obj->custo_estimado($baseline_id);
		if ($custo){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de custos estimados.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Custos</b></td><td>'.$config['simbolo_moeda'].' '.number_format($custo, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha&dialogo=1&tarefa_id='.$arr['tarefa_id'].'&tipo=estimado\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no ícone '.imagem('icones/planilha_estimado.gif').' ver a planilha de custos estimados</td></tr>';
			$saida.=' '.dica('Valores', $dentro).$link.imagem('icones/planilha_estimado.gif').'</a>'.dicaF();
			}

		$gasto=$obj->gasto_efetuado($baseline_id);
		if ($gasto){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de gastos efetuados.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>gastos</b></td><td>'.$config['simbolo_moeda'].' '.number_format($gasto, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha&dialogo=1&baseline_id='.$baseline_id.'&tarefa_id='.$arr['tarefa_id'].'&tipo=efetivo\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no ícone '.imagem('icones/planilha_gasto.gif').' ver a planilha de gastos efetuados</td></tr>';
			$saida.=' '.dica('Valores', $dentro).$link.imagem('icones/planilha_gasto.gif').'</a>'.dicaF();
			}

		$mao_obra_gasto=($Aplic->profissional ? $obj->mao_obra_gasto($baseline_id) : null);
		if ($mao_obra_gasto){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de gasto com mão de obra.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Mão de obra</b></td><td>'.$config['simbolo_moeda'].' '.number_format($mao_obra_gasto, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha_mao_obra&dialogo=1&tarefa_id='.$arr['tarefa_id'].'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no ícone '.imagem('icones/mo_estimado.gif').' ver a planilha de gastos com mão de obra</td></tr>';
			$saida.=' '.dica('Valores', $dentro).$link.imagem('icones/mo_estimado.gif').'</a>'.dicaF();
			}
		$v->adTabela(($baseline_id ? 'baseline_' : '').'recurso_tarefas','recurso_tarefas');
		$v->adCampo('count(recurso_id)');
		$v->adOnde('tarefa_id = '.$arr['tarefa_id']);
		if ($baseline_id)	$v->adOnde('recurso_tarefas.baseline_id='.(int)$baseline_id);
		$qnt = $v->Resultado();
		$v->limpar();
		if ($qnt > 0) $saida.='<a href="javascript:void(0);" onclick="javascript:window.open(\'?m=tarefas&a=lista_recursos&dialogo=1&tarefa_id='.$arr['tarefa_id'].'\', \'Recursos\', \'width=790, height=470, left=0, top=0, scrollbars=yes, resizable=no\')">&nbsp;'.imagem('icones/recurso_estimado.gif','Recursos Alocados', 'Há alocação de '.$qnt.' recurso'.($qnt>1 ? 's' : '').' para '.($config['genero_tarefa']=='a' ? 'esta': 'este').' '.$config['tarefa'].'.<br><br>Clique no ícone '.imagem('icones/recurso_estimado.gif').' para visualizar').'</a>';
		$saida.='</td>';
		$saida.='<td nowrap="nowrap" align="left" >&nbsp;'.link_usuario($arr['tarefa_dono'],'','','esquerda').'</td>';
		$v->adTabela('tarefa_designados');
		$v->adCampo('usuario_id, perc_designado');
		$v->adOnde('tarefa_id = '.$arr['tarefa_id']);
		$participantes = $v->lista();
		$v->limpar();
		$saida_quem='';
		if ($participantes && count($participantes)) {
				$saida_quem.= link_usuario($participantes[0]['usuario_id'], '','','esquerda');
				$qnt_participantes=count($participantes);
				if ($qnt_participantes > 1) {
						$lista='';
						for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').'<br>';
						$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar_grande(\'participantes_'.$arr['tarefa_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$arr['tarefa_id'].'"><br>'.$lista.'</span>';
						}
				}
		$saida.= '<td align="left" nowrap="nowrap">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
		$saida.='<td id="ignore_td_'.$arr['tarefa_id'].'" nowrap="nowrap" width="120px" align="center" style="'.$estilo.'">&nbsp;'.($data_inicio ? $data_inicio->format($df.' '.$tf) : '&nbsp;').'&nbsp;</td><td id="ignore_td_'.$arr['tarefa_id'].'" align="right" nowrap="nowrap" style="'.$estilo.'">&nbsp;'.number_format($arr['tarefa_duracao']/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8), 0, ',', '.').'&nbsp;</td><td width="120px" id="ignore_td_'.$arr['tarefa_id'].'" nowrap="nowrap" align="center" style="'.$estilo.'">&nbsp;'.($data_fim ? $data_fim->format($df.' '.$tf) : '&nbsp;').'&nbsp;</td>';
		$saida.='<td id="ignore_td_'.$arr['tarefa_id'].'" nowrap="nowrap" align="center" style="'.$estilo.'">'.$arr['dias'].'</td>';
		if ($config['editar_designado_diretamente']){
		 	$saida.= '<td align="center" width="10">'.($editar ? dica('Selecionar '.ucfirst($config['tarefa']), 'Marque esta caixa, caso deseje deslocar as datas de início e término d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.<ul><li>Após ter terminado de marcar '.$config['genero_tarefa'].'s '.$config['tarefas'].' selecione a opção de tempo na caixa de seleção <b>deslocar</b> no canto inferior.').'<input type="checkbox" name="selecionado_tarefa['.$arr['tarefa_id'].']" value="'.$arr['tarefa_id'].'" onfocus="estah_marcado=true;" onblur="estah_marcado=false;" id="selecionado_tarefa_'.$arr['tarefa_id'].'" />'.dicaF() : '&nbsp;').'</td>';
			}
		$saida.='</tr>';
		}
	return $saida;
	}


function mostrarTarefa($arr, $nivel = 0, $aberta = true, $visao_hoje = false, $esconderAbrirFecharLink = false, $permitirRepitir = false, $baseline_id=false) {
	global $Aplic, $texto_consulta, $tipoDuracao, $usuarioDesig, $config ;
	global $m, $a, $historico_ativo;
	$AdicionarRegistro = $Aplic->checarModulo('tarefa_log', 'adicionar');
	if ($Aplic->usuario_super_admin || permiteAcessar($arr['tarefa_acesso'], $arr['tarefa_projeto'], $arr['tarefa_id'])){
		$expandido = $Aplic->getPref('tarefasexpandidas');
		$agora = new CData();
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$podeEditar = ($Aplic->usuario_super_admin || $Aplic->checarModulo('tarefas','editar'));
		$permiteEditar=($Aplic->usuario_super_admin || permiteEditar($arr['tarefa_acesso'], $arr['tarefa_projeto'], $arr['tarefa_id']));
		$editar=($podeEditar&&$permiteEditar);
		if ($editar){
			if (($m=='projetos' && $a=='ver') ||($m=='tarefas' && $a=='index'))	$clique='selecionar_caixa(\'selecionado_tarefa\', \''.$arr['tarefa_id'].'\', \'projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_\',\'frmDesignar'.$arr['tarefa_projeto'].'\')';
			else $clique='selecionar_caixa(\'selecionado_tarefa\', \''.$arr['tarefa_id'].'\', \'projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_\',\'frm_tarefas\')';
			}
		else $clique='';
		$data_inicio = intval($arr['tarefa_inicio']) ? new CData($arr['tarefa_inicio']) : null;
		$data_fim = intval($arr['tarefa_fim']) ? new CData($arr['tarefa_fim']) : null;
		$sinal = 1;
		$estilo = '';
		if ($data_inicio) {
			if (!$data_fim)	$data_fim = new CData();
			if ($agora->after($data_inicio) && $arr['tarefa_percentagem'] == 0 && $agora->before($data_fim)) $estilo = 'background-color:#ffeebb';
			else if ($agora->after($data_inicio) && $arr['tarefa_percentagem'] < 100 && $agora->before($data_fim)) $estilo = 'background-color:#e6eedd';
			else if ($arr['tarefa_percentagem'] == 100) $estilo = 'background-color:#aaddaa; color:#00000';
			else if ($agora->after($data_fim) && $arr['tarefa_percentagem'] < 100 ) $estilo = 'background-color:#cc6666;color:#ffffff';
			if ($agora->after($data_fim)) $sinal = -1;
			$dias = $agora->dataDiferenca($data_fim) * $sinal;
			}
		if ($m=='projetos' && $a=='ver')	echo '<tr id="projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_" onmouseover="iluminar_tds(this, true, '.$arr['tarefa_id'].', '.$arr['tarefa_projeto'].')" onmouseout="iluminar_tds(this, false, '.$arr['tarefa_id'].', '.$arr['tarefa_projeto'].')" '.(($nivel > 0 && !$expandido) ? 'style="display:none"' : '').($clique ? ' onclick="'.$clique.'"' : '').'>';
		elseif ($m!='tarefas') echo '<tr id="projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_" onmouseover="iluminar_tds(this, true, '.$arr['tarefa_id'].')" onmouseout="iluminar_tds(this, false, '.$arr['tarefa_id'].')" '.(($nivel > 0 && !$expandido) ? 'style="display:none"' : '').($clique ? ' onclick="'.$clique.'"' : '').'>';
		else echo '<tr>';
		echo '<td align="center">';

		echo ($editar ? dica('Editar '.ucfirst($config['tarefa']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=editar&tarefa_id='.$arr['tarefa_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;');
		echo '</td>';
		$prefixo_marcada= (isset($arr['tarefa_marcada']) && $arr['tarefa_marcada'] ? '' : 'des');
		echo '<td align="center"><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=tarefas&marcada='.(isset($arr['tarefa_marcada']) && $arr['tarefa_marcada'] ? 0 : 1).'&tarefa_id='.$arr['tarefa_id'].'\');">'.dica('Marcar', 'Clique neste ícone '.imagem('icones/'.$prefixo_marcada.'marcada.gif').' para '.(isset($arr['tarefa_marcada']) && $arr['tarefa_marcada'] ? 'des' : '').'marcar '.$config['genero_tarefa'].' '.$config['tarefa'].'.<br><br>'.ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' marcadas '.imagem('icones/marcada.gif').' são uma forma de chamar a atenção.',True).'<img src="'.acharImagem('icones/'.$prefixo_marcada. 'marcada.gif').'" border=0 />'.dicaF().'</a></td>';
		if (isset($arr['tarefa_log_problema']) && $arr['tarefa_log_problema'] > 0) echo '<td align="center" valign="middle"><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$arr['tarefa_id'].'&tab=0&problem=1\');">'.imagem('icones/aviso.gif', imagem('icones/aviso.gif').' Problema', 'Foi registrado um problema nest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'<br>Clique neste ícone '.imagem('icones/aviso.gif').' para visualizar o registro.'). dicaF().'</a></td>';
		elseif ($AdicionarRegistro && $arr['tarefa_dinamica'] != 1 && $permiteEditar) echo '<td align="center" width="11">'.dica('Adicionar Registro', 'Clique neste ícone '.imagem('icones/adicionar.png').' para criar um novo registro dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa']).'<a href="javascript:void(0);" onclick="popLog('.$arr['tarefa_id'].');">'.imagem('icones/adicionar.png').'</a>'.dicaF().'</td>';
		else echo '<td align="center">&nbsp;</td>';
		echo'<td align="center">'.dica('Percentual d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Realizada', 'Neste campo é mostrado quantos por cento d'.$config['genero_tarefa'].' '.$config['tarefa'].' já foi realizado.'). intval($arr['tarefa_percentagem']).'%'.dicaF().'</td>';
		echo '<td align="center" width="10px" nowrap="nowrap">'.prioridade($arr['tarefa_prioridade']).'</td>';
		echo '<td>';
		if ($nivel == -1)	echo '...';
		for ($y = 0; $y < $nivel; $y++) {
			if ($y + 1 == $nivel)	echo '<img src="'.acharImagem('subnivel.gif').'" width="16" height="12" border=0>';
			else echo '<img src="'.acharImagem('shim.gif').'" width="16" height="12"  border=0>';
			}
		$abrir_link=($m!='tarefas' ? '<a href="javascript: void(0);">'.dica('Colapsar '.ucfirst($config['tarefa']),'Clique no ícone '.imagem('icones/colapsar.gif').' para colapsar as subtarefas.').'<img onclick="expandir_colapsar(\'projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_\', \'tblProjetos\',\'\','.($nivel+1).');'.$clique.'" id="projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'__colapsar" src="'.acharImagem('icones/colapsar.gif').'" border=0 align="center" '.(!$expandido?'style="display:none"':'').' />'.dicaF().dica('Expandir '.ucfirst($config['tarefa']),'Clique no ícone '.imagem('icones/expandir.gif').' para expandir as subtarefas.').'<img onclick="expandir_colapsar(\'projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_\', \'tblProjetos\',\'\','.($nivel+1).'); '.$clique.'"  id="projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'__expandir" src="'.acharImagem('icones/expandir.gif').'" border=0 align="center" '.($expandido?'style="display:none"':'').' /></a>'.dicaF() : '');
		if (isset($arr['tarefa_nr_subordinadas']) && $arr['tarefa_nr_subordinadas']) $superior = true;
		else $superior = false;
		if ($arr['tarefa_marco'] > 0) echo '&nbsp;<b>'.link_tarefa($arr['tarefa_id']).'</b>'.dica('Marco de '.ucfirst($config['projeto']), '<ul><li>O marco pode ser vislumbrado como uma data chave de término de um grupo de  '.$config['tarefas'].'.</li><li>No gráfico Gantt será visualizado como um losângulo <font color="#FF0000">&loz;</font> vermelho.</li></ul>'). '<img src="'.acharImagem('icones/marco.gif').'" border=0 />'.dicaF();
		elseif ($arr['tarefa_dinamica'] == '1' || $superior) {
				if (!$visao_hoje) echo $abrir_link;
				if ($arr['tarefa_dinamica'] == '1') echo '&nbsp;<b><i>'.link_tarefa($arr['tarefa_id']).'</i></b>';
				else echo '&nbsp;'.link_tarefa($arr['tarefa_id']);
				}
		else echo '&nbsp;'.link_tarefa($arr['tarefa_id']);
		echo ((isset($arr['nr_arquivos']) && $arr['nr_arquivos'] > 0) ? dica('Tem Anexo', 'Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem '.$arr['nr_arquivos'].' anexo'.($arr['nr_arquivos'] > 1 ? 's.':'.')).imagem('icones/clip.png').dicaF() : '');

		echo (isset($arr['tarefa_acao']) && $arr['tarefa_acao'] ? dica('Ação Social', 'Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' é referente a uma ação social.').imagem('../../../modulos/social/imagens/social_p.gif').dicaF() : '');


		$obj = new CTarefa();
		$obj->tarefa_id=$arr['tarefa_id'];
		$custo=$obj->custo_estimado($baseline_id);
		if ($custo){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de custos estimados.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Custos</b></td><td>'.$config['simbolo_moeda'].' '.number_format($custo, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha&dialogo=1&baseline_id='.$baseline_id.'&tarefa_id='.$arr['tarefa_id'].'&tipo=estimado\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no ícone '.imagem('icones/planilha_estimado.gif').' ver a planilha de custos estimados</td></tr>';
			echo ' '.dica('Valores', $dentro).$link.imagem('icones/planilha_estimado.gif').'</a>'.dicaF();
			}

		$gasto=$obj->gasto_efetuado($baseline_id);
		if ($gasto){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de gastos efetuados.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>gastos</b></td><td>'.$config['simbolo_moeda'].' '.number_format($gasto, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha&dialogo=1&baseline_id='.$baseline_id.'&tarefa_id='.$arr['tarefa_id'].'&tipo=efetivo\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no ícone '.imagem('icones/planilha_gasto.gif').' ver a planilha de gastos efetuados</td></tr>';
			echo ' '.dica('Valores', $dentro).$link.imagem('icones/planilha_gasto.gif').'</a>'.dicaF();
			}

		$mao_obra_gasto=($Aplic->profissional ? $obj->mao_obra_gasto($baseline_id) : null);

		if ($mao_obra_gasto){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de gasto com mão de obra.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Mão de obra</b></td><td>'.$config['simbolo_moeda'].' '.number_format($mao_obra_gasto, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha_mao_obra&baseline_id='.$baseline_id.'&dialogo=1&tarefa_id='.$arr['tarefa_id'].'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no ícone '.imagem('icones/mo_estimado.gif').' ver a planilha de gastos com mão de obra</td></tr>';
			echo ' '.dica('Valores', $dentro).$link.imagem('icones/mo_estimado.gif').'</a>'.dicaF();
			}


		$v = new BDConsulta;
		$v->adTabela(($baseline_id ? 'baseline_' : '').'recurso_tarefas','recurso_tarefas');
		$v->adCampo('count(recurso_id)');
		$v->adOnde('tarefa_id = '.$arr['tarefa_id']);
		if ($baseline_id)	$v->adOnde('recurso_tarefas.baseline_id='.(int)$baseline_id);
		$qnt = $v->Resultado();
		$v->limpar();

		if ($qnt > 0) echo'<a href="javascript:void(0);" onclick="javascript:window.open(\'?m=tarefas&a=lista_recursos&baseline_id='.$baseline_id.'&dialogo=1&tarefa_id='.$arr['tarefa_id'].'\', \'Recursos\', \'width=790, height=470, left=0, top=0, scrollbars=yes, resizable=no\')">&nbsp;'.imagem('icones/recurso_estimado.gif','Recursos Alocados', 'Há alocação de '.$qnt.' recurso'.($qnt>1 ? 's' : '').' para '.($config['genero_tarefa']=='a' ? 'esta': 'este').' '.$config['tarefa'].'.<br><br>Clique no ícone '.imagem('icones/recurso_estimado.gif').' para visualizar').'</a>';

		echo '</td>';
		if ($visao_hoje)  echo '<td align="left">'.link_projeto($arr['tarefa_projeto'], 'cor').'</td>';
		else echo '<td nowrap="nowrap" align="left" >&nbsp;'.link_usuario($arr['tarefa_dono'],'','','esquerda').'</td>';
		if (isset($arr['tarefa_designado_usuarios']) && ($usuarios_designados = $arr['tarefa_designado_usuarios'])) {
			$a_u_vetor_tmp = array();

			echo '<td align="left" nowrap="nowrap">&nbsp;'.link_usuario($usuarios_designados[0]['usuario_id'],'','','esquerda').' ('.$usuarios_designados[0]['perc_designado'].'%)&nbsp;';
			if (count($usuarios_designados) > 1) {
				$lista='';
				echo dica('Outros '.ucfirst($config['usuarios']).' Designados', 'Clique para ver os demais designados.').' <a href="javascript: void(0);" onclick="ativar_usuarios('."'usuarios_".$arr['tarefa_id']."'".'); '.$clique.'">(+'.(count($usuarios_designados) - 1).')</a>'.dicaF(). '<span style="display: none" id="usuarios_'.$arr['tarefa_id'].'">';
				$a_u_vetor_tmp[] = $usuarios_designados[0]['usuario_id'];
				for ($i = 1, $i_cmp = count($usuarios_designados); $i < $i_cmp; $i++) {
					$a_u_vetor_tmp[] = $usuarios_designados[$i]['usuario_id'];
					echo '<br />&nbsp;'.link_usuario($usuarios_designados[$i]['usuario_id'], '','','esquerda').' ('.$usuarios_designados[$i]['perc_designado'].'%)';
					}
				echo '</span>';
				}
			echo '</td>';

			}
		elseif (!$visao_hoje) echo '<td align="center">&nbsp;</td>';
		echo '<td id="ignore_td_'.$arr['tarefa_id'].'" nowrap="nowrap" width="120px" align="center" style="'.$estilo.'">&nbsp;'.($data_inicio ? $data_inicio->format($df.' '.$tf) : '&nbsp;').'&nbsp;</td><td id="ignore_td_'.$arr['tarefa_id'].'" align="right" nowrap="nowrap" style="'.$estilo.'">&nbsp;'.number_format($arr['tarefa_duracao']/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8), 0, ',', '.').'&nbsp;</td><td width="120px" id="ignore_td_'.$arr['tarefa_id'].'" nowrap="nowrap" align="center" style="'.$estilo.'">&nbsp;'.($data_fim ? $data_fim->format($df.' '.$tf) : '&nbsp;').'&nbsp;</td>';
		if ($visao_hoje) echo '<td id="ignore_td_'.$arr['tarefa_id'].'" nowrap="nowrap" align="center" style="'.$estilo.'">'.($arr['tarefa_fazer_em'] > 0 ? $arr['tarefa_fazer_em'] : dica('Prazo Expirou', 'Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' deveria ter sido completada há '.($arr['tarefa_fazer_em']*-1). ' dias atrás.').'expirou'.dicaF()).'</td>';
		echo '<td id="ignore_td_'.$arr['tarefa_id'].'" nowrap="nowrap" align="center" style="'.$estilo.'">'.$arr['dias'].'</td>';
		if ($config['editar_designado_diretamente']){
			if ($editar && $m=='projetos') echo '<td align="center" width="10">'.dica('Selecionar '.ucfirst($config['tarefa']), 'Marque esta caixa, caso deseje deslocar as datas de início e término d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.<ul><li>Após ter terminado de marcar '.$config['genero_tarefa'].'s '.$config['tarefas'].' selecione a opção de tempo na caixa de seleção <b>deslocar</b> no canto inferior.').'<input type="checkbox" name="selecionado_tarefa['.$arr['tarefa_id'].']" value="'.$arr['tarefa_id'].'"  onclick="'.$clique.'" onfocus="estah_marcado=true;" onblur="estah_marcado=false;" id="selecionado_tarefa_'.$arr['tarefa_id'].'" />'.dicaF().'</td>';
			elseif ($m!='tarefas') echo '<td align="center">&nbsp;</td>';
			}
		echo '</tr>';
		}
	}

function acharSubordinada($tarr, $superior, $nivel = 0, $baseline_id=false, $filhos=array()) {
	global $tarefas_mostradas, $expandido;
	$nivel = $nivel + 1;
	if (isset($filhos[$superior])){
		foreach ($filhos[$superior] as $tarefa_id) {
			mostrarTarefa($tarr[$tarefa_id], $nivel, true, false, false, false, $baseline_id);
			$tarefas_mostradas[] = $tarefa_id;
			acharSubordinada($tarr, $tarefa_id, $nivel, $baseline_id, $filhos);
			}
		}
	}

function vetor_ordenar() {
	$args = func_get_args();
	$mvetor = array_shift($args);
	if (empty($mvetor)) return array();
	$i = 0;
	$mLinhaOrdenada = 'return(array_multisort(';
	$vetorOrdenado = array();
	foreach ($args as $arg) {
		$i++;
		if (is_string($arg)) {
			for ($j = 0, $j_cmp = count($mvetor); $j < $j_cmp; $j++) {
				if (!$mvetor[$j]['tarefa_fim']) $mvetor[$j]['tarefa_fim'] = calcFimPorInicioEDuracao($mvetor[$j]);
				if (isset($mvetor[$j][$arg])) $vetorOrdenado[$i][] = $mvetor[$j][$arg];
				else  $vetorOrdenado[$i][] ='';
				}
			}
		else $vetorOrdenado[$i] = $arg;
		$mLinhaOrdenada .= '$vetorOrdenado['.$i.'],';
		}
	$mLinhaOrdenada .= '$mvetor));';
	eval($mLinhaOrdenada);
	return $mvetor;
	}

function calcFimPorInicioEDuracao($tarefa) {
	$data_fim = new CData($tarefa['tarefa_inicio']);
	$data_fim->adSegundos($tarefa['tarefa_duracao'] * $tarefa['tarefa_duracao_tipo'] * SEG_HORA);
	return $data_fim->format(FMT_TIMESTAMP_MYSQL);
	}

function ordenar_por_item_titulo($titulo, $item_nome, $item_tipo, $a = '') {
	global $Aplic, $projeto_id, $tarefa_id, $ver_min, $m;
	global $tarefa_ordenar_item1, $tarefa_ordenar_tipo1, $tarefa_ordenar_ordem1;
	global $tarefa_ordenar_item2, $tarefa_ordenar_tipo2, $tarefa_ordenar_ordem2;
	if ($tarefa_ordenar_item2 == $item_nome) $item_ordem = $tarefa_ordenar_ordem2;
	if ($tarefa_ordenar_item1 == $item_nome) $item_ordem = $tarefa_ordenar_ordem1;
	$s = '';
	if (isset($item_ordem)) $mostrar_icone = true;
 	else {
		$mostrar_icone = false;
		$item_ordem = SORT_DESC;
		}
	$item_ordem = ($item_ordem == SORT_ASC) ? SORT_DESC : SORT_ASC;
	if ($m == 'tarefas') $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas'.(($tarefa_id > 0) ? ('&a=ver&tarefa_id='.$tarefa_id) : $a);
	elseif ($m == 'calendario') $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=ver_dia';
	else $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&bypass=1'.($projeto_id > 0 ? '&a=ver&projeto_id='.$projeto_id : '');
	$s .= '&tarefa_ordenar_item1='.$item_nome;
	$s .= '&tarefa_ordenar_tipo1='.$item_tipo;
	$s .= '&tarefa_ordenar_ordem1='.$item_ordem;
	if ($tarefa_ordenar_item1 == $item_nome) {
		$s .= '&tarefa_ordenar_item2='.$tarefa_ordenar_item2;
		$s .= '&tarefa_ordenar_tipo2='.$tarefa_ordenar_tipo2;
		$s .= '&tarefa_ordenar_ordem2='.$tarefa_ordenar_ordem2;
		}
	else {
		$s .= '&tarefa_ordenar_item2='.$tarefa_ordenar_item1;
		$s .= '&tarefa_ordenar_tipo2='.$tarefa_ordenar_tipo1;
		$s .= '&tarefa_ordenar_ordem2='.$tarefa_ordenar_ordem1;
		}
	$s .= '\');" class="hdr">'.$titulo;
	if ($mostrar_icone) $s .= '&nbsp;<img src="'.acharImagem('icones/seta-'.(($item_ordem == SORT_ASC) ? 'cima' : 'baixo').'.gif').'" border=0 /></a>';
	return $s;
	}


function ordenar_por_titulo($titulo, $item_nome, $item_tipo, $m= '', $a = '', $usuario_id='',$dept_id='') {
	global $Aplic, $projeto_id, $tarefa_id, $ver_min, $m;
	global $tarefa_ordenar_item1, $tarefa_ordenar_tipo1, $tarefa_ordenar_ordem1;
	global $tarefa_ordenar_item2, $tarefa_ordenar_tipo2, $tarefa_ordenar_ordem2;
	if ($tarefa_ordenar_item2 == $item_nome) $item_ordem = $tarefa_ordenar_ordem2;
	if ($tarefa_ordenar_item1 == $item_nome) $item_ordem = $tarefa_ordenar_ordem1;
	$s = '';
		if (isset($item_ordem)) $mostrar_icone = true;
 	else {
		$mostrar_icone = false;
		$item_ordem = SORT_DESC;
		}
	$item_ordem = ($item_ordem == SORT_ASC) ? SORT_DESC : SORT_ASC;
	$s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&bypass=1&'.$a.($usuario_id ? '&usuario_id='.$usuario_id : '').($dept_id ? '&dept_id='.$dept_id : '');
	$s .= '&tarefa_ordenar_item1='.$item_nome;
	$s .= '&tarefa_ordenar_tipo1='.$item_tipo;
	$s .= '&tarefa_ordenar_ordem1='.$item_ordem;
	if ($tarefa_ordenar_item1 == $item_nome) {
		$s .= '&tarefa_ordenar_item2='.$tarefa_ordenar_item2;
		$s .= '&tarefa_ordenar_tipo2='.$tarefa_ordenar_tipo2;
		$s .= '&tarefa_ordenar_ordem2='.$tarefa_ordenar_ordem2;
		}
	else {
		$s .= '&tarefa_ordenar_item2='.$tarefa_ordenar_item1;
		$s .= '&tarefa_ordenar_tipo2='.$tarefa_ordenar_tipo1;
		$s .= '&tarefa_ordenar_ordem2='.$tarefa_ordenar_ordem1;
		}
	$s .= '\');" class="hdr">'.$titulo;
	if ($mostrar_icone) $s .= '&nbsp;<img src="'.acharImagem('icones/seta-'.(($item_ordem == SORT_ASC) ? 'cima' : 'baixo').'.gif').'" border=0 /></a>';
	echo $s;
	}
?>