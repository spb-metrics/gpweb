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

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente');

ini_set('memory_limit', $config['resetar_limite_memoria']);

include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_gantt'));

global $a, $chamador, $localidade_tipo_caract, $mostrarLegendas, $mostrarTrabalho, $usuario_id, $cia_id, $Aplic;


$caminho_critico=getParam($_REQUEST, 'caminho_critico', 0);
$somente_marco=getParam($_REQUEST, 'somente_marco', 0);
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$baseline_id=getParam($_REQUEST, 'baseline_id', 0);
$baseline=($baseline_id ? 'baseline_' : '');


if ($tarefa_id) {
	$obj = new CTarefa(($baseline_id ? true : false), true);
	$obj->load($tarefa_id);
	}




$q = new BDConsulta;

if ($baseline_id){
	$q->adTabela('baseline');
	$q->adCampo('baseline_data');
	$q->adOnde('baseline_id = '.$baseline_id);
	$data_baseline = $q->Resultado();
	$q->limpar();
	$data_baseline='Baseline de '.retorna_data($data_baseline).' hs';
	}
else $data_baseline='';

require_once $Aplic->getClasseModulo('projetos');

$chamador = getParam($_REQUEST, 'chamador', '');
$projeto = new CProjeto;
$tarefasCriticas = ($projeto_id > 0) ? $projeto->getTarefasCriticas($projeto_id) : null;
$tarefasCriticasInvertidas = ($projeto_id > 0) ? getTarefasCriticasInvertidas($projeto_id) : null;


$q->adTabela($baseline.'projetos', 'pr');
$q->adCampo('pr.projeto_id, projeto_cor, projeto_nome, MIN(tarefa_inicio) AS projeto_data_inicio, MAX(tarefa_fim) AS projeto_data_fim');
$q->adUnir($baseline.'tarefas', 't1', 'pr.projeto_id = t1.tarefa_projeto');
if ($projeto_id) $q->adOnde('pr.projeto_id = '.(int)$projeto_id);
if ($baseline_id) $q->adOnde('pr.baseline_id = '.(int)$baseline_id);
if ($baseline_id) $q->adOnde('t1.baseline_id = '.(int)$baseline_id);
if ($tarefa_id) $q->adOnde('t1.tarefa_id IN ('.($obj->tarefas_subordinadas ? $obj->tarefas_subordinadas : $tarefa_id).')');
$q->adGrupo('pr.projeto_id');
$q->adOrdem('projeto_nome');
$projetos = $q->ListaChave('projeto_id');
$q->limpar();

