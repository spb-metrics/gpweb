<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$sql = new BDConsulta;
$ata_id= getParam($_REQUEST, 'ata_id', 0);
$projeto_id= getParam($_REQUEST, 'projeto_id', 0);
$tipo=getParam($_REQUEST, 'tipo', null);

if (!$ata_id) {
	$Aplic->setMsg('N�o foi passado um ID de '.$config['projeto'].' ao tentar editar o plano de ata.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

require_once (BASE_DIR.'/modulos/projetos/ata.class.php');
$obj = new CAta();
$obj->load($ata_id);

if (!permiteEditarAta($obj->ata_acesso,$ata_id)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}

$botoesTitulo = new CBlocoTitulo(($tipo=='proxima' ? 'Pr�xima ': '').'Pauta da Ata de Reuni�o', 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=projetos&a=ata_ver&projeto_id='.$projeto_id.'&ata_id='.$ata_id, 'voltar','','Voltar','Ver os detalhes da ata de reuni�o');	
$botoesTitulo->mostrar();



$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');

$ata_pauta_id= getParam($_REQUEST, 'ata_pauta_id', null);
$ata_pauta_texto=getParam($_REQUEST, 'ata_pauta_texto', null);

$excluirpauta=getParam($_REQUEST, 'excluirpauta', '0');
$editarpauta=getParam($_REQUEST, 'editarpauta', '0');
$mudar_ata_pauta_id=getParam($_REQUEST, 'mudar_ata_pauta_id', null);
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');
$alterar=getParam($_REQUEST, 'alterar', '0');

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="ata_id" value="'.$ata_id.'" />';
echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="alterar" value="" />';

echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="ata_pauta_id" value="" />';
echo '<input type="hidden" name="mudar_ata_pauta_id" value="" />';
echo '<input type="hidden" name="excluirpauta" value="" />';
echo '<input type="hidden" name="editarpauta" value="" />';


	
echo estiloTopoCaixa();
echo '<table width="100%" cellspacing=0 cellpadding=0 width="100%" class="std">';  
echo '<tr><td colspan=2 align="left"><div id="combo_edicao">';
echo '<table cellpadding=0 cellspacing="2"><tr><td><b>Pauta</b></td<td></td></tr><tr><td valign=top><textarea name="ata_pauta_texto" id="ata_pauta_texto" style="width:750px;" class="textarea"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_pauta(0);">'.imagem('icones/adicionar.png').'</a></td></tr></table>';
echo '</div></td></tr>';


echo '<tr><td colspan=2><div id="combo_pautas"></div></td></tr>';



echo '</table>';
echo '</td></tr></table>';
echo estiloFundoCaixa();

echo '</form>';
?>
<script language="javascript">

var ata_id=<?php echo $ata_id?>;
var tipo='<?php echo $tipo?>';
xajax_lista_artefatos(ata_id, tipo);


function inserir_pauta(ata_pauta_id){
	xajax_inserir_pauta(ata_pauta_id, ata_id, document.getElementById('ata_pauta_texto').value, tipo);
	xajax_cancelar_edicao();
	xajax_lista_artefatos(ata_id, tipo);
	}
	
function mudar_ordem(ordem, ata_pauta_id, direcao){
	xajax_mudar_ordem(ordem, ata_pauta_id, direcao, ata_id, tipo);
	xajax_lista_artefatos(ata_id, tipo);
	}	
	
function excluir_pauta(ata_pauta_id){
	xajax_excluir_pauta(ata_pauta_id);
	xajax_lista_artefatos(ata_id, tipo);
	}	
	
function editar_pauta(ata_pauta_id){
	xajax_editar_pauta(ata_pauta_id);
	}	

function cancelar_edicao(){
	xajax_cancelar_edicao();
	}
	
</script>