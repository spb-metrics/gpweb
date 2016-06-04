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

require_once $Aplic->getClasseSistema('aplic');
include_once BASE_DIR.'/modulos/tarefas/funcoes.php';

class CRecurso extends CAplicObjeto {
	var $recurso_id = null;
	var $recurso_nome = null;
	var $recurso_chave = null;
	var $recurso_tipo = null;
	var $recurso_nota = null;
	var $recurso_max_alocacao = null;
	var $recurso_cia = null;
	var $recurso_dept = null;
	var $recurso_nivel_acesso = null;
	var $recurso_responsavel = null;
	var $recurso_unidade = null;
  var $recurso_quantidade = null;
  var $recurso_custo = null;
  var $recurso_nd = null;
  var $recurso_categoria_economica = null;	
	var $recurso_grupo_despesa = null;	
	var $recurso_modalidade_aplicacao = null;	
	var $recurso_ev = null;
	var $recurso_esf = null;
	var $recurso_ptres = null;
	var $recurso_fonte = null;
	var $recurso_sb = null;
	var $recurso_ugr = null;
	var $recurso_pi = null;
	var $recurso_ano = null;
	var $recurso_resultado_primario = null;
	var $recurso_origem = null;
	var $recurso_contato = null;
	var $recurso_credito_adicional = null;
	var $recurso_movimentacao_orcamentaria = null;
	var $recurso_identificador_uso = null;
	var $recurso_liberado = null;
	var $recurso_hora_custo = null;
	var $recurso_centro_custo = null;
	var $recurso_conta_orcamentaria = null;
	var $recurso_ativo = null;	
	var $recurso_principal_indicador = null;
	
	function __construct($recurso_id=0) {
    global $config;
		parent::__construct('recursos', 'recurso_id');
		$this->recurso_id=$recurso_id;
    $this->recurso_nivel_acesso = $config['nivel_acesso_padrao'];
		}
	
	function getNomeTipo() {
		$sql = new BDConsulta;
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor');
		$sql->adOnde( 'sisvalor_titulo = \'tipoRecurso\' AND sisvalor_valor_id = \''.$this->recurso_tipo. '\'' );
		$resultado = $sql->Resultado();
		$sql->limpar();
		if (!$resultado) $resultado = 'Todos os Recursos';
		return $resultado;
		}
		
	
	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta;
		$msg = $this->check();
		if ($msg) {
			$msg_retorno = array(get_class($this).':: checagem de armazenamento', 'falhou', '-');
			if (is_array($msg)) return array_merge($msg_retorno, $msg);
			else {
				array_push($msg_retorno, $msg);
				return $msg_retorno;
				}
			}
		if ($this->recurso_id) {
			$ret = $sql->atualizarObjeto('recursos', $this, 'recurso_id');
			$sql->limpar();
			$this->_acao = 'atualizada';
			} 
		else {
			$this->_acao = 'adicionada';
			$ret = $sql->inserirObjeto('recursos', $this, 'recurso_id');
			$sql->limpar();
			}
	
		$sql->setExcluir('recurso_depts');
		$sql->adOnde('recurso_id='.(int)$this->recurso_id);
		$sql->exec();
		$sql->limpar();
		
