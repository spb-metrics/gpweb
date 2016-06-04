<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\incluir\conectar_bd.php		

Funções complementares à db_adodb utilizadas para conectar ao banco de dados
																																												
********************************************************************************************/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once BASE_DIR.'/incluir/db_adodb.php';
conectar_bd(config('hospedadoBd'), config('nomeBd'), config('usuarioBd'), config('senhaBd'), config('persistenteBd'));
$bd->Execute("SET sql_mode := ''");
$sql = 'SELECT config_nome, config_valor, config_tipo FROM config';
$rs = $bd->Execute($sql);
if ($rs) { 
	$rsArr = $rs->GetArray();
	foreach ($rsArr as $c) {
		if ($c['config_tipo'] == 'checkbox') $c['config_valor'] = ($c['config_valor'] == 'true') ? true : false;
		$config[$c['config_nome']] = $c['config_valor'];
		}
	}

function bd_carregarResultado($sql) {
	$cur = db_exec($sql);
	if (!($cur)) exit(db_error());
	$ret = null;
	if ($linha = db_fetch_row($cur)) $ret = $linha[0];
	db_free_result($cur);
	return $ret;
	}

function bd_carregarObjeto($sql, &$objeto, $unirTudo=false , $tira = true) {
	if ($objeto != null) {
		$hash = array();
		if(!(bd_Linha($sql, $hash)))	return false;
		unirLinhaAoObjeto($hash, $objeto, null, $tira, $unirTudo);
		return true;
		} 
	else {
		$cur = db_exec($sql);
		if (!($cur)) exit(db_error());
		$objeto = db_fetch_object($cur);
		if ($objeto) db_free_result($cur);
		else $objeto = null;
		return (($objeto) ? true : false);
		}
	}

function bd_Linha($sql, &$hash) {
	$cur = db_exec($sql);
	if (!($cur)) exit(db_error());
	$hash = db_fetch_assoc($cur);
	db_free_result($cur);
	return ((!($hash)) ? false : true);
	}

function bd_ListaChave($sql, $indice='') {
	$cur = db_exec($sql);
	if (!($cur)) exit(db_error());
	$listaLinha = array();
	while ($hash = db_fetch_array($cur)) $listaLinha[$hash[(($indice) ? $indice : 0)]] = $indice ? $hash : $hash[1];
	db_free_result($cur);
	return $listaLinha;
	}

function bd_Lista($sql, $maxLinhas=NULL) {
	GLOBAL $Aplic;
	if (!($cur = db_exec($sql))) {
		$Aplic->setMsg(db_error(), UI_MSG_ERRO);
		return false;
		}
	$lista = array();
	$cnt = 0;
	while ($hash = db_fetch_assoc($cur)) {
		$lista[] = $hash;
		if($maxLinhas && $maxLinhas == $cnt++) break;
		}
	db_free_result($cur);
	return $lista;
	}

function bd_carregarColuna($sql, $maxLinhas=NULL) {
	GLOBAL $Aplic;
	if (!($cur = db_exec($sql))) {;
		$Aplic->setMsg(db_error(), UI_MSG_ERRO);
		return false;
		}
	$lista = array();
	$cnt = 0;
	$linha_index = null;
	while ($linha = db_fetch_row($cur)) {
		if (!(isset($linha_index))) {
			if (isset($linha[0])) $linha_index = 0;
			else {
				$linha_indices = array_keys($linha);
				$linha_index = $linha_indices[0];
				}
			}
		$lista[] = $linha[$linha_index];
		if($maxLinhas && $maxLinhas == $cnt++) break;
		}
	db_free_result($cur);
	return $lista;
	}

function bd_carregarListaObjeto($sql, $objeto, $maxLinhas = NULL) {
	$cur = db_exec($sql);
	if (!($cur)) die('bd_carregarListaObjeto : '.db_error());
	$lista = array();
	$cnt = 0;
	$linha_index = null;
	while ($linha = db_fetch_array($cur)) {
		if (!(isset($linha_index))) {
			if (isset($linha[0]))
				$linha_index = 0;
			else {
				$linha_indices = array_keys($linha);
				$linha_index = $linha_indices[0];
				}
			}
		$objeto->load($linha[$linha_index]);
		$lista[] = $objeto;
		if($maxLinhas && $maxLinhas == $cnt++) break;
		}
	db_free_result($cur);
	return $lista;
	}

function bd_inserirVetor($tabela, &$hash, $verboso=false) {
	$fmtsql = "INSERT INTO $tabela (%s) values(%s) ";
	foreach ($hash as $k => $v) {
		if (is_array($v) || is_object($v) || $v == NULL) continue;
		$campos[] = $k;
		$valores[] = "'".db_escape($v)."'";
		}
	$sql = sprintf($fmtsql, implode(',', $campos) ,  implode(',', $valores));
	if ($verboso)	print "$sql<br />\n";
	if (!(db_exec($sql))) return false;
	//$id = db_insert_id();
	return true;
	}

