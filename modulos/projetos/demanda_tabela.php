<?php
global $filtro_prioridade_demanda, $estilo_interface, $dialogo, $tab, $cia_id, $lista_cias, $dept_id, $lista_depts, $responsavel, $supervisor, $autoridade, $cliente, $demanda_setor, $demanda_segmento, $demanda_intervencao, $demanda_tipo_intervencao, $pesquisar_texto,
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



$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_demanda']);
$xmin = $xtamanhoPagina * ($pagina - 1); 


$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'demanda_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');


$xtotalregistros=demandas_quantidade($tab, $cia_id, $lista_cias, $dept_id, $lista_depts, $responsavel, $supervisor, $autoridade, $cliente, $demanda_setor, $demanda_segmento, $demanda_intervencao, $demanda_tipo_intervencao, $pesquisar_texto, $filtro_prioridade_demanda, 
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
	$me_id);

$sql->adTabela('demandas');
$sql->adCampo('demandas.demanda_id, demanda_nome, demanda_usuario, demanda_acesso, demanda_cor, demanda_identificacao, formatar_data(demanda_data, \'%d/%m/%Y\') AS data');
if ($filtro_prioridade_demanda){
		$sql->esqUnir('priorizacao', 'priorizacao', 'demandas.demanda_id=priorizacao_demanda');
		if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_demanda = demandas.demanda_id AND priorizacao_modelo IN ('.$filtro_prioridade_demanda.')) AS priorizacao');
		else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_demanda = demandas.demanda_id AND priorizacao_modelo IN ('.$filtro_prioridade_demanda.')) AS priorizacao');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_demanda.')');
		}
		
