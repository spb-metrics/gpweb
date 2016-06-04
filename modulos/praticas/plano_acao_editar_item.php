<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$plano_acao_id=getParam($_REQUEST, 'plano_acao_id', 0);

require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';

$obj = new CPlanoAcao();
$obj->load($plano_acao_id);

$Aplic->carregarCalendarioJS();

$percentual=getSisValor('TarefaPorcentagem','','','sisvalor_id');
$inicio = 0;
$fim = 24;
$inc = 1;
$horas = array();
for ($atual = $inicio; $atual < $fim + 1; $atual++) {
	if ($atual < 10) $chave_atual = "0".$atual;
	else $chave_atual = $atual;
	if (stristr($Aplic->getPref('formatohora'), '%p')) $horas[$chave_atual] = ($atual > 12 ? $atual - 12 : $atual);
	else 	$horas[$chave_atual] = $atual;
	}
$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;




$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'acao\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="plano_acao_editar_item" />';
echo '<input type="hidden" name="plano_acao_item_id" id="plano_acao_item_id" value="" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.uuid().'" />';
echo '<input type="hidden" name="plano_acao_id" id="plano_acao_id" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="plano_acao_item_id" id="plano_acao_item_id" value="" />';
echo '<input name="plano_acao_item_usuarios" id="plano_acao_item_usuarios" type="hidden" value="" />';
echo '<input name="plano_acao_item_depts" id="plano_acao_item_depts" type="hidden" value="" />';

echo '<input name="plano_acao_calculo_porcentagem" id="plano_acao_calculo_porcentagem" type="hidden" value="'.$obj->plano_acao_calculo_porcentagem.'" />';
echo '<input name="exibir_porcentagem_item" id="exibir_porcentagem_item" type="hidden" value="'.($Aplic->profissional ? $exibir['porcentagem_item'] : '').'" />';



$sql->adTabela('plano_acao');
$sql->adCampo('plano_acao_inicio, plano_acao_fim, plano_acao_cia, plano_acao_responsavel');
$sql->adOnde('plano_acao_id='.(int)$plano_acao_id);
$linha_plano_acao=$sql->Linha();
$df = '%d/%m/%Y';
$data_inicio = intval($linha_plano_acao['plano_acao_inicio']) ? new CData($linha_plano_acao['plano_acao_inicio']) :  new CData(date("Y-m-d H:i:s"));
$data_fim = intval($linha_plano_acao['plano_acao_fim']) ? new CData($linha_plano_acao['plano_acao_fim']) : new CData(date("Y-m-d H:i:s"));

echo '<input name="plano_acao_inicio" id="plano_acao_inicio" type="hidden" value="'.$data_inicio->format('%Y-%m-%d %H:%M:%S').'" />';
echo '<input name="plano_acao_fim" id="plano_acao_fim" type="hidden" value="'.$data_fim->format('%Y-%m-%d %H:%M:%S').'" />';

$botoesTitulo = new CBlocoTitulo('Ações d'.$config['genero_acao'].' '.ucfirst($config['acao']), 'acao_plano.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=praticas&a=plano_acao_ver&plano_acao_id='.$plano_acao_id, 'retornar','','Retornar '.($config['genero_acao']=='a' ? 'a ': 'ao ').ucfirst($config['acao']),'Retornar aos detahes d'.$config['genero_acao'].' '.$config['acao'].'.');
$botoesTitulo->mostrar();


echo estiloTopoCaixa();
echo '<table width="100%" class="std" cellpadding=0 cellspacing=0>';
echo '<tr><td colspan=20><tr><td><table cellpadding=0 cellspacing=0 border=1><tr><td><table width="700" cellpadding=0 cellspacing=1>';
echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->plano_acao_cor.'" colspan="2"><font color="'.melhorCor($obj->plano_acao_cor).'"><b>'.$obj->plano_acao_nome.'<b></font></td></tr>';

