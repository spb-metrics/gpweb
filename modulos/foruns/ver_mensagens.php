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

if (!$dialogo) $Aplic->salvarPosicao();
$ordenar = getParam($_REQUEST, 'ordenar', 'asc');

if (isset($_REQUEST['vertipo'])) $Aplic->setEstado('vertipo', getParam($_REQUEST, 'vertipo', null));
$vertipo = $Aplic->getEstado('vertipo', 'normal');

$q = new BDConsulta;
$q->adTabela('foruns');
$q->adTabela('forum_mensagens');
$q->adCampo('forum_mensagens.*,	contato_id, contato_email, usuario_login, forum_moderador, visita_usuario');
$q->adUnir('forum_visitas', 'v', 'visita_usuario = '.(int)$Aplic->usuario_id.' AND visita_forum = '.(int)$forum_id.' AND visita_mensagem = forum_mensagens.mensagem_id');
$q->esqUnir('usuarios', 'u', 'mensagem_autor = u.usuario_id');
$q->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
$q->adOnde('forum_id = mensagem_forum AND (mensagem_id = '.(int)$mensagem_id.' OR mensagem_superior = '.(int)$mensagem_id.')');
$q->adOrdem('mensagem_data '.$ordenar);
$mensagens = $q->Lista();
$q->limpar();


$blocos = array();
$titulo_blocos = array();

$texto_blocos = array();

$blocos['m=foruns'] = 'lista de fóruns';
$blocos['m=foruns&a=ver&forum_id='.$forum_id] = 'tópicos deste fórum';
$blocos['m=foruns&a=ver_pdf&forum_id='.$forum_id.'&mensagem_id='.$mensagem_id.'&ordenar='.$ordenar.'&dialogo=1'] = 'imprimir';

$titulo_blocos['m=foruns'] = 'Lista de Fóruns';
$titulo_blocos['m=foruns&a=ver&forum_id='.$forum_id] = 'Tópicos deste Fórum';
$titulo_blocos['m=foruns&a=ver_pdf&forum_id='.$forum_id.'&mensagem_id='.$mensagem_id.'&ordenar='.$ordenar.'&dialogo=1'] = 'imprimir';
	
$texto_blocos['m=foruns'] = 'Visualizar a lista de fóruns existentes.';
$texto_blocos['m=foruns&a=ver&forum_id='.$forum_id] = 'Visualizar a lista de tópicos deste fórum.';
$texto_blocos['m=foruns&a=ver_pdf&forum_id='.$forum_id.'&mensagem_id='.$mensagem_id.'&ordenar='.$ordenar.'&dialogo=1'] = 'imprimir.';		



echo '<script language="javascript">';
if ($vertipo != 'normal') {
	echo 'function ativar(id) {';
	if ($vertipo == 'sozinha') {
		echo 'var elems = document.getElementsByTagName("div");';
		echo 'for (var i=0, i_cmp=elems.length; i<i_cmp; i++)	if (elems[i].className == "mensagem") elems[i].style.display = "none";';
		echo 'document.getElementById(id).style.display = "block";';
		} 
	elseif ($vertipo == 'curta') {
		echo 'vista = (document.getElementById(id).style.display == "none") ? "block" : "none";';
		echo 'document.getElementById(id).style.display = vista;';
		}
	echo '}';
	}
if ($podeEditar) {
	echo 'function excluir(id, autor) {';
	echo 'var form = document.frmMensagem;';
	echo 'if (confirm( "Tem a certeza que deseja excluir esta mensagem?")) {';
		echo 'form.del.value = 1;';
		echo 'form.mensagem_id.value = id;';
		echo 'form.mensagem_autor.value=autor;';
		echo 'form.submit();';
		echo '}';
	echo '}';
	}
echo '</script>';


