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


if (!ini_get('safe_mode')){
    @set_time_limit(0);
    @ignore_user_abort(true);
    }

require_once '../base.php';

if (is_file('../config.php')) require_once '../config.php';
if (!isset($config['militar'])) require_once 'config-dist.php';

include_once 'checar_atualizar.php';
require_once BASE_DIR.'/estilo/rondon/funcao_grafica.php';
require_once BASE_DIR.'/incluir/funcoes_principais.php';


$tipoCia = (isset($_REQUEST['tipoCia']) ? instalacao_getParametro($_REQUEST, 'tipoCia', null)  : $config['militar']);
$militar=$tipoCia;

checarDBMultiplo($config);

$profissional=file_exists(BASE_DIR.'/instalacao/sql/gpweb_'.$config['tipoBd'].'_pro.sql');

if ($_REQUEST['modo'] == 'instalar' && checarAtualizacao($config) == 'atualizar') die ('Checagem de Seguran�a: O gpweb aparentemente j� est� configurado.Configura��o cancelada! ');
$baseUrl = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$baseUrl .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
$baseUrl .= isset($_SERVER['SCRIPT_NAME']) ? dirname(dirname(previnirXSS($_SERVER['SCRIPT_NAME']))) : dirname(dirname(getenv('SCRIPT_NAME')));
require_once BASE_DIR.'/codigo/instalacao.inc.php';
$msgBD = '';
$msgCArquivo = 'N�o Criado';
$erroBD = false;
$erroCArquivo = false;

$atualizacao = instalacao_getParametro($_REQUEST, 'modo', 'atualizar');

if (!$atualizacao){
	$dbdrop = instalacao_getParametro($_REQUEST, 'dbdrop', false);
	$fazer_bd =instalacao_getParametro($_REQUEST, 'fazer_bd', null);
	$fazer_bd_cfg = instalacao_getParametro($_REQUEST, 'fazer_bd_cfg', null);
	$fazer_cfg = instalacao_getParametro($_REQUEST, 'fazer_cfg', null);
	$tipoBd = trim(instalacao_getParametro( $_REQUEST, 'tipoBd', 'mysql'));
	$hospedadoBd = trim(instalacao_getParametro( $_REQUEST, 'hospedadoBd', ''));
	$nomeBd = trim(instalacao_getParametro( $_REQUEST, 'nomeBd', ''));
	$prefixoBd = trim(instalacao_getParametro( $_REQUEST, 'prefixoBd', ''));
	$usuarioBd = trim( instalacao_getParametro( $_REQUEST, 'usuarioBd', 'root'));
	$senhaBd = trim(instalacao_getParametro( $_REQUEST, 'senhaBd', ''));
	$persistenteBd = instalacao_getParametro($_REQUEST, 'persistenteBd', false);
	$exemplo = instalacao_getParametro($_REQUEST, 'exemplo', false);
	$treino = instalacao_getParametro($_REQUEST, 'treino', false);
	$areas = instalacao_getParametro($_REQUEST, 'areas', false);
	$restrito = instalacao_getParametro($_REQUEST, 'restrito', false);
	$tem_data_limite = instalacao_getParametro($_REQUEST, 'tem_data_limite', false);
	$data = instalacao_getParametro($_REQUEST, 'data', false);

	$config = array('tipoBd' => $tipoBd, 'hospedadoBd' => $hospedadoBd, 'nomeBd' => $nomeBd, 'prefixoBd' => $prefixoBd, 'usuarioBd' => $usuarioBd, 'senhaBd' => $senhaBd, 'persistenteBd' => $persistenteBd);
	}
else{
	$hospedadoBd=$config['hospedadoBd'];
	$usuarioBd=$config['usuarioBd'];
	$senhaBd=$config['senhaBd'];
	$nomeBd=$config['nomeBd'];
	$tipoBd=$config['tipoBd'];
	$fazer_bd=1;
	$fazer_bd_cfg=0;
	$fazer_cfg=0;
	$exemplo=0;
	$treino=0;
	$areas=0;
	$restrito=0;
	}

$caminhoVersao = array('1.0.0');

