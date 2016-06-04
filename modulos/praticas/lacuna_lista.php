<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
if (!$dialogo) $Aplic->salvarPosicao();

$sql = new BDConsulta();

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['IdxPraticaAno'])) $Aplic->setEstado('IdxPraticaAno', getParam($_REQUEST, 'IdxPraticaAno', null));
$ano = ($Aplic->getEstado('IdxPraticaAno') !== null ? $Aplic->getEstado('IdxPraticaAno') : null);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['indicadortextobusca'])) $Aplic->setEstado('indicadortextobusca', getParam($_REQUEST, 'indicadortextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('indicadortextobusca') ? $Aplic->getEstado('indicadortextobusca') : '');


if (isset($_REQUEST['indicador_lacuna_tipo'])) $Aplic->setEstado('indicador_lacuna_tipo', getParam($_REQUEST, 'indicador_lacuna_tipo', null));
$indicador_lacuna_tipo = $Aplic->getEstado('indicador_lacuna_tipo') !== null ? $Aplic->getEstado('indicador_lacuna_tipo') : 0;


if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;


$ordenar = getParam($_REQUEST, 'ordenar', 'indicador_lacuna_id');
$ordem = getParam($_REQUEST, 'ordem', '0');

if (isset($_REQUEST['somente_superiores'])) $Aplic->setEstado('somente_superiores', getParam($_REQUEST, 'somente_superiores', null));
$somente_superiores = $Aplic->getEstado('somente_superiores') !== null ? $Aplic->getEstado('somente_superiores') : 0;





if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
if ($dept_id) $ver_subordinadas = null;

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));


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

$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}




$sql->adTabela('indicador_lacuna_nos_marcadores');
$sql->esqUnir('indicador_lacuna','indicador_lacuna', 'indicador_lacuna.indicador_lacuna_id=indicador_lacuna_nos_marcadores.indicador_lacuna_id');
$sql->adCampo('DISTINCT ano');
if ($cia_id) $sql->adOnde('indicador_lacuna_cia='.(int)$cia_id);
if ($usuario_id) $sql->adOnde('indicador_lacuna_responsavel='.(int)$usuario_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();


$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();

$criterio=getParam($_REQUEST, 'criterio',0);
$item=getParam($_REQUEST, 'item',0);


echo '<form name="frm_filtro" id="frm_filtro" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="dept_id" value="" />';
echo '<input type="hidden" name="cia_dept" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';

if (!$dialogo && $Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Lacunas de Indicadores', 'lacuna.png', $m, $m.'.'.$a);


	$procuraBuffer = '<tr><td  align=right>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="indicadortextobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=lacuna_lista&indicadortextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$procurar_ano='<tr><td align=right>'.dica('Seleção do Ano', 'Utilize esta opção para filtrar os indicadores pelo ano em que os mesmos foram atualizados.').'Ano:'.dicaF().'</td><td>'.selecionaVetor($anos, 'IdxPraticaAno', 'onchange="mudar_ano()" class="texto" style="width:250px;"', $ano).'</td></tr>';
	
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');
	
	$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$procura_pauta='<tr><td nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().'</td><td nowrap="nowrap" align="left">'.selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="document.frm_filtro.submit()" class="texto" style="width:250px;"', $pratica_modelo_id).'</td></tr>';
	$imprimir='<tr><td>'.dica('Imprir Lacunas de Indicadores', 'Imprimir a lista de lacunas de indicadores.').'<a href="javascript: void(0);" onclick ="imprimir_praticas();">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';
	$nova_lacuna=($podeAdicionar ? '<tr><td nowrap="nowrap">'.dica('Nova Lacuna de Indicador', 'Criar uma nova lacuna de indicador.').'<a href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'lacuna_editar\'; frm_filtro.submit();" ><img src="'.acharImagem('lacuna_novo.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr>' : '');

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/lacuna_p.png').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$procura_pauta.$procuraBuffer.$procurar_ano.'</table></td><td><table cellspacing=0 cellpadding=0>'.$nova_lacuna.$imprimir.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();

	}
elseif (!$dialogo && !$Aplic->profissional){
	$procuraBuffer = '<td>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" class="texto" style="width:145px;" name="indicadortextobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=lacuna_lista&indicadortextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></td>';
	$botoesTitulo = new CBlocoTitulo('Lacunas de Indicadores', 'lacuna.png', $m, $m.'.'.$a);
	$procurar_ano='<tr><td align=right>'.dica('Seleção do Ano', 'Utilize esta opção para filtrar os indicadores pelo ano em que os mesmos foram atualizados.').'Ano:'.dicaF().'</td><td>'.selecionaVetor($anos, 'IdxPraticaAno', 'onchange="mudar_ano()" class="texto"', $ano).'</td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().'</td><td nowrap="nowrap" align="left">'.selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="document.frm_filtro.submit()" class="texto"', $pratica_modelo_id).'</td></tr><tr>'.$procuraBuffer.'</tr>'.$procurar_ano.'</table>', '', '', '');
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><table cellspacing=0 cellpadding=0><tr><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinadas','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinadas à selecionada.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinadas','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinadas à selecionada.').'</a></td>' : '').($dept_id ? '<td nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').$config['dept'].':<input type="text" class="texto" value="'.nome_dept($dept_id).'"></td>' : '').'<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif','Filtrar pel'.$config['genero_dept'].' '.$config['departamento'],'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].'.').'</a></td></tr></table></td></tr>';
	$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.'</table>');
	if ($podeAdicionar) $botoesTitulo->adicionaCelula();
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0><tr><td>'.dica('Nova Lacuna de Indicador', 'Criar uma nova lacuna de indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'lacuna_editar\'; frm_filtro.submit();" ><span>nova&nbsp;lacuna</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->adicionaCelula(dica('Imprir Lacunas de Indicadores', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de lacunas de indicadores.').'<a href="javascript: void(0);" onclick ="imprimir_praticas();">'.imagem('imprimir_p.png').'</a>'. dicaF());
	$botoesTitulo->mostrar();
	}

