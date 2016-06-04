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

global $Aplic, $cia_id, $dept_ids, $secao, $localidade_tipo_caract, $mostrarInativo, $mostrarLegendas, $mostrarTodoGantt, $ordenarTarefasPorNome, $usuario_id, $config;
ini_set('memory_limit', $config['resetar_limite_memoria']);

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';

include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_gantt'));
$q = new BDConsulta;
$df = '%d/%m/%Y';
$projetoStatus = getSisValor('StatusProjeto');
$projetoStatus = unirVetores(array('-2' => 'Todos sem progresso'), $projetoStatus);

if ($Aplic->usuario_id == $usuario_id) $projetoStatus = unirVetores(array('-3' => 'Meus '.$config['projetos']), $projetoStatus);
else $projetoStatus = unirVetores(array('-3' => ucfirst($config['projetos']).' d'.$config['genero_usuario'].'s '.$config['usuarios']), $projetoStatus);

$usuario_id = getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$filtro = getParam($_REQUEST, 'filtro', '-1');
$cia_id = getParam($_REQUEST, 'cia_id', 0);
$secao = getParam($_REQUEST, 'secao', 0);
$mostrarLegendas = getParam($_REQUEST, 'mostrarLegendas', 0);
$mostrarInativo = getParam($_REQUEST, 'mostrarInativo', 0);
$ordenarTarefasPorNome = getParam($_REQUEST, 'ordenarTarefasPorNome', 0);
$mostrarProjRespPertenceDept = getParam($_REQUEST, 'mostrarProjRespPertenceDept', 0);
$portfolio = getParam($_REQUEST, 'portfolio', 0);
$pjobj = new CProjeto;

$lista_portifolio='';
if ($portfolio) {
	$q->adTabela('projeto_portfolio');
	$q->adCampo('projeto_portfolio_filho');
	$q->adOnde('projeto_portfolio_pai = '.(int)$portfolio);
	$lista_portifolio = $q->carregarColuna();
	$q->limpar();
	$lista_portifolio=implode(',',$lista_portifolio);
	}

$horas_trabalhadas = $config['horas_trab_diario'];

$q->adTabela('projetos', 'pr');
$q->adCampo('DISTINCT pr.projeto_id, projeto_cor, projeto_nome, projeto_data_inicio, projeto_data_fim, max(t1.tarefa_fim) AS projeto_fim_atualizado, projeto_percentagem, projeto_status, projeto_ativo,projeto_portfolio');
$q->adUnir('tarefas', 't1', 'pr.projeto_id = t1.tarefa_projeto');
$q->adUnir('cias', 'c1', 'pr.projeto_cia = c1.cia_id');
if ($secao > 0 && !$mostrarProjRespPertenceDept) $q->adOnde('projeto_depts.departamento_id = '.(int)$secao);
if (!($secao > 0) && $cia_id != 0 && !$mostrarProjRespPertenceDept) $q->adOnde('pr.projeto_cia = '.(int)$cia_id);
if ($mostrarProjRespPertenceDept && !empty($responsavel_ids)) $q->adOnde('pr.projeto_responsavel IN ('.implode(',', $responsavel_ids).')');
if ($mostrarInativo != '1') $q->adOnde('pr.projeto_ativo = 1');
$q->adOnde('projeto_template = 0');
if (!$portfolio) $q->adOnde('pr.projeto_portfolio = 0 OR pr.projeto_portfolio IS NULL');
elseif ($lista_portifolio) $q->adOnde('pr.projeto_id IN ('.$lista_portifolio.')');
$q->adGrupo('pr.projeto_id');
$q->adOrdem('pr.projeto_nome, tarefa_fim DESC');
$projetos = $q->Lista();
$q->limpar();


if ($portfolio){
	foreach ($projetos as $chave => $linha) {
		if ($linha['projeto_portfolio']) $projetos[$chave]['projeto_percentagem']=portfolio_porcentagem($linha['projeto_id']);
		}
	}