require_once( BASE_DIR.'/lib/adodb/adodb.inc.php');
@include_once BASE_DIR.'/incluir/versao.php';
$bd = NewADOConnection($tipoBd);
if(!empty($bd)) {
  $dbc = $bd->Connect($hospedadoBd,$usuarioBd,$senhaBd);
  if ($dbc) $bd_existente = $bd->SelectDB($nomeBd);
	}
else $dbc = false;
$bd->Execute('SET NAMES latin1;');
$bd->Execute('SET CHARACTER SET latin1;');
$bd->Execute('SET character_set_connection latin1;');
//n�o usa ANSI mode
$bd->Execute("SET sql_mode := ''");
$versao_atual = $_versao_maior.'.'.$_versao_menor.'.'.$_versao_revisao;

$localidade_tipo_caract='iso-8859-1';
header("Content-Type: text/html; charset=ISO-8859-1", true);
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
echo '<head>';
echo '<meta name="Description" content="gpweb Default Style" />';
echo '<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
echo '<link rel="stylesheet" type="text/css" href="../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" media="all" />';
echo '<style type="text/css" media="all">@import "../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css";</style>';
echo '<link rel="shortcut icon" href="../estilo/rondon/imagens/organizacao/'.(isset($config['militar']) && $config['militar']==11 ? 11 : 10).'/favicon.ico" type="image/ico" />';
echo '<script type="text/javascript" src="../lib/mootools/mootools.js"></script>';
echo '</head>';
echo '<body>';
echo '<table width="100%" cellspacing=0 cellpadding=0 border=0><tr><td align=center>'.dica('Site do Sistema', 'Clique para entrar no site oficial do Sistema.').'<a href="http://www.sistemagpweb.com" target="_blank"><img border=0 alt="gpweb" src="../estilo/rondon/imagens/organizacao/'.(isset($config['militar']) && $config['militar']==11 ? 11 : 10).'/gpweb_logo.png"/></a>'.dicaF().'</td></tr><tr><td>&nbsp;</td></tr></table>';
echo '<table width="95%" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td colspan="2">'.estiloTopoCaixa('100%','../').'</td></tr><tr><td>';
echo '<table cellspacing="6" cellpadding="3" border=0 class="std" align="center" width="100%">';
echo '<tr><td><h1>Instala��o do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</h1></td></tr>';
echo '<tr class="title"><td>Progresso:</td></tr>';
echo '<tr><td><pre>';
if (($fazer_bd || $fazer_bd_cfg)) {
	if($dbc){
  	if (!$atualizacao){
	  	msg('Excluindo a base de dados anterior, caso exista.');
	   	$bd->Execute('DROP DATABASE IF EXISTS `'.$nomeBd.'`');
		 	$bd_existente = false;
			msg('Criando uma nova base de dados');
			$bd->Execute('CREATE DATABASE `'.$nomeBd.'` DEFAULT CHARACTER SET latin1 DEFAULT COLLATE latin1_swedish_ci');
	    $erroBd = $bd->ErrorNo();
	    if ($erroBd != 0 && $erroBd != 1007) {
	     	$erroBD = true;
	      $msgBD .= 'Um erro no Banco de Dados ocorreu. Base de dados n�o foi criada! Os par�metros para a base de dados provavelmente est�o incorretos.<br>'.$bd->ErrorMsg().'<br>';
				}
			}

	 	$bd->Execute('USE `'.$nomeBd .'`');
	 	if (!$atualizacao){
		  msg('Instalando a base de dados');
		  instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/gpweb_'.$tipoBd.'.sql');
		  if ($profissional) instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/gpweb_'.$tipoBd.'_pro.sql');

		  instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/gpweb_dados_basicos.sql');
		  if ($profissional) instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/gpweb_dados_basicos_pro.sql');

		  executar_php(BASE_DIR.'/instalacao/sql/gpweb_'.$tipoBd.'.php');
		  if ($profissional) executar_php(BASE_DIR.'/instalacao/sql/gpweb_'.$tipoBd.'_pro.php');

		 	if ($exemplo) {
		 		msg('Instalando os dados de exemplo');
		 		instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/gpweb_exemplo.sql');
		 		if ($profissional) instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/gpweb_exemplo_pro.sql');
		 		}

			if ($treino) {
		 		msg('Instalando os dados para treino');
		 		instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/gpweb_treino.sql');

		 		}

		 	if ($areas) {
		 		msg('Instalando a base geogr�fica de estados e munic�pios');
		 		instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/gpweb_estados.sql');
		 		instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/gpweb_municipios.sql');
		 		}

		  msg('Configurando para a organiza��o selecionada');
		  instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/configurar_'.$tipoCia.'.sql');

			$erroBd = $bd->ErrorNo();
			if ($erroBd != 0 && $erroBd != 1007) {
			  $erroBD = true;
			  $msgBD .= 'Um erro no Banco de Dados ocorreu. Base de dados n�o foi povoada com dados corretamente!<br>'.$bd->ErrorMsg().'<br>';
			  }
			}
		else{
			require_once '../base.php';
			require_once BASE_DIR.'/config.php';
			if (!isset($GLOBALS['OS_WIN'])) $GLOBALS['OS_WIN'] = (stristr(PHP_OS, 'WIN') !== false);
			require_once BASE_DIR.'/incluir/db_adodb.php';
			require_once BASE_DIR.'/classes/BDConsulta.class.php';

			msg('Atualizando os dados');
			//checar quantas vers�es do BD exiem para atualizar
			$erros='';

			while ($atualizacao< $versao_bd) {
				++$atualizacao;
				instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/atualizar_bd_'.$tipoBd.'_'.$atualizacao.'.sql');
				if ($profissional) instalacao_carregarSQL(BASE_DIR.'/instalacao/sql/atualizar_bd_'.$tipoBd.'_'.$atualizacao.'_pro.sql');

				$erroBd = $bd->ErrorNo();

				executar_php(BASE_DIR.'/instalacao/sql/atualizar_bd_'.$tipoBd.'_'.$atualizacao.'.php');
				if ($profissional) executar_php(BASE_DIR.'/instalacao/sql/atualizar_bd_'.$tipoBd.'_'.$atualizacao.'_pro.php');

				if ($erroBd != 0 && $erroBd != 1007) {
				  $erroBD = true;
				  $erros.=$bd->ErrorMsg();
					}
				}
			if ($erroBD) $msgBD .= 'Um erro no Banco de Dados ocorreu. Base de dados n�o foi povoada com dados corretamente!<br>'.$erros.'<br>';
			}
	 	if ($erroBD) $msgBD = 'Configura��o da Base de Dados incompleta - os seguintes erros ocorreram:<br>'.$msgBD;
		else $msgBD = 'Base de Dados configurada com sucesso<br>';
		}
	else {
		$msgBD = 'N�o criado';
		if (! $dbc) {
			$erroBD=1;
			$msgBD .= '<br/>Nenhuma conex�o ao Banco de Dados dispon�vel! ' .($bd ? $bd->ErrorMsg() : '');
			}
		}
	}

