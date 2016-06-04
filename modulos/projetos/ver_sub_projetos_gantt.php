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
ini_set('memory_limit', $config['resetar_limite_memoria']);
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_gantt'));
global $Aplic, $cia_id, $dept_ids, $secao, $localidade_tipo_caract, $proFiltro, $projetoStatus, $mostrarInativo, $mostrarLegendas, $mostrarTodoGantt, $usuario_id, $projeto_id, $projeto_original_id;
$df = '%d/%m/%Y';
$projetoStatus = getSisValor('StatusProjeto');
$projetoStatus = unirVetores(array('-2' => 'Todos exceto os em execução'), $projetoStatus);
$usuario_id = getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
if ($Aplic->usuario_id == $usuario_id) $projetoStatus = unirVetores(array('-3' => 'Meus '.$config['projetos']), $projetoStatus);
else $projetoStatus = unirVetores(array('-3' => ucfirst($config['projetos']).' d'.$config['genero_usuario'].'s '.$config['usuarios']), $projetoStatus);
$proFiltro = getParam($_REQUEST, 'proFiltro', '0');
$cia_id = getParam($_REQUEST, 'cia_id', 0);
$secao = getParam($_REQUEST, 'secao', 0);
$mostrarLegendas = getParam($_REQUEST, 'mostrarLegendas', 1);
$mostrarInativo = getParam($_REQUEST, 'mostrarInativo', 1);
$original_projeto_id = getParam($_REQUEST, 'original_projeto_id', 1);
$pjobj = new CProjeto;
$horas_trabalhadas = $config['horas_trab_diario'];
$q = new BDConsulta;
$q->adTabela('projetos', 'pr');
$q->adCampo('DISTINCT pr.projeto_id, projeto_cor, projeto_nome, projeto_data_inicio, projeto_data_fim, max(t1.tarefa_fim) AS projeto_fim_atualizado, projeto_percentagem, projeto_status, projeto_ativo');
$q->adUnir('tarefas', 't1', 'pr.projeto_id = t1.tarefa_projeto');
$q->adUnir('cias', 'c1', 'pr.projeto_cia = c1.cia_id');
if ($secao > 0) $q->adOnde('projeto_depts.departamento_id = '.(int)$secao);
if (!($secao > 0) && $cia_id != 0) $q->adOnde('projeto_cia = '.(int)$cia_id);
$q->adOnde('projeto_superior_original = '.(int)$original_projeto_id);
$q->adGrupo('pr.projeto_id');
$q->adOrdem('projeto_nome, tarefa_inicio DESC');
$projetos = $q->ListaChave('projeto_id');
$q->limpar();
$largura = getParam($_REQUEST, 'width', 600);
$data_inicio = getParam($_REQUEST, 'data_inicio', 0);
$data_fim = getParam($_REQUEST, 'data_fim', 0);
$mostrarTodoGantt = getParam($_REQUEST, 'mostrarTodoGantt', '1');
$grafico = new GanttGraph($largura);
$grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HDAY | GANTT_HWEEK);
$grafico->SetFrame(false);
$grafico->SetBox(true, array(0, 0, 0), 2);
$grafico->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY);
if ($data_inicio && $data_fim) $grafico->SetDateRange($data_inicio, $data_fim);
$grafico->scale->actinfo->SetFont(FF_FONT1, FS_NORMAL, 8);
$grafico->scale->actinfo->vgrid->SetColor('gray');
$grafico->scale->actinfo->SetColor('darkgray');
$grafico->scale->actinfo->SetColTitles(array('Nome d'.$config['genero_projeto'].' '.$config['projeto'], '   Início   ', 'Término', 'Provável'), array(160, 10, 70, 70));
$original_projeto = new CProjeto();
$original_projeto->load($original_projeto_id);
$tabelaTitulo = $original_projeto->projeto_nome.': Gantt Multi-'.ucfirst($config['projeto']);
$grafico->scale->tableTitle->Set($tabelaTitulo);
$grafico->scale->tableTitle->SetFont(FF_FONT1, FS_BOLD, 10);
$grafico->scale->SetTableTitleBackground('#eeeeee');
$grafico->scale->tableTitle->Show(true);
if ($data_inicio && $data_fim) {
	$minuto_d_inicio = new CData($data_inicio);
	$minuto_d_fim = new CData($data_fim);
	$grafico->SetDateRange($data_inicio, $data_fim);
	} 
else {
	$d_inicio = new CData();
	$d_fim = new CData();
	$i = 0;
	foreach ($projetos as $projeto) {
		$inicio = substr($projeto["projeto_data_inicio"], 0, 10);
		$fim = substr($projeto["projeto_fim_atualizado"], 0, 10);
		($inicio == '' || $inicio == null || $inicio == '0000-00-00') ? $d_inicio->Date() : $d_inicio->Date($inicio);
		($fim == '' || $fim == null || $fim == '0000-00-00') ? $d_fim->Date() : $d_fim->Date($fim);
		if ($i == 0) {
			$minuto_d_inicio = $d_inicio;
			$minuto_d_fim = $d_fim;
			} 
		else {
			if ($d_inicio->compare($minuto_d_inicio, $d_inicio) > 0) $minuto_d_inicio = $d_inicio;
			if ($d_inicio->compare($minuto_d_fim, $d_fim) < 0) $minuto_d_fim = $d_fim;
			}
		$i++;
		}
	}
$dia_diferenca = $minuto_d_inicio->dataDiferenca($minuto_d_fim);
if ($dia_diferenca > 120 || !$dia_diferenca) $grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH);
elseif ($dia_diferenca > 60) {
	$grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HWEEK);
	$grafico->scale->week->SetStyle(WEEKSTYLE_WNBR);
	}
