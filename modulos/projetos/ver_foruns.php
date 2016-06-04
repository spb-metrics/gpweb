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

global $Aplic, $projeto_id;
$q = new BDConsulta;
$q->adTabela('foruns');
$q->esqUnir('projetos', 'p', 'projeto_id = forum_projeto');
$q->adCampo('forum_id, forum_projeto, forum_descricao, forum_dono, forum_nome, forum_contagem_msg, forum_ultima_data,	projeto_nome, projeto_cor, projeto_id');
$q->adOnde('forum_projeto = '.(int)$projeto_id);
$q->adOrdem('forum_projeto, forum_nome');
$rc=$q->Lista();
$q->limpar();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr><th nowrap="nowrap">&nbsp;</th>';
echo '<th nowrap="nowrap">'.dica('Assunto', 'Assunto do f�rum.').'Assunto'.dicaF().'</th><th>'.dica('Descri��o', 'Descri��o do f�rum.').'Descri��o'.dicaF().'</th><th nowrap="nowrap">'.dica('N�mero de Mensagens', 'N�mero de mensagens cont�das neste f�rum.').'Msg'.dicaF().'</th><th nowrap="nowrap">'.dica('�ltima Postagem', '�ltima postagem de mensagem neste f�rum.').'�ltima Postagem'.dicaF().'</th></tr>';
$tf = $Aplic->getPref('formatohora');
$df = '%d/%m/%Y';
$qnt=0;
foreach ($rc as $linha)	{  
	$qnt++;
	$data = new CData($linha['forum_ultima_data']); 
	echo '<tr><td nowrap="nowrap" align="center">'.( $linha["forum_dono"] == $Aplic->usuario_id ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=editar&forum_id='.$linha['forum_id'].'\');"><img src="'.acharImagem('icones/editar.gif').'" alt="expandir forum" border=0 width=12 height=12></a>' : '').'</td>';
	echo '<td nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$linha["forum_id"].'\')">'.$linha['forum_nome'].'</a></td><td>'.$linha['forum_descricao'].'</td>';
	echo '<td nowrap="nowrap">'.($linha['forum_contagem_msg'] ? $linha['forum_contagem_msg'] : '0').'</td>';
	echo '<td nowrap="nowrap">'.(intval($linha['forum_ultima_data']) > 0 ? $data->format($df.' '.$tf) : 'n/d').'</td></tr>';
	}
if (!$qnt) echo '<tr><td colspan="7"><p>Nenhum f�rum encontrado.</p></td></tr>';
echo '</table>';
?>
