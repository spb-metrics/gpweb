<?php
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

$sql = new BDConsulta;
$sql->adTabela('arquivos');	
$sql->adCampo('arquivo_data, arquivo_nome_real, formatar_data(arquivo_data, \'%Y/%m/%d/\') AS endereco, arquivo_id');
$sql->adOnde('arquivo_local IS NULL');
$lista = $sql->lista();
$sql->limpar();
foreach($lista  as $linha){
	$fnome = $base_dir.'/arquivos/'.$linha['endereco'].$linha['arquivo_nome_real'];
	if (!file_exists($fnome)) {
		ver5('Não achado o arquivo '.$fnome.' com ID '.$linha['arquivo_id']."\n");
		}
	else {
		$sql->adTabela('arquivos');
		$sql->adAtualizar('arquivo_local', $linha['endereco']);
		$sql->adOnde('arquivo_id='.$linha['arquivo_id']);
		$sql->exec();
		$sql->limpar();
		}
	}


?>