echo '<tr><td align="right">'.dica('Nome do item d'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Todo item d'.$config['genero_acao'].' '.$config['acao'].' necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" id="plano_acao_item_nome" name="plano_acao_item_nome" value="" style="width:234px;" class="texto" />*</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'Mesmo que '.$config['genero_acao'].' '.$config['acao'].' seja em proveito de outr'.$config['genero_organizacao'].' '.$config['organizacao'].', deve-se selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que será encarregada de liderar '.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td nowrap="nowrap" colspan="2"><div id="combo_cia">'.selecionar_om(($linha_plano_acao['plano_acao_cia']  ? $linha_plano_acao['plano_acao_cia'] : $Aplic->usuario_cia), 'plano_acao_item_cia', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
echo '<input type="hidden" id="plano_acao_item_responsavel" name="plano_acao_item_responsavel" value="'.($linha_plano_acao['plano_acao_responsavel'] ? $linha_plano_acao['plano_acao_responsavel'] : $Aplic->usuario_id).'" />';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pel'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Tod'.$config['genero_acao'].' '.$config['acao'].' deve ter um responsável. O '.$config['usuario'].' responsável pel'.$config['genero_acao'].' '.$config['acao'].' deverá, preferencialmente, ser o encarregado de atualizar os dados no '.$config['gpweb'].', relativos as su'.$config['genero_acao'].'s '.$config['acoes'].'.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om(($linha_plano_acao['plano_acao_responsavel'] ? $linha_plano_acao['plano_acao_responsavel'] : $Aplic->usuario_id), $Aplic->getPref('om_usuario')).'" style="width:234px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

if ($Aplic->profissional && $exibir['porcentagem_item']) {
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Progresso', 'O item d'.$config['genero_acao'].' '.$config['acao'].' pode ir de 0% (não iniciado) até 100% (completado).').'Progresso:'.dicaF().'</td><td nowrap="nowrap">'.selecionaVetor($percentual, 'plano_acao_item_percentagem', 'size="1" class="texto"').'% </td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Peso', 'O peso item d'.$config['genero_acao'].' '.$config['acao'].'. Será utilizado para o caso de cálculo do progresso geral d'.$config['genero_acao'].' '.$config['acao'].'.').'Peso:'.dicaF().'</td><td><input type="text" id="plano_acao_item_peso" name="plano_acao_item_peso" value="1" size="2" class="texto" /></td></tr>';
	}
else {
	echo '<input type="hidden" name="plano_acao_item_percentagem" id="plano_acao_item_percentagem" value="" />';
	echo '<input type="hidden" name="plano_acao_item_peso" id="plano_acao_item_peso" value="" />';
	}

echo '<tr><td align=right width="100" >'.dica('Tem Data de início', 'Marque esta caixa de seleção caso esta ação tem uma data de ínicio').'Tem início:</td><td><input type="checkbox" value="1" id="tem_inicio" name="tem_inicio" '.($linha_plano_acao['plano_acao_inicio'] ? 'checked="checked"' : '').' />'.dicaF().'<input type="hidden" id="oculto_data_inicio" name="oculto_data_inicio"  value="'.$data_inicio->format('%Y-%m-%d').'" /><input type="text" onchange="setData(\'env\', \'data_inicio\'); data_ajax();" class="texto" style="width:70px;" id="data_inicio" name="data_inicio" value="'.$data_inicio->format($df).'" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data de início deste evento.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário"" border=0 /></a>'.dicaF().dica('Hora do Início', 'Selecione na caixa de seleção a hora do ínicio d'.$config['genero_tarefa'].' '.$config['tarefa']). selecionaVetor($horas, 'inicio_hora', 'size="1" onchange="CompararDatas(); data_ajax();" class="texto"', $data_inicio->getHour()).' : '.dica('Minutos do Início', 'Selecione na caixa de seleção os minutos do ínicio d'.$config['genero_tarefa'].' '.$config['tarefa']).selecionaVetor($minutos, 'inicio_minuto', 'size="1" class="texto" onchange="CompararDatas(); data_ajax();"', $data_inicio->getMinute()).'</td></tr>';
echo '<tr><td align=right>'.dica('Tem Data de término', 'Marque esta caixa de seleção caso esta ação tem uma data de término').'Tem término:</td><td><input type="checkbox" value="1" id="tem_fim" name="tem_fim" '.($linha_plano_acao['plano_acao_fim'] ? 'checked="checked"' : '').' />'.dicaF().'<input type="hidden" id="oculto_data_fim" name="oculto_data_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" onchange="setData(\'env\', \'data_fim\'); horas_ajax();" class="texto" style="width:70px;" id="data_fim" name="data_fim" value="'.($data_fim ? $data_fim->format($df) : '').'" />'.dica('Data de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de término deste evento.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário"" border=0 /></a>'.dicaF().dica('Hora do Término', 'Selecione na caixa de seleção a hora do término.</p>Caso não saiba a hora provável de término, deixe em branco este campo e clique no botão <b>Data de Término</b>').selecionaVetor($horas, 'fim_hora', 'size="1" onchange="CompararDatas(); horas_ajax();" class="texto"', $data_fim ? $data_fim->getHour() : $fim).' : '.dica('Minutos do Término', 'Selecione na caixa de seleção os minutos do término. </p>Caso não saiba os minutos prováveis de término, deixe em branco este campo e clique no botão <b>Data de Término</b>').selecionaVetor($minutos, 'fim_minuto', 'size="1" class="texto" onchange="CompararDatas(); horas_ajax();"', $data_fim ? $data_fim->getMinute() : '00').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Duração', 'Selecionando o número de horas, ou dias, fará o sistema calcular a data provável de término.').'Duração esperada:'.dicaF().'</td><td nowrap="nowrap"><input type="text" onchange="data_ajax();" onkeypress="return somenteFloat(event)" class="texto" name="plano_acao_item_duracao" id="plano_acao_item_duracao" maxlength="30" size="2" value="0" />&nbsp;dias</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('O Que','O que será feito.').'O Que:'.dicaF().'</td><td><textarea rows="4" class="texto" name="plano_acao_item_oque" id="plano_acao_item_oque" style="width:600px"></textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Por que','Por que será feito.').'Por que:'.dicaF().'</td><td><textarea rows="4" name="plano_acao_item_porque" id="plano_acao_item_porque" style="width:600px"></textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Onde','Onde será feito.').'Onde:'.dicaF().'</td><td><textarea rows="4" name="plano_acao_item_onde" id="plano_acao_item_onde" style="width:600px"></textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Quando','Quando será feito.').'Quando:'.dicaF().'</td><td><textarea rows="4" name="plano_acao_item_quando" id="plano_acao_item_quando" style="width:600px"></textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Quem','Por quem será feito.').'Quem:'.dicaF().'</td><td><textarea rows="4" name="plano_acao_item_quem" id="plano_acao_item_quem" style="width:600px"></textarea></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Participantes', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Participantes:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios"><table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table></div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts"><table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table></div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Como','Como será feito.').'Como:'.dicaF().'</td><td><textarea rows="4" name="plano_acao_item_como" id="plano_acao_item_como" style="width:600px"></textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Quanto','Quanto custará fazer').'Quanto:'.dicaF().'</td><td><textarea rows="4" name="plano_acao_item_quanto" id="plano_acao_item_quanto" style="width:600px"></textarea></td></tr>';
echo '<tr><td></td><td><table cellpadding=0 cellspacing=0><tr><td>'.botao('estimado', 'Custo Estimado','Abrir uma janela onde poderá inserir ou editar o custo estimado.','','popEstimado()','','',0).'</td><td>&nbsp;&nbsp;&nbsp;</td><td>'.botao('gasto', 'Valores Gastos','Abrir uma janela onde poderá inserir ou editar os valores gastos.','','popGasto()','','',0).'</td></tr></table></td></tr>';

