<?php
global $dialogo;

$sql = new BDConsulta;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('EstrategiaListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('EstrategiaListaTab') !== null ? $Aplic->getEstado('EstrategiaListaTab') : 0);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
if ($dept_id) $ver_subordinadas = null;

if (isset($_REQUEST['favorito_id'])) $Aplic->setEstado('estrategia_favorito', getParam($_REQUEST, 'favorito_id', null));
$favorito_id = $Aplic->getEstado('estrategia_favorito') !== null ? $Aplic->getEstado('estrategia_favorito') : 0;

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;

if (isset($_REQUEST['estrategiatextobusca'])) $Aplic->setEstado('estrategiatextobusca', getParam($_REQUEST, 'estrategiatextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('estrategiatextobusca') ? $Aplic->getEstado('estrategiatextobusca') : '');

if (isset($_REQUEST['pg_id'])) $Aplic->setEstado('pg_id', getParam($_REQUEST, 'pg_id', null));
$pg_id = ($Aplic->getEstado('pg_id') !== null ? $Aplic->getEstado('pg_id') : null);

$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

if (isset($_REQUEST['ver_dept_subordinados'])) $Aplic->setEstado('ver_dept_subordinados', getParam($_REQUEST, 'ver_dept_subordinados', null));
$ver_dept_subordinados = ($Aplic->getEstado('ver_dept_subordinados') !== null ? $Aplic->getEstado('ver_dept_subordinados') : (($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) ? $Aplic->usuario_prefs['ver_dept_subordinados'] : 0));
if ($ver_subordinadas) $ver_dept_subordinados=0;

$lista_depts='';
if ($ver_dept_subordinados){
	$vetor_depts=array();
	lista_depts_subordinados($dept_id, $vetor_depts);
	$vetor_depts[]=$dept_id;
	$lista_depts=implode(',',$vetor_depts);
	}

$sql->adTabela('plano_gestao');
$sql->adCampo('DISTINCT pg_id, pg_nome');
if ($ver_subordinadas) $sql->adOnde('pg_cia IN ('.$lista_cias.')');
else $sql->adOnde('pg_cia ='.(int)$cia_id);
if ($ver_dept_subordinados && $lista_depts) $sql->adOnde('pg_dept IN ('.$lista_depts.')');
elseif ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);
else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
$sql->adOrdem('pg_nome DESC');
$planos=array(0=>'')+$sql->listaVetorChave('pg_id','pg_nome');
$sql->limpar();


$sql->adTabela('favoritos');
$sql->adCampo('favorito_id, descricao');
$sql->adOnde('estrategia=1');
$sql->adOnde('criador_id='.$Aplic->usuario_id);
$vetor_favoritos=$sql->ListaChave();
$sql->limpar();

$favoritos='';
if (count($vetor_favoritos)) {
	$vetor_favoritos[0]='';
	$favoritos=selecionaVetor($vetor_favoritos, 'favorito_id', 'onchange="document.frm_filtro.submit()" class="texto"', $favorito_id);
	}


if (!$dialogo && $Aplic->profissional){
	$Aplic->salvarPosicao();

	$procuraBuffer = '<tr><td align=right>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descri��o').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="estrategiatextobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=estrategia_lista&estrategiatextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$selecionar_planejamento='<tr><td align="right" nowrap="nowrap">'.dica('Planejamentos Estrat�gico', 'Utilize esta op��o para filtrar pelo planejamentos estrat�gico que est� vinculad'.$config['genero_fator'].'.').'Planejamentos Estrat�gico:</td><td>'.dicaF().selecionaVetor($planos, 'pg_id', 'onchange="frm_filtro.submit();" style="width:250px;" class="texto"', $pg_id).'</td></tr>';
	$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de sele��o � direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	echo '<form name="frm_filtro" id="frm_filtro" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="" />';
	echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
	echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
	$botoesTitulo = new CBlocoTitulo('Lista de '.ucfirst($config['iniciativas']), 'estrategia.gif', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e A��es','Clique nesta barra para esconder/mostrar os filtros e as a��es permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/estrategia_p.gif').'&nbsp;Filtros e A��es</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste �cone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste �cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? '�' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N�o Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste �cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? '�' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste �cone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste �cone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste �cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? '�' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N�o Incluir Subordinad'.$config['genero_dept'].'s','Clique neste �cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? '�' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');


	$nova_estrategia=($podeAdicionar ? '<tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Criar nov'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'<a href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'estrategia_editar\'; frm_filtro.submit();" ><img src="'.acharImagem('estrategia_novo.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr><tr><td nowrap="nowrap"></td></tr>' : '');
	$imprimir='<tr><td nowrap="nowrap" align="right"><a href="javascript: void(0);" onclick ="url_passar(1, \'m=praticas&a=estrategia_lista&dialogo=1\');">'.imagem('imprimir_p.png', 'Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir a lista de '.$config['iniciativas'].'.').'</a></td></tr>';
	$botao_favorito='<tr><td nowrap="nowrap">'.dica('Favoritos', 'Criar ou editar um grupo de '.$config['iniciativas'].' favorit'.$config['genero_iniciativa'].'s, para uma r�pida filtragem.').'<a href="javascript: void(0)" onclick="url_passar(0, \'m=publico&a=favoritos&estrategia=1\');"><img src="'.acharImagem('favorito_p.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr><tr><td nowrap="nowrap">'.$favoritos.'</td></tr>';

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$selecionar_planejamento.$procuraBuffer.'</table></td><td><table cellspacing=0 cellpadding=0>'.$nova_estrategia.$imprimir.$botao_favorito.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	echo '</form>';
	}
elseif (!$dialogo && !$Aplic->profissional){
	$Aplic->salvarPosicao();

	$procuraBuffer = '<tr><td align=right>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descri��o').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="estrategiatextobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=estrategia_lista&estrategiatextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$selecionar_planejamento='<tr><td align="right" nowrap="nowrap">'.dica('Planejamentos Estrat�gico', 'Utilize esta op��o para filtrar pelo planejamentos estrat�gico que est� vinculad'.$config['genero_fator'].'.').'Planejamentos Estrat�gico:</td><td>'.dicaF().selecionaVetor($planos, 'pg_id', 'onchange="frm_filtro.submit();" style="width:250px;" class="texto"', $pg_id).'</td></tr>';
	$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de sele��o � direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	echo '<form name="frm_filtro" id="frm_filtro" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="" />';
	echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
	echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
	$botoesTitulo = new CBlocoTitulo('Lista de '.ucfirst($config['iniciativas']), 'estrategia.gif', $m, $m.'.'.$a);
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste �cone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste �cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? '�' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N�o Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste �cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? '�' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste �cone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste �cone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste �cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? '�' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N�o Incluir Subordinad'.$config['genero_dept'].'s','Clique neste �cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? '�' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0>'.$procurar_om.$procurar_usuario.$selecionar_planejamento.$procuraBuffer.'</table>');
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Criar nov'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'estrategia_editar\'; frm_filtro.submit();" ><span>nov'.$config['genero_iniciativa'].'</span></a>'.dicaF().'</td></tr><tr><td nowrap="nowrap"></td></tr></table>');
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0><tr><td nowrap="nowrap" align="right">'.dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir a lista de '.$config['iniciativas'].'.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=praticas&a=estrategia_lista&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->mostrar();
	echo '</form>';
	}

if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	}


if (!$dialogo){
	$caixaTab = new CTabBox('m=praticas&a=estrategia_lista', '', $tab);
	$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/estrategias_ver_idx','Em execu��o',null,null,'Em execu��o','Visualizar '.$config['genero_iniciativa'].'s '.$config['iniciativas'].' ativ'.$config['genero_iniciativa'].'s que est�o com porcentagem diferente de 100%.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/estrategias_ver_idx','Completad'.$config['genero_iniciativa'].'s',null,null,'Completad'.$config['genero_iniciativa'].'s','Visualizar '.$config['genero_iniciativa'].'s '.$config['iniciativas'].' ativ'.$config['genero_iniciativa'].'s que est�o com porcentagem em 100%.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/estrategias_ver_idx','Inativ'.$config['genero_iniciativa'].'s',null,null,'Inativ'.$config['genero_iniciativa'].'s','Visualizar '.$config['genero_iniciativa'].'s '.$config['iniciativas'].' que foram marcad'.$config['genero_iniciativa'].'s como inativ'.$config['genero_iniciativa'].'s.');
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}
else include_once(BASE_DIR.'/modulos/praticas/estrategias_ver_idx.php');
if ($dialogo) echo '<script language="javascript">self.print();</script>';



?>
<script type="text/javascript">

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	frm_filtro.submit();
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=(usuario_id ? usuario_id : 0);
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	frm_filtro.submit();
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}

</script>