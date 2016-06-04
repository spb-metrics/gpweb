<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cal_sdf;

if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();

$Aplic->carregarCalendarioJS();
setMicroTempo();
require_once ($Aplic->getClasseModulo('cias'));
require_once BASE_DIR.'/modulos/calendario/links_expediente.php';
$tamanho = intval(config('cal_tamanho_string'));


$cia_id=getParam($_REQUEST, 'cia_id', null);
$departamento_id=getParam($_REQUEST, 'departamento_id', null);
$usuario_id=getParam($_REQUEST, 'usuario_id', null);
$projeto_id=getParam($_REQUEST, 'projeto_id',null);
$tarefa_id=getParam($_REQUEST, 'tarefa_id',null);
$recurso_id=getParam($_REQUEST, 'recurso_id', null);

if (!$cia_id && !$tarefa_id && !$departamento_id && !$usuario_id && !$projeto_id && !$recurso_id) $cia_id=$Aplic->usuario_cia;
if ($usuario_id  || $projeto_id || $tarefa_id || $recurso_id) $cia_id=null;

$inserir =getParam($_REQUEST, 'inserir', 0);
$excluir =getParam($_REQUEST, 'excluir', 0);
$segunda =getParam($_REQUEST, 'segunda', 0);
$terca =getParam($_REQUEST, 'terca', 0);
$quarta =getParam($_REQUEST, 'quarta', 0);
$quinta =getParam($_REQUEST, 'quinta', 0);
$sexta =getParam($_REQUEST, 'sexta', 0);
$sabado =getParam($_REQUEST, 'sabado', 0);
$domingo =getParam($_REQUEST, 'domingo', 0);
$periodo_inicial=getParam($_REQUEST, 'periodo_inicial', null);
$periodo_final=getParam($_REQUEST, 'periodo_final', null);
if (!$periodo_final)$periodo_final=$periodo_inicial;
$horas_trab =getParam($_REQUEST, 'horas_trab', 0);
$hora_inicial =getParam($_REQUEST, 'hora_inicial',  substr($config['expediente_inicio'],0, 2));
$minuto_inicial =getParam($_REQUEST, 'minuto_inicial', substr($config['expediente_inicio'],3, 2));
$hora_final =getParam($_REQUEST, 'hora_final', substr($config['expediente_fim'],0, 2));
$minuto_final =getParam($_REQUEST, 'minuto_final', substr($config['expediente_fim'],3, 2));
$h_almoco_inicio=getParam($_REQUEST, 'h_almoco_inicio', substr($config['almoco_inicio'],0, 2));
$m_almoco_inicio=getParam($_REQUEST, 'm_almoco_inicio', substr($config['almoco_inicio'],3, 2));
$h_almoco_fim=getParam($_REQUEST, 'h_almoco_fim', substr($config['almoco_fim'],0, 2));
$m_almoco_fim=getParam($_REQUEST, 'm_almoco_fim', substr($config['almoco_fim'],3, 2));



$dias_semana=array();
if ($segunda)$dias_semana[]=0;
if ($terca)$dias_semana[]=1;
if ($quarta)$dias_semana[]=2;
if ($quinta)$dias_semana[]=3;
if ($sexta)$dias_semana[]=4;
if ($sabado)$dias_semana[]=5;
if ($domingo)$dias_semana[]=6;
if ($excluir){
	$q = new BDConsulta;
	$q->setExcluir('expediente');
	$q->adOnde('data >= \''.$periodo_inicial.'\' AND data <=\''.$periodo_final.'\'');
	if ($dias_semana) $q->adOnde('dia_semana(data) IN ('.implode(',', $dias_semana).')');
	if ($usuario_id) $q->adOnde('usuario_id='.(int)$usuario_id);
	elseif ($recurso_id) $q->adOnde('recurso_id='.(int)$recurso_id);
	elseif ($tarefa_id) $q->adOnde('tarefa_id='.(int)$tarefa_id);
	elseif ($projeto_id) $q->adOnde('projeto_id='.(int)$projeto_id);
	elseif ($departamento_id) $q->adOnde('dept_id='.(int)$departamento_id);
	else $q->adOnde('cia_id='.(int)$cia_id);

	$q->exec();
	$afetado=$bd->Affected_Rows();
	echo '<script>alert("'.($afetado > 1 ? 'Foram excluídos ':'Foi excluído ').$afetado.' expediente'.($afetado > 1 ? 's':'').' do calendário.")</script>';
	$q->limpar();
	}

