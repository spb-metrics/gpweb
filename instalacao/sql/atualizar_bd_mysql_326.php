<?php

global $config;
if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	$sql->adTabela('ata_participante');	
	$sql->adCampo('ata_participante.*');
	$lista = $sql->lista();
	$sql->limpar();

	foreach($lista AS $linha){
		$sql->adTabela('assinatura');
    $sql->adInserir('assinatura_ata', $linha['ata_participante_ata']);
		$sql->adInserir('assinatura_usuario', $linha['ata_participante_usuario']);
		$sql->adInserir('assinatura_atesta', $linha['ata_participante_atesta']);
		$sql->adInserir('assinatura_atesta_opcao', $linha['ata_participante_atesta_opcao']);
		$sql->adInserir('assinatura_funcao', $linha['ata_participante_funcao']);
		$sql->adInserir('assinatura_data', $linha['ata_participante_data']);
		$sql->adInserir('assinatura_aprova', $linha['ata_participante_aprova']);
		$sql->adInserir('assinatura_aprovou', $linha['ata_participante_aprovou']);
		$sql->adInserir('assinatura_observacao', $linha['ata_participante_observacao']);
		$sql->adInserir('assinatura_ordem', $linha['ata_participante_ordem']);
    $sql->exec();
    $sql->limpar();
		}
	}	
?>
