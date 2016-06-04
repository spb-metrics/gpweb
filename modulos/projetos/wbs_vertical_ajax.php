<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

require_once BASE_DIR.'/modulos/tarefas/tarefas.class.php';

require_once BASE_DIR.'/modulos/tarefas/funcoes.php';

if($Aplic->profissional){
  require_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';
  require_once BASE_DIR.'/modulos/projetos/wbs_utilitarios_pro.php';
  }

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function painel_filtro($visao){
	global $Aplic;
	if ($visao=='none') $painel_filtro=0;
	else  $painel_filtro=1;
	$Aplic->setEstado('painel_filtro',$painel_filtro);
	}
$xajax->registerFunction("painel_filtro");



function carregar_projeto_pro( $projeto_id, $baseline_id = 0){
    $cache = CTarefaCache::getInstance();
    return $cache->exibirTarefasAgil($projeto_id, $baseline_id);
    }

$xajax->register(XAJAX_FUNCTION,'carregar_projeto_pro',array('mode' => "'asynchronous'"));




function excluir_tarefa($tarefa_id=0, $projeto_id=0){
	global $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_superior');
	$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
	$sql->adOnde('tarefa_superior != '.(int)$tarefa_id);
	$superior_atual=$sql->Resultado();
	$sql->Limpar();
	if ($Aplic->profissional) renumerar_tarefas_apos_exclusao($tarefa_id, $projeto_id, $superior_atual);
	$obj = new CTarefa();
	$obj->load($tarefa_id);
	$obj->excluir();

	if ($superior_atual) calcular_superior($superior_atual);
	atualizar_percentagem($projeto_id);
	}
$xajax->registerFunction("excluir_tarefa");



function projeto_existe($nome=''){
	$sql = new BDConsulta;
	$sql->adTabela('projetos');
	$sql->adCampo('count(projeto_id)');
	$sql->adOnde('projeto_nome = "'.$nome.'"');
	$existe=$sql->Resultado();
	$sql->Limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_projeto","value", $existe);
	return $objResposta;
	}

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}


function criarProjeto($cia_id=0, $nome_projeto='', $usuario_id=0){
	global $bd, $Aplic, $config;

	$data=calculo_data_final_periodo(date('Y-m-d').' 00:00:00',0,$cia_id);

	$sql = new BDConsulta;
	$sql->adTabela('projetos');
	$sql->adInserir('projeto_nome', previnirXSS(utf8_decode($nome_projeto)));
	$sql->adInserir('projeto_responsavel', ($usuario_id ? $usuario_id : $Aplic->usuario_id));
	$sql->adInserir('projeto_cia', $cia_id);
	$sql->adInserir('projeto_status', 1);
	$sql->adInserir('projeto_data_inicio',$data);
	$sql->adInserir('projeto_data_fim',$data);
    $sql->adInserir('projeto_acesso', (int)$config['nivel_acesso_padrao']);
	$sql->exec();
	$projeto_id=$bd->Insert_ID('projetos','projeto_id');
	$sql->Limpar();

	$sql->adTabela('projetos');
	$sql->adAtualizar('projeto_superior_original', $projeto_id);
	$sql->adAtualizar('projeto_superior', $projeto_id);
	$sql->adOnde('projeto_id = '.$projeto_id);
	$sql->exec();
	$sql->Limpar();



	$objResposta = new xajaxResponse();
	$objResposta->assign("projeto_id","value", $projeto_id);
	return $objResposta;

	}



function inserir_tarefa($tarefa_projeto=0, $tarefa_superior=0, $nome=''){
	global $Aplic, $bd, $config;
	$sql = new BDConsulta;

	$sql->adTabela('projetos');
	$sql->adCampo('projeto_cia');
	$sql->adOnde('projeto_id='.$tarefa_projeto);
	$cia_id=$sql->resultado();
	$sql->limpar();

	if ($Aplic->profissional){
        $cache = CTarefaCache::getInstance();
        $nova_tarefa_id = $cache->inserirTarefa($tarefa_projeto, $tarefa_superior, 'sub', $nome, false);
        $cache->flush();
		}
	else{
        $data = date('Y-m-d').' 00:00:00';
        $tarefa_numeracao=0;

	    $sql->adTabela('tarefas');
	    $sql->adInserir('tarefa_nome', previnirXSS(utf8_decode($nome)));
	    if ($tarefa_superior) $sql->adInserir('tarefa_superior', $tarefa_superior);
	    $sql->adInserir('tarefa_projeto', $tarefa_projeto);
        $sql->adInserir('tarefa_inicio_manual',$data);
        $sql->adInserir('tarefa_fim_manual', $data);
	    $sql->adInserir('tarefa_inicio',$data);
	    $sql->adInserir('tarefa_fim', $data);
	    $sql->adInserir('tarefa_marco', 1);
	    $sql->adInserir('tarefa_dinamica', 0);
	    $sql->adInserir('tarefa_dono', $Aplic->usuario_id);
	    $sql->adInserir('tarefa_criador', $Aplic->usuario_id);
	    $sql->adInserir('tarefa_cia', $cia_id);
	    $sql->adInserir('tarefa_percentagem_data', date('Y-m-d H:i:s'));
        $sql->adInserir('tarefa_acesso', (int)$config['nivel_acesso_padrao']);
	    if ($tarefa_numeracao){
            $sql->adInserir('tarefa_numeracao', $tarefa_numeracao);
            }
	    $sql->exec();
	    $nova_tarefa_id=$bd->Insert_ID('tarefas','tarefa_id');
	    $sql->Limpar();

	    if (!$tarefa_superior){
		    $sql->adTabela('tarefas');
		    $sql->adAtualizar('tarefa_superior', $nova_tarefa_id);
		    $sql->adOnde('tarefa_id = '.$nova_tarefa_id);
		    $sql->exec();
		    $sql->Limpar();
		    }
	    else{
		    $sql->adTabela('tarefas');
		    $sql->adAtualizar('tarefa_marco', 0);
		    $sql->adAtualizar('tarefa_dinamica', 1);
		    $sql->adOnde('tarefa_id = '.$tarefa_superior);
		    $sql->exec();
		    $sql->Limpar();
		    }
        }

	$objResposta = new xajaxResponse();
	$objResposta->assign("nova_tarefa_id","value", $nova_tarefa_id);
	return $objResposta;
	}


