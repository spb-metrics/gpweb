<?php
if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	$sql->adTabela('ata_usuario');
	$sql->adCampo('ata_usuario.*');
	$lista=$sql->lista();
	$sql->Limpar();
	
	foreach($lista as $linha){
		$sql->adTabela('ata_participante');
		$sql->adAtualizar('ata_participante_ata', (int)$linha['ata_usuario_ata']);
		$sql->adOnde('ata_participante_usuario='.(int)$linha['ata_usuario_usuario']);
		$sql->exec();
		$sql->limpar();
		}					
	}									
?>