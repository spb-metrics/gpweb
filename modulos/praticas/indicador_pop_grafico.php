<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\indicador_pop_grafico.php		

Exibe o gr�fico do indicador em uma janela pop-up																																						
																																												
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
