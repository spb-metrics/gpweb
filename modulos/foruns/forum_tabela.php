<?php

global $Aplic, $filtro_prioridade_forum, $ver_subordinadas, $estilo_interface, $lista_cias, $lista_depts, $tab, $usuario_id, $cia_id, $dept_id, $dialogo,
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
	$ata_id,
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


$ordemPor = getParam($_REQUEST, 'ordemPor', 'forum_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$tf = $Aplic->getPref('formatohora');
$max_larg_msg = 30;


$nada_selecionado=(
	!$tarefa_id &&  
	!$projeto_id &&  
	!$pg_perspectiva_id &&  
	!$tema_id &&  
	!$pg_objetivo_estrategico_id &&  
	!$pg_fator_critico_id &&  
	!$pg_estrategia_id && 
	!$pg_meta_id &&  
	!$pratica_id &&  
	!$pratica_indicador_id &&  
	!$plano_acao_id &&  
	!$canvas_id &&  
	!$risco_id && 
	!$risco_resposta_id && 
	!$calendario_id &&  
	!$monitoramento_id &&  
	!$swot_id &&  
	!$operativo_id && 
	!$instrumento_id && 
	!$recurso_id && 
	!$problema_id && 
	!$demanda_id && 
	!$programa_id && 
	!$licao_id && 
	!$evento_id && 
	!$link_id && 
	!$avaliacao_id && 
	!$tgn_id && 
	!$brainstorm_id && 
	!$gut_id && 
	!$causa_efeito_id && 
	!$arquivo_id && 
	!$ata_id && 
	!$checklist_id && 
	!$agenda_id && 
	!$agrupamento_id && 
	!$patrocinador_id && 
	!$template_id &&
	!$painel_id && 
	!$painel_odometro_id  && 
	!$painel_composicao_id &&
	!$tr_id &&
	!$me_id
	);
$sql = new BDConsulta;
	
$sql->adTabela('foruns');
$sql->esqUnir('usuarios', 'u','usuario_id = forum_dono');
$sql->esqUnir('forum_mensagens', 'l', 'l.mensagem_id = forum_ultimo_id');
$sql->esqUnir('forum_mensagens', 'c', 'c.mensagem_forum = forum_id');
$sql->esqUnir('forum_acompanhar', 'w', 'acompanhar_forum = forum_id');
$sql->esqUnir('forum_visitas', 'v', 'visita_usuario = '.(int)$Aplic->usuario_id.' AND visita_forum = forum_id and visita_mensagem = c.mensagem_id');
$sql->esqUnir('contatos', 'cts', 'contato_id = u.usuario_contato');
if ($filtro_prioridade_forum){
		$sql->esqUnir('priorizacao', 'priorizacao', 'foruns.forum_id=priorizacao_forum');
		if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_forum = foruns.forum_id AND priorizacao_modelo IN ('.$filtro_prioridade_forum.')) AS priorizacao');
		else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_forum = foruns.forum_id AND priorizacao_modelo IN ('.$filtro_prioridade_forum.')) AS priorizacao');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_forum.')');
		}
$sql->adCampo('foruns.*');
$sql->adCampo('forum_moderador, forum_data_criacao, forum_ultima_data');
$sql->adCampo('SUM( CASE WHEN c.mensagem_superior IS NULL THEN 1 ELSE 0 END ) AS forum_topicos');
$sql->adCampo('SUM( CASE WHEN c.mensagem_superior > 0 THEN 1 ELSE 0 END) AS forum_respostas');
$sql->adCampo('usuario_login, concatenar_tres(contato_posto, \' \',contato_nomeguerra) nome_responsavel');
$sql->adCampo('l.mensagem_texto, l.mensagem_titulo');
$sql->adCampo('LENGTH(l.mensagem_texto) message_length, acompanhar_usuario, l.mensagem_superior, l.mensagem_id');
$sql->adCampo('count(distinct v.visita_mensagem) as visit_contagem, count(distinct c.mensagem_id) as message_contagem');

