<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
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

$vetor_tarefas=array();

function ver_debug($vetor){
  $objResposta = new xajaxResponse();
  $objResposta->assign('combo_debug',"innerHTML",utf8_encode($vetor));
  return $objResposta;
  }
$xajax->registerFunction("ver_debug");

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

$xajax->registerFunction("projeto_existe");

function listar_projetos_pro( $cia_id = 0, $departamento_id = 0, $pesquisa = '', $checkbox = 0, $checked = 0, $projeto = 0, $ver_subordinadas = 0, $naoFiltrar = 0, $handler = null){
    $lista = listarProjetosPro($cia_id, $departamento_id, $pesquisa, $checkbox, $checked, $projeto, $ver_subordinadas, $naoFiltrar);
    if($handler){
        $objResposta = new xajaxResponse();
        $objResposta->call($handler, json_encode($lista));
        return $objResposta;
        }
    else return $lista;
    }

$xajax->register(XAJAX_FUNCTION,'listar_projetos_pro',array('mode' => "'asynchronous'"));

function processa_links_pro( $projeto = 0 ){
    $cache = CTarefaCache::getInstance();
    $status = $cache->processaLinks($projeto);
    $objResposta = new xajaxResponse();
    $objResposta->setReturnValue($status);
    return $objResposta;
    }

$xajax->registerFunction('processa_links_pro');


function atualiza_tarefa_externa_pro($projeto_id = 0 , $tarefa_id=0){
  $cache = CTarefaCache::getInstance();
  $cache->load($projeto_id);
    return $cache->atualizaTarefaExternaAgil($tarefa_id);
  }
$xajax->register(XAJAX_FUNCTION,'atualiza_tarefa_externa_pro',array('mode' => "'asynchronous'"));


function listar_tarefas_pro( $projeto = 0, $departamento_id = 0, $pesquisa = '', $checkbox = 0, $checked = 0, $projetoFiltro = 0, $handler = null){
    $lista = listarTarefasPro($projeto, $departamento_id, $pesquisa, $checkbox, $checked, $projetoFiltro);
    if($handler){
        $objResposta = new xajaxResponse();
        $objResposta->call($handler, json_encode($lista));
        return $objResposta;
        }
    else return $lista;
    }

$xajax->register(XAJAX_FUNCTION,'listar_tarefas_pro',array('mode' => "'asynchronous'"));

function listar_usuarios_pro( $cia_id = 0, $departamento_id = 0, $pesquisa = '', $checkbox = 0, $checked = 0, $lista_simples = 0, $handler = null){
    $lista = listarUsuariosPro($cia_id, $departamento_id, $pesquisa, $checkbox, $checked, $lista_simples);
    if($handler){
        $objResposta = new xajaxResponse();
        $objResposta->call($handler, json_encode($lista));
        return $objResposta;
        }
    else return $lista;
    }

$xajax->register(XAJAX_FUNCTION,'listar_usuarios_pro',array('mode' => "'asynchronous'"));

function listar_designados_pro( $tarefa_id = 0, $baseline_id=0, $handler = null){
    $lista = listarDesignadosPro($tarefa_id, $baseline_id);
    if($handler){
        $objResposta = new xajaxResponse();
        $objResposta->call($handler, json_encode($lista));
        return $objResposta;
        }
    else return $lista;
    }

$xajax->register(XAJAX_FUNCTION,'listar_designados_pro',array('mode' => "'asynchronous'"));

function altera_designados_pro($tarefa_id, $usuarios){
    setDesignadosPro($tarefa_id, $usuarios);
    }

$xajax->register(XAJAX_FUNCTION,'altera_designados_pro',array('mode' => "'asynchronous'"));

function listar_cias_pro($handler = null, $vazio = 0, $checkbox = 0, $checked = 0){
    global $Aplic;
  $lista = listarCiasPro($vazio, $checkbox, $checked);
    if($handler){
      $objResposta = new xajaxResponse();
      $objResposta->call($handler, json_encode($lista),$Aplic->usuario_cia);
      return $objResposta;
        }
    else return $lista;
  }

$xajax->register(XAJAX_FUNCTION,'listar_cias_pro',array('mode' => "'asynchronous'"));

function detalhes_usuario_pro($usuario_id, $handler = null){
    if(is_array($usuario_id)){
        $data = array();
        foreach($usuario_id as $id){
            $data[] = detalhesUsuarioPro($id);
            }
        if($handler){
            $objResposta = new xajaxResponse();
            $objResposta->call($handler, json_encode($data));
            return $objResposta;
            }
        else return $data;
        }
    else{

        $data = detalhesUsuarioPro($usuario_id);
        if($handler){
            $objResposta = new xajaxResponse();
            $objResposta->call($handler, $data);
            return $objResposta;
            }
        else return $data;
        }
    }

$xajax->register(XAJAX_FUNCTION,'detalhes_usuario_pro',array('mode' => "'asynchronous'"));

function altera_responsavel_pro($tarefa_id, $usuario_id, $handler = null){
    setResponsavelPro($tarefa_id, $usuario_id);
    if($handler && $usuario_id){
        $data = detalhesUsuarioPro($usuario_id);
        $objResposta = new xajaxResponse();
        $objResposta->call($handler, $data);
        return $objResposta;
        }
    }

$xajax->register(XAJAX_FUNCTION,'altera_responsavel_pro',array('mode' => "'asynchronous'"));

function excluir_tarefa_pro($projeto_id=0, $tarefa_id=0){
  $cache = CTarefaCache::getInstance();
  return $cache->excluirTarefaAgil($projeto_id, $tarefa_id);
  }
$xajax->register(XAJAX_FUNCTION,'excluir_tarefa_pro',array('mode' => "'asynchronous'"));

function mudar_predecessoras_pro($tarefa_id=0, $valores=array()){
  $cache = CTarefaCache::getInstance();
  return $cache->mudarPredecessorasAgil($tarefa_id, $valores);
  }
$xajax->register(XAJAX_FUNCTION,'mudar_predecessoras_pro',array('mode' => "'asynchronous'"));

function inserir_link_projeto_pro($projeto=0, $projeto_lnk = 0){
  $cache = CTarefaCache::getInstance();
    return $cache->inserirLinkProjetoAgil($projeto, $projeto_lnk);
  }
$xajax->registerFunction("inserir_link_projeto_pro");

