<?php  
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$Aplic->carregarCalendarioJS();
$tarefa_data = getParam($_REQUEST, 'tarefa_data', 0);
$data = intval($tarefa_data) ? new CData($tarefa_data) : new CData();


if (isset($_REQUEST['vetor_msg_usuario'])) $vetor_msg_usuario = getParam($_REQUEST, 'vetor_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id'])) $vetor_msg_usuario[] = getParam($_REQUEST, 'msg_usuario_id', null);
else  $vetor_msg_usuario = array();


$destino=getParam($_REQUEST, 'destino_cabecalho', getParam($_REQUEST, 'destino', ''));
$grupo_id=getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);
//tipo:  1=despacho 2=resposta 3=encaminhamento 4=anotacao
$tipo=getParam($_REQUEST, 'tipo', 0);
$status=getParam($_REQUEST, 'status', 0);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());
$outros_emails=getParam($_REQUEST, 'outros_emails','');

$msg_id_cripto=getParam($_REQUEST, 'msg_id_cripto', null);
$msg_cripto_id=getParam($_REQUEST, 'msg_cripto_id', null);

$tem_cripto=getParam($_REQUEST, 'cripto', 0);
$senha_antiga=getParam($_REQUEST, 'senha_antiga', 0);

$ListaPARAtarefa=getParam($_REQUEST, 'ListaPARAtarefa', array());
$atividade=array();
if (count($ListaPARAtarefa)){
	foreach ($ListaPARAtarefa as $chave => $valor){
		$dupla=explode(':', $valor);
		$atividade[$dupla[0]]=($dupla[1] ? $dupla[1] : null);
		}
	}

$sql = new BDConsulta;
$legendas=array('grava_encaminha'=>'Encaminhamento',' envia_msg'=>'', 'envia_email'=>'Encaminhamento por e-mail', 'envia_anot'=>'Despacho');



$msg_projeto = getParam($_REQUEST, 'msg_projeto', null);
$msg_tarefa = getParam($_REQUEST, 'msg_tarefa', null);
$msg_pratica = getParam($_REQUEST, 'msg_pratica', null);
$msg_indicador = getParam($_REQUEST, 'msg_indicador', null);
$msg_acao = getParam($_REQUEST, 'msg_acao', null);
$msg_calendario = getParam($_REQUEST, 'msg_calendario', null);
$msg_objetivo = getParam($_REQUEST, 'msg_objetivo', null);
$msg_tema = getParam($_REQUEST, 'msg_tema', null);
$msg_estrategia = getParam($_REQUEST, 'msg_estrategia', null);
$msg_perspectiva = getParam($_REQUEST, 'msg_perspectiva', null);
$msg_canvas = getParam($_REQUEST, 'msg_canvas', null);
$msg_meta = getParam($_REQUEST, 'msg_meta', null);
$msg_fator = getParam($_REQUEST, 'msg_fator', null);
$msg_monitoramento = getParam($_REQUEST, 'msg_monitoramento', null);
$msg_operativo = getParam($_REQUEST, 'msg_operativo', null);

