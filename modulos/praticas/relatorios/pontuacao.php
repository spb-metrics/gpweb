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
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

include_once BASE_DIR.'/modulos/praticas/pauta.class.php';
$pauta=new Cpauta($cia_id, $pratica_modelo_id, $ano);

$sql = new BDConsulta;

$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
$sql->limpar();


$sql->adTabela('pratica_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs, pratica_item_oculto');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
$itens=$sql->ListaChaveSimples('pratica_item_id');
$sql->limpar();


$sql->adTabela('pratica_marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
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

$pontuacao_geral=0;
$total_geral =0;
$criterio_atual='';
$item_atual='';
$qnt=0;
$item=array();

$sql->adTabela('pratica_mod_campo');
$sql->adCampo('DISTINCT pratica_mod_campo_nome');
$sql->adOnde('pratica_mod_campo_modelo='.(int)$pratica_modelo_id);
$resultados=$sql->Lista();
$sql->limpar();

$campo=array();
foreach($resultados as $resultado) $campo[$resultado['pratica_mod_campo_nome']]=1;


$sql->adTabela('pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
$lista=$sql->Lista();
$sql->limpar();	
$regra_campo=array();
foreach($lista as $linha) $regra_campo[$linha['pratica_regra_campo_nome']]=array('nome' => $linha['pratica_regra_campo_texto'], 'descricao' => $linha['pratica_regra_campo_descricao']);


if (!$dialogo){
	echo '<table width="100%"><tr><td width="22">&nbsp;</td><td align="center"><font size="4"><center>Pontuação</center></font></td><td width="22"><a href="javascript: void(0);" onclick ="frm_filtro.target=\'popup\'; frm_filtro.dialogo.value=1; frm_filtro.submit();">'.imagem('imprimir_p.png', 'Imprimir o Relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'</a></td></tr></table>';
	echo estiloTopoCaixa();
	}
else echo '<table width="100%"><tr><td align="center"><font size="4"><center>Pontuação</center></font></td></tr></table>';		

echo '<table border=1 cellpadding=0 cellspacing=0 width="100%" '.($dialogo ? '' : 'class="std2"').'>';
echo '<tr><th>Campo</th><th valign="bottom" style="padding:1px;line-height:10px;">'.texto_vertical1('pontuação', 'Pontuação', 'Resultado da multiplicação da percentagem obtida com a pontuação máxima.').'</th><th valign="bottom" style="padding:1px;line-height:10px;">'.texto_vertical1('percentagem','Percentagem','Percentagem obtida baseado n'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' relacionad'.($config['genero_pratica']=='a' ? 'as': 'os').' com '.$config['genero_marcador'].'s '.$config['marcadores'].'.').'</th>';

foreach($pauta->campos as $chave => $linha) if (!$linha['resultado'])  echo '<th valign="bottom" style="padding:1px;line-height:10px;">'.texto_vertical1($regra_campo[$chave]['nome'], $regra_campo[$chave]['nome'],$regra_campo[$chave]['descricao']).'</th>';


echo '</tr>';



$criterio_atual='';
$item_atual='';

foreach($marcadores as $dado){
	if ($dado['pratica_criterio_id']!=$criterio_atual){
		$criterio_atual=$dado['pratica_criterio_id'];
		
		$dentro = '<table cellspacing="4" cellpadding="2" width="100%">';
		if ($criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_pontos'].'</td></tr>';
		$dentro .= '</table>';
		
		$sql->adTabela('pratica_item');
		$sql->adCampo('DISTINCT pratica_item_id');
		$sql->adOnde('pratica_item_criterio='.(int)$criterio_atual);
		$grupo_itens=$sql->carregarColuna();
		$sql->limpar();
		$valor=0;
		foreach($grupo_itens as $item_id) $valor+=$pauta->pontuacao_item[$item_id];
		$pontuacao_geral+=$valor;
		
		echo '<tr><td align="left" nowrap="nowrap" style="font-weight:bold;">'.dica('Dados Sobre o Critério', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].dicaF().'</td><td align="center" style="font-weight:bold;">'.$valor.'</td><td colspan=20>&nbsp;</td></tr>';
		}
		
	if ($dado['pratica_item_id']!=$item_atual){
		$item_atual=$dado['pratica_item_id'];
		$dentro = '<table cellspacing="4" cellpadding="2" width="100%">';
		if ($itens[$dado['pratica_item_id']]['pratica_item_obs']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_obs'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_pontos'].'</td></tr>';
		$dentro .= '</table>';

		echo '<tr><td align="left" nowrap="nowrap">'.(!$itens[$dado['pratica_item_id']]['pratica_item_oculto'] ? dica('Dados Sobre o Ítem', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].dicaF() : '').'</td>';
		echo imprimir_item($item_atual, $itens[$item_atual]['pratica_item_pontos'], !$itens[$dado['pratica_item_id']]['pratica_item_oculto'] );
		echo '</tr>';
			
		}
	}

$total_geral+=$pontuacao_geral;
echo '<tr><td align="center" nowrap="nowrap">'.dica('Pontuação dos Processos Gerenciais', 'Total de pontos auferidos nos processos gerenciais').'<h1>Total</h1></td><td colspan=20 align="center" ><h1>'.$pontuacao_geral.' Pontos</h1></td></tr>';

echo '</table>';
$pontuacao_geral=0;	
$pontuacao=array();
$no_subitem=array();
$subitem=array();
$nota_subitem=array();
$pontuacao_geral=0;
	
	
$sql->adTabela('pratica_regra');
$sql->adCampo('pratica_regra_campo, pratica_regra_percentagem, pratica_regra_valor, subitem');
$sql->adOnde('pratica_modelo_id='.(int)$pratica_modelo_id);
$regras=$sql->Lista();
$sql->limpar();	
	
	
$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();	


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
$qnt=0;

echo '<table border=1 cellpadding=0 cellspacing=0 width="100%" '.($dialogo ? '' : 'class="std2"').'>';
echo '<tr><th>Campo</th><th valign="bottom" style="padding:1px;line-height:10px;">'.texto_vertical1('pontuação', 'Pontuação', 'Resultado da multiplicação da percentagem obtida com a pontuação máxima.').'</th><th valign="bottom" style="padding:1px;line-height:10px;">'.texto_vertical1('percentagem','Percentagem','Percentagem obtida baseado nos indicadores relacionados com '.$config['genero_marcador'].'s '.$config['marcadores'].'.').'</th>';
foreach($pauta->campos as $chave => $linha) if ($linha['resultado'])  echo '<th valign="bottom" style="padding:1px;line-height:10px;">'.texto_vertical1($regra_campo[$chave]['nome'], $regra_campo[$chave]['nome'],$regra_campo[$chave]['descricao']).'</th>';
echo '</tr>';


$criterio_atual='';
$item_atual='';

foreach($marcadores as $dado){
	if ($dado['pratica_criterio_id']!=$criterio_atual){
		$criterio_atual=$dado['pratica_criterio_id'];
		$dentro = '<table cellspacing="4" cellpadding="2" width="100%">';
		if ($criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_pontos'].'</td></tr>';
		$dentro .= '</table>';
		
		$sql->adTabela('pratica_item');
		$sql->adCampo('DISTINCT pratica_item_id');
		$sql->adOnde('pratica_item_criterio='.(int)$criterio_atual);
		$grupo_itens=$sql->carregarColuna();
		$sql->limpar();
		$valor=0;
		foreach($grupo_itens as $item_id) $valor+=$pauta->pontuacao_item[$item_id];
		$pontuacao_geral+=$valor;
		
		
		echo '<tr><td align="left" nowrap="nowrap" style="font-weight:bold;">'.dica('Dados Sobre o Critério', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].dicaF().'</td><td align="center" style="font-weight:bold;">'.$valor.'<td colspan=20>&nbsp;</td></tr>';
		}
	if ($dado['pratica_item_id']!=$item_atual){
		
		$item_atual=$dado['pratica_item_id'];
		$dentro = '<table cellspacing="4" cellpadding="2" width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_obs'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_pontos'].'</td></tr>';
		$dentro .= '</table>';

		echo '<tr><td align="left" nowrap="nowrap">'.(!$itens[$dado['pratica_item_id']]['pratica_item_oculto'] ? dica('Dados Sobre o Ítem', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].dicaF() : '').'</td>'.imprimir_item2($item_atual, $itens[$item_atual]['pratica_item_pontos'], !$itens[$dado['pratica_item_id']]['pratica_item_oculto']).'</tr>';
		
		}
	}
$total_geral+=$pontuacao_geral;
echo '<tr><td align="center" nowrap="nowrap">'.dica('Pontuação dos Resultados', 'Total de pontos auferidos nos resultados gerenciais').'<h1>Total</h1></td><td colspan=20 align="center" ><h1>'.$pontuacao_geral.' Pontos</h1></td></tr>';
$sql->adTabela('pratica_maturidade');
$sql->adCampo('descricao');
$sql->adOnde('minimo<='.$total_geral);
$sql->adOnde('maximo>='.$total_geral);
$sql->adOnde('pratica_modelo_id='.(int)$pratica_modelo_id);
$maturidade=$sql->Resultado();
$sql->limpar();
echo '<tr><td align="center" nowrap="nowrap">'.dica('Pontuação dos Resultados', 'Total de pontos auferidos nos resultados gerenciais').'<h1>Total Final</h1></td><td colspan=20 align="center" >'.($maturidade ? dica('Nível de Maturidade',$maturidade):'').'<h1>'.$total_geral.' Pontos</h1>'.($maturidade ? dicaF():'').'</td></tr>';
echo '</table>';
if (!$dialogo) echo estiloFundoCaixa();	
else echo '<script>self.print();</script>';	
	

function imprimir_item2($itematual, $pontos, $ver_total=true){
	global $pauta, $campo, $nota_subitem; 
	$saida= '<td style="text-align: center; font-weight:bold;">'.($ver_total ? $pauta->pontuacao_item[$itematual] : '').'</td><td style="text-align: center; font-weight:bold;">'.$pauta->porcentagem_item[$itematual].'</td>';
	
	foreach($pauta->campos as $chave => $linha) if ($linha['resultado']) {
		if ($chave=='pratica_continuada') ver($linha);
		$saida.= '<td style="text-align: center;">'.$pauta->resultados[$itematual][$chave].'</td>';
		}
	
	return $saida;
	}



function imprimir_item($itematual, $pontos, $ver_total=true){
	global $pauta, $campo, $nota_subitem; 
	$saida= '<td style="text-align: center; font-weight:bold;">'.($ver_total ? $pauta->pontuacao_item[$itematual] : '').'</td><td style="text-align: center; font-weight:bold;">'.$pauta->porcentagem_item[$itematual].'</td>';
	
	foreach($pauta->campos as $chave => $linha) if (!$linha['resultado']) $saida.= '<td style="text-align: center;">'.$pauta->pontuacao[$itematual][$chave].'</td>';
	
	return $saida;
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

