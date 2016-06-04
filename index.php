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

/********************************************************************************************

gpweb\index.php

Arquivo base do aplicativo, do qual os outros arquivos são incluidos

convenções:

	$m é o nome da subpasta de gpweb/modulos
	$a é o arquivo com extensão php
	$u é uma subpasta de $m

********************************************************************************************/

require_once 'base.php';
clearstatcache();

//funções básicas
require_once BASE_DIR.'/incluir/funcoes_principais.php';

if (is_file(BASE_DIR.'/config.php')) require_once BASE_DIR.'/config.php';
else {
	$m = isset($_REQUEST['m']) ? getParam($_REQUEST, 'm', null) : '';
	$u = isset($_REQUEST['u']) ? getParam($_REQUEST, 'u', null) : '';
	if($m == 'sistema' && $u == 'menu'){
		echo json_encode(array('sucess' => true, 'menu' => array()));
		exit();
		}
	require_once BASE_DIR.'/instalacao/config-dist.php';
	require_once BASE_DIR.'/estilo/rondon/funcao_grafica.php';
	echo '<html><head><meta http-equiv="refresh" content="5; URL='.BASE_URL.'/instalacao/index.php" charset="iso-8859-1"><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css"></head><body>';
	echo '<br><br><br>';
	echo '<table width="600" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td>'.estiloTopoCaixa().'</td></tr><tr><td>';
	echo '<table cellspacing=0 cellpadding="6" border=0 class="std" width="100%" align="center">';
	echo '<tr><td align="center"><h1>Ainda n&atilde;o foi criado o arquivo de configura&ccedil;&atilde;o.</h1></td><tr>';
	echo '<tr><td align="center"><a href="./instalacao/index.php">Clique aqui para iniciar a instala&ccedil;&atilde;o e criar um</a><br><br>(redirecionamento em 5 seg.)</td></tr></table></td></tr>';
	echo '<tr><td>'.estiloFundoCaixa().'</td></tr></table>';
	echo '</body></html>';
	exit();
	}

$_GPWEB_CODIGO_SEGURANCA_ = false;
if(isset($_COOKIE['gpweb_seckey'])){
    $_GPWEB_CODIGO_SEGURANCA_ = $_COOKIE['gpweb_seckey'];
    }

if (!isset($GLOBALS['OS_WIN'])) $GLOBALS['OS_WIN'] = (stristr(PHP_OS, 'WIN') !== false);

//classe para acesso ao Banco de dados
require_once BASE_DIR.'/incluir/db_adodb.php';
//classe $Aplic com as variaveis do sistema
require_once BASE_DIR.'/classes/ui.class.php';
//manejar sessao
require_once BASE_DIR.'/incluir/sessao.php';

$sem_cabecalho = getParam($_REQUEST, 'sem_cabecalho', false);
$dialogo = getParam($_REQUEST, 'dialogo', 0);

sessaoIniciar(array('Aplic'));

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-cache, must-revaldataInicio, no-store, post-check=0, pre-check=0');
header('Pragma: no-cache');

if( (isset($_SESSION['Aplic']) && $_SESSION['Aplic']->login_externo) && (int) getParam($_REQUEST, 'encerrar_login_externo', 0) ){
    header('location:'.BASE_URL.'/index.php?logout=1');
    exit;
    }

if (!isset($_SESSION['Aplic']) || isset($_REQUEST['logout'])) {
	//fez logout do sistema
	if (isset($_REQUEST['logout']) && isset($_SESSION['Aplic']->usuario_id)) {
		$Aplic = &$_SESSION['Aplic'];
		$usuario_id = $Aplic->usuario_id;
		}
	$_SESSION['Aplic'] = new CAplic;
	$Aplic = &$_SESSION['Aplic'];
	}
else{
	$Aplic = &$_SESSION['Aplic'];
    $Aplic->resetJS();
	}

$Aplic->ultimo_acesso = time();
$ultimo_id_inserido = $Aplic->ultimo_id_inserido;
$Aplic->checarEstilo();
$Aplic->pdf_print = getParam($_REQUEST, 'pdf', 0);
require_once ($Aplic->getClasseSistema('data'));
require_once ($Aplic->getClasseSistema('aplic'));
require_once ($Aplic->getClasseSistema('BDConsulta'));