echo '</form>';

if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	}




$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_resultado');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
if ($criterio) $sql->adOnde('pratica_criterio_id='.(int)$criterio);
$praticas_criterios=$sql->Lista();
$sql->limpar();

$titulo=array();
$nomes_criterios=array();



$sql->adTabela('indicador_lacuna');
$sql->esqUnir('indicador_lacuna_nos_marcadores', 'indicador_lacuna_nos_marcadores', 'indicador_lacuna_nos_marcadores.indicador_lacuna_id=indicador_lacuna.indicador_lacuna_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =indicador_lacuna_nos_marcadores.pratica_marcador_id');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
if ($ano) $sql->adOnde('indicador_lacuna_nos_marcadores.ano ='.(int)$ano);
if ($pratica_modelo_id) $sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
if ($usuario_id) $sql->adOnde('indicador_lacuna_responsavel='.(int)$usuario_id);

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('indicador_lacuna_depts','indicador_lacuna_depts', 'indicador_lacuna_depts.indicador_lacuna_id=indicador_lacuna.indicador_lacuna_id');
	$sql->adOnde('indicador_lacuna_dept='.(int)$dept_id.' OR indicador_lacuna_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('indicador_lacuna_depts','indicador_lacuna_depts', 'indicador_lacuna_depts.indicador_lacuna_id=indicador_lacuna.indicador_lacuna_id');
	$sql->adOnde('indicador_lacuna_dept IN ('.$lista_depts.') OR indicador_lacuna_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('indicador_lacuna_cia', 'indicador_lacuna_cia', 'indicador_lacuna.indicador_lacuna_id=indicador_lacuna_cia_indicador_lacuna');
	$sql->adOnde('indicador_lacuna_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR indicador_lacuna_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('indicador_lacuna_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('indicador_lacuna_cia IN ('.$lista_cias.')');

if ($pesquisar_texto) $sql->adOnde('indicador_lacuna_nome LIKE \'%'.$pesquisar_texto.'%\' OR indicador_lacuna_descricao LIKE \'%'.$pesquisar_texto.'%\'');
$sql->adCampo('DISTINCT indicador_lacuna.indicador_lacuna_id, indicador_lacuna_acesso,  indicador_lacuna_nome, indicador_lacuna_descricao, indicador_lacuna_cor, indicador_lacuna_responsavel, (SELECT COUNT(pratica_marcador_id) FROM indicador_lacuna_nos_marcadores WHERE indicador_lacuna_nos_marcadores.indicador_lacuna_id=indicador_lacuna.indicador_lacuna_id AND pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id.') AS qnt_marcador');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$lacunas=$sql->Lista();
$sql->limpar();



$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$tab = $Aplic->getEstado('PraticaIdxTab') !== null ? $Aplic->getEstado('PraticaIdxTab') : 0;
$pagina = getParam($_REQUEST, 'pagina', 1);
$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$xpg_tamanhoPagina = ($impressao || $dialogo || $m=='projetos' ? 1000 : $config['qnt_indicadores']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1);

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$xpg_totalregistros = ($lacunas ? count($lacunas) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;

echo estiloTopoCaixa();

if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'indicador', 'indicadores','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$impressao) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').(isset($projeto_id) ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) ? '&tarefa_id='.$tarefa_id : '').'&ordenar=indicador_lacuna_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='indicador_lacuna_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor do Indicador', 'Neste campo fica a cor de identificação do indicador.').'Cor'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').(isset($projeto_id) ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) ? '&tarefa_id='.$tarefa_id : '').'&ordenar=indicador_lacuna_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='indicador_lacuna_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome do Indicador', 'Neste campo fica o nome para identificação do indicador.').'Nome'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').(isset($projeto_id) ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) ? '&tarefa_id='.$tarefa_id : '').'&ordenar=indicador_lacuna_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='indicador_lacuna_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição', 'Detalhes sobre do que se trata o indicador.').'Descrição'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').(isset($projeto_id) ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) ? '&tarefa_id='.$tarefa_id : '').'&ordenar=indicador_lacuna_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='indicador_lacuna_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['usuario']), 'O '.$config['usuario'].' responsável pelo indicador.').ucfirst($config['usuario']).dicaF().'</a></th>';
if (!isset($detalhe_projeto)) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=qnt_marcador&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='qnt_marcador' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quantidade de Marcadores', 'A quantidade de marcadores relacionados à pauta selecionada nesta lacuna de indicador.').'<b>Qnt</b>'.dicaF().'</a></th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;

