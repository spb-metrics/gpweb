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
$blocos['m=foruns'] = 'lista de f�runs';
$titulo_blocos = array();
$titulo_blocos['m=foruns'] = 'Lista de F�runs';
$texto_blocos = array();
$texto_blocos['m=foruns'] = 'Visualizar a lista de f�runs existentes.';

echo '<form name="frm_topico_acompanhar" method="post">';
echo '<input type="hidden" name="m" value="foruns" />';
echo '<input type="hidden" name="a" value="ver" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_acompanhar_forum" />';
echo '<input type="hidden" name="acompanhar" value="topico" />';
echo '<input type="hidden" name="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="f" value="'.$f.'" />';

echo '<tr><td colspan="5"><table width="100%" cellspacing="1" cellpadding="2" border=0><tr><td align="left" width="15%">'.mostrarBlocos($blocos, $titulo_blocos, $texto_blocos).'</td><td width="100%" align="right">'.($podeEditar ? botao('novo t�pico', 'Novo T�pico', 'Criar um novo t�pico.<br><br>Cada f�rum dever� ter ao menos um t�pico, pois � dentro dos t�picos que '.$config['genero_usuario'].'s '.$config['usuarios'].' postam as respostas.','','url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&postar_mensagem=1\');','','',0) : '').'</td></tr></table></td></tr>';
echo '<table width="100%" cellspacing=0 cellpadding="2" border=0 class="tbl1"><tr>';
echo '<th>'.dica('Acompanhar', 'Marque as caixas abaixo e clique o bot�o <b>acompanhar</b> para ser informado sobre atualiza��es nos t�picos marcados.<br><br>Quando se est� acompanhando um t�pico, o sistema avisa caso houver mensagens n�o lidas.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&ordemPor=acompanhar_usuario\');" class="hdr">Acomp.</a>'.dicaF().'</th>';
echo '<th>'.dica('T�picos', 'Cada f�rum pode ter um ou mais t�picos.<br><br>Pode imaginar t�picos como subassuntos do f�rum ou perguntas relacionadas ao f�rum.').'<ahref="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&ordemPor=mensagem_titulo\');" class="hdr">T�picos</a>'.dicaF().'</th>';
echo '<th>'.dica('Autor', 'Cada t�pico ter um autor, que � o criador do mesmo.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&ordemPor=usuario_login\');" class="hdr">Autor</a>'.dicaF().'</th>';
echo '<th>'.dica('Respostas', 'Cada t�pico poder� ter diversas respostas (postagens).').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&ordemPor=respostas\');" class="hdr">Respostas</a>'.dicaF().'</th>';
echo '<th>'.dica('�ltima Postagem', 'Data da �ltima resposta inserida no t�pico.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&ordemPor=ultima_resposta\');" class="hdr">�ltima Postagem</a>'.dicaF().'</th>';
echo '</tr>';

$agora = new CData();
foreach ($topicos as $linha) {
	$ultimo = intval($linha['ultima_resposta']) ? new CData($linha['ultima_resposta']) : null;
	if ($linha["mensagem_superior"] == 0) { 
	echo '<tr>';
	echo '<td nowrap="nowrap" align="center" width="1%">'.dica('Acompanhar', 'Marque esta caixa e clique o bot�o <b>acompanhar</b> para ser informado sobre atualiza��es neste t�pico.<br><br>Caso esteja acompanhando este t�pico, o sistema avisar� se houver mensagens n�o lidas.').'<input type="checkbox" name="forum_'.$linha['mensagem_id'].'" '.($linha['acompanhar_usuario'] ? 'checked="checked"' : '').' />'.dicaF().'</td>';
	echo '<td>';
	if ($linha['visita_usuario'] != $Aplic->usuario_id || $linha['visitas_resposta'] != $linha['respostas']) echo imagem('icones/msg_nova.png','Mensagem N�o Lida','Voc� tem mensagens n�o lidas neste t�pico.');
	echo dica($linha['mensagem_titulo'], 'Clique neste t�pico para ler as respostas.').'<span style="font-size:10pt;"><a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'&mensagem_id='.$linha["mensagem_id"].'\');">'.$linha['mensagem_titulo'].'</a></span>'.dicaF();
	echo '</td>';
	echo '<td bgcolor="#dddddd" nowrap="nowrap">'.link_contato($linha['contato_id'],'','','esquerda').'</td>';
	echo '<td align="center" width="40">'.$linha['respostas'].'</td>';
	echo '<td bgcolor="#dddddd" width="110" nowrap="nowrap">';
	if ($linha['ultima_resposta']) {
			echo $ultimo->format($df.' '.$tf).'<br /><font color="#999966">(';
			$intervalo = new Data_Intervalo();
			$intervalo->setFromDateDiff($agora, $ultimo);
			printf('%.1f', $intervalo->format('%d'));
			echo ' dias atr�s)</font>';
			} 
		else echo 'sem resposta';
		echo '</td></tr>';
		}
	} 
echo '</table>';
echo '<table width="100%" border=0 cellpadding=0 cellspacing="1" class="std">';
echo '<tr><td align="left">'.botao('acompanhar', 'Acompanhar', 'Acompanhar os t�picos marcados acima.<br><br>Quando se est� acompanhando um t�pico, o sistema avisa caso houver mensagens n�o lidas.','','frm_topico_acompanhar.submit()').'</td></tr>';
echo '</form></table>';
?>