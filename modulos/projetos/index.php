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
if (!$Aplic->checarModulo('projetos', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');


if($Aplic->profissional){
  require_once(BASE_DIR.'/incluir/ext_util_pro.php');
}

$sql = new BDConsulta;

$projetos_id = getParam($_REQUEST, 'projeto_id', array());

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

//atualizar status dos projetos selecionados
if (isset($_REQUEST['atualizar_projeto_status']) && $_REQUEST['atualizar_projeto_status']  && isset($_REQUEST['projeto_status']) && isset($_REQUEST['projeto_id'])) {
	$status=getParam($_REQUEST, 'projeto_status', null);
	foreach ($projetos_id as $projeto_id) {
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_acesso');
		$sql->adOnde('projeto_id='.(int)$projeto_id);
		$acesso=$sql->resultado();
		$sql->limpar();

		if (permiteEditar($acesso=0, $projeto_id)) {
			$sql->adTabela('projetos');
			$sql->adAtualizar('projeto_status', $status);
			$sql->adOnde('projeto_id   = '.(int)$projeto_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	$projeto_id=null;
	$_REQUEST['projeto_id']=null;
	}


//mover os projetos nas semanas
if (isset($_REQUEST['modificar_datas_projeto']) && $_REQUEST['modificar_datas_projeto'] && isset($_REQUEST['mover_semanas']) && isset($_REQUEST['projeto_id'])) {
	$mover_semanas=getParam($_REQUEST, 'mover_semanas', null);
	$periodo=substr($mover_semanas, 0, 1);
	$semanas=substr($mover_semanas, 1, 3);
	if ($periodo=='d') $periodo='DAY';
	elseif ($periodo=='s') $periodo='WEEK';
	elseif ($periodo=='m') $periodo='MONTH';
	foreach ($projetos_id as $projeto_id) {
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_acesso');
		$sql->adOnde('projeto_id='.(int)$projeto_id);
		$acesso=$sql->resultado();
		$sql->limpar();
		if (permiteEditar($acesso=0, $projeto_id)) {
			$sql->adTabela('projetos');
			$sql->adCampo('adiciona_data((select projeto_data_inicio FROM projetos WHERE projeto_id='.$projeto_id.'), '.$semanas.', \''.$periodo.'\') AS inicio');
			$sql->adCampo('adiciona_data((select projeto_data_fim FROM projetos WHERE projeto_id='.$projeto_id.'), '.$semanas.', \''.$periodo.'\') AS fim');
			$sql->adCampo('adiciona_data((select projeto_fim_atualizado FROM projetos WHERE projeto_id='.$projeto_id.'), '.$semanas.', \''.$periodo.'\') AS fim_atualizado');
			$datas=$sql->Linha();
			$sql->limpar();
			$sql->adTabela('projetos');
			if ($datas['inicio']) $sql->adAtualizar('projeto_data_inicio', $datas['inicio']);
			if ($datas['fim']) $sql->adAtualizar('projeto_data_fim',  $datas['fim']);
			if ($datas['fim_atualizado']) $sql->adAtualizar('projeto_fim_atualizado', $datas['fim_atualizado']);
			$sql->adOnde('projeto_id   = '.(int)$projeto_id);
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('tarefas');
			$sql->adCampo('tarefa_id');
			$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
			$tarefas_id=$sql->ListaChave('tarefa_id');
			$sql->limpar();
			foreach ($tarefas_id as $tarefa_id) {
				$sql->adTabela('tarefas');
				$sql->adCampo('adiciona_data((select tarefa_inicio FROM tarefas WHERE tarefa_id='.$tarefa_id['tarefa_id'].'), '.$semanas.', \''.$periodo.'\') AS inicio');
				$sql->adCampo('adiciona_data((select tarefa_fim FROM tarefas WHERE tarefa_id='.$tarefa_id['tarefa_id'].'), '.$semanas.', \''.$periodo.'\') AS fim');
                $sql->adCampo('adiciona_data((select tarefa_inicio_manual FROM tarefas WHERE tarefa_id='.$tarefa_id['tarefa_id'].'), '.$semanas.', \''.$periodo.'\') AS inicio_manual');
                $sql->adCampo('adiciona_data((select tarefa_fim_manual FROM tarefas WHERE tarefa_id='.$tarefa_id['tarefa_id'].'), '.$semanas.', \''.$periodo.'\') AS fim_manual');
				$datas=$sql->Linha();
				$sql->limpar();
				$sql->adTabela('tarefas');
				if ($datas['inicio']) $sql->adAtualizar('tarefa_inicio', $datas['inicio']);
                if ($datas['inicio_manual']) $sql->adAtualizar('tarefa_inicio_manual', $datas['inicio_manual']);
                if ($datas['fim_manual']) $sql->adAtualizar('tarefa_fim_manual',  $datas['fim_manual']);
				if ($datas['fim']) $sql->adAtualizar('tarefa_fim',  $datas['fim']);
				$sql->adOnde('tarefa_id   = '.(int)$tarefa_id['tarefa_id']);
				$sql->exec();
				$sql->limpar();
				}

			}
		}
	$projeto_id=null;
	$_REQUEST['projeto_id']=null;
	}

$filtro_acionado=getParam($_REQUEST, 'filtro_acionado', null);

if (isset($_REQUEST['ordemPor'])) $Aplic->setEstado('ProjIdxOrdemPor', getParam($_REQUEST, 'ordemPor', null));
$ordenarPor = $Aplic->getEstado('ProjIdxOrdemPor') ? $Aplic->getEstado('ProjIdxOrdemPor') : 'projeto_nome';

if (isset($_REQUEST['ordemDir'])) $Aplic->setEstado('ordemDir', getParam($_REQUEST, 'ordemDir', ''));
$ordemDir = $Aplic->getEstado('ordemDir') ? $Aplic->getEstado('ordemDir') : 'desc';
if ($ordemDir == 'asc') $ordemDir = 'desc';
else $ordemDir = 'asc';

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'projeto\'');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

if (isset($_REQUEST['nao_apenas_superiores'])) $Aplic->setEstado('nao_apenas_superiores', getParam($_REQUEST, 'nao_apenas_superiores', null));
$nao_apenas_superiores = $Aplic->getEstado('nao_apenas_superiores') !== null ? $Aplic->getEstado('nao_apenas_superiores') : 0;

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ListaProjetoTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ListaProjetoTab') !== null ? $Aplic->getEstado('ListaProjetoTab') : 0;

if (isset($_REQUEST['projetostatus']) || $filtro_acionado)	$Aplic->setEstado('projetostatus', getParam($_REQUEST, 'projetostatus', null));
$projetostatus = $Aplic->getEstado('projetostatus') !== null ? $Aplic->getEstado('projetostatus') : ($filtro_acionado ? null : -1);

if (isset($_REQUEST['projeto_tipo']) || $filtro_acionado) $Aplic->setEstado('projeto_tipo', getParam($_REQUEST, 'projeto_tipo', null));
$projeto_tipo = $Aplic->getEstado('projeto_tipo') !== null ? $Aplic->getEstado('projeto_tipo') : -1;

if (isset($_REQUEST['favorito_id']) || $filtro_acionado)	$Aplic->setEstado('projeto_favorito', getParam($_REQUEST, 'favorito_id', null));
$favorito_id = $Aplic->getEstado('projeto_favorito') !== null ? $Aplic->getEstado('projeto_favorito') : 0;

if (isset($_REQUEST['estado_sigla']))	$Aplic->setEstado('estado_sigla', getParam($_REQUEST, 'estado_sigla', null));
$estado_sigla = ($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : '');

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('municipio_id', getParam($_REQUEST, 'municipio_id', null));
$municipio_id = ($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '');

if (isset($_REQUEST['responsavel']))	$Aplic->setEstado('responsavel', getParam($_REQUEST, 'responsavel', null));
$responsavel = $Aplic->getEstado('responsavel') !== null ? $Aplic->getEstado('responsavel') : 0;

if (isset($_REQUEST['supervisor']))	$Aplic->setEstado('supervisor', getParam($_REQUEST, 'supervisor', null));
$supervisor = $Aplic->getEstado('supervisor') !== null ? $Aplic->getEstado('supervisor') : 0;

if (isset($_REQUEST['autoridade']))	$Aplic->setEstado('autoridade', getParam($_REQUEST, 'autoridade', null));
$autoridade = $Aplic->getEstado('autoridade') !== null ? $Aplic->getEstado('autoridade') : 0;

if (isset($_REQUEST['cliente']))	$Aplic->setEstado('cliente', getParam($_REQUEST, 'cliente', null));
$cliente = $Aplic->getEstado('cliente') !== null ? $Aplic->getEstado('cliente') : 0;

if (isset($_REQUEST['projeto_setor']))	$Aplic->setEstado('projeto_setor',getParam($_REQUEST, 'projeto_setor', null));
$projeto_setor = $Aplic->getEstado('projeto_setor') !== null ? $Aplic->getEstado('projeto_setor') : '';

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projeto_segmento',getParam($_REQUEST, 'projeto_segmento', null));
$projeto_segmento = $Aplic->getEstado('projeto_segmento') !== null ? $Aplic->getEstado('projeto_segmento') : '';

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projeto_intervencao', getParam($_REQUEST, 'projeto_intervencao', null));
$projeto_intervencao = $Aplic->getEstado('projeto_intervencao') !== null ? $Aplic->getEstado('projeto_intervencao') : '';

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projeto_tipo_intervencao', getParam($_REQUEST, 'projeto_tipo_intervencao', null));
$projeto_tipo_intervencao = $Aplic->getEstado('projeto_tipo_intervencao') !== null ? $Aplic->getEstado('projeto_tipo_intervencao') : '';

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projtextobusca', getParam($_REQUEST, 'projtextobusca', ''));
$pesquisar_texto = $Aplic->getEstado('projtextobusca') !== null ? $Aplic->getEstado('projtextobusca') : '';

if (isset($_REQUEST['filtro_area'])) $Aplic->setEstado('filtro_area', getParam($_REQUEST, 'filtro_area', ''));
$filtro_area = $Aplic->getEstado('filtro_area') !== null ? $Aplic->getEstado('filtro_area') : '';

if (isset($_REQUEST['filtro_criterio']))	$Aplic->setEstado('filtro_criterio', getParam($_REQUEST, 'filtro_criterio', null));
$filtro_criterio = $Aplic->getEstado('filtro_criterio') !== null ? $Aplic->getEstado('filtro_criterio') : 0;


if (isset($_REQUEST['filtro_perspectiva']))	$Aplic->setEstado('filtro_perspectiva', getParam($_REQUEST, 'filtro_perspectiva', null));
$filtro_perspectiva = $Aplic->getEstado('filtro_perspectiva') !== null ? $Aplic->getEstado('filtro_perspectiva') : 0;

if (isset($_REQUEST['filtro_canvas']))	$Aplic->setEstado('filtro_canvas', getParam($_REQUEST, 'filtro_canvas', null));
$filtro_canvas = $Aplic->getEstado('filtro_canvas') !== null ? $Aplic->getEstado('filtro_canvas') : 0;

if (isset($_REQUEST['filtro_tema']))	$Aplic->setEstado('filtro_tema', getParam($_REQUEST, 'filtro_tema', null));
$filtro_tema = $Aplic->getEstado('filtro_tema') !== null ? $Aplic->getEstado('filtro_tema') : 0;

if (isset($_REQUEST['filtro_objetivo']))	$Aplic->setEstado('filtro_objetivo', getParam($_REQUEST, 'filtro_objetivo', null));
$filtro_objetivo = $Aplic->getEstado('filtro_objetivo') !== null ? $Aplic->getEstado('filtro_objetivo') : 0;

if (isset($_REQUEST['filtro_fator']))	$Aplic->setEstado('filtro_fator', getParam($_REQUEST, 'filtro_fator', null));
$filtro_fator = $Aplic->getEstado('filtro_fator') !== null ? $Aplic->getEstado('filtro_fator') : 0;

if (isset($_REQUEST['filtro_estrategia']))	$Aplic->setEstado('filtro_estrategia', getParam($_REQUEST, 'filtro_estrategia', null));
$filtro_estrategia = $Aplic->getEstado('filtro_estrategia') !== null ? $Aplic->getEstado('filtro_estrategia') : 0;

if (isset($_REQUEST['filtro_meta']))	$Aplic->setEstado('filtro_meta', getParam($_REQUEST, 'filtro_meta', null));
$filtro_meta = $Aplic->getEstado('filtro_meta') !== null ? $Aplic->getEstado('filtro_meta') : 0;

if (isset($_REQUEST['filtro_prioridade']))	$Aplic->setEstado('filtro_prioridade', getParam($_REQUEST, 'filtro_prioridade', null));
$filtro_prioridade = $Aplic->getEstado('filtro_prioridade') !== null ? $Aplic->getEstado('filtro_prioridade') : null;

if (isset($_REQUEST['filtro_opcao']))	$Aplic->setEstado('filtro_opcao', getParam($_REQUEST, 'filtro_opcao', null));
$filtro_opcao = $Aplic->getEstado('filtro_opcao') !== null ? $Aplic->getEstado('filtro_opcao') : '';

$filtro_opcoes=array(''=>'', 'gerente'=>'Gerente de '.$config['projeto'], 'equipe_projeto'=>'Equipe de '.$config['projeto'], 'tarefa'=>'Responsável por '.$config['tarefa'], 'equipe_tarefa'=>'Equipe de '.$config['tarefa'], 'projeto_tarefa'=>'Participa de '.$config['projeto'].' ou '.$config['tarefa']);

if (isset($_REQUEST['pg_perspectiva_id'])) $Aplic->setEstado('pg_perspectiva_id', getParam($_REQUEST,'pg_perspectiva_id', null));
$pg_perspectiva_id  = $Aplic->getEstado('pg_perspectiva_id', null);

if (isset($_REQUEST['tema_id'])) $Aplic->setEstado('tema_id', getParam($_REQUEST,'tema_id', null));
$tema_id  = $Aplic->getEstado('tema_id', null);

if (isset($_REQUEST['pg_objetivo_estrategico_id'])) $Aplic->setEstado('pg_objetivo_estrategico_id', getParam($_REQUEST,'pg_objetivo_estrategico_id', null));
$pg_objetivo_estrategico_id  = $Aplic->getEstado('pg_objetivo_estrategico_id', null);

if (isset($_REQUEST['pg_fator_critico_id'])) $Aplic->setEstado('pg_fator_critico_id', getParam($_REQUEST,'pg_fator_critico_id', null));
$pg_fator_critico_id  = $Aplic->getEstado('pg_fator_critico_id', null);

if (isset($_REQUEST['pg_estrategia_id'])) $Aplic->setEstado('pg_estrategia_id', getParam($_REQUEST,'pg_estrategia_id', null));
$pg_estrategia_id = $Aplic->getEstado('pg_estrategia_id', null);

if (isset($_REQUEST['pg_meta_id'])) $Aplic->setEstado('pg_meta_id', getParam($_REQUEST,'pg_meta_id', null));
$pg_meta_id  = $Aplic->getEstado('pg_meta_id', null);

if (isset($_REQUEST['pratica_id'])) $Aplic->setEstado('pratica_id', getParam($_REQUEST,'pratica_id', null));
$pratica_id  = $Aplic->getEstado('pratica_id', null);

if (isset($_REQUEST['pratica_indicador_id'])) $Aplic->setEstado('pratica_indicador_id', getParam($_REQUEST,'pratica_indicador_id', null));
$pratica_indicador_id  = $Aplic->getEstado('pratica_indicador_id', null);

if (isset($_REQUEST['plano_acao_id'])) $Aplic->setEstado('plano_acao_id', getParam($_REQUEST,'plano_acao_id', null));
$plano_acao_id  = $Aplic->getEstado('plano_acao_id', null);

if (isset($_REQUEST['canvas_id'])) $Aplic->setEstado('canvas_id', getParam($_REQUEST,'canvas_id', null));
$canvas_id  = $Aplic->getEstado('canvas_id', null);

if (isset($_REQUEST['risco_id'])) $Aplic->setEstado('risco_id', getParam($_REQUEST,'risco_id', null));
$risco_id = $Aplic->getEstado('risco_id', null);

if (isset($_REQUEST['risco_resposta_id'])) $Aplic->setEstado('risco_resposta_id', getParam($_REQUEST,'risco_resposta_id', null));
$risco_resposta_id = $Aplic->getEstado('risco_resposta_id', null);

if (isset($_REQUEST['calendario_id'])) $Aplic->setEstado('calendario_id', getParam($_REQUEST,'calendario_id', null));
$calendario_id  = $Aplic->getEstado('calendario_id', null);

if (isset($_REQUEST['monitoramento_id'])) $Aplic->setEstado('monitoramento_id', getParam($_REQUEST,'monitoramento_id', null));
$monitoramento_id  = $Aplic->getEstado('monitoramento_id', null);

if (isset($_REQUEST['ata_id'])) $Aplic->setEstado('ata_id', getParam($_REQUEST,'ata_id', null));
$ata_id  = $Aplic->getEstado('ata_id', null);

if (isset($_REQUEST['swot_id'])) $Aplic->setEstado('swot_id', getParam($_REQUEST,'swot_id', null));
$swot_id  = $Aplic->getEstado('swot_id', null);

if (isset($_REQUEST['operativo_id'])) $Aplic->setEstado('operativo_id', getParam($_REQUEST,'operativo_id', null));
$operativo_id = $Aplic->getEstado('operativo_id', null);

if (isset($_REQUEST['instrumento_id'])) $Aplic->setEstado('instrumento_id', getParam($_REQUEST,'instrumento_id', null));
$instrumento_id = $Aplic->getEstado('instrumento_id', null);

if (isset($_REQUEST['recurso_id'])) $Aplic->setEstado('recurso_id', getParam($_REQUEST,'recurso_id', null));
$recurso_id = $Aplic->getEstado('recurso_id', null);

if (isset($_REQUEST['problema_id'])) $Aplic->setEstado('problema_id', getParam($_REQUEST,'problema_id', null));
$problema_id = $Aplic->getEstado('problema_id', null);

if (isset($_REQUEST['demanda_id'])) $Aplic->setEstado('demanda_id', getParam($_REQUEST,'demanda_id', null));
$demanda_id = $Aplic->getEstado('demanda_id', null);

if (isset($_REQUEST['programa_id'])) $Aplic->setEstado('programa_id', getParam($_REQUEST,'programa_id', null));
$programa_id = $Aplic->getEstado('programa_id', null);

if (isset($_REQUEST['licao_id'])) $Aplic->setEstado('licao_id', getParam($_REQUEST,'licao_id', null));
$licao_id = $Aplic->getEstado('licao_id', null);

if (isset($_REQUEST['evento_id'])) $Aplic->setEstado('evento_id', getParam($_REQUEST,'evento_id', null));
$evento_id = $Aplic->getEstado('evento_id', null);

if (isset($_REQUEST['link_id'])) $Aplic->setEstado('link_id', getParam($_REQUEST,'link_id', null));
$link_id = $Aplic->getEstado('link_id', null);

if (isset($_REQUEST['avaliacao_id'])) $Aplic->setEstado('avaliacao_id', getParam($_REQUEST,'avaliacao_id', null));
$avaliacao_id = $Aplic->getEstado('avaliacao_id', null);

if (isset($_REQUEST['tgn_id'])) $Aplic->setEstado('tgn_id', getParam($_REQUEST,'tgn_id', null));
$tgn_id = $Aplic->getEstado('tgn_id', null);

if (isset($_REQUEST['brainstorm_id'])) $Aplic->setEstado('brainstorm_id', getParam($_REQUEST,'brainstorm_id', null));
$brainstorm_id = $Aplic->getEstado('brainstorm_id', null);

if (isset($_REQUEST['gut_id'])) $Aplic->setEstado('gut_id', getParam($_REQUEST,'gut_id', null));
$gut_id = $Aplic->getEstado('gut_id', null);

if (isset($_REQUEST['causa_efeito_id'])) $Aplic->setEstado('causa_efeito_id', getParam($_REQUEST,'causa_efeito_id', null));
$causa_efeito_id = $Aplic->getEstado('causa_efeito_id', null);

if (isset($_REQUEST['arquivo_id'])) $Aplic->setEstado('arquivo_id', getParam($_REQUEST,'arquivo_id', null));
$arquivo_id = $Aplic->getEstado('arquivo_id', null);

if (isset($_REQUEST['forum_id'])) $Aplic->setEstado('forum_id', getParam($_REQUEST,'forum_id', null));
$forum_id = $Aplic->getEstado('forum_id', null);

if (isset($_REQUEST['checklist_id'])) $Aplic->setEstado('checklist_id', getParam($_REQUEST,'checklist_id', null));
$checklist_id = $Aplic->getEstado('checklist_id', null);

if (isset($_REQUEST['agenda_id'])) $Aplic->setEstado('agenda_id', getParam($_REQUEST,'agenda_id', null));
$agenda_id = $Aplic->getEstado('agenda_id', null);

if (isset($_REQUEST['agrupamento_id'])) $Aplic->setEstado('agrupamento_id', getParam($_REQUEST,'agrupamento_id', null));
$agrupamento_id = $Aplic->getEstado('agrupamento_id', null);

if (isset($_REQUEST['patrocinador_id'])) $Aplic->setEstado('patrocinador_id', getParam($_REQUEST,'patrocinador_id', null));
$patrocinador_id = $Aplic->getEstado('patrocinador_id', null);

if (isset($_REQUEST['template_id'])) $Aplic->setEstado('template_id', getParam($_REQUEST,'template_id', null));
$template_id = $Aplic->getEstado('template_id', null);

if (isset($_REQUEST['painel_id'])) $Aplic->setEstado('painel_id', getParam($_REQUEST,'painel_id', null));
$painel_id = $Aplic->getEstado('painel_id', null);

if (isset($_REQUEST['painel_odometro_id'])) $Aplic->setEstado('painel_odometro_id', getParam($_REQUEST,'painel_odometro_id', null));
$painel_odometro_id = $Aplic->getEstado('painel_odometro_id', null);

if (isset($_REQUEST['painel_composicao_id'])) $Aplic->setEstado('painel_composicao_id', getParam($_REQUEST,'painel_composicao_id', null));
$painel_composicao_id = $Aplic->getEstado('painel_composicao_id', null);

if (isset($_REQUEST['tr_id'])) $Aplic->setEstado('tr_id', getParam($_REQUEST,'tr_id', null));
$tr_id = $Aplic->getEstado('tr_id', null);

if (isset($_REQUEST['me_id'])) $Aplic->setEstado('me_id', getParam($_REQUEST,'me_id', null));
$me_id = $Aplic->getEstado('me_id', null);

$projetos_status=array();
if (!$Aplic->profissional) $projetos_status[0]='&nbsp;';
$projetos_status[-1]='Ativos';
$projetos_status[-2]='Inativos';
$projetos_status+= getSisValor('StatusProjeto');

$projeto_tipos=array();
if(!$Aplic->profissional) $projeto_tipos[-1] = '';
$projeto_tipos += getSisValor('TipoProjeto');

$campos_extras = array();
if($Aplic->profissional){
  $sql->adTabela('campos_customizados_estrutura');
  $sql->adCampo('campo_id, campo_nome, campo_tipo_html, campo_descricao');
  $sql->adOnde("campo_modulo = 'projetos'");
  $sql->adOnde("campo_tipo_html IN ('select', 'textinput', 'textarea', 'checkbox')");
  $campos_extras = $sql->ListaChaveSimples('campo_id');
  $sql->limpar();
  foreach($campos_extras as &$campo){
    $campo_form = 'customizado_'.$campo['campo_nome'];
    if(isset($_REQUEST[$campo_form]))  $Aplic->setEstado($campo_form, getParam($_REQUEST, $campo_form, ''));
    $campo['campo_valor_atual'] = $Aplic->getEstado($campo_form) !== null ? $Aplic->getEstado($campo_form) : '';
    if($campo['campo_tipo_html'] == 'select'){
      $sql->adTabela('campo_customizado_lista');
      $sql->adCampo('campo_customizado_lista_opcao, campo_customizado_lista_valor');
      $sql->adOnde('campo_customizado_lista_campo = '.$campo['campo_id']);
      $res = $sql->listaVetorChave('campo_customizado_lista_opcao','campo_customizado_lista_valor');
      $sql->limpar();
      if(!empty($res)) $campo['lista'] = $res;
      else $campo['lista'] = array();
    }
    }
  }


$estado=array(0 => '&nbsp;');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();

$sql->adTabela('favoritos');
$sql->adCampo('favorito_id, descricao');
$sql->adOnde('projeto=1');
$sql->adOnde('criador_id='.$Aplic->usuario_id);
$vetor_favoritos=$sql->ListaChave();
$sql->limpar();

$favoritos='';
if (count($vetor_favoritos)) {
	if (!$Aplic->profissional) $vetor_favoritos[0]='';
	$favoritos='<tr><td align="right" nowrap="nowrap">'.dica('Favoritos', 'Escolha um grupo de favorit'.$config['genero_projeto'].'s para mostrar '.$config['genero_projeto'].'s '.$config['projeto'].' pertencentes.').'Favoritos:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($vetor_favoritos, 'favorito_id', 'class="texto"'.($Aplic->profissional ? ' multiple' :'').' style="width:200px;"', $favorito_id).'</td></tr>';
	}

$projeto_expandido=getParam($_REQUEST, 'projeto_expandido', 0);

if ($favorito_id) $projeto_expandido=0;

$ativo = intval(!$Aplic->getEstado('ProjIdxTab'));
if (isset($_REQUEST['cia_dept']) && $_REQUEST['cia_dept'])	$Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_dept', null));
else if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
if ($dept_id) $ver_subordinadas = null;

$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

if (isset($_REQUEST['ver_dept_subordinados'])) $Aplic->setEstado('ver_dept_subordinados', getParam($_REQUEST, 'ver_dept_subordinados', null));
$ver_dept_subordinados = ($Aplic->getEstado('ver_dept_subordinados') !== null ? $Aplic->getEstado('ver_dept_subordinados') : (($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) ? $Aplic->usuario_prefs['ver_dept_subordinados'] : 0));
if ($ver_subordinadas) $ver_dept_subordinados=0;

$lista_depts='';
if ($ver_dept_subordinados){
	$vetor_depts=array();
	lista_depts_subordinados($dept_id, $vetor_depts);
	$vetor_depts[]=$dept_id;
	$lista_depts=implode(',',$vetor_depts);
	}

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="cia_dept" value="" />';
echo '<input type="hidden" id="ver_subordinadas" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
echo '<input type="hidden" id="projeto_expandido" name="projeto_expandido" value="'.$projeto_expandido.'" />';
echo '<input type="hidden" id="filtro_area" name="filtro_area" value="'.htmlentities($filtro_area).'" />';
echo '<input type="hidden" name="nao_apenas_superiores" value="'.$nao_apenas_superiores.'" />';
echo '<input type="hidden" name="filtro_criterio" id="filtro_criterio" value="'.$filtro_criterio.'" />';
echo '<input type="hidden" name="filtro_perspectiva" id="filtro_perspectiva" value="'.$filtro_perspectiva.'" />';
echo '<input type="hidden" name="filtro_canvas" id="filtro_canvas" value="'.$filtro_canvas.'" />';
echo '<input type="hidden" name="filtro_tema" id="filtro_tema" value="'.$filtro_tema.'" />';
echo '<input type="hidden" name="filtro_objetivo" id="filtro_objetivo" value="'.$filtro_objetivo.'" />';
echo '<input type="hidden" name="filtro_fator" id="filtro_fator" value="'.$filtro_fator.'" />';
echo '<input type="hidden" name="filtro_estrategia" id="filtro_estrategia" value="'.$filtro_estrategia.'" />';
echo '<input type="hidden" name="filtro_meta" id="filtro_meta" value="'.$filtro_meta.'" />';
echo '<input type="hidden" name="filtro_prioridade" id="filtro_prioridade" value="'.$filtro_prioridade.'" />';
echo '<input type="hidden" name="filtro_acionado" value="1" />';

echo '<input type="hidden" name="pg_perspectiva_id" id="pg_perspectiva_id" value="'.$pg_perspectiva_id.'" />';
echo '<input type="hidden" name="tema_id" id="tema_id" value="'.$tema_id.'" />';
echo '<input type="hidden" name="pg_objetivo_estrategico_id" id="pg_objetivo_estrategico_id" value="'.$pg_objetivo_estrategico_id.'" />';
echo '<input type="hidden" name="pg_fator_critico_id" id="pg_fator_critico_id" value="'.$pg_fator_critico_id.'" />';
echo '<input type="hidden" name="pg_estrategia_id" id="pg_estrategia_id" value="'.$pg_estrategia_id.'" />';
echo '<input type="hidden" name="pg_meta_id" id="pg_meta_id" value="'.$pg_meta_id.'" />';
echo '<input type="hidden" name="pratica_id" id="pratica_id" value="'.$pratica_id.'" />';
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="plano_acao_id" id="plano_acao_id" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="canvas_id" id="canvas_id" value="'.$canvas_id.'" />';
echo '<input type="hidden" name="risco_id" id="risco_id" value="'.$risco_id.'" />';
echo '<input type="hidden" name="risco_resposta_id" id="risco_resposta_id" value="'.$risco_resposta_id.'" />';
echo '<input type="hidden" name="calendario_id" id="calendario_id" value="'.$calendario_id.'" />';
echo '<input type="hidden" name="monitoramento_id" id="monitoramento_id" value="'.$monitoramento_id.'" />';
echo '<input type="hidden" name="ata_id" id="ata_id" value="'.$ata_id.'" />';
echo '<input type="hidden" name="swot_id" id="swot_id" value="'.$swot_id.'" />';
echo '<input type="hidden" name="operativo_id" id="operativo_id" value="'.$operativo_id.'" />';
echo '<input type="hidden" name="instrumento_id" id="instrumento_id" value="'.$instrumento_id.'" />';
echo '<input type="hidden" name="recurso_id" id="recurso_id" value="'.$recurso_id.'" />';
echo '<input type="hidden" name="problema_id" id="problema_id" value="'.$problema_id.'" />';
echo '<input type="hidden" name="demanda_id" id="demanda_id" value="'.$demanda_id.'" />';
echo '<input type="hidden" name="programa_id" id="programa_id" value="'.$programa_id.'" />';
echo '<input type="hidden" name="licao_id" id="licao_id" value="'.$licao_id.'" />';
echo '<input type="hidden" name="evento_id" id="evento_id" value="'.$evento_id.'" />';
echo '<input type="hidden" name="link_id" id="link_id" value="'.$link_id.'" />';
echo '<input type="hidden" name="avaliacao_id" id="avaliacao_id" value="'.$avaliacao_id.'" />';
echo '<input type="hidden" name="tgn_id" id="tgn_id" value="'.$tgn_id.'" />';
echo '<input type="hidden" name="brainstorm_id" id="brainstorm_id" value="'.$brainstorm_id.'" />';
echo '<input type="hidden" name="gut_id" id="gut_id" value="'.$gut_id.'" />';
echo '<input type="hidden" name="causa_efeito_id" id="causa_efeito_id" value="'.$causa_efeito_id.'" />';
echo '<input type="hidden" name="arquivo_id" id="arquivo_id" value="'.$arquivo_id.'" />';
echo '<input type="hidden" name="forum_id" id="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="checklist_id" id="checklist_id" value="'.$checklist_id.'" />';
echo '<input type="hidden" name="agenda_id" id="agenda_id" value="'.$agenda_id.'" />';
echo '<input type="hidden" name="agrupamento_id" id="agrupamento_id" value="'.$agrupamento_id.'" />';
echo '<input type="hidden" name="patrocinador_id" id="patrocinador_id" value="'.$patrocinador_id.'" />';
echo '<input type="hidden" name="template_id" id="template_id" value="'.$template_id.'" />';
echo '<input type="hidden" name="painel_id" id="painel_id" value="'.$painel_id.'" />';
echo '<input type="hidden" name="painel_odometro_id" id="painel_odometro_id" value="'.$painel_odometro_id.'" />';
echo '<input type="hidden" name="painel_composicao_id" id="painel_composicao_id" value="'.$painel_composicao_id.'" />';
echo '<input type="hidden" name="tr_id" id="tr_id" value="'.$tr_id.'" />';
echo '<input type="hidden" name="me_id" id="me_id" value="'.$me_id.'" />';

if($Aplic->profissional){
  foreach($campos_extras as $cmp){
    $nome = 'customizado_'.$cmp['campo_nome'];
    echo '<input type="hidden" name="'.$nome.'" id="'.$nome.'" value="'.$cmp['campo_valor_atual'].'" />';
    }
  }

$ata_ativo=$Aplic->modulo_ativo('atas');
$swot_ativo=$Aplic->modulo_ativo('swot');
$operativo_ativo=$Aplic->modulo_ativo('operativo');
$problema_ativo=$Aplic->modulo_ativo('problema');
$agrupamento_ativo=$Aplic->modulo_ativo('agrupamento');
$patrocinador_ativo=$Aplic->modulo_ativo('patrocinadores');
$tr_ativo=$Aplic->modulo_ativo('tr');

if (!$dialogo && $Aplic->profissional){
	$Aplic->salvarPosicao();

	if ($Aplic->profissional) require_once BASE_DIR.'/modulos/projetos/template_pro.class.php';

	if ($ata_ativo) require_once BASE_DIR.'/modulos/atas/funcoes.php';
	if ($swot_ativo) require_once BASE_DIR.'/modulos/swot/swot.class.php';
	if ($operativo_ativo) require_once BASE_DIR.'/modulos/operativo/funcoes.php';
	if ($problema_ativo) require_once BASE_DIR.'/modulos/problema/funcoes.php';
	if($agrupamento_ativo) require_once BASE_DIR.'/modulos/agrupamento/funcoes.php';
	if($patrocinador_ativo) require_once BASE_DIR.'/modulos/patrocinadores/patrocinadores.class.php';

	$tipos=array(
		''=>'',
		'popProjeto' => ucfirst($config['projeto']),
		'popPerspectiva'=> ucfirst($config['perspectiva']),
		'popTema'=> ucfirst($config['tema']),
		'popObjetivo'=> ucfirst($config['objetivo']),
		'popFator'=> ucfirst($config['fator']),
		'popEstrategia'=> ucfirst($config['iniciativa']),
		'popMeta'=>ucfirst($config['meta']),
		'popAcao'=> ucfirst($config['acao']),
		'popPratica' => ucfirst($config['pratica']),
		'popIndicador' => 'Indicador',
		);
	if ($ata_ativo) $tipos['popAta']='Ata de Reunião';
	if ($swot_ativo) $tipos['popSWOT']='Campo SWOT';
	if ($operativo_ativo) $tipos['popOperativo']='Plano Operativo';
	if ($Aplic->profissional)  {
		$tipos['popCanvas']=ucfirst($config['canvas']);
		$tipos['popRisco']=ucfirst($config['risco']);
		$tipos['popRiscoResposta']=ucfirst($config['risco_resposta']);
		$tipos['popCalendario']='Agenda';
		$tipos['popMonitoramento']='Monitoramento';
		$tipos['popInstrumento']=ucfirst($config['instrumento']);
		$tipos['popRecurso']=ucfirst($config['recurso']);
		if ($problema_ativo) $tipos['popProblema']=ucfirst($config['problema']);
		$tipos['popDemanda']='Demanda';
		$tipos['popPrograma']=ucfirst($config['programa']);
		$tipos['popLicao']=ucfirst($config['licao']);
		$tipos['popEvento']='Evento';
		$tipos['popLink']='Link';
		$tipos['popAvaliacao']='Avaliação';
		$tipos['popTgn']=ucfirst($config['tgn']);
		$tipos['popBrainstorm']='Brainstorm';
		$tipos['popGut']='Matriz G.U.T.';
		$tipos['popCausa_efeito']='Diagrama de Causa-Efeito';
		$tipos['popArquivo']='Arquivo';
		$tipos['popForum']='Fórum';
		$tipos['popChecklist']='Checklist';
		$tipos['popAgenda']='Compromisso';
		if ($agrupamento_ativo) $tipos['popAgrupamento']='Agrupamento';
		if ($patrocinador_ativo) $tipos['popPatrocinador']='Patrocinador';
		$tipos['popTemplate']='Modelo';
		$tipos['popPainel']='Painel de Indicador';
		$tipos['popOdometro']='Odômetro de Indicador';
		$tipos['popComposicaoPaineis']='Composição de Painéis';
		if ($tr_ativo) $tipos['popTR']=ucfirst($config['tr']);
		if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'acesso', null, 'me')) $tipos['popMe']=ucfirst($config['me']);	
		}
	asort($tipos);


	if($plano_acao_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Filtrar as atas de reunião pel'.$config['genero_acao'].' '.$config['acao'].' que estão relacionadas.').ucfirst($config['acao']).':'.dicaF();
		$nome=nome_acao($plano_acao_id);
		}
	elseif($pratica_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Filtrar as atas de reunião pel'.$config['genero_pratica'].' '.$config['pratica'].' que estão relacionadas.').ucfirst($config['pratica']).':'.dicaF();
		$nome=nome_pratica($pratica_id);
		}
	elseif($calendario_id){
		$legenda_filtro=dica('Filtrar pela Agenda', 'Filtrar as atas de reunião pela agenda que estão relacionadas.').'Agenda:'.dicaF();
		$nome=nome_calendario($calendario_id);
		}
	elseif($pratica_indicador_id){
		$legenda_filtro=dica('Filtrar pelo Indicador', 'Filtrar as atas de reunião pelo indicador que estão relacionadas.').'Indicador:'.dicaF();
		$nome=nome_indicador($pratica_indicador_id);
		}
	elseif($pg_objetivo_estrategico_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', 'Filtrar as atas de reunião pel'.$config['genero_objetivo'].' '.$config['objetivo'].' que estão relacionadas.').''.ucfirst($config['objetivo']).':'.dicaF();
		$nome=nome_objetivo($pg_objetivo_estrategico_id);
		}
	elseif($tema_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tema'].' '.ucfirst($config['tema']).'', 'Filtrar as atas de reunião pel'.$config['genero_tema'].' '.$config['tema'].' que estão relacionadas.').ucfirst($config['tema']).':'.dicaF();
		$nome=nome_tema($tema_id);
		}
	elseif($pg_estrategia_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Filtrar as atas de reunião pel'.$config['genero_iniciativa'].' '.$config['iniciativa'].' que estão relacionadas.').ucfirst($config['iniciativa']).':'.dicaF();
		$nome=nome_estrategia($pg_estrategia_id);
		}
	elseif($pg_perspectiva_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Filtrar as atas de reunião pel'.$config['genero_perspectiva'].' '.$config['perspectiva'].' que estão relacionadas.').ucfirst($config['perspectiva']).':'.dicaF();
		$nome=nome_perspectiva($pg_perspectiva_id);
		}
	elseif($canvas_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Filtrar as atas de reunião pel'.$config['genero_canvas'].' '.$config['canvas'].' que estão relacionadas.').ucfirst($config['canvas']).':'.dicaF();
		$nome=nome_canvas($canvas_id);
		}
	elseif($pg_fator_critico_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Filtrar as atas de reunião pel'.$config['genero_fator'].' '.$config['fator'].' que estão relacionadas.').ucfirst($config['fator']).':'.dicaF();
		$nome=nome_fator($pg_fator_critico_id);
		}
	elseif($pg_meta_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Filtrar as atas de reunião pel'.$config['genero_meta'].' '.$config['meta'].' que estão relacionadas.').ucfirst($config['meta']).':'.dicaF();
		$nome=nome_meta($pg_meta_id);
		}
	elseif($risco_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Filtrar as atas de reunião pel'.$config['genero_risco'].' '.$config['risco'].' que estão relacionadas.').ucfirst($config['risco']).':'.dicaF();
		$nome=nome_risco($risco_id);
		}
	elseif($risco_resposta_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Filtrar as atas de reunião pel'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' que estão relacionadas.').ucfirst($config['risco_resposta']).':'.dicaF();
		$nome=nome_risco_resposta($risco_resposta_id);
		}
	elseif($monitoramento_id){
		$legenda_filtro=dica('Filtrar pelo Monitoramento', 'Filtrar as atas de reunião pelo monitoramento que estão relacionadas.').'Monitoramento:'.dicaF();
		$nome=nome_monitoramento($monitoramento_id);
		}
	elseif($ata_id){
		$legenda_filtro=dica('Filtrar pela Ata de Reunião', 'Filtrar as atas de reunião pela ata de reunião a qual estão relacionados.').'Ata:'.dicaF();
		$nome=nome_ata($ata_id);
		}
	elseif($swot_id){
		$legenda_filtro=dica('Filtrar pela Matriz SWOT', 'Filtrar as atas de reunião pela matriz SWOT que estão relacionadas.').'Matriz SWOT:'.dicaF();
		$nome=nome_swot($swot_id);
		}
	elseif($operativo_id){
		$legenda_filtro=dica('Filtrar pelo Plano Operativo', 'Filtrar as atas de reunião pelo plano operativo que estão relacionadas.').'Plano Operativo:'.dicaF();
		$nome=nome_operativo($operativo_id);
		}
	elseif($instrumento_id){
		$legenda_filtro=dica('Filtrar pelo Instrumento Jurídico', 'Filtrar as atas de reunião pelo instrumento jurídico que estão relacionadas.').'Instrumento Jurídico:'.dicaF();
		$nome=nome_instrumento($instrumento_id);
		}
	elseif($recurso_id){
		$legenda_filtro=dica('Filtrar pelo Recurso', 'Filtrar as atas de reunião pelo recurso que estão relacionadas.').'Recurso:'.dicaF();
		$nome=nome_recurso($recurso_id);
		}
	elseif($problema_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Filtrar as atas de reunião pel'.$config['genero_problema'].' '.$config['problema'].' que estão relacionadas.').ucfirst($config['problema']).':'.dicaF();
		$nome=nome_problema($problema_id);
		}
	elseif($demanda_id){
		$legenda_filtro=dica('Filtrar pela Demanda', 'Filtrar as atas de reunião pela demanda que estão relacionadas.').'Demanda:'.dicaF();
		$nome=nome_demanda($demanda_id);
		}
	elseif($programa_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Filtrar as atas de reunião pel'.$config['genero_programa'].' '.$config['programa'].' que estão relacionadas.').ucfirst($config['programa']).':'.dicaF();
		$nome=nome_programa($programa_id);
		}
	elseif($licao_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Filtrar as atas de reunião pel'.$config['genero_licao'].' '.$config['licao'].' que estão relacionadas.').ucfirst($config['licao']).':'.dicaF();
		$nome=nome_licao($licao_id);
		}
	elseif($evento_id){
		$legenda_filtro=dica('Filtrar pelo Evento', 'Filtrar as atas de reunião pelo evento que estão relacionadas.').'Evento:'.dicaF();
		$nome=nome_evento($evento_id);
		}
	elseif($link_id){
		$legenda_filtro=dica('Filtrar pelo Link', 'Filtrar as atas de reunião pelo link que estão relacionadas.').'Link:'.dicaF();
		$nome=nome_link($link_id);
		}
	elseif($avaliacao_id){
		$legenda_filtro=dica('Filtrar pela Avaliação', 'Filtrar as atas de reunião pela avaliação que estão relacionadas.').'Avaliação:'.dicaF();
		$nome=nome_avaliacao($avaliacao_id);
		}
	elseif($tgn_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Filtrar as atas de reunião pel'.$config['genero_tgn'].' '.$config['tgn'].' que estão relacionadas.').ucfirst($config['tgn']).':'.dicaF();
		$nome=nome_tgn($tgn_id);
		}
	elseif($brainstorm_id){
		$legenda_filtro=dica('Filtrar pelo Brainstorm', 'Filtrar as atas de reunião pelo brainstorm que estão relacionadas.').'Brainstorm:'.dicaF();
		$nome=nome_brainstorm($brainstorm_id);
		}
	elseif($gut_id){
		$legenda_filtro=dica('Filtrar pela Matriz GUT', 'Filtrar as atas de reunião pela matriz GUT que estão relacionadas.').'Matriz GUT:'.dicaF();
		$nome=nome_gut($gut_id);
		}
	elseif($causa_efeito_id){
		$legenda_filtro=dica('Filtrar pelo Diagrama de Causa-Efeito', 'Filtrar as atas de reunião pelo diagrama de causa-efeito que estão relacionadas.').'Diagrama de Causa-Efeito:'.dicaF();
		$nome=nome_causa_efeito($causa_efeito_id);
		}
	elseif($arquivo_id){
		$legenda_filtro=dica('Filtrar pelo Arquivo', 'Filtrar as atas de reunião pelo arquivo que estão relacionadas.').'Arquivo:'.dicaF();
		$nome=nome_arquivo($arquivo_id);
		}
	elseif($forum_id){
		$legenda_filtro=dica('Filtrar pelo Fórum', 'Filtrar as atas de reunião pelo fórum que estão relacionadas.').'Fórum:'.dicaF();
		$nome=nome_forum($forum_id);
		}
	elseif($checklist_id){
		$legenda_filtro=dica('Filtrar pelo Checklist', 'Filtrar as atas de reunião pelo checklist que estão relacionadas.').'Checklist:'.dicaF();
		$nome=nome_checklist($checklist_id);
		}
	elseif($agenda_id){
		$legenda_filtro=dica('Filtrar pelo Compromisso', 'Filtrar as atas de reunião pelo compromisso que estão relacionadas.').'Compromisso:'.dicaF();
		$nome=nome_compromisso($agenda_id);
		}
	elseif($agrupamento_id){
		$legenda_filtro=dica('Filtrar pelo Agrupamento', 'Filtrar as atas de reunião pelo agrupamento que estão relacionadas.').'Agrupamento:'.dicaF();
		$nome=nome_agrupamento($agrupamento_id);
		}
	elseif($patrocinador_id){
		$legenda_filtro=dica('Filtrar pelo Patrocinador', 'Filtrar as atas de reunião pelo patrocinador que estão relacionadas.').'Patrocinador:'.dicaF();
		$nome=nome_patrocinador($patrocinador_id);
		}
	elseif($template_id){
		$legenda_filtro=dica('Filtrar pelo Modelo', 'Filtrar as atas de reunião pelo modelo que estão relacionadas.').'Modelo:'.dicaF();
		$nome=nome_template($template_id);
		}
	elseif($painel_id){
		$legenda_filtro=dica('Filtrar pelo Painel', 'Filtrar pelo painel de indicador relacionado.').'Painel:'.dicaF();
		$nome=nome_painel($painel_id);
		}
	elseif($painel_odometro_id){
		$legenda_filtro=dica('Filtrar pelo Odômetro', 'Filtrar pelo odômetro de indicador relacionado.').'Odômetro:'.dicaF();
		$nome=nome_painel_odometro($painel_odometro_id);
		}
	elseif($painel_composicao_id){
		$legenda_filtro=dica('Filtrar pela Composição de Painéis', 'Filtrar pela composição de painéis relacionada.').'Composição de Painéis:'.dicaF();
		$nome=nome_painel_composicao($painel_composicao_id);
		}
	elseif($tr_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Filtrar pel'.$config['genero_tr'].' '.$config['tr'].'relacionad'.$config['genero_tr'].'.').ucfirst($config['tr']).':'.dicaF();
		$nome=nome_tr($tr_id);
		}
	elseif($me_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_me'].' '.ucfirst($config['me']), 'Filtrar pel'.$config['genero_me'].' '.$config['me'].' que estão relacionados.').ucfirst($config['me']).':'.dicaF();
		$nome=nome_me($me_id);
		}		
	else{
		$nome='';
		$legenda_filtro=dica('Filtrar', 'Selecione um campo para filtrar os ata.').'Filtro:'.dicaF();
		}

	$popFiltro='<tr><td align="right" nowrap="nowrap">'.dica('Relacionad'.$config['genero_projeto'],'A qual parte do sistema '.$config['genero_projeto'].'s '.$config['projetos'].' estão relacionad'.$config['genero_projeto'].'s.').'Relacionad'.$config['genero_projeto'].':'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:200px;" class="texto" onchange="popRelacao(this.value)"').'</td></tr>';
	$icone_limpar='<td><a href="javascript:void(0);" onclick="limpar_tudo(); env.submit();">'.imagem('icones/limpar_p.gif','Cancelar Filtro', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para cancelar o filtro aplicado.').'</a></td>';
	$filtros=($nome ? '<tr><td nowrap="nowrap" align="right">'.$legenda_filtro.'</td><td><input type="text" id="nome" name="nome" value="'.$nome.'" style="width:200px;" class="texto" READONLY /></td>'.$icone_limpar.'</tr>' : '');

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/projeto_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table><tr cellspacing=0 cellpadding=0>';
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['projetos']), 'projeto.png', $m, $m.'.'.$a);
	$procurar_estado='<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:200px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
	$procurar_municipio='<tr><td align="right">'.dica('Município', 'Selecione o município d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto"'.($Aplic->profissional ? ' multiple' :'').' style="width:200px;"', '', $municipio_id, true, false).'</div></td></tr>';
	$procurar_status='<tr><td nowrap="nowrap" align="right">'.dica('Status d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Filtre '.$config['genero_projeto'].'s '.$config['projetos'].' pelo status d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').'Status:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($projetos_status, 'projetostatus', 'size="1" style="width:200px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $projetostatus) .'</td></tr>';
	$procura_categoria='<tr><td nowrap="nowrap" align="right">'.dica('Categoria de '.ucfirst($config['projeto']), 'Filtre '.$config['genero_projeto'].'s '.$config['projetos'].' pela categoria  d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').'Categoria:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($projeto_tipos, 'projeto_tipo', 'size="1" style="width:200px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $projeto_tipo) .'</td></tr>';
	$procura_pesquisa='<tr><td nowrap="nowrap" align="right">'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" class="texto" style="width:200px;" id="projtextobusca" name="projtextobusca" onChange="document.env.submit();" value='."'$pesquisar_texto'".'/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&projtextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:200px;" onchange="javascript:mudar_om();"').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.dept_id.value=\'\';  document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:200px;" class="texto" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=1; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');
	$procurar_responsavel='<tr><td align=right>'.dica(ucfirst($config['gerente']), 'Filtrar pelo '.$config['gerente'].' escolhido na caixa de seleção à direita.').ucfirst($config['gerente']).':'.dicaF().'</td><td><input type="hidden" id="responsavel" name="responsavel" value="'.$responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($responsavel).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$procurar_supervisor='<tr><td align=right>'.dica(ucfirst($config['supervisor']), 'Filtrar pelo '.$config['supervisor'].' escolhido na caixa de seleção à direita.').ucfirst($config['supervisor']).':'.dicaF().'</td><td><input type="hidden" id="supervisor" name="supervisor" value="'.$supervisor.'" /><input type="text" id="nome_supervisor" name="nome_supervisor" value="'.nome_usuario($supervisor).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSupervisor();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$procurar_autoridade='<tr><td align=right>'.dica(ucfirst($config['autoridade']), 'Filtrar pelo '.$config['autoridade'].' escolhido na caixa de seleção à direita.').ucfirst($config['autoridade']).':'.dicaF().'</td><td><input type="hidden" id="autoridade" name="autoridade" value="'.$autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_usuario($autoridade).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$procurar_cliente='<tr><td align=right>'.dica(ucfirst($config['cliente']), 'Filtrar pelo '.$config['cliente'].' escolhido na caixa de seleção à direita.').ucfirst($config['cliente']).':'.dicaF().'</td><td><input type="hidden" id="cliente" name="cliente" value="'.$cliente.'" /><input type="text" id="nome_cliente" name="nome_cliente" value="'.nome_usuario($cliente).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCliente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$botao_projeto=(!$config['termo_abertura_obrigatorio'] && $podeAdicionar ? '<tr><td nowrap="nowrap"><a href="javascript: void(0)" onclick="env.a.value=\'editar\'; env.submit();">'.($config['legenda_icone'] ? botao('novo '.$config['projeto'], 'Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique neste botão para criar '.($config['genero_projeto']=='o' ? 'um novo' : 'uma nova').' '.$config['projeto'].'.', '','','','',0) : imagem('icones/projeto_criar.gif', 'Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('icones/projeto_criar.gif').' para criar '.($config['genero_projeto']=='o' ? 'um novo' : 'uma nova').' '.$config['projeto'].'.')).'</a></td></tr>' : '');
	$botao_favorito='<tr><td><a href="javascript: void(0)" onclick="url_passar(0, \'m=publico&a=favoritos&projeto=1\');">'.imagem('icones/favorito_p.png', 'Criar Grupo de Favorit'.$config['genero_projeto'].'s', 'Clique neste ícone '.imagem('icones/favorito_p.png').' para criar ou editar um grupo de '.$config['projetos'].' favorit'.$config['genero_projeto'].'s.').'</a></td></tr>';
	$procura_opcao=($Aplic->profissional ? '<tr><td nowrap="nowrap" align="right">'.dica('Opção de Filtro', 'Filtre '.$config['genero_projeto'].'s '.$config['projetos'].' pela opção escolhida.').'Opção:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($filtro_opcoes, 'filtro_opcao', 'size="1" style="width:200px;" class="texto"', $filtro_opcao) .'</td></tr>' : '');
	$procura_setor='';
	$procura_segmento='';
	$procura_intervencao='';
	$procura_tipo_intervencao='';
	if ($exibir['setor']){
		$setor = array(0 => '&nbsp;') + getSisValor('Setor');
		$segmento=array(0 => '&nbsp;');
		if ($projeto_setor){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Segmento"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_setor.'"');
			$sql->adOrdem('sisvalor_valor');
			$segmento+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}
		$intervencao=array(0 => '&nbsp;');
		if ($projeto_segmento){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Intervencao"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_segmento.'"');
			$sql->adOrdem('sisvalor_valor');
			$intervencao+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}
		$tipo_intervencao=array(0 => '&nbsp;');
		if ($projeto_intervencao){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_intervencao.'"');
			$sql->adOrdem('sisvalor_valor');
			$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}
		$procura_setor='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'projeto_setor', 'style="width:200px;" class="texto" onchange="mudar_segmento();"', $projeto_setor).'</td></tr>';
		$procura_segmento='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'projeto_segmento', 'style="width:200px;" class="texto" onchange="mudar_intervencao();"', $projeto_segmento).'</div></td></tr>';
	 	$procura_intervencao='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'projeto_intervencao', 'style="width:200px;" class="texto" onchange="mudar_tipo_intervencao();"', $projeto_intervencao).'</div></td></tr>';
		$procura_tipo_intervencao='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'projeto_tipo_intervencao', 'style="width:200px;" class="texto"', $projeto_tipo_intervencao).'</div></td></tr>';
		}
	$saida.='<td style="vertical-align:top;"><table cellspacing=0 cellpadding=0 >'.$procura_setor.$procura_segmento.$procura_intervencao.$procura_tipo_intervencao.$procurar_estado.$procurar_municipio.$procurar_status.$popFiltro.$filtros.'</table></td>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';
	$saida.='<td style="vertical-align:top;"><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_responsavel.$procurar_supervisor.$procurar_autoridade.$procurar_cliente.$procura_categoria.$procura_pesquisa.$procura_opcao.$favoritos.'</table></td>';
	if (!$projeto_expandido){
		if ($nao_apenas_superiores) $botao_superiores='<tr><td><a href="javascript: void(0);" onclick ="env.nao_apenas_superiores.value=0; env.submit();">'.imagem('icones/projeto_superior.gif','Ver Projetos Superiores', 'Clique neste ícone '.imagem('icones/projeto_superior.gif').' para exibir apenas os projetos superiores.').'</a></td></tr>';
		else $botao_superiores='<tr><td><a href="javascript: void(0);" onclick ="env.nao_apenas_superiores.value=1; env.submit();">'.imagem('icones/projeto_superior_cancela.gif','Ver Todos os Projetos', 'Clique neste ícone '.imagem('icones/projeto_superior_cancela.gif').' para exibir todos os projetos em vez de apenas os projetos superiores.').'</a></td></tr>';
		}
	else $botao_superiores='';
	$botao_filtrar='<tr><td><a href="javascript:void(0);" onclick="document.env.submit();">'.($config['legenda_icone'] ? botao('filtrar', 'Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelos parâmetros selecionados à esquerda.', '','','','',0) : imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelos parâmetros selecionados à esquerda.')).'</a></td></tr>';
	$botao_imprimir='<tr><td><a href="javascript: void(0)" onclick="url_passar(1, \'m=projetos&a=index&dialogo=1&tab='.$tab.'\');">'.imagem('icones/imprimir_p.png', 'Imprimir '.ucfirst($config['projetos']), 'Clique neste ícone '.imagem('icones/imprimir_p.png').' para imprimir a lista de '.$config['projetos'].'.').'</a></td></tr>';
	$botao_pdf='<tr><td><a href="javascript: void(0)" onclick="url_passar(1, \'m=projetos&a=index&dialogo=1&sem_cabecalho=1&pdf=1&page_orientation=Landscape&tab='.$tab.'\');">'.imagem('icones/pdf_2.png', 'Imprimir '.ucfirst($config['projetos']), 'Clique neste ícone '.imagem('icones/pdf_2.png').' para imprimir a lista de '.$config['projetos'].'.').'</a></td></tr>';
	$botao_excel=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="exportar_excel();">'.imagem('icones/excel_p.gif', 'Exportar '.ucfirst($config['projetos']).' para Excel' , 'Clique neste ícone '.imagem('icones/excel_p.gif').' para exportar a lista de '.$config['projetos'].' para o formato excel.').'</a>'.dicaF().'</td></tr>' : '');

	$botao_mysql=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="exportar_mysql();">'.imagem('icones/export_bd_p.png', 'Exportar '.ucfirst($config['projetos']).' para o MySQL' , 'Clique neste ícone '.imagem('icones/export_bd_p.png').' para exportar a lista de '.$config['projetos'].' para a tabela projeto_resumo do MySQL.').'</a>'.dicaF().'</td></tr>' : '');

	$botao_pizza_geral=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="dashboard_geral();">'.imagem('icones/estatistica_p.png', 'Painel geral d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']), 'Clique neste ícone '.imagem('icones/estatistica_p.png').' para exibir o painel geral d'.$config['genero_projeto'].'s  '.$config['projetos'].'.').'</a>'.dicaF().'</td></tr>' : '');
	if($filtro_prioridade) $botao_prioridade=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="priorizacao(0);">'.imagem('icones/priorizacao_nao_p.png', 'Cancelar a Priorização de '.ucfirst($config['projetos']) , 'Clique neste ícone '.imagem('icones/priorizacao_nao_p.png').' para cancelar a priorização da lista de '.$config['projetos'].'.').'</a>'.dicaF().'</td></tr>' : '');
	else $botao_prioridade=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="priorizacao(1);">'.imagem('icones/priorizacao_p.png', 'Priorização de '.ucfirst($config['projetos']) , 'Clique neste ícone '.imagem('icones/priorizacao_p.png').' para priorizar a lista de '.$config['projetos'].'.').'</a>'.dicaF().'</td></tr>' : '');
	$botao_graficos=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="parent.gpwebApp.graficosProjetos(\''.($tab==2 ? ucfirst($config['portfolio']) : ucfirst($config['projeto'])).'\');">'.imagem('icones/grafico_p.png', 'Mostrar a interface de gráficos' , 'Clique neste ícone '.imagem('grafico_p.png').' para a janela de gráficos customizados.').'</a>'.dicaF().'</td></tr>' : '');
	$botao_campos=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="popCamposExibir();">'.imagem('icones/campos_p.gif', 'Campos' , 'Clique neste ícone '.imagem('campos_p.gif').' para escolha quais campos d'.$config['genero_projeto'].' '.$config['projeto'].' deseja exibir.').'</a>'.dicaF().'</td></tr>' : '');
	if($filtro_area) $botao_area = ($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="selecionarArea();">'.imagem('icones/gmapsx_p.png', 'Áreas' , 'Clique neste ícone '.imagem('icones/gmapsx_p.png').' para remover o filtro por área.').'</a>'.dicaF().'</td></tr>' : '');
	else $botao_area=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="popSelecionarArea();">'.imagem('icones/gmaps_p.png', 'Áreas' , 'Clique neste ícone '.imagem('icones/gmaps_p.png').' para selecionar a área a ser filtrada.').'</a>'.dicaF().'</td></tr>' : '');
  $botao_custom_field=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="javascript:popFiltroCamposCustomizados();">'.imagem('icones/custom_field_search.png', 'Campo Customizado', 'Clique neste ícone '.imagem('icones/custom_field_search.png').' para filtrar '.$config['projetos'].' utilizando os campos customizados.').'</a></td></tr>' : '<tr><td></td></tr>');
	if ($Aplic->profissional){
		$botao_gestao=($filtro_criterio || $filtro_perspectiva || $filtro_canvas || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta	? '<tr><td><a href="javascript: void(0)" onclick="popFiltroGestao();">'.imagem('icones/ferramentas_nao_p.png', 'Mudar Filtro de Gestão' , 'Clique neste ícone '.imagem('ferramentas_nao_p.png').' para mudar o filtros de gestão.').'</a>'.dicaF().'</td></tr>' : '<tr><td><a href="javascript: void(0)" onclick="popFiltroGestao();">'.imagem('icones/ferramentas_p.png', 'Mostrar Filtros de Gestão' , 'Clique neste ícone '.imagem('ferramentas_p.png').' para a janela de filtros de gestão.').'</a>'.dicaF().'</td></tr>');
		}
	else $botao_gestao='';
	$saida.='<td style="vertical-align:top;"><table cellspacing=0 cellpadding=0><tr><td valign=top><table cellspacing=3 cellpadding=0>'.$botao_filtrar.$botao_projeto.$botao_superiores.$botao_favorito.$botao_gestao.$botao_imprimir.$botao_custom_field.'</table></td><td><table cellspacing=3 cellpadding=0>'.$botao_area.$botao_excel.$botao_mysql.$botao_pizza_geral.$botao_graficos.$botao_prioridade.$botao_pdf.$botao_campos.$vazio.'</table></td><td></td></tr></table></td>';
	$saida.='</tr></table></div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();

	}

elseif (!$dialogo){
	$Aplic->salvarPosicao();
	
	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/projeto_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table><tr cellspacing=0 cellpadding=0>';

	
	
	
	
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['projetos']), 'projeto.png', $m, $m.'.'.$a);
	$procurar_estado='<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:200px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
	$procurar_municipio='<tr><td align="right">'.dica('Município', 'Selecione o município d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto" style="width:200px;"', '', $municipio_id, true, false).'</div></td></tr>';
	$procurar_status='<tr><td nowrap="nowrap" align="right">'.dica('Status d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Filtre '.$config['genero_projeto'].'s '.$config['projetos'].' pelo status d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').'Status:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($projetos_status, 'projetostatus', 'size="1" style="width:200px;" class="texto"', $projetostatus) .'</td></tr>';
	$procura_categoria='<tr><td nowrap="nowrap" align="right">'.dica('Categoria de '.ucfirst($config['projeto']), 'Filtre '.$config['genero_projeto'].'s '.$config['projetos'].' pela categoria  d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').'Categoria:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($projeto_tipos, 'projeto_tipo', 'size="1" style="width:200px;" class="texto"', $projeto_tipo) .'</td></tr>';
	$procura_pesquisa='<tr><td nowrap="nowrap" align="right">'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" class="texto" style="width:200px;" id="projtextobusca" name="projtextobusca" onChange="document.env.submit();" value='."'$pesquisar_texto'".'/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&projtextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';

	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:200px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.dept_id.value=\'\';  document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:200px;" class="texto" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=1; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');


	$procurar_responsavel='<tr><td align=right>'.dica(ucfirst($config['gerente']), 'Filtrar pelo '.$config['gerente'].' escolhido na caixa de seleção à direita.').ucfirst($config['gerente']).':'.dicaF().'</td><td><input type="hidden" id="responsavel" name="responsavel" value="'.$responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($responsavel).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$procurar_supervisor='<tr><td align=right>'.dica(ucfirst($config['supervisor']), 'Filtrar pelo '.$config['supervisor'].' escolhido na caixa de seleção à direita.').ucfirst($config['supervisor']).':'.dicaF().'</td><td><input type="hidden" id="supervisor" name="supervisor" value="'.$supervisor.'" /><input type="text" id="nome_supervisor" name="nome_supervisor" value="'.nome_usuario($supervisor).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSupervisor();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$procurar_autoridade='<tr><td align=right>'.dica(ucfirst($config['autoridade']), 'Filtrar pelo '.$config['autoridade'].' escolhido na caixa de seleção à direita.').ucfirst($config['autoridade']).':'.dicaF().'</td><td><input type="hidden" id="autoridade" name="autoridade" value="'.$autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_usuario($autoridade).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

	
	$botao_projeto=(!$config['termo_abertura_obrigatorio'] && $podeAdicionar ? '<tr><td nowrap="nowrap"><a href="javascript: void(0)" onclick="env.a.value=\'editar\'; env.submit();">'.($config['legenda_icone'] ? botao('novo '.$config['projeto'], 'Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('icones/projeto_criar.gif').' para criar um novo '.$config['projetos'].'.', '','','','',0) : imagem('icones/projeto_criar.gif', 'Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('icones/projeto_criar.gif').' para criar um novo '.$config['projetos'].'.')).'</a></td></tr>' : '');
	
	
	$botao_favorito='<tr><td><a href="javascript: void(0)" onclick="url_passar(0, \'m=publico&a=favoritos&projeto=1\');">'.imagem('icones/favorito_p.png', 'Criar Grupo de Favorit'.$config['genero_projeto'].'s', 'Clique neste ícone '.imagem('icones/favorito_p.png').' para criar ou editar um grupo de '.$config['projetos'].' favorit'.$config['genero_projeto'].'s.').'</a></td></tr>';
	$procura_opcao='';
	$procura_setor='';
	$procura_segmento='';
	$procura_intervencao='';
	$procura_tipo_intervencao='';
	if ($exibir['setor']){
		$setor = array(0 => '&nbsp;') + getSisValor('Setor');
		$segmento=array(0 => '&nbsp;');
		if ($projeto_setor){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Segmento"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_setor.'"');
			$sql->adOrdem('sisvalor_valor');
			$segmento+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}
		$intervencao=array(0 => '&nbsp;');
		if ($projeto_segmento){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Intervencao"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_segmento.'"');
			$sql->adOrdem('sisvalor_valor');
			$intervencao+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}
		$tipo_intervencao=array(0 => '&nbsp;');
		if ($projeto_intervencao){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_intervencao.'"');
			$sql->adOrdem('sisvalor_valor');
			$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}
		$procura_setor='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'projeto_setor', 'style="width:200px;" class="texto" onchange="mudar_segmento();"', $projeto_setor).'</td></tr>';
		$procura_segmento='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'projeto_segmento', 'style="width:200px;" class="texto" onchange="mudar_intervencao();"', $projeto_segmento).'</div></td></tr>';
	 	$procura_intervencao='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'projeto_intervencao', 'style="width:200px;" class="texto" onchange="mudar_tipo_intervencao();"', $projeto_intervencao).'</div></td></tr>';
		$procura_tipo_intervencao='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'projeto_tipo_intervencao', 'style="width:200px;" class="texto"', $projeto_tipo_intervencao).'</div></td></tr>';
		}
	//$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0 >'.$procura_setor.$procura_segmento.$procura_intervencao.$procura_tipo_intervencao.$procurar_estado.$procurar_municipio.$procurar_status.'</table>',' style="vertical-align:top;"');
	$saida.='<td style="vertical-align:top;"><table cellspacing=0 cellpadding=0 >'.$procura_setor.$procura_segmento.$procura_intervencao.$procura_tipo_intervencao.$procurar_estado.$procurar_municipio.$procurar_status.'</table></td>';
	
	
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';
	//$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_responsavel.$procurar_supervisor.$procurar_autoridade.$procura_categoria.$procura_pesquisa.$procura_opcao.$favoritos.'</table>',' style="vertical-align:top;"');
	$saida.='<td style="vertical-align:top;"><table cellspacing=0 cellpadding=0 >'.$procurar_om.$procurar_responsavel.$procurar_supervisor.$procurar_autoridade.$procura_categoria.$procura_pesquisa.$procura_opcao.$favoritos.'</table></td>';

	if (!$projeto_expandido){
		if ($nao_apenas_superiores) $botao_superiores='<tr><td><a href="javascript: void(0);" onclick ="env.nao_apenas_superiores.value=0; env.submit();">'.imagem('icones/projeto_superior.gif','Ver Projetos Superiores', 'Clique neste ícone '.imagem('icones/projeto_superior.gif').' para exibir apenas os projetos superiores.').'</a></td></tr>';
		else $botao_superiores='<tr><td><a href="javascript: void(0);" onclick ="env.nao_apenas_superiores.value=1; env.submit();">'.imagem('icones/projeto_superior_cancela.gif','Ver Todos os Projetos', 'Clique neste ícone '.imagem('icones/projeto_superior_cancela.gif').' para exibir todos os projetos em vez de apenas os projetos superiores.').'</a></td></tr>';
		}
	else $botao_superiores='';
	$botao_filtrar='<tr><td><a href="javascript:void(0);" onclick="document.env.submit();">'.($config['legenda_icone'] ? botao('filtrar', 'Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelos parâmetros selecionados à esquerda.', '','','','',0) : imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelos parâmetros selecionados à esquerda.')).'</a></td></tr>';
	$botao_imprimir='<tr><td><a href="javascript: void(0)" onclick="url_passar(1, \'m=projetos&a=index&dialogo=1&tab='.$tab.'\');">'.imagem('icones/imprimir_p.png', 'Imprimir '.ucfirst($config['projetos']), 'Clique neste ícone '.imagem('icones/imprimir_p.png').' para imprimir a lista de '.$config['projetos'].'.').'</a></td></tr>';
	$botao_pdf='<tr><td><a href="javascript: void(0)" onclick="url_passar(1, \'m=projetos&a=index&dialogo=1&sem_cabecalho=1&pdf=1&page_orientation=Landscape&tab='.$tab.'\');">'.imagem('icones/pdf_2.png', 'Imprimir '.ucfirst($config['projetos']), 'Clique neste ícone '.imagem('icones/pdf_2.png').' para imprimir a lista de '.$config['projetos'].'.').'</a></td></tr>';
	$botao_gestao='';
	//$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0><tr><td><table cellspacing=3 cellpadding=0>'.$botao_projeto.$botao_filtrar.$botao_superiores.$botao_favorito.$botao_gestao.$botao_imprimir.$vazio.'</table></td><td><table cellspacing=3 cellpadding=0>'.$botao_area.$botao_graficos.$botao_prioridade.$botao_pdf.$botao_campos.'</table></td><td></td></tr></table>',' style="vertical-align:top;"');
	$saida.='<td style="vertical-align:top;"><table cellspacing=0 cellpadding=0 >'.$botao_projeto.$botao_imprimir.$botao_pdf.'</table></td>';
	
	$saida.='</tr></table></div></div>';
	$botoesTitulo->adicionaCelula($saida);
	
	$botoesTitulo->mostrar();
	
	
	
	
	
	
	}

echo '</form>';


if ($Aplic->profissional){
	if (is_array($cia_id)) $cia_id=implode(',', $cia_id);
	if (is_array($dept_id)) $dept_id=implode(',', $dept_id);
	if (is_array($projeto_tipo)) $projeto_tipo=implode(',', $projeto_tipo);
	if (is_array($projeto_setor)) $projeto_setor=implode(',', $projeto_setor);
	if (is_array($projeto_segmento)) $projeto_segmento=implode(',', $projeto_segmento);
	if (is_array($projeto_intervencao)) $projeto_intervencao=implode(',', $projeto_intervencao);
	if (is_array($projeto_tipo_intervencao)) $projeto_tipo_intervencao=implode(',', $projeto_tipo_intervencao);
	if (is_array($estado_sigla)) $estado_sigla=implode(',', $estado_sigla);
	if (is_array($municipio_id)) $municipio_id=implode(',', $municipio_id);
	if (is_array($favorito_id)) $favorito_id=implode(',', $favorito_id);
	if (is_array($projetostatus)) $projetostatus=implode(',', $projetostatus);
	if (is_array($favorito_id)) $favorito_id=implode(',', $favorito_id);
	if (is_array($filtro_criterio)) $filtro_criterio=implode(',', $filtro_criterio);
	if (is_array($filtro_objetivo)) $filtro_objetivo=implode(',', $filtro_objetivo);
	if (is_array($filtro_tema)) $filtro_tema=implode(',', $filtro_tema);
	if (is_array($filtro_perspectiva)) $filtro_perspectiva=implode(',', $filtro_perspectiva);
	if (is_array($filtro_canvas)) $filtro_canvas=implode(',', $filtro_canvas);
	if (is_array($filtro_estrategia)) $filtro_estrategia=implode(',', $filtro_estrategia);
	if (is_array($filtro_fator)) $filtro_fator=implode(',', $filtro_fator);
	if (is_array($filtro_meta)) $filtro_meta=implode(',', $filtro_meta);
	}
$config['mostrar_total']=true;
if (!$dialogo){
	$caixaTab = new CTabBox('m=projetos', '', $tab);

	$xpg_totalregistros_projetos = null;
	$xpg_totalregistros_recebidos = null;
	$xpg_totalregistros_portfolios = null;
	$xpg_totalregistros_inativos = null;
	if ($config['mostrar_total']){
		$filtrosBuilder = new FiltrosProjetoBuilder();
		$filtrosBuilder->setUsuarioId($responsavel)
			->setSupervisor($supervisor)
			->setAutoridade($autoridade)
			->setCliente($cliente)
			->setCiaId($cia_id)
			->setOrdenarPor($ordenarPor)
			->setOrdemDir($ordemDir)
			->setProjetoTipo($projeto_tipo)
			->setProjetoSetor($projeto_setor)
			->setProjetoSegmento($projeto_segmento)
			->setProjetoIntervencao($projeto_intervencao)
			->setProjetoTipoIntervencao($projeto_tipo_intervencao)
			->setEstadoSigla($estado_sigla)
			->setMunicipioId($municipio_id)
			->setPesquisarTexto($pesquisar_texto)
			->setMostrarProjRespPertenceDept(false)
			->setRecebido(false)
			->setDeptId($lista_depts ? $lista_depts : $dept_id)
			->setFavoritoId($favorito_id)
			->setListaCias($lista_cias)
			->setProjetoStatus($projetostatus)
			->setPontoInicio(null)
			->setProjetoExpandido($projeto_expandido)
			->setNaoApenasSuperiores($nao_apenas_superiores)
			->setExibir($exibir)
			->setPortfolio(null)
			->setTemplate(false)
			->setPortfolioPai(null)
			->setLimite(false)
			->setDataInicio(null)
			->setDataTermino(null)
			->setFiltroArea($filtro_area)
			->setFiltroCriterio($filtro_criterio)
			->setFiltroOpcao($filtro_opcao)
			->setFiltroPrioridade($filtro_prioridade)
			->setFiltroPerspectiva($filtro_perspectiva)
			->setFiltroTema($filtro_tema)
			->setFiltroObjetivo($filtro_objetivo)
			->setFiltroFator($filtro_fator)
			->setFiltroEstrategia($filtro_estrategia)
			->setFiltroMeta($filtro_meta)
			->setFiltroCanvas($filtro_canvas)
			->setFiltroExtra($campos_extras)
			->setPgPerspectivaId($pg_perspectiva_id)
			->setTemaId($tema_id)
			->setPgObjetivoEstrategicoId($pg_objetivo_estrategico_id)
			->setPgFatorCriticoId($pg_fator_critico_id)
			->setPgEstrategiaId($pg_estrategia_id)
			->setPgMetaId($pg_meta_id)
			->setPraticaId($pratica_id)
			->setPraticaIndicadorId($pratica_indicador_id)
			->setPlanoAcaoId($plano_acao_id)
			->setCanvasId($canvas_id)
			->setRiscoId($risco_id)
			->setRiscoRespostaId($risco_resposta_id)
			->setCalendarioId($calendario_id)
			->setMonitoramentoId($monitoramento_id)
			->setAtaId($ata_id)
			->setSwotId($swot_id)
			->setOperativoId($operativo_id)
			->setInstrumentoId($instrumento_id)
			->setRecursoId($recurso_id)
			->setProblemaId($problema_id)
			->setDemandaId($demanda_id)
			->setProgramaId($programa_id)
			->setLicaoId($licao_id)
			->setEventoId($evento_id)
			->setLinkId($link_id)
			->setAvaliacaoId($avaliacao_id)
			->setTgnId($tgn_id)
			->setBrainstormId($brainstorm_id)
			->setGutId($gut_id)
			->setCausaEfeitoId($causa_efeito_id)
			->setArquivoId($arquivo_id)
			->setForumId($forum_id)
			->setChecklistId($checklist_id)
			->setAgendaId($agenda_id)
			->setAgrupamentoId($agrupamento_id)
			->setPatrocinadorId($patrocinador_id)
			->setTemplateId($template_id)
			->setPainelId($painel_id)
			->setPainelOdometroId($painel_odometro_id)
			->setPainelComposicaoId($painel_composicao_id)
			->setTrId($tr_id)
			->setMeId($me_id);

		$xpg_totalregistros_projetos = (int)projetos_quantidade( $filtrosBuilder );
		$total1 = ' ('. $xpg_totalregistros_projetos . ')';

		$filtrosBuilder->setRecebido(true);
		$xpg_totalregistros_recebidos = (int)projetos_quantidade( $filtrosBuilder );
		$total2 = ' (' . $xpg_totalregistros_recebidos . ')';

		if ($Aplic->profissional) {
			$filtrosBuilder->setRecebido(false)
				->setPortfolio(true);
			$xpg_totalregistros_portfolios = (int)projetos_quantidade( $filtrosBuilder );
			$total3 = ' ('. $xpg_totalregistros_portfolios . ')';

			$filtrosBuilder->setPortfolio(false)
				->setTemplate(true);
			$xpg_totalregistros_modelos = (int)projetos_quantidade( $filtrosBuilder );
			$total4 = ' (' . $xpg_totalregistros_modelos . ')';
			}

		}
	else {
		$total1='';
		$total2='';
		if ($Aplic->profissional) {
			$total3='';
			$total4='';
			}
		}

	$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_projetos',ucfirst($config['projetos']).$total1, true,null,ucfirst($config['projetos']),'Clique nesta aba para visualizar '.$config['genero_projeto'].'s '.$config['projetos'].'.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_projetos','Recebid'.$config['genero_projeto'].$total2, true,null,'Recebid'.$config['genero_projeto'],'Clique nesta aba para visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' recebid'.$config['genero_projeto'].'s de outr'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_portifolio_pro',ucfirst($config['portfolio']).$total3, true,null,ucfirst($config['portfolio']),'Clique nesta aba para visualizar '.$config['genero_portfolio'].'s '.$config['portfolios'].' de '.$config['projetos'].'.');
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_modelo_pro','Modelos'.$total4, true,null,'Modelos','Clique nesta aba para visualizar os modelos de '.$config['projetos'].' que podem ter '. ($config['genero_tarefa']=='o'? 'seus' : 'suas').' '.$config['tarefas'].' importad'.$config['genero_tarefa'].'s para '.$config['genero_projeto'].'s '.$config['projetos'].'.');

	$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_gantt', 'Gantt',null,null,'Gantt','Clique nesta aba para visualizar o gráfico de Gantt de tod'.$config['genero_projeto'].'s '.$config['genero_projeto'].'s '.$config['projetos'].' de um dos grupos à esquerda.');
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}
else{
	//impressao
	if ($tab==0 || $tab==1) require_once BASE_DIR.'/modulos/projetos/ver_idx_projetos.php';
	if ($tab==2 && $Aplic->profissional) require_once BASE_DIR.'/modulos/projetos/ver_idx_portifolio_pro.php';
	if (($tab==2 && !$Aplic->profissional)||($tab==3 && $Aplic->profissional)) require_once BASE_DIR.'/modulos/projetos/ver_gantt.php';
	}


if($Aplic->profissional){
	$Aplic->carregarComboMultiSelecaoJS();

	echo '<script language="javascript">';

	echo 'function criarComboCia(){$jq("#cia_id").multiSelect({multiple:false, onCheck: function(){mudar_om();}});}';

	echo 'function criarComboCidades(){$jq("#municipio_id").multiSelect();}';
	if ($exibir['setor']){
		echo 'function criarComboSegmento(){$jq("#projeto_segmento").multiSelect({multiple:false, onCheck: function(){mudar_intervencao();}});}';
		echo 'function criarComboIntervencao(){$jq("#projeto_intervencao").multiSelect({multiple:false, onCheck: function(){mudar_tipo_intervencao();}});}';
		echo 'function criarComboTipoIntervencao(){$jq("#projeto_tipo_intervencao").multiSelect({multiple:false});}';
		}
	echo '$jq(function(){';
	echo '  $jq("#projeto_tipo").multiSelect();';
	echo '  $jq("#projetostatus").multiSelect();';

	if (count($vetor_favoritos)) echo '  $jq("#favorito_id").multiSelect();';
	echo '  $jq("#estado_sigla").multiSelect({multiple:false, onCheck: function(){mudar_cidades();}});';
	if ($exibir['setor']) echo '  $jq("#projeto_setor").multiSelect({multiple:false, onCheck: function(){mudar_segmento();}});';

	echo 'criarComboCia();';
	echo 'criarComboCidades();';
	if ($exibir['setor']){
		echo 'criarComboSegmento();';
		echo 'criarComboIntervencao();';
		echo 'criarComboTipoIntervencao();';
		}
	echo '});';
	echo '</script>';
	}

?>

<script language="javascript">




function popFiltroGestao() {
		parent.gpwebApp.popUp("Filtro de Gestão", 800, 400, 'm=projetos&a=filtro_gestao_pro&dialogo=1&cia_id='+document.getElementById('cia_id').value
		+'&filtro_criterio='+env.filtro_criterio.value
		+'&filtro_perspectiva='+env.filtro_perspectiva.value
		+'&filtro_tema='+env.filtro_tema.value
		+'&filtro_objetivo='+env.filtro_objetivo.value
		+'&filtro_me='+env.filtro_me.value
		+'&filtro_fator='+env.filtro_fator.value
		+'&filtro_estrategia='+env.filtro_estrategia.value
		+'&filtro_meta='+env.filtro_meta.value
		, window.setFiltroGestao, window);
		}

function setFiltroGestao(filtro_criterio, filtro_perspectiva, filtro_tema, filtro_objetivo, filtro_me, filtro_fator, filtro_estrategia, filtro_meta){
	env.filtro_criterio.value=filtro_criterio;
	env.filtro_perspectiva.value=filtro_perspectiva;
	env.filtro_tema.value=filtro_tema;
	env.filtro_objetivo.value=filtro_objetivo;
	env.filtro_me.value=filtro_me;
	env.filtro_fator.value=filtro_fator;
	env.filtro_estrategia.value=filtro_estrategia;
	env.filtro_meta.value=filtro_meta;
	env.submit();
	}


function popCamposExibir(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Campos", 500, 500, 'm=projetos&a=campos_projetos_pro&dialogo=1', window.setCamposExibir, window);
	else window.open('./index.php?m=projetos&a=campos_projetos_pro&dialogo=1', 'Campos','height=400,width=400,resizable,scrollbars=yes, left=0, top=0');
	}

function setCamposExibir(){
	url_passar(0, 'm=projetos&a=index');
	}

function exportar_excel(){
	var projeto_tipo=$jq.fn.multiSelect.selected('#projeto_tipo');
	var municipios=$jq.fn.multiSelect.selected('#municipio_id');
	var projetostatus=$jq.fn.multiSelect.selected('#projetostatus');
	<?php

	if ($exibir['setor']){
		echo "
		var projeto_setor=document.getElementById('projeto_setor').value;
		var projeto_segmento=document.getElementById('projeto_segmento').value;
		var projeto_intervencao=document.getElementById('projeto_intervencao').value;
		var projeto_tipo_intervencao=document.getElementById('projeto_tipo_intervencao').value;";
		}
	else {
		echo "
		var projeto_setor='';
		var projeto_segmento='';
		var projeto_intervencao='';
		var projeto_tipo_intervencao='';";
		}
	?>

  url_passar(1, 'm=projetos&a=exportar_excel_pro&sem_cabecalho=1&tab='+<?php echo $tab ?>+'&ver_subordinadas='+env.ver_subordinadas.value+'&projeto_expandido='+document.getElementById('projeto_expandido').value+'&nao_apenas_superiores='+env.nao_apenas_superiores.value+'&cia_id='+document.getElementById('cia_id').value+'&cia_dept='+<?php echo $dept_id ? $dept_id : '""' ?>+'&responsavel='+document.getElementById('responsavel').value+'&supervisor='+document.getElementById('supervisor').value+'&autoridade='+document.getElementById('autoridade').value+'&cliente='+document.getElementById('cliente').value+'&projeto_tipo='+projeto_tipo+'&projtextobusca='+document.getElementById('projtextobusca').value+'&projeto_setor='+projeto_setor+'&projeto_segmento='+projeto_segmento+'&projeto_intervencao='+projeto_intervencao+'&projeto_tipo_intervencao='+projeto_tipo_intervencao+'&estado_sigla='+document.getElementById('estado_sigla').value+'&municipio_id='+municipios+'&projetostatus='+projetostatus);

	}


function exportar_mysql(){
	var projeto_tipo=$jq.fn.multiSelect.selected('#projeto_tipo');
	var municipios=$jq.fn.multiSelect.selected('#municipio_id');
	var projetostatus=$jq.fn.multiSelect.selected('#projetostatus');
	<?php

	if ($exibir['setor']){
		echo "
		var projeto_setor=document.getElementById('projeto_setor').value;
		var projeto_segmento=document.getElementById('projeto_segmento').value;
		var projeto_intervencao=document.getElementById('projeto_intervencao').value;
		var projeto_tipo_intervencao=document.getElementById('projeto_tipo_intervencao').value;";
		}
	else {
		echo "
		var projeto_setor='';
		var projeto_segmento='';
		var projeto_intervencao='';
		var projeto_tipo_intervencao='';";
		}
	?>
  url_passar(1, 'm=projetos&a=exportar_mysql_pro&sem_cabecalho=1&tab='+<?php echo $tab ?>+'&ver_subordinadas='+env.ver_subordinadas.value+'&projeto_expandido='+document.getElementById('projeto_expandido').value+'&nao_apenas_superiores='+env.nao_apenas_superiores.value+'&cia_id='+document.getElementById('cia_id').value+'&cia_dept='+<?php echo $dept_id ? $dept_id : '""' ?>+'&responsavel='+document.getElementById('responsavel').value+'&supervisor='+document.getElementById('supervisor').value+'&autoridade='+document.getElementById('autoridade').value+'&cliente='+document.getElementById('cliente').value+'&projeto_tipo='+projeto_tipo+'&projtextobusca='+document.getElementById('projtextobusca').value+'&projeto_setor='+projeto_setor+'&projeto_segmento='+projeto_segmento+'&projeto_intervencao='+projeto_intervencao+'&projeto_tipo_intervencao='+projeto_tipo_intervencao+'&estado_sigla='+document.getElementById('estado_sigla').value+'&municipio_id='+municipios+'&projetostatus='+projetostatus);
	}



function dashboard_geral(){
	var projeto_tipo=$jq.fn.multiSelect.selected('#projeto_tipo');
	var municipios=$jq.fn.multiSelect.selected('#municipio_id');
	var projetostatus=$jq.fn.multiSelect.selected('#projetostatus');
	<?php

	if ($exibir['setor']){
		echo "
		var projeto_setor=document.getElementById('projeto_setor').value;
		var projeto_segmento=document.getElementById('projeto_segmento').value;
		var projeto_intervencao=document.getElementById('projeto_intervencao').value;
		var projeto_tipo_intervencao=document.getElementById('projeto_tipo_intervencao').value;";
		}
	else {
		echo "
		var projeto_setor='';
		var projeto_segmento='';
		var projeto_intervencao='';
		var projeto_tipo_intervencao='';";
		}
	?>
  url_passar(1, 'm=projetos&a=dashboard_geral_pro&dialogo=1&jquery=1&tab='+<?php echo $tab ?>+'&ver_subordinadas='+env.ver_subordinadas.value+'&projeto_expandido='+document.getElementById('projeto_expandido').value+'&nao_apenas_superiores='+env.nao_apenas_superiores.value+'&cia_id='+document.getElementById('cia_id').value+'&cia_dept='+<?php echo $dept_id ? $dept_id : '""' ?>+'&responsavel='+document.getElementById('responsavel').value+'&supervisor='+document.getElementById('supervisor').value+'&autoridade='+document.getElementById('autoridade').value+'&cliente='+document.getElementById('cliente').value+'&projeto_tipo='+projeto_tipo+'&projtextobusca='+document.getElementById('projtextobusca').value+'&projeto_setor='+projeto_setor+'&projeto_segmento='+projeto_segmento+'&projeto_intervencao='+projeto_intervencao+'&projeto_tipo_intervencao='+projeto_tipo_intervencao+'&estado_sigla='+document.getElementById('estado_sigla').value+'&municipio_id='+municipios+'&projetostatus='+projetostatus);
	}


function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:200px;"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>));
	}

function mudar_segmento(){
	<?php
	if($Aplic->profissional){
		echo '$jq.fn.multiSelect.clear("#projeto_tipo_intervencao");';
		echo '$jq.fn.multiSelect.clear("#projeto_intervencao");';
		}
	else{
		echo 'document.getElementById("projeto_intervencao").length=0;';
		echo 'document.getElementById("projeto_tipo_intervencao").length=0;';
		}
	?>
	xajax_mudar_ajax(document.getElementById('projeto_setor').value, 'Segmento', 'projeto_segmento','combo_segmento', 'style="width:200px;" class="texto" size=1 onchange="mudar_intervencao();"');
	}

function mudar_intervencao(){
	<?php
	if($Aplic->profissional) echo '$jq.fn.multiSelect.clear("#projeto_tipo_intervencao");';
	else echo 'document.getElementById("projeto_tipo_intervencao").length=0;';
	?>
	xajax_mudar_ajax(document.getElementById('projeto_segmento').value, 'Intervencao', 'projeto_intervencao','combo_intervencao', 'style="width:200px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"');

	}

function mudar_tipo_intervencao(){
	xajax_mudar_ajax(document.getElementById('projeto_intervencao').value, 'TipoIntervencao', 'projeto_tipo_intervencao','combo_tipo_intervencao', 'style="width:200px;" class="texto" size=1');
	}


function imprimir_projetos(tab){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 500, 500, 'm=projetos&a=imprimir_projetos&dialogo=1&cia_id='+document.getElementById('cia_id').value+'&sem_cabecalho=1&tab='+tab, null, window);
	else window.open('./index.php?m=projetos&a=imprimir_projetos&dialogo=1&cia_id='+document.getElementById('cia_id').value+'&sem_cabecalho=1&tab='+tab, 'imprimir','width=1200, height=600, menubar=1, scrollbars=1');
	}


