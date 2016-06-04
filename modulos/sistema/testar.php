<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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
		echo '<b>Conex�o falhou:</b> '.$db->getMessage();
		$ok=0;
		echo '<br><br>';
		if ($hospedadoBd!='127.0.0.1' && $hospedadoBd!='localhost') include_once BASE_DIR.'/modulos/sistema/como_conectar.php';
		
		}

if ($ok) {
	echo '<br>Conex�o foi estabelecida<br>&nbsp;';
	$resultado = $db->query('show tables');
	$linhas=$resultado->fetchAll();
	echo '<br><b>Tabelas encontradas</b><br>';
	foreach($linhas AS $linha) echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$linha[0].'<br>';
	}

echo '</td></tr></table>';
echo estiloFundoCaixa();
?>