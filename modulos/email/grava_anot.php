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

$msg_cripto_id=getParam($_REQUEST, 'msg_cripto_id', null);


//tipo: 0 = anotacao; 1 = despacho; 2=resposta ; .....3=encaminhamento 
if (isset($_REQUEST['vetor_msg_usuario'])) $vetor_msg_usuario = getParam($_REQUEST, 'vetor_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id'])) $vetor_msg_usuario[] = getParam($_REQUEST, 'msg_usuario_id', null);
else  $vetor_msg_usuario = array();

$sql = new BDConsulta;
$destinatarios=array();
foreach($vetor_msg_usuario as $chave => $valor){ 
	
	$sql->adTabela('msg_usuario');
	$sql->adCampo('msg_id');
	$sql->adOnde('msg_usuario_id = '.$valor);
	$msg_id=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('anotacao');
	$sql->adInserir('msg_id', $msg_id);
	$sql->adInserir('msg_usuario_id', $valor);
	$sql->adInserir('datahora', $data);
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('texto', $anot);
	$sql->adInserir('tipo', $tipo);
	$sql->adInserir('nome_de', $Aplic->usuario_nome);
	$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
	if ($podeler_nota) $sql->adInserir('anotacao_usuarios', 1);
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de anotações!'.$bd->stderr(true));
	$anotacao_id=$bd->Insert_ID('anotacao','anotacao_id');
	$sql->limpar();
	if ($podeler_nota=='criador'){
		$sql->adTabela('msg');
		$sql->adCampo('de_id');
		$sql->adOnde('msg_id = '.$msg_id);
		$de_id = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('anotacao_usuarios');
		$sql->adInserir('anotacao_id', $anotacao_id);
		$sql->adInserir('usuario_id', $de_id);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de anotacao_usuarios!'.$bd->stderr(true));
		$sql->limpar();			
		}
	if ($podeler_nota=='remetentes'){
		$sql->adTabela('msg_usuario');
		$sql->adCampo('de_id');
		$sql->adOnde('msg_id = '.$msg_id);
		$sql->adOnde('para_id='.$Aplic->usuario_id);
		$sql->adGrupo('de_id');
		$remetentes = $sql->lista();
		$sql->limpar();
		foreach($remetentes as $remetente){
			$sql->adTabela('anotacao_usuarios');
			$sql->adInserir('anotacao_id', $anotacao_id);
			$sql->adInserir('usuario_id', $remetente['de_id']);
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de anotacao_usuarios!'.$bd->stderr(true));
			$sql->limpar();			
			}
		}
	if ($notifica_criador_nota || $notifica_destinatarios_nota || $tipo==2)	{
		$sql->adTabela('msg');
		$sql->adCampo('de_id, referencia');
		$sql->adOnde('msg_id = '.$msg_id);
		$msg_original = $sql->Linha();
		$sql->Limpar();
		$j=0;
		//enviar notificação para o criador da mensagem
		if ($notifica_criador_nota && $msg_original['de_id'] != $Aplic->usuario_id) $destinatarios[$j++]=$msg_original['de_id'];
		}
	//enviar notificação para os demais destinatários da mensagem	
	if ($notifica_destinatarios_nota){
		$sql->adTabela('msg_usuario');
		$sql->adCampo('para_id');
		$sql->adOnde('msg_id = '.$msg_id);
		$sql->adOnde('para_id !='.$Aplic->usuario_id);
		$sql_resultado = $sql->Lista();
		$sql->Limpar();
		foreach ($sql_resultado as $linha) $destinatarios[$j++]=$linha['para_id'];
		}
	if ($notifica_criador_nota || $notifica_destinatarios_nota || $tipo==2){
		
		
		
		foreach((array)$destinatarios as $chave => $destinatario){ 
			$sql->adTabela('msg_usuario');
			$sql->adInserir('de_id', $Aplic->usuario_id);
			$sql->adInserir('para_id', $destinatario);
			$sql->adInserir('msg_id', $msg_id);
			$sql->adInserir('tipo', $tipo);
			$sql->adInserir('datahora', $data);
			if ($msg_cripto_id) $sql->adInserir('msg_cripto_id', $msg_cripto_id);
			$sql->adInserir('nome_de', $Aplic->usuario_nome);
			$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
			$sql->adInserir('nome_para', nome_usuario($destinatario));
			$sql->adInserir('funcao_para', funcao_usuario($destinatario));
			if (isset($anotacao_id)) $sql->adInserir('anotacao_id', $anotacao_id);
			
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!'.$bd->stderr(true));
			$sql->limpar();
			}
		}		
	}

if ($tipo==4) $Aplic->setMsg('Anotação gravada com sucesso!', UI_MSG_OK);
else if ($tipo==2) $Aplic->setMsg('Resposta enviada com sucesso!', UI_MSG_OK);

//se for despacho
if ($encaminha == 1 && $tipo==1) {
	include ("grava_encaminha.php");
	exit;
	}