$q->adTabela($baseline.'tarefas', 't');
$q->esqUnir($baseline.'projetos', 'p', 'projeto_id = t.tarefa_projeto');
$q->esqUnir($baseline.'tarefa_designados', 'ut','ut.tarefa_id = t.tarefa_id');
$q->adCampo('t.tarefa_id, tarefa_superior, tarefa_nome, tarefa_inicio, tarefa_fim, tarefa_duracao, tarefa_duracao_tipo, tarefa_prioridade, tarefa_percentagem, tarefa_ordem, tarefa_projeto, tarefa_marco, projeto_nome, tarefa_dinamica');
if ($baseline_id) $q->adOnde('t.baseline_id = '.(int)$baseline_id);
if ($baseline_id) $q->adOnde('p.baseline_id = '.(int)$baseline_id);
if ($projeto_id) $q->adOnde('tarefa_projeto = '.(int)$projeto_id);
if ($usuario_id) $q->adOnde('(ut.usuario_id = '.(int)$usuario_id.' OR projeto_responsavel ='.(int)$usuario_id.')');
if ($cia_id) $q->adOnde('(p.projeto_cia = '.(int)$cia_id);
if ($somente_marco) $q->adOnde('tarefa_marco=1');
if ($tarefa_id) $q->adOnde('t.tarefa_id IN ('.($obj->tarefas_subordinadas ? $obj->tarefas_subordinadas : $tarefa_id).')');
$q->adOrdem('projeto_id, '.($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio, tarefa_nome');
$proTarefas = $q->ListaChave('tarefa_id');
$q->limpar();

//Vetor de tarefas do caminho crítico
$maior_data='';
$ultima_tarefa=0;
foreach($proTarefas as $tarefa_analisada) {
	if ($tarefa_analisada['tarefa_fim']> $maior_data){
		$maior_data=$tarefa_analisada['tarefa_fim'];
		$ultima_tarefa=$tarefa_analisada['tarefa_id'];
		}
	}

$saida=array($ultima_tarefa => 1);
dependencias($ultima_tarefa);






$orrarr[] = array('tarefa_id' => 0, 'order_up' => 0, 'order' => '');
$fim_max = null;
$inicio_min = date('Y-m-d H:i:s');
foreach ($proTarefas as $linha) {
	if (!$linha['tarefa_inicio']) {
		if (!$linha['tarefa_fim']) {
			$dataHoje = new CData();
			$linha['tarefa_inicio'] = $dataHoje->format(FMT_TIMESTAMP_DATA);
			} 
		else $linha['tarefa_inicio'] = $linha['tarefa_fim'];
		}
	$tsd = new CData($linha['tarefa_inicio']);
	if ($tsd->before(new CData($inicio_min))) $inicio_min = $linha['tarefa_inicio'];
	if (!$linha['tarefa_fim']) {
		if ($linha['tarefa_duracao']) $linha['tarefa_fim'] = db_unix2dateTime(db_dateTime2unix($linha['tarefa_inicio']) + 86400 * converterParaDias($linha['tarefa_duracao'], $linha['tarefa_duracao_tipo']));
		else {
			$dataHoje = new CData();
			$linha['tarefa_fim'] = $dataHoje->format(FMT_TIMESTAMP_DATA);
			}
		}
	$ted = new CData($linha['tarefa_fim']);
	if ($ted->after(new CData($fim_max))) $fim_max = $linha['tarefa_fim'];
	$projetos[$linha['tarefa_projeto']]['tarefas'][] = $linha;
	}

$largura = getParam($_REQUEST, 'width', 600);

if ($largura>1400) $largura=1400;

$inicio_min = substr($tarefasCriticasInvertidas[0]['tarefa_inicio'], 0, 10);
if ($inicio_min == '0000-00-00' || !$inicio_min) $inicio_min = $projetos[$projeto_id]['projeto_data_inicio'];
$fim_max = substr($tarefasCriticas[0]['tarefa_fim'], 0, 10);
if ($fim_max == '0000-00-00' || !$fim_max) $fim_max = $projetos[$projeto_id]['projeto_data_fim'];
$data_inicio = getParam($_REQUEST, 'data_inicio', $inicio_min);
$data_fim = getParam($_REQUEST, 'data_fim', $fim_max);
$quantidade = 0;
$grafico = new GanttGraph($largura);
$grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HDAY | GANTT_HWEEK);
$grafico->SetFrame(false);
$grafico->SetBox(true, array(0, 0, 0), 2);
$grafico->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY);

//$vetorGantt = array();

if ($data_inicio && $data_fim) $grafico->SetDateRange($data_inicio, $data_fim);
$grafico->scale->actinfo->SetFont(FF_FONT1,FS_BOLD);
$grafico->scale->actinfo->SetColor('darkgray');
if ($mostrarTrabalho == '1') $grafico->scale->actinfo->SetColTitles(array('nome d'.$config['genero_tarefa'].' '.$config['tarefa'], 'trabalho', '%', 'inicio', 'término'), array(100, 30, 40, 40));
else $grafico->scale->actinfo->SetColTitles(array('nome d'.$config['genero_tarefa'].' '.$config['tarefa'], 'dur.', '%', 'inicio', 'término'), array(100, 30, 40, 40));
if ($data_inicio && $data_fim) {
	$minuto_d_inicio = new CData($data_inicio);
	$minuto_d_fim = new CData($data_fim);
	} 
else {
	$minuto_d_inicio = new CData();
	$minuto_d_fim = new CData();
	$d_inicio = new CData();
	$d_fim = new CData();
	for ($i = 0, $i_cmp = count($vetorGantt); $i < $i_cmp; $i++) {
		$vetor = $vetorGantt[$i][0];
		$inicio = substr($vetor['tarefa_inicio'], 0, 10);
		$fim = substr($vetor['tarefa_fim'], 0, 10);
		$d_inicio->Date($inicio);
		$d_fim->Date($fim);
		if ($i == 0) {
			$minuto_d_inicio = $d_inicio->duplicar();
			$minuto_d_fim = $d_fim->duplicar();
			} 
		else {
			if ($d_inicio->compare($minuto_d_inicio, $d_inicio) > 0) $minuto_d_inicio = $d_inicio->duplicar();
			if ($d_inicio->compare($minuto_d_fim, $d_fim) < 0) $minuto_d_fim = $d_fim->duplicar();
			}
		}
	}

$nome_projeto= (strlen($projetos[$projeto_id]['projeto_nome']) > 53 ? substr($projetos[$projeto_id]['projeto_nome'], 0, 52).'...' : $projetos[$projeto_id]['projeto_nome']);


