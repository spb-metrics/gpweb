<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

require_once (BASE_DIR.'/modulos/praticas/plano_acao.class.php');

$sql = new BDConsulta;

$_REQUEST['plano_acao_ativo']=(isset($_REQUEST['plano_acao_ativo']) ? 1 : 0);

$del = intval(getParam($_REQUEST, 'del', 0));
$plano_acao_id = getParam($_REQUEST, 'plano_acao_id', null);

$uuid = getParam($_REQUEST, 'uuid', null);

$comentario=getParam($_REQUEST, 'email_comentario', '');

$plano_acao_calculo_porcentagem_antigo = getParam($_REQUEST, 'plano_acao_calculo_porcentagem_antigo', null);
$plano_acao_percentagem_antigo = getParam($_REQUEST, 'plano_acao_percentagem_antigo', null);

$obj = new CPlanoAcao();
if ($plano_acao_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';
if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=plano_acao_ver&tab=7&plano_acao_id='.$plano_acao_id);
	}
	
	
$codigo=$obj->getCodigo();
if ($codigo) $obj->plano_acao_codigo=$codigo;


$Aplic->setMsg('Plano de Ação');
if ($del) {
	
	

	$obj->load($plano_acao_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		} 
	else {
		
		if ($Aplic->profissional){
			$sql->adTabela('plano_acao_observador');
			$sql->adCampo('plano_acao_observador.*');
			$sql->adOnde('plano_acao_observador_plano_acao ='.(int)$plano_acao_id);
			$lista = $sql->lista();
			$sql->limpar();
			
			$qnt_projeto=0;
			$qnt_programa=0;
			$qnt_perspectiva=0;
			$qnt_tema=0;
			$qnt_objetivo=0;
			$qnt_me=0;
			$qnt_fator=0;
			$qnt_estrategia=0;
			$qnt_meta=0;
			$qnt_acao=0;
			
			foreach($lista as $linha){
				
				if ($linha['plano_acao_observador_projeto']){
					if (!($qnt_projeto++)) require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
					$obj= new CProjeto();
					$obj->load($linha['plano_acao_observador_projeto']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->$linha['plano_acao_observador_metodo'];
						}
					}	
				elseif ($linha['plano_acao_observador_programa']){
					if (!($qnt_programa++)) require_once BASE_DIR.'/modulos/projetos/programa_pro.class.php';
					$obj= new CPrograma();
					$obj->load($linha['plano_acao_observador_programa']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->$linha['plano_acao_observador_metodo'];
						}
					}	
				elseif ($linha['plano_acao_observador_perspectiva']){
					if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
					$obj= new CPerspectiva();
					$obj->load($linha['plano_acao_observador_perspectiva']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->$linha['plano_acao_observador_metodo'];
						}
					}	
				elseif ($linha['plano_acao_observador_tema']){
					if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
					$obj= new CTema();
					$obj->load($linha['plano_acao_observador_tema']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->$linha['plano_acao_observador_metodo'];
						}
					}	
				elseif ($linha['plano_acao_observador_objetivo']){
					if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
					$obj= new CObjetivo();
					$obj->load($linha['plano_acao_observador_objetivo']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->$linha['plano_acao_observador_metodo'];
						}
					}	
				elseif ($linha['plano_acao_observador_me']){
					if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
					$obj= new CMe();
					$obj->load($linha['plano_acao_observador_me']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->$linha['plano_acao_observador_metodo'];
						}
					}		
				elseif ($linha['plano_acao_observador_fator']){
					if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
					$obj= new CFator();
					$obj->load($linha['plano_acao_observador_fator']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->$linha['plano_acao_observador_metodo'];
						}
					}	
				elseif ($linha['plano_acao_observador_estrategia']){
					if (!($qnt_estrategia++)) require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
					$obj= new CEstrategia();
					$obj->load($linha['plano_acao_observador_estrategia']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->$linha['plano_acao_observador_metodo'];
						}
					}	
				elseif ($linha['plano_acao_observador_meta']){
					if (!($qnt_meta++)) require_once BASE_DIR.'/modulos/praticas/meta.class.php';
					$obj= new CMeta();
					$obj->load($linha['plano_acao_observador_meta']);
					if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
						$obj->$linha['plano_acao_observador_metodo'];
						}
					}				
				}
			}
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		}
	if ($dialogo){
		echo '<script language="javascript">';
		echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
		echo 'else self.close();';
		echo '</script>';	
		} 
	$Aplic->redirecionar('m=praticas&a=plano_acao_lista');
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	if (getParam($_REQUEST, 'email_responsavel', 0)) $obj->notificarResponsavel($comentario, $plano_acao_id);
	if (getParam($_REQUEST, 'email_designados', 0)) $obj->notificarDesignados($comentario, $plano_acao_id);
	if (getParam($_REQUEST, 'email_contatos', 0)) $obj->notificarContatos($comentario, $plano_acao_id);
	$Aplic->setMsg($plano_acao_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
	
	

if ($Aplic->profissional && $plano_acao_percentagem_antigo!=$obj->plano_acao_percentagem) $obj->disparo_observador('fisico');

	
	
	
if ($dialogo){
	echo '<script language="javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	}
if ($Aplic->profissional && $uuid){
	
	$sql->adTabela('plano_acao_gestao');
	$sql->adCampo('plano_acao_gestao.*');
	$sql->adOnde('plano_acao_gestao_acao='.(int)$plano_acao_id);
	$sql->adOrdem('plano_acao_gestao_ordem ASC');
	$linha=$sql->linha();
	$sql->limpar();
	
	$sql->adTabela('plano_acao_gestao');
	$sql->adCampo('count(plano_acao_gestao_id)');
	$sql->adOnde('plano_acao_gestao_acao='.(int)$plano_acao_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	
	if ($linha['plano_acao_gestao_tarefa'] && $qnt==1) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['plano_acao_gestao_tarefa'];
	elseif ($linha['plano_acao_gestao_projeto'] && $qnt==1) $endereco='m=projetos&a=ver&projeto_id='.$linha['plano_acao_gestao_projeto'];
	elseif ($linha['plano_acao_gestao_perspectiva'] && $qnt==1) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['plano_acao_gestao_perspectiva'];
	elseif ($linha['plano_acao_gestao_tema'] && $qnt==1) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['plano_acao_gestao_tema'];
	elseif ($linha['plano_acao_gestao_objetivo'] && $qnt==1) $endereco='m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$linha['plano_acao_gestao_objetivo'];
	elseif ($linha['plano_acao_gestao_fator'] && $qnt==1) $endereco='m=praticas&a=fator_ver&pg_fator_critico_id='.$linha['plano_acao_gestao_fator'];
	elseif ($linha['plano_acao_gestao_estrategia'] && $qnt==1) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['plano_acao_gestao_estrategia'];
	elseif ($linha['plano_acao_gestao_meta'] && $qnt==1) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['plano_acao_gestao_meta'];
	elseif ($linha['plano_acao_gestao_pratica'] && $qnt==1) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['plano_acao_gestao_pratica'];
	elseif ($linha['plano_acao_gestao_indicador'] && $qnt==1) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['plano_acao_gestao_indicador'];
	elseif ($linha['plano_acao_gestao_canvas'] && $qnt==1) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['plano_acao_gestao_canvas'];
	elseif ($linha['plano_acao_gestao_risco'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['plano_acao_gestao_risco'];
	elseif ($linha['plano_acao_gestao_risco_resposta'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['plano_acao_gestao_risco_resposta'];
	elseif ($linha['plano_acao_gestao_calendario'] && $qnt==1) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['plano_acao_gestao_calendario'];
	elseif ($linha['plano_acao_gestao_monitoramento'] && $qnt==1) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['plano_acao_gestao_monitoramento'];
	elseif ($linha['plano_acao_gestao_ata'] && $qnt==1) $endereco='m=atas&a=ata_ver&ata_id='.$linha['plano_acao_gestao_ata'];
	elseif ($linha['plano_acao_gestao_swot'] && $qnt==1) $endereco='m=swot&a=swot_ver&swot_id='.$linha['plano_acao_gestao_swot'];
	elseif ($linha['plano_acao_gestao_operativo'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['plano_acao_operativo'];
	elseif ($linha['plano_acao_gestao_instrumento'] && $qnt==1) $endereco='m=recursos&a=instrumento_ver&instrumento_id='.$linha['plano_acao_gestao_instrumento'];
	elseif ($linha['plano_acao_gestao_recurso'] && $qnt==1) $endereco='m=recursos&a=ver&recurso_id='.$linha['plano_acao_gestao_recurso'];
	elseif ($linha['plano_acao_gestao_problema'] && $qnt==1) $endereco='m=problema&a=problema_ver&problema_id='.$linha['plano_acao_gestao_problema'];
	elseif ($linha['plano_acao_gestao_demanda'] && $qnt==1) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['plano_acao_gestao_demanda'];
	elseif ($linha['plano_acao_gestao_programa'] && $qnt==1) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['plano_acao_gestao_programa'];
	elseif ($linha['plano_acao_gestao_evento'] && $qnt==1) $endereco='m=calendario&a=ver&evento_id='.$linha['plano_acao_gestao_evento'];
	elseif ($linha['plano_acao_gestao_link'] && $qnt==1) $endereco='m=links&a=ver&link_id='.$linha['plano_acao_gestao_link'];
	elseif ($linha['plano_acao_gestao_avaliacao'] && $qnt==1) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['plano_acao_gestao_avaliacao'];
	elseif ($linha['plano_acao_gestao_tgn'] && $qnt==1) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['plano_acao_gestao_tgn'];
	elseif ($linha['plano_acao_gestao_brainstorm'] && $qnt==1) $endereco='m=praticas&a=brainstorm_pro_ver&brainstorm_id='.$linha['plano_acao_gestao_brainstorm'];
	elseif ($linha['plano_acao_gestao_gut'] && $qnt==1) $endereco='m=praticas&a=gut_pro_ver&gut_id='.$linha['plano_acao_gestao_gut'];
	elseif ($linha['plano_acao_gestao_causa_efeito'] && $qnt==1) $endereco='m=praticas&a=causa_efeito_pro_ver&causa_efeito_id='.$linha['plano_acao_gestao_causa_efeito'];
	elseif ($linha['plano_acao_gestao_arquivo'] && $qnt==1) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['plano_acao_gestao_arquivo'];
	elseif ($linha['plano_acao_gestao_forum'] && $qnt==1) $endereco='m=foruns&a=ver&forum_id='.$linha['plano_acao_gestao_forum'];
	elseif ($linha['plano_acao_gestao_checklist'] && $qnt==1) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['plano_acao_gestao_checklist'];
	elseif ($linha['plano_acao_gestao_agenda'] && $qnt==1) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['plano_acao_gestao_agenda'];
	elseif ($linha['plano_acao_gestao_agrupamento'] && $qnt==1) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['plano_acao_gestao_agrupamento'];
	elseif ($linha['plano_acao_gestao_patrocinador'] && $qnt==1) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['plano_acao_gestao_patrocinador'];
	elseif ($linha['plano_acao_gestao_template'] && $qnt==1) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['plano_acao_gestao_template'];
	elseif ($linha['plano_acao_gestao_painel'] && $qnt==1) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['plano_acao_gestao_painel'];
	elseif ($linha['plano_acao_gestao_painel_odometro'] && $qnt==1) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['plano_acao_gestao_painel_odometro'];
	elseif ($linha['plano_acao_gestao_painel_composicao'] && $qnt==1) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['plano_acao_gestao_painel_composicao'];
	elseif ($linha['plano_acao_gestao_tr'] && $qnt==1) $endereco='m=tr&a=tr_ver&tr_id='.$linha['plano_acao_gestao_tr'];
	elseif ($linha['plano_acao_gestao_me'] && $qnt==1) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['plano_acao_gestao_me'];
	else $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$obj->plano_acao_id;
	$Aplic->redirecionar($endereco);
	}
elseif($Aplic->profissional) $Aplic->redirecionar('m=praticas&a=plano_acao_ver&plano_acao_id='.$obj->plano_acao_id);
elseif ($obj->plano_acao_tarefa) $Aplic->redirecionar('m=tarefas&a=ver&tab=4&tarefa_id='.(int)$obj->plano_acao_tarefa); 
elseif ($obj->plano_acao_projeto) $Aplic->redirecionar('m=projetos&a=ver&tab=5&projeto_id='.(int)$obj->plano_acao_projeto); 	
elseif ($obj->plano_acao_indicador) $Aplic->redirecionar('m=praticas&a=indicador_ver&tab=6&pratica_indicador_id='.(int)$obj->plano_acao_indicador); 
elseif ($obj->plano_acao_pratica) $Aplic->redirecionar('m=praticas&a=pratica_ver&tab=6&pratica_id='.$obj->plano_acao_pratica); 
elseif ($obj->plano_acao_perspectiva) $Aplic->redirecionar('m=praticas&a=perspectiva_ver&tab=1&pg_perspectiva_id='.$obj->plano_acao_perspectiva);
elseif ($obj->plano_acao_canvas) $Aplic->redirecionar('m=praticas&a=canvas_pro_ver&tab=2&canvas_id='.$obj->plano_acao_canvas);
elseif ($obj->plano_acao_tema) $Aplic->redirecionar('m=praticas&a=tema_ver&tab=3&tema_id='.$obj->plano_acao_tema);
elseif ($obj->plano_acao_objetivo) $Aplic->redirecionar('m=praticas&a=obj_estrategico_ver&tab=3&pg_objetivo_estrategico_id='.$obj->plano_acao_objetivo);
elseif ($obj->plano_acao_fator) $Aplic->redirecionar('m=praticas&a=fator_ver&tab=3&pg_fator_critico_id='.$obj->plano_acao_fator);
elseif ($obj->plano_acao_estrategia) $Aplic->redirecionar('m=praticas&a=estrategia_ver&tab=3&pg_estrategia_id='.$obj->plano_acao_estrategia);
elseif ($obj->plano_acao_meta) $Aplic->redirecionar('m=praticas&a=meta_ver&tab=3&pg_meta_id='.$obj->plano_acao_meta);
else $Aplic->redirecionar('m=praticas&a=plano_acao_ver&plano_acao_id='.$obj->plano_acao_id);

?>