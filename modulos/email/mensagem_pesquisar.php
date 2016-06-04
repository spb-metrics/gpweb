<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
global $dialogo;

if (!$dialogo) $Aplic->salvarPosicao();

$Aplic->carregarCalendarioJS();

if (isset($_REQUEST['vetor_modelo_msg_usuario'])) $vetor_modelo_msg_usuario = getParam($_REQUEST, 'vetor_modelo_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id']) && $_REQUEST['msg_usuario_id']) $vetor_modelo_msg_usuario[] = getParam($_REQUEST, 'msg_usuario_id', null);

if (!isset($vetor_modelo_msg_usuario)){
	if (isset($_REQUEST['modeloID']) && $_REQUEST['modeloID']) $modeloID = getParam($_REQUEST, 'modeloID', null); 
	else if (isset($_REQUEST['msg_id']) && $_REQUEST['msg_id']) $modeloID[] = getParam($_REQUEST, 'msg_id', null);
	else if (!isset($modeloID)) $modeloID = array();
	}

if (isset($_REQUEST['vetor_msg_usuario'])) $vetor_msg_usuario = getParam($_REQUEST, 'vetor_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id'])) $vetor_msg_usuario[] = getParam($_REQUEST, 'msg_usuario_id', null);
else  $vetor_msg_usuario = array();


$msg_usuario_id=reset($vetor_msg_usuario); 

$msg_id_original=getParam($_REQUEST, 'msg_id_original', 0);

$status=getParam($_REQUEST, 'status', 1);
$senha=getParam($_REQUEST, 'senha', '');
$campo_ordenar=getParam($_REQUEST, 'campo_ordenar', 'data');
$pesquisar=getParam($_REQUEST, 'pesquisar', 0);
$pesquisa_inicio=getParam($_REQUEST, 'pesquisa_inicio', null);
$pesquisa_fim=getParam($_REQUEST, 'pesquisa_fim', null);
$data_inicio = intval($pesquisa_inicio) ? new CData($pesquisa_inicio) : new CData();
$data_fim = intval($pesquisa_fim) ? new CData($pesquisa_fim) : new CData();
$pagina = getParam($_REQUEST, 'pagina', 1);
$sentido = getParam($_REQUEST, 'sentido', 0);
$numero=getParam($_REQUEST, 'numero', '');
$assunto=getParam($_REQUEST, 'assunto', '');
$pesquisa_inicio=getParam($_REQUEST, 'pesquisa_inicio', null);
$pesquisa_fim=getParam($_REQUEST, 'pesquisa_fim', null);
$tipo_tempo=getParam($_REQUEST, 'tipo_tempo', '');
$criador=getParam($_REQUEST, 'criador', 0);
$enviou=getParam($_REQUEST, 'enviou', 0);


$caixa_entrada=getParam($_REQUEST, 'caixa_entrada', 0);
$caixa_pendente=getParam($_REQUEST, 'caixa_pendente', 0);
$caixa_arquivado=getParam($_REQUEST, 'caixa_arquivado', 0);


$anexar_mensagem=getParam($_REQUEST, 'anexar_mensagem', 0);
$referenciar_mensagem=getParam($_REQUEST, 'referenciar_mensagem', 0);

$anexar_msg=getParam($_REQUEST, 'anexar_msg', 0);
$retornar=getParam($_REQUEST, 'retornar', '');
$campo=getParam($_REQUEST, 'campo', 0);
$msg_id=getParam($_REQUEST, 'msg_id', 0);
$pesquisar_tudo=getParam($_REQUEST, 'pesquisar_tudo', 0);
$item_menu=getParam($_REQUEST, 'item_menu', ($anexar_mensagem || $referenciar_mensagem ? '' : 'entrada'));

$tipo=array(''=> '', '0'=>'', '1'=>'Despacho', '2'=>'Resposta', '3'=>'Encaminhamento', '4'=>'Nota');
//muda a ordenação ao clicar nos titulos

if ($sentido) {
	$sentido=0; 
	$ordem='DESC'; 
	} 
else{ 
	$sentido=1; 
	$ordem='ASC' ; 
	};

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="m" name="m" value="email">';
echo '<input type=hidden id="a" name="a" value="mensagem_pesquisar">';
echo '<input type=hidden id="msg_id_original" name="msg_id_original" value="'.$msg_id_original.'">';
echo '<input type=hidden id="msg_usuario_id" name="msg_usuario_id" value="'.$msg_usuario_id.'">';	
echo '<input type=hidden id="pesquisar" name="pesquisar" value="'.$pesquisar.'">';
echo '<input type=hidden id="pagina" name="pagina" value="'.$pagina.'">';
echo '<input type=hidden id="sentido" name="sentido" value="'.$sentido.'">';	
echo '<input type=hidden id="campo_ordenar" name="campo_ordenar" value="'.$campo_ordenar.'">';
echo '<input type=hidden id="editar" name="editar" value="">';
echo '<input type=hidden id="item_menu" name="item_menu" value="'.$item_menu.'">';
echo '<input type=hidden id="caixa_entrada" name="caixa_entrada" value="">';
echo '<input type=hidden id="caixa_pendente" name="caixa_pendente" value="">';
echo '<input type=hidden id="caixa_arquivado" name="caixa_arquivado" value="">';


