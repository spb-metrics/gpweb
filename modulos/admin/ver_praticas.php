<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $estilo_interface,$tab, $perms, $cia_id, $usuario_id, $dialogo;
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$sql = new BDConsulta();
$sql->adTabela('pratica_requisito');
$sql->esqUnir('praticas','praticas', 'praticas.pratica_id=pratica_requisito.pratica_id');
$sql->adCampo('DISTINCT ano');
if ($usuario_id) $sql->adOnde('pratica_responsavel IN ('.$usuario_id.')');
else if ($cia_id) $sql->adOnde('pratica_cia='.(int)$cia_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);


$ano = ($Aplic->getEstado('IdxPraticaAno') !== null ? $Aplic->getEstado('IdxPraticaAno') : $ultimo_ano);
$ordenar = getParam($_REQUEST, 'ordenar_pratica', 'pratica_id');
$ordem = getParam($_REQUEST, 'ordem', '0');

$titulo=array();

$todos=array();

$sql->adTabela('praticas');
$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
$sql->esqUnir('pratica_depts', 'pratica_depts', 'pratica_depts.pratica_id=praticas.pratica_id');
$sql->esqUnir('pratica_usuarios', 'pratica_usuarios', 'pratica_usuarios.pratica_id=praticas.pratica_id');
if ($usuario_id) $sql->adOnde('pratica_responsavel IN ('.$usuario_id.') OR pratica_usuarios.usuario_id IN ('.$usuario_id.')');
else if ($cia_id) $sql->adOnde('pratica_cia='.(int)$cia_id);
if ($ano) $sql->adOnde('pratica_requisito.ano='.(int)$ano);
$sql->adCampo('DISTINCT praticas.pratica_id, pratica_nome, pratica_descricao, pratica_cor, pratica_responsavel, pratica_acesso');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$praticas=$sql->Lista();
$sql->limpar();

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$pagina = getParam($_REQUEST, 'pagina', 1);

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$xpg_tamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_praticas']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$xpg_totalregistros = ($praticas ? count($praticas) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'prática', 'práticas','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar_pratica=pratica_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Cor'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar_pratica=pratica_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica o nome para identificação d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Nome'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar_pratica=pratica_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição', 'Detalhes sobre do que se trata '.$config['genero_pratica'].' '.$config['pratica'].'.').'Descrição'.dicaF().'</a></th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $praticas[$i];
	$qnt++;
	$editar=permiteEditarPratica($linha['pratica_acesso'], $linha['pratica_id']);
	
	echo '<tr>';
	if (!$impressao && $editar) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_pratica'].' '.$config['pratica'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=pratica_editar&pratica_id='.$linha['pratica_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['pratica_cor'].'"><font color="'.melhorCor($linha['pratica_cor']).'">&nbsp;&nbsp;</font></td>';
	echo '<td>'.link_pratica($linha['pratica_id']).'</td>';
	echo '<td>'.($linha['pratica_descricao'] ? $linha['pratica_descricao'] : '&nbsp;').'</td>';
	echo '</tr>';

	}
if (!count($praticas)) echo '<tr><td colspan=20><p>Nenhum'.($config['genero_pratica']=='a' ? 'a': '').' '.$config['pratica'].' encontrad'.$config['genero_pratica'].'.</p></td></tr>';
echo '</table>';
?>
