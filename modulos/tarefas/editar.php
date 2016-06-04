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

global $dialogo, $Aplic;

$wbs_completo=getParam($_REQUEST, 'wbs_completo', null);
$sql = new BDConsulta;
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = "tarefa"');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


$tarefa_id = getParam($_REQUEST, 'tarefa_id', null);
$obj = new CTarefa();
if ($tarefa_id > 0 && !$obj->load($tarefa_id)) {
	$Aplic->setMsg(ucfirst($config['tarefa']));
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=tarefas');
	}

$tarefa_superior = isset($_REQUEST['tarefa_superior']) ? getParam($_REQUEST, 'tarefa_superior', $obj->tarefa_superior) : $obj->tarefa_superior;
$tarefa_projeto = intval($obj->tarefa_projeto);
if (!$tarefa_projeto) {
	$tarefa_projeto = getParam($_REQUEST, 'tarefa_projeto', null);
	if (!$tarefa_projeto) {
		$Aplic->setMsg('Não foi possível criar '.$config['genero_tarefa'].' '.$config['tarefa'], UI_MSG_ERRO);
		$Aplic->redirecionar('m=tarefas');
		}
	}

if (!$tarefa_id && !$Aplic->checarModulo('tarefas', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');
elseif ($tarefa_id && !$Aplic->checarModulo('tarefas', 'editar')) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');

$tipoDuracao = getSisValor('TipoDuracaoTarefa');
if (!$obj->podeAcessar($Aplic->usuario_id)) {$Aplic->redirecionar('m=publico&a=acesso_negado&err=noacesso');}
$projeto = new CProjeto();

$projeto->load($tarefa_projeto);

$permite_editar_data=true;
if ($projeto->projeto_trava_data){
	if (!$projeto->podeEditar()) $permite_editar_data=false;
	}

$sql->adTabela('tarefas');
$sql->adCampo('tarefa_id, tarefa_nome, tarefa_fim, tarefa_inicio, tarefa_marco, tarefa_superior, tarefa_dinamica');
$sql->adOnde('tarefa_projeto = '.(int)$tarefa_projeto);
$sql->adOnde('tarefa_id = tarefa_superior');
$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio');
$raiz_tarefas = $sql->ListaChave('tarefa_id');
$sql->limpar();
$projTarefas = array();
$opcoes_tarefa_superior = '';

$sql->adTabela('tarefas');
$sql->adCampo('tarefa_id, tarefa_nome, tarefa_fim, tarefa_inicio, tarefa_marco, tarefa_superior, tarefa_dinamica');
$sql->adOnde('tarefa_projeto = '.(int)$tarefa_projeto);
$sql->adOnde('tarefa_id != tarefa_superior');
$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio');
$superiores = array();
$projTarefasComDatasFinais = array($obj->tarefa_id => 'Nenhuma');
$todas_tarefas = array();
$sub_tarefas = $sql->exec();
if ($sub_tarefas) {
	while ($sub_tarefa = $sql->carregarLinha()) {
		$superiores[$sub_tarefa['tarefa_superior']][] = $sub_tarefa['tarefa_id'];
		$todas_tarefas[$sub_tarefa['tarefa_id']] = $sub_tarefa;
		construir_lista_data($projTarefasComDatasFinais, $sub_tarefa);
		}
	}
$sql->limpar();

foreach ($raiz_tarefas as $raiz_tarefa) {
	construir_lista_data($projTarefasComDatasFinais, $raiz_tarefa);
	if ($raiz_tarefa['tarefa_id'] != $tarefa_id)	construirArvoreTarefa($raiz_tarefa);
	}


$ttl = $tarefa_id > 0 ? 'Editar Tarefa' : 'Adicionar Tarefa';

if (!$dialogo){
	$botoesTitulo = new CBlocoTitulo($ttl, 'tarefa.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	}

$depts = array(0 => '');
$cia_id = $projeto->projeto_cia;



$depts_contagem = 0;
if (is_null($obj->tarefa_dinamica)) $obj->tarefa_dinamica = 0;

$sql->adTabela('tarefas');
$sql->esqUnir('projetos','projetos','tarefas.tarefa_projeto=projetos.projeto_id');
$sql->adCampo('projeto_cia');
$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
$projeto_cia = $sql->resultado();
$sql->limpar();





echo '<form name="frmEditar" method="post">';
echo '<input name="m" type="hidden" value="tarefas" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input name="projeto_id" type="hidden" value="'.$tarefa_projeto.'" />';
echo '<input name="fazerSQL" type="hidden" value="fazer_tarefa_aed" />';
echo '<input name="tarefa_id" id="tarefa_id" type="hidden" value="'.$tarefa_id.'" />';
echo '<input name="tarefa_projeto" type="hidden" value="'.$tarefa_projeto.'" />';
echo '<input name="tarefa_horas_trabalhadas" id="tarefa_horas_trabalhadas" type="hidden" value="'.round(($obj->tarefa_percentagem*$obj->tarefa_duracao)/100).'" />';

echo '<input name="tarefa_percentagem_antiga" id="tarefa_percentagem_antiga" type="hidden" value="'.$obj->tarefa_percentagem.'" />';
echo '<input name="tarefa_percentagem_data" id="tarefa_percentagem_data" type="hidden" value="'.$obj->tarefa_percentagem_data.'" />';

echo '<input name="wbs" type="hidden" value="'.($dialogo ? 1 : 0).'" />';
echo '<input name="tarefa_sequencial" id="tarefa_sequencial" type="hidden" value="'.$obj->tarefa_sequencial.'" />';
echo '<input name="tarefa_numeracao" id="tarefa_numeracao" type="hidden" value="'.$obj->tarefa_numeracao.'" />';
echo '<input name="tarefa_gerenciamento" id="tarefa_gerenciamento" type="hidden" value="'.$obj->tarefa_gerenciamento.'" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($tarefa_id ? null : uuid()).'" />';




echo estiloTopoCaixa();
echo '<table border=0 cellpadding=0 cellspacing=1 width="100%" class="std">';
echo '<tr><td colspan="2" style="border: outset #eeeeee 1px;background-color:#'.$projeto->projeto_cor.'" ><font color="'.melhorCor($projeto->projeto_cor).'"><b>'.ucfirst($config['projeto']).': '.$projeto->projeto_nome.'</b></font></td></tr>';
echo '<tr valign="top">';
echo '<td colspan="2">'.dica('Nome d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']),'Tod'.$config['genero_tarefa'].' '.$config['tarefa'].' precisa ter um nome único para se diferenciar as inúmer'.$config['genero_tarefa'].'s '.$config['tarefas'].' que costumam compor um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].'.').'Nome: '.dicaF().'<input type="text" class="texto" name="tarefa_nome" value="'.($obj->tarefa_nome).'" size="40" maxlength="255" '.($dialogo ? 'READONLY': '' ).' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
echo dica('Status', ucfirst($config['genero_tarefa']).' '.$config['tarefa'].' deve ter um status que reflita sua situação atual.').'Status: '.dicaF().selecionaVetor($status, 'tarefa_status', 'size="1" class="texto"', ($obj->tarefa_status ? $obj->tarefa_status : 0)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
echo dica('Prioridade', 'A prioridade para fins de filtragem.').'Prioridade: '.dicaF().selecionaVetor($prioridade, 'tarefa_prioridade', 'size="1" class="texto"', ($obj->tarefa_prioridade ? $obj->tarefa_prioridade : 0)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

echo dica('Progresso', ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' podem ir de 0% (não iniciadas) até 100% (completadas).</p> No gráfico Gantt o progresso será visualizado como uma linha escura dentro do bloco horizontal d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Progresso: '.dicaF().selecionaVetor($percentual, 'tarefa_percentagem', 'size="1" class="texto" '.($obj->tarefa_acao || $projeto->projeto_fisico_registro ? 'disabled="disabled"' : ''), (int)$obj->tarefa_percentagem).'% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<input name="tarefa_percentagem_antes" type="hidden" value="'.(int)$obj->tarefa_percentagem.'" />';

echo '</tr>';
echo '<tr><td align="left">'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados(document.frmEditar)').'</td>';
if (!$dialogo) echo '<td align="right">'.botao('cancelar', 'Cancelar','Cancelar a edição d'.$config['genero_tarefa'].' '.$config['tarefa'].'.','','if(confirm(\'Tem a certeza de que deseja cancelar?\')){url_passar(0, \''.($tarefa_id ? 'm=tarefas&a=ver&tarefa_id='.(int)$tarefa_id : 'm=projetos&a=ver&projeto_id='.$tarefa_projeto).'\');}').'</td>';
echo '</tr></table>';
if (isset($_REQUEST['tab'])) $Aplic->setEstado('TarefaEditarTab', getParam($_REQUEST, 'tab', 0));
$tab = $Aplic->getEstado('TarefaEditarTab', 0);
$caixaTab = new CTabBox('m=tarefas&a=editar&tarefa_id='.(int)$tarefa_id, '', $tab);
$caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ae_desc', 'Detalhes', null, null,'Detalhes','As principais informações relativas a atividade se encontram nesta seção.');
$caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ae_depend', 'Predecessoras', null, null, 'Predecessoras', 'Agrupar '.$config['tarefas'].' pode ajudar a administrar um grande número de  '.$config['tarefas'].' ou a criar sub-'.$config['projetos'].', dentro d'.$config['genero_projeto'].' '.$config['projeto'].', podendo serem monitorados.</p>'.ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' são agrupada sob uma <b>Tarefa Superior</b>.');
$caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ae_datas', 'Datas', null, null,'Datas','Marcar o ínicio e término d'.$config['genero_tarefa'].' '.$config['tarefa']);
$caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ae_designados', 'Designados',null, null,'Designados', 'A <i>priori</i> tod'.$config['genero_tarefa'].' '.$config['tarefa'].' deve ter ao menos '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' designado.</p>A excessão são '.$config['genero_tarefa'].'s '.$config['tarefas'].' <b>Dinâmicas</b>, que não tem existencia própria, porem as subtarefas desta deverão ter '.$config['usuarios'].' designados.');
if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ae_entrega_pro', 'Entregas',null, null,'Entregas', 'As entregas d'.$config['genero_tarefa'].' '.$config['tarefa'].'.');
$caixaTab->mostrar('', true,'','',true);
echo estiloFundoCaixa();
echo '</form>';


function getEspacos($quantidade) {
	if ($quantidade == 0) return '';
	return str_repeat('&nbsp;', $quantidade);
	}

function construirArvoreTarefa($tarefa_data, $profundidade = 0) {
	global $projTarefas, $todas_tarefas, $superiores, $opcoes_tarefa_superior, $tarefa_superior, $tarefa_id;
	$projTarefas[$tarefa_data['tarefa_id']] = $tarefa_data['tarefa_nome'];
	$selecionado = $tarefa_data['tarefa_id'] == $tarefa_superior ? 'selected="selected"' : '';
	//$tarefa_data['tarefa_nome'] = strlen($tarefa_data[1]) > 45 ? substr($tarefa_data['tarefa_nome'], 0, 45).'...' : $tarefa_data['tarefa_nome'];
	$opcoes_tarefa_superior .= '<option value="'.$tarefa_data['tarefa_id'].'" '.$selecionado.'>'.getEspacos($profundidade * 3).$tarefa_data['tarefa_nome'].'</option>';
	if (isset($superiores[$tarefa_data['tarefa_id']])) {
		foreach ($superiores[$tarefa_data['tarefa_id']] as $tarefa_subordinada) {
			if ($tarefa_subordinada != $tarefa_id) construirArvoreTarefa($todas_tarefas[$tarefa_subordinada], ($profundidade + 1));
			}
		}
	}

function construir_lista_data(&$vetor_data, $linha) {
	global $dinamicas_seguidas, $projeto;
	if ($linha['tarefa_marco'] == 0) $data = new CData($linha['tarefa_fim']);
	else $data = new CData($linha['tarefa_inicio']);
	$sdata = $data->format('%d/%m/%Y');
	$ihora = $data->format('%H');
	$smin = $data->format('%M');
	$vetor_data[$linha['tarefa_id']] = array($linha['tarefa_nome'], $sdata, $ihora, $smin);
	}
?>
<script language="JavaScript">

var tarefa_id = '<?php echo $obj->tarefa_id; ?>';
var checar_datas_tarefas = true;
var pode_editar_tempo = true;
var tarefa_nome_msg = "<?php echo 'Por favor insira um nome para '.$config['genero_tarefa'].' '.$config['tarefa'].' válido' ?>;"
var tarefa_inicio_msg = 'Por favor insira uma data de início válida';
var tarefa_end_msg ='Por favor selecione uma data final válida';


var dias_uteis = new Array(<?php echo config('cal_dias_uteis'); ?>);
var cal_dia_inicio = <?php echo intval(config('cal_dia_inicio')); ?>;
var cal_dia_fim = <?php echo intval(config('cal_dia_fim')); ?>;
var horas_trab_diario = <?php echo config('horas_trab_diario'); ?>;



</script>