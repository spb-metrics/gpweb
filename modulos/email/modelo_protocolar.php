<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
global $dialogo;

$campo_ordenar=getParam($_REQUEST, 'campo_ordenar', 'data');
$Aplic->carregarCalendarioJS();
$data_inicio= getParam($_REQUEST, 'data_inicio', '');
$data_fim= getParam($_REQUEST, 'data_fim', '');
$pagina = getParam($_REQUEST, 'pagina', 1);
$sentido = getParam($_REQUEST, 'sentido', 0);
$numero=getParam($_REQUEST, 'numero', '');
$protocolo=getParam($_REQUEST, 'protocolo', '');
$assunto=getParam($_REQUEST, 'assunto', '');
$pesquisa_inicio=getParam($_REQUEST, 'pesquisa_inicio', null);
$pesquisa_fim=getParam($_REQUEST, 'pesquisa_fim', null);
$data_inicio = intval($pesquisa_inicio) ? new CData($pesquisa_inicio) : new CData();
$data_fim = intval($pesquisa_fim) ? new CData($pesquisa_fim) : new CData();
$tipo_tempo=getParam($_REQUEST, 'tipo_tempo', '');
$criador=getParam($_REQUEST, 'criador', null);
$aprovou=getParam($_REQUEST, 'aprovou', null);
$estado_documento=getParam($_REQUEST, 'estado_documento', 'nao_protocolado');
$acao_documento=getParam($_REQUEST, 'acao_documento', '');
$modelo_tipo_id=getParam($_REQUEST, 'modelo_tipo_id', '');
$tipo_documento=getParam($_REQUEST, 'tipo_documento', 0);
if($tipo_documento > 0)$modelo_tipo_id=$tipo_documento;
elseif($tipo_documento < 0)$modelo_tipo_id=0;
$caixa_para_protocolar=getParam($_REQUEST, 'caixa_para_protocolar', 0);
$caixa_protocolado=getParam($_REQUEST, 'caixa_protocolado', 0);
$retornar=getParam($_REQUEST, 'retornar', '');
$campo=getParam($_REQUEST, 'campo', 0);
$modelo_id=getParam($_REQUEST, 'modelo_id', 0);
$item_menu=getParam($_REQUEST, 'item_menu', 'a_protocolar');
$pasta=getParam($_REQUEST, 'pasta', null);
$mover=getParam($_REQUEST, 'mover', array());
$protocolar_um=getParam($_REQUEST, 'protocolar_um', 0);
$protocolar_nup=getParam($_REQUEST, 'protocolar_nup', 0);
$protocolar_sequencial=getParam($_REQUEST, 'protocolar_sequencial', 0);
$modeloID=getParam($_REQUEST, 'modeloID', array());


$sql = new BDConsulta;
if ($protocolar_um){
	foreach($modeloID as $modelo){
		$sql->adTabela('modelos');
		$sql->adAtualizar('modelo_protocolo', getParam($_REQUEST, 'numero_protocolo', ''));
		$sql->adAtualizar('modelo_protocolista', $Aplic->usuario_id);
		$sql->adAtualizar('modelo_data_protocolo', date('Y-m-d H:i:s'));
		$sql->adOnde('modelo_id ='.$modelo);
		$sql->exec();
		$sql->limpar();
		}
	}

if ($protocolar_nup){
	$cia_qnt_nup=getParam($_REQUEST, 'cia_qnt_nup', 0);
	$sql->adTabela('cias');
	$sql->adCampo('cia_nup');
	$sql->adOnde('cia_id = '.$Aplic->usuario_cia);
	$organizacao = $sql->Resultado();
	$sql->limpar();


	foreach($modeloID as $modelo){
		$cia_qnt_nup=$cia_qnt_nup+1;
		$sql->adTabela('modelos');
		$sql->adAtualizar('modelo_protocolo', inserir_NUP($cia_qnt_nup, $organizacao));
		$sql->adAtualizar('modelo_protocolista', $Aplic->usuario_id);
		$sql->adAtualizar('modelo_data_protocolo', date('Y-m-d H:i:s'));
		$sql->adOnde('modelo_id ='.$modelo);
		$sql->exec();
		$sql->limpar();
		}
		
	$sql->adTabela('cias');
	$sql->adAtualizar('cia_qnt_nup', $cia_qnt_nup);
	$sql->adOnde('cia_id = '.$Aplic->usuario_cia);
	$sql->exec();
	$sql->limpar();	
	}
	
