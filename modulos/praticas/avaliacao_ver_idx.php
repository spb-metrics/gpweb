<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

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

if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Avalia��o', 'Avalia��os','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor da Avalia��o', 'Neste campo fica a cor de identifica��o da avalia��o.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da Avalia��o', 'Neste campo fica um nome para identifica��o da avalia��o.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descri��o da Avalia��o', 'Neste campo fica a descri��o da avalia��o.').'Descri��o'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Respons�vel', 'O '.$config['usuario'].' respons�vel pela avalia��o.').'Respons�vel'.dicaF().'</a></th>';


echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('In�cio', 'A data de �nicio da avalia��o.').'In�cio'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_fim&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_fim' ? imagem('icones/'.$seta[$ordem]) : '').dica('T�rmino', 'A data de t�rmino da avalia��o.').'T�rmino'.dicaF().'</a></th>';


echo '</tr>';

$qnt=0;
foreach ($avaliacoes as $linha) {
	if (permiteAcessarAvaliacao($linha['avaliacao_acesso'],$linha['avaliacao_id'])){
		$qnt++;
		$editar=permiteEditarAvaliacao($linha['avaliacao_acesso'],$linha['avaliacao_id']);
		echo '<tr>';
		if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar Avalia��o', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar a avalia��o.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=avaliacao_editar&avaliacao_id='.$linha['avaliacao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['avaliacao_cor'].'"><font color="'.melhorCor($linha['avaliacao_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_avaliacao($linha['avaliacao_id']).'</td>';
		echo '<td>'.($linha['avaliacao_descricao'] ? $linha['avaliacao_descricao'] : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['avaliacao_responsavel'],'','','esquerda').'</td>';
		echo '<td width=110>'.$linha['inicio'].'</td>';
		echo '<td width=110>'.$linha['fim'].'</td>';
		echo '</tr>';
		}
	}
if (!count($avaliacoes)) echo '<tr><td colspan=20><p>Nenhuma avalia��o encontrada.</p></td></tr>';
elseif(count($avaliacoes) && !$qnt) echo '<tr><td colspan="20"><p>N�o teve permiss�o de visualizar qualquer das avalia��es.</p></td></tr>';
echo '</table>';

?>