$linha = 0;
if (!is_array($projetos) || sizeof($projetos) == 0) {
	$d = new CData();
	$barra = new GanttBar($linha++, array(' '.($config['genero_projeto']=='o'? 'Nenhum' : 'Nenhuma').' '.$config['projeto'].' encontrad'.$config['genero_projeto'], ' ', ' ', ' '), $d->getData(), $d->getData(), ' ', 0.6);
	$barra->title->SetCOlor('red');
	$grafico->Add($barra);
	}
if (is_array($projetos)) {
	foreach ($projetos as $p) {
		if ($localidade_tipo_caract == 'utf-8' && function_exists('utf8_decode')) $nome = strlen(utf8_decode($p['projeto_nome'])) > 35 ? substr(utf8_decode($p['projeto_nome']), 0, 33).'...' : utf8_decode($p['projeto_nome']);
		else $nome =(strlen($p['projeto_nome']) > 25 ? substr($p['projeto_nome'], 0, 22) : $p['projeto_nome']);
		$inicio = ($p['projeto_data_inicio'] ? $p['projeto_data_inicio'] : date('Y-m-d H:i:s'));
		$data_fim = $p['projeto_data_fim'];
		$data_fim = new CData($data_fim);
		$fim = $data_fim->getData();
		$inicio = new CData($inicio);
		$inicio = $inicio->getData();
		$progresso = $p['projeto_percentagem'] + 0;
		$legenda = ' ';
		if (!$inicio || $inicio == '0000-00-00') {
			$inicio = !$fim ? date('Y-m-d') : $fim;
			$legenda .= ' (sem data de início)';
			}
		if (!$fim) {
			$fim = $inicio;
			$legenda .= ' (sem data de término)';
			} 
		else $cap = '';
		$datafim = new CData($fim);
		$datainicio = new CData($inicio);
		$fim_atual = $p['projeto_fim_atualizado'] ? $p['projeto_fim_atualizado'] : $fim;
		$datafim_atual = new CData($fim_atual);
		$datafim_atual = $datafim_atual->after($datainicio) ? $datafim_atual : $datafim;
		$barra = new GanttBar($linha++, array($nome, $datainicio->format($df), $datafim->format($df), $datafim_atual->format($df)), $inicio, $fim_atual, $cap, 0.6);
		$barra->progress->Set(min(($progresso / 100), 1));
		$barra->title->SetFont(FF_FONT1, FS_BOLD, 7);
		$barra->SetFillColor('#'.$p['projeto_cor']);
		$barra->SetPattern(BAND_SOLID, '#'.$p['projeto_cor']);
		$barra->caption = new TextProperty($legenda);
		$barra->caption->Align('left', 'center');
		$barra->caption->SetFont(FF_FONT1, FS_NORMAL, 8);
		if ($p['projeto_ativo'] < 1 || $p['projeto_percentagem'] > 99.9) {
			$barra->caption->SetColor('darkgray');
			$barra->title->SetColor('darkgray');
			$barra->SetColor('darkgray');
			$barra->SetFillColor('gray');
			$barra->progress->SetFillColor('darkgray');
			$barra->progress->SetPattern(BAND_SOLID, 'darkgray', 98);
			}
		$grafico->Add($barra);
		if ($mostrarTodoGantt) {
			for ($i = 0, $i_cmp = (isset($vetorGantt[$p['projeto_id']]) ? count($vetorGantt[$p['projeto_id']]) : 0); $i < $i_cmp; $i++) {
				$t = $vetorGantt[$p['projeto_id']][$i][0];
				$nivel = $vetorGantt[$p['projeto_id']][$i][1];
				if ($t['tarefa_fim'] == null) $t['tarefa_fim'] = $t['tarefa_inicio'];
				$tInicio = ($t['tarefa_inicio'] ? $t['tarefa_inicio'] : date('Y-m-d H:i:s'));
				$tFim = ($t['tarefa_fim'] ? $t['tarefa_fim'] : date('Y-m-d H:i:s'));
				$tInicioObj = new CData($tInicio);
				$tFimObj = new CData($tFim);
				if ($t['tarefa_marco'] != 1) {
					$advance = str_repeat('  ', $nivel);
					$barra2 = new GanttBar($linha++, array((strlen($advance.$t['tarefa_nome']) > 35 ? substr($advance.$t['tarefa_nome'], 0, 33).'...' : $advance.$t['tarefa_nome']), $tInicioObj->format($df), $tFimObj->format($df), ' '), $tInicio, $tFim, ' ', $t['tarefa_dinamica'] == 1 ? 0.1 : 0.6);
					$barra2->title->SetColor(melhorCor('#ffffff', '#'.$p['projeto_cor'], '#000000'));
	        $barra2->title->SetFont(FF_FONT0);
					$barra2->title->SetFont(FF_FONT1, FS_NORMAL, 7);
					$barra2->SetFillColor('#'.$p['projeto_cor']);
					$grafico->Add($barra2);
					} 
				else {
					$advance='';
					$barra2 = new MileStone($linha++, array((strlen($advance.$t['tarefa_nome']) > 35 ? substr($advance.$t['tarefa_nome'], 0, 33).'...' : $advance.$t['tarefa_nome']), $tInicioObj->format($df), $tFimObj->format($df), ' '), $t['tarefa_inicio'], '');
					$barra2->title->SetColor('#CC0000');
          $barra2->title->SetFont(FF_FONT0);
					$barra2->title->SetFont(FF_FONT1, FS_NORMAL, 7);
					$grafico->Add($barra2);
					}
				}
			}
		}
	}
$hoje = date('y-m-d');
$linhaVert = new GanttVLine($hoje, 'Hoje');
$linhaVert->title->SetFont(FF_FONT1, FS_BOLD, 9);
$grafico->Add($linhaVert);
$grafico->Stroke();
?>