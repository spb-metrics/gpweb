<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


$data = date('Y-m-d H:i:s');
$tipo=getParam($_REQUEST, 'tipo', 0);
$arquivar=getParam($_REQUEST, 'arquivar', 0);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());
$outros_emails=getParam($_REQUEST, 'outros_emails','');

if (isset($_REQUEST['vetor_modelo_msg_usuario'])) $vetor_modelo_msg_usuario = getParam($_REQUEST, 'vetor_modelo_msg_usuario', null); 
else if (isset($_REQUEST['modelo_usuario_id']) && $_REQUEST['modelo_usuario_id']) $vetor_modelo_msg_usuario[] = getParam($_REQUEST, 'modelo_usuario_id', null);

if (!isset($vetor_modelo_msg_usuario)){
	if (isset($_REQUEST['modeloID']) && $_REQUEST['modeloID']) $modeloID = getParam($_REQUEST, 'modeloID', null); 
	else if (isset($_REQUEST['modelo_id']) && $_REQUEST['modelo_id']) $modeloID[] = getParam($_REQUEST, 'modelo_id', null);
	else if (!isset($modeloID)) $modeloID = array();
	}

$so_modelo=isset($modeloID);


$status_original=getParam($_REQUEST, 'status_original', 0);
$tipo_cripto=getParam($_REQUEST, 'tipo_cripto', 0);
$senha=getParam($_REQUEST, 'senha', '');
$msg_id_cripto=getParam($_REQUEST, 'msg_id_cripto', 0);

$prazo_responder=getParam($_REQUEST, 'prazo_responder', 0);
$data_limite=getParam($_REQUEST, 'data_limite', '');
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


foreach(($so_modelo ? $modeloID : $vetor_modelo_msg_usuario) as $chave => $valormsg_id){ 
	
	if (!$so_modelo){
		$sql->adTabela('modelo_usuario');
		$sql->adCampo('modelo_id');
		$sql->adOnde('modelo_usuario_id = '.$valormsg_id);
		$modelo_id=$sql->Resultado();
		$sql->limpar();
		$modelo_usuario_id=$modelo_id;
		}
	else $modelo_id=$valormsg_id;	
	
	
	
	foreach($ListaPARA as $chave => $valor){ 
		$sql->adTabela('modelo_usuario');
		$sql->adInserir('de_id', $Aplic->usuario_id);
		$sql->adInserir('para_id', $valor);
		$sql->adInserir('modelo_id', $modelo_id);
		$sql->adInserir('tipo', $tipo);
		$sql->adInserir('datahora', $data);
		$sql->adInserir('aviso_leitura', (in_array($valor, $ListaPARAaviso) ? '1' : '0'));
		$sql->adInserir('copia_oculta', (in_array($valor, $ListaPARAoculto) ? '1' : '0' ));
		$sql->adInserir('nome_de', $Aplic->usuario_nome);
		$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
		$sql->adInserir('nome_para', nome_usuario($valor));
		$sql->adInserir('funcao_para', funcao_usuario($valor));
		if(isset($anotacao_id)) $sql->adInserir('modelo_anotacao_id', $anotacao_id);
		if ($prazo_responder && $data_limite) $sql->adInserir('data_limite', $data_limite->format(FMT_TIMESTAMP_MYSQL));
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
		$modelo_usuario_id=$bd->Insert_ID('modelo_usuario','modelo_usuario_id');
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
			$sql->adInserir('nome_de', nome_usuario($valor));
			$sql->adInserir('funcao_de', funcao_usuario($valor));
			$sql->adInserir('nome_para', nome_usuario($encaminha));
			$sql->adInserir('funcao_para', funcao_usuario($encaminha));
			if (isset($anotacao_id)) $sql->adInserir('anotacao_id', $anotacao_id);
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
			$modelo_usuario_id=$bd->Insert_ID('modelo_usuario','modelo_usuario_id');
			$sql->Limpar();
			}
	  $encaminha = '';
		}	
	
	if ($arquivar == 1 || $arquivar == 2){	
		if ($arquivar == 1)$status = 4;
		if ($arquivar == 2)$status = 3;
		
		$sql->adTabela('modelo_usuario');
		$sql->adAtualizar('status', $status);
		$sql->adOnde(($status_original==5 ? 'de_id=' : 'para_id=').$Aplic->usuario_id);
		$sql->adOnde('modelo_id ='.$modelo_id);
		if (!$sql->exec()) die('Não foi possivel alterar os valores de msg_usuario'.$bd->stderr(true));
		$sql->limpar();
		}
	

	//email externo
	$email_destinatarios=array();
	if ($outros_emails) $email_destinatarios=explode(';',str_replace(' ', '', $outros_emails)); 
	
	foreach ($email_destinatarios as $email_extra){
		$sql->adTabela('modelo_usuario_ext');
		$sql->adInserir('de_id', $Aplic->usuario_id);
		$sql->adInserir('para', $email_extra);
		$sql->adInserir('modelo_id', $modelo_id);
		$sql->adInserir('datahora', $data);
		$sql->adInserir('tipo', 3);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario_ext!');
		$modelo_usuario_id=$bd->Insert_ID('modelo_usuario_ext','modelo_usuario_ext_id');
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
		
		$sql->adTabela('modelos');
		$sql->esqUnir('modelo_usuario', 'modelo_usuario','modelo_usuario.modelo_id=modelos.modelo_id');
		$sql->adCampo('modelo_assunto');
		$modelo_assunto = $sql->Resultado();
		$sql->limpar();
		$texto='Em anexo documento criado no '.$config['gpweb'].'.<br><br><a href="'.$config['dominio_site'].'/index.php?m=email&a=modelo_editar&modelo_id='.$valor.'">Clique neste link para acessar o documento</a>';
		msg_email_externo($email_destinatarios, $modelo_assunto, $texto, '', $modelo_id);
		}
	}

if ($tipo==1) $Aplic->setMsg('Despacho enviado com sucesso', UI_MSG_OK);
else if ($tipo==3) $Aplic->setMsg('Encaminhamento enviado com sucesso', UI_MSG_OK);
else $Aplic->setMsg('Documento enviado com sucesso', UI_MSG_OK);



if ($arquivar == 1)$status = 4;
if ($arquivar == 2)$status = 3;
$Aplic->redirecionar('m=email&a=modelo_pesquisar'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status : '&status=1'));
?>
