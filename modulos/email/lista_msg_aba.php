<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


global $estilo_interface, $status, $swot_ativo, $operativo_ativo, $problema_ativo, $agrupamento_ativo, $patrocinador_ativo, $ata_ativo,
	$tarefa_id, 
	$projeto_id, 
	$pg_perspectiva_id, 
	$tema_id, 
	$pg_objetivo_estrategico_id, 
	$pg_fator_critico_id, 
	$pg_estrategia_id,
	$pg_meta_id, 
	$pratica_id, 
	$pratica_indicador_id, 
	$plano_acao_id, 
	$canvas_id, 
	$risco_id,
	$risco_resposta_id,
	$calendario_id, 
	$monitoramento_id, 
	$ata_id, 
	$swot_id, 
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id;

$cor_prioridade=getSisValor('cor_precedencia');
$precedencia=getSisValor('precedencia');
$class_sigilosa=getSisValor('class_sigilosa');
$tipos_status=getSisValor('status');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$usuario_id=$Aplic->usuario_id;

$coletivo=($Aplic->usuario_lista_grupo && $Aplic->usuario_lista_grupo!=$usuario_id);

$tipo=array(''=> '', '0'=>'', '1'=>'Despacho', '2'=>'Resposta', '3'=>'Encaminhamento', '4'=>'Nota');
if (isset($_REQUEST['status_cabecalho'])) $status = getParam($_REQUEST, 'status_cabecalho', null);
$numero_status=getParam($_REQUEST, 'numero_status', 0);
$pasta=getParam($_REQUEST, 'pasta', null);
$mover=getParam($_REQUEST, 'mover', array());
$campo_ordenar=getParam($_REQUEST, 'campo_ordenar', '');
$sentido=getParam($_REQUEST, 'sentido', '');
$retornar=getParam($_REQUEST, 'retornar', '');
$pagina = getParam($_REQUEST, 'pagina', 1);
$outro_usuario=0;


$sql = new BDConsulta;

//checar se tem pasta particular
$tem_pasta=0;
if (!$outro_usuario){
	//entrada
	$sql->adTabela('pasta');
	$sql->adCampo('count(pasta_id) as soma');
	$sql->adOnde('usuario_id='.$Aplic->usuario_id);
	$tem_pasta = $sql->Resultado();
	$sql->limpar();
	}

//pesquisa
$cia_usuario_enviou=getParam($_REQUEST, 'cia_usuario_enviou', 0);
$cia_usuario_recebeu=getParam($_REQUEST, 'cia_usuario_recebeu', 0);
$cia_usuario_criou=getParam($_REQUEST, 'cia_usuario_criou', 0);
$assunto=htmlentities(getParam($_REQUEST, 'assunto', ''));
$pesquisa_inicio=getParam($_REQUEST, 'pesquisa_inicio', '');
$pesquisa_fim=getParam($_REQUEST, 'pesquisa_fim', '');

//para msg vindas do exibir msg para arquivar em pasta
$arquivar=getParam($_REQUEST, 'arquivar', 0);
//muda a ordenação ao clicar nos titulos
	
if ($mover){
	$sql->adTabela('msg_usuario');
	$sql->adAtualizar('pasta_id', $pasta);
	$sql->adOnde('para_id='.$Aplic->usuario_id);
	if ($Aplic->getPref('agrupar_msg')) $sql->adOnde('msg_id IN ('.grupo_msg($mover).')');
	else $sql->adOnde('msg_usuario_id IN ('.$mover.')');
	$sql->exec();
	$sql->limpar();
	//Verificar msgs novas
	$sql->adTabela('msg_usuario');
	$sql->adCampo('datahora_leitura, de_id, msg_usuario_id, aviso_leitura');
	$sql->adOnde('para_id='.$Aplic->usuario_id);
	if ($Aplic->getPref('agrupar_msg')) $sql->adOnde('msg_id IN ('.grupo_msg($mover).')');
	else $sql->adOnde('msg_usuario_id IN ('.$mover.')');
	$sql->adOnde('status<2');
	$lista=$sql->lista();
	$sql->limpar();
	foreach($lista as $rs_leitura){
		if (!$rs_leitura['datahora_leitura']) {
			//msg não lida na caixa de entrada
			$data = date('Y-m-d H:i:s');
			if ($rs_leitura['aviso_leitura']==1) aviso_leitura($rs_leitura['de_id'], $rs_leitura['msg_usuario_id'], $data);
			$sql->adTabela('msg_usuario');
			$sql->adAtualizar('status', '4');
			$sql->adAtualizar('datahora_leitura', $data);
			$sql->adOnde('para_id='.$Aplic->usuario_id);
			$sql->adOnde('msg_usuario_id = '.$rs_leitura['msg_usuario_id']);
			$sql->adOnde('datahora_leitura IS NULL');
			$sql->exec();
			$sql->limpar();
			}
		else {
			//msg já lida ainda na caixa de entrada
			$sql->adTabela('msg_usuario');
			$sql->adAtualizar('status', '4');
			$sql->adOnde('para_id='.$Aplic->usuario_id);
			$sql->adOnde('msg_usuario_id = '.$rs_leitura['msg_usuario_id']);
			$sql->exec();
			$sql->limpar();
			}	
		$status=4;
		}
	echo '<script>url_passar(0, "m=email&a=lista_msg&'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status.'&pasta='.$pasta : '&status=1').'");</script>';	
}

//busca lista de mensagens para listar na tela
$sql->adTabela('msg_usuario');
$sql->esqUnir('msg','msg','msg.msg_id = msg_usuario.msg_id');
$sql->esqUnir('usuarios','usuarios', 'usuarios.usuario_id = msg_usuario.de_id');
$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
$sql->esqUnir('cias', 'cias', 'cias.cia_id = contatos.contato_cia');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = contatos.contato_dept');
$sql->esqUnir('anotacao', 'anotacao', 'anotacao.anotacao_id = msg_usuario.anotacao_id');
$sql->adCampo('msg_usuario.de_id, msg_usuario.msg_id, msg_usuario.msg_usuario_id, msg_usuario.datahora, msg.cripto, msg.precedencia, msg.class_sigilosa, msg.referencia, msg.texto, msg_usuario.nota, anotacao.texto AS texto_nota, msg_usuario.cor, msg_usuario.tarefa, msg_usuario.nome_de, msg_usuario.status, cias.cia_nome, depts.dept_nome, contatos.contato_funcao, msg_usuario.tipo, msg_usuario.funcao_de, concatenar_tres(contatos.contato_posto, \' \', contatos.contato_nomeguerra) AS nome_usuario, msg_usuario.para_id, msg_usuario.funcao_para');
$sql->adCampo('msg_projeto, msg_tarefa, msg_pratica, msg_acao, msg_tema, msg_objetivo, msg_fator, msg_estrategia, msg_perspectiva, msg_canvas, msg_meta, msg_indicador, msg_monitoramento, msg_operativo, msg_canvas');