$largura = min(getParam($_GET, 'width', 600), 1400);
$data_inicio = getParam($_GET, 'data_inicio', 0);
$data_fim = getParam($_GET, 'data_fim', 0);
$mostrarTodoGantt = getParam($_REQUEST, 'mostrarTodoGantt', '0');
$grafico = new GanttGraph($largura);
if (!$data_inicio && !$data_fim) $grafico->ShowHeaders(GANTT_HYEAR| GANTT_HMONTH);
else $grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HDAY | GANTT_HWEEK);
$grafico->SetFrame(false);
$grafico->SetBox(true, array(0, 0, 0), 2);
$grafico->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY);
$pLocale = setlocale(LC_TIME, 0); 
$res = setlocale(LC_TIME, $Aplic->usuario_linguagem[2]);
if ($res) $grafico->scale->SetDateLocale($Aplic->usuario_linguagem[2]);
setlocale(LC_TIME, $pLocale);
if ($data_inicio && $data_fim) $grafico->SetDateRange($data_inicio, $data_fim);
$grafico->scale->actinfo->vgrid->SetColor('gray');
$grafico->scale->actinfo->SetColor('darkgray');
$grafico->scale->actinfo->SetColTitles(array('Nome d'.$config['genero_projeto'].' '.$config['projeto'], ' Início ', 'Término', 'Provável'), array(160, 10, 70, 70));
$tabelaTitulo = ($filtro == '-1' ? 'Todos '.$config['genero_projeto'].'s '.ucfirst($config['projetos']) : ($portfolio ? ucfirst($config['portfolio']) : $projetoStatus[$filtro]));
$grafico->scale->tableTitle->Set($tabelaTitulo);
$grafico->scale->tableTitle->SetFont(FF_FONT1, FS_BOLD, 8);
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

	$minuto_d_inicio = $d_inicio;
	$minuto_d_fim = $d_fim;
	
	/*
	
	for ($i = 0, $i_cmp = count($projetos); $i < $i_cmp; $i++) {
		$inicio = substr($projetos[$i]['projeto_data_inicio'], 0, 10);
		$fim = substr($projetos[$i]['projeto_data_fim'], 0, 10);
		$d_inicio->Date($inicio);
		$d_fim->Date($fim);
		
		if ($i == 0) {

			$minuto_d_inicio = $d_inicio;
			$minuto_d_fim = $d_fim;
			} 
		else {
			if ($d_inicio->compare($minuto_d_inicio, $d_inicio) > 0) $minuto_d_inicio = $d_inicio;
			if ($d_inicio->compare($minuto_d_fim, $d_fim) < 0) $minuto_d_fim = $d_fim;
			}
			
		}
		*/
	}