if ($inserir){
	$q = new BDConsulta;
	$q->setExcluir('expediente');
	$q->adOnde('data >= \''.$periodo_inicial.'\' AND data <=\''.$periodo_final.'\'');
	if ($dias_semana) $q->adOnde('dia_semana(data) IN ('.implode(',', $dias_semana).')');
	if ($usuario_id) $q->adOnde('usuario_id='.(int)$usuario_id);
	elseif ($recurso_id) $q->adOnde('recurso_id='.(int)$recurso_id);
	elseif ($tarefa_id) $q->adOnde('tarefa_id='.(int)$tarefa_id);
	elseif ($projeto_id) $q->adOnde('projeto_id='.(int)$projeto_id);
	elseif ($departamento_id) $q->adOnde('dept_id='.(int)$departamento_id);
	else $q->adOnde('cia_id='.(int)$cia_id);
	
	$q->exec();
	$afetado=$bd->Affected_Rows();
	$q->limpar();
	$data1 = new CData($periodo_inicial);
	$data2 = new CData($periodo_final);
	$conversao=  array('0'=>'6', '1'=>'0', '2'=>'1', '3'=>'2', '4'=>'3', '5'=>'4', '6'=>'5' );
	$afetado=0;
	for ($i=$data1; $i->format(FMT_TIMESTAMP_DATA)<=$data2->format(FMT_TIMESTAMP_DATA); $i->adDias(1)){
		if (!$dias_semana || in_array($conversao[$i->format("%w")] , $dias_semana)){
			$q->adTabela('expediente');
			$q->adInserir('data', $i->format('%Y-%m-%d'));
			if ($usuario_id) $q->adInserir('usuario_id', (int)$usuario_id);
			elseif ($recurso_id) $q->adInserir('recurso_id', (int)$recurso_id);
			elseif ($tarefa_id) $q->adInserir('tarefa_id', (int)$tarefa_id);
			elseif ($projeto_id) $q->adInserir('projeto_id', (int)$projeto_id);
			elseif ($departamento_id) $q->adInserir('dept_id', (int)$departamento_id);
			else $q->adInserir('cia_id', (int)$cia_id);
			
			$q->adInserir('inicio', $hora_inicial.':'.$minuto_inicial.':00');
			$q->adInserir('fim', $hora_final.':'.$minuto_final.':00');
			$q->adInserir('almoco_inicio', $h_almoco_inicio.':'.$m_almoco_inicio.':00');
			$q->adInserir('almoco_fim', $h_almoco_fim.':'.$m_almoco_fim.':00');
			if (!$q->exec()) echo '<script>alert("Não foi possível inserir no histórico os dados do item '.$excluido['tarefa_custos_nome'].'")</script>';
			$afetado+=$bd->Affected_Rows();
			$q->limpar();
			}
		}
	echo '<script>alert("'.($afetado > 1 ? 'Foram inseridos ':'Foi inserido ').$afetado.' expediente'.($afetado > 1 ? 's':'').' no calendário.")</script>';
	}

$inc = 1;

$horas = array();
for ($atual = 0; $atual <= 24; $atual++) {
	if ($atual < 10) $chave_atual = "0".$atual;
	else $chave_atual = $atual;
	$horas[$chave_atual] = $atual;
	}	
$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;
$data = getParam($_REQUEST, 'data', '');
$botoesTitulo = new CBlocoTitulo('Expediente', 'calendario.png', $m, "$m.$a");

$botoesTitulo->adicionaBotao('m=calendario&a=expediente&cia_id='.(int)$cia_id.'&usuario_id='.(int)$usuario_id.'&projeto_id='.(int)$projeto_id.'&recurso_id='.(int)$recurso_id.'&tarefa_id='.(int)$tarefa_id, 'voltar','','Voltar','Voltar à tela de visualização de expediente.');

$botoesTitulo->mostrar();
echo '<form method="post" name="frmEditar">';

echo '<input type="hidden" name="m" value="calendario" />';
echo '<input type="hidden" name="a" value="editar_expediente" />';

echo '<input type="hidden" name="cia_id" value="'.$cia_id.'" />';
echo '<input type="hidden" name="departamento_id" value="'.$departamento_id.'" />';
echo '<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="recurso_id" value="'.$recurso_id.'" />';
echo '<input type="hidden" name="excluir" id="excluir" value="0" />';
echo '<input type="hidden" name="inserir" id="inserir" value="0" />';

echo estiloTopoCaixa();

echo '<table class="std" width="100%" cellspacing=0 cellpadding=0><tr><td><table align="center">';

if ($usuario_id) $titulo = nome_om($usuario_id,$Aplic->getPref('om_usuario'));
elseif ($tarefa_id) $titulo = nome_tarefa($tarefa_id);
elseif ($projeto_id) $titulo = nome_projeto($projeto_id);
elseif ($recurso_id) $titulo = nome_recurso($recurso_id);
else $titulo = nome_cia($cia_id);