function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia, deptartamento){
	env.cia_dept.value=cia;
	env.dept_id.value=deptartamento;
	env.submit();
	}


var usuarios_gerente = '<?php echo $responsavel?>';
var usuarios_supervisor = '<?php echo $supervisor?>';
var usuarios_autoridade = '<?php echo $autoridade?>';
var usuarios_cliente = '<?php echo $cliente?>';


<?php if ($Aplic->profissional){ ?>

function popFiltroCamposCustomizados(){
    var campos = <?php echo json_encode(toUtf8($campos_extras));?>;

     for(key in campos){
          if(campos.hasOwnProperty(key)){
            var cmp = campos[key];
            var id = 'customizado_' + cmp['campo_nome'];
            var fld = document.getElementById(id);
            if(fld){
              campos[key]['campo_valor_atual']=fld.value;
            }
          }
        }
    var w = window.parent.gpwebApp.filtroCamposCustomizados(campos);

    if(w){
      w.on('salvar', function(w, fields){
        for(key in fields){
          if(fields.hasOwnProperty(key)){
            var cmp = fields[key];
            var fld = document.getElementById('customizado_' + cmp['campo_nome']);
            if(fld){
              fld.value = cmp['campo_valor_atual'];
            }
          }
        }
      });
    }
}

function priorizacao() {
	parent.gpwebApp.popUp("<?php echo 'Priorização de '.ucfirst($config['projetos'])?>", 400, 300, 'm=publico&a=filtro_priorizacao_pro&dialogo=1&projeto=1&filtro_prioridade='+env.filtro_prioridade.value, window.setFiltroPriorizacao, window);
	}

function setFiltroPriorizacao(filtro_prioridade){
	env.filtro_prioridade.value=filtro_prioridade;
	env.submit();
	}


function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Responsável", 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_gerente, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_gerente, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setResponsavel(usuario_id_string){
	if(!usuario_id_string) usuarios_gerente = '';
	document.getElementById('responsavel').value = usuario_id_string;
	usuarios_gerente = usuario_id_string;
	xajax_lista_nome(usuario_id_string, 'nome_responsavel');
	}

