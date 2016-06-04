<?php
global $estilo_interface, $dialogo, $tab, $cia_id, $dept_id, $lista_depts, $lista_cias, $pesquisar_texto;
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_demanda']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', ($tab!=0 ? 'projeto_viabilidade_nome' : 'demanda_nome'));
$ordem = getParam($_REQUEST, 'ordem', '0');

$xtotalregistros=viabilidades_quantidade($tab, $cia_id, $lista_cias, $pesquisar_texto);

if ($tab!=0){
	$sql->adTabela('projeto_viabilidade');
	$sql->esqUnir('demandas','demandas','demandas.demanda_id=projeto_viabilidade.projeto_viabilidade_demanda');
	if (trim($pesquisar_texto)) $sql->adOnde('projeto_viabilidade_nome LIKE \'%'.$pesquisar_texto.'%\' OR projeto_viabilidade_observacao LIKE \'%'.$pesquisar_texto.'%\'');
	}
else {
	$sql->adTabela('demandas');
	if (trim($pesquisar_texto)) $sql->adOnde('demanda_nome LIKE \'%'.$pesquisar_texto.'%\' OR demanda_observacao LIKE \'%'.$pesquisar_texto.'%\'');
	}
$sql->esqUnir('projeto_abertura','projeto_abertura','demandas.demanda_id=projeto_abertura_demanda');
$sql->adCampo('projeto_abertura_recusa');

if ($tab!=0) $sql->adCampo('projeto_viabilidade.projeto_viabilidade_id, projeto_viabilidade_nome, projeto_viabilidade_responsavel, projeto_viabilidade_acesso, projeto_viabilidade_cor, projeto_viabilidade_necessidade');
if ($tab==0) $sql->adCampo('demandas.demanda_id, demanda_nome, demanda_usuario, demanda_acesso, demanda_cor, demanda_identificacao');


if ($tab!=0){
	if ($dept_id && !$lista_depts) {
		$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
		$sql->adOnde('projeto_viabilidade_dept='.(int)$dept_id.' OR projeto_viabilidade_dept_dept='.(int)$dept_id);
		}
	elseif ($lista_depts) {
		$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
		$sql->adOnde('projeto_viabilidade_dept IN ('.$lista_depts.') OR projeto_viabilidade_dept_dept IN ('.$lista_depts.')');
		}	
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('projeto_viabilidade_cia', 'projeto_viabilidade_cia', 'projeto_viabilidade.projeto_viabilidade_id=projeto_viabilidade_cia_projeto_viabilidade');
		$sql->adOnde('projeto_viabilidade_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_viabilidade_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}
	elseif ($cia_id && !$lista_cias) $sql->adOnde('projeto_viabilidade_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('projeto_viabilidade_cia IN ('.$lista_cias.')');
	}
else {
	if ($dept_id && !$lista_depts) {
		$sql->esqUnir('demanda_depts','demanda_depts', 'demanda_depts.demanda_id=demandas.demanda_id');
		$sql->adOnde('demanda_dept='.(int)$dept_id.' OR demanda_depts.dept_id='.(int)$dept_id);
		}
	elseif ($lista_depts) {
		$sql->esqUnir('demanda_depts','demanda_depts', 'demanda_depts.demanda_id=demandas.demanda_id');
		$sql->adOnde('demanda_dept IN ('.$lista_depts.') OR demanda_depts.dept_id IN ('.$lista_depts.')');
		}	
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('demanda_cia', 'demanda_cia', 'demandas.demanda_id=demanda_cia_demanda');
		$sql->adOnde('demanda_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR demanda_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}
	elseif ($cia_id && !$lista_cias) $sql->adOnde('demanda_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('demanda_cia IN ('.$lista_cias.')');
	}
	


if ($tab==0) $sql->adOnde('demanda_viabilidade IS NULL');	
if ($tab==1) $sql->adOnde('projeto_viabilidade_viavel=1');	
if ($tab==2) $sql->adOnde('projeto_viabilidade_viavel=-1');	
if ($tab==3) $sql->adOnde('projeto_abertura_aprovado=0');	
if ($tab==4) $sql->adOnde('projeto_abertura_aprovado=-1');	
if ($tab!=5)$sql->adOnde('demanda_projeto IS NULL');	
else $sql->adOnde('demanda_projeto IS NOT NULL');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$projeto_viabilidade=$sql->Lista();
$sql->limpar();


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Estudo de Viabilidade', 'Estudos de Viabilidade','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
if ($tab!=0) {
	echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação do estudo de viabilidade.').'Cor'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identificação do possível projeto.').'Nome'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_necessidade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_necessidade' ? imagem('icones/'.$seta[$ordem]) : '').dica('Necessidade', 'Descrição do problema que se deseja resolver por meio do projeto.').'Necessidade'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pelo estudo de viabilidade.').'Responsável'.dicaF().'</a></th>';
	if ($tab==4) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_recusa&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_recusa' ? imagem('icones/'.$seta[$ordem]) : '').dica('Justificativa', 'A justificativa para o termo de abertura não ter sido aprovado.').'Justificativa'.dicaF().'</a></th>';	
	}
else{
	echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=demanda_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='demanda_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação da demanda.').'Cor'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=demanda_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='demanda_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da Demanda', 'Neste campo fica um nome para identificação da demanda.').'Nome'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=demanda_identificacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='demanda_identificacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Identificação', 'Neste campo fica a identificação da demanda.').'Identificação'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=demanda_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='demanda_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Demandante', 'O '.$config['usuario'].' que inseriu a demanda.').'Demandante'.dicaF().'</a></th>';
	}	
