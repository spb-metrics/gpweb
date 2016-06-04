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

require_once ($Aplic->getClasseBiblioteca('PEAR/Date'));
define('FMT_DATAISO', '%Y%m%dT%H%M%S');
define('FMT_TIMESTAMP_MYSQL', '%Y-%m-%d %H:%M:%S');
define('FMT_DATA_MYSQL', '%Y-%m-%d');
define('FMT_TIMESTAMP', '%Y%m%d%H%M%S');
define('FMT_TIMESTAMP_DATA', '%Y%m%d');
define('SEG_MINUTO', 60);
define('SEG_HORA', 3600);
define('SEG_DIA', 86400);

function sinal($x) {
		return $x ? ($x > 0 ? 1 : -1) : 0;
		}

class CData extends Date {

	function compare($d1, $d2, $converterTZ = false) {
		if ($converterTZ) {
			$d1->convertTZ(new Date_TimeZone('UTC'));
			$d2->convertTZ(new Date_TimeZone('UTC'));
			}
		$dias1 = Data_Calc::dataParaDias($d1->dia, $d1->mes, $d1->ano);
		$dias2 = Data_Calc::dataParaDias($d2->dia, $d2->mes, $d2->ano);
		$valor_comparado = 0;
		if ($dias1 - $dias2) $valor_comparado = $dias1 - $dias2;
		elseif ($d1->hora - $d2->hora) $valor_comparado = sinal($d1->hora - $d2->hora);
		elseif ($d1->minuto - $d2->minuto) $valor_comparado = sinal($d1->minuto - $d2->minuto);
		elseif ($d1->segundo - $d2->segundo) $valor_comparado = sinal($d1->segundo - $d2->segundo);
		return sinal($valor_comparado);
		}

	function adDias($n) {
		$formato_horas = $this->getTempo();
		$horaAntiga = $this->getHour();
		$this->setData($formato_horas + SEG_DIA * ceil($n), DATE_FORMAT_UNIXTIME);
		if (($horaAntiga - $this->getHour()) || !is_int($n)) {
			$formato_horas += ($horaAntiga - $this->getHour()) * SEG_HORA;
			$this->setData($formato_horas + SEG_DIA * $n, DATE_FORMAT_UNIXTIME);
			}
		}

	function adMeses($n) {
		$an = abs($n);
		$anos = floor($an / 12);
		$meses = $an % 12;
		if ($n < 0) {
			$this->ano -= $anos;
			$this->mes -= $meses;
			if ($this->mes < 1) {
				$this->ano--;
				$this->mes = 12 + $this->mes;
				}
			} 
		else {
			$this->ano += $anos;
			$this->mes += $meses;
			if ($this->mes > 12) {
				$this->ano++;
				$this->mes -= 12;
				}
			}
		}

	function dataDiferenca($quando) {
		if (!is_object($quando)) return false;
		return Data_calc::dateDiff($this->getDay(), $this->getMonth(), $this->getYear(), $quando->getDay(), $quando->getMonth(), $quando->getYear());
		}

	function setTime($h = 0, $m = 0, $s = 0) {
		$this->setHour($h);
		$this->setMinute($m);
		$this->setSecond($s);
		}

	function serDiaUtil($cia_id=0) {
		global $Aplic;
		$dias_uteis = config('cal_dias_uteis');
		$dias_uteis = ((is_null($dias_uteis)) ? array('1', '2', '3', '4', '5') : explode(',', $dias_uteis));
		
		if ($cia_id){
			$sql = new BDConsulta;
			$sql->adTabela('expediente');
			$sql->adCampo('IF (((almoco_inicio >= inicio) AND (almoco_fim <= fim)), (tempo_em_segundos(diferenca_tempo(almoco_inicio, inicio))+tempo_em_segundos(diferenca_tempo(fim, almoco_fim)))/3600, tempo_em_segundos(diferenca_tempo(fim, inicio))/3600) AS horas');
			$sql->adOnde('data=\''.$this->getYear().'-'.$this->getMonth().'-'.$this->getDay().'\'');
			$horas=$sql->resultado();
			if((float)$horas > 0) return true;
			elseif($horas!=false) return false;
			}
		return in_array($this->getDayOfWeek(), $dias_uteis);
		}

	function getAMPM() {
		return (($this->getHour() > 11) ? 'pm' : 'am');
		}