function popSupervisor(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['supervisor']) ?>", 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_supervisor, window.setSupervisor, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_supervisor, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setSupervisor(usuario_id_string){
	if(!usuario_id_string) usuarios_gerente = '';
	document.getElementById('supervisor').value = usuario_id_string;
	usuarios_gerente = usuario_id_string;
	xajax_lista_nome(usuario_id_string, 'nome_supervisor');
	}


function popAutoridade(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['autoridade']) ?>", 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_autoridade, window.setAutoridade, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_autoridade, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setAutoridade(usuario_id_string){
	if(!usuario_id_string) usuarios_gerente = '';
	document.getElementById('autoridade').value = usuario_id_string;
	usuarios_gerente = usuario_id_string;
	xajax_lista_nome(usuario_id_string, 'nome_autoridade');
	}

function popCliente(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['cliente']) ?>", 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setCliente&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_cliente, window.setCliente, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setCliente&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_cliente, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setCliente(usuario_id_string){
	if(!usuario_id_string) usuarios_gerente = '';
	document.getElementById('cliente').value = usuario_id_string;
	usuarios_gerente = usuario_id_string;
	xajax_lista_nome(usuario_id_string, 'nome_cliente');
	}

function popSelecionarArea(){
	if(!parent || !parent.gpwebApp) return false;
	parent.gpwebApp.selecionarArea(selecionarArea, window);
	}