if ($Aplic->profissional && ($cia_id || $lista_cias) ) {
	$sql->esqUnir('demanda_cia', 'demanda_cia', 'demandas.demanda_id=demanda_cia_demanda');
	$sql->adOnde('demanda_cia IN ('.($lista_cias ? $lista_cias : $cia_id).') OR demanda_cia_cia IN ('.($lista_cias ? $lista_cias : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('demanda_cia IN ('.$cia_id.')');
else if ($lista_cias) $sql->adOnde('demanda_cia IN ('.$lista_cias.')');

if($dept_id || $lista_depts) $sql->esqUnir('demanda_depts', 'demanda_depts', 'demanda_depts.demanda_id = demandas.demanda_id');
if($dept_id && !$lista_depts) $sql->adOnde('demanda_depts.dept_id IN ('.$dept_id.') OR demanda_dept IN ('.$dept_id.')');
else if($lista_depts) $sql->adOnde('demanda_depts.dept_id IN ('.$lista_depts.') OR demanda_dept IN ('.$lista_depts.')');
if ($demanda_setor) $sql->adOnde('demanda_setor IN ('.$demanda_setor.')');
if ($demanda_segmento) $sql->adOnde('demanda_segmento IN ('.$demanda_segmento.')');
if ($demanda_intervencao) $sql->adOnde('demanda_intervencao IN ('.$demanda_intervencao.')');
if ($demanda_tipo_intervencao) $sql->adOnde('demanda_tipo_intervencao IN ('.$demanda_tipo_intervencao.')');
if ($supervisor) $sql->adOnde('demanda_supervisor IN ('.$supervisor.')');
if ($autoridade) $sql->adOnde('demanda_autoridade IN ('.$autoridade.')');
if ($cliente) $sql->adOnde('demanda_cliente IN ('.$cliente.')');
if($responsavel) $sql->esqUnir('demanda_usuarios', 'demanda_usuarios', 'demanda_usuarios.demanda_id = demandas.demanda_id');
if ($responsavel) $sql->adOnde('(demanda_usuarios.usuario_id IN ('.$responsavel.') OR demanda_usuario IN ('.$responsavel.'))');
if (trim($pesquisar_texto)) $sql->adOnde('demanda_nome LIKE \'%'.$pesquisar_texto.'%\' OR demanda_identificacao LIKE \'%'.$pesquisar_texto.'%\' OR demanda_justificativa LIKE \'%'.$pesquisar_texto.'%\' OR demanda_observacao LIKE \'%'.$pesquisar_texto.'%\' OR demanda_resultados LIKE \'%'.$pesquisar_texto.'%\' OR demanda_alinhamento LIKE \'%'.$pesquisar_texto.'%\'');

if ($Aplic->profissional){
	$sql->esqUnir('demanda_gestao','demanda_gestao','demanda_gestao_demanda = demandas.demanda_id');
	if ($tarefa_id) $sql->adOnde('demanda_gestao_tarefa='.$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('demanda_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('demanda_gestao_perspectiva='.$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('demanda_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('demanda_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('demanda_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('demanda_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('demanda_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('demanda_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('demanda_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('demanda_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('demanda_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('demanda_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('demanda_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('demanda_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('demanda_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('demanda_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('demanda_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('demanda_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('demanda_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('demanda_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('demanda_gestao_problema='.(int)$problema_id);
	elseif ($programa_id) $sql->adOnde('demanda_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('demanda_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('demanda_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('demanda_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('demanda_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('demanda_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('demanda_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('demanda_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('demanda_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('demanda_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('demanda_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('demanda_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('demanda_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('demanda_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('demanda_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('demanda_gestao_template='.(int)$template_id);
	elseif ($painel_id) $sql->adOnde('demanda_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('demanda_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('demanda_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('demanda_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('demanda_gestao_me='.(int)$me_id);
	}

if ($tab==0) $sql->adOnde('demanda_caracteristica_projeto IS NULL OR demanda_caracteristica_projeto=0');
elseif ($tab==1) $sql->adOnde('demanda_caracteristica_projeto=1');
elseif ($tab==2) $sql->adOnde('demanda_caracteristica_projeto=-1');
if ($tab< 3) $sql->adOnde('demanda_projeto IS NULL OR demanda_projeto=0');
elseif ($tab==3) $sql->adOnde('demanda_projeto IS NOT NULL AND demanda_projeto!=0');
if ($tab<5) $sql->adOnde('demanda_ativa=1');
else $sql->adOnde('demanda_ativa=0');
	
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$sql->adGrupo('demandas.demanda_id');

$demandas=$sql->Lista();
$sql->limpar();
$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Demanda', 'Demandas','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" cellpadding=2 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$dialogo) echo '<th>&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=demanda_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='demanda_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação da demanda.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=demanda_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='demanda_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da Demanda', 'Neste campo fica um nome para identificação da demanda.').'Nome'.dicaF().'</a></th>';

if ($filtro_prioridade_demanda) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Priorização'.dicaF().'</a></th>';

echo '<th width=40><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=demanda_data&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='demanda_data' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data', 'Neste campo fica a data de inserção da demanda.').'Data'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=demanda_identificacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='demanda_identificacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Identificação', 'Neste campo fica a identificação da demanda.').'Identificação'.dicaF().'</a></th>';

if ($Aplic->profissional) echo '<th nowrap="nowrap">'.dica('Relacionada', ' que área este demanda está relacionada.').'Relacionada'.dicaF().'</th>';


echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=demanda_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='demanda_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Demandante', 'O '.$config['usuario'].' que inseriu a demanda.').'Demandante'.dicaF().'</a></th>';
echo '</tr>';

for ($j = 0; $j < count($demandas); $j++) {
	$linha = $demandas[$j];
	$editar=permiteEditarDemanda($linha['demanda_acesso'],$linha['demanda_id']);
	if (permiteAcessarDemanda($linha['demanda_acesso'],$linha['demanda_id'])){
		echo '<tr>';
		if (!$dialogo) echo '<td width="16">'.($editar ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a demanda.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=demanda_editar&demanda_id='.$linha['demanda_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td width="15" align="right" style="background-color:#'.$linha['demanda_cor'].'"><font color="'.melhorCor($linha['demanda_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_demanda($linha['demanda_id']).'</td>';
		if ($filtro_prioridade_demanda) echo '<td align="right" nowrap="nowrap" width=50>'.($linha['priorizacao']).'</td>';

		echo '<td>'.($linha['data'] ? $linha['data']: '&nbsp;').'</td>';
		echo '<td>'.($linha['demanda_identificacao'] ? $linha['demanda_identificacao']: '&nbsp;').'</td>';
		
		
		
		if ($Aplic->profissional){
			$sql->adTabela('demanda_gestao');
			$sql->adCampo('demanda_gestao.*');
			$sql->adOnde('demanda_gestao_demanda ='.(int)$linha['demanda_id']);	
			$sql->adOrdem('demanda_gestao_ordem');
			$lista = $sql->Lista();
			$sql->Limpar();
			$qnt=0;
			echo '<td>';	
			if (count($lista)) {
				foreach($lista as $gestao_data){
					if ($gestao_data['demanda_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['demanda_gestao_tarefa']);
					elseif ($gestao_data['demanda_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['demanda_gestao_projeto']);
					elseif ($gestao_data['demanda_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['demanda_gestao_pratica']);
					elseif ($gestao_data['demanda_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['demanda_gestao_acao']);
					elseif ($gestao_data['demanda_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['demanda_gestao_perspectiva']);
					elseif ($gestao_data['demanda_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['demanda_gestao_tema']);
					elseif ($gestao_data['demanda_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['demanda_gestao_objetivo']);
					elseif ($gestao_data['demanda_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['demanda_gestao_fator']);
					elseif ($gestao_data['demanda_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['demanda_gestao_estrategia']);
					elseif ($gestao_data['demanda_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['demanda_gestao_meta']);
					elseif ($gestao_data['demanda_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['demanda_gestao_canvas']);
					elseif ($gestao_data['demanda_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['demanda_gestao_risco']);
					elseif ($gestao_data['demanda_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['demanda_gestao_risco_resposta']);
					elseif ($gestao_data['demanda_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['demanda_gestao_indicador']);
					elseif ($gestao_data['demanda_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['demanda_gestao_calendario']);
					elseif ($gestao_data['demanda_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['demanda_gestao_monitoramento']);
					elseif ($gestao_data['demanda_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['demanda_gestao_ata']);
					elseif ($gestao_data['demanda_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['demanda_gestao_swot']);
					elseif ($gestao_data['demanda_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['demanda_gestao_operativo']);
					elseif ($gestao_data['demanda_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['demanda_gestao_instrumento']);
					elseif ($gestao_data['demanda_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['demanda_gestao_recurso']);
					elseif ($gestao_data['demanda_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['demanda_gestao_problema']);
					elseif ($gestao_data['demanda_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['demanda_gestao_programa']);
					elseif ($gestao_data['demanda_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['demanda_gestao_licao']);
					elseif ($gestao_data['demanda_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['demanda_gestao_evento']);
					elseif ($gestao_data['demanda_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['demanda_gestao_link']);
					elseif ($gestao_data['demanda_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['demanda_gestao_avaliacao']);
					elseif ($gestao_data['demanda_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['demanda_gestao_tgn']);
					elseif ($gestao_data['demanda_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['demanda_gestao_brainstorm']);
					elseif ($gestao_data['demanda_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['demanda_gestao_gut']);
					elseif ($gestao_data['demanda_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['demanda_gestao_causa_efeito']);
					elseif ($gestao_data['demanda_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['demanda_gestao_arquivo']);
					elseif ($gestao_data['demanda_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['demanda_gestao_forum']);
					elseif ($gestao_data['demanda_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['demanda_gestao_checklist']);
					elseif ($gestao_data['demanda_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['demanda_gestao_agenda']);
					elseif ($gestao_data['demanda_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['demanda_gestao_agrupamento']);
					elseif ($gestao_data['demanda_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['demanda_gestao_patrocinador']);
					elseif ($gestao_data['demanda_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['demanda_gestao_template']);
					elseif ($gestao_data['demanda_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['demanda_gestao_painel']);
					elseif ($gestao_data['demanda_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['demanda_gestao_painel_odometro']);
					elseif ($gestao_data['demanda_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['demanda_gestao_painel_composicao']);		
					elseif ($gestao_data['demanda_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['demanda_gestao_tr']);	
					}
				}	
			echo '</td>';	
			}
		
		
		
		
		
		echo '<td>'.link_usuario($linha['demanda_usuario'],'','','esquerda').'</td>';
		echo '</tr>';
		}
	}
if (!count($demandas)) echo '<tr><td colspan=20><p>Nenhuma demanda encontrada.</p></td></tr>';
echo '</table>';
?>