function renomear_tarefa($tarefa_id=0, $nome){
	$sql = new BDConsulta;
	$sql->adTabela('tarefas');
	$sql->adAtualizar('tarefa_nome', previnirXSS(utf8_decode($nome)));
	$sql->adOnde('tarefa_id = '.$tarefa_id);
	$sql->exec();
	$sql->Limpar();
	return true;
	}

function renomear_projeto($projeto_id=0, $nome){
	$sql = new BDConsulta;
	$sql->adTabela('projetos');
	$sql->adAtualizar('projeto_nome', previnirXSS(utf8_decode($nome)));
	$sql->adOnde('projeto_id = '.$projeto_id);
	$sql->exec();
	$sql->Limpar();
	return true;
	}

function superior_tarefa($tarefa_id=0, $tarefa_superior=0){
	global $Aplic;

    if($Aplic->profissional){
        $cache = CTarefaCache::getInstance();
        $ok = $cache->mudarPosicaoTarefa($tarefa_superior, $tarefa_id);
        if($ok) $cache->flush();
        return $ok;
        }

	$sql = new BDConsulta;

	//checar se a tarefa a qual estava subordinada tem outras filho, senão deixa de ser dinamica
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_superior');
	$sql->adOnde('tarefa_id = '.$tarefa_id);
	$superior_atual=$sql->Resultado();
	$sql->Limpar();

	if ($superior_atual!=$tarefa_id){
		$sql->adTabela('tarefas');
		$sql->adCampo('count(tarefa_id) AS soma');
		$sql->adOnde('tarefa_superior = '.$superior_atual);
		$sql->adOnde('tarefa_id != '.$superior_atual);
		$qnt_subordinadas=$sql->Resultado();
		$sql->Limpar();

		if ($qnt_subordinadas<2){
			$sql->adTabela('tarefas');
			$sql->adAtualizar('tarefa_dinamica', 0);
			$sql->adOnde('tarefa_id = '.$superior_atual);
			$sql->exec();
			$sql->Limpar();
			}
		}

	if ($tarefa_id!=$tarefa_superior){
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_marco', 0);
		$sql->adAtualizar('tarefa_dinamica', 1);
		$sql->adOnde('tarefa_id = '.$tarefa_superior);
		$sql->exec();
		$sql->Limpar();
		}


	if ($Aplic->profissional){
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_projeto');
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$tarefa_projeto=$sql->Resultado();
		$sql->Limpar();
		$tarefa_numeracao=numeracao_nova_tarefa($tarefa_projeto, ($tarefa_superior!=$tarefa_id ? $tarefa_superior : null));
		renumerar_tarefas_apos_exclusao($tarefa_id, $tarefa_projeto, $superior_atual);
		}
	else $tarefa_numeracao=0;



	$sql->adTabela('tarefas');
	$sql->adAtualizar('tarefa_superior', ($tarefa_superior ? $tarefa_superior : $tarefa_id));
	if ($tarefa_numeracao) $sql->adAtualizar('tarefa_numeracao', $tarefa_numeracao);
	$sql->adOnde('tarefa_id = '.$tarefa_id);
	$sql->exec();
	$sql->Limpar();

	verifica_dependencias($tarefa_id);

	if ($superior_atual)  calcular_superior($superior_atual);
	if ($tarefa_superior && ($tarefa_superior!=$superior_atual)) calcular_superior($tarefa_superior);

	return true;
	}

$xajax->registerFunction("projeto_existe");
$xajax->registerFunction("criarProjeto");
$xajax->registerFunction("inserir_tarefa");
$xajax->registerFunction("superior_tarefa");
$xajax->registerFunction("renomear_tarefa");
$xajax->registerFunction("renomear_projeto");
$xajax->registerFunction("selecionar_om_ajax");

$xajax->processRequest();
?>