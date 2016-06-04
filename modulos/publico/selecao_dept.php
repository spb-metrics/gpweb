<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$mostrar_todos = getParam($_REQUEST, 'mostrar_todos', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$dept_id = getParam($_REQUEST, 'departamento_id', 0);
$chamarVolta = getParam($_REQUEST, 'chamar_volta', null);
$depts_enviados = getParam($_REQUEST, 'depts_enviados', 0);
$depts_selecionados_id = getParam($_REQUEST, 'depts_id_selecionados', '');
if (getParam($_REQUEST, 'depts_id_selecionados')) $depts_selecionados_id = getParam($_REQUEST, 'depts_id_selecionados');

if ($depts_enviados) {
	$chamarVolta_string = ($chamarVolta ? "window.opener.$chamarVolta('$depts_selecionados_id');" : '');
	echo '<script language="javascript">if(parent && parent.gpwebApp){parent.gpwebApp._popupCallback(\''.$depts_selecionados_id.'\');} else {'.$chamarVolta_string.'self.close();}</script>';
	}
$depts_id = remover_invalido(explode(',', $depts_selecionados_id));
$depts_selecionados_id = implode(',', $depts_id);

$sql = new BDConsulta;

$sql->adTabela('depts');
$sql->adCampo('dept_superior');
$sql->adOnde('dept_id = '.(int)$Aplic->usuario_dept);
$dept_superior = $sql->resultado();
$sql->limpar();
	
if ($Aplic->usuario_pode_todos_depts || $Aplic->usuario_super_admin){
	$sql->adTabela('depts');
	$sql->adCampo('dept_nome, dept_id');
	$sql->adOnde('dept_cia = '.(int)$cia_id);
	$sql->adOnde('dept_superior IS NULL OR dept_superior=0');
	$sql->adOrdem('dept_nome ASC');
	$depts = $sql->ListaChave('dept_id');
	$sql->limpar();
	}
elseif ($Aplic->usuario_pode_dept_superior || $Aplic->usuario_pode_dept_lateral){
	
	if ($dept_superior){
		$sql->adTabela('depts');
		$sql->adCampo('dept_nome, dept_id');
		$sql->adOnde('dept_id = '.(int)$dept_superior);
		$depts = $sql->ListaChave('dept_id');
		$sql->limpar();
		} 
	elseif(!$Aplic->usuario_pode_dept_lateral) {
		$sql->adTabela('depts');
		$sql->adCampo('dept_nome, dept_id');
		$sql->adOnde('dept_id = '.(int)$Aplic->usuario_dept);
		$depts = $sql->ListaChave('dept_id');
		$sql->limpar();
		}
	else {
		$sql->adTabela('depts');
		$sql->adCampo('dept_nome, dept_id');
		$sql->adOnde('dept_cia = '.(int)$Aplic->usuario_cia);
		$sql->adOnde('dept_superior IS NULL OR dept_superior=0');
		$sql->adOrdem('dept_nome ASC');
		$depts = $sql->ListaChave('dept_id');
		$sql->limpar();
		} 	
	}
else {
	$sql->adTabela('depts');
	$sql->adCampo('dept_nome, dept_id');
	$sql->adOnde('dept_id = '.(int)$Aplic->usuario_dept);
	$depts = $sql->ListaChave('dept_id');
	$sql->limpar();
	}

echo '<form method="post" name="frmSelecionaDept">';
echo '<input type="hidden" name="m" value="publico" />';
echo '<input type="hidden" name="a" value="selecao_dept" />';
echo '<input type="hidden" name="dialogo" value="1" />';
if ($chamarVolta) echo '<input type="hidden" name="chamar_volta" value="'.$chamarVolta.'" />';
echo '<input type="hidden" name="cia_id" value="'.$cia_id.'" />';

echo estiloTopoCaixa();
$escolhidos=$depts_id;

echo '<table width="100%" class="std" cellspacing=0 cellpadding=0>';
if ($Aplic->usuario_pode_todos_depts || $Aplic->usuario_super_admin) echo '<tr><td><table><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript: void(0);" onclick="document.frmSelecionaDept.depts_enviados.value=0; setDeptIDs(); document.frmSelecionaDept.submit();">'.imagem('icones/atualizar.png').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="cia_id" id="cia_id" value="'.$cia_id.'" />';

foreach ($depts as $dept_id => $secao_data) {

	if (in_array($dept_id, $depts_id)){
		$marcado ='checked="checked"';
		unset($escolhidos[array_search($dept_id, $escolhidos)]);
		}
	else $marcado ='';
	if ($Aplic->usuario_pode_todos_depts || $Aplic->usuario_super_admin || ($Aplic->usuario_pode_dept_superior && ($dept_id==$dept_superior)) || ($Aplic->usuario_pode_dept_lateral && ($dept_id!=$dept_superior)) || ($dept_id==$Aplic->usuario_dept)) echo '<tr><td colspan=20><input type="checkbox" name="departamento_id[]" id="secao'.$dept_id.'" value="'.$dept_id.'" '.$marcado.' /><label for="secao_'.$dept_id.'">' .$secao_data['dept_nome'].'</label></td></tr>';
	if ($Aplic->usuario_pode_todos_depts || $Aplic->usuario_super_admin || 
		($Aplic->usuario_pode_dept_lateral && ($dept_id==$dept_superior)) ||
		($Aplic->usuario_pode_dept_subordinado && ($dept_id==$Aplic->usuario_dept))
		) subniveis($dept_id, '&nbsp;&nbsp;&nbsp;', false, ($dept_id==$Aplic->usuario_dept));
	
	elseif ($Aplic->usuario_pode_dept_superior || $Aplic->usuario_pode_dept_subordinado)  subniveis($dept_id, '&nbsp;&nbsp;&nbsp;', true, ($dept_id==$Aplic->usuario_dept));
	}

foreach ($escolhidos as $dept_id => $secao_data) echo '<input type="hidden" name="departamento_id[]" value="'.$secao_data.'" checked="checked"  />';
echo '<input name="depts_enviados" type="hidden" value="1" />';
echo '<input name="depts_id_selecionados" type="hidden" value="'.$depts_selecionados_id.'" />';
echo '<tr><td align="left">'.botao('confirmar', '', '','','setDeptIDs();document.frmSelecionaDept.submit();','','',0).'</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';


function subniveis($dept_id, $subnivel, $dept_proprio=false, $ramo_pai=false){
	global $Aplic, $depts_id, $escolhidos, $sql;
	$sql->adTabela('depts');
	$sql->adCampo('dept_id, dept_nome');
	$sql->adOnde('dept_superior = '.(int)$dept_id);
	$sql->adOrdem('dept_ordem, dept_nome');
	$subordinados = $sql->lista();
	$sql->limpar();
	foreach($subordinados as $linha){
		if (in_array($linha['dept_id'], $depts_id)){
			$marcado ='checked="checked"';
			unset($escolhidos[array_search($linha['dept_id'], $escolhidos)]);
			}
		else $marcado ='';
		if (!$dept_proprio || ($linha['dept_id']==$Aplic->usuario_dept) || $ramo_pai) echo '<tr><td colspan=20>'.$subnivel.'<input type="checkbox" name="departamento_id[]" id="secao'.$linha['dept_id'].'" value="'.$linha['dept_id'].'" '.$marcado.' /><label for="secao_'.$linha['dept_id'].'">' .$linha['dept_nome'].'</label></td></tr>';
		if ($Aplic->usuario_pode_todos_depts || $Aplic->usuario_super_admin || ($Aplic->usuario_pode_dept_subordinado && ($linha['dept_id']==$Aplic->usuario_dept ? true : $ramo_pai))) subniveis($linha['dept_id'], $subnivel.'&nbsp;&nbsp;&nbsp;', $dept_proprio, ($linha['dept_id']==$Aplic->usuario_dept ? true : $ramo_pai));
		}
	}


function remover_invalido($arr) {
	$resultado = array();
	foreach ($arr as $val) if (!empty($val) && trim($val)) $resultado[] = $val;
	return $resultado;
	}
?>
<script language="javascript">
	
function mudar_om(){	
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om();"'); 	
	}		
	
function setDeptIDs() {
	var campo = document.getElementsByName('departamento_id[]');
	var depts_id_selecionados = document.frmSelecionaDept.depts_id_selecionados;
	var tmp = new Array();
	var contagem = 0;
	for (i = 0, i_cmp = campo.length; i < i_cmp; i++) {
		if (campo[i].checked && campo[i].value) tmp[contagem++] = campo[i].value;
		}
	depts_id_selecionados.value = tmp.join(',');
	return depts_id_selecionados;
	}
</script>
