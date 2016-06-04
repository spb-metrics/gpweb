<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$tipo=getParam($_REQUEST, 'tipo', 0);
$encaminha=getParam($_REQUEST, 'encaminha', 0);
$arquivar=getParam($_REQUEST, 'arquivar', 0);
$anot=getParam($_REQUEST, 'anot', 0);
$notifica_criador_nota=getParam($_REQUEST, 'notifica_criador_nota', 0);
$notifica_destinatarios_nota=getParam($_REQUEST, 'notifica_destinatarios_nota', 0);
$status_original=getParam($_REQUEST, 'status_original', 0);
$podeler_nota=getParam($_REQUEST, 'podeler_nota', '');
$receber_resposta=getParam($_REQUEST, 'receber_resposta', '');
//variavel abaixo será para ser o mesmo tempo da gravação de anotação e dos despachos, como forma de saber exatamente quais foram os destinatários dos despachos.
$data = date('Y-m-d H:i:s');
$data_anot=$data;

//tipo: 4 = anotacao; 1 = despacho; 2=resposta  


if (isset($_REQUEST['vetor_modelo_msg_usuario'])) $vetor_modelo_msg_usuario = getParam($_REQUEST, 'vetor_modelo_msg_usuario', null); 
else if (isset($_REQUEST['modelo_usuario_id']) && $_REQUEST['modelo_usuario_id']) $vetor_modelo_msg_usuario[] = getParam($_REQUEST, 'modelo_usuario_id', null);

if (!isset($vetor_modelo_msg_usuario)){
	if (isset($_REQUEST['modeloID']) && $_REQUEST['modeloID']) $modeloID = getParam($_REQUEST, 'modeloID', null); 
	else if (isset($_REQUEST['modelo_id']) && $_REQUEST['modelo_id']) $modeloID[] = getParam($_REQUEST, 'modelo_id', null);
	else if (!isset($modeloID)) $modeloID = array();
	}

$so_modelo=isset($modeloID);



$sql = new BDConsulta;
$destinatarios=array();

foreach(($so_modelo ? $modeloID : $vetor_modelo_msg_usuario) as $chave => $valor){ 

	if (!$so_modelo){
		$sql->adTabela('modelo_usuario');
		$sql->adCampo('modelo_id');
		$sql->adOnde('modelo_usuario_id = '.$valor);
		$modelo_id=$sql->Resultado();
		$sql->limpar();
		$modelo_usuario_id=$valor;
		}
	else $modelo_id=$valor;	
	
	$sql->adTabela('modelo_anotacao');
	$sql->adInserir('modelo_id', $modelo_id);
	$sql->adInserir('datahora', $data);
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('texto', $anot);
	$sql->adInserir('tipo', $tipo);
	$sql->adInserir('nome_de', $Aplic->usuario_nome);
	$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
	if ($podeler_nota) $sql->adInserir('anotacao_usuarios', 1);
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de anotações!'.$bd->stderr(true));
	$anotacao_id=$bd->Insert_ID('modelo_anotacao','modelo_anotacao_id');
	$sql->limpar();
	
	if ($podeler_nota=='criador'){
		$sql->adTabela('modelos');
		$sql->adCampo('modelo_criador_original');
		$sql->adOnde('modelo_id = '.$modelo_id);
		$criador = $sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('modelo_anotacao_usuarios');
		$sql->adInserir('modelo_anotacao_id', $anotacao_id);
		$sql->adInserir('usuario_id', $criador);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de anotacao_usuarios!'.$bd->stderr(true));
		$sql->limpar();			
		}
		
	if ($podeler_nota=='remetentes'){
		$sql->adTabela('modelo_usuario');
		$sql->adCampo('de_id');
		
		if ($so_modelo){
			$sql->adOnde('modelo_id = '.$modelo_id);
			$sql->adOnde('para_id='.$Aplic->usuario_id);
			}
		else $sql->adOnde('modelo_usuario_id='.$modelo_usuario_id);
			
		$sql->adGrupo('de_id');
		$remetentes = $sql->lista();
		$sql->limpar();
		foreach($remetentes as $remetente){
			$sql->adTabela('modelo_anotacao_usuarios');
			$sql->adInserir('modelo_anotacao_id', $anotacao_id);
			$sql->adInserir('usuario_id', $remetente['de_id']);
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de anotacao_usuarios!'.$bd->stderr(true));
			$sql->limpar();			
			}
		}
	if ($notifica_criador_nota || $notifica_destinatarios_nota || $tipo==2)	{

		$sql->adTabela('modelo_usuario');
		$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id = modelo_usuario.modelo_id');
		$sql->adCampo('de_id, modelo_assunto');
		if ($so_modelo){
			$sql->adOnde('modelo_usuario.modelo_id = '.$modelo_id);
			}
		else $sql->adOnde('modelo_usuario_id='.$modelo_usuario_id);

		$msg_original = $sql->Linha();
		$sql->Limpar();
		$j=0;
		//enviar notificação para o criador da mensagem
		if ($notifica_criador_nota && $msg_original['de_id'] != $Aplic->usuario_id) $destinatarios[$j++]=$msg_original['de_id'];
		}
	//enviar notificação para os demais destinatários da mensagem	
	if ($notifica_destinatarios_nota){
		$sql->adTabela('modelo_usuario');
		$sql->adCampo('para_id');
		$sql->adOnde('modelo_id = '.$modelo_id);
		$sql->adOnde('para_id <>'.$Aplic->usuario_id);
		$sql_resultado = $sql->Lista();
		$sql->Limpar();
		foreach ($sql_resultado as $linha) $destinatarios[$j++]=$linha['para_id'];
		}
	if ($notifica_criador_nota || $notifica_destinatarios_nota || $tipo==2){
		foreach((array)$destinatarios as $chave => $valor){ 
			$sql->adTabela('modelo_usuario');
			$sql->adInserir('de_id', $Aplic->usuario_id);
			$sql->adInserir('para_id', $valor);
			$sql->adInserir('modelo_id', $modelo_id);
			$sql->adInserir('tipo', $tipo);
			$sql->adInserir('datahora', $data);
			$sql->adInserir('nome_de', $Aplic->usuario_nome);
			$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
			$sql->adInserir('nome_para', nome_usuario($valor));
			$sql->adInserir('funcao_para', funcao_usuario($valor));
			$sql->adInserir('modelo_anotacao_id', $anotacao_id);
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!'.$bd->stderr(true));
			$sql->limpar();
			}
		}		
	}