if ($fazer_bd_cfg || $fazer_cfg){
	msg('Criando arquivo de configura��o');
	$arq_config = '<?php '."\n";
	$arq_config .= '/******** Configura��o do Banco de Dados MySQL ********/ '. "\n";
	$arq_config .= '$config[\'tipoBd\'] = \''.$tipoBd.'\';'."\n";
	$arq_config .= '$config[\'hospedadoBd\'] = \''.$hospedadoBd.'\';'."\n";
	$arq_config .= '$config[\'nomeBd\'] = \''.$nomeBd.'\';'."\n";
	$arq_config .= '$config[\'prefixoBd\'] = \''.$prefixoBd.'\';'."\n";
	$arq_config .= '$config[\'usuarioBd\'] = \''.$usuarioBd.'\';'."\n";
	$arq_config .= '$config[\'senhaBd\'] = \''.$senhaBd.'\';'."\n";
	$arq_config .= '$config[\'persistenteBd\'] = '.($persistenteBd ? 'true' : 'false').";\n";
	$arq_config .= '$config[\'militar\'] = '.$tipoCia.';'."\n";
	if ($exemplo) $arq_config .= '$config[\'exemplo\'] = 1;'."\n";
	if ($restrito) $arq_config .= '$config[\'restrito\'] = 1;'."\n";
	if ($tem_data_limite) $arq_config .= '$config[\'data_limite\'] = \''.$data.'\';'."\n";

	$arq_config .= '?>';
	$arq_config = trim($arq_config);
	if ($fazer_cfg || $fazer_bd_cfg){
	 if ((is_writable('../config.php')  || ! is_file('../config.php') ) && ($fp = fopen('../config.php', 'w'))){
		  fputs( $fp, $arq_config, strlen($arq_config));
		  fclose( $fp );
		  $msgCArquivo = 'Arquivo de Configura��o criado com sucesso'."\n";
			}
		else{
		  $erroCArquivo = true;
		  $msgCArquivo = 'Arquivo de Configura��o n�o pode ser criado'."\n";
		 	}
		}
	}
