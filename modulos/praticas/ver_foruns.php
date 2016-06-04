<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $tarefa_id, 
	$projeto_id, 
	$pg_perspectiva_id, 
	$tema_id, 
	$pg_objetivo_estrategico_id, 
	$pg_fator_critico_id, 
	$pg_estrategia_id,
	$pg_meta_id, 
	$pratica_id, 
	$pratica_indicador_id, 
	$plano_acao_id, 
	$canvas_id, 
	$risco_id,
	$risco_resposta_id,
	$calendario_id, 
	$monitoramento_id, 
	$ata_id, 
	$swot_id, 
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id;
$q = new BDConsulta;
$q->adTabela('foruns');
$q->adCampo('forum_id, forum_descricao, forum_dono, forum_nome, forum_contagem_msg, forum_ultima_data');
if ($pratica_id) $q->adOnde('forum_pratica = '.(int)$pratica_id);
elseif ($pratica_indicador_id) $q->adOnde('forum_indicador = '.(int)$pratica_indicador_id);
elseif ($pg_fator_critico_id) $q->adOnde('forum_fator = '.(int)$pg_fator_critico_id);
elseif ($pg_meta_id) $q->adOnde('forum_fator = '.(int)$pg_meta_id);
elseif ($pg_perspectiva_id) $q->adOnde('forum_perspectiva = '.(int)$pg_perspectiva_id);
elseif ($canvas_id) $q->adOnde('forum_canvas = '.(int)$canvas_id);
elseif ($tema_id) $q->adOnde('forum_tema = '.(int)$tema_id);
elseif ($pg_objetivo_estrategico_id) $q->adOnde('forum_objetivo = '.(int)$pg_objetivo_estrategico_id);
elseif ($pg_estrategia_id) $q->adOnde('forum_estrategia = '.(int)$pg_estrategia_id);
elseif ($plano_acao_id) $q->adOnde('forum_acao = '.(int)$plano_acao_id);
$q->adOrdem('forum_nome');
$rc=$q->Lista();
$q->limpar();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr><th nowrap="nowrap" width="16">&nbsp;</th>';
echo '<th nowrap="nowrap">'.dica('Assunto', 'Assunto do fórum.').'Assunto'.dicaF().'</th><th>'.dica('Descrição', 'Descrição do fórum.').'Descrição'.dicaF().'</th><th nowrap="nowrap">'.dica('Número de Mensagens', 'Número de mensagens contidas neste fórum.').'Msg'.dicaF().'</th><th nowrap="nowrap">'.dica('Última Postagem', 'Última postagem de mensagem neste fórum.').'Última Postagem'.dicaF().'</th></tr>';
$tf = $Aplic->getPref('formatohora');
$df = '%d/%m/%Y';
$qnt=0;
foreach ($rc as $linha)	{  
	$qnt++;
	$data = new CData($linha['forum_ultima_data']); 
	echo '<tr><td nowrap="nowrap" align="center">'.( $linha["forum_dono"] == $Aplic->usuario_id ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=editar&forum_id='.$linha['forum_id'].'\');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o fórum.').'</a>' : '').'</td>';
	echo '<td nowrap="nowrap">'.link_forum($linha['forum_id']).'</td><td>'.($linha['forum_descricao'] ? $linha['forum_descricao'] : '&nbsp;').'</td>';
	echo '<td nowrap="nowrap" align="center" width="40">'.($linha['forum_contagem_msg'] ? $linha['forum_contagem_msg'] : 0).'</td>';
	echo '<td nowrap="nowrap"  width="120" align="center">'.(intval($linha['forum_ultima_data']) > 0 ? $data->format($df.' '.$tf) : 'n/d').'</td></tr>';
	}
if (!$qnt) echo '<tr><td colspan="7"><p>Nenhum fórum encontrado.</p></td></tr>';
echo '</table>';
?>
