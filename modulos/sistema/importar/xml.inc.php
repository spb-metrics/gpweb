<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $stack;
global $reset;
$stack = array();

function startTag($parser, $name, $attrs) {
    global $stack, $reset;
    $reset = false;

    $tag=array("name"=>$name,"attrs"=>$attrs);
    array_push($stack, $tag);
}

function cdata($parser, $cdata) {
  global $stack, $reset, $i;
  $stack[count($stack)-1]['cdata']=1;
  if(trim($cdata)) {
    if ($reset || !isset($stack[count($stack)-1]['cdata'])) $stack[count($stack)-1]['cdata'] = $cdata;
    else $stack[count($stack)-1]['cdata'] .= $cdata;
		}
	}

function endTag($parser, $name) {
  global $stack, $reset;
  $reset = true;
  $stack[count($stack)-2]['children'][] = $stack[count($stack)-1];
  array_pop($stack);
	}

function xmlParse($data) {
  global $stack;
  $xml_parser = xml_parser_create();
  xml_set_element_handler($xml_parser, "startTag", "endTag");
  xml_set_character_data_handler($xml_parser, "cdata");
  $erro = xml_parse($xml_parser, $data);
  if(!$erro) {
    echo '<tr><td colspan=20>'.sprintf("Erro no XML: %s na linha %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)).'</td></tr>';
		echo '</table>'.estiloFundoCaixa();
		die();
		}
  xml_parser_free($xml_parser);
  return $stack;
	}

function rebuildTree($tree) {
	foreach($tree as $tag) {
		if (isset($tag['children'])) $newTree[$tag['name']][] = rebuildTree($tag['children']);
    else {
    	$newTree[$tag['name']] = (isset($tag['cdata']) ? $tag['cdata'] : '');
    	}
		}
	return $newTree;
	}