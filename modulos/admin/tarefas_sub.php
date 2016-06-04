<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $mostrarCaixachecarEditar, $tarefas, $prioridades;
global $m, $a, $data, $mostrar_marcada, $mostra_projeto_completo, $mostraProjetosEspera, $mostrar_tarefa_dinamica, $mostrar_tarefa_baixa, $mostrar_sem_data, $usuario_id, $tarefa_tipo;
global $tarefa_ordenar_item1, $tarefa_ordenar_tipo1, $tarefa_ordenar_ordem1;
global $tarefa_ordenar_item2, $tarefa_ordenar_tipo2, $tarefa_ordenar_ordem2;
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0>';
echo '<form name="frm_botoes" method="post">';
echo '<input type="hidden" name="m" value="admin" />';
echo '<input type="hidden" name="a" value="ver_usuario" />';
echo '<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';

echo '<input type="hidden" name="mostrar_form" value="1" />';
echo '<tr><td align="left">';
echo dica('Mostrar', 'Marque nas caixas � direita quais '.$config['genero_tarefa'].'s '.$config['tarefas'].' que deseja visualizar').'Mostrar:'.dicaF();
echo '</td><td>'.dica('Mostrar apenas '.$config['genero_tarefa'].'s '.$config['tarefas'].' marcadas', 'Marque para mostrar apenas '.$config['genero_tarefa'].'s '.$config['tarefas'].' marcadas.<ul><li>'.ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' marcadas s�o aquelas com um globo verde brilhante.</li></ul>').'<label for="mostrar_marcada">S� marcadas</label>'.dicaF().'<input type="checkbox" name="mostrar_marcada" id="mostrar_marcada" onclick="document.frm_botoes.submit()" '.($mostrar_marcada ? 'checked="checked"' : '').' />';
echo '</td><td>'.dica('Mostrar '.$config['genero_projeto'].'s '.$config['projetos'].' completados', 'Marque para mostrar tamb�m '.$config['genero_projeto'].'s '.$config['projetos'].' completad'.$config['genero_projeto'].'s.<ul><li>'.ucfirst($config['genero_projeto']).'s '.$config['projetos'].' completad'.$config['genero_projeto'].'s s�o aquelas com todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' 100%.</li></ul>').'<label for="mostra_projeto_completo">'.ucfirst($config['projetos']).' completados</label>'.dicaF().'<input type="checkbox" name="mostra_projeto_completo" id="mostra_projeto_completo" onclick="document.frm_botoes.submit()" '.($mostra_projeto_completo ? 'checked="checked"' : '').' />';
echo dica('Mostrar '.$config['genero_tarefa'].'s '.$config['tarefas'].' completadas', 'Marque para mostrar tamb�m '.$config['genero_tarefa'].'s '.$config['tarefas'].' completadas.').'<label for="mesmo_completa">'.ucfirst($config['tarefas']).' completad'.$config['genero_tarefa'].'s</label>'.dicaF().'<input type="checkbox" name="mesmo_completa" id="mesmo_completa" onclick="document.frm_botoes.submit()" '.($mesmo_completa ? 'checked="checked"' : '').' />';
echo '</td><td>'.dica('Mostrar '.$config['genero_projeto'].'s '.$config['projetos'].' em espera', 'Marque para mostrar tamb�m '.$config['genero_projeto'].'s '.$config['projetos'].' em espera.').'<label for="mostrar_proj_aguardando">Em Espera</label>'.dicaF().'<input type="checkbox" name="mostrar_proj_aguardando" id="mostrar_proj_aguardando" onclick="document.frm_botoes.submit()" '.($mostrar_proj_aguardando? 'checked="checked"' : '').' />';
echo '</td><td>'.dica('Mostrar '.$config['genero_tarefa'].'s '.$config['tarefas'].' din�micas', 'Marque para mostrar tamb�m '.$config['genero_tarefa'].'s '.$config['tarefas'].' din�micas.<ul><li>'.ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' din�micas s�o aquelas que n�o tem exist�ncia pr�pria, mas sim uma representa��o das subtarefas.</li></ul>').'<label for="mostrar_tarefa_dinamica">Din�micas</label>'.dicaF().'<input type="checkbox" name="mostrar_tarefa_dinamica" id="mostrar_tarefa_dinamica" onclick="document.frm_botoes.submit()" '.($mostrar_tarefa_dinamica ? 'checked="checked"' : '').' />';
echo '</td><td>'.dica('Mostrar '.$config['genero_tarefa'].'s '.$config['tarefas'].' com baixa prioridade', 'Marque para mostrar tamb�m '.$config['genero_tarefa'].'s '.$config['tarefas'].' com baixa prioridade.<ul><li>'.ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' com baixa prioridade apresentam uma seta virada para baixo.</li></ul>').'<label for="mostrar_tarefa_baixa">Baixa Prioridade</label>'.dicaF().'<input type="checkbox" name="mostrar_tarefa_baixa" id="mostrar_tarefa_baixa" onclick="document.frm_botoes.submit()" '.($mostrar_tarefa_baixa ? 'checked="checked"' : '').' />';
echo '</td><td>'.dica('Mostrar '.$config['genero_tarefa'].'s '.$config['tarefas'].' com datas em branco', 'Marque para mostrar tamb�m '.$config['genero_tarefa'].'s '.$config['tarefas'].' com datas em branco.').'<label for="mostrar_sem_data">Sem Datas</label>'.dicaF().'<input type="checkbox" name="mostrar_sem_data" id="mostrar_sem_data" onclick="document.frm_botoes.submit()" '.($mostrar_sem_data ? 'checked="checked"' : '').' />';
echo '</td><td>'.dica('Filtro por Tipo de '.ucfirst($config['tarefa']), 'Selecione na caixa � direita para qual tipo de '.$config['tarefa'].' deseja visualizar os resultados.').'Tipo: '.dicaF();	
$tipos = array('' => 'todos') + getSisValor('TipoTarefa');
echo selecionaVetor($tipos, 'tarefa_tipo', 'class="texto" onchange="document.frm_botoes.submit()"', $tarefa_tipo);
echo '</label></td></tr>'; 
echo '</form></table>';
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<form name="form" method="post" action="'.previnirXSS($_SERVER['REQUEST_URI']).'">';
echo '<tr>';
	echo '<th width="10">&nbsp;</th>';
	echo '<th width="10">'.dica('Marcad'.$config['genero_tarefa'], 'Clique no globo abaixo para marcar ou desmascar '.$config['genero_tarefa'].' tarefa.<p> A marca��o tem a finalidade de chamar a aten��o, visualmente, para uma determinada '.$config['tarefa'].'.').'M'.dicaF().'</th>';
	echo '<th width="20" colspan="2">'.dica('Porcentual d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Realizada', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' pelos percentuais realizados.');  ordenar_por_titulo('Feito', 'tarefa_percentagem', SORT_NUMERIC,  'admin', 'a=ver_usuario', $usuario_id).dicaF().'</th>';
	echo '<th width="15" align="center">'.dica('Prioridade', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo por prioridade. H� cinco n�veis de prioridades � saber:</p><ul><li>'.imagem('icones/prioridade+2.gif').' - a mais alta preced�ncia.</li><li>'.imagem('icones/prioridade+1.gif').' - alta preced�ncia.</li><li>'.imagem('icones/prioridade0.gif').' - '.$config['projetos'].' rotineir'.$config['genero_projeto'].'s, normalmente os que n�o tem sucessoras.</li><li>'.imagem('icones/prioridade-1.gif').' - '.$config['projetos'].' rotineir'.$config['genero_projeto'].'s que n�o tenham um impacto significativo nos planos e metas.</li><li>'.imagem('icones/prioridade-2.gif').' - '.$config['projetos'].' que n�o tenham impacto nos planos e metas.</li></ul>');ordenar_por_titulo('P', 'tarefa_prioridade', SORT_NUMERIC,  'admin', 'a=ver_usuario', $usuario_id).dicaF().'</th>';
	echo '<th>'.dica('Nome d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' pelo nome das mesmas.');ordenar_por_titulo(ucfirst($config['tarefa']), 'tarefa_nome', SORT_STRING, 'admin', 'a=ver_usuario', $usuario_id).dicaF().'</th>';
	echo '<th>'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' pel'.$config['genero_projeto'].'s '.$config['projetos'].' que pertencem.');ordenar_por_titulo('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'tarefa_projeto', SORT_NUMERIC,  'admin', 'a=ver_usuario', $usuario_id).dicaF().'</th>';
	echo '<th nowrap="nowrap" width="140">'.dica('In�cio', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pela data de in�cio das mesmas.');ordenar_por_titulo('Data de In�cio', 'tarefa_inicio', SORT_NUMERIC, 'admin', 'a=ver_usuario', $usuario_id).dicaF().'</th>';
	echo '<th nowrap="nowrap">'.dica('Dura��o', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pela dura��o das mesmas ');ordenar_por_titulo('Dura��o', 'tarefa_duracao', SORT_NUMERIC,  'admin', 'a=ver_usuario', $usuario_id).dicaF().'</th>';
	echo '<th nowrap="nowrap" width="140">'.dica('T�rmino', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pelo t�rmino das mesmas.');ordenar_por_titulo('T�rmino', 'tarefa_fim', SORT_NUMERIC, 'admin', 'a=ver_usuario', $usuario_id).dicaF().'</th>';
	echo '<th nowrap="nowrap">'.dica('Fazer em', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pelo tempo necess�rio para realiza-las.');ordenar_por_titulo('Fazer em', 'tarefa_fazer_em', SORT_NUMERIC,  'admin', 'a=ver_usuario', $usuario_id).dicaF().'</th>';
	echo '<th>'.dica('Dias', 'n�mero de dias entre o �nicio e fim d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.');ordenar_por_titulo('Dias', 'dias', SORT_NUMERIC,  'admin', 'a=ver_usuario', $usuario_id).dicaF().'</th>';
	if ($config['editar_designado_diretamente']) echo '<th>&nbsp;</th>';
echo '</tr>';
$agora = new CData();
$df = '%d/%m/%Y';
foreach ($tarefas as $tId => $tarefa) {
	$sinal = 1;
	$inicio = intval($tarefa['tarefa_inicio']) ? new CData($tarefa['tarefa_inicio']) : null;
	$fim = intval($tarefa['tarefa_fim']) ? new CData($tarefa['tarefa_fim']) : null;
	if (!$fim && $inicio) {
		$fim = $inicio;
		$fim->adSegundos($tarefa['tarefa_duracao'] * $tarefa['tarefa_duracao_tipo'] * SEG_HORA);
		}
	if ($fim && $agora->after($fim)) $sinal = -1;
	$dias = $fim ? $agora->dataDiferenca($fim) * $sinal : null;
	$tarefas[$tId]['tarefa_fazer_em'] = $dias;
	}
if ($tarefa_ordenar_item1 != '') {
	if ($tarefa_ordenar_item2 != '' && $tarefa_ordenar_item1 != $tarefa_ordenar_item2) $tarefas = vetor_ordenar($tarefas, $tarefa_ordenar_item1, $tarefa_ordenar_ordem1, $tarefa_ordenar_tipo1, $tarefa_ordenar_item2, $tarefa_ordenar_ordem2, $tarefa_ordenar_tipo2);
	else $tarefas = vetor_ordenar($tarefas, $tarefa_ordenar_item1, $tarefa_ordenar_ordem1, $tarefa_ordenar_tipo1);
	} 
else { 
	for ($j = 0, $j_cmp = count($tarefas); $j < $j_cmp; $j++) {
		if (!$tarefas[$j]['tarefa_fim']) {	
			if (!$tarefas[$j]['tarefa_inicio']) {
				$tarefas[$j]['tarefa_inicio'] = null;
				$tarefas[$j]['tarefa_fim'] = null;
				} 
			else $tarefas[$j]['tarefa_fim'] = calcFimPorInicioEDuracao($tarefas[$j]);
			}
		}
	}
$historico_ativo = false;
$mostrarCaixachecarEditar=false;
$saida='';
foreach ($tarefas as $tarefa) $saida.=mostrarTarefa($tarefa, 0, false, true);
echo $saida;
if (!count($tarefas)) echo '<tr><td colspan="11">Nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' encontrad'.$config['genero_tarefa'].'.</td></tr>';
elseif (!$saida) echo '<tr><td colspan="11"><p>N�o tem autoriza��o para ver nenhuma d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.</p></td></tr>';
echo '</table>';
echo '</form>';
echo '<table width="100%" border=0 cellpadding="2" cellspacing=0 class="std"><tr><td><table><tr><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffffff">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' Futura', ucfirst($config['tarefa']).' futura � aquela em que a data de �nicio da mesma ainda n�o ocorreu.').ucfirst($config['tarefa']).' futura'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#e6eedd">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' Iniciada e Dentro do Prazo', ucfirst($config['tarefa']).' iniciada e dentro do prazo � aquela em que a data de �nicio da mesma j� ocorreu, e a mesma j� est� acima de 0% executada, entretanto ainda n�o se chegou na data de t�rmino.').'Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffeebb">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' que Deveria ter Iniciada', ucfirst($config['tarefa']).' futura � aquela em que a data de �nicio da mesma j� ocorreu, entretanto ainda se encontra em 0% executada.').'Deveria ter iniciada'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#cc6666">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' em Atraso', ucfirst($config['tarefa']).' em atraso � aquela em que a data de t�rmino da mesma j� ocorreu, entretanto ainda n�o se encontra em 100% executada.').'Em atraso'.dicaF().'</td>';
echo '</tr></table></td></tr></table>';
?>