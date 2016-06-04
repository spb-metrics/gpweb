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

global $perms, $usuario_id, $podeEditar, $tab, $podeExcluir, $podeEditar;

$sql = new BDConsulta;

$coletivo=($Aplic->usuario_lista_grupo && $Aplic->usuario_lista_grupo!=$usuario_id);


$podeEditar = $podeEditar;

$tarefa_ordenar_item1 = getParam($_REQUEST, 'tarefa_ordenar_item1', '');
$tarefa_ordenar_tipo1 = getParam($_REQUEST, 'tarefa_ordenar_tipo1', '');
$tarefa_ordenar_item2 = getParam($_REQUEST, 'tarefa_ordenar_item2', '');
$tarefa_ordenar_tipo2 = getParam($_REQUEST, 'tarefa_ordenar_tipo2', '');
$tarefa_ordenar_ordem1 = intval(getParam($_REQUEST, 'tarefa_ordenar_ordem1', 0));
$tarefa_ordenar_ordem2 = intval(getParam($_REQUEST, 'tarefa_ordenar_ordem2', 0));

$sql->adTabela('tarefas', 'ta');
$sql->esqUnir('projetos', 'pr','pr.projeto_id=tarefa_projeto');
$sql->esqUnir('tarefa_designados', 'td','td.tarefa_id = ta.tarefa_id');
$sql->esqUnir('usuario_tarefa_marcada', 'tp', 'tp.tarefa_id = ta.tarefa_id and tp.usuario_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
$sql->adCampo('DISTINCT ta.tarefa_id, tarefa_prioridade, tarefa_percentagem, tarefa_dinamica, tarefa_marcada, tarefa_inicio, tarefa_fim, tarefa_projeto, tarefa_marco, tarefa_duracao, tarefa_acesso');
$sql->adCampo('projeto_nome, pr.projeto_id, projeto_cor');
$sql->adCampo('diferenca_data(tarefa_fim,tarefa_inicio) as dias');
$sql->adCampo('tarefa_marcada');
$sql->adOnde('projeto_template = 0 OR projeto_template IS NULL');
$sql->adOnde('( ta.tarefa_percentagem < 100 OR ta.tarefa_percentagem IS NULL)');
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('(td.usuario_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).' OR tarefa_dono '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).')');
$sql->adOrdem('ta.tarefa_inicio');
$tarefas = $sql->Lista();
$sql->limpar();




for ($j = 0, $j_cmp = count($tarefas); $j < $j_cmp; $j++) {
	if (!$tarefas[$j]['tarefa_fim']) {
		if (!$tarefas[$j]['tarefa_inicio']) {
			$tarefas[$j]['tarefa_inicio'] = null; 
			$tarefas[$j]['tarefa_fim'] = null;
			} 
		else $tarefas[$j]['tarefa_fim'] = calcFimPorInicioEDuracao($tarefas[$j]);
		}
	}

$prioridades = array('2' =>'muito alta', '1' => 'alta', '0' => 'normal', '-1' => 'baixa', '-2' => 'muito baixa');
$tipoDuracao = getSisValor('TipoDuracaoTarefa');

echo '<form name="frm_botoes" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="tab" value="'.$tab.'" />';
echo '<input type="hidden" name="mostrar_form" value="1" />';
echo '</form>';

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<form name="frm_tarefas" id="frm_tarefas" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<tr>';
echo '<th width="10">&nbsp;</th>';
echo '<th width="10">'.dica('Marcar', 'Clique no globo abaixo para marcar ou desmarcar '.$config['genero_tarefa'].' '.$config['tarefa'].'.<p> A marcação tem a finalidade de chamar a atenção, visualmente, para uma determinad'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'M'.dicaF().'</th>';
echo '<th width="20" colspan="2">'.dica('Porcentual d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Realizada', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' pelos percentuais realizados.').ordenar_por_item_titulo('Feito', 'tarefa_percentagem', SORT_NUMERIC, '&a=parafazer').dicaF().'</th>';
echo '<th width="15" align="center">'.dica('Prioridade', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo por prioridade.').ordenar_por_item_titulo('P', 'tarefa_prioridade', SORT_NUMERIC, '&a=parafazer').dicaF().'</th>';
echo '<th>'.dica('Nome d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' pelo nome das mesmas.').ordenar_por_item_titulo('Tarefa', 'tarefa_nome', SORT_STRING, '&a=parafazer').dicaF().'</th>';
echo '<th>'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' pel'.$config['genero_projeto'].'s '.$config['projetos'].' aos quais pertencem.').ordenar_por_item_titulo('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'tarefa_projeto', SORT_NUMERIC, '&a=parafazer').dicaF().'</th>';
echo '<th nowrap="nowrap" width="140">'.dica('Data de Início', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pela data de início das mesmas.').ordenar_por_item_titulo('Data de Início', 'tarefa_inicio', SORT_NUMERIC, '&a=parafazer').dicaF().'</th>';
echo '<th nowrap="nowrap">'.dica('Duração', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pela duração das mesmas ').ordenar_por_item_titulo('Duração', 'tarefa_duracao', SORT_NUMERIC, '&a=parafazer').dicaF().'</th>';
echo '<th nowrap="nowrap" width="140">'.dica('Término', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pelo término das mesmas.').ordenar_por_item_titulo('Término', 'tarefa_fim', SORT_NUMERIC, '&a=parafazer').dicaF().'</th>';
echo '<th nowrap="nowrap">'.dica('Fazer em', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pelo tempo necessário para realiza-las.').ordenar_por_item_titulo('Fazer em', 'tarefa_fazer_em', SORT_NUMERIC, '&a=parafazer').dicaF().'</th>';
echo '<th nowrap="nowrap">'.dica('Dias', 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' abaixo pela duração em dias.').ordenar_por_item_titulo('Dias', 'dias', SORT_NUMERIC, '&a=parafazer').dicaF().'</th>';

if (config('editar_designado_diretamente')) echo '<th width="0">&nbsp;</th>';
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
$saida='';
foreach ($tarefas as $tarefa) $saida.=mostrarTarefa($tarefa, 0, false, true);
echo $saida;
if (!count($tarefas)) echo '<tr><td colspan="20">Nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' encontrad'.$config['genero_tarefa'].'.</td></tr>';
echo '</table>';
echo '</form>';
echo '<table cellpadding="0" cellspacing=0 class="std" width="100%"><tr><td>&nbsp; &nbsp;</td><td><table><tr>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffffff">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' Futur'.$config['genero_tarefa'], ucfirst($config['tarefa']).' futur'.$config['genero_tarefa'].' é '.$config['genero_tarefa'].' em que a data de ínicio  ainda não ocorreu.').ucfirst($config['tarefa']).' futura'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#e6eedd">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' Iniciad'.$config['genero_tarefa'].' e Dentro do Prazo', ucfirst($config['tarefa']).' iniciad'.$config['genero_tarefa'].' e dentro do prazo é '.$config['genero_tarefa'].' em que a data de ínicio  já ocorreu, e a mesma já está acima de 0% executada, entretanto ainda não se chegou na data de término.').'Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffeebb">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' que Deveria ter Iniciad'.$config['genero_tarefa'], ucfirst($config['tarefa']).' futur'.$config['genero_tarefa'].' é '.$config['genero_tarefa'].' em que a data de ínicio já ocorreu, entretanto ainda se encontra em 0% executad'.$config['genero_tarefa'].'.').'Deveria ter iniciada'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#cc6666">&nbsp; &nbsp;</td><td>'.dica(ucfirst($config['tarefa']).' em Atraso', ucfirst($config['tarefa']).' em atraso é '.$config['genero_tarefa'].' em que a data de término já ocorreu, entretanto ainda não se encontra em 100% executad'.$config['genero_tarefa'].'.').'Em atraso'.dicaF().'</td>';	
echo '</tr></table></td></tr></table>';
if (isset($_REQUEST['usuario_id']))	echo '<script LANGUAGE="javascript">document.frm_botoes.submit();</script>'; 
?>

<script language="javascript">

function iluminar_tds(linha,alto,id){
	if(document.getElementsByTagName){
		var tcs=linha.getElementsByTagName('td');
		var nome_celula='';
		if(!id)check=false;
		else{
			var f=eval('document.frm_tarefas');
			var check=eval('f.selecionado_tarefa_'+id+'.checked')
			}
		for(var j=0,j_cmp=tcs.length;j<j_cmp;j+=1){
			nome_celula=eval('tcs['+j+'].id');
			if(!(nome_celula.indexOf('ignore_td_')>=0)){
				if(alto==3) tcs[j].style.background='#FFFFCC';
				else if(alto==2||check)
				tcs[j].style.background='#FFCCCC';
				else if(alto==1) tcs[j].style.background='#FFFFCC';
				else tcs[j].style.background='#FFFFFF';
				}
			}
		}
	}
	
var estah_marcado;

function selecionar_caixa(box,id,linha_id,nome_formulario){
	var f=eval('document.'+nome_formulario);
	var check=eval('f.'+box+'_'+id+'.checked');
	boxObj=eval('f.elements["'+box+'_'+id+'"]');
	if((estah_marcado&&boxObj.checked&&!boxObj.disabled)||(!estah_marcado&&!boxObj.checked&&!boxObj.disabled)){linha=document.getElementById(linha_id);
		boxObj.checked=true;
		iluminar_tds(linha,2,id);
		}
	else if((estah_marcado&&!boxObj.checked&&!boxObj.disabled)||(!estah_marcado&&boxObj.checked&&!boxObj.disabled)){
		linha=document.getElementById(linha_id);
		boxObj.checked=false;
		iluminar_tds(linha,3,id);
		}
	}	

</script>