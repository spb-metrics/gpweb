<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');
global $localidade_tipo_caract, $dialogo;

require_once (BASE_DIR.'/modulos/email/email.class.php');
$tamanho = intval(config('cal_tamanho_string'));
if (!$dialogo) $Aplic->salvarPosicao();
$nome_meses=array('01'=>'Janeiro', '02'=>'Fevereiro', '03'=>'Março', '04'=>'Abril', '05'=>'Maio', '06'=>'Junho', '07'=>'Julho', '08'=>'Agosto', '09'=>'Setembro', '10'=>'Outubro', '11'=>'Novembro', '12'=>'Dezembro');

if (isset($_REQUEST['periodo_todo'])) $Aplic->setEstado('periodo_todo', getParam($_REQUEST, 'periodo_todo', null));
$periodo_todo = $Aplic->getEstado('periodo_todo', null);

if (isset($_REQUEST['agenda_tipo_id'])) $Aplic->setEstado('CalIdxAgenda_tipo', getParam($_REQUEST, 'agenda_tipo_id', null));
$agenda_tipo_id = $Aplic->getEstado('CalIdxAgenda_tipo', 0);


$cHoje = new CData();
$hoje = $cHoje->format(FMT_TIMESTAMP_DATA);
$data = getParam($_REQUEST, 'data', '');
if (!$data) $data=date('Ymd',strtotime('monday this week'));

$sql = new BDConsulta;
$esta_semana = new CData($data);
$dd = $esta_semana->getDay();
$mm = $esta_semana->getMonth();
$aa = $esta_semana->getYear();
$primeiraData = new CData(Data_calc::beginOfWeek($dd, $mm, $aa, FMT_TIMESTAMP_DATA, (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1)));
$primeiraData->setTime(0, 0, 0);
$ultimaData = new CData(Data_calc::endOfWeek($dd, $mm, $aa, FMT_TIMESTAMP_DATA, (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1)));
$ultimaData->setTime(23, 59, 59);
$semana_ant = new CData(Data_calc::beginOfPrevWeek($dd, $mm, $aa, FMT_TIMESTAMP_DATA, (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1)));
$proxSemana = new CData(Data_calc::beginOfNextWeek($dd, $mm, $aa, FMT_TIMESTAMP_DATA, (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1)));
$links = array();
require_once (BASE_DIR.'/modulos/email/links_compromissos.php');
require_once (BASE_DIR.'/modulos/email/links_despachos.php');
$links=getCompromissoLinks($periodo_todo, $primeiraData, $ultimaData, $links, $tamanho, false, $Aplic->usuario_id, $agenda_tipo_id);
$links=getDespachoLinks($primeiraData, $ultimaData, $links);
$links=getMsg_TarefaLinks($primeiraData, $ultimaData, $links);
$links=getDespachoModeloLinks($primeiraData, $ultimaData, $links);


echo '<form method="post" name="env">'; 
echo '<input type="hidden" name="m" value="email" />';
echo '<input type="hidden" name="a" value="ver_semana" />';
echo '<input type="hidden" name="retornar" value="" />';
echo '<input type="hidden" name="data" value="'.$data.'" />';
echo '<input type="hidden" name="agenda_tipo_id" value="'.$agenda_tipo_id.'" />';
echo '<input type="hidden" name="periodo_todo" value="'.$periodo_todo.'" />';

if (!$dialogo){
	$botoesTitulo = new CBlocoTitulo('Agenda de Compromissos Semanal', 'calendario.png', $m, "$m.$a");
	$botoesTitulo->adicionaBotao('m=email&a=ver_mes&data='.$esta_semana->format(FMT_TIMESTAMP_DATA), 'visão mensal','','Visão Mensal','Visualizar o mês inteiro.');
	$icone_agenda='<td>'.dica('Filtrar pelas Agendas', 'Visualizar os compromissos para uma das agendas cadastradas.').'Agenda:'.dicaF().'</td><td><input type="text" id="nome" name="nome" READONLY value="'.(count(explode(',', $agenda_tipo_id))>1 ? 'multiplos calendários' : nome_agenda($agenda_tipo_id)).'"></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar uma agenda.').'</a></td>';
	$icone_periodo_todo='<td><a href="javascript: void(0);" onclick="env.periodo_todo.value='.($periodo_todo ? 0 : 1).'; env.submit();">'.($periodo_todo ? imagem('icones/inicio_fim_nao.gif','Todo Período','Atualmente está sendo mostrado todos os dias que haja evento, tarefa ou plano de ação. Clique neste ícone '.imagem('icones/inicio_fim_nao.gif').' para visualizar apenas os dias em que inicia e termina.') : imagem('icones/inicio_fim.gif','Início e Fim','Atualmente está sendo mostrado apenas os dias em que inicia e termina um compromisso. Clique neste ícone '.imagem('icones/inicio_fim.gif').' para visualizar todos os dias.')).'</a></td>';
	$botoesTitulo->adicionaCelula('<table><tr>'.$icone_agenda.$icone_periodo_todo.'</tr></table>');
	$botoesTitulo->adicionaCelula(botao('agendas', 'Agendas', 'Inserir e Editar as agendas particulares.','','env.a.value=\'editar_agenda_tipo\'; env.m.value=\'email\'; env.retornar.value=\'ver_semana\'; env.submit();'));
	$botoesTitulo->adicionaCelula(dica('Imprimir a Semana', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir a semana a partir do navegador Web.').'<a href="javascript: void(0);" onclick ="url_passar(1,\'m=email&a=ver_semana&dialogo=1&data='.$data.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	}
echo '</form>';

$sql = new BDConsulta;

if (!$dialogo){
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
	echo estiloTopoCaixa();
	}

echo '<table class="motitulo" border=0 cellspacing=0 cellpadding="2" width="100%" >';
echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=ver_semana&data='.$semana_ant->format(FMT_TIMESTAMP_DATA).'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif'), 'Semana Anterior', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif')).' para exibir a semana anterior.').'</a></td><td width="100%" align="center"><b><span style="font-size:12pt">'.$primeiraData->format('%U').'ª Semana - '.$nome_meses[$primeiraData->format('%m')].' de '.$primeiraData->format('%Y').'</span></b></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=ver_semana&data='.$proxSemana->format(FMT_TIMESTAMP_DATA).'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif'), 'Próxima Semana', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif')).' para exibir a próxima semana.').'</a></td></tr>';
echo '</table>';
$titulo='<h1><center>'.$primeiraData->format('%U').'ª Semana - '.$nome_meses[$primeiraData->format('%m')].' de '.$primeiraData->format('%Y').'</center></h1>';
echo '<table border=0 cellspacing="1" cellpadding="2" width="100%" style="margin-width:4px;background-color:black" class="mocal">';
$column = 0;
$mostrar_dia = $esta_semana;
$hoje = new CData();
$hoje = $hoje->format(FMT_TIMESTAMP_DATA);

