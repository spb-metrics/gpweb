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

global $Aplic, $negar1, $podeAcessar, $podeEditar,  $dialogo, $estilo_interface, $usuario_id, $cia_id, $dept_id, $lista_depts, $lista_cias, $mostrarProjeto, $m, $u, $a,$tab;

$pagina = getParam($_REQUEST, 'pagina', 1);
$pesquisa = getParam($_REQUEST, 'search', '');
$ordenarPor = getParam($_REQUEST, 'ordenar', 'pg_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');



if (!isset($projeto_id)) $projeto_id = getParam($_REQUEST, 'projeto_id', 0);
if (!isset($pratica_indicador_id)) $pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', 0);
if (!isset($pratica_id)) $pratica_id = getParam($_REQUEST, 'pratica_id', 0);
if (!isset($mostrarProjeto)) $mostrarProjeto = true;

$xpg_tamanhoPagina = $config['qnt_links'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 


$sql = new BDConsulta();

$sql->adTabela('plano_gestao');
if ($usuario_id) $sql->esqUnir('plano_gestao_usuario','plano_gestao_usuario','plano_gestao_usuario_plano=plano_gestao.pg_id');	
if ($usuario_id) $sql->adOnde('pg_usuario = '.(int)$usuario_id.' OR plano_gestao_usuario_usuario='.(int)$usuario_id); 

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('plano_gestao_dept', 'plano_gestao_dept', 'plano_gestao_dept_plano=plano_gestao.pg_id');
	$sql->adOnde('pg_dept='.(int)$dept_id.' OR plano_gestao_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('plano_gestao_dept', 'plano_gestao_dept', 'plano_gestao_dept_plano=plano_gestao.pg_id');
	$sql->adOnde('pg_dept IN ('.$lista_depts.') OR plano_gestao_dept_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('plano_gestao_cia', 'plano_gestao_cia', 'plano_gestao_cia_plano=plano_gestao.pg_id');
	$sql->adOnde('pg_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_gestao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('pg_cia IN ('.$lista_cias.')');	
	
if (!empty($pesquisa)) $sql->adOnde('(pg_nome LIKE \'%'.$pesquisa.'%\' OR pg_descricao LIKE \'%'.$pesquisa.'%\')');
if ($tab==0) $sql->adOnde('pg_ativo=1');
$sql->adCampo('count(DISTINCT plano_gestao.pg_id)');
$xpg_totalregistros = $sql->resultado();
$sql->limpar();


$sql->adTabela('plano_gestao');
if ($usuario_id) $sql->esqUnir('plano_gestao_usuario','plano_gestao_usuario','plano_gestao_usuario_plano=plano_gestao.pg_id');	
if ($usuario_id) $sql->adOnde('pg_usuario = '.(int)$usuario_id.' OR plano_gestao_usuario_usuario='.(int)$usuario_id); 
if ($dept_id && !$lista_depts) {
	$sql->esqUnir('plano_gestao_dept', 'plano_gestao_dept', 'plano_gestao_dept_plano=plano_gestao.pg_id');
	$sql->adOnde('pg_dept='.(int)$dept_id.' OR plano_gestao_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('plano_gestao_dept', 'plano_gestao_dept', 'plano_gestao_dept_plano=plano_gestao.pg_id');
	$sql->adOnde('pg_dept IN ('.$lista_depts.') OR plano_gestao_dept_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('plano_gestao_cia', 'plano_gestao_cia', 'plano_gestao_cia_plano=plano_gestao.pg_id');
	$sql->adOnde('pg_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_gestao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('pg_cia IN ('.$lista_cias.')');	
	
if (!empty($pesquisa)) $sql->adOnde('(pg_nome LIKE \'%'.$pesquisa.'%\' OR pg_descricao LIKE \'%'.$pesquisa.'%\')');
if ($tab==0) $sql->adOnde('pg_ativo=1');
elseif ($tab==1) $sql->adOnde('pg_ativo!=1 OR pg_ativo IS NULL');	
$sql->adCampo('pg_id, pg_nome, pg_descricao, pg_usuario, pg_cor, formatar_data(pg_inicio, "%d/%m/%Y") AS inicio, formatar_data(pg_fim, "%d/%m/%Y") AS fim, pg_acesso, pg_dept');
$sql->adOrdem($ordenarPor.($ordem ? ' DESC' : ' ASC'));
$sql->adGrupo('pg_id');
$sql->setLimite($xpg_min, $xpg_tamanhoPagina);
$linhas = $sql->Lista();
$sql->limpar();

$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1 && !$dialogo) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'Planejamento Estratégico', 'Planejamentos Estratégicos','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
echo '<th nowrap="nowrap">&nbsp;</th>';

echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($u ? '&u='.$u : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='pg_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação.').'Cor'.dicaF().'</a></th>';


echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($u ? '&u='.$u : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='pg_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identificação.').'Nome'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($u ? '&u='.$u : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='pg_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição', 'Neste campo fica a descrição pormenorizada.').'Descrição'.dicaF().'</th>';

echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($u ? '&u='.$u : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='pg_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Início', 'Neste campo fica a data de ínicio.').'Início'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($u ? '&u='.$u : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_fim&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='pg_fim' ? imagem('icones/'.$seta[$ordem]) : '').dica('Término', 'Neste campo fica a data de término.').'Término'.dicaF().'</th>';

echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($u ? '&u='.$u : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_dept&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='pg_dept' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['departamento']), 'Neste campo fica o nome d'.$config['genero_dept'].' '.$config['departamento'].' responsável.').ucfirst($config['departamento']).dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($u ? '&u='.$u : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='pg_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'Neste campo fica o nome d'.$config['genero_usuario'].' '.$config['usuario'].' responsável.').'Responsável'.dicaF().'</th>';

echo '</tr>';

$qnt=0;
foreach ($linhas as $linha) {
	if (permiteAcessarPlanoGestao($linha['pg_acesso'],$linha['pg_id'])) {
		$qnt++;
		$editar=permiteEditarPlanoGestao($linha['pg_acesso'],$linha['pg_id']);
		echo '<tr>';
		echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o planejamentos estratégico.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&u=gestao&a=gestao_editar&pg_id='.$linha['pg_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		
		
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['pg_cor'].'"><font color="'.melhorCor($linha['pg_cor']).'">&nbsp;&nbsp;</font></td>';
		
		echo '<td nowrap="nowrap">'.dica($linha['pg_nome'], 'Clique para visualizar os detalhes deste planejamentos estratégico.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&u=gestao&a=menu&pg_id='.$linha['pg_id'].'\');">'.$linha['pg_nome'].'</a>'.dicaF().'</td>';
		echo '<td>'.($linha['pg_descricao'] ? $linha['pg_descricao'] : '&nbsp;').'</td>';
		echo '<td width=80 align=center>'.($linha['inicio'] ? $linha['inicio'] : '&nbsp;').'</td>';
		echo '<td width=80 align=center>'.($linha['fim'] ? $linha['fim'] : '&nbsp;').'</td>';
		echo '<td>'.link_secao($linha['pg_dept']).'</td>';
		echo '<td>'.link_usuario($linha['pg_usuario'],'','','esquerda').'</td>';
		
		echo '</tr>';
		}
	}
if (!count($linhas)) echo '<tr><td colspan=20><p>Nenhum planejamentos estratégico encontrado.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="8"><p>Não tem autorização para visualizar nenhum dos planejamentos estratégico.</p></td></tr>';		
echo '</table>';
?>