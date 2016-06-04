<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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
	if (!$sql->exec()) die('Não foi possível atualizar msg_usuario.');
	$sql->limpar();
	} 
else $Aplic->setMsg('Acesso negado!', UI_MSG_ERRO);

$Aplic->redirecionar('m=email&a='.$Aplic->usuario_prefs['modelo_msg'].'&msg_usuario_id='.$msg_usuario_id);
?>
