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

global $mostrarCaixachecarEditar, $tarefas, $prioridades;
global $m, $a, $data, $mostrar_marcada, $mostra_projeto_completo, $mostraProjetosEspera, $mostrar_tarefa_dinamica, $mostrar_tarefa_baixa, $mostrar_sem_data, $usuario_id, $dept_id, $tarefa_tipo;
global $tarefa_ordenar_item1, $tarefa_ordenar_tipo1, $tarefa_ordenar_ordem1;
global $tarefa_ordenar_item2, $tarefa_ordenar_tipo2, $tarefa_ordenar_ordem2;

echo '<form name="frm_botoes" method="post">';
echo '<input type="hidden" name="m" value="depts" />';
echo '<input type="hidden" name="a" value="ver" />';
echo '<input type="hidden" name="tab" value="2" />';
echo '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />';

echo '<input type="hidden" name="mostrar_form" value="1" />';
echo '<table width="100%" border=0 cellpadding="1" cellspacing=0>';
echo '<tr><td>';
echo dica('Mostrar apenas '.$config['genero_tarefa'].'s '.$config['tarefas'].' marcadas', 'Marque para mostrar apenas '.$config['genero_tarefa'].'s '.$config['tarefas'].' marcadas.<ul><li>'.ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' marcadas são aquelas com um globo verde brilhante.</li></ul>').'<label for="mostrar_marcada">Só marcadas</label>'.dicaF().'<input type="checkbox" name="mostrar_marcada" id="mostrar_marcada" onclick="document.frm_botoes.submit()" '.($mostrar_marcada ? 'checked="checked"' : '').' /></td><td>';
echo dica('Mostrar '.$config['genero_projeto'].'s '.$config['projetos'].' completad'.$config['genero_projeto'].'s', 'Marque para mostrar também '.$config['genero_projeto'].'s '.$config['projetos'].' completad'.$config['genero_projeto'].'s.<ul><li>'.ucfirst($config['genero_projeto']).'s '.$config['projetos'].' completad'.$config['genero_projeto'].'s são aquelas com todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' 100%.</li></ul>').'<label for="mostra_projeto_completo">'.ucfirst($config['projetos']).' completad'.$config['genero_projeto'].'s</label>'.dicaF().'<input type="checkbox" name="mostra_projeto_completo" id="mostra_projeto_completo" onclick="document.frm_botoes.submit()" '.($mostra_projeto_completo ? 'checked="checked"' : '').' /></td><td>';
echo dica('Mostrar '.$config['genero_tarefa'].'s '.$config['tarefas'].' completadas', 'Marque para mostrar também '.$config['genero_tarefa'].'s '.$config['tarefas'].' completadas.').'<label for="mesmo_completa">'.ucfirst($config['tarefas']).' completad'.$config['genero_tarefa'].'s</label>'.dicaF().'<input type="checkbox" name="mesmo_completa" id="mesmo_completa" onclick="document.frm_botoes.submit()" '.($mesmo_completa ? 'checked="checked"' : '').' /></td><td>';
echo dica('Mostrar '.$config['genero_projeto'].'s '.$config['projetos'].' em espera', 'Marque para mostrar também '.$config['genero_projeto'].'s '.$config['projetos'].' em espera.').'<label for="mostrar_proj_aguardando">Em Espera</label>'.dicaF().'<input type="checkbox" name="mostrar_proj_aguardando" id="mostrar_proj_aguardando" onclick="document.frm_botoes.submit()" '.($mostrar_proj_aguardando? 'checked="checked"' : '').' /></td><td>';
echo dica('Mostrar '.$config['genero_tarefa'].'s '.$config['tarefas'].' dinâmic'.$config['genero_tarefa'].'s', 'Marque para mostrar também '.$config['genero_tarefa'].'s '.$config['tarefas'].' dinâmicas.<ul><li>'.ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' dinâmicas são aquelas que não tem existência própria, mas sim uma representação das subtarefas.</li></ul>').'<label for="mostrar_tarefa_dinamica">Dinâmicas</label>'.dicaF().'<input type="checkbox" name="mostrar_tarefa_dinamica" id="mostrar_tarefa_dinamica" onclick="document.frm_botoes.submit()" '.($mostrar_tarefa_dinamica ? 'checked="checked"' : '').' /></td><td>';
echo dica('Mostrar '.$config['genero_tarefa'].'s '.$config['tarefas'].' com baixa prioridade', 'Marque para mostrar também '.$config['genero_tarefa'].'s '.$config['tarefas'].' com baixa prioridade.<ul><li>'.ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' com baixa prioridade apresentam uma seta virada para baixo.</li></ul>').'<label for="mostrar_tarefa_baixa">Baixa Prioridade</label>'.dicaF().'<input type="checkbox" name="mostrar_tarefa_baixa" id="mostrar_tarefa_baixa" onclick="document.frm_botoes.submit()" '.($mostrar_tarefa_baixa ? 'checked="checked"' : '').' /></td><td>';
echo dica('Mostrar '.$config['genero_tarefa'].'s '.$config['tarefas'].' com datas em branco', 'Marque para mostrar também '.$config['genero_tarefa'].'s '.$config['tarefas'].' com datas em branco.').'<label for="mostrar_sem_data">Sem Datas</label>'.dicaF().'<input type="checkbox" name="mostrar_sem_data" id="mostrar_sem_data" onclick="document.frm_botoes.submit()" '.($mostrar_sem_data ? 'checked="checked"' : '').' /></td><td>';
$tipos = array('' => 'todos') + getSisValor('TipoTarefa');
echo '<label for="teste">'.dica('Filtro por Tipo de '.ucfirst($config['tarefa']), 'Selecione na caixa à direita para qual tipo de '.$config['tarefa'].' deseja visualizar os resultados.').'Tipo: '.dicaF().selecionaVetor($tipos, 'tarefa_tipo', 'class="texto" onchange="document.frm_botoes.submit()"', $tarefa_tipo).'</label></td>';
echo '</tr>'; 
echo '</form></table>';
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<form name="form" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="data" value="'.$data.'" />';
echo '<tr>';
	echo '<th width="10">&nbsp;</th>';
	echo '<th width="10">'.dica('Marcad'.$config['genero_tarefa'], 'Clique no globo abaixo para marcar ou desmascar '.$config['genero_tarefa'].' '.$config['tarefa'].'.<p> A marcação tem a finalidade de chamar a atenção, visualmente, para uma determinad'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'M'.dicaF().'</th>';
	echo '<th width="20" colspan="2">'.dica('Porcentual d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Realizada', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' pelos percentuais realizados.');  ordenar_por_titulo('Feito', 'tarefa_percentagem', SORT_NUMERIC,  'depts', 'a=ver', '', $dept_id).dicaF().'</th>';
	echo '<th width="15" align="center">'.dica('Prioridade', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo por prioridade.');ordenar_por_titulo('P', 'tarefa_prioridade', SORT_NUMERIC,  'depts', 'a=ver', '', $dept_id).dicaF().'</th>';
	echo '<th>'.dica('Nome d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' pelo nome das mesmas.');ordenar_por_titulo(ucfirst($config['tarefa']), 'tarefa_nome', SORT_STRING, 'depts', 'a=ver', '', $dept_id).dicaF().'</th>';
	echo '<th>'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' pel'.$config['genero_projeto'].'s '.$config['projetos'].' que pertencem.');ordenar_por_titulo('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'tarefa_projeto', SORT_NUMERIC,  'depts', 'a=ver', '', $dept_id).dicaF().'</th>';
	echo '<th nowrap="nowrap" width="140">'.dica('Início', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pela data de início das mesmas.');ordenar_por_titulo('Data de Início', 'tarefa_inicio', SORT_NUMERIC, 'depts', 'a=ver', '', $dept_id).dicaF().'</th>';
	echo '<th nowrap="nowrap">'.dica('Duração', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pela duração das mesmas ');ordenar_por_titulo('Duração', 'tarefa_duracao', SORT_NUMERIC,  'depts', 'a=ver', '', $dept_id).dicaF().'</th>';
	echo '<th nowrap="nowrap" width="140">'.dica('Término', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pelo término das mesmas.');ordenar_por_titulo('Término', 'tarefa_fim', SORT_NUMERIC, 'depts', 'a=ver', '', $dept_id).dicaF().'</th>';
	echo '<th nowrap="nowrap">'.dica('Fazer em', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pelo tempo necessário para realiza-las.');ordenar_por_titulo('Fazer em', 'tarefa_fazer_em', SORT_NUMERIC,  'depts', 'a=ver', '', $dept_id).dicaF().'</th>';
echo '</tr>';
$agora = new CData();
$df = '%d/%m/%Y';


if (count($tarefas)<1){
	$q = new BDConsulta;
	$q->adTabela('tarefa_depts');
	$q->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = tarefa_depts.tarefa_id');
	$q->adCampo('tarefas.*, diferenca_data(tarefa_fim,tarefa_inicio) as dias');
	$q->adOnde('departamento_id = '.(int)$dept_id);
	$tarefas = $q->lista();
	$q->limpar();
	}

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
elseif (!$saida) echo '<tr><td colspan="11"><p>Não tem autorização para ver nenhuma d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.</p></td></tr>';
echo '</table></form>';
echo '<table class="std" width="100%"><tr><td><table><tr><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffffff">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' Futura', ucfirst($config['tarefa']).' futura é aquela em que a data de ínicio da mesma ainda não ocorreu.').ucfirst($config['tarefa']).' futura'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#e6eedd">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' Iniciada e Dentro do Prazo', ucfirst($config['tarefa']).' iniciada e dentro do prazo é aquela em que a data de ínicio da mesma já ocorreu, e a mesma já está acima de 0% executada, entretanto ainda não se chegou na data de término.').'Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffeebb">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' que Deveria ter Iniciada', ucfirst($config['tarefa']).' futura é aquela em que a data de ínicio da mesma já ocorreu, entretanto ainda se encontra em 0% executada.').'Deveria ter iniciada'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#cc6666">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' em Atraso', ucfirst($config['tarefa']).' em atraso é aquela em que a data de término da mesma já ocorreu, entretanto ainda não se encontra em 100% executada.').'Em atraso'.dicaF().'</td>';
echo '</tr></table></td></tr></table>';
?>