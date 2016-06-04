<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $bd;

$precedencia=getParam($_REQUEST, 'precedencia', 0);
$class_sigilosa=getParam($_REQUEST, 'class_sigilosa', 0);
$referencia=getParam($_REQUEST, 'referencia', '');
$texto=getParam($_REQUEST, 'texto', '');

if ($Aplic->usuario_rodape) $texto.='<br>'.$Aplic->usuario_rodape;

$cm=getParam($_REQUEST, 'cm', 0);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());

$outros_emails=getParam($_REQUEST, 'outros_emails','');
$msg_projeto=getParam($_REQUEST, 'msg_projeto', '');
$msg_tarefa=getParam($_REQUEST, 'msg_tarefa', '');
$msg_pratica=getParam($_REQUEST, 'msg_pratica', '');
$msg_acao=getParam($_REQUEST, 'msg_acao', '');
$msg_indicador=getParam($_REQUEST, 'msg_indicador', '');
$msg_objetivo=getParam($_REQUEST, 'msg_objetivo', '');
$msg_tema=getParam($_REQUEST, 'msg_tema', '');
$msg_estrategia=getParam($_REQUEST, 'msg_estrategia', '');
$msg_fator=getParam($_REQUEST, 'msg_fator', '');
$msg_perspectiva=getParam($_REQUEST, 'msg_perspectiva', '');
$msg_canvas=getParam($_REQUEST, 'msg_canvas', '');
$msg_meta=getParam($_REQUEST, 'msg_meta', '');
$msg_monitoramento=getParam($_REQUEST, 'msg_monitoramento', '');
$msg_operativo=getParam($_REQUEST, 'msg_operativo', '');

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
	$Aplic->redirecionar('m=email&a=lista_msg');
	}


$msg_usuario_id=0;
$tipo_cripto=getParam($_REQUEST, 'tipo_cripto', 0);
$senha=getParam($_REQUEST, 'senha', '');
$data = date('Y-m-d H:i:s');

$lista_doc=getParam($_REQUEST, 'lista_doc', array());

$lista_doc_referencia=getParam($_REQUEST, 'lista_doc_referencia', array());

$lista_msg_referencia=getParam($_REQUEST, 'lista_msg_referencia', array());

$assinatura='';
if (function_exists('openssl_sign') && $Aplic->chave_privada)	{
	$identificador=$precedencia.$class_sigilosa.$referencia.$Aplic->usuario_id.$texto.$tipo_cripto.$cm.$data;
	openssl_sign($identificador, $assinatura, $Aplic->chave_privada, OPENSSL_ALGO_SHA1);
	}


$sql = new BDConsulta;
$sql->adTabela('msg');
$sql->adInserir('precedencia', $precedencia);
$sql->adInserir('class_sigilosa', $class_sigilosa);
$sql->adInserir('referencia', $referencia);
$sql->adInserir('de_id', $Aplic->usuario_id);
if (!$tipo_cripto) $sql->adInserir('texto', $texto);
$sql->adInserir('cripto', $tipo_cripto);
$sql->adInserir('cm', $cm);
$sql->adInserir('data_envio', $data);
$sql->adInserir('nome_de', $Aplic->usuario_nome);
$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
if ($assinatura) $sql->adInserir('assinatura', base64_encode($assinatura));
if ($Aplic->chave_publica_id) $sql->adInserir('chave_publica', $Aplic->chave_publica_id);
if(!$Aplic->profissional){
	if ($msg_projeto) $sql->adInserir('msg_projeto', $msg_projeto);
	if ($msg_tarefa) $sql->adInserir('msg_tarefa', $msg_tarefa);
	if ($msg_pratica) $sql->adInserir('msg_pratica', $msg_pratica);
	if ($msg_acao) $sql->adInserir('msg_acao', $msg_acao);
	if ($msg_indicador) $sql->adInserir('msg_indicador', $msg_indicador);
	if ($msg_objetivo) $sql->adInserir('msg_objetivo', $msg_objetivo);
	if ($msg_tema) $sql->adInserir('msg_tema', $msg_tema);
	if ($msg_estrategia) $sql->adInserir('msg_estrategia', $msg_estrategia);
	if ($msg_fator) $sql->adInserir('msg_fator', $msg_fator);
	if ($msg_perspectiva) $sql->adInserir('msg_perspectiva', $msg_perspectiva);
	if ($msg_canvas) $sql->adInserir('msg_canvas', $msg_canvas);
	if ($msg_meta) $sql->adInserir('msg_meta', $msg_meta);
	if ($msg_monitoramento) $sql->adInserir('msg_monitoramento', $msg_monitoramento);
	if ($msg_operativo) $sql->adInserir('msg_operativo', $msg_operativo);
	}