echo '</pre></td></tr>';
echo '</table></td></tr>';
echo '<tr><td>'.estiloFundoCaixa('100%','../').'</td></tr></table>';
echo '<table width="95%" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td>'.estiloTopoCaixa('100%','../').'</td></tr><tr><td>';
echo '<table cellspacing="6" cellpadding="3" border=0 class="std" align="center" width="100%">';
if ($fazer_bd || $fazer_bd_cfg) echo '<tr><td class="title" valign="top" width="440" align="right">Resultado da '.($atualizacao ? 'atualiza��o' : 'instala��o').' da base de dados:</td><td align="left"><b style="color:'.($erroBD ? 'red' : 'green').'">'.$msgBD.'</b>'.($erroBD ? '<br />Por favor entenda que erros relacionados a indices quebrados durante atualiza��o s�o <b>normais</b> e n�o indicam um problema.' : '').'</td></tr>';
if ($fazer_bd_cfg || $fazer_cfg) echo '<tr><td class="title" align="right">Resultado da cria��o do arquivo de configura��o:</td><td align="left"><b style="color:'.($erroCArquivo ? 'red' : 'green').'">'.$msgCArquivo.'</b></td></tr>';
if(($fazer_cfg || $fazer_bd_cfg) && $erroCArquivo){
	echo '<tr><td  align="left" colspan="2">O seguinte conte�do dever� ir para gpweb'.(file_exists('../modulos/projetos/tarefa_cache.class_pro.php') ?'/server':'').'/<b>config.php</b>. Crie este arquivo manualmente e cole as linhas de baixo � m�o. Exclua todas as linhas em branco ap�s \'?>\' e salve. Este arquivo dever� poder ser lido pelo Servidor Web.</td></tr>';
	echo '<tr><td align="center" colspan="2"><textarea class="botao" name="hospedadoBd" cols="100" rows="20" title="Conte�do de config.php para cria��o manual." />'.$msg.$config.'</textarea></td></tr>';
	}
echo '<tr><td  align="center" colspan="2"><br/><b><span id="" title="Login::Clique neste link para ir diretamente � tela inicial de login do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'."><a href="../'.(file_exists('../modulos/projetos/tarefa_cache.class_pro.php') ?'../':'').'index.php">Login e configurar as op��es do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</a></span></b></td></tr>';
if (!$atualizacao) echo '<tr><td  align="center" colspan="2"><p>O login do Administrador foi configurado para <b>admin</b> com a senha <b>123456</b>. Sugerimos mudar esta senha ap�s efetuar seu primeiro login.</p></td></tr>';



if ($exemplo) echo '<tr><td align="center" colspan="2"><p>Todos os usu�rios do banco de dados exemplo tamb�m est�o configurados com a senha <b>123456</b>.</p></td></tr>';
echo '</table></td></tr>';
echo '<tr><td>'.estiloFundoCaixa('100%','../').'</td></tr></table>';
echo '<script type="text/javascript">window.addEvent(\'domready\', function(){var as = []; $$(\'span\').each(function(span){if (span.getAttribute(\'title\')) as.push(span);});new Tips(as), {	}});</script>';
echo '</body></html>';


function rrmdir($dir) {
   if (is_dir($dir)) {
  	$objects = scandir($dir);
   	foreach ($objects as $object) {
     	if ($object != "." && $object != "..") {
	      if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object);
	      else unlink($dir."/".$object);
     		}
   		}
    reset($objects);
    rmdir($dir);
   	}
 	}
?>
