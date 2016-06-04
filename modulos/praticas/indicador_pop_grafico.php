<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\indicador_pop_grafico.php		

Exibe o gráfico do indicador em uma janela pop-up																																						
																																												
********************************************************************************************/
if(!isset($pratica_indicador_id) || !$pratica_indicador_id) $pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', 0);


$sql = new BDConsulta;

$sql->adTabela('pratica_indicador_requisito');
$sql->adCampo('DISTINCT ano');
$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);
asort($anos);

if (isset($_REQUEST['IdxIndicadorAno'])) $Aplic->setEstado('IdxIndicadorAno', getParam($_REQUEST, 'IdxIndicadorAno', null));
$ano = ($Aplic->getEstado('IdxIndicadorAno') !== null && isset($anos[$Aplic->getEstado('IdxIndicadorAno')]) ? $Aplic->getEstado('IdxIndicadorAno') : $ultimo_ano);


$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
$sql->adCampo('pratica_indicador_unidade, pratica_indicador_cor, pratica_indicador_nome, pratica_indicador_tipografico, pratica_indicador_agrupar, pratica_indicador_mostrar_valor, pratica_indicador_mostrar_titulo, pratica_indicador_media_movel');
$sql->adOnde('pratica_indicador_id='.$pratica_indicador_id);
$pratica_indicador=$sql->Linha();
$sql->limpar();

$src = '?m=praticas&a=grafico_free&sem_cabecalho=1&ano='.$ano.'&mostrar_valor='.$pratica_indicador['pratica_indicador_mostrar_valor'].'&mostrar_titulo='.$pratica_indicador['pratica_indicador_mostrar_titulo'].'&media_movel='.$pratica_indicador['pratica_indicador_media_movel'].'&agrupar='.$pratica_indicador['pratica_indicador_agrupar'].'&tipografico='.$pratica_indicador['pratica_indicador_tipografico'].'&pratica_indicador_id='.$pratica_indicador_id."&width='+((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";
echo "<table cellspacing='0' cellpadding='0' align='center' class='tbl3'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr></table>";
?>
