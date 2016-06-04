<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $dept_id, $negar, $podeAcessar, $podeEditar, $config, $data_inicio, $data_fim, $este_dia, $evento_filtro, $evento_filtro_lista;
require_once $Aplic->getClasseModulo('calendario');
$usuario_id = $Aplic->usuario_id;
$data_inicio = new CData();
$data_fim = new CData('9999-12-31 23:59:59');
$eventos = CEvento::getEventoParaPeriodo($data_inicio, $data_fim, 'todos', 0, 0, $dept_id);
$inicio_hora = config('cal_dia_inicio');
$fim_hora = config('cal_dia_fim');
$tf = $Aplic->getPref('formatohora');
$df = '%d/%m/%Y';
$tipos = getSisValor('TipoEvento');
echo '<table cellspacing=0 cellpadding="2" border=0 width="100%" class="tbl1">';
echo '<tr><th>'.dica('Data-Hora', 'Data e hora de in�cio e t�rmino do evento.').'Data'.dicaF().'</th><th>'.dica('Tipo', 'O tipo de evento.').'Tipo'.dicaF().'</th><th>'.dica('Nome', 'Nome do evento.').'Nome'.dicaF().'</th></tr>';
$qnt=0;
foreach ($eventos as $linha) {
	echo '<tr><td width="25%" nowrap="nowrap">'.retorna_data($linha['evento_inicio']).'&nbsp;-&nbsp;'.retorna_data($linha['evento_fim']).'</td>';
	echo '<td width="10%" nowrap="nowrap">'.imagem('icones/evento'.$linha['evento_tipo'].'.png').'&nbsp;<b>'.$tipos[$linha['evento_tipo']].'</b></td>';
	echo '<td>'.link_evento($linha['evento_id']).'</td></tr>';
	}
if (!count($eventos)) echo '<tr><td colspan="3"><p>Nenhum evento encontrado.</p></td></tr>';		
echo '</table>';
?>