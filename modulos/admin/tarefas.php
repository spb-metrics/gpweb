<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $Aplic, $mostrarCaixachecarEditar, $dialogo, $este_dia, $config, $usuario_id, $podeEditar;

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
require_once ($Aplic->getClasseModulo('projetos'));
$mostrarCaixachecarEditar = config('editar_designado_diretamente');
$projeto_status_aguardando = 4;
if (isset($_REQUEST['usuario_id'])) $usuario_id=getParam($_REQUEST, 'usuario_id', 0);
if (isset($_REQUEST['tab'])) $Aplic->setEstado('TabParaFazer', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('TabParaFazer') !== null ? $Aplic->getEstado('TabParaFazer') : 0;
if (isset($_REQUEST['tarefa_tipo'])) $Aplic->setEstado('TipoTarefaParaFazer', getParam($_REQUEST, 'tarefa_tipo', ''));
$tarefa_tipo = $Aplic->getEstado('TipoTarefaParaFazer') !== null ? $Aplic->getEstado('TipoTarefaParaFazer') : '';
$projeto_id = 0;
$este_dia= new CData(date('Y-m-d'));
$data = $este_dia->format(FMT_TIMESTAMP_DATA);
$podeEditar = $podeEditar;
if (isset($_REQUEST['mostrar_form'])) {
	$Aplic->setEstado('mostra_projeto_completo', getParam($_REQUEST, 'mostra_projeto_completo', 0));
	$Aplic->setEstado('mostrar_tarefa_baixa', getParam($_REQUEST, 'mostrar_tarefa_baixa', 0));
	$Aplic->setEstado('mostrar_proj_aguardando', getParam($_REQUEST, 'mostrar_proj_aguardando', 0));
	$Aplic->setEstado('mostrar_tarefa_dinamica', getParam($_REQUEST, 'mostrar_tarefa_dinamica', 0));
	$Aplic->setEstado('mostrar_marcada', getParam($_REQUEST, 'mostrar_marcada', 0));
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
			$q = new BDConsulta;
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
		echo db_error();
		$q->limpar();
		}
	}
if (!$dialogo) $Aplic->salvarPosicao();
$proj = new CProjeto;
$tobj = new CTarefa;
$q = new BDConsulta;

$q->adCampo('projetos.projeto_nome, projetos.projeto_id, projetos.projeto_cor');
$q->adCampo('diferenca_data(tarefa_fim,tarefa_inicio) as dias');
$q->adCampo('tarefas.*');
$q->adTabela('tarefas', '');
$q->esqUnir('projetos', '', 'projetos.projeto_id = tarefas.tarefa_projeto');
$q->esqUnir('usuario_tarefa_marcada', '', 'usuario_tarefa_marcada.tarefa_id = tarefas.tarefa_id');
$q->esqUnir('tarefa_designados', '', 'tarefa_designados.tarefa_id = tarefas.tarefa_id');
$q->adOnde('tarefas.tarefa_id IS NOT NULL');
$q->adOnde('tarefa_designados.usuario_id = '.(int)$usuario_id);
if (!$mesmo_completa) $q->adOnde('( tarefas.tarefa_percentagem < 100 or tarefas.tarefa_percentagem IS NULL)');
$q->adOnde('projetos.projeto_id = tarefas.tarefa_projeto');
if (!$mostra_projeto_completo) $q->adOnde('projetos.projeto_ativo = 1');
if (!$mostrar_tarefa_baixa) $q->adOnde('tarefas.tarefa_prioridade >= 0');
if (!$mostraProjetosEspera) $q->adOnde('projetos.projeto_status != '.(int)$projeto_status_aguardando);
if (!$mostrar_tarefa_dinamica) $q->adOnde('tarefas.tarefa_dinamica != 1');
if ($mostrar_marcada) $q->adOnde('tarefas.tarefa_marcada = 1');
if (!$mostrar_sem_data) $q->adOnde('tarefas.tarefa_inicio IS NOT NULL');
if ($tarefa_tipo) $q->adOnde('tarefas.tarefa_tipo = "'.$tarefa_tipo.'"');
$q->adGrupo('tarefas.tarefa_id, projetos.projeto_nome, projetos.projeto_id, projetos.projeto_cor, usuario_tarefa_marcada.tarefa_marcada');
$q->adOrdem('tarefas.tarefa_fim, tarefas.tarefa_prioridade DESC');
//EUD

global $tarefas;
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
global $prioridades;
$prioridades = array('2' =>'muito alta', '1' => 'alta', '0' => 'normal', '-1' => 'baixa', '-2' => 'muito baixa');
global $tipoDuracao;
$tipoDuracao = getSisValor('TipoDuracaoTarefa');
include BASE_DIR.'/modulos/admin/tarefas_sub.php';

?>