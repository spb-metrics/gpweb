<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf, $dialogo;
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


echo '<table width="100%" border=0 cellpadding="2" cellspacing=0><tr><td><table><tr>';
echo '<td>&nbsp; &nbsp;</td><td style="border-style:solid;border-width:1px" bgcolor="#d8ffcf">&nbsp; &nbsp;</td><td>'.dica('Poucos Indicadores', 'Poucos indicadores atendem ao requisito.').'Poucos 0-24%'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#edffcf">&nbsp; &nbsp;</td><td>'.dica('Muitos Indicadores', 'Muitos indicadores atendem ao requisito.').'Muitos 25-49%'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#feffcf">&nbsp; &nbsp;</td><td>'.dica('Maioria dos Indicadores', 'Maioria dos indicadores atendem ao requisito.').'Maioria 50-74%'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffe9cf">&nbsp; &nbsp;</td><td>'.dica('Quase Todos os Indicadores', 'Quase todos os indicadores atendem ao requisito.').'Quase Todos 75-99%'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#ffd9cf">&nbsp; &nbsp;</td><td>'.dica('Todos os Indicadores', 'Todos os indicadores atendem ao requisito.').'Todos 100%'.dicaF().'</td>';
echo '</tr></table></td></tr></table>';


$criterio_atual='';
$item_atual='';


if (!$dialogo){
	echo '<table width="100%"><tr><td width="22">&nbsp;</td><td align="center"><font size="4"><center>Oportunidades de melhoria nos resultados</center></font></td><td width="22"><a href="javascript: void(0);" onclick ="frm_filtro.target=\'popup\'; frm_filtro.dialogo.value=1; frm_filtro.submit();">'.imagem('imprimir_p.png', 'Imprimir o Relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'</a></td></tr></table>';
	echo estiloTopoCaixa();
	}
else echo '<table width="100%"><tr><td align="center"><font size="4"><center>Oportunidades de melhoria nos resultados</center></font></td></tr></table>';		
echo '<table border=1 cellpadding=0 cellspacing=0 width="100%" '.($dialogo ? '' : 'class="std2"').'>';

echo '<tr><th>Campo</th><th valign="bottom" style="padding:1px;line-height:10px;">'.texto_vertical1('quantidade','Quantidade de Indicadores','Total de indicadores que atendem '.$config['genero_marcador'].'s '.$config['marcadores'].'.').'</th>';
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
		$sql->adTabela('pratica_indicador_nos_marcadores');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
		$sql->adCampo('DISTINCT pratica_indicador_nos_marcadores.pratica_indicador_id');
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
		$sql->adOnde('pratica_item_criterio='.(int)$criterio_atual);
		$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$ano);
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
		
		
	
		$sql->adTabela('pratica_indicador_nos_marcadores');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
		$sql->adCampo('DISTINCT pratica_indicador_nos_marcadores.pratica_indicador_id');
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$sql->adOnde('pratica_marcador_item='.$item_atual);
		$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
		$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$ano);
		$grupo=$sql->Lista();
		$sql->limpar();
		
		
		if (!$itens[$dado['pratica_item_id']]['pratica_item_oculto']) echo '<tr><td align="left" nowrap="nowrap">&nbsp;'.dica('Dados Sobre o Ítem', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].dicaF().'</td>'.somatorio($grupo).'</tr>';
		}
	
	
	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	$sql->adCampo('DISTINCT pratica_indicador_nos_marcadores.pratica_indicador_id');
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_marcador.pratica_marcador_id='.$dado['pratica_marcador_id']);
	$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$ano);
	$grupo=$sql->Lista();
	$sql->limpar();
	
	echo '<tr><td><table cellpadding=0 cellspacing=0><tr><td align="left" nowrap="nowrap" valign="top" style="width:20px">&nbsp;&nbsp;<b>'.$dado['pratica_marcador_letra'].'</b></td><td width="100%">'.($dado['pratica_marcador_extra'] ? dica('Informações Extras', $dado['pratica_marcador_extra']).$dado['pratica_marcador_texto'].dicaF() : $dado['pratica_marcador_texto']).'</td></tr></table></td>'.somatorio($grupo).'</tr>';
	}
