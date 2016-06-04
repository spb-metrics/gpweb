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
	$Aplic->setMsg('Não foi passado um ID de '.$config['projeto'].' ao tentar editar o plano de comunicacao.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeEditar && permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id))) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}

$botoesTitulo = new CBlocoTitulo('Eventos de Comunicação', 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=projetos&a=comunicacao_ver&projeto_id='.$projeto_id, 'voltar','','Voltar','Ver os detalhes do plano de comunicação do projeto.');	
$botoesTitulo->mostrar();


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';

echo '<input type="hidden" name="projeto_comunicacao_evento_id" id="projeto_comunicacao_evento_id" value="" />';
	
echo estiloTopoCaixa();
echo '<table width="100%" cellspacing=0 cellpadding=0 width="100%" class="std">';  
echo '<tr><td colspan=2 align="left">';
echo '<table cellpadding=0 cellspacing="2" width="100%"><tr><td><b>Evento</b></td><td><b>Objetivo</b></td><td><b>Responsável</b></td><td><b>Público alvo</b></td><td><b>Canal</b></td><td><b>Periodicidade</b></td><td></td></tr>';
echo '<tr>';
echo '<td valign=top><input type="text" class="texto" name="projeto_comunicacao_evento_evento" id="projeto_comunicacao_evento_evento" class="textarea" style="width:100%"></td>';
echo '<td valign=top><textarea name="projeto_comunicacao_evento_objetivo" id="projeto_comunicacao_evento_objetivo" class="textarea" style="width:100%"></textarea></td>';
echo '<td valign=top><textarea name="projeto_comunicacao_evento_responsavel" id="projeto_comunicacao_evento_responsavel" class="textarea" style="width:100%"></textarea></td>';
echo '<td valign=top><textarea name="projeto_comunicacao_evento_publico" id="projeto_comunicacao_evento_publico" class="textarea" style="width:100%"></textarea></td>';
echo '<td valign=top><textarea name="projeto_comunicacao_evento_canal" id="projeto_comunicacao_evento_canal" class="textarea" style="width:100%"></textarea></td>';
echo '<td valign=top><textarea name="projeto_comunicacao_evento_periodicidade" id="projeto_comunicacao_evento_periodicidade" class="textarea" style="width:100%"></textarea></td>';


echo '<td id="adicionar_evento" style="display:"><a href="javascript: void(0);" onclick="inserir_evento();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir o evento.').'</a></td>';
echo '<td id="confirmar_evento" style="display:none"><a href="javascript: void(0);" onclick="limpar();">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do evento.').'</a><a href="javascript: void(0);" onclick="inserir_evento();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do evento.').'</a></td></tr>';




//echo '<td><a href="javascript:void(0);" onclick="javascript:inserir_evento(0);">'.imagem('icones/adicionar.png').'</a></td>';


echo '</tr></table>';
echo '</td></tr>';



echo '<tr><td colspan=2><div id="combo_eventos"></div></td></tr>';



echo '</table>';
echo '</td></tr></table>';
echo estiloFundoCaixa();

echo '</form>';
?>
<script language="javascript">

function pop_evento(evento_id){
	parent.gpwebApp.popUp('Evento de Calendário', 800, 600, 'm=calendario&a=ver&dialogo=1&projeto_comunicacao_evento_id='+projeto_comunicacao_evento_id+'&evento_projeto='+document.getElementById('projeto_id').value+(evento_id > 0 ? '&evento_id='+evento_id : ''), null, window);
	
	}

function excluir_calendario(projeto_comunicacao_evento_id, evento_id){
	xajax_excluir_calendario(projeto_comunicacao_evento_id, evento_id);
	}

function adicionar_evento_calendario(projeto_comunicacao_evento_id, evento_id){
	parent.gpwebApp.popUp('Evento de Calendário', 800, 600, 'm=calendario&a=editar&dialogo=1&projeto_comunicacao_evento_id='+projeto_comunicacao_evento_id+'&evento_projeto='+document.getElementById('projeto_id').value+(evento_id > 0 ? '&evento_id='+evento_id : ''), window.setEventoComunicacao, window);
	}

function setEventoComunicacao(projeto_comunicacao_evento_id, evento_id){
	xajax_adicionar_calendario(projeto_comunicacao_evento_id, evento_id);
	}



xajax_lista_artefatos(document.getElementById('projeto_id').value);


function inserir_evento(){
	xajax_inserir_evento(document.getElementById('projeto_comunicacao_evento_id').value, document.getElementById('projeto_id').value, document.getElementById('projeto_comunicacao_evento_evento').value, document.getElementById('projeto_comunicacao_evento_objetivo').value, document.getElementById('projeto_comunicacao_evento_responsavel').value, document.getElementById('projeto_comunicacao_evento_publico').value, document.getElementById('projeto_comunicacao_evento_canal').value, document.getElementById('projeto_comunicacao_evento_periodicidade').value);
	limpar();
	xajax_lista_artefatos(document.getElementById('projeto_id').value);
	}
	
function mudar_ordem(ordem, projeto_comunicacao_evento_id, direcao){
	xajax_mudar_ordem(ordem, projeto_comunicacao_evento_id, direcao, document.getElementById('projeto_id').value);
	xajax_lista_artefatos(document.getElementById('projeto_id').value);
	}	
	
function excluir_evento(projeto_comunicacao_evento_id){
	xajax_excluir_evento(projeto_comunicacao_evento_id);
	xajax_lista_artefatos(document.getElementById('projeto_id').value);
	}	
	
function editar_evento(projeto_comunicacao_evento_id){
	xajax_editar_evento(projeto_comunicacao_evento_id);
	
	document.getElementById('adicionar_evento').style.display="none";
	document.getElementById('confirmar_evento').style.display="";
	
	}	
	
function limpar(){
	document.getElementById('projeto_comunicacao_evento_id').value='';
	document.getElementById('projeto_comunicacao_evento_evento').value='';
	document.getElementById('projeto_comunicacao_evento_objetivo').value='';
	document.getElementById('projeto_comunicacao_evento_responsavel').value='';
	document.getElementById('projeto_comunicacao_evento_publico').value='';
	document.getElementById('projeto_comunicacao_evento_canal').value='';
	document.getElementById('projeto_comunicacao_evento_periodicidade').value='';
	document.getElementById('adicionar_evento').style.display='';	
	document.getElementById('confirmar_evento').style.display='none';
	}	
	
</script>