echo '<input type=hidden id="anexar_mensagem" name="anexar_mensagem" value="'.$anexar_mensagem.'">';
echo '<input type=hidden id="referenciar_mensagem" name="referenciar_mensagem" value="'.$referenciar_mensagem.'">';

echo '<input type=hidden id="dialogo" name="dialogo" value="'.$dialogo.'">';
echo '<input type=hidden id="anexar_msg" name="anexar_msg" value="">';
echo '<input type=hidden id="retornar" name="retornar" value="'.$retornar.'">';
echo '<input type=hidden id="campo" name="campo" value="'.$campo.'">';
echo '<input type=hidden id="msg_id" name="msg_id" value="'.$msg_id.'">';

echo '<input type=hidden id="status" name="status" value="'.$status.'">';

echo '<input type=hidden id="tipo" name="tipo" value="">';
echo '<input type=hidden name="destino" id="destino" value="">';	
echo '<input type=hidden name="arquivar" id="arquivar" value="">';
echo '<input type=hidden name="novo" id="novo" value="">';
echo '<input type=hidden name="tab" id="tab" value="">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="">';
echo '<input type=hidden name="msg_usuario_id" id="msg_usuario_id" value="">';

$sql = new BDConsulta;

if ($anexar_msg){
	$data=date('Y-m-d H:i:s');
	$msg_id=$anexar_msg;
	$sql->adTabela('anexos');
	$sql->adCampo('anexo_id');
	$sql->adOnde('msg_id_original = '.$msg_id_original);
	$sql->adOnde('modelo = '.$msg_id);
	$existente=$sql->Resultado();
	
	if (!$existente){
		$sql->adTabela('modelos');
		$sql->esqUnir('usuarios','usuarios','modelo_criador_original=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('modelos_tipo', 'modelos_tipo', 'modelo_tipo_id = modelo_tipo');
		$sql->esqUnir('modelos_dados','modelos_dados','modelo_dados_modelo=modelos.msg_id');
		$sql->esqUnir('modelos_anexos', 'modelos_anexos', 'modelos_anexos.msg_id=modelos.msg_id');
		$sql->adCampo('modelo_tipo_nome, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, referencia, modelos.msg_id, modelo_tipo, modelo_criador_original, modelo_data, modelo_versao_aprovada');
		$sql->adOnde('modelos.msg_id = '.$msg_id);
		$linha=$sql->Linha();
		$sql->Limpar();
		$assinatura='';
		if (function_exists('openssl_sign') && $Aplic->chave_privada)	{
			$identificador=$msg_id_original.$linha['referencia'].$Aplic->usuario_id.$linha['modelo_tipo_nome'].$data.$msg_id;
			openssl_sign($identificador, $assinatura, $Aplic->chave_privada);
			}
		$sql->adTabela('anexos');
		$sql->adInserir('msg_id_original', $msg_id_original);
		$sql->adInserir('nome', $linha['referencia']);
		$sql->adInserir('usuario_id', $Aplic->usuario_id);
		$sql->adInserir('tipo_doc', $linha['modelo_tipo_nome']);
		$sql->adInserir('nome_de', $Aplic->usuario_nome);
		$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
		$sql->adInserir('data_envio', $data);
		$sql->adInserir('modelo', $msg_id);
		$sql->adInserir('assinatura',  base64_encode($assinatura));
		if ($Aplic->chave_publica_id) $sql->adInserir('chave_publica', $Aplic->chave_publica_id);
		if (!$sql->exec()) echo ('Não foi possível inserir os anexos na tabela anexos!');
		$sql->Limpar();
		echo '<script language="javascript">alert("Documento foi anexado.");</script>';
		}
	else 	echo '<script language="javascript">alert("Documento já foi anexado anteriormente.");</script>';
	echo '<script language="javascript">env.a.value="'.$retornar.'";env.submit();</script>';
	exit();
	}


 



$tipos_tempo=array('data_criacao'=>'data de criação', 'data_envio'=>'data de envio'); 


$cor_prioridade=getSisValor('cor_precedencia');
$precedencia=getSisValor('precedencia','','','sisvalor_valor_id ASC');
$class_sigilosa=getSisValor('class_sigilosa','','','sisvalor_valor_id ASC');
$tipos_status=getSisValor('status');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

//pega status do cm (ver msg de outros)
if (isset($_REQUEST['status_cabecalho'])) $status = getParam($_REQUEST, 'status_cabecalho', null);
$numero_status=getParam($_REQUEST, 'numero_status', 0);



//pesquisa
$sel_usuario_de=getParam($_REQUEST, 'sel_usuario_de', 0);
$sel_usuario_para=getParam($_REQUEST, 'sel_usuario_para', 0);
$assunto=getParam($_REQUEST, 'assunto', '');
$ordem_inicio=getParam($_REQUEST, 'pesquisa_inicio', '');
$ordem_fim=getParam($_REQUEST, 'pesquisa_fim', '');

	
$lista_msg = 1;
	
$titulo = 'Pesquisa de '.ucfirst($config['mensagens']).'';

if($pesquisar){
	$sql->adTabela('msg');
  $sql->esqUnir('msg_usuario','msg_usuario','msg_usuario.msg_id = msg.msg_id');
  $sql->esqUnir('usuarios','usuarios', 'msg_usuario.de_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
  $sql->adCampo('cia_nome, dept_nome');
  $sql->adCampo('class_sigilosa, msg.de_id as criador, texto, msg_usuario_id, msg_usuario.datahora, referencia, msg_usuario.datahora, msg.msg_id, msg_usuario.cor, msg_usuario.nota, contatos.contato_funcao, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	
	if ($caixa_entrada){
		$sql->adOnde('msg_usuario.status<3');
		$sql->adOnde('msg_usuario.para_id='.$Aplic->usuario_id);
		}
	elseif ($caixa_pendente){
		$sql->adOnde('msg_usuario.status=3');
		$sql->adOnde('msg_usuario.para_id='.$Aplic->usuario_id);
		}
	elseif ($caixa_arquivado){
		$sql->adOnde('msg_usuario.status=4');
		$sql->adOnde('msg_usuario.para_id='.$Aplic->usuario_id);
		}
	else{
		$sql->adOnde('msg_usuario.para_id='.$Aplic->usuario_id.' OR msg_usuario.de_id='.$Aplic->usuario_id);
		}
	

	if ($enviou) $sql->adOnde('msg_usuario.de_id='.$enviou);
	if ($numero) $sql->adOnde('msg.msg_id='.$numero);


	if ($assunto) $sql->adOnde('msg.referencia LIKE \'%'.$assunto.'%\' OR msg.texto LIKE \'%'.$assunto.'%\'');
	
	if ($pesquisa_inicio){
		if ($tipo_tempo=='data_criacao')  $sql->adOnde('msg.data_envio >= \''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\'');
		elseif ($tipo_tempo=='data_envio')  $sql->adOnde('msg_usuario.datahora >= \''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\'');
		}
	if ($pesquisa_fim){
		if ($tipo_tempo=='data_criacao')  $sql->adOnde('msg.data_envio <= \''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\'');
		elseif ($tipo_tempo=='data_envio')  $sql->adOnde('msg_usuario.datahora <= \''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\'');
		}
	if ($criador) $sql->adOnde('msg.de_id='.$criador);	
	
	if (!$pesquisar_tudo && ($item_menu=='entrada'|| $item_menu=='pendente'||$item_menu=='arquivado' || $item_menu=='enviado')) $sql->adOnde('msg_usuario.de_id='.$Aplic->usuario_id.' OR msg_usuario.para_id='.$Aplic->usuario_id);
	
	
	if ($campo_ordenar=='numero') $sql->adOrdem('msg.msg_id '.$ordem.', status ASC');
	else if ($campo_ordenar=='criador') $sql->adOrdem('msg.de_id '.$ordem.', msg.msg_id DESC, status ASC');
	else if ($campo_ordenar=='referencia')$sql->adOrdem('referencia '.$ordem.', msg.msg_id DESC, status ASC');
	else if ($campo_ordenar=='data_envio')$sql->adOrdem('msg_usuario.datahora '.$ordem.', msg.msg_id DESC, status ASC');
	else if ($campo_ordenar=='data_criacao')$sql->adOrdem('msg.data_envio '.$ordem.', msg.msg_id DESC, status ASC');
	else $sql->adOrdem('msg.msg_id '.$ordem.', status ASC');
	//$sql->adGrupo('msg.msg_id');
  //EUZ Postgres
	$sql->adGrupo('msg.msg_id, cia_nome, dept_nome,class_sigilosa, msg.de_id, texto, msg_usuario_id, msg_usuario.datahora, referencia, contatos.contato_funcao, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, contato_posto, contato_nomeguerra');
	//EUD
	
	}
elseif ($item_menu=='entrada'){
	$titulo = 'Caixa de Entrada de '.ucfirst($config['mensagens']).'';
	if (empty($numero_status)) $numero_status = 1;
	$sql->adTabela('msg');
  $sql->esqUnir('msg_usuario','msg_usuario','msg_usuario.msg_id = msg.msg_id');
  $sql->esqUnir('usuarios','usuarios', 'msg_usuario.de_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
  $sql->adCampo('cia_nome, dept_nome');
  $sql->adCampo('class_sigilosa, msg.de_id as criador, texto, msg_usuario_id, msg_usuario.datahora, referencia, msg_usuario.datahora, msg.msg_id, msg_usuario.cor, msg_usuario.nota, contatos.contato_funcao, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	
	$sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
	$sql->adOnde('msg_usuario.status <= 2');
	//$sql->adGrupo('msg_id');
  //EUZ Postgres
	$sql->adGrupo('msg.msg_id, cia_nome, dept_nome,class_sigilosa, msg.de_id, texto, msg_usuario_id, msg_usuario.datahora, referencia, contatos.contato_funcao, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, contato_posto, contato_nomeguerra');
	//EUD
	
	}
//pendentes
elseif ($item_menu=='pendente'){
	$titulo = ucfirst($config['mensagens']).' Pendentes';
	$sql->adTabela('msg');
  $sql->esqUnir('msg_usuario','msg_usuario','msg_usuario.msg_id = msg.msg_id');
  $sql->esqUnir('usuarios','usuarios', 'msg_usuario.de_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
  $sql->adCampo('cia_nome, dept_nome');
  $sql->adCampo('class_sigilosa, msg.de_id as criador, texto, msg_usuario_id, msg_usuario.datahora, referencia, msg_usuario.datahora, msg.msg_id, msg_usuario.cor, msg_usuario.nota, contatos.contato_funcao, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('msg_usuario.para_id= '.$Aplic->usuario_id);
	$sql->adOnde('msg_usuario.status = 3');
  //EUZ Postgres
  $sql->adGrupo('msg.msg_id, cia_nome, dept_nome,class_sigilosa, msg.de_id, texto, msg_usuario_id, msg_usuario.datahora, referencia, contatos.contato_funcao, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, contato_posto, contato_nomeguerra');
  //$sql->adGrupo('msg_id');

	}
//arquivadas
elseif ($item_menu=='arquivado'){
	$titulo = ucfirst($config['mensagens']).' Arquivad'.$config['genero_mensagem'].'s';
	$sql->adTabela('msg');
  $sql->esqUnir('msg_usuario','msg_usuario','msg_usuario.msg_id = msg.msg_id');
  $sql->esqUnir('usuarios','usuarios', 'msg_usuario.de_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
  $sql->adCampo('cia_nome, dept_nome');
  $sql->adCampo('class_sigilosa, msg.de_id as criador, texto, msg_usuario_id, msg_usuario.datahora, referencia, msg_usuario.datahora, msg.msg_id, msg_usuario.cor, msg_usuario.nota, contatos.contato_funcao, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	
	$sql->adOnde('msg_usuario.para_id= '.$Aplic->usuario_id);
	$sql->adOnde('msg_usuario.status = 4');
  //$sql->adGrupo('msg_id');
  //EUZ Postgres
	$sql->adGrupo('msg.msg_id, cia_nome, dept_nome,class_sigilosa, msg.de_id, texto, msg_usuario_id, msg_usuario.datahora, referencia, contatos.contato_funcao, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, contato_posto, contato_nomeguerra');
  //EUD  

	}
//enviados
elseif ($item_menu=='enviado'){
	$titulo = ucfirst($config['mensagens']).' Enviad'.$config['genero_mensagem'].'s';
	$sql->adTabela('msg');
  $sql->esqUnir('msg_usuario','msg_usuario','msg_usuario.msg_id = msg.msg_id');
  $sql->esqUnir('usuarios','usuarios', 'msg_usuario.de_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
  $sql->adCampo('cia_nome, dept_nome');
  $sql->adCampo('class_sigilosa, msg.de_id as criador, texto, msg_usuario_id, msg_usuario.datahora, referencia, msg_usuario.datahora, msg.msg_id, msg_usuario.cor, msg_usuario.nota, contatos.contato_funcao, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('msg_usuario.de_id = '.$Aplic->usuario_id);
  //EUZ Postgres
	$sql->adGrupo('msg.msg_id, cia_nome, dept_nome,class_sigilosa, msg.de_id, texto, msg_usuario_id, msg_usuario.datahora, referencia, contatos.contato_funcao, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, contato_posto, contato_nomeguerra');
  //$sql->adGrupo('msg.msg_id');
  //EUD

  }

$xpg_total_paginas =0;	


if ($campo_ordenar=='numero') $sql->adOrdem('msg.msg_id '.$ordem.', status ASC');
else if ($campo_ordenar=='criador') $sql->adOrdem('msg.de_id '.$ordem.', msg.msg_id DESC, status ASC');
else if ($campo_ordenar=='referencia')$sql->adOrdem('referencia '.$ordem.', msg.msg_id DESC, status ASC');
else if ($campo_ordenar=='data_envio')$sql->adOrdem('msg_usuario.datahora '.$ordem.', msg.msg_id DESC, status ASC');
else if ($campo_ordenar=='data_criacao')$sql->adOrdem('msg.data_envio '.$ordem.', msg.msg_id DESC, status ASC');
else $sql->adOrdem('msg.msg_id '.$ordem.', status ASC');

if ($tipo_tempo=='data_envio') $campo_data='datahora';
else $campo_data='data_envio';
		
$resultados=$sql->Lista();
$sql->limpar();		
		
echo estiloTopoCaixa();
echo '<table class="std2" width="100%" align="center" cellpadding=0 cellspacing=0 >';
echo '<tr><td align="left" colspan="20" width="100%" id="barra">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	//caixa entrada
	$sql->adTabela('msg_usuario');
	$sql->adCampo('count(DISTINCT msg_id)');
	$sql->adOnde('para_id='.$Aplic->usuario_id);
	$sql->adOnde('status<2');
	$total_entrada = $sql->Resultado();
	$sql->limpar();
	$km->Add("root","entrada","Entrada".($total_entrada ? ' ('.$total_entrada.')' : ''), "javascript: void(0);' onclick='env.item_menu.value=\"entrada\"; env.pesquisar.value=0; env.submit();");
	//caixa pendentes
	$sql->adTabela('msg_usuario');
	$sql->adCampo('count(msg_usuario_id)');
	$sql->adOnde('para_id='.$Aplic->usuario_id);
	$sql->adOnde('status=3');
	$pendentes = $sql->Resultado();
	$sql->limpar();
	$km->Add("root","pendentes","Pendentes".($pendentes ? ' ('.$pendentes.')': ''), "javascript: void(0);' onclick='limpar_pesquisa(); env.item_menu.value=\"pendente\"; env.pesquisar.value=0; env.submit();");
	$km->Add("root","arquivados","Arquivadas", "javascript: void(0);' onclick='env.item_menu.value=\"arquivado\"; env.pesquisar.value=0; env.submit();");
	$km->Add("root","enviados","Enviadas", "javascript: void(0);' onclick='env.item_menu.value=\"enviado\"; env.pesquisar.value=0; env.submit();");
	echo $km->Render();
	echo '</td></tr>';
	
echo '<tr><td height=30  colspan="20"><center><h1>'.$titulo.'</h1></center></td></tr>';	
	
echo '<tr id="pesquisa_completa" '.(!$anexar_mensagem && !$referenciar_mensagem && !$pesquisar ? 'style="display:none;"' : '').'><td><table width="100%">';
echo '<tr><td width="100%" valign="top"><table width="100%" cellpadding=0 cellspacing=0>';
echo '<tr><td align="right" width="130">'.dica('Texto à Pesquisar','Escreva a palavra chave a ser pesquisa n'.$config['genero_mensagem'].'s '.$config['mensagens'].' do sistema.').'Texto:'.dicaF().'</td><td><input type="text" class="texto" name="assunto" id="assunto" size="60" value="'.$assunto.'"></td></tr>';
echo '<tr><td align="right">'.dica('Nr Documento','Escolha o número d'.$config['genero_mensagem'].' '.$config['mensagem'].' que deseja encontrar').'Nr '.ucfirst($config['mensagem']).':'.dicaF().'</td><td><input type="text" class="texto" name="numero" id="numero" size="10" value="'.$numero.'"></td></tr>';
echo '<tr><td align="right">'.dica('Criador','Escolha '.$config['genero_mensagem'].'s '.$config['mensagens'].' que tenham sido criado '.$config['genero_mensagem'].'s pel'.$config['genero_usuario'].' '.$config['usuario'].' selecionado.').'Criador:'.dicaF().'</td><td><input type="hidden" id="criador" name="criador" value="'.$criador.'" /><input type="text" id="nome_criador" name="nome_criador" value="'.nome_om($criador,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popCriador();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';	
echo '<tr><td align="right">'.dica('Enviou','Escolha '.$config['genero_mensagem'].'s '.$config['mensagens'].' que tenham sido enviad '.$config['genero_mensagem'].'s pel'.$config['genero_usuario'].' '.$config['usuario'].' selecionado.').'Enviou:'.dicaF().'</td><td><input type="hidden" id="enviou" name="enviou" value="'.$enviou.'" /><input type="text" id="nome_enviou" name="nome_enviou" value="'.nome_om($enviou,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popEnviou();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';	

if ($Aplic->usuario_super_admin) echo '<tr><td align="right">Todos '.$config['genero_usuario'].'s '.$config['usuarios'].':</td><td><input type="checkbox"" class="texto" name="pesquisar_tudo" id="pesquisar_tudo"  value="1"></td></tr>';
else echo '<input type="hidden""  name="pesquisar_tudo" id="pesquisar_tudo"  value="0">';


echo '<tr><td colspan=20></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Início', 'Digite ou escolha no calendário a data de início da pesquisa.').'De:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="pesquisa_inicio" id="pesquisa_inicio" value="'.$pesquisa_inicio.'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'pesquisa_inicio\');" value="'.($pesquisa_inicio ? retorna_data($pesquisa_inicio, false): '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data inicial da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="Agenda"" border=0 /></a>'.dicaF().dica('Data de Término', 'Digite ou escolha no calendário a data de término da pesquisa.').'&nbsp;&nbsp;a&nbsp;&nbsp;'.dicaF().'<input type="hidden" name="pesquisa_fim" id="pesquisa_fim" value="'.$pesquisa_fim.'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\', \'pesquisa_fim\');" value="'.($pesquisa_fim ? retorna_data($pesquisa_fim, false): '').'" class="texto" />'.dica('Data de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data limite da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="Agenda"" border=0 /></a>'.dicaF().'&nbsp;&nbsp;&nbsp;&nbsp;'.selecionaVetor($tipos_tempo, 'tipo_tempo', 'size="1" class="texto"', $tipo_tempo).'</td></tr>';
echo '</table></td></tr>';
echo '<tr><td colspan=2 align="center"><table width="100%" cellpadding=0 cellspacing=0><tr><td width="130">&nbsp;</td><td>'.botao('pesquisar', 'Pesquisar','Clique neste botão para efetuar a pesquisa n'.$config['genero_mensagem'].'s '.$config['mensagens'].'.','','env.item_menu.value=\'pesquisar\'; env.pesquisar.value=1; env.submit();').'</td><td>'.($retornar ? botao('retornar', 'Retornar','Ao se pressionar este botão irá retornar a tela anterior.','','env.a.value=\''.$retornar.'\'; env.submit();') : '').'</td></tr></table></td></tr>';
echo '</table></td></tr>';






echo '</table>';	
echo '<table width="100%" class="std" align="center" rules="ALL" cellpadding=0 cellspacing=0>';
echo '<tr align=center>';


if (!$anexar_mensagem && !$referenciar_mensagem) echo '<td><input type="checkbox" name="sel_todas" value="1" onclick="marca_sel_todas();"></td>';
echo '<td>'.dica('Ordenar pelo Assunto','Clique para ordenar pelo assunto d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'referencia\');">'.($campo_ordenar=='referencia' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Assunto</b></a>'.dicaF().'</td>';
echo '<td>'.dica('Ordenar pelo Remetente','Clique para ordenar pelos remetentes d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'de\');">'.($campo_ordenar=='de' ? imagem('icones/'.$seta[$sentido]) : '').'<b>De</b></a>'.dicaF().'</td>';
if ($item_menu=='enviado') echo '<td><b>Não Leram</b></td>';
echo '<td>'.dica('Ordenar pela Data de Envio','Clique para ordenar pela data de envio d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'data\');">'.($campo_ordenar=='data' ? imagem('icones/'.$seta[$sentido]) : '').'<b><b>Data de Envio</b></a>'.dicaF().'</td>';
if ($item_menu=='entrada') echo '<td align="center">'.dica('Ordenar pelo Status d'.$config['genero_mensagem'].'s '.ucfirst($config['mensagens']),'Clique para ordenar pelo status d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'status\');">'.($campo_ordenar=='status' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Status</b></a>'.dicaF().'</td>';
echo '<td>'.dica('Ordenar pelo Número','Clique para ordenar pelos números d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'msg\');">'.($campo_ordenar=='msg' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Nr</b></a>'.dicaF().'</td>';
if ($item_menu=='entrada' || $item_menu=='pendente' || $item_menu=='arquivado') echo '<td align="center">'.dica('Ordenar pela Cor d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']),'Clique para ordenar ordenar pela cor d'.$config['genero_mensagem'].' '.$config['mensagem'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'cor\');">'.($campo_ordenar=='cor' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Cor</b></a>'.dicaF().'</td>';
echo '</tr>';


$xpg_tamanhoPagina = 16;
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 
$xpg_totalregistros = ($resultados ? count($resultados) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
$tipo_linha=0;

for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$rs=$resultados[$i];
  $tipo_linha =($tipo_linha == 1 ? 0 : 1);
  if (($anexar_mensagem || $referenciar_mensagem)&& !$msg_id_original) $icone_anexar='<a href="javascript:void(0);" onclick="javascript: anexar('.$rs['msg_id'].', \'Msg '.$rs['msg_id'].($rs['referencia']? ' - '.$rs['referencia']:'').($rs['nome_usuario']? ' ('.$rs['nome_usuario'].')' : '' ).'\', '.$campo.');">'.imagem('icones/adicionar.png','Adicionar Documento', 'Clique neste ícone '.imagem('icones/adicionar.png').'para adicionar este documento.').'</a>';
	elseif (($anexar_mensagem || $referenciar_mensagem) && $msg_id_original) $icone_anexar='<a href="javascript:void(0);" onclick="javascript: env.anexar_msg.value='.$rs['msg_id'].'; env.submit();">'.imagem('icones/adicionar.png','Adicionar Documento', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar este documento n'.$config['genero_mensagem'].' '.$config['mensagem'].' '.$msg_id_original).'</a>';
	else $icone_anexar='';
  //verifica se tem anexo
  $sql->adTabela('anexos');
  $sql->adUnir('usuarios','usuarios','anexos.usuario_id=usuarios.usuario_id');
  $sql->adCampo('anexos.modelo, anexos.msg_id, anexos.nome, anexos.caminho, anexos.usuario_id, anexos.tipo_doc, anexos.doc_nr, anexos.nome_de, anexos.funcao_de, anexos.data_envio, contatos.contato_funcao');
  $sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adOnde('msg_id ='.$rs['msg_id']);
	$sql->adOrdem('anexo_id');
  $sql_resultadosc = $sql->Lista();
  $sql->limpar();
  $texto_anexo='';
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Nome</b></td><td>'.$rs['nome_usuario'].'</td></tr>';
	if ($rs['contato_funcao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Função</b></td><td>'.$rs['contato_funcao'].'</td></tr>';
	if ($rs['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.$config['organizacao'].'</b></td><td>'.$rs['cia_nome'].'</td></tr>';
	if ($rs['dept_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.$config['departamento'].'</b></td><td>'.$rs['dept_nome'].'</td></tr>';
	$dentro .= '</table>';
	$dentro .= 'Clique para ver os detalhes deste '.$config['usuario'].'.';
  $qnt_anexo=0;
  $modelo=0;
	$qnt_arquivo=0;
  foreach((array)$sql_resultadosc as $rs_anexo){
		++$qnt_anexo;
		if ($rs_anexo['modelo']) $modelo++; 
		else $qnt_arquivo++;
		if ($qnt_anexo==1) $texto_anexo='<BR><BR><b>Documentos em Anexo:</b><BR>';
		$texto_anexo.='&nbsp;&nbsp;'.$rs_anexo['nome'].' - '.($Aplic->usuario_prefs['nomefuncao'] ? ($rs_anexo['nome_de'] ? $rs_anexo['nome_de'] : $rs_anexo['nome_usuario']) : ($rs_anexo['funcao_de'] ? $rs_anexo['funcao_de'] : $rs_anexo['contato_funcao']) ).($rs_anexo['data_envio']? ' - '.retorna_data($rs_anexo['data_envio']) : '').'<br>';
		}
	echo '<tr '.($item_menu=='entrada' && $rs['status']==0 ? retornar_cores (2) : retornar_cores ($tipo_linha)).'>'; 
	if (!$anexar_mensagem && !$referenciar_mensagem) echo '<td width="20"><input type="checkbox" name="vetor_modelo_msg_usuario[]" value="'.$rs['msg_usuario_id'].'"></td>';
	$imagem=imagem('icones/msg'.($modelo ? '1' : '0').($qnt_arquivo ? '1' : '0').'000.gif');
	$rs_anexo=null;
	
	if ($rs['class_sigilosa'] <= $Aplic->usuario_acesso_email) echo '<td align="left">'.($icone_anexar ? $icone_anexar : '').$imagem.'<a href="javascript:void(0);" onclick="javascript:visualizar_msg('.$rs['msg_id'].', '.$rs['msg_usuario_id'].')">'.dica($rs['referencia'], $rs['texto'].$texto_anexo).$rs['referencia'].dicaF().'</a></td>';
	else echo '<td align="left">'.dica('Acesso Restrito', 'Classificação sigilosa superior ao seu nível de acesso').'Acesso Restrito'.dicaF().'</td>';
	
	echo '<td nowrap="nowrap">'.dica('Detalhes do '.ucfirst($config['usuario']), $dentro).'<a href="javascript:void(0);" onclick="javascript:visualizar_usuario('.$rs['de_id'].');">'.($Aplic->usuario_prefs['nomefuncao'] ? ($rs['nome_de'] ? $rs['nome_de'] : $rs['nome_usuario']) : ($rs['funcao_de'] ? $rs['funcao_de'] : $rs['contato_funcao'])).'</a>'.dicaF().'</td>';	

	
	if ($item_menu=='enviado'){
		$sql->adTabela('msg_usuario');
	  $sql->adCampo('count(msg_usuario.para_id) AS quantidade');
		$sql->adOnde('msg_usuario.status = 0 AND msg_id = '.$rs['msg_id']);
	  $quantidade = $sql->Resultado();
	  $sql->limpar();
		echo '<td nowrap="nowrap">'.$quantidade.'</td>'; 
		}
	echo '<td nowrap="nowrap" width="120">'.retorna_data($rs['datahora']).'</td>';
	if ($item_menu=='entrada'){ 
		echo '<td nowrap="nowrap">'.$tipos_status[$rs['status']].'</td>';
		$passou=1;
		}
	echo '<td nowrap="nowrap">'.$rs['msg_id'].'</td>';
	if ($item_menu=='entrada' || $item_menu=='pendente' || $item_menu=='arquivado') echo '<td width="25" style="background-color:#'.$rs['cor'].'">'.($rs['nota'] ? dica('Anotação',$rs['nota']).imagem('icones/anexar.png'): '&nbsp;').dicaF().'</td>';	
	echo '</tr>';
 	} 

if (!$xpg_totalregistros) echo '<tr><td colspan=20 style="background-color: #ffffff">Nenh'.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' foi encontrad'.$config['mensagem'].'</td></tr>';

echo '</table>';

echo '</form>';
echo estiloFundoCaixa();


if ($xpg_total_paginas > 1) mostrarBarraNav2($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'documento', 'documentos', '', 'env');


echo '</body></html>';




echo '</table></form>';	
 
?>
<script type="text/javascript">

function popCriador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Criador', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCriador&usuario_id='+document.getElementById('criador').value, window.setCriador, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCriador&usuario_id='+document.getElementById('criador').value, 'Criador','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setCriador(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('criador').value=usuario_id;		
	document.getElementById('nome_criador').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}		


function popEnviou() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Aprovador', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setEnviou&usuario_id='+document.getElementById('enviou').value, window.setEnviou, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setEnviou&usuario_id='+document.getElementById('enviou').value, 'Aprovador','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setEnviou(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('enviou').value=usuario_id;		
	document.getElementById('nome_enviou').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}


		
     

function marca_sel_todas() {
  with(document.getElementById('env')) {
	  for(i=0;i<elements.length;i++) {
			thiselm = elements[i];
			thiselm.checked = !thiselm.checked
      }
    }
  }
  


function visualizar_msg(msg_id, msg_usuario_id){
	url_passar(1, 'm=email&a=<?php echo $Aplic->usuario_prefs["modelo_msg"];?>&msg_id='+msg_id+'&msg_usuario_id='+msg_usuario_id+'&dialogo=1');
	}		

function visualizar_usuario(usuario_id){
	env.m.value='admin';
	env.a.value='ver_usuario';
	env.tab.value=3;
	env.usuario_id.value=usuario_id;
	env.retornar.value="mensagem_pesquisar";
	env.submit();	
	} 

	
function ordenar(pesquisa){
	env.campo_ordenar.value=pesquisa;	 
	env.a.value="mensagem_pesquisar";
	env.submit();
	}  	
	
function anexar(msg_id, texto, campo){
	<?php 
	if ($Aplic->profissional) echo ($referenciar_mensagem ? 'parent.gpwebApp._popupCallback(msg_id, texto, campo);' : 'parent.gpwebApp._popupCallback(msg_id, texto, campo);'); 
	else echo ($referenciar_mensagem ? 'window.opener.anexar_mensagem_referencia(msg_id, texto, campo);' : 'window.opener.anexar_mensagem(msg_id, texto, campo);'); 
	?>
	window.close();
	}	
	
function mostrarEsconder() {
  if (document.getElementById('pesquisa_completa').style.display != 'none') document.getElementById('pesquisa_completa').style.display = 'none';
  else document.getElementById('pesquisa_completa').style.display = '';
	}

var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "pesquisa_inicio",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pesquisa_inicio").value = Calendario.printDate(date, "%Y%m%d");
      }
  	cal1.hide(); 
  	}
  });
  
	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "pesquisa_fim",
		date : <?php echo $data_fim->format("%Y%m%d")?>,
		selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) { 
    var date = cal2.selection.get();
    if (date){
      date = Calendario.intToDate(date);
      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pesquisa_fim").value = Calendario.printDate(date, "%Y%m%d");
      }
  	cal2.hide(); 
  	}
  });	
	
function setData( frm_nome, f_data, f_data_real) {
	campo_data = eval( 'document.'+frm_nome+ '.'+f_data);
	campo_data_real = eval( 'document.'+frm_nome+'.'+f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		} 
    else{
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		} 
	else campo_data_real.value = '';
	}

function limpar_pesquisa(){
	
	env.tipo_tempo.selectedIndex=0;
	env.numero.value='';
	env.assunto.value='';
	env.data_inicio.value='';
	env.data_fim.value='';
	env.pesquisa_inicio.value='';
	env.pesquisa_fim.value='';
	env.criador.selectedIndex=0;
	env.enviou.selectedIndex=0;
	env.pesquisar_tudo.checked=false;
	}
</script>	