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

global $Aplic, $mostrarCaixachecarEditar, $este_dia, $config, $usuario_id;
require_once ($Aplic->getClasseModulo('projetos'));
$mostrarCaixachecarEditar = config('editar_designado_diretamente');
$projeto_status_aguardando = 4;

$q = new BDConsulta;


if (isset($_REQUEST['dept_id'])) $dept_id=getParam($_REQUEST, 'dept_id', 0);
$usuario_id=$Aplic->usuario_id;
if (isset($_REQUEST['tab'])) $Aplic->setEstado('TabListaTarefas', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('TabListaTarefas') !== null ? $Aplic->getEstado('TabListaTarefas') : 0;
if (isset($_REQUEST['tarefa_tipo'])) $Aplic->setEstado('TipoTarefaParaFazer', getParam($_REQUEST, 'tarefa_tipo', ''));

$tarefa_tipo = $Aplic->getEstado('TipoTarefaParaFazer') !== null ? $Aplic->getEstado('TipoTarefaParaFazer') : '';
$projeto_id = 0;
$este_dia= new CData(date('Y-m-d'));
$data = $este_dia->format(FMT_TIMESTAMP_DATA);

if (isset($_REQUEST['mostrar_form'])) {
	$Aplic->setEstado('mostra_projeto_completo', getParam($_REQUEST, 'mostra_projeto_completo', 0));
	$Aplic->setEstado('mostrar_tarefa_baixa', getParam($_REQUEST, 'mostrar_tarefa_baixa', 0));
	$Aplic->setEstado('mostrar_proj_aguardando', getParam($_REQUEST, 'mostrar_proj_aguardando', 0));
	$Aplic->setEstado('mostrar_tarefa_dinamica', getParam($_REQUEST, 'mostrar_tarefa_dinamica', 0));
	$Aplic->setEstado('mostrar_marcada', getParam($_REQUEST, 'mostrar_marcada', 0));
	$Aplic->setEstado('mostrar_sem_data', getParam($_REQUEST, 'mostrar_sem_data', 0));
	$Aplic->setEstado('mostrar_sem_data', getParam($_REQUEST, 'mostrar_sem_data', 0));
	$Aplic->setEstado('mesmo_completa', getParam($_REQUEST, 'mesmo_completa', 0));
	}
global $mostra_projeto_completo, $mostrar_tarefa_baixa, $mostraProjetosEspera, $mostrar_tarefa_dinamica, $mostrar_marcada, $mostrar_sem_data;
$mostra_projeto_completo = $Aplic->getEstado('mostra_projeto_completo', 0);
$mostrar_tarefa_baixa = $Aplic->getEstado('mostrar_tarefa_baixa', 1);
$mostrar_proj_aguardando= $Aplic->getEstado('mostrar_proj_aguardando', 0);
$mostrar_tarefa_dinamica = $Aplic->getEstado('mostrar_tarefa_dinamica', 0);
$mostrar_marcada = $Aplic->getEstado('mostrar_marcada', 0);
$mostrar_sem_data = $Aplic->getEstado('mostrar_sem_data', 0);
$mesmo_completa = $Aplic->getEstado('mesmo_completa', 0);
global $tarefa_ordenar_item1, $tarefa_ordenar_tipo1, $tarefa_ordenar_ordem1;
global $tarefa_ordenar_item2, $tarefa_ordenar_tipo2, $tarefa_ordenar_ordem2;
$tarefa_ordenar_item1 = getParam($_REQUEST, 'tarefa_ordenar_item1', '');
$tarefa_ordenar_tipo1 = getParam($_REQUEST, 'tarefa_ordenar_tipo1', '');
$tarefa_ordenar_item2 = getParam($_REQUEST, 'tarefa_ordenar_item2', '');
$tarefa_ordenar_tipo2 = getParam($_REQUEST, 'tarefa_ordenar_tipo2', '');
$tarefa_ordenar_ordem1 = intval(getParam($_REQUEST, 'tarefa_ordenar_ordem1', 0));
$tarefa_ordenar_ordem2 = intval(getParam($_REQUEST, 'tarefa_ordenar_ordem2', 0));
$tarefa_prioridade = getParam($_REQUEST, 'tarefa_prioridade', 99);
$selecionado = getParam($_REQUEST, 'selecionado_tarefa', 0);
if (is_array($selecionado) && count($selecionado)) {
	foreach ($selecionado as $chave => $val) {
		if ($tarefa_prioridade == 'c') {
			$q->adTabela('tarefas');
			$q->adAtualizar('tarefa_percentagem', '100');
			$q->adAtualizar('tarefa_percentagem_data', date('Y-m-d H:i:s'));
			$q->adOnde('tarefa_id='.(int)$val);
			} 
		elseif ($tarefa_prioridade == 'd') {
			$q = new BDConsulta;
			$q->setExcluir('tarefas');
			$q->adOnde('tarefa_id='.(int)$val);
			} 
		elseif ($tarefa_prioridade > -2 && $tarefa_prioridade < 2) {
			$q = new BDConsulta;
			$q->adTabela('tarefas');
			$q->adAtualizar('tarefa_prioridade', $tarefa_prioridade);
			$q->adOnde('tarefa_id='.(int)$val);
			}
		$q->exec();
		$q->limpar();
		}
	}

$q->adTabela('projetos');
$q->esqUnir('tarefas', 'tarefas', 'projetos.projeto_id = tarefas.tarefa_projeto');
$q->esqUnir('projeto_depts', 'projeto_depts', 'projetos.projeto_id = projeto_depts.projeto_id');
$q->esqUnir('usuario_tarefa_marcada', 'usuario_tarefa_marcada', 'tarefas.tarefa_id = usuario_tarefa_marcada.tarefa_id');
$q->esqUnir('tarefa_depts', 'tarefa_depts', 'tarefas.tarefa_id = tarefa_depts.tarefa_id');
$q->adCampo('tarefas.*, projetos.projeto_nome, projetos.projeto_id, projetos.projeto_cor');
$q->adOnde('tarefa_depts.departamento_id = '.(int)$dept_id );
if (!$mesmo_completa) $q->adOnde('(tarefas.tarefa_percentagem < 100 or tarefas.tarefa_percentagem IS NULL)');
$q->adOnde('projeto_template = 0');
$q->adOnde('projetos.projeto_id = tarefas.tarefa_projeto');
if (!$mostra_projeto_completo) $q->adOnde('projeto_ativo = 1');
if (!$mostrar_tarefa_baixa) $q->adOnde('tarefa_prioridade >= 0');
if (!$mostraProjetosEspera) $q->adOnde('projeto_status != '.(int)$projeto_status_aguardando);
if (!$mostrar_tarefa_dinamica) $q->adOnde('tarefa_dinamica != 1');
if ($mostrar_marcada) $q->adOnde('tarefa_marcada = 1');
if (!$mostrar_sem_data) $q->adOnde('tarefas.tarefa_inicio IS NOT NULL');
if ($tarefa_tipo) $q->adOnde('tarefas.tarefa_tipo = "'.$tarefa_tipo.'"');
$q->adOrdem('tarefas.tarefa_fim');
$q->adOrdem('tarefas.tarefa_prioridade DESC');
$tarefas = $q->Lista();
$q->limpar();

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
include BASE_DIR.'/modulos/depts/tarefas_sub.php';
?>
<script type="text/JavaScript">

function iluminar_tds(row,high,id){
	if(document.getElementsByTagName){
		var tcs=row.getElementsByTagName('td');
		var nome_celula='';
		if(!id) check=false;
		else{
			var f=eval('document.frm_tarefas');
			var check=eval('f.selecionado_tarefa_'+id+'.checked')
			}
		for(var j=0,j_cmp=tcs.length;j<j_cmp;j+=1){
			nome_celula=eval('tcs['+j+'].id');
			if(!(nome_celula.indexOf('ignore_td_')>=0)){
				if(high==3) tcs[j].style.background='#FFFFCC';
				else if(high==2||check)
				tcs[j].style.background='#FFCCCC';
				else if(high==1) tcs[j].style.background='#FFFFCC';
				else tcs[j].style.background='#FFFFFF';
				}
			}
		}
	}

</script>