<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$status=getParam($_REQUEST, 'status', 1);
$status_original=getParam($_REQUEST, 'status_original', 0);
$pasta=getParam($_REQUEST, 'pasta', null);

if (isset($_REQUEST['vetor_modelo_msg_usuario'])) $vetor_modelo_msg_usuario = getParam($_REQUEST, 'vetor_modelo_msg_usuario', null); 
else if (isset($_REQUEST['modelo_usuario_id']) && $_REQUEST['modelo_usuario_id']) $vetor_modelo_msg_usuario[] = getParam($_REQUEST, 'modelo_usuario_id', null);

if (!isset($vetor_modelo_msg_usuario)){
	if (isset($_REQUEST['modeloID']) && $_REQUEST['modeloID']) $modeloID = getParam($_REQUEST, 'modeloID', null); 
	else if (isset($_REQUEST['modelo_id']) && $_REQUEST['modelo_id']) $modeloID[] = getParam($_REQUEST, 'modelo_id', null);
	else if (!isset($modeloID)) $modeloID = array();
	}

$so_modelo=isset($modeloID);

if ($Aplic->getPref('agrupar_msg')) $vetor_modelo_msg_usuario=vetor_grupo_doc($vetor_modelo_msg_usuario);


$sql = new BDConsulta;


foreach(($so_modelo ? $modeloID : $vetor_modelo_msg_usuario) as $chave => $valor){
	
	if (!$so_modelo && !$Aplic->getPref('agrupar_msg')){
		$sql->adTabela('modelo_usuario');
		$sql->adCampo('modelo_id');
		if ($Aplic->getPref('agrupar_msg')) $sql->adOnde('modelo_id ='.$valor);
		else $sql->adOnde('modelo_usuario_id ='.$valor);

		$modelo_id=$sql->Resultado();
		$sql->limpar();
		$modelo_usuario_id=$valor;
		}
	else $modelo_id=$valor;	
	
	 
	$sql->adTabela('modelo_usuario');
	$sql->adAtualizar('pasta_id', $pasta);
	$sql->adAtualizar('status', $status);
	
	if ($so_modelo || $Aplic->getPref('agrupar_msg')){
		$sql->adOnde('para_id='.$Aplic->usuario_id);
		$sql->adOnde('modelo_id ='.$modelo_id);
		}
	else $sql->adOnde('modelo_usuario_id ='.$valor);
	
	if (!$sql->exec()) die('Não foi possivel alterar os valores'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->adTabela('modelo_usuario');
	$sql->adCampo('de_id, aviso_leitura, modelo_usuario_id, datahora_leitura');
	if ($so_modelo || $Aplic->getPref('agrupar_msg')){
		$sql->adOnde('para_id='.$Aplic->usuario_id);
		$sql->adOnde('modelo_id ='.$modelo_id);
		}
	else $sql->adOnde('modelo_usuario_id ='.$valor);
	$sql_resultadosa = $sql->Lista();
	$sql->Limpar();

	foreach($sql_resultadosa as $rs_leitura){ 
		if (!$rs_leitura['datahora_leitura']) {
			$data = date('Y-m-d H:i:s');
			$sql->adTabela('modelo_usuario');
			$sql->adAtualizar('datahora_leitura', $data);
				if ($so_modelo  || $Aplic->getPref('agrupar_msg')){
					$sql->adOnde('para_id='.$Aplic->usuario_id);
					$sql->adOnde('modelo_id ='.$modelo_id);
					}
				else $sql->adOnde('modelo_usuario_id ='.$modelo_usuario_id);	
					
			$sql->adOnde('datahora_leitura IS NULL');
			if (!$sql->exec()) die('Não foi possível atualizar msg_usuario.');
			$sql->limpar();
			if ($rs_leitura['aviso_leitura']==1) aviso_leitura_modelo($rs_leitura['de_id'], $rs_leitura['modelo_usuario_id'], $data);
			}
		}	
	}
	
$Aplic->redirecionar('m=email&a=modelo_pesquisar'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status : '&status=1'));	
?>
