<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $projeto_id, $negar, $podeAcessar, $podeEditar, $config, $data_inicio, $data_fim, $este_dia, $agenda_filtro, $agenda_filtro_lista;
require_once $Aplic->getClasseModulo('calendario');
require_once (BASE_DIR.'/modulos/email/email.class.php');
if(!isset($usuario_id)) $usuario_id = $Aplic->usuario_id;
$data_inicio =  new CData();
$data_fim =  new CData('9999-12-31 23:59:59');
if (isset($_REQUEST['usuario_id'])) $usuario_id = getParam($_REQUEST, 'usuario_id', '');

$compromissos = CAgenda::getCompromissoParaPeriodo($data_inicio, $data_fim, '', $Aplic->usuario_lista_grupo);

$inicio_hora = config('cal_dia_inicio');
$fim_hora = config('cal_dia_fim');
$tf = $Aplic->getPref('formatohora');
$df = '%d/%m/%Y';
$tipos = getSisValor('TipoEvento');
$html = '<table cellspacing=0 cellpadding="2" border=0 width="100%" class="tbl1">';
$html .= '<tr><th>'.dica('Data - Hora', 'A data e hora do início e término do evento.').'Data'.dicaF().'</th><th>'.dica('Compromisso', 'O nome do compromisso.').'Compromisso'.dicaF().'</th></tr>';
$qnt=0;
foreach ($compromissos as $linha) {
	$qnt++;
	$html .= '<tr>';
	$inicio = new CData($linha['agenda_inicio']);
	$fim = new CData($linha['agenda_fim']);
	$html .= '<td nowrap="nowrap" width="230">'.$inicio->format($df.' '.$tf).'&nbsp;-&nbsp;'.$fim->format($df.' '.$tf).'</td>';
	$html .= '<td>'.link_compromisso($linha['agenda_id']).'</td></tr>';
	}
if (!$qnt) $html .= '<tr><td colspan=3 align="left"><p>Nenhum compromisso encontrado.</p></td></tr>';
$html .= '</table>';
echo $html;
?>