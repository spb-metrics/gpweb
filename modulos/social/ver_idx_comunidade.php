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

global $estilo_interface, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $estado_sigla, $municipio_id , $pesquisa;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = $config['qnt_projetos'];
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'social_comunidade_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('social_comunidade');
$sql->adCampo('count(DISTINCT social_comunidade.social_comunidade_id)');
if ($estado_sigla) $sql->adOnde('social_comunidade_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_comunidade_municipio="'.$municipio_id.'"');
if ($pesquisa) $sql->adOnde('(social_comunidade_nome LIKE \'%'.$pesquisa.'%\' OR social_comunidade_descricao LIKE \'%'.$pesquisa.'%\')');
$xtotalregistros=$sql->Resultado();
$sql->limpar();


$sql->adTabela('social_comunidade');
$sql->adCampo('DISTINCT social_comunidade.social_comunidade_id, social_comunidade_cor, social_comunidade_nome, social_comunidade_descricao');
if ($estado_sigla) $sql->adOnde('social_comunidade_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_comunidade_municipio="'.$municipio_id.'"');
if ($pesquisa) $sql->adOnde('(social_comunidade_nome LIKE \'%'.$pesquisa.'%\' OR social_comunidade_descricao LIKE \'%'.$pesquisa.'%\')');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $config['qnt_projetos']);
$comunidade=$sql->Lista();
$sql->limpar();


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Comunidade', 'Comunidades','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_comunidade_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_comunidade_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor da Comunidade', 'Neste campo fica a cor de identifica��o da comunidade.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_comunidade_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_comunidade_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da Comunidade', 'Neste campo fica um nome para identifica��o da comunidade.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_comunidade_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_comunidade_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descri��o da Comunidade', 'Neste campo fica a descri��o da comunidade.').'Descri��o'.dicaF().'</a></th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = 0; $i < count($comunidade); $i++) {
	$linha = $comunidade[$i];
	$qnt++;

	
	echo '<tr>';
	if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="20">'.($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_comunidade') ? dica('Editar Social', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o programa social.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=comunidade_editar&social_comunidade_id='.$linha['social_comunidade_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['social_comunidade_cor'].'"><font color="'.melhorCor($linha['social_comunidade_cor']).'">&nbsp;&nbsp;</font></td>';
	echo '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=comunidade_ver&social_comunidade_id='.$linha['social_comunidade_id'].'\');">'.($linha['social_comunidade_nome'] ? $linha['social_comunidade_nome'] : '&nbsp;').'</a></td>';
	echo '<td>'.($linha['social_comunidade_descricao'] ? $linha['social_comunidade_descricao'] : '&nbsp;').'</td>';
	echo '</tr>';

	}
if (!count($comunidade)) echo '<tr><td colspan=20><p>Nenhuma comunidade encontrada.</p></td></tr>';
echo '</table>';

?>