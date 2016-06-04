<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

$cia_id = getParam($_REQUEST, 'cia_id', 0);
$chamar_volta = getParam($_REQUEST, 'chamar_volta', null);
$campo = getParam($_REQUEST, 'campo', '');
$tipo_dado = getParam($_REQUEST, 'tipo_dado', '');
$sql = new BDConsulta;
if ($cia_id){
	$sql->adTabela('cias');
	$sql->esqUnir('municipios','municipios','municipio_id=cia_cidade');
	$sql->adCampo('cia_nome, cia_endereco1, cia_endereco2, municipio_nome AS cia_cidade, cia_estado, cia_cep, cia_tel1, cia_tel2, cia_fax');
	$sql->adOnde('cia_id='.(int)$cia_id);
	$rs=$sql->Linha();	
	echo '<script language="javascript">';
	echo 'window.opener.'.$chamar_volta.'('.$campo.', "'.$tipo_dado.'", "'.$rs['cia_nome'].'", "'.$rs['cia_endereco1'].'", "'.$rs['cia_endereco2'].'", "'.$rs['cia_cidade'].'", "'.$rs['cia_estado'].'", "'.$rs['cia_cep'].'", "'.$rs['cia_tel1'].'", "'.$rs['cia_tel2'].'", "'.$rs['cia_fax'].'");';
	echo 'self.close();</script>';
	}


//modelos
$campo = getParam($_REQUEST, 'campo', 0);

echo '<form method="post" name="env">';
echo '<input type="hidden" name="m" value="publico" />';
echo '<input type="hidden" name="a" value="selecao_organizacao" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type=hidden name="tipo_dado" id="tipo_dado"  value="'.$tipo_dado.'">';
echo '<input type=hidden name="chamar_volta" id="tipo_dado"  value="'.$chamar_volta.'">';
echo '<input type=hidden name="campo" id="campo"  value="'.$campo.'">';


echo estiloTopoCaixa();
echo '<table width="100%" class="std" cellspacing=0 cellpadding=0>';
echo '<tr><td><table><tr><td align=right>'.ucfirst($config['organizacao']).':</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></form></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png').'</a></td></tr></table></td></tr>';
echo '</table>';
echo'</form>';
echo estiloFundoCaixa();

?>
<script language="javascript">
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}
</script>
