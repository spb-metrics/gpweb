<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $config;

$dontStartSession = 1;
require_once('init.php');
require_once('lang/class.default.php');
require_once('lang/'.$config['lang'].'.php'); 
$sql = new BDConsulta;
$listId = (int)_get('list');
$sql->adTabela('parafazer_listas');
$sql->adCampo('id, nome, usuario_id');
$sql->adOnde('id='.$listId);
$listData = $sql->Linha();
$sql->limpar();
if(!$listData) die("Não existe esta lista.");
$listData['_feed_titulo'] = sprintf("%s", $listData['nome']);
$listData['_feed_descr'] = sprintf('Nov'.$config['genero_tarefa'].' '.$config['tarefa']." em %s", $listData['nome']);
htmlarray_ref($listData);
$data = array();
$sql->adTabela('parafazer_tarefa');
$sql->adCampo('id, d, lista_id, compl, titulo, nota, prio, ow, parafazer_chave, datafinal, datafinal IS NULL AS ddn');
$sql->adOnde('lista_id='. $listId);
$sql->adOrdem('d DESC'); 
$sql->setLimite(100); 
$q = $sql->Lista();
$sql->limpar();
foreach ($q as $r){
	if($r['prio'] > 0) $r['prio'] = '+'.$r['prio'];
	$a = array();
	if($r['prio']) $a[] = "Prioridade: $r[prio]";
	if($r['datafinal'] != '') {
		$ad = explode('-', $r['datafinal']);
		$a[] = "Até: ".formatarData3($config['dateformat'], (int)$ad[0], (int)$ad[1], (int)$ad[2]);
		}
	if($r['parafazer_chave'] != '') $a[] = "Chaves: ". str_replace(',', ', ', $r['parafazer_chave']);
	$r['_descr'] = nl2br($r['nota']). ($a && $r['nota']!='' ? "<br><br>" : "").  implode("<br>", $a);
	$data[] = htmlarray($r);
	}

printRss($listData, $data);


function printRss($listData, $data){
	$link = htmlarray('http://'. previnirXSS($_SERVER['HTTP_HOST']). substr(previnirXSS($_SERVER['REQUEST_URI']), 0, strrpos(previnirXSS($_SERVER['REQUEST_URI']), '/') + 1));
	$buildDate = gmdate('r');
	$s = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n<channel>\n".
		"<title>$listData[_feed_titulo]</title>\n<link>$link</link>\n<description>$listData[_feed_descr]</description>\n".
		"<lastBuildDate>$buildDate</lastBuildDate>\n\n";
	foreach($data as $v){
		$da = explode(' ', $v['d']);
		$dDate = explode('-', $da[0]);
		$dTime = explode(':', $da[1]);
		$d_ts = mktime((int)$dTime[0],(int)$dTime[1],(int)$dTime[2], (int)$dDate[1],(int)$dDate[2],(int)$dDate[0]);
		$d = gmdate('r', $d_ts);
		$guid = $listData['id'].'-'.$v['id'].'-'.$d_ts;
		$s .= "<item>\n<title>$v[titulo]</title>\n".
			"<link>$link</link>\n".
			"<pubDate>$d</pubDate>\n".
			"<description>$v[_descr]</description>\n".
			"<guid isPermaLink=\"false\">$guid</guid>\n".
			"</item>\n";
		}
	$s .= "</channel>\n</rss>";
	header("Content-type: text/xml");
	print $s;
	}

?>