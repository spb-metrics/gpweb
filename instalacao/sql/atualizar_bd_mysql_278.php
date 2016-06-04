<?php
global $config;


$sql = new BDConsulta;

$sql->adTabela('modelos_tipo');
$sql->adCampo('modelo_tipo_id');
$sql->adOnde('organizacao='.(int)$config['militar']);
$tipos=$sql->carregarColuna();
$sql->limpar();

$sql->adTabela('cias');
$sql->adCampo('cia_id');
$cias=$sql->carregarColuna();
$sql->limpar();

foreach($cias as $cia_id){
	foreach($tipos as $tipo_id){
		$sql->adTabela('modelo_cia');
		$sql->adInserir('modelo_cia_cia', (int)$cia_id);
		$sql->adInserir('modelo_cia_tipo', (int)$tipo_id);
		$sql->exec();
		$sql->limpar();
		}	
	}			
	

$sql->adTabela('modelos_tipo');
$sql->adCampo('modelo_tipo_id, organizacao');
$tipos=$sql->lista();
$sql->limpar();

foreach($tipos As $linha){
	if (file_exists(BASE_DIR.'/modulos/email/modelos/'.$linha['organizacao'].'/modelo'.$linha['modelo_tipo_id'].'.html')){
		$html=file_get_contents(BASE_DIR.'/modulos/email/modelos/'.$linha['organizacao'].'/modelo'.$linha['modelo_tipo_id'].'.html');
		$sql->adTabela('modelos_tipo');
		$sql->adAtualizar('modelo_tipo_html', $html);
		$sql->adOnde('modelo_tipo_id='.(int)$linha['modelo_tipo_id']);
		$sql->exec();
		$sql->limpar();
		}
	}
		
			
							
?>