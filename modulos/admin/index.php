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

$podeEditar=($podeEditar || $Aplic->usuario_admin);

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!($podeAcessar || $Aplic->usuario_super_admin || $Aplic->usuario_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$dialogo) $Aplic->salvarPosicao();

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', intval(getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia)));
$cia_id = $Aplic->getEstado('cia_id', $Aplic->usuario_cia);

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);


$cha_string = getParam($_REQUEST, 'cha_string', '');
$usuario_perfil=getParam($_REQUEST, 'usuario_perfil', null);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if ($dept_id) $ver_subordinadas = null;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('usuario_idTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('usuario_idTab') !== null ? $Aplic->getEstado('usuario_idTab') : 0;
if (isset($_REQUEST['stub'])) {
	$Aplic->setEstado('usuario_idStub', getParam($_REQUEST, 'stub', null));
	$Aplic->setEstado('usuario_idOnde', '');
	}
elseif (isset($_REQUEST['onde'])) {
	$Aplic->setEstado('usuario_idOnde', getParam($_REQUEST, 'onde', null));
	$Aplic->setEstado('usuario_idStub', '');
	}
$procura = $Aplic->getEstado('usuario_idStub');
$onde = $Aplic->getEstado('usuario_idOnde');
if (isset($_REQUEST['ordemPor'])) $Aplic->setEstado('usuario_idOrderby', getParam($_REQUEST, 'ordemPor', null));
$ordenarPor = $Aplic->getEstado('usuario_idOrderby') ? $Aplic->getEstado('usuario_idOrderby') : 'usuario_login';
$ordenarPor = ($tab == 3 || ($ordenarPor != 'entrou' && $ordenarPor != 'usuario_ip')) ? $ordenarPor : 'usuario_login';

$ordem = getParam($_REQUEST, 'ordem', '');
$ordem=($ordem=='ASC' ? 'DESC' : 'ASC');




$sql = new BDConsulta;

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


$let = ":";
$sql->adTabela('usuarios', 'u');
$sql->adCampo('DISTINCT UPPER(SUBSTRING(usuario_login, 1, 1)) AS L');
$arr = $sql->Lista();
$sql->Limpar();
foreach ($arr as $L) @$let.= $L['L'];


$q = new BDConsulta;
$sql->adTabela('usuarios', 'u');
$sql->adCampo('DISTINCT UPPER(SUBSTRING(contato_posto, 1, 1)) AS L');
$sql->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
$arr = $sql->Lista();
$sql->Limpar();
foreach ($arr as $L) {
	if( @$L['L']) $let .= strpos($let, $L['L']) ? '' : $L['L'];
	}


$sql->adTabela('usuarios', 'u');
$sql->adCampo('DISTINCT UPPER(SUBSTRING(contato_nomeguerra, 1, 1)) AS L');
$sql->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
$arr = $sql->Lista();
$sql->Limpar();

foreach ($arr as $L) {
	if( @$L['L']) $let .= strpos($let, $L['L']) ? '' : $L['L'];
	}

if ($Aplic->profissional){
	
	}

echo '<form name="frm_filtro" id="frm_filtro" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="ver_subordinadas" id="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" id="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
echo '<input type="hidden" name="cha_string" id="cha_string" value="" />';

if (!$dialogo && !$Aplic->profissional){

	$alfabeto = '<table cellpadding=1 cellspacing=1 border=0><tr>';
	$alfabeto .= '<td width="100%" align="right">'.dica('Mostrar', 'Selecione à direita por qual letra deseja filtrar '.$config['genero_usuario'].'s '.$config['usuarios'].'. Serão pesquisados pelos login, '.($config['militar'] < 10 ? 'nomes de guerra e posto/grad.' : 'pronome de tratamento e nome d'.$config['genero_usuario'].' '.$config['usuario'].'.')).'Mostrar:'.dicaF().'</td>';
	$alfabeto .= '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&stub=0\');">'.dica('Mostrar Todos', 'Mostrar todos os conatos.').'Todos'.dicaF().'</a></td>';
	for ($c = 65; $c < 91; $c++) {
		$cu = chr($c);
		$cell = strpos($let, $cu) > 0 ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&stub='.$cu.'\');">'.dica('Filtrar pela Letra '.$cu, 'Mostrar '.$config['genero_usuario'].'s '.$config['usuarios'].' em que o login, '.($config['militar'] < 10 ? 'nomes de guerra ou posto/grad.' : 'pronome de tratamento ou nome d'.$config['genero_usuario'].' '.$config['usuario'].'.').' começem com a letra '.$cu.'.').$cu.dicaF().'</a>' : '<font color="#999999">'.$cu.'</font>';
		$alfabeto .= '<td>'.$cell.'</td>';
		}
	$alfabeto .= '</tr></table>';


	$botoesTitulo = new CBlocoTitulo('Administração de '.ucfirst($config['usuarios']), 'membro.png', $m, "$m.$a");

	$procurar_om='<table><tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada à esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinadas','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinadas à selecionada.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinadas','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinadas à selecionada.').'</a></td>' : '').'</tr></table>';
	$botoesTitulo->adicionaCelula($procurar_om);
	$botoesTitulo->adicionaCelula('<table width=110><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_usuario'].' '.ucfirst($config['usuario']), 'Adicionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' no Sistema.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=admin&a=editar_usuario\');" ><span>nov'.$config['genero_usuario'].'</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->mostrar();
	$botoesTitulo = new CBlocoTitulo('', '', $m, "$m.$a");
	$botoesTitulo->adicionaCelula(dica('Pesquisar', 'Pesquisar '.$config['genero_usuario'].'s '.$config['usuarios'].' baseados no texto inserido à esquerda.').'Pesquisar:&nbsp;'.dicaF().'<input type="text" name="onde" class="texto" style="width:250px;" value="'.$onde.'" /><a href="javascript:void(0);" onclick="frm_filtro.onde.value=\'\'; frm_filtro.submit();">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a>');
	$botoesTitulo->adicionaCelula($alfabeto);
	$botoesTitulo->mostrar();
	}

