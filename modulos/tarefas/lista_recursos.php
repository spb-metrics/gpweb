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

$ordenar = getParam($_REQUEST, 'ordenar', 'tarefa_inicio');
$ordem = getParam($_REQUEST, 'ordem', '0');
$tarefa_id = getParam($_REQUEST, 'tarefa_id', '0');
$projeto_id = getParam($_REQUEST, 'projeto_id', '0');
$pagina = getParam($_REQUEST, 'pagina', 1);

$nd= getSisValorND();
$unidade= getSisValor('TipoUnidade');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$sql = new BDConsulta();
$sql->adTabela('recurso_tarefas');
$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id=recurso_tarefas.tarefa_id');
$sql->esqUnir('recursos','recursos','recursos.recurso_id=recurso_tarefas.recurso_id');
$sql->adCampo('recurso_tarefas.*, tarefa_inicio, tarefa_fim, recurso_nome, recursos.recurso_quantidade AS total, recurso_tipo, recurso_unidade');
$sql->adCampo('(tarefa_duracao * recurso_hora_custo) AS estimado');
if ($tarefa_id) $sql->adOnde('tarefas.tarefa_id = '.(int)$tarefa_id);
else $sql->adOnde('tarefa_projeto = '.(int)$projeto_id); 
$sql->adOrdem($ordenar.($ordem ? ' ASC' :  ' DESC'));
$recursos = $sql->Lista();	
$sql->limpar();

echo estiloTopoCaixa();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type=hidden name="pagina" id="pagina" value="'.$pagina.'">';
echo '</form>';

$pagina = getParam($_REQUEST, 'pagina', 1);
$xpg_tamanhoPagina = $config['qnt_recursos'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 
$xpg_totalregistros = ($recursos ? count($recursos) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav2($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'recurso', 'recursos','class="std"');

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($projeto_id) && $projeto_id ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) && $tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&dialogo=1&ordenar=recurso_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='recurso_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Nome'.'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($projeto_id) && $projeto_id ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) && $tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&dialogo=1&ordenar=recurso_quantidade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='recurso_quantidade' ? imagem('icones/'.$seta[$ordem]) : '').'Alocado'.'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($projeto_id) && $projeto_id ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) && $tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&dialogo=1&ordenar=total&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='total' ? imagem('icones/'.$seta[$ordem]) : '').'Total'.'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($projeto_id) && $projeto_id ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) && $tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&dialogo=1&ordenar=tarefa_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_inicio' ? imagem('icones/'.$seta[$ordem]) : '').'De'.'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($projeto_id) && $projeto_id ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) && $tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&dialogo=1&ordenar=tarefa_fim&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_fim' ? imagem('icones/'.$seta[$ordem]) : '').'Até'.'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($projeto_id) && $projeto_id ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) && $tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&dialogo=1&ordenar=estimado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='estimado' ? imagem('icones/'.$seta[$ordem]) : '').'Valor Estimado'.'</a></th>';
echo '</tr>';

for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $recursos[$i];
	echo '<tr>';
	echo '<td>'.$linha['recurso_nome'].'</td>';
	echo '<td align=right>'.($linha['recurso_tipo']==5 ? $config["simbolo_moeda"].' '.number_format($linha['recurso_quantidade'], 2, ',', '.') : number_format($linha['recurso_quantidade'], 2, ',', '.').(isset($unidade[$linha['recurso_unidade']])? ' '.$unidade[$linha['recurso_unidade']] :'')).'</td>';
	echo '<td align=right>'.($linha['recurso_tipo']==5 ? $config["simbolo_moeda"].' '.number_format($linha['total'], 2, ',', '.') : number_format($linha['total'], 2, ',', '.').(isset($unidade[$linha['recurso_unidade']])? ' '.$unidade[$linha['recurso_unidade']] :'')).'</td>';
	echo '<td align=center width=110 nowrap="nowrap">'.retorna_data($linha['tarefa_inicio']).'</td>';
	echo '<td align=center width=110 nowrap="nowrap">'.retorna_data($linha['tarefa_fim']).'</td>';
	echo '<td align=right>'.($linha['recurso_tipo']==5 ? 'N/A' : $config["simbolo_moeda"].' '.number_format($linha['estimado'], 2, ',', '.')).'</td>';
	
	echo '</tr>';
	}
if (!count($recursos)) echo '<tr><td colspan="20"><p>Nenhum recurso encontrado.</p></td></tr>';
echo '</table>';
echo estiloFundoCaixa();
?>