function inserir_link_tarefa_pro($projeto=0, $superior, $tarefa_lnk = 0){
  $cache = CTarefaCache::getInstance();
    return $cache->inserirLinkTarefaAgil($projeto, $superior, $tarefa_lnk);
  }
$xajax->registerFunction("inserir_link_tarefa_pro");

function inserir_tarefa_pro($tarefa_projeto=0, $tarefa_referencia=0, $action = 'sub', $nome='', $inicio='', $duracao=0){
  $cache = CTarefaCache::getInstance();
  return $cache->inserirTarefaAgil($tarefa_projeto, $tarefa_referencia, $action, $nome, $inicio, $duracao);
  }
$xajax->registerFunction("inserir_tarefa_pro");

function renomear_tarefa_pro($tarefa_id=0, $nome){
  $sql = new BDConsulta;
  $sql->adTabela('tarefas');
  $sql->adAtualizar('tarefa_nome', previnirXSS(utf8_decode($nome)));
  $sql->adOnde('tarefa_id = '.(int)$tarefa_id);
  $sql->exec();
  $sql->Limpar();
  return true;
  }
$xajax->register(XAJAX_FUNCTION,'renomear_tarefa_pro',array('mode' => "'asynchronous'"));

function mudar_sucessoras_pro($tarefa_id=0, $dependencias=array()){
  $cache = CTarefaCache::getInstance();
  return $cache->mudarSucessorasAgil($tarefa_id, $dependencias);
  }
$xajax->register(XAJAX_FUNCTION,'mudar_sucessoras_pro',array('mode' => "'asynchronous'"));

function renomear_projeto_pro($projeto_id=0, $nome){
  $sql = new BDConsulta;
  $sql->adTabela('projetos');
  $sql->adCampo('count(projeto_id)');
  $sql->adOnde('projeto_nome = "'.$nome.'"');
  $existe=$sql->Resultado();
  $sql->Limpar();
  $objResposta = new xajaxResponse();
  if($existe){
    $objResposta->setReturnValue(false);
    return $objResposta;
  }

  $sql->adTabela('projetos');
  $sql->adAtualizar('projeto_nome', previnirXSS(utf8_decode($nome)));
  $sql->adOnde('projeto_id = '.(int)$projeto_id);
  $sql->exec();
  $sql->Limpar();
  $objResposta->setReturnValue(true);
  return $objResposta;
}
$xajax->registerFunction("renomear_projeto_pro");

function exibir_tarefas_pro($projeto_id=0, $baseline_id=0){
    $cache = CTarefaCache::getInstance();
    return $cache->exibirTarefasAgil($projeto_id, $baseline_id, true);
  }
$xajax->register(XAJAX_FUNCTION,'exibir_tarefas_pro',array('mode' => "'asynchronous'"));

function mudar_posicao_pro($superior = 0, $tarefa_id=0, $action='inserir', $posicao = 1){
    $cache = CTarefaCache::getInstance();
    return $cache->mudarPosicaoTarefaAgil($superior, $tarefa_id, $action, $posicao);
    }
$xajax->register(XAJAX_FUNCTION,'mudar_posicao_pro',array('mode' => "'asynchronous'"));

function mudar_inicio_pro($projeto_id, $tarefa_id, $inicio, $dias=0){
    $cache = CTarefaCache::getInstance();
    return $cache->mudarInicioTarefaAgil($projeto_id, $tarefa_id, $inicio, $dias);
  }
$xajax->register(XAJAX_FUNCTION,'mudar_inicio_pro',array('mode' => "'asynchronous'"));

function mudar_duracao_pro($projeto_id, $tarefa_id, $inicio, $dias=0){
  $cache = CTarefaCache::getInstance();
    return $cache->mudarHorasTarefaAgil($projeto_id, $tarefa_id, $inicio, $dias);
  }
$xajax->register(XAJAX_FUNCTION,'mudar_duracao_pro',array('mode' => "'asynchronous'"));

function mudar_percentagem_pro( $projeto_id, $tarefa_id, $percentagem){
  $cache = CTarefaCache::getInstance();
    return $cache->mudarPercentagemTarefaAgil($projeto_id, $tarefa_id, $percentagem);
  }
$xajax->register(XAJAX_FUNCTION,'mudar_percentagem_pro',array('mode' => "'asynchronous'"));

function mudar_datas_pro($projeto_id, $tarefa_id, $inicio, $fim){
    $cache = CTarefaCache::getInstance();
    return $cache->mudarDatasTarefaAgil($projeto_id, $tarefa_id, $inicio, $fim);
  }
$xajax->register(XAJAX_FUNCTION,'mudar_datas_pro',array('mode' => "'asynchronous'"));