$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;
if ($msg_projeto || $msg_tarefa || $msg_pratica || $msg_indicador || $msg_acao || $msg_calendario || $msg_objetivo || $msg_tema || $msg_tema || $msg_estrategia || $msg_meta || $msg_perspectiva || $msg_canvas || $msg_fator || $msg_monitoramento || $msg_operativo){
	$sql->adTabela('cias');
	if ($msg_projeto && !$msg_tarefa) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
	if ($msg_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
	if ($msg_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
	if ($msg_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
	if ($msg_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
	if ($msg_objetivo) $sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','pg_objetivo_estrategico_cia=cias.cia_id');
	if ($msg_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
	if ($msg_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
	if ($msg_fator) $sql->esqUnir('fatores_criticos','fatores_criticos','pg_fator_critico_cia=cias.cia_id');
	if ($msg_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
	if ($msg_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
	if ($msg_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
	if ($msg_calendario) $sql->esqUnir('calendario','calendario','unidade_id=cias.cia_id');
	if ($msg_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
	if ($msg_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
	
	if ($msg_tarefa) $sql->adOnde('tarefa_id = '.$msg_tarefa);
	elseif ($msg_projeto) $sql->adOnde('projeto_id = '.$msg_projeto);
	elseif ($msg_acao) $sql->adOnde('plano_acao_id = '.$msg_acao);
	elseif ($msg_indicador) $sql->adOnde('pratica_indicador_id = '.$msg_indicador);
	elseif ($msg_objetivo) $sql->adOnde('pg_objetivo_estrategico_id = '.$msg_objetivo);
	elseif ($msg_tema) $sql->adOnde('tema_id = '.$msg_tema);
	elseif ($msg_estrategia) $sql->adOnde('pg_estrategia_id = '.$msg_estrategia);
	elseif ($msg_pratica) $sql->adOnde('pratica_id = '.$msg_pratica);
	elseif ($msg_fator) $sql->adOnde('pg_fator_critico_id = '.$msg_fator);
	elseif ($msg_perspectiva) $sql->adOnde('pg_perspectiva_id = '.$msg_perspectiva);
	elseif ($msg_canvas) $sql->adOnde('canvas_id = '.$msg_canvas);
	elseif ($msg_meta) $sql->adOnde('pg_meta_id = '.$msg_meta);
	elseif ($msg_calendario) $sql->adOnde('calendario_id = '.$msg_calendario);
	elseif ($msg_monitoramento) $sql->adOnde('monitoramento_id = '.$msg_monitoramento);
	elseif ($msg_operativo) $sql->adOnde('operativo_id = '.$msg_operativo);
	$sql->adCampo('cia_id');
	$cia_id = $sql->Resultado();
	$sql->limpar();
	}


if (!$grupo_id && !$grupo_id2) {
	$grupo_id=$Aplic->usuario_prefs['grupoid'];
	$grupo_id2=$Aplic->usuario_prefs['grupoid2'];
	}

//tipo:  1=despacho 2=resposta 3=encaminhamento 4=anotacao

if ($tipo==1) $ttl='Despacho';
elseif ($tipo==2) $ttl='Resposta';
elseif ($tipo==3) $ttl='Encaminhamento';
else $ttl='Anotacao';

$botoesTitulo = new CBlocoTitulo($ttl, 'email1.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="a" name="a" value="'.$destino.'">';
echo '<input type=hidden id="m" name="m" value="email">';	
echo '<input type=hidden id="destino" name="destino" value="'.$destino.'">';		
echo '<input type=hidden id="status" name="status" value="'.$status.'">';	
echo '<input type=hidden id="status_original" name="status_original" value="'.$status.'">';	
echo '<input type=hidden id="tipo" name="tipo" value="'.$tipo.'">';	
echo '<input type=hidden id="grupo_id" name="grupo_id" value="">';		
echo '<input type=hidden id="grupo_id2" name="grupo_id2" value="">';		
echo '<input type=hidden id="arquivar" name="arquivar" value="">';		
echo '<input type=hidden id="msg_id_cripto" name="msg_id_cripto" value="'.$msg_id_cripto.'">';
echo '<input type=hidden id="msg_cripto_id" name="msg_cripto_id" value="'.$msg_cripto_id.'">';
echo '<input type=hidden name="senha_antiga" id="senha_antiga" value="'.$senha_antiga.'">';
echo '<input type=hidden id="cia_id" name="cia_id" value="'.$cia_id.'">';

foreach ($vetor_msg_usuario as $chave => $valor) echo '<input type=hidden name="vetor_msg_usuario[]" id="vetor_msg_usuario" value="'.$valor.'">'; 


$sql->adTabela('grupo');
$sql->esqUnir('grupo_permissao','gp1','gp1.grupo_id = grupo.grupo_id');
$sql->esqUnir('grupo_permissao','gp2','gp2.grupo_id=grupo.grupo_id AND gp2.usuario_id = '.$Aplic->usuario_id);
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia');
$sql->adCampo('COUNT(gp1.usuario_id) AS protegido');
$sql->adCampo('COUNT(gp2.usuario_id) AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
$sql->adOrdem('grupo_descricao ASC');
$sql->adGrupo('grupo.grupo_id, grupo_descricao, grupo_cia');
$achados=$sql->Lista();
$sql->limpar();


$grupos=array();
$grupos[0]='';
$tem_protegido=0;
foreach($achados as $linha) {
	if ($linha['protegido']) $tem_protegido=1;
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence'])) $grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se há grupo privado da cia, se houver não haverá opção de ver todos o usuários da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) $grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;
if (!$grupo_id && !$grupo_id2 && !$tem_protegido) {
	$grupo_id=-1;
	}

echo estiloTopoCaixa();
echo '<table align="center" class="std" width="100%" cellpadding=0 cellspacing=0>';
echo '<tr height="20" class="std" align="center"><td colspan=2><br><b>Selecione o(s) destinatário(s)<b></td></tr>';

if ($tem_cripto){
	echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0><tr>';
	echo '<td>'.dica('Criptografia'.$tem_cripto,'<ul><li><b>Chaves Públicas</b> - é a mais segura, pois somente o destinatário com a chave particular poderão visualizar '.$config['genero_mensagem'].' '.$config['mensagem'].', entretanto caso o usuário não tenha uma chave particular não poderá ler '.$config['genero_mensagem'].' '.$config['mensagem'].'.<br>Os '.$config['usuarios'].' com pares de chaves pública/privada serão apresentados na cor azul.</li><li><b>Senha</b> - é menos segura, pois uma unica senha é utilizada para criptografar e decriptografar '.$config['genero_mensagem'].' '.$config['mensagem'].', entretanto tem a vantagem que não necessita que os destinatários tenham pares de chaves pública/privada.</li></ul>').'Criptografia:'.dicaF().'</td>';
	echo '<td><input type="radio" class="std2" '.(!$Aplic->chave_privada ? 'disabled = "true"' : '').' name="tipo_cripto" value="1"'.($tem_cripto == '1' ? ' checked="checked"' : '').' onclick="env.senha.type=\'hidden\'" />'.(!$Aplic->chave_privada ? dica('Desabilitado','Carregue a sua chave privada para poder utilizar este método criptográfico.').'Chaves públicas'.dicaF() : 'Chaves públicas').'</td>';
	echo '<td><input type="radio" class="std2" onclick="env.senha.type=\'password\'" name="tipo_cripto" value="2"'.($tem_cripto == '2' ? ' checked="checked"' : '').' />Senha</td>';
	echo '<td><input type="'.($tem_cripto=='2'? 'password' : 'hidden').'" class="texto" id="senha" name="senha" value=""></td>';	
	echo '</tr></table></td></tr>';
	}
echo '<tr><td style="text-align:center" width="50%"><table width="100%" cellpadding=0 cellspacing=0>';

echo '<tr><td align=right>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" class="texto" style="width:246px;" name="busca" id="busca" onchange="env.grupo_a.value=0; env.grupo_b.value=0; mudar_usuario_pesquisa();" value=""/></td><td><a href="javascript:void(0);" onclick="env.busca.value=\'\';">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></td><tr>';
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) echo '<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td><div id="combo_cia_designados">'.selecionar_om($Aplic->usuario_cia, 'cia_designados', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om_designados();"','',1).'</div></td><td><a href="javascript:void(0);" onclick="mudar_usuarios_designados()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';

echo '<tr><td align=right>'.dica('Grupo','Clique uma vez para abrir a caixa de seleção e depois escolha um dos grupos abaixo, para selecionar os destinatário.<BR><BR>Este grupos são criados pelo administrador do Sistema.<BR><BR>Para criar grupos particulares utilize o botão GRUPOS.').'Grupo:'.dicaF().'</td><td align=left>'.selecionaVetor($grupos, 'grupo_a', 'size="1" style="width:250px" class="texto" onchange="env.grupo_b.value=0; env.busca.value=\'\'; mudar_grupo_id(\'grupo_a\');"',$grupo_id).'</td></tr>';

$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'') +$grupos;
echo '<tr><td align=right>'.dica('Selecionar Grupo Particular','Escolha '.$config['usuarios'].' incluídos em um dos seus grupos particulares.<BR><BR>Este grupos são criados por ti utilizando o botão <b>Grupos</b>.').'Particular:'.dicaF().'</td><td align=left>'.selecionaVetor($grupos, 'grupo_b', 'style="width:250px" size="1" class="texto" onchange="env.grupo_a.value=0; env.busca.value=\'\'; mudar_grupo_id(\'grupo_b\');"',$grupo_id2).'</td></tr>';

echo '</table></td>';
echo '<td width="50%"><table cellpadding=0 cellspacing=0>';


$titulo='';

if ($destino=='grava_encaminha') echo '<tr><td>&nbsp;</td><td>'.dica('Encaminhar','Clique neste botão para encaminhar.').'<a class="botao" href="javascript:btRemeter()"><span><b>encaminhar</b></span></a>'.dicaF().'&nbsp;</td><td>'.dica('Encaminhar e Arquivar','Clique nesta opção para encaminhar e arquivar.<BR><BR>'.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' será enviad'.$config['genero_mensagem'].' para a caixa d'.$config['genero_mensagem'].'s arquivad'.$config['genero_mensagem'].'s').'<a class="botao" href="javascript:btRemeter_arquivar()"><span><b>enc.&nbsp;e&nbsp;arquivar</b></span></a>'.dicaF().'&nbsp;</td><td>'.dica('Encaminhar e Pender','Clique nesta opção para encaminhar e pender.<BR><BR>'.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' será enviad'.$config['genero_mensagem'].' para a caixa d'.$config['genero_mensagem'].'s pendentes').'<a class="botao" href="javascript:btRemeter_pender()"><span><b>enc.&nbsp;e&nbsp;pender</b></span></a>'.dicaF().'</td></tr>';				
else if ($destino=='envia_msg')	echo '<tr><td width=305>&nbsp;</td><td>'.dica('Avançar','Clique neste botão para escrever '.$config['genero_mensagem'].' '.$config['mensagem'].'.').'<a class="botao" href="javascript:btRemeter()"><span><b>avançar</b></span></a>'.dicaF().'</td></tr>';
else echo '<tr><td width=295>&nbsp;</td><td>'.dica('Escrever Despacho','Clique nesta botão para escrever o despacho.').'<a class="botao" href="javascript:btRemeter()"><span><b>despacho</b></span></a></td></tr>';

echo '</table></td></tr>';
echo '<tr><td style="text-align:center" width="50%">';
echo '<fieldset><legend class=texto style="color: black;">'.dica('Seleção de '.ucfirst($config['usuarios']),'Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para adiciona-lo à lista de destinatário.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão INCLUIR.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;<b>'.ucfirst($config['usuarios']).'</b>&nbsp</legend>';
echo '<div id="combo_de">';


if ($grupo_id==-1) echo mudar_usuario_em_dept(false, $cia_id, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
else {
	echo '<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
	if ($grupo_id || $grupo_id2){
		$sql->adTabela('usuarios');
		$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->esqUnir('chaves_publicas','chaves_publicas','chave_publica_usuario=usuarios.usuario_id');
		$sql->adCampo('DISTINCT usuarios.usuario_id, usuario_grupo_dept, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_posto_valor, cia_nome, contato_nomeguerra');
		$sql->adCampo('chave_publica_id');
		$sql->adOnde('usuario_ativo=1');	
		if ($grupo_id2) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id2);
		elseif ($grupo_id > 0) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id);
		elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
		$sql->adOrdem('chave_publica_data DESC');
	 	$usuarios = $sql->ListaChave('usuario_id');
		$sql->limpar();
   	foreach ($usuarios as $rs) echo '<option value="'.$rs['usuario_id'].'" style="color: '.($rs['chave_publica_id']? 'blue': 'black').';">'.($rs['usuario_grupo_dept'] ? $rs['contato_nomeguerra'] : nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '')).'</option>';
    } 
	echo '</select>';
	}
	
echo '</div></fieldset>';
echo '</td>';

echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Destinatários','Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para remove-lo dos destinatários.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Destinatários</b>&nbsp;</legend><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;">';
foreach($ListaPARA as $chave => $valor){ 	
	echo "<option value=".$valor.">";
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
	$sql->adCampo('usuario_grupo_dept, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuario_id, cia_nome, contato_nomeguerra');
	$sql->adOnde('usuario_id='.$valor);	
	$rs =	$sql->Linha();
	$sql->limpar();
	echo ($rs['usuario_grupo_dept'] ? $rs['contato_nomeguerra'] : nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: ''));
	if (in_array($valor, $ListaPARAoculto, true)) echo ' - oculo';
	if (in_array($valor, $ListaPARAaviso, true)) echo ' - aviso';
	if (in_array($valor, $ListaPARAexterno, true)) echo ' - externo';
	if (isset($atividade[$valor])) echo ' - atividade'.((isset($atividade[$valor]) && $atividade[$valor]) ? ' '.retorna_data($atividade[$valor], false) : '');
	echo '</option>';
	}
echo '</select></fieldset></td></tr>';
echo '<tr><td style="text-align:center">';
echo '<select name="ListaPARAoculto[]" multiple id="ListaPARAoculto" size=4 style="width:100%; display:none">';
foreach($ListaPARAoculto as $chave => $valor) echo "<option value=".$valor."></option>";
echo '</select>';

echo '<select name="ListaPARAaviso[]" multiple id="ListaPARAaviso" size=4 style="width:100%; display:none">';
foreach($ListaPARAaviso as $chave => $valor  ) echo "<option value=".$valor."></option>";
echo '</select>';

echo '<select name="ListaPARAexterno[]" multiple id="ListaPARAexterno" size=4 style="width:100%; display:none">';
foreach($ListaPARAexterno as $chave => $valor) echo "<option value=".$valor."></option>";
echo '</select>';

echo '<select name="ListaPARAtarefa[]" multiple id="ListaPARAtarefa" size=4 style="width:100%; display:none">';
foreach($ListaPARAtarefa as $chave => $valor) echo "<option value=".$valor."></option>";
echo '</select>';

echo '</td></tr>';

echo '<tr><td class=CampoJanela style="text-align:center"><table cellpadding=0 cellspacing=0><tr><td width="150">'.dica('Incluir','Clique neste botão para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de destinatários.').'<a class="botao" href="javascript:Mover(env.ListaDE, env.ListaPARA)"><span><b>incluir >></b></span></a></td><td>'.dica('Incluir Todos','Clique neste botão para incluir todos '.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a class="botao" href="javascript:btSelecionarTodos_onclick()"><span><b>incluir todos</b></span></a>'.dicaF().'</td></tr></table></td><td style="text-align:center"><table cellpadding=0 cellspacing=0 width="100%"><tr><td>'.dica("Remover","Clique neste botão para remover os destinatários selecionados da caixa de destinatários.").'<a class="botao" href="javascript:Mover2(env.ListaPARA, env.ListaDE)"><span><b><< remover</b></span></a></td><td align=right width=50>'.dica("Voltar","Clique neste botão para voltar à tela principal.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.a.value=\'lista_msg\'; env.submit();"><span><b>voltar</b></span></a></td></tr></table></td></tr>';

echo '<tr><td align=left nowrap="nowrap" colspan=20><table cellpadding=0 cellspacing=0><tr><td>'.dica('Aviso de Leitura','Selecione esta caixa caso deseje receber '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' de notificação assim que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados lerem '.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Aviso de leitura'.dicaF().'</td><td>';
echo  '<td><input type="checkbox" name="aviso" id="aviso" value="1"></td>';
echo ($Aplic->usuario_pode_oculta ? '<td>'.dica('Destinatário Oculto','Selecione esta caixa caso deseje que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados não apareçam na lista de encaminhados dos outros destinatários d'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Oculto'.dicaF().'</td><td><input type="checkbox" name="oculto" id="oculto" value="1" ></td>' : '<input type="checkbox" name="oculto" id="oculto" value="0" style="display:none">');
echo ($config['email_ativo'] ? '<td>'.dica('E-Mail Externo','Selecione esta caixa caso deseje que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados que tenham E-mails externos cadastrados recebam uma cópia d'.$config['genero_mensagem'].' '.$config['mensagem'].' em suas contas de E-mail.').'E-mail'.dicaF().'</td><td><input type="checkbox" name="externo" id="externo" value="1"></td>' : '<input type="hidden" name="externo" id="externo" value="0">');
echo '<td>'.dica('Atividade','Selecione esta caixa caso deseje que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados recebam '.$config['genero_mensagem'].' '.$config['mensagem'].' como uma atividade, que deverão indicar o progresso da mesma entre 0% - 100%.').'Atividade'.dicaF().'</td><td><input type="checkbox" name="atividade" id="atividade" value="1"></td>';
echo '<td>'.dica('Prazo para a Atividade','Marque esta caixa caso deseja impor um prazo limite para que os desinatários executem a atividade relacionada '.($config['genero_mensagem']=='a' ? 'a': 'ao').' '.$config['mensagem'].'.').'Prazo'.dicaF().'</td><td><input type="checkbox" name="prazo_responder" id="prazo_responder" size=50 value=1 onchange="javascript:if (env.prazo_responder.checked) {env.atividade.checked=true; document.getElementById(\'ver_data\').style.display = \'\';} else document.getElementById(\'ver_data\').style.display = \'none\';"><span id="ver_data" style="display:none"><input type="hidden" name="tarefa_data" id="tarefa_data" value="'.($data ? $data->format(FMT_DATA_MYSQL) : '').'" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'env\', \'data\', \'tarefa_data\');" value="'.($data ? $data->format($df) : '').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar um prazo limite para que os desinatários executem a tarefa relacionada '.($config['genero_mensagem']=='a' ? 'a': 'ao').' '.$config['mensagem'].'.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</span></td>';
echo '</tr></table></td></tr>';

if ($config['email_ativo']) echo '<tr><td align=left nowrap="nowrap" colspan=20><table cellpadding=0 cellspacing=0><tr><td>'.dica('E-mail de Outros Destinatários', 'Insira os e-mails de outros destinatários que não constam nas listas dos grupos acima.<br>Separe os e-mails por ponto-vírgula<br>ex: reinert@hotmail.com;sergio@oi.com.br').'Outros destinatários:'.dicaF().'</td><td><input type="text" name="outros_emails" value="" size="90" maxlength="255" class="texto" /></td></tr></table></td></tr>';
else echo '<input type=hidden id="outros_emails" name="outros_emails" value="">';

echo '<tr><td colspan=20>&nbsp;</td>';
if (count($vetor_msg_usuario)) echo '<tr><td colspan=2 align="center">'.(isset($legendas[$destino]) ? $legendas[$destino].' para ' : '').relacao_mensagens().'</td></tr><tr><td colspan=2 align="center">&nbsp</td></tr>';
echo '</table></td></tr></table>';
echo estiloFundoCaixa();
echo '</form></body></html>';
?>
<script LANGUAGE="javascript">
	

function mudar_om_designados(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om_designados();"','',1); 	
	}
	
	
function mudar_usuarios_designados(){	
	xajax_mudar_usuario_ajax(document.getElementById('cia_designados').value, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"'); 	
	}	


function mudar_grupo_id(grupo) {
	if (document.getElementById(grupo).value!=-1) xajax_mudar_usuario_grupo_ajax(document.getElementById(grupo).value);
	else mudar_usuarios_designados();
	}

function mudar_usuario_pesquisa() {
	xajax_mudar_usuario_pesquisa_ajax(document.getElementById('busca').value);
	}
		
var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "tarefa_data",
	date :  <?php echo $data->format("%Y%m%d")?>,
	selection: <?php echo $data->format("%Y%m%d")?>,
  onSelect: function(cal1) { 
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("data").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("tarefa_data").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal1.hide(); 
	}
});	
			
	
function setData(frm_nome, f_data, f_data_real) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
        alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
        campo_data_real.value = '';
        campo_data.style.backgroundColor = 'red';
      	} 
    else {
      	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
      	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
        campo_data.style.backgroundColor = '';
				}
		} 
	else campo_data_real.value = '';
	}	
	
function Mover(ListaDE,ListaPARA) {
	//checar se já existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value > 0) {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text.replace(/(^[\s]+|[\s]+$)/g, '');
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) {
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				if (env.oculto.checked) {
					var no2 = new Option();
					no2.value = ListaDE.options[i].value;
					no2.text = ListaDE.options[i].text;
					env.ListaPARAoculto.options[env.ListaPARAoculto.options.length] = no2;
					no.text = no.text+' - oculto';
					}
				if (env.aviso.checked) {
					var no3 = new Option();
					no3.value = ListaDE.options[i].value;
					no3.text = ListaDE.options[i].text;
					env.ListaPARAaviso.options[env.ListaPARAaviso.options.length] = no3;
					no.text = no.text+' - aviso';
					}
				if (env.externo.checked) {
					var no4 = new Option();
					no4.value = ListaDE.options[i].value;
					no4.text = ListaDE.options[i].text;
					env.ListaPARAexterno.options[env.ListaPARAexterno.options.length] = no4;
					no.text = no.text+' - externo';
					}
				if (env.atividade.checked) {
					var no5 = new Option();
					no5.value = ListaDE.options[i].value+':'+(env.prazo_responder.checked ? env.tarefa_data.value : '');
					no5.text = ListaDE.options[i].text;
					env.ListaPARAtarefa.options[env.ListaPARAtarefa.options.length] = no5;
					no.text = no.text+' - atividade'+(env.prazo_responder.checked ? ' '+env.data.value : '');
					}
				ListaPARA.options[ListaPARA.options.length] = no;
				}
			}
		}
	}

function Mover2(ListaPARA,ListaDE) {
	var oculto;
	var aviso;
	var externo;
	var tarefa=0;

	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value > 0) {

			oculto=0;
			aviso=0;
			externo=0;
			tarefa=0;

			for(var j=0; j < env.ListaPARAoculto.options.length; j++){
				if (env.ListaPARAoculto.options[j].value == env.ListaPARA.options[i].value) {
					oculto=1;
					break;
					}
				}
			for(var k=0; k < env.ListaPARAaviso.options.length; k++){
				if (env.ListaPARAaviso.options[k].value == env.ListaPARA.options[i].value) {
					aviso=1;
					break;
					}
				}
			for(var e=0; e < env.ListaPARAexterno.options.length; e++){
				if (env.ListaPARAexterno.options[e].value == env.ListaPARA.options[i].value) {
					externo=1;
					break;
					}
				}
			for(var d=0; d < env.ListaPARAtarefa.options.length; d++){
				if (env.ListaPARAtarefa.options[d].value == env.ListaPARA.options[i].value) {
					tarefa=1;
					break;
					}
				}

			
			if (oculto==1){
					env.ListaPARAoculto.options[j].value = "";
					env.ListaPARAoculto.options[j].text = "";
					}
			if (aviso==1){
				env.ListaPARAaviso.options[k].value = "";
				env.ListaPARAaviso.options[k].text = "";
				}
			if (externo==1){
				env.ListaPARAexterno.options[e].value = "";
				env.ListaPARAexterno.options[e].text = "";
				}
			if (tarefa==1){
				env.ListaPARAtarefa.options[d].value = "";
				env.ListaPARAtarefa.options[d].text = "";
				}	
			
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	LimpaVazios(env.ListaPARAoculto, env.ListaPARAoculto.options.length);
  LimpaVazios(env.ListaPARAaviso, env.ListaPARAaviso.options.length);
  LimpaVazios(env.ListaPARAexterno, env.ListaPARAexterno.options.length);
  LimpaVazios(env.ListaPARAtarefa, env.ListaPARAtarefa.options.length);
  
	}

// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}

// Seleciona todos os campos da lista de destinatários e efetua o submit
function selecionar() {
	
	<?php if ($tem_cripto) echo 'if(env.tipo_cripto[1].checked && env.tipo_cripto[1].value==2 && env.senha.value.length==0) {alert("Insira uma senha!");return 0; }'; ?>	

	if (env.ListaPARA.length== 0 && env.outros_emails.value.length==0) {
		alert("Selecione ao menos um destinatário!");
		return 0;
		}

	for (var i=0; i < env.ListaPARA.length ; i++) {
		env.ListaPARA.options[i].selected = true;
		}
	for (var i=0; i < env.ListaPARAoculto.length ; i++) {
		env.ListaPARAoculto.options[i].selected = true;
	  }
	for (var i=0; i < env.ListaPARAaviso.length ; i++) {
		env.ListaPARAaviso.options[i].selected = true;
		}
	for (var i=0; i < env.ListaPARAexterno.length ; i++) {
		env.ListaPARAexterno.options[i].selected = true;
		}
	for (var i=0; i < env.ListaPARAtarefa.length ; i++) {
		env.ListaPARAtarefa.options[i].selected = true;
		}
	if (env.prazo_responder.checked==false) env.tarefa_data.value='';

	return 1;
	}



function btRemeter() {
	if (selecionar()) env.submit();
	}

function btRemeter_arquivar() {
	env.status.value=4;
	env.arquivar.value=1;
	if (selecionar()) env.submit();
	}

function btRemeter_pender() {
	env.status.value=3;
	env.arquivar.value=2;
	if (selecionar()) env.submit();
	}
	

	
	


// Seleciona todos os campos da lista de usuários
function btSelecionarTodos_onclick() {
	for (var i=0; i < env.ListaDE.length ; i++) {
		env.ListaDE.options[i].selected = true;
	}
	Mover(env.ListaDE, env.ListaPARA);
}

function campos(){
	for (var i=0; i < env.ListaPARA.length ; i++) env.ListaPARA.options[i].selected = true;
	for (var i=0; i < env.ListaPARAoculto.length ; i++) env.ListaPARAoculto.options[i].selected = true;
	for (var i=0; i < env.ListaPARAaviso.length ; i++) env.ListaPARAaviso.options[i].selected = true;
	for (var i=0; i < env.ListaPARAexterno.length ; i++) env.ListaPARAexterno.options[i].selected = true;
	for (var i=0; i < env.ListaPARAtarefa.length ; i++) env.ListaPARAtarefa.options[i].selected = true;
	env.a.value = "editar_grupos";	
	env.submit();
}






function esconder_tipof(){
	limpar_tudo();
	document.getElementById('projeto').style.display='none';
	document.getElementById('tarefaf').style.display='none';
	document.getElementById('pratica').style.display='none';
	document.getElementById('acao').style.display='none';
	document.getElementById('indicador').style.display='none';
	document.getElementById('objetivo').style.display='none';
	document.getElementById('estrategia').style.display='none';
	document.getElementById('fator').style.display='none';
	document.getElementById('meta').style.display='none';
	document.getElementById('perspectiva').style.display='none';
	document.getElementById('canvas').style.display='none';
	document.getElementById('tema').style.display='none';
	document.getElementById('monitoramento').style.display='none';
	document.getElementById('operativo').style.display='none';
	}
	
function mostrar(){
	esconder_tipof();
	if (document.getElementById('tipof').value){
		document.getElementById(document.getElementById('tipof').value).style.display='';
		if (document.getElementById('tipof').value=='projeto') document.getElementById('tarefaf').style.display='';
		}
	

	}

function limpar_tudo(){
	if (document.getElementById('tipof').value!='projeto'){ 
		document.env.msg_projeto.value = null;
		document.env.projeto_nome.value = '';
		}
	document.env.msg_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.msg_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.msg_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.msg_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.msg_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.msg_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.msg_estrategia.value = null;
	document.env.estrategia_nome.value = '';	
	document.env.msg_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.msg_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.msg_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.msg_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.msg_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.msg_operativo.value = null;
	document.env.operativo_nome.value = '';
	}

function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Operativo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, 'Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Monitoramento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, 'Monitoramento','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, 'Prática','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Indicador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, 'Indicadores','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}
	
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, 'Iniciativas','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('cia_id').value, 'Objetivos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}		
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}		
	

	
function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function popTarefa() {
	var f = document.env;
	if (f.msg_projeto.value == null) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.msg_projeto.value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.msg_projeto.value, 'tarefa','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}


function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('cia_id').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}		
<?php if($Aplic->profissional) {?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	
<?php } ?>
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	
	
function setOperativo(chave, valor){
	limpar_tudo();
	document.env.msg_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.msg_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	}	

function setPratica(chave, valor){
	limpar_tudo();
	document.env.msg_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.msg_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	
	}


function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.msg_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	
	}


function setIndicador(chave, valor){
	limpar_tudo();
	document.env.msg_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	

	}


function setAcao(chave, valor){
	limpar_tudo();
	document.env.msg_acao.value = chave;
	document.env.acao_nome.value = valor;
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.msg_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	}


function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.msg_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.msg_tema.value = chave;
	document.env.tema_nome.value = valor;
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.msg_fator.value = chave;
	document.env.fator_nome.value = valor;
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.msg_meta.value = chave;
	document.env.meta_nome.value = valor;
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.msg_perspectiva.value = chave;
	document.env.meta_perspectiva.value = valor;
	}


function setCanvas(chave, valor){
	limpar_tudo();
	document.env.msg_canvas.value = chave;
	document.env.meta_canvas.value = valor;
	}
</script>