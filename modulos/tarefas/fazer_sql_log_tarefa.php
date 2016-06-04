<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


global $config;

include_once BASE_DIR.'/modulos/tarefas/funcoes.php';

$sql = new BDConsulta;
$del = intval(getParam($_REQUEST, 'del', 0));
$tarefa_id = getParam($_REQUEST, 'tarefa_id', null);
$tarefa_log_id = getParam($_REQUEST, 'tarefa_log_id', null);
$projeto_id = getParam($_REQUEST, 'projeto_id', null);
$dialogo = getParam($_REQUEST, 'dialogo', 0);

if (!$projeto_id) {
	$sql->adTabela('tarefas');
  $sql->adCampo('tarefa_projeto');
  $sql->adOnde('tarefa_id='.(int)$tarefa_id);
  $projeto_id=$sql->resultado();
  $sql->limpar();
	}


if ($Aplic->profissional){
	$sql->adTabela('projetos');
  $sql->adCampo('projeto_aprova_registro');
  $sql->adOnde('projeto_id='.(int)$projeto_id);
  $aprova_registro=$sql->resultado();
  $sql->limpar();
	}
else $aprova_registro=false;




$cache = null;
if($Aplic->profissional){
    require_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';
    $cache = CTarefaCache::getInstance();
    }

$obj = new CTarefaLog();
if ($tarefa_log_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=tarefas&a=ver&tab=0&projeto_id='.(int)$projeto_id.'&tarefa_id='.(int)$tarefa_id);
	}

$Aplic->setMsg('Registro de ocorrência d'.$config['genero_tarefa'].' '.$config['tarefa']);

$nova_percentagem = null;
$nova_data_inicio = null;
$nova_data_fim = null;
$nova_duracao = null;
$novo_numero_dias  = null;
$novo_status = null;