//se for anotacao não irá selecionar destinatários e arquivar ou pender ou apenas marcar como vista
if ($tipo==4) {
	if ($arquivar == 1) $status=4; 
	else if ($arquivar == 2) $status=3; 
	else $status=($status_original< 3 ? 1 : $status_original);
	foreach($vetor_msg_usuario as $chave => $valor){ 	
		$sql->adTabela('msg_usuario');
		$sql->adAtualizar('status', $status);
		$sql->adOnde('msg_usuario_id = '.$valor);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!'.$bd->stderr(true));
		$sql->limpar();

		//se nao chegou a abrir as msg serao consideradas como lidas
		$sql->adTabela('msg_usuario');
		$sql->adCampo('datahora_leitura, de_id, aviso_leitura');
		$sql->adOnde('msg_usuario_id = '.$valor);
		$rs_leitura = $sql->Linha();
		$sql->limpar();


		if (!$rs_leitura['datahora_leitura']) {
			$sql->adTabela('msg_usuario');
			$sql->adAtualizar('datahora_leitura', date('Y-m-d H:i:s'));
			$sql->adAtualizar('status', 1);
			$sql->adOnde('msg_usuario_id = '.$valor);
			$sql->adOnde('datahora_leitura IS NULL');
			if (!$sql->exec()) die('Não foi possível atualizar os dados na tabela msg_usuario!'.$bd->stderr(true));
			$sql->limpar();
			if ($rs_leitura['aviso_leitura']==1) aviso_leitura ($rs_leitura['de_id'], $valor, $data);
			}
	
		}
	$Aplic->redirecionar('m=email&a=lista_msg'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status : '&status=1'));	
	}

//se for responder usará o Id dos remetentes da mensagem ou do criador da mesma como destinataio
if ($tipo==2) {
	
	if ($arquivar == 1) $status=4; 
	else if ($arquivar == 2) $status=3; 
	else $status=($status_original< 3 ? 1 : $status_original);
	foreach($vetor_msg_usuario as $chave => $valor){ 	
		$remetentes =array();
		if ($receber_resposta=='remetentes'){
			$sql->adTabela('msg_usuario');
			$sql->adCampo('de_id');
			$sql->adOnde('msg_usuario_id = '.$valor);
			$remetentes = $sql->Lista();
			$sql->Limpar();
			}
		else{
			$sql->adTabela('msg');
			$sql->adUnir('msg_usuario','msg_usuario','msg.msg_id=msg_usuario.msg_id');
			$sql->adCampo('msg.de_id');
			$sql->adOnde('msg_usuario.msg_usuario_id = '.$valor);
			$remetentes = $sql->Lista();
			$sql->Limpar();
			}
		foreach($remetentes as $remetente){
			$sql->adTabela('msg_usuario');
			$sql->adInserir('de_id', $Aplic->usuario_id);
			$sql->adInserir('para_id', $remetente['de_id']);
			$sql->adInserir('msg_id', $msg_id);
			$sql->adInserir('tipo', $tipo);
			if ($msg_cripto_id) $sql->adInserir('msg_cripto_id', $msg_cripto_id);
			$sql->adInserir('datahora', $data);
			$sql->adInserir('nome_de', $Aplic->usuario_nome);
			$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
			$sql->adInserir('nome_para', nome_usuario($remetente['de_id']));
			$sql->adInserir('funcao_para', funcao_usuario($remetente['de_id']));
			if (isset($anotacao_id)) $sql->adInserir('anotacao_id', $anotacao_id);
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!'.$bd->stderr(true));
			$sql->limpar();
	  	$encaminha = retorna_encaminha($remetente['de_id']);
	  	if (!empty($encaminha)) {
				$sql->adTabela('msg_usuario');
				$sql->adInserir('de_id', $remetente['de_id']);
				$sql->adInserir('para_id', $encaminha);
				$sql->adInserir('msg_id', $msg_id);
				$sql->adInserir('tipo', $tipo);
				if ($msg_cripto_id) $sql->adInserir('msg_cripto_id', $msg_cripto_id);
				$sql->adInserir('datahora', $data);
				$sql->adInserir('nome_de', nome_usuario($remetente['de_id']));
				$sql->adInserir('funcao_de', funcao_usuario($remetente['de_id']));
				$sql->adInserir('nome_para', nome_usuario($encaminha));
				$sql->adInserir('funcao_para', funcao_usuario($encaminha));
				if (isset($anotacao_id)) $sql->adInserir('anotacao_id', $anotacao_id);
				if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!'.$bd->stderr(true));
				$sql->limpar();
	    	} 
			$encaminha = "";
			
			$sql->adTabela('msg_usuario');
			$sql->adAtualizar('status', $status);
			$sql->adOnde('msg_usuario_id='.$valor);
			if (!$sql->exec()) die('Não foi possivel alterar os valores de msg_usuario!'.$bd->stderr(true));
			$sql->limpar();	
			//se nao chegou a abrir as msg serao consideradas como lidas
			
			$sql->adTabela('msg_usuario');
			$sql->adAtualizar('datahora_leitura', $data);
			$sql->adOnde('msg_usuario_id='.$valor);
			$sql->adOnde('datahora_leitura IS NULL');
			if (!$sql->exec()) die('Não foi possivel alterar os valores de msg_usuario!'.$bd->stderr(true));
			$sql->limpar();
			}
		}
	}		

$Aplic->redirecionar('m=email&a=lista_msg'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status : '&status=1'));	
?>
