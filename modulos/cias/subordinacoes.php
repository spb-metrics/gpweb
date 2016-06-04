<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $procurar_string, $dialogo, $cia_id;

$sql = new BDConsulta;

//descobrir o pai
$sql->adTabela('cias');
$sql->esqUnir('contatos', 'con', 'cia_responsavel = con.contato_id');
$sql->adCampo('cia_id, cia_nome, cia_nome_completo');
if ($cia_id) $sql->adOnde('cia_id='.(int)$cia_id);
else $sql->adOnde('cia_superior IS NULL OR cia_id=cia_superior');
$cia_pai = $sql->Linha();
$sql->limpar();
$linha_branco=0;

echo '<table width="'.($dialogo ? '750' : '100%').'" cellpadding=0 cellspacing=0 class="tbl1">';

echo '<tr><td><b><a href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver&tab=0&cia_id='.(int)$cia_pai['cia_id'].'\');">'.$cia_pai['cia_nome'].' - '.$cia_pai['cia_nome_completo'].'</a></b></td></tr>';
$linha_branco=0;
cias_subordinadas($cia_pai['cia_id'], '&nbsp;&nbsp;');
echo '</table>';

if ($dialogo) echo '<script>self.print();</script>';

function cias_subordinadas($cia_id, $dist=''){
	global $Aplic, $linha_branco;

	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('cias');
	$sql->adCampo('cia_id, cia_nome, cia_nome_completo');
	$sql->adOnde('cia_superior='.(int)$cia_id.' AND cia_superior!=cia_id');
	$sql->adOrdem('cia_superior ASC, cia_nome ASC');
	$cias_subordinadas=$sql->lista();
	$sql->limpar();
	$retorno=0;
	foreach($cias_subordinadas as $linha){
		$retorno++;
		echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver&tab=0&cia_id='.(int)$linha['cia_id'].'\');">'.$dist.imagem('icones/subnivel.gif').$linha['cia_nome'].' - '.$linha['cia_nome_completo'].'</a></td></tr>';
		$linha_branco=0;
		cias_subordinadas($linha['cia_id'], $dist.'&nbsp;&nbsp;');
		}
	if ($retorno && !$linha_branco)	{
		$linha_branco=1;
		echo '<tr><td>&nbsp;</td></tr>';
		}
	return count($cias_subordinadas);	
	}
	
?>