	function prox_dia_util($preservarHoras = false, $cia_id=0) {
		global $Aplic;
		$fazer = $this;
		$inicio = intval(config('cal_dia_inicio'));
		$fim = intval(config('cal_dia_fim'));
		while (!$this->serDiaUtil($cia_id) || $this->getHour() > $fim || ($preservarHoras == false && $this->getHour() == $fim && $this->getMinute() == '0')) {
			$this->adDias(1);
			$this->setTime($inicio, '0', '0');
			}
		if ($preservarHoras)	$this->setTime($fazer->getHour(), '0', '0');
		return $this;
		}

	function ant_dia_util($preservarHoras = false, $cia_id=0) {
		global $Aplic;
		$fazer = $this;
		$fim = intval(config('cal_dia_fim'));
		$inicio = intval(config('cal_dia_inicio'));
		while (!$this->serDiaUtil($cia_id) || ($this->getHour() < $inicio) || ($this->getHour() == $inicio && $this->getMinute() == '0')) {
			$this->adDias(-1);
			$this->setTime($fim, '0', '0');
			}
		if ($preservarHoras)	$this->setTime($fazer->getHour(), '0', '0');
		return $this;
		}

	function adDuracao($duracao = '8', $duracaoTipo = '1', $cia_id=0) {
		$sinal = sinal($duracao);
		$duracao = abs($duracao);
		if ($duracaoTipo == '24') $dias_uteis_completos = $duracao;
		elseif ($duracaoTipo == '1') { 
				$cal_dia_inicio = intval(config('cal_dia_inicio'));
				$cal_dia_fim = intval(config('cal_dia_fim'));
				$horasTrabDiario = config('horas_trab_diario');
				($sinal > 0) ? $this->prox_dia_util() : $this->ant_dia_util();
				$primeiroDia = ($sinal > 0) ? min($cal_dia_fim - $this->hora, $horasTrabDiario) : min($this->hora - $cal_dia_inicio, $horasTrabDiario);
				if ($primeiroDia < 0) $primeiroDia = 0;
				if ($duracao <= $primeiroDia) {
					($sinal > 0) ? $this->setHour($this->hora + $duracao) : $this->setHour($this->hora - $duracao);
					return $this;
					}
				$primeiroAdj = min($horasTrabDiario, $primeiroDia);
				$duracao -= $primeiroAdj;
				$this->adDias(1 * $sinal);
				($sinal > 0) ? $this->prox_dia_util() : $this->ant_dia_util();
				$horasRemanescentes = ($duracao > $horasTrabDiario) ? ($duracao % $horasTrabDiario) : $duracao;
				$dias_uteis_completos = round(($duracao - $horasRemanescentes) / $horasTrabDiario);
				if ($horasRemanescentes == 0 && $dias_uteis_completos > 0) {
					$dias_uteis_completos--;
					($sinal > 0) ? $this->setHour($cal_dia_inicio + $horasTrabDiario) : $this->setHour($cal_dia_fim - $horasTrabDiario);
					} 
				else ($sinal > 0) ? $this->setHour($cal_dia_inicio + $horasRemanescentes) : $this->setHour($cal_dia_fim - $horasRemanescentes);
				}
		for ($i = 0; $i < $dias_uteis_completos; $i++) {
			$this->adDias(1 * $sinal);
			if (!$this->serDiaUtil($cia_id)) $dias_uteis_completos++;
			}
		return $this->prox_dia_util();
		}

	function calcDuracao($e, $cia_id=0) {
		$s = new CData();
		$s->copy($this);
		$cal_dia_inicio = intval(config('cal_dia_inicio'));
		$cal_dia_fim = intval(config('cal_dia_fim'));
		$horasTrabDiario = config('horas_trab_diario');
		$sinal = 1;
		if ($e->before($s)) {
			$sinal = -1;
			$provisorio = $s;
			$s->copy($e);
			$e = $provisorio;
			}
		$dias = $e->dataDiferenca($s);
		if ($dias == 0) return min($horasTrabDiario, abs($e->hora - $s->hora)) * $sinal;
		$duracao = 0;
		$duracao += $s->serDiaUtil($cia_id) ? min($horasTrabDiario, abs($cal_dia_fim - $s->hora)) : 0;
		$s->adDias(1);
		for ($i = 1; $i < $dias; $i++) {
			$duracao += $s->serDiaUtil($cia_id) ? $horasTrabDiario : 0;
			$s->adDias(1);
			}
		$duracao += $s->serDiaUtil($cia_id) ? min($horasTrabDiario, abs($e->hora - $cal_dia_inicio)) : 0;
		return $duracao * $sinal;
		}

