<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

/********************************************************************************************

Classe CAgendaMes para manipular os compromissos da agenda mensal particular

gpweb\modulos\email\email.class.php

********************************************************************************************/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once ($Aplic->getClasseBiblioteca('PEAR/Date'));
require_once ($Aplic->getClasseSistema('aplic'));
require_once $Aplic->getClasseSistema('libmail');
require_once $Aplic->getClasseSistema('data');

$nome_meses=array('01'=>'Janeiro', '02'=>'Fevereiro', '03'=>'Março', '04'=>'Abril', '05'=>'Maio', '06'=>'Junho', '07'=>'Julho', '08'=>'Agosto', '09'=>'Setembro', '10'=>'Outubro', '11'=>'Novembro', '12'=>'Dezembro');

class CAgendaMes {
	var $este_mes;
	var $mesAnterior;
	var $mesProximo;
	var $anoAnterior;
	var $anoProximo;
	var $estiloTitulo;
	var $estiloPrincipal;
	var $chamarVolta;
	var $mostrarCabecalho;
	var $mostrarSetas;
	var $mostrarDias;
	var $mostrarSemana;
	var $clicarMes;
	var $mostrarCompromissos;
	var $diaFuncao;
	var $semanaFuncao;
	var $compomissoFuncao;
	var $mostrarDiasIluminados;
	var $alocacao;

	function __construct($data = null) {
		$this->setData($data);
		$this->classes = array();
		$this->chamar_volta = '';
		$this->mostrarTitulo = true;
		$this->mostrarSetas = true;
		$this->mostrarDias = true;
		$this->mostrarSemana = true;
		$this->mostrarCompromissos = true;
		$this->mostrarDiasIluminados = true;
		$this->estiloTitulo = '';
		$this->estiloPrincipal = '';
		$this->diaFuncao = '';
		$this->semanaFuncao = '';
		$this->compromissos = array();
		$this->diasIluminados = array();
		}


	function setData($data = null) {
		global $Aplic;
		$this->esteMes = new CData($data);
		$d = $this->esteMes->getDay();
		$m = $this->esteMes->getMonth();
		$y = $this->esteMes->getYear();
		$this->anoAnterior = new CData($data);
		$this->anoAnterior->setYear($this->anoAnterior->getYear() - 1);
		$this->anoProximo = new CData($data);
		$this->anoProximo->setYear($this->anoProximo->getYear() + 1);
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$data = Data_Calc::beginOfPrevMonth($d, $m, $y, FMT_TIMESTAMP_DATA);
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$this->mesAnterior = new CData($data);
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$data = Data_Calc::beginOfNextMonth($d, $m, $y, FMT_TIMESTAMP_DATA);
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$this->mesProximo = new CData($data);
		}

	function setEstilo($titulo, $principal) {
		$this->estiloTitulo = $titulo;
		$this->estiloPrincipal = $principal;
		}

	function setLinkFuncoes($dia = '', $semana = '', $compomissoFuncao='') {
		$this->diaFuncao = $dia;
		$this->semanaFuncao = $semana;
		$this->compomissoFuncao = $compomissoFuncao;
		}

function setAlocacao($sim = '') {
		$this->alocacao = $sim;
		}

	function setCallback($function) {
		$this->chamar_volta = $function;
		}

	function setCompromissos($e) {
		$this->compromissos = $e;
		}

	function setDiasIluminados($hd) {
		$this->diasIluminados = $hd;
		}

	function mostrar() {
		$s = '';
		if ($this->mostrarTitulo) $s .= $this->_desenharTitulo();
		$s .= '<table border=0 cellspacing="1" cellpadding="2" width="100%" class="'.$this->estiloPrincipal.'">';
		if ($this->mostrarDias) $s .= $this->_desenharDias();
		$s .= $this->_desenharPrincipal();
		$s .= '</table>';
		return $s;
		}


