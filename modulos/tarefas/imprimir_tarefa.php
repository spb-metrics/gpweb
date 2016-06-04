<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $config;
echo '<html><head><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css"></head><body>';

$tipo = getParam($_REQUEST, 'tipo', 0);
$tarefa_id = getParam($_REQUEST, 'tarefa_id', 0);
$baseline_id = getParam($_REQUEST, 'baseline_id', 0);

if ($Aplic->profissional) {
	$barra=codigo_barra('tarefa', $tarefa_id);
	if ($barra['cabecalho']) echo $barra['imagem'];
	}

$obj = new CTarefa(($baseline_id ? true : false), true);
$obj->load($tarefa_id);
$listas_tarefas=($obj->tarefas_subordinadas ? $obj->tarefas_subordinadas : $tarefa_id);

if (!$podeAcessar || !$obj->podeAcessar()) $Aplic->redirecionar('m=publico&a=acesso_negado');

$q = new BDConsulta;

$prioridades = getSisValor('PrioridadeTarefa');
$tipos = getSisValor('TipoTarefa');
include_once ($Aplic->getClasseModulo('tarefas'));
global $tarefa_acesso;
$extra = array(0 => '(nenhum)', 1 => 'Marco', 2 => ucfirst($config['tarefa']).' Dinâmic'.$config['genero_tarefa'], 3 => ucfirst($config['tarefa']).' Inativ'.$config['genero_tarefa']);




