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
setMicroTempo();

if (isset($_REQUEST['agenda_tipo_id'])) $Aplic->setEstado('CalIdxAgenda_tipo', getParam($_REQUEST, 'agenda_tipo_id', null));
$agenda_tipo_id = $Aplic->getEstado('CalIdxAgenda_tipo', 0);

if (isset($_REQUEST['periodo_todo'])) $Aplic->setEstado('periodo_todo', getParam($_REQUEST, 'periodo_todo', null));
$periodo_todo = $Aplic->getEstado('periodo_todo', null);

$tamanho = intval(config('cal_tamanho_string'));
if (!$dialogo) $Aplic->salvarPosicao();

$cHoje = new CData();
$hoje = $cHoje->format(FMT_TIMESTAMP_DATA);
$data = getParam($_REQUEST, 'data', $hoje);
if (!$data) $data =$hoje;

echo '<form method="post" name="env">'; 
echo '<input type="hidden" name="m" value="email" />';
echo '<input type="hidden" name="a" value="ver_ano" />';
echo '<input type="hidden" name="retornar" value="" />';
echo '<input type="hidden" name="data" value="'.$data.'" />';
echo '<input type="hidden" name="agenda_tipo_id" value="'.$agenda_tipo_id.'" />';
echo '<input type="hidden" name="periodo_todo" value="'.$periodo_todo.'" />';

if (!$dialogo){
	$botoesTitulo = new CBlocoTitulo('Agenda de Compromissos Anual', 'calendario.png', $m, "$m.$a");
	$icone_agenda='<td>'.dica('Filtrar pelas Agendas', 'Visualizar os compromissos para uma das agendas cadastradas.').'Agenda:'.dicaF().'</td><td><input type="text" id="nome" name="nome" READONLY value="'.(count(explode(',', $agenda_tipo_id))>1 ? 'multiplos calendários' : nome_agenda($agenda_tipo_id)).'"></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar uma agenda.').'</a></td>';
	$icone_periodo_todo='<td><a href="javascript: void(0);" onclick="env.periodo_todo.value='.($periodo_todo ? 0 : 1).'; env.submit();">'.($periodo_todo ? imagem('icones/inicio_fim_nao.gif','Todo Período','Atualmente está sendo mostrado todos os dias que haja evento, tarefa ou plano de ação. Clique neste ícone '.imagem('icones/inicio_fim_nao.gif').' para visualizar apenas os dias em que inicia e termina.') : imagem('icones/inicio_fim.gif','Início e Fim','Atualmente está sendo mostrado apenas os dias em que inicia e termina um compromisso. Clique neste ícone '.imagem('icones/inicio_fim.gif').' para visualizar todos os dias.')).'</a></td>';
	$botoesTitulo->adicionaCelula('<table><tr>'.$icone_agenda.$icone_periodo_todo.'</tr></table>');
	$botoesTitulo->adicionaCelula(botao('agendas', 'Agendas', 'Inserir e Editar as agendas particulares.','','env.a.value=\'editar_agenda_tipo\'; env.m.value=\'email\'; env.retornar.value=\'ver_ano\'; env.submit();'));
	$botoesTitulo->adicionaCelula(dica('Imprimir o Ano', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o ano a partir do navegador Web.').'<a href="javascript: void(0);" onclick ="url_passar(1,\'m=email&a=ver_ano&dialogo=1&data='.$data.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	}
echo '</form>';

$sql = new BDConsulta;

$sql->adTabela('agenda', 'e');
$sql->esqUnir('agenda_usuarios', 'agenda_usuarios', 'agenda_usuarios.agenda_id = e.agenda_id');
$sql->adCampo('aceito, e.agenda_id, agenda_titulo, agenda_inicio, agenda_fim, agenda_descricao, agenda_nr_recorrencias, agenda_recorrencias, agenda_lembrar, agenda_dono, e.agenda_localizacao, e.agenda_cor');
$sql->adOrdem('e.agenda_inicio, e.agenda_fim ASC');
$sql->adOnde('agenda_dono != '.$Aplic->usuario_id);
$sql->adOnde('agenda_usuarios.usuario_id='.$Aplic->usuario_id);
$sql->adOnde('agenda_usuarios.aceito=0');
$convites=$sql->Lista();
$sql->Limpar();
if (count($convites) && !$dialogo) echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr id="linha_convite"><td align="center"><div><font style="font-size: 12pt;" size="4"><a href="javascript:void(0);" onclick="linha_convite.style.display=\'none\'; window.open(\'?m=email&a=convite&dialogo=1&calendario='.$a.'&data='.$data.'\', \'convite\', \'width=1020, height=600, left=0, top=0, scrollbars=yes, resizable=yes\')">'.dica('Convite','Convite para ativide').imagem('convite.gif').dicaF().'</a></font></div></td></tr></table>'; 


if (!$data) $data = new CData();
else $data = new CData($data);
$data->setDay(1);
$data->setMonth(1);
$anoAnterior = $data->format(FMT_TIMESTAMP_DATA);
$anoAnterior = (int)($anoAnterior - 10000);
$anoProximo = $data->format(FMT_TIMESTAMP_DATA);
$anoProximo = (int)($anoProximo + 10000);

if (!$dialogo) echo estiloTopoCaixa();
echo '<table '.($dialogo ? '' : 'class="std2"').' width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td><table width="100%" cellspacing=0 cellpadding=0><tr><td colspan="20" valign="top">';
echo '<table border=0 cellspacing=0 cellpadding="2" width="100%" class="motitulo">';
echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=ver_ano&data='.$anoAnterior.'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif'), 'Ano Anterior', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif')).' para exibir o ano anterior.').'</a></td>';
echo '<th width="100%" align="center">'.htmlentities($data->format('%Y')).'</th><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=ver_ano&data='.$anoProximo.'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif'), 'Próximo Ano', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif')).' para exibir o próximo ano.').'</a></td></tr></table></td></tr>';
$minutoiCal = new CAgendaMes($data);
$minutoiCal->setEstilo('minititulo', 'minical');
$minutoiCal->mostrarSetas = false;
$minutoiCal->mostrarSemana = true;
$minutoiCal->clicarMes = true;
$minutoiCal->setLinkFuncoes('clicarDia', 'clicarSemana');
require_once (BASE_DIR.'/modulos/email/links_compromissos.php');
require_once (BASE_DIR.'/modulos/email/links_despachos.php');
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true,$Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true,$Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true,$Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
$data->adMeses(1);
$primeiraData = new CData($data);
$primeiraData->setDay(1);
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData($data);
$ultimaData->setDay($data->getDaysInMonth());
$ultimaData->setTime(23, 59, 59);
$links = array();
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, true, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
$minutoiCal->setCompromissos($links);
$minutoiCal->setData($data);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$minutoiCal->mostrar().'</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';

