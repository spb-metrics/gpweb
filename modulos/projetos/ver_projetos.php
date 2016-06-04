<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $pg_perspectiva_id, 
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

$ordenar = getParam($_REQUEST, 'ordenar', 'pratica_indicador_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql = new BDConsulta;


echo '<table cellpadding=0 cellspacing=0 width="100%" class="tbl1">';
echo '<tr><th></th><th>Cor</th><th>Nome</th><th>Físico</th><th>'.ucfirst($config['organizacao']).'</th><th>Início</th><th>Término</th><th>'.ucfirst($config['gerente']).'</th>'.($Aplic->profissional ? '<th>Relacionad'.$config['genero_projeto'].'</th>' : '').'</tr>';
$sql->adTabela('projetos');
$sql->esqUnir('projeto_gestao','projeto_gestao', 'projetos.projeto_id=projeto_gestao_projeto');
$sql->adCampo('projetos.*');
if ($pg_perspectiva_id) $sql->adOnde('projeto_gestao_perspectiva='.$pg_perspectiva_id);
elseif ($tema_id) $sql->adOnde('projeto_gestao_tema='.(int)$tema_id);
elseif ($pg_objetivo_estrategico_id) $sql->adOnde('projeto_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
elseif ($pg_fator_critico_id) $sql->adOnde('projeto_gestao_fator='.(int)$pg_fator_critico_id);
elseif ($pg_estrategia_id) $sql->adOnde('projeto_gestao_estrategia='.(int)$pg_estrategia_id);
elseif ($pg_meta_id) $sql->adOnde('projeto_gestao_meta='.(int)$pg_meta_id);
elseif ($pratica_id) $sql->adOnde('projeto_gestao_pratica='.(int)$pratica_id);
elseif ($pratica_indicador_id) $sql->adOnde('projeto_gestao_indicador='.(int)$pratica_indicador_id);
elseif ($plano_acao_id) $sql->adOnde('projeto_gestao_acao='.(int)$plano_acao_id);
elseif ($canvas_id) $sql->adOnde('projeto_gestao_canvas='.(int)$canvas_id);
elseif ($risco_id) $sql->adOnde('projeto_gestao_risco='.(int)$risco_id);
elseif ($risco_resposta_id) $sql->adOnde('projeto_gestao_risco_resposta='.(int)$risco_resposta_id);
elseif ($calendario_id) $sql->adOnde('projeto_gestao_calendario='.(int)$calendario_id);
elseif ($monitoramento_id) $sql->adOnde('projeto_gestao_monitoramento='.(int)$monitoramento_id);
elseif ($ata_id) $sql->adOnde('projeto_gestao_ata='.(int)$ata_id);
elseif ($swot_id) $sql->adOnde('projeto_gestao_swot='.(int)$swot_id);
elseif ($operativo_id) $sql->adOnde('projeto_gestao_operativo='.(int)$operativo_id);
elseif ($instrumento_id) $sql->adOnde('projeto_gestao_instrumento='.(int)$instrumento_id);
elseif ($recurso_id) $sql->adOnde('projeto_gestao_recurso='.(int)$recurso_id);
elseif ($problema_id) $sql->adOnde('projeto_gestao_problema='.(int)$problema_id);
elseif ($demanda_id) $sql->adOnde('projeto_gestao_demanda='.(int)$demanda_id);
elseif ($programa_id) $sql->adOnde('projeto_gestao_programa='.(int)$programa_id);
elseif ($licao_id) $sql->adOnde('projeto_gestao_licao='.(int)$licao_id);
elseif ($evento_id) $sql->adOnde('projeto_gestao_evento='.(int)$evento_id);
elseif ($link_id) $sql->adOnde('projeto_gestao_link='.(int)$link_id);
elseif ($avaliacao_id) $sql->adOnde('projeto_gestao_avaliacao='.(int)$avaliacao_id);
elseif ($tgn_id) $sql->adOnde('projeto_gestao_tgn='.(int)$tgn_id);
elseif ($brainstorm_id) $sql->adOnde('projeto_gestao_brainstorm='.(int)$brainstorm_id);
elseif ($gut_id) $sql->adOnde('projeto_gestao_gut='.(int)$gut_id);
elseif ($causa_efeito_id) $sql->adOnde('projeto_gestao_causa_efeito='.(int)$causa_efeito_id);
elseif ($arquivo_id) $sql->adOnde('projeto_gestao_arquivo='.(int)$arquivo_id);
elseif ($forum_id) $sql->adOnde('projeto_gestao_forum='.(int)$forum_id);
elseif ($checklist_id) $sql->adOnde('projeto_gestao_checklist='.(int)$checklist_id);
elseif ($agenda_id) $sql->adOnde('projeto_gestao_agenda='.(int)$agenda_id);
elseif ($agrupamento_id) $sql->adOnde('projeto_gestao_agrupamento='.(int)$agrupamento_id);
elseif ($patrocinador_id) $sql->adOnde('projeto_gestao_patrocinador='.(int)$patrocinador_id);
elseif ($template_id) $sql->adOnde('projeto_gestao_template='.(int)$template_id);
elseif ($painel_id) $sql->adOnde('projeto_gestao_painel='.(int)$painel_id);
elseif ($painel_odometro_id) $sql->adOnde('projeto_gestao_painel_odometro='.(int)$painel_odometro_id);
elseif ($painel_composicao_id) $sql->adOnde('projeto_gestao_painel_composicao='.(int)$painel_composicao_id);
elseif ($tr_id) $sql->adOnde('projeto_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('projeto_gestao_me='.(int)$me_id);
$sql->adOrdem('projeto_nome');
$sql->adGrupo('projeto_id');
$projetos=$sql->lista();
$sql->limpar();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'projetos\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'projetos\'');
$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
$diff = array_diff_key($exibir, $exibir2);
if($diff) $exibir = array_merge($exibir2, $diff);
else $exibir = $exibir2;

$projStatus = getSisValor('StatusProjeto');
$qnt=0;
foreach ($projetos as $linha){
	if ($Aplic->usuario_super_admin || permiteAcessar($linha['projeto_acesso'], $linha['projeto_id'])){
		$qnt++;
		$nenhum = false;
		$editar = permiteEditar($linha['projeto_acesso'], $linha['projeto_id']);
		$data_inicio = intval($linha['projeto_data_inicio']) ? new CData($linha['projeto_data_inicio']) : null;
		$data_fim = intval($linha['projeto_data_fim']) ? new CData($linha['projeto_data_fim']) : null;
		$data_fim_atual = intval($linha['projeto_fim_atualizado']) ? new CData($linha['projeto_fim_atualizado']) : null;
		$estilo = (($data_fim_atual > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';
		echo '<tr id="projeto_'.$linha['projeto_id'].'" onmouseover="iluminar_tds(this, true, '.$linha['projeto_id'].')" onmouseout="iluminar_tds(this, false, '.$linha['projeto_id'].')" onclick="selecionar_projeto('.$linha['projeto_id'].')">';
		
		echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_projeto'].' '.$config['projeto'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=editar&projeto_id='.$linha['projeto_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';

		
		if ($exibir['cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_cor'].'"><font color="'.melhorCor($linha['projeto_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_projeto($linha["projeto_id"],'','','','','',true).'</td>';
		echo '<td width="45" align="right">'.sprintf('%.1f%%', $linha['projeto_percentagem']).'</td>';		
		echo '<td>'.link_cia($linha['projeto_cia']).'</td>';
		echo '<td width="80px" nowrap="nowrap" align="center">'.($data_inicio ? $data_inicio->format("%d/%m/%Y") : '&nbsp;').'</td>';
		echo '<td width="80px" nowrap="nowrap" align="center">'.($data_fim ? $data_fim->format("%d/%m/%Y") : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['projeto_responsavel'],'','','esquerda').'</td>';
		
		if ($Aplic->profissional){
			$sql->adTabela('projeto_gestao');
			$sql->adCampo('projeto_gestao.*');
			$sql->adOnde('projeto_gestao_projeto ='.(int)$linha['projeto_id']);
			$sql->adOrdem('projeto_gestao_ordem');
		  $gestao = $sql->Lista();
		  $sql->Limpar();
		  $usado=0;
			echo '<td align="left">';
			foreach($gestao as $gestao_data){
				if ($gestao_data['projeto_gestao_pratica']) echo ($usado++? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_gestao_pratica']);
				elseif ($gestao_data['projeto_gestao_acao']) echo ($usado++? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_gestao_acao']);
				elseif ($gestao_data['projeto_gestao_perspectiva']) echo ($usado++? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_gestao_perspectiva']);
				elseif ($gestao_data['projeto_gestao_tema']) echo ($usado++? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['projeto_gestao_tema']);
				elseif ($gestao_data['projeto_gestao_objetivo']) echo ($usado++? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_gestao_objetivo']);
				elseif ($gestao_data['projeto_gestao_fator']) echo ($usado++? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_gestao_fator']);
				elseif ($gestao_data['projeto_gestao_estrategia']) echo ($usado++? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_gestao_estrategia']);
				elseif ($gestao_data['projeto_gestao_meta']) echo ($usado++? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_gestao_meta']);
				elseif ($gestao_data['projeto_gestao_canvas']) echo ($usado++? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_gestao_canvas']);
				elseif ($gestao_data['projeto_gestao_risco']) echo ($usado++? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['projeto_gestao_risco']);
				elseif ($gestao_data['projeto_gestao_risco_resposta']) echo ($usado++? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_gestao_risco_resposta']);
				elseif ($gestao_data['projeto_gestao_indicador']) echo ($usado++? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_gestao_indicador']);
				elseif ($gestao_data['projeto_gestao_calendario']) echo ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['projeto_gestao_calendario']);
				elseif ($gestao_data['projeto_gestao_monitoramento']) echo ($usado++? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_gestao_monitoramento']);
				elseif ($gestao_data['projeto_gestao_ata']) echo ($usado++? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['projeto_gestao_ata']);
				elseif ($gestao_data['projeto_gestao_swot']) echo ($usado++? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['projeto_gestao_swot']);
				elseif ($gestao_data['projeto_gestao_operativo']) echo ($usado++? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_gestao_operativo']);
				elseif ($gestao_data['projeto_gestao_instrumento']) echo ($usado++? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_gestao_instrumento']);
				elseif ($gestao_data['projeto_gestao_recurso']) echo ($usado++? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_gestao_recurso']);
				elseif ($gestao_data['projeto_gestao_problema']) echo ($usado++? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['projeto_gestao_problema']);
				elseif ($gestao_data['projeto_gestao_demanda']) echo ($usado++? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_gestao_demanda']);
				elseif ($gestao_data['projeto_gestao_programa']) echo ($usado++? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['projeto_gestao_programa']);
				elseif ($gestao_data['projeto_gestao_licao']) echo ($usado++? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_gestao_licao']);
				elseif ($gestao_data['projeto_gestao_evento']) echo ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_gestao_evento']);
				elseif ($gestao_data['projeto_gestao_link']) echo ($usado++? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['projeto_gestao_link']);
				elseif ($gestao_data['projeto_gestao_avaliacao']) echo ($usado++? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_gestao_avaliacao']);
				elseif ($gestao_data['projeto_gestao_tgn']) echo ($usado++? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_gestao_tgn']);
				elseif ($gestao_data['projeto_gestao_brainstorm']) echo ($usado++? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['projeto_gestao_brainstorm']);
				elseif ($gestao_data['projeto_gestao_gut']) echo ($usado++? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['projeto_gestao_gut']);
				elseif ($gestao_data['projeto_gestao_causa_efeito']) echo ($usado++? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['projeto_gestao_causa_efeito']);
				elseif ($gestao_data['projeto_gestao_arquivo']) echo ($usado++? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_gestao_arquivo']);
				elseif ($gestao_data['projeto_gestao_forum']) echo ($usado++? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_gestao_forum']);
				elseif ($gestao_data['projeto_gestao_checklist']) echo ($usado++? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_gestao_checklist']);
				elseif ($gestao_data['projeto_gestao_agenda']) echo ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['projeto_gestao_agenda']);
				elseif ($gestao_data['projeto_gestao_agrupamento']) echo ($usado++? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['projeto_gestao_agrupamento']);
				elseif ($gestao_data['projeto_gestao_patrocinador']) echo ($usado++? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_gestao_patrocinador']);
				elseif ($gestao_data['projeto_gestao_template']) echo ($usado++? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['projeto_gestao_template']);
				elseif ($gestao_data['projeto_gestao_painel']) echo ($usado++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['projeto_gestao_painel']);
				elseif ($gestao_data['projeto_gestao_painel_odometro']) echo ($usado++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_gestao_painel_odometro']);
				elseif ($gestao_data['projeto_gestao_painel_composicao']) echo ($usado++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['projeto_gestao_painel_composicao']);
				elseif ($gestao_data['projeto_gestao_tr']) echo ($usado++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['projeto_gestao_tr']);
				elseif ($gestao_data['projeto_gestao_me']) echo ($usado++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['projeto_gestao_me']);


				}
			echo '</td>';
			}
		
		echo '</tr>';
		}
	}
if (!$qnt) echo '<tr><td colspan=20>Nenhum'.($config['genero_projeto']=='a' ? 'a' :'').' '.$config['projeto'].' vinculad'.$config['genero_projeto'].'</td></tr>';	
echo '</table>';