echo '<form name="frmMensagem" method="POST">';
echo '<input type="hidden" name="m" value="foruns" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_postagem_aed" />';
echo '<input type="hidden" name="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="mensagem_id" value="0" />';
echo '<input type="hidden" name="mensagem_autor" value="0" />';
echo '</form>';

echo '<tr><td colspan="2">';
echo '<table width="100%" cellspacing="1" cellpadding="2" border=0 align="center"><tr>';
echo '<td align="left" nowrap="nowrap"></td><td nowrap="nowrap"><form method="post"><input type="hidden" name="m" value="'.$m.'" /><input type="hidden" name="a" value="ver" /><input type="hidden" name="forum_id" value="'.$forum_id.'" /><input type="hidden" name="mensagem_id" value="'.$mensagem_id.'" /><input type="hidden" name="ordenar" value="'.$ordenar.'" />Ver: '.dica('Normal', 'Ver todos os títulos e os conteúdos das mensagens, deste tópico, na mesma tela.').'<input type="radio" name="vertipo" value="normal" '.($vertipo == 'normal' ? 'checked' : '').' onclick="this.form.submit();" />Normal'.dicaF().dica('Colapsado', 'Ver a lista dos títulos das mensagens, deste tópico, e ao clicar nestes o corpo das mensagens será expandido abaixo dos títulos.').'<input type="radio" name="vertipo" value="curta" '.($vertipo == 'curta' ? 'checked' : '').' onclick="this.form.submit();" />Colapsado'.dicaF().dica('Uma Mensagem de Cada Vez', 'Ver a lista dos títulos das mensagens, deste tópico, e ao clicar em uma destas o corpo das mensagens será exibido na parte central da tela.').'<input type="radio" name="vertipo" value="sozinha" '.($vertipo == 'sozinha' ? 'checked' : '').' onclick="this.form.submit();" />Uma mensagem de cada vez'.dicaF().'</form></td>';
$ordenar = ($ordenar == 'asc' ? 'desc' : 'asc'); 
echo '<td width="100%" align="right"><table><tr><td>'.botao('ordenar '.($ordenar == 'asc' ? 'ascendente' : 'descendente'), 'Ordenar '.($ordenar == 'asc' ? 'Ascendente' : 'Descendente'), 'Ordenar as respostas de forma '.($ordenar == 'asc' ? 'ascendente' : 'descendente'),'','url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_id='.$mensagem_id.'&ordenar='.$ordenar.'\');','','',0);
if ($podeEditar) echo '</td><td>'.botao('postar resposta', 'Postar Resposta', 'Acrescentar uma mensagem a este tópico.','','url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_superior='.$mensagem_id.'&postar_mensagem=1\');','','',0).'</td><td>'.botao('novo tópico', 'Novo Tópico', 'Criar um novo tópico dentro deste fórum.','','url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_id=0&postar_mensagem=1\');','','',0);
echo '</td></tr></table></td>';
echo '</tr></table></td></tr>';
echo '<tr>';
if ($vertipo != 'curta') echo '<td align="center" style="background: #f2f0ec" nowrap><b>Autor</b></td>';
echo '<td style="background: #f2f0ec" align="left" width="'.($vertipo == 'sozinha' ? '60' : '100').'%"><b>Mensagem</b></td>';
echo '</tr>';
$x = false;
$data = new CData();
if ($vertipo == 'sozinha') {
	$s = '';
	$primeiro = true;
	}
