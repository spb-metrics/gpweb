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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/meta_editar_pro_ajax.php');

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


function atualizar_anos_ajax($cia_id=1, $posicao){
	global $Aplic;
	$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao');
	$sql->adCampo('DISTINCT pg_id, pg_ano');
	$sql->adOnde('pg_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
	$sql->adOrdem('pg_ano DESC');
	$listaanos=$sql->Lista();
	$sql->limpar();
	
	$anos=array();
	foreach ((array)$listaanos as $ano_achado) $anos[(int)$ano_achado['pg_id']]=(int)$ano_achado['pg_ano'];
	$saida=selecionaVetor($anos, 'pg_meta_pg_id', 'class="texto" size=1');
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
	

$xajax->registerFunction("atualizar_anos_ajax");




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



function mudar_posicao_gestao($ordem, $meta_gestao_id, $direcao, $pg_meta_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $meta_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('meta_gestao');
		$sql->adOnde('meta_gestao_id != '.(int)$meta_gestao_id);
		if ($uuid) $sql->adOnde('meta_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('meta_gestao_meta = '.(int)$pg_meta_id);
		$sql->adOrdem('meta_gestao_ordem');
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
			$sql->adTabela('meta_gestao');
			$sql->adAtualizar('meta_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('meta_gestao_id = '.(int)$meta_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('meta_gestao');
					$sql->adAtualizar('meta_gestao_ordem', $idx);
					$sql->adOnde('meta_gestao_id = '.(int)$acao['meta_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('meta_gestao');
					$sql->adAtualizar('meta_gestao_ordem', $idx + 1);
					$sql->adOnde('meta_gestao_id = '.(int)$acao['meta_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($pg_meta_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$pg_meta_id=0, 
	$uuid='',  
	$meta_projeto=null,  
	$meta_tarefa=null,  
	$meta_perspectiva=null,
	$meta_tema=null, 
	$meta_objetivo=null, 
	$meta_fator=null, 
	$meta_estrategia=null, 
	$meta_meta2=null,
	$meta_pratica=null,  
	$meta_acao=null, 
	$meta_canvas=null, 
	$meta_risco=null, 
	$meta_risco_resposta=null,
	$meta_indicador=null,
	$meta_calendario=null,
	$meta_monitoramento=null,
	$meta_ata=null,
	$meta_swot=null,
	$meta_operativo=null,
	$meta_instrumento=null,
	$meta_recurso=null,
	$meta_problema=null,
	$meta_demanda=null,
	$meta_programa=null,
	$meta_licao=null,
	$meta_evento=null,
	$meta_link=null,
	$meta_avaliacao=null,
	$meta_tgn=null,
	$meta_brainstorm=null,
	$meta_gut=null,
	$meta_causa_efeito=null,
	$meta_arquivo=null,
	$meta_forum=null,
	$meta_checklist=null,
	$meta_agenda=null,
	$meta_agrupamento=null,
	$meta_patrocinador=null,
	$meta_template=null,
	$meta_painel=null,
	$meta_painel_odometro=null,
	$meta_painel_composicao=null,
	$meta_tr=null,
	$meta_me=null
	)
	{
	if (
		$meta_projeto || 
		$meta_tarefa ||  
		$meta_tema || 
		$meta_objetivo || 
		$meta_fator || 
		$meta_estrategia || 
		$meta_meta2 || 
		$meta_acao || 
		$meta_pratica || 
		$meta_perspectiva || 
		$meta_canvas || 
		$meta_risco || 
		$meta_risco_resposta ||
		$meta_indicador ||
		$meta_calendario ||
		$meta_monitoramento ||
		$meta_ata ||
		$meta_swot ||
		$meta_operativo ||
		$meta_instrumento ||
		$meta_recurso ||
		$meta_problema ||
		$meta_demanda ||
		$meta_programa ||
		$meta_licao ||
		$meta_evento ||
		$meta_link ||
		$meta_avaliacao ||
		$meta_tgn ||
		$meta_brainstorm ||
		$meta_gut ||
		$meta_causa_efeito ||
		$meta_arquivo ||
		$meta_forum ||
		$meta_checklist ||
		$meta_agenda ||
		$meta_agrupamento ||
		$meta_patrocinador ||
		$meta_template || 
		$meta_painel ||
		$meta_painel_odometro ||
		$meta_painel_composicao	||
		$meta_tr ||
		$meta_me	
		){
		$sql = new BDConsulta;
		
	//verificar se já não inseriu antes
		$sql->adTabela('meta_gestao');
		$sql->adCampo('count(meta_gestao_id)');
		if ($uuid) $sql->adOnde('meta_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('meta_gestao_meta ='.(int)$pg_meta_id);	
		if ($meta_tarefa) $sql->adOnde('meta_gestao_tarefa='.(int)$meta_tarefa);
		elseif ($meta_projeto) $sql->adOnde('meta_gestao_projeto='.(int)$meta_projeto);
		elseif ($meta_perspectiva) $sql->adOnde('meta_gestao_perspectiva='.(int)$meta_perspectiva);
		elseif ($meta_tema) $sql->adOnde('meta_gestao_tema='.(int)$meta_tema);
		elseif ($meta_objetivo) $sql->adOnde('meta_gestao_objetivo='.(int)$meta_objetivo);
		elseif ($meta_fator) $sql->adOnde('meta_gestao_fator='.(int)$meta_fator);
		elseif ($meta_estrategia) $sql->adOnde('meta_gestao_estrategia='.(int)$meta_estrategia);
		elseif ($meta_meta2) $sql->adOnde('meta_gestao_meta2='.(int)$meta_meta2);
		elseif ($meta_acao) $sql->adOnde('meta_gestao_acao='.(int)$meta_acao);
		elseif ($meta_pratica) $sql->adOnde('meta_gestao_pratica='.(int)$meta_pratica);
		elseif ($meta_canvas) $sql->adOnde('meta_gestao_canvas='.(int)$meta_canvas);
		elseif ($meta_risco) $sql->adOnde('meta_gestao_risco='.(int)$meta_risco);
		elseif ($meta_risco_resposta) $sql->adOnde('meta_gestao_risco_resposta='.(int)$meta_risco_resposta);
		elseif ($meta_indicador) $sql->adOnde('meta_gestao_indicador='.(int)$meta_indicador);
		elseif ($meta_calendario) $sql->adOnde('meta_gestao_calendario='.(int)$meta_calendario);
		elseif ($meta_monitoramento) $sql->adOnde('meta_gestao_monitoramento='.(int)$meta_monitoramento);
		elseif ($meta_ata) $sql->adOnde('meta_gestao_ata='.(int)$meta_ata);
		elseif ($meta_swot) $sql->adOnde('meta_gestao_swot='.(int)$meta_swot);
		elseif ($meta_operativo) $sql->adOnde('meta_gestao_operativo='.(int)$meta_operativo);
		elseif ($meta_instrumento) $sql->adOnde('meta_gestao_instrumento='.(int)$meta_instrumento);
		elseif ($meta_recurso) $sql->adOnde('meta_gestao_recurso='.(int)$meta_recurso);
		elseif ($meta_problema) $sql->adOnde('meta_gestao_problema='.(int)$meta_problema);
		elseif ($meta_demanda) $sql->adOnde('meta_gestao_demanda='.(int)$meta_demanda);
		elseif ($meta_programa) $sql->adOnde('meta_gestao_programa='.(int)$meta_programa);
		elseif ($meta_licao) $sql->adOnde('meta_gestao_licao='.(int)$meta_licao);
		elseif ($meta_evento) $sql->adOnde('meta_gestao_evento='.(int)$meta_evento);
		elseif ($meta_link) $sql->adOnde('meta_gestao_link='.(int)$meta_link);
		elseif ($meta_avaliacao) $sql->adOnde('meta_gestao_avaliacao='.(int)$meta_avaliacao);
		elseif ($meta_tgn) $sql->adOnde('meta_gestao_tgn='.(int)$meta_tgn);
		elseif ($meta_brainstorm) $sql->adOnde('meta_gestao_brainstorm='.(int)$meta_brainstorm);
		elseif ($meta_gut) $sql->adOnde('meta_gestao_gut='.(int)$meta_gut);
		elseif ($meta_causa_efeito) $sql->adOnde('meta_gestao_causa_efeito='.(int)$meta_causa_efeito);
		elseif ($meta_arquivo) $sql->adOnde('meta_gestao_arquivo='.(int)$meta_arquivo);
		elseif ($meta_forum) $sql->adOnde('meta_gestao_forum='.(int)$meta_forum);
		elseif ($meta_checklist) $sql->adOnde('meta_gestao_checklist='.(int)$meta_checklist);
		elseif ($meta_agenda) $sql->adOnde('meta_gestao_agenda='.(int)$meta_agenda);
		elseif ($meta_agrupamento) $sql->adOnde('meta_gestao_agrupamento='.(int)$meta_agrupamento);
		elseif ($meta_patrocinador) $sql->adOnde('meta_gestao_patrocinador='.(int)$meta_patrocinador);
		elseif ($meta_template) $sql->adOnde('meta_gestao_template='.(int)$meta_template);
		elseif ($meta_painel) $sql->adOnde('meta_gestao_painel='.(int)$meta_painel);
		elseif ($meta_painel_odometro) $sql->adOnde('meta_gestao_painel_odometro='.(int)$meta_painel_odometro);
		elseif ($meta_painel_composicao) $sql->adOnde('meta_gestao_painel_composicao='.(int)$meta_painel_composicao);
		elseif ($meta_tr) $sql->adOnde('meta_gestao_tr='.(int)$meta_tr);
		elseif ($meta_me) $sql->adOnde('meta_gestao_me='.(int)$meta_me);
	  $existe = $sql->Resultado();
	  $sql->Limpar();
		if (!$existe){
			$sql->adTabela('meta_gestao');
			$sql->adCampo('MAX(meta_gestao_ordem)');
			if ($uuid) $sql->adOnde('meta_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('meta_gestao_meta ='.(int)$pg_meta_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->Limpar();
			$sql->adTabela('meta_gestao');
			if ($uuid) $sql->adInserir('meta_gestao_uuid', $uuid);
			else $sql->adInserir('meta_gestao_meta', (int)$pg_meta_id);
			
			if ($meta_tarefa) $sql->adInserir('meta_gestao_tarefa', (int)$meta_tarefa);
			if ($meta_projeto) $sql->adInserir('meta_gestao_projeto', (int)$meta_projeto);
			elseif ($meta_perspectiva) $sql->adInserir('meta_gestao_perspectiva', (int)$meta_perspectiva);
			elseif ($meta_tema) $sql->adInserir('meta_gestao_tema', (int)$meta_tema);
			elseif ($meta_objetivo) $sql->adInserir('meta_gestao_objetivo', (int)$meta_objetivo);
			elseif ($meta_fator) $sql->adInserir('meta_gestao_fator', (int)$meta_fator);
			elseif ($meta_estrategia) $sql->adInserir('meta_gestao_estrategia', (int)$meta_estrategia);
			elseif ($meta_meta2) $sql->adInserir('meta_gestao_meta2', (int)$meta_meta2);
			elseif ($meta_acao) $sql->adInserir('meta_gestao_acao', (int)$meta_acao);
			elseif ($meta_pratica) $sql->adInserir('meta_gestao_pratica', (int)$meta_pratica);
			elseif ($meta_canvas) $sql->adInserir('meta_gestao_canvas', (int)$meta_canvas);
			elseif ($meta_risco) $sql->adInserir('meta_gestao_risco', (int)$meta_risco);
			elseif ($meta_risco_resposta) $sql->adInserir('meta_gestao_risco_resposta', (int)$meta_risco_resposta);
			elseif ($meta_indicador) $sql->adInserir('meta_gestao_indicador', (int)$meta_indicador);
			elseif ($meta_calendario) $sql->adInserir('meta_gestao_calendario', (int)$meta_calendario);
			elseif ($meta_monitoramento) $sql->adInserir('meta_gestao_monitoramento', (int)$meta_monitoramento);
			elseif ($meta_ata) $sql->adInserir('meta_gestao_ata', (int)$meta_ata);
			elseif ($meta_swot) $sql->adInserir('meta_gestao_swot', (int)$meta_swot);
			elseif ($meta_operativo) $sql->adInserir('meta_gestao_operativo', (int)$meta_operativo);
			elseif ($meta_instrumento) $sql->adInserir('meta_gestao_instrumento', (int)$meta_instrumento);
			elseif ($meta_recurso) $sql->adInserir('meta_gestao_recurso', (int)$meta_recurso);
			elseif ($meta_problema) $sql->adInserir('meta_gestao_problema', (int)$meta_problema);
			elseif ($meta_demanda) $sql->adInserir('meta_gestao_demanda', (int)$meta_demanda);
			elseif ($meta_programa) $sql->adInserir('meta_gestao_programa', (int)$meta_programa);
			elseif ($meta_licao) $sql->adInserir('meta_gestao_licao', (int)$meta_licao);
			elseif ($meta_evento) $sql->adInserir('meta_gestao_evento', (int)$meta_evento);
			elseif ($meta_link) $sql->adInserir('meta_gestao_link', (int)$meta_link);
			elseif ($meta_avaliacao) $sql->adInserir('meta_gestao_avaliacao', (int)$meta_avaliacao);
			elseif ($meta_tgn) $sql->adInserir('meta_gestao_tgn', (int)$meta_tgn);
			elseif ($meta_brainstorm) $sql->adInserir('meta_gestao_brainstorm', (int)$meta_brainstorm);
			elseif ($meta_gut) $sql->adInserir('meta_gestao_gut', (int)$meta_gut);
			elseif ($meta_causa_efeito) $sql->adInserir('meta_gestao_causa_efeito', (int)$meta_causa_efeito);
			elseif ($meta_arquivo) $sql->adInserir('meta_gestao_arquivo', (int)$meta_arquivo);
			elseif ($meta_forum) $sql->adInserir('meta_gestao_forum', (int)$meta_forum);
			elseif ($meta_checklist) $sql->adInserir('meta_gestao_checklist', (int)$meta_checklist);
			elseif ($meta_agenda) $sql->adInserir('meta_gestao_agenda', (int)$meta_agenda);
			elseif ($meta_agrupamento) $sql->adInserir('meta_gestao_agrupamento', (int)$meta_agrupamento);
			elseif ($meta_patrocinador) $sql->adInserir('meta_gestao_patrocinador', (int)$meta_patrocinador);
			elseif ($meta_template) $sql->adInserir('meta_gestao_template', (int)$meta_template);
			elseif ($meta_painel) $sql->adInserir('meta_gestao_painel', (int)$meta_painel);
			elseif ($meta_painel_odometro) $sql->adInserir('meta_gestao_painel_odometro', (int)$meta_painel_odometro);
			elseif ($meta_painel_composicao) $sql->adInserir('meta_gestao_painel_composicao', (int)$meta_painel_composicao);		
			elseif ($meta_tr) $sql->adInserir('meta_gestao_tr', (int)$meta_tr);
			elseif ($meta_me) $sql->adInserir('meta_gestao_me', (int)$meta_me);
			$sql->adInserir('meta_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->Limpar();
	
			$saida=atualizar_gestao($pg_meta_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($pg_meta_id=0, $uuid='', $meta_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('meta_gestao');
	$sql->adOnde('meta_gestao_id='.(int)$meta_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($pg_meta_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($pg_meta_id=0, $uuid=''){	
	$saida=atualizar_gestao($pg_meta_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($pg_meta_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('meta_gestao');
	$sql->adCampo('meta_gestao.*');
	if ($uuid) $sql->adOnde('meta_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('meta_gestao_meta ='.(int)$pg_meta_id);	
	$sql->adOrdem('meta_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td nowrap="nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['meta_gestao_ordem'].', '.$gestao_data['meta_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['meta_gestao_ordem'].', '.$gestao_data['meta_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['meta_gestao_ordem'].', '.$gestao_data['meta_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['meta_gestao_ordem'].', '.$gestao_data['meta_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
		if ($gestao_data['meta_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['meta_gestao_tarefa']).'</td>';
		else if ($gestao_data['meta_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['meta_gestao_projeto']).'</td>';
		elseif ($gestao_data['meta_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['meta_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['meta_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['meta_gestao_tema']).'</td>';
		elseif ($gestao_data['meta_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['meta_gestao_acao']).'</td>';
		elseif ($gestao_data['meta_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['meta_gestao_fator']).'</td>';
		elseif ($gestao_data['meta_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['meta_gestao_objetivo']).'</td>';
		elseif ($gestao_data['meta_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['meta_gestao_pratica']).'</td>';
		elseif ($gestao_data['meta_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['meta_gestao_estrategia']).'</td>';
		elseif ($gestao_data['meta_gestao_meta2']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['meta_gestao_meta2']).'</td>';
		elseif ($gestao_data['meta_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['meta_gestao_canvas']).'</td>';
		elseif ($gestao_data['meta_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['meta_gestao_risco']).'</td>';
		elseif ($gestao_data['meta_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['meta_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['meta_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['meta_gestao_indicador']).'</td>';
		elseif ($gestao_data['meta_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_calendario($gestao_data['meta_gestao_calendario']).'</td>';
		elseif ($gestao_data['meta_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['meta_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['meta_gestao_ata']) $saida.= '<td align=left>'.imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['meta_gestao_ata']).'</td>';
		elseif ($gestao_data['meta_gestao_swot']) $saida.= '<td align=left>'.imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['meta_gestao_swot']).'</td>';
		elseif ($gestao_data['meta_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['meta_gestao_operativo']).'</td>';
		elseif ($gestao_data['meta_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['meta_gestao_instrumento']).'</td>';
		elseif ($gestao_data['meta_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['meta_gestao_recurso']).'</td>';
		elseif ($gestao_data['meta_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema_pro($gestao_data['meta_gestao_problema']).'</td>';
		elseif ($gestao_data['meta_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['meta_gestao_demanda']).'</td>';
		elseif ($gestao_data['meta_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['meta_gestao_programa']).'</td>';
		elseif ($gestao_data['meta_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['meta_gestao_licao']).'</td>';
		elseif ($gestao_data['meta_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['meta_gestao_evento']).'</td>';
		elseif ($gestao_data['meta_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['meta_gestao_link']).'</td>';
		elseif ($gestao_data['meta_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['meta_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['meta_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['meta_gestao_tgn']).'</td>';
		elseif ($gestao_data['meta_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['meta_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['meta_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut_pro($gestao_data['meta_gestao_gut']).'</td>';
		elseif ($gestao_data['meta_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['meta_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['meta_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['meta_gestao_arquivo']).'</td>';
		elseif ($gestao_data['meta_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['meta_gestao_forum']).'</td>';
		elseif ($gestao_data['meta_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['meta_gestao_checklist']).'</td>';
		elseif ($gestao_data['meta_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_agenda($gestao_data['meta_gestao_agenda']).'</td>';
		elseif ($gestao_data['meta_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['meta_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['meta_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['meta_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['meta_gestao_template']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_template($gestao_data['meta_gestao_template']).'</td>';
		elseif ($gestao_data['meta_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_painel($gestao_data['meta_gestao_painel']).'</td>';
		elseif ($gestao_data['meta_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['meta_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['meta_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['meta_gestao_painel_composicao']).'</td>';
		elseif ($gestao_data['meta_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['meta_gestao_tr']).'</td>';
		elseif ($gestao_data['meta_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['meta_gestao_me']).'</td>';

		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['meta_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}

	





function qnt_metas($pg_meta_id=null, $meta_meta_uuid=''){
	$sql = new BDConsulta;	
	$sql->adTabela('meta_meta');
	$sql->adCampo('count(meta_meta_id)');
	if ($meta_meta_uuid) $sql->adOnde('meta_meta_uuid = \''.$meta_meta_uuid.'\'');
	else $sql->adOnde('meta_meta_meta = '.(int)$pg_meta_id);
	$qnt = $sql->resultado();
	$sql->limpar();
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("qnt_metas","value", (int)$qnt);
	return $objResposta;
	}
$xajax->registerFunction("qnt_metas");

function editar_meta($meta_meta_id=null){
	
	$sql = new BDConsulta;	
	$sql->adTabela('meta_meta');
	$sql->adCampo('formatar_data(meta_meta_data_inicio, "%d/%m/%Y") as data, formatar_data(meta_meta_data_fim, "%d/%m/%Y") as data_meta');
	$sql->adCampo('meta_meta.*');
	$sql->adOnde('meta_meta_id = '.(int)$meta_meta_id);
	$linha = $sql->linha();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("meta_meta_id","value", $meta_meta_id);
	$objResposta->assign("meta_meta_data_inicio","value", $linha['meta_meta_data_inicio']);
	$objResposta->assign("data_inicio","value", $linha['data']);
	$objResposta->assign("meta_meta_data_fim","value", $linha['meta_meta_data_fim']);
	$objResposta->assign("data","value", $linha['data_meta']);
	$objResposta->assign("meta_meta_valor_meta","value", ($linha['meta_meta_valor_meta']!=null ? number_format($linha['meta_meta_valor_meta'], 2, ',', '.') : ''));
	$objResposta->assign("meta_meta_valor_meta_boa","value", ($linha['meta_meta_valor_meta_boa']!=null ? number_format($linha['meta_meta_valor_meta_boa'], 2, ',', '.') : ''));
	$objResposta->assign("meta_meta_valor_meta_regular","value", ($linha['meta_meta_valor_meta_regular']!=null ? number_format($linha['meta_meta_valor_meta_regular'], 2, ',', '.') : ''));
	$objResposta->assign("meta_meta_valor_meta_ruim","value", ($linha['meta_meta_valor_meta_ruim']!=null ? number_format($linha['meta_meta_valor_meta_ruim'], 2, ',', '.') : ''));
	return $objResposta;
	}
$xajax->registerFunction("editar_meta");

function excluir_meta($meta_meta_id=null, $meta_meta_meta=null, $meta_meta_uuid=null){
	$sql = new BDConsulta;	
	$sql->setExcluir('meta_meta');
	$sql->adOnde('meta_meta_id = '.(int)$meta_meta_id);
	$sql->exec();
	$sql->limpar();
	$saida=exibe_metas($meta_meta_meta, $meta_meta_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("metas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("excluir_meta");

function incluir_meta(
	$meta_meta_id=null, 
	$meta_meta_meta=null, 
	$meta_meta_uuid=null, 
	$meta_meta_data_inicio=null, 
	$meta_meta_valor_meta=null, 
	$meta_meta_valor_meta_boa=null, 
	$meta_meta_valor_meta_regular=null, 
	$meta_meta_valor_meta_ruim=null, 
	$meta_meta_data_fim=null){

	$sql = new BDConsulta;	
	$sql->adTabela('meta_meta');
	if (!$meta_meta_id){
		if ($meta_meta_uuid) $sql->adInserir('meta_meta_uuid', $meta_meta_uuid);
		else $sql->adInserir('meta_meta_meta', (int)$meta_meta_meta);
		$sql->adInserir('meta_meta_valor_meta', float_americano($meta_meta_valor_meta));
		if ($meta_meta_valor_meta_boa != '') $sql->adInserir('meta_meta_valor_meta_boa', float_americano($meta_meta_valor_meta_boa));
		if ($meta_meta_valor_meta_regular != '') $sql->adInserir('meta_meta_valor_meta_regular', float_americano($meta_meta_valor_meta_regular));
		if ($meta_meta_valor_meta_ruim != '') $sql->adInserir('meta_meta_valor_meta_ruim', float_americano($meta_meta_valor_meta_ruim));
		$sql->adInserir('meta_meta_data_fim', $meta_meta_data_fim);
		$sql->adInserir('meta_meta_data_inicio', $meta_meta_data_inicio);
		$sql->exec();
		$sql->limpar();
		}
	else{
		$sql->adAtualizar('meta_meta_valor_meta', float_americano($meta_meta_valor_meta));
		$sql->adAtualizar('meta_meta_valor_meta_boa', ($meta_meta_valor_meta_boa !='' ? float_americano($meta_meta_valor_meta_boa) : null));
		$sql->adAtualizar('meta_meta_valor_meta_regular', ($meta_meta_valor_meta_regular !='' ? float_americano($meta_meta_valor_meta_regular) : null));
		$sql->adAtualizar('meta_meta_valor_meta_ruim', ($meta_meta_valor_meta_ruim !='' ? float_americano($meta_meta_valor_meta_ruim) : null));
		$sql->adAtualizar('meta_meta_data_fim', $meta_meta_data_fim);
		$sql->adAtualizar('meta_meta_data_inicio', $meta_meta_data_inicio);
		$sql->adOnde('meta_meta_id = '.$meta_meta_id);
		$sql->exec();
		$sql->limpar();
		}
	$saida=exibe_metas($meta_meta_meta, $meta_meta_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("metas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("incluir_meta");


function exibe_metas($pg_meta_id=null, $meta_meta_uuid=''){
	global $Aplic;
	$sql = new BDConsulta;	
	$sql->adTabela('meta_meta');
	$sql->adCampo('formatar_data(meta_meta_data_inicio, "%d/%m/%Y") as data, formatar_data(meta_meta_data_fim, "%d/%m/%Y") as data_meta');
	$sql->adCampo('meta_meta_id, meta_meta_valor_meta, meta_meta_valor_meta_boa, meta_meta_valor_meta_regular, meta_meta_valor_meta_ruim');
	if ($meta_meta_uuid) $sql->adOnde('meta_meta_uuid = \''.$meta_meta_uuid.'\'');
	else $sql->adOnde('meta_meta_meta = '.(int)$pg_meta_id);
	$sql->adOrdem('meta_meta_data_inicio');
	$metas = $sql->lista();
	
	$sql->limpar();
	
	$saida='';
	if (count($metas)){
		$saida.= '<table class="tbl1" cellpadding=0 cellspacing=0><tr><th>Meta</th>'.($Aplic->profissional ? '<th>Bom</th><th>Regular</th><th>Ruim</th>' : '').'<th>Início</th><th>Limite</th><th></th></tr>';
		foreach($metas as $linha) {
			$saida.= '<tr>';
			$saida.= '<td align=right>'.number_format($linha['meta_meta_valor_meta'], 2, ',', '.').'</td>';
			if ($Aplic->profissional){
				$saida.= '<td align=right>'.($linha['meta_meta_valor_meta_boa'] != null ? number_format($linha['meta_meta_valor_meta_boa'], 2, ',', '.') : '&nbsp;').'</td>';
				$saida.= '<td align=right>'.($linha['meta_meta_valor_meta_regular'] != null ? number_format($linha['meta_meta_valor_meta_regular'], 2, ',', '.') : '&nbsp;').'</td>';
				$saida.= '<td align=right>'.($linha['meta_meta_valor_meta_ruim'] != null ? number_format($linha['meta_meta_valor_meta_ruim'], 2, ',', '.') : '&nbsp;').'</td>';
				}
			$saida.= '<td>'.$linha['data'].'</td><td>'.$linha['data_meta'].'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="editar_meta('.$linha['meta_meta_id'].');">'.imagem('icones/editar.gif', 'Editar Meta', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta meta.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta meta?\')) {excluir_meta('.$linha['meta_meta_id'].');}">'.imagem('icones/remover.png', 'Excluir Meta', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta meta.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.='</table>';
		}
	return $saida;
	}




$xajax->processRequest();

?>