if ($protocolar_sequencial){
	$cia_qnt_nr=getParam($_REQUEST, 'cia_qnt_nr', 0);
	$prefixo=getParam($_REQUEST, 'cia_prefixo', '');
	$sufixo=getParam($_REQUEST, 'cia_sufixo', '');
	foreach($modeloID as $modelo){
		$cia_qnt_nr=$cia_qnt_nr+1;
		$protocolo=$prefixo.$cia_qnt_nr.$sufixo;
		$sql->adTabela('modelos');
		$sql->adAtualizar('modelo_protocolo',$protocolo);
		$sql->adAtualizar('modelo_protocolista', $Aplic->usuario_id);
		$sql->adAtualizar('modelo_data_protocolo', date('Y-m-d H:i:s'));
		$sql->adOnde('modelo_id ='.$modelo);
		$sql->exec();
		$sql->limpar();
		}
		
	$sql->adTabela('cias');
	$sql->adAtualizar('cia_qnt_nr', $cia_qnt_nr);
	$sql->adOnde('cia_id = '.$Aplic->usuario_cia);
	$sql->exec();
	$sql->limpar();	
	}	
	
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
echo '<input type=hidden id="a" name="a" value="modelo_protocolar">';	
echo '<input type=hidden id="pagina" name="pagina" value="'.$pagina.'">';
echo '<input type=hidden id="sentido" name="sentido" value="'.$sentido.'">';	
echo '<input type=hidden id="campo_ordenar" name="campo_ordenar" value="'.$campo_ordenar.'">';
echo '<input type=hidden id="editar" name="editar" value="">';
echo '<input type=hidden id="item_menu" name="item_menu" value="'.$item_menu.'">';
echo '<input type=hidden id="caixa_para_protocolar" name="caixa_para_protocolar" value="">';
echo '<input type=hidden id="caixa_protocolado" name="caixa_protocolado" value="">';
echo '<input type=hidden id="dialogo" name="dialogo" value="'.$dialogo.'">';
echo '<input type=hidden id="retornar" name="retornar" value="'.$retornar.'">';
echo '<input type=hidden id="campo" name="campo" value="'.$campo.'">';
echo '<input type=hidden id="modelo_id" name="modelo_id" value="'.$modelo_id.'">';
echo '<input type=hidden id="modelo_tipo_id" name="modelo_tipo_id" value="'.$modelo_tipo_id.'">';
echo '<input type=hidden name="mover" id="mover" value="">';
echo '<input type=hidden name="pasta" id="pasta" value="'.$pasta.'">';
echo '<input type=hidden id="tipo" name="tipo" value="">';
echo '<input type=hidden name="destino" id="destino" value="">';	
echo '<input type=hidden name="arquivar" id="arquivar" value="">';
echo '<input type=hidden name="novo" id="novo" value="">';
echo '<input type=hidden name="tab" id="tab" value="">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="">';
echo '<input type=hidden name="modelo_usuario_id" id="modelo_usuario_id" value="">';
echo '<input type=hidden name="protocolar_um" id="protocolar_um" value="">';
echo '<input type=hidden name="protocolar_nup" id="protocolar_nup" value="">';
echo '<input type=hidden name="protocolar_sequencial" id="protocolar_sequencial" value="">';

echo estiloTopoCaixa(); 

$tipos_tempo=array('data_criacao'=>'data de criação', 'data_aprovado'=>'data de aprovação', 'data_protocolo'=>'data do protocolo', 'data_assinatura'=>'data da assinatura'); 
$estado_documentos=array('nao_protocolado'=>'Aguardando protocolo', 'protocolado'=>'Protocolado'); 
$sql->adTabela('modelos_tipo');
$sql->adCampo('modelo_tipo_id, modelo_tipo_nome');
$sql->adOnde('organizacao='.$config['militar']);
$modelos = $sql->listaVetorChave('modelo_tipo_id', 'modelo_tipo_nome');
$modelos = array('0'=>'')+$modelos;
$sql->limpar();
echo '<table class="std2" width="100%" align="center" cellpadding=0 cellspacing=0 >';
echo '<tr><td align="center" colspan="2" id="barra">';
require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
$ko = new CoolMenu("ko");
$ko->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
$ko->styleFolder="default";
$ko->Add("root","pesquisa","Pesquisar", "javascript: void(0);' onclick='mostrarEsconder(); return false;");