if ($Aplic->profissional && isset($_REQUEST['login_externo']) && $_REQUEST['login_externo']==1) {
  //link externo na versão Pro
  include_once (BASE_DIR.'/codigo/login_externo_pro.php');
  if (isset($linha['usuario_externo_endereco'])){
    $m = getParam($_REQUEST, 'm', '');
    $u = getParam($_REQUEST, 'u', '');
    if($m != 'sistema' || $u != 'menu'){
      $_REQUEST['m']='projetos';
      $_REQUEST['a']='vazio';
      $_REQUEST['u']='';
      }
    }
  }

if ($Aplic->fazerLogin()){
	$m = getParam($_REQUEST, 'm', '');
	$u = getParam($_REQUEST, 'u', '');
	if($m == 'sistema' && $u == 'menu'){
		echo json_encode(array('sucess' => true, 'menu' => array()));
		session_unset();
		session_destroy();
		exit();
		}
	$Aplic->carregarPrefs(0);
	}

if (isset($usuario_id) && isset($_REQUEST['logout']))$Aplic->registrarLogout($usuario_id);

if (getParam($_REQUEST, 'perdeu_senha', 0)){
	//caso tenha perdido a senha
	$estilo_ui = 'rondon';
	$Aplic->setUsuarioLocalidade();
	@include_once BASE_DIR.'/localidades/pt/localidades.php';
	setlocale(LC_TIME, $Aplic->usuario_linguagem);
	if (getParam($_REQUEST, 'envio_senha', 0)) {
		require BASE_DIR.'/incluir/envio_senha.php';
		}
	else require BASE_DIR.'/codigo/perdeu_senha.php';
	exit();
	}



$logou = false;
if (isset($_REQUEST['login'])) {
	//foi efetuado tentativa de login
	$usuarioNome = getParam($_REQUEST, 'usuarioNome', '');
	$senha = getParam($_REQUEST, 'senha', '');
	$Aplic->setUsuarioLocalidade();
	@include_once (BASE_DIR.'/localidades/pt/localidades.php');
	$ok = $Aplic->login($usuarioNome, $senha);
	if (!$ok) $Aplic->setMsg('Login e senha não conferem ou conta inativa', UI_MSG_ERRO);
	else $Aplic->registrarLogin();
	$logou = true;
	}

$estilo_ui = 'rondon';
$m = '';
$a = '';
$u = '';

if ($Aplic->fazerLogin()) {

	//tela de login
	$Aplic->setUsuarioLocalidade();
	@include_once ('./localidades/'.$Aplic->base_localidade.'/localidades.php');
	setlocale(LC_TIME, $Aplic->usuario_linguagem);
	$redirecionar = (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) ? strip_tags(previnirXSS($_SERVER['QUERY_STRING'])) : '';
	if (strpos($redirecionar, 'logout') !== false) $redirecionar = '';
	if (isset($localidade_tipo_caract)) header('Content-type: text/html;charset='.$localidade_tipo_caract);
	require BASE_DIR.'/codigo/login.php';
	session_unset();
	session_destroy();
	exit;
	}

$Aplic->setUsuarioLocalidade();
$def_a = 'index';


$mostra_favorito=false;


if (!isset($_REQUEST['m'])) {

	if ($Aplic->profissional && isset($Aplic->usuario_prefs['favorito']) && $Aplic->usuario_prefs['favorito']) $mostra_favorito=true;

	//seleciona o módulo default
	$m =(isset($Aplic->usuario_prefs['padrao_ver_m']) && $Aplic->usuario_prefs['padrao_ver_m'] ? $Aplic->usuario_prefs['padrao_ver_m'] : $config['padrao_ver_m']);
	$def_a =(isset($Aplic->usuario_prefs['padrao_ver_a']) && $Aplic->usuario_prefs['padrao_ver_a'] ? $Aplic->usuario_prefs['padrao_ver_a'] : ($config['padrao_ver_a'] ? $config['padrao_ver_a']: 'index'));
	$tab =(isset($Aplic->usuario_prefs['padrao_ver_tab']) && $Aplic->usuario_prefs['padrao_ver_tab']!='' ? $Aplic->usuario_prefs['padrao_ver_tab'] : $config['padrao_ver_tab']);

	//caso não tenha um módulo selecionado
	if (!$Aplic->checarModulo($m, 'acesso')) {
		//sem permissão de acesso
		$m = 'publico';
		$def_a = 'bemvindo';
		}
	}
