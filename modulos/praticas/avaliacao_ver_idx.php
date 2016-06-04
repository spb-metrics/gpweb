<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $sql, $perms, $Aplic, $cia_id, $lista_cias, $lista_depts, $dept_id, $tab, $tabAtualId, $tabNomeAtual, $estah_tab, $st_praticas_arr, $ordem, $ordenar, $perspectiva_tab, $favorito_id, $dialogo;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);



$pagina = getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_objetivos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$ordenar = getParam($_REQUEST, 'ordenar', 'avaliacao_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('avaliacao');
$sql->adCampo('count(DISTINCT avaliacao_id) as soma');

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('avaliacao_dept','avaliacao_dept', 'avaliacao_dept_avaliacao=avaliacao.avaliacao_id');
	$sql->adOnde('avaliacao_dept='.(int)$dept_id.' OR avaliacao_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('avaliacao_dept','avaliacao_dept', 'avaliacao_dept_avaliacao=avaliacao.avaliacao_id');
	$sql->adOnde('avaliacao_dept IN ('.$lista_depts.') OR avaliacao_dept_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('avaliacao_cia', 'avaliacao_cia', 'avaliacao.avaliacao_id=avaliacao_cia_avaliacao');
	$sql->adOnde('avaliacao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR avaliacao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('avaliacao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('avaliacao_cia IN ('.$lista_cias.')');

$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('avaliacao');
$sql->adCampo('DISTINCT avaliacao.avaliacao_id, avaliacao_nome, avaliacao_responsavel, avaliacao_acesso, avaliacao_cor, avaliacao_descricao, formatar_data(avaliacao_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(avaliacao_fim, \'%d/%m/%Y  %H:%i\') AS fim');

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('avaliacao_dept','avaliacao_dept', 'avaliacao_dept_avaliacao=avaliacao.avaliacao_id');
	$sql->adOnde('avaliacao_dept='.(int)$dept_id.' OR avaliacao_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('avaliacao_dept','avaliacao_dept', 'avaliacao_dept_avaliacao=avaliacao.avaliacao_id');
	$sql->adOnde('avaliacao_dept IN ('.$lista_depts.') OR avaliacao_dept_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('avaliacao_cia', 'avaliacao_cia', 'avaliacao.avaliacao_id=avaliacao_cia_avaliacao');
	$sql->adOnde('avaliacao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR avaliacao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('avaliacao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('avaliacao_cia IN ('.$lista_cias.')');

$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$avaliacoes=$sql->Lista();
$sql->limpar();


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;

if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Avaliação', 'Avaliaçãos','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor da Avaliação', 'Neste campo fica a cor de identificação da avaliação.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da Avaliação', 'Neste campo fica um nome para identificação da avaliação.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição da Avaliação', 'Neste campo fica a descrição da avaliação.').'Descrição'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pela avaliação.').'Responsável'.dicaF().'</a></th>';


echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Início', 'A data de ínicio da avaliação.').'Início'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_fim&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_fim' ? imagem('icones/'.$seta[$ordem]) : '').dica('Término', 'A data de término da avaliação.').'Término'.dicaF().'</a></th>';


echo '</tr>';

$qnt=0;
foreach ($avaliacoes as $linha) {
	if (permiteAcessarAvaliacao($linha['avaliacao_acesso'],$linha['avaliacao_id'])){
		$qnt++;
		$editar=permiteEditarAvaliacao($linha['avaliacao_acesso'],$linha['avaliacao_id']);
		echo '<tr>';
		if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar Avaliação', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a avaliação.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=avaliacao_editar&avaliacao_id='.$linha['avaliacao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['avaliacao_cor'].'"><font color="'.melhorCor($linha['avaliacao_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_avaliacao($linha['avaliacao_id']).'</td>';
		echo '<td>'.($linha['avaliacao_descricao'] ? $linha['avaliacao_descricao'] : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['avaliacao_responsavel'],'','','esquerda').'</td>';
		echo '<td width=110>'.$linha['inicio'].'</td>';
		echo '<td width=110>'.$linha['fim'].'</td>';
		echo '</tr>';
		}
	}
if (!count($avaliacoes)) echo '<tr><td colspan=20><p>Nenhuma avaliação encontrada.</p></td></tr>';
elseif(count($avaliacoes) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer das avaliações.</p></td></tr>';
echo '</table>';

?>