function excluir_tarefa($tarefa_id=0, $projeto_id=0, $tarefa_pai = 0, $retornaDados = true){
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

function mudar_dependencia($tarefa_id=0, $valores='', $vetor_tarefas=''){

  $circular=0;
  $conversao_tarefa=array();
  $vetor_tarefas=explode(';', $vetor_tarefas);

  foreach ($vetor_tarefas as $linha) {
    $valor=explode(':', $linha);
    if (isset($valor[1]) && isset($valor[0])) $conversao_tarefa[$valor[1]]=$valor[0];
    }

  $valores=str_replace(',',';', $valores);
  $valores=str_replace(' ','', $valores);
  $valores=strtoupper($valores);
  $valores=explode(';',$valores);

  $saida='';
  $sql = new BDConsulta;
  $sql->setExcluir('tarefa_dependencias');
  $sql->adOnde('dependencias_tarefa_id = '.(int)$tarefa_id);
  $sql->exec();
  $sql->limpar();


  //verificar se a tarefa atual é dinâmica. Se for a dependencia é posta na tarefa filho do inicio ou do fim
  $subordinada_inicio=0;
  $subordinada_fim=0;
  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_dinamica');
  $sql->adOnde('tarefa_id = '.(int)$tarefa_id);
  $dinamica=$sql->Resultado();
  $sql->Limpar();
  if ($dinamica){

    $sql->adTabela('tarefas');
    $sql->adCampo('min(tarefa_inicio)');
    $sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
    $sql->adOnde('tarefa_id != '.(int)$tarefa_id);
    $inicio=$sql->comando_sql();
    $sql->Limpar();

    $sql->adTabela('tarefas');
    $sql->adCampo('max(tarefa_fim)');
    $sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
    $sql->adOnde('tarefa_id != '.(int)$tarefa_id);
    $fim=$sql->comando_sql();
    $sql->Limpar();


    $sql->adTabela('tarefas');
    $sql->adCampo('tarefa_id');
    $sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
    $sql->adOnde('tarefa_id != '.(int)$tarefa_id);
    $sql->adOnde('tarefa_inicio =('.$inicio.')');
    $subordinada_inicio=$sql->Resultado();
    $sql->Limpar();

    $sql->adTabela('tarefas');
    $sql->adCampo('tarefa_id');
    $sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
    $sql->adOnde('tarefa_id != '.(int)$tarefa_id);
    $sql->adOnde('tarefa_fim =('.$fim.')');
    $subordinada_fim=$sql->Resultado();
    $sql->Limpar();
    }

  foreach($valores as $valor){
    $qnt_latencia='';
    $tipo_latencia='';
    $dependencia=0;
    //verifico se tem latencia

    $sinal=(strpos($valor, '-') ? -1 : 1);

    if ($sinal > 0) $valor=explode('+',$valor);
    else  $valor=explode('-',$valor);
    if (isset($valor[1]) && $valor[1]) {
      if ($achado=strpos($valor[1], 'D')){
        $qnt_latencia=$sinal*substr($valor[1],0, $achado);
        $tipo_latencia='d';
        }
      elseif ($achado=strpos($valor[1], 'H')){
        $qnt_latencia=$sinal*substr($valor[1],0, $achado);
        $tipo_latencia='h';
        }
      elseif ($achado=strpos($valor[1], 'S')){
        $qnt_latencia=$sinal*substr($valor[1],0, $achado);
        $tipo_latencia='s';
        }
      elseif ($achado=strpos($valor[1], 'M')){
        $qnt_latencia=$sinal*substr($valor[1],0, $achado);
        $tipo_latencia='m';
        }
      }

    //tipo de dependencia
    if ($achado=strpos($valor[0], 'TT')){
      $dependencia=substr($valor[0],0, $achado);
      $tipo='TT';
      }
    elseif ($achado=strpos($valor[0], 'IT')){
      $dependencia=substr($valor[0],0, $achado);
      $tipo='IT';
      }
    elseif ($achado=strpos($valor[0], 'II')){
      $dependencia=substr($valor[0],0, $achado);
      $tipo='II';
      }
    elseif ($achado=strpos($valor[0], 'TI')){
      $dependencia=substr($valor[0],0, $achado);
      $tipo='TI';
      }
    else{
      $dependencia=$valor[0];
      $tipo='TI';
      }

    $dependencia_original=(isset($conversao_tarefa[$dependencia]) ? $conversao_tarefa[$dependencia] : 0);


    $obj=new CTarefa;

    //caso seja tarefa dinamica colocar dependencia filho
    if ($subordinada_inicio || $subordinada_fim){
      if(!$obj->verificar_dependencia_circular(($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim), $dependencia_original)){

        //verificarse já existe esta dependencia
        $sql->adTabela('tarefa_dependencias');
        $sql->adCampo('count(dependencias_tarefa_id)');
        $sql->adOnde('dependencias_tarefa_id = '.(int)($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim));
        $sql->adOnde('dependencias_req_tarefa_id = '.(int)$dependencia_original);
        $existe=$sql->Resultado();
        $sql->Limpar();

        if (!$existe && $dependencia_original){
          $sql->adTabela('tarefa_dependencias');
          $sql->adInserir('dependencias_tarefa_id', ($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim));
          $sql->adInserir('dependencias_req_tarefa_id', (int)$dependencia_original);
          $sql->adInserir('tipo_dependencia', $tipo);
          if ($qnt_latencia) $sql->adInserir('latencia', $qnt_latencia);
          if ($tipo_latencia) $sql->adInserir('tipo_latencia', $tipo_latencia);
          $sql->exec();
          $sql->Limpar();
          }
        }
      }
    else if(!$obj->verificar_dependencia_circular($tarefa_id, $dependencia_original)){
      if ($dependencia_original){
        $sql->adTabela('tarefa_dependencias');
        $sql->adInserir('dependencias_tarefa_id', (int)$tarefa_id);
        $sql->adInserir('dependencias_req_tarefa_id', (int)$dependencia_original);
        $sql->adInserir('tipo_dependencia', $tipo);
        if ($qnt_latencia) $sql->adInserir('latencia', $qnt_latencia);
        if ($tipo_latencia) $sql->adInserir('tipo_latencia', $tipo_latencia);
        $sql->exec();
        $sql->Limpar();

        $saida.=';'.$dependencia.($tipo!='TI' ? $tipo : '').($qnt_latencia ? ($qnt_latencia < 0 ? '' : '+').$qnt_latencia.strtolower($tipo_latencia): '');
        }
      }
    else $circular++;
    }

  $objResposta = new xajaxResponse();
  $objResposta->assign("retorno_dependencia","value", (substr($saida,1) ? substr($saida,1) : ''));
  $objResposta->assign("dependencia_circular","value", $circular);
  return $objResposta;
  }

$xajax->registerFunction("mudar_dependencia");

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
  $saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
  $objResposta = new xajaxResponse();
  $objResposta->assign($posicao,"innerHTML", $saida);
  return $objResposta;
  }
$xajax->registerFunction("selecionar_om_ajax");

function criarProjeto($cia_id=0, $nome_projeto='', $usuario_id=0){
  global $bd, $Aplic, $config;
  $data=calculo_data_final_periodo(date('Y-m-d').' 00:00:00',0,$cia_id);
  $sql = new BDConsulta;
  $sql->adTabela('projetos');
  $sql->adInserir('projeto_nome', previnirXSS(utf8_decode($nome_projeto)));
  $sql->adInserir('projeto_responsavel', ($usuario_id ? $usuario_id : $Aplic->usuario_id));
  $sql->adInserir('projeto_cia', (int)$cia_id);
  $sql->adInserir('projeto_status', 1);
  $sql->adInserir('projeto_data_inicio',$data);
  $sql->adInserir('projeto_data_fim', $data);
  $sql->adInserir('projeto_acesso', (int)$config['nivel_acesso_padrao']);
  $sql->exec();
  $projeto_id=$bd->Insert_ID('projetos','projeto_id');
  $sql->Limpar();

  $sql->adTabela('projetos');
  $sql->adAtualizar('projeto_superior_original', (int)$projeto_id);
  $sql->adAtualizar('projeto_superior', (int)$projeto_id);
  $sql->adOnde('projeto_id = '.(int)$projeto_id);
  $sql->exec();
  $sql->Limpar();

  $objResposta = new xajaxResponse();
  $objResposta->assign("projeto_id","value", $projeto_id);
  return $objResposta;

  }