if ($tipo==4) $Aplic->setMsg('Anotação gravada com sucesso', UI_MSG_OK);
else if ($tipo==2) $Aplic->setMsg('Resposta enviada com sucesso', UI_MSG_OK);


//se for despacho
if ($encaminha == 1 && $tipo==1) {
	include ("modelo_grava_encaminha.php");
	exit;
	}

//se for anotacao não irá selecionar destinatários e arquivar ou pender ou apenas marcar como vista
if ($tipo==4) {
	if ($arquivar == 1) $valor_status=4; 
	else if ($arquivar == 2) $valor_status=3; 
	else $valor_status=1;
	foreach(($so_modelo ? $modeloID : $vetor_modelo_msg_usuario)  as $chave => $valor){ 
		
		if (!$so_modelo){
			$sql->adTabela('modelo_usuario');
			$sql->adCampo('modelo_id');
			$sql->adOnde('modelo_usuario_id = '.$valor);
			$modelo_id=$sql->Resultado();
			$sql->limpar();
			$modelo_usuario_id=$valor;
			}
		else $modelo_id=$valor;	
		
		
			
		$sql->adTabela('modelo_usuario');
		$sql->adAtualizar('status', $valor_status);
		if ($so_modelo){
			$sql->adOnde('modelo_id = '.$modelo_id);
			$sql->adOnde('para_id='.$Aplic->usuario_id);
			}
		else $sql->adOnde('modelo_usuario_id='.$modelo_usuario_id);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!'.$bd->stderr(true));
		$sql->limpar();

		//se nao chegou a abrir as msg serao consideradas como lidas
		$sql->adTabela('modelo_usuario');
		$sql->adCampo('datahora_leitura, de_id, aviso_leitura');
		if ($so_modelo){
			$sql->adOnde('modelo_id = '.$modelo_id);
			$sql->adOnde('para_id='.$Aplic->usuario_id);
			}
		else $sql->adOnde('modelo_usuario_id='.$modelo_usuario_id);
		$sql_resultado = $sql->Lista();
		$sql->limpar();

		foreach ($sql_resultado as $rs_leitura){
			if (!$rs_leitura['datahora_leitura']) {
				$sql->adTabela('modelo_usuario');
				$sql->adAtualizar('datahora_leitura', date('Y-m-d H:i:s'));
				$sql->adAtualizar('status', 1);
				if ($so_modelo){
					$sql->adOnde('modelo_id = '.$modelo_id);
					$sql->adOnde('para_id='.$Aplic->usuario_id);
					}
				else $sql->adOnde('modelo_usuario_id='.$modelo_usuario_id);
				$sql->adOnde('datahora_leitura IS NULL');
				if (!$sql->exec()) die('Não foi possível atualizar os dados na tabela msg_usuario!'.$bd->stderr(true));
				$sql->limpar();
				if ($rs_leitura['aviso_leitura']==1) aviso_leitura_modelo($rs_leitura['de_id'], $valor, $data);
				}
			}		
		}
	$Aplic->redirecionar('m=email&a=modelo_pesquisar'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status : '&status=1'));		
	}

