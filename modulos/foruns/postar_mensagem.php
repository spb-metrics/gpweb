<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $linha;

$Aplic->carregarCKEditorJS();

$mensagem_id = getParam($_REQUEST, 'mensagem_id', null);
$mensagem_superior = getParam($_REQUEST, 'mensagem_superior', null);
$forum_id = getParam($_REQUEST, 'forum_id', null);
if (!$podeAdicionar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$toms_url_retorno = array();
foreach ($_REQUEST as $k => $v) {
	if ($k != 'postar_mensagem') $toms_url_retorno[] = "$k=$v";
	}
$base_dir_retorno = implode('&', $toms_url_retorno);
$sql = new BDConsulta;
$sql->adTabela('foruns');
$sql->adCampo('forum_nome, forum_dono, forum_moderador');
$sql->adOnde('foruns.forum_id = '.(int)$forum_id);
$forum_info = $sql->linha();
$sql->limpar();

$sql->adTabela('forum_mensagens');
$sql->esqUnir('usuarios', 'u', 'mensagem_autor = u.usuario_id');
$sql->adCampo('forum_mensagens.*, usuario_login');
$sql->adOnde('mensagem_id = '.(int)($mensagem_id ? $mensagem_id : $mensagem_superior));
$mensagem_info = $sql->linha();
$sql->limpar();

if ($mensagem_superior) {
	$sql->adTabela('forum_mensagens');
	$sql->adOnde('mensagem_superior = '.(int)($mensagem_id ? $mensagem_id : $mensagem_superior));
	$sql->adOrdem('mensagem_id DESC');
	$sql->setLimite(1);
	$ultima_mensagem_info = $sql->linha();
	if (!$ultima_mensagem_info) {
		$ultima_mensagem_info = &$mensagem_info;
		}
	$sql->limpar();
	}
$blocos = array();
$blocos['m=foruns'] = 'lista fóruns';
$blocos['m=foruns&a=ver&forum_id='.$forum_id] = 'tópicos deste fórum';
$titulo_blocos = array();
$titulo_blocos['m=foruns'] = 'Lista de Fóruns';
$titulo_blocos['m=foruns&a=ver&forum_id='.$forum_id] = 'Tópicos deste fórum';
$texto_blocos = array();
$texto_blocos['m=foruns'] = 'Visualizar a lista de fóruns existentes.';
$texto_blocos['m=foruns&a=ver&forum_id='.$forum_id] = 'Visualizar a lista de tópicos deste fórum.';
if ($mensagem_superior) {
	$blocos['m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_id='.$mensagem_superior] = 'este tópico';
	$titulo_blocos['m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_id='.$mensagem_superior] = 'Este Tópico';
  $texto_blocos['m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_id='.$mensagem_superior] = 'Visualizar a lista de mensagens deste tópico.';
	}


echo '<form name="mudarForum" method="post">';
echo '<input type="hidden" name="m" value="foruns" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_postagem_aed" />';
echo '<input type="hidden" name="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="mensagem_forum" value="'.$forum_id.'" />';
echo '<input type="hidden" name="mensagem_superior" value="'.$mensagem_superior.'" />';
echo '<input type="hidden" name="mensagem_publicada" value="'.($forum_info['forum_moderador'] ? '1' : '0').'" />';
echo '<input type="hidden" name="mensagem_data" value="'.$mensagem_info['mensagem_data'].'" />';
echo '<input type="hidden" name="mensagem_autor" value="'.(isset($mensagem_info['mensagem_autor']) && ($mensagem_id || $mensagem_superior < 0) ? $mensagem_info['mensagem_autor'] : $Aplic->usuario_id).'" />';
echo '<input type="hidden" name="mensagem_editor" value="'.(isset($mensagem_info['mensagem_autor']) && ($mensagem_id || $mensagem_superior < 0) ? $Aplic->usuario_id : null).'" />';
echo '<input type="hidden" name="mensagem_id" value="'.$mensagem_id.'" />';


echo '<tr><td align="left" nowrap="nowrap">'.mostrarBlocos($blocos, $titulo_blocos, $texto_blocos).'</td></tr>';

echo '<tr><td valign="top" colspan="2" align="left"><b>'.($mensagem_id ? 'Editar Tópico' : 'Adicionar Tópico').'</b></td></tr>';
if ($mensagem_superior) {
	$data = intval($mensagem_info['mensagem_data']) ? new CData($mensagem_info['mensagem_data']) : new CData();
	echo '<tr><td align="left">'.dica('Autor', 'Cada tópico ter um autor, que é o criador do mesmo.').'Autor:'.dicaF().'</td><td align="left">'.getUsuarioNome($mensagem_info['usuario_login']).' ('.$data->format($df.' '.$tf).')</td></tr>';
	echo '<tr><td align="left">'.dica('Assunto', 'Cada tópico tem um assunto.<br><br>Pode imaginar tópicos como subassuntos do fórum ou perguntas relacionadas ao fórum.').'Assunto:'.dicaF().'</td><td align="left">'.$mensagem_info['mensagem_titulo'].'</td></tr>';
	echo '<tr><td align="left" valign="top">'.dica('Mensagem', 'Mensagem inicial deste tópico.').'Mensagem:'.dicaF().'</td><td align="left">';
	echo $mensagem_info['mensagem_texto'];
	echo '</td></tr>';
	echo '<tr><td colspan="2" align="left"><hr /></td></tr>';
	}
echo '<tr><td align="right">'.dica('Título', 'A resposta a este tópico precisa ter um título.').'Título:'.dicaF().'</td><td><input type="text" class="texto" name="mensagem_titulo" value="'.($mensagem_id || !$mensagem_superior ? '' : 'Re: ').$mensagem_info['mensagem_titulo'].'" size="50" maxlength="250" /></td></tr>';
echo '<tr><td align="right" valign="top">'.dica('Mensagem', 'O corpo da mensagem de resposta a este tópico.').'Mensagem:'.dicaF().'</td><td align="left" valign="top"><textarea data-gpweb-cmp="ckeditor" name="mensagem_texto" id="mensagem_texto" style="width:700px;">'.$mensagem_info['mensagem_texto'].'</textarea></td></tr>';
echo '<tr><td></td>';

echo '<tr><td>';

if (($podeEditar && isset($linha['mensagem_autor']) && ($Aplic->usuario_id == $linha['forum_moderador'] || $Aplic->usuario_id == $linha['mensagem_autor'] || $Aplic->usuario_super_admin)) || ($podeAdicionar && (!isset($linha['mensagem_id']) || (isset($linha['mensagem_id']) && !$linha['mensagem_id'])))) echo botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()');
echo '</td><td align="right">'.botao('voltar', 'Voltar', 'Voltar a lista de tópicos.','','url_passar(0, \''.$base_dir_retorno.'\');').'</td></tr></form></table>';

?>
<script language="javascript">

function enviarDados(){
	var form = document.mudarForum;
	if (form.mensagem_titulo.value.search(/^\s*$/) >= 0 ) {
		alert('Por favor, insira um assunto válido.');
		form.mensagem_titulo.focus();
		}

	else form.submit();
	}

function excluir(){
	var form = document.mudarForum;
	if (confirm( 'Tem a certeza de que quer excluir esta mensagem?' )) {
		form.del.value="<?php echo $mensagem_id; ?>";
		form.submit();
		}
	}

function ordenarPorNome(x){
	var form = document.mudarForum;
	if (x == 'nome') form.forum_ordem_por.value = form.forum_last_name.value + ', ' + form.forum_nome.value;
	else form.forum_ordem_por.value = form.forum_projeto.value;
	}
</script>