$xajax->registerFunction("criarProjeto");

function inserir_tarefa($tarefa_projeto=0, $tarefa_superior=0, $nome='', $tarefa_pai=0){
  global $bd, $Aplic, $config;

  $sql = new BDConsulta;

  $sql->adTabela('projetos');
  $sql->adCampo('projeto_cia');
  $sql->adOnde('projeto_id='.(int)$tarefa_projeto);
  $cia_id=$sql->resultado();
  $sql->limpar();
  $data=date('Y-m-d H').':00:00';

  if ($Aplic->profissional){


    $tarefa_numeracao=numeracao_nova_tarefa($tarefa_projeto, $tarefa_superior);
    }
  else $tarefa_numeracao=0;

  $sql->adTabela('tarefas');
  $sql->adInserir('tarefa_nome', previnirXSS(utf8_decode($nome)));
  if ($tarefa_superior) $sql->adInserir('tarefa_superior', $tarefa_superior);
  $sql->adInserir('tarefa_projeto', $tarefa_projeto);
  $sql->adInserir('tarefa_inicio_manual',$data);
  $sql->adInserir('tarefa_fim_manual', $data);
  $sql->adInserir('tarefa_inicio',$data);
  $sql->adInserir('tarefa_fim', $data);
  $sql->adInserir('tarefa_dono', $Aplic->usuario_id);
  $sql->adInserir('tarefa_cia', $cia_id);
  $sql->adInserir('tarefa_criador', $Aplic->usuario_id);
  $sql->adInserir('tarefa_marco', 1);
  $sql->adInserir('tarefa_dinamica', 0);
  $sql->adInserir('tarefa_acesso', (int)$config['nivel_acesso_padrao']);
  if ($tarefa_numeracao) $sql->adInserir('tarefa_numeracao', $tarefa_numeracao);
  $sql->exec();
  $nova_tarefa_id=$bd->Insert_ID('tarefas','tarefa_id');
  $sql->Limpar();

  if (!$tarefa_superior){
    $sql->adTabela('tarefas');
    $sql->adAtualizar('tarefa_superior', $nova_tarefa_id);
    $sql->adOnde('tarefa_id = '.(int)$nova_tarefa_id);
    $sql->exec();
    $sql->Limpar();
    }
  $objResposta = new xajaxResponse();
  $objResposta->assign("nova_tarefa_id","value", $nova_tarefa_id);
  return $objResposta;
  }
$xajax->registerFunction("inserir_tarefa");

function inserir_tarefa_acima_ajax($tarefa_projeto=0, $tarefa_superior=0, $nome='', $tarefa_pai=0, $retornaDados = true){
  $nova_tarefa_id=inserir_tarefa_acima($tarefa_projeto, $tarefa_superior, $nome);
  $objResposta = new xajaxResponse();
  $objResposta->assign("nova_tarefa_id","value", $nova_tarefa_id);
  return $objResposta;
  }
$xajax->registerFunction("inserir_tarefa_acima_ajax");

function inserir_tarefa_abaixo_ajax($tarefa_projeto=0, $tarefa_superior=0, $nome='', $tarefa_pai=0, $retornaDados = true){
  $nova_tarefa_id=inserir_tarefa_abaixo($tarefa_projeto, $tarefa_superior, $nome);
  $objResposta = new xajaxResponse();
  $objResposta->assign("nova_tarefa_id","value", $nova_tarefa_id);
  return $objResposta;
  }
$xajax->registerFunction("inserir_tarefa_abaixo_ajax");

function renomear_tarefa($tarefa_id=0, $nome){
  $sql = new BDConsulta;
  $sql->adTabela('tarefas');
  $sql->adAtualizar('tarefa_nome', previnirXSS(utf8_decode($nome)));
  $sql->adOnde('tarefa_id = '.(int)$tarefa_id);
  $sql->exec();
  $sql->Limpar();
  return true;
  }
$xajax->registerFunction("renomear_tarefa");

function renomear_projeto($projeto_id=0, $nome){
  $sql = new BDConsulta;
  $sql->adTabela('projetos');
  $sql->adAtualizar('projeto_nome', previnirXSS(utf8_decode($nome)));
  $sql->adOnde('projeto_id = '.(int)$projeto_id);
  $sql->exec();
  $sql->Limpar();
  return true;
  }
$xajax->registerFunction("renomear_projeto");

function superior_tarefa($tarefa_id=0, $tarefa_superior=0, $tarefa_pai=0){
  global $Aplic;

  $sql = new BDConsulta;
  //verificar a superior à atual para recalculo de seu inicio e fim
  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_superior');
  $sql->adOnde('tarefa_id = '.(int)$tarefa_id);
  $sql->adOnde('tarefa_superior != '.(int)$tarefa_id);
  $superior_atual=$sql->Resultado();
  $sql->Limpar();


  if ($superior_atual!=$tarefa_id){
    $sql->adTabela('tarefas');
    $sql->adCampo('count(tarefa_id) AS soma');
    $sql->adOnde('tarefa_superior = '.(int)$superior_atual);
    $sql->adOnde('tarefa_id != '.(int)$superior_atual);
    $qnt_subordinadas=$sql->Resultado();
    $sql->Limpar();

    if ($qnt_subordinadas<2){
      $sql->adTabela('tarefas');
      $sql->adAtualizar('tarefa_dinamica', 0);
      $sql->adOnde('tarefa_id = '.(int)$superior_atual);
      $sql->exec();
      $sql->Limpar();
      }
    }

  if ($Aplic->profissional){
    $sql->adTabela('tarefas');
    $sql->adCampo('tarefa_projeto');
    $sql->adOnde('tarefa_id = '.(int)$tarefa_id);
    $tarefa_projeto=$sql->Resultado();
    $sql->Limpar();
    $tarefa_numeracao=numeracao_nova_tarefa($tarefa_projeto, $tarefa_superior);
    renumerar_tarefas_apos_exclusao($tarefa_id, $tarefa_projeto, $superior_atual);
    }
  else $tarefa_numeracao=0;

  $sql->adTabela('tarefas');
  $sql->adAtualizar('tarefa_superior', ($tarefa_superior ? $tarefa_superior : $tarefa_id));
  if ($tarefa_numeracao) $sql->adAtualizar('tarefa_numeracao', $tarefa_numeracao);
  $sql->adOnde('tarefa_id = '.(int)$tarefa_id);
  $sql->exec();
  $sql->Limpar();

  if ($superior_atual)  calcular_superior($superior_atual);
  if ($tarefa_superior && ($tarefa_superior!=$superior_atual)) calcular_superior($tarefa_superior);
  return true;
  }

