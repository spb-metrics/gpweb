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
global $m, $a, $projeto_id, $f, $ver_min, $texto_consulta, $tipoDuracao, $cia_id, $dept_id, $lista_depts, $lista_cias, $mostrar_marcada, $inativo,
$tarefa_ordenar_item1, $tarefa_ordenar_tipo1, $tarefa_ordenar_ordem1,
$tarefa_ordenar_item2, $tarefa_ordenar_tipo2, $tarefa_ordenar_ordem2,
$pesquisar_texto, $usuario_id, $config, $tabAtualId, $tabNomeAtual, $podeEditar, $mostrarCaixachecarEditar, $Aplic, $baseline_id;

if (empty($texto_consulta)) $texto_consulta = '?m='.$m.'&a='.$a;

$sql = new BDConsulta;
	
$mover=array();
$mover[]='';
for ($i=1;$i<=12;$i++) $mover['m'.$i]='+'.($i < 10 ? '0':'').$i.' mes'.($i>1 ? 'es' : '');
for ($i=1;$i<=5;$i++) $mover['s'.$i]='+'.($i < 10 ? '0':'').$i.' semana'.($i>1 ? 's' : '');
for ($i=1;$i<=30;$i++) $mover['d'.$i]='+'.($i < 10 ? '0':'').$i.' dia'.($i>1 ? 's' : '');
for ($i=-1;$i>=-12;$i--) $mover['m'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' mes'.($i<-1 ? 'es' : '');
for ($i=-1;$i>=-5;$i--) $mover['s'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' semana'.($i<-1 ? 's' : '');
for ($i=-1;$i>=-30;$i--) $mover['d'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' dia'.($i<-1 ? 's' : '');

$cols = 13;
if (isset($_REQUEST['modificar_datas_tarefas']) && $_REQUEST['modificar_datas_tarefas'] && isset($_REQUEST['semanas'])){
	include_once BASE_DIR.'/modulos/tarefas/funcoes.php';
	$mover_semanas=getParam($_REQUEST, 'semanas', null);
	$periodo=substr($mover_semanas, 0, 1);
	$semanas=substr($mover_semanas, 1, 3);
	if ($periodo=='d') $periodo='DAY';
	elseif ($periodo=='s') $periodo='WEEK';
	elseif ($periodo=='m') $periodo='MONTH';
	$conjunto_tarefas=getParam($_REQUEST, 'selecionado_tarefa', array());
	//incluir subtarefas
	$vetor=array();
	foreach ($conjunto_tarefas as $tarefa_id) {
		$vetor[]=$tarefa_id;
		lista_tarefas_subordinadas($tarefa_id, $vetor);
		}

	foreach ($vetor as $tarefa_id) {
		$sql->adTabela('tarefas');
		$sql->adCampo('adiciona_data((select tarefa_inicio FROM tarefas WHERE tarefa_id='.(int)$tarefa_id.'), '.$semanas.', \''.$periodo.'\') AS inicio');
		$sql->adCampo('adiciona_data((select tarefa_fim FROM tarefas WHERE tarefa_id='.(int)$tarefa_id.'), '.$semanas.', \''.$periodo.'\') AS fim');
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$datas=$sql->Linha();
		$sql->limpar();
		$sql->adTabela('tarefas');
		if ($datas['inicio']) $sql->adAtualizar('tarefa_inicio', $datas['inicio']);
		if ($datas['fim']) $sql->adAtualizar('tarefa_fim',  $datas['fim']);
        if ($datas['inicio']) $sql->adAtualizar('tarefa_inicio_manual', $datas['inicio']);
        if ($datas['fim']) $sql->adAtualizar('tarefa_fim_manual',  $datas['fim']);
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$sql->exec();
		$sql->limpar();
		}
	//verificar as dependencias
	foreach ($vetor as $tarefa_id) {
		verifica_dependencias($tarefa_id);
		calcular_superior($tarefa_id);
		}
	}

$tarefa_id = intval(getParam($_REQUEST, 'tarefa_id', 0));

$marcada_apenas = intval(getParam($_REQUEST, 'marcada', 0));

if (isset($_REQUEST['marcada'])){
	$marcada = intval(getParam($_REQUEST, 'marcada', 0));
	$msg = '';
	if ($tarefa_id){
		if ($marcada) {
			$sql->adTabela('usuario_tarefa_marcada');
			$sql->adInserir('usuario_id', $Aplic->usuario_id);
			$sql->adInserir('tarefa_id', $tarefa_id);
			}
		else {
			$sql->setExcluir('usuario_tarefa_marcada');
			$sql->adOnde('usuario_id = '.(int)$Aplic->usuario_id);
			$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
			}
		if (!$sql->exec()) $Aplic->setMsg('erro deinserção/exclusão', UI_MSG_ERRO, true);
		$sql->limpar();
		}
	$Aplic->redirecionar($Aplic->getPosicao());
	}


$tipoDuracao = getSisValor('TipoDuracaoTarefa');
$prioridadeTarefa = getSisValor('PrioridadeTarefa');
$tarefa_projeto = intval(getParam($_REQUEST, 'tarefa_projeto', null));
$tarefa_ordenar_item1 = getParam($_REQUEST, 'tarefa_ordenar_item1', '');
$tarefa_ordenar_tipo1 = getParam($_REQUEST, 'tarefa_ordenar_tipo1', '');
$tarefa_ordenar_item2 = getParam($_REQUEST, 'tarefa_ordenar_item2', '');
$tarefa_ordenar_tipo2 = getParam($_REQUEST, 'tarefa_ordenar_tipo2', '');
$tarefa_ordenar_ordem1 = intval(getParam($_REQUEST, 'tarefa_ordenar_ordem1', 0));
$tarefa_ordenar_ordem2 = intval(getParam($_REQUEST, 'tarefa_ordenar_ordem2', 0));
if (isset($_REQUEST['mostrar_tarefa_options'])) $Aplic->setEstado('ListaTarefasMostrarIncompletas', getParam($_REQUEST, 'mostrar_incompleta', 0));
$mostrarIncompleta = $Aplic->getEstado('ListaTarefasMostrarIncompletas', 0);
require_once $Aplic->getClasseModulo('projetos');
$projeto = new CProjeto;
$horas_trabalhadas = ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);


$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas', 'tarefas');
$sql->esqUnir(($baseline_id ? 'baseline_' : '').'projetos', 'projetos', 'projeto_id = tarefa_projeto');
$sql->adCampo('DISTINCT projeto_id,  projeto_acesso, projeto_descricao, projeto_data_inicio, projeto_data_fim, projeto_cor, projeto_nome, projeto_percentagem');
$sql->adOnde((isset($lista_onde) && $lista_onde ? $lista_onde.' AND ' : '').'tarefas.tarefa_id = tarefa_superior');
$sql->esqUnir('usuario_tarefa_marcada', 'usuario_tarefa_marcada', 'usuario_tarefa_marcada.tarefa_id = tarefas.tarefa_id');
if ($baseline_id) {
		$sql->adOnde('projetos.baseline_id='.(int)$baseline_id);
		$sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
		}
if ($mostrar_marcada)	$sql->adOnde('usuario_tarefa_marcada.usuario_id ='.(int)$Aplic->usuario_id);


if ($dept_id && !$lista_depts) {
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_depts','tarefa_depts', 'tarefa_depts.tarefa_id=tarefas.tarefa_id');
	$sql->adOnde('tarefa_dept='.(int)$dept_id.' OR tarefa_depts.departamento_id='.(int)$dept_id);
	if ($baseline_id) $sql->adOnde('tarefa_depts.baseline_id='.(int)$baseline_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_depts','tarefa_depts', 'tarefa_depts.tarefa_id=tarefas.tarefa_id');
	$sql->adOnde('tarefa_dept IN ('.$lista_depts.') OR tarefa_depts.departamento_id IN ('.$lista_depts.')');
	if ($baseline_id) $sql->adOnde('tarefa_depts.baseline_id='.(int)$baseline_id);
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_cia', 'tarefa_cia', 'tarefas.tarefa_id=tarefa_cia_tarefa');
	$sql->adOnde('tarefa_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tarefa_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	if ($baseline_id) $sql->adOnde('tarefa_cia.baseline_id='.(int)$baseline_id);
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('tarefa_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('tarefa_cia IN ('.$lista_cias.')');	


if ($projeto_id) $sql->adOnde('projetos.projeto_id='.(int)$projeto_id);
if (!$projeto_id && !$tarefa_id) $sql->adOrdem('projeto_nome');
$projetos = array();
$lista_projetos=$sql->Lista();
$sql->limpar();


foreach($lista_projetos as $linha){
	$projetos[$linha['projeto_id']] = $linha;
	}


$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos','projetos');
$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas', 't1', 'projetos.projeto_id = t1.tarefa_projeto');
$sql->adCampo('projeto_id, COUNT(t1.tarefa_id) AS total_tarefas');
if (isset($lista_onde) && $lista_onde) $sql->adOnde($lista_onde);
if ($baseline_id) {
		$sql->adOnde('projetos.baseline_id='.(int)$baseline_id);
		$sql->adOnde('t1.baseline_id='.(int)$baseline_id);
		}
$sql->adGrupo('projeto_id');
$lista_projetos=$sql->Lista();
$sql->limpar();

foreach($lista_projetos as $linha2){
	if (isset($projetos[$linha2['projeto_id']]) && $projetos[$linha2['projeto_id']]) array_push($projetos[$linha2['projeto_id']], $linha2);
	}

$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas', 'tarefas');
$sql->esqUnir(($baseline_id ? 'baseline_' : '').'projetos', 'p', 'p.projeto_id = tarefa_projeto');
$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_log', 'tlog', 'tlog.tarefa_log_tarefa = tarefas.tarefa_id AND tlog.tarefa_log_problema > 0'.($baseline_id? ' AND tlog.baseline_id='.(int)$baseline_id : ''));
$sql->esqUnir('arquivos', 'f', 'tarefas.tarefa_id = f.arquivo_tarefa');
$sql->esqUnir('usuario_tarefa_marcada', 'marcada', 'tarefas.tarefa_id = marcada.tarefa_id AND marcada.usuario_id = '.(int)$Aplic->usuario_id);
if ($baseline_id){
	$sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
	$sql->adOnde('p.baseline_id='.(int)$baseline_id);
	}
$sql->adCampo('tarefas.tarefa_id, tarefa_acao, tarefa_acesso, tarefa_superior, tarefa_nome, tarefa_inicio, tarefa_fim, tarefa_dinamica, count(tarefas.tarefa_superior) as subordinada, tarefa_marcada, tarefa_prioridade, tarefa_percentagem, tarefa_duracao, tarefa_duracao_tipo, tarefa_projeto, tarefa_descricao, tarefa_dono, tarefa_status');
$sql->adCampo('tarefa_marco');
$sql->adCampo('count(distinct f.arquivo_tarefa) as nr_arquivos');
$sql->adCampo('tlog.tarefa_log_problema');

$sq = new BDConsulta;
$sq->adTabela(($baseline_id ? 'baseline_' : '').'tarefas', 'starefas');
if ($baseline_id) $sq->adOnde('starefas.baseline_id='.(int)$baseline_id);
$sq->adCampo('COUNT(DISTINCT tarefa_id)');
$sq->adOnde('starefas.tarefa_id != tarefas.tarefa_id AND starefas.tarefa_superior = tarefas.tarefa_id');
$subconsulta = $sq->prepare();
$sq->limpar();

$sql->adCampo('('.$subconsulta.') AS tarefa_nr_subordinadas');
$sql->adCampo('diferenca_data(tarefa_fim,tarefa_inicio) as dias');
if ($projeto_id) {
	$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
	$f = 'todos';
	}
else {
	$sql->adOnde('projeto_ativo = 1');
	$sql->adOnde('projeto_template = 0');
	}
if ($tarefa_id) $f = 'subordinadaProfunda';
if ($mostrar_marcada) $sql->adOnde('tarefa_marcada = 1');
$f = (($f) ? $f : '');
$nunca_mostrar_com_pontos = array('subordinada', '');
switch ($f) {
	case 'todos':
		break;
	case 'minhasterminadas7dias':
		$sql->adOnde('ut.usuario_id ='.(int)$Aplic->usuario_id);
	case 'todasterminadas7dias':
		$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_designados','tarefa_designados','tarefa_designados.tarefa_id = tarefas.tarefa_id');
		if ($baseline_id) $sql->adOnde('tarefa_designados.baseline_id='.(int)$baseline_id);
		$sql->adOnde('tarefa_designados.usuario_id ='.(int)$Aplic->usuario_id);
		$sql->adOnde('tarefa_percentagem = 100');
		$sql->adOnde('tarefa_fim >= \''.date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'))).'\'');
		break;
	case 'subordinada':
		$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
		$sql->adOnde('tarefas.tarefa_id !='.(int)$tarefa_id);
		break;
	case 'subordinadaProfunda':
		$tarefaobj = new CTarefa;
		$tarefaobj->load((int)$tarefa_id);
		$subordinadaProfunda = $tarefaobj->getSubordinadaProfunda();
		if (count($subordinadaProfunda)) $sql->adOnde('tarefas.tarefa_id IN ('.implode(',', $subordinadaProfunda).')');
		$sql->adOnde('tarefas.tarefa_id !='.(int)$tarefa_id);
		break;
	case 'meuProj':
		$sql->adOnde('projeto_responsavel ='.(int)$Aplic->usuario_id);
		break;
	case 'minhaCia':
		if (!$Aplic->usuario_cia) $Aplic->usuario_cia = 0;
		$sql->adOnde('projeto_cia = '.(int)$Aplic->usuario_cia);
		break;
	case 'meu':
		$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_designados','tarefa_designados','tarefa_designados.tarefa_id = tarefas.tarefa_id');
		if ($baseline_id) $sql->adOnde('tarefa_designados.baseline_id='.(int)$baseline_id);
		$sql->adOnde('tarefa_designados.usuario_id ='.(int)$Aplic->usuario_id.' OR tarefa_dono='.(int)$Aplic->usuario_id);
		break;
	case 'minhasIncompletas':
		$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_designados','tarefa_designados','tarefa_designados.tarefa_id = tarefas.tarefa_id');
		if ($baseline_id) $sql->adOnde('tarefa_designados.baseline_id='.(int)$baseline_id);
		$sql->adOnde('tarefa_designados.usuario_id ='.(int)$Aplic->usuario_id.' OR tarefa_dono='.(int)$Aplic->usuario_id);
		$sql->adOnde('tarefa_percentagem < 100 AND tarefa_marco=0');
		break;
	case 'todasIncompletas':
		$sql->adOnde('tarefa_percentagem < 100 AND tarefa_marco=0');
		break;
	case 'semDesignado':
		$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_designados', 'vazio', 'tarefas.tarefa_id = vazio.tarefa_id');
		if ($baseline_id) $sql->adOnde('vazio.baseline_id='.(int)$baseline_id);
		$sql->adOnde('vazio.tarefa_id IS NULL');
		break;
	case 'tarefaCriada':
		$sql->adOnde('tarefa_criador ='.(int)$Aplic->usuario_id);
		break;
	default:
		$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_designados','tarefa_designados','tarefa_designados.tarefa_id = tarefas.tarefa_id');
		if ($baseline_id) $sql->adOnde('tarefa_designados.baseline_id='.(int)$baseline_id);
		$sql->adOnde('tarefa_designados.usuario_id ='.(int)$usuario_id);
		break;
	}
if ($usuario_id) $sql->adOnde('tarefas.tarefa_dono ='.(int)$usuario_id);

if (($projeto_id || $tarefa_id) && $mostrarIncompleta) $sql->adOnde('( tarefa_percentagem < 100 OR tarefa_percentagem IS NULL)');
$tarefa_status = 0;

if (isset($tarefa_tipo) && $tarefa_tipo && ($tarefa_tipo != "-1")) $sql->adOnde('tarefa_tipo = "'.$tarefa_tipo.'"');
if (isset($tarefa_dono) && $tarefa_dono && ($tarefa_dono != -1)) $sql->adOnde('tarefa_dono = '.(int)$tarefa_dono);
	if (($projeto_id || !$tarefa_id) && !$ver_min) {
	if ($pesquisar_texto) $sql->adOnde('( tarefa_nome LIKE (\'%'.$pesquisar_texto.'%\') OR tarefa_descricao LIKE (\'%'.$pesquisar_texto.'%\') )');
	}
$projetos_filtro = '';
$tarefas_filtro = '';


if ($dept_id && !$lista_depts) {
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_depts','tarefa_depts', 'tarefa_depts.tarefa_id=tarefas.tarefa_id');
	$sql->adOnde('tarefa_dept='.(int)$dept_id.' OR tarefa_depts.departamento_id='.(int)$dept_id);
	if ($baseline_id) $sql->adOnde('tarefa_depts.baseline_id='.(int)$baseline_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_depts','tarefa_depts', 'tarefa_depts.tarefa_id=tarefas.tarefa_id');
	$sql->adOnde('tarefa_dept IN ('.$lista_depts.') OR tarefa_depts.departamento_id IN ('.$lista_depts.')');
	if ($baseline_id) $sql->adOnde('tarefa_depts.baseline_id='.(int)$baseline_id);
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_cia', 'tarefa_cia', 'tarefas.tarefa_id=tarefa_cia_tarefa');
	$sql->adOnde('tarefa_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tarefa_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	if ($baseline_id) $sql->adOnde('tarefa_cia.baseline_id='.(int)$baseline_id);
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('tarefa_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('tarefa_cia IN ('.$lista_cias.')');	

if (!$projeto_id && !$tarefa_id) $sql->adOrdem('p.projeto_id, '.($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio');
else $sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio');
$tarefas = $sql->Lista();
$sql->limpar();




foreach ($tarefas as $linha) {
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_designados','tarefa_designados');
	if ($baseline_id) $sql->adOnde('tarefa_designados.baseline_id='.(int)$baseline_id);
	$sql->adCampo('usuario_id, perc_designado, perc_designado as designado');
	$sql->adOnde('tarefa_id = '.(int)$linha['tarefa_id']);
	$sql->adGrupo('usuario_id');
	$sql->adOrdem('perc_designado desc');
	$usuarios_designados = array();
	$linha['tarefa_designado_usuarios'] = $sql->Lista();
	$sql->limpar();
	$projetos[$linha['tarefa_projeto']]['tarefas'][] = $linha;
	}
$mostrarCaixachecarEditar = ((isset($podeEditar) && $podeEditar && config('editar_designado_diretamente')) ? true : false);

global $historico_ativo;


$expandido = $tarefa_id ? true : $Aplic->getPref('tarefasexpandidas');
if ($projeto_id) {
	echo "<form name='tarefa_list_options' method='POST' action='$texto_consulta'>";
	echo "<input type='hidden' name='mostrar_tarefa_options' value='1' />";
	echo "<input type='hidden' name='projeto_id' value='".$projeto_id."' />";
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><th align="right"><input type="checkbox" name="mostrar_incompleta" id="mostrar_incompleta" onclick="document.tarefa_list_options.submit();" '.( $mostrarIncompleta ? 'checked="checked"' : '').' />'.dica('Apen'.$config['genero_tarefa'].'s '.$config['tarefas'].' não completadas', 'Não ver '.$config['genero_tarefa'].'s '.$config['tarefas'].' já 100% completas.').'<label for="mostrar_incompleta">Apen'.$config['genero_tarefa'].'s '.$config['tarefas'].' não completadas</label>'.dicaF().'</th></tr>';
	echo '</table></form>';
	}
echo '<form name="frm" id="frm" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
if ($projeto_id) echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="modificar_datas_tarefas" id="modificar_datas_tarefas" value="" />';
echo '<input type="hidden" name="semanas" id="semanas" value="" />';
echo '<input type="hidden" name="dialogo" id="dialogo" value="" />';
$titulos = '<table id="tblProjetos" width="100%" border=0 cellpadding=0 cellspacing=0 class="tbl1">';
$titulos .= '<tr>';
$titulos .= '<th width="10">&nbsp;</th>';
$titulos .= '<th width="10">'.dica('Marcar ou Desmarcar', 'Clique nos ícones '.imagem('icones/desmarcada.gif').'  '.imagem('icones/marcada.gif').' , para marcar ou desmascar '.$config['genero_tarefa'].'s '.$config['tarefas'].'.<p> A marcação tem a finalidade de chamar a atenção, visualmente, para uma determinad'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<b>M</b>'.dicaF().'</th>';
$titulos .= '<th width="10">'.dica('Registrar', 'Clique no ícone '.imagem('icones/adicionar.png').' abaixo para criar um registro para '.$config['genero_tarefa'].' '.$config['tarefa'].' selecionad'.$config['genero_tarefa'].'.').'<b>R</b>'.dicaF().'</th>';
$titulos .= '<th width="20"><b>'.dica('Porcentual d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Realizada', 'Percentagem realizada d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Feito'.dicaF().'</b></th>';
$titulos .= '<th align="center"><b>'.dica('Prioridade', 'O nível de prioridade.').'P'.dicaF().'</b></th>';
$titulos .= '<th><b>'.dica('Nome d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Nome definido para '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Tarefa'.dicaF().'</b></th>';
$titulos .= '<th nowrap="nowrap"><b>'.dica('Responsável', 'Responsável pel'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Responsável'.dicaF().'</b></th>';
$titulos .= '<th nowrap="nowrap"><b>'.dica(ucfirst($config['usuarios']).' Designados', 'Nos campos abaixo são mostrados '.$config['genero_usuario'].'s '.$config['usuarios'].' que foram designados para cad'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como o grau de comprometimento dos mesmos com '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Designados'.dicaF().'</b></th>';
$titulos .= '<th nowrap="nowrap" width="120"><b>'.dica('Data de Início', 'Data de início tarefas.').'Início'.dicaF().'</b></th>';
$titulos .= '<th nowrap="nowrap"><b>'.dica('Duração', 'Duração d'.$config['genero_tarefa'].'s '.$config['tarefas'].' em dias uteis de '.config('horas_trab_diario').' horas.<br>No caso d'.$config['genero_tarefa'].'s '.$config['tarefas'].' diâmic'.$config['genero_tarefa'].'s será o somatório da duração d'.$config['genero_tarefa'].'s '.$config['tarefas'].' filh'.$config['genero_tarefa'].'s.').'Dur.'.dicaF().'</b></th>';
$titulos .= '<th nowrap="nowrap" width="120"><b>'.dica('Data de Término', 'Término d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Término'.dicaF().'</b></th>';
$titulos .= '<th nowrap="nowrap"><b>'.dica('Dias', 'Número de dias entre o início e término d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Dias'.dicaF().'</b></th>';

if ($mostrarCaixachecarEditar && $m!='tarefas') $titulos .= '<th width="1">&nbsp;</th>';
else $cols--;
$titulos .= '</tr>';
reset($projetos);
if ($config['editar_designado_diretamente']) {
	$tempoTarefa = new CTarefa();
	$usuarioDesig = $tempoTarefa->getDesignacao('usuario_id', null, true, $cia_id);
	}

$qnt=0;

foreach ($projetos as $k => $p) {

	if (!isset($p['projeto_id']) && isset($p['tarefas'][0]['tarefa_projeto'])){
		//tentar construir os dados faltando...

		$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos','projetos');
		if ($baseline_id) $sql->adOnde('projetos.baseline_id='.(int)$baseline_id);
		$sql->adCampo('projeto_acesso, projeto_cor, projeto_percentagem');
		$sql->adOnde('projeto_id = '.(int)$p['tarefas'][0]['tarefa_projeto']);
		$dados=$sql->Linha();
		$sql->limpar();

		$p['projeto_acesso']=$dados['projeto_acesso'];
		$p['projeto_cor']=$dados['projeto_cor'];
		$p['projeto_percentagem']=$dados['projeto_percentagem'];
		$p['projeto_id']=(int)$p['tarefas'][0]['tarefa_projeto'];
		}

	if (isset($p['projeto_id']) && permiteAcessar($p['projeto_acesso'], $p['projeto_id'], 0)){
		$qnt++;
		if (isset($p['tarefas'])) $tnums = count($p['tarefas']);
		else $tnums = 0;
		if ($tnums > 0 || $projeto_id == $p['projeto_id']) {
			echo '<form name="frmDesignar'.$p['projeto_id'].'" id="frmDesignar'.$p['projeto_id'].'" method="post">';
			echo '<input type="hidden" name="m" value="'.$m.'" />';
			echo '<input type="hidden" name="a" value="'.$a.'" />';
			if (!$ver_min) {
				$abrir_link ='';
				echo '<input type="hidden" name="del" value="1" />';
				echo '<input type="hidden" name="rm" value="0" />';
				echo '<input type="hidden" name="store" value="0" />';
				echo '<input type="hidden" name="fazerSQL" value="fazer_tarefa_designar_aed" />';
				echo '<input type="hidden" name="projeto_id" value="'.$p['projeto_id'].'" />';
				echo '<input type="hidden" name="listaDesignados" />';
				echo '<input type="hidden" name="htarefas" />';
				echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 >';
				echo '<tr>';
			  echo '<td colspan="'.($config['editar_designado_diretamente'] ? $cols - 4 : $cols - 1).'"  style="background: #f2f0ec">';
				echo '<table width="100%" border=0><tr>';
				echo '<td style="background-color:#'.$p['projeto_cor'].';color:#'.melhorCor($p['projeto_cor']).';" nowrap="nowrap">'.link_projeto($k, true).'</a></td>';
				echo '<td style="background: #f2f0ec" width="'.(101 - intval($p['projeto_percentagem'])).'%">'.(intval($p['projeto_percentagem'])).'%</td>';
			  echo '</tr></table>';
			  echo '</td>';
				echo '</tr></table>';
				}

			echo $titulos;
			if ($tarefa_ordenar_item1 != '') {
				if ($tarefa_ordenar_item2 != '' && $tarefa_ordenar_item1 != $tarefa_ordenar_item2)	$p['tarefas'] = vetor_ordenar($p['tarefas'], $tarefa_ordenar_item1, $tarefa_ordenar_ordem1, $tarefa_ordenar_tipo1, $tarefa_ordenar_item2, $tarefa_ordenar_ordem2, $tarefa_ordenar_tipo2);
				else $p['tarefas'] = vetor_ordenar($p['tarefas'], $tarefa_ordenar_item1, $tarefa_ordenar_ordem1, $tarefa_ordenar_tipo1);
				}
			global $tarefas_filtradas, $subordinada_de;

			if (isset($p['tarefas']) && is_array($p['tarefas'])) {
				foreach ($p['tarefas'] as $i => $t) {
					$tarefas_filtradas[] = $t['tarefa_id'];
					$subordinada_de[$t['tarefa_superior']] = (isset($subordinada_de[$t['tarefa_superior']])&& $subordinada_de[$t['tarefa_superior']] ? $subordinada_de[$t['tarefa_superior']] : array());
					if ($t['tarefa_superior'] != $t['tarefa_id']) array_push($subordinada_de[$t['tarefa_superior']], $t['tarefa_id']);
					}
				}
			$saida='';
			if (isset($p['tarefas']) && is_array($p['tarefas'])) {
				global $tarefas_mostradas;
				$tarefas_mostradas = array();
				$superior_tarefas = array();
			//	reset($p);

				foreach ($p['tarefas'] as $i => $t1) {
					if ($tarefa_ordenar_item1) {
						}
					else {
							if (($t1['tarefa_superior'] == $t1['tarefa_id']) && !$tarefa_id) {
							$sem_subordinada = empty($subordinada_de[$t1['tarefa_id']]);
							$saida.=mostrarTarefa($t1, 0, true, false, $sem_subordinada,false, $baseline_id);
							$tarefas_mostradas[] = $t1['tarefa_id'];
							$saida.=acharSubordinada($p['tarefas'], $t1['tarefa_id'], 0, $baseline_id);
							}
						elseif ($t1['tarefa_superior'] == $tarefa_id && $tarefa_id) {
							$sem_subordinada = empty($subordinada_de[$t1['tarefa_id']]);
							$saida.=mostrarTarefa($t1, 0, true, false, $sem_subordinada,false, $baseline_id);
							$tarefas_mostradas[] = $t1['tarefa_id'];
							$saida.=acharSubordinada($p['tarefas'], $t1['tarefa_id'], 0, $baseline_id);
							}
						}
					}
				//reset($p);
				foreach ($p['tarefas'] as $i => $t1) {
					if (!in_array($t1['tarefa_id'], $tarefas_mostradas)) {
						$saida.=mostrarTarefa($t1, -1, true, false, true, false, $baseline_id);
						$tarefas_mostradas[] = $t1['tarefa_id'];
						}
					}
				}
			echo $saida;
			if (!count($p['tarefas'])) echo '<tr><td colspan="'.$cols.'"><p>Não há nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].'.</p></td></tr>';
			echo '</table>';
			if ($tnums && !$ver_min) {
				echo '<table width="100%" style="background: #f2f0ec">';
			  echo '<tr><td>'.botao('gráfico Gantt', 'Gráfico Gantt','Visualizar o gráfico Gantt d'.$config['genero_projeto'].' '.$config['projeto'].' dest'.$config['genero_tarefa'].'s '.$config['tarefas'].'.','','url_passar(0, \'m=tarefas&a=ver_gantt&projeto_id='.$k.'\');','','',0).'</td></tr>';
			  echo '</table></td></tr><tr><td>&nbsp;</td></tr></table>';
				}
			}
		echo '</form>';
		}
	}

if (!count($projetos)) echo '<table width="100%" border=0 style="background: #ffffff"><tr><td><p>'.($config['genero_tarefa']=='o'? 'Nenhum' : 'Nenhuma').' '.$config['tarefa'].' encontrad'.$config['genero_tarefa'].'.</p></td><td align="right"></td></tr></table>';
elseif (!$qnt) echo '<table width="100%" border=0 style="background: #ffffff"><tr><td><p>Não tem autorização para visualizar nenhum d'.$config['genero_tarefa'].'s '.$config['tarefa'].'.</p></td><td align="right"></td></tr></table>';
else {
	$Aplic->salvarPosicao();
	echo '<table width="100%" border=0 style="background: #f2f0ec">';
	echo '<tr><td><table><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffffff" nowrap="nowrap">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Futur'.$config['genero_tarefa'], ucfirst($config['tarefa']).' futur'.$config['genero_tarefa'].' é '.$config['genero_tarefa'].' em que a data de ínicio  ainda não ocorreu.').ucfirst($config['tarefa']).' futura'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px" bgcolor="#e6eedd" nowrap="nowrap">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Iniciad'.$config['genero_tarefa'].' e Dentro do Prazo', ucfirst($config['tarefa']).' iniciad'.$config['genero_tarefa'].' e dentro do prazo é '.$config['genero_tarefa'].' em que a data de ínicio  já ocorreu, e a mesma já está acima de 0% executada, entretanto ainda não se chegou na data de término.').'Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffeebb" nowrap="nowrap">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' que Deveria ter Iniciad'.$config['genero_tarefa'], ucfirst($config['tarefa']).' futur'.$config['genero_tarefa'].' é '.$config['genero_tarefa'].' em que a data de ínicio já ocorreu, entretanto ainda se encontra em 0% executad'.$config['genero_tarefa'].'.').'Deveria ter iniciada'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px" bgcolor="#cc6666" nowrap="nowrap">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' em Atraso', ucfirst($config['tarefa']).' em atraso é '.$config['genero_tarefa'].' em que a data de término já ocorreu, entretanto ainda não se encontra em 100% executad'.$config['genero_tarefa'].'.').'Em atraso'.dicaF().'</td>';
	echo '<td style="border-style:solid;border-width:1px" bgcolor="#aaddaa" nowrap="nowrap">&nbsp;&nbsp;&nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Feit'.$config['genero_tarefa'], ucfirst($config['tarefa']).' feit'.$config['genero_tarefa'].' é '.$config['genero_tarefa'].' em que se encontra 100% executada.').'Feit'.$config['genero_tarefa'].dicaF().'</td>';
	echo '</tr></table></td></tr></table>';
	}


function lista_tarefas_subordinadas($tarefa_id, &$vetor=array()){
	global $baseline_id;
	$sql = new BDConsulta;
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	if ($baseline_id) $sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
	$sql->adCampo('tarefa_id');
	$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
	$sql->adOnde('tarefa_id != '.(int)$tarefa_id);
	$lista=$sql->carregarColuna();
	$sql->limpar();
	foreach($lista as $tarefa){
		$vetor[]=$tarefa;
		 lista_tarefas_subordinadas($tarefa, $vetor);
		}
	}




?>

<script type="text/JavaScript">

function popLog(tarefa_id) {
    if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp('Registro',800, 465,'m=tarefas&a=ver_log_atualizar_pro&dialogo=1&tarefa_id='+tarefa_id,window.retornoLog, window);
    else window.open('./index.php?m=tarefas&a=ver_log_atualizar&dialogo=1&tarefa_id='+tarefa_id, 'Registro','height=322,width=800px,resizable,scrollbars=no');
	}

function retornoLog(update){
    if(update){
        url_passar(false,'m=tarefas&a=index');
        }
    }

function ativar_usuarios(id){
  var element = document.getElementById(id);
  element.style.display = (element.style.display == '' || element.style.display == "none") ? "inline" : "none";
	}

function marcarTodas(projeto_id) {
	var f = eval('document.frmDesignar' + projeto_id);
	var cFlag = f.master.checked ? false : true;
	for (var i=0, i_cmp=f.elements.length; i<i_cmp;i++) {
		var e = f.elements[i];
		if(e.type == 'checkbox' && e.checked == cFlag && e.name != 'master')	e.checked = !e.checked;
		}
	}

function chDesignacao(projeto_id, rmUsuario, del) {
	var f = eval('document.frmDesignar' + projeto_id);
	var fl = f.ad_usuarios.length-1;
	var c = 0;
	var a = 0;
	f.listaDesignados.value = '';
	f.htarefas.value = '';
	var qnt=0;
	for (var i=0, i_cmp=f.elements.length; i<i_cmp;i++) {
		var e = f.elements[i];
		if(e.type == 'checkbox' && e.checked == true && e.name != 'master') {
			c++;
			f.htarefas.value+=(qnt++ ? ',' : '')+e.value;
			}
		}
	qnt=0;
	for (fl; fl > -1; fl--) {
		if (f.ad_usuarios.options[fl].selected) {
			a++;
			f.listaDesignados.value +=(qnt++ ? ',' : '')+f.ad_usuarios.options[fl].value;
			}
		}
	if (del == true) {
		if (c == 0) alert ('Por favor selecione pelo menos um<?php echo ($config["genero_tarefa"]=="a" ?  "a" : "")." ".$config["tarefa"]?>');
		else if (a == 0 && rmUsuario == 1) alert ('Por favor selecione pelo menos um designado');
		else if (confirm('Tem a certeza que deseja remover o <?php $config["usuario"]?> da(s) tarefa(s)?')) {
			f.del.value = 1;
			f.rm.value = rmUsuario;
			f.projeto_id.value = projeto_id;
			f.submit();
			}
		}
	else {
		if (c == 0) alert ('Por favor selecione pelo menos um<?php echo ($config["genero_tarefa"]=="a" ?  "a" : "")." ".$config["tarefa"]?>');
		else if (a == 0) alert ('Por favor selecione pelo menos um designado');
		else {
			f.rm.value = rmUsuario;
			f.del.value = del;
			f.projeto_id.value = projeto_id;
			f.submit();
			}
		}
	}


function adBlocoComponente(li){
	if(document.all||navigator.appName=="Microsoft Internet Explorer"){
		var form=document.frm_parte;
		var ni=document.getElementById('tblProjetos');
		var newitem=document.createElement('input');
		var htmltxt="";
		newitem.id='parte_selecionado_tarefa['+li+']';
		newitem.name='parte_selecionado_tarefa['+li+']';
		newitem.type='hidden';
		ni.appendChild(newitem)
		}
	else{
		var form=document.frm_parte;
		var ni=document.getElementById('tblProjetos');
		var newitem=document.createElement('input');
		newitem.setAttribute("id",'parte_selecionado_tarefa['+li+']');
		newitem.setAttribute("name",'parte_selecionado_tarefa['+li+']');
		newitem.setAttribute("type",'hidden');ni.appendChild(newitem)
		}
	}

function removerBlocoComponente(li){
	var t=document.getElementById('tblProjetos');
	var old=document.getElementById('parte_selecionado_tarefa['+li+']');
	t.removeChild(old);
	}


var estah_marcado=null;


</script>