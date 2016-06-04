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

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);

if (!$ata_id) {
	$Aplic->setMsg('N�o foi passado um ID de '.$config['projeto'].' ao tentar editar o plano de ata.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}
echo '<script type="text/javascript" src="'.BASE_URL.'/lib/calendario2/datetimepicker_css.js"></script>';
require_once (BASE_DIR.'/modulos/projetos/ata.class.php');
$obj = new CAta();
$obj->load($ata_id);

if (!permiteEditarAta($obj->ata_acesso,$ata_id)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}

$botoesTitulo = new CBlocoTitulo('A��es da Ata de Reuni�o', 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=projetos&a=ata_ver&projeto_id='.$projeto_id.'&ata_id='.$ata_id, 'voltar','','Voltar','Ver os detalhes da ata de reuni�o');	
$botoesTitulo->mostrar();



$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');

$ata_acao_id= getParam($_REQUEST, 'ata_acao_id', null);
$ata_acao_texto=getParam($_REQUEST, 'ata_acao_texto', null);

$excluiracao=getParam($_REQUEST, 'excluiracao', '0');
$editaracao=getParam($_REQUEST, 'editaracao', '0');
$mudar_ata_acao_id=getParam($_REQUEST, 'mudar_ata_acao_id', null);
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
echo '<input type="hidden" name="ata_acao_id" value="" />';
echo '<input type="hidden" name="mudar_ata_acao_id" value="" />';
echo '<input type="hidden" name="excluiracao" value="" />';
echo '<input type="hidden" name="editaracao" value="" />';


	
echo estiloTopoCaixa();
echo '<table width="100%" cellspacing=0 cellpadding=0 width="100%" class="std">';  
echo '<tr><td colspan=2 align="left"><div id="combo_edicao">';


echo '</div></td></tr>';


echo '<tr><td colspan=2><div id="combo_acoes"></div></td></tr>';



echo '</table>';
echo '</td></tr></table>';
echo estiloFundoCaixa();

echo '</form>';
?>
<script language="javascript">

var ata_id=<?php echo $ata_id?>;
xajax_cancelar_edicao();
xajax_lista_artefatos(ata_id);

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Respons�vel', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id=<?php echo $objProjeto->projeto_cia ?>&usuario_id='+document.getElementById('ata_acao_responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id=<?php echo $objProjeto->projeto_cia ?>&usuario_id='+document.getElementById('ata_acao_responsavel').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('ata_acao_responsavel').value=usuario_id;		
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}	

function inserir_acao(ata_acao_id){
	xajax_inserir_acao(ata_acao_id, ata_id, document.getElementById('ata_acao_texto').value, document.getElementById('ini').value, document.getElementById('ata_acao_responsavel').value);
	cancelar_edicao();
	xajax_lista_artefatos(ata_id);
	}
	
function mudar_ordem(ordem, ata_acao_id, direcao){
	xajax_mudar_ordem(ordem, ata_acao_id, direcao, ata_id);
	xajax_lista_artefatos(ata_id);
	}	
	
function excluir_acao(ata_acao_id){
	xajax_excluir_acao(ata_acao_id);
	xajax_lista_artefatos(ata_id);
	}	
	
function editar_acao(ata_acao_id){
	xajax_editar_acao(ata_acao_id);
	}	

function cancelar_edicao(){
	xajax_cancelar_edicao();
	}
	
</script>