	function nrDiasUteisNoEspaco($e, $cia_id=0) {
		global $Aplic;
		$sinal = 1;
		if ($e->before($this)) $sinal = -1;
		$wd = 0;
		$dias = $e->dataDiferenca($this);
		$inicio = $this;
		for ($i = 0; $i <= $dias; $i++) {
			if ($inicio->serDiaUtil($cia_id)) $wd++;
			$inicio->adDias(1 * $sinal);
			}
		return $wd;
		}

	function duplicar() {
		if (version_compare(phpversion(), '5') >= 0) $novoObj = clone ($this);
		else $novoObj = $this;
		return $novoObj;
		}

	function calFim($duracao, $tipoDuracao) {
		$f = new CData();
		$f->copy($this);
		$cal_dia_inicio = intval(config('cal_dia_inicio'));
		$cal_dia_fim = intval(config('cal_dia_fim'));
		$horas_trab_diario = config('horas_trab_diario');
		$diasUteis = config('cal_dias_uteis');
		$dias_uteis = explode(',', $diasUteis);
		$inc = floor($duracao);
		$horasAdicionarUltimoDia = 0;
		$horasAdicionarPrimeiroDia = $duracao;
		$dias_uteis_completos = 0;
		$int_st_hora = $f->getHour();
		$espacoTrab = $cal_dia_fim - $cal_dia_inicio - $horas_trab_diario;
		$k = 7 - count($dias_uteis);
		$duracaoMins = ($duracao - $inc) * 60;
		if (($f->getMinute() + $duracaoMins) >= 60) $inc++;
		$minutos = ($f->getMinute() + $duracaoMins) % 60;
		if ($minutos > 38) $f->setMinute(45);
		elseif ($minutos > 23) $f->setMinute(30);
		elseif ($minutos > 8) $f->setMinute(15);
		else $f->setMinute(0);
		for ($i = 0; $i < $k; $i++) {
			if (array_search($f->getDayOfWeek(), $dias_uteis) === false) $f->adDias(1);
			}
		if ($tipoDuracao == 24) {
			if ($f->getHour() == $cal_dia_inicio && $f->getMinute() == 0) {
				$dias_uteis_completos = ceil($inc);
				$f->setMinute(0);
				} 
			else $dias_uteis_completos = ceil($inc) + 1;
			if (!(array_search($f->getDayOfWeek(), $dias_uteis) === false)) $dias_uteis_completos--;
			for ($i = 0; $i < $dias_uteis_completos; $i++) {
				$f->adDias(1);
				if (array_search($f->getDayOfWeek(), $dias_uteis) === false) $i--;
				}
			if ($f->getHour() == $cal_dia_inicio && $f->getMinute() == 0) {
				$f->setHour($cal_dia_fim);
				$f->setMinute(0);
				}
			} 
		else {
			$horasAdicionarPrimeiroDia = $inc;
			if ($f->getHour() + $inc > ($cal_dia_fim - $espacoTrab)) $horasAdicionarPrimeiroDia = ($cal_dia_fim - $espacoTrab) - $f->getHour();

			if ($horasAdicionarPrimeiroDia > $horas_trab_diario)	$horasAdicionarPrimeiroDia = $horas_trab_diario;
			$inc -= $horasAdicionarPrimeiroDia;
			$horasAdicionarUltimoDia = $inc % $horas_trab_diario;
			$dias_uteis_completos = floor(($inc - $horasAdicionarUltimoDia) / $horas_trab_diario);
			if ($horasAdicionarUltimoDia <= 0 && !($horasAdicionarPrimeiroDia == $horas_trab_diario)) $f->setHour($f->getHour() + $horasAdicionarPrimeiroDia);
			elseif ($horasAdicionarUltimoDia == 0) $f->setHour($f->getHour() + $horasAdicionarPrimeiroDia + $espacoTrab);
			else {
				$f->setHour($cal_dia_inicio + $horasAdicionarUltimoDia);
				$f->adDias(1);
				}
			if (($f->getHour() == $cal_dia_fim || ($f->getHour() - $int_st_hora) == ($horas_trab_diario + $espacoTrab)) && $minutos > 0) {
				$f->adDias(1);
				$f->setHour($cal_dia_inicio);
				}
			$g = false;
			for ($i = 0, $i_cmp = ceil($dias_uteis_completos); $i < $i_cmp; $i++) {
				if (!$g) $f->addHours(1);
				$g = false;
				if (array_search($f->getDayOfWeek(), $dias_uteis) === false) {
					$f->adDias(1);
					$i--;
					$g = true;
					}
				}
			}
		for ($i = 0, $i_cmp = 7 - count($dias_uteis); $i < $i_cmp; $i++) {
			if (array_search($f->getDayOfWeek(), $dias_uteis) === false) $f->adDias(1);
			}
		return $f;
		}
	}
?>
