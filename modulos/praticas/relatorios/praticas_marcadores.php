<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf, $dialogo, $ano, $pratica_modelo_id, $usuario_id;
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

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
	
	
$sql->adTabela('pratica_nos_marcadores');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_nos_marcadores.marcador =pratica_marcador.pratica_marcador_id');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
$sql->adCampo('DISTINCT praticas.pratica_id, pratica_nome, pratica_controlada, pratica_proativa, pratica_abrange_pertinentes, pratica_continuada, pratica_refinada, pratica_coerente, pratica_interrelacionada, pratica_cooperacao, pratica_cooperacao_partes, pratica_arte, pratica_inovacao, pratica_acesso');
$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_cia='.(int)$cia_id);
$sql->adOnde('pratica_requisito.ano='.(int)$ano);
$sql->adOnde('pratica_criterio_resultado=0');
if($filtro_criterio > 0) $sql->adOnde('pratica_criterio_id='.$filtro_criterio);
elseif (!$filtro_criterio)$sql->adOnde('pratica_criterio_id=0 OR pratica_criterio_id IS NULL');
$praticas=$sql->ListaChaveSimples('pratica_id');
$sql->limpar();

$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
if($filtro_criterio > 0) $sql->adOnde('pratica_criterio_id='.(int)$filtro_criterio);
elseif (!$filtro_criterio)$sql->adOnde('pratica_criterio_id=0 OR pratica_criterio_id IS NULL');
$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
$sql->limpar();


$sql->adTabela('pratica_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs, pratica_item_oculto');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
if($filtro_criterio > 0) $sql->adOnde('pratica_criterio_id='.(int)$filtro_criterio);
elseif (!$filtro_criterio)$sql->adOnde('pratica_criterio_id=0 OR pratica_criterio_id IS NULL');
$itens=$sql->ListaChaveSimples('pratica_item_id');
$sql->limpar();