$numero=0;
$tarefas[][]=array();
$usuarios=array();
$nomend=array(0 => '');
$nomend+= getSisValorND();
$lista_tarefas=array();
$unidade= getSisValor('TipoUnidade');
$departamentos=array();
$tarefas_dep[][]=array();
$df = '%d/%m/%Y';
$data_inicio = intval($obj->tarefa_inicio) ? new CData($obj->tarefa_inicio) : null;
$data_fim = intval($obj->tarefa_fim) ? new CData($obj->tarefa_fim) : null;
$hoje = new CData();
$estilo = (($obj->tarefa_percentagem < 99.99 && $hoje > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';


$q->adTabela('tarefa_designados', 't');
$q->esqUnir('usuarios', 'u','t.usuario_id = u.usuario_id');
$q->esqUnir('contatos', 'c', 'usuario_contato = contato_id');
$q->adCampo('u.usuario_id, u.usuario_login, contato_email, perc_designado');
$q->adOnde('t.tarefa_id = '.(int)$tarefa_id);
$q->adOrdem('u.usuario_login');
$usuarios = $q->Lista();
$q->limpar();

$tipoDuracao = getSisValor('TipoDuracaoTarefa');

$projeto = null;
$horas_trabalhadas = (config('horas_trab_diario') ? config('horas_trab_diario') : 8);
$q->adTabela('projetos');
$q->esqUnir('tarefas', 't1', 'projetos.projeto_id = t1.tarefa_projeto');
$q->esqUnir('cias', 'com', 'cia_id = projeto_cia');
$q->esqUnir('usuarios', 'u', 'usuario_id = projeto_responsavel');
$q->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
$q->adCampo('cia_nome, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' usuario_nome,projeto_data_fim, projeto_data_inicio, projeto_nome, projeto_cor, projeto_descricao');
$q->adCampo('projeto_percentagem');
$q->adOnde('projeto_id = '.$obj->tarefa_projeto. ' AND t1.tarefa_id = t1.tarefa_superior');
$q->adOnde('projeto_id = '.(int)$obj->tarefa_projeto);
$q->carregarObjeto($projeto);
$q->limpar();


echo '<table border="0" cellpadding="0" cellspacing="0" style="max-width: 21.001cm;overflow:hidden;">';

$sql = new BDConsulta;
$sql->adTabela('projetos');
$sql->esqUnir('cias','cias','cias.cia_id=projeto_cia');
$sql->esqUnir('municipios','municipios','municipio_id=cia_cidade');
$sql->adCampo('cia_cabacalho, projeto_responsavel, projeto_supervisor, projeto_nome, municipio_nome AS cia_cidade');
$sql->adOnde('projeto_id='.$obj->tarefa_projeto);
$dados_projeto=$sql->Linha();
$sql->limpar();

echo '<tr><td align="center"><img src="'.$Aplic->gpweb_brasao.'"/></td></tr>'; 
echo '<tr><td align="center"><font size=2>'.$dados_projeto['cia_cabacalho'].'</font></td></tr>'; 
echo '<tr><td style="height:10px;">&nbsp;</td><tr>';
echo '<tr><td align="center"><h1>PROJETO '.$projeto->projeto_nome.'<br>TAREFA '.$obj->tarefa_nome.'</h1></td></tr>';
echo '<tr><td colspan=20 style="height:30px;">&nbsp;</td></tr>';

if ($obj->tarefa_dono) {
	echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. RESPONSÁVEL PELA TAREFA</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.nome_funcao('','','','',$obj->tarefa_dono).'</font></td></tr>';
	echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';
	}
if ($obj->tarefa_descricao){
	echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. DESCRIÇÃO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$obj->tarefa_descricao.'</font></td></tr>';
	echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';
	}
if ($obj->tarefa_inicio){
	echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. DATA DE INÍCIO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$data_inicio->format($df).'</font></td></tr>';
	echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';
	}
if ($obj->tarefa_fim){
	echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. DATA DE TÉRMINO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$data_fim->format($df).'</font></td></tr>';
	echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';
	}
if ($obj->tarefa_duracao){
	echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. Duração</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$obj->tarefa_duracao.' '.$tipoDuracao[$obj->tarefa_duracao_tipo].'</font></td></tr>';
	echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';
	}
echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. PROGRESSO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>';printf("%.1f%%", $obj->tarefa_percentagem); echo '</font></td></tr>';
echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';
if ($obj->tarefa_url_relacionada) {
	echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. ENDEREÇO URL</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$obj->tarefa_url_relacionada.'</font></td></tr>';
	echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';
	}
$custo_estimado=$obj->custo_estimado();
$gasto_efetuado=$obj->gasto_efetuado();
$gasto_registro=$obj->gasto_registro();
if ($custo_estimado !='0.00') {
	echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. CUSTO ESTIMADO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$config['simbolo_moeda'].' '.$custo_estimado.'</font></td></tr>';
	echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';
	}
if ($gasto_efetuado !='0.00') {
	echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. CUSTO EFETIVO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$config['simbolo_moeda'].' '.$gasto_efetuado.'</font></td></tr>';
	echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';
	}
if ($gasto_registro !='0.00') {
	echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. GASTOS EXTRAS</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$config['simbolo_moeda'].' '.$gasto_registro.'</font></td></tr>';
	echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';
	}


if (count($usuarios)>1) echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. DESIGNADOS</b></font>';
else echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. DESIGNADO</b></font>';
if (count($usuarios)) foreach ($usuarios as $linha) echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.nome_funcao('','','','',$linha['usuario_id']).' '.$linha['perc_designado'].'%</font>';
else 	echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Ninguem foi designado para est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'</font>';
echo '</td></tr>';
echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';


$q->adCampo('td.dependencias_req_tarefa_id, t.tarefa_nome');
$q->adTabela('tarefas', 't');
$q->adTabela('tarefa_dependencias', 'td');
$q->adOnde('td.dependencias_req_tarefa_id = t.tarefa_id');
$q->adOnde('td.dependencias_tarefa_id = '.(int)$tarefa_id);
$tarefaDep = $q->Lista();
$q->limpar();

if (count($tarefaDep)>1) echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. DEPENDÊNCIAS</b></font>';
else echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. DEPENDÊNCIA</b></font><br>';
if (count($tarefaDep)) foreach ($tarefaDep as $linha) echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$linha['tarefa_nome'].'</font>';
else 	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Nenhuma predecessora para est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'</font>';
echo '</td></tr>';
echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';

$q->adCampo('td.dependencias_tarefa_id, t.tarefa_nome');
$q->adTabela('tarefas', 't');
$q->adTabela('tarefa_dependencias', 'td');
$q->adOnde('td.dependencias_tarefa_id = t.tarefa_id');
$q->adOnde('td.dependencias_req_tarefa_id = '.(int)$tarefa_id);
$dependingTarefas = $q->Lista();
$q->limpar();

if (count($dependingTarefas)>1) echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. DEPENDÊNTES</b></font>';
else echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. DEPENDÊNTE</b></font>';
if (count($dependingTarefas)) foreach ($dependingTarefas as $linha) echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$linha['tarefa_nome'].'</font>';
else 	echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' depende dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').'</font>';
echo '</td></tr>';
echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';


$nd=array();
$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
$RefRegistroTarefaImagem = getSisValor('RefRegistroTarefaImagem');
$projeto = new CProjeto;
$q = new BDConsulta;
$q->adTabela('tarefa_log');
$q->adCampo('tarefa_log.*, tarefa_nome, usuario_login, tarefa_id');
//$q->adCampo('contato_id');
$q->adUnir('usuarios', 'u', 'usuario_id = tarefa_log_criador');
$q->adUnir('tarefas', 't', 'tarefa_log_tarefa = t.tarefa_id');
$q->adUnir('contatos', 'ct', 'contato_id = usuario_contato');
$q->adOnde('tarefa_log_tarefa IN ('.$listas_tarefas.')');
$q->adOrdem('tarefa_log_data');
$logs = $q->Lista();
$hrs = 0;
$qnt=0;
$custo=array();
if (count($logs)){
	echo '<tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. REGISTROS DAS TAREFAS</b></font></td></tr>';
	echo '<tr><td align="left" nowrap="nowrap"><table border=0 cellpadding="2" cellspacing=0 class="tbl1">';
	echo '<tr><th>Data</th><th>Tarefa</th><th>Ref.</th><th>Título</a></th><th>Responsável</th><th>Horas</th><th>Comentários</th><th>ND</th><th>Custos</th></tr>';
	foreach ($logs as $linha) {
		$qnt++;
		$tarefa_log_data = intval($linha['tarefa_log_data']) ? new CData($linha['tarefa_log_data']) : null;
		$estilo = $linha['tarefa_log_problema'] ? 'background-color:#cc6666;color:#ffffff' : '';
		echo '<tr bgcolor="white" valign="top">';
		echo '<td nowrap="nowrap">'.($tarefa_log_data ? $tarefa_log_data->format($df) : '&nbsp;').'</td>';
		echo '<td style="white-space: pre-wrap; word-break: all; -ms-word-break: break-all;-ms-word-break: break-all;">'.nome_tarefa($linha['tarefa_id']).'</td>';
		$imagem_referencia = '-';
		if ($linha['tarefa_log_referencia'] > 0) {
			if (isset($RefRegistroTarefaImagem[$linha['tarefa_log_referencia']])) $imagem_referencia = imagem('icones/'.$RefRegistroTarefaImagem[$linha['tarefa_log_referencia']], imagem('icones/'.$RefRegistroTarefaImagem[$linha['tarefa_log_referencia']]).' '.$RefRegistroTarefa[$linha['tarefa_log_referencia']], 'Forma pela qual foram obtidos os dados para efetuar este registro de trabalho.');
			elseif (isset($RefRegistroTarefa[$linha['tarefa_log_referencia']])) $imagem_referencia = $RefRegistroTarefa[$linha['tarefa_log_referencia']];
			}
		echo '<td align="center" valign="middle">'.$imagem_referencia.'</td>';
		echo '<td style="white-space: pre-wrap; word-break: all; -ms-word-break: break-all;-ms-word-break: break-all;'.$estilo.'">'.$linha['tarefa_log_nome'].'</td>';
		echo '<td nowrap="nowrap">'.nome_funcao('','','','',$linha['tarefa_log_criador']).'</td>';
		echo '<td align="right">'.sprintf('%.2f', $linha['tarefa_log_horas']).'</td>';
		echo '<td style="white-space: pre-wrap; word-break: all; -ms-word-break: break-all;-ms-word-break: break-all;">'.str_replace("\n", '<br />', $linha['tarefa_log_descricao']).'</td>';
		$nd=($linha['tarefa_log_categoria_economica'] && $linha['tarefa_log_grupo_despesa'] && $linha['tarefa_log_modalidade_aplicacao'] ? $linha['tarefa_log_categoria_economica'].'.'.$linha['tarefa_log_grupo_despesa'].'.'.$linha['tarefa_log_modalidade_aplicacao'].'.' : '').$linha['tarefa_log_nd'];
		echo '<td align="center" valign="middle">'.($linha['tarefa_log_custo']!=0 ? dica('Natureza da Despesa', (isset($nomend[$linha['tarefa_log_nd']]) ? $nomend[$linha['tarefa_log_nd']] : '')).$linha['tarefa_log_nd'].dicaF(): '&nbsp;').'</td>';
		echo '<td align="right">'.number_format($linha['tarefa_log_custo'], 2, ',', '.').'</td>';
		echo '</tr>';
		$hrs += (float)$linha['tarefa_log_horas'];
		if (isset($custo[$linha['tarefa_log_nd']])) $custo[$linha['tarefa_log_nd']] += (float)$linha['tarefa_log_custo'];
		else $custo[$linha['tarefa_log_nd']] = (float)$linha['tarefa_log_custo'];
		}
	if (!$qnt) $s = '<tr><td bgcolor="white">Nenhum registro de '.$config['genero_tarefa'].' encontrado.</td></tr></table></td></tr>';	
	else {
		echo '<tr bgcolor="white" valign="top">';
		echo '<td colspan="5" align="right" valign="middle"><b>Total de Horas:</b></td>';
		$minutos = (int)(($hrs - ((int)$hrs)) * 60);
		$minutos = ((strlen($minutos) == 1) ? ('0'.$minutos) : $minutos);
		echo '<td align="right" valign="middle"><b>'.(int)$hrs.':'.$minutos.'</b></td>';
		echo '<td align="right" colspan="2"><b>Custos</b>';
		foreach ($custo as $nd => $somatorio) {
			if ($somatorio > 0) echo '<br>'.$nd;
			}
		echo '<br><b>Total Geral</b>';
		echo'</td>';
		echo '<td align="right">';
		$somatorio_total=0;
		foreach ($custo as $nd => $somatorio) {
			if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
			$somatorio_total+=$somatorio;
			}
		 echo '<br><b>'.number_format($somatorio_total, 2, ',', '.').'</b></td>';	
		echo '</tr>';
		echo '</table></td></tr><tr><td><table><tr><td>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Legenda</td><td>&nbsp; &nbsp;</td><td bgcolor="#ffffff" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>Registro Normal&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td bgcolor="#cc6666" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>Registro de Problema</td></tr></table></td></tr>';
		}
	}

echo '<tr><td colspan=20 style="height:20px;">&nbsp;</td></tr>';

$objProjeto = new CProjeto();
$objProjeto->load($obj->tarefa_projeto);
$nd=array();
if ($custo_estimado !='0.00'){
	echo '<tr><td><table><tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. CUSTOS PLANEJADOS</b></font></td></tr>';
	echo '<tr><td>&nbsp;</td></tr>';
	echo '<tr><td align="left" nowrap="nowrap">';
		$q->adTabela('tarefa_custos', 't');
		$q->adCampo('t.*, ((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS valor ');
		$q->adOnde('t.tarefa_custos_tarefa IN ('.$listas_tarefas.')');
		if ($Aplic->profissional && $config['aprova_custo']) $q->adOnde('tarefa_custos_aprovado = 1');
		$q->adOrdem('tarefa_custos_tarefa, tarefa_custos_ordem');	
	$linhas= $q->Lista();
	$qnt=0;
	echo '<table align="center" border=0 cellpadding="2" cellspacing=0 class="tbl1">';
	echo '<tr><th>Nome</th><th>Descrição</th><th>Unidade</th><th width="40">Qnt.</th><th>Valor ('.$config['simbolo_moeda'].')</th><th>ND</th><th width="100">Total ('.$config['simbolo_moeda'].')</th><th>Responsável</th></tr>';
	$total=0;
	$custo=array();
	$tarefa=0;
	foreach ($linhas as $dado) {
		echo '<tr align="center"><td align="left">'.(++$qnt).' - '.$dado['tarefa_custos_nome'].'</td><td align="left">'.$dado['tarefa_custos_descricao'].'</td><td>'.(isset($unidade[$dado['tarefa_custos_tipo']]) ? $unidade[$dado['tarefa_custos_tipo']] : '&nbsp;').'</td><td>'.$dado['tarefa_custos_quantidade'].'</td><td align="right">'.number_format($dado['tarefa_custos_custo'], 2, ',', '.').'</td><td>'.dica('Natureza da Despesa', (isset($nomend[$dado['tarefa_custos_nd']]) ? $nomend[$dado['tarefa_custos_nd']] : '')).$dado['tarefa_custos_nd'].'</td><td align="right">'.number_format($dado['valor'], 2, ',', '.').'</td><td align="left">'.nome_funcao('','','','',$dado['tarefa_custos_usuario']).'</td><tr>';
		if (isset($custo[$dado['tarefa_custos_nd']])) $custo[$dado['tarefa_custos_nd']] += (float)$dado['valor'];	
		else $custo[$dado['tarefa_custos_nd']] = (float)$dado['valor'];	
		$total+=$dado['valor'];
		}
	if ($qnt) {
		if ($total) {
			echo '<tr><td colspan="6" class="std" align="right">';

			foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) {
				echo '<br>'.(isset($nomend[$indice_nd]) ? $nomend[$indice_nd] : 'Sem ND');
				}
			echo '<br><b>Total</td><td align="right">';	
			foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
			echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="2">&nbsp;</td></tr>';	
			}	
		}
	else echo '<tr><td colspan="8" class="std" align="left"><b>Nenhum item encontrado.</b></td></tr>';	
	echo '</table></td></tr></table></td></tr>';
	}
	
$nd=array();	
if ($gasto_efetuado !='0.00'){
	echo '<tr><td><table><tr><td align="left" nowrap="nowrap"><font size=3><b>'.++$numero.'. CUSTOS EFETIVADOS</b></font></td></tr>';
	echo '<tr><td>&nbsp;</td></tr>';
	echo '<tr><td align="left" nowrap="nowrap">';
	$q->adTabela('tarefa_gastos', 't');
	$q->adCampo('t.*, ((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor ');
	$q->adOnde('t.tarefa_gastos_tarefa IN ('.$listas_tarefas.')');
	$q->adOrdem('tarefa_gastos_tarefa, tarefa_gastos_ordem');
	if ($Aplic->profissional && $config['aprova_gasto']) $q->adOnde('tarefa_gastos_aprovado = 1');
	$linhas= $q->Lista();
	$qnt=0;
	echo '<table align="center"  border=0 cellpadding="2" cellspacing=0 class="tbl1">';
	echo '<tr><th>Nome</th><th>Descrição</th><th>Unidade</th><th width="40">Qnt.</th><th>Valor ('.$config['simbolo_moeda'].')</th><th>ND</th><th width="100">Total ('.$config['simbolo_moeda'].')</th><th>Responsável</th></tr>';
	$total=0;
	$custo=array();
	$tarefa=0;
	foreach ($linhas as $dado) {
		echo '<tr align="center"><td align="left">'.++$qnt.' - '.$dado['tarefa_gastos_nome'].'</td><td align="left">'.$dado['tarefa_gastos_descricao'].'</td><td>'.$unidade[$dado['tarefa_gastos_tipo']].'</td><td>'.$dado['tarefa_gastos_quantidade'].'</td><td align="right">'.number_format($dado['tarefa_gastos_custo'], 2, ',', '.').'</td><td>'.dica('Natureza da Despesa', (isset($nomend[$dado['tarefa_gastos_nd']]) ? $nomend[$dado['tarefa_gastos_nd']] : '&nbsp;')).$dado['tarefa_gastos_nd'].'</td><td align="right">'.number_format($dado['valor'], 2, ',', '.').'</td><td align="left">'.nome_funcao('','','','',$dado['tarefa_gastos_usuario']).'</td><tr>';
		if (isset($custo[$dado['tarefa_gastos_nd']])) $custo[$dado['tarefa_gastos_nd']] += (float)$dado['valor'];	
		else $custo[$dado['tarefa_gastos_nd']] = (float)$dado['valor'];	
		$total+=$dado['valor'];
		} 

	if ($qnt) {
		if ($total) {
			echo '<tr><td colspan="6" class="std" align="right">';
			foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.(isset($nomend[$indice_nd]) && $nomend[$indice_nd] ? $nomend[$indice_nd] : 'Sem ND');
			echo '<br><b>Total</td><td align="right">';	
			foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
			echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="2">&nbsp;</td></tr>';	
			}	
		}
	else echo '<tr><td colspan="8" class="std" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Nenhum item encontrado.</b></td></tr>';	
	echo '</table></td></tr></table></td></tr>';
	}



echo '<tr><td align="left"><font size=3><b>'.++$numero.'. GRÁFICO GANTT DO PROJETO</b></font></td></tr>';
$src = "?m=tarefas&a=gantt&sem_cabecalho=1&mostrarLegendas=1&proFiltro=&mostrarInativo=1mostrarTodoGantt=1&projeto_id=".$obj->tarefa_projeto."&tarefa_id=".$tarefa_id."&width=1060";
echo '<tr><td align="left"><img src="'.$src.'" alt=""></td></tr>';



//assinatura
$sql->adTabela('projetos');
$sql->esqUnir('usuarios','u2','u2.usuario_id=projetos.projeto_responsavel');
$sql->esqUnir('contatos','c2','c2.contato_id=u2.usuario_contato');
$sql->adCampo('concatenar_tres(c2.contato_posto, \' \', c2.contato_nomeguerra) AS nome_gerente, contato_nomecompleto, contato_posto');
$sql->adOnde('projeto_id='.$obj->tarefa_projeto);
$linha=$sql->Linha();
$sql->limpar();
	echo '<tr><td style="height:30px;">&nbsp;</td><tr>';
$data = new CData();
	
echo '<tr><td colspan=2 align="center"><font size=2>'.($dados_projeto['cia_cidade'] ? $dados_projeto['cia_cidade'].', ' : '').retorna_data_extenso($data->format(FMT_DATA_MYSQL)).'</font></td></tr>'; 
if ($linha['nome_gerente']){
	echo '<tr><td colspan=2 style="height:50px;">&nbsp;</td></tr>';
	echo '<tr><td colspan=2 align="center">__________________________________________</td></tr>'; 
	echo '<tr><td colspan=2 align="center"><font size=2>'.($linha['contato_posto'] ? $linha['contato_posto'].' ' :'').($linha['contato_nomecompleto'] ? $linha['contato_nomecompleto'] : $linha['nome_gerente']).'</font></td></tr>';
	echo '<tr><td colspan=2 align="center"><font size=2>'.ucfirst($config['gerente']).' d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).'</font></td></tr>';
	}	


if (!$Aplic->profissional && $config['barra_projeto']){
	echo '<tr><td colspan=20><table><tr><td width=12></td><td><script>document.write(\'<img src="'.BASE_URL.'/lib/barras/barcode.php?quality=75&barcode=T'.$tarefa_id.'\">\')</script></td></tr></table></td></tr>';
	}

if ($Aplic->profissional && $barra['rodape']) echo $barra['imagem'];

echo '</table>';
?>
<script language="javascript">
//self.print();
</script>