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

$nome_meses=array('01'=>'Janeiro', '02'=>'Fevereiro', '03'=>'Março', '04'=>'Abril', '05'=>'Maio', '06'=>'Junho', '07'=>'Julho', '08'=>'Agosto', '09'=>'Setembro', '10'=>'Outubro', '11'=>'Novembro', '12'=>'Dezembro');

require_once ($Aplic->getClasseBiblioteca('PEAR/Date'));
require_once ($Aplic->getClasseSistema('aplic'));
require_once $Aplic->getClasseSistema('libmail');
require_once $Aplic->getClasseSistema('data');
require_once ($Aplic->getClasseSistema('evento_recorrencia'));

class CCalendarioMes {
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
	var $mostrarEventos;
	var $diaFuncao;
	var $semanaFuncao;
	var $eventoFuncao;
	var $mostrarDiasIluminados;
	var $expediente;
	var $alocacao;

	function __construct($data = null) {
		$this->setData($data);
		$this->classes = array();
		$this->chamar_volta = '';
		$this->mostrarTitulo = true;
		$this->mostrarSetas = true;
		$this->mostrarDias = true;
		$this->mostrarSemana = true;
		$this->mostrarEventos = true;
		$this->mostrarDiasIluminados = true;
		$this->expediente = false;
		$this->estiloTitulo = '';
		$this->estiloPrincipal = '';
		$this->diaFuncao = '';
		$this->semanaFuncao = '';
		$this->eventos = array();
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

	function setLinkFuncoes($dia = '', $semana = '', $eventoFuncao='') {
		$this->diaFuncao = $dia;
		$this->semanaFuncao = $semana;
		$this->eventoFuncao = $eventoFuncao;
		}

function setExpediente($sim = '') {
		$this->expediente = $sim;
		}

function setAlocacao($sim = '') {
		$this->alocacao = $sim;
		}

	function setCallback($function) {
		$this->chamar_volta = $function;
		}

	function setEventos($e) {
		$this->eventos = $e;
		}

	function setDiasIluminados($hd) {
		$this->diasIluminados = $hd;
		}

	function mostrar() {
		$s = '';
		if ($this->mostrarTitulo) $s .= $this->_desenharTitulo();
		$s .= '<table border=0 cellspacing=1 cellpadding=2 width="100%" class="'.$this->estiloPrincipal.'">';
		if ($this->mostrarDias) $s .= $this->_desenharDias();
		$s .= $this->_desenharPrincipal();
		$s .= '</table>';
		return $s;
		}


	function _desenharTitulo() {
		global $Aplic, $m, $a, $localidade_tipo_caract, $estilo_interface;
		$nome_meses=array('01'=>'Janeiro', '02'=>'Fevereiro', '03'=>'Março', '04'=>'Abril', '05'=>'Maio', '06'=>'Junho', '07'=>'Julho', '08'=>'Agosto', '09'=>'Setembro', '10'=>'Outubro', '11'=>'Novembro', '12'=>'Dezembro');
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
			$s .= dica($nome_meses[$this->esteMes->format('%m')].' de '.$this->esteMes->format('%Y'), 'Clique para exibir este mês.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&data='.$this->esteMes->format(FMT_TIMESTAMP_DATA).'\');">';
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
		global $Aplic, $localidade_tipo_caract, $config;
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$semana = Data_Calc::getCalendarioSemana(null, null, null, '%a', (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1));
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$s = ($this->mostrarSemana ? '<td style="background-color:#f2f1f1;">&nbsp;</td>' : '');
		foreach ($semana as $dia) $s .= '<td width="14%" align="center" style="background-color:#f2f1f1;">'. dia_semana_curto($dia) .'</td>';
		return '<tr>'.$s.'</tr>';
		}

	function _desenharPrincipal() {
		global $Aplic, $diasUteis,$config, $nome_meses, $cia_id;
		if (!$cia_id)$cia_id=$Aplic->getEstado('cia_id', $Aplic->usuario_cia);
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
		$sql->adOnde('cia_id='.(int)$cia_id);
		$sql->adOnde('diferenca_tempo(fim,inicio)=\'00:00:00\'');
		$sql->adOnde('data >=\''.$este_ano.'-'.$data->getMonth().'-01\'');
		$sql->adOnde('data <\''.$data->beginOfNextMonth().'\'');
		$feriados=$sql->Lista();
		$sql->limpar();
		$sem_expediente=array();
		foreach($feriados as $feriado) $sem_expediente[]=$feriado['feriado'];

		$sql->adTabela('expediente');
		$sql->adCampo("formatar_data(data,'%Y%m%d') as meio");
		$sql->adOnde('cia_id='.(int)$cia_id);
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

				if (array_key_exists($cdia, $this->eventos) && $this->estiloPrincipal == 'minical') {
					$nr_tarefas = 0;
					$nr_eventos = 0;
					$nr_acoes = 0;
					$nr_atas = 0;
					$nr_problemas = 0;
					$nr_expedientes=0;
					$nr_sobrecarga=0;
					$nr_alocacao=0;
					$horas=0;
					$sobrecarga=0;

					//ver($this->eventos);

					foreach ($this->eventos[$cdia] as $registro) {
						if (isset($registro['tarefa']) && $registro['tarefa']) ++$nr_tarefas;
						elseif (isset($registro['acao']) && $registro['acao']) ++$nr_acoes;
						elseif (isset($registro['ata']) && $registro['ata']) ++$nr_atas;
						elseif (isset($registro['problema']) && $registro['problema']) ++$nr_problemas;
						elseif (isset($registro['alocacao']) && $registro['alocacao']) ++$nr_alocacao;
						elseif (isset($registro['expediente']) && $registro['expediente']) {
							++$nr_expedientes;
							$horas+=$registro['horas'];
							}
						elseif (isset($registro['sobrecarga']) && $registro['sobrecarga']) {
							++$nr_sobrecarga;
							$sobrecarga+=$registro['percentagem'];
							}
						else ++$nr_eventos;
						$texto.=$registro['texto_mini'];
						}
					if ($dia == $hoje) $classe = 'hoje';


					$qnt_multiplo=0;
					//checar se multiplo
					if ($nr_eventos) $qnt_multiplo++;
					if ($nr_tarefas) $qnt_multiplo++;
					if ($nr_acoes) $qnt_multiplo++;
					if ($nr_atas) $qnt_multiplo++;
					if ($nr_problemas) $qnt_multiplo++;

					if ($qnt_multiplo > 1) {
						$classe = 'multiplo';
						$titulo='Múltiplos Objetos';
						}
					elseif ($nr_acoes) {
						$classe = 'acao';
						$titulo='Planos de Ação';
						}
					elseif ($nr_atas) {
						$classe = 'ata';
						$titulo='Atas de Reunião';
						}	
					elseif ($nr_problemas) {
						$classe = 'problema';
						$titulo=ucfirst($config['problemas']);
						}		
					elseif ($nr_tarefas) {
						$classe = 'tarefa';
						$titulo=ucfirst($config['tarefas']);
						}
					elseif ($nr_eventos) {
						$classe = 'evento';
						$titulo='Eventos';
						}

					elseif ($nr_expedientes) {
						$integral=($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
						if ($integral==$registro['horas']) $classe = 'expediente_normal';
						elseif ((($integral/2)<=$registro['horas']) && ($registro['horas']<=($integral*0.75))) $classe = 'expediente_meio';
						elseif ($registro['horas']< 0.1) $classe = 'expediente_sem';
						else $classe = 'expediente_outros';
						}
					elseif ($nr_sobrecarga) {
						if ($registro['percentagem'] > 0 && $registro['percentagem'] < 25) $classe = 'sobrecarga_25';
						elseif ($registro['percentagem'] >= 25 && $registro['percentagem'] < 50) $classe = 'sobrecarga_50';
						elseif ($registro['percentagem'] >= 50 && $registro['percentagem'] < 75) $classe = 'sobrecarga_75';
						elseif ($registro['percentagem'] >= 75 && $registro['percentagem'] < 95) $classe = 'sobrecarga_95';
						elseif ($registro['percentagem'] >= 95 && $registro['percentagem'] <= 100) $classe = 'sobrecarga_100';
						elseif ($registro['percentagem'] > 100) $classe = 'sobrecarga_acima100';
						else $classe = '';
						}
					elseif ($nr_alocacao) {
						if ($registro['percentagem'] > 0 && $registro['percentagem'] < 25) $classe = 'alocacao_25';
						elseif ($registro['percentagem'] >= 25 && $registro['percentagem'] < 50) $classe = 'alocacao_50';
						elseif ($registro['percentagem'] >= 50 && $registro['percentagem'] < 75) $classe = 'alocacao_75';
						elseif ($registro['percentagem'] >= 75 && $registro['percentagem'] < 95) $classe = 'alocacao_95';
						elseif ($registro['percentagem'] >= 95 && $registro['percentagem'] <= 100) $classe = 'alocacao_100';
						elseif ($registro['percentagem'] > 100) $classe = 'alocacao_acima100';
						else $classe = '';
						}
					}
				elseif ($m != $este_mes) $classe = 'vazio';
				elseif ($dia == $hoje)	$classe = 'hoje';
				elseif (in_array($cdia, $sem_expediente)) $classe = 'fim_semana';
				elseif (in_array($cdia, $meio_expediente)) $classe = 'meio_expediente';
				elseif (!in_array($diadasemana,$diasUteis)) $classe = 'fim_semana';
				else $classe = 'dia';
				

				
				$dia = substr($dia, 0, 8);
				$html .= '<td class="'.$classe.'"'.(($this->mostrarDiasIluminados && isset($this->diasIluminados[$dia])) ? ' style="border: 1px solid '.$this->diasIluminados[$dia].'"' : '').' ondblclick="'.$this->eventoFuncao.'(\''.$dia.'\',\''.$este_dia->format($df).'\')'.'">';
				if ($m == $este_mes) {
					if ($this->expediente) $html .= ($texto ? dica('Expediente no dia '.$d.' de '.strtolower($nome_meses[$this->esteMes->format('%m')]).' de '.$this->esteMes->format('%Y'), '<table cellspacing=0 cellpadding=0>'.$texto.'</table>').$d.dicaF() : $d);
					elseif ($this->alocacao) $html .= ($texto ? dica('Alocação do recurso no dia '.$d.' de '.strtolower($nome_meses[$this->esteMes->format('%m')]).' de '.$this->esteMes->format('%Y'), '<table cellspacing=0 cellpadding=0>'.$texto.'</table>').$d.dicaF() : $d);
					elseif ($this->diaFuncao) $html .= "<a href=\"javascript:$this->diaFuncao('$dia','".$este_dia->format($df)."')\" class=\"$classe\">".($texto ? dica($titulo.' no dia '.$d.' de '.strtolower($nome_meses[$this->esteMes->format('%m')]).' de '.$this->esteMes->format('%Y'), '<table cellspacing=0 cellpadding=0>'.$texto.'</table>').$d.dicaF() : $d).'</a>';
					else $html .= $d;
					if ($this->mostrarEventos) $html .= $this->_desenharEventos(substr($dia, 0, 8));
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

	function _desenharEventos($dia) {
		if (!isset($this->eventos[$dia]) || $this->estiloPrincipal == 'minical') return '';
		$eventos = $this->eventos[$dia];
		$s = '<br><table cellpadding=0 cellspacing=0 align="left">';
		foreach ($eventos as $e) $s .= $e['texto'];
		$s.='</table>';
		return $s;
		}



	}
/********************************************************************************************

Classe CEvento para manipulação dos eventos.

********************************************************************************************/
class CEvento extends CAplicObjeto {
	var $evento_id = null;
	var $evento_cia = null;
	var $evento_dept = null;
	var $evento_titulo = null;
	var $evento_inicio = null;
	var $evento_fim = null;
	var $evento_superior = null;
	var $evento_descricao = null;
	var $evento_oque = null;
	var $evento_onde = null;
	var $evento_quando = null;
	var $evento_como = null;
	var $evento_porque = null;
	var $evento_quanto = null;
	var $evento_quem = null;
	var $evento_nr_recorrencias = null;
	var $evento_recorrencias = null;
	var $evento_lembrar = null;
	var $evento_icone = null;
	var $evento_dono = null;
	var $evento_projeto = null;
	var $evento_tarefa = null;
	var $evento_tipo = null;
	var $evento_notificar = null;
	var $evento_diautil = null;
	var $evento_acesso = null;
	var $evento_cor = null;
	var $evento_pratica = null;
	var $evento_acao = null;
	var $evento_indicador = null;
	var $evento_perspectiva = null;
	var $evento_tema = null;
	var $evento_objetivo = null;
	var $evento_estrategia = null;
	var $evento_calendario = null;
	var $evento_fator = null;
	var $evento_meta = null;
	var $evento_canvas = null;
	var $evento_recorrencia_pai = null;
	var $evento_principal_indicador = null;

	function __construct() {
		parent::__construct('eventos', 'evento_id');
		}

	function check() {
		$this->evento_tipo = intval($this->evento_tipo);
		$this->evento_diautil = intval($this->evento_diautil);
		return null;
		}

	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$this->arrumarTodos();
		$msg = $this->check();
		if ($msg)	return get_class($this).'::checagem para armazenar falhou - '.$msg;

		$sql = new BDConsulta;
		if ($this->evento_id) {
			$ret = $sql->atualizarObjeto('eventos', $this, 'evento_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('eventos', $this, 'evento_id');
			$sql->limpar();
			}

		if ($Aplic->profissional && isset($_REQUEST['uuid']) && $_REQUEST['uuid']){
			$sql->adTabela('evento_gestao');
			$sql->adAtualizar('evento_gestao_evento', (int)$this->evento_id);
			$sql->adAtualizar('evento_gestao_uuid', null);
			$sql->adOnde('evento_gestao_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			}


		$depts_selecionados=getParam($_REQUEST, 'evento_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('evento_depts');
		$sql->adOnde('evento_id = '.$this->evento_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('evento_depts');
				$sql->adInserir('evento_id', $this->evento_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('evento_cia');
			$sql->adOnde('evento_cia_evento='.(int)$this->evento_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'evento_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('evento_cia');
						$sql->adInserir('evento_cia_evento', $this->evento_id);
						$sql->adInserir('evento_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}
			
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('evento', $this->evento_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->evento_id);	

		}

	function getTarefaNome() {
		$tarefaNome = '';
		if (!$this->evento_tarefa)	return $tarefaNome;
		$this->_consulta->limpar();
		$this->_consulta->adTabela('tarefas');
		$this->_consulta->adCampo('tarefa_nome');
		$this->_consulta->adOnde('tarefa_id = '.(int)$this->evento_tarefa);
		$tarefaNome =$this->_consulta->Resultado();
		$this->_consulta->limpar();
		return $tarefaNome;
		}

	function getAcaoNome() {
		$acaoNome = '';
		if (!$this->evento_acao)	return $acaoNome;
		$this->_consulta->limpar();
		$this->_consulta->adTabela('plano_acao');
		$this->_consulta->adCampo('plano_acao_nome');
		$this->_consulta->adOnde('plano_acao_id = '.(int)$this->evento_acao);
		$acaoNome =$this->_consulta->Resultado();
		$this->_consulta->limpar();
		return $acaoNome;
		}


	function excluir($oid = NULL) {
		global $Aplic,$config;
		$excluido = parent::excluir($this->evento_id);
		if (empty($excluido)) {
			$sql = new BDConsulta;
			
			$sql->setExcluir('evento_recorrencia');
			$sql->adOnde('recorrencia_id_origem = '.$this->evento_id);
			$sql->adOnde('recorrencia_modulo = \'calendario\'');
			$excluido = ((!$sql->exec()) ? 'Não foi possível eliminar da tabela evento_recorrencia.'.db_error():null);
			$sql->Limpar();
			}
		if ($Aplic->getEstado('evento_id', null)==$this->evento_id) $Aplic->setEstado('evento_id', null);	
		return $excluido;
		}

	static function getEventoParaPeriodo($data_inicio, $data_fim, $evento_filtro='', $usuario_id=null, $cia_id=null, $dept_id=null,
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
		$me_id=null
		) {
		global $Aplic;

		$db_inicio=($data_inicio ? $data_inicio->format('%Y-%m-%d %H:%M:%S') : null);
		$db_fim=($data_fim ? $data_fim->format('%Y-%m-%d %H:%M:%S') : null);

		$nada_selecionado=!(
			$tarefa_id ||
			$projeto_id ||
			$pg_perspectiva_id ||
			$tema_id ||
			$pg_objetivo_estrategico_id ||
			$pg_fator_critico_id ||
			$pg_estrategia_id ||
			$pg_meta_id ||
			$pratica_id ||
			$pratica_indicador_id ||
			$plano_acao_id ||
			$canvas_id ||
			$risco_id ||
			$risco_resposta_id ||
			$calendario_id ||
			$monitoramento_id ||
			$ata_id ||
			$swot_id ||
			$operativo_id ||
			$instrumento_id ||
			$recurso_id ||
			$problema_id ||
			$demanda_id ||
			$programa_id ||
			$licao_id ||
			$link_id ||
			$avaliacao_id ||
			$tgn_id ||
			$brainstorm_id ||
			$gut_id ||
			$causa_efeito_id ||
			$arquivo_id ||
			$forum_id ||
			$checklist_id ||
			$agenda_id ||
			$agrupamento_id ||
			$patrocinador_id ||
			$template_id ||
			$painel_id ||
			$painel_odometro_id ||
			$painel_composicao_id ||
			$tr_id ||
			$me_id
			);


		$sql = new BDConsulta;
		$sql->adTabela('eventos', 'e');
		if ($Aplic->profissional){
			$sql->esqUnir('evento_gestao', 'evento_gestao', 'e.evento_id = evento_gestao_evento');
			if ($tarefa_id) $sql->adOnde('evento_gestao_tarefa='.(int)$tarefa_id);
			elseif ($projeto_id) $sql->adOnde('evento_gestao_projeto='.(int)$projeto_id);
			elseif ($pg_perspectiva_id) $sql->adOnde('evento_gestao_perspectiva='.(int)$pg_perspectiva_id);
			elseif ($tema_id) $sql->adOnde('evento_gestao_tema='.(int)$tema_id);
			elseif ($pg_objetivo_estrategico_id) $sql->adOnde('evento_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
			elseif ($pg_fator_critico_id) $sql->adOnde('evento_gestao_fator='.(int)$pg_fator_critico_id);
			elseif ($pg_estrategia_id) $sql->adOnde('evento_gestao_estrategia='.(int)$pg_estrategia_id);
			elseif ($pg_meta_id) $sql->adOnde('evento_gestao_meta='.(int)$pg_meta_id);
			elseif ($pratica_id) $sql->adOnde('evento_gestao_pratica='.(int)$pratica_id);
			elseif ($pratica_indicador_id) $sql->adOnde('evento_gestao_indicador='.(int)$pratica_indicador_id);
			elseif ($plano_acao_id) $sql->adOnde('evento_gestao_acao='.(int)$plano_acao_id);
			elseif ($canvas_id) $sql->adOnde('evento_gestao_canvas='.(int)$canvas_id);
			elseif ($risco_id) $sql->adOnde('evento_gestao_risco='.(int)$risco_id);
			elseif ($risco_resposta_id) $sql->adOnde('evento_gestao_risco_resposta='.(int)$risco_resposta_id);
			elseif ($calendario_id) $sql->adOnde('evento_gestao_calendario='.(int)$calendario_id);
			elseif ($monitoramento_id) $sql->adOnde('evento_gestao_monitoramento='.(int)$monitoramento_id);
			elseif ($ata_id) $sql->adOnde('evento_gestao_ata='.(int)$ata_id);
			elseif ($swot_id) $sql->adOnde('evento_gestao_swot='.(int)$swot_id);
			elseif ($operativo_id) $sql->adOnde('evento_gestao_operativo='.(int)$operativo_id);
			elseif ($instrumento_id) $sql->adOnde('evento_gestao_instrumento='.(int)$instrumento_id);
			elseif ($recurso_id) $sql->adOnde('evento_gestao_recurso='.(int)$recurso_id);
			elseif ($problema_id) $sql->adOnde('evento_gestao_problema='.(int)$problema_id);
			elseif ($demanda_id) $sql->adOnde('evento_gestao_demanda='.(int)$demanda_id);
			elseif ($programa_id) $sql->adOnde('evento_gestao_programa='.(int)$programa_id);
			elseif ($licao_id) $sql->adOnde('evento_gestao_licao='.(int)$licao_id);
			elseif ($link_id) $sql->adOnde('evento_gestao_link='.(int)$link_id);
			elseif ($avaliacao_id) $sql->adOnde('evento_gestao_avaliacao='.(int)$avaliacao_id);
			elseif ($tgn_id) $sql->adOnde('evento_gestao_tgn='.(int)$tgn_id);
			elseif ($brainstorm_id) $sql->adOnde('evento_gestao_brainstorm='.(int)$brainstorm_id);
			elseif ($gut_id) $sql->adOnde('evento_gestao_gut='.(int)$gut_id);
			elseif ($causa_efeito_id) $sql->adOnde('evento_gestao_causa_efeito='.(int)$causa_efeito_id);
			elseif ($arquivo_id) $sql->adOnde('evento_gestao_arquivo='.(int)$arquivo_id);
			elseif ($forum_id) $sql->adOnde('evento_gestao_forum='.(int)$forum_id);
			elseif ($checklist_id) $sql->adOnde('evento_gestao_checklist='.(int)$checklist_id);
			elseif ($agenda_id) $sql->adOnde('evento_gestao_agenda='.(int)$agenda_id);
			elseif ($agrupamento_id) $sql->adOnde('evento_gestao_agrupamento='.(int)$agrupamento_id);
			elseif ($patrocinador_id) $sql->adOnde('evento_gestao_patrocinador='.(int)$patrocinador_id);
			elseif ($template_id) $sql->adOnde('evento_gestao_template='.(int)$template_id);
			elseif ($painel_id) $sql->adOnde('evento_gestao_painel='.(int)$painel_id);
			elseif ($painel_odometro_id) $sql->adOnde('evento_gestao_painel_odometro='.(int)$painel_odometro_id);
			elseif ($painel_composicao_id) $sql->adOnde('evento_gestao_painel_composicao='.(int)$painel_composicao_id);
			elseif ($tr_id) $sql->adOnde('evento_gestao_tr='.(int)$tr_id);
			elseif ($me_id) $sql->adOnde('evento_gestao_me='.(int)$me_id);
			}
		else {
			if ($tarefa_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('tarefas', 't', 't.tarefa_id =  e.evento_tarefa');
			if ($projeto_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('projetos', 'p', 'p.projeto_id =  e.evento_projeto');
			if ($pratica_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id =  e.evento_pratica');
			if ($pratica_indicador_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador.pratica_indicador_id =  e.evento_indicador');
			if ($tema_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('tema', 'tema', 'tema_id = e.evento_tema');
			if ($pg_objetivo_estrategico_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'pg_objetivo_estrategico_id = e.evento_objetivo');
			if ($pg_estrategia_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id = e.evento_estrategia');
			if ($pg_fator_critico_id && ($dept_id && $nada_selecionado)) $sql->esqUnir('fatores_criticos', 'fatores_criticos', 'pg_fator_critico_id = e.evento_fator');
			if ($pg_meta_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('metas', 'metas', 'pg_meta_id = e.evento_meta');
			if ($pg_perspectiva_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('perspectivas', 'perspectivas', 'pg_perspectiva_id = e.evento_perspectiva');
			if ($canvas_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('canvas', 'canvas', 'canvas_id = e.evento_canvas');
			if ($plano_acao_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id =  e.evento_acao');
			if ($calendario_id || ($dept_id && $nada_selecionado)) $sql->esqUnir('calendario', 'calendario', 'calendario.calendario_id =  e.evento_calendario');

			if ($dept_id && $nada_selecionado){
				$sql->esqUnir('tarefa_depts', 'tarefa_depts', 'evento_tarefa = tarefa_depts.tarefa_id');
				$sql->esqUnir('projeto_depts', 'projeto_depts', 'evento_projeto = projeto_depts.projeto_id');
				$sql->esqUnir('pratica_depts', 'pratica_depts', 'evento_pratica = pratica_depts.pratica_id');
				$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id = evento_indicador');
				$sql->esqUnir('objetivos_estrategicos_depts', 'objetivos_estrategicos_depts', 'objetivos_estrategicos_depts.pg_objetivo_estrategico_id = evento_objetivo');
				$sql->esqUnir('fatores_criticos_depts', 'fatores_criticos_depts', 'fatores_criticos_depts.pg_fator_critico_id = evento_fator');
				$sql->esqUnir('metas_depts', 'metas_depts', 'metas_depts.pg_meta_id = evento_meta');
				$sql->esqUnir('perspectivas_depts', 'perspectivas_depts', 'perspectivas_depts.pg_perspectiva_id = evento_perspectiva');
				$sql->esqUnir('canvas_dept', 'canvas_dept', 'canvas_dept_canvas = canvas_id');
				$sql->adOnde('tarefa_depts.departamento_id IN ('.$dept_id.')
					OR projeto_depts.departamento_id IN ('.$dept_id.')
					OR pratica_depts.dept_id IN ('.$dept_id.')
					OR pratica_indicador_depts.dept_id IN ('.$dept_id.')
					OR objetivos_estrategicos_depts.dept_id IN ('.$dept_id.')
					OR perspectivas_depts.dept_id IN ('.$dept_id.')
					OR canvas_dept_dept IN ('.$dept_id.')
					OR metas_depts.dept_id IN ('.$dept_id.')');
				}

			if($dept_id && $tarefa_id){
				$sql->esqUnir('tarefa_depts', 'tarefa_depts', 't.tarefa_id = tarefa_depts.tarefa_id');
				$sql->adOnde('tarefa_depts.departamento_id IN ('.$dept_id.')');
				}
			elseif($dept_id && $projeto_id){
				$sql->esqUnir('projeto_depts', 'projeto_depts', 'p.projeto_id = projeto_depts.projeto_id');
				$sql->adOnde('projeto_depts.departamento_id IN ('.$dept_id.')');
				}
			elseif($dept_id && $pratica_id){
				$sql->esqUnir('pratica_depts', 'pratica_depts', 'praticas.pratica_id = pratica_depts.pratica_id');
				$sql->adOnde('pratica_depts.dept_id IN ('.$dept_id.')');
				}
			elseif($dept_id && $pratica_indicador_id){
				$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id = pratica_indicador.pratica_indicador_id');
				$sql->adOnde('pratica_indicador_depts.dept_id IN ('.$dept_id.')');
				}

			elseif($dept_id && $pg_objetivo_estrategico_id){
				$sql->esqUnir('objetivos_estrategicos_depts', 'objetivos_estrategicos_depts', 'objetivos_estrategicos_depts.pg_objetivo_estrategico_id = objetivos_estrategicos.pg_objetivo_estrategico_id');
				$sql->adOnde('objetivos_estrategicos_depts.dept_id IN ('.$dept_id.')');
				}

			elseif($dept_id && $pg_fator_critico_id){
				$sql->esqUnir('fatores_criticos_depts', 'fatores_criticos_depts', 'fatores_criticos_depts.pg_fator_critico_id = fatores_criticos.pg_fator_critico_id');
				$sql->adOnde('fatores_criticos_depts.dept_id IN ('.$dept_id.')');
				}

			elseif($dept_id && $pg_meta_id){
				$sql->esqUnir('metas_depts', 'metas_depts', 'metas_depts.pg_meta_id = metas.pg_meta_id');
				$sql->adOnde('metas_depts.dept_id IN ('.$dept_id.')');
				}

			elseif($dept_id && $pg_perspectiva_id){
				$sql->esqUnir('perspectivas_depts', 'perspectivas_depts', 'perspectivas_depts.pg_perspectiva_id = perspectivas.pg_perspectiva_id');
				$sql->adOnde('perspectivas_depts.dept_id IN ('.$dept_id.')');
				}

			elseif($dept_id && $canvas_id){
				$sql->esqUnir('canvas_dept', 'canvas_dept', 'canvas_dept_canvas = canvas_id');
				$sql->adOnde('canvas_dept_deptIN ('.$dept_id.')');
				}

			if ($tarefa_id)	$sql->adOnde('e.evento_tarefa ='.(int)$tarefa_id);

			if ($projeto_id > 0) $sql->adOnde('e.evento_projeto = '.(int)$projeto_id);
			//elseif ($evento_filtro=='todos' && !$usuario_id) $sql->adOnde('e.evento_projeto IS NULL');

			if ($pratica_id > 0) $sql->adOnde('e.evento_pratica = '.(int)$pratica_id);
			//elseif ($evento_filtro=='todos' && !$usuario_id) $sql->adOnde('e.evento_pratica IS NULL');

			if ($pratica_indicador_id > 0) $sql->adOnde('e.evento_indicador = '.(int)$pratica_indicador_id);
			//elseif ($evento_filtro=='todos' && !$usuario_id) $sql->adOnde('e.evento_indicador IS NULL');

			if ($pg_objetivo_estrategico_id > 0) $sql->adOnde('e.evento_objetivo = '.(int)$pg_objetivo_estrategico_id);
			//elseif ($evento_filtro=='todos' && !$usuario_id) $sql->adOnde('e.evento_objetivo IS NULL');

			if ($tema_id > 0) $sql->adOnde('e.evento_tema = '.(int)$tema_id);
			//elseif ($evento_filtro=='todos' && !$usuario_id) $sql->adOnde('e.evento_tema IS NULL');

			if ($pg_estrategia_id > 0) $sql->adOnde('e.evento_estrategia = '.(int)$pg_estrategia_id);
			//elseif ($evento_filtro=='todos' && !$usuario_id) $sql->adOnde('e.evento_estrategia IS NULL');

			if ($pg_fator_critico_id > 0) $sql->adOnde('e.evento_fator = '.(int)$pg_fator_critico_id);
			//elseif ($evento_filtro=='todos' && !$usuario_id) $sql->adOnde('e.evento_fator IS NULL');

			if ($pg_meta_id > 0) $sql->adOnde('e.evento_meta = '.(int)$pg_meta_id);
			//elseif ($evento_filtro=='todos' && !$usuario_id) $sql->adOnde('e.evento_meta IS NULL');

			if ($pg_perspectiva_id > 0) $sql->adOnde('e.evento_perspectiva = '.(int)$pg_perspectiva_id);
			//elseif ($evento_filtro=='todos' && !$usuario_id) $sql->adOnde('e.evento_perspectiva IS NULL');

			if ($canvas_id > 0) $sql->adOnde('e.evento_canvas = '.(int)$canvas_id);

			if ($plano_acao_id > 0) $sql->adOnde('e.evento_acao = '.(int)$plano_acao_id);
			//elseif ($evento_filtro=='todos' && !$usuario_id) $sql->adOnde('e.evento_acao IS NULL');

			if ($calendario_id) $sql->adOnde('e.evento_calendario IN ('.$calendario_id.')');
			//elseif ($evento_filtro=='todos' && !$usuario_id)  $sql->adOnde('e.evento_calendario IS NULL');
			}

		if ($evento_filtro=='todos'){
			if ($dept_id) {
				$sql->esqUnir('evento_depts','evento_depts', 'evento_depts.evento_id=e.evento_id');
				$sql->adOnde('evento_depts.dept_id IN ('.$dept_id.') OR e.evento_dept IN ('.$dept_id.')');
				}
			elseif ($Aplic->profissional && $cia_id) {
				$sql->esqUnir('evento_cia', 'evento_cia', 'e.evento_id=evento_cia_evento');
				$sql->adOnde('evento_cia IN ('.$cia_id.') OR evento_cia_cia IN ('.$cia_id.')');
				}
			elseif ($cia_id) $sql->adOnde('evento_cia='.(int)$cia_id);
			}

		switch ($evento_filtro) {
			case 'meu':
				$sql->esqUnir('evento_usuarios', 'eu', 'eu.evento_id = e.evento_id');
				$sql->adOnde('evento_dono IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') OR eu.usuario_id  IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).')');
				break;
			case 'dono':
				$sql->adOnde('evento_dono IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).')');
				break;
			case 'todos':
				if ($usuario_id){
					$sql->esqUnir('evento_usuarios', 'eu', 'eu.evento_id = e.evento_id');
					$sql->adOnde('(evento_dono IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') OR (eu.usuario_id IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') AND (eu.aceito=1 || eu.aceito=0)))');
					}
				break;
			case 'todos_aceitos':
				$sql->esqUnir('evento_usuarios', 'eu', 'eu.evento_id = e.evento_id');
				$sql->adOnde('eu.usuario_id IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') AND eu.aceito=1');
				break;
			case 'todos_pendentes':
				$sql->esqUnir('evento_usuarios', 'eu', 'eu.evento_id = e.evento_id');
				$sql->adOnde('eu.usuario_id IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') AND eu.aceito=0');
				break;
			case 'todos_recusados':
				$sql->esqUnir('evento_usuarios', 'eu', 'eu.evento_id = e.evento_id');
				$sql->adOnde('eu.usuario_id IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') AND eu.aceito=-1');
				break;
			}
		if ($db_inicio && $db_fim) $sql->adOnde('(evento_inicio <= \''.$db_fim.'\' AND evento_fim >= \''.$db_inicio. '\' OR evento_inicio BETWEEN \''.$db_inicio. '\' AND \''.$db_fim.'\')');
		$sql->adCampo('DISTINCT e.evento_id, e.evento_acesso, e.evento_titulo, e.evento_inicio, e.evento_fim, e.evento_superior, e.evento_descricao, e.evento_url, e.evento_nr_recorrencias, e.evento_recorrencias, e.evento_lembrar, e.evento_icone, e.evento_dono, e.evento_projeto, e.evento_tarefa, e.evento_tipo, e.evento_diautil, e.evento_notificar, e.evento_localizacao, e.evento_cor');
		$sql->adOrdem('e.evento_inicio, e.evento_fim ASC');
		$listaEvento = $sql->Lista();
		$sql->limpar();
		return $listaEvento;
		}

	function getDesignado($tipo='') {
		global $config;
		$sql = new BDConsulta;
		$sql->adTabela('evento_usuarios','ue');
		$sql->esqUnir('usuarios', 'u','ue.usuario_id = u.usuario_id');
		$sql->esqUnir('contatos', 'con', 'usuario_contato = contato_id');
		$sql->adCampo('u.usuario_id');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome');
		$sql->adOnde('ue.evento_id = '.(int)$this->evento_id);
		$sql->adOnde('ue.usuario_id = u.usuario_id');
		if ($tipo=='nao_recusou') $sql->adOnde('aceito != -1');
		elseif ($tipo=='recusou') $sql->adOnde('aceito = -1');
		elseif ($tipo=='aceitou') $sql->adOnde('aceito = 1');
		elseif ($tipo=='nao_decidiu') $sql->adOnde('aceito = 0');
		$sql->adOrdem('con.contato_posto_valor, con.contato_nomeguerra');
		$designado = $sql->listaVetorChave('usuario_id','nome');
		$sql->Limpar();
		return $designado;
	}


	function atualizarDesignados($designado, $porcentagem) {
		global $Aplic;
		require_once BASE_DIR.'/modulos/tarefas/funcoes.php';
		$sql = new BDConsulta;
		$sql->setExcluir('evento_usuarios');
		$sql->adOnde('evento_id = '.(int)$this->evento_id);
		if (implode(',',$designado)) $sql->adOnde('usuario_id NOT IN('.implode(',',$designado).')');
		$sql->exec();
		$sql->limpar();
		if (is_array($designado) && count($designado)) {
			foreach ($designado as $chave => $usuario_id) {
				if ($usuario_id) {
					//checar se já foi inserido
					$sql->adTabela('evento_usuarios');
					$sql->adOnde('evento_id = '.$this->evento_id);
					$sql->adOnde('usuario_id = '.$usuario_id);
					$sql->adCampo('usuario_id');
					$ja_tem=$sql->Resultado();
					$sql->limpar();

					$sql->adTabela('usuarios');
					$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
					$sql->adOnde('usuario_id = '.$usuario_id);
					$sql->adCampo('contato_cia');
					$cia_id=$sql->Resultado();
					$sql->limpar();
					$duracao=horas_periodo($this->evento_inicio, $this->evento_fim, $cia_id, $usuario_id);

					if (!$ja_tem){
						$sql->adTabela('evento_usuarios');
						$sql->adInserir('evento_id', $this->evento_id);
						$sql->adInserir('usuario_id', $usuario_id);
						$sql->adInserir('duracao', $duracao);
						$sql->adInserir('percentual', (isset($porcentagem[$chave]) ? $porcentagem[$chave] : 100));
						if ($usuario_id==$this->evento_dono) {
							$sql->adInserir('aceito', 1);
							$sql->adInserir('data', date('Y-m-d H:i:s'));
							}
						$sql->exec();
						$sql->limpar();
						}
					else{
						}
					}
				}
			if ($msg = db_error()) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}
		}

	function atualizarDuracao($designado) {
		global $Aplic;
		require_once BASE_DIR.'/modulos/tarefas/funcoes.php';
		$sql = new BDConsulta;
		if (is_array($designado) && count($designado)) {
			foreach ($designado as $usuario_id) {
				if ($usuario_id) {
					$sql->adTabela('usuarios');
					$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
					$sql->adOnde('usuario_id = '.$usuario_id);
					$sql->adCampo('contato_cia');
					$cia_id=$sql->Resultado();
					$sql->limpar();
					$duracao=horas_periodo($this->evento_inicio, $this->evento_fim, $cia_id, $usuario_id);
					$sql->adTabela('evento_usuarios');
					$sql->adAtualizar('duracao', $duracao);
					$sql->adOnde('evento_id='.(int)$this->evento_id);
					$sql->adOnde('usuario_id='.(int)$usuario_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		}

	function notificar($designados, $atualizar = false, $conflito = false) {
		global $Aplic, $localidade_tipo_caract, $config;
		$email_dono = $Aplic->getPref('emailtodos');
		$lista_designados = explode(',', $designados);
		$responsavel_eh_designado = in_array($this->evento_dono, $lista_designados);
		if ($email_dono && !$responsavel_eh_designado && $this->evento_dono) array_push($lista_designados, $this->evento_dono);
		foreach ($lista_designados as $chave => $x) {
			if (!$x) unset($lista_designados[$chave]);
			}
		if (!count($lista_designados)) return;
		$sql = new BDConsulta;
		$sql->adTabela('usuarios', 'u');
		$sql->adTabela('contatos', 'con');
		$sql->adCampo('usuario_id, contato_posto,contato_nomeguerra, contato_email');
		$sql->adOnde('u.usuario_contato = con.contato_id');
		$sql->adOnde('usuario_id in ('.implode(',', $lista_designados).')');
		$sql->adOrdem('con.contato_posto_valor, con.contato_nomeguerra');
		$usuarios = $sql->ListaChave('usuario_id');
		$sql->Limpar();
		$formato_data = $Aplic->getPref('datacurta');
		$formato_hora = $Aplic->getPref('formatohora');
		$fmt = $formato_data.' '.$formato_hora;
		$data_inicio = new CData($this->evento_inicio);
		$data_fim = new CData($this->evento_fim);
		$tipo = ($atualizar ? "Evento atualizado" : "Novo evento");
		if ($conflito) $titulo="Solicitar Evento: ".$this->evento_titulo;
		else $titulo=$tipo." Evento: ".$this->evento_titulo;
		$corpo = '';
		if ($conflito) {
			$corpo .= "Você foi convidado para um evento de ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra."\n";
			$corpo .= "Entretanto, ou você ou outro convidado tem outro evento ao mesmo tempo\n";
			$corpo .= $Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra." solicitou que você reponda a esta menssagem\n";
			$corpo .= "e confirme se irá ou não fazer o requerido em tempo.\n\n";
			}
		$corpo .= "<b>Evento:</b>\t".$this->evento_titulo."\n";
		$corpo .= "<b>Início:</b>\t".$data_inicio->format($fmt)."\n";
		$corpo .= "<b>Término:</b>\t".$data_fim->format($fmt)."\n";
		if ($this->evento_projeto) {
			$prj = array();
			$sql->adTabela('projetos', 'p');
			$sql->adCampo('projeto_nome');
			$sql->adOnde('p.projeto_id ='.(int)$this->evento_projeto);
			if ($prj = $sql->Linha()) $corpo .= '<b>'.ucfirst($config['projeto']).":</b>\t".$prj['projeto_nome']."\n";
			$sql->limpar();
			}
		if ($this->evento_pratica) {
			$sql->adTabela('praticas', 'p');
			$sql->adCampo('pratica_nome');
			$sql->adOnde('p.pratica_id ='.(int)$this->evento_pratica);
			if ($pratica_nome = $sql->Resultado()) $corpo .= '<b>'.ucfirst($config['pratica']).":</b>\t".$pratica_nome."\n";
			$sql->limpar();
			}
		if ($this->evento_indicador) {
			$sql->adTabela('pratica_indicador', 'p');
			$sql->adCampo('pratica_indicador_nome');
			$sql->adOnde('p.pratica_indicador_id ='.(int)$this->evento_indicador);
			if ($pratica_indicador_nome = $sql->Resultado()) $corpo .= "<b>Indicador:</b>\t".$pratica_indicador_nome."\n";
			$sql->limpar();
			}
		if ($this->evento_objetivo) {
			$sql->adTabela('objetivos_estrategicos');
			$sql->adCampo('pg_objetivo_estrategico_nome');
			$sql->adOnde('pg_objetivo_estrategico_id ='.(int)$this->evento_objetivo);
			if ($objetivo_nome = $sql->Resultado()) $corpo .= '<b>'.ucfirst($config['objetivo']).":</b>\t".$objetivo_nome."\n";
			$sql->limpar();
			}
		if ($this->evento_tema) {
			$sql->adTabela('tema');
			$sql->adCampo('tema_nome');
			$sql->adOnde('tema_id ='.(int)$this->evento_tema);
			if ($tema_nome = $sql->Resultado()) $corpo .= '<b>'.ucfirst($config['tema']).":</b>\t".$tema_nome."\n";
			$sql->limpar();
			}
		if ($this->evento_estrategia) {
			$sql->adTabela('estrategias');
			$sql->adCampo('pg_estrategia_nome');
			$sql->adOnde('pg_estrategia_id ='.(int)$this->evento_estrategia);
			if ($estrategia_nome = $sql->Resultado()) $corpo .= "<b>Iniciativa:</b>\t".$estrategia_nome."\n";
			$sql->limpar();
			}


		if ($this->evento_calendario) {
			$sql->adTabela('calendario');
			$sql->adCampo('descricao');
			$sql->adOnde('calendario_id ='.(int)$this->evento_calendario);
			if ($calendario_nome = $sql->Resultado()) $corpo .= "<b>Calendário:</b>\t".$calendario_nome."\n";
			$sql->limpar();
			}
		$tipos = getSisValor('TipoEvento');
		$corpo .= "<b>Tipo:</b>\t".$tipos[$this->evento_tipo]."\n";
		$corpo .= "<b>".ucfirst($config['usuarios']).":</b>\t";
		$corpo_anexa = '';
		foreach ($usuarios as $usuario) {
			$corpo_anexa .= ((($corpo_anexa) ? ', ' : '').($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']));
			}
		$corpo .= $corpo_anexa."\n\n".$this->evento_descricao."\n";
		$link_interno= "\t".'<a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=ver&evento_id='.$this->evento_id.'\');">Clique aqui para acessar</a>'."\n";
		foreach ($usuarios as $usuario) {
			if (!$email_dono && $usuario['usuario_id'] == $this->evento_dono) continue;
			msg_email_interno ('', $titulo, $corpo.$link_interno,'',$usuario['usuario_id']);
			if ($config['email_ativo'] && $config['email_externo_auto']){
				$email = new Mail;
				$email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }


				if ($Aplic->profissional){
					require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
					$link_externo='<br><a href="'.link_email_externo($usuario['usuario_id'], 'm=calendario&a=ver&evento_id='.$this->evento_id).'"><b>Clique aqui para acessar</b></a>';
					}
				else $link_externo='';

				$email->Assunto($titulo, $localidade_tipo_caract);
				$email->Corpo($corpo.$link_externo, $localidade_tipo_caract);
				$email->Para($usuario['contato_email'], true);
				$email->Enviar();
				}
			}
		}

	function checarConflito($listaUsuarios = null) {
		global $Aplic, $config;
		if (!isset($listaUsuarios)) return false;
		$usuarios = explode(',', $listaUsuarios);
		if (!count($usuarios)) return false;
		$data_inicio = new CData($this->evento_inicio);
		$data_fim = new CData($this->evento_fim);
		$sql = new BDConsulta;
		$sql->adTabela('eventos', 'e');
		$sql->adCampo('DISTINCT ue.usuario_id');
		$sql->adUnir('evento_usuarios', 'ue', 'ue.evento_id = e.evento_id');
		$sql->adOnde('evento_inicio <= \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\'');
		$sql->adOnde('evento_fim >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'');
		$sql->adOnde('ue.usuario_id IN ('.implode(',', $usuarios).')');
		$sql->adOnde('e.evento_id !='.(int)$this->evento_id);
		$conflitos = $sql->carregarColuna();
		$sql->limpar();
		if (count($conflitos)) {
			$sql->adTabela('usuarios', 'u');
			$sql->esqUnir('contatos', 'con','usuario_contato = contato_id');
			$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
			$sql->adOnde('usuario_id IN ('.implode(',', $conflitos).')');
			return $sql->ListaChave();
			}
		else return false;
		}

	function getEventosNaJanela($data_inicio, $data_fim, $inicio_hora, $fim_hora, $usuarios = null) {
		global $Aplic;
		if (!isset($usuarios)) return false;
		if (!count($usuarios)) return false;
		$sql = new BDConsulta;
		$sql->adTabela('eventos', 'e');
		$sql->adCampo('e.evento_dono, ue.usuario_id, e.evento_diautil, e.evento_id, e.evento_inicio, e.evento_fim');
		$sql->adUnir('evento_usuarios', 'ue', 'ue.evento_id = e.evento_id');
		$sql->adOnde('evento_inicio >= \''.$data_inicio.'\'	AND evento_fim <= \''.$data_fim.'\'	AND extrair(\'HOUR_MINUTE\', e.evento_fim) >= \''.$inicio_hora.'\'	AND extrair(\'HOUR_MINUTE\', e.evento_inicio) <= \''.$fim_hora.'\' AND ( e.evento_dono in ('.implode(',', $usuarios).')	OR ue.usuario_id in ('.implode(',',$usuarios).') )');
		$resultado = $sql->exec();
		if (!$resultado) return false;
		$listaEventos = array();
		while ($linha = $sql->carregarLinha()) $listaEventos[] = $linha;
		$sql->limpar();
		return $listaEventos;
		}

	function podeAcessar(){
		if ($this->evento_pratica) $valor=permiteAcessarPratica($this->evento_acesso, $this->evento_pratica, $this->evento_acao);
		elseif ($this->evento_indicador) $valor=permiteAcessarIndicador($this->evento_acesso, $this->evento_indicador);
		elseif ($this->evento_tema) $valor=permiteAcessarTema(evento_acesso, $this->evento_tema);
		elseif ($this->evento_objetivo) $valor=permiteAcessarObjetivo($this->evento_acesso, $this->evento_objetivo);
		elseif ($this->evento_estrategia) $valor=permiteAcessarEstrategia($this->evento_acesso, $this->evento_estrategia);
		elseif ($this->evento_calendario) $valor=permiteAcessarCalendario($this->evento_calendario);
		else $valor=permiteAcessar($this->evento_acesso, $this->evento_projeto, $this->evento_tarefa);
		return $valor;
		}

	function podeEditar() {
		if ($this->evento_pratica) $valor=permiteEditarPratica($this->evento_acesso, $this->evento_pratica, $this->evento_acao);
		elseif ($this->evento_indicador) $valor=permiteEditarIndicador($this->evento_acesso, $this->evento_indicador);
		elseif ($this->evento_tema) $valor=permiteEditarTema($this->evento_acesso, $this->evento_tema);
		elseif ($this->evento_objetivo) $valor=permiteEditarObjetivo($this->evento_acesso, $this->evento_objetivo);
		elseif ($this->evento_estrategia) $valor=permiteEditarEstrategia($this->evento_acesso, $this->evento_estrategia);
		elseif ($this->evento_calendario) $valor=permiteEditarCalendario($this->evento_calendario);
		else $valor=permiteEditar($this->evento_acesso, $this->evento_projeto, $this->evento_tarefa);
		return $valor;
		}



	function adLembrete() {
		if (!$this->evento_inicio||($this->evento_lembrar < 1)) {
			return $this->limparLembrete();
			}
		$eq = new EventoFila;
		$args = null;
		$lembretes_antigos = $eq->procurar('calendario', 'evento', $this->evento_id);
		if (count($lembretes_antigos)) {
			foreach ($lembretes_antigos as $antigo_id => $data_antiga) $eq->remover($antigo_id);
			}
		$data = new CData($this->evento_inicio);
		$hoje = new CData(date('Y-m-d'));
		if ($data->compare($data, $hoje) < 0) $inicio_dia = time();
		else {
			$inicio_dia = $data->getData(DATE_FORMAT_UNIXTIME);
			}
		$eq->adicionar(array($this, 'lembrar'), $args, 'calendario', false, $this->evento_id, 'evento', ($inicio_dia-$this->evento_lembrar));
		}

	function lembrar($modulo=null, $tipo=null, $id=null, $responsavel=null, $args=null) {
		global $localidade_tipo_caract, $Aplic, $config;
		$tipos = getSisValor('TipoEvento');
		$sql = new BDConsulta;
	  $sem_email_interno=0;
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		if (!$this->load($id)) return - 1;
		$this->htmlDecodificar();
		$hoje = new CData();
		$sql->adTabela('eventos','e');
		$sql->esqUnir('evento_usuarios', 'eu', 'eu.evento_id = e.evento_id');
		$sql->esqUnir('usuarios', 'u', 'u.usuario_id = eu.usuario_id');
		$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->adCampo('c.contato_id, contato_posto, contato_nomeguerra, contato_email,u.usuario_id, cia_nome');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$sql->adOnde('e.evento_id = '.(int)$id);
		$contatos = $sql->ListaChaveSimples('contato_id');
		$sql->limpar();

		$responsavel_naoeh_designado = false;
		$sql->adTabela('usuarios', 'u');
		$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->adCampo('c.contato_id, contato_posto, contato_nomeguerra, contato_email, usuario_id, cia_nome');
		$sql->adOnde('u.usuario_id = '.(int)$this->evento_dono);
		$responsavel=$sql->linha();
		$sql->limpar();

		if (!isset($contatos[$responsavel['contato_id']])) {
			$contatos[$responsavel['contato_id']]=$responsavel;
			}

		$agora = new CData();

		$projeto_nome = ($this->evento_projeto ? htmlspecialchars_decode(nome_projeto($this->evento_projeto)) : '');
		$tarefa_nome = ($this->evento_tarefa ? htmlspecialchars_decode(nome_tarefa($this->evento_tarefa)) : '');
		$indicador_nome = ($this->evento_indicador ? htmlspecialchars_decode(nome_indicador($this->evento_indicador)) : '');
		$objetivo_nome = ($this->evento_objetivo ? htmlspecialchars_decode(nome_objetivo($this->evento_objetivo)) : '');
		$tema_nome = ($this->evento_tema ? htmlspecialchars_decode(nome_tema($this->evento_tema)) : '');
		$estrategia_nome = ($this->evento_estrategia ? htmlspecialchars_decode(nome_estrategia($this->evento_estrategia)) : '');
		$calendario_nome = ($this->evento_calendario ? htmlspecialchars_decode(nome_calendario($this->evento_calendario)) : '');
		$pratica_nome = ($this->evento_pratica ? htmlspecialchars_decode(nome_pratica($this->evento_pratica)) : '');
		$acao_nome = ($this->evento_acao ? htmlspecialchars_decode(nome_acao($this->evento_acao)) : '');

		$data = new CData($this->evento_inicio);
		$assunto = '<b>Lembrete: </b>'.$this->evento_titulo.($projeto_nome || $pratica_nome || $indicador_nome || $calendario_nome || $estrategia_nome || $objetivo_nome || $tema_nome ? ' ('.$tema_nome.$projeto_nome.$pratica_nome.$estrategia_nome.$objetivo_nome.$indicador_nome.$calendario_nome.$acao_nome.($tarefa_nome  ? ' - '.$tarefa_nome :'').')': '');
		$corpo='<b>Evento:</b> '.$this->evento_titulo.($projeto_nome || $pratica_nome || $indicador_nome || $calendario_nome || $estrategia_nome || $objetivo_nome || $tema_nome ? ' ('.$projeto_nome.$pratica_nome.$indicador_nome.$estrategia_nome.$objetivo_nome.$calendario_nome.$acao_nome.($tarefa_nome ? ' - '.$tarefa_nome :'').')': '').'<br>';
		if ($this->evento_inicio) $corpo.='<b>Data de Início:</b> '.retorna_data($this->evento_inicio, true).'<br>';
		if ($this->evento_fim) $corpo.='<b>Data de Término:</b> '.retorna_data($this->evento_fim, true).'<br>';
		if ($this->evento_dono) $corpo.='<b>Responsável:</b> '.$responsavel['contato_posto'].' ' .$responsavel['contato_nomeguerra'].($responsavel['cia_nome'] ? ' - '.$responsavel['cia_nome'] : '').'<br>';
		if ($this->evento_tipo) $corpo.='<b>Tipo:</b> '.$tipos[$this->evento_tipo].'<br>';
		$corpo.='<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=ver_dia&data='.$data->format(FMT_TIMESTAMP_DATA).'&tab=0\');">Clique aqui para visualizar o evento</a><br><br>';
		$designados='';
		foreach ($contatos as $contato) {
			$designados.= $contato['contato_posto'].' '.$contato['contato_nomeguerra'].($contato['cia_nome'] ? ' - '.$contato['cia_nome'] : '').($contato['contato_email'] ? ' <'.$contato['contato_email'].'>' : '').'<br>';
			}
		if ($designados) $corpo.='<b>Participante'.(count($contatos) > 1 ? 's':'').':</b><br>'.$designados;
		if ($this->evento_descricao) $corpo .= '<br><b>Descrição:</b><br>'.$this->evento_descricao.'<br>';
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
			$retorno_interno=msg_email_interno('', $assunto, $corpo,'',$contato['usuario_id']);
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
		$evento_lista = $ev->procurar('calendario', 'evento', $this->evento_id);
		if (count($evento_lista)) {
			foreach ($evento_lista as $id => $data) $ev->remover($id);
			}
		}


	function criar_recorrencias(){
		global $bd;
		$sql = new BDConsulta;
		$sql->adTabela('eventos');
		$sql->adCampo('eventos.*');
		$sql->adOnde('evento_id='.(int)$this->evento_id);
		$linha = $sql->linha();
		$sql->limpar();

		// 1 = hora, 2 = dia , 3 = semana, 4 = 15 dias, 5 = mes, 6 = quadrimestre, 7 = semestral, 8 =anual

		$data1=substr($linha['evento_inicio'], 0, 10);
		$data2=substr($linha['evento_fim'], 0, 10);
		$hora_inicio=substr($linha['evento_inicio'], 10, 8);
		$hora_fim=substr($linha['evento_fim'], 10, 8);

		$sql->adTabela('evento_usuarios');
		$sql->adCampo('evento_usuarios.*');
		$sql->adOnde('evento_id='.(int)$this->evento_id);
		$designados = $sql->lista();
		$sql->limpar();


		for ($i=0; $i < $linha['evento_nr_recorrencias'] ; $i++){

			if ($linha['evento_recorrencias']==2){
				$data1=strtotime('+1 day', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+1 day', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==3){
				$data1=strtotime('+1 week', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+1 week', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==4){
				$data1=strtotime('+15 day', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+15 day', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==5){
				$data1=strtotime('+1 month', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+1 month', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==9){
				$data1=strtotime('+2 month', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+2 month', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}


			if ($linha['evento_recorrencias']==10){
				$data1=strtotime('+3 month', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+3 month', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}


			if ($linha['evento_recorrencias']==6){
				$data1=strtotime('+4 month', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+4 month', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==7){
				$data1=strtotime('+6 month', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+6 month', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==8){
				$data1=strtotime('+1 year', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+1 year', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			$sql->adTabela('eventos');

			foreach($linha as $chave => $valor) if ($chave !='evento_id' && $chave !='evento_recorrencia_pai' && $chave !='evento_inicio' && $chave !='evento_fim') $sql->adInserir($chave, $valor);
			$sql->adInserir('evento_inicio', $data1.' '.$hora_inicio);
			$sql->adInserir('evento_fim', $data2.' '.$hora_fim);
			$sql->adInserir('evento_recorrencia_pai', $linha['evento_id']);
			$sql->exec();
			$evento_id=$bd->Insert_ID('eventos','evento_id');
			$sql->limpar();
			//designados
			foreach($designados as $linha2){
				$sql->adTabela('evento_usuarios');
				$sql->adInserir('usuario_id', $linha2['usuario_id']);
				$sql->adInserir('aceito', $linha2['aceito']);
				$sql->adInserir('data', $linha2['data']);
				$sql->adInserir('duracao', $linha2['duracao']);
				$sql->adInserir('percentual', $linha2['percentual']);
				$sql->adInserir('evento_id', $evento_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		}


	}

$evento_filtro_lista = array('todos' => 'Todos os eventos', 'meu' => 'Eventos onde '.$config['genero_usuario'].' '.$config['usuario'].' está designad'.$config['genero_usuario'].' ou responsável', 'dono' => 'Eventos onde '.$config['genero_usuario'].' '.$config['usuario'].' é '.$config['genero_usuario'].' responsável', 'todos_aceitos' => 'Eventos aceitos pel'.$config['genero_usuario'].' '.$config['usuario'], 'todos_pendentes' => 'Eventos pendentes de aceitar pel'.$config['genero_usuario'].' '.$config['usuario'], 'todos_recusados' => 'Eventos recusados pel'.$config['genero_usuario'].' '.$config['usuario']);
?>