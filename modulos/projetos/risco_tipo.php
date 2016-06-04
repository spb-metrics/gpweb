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
	$Aplic->setMsg('Não foi passado um ID de '.$config['projeto'].' ao tentar editar o plano de risco.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeEditar && permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id))) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}

$botoesTitulo = new CBlocoTitulo('Tipos de Riscos', 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=projetos&a=risco_ver&projeto_id='.$projeto_id, 'voltar','','Voltar','Ver os detalhes do gerenciamento de risco do projeto.');	
$botoesTitulo->mostrar();


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';

$RiscoCategoria = array('' => '') + getSisValor('RiscoCategoria');
$tipo=array('Negativo'=>'Negativo', 'Positivo'=>'Positivo');
$probabilidade=array(1=>'Baixa', 2=>'Média', 3=>'Alta');
$impacto=array(1=>'Baixo', 2=>'Médio', 3=>'Alto');

echo estiloTopoCaixa();
echo '<table width="100%" cellspacing=0 cellpadding=0 width="100%" class="std">';  
echo '<tr><td colspan=2 align="left"><div id="combo_edicao">';
$linha_edicao='<table cellpadding=0 cellspacing="2" width="100%"><tr><td><b>Descrição</b></td><td><b>Categoria</b></td><td><b>Tipo</b></td><td><b>Consequência</b></td><td><b>Probabilidade</b></td><td><b>Impacto</b></td><td><b>Ação</b></td><td><b>Gatilho</b></td><td><b>Resposta ao Risco</b></td><td><b>Responsável</b></td><td><b>Status</b></td><td></td></tr><tr><td valign=top><textarea name="projeto_risco_tipo_descricao" id="projeto_risco_tipo_descricao" class="textarea" style="width:100%"></textarea></td><td valign=top>'.selecionaVetor($RiscoCategoria, 'projeto_risco_tipo_categoria', 'size="1" class="texto"').'</td><td valign=top>'.selecionaVetor($tipo, 'projeto_risco_tipo_tipo', 'size="1" class="texto"').'</td><td valign=top><textarea name="projeto_risco_tipo_consequencia" id="projeto_risco_tipo_consequencia" class="textarea" style="width:100%"></textarea></td><td valign=top>'.selecionaVetor($probabilidade, 'projeto_risco_tipo_probabilidade', 'size="1" class="texto"').'</td><td valign=top>'.selecionaVetor($impacto, 'projeto_risco_tipo_impacto', 'size="1" class="texto"').'</td><td valign=top><textarea name="projeto_risco_tipo_acao" id="projeto_risco_tipo_acao" class="textarea" style="width:100%"></textarea></td><td valign=top><textarea name="projeto_risco_tipo_gatilho" id="projeto_risco_tipo_gatilho" class="textarea" style="width:100%"></textarea></td><td valign=top><textarea name="projeto_risco_tipo_resposta" id="projeto_risco_tipo_resposta" class="textarea" style="width:100%"></textarea></td><td valign=top><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="projeto_risco_tipo_usuario" name="projeto_risco_tipo_usuario" value="" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="" style="width:120px;" class="texto" READONLY /></td><td valign=top><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif').'</a></td></tr></table></td><td valign=top><textarea name="projeto_risco_tipo_status" id="projeto_risco_tipo_status" class="textarea" style="width:100%"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_tipo(0);">'.imagem('icones/adicionar.png').'</a></td></tr></table>';

echo $linha_edicao;
echo '</div></td></tr>';
echo '<tr><td colspan=2><div id="combo_tipos"></div></td></tr>';



echo '</table>';
echo '</td></tr></table>';
echo estiloFundoCaixa();

echo '</form>';
?>
<script language="javascript">

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id=<?php echo $objProjeto->projeto_cia ?>&usuario_id='+document.getElementById('projeto_risco_tipo_usuario').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id=<?php echo $objProjeto->projeto_cia ?>&usuario_id='+document.getElementById('projeto_risco_tipo_usuario').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('projeto_risco_tipo_usuario').value=usuario_id;		
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}	

var projeto_id=<?php echo $projeto_id?>;

	


function inserir_tipo(projeto_risco_tipo_id){
	xajax_inserir_tipo(
	projeto_risco_tipo_id, 
	projeto_id, 
	document.getElementById('projeto_risco_tipo_descricao').value, 
	document.getElementById('projeto_risco_tipo_categoria').value, 
	document.getElementById('projeto_risco_tipo_tipo').value, 
	document.getElementById('projeto_risco_tipo_consequencia').value,
	document.getElementById('projeto_risco_tipo_probabilidade').value, 
	document.getElementById('projeto_risco_tipo_impacto').value,
	document.getElementById('projeto_risco_tipo_acao').value, 
	document.getElementById('projeto_risco_tipo_gatilho').value,
	document.getElementById('projeto_risco_tipo_resposta').value, 
	document.getElementById('projeto_risco_tipo_usuario').value,
	document.getElementById('projeto_risco_tipo_status').value
	);
	cancelar_edicao();
	xajax_lista_artefatos(projeto_id);
	}

function mudar_ordem(ordem, projeto_risco_tipo_id, direcao){
	xajax_mudar_ordem(ordem, projeto_risco_tipo_id, direcao, projeto_id);
	xajax_lista_artefatos(projeto_id);
	}	
	
function excluir_tipo(projeto_risco_tipo_id){
	xajax_excluir_tipo(projeto_risco_tipo_id);
	xajax_lista_artefatos(projeto_id);
	}	
	
function editar_tipo(projeto_risco_tipo_id){
	xajax_editar_tipo(projeto_risco_tipo_id);
	}	
	
xajax_lista_artefatos(projeto_id);


function cancelar_edicao(){
	xajax_cancelar_edicao();
	//document.getElementById('combo_edicao').innerHTML='<?php echo $linha_edicao ?>';
	}	


	
	
</script>