$sql->adTabela('pratica_marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
if($filtro_criterio > 0) $sql->adOnde('pratica_criterio_id='.(int)$filtro_criterio);
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

if (!$dialogo) echo '<table border=0 cellpadding="2" cellspacing=0><td>'.dica('Seleção de Critério', 'Utilize esta opção para filtrar pelo critério selecionado.').'&nbsp;Critério:'.dicaF().'</td><td nowrap="nowrap" align="left">'.selecionaVetor($lista_criterio, 'filtro_criterio', 'onchange="document.env.submit()" class="texto"', $filtro_criterio).'</td></form></tr></table>';


$criterio_atual='';
$item_atual='';

if ($filtro_criterio) {
	
	if (!$dialogo){
	echo '<table width="100%"><tr><td width="22">&nbsp;</td><td align="center"><font size="4"><center>Lista d'.$config['genero_pratica'].'s '.$config['praticas'].' n'.$config['genero_marcador'].'s '.$config['marcadores'].'</center></font></td><td width="22"><a href="javascript: void(0);" onclick ="frm_filtro.target=\'popup\'; frm_filtro.dialogo.value=1; frm_filtro.submit();">'.imagem('imprimir_p.png', 'Imprimir o Relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'</a></td></tr></table>';
	echo estiloTopoCaixa();
	}
	else echo '<table width="750"><tr><td align="center"><font size="4"><center>Lista d'.$config['genero_pratica'].'s '.$config['praticas'].' n'.$config['genero_marcador'].'s '.$config['marcadores'].'</center></font></td></tr></table>';		
	
	echo '<table border=1 cellpadding=0 cellspacing=0 '.($dialogo ? 'width="750"' : 'width="100%" class="std2"').'>';
	
	
	//campos utilizados na regua específica	
	$sql->adTabela('pratica_regra');
	$sql->esqUnir('pratica_regra_campo', 'pratica_regra_campo', 'pratica_regra_campo_nome=pratica_regra_campo');
	$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
	$sql->adOnde('pratica_modelo_id='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_regra_campo_resultado=0');
	$sql->adOrdem('subitem ASC, pratica_regra_ordem');
	$sql->adGrupo('pratica_regra_campo_nome');
	$lista=$sql->lista();
	$sql->limpar();
	
	$campos=array();
	
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
	
	
	echo '<tr><th>Campo</th>';
	foreach ($lista as $linha)	{
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
			echo '<tr><td align="left" nowrap="nowrap" style="font-size:200%; font-weight:bold">'.dica('Dados Sobre o Critério', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].dicaF().'</td><td colspan=20>&nbsp;</td></tr>';
			}
			
		if ($dado['pratica_item_id']!=$item_atual){
			$item_atual=$dado['pratica_item_id'];
			$dentro = '<table cellspacing="4" cellpadding="2" width="100%">';
			if ($itens[$dado['pratica_item_id']]['pratica_item_obs']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_obs'].'</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_pontos'].'</td></tr>';
			$dentro .= '</table>';
			if (!$itens[$dado['pratica_item_id']]['pratica_item_oculto']) echo '<tr><td align="left" nowrap="nowrap" style="font-size:150%; font-weight:bold">&nbsp;'.dica('Dados Sobre o Ítem', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].dicaF().'</td><td colspan=20>&nbsp;</td></tr>';
			}
		
		
		
		$sql->adTabela('pratica_nos_marcadores');
		$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
		$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
		
		
		
		
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_nos_marcadores.marcador =pratica_marcador.pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
		
		
		
		$q2 = new BDConsulta();
		$q2->adTabela('pratica_nos_verbos');
		$q2->adCampo('COUNT(verbo)');
		$q2->adOnde('pratica=praticas.pratica_id');
		$q2->adOnde('ano='.(int)$ano);	
		$sql->adCampo('DISTINCT praticas.pratica_id, pratica_nome, pratica_controlada, ('.$q2->prepare().') AS pratica_adequada, pratica_proativa, pratica_abrange_pertinentes, pratica_continuada, pratica_refinada, pratica_coerente, pratica_interrelacionada, pratica_cooperacao, pratica_cooperacao_partes, pratica_arte, pratica_inovacao, pratica_melhoria_aprendizado, pratica_agil, pratica_gerencial,  pratica_incoerente, pratica_refinada_implantacao, pratica_acesso');
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$sql->adOnde('pratica_cia='.(int)$cia_id);
		$sql->adOnde('marcador='.$dado['pratica_marcador_id']);
		$sql->adOnde('pratica_nos_marcadores.ano = '.(int)$ano);
		if ($usuario_id) $sql->adOnde('pratica_responsavel = '.(int)$usuario_id);
		$sql->adGrupo('pratica_nos_marcadores.pratica');
		$grupo=$sql->Lista();
		$sql->limpar();

		echo '<tr><td><table cellpadding=0 cellspacing=0><tr><td align="left" nowrap="nowrap" valign="top" style="width:20px; font-size:110%;">&nbsp;&nbsp;'.$dado['pratica_marcador_letra'].'.</td><td style="font-size:110%;">'.($dado['pratica_marcador_extra'] ? dica('Informações Extras', $dado['pratica_marcador_extra']).$dado['pratica_marcador_texto'].dicaF() : $dado['pratica_marcador_texto']).'</td></tr></table></td><td colspan=20>&nbsp;</td></tr>';
		echo somatorio($grupo);
		}
	echo '</table>';
	if (!$dialogo) echo estiloFundoCaixa();	
	else echo '<script>self.print();</script>';	
	}


function somatorio($vetor_praticas){
	global $praticas, $campos, $dialogo;
	//inicialização dos dois vetores
	$soma=array();
	$nomes=array();
	foreach ($vetor_praticas as $linha){
		if (permiteAcessarPratica($linha['pratica_acesso'],$linha['pratica_id'])) echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(!$dialogo ? '<a href="javascript: void(0);" onclick="ver_pratica('.$linha['pratica_id'].')">' : '').$linha['pratica_nome'].(!$dialogo ? '</a>':'').'</td>';	
		else echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$linha['pratica_nome'].'</td>';	
		foreach ($campos as $chave => $nome_campo) echo '<td align=center>'.($linha[$nome_campo] ? imagem('icones/ponto.png') : imagem('icones/vazio16.gif')).'</td>';
		}
	echo '</tr>';
	}
	
	

function texto_vertical1($legenda, $titulo='', $texto=''){
	$saida='';
	for ($i=0; $i< strlen($legenda); $i++) $saida.=$legenda[$i].'<br>';
	return dica($titulo, $texto).$saida.dicaF();
	}
?>
<script language="javascript">

function ver_pratica(pratica_id){
	env.pratica_id.value=pratica_id;
	env.a.value='pratica_ver';
	env.submit();
	}


function exibir(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
</script>

