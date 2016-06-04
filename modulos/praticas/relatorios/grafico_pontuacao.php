<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf, $cia_id, $ano, $usuario_id, $pratica_modelo_id, $dialogo;


$sql = new BDConsulta;

include_once BASE_DIR.'/modulos/praticas/pauta.class.php';
$pauta=new Cpauta($cia_id, $pratica_modelo_id, $ano);


$tipo_grafico = array('barra' => 'Barra', 'barra_sombra' => 'Barra com sombra', 'poligono' => 'Polígono');
$tipografico=getParam($_REQUEST, 'tipografico', 'poligono');

echo '<form name="mudar_grafico" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="relatorios" />';
echo '<input type="hidden" name="relatorio_tipo" value="grafico_pontuacao" />';


if (!$dialogo){

	echo '<table width="100%"><tr><td width="22">&nbsp;</td><td align="center"><font size="4"><center>Pontuação</center></font></td><td width="22"><a href="javascript: void(0);" onclick ="frm_filtro.target=\'popup\'; frm_filtro.dialogo.value=1; frm_filtro.submit();">'.imagem('imprimir_p.png', 'Imprimir o Relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'</a></td></tr></table>';
	echo estiloTopoCaixa();
	echo '<table cellspacing="4" cellpadding=0 align="center" class="std2" width="100%">';
	echo '<tr><td>'.dica('Tipo de Gráfico', 'Escolha qual a melhor representação gráfica para os valores do indicador.').'Gráfico:'.dicaF().selecionaVetor($tipo_grafico, 'tipografico', 'onchange="mudar_grafico.submit();" class="texto"', $tipografico).'</td></tr>';

	}
else echo '<table width="100%"><tr><td align="center"><font size="4"><center>Pontuação</center></font></td></tr></table>';	
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

