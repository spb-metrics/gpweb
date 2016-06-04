<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
 
$data = date('Y-m-d H:i:s');
$tipo=getParam($_REQUEST, 'tipo', null);
$arquivar=getParam($_REQUEST, 'arquivar', null);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());

$outros_emails=getParam($_REQUEST, 'outros_emails','');
$status_original=getParam($_REQUEST, 'status_original', 0);
$tipo_cripto=getParam($_REQUEST, 'tipo_cripto', 0);
$senha=getParam($_REQUEST, 'senha', '');
$senha_antiga=getParam($_REQUEST, 'senha_antiga', '');
$msg_id_cripto=getParam($_REQUEST, 'msg_id_cripto', null);
$msg_cripto_id=getParam($_REQUEST, 'msg_cripto_id', null);
$prazo_responder=getParam($_REQUEST, 'prazo_responder', null);
$data_limite=getParam($_REQUEST, 'data_limite', null);

$ListaPARAtarefa=getParam($_REQUEST, 'ListaPARAtarefa', array());
$atividade=array();
if (count($ListaPARAtarefa)){
	foreach ($ListaPARAtarefa as $chave => $valor){
		$dupla=explode(':', $valor);
		$atividade[$dupla[0]]=($dupla[1] ? $dupla[1] : null);
		}
	}



if (!count($ListaPARA) && $outros_emails) {
	$ListaPARA[]=0;
	}
elseif (!count($ListaPARA)){
	$Aplic->setMsg('Não foi localizado nenhum destinatário para o envio!', UI_MSG_ERRO);
	
	if ($arquivar == 1)$status = 4;
	if ($arquivar == 2)$status = 3;
	
	$Aplic->redirecionar('m=email&a=lista_msg'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status : '&status=1'));
	}

if (isset($_REQUEST['vetor_msg_usuario'])) $vetor_msg_usuario = getParam($_REQUEST, 'vetor_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id'])) $vetor_msg_usuario[] = getParam($_REQUEST, 'msg_usuario_id', null);
else  $vetor_msg_usuario = array();


if ($data_limite){
	$data_limite=new CData($data_limite);
	$data_limite->setTime(23, 59);
	}


$sql = new BDConsulta;
// tipo=3 encaminhamento

//variavel abaixo será para ser o mesmo tempo da gravação de anotação e dos despachos, como forma de saber exatamente quais foram os destinatários dos despachos.
$data_anot=getParam($_REQUEST, 'data_anot', 0);

if ($data_anot) $data=$data_anot;
if ($arquivar == 1 || $arquivar == 2) $nao_aviso = 1;





