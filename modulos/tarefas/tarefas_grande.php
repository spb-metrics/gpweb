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

global $estilo_interface, $obj, $projeto_id, $mostrarCaixachecarEditar, $editar, $Aplic, $editar, $baseline_id;

$tarefa_superior=intval(getParam($_REQUEST, 'tarefa_superior', 0));

$pagina = getParam($_REQUEST, 'pagina', 1);
$xpg_tamanhoPagina = $config['qnt_tarefas'];

$xpg_min = $xpg_tamanhoPagina * ($pagina - 1);


$mover=array();
$mover[]='';
for ($i=1;$i<=12;$i++) $mover['m'.$i]='+'.($i < 10 ? '0':'').$i.' mes'.($i>1 ? 'es' : '');
for ($i=1;$i<=5;$i++) $mover['s'.$i]='+'.($i < 10 ? '0':'').$i.' semana'.($i>1 ? 's' : '');
for ($i=1;$i<=30;$i++) $mover['d'.$i]='+'.($i < 10 ? '0':'').$i.' dia'.($i>1 ? 's' : '');
for ($i=-1;$i>=-12;$i--) $mover['m'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' mes'.($i<-1 ? 'es' : '');
for ($i=-1;$i>=-5;$i--) $mover['s'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' semana'.($i<-1 ? 's' : '');
for ($i=-1;$i>=-30;$i--) $mover['d'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' dia'.($i<-1 ? 's' : '');

$cols = 13;

$sql = new BDConsulta;

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
	
	//$sinal = $semanas >= 0 ? 1 : -1;
	foreach ($vetor as $tarefa_id) {
		$sql->adTabela('tarefas');
		$sql->adCampo('adiciona_data((select tarefa_inicio FROM tarefas WHERE tarefa_id='.(int)$tarefa_id.'), '.$semanas.', \''.$periodo.'\') AS inicio');
		$sql->adCampo('tarefa_duracao, tarefa_cia, tarefa_projeto');
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$dados=$sql->Linha();
		$sql->limpar();
		
		$sql->adTabela('tarefas');		
		//$inicio = ajusta_dia_util($dados['inicio'], $sinal, $dados['tarefa_cia'], 0, $dados['tarefa_projeto'], 0, $tarefa_id);
		$fim = calculo_data_final_periodo($dados['inicio'], $dados['tarefa_duracao'], $dados['tarefa_cia'], 0, $dados['tarefa_projeto'], 0, $tarefa_id);
        $sql->adAtualizar('tarefa_inicio_manual', $dados['inicio']);
        $sql->adAtualizar('tarefa_fim_manual', $fim);
		$sql->adAtualizar('tarefa_inicio', $dados['inicio']);
		$sql->adAtualizar('tarefa_fim', $fim);
		$sql->adOnde('tarefa_id   = '.(int)$tarefa_id);
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
if (isset($_REQUEST['marcada'])) {
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
		else $sql->limpar();
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




$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
$sql->adCampo('count(tarefa_id)');
$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
if ($baseline_id) $sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
if ($tarefa_superior)	$sql->adOnde('tarefa_superior='.$tarefa_superior.' AND tarefas.tarefa_id!='.$tarefa_superior);
else $sql->adOnde('tarefa_superior=tarefas.tarefa_id');
$xpg_totalregistros = $sql->Resultado();
$sql->limpar();

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
$sq->adCampo('COUNT(tarefa_id)');
$sq->adOnde('starefas.tarefa_id != tarefas.tarefa_id AND starefas.tarefa_superior = tarefas.tarefa_id');
$subconsulta = $sq->prepare();
$sq->limpar();

$sql->adCampo('('.$subconsulta.') AS tarefa_nr_subordinadas');
$sql->adCampo('diferenca_data(tarefa_fim,tarefa_inicio) as dias');	
$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);

if ($tarefa_superior){
	$sql->adOnde('tarefa_superior='.$tarefa_superior.' AND tarefas.tarefa_id!='.$tarefa_superior);
	$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio');
	}
else {
	$sql->adOnde('tarefa_superior=tarefas.tarefa_id');
	$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio');
	}	
$sql->setLimite($xpg_min, $xpg_tamanhoPagina);
$sql->adGrupo('tarefas.tarefa_id');
$tarefas = $sql->Lista();
$sql->limpar();


//se há uma superior garantir que seja a 1a tarefa do vetor
if ($tarefa_superior){

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
	$sq->adCampo('COUNT(tarefa_id)');
	$sq->adOnde('starefas.tarefa_id != tarefas.tarefa_id AND starefas.tarefa_superior = tarefas.tarefa_id');
	$subconsulta = $sq->prepare();
	$sq->limpar();
	$sql->adCampo('('.$subconsulta.') AS tarefa_nr_subordinadas');
	$sql->adCampo('diferenca_data(tarefa_fim,tarefa_inicio) as dias');	
	$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
	$sql->adOnde('tarefas.tarefa_id='.$tarefa_superior);
	$sql->adGrupo('tarefas.tarefa_id');
	$tarefa_pai = $sql->lista();
	$sql->limpar();
	
	$tarefas = array_merge((array)$tarefa_pai, (array)$tarefas);	
	}





$expandido = $tarefa_id ? true : $Aplic->getPref('tarefasexpandidas');

echo '<form name="frm" id="frm" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';				
echo '<input type="hidden" name="modificar_datas_tarefas" id="modificar_datas_tarefas" value="" />';	 
echo '<input type="hidden" name="semanas" id="semanas" value="" />';	
echo '<input type="hidden" name="dialogo" id="dialogo" value="" />'; 
echo '<input type="hidden" name="tarefa_superior" id="tarefa_superior" value="'.$tarefa_superior.'" />';
echo '<input type="hidden" name="clonar_tarefas" id="clonar_tarefas" value="" />';	
echo '<input type="hidden" name="mover_tarefas" id="mover_tarefas" value="" />';	

$xpg_total_paginas = (($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0);
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $config['tarefa'], $config['tarefas'],'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table id="tblProjetos" width="100%" border=0 cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
echo '<th width="10">&nbsp;</th>';
echo '<th width="10">'.dica('Marcar ou Desmarcar', 'Clique nos ícones '.imagem('icones/desmarcada.gif').'  '.imagem('icones/marcada.gif').' , para marcar ou desmascar '.$config['genero_tarefa'].'s '.$config['tarefas'].'.<p> A marcação tem a finalidade de chamar a atenção, visualmente, para uma determinad'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<b>M</b>'.dicaF().'</th>';
echo '<th width="10">'.dica('Registrar', 'Clique no ícone '.imagem('icones/adicionar.png').' abaixo para criar um registro para '.$config['genero_tarefa'].' '.$config['tarefa'].' selecionad'.$config['genero_tarefa'].'.').'<b>R</b>'.dicaF().'</th>';
echo '<th width="20"><b>'.dica('Porcentual d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Realizada', 'Percentagem realizada d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Feito'.dicaF().'</b></th>';
echo '<th align="center"><b>'.dica('Prioridade', 'O nível de prioridade.').'P'.dicaF().'</b></th>';
echo '<th><b>'.dica('Nome d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Nome definido para '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Tarefa'.dicaF().'</b></th>';
echo '<th nowrap="nowrap"><b>'.dica('Responsável', 'Responsável pel'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Responsável'.dicaF().'</b></th>';
echo '<th nowrap="nowrap"><b>'.dica(ucfirst($config['usuarios']).' Designados', 'Nos campos abaixo são mostrados '.$config['genero_usuario'].'s '.$config['usuarios'].' que foram designados para cad'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como o grau de comprometimento dos mesmos com '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Designados'.dicaF().'</b></th>';
echo '<th nowrap="nowrap" width="120"><b>'.dica('Data de Início', 'Data de início tarefas.').'Início'.dicaF().'</b></th>';
echo '<th nowrap="nowrap"><b>'.dica('Duração', 'Duração d'.$config['genero_tarefa'].'s '.$config['tarefas'].' em dias uteis de '.config('horas_trab_diario').' horas.<br>No caso d'.$config['genero_tarefa'].'s '.$config['tarefas'].' diâmic'.$config['genero_tarefa'].'s será o somatório da duração d'.$config['genero_tarefa'].'s '.$config['tarefas'].' filh'.$config['genero_tarefa'].'s.').'Dur.'.dicaF().'</b></th>';
echo '<th nowrap="nowrap" width="120"><b>'.dica('Data de Término', 'Término d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Término'.dicaF().'</b></th>';
echo '<th nowrap="nowrap"><b>'.dica('Dias', 'Número de dias entre o início e término d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Dias'.dicaF().'</b></th>';
echo '<th width="1">&nbsp;</th>';
echo '</tr>';

$qnt=0;	

foreach ($tarefas as $arr) {
	$qnt++;
	echo mostrarTarefaGrande($arr, $projeto_id, $editar, $tarefa_superior, $baseline_id);
	}
if (!count($tarefas)) echo '<table width="100%" border=0 style="background: #ffffff"><tr><td><p>'.($config['genero_tarefa']=='o'? 'Nenhum' : 'Nenhuma').' '.$config['tarefa'].' encontrad'.$config['genero_tarefa'].'.</p></td><td align="right"></td></tr></table>';
elseif (!$qnt) echo '<table width="100%" border=0 style="background: #ffffff"><tr><td><p>Não tem autorização para visualizar nenhum d'.$config['genero_tarefa'].'s '.$config['tarefa'].'.</p></td><td align="right"></td></tr></table>';
else {
	$Aplic->salvarPosicao();
	echo '<table width="100%" border=0 style="background: #f2f0ec" cellpadding=0 cellspacing=0>';	
	echo '<tr><td><table cellpadding=0 cellspacing=0><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffffff" nowrap="nowrap">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Futur'.$config['genero_tarefa'], ucfirst($config['tarefa']).' futur'.$config['genero_tarefa'].' é '.$config['genero_tarefa'].' em que a data de ínicio  ainda não ocorreu.').ucfirst($config['tarefa']).' futura'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px" bgcolor="#e6eedd" nowrap="nowrap">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Iniciad'.$config['genero_tarefa'].' e Dentro do Prazo', ucfirst($config['tarefa']).' iniciad'.$config['genero_tarefa'].' e dentro do prazo é '.$config['genero_tarefa'].' em que a data de ínicio  já ocorreu, e a mesma já está acima de 0% executada, entretanto ainda não se chegou na data de término.').'Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffeebb" nowrap="nowrap">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' que Deveria ter Iniciad'.$config['genero_tarefa'], ucfirst($config['tarefa']).' futur'.$config['genero_tarefa'].' é '.$config['genero_tarefa'].' em que a data de ínicio já ocorreu, entretanto ainda se encontra em 0% executad'.$config['genero_tarefa'].'.').'Deveria ter iniciada'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px" bgcolor="#cc6666" nowrap="nowrap">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' em Atraso', ucfirst($config['tarefa']).' em atraso é '.$config['genero_tarefa'].' em que a data de término já ocorreu, entretanto ainda não se encontra em 100% executad'.$config['genero_tarefa'].'.').'Em atraso'.dicaF().'</td>';
	echo '<td style="border-style:solid;border-width:1px" bgcolor="#aaddaa" nowrap="nowrap">&nbsp;&nbsp;&nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Feit'.$config['genero_tarefa'], ucfirst($config['tarefa']).' feit'.$config['genero_tarefa'].' é '.$config['genero_tarefa'].' em que se encontra 100% executada.').'Feit'.$config['genero_tarefa'].dicaF().'</td>';
	echo '<td width="80%" align="right">'.($editar && $m!='tarefas' && !$baseline_id ? dica('Deslocar no Tempo '.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Deslocar todas as datas d'.$config['genero_tarefa'].' '.$config['tarefa'].' acima selecionad'.$config['genero_tarefa'].'s.').'Deslocar:'.dicaF().selecionaVetor($mover, 'mover_semanas', 'size="1" class="texto" onChange="javascript:deslocar_tempo();"') : '&nbsp;').'</td>';
	if ($Aplic->profissional && $editar && !$baseline_id) {
		echo '<td>'.botao('duplicar', 'Duplicar '.ucfirst($config['tarefa']), 'Duplicar '.($config['genero_tarefa']=='o' ? 'um' : 'uma').' '.$config['tarefa'].' selecionad'.$config['genero_tarefa'].' junto com '.($config['genero_tarefa']=='o' ? 'seus' : 'suas').' '.$config['tarefa'].'s subordinad'.$config['genero_tarefa'].'s '.($config['genero_projeto']=='o' ? 'neste' : 'nesta').' '.$config['projeto'].'.','','duplicar_tarefa();').'</td>';
		echo '<td>'.botao('clonar', 'Clonar '.ucfirst($config['tarefa']), 'Clonar '.$config['genero_tarefa'].' '.$config['tarefa'].' selecionad'.$config['genero_tarefa'].' junto com '.$config['genero_tarefa'].'s subordinad'.$config['genero_tarefa'].'s para outr'.$config['genero_projeto'].' '.$config['projeto'].'.','','clonar_tarefa();').'</td>';
		echo '<td>'.botao('mover', 'Mover '.ucfirst($config['tarefa']), 'Mover '.$config['genero_tarefa'].' '.$config['tarefa'].' selecionad'.$config['genero_tarefa'].' junto com '.$config['genero_tarefa'].'s subordinad'.$config['genero_tarefa'].'s para outr'.$config['genero_projeto'].' '.$config['projeto'].'.','','mover_tarefa();').'</td>';
		}
	echo '</tr></table></td></tr></table>';
	}





function lista_tarefas_subordinadas($tarefa_id, &$vetor=array()){
	$sql = new BDConsulta;
	$sql->adTabela('tarefas');
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

<script language="javascript">

function deslocar_tempo(){
	if(document.getElementById('mover_semanas').selectedIndex == 0) return;
	
	if (numero_tarefas_selecionado() > 0){
		if(confirm("Tem certeza que deseja deslocar <?php echo $config['genero_tarefa'].'s '.$config['tarefas']?> no tempo?")){
			document.getElementById('modificar_datas_tarefas').value=1; 
			document.getElementById('semanas').value=document.getElementById('mover_semanas').options[document.getElementById('mover_semanas').selectedIndex].value; 
			document.frm.submit();
			}
		}
	else{
		alert ("Precisa selecionar ao menos <?php echo ($config['genero_tarefa']=='o' ? 'um ' : 'uma ').$config['tarefas']?>");
		document.getElementById('mover_semanas').selectedIndex = 0;
		}
	}
	
function popLog(tarefa_id) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Registro', 500, 500, 'm=tarefas&a=ver_log_atualizar&dialogo=1&projeto_id=<?php echo $projeto_id ?>&tarefa_id='+tarefa_id, null, window);
	else window.open('./index.php?m=tarefas&a=ver_log_atualizar&dialogo=1&projeto_id=<?php echo $projeto_id ?>&tarefa_id='+tarefa_id, 'Registro','height=322,width=800px,resizable,scrollbars=no');
	}	


function numero_tarefas_selecionado(){
	var f = eval('document.frm');
	var qnt=0;
	for (var i=0, i_cmp=f.elements.length; i<i_cmp; i++) {
		var e = f.elements[i];
		if(e.type == 'checkbox' && e.checked && e.name.substring(0, 18)=='selecionado_tarefa') qnt++;
		}
	return qnt; 	
	}


function mover_tarefa(){
	if (numero_tarefas_selecionado() == 0) alert("Selecione ao menos <?php echo ($config['genero_tarefa']=='a' ? 'uma' : 'um').' '.$config['tarefa'] ?>");
	else {
		window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=ProjetoMoverTarefas&tabela=projetos&cia_id='+<?php echo $obj->projeto_cia ?>, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	}	

function ProjetoMoverTarefas(chave, valor){
	if (chave > 0 && chave != <?php echo $projeto_id ?>) {
		
		document.getElementById('mover_tarefas').value=chave;
		document.frm.submit();
		}
	else if (chave == <?php echo $projeto_id ?>) alert("Selecione <?php echo ($config['genero_projeto']=='a' ? 'uma' : 'um').' '.$config['projeto']?> diferente");		
	else alert("Selecione <?php echo ($config['genero_projeto']=='a' ? 'uma' : 'um').' '.$config['projeto'] ?>");
	}

function clonar_tarefa(){
	if (numero_tarefas_selecionado() == 0) alert("Selecione ao menos <?php echo ($config['genero_tarefa']=='a' ? 'uma' : 'um').' '.$config['tarefa'] ?>");
	else {
		window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=ProjetoClonarTarefas&tabela=projetos&cia_id='+<?php echo $obj->projeto_cia ?>, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	}	

function ProjetoClonarTarefas(chave, valor){
	if (chave > 0 && chave != <?php echo $projeto_id ?>) {
		
		document.getElementById('clonar_tarefas').value=chave;
		document.frm.submit();
		}
	else if (chave == <?php echo $projeto_id ?>) alert("Selecione <?php echo ($config['genero_projeto']=='a' ? 'uma' : 'um').' '.$config['projeto']?> diferente");		
	else alert("Selecione <?php echo ($config['genero_projeto']=='a' ? 'uma' : 'um').' '.$config['projeto'] ?>");
	}


function duplicar_tarefa(){
	var f = eval('document.frm');
	var qnt=0;
	var seleciondado=null;
	for (var i=0, i_cmp=f.elements.length; i<i_cmp; i++) {
		var e = f.elements[i];
		if(e.type == 'checkbox' && e.checked && e.name.substring(0, 18)=='selecionado_tarefa')	{
			qnt++;
			seleciondado=e.value;	
			}
		}
	if (qnt > 1) alert("Selecione apenas <?php echo ($config['genero_tarefa']=='a' ? 'uma' : 'um').' '.$config['tarefa'] ?>");
	else if (qnt == 0) alert("Selecione <?php echo ($config['genero_tarefa']=='a' ? 'uma' : 'um').' '.$config['tarefa'] ?>");
	else {
		var nome_tarefa = prompt("Nome d<?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>:","");
		if (nome_tarefa!=null && nome_tarefa!='')	{
			url_passar(0, 'm=projetos&a=ver&projeto_id=<?php echo $projeto_id ?>&duplicar='+seleciondado+'&nome_tarefa='+nome_tarefa);	
			}	
		else alert('Escreva um nome válido');	
		}
	}	



	
function expandir_colapsar_grande(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
</script>	