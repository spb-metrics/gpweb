<?php

if (!($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$Aplic->usuario_super_admin && !$Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'importa_familia')) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();
$sql = new BDConsulta;
global $bd;

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

if (getParam($_REQUEST, 'importar', '')){
	
	$arquivo = getParam($_REQUEST, 'arquivo', '');
	if (isset($_FILES['arquivo'])) {	
		$upload = $_FILES['arquivo'];
		if ($upload['size'] < 1)echo '<script>alert("Arquivo enviado tem tamanho zero. Processo abortado.")</script>';
		else {
			$extensao = substr($_FILES['arquivo']['name'], -3, 3);
			if ($extensao=='zip') $nome=str_replace('.zip', '', $_FILES['arquivo']['name']);
			else {
				ver2('Estensão do arquivo não é zip! Processo abortado.');
				exit();
				}
			
			//cria a pasta
			
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
		 	
		 	if (!is_dir($base_dir.'/arquivos/temp')){
				$res = mkdir($base_dir.'/arquivos/temp', 0777);
				if (!$res) {
					$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\temp', UI_MSG_ALERTA);
					return false;
					}
				}	
			
			if (!is_dir($base_dir.'/arquivos/temp/'.$nome)){
				$res = mkdir($base_dir.'/arquivos/temp/'.$nome, 0777);
				if (!$res) {
					$Aplic->setMsg('A pasta para receber não foi configurada para receber arquivos - mude as permissões em '.$base_dir.'/arquivos/temp/', UI_MSG_ALERTA);
					return false;
					}
				}		
			move_uploaded_file($_FILES['arquivo']['tmp_name'], $base_dir.'/arquivos/temp/'.$nome.'/'.$_FILES['arquivo']['name']);
			$zip = new ZipArchive;
	    $zip->open($base_dir.'/arquivos/temp/'.$nome.'/'.$_FILES['arquivo']['name']);
	    $zip->extractTo($base_dir.'/arquivos/temp/'.$nome);
	    $zip->close(); 
			@unlink($base_dir.'/arquivos/temp/'.$nome.'/'.$_FILES['arquivo']['name']);
			$beneficiarios=carregar_arquivo_tab($base_dir.'/arquivos/temp/'.$nome.'/social_familia.txt');
			if (count($beneficiarios)){
				$campos=$beneficiarios[0];
				$chave_familia_id=array_search('social_familia_id', $campos);
				$chave_familia_uuid=array_search('social_familia_uuid', $campos);
				array_shift($beneficiarios);
				//só insiro usuários cuja chave uuid não existam no servidor
				$lista_uuid=array();
				$uuid_ok=array();
				foreach($beneficiarios as $linha) {
					if (isset($linha[$chave_familia_uuid])) $lista_uuid[$linha[$chave_familia_uuid]]=$linha[$chave_familia_uuid];
					}

				foreach($lista_uuid as $chave => $valor){
					$sql->adTabela('social_familia');
					$sql->adCampo('count(social_familia_id)');
					$sql->adOnde('social_familia_uuid="'.$valor.'"');
					$existe= $sql->Resultado();
					$sql->limpar();
					$uuid_ok[$valor]=($existe ? 0 : 1);
					}
				
				//lista das comunidades existentes, para evitar chave estrangeira ruim (sincronização incorreta)
				$sql->adTabela('social_comunidade');
				$sql->adCampo('social_comunidade_id');
				$lista_comunidade= $sql->carregarColuna();
				$sql->limpar();
				$comunidade=array();
				foreach($lista_comunidade as $chave => $valor) $comunidade[$valor]=$valor;
					
				//lista das ações existentes, para evitar chave estrangeira ruim (sincronização incorreta)
				$sql->adTabela('social_acao');
				$sql->adCampo('social_acao_id');
				$lista_acoes= $sql->carregarColuna();
				$sql->limpar();
				$acao=array();
				foreach($lista_acoes as $chave => $valor) $acao[$valor]=$valor;	
					
					
				//inserir os beneficiários que o ainda não se encontram na base de dados
				$nao_inserido=array();
				$familia_id=array();
				foreach($beneficiarios as $linha){
					if (isset($uuid_ok[$linha[$chave_familia_uuid]]) && $uuid_ok[$linha[$chave_familia_uuid]]){
						// familia_nome é para impedir de inserir linha em branco
						$familia_nome='';
						$sql->adTabela('social_familia');
						foreach($campos as $chave2 => $campo) {
							//checar se chaves estrangeiras estão integras
							if ($campo!='social_familia_id' && $campo!='social_familia_comunidade' && isset($linha[$chave2])) $sql->adInserir($campo, $linha[$chave2]);
							elseif ($campo=='social_familia_comunidade' && isset($linha[$chave2]) && isset($comunidade[$linha[$chave2]])) $sql->adInserir($campo, $linha[$chave2]);
							if ($campo=='social_familia_nome'  && isset($linha[$chave2])) $familia_nome=$linha[$chave2];
							}
						if ($familia_nome){	
							if (!$sql->exec()) die('Não foi possível inserir beneficiários na tabela social_familia.');
							$social_familia_id = $bd->Insert_ID('social_familia','social_familia_id');
							}
						$sql->limpar();
						$familia_id[$linha[$chave_familia_id]]=$social_familia_id;
						}
					else $nao_inserido[$linha[$chave_familia_id]]=$linha;
					}
				inserir_tabela_familia($nome, 'social_familia_acao', '', 'social_familia_acao_familia', $familia_id, $nao_inserido,'social_familia_acao_usuario','social_familia_acao_usuario_conclusao');	
				inserir_tabela_familia($nome, 'social_familia_acao_negada', '', 'social_familia_acao_negada_familia', $familia_id, $nao_inserido,'social_familia_acao_negada_usuario');
				//inserir_tabela_familia($nome, 'social_familia_arquivo', 'social_familia_arquivo_id', 'social_familia_arquivo_familia', $familia_id, $nao_inserido);
				inserir_tabela_familia($nome, 'social_familia_irrigacao', '', 'social_familia_irrigacao_familia', $familia_id, $nao_inserido);
				inserir_tabela_familia($nome, 'social_familia_lista', '', 'social_familia_lista_familia', $familia_id, $nao_inserido,'social_familia_lista_usuario');
				inserir_tabela_familia($nome, 'social_familia_log', 'social_familia_log_id', 'social_familia_log_familia', $familia_id, $nao_inserido,'social_familia_log_criador');
				inserir_tabela_familia($nome, 'social_familia_opcao', '', 'social_familia_opcao_familia', $familia_id, $nao_inserido);
				inserir_tabela_familia($nome, 'social_familia_problema', 'social_familia_problema_id', 'social_familia_problema_familia', $familia_id, $nao_inserido,'social_familia_problema_usuario_insercao','social_familia_problema_usuario_status');
				inserir_tabela_familia($nome, 'social_familia_producao', '', 'social_familia_producao_familia', $familia_id, $nao_inserido);
				ver2('Importação completada');
				}
			}
		}	
	else echo '<script>alert("Não foi enviado nenhum arquivo.")</script>';
	}