echo '<tr><td colspan="5" align=center><h1>Expediente para '.$titulo.'</h1></td></tr>';


if ($periodo_inicial) $data = new CData($periodo_inicial);
echo '<tr>';
echo '<td align="left" nowrap="nowrap" width="120"><table><tr><td>&nbsp;'.dica('Data Inícial', 'Digite ou escolha no calendário a data inícial do intervalo de tempo em que serão editados os expedientes.').'Data Inicial'.dicaF().'</td></tr><tr><td nowrap="nowrap"><input type="hidden" name="periodo_inicial" id="periodo_inicial" value="'.$periodo_inicial.'" /><input type="text" size="9" name="data_inicial" id="data_inicial" onchange="setData(\'frmEditar\', \'inicial\');" value="'.($periodo_inicial ? $data->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data inícial do intervalo de tempo em que serão editados os expedientes.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn1" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 />'.dicaF().'</a></td></tr>';
if ($periodo_final) $data = new CData($periodo_final);
echo '<tr><td>&nbsp;</td></tr><tr><td>&nbsp;'.dica('Data Final', 'Digite ou escolha no calendário a data final do intervalo de tempo em que serão editados os expedientes.').'Data Final</td></tr><tr><td nowrap="nowrap"><input type="hidden" name="periodo_final" id="periodo_final" value="'.$periodo_final.'" /><input type="text" size="9" name="data_final" id="data_final" onchange="setData(\'frmEditar\', \'final\');" value="'.($periodo_final ? $data->format($df) : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Data Final', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data final do intervalo de tempo em que serão editados os expedientes.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 />'.dicaF().'</a></td></tr></table></td>';
echo '<td align="left" nowrap="nowrap" width="100"><table>';
echo '<tr><td align="right"><label for="segunda">'.dica('Segunda-Feira', 'Marque para afetar as segundas-feiras compreendidas entre a data de início e fim, de acordo com as opções abaixo').'2ª Feira'.dicaF().'</label><input type="checkbox" value="1" name="segunda" id="segunda" '.($segunda ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right"><label for="terca">'.dica('Terça-Feira', 'Marque para afetar as terças-feiras compreendidas entre a data de início e fim, de acordo com as opções abaixo').'3ª Feira'.dicaF().'</label><input type="checkbox" value="1" name="terca" id="terca" '.($terca ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right"><label for="terca">'.dica('Quarta-Feira', 'Marque para afetar as quarta-feiras compreendidas entre a data de início e fim, de acordo com as opções abaixo').'4ª Feira'.dicaF().'</label><input type="checkbox" value="1" name="quarta" id="quarta" '.($quarta ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right"><label for="terca">'.dica('Quinta-Feira', 'Marque para afetar as quinta-feiras compreendidas entre a data de início e fim, de acordo com as opções abaixo').'5ª Feira'.dicaF().'</label><input type="checkbox" value="1" name="quinta" id="quinta" '.($quinta ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right"><label for="terca">'.dica('Sexta-Feira', 'Marque para afetar as sexta-feiras compreendidas entre a data de início e fim, de acordo com as opções abaixo').'6ª Feira'.dicaF().'</label><input type="checkbox" value="1" name="sexta" id="sexta" '.($sexta ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right"><label for="terca">'.dica('Sábado', 'Marque para afetar os sábado compreendidas entre a data de início e fim, de acordo com as opções abaixo').'Sábado'.dicaF().'</label><input type="checkbox" value="1" name="sabado" id="sabado" '.($sabado ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right"><label for="terca">'.dica('Domingo', 'Marque para afetar os domingos compreendidas entre a data de início e fim, de acordo com as opções abaixo').'Domingo'.dicaF().'</label><input type="checkbox" value="1" name="domingo" id="domingo" '.($domingo ? 'checked="checked"' : '').' /></td></tr>';
echo '</table></td>';
echo '<td width="160"><table>';
echo '<tr><td>&nbsp;</td><tr><td align="center">'.dica('Início do Expediente', 'Escolha nas caixas de seleção abaixo a hora do ínicio do expediente').'Inicio do Expediente'.dicaF().'</td></tr><tr><td align="center">'.dica('Hora do Início', 'Selecione na caixa de seleção a hora do ínicio do expediente'). selecionaVetor($horas, 'hora_inicial', 'size="1" class="texto"', $hora_inicial).' : '.dica('Minutos do Início', 'Selecione na caixa de seleção os minutos do início do expediente.'). selecionaVetor($minutos, 'minuto_inicial', 'size="1" class="texto"', $minuto_inicial).'</td></tr>';
echo '<tr></tr><tr><td align="center">'.dica('Término do Expediente', 'Escolha nas caixas de seleção abaixo a hora de término do expediente').'Término do Expediente'.dicaF().'</td></tr><tr><td align="center">'.dica('Hora do Término', 'Selecione na caixa de seleção a hora do término do expediente'). selecionaVetor($horas, 'hora_final', 'size="1" class="texto"', $hora_final).' : '.dica('Minutos do Término', 'Selecione na caixa de seleção os minutos do término do expediente.'). selecionaVetor($minutos, 'minuto_final', 'size="1" class="texto"', $minuto_final).'</td></tr>';
echo '<tr><td>&nbsp;</td><tr><td align="center">'.dica('Início do Almoço', 'Escolha nas caixas de seleção abaixo a hora do ínicio do almoço').'Inicio do Almoço'.dicaF().'</td></tr><tr><td align="center">'.dica('Hora do Início', 'Selecione na caixa de seleção a hora do ínicio do almoço'). selecionaVetor($horas, 'h_almoco_inicio', 'size="1" class="texto"', $h_almoco_inicio).' : '.dica('Minutos do Início', 'Selecione na caixa de seleção os minutos do início do expediente.'). selecionaVetor($minutos, 'm_almoco_inicio', 'size="1" class="texto"', $m_almoco_inicio).'</td></tr>';
echo '<tr></tr><tr><td align="center">'.dica('Término do Almoço', 'Escolha nas caixas de seleção abaixo a hora de término do almoço').'Término do Almoço'.dicaF().'</td></tr><tr><td align="center">'.dica('Hora do Término', 'Selecione na caixa de seleção a hora do término do almoço'). selecionaVetor($horas, 'h_almoco_fim', 'size="1" class="texto"', $h_almoco_fim).' : '.dica('Minutos do Término', 'Selecione na caixa de seleção os minutos do término do expediente.'). selecionaVetor($minutos, 'm_almoco_fim', 'size="1" class="texto"', $h_almoco_fim).'</td></tr>';

echo '</table></td>';
echo '<td width="250"><div id="cont"></div></td>';
echo '<td><table><tr><td align="center">'.botao('inserir', 'Inserir','Insirir, nos dias de semana selecionados, dentro da faixa de tempo escolhida, as horas diárias de trabalho, assim como o início e término do expediente.<br><br>Caso já exista algum expediente previamente criado, ele será alterado.<br><br>Para criar dias sem expediente basta selecionar que o horário de início seja identico ao de término do expediente.','','document.getElementById(\'inserir\').value=1; frmEditar.submit()').'</td></tr>';
echo '<tr><td>&nbsp;</td><tr><tr><td align="center">'.botao('excluir', 'Excluir','Excluir, nos dias de semana selecionados, dentro da faixa de tempo escolhida, os expedintes.','','document.getElementById(\'excluir\').value=1; frmEditar.submit()').'</td></tr>';
echo '</table></td>';
echo '</tr>';
echo '<tr><td colspan="5" align="center">&nbsp;</td></tr><tr><td colspan="5" align="center"><table class="minical" align="center"><tr><td style="border-style:solid;border-width:1px" class="expediente_normal">&nbsp;&nbsp;</td><td nowrap="nowrap">Expediente Normal</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_meio">&nbsp;&nbsp;</td><td nowrap="nowrap">Meio Expediente</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_sem">&nbsp;&nbsp;</td><td nowrap="nowrap">Sem Expediente</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_outros">&nbsp;&nbsp;</td><td nowrap="nowrap">Expediente Alterntivo</td><td>&nbsp;</td><td class="hoje">&nbsp;&nbsp;</td><td nowrap="nowrap">Hoje</td><td>&nbsp;</td></tr></table></td></tr>';
$nomeDia=array('0'=>'Domingo', '1'=>'Segunda', '2'=>'Terça', '3'=>'Quarta', '4'=>'Quinta', '5'=>'Sexta', '6'=>'Sábado');
$dias_uteis=explode (',', $config['cal_dias_uteis']);
echo '<tr><td colspan="15" align="center"><table><tr><td>&nbsp;</td><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;">Dias Uteis</td><td>';
foreach ($dias_uteis as $chave => $valor) echo $nomeDia[$valor].' ';
echo '</td>';
echo '<td>&nbsp;</td><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;">Início</td><td>'.substr($config['expediente_inicio'],0, 5).'</td>';
echo '<td>&nbsp;</td><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;">Término</td><td>'.substr($config['expediente_fim'],0, 5).'</td>';
echo '</tr></table></td></tr>';


echo '</table></td></tr></table></form>';
echo estiloFundoCaixa();
?>
<script language="javascript">
function mudar_om(){	
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}
	
function mudar_usuario(){	
	var cia_id=document.getElementById('cia_id').value;
	var contato_id=document.getElementById('usuario_id').value;
	xajax_mudar_usuario_ajax(cia_id, usuario_id, 'usuario_id','combo_usuario', 'class="texto" size=1 style="width:250px;" onchange="escolheu_usuario();"'); 	
	}	
	
function escolheu_usuario(){
	document.frmUsuario.cia_id.value=document.frmCia.cia_id.value; 
	document.frmUsuario.submit();
	}	
	
	var INFO_DATA = {
	<?php
	$q = new BDConsulta;
	$q->adTabela('expediente');
	$q->adCampo('data, inicio, fim');
	$q->adCampo('IF (((almoco_inicio > inicio) AND (almoco_fim < fim)), (tempo_em_segundos(diferenca_tempo(almoco_inicio, inicio))+tempo_em_segundos(diferenca_tempo(fim, almoco_fim)))/3600, tempo_em_segundos(diferenca_tempo(fim, inicio))/3600) AS horas');
	//$q->adCampo('CASE WHEN ((almoco_inicio > inicio) AND (almoco_fim < fim)) THEN ( tempo_em_segundos(CAST((almoco_inicio - inicio) AS TIME)) + tempo_em_segundos(CAST((fim - almoco_fim) AS TIME)) / 3600 ) ELSE ( tempo_em_segundos(CAST((fim - inicio) AS TIME) ) / 3600 ) END AS horas');
	//EUZEBIO ERRADO
	if ($usuario_id) $q->adOnde('usuario_id='.(int)$usuario_id);
	elseif ($recurso_id) $q->adOnde('recurso_id='.(int)$recurso_id);
	elseif ($tarefa_id) $q->adOnde('tarefa_id='.(int)$tarefa_id);
	elseif ($projeto_id) $q->adOnde('projeto_id='.(int)$projeto_id);
	elseif ($departamento_id) $q->adOnde('dept_id='.(int)$departamento_id);
	else $q->adOnde('cia_id='.(int)$cia_id);
	$datas = $q->Lista();
	$q->limpar();
	foreach ($datas as $registro) {
		$data_expediente = new CData($registro['data']);
		$indice=$data_expediente->format("%Y%m%d");
		$integral=(int)($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
		if ($integral==$registro['horas']) $classe = 'normal';
		elseif ((($integral/2)<=$registro['horas']) && ($registro['horas']<=($integral*0.75))) $classe = 'meio';
		elseif ($registro['horas']<=0) $classe = 'sem';
		else $classe = 'outros';
		echo $indice.': { klass: "'.$classe.'", tooltip: ""}, ';
		}	
	?>
  	};

	 function getInfoData(date, wantsClassName) {
    var como_numero = Calendario.dateToInt(date);
    return INFO_DATA[como_numero];
  	}	

  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "periodo_inicial",
    dateInfo : getInfoData,
    onSelect: function(cal1) { 
	    var date = cal1.selection.get();
	    if (date){
	    	date = Calendario.intToDate(date);
	      document.getElementById("data_inicial").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("periodo_inicial").value = Calendario.printDate(date, "%Y-%m-%d");
	      }
	  	cal1.hide(); 
	  	}
  	});
  
	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "periodo_final",
    dateInfo : getInfoData,
    onSelect : function(cal2) { 
	    var date = cal2.selection.get();
	    if (date){
	      date = Calendario.intToDate(date);
	      document.getElementById("data_final").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("periodo_final").value = Calendario.printDate(date, "%Y-%m-%d");
	      }
	  	cal2.hide(); 
	  	}
  	});

var cal3 = Calendario.setup({
		cont     : "cont",
		<?php 
		if ($periodo_inicial) { 
			$data = new CData($periodo_inicial); 
			echo 'date  : '.$data->format(FMT_TIMESTAMP_DATA).',';
			}
			?>
    dateInfo : getInfoData,
  	});
  	


function setData( frm_nome, f_data ) {
	campo_data = eval( 'document.' + frm_nome + '.data_' + f_data );
	campo_data_real = eval( 'document.'+frm_nome+'.'+'periodo_' + f_data );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
        alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
        campo_data_real.value = '';
        campo_data.style.backgroundColor = 'red';
      	} 
    else {
      	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
      	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
        campo_data.style.backgroundColor = '';
				}
		} 
	else campo_data_real.value = '';
	}  	
  	
</script>