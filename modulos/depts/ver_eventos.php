<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

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
echo '<tr><th>'.dica('Data-Hora', 'Data e hora de início e término do evento.').'Data'.dicaF().'</th><th>'.dica('Tipo', 'O tipo de evento.').'Tipo'.dicaF().'</th><th>'.dica('Nome', 'Nome do evento.').'Nome'.dicaF().'</th></tr>';
$qnt=0;
foreach ($eventos as $linha) {
	echo '<tr><td width="25%" nowrap="nowrap">'.retorna_data($linha['evento_inicio']).'&nbsp;-&nbsp;'.retorna_data($linha['evento_fim']).'</td>';
	echo '<td width="10%" nowrap="nowrap">'.imagem('icones/evento'.$linha['evento_tipo'].'.png').'&nbsp;<b>'.$tipos[$linha['evento_tipo']].'</b></td>';
	echo '<td>'.link_evento($linha['evento_id']).'</td></tr>';
	}
if (!count($eventos)) echo '<tr><td colspan="3"><p>Nenhum evento encontrado.</p></td></tr>';		
echo '</table>';
?>