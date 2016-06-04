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
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

//vindo do adicionar evento no plano de comunicacoes
$projeto_comunicacao_evento_id = getParam($_REQUEST, 'projeto_comunicacao_evento_id', null);

$obj = new CEvento();
$msg = '';
$del = getParam($_REQUEST, 'del', 0);
$nao_eh_novo = getParam($_REQUEST, 'evento_id', null);
$evento_id = getParam($_REQUEST, 'evento_id', null);

if ($del && !$podeExcluir) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($nao_eh_novo && !$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$podeAdicionar) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (isset($_REQUEST['evento_inicio'])) $_REQUEST['evento_inicio']=getParam($_REQUEST, 'evento_inicio', null).' '.getParam($_REQUEST, 'inicio_hora', null);
if (isset($_REQUEST['evento_fim'])) $_REQUEST['evento_fim']=getParam($_REQUEST, 'evento_fim', null).' '.getParam($_REQUEST, 'fim_hora', null);

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=calendario&a=index&data='.$obj->evento_inicio);
	}
		
if (!$obj->evento_recorrencias) $obj->evento_nr_recorrencias = 0;


require_once $Aplic->getClasseSistema('CampoCustomizados');

if ($del) {
	if (!$obj->podeExcluir($msg)) {
		$Aplic->setMsg('Evento '.$msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=calendario&a=index&data='.$obj->evento_inicio);
		}
	if (($msg = $obj->excluir())) $Aplic->setMsg('Evento '.$msg, UI_MSG_ERRO);
	elseif(!$projeto_comunicacao_evento_id) $Aplic->setMsg('Evento excluído', UI_MSG_OK, true);
	
	
	
	$sql = new BDConsulta;
	$sql->adTabela('evento_arquivos');
	$sql->adCampo('evento_arquivo_endereco');
	$sql->adOnde('evento_arquivo_evento_id='.$evento_id);
	$caminhos=$sql->Lista();
	$sql->limpar();
	foreach ($caminhos as $caminho){
		@unlink($base_dir.'/arquivos/eventos/'.$caminho['evento_arquivo_endereco']);
		}
	@rmdir($base_dir.'/arquivos/eventos/'.$evento_id);	
	$sql->setExcluir('evento_arquivos');
	$sql->adOnde('evento_arquivo_evento_id='.$evento_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela evento_arquivos!'.$bd->stderr(true));
	$sql->limpar();	
	$Aplic->redirecionar('m=calendario&a=index&data='.$obj->evento_inicio);
	} 
else {
	if ($_REQUEST['evento_designado'] !='' && ($conflito = $obj->checarConflito(getParam($_REQUEST, 'evento_designado', null)))) {
		$Aplic->redirecionar('m=calendario&a=conflito&projeto_comunicacao_evento_id='.$projeto_comunicacao_evento_id.'&conflito='.implode(',',$conflito).'&objeto='.base64_encode(serialize($_REQUEST)));
		exit();
		} 
	else {
		if (($msg = $obj->armazenar())) $Aplic->setMsg('Evento '.$msg, UI_MSG_ERRO);
		else {
			if (!$projeto_comunicacao_evento_id) $Aplic->setMsg($nao_eh_novo ? 'Evento atualizado' : 'Evento adicionado', UI_MSG_OK, true);
			if (isset($_REQUEST['evento_designado'])) $obj->atualizarDesignados(explode(',', getParam($_REQUEST, 'evento_designado', null)),explode(',', getParam($_REQUEST, 'evento_designado_porcentagem', null)));
			if (($_REQUEST['evento_inicio_antigo'] && $_REQUEST['evento_inicio_antigo']!=$_REQUEST['evento_inicio']) || ($_REQUEST['evento_fim_antigo'] && $_REQUEST['evento_fim_antigo']!=$_REQUEST['evento_fim'])) $obj->atualizarDuracao(explode(',', getParam($_REQUEST, 'evento_designado', null)),explode(',', getParam($_REQUEST, 'evento_designado_porcentagem', null)));
			if (isset($_REQUEST['email_convidado'])) $obj->notificar(getParam($_REQUEST, 'evento_designado', null), $nao_eh_novo);
			$obj->adLembrete();
			//arquivo
			grava_arquivo_evento($obj->evento_id);
			}
		
		
		if ($_REQUEST['evento_nr_recorrencias'] > 0 && $_REQUEST['evento_recorrencias'] > 0 && !$_REQUEST['evento_recorrencia_pai']) {
			
			//verificar se nunca criou recorrencias
			$sql = new BDConsulta;
			$sql->adTabela('eventos');
			$sql->adCampo('count(evento_id)');
			$sql->adOnde('evento_recorrencia_pai='.(int)$obj->evento_id);
			$existe_recorrencia=$sql->Resultado();
			$sql->limpar();
			if (!$existe_recorrencia) $obj->criar_recorrencias();
			}

		}
	}

//vindo do adicionar evento no plano de comunicacoes	
if ($projeto_comunicacao_evento_id){
	echo '<script language="javascript">parent.gpwebApp._popupCallback('.$projeto_comunicacao_evento_id.', '.$obj->evento_id.');</script>';
	}	
elseif ($dialogo){
	echo '<script language="javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	}
if ($Aplic->profissional && getParam($_REQUEST, 'uuid', null)){
	$sql = new BDConsulta;
	$sql->adTabela('evento_gestao');
	$sql->adCampo('evento_gestao.*');
	$sql->adOnde('evento_gestao_evento='.(int)(int)$obj->evento_id);
	$sql->adOrdem('evento_gestao_ordem ASC');
	$linha=$sql->linha();
	$sql->limpar();
	
	$sql->adTabela('evento_gestao');
	$sql->adCampo('count(evento_gestao_id)');
	$sql->adOnde('evento_gestao_evento='.(int)$obj->evento_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	
	if ($linha['evento_gestao_tarefa'] && $qnt==1) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['evento_gestao_tarefa'];
	elseif ($linha['evento_gestao_projeto'] && $qnt==1) $endereco='m=projetos&a=ver&projeto_id='.$linha['evento_gestao_projeto'];
	elseif ($linha['evento_gestao_perspectiva'] && $qnt==1) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['evento_gestao_perspectiva'];
	elseif ($linha['evento_gestao_tema'] && $qnt==1) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['evento_gestao_tema'];
	elseif ($linha['evento_gestao_objetivo'] && $qnt==1) $endereco='m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$linha['evento_gestao_objetivo'];
	elseif ($linha['evento_gestao_fator'] && $qnt==1) $endereco='m=praticas&a=fator_ver&pg_fator_critico_id='.$linha['evento_gestao_fator'];
	elseif ($linha['evento_gestao_estrategia'] && $qnt==1) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['evento_gestao_estrategia'];
	elseif ($linha['evento_gestao_meta'] && $qnt==1) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['evento_gestao_meta'];
	elseif ($linha['evento_gestao_pratica'] && $qnt==1) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['evento_gestao_pratica'];
	elseif ($linha['evento_gestao_indicador'] && $qnt==1) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['evento_gestao_indicador'];
	elseif ($linha['evento_gestao_acao'] && $qnt==1) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['evento_gestao_acao'];
	elseif ($linha['evento_gestao_canvas'] && $qnt==1) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['evento_gestao_canvas'];
	elseif ($linha['evento_gestao_risco'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['evento_gestao_risco'];
	elseif ($linha['evento_gestao_risco_resposta'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['evento_gestao_risco_resposta'];
	elseif ($linha['evento_gestao_calendario'] && $qnt==1) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['evento_gestao_calendario'];
	elseif ($linha['evento_gestao_monitoramento'] && $qnt==1) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['evento_gestao_monitoramento'];
	elseif ($linha['evento_gestao_ata'] && $qnt==1) $endereco='m=atas&a=ata_ver&ata_id='.$linha['evento_gestao_ata'];
	elseif ($linha['evento_gestao_swot'] && $qnt==1) $endereco='m=swot&a=swot_ver&swot_id='.$linha['evento_gestao_swot'];
	elseif ($linha['evento_gestao_operativo'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['evento_operativo'];
	elseif ($linha['evento_gestao_instrumento'] && $qnt==1) $endereco='m=recursos&a=instrumento_ver&instrumento_id='.$linha['evento_gestao_instrumento'];
	elseif ($linha['evento_gestao_recurso'] && $qnt==1) $endereco='m=recursos&a=ver&recurso_id='.$linha['evento_gestao_recurso'];
	elseif ($linha['evento_gestao_problema'] && $qnt==1) $endereco='m=problema&a=problema_ver&problema_id='.$linha['evento_gestao_problema'];
	elseif ($linha['evento_gestao_demanda'] && $qnt==1) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['evento_gestao_demanda'];
	elseif ($linha['evento_gestao_programa'] && $qnt==1) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['evento_gestao_programa'];
	elseif ($linha['evento_gestao_link'] && $qnt==1) $endereco='m=links&a=ver&link_id='.$linha['evento_gestao_link'];
	elseif ($linha['evento_gestao_avaliacao'] && $qnt==1) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['evento_gestao_avaliacao'];
	elseif ($linha['evento_gestao_tgn'] && $qnt==1) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['evento_gestao_tgn'];
	elseif ($linha['evento_gestao_brainstorm'] && $qnt==1) $endereco='m=praticas&a=brainstorm_pro_ver&brainstorm_id='.$linha['evento_gestao_brainstorm'];
	elseif ($linha['evento_gestao_gut'] && $qnt==1) $endereco='m=praticas&a=gut_pro_ver&gut_id='.$linha['evento_gestao_gut'];
	elseif ($linha['evento_gestao_causa_efeito'] && $qnt==1) $endereco='m=praticas&a=causa_efeito_pro_ver&causa_efeito_id='.$linha['evento_gestao_causa_efeito'];
	elseif ($linha['evento_gestao_arquivo'] && $qnt==1) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['evento_gestao_arquivo'];
	elseif ($linha['evento_gestao_forum'] && $qnt==1) $endereco='m=foruns&a=ver&forum_id='.$linha['evento_gestao_forum'];
	elseif ($linha['evento_gestao_checklist'] && $qnt==1) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['evento_gestao_checklist'];
	elseif ($linha['evento_gestao_agenda'] && $qnt==1) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['evento_gestao_agenda'];
	elseif ($linha['evento_gestao_agrupamento'] && $qnt==1) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['evento_gestao_agrupamento'];
	elseif ($linha['evento_gestao_patrocinador'] && $qnt==1) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['evento_gestao_patrocinador'];
	elseif ($linha['evento_gestao_template'] && $qnt==1) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['evento_gestao_template'];
	elseif ($linha['evento_gestao_painel'] && $qnt==1) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['evento_gestao_painel'];
	elseif ($linha['evento_gestao_painel_odometro'] && $qnt==1) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['evento_gestao_painel_odometro'];
	elseif ($linha['evento_gestao_painel_composicao'] && $qnt==1) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['evento_gestao_painel_composicao'];
	elseif ($linha['evento_gestao_tr'] && $qnt==1) $endereco='m=tr&a=tr_ver&tr_id='.$linha['evento_gestao_tr'];
	elseif ($linha['evento_gestao_me'] && $qnt==1) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['evento_gestao_me'];
	else $endereco='m=calendario&a=index&data='.$obj->evento_inicio;
	$Aplic->redirecionar($endereco);
	}
else $Aplic->redirecionar('m=calendario&a=index&data='.$obj->evento_inicio);
?>