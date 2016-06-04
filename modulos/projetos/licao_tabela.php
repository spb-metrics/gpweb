<?php
global $estilo_interface, $dialogo, $tab, $cia_id, $dept_id, $lista_cias, $pesquisar_texto, $licaostatus, $licaocategoria, $licaotipo, $usuario_id, $projeto_id, $lista_depts, $data_inicio, $data_fim, $filtro_extra_lista, $usar_periodo;
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');


$licao_categoria=getSisValor('LicaoCategoria');

$sql = new BDConsulta;
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_licoes']);
$xmin = $xtamanhoPagina * ($pagina - 1);  

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'licao_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');


$sql->adTabela('licao');
$sql->adCampo('count(DISTINCT licao.licao_id)');
if ($pesquisar_texto) $sql->adOnde('licao_nome LIKE \'%'.$pesquisar_texto.'%\' OR licao_ocorrencia LIKE \'%'.$pesquisar_texto.'%\' OR licao_consequencia LIKE \'%'.$pesquisar_texto.'%\' OR licao_acao_tomada LIKE \'%'.$pesquisar_texto.'%\' OR licao_aprendizado LIKE \'%'.$pesquisar_texto.'%\'');
if ($usar_periodo) {
	$sql->adOnde('licao_data_final >=\''.$data_inicio->format("%Y-%m-%d").'\'');
	$sql->adOnde('licao_data_final <=\''.$data_fim->format("%Y-%m-%d").'\'');
	}
if($projeto_id) $sql->adOnde('licao_projeto='.(int)$projeto_id);
if ($dept_id && !$lista_depts) {
	$sql->esqUnir('licao_dept','licao_dept', 'licao_dept.licao_dept_licao=licao.licao_id');
	$sql->adOnde('licao_dept_dept='.(int)$dept_id.' OR licao_dept='.(int)$dept_id);
	}	