echo '</table></td><td align=left><table cellpadding=0 cellspacing=0 align=left>';


echo '<tr>';
echo '<td id="adicionar_acao" style="display:"><a href="javascript: void(0);" onclick="incluir_acao();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir a ação.').'</a></td>';
echo '<td id="confirmar_acao" style="display:none"><a href="javascript: void(0);" onclick="limpar();	document.getElementById(\'confirmar_acao\').style.display=\'none\';">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição da ação.').'</a><a href="javascript: void(0);" onclick="incluir_acao();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição da ação.').'</a></td>';
echo '</tr></table></td></tr></table></td></tr>';


echo '<tr><td colspan=20><div id="acoes">';



$sql->adTabela('plano_acao_item');
$sql->adOnde('plano_acao_item_acao = '.(int)$plano_acao_id);
$sql->adCampo('plano_acao_item.*');
$sql->adOrdem('plano_acao_item_ordem');
$acoes=$sql->ListaChave('plano_acao_item_id');
$sql->limpar();
$saida='';

if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'acao\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();
	}

if (count($acoes)) {
	echo '<table cellspacing=0 cellpadding=0 width=100%><tr><td></td><td><table cellspacing=0 cellpadding=0 class="tbl1" align=left width=100%><tr><th>&nbsp;</th><th>O Que</th><th>Por que</th><th>Onde</th><th>Quando</th><th>Quem</th><th>Como</th><th>Quanto</th>'.($Aplic->profissional && $exibir['porcentagem_item'] ? '<th>Peso</th><th>%</th>' : '').'<th>&nbsp;</th></tr>';
	foreach ($acoes as $plano_acao_item_id => $linha) {
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverPrimeiro\');">'.imagem('icones/2setacima.gif', 'Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição.').'</a>';
		echo '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverParaCima\');">'.imagem('icones/1setacima.gif', 'Posição Acima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover uma posição acima.').'</a>';
		echo '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverParaBaixo\');">'.imagem('icones/1setabaixo.gif', 'Posição Abaixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover uma posição abaixo.').'</a>';
		echo '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverUltimo\');">'.imagem('icones/2setabaixo.gif', 'Última Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição.').'</a>';
		echo '</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_oque'] ? $linha['plano_acao_item_oque'] : '&nbsp;').'</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_porque'] ? $linha['plano_acao_item_porque'] : '&nbsp;').'</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_onde'] ? $linha['plano_acao_item_onde'] : '&nbsp;').'</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.$linha['plano_acao_item_quando'];
			if ($linha['plano_acao_item_quando'] && ($linha['plano_acao_item_inicio'] || $linha['plano_acao_item_fim'])) echo '<br>';
			if ($linha['plano_acao_item_inicio']) echo retorna_data($linha['plano_acao_item_inicio']);
			if ($linha['plano_acao_item_inicio'] && $linha['plano_acao_item_fim']) echo '<br>';
			if ($linha['plano_acao_item_fim']) echo retorna_data($linha['plano_acao_item_fim']);
			if (!$linha['plano_acao_item_quando'] && !$linha['plano_acao_item_inicio'] && !$linha['plano_acao_item_fim']) echo '&nbsp;';
		echo '</td>';

		echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.$linha['plano_acao_item_quem'];

		$sql->adTabela('plano_acao_item_designados');
		$sql->adCampo('usuario_id');
		$sql->adOnde('plano_acao_item_id = '.$linha['plano_acao_item_id']);
		$participantes = $sql->carregarColuna();
		$sql->limpar();

		$saida_quem='';
		if ($participantes && count($participantes)) {
			$saida_quem.= link_usuario($participantes[0], '','','esquerda');
			$qnt_participantes=count($participantes);
			if ($qnt_participantes > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['plano_acao_item_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['plano_acao_item_id'].'"><br>'.$lista.'</span>';
				}
			}
		$sql->adTabela('plano_acao_item_depts');
		$sql->adCampo('dept_id');
		$sql->adOnde('plano_acao_item_id = '.$linha['plano_acao_item_id']);
		$depts = $sql->carregarColuna();
		$sql->limpar();

		$saida_dept='';
		if ($depts && count($depts)) {
			$saida_dept.= link_secao($depts[0]);
			$qnt_depts=count($depts);
			if ($qnt_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts[$i]).'<br>';
				$saida_dept.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamentos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'depts_'.$linha['plano_acao_item_id'].'\');">(+'.($qnt_depts - 1).')</a><span style="display: none" id="depts_'.$linha['plano_acao_item_id'].'"><br>'.$lista.'</span>';
				}
			}
		if ($saida_quem) echo ($linha['plano_acao_item_quem'] ? '<br>' : '').$saida_quem;
		if ($saida_dept) echo ($linha['plano_acao_item_quem'] || $saida_quem ? '<br>' : '').$saida_dept;
		if (!$saida_quem && !$linha['plano_acao_item_quem'] && !$saida_dept) echo '&nbsp;';
		echo '</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_como'] ? $linha['plano_acao_item_como'] : '&nbsp;').'</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.$linha['plano_acao_item_quanto'];
		$sql->adTabela('plano_acao_item_custos');
		$sql->adCampo('SUM(((plano_acao_item_custos_quantidade*plano_acao_item_custos_custo)*((100+plano_acao_item_custos_bdi)/100))) as total');
		$sql->adOnde('plano_acao_item_custos_plano_acao_item = '.$linha['plano_acao_item_id']);
		$custo = $sql->Resultado();
		$sql->limpar();
		if ($custo) echo ($linha['plano_acao_item_quanto']? '<br>' : '').'custo: '.$config['simbolo_moeda'].' '.number_format($custo, 2, ',', '.');
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adCampo('SUM(((plano_acao_item_gastos_quantidade*plano_acao_item_gastos_custo)*((100+plano_acao_item_gastos_bdi)/100))) as total');
		$sql->adOnde('plano_acao_item_gastos_plano_acao_item = '.$linha['plano_acao_item_id']);
		$gasto = $sql->Resultado();
		$sql->limpar();
		if ($gasto) echo ($linha['plano_acao_item_quanto'] || $custo ? '<br>' : '').'gasto: '.$config['simbolo_moeda'].' '.number_format($gasto, 2, ',', '.');
		if (!$linha['plano_acao_item_quanto']) echo '&nbsp;';
		echo '</td>';
		if ($Aplic->profissional && $exibir['porcentagem_item']){
			echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: right; vertical-align:text-top;">'.($linha['plano_acao_item_peso'] ? number_format($linha['plano_acao_item_peso'], 2, ',', '.') : '&nbsp;').'</td>';
			echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: right; vertical-align:text-top;">'.(int)$linha['plano_acao_item_percentagem'].'</td>';
			}

		echo '<td width=32><a href="javascript: void(0);" onclick="editar_acao('.$linha['plano_acao_item_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a ação.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este acao?\')) {excluir_acao('.$linha['plano_acao_item_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir a ação.').'</a></td>';
		echo '</tr>';
		}
	echo '</table></td></tr></table>';
	}