echo '</table>';
if (!$dialogo) echo estiloFundoCaixa();	
else echo '<script>self.print();</script>';	



function somatorio($vetor_indicadores){
	global $indicadores, $campos;
	$soma=array();
	$nomes=array();
	foreach ($campos as $chave => $nome_campo){
		$soma[$chave]=0;
		$nomes[$chave]=null;
		}
	$nomes['pratica_indicador_nome']=null;
	


	foreach($vetor_indicadores as $chave => $pratica_indicador_id){
		foreach ($campos as $chave => $nome_campo){
			if (!$indicadores[$pratica_indicador_id['pratica_indicador_id']][$chave]) {$soma[$chave]++; $nomes[$chave][$pratica_indicador_id['pratica_indicador_id']]=$indicadores[$pratica_indicador_id['pratica_indicador_id']]['pratica_indicador_nome'];}
			}
		$nomes['pratica_indicador_nome'][$pratica_indicador_id['pratica_indicador_id']]=$indicadores[$pratica_indicador_id['pratica_indicador_id']]['pratica_indicador_nome'];
		}
	$total=count($vetor_indicadores);
	$saida='';
	foreach ($campos as $chave => $nome_campo) $saida.=formata_soma($total, $soma[$chave], $nomes[$chave]);
	return formata_nomes($nomes['pratica_indicador_nome']).$saida.'</td>';
	}
	

function formata_nomes($nomes=array()){
	global $indicadores;
	static $n=0;
	$n++;
	$saida='';
	$quantidade=count($nomes);
	if ($quantidade){
		foreach($nomes as $pratica_indicador_id => $pratica_indicador_nome) {
			if ($indicadores[$pratica_indicador_id]['pode_ver']) $saida.='<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&pratica_indicador_id='.$pratica_indicador_id.'\');">'.$pratica_indicador_nome.'</a><br>';
			else $saida.=$pratica_indicador_nome.'<br>';
			}
		$saida='<table align="center"><tr><td nowrap="nowrap"><a href="javascript: void(0);" onclick="exibir(\'n_'.$n.'\')">'.dica('Indicadores', 'Clique para visualizar os indicadores').$quantidade.dicaF().'</a></td></tr><tr id="n_'.$n.'" style="display:none"><td nowrap="nowrap">'.$saida.'</td></tr></table>';
		return '<td style="text-align:center;">'.$saida.'</td>';
		}
	else return '<td style="text-align:center;">'.$quantidade.dicaF().'</td>';
	}		
	
	
function formata_soma($total, $quantidade, $nomes=array()){
	global $config, $indicadores;
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
		foreach($nomes as $pratica_indicador_id => $pratica_indicador_nome) {
			if ($indicadores[$pratica_indicador_id]['pode_ver']) $saida.='<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&pratica_indicador_id='.$pratica_indicador_id.'\');">'.$pratica_indicador_nome.'</a><br>';
			else $saida.=$pratica_indicador_nome.'<br>';
			}
		$saida='<table align="center"><tr><td nowrap="nowrap"><a href="javascript: void(0);" onclick="exibir(\'c_'.$i.'\')">'.dica('Percentagem', $percentagem.'%<br><br>Clique para visualizar '.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas']).$quantidade.dicaF().'</a></td></tr><tr id="c_'.$i.'" style="display:none"><td nowrap="nowrap">'.$saida.'</td></tr></table>';
		return '<td style="text-align:center; background:#'.$cor.'">'.$saida.'</td>';
		}
	else{
		return '<td style="text-align:center; background:#'.$cor.'">'.dica('Percentagem', $percentagem.'%').'&nbsp;'.$quantidade.'&nbsp;'.dicaF().'</td>';
		}	
	}
	

function texto_vertical1($texto, $titulo='', $sumario=''){
	$saida='';
	for ($i=0; $i< strlen($texto); $i++) $saida.=$texto[$i].'<br>';
	return dica($titulo,$sumario).$saida.dicaF();
	}
?>
<script language="javascript">


function exibir(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
</script>

