<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $dialogo;

require_once (BASE_DIR.'/modulos/email/email.class.php');

$cHoje = new CData();
$hoje = $cHoje->format(FMT_TIMESTAMP_DATA);
$data = getParam($_REQUEST, 'data', $hoje);
if (!$data) $data =$hoje;

if (isset($_REQUEST['periodo_todo'])) $Aplic->setEstado('periodo_todo', getParam($_REQUEST, 'periodo_todo', null));
$periodo_todo = $Aplic->getEstado('periodo_todo', null);

if (isset($_REQUEST['agenda_tipo_id'])) $Aplic->setEstado('CalIdxAgenda_tipo', getParam($_REQUEST, 'agenda_tipo_id', null));
$agenda_tipo_id = $Aplic->getEstado('CalIdxAgenda_tipo', 0);

echo '<form method="post" name="env">'; 
echo '<input type="hidden" name="m" value="email" />';
echo '<input type="hidden" name="a" value="ver_mes" />';
echo '<input type="hidden" name="retornar" value="" />';
echo '<input type="hidden" name="data" value="'.$data.'" />';
echo '<input type="hidden" name="agenda_tipo_id" value="'.$agenda_tipo_id.'" />';
echo '<input type="hidden" name="periodo_todo" value="'.$periodo_todo.'" />';

$sql = new BDConsulta;



$tamanho = intval(config('cal_tamanho_string'));
if (!$dialogo) $Aplic->salvarPosicao();

