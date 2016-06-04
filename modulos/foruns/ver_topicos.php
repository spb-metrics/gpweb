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
if (isset($_REQUEST['ordemPor'])) {
	$ordemDir = $Aplic->getEstado('ForumVerOrdemDir') ? ($Aplic->getEstado('ForumVerOrdemDir') == 'asc' ? 'desc' : 'asc') : 'desc';
	$Aplic->setEstado('ForumVerOrdemPor', getParam($_REQUEST, 'ordemPor', null));
	$Aplic->setEstado('ForumVerOrdemDir', $ordemDir);
	}
$ordenarPor = $Aplic->getEstado('ForumVerOrdemPor') ? $Aplic->getEstado('ForumVerOrdemPor') : 'ultima_resposta';
$ordemDir = $Aplic->getEstado('ForumVerOrdemDir') ? $Aplic->getEstado('ForumVerOrdemDir') : 'desc';
$q = new BDConsulta;
$q->adTabela('forum_mensagens', 'fm1');
$q->adCampo('fm1.*');
$q->adCampo('COUNT(distinct fm2.mensagem_id) AS respostas');
$q->adCampo('MAX(fm2.mensagem_data) AS ultima_resposta');
$q->adCampo('usuario_login, contato_id, acompanhar_usuario');
$q->adCampo('count(distinct v1.visita_mensagem) as visitas_resposta');
$q->adCampo('v1.visita_usuario');
$q->esqUnir('usuarios', 'u', 'fm1.mensagem_autor = u.usuario_id');
$q->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
$q->esqUnir('forum_mensagens', 'fm2', 'fm1.mensagem_id = fm2.mensagem_superior AND fm2.mensagem_superior!=0');
$q->esqUnir('forum_acompanhar', 'fw', 'acompanhar_usuario = '.(int)$Aplic->usuario_id.' AND acompanhar_topico = fm1.mensagem_id');
$q->esqUnir('forum_visitas', 'v1', 'v1.visita_usuario = '.(int)$Aplic->usuario_id.' AND v1.visita_mensagem = fm1.mensagem_id');
$q->adOnde('fm1.mensagem_forum = '.(int)$forum_id);


switch ($f) {
	case 1:
		$q->adOnde('acompanhar_usuario IS NOT NULL');
		break;
	case 2:
		$q->adOnde('(NOW() < adiciona_data(fm2.mensagem_data, 30, \'DAY\') OR NOW() < adiciona_data(fm1.mensagem_data, 30, \'DAY\'))');
		break;
	}

//EUZ
//$q->adGrupo('fm1.mensagem_id, fm1.mensagem_superior');
$q->adGrupo('fm1.mensagem_id, fm1.mensagem_superior, u.usuario_login, con.contato_id, fw.acompanhar_usuario, v1.visita_usuario');
//EUD
$q->adOrdem($ordenarPor.' '.$ordemDir);
$topicos = $q->Lista();

$blocos = array();
$blocos['m=foruns'] = 'lista de fóruns';
$titulo_blocos = array();
$titulo_blocos['m=foruns'] = 'Lista de Fóruns';
$texto_blocos = array();
$texto_blocos['m=foruns'] = 'Visualizar a lista de fóruns existentes.';

echo '<form name="frm_topico_acompanhar" method="post">';
echo '<input type="hidden" name="m" value="foruns" />';
echo '<input type="hidden" name="a" value="ver" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_acompanhar_forum" />';
echo '<input type="hidden" name="acompanhar" value="topico" />';
echo '<input type="hidden" name="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="f" value="'.$f.'" />';