if ($del){
	$obj->load($tarefa_log_id);
	if (($msg = $obj->excluir()))	$Aplic->setMsg($msg, UI_MSG_ERRO);
	else $Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
	$Aplic->redirecionar('m='.($tarefa_id ? 'tarefas&tab=0' : 'projetos&tab=3').'&a=ver&projeto_id='.$projeto_id.'&tarefa_id='.$tarefa_id);
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	
	$Aplic->setMsg($tarefa_log_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);

	if (!$aprova_registro && ($_REQUEST['tarefa_percentagem_antiga']!=$_REQUEST['tarefa_percentagem'])){
    if($cache){
      $nova_percentagem = (float)getParam($_REQUEST, 'tarefa_percentagem', null);
      $cache->mudarPercentualTarefa($tarefa_id, $nova_percentagem);
      }
    else{
      $sql->adTabela('tarefas');
      $sql->adAtualizar('tarefa_percentagem', (int)getParam($_REQUEST, 'tarefa_percentagem', null));
      $sql->adAtualizar('tarefa_percentagem_data', date('Y-m-d H:i:s'));
      $sql->adOnde('tarefa_id='.(int)$tarefa_id);
      $sql->exec();
      $sql->limpar();
      }
      
		$sql->adTabela('tarefa_log');
		$sql->adAtualizar('tarefa_log_reg_mudanca_percentagem', $_REQUEST['tarefa_percentagem']);
		$sql->adOnde('tarefa_log_id='.(int)$obj->tarefa_log_id);
		$sql->exec();
		$sql->limpar();
		}
	else if ($_REQUEST['tarefa_percentagem_antiga']!=$_REQUEST['tarefa_percentagem']){
		$sql->adTabela('tarefa_log');
		$sql->adAtualizar('tarefa_log_reg_mudanca_percentagem', $_REQUEST['tarefa_percentagem']);
		$sql->adOnde('tarefa_log_id='.(int)$obj->tarefa_log_id);
		$sql->exec();
		$sql->limpar();
		}



	if ($_REQUEST['tarefa_realizado_antigo']!=$_REQUEST['tarefa_realizado']){
   
		$sql->adTabela('tarefa_log');
		$sql->adAtualizar('tarefa_log_reg_mudanca_realizado', getParam($_REQUEST, 'tarefa_realizado', null));
		$sql->adOnde('tarefa_log_id='.(int)$obj->tarefa_log_id);
		$sql->exec();
		$sql->limpar();
		
		if (!$aprova_registro){
	    $sql->adTabela('tarefas');
	    $sql->adAtualizar('tarefa_realizado', getParam($_REQUEST, 'tarefa_realizado', null));
	    $sql->adOnde('tarefa_id='.(int)$tarefa_id);
	    $sql->exec();
	    $sql->limpar();
			}
		}


	if ($_REQUEST['tarefa_status_antigo']!=$_REQUEST['tarefa_status']){
    $novo_status = getParam($_REQUEST, 'tarefa_status', null);
		
		$sql->adTabela('tarefa_log');
		$sql->adAtualizar('tarefa_log_reg_mudanca_status', $novo_status);
		$sql->adOnde('tarefa_log_id='.(int)$obj->tarefa_log_id);
		$sql->exec();
		$sql->limpar();
		
		if (!$aprova_registro){
			$sql->adTabela('tarefas');
			$sql->adAtualizar('tarefa_status', $novo_status);
			$sql->adOnde('tarefa_id='.(int)$tarefa_id);
			$sql->exec();
			$sql->limpar();
			}
		}



	$novo_fim=getParam($_REQUEST, 'oculto_data_fim', null).' '.getParam($_REQUEST, 'hora_fim', null).':'.getParam($_REQUEST, 'minuto_fim', null).':00';
	$novo_inicio=getParam($_REQUEST, 'oculto_data_inicio', null).' '.getParam($_REQUEST, 'inicio_hora', null).':'.getParam($_REQUEST, 'inicio_minutos', null).':00';

	if(($_REQUEST['tarefa_fim_antiga']!=$novo_fim || $_REQUEST['tarefa_inicio_antiga']!=$novo_inicio || $_REQUEST['tarefa_duracao']!=$_REQUEST['tarefa_duracao_antiga'])){
    
    $sql->adTabela('tarefa_log');
		$sql->adAtualizar('tarefa_log_reg_mudanca_inicio', $novo_inicio);
		$sql->adAtualizar('tarefa_log_reg_mudanca_fim', $novo_fim);
		$sql->adAtualizar('tarefa_log_reg_mudanca_duracao', (float)$_REQUEST['tarefa_duracao']);
		$sql->adOnde('tarefa_log_id='.(int)$obj->tarefa_log_id);
		$sql->exec();
		$sql->limpar();
    
    if (!$aprova_registro){
	    if($cache){
	      $nova_data_inicio = retorna_data($novo_inicio);
	      $nova_data_fim = retorna_data($novo_fim);
	      $nova_duracao = (float)getParam($_REQUEST, 'tarefa_duracao', null);
	      $dataInicial = strtotime(substr($novo_inicio,0,10));
	      $dataFinal = strtotime(substr($novo_fim,0,10));
	      $dias = ($dataFinal-$dataInicial);
	      if($dias > 0) $dias /= 86400;
	      $novo_numero_dias = round($dias,2);
	      $duracao = ($_REQUEST['tarefa_duracao']!=$_REQUEST['tarefa_duracao_antiga'] ? $nova_duracao : false);
	      if($duracao !== false){
	        $duracao *= (int)$config['horas_trab_diario'];
	        }
	      $cache->mudarDatasTarefa($tarefa_id, $novo_inicio, $novo_fim, $duracao);
	      }
	    else{
	      $sql->adTabela('tarefas');
	      $sql->adAtualizar('tarefa_dinamica', 0);
	      $sql->adAtualizar('tarefa_duracao_manual', getParam($_REQUEST, 'tarefa_duracao', null));
	      $sql->adAtualizar('tarefa_inicio_manual', $novo_inicio);
	      $sql->adAtualizar('tarefa_fim_manual', $novo_fim);
	      $sql->adAtualizar('tarefa_duracao', getParam($_REQUEST, 'tarefa_duracao', null));
	      $sql->adAtualizar('tarefa_inicio', $novo_inicio);
	      $sql->adAtualizar('tarefa_fim', $novo_fim);
	      $sql->adAtualizar('tarefa_marco', ($_REQUEST['tarefa_duracao'] > 0 ? 0 : 1));
	      $sql->adOnde('tarefa_id = '.(int)$tarefa_id);
	      $sql->exec();
	      $sql->Limpar();
	      verifica_dependencias($tarefa_id);
	      //calcular_superior($tarefa_id);
	      }
			}
		}
		
  if($cache && $cache->flushToSession(false)){
    $cache->salvarCache($projeto_id);
    }

	//checar anexo

	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

	if(isset($_FILES['arquivo']['name'])){

		foreach($_FILES['arquivo']['name'] as $chave => $linha){
			if (file_exists($_FILES['arquivo']['tmp_name'][$chave]) && !empty($_FILES['arquivo']['tmp_name'][$chave])){
			  $pasta='tarefa_log';
			  $tipo=strtolower(pathinfo($_FILES['arquivo']['name'][$chave], PATHINFO_EXTENSION));
			  $tamanho=explode('/',$_FILES['arquivo']['size'][$chave]);
			  $permitido=getSisValor('downloadPermitido');
			  $proibido=getSisValor('downloadProibido');
			  $verificar_malicioso=explode('.',$_FILES['arquivo']['name'][$chave]);
			 	$malicioso=false;
			 	foreach($verificar_malicioso as $extensao) {
			 		if (in_array(strtolower($extensao), $proibido)) {
			 			$malicioso=$extensao;
			 			break;
			 			}
			 		}
			 	if ($malicioso) {
			  	$Aplic->setMsg('Extensão '.$malicioso.' não é permitida!', UI_MSG_ERRO);
			  	}
			  elseif (!in_array($tipo, $permitido)) {
			  	$Aplic->setMsg('Extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido', UI_MSG_ERRO);
			  	}
			  else {
					$sql = new BDConsulta;
				 	$sql->adTabela('tarefa_log_arquivo');
					$sql->adCampo('count(tarefa_log_arquivo_id) AS soma');
					$sql->adOnde('tarefa_log_arquivo_tarefa_log_id ='.$obj->tarefa_log_id);
				  $soma_total = 1+(int)$sql->Resultado();
				  $sql->Limpar();
				  $caminho = $soma_total.'_'.$_FILES['arquivo']['name'][$chave];
				  $caminho = removerSimbolos($caminho);
				  $caminho = removerSimbolos($caminho);
				  $caminho = removerSimbolos($caminho);
				 	if (!is_dir($base_dir)){
						$res = mkdir($base_dir, 0777);
						if (!$res) {
							$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
							return false;
							}
						}
				 	if (!is_dir($base_dir.'/arquivos')){
						$res = mkdir($base_dir.'/arquivos', 0777);
						if (!$res) {
							$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
							return false;
							}
						}
				 	if (!is_dir($base_dir.'/arquivos/tarefa_log')){
						$res = mkdir($base_dir.'/arquivos/tarefa_log', 0777);
						if (!$res) {
							$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos.', UI_MSG_ALERTA);
							return false;
							}
						}
				 	if (!is_dir($base_dir.'/arquivos/tarefa_log/'.$obj->tarefa_log_id)){
						$res = mkdir($base_dir.'/arquivos/tarefa_log/'.$obj->tarefa_log_id, 0777);
						if (!$res) {
							$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\\tarefa_log\.', UI_MSG_ALERTA);
							return false;
							}
						}
				  // move o arquivo para o destino
				  $caminho_completo = $base_dir.'/arquivos/tarefa_log/'.$obj->tarefa_log_id.'/'.$caminho;
				  move_uploaded_file($_FILES['arquivo']['tmp_name'][$chave], $caminho_completo);
				  if (file_exists($caminho_completo)) {
				  	$tipo=explode('/',$_FILES['arquivo']['type'][$chave]);
				  	$sql->adTabela('tarefa_log_arquivo');
						$sql->adInserir('tarefa_log_arquivo_tarefa_log_id', $obj->tarefa_log_id);
						$sql->adInserir('tarefa_log_arquivo_nome', $_FILES['arquivo']['name'][$chave]);
						$sql->adInserir('tarefa_log_arquivo_endereco', $obj->tarefa_log_id.'/'.$caminho);
						$sql->adInserir('tarefa_log_arquivo_usuario', $Aplic->usuario_id);
						$sql->adInserir('tarefa_log_arquivo_data', date('Y-m-d H:i:s'));
						$sql->adInserir('tarefa_log_arquivo_ordem', $soma_total);
						$sql->adInserir('tarefa_log_arquivo_tipo', $tipo[0]);
						$sql->adInserir('tarefa_log_arquivo_extensao', $tipo[1]);
						if (!$sql->exec()) $Aplic->setMsg('Não foi possível inserir o anexos na tabela tarefa_log_arquivo!', UI_MSG_ERRO);
						$sql->Limpar();
				  	}
				  }
				}
			}
		}
		
	//sempre checar percentagem        
	calcular_superior($tarefa_id);                    
  atualizar_percentagem($projeto_id); 
	}

if ($dialogo){
	echo '<script language="javascript">';
    echo 'var resultado = {';
      echo 'id:'.$tarefa_id.',';
      echo 'inicio:'.($nova_data_inicio != null ? "\"".$nova_data_inicio."\"" : 'null').',';
      echo 'fim:'.($nova_data_fim != null ? "\"".$nova_data_fim."\"" : 'null').',';
      echo 'duracao:'.($nova_duracao != null ? $nova_duracao : 'null').',';
      echo 'dias:'.($novo_numero_dias != null ? $novo_numero_dias : 'null').',';
      echo 'percentagem:'.($nova_percentagem != null ? $nova_percentagem : 'null').',';
      echo 'status:'.($novo_status != null ? $novo_status : 'null');
    echo '};';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(resultado);';
	echo 'else self.close();';
	echo '</script>';
	}
else $Aplic->redirecionar('m=tarefas&a=ver&tab=0&projeto_id='.$projeto_id.'&tarefa_id='.$tarefa_id);
exit();
?>