if (!$dialogo){
	$botoesTitulo = new CBlocoTitulo('Agenda de Compromissos Mensal', 'calendario.png', $m, $m.'.'.$a);
	
	$icone_agenda='<td>'.dica('Filtrar pelas Agendas', 'Visualizar os compromissos para uma das agendas cadastradas.').'Agenda:'.dicaF().'</td><td><input type="text" id="nome" name="nome" READONLY value="'.(count(explode(',', $agenda_tipo_id))>1 ? 'multiplos calendários' : nome_agenda($agenda_tipo_id)).'"></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar uma agenda.').'</a></td>';
	$icone_periodo_todo='<td><a href="javascript: void(0);" onclick="env.periodo_todo.value='.($periodo_todo ? 0 : 1).'; env.submit();">'.($periodo_todo ? imagem('icones/inicio_fim_nao.gif','Todo Período','Atualmente está sendo mostrado todos os dias que haja evento, tarefa ou plano de ação. Clique neste ícone '.imagem('icones/inicio_fim_nao.gif').' para visualizar apenas os dias em que inicia e termina.') : imagem('icones/inicio_fim.gif','Início e Fim','Atualmente está sendo mostrado apenas os dias em que inicia e termina um compromisso. Clique neste ícone '.imagem('icones/inicio_fim.gif').' para visualizar todos os dias.')).'</a></td>';
	
	$botoesTitulo->adicionaCelula('<table><tr>'.$icone_agenda.$icone_periodo_todo.'</tr></table>');
	
	
	$botoesTitulo->adicionaCelula(botao('agendas', 'Agendas', 'Inserir e Editar as agendas particulares.','','env.a.value=\'editar_agenda_tipo\'; env.retornar.value=\'ver_mes\'; env.submit();'));
	$botoesTitulo->adicionaCelula(dica('Imprimir o Mês', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o mês a partir do navegador Web.').'<a href="javascript: void(0);" onclick ="url_passar(1,\'m=email&a=ver_mes&dialogo=1&data='.$data.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->adicionaBotao('m=email&a=ver_ano&data='.$data, 'visão anual ','','Visão Anual','Visualizar todos o ano inteiro.');
	$botoesTitulo->mostrar();
	}

$sql->adTabela('agenda', 'e');
$sql->esqUnir('agenda_usuarios', 'agenda_usuarios', 'agenda_usuarios.agenda_id = e.agenda_id');
$sql->adCampo('aceito, e.agenda_id, agenda_titulo, agenda_inicio, agenda_fim, agenda_descricao, agenda_nr_recorrencias, agenda_recorrencias, agenda_lembrar, agenda_dono, e.agenda_localizacao, e.agenda_cor');
$sql->adOrdem('e.agenda_inicio, e.agenda_fim ASC');
$sql->adOnde('agenda_dono != '.$Aplic->usuario_id);
$sql->adOnde('agenda_usuarios.usuario_id='.$Aplic->usuario_id);
$sql->adOnde('agenda_usuarios.aceito=0');
$convites=$sql->Lista();
$sql->Limpar();

if (count($convites)) echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr id="linha_convite"><td align="center"><div><font style="font-size: 12pt;" size="4"><a href="javascript:void(0);" onclick="linha_convite.style.display=\'none\'; '.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Convite\', 1000, 600, \'m=email&a=convite&dialogo=1&calendario='.$a.'&data='.$data.'\', window.setConvite, window);' : 'window.open(\'?m=email&a=convite&dialogo=1&calendario='.$a.'&data='.$data.'\', \'convite\', \'width=1020, height=600, left=0, top=0, scrollbars=yes, resizable=yes\')').'">'.dica('Convite','Convite para ativide').imagem('convite.gif').dicaF().'</a></font></div></td></tr></table>'; 


if (!$dialogo) echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td>';
$data = new CData($data);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
require_once (BASE_DIR.'/modulos/email/links_compromissos.php');
require_once (BASE_DIR.'/modulos/email/links_despachos.php');
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, false, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$cal = new CAgendaMes($data);
$cal->setEstilo('motitulo', 'mocal');
$cal->setLinkFuncoes('clicarDia', 'clicarSemana', 'clicarCompromisso');
$cal->setCompromissos($links);
echo $cal->mostrar();



if (!$dialogo){
	$minutoiCal = new CAgendaMes($cal->mesAnterior);
	$minutoiCal->setEstilo('minititulo', 'minical');
	$minutoiCal->mostrarSetas = false;
	$minutoiCal->mostrarSemana = false;
	$minutoiCal->clicarMes = true;
	$minutoiCal->setLinkFuncoes('clicarDia');
	$primeiraData = new CData($cal->mesAnterior);
	$primeiraData->setDay(1);
	$primeiraData->setTime(0, 0, 0);
	$ultimaData = new CData($cal->mesAnterior);
	$ultimaData->setDay($cal->mesAnterior->getDaysInMonth());
	$ultimaData->setTime(23, 59, 59);
	$links = array();
	$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, false, $Aplic->usuario_id, $agenda_tipo_id);
	$links=getDespachoLinks($primeiraData, $ultimaData, $links);
	$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
	$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
	$minutoiCal->setCompromissos($links);
	echo '<table class="std" cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
	echo '<td valign="top" align="center" width="220">'.$minutoiCal->mostrar().'</td>';
	echo '<td valign="top" align="center" width="75%"><table><tr><td style="border-style:solid;border-width:1px; background-color: #fefeed;">&nbsp;&nbsp;</td><td nowrap="nowrap">Meio expediente</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px; background-color: #f0e8e8;">&nbsp;&nbsp;</td><td nowrap="nowrap">Sem expediente</td></tr></table></td>';
	$minutoiCal->setData($cal->mesProximo);
	$primeiraData = new CData($cal->mesProximo);
	$primeiraData->setDay(1);
	$primeiraData->setTime(0, 0, 0);
	$ultimaData = new CData($cal->mesProximo);
	$ultimaData->setDay($cal->mesProximo->getDaysInMonth());
	$ultimaData->setTime(23, 59, 59);
	$links = array();
	$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true, $Aplic->usuario_id, $agenda_tipo_id);
	$links=getDespachoLinks($primeiraData, $ultimaData, $links);
	$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
	$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
	$minutoiCal->setCompromissos($links);
	echo '<td valign="top" align="center" width="220">'.$minutoiCal->mostrar().'</td>';
	echo '</tr></table>';									
	echo '</td></tr></table>';
	}
if (!$dialogo) echo estiloFundoCaixa();
else echo '<script>self.print();</script>';

echo '</form>';

?>
<script language="javascript">
	
function setConvite(){
	location.reload();
	}	
	
function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agenda', 500, 500, 'm=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAgenda&tabela=agenda_tipo&valores=<?php echo $agenda_tipo_id ?>', window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAgenda&tabela=agenda_tipo&valores=<?php echo $agenda_tipo_id ?>', 'Agenda','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	document.env.agenda_tipo_id.value=chave;
	document.env.submit();
	}	
	
	
	
function clicarDia(uts, dataFim ) {
	url_passar(0, 'm=email&a=ver_dia&data='+uts+'&tab=0');
	}
function clicarCompromisso(uts, dataFim ) {
	url_passar(0, 'm=email&a=editar_compromisso&data='+uts);
	}	
function clicarSemana( uts, dataFim ) {
	url_passar(0, 'm=email&a=ver_semana&data='+uts);
	}	
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
</script>