$novas_mensagens = array();
$lado ='';
foreach ($mensagens as $linha) {
	if ($linha['mensagem_id'] == $mensagem_id) $topico = $linha['mensagem_titulo'];
	$q = new BDConsulta;
	$q->adTabela('forum_mensagens');
	$q->adTabela('usuarios');
	$q->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
	$q->adCampo('DISTINCT contato_email, contato_id, usuario_login');
	$q->adOnde('usuarios.usuario_id = '.(int)$linha['mensagem_editor']);
	$editor = $q->Lista();
	$data = intval($linha['mensagem_data']) ? new CData($linha['mensagem_data']) : null;
	if ($vertipo != 'sozinha') $s = '';
	$estilo = $x ? 'background-color:#eeeeee' : '';
	$estilo ='';
	if ($vertipo == 'normal') {
		$s .= '<tr>';
		$s .= '<td valign="top" style="'.$estilo.'" nowrap="nowrap">';
		$s .= '<font size="2">'.link_contato($linha['contato_id'],'','','esquerda').'</font>';
		if (sizeof($editor) > 0) {
			$s .= '<br/>&nbsp;<br/>'."última edição por";
			$s .= ':<br/>';
			$s .= '<font size="1">'.link_contato($editor[0]['contato_id'],'','','esquerda').'</font>';
			}
		if ($linha['visita_usuario'] != $Aplic->usuario_id) {
			$s .= '<br />&nbsp;'.imagem('icones/msg_nova.png');
			$novas_mensagens[] = $linha['mensagem_id'];
			}
		$s .= '</td>';
		$s .= '<td valign="top" style="'.$estilo.'">';
		$s .= '<font size="2"><b>'.$linha['mensagem_titulo'].'</b><hr size=1>';
		$linha['mensagem_texto'] = $bbparser->qparse($linha['mensagem_texto']);
		$s .= str_replace(chr(13), '&nbsp;<br />', $linha['mensagem_texto']);
		$s .= '</font></td>';
		$s .= '<td valign="top" align="right" style="'.$estilo.'">';
		$podeEditar = $Aplic->checarModulo('foruns', 'editar');
		if ($podeEditar && ($Aplic->usuario_id == $linha['forum_moderador'] || $Aplic->usuario_id == $linha['mensagem_autor'] || $Aplic->usuario_super_admin)) {
			$s .= dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta mensagem').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&postar_mensagem=1&forum_id='.$linha['mensagem_forum'].'&mensagem_superior='.$linha['mensagem_superior'].'&mensagem_id='.$linha["mensagem_id"].'\');">'.imagem('icones/editar.gif'). dicaF(). '</a>';
			$s .= dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta mensagem').'<a href="javascript:excluir('.$linha['mensagem_id'].', '.$linha['mensagem_autor'].')">'.imagem('icones/remover.png'). dicaF(). '</a>' ;
			}
		$s .= '</td>';
		$s .= '</tr><tr>';
		$s .= '<td valign="top" style="'.$estilo.'" nowrap="nowrap">';
		$s .= '<img src="'.acharImagem('icones/postagem.gif', $m).'" alt="data da postagem" border=0 width="14" height="11">'.$data->format($df.' '.$tf).'</td>';
		
		$s .= '</tr>';
		} 
	elseif ($vertipo == 'curta') {
		$s .= '<tr><td valign="top" style="'.$estilo.'" ><font size="2">'.link_contato($linha['contato_id'],'','','esquerda').'</font>&nbsp;';
		$s .= ' ('.$data->format($df.' '.$tf).') ';
		$s .= '<a name="'.$linha['mensagem_id'].'" href="javascript: void(0);" onclick="ativar('.$linha['mensagem_id'].')"><span size="2"><b>'.$linha['mensagem_titulo'].'</b></span></a>';
		$s .= '<div class="mensagem" id="'.$linha['mensagem_id'].'" style="display: none">';
		$linha['mensagem_texto'] = $bbparser->qparse($linha['mensagem_texto']);
		$s .= str_replace(chr(13), "&nbsp;<br />", $linha['mensagem_texto']);
		$s .= '</div>';
		if (sizeof($editor) > 0) $s .= '<br/>última edição por:&nbsp;<font size="1">'.link_contato($editor[0]['contato_id'],'','','esquerda').'</font>';
		$s .='</td>';
		$s .= '<td valign="top" align="right" style="'.$estilo.'">';
		$podeEditar = $Aplic->checarModulo('foruns', 'editar');
		if ($podeEditar && ($Aplic->usuario_id == $linha['forum_moderador'] || $Aplic->usuario_id == $linha['mensagem_autor'] || $Aplic->usuario_super_admin)) {
			$s .= dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta mensagem').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&postar_mensagem=1&forum_id='.$linha['mensagem_forum'].'&mensagem_superior='.$linha['mensagem_superior'].'&mensagem_id='.$linha["mensagem_id"].'\');">'.imagem('icones/editar.gif'). dicaF(). '</a>';
			$s .= dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta mensagem').'<a href="javascript:excluir('.$linha['mensagem_id'].', '.$linha['mensagem_autor'].')">'.imagem('icones/remover.png'). dicaF(). '</a>' ;
			}
		$s .= '</td></tr>';
		} 
	elseif ($vertipo == 'sozinha') {
		$s .= '<tr>';
		$s .='<td valign="top" style="'.$estilo.'">';
		
		$s .= '<table><tr><td>';
		$podeEditar = $Aplic->checarModulo('foruns', 'editar');
		if ($podeEditar && ($Aplic->usuario_id == $linha['forum_moderador'] || $Aplic->usuario_id == $linha['mensagem_autor'] || $Aplic->usuario_super_admin)) {
			$s .= dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta mensagem').'<a href="javascript:excluir('.$linha['mensagem_id'].', '.$linha['mensagem_autor'].')">'.imagem('icones/remover.png'). dicaF(). '</a>' ;
			$s .= dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta mensagem').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&postar_mensagem=1&forum_id='.$linha['mensagem_forum'].'&mensagem_superior='.$linha['mensagem_superior'].'&mensagem_id='.$linha["mensagem_id"].'\');">'.imagem('icones/editar.gif'). dicaF(). '</a>';
			}
		$s .= '</td><td>';
		
		$s .= $data->format($df.' '.$tf).' - <font size="2">'.link_contato($linha['contato_id'],'','','esquerda').'</font>&nbsp;&nbsp;';
		$s .= '<a href="javascript: void(0);" onclick="ativar('.$linha['mensagem_id'].')"><span size="2"><b>'.$linha['mensagem_titulo'].'</b></span></a>';
		$lado .= '<div class="mensagem" id="'.$linha['mensagem_id'].'" style="display: none">';
		$linha['mensagem_texto'] = $bbparser->qparse($linha['mensagem_texto']);
		$lado .= str_replace(chr(13), '&nbsp;<br />', $linha['mensagem_texto']);
		$lado .= '</div>';
		if (sizeof($editor) > 0) $s .= '<br/>última edição por:&nbsp;&nbsp;&nbsp;<font size="1">'.link_contato($editor[0]['contato_id'],'','','esquerda').'</font>';
		$s .= '</td></tr></table></td>';
		if ($primeiro) {
			$s .= '<td rowspan="'.count($mensagens).'" valign="top">';
			echo $s;
			$s = '';
			$primeiro = false;
			}
		$s .= '</tr>';
		}
	if ($vertipo != 'sozinha') echo $s;
	$x = !$x;
	}
if ($vertipo == 'sozinha') echo $lado.'</td>'.$s;
echo '<tr><td colspan="2"><table border=0 cellpadding="2" cellspacing="1" width="100%"><tr><td align="left" nowrap="nowrap">';
echo mostrarBlocos($blocos, $titulo_blocos, $texto_blocos); 
echo '</td></td></tr></table></td></tr></table>';
foreach ($novas_mensagens as $msg_id) {
	$q = new BDConsulta;
	$q->adTabela('forum_visitas');
	$q->adInserir('visita_usuario', $Aplic->usuario_id);
	$q->adInserir('visita_forum', $forum_id);
	$q->adInserir('visita_mensagem', $msg_id);
	$q->adInserir('visita_data', $data->getData());
	$q->exec();
	$q->limpar();
	}
?>