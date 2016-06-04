<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\social\ver_idx_social.php		
																													
																																												
********************************************************************************************/ 

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $sql, $perms, $Aplic, $cia_id, $lista_cias, $tab, $tabAtualId, $tabNomeAtual, $estah_tab, $st_praticas_arr, $ordem, $ordenar, $id, $perspectiva_tab, $dialogo;




$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$pagina = getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_estrategias']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'social_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('social');
$sql->adCampo('social.social_id, social_nome, social_responsavel, social_acesso, social_cor, social_descricao');
if ($cia_id && !$lista_cias) $sql->adOnde('social_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('social_cia IN ('.$lista_cias.')');	
	
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->adGrupo('social.social_id');
$social=$sql->Lista();
$sql->limpar();



$xtotalregistros = ($social ? count($social) : 0);
$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Programa Social', 'Programas Sociais','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor do Programa Social', 'Neste campo fica a cor de identificação do programa social.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome do Programa Social', 'Neste campo fica um nome para identificação do programa social.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição do Programa Social', 'Neste campo fica a descrição do programa social.').'Descrição'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=social_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pelo programa social.').'Responsável'.dicaF().'</a></th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = 0; $i < count($social); $i++) {
	$linha = $social[$i];
	$qnt++;
	$editar=(permiteEditarSocial($linha['social_acesso'],$linha['social_id']) && ($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_social')));
	$ver=permiteAcessarSocial($linha['social_acesso'],$linha['social_id']);
	
	
	
	
	echo '<tr>';
	if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar Social', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o programa social.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=social_editar&social_id='.$linha['social_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['social_cor'].'"><font color="'.melhorCor($linha['social_cor']).'">&nbsp;&nbsp;</font></td>';
	
	if ($ver) echo '<td>'.link_social($linha['social_id']).'</td>';
	else echo '<td nowrap="nowrap">'.dica('Social', 'Não tem permissão para ver os detalhes deste programa social.').'<i>'.$linha['social_nome'].'</i>'.dicaF().'</td>';
	
	if ($ver) echo '<td>'.($linha['social_descricao'] ? $linha['social_descricao'] : '&nbsp;').'</td>';
	else echo '<td nowrap="nowrap">'.dica('Social', 'Não tem permissão para ver a descrição deste programa social.').'<i>Descrição</i>'.dicaF().'</td>';
	echo '<td nowrap="nowrap">'.link_usuario($linha['social_responsavel'],'','','esquerda').'</td>';
	echo '</tr>';

	}
if (!count($social)) echo '<tr><td colspan=20><p>Nenhum programa social encontrado.</p></td></tr>';
echo '</table>';

?>