if ($Aplic->profissional){	
	$sql->esqUnir('msg_gestao','msg_gestao','msg_gestao_msg = msg.msg_id');
	if ($tarefa_id) $sql->adOnde('msg_gestao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('msg_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('msg_gestao_perspectiva='.(int)$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('msg_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('msg_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('msg_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('msg_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('msg_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('msg_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('msg_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('msg_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('msg_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('msg_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('msg_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('msg_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('msg_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('msg_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('msg_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('msg_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('msg_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('msg_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('msg_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('msg_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('msg_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('msg_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('msg_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('msg_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('msg_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('msg_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('msg_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('msg_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('msg_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('msg_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('msg_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('msg_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('msg_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('msg_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('msg_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('msg_gestao_template='.(int)$template_id);	
	elseif ($painel_id) $sql->adOnde('msg_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('msg_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('msg_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('msg_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('msg_gestao_me='.(int)$me_id);
	}


//calcula o número de mensagens
$sql_total= new BDConsulta;
$sql_total->adTabela('msg_usuario');
$sql_total->esqUnir('msg','msg','msg_usuario.msg_id = msg.msg_id');
$sql_total->esqUnir('usuarios','usuarios', 'msg_usuario.de_id=usuarios.usuario_id');
$sql_total->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql_total->esqUnir('cias', 'cias', 'cia_id = contato_cia');
$sql_total->esqUnir('depts', 'depts', 'dept_id = contato_dept');
$sql_total->esqUnir('anotacao', 'anotacao', 'anotacao.anotacao_id = msg_usuario.anotacao_id');

//caixa de entrada
if ($status <= 2 ){
	$titulo = "Caixa de Entrada";
	if (empty($numero_status)) $numero_status = 1;
	$sql->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
	$sql->adOnde('msg_usuario.status <= 2');
	$sql_total->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
	$sql_total->adOnde('msg_usuario.status <= 2');
	}


if ($status == 3 ){
	$titulo = ucfirst($config['mensagens'])." Pendentes";

	$sql->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
	$sql->adOnde('msg_usuario.status = 3');
  if ($pasta > 0) $sql->adOnde('msg_usuario.pasta_id ='.$pasta);
  else if ($pasta == 0) $sql->adOnde('msg_usuario.pasta_id IS NULL OR msg_usuario.pasta_id < 1'); 
  $sql_total->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
	$sql_total->adOnde('msg_usuario.status = 3');
  if ($pasta > 0) $sql_total->adOnde('msg_usuario.pasta_id ='.$pasta);
  else if ($pasta == 0) $sql_total->adOnde('msg_usuario.pasta_id IS NULL OR msg_usuario.pasta_id < 1'); 
	}

//arquivadas
if ($status == 4 ){
	$titulo = ucfirst($config['mensagens']).' Arquivad'.$config['genero_mensagem'].'s';
	$sql->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
	$sql->adOnde('msg_usuario.status = 4');
  if ($pasta > 0) $sql->adOnde('msg_usuario.pasta_id ='.$pasta);
  else if ($pasta == 0) $sql->adOnde('msg_usuario.pasta_id IS NULL OR msg_usuario.pasta_id < 1'); 
	
	$sql_total->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
	$sql_total->adOnde('msg_usuario.status = 4');
  if ($pasta > 0) $sql_total->adOnde('msg_usuario.pasta_id ='.$pasta);
  else if ($pasta == 0) $sql_total->adOnde('msg_usuario.pasta_id IS NULL OR msg_usuario.pasta_id < 1'); 
	}

//enviadas
if ($status == 5 ){
	$titulo = ucfirst($config['mensagens']).' Enviad'.$config['genero_mensagem'].'s';
 	$sql->adOnde('msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
 	$sql_total->adOnde('msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
}
	
if ($status == 6 && ($Aplic->usuario_admin || $administrador)) { // modulo cm
	$titulo = 'Caixa de Entrada - Todos '.ucfirst($config['usuarios']).' - não lidas';
	$sql->adOnde('msg_usuario.status = 0 OR usuario_id IS NULL');
	$sql_total->adOnde('msg_usuario.status = 0 OR usuario_id IS NULL');
}

//entrada
if ($status == 7 && ($Aplic->usuario_admin || $administrador)) { // modulo cm
	$titulo = 'Caixa de Entrada - Todos '.ucfirst($config['usuarios']);
	$sql->adOnde('msg_usuario.status <= 2');
	$sql_total->adOnde('msg_usuario.status <= 2');
	}

if ($status == 10) { // pesquisa
	
  $titulo = 'Resultado da Pesquisa';
	$data_inicio=($pesquisa_inicio? new DateTime($pesquisa_inicio.' 00:00:00'): '');
	$data_fim=($pesquisa_fim? new DateTime($pesquisa_fim.' 23:59:59'): '');
	
	if ($Aplic->usuario_admin){
		if (!$cia_usuario_enviou && !$cia_usuario_recebeu && !$cia_usuario_criou) $sql->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).' OR msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
		if ($cia_usuario_enviou) $sql->adOnde('msg_usuario.de_id = '.$cia_usuario_enviou);
		if ($cia_usuario_recebeu) $sql->adOnde('msg_usuario.para_id = '.$cia_usuario_recebeu);
		if ($cia_usuario_criou) $sql->adOnde('msg.de_id = '.$cia_usuario_criou);
		}
	else{
		if (!$cia_usuario_enviou && !$cia_usuario_recebeu && !$cia_usuario_criou) $sql->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).' OR msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
		elseif ($cia_usuario_criou) $sql->adOnde('msg.de_id = '.$cia_usuario_criou.' AND (msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).' OR msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).')');
		elseif ($cia_usuario_enviou) $sql->adOnde('msg_usuario.de_id = '.$cia_usuario_enviou.' AND msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
		elseif ($cia_usuario_recebeu) $sql->adOnde('msg_usuario.para_id = '.$cia_usuario_enviou.' AND msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
		}
	if ($assunto) $sql->adOnde('UPPER(msg.texto) LIKE \'%'.strtoupper($assunto).'%\' OR UPPER(msg.referencia) LIKE \'%'.strtoupper($assunto).'%\'');
  if ($data_inicio)  $sql->adOnde('data_envio >= \''.date_format($data_inicio, 'Y-m-d H:i:s').'\'');
  if ($data_fim)  $sql->adOnde('data_envio <= \''.date_format($data_fim, 'Y-m-d H:i:s').'\'');
	if ($pasta > 0)  $sql->adOnde('msg_usuario.pasta_id = '.$pasta); 
	elseif ($pasta == 0) $sql->adOnde('msg_usuario.pasta_id IS NULL OR msg_usuario.pasta_id < 1'); 
	
	
	if ($Aplic->usuario_admin){
		if (!$cia_usuario_enviou && !$cia_usuario_recebeu && !$cia_usuario_criou) $sql_total->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).' OR msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
		if ($cia_usuario_enviou) $sql_total->adOnde('msg_usuario.de_id = '.$cia_usuario_enviou);
		if ($cia_usuario_recebeu) $sql_total->adOnde('msg_usuario.para_id = '.$cia_usuario_recebeu);
		if ($cia_usuario_criou) $sql_total->adOnde('msg.de_id = '.$cia_usuario_criou);
		}
	else{
		if (!$cia_usuario_enviou && !$cia_usuario_recebeu && !$cia_usuario_criou) $sql_total->adOnde('msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).' OR msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
		elseif ($cia_usuario_criou) $sql_total->adOnde('msg.de_id = '.$cia_usuario_criou.' AND (msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).' OR msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).')');
		elseif ($cia_usuario_enviou) $sql_total->adOnde('msg_usuario.de_id = '.$cia_usuario_enviou.' AND msg_usuario.para_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
		elseif ($cia_usuario_recebeu) $sql_total->adOnde('msg_usuario.para_id = '.$cia_usuario_enviou.' AND msg_usuario.de_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
		}
	 
	if ($assunto) $sql_total->adOnde('UPPER(msg.texto) LIKE \'%'.strtoupper($assunto).'%\' OR UPPER(msg.referencia) LIKE \'%'.strtoupper($assunto).'%\'');
  if ($data_inicio)  $sql_total->adOnde('data_envio >= \''.date_format($data_inicio, 'Y-m-d H:i:s').'\'');
  if ($data_fim)  $sql_total->adOnde('data_envio <= \''.date_format($data_fim, 'Y-m-d H:i:s').'\'');
	if ($pasta > 0)  $sql_total->adOnde('msg_usuario.pasta_id = '.$pasta); 
	elseif ($pasta == 0) $sql_total->adOnde('msg_usuario.pasta_id IS NULL OR msg_usuario.pasta_id < 1'); 
	}

if ($campo_ordenar=='msg') $sql->adOrdem('msg.msg_id '.$sentido.', msg_usuario.status ASC, msg.precedencia ASC');
else if ($campo_ordenar=='de') $sql->adOrdem("msg_usuario.funcao_de ".$sentido);	//.", msg.msg_id DESC");
else if ($campo_ordenar=='referencia')$sql->adOrdem("msg.referencia ".$sentido.", msg.msg_id DESC");
else if ($campo_ordenar=='data')$sql->adOrdem("msg_usuario.datahora ".$sentido.", msg.msg_id DESC");
else if ($campo_ordenar=='precedencia')$sql->adOrdem("msg.precedencia ".$sentido.", msg.msg_id DESC");
else if ($campo_ordenar=='sigilo')$sql->adOrdem("msg.class_sigilosa ".$sentido.", msg.msg_id DESC");
else if ($campo_ordenar=='status')$sql->adOrdem("msg_usuario.status ".$sentido.", msg.msg_id DESC");
else if ($campo_ordenar=='cor')$sql->adOrdem("msg_usuario.cor ".$sentido.", msg.msg_id DESC");
else if ($campo_ordenar=='para') $sql->adOrdem("msg_usuario.funcao_para ".$sentido);
else $sql->adOrdem("msg.msg_id DESC");
		
if ($Aplic->getPref('agrupar_msg')  || $status == 10 || $status == 5)	$sql->adGrupo('msg_usuario.msg_id');	
if ($status == 10) $sql_total->adCampo('count(DISTINCT msg.msg_id)');
else if ($status >= 4 || $Aplic->getPref('agrupar_msg')) $sql_total->adCampo('count(DISTINCT msg_usuario.msg_id)');
else $sql_total->adCampo('count(msg_usuario.msg_usuario_id)');
$xpg_tamanhoPagina=$config['qnt_emails'];
$xpg_min=$xpg_tamanhoPagina * ($pagina - 1); 
$xpg_totalregistros=$sql_total->Resultado();

$sql_total->adGrupo('msg.msg_id');
$sql_total->limpar();
$sql->setLimite($xpg_min, $xpg_tamanhoPagina);


$sql->adGrupo('msg.msg_id');

$sql_resultados=$sql->Lista();
$sql->limpar();

$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
$tipo_linha = 0;
$msg_ID = 0;
$cont=0;

echo '<input type=hidden name="a" id="a" value="'.$a.'">';
echo '<input type=hidden name="m" id="m" value="email">';
echo '<input type=hidden name="status" id="status" value="'.$status.'">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="'.$usuario_id.'">';
echo '<input type=hidden name="destino" id="destino" value="">';	
echo '<input type=hidden name="tipo" id="tipo" value="">';
echo '<input type=hidden name="mover" id="mover" value="">';
echo '<input type=hidden name="pasta" id="pasta" value="'.$pasta.'">';
echo '<input type=hidden name="sentido" id="sentido" value="'.$sentido.'">';
echo '<input type=hidden name="campo_ordenar" id="campo_ordenar" value="'.$campo_ordenar.'">';

echo '<input type=hidden name="cia_usuario_enviou" id="cia_usuario_enviou" value="'.$cia_usuario_enviou.'">';
echo '<input type=hidden name="cia_usuario_recebeu" id="cia_usuario_recebeu" value="'.$cia_usuario_recebeu.'">';
echo '<input type=hidden name="cia_usuario_criou" id="cia_usuario_criou" value="'.$cia_usuario_criou.'">';

echo '<input type=hidden name="assunto" id="assunto" value="'.$assunto.'">';
echo '<input type=hidden name="pesquisa_inicio" id="pesquisa_inicio" value="'.$pesquisa_inicio.'">';
echo '<input type=hidden name="pesquisa_fim" id="pesquisa_fim" value="'.$pesquisa_fim.'">';
echo '<input type=hidden name="tab" id="tab" value="0">';
echo '<input type=hidden name="msg_usuario_id" id="msg_usuario_id" value="">';
echo '<input type=hidden name="senha" id="senha" value="">';
echo '<input type=hidden name="modelo_id" id="modelo_id" value="">';
echo '<input type=hidden name="pagina" id="pagina" value="'.$pagina.'">';
echo '<input type=hidden id="retornar" name="retornar" value="'.$retornar.'">';



echo '<input type="hidden" name="cia_id" id="cia_id" value="'.$Aplic->usuario_cia.'" />';
echo '<input type="hidden" name="tarefa_id" id="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="pg_perspectiva_id" id="pg_perspectiva_id" value="'.$pg_perspectiva_id.'" />';
echo '<input type="hidden" name="tema_id" id="tema_id" value="'.$tema_id.'" />';
echo '<input type="hidden" name="pg_objetivo_estrategico_id" id="pg_objetivo_estrategico_id" value="'.$pg_objetivo_estrategico_id.'" />';
echo '<input type="hidden" name="pg_fator_critico_id" id="pg_fator_critico_id" value="'.$pg_fator_critico_id.'" />';
echo '<input type="hidden" name="pg_estrategia_id" id="pg_estrategia_id" value="'.$pg_estrategia_id.'" />';
echo '<input type="hidden" name="pg_meta_id" id="pg_meta_id" value="'.$pg_meta_id.'" />';
echo '<input type="hidden" name="pratica_id" id="pratica_id" value="'.$pratica_id.'" />';
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="plano_acao_id" id="plano_acao_id" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="canvas_id" id="canvas_id" value="'.$canvas_id.'" />';
echo '<input type="hidden" name="risco_id" id="risco_id" value="'.$risco_id.'" />';
echo '<input type="hidden" name="risco_resposta_id" id="risco_resposta_id" value="'.$risco_resposta_id.'" />';
echo '<input type="hidden" name="calendario_id" id="calendario_id" value="'.$calendario_id.'" />';
echo '<input type="hidden" name="monitoramento_id" id="monitoramento_id" value="'.$monitoramento_id.'" />';
echo '<input type="hidden" name="ata_id" id="ata_id" value="'.$ata_id.'" />';
echo '<input type="hidden" name="swot_id" id="swot_id" value="'.$swot_id.'" />';
echo '<input type="hidden" name="operativo_id" id="operativo_id" value="'.$operativo_id.'" />';
echo '<input type="hidden" name="instrumento_id" id="instrumento_id" value="'.$instrumento_id.'" />';
echo '<input type="hidden" name="recurso_id" id="recurso_id" value="'.$recurso_id.'" />';
echo '<input type="hidden" name="problema_id" id="problema_id" value="'.$problema_id.'" />';
echo '<input type="hidden" name="demanda_id" id="demanda_id" value="'.$demanda_id.'" />';
echo '<input type="hidden" name="programa_id" id="programa_id" value="'.$programa_id.'" />';
echo '<input type="hidden" name="licao_id" id="licao_id" value="'.$licao_id.'" />';
echo '<input type="hidden" name="evento_id" id="evento_id" value="'.$evento_id.'" />';
echo '<input type="hidden" name="link_id" id="link_id" value="'.$link_id.'" />';
echo '<input type="hidden" name="avaliacao_id" id="avaliacao_id" value="'.$avaliacao_id.'" />';
echo '<input type="hidden" name="tgn_id" id="tgn_id" value="'.$tgn_id.'" />';
echo '<input type="hidden" name="brainstorm_id" id="brainstorm_id" value="'.$brainstorm_id.'" />';
echo '<input type="hidden" name="gut_id" id="gut_id" value="'.$gut_id.'" />';
echo '<input type="hidden" name="causa_efeito_id" id="causa_efeito_id" value="'.$causa_efeito_id.'" />';
echo '<input type="hidden" name="arquivo_id" id="arquivo_id" value="'.$arquivo_id.'" />';
echo '<input type="hidden" name="forum_id" id="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="checklist_id" id="checklist_id" value="'.$checklist_id.'" />';
echo '<input type="hidden" name="agenda_id" id="agenda_id" value="'.$agenda_id.'" />';
echo '<input type="hidden" name="agrupamento_id" id="agrupamento_id" value="'.$agrupamento_id.'" />';
echo '<input type="hidden" name="patrocinador_id" id="patrocinador_id" value="'.$patrocinador_id.'" />';
echo '<input type="hidden" name="template_id" id="template_id" value="'.$template_id.'" />';
echo '<input type="hidden" name="painel_id" id="painel_id" value="'.$painel_id.'" />';
echo '<input type="hidden" name="painel_odometro_id" id="painel_odometro_id" value="'.$painel_odometro_id.'" />';
echo '<input type="hidden" name="painel_composicao_id" id="painel_composicao_id" value="'.$painel_composicao_id.'" />';
echo '<input type="hidden" name="tr_id" id="tr_id" value="'.$tr_id.'" />';
echo '<input type="hidden" name="me_id" id="me_id" value="'.$me_id.'" />';


echo estiloTopoCaixa();
if ($xpg_total_paginas > 1) mostrarBarraNav2($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'mensagem', 'mensagens', '', 'env',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" class="std" align="center" rules="ALL" cellpadding=0 cellspacing=0>';
echo '<tr><td height=30  colspan="20"><font size=2><center><b>'.$titulo.'</b></center></td></tr>';
echo '<tr align=center>';
echo '<td><input type="checkbox" name="sel_todas" value="1" onclick="marca_sel_todas();"></td>';
echo '<td>'.dica('Ordenar pelo Assunto','Clique para ordenar pelo assunto d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'referencia\');">'.($campo_ordenar=='referencia' ? imagem('icones/'.$seta[($sentido=='ASC'? 1 : 0)]) : '').'<b>Assunto</b></a>'.dicaF().'</td>';
if ($status != 5) echo '<td>'.dica('Ordenar pelo Remetente','Clique para ordenar pelos remetentes d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'de\');">'.($campo_ordenar=='de' ? imagem('icones/'.$seta[($sentido=='ASC'? 1 : 0)]) : '').'<b>De</b></a>'.dicaF().'</td>';
if ($status == 5) echo '<td>'.dica('Ordenar pelo Destinatário','Clique para ordenar pelos destinatários d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'para\');">'.($campo_ordenar=='para' ? imagem('icones/'.$seta[($sentido=='ASC'? 1 : 0)]) : '').'<b>Para</b></a>'.dicaF().'</td>'; 
if ($status == 5) echo '<td><b>Não Leram</b></td>';
echo '<td>'.dica('Ordenar pela Data de Envio','Clique para ordenar pela data de envio d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'data\');">'.($campo_ordenar=='data' ? imagem('icones/'.$seta[($sentido=='ASC'? 1 : 0)]) : '').'<b><b>Data de Envio</b></a>'.dicaF().'</td>';
if ($config['msg_precedencia']) echo '<td>'.dica('Ordenar pela Precedência','Clique para ordenar pela precedência d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'precedencia\');">'.($campo_ordenar=='precedencia' ? imagem('icones/'.$seta[($sentido=='ASC'? 1 : 0)]) : '').'<b>Precedência</b></a>'.dicaF().'</td>';
if ($config['msg_class_sigilosa']) echo '<td>'.dica('Ordenar pela Classificação Sigilosa','Clique para ordenar pela classificação sigilosa d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'sigilo\');">'.($campo_ordenar=='sigilo' ? imagem('icones/'.$seta[($sentido=='ASC'? 1 : 0)]) : '').'<b>Class Sigilosa</b></a>'.dicaF().'</td>';
if ($status == 1 || $status == 10 ) echo '<td align="center">'.dica('Ordenar pelo Status d'.$config['genero_mensagem'].'s '.ucfirst($config['mensagens']),'Clique para ordenar pelo status d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'status\');">'.($campo_ordenar=='status' ? imagem('icones/'.$seta[($sentido=='ASC'? 1 : 0)]) : '').'<b>Status</b></a>'.dicaF().'</td>';
echo '<td>'.dica('Ordenar pelo Número','Clique para ordenar pelos números d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'msg\');">'.($campo_ordenar=='msg' ? imagem('icones/'.$seta[($sentido=='ASC'? 1 : 0)]) : '').'<b>Nr</b></a>'.dicaF().'</td>';
if ($status <= 4) echo '<td align="center">'.dica('Ordenar pela Cor d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']),'Clique para ordenar ordenar pela cor d'.$config['genero_mensagem'].' '.$config['mensagem'].'.<br><br>A cada clique será alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'cor\');">'.($campo_ordenar=='cor' ? imagem('icones/'.$seta[($sentido=='ASC'? 1 : 0)]) : '').'<b>Cor</b></a>'.dicaF().'</td>';
echo '</tr>';




foreach($sql_resultados as $rs) {
	
	if ($tipo_linha == 1) $tipo_linha = 0; else $tipo_linha = 1;
  //verifica se tem anexo
  $sql->adTabela('anexos');
  $sql->esqUnir('usuarios','usuarios','anexos.usuario_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->adCampo('nome_fantasia, anexos.anexo_id, anexos.nome, anexos.caminho, anexos.usuario_id, anexos.tipo_doc, anexos.doc_nr, anexos.nome_de, anexos.funcao_de, anexos.data_envio, contatos.contato_funcao, anexos.modelo');
  $sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('msg_id ='.$rs['msg_id']);
	$sql->adOrdem('anexo_id');
  
  $sql_resultadosc = $sql->Lista();
  $sql->limpar();
  $texto_anexo='';
  if ($status != 5){
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($rs['contato_funcao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-moz-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Função</b></td><td>'.$rs['contato_funcao'].'</td></tr>';
		if ($rs['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-moz-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.$config['organizacao'].'</b></td><td>'.$rs['cia_nome'].'</td></tr>';
		if ($rs['dept_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-moz-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.$config['departamento'].'</b></td><td>'.$rs['dept_nome'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= 'Clique para ver os detalhes deste '.$config['usuario'].'.';
		}
  $pode_ler=(($rs['class_sigilosa'] <= $Aplic->usuario_acesso_email) || !$rs['class_sigilosa']); 
  $qnt_anexo=0;
  $modelo=0;
  $qnt_arquivo=0;
  foreach ($sql_resultadosc as $rs_anexo){
		++$qnt_anexo;
		if ($rs_anexo['modelo']) $modelo++; 
		else $qnt_arquivo++;
		if ($qnt_anexo==1) $texto_anexo='<BR><b>Documentos em Anexo:</b><BR>';
		$texto_anexo.='&nbsp;&nbsp;'.($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']).' - '.($Aplic->usuario_prefs['nomefuncao'] ? ($rs_anexo['nome_de'] ? $rs_anexo['nome_de'] : $rs_anexo['nome_usuario']) : ($rs_anexo['funcao_de'] ? $rs_anexo['funcao_de'] : $rs_anexo['contato_funcao']) ).($rs_anexo['data_envio']? ' - '.retorna_data($rs_anexo['data_envio']) : '').'<br>';
		}
	
	echo '<tr '.($rs['status']==0 && ($status == 1 || $status == 10) ? retornar_cores (2) : retornar_cores ($tipo_linha)).'>'; 
	echo '<td><input type="checkbox" name="vetor_msg_usuario[]" value="'.$rs['msg_usuario_id'].'"><input type=hidden name="cripto_'.$rs['msg_usuario_id'].'" id="cripto_'.$rs['msg_usuario_id'].'" value="'.$rs['cripto'].'"></td>';
	$imagem=(imagem($rs['tarefa'] ? 'icones/task_p.png' : 'icones/msg'.($modelo ? '1' : '0').($qnt_arquivo ? '1' : '0').($rs['cripto'] ? '1': '0').$rs['tipo'].($rs['tarefa'] ? '1' : '0').'.gif'));



	$icone='';	
	if ($Aplic->profissional){
		$sql->adTabela('msg_gestao');
		$sql->adCampo('msg_gestao.*');
		$sql->adOnde('msg_gestao_msg='.$rs['msg_id']);
		$lista = $sql->lista();
		$sql->limpar();
			
		foreach($lista as $linha) {	
			if ($linha['msg_gestao_tarefa']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=tarefas&a=ver&tarefa_id='.$linha['msg_gestao_tarefa'].'\');">'.imagem('icones/tarefa_p.gif',ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' para ver '.$config['genero_tarefa'].' '.$config['tarefa'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_projeto']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=ver&projeto_id='.$linha['msg_gestao_projeto'].'\');">'.imagem('icones/projeto_p.gif',ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para ver '.$config['genero_projeto'].' '.$config['projeto'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_perspectiva']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['msg_gestao_perspectiva'].'\');">'.imagem('icones/perspectiva_p.png',ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para ver'.$config['genero_perspectiva'].' '.$config['perspectiva'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_tema']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=tema_ver&tema_id='.$linha['msg_gestao_tema'].'\');">'.imagem('icones/tema_p.png',ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para ver '.$config['genero_tema'].' '.$config['tema'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_objetivo']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$linha['msg_gestao_objetivo'].'\');">'.imagem('icones/obj_estrategicos_p.gif',ucfirst($config['objetivo']),'Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para ver '.$config['genero_objetivo'].' '.$config['objetivo'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_fator']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=fator_ver&pg_fator_critico_id='.$linha['msg_gestao_fator'].'\');">'.imagem('icones/fator_p.gif',ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para ver '.$config['genero_fator'].' '.$config['fator'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_estrategia']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['msg_gestao_estrategia'].'\');">'.imagem('icones/estrategia_p.gif',ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para ver '.$config['genero_iniciativa'].' '.$config['iniciativa'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_meta']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=meta_ver&pg_meta_id='.$linha['msg_gestao_meta'].'\');">'.imagem('icones/meta_p.gif',ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para ver '.$config['genero_meta'].' '.$config['meta'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_pratica']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=pratica_ver&pratica_id='.$linha['msg_gestao_pratica'].'\');">'.imagem('icones/pratica_p.gif',ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para ver '.$config['genero_pratica'].' '.$config['pratica'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_indicador']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['msg_gestao_indicador'].'\');">'.imagem('icones/indicador_p.gif','Indicador','Clique neste ícone '.imagem('icones/indicador_p.gif').' para ver o indicador ao qual '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_acao']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['msg_gestao_acao'].'\');">'.imagem('icones/acao_p.gif',ucfirst($config['acao']),'Clique neste ícone '.imagem('icones/acao_p.gif').' para ver '.$config['genero_acao'].' '.$config['acao'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_canvas']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=canvas_pro_ver&canvas_id='.$linha['msg_gestao_canvas'].'\');">'.imagem('icones/canvas_p.png',ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para ver'.$config['genero_canvas'].' '.$config['canvas'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_risco']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=operativo&a=operativo_ver&operativo_id='.$linha['msg_gestao_risco'].'\');">'.imagem('icones/risco_p.png',ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para ver '.$config['genero_risco'].' '.$config['risco'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';	
			elseif ($linha['msg_gestao_risco_resposta']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=operativo&a=operativo_ver&operativo_id='.$linha['msg_gestao_risco_resposta'].'\');">'.imagem('icones/operativo_p.png',ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_p.png').' para ver '.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';	
			elseif ($linha['msg_gestao_calendario']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['msg_gestao_calendario'].'\');">'.imagem('icones/calendario_p.png','Agenda','Clique neste ícone '.imagem('icones/calendario_p.png').' para ver a agenda que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_monitoramento']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['msg_gestao_monitoramento'].'\');">'.imagem('icones/monitoramento_p.gif','Monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para ver o monitoramento que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_ata']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=atas&a=ata_ver&ata_id='.$linha['msg_gestao_ata'].'\');">'.imagem('../../../modulos/atas/imagens/ata_p.png','Ata de Reunião','Clique neste ícone '.imagem('../../../modulos/atas/imagens/ata_p.png').' para ver a ata de reunião que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_swot']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=swot&a=swot_ver&swot_id='.$linha['msg_gestao_swot'].'\');">'.imagem('../../../modulos/swot/imagens/swot_p.png','Matriz SWOT','Clique neste ícone '.imagem('../../../modulos/swot/imagens/swot_p.png').' para ver a matriz SWOT que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_operativo']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=operativo&a=operativo_ver&operativo_id='.$linha['msg_operativo'].'\');">'.imagem('icones/operativo_p.png','Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para ver o plano operativo que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';	
			elseif ($linha['msg_gestao_instrumento']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=recursos&a=instrumento_ver&instrumento_id='.$linha['msg_gestao_instrumento'].'\');">'.imagem('icones/instrumento_p.png',ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para ver '.$config['genero_instrumento'].' '.$config['instrumento'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';	
			elseif ($linha['msg_gestao_recurso']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=recursos&a=ver&recurso_id='.$linha['msg_gestao_recurso'].'\');">'.imagem('icones/recurso_p.png',ucfirst($config['recurso']),'Clique neste ícone '.imagem('icones/recurso_p.png').' para ver '.$config['genero_recurso'].' '.$config['recurso'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';	
			elseif ($linha['msg_gestao_problema']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=problema&a=problema_ver&problema_id='.$linha['msg_gestao_problema'].'\');">'.imagem('icones/problema_p.png',ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para ver '.$config['genero_problema'].' '.$config['problema'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';	
			elseif ($linha['msg_gestao_demanda']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=demanda_ver&demanda_id='.$linha['msg_gestao_demanda'].'\');">'.imagem('icones/demanda_p.gif','Demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para ver a demanda que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_programa']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=programa_pro_ver&programa_id='.$linha['msg_gestao_programa'].'\');">'.imagem('icones/programa_p.png',ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para ver '.$config['genero_programa'].' '.$config['programa'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';	
			elseif ($linha['msg_gestao_evento']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=calendario&a=ver&evento_id='.$linha['msg_gestao_evento'].'\');">'.imagem('icones/calendario_p.png','Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para ver o evento que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_link']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=links&a=ver&link_id='.$linha['msg_gestao_link'].'\');">'.imagem('icones/links_p.gif','Link','Clique neste ícone '.imagem('icones/links_p.gif').' para ver o link que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_avaliacao']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['msg_gestao_avaliacao'].'\');">'.imagem('icones/avaliacao_p.gif','Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para ver a avaliação que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_tgn']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=tgn_pro_ver&tgn_id='.$linha['msg_gestao_tgn'].'\');">'.imagem('icones/tgn_p.png',ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para ver a '.$config['tgn'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_brainstorm']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=brainstorm_pro_ver&brainstorm_id='.$linha['msg_gestao_brainstorm'].'\');">'.imagem('icones/brainstorm_p.gif','Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para ver o brainstorm que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_gut']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=gut_pro_ver&gut_id='.$linha['msg_gestao_gut'].'\');">'.imagem('icones/gut_p.gif','Matriz G.U.T.','Clique neste ícone '.imagem('icones/gut_p.gif').' para ver a matriz G.U.T. que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_causa_efeito']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=causa_efeito_pro_ver&causa_efeito_id='.$linha['msg_gestao_causa_efeito'].'\');">'.imagem('icones/causa_efeito_p.gif','Diagrama de Causa-Efeito','Clique neste ícone '.imagem('icones/causa_efeito_p.gif').' para ver o diagrama de causa-efeito que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_arquivo']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=arquivos&a=ver&arquivo_id='.$linha['msg_gestao_arquivo'].'\');">'.imagem('icones/arquivo_p.png','Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para ver a arquivo que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_forum']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=foruns&a=ver&forum_id='.$linha['msg_gestao_forum'].'\');">'.imagem('icones/forum_p.gif','Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para ver o fórum que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_checklist']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=checklist_ver&checklist_id='.$linha['msg_gestao_checklist'].'\');">'.imagem('icones/todo_list_p.png','Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para ver o checklist que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_agenda']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=email&a=ver_compromisso&agenda_id='.$linha['msg_gestao_agenda'].'\');">'.imagem('icones/calendario_p.png','Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para ver o compromisso que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_agrupamento']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['msg_gestao_agrupamento'].'\');">'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png','Agrupamento','Clique neste ícone '.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').' para ver o arupamento que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_patrocinador']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['msg_gestao_patrocinador'].'\');">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Patrocinador','Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para ver o patrocinador que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_template']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=template_pro_ver&template_id='.$linha['msg_gestao_template'].'\');">'.imagem('icones/instrumento_p.png','Modelo','Clique neste ícone '.imagem('icones/instrumento_p.png').' para ver o modelo que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_painel']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=painel_pro_ver&painel_id='.$linha['msg_gestao_painel'].'\');">'.imagem('icones/indicador_p.gif','Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para ver o painel que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_painel_odometro']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['msg_gestao_painel_odometro'].'\');">'.imagem('icones/odometro_p.png','Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para ver o odômetro que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_painel_composicao']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['msg_gestao_painel_composicao'].'\');">'.imagem('icones/painel_p.gif','Composição de Painéis','Clique neste ícone '.imagem('icones/painel_p.gif').' para ver a composição de painéis que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_tr']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=tr&a=tr_ver&tr_id='.$linha['msg_gestao_tr'].'\');">'.imagem('icones/tr_p.png',ucfirst($config['tr']),'Clique neste ícone '.imagem('icones/tr_p.png').' para ver '.$config['genero_tr'].' '.$config['tr'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			elseif ($linha['msg_gestao_me']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=me_ver_pro&me_id='.$linha['msg_gestao_me'].'\');">'.imagem('icones/me_p.png',ucfirst($config['me']),'Clique neste ícone '.imagem('icones/me_p.png').' para ver '.$config['genero_me'].' '.$config['me'].' que '.($config['genero_mensagem']=='o' ? 'este' : 'esta').' '.$config['mensagem'].' está vinculad'.$config['genero_mensagem'].'.').'</a>';
			
			}
		}
		
	$rs_anexo=null;
	if ($pode_ler && !$rs['cripto']) echo '<td align="left" width="50%">'.$imagem.$icone.'<a href="javascript:void(0);" onclick="javascript:visualizar_msg('.$rs['msg_usuario_id'].', '.$rs['cripto'].')">'.dica((!$rs['tipo'] ? $rs['referencia'] : $tipo[$rs['tipo']]), ($rs['texto_nota'] ?  retorna_tira_duas_linhas($rs['texto_nota']) : retorna_tira_duas_linhas($rs['texto'].$texto_anexo))).$rs['referencia'].dicaF().'</a></td>';
	elseif ($pode_ler) echo '<td align="left"  width="50%">'.$imagem.$icone.'<a href="javascript:void(0);" onclick="javascript:visualizar_msg('.$rs['msg_usuario_id'].', '.$rs['cripto'].')">'.($rs['cripto']==1 ? dica ('Chave Pública', ''.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' está criptografad'.$config['genero_mensagem'].' utilizando a '.($status < 5 ? 'sua chave pública. Deverá ter a chave privada carregada para poder ler-la' : 'chave pública do destinatário.')) : dica('Senha', ''.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' está criptografad'.$config['genero_mensagem'].' com uma senha criada pelo remetente.')).$rs['referencia'].dicaF().'</a></td>';
	else  echo '<td align="left" width="50%">'.imagem('icones/vazio16.gif').'&nbsp;'.dica('Acesso Restrito', 'Classificação sigilosa superior ao seu nível de acesso').'Acesso Restrito'.dicaF().'</a></td>';
	
	if ($status != 5) echo '<td nowrap="nowrap">'.dica('Detalhes do '.ucfirst($config['usuario']), $dentro).'<a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=admin&a=ver_usuario&tab=3&usuario_id='.$rs['de_id'].'\');">'.($Aplic->usuario_prefs['nomefuncao'] ? ($rs['nome_de'] ? $rs['nome_de'] : $rs['nome_usuario']) : ($rs['funcao_de'] ? $rs['funcao_de'] : $rs['contato_funcao'])).'</a>'.dicaF().'</td>';		
	if ($status == 5) echo '<td nowrap="nowrap" width="130">'.dica('Detalhes do '.ucfirst($config['usuario']), 'Clique para ver detalhes deste usuário').'<a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=admin&a=ver_usuario&tab=3&usuario_id='.$rs['para_id'].'\');">'.$rs['funcao_para'].'</a>'.dicaF().'</td>';				
			
	if ($status == 5){
		echo '<td nowrap="nowrap">';
		$sql->adTabela('msg_usuario');
	  $sql->adCampo('count(msg_usuario.para_id) AS quantidade');
		$sql->adOnde('msg_usuario.status = 0 AND msg_id = '.$rs['msg_id']);
	  $quantidade = $sql->Resultado();
	  $sql->limpar();
		echo $quantidade;
		echo '</td>'; 
		}
	

	echo '<td nowrap="nowrap">'.retorna_data($rs['datahora']).'</td>';
	if ($config['msg_precedencia']) echo '<td nowrap="nowrap" style ="color:'.(isset($cor_prioridade[$rs['precedencia']]) ? $cor_prioridade[$rs['precedencia']] : '#000').(isset($rs['precedencia']) && $rs['precedencia'] ? ';font-weight: bold;' :';').'">'.(isset($precedencia[$rs['precedencia']]) ? $precedencia[$rs['precedencia']] : '').'</td>';
	if ($config['msg_class_sigilosa']) echo '<td nowrap="nowrap">'.(isset($class_sigilosa[$rs['class_sigilosa']]) ? $class_sigilosa[$rs['class_sigilosa']] : '').'</td>';
	if ($status == 1 || $status == 10 ){ 
		echo '<td nowrap="nowrap">'.(isset($tipos_status[$rs['status']]) ? $tipos_status[$rs['status']] : '&nbsp;').'</td>';
		$passou=1;
		}
	echo '<td nowrap="nowrap">'.$rs['msg_id'].'</td>';
	if ($status <= 4) echo '<td width="25" style="background-color:#'.$rs['cor'].'"><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=email&a=inserir_nota&msg_usuario_id='.$rs['msg_usuario_id'].'\');">'.($rs['nota'] ? dica('Anotação', retorna_tira_duas_linhas($rs['nota']).'<br>Clique para editar a anotação').imagem('icones/anexar.png'): dica('Inserir Anotação','Clique para inserir uma anotação').imagem('icones/nota.gif')).dicaF().'</a></td>';	
	echo '</tr>';
	}

echo '<tr><td align=center colspan="20"><table><tr>';



echo '<td>'.dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']),'Enviar um'.($config['genero_mensagem']=='a' ? 'a' : '').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'<a class="botao" href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=email&a='.($Aplic->profissional ? 'nova_mensagem_pro' : 'seleciona_usuarios').'&destino_cabecalho=envia_msg\');"><span><b>nov'.$config['genero_mensagem'].'</b></span></a>'.dicaF().'</td>';
if ($status < 5) 	echo '<td>'.dica('Responder','Clique nesta opção para enviar uma resposta para o criador d'.$config['genero_mensagem'].' '.$config['mensagem'].'.<BR><BR>Ao contrário do DESPACHAR, em que se seleciona quantos destinatários desejar, ao responder apenas o criador d'.$config['genero_mensagem'].' '.$config['mensagem'].' é automaticamente selecionado.').'<a class="botao" href="javascript:void(0);" onclick="javascript:responder();"><span><b>responder</b></span></a>'.dicaF().'</td>';
echo '<td>'.dica('Despachar','Clique nesta opção para enviar um texto para os destinatários selecionados das mensagens selecionadas.<BR><BR>Ao contrário do RESPONDER, em que o criador d'.$config['genero_mensagem'].' '.$config['mensagem'].' é automaticamente selecionado, no despacho cada destinatário deverá ser selecionado.').'<a class="botao" href="javascript:void(0);" onclick="javascript:despachar();"><span><b>despachar</b></span></a>'.dicaF().'</td>';
echo '<td>'.dica('Anotar','Clique nesta opção para registrar um texto junto com  as mensagens selecionadas.<BR><BR>Anotar se diferencia das opções RESPONDER E DESPACHAR por não enviar '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' para nenhum destinatário.').'<a class="botao"  href="javascript:void(0);" onclick="javascript:anotar();"><span><b>anotar</b></span></a>'.dicaF().'</td>';
echo '<td>'.dica('Encaminhar','Clique nesta opção para enviar as mensagens selecionadas para os destinatários selecionados.<BR><BR>Ao contrário de DESPACHAR, RESPONDER e ANOTAR, nenhum texto será registrado.').'<a class="botao" href="javascript:void(0);" onclick="javascript:encaminhar();"><span><b>encaminhar</b></span></a>'.dicaF().'</td>';
if ($status != 3 && $status < 5 && !$outro_usuario) echo '<td>'.dica('Pender','Clique nesta opção para mover '.$config['genero_mensagem'].'s '.$config['mensagens'].' selecionad'.$config['genero_mensagem'].'s para a caixa d'.$config['genero_mensagem'].'s pendentes.').'<a class="botao" href="javascript:pender();"><span><b>pender</b></span></a>'.dicaF().'</td>';
if ($status < 4 && !$outro_usuario) echo '<td>'.dica('Arquivar','Clique nesta opção para mover '.$config['genero_mensagem'].'s '.$config['mensagens'].' selecionad'.$config['genero_mensagem'].'s para a caixa d'.$config['genero_mensagem'].'s arquivad'.$config['genero_mensagem'].'s.').'<a class="botao" href="javascript:arquivar();"><span><b>arquivar</b></span></a>'.dicaF().'</td>';
if ($status != 1 && !$outro_usuario && $tem_pasta && $status != 5) echo '<td>'.dica('Caixa de Seleção de Pasta','Selecione na caixa de opção em qual pasta deseja entrar para ver '.$config['genero_mensagem'].'s '.$config['mensagens'].' armazenad'.$config['genero_mensagem'].'s.<BR><BR>Para mover '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' para a pasta, selecione a mesma e utilize a próxima caixa de seleção, do lado direito.').'<a>'.comboPasta($usuario_id).'</a>'.dicaF().'</td>'; 
if (!$outro_usuario && $tem_pasta && $status != 5) echo '<td>'.dica('Mover para Pasta','Selecione na caixa de opção para qual pasta deseja mover '.$config['genero_mensagem'].'s '.$config['mensagens'].' selecionad'.$config['genero_mensagem'].'s.<BR><BR>Para mover '.$config['genero_mensagem'].'s '.$config['mensagens'].' armazenad'.$config['genero_mensagem'].'s, em uma determinada pasta, utilize a caixa de seleção anterior, do lado esquerdo.').'<a>'.comboMover($usuario_id).'</a>'.dicaF().'</td>'; 

echo '</tr></table></td></tr></table></form>';

//echo '<p align="center">';

//echo '</body></html>';

function comboPasta($usuario_id) {
	global $status, $pasta, $Aplic;
	$sql = new BDConsulta;
	$s = '<select id="codigo_pasta" name="codigo_pasta" class=text size=1 onchange="resulta_combo('.$status.');">';
	$s .= '<option value="0" '.($pasta==0 ? ' selected="selected"' : '').' >fora das pastas</option>';
	$s .= '<option value="-1" '.($pasta==-1 ? ' selected="selected"' : '').' >todas as pastas</option>';
	$sql->adTabela('pasta');
  $sql->adCampo('pasta_id,nome');
	$sql->adOnde('pasta.usuario_id='.$usuario_id);
	$pastas=$sql->Lista();
	$sql->limpar();
	foreach ($pastas as $linha) $s .= '<option value="'.$linha['pasta_id'].'"'.(($linha['pasta_id'] == $pasta ) ? ' selected="selected"' : '').'>'.$linha['nome'].'</option>';
	$s .= '</select>';
	return $s;	
	}

function comboMover($usuario_id) {
	global $status, $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('pasta');
  $sql->adCampo('pasta_id, nome');
	$sql->adOnde('pasta.usuario_id='.$usuario_id);
	$pastas=$sql->Lista();
	$sql->limpar();
	$s = '<select id="codigo_mover_pasta" name="codigo_mover_pasta" class=text size=1 onchange="javascript:mover_pasta();" >';
	$s .= '<option value="null" >'.($status==1 ? 'arquivar em' : 'mover para').'</option>';
	$s .= '<option value="null" >fora de pasta</option>';
	foreach ($pastas as $linha) $s .= '<option value="'.$linha['pasta_id'].'">'.$linha['nome'].'</option>';
	$s .= '</select>';
	return $s;	
	}

echo '<div id="light" class="caixa_senha"><table align=center id="tabelasenha" style="display:none">';
echo '<tr><td colspan=3>Insira a senha</td></tr>';
echo '<tr><td><input type="password" size="25" maxlength="32" name="caixa_senha" id="caixa_senha" class="texto"  onkeypress="return submitenter(this, event)" /></td><td><a class="botao" href="javascript:void(0)" onclick="colocar_senha();"><span>OK</span></a></td><td><a class="botao" href="javascript:void(0)" onclick="document.getElementById(\'light\').style.display=\'none\'; document.getElementById(\'fade\').style.display=\'none\'; document.getElementById(\'tabelasenha\').style.display=\'none\'; "><span>cancelar</span></a></td></tr>';
echo '</table></div>';

if($Aplic->profissional){
$dados = array( 'id' => $Aplic->usuario_id, 'nome' => utf8_encode($Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra));
$dados['msg_nao_lidas'] = $Aplic->mensagensNaoLidas();
$dados['msg_total'] = $Aplic->mensagensTotalCaixaEntrada();
$dados['msg_pendentes'] = $Aplic->mensagensTotalPendentes();
echo '<script type="text/javascript">if(parent && parent.gpwebApp) parent.gpwebApp.atualizaDadosUsuario('.json_encode($dados).')</script>';	
}

?>

<script language=Javascript>

function visualizar_msg(msg, cripto){
	var carregou_chave="<?php echo ($Aplic->chave_privada ? '1' : '')?>";
	if (cripto == 2){
		env.msg_usuario_id.value=msg;
		document.getElementById('tabelasenha').style.display=''; 
		document.getElementById('light').style.display='block'; 
		document.getElementById('fade').style.display='block';
		}
	else if (cripto==0 || carregou_chave){
		env.msg_usuario_id.value=msg;
		env.a.value="<?php echo $Aplic->usuario_prefs['modelo_msg'];?>";
		env.submit();	
		}		
	else alert('Necessita primeiramente carregar a sua chave privada. Clique no botão Chaves no canto superior direito.');	
	}

function colocar_senha(){
	env.senha.value=document.getElementById('caixa_senha').value;
	env.a.value="<?php echo $Aplic->usuario_prefs['modelo_msg'];?>";
	if (env.senha.value !='') env.submit();
	else alert("Necessita inserir uma senha para ler <?php echo $config['genero_mensagem'].' '.$config['mensagem']?>!");
	}


function submitenter(campo,e){
	var codigo;
	if (window.event) codigo = window.event.keyCode;
	else if (e) codigo = e.which;
	else return true;
	
	if (codigo == 13)   {
	   colocar_senha();
	   return false;
	   }
	else return true;
	}

function resulta_combo(status) {
  document.getElementById('status').value='<?php echo $status ?>';
  document.getElementById('pasta').value=document.getElementById('codigo_pasta').value;
  document.getElementById('a').value="lista_msg";
	document.getElementById('env').submit();		
  } 
        
function mover_pasta() {
  var vetor_msg_usuario_id=new Array();
  var j=0;
  for(i=0;i < document.getElementById('env').elements.length;i++) {
			thiselm = document.getElementById('env').elements[i];
			if (thiselm.checked && thiselm.name=='vetor_msg_usuario[]') {
				vetor_msg_usuario_id[j++]=thiselm.value;
				}
	  	}	
	if (j>0){
			document.getElementById('mover').value=vetor_msg_usuario_id;
			document.getElementById('pasta').value=document.getElementById('codigo_mover_pasta').value;
  		document.getElementById('a').value="lista_msg";
			document.getElementById('env').submit();
			}
	else alert ("Selecione ao menos <?php echo ($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem']?>!");				
  }              

function marca_sel_todas() {
  with(document.getElementById('env')) {
	  for(i=0;i<elements.length;i++) {
			thiselm = elements[i];
			thiselm.checked = !thiselm.checked
      }
    }
  }
  

function anotar(){
	if (verifica_selecao()){
		env.status.value=null;
		env.tipo.value=4;
		env.a.value="envia_anot";
		env.submit();	
		}
	}  

function despachar(){
	if (verifica_selecao() && verifica_cripto()){
		env.status.value=null;	
		env.tipo.value=1;
		env.destino.value="envia_anot";
		env.a.value="seleciona_usuarios";
		env.submit();
		}

	}  

function responder(){
	if (verifica_selecao()){
		env.status.value=null;	
		env.tipo.value=2;
		env.a.value="envia_anot";
		env.submit();
		}
	}  

function encaminhar(){
	if (verifica_selecao() && verifica_cripto()){	
		env.tipo.value=3;
		env.destino.value="grava_encaminha";
		env.a.value="seleciona_usuarios";
		env.submit();
		}
	} 

function pender(){
	if (verifica_selecao()){	
		env.status.value=3;
		env.a.value="grava_status";
		env.submit();
		}
	} 

function arquivar(){
	if (verifica_selecao()){	
		env.status.value=4;
		env.a.value="grava_status";
		env.submit();
		}
	} 
 

function ordenar(valor){
	env.campo_ordenar.value=valor;	 
	env.a.value="lista_msg";
	if (env.sentido.value=="ASC") env.sentido.value="DESC";
	else env.sentido.value="ASC";
	env.submit();
	}  
 
function verifica_selecao(){
	var j=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		if (document.getElementById('env').elements[i].checked) j++;
		}	
	if (j>0) return 1;
	else {
		alert ("Selecione ao menos <?php echo ($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem']?>!"); 
		return 0;
		}
	}  

function verifica_cripto(){
	var j=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		esteelem = document.getElementById('env').elements[i];
		
		if (esteelem.checked) {
			if (document.getElementById('cripto_'+esteelem.value)!=null && document.getElementById('cripto_'+esteelem.value).value >0)	j++;
			}
		}	
	if (!j) return true;
	else {
		alert ("Por motivos de segurança, <?php echo $config['genero_mensagem'].'s '.$config['mensagens']?> criptografad<?php echo $config['genero_mensagem']?>s deverão ser abert<?php echo $config['genero_mensagem']?>s para visualização, um<?php echo ($config['genero_mensagem']=='a' ? 'a' : '')?> de cada vez, antes de enviar para terceiros!"); 
		return false;
		}
	}
	  
</script>