$xajax->registerFunction("superior_tarefa");

function renumerar_tarefas($projeto_id=0){
  $sql = new BDConsulta;
  $numero=0;

  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_id');
  $sql->adOnde('tarefa_projeto='.(int)$projeto_id);
  $sql->adOrdem('tarefa_inicio ASC, tarefa_nome ASC');
  $tarefas=$sql->carregarColuna();
  $sql->limpar();
  foreach($tarefas as $tarefa) {
    $sql->adTabela('tarefas');
    $sql->adAtualizar('tarefa_numeracao', ++$numero);
    $sql->adOnde('tarefa_id = '.(int)$tarefa);
    $sql->exec();
    $sql->Limpar();
    }

        return new xajaxResponse();
  }
$xajax->registerFunction("renumerar_tarefas");

function exibir_tarefas($projeto_id=0, $por_numeracao=0, $tarefa_id=0){
  global $Aplic, $vetor_tarefas;

    if ($Aplic->profissional) $por_numeracao=true;
    $sql = new BDConsulta;
    $saida='<table id="geral" cellspacing=0 cellpadding=0 border=0>';
    $total_tarefas=0;
    $sql->adTabela('projetos');
    $sql->adCampo('projeto_nome, projeto_acesso');
    $sql->adOnde('projeto_id='.(int)$projeto_id);
    $projeto=$sql->linha();
    $sql->limpar();
    $podeEditarProjeto=permiteEditar($projeto['projeto_acesso'], $projeto_id);
    $saida.='<tr><td colspan=20 align=center><b>Projeto</b>&nbsp;<input class="texto" type="Text" '.(!$podeEditarProjeto ? 'READONLY': '' ).' onchange="mudar_nome_projeto(\''.$projeto_id.'\')" style="width:180px;" id="pro'.$projeto_id.'" value="'.utf8_encode($projeto['projeto_nome']).'" onchange="mudar_nome_projeto(\''.$projeto_id.'\')" onFocus="tarefaAtiva(-1)" />'.($podeEditarProjeto ? '<img src="./estilo/rondon/imagens/icones/editar.gif" onclick="javascript:editar_projeto('.$projeto_id.')" style="cursor:pointer; vertical-align:middle"/>': '' ).'</td></tr>';

    $objResposta = new xajaxResponse();
    $sql->adTabela('tarefas');
    $sql->adCampo('tarefa_id, tarefa_nome, tarefa_inicio, tarefa_fim, tarefa_duracao, tarefa_percentagem, tarefa_acao, tarefa_dinamica, tarefa_acesso, tarefa_projeto, tarefa_numeracao, tarefa_superior');
    if ($tarefa_id) $sql->adOnde('tarefa_id='.(int)$tarefa_id);
    else $sql->adOnde('tarefa_id=tarefa_superior OR tarefa_superior IS NULL');
    $sql->adOnde('tarefa_projeto='.(int)$projeto_id);
    $sql->adOrdem(($por_numeracao ? 'tarefa_numeracao ASC, ' : '').'tarefa_inicio ASC, tarefa_nome ASC');
    $tarefas=$sql->Lista();
    $sql->limpar();

    if($tarefa_id){
        $numeracao_pai='';
        if ($tarefas[0]['tarefa_id']!=$tarefas[0]['tarefa_superior']) numeracao_pai($tarefas[0]['tarefa_superior'], $numeracao_pai);
    }
    else $numeracao_pai='';

    foreach($tarefas as $tarefa) vetor_tarefas($tarefa['tarefa_id']);
    if (count($tarefas)) $saida.='<tr>'.($Aplic->profissional ? '<th>&nbsp;</th><th>'.utf8_encode('Nº').'</th>':'').'<th>Tarefa</th><th>'.utf8_encode('Início').'</th><th>'.utf8_encode('Término').'</th><th>Dias</th><th>'.utf8_encode('Predecessoras').'</th><th>'.utf8_encode('%').'</th><th></th></tr>';
    foreach($tarefas as $tarefa){
        $saida.=exibir_tarefa($tarefa,'',$numeracao_pai, $podeEditarProjeto, $tarefa_id);
        $saida.=acrescentar_subordinada($tarefa['tarefa_id'], 0, $por_numeracao, ($numeracao_pai ? $numeracao_pai.'.' : '').$tarefa['tarefa_numeracao'], $podeEditarProjeto, $tarefa_id);
    }

    $saida.= '</table>';
    $qnt=0;
    $saida2='';
    foreach ($vetor_tarefas as $id_verdadeiro => $id_falso) $saida2.=($qnt++ ? ';' : '').$id_verdadeiro.':'.$id_falso;

    $objResposta->assign('combo_tarefas',"innerHTML", $saida);
    $objResposta->assign('vetor_tarefas',"value", $saida2);

  return $objResposta;

    /*if ($Aplic->profissional) $por_numeracao=true;
  $sql = new BDConsulta;
  $saida='<table id="geral" cellspacing=0 cellpadding=0 border=0>';
  $total_tarefas=0;
  $sql->adTabela('projetos');
  $sql->adCampo('projeto_nome, projeto_acesso');
  $sql->adOnde('projeto_id='.(int)$projeto_id);
  $projeto=$sql->linha();
  $sql->limpar();
  $podeEditarProjeto=permiteEditar($projeto['projeto_acesso'], $projeto_id);
  $saida.='<tr><td colspan=20 align=center><b>Projeto</b>&nbsp;<input class="texto" type="Text" '.(!$podeEditarProjeto ? 'READONLY': '' ).' onchange="mudar_nome_projeto(\''.$projeto_id.'\')" style="width:180px;" id="pro'.$projeto_id.'" value="'.utf8_encode($projeto['projeto_nome']).'" onchange="mudar_nome_projeto(\''.$projeto_id.'\')" onFocus="tarefaAtiva(-1)" />'.($podeEditarProjeto ? '<img src="./estilo/rondon/imagens/icones/editar.gif" onclick="javascript:editar_projeto('.$projeto_id.')" style="cursor:pointer; vertical-align:middle"/>': '' ).'</td></tr>';

  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_id, tarefa_nome, tarefa_inicio, tarefa_fim, tarefa_duracao, tarefa_percentagem, tarefa_acao, tarefa_dinamica, tarefa_acesso, tarefa_projeto, tarefa_numeracao, tarefa_superior');
  if ($tarefa_id) $sql->adOnde('tarefa_id='.(int)$tarefa_id);
  else $sql->adOnde('tarefa_id=tarefa_superior OR tarefa_superior IS NULL');
  $sql->adOnde('tarefa_projeto='.(int)$projeto_id);
  $sql->adOrdem(($por_numeracao ? 'tarefa_numeracao ASC, ' : '').'tarefa_inicio ASC, tarefa_nome ASC');
  $tarefas=$sql->Lista();
  $sql->limpar();

  if ($tarefa_id){
    $numeracao_pai='';
    if ($tarefas[0]['tarefa_id']!=$tarefas[0]['tarefa_superior']) numeracao_pai($tarefas[0]['tarefa_superior'], $numeracao_pai);
    }
  else $numeracao_pai='';


  foreach($tarefas as $tarefa) vetor_tarefas($tarefa['tarefa_id']);
  if (count($tarefas)) $saida.='<tr>'.($Aplic->profissional ? '<th>&nbsp;</th><th>'.utf8_encode('Nº').'</th>':'').'<th>Tarefa</th><th>'.utf8_encode('Início').'</th><th>'.utf8_encode('Término').'</th><th>Dias</th><th>'.utf8_encode('Predecessoras').'</th><th>'.utf8_encode('%').'</th><th></th></tr>';
  foreach($tarefas as $tarefa){
    $saida.=exibir_tarefa($tarefa,'',$numeracao_pai, $podeEditarProjeto, $tarefa_id);
    $saida.=acrescentar_subordinada($tarefa['tarefa_id'], 0, $por_numeracao, ($numeracao_pai ? $numeracao_pai.'.' : '').$tarefa['tarefa_numeracao'], $podeEditarProjeto, $tarefa_id);
    }
  $saida.= '</table>';
  $qnt=0;
  $saida2='';
  foreach ($vetor_tarefas as $id_verdadeiro => $id_falso) $saida2.=($qnt++ ? ';' : '').$id_verdadeiro.':'.$id_falso;
  $objResposta = new xajaxResponse();
  $objResposta->assign('combo_tarefas',"innerHTML", $saida);
  $objResposta->assign('vetor_tarefas',"value", $saida2);
  return $objResposta;*/
}
$xajax->registerFunction("exibir_tarefas");

