<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$campo=getParam($_REQUEST, 'campo', '');
$item=getParam($_REQUEST, 'item', '');
$tab=getParam($_REQUEST, 'tab', 0);
$criterio=getParam($_REQUEST, 'criterio', 0);
$resultado=getParam($_REQUEST, 'resultado', 0);

$ano=getParam($_REQUEST, 'ano', date('Y'));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
$usuario_id = ($Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0);
$pratica_id = $Aplic->getEstado('CalIdxPratrica', 0);
$pratica_indicador_id = $Aplic->getEstado('CalIdxIndicador', 0);
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);


$sql = new BDConsulta();

$sql->adTabela('pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_texto');
$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_campo_nome=\''.$campo.'\'');
$marcador=$sql->Resultado();
$sql->limpar();


if ($resultado){
	
	include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
	
	
	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_indicador_nos_marcadores.pratica_marcador_id');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito'); 
	if ($item) $sql->adOnde('pratica_item_id='.$item); 
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_indicador_resultado=1');
	if ($usuario_id) $sql->adOnde('pratica_indicador_responsavel='.$usuario_id);
	if ($cia_id) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pratica_indicador_depts.dept_id='.$dept_id);

	$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_nome, pratica_indicador_requisito_lider, pratica_indicador_requisito_excelencia, pratica_indicador_requisito_atendimento, pratica_indicador_requisito_relevante, pratica_indicador_agrupar, pratica_indicador_sentido');
	$sql->adOrdem('pratica_indicador_nome ASC');
	$indicadores=$sql->Lista();
	$sql->limpar();
	
	
	foreach ($indicadores as $chave => $linha){
		$obj_indicador = new Indicador($linha['pratica_indicador_id']);
		

		$valor=$obj_indicador->Valor_atual();
		
		if ($campo=='pratica_indicador_requisito_lider') $indicadores[$chave]['campo']=$linha['pratica_indicador_requisito_lider'];
		elseif ($campo=='pratica_indicador_requisito_excelencia') $indicadores[$chave]['campo']=$linha['pratica_indicador_requisito_excelencia'];
		elseif ($campo=='pratica_indicador_requisito_atendimento') $indicadores[$chave]['campo']=$linha['pratica_indicador_requisito_atendimento'];
		elseif ($campo=='pratica_indicador_requisito_relevante') $indicadores[$chave]['campo']=$linha['pratica_indicador_requisito_relevante'];
		elseif ($campo=='pratica_indicador_requisito_favoravel') $indicadores[$chave]['campo']=$linha['pratica_indicador_requisito_favoravel'];
		elseif ($campo=='pratica_indicador_requisito_superior') $indicadores[$chave]['campo']=$linha['pratica_indicador_requisito_superior'];
		}
			

	
	echo estiloTopoCaixa();
	echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std">';
	echo '<tr><td><h1>'.$marcador.'</h1></td></tr>';
	foreach ($indicadores as $linha){
		echo '<tr><td><table><tr><td style="background-color:'.($linha['campo'] ? '#f2f0ec' : '#fdc5c5').';"><a href="javascript: void(0);" onclick="ir_para_indicador('.$linha['pratica_indicador_id'].');">'.$linha['pratica_indicador_nome'].'</a></td></tr></table></td></tr>';
		}
	echo '<td valign="top" align="center" width="75%"><table><tr><td style="border-style:solid;border-width:1px; background-color: #fdc5c5;">&nbsp;&nbsp;</td><td nowrap="nowrap">Não atende '.strtolower($marcador).'</td></tr></table></td>';	
	echo '</table>';	
	echo estiloFundoCaixa();
	}
else {	
	$sql->adTabela('praticas');
	$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica =praticas.pratica_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_nos_marcadores.marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->esqUnir('pratica_depts', 'pratica_depts', 'pratica_depts.pratica_id=praticas.pratica_id');
	$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
	if ($ano) $sql->adOnde('pratica_requisito.ano='.$ano); 
	
	if ($item)$sql->adOnde('pratica_item_id='.$item);
	if ($cia_id) $sql->adOnde('pratica_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pratica_depts.dept_id='.$dept_id);
	if ($pratica_modelo_id) $sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	if ($usuario_id) $sql->adOnde('pratica_responsavel='.$usuario_id);
	$sql->adCampo('DISTINCT praticas.pratica_id, pratica_acesso, pratica_nome, '.$campo);
	if ($criterio) $sql->adOnde('pratica_criterio_id='.(int)$criterio);
	$sql->adOrdem($campo.' ASC, pratica_nome ASC');
	$praticas=$sql->Lista();
	$sql->limpar();
	echo estiloTopoCaixa();
	echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std">';
		echo '<tr><td><h1>'.$marcador.'</h1></td></tr>';
	foreach ($praticas as $linha){
		echo '<tr><td><table><tr><td style="background-color:'.($linha[$campo] ? '#f2f0ec' : '#fdc5c5').';"><a href="javascript: void(0);" onclick="ir_para('.$linha['pratica_id'].');">'.$linha['pratica_nome'].'</a></td></tr></table></td></tr>';
		}
	echo '<td valign="top" align="center" width="75%"><table><tr><td style="border-style:solid;border-width:1px; background-color: #fdc5c5;">&nbsp;&nbsp;</td><td nowrap="nowrap">Não atende '.strtolower($marcador).'</td></tr></table></td>';	
	echo '</table>';	
	echo estiloFundoCaixa();
	}
	
	
?>
<script language="javascript">
function ir_para(pratica_id){
	window.opener.url_passar(0, "m=praticas&a=pratica_ver&pratica_id="+pratica_id);
	self.close();
	}
	
function ir_para_indicador(pratica_indicador_id){
	window.opener.url_passar(0, "m=praticas&a=indicador_ver&pratica_indicador_id="+pratica_indicador_id);
	self.close();
	}	
</script>	