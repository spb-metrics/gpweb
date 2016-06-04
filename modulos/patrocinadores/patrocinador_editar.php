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

$patrocinador_id = intval(getParam($_REQUEST, 'patrocinador_id', 0));

if ($patrocinador_id && !($podeEditar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$patrocinador_id && !($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

require_once ($Aplic->getClasseSistema('CampoCustomizados'));


$Aplic->carregarCalendarioJS();

$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

$obj = new CPatrocinador;
$obj->load($patrocinador_id);

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();

$cidades=array(''=>'');
$sql->adTabela('municipios');
$sql->adCampo('municipio_id, municipio_nome');
$sql->adOnde('estado_sigla=\''.$obj->patrocinador_estado.'\'');
$sql->adOrdem('municipio_nome');
$cidades+= $sql->listaVetorChave('municipio_id', 'municipio_nome');
$sql->limpar();

$paises = array('' => '(Selecione um país)') + getPais('Paises');


$cia_id = ($obj->patrocinador_cia ? $obj->patrocinador_cia : ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia));



if(!($podeEditar && permiteEditarPatrocinador($obj->patrocinador_acesso,$patrocinador_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');


$patrocinador_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

if ((!$podeEditar && $patrocinador_id > 0) || (!$podeAdicionar && $patrocinador_id == 0)) $Aplic->redirecionar('m=publico&a=acesso_negado');


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



$patrocinador_projeto = getParam($_REQUEST, 'patrocinador_projeto', null);
$patrocinador_tarefa = getParam($_REQUEST, 'patrocinador_tarefa', null);
$patrocinador_perspectiva = getParam($_REQUEST, 'patrocinador_perspectiva', null);
$patrocinador_tema = getParam($_REQUEST, 'patrocinador_tema', null);
$patrocinador_objetivo = getParam($_REQUEST, 'patrocinador_objetivo', null);
$patrocinador_fator = getParam($_REQUEST, 'patrocinador_fator', null);
$patrocinador_estrategia = getParam($_REQUEST, 'patrocinador_estrategia', null);
$patrocinador_meta = getParam($_REQUEST, 'patrocinador_meta', null);
$patrocinador_pratica = getParam($_REQUEST, 'patrocinador_pratica', null);
$patrocinador_acao = getParam($_REQUEST, 'patrocinador_acao', null);
$patrocinador_canvas = getParam($_REQUEST, 'patrocinador_canvas', null);
$patrocinador_risco = getParam($_REQUEST, 'patrocinador_risco', null);
$patrocinador_risco_resposta = getParam($_REQUEST, 'patrocinador_risco_resposta', null);
$patrocinador_indicador = getParam($_REQUEST, 'patrocinador_indicador', null);
$patrocinador_calendario = getParam($_REQUEST, 'patrocinador_calendario', null);
$patrocinador_monitoramento = getParam($_REQUEST, 'patrocinador_monitoramento', null);
$patrocinador_ata = getParam($_REQUEST, 'patrocinador_ata', null);
$patrocinador_swot = getParam($_REQUEST, 'patrocinador_swot', null);
$patrocinador_operativo = getParam($_REQUEST, 'patrocinador_operativo', null);
$patrocinador_instrumento = getParam($_REQUEST, 'patrocinador_instrumento', null);
$patrocinador_recurso = getParam($_REQUEST, 'patrocinador_recurso', null);
$patrocinador_problema = getParam($_REQUEST, 'patrocinador_problema', null);
$patrocinador_demanda = getParam($_REQUEST, 'patrocinador_demanda', null);
$patrocinador_programa = getParam($_REQUEST, 'patrocinador_programa', null);
$patrocinador_licao = getParam($_REQUEST, 'patrocinador_licao', null);
$patrocinador_evento = getParam($_REQUEST, 'patrocinador_evento', null);
$patrocinador_link = getParam($_REQUEST, 'patrocinador_link', null);
$patrocinador_avaliacao = getParam($_REQUEST, 'patrocinador_avaliacao', null);
$patrocinador_tgn = getParam($_REQUEST, 'patrocinador_tgn', null);
$patrocinador_brainstorm = getParam($_REQUEST, 'patrocinador_brainstorm', null);
$patrocinador_gut = getParam($_REQUEST, 'patrocinador_gut', null);
$patrocinador_causa_efeito = getParam($_REQUEST, 'patrocinador_causa_efeito', null);
$patrocinador_arquivo = getParam($_REQUEST, 'patrocinador_arquivo', null);
$patrocinador_forum = getParam($_REQUEST, 'patrocinador_forum', null);
$patrocinador_checklist = getParam($_REQUEST, 'patrocinador_checklist', null);
$patrocinador_agenda = getParam($_REQUEST, 'patrocinador_agenda', null);
$patrocinador_agrupamento = getParam($_REQUEST, 'patrocinador_agrupamento', null);
$patrocinador_template = getParam($_REQUEST, 'patrocinador_template', null);

if (
	$patrocinador_projeto ||
	$patrocinador_tarefa ||
	$patrocinador_perspectiva ||
	$patrocinador_tema ||
	$patrocinador_objetivo ||
	$patrocinador_fator ||
	$patrocinador_estrategia ||
	$patrocinador_meta ||
	$patrocinador_pratica ||
	$patrocinador_acao ||
	$patrocinador_canvas ||
	$patrocinador_risco ||
	$patrocinador_risco_resposta ||
	$patrocinador_indicador ||
	$patrocinador_calendario ||
	$patrocinador_monitoramento ||
	$patrocinador_ata ||
	$patrocinador_swot ||
	$patrocinador_operativo ||
	$patrocinador_instrumento ||
	$patrocinador_recurso ||
	$patrocinador_problema ||
	$patrocinador_demanda ||
	$patrocinador_programa ||
	$patrocinador_licao ||
	$patrocinador_evento ||
	$patrocinador_link ||
	$patrocinador_avaliacao ||
	$patrocinador_tgn ||
	$patrocinador_brainstorm ||
	$patrocinador_gut ||
	$patrocinador_causa_efeito ||
	$patrocinador_arquivo ||
	$patrocinador_forum ||
	$patrocinador_checklist ||
	$patrocinador_agenda ||
	$patrocinador_agrupamento ||
	$patrocinador_template
	){
	$sql->adTabela('cias');
	if ($patrocinador_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
	elseif ($patrocinador_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
	elseif ($patrocinador_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
	elseif ($patrocinador_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
	elseif ($patrocinador_objetivo) $sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','pg_objetivo_estrategico_cia=cias.cia_id');
	elseif ($patrocinador_fator) $sql->esqUnir('fatores_criticos','fatores_criticos','pg_fator_critico_cia=cias.cia_id');
	elseif ($patrocinador_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
	elseif ($patrocinador_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
	elseif ($patrocinador_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
	elseif ($patrocinador_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
	elseif ($patrocinador_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
	elseif ($patrocinador_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
	elseif ($patrocinador_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
	elseif ($patrocinador_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
	elseif ($patrocinador_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
	elseif ($patrocinador_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
	elseif ($patrocinador_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
	elseif ($patrocinador_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
	elseif ($patrocinador_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
	elseif ($patrocinador_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
	elseif ($patrocinador_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
	elseif ($patrocinador_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
	elseif ($patrocinador_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
	elseif ($patrocinador_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
	elseif ($patrocinador_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
	elseif ($patrocinador_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
	elseif ($patrocinador_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
	elseif ($patrocinador_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
	elseif ($patrocinador_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
	elseif ($patrocinador_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
	elseif ($patrocinador_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
	elseif ($patrocinador_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
	elseif ($patrocinador_arquivo) $sql->esqUnir('arquivos','arquivos','arquivo_cia=cias.cia_id');
	elseif ($patrocinador_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
	elseif ($patrocinador_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
	elseif ($patrocinador_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
	elseif ($patrocinador_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
	elseif ($patrocinador_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');

	if ($patrocinador_tarefa) $sql->adOnde('tarefa_id = '.(int)$patrocinador_tarefa);
	elseif ($patrocinador_projeto) $sql->adOnde('projeto_id = '.(int)$patrocinador_projeto);
	elseif ($patrocinador_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$patrocinador_perspectiva);
	elseif ($patrocinador_tema) $sql->adOnde('tema_id = '.(int)$patrocinador_tema);
	elseif ($patrocinador_objetivo) $sql->adOnde('pg_objetivo_estrategico_id = '.(int)$patrocinador_objetivo);
	elseif ($patrocinador_fator) $sql->adOnde('pg_fator_critico_id = '.(int)$patrocinador_fator);
	elseif ($patrocinador_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$patrocinador_estrategia);
	elseif ($patrocinador_meta) $sql->adOnde('pg_meta_id = '.(int)$patrocinador_meta);
	elseif ($patrocinador_pratica) $sql->adOnde('pratica_id = '.(int)$patrocinador_pratica);
	elseif ($patrocinador_acao) $sql->adOnde('plano_acao_id = '.(int)$patrocinador_acao);
	elseif ($patrocinador_canvas) $sql->adOnde('canvas_id = '.(int)$patrocinador_canvas);
	elseif ($patrocinador_risco) $sql->adOnde('risco_id = '.(int)$patrocinador_risco);
	elseif ($patrocinador_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$patrocinador_risco_resposta);
	elseif ($patrocinador_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$patrocinador_indicador);
	elseif ($patrocinador_calendario) $sql->adOnde('calendario_id = '.(int)$patrocinador_calendario);
	elseif ($patrocinador_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$patrocinador_monitoramento);
	elseif ($patrocinador_ata) $sql->adOnde('ata_id = '.(int)$patrocinador_ata);
	elseif ($patrocinador_swot) $sql->adOnde('swot_id = '.(int)$patrocinador_swot);
	elseif ($patrocinador_operativo) $sql->adOnde('operativo_id = '.(int)$patrocinador_operativo);
	elseif ($patrocinador_instrumento) $sql->adOnde('instrumento_id = '.(int)$patrocinador_instrumento);
	elseif ($patrocinador_recurso) $sql->adOnde('recurso_id = '.(int)$patrocinador_recurso);
	elseif ($patrocinador_problema) $sql->adOnde('problema_id = '.(int)$patrocinador_problema);
	elseif ($patrocinador_demanda) $sql->adOnde('demanda_id = '.(int)$patrocinador_demanda);
	elseif ($patrocinador_programa) $sql->adOnde('programa_id = '.(int)$patrocinador_programa);
	elseif ($patrocinador_licao) $sql->adOnde('licao_id = '.(int)$patrocinador_licao);
	elseif ($patrocinador_evento) $sql->adOnde('evento_id = '.(int)$patrocinador_evento);
	elseif ($patrocinador_link) $sql->adOnde('link_id = '.(int)$patrocinador_link);
	elseif ($patrocinador_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$patrocinador_avaliacao);
	elseif ($patrocinador_tgn) $sql->adOnde('tgn_id = '.(int)$patrocinador_tgn);
	elseif ($patrocinador_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$patrocinador_brainstorm);
	elseif ($patrocinador_gut) $sql->adOnde('gut_id = '.(int)$patrocinador_gut);
	elseif ($patrocinador_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$patrocinador_causa_efeito);
	elseif ($patrocinador_arquivo) $sql->adOnde('arquivo_id = '.(int)$patrocinador_arquivo);
	elseif ($patrocinador_forum) $sql->adOnde('forum_id = '.(int)$patrocinador_forum);
	elseif ($patrocinador_checklist) $sql->adOnde('checklist_id = '.(int)$patrocinador_checklist);
	elseif ($patrocinador_agenda) $sql->adOnde('agenda_id = '.(int)$patrocinador_agenda);
	elseif ($patrocinador_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$patrocinador_agrupamento);
	elseif ($patrocinador_template) $sql->adOnde('template_id = '.(int)$patrocinador_template);
	$sql->adCampo('cia_id');
	$cia_id = $sql->Resultado();
	$sql->limpar();
	}






$df = '%d/%m/%Y';
$ttl = ($patrocinador_id ? 'Editar Patrocinador' : 'Criar Patrocinador');
$botoesTitulo = new CBlocoTitulo($ttl, '../../../modulos/Patrocinadores/imagens/patrocinador.gif', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$usuarios_selecionados=array();
$depts_selecionados=array();
$instrumentos_selecionados=array();
$cias_selecionadas = array();
if ($patrocinador_id) {
	$sql->adTabela('patrocinadores_usuarios', 'patrocinadores_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('patrocinador_id = '.(int)$patrocinador_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('patrocinadores_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('patrocinador_id ='.(int)$patrocinador_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('patrocinadores_instrumentos');
	$sql->adCampo('instrumento_id');
	$sql->adOnde('patrocinador_id ='.(int)$patrocinador_id);
	$instrumentos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('patrocinador_cia');
		$sql->adCampo('patrocinador_cia_cia');
		$sql->adOnde('patrocinador_cia_patrocinador = '.(int)$patrocinador_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="patrocinadores" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="patrocinador_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="patrocinador_id" id="patrocinador_id" value="'.$patrocinador_id.'" />';
echo '<input name="patrocinador_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="patrocinador_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="patrocinador_instrumentos" type="hidden" value="'.implode(',', $instrumentos_selecionados).'" />';
echo '<input name="patrocinador_cias"  id="patrocinador_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="editar" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($patrocinador_id ? '' : uuid()).'" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';

echo '<tr><td align="right">'.dica('Nome do Patrocinador', 'Todo patrocinador necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="patrocinador_nome" value="'.$obj->patrocinador_nome.'" style="width:286px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'A qual '.$config['organizacao'].' pertence este patrocinador.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'patrocinador_cia', 'class=texto size=1 style="width:290px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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

if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_objetivo']=='a' ? 'esta' : 'este').' '.$config['objetivo'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="patrocinador_dept" id="patrocinador_dept" value="'.($patrocinador_id ? $obj->patrocinador_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($patrocinador_id ? $obj->patrocinador_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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



echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável', 'Todo patrocinio deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="patrocinador_responsavel" name="patrocinador_responsavel" value="'.($obj->patrocinador_responsavel ? $obj->patrocinador_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->patrocinador_responsavel? $obj->patrocinador_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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





$saida_instrumentos='';
if (count($instrumentos_selecionados)) {
		$saida_instrumentos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_instrumentos.= '<tr><td>'.link_instrumento($instrumentos_selecionados[0],'','','esquerda');
		$qnt_lista_instrumentos=count($instrumentos_selecionados);
		if ($qnt_lista_instrumentos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_instrumentos; $i < $i_cmp; $i++) $lista.=link_instrumento($instrumentos_selecionados[$i],'','','esquerda').'<br>';
				$saida_instrumentos.= dica('Outr'.$config['genero_instrumento'].'s '.ucfirst($config['instrumentos']), 'Clique para visualizar '.$config['genero_instrumento'].'s demais '.strtolower($config['instrumentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_instrumentos\');">(+'.($qnt_lista_instrumentos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_instrumentos"><br>'.$lista.'</span>';
				}
		$saida_instrumentos.= '</td></tr></table>';
		}
else $saida_instrumentos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['instrumentos']), 'Quais '.strtolower($config['instrumentos']).' estão envolvid'.$config['genero_instrumento'].'s.').ucfirst($config['instrumentos']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_instrumentos">'.$saida_instrumentos.'</div></td><td>'.botao_icone('instrumento_p.png','Selecionar', 'selecionar '.$config['instrumentos'].'.','popInstrumentos()').'</td></tr></table></td></tr>';

echo '<tr><td align="right">'.dica('Descrição', 'Escreva uma descrição do patrocinador.').'Descrição:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0 style="width:288px;"><tr><td><textarea data-gpweb-cmp="ckeditor" rows="10" name="patrocinador_descricao" id="patrocinador_descricao">'.$obj->patrocinador_descricao.'</textarea></td></tr></table></td></tr>';
echo '<tr><td align="right">'.dica('CPF', 'Escreva o CPF do patrocinador.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização dos patrocinadores quando se trabalha com diversas '.$config['organizacao'].'.').'CPF:'.dicaF().'</td><td><input type="text" class="texto" name="patrocinador_cpf" value="'.$obj->patrocinador_cpf.'" maxlength="14" style="width:286px;" onchange="verificarCPF()" /></td></tr>';
echo '<tr><td align="right">'.dica('CNPJ', 'Escreva o CNPJ do patrocinador.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização dos patrocinadores quando se trabalha com diversas '.$config['organizacao'].'.').'CNPJ:'.dicaF().'</td><td><input type="text" class="texto" name="patrocinador_cnpj" value="'.$obj->patrocinador_cnpj.'" maxlength="18" style="width:286px;" onchange="verificarCNPJ()" /></td></tr>';
echo '<tr><td align="right">'.dica('Endereço', 'Escreva o enderço do patrocinador.').'Endereço:'.dicaF().'</td><td><input type="text" class="texto" name="patrocinador_endereco1" value="'.$obj->patrocinador_endereco1.'" style="width:286px;" maxlength="255" /></td></tr>';
echo '<tr><td align="right">'.dica('Complemento do Endereço', 'Escreva o complemento do enderço do patrocinador.').'Complemento:'.dicaF().'</td><td><input type="text" class="texto" name="patrocinador_endereco2" value="'.$obj->patrocinador_endereco2.'" style="width:286px;" maxlength="255" /></td></tr>';
echo '<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado do patrocinador.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'patrocinador_estado', 'class="texto" size="1" onchange="mudar_cidades();" style="width:290px;"', $obj->patrocinador_estado).'</td></tr>';
echo '<tr><td align="right">'.dica('Município', 'Selecione o município do patrocinador.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionaVetor($cidades,'patrocinador_cidade', 'class="texto" style="width:290px;"', $obj->patrocinador_cidade).'</div></td></tr>';
echo '<tr><td align="right">'.dica('CEP', 'Escreva o CEP do patrocinador.').'CEP:'.dicaF().'</td><td><input type="text" class="texto" name="patrocinador_cep" value="'.$obj->patrocinador_cep.'" style="width:286px;" maxlength="15" /></td></tr>';
echo '<tr><td align="right">'.dica('País', 'Escolha na caixa de opção à direita o País do patrocinador.').'País:'.dicaF().'</td><td>'.selecionaVetor($paises, 'patrocinador_pais', 'size="1" style="width:290px;" class="texto"', ($obj->patrocinador_pais ? $obj->patrocinador_pais : 'BR')).'</td></tr>';

echo '<tr><td align="right">'.dica('E-mail', 'Escreva o e-mail do patrocinador.').'E-mail:'.dicaF().'</td><td><input type="text" class="texto" name="patrocinador_email" style="width:286px;" value="'.$obj->patrocinador_email.'" /></td></tr>';
echo '<tr><td align="right">'.dica('Página Web', 'Escreva a URL da página web do patrocinador.').'Web:'.dicaF().'</td><td><input type="text" class="texto" name="patrocinador_url" style="width:286px;" value="'.$obj->patrocinador_url.'" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Comercial', 'Escreva o telefone comercial do patrocinador.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização dos patrocinadores quando se trabalha com diversas '.$config['organizacao'].'.').'Telefone Comercial:'.dicaF().'</td><td>(<input type="text" class="texto" name="patrocinador_dddtel" value="'.$obj->patrocinador_dddtel.'" maxlength="6" size="1" />) <input type="text" class="texto" name="patrocinador_tel" value="'.$obj->patrocinador_tel.'" maxlength="30" style="width:245px;" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Residencial', 'Escreva o telefone residencial do patrocinador.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização dos patrocinadores quando se trabalha com diversas '.$config['organizacao'].'.').'Telefone Residencial:'.dicaF().'</td><td>(<input type="text" class="texto" name="patrocinador_dddtel2" value="'.$obj->patrocinador_dddtel2.'" maxlength="6" size="1" />) <input type="text" class="texto" name="patrocinador_tel2" value="'.$obj->patrocinador_tel2.'" maxlength="30" style="width:245px;" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Fax', 'Escreva o Fax do patrocinador.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização dos patrocinadores quando se trabalha com diversas '.$config['organizacao'].'.').'Fax:'.dicaF().'</td><td>(<input type="text" class="texto" name="patrocinador_dddfax" value="'.$obj->patrocinador_dddfax.'" maxlength="6" size="1" />) <input type="text" class="texto" name="patrocinador_fax" value="'.$obj->patrocinador_fax.'" maxlength="30" style="width:245px;" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Celular', 'Escreva o celular do patrocinador.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização dos patrocinadores quando se trabalha com diversas '.$config['organizacao'].'.').'Celular:'.dicaF().'</td><td>(<input type="text" class="texto" name="patrocinador_dddcel" value="'.$obj->patrocinador_dddcel.'" maxlength="6" size="1" />) <input type="text" class="texto" name="patrocinador_cel" value="'.$obj->patrocinador_cel.'" maxlength="30" style="width:245px;" /></td></tr>';


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
if ($Aplic->profissional) {
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
	$tipos['checklist']='Checklist';
	$tipos['agenda']='Compromisso';
	if ($agrupamento_ativo) $tipos['agrupamento']='Agrupamento';
	$tipos['template']='Modelo';
	}
asort($tipos);

if ($patrocinador_projeto) $tipo='projeto';
elseif ($patrocinador_pratica) $tipo='pratica';
elseif ($patrocinador_acao) $tipo='acao';
elseif ($patrocinador_objetivo) $tipo='objetivo';
elseif ($patrocinador_tema) $tipo='tema';
elseif ($patrocinador_fator) $tipo='fator';
elseif ($patrocinador_estrategia) $tipo='estrategia';
elseif ($patrocinador_perspectiva) $tipo='perspectiva';
elseif ($patrocinador_canvas) $tipo='canvas';
elseif ($patrocinador_risco) $tipo='risco';
elseif ($patrocinador_risco_resposta) $tipo='risco_resposta';
elseif ($patrocinador_meta) $tipo='meta';
elseif ($patrocinador_indicador) $tipo='patrocinador_indicador';
elseif ($patrocinador_swot) $tipo='swot';
elseif ($patrocinador_ata) $tipo='ata';
elseif ($patrocinador_monitoramento) $tipo='monitoramento';
elseif ($patrocinador_calendario) $tipo='calendario';
elseif ($patrocinador_operativo) $tipo='operativo';
elseif ($patrocinador_instrumento) $tipo='instrumento';
elseif ($patrocinador_recurso) $tipo='recurso';
elseif ($patrocinador_problema) $tipo='problema';
elseif ($patrocinador_demanda) $tipo='demanda';
elseif ($patrocinador_programa) $tipo='programa';
elseif ($patrocinador_licao) $tipo='licao';
elseif ($patrocinador_evento) $tipo='evento';
elseif ($patrocinador_link) $tipo='link';
elseif ($patrocinador_avaliacao) $tipo='avaliacao';
elseif ($patrocinador_tgn) $tipo='tgn';
elseif ($patrocinador_brainstorm) $tipo='brainstorm';
elseif ($patrocinador_gut) $tipo='gut';
elseif ($patrocinador_causa_efeito) $tipo='causa_efeito';
elseif ($patrocinador_arquivo) $tipo='arquivo';
elseif ($patrocinador_forum) $tipo='forum';
elseif ($patrocinador_checklist) $tipo='checklist';
elseif ($patrocinador_agenda) $tipo='agenda';
elseif ($patrocinador_agrupamento) $tipo='agrupamento';
elseif ($patrocinador_template) $tipo='template';


else $tipo='';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Relacionado','A qual parte do sistema o patrocinador está relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:250px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';
echo '<tr '.($patrocinador_projeto || $patrocinador_tarefa ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso o patrocinador seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_projeto" value="'.$patrocinador_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($patrocinador_projeto).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a>'.($Aplic->profissional ? '<a href="javascript: void(0);" onclick="incluir_relacionado();">'.imagem('icones/adicionar.png','Adicionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar '.$config['genero_projeto'].' '.$config['projeto'].' escolhid'.$config['genero_projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';
echo '<tr '.($patrocinador_projeto || $patrocinador_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso o patrocinador seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_tarefa" value="'.$patrocinador_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($patrocinador_tarefa).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso o patrocinador seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_pratica" value="'.$patrocinador_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($patrocinador_pratica).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso o patrocinador seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_acao" value="'.$patrocinador_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($patrocinador_acao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso o patrocinador seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_perspectiva" value="'.$patrocinador_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($patrocinador_perspectiva).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso o patrocinador seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_tema" value="'.$patrocinador_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($patrocinador_tema).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso o patrocinador seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_objetivo" value="'.$patrocinador_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($patrocinador_objetivo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso o patrocinador seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_estrategia" value="'.$patrocinador_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($patrocinador_estrategia).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso o patrocinador seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_fator" value="'.$patrocinador_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($patrocinador_fator).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']), 'Caso o patrocinador seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_meta" value="'.$patrocinador_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($patrocinador_meta).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" nowrap="nowrap">'.dica('Indicador', 'Caso o patrocinador seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_indicador" value="'.$patrocinador_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($patrocinador_indicador).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" nowrap="nowrap">'.dica('Monitoramento', 'Caso o patrocinador seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_monitoramento" value="'.$patrocinador_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($patrocinador_monitoramento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';

if ($agrupamento_ativo) echo '<tr '.($patrocinador_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" nowrap="nowrap">'.dica('Agrupamento', 'Caso o patrocinador seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_agrupamento" value="'.$patrocinador_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($patrocinador_agrupamento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="patrocinador_agrupamento" value="" id="agrupamento" /><input type="hidden" id="agrupamento_nome" name="agrupamento_nome" value="">';


echo '<tr '.($patrocinador_template ? '' : 'style="display:none"').' id="template" ><td align="right" nowrap="nowrap">'.dica('Modelo', 'Caso o patrocinador seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_template" value="'.$patrocinador_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($patrocinador_template).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';

echo '<tr '.($patrocinador_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" nowrap="nowrap">'.dica('Agenda', 'Caso o patrocinador seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_calendario" value="'.$patrocinador_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($patrocinador_calendario).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/calendario_p.png','Selecionar calendario','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um calendario.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['instrumento']), 'Caso o patrocinador seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_instrumento" value="'.$patrocinador_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($patrocinador_instrumento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['recurso']), 'Caso o patrocinador seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_recurso" value="'.$patrocinador_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($patrocinador_recurso).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
if ($problema_ativo) echo '<tr '.($patrocinador_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['problema']), 'Caso o patrocinador seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_problema" value="'.$patrocinador_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($patrocinador_problema).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="patrocinador_problema" value="" id="problema" /><input type="hidden" id="problema_nome" name="problema_nome" value="">';
echo '<tr '.($patrocinador_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" nowrap="nowrap">'.dica('Demanda', 'Caso o patrocinador seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_demanda" value="'.$patrocinador_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($patrocinador_demanda).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar um demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['programa']), 'Caso o patrocinador seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_programa" value="'.$patrocinador_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($patrocinador_programa).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['licao']), 'Caso o patrocinador seja específico de uma lição aprendida, neste campo deverá constar o nome da lição aprendida.').'Lição Aprendida:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_licao" value="'.$patrocinador_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($patrocinador_licao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar Lição Aprendida','Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar uma lição aprendida.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" nowrap="nowrap">'.dica('Evento', 'Caso o patrocinador seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_evento" value="'.$patrocinador_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($patrocinador_evento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_link ? '' : 'style="display:none"').' id="link" ><td align="right" nowrap="nowrap">'.dica('link', 'Caso o patrocinador seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_link" value="'.$patrocinador_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($patrocinador_link).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" nowrap="nowrap">'.dica('Avaliação', 'Caso o patrocinador seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_avaliacao" value="'.$patrocinador_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($patrocinador_avaliacao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tgn']), 'Caso o patrocinador seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_tgn" value="'.$patrocinador_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($patrocinador_tgn).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" nowrap="nowrap">'.dica('Brainstorm', 'Caso o patrocinador seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_brainstorm" value="'.$patrocinador_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($patrocinador_brainstorm).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" nowrap="nowrap">'.dica('Matriz G.U.T.', 'Caso o patrocinador seja específico de uma matriz G.U.T., neste campo deverá constar o nome da matriz G.U.T..').'Matriz G.U.T.:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_gut" value="'.$patrocinador_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($patrocinador_gut).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz G.U.T.','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" nowrap="nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso o patrocinador seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_causa_efeito" value="'.$patrocinador_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($patrocinador_causa_efeito).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" nowrap="nowrap">'.dica('Arquivo', 'Caso o patrocinador seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_arquivo" value="'.$patrocinador_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($patrocinador_arquivo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" nowrap="nowrap">'.dica('Fórum', 'Caso o patrocinador seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_forum" value="'.$patrocinador_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($patrocinador_forum).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" nowrap="nowrap">'.dica('Checklist', 'Caso o patrocinador seja específico de um checklist, neste campo deverá constar o nome do checklist.').'checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_checklist" value="'.$patrocinador_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($patrocinador_checklist).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($patrocinador_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" nowrap="nowrap">'.dica('Compromisso', 'Caso o patrocinador seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_agenda" value="'.$patrocinador_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($patrocinador_agenda).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
if (!$Aplic->profissional) {
	echo '<input type="hidden" name="patrocinador_canvas" value="" id="canvas" /><input type="hidden" id="canvas_nome" name="canvas_nome" value="">';
	echo '<input type="hidden" name="patrocinador_risco" value="" id="risco" /><input type="hidden" id="risco_nome" name="risco_nome" value="">';
	echo '<input type="hidden" name="patrocinador_risco_resposta" value="" id="risco_resposta" /><input type="hidden" id="risco_resposta_nome" name="risco_resposta_nome" value="">';
	}
else {
	echo '<tr '.($patrocinador_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso o patrocinador seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_risco" value="'.$patrocinador_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($patrocinador_risco).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($patrocinador_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso o patrocinador seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_risco_resposta" value="'.$patrocinador_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($patrocinador_risco_resposta).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($patrocinador_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso o patrocinador seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_canvas" value="'.$patrocinador_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($patrocinador_canvas).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
	}
if ($swot_ativo) echo '<tr '.(isset($patrocinador_swot) && $patrocinador_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" nowrap="nowrap">'.dica('Campo SWOT', 'Caso o patrocinador seja específico de um campo da matriz SWOT neste campo deverá constar o nome do campo da matriz SWOT').'campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_swot" value="'.(isset($patrocinador_swot) ? $patrocinador_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($patrocinador_swot) ? $patrocinador_swot : null)).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('../../../modulos/swot/imagens/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('../../../modulos/swot/imagens/swot_p.png').' para selecionar um campo da matriz SWOT.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="patrocinador_swot" value="" id="swot" /><input type="hidden" id="swot_nome" name="swot_nome" value="">';
if ($ata_ativo) echo '<tr '.(isset($patrocinador_ata) && $patrocinador_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" nowrap="nowrap">'.dica('Ata de Reunião', 'Caso o patrocinador seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_ata" value="'.(isset($patrocinador_ata) ? $patrocinador_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($patrocinador_ata) ? $patrocinador_ata : null)).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('../../../modulos/atas/imagens/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('../../../modulos/atas/imagens/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="patrocinador_ata" value="" id="ata" /><input type="hidden" id="ata_nome" name="ata_nome" value="">';
if ($operativo_ativo) echo '<tr '.($patrocinador_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso o patrocinador seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="patrocinador_operativo" value="'.$patrocinador_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($patrocinador_operativo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="patrocinador_operativo" value="" id="operativo" /><input type="hidden" id="operativo_nome" name="operativo_nome" value="">';
if ($Aplic->profissional){
	$sql->adTabela('patrocinador_gestao');
	$sql->adCampo('patrocinador_gestao.*');
	$sql->adOnde('patrocinador_gestao_patrocinador ='.(int)$patrocinador_id);
	$sql->adOrdem('patrocinador_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
	echo '<tr><td></td><td><div id="combo_gestao">';
	if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['patrocinador_gestao_ordem'].', '.$gestao_data['patrocinador_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['patrocinador_gestao_ordem'].', '.$gestao_data['patrocinador_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['patrocinador_gestao_ordem'].', '.$gestao_data['patrocinador_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['patrocinador_gestao_ordem'].', '.$gestao_data['patrocinador_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		if ($gestao_data['patrocinador_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['patrocinador_gestao_tarefa']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['patrocinador_gestao_projeto']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['patrocinador_gestao_pratica']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['patrocinador_gestao_acao']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['patrocinador_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['patrocinador_gestao_tema']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['patrocinador_gestao_objetivo']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['patrocinador_gestao_fator']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['patrocinador_gestao_estrategia']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['patrocinador_gestao_meta']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['patrocinador_gestao_canvas']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['patrocinador_gestao_risco']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['patrocinador_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['patrocinador_gestao_indicador']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_calendario']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_calendario($gestao_data['patrocinador_gestao_calendario']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['patrocinador_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_ata']) echo '<td align=left>'.imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['patrocinador_gestao_ata']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_swot']) echo '<td align=left>'.imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['patrocinador_gestao_swot']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['patrocinador_gestao_operativo']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['patrocinador_gestao_instrumento']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['patrocinador_gestao_recurso']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema_pro($gestao_data['patrocinador_gestao_problema']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['patrocinador_gestao_demanda']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['patrocinador_gestao_programa']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['patrocinador_gestao_licao']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['patrocinador_gestao_evento']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['patrocinador_gestao_link']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['patrocinador_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['patrocinador_gestao_tgn']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['patrocinador_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut_pro($gestao_data['patrocinador_gestao_gut']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['patrocinador_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['patrocinador_gestao_arquivo']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['patrocinador_gestao_forum']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['patrocinador_gestao_checklist']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_agenda']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_agenda($gestao_data['patrocinador_gestao_agenda']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_agrupamento']) echo '<td align=left>'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['patrocinador_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_template']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_template($gestao_data['patrocinador_gestao_template']).'</td>';
		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['patrocinador_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) echo '</table>';
	echo '</div></td></tr>';
	}



echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="patrocinador_cor" value="'.($obj->patrocinador_cor ? $obj->patrocinador_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->patrocinador_cor ? $obj->patrocinador_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O patrocinador pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o patrocinador.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para o patrocinador podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para o patrocinador ver e editar o patrocinador</li><li><b>Privado</b> - Somente o responsável e os designados para o patrocinador podem ver a mesma, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($patrocinador_acesso, 'patrocinador_acesso', 'class="texto"', ($patrocinador_id ? $obj->patrocinador_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o patrocinador ainda esteja ativa deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="patrocinador_ativo" '.($obj->patrocinador_ativo || !$patrocinador_id ? 'checked="checked"' : '').' /></td></tr>';



$campos_customizados = new CampoCustomizados('patrocinadores', $patrocinador_id, 'editar');
$campos_customizados->imprimirHTML();
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($patrocinador_id > 0 ? 'modificação' : 'criação').' do patrocinador.').'Notificar:'.dicaF().'</td>';


echo '<td>';
echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pelo Patrocinador', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por este patrocinador.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para o Patrocinador', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para este patrocinador.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '</td></tr>';
echo '<tr><td>'.($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso') ? botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro do patrocinador.','','popEmailContatos()') : '').'</td>';
echo ($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr>';




echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($patrocinador_id ? 'edição' : 'criação').' do patrocinador.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';

echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">
function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('patrocinador_cia').value+'&cias_id_selecionadas='+document.getElementById('patrocinador_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.patrocinador_cias.value = organizacao_id_string;
	document.getElementById('patrocinador_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('patrocinador_cias').value);
	__buildTooltip();
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('patrocinador_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('patrocinador_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.patrocinador_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('patrocinador_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('patrocinador_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.patrocinador_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}

function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('patrocinador_dept').value+'&cia_id='+document.getElementById('patrocinador_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('patrocinador_dept').value+'&cia_id='+document.getElementById('patrocinador_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('patrocinador_cia').value=cia_id;
	document.getElementById('patrocinador_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}


var instrumentos_id_selecionados = '<?php echo implode(",", $instrumentos_selecionados)?>';

function popInstrumentos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["instrumentos"])?>', 500, 500, 'm=publico&a=selecionar_multiplo&tabela=instrumento&dialogo=1&chamar_volta=setInstrumentos&cia_id='+document.getElementById('patrocinador_cia').value, window.setInstrumentos, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&tabela=instrumento&dialogo=1&chamar_volta=setInstrumentos&cia_id='+document.getElementById('patrocinador_cia').value, 'instrumentos','height=500,width=500,resizable,scrollbars=yes');
	}

function setInstrumentos(instrumento_id_string){
	if(!instrumento_id_string) instrumento_id_string = '';
	document.env.patrocinador_instrumentos.value = instrumento_id_string;
	instrumentos_id_selecionados = instrumento_id_string;
	xajax_exibir_instrumentos(instrumentos_id_selecionados);
	__buildTooltip();
	}

function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(patrocinador_id_string) {
	if (!patrocinador_id_string) patrocinador_id_string = '';
	document.getElementById('email_outro').value = patrocinador_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var objetivo_emails = document.getElementById('patrocinadores_usuarios');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}



function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('patrocinador_cia').value+'&usuario_id='+document.getElementById('patrocinador_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('patrocinador_cia').value+'&usuario_id='+document.getElementById('patrocinador_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('patrocinador_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_om(){
	var cia_id=document.getElementById('patrocinador_cia').value;
	xajax_selecionar_om_ajax(cia_id,'patrocinador_cia','combo_cia', 'class="texto" size=1 style="width:290px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir este patrocinador?")) {
		var f = document.env;
		f.del.value=1;
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.patrocinador_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.patrocinador_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.patrocinador_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.patrocinador_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}


function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('patrocinador_estado').value,'patrocinador_cidade','combo_cidade', 'class="texto" size=1 style="width:290px;"', '<?php echo $obj->patrocinador_cidade ?>');
	}

mudar_cidades();







var NUM_DIGITOS_CPF = 11;
var NUM_DIGITOS_CNPJ = 14;
var NUM_DGT_CNPJ_BASE = 8;

String.prototype.lpad = function (pSize, pCharPad) {
	var str = this;
	var dif = pSize - str.length;
	var ch = String(pCharPad).charAt(0);
	for (; dif > 0; dif--) str = ch + str;
	return (str);
	}
String.prototype.trim = function () {
	return this.replace(/^\s*/, "").replace(/\s*$/, "");
	}

function unformatNumber(pNum) {
	return String(pNum).replace(/\D/g, "").replace(/^0+/, "");
	}

function formatCpfCnpj(pCpfCnpj, pUseSepar, pIsCnpj) {
	if (pIsCnpj == null) pIsCnpj = false;
	if (pUseSepar == null) pUseSepar = true;
	var maxDigitos = pIsCnpj ? NUM_DIGITOS_CNPJ : NUM_DIGITOS_CPF;
	var numero = unformatNumber(pCpfCnpj);
	numero = numero.lpad(maxDigitos, '0');
	if (!pUseSepar) return numero;
	if (pIsCnpj) {
		reCnpj = /(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/;
		numero = numero.replace(reCnpj, "$1.$2.$3/$4-$5")
		}
	else {
		reCpf = /(\d{3})(\d{3})(\d{3})(\d{2})$/;
		numero = numero.replace(reCpf, "$1.$2.$3-$4")
		}
	return numero
	}

function dvCpfCnpj(pEfetivo, pIsCnpj) {
	if (pIsCnpj == null) pIsCnpj = false;
	var i, j, k, soma, dv;
	var cicloPeso = pIsCnpj ? NUM_DGT_CNPJ_BASE : NUM_DIGITOS_CPF;
	var maxDigitos = pIsCnpj ? NUM_DIGITOS_CNPJ : NUM_DIGITOS_CPF;
	var calculado = formatCpfCnpj(pEfetivo + "00", false, pIsCnpj);
	calculado = calculado.substring(0, maxDigitos - 2);
	var result = "";
	for (j = 1; j <= 2; j++) {
		k = 2;
		soma = 0;
		for (i = calculado.length - 1; i >= 0; i--) {
			soma += (calculado.charAt(i) - '0') * k;
			k = (k - 1) % cicloPeso + 2
			}
		dv = 11 - soma % 11;
		if (dv > 9) dv = 0;
		calculado += dv;
		result += dv
		}
	return result
	}

function isCpf(pCpf) {
	var numero = formatCpfCnpj(pCpf, false, false);
	if (numero.length > NUM_DIGITOS_CPF) return false;
	var base = numero.substring(0, numero.length - 2);
	var digitos = dvCpfCnpj(base, false);
	var algUnico, i;
	if (numero != "" + base + digitos) return false;
	algUnico = true;
	for (i = 1; algUnico && i < NUM_DIGITOS_CPF; i++) algUnico = (numero.charAt(i - 1) == numero.charAt(i));
	return (!algUnico);
	}

function isCnpj(pCnpj) {
	var numero = formatCpfCnpj(pCnpj, false, true);
	if (numero.length > NUM_DIGITOS_CNPJ) return false;
	var base = numero.substring(0, NUM_DGT_CNPJ_BASE);
	var ordem = numero.substring(NUM_DGT_CNPJ_BASE, 12);
	var digitos = dvCpfCnpj(base + ordem, true);
	var algUnico;
	if (numero != "" + base + ordem + digitos) return false;
	algUnico = numero.charAt(0) != '0';
	for (i = 1; algUnico && i < NUM_DGT_CNPJ_BASE; i++) algUnico = (numero.charAt(i - 1) == numero.charAt(i));
	if (algUnico) return false;
	if (ordem == "0000") return false;
	return (base == "00000000" || parseInt(ordem, 10) <= 300 || base.substring(0, 3) != "000");
	}

function isCpfCnpj(pCpfCnpj) {
	var numero = pCpfCnpj.replace(/\D/g, "");
	if (numero.length > NUM_DIGITOS_CPF) return isCnpj(pCpfCnpj);
	else return isCpf(pCpfCnpj);
	}


function verificarCPF(){
	var cpf=env.patrocinador_cpf.value;
	if(!isCpf(cpf)){
		alert('CPF inválido!');
		env.patrocinador_cpf.focus();
		}
	else
	env.patrocinador_cpf.value=formatCpfCnpj(cpf, true, false);
	}

function verificarCNPJ(){
	var cnpj=env.patrocinador_cnpj.value;
	if(!isCnpj(cnpj)){
		alert('CNPJ inválido!');
		env.patrocinador_cnpj.focus();
		}
	else
	env.patrocinador_cnpj.value=formatCpfCnpj(cnpj, true, true);
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
	document.getElementById('checklist').style.display='none';
	document.getElementById('agenda').style.display='none';
	document.getElementById('template').style.display='none';
	<?php
	if($agrupamento_ativo) echo 'document.getElementById(\'agrupamento\').style.display=\'none\';';
	if($swot_ativo) echo 'document.getElementById(\'swot\').style.display=\'none\';';
	if($ata_ativo) echo 'document.getElementById(\'ata\').style.display=\'none\';';
	if($operativo_ativo) echo 'document.getElementById(\'operativo\').style.display=\'none\';';
	?>
	}


<?php  if ($Aplic->profissional) { ?>

	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('patrocinador_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('patrocinador_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.patrocinador_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}


	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('patrocinador_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('patrocinador_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.patrocinador_template.value = chave;
		document.env.template_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}


<?php } ?>


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('patrocinador_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.patrocinador_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	}

function popTarefa() {
	var f = document.env;
	if (f.patrocinador_projeto.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.patrocinador_projeto.value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.patrocinador_projeto.value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.patrocinador_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('patrocinador_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.patrocinador_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('patrocinador_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.patrocinador_tema.value = chave;
	document.env.tema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('patrocinador_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.patrocinador_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('patrocinador_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.patrocinador_fator.value = chave;
	document.env.fator_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('patrocinador_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.patrocinador_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('patrocinador_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.patrocinador_meta.value = chave;
	document.env.meta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('patrocinador_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.patrocinador_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('patrocinador_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('patrocinador_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.patrocinador_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('patrocinador_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.patrocinador_acao.value = chave;
	document.env.acao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('patrocinador_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.patrocinador_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('patrocinador_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRisco(chave, valor){
	limpar_tudo();
	document.env.patrocinador_risco.value = chave;
	document.env.risco_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco_respostas'])) { ?>
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('patrocinador_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.patrocinador_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>


function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('patrocinador_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('patrocinador_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.patrocinador_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('patrocinador_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('patrocinador_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.patrocinador_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('patrocinador_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.patrocinador_ata.value = chave;
	document.env.ata_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popSWOT() {
	parent.gpwebApp.popUp('SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('patrocinador_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.patrocinador_swot.value = chave;
	document.env.swot_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('patrocinador_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('patrocinador_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.patrocinador_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	}

function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('patrocinador_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('patrocinador_cia').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.patrocinador_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('patrocinador_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('patrocinador_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.patrocinador_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('patrocinador_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.patrocinador_problema.value = chave;
	document.env.problema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('patrocinador_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('patrocinador_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.patrocinador_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('patrocinador_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.patrocinador_programa.value = chave;
	document.env.programa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('patrocinador_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.patrocinador_licao.value = chave;
	document.env.licao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}


function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('patrocinador_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('patrocinador_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.patrocinador_evento.value = chave;
	document.env.evento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('patrocinador_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('patrocinador_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.patrocinador_link.value = chave;
	document.env.link_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('patrocinador_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('patrocinador_cia').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.patrocinador_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('patrocinador_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('patrocinador_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.patrocinador_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('patrocinador_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('patrocinador_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.patrocinador_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('patrocinador_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('patrocinador_cia').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.patrocinador_gut.value = chave;
	document.env.gut_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('patrocinador_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('patrocinador_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.patrocinador_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('patrocinador_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('patrocinador_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.patrocinador_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('patrocinador_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('patrocinador_cia').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.patrocinador_forum.value = chave;
	document.env.forum_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('patrocinador_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('patrocinador_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.patrocinador_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('patrocinador_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('patrocinador_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.patrocinador_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}



function limpar_tudo(){
	if (document.getElementById('tipo_relacao').value!='projeto'){
		document.env.projeto_nome.value = '';
		document.env.patrocinador_projeto.value = null;
		}
	document.env.patrocinador_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.patrocinador_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.patrocinador_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.patrocinador_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.patrocinador_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.patrocinador_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.patrocinador_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.patrocinador_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.patrocinador_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.patrocinador_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.patrocinador_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.patrocinador_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.patrocinador_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.patrocinador_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.patrocinador_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.patrocinador_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.patrocinador_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.patrocinador_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.patrocinador_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.patrocinador_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.patrocinador_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.patrocinador_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.patrocinador_link.value = null;
	document.env.link_nome.value = '';
	document.env.patrocinador_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.patrocinador_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.patrocinador_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.patrocinador_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.patrocinador_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.patrocinador_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.patrocinador_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.patrocinador_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.patrocinador_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.patrocinador_template.value = null;
	document.env.template_nome.value = '';
	<?php
	if($swot_ativo) echo 'document.env.swot_nome.value = \'\';	document.env.patrocinador_swot.value = null;';
	if($ata_ativo) echo 'document.env.ata_nome.value = \'\';	document.env.patrocinador_ata.value = null;';
	if($operativo_ativo) echo 'document.env.operativo_nome.value = \'\';	document.env.patrocinador_operativo.value = null;';
	if($agrupamento_ativo) echo 'document.env.agrupamento_nome.value = \'\';	document.env.patrocinador_agrupamento.value = null;';
	?>
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('patrocinador_id').value,
	document.getElementById('uuid').value,
	f.patrocinador_projeto.value,
	f.patrocinador_tarefa.value,
	f.patrocinador_perspectiva.value,
	f.patrocinador_tema.value,
	f.patrocinador_objetivo.value,
	f.patrocinador_fator.value,
	f.patrocinador_estrategia.value,
	f.patrocinador_meta.value,
	f.patrocinador_pratica.value,
	f.patrocinador_acao.value,
	f.patrocinador_canvas.value,
	f.patrocinador_risco.value,
	f.patrocinador_risco_resposta.value,
	f.patrocinador_indicador.value,
	f.patrocinador_calendario.value,
	f.patrocinador_monitoramento.value,
	f.patrocinador_ata.value,
	f.patrocinador_swot.value,
	f.patrocinador_operativo.value,
	f.patrocinador_instrumento.value,
	f.patrocinador_recurso.value,
	f.patrocinador_problema.value,
	f.patrocinador_demanda.value,
	f.patrocinador_programa.value,
	f.patrocinador_licao.value,
	f.patrocinador_evento.value,
	f.patrocinador_link.value,
	f.patrocinador_avaliacao.value,
	f.patrocinador_tgn.value,
	f.patrocinador_brainstorm.value,
	f.patrocinador_gut.value,
	f.patrocinador_causa_efeito.value,
	f.patrocinador_arquivo.value,
	f.patrocinador_forum.value,
	f.patrocinador_checklist.value,
	f.patrocinador_agenda.value,
	f.patrocinador_agrupamento.value,
	f.patrocinador_template.value

	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(patrocinador_gestao_id){
	xajax_excluir_gestao(document.getElementById('patrocinador_id').value, document.getElementById('uuid').value, patrocinador_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, patrocinador_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, patrocinador_gestao_id, direcao, document.getElementById('patrocinador_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$patrocinador_id && (
	$patrocinador_projeto ||
	$patrocinador_tarefa ||
	$patrocinador_perspectiva ||
	$patrocinador_tema ||
	$patrocinador_objetivo ||
	$patrocinador_fator ||
	$patrocinador_estrategia ||
	$patrocinador_meta ||
	$patrocinador_pratica ||
	$patrocinador_acao ||
	$patrocinador_canvas ||
	$patrocinador_risco ||
	$patrocinador_risco_resposta ||
	$patrocinador_indicador ||
	$patrocinador_calendario ||
	$patrocinador_monitoramento ||
	$patrocinador_ata ||
	$patrocinador_swot ||
	$patrocinador_operativo ||
	$patrocinador_instrumento ||
	$patrocinador_recurso ||
	$patrocinador_problema ||
	$patrocinador_demanda ||
	$patrocinador_programa ||
	$patrocinador_licao ||
	$patrocinador_evento ||
	$patrocinador_link ||
	$patrocinador_avaliacao ||
	$patrocinador_tgn ||
	$patrocinador_brainstorm ||
	$patrocinador_gut ||
	$patrocinador_causa_efeito ||
	$patrocinador_arquivo ||
	$patrocinador_forum ||
	$patrocinador_checklist ||
	$patrocinador_agenda ||
	$patrocinador_agrupamento ||
	$patrocinador_template
	)) echo 'incluir_relacionado();';
	?>

</script>

