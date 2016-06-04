<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf, $cia_id, $dialogo;
//Caso de impressão
if (!isset($cia_id))$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

$sql = new BDConsulta;
if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

if ((!$podeEditar && $pratica_indicador_id > 0) || (!$podeAdicionar && $pratica_indicador_id == 0)) $Aplic->redirecionar('m=publico&a=acesso_negado');
	
$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();	

$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_tipo');
$sql->adOnde('pratica_modelo_id='.(int)$pratica_modelo_id);
$tipo_pauta=$sql->Resultado();
$sql->limpar();

$sql->adTabela('pratica_regra');
$sql->esqUnir('pratica_regra_campo', 'pratica_regra_campo', 'pratica_regra_campo=pratica_regra_campo_nome');
$sql->adCampo('pratica_regra_campo, pratica_regra_ordem, subitem, pratica_regra_resultado, pratica_regra_campo_texto, pratica_regra_campo_descricao');
$sql->adOnde('pratica_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_resultado=1');
$sql->adOrdem('subitem ASC, pratica_regra_ordem');
$sql->adGrupo('pratica_regra_campo');
$regras_lista=$sql->lista();
$sql->limpar();
$campos=array();
foreach($regras_lista as $linha) $campos[$linha['pratica_regra_campo']]=array('subitem'=> $linha['subitem'], 'pratica_regra_campo_texto'=> $linha['pratica_regra_campo_texto'], 'pratica_regra_campo_descricao'=> $linha['pratica_regra_campo_descricao']);


$sql->adTabela('pratica_indicador_nos_marcadores');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito', 'pratica_indicador.pratica_indicador_requisito = pratica_indicador_requisito.pratica_indicador_requisito_id');
$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_agrupar, pratica_indicador_nome, pratica_indicador_acesso, 0 AS pode_ver');
$sql->adCampo('
		pratica_indicador_requisito_relevante AS pratica_indicador_relevante, 
		pratica_indicador_requisito_favoravel AS pratica_indicador_favoravel, 
		pratica_indicador_requisito_tendencia AS pratica_indicador_tendencia,
		pratica_indicador_requisito_superior AS pratica_indicador_superior,
		pratica_indicador_requisito_atendimento AS pratica_indicador_atendimento, 
		pratica_indicador_requisito_lider AS pratica_indicador_lider, 
		pratica_indicador_requisito_excelencia AS pratica_indicador_excelencia, 
		pratica_indicador_requisito_referencial AS pratica_indicador_referencial,
		pratica_indicador_requisito_estrategico AS pratica_indicador_estrategico');
		
if ($tipo_pauta=='fnq_2015'){
		$sql->adCampo('
			0 AS pratica_indicador_complemento,
			0 AS pratica_indicador_8c_8e,
			0 AS pratica_indicador_estrategico_favoravel,
			0 AS pratica_indicador_8c_8e2,
			0 AS pratica_indicador_estrategico_superior,
			0 AS pratica_indicador_8c_8e3,
			0 AS pratica_indicador_8c_8e4,
			0 AS pratica_indicador_estrategico_atendimento,
			0 AS pratica_indicador_8c_8e5,
			0 AS pratica_indicador_estrategico_lider,
			0 AS pratica_indicador_estrategico_excelencia
			');
		}		
		
		
$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_indicador_requisito_ano='.(int)$ano);
$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$ano);
$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
$sql->adOnde('pratica_criterio_resultado=1');
$indicadores=$sql->ListaChaveSimples('pratica_indicador_id');
$sql->limpar();


if (isset($campos['pratica_indicador_8c_8e'])){
	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador.pratica_indicador_id=pratica_indicador_nos_marcadores.pratica_indicador_id');
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$ano);
	$sql->adOnde('pratica_criterio_resultado=1');
	$sql->adOnde('pratica_marcador.pratica_marcador_id=1079 OR pratica_marcador.pratica_marcador_id=1081');
	$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id');
	$atende_8c_8e=$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_id');
	$sql->limpar();
	}

