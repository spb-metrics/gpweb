<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require_once (BASE_DIR.'/modulos/projetos/recebimento.class.php');
$sql = new BDConsulta;
$projeto_recebimento_id= getParam($_REQUEST, 'projeto_recebimento_id', 0);

$obj = new CRecebimento();
$obj->load($projeto_recebimento_id);


$podeEditar=permiteEditarRecebimento($obj->projeto_recebimento_acesso,$projeto_recebimento_id);


if (!$projeto_recebimento_id) {
	$Aplic->setMsg('Não foi passado um ID correto.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!$podeEditar) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}

$botoesTitulo = new CBlocoTitulo('Relação dos Produtos/Serviços Entregues', 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=projetos&a=recebimento_ver&projeto_recebimento_id='.$projeto_recebimento_id, 'voltar','','Voltar','Ver os detalhes do recebimento de produtos/serviços.');	
$botoesTitulo->mostrar();


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_recebimento_id" value="'.$projeto_recebimento_id.'" />';


echo estiloTopoCaixa();
echo '<table width="100%" cellspacing=0 cellpadding=0 width="100%" class="std">';  
echo '<tr><td colspan=2 align="left"><div id="combo_edicao">';
echo '<table cellpadding=0 cellspacing="2" width="100%"><tr><td><b>Produto/Serviço</b></td></tr><tr><td valign=top><textarea name="projeto_recebimento_lista_produto" id="projeto_recebimento_lista_produto" class="textarea" style="width:100%"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_tipo(0);">'.imagem('icones/adicionar.png').'</a></td></tr></table>';
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