function selecionarArea(area){
	if(area) env.filtro_area.value=parent.Ext.JSON.encode(area);
	else env.filtro_area.value = '';
	env.submit();
	}

<?php } else { ?>

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('responsavel').value=usuario_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function popSupervisor(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["supervisor"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('supervisor').value, window.setSupervisor, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('supervisor').value, '<?php echo ucfirst($config["supervisor"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setSupervisor(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('supervisor').value=usuario_id;
	document.getElementById('nome_supervisor').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function popAutoridade(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["autoridade"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('autoridade').value, window.setAutoridade, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('autoridade').value, '<?php echo ucfirst($config["autoridade"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('autoridade').value=usuario_id;
	document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function popCliente(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["cliente"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCliente&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('cliente').value, window.setCliente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCliente&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('cliente').value, '<?php echo ucfirst($config["cliente"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setCliente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('cliente').value=usuario_id;
	document.getElementById('nome_cliente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

<?php } ?>


function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:200px;" onchange="javascript:mudar_om();"');
	}



function iluminar_tds(linha,alto,id){
	if(document.getElementsByTagName){
		var tcs=linha.getElementsByTagName('td');
		var nome_celula='';
		if(!id)check=false;
		else{
			var f=eval('document.frm');
			var check=eval('f.selecao_projeto_'+id+'.checked')
			}
		for(var j=0,j_cmp=tcs.length;j<j_cmp;j+=1){
			nome_celula=eval('tcs['+j+'].id');
			if(!(nome_celula.indexOf('ignore_td_')>=0)){
				if(alto==3) tcs[j].style.background='#FFFFCC';
				else if(alto==2||check)
				tcs[j].style.background='#FFCCCC';
				else if(alto==1) tcs[j].style.background='#FFFFCC';
				else tcs[j].style.background='#FFFFFF';
				}
			}
		}
	}

var estah_marcado;

function selecionar_projeto(id){
	var f=eval('document.frm');
	var boxObj=eval('f.elements["selecao_projeto_'+id+'"]');
	if(boxObj.checked){
		var linha=document.getElementById('projeto_'+id);
		boxObj.checked=false;
		iluminar_tds(linha,2,id);
		}
	else if(!boxObj.checked){
		var linha=document.getElementById('projeto_'+id);
		boxObj.checked=true;
		iluminar_tds(linha,3,id);
		}
	}

function selecionar_multiprojeto(id1, id2){
	var f=eval('document.frm');
	var boxObj=eval('f.elements["selecao_projeto_'+id2+'"]');
	if(boxObj.checked){
		var linha=document.getElementById('multiprojeto_tr_'+id1+'_'+id2+'_');
		boxObj.checked=false;
		iluminar_tds(linha,2,id2);
		}
	else if(!boxObj.checked){
		var linha=document.getElementById('multiprojeto_tr_'+id1+'_'+id2+'_');
		boxObj.checked=true;
		iluminar_tds(linha,3,id2);
		}
	}
var nomeTab="<?php echo $caixaTab->tabs[$tab][1] ?>";

function expandir_colapsar(id,tabelaNome,option,opt_nivel,root){
	var expandir=(option=='expandir'?1:0);
	var colapsar=(option=='colapsar'?1:0);
	var nivel=(opt_nivel==0?0:(opt_nivel>0?opt_nivel:-1));
	var include_root=(root?root:0);var done=false;
	var encontrado=false;var trs=document.getElementsByTagName('tr');
	for(var i=0;i<trs.length;i++){
		var tr_nome=trs.item(i).id;
		if((tr_nome.indexOf(id)>=0)&&nivel<0){
			var tr=document.getElementById(tr_nome);
			if(colapsar||expandir){
				if(colapsar){
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="colapsar";
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
						img_colapsar.style.display="none";
						img_expandir.style.display="inline";
						}
					else{
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
						img_colapsar.style.display="none";
						img_expandir.style.display="inline";
						}
					}
				else{
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="visible";
						tr.style.display="";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
						img_colapsar.style.display="inline";
						img_expandir.style.display="none";
						}
				else{
					tr.style.display="";
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
					img_colapsar.style.display="inline";
					img_expandir.style.display="none";
					}
				}
			}
		else {
			if(navigator.family=="gecko"||navigator.family=="opera"){
				tr.style.visibility=(tr.style.visibility==''||tr.style.visibility=="colapsar") ? "visible":"colapsar";
				tr.style.display=(tr.style.display=="none")? "" : "none";
				var img_expandir=document.getElementById(tr_nome+'_expandir');
				var img_colapsar=document.getElementById(tr_nome+'_colapsar');
				if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
				if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
				img_colapsar.style.display=(tr.style.visibility=='visible') ? "inline" : "none";
				img_expandir.style.display=(tr.style.visibility==''||tr.style.visibility=="colapsar")?"inline":"none";
				}
			else{
				tr.style.display=(tr.style.display=="none")?"":"none";
				var img_expandir=document.getElementById(tr_nome+'_expandir');
				var img_colapsar=document.getElementById(tr_nome+'_colapsar');
				if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
				if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
				img_colapsar.style.display=(tr.style.display=='')?"inline":"none";
				img_expandir.style.display=(tr.style.display=='none')?"inline":"none";
				}
			}
		}
		else if((tr_nome.indexOf(id)>=0)&&nivel>=0&&!done&&!encontrado&&!include_root){
			encontrado=true;
			var tr=document.getElementById(tr_nome);
			var img_expandir=document.getElementById(tr_nome+'_expandir');
			var img_colapsar=document.getElementById(tr_nome+'_colapsar');
			if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
			if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
			if(!(img_colapsar==null)) img_colapsar.style.display=(img_colapsar.style.display=='none')?"inline":"none";
			if(!(img_expandir==null)){
				img_expandir.style.display=(img_expandir.style.display=='none')?"inline":"none";
				opt=(img_expandir.style.display=="inline")?"colapsar":"expandir";
				colapsar=(opt=='colapsar'?1:0);expandir=(opt=='expandir'?1:0);
				}
			}
		else if((tr_nome.indexOf(id)>=0)&&nivel>=0&&include_root){
			encontrado=true;
			var tr=document.getElementById(tr_nome);
			nivel_atual=parseInt(tr_nome.substr(tr_nome.indexOf('>')+1,tr_nome.indexOf('<')-tr_nome.indexOf('>')-1));
			if(colapsar){
				if(navigator.family=="gecko"||navigator.family=="opera"){
					if((include_root==1&&nivel==0)||(nivel_atual>0)){
						tr.style.visibility="colapsar";
						tr.style.display="none";
						}
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
					if(!(img_colapsar==null)) img_colapsar.style.display="none";
					if(!(img_expandir==null)) img_expandir.style.display="inline";
					}
				else{
					if((include_root==1&&nivel==0)||(nivel_atual>0)) tr.style.display="none";
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
					if(!(img_colapsar==null))	img_colapsar.style.display="none";
					if(!(img_expandir==null))	img_expandir.style.display="inline";
					}
				}
			else{
				if(navigator.family=="gecko"||navigator.family=="opera"){
					if((include_root==1&&nivel==0)||(nivel_atual>0)) tr.style.visibility="visible";tr.style.display="";
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
					if(!(img_colapsar==null))	img_colapsar.style.display="inline";
					if(!(img_expandir==null))	img_expandir.style.display="none";
					}
			else{
				if((include_root==1&&nivel==0)||(nivel_atual>0)){
					tr.style.display=""}
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null){var img_colapsar=document.getElementById(id+'_colapsar')}
					if(!(img_colapsar==null)){img_colapsar.style.display="inline"}
					if(!(img_expandir==null)){img_expandir.style.display="none"}
					}
				}
			}
		else if(nivel>0&&!done&&(encontrado||nivel==0)){
			nivel_atual=parseInt(tr_nome.substr(tr_nome.indexOf('>')+1,tr_nome.indexOf('<')-tr_nome.indexOf('>')-1));
			if(nivel_atual<nivel){
				done=true;
				return;
				}
			else{
				var tr=document.getElementById(tr_nome);
				if(colapsar){
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="colapsar";
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null)var img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null){var img_colapsar=document.getElementById(id+'_colapsar')}
						if(!(img_colapsar==null)){img_colapsar.style.display="none"}
						if(!(img_expandir==null)){img_expandir.style.display="inline"}
						}
					else{
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null){var img_expandir=document.getElementById(id+'_expandir')}
						if(img_colapsar==null){var img_colapsar=document.getElementById(id+'_colapsar')}
						if(!(img_colapsar==null)){img_colapsar.style.display="none"}
						if(!(img_expandir==null)){img_expandir.style.display="inline"}
						}
					}
				else{
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="visible";
						tr.style.display="";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null){var img_expandir=document.getElementById(id+'_expandir')}
						if(img_colapsar==null){var img_colapsar=document.getElementById(id+'_colapsar')}
						if(!(img_colapsar==null)){img_colapsar.style.display="inline"}
						if(!(img_expandir==null)){img_expandir.style.display="none"}
						}
					else{
						tr.style.display="";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null){var img_expandir=document.getElementById(id+'_expandir')}
						if(img_colapsar==null){var img_colapsar=document.getElementById(id+'_colapsar')}
						if(!(img_colapsar==null)){img_colapsar.style.display="inline"}
						if(!(img_expandir==null)){img_expandir.style.display="none"}
						}
					}
				}
			}
		}
	}



