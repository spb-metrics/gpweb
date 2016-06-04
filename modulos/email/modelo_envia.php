<?php  
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (isset($_REQUEST['modeloID'])) $modeloID=getParam($_REQUEST, 'modeloID', null); 
else if (isset($_REQUEST['modelo_id'])) $modeloID[]=getParam($_REQUEST, 'modelo_id', null);
else  $modeloID=array();
$msg_id=reset($modeloID); 

$status=getParam($_REQUEST, 'status', 0);
$enviar_email=getParam($_REQUEST, 'enviar_email', 0);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());
$outros_emails=getParam($_REQUEST, 'outros_emails','');

$tipo=getParam($_REQUEST, 'tipo', 0);
$sql = new BDConsulta;

if ($enviar_email){
	if (false && function_exists("curl_init")) {
		$multi = curl_multi_init(); 
		$sessao_Curl=array();
		} 
	$qnt=0;
	foreach ((array)$modeloID as $chave => $valor){
		$sql->adTabela('contatos');
		$sql->adCampo('contato_email, contato_email2, usuario_id');
		$sql->esqUnir('usuarios', 'usuarios', 'contato_id = usuario_contato');
		$sql->adOnde('usuario_id IN ('.implode(',',(array)$ListaPARA).')');
		$sql_resultados = $sql->Lista();
		$sql->limpar();
		$destinatarios=array();
		foreach ($sql_resultados as $rs){
			if ($rs['contato_email'] || $rs['contato_email2']) $destinatarios[]=$rs['usuario_id'];
			}
		if (false && function_exists("curl_init")) {
			$qnt++; 
			$sessao_Curl[$qnt] = curl_init(BASE_URL.'/modulos/publico/email_externo.php');
			curl_setopt ($sessao_Curl[$qnt], CURLOPT_POST, 1);
			curl_setopt ($sessao_Curl[$qnt], CURLOPT_POSTFIELDS, 'destinatarios='.implode(',',$destinatarios).'&de_id='.$Aplic->usuario_id.'&msg_id='.$valor);
		 	curl_setopt ($sessao_Curl[$qnt], CURLOPT_FOLLOWLOCATION, 1);
			curl_multi_add_handle($multi, $sessao_Curl[$qnt]);  
			}
		else {
			$sql = new BDConsulta;
			$sql->adTabela('contatos');
			$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_email, contato_email2, usuario_id');
			$sql->esqUnir('usuarios', 'usuarios', 'contato_id = usuario_contato');
			$sql->adOnde('usuario_id IN ('.implode(',',(array)$destinatarios).')');
			$sql_resultados = $sql->Lista();
			$sql->limpar();
			$email_destinatarios=array();
			foreach ($sql_resultados as $rs){
				if ($rs['contato_email'])$email_destinatarios[]=$rs['contato_email'];
				if ($rs['contato_email2'])$email_destinatarios[]=$rs['contato_email2'];
				}
			$sql->adTabela('msg');
			$sql->adCampo('referencia');
			$sql->adOnde('msg_id = '.(int)$msg_id);
			$titulo = $sql->Resultado();
			$sql->limpar();
			//nao faz nenhum sentido - copiei e colei mas nao fiz 
			$texto=texto_msg_email($msg_id);
			msg_email_externo ($email_destinatarios, $titulo, $texto);
			}	
		}
	if (false && function_exists("curl_init")) {
		do{  
			curl_multi_exec($multi, $ativo);  
			}
		while($ativo > 0);  	
		}
	echo '<form method="POST" id="enviar" name="enviar">';
	echo '<input type=hidden name="m" value="email">';
	echo '<input type=hidden name="a" value="modelo_pesquisar">';
	echo '<input type=hidden name="status" value="0">';
	echo '</form>';
	echo '<script language=Javascript>alert ("Enviado para e-mail externo."); enviar.submit();</script>';
	}
echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="m" value="email">';
echo '<input type=hidden name="a" value="modelo_envia_email">';
echo '<input type=hidden id="destino" name="destino" value="envia_email">';
echo '<input type=hidden id="tipo" name="tipo" value="'.$tipo.'">';
echo '<input type=hidden id="status" name="status" value="'.$status.'">';	
echo '<input type=hidden name=enviar_email id="enviar_email" value="1">';
foreach ($modeloID as $chave => $valor) echo '<input type=hidden name=modeloID[] id="modeloID" value="'.$valor.'">';
foreach ($ListaPARA as $chave => $valor) echo '<input type=hidden name=ListaPARA[] id="ListaPARA" value="'.$valor.'">';
foreach ($ListaPARAoculto as $chave => $valor) echo '<input type=hidden name=ListaPARAoculto[] id="ListaPARAoculto" value="'.$valor.'">';
foreach ($ListaPARAaviso as $chave => $valor) echo '<input type=hidden name=ListaPARAaviso[] id="ListaPARAaviso" value="'.$valor.'">';
foreach ($ListaPARAexterno as $chave => $valor) echo '<input type=hidden name=ListaPARAexterno[] id="ListaPARAexterno" value="'.$valor.'">';
echo '<input type=hidden name="outros_emails" id="outros_emails" value="'.$outros_emails.'">';

echo estiloTopoCaixa(770);
echo '<table align="center" class="std" cellspacing=0 width="770" cellpadding=0>';
echo '<tr width="100%"><td colspan="2" align="center"><h1>Encaminhar por E-mail</h1></td></tr>';
echo '<tr><td align="right" width="60" size="2"><b>De:&nbsp;</b></td><td>'.($Aplic->usuario_prefs['nomefuncao'] ? $Aplic->usuario_nome.($Aplic->usuario_funcao && $Aplic->usuario_nome && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ?  $Aplic->usuario_funcao : '') : ($Aplic->usuario_prefs['exibenomefuncao'] ?  $Aplic->usuario_funcao : '').($Aplic->usuario_nome  && $Aplic->usuario_funcao && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').$Aplic->usuario_nome).'</td></tr>';
echo '<tr><td align="right" valign="top"><b>Para:&nbsp;</b></td><td>';
$aviso=array();
$qnt=0;
$sql->adTabela('contatos');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_email, contato_email2, usuario_id');
$sql->esqUnir('usuarios', 'usuarios', 'contato_id = usuario_contato');
$sql->adOnde('usuario_id IN ('.implode(',',(array)$ListaPARA).')');
$sql_resultados = $sql->Lista();
$sql->limpar();
foreach ($sql_resultados as $rs){			
		if (!($rs['contato_email'] || $rs['contato_email2'])) $aviso[]=$rs['nome_usuario'];	
		else {
			echo ($Aplic->usuario_prefs['nomefuncao'] ? $rs['nome_usuario'].($rs['contato_funcao'] && $rs['nome_usuario'] && $Aplic->usuario_prefs['exibenomefuncao']? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '') : ($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '').($rs['nome_usuario'] && $rs['contato_funcao'] && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').$rs['nome_usuario']).'['.$rs['contato_email'].']<br>';	
			$qnt++;
			}
		};
echo '<br></td></tr>';
if ($qnt) echo '<tr><td colspan=2><table><tr><td align="left" width="335">'.dica("Voltar","Clique neste bot�o para voltar � sele��o de destinat�rios.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.a.value=\'seleciona_usuarios\'; env.submit();"><span><b>voltar</b></span></a></td><td align="right">'.dica("Encaminhar",'Clique neste bot�o para enviar para os destinat�rios '.($qnt>1 ? $config['genero_mensagem'].'s '.$config['mensagens'] : $config['genero_mensagem'].' '.$config['mensagem']).'.').'<a class="botao" href="javascript:document.getElementById(\'env\').submit();" ><span><b>Encaminhar</b></span></a></td></tr></table></td></tr>';
else echo '<tr><td colspan=2><table><tr><td width="350px">&nbsp;</td><td align="right">'.dica("Voltar","Clique neste bot�o para voltar � sele��o de destinat�rios.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.a.value=\'seleciona_usuarios\'; env.submit();"><span><b>voltar</b></span></a></td></tr></table></td></tr>';
if (count($aviso)) echo '<tr><td align="center" colspan=2><br><b>'.(count($aviso) > 1 ? ucfirst($config['usuarios']) : ucfirst($config['usuario'])).' sem e-mail externo cadastrado</b></td></tr><td colspan=2 align="left"><table align="center" width="300" cellpadding=0 cellspacing=0 class="tbl1"><tr><td align="center">'.implode('<br>', $aviso).'</td></tr></table><br></td></tr>';
if (count($modeloID)) echo '<tr><td colspan=2 align="center">Encaminhamento por e-mail para '.relacao_mensagens().'</td></tr><tr><td colspan=2 align="center">&nbsp</td></tr>';
echo '</table>';
echo estiloFundoCaixa(770);	
echo '</form></body></html>';
?>