		$depts=getParam($_REQUEST, 'recurso_depts', '');
		$depts=explode(',', $depts);
		if (count($depts)) {
			foreach ($depts as $secao) {
				if ($secao){
					$sql->adTabela('recurso_depts');
					$sql->adInserir('recurso_id', $this->recurso_id);
					$sql->adInserir('departamento_id', $secao);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		$sql->setExcluir('recurso_usuarios');
		$sql->adOnde('recurso_id='.(int)$this->recurso_id);
		$sql->exec();
		$sql->limpar();
		
		$usuarios=getParam($_REQUEST, 'recurso_usuarios', '');
		$usuarios=explode(',', $usuarios);
		if (count($usuarios)) {
			foreach ($usuarios as $usuario) {
				if ($usuario){
					$sql->adTabela('recurso_usuarios');
					$sql->adInserir('recurso_id', $this->recurso_id);
					$sql->adInserir('usuario_id', $usuario);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		
		if ($Aplic->profissional){
			$sql->setExcluir('recurso_cia');
			$sql->adOnde('recurso_cia_recurso='.(int)$this->recurso_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'recurso_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('recurso_cia');
						$sql->adInserir('recurso_cia_recurso', $this->recurso_id);
						$sql->adInserir('recurso_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}	
			}	
			
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('recursos', $this->recurso_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->recurso_id);	
			
		}
	
	function qntDisponivel($tarefa_id=0, $data_inicio=null, $data_fim=null){
		if ($this->recurso_tipo < 4) return (float)$this->recurso_quantidade;
		$sql = new BDConsulta;
		$sql->adTabela('recurso_tarefas');
		$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id=recurso_tarefas.tarefa_id');
		$sql->adCampo('SUM(recurso_quantidade)');
		$sql->adOnde('recurso_id='.$this->recurso_id);
		if ($tarefa_id) $sql->adOnde('recurso_tarefas.tarefa_id!='.$tarefa_id);
		if ($data_inicio && $data_fim && ($this->recurso_tipo < 4)) $sql->adOnde('tarefa_inicio<=\''.$data_fim.'\' AND tarefa_fim>=\''.$data_inicio.'\'');
		
		$resultado = $sql->Resultado();
		$sql->limpar();
		return ($this->recurso_quantidade-$resultado);
		}
	
	
	function podeAcessar($usuario_id) {
		global $Aplic;
		$sql = new BDConsulta;
	  //EUZ adiciondo @ para ocultar Warning
		if ($Aplic->usuario_super_admin) return true;
		switch ($this->recurso_nivel_acesso) {
			case 0:
				// publico
				$valorRetorno = true;
				break;
			case 1:
				// protegido
				$valorRetorno = true;			
				break;
			case 2:
				// participante
				$sql->adTabela('recurso_usuarios');
				$sql->adCampo('COUNT(recurso_id)');
				$sql->adOnde('usuario_id='.(int)$usuario_id.' AND recurso_id='.(int)$this->recurso_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = (($quantidade > 0) || $this->recurso_responsavel == $usuario_id);
				break;
			case 3:
				// privado
				$sql->adTabela('recurso_usuarios');
				$sql->adCampo('COUNT(recurso_id)');
				$sql->adOnde('usuario_id='.(int)$usuario_id.' AND recurso_id='.(int)$this->recurso_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = (($quantidade > 0) || $this->recurso_responsavel == $usuario_id);
				break;
			}
		return $valorRetorno;
		}

	function podeEditar($usuario_id) {
		global $Aplic;
		$sql = new BDConsulta;
  	//EUZ adiciondo @ para ocultar Warning
		if ($Aplic->usuario_super_admin) return true;
		switch ($this->recurso_nivel_acesso) {
			case 0:
				// publico
				$valorRetorno = true;
				break;
			case 1:
				// protegido
				$sql->adTabela('recurso_usuarios');
				$sql->adCampo('COUNT(recurso_id)');
				$sql->adOnde('usuario_id='.(int)$usuario_id.' AND recurso_id='.(int)$this->recurso_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = ($quantidade > 0 || $this->recurso_responsavel == $usuario_id);
				break;		
			case 2:
				// participante
				$sql->adTabela('recurso_usuarios');
				$sql->adCampo('COUNT(recurso_id)');
				$sql->adOnde('usuario_id='.(int)$usuario_id.' AND recurso_id='.(int)$this->recurso_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = ($quantidade > 0 || $this->recurso_responsavel == $usuario_id);
				break;
			case 3:
				// privado
				$valorRetorno = ($this->recurso_responsavel == $usuario_id);
				break;
			default:
				$valorRetorno = false;
				break;
			}
		return $valorRetorno;
		}

	
	
	function setData($data){
		$this->data=$data;
		$this->minical = new CCalendarioMes($data);
		$this->minical->setEstilo('minititulo', 'minical');
		$this->minical->mostrarSetas = false;
		$this->minical->mostrarSemana = false;
		$this->minical->clicarMes = false;
		$this->minical->setAlocacao('sim');
		}


	function adicionarMes($qnt){
		$this->data->adMeses($qnt);
		}

	function calendarioMesAtual($sobrecarga=false){
		$primeira_data = new CData($this->data);
		$primeira_data->setDay(1);
		$primeira_data->setTime(0, 0, 0);
		$ultima_data = new CData($this->data);
		$ultima_data->setDay($this->data->getDaysInMonth());
		$ultima_data->setTime(23, 59, 59);
		$links = array();
		$this->getDisponibilidadeLinks($links);
		$this->minical->setEventos($links);
		$this->minical->setData($this->data);
		return $this->minical->mostrar();
		}
		
		
	function getDisponibilidadeLinks(&$links) {
		global $a, $Aplic, $config, $usuario_id;
		$primeira_data = new CData($this->data);
		$primeira_data->setDay(1);
		$primeira_data->setTime(0, 0, 0);
		$ultima_data = new CData($this->data);
		$ultima_data->setDay($this->data->getDaysInMonth());
		$ultima_data->setTime(23, 59, 59);
		$d="%Y-%m-%d";
		$horas_diasuteis=array();
		$sql = new BDConsulta;
		$sql->adTabela('recurso_tarefas', 'rt');
		$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id=rt.tarefa_id');
		$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id=rt.recurso_id');
		$sql->adCampo('recursos.recurso_quantidade AS total, tarefa_inicio, tarefa_fim, rt.recurso_quantidade');
		$sql->adOnde('date(tarefa_inicio) <= \''.$ultima_data->format($d).'\' AND date(tarefa_fim)>= \''.$primeira_data->format($d).'\'');
		$sql->adOnde('rt.recurso_id='.(int)$this->recurso_id);
		$recursos=$sql->Lista();
		$percentual_recursos_dias=array();
		$quantidade_recursos_dias=array();
		
		foreach ($recursos as $recurso) {
			$data_inicial=new CData($recurso['tarefa_inicio']);
			$data_final=new CData($recurso['tarefa_fim']);
			$soma_hora_uteis=0;
			$data=$data_inicial;
			for ($i = 0, $i_cmp = $data_inicial->dataDiferenca($data_final); $i <= $i_cmp; $i++) {
				if (isset($recurso['recurso_quantidade']) && $recurso['recurso_quantidade']) {
					if (isset($percentual_recursos_dias[$data->format(FMT_TIMESTAMP_DATA)])){
						$percentual_recursos_dias[$data->format(FMT_TIMESTAMP_DATA)]+=(int)($recurso['recurso_quantidade']/$recurso['total']*100);
						$quantidade_recursos_dias[$data->format(FMT_TIMESTAMP_DATA)]+=$recurso['recurso_quantidade'];
						}
					else {
						$percentual_recursos_dias[$data->format(FMT_TIMESTAMP_DATA)]=(int)($recurso['recurso_quantidade']/$recurso['total']*100);
						$quantidade_recursos_dias[$data->format(FMT_TIMESTAMP_DATA)]=$recurso['recurso_quantidade'];
						}
					}
				$data = $data->getNextDay();
				}
			}
		
		$data=$primeira_data;
		for ($i = 0, $i_cmp = $primeira_data->dataDiferenca($ultima_data); $i <= $i_cmp; $i++) {
			$indice=$data->format(FMT_TIMESTAMP_DATA);
			$percentual=(isset($percentual_recursos_dias[$indice]) ? (int)$percentual_recursos_dias[$indice] : '');
			$quantidade=(isset($quantidade_recursos_dias[$indice]) ? $quantidade_recursos_dias[$indice] : '');
			if ($percentual){
				$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Percentagem</b></td><td>'.$percentual.'%</td></tr>';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Quantidade</b></td><td>'.number_format((float)$quantidade, 2, ',', '.').'</td></tr>';
				$dentro .= '</table>';
				$temp =  array('alocacao' => true, 'texto_mini' => '<tr><td>'.$dentro.'</td></tr>', 'percentagem' => $percentual);
				$links[$indice][] = $temp;
				
				}
			$data = $data->getNextDay();
			}
		}	
		
	}
?>