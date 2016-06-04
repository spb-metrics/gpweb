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

if (!$dialogo) $Aplic->salvarPosicao();
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$paises = getPais('Paises');
$filtro_adicional = '';

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;

if (isset($_REQUEST['onde'])) $Aplic->setEstado('IdxFiltroContato', getParam($_REQUEST, 'onde', null));

if (isset($_REQUEST['estado_sigla'])) $Aplic->setEstado('estado_sigla', getParam($_REQUEST, 'estado_sigla', null));
$estado_sigla = ($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : '');

if (isset($_REQUEST['municipio_id'])) $Aplic->setEstado('municipio_id', getParam($_REQUEST, 'municipio_id', null));
$municipio_id = ($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '');

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));


if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : '');

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



$sql = new BDConsulta;

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();

if (isset($_REQUEST['procurar_string']) && $_REQUEST['procurar_string']) {
	$Aplic->setEstado('IdxFiltroContato', '%'.getParam($_REQUEST, 'procurar_string', null));
	$filtro_adicional = 'OR contato_posto like \'%'.getParam($_REQUEST, 'procurar_string', null).'%\' OR contato_nomeguerra  like \'%'.getParam($_REQUEST, 'procurar_string', null).'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra)  like \'%'.getParam($_REQUEST, 'procurar_string', null).'%\' OR cia_nome like \'%'.getParam($_REQUEST, 'procurar_string', null).'%\' OR contato_notas  like \'%'.getParam($_REQUEST, 'procurar_string', null).'%\' OR contato_email like \'%'.getParam($_REQUEST, 'procurar_string', null).'%\'';
	}
elseif(isset($_REQUEST['procurar_string'])) $Aplic->setEstado('IdxFiltroContato', '');

$onde = $Aplic->getEstado('IdxFiltroContato') ? $Aplic->getEstado('IdxFiltroContato') : '';
$default_procurar_string = $onde;


$ordenarPor = 'contato_posto';
$dias_para_atualizacao = 5;

$let = ":";
$pesquisa_mapa = array($ordenarPor, 'contato_posto', 'contato_nomeguerra');
foreach ($pesquisa_mapa as $pesquisa_nome) {
	$sql->adTabela('contatos');
	$sql->adCampo('DISTINCT UPPER(SUBSTRING('.$pesquisa_nome.',1,1)) as L');
	$sql->adOnde('contato_privado=0 OR contato_privado IS NULL OR (contato_privado=1 AND contato_dono='.$Aplic->usuario_id.') OR contato_dono IS NULL');
	if ($usuario_id) $sql->adOnde('contato_dono='.$usuario_id);

	if ($lista_cias && !$usuario_id) $sql->adOnde('contato_cia IN ('.$lista_cias.')');
	else if ($cia_id && !$usuario_id) $sql->adOnde('contato_cia='.(int)$cia_id);
	else if (!$usuario_id) $sql->adOnde('contato_cia IS NULL');
	if ($lista_depts)  $sql->adOnde('contato_dept IN ('.$lista_depts.')');
	else if ($dept_id) $sql->adOnde('contato_dept ='.(int)$dept_id);
	
	$arr = $sql->Lista();
	foreach ($arr as $L) $let .= @$L['L'];   //EUZ @ para esconder erro
	}
$sql->limpar();