echo '</div></td></tr>';

echo '</table>';
echo '</td></tr></table>';

echo estiloFundoCaixa();
?>


<script language="javascript">

function somenteFloat(e){
	var tecla=new Number();
	if(window.event) tecla = e.keyCode;
	else if(e.which) tecla = e.which;
	else return true;
	if(((tecla < "48") && tecla !="44") || (tecla > "57")) return false;
	}	

function mudar_posicao_acao(plano_acao_item_ordem, plano_acao_item_id, direcao){
	xajax_mudar_posicao_acao_ajax(plano_acao_item_ordem, plano_acao_item_id, direcao, document.getElementById('plano_acao_id').value);
	__buildTooltip();
	}

function editar_acao(plano_acao_item_id){
	xajax_editar_acao(plano_acao_item_id);
	
	
	xajax_exibir_usuarios(document.getElementById('plano_acao_item_usuarios').value);
	xajax_exibir_depts(document.getElementById('plano_acao_item_depts').value);
	
	
	
	document.getElementById('adicionar_acao').style.display="none";
	document.getElementById('confirmar_acao').style.display="";
	__buildTooltip();
	}

function incluir_acao(){
	var inicio=document.getElementById('oculto_data_inicio').value+' '+document.getElementById('inicio_hora').value+':'+document.getElementById('inicio_minuto').value+':00';
	var fim=document.getElementById('oculto_data_fim').value+' '+document.getElementById('fim_hora').value+':'+document.getElementById('fim_minuto').value+':00';


	var inicio_plano=document.getElementById('plano_acao_inicio').value;
	var fim_plano=document.getElementById('plano_acao_fim').value;

	if (document.getElementById('plano_acao_item_nome').value.length<1){
		alert('De um nome para para o item da ação.');
		document.getElementById('plano_acao_item_nome').focus();
		}
	else if(
		document.getElementById('plano_acao_item_oque').value.length<1 &&
		document.getElementById('plano_acao_item_porque').value.length<1 &&
		document.getElementById('plano_acao_item_onde').value.length<1 &&
		document.getElementById('plano_acao_item_quando').value.length<1 &&
		document.getElementById('plano_acao_item_quem').value.length<1 &&
		document.getElementById('plano_acao_item_como').value.length<1 &&
		document.getElementById('plano_acao_item_quanto').value.length<1
		){
		alert('Preencha ao menos alguma informação sobre a ação.');
		document.getElementById('plano_acao_item_oque').focus();
		}
	else {
		xajax_incluir_acao_ajax(
		document.getElementById('plano_acao_id').value,
		document.getElementById('plano_acao_item_id').value,
		document.getElementById('uuid').value,
		document.getElementById('plano_acao_item_responsavel').value,
		document.getElementById('plano_acao_item_cia').value,
		document.getElementById('plano_acao_item_nome').value,
		document.getElementById('plano_acao_item_quando').value,
		document.getElementById('plano_acao_item_oque').value,
		document.getElementById('plano_acao_item_como').value,
		document.getElementById('plano_acao_item_onde').value,
		document.getElementById('plano_acao_item_quanto').value,
		document.getElementById('plano_acao_item_porque').value,
		document.getElementById('plano_acao_item_quem').value,
		inicio,
		fim,
		document.getElementById('plano_acao_item_duracao').value,
		document.getElementById('tem_inicio').checked,
		document.getElementById('tem_fim').checked,
		document.getElementById('plano_acao_item_usuarios').value,
		document.getElementById('plano_acao_item_depts').value,

		document.getElementById('plano_acao_item_percentagem').value,
		document.getElementById('plano_acao_item_peso').value,

		document.getElementById('plano_acao_calculo_porcentagem').value,
		document.getElementById('exibir_porcentagem_item').value
		);
		limpar();
		__buildTooltip();
		}
	}

