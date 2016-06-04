<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf, $cia_id, $ano, $usuario_id, $pratica_modelo_id, $dialogo;


$sql = new BDConsulta;

include_once BASE_DIR.'/modulos/praticas/pauta.class.php';
$pauta=new Cpauta($cia_id, $pratica_modelo_id, $ano);


$tipo_grafico = array('barra' => 'Barra', 'barra_sombra' => 'Barra com sombra', 'poligono' => 'Pol�gono');
$tipografico=getParam($_REQUEST, 'tipografico', 'poligono');

echo '<form name="mudar_grafico" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="relatorios" />';
echo '<input type="hidden" name="relatorio_tipo" value="grafico_pontuacao" />';


if (!$dialogo){

	echo '<table width="100%"><tr><td width="22">&nbsp;</td><td align="center"><font size="4"><center>Pontua��o</center></font></td><td width="22"><a href="javascript: void(0);" onclick ="frm_filtro.target=\'popup\'; frm_filtro.dialogo.value=1; frm_filtro.submit();">'.imagem('imprimir_p.png', 'Imprimir o Relat�rio', 'Clique neste �cone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poder� imprimir o relat�rio a partir do navegador Web.').'</a></td></tr></table>';
	echo estiloTopoCaixa();
	echo '<table cellspacing="4" cellpadding=0 align="center" class="std2" width="100%">';
	echo '<tr><td>'.dica('Tipo de Gr�fico', 'Escolha qual a melhor representa��o gr�fica para os valores do indicador.').'Gr�fico:'.dicaF().selecionaVetor($tipo_grafico, 'tipografico', 'onchange="mudar_grafico.submit();" class="texto"', $tipografico).'</td></tr>';

	}
else echo '<table width="100%"><tr><td align="center"><font size="4"><center>Pontua��o</center></font></td></tr></table>';	
echo '</form>';
	


if (!$dialogo)echo '<tr><td>';

$nomes=array();
$porcentagem=array();
foreach ($pauta->criterios as $criterio_id => $linha){
	$nomes[]=$linha['pratica_criterio_nome'];
	$porcentagem[]=$pauta->porcentagem_criterio[$criterio_id];
	}


$src = '?m=praticas&a=grafico_radial&sem_cabecalho=1&tipografico='.$tipografico.'&nomes='.implode('*!', $nomes).'&pontos='.implode('*!', $porcentagem)."&width='+((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";
echo "<table cellspacing='0' cellpadding='0' align='center' class='tbl3'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr></table>";
if (!$dialogo) echo '</td></tr>'.estiloFundoCaixa();	

if ($dialogo)echo '<script>self.print();</script>';	



?>