$mostrarcampos = array('contato_funcao' => 'contato_funcao', 'contato_identidade' => 'contato_identidade', 'contato_cpf' => 'contato_cpf', 'contato_cnpj' => 'contato_cnpj', 'contato_endereco1' => 'contato_endereco1', 'contato_endereco2' => 'contato_endereco2', 'municipio_nome' => 'municipio_nome', 'contato_estado' => 'contato_estado', 'contato_cep' => 'contato_cep', 'contato_pais' => 'contato_pais', 'contato_cia' => 'contato_cia', 'cia_nome' => 'cia_nome', 'dept_nome' => 'dept_nome', 'contato_dddtel' => 'contato_dddtel', 'contato_tel' => 'contato_tel', 'contato_dddtel2' => 'contato_dddtel2', 'contato_tel2' => 'contato_tel2', 'contato_dddcel' => 'contato_dddcel', 'contato_cel' => 'contato_cel', 'contato_dddfax' => 'contato_dddfax', 'contato_fax' => 'contato_fax', 'contato_email' => 'contato_email', 'contato_jabber' => 'contato_jabber', 'contato_icq' => 'contato_icq', 'contato_msn' => 'contato_msn', 'contato_yahoo' => 'contato_yahoo', 'contato_skype' => 'contato_skype', 'contato_hora_custo' => 'contato_hora_custo');
$sql->adTabela('contatos', 'a');
$sql->esqUnir('cias', 'b', 'a.contato_cia = b.cia_id');
$sql->esqUnir('depts', 'depts', 'contato_dept = dept_id');
$sql->esqUnir('usuarios', 'usuarios', 'contato_id = usuario_contato');
$sql->esqUnir('municipios', 'municipios', 'municipio_id = CAST( contato_cidade as '.( config('tipoBd')=='mysql' ? 'UNSIGNED ' : '' ).' INTEGER)');
$sql->adCampo('municipio_nome, contato_id, contato_ordem, usuario_id, contato_posto, contato_nomeguerra, contato_tel, contato_arma, contato_cnpj, contato_cpf, contato_identidade, usuario_ativo, contato_hora_custo');
$sql->adCampo('contato_chave_atualizacao, contato_pedido_atualizacao, contato_ultima_atualizacao, contato_dono');
$sql->adCampo($mostrarcampos);
if ($onde || $filtro_adicional) $sql->adOnde('(contato_posto LIKE \'%'.$onde.'%\' OR contato_nomeguerra LIKE \'%'.$onde.'%\' '.$filtro_adicional.')');
$sql->adOnde('(contato_privado=0 OR contato_privado IS NULL OR (contato_privado=1 AND contato_dono='.$Aplic->usuario_id.')	OR contato_dono IS NULL)');
$sql->adOnde('usuario_ativo IS NULL OR usuario_ativo = 1');
if ($usuario_id) $sql->adOnde('contato_dono='.$usuario_id);
if ($lista_cias && !$usuario_id) $sql->adOnde('contato_cia IN ('.$lista_cias.')');
else if ($cia_id && !$usuario_id) $sql->adOnde('contato_cia='.(int)$cia_id);
else if (!$usuario_id) $sql->adOnde('contato_cia IS NULL');
if ($lista_depts)  $sql->adOnde('contato_dept IN ('.$lista_depts.')');
else if ($dept_id) $sql->adOnde('contato_dept ='.(int)$dept_id);
if ($estado_sigla) $sql->adOnde('contato_estado = \''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('contato_cidade = \''.$municipio_id.'\'');
$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
$carrLargura = 4;
$linhas = $sql->Lista();
$sql->limpar();

$alfabeto = '<tr><td align="right">'.dica('Mostrar', 'Selecione à direita por qual letra deseja filtrar '.$config['genero_contato'].'s '.$config['contatos'].'. Serão pesquisados pelos nomes de guerra e postos/graduação.').'Mostrar:'.dicaF().' </td>';
$alfabeto .= '<td colspan=20><table cellpadding=1 cellspacing=0 border=0><tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&onde=0\');">'.dica('Mostrar Todos', 'Mostrar tod'.$config['genero_contato'].'s '.$config['genero_contato'].'s '.$config['contatos'].'.').'Todos'.dicaF().'</a></td>';
for ($c = 65; $c < 91; $c++) {
	$cu = chr($c);
	$cell = strpos($let, $cu) > 0 ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&onde='.$cu.'\');">'.dica('Filtrar pela Letra '.$cu, 'Mostrar '.$config['genero_contato'].'s '.$config['contatos'].' em que o nome de guerra ou posto/gradução começem com a letra '.$cu.'.').$cu.dicaF().'</a>' : '<font color="#999999">'.$cu.'</font>';
	$alfabeto .= '<td>'.$cell.'</td>';
	}
$alfabeto .= '</tr></table></td></tr>';


$procurar_usuario='<tr><td align="right">'.dica('Responsável pel'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').'Responsável:'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
$procurar_estado='<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado d'.$config['genero_contato'].' '.$config['contato'].'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:250px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
$procurar_municipio='<tr><td align="right">'.dica('Município', 'Selecione o município d'.$config['genero_contato'].' '.$config['contato'].'.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax( $estado_sigla, 'municipio_id', 'class="texto" style="width:250px;"', '', $municipio_id, true, false).'</div></td></tr>';
$pesquisar='<tr><td align="right">'.dica('Pesquisar', 'Pesquisar contatos que correspondam ao texto da caixa de pesquisa.').'Pesquisar:'.dicaF().'</td><td><input class="texto" type="text" name="procurar_string" value="'.$default_procurar_string.'" onchange="document.filtro.submit();" style="width:250px;" /></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&procurar_string=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Pressione este botão para limpar a pesquisa de contatos por palavra chave').'</a></td></tr>';

echo '<form name="frm_filtro" id="frm_filtro" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';

if (!$dialogo && $Aplic->profissional){

	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['contatos']), 'contatos.png', $m, $m.'.'.$a);

	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"', '&nbsp;').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('contatos_p.png').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	$novo=($podeAdicionar ? '<tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Adicionar um nov'.$config['genero_contato'].' '.$config['contato'].'.').'<a href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=contatos&a=editar\');" >'.imagem('contatos_novo.png').'</a>'.dicaF().'</td></tr>' : '');
	$imprimir='<tr><td>'.dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir '.$config['genero_tarefa'].'s '.$config['tarefas'].' e ata para este mês.').'<a href="javascript: void(0);" onclick ="url_passar(1,\'m=contatos&a=index&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';
	$filtrar='<tr><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td></tr>';


	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$procurar_estado.$procurar_municipio.$pesquisar.$alfabeto.'</table></td><td><table cellspacing=0 cellpadding=0>'.$filtrar.$novo.$imprimir.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	}
