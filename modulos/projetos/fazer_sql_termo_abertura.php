<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/projetos/termo_abertura.class.php');

$sql = new BDConsulta;

$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$aprovar = intval(getParam($_REQUEST, 'aprovar', 0));
$nao_aprovar = intval(getParam($_REQUEST, 'nao_aprovar', 0));
$projeto_abertura_id = getParam($_REQUEST, 'projeto_abertura_id', null);
$Aplic->setMsg('Termo de Abertura');

$obj = new CTermoAbertura();


if ($nao_aprovar && $projeto_abertura_id) {
	$sql->adTabela('projeto_abertura');
	$sql->adAtualizar('projeto_abertura_aprovado', -1);
	$sql->adAtualizar('projeto_abertura_recusa', getParam($_REQUEST, 'projeto_abertura_recusa', ''));
	$sql->adAtualizar('projeto_abertura_data', date('Y-m-d H:i:s'));
	$sql->adOnde('projeto_abertura_id='.(int)$projeto_abertura_id);
	$sql->exec();
	$sql->limpar();
	
	$Aplic->setMsg('não aprovado', UI_MSG_ALERTA, true);
	$Aplic->redirecionar('m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$projeto_abertura_id);
	exit();	
	}


if ($aprovar && $projeto_abertura_id) {
	$obj->load($projeto_abertura_id);

	$sql = new BDConsulta;
	
	$sql->adTabela('projetos');
	$sql->adInserir('projeto_acesso', $obj->projeto_abertura_acesso);
	if ($obj->projeto_abertura_cia) $sql->adInserir('projeto_cia', $obj->projeto_abertura_cia);
	if ($obj->projeto_abertura_nome) $sql->adInserir('projeto_nome', $obj->projeto_abertura_nome);
	if ($obj->projeto_abertura_codigo) $sql->adInserir('projeto_codigo', $obj->projeto_abertura_codigo);
	if ($obj->projeto_abertura_setor) $sql->adInserir('projeto_setor', $obj->projeto_abertura_setor);
	if ($obj->projeto_abertura_segmento) $sql->adInserir('projeto_segmento', $obj->projeto_abertura_segmento);
	if ($obj->projeto_abertura_intervencao) $sql->adInserir('projeto_intervencao', $obj->projeto_abertura_intervencao);
	if ($obj->projeto_abertura_tipo_intervencao) $sql->adInserir('projeto_tipo_intervencao', $obj->projeto_abertura_tipo_intervencao);
	if ($obj->projeto_abertura_ano) $sql->adInserir('projeto_ano', $obj->projeto_abertura_ano);
	if ($obj->projeto_abertura_gerente_projeto) $sql->adInserir('projeto_responsavel', $obj->projeto_abertura_gerente_projeto);
	if ($obj->projeto_abertura_autoridade) $sql->adInserir('projeto_autoridade', $obj->projeto_abertura_autoridade);
	if ($obj->projeto_abertura_cia) $sql->adInserir('projeto_criador', $obj->projeto_abertura_autoridade);
	if ($obj->projeto_abertura_cor) $sql->adInserir('projeto_cor', $obj->projeto_abertura_cor);
	$sql->adInserir('projeto_data_inicio',  date('Y-m-d H:i:s'));
	$sql->adInserir('projeto_data_fim',  date('Y-m-d H:i:s'));
	$sql->adInserir('projeto_fim_atualizado',  date('Y-m-d H:i:s'));
	$sql->adInserir('projeto_status', 1);
	
	if ($obj->projeto_abertura_justificativa) $sql->adInserir('projeto_justificativa', $obj->projeto_abertura_justificativa);
	if ($obj->projeto_abertura_objetivo) $sql->adInserir('projeto_objetivo', $obj->projeto_abertura_objetivo);
	if ($obj->projeto_abertura_escopo) $sql->adInserir('projeto_escopo', $obj->projeto_abertura_escopo);
	if ($obj->projeto_abertura_nao_escopo) $sql->adInserir('projeto_nao_escopo', $obj->projeto_abertura_nao_escopo);
	if ($obj->projeto_abertura_premissas) $sql->adInserir('projeto_premissas', $obj->projeto_abertura_premissas);
	if ($obj->projeto_abertura_restricoes) $sql->adInserir('projeto_restricoes', $obj->projeto_abertura_restricoes);
	if ($obj->projeto_abertura_custo) $sql->adInserir('projeto_orcamento', $obj->projeto_abertura_custo);
	
	$sql->exec();
	$projeto_id = $bd->Insert_ID('projetos','projeto_id');
	$sql->limpar();

	$sql->adTabela('projetos');
	$sql->adAtualizar('projeto_superior', (int)$projeto_id);
	$sql->adAtualizar('projeto_superior_original', (int)$projeto_id);
	$sql->adOnde('projeto_id='.(int)$projeto_id);
	$sql->exec();
	$sql->limpar();

	

	
	if ($Aplic->profissional){
		
		//se for um portifilio de demandas colocar nos filhos
		
		$sql->adTabela('demandas');
		$sql->adCampo('demanda_id');
		$sql->adOnde('demanda_termo_abertura='.(int)$projeto_abertura_id);
		$demanda_pai = $sql->resultado();
		$sql->Limpar();
			
		
			
		$sql->adTabela('demanda_portfolio');
		$sql->adCampo('demanda_portfolio_filho');
		$sql->adOnde('demanda_portfolio_pai='.(int)$demanda_pai);
		$demandas_filhas = $sql->carregarColuna();
		$sql->Limpar();
		
		foreach($demandas_filhas as $demanda_filha){
			$sql->adTabela('demandas');
			$sql->adAtualizar('demanda_projeto', (int)$projeto_id);
			$sql->adAtualizar('demanda_superior', (int)$demanda_pai);
			$sql->adOnde('demanda_id='.(int)$demanda_filha);
			$sql->adOnde('demanda_projeto IS NULL');
			$sql->adOnde('demanda_superior IS NULL');
			$sql->exec();
			$sql->limpar();
			}
		
		
		$sql->adTabela('demanda_gestao');
		$sql->esqUnir('demandas', 'demandas', 'demanda_gestao_demanda=demanda_id');
		$sql->adCampo('demanda_gestao.*');
		$sql->adOnde('demanda_termo_abertura ='.(int)$projeto_abertura_id);	
		$sql->adOrdem('demanda_gestao_ordem');
		$lista_gestao = $sql->Lista();
		$sql->Limpar();
		
		foreach($lista_gestao as $gestao){
			$sql->adTabela('projeto_gestao');
			$sql->adInserir('projeto_gestao_projeto',  $projeto_id);

			if ($gestao['demanda_gestao_tarefa']) $sql->adInserir('projeto_gestao_tarefa', $gestao['demanda_gestao_tarefa']);
			elseif ($gestao['demanda_gestao_perspectiva']) $sql->adInserir('projeto_gestao_perspectiva', $gestao['demanda_gestao_perspectiva']);
			elseif ($gestao['demanda_gestao_tema']) $sql->adInserir('projeto_gestao_tema', $gestao['demanda_gestao_tema']);
			elseif ($gestao['demanda_gestao_objetivo']) $sql->adInserir('projeto_gestao_objetivo', $gestao['demanda_gestao_objetivo']);
			elseif ($gestao['demanda_gestao_fator']) $sql->adInserir('projeto_gestao_fator', $gestao['demanda_gestao_fator']);
			elseif ($gestao['demanda_gestao_estrategia']) $sql->adInserir('projeto_gestao_estrategia', $gestao['demanda_gestao_estrategia']);
			elseif ($gestao['demanda_gestao_meta']) $sql->adInserir('projeto_gestao_meta', $gestao['demanda_gestao_meta']);
			elseif ($gestao['demanda_gestao_pratica']) $sql->adInserir('projeto_gestao_pratica', $gestao['demanda_gestao_pratica']);
			elseif ($gestao['demanda_gestao_indicador']) $sql->adInserir('projeto_gestao_indicador', $gestao['demanda_gestao_indicador']);
			elseif ($gestao['demanda_gestao_acao']) $sql->adInserir('projeto_gestao_acao', $gestao['demanda_gestao_acao']);
			elseif ($gestao['demanda_gestao_canvas']) $sql->adInserir('projeto_gestao_canvas', $gestao['demanda_gestao_canvas']);
			elseif ($gestao['demanda_gestao_risco']) $sql->adInserir('projeto_gestao_risco', $gestao['demanda_gestao_risco']);
			elseif ($gestao['demanda_gestao_risco_resposta']) $sql->adInserir('projeto_gestao_risco_resposta', $gestao['demanda_gestao_risco_resposta']);
			elseif ($gestao['demanda_gestao_calendario']) $sql->adInserir('projeto_gestao_calendario', $gestao['demanda_gestao_calendario']);
			elseif ($gestao['demanda_gestao_monitoramento']) $sql->adInserir('projeto_gestao_monitoramento', $gestao['demanda_gestao_monitoramento']);
			elseif ($gestao['demanda_gestao_ata']) $sql->adInserir('projeto_gestao_ata', $gestao['demanda_gestao_ata']);
			elseif ($gestao['demanda_gestao_swot']) $sql->adInserir('projeto_gestao_swot', $gestao['demanda_gestao_swot']);
			elseif ($gestao['demanda_gestao_operativo']) $sql->adInserir('projeto_gestao_operativo', $gestao['demanda_gestao_operativo']);
			elseif ($gestao['demanda_gestao_instrumento']) $sql->adInserir('projeto_gestao_instrumento', $gestao['demanda_gestao_instrumento']);
			elseif ($gestao['demanda_gestao_recurso']) $sql->adInserir('projeto_gestao_recurso', $gestao['demanda_gestao_recurso']);
			elseif ($gestao['demanda_gestao_problema']) $sql->adInserir('projeto_gestao_problema', $gestao['demanda_gestao_problema']);
			elseif ($gestao['demanda_gestao_programa']) $sql->adInserir('projeto_gestao_programa', $gestao['demanda_gestao_programa']);
			elseif ($gestao['demanda_gestao_licao']) $sql->adInserir('projeto_gestao_licao', $gestao['demanda_gestao_licao']);
			elseif ($gestao['demanda_gestao_evento']) $sql->adInserir('projeto_gestao_evento', $gestao['demanda_gestao_evento']);
			elseif ($gestao['demanda_gestao_link']) $sql->adInserir('projeto_gestao_link', $gestao['demanda_gestao_link']);
			elseif ($gestao['demanda_gestao_avaliacao']) $sql->adInserir('projeto_gestao_avaliacao', $gestao['demanda_gestao_avaliacao']);
			elseif ($gestao['demanda_gestao_tgn']) $sql->adInserir('projeto_gestao_tgn', $gestao['demanda_gestao_tgn']);
			elseif ($gestao['demanda_gestao_brainstorm']) $sql->adInserir('projeto_gestao_brainstorm', $gestao['demanda_gestao_brainstorm']);
			elseif ($gestao['demanda_gestao_gut']) $sql->adInserir('projeto_gestao_gut', $gestao['demanda_gestao_gut']);
			elseif ($gestao['demanda_gestao_causa_efeito']) $sql->adInserir('projeto_gestao_causa_efeito', $gestao['demanda_gestao_causa_efeito']);
			elseif ($gestao['demanda_gestao_arquivo']) $sql->adInserir('projeto_gestao_arquivo', $gestao['demanda_gestao_arquivo']);
			elseif ($gestao['demanda_gestao_forum']) $sql->adInserir('projeto_gestao_forum', $gestao['demanda_gestao_forum']);
			elseif ($gestao['demanda_gestao_checklist']) $sql->adInserir('projeto_gestao_checklist', $gestao['demanda_gestao_checklist']);
			elseif ($gestao['demanda_gestao_agenda']) $sql->adInserir('projeto_gestao_agenda', $gestao['demanda_gestao_agenda']);
			elseif ($gestao['demanda_gestao_agrupamento']) $sql->adInserir('projeto_gestao_agrupamento', $gestao['demanda_gestao_agrupamento']);
			elseif ($gestao['demanda_gestao_patrocinador']) $sql->adInserir('projeto_gestao_patrocinador', $gestao['demanda_gestao_patrocinador']);
			elseif ($gestao['demanda_gestao_template']) $sql->adInserir('projeto_gestao_template', $gestao['demanda_gestao_template']);
			elseif ($gestao['demanda_gestao_painel']) $sql->adInserir('projeto_gestao_painel', $gestao['demanda_gestao_painel']);
			elseif ($gestao['demanda_gestao_painel_odometro']) $sql->adInserir('projeto_gestao_painel_odometro', $gestao['demanda_gestao_painel_odometro']);
			elseif ($gestao['demanda_gestao_painel_composicao']) $sql->adInserir('projeto_gestao_painel_composicao', $gestao['demanda_gestao_painel_composicao']);

			$sql->adInserir('projeto_gestao_ordem', $gestao['demanda_gestao_ordem']);
			$sql->exec();
			$sql->limpar();
			}
		

		$sql->adTabela('priorizacao');
		$sql->esqUnir('priorizacao_modelo', 'priorizacao_modelo', 'priorizacao_modelo=priorizacao_modelo_id');
		$sql->adCampo('priorizacao.*');
		$sql->adOnde('priorizacao_demanda ='.(int)$demanda_pai);	
		$sql->adOnde('priorizacao_modelo_projeto = 1');
		$lista_priorizacao = $sql->Lista();
		$sql->Limpar();
		
		foreach($lista_priorizacao as $priorizacao){
			$sql->adTabela('priorizacao');
			$sql->adInserir('priorizacao_projeto',  $projeto_id);
			if ($priorizacao['priorizacao_modelo']) $sql->adInserir('priorizacao_modelo', $priorizacao['priorizacao_modelo']);
			if ($priorizacao['priorizacao_valor']) $sql->adInserir('priorizacao_valor', $priorizacao['priorizacao_valor']);
			$sql->exec();
			$sql->limpar();
			}	
		}
		
	
	$sql->adTabela('projeto_abertura');
	$sql->adAtualizar('projeto_abertura_aprovado', 1);
	$sql->adAtualizar('projeto_abertura_projeto', (int)$projeto_id);
	$sql->adAtualizar('projeto_abertura_data', date('Y-m-d H:i:s'));
	$sql->adAtualizar('projeto_abertura_aprovacao', getParam($_REQUEST, 'projeto_abertura_aprovacao', ''));
	$sql->adOnde('projeto_abertura_id='.(int)$projeto_abertura_id);
	$sql->exec();
	$sql->limpar();

	$sql->adTabela('demandas');
	$sql->adAtualizar('demanda_projeto', (int)$projeto_id);
	$sql->adOnde('demanda_termo_abertura='.(int)$projeto_abertura_id);
	$sql->exec();
	$sql->limpar();
	
	$sql->adTabela('projeto_viabilidade');
	$sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura.projeto_abertura_demanda=projeto_viabilidade.projeto_viabilidade_demanda');
	$sql->adCampo('projeto_viabilidade_id');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$projeto_viabilidade_id = $sql->resultado();
	$sql->limpar();
	
	$sql->adTabela('projeto_viabilidade');
	$sql->adAtualizar('projeto_viabilidade_projeto', (int)$projeto_id);
	$sql->adOnde('projeto_viabilidade_id='.(int)$projeto_viabilidade_id);
	$sql->exec();
	$sql->limpar();
	
	$sql->adTabela('projeto_abertura_usuarios');
	$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=projeto_abertura_usuarios.usuario_id');
	$sql->adCampo('usuario_contato');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$lista_usuarios = $sql->carregarColuna();
	$sql->limpar();
	
	$sql->adTabela('projeto_abertura_patrocinadores');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$lista_patrocinadores = $sql->carregarColuna();
	$sql->limpar();
	
	$sql->adTabela('projeto_abertura_interessados');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$lista_interessados = $sql->carregarColuna();
	$sql->limpar();
	
	$ordem=0;
	foreach($lista_usuarios as $contato_id){
		$sql->adTabela('projeto_integrantes');
		$sql->adInserir('contato_id',  $contato_id);
		$sql->adInserir('projeto_id', $projeto_id);
		$sql->adInserir('ordem', ++$ordem);
		$sql->exec();
		$sql->limpar();
		}


	$ordem=0;
	foreach($lista_interessados as $contato_id){
		$sql->adTabela('projeto_contatos');
		$sql->adInserir('contato_id',  $contato_id);
		$sql->adInserir('projeto_id', $projeto_id);
		$sql->adInserir('ordem', ++$ordem);
		$sql->exec();
		$sql->limpar();
		}

	$ordem=0;
	foreach($lista_patrocinadores as $contato_id){
		$sql->adTabela('projeto_stakeholder');
		$sql->adInserir('projeto_stakeholder_contato',  $contato_id);
		$sql->adInserir('projeto_stakeholder_projeto', $projeto_id);
		$sql->adInserir('projeto_stakeholder_perfil', 1);
		$sql->adInserir('projeto_stakeholder_ordem', ++$ordem);
		$sql->exec();
		$sql->limpar();
		}

	$projeto = new CProjeto(false);
	$projeto->load($projeto_id, true);

	$codigo=$projeto->getCodigo();
	if ($codigo) {
		$sql->adTabela('projetos');
		$sql->adAtualizar('projeto_codigo', $codigo);
		$sql->adOnde('projeto_id='.(int)$projeto_id);
		$sql->exec();
		$sql->limpar();
		}
		
	$projeto->setSequencial();

	if ($projeto_id){
		$Aplic->setMsg('aprovado', UI_MSG_OK, true);
		$Aplic->redirecionar('m=projetos&a=ver&projeto_id='.$projeto_id);
		}
	else{
		$Aplic->setMsg('teve erro ao tentar criar novo projeto', UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$projeto_abertura_id);
		}	
		
	exit();	
	}


if ($excluir) {
	$obj->load($projeto_abertura_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$projeto_abertura_id);
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=viabilidade_lista&tab=0');
		}
	exit();	
	}


if ($projeto_abertura_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=viabilidade_lista&tab=0');
	}

$codigo=$obj->getCodigo();
if ($codigo) $obj->projeto_abertura_codigo=$codigo;

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($projeto_abertura_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
	
$obj->setSequencial();	

$Aplic->redirecionar('m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$obj->projeto_abertura_id);

?>