function popRelacao(relacao){
	if(relacao) eval(relacao+'()');
	env.tipo_relacao.value='';
	}

function limpar_tudo(){
	document.env.pg_perspectiva_id .value = null;
	document.env.tema_id .value = null;
	document.env.pg_objetivo_estrategico_id .value = null;
	document.env.pg_fator_critico_id .value = null;
	document.env.pg_estrategia_id.value = null;
	document.env.pg_meta_id .value = null;
	document.env.pratica_id .value = null;
	document.env.pratica_indicador_id .value = null;
	document.env.plano_acao_id .value = null;
	document.env.canvas_id .value = null;
	document.env.risco_id.value = null;
	document.env.risco_resposta_id.value = null;
	document.env.calendario_id .value = null;
	document.env.monitoramento_id .value = null;
	document.env.instrumento_id.value = null;
	document.env.recurso_id.value = null;
	document.env.problema_id.value = null;
	document.env.demanda_id.value = null;
	document.env.programa_id.value = null;
	document.env.licao_id.value = null;
	document.env.evento_id.value = null;
	document.env.link_id.value = null;
	document.env.avaliacao_id.value = null;
	document.env.tgn_id.value = null;
	document.env.brainstorm_id.value = null;
	document.env.gut_id.value = null;
	document.env.causa_efeito_id.value = null;
	document.env.arquivo_id.value = null;
	document.env.forum_id.value = null;
	document.env.checklist_id.value = null;
	document.env.agenda_id.value = null;
	document.env.template_id.value = null;
	document.env.painel_id.value = null;
	document.env.painel_odometro_id.value = null;
	document.env.painel_composicao_id.value = null;
	<?php
	if($swot_ativo) echo 'document.env.swot_id.value = null;';
	if($ata_ativo) echo 'document.env.ata_id.value = null;';
	if($operativo_ativo) echo 'document.env.operativo_id.value = null;';
	if($agrupamento_ativo) echo 'document.env.agrupamento_id.value = null;';
	if($patrocinador_ativo) echo 'document.env.patrocinador_id.value = null;';
	if($tr_ativo) echo 'document.env.tr_id.value = null;';
	if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'acesso', null, 'me')) echo 'document.env.me_id.value = null;';
	?>
	}


