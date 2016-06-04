<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$modelo_id=getParam($_REQUEST, 'modelo_id', '');
$anexo_id=getParam($_REQUEST, 'anexo_id', '');
$sql = new BDConsulta;

$sql->adTabela('preferencia_cor');
$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos');
$sql->adOnde('usuario_id ='.$Aplic->usuario_id);
$cor=$sql->Linha();
$sql->limpar();
if (!isset($cor['cor_msg'])) {
	$sql->adTabela('preferencia_cor');
	$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos');
	$sql->adOnde('usuario_id = 0 OR usuario_id IS NULL');
	$cor=$sql->Linha();
	$sql->limpar();
 	}


if ($modelo_id){
	$sql->adTabela('modelo_leitura');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=modelo_leitura.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, datahora_leitura');
	$sql->adOnde('modelo_id = '.$modelo_id);
	}
else{
	$sql->adTabela('anexo_leitura');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=anexo_leitura.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, datahora_leitura');
	$sql->adOnde('anexo_id = '.$anexo_id);	
	}



$linhas = $sql->Lista();
$sql->limpar();	
if ($linhas && count($linhas)){
	echo '<table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_encamihamentos'].'" cellspacing=0 width="790" cellpadding=0>';
	echo '<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Hist�rico de leitura</b></td></tr>';
	echo '<tr><td><table align="center" class="tbl1" cellspacing=0 width="100%" cellpadding=0>';
	echo '<tr><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>'.ucfirst($config['usuario']).'</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data de Leitura</b></td></tr>'; 	
	foreach ($linhas as $linha){ 
    echo '<tr>';
    echo "<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>".$linha['nome_usuario'].'</td>';
    echo "<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>".retorna_data($linha['datahora_leitura']).'</td>';
		echo '</tr>';
		}
	echo '</table></td></tr><tr><td>&nbsp;</td></tr></table>';
	echo sombra_baixo('', 790); 			
	}
else {
	echo '<table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_encamihamentos'].'" cellspacing=0 width="790" cellpadding=0>';
	echo '<tr><td align="center" style="font-size:12pt;"><b>Hist�rico de leitura</b></td></tr>';
	echo '<tr><td align="center" style="background-color: #FFFFFF">N�o houve nenhum acesso ao anexo</td></tr></table>';
	echo sombra_baixo('', 790); 			
	}

?>