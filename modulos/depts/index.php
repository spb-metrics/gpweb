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

if (!$Aplic->checarModulo('depts', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ListaSecaoTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ListaSecaoTab') !== null ? $Aplic->getEstado('ListaSecaoTab') : 0;

if (isset($_REQUEST['ordemPor'])) {
	$ordemDir = $Aplic->getEstado('DeptIdxOrdemDir') ? ($Aplic->getEstado('DeptIdxOrdemDir') == 'asc' ? 'desc' : 'asc') : 'desc';
	$Aplic->setEstado('DeptIdxOrdemPor', getParam($_REQUEST, 'ordemPor', null));
	$Aplic->setEstado('DeptIdxOrdemDir', $ordemDir);
	}
$ordenarPor = $Aplic->getEstado('DeptIdxOrdemPor') ? $Aplic->getEstado('DeptIdxOrdemPor') : 'dept_nome';
$ordemDir = $Aplic->getEstado('DeptIdxOrdemDir') ? $Aplic->getEstado('DeptIdxOrdemDir') : 'asc';
if (isset($_REQUEST['contato_id'])) $Aplic->setEstado('contato_id', getParam($_REQUEST, 'contato_id', 0));
$contato_id = $Aplic->getEstado('contato_id') ? $Aplic->getEstado('contato_id') : 0;
if ($contato_id < 0) $contato_id=0;
if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', 0));
$cia_id = ($Aplic->getEstado('cia_id') ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
$tipos = getSisValor('TipoDepartamento');

if (isset($_REQUEST['procurar_string'])) $Aplic->setEstado('procurar_string', getParam($_REQUEST, 'procurar_string', ''));
$procurar_string = ($Aplic->getEstado('procurar_string') ? $Aplic->getEstado('procurar_string') : '');


echo '<form name="frm_dept" id="frm_dept" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';


$pesquisar='<tr><td align=right>'.dica('Pesquisar '.$config['genero_dept'].'s '.$config['departamentos'], 'Pesquisar '.strtolower($config['departamentos']).' pelo campo texto à direita.').'Pesquisar:'.dicaF().'</td><td><input class="texto" type="text" name="procurar_string" style="width:250px;" value="'.$procurar_string.'" onchange="frm_dept.submit();" /></td><td><a href="javascript:void(0);" onclick="frm_dept.procurar_string.value=\'\'; frm_dept.submit();">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_dept.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada à esquerda.').'</a></td></tr>';
$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="contato_id" name="contato_id" value="'.$contato_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_contato($contato_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
$procurar_string = addslashes($procurar_string);
if (!$dialogo && $Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['departamentos']), 'depts.png', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/secoes_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';


	$novo=($cia_id && $Aplic->checarModulo('depts', 'adicionar') && ($Aplic->usuario_super_admin || $Aplic->usuario_admin) ? '<tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_dept'].' '.$config['departamento'], 'Criar um'.$config['genero_dept'].' nov'.$config['genero_dept'].' '.$config['departamento'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].' atual.').'<a href="javascript: void(0)" onclick="javascript:frm_dept.a.value=\'editar\'; frm_dept.submit();" ><img src="'.acharImagem('depts_novo.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr>' : '');

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$pesquisar.$procurar_om.$procurar_usuario.'</table></td><td><table cellspacing=0 cellpadding=0>'.$novo.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();


	}
elseif (!$dialogo && !$Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['departamentos']), 'depts.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$pesquisar.'<tr><td><td>&nbsp;</td></tr></table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.'</table>');
	if ($cia_id && $Aplic->checarModulo('depts', 'adicionar') && ($Aplic->usuario_super_admin || $Aplic->usuario_admin)) $botoesTitulo->adicionaCelula('<table width=85><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_dept'].' '.$config['departamento'], 'Criar um'.$config['genero_dept'].' nov'.$config['genero_dept'].' '.$config['departamento'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].' atual.').'<a class="botao" href="javascript: void(0)" onclick="javascript:frm_dept.a.value=\'editar\'; frm_dept.submit();" ><span>nov'.$config['genero_dept'].'</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->mostrar();
	}

echo '</form>';

if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	}


if (isset($_REQUEST['tab'])) $Aplic->setEstado('DeptListaTab', getParam($_REQUEST, 'tab', null));
$tabTiposDepts = $Aplic->getEstado('DeptListaTab') !== null ? $Aplic->getEstado('DeptListaTab') : 0;
$deptsType = $tabTiposDepts;
$caixaTab = new CTabBox('m=depts', BASE_DIR.'/modulos/depts/', $tabTiposDepts);

$caixaTab->adicionar('ver_depts', 'Ativ'.$config['genero_dept'].'s',null,null,'Ativ'.$config['genero_dept'].'s','Visualizar '.$config['genero_dept'].'s '.$config['departamentos'].' ativ'.$config['genero_dept'].'s.');
$caixaTab->adicionar('ver_depts', 'Inativ'.$config['genero_dept'].'s',null,null,'Inativ'.$config['genero_dept'].'s','Visualizar '.$config['genero_dept'].'s '.$config['departamentos'].' inativ'.$config['genero_dept'].'s.');
$caixaTab->adicionar('ver_depts', 'Tod'.$config['genero_dept'].'s',null,null,'Tod'.$config['genero_dept'].'s','Visualizar tod'.$config['genero_dept'].'s '.$config['genero_dept'].'s '.$config['departamentos'].'.');

$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','',$tab);
?>

<script language="javascript">

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&contato=1&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('contato_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&contato=1&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('contato_id').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('contato_id').value=usuario_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	frm_dept.submit();
	}

function mudar_om(){
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}


</script>