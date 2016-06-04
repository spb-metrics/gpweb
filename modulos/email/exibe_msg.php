<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
if (isset($_REQUEST['vetor_msg_usuario'])) $vetor_msg_usuario = getParam($_REQUEST, 'vetor_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id'])) $vetor_msg_usuario[] = getParam($_REQUEST, 'msg_usuario_id', null);
else  $vetor_msg_usuario = array();
$msg_usuario_id=reset($vetor_msg_usuario); 
$status=getParam($_REQUEST, 'status', 0);
$senha=getParam($_REQUEST, 'senha', '');
$usuario_id=getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$outro_usuario=getParam($_REQUEST, 'outro_usuario', 0);
$pasta=getParam($_REQUEST, 'pasta', null);
$tipos_status=array('' => 'indefinido') + getSisValor('status');
$precedencia=getSisValor('precedencia');
$class_sigilosa=getSisValor('class_sigilosa');
$sql = new BDConsulta;


$coletivo=($Aplic->usuario_lista_grupo && $Aplic->usuario_lista_grupo!=$Aplic->usuario_id);


//se tiver msg_id, de referencia achar um usuario_id
if ($msg_id=getParam($_REQUEST, 'msg_id', 0)){
	$sql->adTabela('msg_usuario');
	$sql->adCampo('msg_usuario_id');
	$sql->adOnde('msg_usuario.msg_id= '.$msg_id);
	$sql->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$Aplic->usuario_id).' OR msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$Aplic->usuario_id));
	$achado=$sql->resultado();
	$sql->limpar();
	if (!$achado){
		//se nao achou tenta qualquer destinatário com o Id da mensagem
		$sql->adTabela('msg_usuario');
		$sql->adCampo('msg_usuario_id');
		$sql->adOnde('msg_usuario.msg_id= '.$msg_id);
		$achado=$sql->resultado();
		$sql->limpar();
		}
	if ($achado)$msg_usuario_id=$achado;	
	}


//cm impede ver mensagens de outro usuario se não for CM ou administrador
if (!$Aplic->usuario_admin && $Aplic->usuario_acesso_email!=1) $usuario_id = $Aplic->usuario_id;

$sql->adTabela('msg');
$sql->adUnir('msg_usuario','msg_usuario','msg_usuario.msg_id = msg.msg_id');
$sql->adCampo('msg_usuario_id');
$sql->adOnde('msg_usuario.msg_usuario_id = '.$msg_usuario_id);
$sql->adOnde('msg.class_sigilosa <= '.$Aplic->usuario_acesso_email);
$permitido = $sql->Resultado();
$sql->limpar();

if (!$permitido) {
	echo '<script language=Javascript>alert("Não tem permissão de acesso a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'"); url_passar(0, \'m=email&a=lista_msg&status=1\');</script>';
	exit();
	}			

//dados básicos da mensagem	
$sql->adTabela('msg_usuario');
$sql->adUnir('msg','msg','msg.msg_id=msg_usuario.msg_id');
$sql->esqUnir('msg_cripto', 'msg_cripto', 'msg_usuario.msg_cripto_id=msg_cripto.msg_cripto_id');
$sql->esqUnir('chaves_publicas', 'chaves_publicas', 'msg.chave_publica = chave_publica_id');
$sql->adCampo('msg_usuario.msg_cripto_id, chave_publica_chave AS publica');
$sql->adCampo('tarefa_data, tarefa_progresso, msg_usuario.anotacao_id, msg_usuario.tipo, msg_usuario.aviso_leitura, msg_usuario.datahora_leitura, msg.data_envio, msg.msg_id, data_retorno, data_limite, resposta_despacho, msg.cm, assinatura, msg_usuario_id, msg.precedencia, msg.class_sigilosa, msg.referencia, msg.texto, msg.cripto, datahora, msg_usuario.de_id, msg_usuario.msg_cripto_id');
$sql->adOnde('msg_usuario.msg_usuario_id = '.$msg_usuario_id);
$rs = $sql->Linha();
$sql->limpar();
$msg_id=$rs['msg_id'];

if ($rs['cripto']) {
	$msg_id_cripto=$msg_id;
	$msg_cripto_id=$rs['msg_cripto_id'];
	}
else {
	$msg_id_cripto=0;
	$msg_cripto_id=0;
	}
if ($rs['cripto']==1){
	$sql->adTabela('msg_cripto');
	$sql->adCampo('texto, chave_envelope');
	$sql->adOnde('msg_cripto_msg = '.$msg_id);
	$sql->adOnde('msg_cripto_para = '.$usuario_id);
	$linha_cripto = $sql->Linha();
	$sql->limpar();
	openssl_open(base64_decode($linha_cripto['texto']), $em_claro, base64_decode($linha_cripto['chave_envelope']), $Aplic->chave_privada);
	$rs['texto']=$em_claro;
	}
elseif ($rs['cripto']==2){
	$sql->adTabela('msg_cripto');
	$sql->adCampo('texto');
	$sql->adOnde('msg_cripto_id = '.$rs['msg_cripto_id']);
	$linha_cripto = $sql->Resultado();
	$sql->limpar();
	require_once BASE_DIR.'/classes/cifra.class.php';
	$cifra = new cifra; 
	$cifra->set_key($senha);
	$rs['texto']=$cifra->decriptar($linha_cripto);
	}

$assinado='';

//verificar dados originais da 1a mensagem
$sql->adTabela('msg');
$sql->esqUnir('chaves_publicas', 'chaves_publicas', 'msg.chave_publica = chave_publica_id');
$sql->adCampo('precedencia, class_sigilosa, referencia, de_id, texto, cripto, cm, data_envio, assinatura, chave_publica_chave');
$sql->adOnde('msg_id = '.$msg_id);
$original = $sql->Linha();
$sql->limpar();

if (function_exists('openssl_sign') && $rs['assinatura']){	
	$identificador=$original['precedencia'].$original['class_sigilosa'].$original['referencia'].$original['de_id'].$rs['texto'].$original['cripto'].$original['cm'].$original['data_envio'];
	$ok = openssl_verify($identificador, base64_decode($original['assinatura']), $original['chave_publica_chave'], OPENSSL_ALGO_SHA1);
	if (!$ok) $assinado='&nbsp;'.dica(nome_funcao('','','','',$original['de_id']),'A assinatura digital d'.$config['genero_mensagem'].' '.$config['mensagem'].' não confere! '.ucfirst($config['mensagem']).' possívelmente adulterada.').'<img src="'.acharImagem('icones/assinatura_erro.gif').'" style="vertical-align:top" width="15" height="13" />'.dicaF();
	else $assinado='&nbsp;'.dica(nome_funcao('','','','',$original['de_id']),'A assinatura digital n'.$config['genero_mensagem'].' '.$config['mensagem'].' confere.').'<img src="'.acharImagem('icones/assinatura.gif').'" style="vertical-align:top" width="15" height="13" />'.dicaF();
	}

