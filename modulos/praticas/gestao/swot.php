<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\gestao\oportunidades.php		

Exibe e editar o campo oportunidades, do plano de gestão																																					
																																												
********************************************************************************************/


$sql->adTabela('plano_gestao_oportunidade');
$sql->adCampo('*');
$sql->adOnde('pg_oportunidade_pg_id='.(int)$pg_id);
$sql->adOrdem('pg_oportunidade_ordem ASC');
$oportunidades=$sql->Lista();
$sql->Limpar();
$saida_oportunidades='<table width=100% cellspacing=0 cellpadding=0><tr><th>Oportunidades (O)</th></tr>';
foreach ($oportunidades as $oportunidade) {

	$saida_oportunidades.='<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$oportunidade['pg_oportunidade_nome'].'</td></tr>';
	}
$saida_oportunidades.= '</table>';


$sql->adTabela('plano_gestao_ameacas');
$sql->adCampo('*');
$sql->adOnde('pg_ameaca_pg_id='.(int)$pg_id);
$sql->adOrdem('pg_ameaca_ordem ASC');
$ameacas=$sql->Lista();
$sql->Limpar();
$saida_ameacas='<table width=100% cellspacing=0 cellpadding=0><tr><th>Ameaças (T)</th></tr>';
foreach ($ameacas as $ameaca) {
	$saida_ameacas.='<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$ameaca['pg_ameaca_nome'].'</td></tr>';
	}
$saida_ameacas.= '</table>';



$sql->adTabela('plano_gestao_pontosfortes');
$sql->adCampo('*');
$sql->adOnde('pg_ponto_forte_pg_id='.(int)$pg_id);
$sql->adOrdem('pg_ponto_forte_ordem ASC');
$ponto_fortes=$sql->Lista();
$sql->Limpar();
$saida_ponto_fortes='<table width=100% cellspacing=0 cellpadding=0><tr><th>Forças (S)</th></tr>';
foreach ($ponto_fortes as $ponto_forte) {
	$saida_ponto_fortes.='<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$ponto_forte['pg_ponto_forte_nome'].'</td></tr>';
	}
$saida_ponto_fortes.= '</table>';



$sql->adTabela('plano_gestao_oportunidade_melhorias');
$sql->adCampo('*');
$sql->adOnde('pg_oportunidade_melhoria_pg_id='.(int)$pg_id);
$sql->adOrdem('pg_oportunidade_melhoria_ordem ASC');
$oportunidade_melhorias=$sql->Lista();
$sql->Limpar();
$saida_oportunidade_melhorias='<table width=100% cellspacing=0 cellpadding=0><tr><th>Fraquezas (W)</th></tr>';
foreach ($oportunidade_melhorias as $oportunidade_melhoria) {
	$saida_oportunidade_melhorias.='<tr><td>'.$oportunidade_melhoria['pg_oportunidade_melhoria_nome'].'</td></tr>';
	}
$saida_oportunidade_melhorias.= '</table>';

echo '<table cellspacing=0 cellpadding=0>';
echo '<tr><td></td><td align=center style="background-color: #ccefa9"><b><br>Positivo<br>&nbsp;</b></td><td align=center style="background-color: #f4a5a5"><b>Negativo</b></td></tr>';
echo '<tr><td style="background-color: #faf2b4"><b>Interno</b></td><td valign=top style="background-color: #e0ed84">'.$saida_ponto_fortes.'</td><td valign=top style="background-color: #f7c082">'.$saida_oportunidade_melhorias.'</td></tr>';
echo '<tr><td style="background-color: #c2d4e8"><b>Externo</b></td><td valign=top style="background-color: #a7ceb9">'.$saida_oportunidades.'</td><td valign=top style="background-color: #c0a2b6">'.$saida_ameacas.'</td></tr>';




echo '</td></tr></table>';


?>