echo '</tr>';


foreach ($projeto_viabilidade as $linha) {
	echo '<tr>';
	if ($tab!=0) {
		
		if (permiteAcessarViabilidade($linha['projeto_viabilidade_acesso'],$linha['projeto_viabilidade_id'])){
			$editar=permiteEditarViabilidade($linha['projeto_viabilidade_acesso'],$linha['projeto_viabilidade_id']);
			if (!$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a viabilidade.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=viabilidade_editar&projeto_viabilidade_id='.$linha['projeto_viabilidade_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
			echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_viabilidade_cor'].'"><font color="'.melhorCor($linha['projeto_viabilidade_cor']).'">&nbsp;&nbsp;</font></td>';
			echo '<td>'.link_viabilidade($linha['projeto_viabilidade_id']).'</td>';
			echo '<td>'.($linha['projeto_viabilidade_necessidade'] ? $linha['projeto_viabilidade_necessidade']: '&nbsp;').'</td>';
			echo '<td>'.link_usuario($linha['projeto_viabilidade_responsavel'],'','','esquerda').'</td>';
			if ($tab==4) echo '<td>'.$linha['projeto_abertura_recusa'].'</td>';
			echo '</tr>';
			}
		}
	else{
		if (permiteAcessarDemanda($linha['demanda_acesso'],$linha['demanda_id'])){
			$editar=permiteEditarDemanda($linha['demanda_acesso'],$linha['demanda_id']);
			if (!$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a viabilidade.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=demanda_editar&demanda_id='.$linha['demanda_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
			echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['demanda_cor'].'"><font color="'.melhorCor($linha['demanda_cor']).'">&nbsp;&nbsp;</font></td>';
			echo '<td>'.link_demanda($linha['demanda_id']).'</td>';
			echo '<td>'.($linha['demanda_identificacao'] ? $linha['demanda_identificacao']: '&nbsp;').'</td>';
			echo '<td>'.link_usuario($linha['demanda_usuario'],'','','esquerda').'</td>';
			echo '</tr>';
			}
		}
	}
if (!count($projeto_viabilidade) && $tab) echo '<tr><td colspan=20><p>Nenhuma estudo de viabilidade encontrado.</p></td></tr>';
else if (!count($projeto_viabilidade)) echo '<tr><td colspan=20><p>Nenhuma demanda, não analisada, encontrada.</p></td></tr>';
echo '</table>';
?>