else{
  $m = $Aplic->checarNomeArquivo(getParam($_REQUEST, 'm', ''));
  }

//a é o arquivo .php desejado do módulo
$a = $Aplic->checarNomeArquivo(getParam($_REQUEST, 'a', $def_a));

//u é a subpasta do módulo
$u = $Aplic->checarNomeArquivo(getParam($_REQUEST, 'u', ''));

//variavel global para tipo de interface
$estilo_interface=(isset($config['estilo_css']) ? $config['estilo_css'] : 'classico');

//garante que não mostra o menu quando veio de link
if($m == 'sistema' && $u == 'menu' && $Aplic->login_externo){
    echo json_encode(array('sucess' => true, 'menu' => array()));
    exit();
    }

//configurações de tradução
@include_once BASE_DIR.'/localidades/pt/localidades.php';
if (file_exists(BASE_DIR.'/modulos/'.$m.'/'.($u ? $u.'/' : '').$a.'_idioma.php')) include_once BASE_DIR.'/modulos/'.$m.'/'.($u ? $u.'/' : '').$a.'_idioma.php';
setlocale(LC_TIME, $Aplic->usuario_linguagem);

//permissões no módulo

list($podeAcessar, $podeEditar, $podeAdicionar, $podeExcluir, $podeAprovar)  = listaPermissoes($m);

//tipo de caracter no header
if (!$sem_cabecalho && isset($localidade_tipo_caract)) header('Content-type: text/html;charset='.$localidade_tipo_caract);

//se houver uma classe para módulo, carregar atomaticamente
$classeModulo = $Aplic->getClasseModulo($m);
if (file_exists($classeModulo)) include_once ($classeModulo);
if ($u && file_exists(BASE_DIR.'/modulos/'.$m.'/'.$u.'/'.$u.'.class.php')) include_once BASE_DIR.'/modulos/'.$m.'/'.$u.'/'.$u.'.class.php';


//sobrecarga da classe gráfica
include BASE_DIR.'/estilo/rondon/sobrecarga.php';
ob_start();

//carregar o cabeçalho
if (!$sem_cabecalho) {
	require BASE_DIR.'/estilo/rondon/cabecalho.php';
	}
else if($Aplic->pdf_print){
	ob_clean();
	echo '<html>';
	echo '<head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo '<link rel="stylesheet" type="text/css" href="'.BASE_URL.'/estilo/rondon/imprimir_'.$config['estilo_css'].'.css" media="all" />';
	echo '<style type="text/css" media="all">@import "'.BASE_URL.'/estilo/rondon/imprimir_'.$config['estilo_css'].'.css";</style></head>';
	echo '<body>';
	}

//carregar favorito


if ($mostra_favorito) {
		$sql = new BDConsulta;
		$sql->adTabela('menu_item');
		$sql->adCampo('menu_item_parametros');
		$sql->adOnde('menu_item_id = '.(int)$Aplic->usuario_prefs['favorito']);
		$endereco=$sql->Resultado();
		$sql->limpar();
		if ($endereco) {
			include_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
			logar_externo_pagina($endereco);
			}
		}





//se houver um POST fazerSQL, executar o script equivalente

if (isset($_REQUEST['fazerSQL']) && $_REQUEST['fazerSQL']) {
	if (file_exists(BASE_DIR.'/modulos/'.$m.'/'.($u ? $u.'/' : '').$Aplic->checarNomeArquivo(getParam($_REQUEST, 'fazerSQL', null)).'.php')) require BASE_DIR.'/modulos/'.$m.'/'.($u ? $u.'/' : '').$Aplic->checarNomeArquivo($_REQUEST['fazerSQL']).'.php';
	elseif (file_exists(BASE_DIR.'/modulos/'.$m.'/'.$Aplic->checarNomeArquivo(getParam($_REQUEST, 'fazerSQL', null)).'.php')) require BASE_DIR.'/modulos/'.$m.'/'.$Aplic->checarNomeArquivo(getParam($_REQUEST, 'fazerSQL', null)).'.php';
	else require BASE_DIR.'/modulos/'.$m.'/'.$Aplic->checarNomeArquivo(getParam($_REQUEST, 'fazerSQL', null)).'.php';
	}