//se for responder usará o Id dos remetentes do documento ou do criador do mesmo como destinatario
if ($tipo==2) {
	
	if ($arquivar == 1) $valor_status=4; 
	else if ($arquivar == 2) $valor_status=3; 
	else $valor_status=1;
	foreach(($so_modelo ? $modeloID : $vetor_modelo_msg_usuario) as $chave => $valor){ 
		
		if (!$so_modelo){
			$sql->adTabela('modelo_usuario');
			$sql->adCampo('modelo_id');
			$sql->adOnde('modelo_usuario_id = '.$valor);
			$modelo_id=$sql->Resultado();
			$sql->limpar();
			$modelo_usuario_id=$valor;
			}
		else $modelo_id=$valor;	
			
		$remetentes =array();
		if ($receber_resposta=='remetentes'){
			$sql->adTabela('modelo_usuario');
			$sql->adCampo('de_id');
			if ($so_modelo){
				$sql->adOnde('modelo_id = '.$modelo_id);
				$sql->adOnde('para_id='.$Aplic->usuario_id);
				}
			else $sql->adOnde('modelo_usuario_id='.$modelo_usuario_id);
			$remetentes = $sql->Lista();
			$sql->Limpar();
			}
		else{
			$sql->adTabela('modelos');
			$sql->adCampo('modelo_criador_original');
			$sql->adOnde('modelo_id = '.$modelo_id);
			$remetentes = $sql->Lista();
			$sql->Limpar();
			}
		foreach($remetentes as $remetente){
			$sql->adTabela('modelo_usuario');
			$sql->adInserir('de_id', $Aplic->usuario_id);
			$sql->adInserir('para_id', $remetente['de_id']);
			$sql->adInserir('modelo_id', $modelo_id);
			$sql->adInserir('tipo', $tipo);
			$sql->adInserir('datahora', $data);
			$sql->adInserir('nome_de', $Aplic->usuario_nome);
			$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
			$sql->adInserir('nome_para', nome_usuario($remetente['de_id']));
			$sql->adInserir('funcao_para', funcao_usuario($remetente['de_id']));
			if (isset($anotacao_id)) $sql->adInserir('modelo_anotacao_id', $anotacao_id);
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!'.$bd->stderr(true));
			$sql->limpar();
	  	$encaminha = retorna_encaminha($remetente['de_id']);
	  	if (!empty($encaminha)) {
				$sql->adTabela('modelo_usuario');
				$sql->adInserir('de_id', $remetente['de_id']);
				$sql->adInserir('para_id', $encaminha);
				$sql->adInserir('modelo_id', $modelo_id);
				$sql->adInserir('tipo', $tipo);
				$sql->adInserir('datahora', $data);
				$sql->adInserir('nome_de', nome_usuario($remetente['de_id']));
				$sql->adInserir('funcao_de', funcao_usuario($remetente['de_id']));
				$sql->adInserir('nome_para', nome_usuario($encaminha));
				$sql->adInserir('funcao_para', funcao_usuario($encaminha));
				if (isset($anotacao_id)) $sql->adInserir('modelo_anotacao_id', $anotacao_id);
				if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!'.$bd->stderr(true));
				$sql->limpar();
	    	} 
			$encaminha = "";
			
			$sql->adTabela('modelo_usuario');
			$sql->adAtualizar('status', $valor_status);
			if ($so_modelo){
				$sql->adOnde('modelo_id = '.$modelo_id);
				$sql->adOnde('para_id='.$Aplic->usuario_id);
				}
			else $sql->adOnde('modelo_usuario_id='.$modelo_usuario_id);
			if (!$sql->exec()) die('Não foi possivel alterar os valores de modelo_usuario!'.$bd->stderr(true));
			$sql->limpar();	
			//se nao chegou a abrir as msg serao consideradas como lidas
			
			$sql->adTabela('modelo_usuario');
			$sql->adAtualizar('datahora_leitura', $data);
			if ($so_modelo){
				$sql->adOnde('modelo_id = '.$modelo_id);
				$sql->adOnde('para_id='.$Aplic->usuario_id);
				}
			else $sql->adOnde('modelo_usuario_id='.$modelo_usuario_id);
			$sql->adOnde('datahora_leitura IS NULL');
			if (!$sql->exec()) die('Não foi possivel alterar os valores de modelo_usuario!'.$bd->stderr(true));
			$sql->limpar();
			}
		}
	}		
$Aplic->redirecionar('m=email&a=modelo_pesquisar'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status : '&status=1'));

?>
