<?php

global $config;

$sql = new BDConsulta;
$sql->adTabela('campos_customizados_estrutura');	
$sql->adCampo('campo_id, campo_nome, campo_modulo, campo_descricao');
$lista_customizado = $sql->lista();
$sql->limpar();


foreach($lista_customizado AS $linha){
	
	//pesquisa se já existe
	$sql->adTabela('campo_formulario');	
	$sql->adCampo('campo_formulario_id');
	$sql->adOnde('campo_formulario_tipo=\''.$linha['campo_modulo'].'_ex\'');
	$sql->adOnde('campo_formulario_campo=\''.$linha['campo_nome'].'_ex\'');
	$existe = $sql->resultado();
	$sql->limpar();
	
	if (!$existe){
		$sql->adTabela('campo_formulario');
    $sql->adInserir('campo_formulario_campo', $linha['campo_nome'].'_ex');
    $sql->adInserir('campo_formulario_tipo', $linha['campo_modulo'].'_ex');
    $sql->adInserir('campo_formulario_descricao', $linha['campo_descricao']);
    $sql->adInserir('campo_formulario_customizado_id', $linha['campo_id']);
    $sql->exec();
    $sql->limpar();
 
		}
	}
?>
