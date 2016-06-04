<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cal_sdf;

if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();

$Aplic->carregarCalendarioJS();
require_once BASE_DIR.'/modulos/calendario/jornada_links.php';
require_once BASE_DIR.'/modulos/calendario/jornada.class.php';

$tamanho = intval(config('cal_tamanho_string'));

$jornada_id=getParam($_REQUEST, 'jornada_id', null);
$cia_id=getParam($_REQUEST, 'cia_id', null);
$departamento_id=getParam($_REQUEST, 'departamento_id', null);
$usuario_id=getParam($_REQUEST, 'usuario_id', null);
$projeto_id=getParam($_REQUEST, 'projeto_id',null);
$tarefa_id=getParam($_REQUEST, 'tarefa_id',null);
$recurso_id=getParam($_REQUEST, 'recurso_id', null);
$jornada_mudar=getParam($_REQUEST, 'jornada_mudar', null);

$segunda=getParam($_REQUEST, 'segunda', 0);
$terca=getParam($_REQUEST, 'terca', 0);
$quarta=getParam($_REQUEST, 'quarta', 0);
$quinta=getParam($_REQUEST, 'quinta', 0);
$sexta=getParam($_REQUEST, 'sexta', 0);
$sabado=getParam($_REQUEST, 'sabado', 0);
$domingo=getParam($_REQUEST, 'domingo', 0);
$periodo_inicial=getParam($_REQUEST, 'periodo_inicial', null);
$periodo_final=getParam($_REQUEST, 'periodo_final', null);
$horas_trab=getParam($_REQUEST, 'horas_trab', 0);
$hora_inicial=getParam($_REQUEST, 'hora_inicial',  substr($config['expediente_inicio'],0, 2));
$minuto_inicial=getParam($_REQUEST, 'minuto_inicial', substr($config['expediente_inicio'],3, 2));
$hora_final=getParam($_REQUEST, 'hora_final', substr($config['expediente_fim'],0, 2));
$minuto_final=getParam($_REQUEST, 'minuto_final', substr($config['expediente_fim'],3, 2));
$h_almoco_inicio=getParam($_REQUEST, 'h_almoco_inicio', substr($config['almoco_inicio'],0, 2));
$m_almoco_inicio=getParam($_REQUEST, 'm_almoco_inicio', substr($config['almoco_inicio'],3, 2));
$h_almoco_fim=getParam($_REQUEST, 'h_almoco_fim', substr($config['almoco_fim'],0, 2));
$m_almoco_fim=getParam($_REQUEST, 'm_almoco_fim', substr($config['almoco_fim'],3, 2));
$anual=getParam($_REQUEST, 'anual', 0);



if (!$cia_id && !$tarefa_id && !$departamento_id && !$usuario_id && !$projeto_id && !$recurso_id && !$jornada_id) $cia_id=$Aplic->usuario_cia;
if ($usuario_id  || $projeto_id || $tarefa_id || $recurso_id || $jornada_id) $cia_id=null;

if (!$periodo_final)$periodo_final=$periodo_inicial;


$sql = new BDConsulta;

$dias_semana=array();
if ($segunda) $dias_semana[]=0;
if ($terca) $dias_semana[]=1;
if ($quarta) $dias_semana[]=2;
if ($quinta) $dias_semana[]=3;
if ($sexta) $dias_semana[]=4;
if ($sabado) $dias_semana[]=5;
if ($domingo) $dias_semana[]=6;

