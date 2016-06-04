<?php
global $estilo_interface, $dialogo, $tab, $projeto_id, $podeEditar, $projeto_id;
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;
$pagina = getParam($_REQUEST, 'pagina', 1);

$xpg_tamanhoPagina = ($dialogo ? 90000 : 30);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'ata_data_inicio');
$ordem = getParam($_REQUEST, 'ordem', '0');


$obj = new CProjeto();
$obj->load($projeto_id);
$sql = new BDConsulta();

$editar=permiteEditar($obj->projeto_acesso,$obj->projeto_id);


$sql->adTabela('ata');
$sql->adCampo('ata.*');
$sql->adOnde('ata_projeto='.(int)$projeto_id);
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->adGrupo('ata_id');
$recebimentos=$sql->Lista();
$sql->limpar();



$xpg_totalregistros = ($recebimentos ? count($recebimentos) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'ata de reunião', 'solicitações de mudanças','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=ata_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='ata_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=ata_numero&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='ata_numero' ? imagem('icones/'.$seta[$ordem]) : '').dica('Número da ata de reunião', 'Neste campo fica a número da ata de reunião.').'Número'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=ata_data_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='ata_data_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data', 'Neste campo fica a data da ata de reunião.').'Data'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=ata_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='ata_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável pela Entrega', 'O '.$config['usuario'].' responsável pela ata de reunião.').'Responsável'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=ata_relato&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='ata_relato' ? imagem('icones/'.$seta[$ordem]) : '').dica('Relato', 'O relato da ata de reunião.').'Relato'.dicaF().'</a></th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $recebimentos[$i];
	$qnt++;
	echo '<tr>';
	if (!$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar && $podeEditar ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a recebimento.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=ata_editar&projeto_id='.$projeto_id.'&ata_id='.$linha['ata_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['ata_cor'].'"><font color="'.melhorCor($linha['ata_cor']).'">&nbsp;&nbsp;</font></td>';
	echo '<td>'.link_ata($linha['ata_id']).'</td>';
	echo '<td>'.($linha['ata_data_inicio'] ? retorna_data($linha['ata_data_inicio'], false): '&nbsp;').'</td>';
	echo '<td nowrap="nowrap">'.link_usuario($linha['ata_responsavel'],'','','esquerda').'</td>';
	echo '<td>'.($linha['ata_relato'] ? $linha['ata_relato'] : '&nbsp;').'</td>';
	echo '</tr>';

	}
if (!count($recebimentos)) echo '<tr><td colspan=20><p>Nenhuma ata de reunião encontrada.</p></td></tr>';
echo '</table>';
?>