function numeracao_pai($tarefa_superior=0, &$numeracao_pai=''){
  $sql = new BDConsulta;
  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_numeracao, tarefa_superior, tarefa_id');
  $sql->adOnde('tarefa_id='.(int)$tarefa_superior);
  $linha=$sql->linha();
  $sql->limpar();
  $numeracao_pai=$linha['tarefa_numeracao'].($numeracao_pai ? '.'.$numeracao_pai : '');
  if ($linha['tarefa_id']!=$linha['tarefa_superior']) numeracao_pai($linha['tarefa_superior'], $numeracao_pai);
  }

function acrescentar_subordinada($tarefa_pai=0, $subnivel=0, $por_numeracao=0, $numeracao_pai='', $podeEditarProjeto=false, $tarefa_id=0){
  $sql = new BDConsulta;
  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_id, tarefa_nome, tarefa_inicio, tarefa_fim, tarefa_duracao, tarefa_percentagem, tarefa_acao, tarefa_dinamica, tarefa_acesso, tarefa_projeto, tarefa_numeracao');
  $sql->adOnde('tarefa_superior ='.(int)$tarefa_pai.' AND tarefa_id!='.(int)$tarefa_pai);
  $sql->adOrdem(($por_numeracao ? 'tarefa_numeracao ASC, ' : '').'tarefa_inicio ASC, tarefa_nome ASC');
  $lista=$sql->lista();
  $sql->limpar();
  $saida='';
  $espaco='';
  for ($i=0; $i<=$subnivel ; $i++) $espaco.='&nbsp;';
  $espaco.=imagem('icones/subnivel.gif');
  foreach($lista as $tarefa){
    $saida.=exibir_tarefa($tarefa, $espaco, $numeracao_pai, $podeEditarProjeto, $tarefa_id);
    $saida.=acrescentar_subordinada($tarefa['tarefa_id'], ++$subnivel, $por_numeracao, $numeracao_pai.'.'.$tarefa['tarefa_numeracao'], $podeEditarProjeto, $tarefa_id);
    }
  return $saida;
  }

function vetor_tarefas($tarefa_id){
  global $total_tarefas, $vetor_tarefas;
  $total_tarefas++;
  $vetor_tarefas[$tarefa_id]=$total_tarefas;
  vetor_subordinado($tarefa_id);
  }

function vetor_subordinado($tarefa_pai=0){
  $sql = new BDConsulta;
  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_id');
  $sql->adOnde('tarefa_superior ='.(int)$tarefa_pai.' AND tarefa_id!='.(int)$tarefa_pai);
  $lista=$sql->carregarColuna();
  $sql->limpar();
  foreach($lista as $chave => $tarefa_filho) vetor_tarefas($tarefa_filho);
  }

