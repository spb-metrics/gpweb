<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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
	if (!$sql->exec()) die('Não foi possivel alterar os valores'.$bd->stderr(true));
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
			if (!$sql->exec()) die('Não foi possível atualizar msg_usuario.');
			$sql->limpar();
			if ($rs_leitura['aviso_leitura']==1) aviso_leitura($rs_leitura['de_id'], $rs_leitura['msg_usuario_id'], $data);
			}
		}	
	}

$Aplic->redirecionar('m=email&a=lista_msg'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status : '&status=1'));
?>
