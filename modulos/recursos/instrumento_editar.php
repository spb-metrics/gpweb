<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$instrumento_id = intval(getParam($_REQUEST, 'instrumento_id', 0));
if (!$podeAdicionar && !$instrumento_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$podeEditar && $instrumento_id) $Aplic->redirecionar('m=publico&a=acesso_negado');

require_once BASE_DIR.'/modulos/recursos/instrumento.class.php';

$Aplic->carregarCKEditorJS();


$Aplic->carregarCalendarioJS();
$sql = new BDConsulta();

$msg = '';
$obj = new CInstrumento();
$obj->load($instrumento_id);

if ($instrumento_id && !$obj->instrumento_nome) {
	$Aplic->setMsg(''.ucfirst($config['instrumento']));
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=recursos');
	}
$ttl = $instrumento_id ? 'Editar '.ucfirst($config['instrumento']) : 'Adicionar '.ucfirst($config['instrumento']);
$botoesTitulo = new CBlocoTitulo($ttl, 'instrumento.png', $m, $m.'.'.$a);

$botoesTitulo->mostrar();
$cias_selecionadas = array();
$usuarios_selecionados=array();
$depts_selecionados=array();
$contatos_selecionados=array();
$recursos_selecionados=array();
if ($instrumento_id) {
	$sql->adTabela('instrumento_designados');
	$sql->adCampo('usuario_id');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('instrumento_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('instrumento_id ='.(int)$instrumento_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('instrumento_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('instrumento_recursos');
	$sql->adCampo('DISTINCT recurso_id');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$recursos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('instrumento_cia');
		$sql->adCampo('instrumento_cia_cia');
		$sql->adOnde('instrumento_cia_instrumento = '.(int)$instrumento_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}

	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="recursos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_instrumento_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="instrumento_id" id="instrumento_id" value="'.$instrumento_id.'" />';
echo '<input name="instrumento_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="instrumento_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="instrumento_contatos" type="hidden" value="'.implode(',', $contatos_selecionados).'" />';
echo '<input name="instrumento_recursos" type="hidden" value="'.implode(',', $recursos_selecionados).'" />';
echo '<input name="instrumento_cias"  id="instrumento_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($instrumento_id ? null : uuid()).'" />';

$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;

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
$tr_ativo=$Aplic->modulo_ativo('tr');

$instrumento_projeto = getParam($_REQUEST, 'instrumento_projeto', null);
$instrumento_tarefa = getParam($_REQUEST, 'instrumento_tarefa', null);
$instrumento_perspectiva = getParam($_REQUEST, 'instrumento_perspectiva', null);
$instrumento_tema = getParam($_REQUEST, 'instrumento_tema', null);
$instrumento_objetivo = getParam($_REQUEST, 'instrumento_objetivo', null);
$instrumento_fator = getParam($_REQUEST, 'instrumento_fator', null);
$instrumento_estrategia = getParam($_REQUEST, 'instrumento_estrategia', null);
$instrumento_meta = getParam($_REQUEST, 'instrumento_meta', null);
$instrumento_pratica = getParam($_REQUEST, 'instrumento_pratica', null);
$instrumento_acao = getParam($_REQUEST, 'instrumento_acao', null);
$instrumento_canvas = getParam($_REQUEST, 'instrumento_canvas', null);
$instrumento_risco = getParam($_REQUEST, 'instrumento_risco', null);
$instrumento_risco_resposta = getParam($_REQUEST, 'instrumento_risco_resposta', null);
$instrumento_indicador = getParam($_REQUEST, 'instrumento_indicador', null);
$instrumento_calendario = getParam($_REQUEST, 'instrumento_calendario', null);
$instrumento_monitoramento = getParam($_REQUEST, 'instrumento_monitoramento', null);
$instrumento_ata = getParam($_REQUEST, 'instrumento_ata', null);
$instrumento_swot = getParam($_REQUEST, 'instrumento_swot', null);
$instrumento_operativo = getParam($_REQUEST, 'instrumento_operativo', null);
$instrumento_recurso = getParam($_REQUEST, 'instrumento_recurso', null);
$instrumento_problema = getParam($_REQUEST, 'instrumento_problema', null);
$instrumento_demanda = getParam($_REQUEST, 'instrumento_demanda', null);
$instrumento_programa = getParam($_REQUEST, 'instrumento_programa', null);
$instrumento_licao = getParam($_REQUEST, 'instrumento_licao', null);
$instrumento_evento = getParam($_REQUEST, 'instrumento_evento', null);
$instrumento_link = getParam($_REQUEST, 'instrumento_link', null);
$instrumento_avaliacao = getParam($_REQUEST, 'instrumento_avaliacao', null);
$instrumento_tgn = getParam($_REQUEST, 'instrumento_tgn', null);
$instrumento_brainstorm = getParam($_REQUEST, 'instrumento_brainstorm', null);
$instrumento_gut = getParam($_REQUEST, 'instrumento_gut', null);
$instrumento_causa_efeito = getParam($_REQUEST, 'instrumento_causa_efeito', null);
$instrumento_arquivo = getParam($_REQUEST, 'instrumento_arquivo', null);
$instrumento_forum = getParam($_REQUEST, 'instrumento_forum', null);
$instrumento_checklist = getParam($_REQUEST, 'instrumento_checklist', null);
$instrumento_agenda = getParam($_REQUEST, 'instrumento_agenda', null);
$instrumento_agrupamento = getParam($_REQUEST, 'instrumento_agrupamento', null);
$instrumento_patrocinador = getParam($_REQUEST, 'instrumento_patrocinador', null);
$instrumento_template = getParam($_REQUEST, 'instrumento_template', null);
$instrumento_painel = getParam($_REQUEST, '$instrumento_painel', null);
$instrumento_painel_odometro = getParam($_REQUEST, '$instrumento_painel_odometro', null);
$instrumento_painel_composicao = getParam($_REQUEST, '$instrumento_painel_composicao', null);
$instrumento_tr = getParam($_REQUEST, '$instrumento_tr', null);
$instrumento_me = getParam($_REQUEST, '$instrumento_me', null);
if (
	$instrumento_projeto ||
	$instrumento_tarefa ||
	$instrumento_perspectiva ||
	$instrumento_tema ||
	$instrumento_objetivo ||
	$instrumento_fator ||
	$instrumento_estrategia ||
	$instrumento_meta ||
	$instrumento_pratica ||
	$instrumento_acao ||
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
	$sql->adTabela('cias');
	if ($instrumento_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
	elseif ($instrumento_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
	elseif ($instrumento_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
	elseif ($instrumento_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
	elseif ($instrumento_objetivo) $sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','pg_objetivo_estrategico_cia=cias.cia_id');
	elseif ($instrumento_fator) $sql->esqUnir('fatores_criticos','fatores_criticos','pg_fator_critico_cia=cias.cia_id');
	elseif ($instrumento_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
	elseif ($instrumento_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
	elseif ($instrumento_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
	elseif ($instrumento_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
	elseif ($instrumento_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
	elseif ($instrumento_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
	elseif ($instrumento_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
	elseif ($instrumento_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
	elseif ($instrumento_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
	elseif ($instrumento_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
	elseif ($instrumento_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
	elseif ($instrumento_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
	elseif ($instrumento_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
	elseif ($instrumento_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
	elseif ($instrumento_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
	elseif ($instrumento_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
	elseif ($instrumento_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
	elseif ($instrumento_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
	elseif ($instrumento_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
	elseif ($instrumento_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
	elseif ($instrumento_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
	elseif ($instrumento_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
	elseif ($instrumento_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
	elseif ($instrumento_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
	elseif ($instrumento_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
	elseif ($instrumento_arquivo) $sql->esqUnir('arquivos','arquivos','arquivo_cia=cias.cia_id');
	elseif ($instrumento_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
	elseif ($instrumento_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
	elseif ($instrumento_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
	elseif ($instrumento_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
	elseif ($instrumento_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
	elseif ($instrumento_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
	elseif ($instrumento_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
	elseif ($instrumento_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
	elseif ($instrumento_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
	elseif ($instrumento_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
	elseif ($instrumento_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');

	if ($instrumento_tarefa) $sql->adOnde('tarefa_id = '.(int)$instrumento_tarefa);
	elseif ($instrumento_projeto) $sql->adOnde('projeto_id = '.(int)$instrumento_projeto);
	elseif ($instrumento_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$instrumento_perspectiva);
	elseif ($instrumento_tema) $sql->adOnde('tema_id = '.(int)$instrumento_tema);
	elseif ($instrumento_objetivo) $sql->adOnde('pg_objetivo_estrategico_id = '.(int)$instrumento_objetivo);
	elseif ($instrumento_fator) $sql->adOnde('pg_fator_critico_id = '.(int)$instrumento_fator);
	elseif ($instrumento_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$instrumento_estrategia);
	elseif ($instrumento_meta) $sql->adOnde('pg_meta_id = '.(int)$instrumento_meta);
	elseif ($instrumento_pratica) $sql->adOnde('pratica_id = '.(int)$instrumento_pratica);
	elseif ($instrumento_acao) $sql->adOnde('plano_acao_id = '.(int)$instrumento_acao);
	elseif ($instrumento_canvas) $sql->adOnde('canvas_id = '.(int)$instrumento_canvas);
	elseif ($instrumento_risco) $sql->adOnde('risco_id = '.(int)$instrumento_risco);
	elseif ($instrumento_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$instrumento_risco_resposta);
	elseif ($instrumento_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$instrumento_indicador);
	elseif ($instrumento_calendario) $sql->adOnde('calendario_id = '.(int)$instrumento_calendario);
	elseif ($instrumento_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$instrumento_monitoramento);
	elseif ($instrumento_ata) $sql->adOnde('ata_id = '.(int)$instrumento_ata);
	elseif ($instrumento_swot) $sql->adOnde('swot_id = '.(int)$instrumento_swot);
	elseif ($instrumento_operativo) $sql->adOnde('operativo_id = '.(int)$instrumento_operativo);
	elseif ($instrumento_recurso) $sql->adOnde('recurso_id = '.(int)$instrumento_recurso);
	elseif ($instrumento_problema) $sql->adOnde('problema_id = '.(int)$instrumento_problema);
	elseif ($instrumento_demanda) $sql->adOnde('demanda_id = '.(int)$instrumento_demanda);
	elseif ($instrumento_programa) $sql->adOnde('programa_id = '.(int)$instrumento_programa);
	elseif ($instrumento_licao) $sql->adOnde('licao_id = '.(int)$instrumento_licao);
	elseif ($instrumento_evento) $sql->adOnde('evento_id = '.(int)$instrumento_evento);
	elseif ($instrumento_link) $sql->adOnde('link_id = '.(int)$instrumento_link);
	elseif ($instrumento_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$instrumento_avaliacao);
	elseif ($instrumento_tgn) $sql->adOnde('tgn_id = '.(int)$instrumento_tgn);
	elseif ($instrumento_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$instrumento_brainstorm);
	elseif ($instrumento_gut) $sql->adOnde('gut_id = '.(int)$instrumento_gut);
	elseif ($instrumento_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$instrumento_causa_efeito);
	elseif ($instrumento_arquivo) $sql->adOnde('arquivo_id = '.(int)$instrumento_arquivo);
	elseif ($instrumento_forum) $sql->adOnde('forum_id = '.(int)$instrumento_forum);
	elseif ($instrumento_checklist) $sql->adOnde('checklist_id = '.(int)$instrumento_checklist);
	elseif ($instrumento_agenda) $sql->adOnde('agenda_id = '.(int)$instrumento_agenda);
	elseif ($instrumento_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$instrumento_agrupamento);
	elseif ($instrumento_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$instrumento_patrocinador);
	elseif ($instrumento_template) $sql->adOnde('template_id = '.(int)$instrumento_template);
	elseif ($instrumento_painel) $sql->adOnde('painel_id = '.(int)$instrumento_painel);
	elseif ($instrumento_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$instrumento_painel_odometro);
	elseif ($instrumento_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$instrumento_painel_composicao);
	elseif ($instrumento_tr) $sql->adOnde('tr_id = '.(int)$instrumento_tr);
	elseif ($instrumento_me) $sql->adOnde('me_id = '.(int)$instrumento_me);
	$sql->adCampo('cia_id');
	$cia_id = $sql->Resultado();
	$sql->limpar();
	}



echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome', 'Preencha neste campo um nome para identificação d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Nome:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="instrumento_nome" style="width:284px" value="'.(isset($obj->instrumento_nome) ? $obj->instrumento_nome : '').'"></td></tr>';
echo '<tr><td align=right nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om(($obj->instrumento_cia ? $obj->instrumento_cia : $Aplic->usuario_cia), 'instrumento_cia', 'class=texto size=1 style="width:284px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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


if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="instrumento_dept" id="instrumento_dept" value="'.$obj->instrumento_dept.'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($obj->instrumento_dept).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';



echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pel'.$config['genero_instrumento'].' '.$config['instrumento'], 'Tod'.$config['genero_instrumento'].' '.$config['instrumento'].' deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="instrumento_responsavel" name="instrumento_responsavel" value="'.($obj->instrumento_responsavel ? $obj->instrumento_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->instrumento_responsavel ? $obj->instrumento_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s Designados', 'Clique para visualizar '.$config['genero_usuario'].'s demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';




echo '<tr><td align="right" nowrap="nowrap">'.dica('Número', 'Preencha neste campo o número de identificação d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Número:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="instrumento_numero" style="width:284px" value="'.(isset($obj->instrumento_numero) ? $obj->instrumento_numero : '').'"></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Ano', 'Preencha neste campo o ano d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Ano:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="instrumento_ano" style="width:284px" value="'.(isset($obj->instrumento_ano) ? $obj->instrumento_ano : date('Y')).'"></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo', 'Selecione qual o tipo de instrumento.').'Tipo:'.dicaF().'</td><td align="left" >'.selecionaVetor(getSisValor('TipoInstrumento','','','',true,0), 'instrumento_tipo', 'class="texto" style="width:284px"', (isset($obj->instrumento_tipo) ? $obj->instrumento_tipo : '')).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Objeto', 'Preencha neste campo o objeto d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Objeto:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" rows="3" name="instrumento_objeto" id="instrumento_objeto">'.(isset($obj->instrumento_objeto) ? $obj->instrumento_objeto : '').'</textarea></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Justificativa', 'Preencha neste campo a justificativa d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Justificativa:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" rows="3" name="instrumento_justificativa" id="instrumento_justificativa">'.(isset($obj->instrumento_justificativa) ? $obj->instrumento_justificativa : '').'</textarea></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Situação', 'Selecione qual a situação d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Situação:'.dicaF().'</td><td align="left" >'.selecionaVetor(getSisValor('SituacaoInstrumento','','','',true,0), 'instrumento_situacao', 'class="texto" style="width:284px"', (isset($obj->instrumento_situacao) ? $obj->instrumento_situacao : '')).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Licitação', 'Selecione qual o tipo de licitação para '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Licitação:'.dicaF().'</td><td align="left" >'.selecionaVetor(getSisValor('ModalidadeLicitacao','','','',true,0), 'instrumento_licitacao', 'class="texto" style="width:284px"', (isset($obj->instrumento_licitacao) ? $obj->instrumento_licitacao : '')).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Número do Edital', 'Preencha neste campo o número do edital da licitação d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Número do Edital:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="instrumento_edital_nr" style="width:284px" value="'.(isset($obj->instrumento_edital_nr) ? $obj->instrumento_edital_nr : '').'"></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Número do Processo', 'Preencha neste campo o número do processo d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Número do Processo:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="instrumento_processo" style="width:284px" value="'.(isset($obj->instrumento_processo) ? $obj->instrumento_processo : '').'"></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Entidade', 'Preencha neste campo a entidade com a qual foi celebrado '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Entidade:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="instrumento_entidade" style="width:284px" value="'.(isset($obj->instrumento_entidade) ? $obj->instrumento_entidade : '').'"></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('CNPJ da Entidade', 'Preencha neste campo o número do CNPJ da entidade com a qual foi celebrado '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'CNPJ da entidade:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="instrumento_entidade_cnpj" style="width:284px" value="'.(isset($obj->instrumento_entidade_cnpj) ? $obj->instrumento_entidade_cnpj : '').'"></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Celebração', 'Data em que '.$config['genero_instrumento'].' '.$config['instrumento'].' foi celebrado.').'Data de celebração:'.dicaF().'</td><td width="100%" colspan="2"><input type="hidden" name="instrumento_data_celebracao" id="instrumento_data_celebracao" value="'.(isset($obj->instrumento_data_celebracao) ? $obj->instrumento_data_celebracao : '').'" /><input type="text" name="instrumento_data_celebracao_texto" style="width:70px;" id="instrumento_data_celebracao_texto" onchange="setData(\'env\', \'instrumento_data_celebracao_texto\' , \'instrumento_data_celebracao\');" value="'.(isset($obj->instrumento_data_celebracao) ? retorna_data($obj->instrumento_data_celebracao, true) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="botao_instrumento_data_celebracao" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Publicação', 'Data em que '.$config['genero_instrumento'].' '.$config['instrumento'].' foi publicaso.').'Data de publicação:'.dicaF().'</td><td width="100%" colspan="2"><input type="hidden" name="instrumento_data_publicacao" id="instrumento_data_publicacao" value="'.(isset($obj->instrumento_data_publicacao) ? $obj->instrumento_data_publicacao : '').'" /><input type="text" name="instrumento_data_publicacao_texto" style="width:70px;" id="instrumento_data_publicacao_texto" onchange="setData(\'env\', \'instrumento_data_publicacao_texto\' , \'instrumento_data_publicacao\');" value="'.(isset($obj->instrumento_data_publicacao) ? retorna_data($obj->instrumento_data_publicacao, true) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="botao_instrumento_data_publicacao" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Início', 'Data de início d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Data de início:'.dicaF().'</td><td width="100%" colspan="2"><input type="hidden" name="instrumento_data_inicio" id="instrumento_data_inicio" value="'.(isset($obj->instrumento_data_inicio) ? $obj->instrumento_data_inicio : '').'" /><input type="text" name="instrumento_data_inicio_texto" style="width:70px;" id="instrumento_data_inicio_texto" onchange="setData(\'env\', \'instrumento_data_inicio_texto\' , \'instrumento_data_inicio\');" value="'.(isset($obj->instrumento_data_inicio) ? retorna_data($obj->instrumento_data_inicio, true) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="botao_instrumento_data_inicio" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Término', 'Data de término d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Data de término:'.dicaF().'</td><td width="100%" colspan="2"><input type="hidden" name="instrumento_data_termino" id="instrumento_data_termino" value="'.(isset($obj->instrumento_data_termino) ? $obj->instrumento_data_termino : '').'" /><input type="text" name="instrumento_data_termino_texto" style="width:70px;" id="instrumento_data_termino_texto" onchange="setData(\'env\', \'instrumento_data_termino_texto\' , \'instrumento_data_termino\');" value="'.(isset($obj->instrumento_data_termino) ? retorna_data($obj->instrumento_data_termino, true) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="botao_instrumento_data_termino" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor', 'Insira o valor d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Valor:'.dicaF().'</td><td>'.$config['simbolo_moeda'].'&nbsp;<input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="instrumento_valor" id="instrumento_valor" value="'.(isset($obj->instrumento_valor) ? number_format($obj->instrumento_valor, 2, ',', '.'):'').'" size="22" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Contrapartida', 'Insira o valor da contrapartida d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].', se for o caso.').'Contrapartida:'.dicaF().'</td><td>'.$config['simbolo_moeda'].'&nbsp;<input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="instrumento_valor_contrapartida" id="instrumento_valor_contrapartida" value="'.(isset($obj->instrumento_valor_contrapartida) ? number_format($obj->instrumento_valor_contrapartida, 2, ',', '.'):'').'" size="22" /></td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_instrumento = '.(int)$instrumento_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'instrumento_principal_indicador', 'class="texto" style="width:284px;"', $obj->instrumento_principal_indicador).'</td></tr>';
	}
echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="instrumento_cor" value="'.($obj->instrumento_cor ? $obj->instrumento_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->instrumento_cor ? $obj->instrumento_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O instrumento pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável  e os designados podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($niveis_acesso, 'instrumento_acesso', 'class="texto"', ($instrumento_id ? $obj->instrumento_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
for($i=0; $i<=100; $i++) $percentual[$i]=$i;
echo '<tr><td align="right" nowrap="nowrap">'.dica('Porcentusl realizado', 'Indique o porcentual d'.$config['genero_instrumento'].' '.$config['instrumento'].' já completado.').'Realizado: '.dicaF().'</td><td>'.selecionaVetor($percentual, 'instrumento_porcentagem', 'size="1" class="texto"', (isset($obj->instrumento_porcentagem) ? (int)$obj->instrumento_porcentagem : '')).'%'.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['supervisor']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_supervisor']=='o' ? 'um' : 'uma').' '.$config['supervisor'].' relacionad'.$config['genero_supervisor'].'.').ucfirst($config['supervisor']).':'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="instrumento_supervisor" name="instrumento_supervisor" value="'.$obj->instrumento_supervisor.'" /><input type="text" id="nome_supervisor" name="nome_supervisor" value="'.nome_om($obj->instrumento_supervisor,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popSupervisor();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['autoridade']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_autoridade']=='o' ? 'um' : 'uma').' '.$config['autoridade'].' relacionad'.$config['genero_autoridade'].'.').ucfirst($config['autoridade']).':'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="instrumento_autoridade" name="instrumento_autoridade" value="'.$obj->instrumento_autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_om($obj->instrumento_autoridade,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['cliente']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_cliente']=='o' ? 'um' : 'uma').' '.$config['cliente'].' relacionad'.$config['genero_cliente'].'.').ucfirst($config['cliente']).':'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="instrumento_cliente" name="instrumento_cliente" value="'.$obj->instrumento_cliente.'" /><input type="text" id="nome_cliente" name="nome_cliente" value="'.nome_om($obj->instrumento_cliente,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popCliente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';



$saida_contatos='';
if (count($contatos_selecionados)) {
		$saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_contatos.= '<tr><td>'.link_contato($contatos_selecionados[0],'','','esquerda');
		$qnt_lista_contatos=count($contatos_selecionados);
		if ($qnt_lista_contatos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
				$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.$config['contatos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
				}
		$saida_contatos.= '</td></tr></table>';
		}
else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Contatos', 'Quais '.strtolower($config['contatos']).' estão envolvid'.$config['genero_contato'].'s.').ucfirst($config['contatos']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_contatos">'.$saida_contatos.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popContatos()').'</td></tr></table></td></tr>';





$saida_recursos='';
if (count($recursos_selecionados)) {
		$saida_recursos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_recursos.= '<tr><td>'.link_recurso($recursos_selecionados[0]);
		$qnt_lista_recursos=count($recursos_selecionados);
		if ($qnt_lista_recursos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_recursos; $i < $i_cmp; $i++) $lista.=link_recurso($recursos_selecionados[$i]).'<br>';
				$saida_recursos.= dica('Outr'.$config['genero_recurso'].'s '.ucfirst($config['recursos']), 'Clique para visualizar '.$config['genero_recurso'].'s demais '.$config['recursos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_recursos\');">(+'.($qnt_lista_recursos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_recursos"><br>'.$lista.'</span>';
				}
		$saida_recursos.= '</td></tr></table>';
		}
else $saida_recursos.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Recursos', 'Quais '.strtolower($config['recursos']).' estão envolvid'.$config['genero_recurso'].'s.').ucfirst($config['recursos']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_recursos">'.$saida_recursos.'</div></td><td>'.botao_icone('recursos_p.gif','Selecionar', 'selecionar '.$config['recursos'].'.','popRecursos()').'</td></tr></table></td></tr>';




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
	if ($Aplic->profissional) {
		$tipos['canvas']=ucfirst($config['canvas']);
		$tipos['risco']=ucfirst($config['risco']);
		$tipos['risco_resposta']=ucfirst($config['risco_resposta']);
		$tipos['calendario']='Agenda';
		$tipos['monitoramento']='Monitoramento';
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
		if ($patrocinador_ativo) $tipos['patrocinador']='Patrocinador';
		$tipos['template']='Modelo';
		$tipos['painel']='Painel de Indicador';
		$tipos['painel_odometro']='Odômetro de Indicador';
		$tipos['painel_composicao']='Composição de Painéis';
		if ($tr_ativo) $tipos['tr']=ucfirst($config['tr']);
		if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $tipos['me']=ucfirst($config['me']);
		}
	asort($tipos);

	if ($instrumento_projeto) $tipo='projeto';
	elseif ($instrumento_pratica) $tipo='pratica';
	elseif ($instrumento_acao) $tipo='acao';
	elseif ($instrumento_objetivo) $tipo='objetivo';
	elseif ($instrumento_tema) $tipo='tema';
	elseif ($instrumento_fator) $tipo='fator';
	elseif ($instrumento_estrategia) $tipo='estrategia';
	elseif ($instrumento_perspectiva) $tipo='perspectiva';
	elseif ($instrumento_canvas) $tipo='canvas';
	elseif ($instrumento_risco) $tipo='risco';
	elseif ($instrumento_risco_resposta) $tipo='risco_resposta';
	elseif ($instrumento_meta) $tipo='meta';
	elseif ($instrumento_indicador) $tipo='instrumento_indicador';
	elseif ($instrumento_swot) $tipo='swot';
	elseif ($instrumento_ata) $tipo='ata';
	elseif ($instrumento_monitoramento) $tipo='monitoramento';
	elseif ($instrumento_calendario) $tipo='calendario';
	elseif ($instrumento_operativo) $tipo='operativo';
	elseif ($instrumento_recurso) $tipo='recurso';
	elseif ($instrumento_problema) $tipo='problema';
	elseif ($instrumento_demanda) $tipo='demanda';
	elseif ($instrumento_programa) $tipo='programa';
	elseif ($instrumento_licao) $tipo='licao';
	elseif ($instrumento_evento) $tipo='evento';
	elseif ($instrumento_link) $tipo='link';
	elseif ($instrumento_avaliacao) $tipo='avaliacao';
	elseif ($instrumento_tgn) $tipo='tgn';
	elseif ($instrumento_brainstorm) $tipo='brainstorm';
	elseif ($instrumento_gut) $tipo='gut';
	elseif ($instrumento_causa_efeito) $tipo='causa_efeito';
	elseif ($instrumento_arquivo) $tipo='arquivo';
	elseif ($instrumento_forum) $tipo='forum';
	elseif ($instrumento_checklist) $tipo='checklist';
	elseif ($instrumento_agenda) $tipo='agenda';
	elseif ($instrumento_agrupamento) $tipo='agrupamento';
	elseif ($instrumento_patrocinador) $tipo='patrocinador';
	elseif ($instrumento_template) $tipo='template';
	elseif ($instrumento_painel) $tipo='painel';
	elseif ($instrumento_painel_odometro) $tipo='painel_odometro';
	elseif ($instrumento_painel_composicao) $tipo='painel_composicao';
	elseif ($instrumento_tr) $tipo='tr';
	elseif ($instrumento_me) $tipo='me';


	else $tipo='';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Relacionad'.$config['genero_instrumento'],'A qual parte do sistema '.$config['genero_instrumento'].' '.$config['instrumento'].' está relacionad'.$config['genero_instrumento'].'.').'Relacionad'.$config['genero_instrumento'].':'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:286px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';
	echo '<tr '.($instrumento_projeto || $instrumento_tarefa ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_projeto" value="'.$instrumento_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($instrumento_projeto).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a>'.($Aplic->profissional ? '<a href="javascript: void(0);" onclick="incluir_relacionado();">'.imagem('icones/adicionar.png','Adicionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar '.$config['genero_projeto'].' '.$config['projeto'].' escolhid'.$config['genero_projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';
	echo '<tr '.($instrumento_projeto || $instrumento_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_tarefa" value="'.$instrumento_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($instrumento_tarefa).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_pratica" value="'.$instrumento_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($instrumento_pratica).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_acao" value="'.$instrumento_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($instrumento_acao).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_perspectiva" value="'.$instrumento_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($instrumento_perspectiva).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_tema" value="'.$instrumento_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($instrumento_tema).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_objetivo" value="'.$instrumento_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($instrumento_objetivo).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_estrategia" value="'.$instrumento_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($instrumento_estrategia).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_fator" value="'.$instrumento_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($instrumento_fator).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']), 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_meta" value="'.$instrumento_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($instrumento_meta).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" nowrap="nowrap">'.dica('Indicador', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_indicador" value="'.$instrumento_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($instrumento_indicador).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" nowrap="nowrap">'.dica('Monitoramento', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_monitoramento" value="'.$instrumento_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($instrumento_monitoramento).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';

	if ($agrupamento_ativo) echo '<tr '.($instrumento_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" nowrap="nowrap">'.dica('Agrupamento', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_agrupamento" value="'.$instrumento_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($instrumento_agrupamento).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="instrumento_agrupamento" value="" id="agrupamento" /><input type="hidden" id="agrupamento_nome" name="agrupamento_nome" value="">';

	if ($patrocinador_ativo) echo '<tr '.($instrumento_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" nowrap="nowrap">'.dica('Patrocinador', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um patrocinador, neste campo deverá constar o nome do patrocinador.').'Patrocinador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_patrocinador" value="'.$instrumento_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($instrumento_patrocinador).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar patrocinador','Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um patrocinador.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="instrumento_patrocinador" value="" id="patrocinador" /><input type="hidden" id="patrocinador_nome" name="patrocinador_nome" value="">';


	echo '<tr '.($instrumento_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" nowrap="nowrap">'.dica('Agenda', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_calendario" value="'.$instrumento_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($instrumento_calendario).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/calendario_p.png','Selecionar calendario','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um calendario.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['recurso']), 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_recurso" value="'.$instrumento_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($instrumento_recurso).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
	if ($problema_ativo) echo '<tr '.($instrumento_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['problema']), 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_problema" value="'.$instrumento_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($instrumento_problema).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="instrumento_problema" value="" id="problema" /><input type="hidden" id="problema_nome" name="problema_nome" value="">';
	echo '<tr '.($instrumento_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" nowrap="nowrap">'.dica('Demanda', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_demanda" value="'.$instrumento_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($instrumento_demanda).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar um demanda.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['programa']), 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_programa" value="'.$instrumento_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($instrumento_programa).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['licao']), 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de uma lição aprendida, neste campo deverá constar o nome da lição aprendida.').'Lição Aprendida:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_licao" value="'.$instrumento_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($instrumento_licao).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar Lição Aprendida','Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar uma lição aprendida.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" nowrap="nowrap">'.dica('Evento', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_evento" value="'.$instrumento_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($instrumento_evento).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_link ? '' : 'style="display:none"').' id="link" ><td align="right" nowrap="nowrap">'.dica('link', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_link" value="'.$instrumento_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($instrumento_link).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" nowrap="nowrap">'.dica('Avaliação', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_avaliacao" value="'.$instrumento_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($instrumento_avaliacao).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tgn']), 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_tgn" value="'.$instrumento_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($instrumento_tgn).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" nowrap="nowrap">'.dica('Brainstorm', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_brainstorm" value="'.$instrumento_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($instrumento_brainstorm).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" nowrap="nowrap">'.dica('Matriz G.U.T.', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de uma matriz G.U.T., neste campo deverá constar o nome da matriz G.U.T..').'Matriz G.U.T.:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_gut" value="'.$instrumento_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($instrumento_gut).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz G.U.T.','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" nowrap="nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_causa_efeito" value="'.$instrumento_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($instrumento_causa_efeito).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" nowrap="nowrap">'.dica('Arquivo', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_arquivo" value="'.$instrumento_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($instrumento_arquivo).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" nowrap="nowrap">'.dica('Fórum', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_forum" value="'.$instrumento_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($instrumento_forum).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" nowrap="nowrap">'.dica('Checklist', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um checklist, neste campo deverá constar o nome do checklist.').'checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_checklist" value="'.$instrumento_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($instrumento_checklist).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($instrumento_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" nowrap="nowrap">'.dica('Compromisso', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_agenda" value="'.$instrumento_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($instrumento_agenda).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
	if (!$Aplic->profissional) {
		echo '<input type="hidden" name="instrumento_canvas" value="" id="canvas" /><input type="hidden" id="canvas_nome" name="canvas_nome" value="">';
		echo '<input type="hidden" name="instrumento_risco" value="" id="risco" /><input type="hidden" id="risco_nome" name="risco_nome" value="">';
		echo '<input type="hidden" name="instrumento_risco_resposta" value="" id="risco_resposta" /><input type="hidden" id="risco_resposta_nome" name="risco_resposta_nome" value="">';

		echo '<input type="hidden" name="instrumento_template" value="" id="template" /><input type="hidden" id="template_nome" name="template_nome" value="">';
		echo '<input type="hidden" name="instrumento_painel" value="" id="painel" /><input type="hidden" id="painel_nome" name="painel_nome" value="">';
		echo '<input type="hidden" name="instrumento_painel_odometro" value="" id="painel_odometro" /><input type="hidden" id="painel_odometro_nome" name="painel_odometro_nome" value="">';
		echo '<input type="hidden" name="instrumento_painel_composicao" value="" id="painel_composicao" /><input type="hidden" id="painel_composicao_nome" name="painel_composicao_nome" value="">';
		echo '<input type="hidden" name="instrumento_tr" value="" id="tr" /><input type="hidden" id="tr_nome" name="tr_nome" value="">';
		echo '<input type="hidden" name="instrumento_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';
		}
	else {
		echo '<tr '.($instrumento_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_risco" value="'.$instrumento_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($instrumento_risco).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($instrumento_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_risco_resposta" value="'.$instrumento_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($instrumento_risco_resposta).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($instrumento_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_canvas" value="'.$instrumento_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($instrumento_canvas).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($instrumento_template ? '' : 'style="display:none"').' id="template" ><td align="right" nowrap="nowrap">'.dica('Modelo', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_template" value="'.$instrumento_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($instrumento_template).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';

		echo '<tr '.($instrumento_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" nowrap="nowrap">'.dica('Painel de Indicador', 'Caso o instrumento seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_painel" value="'.$instrumento_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($instrumento_painel).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($instrumento_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" nowrap="nowrap">'.dica('Odômetro de Indicador', 'Caso o instrumento seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_painel_odometro" value="'.$instrumento_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($instrumento_painel_odometro).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($instrumento_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" nowrap="nowrap">'.dica('Composição de Painéis', 'Caso o instrumento seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_painel_composicao" value="'.$instrumento_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($instrumento_painel_composicao).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/painel_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/painel_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($instrumento_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_tr" value="'.$instrumento_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($instrumento_tr).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
		if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo '<tr '.($instrumento_me ? '' : 'style="display:none"').' id="me" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_me" value="'.$instrumento_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($instrumento_me).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
		else echo '<input type="hidden" name="instrumento_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';


		}
	if ($swot_ativo) echo '<tr '.(isset($instrumento_swot) && $instrumento_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" nowrap="nowrap">'.dica('Campo SWOT', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um campo da matriz SWOT neste campo deverá constar o nome do campo da matriz SWOT').'campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_swot" value="'.(isset($instrumento_swot) ? $instrumento_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($instrumento_swot) ? $instrumento_swot : null)).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('../../../modulos/swot/imagens/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('../../../modulos/swot/imagens/swot_p.png').' para selecionar um campo da matriz SWOT.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="instrumento_swot" value="" id="swot" /><input type="hidden" id="swot_nome" name="swot_nome" value="">';
	if ($ata_ativo) echo '<tr '.(isset($instrumento_ata) && $instrumento_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" nowrap="nowrap">'.dica('Ata de Reunião', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_ata" value="'.(isset($instrumento_ata) ? $instrumento_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($instrumento_ata) ? $instrumento_ata : null)).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('../../../modulos/atas/imagens/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('../../../modulos/atas/imagens/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="instrumento_ata" value="" id="ata" /><input type="hidden" id="ata_nome" name="ata_nome" value="">';
	if ($operativo_ativo) echo '<tr '.($instrumento_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' seja específic'.$config['genero_instrumento'].' de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_operativo" value="'.$instrumento_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($instrumento_operativo).'" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="instrumento_operativo" value="" id="operativo" /><input type="hidden" id="operativo_nome" name="operativo_nome" value="">';
	if ($Aplic->profissional){
		$sql->adTabela('instrumento_gestao');
		$sql->adCampo('instrumento_gestao.*');
		$sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);
		$sql->adOrdem('instrumento_gestao_ordem');
	  $lista = $sql->Lista();
	  $sql->Limpar();
		echo '<tr><td></td><td><div id="combo_gestao">';
		if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
		foreach($lista as $gestao_data){
			echo '<tr align="center">';
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			if ($gestao_data['instrumento_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['instrumento_gestao_tarefa']).'</td>';
			elseif ($gestao_data['instrumento_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['instrumento_gestao_projeto']).'</td>';
			elseif ($gestao_data['instrumento_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['instrumento_gestao_pratica']).'</td>';
			elseif ($gestao_data['instrumento_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['instrumento_gestao_acao']).'</td>';
			elseif ($gestao_data['instrumento_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['instrumento_gestao_perspectiva']).'</td>';
			elseif ($gestao_data['instrumento_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['instrumento_gestao_tema']).'</td>';
			elseif ($gestao_data['instrumento_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['instrumento_gestao_objetivo']).'</td>';
			elseif ($gestao_data['instrumento_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['instrumento_gestao_fator']).'</td>';
			elseif ($gestao_data['instrumento_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['instrumento_gestao_estrategia']).'</td>';
			elseif ($gestao_data['instrumento_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['instrumento_gestao_meta']).'</td>';
			elseif ($gestao_data['instrumento_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['instrumento_gestao_canvas']).'</td>';
			elseif ($gestao_data['instrumento_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['instrumento_gestao_risco']).'</td>';
			elseif ($gestao_data['instrumento_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['instrumento_gestao_risco_resposta']).'</td>';
			elseif ($gestao_data['instrumento_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['instrumento_gestao_indicador']).'</td>';
			elseif ($gestao_data['instrumento_gestao_calendario']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_calendario($gestao_data['instrumento_gestao_calendario']).'</td>';
			elseif ($gestao_data['instrumento_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['instrumento_gestao_monitoramento']).'</td>';
			elseif ($gestao_data['instrumento_gestao_ata']) echo '<td align=left>'.imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['instrumento_gestao_ata']).'</td>';
			elseif ($gestao_data['instrumento_gestao_swot']) echo '<td align=left>'.imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['instrumento_gestao_swot']).'</td>';
			elseif ($gestao_data['instrumento_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['instrumento_gestao_operativo']).'</td>';
			elseif ($gestao_data['instrumento_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['instrumento_gestao_recurso']).'</td>';
			elseif ($gestao_data['instrumento_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema_pro($gestao_data['instrumento_gestao_problema']).'</td>';
			elseif ($gestao_data['instrumento_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['instrumento_gestao_demanda']).'</td>';
			elseif ($gestao_data['instrumento_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['instrumento_gestao_programa']).'</td>';
			elseif ($gestao_data['instrumento_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['instrumento_gestao_licao']).'</td>';
			elseif ($gestao_data['instrumento_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['instrumento_gestao_evento']).'</td>';
			elseif ($gestao_data['instrumento_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['instrumento_gestao_link']).'</td>';
			elseif ($gestao_data['instrumento_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['instrumento_gestao_avaliacao']).'</td>';
			elseif ($gestao_data['instrumento_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['instrumento_gestao_tgn']).'</td>';
			elseif ($gestao_data['instrumento_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['instrumento_gestao_brainstorm']).'</td>';
			elseif ($gestao_data['instrumento_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut_pro($gestao_data['instrumento_gestao_gut']).'</td>';
			elseif ($gestao_data['instrumento_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['instrumento_gestao_causa_efeito']).'</td>';
			elseif ($gestao_data['instrumento_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['instrumento_gestao_arquivo']).'</td>';
			elseif ($gestao_data['instrumento_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['instrumento_gestao_forum']).'</td>';
			elseif ($gestao_data['instrumento_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['instrumento_gestao_checklist']).'</td>';
			elseif ($gestao_data['instrumento_gestao_agenda']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_agenda($gestao_data['instrumento_gestao_agenda']).'</td>';
			elseif ($gestao_data['instrumento_gestao_agrupamento']) echo '<td align=left>'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['instrumento_gestao_agrupamento']).'</td>';
			elseif ($gestao_data['instrumento_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['instrumento_gestao_patrocinador']).'</td>';
			elseif ($gestao_data['instrumento_gestao_template']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_template($gestao_data['instrumento_gestao_template']).'</td>';

			elseif ($gestao_data['instrumento_gestao_painel']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_painel($gestao_data['instrumento_gestao_painel']).'</td>';
			elseif ($gestao_data['instrumento_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['instrumento_gestao_painel_odometro']).'</td>';
			elseif ($gestao_data['instrumento_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['instrumento_gestao_painel_composicao']).'</td>';
			elseif ($gestao_data['instrumento_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['instrumento_gestao_tr']).'</td>';
			elseif ($gestao_data['instrumento_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['instrumento_gestao_me']).'</td>';


			echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['instrumento_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
			}
		if (count($lista)) echo '</table>';
		echo '</div></td></tr>';
		}
	}


echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o instrumento ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="instrumento_ativo" '.($obj->instrumento_ativo || !$instrumento_id ? 'checked="checked"' : '').' /></td></tr>';
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('instrumento', $instrumento_id, 'editar');
$campos_customizados->imprimirHTML();


if ($Aplic->profissional) include_once BASE_DIR.'/modulos/recursos/instrumento_editar_pro.php';


echo '<tr><td style="height:3px;"></td></tr>';
echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_notificar\').style.display) document.getElementById(\'apresentar_notificar\').style.display=\'\'; else document.getElementById(\'apresentar_notificar\').style.display=\'none\';"><a class="aba" href="javascript: void(0);"><b>Notificar</b></a></td></tr>';
echo '<tr id="apresentar_notificar" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';



echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($instrumento_id > 0 ? 'modificação' : 'criação').' d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pel'.$config['genero_instrumento'].' '.$config['instrumento'].'', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por '.($config['genero_instrumento']=='o' ? 'este' : 'esta').' '.$config['instrumento'].'.').'<label for="email_responsavel">Responsável pel'.$config['genero_instrumento'].' '.$config['instrumento'].'</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_instrumento'].' '.$config['instrumento'].'', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para '.($config['genero_instrumento']=='o' ? 'este' : 'esta').' '.$config['instrumento'].'.').'<label for="email_designados">Designados para '.$config['genero_instrumento'].' '.$config['instrumento'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro d'.$config['genero_instrumento'].' '.$config['instrumento'].'.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';



echo '</table></td></tr>';


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($instrumento_id ? 'edição' : 'criação').' d'.$config['genero_instrumento'].' '.$config['instrumento'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0,  \'m=recursos&a='.($instrumento_id ? 'instrumento_ver&instrumento_id='.(int)$instrumento_id : 'instrumento_lista').'\') }').'</td></tr></table></td></tr>';

echo '</table>';
echo estiloFundoCaixa();

$instrumento_data_celebracao = ($obj->instrumento_data_celebracao ? new CData($obj->instrumento_data_celebracao) : new CData());
$instrumento_data_publicacao = ($obj->instrumento_data_publicacao ? new CData($obj->instrumento_data_publicacao) : new CData());
$instrumento_data_inicio = ($obj->instrumento_data_inicio ? new CData($obj->instrumento_data_inicio) : new CData());
$instrumento_data_termino = ($obj->instrumento_data_termino ? new CData($obj->instrumento_data_termino) : new CData());
echo '</form>';
?>

<script language="javascript">

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('instrumento_cia').value+'&cias_id_selecionadas='+document.getElementById('instrumento_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.instrumento_cias.value = organizacao_id_string;
	document.getElementById('instrumento_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('instrumento_cias').value);
	__buildTooltip();
	}


function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var instrumento_emails = document.getElementById('instrumento_usuarios');
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





function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}


function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}

var cal1 = Calendario.setup({
	trigger    : "botao_instrumento_data_celebracao",
  inputField : "instrumento_data_celebracao",
	date :  <?php echo $instrumento_data_celebracao->format("%Y%m%d")?>,
	selection: <?php echo $instrumento_data_celebracao->format("%Y%m%d")?>,
  onSelect: function(cal1) {
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_data_celebracao_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_data_celebracao").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal1.hide();
	}
});

var cal2 = Calendario.setup({
	trigger    : "botao_instrumento_data_publicacao",
  inputField : "instrumento_data_publicacao",
	date :  <?php echo $instrumento_data_publicacao->format("%Y%m%d")?>,
	selection: <?php echo $instrumento_data_publicacao->format("%Y%m%d")?>,
  onSelect: function(cal2) {
  var date = cal2.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_data_publicacao_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_data_publicacao").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal2.hide();
	}
});

var cal3 = Calendario.setup({
	trigger    : "botao_instrumento_data_inicio",
  inputField : "instrumento_data_inicio",
	date :  <?php echo $instrumento_data_inicio->format("%Y%m%d")?>,
	selection: <?php echo $instrumento_data_inicio->format("%Y%m%d")?>,
  onSelect: function(cal3) {
  var date = cal3.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_data_inicio_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal3.hide();
	}
});

var cal4 = Calendario.setup({
	trigger    : "botao_instrumento_data_termino",
  inputField : "instrumento_data_termino",
	date :  <?php echo $instrumento_data_termino->format("%Y%m%d")?>,
	selection: <?php echo $instrumento_data_termino->format("%Y%m%d")?>,
  onSelect: function(cal4) {
  var date = cal4.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_data_termino_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_data_termino").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal4.hide();
	}
});



function setData( frm_nome, f_data , f_data_real) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
    	}
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';	}




function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.instrumento_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.instrumento_cor.value;
	}


function enviarDados() {
	var f = document.env;
	if (f.instrumento_nome.value.length < 1) {
		alert( "Insira um nome para <?php echo $config['genero_instrumento'].' '.$config['instrumento']?>" );
		form.instrumento_nome.focus();
		}
	else {
		f.instrumento_valor.value=moeda2float(f.instrumento_valor.value);
		f.instrumento_valor_contrapartida.value=moeda2float(f.instrumento_valor_contrapartida.value);
		f.submit();
		}
	}

function excluir() {
	if (confirm( "Excluir <?php echo ($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento']?>?" )) {
		var f = document.env;
		f.del.value='1';
		f.submit();
		}
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('instrumento_cia').value,'instrumento_cia','combo_cia', 'class="texto" size=1 style="width:286px;" onchange="javascript:mudar_om();"');
	}


var recursos_id_selecionados = '<?php echo implode(",", $recursos_selecionados)?>';

function popRecursos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["recursos"])?>', 500, 500, 'm=publico&a=selecionar_multiplo&tabela=recursos&dialogo=1&chamar_volta=setRecursos&cia_id='+document.getElementById('instrumento_cia').value+'&valores='+recursos_id_selecionados, window.setRecursos, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&tabela=recursos&dialogo=1&chamar_volta=setRecursos&cia_id='+document.getElementById('instrumento_cia').value+'&valores='+recursos_id_selecionados, '<?php echo ucfirst($config["recursos"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setRecursos(recurso_id_string){
	if(!recurso_id_string) recurso_id_string = '';
	document.env.instrumento_recursos.value = recurso_id_string;
	recursos_id_selecionados = recurso_id_string;
	xajax_exibir_recursos(recursos_id_selecionados);
	__buildTooltip();
	}




var contatos_id_selecionados = '<?php echo implode(",", $contatos_selecionados)?>';

function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('instrumento_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('instrumento_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, '<?php echo ucfirst($config["contatos"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.instrumento_contatos.value = contato_id_string;
	contatos_id_selecionados = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados);
	__buildTooltip();
	}


var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('instrumento_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('instrumento_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.instrumento_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('instrumento_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('instrumento_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.instrumento_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('instrumento_dept').value+'&cia_id='+document.getElementById('instrumento_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('instrumento_dept').value+'&cia_id='+document.getElementById('instrumento_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('instrumento_cia').value=cia_id;
	document.getElementById('instrumento_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('instrumento_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}





function popSupervisor() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['supervisor']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_supervisor').value, window.setSupervisor, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_supervisor').value, 'Supervisor','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setSupervisor(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('instrumento_supervisor').value=usuario_id;
		document.getElementById('nome_supervisor').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}

function popAutoridade() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['autoridade']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_autoridade').value, window.setAutoridade, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_autoridade').value, 'Autoridade','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('instrumento_autoridade').value=usuario_id;
		document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function popCliente() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['cliente']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_cliente').value, window.setCliente, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCliente&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_cliente').value, "<?php echo ucfirst($config['cliente']) ?>",'height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setCliente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('instrumento_cliente').value=usuario_id;
		document.getElementById('nome_cliente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
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
	document.getElementById('painel').style.display='none';
	document.getElementById('painel_odometro').style.display='none';
	document.getElementById('painel_composicao').style.display='none';
	<?php
	if($agrupamento_ativo) echo 'document.getElementById(\'agrupamento\').style.display=\'none\';';
	if($patrocinador_ativo) echo 'document.getElementById(\'patrocinador\').style.display=\'none\';';
	if($swot_ativo) echo 'document.getElementById(\'swot\').style.display=\'none\';';
	if($ata_ativo) echo 'document.getElementById(\'ata\').style.display=\'none\';';
	if($operativo_ativo) echo 'document.getElementById(\'operativo\').style.display=\'none\';';
	if($tr_ativo) echo 'document.getElementById(\'tr\').style.display=\'none\';';
	if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.getElementById(\'me\').style.display=\'none\';';
	?>
	}


<?php  if ($Aplic->profissional) { ?>

	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('instrumento_id').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('instrumento_id').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.instrumento_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Patrocinador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('instrumento_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('instrumento_cia').value, 'Patrocinador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.instrumento_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('instrumento_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('instrumento_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.instrumento_template.value = chave;
		document.env.template_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popPainel() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('instrumento_cia').value, window.setPainel, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('instrumento_cia').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPainel(chave, valor){
		limpar_tudo();
		document.env.instrumento_painel.value = chave;
		document.env.painel_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popOdometro() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('instrumento_cia').value, window.setOdometro, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('instrumento_cia').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setOdometro(chave, valor){
		limpar_tudo();
		document.env.instrumento_painel_odometro.value = chave;
		document.env.painel_odometro_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popComposicaoPaineis() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('instrumento_cia').value, window.setComposicaoPaineis, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('instrumento_cia').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setComposicaoPaineis(chave, valor){
		limpar_tudo();
		document.env.instrumento_painel_composicao.value = chave;
		document.env.painel_composicao_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTR() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('instrumento_cia').value, window.setTR, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTR(chave, valor){
		limpar_tudo();
		document.env.instrumento_tr.value = chave;
		document.env.tr_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popMe() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('instrumento_cia').value, window.setMe, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setMe(chave, valor){
		limpar_tudo();
		document.env.instrumento_me.value = chave;
		document.env.me_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

<?php } ?>


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('instrumento_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.instrumento_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	}

function popTarefa() {
	var f = document.env;
	if (f.instrumento_projeto.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.instrumento_projeto.value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.instrumento_projeto.value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.instrumento_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('instrumento_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.instrumento_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('instrumento_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.instrumento_tema.value = chave;
	document.env.tema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('instrumento_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.instrumento_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('instrumento_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.instrumento_fator.value = chave;
	document.env.fator_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('instrumento_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.instrumento_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('instrumento_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.instrumento_meta.value = chave;
	document.env.meta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('instrumento_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.instrumento_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('instrumento_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('instrumento_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.instrumento_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('instrumento_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.instrumento_acao.value = chave;
	document.env.acao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('instrumento_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.instrumento_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('instrumento_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRisco(chave, valor){
	limpar_tudo();
	document.env.instrumento_risco.value = chave;
	document.env.risco_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco_respostas'])) { ?>
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('instrumento_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.instrumento_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>


function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('instrumento_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('instrumento_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.instrumento_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('instrumento_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('instrumento_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.instrumento_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('instrumento_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.instrumento_ata.value = chave;
	document.env.ata_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popSWOT() {
	parent.gpwebApp.popUp('SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('instrumento_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.instrumento_swot.value = chave;
	document.env.swot_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('instrumento_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('instrumento_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.instrumento_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	}

function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('instrumento_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('instrumento_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.instrumento_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('instrumento_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.instrumento_problema.value = chave;
	document.env.problema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('instrumento_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('instrumento_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.instrumento_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('instrumento_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.instrumento_programa.value = chave;
	document.env.programa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('instrumento_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.instrumento_licao.value = chave;
	document.env.licao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}


function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('instrumento_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('instrumento_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.instrumento_evento.value = chave;
	document.env.evento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('instrumento_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('instrumento_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.instrumento_link.value = chave;
	document.env.link_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('instrumento_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('instrumento_cia').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.instrumento_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('instrumento_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.instrumento_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('instrumento_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('instrumento_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.instrumento_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('instrumento_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('instrumento_cia').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.instrumento_gut.value = chave;
	document.env.gut_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('instrumento_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('instrumento_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.instrumento_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('instrumento_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('instrumento_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.instrumento_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('instrumento_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('instrumento_cia').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.instrumento_forum.value = chave;
	document.env.forum_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('instrumento_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('instrumento_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.instrumento_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('instrumento_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('instrumento_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.instrumento_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}



function limpar_tudo(){
	if (document.getElementById('tipo_relacao').value!='projeto'){
		document.env.projeto_nome.value = '';
		document.env.instrumento_projeto.value = null;
		}
	document.env.instrumento_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.instrumento_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.instrumento_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.instrumento_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.instrumento_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.instrumento_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.instrumento_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.instrumento_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.instrumento_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.instrumento_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.instrumento_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.instrumento_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.instrumento_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.instrumento_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.instrumento_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.instrumento_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.instrumento_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.instrumento_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.instrumento_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.instrumento_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.instrumento_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.instrumento_link.value = null;
	document.env.link_nome.value = '';
	document.env.instrumento_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.instrumento_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.instrumento_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.instrumento_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.instrumento_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.instrumento_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.instrumento_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.instrumento_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.instrumento_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.instrumento_template.value = null;
	document.env.template_nome.value = '';
	document.env.instrumento_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.instrumento_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.instrumento_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	<?php
	if($swot_ativo) echo 'document.env.swot_nome.value = \'\';	document.env.instrumento_swot.value = null;';
	if($ata_ativo) echo 'document.env.ata_nome.value = \'\';	document.env.instrumento_ata.value = null;';
	if($operativo_ativo) echo 'document.env.operativo_nome.value = \'\';	document.env.instrumento_operativo.value = null;';
	if($agrupamento_ativo) echo 'document.env.agrupamento_nome.value = \'\';	document.env.instrumento_agrupamento.value = null;';
	if($patrocinador_ativo) echo 'document.env.patrocinador_nome.value = \'\';	document.env.instrumento_patrocinador.value = null;';
	if($tr_ativo) echo 'document.env.tr_nome.value = \'\';	document.env.instrumento_tr.value = null;';
	if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.env.me_nome.value = \'\';	document.env.instrumento_me.value = null;';
	?>
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	    document.getElementById('instrumento_id').value,
	    document.getElementById('uuid').value,
	    f.instrumento_projeto.value,
	    f.instrumento_tarefa.value,
	    f.instrumento_perspectiva.value,
	    f.instrumento_tema.value,
	    f.instrumento_objetivo.value,
	    f.instrumento_fator.value,
	    f.instrumento_estrategia.value,
	    f.instrumento_meta.value,
	    f.instrumento_pratica.value,
	    f.instrumento_acao.value,
	    f.instrumento_canvas.value,
	    f.instrumento_risco.value,
	    f.instrumento_risco_resposta.value,
	    f.instrumento_indicador.value,
	    f.instrumento_calendario.value,
	    f.instrumento_monitoramento.value,
	    f.instrumento_ata.value,
	    f.instrumento_swot.value,
	    f.instrumento_operativo.value,
	    f.instrumento_recurso.value,
	    f.instrumento_problema.value,
	    f.instrumento_demanda.value,
	    f.instrumento_programa.value,
	    f.instrumento_licao.value,
	    f.instrumento_evento.value,
	    f.instrumento_link.value,
	    f.instrumento_avaliacao.value,
	    f.instrumento_tgn.value,
	    f.instrumento_brainstorm.value,
	    f.instrumento_gut.value,
	    f.instrumento_causa_efeito.value,
	    f.instrumento_arquivo.value,
	    f.instrumento_forum.value,
	    f.instrumento_checklist.value,
	    f.instrumento_agenda.value,
	    f.instrumento_agrupamento.value,
	    f.instrumento_patrocinador.value,
	    f.instrumento_template.value,
	    f.instrumento_painel.value,
	    f.instrumento_painel_odometro.value,
	    f.instrumento_painel_composicao.value,
	    f.instrumento_tr.value,
	    f.instrumento_me.value
	    );
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(instrumento_gestao_id){
	xajax_excluir_gestao(document.getElementById('instrumento_id').value, document.getElementById('uuid').value, instrumento_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, instrumento_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, instrumento_gestao_id, direcao, document.getElementById('instrumento_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$instrumento_id && (
	$instrumento_projeto ||
	$instrumento_tarefa ||
	$instrumento_perspectiva ||
	$instrumento_tema ||
	$instrumento_objetivo ||
	$instrumento_fator ||
	$instrumento_estrategia ||
	$instrumento_meta ||
	$instrumento_pratica ||
	$instrumento_acao ||
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
	$instrumento_painel_composicao ||
	$instrumento_tr ||
	$instrumento_me
	)){
    echo 'incluir_relacionado();';
    }
	?>





window.addEvent('domready', function(){
	//cuidar nome da variavel, para não conflitar
	_toolTip = new Tips();
	});

</script>
