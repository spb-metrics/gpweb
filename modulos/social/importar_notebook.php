<?php
if (!($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$Aplic->usuario_super_admin && !$Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'importa_notebook')) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();
if (getParam($_REQUEST, 'importar', '')){
	require_once BASE_DIR.'/codigo/instalacao.inc.php';
	$arquivo = getParam($_REQUEST, 'arquivo', '');
	if (isset($_FILES['arquivo'])) {	
		$upload = $_FILES['arquivo'];
		if ($upload['size'] < 1)echo '<script>alert("Arquivo enviado tem tamanho zero. Processo abortado.")</script>';
		else {
			$extensao = substr($_FILES['arquivo']['name'], -3, 3);
			if ($extensao=='zip') $nome_importado=str_replace('.zip', '', $_FILES['arquivo']['name']);
			else {
				ver2('Estensão do arquivo não é zip! Processo abortado.');
				exit();
				}
			move_uploaded_file($_FILES['arquivo']['tmp_name'], $base_dir.'/arquivos/temp/'.$_FILES['arquivo']['name']);
			$zip = new ZipArchive;
	    $zip->open($base_dir.'/arquivos/temp/'.$_FILES['arquivo']['name']);
	    $zip->extractTo($base_dir.'/arquivos/temp/');
	    $zip->close(); 
			@unlink($base_dir.'/arquivos/temp/'.$_FILES['arquivo']['name']);
			
			//fazer backup dos dados atuais
			include_once BASE_DIR.'/modulos/social/funcoes.php';
			$nome=exportar_social();
			
			instalacao_carregarSQL($base_dir.'/arquivos/temp/'.$nome_importado.'.sql');
			
			ver2('Dados carregados. O backup dos dados antes da importação encontra-se em '.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/temp/'.$nome.'.zip');
			}
		}
	}










echo '<form name="env" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="" value="importar" />';
echo '<input type="hidden" name="importar" value="0" />';


$botoesTitulo = new CBlocoTitulo('Instalar em Dispositivo Off-Line o Arquivo de Preparação', 'importar.jpg', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();

echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
echo'<tr><td colspan=20 align=center&nbsp;</td></tr>';
echo '<tr><td align=center><table><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="60"></td><td>'.botao('importar', 'Importar', 'Clique neste botão para enviar o arquivo selecionado à esquerda para o servidor e importar os beneficiários existentes no mesmo.','','env.importar.value=1; env.submit()').'</td></tr></table></td></tr>';
echo'<tr><td colspan=20 align=center&nbsp;</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';	


?>