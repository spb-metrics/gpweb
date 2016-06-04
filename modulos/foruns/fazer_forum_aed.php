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
$del = isset($_REQUEST['del']) ? getParam($_REQUEST, 'del', null) : 0;
$nao_eh_novo = getParam($_REQUEST, 'forum_id', null);

transforma_vazio_em_nulo($_REQUEST);

if ($del) {
	if (!$Aplic->checarModulo('foruns', 'excluir')) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif ($nao_eh_novo) {
	if (!$Aplic->checarModulo('foruns', 'editar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif (!$Aplic->checarModulo('foruns', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
$obj = new CForum();
if (($msg = $obj->join($_REQUEST))) {
	$Aplic->setMsg($msg, UI_MSG_ERRO);
	$Aplic->redirecionar('m=foruns');
	}
$Aplic->setMsg('Fórum');
if ($del) {
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		}
	if ($dialogo){
		echo '<script language="javascript">';
		echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
		echo 'else self.close();';
		echo '</script>';	
		} 
	$Aplic->redirecionar('m=foruns');
	
	} 

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else $Aplic->setMsg($nao_eh_novo ? 'atualizado' : 'adicionado', UI_MSG_OK, true);


if ($dialogo){
	echo '<script language="javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	} 
	
if ($Aplic->profissional && getParam($_REQUEST, 'uuid', null)){
	$sql = new BDConsulta;
	$sql->adTabela('forum_gestao');
	$sql->adCampo('forum_gestao.*');
	$sql->adOnde('forum_gestao_forum='.(int)(int)$obj->forum_id);
	$sql->adOrdem('forum_gestao_ordem ASC');
	$linha=$sql->linha();
	$sql->limpar();
	
	$sql->adTabela('forum_gestao');
	$sql->adCampo('count(forum_gestao_id)');
	$sql->adOnde('forum_gestao_forum='.(int)$obj->forum_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	
	if ($linha['forum_gestao_tarefa'] && $qnt==1) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['forum_gestao_tarefa'];
	elseif ($linha['forum_gestao_projeto'] && $qnt==1) $endereco='m=projetos&a=ver&projeto_id='.$linha['forum_gestao_projeto'];
	elseif ($linha['forum_gestao_perspectiva'] && $qnt==1) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['forum_gestao_perspectiva'];
	elseif ($linha['forum_gestao_tema'] && $qnt==1) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['forum_gestao_tema'];
	elseif ($linha['forum_gestao_objetivo'] && $qnt==1) $endereco='m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$linha['forum_gestao_objetivo'];
	elseif ($linha['forum_gestao_fator'] && $qnt==1) $endereco='m=praticas&a=fator_ver&pg_fator_critico_id='.$linha['forum_gestao_fator'];
	elseif ($linha['forum_gestao_estrategia'] && $qnt==1) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['forum_gestao_estrategia'];
	elseif ($linha['forum_gestao_meta'] && $qnt==1) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['forum_gestao_meta'];
	elseif ($linha['forum_gestao_pratica'] && $qnt==1) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['forum_gestao_pratica'];
	elseif ($linha['forum_gestao_indicador'] && $qnt==1) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['forum_gestao_indicador'];
	elseif ($linha['forum_gestao_acao'] && $qnt==1) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['forum_gestao_acao'];
	elseif ($linha['forum_gestao_canvas'] && $qnt==1) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['forum_gestao_canvas'];
	elseif ($linha['forum_gestao_risco'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['forum_gestao_risco'];
	elseif ($linha['forum_gestao_risco_resposta'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['forum_gestao_risco_resposta'];
	elseif ($linha['forum_gestao_calendario'] && $qnt==1) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['forum_gestao_calendario'];
	elseif ($linha['forum_gestao_monitoramento'] && $qnt==1) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['forum_gestao_monitoramento'];
	elseif ($linha['forum_gestao_ata'] && $qnt==1) $endereco='m=atas&a=ata_ver&ata_id='.$linha['forum_gestao_ata'];
	elseif ($linha['forum_gestao_swot'] && $qnt==1) $endereco='m=swot&a=swot_ver&swot_id='.$linha['forum_gestao_swot'];
	elseif ($linha['forum_gestao_operativo'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['forum_operativo'];
	elseif ($linha['forum_gestao_instrumento'] && $qnt==1) $endereco='m=recursos&a=instrumento_ver&instrumento_id='.$linha['forum_gestao_instrumento'];
	elseif ($linha['forum_gestao_recurso'] && $qnt==1) $endereco='m=recursos&a=ver&recurso_id='.$linha['forum_gestao_recurso'];
	elseif ($linha['forum_gestao_problema'] && $qnt==1) $endereco='m=problema&a=problema_ver&problema_id='.$linha['forum_gestao_problema'];
	elseif ($linha['forum_gestao_demanda'] && $qnt==1) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['forum_gestao_demanda'];
	elseif ($linha['forum_gestao_programa'] && $qnt==1) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['forum_gestao_programa'];
	elseif ($linha['forum_gestao_evento'] && $qnt==1) $endereco='m=calendario&a=ver&evento_id='.$linha['forum_gestao_evento'];
	elseif ($linha['forum_gestao_link'] && $qnt==1) $endereco='m=links&a=ver&link_id='.$linha['forum_gestao_link'];
	elseif ($linha['forum_gestao_avaliacao'] && $qnt==1) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['forum_gestao_avaliacao'];
	elseif ($linha['forum_gestao_tgn'] && $qnt==1) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['forum_gestao_tgn'];
	elseif ($linha['forum_gestao_brainstorm'] && $qnt==1) $endereco='m=praticas&a=brainstorm_pro_ver&brainstorm_id='.$linha['forum_gestao_brainstorm'];
	elseif ($linha['forum_gestao_gut'] && $qnt==1) $endereco='m=praticas&a=gut_pro_ver&gut_id='.$linha['forum_gestao_gut'];
	elseif ($linha['forum_gestao_causa_efeito'] && $qnt==1) $endereco='m=praticas&a=causa_efeito_pro_ver&causa_efeito_id='.$linha['forum_gestao_causa_efeito'];
	elseif ($linha['forum_gestao_arquivo'] && $qnt==1) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['forum_gestao_arquivo'];
	elseif ($linha['forum_gestao_checklist'] && $qnt==1) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['forum_gestao_checklist'];
	elseif ($linha['forum_gestao_agenda'] && $qnt==1) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['forum_gestao_agenda'];
	elseif ($linha['forum_gestao_agrupamento'] && $qnt==1) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['forum_gestao_agrupamento'];
	elseif ($linha['forum_gestao_patrocinador'] && $qnt==1) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['forum_gestao_patrocinador'];
	elseif ($linha['forum_gestao_template'] && $qnt==1) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['forum_gestao_template'];
	elseif ($linha['forum_gestao_painel'] && $qnt==1) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['forum_gestao_painel'];
	elseif ($linha['forum_gestao_painel_odometro'] && $qnt==1) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['forum_gestao_painel_odometro'];
	elseif ($linha['forum_gestao_painel_composicao'] && $qnt==1) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['forum_gestao_painel_composicao'];
	elseif ($linha['forum_gestao_tr'] && $qnt==1) $endereco='m=tr&a=tr_ver&tr_id='.$linha['forum_gestao_tr'];
	elseif ($linha['forum_gestao_me'] && $qnt==1) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['forum_gestao_me'];
	else $endereco='m=foruns&a=ver&forum_id='.$obj->forum_id;
	$Aplic->redirecionar($endereco);
	}
else $Aplic->redirecionar('m=foruns&a=ver&forum_id='.$obj->forum_id);


?>