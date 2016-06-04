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

global $estilo_interface, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $social_id , $pesquisa;


$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = $config['qnt_projetos'];
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'social_acao_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('social_acao');
$sql->adCampo('count(DISTINCT social_acao.social_acao_id)');
if ($social_id) $sql->adOnde('social_acao_social="'.$social_id.'"');
if ($pesquisa) $sql->adOnde('(social_acao_nome LIKE \'%'.$pesquisa.'%\' OR social_acao_descricao LIKE \'%'.$pesquisa.'%\')');
$xtotalregistros=$sql->Resultado();
$sql->limpar();


$sql->adTabela('social_acao');
$sql->adCampo('DISTINCT social_acao.social_acao_id, social_acao_cor, social_acao_nome, social_acao_descricao');
if ($social_id) $sql->adOnde('social_acao_social="'.$social_id.'"');
if ($pesquisa) $sql->adOnde('(social_acao_nome LIKE \'%'.$pesquisa.'%\' OR social_acao_descricao LIKE \'%'.$pesquisa.'%\')');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $config['qnt_projetos']);

$acao=$sql->Lista();
$sql->limpar();


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Ação', 'Ações','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_acao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_acao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor da Ação Social', 'Neste campo fica a cor de identificação da ação social.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_acao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_acao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da Ação Socia', 'Neste campo fica um nome para identificação da ação social.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_acao_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_acao_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição da Ação Socia', 'Neste campo fica a descrição da ação social.').'Descrição'.dicaF().'</a></th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = 0; $i < count($acao); $i++) {
	$linha = $acao[$i];
	$qnt++;

	
	echo '<tr>';
	if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="20">'.($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_acao') ? dica('Editar Social', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o programa social.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=acao_editar&social_acao_id='.$linha['social_acao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['social_acao_cor'].'"><font color="'.melhorCor($linha['social_acao_cor']).'">&nbsp;&nbsp;</font></td>';
	echo '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=acao_ver&social_acao_id='.$linha['social_acao_id'].'\');">'.($linha['social_acao_nome'] ? $linha['social_acao_nome'] : '&nbsp;').'</a></td>';
	echo '<td>'.($linha['social_acao_descricao'] ? $linha['social_acao_descricao'] : '&nbsp;').'</td>';
	echo '</tr>';

	}
if (!count($acao)) echo '<tr><td colspan=20><p>Nenhuma ação social encontrada.</p></td></tr>';
echo '</table>';

?>