if (!isset($_SESSION['todas_tabs'][$m])) {
	if (!isset($_SESSION['todas_tabs'])) 	$_SESSION['todas_tabs'] = array();
	$_SESSION['todas_tabs'][$m] = array();
	$todas_tabs = &$_SESSION['todas_tabs'][$m];
	foreach ($Aplic->getModulosAtivos() as $dir => $modulo) {
		if (!$Aplic->checarModulo($dir, 'acesso')) continue;
		$modulos_tabs = $Aplic->lerArquivos(BASE_DIR.'/modulos/'.$dir.'/', '^'.$m.'_tab.*\.php');
		foreach ($modulos_tabs as $tab) {
			$nome_partes = explode('.', $tab);
			$nomeArquivo = substr($tab, 0, -4);
			if (count($nome_partes) > 3) {
				$arquivo = $nome_partes[1];
				if (!isset($todas_tabs[$arquivo])) $todas_tabs[$arquivo] = array();
				$arr = &$todas_tabs[$arquivo];
				$nome = $nome_partes[2];
				}
			else {
				$arr = &$todas_tabs;
				$nome = $nome_partes[1];
				}
			$arr[] = array('nome' => ucfirst(str_replace('_', ' ', $nome)), 'arquivo' => BASE_DIR.'/modulos/'.$dir.'/'.$nomeArquivo, 'modulo' => $dir);
			unset($arr);
			}
		}
	}
else{
    $todas_tabs = &$_SESSION['todas_tabs'][$m];
    }

if (!isset($_SESSION['todos_blocos'][$m])) {
	if (!isset($_SESSION['todos_blocos'])) $_SESSION['todos_blocos'] = array();
	$_SESSION['todos_blocos'][$m] = array();
	$todos_blocos = &$_SESSION['todos_blocos'][$m];
	foreach ($Aplic->getModulosAtivos() as $dir => $modulo) {
		if (!$Aplic->checarModulo($dir, 'acesso'))	continue;
		$modulos_blocos = $Aplic->lerArquivos(BASE_DIR.'/modulos/'.$dir.'/', '^'.$m.'_bloco.*\.php');
		foreach ($modulos_blocos as $tab) {
			$nome_partes = explode('.', $tab);
			$nomeArquivo = substr($tab, 0, -4);
			if (count($nome_partes) > 3) {
				$arquivo = $nome_partes[1];
				if (!isset($todos_blocos[$arquivo]))	$todos_blocos[$arquivo] = array();
				$arr = &$todos_blocos[$arquivo];
				$nome = $nome_partes[2];
				}
			else {
				$arr = &$todos_blocos;
				$nome = $nome_partes[1];
				}
			$arr[] = array('nome' => ucfirst(str_replace('_', ' ', $nome)), 'arquivo' => BASE_DIR.'/modulos/'.$dir.'/'.$nomeArquivo, 'modulo' => $dir);
			unset($arr);
			}
		}
	}
else{
    $todos_blocos = &$_SESSION['todos_blocos'][$m];
    }










//usuário de link externo
if (isset($config['link_externo_login']) && $config['link_externo_login'] && $usuario_externo_endereco=getParam($_REQUEST, 'usuario_externo_endereco', null)){
	$Aplic->redirecionar($usuario_externo_endereco);
	}
//carrega a página .php definida no módulo
elseif (file_exists(BASE_DIR.'/modulos/'.$m.'/'.($u ? $u.'/' : '').$a.'.php')){
    require BASE_DIR.'/modulos/'.$m.'/'.($u ? $u.'/' : '').$a.'.php';
    }
else if(file_exists(BASE_DIR.'/modulos/'.$m.'/'.$a.'.php')){
    require BASE_DIR.'/modulos/'.$m.'/'.$a.'.php';
    }
else {
	//não encontrou a página .php definida no módulo
	$botoesTitulo = new CBlocoTitulo('Aviso', 'log-error.gif');
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table width="100%" cellspacing=0 cellpadding="3" border=0 class="std"><tr><td><br>Possivelmente o aquivo modulos/'.$m.'/'.($u ? $u.'/' : '').$a.'.php'.' está faltando!<br>&nbsp;</td></tr></table>';
	echo estiloFundoCaixa();
	}


