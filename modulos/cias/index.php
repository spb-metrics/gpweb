<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');
if (!$Aplic->checarModulo('cias', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$dialogo) $Aplic->salvarPosicao();

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;


if (isset($_REQUEST['tab'])) $Aplic->setEstado('ciasListaTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ciasListaTab') !== null ? $Aplic->getEstado('ciasListaTab') : 0;

$procurar_string = getParam($_REQUEST, 'procurar_string', '');
if ($procurar_string != '') {
	$procurar_string = $procurar_string == '-1' ? '' : $procurar_string;
	$Aplic->setEstado('procurar_string', $procurar_string);
	}
else $procurar_string = $Aplic->getEstado('procurar_string');
$procurar_string = formSeguro($procurar_string, true);

$tipos = getSisValor('TipoOrganizacao');

$tipos=array(null => 'Todas')+$tipos;

if (isset($_REQUEST['tipoCia'])) $Aplic->setEstado('tipoCia', getParam($_REQUEST, 'tipoCia', null));
$tipoCia = $Aplic->getEstado('tipoCia') !== null ? $Aplic->getEstado('tipoCia') : null;

$procurar_string = addslashes($procurar_string);

echo '<form name="env" id="env" method="POST">';
echo '<input type="hidden" name="dialogo" id="dialogo" value="" />';
echo '<input type="hidden" name="m" id="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" id="a" value="'.$a.'" />';

if (!$dialogo){
	$selecionar_tipos='<tr><td align=right>'.dica('Tipo', 'Pesquisar as '.$config['organizacoes'].' pelo tipo das mesmas.').'Tipo:'.dicaF().'</td><td>'.selecionaVetor($tipos, 'tipoCia', 'size="1" class="texto" style="width:190px;" onChange="enviar();"', $tipoCia).'</td></tr>';
	$pesquisar='<tr><td align=right>'.dica('Pesquisar', 'Pesquisar as '.$config['organizacoes'].' pelo campo texto � direita.').'Pesquisar:'.dicaF().'</td><td>'.'<input class="texto" type="text" style="width:190px;" name="procurar_string" value="'.$procurar_string.'" /></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=index&procurar_string=-1\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.ucfirst($config['organizacao']), 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:190px;" onchange="javascript:mudar_om();"', '&nbsp;').'</div></td><td><a href="javascript:void(0);" onclick="enviar();">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste �cone '.imagem('icones/filtrar_p.png').' para aplicar o filtro.').'</a></td></tr>';
	}

if (!$dialogo && $Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo($config['organizacoes'], 'organizacao.png', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e A��es','Clique nesta barra para esconder/mostrar os filtros e as a��es permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/organizacao_p.gif').'&nbsp;Filtros e A��es</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	$novo= ($podeEditar && $Aplic->usuario_super_admin ? '<tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_organizacao'].' '.$config['organizacao'], 'Criar uma nov'.$config['genero_organizacao'].' '.$config['organizacao'].' no Sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=editar\');" ><img src="'.acharImagem('organizacao_novo.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr>' : '');
	$imprimir='<tr><td>'.dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poder� imprimir a lista de '.$config['organizacoes'].'.').'<a href="javascript: void(0)" onclick="url_passar(1, \'m='.$m.'&a='.$a.'&dialogo=1&tab='.$tab.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$pesquisar.$selecionar_tipos.'</table></td><td><table cellspacing=0 cellpadding=0>'.$novo.$imprimir.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	}
elseif (!$dialogo && !$Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo($config['organizacoes'], 'organizacao.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0>'.$procurar_om.$pesquisar.$selecionar_tipos.'</table>');
	if ($podeEditar && $Aplic->usuario_super_admin) $botoesTitulo->adicionaCelula('<table width=75><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_organizacao'].' '.$config['organizacao'], 'Criar uma nov'.$config['genero_organizacao'].' '.$config['organizacao'].' no Sistema.').'<a class="botao" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=editar\');" ><span>nov'.$config['genero_organizacao'].'</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->adicionaCelula(dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poder� imprimir a lista de '.$config['organizacoes'].'.').'<a href="javascript: void(0)" onclick="url_passar(1, \'m='.$m.'&a='.$a.'&dialogo=1&tab='.$tab.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	}

echo '</form>';

if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	}

$caixaTab = new CTabBox('m=cias', BASE_DIR.'/modulos/cias/', $tab);


if (!$dialogo){	
	$caixaTab->adicionar('ver_cias', 'Ativ'.$config['genero_organizacao'].'s',null,null,'Ativ'.$config['genero_organizacao'].'s','Visualizar '.$config['genero_organizacao'].'s '.$config['organizacoes'].' ativ'.$config['genero_organizacao'].'s.');
	$caixaTab->adicionar('ver_cias', 'Inativ'.$config['genero_organizacao'].'s',null,null,'Inativ'.$config['genero_organizacao'].'s','Visualizar '.$config['genero_organizacao'].'s '.$config['organizacoes'].' inativ'.$config['genero_organizacao'].'s.');
	$caixaTab->adicionar('ver_cias', 'Tod'.$config['genero_organizacao'].'s',null,null,'Tod'.$config['genero_organizacao'].'s','Visualizar tod'.$config['genero_organizacao'].'s '.$config['genero_organizacao'].'s '.$config['organizacoes'].'.');
	$caixaTab->adicionar('subordinacoes', 'Organograma',null,null,'Organograma','Visualizar o organograma d'.$config['genero_organizacao'].'s '.$config['organizacoes'].'.');
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','',$tab);
	}
else{
	if ($tab) include_once BASE_DIR.'/modulos/cias/subordinacoes.php';
	else include_once BASE_DIR.'/modulos/cias/ver_cias.php';
	}
?>
<script language="javascript">

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size="1" style="width:190px;" onchange="javascript:mudar_om();"','&nbsp;');
	}
function enviar(){
    env.submit();
    }
</script>