$pontos_totais=0;
$qnt_indicadores=0;

for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $lacunas[$i];
	$qnt++;
	$editar=($podeEditar && permiteEditarLacuna($linha['indicador_lacuna_acesso'],$linha['indicador_lacuna_id']));
	$ver=permiteAcessarLacuna($linha['indicador_lacuna_acesso'],$linha['indicador_lacuna_id']);
	echo '<tr>';
	if ($ver){
		if (!$impressao) echo '<td nowrap="nowrap" width="16">'.($editar ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=lacuna_editar&indicador_lacuna_id='.$linha['indicador_lacuna_id'].'\');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta lacuna de indicador.').dicaF().'</a>' : '').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['indicador_lacuna_cor'].'"><font color="'.melhorCor($linha['indicador_lacuna_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_lacuna($linha['indicador_lacuna_id']).'</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['indicador_lacuna_descricao'] ? $linha['indicador_lacuna_descricao'] : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['indicador_lacuna_responsavel'],'','','esquerda').'</td>';
		if (!isset($detalhe_projeto)) echo '<td nowrap="nowrap" align=center width=16>'.$linha['qnt_marcador'].'</td>';
		echo '</tr>';
		}
	else echo '<tr><td>&nbsp;</td><td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['indicador_lacuna_cor'].'"><font color="'.melhorCor($linha['indicador_lacuna_cor']).'">&nbsp;&nbsp;</font></td><td>'.dica('Sem Permissão','Não tem permissão de ver os dados desta lacuna de indicador.').'<i>'.link_indicador($linha['indicador_lacuna_id'],'','','','',true).'</i>'.dicaF().'</td><td>'.($linha['indicador_lacuna_descricao'] ? $linha['indicador_lacuna_descricao'] : '&nbsp;').'</td><td>&nbsp;</td><td>&nbsp;</td><td nowrap="nowrap">'.link_usuario($linha['indicador_lacuna_responsavel'],'','','esquerda').'</td><td nowrap="nowrap" align=center>'.$linha['qnt_marcador'].'</td></tr>';

	}
if (!count($lacunas)) echo '<tr><td colspan="20"><p>Nenhuma lacuna de indicador encontrada.</p></td></tr>';
echo '</table></td></tr></table>';


if ($impressao) echo '<script language=Javascript>self.print();</script>';





echo estiloFundoCaixa();

?>
<script type="text/JavaScript">

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	frm_filtro.submit();
	}


function imprimir_praticas(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 500, 500, 'm=praticas&a=imprimir_indicadores&dialogo=1&sem_cabecalho=1&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, null, window);
	else window.open('index.php?m=praticas&a=imprimir_indicadores&dialogo=1&sem_cabecalho=1&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'imprimir_indicadores','width=1200, height=600, menubar=1, scrollbars=1');
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
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}



function iluminar_tds(linha,alto,id){
	if(document.getElementsByTagName){
		var tcs=linha.getElementsByTagName('td');
		var nome_celula='';
		if(!id)check=false;
		else{
			var f=eval('document.frm');
			var check=eval('f.selecao_projeto_'+id+'.checked')
			}
		for(var j=0,j_cmp=tcs.length;j<j_cmp;j+=1){
			nome_celula=eval('tcs['+j+'].id');
			if(!(nome_celula.indexOf('ignore_td_')>=0)){
				if(alto==3) tcs[j].style.background='#FFFFCC';
				else if(alto==2||check)
				tcs[j].style.background='#FFCCCC';
				else if(alto==1) tcs[j].style.background='#FFFFCC';
				else tcs[j].style.background='#FFFFFF';
				}
			}
		}
	}


</script>
