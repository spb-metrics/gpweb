<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $negar, $podeAcessar, $podeEditar, $config, $data_inicio, $data_fim, $este_dia, $evento_filtro, $evento_filtro_lista,
	$tarefa_id,  
	$projeto_id,  
	$pg_perspectiva_id,  
	$tema_id,  
	$pg_objetivo_estrategico_id,  
	$pg_fator_critico_id,  
	$pg_estrategia_id, 
	$pg_meta_id,  
	$pratica_id,  
	$pratica_indicador_id,  
	$plano_acao_id,  
	$canvas_id,  
	$risco_id, 
	$risco_resposta_id, 
	$calendario_id,  
	$monitoramento_id,  
	$ata_id,  
	$swot_id,  
	$operativo_id, 
	$instrumento_id, 
	$recurso_id, 
	$problema_id, 
	$demanda_id, 
	$programa_id, 
	$licao_id, 
	$link_id, 
	$avaliacao_id, 
	$tgn_id, 
	$brainstorm_id, 
	$gut_id, 
	$causa_efeito_id, 
	$arquivo_id, 
	$forum_id, 
	$checklist_id, 
	$agenda_id, 
	$agrupamento_id, 
	$patrocinador_id, 
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id;

require_once $Aplic->getClasseModulo('calendario');

$usuario_id = $Aplic->usuario_id;

$eventos = CEvento::getEventoParaPeriodo($data_inicio, $data_fim, 'todos', '','','',
	$tarefa_id,  
	$projeto_id,  
	$pg_perspectiva_id,  
	$tema_id,  
	$pg_objetivo_estrategico_id,  
	$pg_fator_critico_id,  
	$pg_estrategia_id, 
	$pg_meta_id,  
	$pratica_id,  
	$pratica_indicador_id,  
	$plano_acao_id,  
	$canvas_id,  
	$risco_id, 
	$risco_resposta_id, 
	$calendario_id,  
	$monitoramento_id,  
	$ata_id,  
	$swot_id,  
	$operativo_id, 
	$instrumento_id, 
	$recurso_id, 
	$problema_id, 
	$demanda_id, 
	$programa_id, 
	$licao_id, 
	$link_id, 
	$avaliacao_id, 
	$tgn_id, 
	$brainstorm_id, 
	$gut_id, 
	$causa_efeito_id, 
	$arquivo_id, 
	$forum_id, 
	$checklist_id, 
	$agenda_id, 
	$agrupamento_id, 
	$patrocinador_id, 
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id);



$inicio_hora = config('cal_dia_inicio');
$fim_hora = config('cal_dia_fim');
$tf = $Aplic->getPref('formatohora');
$df = '%d/%m/%Y';
$tipos = getSisValor('TipoEvento');
$html = '<table cellspacing=0 cellpadding="2" border=0 width="100%" class="tbl1">';
$html .= '<tr><th>'.dica('Data - Hora', 'A data e hora do in�cio e t�rmino do evento.').'Data'.dicaF().'</th><th>'.dica('Tipo', 'O tipo de evento.').'Tipo'.dicaF().'</th><th>'.dica('Evento', 'O nome do evento.').'Evento'.dicaF().'</th></tr>';
$qnt=0;
foreach ($eventos as $linha) {
	$qnt++;
	$html .= '<tr>';
	$inicio = new CData($linha['evento_inicio']);
	$fim = new CData($linha['evento_fim']);
	$html .= '<td width="25%" nowrap="nowrap">'.$inicio->format($df.' '.$tf).'&nbsp;-&nbsp;'.$fim->format($df.' '.$tf).'</td>';
	$html .= '<td width="10%" nowrap="nowrap">'.imagem('icones/evento'.$linha['evento_tipo'].'.png', 'Tipo de Evento', 'Cada evento tem um gr�fico diferente para facilitar a identifica��o visual.').'&nbsp;<b>'.$tipos[$linha['evento_tipo']].'</b></td>';
	$html .= '<td>'.link_evento($linha['evento_id']).'</td></tr>';
	}
if (!$qnt) $html .= '<tr><td colspan="3"><p>Nenhum evento encontrado.</p></td></tr>';	
$html .= '</table>';
echo $html;
?>