$dia_diferenca = $minuto_d_inicio->dataDiferenca($minuto_d_fim);
if ($dia_diferenca > 240) $grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH);
elseif ($dia_diferenca > 90) {
	$grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HWEEK);
	$grafico->scale->week->SetStyle(WEEKSTYLE_WNBR);
	}
if ($chamador!='parafazer'){	
	
	if ($data_baseline) $grafico->scale->tableTitle->Set($data_baseline);
	else $grafico->scale->tableTitle->Set($nome_projeto);
	
	if ($dia_diferenca > 240) $grafico->scale->tableTitle->SetFont(FF_FONT1, FS_BOLD, 8);
	else $grafico->scale->tableTitle->SetFont(FF_FONT1, FS_BOLD, 12);
	
	$grafico->scale->SetTableTitleBackground('#FFFFFF');
	$grafico->scale->tableTitle->SetColor('black');
	}
else{
	$grafico->scale->tableTitle->Set(ucfirst($config['tarefa']).' a fazer');
	if ($dia_diferenca > 240) $grafico->scale->tableTitle->SetFont(FF_FONT1, FS_BOLD, 8);
	else $grafico->scale->tableTitle->SetFont(FF_FONT1, FS_BOLD, 12);
	$grafico->scale->SetTableTitleBackground('white');
	$grafico->scale->tableTitle->SetColor('black');
	}

$grafico->scale->tableTitle->Show(true);

reset($projetos);
foreach ($projetos as $p) {
	$tnums = count($p['tarefas']);
	for ($i = 0; $i < $tnums; $i++) {
		$t = $p['tarefas'][$i];
		if (!$t['tarefa_superior'] || $t['tarefa_superior'] == $t['tarefa_id']) {
			mostrargTarefa($t);
			achargSubordinada($p['tarefas'], $t['tarefa_id']);
			}
		}
	}


$esconder_grupos_tarefa = false;
if ($esconder_grupos_tarefa) {
	for ($i = 0, $i_cmp = count($vetorGantt); $i < $i_cmp; $i++) {
		if ($i != count($vetorGantt) - 1 && $vetorGantt[$i + 1][1] > $vetorGantt[$i][1]) {
			array_splice($vetorGantt, $i, 1);
			continue;
			}
		}
	}
$linha = 0;


