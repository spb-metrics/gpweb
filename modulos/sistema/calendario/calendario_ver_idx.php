<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $sql, $perms, $dialogo, $Aplic, $cia_id, $dept_id, $lista_depts, $tab, $lista_cias, $favorito_id, $usuario_id, $pesquisar_texto, $u, $a, $m;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$pagina = getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = 90000;
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'calendario_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('calendario');
$sql->adCampo('count(DISTINCT calendario.calendario_id)');

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('calendario_dept','calendario_dept', 'calendario_dept_calendario=calendario.calendario_id');
	$sql->adOnde('calendario_dept='.(int)$dept_id.' OR calendario_dept.dept_id='.(int)$dept_id);
	}	
elseif ($lista_depts) {
	$sql->esqUnir('calendario_dept','calendario_dept', 'calendario_dept.calendario_id=calendario.calendario_id');
	$sql->adOnde('calendario_dept IN ('.$lista_depts.') OR calendario_dept.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('calendario_cia', 'calendario_cia', 'calendario.calendario_id=calendario_cia_calendario');
	$sql->adOnde('calendario_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR calendario_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('calendario_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('calendario_cia IN ('.$lista_cias.')');	
		
if ($tab==0) $sql->adOnde('calendario_ativo=1');
elseif ($tab==1) $sql->adOnde('calendario_ativo!=1 OR calendario_ativo IS NULL');	

if($usuario_id) {
	$sql->esqUnir('calendario_usuario', 'calendario_usuario', 'calendario.calendario_id=calendario_usuario_calendario');
	$sql->adOnde('calendario_usuario='.(int)$usuario_id.' OR calendario_usuario_usuario='.(int)$usuario_id);
	}
	
if ($pesquisar_texto) $sql->adOnde('calendario_nome LIKE \'%'.$pesquisar_texto.'%\' OR calendario_descricao LIKE \'%'.$pesquisar_texto.'%\'');

$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('calendario');
$sql->adCampo('DISTINCT calendario.calendario_id, calendario_nome, calendario_usuario, calendario_acesso, calendario_cor, calendario_descricao');


if ($dept_id && !$lista_depts) {
	$sql->esqUnir('calendario_dept','calendario_dept', 'calendario_dept_calendario=calendario.calendario_id');
	$sql->adOnde('calendario_dept='.(int)$dept_id.' OR calendario_dept.dept_id='.(int)$dept_id);
	}	
elseif ($lista_depts) {
	$sql->esqUnir('calendario_dept','calendario_dept', 'calendario_dept.calendario_id=calendario.calendario_id');
	$sql->adOnde('calendario_dept IN ('.$lista_depts.') OR calendario_dept.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('calendario_cia', 'calendario_cia', 'calendario.calendario_id=calendario_cia_calendario');
	$sql->adOnde('calendario_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR calendario_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('calendario_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('calendario_cia IN ('.$lista_cias.')');	
		
if ($tab==0) $sql->adOnde('calendario_ativo=1');
elseif ($tab==1) $sql->adOnde('calendario_ativo!=1 OR calendario_ativo IS NULL');	

if($usuario_id) {
	$sql->esqUnir('calendario_usuario', 'calendario_usuario', 'calendario.calendario_id=calendario_usuario_calendario');
	$sql->adOnde('calendario_usuario='.(int)$usuario_id.' OR calendario_usuario_usuario='.(int)$usuario_id);
	}
if ($tab==0) $sql->adOnde('calendario_ativo=1');
elseif ($tab==1) $sql->adOnde('calendario_ativo!=1 OR calendario_ativo IS NULL');
if ($pesquisar_texto) $sql->adOnde('calendario_nome LIKE \'%'.$pesquisar_texto.'%\' OR calendario_descricao LIKE \'%'.$pesquisar_texto.'%\'');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$calendario=$sql->Lista();
$sql->limpar();



$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Agenda Coletiva', 'Agenda Coletiva','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="'.($dialogo ? '750' : '100%').'" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=calendario_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='calendario_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor da '.'Agenda Coletiva'.'', 'Neste campo fica a cor de identificação da agenda coletiva.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=calendario_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='calendario_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da '.'Agenda Coletiva'.'', 'Neste campo fica um nome para identificação da agenda coletiva.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=calendario_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='calendario_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição da '.'Agenda Coletiva'.'', 'Neste campo fica a descrição da agenda coletiva.').'Descrição'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=calendario_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='calendario_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pela agenda coletiva.').'Responsável'.dicaF().'</a></th>';
echo '</tr>';

$qnt=0;
for ($j = 0; $j < count($calendario); $j++) {
	$linha = $calendario[$j];
	$qnt++;
	$editar=permiteEditarCalendario($linha['calendario_acesso'],$linha['calendario_id']);
	$ver=permiteAcessarCalendario($linha['calendario_acesso'],$linha['calendario_id']);
	
	echo '<tr>';
	if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar '.'Agenda Coletiva'.'', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a agenda coletiva.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a=calendario_editar&calendario_id='.$linha['calendario_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="16" align="right" style="background-color:#'.$linha['calendario_cor'].'"><font color="'.melhorCor($linha['calendario_cor']).'">&nbsp;&nbsp;</font></td>';
	
	if ($ver) echo '<td>'.link_calendario($linha['calendario_id']).'</td>';
	else echo '<td nowrap="nowrap">'.dica(''.'Agenda Coletiva'.'', 'Não tem permissão para ver os detalhes desta agenda coletiva.').'<i>'.$linha['calendario_nome'].'</i>'.dicaF().'</td>';
	
	if ($ver) echo '<td>'.($linha['calendario_descricao'] ? $linha['calendario_descricao']: '&nbsp;').'</td>';
	else echo '<td nowrap="nowrap">'.dica(''.'Agenda Coletiva'.'', 'Não tem permissão para ver a descrição desta agenda coletiva.').'<i>Descrição</i>'.dicaF().'</td>';
	
	echo '<td nowrap="nowrap">'.link_usuario($linha['calendario_usuario'],'','','esquerda').'</td>';
	echo '</tr>';

	}
if (!count($calendario)) echo '<tr><td colspan=20><p>Nenhuma agenda coletiva encontrada.</p></td></tr>';
echo '</table>';

?>