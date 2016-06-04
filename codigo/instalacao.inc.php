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

if (!defined('BASE_DIR')) die('Você não deveria acessar diretamente este arquivo.');

if (!defined('UI_MSG_OK')) define( 'UI_MSG_OK', '');
if (!defined('UI_MSG_ALERTA')) define('UI_MSG_ALERTA', 'Alerta: ');
if (!defined('UI_MSG_AVISO')) define('UI_MSG_AVISO', 'Recomendação: ');
if (!defined('UI_MSG_ERRO')) define('UI_MSG_ERRO', 'ERRO: ');

function msg($msg){
 	echo $msg."\n";
 	flush();
	}

function instalacao_defValor($var, $def) {
 	return isset($var) ? $var : $def;
	}


function executar_php($arquivoPHP='') {
	if (!file_exists($arquivoPHP))	return;
	include_once $arquivoPHP;
	}



function instalacao_getParametro($arr, $nome, $def=null ) {
 	return isset($arr[$nome]) ? $arr[$nome] : $def;
	}


function instalacao_getParam($arr, $nome, $def = null) {

	if (is_array($nome)){
		foreach($nome as $chave => $valor){
			if (!isset($arr[$valor])) return $def;	
			else if($arr[$valor]==='null') return null;
			else $arr=$arr[$valor];
			}
		if(!is_array($arr)) return ($arr || $arr=='0' || $arr===0 ? instalacao_previnirXSS($arr) : null);
		else {
			foreach($arr as $chave => $valor1) $arr[$chave]=($valor1 || $valor1=='0' || $valor1===0 ? instalacao_previnirXSS($valor1): null);
			return $arr;
			}
		}
	
	else if (!isset($arr[$nome])) return $def;	
	else if($arr[$nome]==='null') return null;
	else if(!is_array($arr[$nome])) return ($arr[$nome] || $arr[$nome]=='0' || $arr[$nome]===0 ? instalacao_previnirXSS($arr[$nome]) : null);
	else {
		foreach($arr[$nome] as $chave => $valor) $arr[$chave]=($valor || $valor=='0' || $valor===0 ? instalacao_previnirXSS($valor): null);
		return $arr[$nome];
		}
	}

function instalacao_previnirXSS($texto){
  $ruim=true;
  $blacklist=array (
      'java',
		  'script',
		  'javascript',
		  'alert',
      'DELETE',
      'INSERT',
		  'DROP',
		  'CREATE',
		  'DATABASE',
      'SELECT',
      'ALTER',
      'UPDATE',
		  'FSCommand',
		  'onAbort',
		  'onActivate',
		  'onAfterPrint',
		  'onAfterUpdate',
		  'onBeforeActivate',
		  'onBeforeCopy',
		  'onBeforeCut',
		  'onBeforeDeactivate',
		  'onBeforeEditFocus',
		  'onBeforePaste',
		  'onBeforePrint',
		  'onBeforeUnload',
		  'onBegin',
		  'onBlur',
		  'onBounce',
		  'onCellChange',
		  'onChange',
		  'onClick',
		  'onContextMenu',
		  'onControlSelect',
		  'onCopy',
		  'onCut',
		  'onDataAvailable',
		  'onDataSetChanged',
		  'onDataSetComplete',
		  'onDblClick',
		  'onDeactivate',
		  'onDrag',
		  'onDragEnd',
		  'onDragLeave',
		  'onDragEnter',
		  'onDragOver',
		  'onDragDrop',
		  'onDrop',
		  'onEnd',
		  'onError',
		  'onErrorUpdate',
		  'onFilterChange',
		  'onFinish',
		  'onFocus',
		  'onFocusIn',
		  'onFocusOut',
		  'onHelp',
		  'onKeyDown',
		  'onKeyPress',
		  'onKeyUp',
		  'onLayoutComplete',
		  'onLoad',
		  'onLoseCapture',
		  'onMediaComplete',
		  'onMediaError',
		  'onMouseDown',
		  'onMouseEnter',
		  'onMouseLeave',
		  'onMouseMove',
		  'onMouseOut',
		  'onMouseOver',
		  'onMouseUp',
		  'onMouseWheel',
		  'onMove',
		  'onMoveEnd',
		  'onMoveStart',
		  'onOutOfSync',
		  'onPaste',
		  'onPause',
		  'onProgress',
		  'onPropertyChange',
		  'onReadyStateChange',
		  'onRepeat',
		  'onReset',
		  'onResize',
		  'onResizeEnd',
		  'onResizeStart',
		  'onResume',
		  'onReverse',
		  'onRowsEnter',
		  'onRowExit',
		  'onRowDelete',
		  'onRowInserted',
		  'onScroll',
		  'onSeek',
		  'onSelect',
		  'onSelectionChange',
		  'onSelectStart',
		  'onStart',
		  'onStop',
		  'onSyncRestored',
		  'onSubmit',
		  'onTimeError',
		  'onTrackChange',
		  'onUnload',
		  'onURLFlip',
		  'seekSegmentTime');
 
	while($ruim){ 
    $texto_final=str_ireplace($blacklist,'', $texto);
    
    if ($texto==$texto_final){
       $texto=$texto_final;
       $ruim=false;
       }
    else $texto=$texto_final;
    }
  $texto=strip_tags($texto);

	return ($texto != '' && $texto !=null && $texto!='null' ? mysql_real_escape_string($texto) : null);
	}




