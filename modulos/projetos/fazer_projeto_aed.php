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
$del = getParam($_REQUEST, 'del', 0);
$projeto_id = getParam($_REQUEST, 'projeto_id', null);
$nao_eh_novo = getParam($_REQUEST, 'projeto_id', null);
$wbs=getParam($_REQUEST, 'wbs', 0);

include_once BASE_DIR.'/modulos/tarefas/funcoes.php';
//permissoes
if ($del) {
	//checar permissao excluir projeto
	$objeto = new CProjeto();
	$objeto->load($projeto_id);
	if (!$podeExcluir || !permiteEditar($objeto->projeto_acesso,$projeto_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif ($nao_eh_novo) {
		//checar permissao editar projeto
	$objeto = new CProjeto();
	$objeto->load($projeto_id);	
		
	if (!$podeEditar || !permiteEditar($objeto->projeto_acesso,$projeto_id)) 	$Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif (!$Aplic->checarModulo('projetos', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado'); //checar permissao inserir projeto

$obj = new CProjeto();
$msg = '';
$notificar_responsavel = (isset($_REQUEST['email_projeto_responsavel_box']) ? 1 : 0);
$notificar_supervisor = (isset($_REQUEST['email_projeto_supervisor_box']) ? 1 : 0);
$notificar_autoridade = (isset($_REQUEST['email_projeto_autoridade_box']) ? 1 : 0);
$notificar_cliente = (isset($_REQUEST['email_projeto_cliente_box']) ? 1 : 0);
$notificar_contatos = (isset($_REQUEST['email_projeto_contatos_box']) ? 1 : 0);
$notificar_designados = (isset($_REQUEST['email_projeto_designados_box']) ? 1 : 0);
$notificar_stakeholders = (isset($_REQUEST['email_projeto_stakeholder_box']) ? 1 : 0);

$email_contatos = getParam($_REQUEST, 'email_contatos', null);
$email_extras=getParam($_REQUEST, 'email_extras', null);
$email_texto=getParam($_REQUEST, 'email_texto', null);


//gravar as modificações	
require_once ($Aplic->getClasseSistema('CampoCustomizados'));


$datas=array('projeto_data_inicio', 'projeto_data_fim');
$superior='projeto_superior';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index');
	}

if ($obj->projeto_data_inicio) {
	$data = new CData($obj->projeto_data_inicio);
	$obj->projeto_data_inicio = $data->format(FMT_TIMESTAMP_MYSQL);
	}
	
if ($obj->projeto_data_fim) {
	$data = new CData($obj->projeto_data_fim);
	$data->setTime(23, 59, 59);
	$obj->projeto_data_fim = $data->format(FMT_TIMESTAMP_MYSQL);
	}
	
if ($obj->projeto_fim_atualizado) {
	$data = new CData($obj->projeto_fim_atualizado);
	$obj->projeto_fim_atualizado = $data->format(FMT_TIMESTAMP_MYSQL);
	}
	
$codigo=$obj->getCodigo();
if ($codigo) $obj->projeto_codigo=$codigo;
	

if ($del) {



	
	
	
	
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=index');
		exit();
		} 
	else {
		if ($Aplic->profissional){
			$obj->projeto_id=$projeto_id;
			$sql = new BDConsulta;
			$sql->adTabela('projeto_observador');
			$sql->adCampo('projeto_observador.*');
			$sql->adOnde('projeto_observador_projeto ='.(int)$projeto_id);
			$lista = $sql->lista();
			$sql->limpar();
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
				if ($linha['projeto_observador_portfolio']){
					$obj= new CProjeto();
					$obj->load($linha['projeto_observador_portfolio']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->$linha['projeto_observador_metodo']();
						}
					}
				elseif ($linha['projeto_observador_programa']){
					if (!($qnt_programa++)) require_once BASE_DIR.'/modulos/projetos/programa_pro.class.php';
					$obj= new CPrograma();
					$obj->load($linha['projeto_observador_programa']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->$linha['projeto_observador_metodo']();
						}
					}
				elseif ($linha['projeto_observador_perspectiva']){
					if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
					$obj= new CPerspectiva();
					$obj->load($linha['projeto_observador_perspectiva']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->$linha['projeto_observador_metodo']();
						}
					}
				elseif ($linha['projeto_observador_tema']){
					if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
					$obj= new CTema();
					$obj->load($linha['projeto_observador_tema']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->$linha['projeto_observador_metodo']();
						}
					}
				elseif ($linha['projeto_observador_objetivo']){
					if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
					$obj= new CObjetivo();
					$obj->load($linha['projeto_observador_objetivo']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->$linha['projeto_observador_metodo']();
						}
					}
				elseif ($linha['projeto_observador_me']){
					if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
					$obj= new CMe();
					$obj->load($linha['projeto_observador_me']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->$linha['projeto_observador_metodo']();
						}
					}	
				elseif ($linha['projeto_observador_fator']){
					if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
					$obj= new CFator();
					$obj->load($linha['projeto_observador_fator']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->$linha['projeto_observador_metodo']();
						}
					}
				elseif ($linha['projeto_observador_estrategia']){
					if (!($qnt_estrategia++)) require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
					$obj= new CEstrategia();
					$obj->load($linha['projeto_observador_estrategia']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->$linha['projeto_observador_metodo']();
						}
					}
				elseif ($linha['projeto_observador_meta']){
					if (!($qnt_meta++)) require_once BASE_DIR.'/modulos/praticas/meta.class.php';
					$obj= new CMeta();
					$obj->load($linha['projeto_observador_meta']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->$linha['projeto_observador_metodo']();
						}
					}
				elseif ($linha['projeto_observador_acao']){
					if (!($qnt_acao++)) require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
					$obj= new CPlanoAcao();
					$obj->load($linha['projeto_observador_acao']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->$linha['projeto_observador_metodo']();
						}
					}
				}
			}	
		
		if ($notificar_responsavel) {
				if ($msg = $obj->notificarResponsavel(1,'gerente', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
				}		
			if ($notificar_supervisor) {
				if ($msg = $obj->notificarResponsavel(1,'supervisor', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
				}
			if ($notificar_autoridade) {
				if ($msg = $obj->notificarResponsavel(1,'autoridade', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
				}		
			if ($notificar_cliente) {
				if ($msg = $obj->notificarResponsavel(1,'cliente', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
				}	
			if ($notificar_stakeholders) {
				if ($msg = $obj->notificar(1,'stakeholders', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
				}		
			if ($notificar_contatos) {
				if ($msg = $obj->notificar(1,'contatos', $email_texto)) $Aplic->setMsg($msg, UI_MSG_ERRO);
				}
			if ($email_contatos) {
				if ($msg = $obj->notificar(1,'outros', $email_texto, $email_contatos)) $Aplic->setMsg($msg, UI_MSG_ERRO);
				}		
			if ($notificar_designados) {
				if ($msg = $obj->notificar(1,'designados', $email_texto)) $Aplic->setMsg($msg, UI_MSG_ERRO);
				}
			if ($email_extras) {
				if ($msg = $obj->notificar(1,'extras', $email_texto, $email_extras)) $Aplic->setMsg($msg, UI_MSG_ERRO);
				}		
			
		$Aplic->setMsg(ucfirst($config['projeto']).' excluíd'.$config['genero_projeto'], UI_MSG_ALERTA);
		
		if (!$wbs) $Aplic->redirecionar('m=projetos&a=index');
		else echo '<script language="javascript">window.close();</script>';
		exit();
		}
	} 
else {
	if (($msg = $obj->armazenar())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=index');
		exit();
		}
	else {
		$nao_eh_novo = getParam($_REQUEST, 'projeto_id', null);
		if (!$obj->projeto_superior) {
			$obj->projeto_superior = $obj->projeto_id;
			$obj->projeto_superior_original = $obj->projeto_id;
			} 
		else {
			$superior_projeto = new CProjeto();
			$superior_projeto->load($obj->projeto_superior);
			$obj->projeto_superior_original = $superior_projeto->projeto_superior_original;
			}
		if (!$obj->projeto_superior_original)	$obj->projeto_superior_original = $obj->projeto_id;
		$obj->armazenar();
		if ($importarTarefa_projetoId = getParam($_REQUEST, 'importarTarefa_projetoId', '0')) {
			$obj->importarTarefas($importarTarefa_projetoId, getParam($_REQUEST, 'importar_data_inicio', ''));
			}

		
		if ($notificar_responsavel) {
			if ($msg = $obj->notificarResponsavel($nao_eh_novo,'gerente', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
			}		
		if ($notificar_supervisor) {
			if ($msg = $obj->notificarResponsavel($nao_eh_novo,'supervisor', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
			}
		if ($notificar_autoridade) {
			if ($msg = $obj->notificarResponsavel($nao_eh_novo,'autoridade', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
			}		
		if ($notificar_cliente) {
			if ($msg = $obj->notificarResponsavel($nao_eh_novo,'cliente', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
			}	
		if ($notificar_stakeholders) {
			if ($msg = $obj->notificar($nao_eh_novo,'stakeholders', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
			}		
		if ($notificar_contatos) {
			if ($msg = $obj->notificar($nao_eh_novo,'contatos', $email_texto)) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}
		if ($email_contatos) {
			if ($msg = $obj->notificar($nao_eh_novo,'outros', $email_texto, $email_contatos)) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}		
		if ($notificar_designados) {
			if ($msg = $obj->notificar($nao_eh_novo,'designados', $email_texto)) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}
		if ($email_extras) {
			if ($msg = $obj->notificar($nao_eh_novo,'extras', $email_texto, $email_extras)) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}	
		
		$Aplic->setMsg($nao_eh_novo ? ucfirst($config['projeto']).' atualizad'.$config['genero_projeto'] : ucfirst($config['projeto']).' inserid'.$config['genero_projeto'], UI_MSG_OK);
		}
	

	$obj->setSequencial();
		
	if (!$wbs) $Aplic->redirecionar('m=projetos&a=ver&projeto_id='.$obj->projeto_id);
	else echo '<script language="javascript">window.close();</script>';
	}
$Aplic->redirecionar('m=projetos');	
exit();
?>