function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.pg_perspectiva_id.value = chave;
	env.submit();
	}

function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.tema_id.value = chave;
	env.submit();
	}

function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.pg_objetivo_estrategico_id.value = chave;
	env.submit();
	}

function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('cia_id').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.pg_fator_critico_id.value = chave;
	env.submit();
	}

function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.pg_estrategia_id.value = chave;
	env.submit();
	}

function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.pg_meta_id.value = chave;
	env.submit();
	}

function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.pratica_id.value = chave;
	env.submit();
	}

function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_id.value = chave;
	env.submit();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.plano_acao_id.value = chave;
	env.submit();
	}

<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.canvas_id.value = chave;
	env.submit();
	}
<?php }?>

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRisco(chave, valor){
	limpar_tudo();
	document.env.risco_id.value = chave;
	env.submit();
	}
<?php }?>

<?php  if (isset($config['risco_respostas'])) { ?>
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.risco_resposta_id.value = chave;
	env.submit();
	}
<?php }?>


function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.calendario_id.value = chave;
	env.submit();
	}

function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.monitoramento_id.value = chave;
	env.submit();
	}

function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('cia_id').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.ata_id.value = chave;
	env.submit();
	}

function popSWOT() {
	parent.gpwebApp.popUp('SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('cia_id').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.swot_id.value = chave;
	env.submit();
	}

function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.operativo_id.value = chave;
	env.submit();
	}

