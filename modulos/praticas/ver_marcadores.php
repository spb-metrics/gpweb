<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\ver_eventos.php		

Exibe os marcadores da r�gua de pontua��o relativos � pr�tica de gest�o selecionada																																						
																																												
********************************************************************************************/

global $pratica_id, $pratica_modelo_id;

$sql = new BDConsulta;

$sql->adTabela('pratica_nos_marcadores');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
$sql->limpar();

$sql->adTabela('pratica_nos_marcadores');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$itens=$sql->ListaChaveSimples('pratica_item_id');
$sql->limpar();


$sql->adTabela('pratica_nos_marcadores');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOrdem('pratica_criterio_numero');
$sql->adOrdem('pratica_item_numero');
$sql->adOrdem('pratica_marcador_letra');
$marcadores=$sql->Lista();
$sql->limpar();

$criterio_atual='';
$marcador_atual='';

echo '<table id="tblPraticas" border=0 cellpadding=0 cellspacing=1 width="100%" class="std">';
if ($marcadores && count($marcadores)) echo '<tr><td align="left" colspan=2 nowrap="nowrap"><p><b>'.ucfirst($config['marcadores']).' Atendid'.$config['genero_marcador'].'s pel'.$config['genero_pratica'].' '.$config['pratica'].'<b></p></td></tr>';
else echo '<tr><td align="left" colspan=2 nowrap="nowrap"><p><b>Nenhum '.$config['marcador'].' encontrad'.$config['genero_marcador'].'<b></p></td></tr>';
foreach($marcadores as $dado){
	if ($dado['pratica_criterio_id']!=$criterio_atual){
		$criterio_atual=$dado['pratica_criterio_id'];
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observa��es</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_pontos'].'</td></tr>';
		$dentro .= '</table>';
		echo '<tr><td align="left" colspan=2 nowrap="nowrap">'.dica('Dados Sobre o Crit�rio', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].dicaF().'</td></tr>';
		}
	if ($dado['pratica_item_id']!=$marcador_atual){
		$marcador_atual=$dado['pratica_item_id'];
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observa��es</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_obs'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_pontos'].'</td></tr>';
		$dentro .= '</table>';
		echo '<tr><td align="left" colspan=2 nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Dados Sobre o Crit�rio', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].dicaF().'</td></tr>';
		}

	echo '<tr><td align="right" nowrap="nowrap" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$dado['pratica_marcador_letra'].'</b></td><td class="realce" width="100%">'.($dado['pratica_marcador_extra'] ? dica('Informa��es Extras', $dado['pratica_marcador_extra']) : '').$dado['pratica_marcador_texto'].($dado['pratica_marcador_extra'] ? dicaF() : '').'</td></tr>';
	}
echo '</table>';

?>