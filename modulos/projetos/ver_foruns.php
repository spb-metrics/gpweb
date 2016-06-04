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
echo '<th nowrap="nowrap">'.dica('Assunto', 'Assunto do fórum.').'Assunto'.dicaF().'</th><th>'.dica('Descrição', 'Descrição do fórum.').'Descrição'.dicaF().'</th><th nowrap="nowrap">'.dica('Número de Mensagens', 'Número de mensagens contídas neste fórum.').'Msg'.dicaF().'</th><th nowrap="nowrap">'.dica('Última Postagem', 'Última postagem de mensagem neste fórum.').'Última Postagem'.dicaF().'</th></tr>';
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
if (!$qnt) echo '<tr><td colspan="7"><p>Nenhum fórum encontrado.</p></td></tr>';
echo '</table>';
?>