//à protocolar
$sql->adTabela('modelos');
$sql->esqUnir('modelos_dados', 'modelos_dados', 'modelo_dados_modelo=modelos.modelo_id');
$sql->esqUnir('usuarios','u', 'modelos.modelo_autoridade_aprovou=u.usuario_id');
$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
$sql->esqUnir('cias', 'cias', 'cia_id = c.contato_cia');
$sql->adCampo('count(DISTINCT modelos.modelo_id) as quantidade');
$sql->adOnde('modelo_versao_aprovada > 0');
$sql->adOnde('modelo_protocolo IS NULL OR modelo_protocolo=\'\'');
$sql->adOnde('cia_id='.(int)$Aplic->usuario_cia);
$mod = $sql->Resultado();
$sql->limpar();

$ko->Add("root","a_protocolar","à Protocolar".($mod ? ' ('.$mod.')' : ''), "javascript: void(0);' onclick='env.item_menu.value=\"a_protocolar\"; javascript:limpar_pesquisa(); env.caixa_para_protocolar.value=1; env.submit();");





$qnt_entrada=0;	
$qnt_elaboracao=0;
$qnt_protocolar=0;
$qnt_pendente=0;

$sql->adTabela('modelos');
$sql->esqUnir('modelos_tipo', 'modelos_tipo', 'modelo_tipo=modelo_tipo_id');
$sql->esqUnir('modelos_dados', 'modelos_dados', 'modelo_dados_modelo=modelos.modelo_id');
$sql->esqUnir('usuarios','u', 'modelos.modelo_autoridade_aprovou=u.usuario_id');
$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
$sql->esqUnir('cias', 'cias', 'cia_id = c.contato_cia');
$sql->adCampo('count(DISTINCT modelos.modelo_id) as quantidade');
$sql->adCampo('modelo_tipo_id, modelo_tipo_nome');
$sql->adOnde('modelo_versao_aprovada > 0');
$sql->adOnde('Modelo_Protocolo IS NULL OR modelo_protocolo=\'\'');
$sql->adOnde('cia_id='.(int)$Aplic->usuario_cia);
$sql->adGrupo('modelo_tipo');


$mod = $sql->Lista();
$sql->limpar();


foreach($mod as $rs)	{
	if ($rs['quantidade']) {
		$ko->Add("a_protocolar","a_protocolar_doc_".$rs['modelo_tipo_id'],$rs['modelo_tipo_nome'].'('.$rs['quantidade'].')',"javascript: void(0);' onclick='javascript:env.modelo_tipo_id.value=".$rs['modelo_tipo_id']."; env.item_menu.value=\"a_protocolar\"; javascript:limpar_pesquisa(); env.caixa_para_protocolar.value=1; env.submit();");
		$qnt_protocolar++;
		}
	}

	
if (!$qnt_protocolar) $ko->Add("a_protocolar","a_protocolar_doc", 'Não há documentos aguardando protocolo');



$ko->Add("root","protocolados","Protocolados", "javascript: void(0);' onclick='env.item_menu.value=\"protocolado\"; javascript:limpar_pesquisa(); env.caixa_protocolado.value=1; env.submit();");

$sql->adTabela('modelos_tipo');
$sql->adCampo('modelo_tipo_id, modelo_tipo_nome');
$sql->adOnde('organizacao='.$config['militar']);
$tipos_modelo = $sql->Lista();
$sql->limpar();

foreach($tipos_modelo as $rs) $ko->Add("protocolados","protocolado_doc_".$rs['modelo_tipo_id'],$rs['modelo_tipo_nome'], "javascript: void(0);' onclick='env.modelo_tipo_id.value=".$rs['modelo_tipo_id']."; env.item_menu.value=\"protocolado\"; javascript:limpar_pesquisa(); env.caixa_protocolado.value=1; env.submit();");




$ko->Add("root","retornar2","Retornar", "javascript: void(0);' onclick='env.item_menu.value=\"entrada\"; env.a.value=\"modelo_pesquisar\"; env.submit();");

echo $ko->Render();
echo '</td></tr>';


