<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$status=getParam($_REQUEST, 'status', 1);

$pasta=getParam($_REQUEST, 'pasta', null);
If ($pasta < 1) $pasta=null;
if (isset($_REQUEST['vetor_msg_usuario']) && !isset($vetor_msg_usuario)) $vetor_msg_usuario = getParam($_REQUEST, 'vetor_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id'])  && !isset($vetor_msg_usuario)) $vetor_msg_usuario[] = getParam($_REQUEST, 'msg_usuario_id', null);
else if (!isset($vetor_msg_usuario)) $vetor_msg_usuario = array();
$sql = new BDConsulta;

if ($Aplic->getPref('agrupar_msg')) $vetor_msg_usuario=vetor_grupo_msg($vetor_msg_usuario);



foreach($vetor_msg_usuario as $chave => $valor){ 
	$sql->adTabela('msg_usuario');
	$sql->adAtualizar('pasta_id', $pasta);
	$sql->adAtualizar('status', $status);
	$sql->adOnde('para_id='.$Aplic->usuario_id);
	if ($Aplic->getPref('agrupar_msg')) $sql->adOnde('msg_id ='.$valor);
	else $sql->adOnde('msg_usuario_id ='.$valor);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->adTabela('msg_usuario');
	$sql->adCampo('msg_usuario_id, datahora_leitura, de_id, aviso_leitura');
	if ($Aplic->getPref('agrupar_msg')) $sql->adOnde('msg_id ='.$valor);
	else $sql->adOnde('msg_usuario_id ='.$valor);
	$sql->adOnde('para_id='.$Aplic->usuario_id);
	$sql_resultadosa = $sql->Lista();
	$sql->Limpar();

	foreach($sql_resultadosa as $rs_leitura){ 
		if (!$rs_leitura['datahora_leitura']) {
			$data = date('Y-m-d H:i:s');
			$sql->adTabela('msg_usuario');
			$sql->adAtualizar('datahora_leitura', $data);
			$sql->adOnde('para_id='.$Aplic->usuario_id);
			$sql->adOnde('msg_usuario_id = '.$rs_leitura['msg_usuario_id']);
			$sql->adOnde('datahora_leitura IS NULL');
			if (!$sql->exec()) die('N�o foi poss�vel atualizar msg_usuario.');
			$sql->limpar();
			if ($rs_leitura['aviso_leitura']==1) aviso_leitura($rs_leitura['de_id'], $rs_leitura['msg_usuario_id'], $data);
			}
		}	
	}

$Aplic->redirecionar('m=email&a=lista_msg'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status : '&status=1'));
?>