echo '</td></tr>';
echo '<tr><td><table class="minical" align=center><tr>';
echo '<td nowrap="nowrap">Chave:</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" class="dia">&nbsp;&nbsp;</td><td nowrap="nowrap">Dia</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" class="compromisso">&nbsp;&nbsp;</td><td nowrap="nowrap">Compromisso</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" class="fim_semana">&nbsp;&nbsp;</td><td nowrap="nowrap">Sem expediente</td><td>&nbsp;</td>';
echo '<td class="hoje">&nbsp;&nbsp;</td><td nowrap="nowrap">Hoje</td><td>&nbsp;</td>';
echo '<td width="40%">&nbsp;</td>';
echo '</tr></table></td></tr></table>';
if (!$dialogo) echo estiloFundoCaixa();
else echo '<script>self.print();</script>';
?>
<script language="javascript">

function clicarDia( uts, dataFim ) {
	url_passar(0, 'm=email&a=ver_dia&data='+uts);
	}
function clicarSemana( uts, dataFim ) {
	url_passar(0, 'm=email&a=ver_semana&data='+uts);
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agenda', 500, 500, 'm=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAgenda&tabela=agenda_tipo&valores=<?php echo $agenda_tipo_id ?>', window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAgenda&tabela=agenda_tipo&valores=<?php echo $agenda_tipo_id ?>', 'Agenda','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	document.env.agenda_tipo_id.value=chave;
	document.env.submit();
	}
</script>		