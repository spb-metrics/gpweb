<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$sql = new BDConsulta;
$projeto_id= getParam($_REQUEST, 'projeto_id', 0);

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);

if (!$projeto_id) {
	$Aplic->setMsg('Não foi passado um ID de '.$config['projeto'].' ao tentar editar o plano de qualidade.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeEditar && permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id))) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}

$botoesTitulo = new CBlocoTitulo('Entregas e Critérios de Qualidade', 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=projetos&a=qualidade_ver&projeto_id='.$projeto_id, 'voltar','','Voltar','Ver os detalhes do plano de qualidade do projeto.');	
$botoesTitulo->mostrar();



$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');

$projeto_qualidade_entrega_id= getParam($_REQUEST, 'projeto_qualidade_entrega_id', null);
$projeto_qualidade_entrega_entrega=getParam($_REQUEST, 'projeto_qualidade_entrega_entrega', null);
$projeto_qualidade_entrega_criterio=getParam($_REQUEST, 'projeto_qualidade_entrega_criterio', null);
$excluirentrega=getParam($_REQUEST, 'excluirentrega', '0');
$editarentrega=getParam($_REQUEST, 'editarentrega', '0');
$mudar_projeto_qualidade_entrega_id=getParam($_REQUEST, 'mudar_projeto_qualidade_entrega_id', null);
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');
$alterar=getParam($_REQUEST, 'alterar', '0');

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="alterar" value="" />';

echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="projeto_qualidade_entrega_id" value="" />';
echo '<input type="hidden" name="mudar_projeto_qualidade_entrega_id" value="" />';
echo '<input type="hidden" name="excluirentrega" value="" />';
echo '<input type="hidden" name="editarentrega" value="" />';


	
echo estiloTopoCaixa();
echo '<table width="100%" cellspacing=0 cellpadding=0 width="100%" class="std">';  
echo '<tr><td colspan=2 align="left"><div id="combo_edicao">';
echo '<table cellpadding=0 cellspacing="2"><tr><td><b>Entrega</b></td><td><b>Critérios de qualidade</b></td><td></td></tr><tr><td valign=top><input type="text" name="projeto_qualidade_entrega_entrega" id="projeto_qualidade_entrega_entrega" value="" size="50" class="texto" /></td><td valign=top><textarea name="projeto_qualidade_entrega_criterio" id="projeto_qualidade_entrega_criterio" style="width:500px;" class="textarea"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_entrega(0);">'.imagem('icones/adicionar.png').'</a></td></tr></table>';
echo '</div></td></tr>';


echo '<tr><td colspan=2><div id="combo_entregas"></div></td></tr>';



echo '</table>';
echo '</td></tr></table>';
echo estiloFundoCaixa();

echo '</form>';
?>
<script language="javascript">

var projeto_id=<?php echo $projeto_id?>;

xajax_lista_artefatos(projeto_id);


function inserir_entrega(projeto_qualidade_entrega_id){
	xajax_inserir_entrega(projeto_qualidade_entrega_id, projeto_id, document.getElementById('projeto_qualidade_entrega_entrega').value, document.getElementById('projeto_qualidade_entrega_criterio').value);
	xajax_cancelar_edicao();
	xajax_lista_artefatos(projeto_id);
	}
	
function mudar_ordem(ordem, projeto_qualidade_entrega_id, direcao){
	xajax_mudar_ordem(ordem, projeto_qualidade_entrega_id, direcao, projeto_id);
	xajax_lista_artefatos(projeto_id);
	}	
	
function excluir_entrega(projeto_qualidade_entrega_id){
	xajax_excluir_entrega(projeto_qualidade_entrega_id);
	xajax_lista_artefatos(projeto_id);
	}	
	
function editar_entrega(projeto_qualidade_entrega_id){
	xajax_editar_entrega(projeto_qualidade_entrega_id);
	}	

function cancelar_edicao(){
	xajax_cancelar_edicao();
	}
	
</script>