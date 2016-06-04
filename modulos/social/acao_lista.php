<?php
global $dialogo;

if (!($podeAcessar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql = new BDConsulta;

if (isset($_REQUEST['social_id'])) $Aplic->setEstado('social_id', getParam($_REQUEST, 'social_id', null));
$social_id = ($Aplic->getEstado('social_id') !== null ? $Aplic->getEstado('social_id') : null);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('AcaoListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('AcaoListaTab') !== null ? $Aplic->getEstado('AcaoListaTab') : 0);

if (isset($_REQUEST['acaobusca'])) $Aplic->setEstado('acaobusca', getParam($_REQUEST, 'acaobusca', null));
$pesquisa = $Aplic->getEstado('acaobusca') !== null ? $Aplic->getEstado('acaobusca') : '';

$sql = new BDConsulta;

$lista_programas=array('' => '');
$sql->adTabela('social');
$sql->adCampo('social_id, social_nome');
$sql->adOrdem('social_nome');
$lista_programas+= $sql->listaVetorChave('social_id', 'social_nome');
$sql->limpar();

if (!$dialogo){
	echo '<form name="frm_filtro" id="frm_filtro" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="" />';
	$botoesTitulo = new CBlocoTitulo('Lista de Ações Sociais', '../../../modulos/social/imagens/acao.gif', $m, $m.'.'.$a);
	$programas='<tr><td nowrap="nowrap" align="right">'.dica('Programa Social', 'Filtre as famílias por programa social em que estão inseridas.').'Programa:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_programas, 'social_id', 'size="1" style="width:160px;" class="texto" onChange="document.frm_filtro.submit();"', $social_id) .'</td></tr>';
	$pesquisar='<tr><td nowrap="nowrap" align="right">'.dica('Pesquisa', 'Pesquisar as ações sociais pelo campo texto à direita.').'Pesquisar:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" class="texto" style="width:145px;" name="acaobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisa.'"/><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=acao_lista&acaobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$pesquisar.'</table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$programas.'</table>');
	if ($Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_acao') || $Aplic->usuario_super_admin) $botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Nova Ação Social', 'Criar uma nova ação social, que será integrante de um programa social.').'<a class="botao" href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'acao_editar\'; frm_filtro.submit();" ><span>nova&nbsp;ação</span></a>'.dicaF().'</td></tr><tr><td nowrap="nowrap"></td></tr></table>');
	$botoesTitulo->adicionaCelula('<td nowrap="nowrap" align="right">'.dica('Imprimir Ações Sociais', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de ações sociais.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=social&a='.$a.'&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo '</form>';
	}

if (!$dialogo)echo estiloTopoCaixa();
include_once(BASE_DIR.'/modulos/social/ver_idx_acao.php');

if (!$dialogo) echo estiloFundoCaixa();
if ($dialogo) echo '<script language="javascript">self.print()</script>';

?>
