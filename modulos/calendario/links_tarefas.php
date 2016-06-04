<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

function getProblemaLinks($periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal = false, $usuario_id=null, $cia_id = null, $dept_id=null, 
	$tarefa_id=null, 
	$projeto_id=null, 
	$pg_perspectiva_id=null, 
	$tema_id=null, 
	$pg_objetivo_estrategico_id=null, 
	$pg_fator_critico_id=null, 
	$pg_estrategia_id=null,
	$pg_meta_id=null, 
	$pratica_id=null, 
	$pratica_indicador_id=null, 
	$ata_id=null, 
	$canvas_id=null, 
	$risco_id=null,
	$risco_resposta_id=null,
	$calendario_id=null, 
	$monitoramento_id=null, 
	$ata_id=null, 
	$swot_id=null, 
	$operativo_id=null,
	$instrumento_id=null,
	$recurso_id=null,
	$problema_id=null,
	$demanda_id=null,
	$programa_id=null,
	$licao_id=null,
	$link_id=null,
	$avaliacao_id=null,
	$tgn_id=null,
	$brainstorm_id=null,
	$gut_id=null,
	$causa_efeito_id=null,
	$arquivo_id=null,
	$forum_id=null,
	$checklist_id=null,
	$agenda_id=null,
	$agrupamento_id=null,
	$patrocinador_id=null,
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null) {
	global $a, $Aplic, $config;
	
	
	$df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
	
	$db_inicio=$inicioPeriodo->format('%Y-%m-%d %H:%M:%S');
	$db_fim=$fimPeriodo->format('%Y-%m-%d %H:%M:%S');
	$tarefas_filtro = '';
	
	$sql = new BDConsulta;
	$sql->adTabela('problema');
	if ($usuario_id) $sql->esqUnir('problema_usuarios', 'problema_usuarios', 'problema_usuarios.problema_id=problema.problema_id');
	if ($dept_id)	$sql->esqUnir('problema_depts', 'problema_depts', 'problema_depts.problema_id = problema.problema_id');
		
	$sql->adCampo('DISTINCT problema.problema_id, problema_nome, problema_descricao, problema_acesso, problema_inicio, problema_fim, problema_cor AS cor');
	$sql->adOnde('problema_inicio <= \''.$db_fim.'\'');
	$sql->adOnde('problema_fim >= \''.$db_inicio. '\'');
	$sql->adOnde('problema_inicio IS NOT NULL');
	$sql->adOnde('problema_fim IS NOT NULL');
	

	$sql->esqUnir('problema_gestao', 'problema_gestao', 'problema.problema_id = problema_gestao_problema');
	if ($tarefa_id) $sql->adOnde('problema_gestao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('problema_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('problema_gestao_perspectiva='.(int)$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('problema_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('problema_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('problema_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('problema_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('problema_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('problema_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('problema_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($canvas_id) $sql->adOnde('problema_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('problema_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('problema_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('problema_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('problema_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('problema_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('problema_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('problema_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('problema_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('problema_gestao_recurso='.(int)$recurso_id);
	elseif ($demanda_id) $sql->adOnde('problema_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('problema_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('problema_gestao_licao='.(int)$licao_id);
	elseif ($link_id) $sql->adOnde('problema_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('problema_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('problema_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('problema_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('problema_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('problema_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('problema_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('problema_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('problema_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('problema_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('problema_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('problema_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('problema_gestao_template='.(int)$template_id);
	elseif ($painel_id) $sql->adOnde('problema_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('problema_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('problema_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('problema_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('problema_gestao_me='.(int)$me_id);
	if ($usuario_id) $sql->adOnde('problema_usuarios.usuario_id='.(int)$usuario_id.' OR problema_responsavel='.(int)$usuario_id);
	if ($cia_id) $sql->adOnde('problema_cia IN ('.$cia_id.')');
	if ($problema_id) $sql->adOnde('problema.problema_id = '.(int)$problema_id);
	if ($dept_id) $sql->adOnde('problema_dept IN ('.$dept_id.') OR problema_depts.dept_id IN ('.$dept_id.')');
	$sql->adOrdem('problema_inicio');
	$itens = $sql->Lista();
	$sql->limpar();

	$link = array();
	foreach ($itens as $linha) {
		if (permiteAcessarProblema($linha['problema_acesso'], $linha['problema_id'])){	

			$inicio = new CData($linha['problema_inicio']);
			$fim = new CData($linha['problema_fim']);

			$data = $inicio;
			for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
				$meio=false;
				if ($data->format($df)==$inicio->format($df) && $data->format($df)==$fim->format($df)) $inicio_fim=imagem('icones/inicio.gif').$inicio->format($tf).imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$fim->format($tf).imagem('icones/fim.gif');
				elseif ($data->format($df)==$inicio->format($df)) $inicio_fim=imagem('icones/inicio.gif').$inicio->format($tf).imagem('icones/vazio.gif');
				elseif ($data->format($df)==$fim->format($df)) $inicio_fim=imagem('icones/vazio.gif').$fim->format($tf).imagem('icones/fim.gif');
				else {
					$meio=true;
					$inicio_fim=imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
					}
				$texto='<tr valign=middle><td>'.$inicio_fim.'</td><td>'.imagem('icones/problema_p.png').$linha['problema_nome'].'</td></tr>';
				if ($minutoiCal) $link = array('problema' => true, 'texto_mini' => $texto);
				else {
					$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=problema&a=problema_ver&problema_id='.(int)$linha['problema_id'].'\');">'.$inicio_fim.'</a></td><td valign=middle>'.dica(ucfirst($config['problema']), $linha['problema_descricao']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=problema&a=problema_ver&problema_id='.(int)$linha['problema_id'].'\');">'.imagem('icones/problema_p.png').$linha['problema_nome'].'</a>'.dicaF().'</td></tr>';
					$link['texto_mini']=$texto;
					$link['problema']=true;
					}	
				if ($periodo_todo || !$meio) $links[$data->format('%Y%m%d')][] = $link;
	
				$data = $data->getNextDay();
				}
			}	
		}
	return $links;
	}

function getAtaLinks($periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal = false, $usuario_id=null, $cia_id = null, $dept_id=null, 
	$tarefa_id=null, 
	$projeto_id=null, 
	$pg_perspectiva_id=null, 
	$tema_id=null, 
	$pg_objetivo_estrategico_id=null, 
	$pg_fator_critico_id=null, 
	$pg_estrategia_id=null,
	$pg_meta_id=null, 
	$pratica_id=null, 
	$pratica_indicador_id=null, 
	$ata_id=null, 
	$canvas_id=null, 
	$risco_id=null,
	$risco_resposta_id=null,
	$calendario_id=null, 
	$monitoramento_id=null, 
	$ata_id=null, 
	$swot_id=null, 
	$operativo_id=null,
	$instrumento_id=null,
	$recurso_id=null,
	$problema_id=null,
	$demanda_id=null,
	$programa_id=null,
	$licao_id=null,
	$link_id=null,
	$avaliacao_id=null,
	$tgn_id=null,
	$brainstorm_id=null,
	$gut_id=null,
	$causa_efeito_id=null,
	$arquivo_id=null,
	$forum_id=null,
	$checklist_id=null,
	$agenda_id=null,
	$agrupamento_id=null,
	$patrocinador_id=null,
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null) {
	global $a, $Aplic, $config;
	
	
	$df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
	
	$db_inicio=$inicioPeriodo->format('%Y-%m-%d %H:%M:%S');
	$db_fim=$fimPeriodo->format('%Y-%m-%d %H:%M:%S');
	$tarefas_filtro = '';
	
	$sql = new BDConsulta;
	$sql->adTabela('ata_acao','ata_acao');
	$sql->esqUnir('ata', 'ata', 'ata.ata_id = ata_acao_ata');
	if ($usuario_id) $sql->esqUnir('ata_acao_usuario', 'ata_acao_usuario', 'ata_acao_usuario_acao=ata_acao.ata_acao_id');
	if ($dept_id)	$sql->esqUnir('ata_dept', 'ata_dept', 'ata_dept_ata = ata.ata_id');
		
	$sql->adCampo('DISTINCT ata_acao.ata_acao_id, ata_acao_texto, ata_titulo, ata_acesso, ata_acao_inicio, ata_acao_fim, ata_cor AS cor, ata_titulo, ata.ata_id');
	$sql->adOnde('ata_acao_inicio <= \''.$db_fim.'\'');
	$sql->adOnde('ata_acao_fim >= \''.$db_inicio. '\'');
	$sql->adOnde('ata_acao_inicio IS NOT NULL');
	$sql->adOnde('ata_acao_fim IS NOT NULL');
	

	$sql->esqUnir('ata_gestao', 'ata_gestao', 'ata.ata_id = ata_gestao_acao');
	if ($tarefa_id) $sql->adOnde('ata_gestao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('ata_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('ata_gestao_perspectiva='.(int)$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('ata_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('ata_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('ata_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('ata_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('ata_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('ata_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('ata_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($canvas_id) $sql->adOnde('ata_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('ata_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('ata_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('ata_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('ata_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('ata_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('ata_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('ata_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('ata_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('ata_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('ata_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('ata_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('ata_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('ata_gestao_licao='.(int)$licao_id);
	elseif ($link_id) $sql->adOnde('ata_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('ata_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('ata_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('ata_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('ata_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('ata_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('ata_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('ata_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('ata_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('ata_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('ata_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('ata_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('ata_gestao_template='.(int)$template_id);
	elseif ($painel_id) $sql->adOnde('ata_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('ata_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('ata_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('ata_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('ata_gestao_me='.(int)$me_id);
	if ($usuario_id) $sql->adOnde('ata_acao_usuario_usuario='.(int)$usuario_id.' OR ata_acao_responsavel='.(int)$usuario_id);
	if ($cia_id) $sql->adOnde('ata_cia IN ('.$cia_id.')');
	if ($ata_id) $sql->adOnde('ata.ata_id = '.(int)$ata_id);
	if ($dept_id) $sql->adOnde('ata_dept IN ('.$dept_id.') OR ata_dept_dept IN ('.$dept_id.')');
	$sql->adOrdem('ata_acao_inicio');
	$itens = $sql->Lista();
	$sql->limpar();

	$link = array();
	foreach ($itens as $linha) {
		if (permiteAcessarAta($linha['ata_acesso'], $linha['ata_id'])){	

			$inicio = new CData($linha['ata_acao_inicio']);
			$fim = new CData($linha['ata_acao_fim']);

			$data = $inicio;
			for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
				$meio=false;
				if ($data->format($df)==$inicio->format($df) && $data->format($df)==$fim->format($df)) $inicio_fim=imagem('icones/inicio.gif').$inicio->format($tf).imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$fim->format($tf).imagem('icones/fim.gif');
				elseif ($data->format($df)==$inicio->format($df)) $inicio_fim=imagem('icones/inicio.gif').$inicio->format($tf).imagem('icones/vazio.gif');
				elseif ($data->format($df)==$fim->format($df)) $inicio_fim=imagem('icones/vazio.gif').$fim->format($tf).imagem('icones/fim.gif');
				else {
					$meio=true;
					$inicio_fim=imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
					}
				$texto='<tr valign=middle><td>'.$inicio_fim.'</td><td>'.imagem('icones/ata_p.png').$linha['ata_titulo'].'</td></tr>';
				if ($minutoiCal) $link = array('ata' => true, 'texto_mini' => $texto);
				else {
					$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=atas&a=ata_ver&ata_id='.(int)$linha['ata_id'].'\');">'.$inicio_fim.'</a></td><td valign=middle>'.dica('Ação', $linha['ata_acao_texto']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=atas&a=ata_ver&ata_id='.(int)$linha['ata_id'].'\');">'.imagem('icones/ata_p.png').$linha['ata_titulo'].'</a>'.dicaF().'</td></tr>';
					$link['texto_mini']=$texto;
					$link['ata']=true;
					}	
				if ($periodo_todo || !$meio) $links[$data->format('%Y%m%d')][] = $link;
	
				$data = $data->getNextDay();
				}
			}	
		}
	return $links;
	}


function getAcaoLinks($periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal = false, $usuario_id=null, $cia_id = null, $dept_id=null, 
	$tarefa_id=null, 
	$projeto_id=null, 
	$pg_perspectiva_id=null, 
	$tema_id=null, 
	$pg_objetivo_estrategico_id=null, 
	$pg_fator_critico_id=null, 
	$pg_estrategia_id=null,
	$pg_meta_id=null, 
	$pratica_id=null, 
	$pratica_indicador_id=null, 
	$plano_acao_id=null, 
	$canvas_id=null, 
	$risco_id=null,
	$risco_resposta_id=null,
	$calendario_id=null, 
	$monitoramento_id=null, 
	$ata_id=null, 
	$swot_id=null, 
	$operativo_id=null,
	$instrumento_id=null,
	$recurso_id=null,
	$problema_id=null,
	$demanda_id=null,
	$programa_id=null,
	$licao_id=null,
	$link_id=null,
	$avaliacao_id=null,
	$tgn_id=null,
	$brainstorm_id=null,
	$gut_id=null,
	$causa_efeito_id=null,
	$arquivo_id=null,
	$forum_id=null,
	$checklist_id=null,
	$agenda_id=null,
	$agrupamento_id=null,
	$patrocinador_id=null,
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null) {
	global $a, $Aplic, $config;
	
	$df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
	
	$db_inicio=$inicioPeriodo->format('%Y-%m-%d %H:%M:%S');
	$db_fim=$fimPeriodo->format('%Y-%m-%d %H:%M:%S');
	$tarefas_filtro = '';
	
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item','pai');
	$sql->esqUnir('plano_acao', 'pa', 'pa.plano_acao_id = pai.plano_acao_item_acao');
	if ($usuario_id) $sql->esqUnir('plano_acao_item_designados', 'id', 'id.plano_acao_item_id=pai.plano_acao_item_id');
	if ($dept_id){
		$sql->esqUnir('plano_acao_item_depts', 'paid', 'pai.plano_acao_item_id = paid.plano_acao_item_id');
		$sql->esqUnir('depts', 'depts', 'depts.dept_id = paid.dept_id');
		}
	$sql->adCampo('DISTINCT pai.plano_acao_item_id, plano_acao_item_nome, plano_acao_nome, plano_acao_item_acesso, plano_acao_item_inicio, plano_acao_item_fim, plano_acao_cor AS cor, plano_acao_item_oque, plano_acao_id');
	$sql->adOnde('plano_acao_item_inicio <= \''.$db_fim.'\'');
	$sql->adOnde('plano_acao_item_fim >= \''.$db_inicio. '\'');
	$sql->adOnde('plano_acao_item_inicio IS NOT NULL');
	$sql->adOnde('plano_acao_item_fim IS NOT NULL');
	
	
	
	if ($Aplic->profissional){
		if ($plano_acao_id) $sql->adOnde('pa.plano_acao_id='.(int)$plano_acao_id);
		$sql->esqUnir('plano_acao_gestao', 'plano_acao_gestao', 'pa.plano_acao_id = plano_acao_gestao_acao');
		if ($tarefa_id) $sql->adOnde('plano_acao_gestao_tarefa='.(int)$tarefa_id);
		elseif ($projeto_id) $sql->adOnde('plano_acao_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id) $sql->adOnde('plano_acao_gestao_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($tema_id) $sql->adOnde('plano_acao_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id) $sql->adOnde('plano_acao_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id) $sql->adOnde('plano_acao_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id) $sql->adOnde('plano_acao_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id) $sql->adOnde('plano_acao_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id) $sql->adOnde('plano_acao_gestao_pratica='.(int)$pratica_id);
		elseif ($pratica_indicador_id) $sql->adOnde('plano_acao_gestao_indicador='.(int)$pratica_indicador_id);
		elseif ($canvas_id) $sql->adOnde('plano_acao_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id) $sql->adOnde('plano_acao_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id) $sql->adOnde('plano_acao_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id) $sql->adOnde('plano_acao_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id) $sql->adOnde('plano_acao_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id) $sql->adOnde('plano_acao_gestao_ata='.(int)$ata_id);
		elseif ($swot_id) $sql->adOnde('plano_acao_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id) $sql->adOnde('plano_acao_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id) $sql->adOnde('plano_acao_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id) $sql->adOnde('plano_acao_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id) $sql->adOnde('plano_acao_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id) $sql->adOnde('plano_acao_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id) $sql->adOnde('plano_acao_gestao_programa='.(int)$programa_id);
		elseif ($licao_id) $sql->adOnde('plano_acao_gestao_licao='.(int)$licao_id);
		elseif ($link_id) $sql->adOnde('plano_acao_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id) $sql->adOnde('plano_acao_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id) $sql->adOnde('plano_acao_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id) $sql->adOnde('plano_acao_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id) $sql->adOnde('plano_acao_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id) $sql->adOnde('plano_acao_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($arquivo_id) $sql->adOnde('plano_acao_gestao_arquivo='.(int)$arquivo_id);
		elseif ($forum_id) $sql->adOnde('plano_acao_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id) $sql->adOnde('plano_acao_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id) $sql->adOnde('plano_acao_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id) $sql->adOnde('plano_acao_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id) $sql->adOnde('plano_acao_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id) $sql->adOnde('plano_acao_gestao_template='.(int)$template_id);
		elseif ($painel_id) $sql->adOnde('plano_acao_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id) $sql->adOnde('plano_acao_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id) $sql->adOnde('plano_acao_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id) $sql->adOnde('plano_acao_gestao_tr='.(int)$tr_id);
		elseif ($me_id) $sql->adOnde('plano_acao_gestao_me='.(int)$me_id);
		}
	else{
		if ($projeto_id) $sql->adOnde('plano_acao_projeto='.(int)$projeto_id);
		if ($pratica_id) $sql->adOnde('plano_acao_pratica='.(int)$pratica_id);
		if ($pratica_indicador_id) $sql->adOnde('plano_acao_indicador='.(int)$pratica_indicador_id);
		if ($tema_id) $sql->adOnde('plano_acao_tema='.(int)$tema_id);
		if ($pg_objetivo_estrategico_id) $sql->adOnde('plano_acao_objetivo='.(int)$pg_objetivo_estrategico_id);
		if ($pg_estrategia_id) $sql->adOnde('plano_acao_estrategia='.(int)$pg_estrategia_id);
		if ($pg_meta_id) $sql->adOnde('plano_acao_meta='.(int)$pg_meta_id);
		if ($pg_fator_critico_id) $sql->adOnde('plano_acao_fator='.(int)$pg_fator_critico_id);
		}
	
	
	if ($usuario_id) $sql->adOnde('(id.usuario_id='.(int)$usuario_id.' OR plano_acao_item_responsavel='.(int)$usuario_id.')');
	if ($cia_id) $sql->adOnde('plano_acao_cia IN ('.$cia_id.')');
	if ($plano_acao_id) $sql->adOnde('pa.plano_acao_id = '.(int)$plano_acao_id);
	if ($dept_id) $sql->adOnde('paid.dept_id IN ('.$dept_id.')');
	$sql->adOrdem('plano_acao_item_inicio');
	$itens = $sql->Lista();
	$sql->limpar();

	$link = array();
	foreach ($itens as $linha) {
		
		if (permiteAcessarPlanoAcaoItem($linha['plano_acao_item_acesso'], $linha['plano_acao_item_id'])){	

			$inicio = new CData($linha['plano_acao_item_inicio']);
			$fim = new CData($linha['plano_acao_item_fim']);

			$data = $inicio;
			for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
				$meio=false;
				if ($data->format($df)==$inicio->format($df) && $data->format($df)==$fim->format($df)) $inicio_fim=imagem('icones/inicio.gif').$inicio->format($tf).imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$fim->format($tf).imagem('icones/fim.gif');
				elseif ($data->format($df)==$inicio->format($df)) $inicio_fim=imagem('icones/inicio.gif').$inicio->format($tf).imagem('icones/vazio.gif');
				elseif ($data->format($df)==$fim->format($df)) $inicio_fim=imagem('icones/vazio.gif').$fim->format($tf).imagem('icones/fim.gif');
				else {
					$meio=true;
					$inicio_fim=imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
					}
				$texto='<tr valign=middle><td>'.$inicio_fim.'</td><td>'.imagem('icones/plano_acao_p.gif').($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque']).'</td></tr>';
				if ($minutoiCal) $link = array('acao' => true, 'texto_mini' => $texto);
				else {
					$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_ver&plano_acao_id='.(int)$linha['plano_acao_id'].'\');">'.$inicio_fim.'</a></td><td valign=middle>'.imagem('icones/plano_acao_p.gif').link_acao_item($linha['plano_acao_item_id'],'','',$strMaxLarg).'</td></tr>';
					$link['texto_mini']=$texto;
					$link['acao']=true;
					}	
				if ($periodo_todo || !$meio) $links[$data->format('%Y%m%d')][] = $link;
	
				$data = $data->getNextDay();
				}
			}	
		
		}
	return $links;
	}


function getTarefaLinks($periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal = false, $usuario_id=0, $cia_id = '', $dept_id='', 
	$tarefa_id=null,  
	$projeto_id=null,  
	$pg_perspectiva_id=null,  
	$tema_id=null,  
	$pg_objetivo_estrategico_id=null,  
	$pg_fator_critico_id=null,  
	$pg_estrategia_id=null, 
	$pg_meta_id=null,  
	$pratica_id=null,  
	$pratica_indicador_id=null,  
	$plano_acao_id=null,  
	$canvas_id=null,  
	$risco_id=null, 
	$risco_resposta_id=null, 
	$calendario_id=null,  
	$monitoramento_id=null,  
	$ata_id=null,  
	$swot_id=null,  
	$operativo_id=null, 
	$instrumento_id=null, 
	$recurso_id=null, 
	$problema_id=null, 
	$demanda_id=null, 
	$programa_id=null, 
	$licao_id=null, 
	$link_id=null, 
	$avaliacao_id=null, 
	$tgn_id=null, 
	$brainstorm_id=null, 
	$gut_id=null, 
	$causa_efeito_id=null, 
	$arquivo_id=null, 
	$forum_id=null, 
	$checklist_id=null, 
	$agenda_id=null, 
	$agrupamento_id=null, 
	$patrocinador_id=null, 
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null) {
	global $a, $Aplic, $config;
	require_once ($Aplic->getClasseModulo('tarefas'));
	$tarefas = CTarefa::getTarefasParaPeriodo($inicioPeriodo, $fimPeriodo, $usuario_id, $cia_id, $dept_id, 
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
	
  $df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
        
	$link = array();
    
    $dtInicio = $inicioPeriodo->format('%Y-%m-%d');
    $dtFim = $fimPeriodo->format('%Y-%m-%d');
    
    if(!$periodo_todo){
        //somente inicio e fim
        foreach ($tarefas as $linha) {
            if (permiteAcessar($linha['tarefa_acesso'], $linha['projeto_id'], $linha['tarefa_id'])){
                $inicio = substr($linha['tarefa_inicio'], 0,10);
                $fim = substr($linha['tarefa_fim'], 0,10);
                if($dtInicio > $inicio) $inicio = null;
                if($dtFim < $fim) $fim = null;

                if($inicio || $fim){
                    if($fim == $inicio){
                        //inicio e fim no mesmo dia
                        $dataIni = new CData($linha['tarefa_inicio']);
                        $dataFim = new CData($linha['tarefa_fim']);
                        $imagem = imagem('icones/inicio.gif').$dataIni->format($tf).imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$dataFim->format($tf).imagem('icones/fim.gif');
                        $texto='<tr valign=middle><td>'.$imagem.'</td><td>'.$linha['tarefa_nome'].'</td></tr>';
                        if ($minutoiCal) $link = array('tarefa' => true, 'texto_mini' => $texto);
                        else {
                            $link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$linha['tarefa_id'].'\');">'.$imagem.'</a></td><td valign=middle>'.link_tarefa($linha['tarefa_id'],'','',$strMaxLarg).'</td></tr>';
                            $link['texto_mini']=$texto;
                            $link['tarefa']=true;
                            }
                        $links[$dataIni->format('%Y%m%d')][] = $link;
                        }
                    else{
                        if($inicio){
                            $data = new CData($linha['tarefa_inicio']);
                            $imagem = imagem('icones/inicio.gif').$data->format($tf).imagem('icones/vazio.gif');
                            $texto='<tr valign=middle><td>'.$imagem.'</td><td>'.$linha['tarefa_nome'].'</td></tr>';
                            if ($minutoiCal) $link = array('tarefa' => true, 'texto_mini' => $texto);
                            else {
                                $link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$linha['tarefa_id'].'\');">'.$imagem.'</a></td><td valign=middle>'.link_tarefa($linha['tarefa_id'],'','',$strMaxLarg).'</td></tr>';
                                $link['texto_mini'] =$texto;
                                $link['tarefa']=true;
                                }
                                
                            $links[$data->format('%Y%m%d')][] = $link;
                            }
                            
                        if($fim){
                            $data = new CData($linha['tarefa_fim']);
                            $imagem = imagem('icones/vazio.gif').$data->format($tf).imagem('icones/fim.gif');
                            $texto='<tr valign=middle><td>'.$imagem.'</td><td>'.$linha['tarefa_nome'].'</td></tr>';
                            if ($minutoiCal) $link = array('tarefa' => true, 'texto_mini' => $texto);
                            else {
                                $link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$linha['tarefa_id'].'\');">'.$imagem.'</a></td><td valign=middle>'.link_tarefa($linha['tarefa_id'],'','',$strMaxLarg).'</td></tr>';
                                $link['texto_mini']=$texto;
                                $link['tarefa']=true;
                                }
                                
                            $links[$data->format('%Y%m%d')][] = $link;
                            }
                        }
                    }
                }
            }
        }
    else{
        //todos os dias
        foreach ($tarefas as $linha) {
            if (permiteAcessar($linha['tarefa_acesso'], $linha['projeto_id'], $linha['tarefa_id'])){
                $inicio = substr($linha['tarefa_inicio'], 0,10);
                $fim = substr($linha['tarefa_fim'], 0,10);
                
                $inicioReal = $inicio;
                $fimReal = $fim;
                
                if($dtInicio > $inicio) $inicio = $dtInicio;
                if($dtFim < $fim) $fim = $dtFim;
                
                $dataObj = new CData($inicio);
                $data = $dataObj->format('%Y-%m-%d');
                while($data <= $fim){                   
                    if($data == $inicioReal && $data == $fimReal){
                        //inicio e fim no mesmo dia
                        $dataIni = new CData($linha['tarefa_inicio']);
                        $dataFim = new CData($linha['tarefa_fim']);
                        $imagem = imagem('icones/inicio.gif').$dataIni->format($tf).imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$dataFim->format($tf).imagem('icones/fim.gif');
                        }
                    else if($data == $inicioReal){
                        $data = new CData($linha['tarefa_inicio']);
                        $imagem = imagem('icones/inicio.gif').$data->format($tf).imagem('icones/vazio.gif');
                        }
                    else if($data == $fimReal){
                        $data = new CData($linha['tarefa_fim']);
                        $imagem = imagem('icones/vazio.gif').$data->format($tf).imagem('icones/fim.gif');
                        }
                    else{
                        //no meio da tarefa
                        $imagem = imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
                        }
                        
                    $texto='<tr valign=middle><td>'.$imagem.'</td><td>'.$linha['tarefa_nome'].'</td></tr>';
                    if ($minutoiCal) $link = array('tarefa' => true, 'texto_mini' => $texto);
                    else {
                        $link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$linha['tarefa_id'].'\');">'.$imagem.'</a></td><td valign=middle>'.link_tarefa($linha['tarefa_id'],'','',$strMaxLarg).'</td></tr>';
                        $link['texto_mini']=$texto;
                        $link['tarefa']=true;
                        }
                        
                    $links[$dataObj->format('%Y%m%d')][] = $link;
                    
                    $dataObj = $dataObj->getNextDay();
                    $data = $dataObj->format('%Y-%m-%d');
                    }
                }
            }
        }
    
	
	return $links;
	}



?>