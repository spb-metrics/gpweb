<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
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
$blocos['m=foruns'] = 'lista f�runs';
$blocos['m=foruns&a=ver&forum_id='.$forum_id] = 't�picos deste f�rum';
$titulo_blocos = array();
$titulo_blocos['m=foruns'] = 'Lista de F�runs';
$titulo_blocos['m=foruns&a=ver&forum_id='.$forum_id] = 'T�picos deste f�rum';
$texto_blocos = array();
$texto_blocos['m=foruns'] = 'Visualizar a lista de f�runs existentes.';
$texto_blocos['m=foruns&a=ver&forum_id='.$forum_id] = 'Visualizar a lista de t�picos deste f�rum.';
if ($mensagem_superior) {
	$blocos['m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_id='.$mensagem_superior] = 'este t�pico';
	$titulo_blocos['m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_id='.$mensagem_superior] = 'Este T�pico';
  $texto_blocos['m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_id='.$mensagem_superior] = 'Visualizar a lista de mensagens deste t�pico.';
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

echo '<tr><td valign="top" colspan="2" align="left"><b>'.($mensagem_id ? 'Editar T�pico' : 'Adicionar T�pico').'</b></td></tr>';
if ($mensagem_superior) {
	$data = intval($mensagem_info['mensagem_data']) ? new CData($mensagem_info['mensagem_data']) : new CData();
	echo '<tr><td align="left">'.dica('Autor', 'Cada t�pico ter um autor, que � o criador do mesmo.').'Autor:'.dicaF().'</td><td align="left">'.getUsuarioNome($mensagem_info['usuario_login']).' ('.$data->format($df.' '.$tf).')</td></tr>';
	echo '<tr><td align="left">'.dica('Assunto', 'Cada t�pico tem um assunto.<br><br>Pode imaginar t�picos como subassuntos do f�rum ou perguntas relacionadas ao f�rum.').'Assunto:'.dicaF().'</td><td align="left">'.$mensagem_info['mensagem_titulo'].'</td></tr>';
	echo '<tr><td align="left" valign="top">'.dica('Mensagem', 'Mensagem inicial deste t�pico.').'Mensagem:'.dicaF().'</td><td align="left">';
	echo $mensagem_info['mensagem_texto'];
	echo '</td></tr>';
	echo '<tr><td colspan="2" align="left"><hr /></td></tr>';
	}
echo '<tr><td align="right">'.dica('T�tulo', 'A resposta a este t�pico precisa ter um t�tulo.').'T�tulo:'.dicaF().'</td><td><input type="text" class="texto" name="mensagem_titulo" value="'.($mensagem_id || !$mensagem_superior ? '' : 'Re: ').$mensagem_info['mensagem_titulo'].'" size="50" maxlength="250" /></td></tr>';
echo '<tr><td align="right" valign="top">'.dica('Mensagem', 'O corpo da mensagem de resposta a este t�pico.').'Mensagem:'.dicaF().'</td><td align="left" valign="top"><textarea data-gpweb-cmp="ckeditor" name="mensagem_texto" id="mensagem_texto" style="width:700px;">'.$mensagem_info['mensagem_texto'].'</textarea></td></tr>';
echo '<tr><td></td>';

echo '<tr><td>';

if (($podeEditar && isset($linha['mensagem_autor']) && ($Aplic->usuario_id == $linha['forum_moderador'] || $Aplic->usuario_id == $linha['mensagem_autor'] || $Aplic->usuario_super_admin)) || ($podeAdicionar && (!isset($linha['mensagem_id']) || (isset($linha['mensagem_id']) && !$linha['mensagem_id'])))) echo botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()');
echo '</td><td align="right">'.botao('voltar', 'Voltar', 'Voltar a lista de t�picos.','','url_passar(0, \''.$base_dir_retorno.'\');').'</td></tr></form></table>';

?>
<script language="javascript">

function enviarDados(){
	var form = document.mudarForum;
	if (form.mensagem_titulo.value.search(/^\s*$/) >= 0 ) {
		alert('Por favor, insira um assunto v�lido.');
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
