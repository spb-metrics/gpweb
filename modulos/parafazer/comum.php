<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

function htmlarray($a, $exclude=null){
	htmlarray_ref($a, $exclude);
	return $a;
}

function htmlarray_ref(&$a, $exclude=null){
	global $localidade_tipo_caract;
	if(!$a) return;
	if(!is_array($a)) {
		$a = htmlspecialchars($a, ENT_COMPAT, $localidade_tipo_caract);
		return;
	}
	reset($a);
	if($exclude && !is_array($exclude)) $exclude = array($exclude);
	foreach($a as $k=>$v){
		if(is_array($v)) $a[$k] = htmlarray($v, $exclude);
		elseif(!$exclude) $a[$k] = htmlspecialchars($v, ENT_COMPAT, $localidade_tipo_caract);
		elseif(!in_array($k, $exclude)) $a[$k] = htmlspecialchars($v, ENT_COMPAT, $localidade_tipo_caract);
	}
	return;
}

function stop_gpc(&$arr){
	if (!is_array($arr)) return 1;
	
	return 1;
	
	reset($arr);
	foreach($arr as $k=>$v){
		if(is_array($arr[$k])) stop_gpc($arr[$k]);
		elseif(is_string($arr[$k])) $arr[$k] = stripslashes($v);
	}

	return 1;
}
function _post($param,$defvalue = ''){
	if(!isset($_REQUEST[$param])){
		return $defvalue;
	}
	else {
		return $_REQUEST[$param];
	}
}

function _get($param,$defvalue = '')
{
	if(!isset($_REQUEST[$param])) {
		return $defvalue;
	}
	else {
		return $_REQUEST[$param];
	}
} 

class Config {
	public static $params = array(
		'db' => array('default'=>'sqlite', 'type'=>'s'),
		'hospedadoBd' => array('default'=>'localhost', 'type'=>'s'),
		'nomeBd' => array('default'=>'mytinytodo', 'type'=>'s'),
		'usuarioBd' => array('default'=>'user', 'type'=>'s'),
		'senhaBd' => array('default'=>'', 'type'=>'s'),
		'titulo' => array('default'=>'', 'type'=>'s'),
		'lang' => array('default'=>'en', 'type'=>'s'),
		'password' => array('default'=>'', 'type'=>'s'),
		'allowread' => array('default'=>0, 'type'=>'i'),
		'smartsyntax' => array('default'=>1, 'type'=>'i'),
		'autotz' => array('default'=>1, 'type'=>'i'),
		'autotag' => array('default'=>1, 'type'=>'i'),
		'datafinalformat' => array('default'=>1, 'type'=>'i'),
		'primeiroDiaSemana' => array('default'=>1, 'type'=>'i'),
		'session' => array('default'=>'files', 'type'=>'s', 'options'=>array('files','default')),
		'clock' => array('default'=>24, 'type'=>'i', 'options'=>array(12,24)),
		'dateformat' => array('default'=>'j M Y', 'type'=>'s'),
		'dateformatshort' => array('default'=>'j M', 'type'=>'s'),
	);

	public static function save($config){
		$s = '';
		foreach(self::$params as $param => $v){
			if(!isset($config[$param])) $val = $v['default'];
			elseif(isset($v['options']) && !in_array($config[$param], $v['options'])) $val = $v['default'];
			else $val = $config[$param];
			if($v['type']=='i') $s .= "\$config['$param'] = ".(int)$val.";\n";
			else $s .= "\$config['$param'] = '".str_replace(array("\\","'"),array("\\\\","\\'"),$val)."';\n";
			}
		$f = fopen('./db/config.php', 'w');
		if($f === false) throw new Exception("Error while saving config file");
		fwrite($f, "<?php\n\$config = array();\n$s?>");
		fclose($f);
		}

	public static function get($key, $config)	{
		if(isset($config[$key])) return $config[$key];
		elseif(isset(self::$params[$key])) return self::$params[$key]['default'];
		else return null;
		}
	}

function formatarData3($format, $ay, $am, $ad){
	# F - month long, M - month short
	# m - month 2-digit, n - month 1-digit
	# d - day 2-digit, j - day 1-digit
	$ml = array('Janeiro', 'Fevereiro','Março','Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
	$ms = array("Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez");
	$Y = $ay;
	$n = $am;
	$m = $n < 10 ? '0'.$n : $n;
	$F = $ml[$am-1];
	$M = $ms[$am-1];
	$j = $ad;
	$d = $j < 10 ? '0'.$j : $j;
	return str_replace(
		array('Y','F','M','n','m','d','j'),
		array($Y, $F, $M, $n, $m, $d, $j),
		$format);
}

?>