echo '<tr><td colspan="5"><table width="100%" cellspacing="1" cellpadding="2" border=0><tr><td align="left" width="15%">'.mostrarBlocos($blocos, $titulo_blocos, $texto_blocos).'</td><td width="100%" align="right">'.($podeEditar ? botao('novo tópico', 'Novo Tópico', 'Criar um novo tópico.<br><br>Cada fórum deverá ter ao menos um tópico, pois é dentro dos tópicos que '.$config['genero_usuario'].'s '.$config['usuarios'].' postam as respostas.','','url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&postar_mensagem=1\');','','',0) : '').'</td></tr></table></td></tr>';
echo '<table width="100%" cellspacing=0 cellpadding="2" border=0 class="tbl1"><tr>';
echo '<th>'.dica('Acompanhar', 'Marque as caixas abaixo e clique o botão <b>acompanhar</b> para ser informado sobre atualizações nos tópicos marcados.<br><br>Quando se está acompanhando um tópico, o sistema avisa caso houver mensagens não lidas.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&ordemPor=acompanhar_usuario\');" class="hdr">Acomp.</a>'.dicaF().'</th>';
echo '<th>'.dica('Tópicos', 'Cada fórum pode ter um ou mais tópicos.<br><br>Pode imaginar tópicos como subassuntos do fórum ou perguntas relacionadas ao fórum.').'<ahref="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&ordemPor=mensagem_titulo\');" class="hdr">Tópicos</a>'.dicaF().'</th>';
echo '<th>'.dica('Autor', 'Cada tópico ter um autor, que é o criador do mesmo.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&ordemPor=usuario_login\');" class="hdr">Autor</a>'.dicaF().'</th>';
echo '<th>'.dica('Respostas', 'Cada tópico poderá ter diversas respostas (postagens).').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&ordemPor=respostas\');" class="hdr">Respostas</a>'.dicaF().'</th>';
echo '<th>'.dica('Última Postagem', 'Data da última resposta inserida no tópico.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&ordemPor=ultima_resposta\');" class="hdr">Última Postagem</a>'.dicaF().'</th>';
echo '</tr>';

$agora = new CData();
foreach ($topicos as $linha) {
	$ultimo = intval($linha['ultima_resposta']) ? new CData($linha['ultima_resposta']) : null;
	if ($linha["mensagem_superior"] == 0) { 
	echo '<tr>';
	echo '<td nowrap="nowrap" align="center" width="1%">'.dica('Acompanhar', 'Marque esta caixa e clique o botão <b>acompanhar</b> para ser informado sobre atualizações neste tópico.<br><br>Caso esteja acompanhando este tópico, o sistema avisará se houver mensagens não lidas.').'<input type="checkbox" name="forum_'.$linha['mensagem_id'].'" '.($linha['acompanhar_usuario'] ? 'checked="checked"' : '').' />'.dicaF().'</td>';
	echo '<td>';
	if ($linha['visita_usuario'] != $Aplic->usuario_id || $linha['visitas_resposta'] != $linha['respostas']) echo imagem('icones/msg_nova.png','Mensagem Não Lida','Você tem mensagens não lidas neste tópico.');
	echo dica($linha['mensagem_titulo'], 'Clique neste tópico para ler as respostas.').'<span style="font-size:10pt;"><a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_id='.$linha["mensagem_id"].'\');">'.$linha['mensagem_titulo'].'</a></span>'.dicaF();
	echo '</td>';
	echo '<td bgcolor="#dddddd" nowrap="nowrap">'.link_contato($linha['contato_id'],'','','esquerda').'</td>';
	echo '<td align="center" width="40">'.$linha['respostas'].'</td>';
	echo '<td bgcolor="#dddddd" width="110" nowrap="nowrap">';
	if ($linha['ultima_resposta']) {
			echo $ultimo->format($df.' '.$tf).'<br /><font color="#999966">(';
			$intervalo = new Data_Intervalo();
			$intervalo->setFromDateDiff($agora, $ultimo);
			printf('%.1f', $intervalo->format('%d'));
			echo ' dias atrás)</font>';
			} 
		else echo 'sem resposta';
		echo '</td></tr>';
		}
	} 
echo '</table>';
echo '<table width="100%" border=0 cellpadding=0 cellspacing="1" class="std">';
echo '<tr><td align="left">'.botao('acompanhar', 'Acompanhar', 'Acompanhar os tópicos marcados acima.<br><br>Quando se está acompanhando um tópico, o sistema avisa caso houver mensagens não lidas.','','frm_topico_acompanhar.submit()').'</td></tr>';
echo '</form></table>';
?>