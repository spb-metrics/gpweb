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

$ajax=(file_exists(BASE_DIR.'/modulos/'.$m.'/'.($u ? $u.'/' : '').$a.'_ajax.php')? 1 : 0);
if($ajax) include BASE_DIR.'/modulos/'.$m.'/'.($u ? $u.'/' : '').$a.'_ajax.php';


echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
echo '<html>';
echo '<head>';
echo '<meta name="Description" content="gpweb Default Style">';
echo '<meta name="google" value="notranslate">';
echo '<meta http-equiv="Content-Type" content="text/html; charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'">';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
$imprimir=getParam($_REQUEST, 'imprimir', '');
echo '<link rel="stylesheet" type="text/css" href="./estilo/rondon/'.($imprimir ? 'imprimir_' : 'estilo_').$config['estilo_css'].'.css" media="all">';
echo '<style type="text/css" media="all">@import "./estilo/rondon/'.($imprimir  ? 'imprimir_': 'estilo_').$config['estilo_css'].'.css";</style>';
if(!$Aplic->profissional){
    echo '<link rel="shortcut icon" href="./estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico">';
    }

if($ajax) {
	//por o script ignorar variaveis M e A via post, necessário forçar o envio
	$enderecoURI=BASE_URL.'/index.php?m='.$m.'&a='.$a.($u? '&u='.$u : '');
	$xajax->printJavascript(BASE_URL.'/lib/xajax');
	}

$Aplic->carregarCabecalhoJS();

echo '</head><body onload="this.focus();">';
echo '<script>$jq = jQuery.noConflict();</script>';

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

if ($Aplic->chave_criada){
	@unlink($base_dir.'/arquivos/temp/'.$Aplic->chave_criada.'.key');
	@unlink($base_dir.'/arquivos/temp/'.$Aplic->chave_criada.'.crt');
	}

$msg_saud = date("H");

if($msg_saud >= 0 && $msg_saud < 6)$msg_ini_saud = 'Boa madrugada ';
else if($msg_saud >= 6 && $msg_saud < 12) $msg_ini_saud = 'Bom dia ';
else if($msg_saud >= 12 && $msg_saud < 18) $msg_ini_saud = 'Boa tarde ';
else $msg_ini_saud = 'Boa noite ';

$final_saudacao=($Aplic->usuario_lista_grupo && $Aplic->usuario_lista_grupo!=$Aplic->usuario_id ? imagem('icones/membros_p.png', 'Conta de Grupo', 'Ao menos uma conta de grupo está ativada.'): '');
$q = new BDConsulta;