elseif (!$dialogo && !$Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['contatos']), 'contatos.png', $m, $m.'.'.$a);

	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"','&nbsp;').'</div></td></tr>';


	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$alfabeto.$procurar_om.$procurar_usuario.'</table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_estado.$procurar_municipio.$pesquisar.'</table>');
	$botoesTitulo->adicionaCelula('<table><tr><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelos campos escolhidos à esquerda.').'</a></td></tr></table>');
	if ($podeEditar) {
		$botoesTitulo->adicionaBotao('m=contatos&a=csv_exportar&sem_cabecalho=1', 'exportar CSV','','Exportar CSV','Exportar a lista de contatos no formato CSV.<br><br>CSV (comma-separated values) é uma arquivo texto que armazena dados tabelados separados por vírgula. O formato CSV é bastante simples e suportado por quase todas as planilhas eletrônicas e SGDB disponíveis no mercado.');
		$botoesTitulo->adicionaBotao('m=contatos&a=vcard_importa&dialogo=0', 'importar vCard','','Importar vCard','Importar uma lista de contato no formato vCard.<br><br>O vCard (Versitcard) é um padrão mundialmente utilizado para exportação de contatos, particularmente utilizado pelo Microsoft Outlook.');
		}
	if ($podeEditar) $botoesTitulo->adicionaCelula('<table width=90><td style="width:90px;">'.dica('Nov'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Adicionar um nov'.$config['genero_contato'].' '.$config['contato'].'.'). '<a class="botao" href="javascript: void(0);" onclick="javascript:url_passar(0, \'m=contatos&a=editar\');"><span>nov'.$config['genero_contato'].'</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->mostrar();
	}

echo '</form>';
if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	}


echo '<form method="POST" name="modProjetos">';
echo '<input type="hidden" name="m" value="projetos" />';
echo '<input type="hidden" name="a" value="ver" />';
echo '<input type="hidden" name="projeto_id" />';
echo '</form>';

