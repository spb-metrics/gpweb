<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$status=getParam($_REQUEST, 'status', 0);
$senha=getParam($_REQUEST, 'senha', '');
$cripto=getParam($_REQUEST, 'cripto', '');
$msg_id=getParam($_REQUEST, 'msg_id', 0);
$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', 0);
$notifica_criador_anexo=getParam($_REQUEST, 'notifica_criador_anexo', 0);
$notifica_destinatarios_anexo=getParam($_REQUEST, 'notifica_destinatarios_anexo', 0);

grava_anexo(getParam($_REQUEST, 'doc_nr', ''), getParam($_REQUEST, 'doc_tipo', ''), 'doc', getParam($_REQUEST, 'nome_fantasia', ''));


$data = date('Y-m-d H:i:s');
$sql = new BDConsulta;
$i=0;
$destinatarios=array();
if ($notifica_criador_anexo || $notifica_destinatarios_anexo){
	$sql->adTabela('msg');
	$sql->adCampo('de_id, referencia');
	$sql->adOnde('msg_id = '.$msg_id);
	$msg_original = $sql->Linha();
	$sql->Limpar();
	//enviar notifica��o para o criador da mensagem, caso n�o seja eu mesmo
	if ($notifica_criador_anexo && $msg_original['de_id'] != $Aplic->usuario_id) $destinatarios[$i++]=$msg_original['de_id'];
	}
//enviar notifica��o para os demais destinat�rios da mensagem	
if ($notifica_destinatarios_anexo){
	$sql->adTabela('msg_usuario');
	$sql->adCampo('para_id');
	$sql->adOnde('msg_id = '.$msg_id);
	$sql->adOnde('para_id != '.$Aplic->usuario_id);
	$sql_resultado = $sql->Lista();
	$sql->Limpar();
	foreach ($sql_resultado as $linha) $destinatarios[]=$linha['para_id'];
	}

if ($notifica_criador_anexo || $notifica_destinatarios_anexo){
	$referencia='Aviso de inclus�o de anexo na Msg Nr '.$msg_id.' ('.$msg_original['referencia'].')';
	$texto=ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' <a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a='.$Aplic->usuario_prefs['modelo_msg'].'&msg_id='.$msg_id.'\');">Nr '.$msg_id.' ('.$msg_original['referencia'].')</a> teve inclus�o de novo anexo pelo '.($Aplic->usuario_prefs['nomefuncao'] ? $Aplic->usuario_nome.($Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $Aplic->usuario_funcao : '') :	$Aplic->usuario_funcao.($Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $Aplic->usuario_nome : '')).' em '.retorna_data($data).'.';
	
	$sql->adTabela('msg');
	$sql->adInserir('referencia', $referencia);
	$sql->adInserir('de_id', $Aplic->usuario_id);
	$sql->adInserir('texto', $texto);
	$sql->adInserir('data_envio', $data);
	$sql->adInserir('nome_de', $Aplic->usuario_funcao);
	$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
	if (!$sql->exec()) die('N�o foi poss�vel inserir os dados na tabela de msgs!'.$bd->stderr(true));
	$nova_msg_id=$bd->Insert_ID('msg','msg_id');
	$sql->limpar();

	foreach($destinatarios as $chave => $valor){ 
		$sql->adTabela('msg_usuario');
		$sql->adInserir('de_id', $Aplic->usuario_id);
		$sql->adInserir('para_id', $valor);
		$sql->adInserir('msg_id', $nova_msg_id);
		$sql->adInserir('datahora', $data);
		$sql->adInserir('nome_de', $Aplic->usuario_funcao);
		$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
		$sql->adInserir('nome_para', nome_usuario($valor));
		$sql->adInserir('funcao_para', funcao_usuario($valor));
		if (!$sql->exec()) die('N�o foi poss�vel inserir os dados na tabela msg_usuario!'.$bd->stderr(true));
		$sql->limpar();
		}
	}
echo "<script language=Javascript>alert ('Inclus�o de anexo com sucesso!')</script>";


echo '<form method="POST" name="env" id="env">';
echo '<input type=hidden name="m" value="email">';
echo '<input type=hidden name="a" value="'.$Aplic->usuario_prefs['modelo_msg'].'">';
echo '<input type=hidden id="status" name="status" value="'.$status.'">';
echo '<input type=hidden id="msg_usuario_id" name="msg_usuario_id" value="'.$msg_usuario_id.'">';
echo '<input type=hidden id="msg_id" name="msg_id" value="'.$msg_id.'">';
echo '<input type=hidden id="senha" name="senha" value="'.$senha.'">';
echo '<input type=hidden id="msg_id_cripto" name="msg_id_cripto" value="'.$msg_id.'">';
echo '<input type=hidden id="cripto" name="cripto" value="'.$cripto.'">';
echo '</form>';
echo '<script language=Javascript>env.submit();</script>';
?>