function bd_atualizarVetor($tabela, &$hash, $chaveNome, $verboso=false) {
	$fmtsql = "UPDATE $tabela SET %s WHERE %s";
	foreach ($hash as $k => $v) {
		if(is_array($v) || is_object($v) || $k[0] == '_')	continue;
		if($k == $chaveNome) { 
			$onde = "$chaveNome='".db_escape($v)."'";
			continue;
			}
		$val = (($v == '') ? 'NULL' : ("'".db_escape($v)."'"));
		$tmp[] = "$k=$val";
		}
	$sql = sprintf($fmtsql, implode(',', $tmp) , $onde);
	if ($verboso) print "$sql<br />\n";
	$ret = db_exec($sql);
	return $ret;
	}

function bd_excluir($tabela, $chaveNome, $chaveValor) {
	$chaveNome = db_escape($chaveNome);
	$chaveValor = db_escape($chaveValor);
	$ret = db_exec("DELETE FROM $tabela WHERE $chaveNome='$chaveValor'");
	return $ret;
	}

function bd_inserirObjeto($tabela, &$objeto, $chaveNome = NULL, $verboso=false) {
	$fmtsql = "INSERT INTO `$tabela` (%s) values (%s) ";
	foreach (get_object_vars($objeto) as $k => $v) {
		if (is_array($v) || is_object($v) || $v == NULL) continue;
		if ($k[0] == '_') continue;
		$campos[] = $k;
		$valores[] = "'".db_escape($v)."'";
		}
	$sql = sprintf($fmtsql, implode(",", $campos) ,  implode(",", $valores));
	if ($verboso) print "$sql<br />\n";
	if (!(db_exec($sql))) return false;
	$id = db_insert_id($tabela, $chaveNome);
	if ($verboso)	print "id=[$id]<br />\n";
	if ($chaveNome && $id) $objeto->$chaveNome = $id;
	return true;
	}

function bd_atualizarObjeto($tabela, &$objeto, $chaveNome, $atualizarNulos=true, $campoDescricao = NULL) {
	$fmtsql = "UPDATE `$tabela` SET %s WHERE %s";
	$obj_vars_arr = get_object_vars($objeto);
	foreach ($obj_vars_arr as $k => $v) {
		if(is_array($v) || is_object($v) || $k[0] == '_') continue;

		if($k == $chaveNome) { 
			$onde = "$chaveNome='".db_escape($v)."'";
			continue;
			}
		if ($v === NULL && !($atualizarNulos)) continue;
		$val = (($v === '') ? "''" : ("'".db_escape($v)."'"));
		$tmp[] = "$k=$val";
		}
	if (count($tmp)) {
		$sql = sprintf($fmtsql, implode(",", $tmp) , $onde);
		$valorRetorno = db_exec($sql);
		if ($valorRetorno) {
			global $perms;
			$perm_item_id = $perms->get_objeto_id($tabela, $obj_vars_arr[$chaveNome], 'axo');
			if ($perm_item_id) {
				if ($campoDescricao) $chaveDesc = $campoDescricao;
				else $chaveDesc = bd_carregarResultado('select permissoes_item_legenda FROM modulos WHERE permissoes_item_tabela = \''.$tabela.'\'');
				if ($chaveDesc) $perms->editar_objeto($perm_item_id, $tabela, $obj_vars_arr[$chaveDesc], $obj_vars_arr[$chaveNome], 0, 0, 'axo');
				}
			}
		} 
	else $valorRetorno = true;
	return $valorRetorno;
	}

function bd_converterData($src, &$dest, $srcFmt) {
	$resultado = strtotime($src);
	$dest = $resultado;
	return ($resultado != 0);
	}

function bd_datahora($formatoHora = NULL) {
	if (!($formatoHora)) return NULL;
	$datahora_str = ((is_object($formatoHora)) ? $formatoHora->toString('%Y-%m-%d %H:%M:%S') : strftime('%Y-%m-%d %H:%M:%S', $formatoHora));
	return $datahora_str;
	}

function db_dateTime2locale($datahora, $formato) {
	$datahora = null;
	if (intval($datahora)) {
		$data = new CData($datahora);
		$datahora = $data->format($formato);
		}
	return $datahora;
	}

function unirLinhaAoObjeto($hash, &$obj, $prefixo=NULL, $checarAspas=true, $unirTudo=false) {
	if (!(is_array($hash))) die('unirLinhaAoObjeto : hash expected');
	else if (!(is_object($obj))) die('unirLinhaAoObjeto : object expected');
	foreach ($hash as $k => $v) {
		if (is_object($hash[$k])) {
			$erro_str .= ('unirLinhaAoObjeto : non-object expected for hash value with chave '.$k."\n");
			die ($erro_str);
			}
		}
	if ($unirTudo) {
		foreach ($hash as $k => $v) $obj->$k = $hash[$k];
		} 
	else if ($prefixo) {
		foreach (get_object_vars($obj) as $k => $v) {
			if (isset($hash[$prefixo.$k ])) $obj->$k = $hash[$k];
			}
		} 
	else {
		foreach (get_object_vars($obj) as $k => $v) {
			if (isset($hash[$k])) $obj->$k = $hash[$k];
			}
		}
	}
?>