function exibir_tarefa($tarefa=array(), $espaco='', $numeracao_pai='', $podeEditarProjeto=false, $tarefa_id=0){
  global $vetor_tarefas, $Aplic, $config;
  $vetor_dependencia=array('TI'=>'','TT'=>'TT','II'=>'II','IT'=>'IT');
  $sql = new BDConsulta;

  $sql->adTabela('tarefa_dependencias');
  $sql->adCampo('dependencias_req_tarefa_id, tipo_dependencia, latencia, tipo_latencia');
  $sql->adOnde('dependencias_tarefa_id='.(int)$tarefa['tarefa_id']);
  $dependencias=$sql->Lista();
  $sql->limpar();

  $saida_dependencias='';
  $qnt_dep=0;
  foreach($dependencias as $dependencia){
    if (isset($vetor_tarefas[$dependencia['dependencias_req_tarefa_id']])){
      if ($qnt_dep++) $saida_dependencias.=';';
      $saida_dependencias.=$vetor_tarefas[$dependencia['dependencias_req_tarefa_id']].$vetor_dependencia[$dependencia['tipo_dependencia']].($dependencia['latencia'] ? ($dependencia['latencia'] < 0 ? '' : '+').$dependencia['latencia'].$dependencia['tipo_latencia']:'');
      }
    }
  $podeEditar=permiteEditar($tarefa['tarefa_acesso'], $tarefa['tarefa_projeto'], $tarefa['tarefa_id']);
  $saida='';
  $saida.='<tr>';
  $tem_subordinadas=0;
  if ($Aplic->profissional) {
    $sql->adTabela('tarefas');
    $sql->adCampo('count(tarefa_id)');
    $sql->adOnde('tarefa_id!='.(int)$tarefa['tarefa_id']);
    $sql->adOnde('tarefa_superior='.(int)$tarefa['tarefa_id']);
    $tem_subordinadas=$sql->Resultado();
    $sql->limpar();
    $saida.='<td>'.'</td>';
    $saida.='<td><input class="texto" style="width:50px;" type="Text" READONLY value="'.($numeracao_pai ? $numeracao_pai.'.' : '').$tarefa['tarefa_numeracao'].'"></td>';
    }
  $saida.='<td>'.$espaco.$vetor_tarefas[$tarefa['tarefa_id']].($tem_subordinadas ? ($tarefa['tarefa_id']==$tarefa_id ? '<a href="javascript:void(0);" onclick="setTarefa('.($tarefa['tarefa_id']!=$tarefa['tarefa_superior'] ? $tarefa['tarefa_superior'] : 0).')">'.imagem('icones/colapsar.gif').'</a>' : '<a href="javascript:void(0);" onclick="setTarefa('.$tarefa['tarefa_id'].')">'.imagem('icones/expandir.gif').'</a>') : '-').'<input class="texto" type="Text" '.(!$podeEditar ? 'READONLY': '' ).' onchange="mudar_nome_tarefa(\''.$tarefa['tarefa_id'].'\')" style="width:180px;" id="tar'.$tarefa['tarefa_id'].'" value="'.utf8_encode($tarefa['tarefa_nome']).'" onFocus="tarefaAtiva('.$tarefa['tarefa_id'].')" /></td>';
  $saida.='<td>&nbsp;&nbsp;&nbsp;<input class="texto"  type="Text" '.(!$podeEditar || $tarefa['tarefa_dinamica'] ? 'READONLY': '' ).' onchange="processar_mudanca(\'ini'.$tarefa['tarefa_id'].'\')" id="ini'.$tarefa['tarefa_id'].'" value="'.retorna_data($tarefa['tarefa_inicio']).'" maxlength="16" style="width:95px;" onFocus="tarefaAtiva('.$tarefa['tarefa_id'].')" />'.(!$podeEditar || $tarefa['tarefa_dinamica'] ? '<img src="./estilo/rondon/imagens/icones/vazio16.gif">': '<img src="./estilo/rondon/imagens/icones/cal.gif" onclick="javascript:tarefaAtiva('.$tarefa['tarefa_id'].'); NewCssCal(\'ini'.$tarefa['tarefa_id'].'\',\'ddMMyyyy\',\'arrow\',true,\'24\')" style="cursor:pointer; vertical-align:middle"/>' ).'</td>';
  $saida.='<td>&nbsp;&nbsp;&nbsp;<input class="texto"  type="Text" '.(!$podeEditar || $tarefa['tarefa_dinamica'] ? 'READONLY': '' ).' onchange="processar_mudanca(\'fim'.$tarefa['tarefa_id'].'\')" id="fim'.$tarefa['tarefa_id'].'" value="'.retorna_data($tarefa['tarefa_fim']).'" maxlength="16" style="width:95px;" onFocus="tarefaAtiva('.$tarefa['tarefa_id'].')" />'.(!$podeEditar || $tarefa['tarefa_dinamica'] ? '<img src="./estilo/rondon/imagens/icones/vazio16.gif">': '<img src="./estilo/rondon/imagens/icones/cal.gif" onclick="javascript:tarefaAtiva('.$tarefa['tarefa_id'].'); NewCssCal(\'fim'.$tarefa['tarefa_id'].'\',\'ddMMyyyy\',\'arrow\',true,\'24\')" style="cursor:pointer; vertical-align:middle"/>').'</td>';
  $saida.='<td>&nbsp;<input class="texto" type="Text" '.(!$podeEditar || $tarefa['tarefa_dinamica'] ? 'READONLY': '' ).' onchange="processar_mudanca(\'hor'.$tarefa['tarefa_id'].'\')" style="text-align:right" id="hor'.$tarefa['tarefa_id'].'" value="'.($tarefa['tarefa_duracao']/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8)).'" maxlength="4" size="3" onFocus="tarefaAtiva('.$tarefa['tarefa_id'].')" />&nbsp;</td>';
  $saida.='<td>&nbsp;<input class="texto" type="Text" '.(!$podeEditar ? 'READONLY': '' ).' onchange="processar_mudanca(\'dep'.$tarefa['tarefa_id'].'\')" id="dep'.$tarefa['tarefa_id'].'" value="'.$saida_dependencias.'" maxlength="255" size="13" onFocus="tarefaAtiva('.$tarefa['tarefa_id'].')" /></td>';
  $saida.='<td>&nbsp;<input class="texto" type="Text" '.(!$podeEditar || $tarefa['tarefa_dinamica'] || $tarefa['tarefa_acao'] || $tarefa['tarefa_duracao']==0 ? 'READONLY': '' ).' onchange="processar_mudanca(\'per'.$tarefa['tarefa_id'].'\')" style="text-align:right" id="per'.$tarefa['tarefa_id'].'" value="'.($tarefa['tarefa_percentagem']==(int)$tarefa['tarefa_percentagem']  ? (int)$tarefa['tarefa_percentagem'] : $tarefa['tarefa_percentagem']).'" maxlength="4" size="3" onFocus="tarefaAtiva('.$tarefa['tarefa_id'].')" />&nbsp;</td>';
  $saida.='<td>';
  if ($podeEditar) {
    $saida.='<img src="./estilo/rondon/imagens/icones/editar.gif" onclick="javascript:editar_tarefa('.$tarefa['tarefa_id'].')" style="cursor:pointer; vertical-align:middle"/>';
    $saida.='<img src="./estilo/rondon/imagens/icones/excluir.gif" onclick="javascript:excluir_tarefa('.$tarefa['tarefa_id'].')" style="cursor:pointer; vertical-align:middle"/>';
    if ($Aplic->profissional && $podeEditarProjeto) {
      $saida.='<img src="./estilo/rondon/imagens/icones/2setacima.gif" onclick="javascript:mudar_posicao('.$tarefa['tarefa_id'].', \'primeiro\')" style="cursor:pointer; vertical-align:middle"/>';
      $saida.='<img src="./estilo/rondon/imagens/icones/1setacima.gif" onclick="javascript:mudar_posicao('.$tarefa['tarefa_id'].', \'cima\')" style="cursor:pointer; vertical-align:middle"/>';
      $saida.='<img src="./estilo/rondon/imagens/icones/1setabaixo.gif" onclick="javascript:mudar_posicao('.$tarefa['tarefa_id'].', \'baixo\')" style="cursor:pointer; vertical-align:middle"/>';
      $saida.='<img src="./estilo/rondon/imagens/icones/2setabaixo.gif" onclick="javascript:mudar_posicao('.$tarefa['tarefa_id'].', \'ultimo\')" style="cursor:pointer; vertical-align:middle"/>';
      }
    }
  $saida.='</td>';
  $saida.='</tr>';
  return $saida;
  }

