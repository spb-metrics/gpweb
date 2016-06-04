<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


$hospedadoBd = getParam($_REQUEST, 'hospedadoBd', '');
$nomeBd = getParam($_REQUEST, 'nomeBd', '');
$usuarioBd = getParam($_REQUEST, 'usuarioBd', '');
$senhaBd = getParam($_REQUEST, 'senhaBd', '');
$ok=1;

echo estiloTopoCaixa();
echo '<table class="std" width="100%" border=0 cellpadding=0 cellspacing="2"><tr><td align="left">';

try {
	  $db = new PDO('mysql:dbname='.$nomeBd.';host='.$hospedadoBd, $usuarioBd, $senhaBd);
		} 
	catch (PDOException $db) {
		echo '<b>Conexão falhou:</b> '.$db->getMessage();
		$ok=0;
		echo '<br><br>';
		if ($hospedadoBd!='127.0.0.1' && $hospedadoBd!='localhost') include_once BASE_DIR.'/modulos/sistema/como_conectar.php';
		
		}

if ($ok) {
	echo '<br>Conexão foi estabelecida<br>&nbsp;';
	$resultado = $db->query('show tables');
	$linhas=$resultado->fetchAll();
	echo '<br><b>Tabelas encontradas</b><br>';
	foreach($linhas AS $linha) echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$linha[0].'<br>';
	}

echo '</td></tr></table>';
echo estiloFundoCaixa();
?>