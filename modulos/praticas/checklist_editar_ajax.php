<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

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

function exibir_cias($cias){
	global $config;
	$cias_selecionadas=explode(',', $cias);
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0>';
			$saida_cias.= '<tr><td class="texto" style="width:400px;">'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';		
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			} 
	else 	$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_cias',"innerHTML", utf8_encode($saida_cias));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_cias");	

function mudar_posicao_gestao($ordem, $checklist_gestao_id, $direcao, $checklist_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $checklist_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('checklist_gestao');
		$sql->adOnde('checklist_gestao_id != '.(int)$checklist_gestao_id);
		if ($uuid) $sql->adOnde('checklist_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('checklist_gestao_checklist = '.(int)$checklist_id);
		$sql->adOrdem('checklist_gestao_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('checklist_gestao');
			$sql->adAtualizar('checklist_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('checklist_gestao_id = '.(int)$checklist_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('checklist_gestao');
					$sql->adAtualizar('checklist_gestao_ordem', $idx);
					$sql->adOnde('checklist_gestao_id = '.(int)$acao['checklist_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('checklist_gestao');
					$sql->adAtualizar('checklist_gestao_ordem', $idx + 1);
					$sql->adOnde('checklist_gestao_id = '.(int)$acao['checklist_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($checklist_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$checklist_id=0, 
	$uuid='',  
	$checklist_projeto=null,  
	$checklist_tarefa=null,  
	$checklist_perspectiva=null,
	$checklist_tema=null, 
	$checklist_objetivo=null, 
	$checklist_fator=null, 
	$checklist_estrategia=null, 
	$checklist_meta=null,
	$checklist_pratica=null,  
	$checklist_acao=null, 
	$checklist_canvas=null, 
	$checklist_risco=null, 
	$checklist_risco_resposta=null,
	$checklist_indicador=null,
	$checklist_calendario=null,
	$checklist_monitoramento=null,
	$checklist_ata=null,
	$checklist_swot=null,
	$checklist_operativo=null,
	$checklist_instrumento=null,
	$checklist_recurso=null,
	$checklist_problema=null,
	$checklist_demanda=null,
	$checklist_programa=null,
	$checklist_licao=null,
	$checklist_evento=null,
	$checklist_link=null,
	$checklist_avaliacao=null,
	$checklist_tgn=null,
	$checklist_brainstorm=null,
	$checklist_gut=null,
	$checklist_causa_efeito=null,
	$checklist_arquivo=null,
	$checklist_forum=null,
	$checklist_agenda=null,
	$checklist_agrupamento=null,
	$checklist_patrocinador=null,
	$checklist_template=null
	)
	{
	if (
		$checklist_projeto || 
		$checklist_tarefa ||  
		$checklist_tema || 
		$checklist_objetivo || 
		$checklist_fator || 
		$checklist_estrategia || 
		$checklist_acao || 
		$checklist_pratica || 
		$checklist_meta || 
		$checklist_perspectiva || 
		$checklist_canvas || 
		$checklist_risco || 
		$checklist_risco_resposta ||
		$checklist_indicador ||
		$checklist_calendario ||
		$checklist_monitoramento ||
		$checklist_ata ||
		$checklist_swot ||
		$checklist_operativo ||
		$checklist_instrumento ||
		$checklist_recurso ||
		$checklist_problema ||
		$checklist_demanda ||
		$checklist_programa ||
		$checklist_licao ||
		$checklist_evento ||
		$checklist_link ||
		$checklist_avaliacao ||
		$checklist_tgn ||
		$checklist_brainstorm ||
		$checklist_gut ||
		$checklist_causa_efeito ||
		$checklist_arquivo ||
		$checklist_forum ||
		$checklist_agenda ||
		$checklist_agrupamento ||
		$checklist_patrocinador ||
		$checklist_template
		){
		$sql = new BDConsulta;
		
	//verificar se já não inseriu antes
		$sql->adTabela('checklist_gestao');
		$sql->adCampo('count(checklist_gestao_id)');
		if ($uuid) $sql->adOnde('checklist_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('checklist_gestao_checklist ='.(int)$checklist_id);	
		if ($checklist_tarefa) $sql->adOnde('checklist_gestao_tarefa='.(int)$checklist_tarefa);
		elseif ($checklist_projeto) $sql->adOnde('checklist_gestao_projeto='.(int)$checklist_projeto);
		elseif ($checklist_perspectiva) $sql->adOnde('checklist_gestao_perspectiva='.(int)$checklist_perspectiva);
		elseif ($checklist_tema) $sql->adOnde('checklist_gestao_tema='.(int)$checklist_tema);
		elseif ($checklist_objetivo) $sql->adOnde('checklist_gestao_objetivo='.(int)$checklist_objetivo);
		elseif ($checklist_fator) $sql->adOnde('checklist_gestao_fator='.(int)$checklist_fator);
		elseif ($checklist_estrategia) $sql->adOnde('checklist_gestao_estrategia='.(int)$checklist_estrategia);
		elseif ($checklist_acao) $sql->adOnde('checklist_gestao_acao='.(int)$checklist_acao);
		elseif ($checklist_pratica) $sql->adOnde('checklist_gestao_pratica='.(int)$checklist_pratica);
		elseif ($checklist_meta) $sql->adOnde('checklist_gestao_meta='.(int)$checklist_meta);
		elseif ($checklist_canvas) $sql->adOnde('checklist_gestao_canvas='.(int)$checklist_canvas);
		elseif ($checklist_risco) $sql->adOnde('checklist_gestao_risco='.(int)$checklist_risco);
		elseif ($checklist_risco_resposta) $sql->adOnde('checklist_gestao_risco_resposta='.(int)$checklist_risco_resposta);
		elseif ($checklist_indicador) $sql->adOnde('checklist_gestao_indicador='.(int)$checklist_indicador);
		elseif ($checklist_calendario) $sql->adOnde('checklist_gestao_calendario='.(int)$checklist_calendario);
		elseif ($checklist_monitoramento) $sql->adOnde('checklist_gestao_monitoramento='.(int)$checklist_monitoramento);
		elseif ($checklist_ata) $sql->adOnde('checklist_gestao_ata='.(int)$checklist_ata);
		elseif ($checklist_swot) $sql->adOnde('checklist_gestao_swot='.(int)$checklist_swot);
		elseif ($checklist_operativo) $sql->adOnde('checklist_gestao_operativo='.(int)$checklist_operativo);
		elseif ($checklist_instrumento) $sql->adOnde('checklist_gestao_instrumento='.(int)$checklist_instrumento);
		elseif ($checklist_recurso) $sql->adOnde('checklist_gestao_recurso='.(int)$checklist_recurso);
		elseif ($checklist_problema) $sql->adOnde('checklist_gestao_problema='.(int)$checklist_problema);
		elseif ($checklist_demanda) $sql->adOnde('checklist_gestao_demanda='.(int)$checklist_demanda);
		elseif ($checklist_programa) $sql->adOnde('checklist_gestao_programa='.(int)$checklist_programa);
		elseif ($checklist_licao) $sql->adOnde('checklist_gestao_licao='.(int)$checklist_licao);
		elseif ($checklist_evento) $sql->adOnde('checklist_gestao_evento='.(int)$checklist_evento);
		elseif ($checklist_link) $sql->adOnde('checklist_gestao_link='.(int)$checklist_link);
		elseif ($checklist_avaliacao) $sql->adOnde('checklist_gestao_avaliacao='.(int)$checklist_avaliacao);
		elseif ($checklist_tgn) $sql->adOnde('checklist_gestao_tgn='.(int)$checklist_tgn);
		elseif ($checklist_brainstorm) $sql->adOnde('checklist_gestao_brainstorm='.(int)$checklist_brainstorm);
		elseif ($checklist_gut) $sql->adOnde('checklist_gestao_gut='.(int)$checklist_gut);
		elseif ($checklist_causa_efeito) $sql->adOnde('checklist_gestao_causa_efeito='.(int)$checklist_causa_efeito);
		elseif ($checklist_arquivo) $sql->adOnde('checklist_gestao_arquivo='.(int)$checklist_arquivo);
		elseif ($checklist_forum) $sql->adOnde('checklist_gestao_forum='.(int)$checklist_forum);
		elseif ($checklist_agenda) $sql->adOnde('checklist_gestao_agenda='.(int)$checklist_agenda);
		elseif ($checklist_agrupamento) $sql->adOnde('checklist_gestao_agrupamento='.(int)$checklist_agrupamento);
		elseif ($checklist_patrocinador) $sql->adOnde('checklist_gestao_patrocinador='.(int)$checklist_patrocinador);
		elseif ($checklist_template) $sql->adOnde('checklist_gestao_template='.(int)$checklist_template);
	  $existe = $sql->Resultado();
	  $sql->Limpar();
		if (!$existe){
			$sql->adTabela('checklist_gestao');
			$sql->adCampo('MAX(checklist_gestao_ordem)');
			if ($uuid) $sql->adOnde('checklist_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('checklist_gestao_checklist ='.(int)$checklist_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->Limpar();
			$sql->adTabela('checklist_gestao');
			if ($uuid) $sql->adInserir('checklist_gestao_uuid', $uuid);
			else $sql->adInserir('checklist_gestao_checklist', (int)$checklist_id);
			
			if ($checklist_tarefa) $sql->adInserir('checklist_gestao_tarefa', (int)$checklist_tarefa);
			if ($checklist_projeto) $sql->adInserir('checklist_gestao_projeto', (int)$checklist_projeto);
			elseif ($checklist_perspectiva) $sql->adInserir('checklist_gestao_perspectiva', (int)$checklist_perspectiva);
			elseif ($checklist_tema) $sql->adInserir('checklist_gestao_tema', (int)$checklist_tema);
			elseif ($checklist_objetivo) $sql->adInserir('checklist_gestao_objetivo', (int)$checklist_objetivo);
			elseif ($checklist_fator) $sql->adInserir('checklist_gestao_fator', (int)$checklist_fator);
			elseif ($checklist_estrategia) $sql->adInserir('checklist_gestao_estrategia', (int)$checklist_estrategia);
			elseif ($checklist_acao) $sql->adInserir('checklist_gestao_acao', (int)$checklist_acao);
			elseif ($checklist_pratica) $sql->adInserir('checklist_gestao_pratica', (int)$checklist_pratica);
			elseif ($checklist_meta) $sql->adInserir('checklist_gestao_meta', (int)$checklist_meta);
			elseif ($checklist_canvas) $sql->adInserir('checklist_gestao_canvas', (int)$checklist_canvas);
			elseif ($checklist_risco) $sql->adInserir('checklist_gestao_risco', (int)$checklist_risco);
			elseif ($checklist_risco_resposta) $sql->adInserir('checklist_gestao_risco_resposta', (int)$checklist_risco_resposta);
			elseif ($checklist_indicador) $sql->adInserir('checklist_gestao_indicador', (int)$checklist_indicador);
			elseif ($checklist_calendario) $sql->adInserir('checklist_gestao_calendario', (int)$checklist_calendario);
			elseif ($checklist_monitoramento) $sql->adInserir('checklist_gestao_monitoramento', (int)$checklist_monitoramento);
			elseif ($checklist_ata) $sql->adInserir('checklist_gestao_ata', (int)$checklist_ata);
			elseif ($checklist_swot) $sql->adInserir('checklist_gestao_swot', (int)$checklist_swot);
			elseif ($checklist_operativo) $sql->adInserir('checklist_gestao_operativo', (int)$checklist_operativo);
			elseif ($checklist_instrumento) $sql->adInserir('checklist_gestao_instrumento', (int)$checklist_instrumento);
			elseif ($checklist_recurso) $sql->adInserir('checklist_gestao_recurso', (int)$checklist_recurso);
			elseif ($checklist_problema) $sql->adInserir('checklist_gestao_problema', (int)$checklist_problema);
			elseif ($checklist_demanda) $sql->adInserir('checklist_gestao_demanda', (int)$checklist_demanda);
			elseif ($checklist_programa) $sql->adInserir('checklist_gestao_programa', (int)$checklist_programa);
			elseif ($checklist_licao) $sql->adInserir('checklist_gestao_licao', (int)$checklist_licao);
			elseif ($checklist_evento) $sql->adInserir('checklist_gestao_evento', (int)$checklist_evento);
			elseif ($checklist_link) $sql->adInserir('checklist_gestao_link', (int)$checklist_link);
			elseif ($checklist_avaliacao) $sql->adInserir('checklist_gestao_avaliacao', (int)$checklist_avaliacao);
			elseif ($checklist_tgn) $sql->adInserir('checklist_gestao_tgn', (int)$checklist_tgn);
			elseif ($checklist_brainstorm) $sql->adInserir('checklist_gestao_brainstorm', (int)$checklist_brainstorm);
			elseif ($checklist_gut) $sql->adInserir('checklist_gestao_gut', (int)$checklist_gut);
			elseif ($checklist_causa_efeito) $sql->adInserir('checklist_gestao_causa_efeito', (int)$checklist_causa_efeito);
			elseif ($checklist_arquivo) $sql->adInserir('checklist_gestao_arquivo', (int)$checklist_arquivo);
			elseif ($checklist_forum) $sql->adInserir('checklist_gestao_forum', (int)$checklist_forum);
			elseif ($checklist_agenda) $sql->adInserir('checklist_gestao_agenda', (int)$checklist_agenda);
			elseif ($checklist_agrupamento) $sql->adInserir('checklist_gestao_agrupamento', (int)$checklist_agrupamento);
			elseif ($checklist_patrocinador) $sql->adInserir('checklist_gestao_patrocinador', (int)$checklist_patrocinador);
			elseif ($checklist_template) $sql->adInserir('checklist_gestao_template', (int)$checklist_template);
			$sql->adInserir('checklist_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->Limpar();
	
			$saida=atualizar_gestao($checklist_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($checklist_id=0, $uuid='', $checklist_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('checklist_gestao');
	$sql->adOnde('checklist_gestao_id='.(int)$checklist_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($checklist_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($checklist_id=0, $uuid=''){	
	$saida=atualizar_gestao($checklist_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($checklist_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('checklist_gestao');
	$sql->adCampo('checklist_gestao.*');
	if ($uuid) $sql->adOnde('checklist_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('checklist_gestao_checklist ='.(int)$checklist_id);	
	$sql->adOrdem('checklist_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td nowrap="nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
		if ($gestao_data['checklist_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['checklist_gestao_tarefa']).'</td>';
		else if ($gestao_data['checklist_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['checklist_gestao_projeto']).'</td>';
		elseif ($gestao_data['checklist_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['checklist_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['checklist_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['checklist_gestao_tema']).'</td>';
		elseif ($gestao_data['checklist_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['checklist_gestao_meta']).'</td>';
		elseif ($gestao_data['checklist_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['checklist_gestao_acao']).'</td>';
		elseif ($gestao_data['checklist_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['checklist_gestao_fator']).'</td>';
		elseif ($gestao_data['checklist_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['checklist_gestao_objetivo']).'</td>';
		elseif ($gestao_data['checklist_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['checklist_gestao_pratica']).'</td>';
		elseif ($gestao_data['checklist_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['checklist_gestao_estrategia']).'</td>';
		elseif ($gestao_data['checklist_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['checklist_gestao_canvas']).'</td>';
		elseif ($gestao_data['checklist_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['checklist_gestao_risco']).'</td>';
		elseif ($gestao_data['checklist_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['checklist_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['checklist_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['checklist_gestao_indicador']).'</td>';
		elseif ($gestao_data['checklist_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_calendario($gestao_data['checklist_gestao_calendario']).'</td>';
		elseif ($gestao_data['checklist_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['checklist_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['checklist_gestao_ata']) $saida.= '<td align=left>'.imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['checklist_gestao_ata']).'</td>';
		elseif ($gestao_data['checklist_gestao_swot']) $saida.= '<td align=left>'.imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['checklist_gestao_swot']).'</td>';
		elseif ($gestao_data['checklist_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['checklist_gestao_operativo']).'</td>';
		elseif ($gestao_data['checklist_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['checklist_gestao_instrumento']).'</td>';
		elseif ($gestao_data['checklist_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['checklist_gestao_recurso']).'</td>';
		elseif ($gestao_data['checklist_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema_pro($gestao_data['checklist_gestao_problema']).'</td>';
		elseif ($gestao_data['checklist_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['checklist_gestao_demanda']).'</td>';
		elseif ($gestao_data['checklist_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['checklist_gestao_programa']).'</td>';
		elseif ($gestao_data['checklist_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['checklist_gestao_licao']).'</td>';
		elseif ($gestao_data['checklist_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['checklist_gestao_evento']).'</td>';
		elseif ($gestao_data['checklist_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['checklist_gestao_link']).'</td>';
		elseif ($gestao_data['checklist_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['checklist_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['checklist_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['checklist_gestao_tgn']).'</td>';
		elseif ($gestao_data['checklist_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['checklist_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['checklist_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut_pro($gestao_data['checklist_gestao_gut']).'</td>';
		elseif ($gestao_data['checklist_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['checklist_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['checklist_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['checklist_gestao_arquivo']).'</td>';
		elseif ($gestao_data['checklist_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['checklist_gestao_forum']).'</td>';
		elseif ($gestao_data['checklist_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_agenda($gestao_data['checklist_gestao_agenda']).'</td>';
		elseif ($gestao_data['checklist_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['checklist_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['checklist_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['checklist_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['checklist_gestao_template']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_template($gestao_data['checklist_gestao_template']).'</td>';
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['checklist_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}





function exibir_usuarios($usuarios){
	global $config;
	$usuarios_selecionados=explode(',', $usuarios);
	$saida_usuarios='';
	if (count($usuarios_selecionados)) {
			$saida_usuarios.= '<table cellpadding=0 cellspacing=0>';
			$saida_usuarios.= '<tr><td class="texto" style="width:400px;">'.link_usuario($usuarios_selecionados[0],'','','esquerda');
			$qnt_lista_usuarios=count($usuarios_selecionados);
			if ($qnt_lista_usuarios > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';		
					$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
					}
			$saida_usuarios.= '</td></tr></table>';
			} 
	else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_usuarios',"innerHTML", utf8_encode($saida_usuarios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_usuarios");

function exibir_depts($depts){
	global $config;
	$depts_selecionados=explode(',', $depts);
	$saida_depts='';
	if (count($depts_selecionados)) {
			$saida_depts.= '<table cellpadding=0 cellspacing=0>';
			$saida_depts.= '<tr><td class="texto" style="width:400px;">'.link_secao($depts_selecionados[0]);
			$qnt_lista_depts=count($depts_selecionados);
			if ($qnt_lista_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';		
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
					}
			$saida_depts.= '</td></tr></table>';
			} 
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts");


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}

$xajax->registerFunction("selecionar_om_ajax");	









function mudar_posicao_pergunta_ajax($checklist_lista_ordem, $checklist_lista_id, $direcao, $checklist_id=null, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $checklist_lista_id) {
		$novo_ui_checklist_lista_ordem = $checklist_lista_ordem;
		$sql->adTabela('checklist_lista');
		$sql->adOnde('checklist_lista_id != '.$checklist_lista_id);

		if ($uuid) $sql->adOnde('checklist_lista_uuid = \''.$uuid.'\'');
		else $sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_id);
		
		$sql->adOrdem('checklist_lista_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_checklist_lista_ordem;
			$novo_ui_checklist_lista_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_checklist_lista_ordem;
			$novo_ui_checklist_lista_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_checklist_lista_ordem;
			$novo_ui_checklist_lista_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_checklist_lista_ordem;
			$novo_ui_checklist_lista_ordem = count($membros) + 1;
			}
		if ($novo_ui_checklist_lista_ordem && ($novo_ui_checklist_lista_ordem <= count($membros) + 1)) {
			$sql->adTabela('checklist_lista');
			$sql->adAtualizar('checklist_lista_ordem', $novo_ui_checklist_lista_ordem);
			$sql->adOnde('checklist_lista_id = '.$checklist_lista_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_checklist_lista_ordem) {
					$sql->adTabela('checklist_lista');
					$sql->adAtualizar('checklist_lista_ordem', $idx);
					$sql->adOnde('checklist_lista_id = '.$acao['checklist_lista_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('checklist_lista');
					$sql->adAtualizar('checklist_lista_ordem', $idx + 1);
					$sql->adOnde('checklist_lista_id = '.$acao['checklist_lista_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_perguntas($checklist_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perguntas","innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_pergunta_ajax");		
	

function incluir_pergunta_ajax($checklist_id=null, $uuid='', $checklist_lista_id, $checklist_lista_peso='', $checklist_lista_descricao='', $checklist_lista_legenda=false){
	$sql = new BDConsulta;

	$checklist_lista_peso=previnirXSS(utf8_decode($checklist_lista_peso));
	$checklist_lista_descricao=previnirXSS(utf8_decode($checklist_lista_descricao));
	
	if ($checklist_lista_id){
		$sql->adTabela('checklist_lista');
		$sql->adAtualizar('checklist_lista_peso', float_americano($checklist_lista_peso));
		$sql->adAtualizar('checklist_lista_descricao', $checklist_lista_descricao);
		$sql->adAtualizar('checklist_lista_legenda', ($checklist_lista_legenda ? 1 : 0));
		$sql->adOnde('checklist_lista_id ='.$checklist_lista_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('checklist_lista');
		$sql->adCampo('count(checklist_lista_id) AS soma');
		if ($checklist_id) $sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_id);
		else $sql->adOnde('checklist_lista_uuid = \''.$uuid.'\'');
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('checklist_lista');
		$sql->adInserir('checklist_lista_ordem', $soma_total);
		if ($checklist_id) $sql->adInserir('checklist_lista_checklist_id', $checklist_id);
		else $sql->adInserir('checklist_lista_uuid', $uuid);
		$sql->adInserir('checklist_lista_peso', float_americano($checklist_lista_peso));
		$sql->adInserir('checklist_lista_descricao', $checklist_lista_descricao);
		$sql->adInserir('checklist_lista_legenda', ($checklist_lista_legenda ? 1 : 0));
		$sql->exec();
		$sql->Limpar();
		}
	$saida=atualizar_perguntas($checklist_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perguntas","innerHTML", $saida);
	
	
	$sql->adTabela('checklist_lista');
	$sql->adCampo('count(checklist_lista_id)');
	if ($checklist_id) $sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_id);
	else $sql->adOnde('checklist_lista_uuid = \''.$uuid.'\'');
	$quantidade=$sql->Resultado();
	$sql->limpar();
	$objResposta->assign("perguntas_quantidade","value", $quantidade);
	
	
	
	return $objResposta;
	}
$xajax->registerFunction("incluir_pergunta_ajax");	


function excluir_pergunta_ajax($checklist_lista_id, $checklist_id=null, $uuid=''){
	$objResposta = new xajaxResponse();
	
	$sql = new BDConsulta;
	$sql->setExcluir('checklist_lista');
	$sql->adOnde('checklist_lista_id='.$checklist_lista_id);
	$sql->exec();
	$sql->Limpar();
	$saida=atualizar_perguntas($checklist_id, $uuid);
	$objResposta->assign("perguntas","innerHTML", $saida);
	
	$sql->adTabela('checklist_lista');
	$sql->adCampo('count(checklist_lista_id)');
	if ($uuid) $sql->adOnde('checklist_lista_uuid = \''.$uuid.'\'');
	else $sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_id);
	$quantidade=$sql->Resultado();
	$sql->limpar();
	$objResposta->assign("perguntas_quantidade","value", $quantidade);
	
	return $objResposta;
	}

$xajax->registerFunction("excluir_pergunta_ajax");	


function atualizar_perguntas($checklist_id=null, $uuid=''){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('checklist_lista');
	if ($uuid) $sql->adOnde('checklist_lista_uuid = \''.$uuid.'\'');
	else $sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_id);
	$sql->adCampo('checklist_lista.*');
	$sql->adOrdem('checklist_lista_ordem');
	$perguntas=$sql->ListaChave('checklist_lista_id');
	$sql->limpar();
	$saida='';
	$FPTITipoFluxo=getSisValor('FPTITipoFluxo');
	$FPTIFomento=getSisValor('FPTIFomento','','','sisvalor_id');
	if (count($perguntas)) {
		$saida.= '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>Peso</th><th>Pergunta</th><th width=32></th></tr>';
		foreach ($perguntas as $checklist_lista_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
			$saida.= '</td>';
			if (!$linha['checklist_lista_legenda']) $saida.= '<td align="center" nowrap="nowrap">'.((float)$linha['checklist_lista_peso']==(int)$linha['checklist_lista_peso'] ? (int)$linha['checklist_lista_peso']  : number_format((float)$linha['checklist_lista_peso'], 2, ',', '.')).'</td>';
			$saida.= '<td align="left" '.($linha['checklist_lista_legenda'] ? 'colspan=2' : '').'>'.utf8_encode($linha['checklist_lista_descricao']).'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="editar_pergunta('.$linha['checklist_lista_id'].');">'.imagem('icones/editar.gif').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este pergunta de entrada?\')) {excluir_pergunta('.$linha['checklist_lista_id'].');}">'.imagem('icones/remover.png').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table></td></tr></table>';
		}
	return $saida;
	}	

$xajax->registerFunction("atualizar_perguntas");		
	
function editar_pergunta($checklist_lista_id){
	global $config;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('checklist_lista');
	$sql->adCampo('checklist_lista.*');
	$sql->adOnde('checklist_lista_id = '.(int)$checklist_lista_id);
	$sql->adOrdem('checklist_lista_ordem');
	$linha=$sql->Linha();
	$sql->limpar();
	$saida='';	
	$objResposta->assign("checklist_lista_id","value", $checklist_lista_id);
	$objResposta->assign("checklist_lista_peso","value", ((float)$linha['checklist_lista_peso']==(int)$linha['checklist_lista_peso'] ? (int)$linha['checklist_lista_peso']  : number_format((float)$linha['checklist_lista_peso'], 2, ',', '.')));
	$objResposta->assign("texto_apoio","value", utf8_encode($linha['checklist_lista_descricao']));	
	$objResposta->assign("checklist_lista_legenda","checked", ($linha['checklist_lista_legenda'] ? true : false));	
	return $objResposta;
	}	

$xajax->registerFunction("editar_pergunta");	








$xajax->processRequest();

?>