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

if (!$dialogo) $Aplic->salvarPosicao();
$ordenar = getParam($_REQUEST, 'ordenar', 'asc');
$forum_id = getParam($_REQUEST, 'forum_id', 0);
$mensagem_id = getParam($_REQUEST, 'mensagem_id', 0);

if (!$Aplic->checarModulo('foruns', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$q = new BDConsulta;
$q->adTabela('foruns');
$q->esqUnir('projetos', 'p', 'forum_projeto = p.projeto_id');
$q->esqUnir('usuarios', 'u', 'usuario_id = forum_dono');
$q->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
$q->adCampo('forum_id, forum_acesso, forum_projeto,	forum_tarefa, forum_descricao, forum_dono, forum_nome, forum_data_criacao, forum_ultima_data, forum_contagem_msg, forum_moderador, usuario_login, contato_id, projeto_nome, projeto_id, projeto_cor');
$q->adOnde('forum_id = '.(int)$forum_id);
$forum = $q->Linha();
$q->limpar();


$q->adTabela('forum_mensagens');
$q->esqUnir('foruns','foruns','forum_id = mensagem_forum');
$q->esqUnir('forum_visitas', 'v', 'visita_usuario = '.(int)$Aplic->usuario_id.' AND visita_forum = '.(int)$forum_id.' AND visita_mensagem = forum_mensagens.mensagem_id');
$q->esqUnir('usuarios', 'u', 'mensagem_autor = u.usuario_id');
$q->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
$q->adCampo('forum_mensagens.*,	contato_posto, contato_nomeguerra, contato_email, usuario_login, forum_moderador, visita_usuario');
$q->adOnde('(mensagem_id = '.(int)$mensagem_id.' OR mensagem_superior = '.(int)$mensagem_id.')');
if (config('forum_descendent_ordem') || getParam($_REQUEST, 'ordenar', 0)) $q->adOrdem('mensagem_data '.$ordenar);
$mensagens = $q->Lista();
$q->limpar();



$q->adTabela('forum_mensagens');
$q->adCampo('mensagem_titulo');
$q->adOnde('mensagem_id = '.$mensagem_id);
$topico = $q->Resultado();
$q->limpar();


echo '<form name="formulario" id="formulario" action="modulos/relatorios/pdf.php" target="_self" method="post" >';
echo '<input type="hidden" name="pdf" id="pdf" value="" />';
echo '<input type="hidden" name="orientacao" id="orientacao" value="landscape" />';
echo '<input type="hidden" name="relatorio" id="relatorio" value="Mensagens" />';
echo '</form>';


$x = false;
$data = new CData();
$pdfdados = array();
$pdfCabecalho = array('Data', ucfirst($config['usuario']), 'Mensagem');
$novas_mensagens = array();


$pdf =ucfirst($config['projeto']).': '.$forum['projeto_nome'].'<br>F�rum: '.$forum['forum_nome'].'<br>';
$pdf.='T�pico: '.$topico.'<br>';
$pdf.='<table cellpadding=0 cellspacing=0 class="tbl1" width="100%">';
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
foreach ($mensagens as $linha) {
	if ($linha['mensagem_id'] == $mensagem_id) $topico = $linha['mensagem_titulo'];
	$q = new BDConsulta;
	$q->adTabela('forum_mensagens');
	$q->esqUnir('usuarios','usuarios','mensagem_editor=usuarios.usuario_id');
	$q->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
	$q->adCampo('DISTINCT contato_email, contato_posto, contato_nomeguerra, usuario_login');
	$q->adOnde('usuarios.usuario_id = '.(int)$linha['mensagem_editor']);
	$editor = $q->Lista();
	$data = intval($linha['mensagem_data']) ? new CData($linha['mensagem_data']) : null;
	$pdf.='<tr><td>'.($linha['mensagem_data'] ? $data->format($df.' '.$tf) : '&nbsp;').'</td><td>'.($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']).'</td><td><b>'.$linha['mensagem_titulo'].'</b><br>'.$linha['mensagem_texto'].'</td></tr>';
	}
$pdf.='</table>';	
echo $pdf;
echo "<script language='javascript'>document.getElementById('pdf').value='".html_para_javascript($pdf)."'; document.getElementById('formulario').submit();</script>";
?>