function instalacao_getVersao($modo, $bd) {
	$resultado = array( 'ultima_atualizacao_bd' => '', 'ultima_atualizacao_codigo' => '', 'versao_codigo' => '1.0.0', 'versao_bd' => '1');
	$res = $bd->Execute('select * FROM versao LIMIT 1');
	if ($res && $res->RecordCount() > 0) {
	  $linha = $res->FetchRow();
	  $resultado['ultima_atualizacao_bd'] = str_replace('-', '', $linha['ultima_atualizacao_bd']);
	  $resultado['ultima_atualizacao_codigo'] = str_replace('-', '', $linha['ultima_atualizacao_codigo']);
	  $resultado['versao_codigo'] = $linha['versao_codigo'] ? $linha['versao_codigo'] : '1.0.0';
	  $resultado['versao_bd'] = $linha['versao_bd'] ? $linha['versao_bd'] : '1';
	 	}
	return $resultado;
	}

function instalacao_dividirSQL($sql, $ultima_atualizacao) {
	 global $ultimaAtualizacaoBD;
	 $buffer = array();
	 $ret = array();
	 $sql = trim($sql);
	 $compativel =  preg_match_all('/\n#\s*(\d{8})\b/', $sql, $comparados);
	 if ($compativel) {
			$tamanho = count($comparados[0]);
		  $ultimaAtualizacaoBD = $comparados[1][$tamanho-1];
		 	}
	 if ($ultima_atualizacao && $ultima_atualizacao != '00000000') {
	  	msg("Checando por atualizações anteriores");
	  	if ($compativel) {
	   		for ($i = 0; $i < $tamanho; $i++) {
	    		if ((int)$ultima_atualizacao < (int)$comparados[1][$i]) {
	     			$comparar = '/^.*'.trim($comparados[0][$i]).'/Us';
	    			$sql = preg_replace($comparar, "", $sql);
	     			break;
	    			}
	   			}
	   		if ($i == $tamanho) return $ret;
	  		}
	 		}
	 $sql = preg_replace("|\\n#[^\\n]*\\n|", "\n", $sql);

	 $in_string = false;
	 for($i=0; $i<strlen($sql)-1; $i++) {
		 if($sql[$i] == ";" && !$in_string) {
			  $ret[] = substr($sql, 0, $i);
			  $sql = substr($sql, $i + 1);
			  $i = 0;
			  }
	  	if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") $in_string = false;
	  	elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) $in_string = $sql[$i];
	  	if(isset($buffer[1])) $buffer[0] = $buffer[1];
	  	$buffer[1] = $sql[$i];
	 		}
	 if(!empty($sql)) $ret[] = $sql;
	 return($ret);
	 }

function instalacao_carregarSQL($arquivoSQL, $ultima_atualizacao = null){
	 global $erroBD, $msgBD, $bd;
	 if (!file_exists($arquivoSQL))	return;
	 $mqr = false;
	 @set_magic_quotes_runtime(0);
	 $pedacos = array();
	 if ($arquivoSQL) {
		  $comando_sql = fread(fopen($arquivoSQL, "r"), filesize($arquivoSQL));
		  $pedacos  = instalacao_dividirSQL($comando_sql, $ultima_atualizacao);
		  }
	 @set_magic_quotes_runtime($mqr);
	 $erros = 0;
	 $nr_pedacos = count($pedacos);
	 for ($i=0; $i < $nr_pedacos; $i++) {
		  $pedacos[$i] = trim($pedacos[$i]);
		  if(!empty($pedacos[$i]) && $pedacos[$i] != "#") {
			   if (!$resultado = $bd->Execute($pedacos[$i])) {
				   $erros++;
				   $erroBD = true;
				   $msgBD .= $bd->ErrorMsg().'<br>';
				   }
	  		}
	 		}
	  msg('Houve '.$erros.' erros em '.$nr_pedacos.' comandos SQL no arquivo '.$arquivoSQL);
		}

class UI_instalacao {
	var $usuario_id = 0;
	function setMsg($msg, $msgno = '', $anexar=false){
		return msg($msgno.$msg);
		}
	}

?>