foreach($vetor_msg_usuario as $chave => $msg_usuario_id){ 
	$sql->adTabela('msg_usuario');
	$sql->adCampo('msg_id');
	$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
	$msg_id=$sql->Resultado();
	$sql->limpar();
	

		if ($msg_cripto_id){
		//recuperar o texto em claro
		
		$sql->adTabela('msg_cripto');
		$sql->adCampo('*');
		$sql->adOnde('msg_cripto_id = '.$msg_cripto_id);
		$linha_cripto = $sql->Linha();
		$sql->limpar();
		
		
		if ($linha_cripto['tipo_cripto']==1)	openssl_open(base64_decode($linha_cripto['texto']), $texto_claro, base64_decode($linha_cripto['chave_envelope']), $Aplic->chave_privada);
		elseif ($linha_cripto['tipo_cripto']==2){
			require_once BASE_DIR.'/classes/cifra.class.php';
			$cifra = new cifra; 
			$cifra->set_key($senha_antiga);
			$texto_claro=$cifra->decriptar($linha_cripto['texto']);
			}
		
		
		//lista de chaves publicas
		if ($tipo_cripto==1) {
			$chave_publica=array();
			foreach($ListaPARA as $chave => $valor){
				$sql->adTabela('chaves_publicas');
				$sql->adCampo('chave_publica_chave, chave_publica_id');
				$sql->adOnde('chave_publica_usuario = '.$valor);
				$sql->adOnde('chave_publica_data = (SELECT max(chave_publica_data) FROM chaves_publicas WHERE chave_publica_usuario = '.$valor.')');
				$chave_encontrada = $sql->Linha();
				$sql->Limpar();
				if ($chave_encontrada) {
					$chave_publica[$valor]=$chave_encontrada['chave_publica_chave'];
					$chave_publica_id[$valor]=$chave_encontrada['chave_publica_id'];
					}
				}
			}
			
		$msg_cripto_id=0;	
		if ($tipo_cripto==2){
			require_once BASE_DIR.'/classes/cifra.class.php';
			$cifra = new cifra; 
			$cifra->set_key($senha);
			$texto_cifrado = $cifra->encriptar($texto_claro);	
			$sql->adTabela('msg_cripto');
			$sql->adInserir('msg_cripto_de', $Aplic->usuario_id);
			$sql->adInserir('msg_cripto_msg', $msg_id_cripto);
			$sql->adInserir('texto', $texto_cifrado);
			$sql->adInserir('tipo_cripto', '2');
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_cripto!');
			$msg_cripto_id=$bd->Insert_ID('msg_cripto','msg_cripto_id');
			$sql->Limpar();
			}

			
		}	
		
	foreach($ListaPARA as $chave => $valor){ 
		$msg_cripto=0;
		if ($msg_id==$msg_id_cripto && $tipo_cripto==1 && !isset($chave_publica[$valor])) $sem_chave[]=$valor;
		else{
				if ($tipo_cripto==1) {
				//usará as chaves publicas	
				$chave_vetor=array('0' => $chave_publica[$valor], '1'=>'');
				openssl_seal($texto_claro, $selado, $echave, array($chave_vetor));
				$selado = base64_encode($selado);
				$chave_envelope = base64_encode($echave[0]);
			  $sql->adTabela('msg_cripto');
			  $sql->adInserir('msg_cripto_de', $Aplic->usuario_id);
				$sql->adInserir('msg_cripto_para', $valor);
				$sql->adInserir('msg_cripto_msg', $msg_id);
				$sql->adInserir('texto', $selado);
				$sql->adInserir('chave_envelope', $chave_envelope);
				$sql->adInserir('tipo_cripto', '1');
				if ($chave_publica_id[$valor]) $sql->adInserir('chave_publica', $chave_publica_id[$valor]);
				if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de usuários da mensagem!');
				$msg_cripto_id=$bd->Insert_ID('msg_cripto','msg_cripto_id');
				$sql->Limpar();
				}
			
			
			
			$sql->adTabela('msg_usuario');
			$sql->adInserir('de_id', $Aplic->usuario_id);
			$sql->adInserir('para_id', $valor);
			$sql->adInserir('msg_id', $msg_id);
			$sql->adInserir('tipo', $tipo);
			$sql->adInserir('datahora', $data);
			$sql->adInserir('aviso_leitura', (in_array($valor, $ListaPARAaviso) ? '1' : '0'));
			$sql->adInserir('copia_oculta', (in_array($valor, $ListaPARAoculto) ? '1' : '0' ));
			$sql->adInserir('tarefa', (isset($atividade[$valor]) ? '1' : '0' ));
			if (isset($atividade[$valor]) && $atividade[$valor]) $sql->adInserir('tarefa_data', $atividade[$valor]);
			$sql->adInserir('nome_de', $Aplic->usuario_nome);
			$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
			$sql->adInserir('nome_para', nome_usuario($valor));
			$sql->adInserir('funcao_para', funcao_usuario($valor));
			if ($msg_cripto_id) $sql->adInserir('msg_cripto_id', $msg_cripto_id);
			if (isset($anotacao_id)) $sql->adInserir('anotacao_id', $anotacao_id);
			if ($prazo_responder && $data_limite) $sql->adInserir('data_limite', $data_limite->format(FMT_TIMESTAMP_MYSQL));
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
			$msg_usuario_id_novo=$bd->Insert_ID('msg_usuario','msg_usuario_id');
			$sql->Limpar();
			}
	  $encaminha = retorna_encaminha($valor);
	  if ($encaminha) {
	  	$msg_cripto_id=0;
	  	if ($msg_id==$msg_id_cripto && $tipo_cripto==1) {
				$sql->adTabela('chaves_publicas');
				$sql->adCampo('chave_publica_chave, chave_publica_id');
				$sql->adOnde('chave_publica_usuario ='.$encaminha);
				$sql->adOnde('chave_publica_data = (SELECT max(chave_publica_data) FROM chaves_publicas WHERE chave_publica_usuario = '.$encaminha.')');
				$chave_encaminhamento=$sql->Linha();
				$sql->Limpar();
				}
			$msg_cripto=0;	
			if ($msg_id==$msg_id_cripto && $tipo_cripto==1 && !$chave_encaminhamento['chave_publica_chave']) $sem_chave[]=$valor;
			else{
				if ($tipo_cripto==1) {
					$chave_vetor=array('0' => $chave_encaminhamento['chave_publica_chave'], '1'=>'');
					openssl_seal($texto_claro, $selado, $echave, array($chave_vetor));
					$selado = base64_encode($selado);
					$chave_envelope = base64_encode($echave[0]);
				  $sql->adTabela('msg_cripto');
				  $sql->adInserir('msg_cripto_de', $Aplic->usuario_id);
					$sql->adInserir('msg_cripto_para', $encaminha);
					$sql->adInserir('msg_cripto_msg', $msg_id);
					$sql->adInserir('texto', $selado);
					$sql->adInserir('chave_envelope', $chave_envelope);
					$sql->adInserir('tipo_cripto', '1');
					if ($chave_encaminhamento['chave_publica_id']) $sql->adInserir('chave_publica', $chave_encaminhamento['chave_publica_id']);
					if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de usuários da mensagem!');
					$msg_cripto_id=$bd->Insert_ID('msg_cripto','msg_cripto_id');
					$sql->Limpar();
					}
				$sql->adTabela('msg_usuario');
				$sql->adInserir('de_id', $valor);
				$sql->adInserir('para_id', $encaminha);
				$sql->adInserir('msg_id', $msg_id);
				$sql->adInserir('tipo', $tipo);
				$sql->adInserir('datahora', $data);
				$sql->adInserir('aviso_leitura', (in_array($encaminha, $ListaPARAaviso) ? '1' : '0' ));
				$sql->adInserir('copia_oculta', (in_array($encaminha, $ListaPARAoculto) ? '1' :'0' ));
				$sql->adInserir('tarefa', (isset($atividade[$encaminha]) ? '1' : '0' ));
				if (isset($atividade[$encaminha]) && $atividade[$encaminha]) $sql->adInserir('tarefa_data', $atividade[$encaminha]);
				$sql->adInserir('nome_de', nome_usuario($valor));
				$sql->adInserir('funcao_de', funcao_usuario($valor));
				$sql->adInserir('nome_para', nome_usuario($encaminha));
				$sql->adInserir('funcao_para', funcao_usuario($encaminha));
				if ($msg_cripto_id) $sql->adInserir('msg_cripto_id', $msg_cripto_id);
				if (isset($anotacao_id)) $sql->adInserir('anotacao_id', $anotacao_id);
				if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
				$sql->Limpar();
				} 
			}
	  $encaminha = '';
		}	
	
	if ($arquivar == 1 || $arquivar == 2){	
		if ($arquivar == 1)$status = 4;
		if ($arquivar == 2)$status = 3;
		
		$sql->adTabela('msg_usuario');
		$sql->adAtualizar('status', $status);
		$sql->adOnde(($status_original==5 ? 'de_id=' : 'para_id=').$Aplic->usuario_id);
		$sql->adOnde('msg_usuario_id ='.$msg_usuario_id);
		if (!$sql->exec()) die('Não foi possivel alterar os valores de msg_usuario'.$bd->stderr(true));
		$sql->limpar();
		}

	//para cada mensagem preciso verificar documentos em anexo

	$sql->adTabela('anexos');
	$sql->adCampo('modelo');
	$sql->adOnde('modelo >0');
	$sql->adOnde('msg_id='.$msg_id);
	$modelos = $sql->Lista();
	$sql->limpar();
	
	foreach ($modelos as $modelo){
		
		
		foreach($ListaPARA as $chave => $valor){
			$sql->adTabela('modelo_usuario');
			$sql->adInserir('de_id', $Aplic->usuario_id);
			$sql->adInserir('para_id', $valor);
			if ($modelo['modelo']) $sql->adInserir('modelo_id', $modelo['modelo']);
			$sql->adInserir('tipo', $tipo);
			$sql->adInserir('datahora', $data);
			$sql->adInserir('aviso_leitura', (in_array($valor, $ListaPARAaviso) ? '1' : '0'));
			$sql->adInserir('copia_oculta', (in_array($valor, $ListaPARAoculto) ? '1' : '0' ));
			$sql->adInserir('tarefa', (isset($atividade[$valor]) ? '1' : '0' ));
			if (isset($atividade[$valor]) && $atividade[$valor]) $sql->adInserir('tarefa_data', $atividade[$valor]);
			$sql->adInserir('nome_de', $Aplic->usuario_nome);
			$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
			$sql->adInserir('nome_para', nome_usuario($valor));
			$sql->adInserir('funcao_para', funcao_usuario($valor));
			if (isset($modelo_anotacao_id) && $modelo_anotacao_id) $sql->adInserir('modelo_anotacao_id', $modelo_anotacao_id);
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
			$sql->Limpar();
			$encaminha = retorna_encaminha($valor);
	  	if ($encaminha) {
				$sql->adTabela('modelo_usuario');
				$sql->adInserir('de_id', $valor);
				$sql->adInserir('para_id', $encaminha);
				$sql->adInserir('modelo_id', $modelo_id);
				$sql->adInserir('tipo', $tipo);
				$sql->adInserir('datahora', $data);
				$sql->adInserir('aviso_leitura', (in_array($encaminha, $ListaPARAaviso) ? '1' : '0' ));
				$sql->adInserir('copia_oculta', (in_array($encaminha, $ListaPARAoculto) ? '1' :'0' ));
				$sql->adInserir('tarefa', (isset($atividade[$encaminha]) ? '1' : '0' ));
				if (isset($atividade[$encaminha]) && $atividade[$encaminha]) $sql->adInserir('tarefa_data', $atividade[$encaminha]);
				$sql->adInserir('nome_de', nome_usuario($valor));
				$sql->adInserir('funcao_de', funcao_usuario($valor));
				$sql->adInserir('nome_para', nome_usuario($encaminha));
				$sql->adInserir('funcao_para', funcao_usuario($encaminha));
				if (isset($modelo_anotacao_id) && $modelo_anotacao_id) $sql->adInserir('modelo_anotacao_id', $modelo_anotacao_id);
				if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
				$sql->Limpar();
				}
			}
		}

//creio que agora ao responder, anotar ou encaminhar ira mostrar na tela, e todas selecionadas serao considerads lidas
	$sql->adTabela('msg_usuario');
	$sql->adCampo('datahora_leitura, de_id, aviso_leitura');
	$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
	$sql->adOnde('para_id = '.$Aplic->usuario_id);
	$rs_leitura = $sql->Linha();
	$sql->Limpar();
	if (!$rs_leitura['datahora_leitura']) {
		$sql->adTabela('msg_usuario');
		$sql->adAtualizar('datahora_leitura', $data);
		$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
		$sql->adOnde('para_id = '.$Aplic->usuario_id);
		if (!$sql->exec()) die('Não foi possível atualizar msg_usuario.');
		$sql->limpar();
		if ($rs_leitura['aviso_leitura']==1) aviso_leitura ($rs_leitura['de_id'], $msg_usuario_id, $data);
		}
	

	
	//email externo
	$email_destinatarios=array();
	if ($outros_emails) $email_destinatarios=explode(';',str_replace(' ', '', $outros_emails)); 
	
	foreach ($email_destinatarios as $email_extra){
		$sql->adTabela('msg_usuario_ext');
		$sql->adInserir('de_id', $Aplic->usuario_id);
		$sql->adInserir('para', $email_extra);
		$sql->adInserir('msg_id', $msg_id);
		$sql->adInserir('datahora', $data);
		$sql->adInserir('tipo', 3);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario_ext!');
		$msg_usuario_id=$bd->Insert_ID('msg_usuario_ext','msg_usuario_ext_id');
		$sql->Limpar();	
		}
	
	
	if ($ListaPARAexterno){
		$sql->adTabela('contatos');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_email, contato_email2, usuario_id');
		$sql->esqUnir('usuarios', 'usuarios', 'contato_id = usuario_contato');
		$sql->adOnde('usuario_id IN ('.implode(',',(array)$ListaPARAexterno).')');
		$sql_resultados = $sql->Lista();
		$sql->limpar();
		foreach ((array)$sql_resultados as $rs){
			if (email_valido($rs['contato_email']))$email_destinatarios[]=$rs['contato_email'];
			if (email_valido($rs['contato_email2']))$email_destinatarios[]=$rs['contato_email2'];
			}
		}
	
	if (count($email_destinatarios)){
		
		$sql->adTabela('msg');
		$sql->esqUnir('msg_usuario', 'msg_usuario','msg_usuario.msg_id=msg.msg_id');
		$sql->adCampo('referencia');
		$referencia = $sql->Resultado();
		$sql->limpar();
		$texto=texto_msg_email($msg_usuario_id_novo, $status, $Aplic->usuario_id, $senha);
		msg_email_externo($email_destinatarios, $referencia, $texto, $msg_id);
		}
	
	
	}
	
if ($tipo==1) $Aplic->setMsg('Despacho enviado com sucesso', UI_MSG_OK);
else if ($tipo==3) $Aplic->setMsg('Encaminhamento enviado com sucesso', UI_MSG_OK);
else $Aplic->setMsg(ucfirst($config['mensagem']).' enviad'.$config['genero_mensagem'].' com sucesso', UI_MSG_OK);

if ($arquivar == 1)$status = 4;
if ($arquivar == 2)$status = 3;

$Aplic->redirecionar('m=email&a=lista_msg'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status : '&status=1'));

?>