if ($Aplic->profissional){
	$sql->esqUnir('forum_gestao','forum_gestao','forum_gestao_forum = foruns.forum_id');
	
	if ($tab==0) $sql->adOnde('forum_ativo=1');
	elseif ($tab==1) $sql->adOnde('forum_ativo!=1 OR forum_ativo IS NULL');
	
	if ($tarefa_id) $sql->adOnde('forum_gestao_tarefa='.$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('forum_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('forum_gestao_perspectiva='.$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('forum_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('forum_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('forum_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('forum_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('forum_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('forum_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('forum_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('forum_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('forum_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('forum_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('forum_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('forum_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('forum_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('forum_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('forum_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('forum_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('forum_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('forum_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('forum_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('forum_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('forum_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('forum_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('forum_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('forum_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('forum_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('forum_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('forum_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('forum_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('forum_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('forum_gestao_arquivo='.(int)$arquivo_id);
	elseif ($checklist_id) $sql->adOnde('forum_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('forum_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('forum_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('forum_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('forum_gestao_template='.(int)$template_id);
	elseif ($painel_id) $sql->adOnde('forum_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('forum_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('forum_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('forum_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('forum_gestao_me='.(int)$me_id);
	}
else {
	if ($projeto_id) $sql->adOnde('forum_projeto = '.(int)$projeto_id);
	if ($pratica_id) $sql->adOnde('forum_pratica = '.(int)$pratica_id);
	if ($pratica_indicador_id) $sql->adOnde('forum_indicador = '.(int)$pratica_indicador_id);
	if ($pg_objetivo_estrategico_id) $sql->adOnde('forum_objetivo = '.(int)$pg_objetivo_estrategico_id);
	if ($tema_id) $sql->adOnde('forum_tema = '.(int)$tema_id);
	if ($pg_estrategia_id) $sql->adOnde('forum_estrategia = '.(int)$pg_estrategia_id);
	if ($plano_acao_id) $sql->adOnde('forum_acao = '.(int)$plano_acao_id);
	if ($pg_fator_critico_id) $sql->adOnde('forum_fator = '.(int)$pg_fator_critico_id);
	if ($pg_meta_id) $sql->adOnde('forum_meta = '.(int)$pg_meta_id);
	if ($pg_perspectiva_id) $sql->adOnde('forum_perspectiva = '.(int)$pg_perspectiva_id);
	}

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('forum_dept','forum_dept', 'forum_dept.forum_dept_forum=foruns.forum_id');
	$sql->adOnde('forum_dept='.(int)$dept_id.' OR forum_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('forum_dept','forum_dept', 'forum_dept.forum_dept_forum=foruns.forum_id');
	$sql->adOnde('forum_dept IN ('.$lista_depts.') OR forum_dept_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('forum_cia', 'forum_cia', 'foruns.forum_id=forum_cia_forum');
	$sql->adOnde('forum_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR forum_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('forum_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('forum_cia IN ('.$lista_cias.')');	
	

if ($usuario_id) {
	$sql->esqUnir('forum_usuario','forum_usuario', 'forum_usuario_forum=foruns.forum_id');
	$sql->adOnde('forum_dono = '.(int)$usuario_id.' OR forum_moderador = '.(int)$usuario_id.' OR forum_usuario_usuario= '.(int)$usuario_id);
	}


$sql->adGrupo('forum_id');

$sql->adOrdem($ordemPor.' '.($ordem ? 'ASC' : 'DESC'));
$foruns = $sql->Lista();


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


echo '<form name="frm_acompanhar" method="post">';
echo '<input type="hidden" name="m" value="foruns" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_acompanhar_forum" />';
echo '<input type="hidden" name="acompanhar" value="forum" />';


$pagina = getParam($_REQUEST, 'pagina', 1);
$xpg_tamanhoPagina = $config['qnt_foruns'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1);
$xpg_totalregistros = ($foruns ? count($foruns) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'fórum', 'fóruns','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellspacing=0 cellpadding="2" border=0 class="tbl1">';
echo '<tr>';
echo '<th></th>';
echo '<th nowrap="nowrap" align="right"><a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&ordemPor=acompanhar_usuario\');" class="hdr">'.dica('Acompanhar', 'Marque as caixas abaixo e clique o botão <b>acompanhar</b> para ser informado sobre atualizações nos fóruns marcados.<br><br>Quando se está acompanhando um fórum, o sistema avisa caso houver mensagens não lidas.').'A'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordemPor=forum_nome&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Nome', 'Cada fórum é apresentado com seu nome, descrição,que o criou e quando iniciou.').($ordemPor=='forum_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Nome do Fórum'.dicaF().'</a></th>';
if ($filtro_prioridade_forum) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordemPor=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordemPor=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Priorização'.dicaF().'</a></th>';

if ($Aplic->profissional) echo '<th nowrap="nowrap">'.dica('Relacionado', 'A quais áreas do sistema está relacionado.').'Relacionado'.dicaF().'</th>';

echo '<th nowrap="nowrap" width="50" align="center"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordemPor=forum_topicos&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Tópicos', 'Cada fórum pode ter um ou mais tópicos.<br><br>Pode imaginar tópicos como subassuntos do fórum ou perguntas relacionadas ao fórum.').($ordemPor=='forum_topicos' ? imagem('icones/'.$seta[$ordem]) : '').'Tópicos'.dicaF().'</a></th>';
echo '<th nowrap="nowrap" width="50" align="center"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordemPor=forum_respostas&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Respostas', 'Cada tópico poderá ter diversas respostas (postagens).').($ordemPor=='forum_respostas' ? imagem('icones/'.$seta[$ordem]) : '').'Respostas'.dicaF().'</a></th>';
echo '<th nowrap="nowrap" width="200"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordemPor=forum_ultima_data&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Última Postagem', 'Data da última resposta inserida em um dos tópicos.').($ordemPor=='forum_ultima_data' ? imagem('icones/'.$seta[$ordem]) : '').'Última Postagem'.dicaF().'</a></th>';
echo '</tr>';


				


$permiteEditar=$Aplic->checarModulo('foruns', 'editar');


$agora = new CData();

for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $foruns[$i];
	if (isset($linha['forum_id']) && $linha['forum_id'] && permiteAcessarForum($linha['forum_acesso'],  $linha['forum_id'])){
		$permiteEditar=permiteEditarForum($linha['forum_acesso'], $linha['forum_id']);
		
		
		echo '<tr>';
		echo '<td nowrap="nowrap" align="center" width=16>';
				if ($linha["forum_dono"] == $Aplic->usuario_id || $permiteEditar) echo dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' caso deseja editar este fórum.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=editar&forum_id='.$linha['forum_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF();
				if ($linha['visit_contagem'] != $linha['message_contagem']) echo '&nbsp;'.imagem('icones/msg_nova.png', 'Mensagem Não Lida','Você tem mensagem não lida neste fórum.');
		echo '</td>';
		echo '<td nowrap="nowrap" align="center" width=16>'.dica('Acompanhar', 'Marque esta caixa e clique o botão <b>acompanhar</b> para ser informado sobre atualizações neste fórum.<br><br>Caso esteja acompanhando este fórum, o sistema avisará se houver mensagens não lidas.').'<input type="checkbox" name="forum_'.$linha['forum_id'].'" '.($linha['acompanhar_usuario'] ? 'checked="checked"' : '').' />'.dicaF().'</td>';
		
		
		$mensagem_data = intval($linha['forum_ultima_data']) ? new CData($linha['forum_ultima_data']) : null;
		$criar_data = $linha['forum_data_criacao'] ? new CData($linha['forum_data_criacao']) : null;
	
		echo '<td>'.dica($linha['forum_nome'], 'Clique em cima do nome do fórum para ver os detalhes do mesmo.').'<span style="font-size:10pt;font-weight:bold"><a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$linha['forum_id'].'\');">'.$linha['forum_nome'].'</a></span>'.dicaF().'<br />'.$linha['forum_descricao'].'<br /><font color="#777777">Responsável '.$linha['nome_responsavel'].($criar_data ? ' , iniciou em '.$criar_data->format('%d/%m/%Y') : '').'</font></td>';
		
		if ($filtro_prioridade_forum) echo '<td align="right" nowrap="nowrap" width=50>'.($linha['priorizacao']).'</td>';

		
		if ($Aplic->profissional){
			
			echo '<td>';
			
			$sql->adTabela('forum_gestao');
			$sql->adCampo('forum_gestao.*');
			$sql->adOnde('forum_gestao_forum ='.(int)$linha['forum_id']);
			$sql->adOrdem('forum_gestao_ordem');
		  $lista = $sql->Lista();
		  $sql->Limpar();
		  if (count($lista)) {

				$qnt=0;
				foreach($lista as $gestao_data){
					if ($gestao_data['forum_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['forum_gestao_tarefa']);
					elseif ($gestao_data['forum_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['forum_gestao_projeto']);
					elseif ($gestao_data['forum_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['forum_gestao_pratica']);
					elseif ($gestao_data['forum_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['forum_gestao_acao']);
					elseif ($gestao_data['forum_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['forum_gestao_perspectiva']);
					elseif ($gestao_data['forum_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['forum_gestao_tema']);
					elseif ($gestao_data['forum_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['forum_gestao_objetivo']);
					elseif ($gestao_data['forum_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['forum_gestao_fator']);
					elseif ($gestao_data['forum_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['forum_gestao_estrategia']);
					elseif ($gestao_data['forum_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['forum_gestao_meta']);
					elseif ($gestao_data['forum_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['forum_gestao_canvas']);
					elseif ($gestao_data['forum_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['forum_gestao_risco']);
					elseif ($gestao_data['forum_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['forum_gestao_risco_resposta']);
					elseif ($gestao_data['forum_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['forum_gestao_indicador']);
					elseif ($gestao_data['forum_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['forum_gestao_calendario']);
					elseif ($gestao_data['forum_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['forum_gestao_monitoramento']);
					elseif ($gestao_data['forum_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['forum_gestao_ata']);
					elseif ($gestao_data['forum_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['forum_gestao_swot']);
					elseif ($gestao_data['forum_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['forum_gestao_operativo']);
					elseif ($gestao_data['forum_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['forum_gestao_instrumento']);
					elseif ($gestao_data['forum_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['forum_gestao_recurso']);
					elseif ($gestao_data['forum_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['forum_gestao_problema']);
					elseif ($gestao_data['forum_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['forum_gestao_demanda']);
					elseif ($gestao_data['forum_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['forum_gestao_programa']);
					elseif ($gestao_data['forum_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['forum_gestao_licao']);
					elseif ($gestao_data['forum_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['forum_gestao_evento']);
					elseif ($gestao_data['forum_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['forum_gestao_link']);
					elseif ($gestao_data['forum_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['forum_gestao_avaliacao']);
					elseif ($gestao_data['forum_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['forum_gestao_tgn']);
					elseif ($gestao_data['forum_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['forum_gestao_brainstorm']);
					elseif ($gestao_data['forum_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['forum_gestao_gut']);
					elseif ($gestao_data['forum_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['forum_gestao_causa_efeito']);
					elseif ($gestao_data['forum_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['forum_gestao_arquivo']);
					elseif ($gestao_data['forum_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['forum_gestao_checklist']);
					elseif ($gestao_data['forum_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['forum_gestao_agenda']);
					elseif ($gestao_data['forum_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['forum_gestao_agrupamento']);
					elseif ($gestao_data['forum_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['forum_gestao_patrocinador']);
					elseif ($gestao_data['forum_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['forum_gestao_template']);
					elseif ($gestao_data['forum_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['forum_gestao_painel']);
					elseif ($gestao_data['forum_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['forum_gestao_painel_odometro']);
					elseif ($gestao_data['forum_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['forum_gestao_painel_composicao']);
					elseif ($gestao_data['forum_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['forum_gestao_tr']);
					elseif ($gestao_data['forum_gestao_me']) echo ($qnt++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['forum_gestao_me']);
					}
				
				}
			echo '</td>';	
			}
				
		
		
		echo '<td nowrap="nowrap" align="center">'.$linha['forum_topicos'].'</td>';
		echo '<td nowrap="nowrap" align="center">'.$linha['forum_respostas'].'</td>';
		
		
		
		
		
		echo '<td width="225">';
		if ($mensagem_data !== null) {
			echo $mensagem_data->format('%d/%m/%Y'.' '.$tf);
			$ultimo = new Data_Intervalo();
			$ultimo->setFromDateDiff($agora, $mensagem_data);
			echo '<br /><font color=#999966>(Ultima Postagem ';
			printf('%.1f', $ultimo->format('%d'));
			echo ' dias atrás) </font>';
			$id = $linha['mensagem_superior'] < 0 ? $linha['mensagem_id'] : $linha['mensagem_superior'];
			echo '<br />'.dica($linha['mensagem_titulo'], $linha['mensagem_texto']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$linha['forum_id'].'&mensagem_id='.$id.'\');">';
			echo substr($linha['mensagem_texto'],0,$max_larg_msg);
			echo $linha['message_length'] > $max_larg_msg ? '...' : '';
			echo dicaF().'</a>';
			}
		else echo 'Sem Postagem';
		echo '</td></tr>';
		}
	}
if (!$xpg_totalregistros)  echo '<tr><td colspan=20>Nenhum fórum encontrado.</td></tr>';
echo '</table>';
if ($xpg_totalregistros) {
	echo '<table width="100%" cellspacing=0 cellpadding=0 border=0 class="std">';
	echo '<tr><td align="left">'.botao('acompanhar', 'Acompanhar', 'Acompanhar os fóruns marcados acima.<br><br>Quando se está acompanhando um fórum, o sistema avisa caso houver mensagens não lidas.','','frm_acompanhar.submit();').'</td></tr>';
	echo '</table>';
	}
echo '</form>';
?>