echo '<form method="POST" name="modTarefas">';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input type="hidden" name="a" value="ver" />';
echo '<input type="hidden" name="tarefa_id" />';
echo '</form>';


$col=0;

echo estiloTopoCaixa();

$pagina = getParam($_REQUEST, 'pagina', 1);
$xpg_tamanhoPagina = $config['qnt_contatos'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1);
$xpg_totalregistros = ($linhas ? count($linhas) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'contato', 'contatos', 'class="std2"');

echo '<table width="100%" border=0 cellpadding="1" cellspacing=0 class="std2">';
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$carr = $linhas[$i];
		if ($col==$carrLargura) {
			echo '</tr>';
			$col=0;
			}
		if ($col==0) echo '<tr>';
		$col++;

		echo '<td valign=top>';
		echo '<table width="100%" height="140" cellspacing=0 cellpadding=0 class="tbl4" style="background: #ffffff" valign=top><tr><td valign=top><table width="100%" cellspacing=0 cellpadding=0 style="background: #ffffff">';
		echo '<tr><td><table width="100%" cellspacing=0 cellpadding=0 valign=top><tr>';
    $contatoid = $carr['contato_id'];
		echo '<td style="text-align:left" nowrap="nowrap" >'.dica('Ver Detalhes d'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Clique no nome para visualizar os detalhes de '.$carr['contato_posto'].' '.$carr['contato_nomeguerra']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=ver&contato_id='.$contatoid.'\');"><b>'.$carr['contato_posto'].' '.$carr['contato_nomeguerra'].($carr['contato_arma'] && $config['militar'] < 10 ? ' - '.$carr['contato_arma'] : '').'</b></a>'.dicaF().'</td>';
		echo '<td style="text-align:right" nowrap="nowrap">';
		$contato = new CContato();
		$usuario_id = $contato->ehUsuario($contatoid);
    if ($usuario_id ) echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=ver_usuario&usuario_id='.$usuario_id .'\');">'.imagem('icones/usuario_mini.png', 'Detalhes d'.$config['genero_usuario'].' '.ucfirst($config['usuario']), 'Este contato também é '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].', clique neste ícone '.imagem('icones/usuario_mini.png').' para ver seus detalhes.').'</a>';
		echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=vcard_exporta&sem_cabecalho=true&contato_id='.$contatoid.'\');" >'.imagem('icones/cartao.png', 'Exportar Dados', 'Clique neste ícone '.imagem('icones/cartao.png').' para exportar o vCard deste contato.').'</a>';


		$sql->adTabela('projeto_contatos');
		$sql->adCampo('count(projeto_id)');
		$sql->adOnde('contato_id ='.$contatoid);
		$projetos_contato = $sql->carregarLinha();
		$sql->limpar();


		$sql->adTabela('tarefa_contatos');
		$sql->adCampo('count(tarefa_id)');
		$sql->adOnde('contato_id ='.$contatoid);
		$tarefas_contato = $sql->carregarLinha();
		$sql->limpar();

		if ($projetos_contato[0] > 0)	echo '<a href="" onclick="	window.open(\'./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=goProjeto&aceita_portfolio=1&tabela=projetos&usuario_id='.$contatoid.'\', \'selecionar\', \'left=0,top=0,height=600,width=400, scrollbars=yes, resizable=yes\');return false;">'.imagem('icones/projeto_mini.png', 'Contato de '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('icones/projeto_mini.png').' para ver de quais '.$config['projetos'].' '.$carr['contato_posto'].' '.$carr['contato_nomeguerra'].' é contato').'</a>';
		if ($tarefas_contato[0] > 0)	echo '<a href="" onclick="	window.open(\'./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=irTarefa&tabela=tarefas&usuario_id='.$contatoid.'\', \'selecionar\', \'left=0,top=0,height=600,width=400, scrollbars=yes, resizable=yes\');return false;">'.imagem('icones/tarefa_mini.png', 'Contato de '.ucfirst($config['tarefa']), 'Clique neste ícone '.imagem('icones/tarefa_mini.png').' para ver de quais '.$config['tarefas'].' '.$carr['contato_posto'].' '.$carr['contato_nomeguerra'].' é contato').'</a>';
		if ($carr['contato_pedido_atualizacao'] && (!$carr['contato_ultima_atualizacao'] || $carr['contato_ultima_atualizacao'] == 0) && $carr['contato_chave_atualizacao']) {
			$ultimo_pedido = new CData($carr['contato_pedido_atualizacao']);
			$df = '%d/%m/%Y';
			$tf = $Aplic->getPref('formatohora');
			echo imagem('icones/informacao.gif', 'Aguardando Atualização', 'Aguardando pela atualização d'.$config['genero_contato'].' '.$config['contato'].'.<br><br>(Pedido em: '.$ultimo_pedido->format($df.' '.$tf).')');
			}
		elseif ($carr['contato_pedido_atualizacao'] && (!$carr['contato_ultima_atualizacao'] || $carr['contato_ultima_atualizacao'] == 0) && !$carr['contato_chave_atualizacao']) {
			$ultimo_pedido = new CData($carr['contato_pedido_atualizacao']);
			$df = '%d/%m/%Y';
			$tf = $Aplic->getPref('formatohora');
			echo imagem('icones/log-error.gif','Não Atualizou', 'Espera muito longa pela atualização d'.$config['genero_contato'].' '.$config['contato'].'!(Pedir  '.$ultimo_pedido->format($df.' '.$tf).')');
			}
		elseif ($carr['contato_ultima_atualizacao'] && !$carr['contato_chave_atualizacao']) {
			$ultimo_pedido = new CData($carr['contato_ultima_atualizacao']);
			$df = '%d/%m/%Y';
			$df .= ' '.$Aplic->getPref('formatohora');
			echo imagem('icones/ok.gif','Atualização d'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Atualizou em: '.$ultimo_pedido->format($df));
			}

		if ($carr['contato_dono']==$Aplic->usuario_id || $Aplic->usuario_super_admin || $carr['usuario_id']==$Aplic->usuario_id) echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=editar&contato_id='.$contatoid.'\');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar este contato').'</a>';
		echo '</td></tr></table></td></tr>';
		reset($mostrarcampos);
		while (list($chave, $val) = each($mostrarcampos)) {
			if (strlen($carr[$chave]) > 0) {
				if ($val == 'contato_identidade') echo '<tr><td><b>Identidade: </b>'.$carr[$chave].'</td></tr>';
				elseif ($val == 'contato_cpf') echo '<tr><td><b>CPF: </b>'.$carr[$chave].'</td></tr>';
				elseif ($val == 'contato_hora_custo' && $carr[$chave] > 0 && $Aplic->checarModulo('usuarios', 'acesso', $Aplic->usuario_id, 'hora_custo')) echo '<tr><td><b>Custo hora: </b>'.$config["simbolo_moeda"].' '.number_format($carr[$chave], 2, ',', '.').'</td></tr>';
				elseif ($val == 'contato_hora_custo' && $carr[$chave]==0);
				elseif ($val == 'contato_funcao') echo '<tr><td><b>Função: </b>'. $carr[$chave].'</td></tr>';
				elseif ($val == 'contato_cnpj') echo '<tr><td><b>CNPJ: </b>'. $carr[$chave].'</td></tr>';
				elseif ($val == 'contato_email') echo'<tr><td>'.link_email($carr[$chave],$carr['contato_id']).'</td></tr>';
				elseif ($val == 'contato_cia' && is_numeric($carr[$chave])); //não faz nada
				elseif ($val == 'contato_estado' || $val == 'contato_cep' || $val == 'contato_dddtel' || $val == 'contato_dddtel2' || $val == 'contato_dddcel' || $val == 'contato_dddfax' ); //não faz nada
				elseif ($val == 'municipio_nome') echo '<tr><td>'.$carr[$chave].( $carr['contato_estado'] ? ' - '.$carr['contato_estado'] : '').'</td></tr>';
				elseif ($val == 'cia_nome') echo '<tr><td><b>'.ucfirst($config['organizacao']).': </b>'.$carr[$chave].'</td></tr>';
				elseif ($val == 'dept_nome') echo '<tr><td><b>'.$config['dept'].': </b>'.$carr[$chave].'</td></tr>';
				elseif ($val == 'contato_tel') echo '<tr><td><b>Tel Trab: </b>' .($carr['contato_dddtel'] ? '('.$carr['contato_dddtel'].') ' : ''). $carr[$chave].'</td></tr>';
				elseif ($val == 'contato_tel2')	echo '<tr><td><b>Tel Res: </b>'.($carr['contato_dddtel2'] ? '('.$carr['contato_dddtel2'].') ' : '').$carr[$chave].'</td></tr>';
				elseif ($val == 'contato_cel') echo '<tr><td><b>Cel: </b>'.($carr['contato_dddcel'] ? '('.$carr['contato_dddcel'].') ' : '').$carr[$chave].'</td></tr>';
				elseif ($val == 'contato_fax') echo '<tr><td><b>Fax: </b>'.($carr['contato_dddfax'] ? '('.$carr['contato_dddfax'].') ' : '').$carr[$chave].'</td></tr>';
				elseif ($val == 'contato_icq') echo '<tr><td><b>ICQ: </b><a href="http://web.icq.com/whitepages/message_me?uin="'. $carr[$chave].'"&action=message">'. $carr[$chave].'</a></td></tr>';
				elseif ($val == 'contato_msn') echo '<tr><td><b>MSN: </b><a href="msnim:chat?contact='. $carr[$chave].'">'. $carr[$chave].'</a></td></tr>';
				elseif ($val == 'contato_yahoo') echo '<tr><td><b>Yahoo: </b><a href="ymsgr:sendim?'. $carr[$chave].'">'. $carr[$chave].'</a></td></tr>';
				elseif ($val == 'contato_skype') echo '<tr><td><b>Skype: </b><a href="skype:'. $carr[$chave].'?call" class="realce">'. $carr[$chave].'</a></td></tr>';
				elseif ($val == 'contato_jabber') echo '<tr><td><b>Jabber: </b>'. $carr[$chave].'</td></tr>';
				elseif ($val == 'contato_pais' && $carr[$chave]) echo '<tr><td>' .($carr['contato_cep'] ? $carr['contato_cep'].' - ' : '').($paises[$carr[$chave]] ? $paises[$carr[$chave]] : $carr[$chave]).'</td></tr>';
				else echo '<tr><td>'.$carr[$chave].'<br /></td></tr>';
				}
			}
		echo '</table></td></tr></table></td>';
		}
if (!count($linhas)) echo '</tr><tr><td colspan=20>Não foram encontrad'.$config['genero_contato'].'s '.$config['contatos'].'</td>';
echo '</tr></table>';

echo estiloFundoCaixa();

?>
<script language="javascript">

function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:250px;"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>));
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('usuario_id').value=usuario_id;
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		document.frm_filtro.submit();
		}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	frm_filtro.submit();
	}

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function mudar_om(){
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"','&nbsp;');
	}

function mudar_usuario(){
	var cia_id=document.getElementById('cia_id').value;
	var usuario_id=document.getElementById('usuario_id').value;
	xajax_mudar_usuario_ajax(cia_id, usuario_id, 'usuario_id','combo_usuario', 'class="texto" size=1 style="width:250px;" onchange="escolheu_usuario();"');
	}

function escolheu_usuario(){
	document.frm_filtro.cia_id.value=document.frm_filtro.cia_id.value;
	}

function goProjeto( chave, val ) {
	var f = document.modProjetos;
	if (val != '') {
		f.projeto_id.value = chave;
		f.submit();
    }
	}

function irTarefa( chave, val ) {
	var f = document.modTarefas;
	if (val != '') {
		f.tarefa_id.value = chave;
		f.submit();
    }
	}
</script>