elseif ($lista_depts) {
	$sql->esqUnir('licao_dept','licao_dept', 'licao_dept.licao_dept_licao=licao.licao_id');
	$sql->adOnde('licao_dept_dept IN ('.$lista_depts.') OR licao_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('licao_cia', 'licao_cia', 'licao.licao_id=licao_cia_licao');
	$sql->adOnde('licao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR licao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('licao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('licao_cia IN ('.$lista_cias.')');	
if($usuario_id) {
	$sql->esqUnir('licao_usuarios', 'licao_usuarios', 'licao.licao_id=licao_usuarios.licao_id');
	$sql->adOnde('licao_responsavel='.(int)$usuario_id.' OR licao_usuarios.usuario_id='.(int)$usuario_id);	
	}
if ($licaostatus && $licaostatus!=-1)$sql->adOnde('licao_status IN ('.$licaostatus.')');
if ($licaocategoria && $licaocategoria!=-1)$sql->adOnde('licao_categoria IN ('.$licaocategoria.')');
if ($licaotipo && $licaotipo!=-1)$sql->adOnde('licao_tipo IN ('.$licaotipo.')');
if ($tab==0) $sql->adOnde('licao_ativa=1');	
elseif ($tab==1) $sql->adOnde('licao_ativa=0');	
$sql->adOnde('licao_ativa=1');
if($filtro_extra_lista !== false){
    if($filtro_extra_lista){
        $sql->adOnde('licao.licao_id IN ('.$filtro_extra_lista.')');
        }
    }	
$xtotalregistros = $sql->Resultado();
$sql->limpar();




$sql->adTabela('licao');
$sql->adCampo('DISTINCT licao.licao_id, licao_nome, licao_projeto, licao_responsavel, licao_acesso, licao_cor, licao_categoria, licao_tipo');
if ($pesquisar_texto) $sql->adOnde('licao_nome LIKE \'%'.$pesquisar_texto.'%\' OR licao_ocorrencia LIKE \'%'.$pesquisar_texto.'%\' OR licao_consequencia LIKE \'%'.$pesquisar_texto.'%\' OR licao_acao_tomada LIKE \'%'.$pesquisar_texto.'%\' OR licao_aprendizado LIKE \'%'.$pesquisar_texto.'%\'');
if ($usar_periodo) {
	$sql->adOnde('licao_data_final >=\''.$data_inicio->format("%Y-%m-%d").'\'');
	$sql->adOnde('licao_data_final <=\''.$data_fim->format("%Y-%m-%d").'\'');
	}
if($projeto_id) $sql->adOnde('licao_projeto='.(int)$projeto_id);
if ($dept_id && !$lista_depts) {
	$sql->esqUnir('licao_dept','licao_dept', 'licao_dept.licao_dept_licao=licao.licao_id');
	$sql->adOnde('licao_dept_dept='.(int)$dept_id.' OR licao_dept='.(int)$dept_id);
	}	
elseif ($lista_depts) {
	$sql->esqUnir('licao_dept','licao_dept', 'licao_dept.licao_dept_licao=licao.licao_id');
	$sql->adOnde('licao_dept_dept IN ('.$lista_depts.') OR licao_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('licao_cia', 'licao_cia', 'licao.licao_id=licao_cia_licao');
	$sql->adOnde('licao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR licao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('licao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('licao_cia IN ('.$lista_cias.')');	
if($usuario_id) {
	$sql->esqUnir('licao_usuarios', 'licao_usuarios', 'licao.licao_id=licao_usuarios.licao_id');
	$sql->adOnde('licao_responsavel='.(int)$usuario_id.' OR licao_usuarios.usuario_id='.(int)$usuario_id);	
	}
if ($licaostatus && $licaostatus!=-1)$sql->adOnde('licao_status IN ('.$licaostatus.')');
if ($licaocategoria && $licaocategoria!=-1)$sql->adOnde('licao_categoria IN ('.$licaocategoria.')');
if ($licaotipo && $licaotipo!=-1)$sql->adOnde('licao_tipo IN ('.$licaotipo.')');
if ($tab==0) $sql->adOnde('licao_ativa=1');	
elseif ($tab==1) $sql->adOnde('licao_ativa=0');	
$sql->adOnde('licao_ativa=1');
if($filtro_extra_lista !== false){
    if($filtro_extra_lista){
        $sql->adOnde('licao.licao_id IN ('.$filtro_extra_lista.')');
        }
    }	
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$licao=$sql->Lista();
$sql->limpar();





$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['licao']), ucfirst($config['licoes']),'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação da lição aprendida.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da Lição Aprendida', 'Neste campo fica um nome para identificação da lição aprendida.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_projeto&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_projeto' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['projeto']), 'Neste campo fica o nome do '.$config['projeto'].' da lição apendida.').ucfirst($config['projeto']).dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_tipo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_tipo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Tipo', 'Neste campo fica o tipo de lição aprendida.').'Tipo'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_categoria&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_categoria' ? imagem('icones/'.$seta[$ordem]) : '').dica('Categoria', 'Neste campo fica a categoria de lição aprendida.').'Categoria'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=licao_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' que inseriu a lição aprendida.').'Responsável'.dicaF().'</a></th>';
echo '</tr>';

$qnt=0;


foreach ($licao as $linha) {
	$qnt++;
	if (permiteAcessarLicao($linha['licao_acesso'],$linha['licao_id'])){
		$editar=permiteEditarLicao($linha['licao_acesso'],$linha['licao_id']);
		echo '<tr>';
		if (!$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a lição aprendida.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=licao_editar&licao_id='.$linha['licao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['licao_cor'].'"><font color="'.melhorCor($linha['licao_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_licao($linha['licao_id']).'</td>';
		echo '<td>'.link_projeto($linha['licao_projeto']).'</td>';
		echo '<td nowrap="nowrap">'.($linha['licao_tipo']? 'Positiva' : 'Negativa').'</td>';
		echo '<td nowrap="nowrap">'.(isset($licao_categoria[$linha['licao_categoria']]) ? $licao_categoria[$linha['licao_categoria']] : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['licao_responsavel'],'','','esquerda').'</td>';
		echo '</tr>';
		}
	}
if (!count($licao)) echo '<tr><td colspan=20><p>Nenhuma lição aprendida encontrada.</p></td></tr>';
echo '</table>';
?>