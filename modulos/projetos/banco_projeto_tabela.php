<?php
global $estilo_interface, $dialogo, $cia_id, $lista_cias, $dept_id, $lista_depts, $tab, $pesquisar_texto;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;
$pagina = getParam($_REQUEST, 'pagina', 1);

$xpg_tamanhoPagina = ($dialogo ? 90000 : $config['qnt_banco_projeto']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', ($tab ? 'projeto_abertura_nome' : 'projeto_viabilidade_nome'));
$ordem = getParam($_REQUEST, 'ordem', '0');




if ($tab==0 || $tab==2){
	$sql->adTabela('projeto_viabilidade');
	$sql->esqUnir('demandas', 'demandas', 'demandas.demanda_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
	$sql->adCampo('projeto_viabilidade_id,  projeto_viabilidade_demanda, projeto_viabilidade_responsavel, projeto_viabilidade_acesso, projeto_viabilidade_cor, projeto_viabilidade_necessidade');	
	
	if ($cia_id && !$lista_cias) $sql->adOnde('projeto_viabilidade_cia='.(int)$cia_id);
	if ($lista_cias) $sql->adOnde('projeto_viabilidade_cia IN ('.$lista_cias.')');	
	if ($dept_id && !$lista_depts) {
		$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
		$sql->adOnde('projeto_viabilidade_dept='.(int)$dept_id.' OR projeto_viabilidade_dept.projeto_viabilidade_dept_dept='.(int)$dept_id);
		}	
	elseif ($lista_depts){
		$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
		$sql->adOnde('projeto_viabilidade_dept IN ('.$lista_depts.') OR  projeto_viabilidade_dept.projeto_viabilidade_dept_dept IN ('.$lista_depts.')');
		}		
	
	if ($pesquisar_texto) $sql->adOnde('projeto_viabilidade_nome LIKE \'%'.$pesquisar_texto.'%\' OR projeto_viabilidade_codigo LIKE \'%'.$pesquisar_texto.'%\' OR projeto_viabilidade_observacao LIKE \'%'.$pesquisar_texto.'%\'');
	
	if ($tab==0) $sql->adOnde('projeto_viabilidade_ativo=1');
	else $sql->adOnde('projeto_viabilidade_ativo!=1 OR projeto_viabilidade_ativo IS NULL');	
	
	$sql->adOnde('projeto_viabilidade_projeto IS NULL');	
	$sql->adOnde('projeto_viabilidade_viavel=1');	
	$sql->adOnde('demanda_termo_abertura IS NULL');	
	$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
	$sql->adGrupo('projeto_viabilidade.projeto_viabilidade_id');
	}
else{
	$sql->adTabela('projeto_abertura');
	$sql->adCampo('projeto_abertura_id,  projeto_abertura_demanda, projeto_abertura_responsavel, projeto_abertura_acesso, projeto_abertura_cor, projeto_abertura_objetivo');
	
	if ($cia_id && !$lista_cias) $sql->adOnde('projeto_abertura_cia='.(int)$cia_id);
	if ($lista_cias) $sql->adOnde('projeto_abertura_cia IN ('.$lista_cias.')');	
	if ($dept_id && !$lista_depts) {
		$sql->esqUnir('projeto_abertura_dept','projeto_abertura_dept', 'projeto_abertura_dept_projeto_abertura=projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_dept='.(int)$dept_id.' OR projeto_abertura_dept.projeto_abertura_dept_dept='.(int)$dept_id);
		}	
	elseif ($lista_depts){
		$sql->esqUnir('projeto_abertura_dept','projeto_abertura_dept', 'projeto_abertura_dept_projeto_abertura=projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_dept IN ('.$lista_depts.') OR  projeto_abertura_dept.projeto_abertura_dept_dept IN ('.$lista_depts.')');
		}		
	if ($pesquisar_texto) $sql->adOnde('projeto_abertura_nome LIKE \'%'.$pesquisar_texto.'%\' OR projeto_abertura_codigo LIKE \'%'.$pesquisar_texto.'%\' OR projeto_abertura_observacao LIKE \'%'.$pesquisar_texto.'%\'');
	if ($tab==1) $sql->adOnde('projeto_abertura_ativo=1');
	else $sql->adOnde('projeto_abertura_ativo!=1 OR projeto_abertura_ativo IS NULL');	
	
		
	$sql->adOnde('projeto_abertura_projeto IS NULL');	
	$sql->adOnde('projeto_abertura_aprovado!=1');	
	
	
	$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
	$sql->adGrupo('projeto_abertura_id');
	}	

$lista=$sql->Lista();
$sql->limpar();



$xpg_totalregistros = ($lista ? count($lista) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'Possível '.$config['projeto'], 'Possíveis '.$config['projetos'],'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
if (!$tab){
	echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação.').'Cor'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identificação do possível projeto.').'Nome'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_necessidade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_necessidade' ? imagem('icones/'.$seta[$ordem]) : '').dica('Necessidade', 'Descrição do problema que se deseja resolver por meio do projeto.').'Necessidade'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pelo estudo de viabilidade.').'Responsável'.dicaF().'</a></th>';
	}
else{
	echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação.').'Cor'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identificação do possível projeto.').'Nome'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_objetivo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_objetivo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Objetivo', 'Descrição do problema que se deseja resolver por meio do projeto.').'Objetivo'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pelo estudo de viabilidade.').'Responsável'.dicaF().'</a></th>';
	}	
	
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $lista[$i];
	$qnt++;

	
	echo '<tr>';
if (!$tab){
	if (permiteAcessarViabilidade($linha['projeto_viabilidade_acesso'],$linha['projeto_viabilidade_id'])){
		if (!$dialogo) echo '<td nowrap="nowrap" width="20">'.(($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') || $Aplic->usuario_super_admin) ? dica('Criar Termo de Abertura', 'Clique neste ícone '.imagem('icones/novo_documento.gif').' para criar o termo de abertura d'.$config['genero_projeto'].' '.$config['projetos'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=termo_abertura_editar&projeto_viabilidade_id='.$linha['projeto_viabilidade_id'].'\');">'.imagem('icones/novo_documento.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_viabilidade_cor'].'"><font color="'.melhorCor($linha['projeto_viabilidade_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_viabilidade($linha['projeto_viabilidade_id']).'</td>';
		echo '<td>'.($linha['projeto_viabilidade_necessidade'] ? $linha['projeto_viabilidade_necessidade']: '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['projeto_viabilidade_responsavel'],'','','esquerda').'</td>';
		}
	}
else{
	if (permiteAcessarTermoAbertura($linha['projeto_abertura_acesso'],$linha['projeto_abertura_id'])){
		if (!$dialogo) echo '<td nowrap="nowrap" width="20">'.(($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') || $Aplic->usuario_super_admin) ? dica('Editar Minuta do Termo de Abertura', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a minuta do termo de abertura d'.$config['genero_projeto'].' '.$config['projetos'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=termo_abertura_editar&projeto_abertura_id='.$linha['projeto_abertura_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_abertura_cor'].'"><font color="'.melhorCor($linha['projeto_abertura_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_termo_abertura($linha['projeto_abertura_id']).'</td>';
		echo '<td>'.($linha['projeto_abertura_objetivo'] ? $linha['projeto_abertura_objetivo']: '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['projeto_abertura_responsavel'],'','','esquerda').'</td>';
		}
	}	
	
	echo '</tr>';

	}
if (!count($lista)) echo '<tr><td colspan=20><p>Nenhum possível '.$config['projetos'].($tab ? ' com minuta do termo de abertura' : '').'</p></td></tr>';
echo '</table>';


?>