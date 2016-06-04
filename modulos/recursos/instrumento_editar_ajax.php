<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/recursos/instrumento_editar_ajax_pro.php';


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


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");

function exibir_recursos($recursos){
	global $config;
	$recursos_selecionados=explode(',', $recursos);
	$saida_recursos='';
	if (count($recursos_selecionados)) {
			$saida_recursos.= '<table cellpadding=0 cellspacing=0>';
			$saida_recursos.= '<tr><td class="texto" style="width:400px;">'.link_recurso($recursos_selecionados[0],'','','esquerda');
			$qnt_lista_recursos=count($recursos_selecionados);
			if ($qnt_lista_recursos > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_recursos; $i < $i_cmp; $i++) $lista.=link_recurso($recursos_selecionados[$i],'','','esquerda').'<br>';
					$saida_recursos.= dica('Outr'.$config['genero_recurso'].'s '.ucfirst($config['recursos']), 'Clique para visualizar '.$config['genero_recurso'].'s demais '.strtolower($config['recursos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_recursos\');">(+'.($qnt_lista_recursos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_recursos"><br>'.$lista.'</span>';
					}
			$saida_recursos.= '</td></tr></table>';
			}
	else $saida_recursos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_recursos',"innerHTML", utf8_encode($saida_recursos));
	return $objResposta;
	}
$xajax->registerFunction("exibir_recursos");


function exibir_contatos($contatos){
	global $config;
	$contatos_selecionados=explode(',', $contatos);
	$saida_contatos='';
	if (count($contatos_selecionados)) {
			$saida_contatos.= '<table cellpadding=0 cellspacing=0>';
			$saida_contatos.= '<tr><td class="texto" style="width:400px;">'.link_contato($contatos_selecionados[0],'','','esquerda');
			$qnt_lista_contatos=count($contatos_selecionados);
			if ($qnt_lista_contatos > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
					$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.strtolower($config['contatos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
					}
			$saida_contatos.= '</td></tr></table>';
			}
	else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_contatos',"innerHTML", utf8_encode($saida_contatos));
	return $objResposta;
	}
$xajax->registerFunction("exibir_contatos");

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



function mudar_posicao_gestao($ordem, $instrumento_gestao_id, $direcao, $instrumento_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $instrumento_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('instrumento_gestao');
		$sql->adOnde('instrumento_gestao_id != '.(int)$instrumento_gestao_id);
		if ($uuid) $sql->adOnde('instrumento_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_gestao_instrumento = '.(int)$instrumento_id);
		$sql->adOrdem('instrumento_gestao_ordem');
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
			$sql->adTabela('instrumento_gestao');
			$sql->adAtualizar('instrumento_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('instrumento_gestao_id = '.(int)$instrumento_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('instrumento_gestao');
					$sql->adAtualizar('instrumento_gestao_ordem', $idx);
					$sql->adOnde('instrumento_gestao_id = '.(int)$acao['instrumento_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					}
				else {
					$sql->adTabela('instrumento_gestao');
					$sql->adAtualizar('instrumento_gestao_ordem', $idx + 1);
					$sql->adOnde('instrumento_gestao_id = '.(int)$acao['instrumento_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}

	$saida=atualizar_gestao($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$instrumento_id=0,
	$uuid='',
	$instrumento_projeto=null,
	$instrumento_tarefa=null,
	$instrumento_perspectiva=null,
	$instrumento_tema=null,
	$instrumento_objetivo=null,
	$instrumento_fator=null,
	$instrumento_estrategia=null,
	$instrumento_meta=null,
	$instrumento_pratica=null,
	$instrumento_acao=null,
	$instrumento_canvas=null,
	$instrumento_risco=null,
	$instrumento_risco_resposta=null,
	$instrumento_indicador=null,
	$instrumento_calendario=null,
	$instrumento_monitoramento=null,
	$instrumento_ata=null,
	$instrumento_swot=null,
	$instrumento_operativo=null,
	$instrumento_recurso=null,
	$instrumento_problema=null,
	$instrumento_demanda=null,
	$instrumento_programa=null,
	$instrumento_licao=null,
	$instrumento_evento=null,
	$instrumento_link=null,
	$instrumento_avaliacao=null,
	$instrumento_tgn=null,
	$instrumento_brainstorm=null,
	$instrumento_gut=null,
	$instrumento_causa_efeito=null,
	$instrumento_arquivo=null,
	$instrumento_forum=null,
	$instrumento_checklist=null,
	$instrumento_agenda=null,
	$instrumento_agrupamento=null,
	$instrumento_patrocinador=null,
	$instrumento_template=null,
	$instrumento_painel=null,
	$instrumento_painel_odometro=null,
	$instrumento_painel_composicao=null,
	$instrumento_tr=null,
	$instrumento_me=null
	)
	{
	if (
		$instrumento_projeto ||
		$instrumento_tarefa ||
		$instrumento_tema ||
		$instrumento_objetivo ||
		$instrumento_fator ||
		$instrumento_estrategia ||
		$instrumento_acao ||
		$instrumento_pratica ||
		$instrumento_meta ||
		$instrumento_perspectiva ||
		$instrumento_canvas ||
		$instrumento_risco ||
		$instrumento_risco_resposta ||
		$instrumento_indicador ||
		$instrumento_calendario ||
		$instrumento_monitoramento ||
		$instrumento_ata ||
		$instrumento_swot ||
		$instrumento_operativo ||
		$instrumento_recurso ||
		$instrumento_problema ||
		$instrumento_demanda ||
		$instrumento_programa ||
		$instrumento_licao ||
		$instrumento_evento ||
		$instrumento_link ||
		$instrumento_avaliacao ||
		$instrumento_tgn ||
		$instrumento_brainstorm ||
		$instrumento_gut ||
		$instrumento_causa_efeito ||
		$instrumento_arquivo ||
		$instrumento_forum ||
		$instrumento_checklist ||
		$instrumento_agenda ||
		$instrumento_agrupamento ||
		$instrumento_patrocinador ||
		$instrumento_template ||
		$instrumento_painel ||
		$instrumento_painel_odometro ||
		$instrumento_painel_composicao	||
		$instrumento_tr ||
		$instrumento_me
		){
		$sql = new BDConsulta;

	//verificar se já não inseriu antes
		$sql->adTabela('instrumento_gestao');
		$sql->adCampo('count(instrumento_gestao_id)');
		if ($uuid) $sql->adOnde('instrumento_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);
		if ($instrumento_tarefa) $sql->adOnde('instrumento_gestao_tarefa='.(int)$instrumento_tarefa);
		elseif ($instrumento_projeto) $sql->adOnde('instrumento_gestao_projeto='.(int)$instrumento_projeto);
		elseif ($instrumento_perspectiva) $sql->adOnde('instrumento_gestao_perspectiva='.(int)$instrumento_perspectiva);
		elseif ($instrumento_tema) $sql->adOnde('instrumento_gestao_tema='.(int)$instrumento_tema);
		elseif ($instrumento_objetivo) $sql->adOnde('instrumento_gestao_objetivo='.(int)$instrumento_objetivo);
		elseif ($instrumento_fator) $sql->adOnde('instrumento_gestao_fator='.(int)$instrumento_fator);
		elseif ($instrumento_estrategia) $sql->adOnde('instrumento_gestao_estrategia='.(int)$instrumento_estrategia);
		elseif ($instrumento_acao) $sql->adOnde('instrumento_gestao_acao='.(int)$instrumento_acao);
		elseif ($instrumento_pratica) $sql->adOnde('instrumento_gestao_pratica='.(int)$instrumento_pratica);
		elseif ($instrumento_meta) $sql->adOnde('instrumento_gestao_meta='.(int)$instrumento_meta);
		elseif ($instrumento_canvas) $sql->adOnde('instrumento_gestao_canvas='.(int)$instrumento_canvas);
		elseif ($instrumento_risco) $sql->adOnde('instrumento_gestao_risco='.(int)$instrumento_risco);
		elseif ($instrumento_risco_resposta) $sql->adOnde('instrumento_gestao_risco_resposta='.(int)$instrumento_risco_resposta);
		elseif ($instrumento_indicador) $sql->adOnde('instrumento_gestao_indicador='.(int)$instrumento_indicador);
		elseif ($instrumento_calendario) $sql->adOnde('instrumento_gestao_calendario='.(int)$instrumento_calendario);
		elseif ($instrumento_monitoramento) $sql->adOnde('instrumento_gestao_monitoramento='.(int)$instrumento_monitoramento);
		elseif ($instrumento_ata) $sql->adOnde('instrumento_gestao_ata='.(int)$instrumento_ata);
		elseif ($instrumento_swot) $sql->adOnde('instrumento_gestao_swot='.(int)$instrumento_swot);
		elseif ($instrumento_operativo) $sql->adOnde('instrumento_gestao_operativo='.(int)$instrumento_operativo);
		elseif ($instrumento_recurso) $sql->adOnde('instrumento_gestao_recurso='.(int)$instrumento_recurso);
		elseif ($instrumento_problema) $sql->adOnde('instrumento_gestao_problema='.(int)$instrumento_problema);
		elseif ($instrumento_demanda) $sql->adOnde('instrumento_gestao_demanda='.(int)$instrumento_demanda);
		elseif ($instrumento_programa) $sql->adOnde('instrumento_gestao_programa='.(int)$instrumento_programa);
		elseif ($instrumento_licao) $sql->adOnde('instrumento_gestao_licao='.(int)$instrumento_licao);
		elseif ($instrumento_evento) $sql->adOnde('instrumento_gestao_evento='.(int)$instrumento_evento);
		elseif ($instrumento_link) $sql->adOnde('instrumento_gestao_link='.(int)$instrumento_link);
		elseif ($instrumento_avaliacao) $sql->adOnde('instrumento_gestao_avaliacao='.(int)$instrumento_avaliacao);
		elseif ($instrumento_tgn) $sql->adOnde('instrumento_gestao_tgn='.(int)$instrumento_tgn);
		elseif ($instrumento_brainstorm) $sql->adOnde('instrumento_gestao_brainstorm='.(int)$instrumento_brainstorm);
		elseif ($instrumento_gut) $sql->adOnde('instrumento_gestao_gut='.(int)$instrumento_gut);
		elseif ($instrumento_causa_efeito) $sql->adOnde('instrumento_gestao_causa_efeito='.(int)$instrumento_causa_efeito);
		elseif ($instrumento_arquivo) $sql->adOnde('instrumento_gestao_arquivo='.(int)$instrumento_arquivo);
		elseif ($instrumento_forum) $sql->adOnde('instrumento_gestao_forum='.(int)$instrumento_forum);
		elseif ($instrumento_checklist) $sql->adOnde('instrumento_gestao_checklist='.(int)$instrumento_checklist);
		elseif ($instrumento_agenda) $sql->adOnde('instrumento_gestao_agenda='.(int)$instrumento_agenda);
		elseif ($instrumento_agrupamento) $sql->adOnde('instrumento_gestao_agrupamento='.(int)$instrumento_agrupamento);
		elseif ($instrumento_patrocinador) $sql->adOnde('instrumento_gestao_patrocinador='.(int)$instrumento_patrocinador);
		elseif ($instrumento_template) $sql->adOnde('instrumento_gestao_template='.(int)$instrumento_template);
		elseif ($instrumento_painel) $sql->adOnde('instrumento_gestao_painel='.(int)$instrumento_painel);
		elseif ($instrumento_painel_odometro) $sql->adOnde('instrumento_gestao_painel_odometro='.(int)$instrumento_painel_odometro);
		elseif ($instrumento_painel_composicao) $sql->adOnde('instrumento_gestao_painel_composicao='.(int)$instrumento_painel_composicao);
		elseif ($instrumento_tr) $sql->adOnde('instrumento_gestao_tr='.(int)$instrumento_tr);
		elseif ($instrumento_me) $sql->adOnde('instrumento_gestao_me='.(int)$instrumento_me);
	  $existe = $sql->Resultado();
	  $sql->Limpar();
		if (!$existe){
			$sql->adTabela('instrumento_gestao');
			$sql->adCampo('MAX(instrumento_gestao_ordem)');
			if ($uuid) $sql->adOnde('instrumento_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);
		  $qnt = (int)$sql->Resultado();
		  $sql->Limpar();
			$sql->adTabela('instrumento_gestao');
			if ($uuid) $sql->adInserir('instrumento_gestao_uuid', $uuid);
			else $sql->adInserir('instrumento_gestao_instrumento', (int)$instrumento_id);

			if ($instrumento_tarefa) $sql->adInserir('instrumento_gestao_tarefa', (int)$instrumento_tarefa);
			if ($instrumento_projeto) $sql->adInserir('instrumento_gestao_projeto', (int)$instrumento_projeto);
			elseif ($instrumento_perspectiva) $sql->adInserir('instrumento_gestao_perspectiva', (int)$instrumento_perspectiva);
			elseif ($instrumento_tema) $sql->adInserir('instrumento_gestao_tema', (int)$instrumento_tema);
			elseif ($instrumento_objetivo) $sql->adInserir('instrumento_gestao_objetivo', (int)$instrumento_objetivo);
			elseif ($instrumento_fator) $sql->adInserir('instrumento_gestao_fator', (int)$instrumento_fator);
			elseif ($instrumento_estrategia) $sql->adInserir('instrumento_gestao_estrategia', (int)$instrumento_estrategia);
			elseif ($instrumento_acao) $sql->adInserir('instrumento_gestao_acao', (int)$instrumento_acao);
			elseif ($instrumento_pratica) $sql->adInserir('instrumento_gestao_pratica', (int)$instrumento_pratica);
			elseif ($instrumento_meta) $sql->adInserir('instrumento_gestao_meta', (int)$instrumento_meta);
			elseif ($instrumento_canvas) $sql->adInserir('instrumento_gestao_canvas', (int)$instrumento_canvas);
			elseif ($instrumento_risco) $sql->adInserir('instrumento_gestao_risco', (int)$instrumento_risco);
			elseif ($instrumento_risco_resposta) $sql->adInserir('instrumento_gestao_risco_resposta', (int)$instrumento_risco_resposta);
			elseif ($instrumento_indicador) $sql->adInserir('instrumento_gestao_indicador', (int)$instrumento_indicador);
			elseif ($instrumento_calendario) $sql->adInserir('instrumento_gestao_calendario', (int)$instrumento_calendario);
			elseif ($instrumento_monitoramento) $sql->adInserir('instrumento_gestao_monitoramento', (int)$instrumento_monitoramento);
			elseif ($instrumento_ata) $sql->adInserir('instrumento_gestao_ata', (int)$instrumento_ata);
			elseif ($instrumento_swot) $sql->adInserir('instrumento_gestao_swot', (int)$instrumento_swot);
			elseif ($instrumento_operativo) $sql->adInserir('instrumento_gestao_operativo', (int)$instrumento_operativo);
			elseif ($instrumento_recurso) $sql->adInserir('instrumento_gestao_recurso', (int)$instrumento_recurso);
			elseif ($instrumento_problema) $sql->adInserir('instrumento_gestao_problema', (int)$instrumento_problema);
			elseif ($instrumento_demanda) $sql->adInserir('instrumento_gestao_demanda', (int)$instrumento_demanda);
			elseif ($instrumento_programa) $sql->adInserir('instrumento_gestao_programa', (int)$instrumento_programa);
			elseif ($instrumento_licao) $sql->adInserir('instrumento_gestao_licao', (int)$instrumento_licao);
			elseif ($instrumento_evento) $sql->adInserir('instrumento_gestao_evento', (int)$instrumento_evento);
			elseif ($instrumento_link) $sql->adInserir('instrumento_gestao_link', (int)$instrumento_link);
			elseif ($instrumento_avaliacao) $sql->adInserir('instrumento_gestao_avaliacao', (int)$instrumento_avaliacao);
			elseif ($instrumento_tgn) $sql->adInserir('instrumento_gestao_tgn', (int)$instrumento_tgn);
			elseif ($instrumento_brainstorm) $sql->adInserir('instrumento_gestao_brainstorm', (int)$instrumento_brainstorm);
			elseif ($instrumento_gut) $sql->adInserir('instrumento_gestao_gut', (int)$instrumento_gut);
			elseif ($instrumento_causa_efeito) $sql->adInserir('instrumento_gestao_causa_efeito', (int)$instrumento_causa_efeito);
			elseif ($instrumento_arquivo) $sql->adInserir('instrumento_gestao_arquivo', (int)$instrumento_arquivo);
			elseif ($instrumento_forum) $sql->adInserir('instrumento_gestao_forum', (int)$instrumento_forum);
			elseif ($instrumento_checklist) $sql->adInserir('instrumento_gestao_checklist', (int)$instrumento_checklist);
			elseif ($instrumento_agenda) $sql->adInserir('instrumento_gestao_agenda', (int)$instrumento_agenda);
			elseif ($instrumento_agrupamento) $sql->adInserir('instrumento_gestao_agrupamento', (int)$instrumento_agrupamento);
			elseif ($instrumento_patrocinador) $sql->adInserir('instrumento_gestao_patrocinador', (int)$instrumento_patrocinador);
			elseif ($instrumento_template) $sql->adInserir('instrumento_gestao_template', (int)$instrumento_template);
			elseif ($instrumento_painel) $sql->adInserir('instrumento_gestao_painel', (int)$instrumento_painel);
			elseif ($instrumento_painel_odometro) $sql->adInserir('instrumento_gestao_painel_odometro', (int)$instrumento_painel_odometro);
			elseif ($instrumento_painel_composicao) $sql->adInserir('instrumento_gestao_painel_composicao', (int)$instrumento_painel_composicao);
			elseif ($instrumento_tr) $sql->adInserir('instrumento_gestao_tr', (int)$instrumento_tr);
			elseif ($instrumento_me) $sql->adInserir('instrumento_gestao_me', (int)$instrumento_me);
			$sql->adInserir('instrumento_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->Limpar();

			$saida=atualizar_gestao($instrumento_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
            //$saida .= '<script>__buildTooltip()</script>';
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");


function excluir_gestao($instrumento_id=0, $uuid='', $instrumento_gestao_id=0){
	$sql = new BDConsulta;
	$sql->setExcluir('instrumento_gestao');
	$sql->adOnde('instrumento_gestao_id='.(int)$instrumento_gestao_id);
	$sql->exec();

	$saida=atualizar_gestao($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("excluir_gestao");

function exibir_gestao($instrumento_id=0, $uuid=''){
	$saida=atualizar_gestao($instrumento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("exibir_gestao");


function atualizar_gestao($instrumento_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_gestao');
	$sql->adCampo('instrumento_gestao.*');
	if ($uuid) $sql->adOnde('instrumento_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);
	$sql->adOrdem('instrumento_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td nowrap="nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
		if ($gestao_data['instrumento_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['instrumento_gestao_tarefa']).'</td>';
		else if ($gestao_data['instrumento_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['instrumento_gestao_projeto']).'</td>';
		elseif ($gestao_data['instrumento_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['instrumento_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['instrumento_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['instrumento_gestao_tema']).'</td>';
		elseif ($gestao_data['instrumento_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['instrumento_gestao_meta']).'</td>';
		elseif ($gestao_data['instrumento_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['instrumento_gestao_acao']).'</td>';
		elseif ($gestao_data['instrumento_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['instrumento_gestao_fator']).'</td>';
		elseif ($gestao_data['instrumento_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['instrumento_gestao_objetivo']).'</td>';
		elseif ($gestao_data['instrumento_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['instrumento_gestao_pratica']).'</td>';
		elseif ($gestao_data['instrumento_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['instrumento_gestao_estrategia']).'</td>';
		elseif ($gestao_data['instrumento_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['instrumento_gestao_canvas']).'</td>';
		elseif ($gestao_data['instrumento_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['instrumento_gestao_risco']).'</td>';
		elseif ($gestao_data['instrumento_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['instrumento_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['instrumento_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['instrumento_gestao_indicador']).'</td>';
		elseif ($gestao_data['instrumento_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_calendario($gestao_data['instrumento_gestao_calendario']).'</td>';
		elseif ($gestao_data['instrumento_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['instrumento_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['instrumento_gestao_ata']) $saida.= '<td align=left>'.imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['instrumento_gestao_ata']).'</td>';
		elseif ($gestao_data['instrumento_gestao_swot']) $saida.= '<td align=left>'.imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['instrumento_gestao_swot']).'</td>';
		elseif ($gestao_data['instrumento_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['instrumento_gestao_operativo']).'</td>';
		elseif ($gestao_data['instrumento_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['instrumento_gestao_recurso']).'</td>';
		elseif ($gestao_data['instrumento_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema_pro($gestao_data['instrumento_gestao_problema']).'</td>';
		elseif ($gestao_data['instrumento_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['instrumento_gestao_demanda']).'</td>';
		elseif ($gestao_data['instrumento_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['instrumento_gestao_programa']).'</td>';
		elseif ($gestao_data['instrumento_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['instrumento_gestao_licao']).'</td>';
		elseif ($gestao_data['instrumento_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['instrumento_gestao_evento']).'</td>';
		elseif ($gestao_data['instrumento_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['instrumento_gestao_link']).'</td>';
		elseif ($gestao_data['instrumento_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['instrumento_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['instrumento_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['instrumento_gestao_tgn']).'</td>';
		elseif ($gestao_data['instrumento_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['instrumento_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['instrumento_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut_pro($gestao_data['instrumento_gestao_gut']).'</td>';
		elseif ($gestao_data['instrumento_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['instrumento_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['instrumento_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['instrumento_gestao_arquivo']).'</td>';
		elseif ($gestao_data['instrumento_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['instrumento_gestao_forum']).'</td>';
		elseif ($gestao_data['instrumento_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['instrumento_gestao_checklist']).'</td>';
		elseif ($gestao_data['instrumento_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_agenda($gestao_data['instrumento_gestao_agenda']).'</td>';
		elseif ($gestao_data['instrumento_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['instrumento_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['instrumento_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['instrumento_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['instrumento_gestao_template']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_template($gestao_data['instrumento_gestao_template']).'</td>';
		elseif ($gestao_data['instrumento_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_painel($gestao_data['instrumento_gestao_painel']).'</td>';
		elseif ($gestao_data['instrumento_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['instrumento_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['instrumento_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['instrumento_gestao_painel_composicao']).'</td>';
		elseif ($gestao_data['instrumento_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['instrumento_gestao_tr']).'</td>';
		elseif ($gestao_data['instrumento_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['instrumento_gestao_me']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['instrumento_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}



$xajax->processRequest();

?>