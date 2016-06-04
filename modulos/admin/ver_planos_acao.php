<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $estilo_interface,$tab, $cia_id, $usuario_id, $dialogo;
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$ordenar = getParam($_REQUEST, 'ordenar_plano_acao', 'plano_acao_id');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql = new BDConsulta();

$titulo=array();

$todos=array();

$sql->adTabela('plano_acao');
$sql->esqUnir('plano_acao_usuarios', 'plano_acao_usuarios', 'plano_acao_usuarios.plano_acao_id=plano_acao.plano_acao_id');
if ($usuario_id) $sql->adOnde('plano_acao_responsavel ='.(int)$usuario_id.' OR plano_acao_usuarios.usuario_id ='.(int)$usuario_id);
elseif ($cia_id) $sql->adOnde('plano_acao_cia='.(int)$cia_id);
$sql->adCampo('DISTINCT plano_acao.plano_acao_id, plano_acao_nome, plano_acao_descricao, plano_acao_cor, plano_acao_responsavel, plano_acao_acesso');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$acoes=$sql->Lista();
$sql->limpar();

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$pagina = getParam($_REQUEST, 'pagina', 1);

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$xpg_tamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_praticas']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$xpg_totalregistros = ($acoes ? count($acoes) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, strtolower($config['acao']), strtolower($config['acoes']),'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar_plano_acao=plano_acao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação d'.$config['genero_acao'].' '.$config['acao'].'.').'Cor'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar_plano_acao=plano_acao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica o nome para identificação d'.$config['genero_acao'].' '.$config['acao'].'.').'Nome'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar_plano_acao=plano_acao_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição', 'Detalhes sobre do que se trata '.$config['genero_acao'].' '.$config['acao'].'.').'<b>Descrição</b>'.dicaF().'</a></th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $acoes[$i];
	$qnt++;
	$editar=permiteEditarPlanoAcao($linha['plano_acao_acesso'], $linha['plano_acao_id']);
	
	echo '<tr>';
	if (!$impressao && $editar) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar este '.$config['acao'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_editar&plano_acao_id='.$linha['plano_acao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['plano_acao_cor'].'"><font color="'.melhorCor($linha['plano_acao_cor']).'">&nbsp;&nbsp;</font></td>';
	echo '<td>'.link_acao($linha['plano_acao_id']).'</td>';
	echo '<td>'.($linha['plano_acao_descricao'] ? $linha['plano_acao_descricao'] : '&nbsp;').'</td>';
	echo '</tr>';

	}
if (!count($acoes)) echo '<tr><td colspan=20><p>Nenhum'.($config['genero_acao']=='a' ? 'a': '').' '.$config['acao'].' encontrad'.$config['genero_acao'].'.</p></td></tr>';
echo '</table>';
?>