for ($i = 0, $i_cmp = count($vetorGantt); $i < $i_cmp; $i++) {
	$vetor = $vetorGantt[$i][0];
	$nivel = $vetorGantt[$i][1];
	if ($esconder_grupos_tarefa) $nivel = 0;
	$nome = $vetor['tarefa_nome'];
	if ($localidade_tipo_caract == 'utf-8' && function_exists('utf8_decode')) $nome = utf8_decode($nome);
	$nome = strlen($nome) > 34 ? substr($nome, 0, 33).'.' : $nome;
	$nome = str_repeat(' ', $nivel).$nome;
	if ($chamador == 'todo') {
		
		if ($data_baseline) $pnome =$data_baseline;
		else $pnome = $vetor['projeto_nome'];
		if ($localidade_tipo_caract == 'utf-8') {
			if (function_exists('mb_substr')) $pnome = mb_strlen($pnome) > 14 ? mb_substr($pnome, 0, 5).'...'.mb_substr($pnome, -5, 5) : $pnome;
			elseif (function_exists('utf8_decode')) $pnome = utf8_decode($pnome);
			} 
		else $pnome = strlen($pnome) > 14 ? substr($pnome, 0, 5).'...'.substr($pnome, -5, 5) : $pnome;
		}
	$inicio = $vetor['tarefa_inicio'];
	$data_fim = $vetor['tarefa_fim'];
	$data_fim = new CData($data_fim);
	$fim = $data_fim->getData();
	$inicio = new CData($inicio);
	$inicio = $inicio->getData();
	$progresso = $vetor['tarefa_percentagem'] + 0;
	if ($progresso > 100) $progresso = 100;
	elseif ($progresso < 0) $progresso = 0;
	$estados = ($vetor['tarefa_marco'] ? 'm' : '');
	$leg = '';
	if (!$inicio || $inicio == '0000-00-00') {
		$inicio = !$fim ? date('Y-m-d') : $fim;
		$leg .= '(sem início)';
		}
	if (!$fim) {
		$fim = $inicio;
		$leg .= ' (sem término)';
		} 
	else $leg = '';
	$legenda = '';
	if ($mostrarLegendas == '1') {
		
		$q->adTabela($baseline.'tarefa_designados', 'ut');
		$q->adTabela('usuarios', 'u');
		$q->adTabela('contatos', 'c');
		$q->adCampo('ut.tarefa_id, u.usuario_login, ut.perc_designado');
		$q->adCampo('c.contato_posto, c.contato_nomeguerra');
		$q->adOnde('u.usuario_id = ut.usuario_id');
		$q->adOnde('u.usuario_contato = c.contato_id');
		$q->adOnde('ut.tarefa_id = '.(int)$vetor['tarefa_id']);
		if ($baseline_id) $q->adOnde('ut.baseline_id = '.(int)$baseline_id);
		$res = $q->Lista();
		$q->limpar();
		
		foreach ($res as $rw) {
			switch ($rw['perc_designado']) {
				case 100:
					$legenda = $legenda.$rw['contato_posto'].' '.$rw['contato_nomeguerra'].';';
					break;
				default:
					$legenda = $legenda.$rw['contato_posto'].' '.$rw['contato_nomeguerra'].' ['.$rw['perc_designado'].'%];';
					break;
				}
			}
		$legenda = substr($legenda, 0, strlen($legenda) - 1);
		}
	//marco de projeto	
	if ($estados == 'm') {
		$inicio = new CData($inicio);
		$s = $inicio->format('%d/%m/%Y');
		if ($chamador == 'todo') $barra = new MileStone($linha++, array($nome, $pnome, '','', substr($s, 0, 10), substr($s, 0, 10)), substr($vetor['tarefa_inicio'], 0, 10), $s);
		else $barra = new MileStone($linha++, array($nome, '','', substr($s, 0, 10), substr($s, 0, 10)), substr($vetor['tarefa_inicio'], 0, 10), $s);
		$barra->title->SetFont(FF_FONT1, FS_NORMAL, 8);
		if ($mostrarLegendas == '1') $legenda = $inicio->format('%d/%m/%Y');
		$barra->title->SetColor('#CC0000');
		$grafico->Add($barra);
		} 
	else {
		$tipo = $vetor['tarefa_duracao_tipo'];
		$dur = number_format($vetor['tarefa_duracao']/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8), 1, ',', '.');
		$datafim = new CData($fim);
		$datainicio = new CData($inicio);
		$perc=number_format($vetor['tarefa_percentagem'], 1, ',', '.');
		if ($chamador == 'todo') $barra = new GanttBar($linha++, array($nome, $pnome, $dur, $perc, $datainicio->format('%d/%m/%Y'), $datafim->format('%d/%m/%Y')), $inicio, $fim, $leg, $vetor['tarefa_dinamica'] == 1 ? 0.1 : 0.6);
		else $barra = new GanttBar($linha++, array($nome, $dur, $perc, $datainicio->format('%d/%m/%Y'), $datafim->format('%d/%m/%Y')), $inicio, $fim, $leg, $vetor['tarefa_dinamica'] == 1 ? 0.1 : 0.6);
		
		$barra->progress->Set(min(($progresso / 100), 1));
		$barra->title->SetFont(FF_FONT1, FS_NORMAL, 8);
		if ($vetor['tarefa_dinamica'] == 1) {
			$barra->title->SetFont(FF_FONT1, FS_NORMAL, 8);
			$barra->title->SetColor('#0101a9');
			$barra->rightMark->Show();
			$barra->rightMark->SetType(MARK_RIGHTTRIANGLE);
			$barra->rightMark->SetWidth(3);
			$barra->rightMark->SetColor('black');
			$barra->rightMark->SetFillColor('black');
			$barra->leftMark->Show();
			$barra->leftMark->SetType(MARK_LEFTTRIANGLE);
			$barra->leftMark->SetWidth(3);
			$barra->leftMark->SetColor('black');
			$barra->leftMark->SetFillColor('black');
			$barra->SetPattern(BAND_SOLID, 'black');
			}
		}
	$barra->caption = new TextProperty($legenda);
	$barra->caption->Align('left', 'center');
	$barra->caption->SetFont(FF_FONT1, FS_NORMAL, 8);
	if ($progresso >= 100 && $data_fim->isPast() && get_class($barra) == 'ganttbar') {
		$barra->caption->SetColor('darkgray');
		$barra->title->SetColor('darkgray');
		$barra->setCor('darkgray');
		$barra->SetFillColor('darkgray');
		$barra->SetPattern(BAND_SOLID, 'gray');
		$barra->progress->SetFillColor('darkgray');
		$barra->progress->SetPattern(BAND_SOLID, 'gray', 98);
		}

	//cor do caminho crítico
	if ($caminho_critico && isset($saida[$vetor['tarefa_id']]) && $saida[$vetor['tarefa_id']] && (get_class($barra) == 'GanttBar')){
		
		$barra->SetColor('red');
		$barra->SetFillColor('red');
		}
	

	$q->adTabela($baseline.'tarefa_dependencias');
	$q->adCampo('dependencias_tarefa_id, tipo_dependencia');
	$q->adOnde('dependencias_req_tarefa_id='.$vetor['tarefa_id']);
	if ($baseline_id) $q->adOnde('baseline_id = '.(int)$baseline_id);
	$comando_sql = $q->Lista();
	$q->limpar();
		
	foreach ($comando_sql as $dep) {
		for ($d = 0, $d_cmp = count($vetorGantt); $d < $d_cmp; $d++) {
			if ($vetorGantt[$d][0]['tarefa_id'] == $dep['dependencias_tarefa_id']) {
				if ($dep['tipo_dependencia']=='TT') $barra->SetConstrain($d, CONSTRAIN_ENDEND, 'red');
				elseif ($dep['tipo_dependencia']=='TI') $barra->SetConstrain($d, CONSTRAIN_ENDSTART, 'red');
				elseif ($dep['tipo_dependencia']=='II') $barra->SetConstrain($d, CONSTRAIN_STARTSTART, 'red');
				elseif ($dep['tipo_dependencia']=='IT') $barra->SetConstrain($d, CONSTRAIN_STARTEND, 'red');
				}
			}
		}
	
	//adicionar dependencia IT	
	/*$q->adTabela($baseline.'tarefa_dependencias');
	$q->adCampo('dependencias_req_tarefa_id');
	$q->adOnde('dependencias_tarefa_id='.$vetor['tarefa_id']);
	$q->adOnde('tipo_dependencia=\'IT\'');
	if ($baseline_id) $q->adOnde('baseline_id = '.(int)$baseline_id);
	$comando_sql = $q->Lista();
	$q->limpar();
	foreach ($comando_sql as $dep) {
		for ($d = 0, $d_cmp = count($vetorGantt); $d < $d_cmp; $d++) {
			if ($vetorGantt[$d][0]['tarefa_id'] == $dep['dependencias_req_tarefa_id']) {
				$barra->SetConstrain($d, CONSTRAIN_STARTEND, 'red');
				}
			}
		}*/
	
	$grafico->Add($barra);
	}
	
	
	