function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.instrumento_id.value = chave;
	env.submit();
	}

function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.recurso_id.value = chave;
	env.submit();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.problema_id.value = chave;
	env.submit();
	}
<?php } ?>

function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.demanda_id.value = chave;
	env.submit();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.programa_id.value = chave;
	env.submit();
	}
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.licao_id.value = chave;
	env.submit();
	}

function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.evento_id.value = chave;
	env.submit();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.link_id.value = chave;
	env.submit();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.avaliacao_id.value = chave;
	env.submit();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.tgn_id.value = chave;
	env.submit();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.brainstorm_id.value = chave;
	env.submit();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.gut_id.value = chave;
	env.submit();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.causa_efeito_id.value = chave;
	env.submit();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('cia_id').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('cia_id').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.arquivo_id.value = chave;
	env.submit();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.forum_id.value = chave;
	env.submit();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.checklist_id.value = chave;
	env.submit();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.agenda_id.value = chave;
	env.submit();
	}

<?php  if ($Aplic->profissional) { ?>

	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.agrupamento_id.value = chave;
		env.submit();
		}

	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Patrocinador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, 'Patrocinador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.patrocinador_id.value = chave;
		env.submit();
		}

	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.template_id.value = chave;
		env.submit();
		}

	function popPainel() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, window.setPainel, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPainel(chave, valor){
		limpar_tudo();
		document.env.painel_id.value = chave;
		env.submit();
		}

	function popOdometro() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, window.setOdometro, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setOdometro(chave, valor){
		limpar_tudo();
		document.env.painel_odometro_id.value = chave;
		env.submit();
		}

	function popComposicaoPaineis() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, window.setComposicaoPaineis, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setComposicaoPaineis(chave, valor){
		limpar_tudo();
		document.env.painel_composicao_id.value = chave;
		env.submit();
		}

	function popTR() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, window.setTR, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTR(chave, valor){
		limpar_tudo();
		document.env.tr_id.value = chave;
		env.submit();
		}
	
	function popMe() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, window.setMe, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setMe(chave, valor){
		limpar_tudo();
		document.env.me_id.value = chave;
		env.submit();
		}		
		
<?php } ?>


</script>