if(!$Aplic->profissional){
  echo '<table width="100%" cellpadding=0 cellspacing=0 border=0>';

	$podeAcessar_email=$Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso');
	$podeAcessar_contatos=$Aplic->modulo_ativo('contatos') && $Aplic->checarModulo('contatos', 'acesso');
	$podeAcessar_parafazer=$Aplic->modulo_ativo('parafazer') && $Aplic->checarModulo('parafazer', 'acesso');
	$podeAcessar_foruns=$Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso');
	$podeAcessar_arquivos=$Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso');
	$podeAcessar_pesquisa=$Aplic->modulo_ativo('pesquisa') && $Aplic->checarModulo('pesquisa', 'acesso');
	$podeAcessar_recursos=$Aplic->modulo_ativo('recursos') && $Aplic->checarModulo('recursos', 'acesso');
	$podeAcessar_links=$Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso');
	$podeAcessar_cias=$Aplic->modulo_ativo('cias') && $Aplic->checarModulo('cias', 'acesso');
	$podeAcessar_depts=$Aplic->modulo_ativo('depts') && $Aplic->checarModulo('depts', 'acesso');
	$podeAcessar_projetos=$Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso');
	$podeAcessar_tarefas=$Aplic->modulo_ativo('tarefas') && $Aplic->checarModulo('tarefas', 'acesso');
	$podeAcessar_praticas=$Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso');
	$podeAcessar_calendario=$Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso');
	$podeAcessar_relatorios=$Aplic->modulo_ativo('relatorios') && $Aplic->checarModulo('relatorios', 'acesso');
	if (!$Aplic->celular && !$dialogo){
		echo '<tr><td><table width="100%" cellpadding=0 cellspacing=0 border=0><tr><th style="background: url(estilo/rondon/imagens/titulo_fundo.png);" align="left" ><a href="'.$config['endereco_site'].'" target="_blank">'.dica('Site do '.$config['gpweb'], 'Clique para entrar no site oficial do '.$config['gpweb'].'.').'<img src="estilo/rondon/imagens/organizacao/10/mensagens.png" border=0 class="letreiro" align="left" />'.dicaF().'</th>';
		echo '<th style="background: url(estilo/rondon/imagens/titulo_fundo.png);" align="left" width="100%">&nbsp;</th>';
		if ($config['militar']==11) echo '<th style="background: url(estilo/rondon/imagens/titulo_fundo.png);" align="left" ><a href="http://www.mbc.org.br/mbc/pgqp" target="_blank">'.dica('PGQP - Programa Gaúcho da Qualidade e Produtividade', 'Clique para entrar no site oficial do PGQP - Programa Gaúcho da Qualidade e Produtividade.').'<img src="estilo/rondon/imagens/organizacao/11/pgqp.png" border=0 class="letreiro" align="left" />'.dicaF().'</th>';
		else echo '<th style="font-size: xx-small; background: url(estilo/rondon/imagens/titulo_fundo.png); white-space: nowrap;" align="right" >v. '.$Aplic->getVersao().'</th>';
		echo '</table></td></tr>';
		}

	if (!$dialogo && $m!='email'){
		$nav = $Aplic->getMenuModulos();
		echo '<tr class="nav"><td width="100%" nowrap="nowrap" style="background-color: #e6e6e6">';
		require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
		$menu = new CoolMenu('kn');
		$menu->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
		$menu->styleFolder="default";
		$menu->Add("root","menu","Menu", "javascript: void(0);");

		if ($podeAcessar_email) {
			$menu->Add("menu","msg_tarefa",dica(ucfirst($config['mensagens']).' do Tipo Atividade','Selecione esta opção para acessar o painel de controle d'.$config['genero_mensagem'].'s '.$config['mensagens'].' que são do tipo atividade.').ucfirst($config['mensagens']).' do tipo atividade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg_tarefa\");", "estilo/rondon/imagens/icones/task_p.png");
			$menu->Add("menu","despacho",dica('Controle de Despachos','Selecione esta opção para acessar os despacho recebidos e enviados ainda sem uma resposta.').'Despachos'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/despacho_p.gif");
			$menu->Add("despacho","despacho_msg",dica('Controle de Despachos de '.ucfirst($config['mensagens']),'Selecione esta opção para acessar os despacho recebidos e enviados de '.$config['mensagens'].' ainda sem uma resposta.').'Despachos de '.$config['mensagens'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_despacho\");", "estilo/rondon/imagens/icones/despacho_p.gif");
			if ($config['doc_interno']) $menu->Add("despacho","despacho_modelo",dica('Controle de Despachos de Documentos','Selecione esta opção para acessar os despacho recebidos e enviados de documentos ainda sem uma resposta.').'Despachos de documentos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_despacho_modelo\");", "estilo/rondon/imagens/icones/despacho_p.gif");
			$menu->Add("despacho",'vazio0', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}

		if ($podeAcessar_contatos) $menu->Add("menu","contatos",dica('Contatos','Selecione esta opção para acessar os contatos cadastrados.').'Contatos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=contatos&a=index&filtro_id_responsavel=".$Aplic->usuario_id."\");", "estilo/rondon/imagens/icones/contatos_p.png");
		$q->adTabela('parafazer_usuarios');
		$q->adCampo('count(id)');
		$q->adOnde('usuario_id = '.$Aplic->usuario_id.' AND aceito=0');
		$parafazer_outros = $q->Resultado();
		$q->limpar();
		if ($podeAcessar_parafazer) {
			$menu->Add("menu","prafazer", dica('Lembretes','Selecione esta opção para acessar sua lista particular de atividades a serem realizadas.').'Lembretes'.($parafazer_outros ? ' ('.$parafazer_outros.')': '').dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("prafazer","lembretes", dica('Lista de Lembretes','Selecione esta opção para acessar sua lista particular de atividades a serem realizadas.').'Lista de Lembretes'.($parafazer_outros ? ' ('.$parafazer_outros.')': '').dicaF(), "javascript: void(0);' onclick='lista_todo(".$parafazer_outros.");", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("prafazer","controle_prafazer", dica('Controle dos Convites de Lembretes','Selecione esta opção para acessar o painel de aceitação de lembretes recebidos.').'Controle dos Lembretes'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=parafazer&a=controle\");", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("prafazer",'vazio5', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		if ($podeAcessar_foruns) $menu->Add("menu","foruns", dica('Fóruns','Exibir a lista de fóruns').'Fóruns&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=index\");", "estilo/rondon/imagens/icones/forum_p.gif");
		
		
		if ($podeAcessar_arquivos) {
			$menu->Add("menu","arquivos", dica('Arquivos','Exibir os arquivos incluídos no sistema').'Arquivos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/arquivo_p.png");
			$menu->Add("arquivos","lista_arquivos", dica('Lista de Arquivos','Exibir a lista de arquivos incluídos no sistema').'Lista de Arquivos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=index\");", "estilo/rondon/imagens/icones/arquivo_p.png");
			$menu->Add("arquivos","lista_pastas", dica('Lista de Pastas','Exibir a lista de pastas incluídas no sistema').'Lista de Pastas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=pasta_lista\");", "estilo/rondon/imagens/icones/pasta.png");
			
			}
		
		if ($podeAcessar_pesquisa) $menu->Add("menu","pesquisa", dica('Pesquisa Inteligente','Selecione para pesquisar por palavra chave dentro dos módulos do sistema').'Pesquisa Inteligente'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pesquisa&a=index\");", "estilo/rondon/imagens/icones/busca_p.png");
		if ($podeAcessar_recursos) {
			$menu->Add("menu","instrumentos", dica('Instrumentos','Exibir os instrumentos (contrato, convênio, etc.) cadastrados no sistema').'Instrumentos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=instrumento_lista\");", "estilo/rondon/imagens/icones/instrumento_p.png");
			$menu->Add("menu","recursos", '<span style="width:120px;">'.dica('Recursos','Exibir os recursos cadastrados no sistema').'Recursos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=index\");", "estilo/rondon/imagens/icones/recursos_p.gif");
			}

		if ($podeAcessar_links) $menu->Add("menu","links", dica('Links','Exibir os links cadastrados no sistema').'Links&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=index\");", "estilo/rondon/imagens/icones/links_p.gif");
		if ($podeAcessar_cias) $menu->Add("menu",ucfirst($config['organizacoes']), dica(ucfirst($config['organizacoes']),'Exibir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' cadastrados no sistema').ucfirst($config['organizacoes']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=cias&a=index\");", "estilo/rondon/imagens/icones/organizacao_p.gif");
		if ($podeAcessar_depts) $menu->Add("menu",ucfirst($config['departamentos']), dica(ucfirst($config['departamentos']),'Exibir '.$config['genero_dept'].'s '.$config['departamentos'].' cadastrad'.$config['genero_dept'].'s no sistema').ucfirst($config['departamentos']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=depts&a=index\");", "estilo/rondon/imagens/icones/secoes_p.gif");

		if (config('registrar_mudancas') && $Aplic->checarModulo('historico', 'acesso') && $Aplic->modulo_ativo('historico')) $menu->Add("menu","historico",dica('Histórico de alterações','Selecione esta opção para acessar o histórico de alterações efetuados nos diversos módulos do '.$config['gpweb'].'.').'Histórico&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=historico&a=index\");", "estilo/rondon/imagens/icones/historico_p.png");


		//Modulos extras
		exibir_modulos_terceiros();

		if($Aplic->usuario_super_admin || $Aplic->usuario_admin) $menu->Add("menu","admin",dica('Administração dos '.ucfirst($config['usuarios']),'Selecione esta opção para acessar a administração d'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'Administração d'.$config['genero_usuario'].'s '.$config['usuarios'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=admin&a=index\");", "estilo/rondon/imagens/icones/membros_p.png");
		if ($Aplic->usuario_admin || $Aplic->usuario_super_admin) $menu->Add("menu","sistema",dica('Sistema','Selecione esta opção para acessar diversas opções do Sistema.').'Sistema&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&a=index\");", "estilo/rondon/imagens/icones/config-sistema_p.png");

		if ($podeAcessar_projetos) {
			$editar_projeto=$Aplic->checarModulo('projetos', 'editar');
			$menu->Add("root","projetos", dica(ucfirst($config['projetos']),'Exibir o menu de '.$config['projetos']).ucfirst($config['projetos']).dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/projeto_p.gif");
			if ($config['anexo_mpog']) $menu->Add("projetos","demandas", dica('Demandas','Exibir a lista de demandas que poderão se transformar em '.$config['projetos'].'.').'Demandas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_lista\");", "estilo/rondon/imagens/icones/demanda_p.gif");
			if ($config['anexo_mpog']) $menu->Add("projetos","viabilidades", dica('Estudos de Viabilidade','Exibir a lista de estudos de viabilidade de possíveis '.$config['projetos'].'.').'Estudos de viabilidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_lista\");", "estilo/rondon/imagens/icones/viabilidade_p.gif");
			if ($config['anexo_mpog']) $menu->Add("projetos","banco_projetos", dica('Banco de Possíveis '.ucfirst($config['projetos']),'Exibir a lista de possíveis '.$config['projetos'].' de serem criados, através do termo de abertura.').'Banco de possíveis '.$config['projetos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=banco_projeto\");", "estilo/rondon/imagens/icones/banco_projeto_p.gif");
			$menu->Add("projetos","lista_projetos", dica('Lista de '.ucfirst($config['projetos']),'Exibir a lista de '.$config['projetos']).'Lista de '.$config['projetos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=index\");", "estilo/rondon/imagens/icones/projeto_p.gif");
			if ($podeAcessar_tarefas) $menu->Add("projetos","tarefas", dica(ucfirst($config['tarefas']),'Exibir a lista de '.$config['tarefas']).ucfirst($config['tarefas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=index\");", "estilo/rondon/imagens/icones/tarefa_p.gif");
			if ($editar_projeto) $menu->Add("projetos","wbs", dica('Estrutura Analítica de Projeto(WBS)','Exibir a estrutura analítica de projeto').'Estrutura Analítica de Projeto(WBS)'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_vertical\");", "estilo/rondon/imagens/icones/wbs_p.png");
			if ($editar_projeto) $menu->Add("projetos","agil", dica('Gantt Interativo','Exibir interface de criação e edição de '.$config['projetos'],' utilizando gráfico Gantt interativo.').'Gantt Interativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_completo\");", "estilo/rondon/imagens/icones/projeto_facil_p.gif");
			$menu->Add("projetos","visao_marco", dica('Visão Macro d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']),'Exibir relatório com a visão macro d'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Visão Macro d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=arvore_ciclica\");", "estilo/rondon/imagens/icones/arvore_ciclica.gif");
			$menu->Add("projetos","envio_recebimento", dica('Envio e Recebimento de '. ucfirst($config['projetos']),'Exibir '.$config['genero_projeto'].'s '.$config['projetos'].' recebidos e enviados.').'Envio e Recebimento de '. ucfirst($config['projetos']).'&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=receber_projeto\");", "estilo/rondon/imagens/icones/receber_projeto_p.png");
			if ($podeAcessar_relatorios) $menu->Add("projetos","relatorios_projeto", dica('Relatórios de '.$config['projetos'],'Exibir lista de relatórios de '.$config['projetos']).'Relatórios de '.ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=relatorios&a=index\");", "estilo/rondon/imagens/icones/relatorio_p.gif");
			$menu->Add("projetos","licao", dica('Lições Aprendidas','Exibir lista de lições aprendidas n'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Lições aprendidas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_lista\");", "estilo/rondon/imagens/icones/licoes_p.gif");
			if ($Aplic->profissional) {
				$menu->Add("projetos","msproject", dica('Importar do MS Project ou WBS Chart Pro','Abre uma janela onde se pode importar projeto do MS Project ou do WBS Chart Pro.').'Importar do MS Project ou WBS Chart Pro'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&a=index&u=importar\");", "estilo/rondon/imagens/icones/ms_project_p.png");
				$menu->Add("projetos","exportar_msproject", dica('Exportar para o MS Project','Abre uma janela onde se pode exportar projeto para o MS Project.').'Exportar para o MS Project'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&a=exportar_pro&u=exportar\");", "estilo/rondon/imagens/icones/ms_project_p.png");
				$menu->Add("projetos","template", dica('Modelos','Abre uma janela onde se pode cadastrar novos modelos de projetos.').'Modelos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=template_pro_lista\");", "estilo/rondon/imagens/icones/template_p.gif");
				}
			//vazio só para melhorar a diagramação
			$menu->Add("projetos",'vazio10', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		elseif ($podeAcessar_tarefas) $menu->Add("root","tarefas", dica(ucfirst($config['tarefas']),'Exibir a lista de '.$config['tarefas']).ucfirst($config['tarefas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=index\");", "estilo/rondon/imagens/icones/tarefa_p.gif");

		$acesso_praticas=0;

		if ($podeAcessar_praticas) {
			$menu->Add("root","gestao", dica('Gestão','Exibir o planejamento estratégico d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Gestão'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/ferramentas_p.png");
			$menu->Add("gestao","plano_gestao", dica('Planejamento Estratégico','Exibir o planejamento estratégico d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Planejamento Estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gestao_lista&u=gestao\");", "estilo/rondon/imagens/icones/planogestao_p.png");
			$menu->Add("gestao","lista_perspectivas", dica(ucfirst($config['perspectivas']),'Exibir a lista de '.$config['perspectivas'].'.').ucfirst($config['perspectivas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=perspectiva_lista\");", "estilo/rondon/imagens/icones/perspectiva_p.png");
			$menu->Add("gestao","lista_temas", dica(ucfirst($config['temas']),'Exibir a lista de '.$config['temas'].'.').ucfirst($config['temas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tema_lista\");", "estilo/rondon/imagens/icones/tema_p.png");
			$menu->Add("gestao","lista_obj_estrategicos", dica(ucfirst($config['objetivos']),'Exibir a lista de '.$config['objetivos'].'.').ucfirst($config['objetivos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=obj_estrategico_lista\");", "estilo/rondon/imagens/icones/obj_estrategicos_p.gif");
			$menu->Add("gestao","lista_fatores", dica(ucfirst($config['fatores']),'Exibir a lista de '.$config['fatores'].'.').ucfirst($config['fatores']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_lista\");", "estilo/rondon/imagens/icones/fator_p.gif");
			$menu->Add("gestao","lista_estrategias", dica('Iniciativas','Exibir a lista de iniciativas.').'Iniciativas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=estrategia_lista\");", "estilo/rondon/imagens/icones/estrategia_p.gif");
			$menu->Add("gestao","praticas", dica(ucfirst($config['praticas']),'Menu de '.$config['praticas']).ucfirst($config['praticas']).dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/pratica_p.gif");
			$menu->Add("praticas","lista_praticas", dica('Lista de '.ucfirst($config['praticas']),'Exibir a lista de '.$config['praticas']).'Lista de '.$config['praticas'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_lista\");", "estilo/rondon/imagens/icones/pratica_p.gif");
			$menu->Add("praticas","praticas_melhores", dica('Melhores '.ucfirst($config['praticas']),'Exibir a lista de melhores .'.$config['praticas']).'melhores .'.$config['praticas'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_lista_melhores\");", "estilo/rondon/imagens/icones/pratica_p.gif");
			$menu->Add("gestao","indicadores", dica('Indicadores','Menu de indicadores').'Indicadores'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/indicador_p.gif");
			$menu->Add("indicadores","lista_indicadores", dica('Indicadores','Exibir a lista de indicadores').'Lista de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_lista\");", "estilo/rondon/imagens/icones/indicador_p.gif");
			$menu->Add("indicadores","lista_lacunas", dica('Lacuna de Indicadores','Exibir a lista de ausencias de indicadores que seriam necessários para que todos os indicadores relevantes, referentes a uma pauta de pontuação, fossem apresentados no Balanced Score Card.').'Lacuna de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=lacuna_lista\");", "estilo/rondon/imagens/icones/lacuna_p.png");
			$menu->Add("indicadores","avaliacao", dica('Avaliações','Menu de avaliações dos indicadores').'Avaliações'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("avaliacao","lista_avaliação", dica('Lista de Avaliações','Exibir a lista de avaliações dos indicadores').'Lista de avaliações'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_lista\");", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("avaliacao","avaliar1", dica('Avaliar','Executar uma avaliação previamente cadastrada.').'Avaliar'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_executar\");", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("avaliacao","avaliar2", dica('Avaliar em Dispositivos Móveis','Executar uma avaliação previamente cadastrada, para dispositivos com tela pequena.').'Avaliar em dispositivos móveis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_executar&dialogo=1&movel=1\");", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("gestao","lista_metas", dica('Metas','Exibir a lista de metas.').'Metas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_lista\");", "estilo/rondon/imagens/icones/meta_p.gif");
			$menu->Add("gestao","checklist", dica('Checklist','Exibir a lista de checklist').'Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_lista\");", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("gestao","plano_acao", dica('Planos de Ação','Exibir a lista de planos de ação').'Planos de ação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_lista\");", "estilo/rondon/imagens/icones/plano_acao_p.gif");
			$menu->Add("gestao","relatorios_gestao", dica('Relatórios de BSC','Exibir a lista de relatórios de BSC').'Relatórios de BSC'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/relatorio_p.gif");
			$menu->Add("relatorios_gestao","lista_relatorios_gestao", dica('Lista de Relatórios','Exibir a lista de relatórios de gestão').'Lista de relatórios de gestão'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=relatorios\");", "estilo/rondon/imagens/icones/relatorio_p.gif");
			$menu->Add("relatorios_gestao","arvore_gestao", dica('Árvore da Gestão Estratégica','Exibir a árvore da gestão estratégica.').'Árvore da gestão estratégica'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=arvore_gestao\");", "estilo/rondon/imagens/icones/arvore_p.gif");
			$menu->Add("relatorios_gestao","mapa_estrategicos", dica('Mapa Estratégicos','Exibir o mapa estratégico.').'Mapa estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=mapa_estrategico\");", "estilo/rondon/imagens/icones/mapa_estrategico_p.gif");
			$menu->Add("relatorios_gestao","projetos_por_obj_estr", dica('Projetos por '.ucfirst($config['objetivos']),'Exibir o número de projetos por '.$config['objetivos'].'.').ucfirst($config['projetos']).' por '.$config['objetivos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=projetos_por_obj_estrategicos\");", "estilo/rondon/imagens/icones/mapa_estrategico_p.gif");
			$menu->Add("relatorios_gestao","dept_por_obj_estr", dica(ucfirst($config['departamento']).' por '.$config['objetivos'],'Exibir '.$config['departamento'].' por '.$config['objetivos'].'.').ucfirst($config['dept']).' por '.$config['objetivos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=dept_por_obj_estrategicos\");", "estilo/rondon/imagens/icones/mapa_estrategico_p.gif");
			$menu->Add("relatorios_gestao","obj_vs_iniciativas", dica(ucfirst($config['objetivos']).' vs Iniciativas','Exibir a lista de '.$config['objetivos'].' relaciodados com as iniciativas.').ucfirst($config['objetivos']).' vs iniciativas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=objetivos_vs_iniciativas\");", "estilo/rondon/imagens/icones/obj_vs_iniciativas_p.gif");
			$menu->Add("gestao","ferramentas_gestao1", dica('Ferramentas de Gestão','Exibir as ferramentas de gestão').'Ferramentas de Gestão'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/ferramentas_gestao_p.png");
			$menu->Add("ferramentas_gestao1","brainstorm1", dica('Brainstorm','Exibir Brainstorm').'Brainstorm'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=brainstorm\");", "estilo/rondon/imagens/icones/brainstorm_p.gif");
			$menu->Add("ferramentas_gestao1","causa_efeito1", dica('Diagrama de Causa-Efeito','Exibir diagramas de causa-efeito').'Diagrama de Causa-Efeito'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=causa_efeito\");", "estilo/rondon/imagens/icones/causaefeito_p.png");
			$menu->Add("ferramentas_gestao1","gut1", dica('Matriz GUT','Exibir a matriz de priorização GUT (gravidade, urgência e tendência)').'Matriz GUT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gut\");", "estilo/rondon/imagens/icones/gut_p.gif");
			if ($Aplic->profissional){
				$menu->Add("gestao","painel", dica('Painel de Indicadores','Exibir as opções de painel de indicador').'Painel de Indicadores'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/painel_p.gif");
				$menu->Add("painel","painel_lista", dica('Lista de Painéis de Indicadores','Exibir a lista de painéis de indicadores').'Lista de Painéis de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_pro_lista\");", "estilo/rondon/imagens/icones/indicador_p.gif");
				$menu->Add("painel","odometro_lista", dica('Lista de Odômetros','Exibir a lista de odômetros com valores de indicadores').'Lista de Odômetros'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=odometro_pro_lista\");", "estilo/rondon/imagens/icones/odometro_p.png");
				$menu->Add("painel","painel_composicao_lista", dica('Lista de Composição de Painéis','Exibir a lista de composições de painéis').'Lista de Composições de Painéis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_composicao_pro_lista\");", "estilo/rondon/imagens/icones/painel_p.gif");
				}
			$menu->Add("gestao","modelos", dica('Pautas de <i>Balaced Score Card</i>','Visualizar os <i>Balaced Score Card</i> cadastrados nas diversas réguas de pontuação.').'Pautas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=modelos\");", "estilo/rondon/imagens/icones/modelos_p.png");
			$menu->Add("ferramentas_gestao1",'vazio15', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');

			$acesso_praticas=1;
			}

		if ($podeAcessar_calendario) {
			$menu->Add("root",'calendario', dica('Calendário','Exibir o calendário com as datas de início e término dos indicadores, eventos, etc.').'Calendário'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/calendario_p.png");
			$menu->Add("calendario",'ano', dica('Ano','Exibir o calendário anual com as datas de início e término dos indicadores, eventos, etc.').'Ano'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=ver_ano\");", "estilo/rondon/imagens/icones/calendario_p.png");
			$menu->Add("calendario",'mes', dica('Mês','Exibir o calendário mensal com as datas de início e término dos indicadores, eventos, etc.').'Mês'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=index\");", "estilo/rondon/imagens/icones/calendario_p.png");
			$menu->Add("calendario",'semana', dica('Semana','Exibir o calendário semanal com as datas de início e término dos indicadores, eventos, etc.').'Semana'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=ver_semana\");", "estilo/rondon/imagens/icones/calendario_p.png");
			if ($Aplic->profissional && ($config['usar_cronometro'] ||$config['usar_ponto'])){
				$menu->Add("calendario",'folha_ponto', dica('Ponto','Inserir tempo efetivamente trabalho em eventos e '.$config['tarefas'].'.').'Ponto'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/folha_ponto_p.png");
				if ($config['usar_ponto']) $menu->Add("folha_ponto",'folha_ponto_inserir', dica('Folha de Ponto','Inserir tempo efetivamente trabalho em eventos e '.$config['tarefas'].'.').'Folha de Ponto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=folha_ponto_pro\");", "estilo/rondon/imagens/icones/folha_ponto_p.png");
				if ($config['usar_cronometro']) $menu->Add("folha_ponto",'cronometro', dica('Cronômetro','Iniciar e parar o tempo efetivamente trabalho em eventos e '.$config['tarefas'].'.').'Cronômetro'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=cronometro_pro\");", "estilo/rondon/imagens/icones/cronometro_p.gif");
				$menu->Add("folha_ponto",'folha_ponto_relatorio', dica('Relatórios','Lista de relatórios referentes ao tempo efetivamente trabalho em eventos e '.$config['tarefas'].'.').'Relatórios'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=folha_ponto_pro_relatorio\");", "estilo/rondon/imagens/icones/folha_ponto_p.png");
				}
			//vazio só para melhorar a diagramação
			$menu->Add("calendario",'vazio5', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}

		if ($podeAcessar_email) {
			$menu->Add("root",'mensagem', dica('Comunicação', 'Leia e escreva '.$config['mensagens'].' e documentos pelo sistema interno do '.$config['gpweb'].'.').'Comunicação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=1\");", "estilo/rondon/imagens/icones/email.gif");
			$menu->Add("mensagem",'entrada', dica('Caixa de Entrada', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' na caixa de entrada.').ucfirst($config['mensagens']).' na Caixa de Entrada'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=1&pagina=1\");", "estilo/rondon/imagens/icones/email_receber.gif");
			$menu->Add("mensagem",'pendente', dica('Pendentes', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' colocad'.$config['genero_mensagem'].'s como pendentes.').ucfirst($config['mensagens']).' Pendentes'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=3&pagina=1\");", "estilo/rondon/imagens/icones/email_pendente.gif");
			$menu->Add("mensagem",'arquivadas', dica('Arquivad'.$config['genero_mensagem'].'s', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' colocad'.$config['genero_mensagem'].'s na caixa d'.$config['genero_mensagem'].'s arquivad'.$config['genero_mensagem'].'s.').ucfirst($config['mensagens']).' Arquivad'.$config['genero_mensagem'].'s'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=4&pagina=1\");", "estilo/rondon/imagens/icones/email_arquivada.gif");
			$menu->Add("mensagem",'enviadas', dica('Enviad'.$config['genero_mensagem'].'s', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' enviad'.$config['genero_mensagem'].'s.').ucfirst($config['mensagens']).' Enviad'.$config['genero_mensagem'].'s'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=5&pagina=1\");", "estilo/rondon/imagens/icones/email_enviado.gif");
			$menu->Add("mensagem",'nova_msg', dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Enviar um'.($config['genero_mensagem']=='a' ? 'a' : '').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Nov'.$config['genero_mensagem'].' '.ucfirst($config['msg']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=seleciona_usuarios&destino_cabecalho=envia_msg\");", "estilo/rondon/imagens/icones/email_novo.gif");
			if ($config['doc_interno']) $menu->Add("mensagem",'doc_int', dica('Documentos Internos','Selecione esta opção para acessar os documentos criados dentro do '.$config['gpweb'].'.').'Documentos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_pesquisar\");", "estilo/rondon/imagens/icones/msg10000.gif");
			//vazio só para melhorar a diagramação
			$menu->Add("mensagem",'vazio6', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}

		//vazio só para melhorar a diagramação
		$menu->Add("menu",'vazio1', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		echo $menu->Render();
		echo '</td></tr>';
		if (!$Aplic->celular) echo '<tr><td colspan="20" valign="top" style="background: url(estilo/rondon/imagens/nav_sombra.jpg);" align="left"><img width="1" height="13" src="estilo/rondon/imagens/nav_sombra.jpg"/></td></tr>';
		//echo '</table></td></tr>';

		echo '<tr><td colspan=2><table cellspacing=0 cellpadding="3" border=0 width="100%">';
		echo '<tr><td style="font-family:verdana, arial, helvetica, sans-serif;font-size:8pt;">'.$msg_ini_saud.($Aplic->usuario_id > 0 ? ($Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra) : $visitante).$final_saudacao.'</td>';
		if ($Aplic->usuario_id > 0) {
			echo '<td valign="right" align="left" width="200"><form name="frm_pesquisa" method="POST"><input type="hidden" name="m" value="pesquisa" />';
			if ($podeAcessar_pesquisa) echo dica('Pesquisa', 'Este campo pesquisa em toda base do '.$config['gpweb'].', baseado no texto digitado.</p>Caso necessite de fazer uma pesquisa mais refinada, utilize o item <b>Pesquisa</b> na barra de menu acima.',TRUE).imagem('icones/procurar.png').dicaF().'&nbsp;<input class="texto" size="20" type="text" name="palavraChave" value="Pesquisa geral..." onclick="document.frm_pesquisa.palavraChave.value=\'\'" onblur="document.frm_pesquisa.palavraChave.value=\'Pesquisa geral...\'" />';
			else echo '&nbsp;';
			echo '</form></td>';
			echo '<td align="right" width="300"><table cellspacing=0 cellpadding="3" border=0>';
				echo '<tr>';
				if ($Aplic->usuario_tem_lista_grupo) echo '<td>'.dica('Conta de Grupo', 'Selecionar quais contas de grupo deseja deixar ativas, para verificação das informações. Pode-se selecionar quantas contas cadastradas deseja deixar ativas para os diversos filtros do sistema.').'<a class="botao" href="javascript: void(0);" onclick="popContas();"><span>conta&nbsp;de&nbsp;grupo</span></a>'.dicaF().'</td>';
				if ($podeAcessar_email || $podeAcessar_projetos || $podeAcessar_praticas || $podeAcessar_calendario) echo '<td nowrap="nowrap" align="right">'.dica('Ações à Realizar', 'Mostra '.$config['genero_tarefa'].'s '.$config['tarefas'].', eventos, compromissos, indicadores e '.$config['praticas'].' que lhe foram designadas e que ainda não estejam completas.').'<a class="botao" href="javascript: void(0);" onclick="url_passar(0, \'m=tarefas&a=parafazer\');"><span>fazer</span></a>'.dicaF().'</td>';
				if ($podeAcessar_calendario) {
					$agora = new CData();
					echo '<td>'.dica('Hoje', 'Mostra a agenda com os eventos para hoje.').'<a class="botao" href="javascript: void(0);" onclick="url_passar(0, \'m=calendario&a=ver_dia&tab=0&data='.$agora->format(FMT_TIMESTAMP_DATA).'\');"><span>hoje</span></a>'.dicaF().'</td>';
			 		}
				echo '<td>'.dica('Meus Dados', 'Mostra as suas informações de cadastro e outras que sejam de seu interesse.').'<a class="botao" href="javascript: void(0);" onclick="url_passar(0, \'m=admin&a=ver_usuario&filtro_id_responsavel='.$Aplic->usuario_id.'\');"><span>meus&nbsp;dados</span></a>'.dicaF().'</td>';
				echo '<td>'.dica('Sobre', 'Abre uma janela com informações sobre o Sistema e como entrar em contato com o mantenedor do '.$config['gpweb'].'').'<a class="botao" href="javascript: void(0);" onclick="javascript:window.open(\'?m=ajuda&dialogo=1\', \'ajuda\', \'width=700, height=600, left=0, top=0, scrollbars=yes, resizable=yes\')"><span>sobre</span></a>'.dicaF().'</td>';
				echo '<td>'.dica('Sair do Sistema', 'Encerra a sessão atual e retorna à tela de entrada do Sistema.').'<a class="botao" href="./index.php?logout=-1"><span>sair</span></a>'.dicaF().'</td>';
				echo '</tr></table></td></tr>';
			}
		echo '</table></td></tr>';
		}


	///tela de mensagens
	if (!$dialogo && $m=='email'){

		if (!$podeAcessar_email) {
			$Aplic->redirecionar('m=publico&a=acesso_negado');
			exit();
			}

		echo '<tr class="nav"><td width="100%" nowrap="nowrap" style="background-color: #e6e6e6">';
		require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
		$menu = new CoolMenu('kn');
		$menu->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
		$menu->styleFolder="default";
		$menu->Add("root","menu","Menu", "javascript: void(0);");
		if ($podeAcessar_contatos) $menu->Add("menu","contatos",'<span style="width:120px;">'.dica('Contatos','Selecione esta opção para acessar os contatos cadastrados.').'Contatos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=contatos&a=index&filtro_id_responsavel=".$Aplic->usuario_id."\");", "estilo/rondon/imagens/icones/contatos_p.png");
		$q->adTabela('parafazer_usuarios');
		$q->adCampo('count(id)');
		$q->adOnde('usuario_id = '.$Aplic->usuario_id.' AND aceito=0');
		$parafazer_outros = $q->Resultado();
		$q->limpar();
		if ($podeAcessar_parafazer) {
			$menu->Add("menu","prafazer", dica('Lembretes','Selecione esta opção para acessar sua lista particular de atividades a serem realizadas.').'Lembretes'.($parafazer_outros ? ' ('.$parafazer_outros.')': '').dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("prafazer","lembretes", dica('Lista de Lembretes','Selecione esta opção para acessar sua lista particular de atividades a serem realizadas.').'Lista de Lembretes'.($parafazer_outros ? ' ('.$parafazer_outros.')': '').dicaF(), "javascript: void(0);' onclick='lista_todo(".$parafazer_outros.");", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("prafazer","controle_prafazer", dica('Controle dos Convites de Lembretes','Selecione esta opção para acessar o painel de aceitação de lembretes recebidos.').'Controle dos Lembretes'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=parafazer&a=controle\");", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("prafazer",'vazio5', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		if ($podeAcessar_calendario) {
			$menu->Add("menu",'calendario', '<span style="width:120px;">'.dica('Calendário','Exibir o calendário com as datas de início e término dos indicadores, eventos, etc.').'Calendário&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);", "estilo/rondon/imagens/icones/calendario_p.png");

			if ($a=='modelo_pesquisar'){
				$menu->Add("calendario",'vazio51', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
				$menu->Add("calendario",'vazio52', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
				}

			$menu->Add("calendario",'ano', dica('Ano','Exibir o calendário anual com as datas de início e término dos indicadores, eventos, etc.').'Ano&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=ver_ano\");", "estilo/rondon/imagens/icones/calendario_p.png");
			$menu->Add("calendario",'mes', dica('Mês','Exibir o calendário mensal com as datas de início e término dos indicadores, eventos, etc.').'Mês&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=index\");", "estilo/rondon/imagens/icones/calendario_p.png");
			$menu->Add("calendario",'semana', dica('Semana','Exibir o calendário semanal com as datas de início e término dos indicadores, eventos, etc.').'Semana&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=ver_semana\");", "estilo/rondon/imagens/icones/calendario_p.png");
			if ($Aplic->profissional && ($config['usar_cronometro'] ||$config['usar_ponto'])){
				$menu->Add("calendario",'folha_ponto', dica('Ponto','Inserir tempo efetivamente trabalho em eventos e '.$config['tarefas'].'.').'Ponto'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/folha_ponto_p.png");
				if ($config['usar_ponto']) $menu->Add("folha_ponto",'folha_ponto_inserir', dica('Folha de Ponto','Inserir tempo efetivamente trabalho em eventos e '.$config['tarefas'].'.').'Folha de Ponto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=folha_ponto_pro\");", "estilo/rondon/imagens/icones/folha_ponto_p.png");
				if ($config['usar_cronometro']) $menu->Add("folha_ponto",'cronometro', dica('Cronômetro','Iniciar e parar o tempo efetivamente trabalho em eventos e '.$config['tarefas'].'.').'Cronômetro'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=cronometro_pro\");", "estilo/rondon/imagens/icones/cronometro_p.gif");
				$menu->Add("folha_ponto",'folha_ponto_relatorio', dica('Relatórios','Lista de relatórios referentes ao tempo efetivamente trabalho em eventos e '.$config['tarefas'].'.').'Relatórios'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=folha_ponto_pro_relatorio\");", "estilo/rondon/imagens/icones/folha_ponto_p.png");
				}
			//vazio só para melhorar a diagramação
			$menu->Add("calendario",'vazio5', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');

			}
		if ($a=='modelo_pesquisar' || $a=='modelo_protocolar') $menu->Add("menu","nada", '<br><br>', "javascript: void(0);");
		if ($podeAcessar_projetos) {
			if ($a=='modelo_pesquisar') $menu->Add("menu",'vazio55', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			$menu->Add("menu","projetos", dica(ucfirst($config['projetos']),'Exibir o menu de '.$config['projetos']).ucfirst($config['projetos']).dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/projeto_p.gif");
			if ($config['anexo_mpog']) $menu->Add("projetos","demandas", dica('Demandas','Exibir a lista de demandas que poderão se transformar em '.$config['projetos'].'.').'Demandas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_lista\");", "estilo/rondon/imagens/icones/demanda_p.gif");
			if ($config['anexo_mpog']) $menu->Add("projetos","viabilidades", dica('Estudos de Viabilidade','Exibir a lista de estudos de viabilidade de possíveis '.$config['projetos'].'.').'Estudos de viabilidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_lista\");", "estilo/rondon/imagens/icones/viabilidade_p.gif");
			if ($config['anexo_mpog']) $menu->Add("projetos","banco_projetos", dica('Banco de Possíveis '.ucfirst($config['projetos']),'Exibir a lista de possíveis '.$config['projetos'].' de serem criados, através do termo de abertura.').'Banco de possíveis '.$config['projetos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=banco_projeto\");", "estilo/rondon/imagens/icones/banco_projeto_p.gif");
			$menu->Add("projetos","lista_projetos", dica('Lista de '.ucfirst($config['projetos']),'Exibir a lista de '.$config['projetos']).'Lista de '.$config['projetos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=index\");", "estilo/rondon/imagens/icones/projeto_p.gif");
			if ($podeAcessar_tarefas) $menu->Add("projetos","tarefas", '<span style="width:120px;">'.dica(ucfirst($config['tarefas']),'Exibir a lista de '.$config['tarefas']).ucfirst($config['tarefas']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=index\");", "estilo/rondon/imagens/icones/tarefa_p.gif");
			$menu->Add("projetos","wbs", dica('Estrutura Analítica de Projeto(WBS)','Exibir a estrutura analítica de projeto').'Estrutura Analítica de Projeto(WBS)'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_vertical\");", "estilo/rondon/imagens/icones/wbs_p.png");
			$menu->Add("projetos","agil", dica('Gantt Interativo','Exibir interface de criação e edição de '.$config['projetos'],' utilizando gráfico Gantt interativo.').'Gantt Interativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_completo\");", "estilo/rondon/imagens/icones/projeto_facil_p.gif");
			$menu->Add("projetos","visao_marco", dica('Visão Macro d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']),'Exibir relatório com a visão macro d'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Visão Macro d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=arvore_ciclica\");", "estilo/rondon/imagens/icones/arvore_ciclica.gif");
			$menu->Add("projetos","envio_recebimento", dica('Envio e Recebimento de '. ucfirst($config['projetos']),'Exibir '.$config['genero_projeto'].'s '.$config['projetos'].' recebidos e enviados.').'Envio e Recebimento de '. ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=receber_projeto\");", "estilo/rondon/imagens/icones/receber_projeto_p.png");
			if ($podeAcessar_relatorios) $menu->Add("projetos","relatorios", dica('Relatorios de '.$config['projetos'],'Exibir lista de relatórios de '.$config['projetos']).'Relatórios de '.ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=relatorios&a=index\");", "estilo/rondon/imagens/icones/relatorio_p.gif");
			$menu->Add("projetos","licao", dica('Lições Aprendidas','Exibir lista de lições aprendidas n'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Lições aprendidas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_lista\");", "estilo/rondon/imagens/icones/licoes_p.gif");
			if ($Aplic->profissional) {
				$menu->Add("projetos","msproject", dica('Importar do MS Project ou WBS Chart Pro','Abre uma janela onde se pode importar projeto do MS Project ou do WBS Chart Pro.').'Importar do MS Project ou WBS Chart Pro'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&a=index&u=importar\");", "estilo/rondon/imagens/icones/ms_project_p.png");
				$menu->Add("projetos","exportar_msproject", dica('Exportar para o MS Project','Abre uma janela onde se pode exportar projeto para o MS Project.').'Exportar para o MS Project'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&a=exportar_pro&u=exportar\");", "estilo/rondon/imagens/icones/ms_project_p.png");
				$menu->Add("projetos","template", dica('Modelos','Abre uma janela onde se pode cadastrar novos modelos de projetos.').'Modelos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=template_pro_lista\");", "estilo/rondon/imagens/icones/template_p.gif");
				}
			//vazio só para melhorar a diagramação
			$menu->Add("projetos",'vazio7', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		elseif ($podeAcessar_tarefas) $menu->Add("menu","tarefas", dica(ucfirst($config['tarefas']),'Exibir a lista de '.$config['tarefas']).ucfirst($config['tarefas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=index\");", "estilo/rondon/imagens/icones/tarefa_p.gif");


		if ($podeAcessar_praticas) {
			$menu->Add("menu","gestao", dica('Gestão','Exibir o planejamento estratégico d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Gestão'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/ferramentas_p.png");
			$menu->Add("gestao","plano_gestao", dica('Planejamento Estratégico','Exibir o planejamento estratégico d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Planejamento Estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gestao_lista&u=gestao\");", "estilo/rondon/imagens/icones/planogestao_p.png");
			$menu->Add("gestao","lista_perspectivas", dica(ucfirst($config['perspectivas']),'Exibir a lista de '.$config['perspectivas'].'.').ucfirst($config['perspectivas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=perspectiva_lista\");", "estilo/rondon/imagens/icones/perspectiva_p.png");
			$menu->Add("gestao","lista_temas", dica(ucfirst($config['temas']),'Exibir a lista de '.$config['temas'].'.').ucfirst($config['temas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tema_lista\");", "estilo/rondon/imagens/icones/tema_p.png");
			$menu->Add("gestao","lista_obj_estrategicos", dica(ucfirst($config['objetivos']),'Exibir a lista de '.$config['objetivos'].'.').ucfirst($config['objetivos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=obj_estrategico_lista\");", "estilo/rondon/imagens/icones/obj_estrategicos_p.gif");
			$menu->Add("gestao","lista_fatores", dica(ucfirst($config['fatores']),'Exibir a lista de '.$config['fatores'].'.').ucfirst($config['fatores']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_lista\");", "estilo/rondon/imagens/icones/fator_p.gif");
			$menu->Add("gestao","lista_estrategias", dica('Iniciativas','Exibir a lista de iniciativas.').'Iniciativas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=estrategia_lista\");", "estilo/rondon/imagens/icones/estrategia_p.gif");
			$menu->Add("gestao","praticas", dica(ucfirst($config['praticas']),'Menu de '.$config['praticas']).ucfirst($config['praticas']).dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/pratica_p.gif");
			$menu->Add("praticas","lista_praticas", dica('Lista de '.ucfirst($config['praticas']),'Exibir a lista de '.$config['praticas']).'Lista de '.$config['praticas'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_lista\");", "estilo/rondon/imagens/icones/pratica_p.gif");
			$menu->Add("praticas","praticas_melhores", dica('Melhores '.ucfirst($config['praticas']),'Exibir a lista de melhores .'.$config['praticas']).'melhores .'.$config['praticas'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_lista_melhores\");", "estilo/rondon/imagens/icones/pratica_p.gif");
			$menu->Add("gestao","indicadores", dica('Indicadores','Menu de indicadores').'Indicadores'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/indicador_p.gif");
			$menu->Add("indicadores","lista_indicadores", dica('Indicadores','Exibir a lista de indicadores').'Lista de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_lista\");", "estilo/rondon/imagens/icones/indicador_p.gif");
			$menu->Add("indicadores","lista_lacunas", dica('Lacuna de Indicadores','Exibir a lista de ausencias de indicadores que seriam necessários para que todos os indicadores relevantes, referentes a uma pauta de pontuação, fossem apresentados no Balanced Score Card.').'Lacuna de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=lacuna_lista\");", "estilo/rondon/imagens/icones/lacuna_p.png");
			$menu->Add("indicadores","avaliacao", dica('Avaliações','Menu de avaliações dos indicadores').'Avaliações'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("avaliacao","lista_avaliação", dica('Lista de Avaliações','Exibir a lista de avaliações dos indicadores').'Lista de avaliações'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_lista\");", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("avaliacao","avaliar1", dica('Avaliar','Executar uma avaliação previamente cadastrada.').'Avaliar'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_executar\");", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("avaliacao","avaliar2", dica('Avaliar em Dispositivos Móveis','Executar uma avaliação previamente cadastrada, para dispositivos com tela pequena.').'Avaliar em dispositivos móveis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_executar&dialogo=1&movel=1\");", "estilo/rondon/imagens/icones/avaliacao_p.gif");
			$menu->Add("gestao","lista_metas", dica('Metas','Exibir a lista de metas.').'Metas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_lista\");", "estilo/rondon/imagens/icones/meta_p.gif");
			$menu->Add("gestao","checklist", dica('Checklist','Exibir a lista de checklist').'Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_lista\");", "estilo/rondon/imagens/icones/todo_list_p.png");
			$menu->Add("gestao","plano_acao", dica('Planos de Ação','Exibir a lista de planos de ação').'Planos de ação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_lista\");", "estilo/rondon/imagens/icones/plano_acao_p.gif");
			$menu->Add("gestao","relatorios_gestao", dica('Relatórios de BSC','Exibir a lista de relatórios de BSC').'Relatórios de BSC'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/relatorio_p.gif");
			$menu->Add("relatorios_gestao","lista_relatorios_gestao", dica('Lista de Relatórios','Exibir a lista de relatórios de gestão').'Lista de relatórios de gestão'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=relatorios\");", "estilo/rondon/imagens/icones/relatorio_p.gif");
			$menu->Add("relatorios_gestao","arvore_gestao", dica('Árvore da Gestão Estratégica','Exibir a árvore da gestão estratégica.').'Árvore da gestão estratégica'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=arvore_gestao\");", "estilo/rondon/imagens/icones/arvore_p.gif");
			$menu->Add("relatorios_gestao","mapa_estrategicos", dica('Mapa Estratégicos','Exibir o mapa estratégico.').'Mapa estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=mapa_estrategico\");", "estilo/rondon/imagens/icones/mapa_estrategico_p.gif");
			$menu->Add("relatorios_gestao","projetos_por_obj_estr", dica('Projetos por '.ucfirst($config['objetivos']),'Exibir o número de projetos por '.$config['objetivos'].'.').ucfirst($config['projetos']).' por '.$config['objetivos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=projetos_por_obj_estrategicos\");", "estilo/rondon/imagens/icones/mapa_estrategico_p.gif");
			$menu->Add("relatorios_gestao","dept_por_obj_estr", dica(ucfirst($config['departamento']).' por '.ucfirst($config['objetivos']),'Exibir '.$config['departamento'].' por '.$config['objetivos'].'.').ucfirst($config['dept']).' por '.$config['objetivos'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=dept_por_obj_estrategicos\");", "estilo/rondon/imagens/icones/mapa_estrategico_p.gif");
			$menu->Add("relatorios_gestao","obj_vs_iniciativas", dica(ucfirst($config['objetivos']).' vs Iniciativas','Exibir a lista de '.$config['objetivos'].' relaciodados com as iniciativas.').'Objetivos vs iniciativas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=objetivos_vs_iniciativas\");", "estilo/rondon/imagens/icones/obj_vs_iniciativas_p.gif");
			$menu->Add("gestao","ferramentas_gestao1", dica('Ferramentas de Gestão','Exibir as ferramentas de gestão').'Ferramentas de Gestão'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/ferramentas_gestao_p.png");
			$menu->Add("ferramentas_gestao1","brainstorm1", dica('Brainstorm','Exibir Brainstorm').'Brainstorm'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=brainstorm\");", "estilo/rondon/imagens/icones/brainstorm_p.gif");
			$menu->Add("ferramentas_gestao1","causa_efeito1", dica('Diagrama de Causa-Efeito','Exibir diagramas de causa-efeito').'Diagrama de Causa-Efeito'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=causa_efeito\");", "estilo/rondon/imagens/icones/causaefeito_p.png");
			$menu->Add("ferramentas_gestao1","gut1", dica('Matriz GUT','Exibir a matriz de priorização GUT (gravidade, urgência e tendência)').'Matriz GUT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gut\");", "estilo/rondon/imagens/icones/gut_p.gif");
			if ($Aplic->profissional){
				$menu->Add("gestao","painel", dica('Painel de Indicadores','Exibir as opções de painel de indicador').'Painel de Indicadores'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/painel_p.gif");
				$menu->Add("painel","painel_lista", dica('Lista de Painéis de Indicadores','Exibir a lista de painéis de indicadores').'Lista de Painéis de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_pro_lista\");", "estilo/rondon/imagens/icones/indicador_p.gif");
				$menu->Add("painel","odometro_lista", dica('Lista de Odômetros','Exibir a lista de odômetros com valores de indicadores').'Lista de Odômetros'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=odometro_pro_lista\");", "estilo/rondon/imagens/icones/odometro_p.png");
				$menu->Add("painel","painel_composicao_lista", dica('Lista de Composição de Painéis','Exibir a lista de composições de painéis').'Lista de Composições de Painéis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_composicao_pro_lista\");", "estilo/rondon/imagens/icones/painel_p.gif");
				}
			$menu->Add("gestao","modelos", dica('Pautas de <i>Balaced Score Card</i>','Visualizar os <i>Balaced Score Card</i> cadastrados nas diversas réguas de pontuação.').'Pautas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=modelos\");", "estilo/rondon/imagens/icones/modelos_p.png");
			$menu->Add("ferramentas_gestao1",'vazio15', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		if ($podeAcessar_pesquisa) $menu->Add("menu","pesquisa", '<span style="width:120px;">'.dica('Pesquisa Inteligente','Selecione para pesquisar por palavra chave dentro dos módulos do sistema').'Pesquisa Inteligente'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=pesquisa&a=index\");", "estilo/rondon/imagens/icones/busca_p.png");
		if ($podeAcessar_foruns) $menu->Add("menu","foruns", '<span style="width:120px;">'.dica('Fóruns','Exibir a lista de fóruns').'Fóruns&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=index\");", "estilo/rondon/imagens/icones/forum_p.gif");
		if ($podeAcessar_arquivos) $menu->Add("menu","arquivos", '<span style="width:120px;">'.dica('Arquivos','Exibir os arquivos incluídos no sistema').'Arquivos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=index\");", "estilo/rondon/imagens/icones/arquivo_p.png");
		if ($podeAcessar_recursos) {
			$menu->Add("menu","instrumentos", dica('Instrumentos','Exibir os instrumentos (contrato, convênio, etc.) cadastrados no sistema').'Instrumentos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=instrumento_lista\");", "estilo/rondon/imagens/icones/instrumento_p.png");
			$menu->Add("menu","recursos", '<span style="width:120px;">'.dica('Recursos','Exibir os recursos cadastrados no sistema').'Recursos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=index\");", "estilo/rondon/imagens/icones/recursos_p.gif");
			}
		if ($podeAcessar_links) $menu->Add("menu","links", '<span style="width:120px;">'.dica('Links','Exibir os links cadastrados no sistema').'Links&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=links&a=index\");", "estilo/rondon/imagens/icones/links_p.gif");
		if ($podeAcessar_cias) $menu->Add("menu",ucfirst($config['organizacoes']), '<span style="width:120px;">'.dica(ucfirst($config['organizacoes']),'Exibir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' cadastrados no sistema').ucfirst($config['organizacoes']).dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=cias&a=index\");", "estilo/rondon/imagens/icones/organizacao_p.gif");
		if ($podeAcessar_depts) $menu->Add("menu","departamentos", '<span style="width:120px;">'.dica(ucfirst($config['departamentos']),'Exibir '.$config['genero_dept'].'s '.$config['departamentos'].' cadastrad'.$config['genero_dept'].'s no sistema').ucfirst($config['departamentos']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=depts&a=index\");", "estilo/rondon/imagens/icones/secoes_p.gif");
		if (config('registrar_mudancas') && $Aplic->checarModulo('historico', 'acesso') && $Aplic->modulo_ativo('historico')) $menu->Add("menu","historico",dica('Histórico de alterações','Selecione esta opção para acessar o histórico de alterações efetuados nos diversos módulos do '.$config['gpweb'].'.').'Histórico&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=historico&a=index\");", "estilo/rondon/imagens/icones/historico_p.png");

		//Módulos extras
		exibir_modulos_terceiros();

		if($Aplic->usuario_admin || $Aplic->usuario_super_admin) $menu->Add("menu","admin",dica('Administração dos '.ucfirst($config['usuarios']),'Selecione esta opção para acessar a administração d'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'Administração d'.$config['genero_usuario'].'s '.$config['usuarios'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=admin&a=index\");", "estilo/rondon/imagens/icones/membros_p.png");
		if ($Aplic->usuario_admin || $Aplic->usuario_super_admin) $menu->Add("menu","sistema",'<span style="width:120px;">'.dica('Sistema','Selecione esta opção para acessar diversas opções do Sistema.').'Sistema&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</span>', "javascript: void(0);' onclick='url_passar(0, \"m=sistema&a=index\");", "estilo/rondon/imagens/icones/config-sistema_p.png");

		$q->adTabela('msg_usuario');
		if ($Aplic->getPref('agrupar_msg')) $q->adCampo('count(DISTINCT msg_id)');
		else $q->adCampo('count(msg_usuario_id)');
		$q->adOnde('para_id = '.$Aplic->usuario_id.' AND status=0');
		$nao_lidas = $q->Resultado();
		$q->limpar();
		$q->adTabela('msg_usuario');
		if ($Aplic->getPref('agrupar_msg')) $q->adCampo('count(DISTINCT msg_id)');
		else $q->adCampo('count(msg_usuario_id)');
		$q->adOnde('para_id = '.$Aplic->usuario_id.' AND status<2');
		$total_entrada = $q->Resultado();
		$q->limpar();
		$q->adTabela('msg_usuario');
		if ($Aplic->getPref('agrupar_msg')) $q->adCampo('count(DISTINCT msg_id)');
		else $q->adCampo('count(msg_usuario_id)');
		$q->adOnde('para_id = '.$Aplic->usuario_id.' AND status=3');
		$pendentes = $q->Resultado();
		$q->limpar();

		$menu->Add("root",'entrada', dica('Caixa de Entrada', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' na caixa de entrada.').'Entrada'.($nao_lidas ? ' ('.$nao_lidas.'/'.$total_entrada.')' : ($total_entrada ? ' ('.$total_entrada.')' : '')).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=1\");", "estilo/rondon/imagens/icones/email_receber.gif");
		$menu->Add("root",'pendente', dica('Pendentes', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' colocad'.$config['genero_mensagem'].'s como pendentes.').'Pendentes'.($pendentes ? ' ('.$pendentes.')' : '').dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=3\");", "estilo/rondon/imagens/icones/email_pendente.gif");
		$menu->Add("root",'arquivadas', dica('Arquivad'.$config['genero_mensagem'].'s', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' colocad'.$config['genero_mensagem'].'s na caixa d'.$config['genero_mensagem'].'s arquivadas.').'Arquivad'.$config['genero_mensagem'].'s'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=4\");", "estilo/rondon/imagens/icones/email_arquivada.gif");
		$menu->Add("root",'enviadas', dica('Enviadas', 'Leia '.$config['genero_mensagem'].'s '.$config['mensagens'].' enviad'.$config['genero_mensagem'].'s.').'Enviad'.$config['genero_mensagem'].'s'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg&status=5\");", "estilo/rondon/imagens/icones/email_enviado.gif");
		$menu->Add("root",'nova_msg', dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Enviar um'.($config['genero_mensagem']=='a' ? 'a' : '').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Nov'.$config['genero_mensagem'].'&nbsp;'.ucfirst($config['msg']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=seleciona_usuarios&destino_cabecalho=envia_msg\");", "estilo/rondon/imagens/icones/email_novo.gif");

		if ($config['doc_interno']){
			//caixa entrada
			$q->adTabela('modelo_usuario');

			if ($Aplic->getPref('agrupar_msg')) $q->adCampo('count(DISTINCT modelo_id)');
			else $q->adCampo('count(modelo_usuario_id)');
			$q->adOnde('para_id='.$Aplic->usuario_id);
			$q->adOnde('status<2');


			$todas_entr = $q->Resultado();
			$q->limpar();

			$q->adTabela('modelo_usuario');
			if ($Aplic->getPref('agrupar_msg')) $q->adCampo('count(DISTINCT modelo_id)');
			else $q->adCampo('count(modelo_usuario_id)');
			$q->adOnde('para_id='.$Aplic->usuario_id);
			$q->adOnde('status=0');
			$nao_lida = $q->Resultado();
			$q->limpar();

			$menu->Add("root",'doc_int', dica('Documentos Internos','Selecione esta opção para acessar os documentos criados dentro do '.$config['gpweb'].'.').'Documentos '.($todas_entr ? ' ('.$todas_entr.($nao_lida ? '/'.$nao_lida: '').')': '').dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_pesquisar\");", "estilo/rondon/imagens/icones/msg10000.gif");
			}

		$q->adTabela('agenda', 'e');
		$q->esqUnir('agenda_usuarios', 'agenda_usuarios', 'agenda_usuarios.agenda_id = e.agenda_id');
		$q->adCampo('count(DISTINCT e.agenda_id)');
		$q->adOnde('agenda_dono != '.$Aplic->usuario_id);
		$q->adOnde('agenda_usuarios.usuario_id='.$Aplic->usuario_id);
		$q->adOnde('agenda_usuarios.aceito=0');
		$convites=$q->Resultado();
		$q->Limpar();
		$menu->Add("root",'compromissos', dica('Agenda de Compromissos','Selecione esta opção para acessar sua agenda de compromissos.').'Compromissos'.($convites ? ' ('.$convites.')' : '').dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=ver_mes\");", "estilo/rondon/imagens/icones/calendario_p.png");

		if ($podeAcessar_email) {
			$menu->Add("root","msg_tarefa",dica(ucfirst($config['mensagens']).' do Tipo Atividade','Selecione esta opção para acessar o painel de controle d'.$config['genero_mensagem'].'s '.$config['mensagens'].' que são do tipo atividade.').'Atividades'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_msg_tarefa\");", "estilo/rondon/imagens/icones/msg_tarefa_p.gif");
			$menu->Add("root","despacho",dica('Controle de Despachos','Selecione esta opção para acessar os despacho recebidos e enviados ainda sem uma resposta.').'Despachos'.dicaF(), "javascript: void(0);", "estilo/rondon/imagens/icones/despacho_p.gif");
			$menu->Add("despacho","despacho_msg",dica('Controle de Despachos de '.ucfirst($config['mensagens']),'Selecione esta opção para acessar os despacho recebidos e enviados de '.$config['mensagens'].' ainda sem uma resposta.').'Despachos de '.$config['mensagens'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_despacho\");", "estilo/rondon/imagens/icones/despacho_p.gif");
			if ($config['doc_interno']) $menu->Add("despacho","despacho_modelo",dica('Controle de Despachos de Documentos','Selecione esta opção para acessar os despacho recebidos e enviados de documentos ainda sem uma resposta.').'Despachos de documentos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=lista_despacho_modelo\");", "estilo/rondon/imagens/icones/despacho_p.gif");
			$menu->Add("despacho",'vazio16', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		//vazio só para melhorar a diagramação
		$menu->Add("menu",'vazio1', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		echo $menu->Render();
		echo '</td></tr>';
		if (!$Aplic->celular) echo '<tr><td colspan="20" valign="top" style="background: url(estilo/rondon/imagens/nav_sombra.jpg);" align="left"><img width="1" height="13" src="estilo/rondon/imagens/nav_sombra.jpg"/></td></tr>';
		echo '<tr><td colspan="2"><table cellspacing=0 cellpadding="3" border=0 width="100%">';
		echo '<tr>';
		echo '<td style="font-family:verdana, arial, helvetica, sans-serif;font-size:8pt;">'.($Aplic->chave_privada ? imagem('icones/cadeado.gif', 'Chave Privada','A chave privada foi carregada na memória.<br>É possível assinar documentos e ler '.$config['mensagens'].' criptografad'.$config['genero_mensagem'].'s com sua chave pública.') :'').$msg_ini_saud.($Aplic->usuario_id > 0 ? ($Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra) : $visitante).$final_saudacao.'</td>';
		echo '<td valign="right" align="right" width="200">';

		echo '<form name="frm_pesquisa" method="POST">';
		echo '<input type="hidden" name="a" value="lista_msg" />';
		echo '<input type="hidden" name="m" value="email" />';
		echo '<input type="hidden" name="status" value="10" />';
		echo '<input class="texto" size="20" type="text" name="assunto" value="" onchange="javascript:frm_pesquisa.submit();" /></td><td>'.dica('Pesquisa Avançada', 'Clique neste ícone '.imagem('icones/procurar.png').'para abrir uma caixa de opções de pesquisa avançada ou digite a palavra chave no campo de texto à exquerda e pressione ENTER.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=pesquisar\');">'.imagem('icones/procurar.png').'</a>'.dicaF().'</td>';
		echo '</form>';

		echo '<td align="right" width="300"><table cellspacing=0 cellpadding="3" border=0><tr>';
		if ($Aplic->usuario_tem_lista_grupo) echo '<td>'.dica('Conta  de Grupo', 'Selecionar quais contas de grupo deseja deixar ativas, para verificação das informações. Pode-se selecionar quantas contas cadastradas deseja deixar ativas para os diversos filtros do sistema.').'<a class="botao" href="javascript: void(0);" onclick="popContas();"><span>conta&nbsp;de&nbsp;grupo</span></a>'.dicaF().'</td>';
		if ($Aplic->conta_conjunta) echo '<td>'.dica('Mudar de Conta', 'Alterar para a segunda conta cadastrada').'<a class="botao" href="javascript: void(0);" onclick="javascript:url_passar(0, \'m=email&a=mudar_conta\');"><span>outra&nbsp;conta</span></a>'.dicaF().'</td>';
		if (function_exists('openssl_sign')) echo '<td>'.dica('Chaves', 'Abre uma janela onde poderá carregar a chave privada na memória, ou criar o par de chaves públicas e privadas').'<a class="botao" href="javascript: void(0);" onclick="javascript:url_passar(0, \'m=email&a=chaves\');"><span>chaves</span></a>'.dicaF().'</td>';
		echo '<td nowrap="nowrap" align="right">'.dica('Ações à Realizar', 'Mostra '.$config['genero_tarefa'].'s '.$config['tarefas'].', eventos, compromissos, indicadores e '.$config['praticas'].' que lhe foram designadas e que ainda não estejam completas.').'<a class="botao" href="javascript: void(0);" onclick="url_passar(0, \'m=tarefas&a=parafazer\');"><span>fazer</span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Meus Dados', 'Mostra as suas informações de cadastro e outras que sejam de seu interesse.').'<a class="botao" href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=ver_usuario&usuario_id='.$Aplic->usuario_id.'\');"><span>meus&nbsp;dados</span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Sobre', 'Abre uma janela com informações sobre o Sistema e como entrar em contato com o mantenedor do '.$config['gpweb'].'.').'<a class="botao" href="javascript: void(0);" onclick="javascript:window.open(\'?m=ajuda&dialogo=1\', \'ajuda\', \'width=700, height=600, left=0, top=0, scrollbars=yes, resizable=yes\')"><span>sobre</span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Sair do Sistema', 'Encerra a sessão atual e retorna à tela de entrada do Sistema.').'<a class="botao" href="./index.php?logout=-1"><span>sair</span></a>'.dicaF().'</td>';
		echo '</tr></table></td></tr>';
		echo '</table></td></tr>';
		}
    echo '</table>';
    }

if (!$dialogo) echo $Aplic->getMsg();

function exibir_modulos_terceiros(){
	global $Aplic, $q, $menu;
	$q->adTabela('modulos');
	$q->adCampo('mod_diretorio,mod_ui_nome, mod_texto_botao, mod_ui_icone, mod_menu');
	$q->adOnde('mod_tipo !=\'core\'');
	$q->adOnde('mod_ativo=1');
	$q->adOrdem('mod_ui_ordem, mod_ui_nome');
	$modulos = $q->Lista();
	$q->limpar();
	foreach ($modulos as $modulo) {
		if($Aplic->checarModulo($modulo['mod_diretorio'], 'acesso')){
			//verifica se tem script de menu
			if ($modulo['mod_menu']){
				$qnt=0;
				$lista=explode(';',$modulo['mod_menu']);
				foreach($lista as $item){
					$linha=explode(':',$item);
					if (!$qnt) $menu->Add("menu", (isset($linha[4]) ? $linha[4] : $modulo['mod_diretorio']), dica($linha[0],$linha[3]).$linha[0].dicaF(), ($linha[2] ? "javascript: void(0);' onclick='url_passar(0, \"".$linha[2]."\");" : "javascript: void(0);"), ($linha[1]? 'modulos/'.$modulo['mod_diretorio'].'/imagens/'.$linha[1] : ''));
					else $menu->Add((isset($linha[5]) ? $linha[5] : $modulo['mod_diretorio']), (isset($linha[4]) ? $linha[4] : $modulo['mod_diretorio'].'_'.$qnt), dica($linha[0],$linha[3]).$linha[0].dicaF(), ($linha[2] ? "javascript: void(0);' onclick='url_passar(0, \"".$linha[2]."\");" : "javascript: void(0);"), ($linha[1] ? 'modulos/'.$modulo['mod_diretorio'].'/imagens/'.$linha[1] : ''));
					$qnt++;
					}
				}
			else $menu->Add("menu", $modulo['mod_diretorio'], dica($modulo['mod_ui_nome'],$modulo['mod_texto_botao']).$modulo['mod_ui_nome'].dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=".$modulo['mod_diretorio']."&a=index\");", 'modulos/'.$modulo['mod_diretorio'].'/imagens/'.$modulo['mod_ui_icone']);
			}
		}
	}

?>


<div id="fade" class="cobertura_negra"></div>
