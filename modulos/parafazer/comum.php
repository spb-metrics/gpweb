<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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
	$ml = array('Janeiro', 'Fevereiro','Mar�o','Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
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