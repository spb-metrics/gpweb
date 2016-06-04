<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

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