$dia_diferenca = $minuto_d_inicio->dataDiferenca($minuto_d_fim);
if ($dia_diferenca > 360) $grafico->ShowHeaders(GANTT_HYEAR);
elseif ($dia_diferenca > 240) $grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH);
elseif ($dia_diferenca > 90) {
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
		if ($localidade_tipo_caract == 'utf-8' && function_exists('utf8_decode')) $nome = strlen(utf8_decode($p['projeto_nome'])) > 25 ? substr(utf8_decode($p['projeto_nome']), 0, 22).'...' : utf8_decode($p['projeto_nome']);
		else $nome = strlen($p['projeto_nome']) > 30 ? substr($p['projeto_nome'], 0, 28).'...' : $p['projeto_nome'];
		$inicio = ($p['projeto_data_inicio']  ? $p['projeto_data_inicio'] : null);
		$data_fim = ($p['projeto_data_fim']  ? $p['projeto_data_fim'] : null);
		$data_fim = new CData($data_fim);
		$fim = $data_fim->getData();
		$inicio = new CData($inicio);
		$inicio = $inicio->getData();
		$progresso = $p['projeto_percentagem'] + 0;
		$legenda = '';
		if (!$inicio || $inicio == '0000-00-00') {
			$inicio = !$fim ? date('Y-m-d') : $fim;
			$legenda .= '(sem data início)';
			}
		if (!$fim) {
			$fim = $inicio;
			$legenda .= ' '.'(sem data de término)';
			} 
		else $cap = '';
		if ($mostrarLegendas) {
			$legenda .= $projetoStatus[$p['projeto_status']].', ';
			$legenda .= $p['projeto_ativo'] != 0 ? 'Ativo': 'arquivado';
			}
		$datafim = new CData($fim);
		$datainicio = new CData($inicio);
		$fim_atual = intval($p['projeto_fim_atualizado']) ? $p['projeto_fim_atualizado'] : $fim;
		$datafim_atual = new CData($fim_atual);
		$datafim_atual = $datafim_atual->after($datainicio) ? $datafim_atual : $datafim;
		$barra = new GanttBar($linha++, array($nome, $datainicio->format($df), $datafim->format($df), $datafim_atual->format($df)), $inicio, $fim_atual, $cap, 0.6);
		$barra->progress->Set(min(($progresso / 100), 1));
		$barra->title->SetFont(FF_FONT1, FS_NORMAL, 8);
		$barra->SetFillColor('#'.$p['projeto_cor']);
		$barra->SetPattern(BAND_SOLID, '#'.$p['projeto_cor']);
		$barra->caption = new TextProperty($legenda);
		$barra->caption->Align('left', 'center');
		if ($p['projeto_ativo'] == '0') {
			$barra->caption->SetColor('darkgray');
			$barra->title->SetColor('darkgray');
			$barra->SetColor('darkgray');
			$barra->SetFillColor('gray');
			$barra->progress->SetFillColor('darkgray');
			$barra->progress->SetPattern(BAND_SOLID, 'darkgray', 98);
			}
		$grafico->Add($barra);
		if ($mostrarTodoGantt) {
			$q->adTabela('tarefas');
			$q->adCampo('DISTINCT tarefas.tarefa_id, tarefas.tarefa_nome, tarefas.tarefa_inicio, tarefas.tarefa_fim, tarefas.tarefa_duracao, tarefas.tarefa_duracao_tipo, tarefas.tarefa_marco, tarefas.tarefa_dinamica');
			$q->adUnir('projetos', 'p', 'p.projeto_id = tarefas.tarefa_projeto');
			$q->adOnde('p.projeto_id = '.(int)$p['projeto_id']);
			if ($ordenarTarefasPorNome) $q->adOrdem('tarefas.tarefa_nome');
			else $q->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefas.tarefa_inicio ASC');
			$tarefas = $q->Lista();
			$q->limpar();
			foreach ($tarefas as $t) {
				if (!$t['tarefa_inicio']) {
					if (!$t['tarefa_fim']) {
						$dataHoje = new CData();
						$t['tarefa_inicio'] = $dataHoje->format(FMT_TIMESTAMP_DATA);
						} 
					else $t['tarefa_inicio'] = $t['tarefa_fim'];
					}
				if (!$t['tarefa_fim']) {
					if ($t['tarefa_duracao']) $t['tarefa_fim'] = db_unix2dateTime(db_dateTime2unix($t['tarefa_inicio']) + 86400 * converterParaDias($t['tarefa_duracao'], $t['tarefa_duracao_tipo']));
					else {
						$dataHoje = new CData();
						$t['tarefa_fim'] = $dataHoje->format(FMT_TIMESTAMP_DATA);
						}
					}
				$tInicio = intval($t['tarefa_inicio']) ? $t['tarefa_inicio'] : $inicio;
				$tFim = intval($t['tarefa_fim']) ? $t['tarefa_fim'] : $fim;
				$tInicioObj = new CData($t['tarefa_inicio']);
				$tFimObj = new CData($t['tarefa_fim']);
				if ($t['tarefa_marco'] != 1) {
					$barra2 = new GanttBar($linha++, array(substr(' --'.$t['tarefa_nome'], 0, 20).'...', $tInicioObj->format($df), $tFimObj->format($df), ' '), $tInicio, $tFim, ' ', $t['tarefa_dinamica'] == 1 ? 0.1 : 0.6);
					$barra2->title->SetColor('#'.melhorCor('ffffff', $p['projeto_cor'], '000000'));
					$barra2->title->SetFont(FF_FONT1, FS_NORMAL, 8);
					$barra2->SetFillColor('#'.$p['projeto_cor']);
					$grafico->Add($barra2);
					} 
				else {
					$barra2 = new MileStone($linha++, '-- '.$t['tarefa_nome'], $t['tarefa_inicio'], $tInicioObj->format($df));
					$barra2->title->SetFont(FF_FONT1, FS_NORMAL, 8);
					$barra2->title->SetColor('#CC0000');
					$grafico->Add($barra2);
					}
				$q->adTabela('tarefa_designados', 't');
				$q->esqUnir('usuarios', 'u', 'u.usuario_id = t.usuario_id');
				$q->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
				$q->adCampo('DISTINCT contato_posto, contato_nomeguerra, t.tarefa_id');
				$q->adOnde('t.tarefa_id = '.(int)$t['tarefa_id']);
				$q->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
				$trabalhadores = $q->Lista();
				$q->limpar();
				foreach ($trabalhadores as $w) {
					$barra3 = new GanttBar($linha++, array('   * '.$w['contato_posto'].' '.$w['contato_nomeguerra'], ' ', ' ', ' '), $tInicioObj->format(FMT_TIMESTAMP_MYSQL), $tFimObj->format(FMT_TIMESTAMP_MYSQL), 0.6);
					$barra3->title->SetFont(FF_FONT1, FS_NORMAL, 8);
					$barra3->title->SetColor('#'.melhorCor('ffffff', $p['projeto_cor'], '000000'));
					$barra3->SetFillColor('#'.$p['projeto_cor']);
					$grafico->Add($barra3);
					}
				}
			unset($tarefas);
			}
		}
	} 
unset($projetos);
$hoje = date('y-m-d');
$linhaVert = new GanttVLine($hoje, 'Hoje');
$grafico->Add($linhaVert);
$grafico->Stroke();
?>