function excluir_acao(plano_acao_item_id){
	xajax_excluir_acao_ajax(plano_acao_item_id, document.getElementById('plano_acao_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function limpar(){
	document.getElementById('plano_acao_item_id').value=null;
	document.getElementById('plano_acao_item_nome').value='';
	document.getElementById('plano_acao_item_quando').value='';
	document.getElementById('plano_acao_item_oque').value='';
	document.getElementById('plano_acao_item_como').value='';
	document.getElementById('plano_acao_item_onde').value='';
	document.getElementById('plano_acao_item_quanto').value='';
	document.getElementById('plano_acao_item_porque').value='';
	document.getElementById('plano_acao_item_quem').value='';
	document.getElementById('plano_acao_item_usuarios').value='';
	document.getElementById('plano_acao_item_depts').value='';
	
	
	document.getElementById('combo_depts').innerHTML='<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	document.getElementById('combo_usuarios').innerHTML='<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	
	
	
	document.getElementById('adicionar_acao').style.display='';
	document.getElementById('confirmar_acao').style.display='none';
	
	}


function popResponsavel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('plano_acao_item_cia').value+'&usuario_id='+document.getElementById('plano_acao_item_responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('plano_acao_item_cia').value+'&usuario_id='+document.getElementById('plano_acao_item_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('plano_acao_item_responsavel').value=usuario_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('plano_acao_item_cia').value,'plano_acao_item_cia','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}



function popEstimado() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estimado', 1024, 768, 'm=praticas&a=estimado_pro&dialogo=1&uuid='+document.getElementById('uuid').value+'&plano_acao_item_id='+document.getElementById('plano_acao_item_id').value+'&plano_acao_id='+document.getElementById('plano_acao_id').value, null, window);
	else window.open('./index.php?m=praticas&a=estimado&dialogo=1&uuid='+document.getElementById('uuid').value+'&id='+document.getElementById('plano_acao_item_id').value, 'Estimado','height=500,width=800,resizable,scrollbars=yes');
	}

function popGasto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Gasto', 1024, 768, 'm=praticas&a=gasto_pro&dialogo=1&uuid='+document.getElementById('uuid').value+'&plano_acao_item_id='+document.getElementById('plano_acao_item_id').value+'&plano_acao_id='+document.getElementById('plano_acao_id').value, null, window);
	else window.open('./index.php?m=praticas&a=gasto&dialogo=1&uuid='+document.getElementById('uuid').value+'&id='+document.getElementById('plano_acao_item_id').value, 'Gasto','height=500,width=800,resizable,scrollbars=yes');
	}

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Participantes', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('plano_acao_item_cia').value+'&usuarios_id_selecionados='+document.getElementById('plano_acao_item_usuarios').value, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('plano_acao_item_cia').value+'&usuarios_id_selecionados='+document.getElementById('plano_acao_item_usuarios').value, 'participantes','height=500,width=500,resizable,scrollbars=yes');
	}


