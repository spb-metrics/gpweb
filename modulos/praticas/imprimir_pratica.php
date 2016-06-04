<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\imprimir_pratica.php		

Exibir pagina web com os dados da prática de gestão selecionada para impressão																																									
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $m, $a;

$pratica_id = intval(getParam($_REQUEST, 'pratica_id', 0));
$sql = new BDConsulta;

$sql = new BDConsulta();
$sql->adTabela('pratica_requisito');
$sql->adCampo('DISTINCT ano');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);


$ano = ($Aplic->getEstado('IdxPraticaAno') !== null && isset($anos[$Aplic->getEstado('IdxPraticaAno')])? $Aplic->getEstado('IdxPraticaAno') : $ultimo_ano);


echo '<html><head><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css"></head><body>';

$numero=0; 


$sql->adTabela('praticas');
$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->esqUnir('cias', 'cias', 'praticas.pratica_cia=cias.cia_id');
$sql->adCampo('*');
$sql->adOnde('praticas.pratica_id='.(int)$pratica_id);
if ($ano) $sql->adOnde('pratica_requisito.ano='.(int)$ano);
$pratica=$sql->Linha();
$sql->limpar();
echo '<table width="800" cellpadding=0 cellspacing="18">';
echo '<tr><td align="center"><h1>'.$pratica['cia_cabacalho'].'<br>'.strtoupper($config['pratica']).'<br><BR>'.$pratica['pratica_nome'].'</h1></td></tr><tr><td>';
if ($pratica['pratica_responsavel']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. GERENTE D'.strtoupper($config['genero_pratica']).' '.strtoupper($config['pratica']).'</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.nome_funcao('','','','',$pratica['pratica_responsavel']).'</font></td></tr>';
if ($pratica['pratica_descricao']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. DESCRIÇÃO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica['pratica_descricao'].'</font></td></tr>';
if ($pratica['pratica_oque']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. O QUE</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica['pratica_oque'].'</font></td></tr>';
if ($pratica['pratica_porque']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. POR QUE</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica['pratica_porque'].'</font></td></tr>';
if ($pratica['pratica_onde']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. ONDE</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica['pratica_onde'].'</font></td></tr>';
if ($pratica['pratica_quando']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. QUANDO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica['pratica_quando'].'</font></td></tr>';
if ($pratica['pratica_como']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. COMO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica['pratica_como'].'</font></td></tr>';
if ($pratica['pratica_quanto']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. QUANTO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica['pratica_quanto'].'</font></td></tr>';

$sql->adTabela('pratica_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('pratica_id = '.(int)$pratica_id);
$participantes = $sql->Lista();
$saida_quem='';
if ($participantes && count($participantes)) {
		$qnt_participantes=count($participantes);
		$lista='';
		for ($i = 0, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) {
			$saida_quem.= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.nome_funcao('','','','',$participantes[$i]['usuario_id']);	
			}	
		} 
if ($saida_quem || $pratica['pratica_quem']) echo '<tr><td align="left" style="margin-bottom:0cm; margin-top:0cm;"><font size=3><b>'.++$numero.'. QUEM</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.($pratica['pratica_quem'] ? $pratica['pratica_quem'] :'').'</font>'.$saida_quem.'</font></td></tr>';

$sql->adTabela('pratica_depts');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = pratica_depts.dept_id');
$sql->adCampo('dept_nome');
$sql->adOnde('pratica_id = '.(int)$pratica_id);
$departamentos = $sql->Lista();
$sql->limpar();
$saida_depts='';
foreach($departamentos as $dept) $saida_depts.= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$dept['dept_nome'];	
if ($saida_depts) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. '.strtoupper($config['departamento']).'</b></font><font size=2>'.$saida_depts.'</font></td></tr>';

if ($pratica['pratica_arte'] && $pratica['pratica_justificativa_arte']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. ESTADO DE ARTE</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica['pratica_justificativa_arte'].'</font></td></tr>';
if ($pratica['pratica_justificativa_inovacao'] && $pratica['pratica_inovacao']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. '.strtoupper($config['pratica']).' INOVADOR'.strtoupper($config['genero_pratica']).'</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica['pratica_justificativa_inovacao'].'</font></td></tr>';

if ($pratica['pratica_controlada'] || $pratica['pratica_proativa'] || $pratica['pratica_abrange_pertinentes'] || $pratica['pratica_continuada'] || $pratica['pratica_coerente'] || $pratica['pratica_interrelacionada'] || $pratica['pratica_cooperacao']){
	$qnt=0;
	$pontos_fortes='';
	if ($pratica['pratica_controlada']) $pontos_fortes.=($qnt++ > 0 ? ', ' : '').'Controlad'.$config['genero_pratica'];
	if ($pratica['pratica_proativa']) $pontos_fortes.=($qnt++ > 0 ? ', ' : '').'Proativ'.$config['genero_pratica'];
	if ($pratica['pratica_abrange_pertinentes']) $pontos_fortes.=($qnt++ > 0 ? ', ' : '').'Abrangente';
	if ($pratica['pratica_continuada']) $pontos_fortes.=($qnt++ > 0 ? ', ' : '').'Continuad'.$config['genero_pratica'];
	if ($pratica['pratica_refinada']) $pontos_fortes.=($qnt++ > 0 ? ', ' : '').'Refinad'.$config['genero_pratica'];
	if ($pratica['pratica_coerente']) $pontos_fortes.=($qnt++ > 0 ? ', ' : '').'Coerente';
	if ($pratica['pratica_interrelacionada']) $pontos_fortes.=($qnt++ > 0 ? ', ' : '').'Inter-relacionad'.$config['genero_pratica'];
	if ($pratica['pratica_cooperacao']) $pontos_fortes.=($qnt++ > 0 ? ', ' : '').'Cooperativ'.$config['genero_pratica'];
	if ($pratica['pratica_arte']) $pontos_fortes.=($qnt++ > 0 ? ', ' : '').'Estado de arte';
	if ($pratica['pratica_inovacao']) $pontos_fortes.=($qnt++ > 0 ? ', ' : '').'Inovador'.($config['genero_pratica']=='a' ? 'a': '');
	if ($qnt)$pontos_fortes.='.';
	if ($pontos_fortes) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. PONTOS FORTES</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pontos_fortes.'</font></td></tr>';
	}

if (!($pratica['pratica_controlada']&&$pratica['pratica_proativa']&&$pratica['pratica_abrange_pertinentes']&&$pratica['pratica_continuada']&&$pratica['pratica_coerente']&&$pratica['pratica_interrelacionada']&&$pratica['pratica_cooperacao']&&$pratica['pratica_refinada'])){

	$pontos_fracos='';
	//if (!$pratica['pratica_adequada'])$pontos_fracos.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Adequação'.($pratica['pratica_justificativa_adequada'] ? ': '.$pratica['pratica_justificativa_adequada'] : '').'</font>';
	if (!$pratica['pratica_controlada']) $pontos_fracos.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Controle'.($pratica['pratica_justificativa_controlada'] ? ': '.$pratica['pratica_justificativa_controlada'] : '').'</font>';
	if (!$pratica['pratica_proativa']) $pontos_fracos.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Proatividade'.($pratica['pratica_justificativa_proativa'] ? ': '.$pratica['pratica_justificativa_proativa'] : '').'</font>';
	if (!$pratica['pratica_abrange_pertinentes']) $pontos_fracos.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Abrangência'.($pratica['pratica_justificativa_abrangencia'] ? ': '.$pratica['pratica_justificativa_abrangencia'] : '').'</font>';
	if (!$pratica['pratica_continuada']) $pontos_fracos.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Uso Continuado'.($pratica['pratica_justificativa_continuada'] ? ': '.$pratica['pratica_justificativa_continuada'] : '').'</font>';
	if (!$pratica['pratica_refinada']) $pontos_fracos.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Refinamento'.($pratica['pratica_justificativa_refinada'] ? ': '.$pratica['pratica_justificativa_refinada'] : '').'</font>';
	if (!$pratica['pratica_coerente']) $pontos_fracos.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Coerência'.($pratica['pratica_justificativa_coerente'] ? ': '.$pratica['pratica_justificativa_coerente'] : '').'</font>';
	if (!$pratica['pratica_interrelacionada']) $pontos_fracos.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Inter-relacionamento'.($pratica['pratica_justificativa_interrelacionada'] ? ': '.$pratica['pratica_justificativa_interrelacionada'] : '').'</font>';
	if (!$pratica['pratica_cooperacao']) $pontos_fracos.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>Cooperação'.($pratica['pratica_justificativa_cooperacao'] ? ': '.$pratica['pratica_justificativa_cooperacao'] : '').'</font>';
	if ($pontos_fracos) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. OPORTUNIDADES DE MELHORIA</b></font>'.$pontos_fracos.'</td></tr>';
	}


//marcadores

$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$sql->adTabela('pratica_nos_marcadores');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
$sql->limpar();

$sql->adTabela('pratica_nos_marcadores');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$itens=$sql->ListaChaveSimples('pratica_item_id');
$sql->limpar();


$sql->adTabela('pratica_nos_marcadores');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOrdem('pratica_criterio_numero');
$sql->adOrdem('pratica_item_numero');
$sql->adOrdem('pratica_marcador_letra');
$marcadores=$sql->Lista();
$sql->limpar();

$criterio_atual='';
$marcador_atual='';


if ($marcadores && count($marcadores)) {
	
	echo '<tr><td align="left"><font size=3><b>'.++$numero.'. MARCADORES ATENDIDOS PEL'.strtoupper($config['genero_pratica']).' '.strtoupper($config['pratica']).'</b></font></td></tr>';
	echo '<tr><td align="left"><table><tr><td width="50">&nbsp;</td><td><table id="tblPraticas" border=0 cellpadding=0 cellspacing=1 width="100%">';
	foreach($marcadores as $dado){
		if ($dado['pratica_criterio_id']!=$criterio_atual){
			$criterio_atual=$dado['pratica_criterio_id'];
			echo '<tr><td align="left" colspan=2 <font size=2>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</font></td></tr>';
			}
		if ($dado['pratica_item_id']!=$marcador_atual){
			$marcador_atual=$dado['pratica_item_id'];
			echo '<tr><td align="left" colspan=2 ><font size=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].'</font></td></tr>';
			}
	
		echo '<tr><td align="right" valign="top"><font size=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$dado['pratica_marcador_letra'].'</b></font></td><td class="realce" width="100%"><font size=2>'.$dado['pratica_marcador_texto'].'</font></td></tr>';
		}
	echo '</table></td></tr></table></td></tr>';
	}

$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_id');
$sql->adOnde('pratica_indicador_pratica = '.(int)$pratica_id);
$indicadores = $sql->listaVetorChave('pratica_indicador_id','pratica_indicador_id');
$sql->limpar();

if ($indicadores && count($indicadores)){
	echo '<tr><td align="left"><font size=3><b>'.++$numero.'. INDICADOR'.(count($indicadores)>1 ? 'ES' :'').'</b></font></td></tr>';	
	
	foreach($indicadores as $chave => $pratica_indicador_id) {
		$sql = new BDConsulta;
		$sql->adTabela('pratica_indicador');
		$sql->adCampo('pratica_indicador_mostrar_valor,	pratica_indicador_mostrar_titulo, pratica_indicador_max_min, pratica_indicador_tipografico, pratica_indicador_agrupar, pratica_indicador_nr_pontos');
		$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
		$pratica_indicador=$sql->Linha();
		$sql->limpar();
		$pratica_indicador_mostrar_valor=(isset($_REQUEST['pratica_indicador_mostrar_valor']) && isset($_REQUEST['pratica_indicador_mostrar_valor']) || (!isset($_REQUEST['pratica_indicador_mostrar_valor']) && $pratica_indicador['pratica_indicador_mostrar_valor']) ? 1 : 0);
		$pratica_indicador_mostrar_titulo=(isset($_REQUEST['pratica_indicador_mostrar_titulo']) && isset($_REQUEST['pratica_indicador_mostrar_titulo']) || (!isset($_REQUEST['pratica_indicador_mostrar_titulo']) && $pratica_indicador['pratica_indicador_mostrar_titulo']) ? 1 : 0);
		$pratica_indicador_max_min=(isset($_REQUEST['pratica_indicador_max_min']) && isset($_REQUEST['pratica_indicador_max_min']) || (!isset($_REQUEST['pratica_indicador_max_min']) && $pratica_indicador['pratica_indicador_max_min']) ? 1 : 0);
		$pratica_indicador_tipografico=(isset($_REQUEST['pratica_indicador_tipografico']) ? getParam($_REQUEST, 'pratica_indicador_tipografico', null) : $pratica_indicador['pratica_indicador_tipografico']);
		$pratica_indicador_agrupar=(isset($_REQUEST['pratica_indicador_agrupar']) ? getParam($_REQUEST, 'pratica_indicador_agrupar', null) : $pratica_indicador['pratica_indicador_agrupar']);
		$pratica_indicador_nr_pontos=(isset($_REQUEST['pratica_indicador_nr_pontos']) ? getParam($_REQUEST, 'pratica_indicador_nr_pontos', null) : $pratica_indicador['pratica_indicador_nr_pontos']);
		$df = '%d/%m/%Y';
		$data = (isset($_REQUEST['pratica_indicador_data']) ? new CData(getParam($_REQUEST, 'pratica_indicador_data', null)) : new CData());
		$data_final=$data->format("%Y-%m-%d");
		$src = '?m=praticas&a=grafico_free&sem_cabecalho=1&ano='.$ano.'&mostrar_valor='.$pratica_indicador_mostrar_valor.'&data_final='.$data_final.'&nr_pontos='.$pratica_indicador_nr_pontos.'&mostrar_titulo='.$pratica_indicador_mostrar_titulo.'&max_min='.$pratica_indicador_max_min.'&agrupar='.$pratica_indicador_agrupar.'&tipografico='.$pratica_indicador_tipografico.'&pratica_indicador_id='.$pratica_indicador_id."&width=800";
		echo '<tr><td align="left">';
		echo "<table cellspacing='0' cellpadding='0' align='center' class='tbl1'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr></table>";
		echo '</td></tr>';
		}
	
	}







//plano de ação
$sql->adTabela('plano_acao');
$sql->esqUnir('praticas', 'praticas', 'plano_acao.plano_acao_pratica=praticas.pratica_id');
$sql->adCampo('plano_acao.*');
$sql->adOnde('plano_acao_pratica = '.$pratica_id);
$acoes = $sql->Lista();
$sql->limpar();

if ($acoes && count($acoes)){
	echo '<tr><td align="left"><font size=3><b>'.++$numero.'. PLANO DE AÇÂO</b></font></td></tr>';	
	echo '<tr><td align="left">';
	echo '<table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1">';
	echo '<tr><th>Ação</th><th>O Que</th><th>Por Que</th><th>Como</th><th>Quando</th><th>Onde</th><th>Quem</th><th>Quanto</th></tr>';
	foreach($acoes as $acao){
			$sql->adTabela('usuarios', 'u');
			$sql->adTabela('plano_acao_designados', 'ut');
			$sql->adTabela('contatos', 'con');
			$sql->adCampo('u.usuario_id, contato_dept');
			$sql->adOnde('ut.plano_acao_id = '.$acao['plano_acao_id']);
			$sql->adOnde('usuario_contato = contato_id');
			$sql->adOnde('ut.usuario_id = u.usuario_id');
			$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
			$designados = $sql->Lista();
			$sql->limpar();
			echo '<tr>';
			echo '<td>'.$acao['plano_acao_nome'].'</td>';
			echo '<td>'.($acao['plano_acao_oque'] ? $acao['plano_acao_oque'] : '&nbsp;').'</td>';
			echo '<td>'.($acao['plano_acao_porque'] ? $acao['plano_acao_porque'] : '&nbsp;').'</td>';
			echo '<td>'.($acao['plano_acao_como'] ? $acao['plano_acao_como'] : '&nbsp;').'</td>';
			echo '<td>'.($acao['plano_acao_quando'] ? $acao['plano_acao_quando'] : '&nbsp;').'</td>';
			echo '<td>'.($acao['plano_acao_onde'] ? $acao['plano_acao_onde'] : '&nbsp;').'</td>';
			echo '<td>'.($acao['plano_acao_quem'] ? $acao['plano_acao_quem'] : '&nbsp;').'</td>';
			echo '<td>'.($acao['plano_acao_quanto'] ? $acao['plano_acao_quanto'] : '&nbsp;').'</td>';
			echo '</tr>';
			}
	echo '</table>';	
	echo '</td></tr>';
	}


if (!$Aplic->profissional && $config['barra_projeto']){
	//echo '<tr><td colspan=20><table><tr><td width=10></td><td><script>document.write(\'<img src="?m=publico&a=codigo_barra&sem_cabecalho=1&texto=PA'.$pratica_id.'\">\')</script></td></tr></table></td></tr>';
	echo '<tr><td colspan=20><table><tr><td width=12></td><td><script>document.write(\'<img src="'.BASE_URL.'/lib/barras/barcode.php?quality=75&barcode=PA'.$pratica_id.'\">\')</script></td></tr></table></td></tr>';
	}
echo '</table>';


?>
