<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/projetos/licao.class.php');

$sql = new BDConsulta;

$_REQUEST['licao_ativa']=(isset($_REQUEST['licao_ativa']) ? 1 : 0);
$_REQUEST['email_responsavel']=(isset($_REQUEST['email_responsavel']) ? 1 : 0);
$_REQUEST['email_designados']=(isset($_REQUEST['email_designados']) ? 1 : 0);

$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$licao_id = getParam($_REQUEST, 'licao_id', null);

$obj = new CLicao();
if ($licao_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=licao_lista');
	}
$Aplic->setMsg('Li��o');
if ($excluir) {
	$obj->load($licao_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=licao_ver&licao_id='.$licao_id);
		} 
	else {
		$Aplic->setMsg('exclu�da', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=licao_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
    
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($licao_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	
	
	
	//checar anexo

	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	
	if(isset($_FILES['arquivo']['name'])){ 
		
		foreach($_FILES['arquivo']['name'] as $chave => $linha){
			
			if (file_exists($_FILES['arquivo']['tmp_name'][$chave]) && !empty($_FILES['arquivo']['tmp_name'][$chave])){
		
			  $pasta='licao';
			  
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
			  	$Aplic->setMsg('Extens�o '.$malicioso.' n�o � permitida!', UI_MSG_ERRO);
			  	}
			  elseif (!in_array($tipo, $permitido)) {
			  	$Aplic->setMsg('Extens�o '.$tipo.' n�o � permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extens�o o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido', UI_MSG_ERRO);
			  	}
			  else {	
					$sql = new BDConsulta;
				 	$sql->adTabela('licao_arquivo');
					$sql->adCampo('count(licao_arquivo_id) AS soma');
					$sql->adOnde('licao_arquivo_licao ='.$obj->licao_id);	
					
				  $soma_total = 1+(int)$sql->Resultado();
				  $sql->Limpar();
				  $caminho = $soma_total.'_'.$_FILES['arquivo']['name'][$chave];
				  $caminho = removerSimbolos($caminho);
				  $caminho = removerSimbolos($caminho);
				  $caminho = removerSimbolos($caminho);

				 	if (!is_dir($base_dir)){
						$res = mkdir($base_dir, 0777);
						if (!$res) {
							$Aplic->setMsg('N�o foi poss�vel criar a pasta para receber o arquivo - mude as permiss�es na raiz de '.$base_dir, UI_MSG_ALERTA);
							return false;
							}
						}	
				 	
				 	if (!is_dir($base_dir.'/arquivos')){
						$res = mkdir($base_dir.'/arquivos', 0777);
						if (!$res) {
							$Aplic->setMsg('N�o foi poss�vel criar a pasta para receber o arquivo - mude as permiss�es em '.$base_dir.'\.', UI_MSG_ALERTA);
							return false;
							}
						}	
				 	
				 	if (!is_dir($base_dir.'/arquivos/licao')){
						$res = mkdir($base_dir.'/arquivos/licao', 0777);
						if (!$res) {
							$Aplic->setMsg('N�o foi poss�vel criar a pasta para receber o arquivo - mude as permiss�es em '.$base_dir.'\arquivos.', UI_MSG_ALERTA);
							return false;
							}
						}	
				 	
				 	if (!is_dir($base_dir.'/arquivos/licao/'.$obj->licao_id)){
						$res = mkdir($base_dir.'/arquivos/licao/'.$obj->licao_id, 0777);
						if (!$res) {
							$Aplic->setMsg('N�o foi poss�vel criar a pasta para receber o arquivo - mude as permiss�es em '.$base_dir.'\arquivos\\licao\.', UI_MSG_ALERTA);
							return false;
							}
						}	
					
				  // move o arquivo para o destino
				  $caminho_completo = $base_dir.'/arquivos/licao/'.$obj->licao_id.'/'.$caminho;
				  move_uploaded_file($_FILES['arquivo']['tmp_name'][$chave], $caminho_completo);
				  if (file_exists($caminho_completo)) {
				  	$tipo=explode('/',$_FILES['arquivo']['type'][$chave]);
				  	$sql->adTabela('licao_arquivo');
						$sql->adInserir('licao_arquivo_licao', $obj->licao_id);
						$sql->adInserir('licao_arquivo_nome', $_FILES['arquivo']['name'][$chave]);
						$sql->adInserir('licao_arquivo_endereco', $obj->licao_id.'/'.$caminho);
						$sql->adInserir('licao_arquivo_usuario', $Aplic->usuario_id);
						$sql->adInserir('licao_arquivo_data', date('Y-m-d H:i:s'));
						$sql->adInserir('licao_arquivo_ordem', $soma_total);
						$sql->adInserir('licao_arquivo_tipo', $tipo[0]);
						$sql->adInserir('licao_arquivo_extensao', $tipo[1]);
						if (!$sql->exec()) $Aplic->setMsg('N�o foi poss�vel inserir o anexos na tabela licao_arquivo!', UI_MSG_ERRO);
						$sql->Limpar();
				  	} 
				  }
				}	
			}
		}
	
	
	
	
	
	
	}
$Aplic->redirecionar('m=projetos&a=licao_ver&licao_id='.$obj->licao_id);

?>