function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.plano_acao_item_usuarios.value = usuario_id_string;
	xajax_exibir_usuarios(usuario_id_string);
	__buildTooltip();
	}

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('plano_acao_item_cia').value+'&depts_id_selecionados='+document.getElementById('plano_acao_item_depts').value, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('plano_acao_item_cia').value+'&depts_id_selecionados='+document.getElementById('plano_acao_item_depts').value, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.plano_acao_item_depts.value = departamento_id_string;
	xajax_exibir_depts(departamento_id_string);
	__buildTooltip();
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}


var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "oculto_data_inicio",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal1) {
	  var date = cal1.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("oculto_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    data_ajax();
	    }
		cal1.hide();
		}
	});

var cal2 = Calendario.setup({
	trigger : "f_btn2",
  inputField : "oculto_data_fim",
	date : <?php echo $data_fim->format("%Y-%m-%d")?>,
	selection : <?php echo $data_fim->format("%Y-%m-%d")?>,
  onSelect : function(cal2) {
	  var date = cal2.selection.get();
	  if (date){
	    date = Calendario.intToDate(date);
	    document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("oculto_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    horas_ajax();
	    }
		cal2.hide();
		}
	});


function CompararDatas(){
  var str1 = document.getElementById("oculto_data_inicio").value


  var dia1  = parseInt(str1.substring(8,10),10);
  var mes1 = parseInt(str1.substring(5,7),10);
  var ano1  = parseInt(str1.substring(0,4),10);
  var hora1  = parseInt(document.getElementById("inicio_hora").value,10);
	var minuto1  = parseInt(document.getElementById("inicio_minuto").value,10);
	var data1 = new Date(ano1, mes1, dia1, hora1, minuto1);

	var str2 = document.getElementById("oculto_data_fim").value
  var dia2  = parseInt(str2.substring(8,10),10);
  var mes2 = parseInt(str2.substring(5,7),10);
  var ano2  = parseInt(str2.substring(0,4),10);
  var hora2  = parseInt(document.getElementById("fim_hora").value,10);
	var minuto2  = parseInt(document.getElementById("fim_minuto").value,10);
	var data2 = new Date(ano2, mes2, dia2, hora2, minuto2);


  if(data2 < data1){
    document.getElementById("data_fim").value=document.getElementById("data_inicio").value;
    document.getElementById("oculto_data_fim").value=document.getElementById("oculto_data_inicio").value;
    document.getElementById("fim_minuto").value=document.getElementById("inicio_minuto").value;
    document.getElementById("fim_hora").value=document.getElementById("inicio_hora").value;
  	}

	}


function setData(frm_nome, f_data) {
	campo_data = eval( 'document.'+frm_nome+'.'+f_data );
	campo_data_real = eval( 'document.'+frm_nome+'.'+'oculto_'+f_data );
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
      //data final fazer ao menos no mesmo dia da inicial
      CompararDatas();
			}
		}
	else campo_data_real.value = '';
	}

function horas_ajax(){
	var f=document.env;
	var inicio =f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minuto.value+':00';
	var fim=f.oculto_data_fim.value+' '+f.fim_hora.value+':'+f.fim_minuto.value+':00';
	xajax_calcular_duracao(inicio, fim, document.getElementById('plano_acao_item_cia').value);
	}


function data_ajax(){
	var f=document.env;
	var inicio=f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minuto.value+':00';
	var horas=f.plano_acao_item_duracao.value;
	xajax_data_final_periodo(inicio, horas, document.getElementById('plano_acao_item_cia').value);
	}

</script>