$hoje = new CData();
$linhaVert = new GanttVLine($hoje->format(FMT_TIMESTAMP_DATA), 'Hoje');
$linhaVert->title->SetFont(FF_FONT1, FS_BOLD, 10);
$grafico->Add($linhaVert);

$grafico->Stroke();
$grafico->img->SetImgFormat('png');
header ("Content-type: image/png");
function dependencias($tarefa_id){
	global $q, $baseline,$baseline_id, $saida;
	
	$q->adTabela($baseline.'tarefa_dependencias');
	$q->adCampo('dependencias_req_tarefa_id');
	$q->adOnde('dependencias_tarefa_id = '.(int)$tarefa_id);
	if ($baseline_id) $q->adOnde('baseline_id = '.(int)$baseline_id);
	$lista= $q->carregarColuna();
	$q->limpar();
	foreach ((array)$lista as $chave => $valor) $saida[$valor]=1;
	foreach ((array)$lista as $chave => $valor) dependencias($valor);
	}
	
function mostrargTarefa(&$vetor, $nivel = 0) {
	global $vetorGantt;
	$vetorGantt[] = array($vetor, $nivel);
	}
function achargSubordinada(&$tarr, $superior, $nivel = 0) {
	global $projetos;
	$nivel = $nivel + 1;
	$n = count($tarr);
	for ($x = 0; $x < $n; $x++) {
		if ($tarr[$x]['tarefa_superior'] == $superior && $tarr[$x]['tarefa_superior'] != $tarr[$x]['tarefa_id']) {
			mostrargTarefa($tarr[$x], $nivel);
			achargSubordinada($tarr, $tarr[$x]['tarefa_id'], $nivel);
			}
		}
	}


function getTarefasCriticasInvertidas($projeto_id = null, $limite = 1) {
	global $baseline, $baseline_id, $Aplic;
	if (!$projeto_id) {
		$resultado = array();
		$resultado[0]['tarefa_fim'] = null;
		return $resultado;
		} 
	else {
		$q = new BDConsulta;
		$q->adTabela($baseline.'tarefas');
		$q->adOnde('tarefa_projeto = '.(int)$projeto_id .' AND tarefa_fim IS NOT NULL');
		if ($baseline_id) $q->adOnde('baseline_id = '.(int)$baseline_id);
		$q->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC');
		$q->setLimite($limite);
		$resultado=$q->Lista();
		$q->limpar();
		return $resultado;
		}
	}
?>