elseif (!$dialogo && $Aplic->profissional){
	$Aplic->salvarPosicao();

	$botoesTitulo = new CBlocoTitulo('Administração de '.ucfirst($config['usuarios']), 'membro.png', $m, "$m.$a");

	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr></td></table></td></tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr></table></td></tr>' : '');


	$novo_usuario='<tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_usuario'].' '.ucfirst($config['usuario']), 'Adicionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' no Sistema.').'<a href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=admin&a=editar_usuario\');" >'.imagem('icones/membros_novo.png').'</a>'.dicaF().'</td></tr>';
	$imprimir='<tr><td nowrap="nowrap" align="right">'.dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de '.$config['iniciativas'].'.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m='.$m.'&a='.$a.'&u='.$u.'&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';

	$cha_botao='<tr><td nowrap="nowrap" align="right"><a href="javascript:void(0);" onclick="if (document.getElementById(\'cha\').style.display) document.getElementById(\'cha\').style.display=\'\'; else document.getElementById(\'cha\').style.display=\'none\';"">'.imagem('icones/opcoes_filtro.gif','Filtros Extras','Clique neste ícone '.imagem('icones/opcoes_filtro.gif').' para aparecer outras opções de filtragem.').'</a></td></tr>';
	$pesquisa_botao='<tr><td nowrap="nowrap" align="right">'.dica('Pesquisar', 'Pesquisar '.$config['genero_usuario'].'s '.$config['usuarios'].' baseados no texto inserido à esquerda.').'Pesquisar:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" name="onde" class="texto" style="width:247px;" value="'.$onde.'" /></td><td><a href="javascript:void(0);" onclick="frm_filtro.onde.value=\'\'; frm_filtro.submit();">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></td></tr>';


	$alfabeto = '<tr><td align="right">'.dica('Mostrar', 'Selecione à direita por qual letra deseja filtrar '.$config['genero_usuario'].'s '.$config['usuarios'].'. serão pesquisados pelos login, '.($config['militar'] < 10 ? 'nomes de guerra e posto/grad.' : 'pronome de tratamento e nome d'.$config['genero_usuario'].' '.$config['usuario'].'.')).'Mostrar:'.dicaF().'</td>';
	$alfabeto .= '<td><table cellpadding=1 cellspacing=1 border=0><tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&stub=0\');">'.dica('Mostrar Todos', 'Mostrar todos os conatos.').'Todos'.dicaF().'</a></td>';
	for ($c = 65; $c < 91; $c++) {
		$cu = chr($c);
		$cell = strpos($let, $cu) > 0 ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&stub='.$cu.'\');">'.dica('Filtrar pela Letra '.$cu, 'Mostrar '.$config['genero_usuario'].'s '.$config['usuarios'].' em que o login, '.($config['militar'] < 10 ? 'nomes de guerra ou posto/grad.' : 'pronome de tratamento ou nome d'.$config['genero_usuario'].' '.$config['usuario'].'.').' começem com a letra '.$cu.'.').$cu.dicaF().'</a>' : '<font color="#999999">'.$cu.'</font>';
		$alfabeto .= '<td>'.$cell.'</td>';
		}
	$alfabeto .= '</tr></table></td></tr>';

	include_once BASE_DIR.'/modulos/admin/filtro_extra_pro.php';

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
	$saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/membros_p.png').'&nbsp;Filtros e Ações</a></div>'.dicaF();
	$saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
	$saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$pesquisa_botao.$alfabeto.$saida_cha.'</table></td><td valign=top><table cellspacing=0 cellpadding=0>'.$novo_usuario.$imprimir.$cha_botao.'</table></td></tr></table>';
	$saida.= '</div></div>';

	$botoesTitulo->adicionaCelula($saida);

	$botoesTitulo->mostrar();

	}





