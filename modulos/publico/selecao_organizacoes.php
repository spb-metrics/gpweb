<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número RS 11802-5 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$mostrar_todos = getParam($_REQUEST, 'mostrar_todos', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$chamarVolta = getParam($_REQUEST, 'chamar_volta', null);
$cias_enviados = getParam($_REQUEST, 'cias_enviados', 0);
$cias_selecionados_id = getParam($_REQUEST, 'cias_id_selecionadas', '');
if (getParam($_REQUEST, 'cias_id_selecionadas')) $cias_selecionados_id = getParam($_REQUEST, 'cias_id_selecionadas');
if ($cias_enviados == 1) {
	$chamarVolta_string = !is_null($chamarVolta) ? "window.opener.$chamarVolta('$cias_selecionados_id');" : '';
	echo '<script language="javascript">if(parent && parent.gpwebApp){parent.gpwebApp._popupCallback(\''.$cias_selecionados_id.'\');} else {'.$chamarVolta_string.'self.close();}</script>';
	}
$cias_id = remover_invalido(explode(',', $cias_selecionados_id));
$cias_selecionados_id = implode(',', $cias_id);


$sql = new BDConsulta;

$sql->adTabela('cias');
$sql->adCampo('cia_superior');
$sql->adOnde('cia_id = '.(int)$Aplic->usuario_cia);
$cia_superior = $sql->resultado();
$sql->limpar();
//ver($Aplic->usuario_pode_lateral);
if ($Aplic->usuario_pode_todas_cias || $Aplic->usuario_super_admin){
	$sql->adTabela('cias');
	$sql->adCampo('cia_nome, cia_id');
	$sql->adOnde('cia_superior IS NULL OR cia_superior=cia_id');
	$sql->adOrdem('cia_nome ASC');
	$cias = $sql->ListaChave('cia_id');
	$sql->limpar();
	}
elseif ($Aplic->usuario_pode_superior || $Aplic->usuario_pode_lateral){
	if ($cia_superior){
		$sql->adTabela('cias');
		$sql->adCampo('cia_nome, cia_id');
		$sql->adOnde('cia_id = '.(int)$cia_superior);
		$cias = $sql->ListaChave('cia_id');
		$sql->limpar();
		}
	elseif(!$Aplic->usuario_pode_lateral) {
		$sql->adTabela('cias');
		$sql->adCampo('cia_nome, cia_id');
		$sql->adOnde('cia_id = '.(int)$Aplic->usuario_cia);
		$cias = $sql->ListaChave('cia_id');
		$sql->limpar();
		}
	else {
		$sql->adTabela('cias');
		$sql->adCampo('cia_nome, cia_id');
		$sql->adOnde('cia_cia = '.(int)$Aplic->usuario_cia);
		$sql->adOnde('cia_superior IS NULL OR cia_superior=cia_id');
		$sql->adOrdem('cia_nome ASC');
		$cias = $sql->ListaChave('cia_id');
		$sql->limpar();
		}
	}
else {
	$sql->adTabela('cias');
	$sql->adCampo('cia_nome, cia_id');
	$sql->adOnde('cia_id = '.(int)$Aplic->usuario_cia);
	$cias = $sql->ListaChave('cia_id');
	$sql->limpar();
	}

echo '<form method="post" name="frmSelecionaCia">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="dialogo" value="1" />';
if ($chamarVolta) echo '<input type="hidden" name="chamar_volta" value="'.$chamarVolta.'" />';
echo '<input type="hidden" name="cia_id" value="'.$cia_id.'" />';

echo estiloTopoCaixa();
$escolhidos=$cias_id;

echo '<table width="100%" class="std" cellspacing=0 cellpadding=0>';

foreach ($cias as $cia_id => $cia_data) {

	if (in_array($cia_id, $cias_id)){
		$marcado ='checked="checked"';
		unset($escolhidos[array_search($cia_id, $escolhidos)]);
		}
	else $marcado ='';
	if ($Aplic->usuario_pode_todas_cias || $Aplic->usuario_super_admin || ($Aplic->usuario_pode_superior && ($cia_id==$cia_superior)) || ($Aplic->usuario_pode_lateral && ($cia_id!=$cia_superior)) || ($cia_id==$Aplic->usuario_cia)) echo '<tr><td colspan=20><input type="checkbox" name="organizacao_id[]" id="secao'.$cia_id.'" value="'.$cia_id.'" '.$marcado.' /><label for="secao_'.$cia_id.'">' .$cia_data['cia_nome'].'</label></td></tr>';
	if ($Aplic->usuario_pode_todas_cias || $Aplic->usuario_super_admin ||
		($Aplic->usuario_pode_lateral && ($cia_id==$cia_superior)) ||
		($Aplic->usuario_pode_outra_cia && ($cia_id==$Aplic->usuario_cia))
		) subniveis($cia_id, '&nbsp;&nbsp;&nbsp;', false, ($cia_id==$Aplic->usuario_cia));
	elseif ($Aplic->usuario_pode_superior || $Aplic->usuario_pode_outra_cia)  subniveis($cia_id, '&nbsp;&nbsp;&nbsp;', true, ($cia_id==$Aplic->usuario_cia));
	}

foreach ($escolhidos as $cia_id => $cia_data) echo '<input type="hidden" name="organizacao_id[]" value="'.$cia_data.'" checked="checked"  />';
echo '<input name="cias_enviados" type="hidden" value="1" />';
echo '<input name="cias_id_selecionadas" type="hidden" value="'.$cias_selecionados_id.'" />';
echo '<tr><td align="left">'.botao('confirmar', '', '','','setCiaIDs();document.frmSelecionaCia.submit();','','',0).'</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';


function subniveis($cia_id, $subnivel, $cia_proprio=false, $ramo_pai=false){
	global $Aplic, $cias_id, $escolhidos, $sql;
	$sql->adTabela('cias');
	$sql->adCampo('cia_id, cia_nome');
	$sql->adOnde('cia_superior = '.(int)$cia_id . ' AND cia_superior != cia_id');
	$sql->adOrdem('cia_nome');
	$subordinados = $sql->lista();
	$sql->limpar();

	foreach($subordinados as $linha){
		if (in_array($linha['cia_id'], $cias_id)){
			$marcado ='checked="checked"';
			unset($escolhidos[array_search($linha['cia_id'], $escolhidos)]);
			}
		else $marcado ='';

		if (!$cia_proprio || ($linha['cia_id']==$Aplic->usuario_cia) || $ramo_pai) echo '<tr><td colspan=20>'.$subnivel.'<input type="checkbox" name="organizacao_id[]" id="secao'.$linha['cia_id'].'" value="'.$linha['cia_id'].'" '.$marcado.' /><label for="secao_'.$linha['cia_id'].'">' .$linha['cia_nome'].'</label></td></tr>';
		if ($Aplic->usuario_pode_todas_cias || $Aplic->usuario_super_admin || ($Aplic->usuario_pode_outra_cia && ($linha['cia_id']==$Aplic->usuario_cia ? true : $ramo_pai))) subniveis($linha['cia_id'], $subnivel.'&nbsp;&nbsp;&nbsp;', $cia_proprio, ($linha['cia_id']==$Aplic->usuario_cia ? true : $ramo_pai));
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

function setCiaIDs() {
	var campo = document.getElementsByName('organizacao_id[]');
	var cias_id_selecionadas = document.frmSelecionaCia.cias_id_selecionadas;
	var tmp = new Array();
	var contagem = 0;
	for (i = 0, i_cmp = campo.length; i < i_cmp; i++) {
		if (campo[i].checked && campo[i].value) tmp[contagem++] = campo[i].value;
		}
	cias_id_selecionadas.value = tmp.join(',');
	return cias_id_selecionadas;
	}
</script>
