<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $perms, $este_dia, $primeiraData, $ultimaData, $cia_id, $agenda_filtro, $agenda_filtro_lista, $Aplic, $titulo, $agenda_tipo_id;

$links = array();
$compromissos = CAgenda::getCompromissoParaPeriodo($primeiraData, $ultimaData, $agenda_filtro, $Aplic->usuario_id, $agenda_tipo_id);
$compromissos2 = array();
$inicio_hora = $config['cal_dia_inicio'];
$fim_hora = $config['cal_dia_fim'];
$data=getParam($_REQUEST, 'data', '');

foreach ($compromissos as $linha) {
	$inicio = new CData($linha['agenda_inicio']);
	$fim = new CData($linha['agenda_fim']);
	
	//preciso arredondar os minutos para exibição
		if ($inicio->minuto <= 7) $inicio->minuto=0;
		elseif ($inicio->minuto <= 22) $inicio->minuto=15;
		elseif ($inicio->minuto <= 37) $inicio->minuto=30;
		elseif ($inicio->minuto <= 52) $inicio->minuto=45;
		else {
			$inicio->minuto=0;
			if ($inicio->hora < 23) $fim->hora++;
			}
		
		if ($fim->minuto <= 7) $fim->minuto=0;
		elseif ($fim->minuto <= 22) $fim->minuto=15;
		elseif ($fim->minuto <= 37) $fim->minuto=30;
		elseif ($fim->minuto <= 52) $fim->minuto=45;
		else {
			$fim->minuto=0;
			if ($fim->hora < 23) $fim->hora++;
			}
		
	
	
	//inicia e termina no mesmo dia
	if ($inicio->format("%Y%m%d")==$fim->format("%Y%m%d")){
		$compromissos2[$inicio->format('%H%M%S')][] = $linha;
		if ($inicio_hora > $inicio->format('%H')) $inicio_hora = $inicio->format('%H');
		if ($fim_hora < $fim->format('%H')) $fim_hora = $fim->format('%H');
		}
	elseif($data==$inicio->format("%Y%m%d")){  
		// estou no 1o dia
		if ($inicio_hora > $inicio->format('%H')) $inicio_hora = $inicio->format('%H');
		$linha['agenda_fim']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_fim']< 10 ? '0' : '').$config['cal_dia_fim'].':00:00';
		$compromissos2[$inicio->format('%H%M%S')][] = $linha;
		}
	elseif($data==$fim->format("%Y%m%d")){  
		// estou no ultimo dia
		$linha['agenda_inicio']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].':00:00';
		$compromissos2[($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].'0000'][] = $linha;
		if ($fim_hora < $fim->format('%H')) $fim_hora = $fim->format('%H');
		}
	else{
		//um dia no meio
		$linha['agenda_fim']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_fim']< 10 ? '0' : '').$config['cal_dia_fim'].':00:00';
		$linha['agenda_inicio']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].':00:00';
		$compromissos2[($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].'0000'][] = $linha;
		}
	}

$tf = $Aplic->getPref('formatohora');
$diaFormato = $este_dia->format(FMT_TIMESTAMP_DATA);

$inicio=0;
$fim=24;
$inc=15;

$este_dia->setTime($inicio, 0, 0);
$saida = '<table cellpadding=0 cellspacing=0 class="tbl1" width="100%" style="background-color:#ffffff">';
$linhas = 0;
for ($i = 0, $n = (($fim - $inicio) * 60 / $inc); $i <= $n; $i++) {
	$saida .= '<tr>';
	$tm = $este_dia->format($tf);
	$saida .= '<td width="1%" align="right" nowrap="nowrap">'.($este_dia->getMinute() ? $tm : '<b>'.$tm.'</b>').'</td>';
	$formato_horas = $este_dia->format('%H%M%S');
	if (isset($compromissos2[$formato_horas]) && $compromissos2[$formato_horas]) {
		$quantidade = count($compromissos2[$formato_horas]);
		for ($j = 0; $j < $quantidade; $j++) {
			
			$linha = $compromissos2[$formato_horas][$j];
			$et = new CData($linha['agenda_fim']);
			$linhas = ((($et->getHour() * 60 + $et->getMinute()) - ($este_dia->getHour() * 60 + $este_dia->getMinute())) / $inc)+1;
			$saida .= '<td style="color:#'.melhorCor($linha['agenda_cor']).';background-color:#'.$linha['agenda_cor'].'" rowspan="'.$linhas.'" valign="top">';
			$saida .= '<table cellspacing=0 cellpadding=0 border=0><tr><td style="color:#'.melhorCor($linha['agenda_cor']).';background-color:#'.$linha['agenda_cor'].'"></td></tr></table>';
			$saida .= link_compromisso($linha['agenda_id']).'</td>';
			}
		} 
	elseif (--$linhas <= 0) $saida .= '<td>&nbsp;</td>';
	$saida .= '</tr>';
	$este_dia->adSegundos(60 * $inc);
	}
$saida .= '</table>';
echo $saida;
?>