if (isset($_GET['login_externo']) && $_GET['login_externo'] && isset($linha['usuario_externo_endereco'])) {
	include_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
	logar_externo_pagina($linha['usuario_externo_endereco']);
	}

if (!$sem_cabecalho && !$dialogo) {
	//carregar o rodapé padrão da página
	require BASE_DIR.'/estilo/rondon/rodape.php';
	echo '<div id="carregandoMensagem" style="opacity:1;position: fixed; left: 50%; top: 0;display: none;"><table width="80" cellpadding="3" cellspacing="3" border=0><tr><td><b>Carregando</b></td><td>'.imagem('icones/progresso.gif', 'Carregando', 'carregando...').'</td></tr></table></div>';
	}
elseif($dialogo && !$sem_cabecalho){
    require BASE_DIR.'/estilo/rondon/rodape_minimo.php';
    }

if(!$sem_cabecalho){
    if($Aplic->profissional){
        echo '<script>';
        echo 'if(parent && parent.gpwebApp){var gpwebApp = parent.gpwebApp;}';
        echo 'if(gpwebApp){';
        if($logou){
            $dados = array( 'id' => $Aplic->usuario_id, 'nome' => utf8_encode($Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra));

            $acesso_comunicacao = ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso'));
            $dados['email_ativo'] = $acesso_comunicacao ? 1 : 0;
            if($acesso_comunicacao){
                $dados['msg_nao_lidas'] = $Aplic->mensagensNaoLidas();
                $dados['msg_total'] = $Aplic->mensagensTotalCaixaEntrada();
                $dados['msg_pendentes'] = $Aplic->mensagensTotalPendentes();
            }
            echo 'var dados_usu = '.json_encode($dados).';';
            echo 'gpwebApp.onLogin(dados_usu);';
            }
            echo 'gpwebApp.setAppVersao("'.$Aplic->getVersao().'","'.$Aplic->getVersaoJs().'");';
            echo 'gpwebApp.setCurrent('.json_encode(toUtf8($_REQUEST)).');';
            echo 'gpwebApp.module = "'.$m.'";';
            echo 'gpwebApp.sub_module = "'.$u.'";';
            echo 'gpwebApp.arquive = "'.$a.'";';
            echo 'gpwebApp.administrator = '.(($Aplic->usuario_admin || $Aplic->usuario_super_admin) ? 'true' : 'false').';';
            echo '}</script>';
        }
    echo '</body></html>';
    }

if($Aplic->pdf_print && $Aplic->profissional){
	echo '</body></html>';
  set_time_limit(0);
  ignore_user_abort(true);
	ini_set("pcre.backtrack_limit","1000000");
	$page_size = strtoupper(getParam($_REQUEST, 'page_size', 'A4'));
	$page_orientation = strtoupper(getParam($_REQUEST, 'page_orientation', 'P'));
	$pdf_file =	getParam($_REQUEST, 'pdf_file', '');
	$htmlPdf = ob_get_contents();
	ob_clean();

	define('MPDF_PATH', BASE_DIR.'/lib/mpdf/');
	//define("_MPDF_TEMP_PATH", BASE_DIR.'/arquivos/temp/');
	include(MPDF_PATH.'mpdf.php');
	$mpdf=new mPDF('utf-8', $page_size.($page_orientation == 'L' ? '-L' : ''));
    //$mpdf->cacheTables = true;
	$mpdf->showImageErrors = false;
	$mpdf->allow_charset_conversion=true;
	$mpdf->charset_in='iso-8859-1';
    $mpdf->shrink_tables_to_fit=1;
    $mpdf->allow_html_optional_endtags = true;
    $mpdf->setAutoTopMargin = 'stretch';
    $mpdf->autoMarginPadding = 0;
	$mpdf->WriteHTML($htmlPdf);
	if(!$pdf_file && $m) $pdf_file = $m.'.pdf';
	if(!$pdf_file) $pdf_file = 'gpweb.pdf';
	$mpdf->Output($pdf_file,'D');
	}
ob_end_flush();
?>