echo '</form>';

if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	}

$caixaTab = new CTabBox('m=admin', '', $tab);
$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_usuarios_ativos', ucfirst($config['usuario']).' Ativos',null,null,ucfirst($config['usuario']).' Ativos','Visualizar '.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema que estão ativos.');
$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_usuarios_inativos', ucfirst($config['usuario']).' Inativos',null,null,ucfirst($config['usuario']).' Inativos','Visualizar '.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema que não estão mais trabalhando no Sistema ou que solicitaram participar do '.$config['gpweb'].' mas ainda não foram aprovados.');
$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_registro_usuarios', 'Acessos dos '.ucfirst($config['usuario']),null,null,'Acessos dos '.ucfirst($config['usuario']),'Visualizar data e hora do último acesso d'.$config['genero_usuario'].'s '.$config['usuarios'].'.');
if ($config['checar_comprometimento']) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/sobrecarga', 'Comprometimento',null,null,'Comprometimento','Visualizar o grau de comprometimento, por dia, de '.$config['usuario'].' n'.$config['genero_tarefa'].'s '.$config['tarefas'].' em que esteja designado.');
if ($podeEditar && $podeExcluir) $caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_usuarios_sessoes', 'Sessões Ativas',null,null,'Sessões Ativas','Visualizar as Sessões no Servidor que ainda estão ativas.<br><br>Caso '.$config['genero_usuario'].' '.$config['usuario'].' feche o '.$config['gpweb'].' sem efetuar a saída do mesmo, sua sessão permanecerá ativa.');
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);

echo '<form name="frmExcluir" method="post">';
echo '<input type="hidden" name="m" value="admin" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_usuario_aed" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="usuario_id" value="0" />';
echo '</form>';
?>
<script language="javascript">

function mudar_lista(){

	<?php	if ($Aplic->profissional){?>
		var	loop=Object.keys(vetor_cha);
		var saida='';
		for (var i=0 ; i < Object.keys(loop).length; i++) saida+=(i > 0 ? '#' : '')+loop[i]+','+vetor_cha[loop[i]];
		document.getElementById('cha_string').value=saida;
	<?php	}?>
	document.frm_filtro.submit();
	}

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	frm_filtro.submit();
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}

function meExclua( x, y ) {
	if (confirm( "Tem certeza que deseja excluir o <?php echo $config['usuario'] ?>?" )) {
		document.frmExcluir.usuario_id.value = x;
		document.frmExcluir.submit();
	}
}
</script>