echo '<tr id="pesquisa_completa" style="display:none;"><td><table width="100%">';
echo '<tr><td width="50%" valign="top"><table width="100%" cellpadding=0 cellspacing=0>';
echo '<tr><td align="right" width="130">'.dica('Texto à Pesquisar','Escreva a palavra chave a ser pesquisa n'.$config['genero_mensagem'].'s '.$config['mensagens'].' do sistema.').'Texto:'.dicaF().'</td><td><input type="text" class="texto" name="assunto" id="assunto" size="60" value="'.$assunto.'"></td></tr>';
echo '<tr><td align="center" colspan="2"></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Início', 'Digite ou escolha no calendário a data de início da pesquisa.').'De:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="pesquisa_inicio" id="pesquisa_inicio" value="'.$pesquisa_inicio.'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'pesquisa_inicio\');" value="'.($pesquisa_inicio ? retorna_data($pesquisa_inicio, false): '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data de início da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="Agenda"" border=0 /></a>'.dicaF().dica('Data de Término', 'Digite ou escolha no calendário a data de término da pesquisa.').'&nbsp;&nbsp;a&nbsp;&nbsp;'.dicaF().'<input type="hidden" name="pesquisa_fim" id="pesquisa_fim" value="'.$pesquisa_fim.'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\', \'pesquisa_fim\');" value="'.($pesquisa_fim ? retorna_data($pesquisa_fim, false): '').'" class="texto" />'.dica('Data de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data limite desta psquisa.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="Agenda"" border=0 /></a>'.dicaF().'&nbsp;&nbsp;&nbsp;&nbsp;'.selecionaVetor($tipos_tempo, 'tipo_tempo', 'size="1" class="texto"', $tipo_tempo).'</td></tr>';
echo'<tr><td align="right" nowrap="nowrap">'.dica('Tipo de Documento', 'Escolha qual o modelo utilizado.').'Tipo de documento:'.dicaF().'</td><td nowrap="nowrap">'.selecionaVetor($modelos, 'tipo_documento', 'size="1" class="texto"', $tipo_documento).'</td></tr>';
echo'<tr><td align="right" nowrap="nowrap">'.dica('Estado de Documento', 'Escolha em qual estado o mesmo se encontra.').'Estado do documento:'.dicaF().'</td><td nowrap="nowrap">'.selecionaVetor($estado_documentos, 'estado_documento', 'size="1" class="texto"', $estado_documento).'</td></tr>';
echo '</table></td><td width="50%" valign="top"><table width="100%" cellpadding=0 cellspacing=0>';
echo '<tr><td align="right">'.dica('Nr Documento','Escolha o número do documento que deseja encontrar').'Nr Documento:'.dicaF().'</td><td><input type="text" class="texto" name="numero" id="numero" size="10" value="'.$numero.'"></td></tr>';
echo '<tr><td align="right">'.dica('Protocolo','Escolha o número de protocolo do documento que deseja encontrar').'Protocolo:'.dicaF().'</td><td><input type="text" class="texto" name="protocolo" id="protocolo" size="40" value="'.$protocolo.'"></td></tr>';
echo '<tr><td align="right">'.dica('Criador','Escolha os documentos que tenham sido criados pel'.$config['genero_usuario'].' '.$config['usuario'].' selecionado.').'Criador:'.dicaF().'</td><td><input type="hidden" id="criador" name="criador" value="'.$criador.'" /><input type="text" id="nome_criador" name="nome_criador" value="'.nome_om($criador,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popCriador();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';	
echo '<tr><td align="right">'.dica('Aprovou','Escolha os documentos que tenham sido aprovados pel'.$config['genero_usuario'].' '.$config['usuario'].' selecionado.').'Aprovou:'.dicaF().'</td><td><input type="hidden" id="aprovou" name="aprovou" value="'.$aprovou.'" /><input type="text" id="nome_aprovou" name="nome_aprovou" value="'.nome_om($aprovou,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popAprovou();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';	


echo '</table></td></tr>';
echo '<tr><td colspan=2 align="center"><table width="100%" cellpadding=0 cellspacing=0><tr><td width="130">&nbsp;</td><td>'.botao('pesquisar documentos', 'Pesquisar Documentos','Clique neste botão para efetuar a pesquisa nos documentos criados no '.$config['gpweb'].'.','','env.item_menu.value=\'pesquisar\'; env.submit();').'</td><td>'.($retornar ? botao('retornar', 'Retornar','Ao se pressionar este botão irá retornar a tela anterior.','','env.a.value=\''.$retornar.'\'; env.submit();') : '').'</td></tr></table></td></tr>';
echo '</table></td></tr>';
$cor_prioridade=getSisValor('cor_precedencia');
$precedencia=getSisValor('precedencia');
$class_sigilosa=getSisValor('class_sigilosa');
$tipos_status=getSisValor('status');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
//pega status do cm (ver msg de outros)
if (isset($_REQUEST['status_cabecalho'])) $status = getParam($_REQUEST, 'status_cabecalho', null);
$numero_status=getParam($_REQUEST, 'numero_status', 0);
//checar se tem pasta particular
$tem_pasta=0;
//pesquisa
$sel_usuario_de=getParam($_REQUEST, 'sel_usuario_de', 0);
$sel_usuario_para=getParam($_REQUEST, 'sel_usuario_para', 0);
$assunto=getParam($_REQUEST, 'assunto', '');
$ordem_inicio=getParam($_REQUEST, 'pesquisa_inicio', '');
$ordem_fim=getParam($_REQUEST, 'pesquisa_fim', '');
//para msg vindas do exibir msg para arquivar em pasta
$arquivar=getParam($_REQUEST, 'arquivar', 0);
if($item_menu=='a_protocolar') $titulo = 'Documentos aguardando protocolo';
elseif($item_menu=='protocolado') $titulo = 'Documentos protocolados';
else $titulo = 'Pesquisa de documentos';

if($item_menu=='a_protocolar'){
	$sql->adTabela('modelos');
  $sql->esqUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->esqUnir('modelos_dados','modelos_dados','modelo_dados_modelo = modelos.modelo_id');
	$sql->esqUnir('usuarios','u', 'modelos.modelo_autoridade_aprovou=u.usuario_id');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
  $sql->adCampo('DISTINCT modelos.modelo_id, modelo_data_aprovado, modelos.class_sigilosa, modelo_tipo_nome, modelo_assunto, modelo_assunto');
	$sql->adCampo('concatenar_tres(c.contato_posto, \' \', c.contato_nomeguerra) AS nome_usuario, modelo_aprovou_nome, modelo_aprovou_funcao, modelo_autoridade_aprovou');
	$sql->adOnde('modelo_versao_aprovada > 0');
	$sql->adOnde('modelo_protocolo IS NULL OR modelo_protocolo=\'\'');
	if ($modelo_tipo_id) $sql->adOnde('modelo_tipo='.$modelo_tipo_id);
	$sql->adOnde('c.contato_cia='.(int)$Aplic->usuario_cia);
	}
	
	
if($item_menu=='protocolado'){
	$sql->adTabela('modelos');
  $sql->esqUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->esqUnir('modelos_dados','modelos_dados','modelo_dados_modelo = modelos.modelo_id');
	$sql->esqUnir('usuarios','u', 'modelos.modelo_autoridade_aprovou=u.usuario_id');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
  $sql->adCampo('DISTINCT modelos.modelo_id, modelo_data_aprovado, modelos.class_sigilosa, modelo_dados_data, modelo_tipo_nome, modelo_assunto, modelo_assunto');
	$sql->adCampo('concatenar_tres(c.contato_posto, \' \', c.contato_nomeguerra) AS nome_usuario, modelo_aprovou_nome, modelo_aprovou_funcao, modelo_autoridade_aprovou');
	$sql->adOnde('modelo_versao_aprovada > 0');
	$sql->adOnde('modelo_protocolo IS NOT NULL');
	if ($modelo_tipo_id) $sql->adOnde('modelo_tipo='.$modelo_tipo_id);
	$sql->adOnde('c.contato_cia='.(int)$Aplic->usuario_cia);
	}
	
	
if($item_menu=='pesquisar'){
	$sql->adTabela('modelos');
  $sql->esqUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->esqUnir('modelos_dados','modelos_dados','modelo_dados_modelo = modelos.modelo_id');
	$sql->esqUnir('usuarios','u', 'modelos.modelo_autoridade_aprovou=u.usuario_id');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
  $sql->adCampo('DISTINCT modelos.modelo_id, modelo_data_aprovado, modelos.class_sigilosa, modelo_tipo_nome, modelo_assunto, modelo_assunto');
	$sql->adCampo('concatenar_tres(c.contato_posto, \' \', c.contato_nomeguerra) AS nome_usuario, modelo_aprovou_nome, modelo_aprovou_funcao, modelo_autoridade_aprovou');
	$sql->adOnde('c.contato_cia='.(int)$Aplic->usuario_cia);
	if ($caixa_para_protocolar) $sql->adOnde('modelo_versao_aprovada>0 AND modelo_data_protocolo IS NULL');
	if ($caixa_protocolado)	$sql->adOnde('modelo_data_protocolo IS NOT NULL');
	if ($tipo_documento) $sql->adOnde('modelo_tipo='.$tipo_documento);
	if ($protocolo) $sql->adOnde('modelo_protocolo='.$protocolo);
	if ($aprovou) $sql->adOnde('modelo_autoridade_aprovou='.$aprovou);
	if ($numero) $sql->adOnde('modelo_id='.$numero);
	if ($estado_documento=='protocolado') $sql->adOnde('modelo_data_protocolo IS NOT NULL');
	elseif ($estado_documento=='nao_protocolado') $sql->adOnde('modelo_versao_aprovada>0 AND modelo_data_protocolo IS NULL');
	if ($assunto) $sql->adOnde('modelo_assunto LIKE \'%'.$assunto.'%\' OR modelos_dados_campos LIKE \'%'.$assunto.'%\'');
	if ($pesquisa_inicio){
		if ($tipo_tempo=='data_criacao')  $sql->adOnde('modelo_data >= \''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\'');
		elseif ($tipo_tempo=='data_aprovado')  $sql->adOnde('modelo_data_aprovado >= \''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\'');
		elseif ($tipo_tempo=='data_protocolo')  $sql->adOnde('modelo_data_protocolo >= \''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\'');
		elseif ($tipo_tempo=='data_assinatura')  $sql->adOnde('modelo_data_assinado >= \''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\'');
		}
	if ($pesquisa_fim){
		if ($tipo_tempo=='data_criacao')  $sql->adOnde('modelo_data <= \''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\'');
		elseif ($tipo_tempo=='data_aprovado')  $sql->adOnde('modelo_data_aprovado <= \''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\'');
		elseif ($tipo_tempo=='data_protocolo')  $sql->adOnde('modelo_data_protocolo <= \''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\'');
		elseif ($tipo_tempo=='data_assinatura')  $sql->adOnde('modelo_data_assinado <= \''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\'');
		}
	if ($criador) $sql->adOnde('modelo_criador_original='.$criador);	
	}
	
if ($campo_ordenar=='numero') $sql->adOrdem('modelos.modelo_id '.$ordem);
else if ($campo_ordenar=='aprovou') $sql->adOrdem("modelo_autoridade_aprovou ".$ordem.", modelos.modelo_id DESC");
else if ($campo_ordenar=='referencia')$sql->adOrdem("modelo_assunto ".$ordem.", modelos.modelo_id DESC");
else if ($campo_ordenar=='data_aprovado')$sql->adOrdem("modelos.modelo_data_aprovado ".$ordem.", modelos.modelo_id DESC");
else if ($campo_ordenar=='data_protocolo')$sql->adOrdem("modelos.modelo_data_protocolo ".$ordem.", modelos.modelo_id DESC");
else if ($campo_ordenar=='data_assinatura')$sql->adOrdem("modelos.modelo_data_assinatura ".$ordem.", modelos.modelo_id DESC");
else if ($campo_ordenar=='data_criacao')$sql->adOrdem("modelos.modelo_data ".$ordem.", modelos.modelo_id DESC");
else if ($campo_ordenar=='tipo')$sql->adOrdem("modelo_tipo_nome ".$ordem.", modelos.modelo_id DESC");
else $sql->adOrdem('modelos.modelo_id '.$ordem);	

$resultados=$sql->Lista();
$sql->limpar();

$xpg_total_paginas =0;	
$tipo_linha=0;

$xpg_tamanhoPagina = 16;
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 
$xpg_totalregistros = ($resultados ? count($resultados) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
echo '<tr><td height=30  colspan="20"><font size=2><center><b>'.$titulo.'</b></center></td></tr>';
echo '<tr><td colspan="20"><table width="100%" class="std" align="center" rules="ALL" cellpadding=0 cellspacing=0>';
echo '<tr align="center">';
if ($item_menu=='a_protocolar') echo '<td><input type="checkbox" id="sel_todas" name="sel_todas" value="1" onclick="marca_sel_todas();"></td>';
echo '<td>'.dica("Ordenar pelo Assunto","Clique para ordenar pelo assunto do documento.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.").'<a href="javascript:void(0);" onclick="javascript:ordenar(\'assunto\');">'.($campo_ordenar=='assunto' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Assunto</b>'.dicaF().'</a></td>';
echo '<td>'.dica("Ordenar pelo Tipo","Clique para ordenar pelo tipo de documento.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.").'<a href="javascript:void(0);" onclick="javascript:ordenar(\'tipo\');">'.($campo_ordenar=='tipo' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Tipo</b>'.dicaF().'</td>';
echo '<td>'.dica("Ordenar pela Data de Aprovação","Clique para ordenar pela data de aprovação do documento.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.").'<a href="javascript:void(0);" onclick="javascript:ordenar(\'data_aprovado\');">'.($campo_ordenar=='data_aprovado' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Data</b>'.dicaF().'</td>';
echo '<td nowrap="nowrap"><a href="javascript:void(0);" onclick="javascript:ordenar(\'aprovou\');">'.dica('Aprovou', 'Neste campo fica o responsável por a provar o documento.').($campo_ordenar=='aprovou' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Aprovou</b>'.dicaF().'</td>';
echo '<td>'.dica("Ordenar pelo Numero","Clique para ordenar pelo numero do documento.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.").'<a href="javascript:void(0);" onclick="javascript:ordenar(\'numero\');">'.($campo_ordenar=='numero' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Nr</b>'.dicaF().'</td>';
echo '</tr>';

$quant=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$rs=$resultados[$i];
	$tipo_linha =($tipo_linha == 1 ? 0 : 1);
	$icone_anexar='';
	echo '<tr align="center" '.retornar_cores ($tipo_linha).'>';
	if ($item_menu=='a_protocolar') echo '<td width="20"><input type="checkbox" id="modeloID" name="modeloID[]" value="'.$rs['modelo_id'].'" onclick="javascript:apenas_um();" ></td>';
	echo '<td><a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&retornar=modelo_protocolar&modelo_id='.$rs['modelo_id'].(true ? '&dialogo=1\', \'Documento\', \'left=0,top=0,height=1000,width=850,scrollbars=yes, resizable=yes\'' : '\', \'_self\'').')">'.$icone_anexar.$rs['modelo_assunto'].'</a></td>';
	echo '<td>'.$rs['modelo_tipo_nome'].'</td>';
	echo '<td width="120">'.(isset($rs['modelo_dados_data']) ? retorna_data($rs['modelo_data_aprovado']) : '&nbsp;' ).'</td>';
	echo '<td>'.($rs['modelo_autoridade_aprovou'] ? nome_funcao($rs['modelo_aprovou_nome'], null, $rs['modelo_aprovou_funcao'], null, $rs['modelo_autoridade_aprovou']): '&nbsp;' ).'</td>';
	echo '<td>'.$rs['modelo_id'].'</td>';
	echo '</tr>';
	$quant++;
	}
if (!$quant) echo '<tr '.retornar_cores ($tipo_linha).'><td colspan=20><br>Nenhum documento encontrado<br>&nbsp;</td></tr>';	

echo '</table></td></tr>';

$sql->adTabela('cias');
$sql->adCampo('cia_nup, cia_qnt_nup, cia_qnt_nr, cia_prefixo, cia_sufixo');
$sql->adOnde('cia_id = '.$Aplic->usuario_cia);
$nup = $sql->Linha();
$sql->limpar();


echo '<tr><td id="insercao_nup" style="display:none;" colspan=20><table>';
if ($nup['cia_nup']) echo '<tr><td>'.dica('Quantidade de NUP Já Inseridos','Caso selecione a inserção automática de Número Únicos de Processos (NUP), os códigos inseridos serão sequênciais e imediatamente superiores a este.').'NUP já inseridos: '.dicaF().'<input type="text" class="texto" maxlength="6" name="cia_qnt_nup" value="'.$nup['cia_qnt_nup'].'" style="width:60"></td><td>'.dica('Inserir NUP Automático','Clique nesta opção para inserir um NUP automático nos documentos selecionados.').'<a class="botao" href="javascript:void(0);" onclick="javascript:nup();"><span><b>NUP automático</b></span></a></td></tr>';
echo '<tr><td>'.dica('Quantidade de Protocolos Já Inseridos','Caso selecione a inserção automática de protocolos, os números inseridos serão sequênciais e imediatamente superiores a este.').'Protocolos já inseridos: '.dicaF().'<input type="text" class="texto" maxlength="6" name="cia_qnt_nr" value="'.$nup['cia_qnt_nr'].'" style="width:60"></td><td>'.dica('Prefixo','Preencha, caso exista, o prefixo à numeração sequencial crescente.').'Prefixo: '.dicaF().'<input type="text" class="texto" name="cia_prefixo" value="'.$nup['cia_prefixo'].'"></td><td>'.dica('Sufixo','Preencha, caso exista, o sufixo à numeração sequencial crescente.').'Sufixo: '.dicaF().'<input type="text" class="texto" name="cia_sufixo" value="'.$nup['cia_sufixo'].'"></td><td>'.dica('Número Sequencial Automático', 'Clique neste botão para inserir os protocolos em numeração sequencial crescente, podendo haver prefixo ou sufixo, caso tenham sido preenchidos à esquerda.').'<a class="botao" href="javascript:void(0);" onclick="javascript:sequencial();"><span><b>sequencial automático</b></span></a>'.dicaF().'</td></tr>';
echo '</table></td></tr>';




echo '<tr><td colspan=20><table><tr id="insercao_protocolo" style="display:none;"><td>'.dica('Protocolo','Preencha o número de protocolo do documento selecionado neste campo.').'Protocolo: '.dicaF().'<input type="text" class="texto" name="numero_protocolo"></td><td>'.dica('Inserir Protocolo', 'Clique neste botão para inserir o protocolo preenchido à esquerda no documento selecionado.').'<a class="botao" href="javascript:void(0);" onclick="javascript:protocolar();"><span><b>inserir protocolo</b></span></a>'.dicaF().'</td></tr></table></td></tr>';


echo '</table>';
echo '</form>';
echo estiloFundoCaixa();
if ($xpg_total_paginas > 1) mostrarBarraNav2($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'documento', 'documentos', '', 'env');
echo '</body></html>';
echo '</table></form>';	



 
?>
<script type="text/javascript">
	
function popCriador() {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCriador&usuario_id='+document.getElementById('criador').value, 'Criador','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setCriador(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('criador').value=usuario_id;		
		document.getElementById('nome_criador').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}		


function popAprovou() {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAprovou&usuario_id='+document.getElementById('aprovou').value, 'Aprovador','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setAprovou(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('aprovou').value=usuario_id;		
		document.getElementById('nome_aprovou').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}
	

function isInt (i) {
	return (i % 1) == 0;
	}

function apenas_um(){
	var j=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		if (document.getElementById('env').elements[i].name=='modeloID[]' && document.getElementById('env').elements[i].checked) j++;
		}	
	if (j==1) document.getElementById('insercao_protocolo').style.display = '';
	else document.getElementById('insercao_protocolo').style.display = 'none';
	if (j>0)document.getElementById('insercao_nup').style.display = '';
	else document.getElementById('insercao_nup').style.display = 'none';
	} 
	
function nup(){
	if (env.cia_qnt_nup.value.length > 0 && isInt(env.cia_qnt_nup.value)){
		env.protocolar_nup.value=1;
		env.submit();
		}
	else {
		alert('A quantidade de NUP inseridos precisa ser um número inteiro!');	
		env.cia_qnt_nup.focus();
		}
	}

function sequencial(){
	if (env.cia_qnt_nr.value.length > 0 && isInt(env.cia_qnt_nr.value)){
		env.protocolar_sequencial.value=1;
		env.submit();
		}
	else {
		alert('A quantidade de protocolos já inseridos precisa ser um número inteiro!');	
		env.protocolar_nup.focus();
		}
	}


function protocolar(){
	if (env.numero_protocolo.value.length > 0){
		env.protocolar_um.value=1;
		env.submit();
		}
	else {
		alert();
		alert('Precisa inserir um protocolo!');	
		env.numero_protocolo.focus();
		}
	}

function marca_sel_todas() {
	var j=0;
	
  with(document.getElementById('env')) {
		  for(i=0;i<elements.length;i++) {
					thiselm = elements[i];
					if (thiselm.name=='modeloID[]') thiselm.checked = !thiselm.checked;
	        }
      }
  document.getElementById('sel_todas').checked=!document.getElementById('sel_todas').checked;  
  apenas_um();    
  }
  
function visualizar_msg(modelo_id, modelo_usuario_id){
	env.modelo_id.value=modelo_id;
	env.modelo_usuario_id.value=modelo_usuario_id;
	env.retornar.value="modelo_protocolar";
	env.a.value="modelo_editar";
	env.submit();	
	}		


function verifica_selecao(){
	var j=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		if (document.getElementById('env').elements[i].checked) j++;
		}	
	if (j>0) return 1;
	else {
		alert ("Selecione ao menos um documento!"); 
		return 0;
		}
	}  







	
function ordenar(pesquisa){
	env.campo_ordenar.value=pesquisa;	 
	env.a.value="modelo_protocolar";
	env.submit();
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
	env.numero.value='';
	env.protocolo.value='';
	env.assunto.value='';
	env.data_inicio.value='';
	env.data_fim.value='';
	env.pesquisa_inicio.value='';
	env.pesquisa_fim.value='';
	env.criador.selectedIndex=0;
	env.aprovou.selectedIndex=0;
	env.estado_documento.selectedIndex=0;
	env.modelo_tipo_id.selectedIndex=0;
	}
</script>	