$sql->adTabela('expediente');
$sql->adCampo('formatar_data(data,\'%Y%m%d\') AS feriado');
$sql->adOnde('cia_id='.(int)$Aplic->usuario_cia);
$sql->adOnde('diferenca_tempo(fim,inicio)=\'00:00:00\'');
$sql->adOnde('data >=\''.$primeiraData->format('%Y-%m-%d').'\'');
$sql->adOnde('data <\''.$ultimaData->format('%Y-%m-%d').'\'');
$feriados=$sql->Lista();
$sql->limpar();
$sem_expediente=array();
foreach($feriados as $feriado) $sem_expediente[]=$feriado['feriado'];
		
$sql->adTabela('expediente');
$sql->adCampo('formatar_data(data,\'%Y%m%d\') AS meio');
$sql->adOnde('cia_id='.(int)$Aplic->usuario_cia);
$sql->adOnde('diferenca_tempo(fim,inicio)=\''.horasSQL($config['horas_trab_diario']/2).'\'');
$sql->adOnde('data >=\''.$primeiraData->format('%Y-%m-%d').'\'');
$sql->adOnde('data <\''.$ultimaData->format('%Y-%m-%d').'\'');
$meios=$sql->Lista();
$sql->limpar();
$meio_expediente=array();
foreach($meios as $meio) $meio_expediente[]=$meio['meio'];


echo '<tr>';
for ($i = 0; $i < 7; $i++) {
	$diaFormato = $mostrar_dia->format(FMT_TIMESTAMP_DATA);
	$dia = $mostrar_dia->getDay();
	$href = 'm=email&a=ver_dia&data='.$diaFormato.'&tab=0';
	$diadasemana = intval($mostrar_dia->format('%w'));
	
	if ($diadasemana == 0 || $diadasemana == 6 || in_array($diaFormato, $sem_expediente)) $classe='fim_semana';
	elseif (in_array($diaFormato, $meio_expediente)) $classe='meio_expediente';
	else $classe='dia';
	echo '<td class="'.$classe.'" style="width:14.29%;" ondblclick="url_passar(0, \'m=email&a=editar_compromisso&data='.$diaFormato.'\');">';
	$dia_string = "<b>".htmlentities($mostrar_dia->format('%d'), ENT_COMPAT, $localidade_tipo_caract).'</b>';
	$nome_dia = htmlentities(dia_semana($mostrar_dia->format('%A')), ENT_COMPAT, $localidade_tipo_caract);
	echo '<table style="width:100%;border-spacing:0;">';
	echo '<tr><td align="left" colspan=2><a href="javascript:void(0);" onclick="url_passar(0, \''.$href.'\');">'.($diaFormato == $hoje ? '<span style="color:red">' : '').dica($dia_string.' '.$nome_dia, 'Clique para visualizar os compromissos e '.$config['tarefas'].' para este dia.').$dia_string.' '.$nome_dia.dicaF().($diaFormato == $hoje ? '</span>' : '').'</a></td></tr>';
	if (isset($links[$diaFormato])) {
		foreach ($links[$diaFormato] as $e) {
			echo $e['texto'];
			}
		}
	echo '</table></td>';
	$mostrar_dia->adSegundos(24 * 3600);
	}
echo '</tr>';

if (!$dialogo) echo '<tr><td colspan="20" align="right" bgcolor="#f2f0ec"><table width="100%"><tr><td width="20%"></td><td valign="top" align="center" width="60%"><table align="center"><tr><td style="border-style:solid;border-width:1px; background-color: #fefeed;">&nbsp;&nbsp;</td><td nowrap="nowrap">Meio expediente</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px; background-color: #f0e8e8;">&nbsp;&nbsp;</td><td nowrap="nowrap">Sem expediente</td></tr></table></td><td width="20%" align=right><a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=ver_dia&tab=0\');">'.dica('Compromissos para Hoje', 'Clique para exibir os compromissos para a data atual.').'hoje'.dicaF().'</a></td></tr></table></td></tr>';
echo '</table>';
if (!$dialogo) echo estiloFundoCaixa();
else echo '<script>self.print();</script>';
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
</script>	