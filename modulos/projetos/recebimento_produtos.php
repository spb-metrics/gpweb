<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require_once (BASE_DIR.'/modulos/projetos/recebimento.class.php');
$sql = new BDConsulta;
$projeto_recebimento_id= getParam($_REQUEST, 'projeto_recebimento_id', 0);

$obj = new CRecebimento();
$obj->load($projeto_recebimento_id);


$podeEditar=permiteEditarRecebimento($obj->projeto_recebimento_acesso,$projeto_recebimento_id);


if (!$projeto_recebimento_id) {
	$Aplic->setMsg('N�o foi passado um ID correto.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!$podeEditar) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}

$botoesTitulo = new CBlocoTitulo('Rela��o dos Produtos/Servi�os Entregues', 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=projetos&a=recebimento_ver&projeto_recebimento_id='.$projeto_recebimento_id, 'voltar','','Voltar','Ver os detalhes do recebimento de produtos/servi�os.');	
$botoesTitulo->mostrar();


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_recebimento_id" value="'.$projeto_recebimento_id.'" />';


echo estiloTopoCaixa();
echo '<table width="100%" cellspacing=0 cellpadding=0 width="100%" class="std">';  
echo '<tr><td colspan=2 align="left"><div id="combo_edicao">';
echo '<table cellpadding=0 cellspacing="2" width="100%"><tr><td><b>Produto/Servi�o</b></td></tr><tr><td valign=top><textarea name="projeto_recebimento_lista_produto" id="projeto_recebimento_lista_produto" class="textarea" style="width:100%"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_tipo(0);">'.imagem('icones/adicionar.png').'</a></td></tr></table>';
echo '</div></td></tr>';
echo '<tr><td colspan=2><div id="combo_tipos"></div></td></tr>';



echo '</table>';
echo '</td></tr></table>';
echo estiloFundoCaixa();

echo '</form>';
?>
<script language="javascript">


var projeto_recebimento_id=<?php echo $projeto_recebimento_id?>;

	


function inserir_tipo(projeto_recebimento_lista_id){
	xajax_inserir_tipo(projeto_recebimento_lista_id, projeto_recebimento_id, document.getElementById('projeto_recebimento_lista_produto').value);
	cancelar_edicao();
	xajax_lista_artefatos(projeto_recebimento_id);
	}

function mudar_ordem(ordem, projeto_recebimento_lista_id, direcao){
	xajax_mudar_ordem(ordem, projeto_recebimento_lista_id, direcao, projeto_recebimento_id);
	xajax_lista_artefatos(projeto_recebimento_id);
	}	
	
function excluir_tipo(projeto_recebimento_lista_id){
	xajax_excluir_tipo(projeto_recebimento_lista_id);
	xajax_lista_artefatos(projeto_recebimento_id);
	}	
	
function editar_tipo(projeto_recebimento_lista_id){
	xajax_editar_tipo(projeto_recebimento_lista_id);
	}	
	
xajax_lista_artefatos(projeto_recebimento_id);


function cancelar_edicao(){
	xajax_cancelar_edicao();
	}	


	
	
</script>