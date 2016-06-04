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

gpweb\base.php

Configura as constantes	BASE_DIR e BASE_URL

********************************************************************************************/

if (!ini_get('safe_mode')) @set_time_limit(0);

//ini_set('default_charset', 'ISO-8859-1');

//Comente as duas linhas de baixo caso não queira que o PHP exiba notificações e mensagens de erro
ini_set('display_errors', 1);
error_reporting(E_ALL);
//error_reporting(0);
//error_reporting(E_ALL & ~ E_NOTICE);

//cria as constantes BASE_DIR e BASE_URL
$baseDir = dirname(__file__);
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$baseUrl .= get_host();
$caminhoInfo = safe_get_env('PATH_INFO');
if ($caminhoInfo) $baseUrl .= str_replace('\\', '/', dirname($caminhoInfo));
else $baseUrl .= str_replace('\\', '/', dirname(safe_get_env('SCRIPT_NAME')));
$baseUrl = preg_replace('#/$#D', '', $baseUrl);
$baseUrl = str_replace('/codigo', '', $baseUrl);
define('BASE_DIR', $baseDir);
define('BASE_URL', $baseUrl);
date_default_timezone_set('America/Sao_Paulo');

function safe_get_env($nome) {
	if (isset($_SERVER[$nome])) return $_SERVER[$nome];
	elseif (strpos(php_sapi_name(), 'apache') === false) getenv($nome);
	else return '';
	}

function get_host() {
    if ($host = safe_get_env('HTTP_X_FORWARDED_HOST')){
        $elements = explode(',', $host);
        $host = trim(end($elements));
        }
    else{
        if (!($host = safe_get_env('HTTP_HOST'))){
            if (!($host = safe_get_env('SERVER_NAME'))){
                $host = safe_get_env('SERVER_ADDR');
                $host = !empty($host) ? $host : '';
                }
            }
        }

    // Remove port number from host
    //$host = preg_replace('/:\d+$/', '', $host);

    return trim($host);
    }
?>