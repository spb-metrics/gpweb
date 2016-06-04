<?php
//Para o caso de ter dado problema na versão 16
$sql = new BDConsulta;
$sql->adTabela('config');
$sql->adCampo('config_nome');
$sql->adOnde('config_grupo=\'social\'');
$lista=$sql->listaVetorChave('config_nome', 'config_nome');
$sql->Limpar();

if (!isset($lista['genero_beneficiario'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES ('genero_beneficiario','o','social','select');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}

if (!isset($lista['beneficiario'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES ('beneficiario','beneficiário','social','text');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}
	
if (!isset($lista['beneficiarios'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES ('beneficiarios','beneficiários','social','text');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}
	
if (!isset($lista['nis_obrigatorio'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES ('nis_obrigatorio','true','social','checkbox');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}
	
if (!isset($lista['cpf_obrigatorio'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES	('cpf_obrigatorio','true','social','checkbox');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}			

$sql = new BDConsulta;
$sql->adTabela('config_lista');
$sql->adCampo('DISTINCT config_nome');
$lista=$sql->listaVetorChave('config_nome', 'config_nome');
$sql->Limpar();

if (!isset($lista['genero_beneficiario'])){
	$script="INSERT INTO config_lista (config_nome, config_lista_nome) VALUES ('genero_beneficiario','o'),('genero_beneficiario','a');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}

?>
