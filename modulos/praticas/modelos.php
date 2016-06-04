<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

if (isset($_REQUEST['tipo_modelo'])) $Aplic->setEstado('tipo_modelo', getParam($_REQUEST, 'tipo_modelo', null));
$tipo_modelo = ($Aplic->getEstado('tipo_modelo') !== null ? $Aplic->getEstado('tipo_modelo') : 'completo');


$sql = new BDConsulta;	
$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();	
	
$tipos=array('completo'=>'Completo', 'simples'=>'Simples');	
	
echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="modelos" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';	
$pauta='<tr><td nowrap="nowrap" align="right">'.dica('Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta de Pontuação:'.dicaF().'</td><td>'.selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="document.env.submit()" class="texto" style="width:150px;"', $pratica_modelo_id).'</td></tr>';
$tipo='<tr><td nowrap="nowrap" align="right">'.dica('Tipo de Exibição', 'Utilize esta opção escolher a forma de exibição.').'Tipo de Exibição:'.dicaF().'</td><td>'.selecionaVetor($tipos, 'tipo_modelo', 'onchange="document.env.submit()" class="texto" style="width:150px;"', $tipo_modelo).'</td></tr>';


if (!$dialogo && $Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Pauta de Pontuação', 'modelos.png', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/modelos_p.png').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$pauta.$tipo.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	
	
	
	
	}
else {
	$botoesTitulo = new CBlocoTitulo('Pautas', 'modelos.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$pauta.$tipo.'</table>');
	$botoesTitulo->mostrar();
	}
$usuarios =array();
$indicadores =array();
$depts_selecionados = array();




echo estiloTopoCaixa();
echo '<table cellpadding="2" cellspacing=0 border=0 width="100%" class="std">';


echo '<tr><td colspan=2>';


$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
$sql->limpar();


$sql->adTabela('pratica_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs, pratica_item_oculto');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$itens=$sql->ListaChaveSimples('pratica_item_id');
$sql->limpar();

$sql->adTabela('pratica_marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra, pratica_marcador_evidencia, pratica_marcador_orientacao');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOrdem('pratica_criterio_numero');
$sql->adOrdem('pratica_item_numero');
$sql->adOrdem('pratica_marcador_letra');
$marcadores=$sql->Lista();
$sql->limpar();

$criterio_atual='';
$item_atual='';



echo '<table border=0 cellpadding="2" cellspacing=0 width="100%">';
foreach($marcadores as $dado){
	if ($dado['pratica_criterio_id']!=$criterio_atual){
		if ($criterio_atual) echo '</table></td></tr>';
		$criterio_atual=$dado['pratica_criterio_id'];
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_pontos'].'</td></tr>';
		$dentro .= '</table>';
		echo '<tr><td align="left" colspan=2 nowrap="nowrap"><b>'.dica('Dados Sobre o Critério', $dentro).'<a href="javascript: void(0);" onclick="expandir_colapsar(\'criterio_'.$criterio_atual.'\')">'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</a>'.dicaF().'</b></td></tr>';
		echo '<tr id="criterio_'.$criterio_atual.'"><td colspan=2><table cellpadding="2" cellspacing=0 width="100%">';
		
		
		if ($tipo_modelo=='completo' && $itens[$dado['pratica_item_id']]['pratica_item_oculto']) {
			echo '<tr><th colspan=2>Processos Gerenciais</th><th>Complementos</th><th>Evidências</th><th>Orientações</th></tr>';
			}
		
		}
		
	if ($dado['pratica_item_id']!=$item_atual){
		$item_atual=$dado['pratica_item_id'];
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($itens[$dado['pratica_item_id']]['pratica_item_obs']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_obs'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_pontos'].'</td></tr>';
		$dentro .= '</table>';
		if (!$itens[$dado['pratica_item_id']]['pratica_item_oculto']) {
			echo '<tr><td align="left" colspan=2 nowrap="nowrap"><b>'.($tipo_modelo!='completo' ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : '').dica('Dados Sobre o Ítem', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].dicaF().'</b></td></tr>';
			if ($tipo_modelo=='completo') echo '<tr><th colspan=2>Processos Gerenciais</th><th>Complementos</th><th>Evidências</th><th>Orientações</th></tr>';
			}
		
		}
	
	if ($tipo_modelo=='completo') {
		echo '<tr><td valign="top"><b>'.$dado['pratica_marcador_letra'].'.</b></td><td valign="top">'.$dado['pratica_marcador_texto'].'</td><td valign="top">'.$dado['pratica_marcador_extra'].'</td><td valign="top">'.$dado['pratica_marcador_evidencia'].'</td><td valign="top">'.$dado['pratica_marcador_orientacao'].'</td></tr>';
		}
	else{
		echo '<tr><td align="left" nowrap="nowrap" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$dado['pratica_marcador_letra'].'.&nbsp;</b></td><td id="caixa_'.$dado['pratica_marcador_id'].'" width="100%" valign="middle">'.$dado['pratica_marcador_texto'].'</td></tr>';
		if ($dado['pratica_marcador_extra']) echo '<tr><td align="left" nowrap="nowrap" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="100%" valign="middle">'.$dado['pratica_marcador_extra'].'</td></tr>';
		}
	}
if ($criterio_atual) echo '</table>';	
else echo '<tr><td>Selecione uma pauta</td></tr>';
echo '</table>';

echo '</td></tr>';

echo '</table>';

echo '</table>';

echo '</form>';
echo estiloFundoCaixa();
?>
