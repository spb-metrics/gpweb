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
if (!$Aplic->checarModulo('recursos', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ListaRecursosTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ListaRecursosTab') !== null ? $Aplic->getEstado('ListaRecursosTab') : 0);


if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', intval(getParam($_REQUEST, 'usuario_id', 0)));
$usuario_id = ($Aplic->getEstado('usuario_id')!== null ? $Aplic->getEstado('usuario_id') : 0);

if (isset($_REQUEST['recurso_tipo'])) $Aplic->setEstado('recurso_tipo', intval(getParam($_REQUEST, 'recurso_tipo', 0)));
$recurso_tipo = ($Aplic->getEstado('recurso_tipo')!== null ? $Aplic->getEstado('recurso_tipo') : 0);

if (isset($_REQUEST['recurso_ano'])) $Aplic->setEstado('recurso_ano', getParam($_REQUEST, 'recurso_ano', ''));
$recurso_ano = ($Aplic->getEstado('recurso_ano')!== null ? $Aplic->getEstado('recurso_ano') : '');

if (isset($_REQUEST['recurso_ugr'])) $Aplic->setEstado('recurso_ugr', getParam($_REQUEST, 'recurso_ugr', ''));
$recurso_ugr = ($Aplic->getEstado('recurso_ugr')!== null ? $Aplic->getEstado('recurso_ugr') : '');

if (isset($_REQUEST['recurso_ptres'])) $Aplic->setEstado('recurso_ptres', getParam($_REQUEST, 'recurso_ptres', ''));
$recurso_ptres = ($Aplic->getEstado('recurso_ptres')!== null ? $Aplic->getEstado('recurso_ptres') : '');

if (isset($_REQUEST['recurso_credito_adicional'])) $Aplic->setEstado('recurso_credito_adicional', getParam($_REQUEST, 'recurso_credito_adicional', null));
$recurso_credito_adicional = ($Aplic->getEstado('recurso_credito_adicional') !== null ? $Aplic->getEstado('recurso_credito_adicional') : '');

if (isset($_REQUEST['recurso_movimentacao_orcamentaria'])) $Aplic->setEstado('recurso_movimentacao_orcamentaria', getParam($_REQUEST, 'recurso_movimentacao_orcamentaria', null));
$recurso_movimentacao_orcamentaria = ($Aplic->getEstado('recurso_movimentacao_orcamentaria') !== null ? $Aplic->getEstado('recurso_movimentacao_orcamentaria') : '');

if (isset($_REQUEST['recurso_identificador_uso'])) $Aplic->setEstado('recurso_identificador_uso', getParam($_REQUEST, 'recurso_identificador_uso', null));
$recurso_identificador_uso = ($Aplic->getEstado('recurso_identificador_uso') !== null ? $Aplic->getEstado('recurso_identificador_uso') : '');

if (isset($_REQUEST['recurso_pesquisa'])) $Aplic->setEstado('recurso_pesquisa', getParam($_REQUEST, 'recurso_pesquisa', null));
$recurso_pesquisa = ($Aplic->getEstado('recurso_pesquisa') !== null ? $Aplic->getEstado('recurso_pesquisa') : '');

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
if ($dept_id) $ver_subordinadas = null;

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



$listaTipo=array(''=>'')+getSisValor('TipoRecurso');


$sql = new BDConsulta;
$sql->adTabela('recursos');
$sql->adCampo('DISTINCT recurso_ano');
if ($cia_id && !$lista_cias) $sql->adOnde('recurso_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');
$anos = $sql->listaVetorChave('recurso_ano','recurso_ano');
$sql->limpar();
$anos =array(''=>'')+$anos;


$sql->adTabela('recursos');
$sql->adCampo('DISTINCT recurso_ugr');
if ($cia_id && !$lista_cias) $sql->adOnde('recurso_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');
$lista_ugrs = $sql->listaVetorChave('recurso_ugr','recurso_ugr');
$sql->limpar();
$lista_ugrs =array(''=>'')+$lista_ugrs;

$sql->adTabela('recursos');
$sql->adCampo('DISTINCT recurso_ptres');
if ($cia_id && !$lista_cias) $sql->adOnde('recurso_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');
$listaPtres = $sql->listaVetorChave('recurso_ptres','recurso_ptres');
$sql->limpar();
$listaPtres =array(''=>'')+$listaPtres;


$MovimentacaoOrcamentaria=array(''=>'')+getSisValor('MovimentacaoOrcamentaria');
$CreditoAdicional=array(''=>'')+getSisValor('CreditoAdicional');
$IdentificadorUso=array(''=>'')+getSisValor('IdentificadorUso');

$identificador='<tr id="identificador" '.($recurso_tipo!=5 ? 'style="display:none"' : '').' ><td align="right" nowrap="nowrap">'.dica('Identificador de Uso', 'O uso deste recurso.').'Idt:'.dicaF().'</td><td>'.selecionaVetor($IdentificadorUso, 'recurso_identificador_uso', 'class=texto size=1 style="width:250px;"', $recurso_identificador_uso).'</td></tr>';
$credito_adicional='<tr id="credito_adicional" '.($recurso_tipo!=5 ? 'style="display:none"' : '').' ><td align="right" nowrap="nowrap">'.dica('Crédito Adicional', 'Caso seja monetário, seleciona o crédito adicional deste recurso, se for o caso.').'Crédito adicional:'.dicaF().'</td><td>'.selecionaVetor($CreditoAdicional, 'recurso_credito_adicional', 'style="width:250px;" class="texto"', $recurso_credito_adicional).'</td></tr>';
$movimentacao='<tr id="movimentacao" '.($recurso_tipo!=5 ? 'style="display:none"' : '').' ><td align="right" nowrap="nowrap">'.dica('Movimentação Orcamentária', 'Caso seja monetário, seleciona a movimentação orcamentária deste recurso, se for o caso.').'Movimentação:'.dicaF().'</td><td>'.selecionaVetor($MovimentacaoOrcamentaria, 'recurso_movimentacao_orcamentaria', 'style="width:250px;" class="texto"', $recurso_movimentacao_orcamentaria).'</td></tr>';
$ptres='<tr id="ptres" '.($recurso_tipo!=5 ? 'style="display:none"' : '').' ><td align="right" nowrap="nowrap">'.dica('Plano de Trabalho Resumido', 'Insira o plano de trabalho resumido deste recurso.').'PTRES:'.dicaF().'</td><td>'.selecionaVetor($listaPtres, 'recurso_ptres', 'style="width:250px;" class="texto"', $recurso_ptres).'</td></tr>';
$ano='<tr id="combo_ano" '.($recurso_tipo!=5 ? 'style="display:none"' : '').' ><td align="right" >'.dica('Ano', 'Insira o ano deste recurso.').'Ano:'.dicaF().'</td><td>'.selecionaVetor($anos, 'recurso_ano', 'style="width:250px;" class="texto"', $recurso_ano).'</td></tr>';
$ugrs='<tr id="ugrs" '.($recurso_tipo!=5 ? 'style="display:none"' : '').' ><td align="right" >'.dica('Unidade Gestora do Recurso', 'A unidade gestora do recurso.').'UGR:'.dicaF().'</td><td>'.selecionaVetor($lista_ugrs, 'recurso_ugr', 'style="width:250px;" class="texto"', $recurso_ugr).'</td></tr>';
$tipo='<tr><td align="right">'.dica('Tipo', 'Selecione qual o tipo de recurso.').'Tipo:'.dicaF().'</td><td align="left">'.selecionaVetor($listaTipo, 'recurso_tipo', 'style="width:250px;" onchange="ver_orcamentario();" class="texto"', $recurso_tipo).'</td></tr>';
$pesquisa='<tr><tr><td nowrap="nowrap" align="right">'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="recurso_pesquisa" onChange="document.frm_filtro.submit();" value="'.$recurso_pesquisa.'" /></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=recursos&a=index&recurso_pesquisa=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td><td colspan=2>&nbsp;</td></tr>';


$filtro='<tr><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar os recursos.').'</a></td></tr>';
echo '<form name="frm_filtro" id="frm_filtro" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="cia_dept" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';

$podeEditar = $podeEditar;
if (!$dialogo && $Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Recursos', 'recursos.png', $m, $m.'.'.$a);

	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');


	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/recursos_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	$novo=($podeEditar ? '<tr><td nowrap="nowrap">'.dica('Novo Recurso', 'Criar um novo recurso.').'<a href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=recursos&a=editar\');"><img src="'.acharImagem('recursos_novo.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr><tr><td  align=center>'.dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de recursos.').'<a href="javascript: void(0)" onclick="url_passar(1, \'m=recursos&a=index&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'. dicaF().'</td></tr>' : '');

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$identificador.$tipo.$pesquisa.$credito_adicional.$movimentacao.$ano.$ptres.$ugrs.'</table></td><td><table cellspacing=0 cellpadding=0>'.$filtro.$novo.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	}
elseif (!$dialogo && !$Aplic->profissional){

	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');


	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Recursos', 'recursos.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0>'.$tipo.$pesquisa.$credito_adicional.$movimentacao.'</table>');
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0>'.$ano.$ptres.$ugrs.'</table>');
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0>'.$procurar_om.$procurar_usuario.$identificador.'</table>');
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0>'.$filtro.'</table>');
	if ($podeEditar)$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Recurso', 'Criar um novo recurso.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=recursos&a=editar\');"><span>novo</span></a>'.dicaF().'</td></tr><tr><td  align=center>'.dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de recursos.').'<a href="javascript: void(0)" onclick="url_passar(1, \'m=recursos&a=index&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'. dicaF().'</td></tr></table>');
	$botoesTitulo->mostrar();
	}

echo '</form>';

if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	}


$caixaTab = new CTabBox('m='.$m.'&a='.$a, '', $tab);
if (!$dialogo){
	$caixaTab->adicionar(BASE_DIR.'/modulos/recursos/ver_recursos', 'Ativos',null,null,'Ativos','Visualizar os recursos ativos.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/recursos/ver_recursos', 'Inativos',null,null,'Inativos','Visualizar os recursos inativos.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/recursos/ver_recursos', 'Todos',null,null,'Todos','Visualizar todos os recursos.');
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}
else {
	include_once(BASE_DIR.'/modulos/recursos/ver_recursos.php');
	echo '<script language="javascript">self.print();</script>';
	}



?>
<script language="javascript">

function ver_orcamentario(){


	if (document.getElementById('recurso_tipo').value==5){
		document.getElementById('combo_ano').style.display='';
		document.getElementById('identificador').style.display='';
		document.getElementById('credito_adicional').style.display='';
		document.getElementById('movimentacao').style.display='';
		document.getElementById('ptres').style.display='';
		document.getElementById('ugrs').style.display='';
		}
	else {
		document.getElementById('combo_ano').style.display='none';
		document.getElementById('identificador').style.display='none';
		document.getElementById('credito_adicional').style.display='none';
		document.getElementById('movimentacao').style.display='none';
		document.getElementById('ptres').style.display='none';
		document.getElementById('ugrs').style.display='none';
		}

	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}


function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia, deptartamento, nome){
	frm_filtro.dept_id.value=deptartamento;
	frm_filtro.submit();
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	frm_filtro.submit();
	}


</script>