if (isset($campos['pratica_indicador_complemento'])){	
	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador.pratica_indicador_id=pratica_indicador_nos_marcadores.pratica_indicador_id');
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$ano);
	$sql->adOnde('pratica_criterio_resultado=1');
	$sql->adOnde('pratica_marcador_texto IS NOT NULL');
	$sql->adCampo('count(pratica_indicador_nos_marcadores.pratica_marcador_id) AS qnt, pratica_indicador.pratica_indicador_id');
	$sql->adGrupo('pratica_indicador.pratica_indicador_id');
	$total_possivel_complemento=$sql->listaVetorChave('pratica_indicador_id','qnt');
	$sql->limpar();
	
	
	$sql->adTabela('pratica_indicador_complemento');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_complemento_marcador=pratica_marcador_id');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador.pratica_indicador_id=pratica_indicador_complemento_indicador');
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	$sql->adOnde('pratica_indicador_complemento_ano='.(int)$ano);
	$sql->adOnde('pratica_criterio_resultado=1');
	$sql->adOnde('pratica_marcador_texto IS NOT NULL');
	$sql->adCampo('count(DISTINCT pratica_indicador_complemento_id) AS qnt, pratica_indicador.pratica_indicador_id');
	$sql->adGrupo('pratica_indicador.pratica_indicador_id');
	$total_complemento=$sql->listaVetorChave('pratica_indicador_id','qnt');
	$sql->limpar();
	}

foreach($indicadores as $indicador){
	
	$indicadores[$indicador['pratica_indicador_id']]['pode_ver']=permiteAcessarIndicador($indicador['pratica_indicador_acesso'],$indicador['pratica_indicador_id']);
	
	if (isset($campos['pratica_indicador_complemento'])) $indicadores[$indicador['pratica_indicador_id']]['pratica_indicador_complemento']=(isset($total_complemento[$indicador['pratica_indicador_id']]) && ($total_complemento[$indicador['pratica_indicador_id']]==$total_possivel_complemento[$indicador['pratica_indicador_id']]) ? 1 : 0);
	
	if (isset($campos['pratica_indicador_8c_8e']) && $indicador['pratica_indicador_favoravel'] && isset($atende_8c_8e[$indicador['pratica_indicador_id']])) $indicadores[$indicador['pratica_indicador_id']]['pratica_indicador_8c_8e']=1;
	if (isset($campos['pratica_indicador_8c_8e2']) && $indicador['pratica_indicador_superior'] && isset($atende_8c_8e[$indicador['pratica_indicador_id']])) $indicadores[$indicador['pratica_indicador_id']]['pratica_indicador_8c_8e2']=1;
	if (isset($campos['pratica_indicador_8c_8e3']) && $indicador['pratica_indicador_estrategico'] && $indicador['pratica_indicador_superior'] && isset($atende_8c_8e[$indicador['pratica_indicador_id']])) $indicadores[$indicador['pratica_indicador_id']]['pratica_indicador_8c_8e3']=1;
	if (isset($campos['pratica_indicador_8c_8e4']) && $indicador['pratica_indicador_atendimento'] && isset($atende_8c_8e[$indicador['pratica_indicador_id']])) $indicadores[$indicador['pratica_indicador_id']]['pratica_indicador_8c_8e4']=1;
	if (isset($campos['pratica_indicador_8c_8e5']) && $indicador['pratica_indicador_estrategico'] && $indicador['pratica_indicador_atendimento'] && isset($atende_8c_8e[$indicador['pratica_indicador_id']])) $indicadores[$indicador['pratica_indicador_id']]['pratica_indicador_8c_8e5']=1;
	if (isset($campos['pratica_indicador_estrategico_favoravel']) && $indicador['pratica_indicador_estrategico'] && $indicador['pratica_indicador_favoravel']) $indicadores[$indicador['pratica_indicador_id']]['pratica_indicador_estrategico_favoravel']=1;
	if (isset($campos['pratica_indicador_estrategico_superior']) && $indicador['pratica_indicador_estrategico'] && $indicador['pratica_indicador_superior']) $indicadores[$indicador['pratica_indicador_id']]['pratica_indicador_estrategico_superior']=1;
	if (isset($campos['pratica_indicador_estrategico_lider']) && $indicador['pratica_indicador_estrategico'] && $indicador['pratica_indicador_lider']) $indicadores[$indicador['pratica_indicador_id']]['pratica_indicador_estrategico_lider']=1;
	if (isset($campos['pratica_indicador_estrategico_excelencia']) && $indicador['pratica_indicador_estrategico'] && $indicador['pratica_indicador_excelencia']) $indicadores[$indicador['pratica_indicador_id']]['pratica_indicador_estrategico_excelencia']=1;
	}