if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de msgs!');
$msg_id=$bd->Insert_ID('msg','msg_id');
$sql->Limpar();

$uuid=getParam($_REQUEST, 'uuid', null);

if($Aplic->profissional){
	if ($uuid){
		$sql->adTabela('msg_gestao');
		$sql->adAtualizar('msg_gestao_msg', (int)$msg_id);
		$sql->adAtualizar('msg_gestao_uuid', null);
		$sql->adOnde('msg_gestao_uuid=\''.$uuid.'\'');
		$sql->exec();
		$sql->limpar();
		}
	}



$sem_chave=array();

$todos_enviados=$ListaPARA;

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
	$texto_cifrado = $cifra->encriptar($texto);	
	
	$sql->adTabela('msg_cripto');
	$sql->adInserir('msg_cripto_de', $Aplic->usuario_id);
	$sql->adInserir('msg_cripto_msg', $msg_id);
	$sql->adInserir('texto', $texto_cifrado);
	$sql->adInserir('tipo_cripto', '2');
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_cripto!');
	$msg_cripto_id=$bd->Insert_ID('msg_cripto','msg_cripto_id');
	$sql->Limpar();
	}

foreach($ListaPARA as $chave => $valor){ 
	if ($valor){	
		//cripto chave dupla e usuario não tem chave pública
		if ($tipo_cripto==1 && !isset($chave_publica[$valor])) $sem_chave[]=$valor;
		else{
			if ($tipo_cripto==1) {
				$chave_vetor=array('0' => $chave_publica[$valor], '1'=>'');
				openssl_seal($texto, $selado, $echave, array($chave_vetor));
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
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
			$msg_usuario_id=$bd->Insert_ID('msg_usuario','msg_usuario_id');
			$sql->Limpar();
			}
		$msg_usuario_id_novo=$msg_usuario_id;
	  $encaminha = retorna_encaminha($valor);
	  if ($encaminha) {
			$todos_enviados[]=$encaminha;
			if ($tipo_cripto==1) {
				$sql->adTabela('chaves_publicas');
				$sql->adCampo('chave_publica_chave, chave_publica_id');
				$sql->adOnde('chave_publica_usuario ='.$encaminha);
				$sql->adOnde('chave_publica_data = (SELECT max(chave_publica_data) FROM chaves_publicas WHERE chave_publica_usuario = '.$encaminha.')');
				$chave_encaminhamento=$sql->Linha();
				$sql->Limpar();
				if ($chave_encaminhamento['chave_publica_chave']){
					$chave_vetor=array('0' => $chave_encaminhamento['chave_publica_chave'], '1'=>'');
					openssl_seal($texto, $selado, $echave, array($chave_vetor));
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
				else $sem_chave[]=$valor;
				}
			$sql->adTabela('msg_usuario');
			$sql->adInserir('de_id', $valor);
			$sql->adInserir('para_id', $encaminha);
			$sql->adInserir('msg_id', $msg_id);
			$sql->adInserir('tipo', '1');
			$sql->adInserir('datahora', $data);
			$sql->adInserir('aviso_leitura', (in_array($encaminha, $ListaPARAaviso) ? '1' : '0' ));
			$sql->adInserir('copia_oculta', (in_array($encaminha, $ListaPARAoculto) ? '1' :'0' ));	
			$sql->adInserir('tarefa', (isset($atividade[$encaminha]) ? '1' : '0' ));
			if (isset($atividade[$valor]) && $atividade[$encaminha]) $sql->adInserir('tarefa_data', $atividade[$encaminha]);
			$sql->adInserir('nome_de', nome_usuario($valor));
			$sql->adInserir('funcao_de', funcao_usuario($valor));
			$sql->adInserir('nome_para', nome_usuario($encaminha));
			$sql->adInserir('funcao_para', funcao_usuario($encaminha));
			if ($msg_cripto_id) $sql->adInserir('msg_cripto_id', $msg_cripto_id);
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
			$msg_usuario_id=$bd->Insert_ID('msg_usuario','msg_usuario_id');
			$sql->Limpar();
			} 
	  $encaminha = '';
		}
	}	

//verificar se remetente é um dos destinatários, se não for criar uma mensagem já lida para o mesmo dentro das arquivadas
if (!in_array($Aplic->usuario_id, $todos_enviados)){
	if ($tipo_cripto==1){
		$sql->adTabela('chaves_publicas');
		$sql->adCampo('chave_publica_chave, chave_publica_id');
		$sql->adOnde('chave_publica_usuario ='.$Aplic->usuario_id);
		$sql->adOnde('chave_publica_data = (SELECT max( chave_publica_data) FROM chaves_publicas WHERE chave_publica_usuario = '.$Aplic->usuario_id.')');
		$chave_publica_remetente=$sql->Linha();
		$sql->Limpar();
		$chave_vetor=array('0' => $chave_publica_remetente['chave_publica_chave'], '1'=>'');
		openssl_seal($texto, $selado, $echave, array($chave_vetor));
		$selado = base64_encode($selado);
		$chave_envelope = base64_encode($echave[0]);
	  $sql->adTabela('msg_cripto');
	  $sql->adInserir('msg_cripto_de', $Aplic->usuario_id);
		$sql->adInserir('msg_cripto_para', $Aplic->usuario_id);
		$sql->adInserir('msg_cripto_msg', $msg_id);
		$sql->adInserir('texto', $selado);
		$sql->adInserir('chave_envelope', $chave_envelope);
		$sql->adInserir('tipo_cripto', '1');
		if ($chave_publica_remetente['chave_publica_id']) $sql->adInserir('chave_publica', $chave_publica_remetente['chave_publica_id']);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de msg_cripto!');
		$msg_cripto_id=$bd->Insert_ID('msg_cripto','msg_cripto_id');
		$sql->Limpar();
		$sql->adTabela('msg_usuario');
		$sql->adInserir('de_id', $Aplic->usuario_id);
		$sql->adInserir('para_id', $Aplic->usuario_id);
		$sql->adInserir('msg_id', $msg_id);
		$sql->adInserir('status', '4');
		$sql->adInserir('tipo', '3');
		$sql->adInserir('datahora', $data);
		$sql->adInserir('datahora_leitura', $data);
		$sql->adInserir('nome_de', $Aplic->usuario_nome);
		$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
		$sql->adInserir('nome_para', $Aplic->usuario_nome);
		$sql->adInserir('funcao_para', $Aplic->usuario_funcao);
		if ($msg_cripto_id) $sql->adInserir('msg_cripto_id', $msg_cripto_id);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
		$msg_usuario_id=$bd->Insert_ID('msg_usuario','msg_usuario_id');
		$sql->Limpar();	
		}
	}

grava_anexo(getParam($_REQUEST, 'doc_nr', ''), getParam($_REQUEST, 'doc_tipo', ''), 'doc', getParam($_REQUEST, 'nome_fantasia', ''));



//lista das que já estão incluidos
$sql->adTabela('referencia');
$sql->adCampo('referencia_doc_pai');
$sql->adOnde('referencia_msg_filho ='.$msg_id);
$resultados_doc=$sql->Lista();
$sql->limpar();
$referencia_doc_pai=array();
foreach ($resultados_doc as $linha_ref) $referencia_doc_pai[]=$linha_ref['referencia_doc_pai'];
foreach($lista_doc_referencia as $chave => $doc_id_pai){
	if (!in_array($doc_id_pai, $referencia_doc_pai)){
		//se já não foi inserido, será inserido
		$sql->adTabela('referencia');
	  $sql->adInserir('referencia_doc_pai', $doc_id_pai);
		$sql->adInserir('referencia_msg_filho', $msg_id);
		$sql->adInserir('referencia_responsavel', $Aplic->usuario_id);
		$sql->adInserir('referencia_data', date('Y-m-d H:i:s'));
		$sql->adInserir('referencia_nome_de', $Aplic->usuario_nome);
		$sql->adInserir('referencia_funcao_de', $Aplic->usuario_funcao);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela referencia!');
		$sql->limpar();
		$referencia_doc_pai[]=$doc_id_pai;
		
		}
	}


//inserir as mensagens que sãos referencia
//lista das que já estão incluidos
$sql->adTabela('referencia');
$sql->adCampo('referencia_msg_pai');
$sql->adOnde('referencia_msg_filho ='.$msg_id);
$resultados_msg=$sql->Lista();
$sql->limpar();
$referencia_msg_pai=array();
foreach ($resultados_msg as $linha_ref) $referencia_msg_pai[]=$linha_ref['referencia_msg_pai'];
foreach($lista_msg_referencia as $chave => $msg_id_pai){
	if (!in_array($msg_id_pai, $referencia_msg_pai)){
		//se já não foi inserido, será inserido
		$sql->adTabela('referencia');
	  $sql->adInserir('referencia_msg_pai', $msg_id_pai);
		$sql->adInserir('referencia_msg_filho', $msg_id);
		$sql->adInserir('referencia_responsavel', $Aplic->usuario_id);
		$sql->adInserir('referencia_data', date('Y-m-d H:i:s'));
		$sql->adInserir('referencia_nome_de', $Aplic->usuario_nome);
		$sql->adInserir('referencia_funcao_de', $Aplic->usuario_funcao);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela referencia!');
		$sql->limpar();
		$referencia_msg_pai[]=$msg_id_pai;
		}
	}





//grava documento_anexo
foreach($lista_doc as $chave => $modelo_id){
	$sql = new BDConsulta;
	$sql->adTabela('modelos');
	$sql->esqUnir('usuarios','usuarios','modelo_criador_original=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('modelos_tipo', 'modelos_tipo', 'modelo_tipo_id = modelo_tipo');
	$sql->esqUnir('modelos_dados','modelos_dados','modelo_dados_modelo=modelos.modelo_id');
	$sql->esqUnir('modelos_anexos', 'modelos_anexos', 'modelos_anexos.modelo_id=modelos.modelo_id');
	$sql->adCampo('modelo_tipo_nome, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, modelo_assunto, modelo_numero, modelos.modelo_id, modelo_tipo, modelo_criador_original, modelo_data, modelo_versao_aprovada');
	$sql->adOnde('modelos.modelo_id = '.$modelo_id);
	$linha=$sql->Linha();
	$sql->Limpar();
	$assinatura='';
	if (function_exists('openssl_sign') && $Aplic->chave_privada)	{
		$identificador=$msg_id.$linha['modelo_assunto'].$Aplic->usuario_id.$linha['modelo_tipo_nome'].$data.$modelo_id;
		openssl_sign($identificador, $assinatura, $Aplic->chave_privada);
		}
	$sql->adTabela('anexos');
	$sql->adInserir('msg_id', $msg_id);
	$sql->adInserir('nome', $linha['modelo_assunto']);
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('tipo_doc', $linha['modelo_tipo_nome']);
	$sql->adInserir('nome_de', $Aplic->usuario_nome);
	$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
	$sql->adInserir('data_envio', $data);
	$sql->adInserir('modelo', $modelo_id);
	if ($assinatura) $sql->adInserir('assinatura',  base64_encode($assinatura));
	if ($Aplic->chave_publica_id) $sql->adInserir('chave_publica', $Aplic->chave_publica_id);
	if (!$sql->exec()) echo ('Não foi possível inserir os anexos na tabela anexos!');
	$sql->Limpar();

		
	}

if ($Aplic->usuario_cm){
	$sql->adTabela('anotacao');
	$sql->adInserir('msg_id', $msg_id);
	$sql->adInserir('datahora', $data);
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('texto', 'Encaminhada pelo CM');
	$sql->adInserir('tipo', '3');
	$sql->adInserir('nome_de', $Aplic->usuario_nome);
	$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de anotações!');
	$sql->Limpar();
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
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario_ext!');
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
	$texto=texto_msg_email($msg_usuario_id_novo, 0, $Aplic->usuario_id, $senha);
	msg_email_externo($email_destinatarios, $referencia, $texto, $msg_id);
	}

if (!$msg_id) {
	$Aplic->setMsg('Campo Id da mensagem vazio. Verifique se o anexo não é muito grande!', UI_MSG_ERRO);
	$Aplic->redirecionar('m=email&a=lista_msg');
  } 
elseif(count($sem_chave)){
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('usuario_id IN ('.implode(',',$sem_chave).')');
	$lista_sem_chave=$sql->listaVetorChave('usuario_id','nome_usuario');
	$sql->Limpar();
	$plural=(count($sem_chave) > 1 ? 's' : '');
	echo "<script language=Javascript>alert('O$plural seguinte$plural destinatário$plural não tem chave pública divulgada, portanto não foi possível enviar mensagem para o$plural mesmo$plural: ".implode(',', $lista_sem_chave)."')</script>";
	}   
else $Aplic->setMsg('Sucesso no envio!', UI_MSG_OK);


if ($dialogo){
	echo '<script language="javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	}

if ($Aplic->profissional){
	$sql->adTabela('msg_gestao');
	$sql->adCampo('msg_gestao.*');
	$sql->adOnde('msg_gestao_msg='.(int)$msg_id);
	$sql->adOrdem('msg_gestao_ordem ASC');
	$linha=$sql->linha();
	$sql->limpar();
	
	$sql->adTabela('msg_gestao');
	$sql->adCampo('count(msg_gestao_id)');
	$sql->adOnde('msg_gestao_msg='.(int)$msg_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	
	if ($linha['msg_gestao_tarefa'] && $qnt==1) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['msg_gestao_tarefa'];
	elseif ($linha['msg_gestao_projeto'] && $qnt==1) $endereco='m=projetos&a=ver&projeto_id='.$linha['msg_gestao_projeto'];
	elseif ($linha['msg_gestao_perspectiva'] && $qnt==1) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['msg_gestao_perspectiva'];
	elseif ($linha['msg_gestao_tema'] && $qnt==1) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['msg_gestao_tema'];
	elseif ($linha['msg_gestao_objetivo'] && $qnt==1) $endereco='m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$linha['msg_gestao_objetivo'];
	elseif ($linha['msg_gestao_fator'] && $qnt==1) $endereco='m=praticas&a=fator_ver&pg_fator_critico_id='.$linha['msg_gestao_fator'];
	elseif ($linha['msg_gestao_estrategia'] && $qnt==1) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['msg_gestao_estrategia'];
	elseif ($linha['msg_gestao_meta'] && $qnt==1) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['msg_gestao_meta'];
	elseif ($linha['msg_gestao_pratica'] && $qnt==1) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['msg_gestao_pratica'];
	elseif ($linha['msg_gestao_indicador'] && $qnt==1) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['msg_gestao_indicador'];
	elseif ($linha['msg_gestao_acao'] && $qnt==1) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['msg_gestao_acao'];
	elseif ($linha['msg_gestao_canvas'] && $qnt==1) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['msg_gestao_canvas'];
	elseif ($linha['msg_gestao_risco'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['msg_gestao_risco'];
	elseif ($linha['msg_gestao_risco_resposta'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['msg_gestao_risco_resposta'];
	elseif ($linha['msg_gestao_calendario'] && $qnt==1) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['msg_gestao_calendario'];
	elseif ($linha['msg_gestao_monitoramento'] && $qnt==1) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['msg_gestao_monitoramento'];
	elseif ($linha['msg_gestao_ata'] && $qnt==1) $endereco='m=atas&a=ata_ver&ata_id='.$linha['msg_gestao_ata'];
	elseif ($linha['msg_gestao_swot'] && $qnt==1) $endereco='m=swot&a=swot_ver&swot_id='.$linha['msg_gestao_swot'];
	elseif ($linha['msg_gestao_operativo'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['msg_operativo'];
	elseif ($linha['msg_gestao_instrumento'] && $qnt==1) $endereco='m=recursos&a=instrumento_ver&instrumento_id='.$linha['msg_gestao_instrumento'];
	elseif ($linha['msg_gestao_recurso'] && $qnt==1) $endereco='m=recursos&a=ver&recurso_id='.$linha['msg_gestao_recurso'];
	elseif ($linha['msg_gestao_problema'] && $qnt==1) $endereco='m=problema&a=problema_ver&problema_id='.$linha['msg_gestao_problema'];
	elseif ($linha['msg_gestao_demanda'] && $qnt==1) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['msg_gestao_demanda'];
	elseif ($linha['msg_gestao_programa'] && $qnt==1) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['msg_gestao_programa'];
	elseif ($linha['msg_gestao_evento'] && $qnt==1) $endereco='m=calendario&a=ver&evento_id='.$linha['msg_gestao_evento'];
	elseif ($linha['msg_gestao_link'] && $qnt==1) $endereco='m=links&a=ver&link_id='.$linha['msg_gestao_link'];
	elseif ($linha['msg_gestao_avaliacao'] && $qnt==1) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['msg_gestao_avaliacao'];
	elseif ($linha['msg_gestao_tgn'] && $qnt==1) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['msg_gestao_tgn'];
	elseif ($linha['msg_gestao_brainstorm'] && $qnt==1) $endereco='m=praticas&a=brainstorm_pro_ver&brainstorm_id='.$linha['msg_gestao_brainstorm'];
	elseif ($linha['msg_gestao_gut'] && $qnt==1) $endereco='m=praticas&a=gut_pro_ver&gut_id='.$linha['msg_gestao_gut'];
	elseif ($linha['msg_gestao_causa_efeito'] && $qnt==1) $endereco='m=praticas&a=causa_efeito_pro_ver&causa_efeito_id='.$linha['msg_gestao_causa_efeito'];
	elseif ($linha['msg_gestao_arquivo'] && $qnt==1) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['msg_gestao_arquivo'];
	elseif ($linha['msg_gestao_forum'] && $qnt==1) $endereco='m=foruns&a=ver&forum_id='.$linha['msg_gestao_forum'];
	elseif ($linha['msg_gestao_checklist'] && $qnt==1) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['msg_gestao_checklist'];
	elseif ($linha['msg_gestao_agenda'] && $qnt==1) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['msg_gestao_agenda'];
	elseif ($linha['msg_gestao_agrupamento'] && $qnt==1) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['msg_gestao_agrupamento'];
	elseif ($linha['msg_gestao_patrocinador'] && $qnt==1) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['msg_gestao_patrocinador'];
	elseif ($linha['msg_gestao_template'] && $qnt==1) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['msg_gestao_template'];
	elseif ($linha['msg_gestao_painel'] && $qnt==1) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['msg_gestao_painel'];
	elseif ($linha['msg_gestao_painel_odometro'] && $qnt==1) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['msg_gestao_painel_odometro'];
	elseif ($linha['msg_gestao_painel_composicao'] && $qnt==1) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['msg_gestao_painel_composicao'];
	elseif ($linha['msg_gestao_tr'] && $qnt==1) $endereco='m=tr&a=tr_ver&tr_id='.$linha['msg_gestao_tr'];
	elseif ($linha['msg_gestao_me'] && $qnt==1) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['msg_gestao_me'];
	else $endereco='m=email&a=lista_msg';
	$Aplic->redirecionar($endereco);
	}	 
elseif ($msg_tarefa) $Aplic->redirecionar('m=tarefas&a=ver&tarefa_id='.$msg_tarefa);
elseif ($msg_projeto) $Aplic->redirecionar('m=projetos&a=ver&projeto_id='.$msg_projeto);
elseif ($msg_pratica) $Aplic->redirecionar('m=praticas&a=pratica_ver&pratica_id='.$msg_pratica);
elseif ($msg_acao) $Aplic->redirecionar('m=praticas&a=plano_acao_ver&plano_acao_id='.$msg_acao);
elseif ($msg_indicador) $Aplic->redirecionar('m=praticas&a=indicador_ver&pratica_indicador_id='.$msg_indicador);
elseif ($msg_objetivo) $Aplic->redirecionar('m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$msg_objetivo);
elseif ($msg_tema) $Aplic->redirecionar('m=praticas&a=tema_ver&tema_id='.$msg_tema);
elseif ($msg_estrategia) $Aplic->redirecionar('m=praticas&a=estrategia_ver&pg_estrategia_id='.$msg_estrategia);
elseif ($msg_fator) $Aplic->redirecionar('m=praticas&a=fator_ver&pg_fator_critico_id='.$msg_fator);
elseif ($msg_perspectiva) $Aplic->redirecionar('m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$msg_perspectiva);
elseif ($msg_canvas) $Aplic->redirecionar('m=praticas&a=canvas_pro_ver&tab=8&canvas_id='.$msg_canvas);
elseif ($msg_meta) $Aplic->redirecionar('m=praticas&a=meta_ver&pg_meta_id='.$msg_meta);
elseif ($msg_monitoramento) $Aplic->redirecionar('m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$msg_monitoramento);
elseif ($msg_operativo) $Aplic->redirecionar('m=operativo&a=operativo_ver&operativo_id='.$msg_operativo);
else $Aplic->redirecionar('m=email&a=lista_msg');


?>