	function _desenharTitulo() {
		global $Aplic, $m, $a, $localidade_tipo_caract,$nome_meses, $estilo_interface;
		$base_dir = 'm='.$m.($a ? '&a='.$a : '').(isset($_REQUEST['dialogo']) ? '&dialogo=1' : '');
		$s = '<table border=0 cellspacing=0 cellpadding="3" width="100%" class="'.$this->estiloTitulo.'">';
		$s .= '<tr>';
		if ($this->mostrarSetas) {
			$href = $base_dir.'&data='.$this->mesAnterior->format(FMT_TIMESTAMP_DATA).($this->chamar_volta ? '&chamar_volta='.$this->chamar_volta : '').((count($this->diasIluminados) > 0) ? '&uts='.chave($this->diasIluminados) : '');
			$s .= '<td align="left"><a href="javascript:void(0);" onclick="url_passar(0, \''.$href.'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif'), 'Mês Anterior', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif')).' para exibir o mês anterior.').'</a></td>';
			}
		$s .= '<td width="99%" align="center">';
		if ($this->clicarMes) {
			setlocale(LC_TIME, $Aplic->usuario_linguagem);
			$s .= dica($nome_meses[$this->esteMes->format('%m')].' de '.$this->esteMes->format('%Y'), 'Clique para exibir este mês.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=ver_mes&data='.$this->esteMes->format(FMT_TIMESTAMP_DATA).'\');">';
			}
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$s .= '<b>'.$nome_meses[$this->esteMes->format('%m')].' '.$this->esteMes->format('%Y') .'</b>'. (($this->clicarMes) ? dicaF().'</a>' : '');
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$s .= '</td>';
		if ($this->mostrarSetas) {
			$href = ($base_dir.'&data='.$this->mesProximo->format(FMT_TIMESTAMP_DATA).(($this->chamar_volta) ? ('&chamar_volta='.$this->chamar_volta) : '').((count($this->diasIluminados) > 0) ? ('&uts='.chave($this->diasIluminados)) : ''));
			$s .= '<td align="right"><a href="javascript:void(0);" onclick="url_passar(0, \''.$href.'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif'), 'Próximo Mês', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif')).' para exibir o próximo mês.').'</a></td>';
			}
		$s .= '</tr></table>';
		return $s;
		}


	function _desenharDias() {
		global $Aplic, $localidade_tipo_caract;
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$semana = Data_Calc::getCalendarioSemana(null, null, null, '%a', (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1));
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$s = ($this->mostrarSemana ? '<td style="background-color:#f2f1f1;">&nbsp;</td>' : '');
		foreach ($semana as $dia) $s .= '<td width="14%" align="center" style="background-color:#f2f1f1;">'. dia_semana_curto($dia) .'</td>';
		return '<tr>'.$s.'</tr>';
		}

	function _desenharPrincipal() {
		global $Aplic, $diasUteis,$config, $nome_meses;
		if (!isset($diasUteis)) $diasUteis=explode (',', $config['cal_dias_uteis']);
		$hoje = new CData();
		$hoje = $hoje->format('%Y%m%d%w');
		$data = $this->esteMes;
		$este_dia = intval($data->getDay());
		$este_mes = intval($data->getMonth());
		$este_ano = intval($data->getYear());
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$cal = Data_Calc::getCalendarioMes($este_mes, $este_ano, '%Y%m%d%w', (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1));
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$df = '%d/%m/%Y';
		$html = '';

		$sql = new BDConsulta;
		$sql->adTabela('expediente');
		$sql->adCampo('formatar_data(data,\'%Y%m%d\') as feriado');
		$sql->adOnde('cia_id='.(int)$Aplic->usuario_cia);
		$sql->adOnde('diferenca_tempo(fim,inicio)=\'00:00:00\'');
		$sql->adOnde('data >=\''.$este_ano.'-'.$data->getMonth().'-01\'');
		$sql->adOnde('data <\''.$data->beginOfNextMonth().'\'');
		$feriados=$sql->Lista();
		$sql->limpar();
		$sem_expediente=array();
		foreach($feriados as $feriado) $sem_expediente[]=$feriado['feriado'];

		$sql->adTabela('expediente');
		$sql->adCampo("formatar_data(data,'%Y%m%d') as meio");
		$sql->adOnde('cia_id='.(int)$Aplic->usuario_cia);
		$sql->adOnde('diferenca_tempo(fim,inicio)=\''.horasSQL($config['horas_trab_diario']/2).'\'');
		$sql->adOnde('data >=\''.$este_ano.'-'.$data->getMonth().'-01\'');
		$sql->adOnde('data <\''.$data->beginOfNextMonth().'\'');
		$meios=$sql->Lista();
		$sql->limpar();
		$meio_expediente=array();
		foreach($meios as $meio) $meio_expediente[]=$meio['meio'];



		foreach ($cal as $semana) {
			$html .= '<tr>';
			$data = new CData(substr($semana[0],0,8));
			$titulo=$data->format('%U').'ª Semana - '.$nome_meses[$data->format('%m')]. ' de '.$data->format('%Y');
			if ($this->mostrarSemana) $html .= '<td class="semana" style="vertical-align:middle;" align="center">'.($this->diaFuncao ? dica($titulo, 'Clique neste ícone '.imagem('icones/ver.semana.gif').' para exibir esta semana.')."<a href=\"javascript:$this->semanaFuncao('".substr($semana[0],0,8)."')\">" : '').'<img src="'.acharImagem('ver.semana.gif').'" width="16" height="15" border=0 />'.($this->diaFuncao ? '</a>'.dicaF() : '').'</td>';
			foreach ($semana as $dia) {
				$este_dia = new CData($dia);
				$y = intval(substr($dia, 0, 4));
				$m = intval(substr($dia, 4, 2));
				$d = intval(substr($dia, 6, 2));
				$diadasemana = intval(substr($dia, 8, 1));
				$cdia = intval(substr($dia, 0, 8));
				$texto='';
				if (array_key_exists($cdia, $this->compromissos) && $this->estiloPrincipal == 'minical') {
					$nr_compromissos = 0;
					foreach ($this->compromissos[$cdia] as $registro) {
						++$nr_compromissos;
						$texto.=$registro['texto_mini'];
						}
					$classe = 'compromisso';
					}

				elseif ($m != $este_mes) $classe = 'vazio';
				elseif ($dia == $hoje)	$classe = 'hoje';
				elseif (in_array($cdia, $sem_expediente)) $classe = 'fim_semana';
				elseif (in_array($cdia, $meio_expediente)) $classe = 'meio_expediente';
				elseif (!in_array($diadasemana,$diasUteis)) $classe = 'fim_semana';
				else $classe = 'dia';



				$dia = substr($dia, 0, 8);
				$html .= '<td class="'.$classe.'"'.(($this->mostrarDiasIluminados && isset($this->diasIluminados[$dia])) ? ' style="border: 1px solid '.$this->diasIluminados[$dia].'"' : '').' ondblclick="'.$this->compomissoFuncao.'(\''.$dia.'\',\''.$este_dia->format($df).'\')'.'">';
				if ($m == $este_mes) {
					if ($this->diaFuncao) $html .= "<a href=\"javascript:$this->diaFuncao('$dia','".$este_dia->format($df)."')\" class=\"$classe\">".($texto ? dica('Compromissos no dia '.$d.' de '.strtolower($nome_meses[$this->esteMes->format('%m')]).' de '.$this->esteMes->format('%Y'), '<table cellspacing=0 cellpadding=0>'.$texto.'</table>').$d.dicaF() : $d).'</a>';
					else $html .= $d;
					if ($this->mostrarCompromissos) $html .= $this->_desenharCompromissos(substr($dia, 0, 8));
					}
				$html .= '</td>';
				}
			$html .= '</tr>';
			}
		return $html;
		}


	function _desenharSemana($dataObj) {
		$href = "javascript:$this->semanaFuncao(".$dataObj->getTempostamp().',\''.$dataObj->toString().'\')';
		return '<td class="semana">'.($this->diaFuncao ? '<a href="'.$href.'">' : '').dica('Semana', 'Clique neste ícone '.imagem('icones/ver.semana.gif').' para exibir esta semana.').'<img src="'.acharImagem('ver.semana.gif').'" width="16" height="15" border=0 />'.dicaF().'</a>'.($this->diaFuncao ? '</a>' : '').'</td>';
		}

	function _desenharCompromissos($dia) {
		if (!isset($this->compromissos[$dia]) || $this->estiloPrincipal == 'minical') return '';
		$compromissos = $this->compromissos[$dia];
		$s = '<br><table cellpadding=0 cellspacing=0 align="left">';
		foreach ($compromissos as $e) {
			$s .= $e['texto'];
			}
		$s.='</table>';
		return $s;
		}

	}

