<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $tab, $localidade_tipo_caract, $data, $dialogo;

$podeAdicionar = true;

if (isset($_REQUEST['periodo_todo'])) $Aplic->setEstado('periodo_todo', getParam($_REQUEST, 'periodo_todo', null));
$periodo_todo = $Aplic->getEstado('periodo_todo', null);

require_once (BASE_DIR.'/modulos/email/email.class.php');
if (isset($_REQUEST['agenda_tipo_id'])) $Aplic->setEstado('CalIdxAgenda_tipo', getParam($_REQUEST, 'agenda_tipo_id', null));
if (!$dialogo) $Aplic->salvarPosicao();
$agenda_tipo_id = $Aplic->getEstado('CalIdxAgenda_tipo', 0);

$q = new BDConsulta;
$q->adTabela('agenda_tipo');
$q->adCampo('agenda_tipo_id, nome');
$q->adOnde('usuario_id='.$Aplic->usuario_id);
$q->adOrdem('nome');
$tipos = $q->listaVetorChave('agenda_tipo_id', 'nome');
$q->Limpar();
$tipos=array('0' => 'Todas') + $tipos;

if (!$dialogo) $Aplic->salvarPosicao();
require_once ($Aplic->getClasseModulo('tarefas'));
$Aplic->setEstado('CalVerDiaTab', getParam($_REQUEST, 'tab', $tab));
$tab = $Aplic->getEstado('CalVerDiaTab', '0');
$df = '%d/%m/%Y';
$cHoje = new CData();
$hoje = $cHoje->format(FMT_TIMESTAMP_DATA);
$data = getParam($_REQUEST, 'data', $hoje);
if (!$data) $data =$hoje;

$este_dia = new CData($data);
$dd = $este_dia->getDay();
$mm = $este_dia->getMonth();
$aa = $este_dia->getYear();
$esta_semana = Data_calc::beginOfWeek($dd, $mm, $aa, FMT_TIMESTAMP_DATA, (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1));
$primeiraData = php4_clone($este_dia);
$primeiraData->setTime(0, 0, 0);
//$primeiraData->subtrairSegundos(1);
$ultimaData = php4_clone($este_dia);
$ultimaData->setTime(23, 59, 59);
$dia_ant = new CData(Data_calc::diaAnterior($dd, $mm, $aa, FMT_TIMESTAMP_DATA));
$dia_prox = new CData(Data_calc::proxDia($dd, $mm, $aa, FMT_TIMESTAMP_DATA));

echo '<form method="post" name="env">'; 
echo '<input type="hidden" name="m" value="email" />';
echo '<input type="hidden" name="a" value="ver_dia" />';
echo '<input type="hidden" name="retornar" value="" />';
echo '<input type="hidden" name="data" value="'.$data.'" />';
echo '<input type="hidden" name="agenda_tipo_id" value="'.$agenda_tipo_id.'" />';
echo '<input type="hidden" name="periodo_todo" value="'.$periodo_todo.'" />';
echo '<input type="hidden" name="tab" value="'.$tab.'" />';

