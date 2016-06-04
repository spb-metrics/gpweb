<?php
global $estilo_interface, $dialogo, $tab, $projeto_id;
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;
$pagina = getParam($_REQUEST, 'pagina', 1);

$xpg_tamanhoPagina = ($dialogo ? 90000 : 30);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'projeto_recebimento_data_prevista');
$ordem = getParam($_REQUEST, 'ordem', '0');


$obj = new CProjeto();
$obj->load($projeto_id);
$sql = new BDConsulta();

$editar=permiteEditar($obj->projeto_acesso,$obj->projeto_id);


$sql->adTabela('projeto_recebimento');
$sql->adCampo('projeto_recebimento.*');
if ($tab==1) $sql->adOnde('projeto_recebimento_provisorio=1');	
if ($tab==2) $sql->adOnde('projeto_recebimento_definitivo=1');	
$sql->adOnde('projeto_recebimento_projeto='.$projeto_id);
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->adGrupo('projeto_recebimento_id');
$recebimentos=$sql->Lista();
$sql->limpar();



$xpg_totalregistros = ($recebimentos ? count($recebimentos) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'recebimento', 'recebimentos','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação da recebimento.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_numero&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_numero' ? imagem('icones/'.$seta[$ordem]) : '').dica('Número do recebimento', 'Neste campo fica a número do recebimento.').'Número'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_data_prevista&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_data_prevista' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data da Prevista', 'Neste campo fica a data prevista para o recebimento.').'Previsto'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_data_entrega&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_data_entrega' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data da recebimento', 'Neste campo fica a data do recebimento.').'Recebido'.dicaF().'</a></th>';


echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável pela Entrega', 'O '.$config['usuario'].' responsável pela entrega.').'Entrega'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_recebimento_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_recebimento_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável pelo Recebimento', 'O contato que recebeu o produto/serviço.').'Recebimento'.dicaF().'</a></th>';

echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $recebimentos[$i];
	$qnt++;
	echo '<tr>';
	if (!$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a recebimento.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=recebimento_editar&projeto_id='.$projeto_id.'&projeto_recebimento_id='.$linha['projeto_recebimento_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_recebimento_cor'].'"><font color="'.melhorCor($linha['projeto_recebimento_cor']).'">&nbsp;&nbsp;</font></td>';
	echo '<td>'.link_recebimento($linha['projeto_recebimento_id']).'</td>';
	echo '<td>'.($linha['projeto_recebimento_data_prevista'] ? retorna_data($linha['projeto_recebimento_data_prevista'], false): '&nbsp;').'</td>';
	echo '<td>'.($linha['projeto_recebimento_data_entrega'] ? retorna_data($linha['projeto_recebimento_data_entrega'], false): '&nbsp;').'</td>';
	echo '<td nowrap="nowrap">'.link_usuario($linha['projeto_recebimento_responsavel'],'','','esquerda').'</td>';
	echo '<td nowrap="nowrap">'.link_contato($linha['projeto_recebimento_cliente'],'','','esquerda').'</td>';
	echo '</tr>';

	}
if (!count($recebimentos)) echo '<tr><td colspan=20><p>Nenhuma recebimento encontrada.</p></td></tr>';
echo '</table>';
?>