function inserir_tabela_familia($uuid, $tabela, $chave_unica='', $chave_familia='', $familia_id, $nao_inserido, $ignorar1='',  $ignorar2=''){
	global $base_dir, $sql, $acao, $comunidade;
	$vetor_linhas=carregar_arquivo_tab($base_dir.'/arquivos/temp/'.$uuid.'/'.$tabela.'.txt');
	if (count($vetor_linhas)){
		$campos=$vetor_linhas[0];
		$chave_familia_id=array_search($chave_familia, $campos);
		array_shift($vetor_linhas);
		foreach($vetor_linhas as $linha){
			if (!isset($nao_inserido[$linha[$chave_familia_id]])){
				$sql->adTabela($tabela);
				foreach($campos as $chave2 => $campo) {
					if ($campo!=$chave_unica && $campo!=$ignorar1 && $campo!=$ignorar2 && $campo!=$chave_familia && isset($linha[$chave2])) $sql->adInserir($campo, $linha[$chave2]);
					elseif ($campo==$chave_familia && isset($familia_id[$linha[$chave2]])) $sql->adInserir($campo, $familia_id[$linha[$chave2]]);
					$ok=true;
					if ($campo==$chave_familia && !isset($familia_id[$linha[$chave2]])) $ok=false;
					if ($tabela=='social_familia_acao' && $campo=='social_familia_acao_acao' && (!isset($linha[$chave2])||(isset($linha[$chave2]) && !isset($acao[$linha[$chave2]])))) $ok=false;
					}
				//Se houve problema de chave estrangeira acao e familia não inserirá	
				if ($ok) $sql->exec();
				$sql->limpar();
				}
			}
		}
	}


$botoesTitulo = new CBlocoTitulo('Importar Arquivo com '.$config['genero_beneficiario'].'s '.ucfirst($config['beneficiarios']), 'importar.jpg', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();

echo '<form name="env" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="" value="importar" />';
echo '<input type="hidden" name="importar" value="0" />';




echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
echo'<tr><td colspan=20 align=center&nbsp;</td></tr>';
echo '<tr><td align=center><table><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="60"></td><td>'.botao('importar', 'Importar', 'Clique neste botão para enviar o arquivo selecionado à esquerda para o servidor e importar os beneficiários existentes no mesmo.','','env.importar.value=1; env.submit()').'</td></tr></table></td></tr>';
echo'<tr><td colspan=20 align=center&nbsp;</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';	




function carregar_arquivo_tab($caminho, $carregar_chave=false){
  $array = array();
  if (!file_exists($caminho)){ return $array; }
  $conteudo = file($caminho);
  for ($x=0; $x < count($conteudo); $x++){
    if (trim($conteudo[$x]) != ''){
      $line = explode("\t", trim($conteudo[$x]));
      if ($carregar_chave){
       	$array[$x] = $line;
    		}
      else $array[] = $line;
    	}
		}
  return $array;
	}

?>