if (!$dialogo){
	$botoesTitulo = new CBlocoTitulo('Agenda Di�ria', 'calendario.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=email&a=ver_ano&data='.$este_dia->format(FMT_TIMESTAMP_DATA), 'vis�o anual','','Vis�o Anual','Visualizar o ano inteiro.');
	$botoesTitulo->adicionaBotao('m=email&a=ver_mes&data='.$este_dia->format(FMT_TIMESTAMP_DATA), 'vis�o mensal','','Vis�o Mensal','Visualizar o m�s inteiro.');
	$botoesTitulo->adicionaBotao('m=email&a=ver_semana&data='.$esta_semana, 'vis�o semanal','','Vis�o Semanal','Visualizar a semana inteira.');
	if ($podeAdicionar) $botoesTitulo->adicionaBotaoCelula('', 'env.a.value=\'editar_compromisso\'; env.retornar.value=\'ver_dia\'; env.submit();', 'novo compromisso', '', 'Novo Compromisso', 'Criar um novo compromisso.<br><br>Os compromissos s�o atividades com data e hora espec�ficas podendo estar relacionados com '.$config['projetos'].', '.$config['tarefas'].' e '.$config['usuarios'].' espec�ficos');
	$icone_agenda='<td>'.dica('Filtrar pelas Agendas', 'Visualizar os compromissos para uma das agendas cadastradas.').'Agenda:'.dicaF().'</td><td><input type="text" id="nome" name="nome" READONLY value="'.(count(explode(',', $agenda_tipo_id))>1 ? 'multiplos calend�rios' : nome_agenda($agenda_tipo_id)).'"></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Agenda','Clique neste �cone '.imagem('icones/calendario_p.png').' para selecionar uma agenda.').'</a></td>';
	$icone_periodo_todo='<td><a href="javascript: void(0);" onclick="env.periodo_todo.value='.($periodo_todo ? 0 : 1).'; env.submit();">'.($periodo_todo ? imagem('icones/inicio_fim_nao.gif','Todo Per�odo','Atualmente est� sendo mostrado todos os dias que haja evento, tarefa ou plano de a��o. Clique neste �cone '.imagem('icones/inicio_fim_nao.gif').' para visualizar apenas os dias em que inicia e termina.') : imagem('icones/inicio_fim.gif','In�cio e Fim','Atualmente est� sendo mostrado apenas os dias em que inicia e termina um compromisso. Clique neste �cone '.imagem('icones/inicio_fim.gif').' para visualizar todos os dias.')).'</a></td>';
	$botoesTitulo->adicionaCelula('<table><tr>'.$icone_agenda.$icone_periodo_todo.'</tr></table>');
	$botoesTitulo->adicionaCelula(botao('agendas', 'Agendas', 'Inserir e Editar as agendas particulares.','','env.a.value=\'editar_agenda_tipo\'; env.retornar.value=\'ver_dia\'; env.submit();'));
	$botoesTitulo->adicionaCelula(dica('Imprimir Dia', 'Clique neste �cone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poder� imprimir este dia a partir do navegador Web.').'<a href="javascript: void(0);" onclick ="url_passar(1,\'m=email&a=ver_dia&dialogo=1&data='.$data.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();

	echo estiloTopoCaixa();
	echo '<table border=0 class="std" width="100%" cellspacing=4 cellpadding=0>';
	echo '<tr><td valign="top"><table border=0 cellspacing=0 cellpadding="2" width="100%" class="motitulo">';
	$titulo=dia_semana($este_dia->format('%A')).', '.$este_dia->format($df);
	echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=ver_dia&data='.$dia_ant->format(FMT_TIMESTAMP_DATA).'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif'), 'Dia Anterior', 'Clique neste �cone '.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif')).' para exibir o dia anterior.').'</a></td><th width="100%">'.$titulo.'</th><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=ver_dia&data='.$dia_prox->format(FMT_TIMESTAMP_DATA).'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif'), 'Pr�ximo Dia', 'Clique neste �cone '.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif')).' para exibir o pr�ximo dia.').'</a></td></tr>';
	echo '</table>';
	
	$caixaTab = new CTabBox('m=email&a=ver_dia&data='.$este_dia->format(FMT_TIMESTAMP_DATA), BASE_DIR.'/modulos/email/', $tab);
	$caixaTab->adicionar('ver_compromissos_dia', 'Compromissos',null,null,'Compromissos','Visualizar os compromissos marcados para este dia.');
	$caixaTab->adicionar('despachos_enviados', 'Recebidos sem resposta',null,null,'Recebidos Sem Resposta','Clique nesta aba para visualizar os despachos recebidos que anda n�o foram respondidos.');
	$caixaTab->mostrar('','','','',true);
	echo '</td>';
	
	if ($config['cal_ver_dia_mostrar_minical']) { 
	  echo '<td valign="top" width="175">';
		require_once (BASE_DIR.'/modulos/email/links_compromissos.php');
		require_once (BASE_DIR.'/modulos/email/links_despachos.php');
		$minutoiCal = new CAgendaMes($este_dia);
		$minutoiCal->setEstilo('minititulo', 'minical');
		$minutoiCal->mostrarSetas = false;
		$minutoiCal->mostrarSemana = false;
		$minutoiCal->clicarMes = true;
		$minutoiCal->setLinkFuncoes('clicarDia');
		$primeiraData = new CData($minutoiCal->mesAnterior);
		$primeiraData->setDay(1);
		$primeiraData->setTime(0, 0, 0);
		$ultimaData = new CData($minutoiCal->mesAnterior);
		$ultimaData->setDay($minutoiCal->mesAnterior->getDaysInMonth());
		$ultimaData->setTime(23, 59, 59);
		$links = array();
		$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, 50, true, $Aplic->usuario_id, $agenda_tipo_id);
		$links=getDespachoLinks($primeiraData, $ultimaData, $links);
		$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
		$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
		$minutoiCal->setCompromissos($links);
		$minutoiCal->setData($minutoiCal->mesAnterior);
		echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
		echo '<td align="center" >'.$minutoiCal->mostrar().'</td>';
		echo '</tr></table><hr noshade size="1">';
		$primeiraData = new CData($minutoiCal->mesProximo);
		$primeiraData->setDay(1);
		$primeiraData->setTime(0, 0, 0);
		$ultimaData = new CData($minutoiCal->mesProximo);
		$ultimaData->setDay($minutoiCal->mesProximo->getDaysInMonth());
		$ultimaData->setTime(23, 59, 59);
		$links = array();
		$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, 50, false, $Aplic->usuario_id, $agenda_tipo_id);
		$links=getDespachoLinks($primeiraData, $ultimaData, $links);
		$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
		$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
		$minutoiCal->setCompromissos($links);
		$minutoiCal->setData($minutoiCal->mesProximo);
		echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
		echo '<td align="center" >'.$minutoiCal->mostrar().'</td>';
		echo '</tr></table><hr noshade size="1">';
		$primeiraData = new CData($minutoiCal->mesProximo);
		$primeiraData->setDay(1);
		$primeiraData->setTime(0, 0, 0);
		$ultimaData = new CData($minutoiCal->mesProximo);
		$ultimaData->setDay($minutoiCal->mesProximo->getDaysInMonth());
		$ultimaData->setTime(23, 59, 59);
		$links = array();
		$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, 50, false, $Aplic->usuario_id, $agenda_tipo_id);
		$links=getDespachoLinks($primeiraData, $ultimaData, $links);
		$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
		$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);
		$minutoiCal->setCompromissos($links);
		$minutoiCal->setData($minutoiCal->mesProximo);
		echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
		echo '<td align="center" >'.$minutoiCal->mostrar().'</td>';
		echo '</tr></table>';
	 	echo '</td>';
		} 
	echo '</tr></table>';
	echo '</form>';
	
	echo estiloFundoCaixa();
	}
else {
	include_once (BASE_DIR.'/modulos/email/ver_compromissos_dia.php');
	echo '<script>self.print();</script>';
	}

function php4_clone($objeto) {
	if (version_compare(phpversion(), '5.0') < 0) return $objeto;
	else return @clone ($objeto);
	}
?>
<script language="javascript">

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agenda', 500, 500, 'm=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAgenda&tabela=agenda_tipo&valores=<?php echo $agenda_tipo_id ?>', window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAgenda&tabela=agenda_tipo&valores=<?php echo $agenda_tipo_id ?>', 'Agenda','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	document.env.agenda_tipo_id.value=chave;
	document.env.submit();
	}	
	
	
function clicarDia( dataInicio, dataFim ) { 
	url_passar(0, 'm=email&a=ver_dia&data='+dataInicio+'&tab=0');
	}
</script>