if (getParam($_REQUEST, 'mudar_calendario', 0)){
	$sql->setExcluir('jornada_pertence');
	if ($usuario_id) $sql->adOnde('jornada_pertence_usuario='.(int)$usuario_id);
	elseif ($recurso_id) $sql->adOnde('jornada_pertence_recurso='.(int)$recurso_id);
	elseif ($tarefa_id) $sql->adOnde('jornada_pertence_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('jornada_pertence_projeto='.(int)$projeto_id);
	elseif ($departamento_id) $sql->adOnde('jornada_pertence_dept='.(int)$departamento_id);
	elseif($cia_id) $sql->adOnde('jornada_pertence_cia='.(int)$cia_id);
	$sql->exec();
	$sql->limpar();

	$sql->setExcluir('jornada_excessao');
	if ($usuario_id) $sql->adOnde('jornada_excessao_usuario='.(int)$usuario_id);
	elseif ($recurso_id) $sql->adOnde('jornada_excessao_recurso='.(int)$recurso_id);
	elseif ($tarefa_id) $sql->adOnde('jornada_excessao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('jornada_excessao_projeto='.(int)$projeto_id);
	elseif ($departamento_id) $sql->adOnde('jornada_excessao_dept='.(int)$departamento_id);
	else $sql->adOnde('jornada_excessao_cia='.(int)$cia_id);
	$sql->exec();
	$sql->limpar();


	if ($jornada_mudar){
		$sql->adTabela('jornada_pertence');
		$sql->adInserir('jornada_pertence_jornada', $jornada_mudar);
		if ($usuario_id) $sql->adInserir('jornada_pertence_usuario', (int)$usuario_id);
		elseif ($recurso_id) $sql->adInserir('jornada_pertence_recurso', (int)$recurso_id);
		elseif ($tarefa_id) $sql->adInserir('jornada_pertence_tarefa', (int)$tarefa_id);
		elseif ($projeto_id) $sql->adInserir('jornada_pertence_projeto', (int)$projeto_id);
		elseif ($departamento_id) $sql->adInserir('jornada_pertence_dept', (int)$departamento_id);
		else $sql->adInserir('jornada_pertence_cia', (int)$cia_id);
		$sql->exec();
		$sql->limpar();
		}
	ver2('Calendário modificado');
	}



if (getParam($_REQUEST, 'excluir', 0)){
	$sql->setExcluir('jornada_excessao');
	$sql->adOnde('jornada_excessao_data >= \''.$periodo_inicial.'\' AND jornada_excessao_data <=\''.$periodo_final.'\'');
	if ($dias_semana) $sql->adOnde('dia_semana(jornada_excessao_data) IN ('.implode(',', $dias_semana).')');
	if ($usuario_id) $sql->adOnde('jornada_excessao_usuario='.(int)$usuario_id);
	elseif ($recurso_id) $sql->adOnde('jornada_excessao_recurso='.(int)$recurso_id);
	elseif ($tarefa_id) $sql->adOnde('jornada_excessao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('jornada_excessao_projeto='.(int)$projeto_id);
	elseif ($departamento_id) $sql->adOnde('jornada_excessao_dept='.(int)$departamento_id);
	elseif ($cia_id) $sql->adOnde('jornada_excessao_cia='.(int)$cia_id);
	else $sql->adOnde('jornada_excessao_jornada='.(int)$jornada_id);
	$sql->exec();
	$afetado=$bd->Affected_Rows();
	$sql->limpar();
	echo '<script>alert("'.($afetado > 1 ? 'Foram excluídos ':'Foi excluído ').$afetado.' expediente'.($afetado > 1 ? 's':'').' do calendário.")</script>';
	$sql->limpar();
	}

if (getParam($_REQUEST, 'inserir', 0)){

	$sql->setExcluir('jornada_excessao');
	$sql->adOnde('jornada_excessao_data >= \''.$periodo_inicial.'\' AND jornada_excessao_data <=\''.$periodo_final.'\'');
	if ($dias_semana) $sql->adOnde('dia_semana(jornada_excessao_data) IN ('.implode(',', $dias_semana).')');
	if ($usuario_id) $sql->adOnde('jornada_excessao_usuario='.(int)$usuario_id);
	elseif ($recurso_id) $sql->adOnde('jornada_excessao_recurso='.(int)$recurso_id);
	elseif ($tarefa_id) $sql->adOnde('jornada_excessao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('jornada_excessao_projeto='.(int)$projeto_id);
	elseif ($departamento_id) $sql->adOnde('jornada_excessao_dept='.(int)$departamento_id);
	elseif ($cia_id) $sql->adOnde('jornada_excessao_cia='.(int)$cia_id);
	else $sql->adOnde('jornada_excessao_jornada='.(int)$jornada_id);
	$sql->exec();
	$sql->limpar();
	$data1 = new CData($periodo_inicial);
	$data2 = new CData($periodo_final);
	$conversao=  array('0'=>'6', '1'=>'0', '2'=>'1', '3'=>'2', '4'=>'3', '5'=>'4', '6'=>'5' );
	$afetado=0;
	
	
	$inicio=strtotime($hora_inicial.':'.$minuto_inicial.':00');
	$fim=strtotime($hora_final.':'.$minuto_final.':00');
	$almoco_inicio=strtotime($h_almoco_inicio.':'.$m_almoco_inicio.':00');
	$almoco_fim=strtotime($h_almoco_fim.':'.$m_almoco_fim.':00');
	
	$duracao=0;
	
	if ($almoco_fim <= $inicio) {
		$duracao=($fim-$inicio)/3600;
		}
	else if ($almoco_inicio >= $fim) {
		$duracao=($fim-$inicio)/3600;
		}
	else if (($almoco_inicio <= $inicio) && ($almoco_fim <= $fim)) {
		$duracao=($fim-$almoco_fim)/3600;
		}
	else if (($almoco_fim >= $fim) && ($almoco_inicio <= $fim)) {
		$duracao=($almoco_inicio-$inicio)/3600;
		}
	elseif (($inicio <= $almoco_inicio) && ($almoco_fim <= $fim))	{
		$duracao=(($almoco_inicio-$inicio)+($fim-$almoco_fim))/3600;
		}
	else $duracao=($fim-$inicio)/3600;

	for ($i=$data1; $i->format(FMT_TIMESTAMP_DATA)<=$data2->format(FMT_TIMESTAMP_DATA); $i->adDias(1)){
		if (!$dias_semana || in_array($conversao[$i->format("%w")] , $dias_semana)){
			$sql->adTabela('jornada_excessao');
			$sql->adInserir('jornada_excessao_data', $i->format(FMT_TIMESTAMP_DATA));
			if ($usuario_id) $sql->adInserir('jornada_excessao_usuario', (int)$usuario_id);
			elseif ($recurso_id) $sql->adInserir('jornada_excessao_recurso', (int)$recurso_id);
			elseif ($tarefa_id) $sql->adInserir('jornada_excessao_tarefa', (int)$tarefa_id);
			elseif ($projeto_id) $sql->adInserir('jornada_excessao_projeto', (int)$projeto_id);
			elseif ($departamento_id) $sql->adInserir('jornada_excessao_dept', (int)$departamento_id);
			elseif ($cia_id) $sql->adInserir('jornada_excessao_cia', (int)$cia_id);
			else $sql->adInserir('jornada_excessao_jornada', (int)$jornada_id);
			$sql->adInserir('jornada_excessao_duracao', $duracao);
			$sql->adInserir('jornada_excessao_trabalha', ($duracao > 0 ? 1 : 0));
			$sql->adInserir('jornada_excessao_anual', $anual);
			$sql->adInserir('jornada_excessao_inicio', $hora_inicial.':'.$minuto_inicial.':00');
			$sql->adInserir('jornada_excessao_fim', $hora_final.':'.$minuto_final.':00');
			$sql->adInserir('jornada_excessao_almoco_inicio', $h_almoco_inicio.':'.$m_almoco_inicio.':00');
			$sql->adInserir('jornada_excessao_almoco_fim', $h_almoco_fim.':'.$m_almoco_fim.':00');
			$sql->exec();
			$afetado+=$bd->Affected_Rows();
			$sql->limpar();
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

if ($Aplic->usuario_admin || $Aplic->usuario_super_admin) $botoesTitulo->adicionaBotao('m=calendario&a=jornada&cia_id='.(int)$cia_id.'&usuario_id='.(int)$usuario_id.'&projeto_id='.(int)$projeto_id.'&recurso_id='.(int)$recurso_id.'&tarefa_id='.(int)$tarefa_id.'&jornada_id='.(int)$jornada_id, 'voltar','','Voltar','Voltar à tela de visualização de expediente.');
elseif ($tarefa_id) $botoesTitulo->adicionaBotao('m=tarefas&a=ver&tarefa_id='.(int)$tarefa_id, 'voltar','','Voltar','Voltar ao detalhe d'.$config['genero_tarefa'].' '.$config['tarefa'].'.');
elseif ($projeto_id) $botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.(int)$projeto_id, 'voltar','','Voltar','Voltar ao detalhe d'.$config['genero_projeto'].' '.$config['projeto'].'.');

$botoesTitulo->mostrar();
echo '<form method="post" name="env">';
echo '<input type="hidden" name="m" value="calendario" />';
echo '<input type="hidden" name="a" value="jornada_editar" />';
echo '<input type="hidden" name="cia_id" value="'.$cia_id.'" />';
echo '<input type="hidden" name="departamento_id" value="'.$departamento_id.'" />';
echo '<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="recurso_id" value="'.$recurso_id.'" />';
echo '<input type="hidden" name="excluir" id="excluir" value="0" />';
echo '<input type="hidden" name="inserir" id="inserir" value="0" />';
echo '<input type="hidden" name="mudar_calendario" id="mudar_calendario" value="0" />';

echo estiloTopoCaixa();

echo '<table class="std" width="100%" cellspacing=0 cellpadding=0><tr><td><table  align=center cellspacing=0 cellpadding=0>';

$sql->adTabela('jornada');
$sql->adCampo('jornada_id, jornada_nome');
$sql->adOrdem('jornada_nome'); 
$calendarios=array(null => '')+$sql->listaVetorChave('jornada_id','jornada_nome');
$sql->limpar();

if ($usuario_id) $titulo = nome_om($usuario_id,$Aplic->getPref('om_usuario'));
elseif ($recurso_id) $titulo = nome_recurso($recurso_id);
elseif ($tarefa_id) $titulo = nome_tarefa($tarefa_id);
elseif ($projeto_id) $titulo = nome_projeto($projeto_id);
elseif ($cia_id) $titulo = nome_cia($cia_id);
elseif ($jornada_id) $titulo = nome_jornada($jornada_id);

echo '<tr><td colspan=4 align=center><h1>Expediente para '.$titulo.'</h1></td></tr>';

if (!$jornada_id){
	$sql->adTabela('jornada_pertence');
	$sql->adCampo('jornada_pertence_jornada');
	if ($usuario_id) $sql->adOnde('jornada_pertence_usuario='.(int)$usuario_id);
	else if ($recurso_id) $sql->adOnde('jornada_pertence_recurso='.(int)$recurso_id);
	else if ($tarefa_id) $sql->adOnde('jornada_pertence_tarefa='.(int)$tarefa_id);
	else if ($projeto_id) $sql->adOnde('jornada_pertence_projeto='.(int)$projeto_id);
	else if ($cia_id) $sql->adOnde('jornada_pertence_cia='.(int)$cia_id);
	$jornada_mudar=$sql->resultado();
	$sql->limpar();
	echo '<tr><td colspan=4 align=center><table cellspacing=0 cellpadding=0><tr><td>'.dica('Calendário', 'Calendário base para edição de expediente').'Calendário:'.dicaF().'</td><td>'.selecionaVetor($calendarios, 'jornada_mudar', 'class="texto" onchange="env.mudar_calendario.value=1; env.submit()"', $jornada_mudar).'</td></tr></table></td></tr>';
	echo '<input type="hidden" name="jornada_id" id="jornada_id" value="" />';
	}
else {
	echo '<input type="hidden" name="jornada_id" id="jornada_id" value="'.$jornada_id.'" />';
	echo '<input type="hidden" name="jornada_mudar" id="jornada_mudar" value="" />';	
	}

if ($periodo_inicial) $data = new CData($periodo_inicial);

if ($jornada_mudar){

	echo '<tr>';
	echo '<td align="left" nowrap="nowrap" width="120" ><table cellspacing=0 cellpadding=0><tr><td>&nbsp;'.dica('Data Inícial', 'Digite ou escolha no calendário a data inícial do intervalo de tempo em que serão editados os expedientes.').'Data Inicial'.dicaF().'</td></tr><tr><td nowrap="nowrap"><input type="hidden" name="periodo_inicial" id="periodo_inicial" value="'.$periodo_inicial.'" /><input type="text" size="9" name="data_inicial" id="data_inicial" onchange="setData(\'env\', \'inicial\');" value="'.($periodo_inicial ? $data->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data inícial do intervalo de tempo em que serão editados os expedientes.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn1" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 />'.dicaF().'</a></td></tr>';
	if ($periodo_final) $data = new CData($periodo_final);
	echo '<tr><td>&nbsp;</td></tr><tr><td>&nbsp;'.dica('Data Final', 'Digite ou escolha no calendário a data final do intervalo de tempo em que serão editados os expedientes.').'Data Final</td></tr><tr><td nowrap="nowrap"><input type="hidden" name="periodo_final" id="periodo_final" value="'.$periodo_final.'" /><input type="text" size="9" name="data_final" id="data_final" onchange="setData(\'env\', \'final\');" value="'.($periodo_final ? $data->format($df) : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Data Final', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data final do intervalo de tempo em que serão editados os expedientes.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 />'.dicaF().'</a></td></tr></table></td>';
	echo '<td align="left" nowrap="nowrap" width="100"><table cellspacing=0 cellpadding=0>';
	echo '<tr><td align="right"><label for="segunda">'.dica('Segunda-Feira', 'Marque para afetar as segundas-feiras compreendidas entre a data de início e fim, de acordo com as opções abaixo').'2ª Feira'.dicaF().'</label><input type="checkbox" value="1" name="segunda" id="segunda" '.($segunda ? 'checked="checked"' : '').' /></td></tr>';
	echo '<tr><td align="right"><label for="terca">'.dica('Terça-Feira', 'Marque para afetar as terças-feiras compreendidas entre a data de início e fim, de acordo com as opções abaixo').'3ª Feira'.dicaF().'</label><input type="checkbox" value="1" name="terca" id="terca" '.($terca ? 'checked="checked"' : '').' /></td></tr>';
	echo '<tr><td align="right"><label for="terca">'.dica('Quarta-Feira', 'Marque para afetar as quarta-feiras compreendidas entre a data de início e fim, de acordo com as opções abaixo').'4ª Feira'.dicaF().'</label><input type="checkbox" value="1" name="quarta" id="quarta" '.($quarta ? 'checked="checked"' : '').' /></td></tr>';
	echo '<tr><td align="right"><label for="terca">'.dica('Quinta-Feira', 'Marque para afetar as quinta-feiras compreendidas entre a data de início e fim, de acordo com as opções abaixo').'5ª Feira'.dicaF().'</label><input type="checkbox" value="1" name="quinta" id="quinta" '.($quinta ? 'checked="checked"' : '').' /></td></tr>';
	echo '<tr><td align="right"><label for="terca">'.dica('Sexta-Feira', 'Marque para afetar as sexta-feiras compreendidas entre a data de início e fim, de acordo com as opções abaixo').'6ª Feira'.dicaF().'</label><input type="checkbox" value="1" name="sexta" id="sexta" '.($sexta ? 'checked="checked"' : '').' /></td></tr>';
	echo '<tr><td align="right"><label for="terca">'.dica('Sábado', 'Marque para afetar os sábado compreendidas entre a data de início e fim, de acordo com as opções abaixo').'Sábado'.dicaF().'</label><input type="checkbox" value="1" name="sabado" id="sabado" '.($sabado ? 'checked="checked"' : '').' /></td></tr>';
	echo '<tr><td align="right"><label for="terca">'.dica('Domingo', 'Marque para afetar os domingos compreendidas entre a data de início e fim, de acordo com as opções abaixo').'Domingo'.dicaF().'</label><input type="checkbox" value="1" name="domingo" id="domingo" '.($domingo ? 'checked="checked"' : '').' /></td></tr>';
	
	echo '<tr><td align="right"><label for="anual">'.dica('Anual', 'Marque caso ocorra todos os anos.').'Anual'.dicaF().'</label><input type="checkbox" value="1" name="anual" id="anual" '.($anual ? 'checked="checked"' : '').' /></td></tr>';
	
	echo '</table></td>';
	echo '<td width="160" ><table cellspacing=0 cellpadding=0>';
	echo '<tr><td>&nbsp;</td><tr><td align="center">'.dica('Início do Expediente', 'Escolha nas caixas de seleção abaixo a hora do ínicio do expediente').'Inicio do Expediente'.dicaF().'</td></tr><tr><td align="center">'.dica('Hora do Início', 'Selecione na caixa de seleção a hora do ínicio do expediente'). selecionaVetor($horas, 'hora_inicial', 'size="1" class="texto"', $hora_inicial).' : '.dica('Minutos do Início', 'Selecione na caixa de seleção os minutos do início do expediente.'). selecionaVetor($minutos, 'minuto_inicial', 'size="1" class="texto"', $minuto_inicial).'</td></tr>';
	echo '<tr></tr><tr><td align="center">'.dica('Término do Expediente', 'Escolha nas caixas de seleção abaixo a hora de término do expediente').'Término do Expediente'.dicaF().'</td></tr><tr><td align="center">'.dica('Hora do Término', 'Selecione na caixa de seleção a hora do término do expediente'). selecionaVetor($horas, 'hora_final', 'size="1" class="texto"', $hora_final).' : '.dica('Minutos do Término', 'Selecione na caixa de seleção os minutos do término do expediente.'). selecionaVetor($minutos, 'minuto_final', 'size="1" class="texto"', $minuto_final).'</td></tr>';
	echo '<tr><td>&nbsp;</td><tr><td align="center">'.dica('Início do Almoço', 'Escolha nas caixas de seleção abaixo a hora do ínicio do almoço').'Inicio do Almoço'.dicaF().'</td></tr><tr><td align="center">'.dica('Hora do Início', 'Selecione na caixa de seleção a hora do ínicio do almoço'). selecionaVetor($horas, 'h_almoco_inicio', 'size="1" class="texto"', $h_almoco_inicio).' : '.dica('Minutos do Início', 'Selecione na caixa de seleção os minutos do início do expediente.'). selecionaVetor($minutos, 'm_almoco_inicio', 'size="1" class="texto"', $m_almoco_inicio).'</td></tr>';
	echo '<tr></tr><tr><td align="center">'.dica('Término do Almoço', 'Escolha nas caixas de seleção abaixo a hora de término do almoço').'Término do Almoço'.dicaF().'</td></tr><tr><td align="center">'.dica('Hora do Término', 'Selecione na caixa de seleção a hora do término do almoço'). selecionaVetor($horas, 'h_almoco_fim', 'size="1" class="texto"', $h_almoco_fim).' : '.dica('Minutos do Término', 'Selecione na caixa de seleção os minutos do término do expediente.'). selecionaVetor($minutos, 'm_almoco_fim', 'size="1" class="texto"', $h_almoco_fim).'</td></tr>';
	
	echo '</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0><tr><td align="center">'.botao('inserir', 'Inserir','Insirir, nos dias de semana selecionados, dentro da faixa de tempo escolhida, as horas diárias de trabalho, assim como o início e término do expediente.<br><br>Caso já exista algum expediente previamente criado, ele será alterado.<br><br>Para criar dias sem expediente basta selecionar que o horário de início seja identico ao de término do expediente.','','inserirData()').'</td><td>&nbsp;</td><td align="center">'.botao('excluir', 'Excluir','Excluir, nos dias de semana selecionados, dentro da faixa de tempo escolhida, os expedintes.','','excluirData();').'</td></tr></table></td>';
	echo '</tr>';
	
	}

$jornada=new Cjornada($cia_id, $usuario_id, $projeto_id, $recurso_id, $tarefa_id, $jornada_id);
$data=getParam($_REQUEST, 'data', '');
if (!$data) $data = new CData();
else $data = new CData($data);

$data->setDay(1);
$data->setMonth(1);
$anoAnterior = $data->format(FMT_TIMESTAMP_DATA);
$anoAnterior = (int)($anoAnterior - 10000);
$anoProximo = $data->format(FMT_TIMESTAMP_DATA);
$anoProximo = (int)($anoProximo + 10000);


echo '<tr><td colspan=20>';
echo '<table border=0 cellspacing=0 cellpadding="2" width="100%" class="motitulo">';
echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&data='.$anoAnterior.'&cia_id='.(int)$cia_id.'&usuario_id='.$usuario_id.'&projeto_id='.$projeto_id.'&tarefa_id='.$tarefa_id.'&recurso_id='.$recurso_id.'&jornada_id='.(int)$jornada_id.'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif'), 'Ano Anterior', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif')).' para exibir o ano anterior.').'</a></td>';
echo '<th width="100%" align="center">'.htmlentities($data->format('%Y')).'</th><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&data='.$anoProximo.'&cia_id='.(int)$cia_id.'&usuario_id='.$usuario_id.'&projeto_id='.$projeto_id.'&tarefa_id='.$tarefa_id.'&recurso_id='.$recurso_id.'&jornada_id='.(int)$jornada_id.'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif'), 'Próximo Ano', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif')).' para exibir o próximo ano.').'</a></td></tr></table></td></tr>';
$jornada->setData($data);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$jornada->calendarioMesAtual().'</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$jornada->calendarioMesAtual().'</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';

$jornada->adicionarMes(1);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';

$jornada->adicionarMes(1);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';

echo '</tr></table>';



echo '</td></tr>';













echo '<tr><td colspan="5" align="center">&nbsp;</td></tr><tr><td colspan="5" align="center"><table class="minical" align="center"><tr><td style="border-style:solid;border-width:1px" class="expediente_normal">&nbsp;&nbsp;</td><td nowrap="nowrap">Expediente Normal</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_meio">&nbsp;&nbsp;</td><td nowrap="nowrap">Meio Expediente</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_sem">&nbsp;&nbsp;</td><td nowrap="nowrap">Sem Expediente</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_outros">&nbsp;&nbsp;</td><td nowrap="nowrap">Expediente Alterntivo</td><td>&nbsp;</td><td class="hoje">&nbsp;&nbsp;</td><td nowrap="nowrap">Hoje</td><td>&nbsp;</td></tr></table></td></tr>';
echo '</table></td></tr></table></form>';
echo estiloFundoCaixa();
?>
<script language="javascript">
	
	
	
function inserirData(){	

	if (document.getElementById('jornada_id').value > 0 || document.getElementById('jornada_mudar').value) {
		document.getElementById('inserir').value=1; 
		document.env.submit();
		} 
	else alert("Escolha um calendário primeiro");

	}
	
function excluirData(){		
	if (document.getElementById('jornada_id').value > 0 || document.getElementById('jornada_mudar').value) {
		document.getElementById('excluir').value=1; 
		document.env.submit();
		} 
	else alert("Escolha um calendário primeiro");
	}	
	
function mudar_om(){	
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="mudar_om();"'); 	
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



  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "periodo_inicial",
    onSelect: function(cal1) { 
	    var date = cal1.selection.get();
	    if (date){
	    	date = Calendario.intToDate(date);
	      document.getElementById("data_inicial").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("periodo_inicial").value = Calendario.printDate(date, "%Y-%m-%d");
	      CompararDatas();
	      }
	  	cal1.hide(); 
	  	}
  	});
  
	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "periodo_final",
    onSelect : function(cal2) { 
	    var date = cal2.selection.get();
	    if (date){
	      date = Calendario.intToDate(date);
	      document.getElementById("data_final").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("periodo_final").value = Calendario.printDate(date, "%Y-%m-%d");
	      CompararDatas();
	      }
	  	cal2.hide(); 
	  	}
  	});


function CompararDatas(){
    var str1 = document.getElementById("data_inicial").value;
    var str2 = document.getElementById("data_final").value;
    var dt1  = parseInt(str1.substring(0,2),10);
    var mon1 = parseInt(str1.substring(3,5),10);
    var yr1  = parseInt(str1.substring(6,10),10);
    var dt2  = parseInt(str2.substring(0,2),10);
    var mon2 = parseInt(str2.substring(3,5),10);
    var yr2  = parseInt(str2.substring(6,10),10);
    var date1 = new Date(yr1, mon1, dt1);
    var date2 = new Date(yr2, mon2, dt2);
    
    if(document.getElementById("data_final").value==''){
    	document.getElementById("data_final").value=document.getElementById("data_inicial").value;
      document.getElementById("periodo_final").value=document.getElementById("periodo_inicial").value;
    	}
    else if(document.getElementById("data_inicial").value==''){
    	document.getElementById("data_inicial").value=document.getElementById("data_inicial").value;
      document.getElementById("periodo_inicial").value=document.getElementById("periodo_final").value;
    	}
    else if(date2 < date1){
      document.getElementById("data_final").value=document.getElementById("data_inicial").value;
      document.getElementById("periodo_final").value=document.getElementById("periodo_inicial").value;
    	}
   }

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
        CompararDatas();
				}
		} 
	else campo_data_real.value = '';
	}  	
  	
</script>