$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=1');
$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
$sql->limpar();


$sql->adTabela('pratica_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs, pratica_item_oculto');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=1');
$itens=$sql->ListaChaveSimples('pratica_item_id');
$sql->limpar();


$sql->adTabela('pratica_marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=1');
$sql->adOrdem('pratica_criterio_numero');
$sql->adOrdem('pratica_item_numero');
$sql->adOrdem('pratica_marcador_letra');
$marcadores=$sql->Lista();
$sql->limpar();


$criterio_atual='';
$item_atual='';

if (!$dialogo) {
	echo '<form name="env" method="post">';
	echo '<input type="hidden" name="m" value="praticas" />';
	echo '<input type="hidden" name="a" value="indicador_ver" />';
	echo '<input type="hidden" name="pratica_indicador_id" value="" />';
	}

if (!$dialogo){
	echo '<table width="100%"><tr><td width="22">&nbsp;</td><td align="center"><font size="4"><center>Lista dos resultados nos marcadores</center></font></td><td width="22"><a href="javascript: void(0);" onclick ="frm_filtro.target=\'popup\'; frm_filtro.dialogo.value=1; frm_filtro.submit();">'.imagem('imprimir_p.png', 'Imprimir o Relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'</a></td></tr></table>';
	echo estiloTopoCaixa();
	}
else echo '<table width="750"><tr><td align="center"><font size="4"><center>Lista dos resultados nos marcadores</center></font></td></tr></table>';		
echo '<table border=1 cellpadding=0 cellspacing=0 '.($dialogo ? 'width="750"' : 'width="100%" class="std2"').'>';


echo '<tr><th>Campo</th>';
foreach ($campos as $chave => $linha)	{
	echo '<th valign="bottom" style="padding:1px;line-height:10px;">'.texto_vertical1($linha['pratica_regra_campo_texto'],$linha['pratica_regra_campo_texto'], $linha['pratica_regra_campo_descricao']).'</th>';
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

	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->adCampo('DISTINCT pratica_indicador_nos_marcadores.pratica_indicador_id');
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_marcador.pratica_marcador_id='.$dado['pratica_marcador_id']);
	$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	$grupo=$sql->carregarColuna();
	$sql->limpar();
	echo '<tr><td><table cellpadding=0 cellspacing=0><tr><td align="left" nowrap="nowrap" valign="top" style="width:20px">&nbsp;&nbsp;<b>'.$dado['pratica_marcador_letra'].'</b></td><td width="100%">'.($dado['pratica_marcador_extra'] ? dica('Informações Extras', $dado['pratica_marcador_extra']).$dado['pratica_marcador_texto'].dicaF() : $dado['pratica_marcador_texto']).'</td></tr></table></td><td colspan=20>&nbsp;</td></tr>';
	echo somatorio($grupo);
	}
echo '</table>';
if (!$dialogo) echo estiloFundoCaixa();	
else echo '<script>self.print();</script>';	




function somatorio($vetor_indicadores){
	global $campos, $dialogo, $indicadores;

	//inicialização dos dois vetores
	$soma=array();
	$nomes=array();
	foreach ($vetor_indicadores as $pratica_indicador_id){
		if ($indicadores[$pratica_indicador_id]['pode_ver']) echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(!$dialogo ? '<a href="javascript: void(0);" onclick="ver_indicador('.$pratica_indicador_id.')">' : '').$indicadores[$pratica_indicador_id]['pratica_indicador_nome'].(!$dialogo ? '</a>':'').'</td>';	
		else echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$indicadores[$pratica_indicador_id]['pratica_indicador_nome'].'</td>';	

		foreach ($campos as $chave => $linha) {
			echo '<td align=center>'.($indicadores[$pratica_indicador_id][$chave] ? imagem('icones/ponto.png') : imagem('icones/vazio16.gif')).'</td>';
			}
		}
	echo '</tr>';
	}
	

	

function texto_vertical1($texto, $titulo='', $sumario=''){
	$saida='';
	for ($i=0; $i< strlen($texto); $i++) $saida.=$texto[$i].'<br>';
	return dica($titulo,$sumario).$saida.dicaF();
	}
?>
<script language="javascript">
function ver_indicador(indicador_id){
	env.pratica_indicador_id.value=indicador_id;
	env.submit();
	}

function exibir(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
</script>

