<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$data_entrega=getParam($_REQUEST, 'data_entrega', date('Y-m-d H:i'));
$meio=getParam($_REQUEST, 'meio', '');
$usuario_id=getParam($_REQUEST, 'usuario_id', 0);
$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', 0);

$msg_id=getParam($_REQUEST, 'msg_id', 0);
if ( $Aplic->usuario_cm >= 1 ){
	$sql = new BDConsulta;
	$sql->adTabela('msg_usuario');
	$sql->adAtualizar('datahora_leitura', $data_entrega);
	$sql->adAtualizar('status', 1);
	$sql->adAtualizar('cm', $Aplic->usuario_cm);
	$sql->adAtualizar('meio', $meio);
	$sql->adOnde('msg_id = '.$msg_id);
	$sql->adOnde('para_id = '.$usuario_id);
	if (!$sql->exec()) die('N�o foi poss�vel atualizar msg_usuario.');
	$sql->limpar();
	} 
else $Aplic->setMsg('Acesso negado!', UI_MSG_ERRO);

$Aplic->redirecionar('m=email&a='.$Aplic->usuario_prefs['modelo_msg'].'&msg_usuario_id='.$msg_usuario_id);
?>
