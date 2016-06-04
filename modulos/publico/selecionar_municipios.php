<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('N�o deveria acessar este arquivo diretamente.');


$depurar = false;
$chamarVolta = getParam($_REQUEST, 'chamar_volta', 0);
$edicao=getParam($_REQUEST, 'edicao', 0);
$estado_sigla=getParam($_REQUEST, 'estado_sigla', '');
$selecionados_id = getParam($_REQUEST, 'valores', '');

if (getParam($_REQUEST, 'valores')) $selecionados_id = getParam($_REQUEST, 'valores');

if (getParam($_REQUEST, 'enviado', 0)) {
	$chamarVolta_string = !is_null($chamarVolta) ? "window.opener.$chamarVolta('$selecionados_id');" : '';
	echo '<script language="javascript">if(parent && parent.gpwebApp){parent.gpwebApp._popupCallback(\''.$selecionados_id.'\');} else {'.$chamarVolta_string.'self.close();}</script>';
	}


$ids = remover_invalido(explode(',', $selecionados_id));
$selecionados_id = implode(',', $ids);

$escolhidos=$ids;

$estado=array('' => '');

$sql = new BDConsulta;
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();


echo '<script language="javascript">function setFechar(chave, valor){if (chave!=0) window.opener.'.$chamarVolta.'(chave, valor); else window.opener.'.$chamarVolta.'(null, ""); window.close(); }</script>';

echo '<form name="env" method="POST">';
echo '<input type="hidden" name="m" value="publico" />';
echo '<input type="hidden" name="a" value="selecionar_municipios" />';
echo '<input type="hidden" name="chamar_volta" value="'.$chamarVolta.'" />';
echo '<input type="hidden" name="enviado" value="0" />';


$sql->adTabela('municipios');
$sql->adCampo('municipio_id, municipio_nome');
$sql->adOnde('estado_sigla = "'.$estado_sigla.'"');
$sql->adOrdem('municipio_nome');
$lista = $sql->ListaChave();
$sql->limpar();

echo '<b>Selecionar Munic�pios:</b>';
echo estiloTopoCaixa();
echo '<table class="std" width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td colspan=20><table><tr><td>'.dica('Estado', 'Selecione seu estado.').'Estado:'.dicaF().'</td><td colspan="2">'.selecionaVetor($estado, 'estado_sigla',  'class="texto" onchange="setSelecionadosIDs(); document.env.submit();"', $estado_sigla).'</td></tr></table></td></tr>';

if (count($lista) > 0) foreach ($lista as $chave => $val) {
	
	if (in_array($chave, $ids)){
		$marcado ='checked="checked"';
		unset($escolhidos[array_search($chave, $escolhidos)]);
		}
	else $marcado ='';
	
	
	echo '<tr><td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$chave.'" value="'.$chave.'" '.$marcado.' /></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$val.'</td></tr>';
	}
else 	echo '<tr><td colspan=20><a href="javascript:setFechar(0, \'\');">N�o foi encontrado nenhum munic�pio</a></td></tr>';


echo '<tr><td colspan=20><table width=100%><tr><td width=100%>'.botao('confirmar', '', '','','env.enviado.value=1; setSelecionadosIDs(); env.submit();').'</td></tr></table></td></tr>';


echo '</table>';

foreach ($escolhidos as $escolhido_id) echo '<input type="hidden" name="campos[]" value="'.$escolhido_id.'" checked="checked"  />';
echo '<input name="valores" type="hidden" value="'.$selecionados_id.'" />';
echo '</form>';
echo estiloFundoCaixa();

function remover_invalido($arr) {
	$resultado = array();
	foreach ($arr as $val) if (!empty($val) && trim($val)) $resultado[] = $val;
	return $resultado;
	}
?>

<script language="javascript">

function setSelecionadosIDs() {
	var campo = document.getElementsByName('campos[]');
	var valores = document.env.valores;
	var tmp = new Array();
	var contagem = 0;
	for (i = 0, i_cmp = campo.length; i < i_cmp; i++) {
		if (campo[i].checked && campo[i].value) tmp[contagem++] = campo[i].value;
		}
	valores.value = tmp.join(',');
	return valores;
	}
</script>