/********************************************************************************************

Classe CAgenda para manipular os dados da agenda particular

gpweb\modulos\email\email.class.php

********************************************************************************************/


class CAgenda extends CAplicObjeto {
	var $agenda_id = null;
	var $agenda_titulo = null;
	var $agenda_inicio = null;
	var $agenda_fim = null;
	var $agenda_descricao = null;
	var $agenda_nr_recorrencias = null;
	var $agenda_recorrencias = null;
	var $agenda_lembrar = null;
	var $agenda_dono = null;
	var $agenda_privado = null;
	var $agenda_tipo = null;
	var $agenda_notificar = null;
	var $agenda_diautil = null;
	var $agenda_acesso = null;
	var $agenda_cor = null;

	function __construct() {
		parent::__construct('agenda', 'agenda_id');
		}

	function check() {
		$this->agenda_privado = intval($this->agenda_privado);
		$this->agenda_diautil = intval($this->agenda_diautil);
		if ($this->agenda_recorrencias) {
			$data_inicio = new CData($this->agenda_inicio);
			$data_fim = new CData($this->agenda_fim);
			$hora = $data_fim->getHour();
			$minuto = $data_fim->getMinute();
			$data_fim->setData($data_inicio->getData());
			$data_fim->setHour($hora);
			$data_fim->setMinute($minuto);
			$this->agenda_fim = $data_fim->format(FMT_TIMESTAMP_MYSQL);
			}
		return null;
		}

	function excluir($oid = null) {
		global $Aplic,$config;
		$excluido = parent::excluir($this->agenda_id);
		if (empty($excluido)) {
			$q = new BDConsulta;
			$q->setExcluir('agenda_usuarios');
			$q->adOnde('agenda_id = '.(int)$this->agenda_id);
			$excluido = ((!$q->exec()) ? 'Não foi possível eliminar a relação Compromisso-'.ucfirst($config['usuario']).'.'.db_error(): '');
			$q->Limpar();
			}
		return $excluido;
		}

