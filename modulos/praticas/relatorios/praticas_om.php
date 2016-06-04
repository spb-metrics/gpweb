<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf, $dialogo,  $cia_id, $ano, $usuario_id, $pratica_modelo_id;


//Caso de impressão
if (!isset($cia_id))$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

$Aplic->carregarCalendarioJS();
$pratica_id = intval(getParam($_REQUEST, 'pratica_id', 0));
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;


if (isset($_REQUEST['filtro_criterio'])) $Aplic->setEstado('filtro_criterio', getParam($_REQUEST, 'filtro_criterio', null));
$filtro_criterio = ($Aplic->getEstado('filtro_criterio') !== null ? $Aplic->getEstado('filtro_criterio') : 0);

if ((!$podeEditar && $pratica_id > 0) || (!$podeAdicionar && $pratica_id == 0)) $Aplic->redirecionar('m=publico&a=acesso_negado');

	
$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();	
	
$q2 = new BDConsulta();
$q2->adTabela('pratica_nos_verbos');
$q2->adCampo('COUNT(verbo)');
$q2->adOnde('pratica=praticas.pratica_id');	
$q2->adOnde('ano='.(int)$ano);		
	
$sql->adTabela('pratica_nos_marcadores');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_nos_marcadores.marcador =pratica_marcador.pratica_marcador_id');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
$sql->adCampo('DISTINCT praticas.pratica_id, pratica_nome, pratica_controlada, ('.$q2->prepare().') AS pratica_adequada, pratica_proativa, pratica_abrange_pertinentes, pratica_continuada, pratica_refinada, pratica_coerente, pratica_interrelacionada, pratica_cooperacao, pratica_cooperacao_partes, pratica_arte, pratica_inovacao, pratica_melhoria_aprendizado, pratica_agil, pratica_gerencial,  pratica_incoerente, pratica_refinada_implantacao, pratica_acesso, 0 AS pode_ver');
$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_cia='.(int)$cia_id);
if ($usuario_id) $sql->adOnde('pratica_responsavel='.(int)$usuario_id);
$sql->adOnde('pratica_nos_marcadores.ano='.(int)$ano);
$sql->adOnde('pratica_requisito.ano='.(int)$ano);
$sql->adOnde('pratica_criterio_resultado=0');
if($filtro_criterio > 0) $sql->adOnde('pratica_criterio_id='.$filtro_criterio);
elseif (!$filtro_criterio)$sql->adOnde('pratica_criterio_id=0 OR pratica_criterio_id IS NULL');
$praticas=$sql->ListaChaveSimples('pratica_id');
$sql->limpar();

foreach($praticas as $atual){
	$praticas[$atual['pratica_id']]['pode_ver']=permiteAcessarPratica($atual['pratica_acesso'],$atual['pratica_id']);
	}

$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
if($filtro_criterio > 0) $sql->adOnde('pratica_criterio_id='.$filtro_criterio);
elseif (!$filtro_criterio)$sql->adOnde('pratica_criterio_id=0 OR pratica_criterio_id IS NULL');
$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
$sql->limpar();


$sql->adTabela('pratica_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs, pratica_item_oculto');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
if($filtro_criterio > 0) $sql->adOnde('pratica_criterio_id='.$filtro_criterio);
elseif (!$filtro_criterio)$sql->adOnde('pratica_criterio_id=0 OR pratica_criterio_id IS NULL');
$itens=$sql->ListaChaveSimples('pratica_item_id');
$sql->limpar();


