<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$salvar = getParam($_REQUEST, 'salvar', 0);
$salvaranexo = getParam($_REQUEST, 'salvaranexo', 0);
$excluiranexo = getParam($_REQUEST, 'excluiranexo', 0);
$pg_arquivos_id = getParam($_REQUEST, 'pg_arquivos_id', 0);

$sql = new BDConsulta;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['gestao_pagina'])) $Aplic->setEstado('gestao_pagina', getParam($_REQUEST, 'gestao_pagina', null));
$gestao_pagina = ($Aplic->getEstado('gestao_pagina') !== null ? $Aplic->getEstado('gestao_pagina') : 'inicial');

if (isset($_REQUEST['editarPG'])) $Aplic->setEstado('editarPG', getParam($_REQUEST, 'editarPG', null));
$editarPG = ($Aplic->getEstado('editarPG') !== null ? $Aplic->getEstado('editarPG') : 0);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

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



$pg_id = getParam($_REQUEST, 'pg_id', 0);

require_once (BASE_DIR.'/modulos/praticas/gestao/gestao.class.php');
$obj = new CGestao();
$obj->load($pg_id);

if (!$podeAcessar || !$pg_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
if ($pg_id &&!permiteAcessarPlanoGestao($obj->pg_acesso, $pg_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$editar=permiteEditarPlanoGestao($obj->pg_acesso, $pg_id);



$usuarios_selecionados=array();
$depts_selecionados=array();
if ($pg_id) {
	$sql->adTabela('plano_gestao_usuario');
	$sql->adCampo('plano_gestao_usuario_usuario');
	$sql->adOnde('plano_gestao_usuario_plano = '.(int)$pg_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('plano_gestao_dept');
	$sql->adCampo('plano_gestao_dept_dept');
	$sql->adOnde('plano_gestao_dept_plano ='.(int)$pg_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}


echo '<form name="env" id="env" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="menu" />';
echo '<input type="hidden" name="u" value="gestao" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="pg_id" value="'.$pg_id.'" />';
echo '<input type="hidden" name="cia_id" id="cia_id" value="'.$obj->pg_cia.'" />';
echo '<input type="hidden" name="sem_cabecalho" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input name="gestao_pagina" type="hidden" value="'.$gestao_pagina.'" />';






if (!$dialogo){
	$Aplic->salvarPosicao();
	
	$botoesTitulo = new CBlocoTitulo('Planejamento Estratégico', 'planogestao.png', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/planogestao_p.png').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';
	$botoesTitulo->mostrar();

	}
	/*
elseif (!$dialogo && !$Aplic->profissional){
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada à esquerda.').'</a></td>'.($Aplic->profissional ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].'.').'</a></td></tr>'.($dept_id ? '<tr id="combo_dept"><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td></tr>' : '') : '').'</tr>';

	$Aplic->salvarPosicao();	
	$botoesTitulo = new CBlocoTitulo('Planejamento Estratégico', 'planogestao.png', $m, $m.'.'.$a);
	
	if ($podeAdicionar) $botoesTitulo->adicionaCelula('<table width="60" cellpadding=0 cellspacing=0><tr><td width="100%">'.botao('criar','Criar '.ucfirst($config['relatorio_gestao']), 'Clique neste botão para criar '.($config['genero_relatorio_gestao']=='a' ? 'uma nova ' : 'um novo ').$config['relatorio_gestao'],'','url_passar(0, \'m=praticas&u=gestao&a=gestao_editar\')').'</td></tr></table>');
	
	if ($podeEditar && $editar){	
		$botoesTitulo->adicionaCelula('<table width="120" cellpadding=0 cellspacing=0><tr><td width="100%">'.botao('editar detalhameto','Editar Detalhameto', 'Clique neste botão para para editar o detalhamento deste planejamento estratégico.','','url_passar(0, \'m=praticas&u=gestao&a=gestao_editar&pg_id='.(int)$pg_id.'\');').'</td></tr></table>');
		if ($editarPG) $botoesTitulo->adicionaCelula('<table width="120" cellpadding=0 cellspacing=0><tr><td width="100%">'.botao('cancelar edição','Cancelar Edição', 'Clique neste botão para cancelar a edição d'.$config['genero_relatorio_gestao'].' '.ucfirst($config['relatorio_gestao']),'','url_passar(0, \'m=praticas&a=menu&u=gestao&editarPG=0&pg_id='.(int)$pg_id.'\');').'</td></tr></table>');
		elseif($pg_id && $podeEditar) $botoesTitulo->adicionaCelula('<table width="60" cellpadding=0 cellspacing=0><tr><td width="100%">'.botao('editar','Editar '.ucfirst($config['relatorio_gestao']), 'Clique neste botão para editar '.$config['genero_relatorio_gestao'].' '.$config['relatorio_gestao'],'','url_passar(0, \'m=praticas&a=menu&u=gestao&editarPG=1&pg_id='.(int)$pg_id.'\');').'</td></tr></table>');
		}
	$botoesTitulo->adicionaCelula('<td nowrap="nowrap" align="right">'.dica('Imprimir '.$config['genero_plano_gestao'].' '.ucfirst($config['plano_gestao']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'.').'<a href="javascript: void(0);" onclick="url_passar(1, \'m='.$m.'&u='.$u.'&a=imprimir&dialogo=1&pg_id='.$pg_id.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	}
*/



$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'planejamento\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();




require "lib/coolcss/CoolControls/CoolTreeView/cooltreeview.php";
	
$arvore = new CoolTreeView("treeview");
$arvore->scriptFolder = "lib/coolcss/CoolControls/CoolTreeView";
$arvore->imageFolder="lib/coolcss/CoolControls/CoolTreeView/icons";
$arvore->styleFolder="default";
$arvore->showLines = true;

$root = $arvore->getRootNode();
$root->image="xpMyDoc.gif";

if ($exibir['perfil']) $arvore->Add("root","orgperfil","Organização-Perfil",true,"","");
if ($exibir['estrutura'] && $exibir['perfil']) $arvore->Add("orgperfil","EstruturaOrganizacional","<a href='javascript:void(0);' onclick='carregar(\"estrutura_organizacional\");'>Estrutura Organizacional</a>",true,"ball_glass_redS.gif","");
if ($exibir['fornecedores'] && $exibir['perfil']) $arvore->Add("orgperfil","FornecedoreseInsumos_geral","<a href='javascript:void(0);' onclick='carregar(\"fornecedores_insumos_geral\");'>Fornecedores e Insumos</a>",true,"ball_glass_redS.gif","");
if ($exibir['fornecedores'] && $exibir['perfil']) $arvore->Add("FornecedoreseInsumos_geral","FornecedoreseInsumos","<a href='javascript:void(0);' onclick='carregar(\"fornecedores_insumos\");'>Lista de Fornecedores e Insumos</a>",true,"square_redS.gif","");
if ($exibir['processos'] && $exibir['perfil']) $arvore->Add("orgperfil","ProcessosProdutosServicos","<a href='javascript:void(0);' onclick='carregar(\"processos_produtos_servicos\");'>Processos e Produtos/Serviços</a>",true,"ball_glass_redS.gif","");
if ($exibir['clientes'] && $exibir['perfil']) $arvore->Add("orgperfil","Clientes","<a href='javascript:void(0);' onclick='carregar(\"clientes\");'>Clientes/Usuários</a>",true,"ball_glass_redS.gif","");
if ($exibir['pessoal'] && $exibir['perfil']) $arvore->Add("orgperfil","QuadrodePessoal_geral","<a href='javascript:void(0);' onclick='carregar(\"quadropessoal_geral\");'>Quadro de Pessoal</a>",true,"ball_glass_redS.gif","");
if ($exibir['pessoal'] && $exibir['perfil']) $arvore->Add("QuadrodePessoal_geral","QuadrodePessoal","<a href='javascript:void(0);' onclick='carregar(\"quadropessoal\");'>Lista de Pessoal</a>",true,"square_redS.gif","");
if ($exibir['programa'] && $exibir['perfil']) $arvore->Add("orgperfil","ProgramaseAcoes","<a href='javascript:void(0);' onclick='carregar(\"programasacoes\");'>Programas e Ações</a>",true,"ball_glass_redS.gif","");
if ($exibir['premiacao'] && $exibir['perfil']) $arvore->Add("orgperfil","PremiacaoemGestao_geral","<a href='javascript:void(0);' onclick='carregar(\"premiacoes_geral\");'>Premiação em Gestão</a>",true,"ball_glass_redS.gif","");
if ($exibir['premiacao'] && $exibir['perfil']) $arvore->Add("PremiacaoemGestao_geral","PremiacaoemGestao","<a href='javascript:void(0);' onclick='carregar(\"premiacoes\");'>Lista de Premiações em Gestão</a>",true,"square_redS.gif","");

$arvore->Add("root","planodegestao",'Planejamento e Gestão',true,"","");	
$arvore->Add("planodegestao","Missao","<a href='javascript:void(0);' onclick='carregar(\"missao\");'>Missão</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("planodegestao","visaofuturo","<a href='javascript:void(0);' onclick='carregar(\"visaofuturo\");'>Visão de Futuro</a>",true,"ball_glass_blueS.gif","");

if($Aplic->checarModulo('praticas', 'acesso', null, 'planejamento_swot')){
	$arvore->Add("planodegestao","DiagEstra","<a href='javascript:void(0);' onclick='carregar(\"swot\");'>Diagnóstico Estratégico (SWOT)</a>",true,"ball_glass_blueS.gif","");
	$arvore->Add("DiagEstra","AmbInterno","<a href='javascript:void(0);' onclick='carregar(\"swot\");'>Ambiente Interno</a>",true,"square_blueS.gif","");
	$arvore->Add("AmbInterno","AmbInternoPF_geral","<a href='javascript:void(0);' onclick='carregar(\"ponto_forte_geral\");'>Forças</a>",true,"triangle_blueS.gif","");
	$arvore->Add("AmbInternoPF_geral","AmbInternoPF","<a href='javascript:void(0);' onclick='carregar(\"ponto_forte\");'>Lista de Forças</a>",true,"losangulo_azul.gif","");
	$arvore->Add("AmbInterno","AmbInternoOIM_geral","<a href='javascript:void(0);' onclick='carregar(\"oportunidade_melhoria_geral\");'>Fraquezas</a>",true,"triangle_blueS.gif","");
	$arvore->Add("AmbInternoOIM_geral","AmbInternoOIM","<a href='javascript:void(0);' onclick='carregar(\"oportunidade_melhoria\");'>Lista de Fraquezas</a>",true,"losangulo_azul.gif","");
	$arvore->Add("DiagEstra","AmbExterno","<a href='javascript:void(0);' onclick='carregar(\"swot\");'>Ambiente Externo</a>",true,"square_blueS.gif","");
	$arvore->Add("AmbExterno","AmbExternoOport_geral","<a href='javascript:void(0);' onclick='carregar(\"oportunidades_geral\");'>Oportunidades</a>",true,"triangle_blueS.gif","");
	$arvore->Add("AmbExternoOport_geral","AmbExternoOport","<a href='javascript:void(0);' onclick='carregar(\"oportunidades\");'>Lista de Oportunidades</a>",true,"losangulo_azul.gif","");
	$arvore->Add("AmbExterno","AmbExternoAmeaca_geral","<a href='javascript:void(0);' onclick='carregar(\"ameacas_geral\");'>Ameaças</a>",true,"triangle_blueS.gif","");
	$arvore->Add("AmbExternoAmeaca_geral","AmbExternoAmeaca","<a href='javascript:void(0);' onclick='carregar(\"ameacas\");'>Lista de Ameaças</a>",true,"losangulo_azul.gif","");
	}
$arvore->Add("planodegestao","princCrenVal","<a href='javascript:void(0);' onclick='carregar(\"principios\");'>Princípios, Crenças e Valores</a>",true,"ball_glass_blueS.gif","");

$arvore->Add("planodegestao","dirEscSup_geral","<a href='javascript:void(0);' onclick='carregar(\"diretrizes_superiores_geral\");'>Diretrizes do Escalão Superior</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("dirEscSup_geral","dirEscSup","<a href='javascript:void(0);' onclick='carregar(\"diretrizes_superiores\");'>Lista de Diretrizes do Escalão Superior</a>",true,"square_blueS.gif","");


$arvore->Add("planodegestao","dirCmt_geral","<a href='javascript:void(0);' onclick='carregar(\"diretrizes_geral\");'>Diretrizes Internas</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("dirCmt_geral","dirCmt","<a href='javascript:void(0);' onclick='carregar(\"diretrizes\");'>Lista de Diretrizes Internas</a>",true,"square_blueS.gif","");

$arvore->Add("planodegestao","perspectiva","<a href='javascript:void(0);' onclick='carregar(\"perspectivas\");'>".ucfirst($config['perspectivas'])."</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("planodegestao","tema","<a href='javascript:void(0);' onclick='carregar(\"temas\");'>".ucfirst($config['temas'])."</a>",true,"ball_glass_blueS.gif","");

$arvore->Add("planodegestao","objEstOrg_geral","<a href='javascript:void(0);' onclick='carregar(\"objetivos_estrategicos_geral\");'>".ucfirst($config['objetivos'])."</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("objEstOrg_geral","objEstOrg","<a href='javascript:void(0);' onclick='carregar(\"objetivos_estrategicos\");'>Lista de ".ucfirst($config['objetivos'])."</a>",true,"square_blueS.gif","");

if($Aplic->profissional && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'acesso', null, 'me')){
	$arvore->Add("planodegestao","me","<a href='javascript:void(0);' onclick='carregar(\"mes_pro\");'>".ucfirst($config['mes'])."</a>",true,"ball_glass_blueS.gif","");
	}
if(!$Aplic->profissional || ($Aplic->profissional && $Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'acesso', null, 'fator'))){
	$arvore->Add("planodegestao","fatCritSuc_geral","<a href='javascript:void(0);' onclick='carregar(\"fatores_criticos_geral\");'>".ucfirst($config['fatores'])."</a>",true,"ball_glass_blueS.gif","");
	$arvore->Add("fatCritSuc_geral","fatCritSuc","<a href='javascript:void(0);' onclick='carregar(\"fatores_criticos\");'>Lista de ".ucfirst($config['fatores'])."</a>",true,"square_blueS.gif","");
	}
$arvore->Add("planodegestao","estrat_geral","<a href='javascript:void(0);' onclick='carregar(\"estrategias_geral\");'>".ucfirst($config['iniciativas'])."</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("estrat_geral","estrat","<a href='javascript:void(0);' onclick='carregar(\"estrategias\");'>Lista de ".ucfirst($config['iniciativas'])."</a>",true,"square_blueS.gif","");

$arvore->Add("planodegestao","metasOrg_geral","<a href='javascript:void(0);' onclick='carregar(\"metas_geral\");'>".ucfirst($config['metas'])."</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("metasOrg_geral","metasOrg","<a href='javascript:void(0);' onclick='carregar(\"metas\");'>Lista de ".ucfirst($config['metas'])."</a>",true,"square_blueS.gif","");





echo estiloTopoCaixa();


if (!$dialogo){	

	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista_pg",dica('Lista de Planejamentos Estratégicos','Visualizar a lista de todos os planejamentos estratégicos.').'Lista de Planejamentos Estratégicos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_lista\");");

	if ($podeEditar && $editar) {
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_pg",dica('Novo Planejamento Estratégico', 'Criar um novo planejamento estratégico.').'Novo Planejamento Estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_editar\");");
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($podeEditar && $editar) {
		$km->Add("acao","acao_editar",dica('Editar Detalhameto','Clique neste ícone '.imagem('editar.gif').' para editar o detalhamento deste planejamento estratégico.').imagem('editar.gif').'Editar Detalhameto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_editar&pg_id=".(int)$pg_id."\");");
		if ($editarPG) $km->Add("acao","acao_editar",dica('Cancelar Edição de Tópicos','Clique neste ícone '.imagem('planogestao_cancelar_editar.png').' para cancelar a Edição dos tópicos deste planejamento estratégico.').imagem('planogestao_cancelar_editar.png').' Cancelar Edição de Tópicos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=menu&u=gestao&editarPG=0&pg_id=".(int)$pg_id."\");");
		elseif($pg_id && $podeEditar) $km->Add("acao","acao_editar",dica('Editar Tópicos','Clique neste ícone '.imagem('planogestao_editar.png').' para editar os tópicos deste planejamento estratégico.').imagem('planogestao_editar.png').' Editar Tópicos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=menu&u=gestao&editarPG=1&pg_id=".(int)$pg_id."\");");
		if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir','Clique neste ícone '.imagem('remover.png').' para excluir o planejamento estratégico do sistema.').imagem('remover.png').' Excluir Planejamento Estratégico'.dicaF(), "javascript: void(0);' onclick='excluir()");
		}
	//$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	//$km->Add("acao_imprimir","acao_imprimir1",dica(ucfirst($config['relatorio_gestao']), 'Imprimir '.$config['genero_relatorio_gestao'].' '.$config['relatorio_gestao']).' '.ucfirst($config['relatorio_gestao']).dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&u=".$u."&a=imprimir&dialogo=1&pg_id=".$pg_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}



echo '<table id="tblPraticas" border=0 cellpadding=0 cellspacing=0 width="100%" class="std">';



echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'detalhamento\').style.display) document.getElementById(\'detalhamento\').style.display=\'\'; else document.getElementById(\'detalhamento\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Detalhamento</b></a></td></tr>';
echo '<tr id="detalhamento" style="display:none"><td colspan=20><table width="100%" cellspacing=1 cellpadding=0>';



echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome', 'Neste campo consta um nome para identificação.').'Nome:'.dicaF().'</td><td align="left" class="realce">'.$obj->pg_nome.'</td></tr>';
if ($obj->pg_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável pelo plano de gestão.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->pg_cia).'</td></tr>';


if ($Aplic->profissional){
	$sql->adTabela('plano_gestao_cia');
	$sql->adCampo('plano_gestao_cia_cia');
	$sql->adOnde('plano_gestao_cia_plano = '.(int)$pg_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();	
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= '<table cellpadding=0 cellspacing=0 width=100%>';
		$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		$saida_cias.= '</td></tr></table>';
		}
	if ($saida_cias) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}





if ($obj->pg_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->pg_dept).'</td></tr>';
$saida_depts='';
if ($depts_selecionados && count($depts_selecionados)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($depts_selecionados[0]);
		$qnt_lista_depts=count($depts_selecionados);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Qual '.strtolower($config['departamento']).' está envolvid'.$config['genero_dept'].' com este link.').ucfirst($config['departamento']).' envolvid'.$config['genero_dept'].':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';
if ($obj->pg_usuario) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável', ucfirst($config['usuario']).' responsável por gerenciar.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->pg_usuario, '','','esquerda').'</td></tr>';		
$saida_quem='';
if ($usuarios_selecionados && count($usuarios_selecionados)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($usuarios_selecionados[0], '','','esquerda');
		$qnt_usuarios_selecionados=count($usuarios_selecionados);
		if ($qnt_usuarios_selecionados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_usuarios_selecionados; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i], '','','esquerda').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais usuarios_selecionados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'usuarios_selecionados\');">(+'.($qnt_usuarios_selecionados - 1).')</a>'.dicaF(). '<span style="display: none" id="usuarios_selecionados"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';
$link_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'Pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designado podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável os designados podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" class="realce">'.$link_acesso[$obj->pg_acesso].'</td></tr>';
if ($obj->pg_descricao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição', 'Um texto explicativo para facilitar a compreensão e facilitar futuras pesquisas.').'Descrição:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->pg_descricao.'</td>';


echo '<tr><td nowrap="nowrap" align="right">'.dica('Data Inicial', 'Digite ou escolha no calendário a data de início.').'De:'.dicaF().'</td><td align="left" class="realce">'.retorna_data($obj->pg_inicio, false).'</td></tr>';
echo '<tr><td nowrap="nowrap" align="right">'.dica('Data Final', 'Digite ou escolha no calendário a data final.').'Até:'.dicaF().'</td><td align="left" class="realce">'.retorna_data($obj->pg_fim, false).'</td></tr>';




echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativo', 'Se o planejamento estratégico se encontra ativo.').'Ativo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->pg_ativo ? 'Sim' : 'Não').'</td></tr>';


echo '</table></td></tr>';




echo '<tr>';
echo '<td style="background:#FFFFFF;" valign="top"><div style="padding:10px;">'.$arvore->Render().'</div></td>';
echo '<td width="100%" valign="top">';

include_once BASE_DIR.'/modulos/praticas/gestao/'.$gestao_pagina.'.php';

echo '</td></tr></table>';
echo '</form>';
echo estiloFundoCaixa();
?>
<script type="text/javascript">

function pg_download(pg_arquivos_id){
	url_passar(0, 'm=praticas&a=download_arquivo&sem_cabecalho=1&pg_arquivos_id='+pg_arquivos_id);
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir o planejamento estratégico?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fazer_gestao_aed';
		f.submit();
		}
	}

function nova_gestao(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Criar Planejamento Estratégico', 700, 400, 'm=praticas&u=gestao&a=criar_pg&dialogo=1', window.criar_pg, window);
	else window.open('?m=praticas&u=gestao&a=criar_pg&dialogo=1', 'Criar Planejamento Estratégico', 'width=600, height=400, left=0, top=0, scrollbars=no, resizable=no');
	}


function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	env.submit();
	}



function criar_pg(pg_id){
	env.pg_id.value=pg_id;	
	env.submit();
	}
	
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	<?php if ($Aplic->profissional){ ?>
	env.dept_id.value=null;
	document.getElementById('combo_dept').style.display='none';
	<?php } ?>
	}	
	
function carregar(pagina){
	env.gestao_pagina.value=pagina;
	env.submit();
	}	
	

treeview.expandAll();
	
	
</script> 
	