function mudar_posicao($tarefa_id=0, $posicao='cima', $tarefa_pai = 0, $retornarDados = false){
  $alterado=mudar_posicao_tarefa($tarefa_id, $posicao);
  $objResposta = new xajaxResponse();
  $objResposta->assign('mudou_posicao',"value", $alterado);
  return $objResposta;
  }
$xajax->registerFunction("mudar_posicao");

function mudar_inicio($projeto_id, $tarefa_id, $inicio, $dias=0){
  //verificar a organização da tarefa
  $sql = new BDConsulta;
  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_cia');
  $sql->adOnde('tarefa_id='.$tarefa_id);
  $cia_id=$sql->resultado();
  $sql->limpar();

  $horas=$dias*config('horas_trab_diario');
  if ($horas) $fim=calculo_data_final_periodo($inicio, $horas, $cia_id, null, $projeto_id, null, $tarefa_id);
  else $fim=$inicio;

  $sql->adTabela('tarefas');
  $sql->adAtualizar('tarefa_inicio_manual', $inicio);
  $sql->adAtualizar('tarefa_fim_manual', $fim);
  $sql->adAtualizar('tarefa_inicio', $inicio);
  $sql->adAtualizar('tarefa_fim', $fim);
  $sql->adAtualizar('tarefa_marco', ($horas > 0 ? 0 : 1));
  $sql->adOnde('tarefa_id = '.$tarefa_id);
  $sql->exec();
  $sql->Limpar();
  verifica_dependencias($tarefa_id);
  calcular_superior($tarefa_id);
  }
$xajax->registerFunction("mudar_inicio");

function mudar_horas($projeto_id, $tarefa_id, $inicio, $dias=0){
  //verificar a organização da tarefa
  $sql = new BDConsulta;
  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_cia');
  $sql->adOnde('tarefa_id='.(int)$tarefa_id);
  $cia_id=$sql->resultado();
  $sql->limpar();

  $horas=$dias*config('horas_trab_diario');
  if ($horas) $fim=calculo_data_final_periodo($inicio, $horas, $cia_id, null, $projeto_id, null, $tarefa_id);
  else $fim=$inicio;

  $sql->adTabela('tarefas');
  $sql->adAtualizar('tarefa_duracao_manual', $horas);
  $sql->adAtualizar('tarefa_duracao', $horas);
  $sql->adAtualizar('tarefa_marco', ($horas > 0 ? 0 : 1));
  $sql->adAtualizar('tarefa_fim_manual', $fim);
  $sql->adAtualizar('tarefa_fim', $fim);
  $sql->adOnde('tarefa_id = '.$tarefa_id);
  $sql->exec();
  $sql->Limpar();

  // Falta recalcular recursivamente as datas inicio e fim das tarefas superiores
  verifica_dependencias($tarefa_id);
  calcular_superior($tarefa_id);
  }
$xajax->registerFunction("mudar_horas");

function mudar_percentagem($tarefa_id, $percentagem, $projeto_id){

  $sql = new BDConsulta;
  $sql->adTabela('tarefas');
  $sql->adAtualizar('tarefa_percentagem', $percentagem);
  $sql->adAtualizar('tarefa_percentagem_data', date('Y-m-d H:i:s'));
  $sql->adOnde('tarefa_id = '.$tarefa_id);
  $sql->exec();
  $sql->Limpar();

  calcular_superior($tarefa_id);
  atualizar_percentagem($projeto_id);
    return new xajaxResponse();
  }
$xajax->registerFunction("mudar_percentagem");

function mudar_fim($projeto_id, $tarefa_id, $inicio, $fim){
  //verificar a organização da tarefa
  $sql = new BDConsulta;
  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_cia');
  $sql->adOnde('tarefa_id='.$tarefa_id);
  $cia_id=$sql->resultado();
  $sql->limpar();

  if ($fim > $inicio) $horas=horas_periodo($inicio, $fim, $cia_id, null, $projeto_id, null, $tarefa_id);
  else {
    $fim=$inicio;
    $horas=0;
    }
  $horas=abs($horas);
  $sql->adTabela('tarefas');
  $sql->adAtualizar('tarefa_fim_manual', $fim);
  $sql->adAtualizar('tarefa_duracao_manual', $horas);
  $sql->adAtualizar('tarefa_fim', $fim);
  $sql->adAtualizar('tarefa_duracao', $horas);
  $sql->adAtualizar('tarefa_marco', ($horas > 0 ? 0 : 1));
  $sql->adOnde('tarefa_id = '.$tarefa_id);
  $sql->exec();
  $sql->Limpar();

  // Falta recalcular recursivamente as datas inicio e fim das tarefas superiores
  verifica_dependencias($tarefa_id);

  $sql->adTabela('tarefas');
  $sql->adCampo('tarefa_superior');
  $sql->adOnde('tarefa_superior !='.$tarefa_id);
  $sql->adOnde('tarefa_id = '.$tarefa_id);
  $tarefa_superior=$sql->resultado();
  $sql->Limpar();
  if ($tarefa_superior) calcular_superior($tarefa_superior);
  }
$xajax->registerFunction("mudar_fim");


function calcular_superior_ajax($tarefa_id){
  if ($tarefa_id) calcular_superior($tarefa_id);
  }
$xajax->registerFunction("calcular_superior_ajax");



$xajax->registerFunction("verifica_dependencias");

if(class_exists('CTarefaCache')){
    $xajax->register(XAJAX_FUNCTION,'salvar_cache',array('mode' => "'asynchronous'"));
    }

$xajax->processRequest();
?>