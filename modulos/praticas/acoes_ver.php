<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


global $pratica_id, $podeEditar, $podeAdicionar;


$ordenar = getParam($_REQUEST, 'ordenar', 'plano_acao_nome');
$ordem = getParam($_REQUEST, 'ordem', '1');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;

$direcao = getParam($_REQUEST, 'cmd', '');
$plano_acao_id = getParam($_REQUEST, 'plano_acao_id', '0');
$ordem = getParam($_REQUEST, 'ordem', '0');


echo '<table id="tblPraticas" border=0 cellpadding=0 cellspacing=1 width="100%" class="std">';


$sql->adTabela('plano_acao');
$sql->adCampo('plano_acao_id, plano_acao_descricao, plano_acao_acesso, plano_acao_cor');
$sql->adOnde('plano_acao_pratica = '.$pratica_id);
$sql->adOrdem($ordenar.($ordem ? ' ASC' : ' DESC'));
$acoes = $sql->Lista();
$sql->limpar();

echo '<table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1">';
echo '<tr>';
if ($podeEditar) echo '<th width="16">'.($podeAdicionar ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_editar&plano_acao_pratica='.$pratica_id.'\');">'.imagem('icones/adicionar.png', 'Adicionar '.$config['acao'],'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar uma nov'.$config['genero_acao'].' '.$config['acao'].' a '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'</a>' : '').'</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=pratica_ver&tab=0&pratica_id='.$pratica_id.'&ordenar=plano_acao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Cor d'.$config['genero_acao'].' '.$config['acao'].'.').'Cor'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=pratica_ver&tab=0&pratica_id='.$pratica_id.'&ordenar=plano_acao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Nome d'.$config['genero_acao'].' '.$config['acao'].'.').'Nome'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=pratica_ver&tab=0&pratica_id='.$pratica_id.'&ordenar=plano_acao_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição', 'Descrição d'.$config['genero_acao'].' '.$config['acao'].'.').'Descrição'.dicaF().'</a></th>';
echo '</tr>';

foreach($acoes as $acao){

		echo '<tr>';
		if ($podeEditar) echo (permiteEditarPlanoAcao($acao['plano_acao_acesso'],$acao['plano_acao_id']) ? '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_editar&plano_acao_id='.$acao['plano_acao_id'].'\');">'.imagem('icones/editar.gif','Editar','Clique neste ícone '.imagem('icones/editar.gif').' para editar esta ação.').'</a></td>' : '<td></td>');
		echo '<td style="background-color:#'.$acao['plano_acao_cor'].'; width:20px;">&nbsp;</td>';	
		echo '<td>'.link_acao($acao['plano_acao_id']).'</td>';
		echo '<td>'.($acao['plano_acao_descricao'] ? $acao['plano_acao_descricao'] : '&nbsp;').'</td>';
		echo '</tr>';

		}
if (!$acoes || !count($acoes)) echo '<tr><td colspan=20>Não há '.$config['acao'].' cadastrad'.$config['genero_acao'].'s</td></tr>';

echo '</table>';

?>