//marcar como lida
if ($status ==1 && !$rs['datahora_leitura']) {
	$data = date('Y-m-d H:i:s');
	$sql->adTabela('msg_usuario');
	$sql->adAtualizar('datahora_leitura', $data);
	$sql->adAtualizar('status', 1);
	$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
	$sql->adOnde('para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$Aplic->usuario_id));
	$retorno=$sql->exec();
	$sql->limpar();
	if ($rs['aviso_leitura']==1 && $Aplic->usuario_id==$usuario_id) aviso_leitura($rs['de_id'], $msg_usuario_id, $data);
	}

$sql->adTabela('preferencia_cor');
$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos, cor_referencia, cor_referenciado');
$sql->adOnde('usuario_id ='.(int)$Aplic->usuario_id);
$cor=$sql->Linha();
$sql->limpar();

if (!isset($cor['cor_msg'])) {
	$sql->adTabela('preferencia_cor');
	$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos, cor_referencia, cor_referenciado');
	$sql->adOnde('usuario_id = 0 OR usuario_id IS NULL');
	$cor=$sql->Linha();
	$sql->limpar();
 	}
 	
 
echo '<form method="POST" id="imprimir" name="imprimir" target="_blank">';
echo '<input type=hidden id="dialogo" name="dialogo" value="1">';
echo '<input type=hidden name="m" id="m" value="email">';  		 
echo '<input type=hidden name="a" id="a" value="exibe_msg_imp">';
echo '<input type=hidden name="msg_id" id="msg_id" value="'.$msg_id.'">';
echo '<input type=hidden name="msg_usuario_id" id="msg_usuario_id" value="'.$msg_usuario_id.'">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="'.$usuario_id.'">';  
echo '<input type=hidden name="msg_id_cripto" id="msg_id_cripto" value="'.$msg_id_cripto.'">';
echo '<input type=hidden name="msg_cripto_id" id="msg_cripto_id" value="'.$msg_cripto_id.'">';
echo '<input type=hidden name="cripto" id="cripto" value="'.$rs['cripto'].'">';
echo '<input type=hidden name="senha_antiga" id="senha_antiga" value="'.$senha.'">';
echo '<input type=hidden name="senha" id="senha" value="'.$senha.'">';
echo '</form>';	
 	
  
echo '<form method="POST" id="env" name="env">';
foreach ($vetor_msg_usuario as $chave => $valor) echo '<input type=hidden name=vetor_msg_usuario[] id="vetor_msg_usuario" value="'.$valor.'">'; 
echo '<input type=hidden name="a" id="a" value="">';  		
echo '<input type=hidden name="m" id="email" value="email">';
echo '<input type=hidden name="status" id="status" value='.$status.'>';
echo '<input type=hidden name="arquivar" id="arquivar" value="">'; 		
echo '<input type=hidden name="pasta" id="pasta" value="">'; 		
echo '<input type=hidden name="mover" id="mover" value="">'; 		
echo '<input type=hidden name="destino" id="destino" value="">'; 		
echo '<input type=hidden name="tipo" id="tipo" value="">'; 		
echo '<input type=hidden name="usuario_id" id="usuario_id" value="'.$usuario_id.'">'; 	
echo '<input type=hidden name="anexo" id="anexo"  value="">';
echo '<input type=hidden name="outro_usuario" id="outro_usuario" value="">'; 
echo '<input type=hidden name="sem_cabecalho" id="sem_cabecalho" value="">'; 
echo '<input type=hidden name="msg_id" id="msg_id" value="'.$msg_id.'">';
echo '<input type=hidden name="msg_id_cripto" id="msg_id_cripto" value="'.$msg_id_cripto.'">';
echo '<input type=hidden name="msg_cripto_id" id="msg_cripto_id" value="'.$msg_cripto_id.'">';
echo '<input type=hidden name="msg_usuario_id" id="msg_usuario_id" value="'.$msg_usuario_id.'">';
echo '<input type=hidden name="cripto" id="cripto" value="'.$rs['cripto'].'">';
echo '<input type=hidden name="senha_antiga" id="senha_antiga" value="'.$senha.'">';
echo '<input type=hidden name="senha" id="senha" value="'.$senha.'">';
echo '<input type=hidden name="anexar_documento" id="anexar_documento" value="">';
echo '<input type=hidden name="retornar" id="retornar" value="'.$a.'">'; 
echo '<input type=hidden name="mudar_status" id="mudar_status" value="">';
echo '<input type=hidden name="status_original" id="status_original" value="'.$status.'">';


echo '<table rules="ALL" border="1" align="center" cellspacing=0 cellpadding=0 width=770>'; 
echo '<tr><td colspan=2 width="100%" style="background-color: #e6e6e6">';
require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
$km = new CoolMenu("km");
$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
$km->styleFolder="default";

if ($rs['data_limite'] && !$rs['resposta_despacho'])	$km->Add("root","resposta_despacho",dica('Inserir uma Resposta à um Despacho', 'Este doumento lhe foi enviado através de um despacho com solicitação de resposta até '.retorna_data($rs['data_limite'], false).'.<br><br>Clique neste botão para abrir uma janela onde poderá escrever uma resposta a este despacho.').'Responder despacho'.dicaF(), "javascript: void(0);' onclick='resposta_despacho();");	
elseif ($rs['tipo']==1 && !$rs['resposta_despacho'])	$km->Add("root","resposta_despacho",dica('Inserir uma Resposta à um Despacho', 'Este doumento lhe foi enviado através de um despacho sem prazo para responder.').'Responder despacho'.dicaF(), "javascript: void(0);' onclick='resposta_despacho();");	

$km->Add("root","root_arquivar",dica('Arquivar','Clique nesta opção para mover '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' para a caixa d'.$config['genero_mensagem'].'s '.$config['mensagens'].' arquivad'.$config['genero_mensagem'].'s.').'Arquivar'.dicaF(), "javascript: void(0);' onclick='arquiva();");
$km->Add("root","root_responder",dica('Responder','Clique nesta opção para enviar uma resposta para o remetente d'.$config['genero_mensagem'].' '.$config['mensagem'].'.<BR><BR>Ao contrário do Despachar, em que se seleciona quantos destinatários desejar, ao responder apenas o remetente d'.$config['genero_mensagem'].' '.$config['mensagem'].' é automaticamente selecionado.').'Responder'.dicaF(), "javascript: void(0);' onclick='responder();");
$km->Add("root","root_encaminhar",dica('Encaminhar','Clique nesta opção para enviar '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' para os destinatários selecionados.<BR><BR>Ao contrário de Despachar, Responder e Anotar, nenhum texto será registrado.').'Encaminhar'.dicaF(), "javascript: void(0);' onclick='encaminhar();");
$km->Add("root","acao",dica('Ação','Selecione qual ação deseja execuar n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'Ação');	
$km->Add("acao","acao_despachar",dica('Despachar','Clique nesta opção para enviar um texto para os destinatários selecionados.<BR><BR>Ao contrário do Responder, em que o criador d'.$config['genero_mensagem'].' '.$config['mensagem'].' é automaticamente selecionado, no despacho cada destinatário deverá ser selecionado.').'Despachar'.dicaF(), "javascript: void(0);' onclick='despachar();");
$km->Add("acao","acao_anotar",dica('Anotar','Clique nesta opção para registrar um texto junto com '.$config['genero_mensagem'].' '.$config['mensagem'].'.<BR><BR>Anotar se diferencia das opções Responder e Anotar por não enviar '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' para nenhum destinatário.').'Anotar'.dicaF(), "javascript: void(0);' onclick='anotar();");
$km->Add("acao","acao_externo",dica('Anexar Arquivo Externo','Clique nesta opção para anexar um arquivo externo à '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'Anexar Arquivo Externo'.dicaF(), "javascript: void(0);' onclick='enviar_anexo();");
if ($config['doc_interno']) $km->Add("acao","acao_doc_int",dica('Anexar Documento Interno','Clique nesta opção para anexar um documento criado no '.$config['gpweb'].' à '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'Anexar Documento Interno'.dicaF(), "javascript: void(0);' onclick='enviar_documento();");

//todos os destinatários do mesmo envio
$sql->adTabela('msg_usuario');
$sql->adCampo('DISTINCT para_id');
$sql->adOnde('msg_id ='.(int)$msg_id);
if ($rs['de_id']) $sql->adOnde('de_id ='.(int)$rs['de_id']);
$sql->adOnde('datahora =\''.$rs['datahora'].'\'');
$todos_destinatarios = $sql->carregarColuna();
$sql->limpar();



if (!$outro_usuario && in_array($Aplic->usuario_id, $todos_destinatarios)){
	$km->Add("root","mover_msg",dica('Mover Para','Selecione para aonde deseja mover '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'Mover Para'.dicaF());
	$km->Add("mover_msg","entrada",dica('Entrada','Clique nesta opção para mover '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' para a caixa de entrada.').'Entrada'.dicaF(), "javascript: void(0);' onclick='entrada();");
	$km->Add("mover_msg","pendente",dica('Pendentes','Clique nesta opção para mover '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' para a caixa d'.$config['genero_mensagem'].'s '.$config['mensagens'].' pendentes.').'Pendentes'.dicaF(), "javascript: void(0);' onclick='pender();");
	$sql->adTabela('pasta');
	$sql->adCampo('pasta_id, nome');
	$sql->adOnde('usuario_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$Aplic->usuario_id));
	$pastas=$sql->Lista();
	$sql->limpar();
	if (count($pastas)){ 
		$km->Add("mover_msg","mover_pasta",dica('Para Pasta','Selecione em qual pasta deseja arquivar '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'Para Pasta'.dicaF());
		foreach ($pastas as $linha) $km->Add("mover_pasta","pasta_".$linha['pasta_id'],$linha['nome'], "javascript: void(0);' onclick='mover_pasta(".$linha['pasta_id'].");");
		}
	}

$km->Add("root","modelo_exibicao",dica('Modo de Exibição','Selecione outro modo de exibir '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'Exibição');	
$km->Add("modelo_exibicao","mod_impressao",dica('Impressão','Clique nesta opção para visualizar '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' no formato para impressão.').'Impressão'.dicaF(), "javascript: void(0);' onclick='imprimir.submit();");
$km->Add("modelo_exibicao","mod_webmail",dica('Webmail','Clique nesta opção para visualizar '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' no formato webmail.').'Webmail'.dicaF(), "javascript: void(0);' onclick='env.a.value=\"exibe_msg_mod_email\"; env.submit();");

$sql->adTabela('msg_usuario');
$sql->adCampo('MAX(msg_usuario_id)');
$sql->adOnde('msg_usuario_id < '.$msg_usuario_id);
if ($pasta && $status!=5) $sql->adOnde('pasta_id='.(int)$pasta);
if ($status!=5) $sql->adOnde('para_id ='.(int)$usuario_id);
else $sql->adOnde('de_id ='.(int)$usuario_id);
if ($status==1) $sql->adOnde('status<2');
elseif ($status==3) $sql->adOnde('status=3');
elseif ($status==4) $sql->adOnde('status=4');
$anterior=$sql->Resultado();
if ($anterior) $km->Add("root","abaixo",imagem('icones/seta_baixo.gif', ''.ucfirst($config['mensagem']).' Anterior', 'Clique nesta seta para ler '.$config['genero_mensagem'].' '.$config['mensagem'].' anterior a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').'.').'&nbsp;', "javascript: void(0);' onclick='url_passar(0, \"m=email&a=exibe_msg&status=".$status."&msg_usuario_id=".$anterior."\");");

$sql->adTabela('msg_usuario');
$sql->adCampo('MIN(msg_usuario_id)');
$sql->adOnde('msg_usuario_id > '.$msg_usuario_id);
if ($pasta && $status!=5) $sql->adOnde('pasta_id='.(int)$pasta);
if ($status!=5) $sql->adOnde('para_id ='.(int)$usuario_id);
else $sql->adOnde('de_id ='.(int)$usuario_id);
if ($status==1) $sql->adOnde('status<2');
elseif ($status==3) $sql->adOnde('status=3');
elseif ($status==4) $sql->adOnde('status=4');
$posterior=$sql->Resultado();
if ($posterior) $km->Add("root","acima",imagem('icones/seta_acima.gif', ''.ucfirst($config['mensagem']).' Posterior', 'Clique nesta seta para ler '.$config['genero_mensagem'].' '.$config['mensagem'].' posterior a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').'.').'&nbsp;', "javascript: void(0);' onclick='url_passar(0, \"m=email&a=exibe_msg&status=".$status."&msg_usuario_id=".$posterior."\");");


echo $km->Render();
echo '</td></tr>';
echo '</table>';
echo sombra_baixo();


echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';	
echo '<table align="center" width="770" cellspacing=0 cellpadding=0>';
echo '<tr><td colspan=60 align="center" style="font-size:10pt;  padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_msg'].'"><b>'.strtoupper($config['mensagem']).' '.$msg_id.'</b></td></tr>';

$sql->adTabela('msg_usuario');
$sql->adCampo('count(msg_usuario_id)');
$sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
$sql->adOnde('ignorar_para=0 OR ignorar_para IS NULL');
$sql->adOnde('msg_usuario.tarefa=1');
$sql->adOnde('msg_usuario_id = '.$rs['msg_usuario_id']);
$tipo_tarefa=$sql->Resultado();
$sql->limpar();
if ($tipo_tarefa){
	$agora=date('Y-m-d');
	$estilo ='font-size:10pt; padding-left: 5px; padding-right: 5px;';
	if($rs['tarefa_data']){
		if ($agora > $rs['tarefa_data'] && $rs['tarefa_progresso'] < 100 ) $estilo = 'style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color:#cc6666;color:#ffffff"';
		elseif ($agora < $rs['tarefa_data'] && $rs['tarefa_progresso'] == 0) $estilo = 'font-size:10pt; padding-left: 5px; padding-right: 5px;style="background-color:#ffeebb"';
		}
	$percentual =array(-1=>'abortada')+getSisValor('TarefaPorcentagem','','','sisvalor_id');
	echo '<tr style="background-color: #'.$cor['cor_msg'].'"><td colspan=30 align=center><table cellspacing=0 cellpadding=0><tr><td align="center" style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px;">'.dica('Percentagem Executada','Selecione a percentagem já executada d'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' do tipo atividade.').'Percentagem executada: '.dicaF().selecionaVetor($percentual, 'percentagem_'.$rs['msg_usuario_id'], 'size="1" class="texto" onchange="mudar_porcentagem('.$rs['msg_usuario_id'].');"' , (int)$rs['tarefa_progresso']).'</td><td style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px;">'.dica('Prazo', 'Prazo limite de execução d'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' do tipo atividade.').'Prazo:'.dicaF().'</td><td '.$estilo.'>'.retorna_data($rs['tarefa_data'],false ).'</td><tr></table></td><tr>';
	}


echo '<tr style="background-color: #'.$cor['cor_msg'].'">';
echo '<td width="20" align="center" style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px;'.($config['msg_precedencia'] ? '' : ' display:none;').'">'.dica('Precedência','A precedência diz respeito à urgencia na leitura d'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Precedência'.dicaF().'</td>';
echo '<td width="20" align="center" style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px;'.($config['msg_class_sigilosa'] ? '' : ' display:none;').'">'.dica('Classificação Sigilosa','A classificação sigilosa serve para restringir quais destinatários podem ler '.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Class&nbsp;Sigilosa'.dicaF().'</td>';
echo '<td align="center" colspan=20 style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px;" >'.dica('Assunto','O assunto serve para pesquisar '.$config['genero_mensagem'].'s '.$config['mensagem'].' e facilita na organização d'.$config['genero_mensagem'].'s mesm'.$config['genero_mensagem'].'s.').'Assunto'.dicaF().'</td></tr>';
echo '<tr style="background-color: #'.$cor['cor_fundo'].'">';
echo '<td align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; '.($config['msg_precedencia'] ? '' : 'display:none;').'">'.(isset($precedencia[$rs['precedencia']]) ? $precedencia[$rs['precedencia']] : 'sem precedência').'</td>';
echo '<td align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; '.($config['msg_class_sigilosa'] ? '' : 'display:none;').'">'.(isset($class_sigilosa[$rs['class_sigilosa']]) ? $class_sigilosa[$rs['class_sigilosa']] : 'sem precedência').'</td>';
echo '<td colspan=20 align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px;">'.$rs['referencia'].'</td></tr>';	
echo '<tr><td align="right" style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_msg'].'">De:</td>';
echo '<td colspan=40 style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].';">';

//todos os remetentes
$sql->adTabela('msg_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=msg_usuario.de_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
$sql->adOnde('datahora =\''.$rs['datahora'].'\'');
$sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, msg_usuario.msg_usuario_id, contatos.contato_funcao');
$remetentes = $sql->lista();
$sql->limpar();
$i=0;

if (isset($remetentes[0])) echo nome_funcao($remetentes[0]['nome_de'],$remetentes[0]['nome_usuario'], $remetentes[0]['funcao_de'], $remetentes[0]['contato_funcao']);

$sql->adTabela('anotacao');
$sql->adCampo('usuario_id, nome_de, funcao_de');
$sql->adOnde('msg_id = '.$msg_id);
$sql->adOnde('tipo = 3');
$rs_para=$sql->Linha();
$sql->limpar();
if (isset($rs_para['usuario_id'])) echo ' - (CM: enviada por '.nome_funcao($rs_para['nome_de'], '', $rs_para['funcao_de'], '', $rs_para['usuario_id']);

echo '</td></tr>';
echo '<tr><td align="right" style="font-size:10pt;font-weight:Bold;  padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_msg'].';" >Para:</td>';
echo '<td colspan=40 style="font-size:9pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].';">';


//todos os destinatários
$sql->adTabela('msg_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=msg_usuario.para_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('msg_usuario_id, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, msg_usuario.status, msg_usuario.datahora_leitura, msg_usuario.cm, msg_usuario.meio, usuarios.usuario_id, contato_funcao');
$sql->adCampo('para_id');
$sql->adOnde('msg_usuario.msg_id ='.(int)$msg_id);
$sql->adOnde('datahora =\''.$rs['datahora'].'\'');
$sql->adOnde('msg_usuario.para_id > 0');
$sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, msg_usuario.msg_usuario_id, contatos.contato_funcao');


$destinatarios = $sql->Lista();
$sql->limpar();

foreach($destinatarios as $chave => $destinatario){
	if ($destinatario['para_id']==$usuario_id) {
		$apoio=$destinatarios[0];
		$destinatarios[0]=$destinatarios[$chave];
		$destinatarios[$chave]=$apoio;
		}
	}
//todos os destinatários extras
$sql->adTabela('msg_usuario_ext');
$sql->adCampo('para');
$sql->adOnde('msg_id ='.(int)$msg_id);
$sql->adOnde('datahora =\''.$rs['datahora'].'\'');
$sql->adGrupo('para');
$destinatarios_extras = $sql->Lista();
$sql->limpar();
if (isset($destinatarios[0]) && $destinatarios[0]) echo formata_destinatario($destinatarios[0]);
elseif(isset($destinatarios_extras[0]) && $destinatarios_extras[0]) echo $destinatarios_extras[0]['para']; 
$qnt_destinatario=count($destinatarios)+count($destinatarios_extras);
if ($qnt_destinatario > 1) {		
		$lista='';
		for ($i = 1, $i_cmp = count($destinatarios); $i < $i_cmp; $i++) $lista.= formata_destinatario($destinatarios[$i]).'<br>';	
		for ($i = 1, $i_cmp = count($destinatarios_extras); $i < $i_cmp; $i++) $lista.= $destinatarios_extras[$i]['para'].'<br>';	
		echo dica('Outros Destinatários', 'Clique para visualizar os demais destinatários.').' <a href="javascript: void(0);" onclick="ver_destinatario();">(+'.($qnt_destinatario - 1).')</a>'.dicaF(). '<span style="display: none" id="destinatario"><br>'.$lista.'</span>';
		}

echo '</td></tr>';

echo '<tr><td colspan=60 align="center" style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_msg'].';">Texto d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']).'</td></tr>';

echo '<tr><td colspan=60 style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].';">'.$rs['texto'].'</td></tr>';


echo '<tr><td colspan=60 style="font-size:10pt; padding-left: 5px; padding-right: 5px ;background-color: #'.$cor['cor_msg'].';">';
echo dica('Criptografada','Indica se '.$config['genero_mensagem'].' '.$config['mensagem'].' foi criptografad'.$config['genero_mensagem'].' ao ser enviad'.$config['genero_mensagem'].'.').'<b>Crpt:</b>&nbsp;'.dicaF().($rs['cripto'] ? 'Sim' : 'Não').$assinado;
echo '&nbsp;&nbsp;&nbsp;<b>Data de Envio:</b>&nbsp;'.retorna_data($rs['datahora']).'</td>';
echo '</tr>';


if ($Aplic->profissional){
	$sql->adTabela('msg_gestao');
	$sql->adCampo('msg_gestao.*');
	$sql->adOnde('msg_gestao_msg ='.(int)$msg_id);
	$sql->adOrdem('msg_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
  if (count($lista)) {
	  if ($Aplic->profissional) require_once BASE_DIR.'/modulos/projetos/template_pro.class.php';
		$ata_ativo=$Aplic->modulo_ativo('atas');
		if ($ata_ativo) require_once BASE_DIR.'/modulos/atas/funcoes.php';
		$swot_ativo=$Aplic->modulo_ativo('swot');
		if ($swot_ativo) require_once BASE_DIR.'/modulos/swot/swot.class.php';
		$operativo_ativo=$Aplic->modulo_ativo('operativo');
		if ($operativo_ativo) require_once BASE_DIR.'/modulos/operativo/funcoes.php';
		$problema_ativo=$Aplic->modulo_ativo('problema');
		if ($problema_ativo) require_once BASE_DIR.'/modulos/problema/funcoes.php';
		$agrupamento_ativo=$Aplic->modulo_ativo('agrupamento');
		if($agrupamento_ativo) require_once BASE_DIR.'/modulos/agrupamento/funcoes.php';
		$patrocinador_ativo=$Aplic->modulo_ativo('patrocinadores');
		if($patrocinador_ativo) require_once BASE_DIR.'/modulos/patrocinadores/patrocinadores.class.php';
		echo '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px ;background-color: #'.$cor['cor_msg'].';"><b>Relacionamento:</b></td><td>';
		$qnt=0;
		foreach($lista as $gestao_data){
			if ($gestao_data['msg_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['msg_gestao_tarefa']);
			elseif ($gestao_data['msg_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['msg_gestao_projeto']);
			elseif ($gestao_data['msg_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['msg_gestao_pratica']);
			elseif ($gestao_data['msg_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['msg_gestao_acao']);
			elseif ($gestao_data['msg_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['msg_gestao_perspectiva']);
			elseif ($gestao_data['msg_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['msg_gestao_tema']);
			elseif ($gestao_data['msg_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['msg_gestao_objetivo']);
			elseif ($gestao_data['msg_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['msg_gestao_fator']);
			elseif ($gestao_data['msg_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['msg_gestao_estrategia']);
			elseif ($gestao_data['msg_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['msg_gestao_meta']);
			elseif ($gestao_data['msg_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['msg_gestao_canvas']);
			elseif ($gestao_data['msg_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['msg_gestao_risco']);
			elseif ($gestao_data['msg_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['msg_gestao_risco_resposta']);
			elseif ($gestao_data['msg_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['msg_gestao_indicador']);
			elseif ($gestao_data['msg_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['msg_gestao_calendario']);
			elseif ($gestao_data['msg_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['msg_gestao_monitoramento']);
			elseif ($gestao_data['msg_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['msg_gestao_ata']);
			elseif ($gestao_data['msg_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['msg_gestao_swot']);
			elseif ($gestao_data['msg_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['msg_gestao_operativo']);
			elseif ($gestao_data['msg_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['msg_gestao_instrumento']);
			elseif ($gestao_data['msg_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['msg_gestao_recurso']);
			elseif ($gestao_data['msg_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['msg_gestao_problema']);
			elseif ($gestao_data['msg_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['msg_gestao_demanda']);
			elseif ($gestao_data['msg_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['msg_gestao_programa']);
			elseif ($gestao_data['msg_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['msg_gestao_licao']);
			elseif ($gestao_data['msg_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['msg_gestao_evento']);
			elseif ($gestao_data['msg_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['msg_gestao_link']);
			elseif ($gestao_data['msg_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['msg_gestao_avaliacao']);
			elseif ($gestao_data['msg_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['msg_gestao_tgn']);
			elseif ($gestao_data['msg_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['msg_gestao_brainstorm']);
			elseif ($gestao_data['msg_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['msg_gestao_gut']);
			elseif ($gestao_data['msg_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['msg_gestao_causa_efeito']);
			elseif ($gestao_data['msg_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['msg_gestao_arquivo']);
			elseif ($gestao_data['msg_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['msg_gestao_forum']);
			elseif ($gestao_data['msg_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['msg_gestao_checklist']);
			elseif ($gestao_data['msg_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['msg_gestao_agenda']);
			elseif ($gestao_data['msg_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['msg_gestao_agrupamento']);
			elseif ($gestao_data['msg_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['msg_gestao_patrocinador']);
			elseif ($gestao_data['msg_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['msg_gestao_template']);
			elseif ($gestao_data['msg_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['msg_gestao_painel']);
			elseif ($gestao_data['msg_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['msg_gestao_painel_odometro']);
			elseif ($gestao_data['msg_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['msg_gestao_painel_composicao']);
			elseif ($gestao_data['msg_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['msg_gestao_tr']);	
			elseif ($gestao_data['msg_gestao_me']) echo ($qnt++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['msg_gestao_me']);
			}
		echo '</td></tr>';
		}
	}

echo '</table></td></tr>';
echo '</td></tr></table>';   
echo sombra_baixo(); 


//referencias
$sql->adTabela('referencia');
$sql->esqUnir('msg', 'msg', 'msg.msg_id=referencia.referencia_msg_pai');
$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id=referencia.referencia_doc_pai');
$sql->adCampo('referencia.*, msg.de_id, modelos.*, msg.referencia, msg.data_envio, nome_de, funcao_de');
$sql->adOnde('referencia_msg_filho = '.$msg_id);
$lista_referencia_pai = $sql->Lista();
$sql->limpar();
$referencia_pai='';
if ($lista_referencia_pai && count($lista_referencia_pai)) {
		$qnt_msg=0;
		$qnt_doc=0;
		$referencia_pai.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		if ($lista_referencia_pai[0]['referencia_msg_pai']) {
			$referencia_pai.= '<tr><td>'.dica('Ler '.ucfirst($config['mensagem']), 'Clique para ler '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'<a href="javascript: void(0);" onclick="ver_msg('.$lista_referencia_pai[0]['referencia_msg_pai'].');">Msg. '.$lista_referencia_pai[0]['referencia_msg_pai'].($lista_referencia_pai[0]['referencia']? ' - '.$lista_referencia_pai[0]['referencia'] : '').' - '.nome_funcao($lista_referencia_pai[0]['nome_de'], '', $lista_referencia_pai[0]['funcao_de'], '', $lista_referencia_pai[0]['de_id']).' - '.retorna_data($lista_referencia_pai[0]['data_envio'], false).'</a>'.dicaF();
			$qnt_msg++;
			}
		else {
			if ($lista_referencia_pai[0]['modelo_autoridade_assinou']) {
				$nome=nome_funcao($lista_referencia_pai[0]['modelo_assinatura_nome'], '', $lista_referencia_pai[0]['modelo_assinatura_funcao'], '', $lista_referencia_pai[0]['modelo_autoridade_assinou']);
				$data=retorna_data($lista_referencia_pai[0]['modelo_data_assinado'], false);
				}
			elseif ($lista_referencia_pai[0]['modelo_autoridade_aprovou']) {
				$nome=nome_funcao($lista_referencia_pai[0]['modelo_aprovou_nome'], '', $lista_referencia_pai[0]['modelo_aprovou_funcao'], '', $lista_referencia_pai[0]['modelo_autoridade_aprovou']);
				$data=retorna_data($lista_referencia_pai[0]['modelo_data_aprovado'], false);
				}
			else {
				$nome=nome_funcao($lista_referencia_pai[0]['modelo_criador_nome'], '', $lista_referencia_pai[0]['modelo_criador_funcao'], '', $lista_referencia_pai[0]['modelo_criador_original']);
				$data=retorna_data($lista_referencia_pai[0]['modelo_data'], false);
				}
			$referencia_pai.= '<tr><td>'.dica('Ler Documento', 'Clique para ler este documento').'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.$lista_referencia_pai[0]['referencia_doc_pai'].($lista_referencia_pai[0]['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">Doc. '.$lista_referencia_pai[0]['referencia_doc_pai'].($lista_referencia_pai[0]['modelo_assunto']? ' - '.$lista_referencia_pai[0]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'</a>'.dicaF();
			$qnt_doc++;
			}
		$qnt_lista_referencia_pai=count($lista_referencia_pai);
		if ($qnt_lista_referencia_pai > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_referencia_pai; $i < $i_cmp; $i++) {
					if ($lista_referencia_pai[$i]['referencia_msg_pai']) {
						$lista.= dica('Ler '.ucfirst($config['mensagem']), 'Clique para ler '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'<a href="javascript: void(0);" onclick="ver_msg('.$lista_referencia_pai[$i]['referencia_msg_pai'].');">Msg. '.$lista_referencia_pai[$i]['referencia_msg_pai'].($lista_referencia_pai[$i]['referencia']? ' - '.$lista_referencia_pai[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_pai[$i]['nome_de'], '', $lista_referencia_pai[$i]['funcao_de'], '', $lista_referencia_pai[$i]['de_id']).' - '.retorna_data($lista_referencia_pai[$i]['data_envio'], false).'</a>'.dicaF().'<br>';
						$qnt_msg++;
						}
					else {
						if ($lista_referencia_pai[$i]['modelo_autoridade_assinou']) {
							$nome=nome_funcao($lista_referencia_pai[$i]['modelo_assinatura_nome'], '', $lista_referencia_pai[$i]['modelo_assinatura_funcao'], '', $lista_referencia_pai[$i]['modelo_autoridade_assinou']);
							$data=retorna_data($lista_referencia_pai[$i]['modelo_data_assinado'], false);
							}
						elseif ($lista_referencia_pai[$i]['modelo_autoridade_aprovou']) {
							$nome=nome_funcao($lista_referencia_pai[$i]['modelo_aprovou_nome'], '', $lista_referencia_pai[$i]['modelo_aprovou_funcao'], '', $lista_referencia_pai[$i]['modelo_autoridade_aprovou']);
							$data=retorna_data($lista_referencia_pai[$i]['modelo_data_aprovado'], false);
							}
						else {
							$nome=nome_funcao($lista_referencia_pai[$i]['modelo_criador_nome'], '', $lista_referencia_pai[$i]['modelo_criador_funcao'], '', $lista_referencia_pai[$i]['modelo_criador_original']);
							$data=retorna_data($lista_referencia_pai[$i]['modelo_data'], false);
							}
						$lista.= dica('Ler Documento', 'Clique para ler este documento').'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">Doc. '.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_assunto']? ' - '.$lista_referencia_pai[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'</a>'.dicaF().'<br>';
						$qnt_doc++;
						}
					}
				$referencia_pai.= dica('Outras Referencias', 'Clique para visualizar as demais referencias.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_referencia_pai\');">(+'.($qnt_lista_referencia_pai - 1).')</a>'.dicaF(). '<span style="display:none" id="lista_referencia_pai"><br>'.$lista.'</span>';
				}
		$referencia_pai.= '</td></tr></table>';
		} 
if ($referencia_pai) {
	echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
	echo '<table align="center" cellspacing=0 width="770" cellpadding=0>';
	echo '<tr><td align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px;background-color: #'.$cor['cor_referencia'].';">'.dica(($qnt_lista_referencia_pai > 1 ? 'Referencias' : 'Referencia'), ($qnt_doc>1 ? 'documentos' : '').($qnt_doc==1 ? 'documento' : '').($qnt_doc && $qnt_msg? ' e ' : '').($qnt_msg>1 ? 'mensagens' : '').($qnt_msg==1 ? 'mensagem' : '').' que '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' faz referencia.').'<b>'.($qnt_lista_referencia_pai > 1 ? 'Referencias' : 'Referencia').'</b>'.dicaF().'</td></tr>';	
	echo '<tr><td>'.$referencia_pai.'</td></tr>';
	echo '</table></td></tr></table>';
	echo sombra_baixo(); 
	}


//referencias filho
$sql->adTabela('referencia');
$sql->esqUnir('msg', 'msg', 'msg.msg_id=referencia.referencia_msg_filho');
$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id=referencia.referencia_doc_filho');
$sql->adCampo('referencia.*, msg.de_id, modelos.*, msg.referencia, msg.data_envio, nome_de, funcao_de');
$sql->adOnde('referencia_msg_pai = '.$msg_id);
$lista_referencia_filho = $sql->Lista();
$sql->limpar();
$referencia_filho='';
if ($lista_referencia_filho && count($lista_referencia_filho)) {
		$qnt_msg=0;
		$qnt_doc=0;
		$referencia_filho.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		if ($lista_referencia_filho[0]['referencia_msg_filho']) {
			$referencia_filho.= '<tr><td>'.dica('Ler '.ucfirst($config['mensagem']), 'Clique para ler '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'<a href="javascript: void(0);" onclick="ver_msg('.$lista_referencia_filho[0]['referencia_msg_filho'].');">Msg. '.$lista_referencia_filho[0]['referencia_msg_filho'].($lista_referencia_filho[0]['referencia']? ' - '.$lista_referencia_filho[0]['referencia'] : '').' - '.nome_funcao($lista_referencia_filho[0]['nome_de'], '', $lista_referencia_filho[0]['funcao_de'], '', $lista_referencia_filho[0]['de_id']).' - '.retorna_data($lista_referencia_filho[0]['data_envio'], false).'</a>'.dicaF();
			$qnt_msg++;
			}
		else {
			if ($lista_referencia_filho[0]['modelo_autoridade_assinou']) {
				$nome=nome_funcao($lista_referencia_filho[0]['modelo_assinatura_nome'], '', $lista_referencia_filho[0]['modelo_assinatura_funcao'], '', $lista_referencia_filho[0]['modelo_autoridade_assinou']);
				$data=retorna_data($lista_referencia_filho[0]['modelo_data_assinado'], false);
				}
			elseif ($lista_referencia_filho[0]['modelo_autoridade_aprovou']) {
				$nome=nome_funcao($lista_referencia_filho[0]['modelo_aprovou_nome'], '', $lista_referencia_filho[0]['modelo_aprovou_funcao'], '', $lista_referencia_filho[0]['modelo_autoridade_aprovou']);
				$data=retorna_data($lista_referencia_filho[0]['modelo_data_aprovado'], false);
				}
			else {
				$nome=nome_funcao($lista_referencia_filho[0]['modelo_criador_nome'], '', $lista_referencia_filho[0]['modelo_criador_funcao'], '', $lista_referencia_filho[0]['modelo_criador_original']);
				$data=retorna_data($lista_referencia_filho[0]['modelo_data'], false);
				}
			$referencia_filho.= '<tr><td>'.dica('Ler Documento', 'Clique para ler este documento').'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.$lista_referencia_filho[0]['referencia_doc_filho'].($lista_referencia_filho[0]['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">Doc. '.$lista_referencia_filho[0]['referencia_doc_filho'].($lista_referencia_filho[0]['modelo_assunto']? ' - '.$lista_referencia_filho[0]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'</a>'.dicaF();
			$qnt_doc++;
			}
		$qnt_lista_referencia_filho=count($lista_referencia_filho);
		if ($qnt_lista_referencia_filho > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_referencia_filho; $i < $i_cmp; $i++) {
					if ($lista_referencia_filho[$i]['referencia_msg_filho']) {
						$lista.= dica('Ler '.ucfirst($config['mensagem']), 'Clique para ler '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'<a href="javascript: void(0);" onclick="ver_msg('.$lista_referencia_filho[$i]['referencia_msg_filho'].');">Msg. '.$lista_referencia_filho[$i]['referencia_msg_filho'].($lista_referencia_filho[$i]['referencia']? ' - '.$lista_referencia_filho[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_filho[$i]['nome_de'], '', $lista_referencia_filho[$i]['funcao_de'], '', $lista_referencia_filho[$i]['de_id']).' - '.retorna_data($lista_referencia_filho[$i]['data_envio'], false).'</a>'.dicaF().'<br>';
						$qnt_msg++;
						}
					else {
						if ($lista_referencia_filho[$i]['modelo_autoridade_assinou']) {
							$nome=nome_funcao($lista_referencia_filho[$i]['modelo_assinatura_nome'], '', $lista_referencia_filho[$i]['modelo_assinatura_funcao'], '', $lista_referencia_filho[$i]['modelo_autoridade_assinou']);
							$data=retorna_data($lista_referencia_filho[$i]['modelo_data_assinado'], false);
							}
						elseif ($lista_referencia_filho[$i]['modelo_autoridade_aprovou']) {
							$nome=nome_funcao($lista_referencia_filho[$i]['modelo_aprovou_nome'], '', $lista_referencia_filho[$i]['modelo_aprovou_funcao'], '', $lista_referencia_filho[$i]['modelo_autoridade_aprovou']);
							$data=retorna_data($lista_referencia_filho[$i]['modelo_data_aprovado'], false);
							}
						else {
							$nome=nome_funcao($lista_referencia_filho[$i]['modelo_criador_nome'], '', $lista_referencia_filho[$i]['modelo_criador_funcao'], '', $lista_referencia_filho[$i]['modelo_criador_original']);
							$data=retorna_data($lista_referencia_filho[$i]['modelo_data'], false);
							}
						$lista.= dica('Ler Documento', 'Clique para ler este documento').'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.$lista_referencia_filho[$i]['referencia_doc_filho'].($lista_referencia_filho[$i]['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">Doc. '.$lista_referencia_filho[$i]['referencia_doc_filho'].($lista_referencia_filho[$i]['modelo_assunto']? ' - '.$lista_referencia_filho[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'</a>'.dicaF().'<br>';
						$qnt_doc++;
						}
					}
				$referencia_filho.= dica('Outras Referencias', 'Clique para visualizar as demais referencias.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_referencia_filho\');">(+'.($qnt_lista_referencia_filho - 1).')</a>'.dicaF(). '<span style="display:none" id="lista_referencia_filho"><br>'.$lista.'</span>';
				}
		$referencia_filho.= '</td></tr></table>';
		} 
if ($referencia_filho) {
	echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
	echo '<table align="center" cellspacing=0 width="770" cellpadding=0>';
	echo '<tr><td align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px;background-color: #'.$cor['cor_referenciado'].';">'.dica(($qnt_lista_referencia_filho > 1 ? 'Fazem Referencia' : 'Faz Referencia'), ($qnt_doc>1 ? 'documentos' : '').($qnt_doc==1 ? 'documento' : '').($qnt_doc && $qnt_msg? ' e ' : '').($qnt_msg>1 ? 'mensagens' : '').($qnt_msg==1 ? 'mensagem' : '').' que '.($qnt_lista_referencia_filho > 1 ? 'fazem referencia' : 'faz referencia').' a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'<b>'.($qnt_lista_referencia_filho > 1 ? 'Fazem Referencia a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'] : 'Faz referencia a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'</b>'.dicaF().'</td></tr>';	
	echo '<tr><td>'.$referencia_filho.'</td></tr>';
	echo '</table></td></tr></table>';
	echo sombra_baixo(); 
	}



//Anexos


$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);

$sql->adTabela('anexos');
$sql->adUnir('usuarios','usuarios', 'anexos.usuario_id=usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->esqUnir('modelos', 'modelos', 'modelo_id = modelo');
$sql->esqUnir('modelos_tipo', 'modelos_tipo', 'modelo_tipo_id = modelo_tipo');
$sql->adCampo('nome_fantasia, modelo_tipo_nome, modelo_data, modelo_numero, modelo_autoridade_aprovou, modelo_autoridade_assinou, modelo_protocolo, modelo_assunto');
$sql->adCampo('msg_id, assinatura, anexo_id, nome, caminho, anexos.usuario_id, nome_de, funcao_de, tipo_doc, doc_nr, data_envio, contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, anexos.modelo');
$sql->esqUnir('chaves_publicas', 'chaves_publicas', 'anexos.chave_publica = chave_publica_id');
$sql->adCampo('chave_publica_chave AS publica');
$sql->adOnde('msg_id = '.$msg_id);
$sql->adOrdem('anexo_id DESC');
$anexos = $sql->Lista();
$sql->limpar();
$quantidade_anexos=0;	
foreach ($anexos as $rs_anexo){ 
	$quantidade_anexos++;
	if ($quantidade_anexos==1) {
		echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		echo '<table align="center" cellspacing=0 width="770" cellpadding=0>';
		echo	'<tr><td align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px;background-color: #'.$cor['cor_anexo'].';"><b>'.(count($anexos) > 1 ? dica('Anexos','Arquivos ou documentos internos anexados n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'Anexos' : dica('Anexo','Arquivo ou documento interno anexado n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'Anexo').dicaF().'</b></td></tr>';	
		}
	$assinado='';
	if (function_exists('openssl_sign') && $rs_anexo['assinatura']){	
		//$identificador=$rs_anexo['msg_id'].$rs_anexo['nome'].$rs_anexo['caminho'].$rs_anexo['usuario_id'].$rs_anexo['tipo_doc'].$rs_anexo['doc_nr'].$rs_anexo['data_envio'].$rs_anexo['modelo'];
		
		$identificador=$rs_anexo['msg_id'].$rs_anexo['nome'].$rs_anexo['caminho'].$rs_anexo['usuario_id'].$rs_anexo['tipo_doc'].$rs_anexo['doc_nr'].$rs_anexo['data_envio'].$rs_anexo['modelo'];
					
		
		if ($rs_anexo['publica'])	$ok = openssl_verify($identificador, base64_decode($rs_anexo['assinatura']), $rs_anexo['publica']);
		else $ok=0;
		if (!$ok) $assinado=dica(nome_funcao('','','','',$rs_anexo['usuario_id']),'A assinatura digital do anexo não confere! Anexo possívelmente adulterado.').'<img src="'.acharImagem('icones/assinatura_erro.gif').'" style="vertical-align:top" width="15" height="13" />'.dicaF();
		else $assinado=dica(nome_funcao('','','','',$rs_anexo['usuario_id']),'A assinatura digital do anexo confere.').'<img src="'.acharImagem('icones/assinatura.gif').'" style="vertical-align:top" width="15" height="13" />'.dicaF();
		}
	if (!$assinado) $assinado='<img src="'.acharImagem('icones/assinatura_sem.gif').'" style="vertical-align:top" width="15" height="13" />';
	if ($rs_anexo['modelo']){
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Remetente</b></td><td>'.nome_funcao($rs_anexo['nome_de'], $rs_anexo['nome_usuario'], $rs_anexo['funcao_de'], $rs_anexo['contato_funcao']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($rs_anexo['data_envio']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tipo</b></td><td>'.$rs_anexo['modelo_tipo_nome'].'</td></tr>';
		if ($rs_anexo['modelo_protocolo']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Protocolo</b></td><td>'.$rs_anexo['modelo_protocolo'].'</td></tr>';
		if ($rs_anexo['modelo_assunto']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Assunto</b></td><td>'.$rs_anexo['modelo_assunto'].'</td></tr>';
		if ($rs_anexo['modelo_autoridade_assinou']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Assinou</b></td><td>'.nome_usuario($rs_anexo['modelo_autoridade_assinou']).'</td></tr>';
		elseif ($rs_anexo['modelo_autoridade_aprovou']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Aprovou</b></td><td>'.nome_usuario($rs_anexo['modelo_autoridade_aprovou']).'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique neste link para visualizar o documento no Navegador Web.';
		}
	else{	 
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Remetente</b></td><td>'.nome_funcao($rs_anexo['nome_de'], $rs_anexo['nome_usuario'], $rs_anexo['funcao_de'], $rs_anexo['contato_funcao']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($rs_anexo['data_envio']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Referência</b></td><td>'.$rs_anexo['doc_nr'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tipo</b></td><td>'.$rs_anexo['tipo_doc'].'</td></tr>';
		if ($rs_anexo['nome_fantasia'] && $rs_anexo['nome_fantasia']!=$rs_anexo['nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Nome original</b></td><td>'.$rs_anexo['nome'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
		}
	if ($rs_anexo['modelo']) echo '<tr align="left" style="font-size:10pt; padding-left: 5px; padding-right: 5px;background-color: #'.$cor['cor_fundo'].';" ><td>'.$assinado.'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.$rs_anexo['modelo'].($rs_anexo['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">'.dica(($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']),$dentro).($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']).' - '.nome_funcao($rs_anexo['nome_de'], '', $rs_anexo['funcao_de'], '', $rs_anexo['usuario_id']).($rs_anexo['data_envio'] ? ' - '.retorna_data($rs_anexo['data_envio']) : '').dicaF().'</a><a href="javascript:void(0);" onclick="window.open(\'?m=email&a=historico_anexo&dialogo=1&modelo_id='.$rs_anexo['modelo'].'\', \'historico\', \'width=790, height=600, left=0, top=0, scrollbars=yes, resizable=no\')">'.imagem('icones/info.gif','Histórico de leitura','Clique neste ícone '.imagem('icones/info.gif').' para visualizar o histórico de leitura deste documento.').'</a></td></td></tr>';
	else echo '<tr align="left" style="font-size:10pt; padding-left: 5px; padding-right: 5px;background-color: #'.$cor['cor_fundo'].';" ><td>'.$assinado.'<a href="javascript:void(0);" onclick="anexo_lido('.$rs_anexo['anexo_id'].'); url_passar(0, \'m=email&a=download_arquivo&sem_cabecalho=1&anexo='.$rs_anexo['anexo_id'].'\');">'.dica(($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']),$dentro).($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']).' - '.nome_funcao($rs_anexo['nome_de'], '', $rs_anexo['funcao_de'], '', $rs_anexo['usuario_id']).($rs_anexo['data_envio'] ? ' - '.retorna_data($rs_anexo['data_envio']) : '').'</a>&nbsp;<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=historico_anexo&dialogo=1&anexo_id='.$rs_anexo['anexo_id'].'\', \'historico\', \'width=790, height=600, left=0, top=0, scrollbars=yes, resizable=no\')">'.imagem('icones/info.gif','Histórico de leitura','Clique neste ícone '.imagem('icones/info.gif').' para visualizar o histórico de leitura deste anexo.').'</a></td></tr>';
	
	}
if ($quantidade_anexos)  echo '</table></td></tr></table>'.sombra_baixo();	


if ($Aplic->getPref('msg_extra')) include_once(BASE_DIR.'/modulos/email/exibe_extra.php');
else echo '<table align="center"><tr><td>'.dica('Histórico','Clique neste link para visualizar o processamento d'.$config['genero_mensagem'].' '.$config['mensagem'].' com os encaminhamentos, notas, despachos e respostas.').'<a href="javascript:void(0);" onclick="javascript:visualizar_encaminhamentos();" style="padding-left: 5px; font-size:10pt; font-weight:Bold;">Histórico</a>'.dicaF().'</td></tr></table>';
echo '</form>';

echo '<form name="impressao" id="impressao" method="POST" action="" target="_new"><input type=hidden name="msg_id" value="'.$msg_id.'"></form>';
echo '</body></html>';

function formata_destinatario($rs_para=array()){
	global $Aplic,$tipos_status;
	$saida='';
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
	if ($rs_para['copia_oculta'] !=1|| $rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3) $saida.= nome_funcao($rs_para['nome_para'], $rs_para['nome_usuario'], $rs_para['funcao_para'], $rs_para['contato_funcao'], $rs_para['para_id']);
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id  || $Aplic->usuario_acesso_email > 3)) $saida.= '</i>';
	if ($rs_para['copia_oculta'] !=1 || $rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id  || $Aplic->usuario_acesso_email > 3){
		if (!$rs_para['datahora_leitura']){
			if ($Aplic->usuario_cm) $saida.= '<a href="javascript:void(0);" onclick="javascript:env.a.value=\'envia_entrega_cm\'; env.usuario_id.value='.$rs_para['para_id'].'; env.submit();" > - não lida</a>';
			else $saida.= ' - não lida'; 
			}
		else{
			$saida.= ' - '.$tipos_status[$rs_para['status']].' em '.retorna_data($rs_para['datahora_leitura']);
			if ($rs_para['cm'] > 1 ) $saida.= ' - (CM: '.nome_usuario($rs_para['cm']).' por '.$rs_para['meio'].') ';
			}
		}	
	return $saida;	
	}

function formata_despacho ($rs_anotf=array()){
	global $Aplic;
	$saida='';
	if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '<b>';
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
  if ($rs_anotf['copia_oculta'] !=1 || ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= nome_funcao($rs_anotf['nome_para'], $rs_anotf['nome_usuario'], $rs_anotf['funcao_para'], $rs_anotf['contato_funcao'])."&nbsp;&nbsp;";
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3 )) $saida.= '</i>'; 
  if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '</b>';
  return $saida;
	}
	
if($Aplic->profissional){
	$dados = array( 'id' => $Aplic->usuario_id, 'nome' => utf8_encode($Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra));
	$dados['msg_nao_lidas'] = $Aplic->mensagensNaoLidas();
	$dados['msg_total'] = $Aplic->mensagensTotalCaixaEntrada();
	$dados['msg_pendentes'] = $Aplic->mensagensTotalPendentes();
	echo '<script type="text/javascript">if(parent && parent.gpwebApp) parent.gpwebApp.atualizaDadosUsuario('.json_encode($dados).')</script>';	
}

?>
<script language="javascript">

function mudar_porcentagem(msg_usuario_id){
	porcentagem=document.getElementById('percentagem_'+msg_usuario_id).value;
	xajax_mudar_porcentagem_ajax(msg_usuario_id, porcentagem);
	}

//enviar msg para caixa de entrada
function ver_msg(msg_id){
	env.a.value='exibe_msg';
	env.msg_id.value=msg_id;
	env.submit();	
	}



function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function anexo_lido(anexo_id){	
	xajax_anexo_lido_ajax(anexo_id); 	
	}

	
function sumir(campo){
		document.getElementById(campo).style.display = 'none';
		}
		
function ver_destinatario(){
	if (document.getElementById('destinatario').style.display=='none') document.getElementById('destinatario').style.display='';
	else document.getElementById('destinatario').style.display='none';
	}

function mover_pasta(pasta_id) {
	env.a.value='lista_msg';
	env.arquivar.value=1;
	env.pasta.value=pasta_id;
	env.mover.value=<?php echo $msg_usuario_id?>;
	env.submit();
	}; 

//enviar msg para caixa de entrada
function entrada(){
	env.a.value='grava_status';
	env.status.value=1;
	env.submit();	
	}

//pender
function pender(){
	env.a.value='grava_status';
	env.status.value=3;
	env.submit();		
	}

//arquivar
function arquiva(){
	env.a.value='grava_status';
	env.status.value=4;
	env.submit();		
	}

//responder msg
function responder(){
	env.a.value='envia_anot';
	env.tipo.value=2;
	env.submit();		
	}

//despachor
function despachar(){
	env.a.value='seleciona_usuarios';
	env.destino.value='envia_anot';
	env.tipo.value=1;
	env.submit();			
	}

//anotacao
function anotar(){
	env.a.value='envia_anot';
	env.tipo.value=4;
	env.submit();
	}

//enviar anexo
function enviar_anexo(){
	env.a.value='envia_anexo';
	env.submit();
	} 

//enviar documento
function enviar_documento(){
	env.anexar_documento.value=1;
	env.a.value='modelo_pesquisar';
	env.submit();
	} 

function voltar(){
	env.a.value='lista_msg';
	env.submit();
	}


//encaminhar
function encaminhar(){
	env.a.value='seleciona_usuarios';
	env.destino.value='grava_encaminha';
	env.tipo.value=3;
	env.submit();	
	}  

function visualizar_encaminhamentos(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 800, 'm=email&a=exibe_extra&dialogo=1&msg_usuario_id=<?php echo $rs["msg_usuario_id"] ?>&msg_id=<?php echo $rs["msg_id"] ?>', null, window);
	else window.open('./index.php?m=email&a=exibe_extra&dialogo=1&msg_usuario_id=<?php echo $rs["msg_usuario_id"] ?>&msg_id=<?php echo $rs["msg_id"] ?>', '','height=600, width=800, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no, left=0, top=0,');
	}	
	
function resposta_despacho(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 800, 'm=email&a=resposta_despacho&dialogo=1&msg_usuario_id=<?php echo $rs["msg_usuario_id"] ?>&msg_id=<?php echo $rs["msg_id"] ?>&&anotacao_id=<?php echo $rs["anotacao_id"] ?>', null, window);
	else window.open('./index.php?m=email&a=resposta_despacho&dialogo=1&msg_usuario_id=<?php echo $rs["msg_usuario_id"] ?>&msg_id=<?php echo $rs["msg_id"] ?>&&anotacao_id=<?php echo $rs["anotacao_id"] ?>', '','height=600, width=840, left=0, top=0, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}	   
</script>
