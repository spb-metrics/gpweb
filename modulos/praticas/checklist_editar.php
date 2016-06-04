<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;

$Aplic->carregarCKEditorJS();

require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$Aplic->carregarCalendarioJS();
$checklist_id = getParam($_REQUEST, 'checklist_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;



$sql->adTabela('checklist');
$sql->adCampo('checklist_acesso');
$sql->adOnde('checklist_id='.(int)$checklist_id);
$checklist=$sql->Linha();
$sql->limpar();

if(!($podeEditar&& permiteEditarChecklist($checklist['checklist_acesso'],$checklist_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');


$checklist_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

if ($salvar && $checklist_id){
	$atualizar=1;
	$sql->adTabela('checklist');
	$sql->adAtualizar('checklist_nome', getParam($_REQUEST, 'checklist_nome', ''));
	$sql->adAtualizar('checklist_descricao', getParam($_REQUEST, 'checklist_descricao', ''));
	$sql->adAtualizar('checklist_responsavel', getParam($_REQUEST, 'checklist_responsavel', null));
	$sql->adAtualizar('checklist_cia', getParam($_REQUEST, 'checklist_cia', null));
	$sql->adAtualizar('checklist_dept', getParam($_REQUEST, 'checklist_dept', null));
	$sql->adAtualizar('checklist_cor', getParam($_REQUEST, 'checklist_cor', ''));
	$sql->adAtualizar('checklist_acesso', getParam($_REQUEST, 'checklist_acesso', 0));
	$sql->adAtualizar('checklist_modelo', getParam($_REQUEST, 'checklist_modelo', 1));
	$sql->adAtualizar('checklist_tipo', getParam($_REQUEST, 'checklist_tipo', 0));
	$sql->adAtualizar('checklist_superior', getParam($_REQUEST, 'checklist_superior', null));
	$sql->adAtualizar('checklist_ativo', getParam($_REQUEST, 'checklist_ativo', 0));
	$sql->adAtualizar('checklist_principal_indicador', getParam($_REQUEST, 'checklist_principal_indicador', null));

	$sql->adOnde('checklist_id = '.(int)$checklist_id);
	$retorno=$sql->exec();
	$sql->Limpar();
	}

if ($salvar && !$checklist_id){
	$atualizar=0;
	$sql->adTabela('checklist');
	$sql->adInserir('checklist_nome', getParam($_REQUEST, 'checklist_nome', ''));
	$sql->adInserir('checklist_descricao', getParam($_REQUEST, 'checklist_descricao', ''));
	$sql->adInserir('checklist_responsavel', getParam($_REQUEST, 'checklist_responsavel', null));
	$sql->adInserir('checklist_cia', getParam($_REQUEST, 'checklist_cia', null));
	$sql->adInserir('checklist_dept', getParam($_REQUEST, 'checklist_dept', null));
	$sql->adInserir('checklist_cor', getParam($_REQUEST, 'checklist_cor', ''));
	$sql->adInserir('checklist_acesso', getParam($_REQUEST, 'checklist_acesso', 0));
	$sql->adInserir('checklist_modelo', getParam($_REQUEST, 'checklist_modelo', 1));
	$sql->adInserir('checklist_tipo', getParam($_REQUEST, 'checklist_tipo', 0));
	$sql->adInserir('checklist_superior', getParam($_REQUEST, 'checklist_superior', null));
	$sql->adInserir('checklist_ativo', getParam($_REQUEST, 'checklist_ativo', 0));
	$sql->adInserir('checklist_principal_indicador', getParam($_REQUEST, 'checklist_principal_indicador', null));
	$sql->adOnde('checklist_id = '.(int)$checklist_id);
	$sql->exec();
	$checklist_id=$bd->Insert_ID('checklist','checklist_id');
	$sql->Limpar();


	$uuid=getParam($_REQUEST, 'uuid', null);
	if ($Aplic->profissional && $uuid){
		$sql->adTabela('checklist_gestao');
		$sql->adAtualizar('checklist_gestao_checklist', (int)$checklist_id);
		$sql->adAtualizar('checklist_gestao_uuid', null);
		$sql->adOnde('checklist_gestao_uuid=\''.$uuid.'\'');
		$sql->exec();
		$sql->limpar();


		$sql->adTabela('checklist_lista');
		$sql->adAtualizar('checklist_lista_checklist_id', (int)$checklist_id);
		$sql->adAtualizar('checklist_lista_uuid', null);
		$sql->adOnde('checklist_lista_uuid=\''.$uuid.'\'');
		$sql->exec();
		$sql->limpar();

		}
	}


if ($salvar){

	$campos_customizados = new CampoCustomizados('checklist', $checklist_id, 'editar');
	$campos_customizados->join($_REQUEST);
	$campos_customizados->armazenar($checklist_id);

	$checklist_usuarios=getParam($_REQUEST, 'checklist_usuarios', null);
	$checklist_usuarios=explode(',', $checklist_usuarios);
	$sql->setExcluir('checklist_usuarios');
	$sql->adOnde('checklist_id = '.(int)$checklist_id);
	$sql->exec();
	$sql->limpar();
	foreach($checklist_usuarios as $chave => $usuario_id){
		if($usuario_id){
			$sql->adTabela('checklist_usuarios');
			$sql->adInserir('checklist_id', (int)$checklist_id);
			$sql->adInserir('usuario_id', (int)$usuario_id);
			$sql->exec();
			$sql->limpar();
			}
		}

	$depts_selecionados=getParam($_REQUEST, 'checklist_depts', null);
	$depts_selecionados=explode(',', $depts_selecionados);
	$sql->setExcluir('checklist_depts');
	$sql->adOnde('checklist_id = '.(int)$checklist_id);
	$sql->exec();
	$sql->limpar();
	foreach($depts_selecionados as $chave => $dept_id){
		if($dept_id){
			$sql->adTabela('checklist_depts');
			$sql->adInserir('checklist_id', (int)$checklist_id);
			$sql->adInserir('dept_id', (int)$dept_id);
			$sql->exec();
			$sql->limpar();
			}
		}

	if ($Aplic->profissional){
		$sql->setExcluir('checklist_cia');
		$sql->adOnde('checklist_cia_checklist='.(int)$checklist_id);
		$sql->exec();
		$sql->limpar();
		$cias=getParam($_REQUEST, 'checklist_cias', '');
		$cias=explode(',', $cias);
		if (count($cias)) {
			foreach ($cias as $cia_id) {
				if ($cia_id){
					$sql->adTabela('checklist_cia');
					$sql->adInserir('checklist_cia_checklist', $checklist_id);
					$sql->adInserir('checklist_cia_cia', $cia_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		}


	$Aplic->setMsg('checklist '.($atualizar ? 'atualizado' : 'inserido'), UI_MSG_OK);
	$Aplic->redirecionar('m=praticas&a=checklist_ver&checklist_id='.$checklist_id);
	}


if ((!$podeEditar && $checklist_id) || (!$podeAdicionar && !$checklist_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$df = '%d/%m/%Y';
$ttl = ($checklist_id ? 'Editar Checklist' : 'Criar Checklist');
$botoesTitulo = new CBlocoTitulo($ttl, 'todo_list.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$sql->adTabela('checklist');
$sql->adCampo('checklist.*');
$sql->adOnde('checklist_id='.(int)$checklist_id);
$checklist=$sql->Linha();
$sql->limpar();

if ($checklist['checklist_cia']) $cia_id=$checklist['checklist_cia'];
else $cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);


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


$checklist_projeto = getParam($_REQUEST, 'checklist_projeto', null);
$checklist_tarefa = getParam($_REQUEST, 'checklist_tarefa', null);
$checklist_perspectiva = getParam($_REQUEST, 'checklist_perspectiva', null);
$checklist_tema = getParam($_REQUEST, 'checklist_tema', null);
$checklist_objetivo = getParam($_REQUEST, 'checklist_objetivo', null);
$checklist_fator = getParam($_REQUEST, 'checklist_fator', null);
$checklist_estrategia = getParam($_REQUEST, 'checklist_estrategia', null);
$checklist_meta = getParam($_REQUEST, 'checklist_meta', null);
$checklist_pratica = getParam($_REQUEST, 'checklist_pratica', null);
$checklist_acao = getParam($_REQUEST, 'checklist_acao', null);
$checklist_canvas = getParam($_REQUEST, 'checklist_canvas', null);
$checklist_risco = getParam($_REQUEST, 'checklist_risco', null);
$checklist_risco_resposta = getParam($_REQUEST, 'checklist_risco_resposta', null);
$checklist_indicador = getParam($_REQUEST, 'checklist_indicador', null);
$checklist_calendario = getParam($_REQUEST, 'checklist_calendario', null);
$checklist_monitoramento = getParam($_REQUEST, 'checklist_monitoramento', null);
$checklist_ata = getParam($_REQUEST, 'checklist_ata', null);
$checklist_swot = getParam($_REQUEST, 'checklist_swot', null);
$checklist_operativo = getParam($_REQUEST, 'checklist_operativo', null);
$checklist_instrumento = getParam($_REQUEST, 'checklist_instrumento', null);
$checklist_recurso = getParam($_REQUEST, 'checklist_recurso', null);
$checklist_problema = getParam($_REQUEST, 'checklist_problema', null);
$checklist_demanda = getParam($_REQUEST, 'checklist_demanda', null);
$checklist_programa = getParam($_REQUEST, 'checklist_programa', null);
$checklist_licao = getParam($_REQUEST, 'checklist_licao', null);
$checklist_evento = getParam($_REQUEST, 'checklist_evento', null);
$checklist_link = getParam($_REQUEST, 'checklist_link', null);
$checklist_avaliacao = getParam($_REQUEST, 'checklist_avaliacao', null);
$checklist_tgn = getParam($_REQUEST, 'checklist_tgn', null);
$checklist_brainstorm = getParam($_REQUEST, 'checklist_brainstorm', null);
$checklist_gut = getParam($_REQUEST, 'checklist_gut', null);
$checklist_causa_efeito = getParam($_REQUEST, 'checklist_causa_efeito', null);
$checklist_arquivo = getParam($_REQUEST, 'checklist_arquivo', null);
$checklist_forum = getParam($_REQUEST, 'checklist_forum', null);
$checklist_agenda = getParam($_REQUEST, 'checklist_agenda', null);
$checklist_agrupamento = getParam($_REQUEST, 'checklist_agrupamento', null);
$checklist_patrocinador = getParam($_REQUEST, 'checklist_patrocinador', null);
$checklist_template = getParam($_REQUEST, 'checklist_template', null);

if (
	$checklist_projeto ||
	$checklist_tarefa ||
	$checklist_perspectiva ||
	$checklist_tema ||
	$checklist_objetivo ||
	$checklist_fator ||
	$checklist_estrategia ||
	$checklist_meta ||
	$checklist_pratica ||
	$checklist_acao ||
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
	$sql->adTabela('cias');
	if ($checklist_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
	elseif ($checklist_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
	elseif ($checklist_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
	elseif ($checklist_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
	elseif ($checklist_objetivo) $sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','pg_objetivo_estrategico_cia=cias.cia_id');
	elseif ($checklist_fator) $sql->esqUnir('fatores_criticos','fatores_criticos','pg_fator_critico_cia=cias.cia_id');
	elseif ($checklist_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
	elseif ($checklist_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
	elseif ($checklist_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
	elseif ($checklist_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
	elseif ($checklist_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
	elseif ($checklist_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
	elseif ($checklist_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
	elseif ($checklist_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
	elseif ($checklist_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
	elseif ($checklist_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
	elseif ($checklist_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
	elseif ($checklist_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
	elseif ($checklist_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
	elseif ($checklist_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
	elseif ($checklist_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
	elseif ($checklist_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
	elseif ($checklist_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
	elseif ($checklist_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
	elseif ($checklist_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
	elseif ($checklist_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
	elseif ($checklist_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
	elseif ($checklist_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
	elseif ($checklist_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
	elseif ($checklist_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
	elseif ($checklist_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
	elseif ($checklist_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
	elseif ($checklist_arquivo) $sql->esqUnir('arquivos','arquivos','arquivo_cia=cias.cia_id');
	elseif ($checklist_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
	elseif ($checklist_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
	elseif ($checklist_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
	elseif ($checklist_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
	elseif ($checklist_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');

	if ($checklist_tarefa) $sql->adOnde('tarefa_id = '.(int)$checklist_tarefa);
	elseif ($checklist_projeto) $sql->adOnde('projeto_id = '.(int)$checklist_projeto);
	elseif ($checklist_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$checklist_perspectiva);
	elseif ($checklist_tema) $sql->adOnde('tema_id = '.(int)$checklist_tema);
	elseif ($checklist_objetivo) $sql->adOnde('pg_objetivo_estrategico_id = '.(int)$checklist_objetivo);
	elseif ($checklist_fator) $sql->adOnde('pg_fator_critico_id = '.(int)$checklist_fator);
	elseif ($checklist_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$checklist_estrategia);
	elseif ($checklist_meta) $sql->adOnde('pg_meta_id = '.(int)$checklist_meta);
	elseif ($checklist_pratica) $sql->adOnde('pratica_id = '.(int)$checklist_pratica);
	elseif ($checklist_acao) $sql->adOnde('plano_acao_id = '.(int)$checklist_acao);
	elseif ($checklist_canvas) $sql->adOnde('canvas_id = '.(int)$checklist_canvas);
	elseif ($checklist_risco) $sql->adOnde('risco_id = '.(int)$checklist_risco);
	elseif ($checklist_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$checklist_risco_resposta);
	elseif ($checklist_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$checklist_indicador);
	elseif ($checklist_calendario) $sql->adOnde('calendario_id = '.(int)$checklist_calendario);
	elseif ($checklist_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$checklist_monitoramento);
	elseif ($checklist_ata) $sql->adOnde('ata_id = '.(int)$checklist_ata);
	elseif ($checklist_swot) $sql->adOnde('swot_id = '.(int)$checklist_swot);
	elseif ($checklist_operativo) $sql->adOnde('operativo_id = '.(int)$checklist_operativo);
	elseif ($checklist_instrumento) $sql->adOnde('instrumento_id = '.(int)$checklist_instrumento);
	elseif ($checklist_recurso) $sql->adOnde('recurso_id = '.(int)$checklist_recurso);
	elseif ($checklist_problema) $sql->adOnde('problema_id = '.(int)$checklist_problema);
	elseif ($checklist_demanda) $sql->adOnde('demanda_id = '.(int)$checklist_demanda);
	elseif ($checklist_programa) $sql->adOnde('programa_id = '.(int)$checklist_programa);
	elseif ($checklist_licao) $sql->adOnde('licao_id = '.(int)$checklist_licao);
	elseif ($checklist_evento) $sql->adOnde('evento_id = '.(int)$checklist_evento);
	elseif ($checklist_link) $sql->adOnde('link_id = '.(int)$checklist_link);
	elseif ($checklist_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$checklist_avaliacao);
	elseif ($checklist_tgn) $sql->adOnde('tgn_id = '.(int)$checklist_tgn);
	elseif ($checklist_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$checklist_brainstorm);
	elseif ($checklist_gut) $sql->adOnde('gut_id = '.(int)$checklist_gut);
	elseif ($checklist_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$checklist_causa_efeito);
	elseif ($checklist_arquivo) $sql->adOnde('arquivo_id = '.(int)$checklist_arquivo);
	elseif ($checklist_forum) $sql->adOnde('forum_id = '.(int)$checklist_forum);
	elseif ($checklist_agenda) $sql->adOnde('agenda_id = '.(int)$checklist_agenda);
	elseif ($checklist_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$checklist_agrupamento);
	elseif ($checklist_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$checklist_patrocinador);
	elseif ($checklist_template) $sql->adOnde('template_id = '.(int)$checklist_template);
	$sql->adCampo('cia_id');
	$cia_id = $sql->Resultado();
	$sql->limpar();
	}


$usuarios_selecionados=array();
$indicadores =array();
$depts_selecionados = array();
$cias_selecionadas = array();
if ($checklist_id) {
	$sql->adTabela('checklist_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('checklist_id = '.(int)$checklist_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('pratica_indicador');
	$sql->adCampo('DISTINCT pratica_indicador_id');
	$sql->adOnde('pratica_indicador_checklist = '.(int)$checklist_id);
	$indicadores = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('checklist_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('checklist_id ='.(int)$checklist_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('checklist_cia');
		$sql->adCampo('checklist_cia_cia');
		$sql->adOnde('checklist_cia_checklist = '.(int)$checklist_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="checklist_editar" />';
echo '<input type="hidden" name="checklist_id" id="checklist_id" value="'.$checklist_id.'" />';
echo '<input name="checklist_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="pratica_indicador_ides" type="hidden" value="'.implode(',', $indicadores).'" />';
echo '<input name="checklist_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="checklist_cias"  id="checklist_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';

echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($checklist_id ? '' : uuid()).'" />';

echo estiloTopoCaixa();
echo '<table cellspacing=1 cellpadding=1 border=0 width="100%" class="std">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
echo '<tr><td align="right">'.dica('Nome do Checklist', 'Todo checklist necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="checklist_nome" value="'.($checklist['checklist_nome'] ? $checklist['checklist_nome'] : '').'" style="width:284px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'A qual '.$config['organizacao'].' pertence este checklist.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'checklist_cia', 'class=texto size=1 style="width:288px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
if ($Aplic->profissional) {
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			}
	else $saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}
if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por este checklist.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="checklist_dept" id="checklist_dept" value="'.($checklist_id ? $checklist['checklist_dept'] : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($checklist_id ? $checklist['checklist_dept'] : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

$saida_depts='';
if (count($depts_selecionados)) {
		$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_depts.= '<tr><td>'.link_secao($depts_selecionados[0]);
		$qnt_lista_depts=count($depts_selecionados);
		if ($qnt_lista_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		}
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap" width="100">'.dica('Responsável', 'Toda meta deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="checklist_responsavel" name="checklist_responsavel" value="'.($checklist['checklist_responsavel'] ? $checklist['checklist_responsavel'] : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($checklist['checklist_responsavel'] ? $checklist['checklist_responsavel'] : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';






echo '<tr><td align="right">'.dica('Descrição', 'Uma breve descrição do checklist, caso seja pertinente.').'Descrição</td><td><textarea data-gpweb-cmp="ckeditor" rows="10" name="checklist_descricao" id="checklist_descricao">'.$checklist['checklist_descricao'].'</textarea></td></tr>';
$sql->adTabela('checklist_modelo');
$sql->adCampo('checklist_modelo_id, checklist_modelo_nome');
$modelos = $sql->listaVetorChave('checklist_modelo_id', 'checklist_modelo_nome');
$sql->limpar();

echo '<tr><td align="right" nowrap="nowrap">'.dica('Modelo', 'Escolha o modelo de checklist.').'Modelo:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($modelos, 'checklist_modelo', 'class="texto" style="width:284px;"', ($checklist['checklist_modelo'] ? $checklist['checklist_modelo'] : '1')).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Checklist Superior', 'Caso este check list tenha um cheklist superior selecione o mesmo.').'Checklist superior:'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="checklist_superior" name="checklist_superior" value="'.(isset($checklist['checklist_superior']) ? $checklist['checklist_superior'] : null).'" /><input type="text" id="nome_checklist" name="nome_checklist" value="'.nome_checklist((isset($checklist['checklist_superior']) ? $checklist['checklist_superior'] : null)).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';


$sql->adTabela('pratica_indicador');
$sql->adCampo('DISTINCT pratica_indicador_id');
$sql->adOnde('pratica_indicador_checklist = '.(int)$checklist_id);
$lista_indicadores = $sql->carregarColuna();

$sql->limpar();
$saida_indicador='';
if ($lista_indicadores && count($lista_indicadores)) {
		$saida_indicador.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_indicador.= '<tr><td>'.link_indicador($lista_indicadores[0]);
		$qnt_lista_indicadores=count($lista_indicadores);
		if ($qnt_lista_indicadores > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_indicadores; $i < $i_cmp; $i++) $lista.=link_indicador($lista_indicadores[$i]).'<br>';
				$saida_indicador.= dica('Outros Indicadores', 'Clique para visualizar os demais indicadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_indicadores\');">(+'.($qnt_lista_indicadores - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_indicadores"><br>'.$lista.'</span>';
				}
		$saida_indicador.= '</td></tr></table>';
		}
if ($saida_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador', 'Qual indicador está relacionado a este checklist.').'Indicador:'.dicaF().'</td><td width="100%" colspan="2">'.$saida_indicador.'</td></tr>';


if ($Aplic->profissional){

	$tipos=array(
		''=>'',
		'projeto' => ucfirst($config['projeto']),
		'perspectiva'=> ucfirst($config['perspectiva']),
		'tema'=> ucfirst($config['tema']),
		'objetivo'=> ucfirst($config['objetivo']),
		'estrategia'=> ucfirst($config['iniciativa']),
		'meta'=>ucfirst($config['meta']),
		'acao'=> ucfirst($config['acao']),
		'pratica' => ucfirst($config['pratica']),
		'indicador' => 'Indicador',
		);
	if (!$Aplic->profissional || ($Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator'))) $tipos['fator']=ucfirst($config['fator']);		
	if ($ata_ativo) $tipos['ata']='Ata de Reunião';
	if ($swot_ativo) $tipos['swot']='Campo SWOT';
	if ($operativo_ativo) $tipos['operativo']='Plano Operativo';

	$tipos['canvas']=ucfirst($config['canvas']);
	$tipos['risco']=ucfirst($config['risco']);
	$tipos['risco_resposta']=ucfirst($config['risco_resposta']);
	$tipos['calendario']='Agenda';
	$tipos['monitoramento']='Monitoramento';
	$tipos['instrumento']=ucfirst($config['instrumento']);
	$tipos['recurso']=ucfirst($config['recurso']);
	if ($problema_ativo) $tipos['problema']=ucfirst($config['problema']);
	$tipos['demanda']='Demanda';
	$tipos['programa']=ucfirst($config['programa']);
	$tipos['licao']=ucfirst($config['licao']);
	$tipos['evento']='Evento';
	$tipos['link']='Link';
	$tipos['avaliacao']='Avaliação';
	$tipos['tgn']=ucfirst($config['tgn']);
	$tipos['brainstorm']='Brainstorm';
	$tipos['gut']='Matriz G.U.T.';
	$tipos['causa_efeito']='Diagrama de Causa-Efeito';
	$tipos['arquivo']='Arquivo';
	$tipos['forum']='Fórum';
	$tipos['agenda']='Compromisso';
	if ($agrupamento_ativo) $tipos['agrupamento']='Agrupamento';
	if ($patrocinador_ativo) $tipos['patrocinador']='Patrocinador';
	$tipos['template']='Modelo';
	asort($tipos);

	if ($checklist_projeto) $tipo='projeto';
	elseif ($checklist_pratica) $tipo='pratica';
	elseif ($checklist_acao) $tipo='acao';
	elseif ($checklist_objetivo) $tipo='objetivo';
	elseif ($checklist_tema) $tipo='tema';
	elseif ($checklist_fator) $tipo='fator';
	elseif ($checklist_estrategia) $tipo='estrategia';
	elseif ($checklist_perspectiva) $tipo='perspectiva';
	elseif ($checklist_canvas) $tipo='canvas';
	elseif ($checklist_risco) $tipo='risco';
	elseif ($checklist_risco_resposta) $tipo='risco_resposta';
	elseif ($checklist_meta) $tipo='meta';
	elseif ($checklist_indicador) $tipo='checklist_indicador';
	elseif ($checklist_swot) $tipo='swot';
	elseif ($checklist_ata) $tipo='ata';
	elseif ($checklist_monitoramento) $tipo='monitoramento';
	elseif ($checklist_calendario) $tipo='calendario';
	elseif ($checklist_operativo) $tipo='operativo';
	elseif ($checklist_instrumento) $tipo='instrumento';
	elseif ($checklist_recurso) $tipo='recurso';
	elseif ($checklist_problema) $tipo='problema';
	elseif ($checklist_demanda) $tipo='demanda';
	elseif ($checklist_programa) $tipo='programa';
	elseif ($checklist_licao) $tipo='licao';
	elseif ($checklist_evento) $tipo='evento';
	elseif ($checklist_link) $tipo='link';
	elseif ($checklist_avaliacao) $tipo='avaliacao';
	elseif ($checklist_tgn) $tipo='tgn';
	elseif ($checklist_brainstorm) $tipo='brainstorm';
	elseif ($checklist_gut) $tipo='gut';
	elseif ($checklist_causa_efeito) $tipo='causa_efeito';
	elseif ($checklist_arquivo) $tipo='arquivo';
	elseif ($checklist_forum) $tipo='forum';
	elseif ($checklist_agenda) $tipo='agenda';
	elseif ($checklist_agrupamento) $tipo='agrupamento';
	elseif ($checklist_patrocinador) $tipo='patrocinador';
	elseif ($checklist_template) $tipo='template';


	else $tipo='';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Relacionad'.$config['genero_mensagem'],'A qual parte do sistema o checklist está relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:284px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';
	echo '<tr '.($checklist_projeto || $checklist_tarefa ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso o checklist seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_projeto" value="'.$checklist_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($checklist_projeto).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a>'.($Aplic->profissional ? '<a href="javascript: void(0);" onclick="incluir_relacionado();">'.imagem('icones/adicionar.png','Adicionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar '.$config['genero_projeto'].' '.$config['projeto'].' escolhid'.$config['genero_projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';
	echo '<tr '.($checklist_projeto || $checklist_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionad'.$config['genero_tarefa'], 'Caso o checklist seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_tarefa" value="'.$checklist_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($checklist_tarefa).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso o checklist seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_pratica" value="'.$checklist_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($checklist_pratica).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso o checklist seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_acao" value="'.$checklist_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($checklist_acao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso o checklist seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_perspectiva" value="'.$checklist_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($checklist_perspectiva).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso o checklist seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_tema" value="'.$checklist_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($checklist_tema).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso o checklist seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_objetivo" value="'.$checklist_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($checklist_objetivo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso o checklist seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_estrategia" value="'.$checklist_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($checklist_estrategia).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso o checklist seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_fator" value="'.$checklist_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($checklist_fator).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']), 'Caso o checklist seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_meta" value="'.$checklist_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($checklist_meta).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" nowrap="nowrap">'.dica('Indicador', 'Caso o checklist seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_indicador" value="'.$checklist_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($checklist_indicador).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" nowrap="nowrap">'.dica('Monitoramento', 'Caso o checklist seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_monitoramento" value="'.$checklist_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($checklist_monitoramento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';

	if ($agrupamento_ativo) echo '<tr '.($checklist_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" nowrap="nowrap">'.dica('Agrupamento', 'Caso o checklist seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_agrupamento" value="'.$checklist_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($checklist_agrupamento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="checklist_agrupamento" value="" id="agrupamento" /><input type="hidden" id="agrupamento_nome" name="agrupamento_nome" value="">';

	if ($patrocinador_ativo) echo '<tr '.($checklist_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" nowrap="nowrap">'.dica('Patrocinador', 'Caso o checklist seja específico de um patrocinador, neste campo deverá constar o nome do patrocinador.').'Patrocinador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_patrocinador" value="'.$checklist_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($checklist_patrocinador).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar patrocinador','Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um patrocinador.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="checklist_patrocinador" value="" id="patrocinador" /><input type="hidden" id="patrocinador_nome" name="patrocinador_nome" value="">';

	echo '<tr '.($checklist_template ? '' : 'style="display:none"').' id="template" ><td align="right" nowrap="nowrap">'.dica('Modelo', 'Caso o checklist seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_template" value="'.$checklist_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($checklist_template).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';

	echo '<tr '.($checklist_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" nowrap="nowrap">'.dica('Agenda', 'Caso o checklist seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_calendario" value="'.$checklist_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($checklist_calendario).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/calendario_p.png','Selecionar calendario','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um calendario.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['instrumento']), 'Caso o checklist seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_instrumento" value="'.$checklist_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($checklist_instrumento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['recurso']), 'Caso o checklist seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_recurso" value="'.$checklist_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($checklist_recurso).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
	if ($problema_ativo) echo '<tr '.($checklist_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['problema']), 'Caso o checklist seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_problema" value="'.$checklist_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($checklist_problema).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="checklist_problema" value="" id="problema" /><input type="hidden" id="problema_nome" name="problema_nome" value="">';
	echo '<tr '.($checklist_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" nowrap="nowrap">'.dica('Demanda', 'Caso o checklist seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_demanda" value="'.$checklist_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($checklist_demanda).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar um demanda.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['programa']), 'Caso o checklist seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_programa" value="'.$checklist_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($checklist_programa).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['licao']), 'Caso o checklist seja específico de uma lição aprendida, neste campo deverá constar o nome da lição aprendida.').'Lição Aprendida:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_licao" value="'.$checklist_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($checklist_licao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar Lição Aprendida','Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar uma lição aprendida.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" nowrap="nowrap">'.dica('Evento', 'Caso o checklist seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_evento" value="'.$checklist_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($checklist_evento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_link ? '' : 'style="display:none"').' id="link" ><td align="right" nowrap="nowrap">'.dica('link', 'Caso o checklist seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_link" value="'.$checklist_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($checklist_link).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" nowrap="nowrap">'.dica('Avaliação', 'Caso o checklist seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_avaliacao" value="'.$checklist_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($checklist_avaliacao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tgn']), 'Caso o checklist seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_tgn" value="'.$checklist_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($checklist_tgn).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" nowrap="nowrap">'.dica('Brainstorm', 'Caso o checklist seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_brainstorm" value="'.$checklist_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($checklist_brainstorm).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" nowrap="nowrap">'.dica('Matriz G.U.T.', 'Caso o checklist seja específico de uma matriz G.U.T., neste campo deverá constar o nome da matriz G.U.T..').'Matriz G.U.T.:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_gut" value="'.$checklist_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($checklist_gut).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz G.U.T.','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" nowrap="nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso o checklist seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_causa_efeito" value="'.$checklist_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($checklist_causa_efeito).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" nowrap="nowrap">'.dica('Arquivo', 'Caso o checklist seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_arquivo" value="'.$checklist_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($checklist_arquivo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" nowrap="nowrap">'.dica('Fórum', 'Caso o checklist seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_forum" value="'.$checklist_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($checklist_forum).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" nowrap="nowrap">'.dica('Compromisso', 'Caso o checklist seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_agenda" value="'.$checklist_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($checklist_agenda).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';

	echo '<tr '.($checklist_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso o checklist seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_risco" value="'.$checklist_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($checklist_risco).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso o checklist seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_risco_resposta" value="'.$checklist_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($checklist_risco_resposta).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($checklist_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso o checklist seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_canvas" value="'.$checklist_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($checklist_canvas).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';

	if ($swot_ativo) echo '<tr '.(isset($checklist_swot) && $checklist_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" nowrap="nowrap">'.dica('Campo SWOT', 'Caso o checklist seja específico de um campo da matriz SWOT neste campo deverá constar o nome do campo da matriz SWOT').'campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_swot" value="'.(isset($checklist_swot) ? $checklist_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($checklist_swot) ? $checklist_swot : null)).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('../../../modulos/swot/imagens/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('../../../modulos/swot/imagens/swot_p.png').' para selecionar um campo da matriz SWOT.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="checklist_swot" value="" id="swot" /><input type="hidden" id="swot_nome" name="swot_nome" value="">';
	if ($ata_ativo) echo '<tr '.(isset($checklist_ata) && $checklist_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" nowrap="nowrap">'.dica('Ata de Reunião', 'Caso o checklist seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_ata" value="'.(isset($checklist_ata) ? $checklist_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($checklist_ata) ? $checklist_ata : null)).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('../../../modulos/atas/imagens/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('../../../modulos/atas/imagens/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="checklist_ata" value="" id="ata" /><input type="hidden" id="ata_nome" name="ata_nome" value="">';
	if ($operativo_ativo) echo '<tr '.($checklist_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso o checklist seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_operativo" value="'.$checklist_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($checklist_operativo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="checklist_operativo" value="" id="operativo" /><input type="hidden" id="operativo_nome" name="operativo_nome" value="">';

	$sql->adTabela('checklist_gestao');
	$sql->adCampo('checklist_gestao.*');
	$sql->adOnde('checklist_gestao_checklist ='.(int)$checklist_id);
	$sql->adOrdem('checklist_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
	echo '<tr><td></td><td><div id="combo_gestao">';
	if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		if ($gestao_data['checklist_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['checklist_gestao_tarefa']).'</td>';
		elseif ($gestao_data['checklist_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['checklist_gestao_projeto']).'</td>';
		elseif ($gestao_data['checklist_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['checklist_gestao_pratica']).'</td>';
		elseif ($gestao_data['checklist_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['checklist_gestao_acao']).'</td>';
		elseif ($gestao_data['checklist_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['checklist_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['checklist_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['checklist_gestao_tema']).'</td>';
		elseif ($gestao_data['checklist_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['checklist_gestao_objetivo']).'</td>';
		elseif ($gestao_data['checklist_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['checklist_gestao_fator']).'</td>';
		elseif ($gestao_data['checklist_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['checklist_gestao_estrategia']).'</td>';
		elseif ($gestao_data['checklist_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['checklist_gestao_meta']).'</td>';
		elseif ($gestao_data['checklist_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['checklist_gestao_canvas']).'</td>';
		elseif ($gestao_data['checklist_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['checklist_gestao_risco']).'</td>';
		elseif ($gestao_data['checklist_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['checklist_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['checklist_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['checklist_gestao_indicador']).'</td>';
		elseif ($gestao_data['checklist_gestao_calendario']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_calendario($gestao_data['checklist_gestao_calendario']).'</td>';
		elseif ($gestao_data['checklist_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['checklist_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['checklist_gestao_ata']) echo '<td align=left>'.imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['checklist_gestao_ata']).'</td>';
		elseif ($gestao_data['checklist_gestao_swot']) echo '<td align=left>'.imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['checklist_gestao_swot']).'</td>';
		elseif ($gestao_data['checklist_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['checklist_gestao_operativo']).'</td>';
		elseif ($gestao_data['checklist_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['checklist_gestao_instrumento']).'</td>';
		elseif ($gestao_data['checklist_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['checklist_gestao_recurso']).'</td>';
		elseif ($gestao_data['checklist_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema_pro($gestao_data['checklist_gestao_problema']).'</td>';
		elseif ($gestao_data['checklist_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['checklist_gestao_demanda']).'</td>';
		elseif ($gestao_data['checklist_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['checklist_gestao_programa']).'</td>';
		elseif ($gestao_data['checklist_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['checklist_gestao_licao']).'</td>';
		elseif ($gestao_data['checklist_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['checklist_gestao_evento']).'</td>';
		elseif ($gestao_data['checklist_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['checklist_gestao_link']).'</td>';
		elseif ($gestao_data['checklist_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['checklist_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['checklist_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['checklist_gestao_tgn']).'</td>';
		elseif ($gestao_data['checklist_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['checklist_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['checklist_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut_pro($gestao_data['checklist_gestao_gut']).'</td>';
		elseif ($gestao_data['checklist_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['checklist_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['checklist_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['checklist_gestao_arquivo']).'</td>';
		elseif ($gestao_data['checklist_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['checklist_gestao_forum']).'</td>';
		elseif ($gestao_data['checklist_gestao_agenda']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_agenda($gestao_data['checklist_gestao_agenda']).'</td>';
		elseif ($gestao_data['checklist_gestao_agrupamento']) echo '<td align=left>'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['checklist_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['checklist_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['checklist_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['checklist_gestao_template']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_template($gestao_data['checklist_gestao_template']).'</td>';
		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['checklist_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) echo '</table>';
	echo '</div></td></tr>';
	}


if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_checklist = '.(int)$checklist_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'checklist_principal_indicador', 'class="texto" style="width:284px;"', $checklist['checklist_principal_indicador']).'</td></tr>';
	else echo '<input type="hidden" name="checklist_principal_indicador" value="" />';
	}
else echo '<input type="hidden" name="checklist_principal_indicador" value="" />';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="checklist_cor" value="'.($checklist['checklist_cor'] ? $checklist['checklist_cor'] : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($checklist['checklist_cor'] ? $checklist['checklist_cor'] : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'Os checklist podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o checklist.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para o checklist podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para o checklist ver e editar o checklist</li><li><b>Privado</b> - Somente o responsável e os designados para o checklist podem ver a mesma, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($checklist_acesso, 'checklist_acesso', 'class="texto"', ($checklist_id ? $checklist['checklist_acesso'] : $config['nivel_acesso_padrao'])).'</td></tr>';

echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o checklist ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="checklist_ativo" '.($checklist['checklist_ativo'] || !$checklist_id ? 'checked="checked"' : '').' /></td></tr>';

$campos_customizados = new CampoCustomizados('checklist', $checklist_id, 'editar');
$campos_customizados->imprimirHTML();
















echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_perguntas\').style.display) document.getElementById(\'apresentar_perguntas\').style.display=\'\'; else document.getElementById(\'apresentar_perguntas\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Perguntas</b></a></td></tr>';
echo '<tr id="apresentar_perguntas" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width=100%>';

echo '<input type="hidden" id="checklist_lista_id" name="checklist_lista_id" value="" />';
echo '<input type="hidden" id="texto_apoio" name="texto_apoio" value="" />';

	echo '<tr><td><table cellspacing=0 cellpadding=0>';

	echo '<tr><td><table cellspacing=0 cellpadding=0>';
	echo '<tr><td align="right" style="width:85px;">'.dica('Peso', 'O peso da pergunta do checklist.').'Peso:'.dicaF().'</td><td><input type="text" id="checklist_lista_peso" name="checklist_lista_peso" value="1" style="width:200px;" class="texto" onkeypress="return somenteFloat(event)" /></td></tr>';
	echo '<tr><td align="right">'.dica('Descrição', 'O detalhamento da pergunta do checklist.').'Descrição:'.dicaF().'</td><td style="width:550px;"><textarea data-gpweb-cmp="ckeditor" rows="2" class="texto" name="checklist_lista_descricao" id="checklist_lista_descricao"></textarea></td></tr>';
	echo '<tr><td align="right">'.dica('Legenda', 'Marque caso esta linha seja apenas uma leganda.').'Legenda:'.dicaF().'</td><td><input type="checkbox" value="1" name="checklist_lista_legenda" id="checklist_lista_legenda" /></td></tr>';
	echo '</table></td>
	<td id="adicionar_pergunta" style="display:"><a href="javascript: void(0);" onclick="incluir_pergunta();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir a pergunta do checklist.').'</a></td>';
	echo '<td id="confirmar_pergunta" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'checklist_lista_id\').value=0;	document.getElementById(\'checklist_lista_peso\').value=\'1\'; document.getElementById(\'texto_apoio\').value=\'\'; CKEDITOR.instances[\'checklist_lista_descricao\'].setData(\'\'); document.getElementById(\'adicionar_pergunta\').style.display=\'\'; document.getElementById(\'checklist_lista_legenda\').checked=false; document.getElementById(\'confirmar_pergunta\').style.display=\'none\';">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição da pergunta do checklist.').'</a><a href="javascript: void(0);" onclick="incluir_pergunta();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição da pergunta do checklist.').'</a></td></tr></table></td></tr>';


	$sql->adTabela('checklist_lista');
	$sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_id);
	$sql->adCampo('checklist_lista.*');
	$sql->adOrdem('checklist_lista_ordem');
	$perguntas=$sql->ListaChave('checklist_lista_id');
	$sql->limpar();
	echo '<tr><td colspan=20 align=left><table cellspacing=0 cellpadding=0><tr><td style="width:85px;"></td><td><div id="perguntas">';
	if (count($perguntas)) {
		echo '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>Peso</th><th>Pertgunta</th><th width=32></th></tr>';
		foreach ($perguntas as $checklist_lista_id => $linha) {
			echo '<tr>';
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			if (!$linha['checklist_lista_legenda']) echo '<td align="center">'.((float)$linha['checklist_lista_peso']==(int)$linha['checklist_lista_peso'] ? (int)$linha['checklist_lista_peso']  : number_format((float)$linha['checklist_lista_peso'], 2, ',', '.')).'</td>';
			echo '<td align="left" '.($linha['checklist_lista_legenda'] ? 'colspan=2' : '').'>'.($linha['checklist_lista_descricao'] ? $linha['checklist_lista_descricao'] : '&nbsp;').'</td>';
			echo '<td><a href="javascript: void(0);" onclick="editar_pergunta('.$linha['checklist_lista_id'].');">'.imagem('icones/editar.gif', 'Editar Fluxo', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a pergunta do checklist.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este pergunta?\')) {excluir_pergunta('.$linha['checklist_lista_id'].');}">'.imagem('icones/remover.png', 'Excluir Fluxo', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este pergunta de entrada.').'</a></td>';
			echo '</tr>';
			}
		echo '</table></td></tr></table>';
		}

	echo '</div></td></tr>';

	echo '</table></td></tr>';









echo '</table></td></tr>';


echo '<input type="hidden" name="perguntas_quantidade" id="perguntas_quantidade" value="'.count($perguntas).'" />';






echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($checklist_id ? 'edição' : 'criação').' do pratica.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">
	
function somenteFloat(e){
	var tecla=new Number();
	if(window.event) tecla = e.keyCode;
	else if(e.which) tecla = e.which;
	else return true;
	if(((tecla < "48") && tecla !="44") || (tecla > "57")) return false;
	}	
		
function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('checklist_cia').value+'&cias_id_selecionadas='+document.getElementById('checklist_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.checklist_cias.value = organizacao_id_string;
	document.getElementById('checklist_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('checklist_cias').value);
	__buildTooltip();
	}

function mudar_posicao_pergunta(checklist_lista_ordem, checklist_lista_id, direcao){
	xajax_mudar_posicao_pergunta_ajax(checklist_lista_ordem, checklist_lista_id, direcao, document.getElementById('checklist_id').value, document.getElementById('uuid').value);
	}

function editar_pergunta(checklist_lista_id){
	xajax_editar_pergunta(checklist_lista_id);
	document.getElementById('adicionar_pergunta').style.display="none";
	document.getElementById('confirmar_pergunta').style.display="";
	CKEDITOR.instances['checklist_lista_descricao'].setData(document.getElementById('texto_apoio').value);
	}

function incluir_pergunta(){

	var texto=CKEDITOR.instances['checklist_lista_descricao'].getData();
	var peso=document.getElementById('checklist_lista_peso').value;
	var legenda=document.getElementById('checklist_lista_legenda').checked;

	if (peso.length > 0 && texto.length > 0){
		xajax_incluir_pergunta_ajax(document.getElementById('checklist_id').value, document.getElementById('uuid').value, document.getElementById('checklist_lista_id').value, peso, texto, legenda);
		document.getElementById('checklist_lista_id').value=null;
		document.getElementById('checklist_lista_peso').value='1';
		CKEDITOR.instances['checklist_lista_descricao'].setData('');
		document.getElementById('checklist_lista_legenda').checked=false;
		document.getElementById('adicionar_pergunta').style.display='';
		document.getElementById('confirmar_pergunta').style.display='none';
		}
	else if (peso.length < 1) alert('Insira um peso para a pergunta do checklist.');
	else if (texto.length < 1) alert('Insira a descrição da pergunta do checklist.');
	}

function excluir_pergunta(checklist_lista_id){
	xajax_excluir_pergunta_ajax(checklist_lista_id, document.getElementById('checklist_id').value, document.getElementById('uuid').value);
	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('checklist_cia').value+'&usuario_id='+document.getElementById('checklist_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('checklist_cia').value+'&usuario_id='+document.getElementById('checklist_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('checklist_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('checklist_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('checklist_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.checklist_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('checklist_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('checklist_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.checklist_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('checklist_dept').value+'&cia_id='+document.getElementById('checklist_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('checklist_dept').value+'&cia_id='+document.getElementById('checklist_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('checklist_cia').value=cia_id;
	document.getElementById('checklist_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}



function mudar_om(){
	var cia_id=document.getElementById('checklist_cia').value;
	xajax_selecionar_om_ajax(cia_id,'checklist_cia','combo_cia', 'class="texto" size=1 style="width:288px;" onchange="javascript:mudar_om();"');
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir este checklist?")) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql';
		f.modulo.value='checklist';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.checklist_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.checklist_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.checklist_nome.value.length < 3) {
		alert('Escreva um nome para o checklist válido');
		f.checklist_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}


function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('checklist_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('checklist_cia').value, 'Checklist','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}
function setChecklist(chave, valor){

	document.getElementById('checklist_superior').value=(chave > 0 ? chave : null);
	document.getElementById('nome_checklist').value=valor;
	}




function mostrar(){
	limpar_tudo();
	esconder_tipo();
	if (document.getElementById('tipo_relacao').value){
		document.getElementById(document.getElementById('tipo_relacao').value).style.display='';
		if (document.getElementById('tipo_relacao').value=='projeto') document.getElementById('tarefa').style.display='';
		}
	}

function esconder_tipo(){
	document.getElementById('projeto').style.display='none';
	document.getElementById('tarefa').style.display='none';
	document.getElementById('pratica').style.display='none';
	document.getElementById('indicador').style.display='none';
	document.getElementById('acao').style.display='none';
	document.getElementById('objetivo').style.display='none';
	document.getElementById('estrategia').style.display='none';
	document.getElementById('fator').style.display='none';
	document.getElementById('perspectiva').style.display='none';
	document.getElementById('canvas').style.display='none';
	document.getElementById('risco').style.display='none';
	document.getElementById('risco_resposta').style.display='none';
	document.getElementById('meta').style.display='none';
	document.getElementById('tema').style.display='none';
	document.getElementById('calendario').style.display='none';
	document.getElementById('monitoramento').style.display='none';
	document.getElementById('instrumento').style.display='none';
	document.getElementById('recurso').style.display='none';
	document.getElementById('problema').style.display='none';
	document.getElementById('demanda').style.display='none';
	document.getElementById('programa').style.display='none';
	document.getElementById('licao').style.display='none';
	document.getElementById('evento').style.display='none';
	document.getElementById('link').style.display='none';
	document.getElementById('avaliacao').style.display='none';
	document.getElementById('tgn').style.display='none';
	document.getElementById('brainstorm').style.display='none';
	document.getElementById('gut').style.display='none';
	document.getElementById('causa_efeito').style.display='none';
	document.getElementById('arquivo').style.display='none';
	document.getElementById('forum').style.display='none';
	document.getElementById('agenda').style.display='none';
	document.getElementById('template').style.display='none';
	<?php
	if($agrupamento_ativo) echo 'document.getElementById(\'agrupamento\').style.display=\'none\';';
	if($patrocinador_ativo) echo 'document.getElementById(\'patrocinador\').style.display=\'none\';';
	if($swot_ativo) echo 'document.getElementById(\'swot\').style.display=\'none\';';
	if($ata_ativo) echo 'document.getElementById(\'ata\').style.display=\'none\';';
	if($operativo_ativo) echo 'document.getElementById(\'operativo\').style.display=\'none\';';
	?>
	}


<?php  if ($Aplic->profissional) { ?>

	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('checklist_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('checklist_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.checklist_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Patrocinador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('checklist_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('checklist_cia').value, 'Patrocinador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.checklist_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('checklist_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('checklist_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.checklist_template.value = chave;
		document.env.template_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}


<?php } ?>


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('checklist_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.checklist_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	}

function popTarefa() {
	var f = document.env;
	if (f.checklist_projeto.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.checklist_projeto.value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.checklist_projeto.value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.checklist_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('checklist_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.checklist_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('checklist_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.checklist_tema.value = chave;
	document.env.tema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('checklist_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.checklist_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('checklist_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.checklist_fator.value = chave;
	document.env.fator_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('checklist_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.checklist_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('checklist_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.checklist_meta.value = chave;
	document.env.meta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('checklist_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.checklist_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('checklist_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('checklist_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.checklist_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('checklist_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.checklist_acao.value = chave;
	document.env.acao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('checklist_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.checklist_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('checklist_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRisco(chave, valor){
	limpar_tudo();
	document.env.checklist_risco.value = chave;
	document.env.risco_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco_respostas'])) { ?>
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('checklist_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('checklist_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.checklist_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>


function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('checklist_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('checklist_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.checklist_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('checklist_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('checklist_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.checklist_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('checklist_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.checklist_ata.value = chave;
	document.env.ata_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popSWOT() {
	parent.gpwebApp.popUp('SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('checklist_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.checklist_swot.value = chave;
	document.env.swot_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('checklist_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('checklist_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.checklist_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	}

function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('checklist_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('checklist_cia').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.checklist_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('checklist_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('checklist_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.checklist_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('checklist_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.checklist_problema.value = chave;
	document.env.problema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('checklist_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('checklist_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.checklist_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('checklist_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.checklist_programa.value = chave;
	document.env.programa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('checklist_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.checklist_licao.value = chave;
	document.env.licao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}


function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('checklist_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('checklist_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.checklist_evento.value = chave;
	document.env.evento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('checklist_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('checklist_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.checklist_link.value = chave;
	document.env.link_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('checklist_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('checklist_cia').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.checklist_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('checklist_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.checklist_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('checklist_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('checklist_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.checklist_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('checklist_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('checklist_cia').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.checklist_gut.value = chave;
	document.env.gut_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('checklist_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('checklist_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.checklist_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('checklist_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('checklist_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.checklist_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('checklist_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('checklist_cia').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.checklist_forum.value = chave;
	document.env.forum_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('checklist_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('checklist_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.checklist_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}



function limpar_tudo(){
	if (document.getElementById('tipo_relacao').value!='projeto'){
		document.env.projeto_nome.value = '';
		document.env.checklist_projeto.value = null;
		}
	document.env.checklist_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.checklist_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.checklist_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.checklist_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.checklist_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.checklist_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.checklist_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.checklist_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.checklist_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.checklist_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.checklist_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.checklist_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.checklist_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.checklist_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.checklist_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.checklist_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.checklist_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.checklist_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.checklist_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.checklist_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.checklist_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.checklist_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.checklist_link.value = null;
	document.env.link_nome.value = '';
	document.env.checklist_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.checklist_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.checklist_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.checklist_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.checklist_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.checklist_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.checklist_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.checklist_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.checklist_template.value = null;
	document.env.template_nome.value = '';
	<?php
	if($swot_ativo) echo 'document.env.swot_nome.value = \'\';	document.env.checklist_swot.value = null;';
	if($ata_ativo) echo 'document.env.ata_nome.value = \'\';	document.env.checklist_ata.value = null;';
	if($operativo_ativo) echo 'document.env.operativo_nome.value = \'\';	document.env.checklist_operativo.value = null;';
	if($agrupamento_ativo) echo 'document.env.agrupamento_nome.value = \'\';	document.env.checklist_agrupamento.value = null;';
	if($patrocinador_ativo) echo 'document.env.patrocinador_nome.value = \'\';	document.env.checklist_patrocinador.value = null;';
	?>
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('checklist_id').value,
	document.getElementById('uuid').value,
	f.checklist_projeto.value,
	f.checklist_tarefa.value,
	f.checklist_perspectiva.value,
	f.checklist_tema.value,
	f.checklist_objetivo.value,
	f.checklist_fator.value,
	f.checklist_estrategia.value,
	f.checklist_meta.value,
	f.checklist_pratica.value,
	f.checklist_acao.value,
	f.checklist_canvas.value,
	f.checklist_risco.value,
	f.checklist_risco_resposta.value,
	f.checklist_indicador.value,
	f.checklist_calendario.value,
	f.checklist_monitoramento.value,
	f.checklist_ata.value,
	f.checklist_swot.value,
	f.checklist_operativo.value,
	f.checklist_instrumento.value,
	f.checklist_recurso.value,
	f.checklist_problema.value,
	f.checklist_demanda.value,
	f.checklist_programa.value,
	f.checklist_licao.value,
	f.checklist_evento.value,
	f.checklist_link.value,
	f.checklist_avaliacao.value,
	f.checklist_tgn.value,
	f.checklist_brainstorm.value,
	f.checklist_gut.value,
	f.checklist_causa_efeito.value,
	f.checklist_arquivo.value,
	f.checklist_forum.value,
	f.checklist_agenda.value,
	f.checklist_agrupamento.value,
	f.checklist_patrocinador.value,
	f.checklist_template.value

	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(checklist_gestao_id){
	xajax_excluir_gestao(document.getElementById('checklist_id').value, document.getElementById('uuid').value, checklist_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, checklist_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, checklist_gestao_id, direcao, document.getElementById('checklist_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$checklist_id && (
	$checklist_projeto ||
	$checklist_tarefa ||
	$checklist_perspectiva ||
	$checklist_tema ||
	$checklist_objetivo ||
	$checklist_fator ||
	$checklist_estrategia ||
	$checklist_meta ||
	$checklist_pratica ||
	$checklist_acao ||
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
	)) echo 'incluir_relacionado();';
	?>

</script>