$sql->adTabela('pratica_marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
if($filtro_criterio > 0) $sql->adOnde('pratica_criterio_id='.$filtro_criterio);
elseif (!$filtro_criterio)$sql->adOnde('pratica_criterio_id=0 OR pratica_criterio_id IS NULL');
$sql->adOrdem('pratica_criterio_numero');
$sql->adOrdem('pratica_item_numero');
$sql->adOrdem('pratica_marcador_letra');
$marcadores=$sql->Lista();
$sql->limpar();


$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
$resultado=$sql->Lista();
$sql->limpar();

$lista_criterio=array();
foreach($resultado as $criterio) $lista_criterio[$criterio['pratica_criterio_id']]=$criterio['pratica_criterio_nome'];
$lista_criterio=array('0'=>'', '-1'=>'Todos')+$lista_criterio;

if (!$dialogo) {
	echo '<form name="env" method="post">';
	echo '<input type="hidden" name="m" value="praticas" />';
	echo '<input type="hidden" name="a" value="relatorios" />';
	echo '<input type="hidden" name="pratica_id" value="'.$pratica_id.'" />';
	echo '<input type="hidden" name="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
	echo '<input type="hidden" name="relatorio_tipo" value="'.$relatorio_tipo.'" />';
	}

echo '<table width="100%" border=0 cellpadding="2" cellspacing=0><tr><td><table><tr>';
if (!$dialogo) echo '<td>'.dica('Seleção de Critério', 'Utilize esta opção para filtrar pelo critério selecionado.').'&nbsp;Critério:'.dicaF().'</td><td nowrap="nowrap" align="left">'.selecionaVetor($lista_criterio, 'filtro_criterio', 'onchange="document.env.submit()" class="texto"', $filtro_criterio).'</td></form>';
echo '<td>&nbsp; &nbsp;</td><td style="border-style:solid;border-width:1px" bgcolor="#d8ffcf">&nbsp; &nbsp;</td><td>'.dica('Pouc'.$config['genero_pratica'].'s '.ucfirst($config['praticas']), 'Pouc'.$config['genero_pratica'].'s '.$config['praticas'].' não atendem ao requisito.').'Pouc'.$config['genero_pratica'].'s 0-24%'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#edffcf">&nbsp; &nbsp;</td><td>'.dica('Muit'.$config['genero_pratica'].'s '.ucfirst($config['praticas']), 'Muit'.$config['genero_pratica'].'s '.$config['praticas'].' não atendem ao requisito.').'Muit'.$config['genero_pratica'].'s 25-49%'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#feffcf">&nbsp; &nbsp;</td><td>'.dica('Maioria d'.$config['genero_pratica'].'s '.ucfirst($config['praticas']), 'Maioria d'.$config['genero_pratica'].'s '.$config['praticas'].' não atendem ao requisito.').'Maioria 50-74%'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffe9cf">&nbsp; &nbsp;</td><td>'.dica('Quase Tod'.$config['genero_pratica'].'s '.$config['genero_pratica'].'s '.ucfirst($config['praticas']), 'Quase tod'.$config['genero_pratica'].'s '.$config['genero_pratica'].'s '.$config['praticas'].' não atendem ao requisito.').'Quase Tod'.$config['genero_pratica'].'s 75-99%'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffd9cf">&nbsp; &nbsp;</td><td>'.dica('Tod'.$config['genero_pratica'].'s '.$config['genero_pratica'].'s '.ucfirst($config['praticas']), 'Tod'.$config['genero_pratica'].'s '.$config['genero_pratica'].'s '.$config['praticas'].' não atendem ao requisito.').'Tod'.$config['genero_pratica'].'s 100%'.dicaF().'</td>';
echo '</tr></table></td></tr></table>';


$criterio_atual='';
$item_atual='';

if ($filtro_criterio) {
	
	if (!$dialogo){
	echo '<table width="100%"><tr><td width="22">&nbsp;</td><td align="center"><font size="4"><center>Oportunidades de Melhoria n'.$config['genero_pratica'].'s '.ucfirst($config['praticas']).'</center></font></td><td width="22"><a href="javascript: void(0);" onclick ="frm_filtro.target=\'popup\'; frm_filtro.dialogo.value=1; frm_filtro.submit();">'.imagem('imprimir_p.png', 'Imprimir o Relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'</a></td></tr></table>';
	echo estiloTopoCaixa();
	}
	else echo '<table width="100%"><tr><td align="center"><font size="4"><center>Oportunidades de Melhoria n'.$config['genero_pratica'].'s '.ucfirst($config['praticas']).'</center></font></td></tr></table>';		
	
	echo '<table border=1 cellpadding=0 cellspacing=0 width="100%" '.($dialogo ? '' : 'class="std2"').'>';
	
	//campos utilizados na regua específica	
	$sql->adTabela('pratica_regra');
	$sql->esqUnir('pratica_regra_campo', 'pratica_regra_campo', 'pratica_regra_campo_nome=pratica_regra_campo');
	$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
	$sql->adOnde('pratica_modelo_id='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_regra_campo_resultado=0');
	$sql->adOrdem('subitem ASC, pratica_regra_ordem');
	$sql->adGrupo('pratica_regra_campo_nome');
	$vetor_campos=$sql->lista();
	$sql->limpar();
	
	$vetor_existe=array(
		'pratica_controlada',
		'pratica_proativa',
		'pratica_abrange_pertinentes',
		'pratica_continuada',
		'pratica_refinada',
		'pratica_melhoria_aprendizado',
		'pratica_coerente',
		'pratica_interrelacionada',
		'pratica_cooperacao',
		'pratica_cooperacao_partes',
		'pratica_arte',
		'pratica_inovacao',
		'pratica_gerencial',
		'pratica_agil',
		'pratica_refinada_implantacao',
		'pratica_incoerente'
		);
	
	$campos=array();
	echo '<tr><th>Campo</th><th valign="bottom" style="padding:1px;line-height:10px;">'.texto_vertical1('quantidade de '.$config['praticas'],'Quantidade de '.ucfirst($config['praticas']),'Somatório d'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' que atendem '.$config['genero_marcador'].'s '.$config['marcadores'].'.').'</th>';
	foreach ($vetor_campos as $linha)	{
		if (in_array($linha['pratica_regra_campo_nome'], $vetor_existe)){
			$campos[]=$linha['pratica_regra_campo_nome'];
			echo '<th valign="bottom" style="padding:1px;line-height:10px;">'.texto_vertical1($linha['pratica_regra_campo_texto'],$linha['pratica_regra_campo_texto'], $linha['pratica_regra_campo_descricao']).'</th>';
			}
		}
	echo '</tr>';
	
	foreach($marcadores as $dado){
		if ($dado['pratica_criterio_id']!=$criterio_atual){
			$criterio_atual=$dado['pratica_criterio_id'];
			$dentro = '<table cellspacing="4" cellpadding="2" width="100%">';
			if ($criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs'].'</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_pontos'].'</td></tr>';
			$dentro .= '</table>';
			$sql->adTabela('pratica_nos_marcadores');
			$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
			$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
			$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
			$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
			$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
			$sql->adCampo('DISTINCT praticas.pratica_id');
			$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
			$sql->adOnde('pratica_cia='.(int)$cia_id);
			if ($usuario_id) $sql->adOnde('pratica_responsavel='.(int)$usuario_id);
			$sql->adOnde('pratica_nos_marcadores.ano='.(int)$ano);
			$sql->adOnde('pratica_requisito.ano='.(int)$ano);
			$sql->adOnde('pratica_item_criterio='.(int)$criterio_atual);
			$grupo=$sql->Lista();
			$sql->limpar();
			echo '<tr><td align="left" nowrap="nowrap">'.dica('Dados Sobre o Critério', $dentro).'<b>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</b>'.dicaF().'</td>'.somatorio($grupo).'</tr>';
			}
			
		if ($dado['pratica_item_id']!=$item_atual){
			$item_atual=$dado['pratica_item_id'];
			$dentro = '<table cellspacing="4" cellpadding="2" width="100%">';
			if ($itens[$dado['pratica_item_id']]['pratica_item_obs']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_obs'].'</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_pontos'].'</td></tr>';
			$dentro .= '</table>';
			$sql->adTabela('pratica_nos_marcadores');
			$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
			$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
			$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
			$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
			$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
			$sql->adCampo('DISTINCT praticas.pratica_id');
			$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
			$sql->adOnde('pratica_cia='.(int)$cia_id);
			if ($usuario_id) $sql->adOnde('pratica_responsavel='.(int)$usuario_id);
			$sql->adOnde('pratica_nos_marcadores.ano='.(int)$ano);
			$sql->adOnde('pratica_requisito.ano='.(int)$ano);
			$sql->adOnde('pratica_marcador_item='.$item_atual);
			$grupo=$sql->Lista();
			$sql->limpar();
			if (!$itens[$dado['pratica_item_id']]['pratica_item_oculto']) echo '<tr><td align="left" nowrap="nowrap">&nbsp;'.dica('Dados Sobre o Ítem', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].dicaF().'</td>'.somatorio($grupo).'</tr>';
			}
		
		
		
		$sql->adTabela('pratica_nos_marcadores');
		$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
		$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
		$sql->adCampo('DISTINCT praticas.pratica_id');
		$sql->adOnde('pratica_cia='.(int)$cia_id);
		if ($usuario_id) $sql->adOnde('pratica_responsavel='.(int)$usuario_id);
		$sql->adOnde('pratica_nos_marcadores.ano='.(int)$ano);
		$sql->adOnde('pratica_requisito.ano='.(int)$ano);
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$sql->adOnde('marcador='.$dado['pratica_marcador_id']);
		$grupo=$sql->Lista();
		$sql->limpar();
		
		
		echo '<tr><td><table cellpadding=0 cellspacing=0><tr><td align="left" nowrap="nowrap" valign="top" style="width:20px">&nbsp;&nbsp;<b>'.$dado['pratica_marcador_letra'].'</b></td><td >'.($dado['pratica_marcador_extra'] ? dica('Informações Extras', $dado['pratica_marcador_extra']).$dado['pratica_marcador_texto'].dicaF() : $dado['pratica_marcador_texto']).'</td></tr></table></td>'.somatorio($grupo).'</tr>';
		}
	echo '</table>';
	if (!$dialogo) echo estiloFundoCaixa();	
	else echo '<script>self.print();</script>';	
	}


function somatorio($vetor_praticas){
	global $praticas, $campos;
	//inicialização dos dois vetores
	$soma=array();
	$nomes=array();
	foreach ($campos as $chave => $nome_campo){
		$soma[$nome_campo]=0;
		$nomes[$nome_campo]=null;
		}
	$nomes['pratica_nome']=null;
	foreach($vetor_praticas as $chave => $pratica_id){
		foreach ($campos as $chave => $nome_campo){
			if (!$praticas[$pratica_id['pratica_id']][$nome_campo]) {$soma[$nome_campo]++; $nomes[$nome_campo][$pratica_id['pratica_id']]=$praticas[$pratica_id['pratica_id']]['pratica_nome'];}
			}
		$nomes['pratica_nome'][$pratica_id['pratica_id']]=$praticas[$pratica_id['pratica_id']]['pratica_nome'];
		}
	$total=count($vetor_praticas);
	$saida='';
	foreach ($campos as $chave => $nome_campo) $saida.=formata_soma($total, $soma[$nome_campo], $nomes[$nome_campo]);
	return formata_nomes($nomes['pratica_nome']).$saida.'</td>';
	}
	
function formata_nomes($nomes=array()){
	global $config, $praticas;
	static $n=0;
	$n++;
	$saida='';
	$quantidade=count($nomes);
	if ($quantidade){
		foreach($nomes as $pratica_id => $pratica_nome) {
			if ($praticas[$pratica_id]['pode_ver']) $saida.='<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=pratica_ver&pratica_id='.$pratica_id.'\');">'.$pratica_nome.'</a><br>';
			else $saida.=$pratica_nome.'<br>';
			}
		$saida='<table align="center"><tr><td><a href="javascript: void(0);" onclick="exibir(\'n_'.$n.'\')">'.dica(ucfirst($config['praticas']), 'Clique para visualizar '.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas']).$quantidade.dicaF().'</a></td></tr><tr id="n_'.$n.'" style="display:none"><td>'.$saida.'</td></tr></table>';
		return '<td style="text-align:center;">'.$saida.'</td>';
		}
	else return '<td style="text-align:center;">'.$quantidade.dicaF().'</td>';
	}		
	
	
function formata_soma($total, $quantidade, $nomes=array()){
	global $config, $praticas;
	static $i=0;
	$i++;
	$percentagem=($total ? (int)(($quantidade/$total)*100) : 0);
	
	if ($percentagem<25) $cor='d8ffcf';
	elseif ($percentagem<50) $cor='edffcf';
	elseif ($percentagem<75) $cor='feffcf';
	elseif ($percentagem<100) $cor='ffe9cf';
	else $cor='ffd9cf';
	$saida='';
	if (count($nomes)){
		foreach($nomes as $pratica_id => $pratica_nome) {
			if ($praticas[$pratica_id]['pode_ver']) $saida.='<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=pratica_ver&pratica_id='.$pratica_id.'\');">'.$pratica_nome.'</a><br>';
			else $saida.=$pratica_nome.'<br>';
			}
		$saida='<table align="center"><tr><td><a href="javascript: void(0);" onclick="exibir(\'c_'.$i.'\')">'.dica('Percentagem', $percentagem.'%<br><br>Clique para visualizar '.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas']).$quantidade.dicaF().'</a></td></tr><tr id="c_'.$i.'" style="display:none"><td>'.$saida.'</td></tr></table>';
		return '<td style="text-align:center; background:#'.$cor.'">'.$saida.'</td>';
		}
	else{
		return '<td style="text-align:center; background:#'.$cor.'">'.dica('Percentagem', $percentagem.'%').'&nbsp;'.$quantidade.'&nbsp;'.dicaF().'</td>';
		}	
	}	
	

function texto_vertical1($legenda, $titulo='', $texto=''){
	$saida='';
	for ($i=0; $i< strlen($legenda); $i++) $saida.=$legenda[$i].'<br>';
	return dica($titulo, $texto).$saida.dicaF();
	}
?>
<script language="javascript">


function exibir(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
</script>