	static function getCompromissoRecorrenteParaPeriodo($data_inicio, $data_fim, $agenda_inicio, $agenda_fim, $agenda_recorrencias, $agenda_nr_recorrencias, $j) {
		$compromissoTransferido = array();
		$compromissoInicio = new CData($agenda_inicio);
		$compromissoFim = new CData($agenda_fim);
		if ($j > 0) {
			switch ($agenda_recorrencias) {
				case 1:
					$compromissoInicio->adIntervalo(new Data_Intervalo(3600 * $j));
					$compromissoFim->adIntervalo(new Data_Intervalo(3600 * $j));
					break;
				case 2:
					$compromissoInicio->adDias($j);
					$compromissoFim->adDias($j);
					break;
				case 3:
					$compromissoInicio->adDias(7 * $j);
					$compromissoFim->adDias(7 * $j);
					break;
				case 4:
					$compromissoInicio->adDias(14 * $j);
					$compromissoFim->adDias(14 * $j);
					break;
				case 5:
					$compromissoInicio->adMeses($j);
					$compromissoFim->adMeses($j);
					break;
				case 6:
					$compromissoInicio->adMeses(3 * $j);
					$compromissoFim->adMeses(3 * $j);
					break;
				case 7:
					$compromissoInicio->adMeses(6 * $j);
					$compromissoFim->adMeses(6 * $j);
					break;
				case 8:
					$compromissoInicio->adMeses(12 * $j);
					$compromissoFim->adMeses(12 * $j);
					break;
				default:
					break;
				}
			}

		if ($data_inicio->compare($data_inicio, $compromissoInicio) <= 0 && $data_fim->compare($data_fim, $compromissoFim) >= 0) {
			$compromissoTransferido = array($compromissoInicio, $compromissoFim);
			}
		return $compromissoTransferido;
		}

	static function getDespachoParaPeriodo($data_inicio, $data_fim) {
		global $Aplic;
		$db_inicio= $data_inicio->format(FMT_TIMESTAMP_MYSQL);
		$db_fim = $data_fim->format(FMT_TIMESTAMP_MYSQL);

		$sql = new BDConsulta;
		$sql->adTabela('msg_usuario');
		$sql->esqUnir('anotacao','anotacao','anotacao.anotacao_id=msg_usuario.anotacao_id');
		$sql->adCampo('msg_usuario.msg_usuario_id, data_limite');
		$sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
		$sql->adOnde('msg_usuario.tipo=1');
		$sql->adOnde('resposta_despacho IS NULL');
		$sql->adOnde('anotacao.anotacao_id IS NOT NULL');
		$sql->adOnde('data_limite>=\''.$db_inicio.'\' AND data_limite<=\''.$db_fim.'\'');
		$sql->adGrupo('anotacao.anotacao_id');
		$despachos = $sql->Lista();
		$sql->limpar();
		return $despachos;
		}

	static function getMsg_TarefaParaPeriodo($data_inicio, $data_fim) {
		global $Aplic;
		$db_inicio= $data_inicio->format(FMT_TIMESTAMP_MYSQL);
		$db_fim = $data_fim->format(FMT_TIMESTAMP_MYSQL);

		$sql = new BDConsulta;
		$sql->adTabela('msg_usuario');
		$sql->adCampo('msg_usuario.msg_usuario_id, tarefa_data');
		$sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
		$sql->adOnde('tarefa=1');
		$sql->adOnde('tarefa_progresso!=100');
		$sql->adOnde('tarefa_progresso!= -1');
		$sql->adOnde('ignorar_para IS NULL OR ignorar_para=0');
		$sql->adOnde('tarefa_data>=\''.$db_inicio.'\' AND tarefa_data<=\''.$db_fim.'\'');
		$tarefas = $sql->Lista();
		$sql->limpar();
		return $tarefas;
		}


	static function getDespachoModeloParaPeriodo($data_inicio, $data_fim) {
		global $Aplic;
		$db_inicio= $data_inicio->format(FMT_TIMESTAMP_MYSQL);
		$db_fim = $data_fim->format(FMT_TIMESTAMP_MYSQL);

		$sql = new BDConsulta;
		$sql->adTabela('modelo_usuario');
		$sql->esqUnir('modelo_anotacao','modelo_anotacao','modelo_anotacao.modelo_anotacao_id=modelo_usuario.modelo_anotacao_id');
		$sql->adCampo('modelo_usuario.modelo_usuario_id, data_limite');
		$sql->adOnde('modelo_usuario.para_id = '.$Aplic->usuario_id);
		$sql->adOnde('modelo_usuario.tipo=1');
		$sql->adOnde('resposta_despacho IS NULL');
		$sql->adOnde('modelo_anotacao.modelo_anotacao_id IS NOT NULL');
		$sql->adOnde('data_limite>=\''.$db_inicio.'\' AND data_limite<=\''.$db_fim.'\'');
		$sql->adGrupo('modelo_anotacao.modelo_anotacao_id');
		$despachos = $sql->Lista();
		$sql->limpar();
		return $despachos;
		}



