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

$arquivo_id = intval(getParam($_REQUEST, 'arquivo_id', 0));
$del = intval(getParam($_REQUEST, 'del', 0));
$duplicar = intval(getParam($_REQUEST, 'duplicar', 0));

global $bd;

$sql = new BDConsulta;

$not = getParam($_REQUEST, 'notificar', '0');
$notcont = getParam($_REQUEST, 'notificar_contatos', '0');
if ($not != '0') $not = '1';
if ($notcont != '0') $notcont = '1';
$nao_eh_novo = getParam($_REQUEST, 'arquivo_id', null);

if (!$Aplic->checarModulo('arquivos', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
$obj = new CArquivo();
if ($arquivo_id) {
	$obj->_mensagem = 'atualizado';
	$antigoObj = new CArquivo();
	$antigoObj->load($arquivo_id);

	//Se foi inserido um novo arquivo gravar histórico do antigo
	if(isset($_FILES['arquivo']['size']) && $_FILES['arquivo']['size'] > 0){
		$sql->adTabela('arquivo_historico');
		foreach(get_class_vars('CArquivo') as $chave => $valor_inutil)	if (substr($chave, 0, 1)!='_' && $antigoObj->{$chave}!='') $sql->adInserir($chave, $antigoObj->{$chave});
		$sql->exec();
		$sql->limpar();
		}	
	} 
else $obj->_mensagem = 'adicionado';



$obj->arquivo_categoria = intval(getParam($_REQUEST, 'arquivo_categoria', 0));
$versao = getParam($_REQUEST, 'arquivo_versao', 0);
$revisao_tipo = getParam($_REQUEST, 'revision_tipo', 0);
if (strcasecmp('major', $revisao_tipo) == 0) {
	$maior_num = strtok($versao, '.') + 1;
	$_REQUEST['arquivo_versao'] = $maior_num;
	}
if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=arquivos');
	}
	
$Aplic->setMsg('Arquivo');
if ($duplicar) {
	$obj->load($arquivo_id);
	$novo_arquivo = new CArquivo();
	$novo_arquivo = $obj->duplicar();
	$novo_arquivo->arquivo_pasta = null;
	if (!($dup_nome_real = $obj->duplicarArquivo($obj->arquivo_projeto, $obj->arquivo_nome_real))) {
		$Aplic->setMsg('Não foi possível duplicar o arquivo, verifique as permissões de arquivo', UI_MSG_ERRO);
		} 
	else {
		$novo_arquivo->arquivo_nome_real = $dup_nome_real;
		$novo_arquivo->arquivo_data = date('Y-m-d H:i:s');
		if (($msg = $novo_arquivo->armazenar())) {
			$Aplic->setMsg($msg, UI_MSG_ERRO);
			} 
		else {
			$Aplic->setMsg('duplicado', UI_MSG_OK, true);
			}
		}
	if ($dialogo){
		echo '<script language="javascript">';
		echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
		echo 'else self.close();';
		echo '</script>';	
		} 
	elseif($Aplic->profissional){
		$sql->adTabela('arquivo_gestao');
		$sql->adCampo('arquivo_gestao.*');
		$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
		$sql->adOrdem('arquivo_gestao_ordem ASC');
		$linha=$sql->linha();
		$sql->limpar();
		
		$sql->adTabela('arquivo_gestao');
		$sql->adCampo('count(arquivo_gestao_id)');
		$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
		$qnt=$sql->Resultado();
		$sql->limpar();
		
		if ($linha['arquivo_gestao_tarefa'] && $qnt==1) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['arquivo_gestao_tarefa'];
		elseif ($linha['arquivo_gestao_projeto'] && $qnt==1) $endereco='m=projetos&a=ver&projeto_id='.$linha['arquivo_gestao_projeto'];
		elseif ($linha['arquivo_gestao_perspectiva'] && $qnt==1) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['arquivo_gestao_perspectiva'];
		elseif ($linha['arquivo_gestao_tema'] && $qnt==1) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['arquivo_gestao_tema'];
		elseif ($linha['arquivo_gestao_objetivo'] && $qnt==1) $endereco='m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$linha['arquivo_gestao_objetivo'];
		elseif ($linha['arquivo_gestao_fator'] && $qnt==1) $endereco='m=praticas&a=fator_ver&pg_fator_critico_id='.$linha['arquivo_gestao_fator'];
		elseif ($linha['arquivo_gestao_estrategia'] && $qnt==1) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['arquivo_gestao_estrategia'];
		elseif ($linha['arquivo_gestao_meta'] && $qnt==1) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['arquivo_gestao_meta'];
		elseif ($linha['arquivo_gestao_pratica'] && $qnt==1) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['arquivo_gestao_pratica'];
		elseif ($linha['arquivo_gestao_indicador'] && $qnt==1) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['arquivo_gestao_indicador'];
		elseif ($linha['arquivo_gestao_acao'] && $qnt==1) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['arquivo_gestao_acao'];
		elseif ($linha['arquivo_gestao_canvas'] && $qnt==1) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['arquivo_gestao_canvas'];
		elseif ($linha['arquivo_gestao_risco'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_gestao_risco'];
		elseif ($linha['arquivo_gestao_risco_resposta'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_gestao_risco_resposta'];
		elseif ($linha['arquivo_gestao_calendario'] && $qnt==1) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['arquivo_gestao_calendario'];
		elseif ($linha['arquivo_gestao_monitoramento'] && $qnt==1) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['arquivo_gestao_monitoramento'];
		elseif ($linha['arquivo_gestao_ata'] && $qnt==1) $endereco='m=atas&a=ata_ver&ata_id='.$linha['arquivo_gestao_ata'];
		elseif ($linha['arquivo_gestao_swot'] && $qnt==1) $endereco='m=swot&a=swot_ver&swot_id='.$linha['arquivo_gestao_swot'];
		elseif ($linha['arquivo_gestao_operativo'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_operativo'];
		elseif ($linha['arquivo_gestao_instrumento'] && $qnt==1) $endereco='m=recursos&a=instrumento_ver&instrumento_id='.$linha['arquivo_gestao_instrumento'];
		elseif ($linha['arquivo_gestao_recurso'] && $qnt==1) $endereco='m=recursos&a=ver&recurso_id='.$linha['arquivo_gestao_recurso'];
		elseif ($linha['arquivo_gestao_problema'] && $qnt==1) $endereco='m=problema&a=problema_ver&problema_id='.$linha['arquivo_gestao_problema'];
		elseif ($linha['arquivo_gestao_demanda'] && $qnt==1) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['arquivo_gestao_demanda'];
		elseif ($linha['arquivo_gestao_programa'] && $qnt==1) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['arquivo_gestao_programa'];
		elseif ($linha['arquivo_gestao_evento'] && $qnt==1) $endereco='m=calendario&a=ver&evento_id='.$linha['arquivo_gestao_evento'];
		elseif ($linha['arquivo_gestao_link'] && $qnt==1) $endereco='m=links&a=ver&link_id='.$linha['arquivo_gestao_link'];
		elseif ($linha['arquivo_gestao_avaliacao'] && $qnt==1) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['arquivo_gestao_avaliacao'];
		elseif ($linha['arquivo_gestao_tgn'] && $qnt==1) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['arquivo_gestao_tgn'];
		elseif ($linha['arquivo_gestao_brainstorm'] && $qnt==1) $endereco='m=praticas&a=brainstorm_pro_ver&brainstorm_id='.$linha['arquivo_gestao_brainstorm'];
		elseif ($linha['arquivo_gestao_gut'] && $qnt==1) $endereco='m=praticas&a=gut_pro_ver&gut_id='.$linha['arquivo_gestao_gut'];
		elseif ($linha['arquivo_gestao_causa_efeito'] && $qnt==1) $endereco='m=praticas&a=causa_efeito_pro_ver&causa_efeito_id='.$linha['arquivo_gestao_causa_efeito'];
		elseif ($linha['arquivo_gestao_forum'] && $qnt==1) $endereco='m=foruns&a=ver&forum_id='.$linha['arquivo_gestao_forum'];
		elseif ($linha['arquivo_gestao_checklist'] && $qnt==1) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['arquivo_gestao_checklist'];
		elseif ($linha['arquivo_gestao_agenda'] && $qnt==1) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['arquivo_gestao_agenda'];
		elseif ($linha['arquivo_gestao_agrupamento'] && $qnt==1) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['arquivo_gestao_agrupamento'];
		elseif ($linha['arquivo_gestao_patrocinador'] && $qnt==1) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['arquivo_gestao_patrocinador'];
		elseif ($linha['arquivo_gestao_template'] && $qnt==1) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['arquivo_gestao_template'];
		elseif ($linha['arquivo_gestao_painel'] && $qnt==1) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['arquivo_gestao_painel'];
		elseif ($linha['arquivo_gestao_painel_odometro'] && $qnt==1) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['arquivo_gestao_painel_odometro'];
		elseif ($linha['arquivo_gestao_painel_composicao'] && $qnt==1) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['arquivo_gestao_painel_composicao'];
		elseif ($linha['arquivo_gestao_tr'] && $qnt==1) $endereco='m=tr&a=tr_ver&tr_id='.$linha['arquivo_gestao_tr'];
		elseif ($linha['arquivo_gestao_me'] && $qnt==1) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['arquivo_gestao_me'];
		else $endereco='m=arquivos&a=ver&arquivo_id='.$obj->arquivo_id;
		$Aplic->redirecionar($endereco);
		
		}	
		
			
	elseif ($obj->arquivo_tarefa) $Aplic->redirecionar('m=tarefas&a=ver&tab=3&tarefa_id='.(int)$obj->arquivo_tarefa); 
	elseif ($obj->arquivo_projeto) $Aplic->redirecionar('m=projetos&a=ver&tab=5&projeto_id='.(int)$obj->arquivo_projeto); 	
	elseif ($obj->arquivo_indicador) $Aplic->redirecionar('m=praticas&a=indicador_ver&tab=6&pratica_indicador_id='.(int)$obj->arquivo_indicador); 
	elseif ($obj->arquivo_pratica) $Aplic->redirecionar('m=praticas&a=pratica_ver&tab=6&pratica_id='.$obj->arquivo_pratica); 
	elseif ($obj->arquivo_demanda) $Aplic->redirecionar('m=projetos&a=demanda_ver&tab=0&demanda_id='.$obj->arquivo_demanda); 
	elseif ($obj->arquivo_instrumento) $Aplic->redirecionar('m=recursos&a=instrumento_ver&tab=0&instrumento_id='.$obj->arquivo_instrumento); 
	elseif ($obj->arquivo_perspectiva) $Aplic->redirecionar('m=praticas&a=perspectiva_ver&tab=1&pg_perspectiva_id='.$obj->arquivo_perspectiva);
	elseif ($obj->arquivo_canvas) $Aplic->redirecionar('m=praticas&a=canvas_pro_ver&tab=2&canvas_id='.$obj->arquivo_canvas);
	elseif ($obj->arquivo_tema) $Aplic->redirecionar('m=praticas&a=tema_ver&tab=3&tema_id='.$obj->arquivo_tema);
	elseif ($obj->arquivo_objetivo) $Aplic->redirecionar('m=praticas&a=obj_estrategico_ver&tab=3&pg_objetivo_estrategico_id='.$obj->arquivo_objetivo);
	elseif ($obj->arquivo_fator) $Aplic->redirecionar('m=praticas&a=fator_ver&tab=3&pg_fator_critico_id='.$obj->arquivo_fator);
	elseif ($obj->arquivo_estrategia) $Aplic->redirecionar('m=praticas&a=estrategia_ver&tab=3&pg_estrategia_id='.$obj->arquivo_estrategia);
	elseif ($obj->arquivo_acao) $Aplic->redirecionar('m=praticas&a=plano_acao_ver&tab=3&plano_acao_id='.$obj->arquivo_acao);
	elseif ($obj->arquivo_meta) $Aplic->redirecionar('m=praticas&a=meta_ver&tab=3&pg_meta_id='.$obj->arquivo_meta);
	elseif ($obj->arquivo_ata) $Aplic->redirecionar('m=atas&a=ata_ver&tab=0&ata_id='.$obj->arquivo_ata);
	else $Aplic->redirecionar('m=arquivos&arquivo_usuario='.$obj->arquivo_usuario.'&calendario_id='.$obj->arquivo_calendario);	
	}
	
if ($del) {
	$obj->load($arquivo_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		} 
	else {
		if ($not == '1') $obj->notificar();
		$Aplic->setMsg('excluído', UI_MSG_OK, true);
		}
	if ($dialogo){
		echo '<script language="javascript">';
		echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
		echo 'else self.close();';
		echo '</script>';	
		}
	elseif($Aplic->profissional){
		$sql->adTabela('arquivo_gestao');
		$sql->adCampo('arquivo_gestao.*');
		$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
		$sql->adOrdem('arquivo_gestao_ordem ASC');
		$linha=$sql->linha();
		$sql->limpar();
		
		$sql->adTabela('arquivo_gestao');
		$sql->adCampo('count(arquivo_gestao_id)');
		$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
		$qnt=$sql->Resultado();
		$sql->limpar();
		
		if ($linha['arquivo_gestao_tarefa'] && $qnt==1) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['arquivo_gestao_tarefa'];
		elseif ($linha['arquivo_gestao_projeto'] && $qnt==1) $endereco='m=projetos&a=ver&projeto_id='.$linha['arquivo_gestao_projeto'];
		elseif ($linha['arquivo_gestao_perspectiva'] && $qnt==1) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['arquivo_gestao_perspectiva'];
		elseif ($linha['arquivo_gestao_tema'] && $qnt==1) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['arquivo_gestao_tema'];
		elseif ($linha['arquivo_gestao_objetivo'] && $qnt==1) $endereco='m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$linha['arquivo_gestao_objetivo'];
		elseif ($linha['arquivo_gestao_fator'] && $qnt==1) $endereco='m=praticas&a=fator_ver&pg_fator_critico_id='.$linha['arquivo_gestao_fator'];
		elseif ($linha['arquivo_gestao_estrategia'] && $qnt==1) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['arquivo_gestao_estrategia'];
		elseif ($linha['arquivo_gestao_meta'] && $qnt==1) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['arquivo_gestao_meta'];
		elseif ($linha['arquivo_gestao_pratica'] && $qnt==1) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['arquivo_gestao_pratica'];
		elseif ($linha['arquivo_gestao_indicador'] && $qnt==1) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['arquivo_gestao_indicador'];
		elseif ($linha['arquivo_gestao_acao'] && $qnt==1) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['arquivo_gestao_acao'];
		elseif ($linha['arquivo_gestao_canvas'] && $qnt==1) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['arquivo_gestao_canvas'];
		elseif ($linha['arquivo_gestao_risco'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_gestao_risco'];
		elseif ($linha['arquivo_gestao_risco_resposta'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_gestao_risco_resposta'];
		elseif ($linha['arquivo_gestao_calendario'] && $qnt==1) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['arquivo_gestao_calendario'];
		elseif ($linha['arquivo_gestao_monitoramento'] && $qnt==1) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['arquivo_gestao_monitoramento'];
		elseif ($linha['arquivo_gestao_ata'] && $qnt==1) $endereco='m=atas&a=ata_ver&ata_id='.$linha['arquivo_gestao_ata'];
		elseif ($linha['arquivo_gestao_swot'] && $qnt==1) $endereco='m=swot&a=swot_ver&swot_id='.$linha['arquivo_gestao_swot'];
		elseif ($linha['arquivo_gestao_operativo'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_operativo'];
		elseif ($linha['arquivo_gestao_instrumento'] && $qnt==1) $endereco='m=recursos&a=instrumento_ver&instrumento_id='.$linha['arquivo_gestao_instrumento'];
		elseif ($linha['arquivo_gestao_recurso'] && $qnt==1) $endereco='m=recursos&a=ver&recurso_id='.$linha['arquivo_gestao_recurso'];
		elseif ($linha['arquivo_gestao_problema'] && $qnt==1) $endereco='m=problema&a=problema_ver&problema_id='.$linha['arquivo_gestao_problema'];
		elseif ($linha['arquivo_gestao_demanda'] && $qnt==1) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['arquivo_gestao_demanda'];
		elseif ($linha['arquivo_gestao_programa'] && $qnt==1) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['arquivo_gestao_programa'];
		elseif ($linha['arquivo_gestao_evento'] && $qnt==1) $endereco='m=calendario&a=ver&evento_id='.$linha['arquivo_gestao_evento'];
		elseif ($linha['arquivo_gestao_link'] && $qnt==1) $endereco='m=links&a=ver&link_id='.$linha['arquivo_gestao_link'];
		elseif ($linha['arquivo_gestao_avaliacao'] && $qnt==1) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['arquivo_gestao_avaliacao'];
		elseif ($linha['arquivo_gestao_tgn'] && $qnt==1) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['arquivo_gestao_tgn'];
		elseif ($linha['arquivo_gestao_brainstorm'] && $qnt==1) $endereco='m=praticas&a=brainstorm_pro_ver&brainstorm_id='.$linha['arquivo_gestao_brainstorm'];
		elseif ($linha['arquivo_gestao_gut'] && $qnt==1) $endereco='m=praticas&a=gut_pro_ver&gut_id='.$linha['arquivo_gestao_gut'];
		elseif ($linha['arquivo_gestao_causa_efeito'] && $qnt==1) $endereco='m=praticas&a=causa_efeito_pro_ver&causa_efeito_id='.$linha['arquivo_gestao_causa_efeito'];
		elseif ($linha['arquivo_gestao_forum'] && $qnt==1) $endereco='m=foruns&a=ver&forum_id='.$linha['arquivo_gestao_forum'];
		elseif ($linha['arquivo_gestao_checklist'] && $qnt==1) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['arquivo_gestao_checklist'];
		elseif ($linha['arquivo_gestao_agenda'] && $qnt==1) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['arquivo_gestao_agenda'];
		elseif ($linha['arquivo_gestao_agrupamento'] && $qnt==1) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['arquivo_gestao_agrupamento'];
		elseif ($linha['arquivo_gestao_patrocinador'] && $qnt==1) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['arquivo_gestao_patrocinador'];
		elseif ($linha['arquivo_gestao_template'] && $qnt==1) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['arquivo_gestao_template'];
		elseif ($linha['arquivo_gestao_painel'] && $qnt==1) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['arquivo_gestao_painel'];
		elseif ($linha['arquivo_gestao_painel_odometro'] && $qnt==1) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['arquivo_gestao_painel_odometro'];
		elseif ($linha['arquivo_gestao_painel_composicao'] && $qnt==1) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['arquivo_gestao_painel_composicao'];
		elseif ($linha['arquivo_gestao_tr'] && $qnt==1) $endereco='m=tr&a=tr_ver&tr_id='.$linha['arquivo_gestao_tr'];
		elseif ($linha['arquivo_gestao_me'] && $qnt==1) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['arquivo_gestao_me'];
		else $endereco='m=arquivos&a=index';
		$Aplic->redirecionar($endereco);
		}		 		
	elseif ($obj->arquivo_tarefa) $Aplic->redirecionar('m=tarefas&a=ver&tab=4&tarefa_id='.(int)$obj->arquivo_tarefa); 
	elseif ($obj->arquivo_projeto) $Aplic->redirecionar('m=projetos&a=ver&tab=5&projeto_id='.(int)$obj->arquivo_projeto); 	
	elseif ($obj->arquivo_indicador) $Aplic->redirecionar('m=praticas&a=indicador_ver&tab=6&pratica_indicador_id='.(int)$obj->arquivo_indicador); 
	elseif ($obj->arquivo_pratica) $Aplic->redirecionar('m=praticas&a=pratica_ver&tab=6&pratica_id='.$obj->arquivo_pratica); 
	elseif ($obj->arquivo_demanda) $Aplic->redirecionar('m=projetos&a=demanda_ver&tab=0&demanda_id='.$obj->arquivo_demanda); 
	elseif ($obj->arquivo_instrumento) $Aplic->redirecionar('m=recursos&a=instrumento_ver&tab=0&instrumento_id='.$obj->arquivo_instrumento); 
	elseif ($obj->arquivo_perspectiva) $Aplic->redirecionar('m=praticas&a=perspectiva_ver&tab=1&pg_perspectiva_id='.$obj->arquivo_perspectiva);
	elseif ($obj->arquivo_canvas) $Aplic->redirecionar('m=praticas&a=canvas_pro_ver&tab=2&canvas_id='.$obj->arquivo_canvas);
	elseif ($obj->arquivo_tema) $Aplic->redirecionar('m=praticas&a=tema_ver&tab=3&tema_id='.$obj->arquivo_tema);
	elseif ($obj->arquivo_objetivo) $Aplic->redirecionar('m=praticas&a=obj_estrategico_ver&tab=3&pg_objetivo_estrategico_id='.$obj->arquivo_objetivo);
	elseif ($obj->arquivo_fator) $Aplic->redirecionar('m=praticas&a=fator_ver&tab=3&pg_fator_critico_id='.$obj->arquivo_fator);
	elseif ($obj->arquivo_estrategia) $Aplic->redirecionar('m=praticas&a=estrategia_ver&tab=3&pg_estrategia_id='.$obj->arquivo_estrategia);
	elseif ($obj->arquivo_acao) $Aplic->redirecionar('m=praticas&a=plano_acao_ver&tab=3&plano_acao_id='.$obj->arquivo_acao);
	elseif ($obj->arquivo_meta) $Aplic->redirecionar('m=praticas&a=meta_ver&tab=3&pg_meta_id='.$obj->arquivo_meta);
	elseif ($obj->arquivo_ata) $Aplic->redirecionar('m=atas&a=ata_ver&tab=0&ata_id='.$obj->arquivo_ata);
	else $Aplic->redirecionar('m=arquivos&arquivo_usuario='.$obj->arquivo_usuario.'&calendario_id='.$obj->arquivo_calendario);	
	}
	
ignore_user_abort(1);
$upload = null;

if (isset($_FILES['arquivo'])) {
	$upload = $_FILES['arquivo'];
	$tipo=explode('/',$upload['type']);	
	$tipo=strtolower(pathinfo($upload['name'], PATHINFO_EXTENSION));
	$permitido=getSisValor('downloadPermitido');
	
	$proibido=getSisValor('downloadProibido');
  $verificar_malicioso=explode('.',$_FILES['arquivo']['name']);
 	$malicioso=false;
 	foreach($verificar_malicioso as $extensao) {
 		if (in_array(strtolower($extensao), $proibido)) {
 			$malicioso=$extensao;
 			break;
 			}
 		}
 	if ($malicioso) {
  	$Aplic->setMsg('Extensão '.$malicioso.' não é permitida!', UI_MSG_ERRO);
  	$Aplic->redirecionar('m=arquivos');
  	}
  elseif ($upload['size'] < 1) {
		if (!$arquivo_id) {
			$Aplic->setMsg('Arquivo enviado tem tamanho zero. Processo abortado.', UI_MSG_ERRO);
			$Aplic->redirecionar('m=arquivos');
			}
		}
  else if (!in_array($tipo, $permitido)) {
  	$Aplic->setMsg('Extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido', UI_MSG_ERRO);
		$Aplic->redirecionar('m=arquivos');
  	}	
	else {
		$obj->arquivo_nome = $upload['name'];
		$obj->arquivo_tipo = $upload['type'];
		$obj->arquivo_tamanho = $upload['size'];
		$obj->arquivo_data = date('Y-m-d H:i:s');
		$obj->arquivo_nome_real = md5(uniqid(rand(), true));
		$res = $obj->moverTemp($upload);
		
		if (!$res) {
			$Aplic->setMsg('Não foi possível escrever o arquivo', UI_MSG_ERRO);
			$Aplic->redirecionar('m=arquivos');
			}
		}
	}
	
	
if (!$Aplic->profissional && $arquivo_id && (($obj->arquivo_projeto != $antigoObj->arquivo_projeto) || ($obj->arquivo_indicador != $antigoObj->arquivo_indicador) || ($obj->arquivo_pratica != $antigoObj->arquivo_pratica) || ($obj->arquivo_demanda != $antigoObj->arquivo_demanda) || ($obj->arquivo_instrumento != $antigoObj->arquivo_instrumento)|| ($obj->arquivo_usuario != $antigoObj->arquivo_usuario) || ($obj->arquivo_tema != $antigoObj->arquivo_tema) || ($obj->arquivo_objetivo != $antigoObj->arquivo_objetivo)  || ($obj->arquivo_estrategia != $antigoObj->arquivo_estrategia) || ($obj->arquivo_acao != $antigoObj->arquivo_acao) || ($obj->arquivo_fator != $antigoObj->arquivo_fator) || ($obj->arquivo_meta != $antigoObj->arquivo_meta) || ($obj->arquivo_perspectiva != $antigoObj->arquivo_perspectiva) || ($obj->arquivo_canvas != $antigoObj->arquivo_canvas) || ($obj->arquivo_calendario != $antigoObj->arquivo_calendario) || ($obj->arquivo_ata != $antigoObj->arquivo_ata))) {

	$res = $obj->moverArquivo($antigoObj->arquivo_nome_real, $antigoObj->arquivo_projeto, $antigoObj->arquivo_pratica, $antigoObj->arquivo_indicador, $antigoObj->arquivo_usuario, $antigoObj->arquivo_objetivo, $antigoObj->arquivo_estrategia, $antigoObj->arquivo_acao, $antigoObj->arquivo_fator, $antigoObj->arquivo_meta, $antigoObj->arquivo_perspectiva, $antigoObj->arquivo_tema, $antigoObj->arquivo_demanda, $antigoObj->arquivo_calendario, $antigoObj->arquivo_ata, $antigoObj->arquivo_instrumento, $antigoObj->arquivo_canvas);
	if (!$res) {
		$Aplic->setMsg('Não foi possível mover o arquivo', UI_MSG_ERRO);
		$Aplic->redirecionar('m=arquivos&projeto_id='.$obj->arquivo_projeto.'&pratica_indicador_id='.$obj->arquivo_indicador.'&pratica_id='.$obj->arquivo_pratica.'&demanda_id='.$obj->arquivo_demanda.'&instrumento_id='.$obj->arquivo_instrumento.'&arquivo_usuario='.$obj->arquivo_usuario.'&tema_id='.$obj->arquivo_tema.'&pg_objetivo_estrategico_id='.$obj->arquivo_objetivo.'&pg_estrategia_id='.$obj->arquivo_estrategia.'&plano_acao_id='.$obj->arquivo_acao.'&pg_fator_critico_id='.$obj->arquivo_fator.'&pg_meta_id='.$obj->arquivo_meta.'&pg_perspectiva_id='.$obj->arquivo_perspectiva.'&canvas_id='.$obj->arquivo_canvas.'&calendario_id='.$obj->arquivo_calendario.'&ata_id='.$obj->arquivo_ata);
		}
	}
	
	
if (!$arquivo_id) {
	$obj->arquivo_dono = $Aplic->usuario_id;
	if (!$obj->arquivo_versao_id) {
		$sql->adTabela('arquivos');
		$sql->adCampo('arquivo_versao_id');
		$sql->adOrdem('arquivo_versao_id DESC');
		$sql->setLimite(1);
		$ultimo_arquivo_versao = $sql->Resultado();
		$sql->limpar();
		$obj->arquivo_versao_id = $ultimo_arquivo_versao + 1;
		} 
	else {
		$sql->adTabela('arquivos');
		$sql->adAtualizar('arquivo_saida', '');
		$sql->adOnde('arquivo_versao_id = '.(int)$obj->arquivo_versao_id);
		$sql->exec();
		$sql->limpar();
		}
	}
if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->load($obj->arquivo_id);
	if ($not == '1') $obj->notificar();
	$Aplic->setMsg($arquivo_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}

if ($dialogo){
	echo '<script language="javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	}

if ($Aplic->profissional && getParam($_REQUEST, 'uuid', null)){
	
	$sql->adTabela('arquivo_gestao');
	$sql->adCampo('arquivo_gestao.*');
	$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
	$sql->adOrdem('arquivo_gestao_ordem ASC');
	$linha=$sql->linha();
	$sql->limpar();
	
	$sql->adTabela('arquivo_gestao');
	$sql->adCampo('count(arquivo_gestao_id)');
	$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	
	if ($linha['arquivo_gestao_tarefa'] && $qnt==1) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['arquivo_gestao_tarefa'];
	elseif ($linha['arquivo_gestao_projeto'] && $qnt==1) $endereco='m=projetos&a=ver&projeto_id='.$linha['arquivo_gestao_projeto'];
	elseif ($linha['arquivo_gestao_perspectiva'] && $qnt==1) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['arquivo_gestao_perspectiva'];
	elseif ($linha['arquivo_gestao_tema'] && $qnt==1) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['arquivo_gestao_tema'];
	elseif ($linha['arquivo_gestao_objetivo'] && $qnt==1) $endereco='m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$linha['arquivo_gestao_objetivo'];
	elseif ($linha['arquivo_gestao_fator'] && $qnt==1) $endereco='m=praticas&a=fator_ver&pg_fator_critico_id='.$linha['arquivo_gestao_fator'];
	elseif ($linha['arquivo_gestao_estrategia'] && $qnt==1) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['arquivo_gestao_estrategia'];
	elseif ($linha['arquivo_gestao_meta'] && $qnt==1) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['arquivo_gestao_meta'];
	elseif ($linha['arquivo_gestao_pratica'] && $qnt==1) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['arquivo_gestao_pratica'];
	elseif ($linha['arquivo_gestao_indicador'] && $qnt==1) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['arquivo_gestao_indicador'];
	elseif ($linha['arquivo_gestao_acao'] && $qnt==1) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['arquivo_gestao_acao'];
	elseif ($linha['arquivo_gestao_canvas'] && $qnt==1) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['arquivo_gestao_canvas'];
	elseif ($linha['arquivo_gestao_risco'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_gestao_risco'];
	elseif ($linha['arquivo_gestao_risco_resposta'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_gestao_risco_resposta'];
	elseif ($linha['arquivo_gestao_calendario'] && $qnt==1) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['arquivo_gestao_calendario'];
	elseif ($linha['arquivo_gestao_monitoramento'] && $qnt==1) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['arquivo_gestao_monitoramento'];
	elseif ($linha['arquivo_gestao_ata'] && $qnt==1) $endereco='m=atas&a=ata_ver&ata_id='.$linha['arquivo_gestao_ata'];
	elseif ($linha['arquivo_gestao_swot'] && $qnt==1) $endereco='m=swot&a=swot_ver&swot_id='.$linha['arquivo_gestao_swot'];
	elseif ($linha['arquivo_gestao_operativo'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_operativo'];
	elseif ($linha['arquivo_gestao_instrumento'] && $qnt==1) $endereco='m=recursos&a=instrumento_ver&instrumento_id='.$linha['arquivo_gestao_instrumento'];
	elseif ($linha['arquivo_gestao_recurso'] && $qnt==1) $endereco='m=recursos&a=ver&recurso_id='.$linha['arquivo_gestao_recurso'];
	elseif ($linha['arquivo_gestao_problema'] && $qnt==1) $endereco='m=problema&a=problema_ver&problema_id='.$linha['arquivo_gestao_problema'];
	elseif ($linha['arquivo_gestao_demanda'] && $qnt==1) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['arquivo_gestao_demanda'];
	elseif ($linha['arquivo_gestao_programa'] && $qnt==1) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['arquivo_gestao_programa'];
	elseif ($linha['arquivo_gestao_evento'] && $qnt==1) $endereco='m=calendario&a=ver&evento_id='.$linha['arquivo_gestao_evento'];
	elseif ($linha['arquivo_gestao_link'] && $qnt==1) $endereco='m=links&a=ver&link_id='.$linha['arquivo_gestao_link'];
	elseif ($linha['arquivo_gestao_avaliacao'] && $qnt==1) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['arquivo_gestao_avaliacao'];
	elseif ($linha['arquivo_gestao_tgn'] && $qnt==1) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['arquivo_gestao_tgn'];
	elseif ($linha['arquivo_gestao_brainstorm'] && $qnt==1) $endereco='m=praticas&a=brainstorm_pro_ver&brainstorm_id='.$linha['arquivo_gestao_brainstorm'];
	elseif ($linha['arquivo_gestao_gut'] && $qnt==1) $endereco='m=praticas&a=gut_pro_ver&gut_id='.$linha['arquivo_gestao_gut'];
	elseif ($linha['arquivo_gestao_causa_efeito'] && $qnt==1) $endereco='m=praticas&a=causa_efeito_pro_ver&causa_efeito_id='.$linha['arquivo_gestao_causa_efeito'];
	elseif ($linha['arquivo_gestao_forum'] && $qnt==1) $endereco='m=foruns&a=ver&forum_id='.$linha['arquivo_gestao_forum'];
	elseif ($linha['arquivo_gestao_checklist'] && $qnt==1) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['arquivo_gestao_checklist'];
	elseif ($linha['arquivo_gestao_agenda'] && $qnt==1) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['arquivo_gestao_agenda'];
	elseif ($linha['arquivo_gestao_agrupamento'] && $qnt==1) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['arquivo_gestao_agrupamento'];
	elseif ($linha['arquivo_gestao_patrocinador'] && $qnt==1) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['arquivo_gestao_patrocinador'];
	elseif ($linha['arquivo_gestao_template'] && $qnt==1) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['arquivo_gestao_template'];
	elseif ($linha['arquivo_gestao_painel'] && $qnt==1) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['arquivo_gestao_painel'];
	elseif ($linha['arquivo_gestao_painel_odometro'] && $qnt==1) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['arquivo_gestao_painel_odometro'];
	elseif ($linha['arquivo_gestao_painel_composicao'] && $qnt==1) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['arquivo_gestao_painel_composicao'];
	elseif ($linha['arquivo_gestao_tr'] && $qnt==1) $endereco='m=tr&a=tr_ver&tr_id='.$linha['arquivo_gestao_tr'];
	elseif ($linha['arquivo_gestao_me'] && $qnt==1) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['arquivo_gestao_me'];
	else $endereco='m=arquivos&a=ver&arquivo_id='.$obj->arquivo_id;
	$Aplic->redirecionar($endereco);
	}
elseif ($Aplic->profissional) $Aplic->redirecionar('m=arquivos&a=ver&arquivo_id='.$obj->arquivo_id);
elseif ($obj->arquivo_tarefa) $Aplic->redirecionar('m=tarefas&a=ver&tab=4&tarefa_id='.(int)$obj->arquivo_tarefa); 
elseif ($obj->arquivo_projeto) $Aplic->redirecionar('m=projetos&a=ver&tab=5&projeto_id='.(int)$obj->arquivo_projeto); 	
elseif ($obj->arquivo_indicador) $Aplic->redirecionar('m=praticas&a=indicador_ver&tab=6&pratica_indicador_id='.(int)$obj->arquivo_indicador); 
elseif ($obj->arquivo_pratica) $Aplic->redirecionar('m=praticas&a=pratica_ver&tab=6&pratica_id='.$obj->arquivo_pratica); 
elseif ($obj->arquivo_demanda) $Aplic->redirecionar('m=projetos&a=demanda_ver&tab=0&demanda_id='.$obj->arquivo_demanda); 
elseif ($obj->arquivo_instrumento) $Aplic->redirecionar('m=recursos&a=instrumento_ver&tab=0&instrumento_id='.$obj->arquivo_instrumento); 
elseif ($obj->arquivo_perspectiva) $Aplic->redirecionar('m=praticas&a=perspectiva_ver&tab=1&pg_perspectiva_id='.$obj->arquivo_perspectiva);
elseif ($obj->arquivo_canvas) $Aplic->redirecionar('m=praticas&a=canvas_pro_ver&tab=2&canvas_id='.$obj->arquivo_canvas);
elseif ($obj->arquivo_tema) $Aplic->redirecionar('m=praticas&a=tema_ver&tab=3&tema_id='.$obj->arquivo_tema);
elseif ($obj->arquivo_objetivo) $Aplic->redirecionar('m=praticas&a=obj_estrategico_ver&tab=3&pg_objetivo_estrategico_id='.$obj->arquivo_objetivo);
elseif ($obj->arquivo_fator) $Aplic->redirecionar('m=praticas&a=fator_ver&tab=3&pg_fator_critico_id='.$obj->arquivo_fator);
elseif ($obj->arquivo_estrategia) $Aplic->redirecionar('m=praticas&a=estrategia_ver&tab=3&pg_estrategia_id='.$obj->arquivo_estrategia);
elseif ($obj->arquivo_acao) $Aplic->redirecionar('m=praticas&a=plano_acao_ver&tab=3&plano_acao_id='.$obj->arquivo_acao);
elseif ($obj->arquivo_meta) $Aplic->redirecionar('m=praticas&a=meta_ver&tab=3&pg_meta_id='.$obj->arquivo_meta);
elseif ($obj->arquivo_ata) $Aplic->redirecionar('m=atas&a=ata_ver&tab=0&ata_id='.$obj->arquivo_ata);
else $Aplic->redirecionar('m=arquivos&a=ver&arquivo_id='.$obj->arquivo_id);

			 

?>