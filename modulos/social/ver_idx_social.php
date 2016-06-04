<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\social\ver_idx_social.php		
																													
																																												
********************************************************************************************/ 

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

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
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor do Programa Social', 'Neste campo fica a cor de identifica��o do programa social.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome do Programa Social', 'Neste campo fica um nome para identifica��o do programa social.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descri��o do Programa Social', 'Neste campo fica a descri��o do programa social.').'Descri��o'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=social_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Respons�vel', 'O '.$config['usuario'].' respons�vel pelo programa social.').'Respons�vel'.dicaF().'</a></th>';
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
	if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar Social', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o programa social.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=social_editar&social_id='.$linha['social_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['social_cor'].'"><font color="'.melhorCor($linha['social_cor']).'">&nbsp;&nbsp;</font></td>';
	
	if ($ver) echo '<td>'.link_social($linha['social_id']).'</td>';
	else echo '<td nowrap="nowrap">'.dica('Social', 'N�o tem permiss�o para ver os detalhes deste programa social.').'<i>'.$linha['social_nome'].'</i>'.dicaF().'</td>';
	
	if ($ver) echo '<td>'.($linha['social_descricao'] ? $linha['social_descricao'] : '&nbsp;').'</td>';
	else echo '<td nowrap="nowrap">'.dica('Social', 'N�o tem permiss�o para ver a descri��o deste programa social.').'<i>Descri��o</i>'.dicaF().'</td>';
	echo '<td nowrap="nowrap">'.link_usuario($linha['social_responsavel'],'','','esquerda').'</td>';
	echo '</tr>';

	}
if (!count($social)) echo '<tr><td colspan=20><p>Nenhum programa social encontrado.</p></td></tr>';
echo '</table>';

?>