	static function getCompromissoParaPeriodo($data_inicio, $data_fim, $filtro = 'todos', $usuario_id=0, $agenda_tipo_id=0) {
		global $Aplic;

		$db_inicio=$data_inicio->format(FMT_TIMESTAMP_MYSQL);
		$db_fim = $data_fim->format(FMT_TIMESTAMP_MYSQL);
		$consultas = array('q' => 'q', 'r' => 'r');
		foreach ($consultas as $consulta) {
			$q = new BDConsulta;
			$q->adTabela('agenda', 'e');
			$q->esqUnir('agenda_usuarios', 'agenda_usuarios', 'agenda_usuarios.agenda_id = e.agenda_id');
			$q->adCampo('DISTINCT e.agenda_id, e.agenda_acesso, e.agenda_titulo, e.agenda_inicio, e.agenda_fim, e.agenda_descricao, e.agenda_nr_recorrencias, e.agenda_recorrencias, e.agenda_lembrar, e.agenda_dono, e.agenda_privado, e.agenda_tipo, e.agenda_diautil, e.agenda_notificar, e.agenda_localizacao, e.agenda_cor');
			$q->adOrdem('e.agenda_inicio, e.agenda_fim ASC');
			if ($usuario_id) $q->adOnde('agenda_dono IN ('.$usuario_id.') OR (agenda_usuarios.usuario_id IN ('.$usuario_id.') AND agenda_usuarios.aceito=1)');
			if ($agenda_tipo_id) $q->adOnde('agenda_tipo IN ('.$agenda_tipo_id.')');

			if ($consulta == 'q') {
				$q->adOnde('(agenda_recorrencias <= 0)');
				$q->adOnde('(agenda_inicio <= \''.$db_fim.'\' AND agenda_fim >= \''.$db_inicio. '\' OR agenda_inicio BETWEEN \''.$db_inicio. '\' AND \''.$db_fim.'\')');
				$listaCompromisso = $q->Lista();
				}
			elseif ($consulta == 'r') {
				$q->adOnde('(agenda_recorrencias > 0)');
				$listaCompromissoRec = $q->Lista();
				}
			}
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$tamanhoPeriodo = Data_Calc::dateDiff($data_inicio->getDay(), $data_inicio->getMonth(), $data_inicio->getYear(), $data_fim->getDay(), $data_fim->getMonth(), $data_fim->getYear());
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		for ($i = 0, $i_cmp = sizeof($listaCompromissoRec); $i < $i_cmp; $i++) {
			for ($j = 0, $j_cmp = intval($listaCompromissoRec[$i]['agenda_nr_recorrencias']); $j <= $j_cmp; $j++) {
				if ($tamanhoPeriodo == 1) $recCompromissoData = CAgenda::getCompromissoRecorrenteParaPeriodo($data_inicio, $data_fim, $listaCompromissoRec[$i]['agenda_inicio'], $listaCompromissoRec[$i]['agenda_fim'], $listaCompromissoRec[$i]['agenda_recorrencias'], $listaCompromissoRec[$i]['agenda_nr_recorrencias'], $j);
				elseif ($tamanhoPeriodo > 1 && $listaCompromissoRec[$i]['agenda_recorrencias'] == 1 && $j == 0) {
					$recCompromissoData = CAgenda::getCompromissoRecorrenteParaPeriodo($data_inicio, $data_fim, $listaCompromissoRec[$i]['agenda_inicio'], $listaCompromissoRec[$i]['agenda_fim'], $listaCompromissoRec[$i]['agenda_recorrencias'], $listaCompromissoRec[$i]['agenda_nr_recorrencias'], $j);
					$listaCompromissoRec[$i]['agenda_titulo'] = $listaCompromissoRec[$i]['agenda_titulo']."(a cada hora)";
					}
				elseif ($tamanhoPeriodo > 1 && $listaCompromissoRec[$i]['agenda_recorrencias'] > 1) $recCompromissoData = CAgenda::getCompromissoRecorrenteParaPeriodo($data_inicio, $data_fim, $listaCompromissoRec[$i]['agenda_inicio'], $listaCompromissoRec[$i]['agenda_fim'], $listaCompromissoRec[$i]['agenda_recorrencias'], $listaCompromissoRec[$i]['agenda_nr_recorrencias'], $j);
				if (isset($recCompromissoData) && sizeof($recCompromissoData) > 0) {
					$eList[0] = $listaCompromissoRec[$i];
					$eList[0]['agenda_inicio'] = $recCompromissoData[0]->format(FMT_TIMESTAMP_MYSQL);
					$eList[0]['agenda_fim'] = $recCompromissoData[1]->format(FMT_TIMESTAMP_MYSQL);
					$listaCompromisso = array_merge($listaCompromisso, $eList);
					}
				$recCompromissoData = array();
				}
			}
		return $listaCompromisso;
		}

