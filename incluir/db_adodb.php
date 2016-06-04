<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

/********************************************************************************************

gpweb\incluir\db_adodb.php

Funções utilizadas para conectar ao banco de dados

********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $config;

require_once (BASE_DIR.'/lib/adodb/adodb.inc.php');

function conectar_bd($servidor = 'localhost', $nomeBd, $usuario = 'root', $senha = '', $persist = false) {
    global $bd, $ADODB_FETCH_MODE, $config;
    switch (strtolower(trim(config('tipoBd')))) {
      case 'oci8':
      case 'oracle':
        if ($persist) $bd->PConnect($servidor, $usuario, $senha, $nomeBd) or die('ERRO FATAL: Conexão ao  SGDB falhou.');
        else $bd->Connect($servidor, $usuario, $senha, $nomeBd) or die('ERRO FATAL: Conexão ao  SGDB falhou.');
        if (!defined('ADODB_ASSOC_CASE')) define('ADODB_ASSOC_CASE', 0);
        break;
      default:
      //mySQL
        if ($persist)    {
            if (!($bd->PConnect($servidor, $usuario, $senha, $nomeBd))){
              include_once (BASE_DIR.'/estilo/rondon/funcao_grafica.php');
              if (!isset($config['militar'])) include_once BASE_DIR.'/instalacao/config-dist.php';
              $diretorio=explode('/', dirname(safe_get_env('SCRIPT_NAME')));
              $caminho=(isset($diretorio[2]) && $diretorio[2]=='instalacao' ? '../' : '');
							echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252"><title>gpweb</title><meta http-equiv="Content-Language" content="pt-br"><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><LINK href="estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" type=text/css rel=stylesheet></head><body></script>';
							echo '<br><br><table align="center" style="width:770px; text-align:center; border-width:0px; padding:0px; border-spacing:0px;" class="std"><tr colspan="2"><td>&nbsp;</td></tr><tr><td align="center" colspan="2"><b>N&atilde;o foi poss&iacute;vel abrir o banco de dados!<b><br><br>Verifique as configura&ccedil;&otilde;es para acesso ao servidor MySQL no arquivo config.php na raiz do '.$config['gpweb'].' ou entre no menu de cria&ccedil;&atilde;o do arquivo config.php ou da base de dados.</td></tr>';
							echo '<tr><td>&nbsp;</td></tr><tr><td align="center">'.botao('menu de instala&ccedil;&atilde;o', 'Menu de Instala&ccedil;&atilde;o','pressione este bot&atilde;o para acessar o menu de instala&ccedil;&atilde;o.','','window.location=\''.str_replace('/instalacao', '', BASE_URL).'/instalacao/index.php\'').'</td></tr><tr><td>&nbsp;</td></tr>';
							echo '</table></body></html>';
							exit;
              }
            }
        else {
            if (!($bd->Connect($servidor, $usuario, $senha, $nomeBd))){
							include_once (BASE_DIR.'/estilo/rondon/funcao_grafica.php');
							if (!isset($config['militar'])) include_once BASE_DIR.'/instalacao/config-dist.php';
							$diretorio=explode('/', dirname(safe_get_env('SCRIPT_NAME')));
							$caminho=(isset($diretorio[2]) && $diretorio[2]=='instalacao' ? '../' : '');
							echo '<html><head><title>gpweb</title><meta http-equiv="Content-Language" content="pt-br"><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><LINK href="estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" type=text/css rel=stylesheet></head><body></script>';
							echo '<br><br><table align="center" style="width:770px; text-align:center; border-width:0px; padding:0px; border-spacing:0px;" class="std"><tr colspan="2"><td>&nbsp;</td></tr><tr><td align="center" colspan="2"><b>N&atilde;o foi poss&iacute;vel abrir o banco de dados!<b><br><br>Verifique as configura&ccedil;&otilde;es para acesso ao servidor MySQL no arquivo config.php na raiz do sistema ou entre no menu de cria&ccedil;&atilde;o do arquivo config.php ou da base de dados.</td></tr>';
							echo '<tr><td>&nbsp;</td></tr><tr><td align="center">'.botao('menu de instala&ccedil;&atilde;o', 'Menu de Instala&ccedil;&atilde;o','pressione este bot&atilde;o para acessar o menu de instala&ccedil;&atilde;o.','','window.location=\''.str_replace('/instalacao', '', BASE_URL).'/instalacao/index.php\'').'</td></tr><tr><td>&nbsp;</td></tr>';
							echo '</table></body></html>';
							exit;
							}
            }
        }
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    }

function db_error() {
    global $bd;
    if (!is_object($bd)) dprint(__file__, __line__, 0, 'Objeto do banco de dados não existe.');
    return $bd->ErrorMsg();
    }

function db_errno() {
    global $bd;
    if (!is_object($bd)) dprint(__file__, __line__, 0, 'Objeto do banco de dados não existe.');
    return $bd->ErrorNo();
    }

function db_insert_id($tabela='',$coluna='') {
    global $bd;
    if (!is_object($bd)) dprint(__file__, __line__, 0, 'Objeto do banco de dados não existe.');
    return $bd->Insert_ID($tabela, $coluna);
    }

function db_exec($sql) {
    global $bd, $performance_dbtime, $performance_consultas_antigas;
    if (!is_object($bd)) dprint(__file__, __line__, 0, 'Objeto do banco de dados não existe.');
    $qid = $bd->Execute($sql);
    dprint(__file__, __line__, 10, $sql);
    if ($msg = db_error()) {
        global $Aplic;
        dprint(__file__, __line__, 0, "Erro ao executar: <pre>$sql</pre>");
        $bd->Execute($sql);
        if (!db_error()) echo '<script language="JavaScript"> location.reload(); </script>';
        }
    if (!$qid && preg_match('/^\<select\>/i', $sql)) dprint(__file__, __line__, 0, $sql);
    return $qid;
    }

function db_free_result($cur) {
    if (!is_object($cur)) dprint(__file__, __line__, 0, 'Objeto inválido passado para db_free_result.');
    $cur->Close();
    }

function db_num_rows($qid) {
    if (!is_object($qid)) dprint(__file__, __line__, 0, 'Objeto inválido passado para db_num_rows.');
    return $qid->RecordCount();
    }

function db_fetch_row(&$qid) {
    if (!is_object($qid))    dprint(__file__, __line__, 0, 'Objeto inválido passado para db_fetch_row.');
    return $qid->FetchRow();
    }

function db_fetch_assoc(&$qid) {
    if (!is_object($qid)) dprint(__file__, __line__, 0, 'Objeto inválido passado para db_fetch_assoc.');
    return $qid->FetchRow();
    }

function db_fetch_array(&$qid) {
    if (!is_object($qid))    dprint(__file__, __line__, 0, 'Objeto inválido passado para db_fetch_array.');
    $resultado = $qid->FetchRow();
    if ($resultado && !isset($resultado[0])) {
        $ak = array_keys($resultado);
        foreach ($ak as $k => $v) $resultado[$k] = $resultado[$v];
        }
    return $resultado;
    }

function db_fetch_object($qid) {
    if (!is_object($qid)) dprint(__file__, __line__, 0, 'Objeto inválido passado para db_fetch_object.');
    return $qid->FetchNextObject(false);
    }

function db_escape($str) {
    global $bd;
    return substr($bd->qstr($str), 1, -1);
    }

function versao_bd() {
    return 'ADODB';
    }

function db_unix2dateTime($time) {
    global $bd;
    return $bd->DBDate($time);
    }

function db_dateTime2unix($time) {
    global $bd;
    return $bd->UnixDate($time);
    }


function db_multiplo_exit($erro=''){
	global $config;
    $m = isset($_REQUEST['m']) ? getParam($_REQUEST, 'm', null) : '';
    $u = isset($_REQUEST['u']) ? getParam($_REQUEST, 'u', null) : '';
    if($m == 'sistema' && $u == 'menu'){
        echo json_encode(array('sucess' => true, 'menu' => array()));
        exit();
        }
    else{
        ini_set('default_charset', 'ISO-8859-1');
        require_once BASE_DIR.'/estilo/rondon/funcao_grafica.php';
        echo '<html><head><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico">';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
        echo '<link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css"></head><body>';
        echo $erro;
        echo '<br><br><br>';
        echo '<table width="600" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td>'.estiloTopoCaixa().'</td></tr><tr><td>';
        echo '<table cellspacing=0 cellpadding="6" border=0 class="std" width="100%" align="center">';
        echo '<tr><td align="center"><h1>Desculpe, mas os dados informados não são válidos.</h1></td><tr>';
        echo '<tr><td align="center">Caso considere isto um erro entre em contato com a Sistema GP-Web LTDA-ME pelo e-mail: <a href="mailto:suporte@sistemagpweb.com.br?subject=Problema de acesso ao sistema">suporte@sistemagpweb.com.br</a></br>Informe o ocorrido juntamente com os dados do cliente ao qual esta registrada a assinatura do sistema.<br/>Obrigado!</td></tr></table></td></tr>';
        echo '<tr><td>'.estiloFundoCaixa().'</td></tr></table>';
        echo '</body></html>';
        exit();
        }
    }

$_GPWEB_MULTIPLO_INSTANCIA = false;

$tokenCookie = isset($_COOKIE['gpweb_token']) ? getParam($_COOKIE,'gpweb_token', '') : false;
$tokenSession = isset($_REQUEST['gpw']) ? getParam($_REQUEST,'gpw', '') : false;

if(isset($config['multiplo']) && $config['multiplo'] ){
    if(!$tokenSession && !$tokenCookie){
        db_multiplo_exit('erro 1');
        }
    else{
        $_GPWEB_MULTIPLO_INSTANCIA = $tokenSession ? strip_tags($tokenSession) : strip_tags($tokenCookie);
        }
    }

if($_GPWEB_MULTIPLO_INSTANCIA !== false){
    if(!isset($_SESSION['gpweb_multiplo']) || !isset($_SESSION['gpweb_token']) || $_SESSION['gpweb_token'] !== $_GPWEB_MULTIPLO_INSTANCIA){
        $bd = NewADOConnection(config('tipoBd'));
        conectar_bd(config('hospedadoBd'), config('nomeBd'), config('usuarioBd'), config('senhaBd'), config('persistenteBd'));
        $sql = "select srv.* FROM ".config('prefixoBd')."servidores AS srv,".config('prefixoBd')."clientes AS cl WHERE srv.servidor_id = cl.cliente_servidor_id AND cl.cliente_chave = '".$_GPWEB_MULTIPLO_INSTANCIA."' AND cl.cliente_ativo > 0";
        $rs = $bd->Execute($sql);
        if ($rs) {
            $rsArr = $rs->GetArray();
            if(!empty($rsArr)){
                $rsArr = $rsArr[0];
                $config['hospedadoBd'] = $rsArr['servidor_database_url'];
                $config['nomeBd'] = $rsArr['servidor_prefixo'].$_GPWEB_MULTIPLO_INSTANCIA;
                $_SESSION['gpweb_token'] = $_GPWEB_MULTIPLO_INSTANCIA;
                $_SESSION['gpweb_multiplo'] = array('hospedadoBd' => $config['hospedadoBd'], 'nomeBd' => $config['nomeBd']);
                }
            else{
                db_multiplo_exit('erro 2');
                }
            $rs->Close();
            $bd->Close();
            }
        else{
            db_multiplo_exit('erro 3');
            }
        }
    else{
        $config['hospedadoBd'] = $_SESSION['gpweb_multiplo']['hospedadoBd'];
        $config['nomeBd'] = $_SESSION['gpweb_multiplo']['nomeBd'];

        }
        setcookie('gpweb_token',$_GPWEB_MULTIPLO_INSTANCIA);
    }

$bd = NewADOConnection(config('tipoBd'));
conectar_bd(config('hospedadoBd'), config('nomeBd'), config('usuarioBd'), config('senhaBd'), config('persistenteBd'));
$bd->Execute('SET NAMES latin1;');
//$bd->Execute('SET CHARACTER SET latin1;');
$bd->Execute('SET character_set_connection latin1;');
//não usa ANSI mode
$bd->Execute("SET sql_mode := ''");

$sql = 'select config_nome, config_valor, config_tipo FROM '.config('prefixoBd').'config';
$rs = $bd->Execute($sql);
if ($rs) {
    $rsArr = $rs->GetArray();
    switch (strtolower(trim(config('tipoBd')))) {
        case 'oci8':
        case 'oracle':
            foreach ($rsArr as $c) {
                if ($c['CONFIG_TYPE'] == 'checkbox') $c['CONFIG_value'] = ($c['CONFIG_values'] == 'true') ? true : false;
                $config[$c['CONFIG_NAME']] = $c['CONFIG_value'];
                }
            break;
        default:
        //mySQL
            foreach ($rsArr as $c) {
                if ($c['config_tipo'] == 'checkbox') $c['config_valor'] = ($c['config_valor'] == 'true') ? true : false;
                $config[$c['config_nome']] = $c['config_valor'];
                }
        }
    }
?>