	function getDesignado($tipo='nao_recusou', $saida_indice=true) {
		global $config;
		$q = new BDConsulta;
		$q->adTabela('agenda_usuarios', 'ue');
		$q->esqUnir('usuarios', 'u', 'ue.usuario_id=u.usuario_id');
		$q->esqUnir('contatos', 'con','con.contato_id=usuario_contato');
		$q->adCampo('u.usuario_id');
		if ($saida_indice) $q->adCampo(''.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
		$q->adOnde('ue.agenda_id = '.(int)$this->agenda_id);
		if ($tipo=='nao_recusou') $q->adOnde('aceito != -1');
		elseif ($tipo=='recusou') $q->adOnde('aceito = -1');
		elseif ($tipo=='aceitou') $q->adOnde('aceito = 1');
		elseif ($tipo=='nao_decidiu') $q->adOnde('aceito = 0');
		$q->adOrdem('con.contato_posto_valor, con.contato_nomeguerra');
		if ($saida_indice) $designado = $q->ListaChave();
		else  $designado = $q->Lista();
		return $designado;
		}


	function atualizarDesignados($designado) {
		global $Aplic;
		$q = new BDConsulta;
		$q->setExcluir('agenda_usuarios');
		$q->adOnde('agenda_id = '.(int)$this->agenda_id);

		if (is_array($designado) && implode(',',$designado)) $q->adOnde('usuario_id NOT IN('.implode(',',$designado).')');
		$q->exec();
		$q->limpar();
		if (is_array($designado) && count($designado)) {
			foreach ($designado as $uid) {
				if ($uid) {

					//checar se já foi inserido
					$q->adTabela('agenda_usuarios');
					$q->adOnde('agenda_id = '.(int)$this->agenda_id);
					$q->adOnde('usuario_id = '.$uid);
					$q->adCampo('usuario_id');
					$ja_tem=$q->Resultado();
					$q->limpar();
					if (!$ja_tem){
						$q->adTabela('agenda_usuarios', 'ue');
						$q->adInserir('agenda_id', $this->agenda_id);
						$q->adInserir('usuario_id', $uid);
						if ($uid==$Aplic->usuario_id){
							$q->adInserir('aceito', 1);
							$q->adInserir('data', date('Y-m-d H:i:s'));
							}
						$q->exec();
						$q->limpar();
						}
					}
				}
			if ($msg = db_error()) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}
		}

	function notificar($designados, $atualizar = false, $conflito = false) {
		global $Aplic, $localidade_tipo_caract, $config;
		$email_dono = $Aplic->getPref('emailtodos');
		$lista_designados = explode(',', $designados);
		$responsavel_eh_designado = in_array($this->agenda_dono, $lista_designados);
		if ($email_dono && !$responsavel_eh_designado && $this->agenda_dono) array_push($lista_designados, $this->agenda_dono);
		foreach ($lista_designados as $chave => $x) {
			if (!$x) unset($lista_designados[$chave]);
			}
		if (!count($lista_designados)) return;
		$q = new BDConsulta;
		$q->adTabela('usuarios', 'u');
		$q->adTabela('contatos', 'con');
		$q->adCampo('usuario_id, contato_posto,contato_nomeguerra, contato_email');
		$q->adOnde('u.usuario_contato = con.contato_id');
		$q->adOnde('usuario_id in ('.implode(',', $lista_designados).')');
		$q->adOrdem('con.contato_posto_valor, con.contato_nomeguerra');
		$usuarios = $q->ListaChave('usuario_id');
		$formato_data = $Aplic->getPref('datacurta');
		$formato_hora = $Aplic->getPref('formatohora');
		$fmt = $formato_data.' '.$formato_hora;
		$data_inicio = new CData($this->agenda_inicio);
		$data_fim = new CData($this->agenda_fim);

		$tipo = ($atualizar ? "Atualizar" : "Novo");
		if ($conflito) $titulo="Solicitar Compromisso: ".$this->agenda_titulo;
		else $titulo=$tipo." Compromisso: ".$this->agenda_titulo;

		$corpo = '';
		if ($conflito) {
			$corpo .= "Você foi convidado para um compromisso de ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra."\n";
			$corpo .= "Entretanto, ou você ou outro convidado tem outro compromisso ao mesmo tempo\n";
			$corpo .= $Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra." solicitou que você reponda a esta menssagem\n";
			$corpo .= "e confirme se irá ou não fazer o requerido em tempo.\n\n";
			}
		$corpo .= "<b>Compromisso:</b>\t".$this->agenda_titulo."\n";
		if (!$conflito) $corpo .= "\t".'<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=ver_compromisso&agenda_id='.$this->agenda_id.'\');">Clique aqui para acessar</a>'."\n";
		$corpo .= "<b>Início:</b>\t".$data_inicio->format($fmt)."\n";
		$corpo .= "<b>Término:</b>\t".$data_fim->format($fmt)."\n";
		$corpo .= "<b>Tipo:</b>\t".$this->agenda_tipo."\n";
		$corpo .= "<b>".ucfirst($config['usuarios']).":</b>\t";
		$corpo_attend = '';
		foreach ($usuarios as $usuario) {
			$corpo_attend .= ((($corpo_attend) ? ', ' : '').($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']));
			}
		$corpo .= $corpo_attend."\n\n".$this->agenda_descricao."\n";

		foreach ($usuarios as $usuario) {
			$email = new Mail;
            $email->De($config['email'], $Aplic->usuario_nome);

            if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                $email->ResponderPara($Aplic->usuario_email);
                }
            else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                $email->ResponderPara($Aplic->usuario_email2);
                }

			$email->Assunto($titulo, $localidade_tipo_caract);
			$email->Corpo($corpo, $localidade_tipo_caract);
			msg_email_interno ('', $titulo, $corpo, '',$usuario['usuario_id']);
			if (!$email_dono && $usuario['usuario_id'] == $this->agenda_dono) continue;
			$email->Para($usuario['contato_email'], true);
			if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) $email->Enviar();
			}
		}

	function checarConflito($listaUsuarios = null) {
		global $Aplic, $config;
		if (!isset($listaUsuarios)) return false;
		$usuarios = explode(',', $listaUsuarios);
		$chave = array_search($Aplic->usuario_id, $usuarios);
		if (isset($chave) && $chave !== false) unset($usuarios[$chave]);
		if (!count($usuarios)) return false;
		$data_inicio = new CData($this->agenda_inicio);
		$data_fim = new CData($this->agenda_fim);
		$q = new BDConsulta;
		$q->adTabela('agenda', 'e');
		$q->adCampo('e.agenda_dono, ue.usuario_id, e.agenda_diautil, e.agenda_id, e.agenda_inicio, e.agenda_fim');
		$q->adUnir('agenda_usuarios', 'ue', 'ue.agenda_id = e.agenda_id');
		$q->adOnde('agenda_inicio <= \''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\'');
		$q->adOnde('agenda_fim >= \''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\'');
		$q->adOnde('e.agenda_dono IN ('.implode(',', $usuarios).') OR (ue.usuario_id IN ('.implode(',', $usuarios).') AND ue.aceito != -1)');
		$q->adOnde('e.agenda_id <>'.(int)$this->agenda_id);
		$lista = $q->lista();
		$conflitos = array();
		foreach ($lista as $linha) {
			array_push($conflitos, $linha['agenda_dono']);
			if ($linha['usuario_id']) array_push($conflitos, $linha['usuario_id']);
			}
		$conflito = array_unique($conflitos);
		$q->limpar();
		if (count($conflito)) {
			$q->adTabela('usuarios', 'u');
			$q->adTabela('contatos', 'con');
			$q->adCampo('usuario_id');
			$q->adCampo(''.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
			$q->adOnde('usuario_id IN ('.implode(',', $conflito).')');
			$q->adOnde('usuario_contato = contato_id');
			return $q->ListaChave();
			}
		else return false;
		}

	function getCompromissosNaJanela($data_inicio, $data_fim, $inicio_hora, $fim_hora, $usuarios = null) {
		global $Aplic;
		if (!isset($usuarios)) return false;
		if (!count($usuarios)) return false;
		$q = new BDConsulta;
		$q->adTabela('agenda', 'e');
		$q->adCampo('e.agenda_dono, ue.usuario_id, e.agenda_diautil, e.agenda_id, e.agenda_inicio, e.agenda_fim');
		$q->adUnir('agenda_usuarios', 'ue', 'ue.agenda_id = e.agenda_id');
		$q->adOnde('agenda_inicio >= \''.$data_inicio.'\'	AND agenda_fim <= \''.$data_fim.'\'	AND extrair(\'HOUR_MINUTE\', e.agenda_fim) >= \''.$inicio_hora.'\'	AND extrair(\'HOUR_MINUTE\', e.agenda_inicio) <= \''.$fim_hora.'\' AND ( e.agenda_dono in ('.implode(',', $usuarios).')	OR ue.usuario_id in ('.implode(',',$usuarios).') )');
		$resultado = $q->exec();
		if (!$resultado) return false;
		$listaCompromissos = array();
		while ($linha = $q->carregarLinha()) $listaCompromissos[] = $linha;
		$q->limpar();
		return $listaCompromissos;
		}

	function podeAcessar() {
		$valor=true;
		return $valor;
		}

	function podeEditar($agenda_id=0) {
		$valor=permiteEditarCompromisso(($agenda_id ? $agenda_id : $this->agenda_id));
		return $valor;
		}

	function podeExcluir(&$msg='', $agenda_id = null, $unioes = null) {
		$valor=permiteExcluirCompromisso(($agenda_id ? $agenda_id : $this->agenda_id));
		return $valor;
		}

function adLembrete() {
		if (!$this->agenda_inicio||($this->agenda_lembrar <= 0)) {
			return $this->limparLembrete();
			}
		$eq = new EventoFila;
		$args = null;
		$lembretes_antigos = $eq->procurar('email', 'agenda', $this->agenda_id);
		if (count($lembretes_antigos)) {
			foreach ($lembretes_antigos as $antigo_id => $data_antiga) $eq->remover($antigo_id);
			}
		$data = new CData($this->agenda_inicio);
		$hoje = new CData(date('Y-m-d'));
		if ($data->compare($data, $hoje) < 0) $inicio_dia = time();
		else {
			$inicio_dia = $data->getData(DATE_FORMAT_UNIXTIME);
			}
		$eq->adicionar(array($this, 'lembrar'), $args, 'email', false, $this->agenda_id, 'agenda', ($inicio_dia-$this->agenda_lembrar));
		}

	function lembrar($modulo, $tipo, $id, $responsavel, &$args) {
		global $localidade_tipo_caract, $Aplic, $config;
		$q = new BDConsulta;
	  $sem_email_interno=0;
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		if (!$this->load($id)) return - 1;
		$this->htmlDecodificar();
		$hoje = new CData();
		$q->adTabela('agenda','e');
		$q->esqUnir('agenda_usuarios', 'eu', 'eu.agenda_id = e.agenda_id');
		$q->esqUnir('usuarios', 'u', 'u.usuario_id = eu.usuario_id');
		$q->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
		$q->adCampo('c.contato_id, contato_posto, contato_nomeguerra, contato_email, u.usuario_id');
		$q->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$q->adOnde('e.agenda_id = '.(int)$id);
		$contatos = $q->ListaChave('contato_id');
		$q->limpar();
		$responsavel_naoeh_designado = false;
		$q->adTabela('usuarios', 'u');
		$q->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
		$q->adCampo('c.contato_id, contato_posto, contato_nomeguerra, contato_email');
		$q->adOnde('u.usuario_id = '.(int)$this->agenda_dono);
		if ($q->exec(ADODB_FETCH_NUM)) {
			list($responsavel_contato, $responsavel_posto, $responsavel_nomeguerra, $responsavel_email) = $q->carregarLinha();
			if (!isset($contatos[$responsavel_contato])) {
				$responsavel_naoeh_designado = true;
				$contatos[$responsavel_contato] = array('contato_id' => $responsavel_contato, 'contato_posto' => $responsavel_posto, 'contato_nomeguerra' => $responsavel_nomeguerra, 'contato_email' => $responsavel_email);
				}
			}
		$q->limpar();
		$agora = new CData();
		$data = new CData($this->agenda_inicio);
		$assunto = '<b>Lembrete: </b>'.$this->agenda_titulo;
		$corpo='<b>Evento:</b> '.$this->agenda_titulo.'<br>';
		if ($this->agenda_inicio) $corpo.='<b>Data de Início:</b> '.retorna_data($this->agenda_inicio, true).'<br>';
		if ($this->agenda_fim) $corpo.='<b>Data de Término:</b> '.retorna_data($this->agenda_fim, true).'<br>';
		$corpo.='<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=ver_dia&data='.$data->format(FMT_TIMESTAMP_DATA).'&tab=0\');">Clique aqui para visualizar o compromisso</a><br><br>';

		$designados='';
		foreach ($contatos as $contato) {
			$designados.= $contato['contato_posto'].' '.$contato['contato_nomeguerra'].($contato['contato_email'] ? ' <'.$contato['contato_email'].'>' : '').'<br>';
			}
		if (count($contatos)>1) $corpo.='<b>Participante'.(count($contatos) > 1 ? 's':'').':</b><br>'.$designados;
		if ($this->agenda_descricao) $corpo .= '<br><b>Descrição:</b><br>'.$this->agenda_descricao.'<br>';
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
			$retorno_interno=msg_email_interno ('', $assunto, $corpo,'',$contato['usuario_id']);
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

	function limparLembrete() {
		$ev = new EventoFila;
		$agenda_lista = $ev->procurar('email', 'agenda', $this->agenda_id);
		if (count($agenda_lista)) {
			foreach ($agenda_lista as $id => $data) $ev->remover($id);
			}
		}


	}

$agenda_filtro_lista = array('meu' => 'Meus compromissos', 'dono' => 'Compromissos que eu criei', 'todos' => 'Todos os compromissos');
?>