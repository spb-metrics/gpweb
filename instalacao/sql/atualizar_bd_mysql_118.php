<?php

$sql = new BDConsulta;
require_once BASE_DIR.'/classes/aplic.class.php';
require_once BASE_DIR.'/classes/ui.class.php';
require_once BASE_DIR.'/modulos/sistema/perfis/perfis_antigo.class.php';

$Aplic = new CAplic();
$GLOBALS['Aplic']=$Aplic;

$perms = &$Aplic->acl();

$cperfil = new CPerfil;
$perfis = $cperfil->getPerfis();

foreach($perfis as $linha){
	$sql->adTabela('perfil');
	$sql->adInserir('perfil_id', $linha['id']);
	$sql->adInserir('perfil_nome',$linha['valor']);
	$sql->adInserir('perfil_descricao', $linha['nome']);
	$sql->exec();
	$sql->limpar();
	
	
	
	$perfil_acls = $perms->getPerfilACLs($linha['id']);
	if (!is_array($perfil_acls)) $perfil_acls = array();
	$perm_list = $perms->getListaPermissao();
	foreach ($perfil_acls as $acl) {
		$permissao = $perms->get_acl($acl);
		$atual='';
		if (is_array($permissao)) {
			$itemlist = array();
			if (is_array($permissao['axo_grupos'])) {
				foreach ($permissao['axo_grupos'] as $grupo_id) {
					$grupo_data = $perms->get_grupo_dados($grupo_id, 'axo');
					$atual=$grupo_data['valor'];
					}
				}
			if (is_array($permissao['axo'])) {
				foreach ($permissao['axo'] as $chave => $secao) {
					foreach ($secao as $id) {
						$mod_data = $perms->get_objeto_completo($id, $chave, 1, 'axo');
						$atual=$mod_data['valor'];
						}
					}
				}
	
			$tipo_permissao=array();
			if (is_array($permissao['aco'])) {
				foreach ($permissao['aco'] as $chave => $secao) {
					foreach ($secao as $valor) {
						$perm = $perms->get_objeto_completo($valor, $chave, 1, 'aco');
						$tipo_permissao[$perm['valor']]=true;
						}
					}
				}
		
			if($permissao['permitir'] && $atual){
				$sql->adTabela('perfil_acesso');
				$sql->adInserir('perfil_acesso_perfil', $linha['id']);
				$sql->adInserir('perfil_acesso_modulo', $atual);
				if (isset($tipo_permissao['acesso'])) $sql->adInserir('perfil_acesso_acesso', 1);
				if (isset($tipo_permissao['ver'])) $sql->adInserir('perfil_acesso_ver', 1);
				if (isset($tipo_permissao['editar'])) $sql->adInserir('perfil_acesso_editar', 1);
				if (isset($tipo_permissao['adicionar'])) $sql->adInserir('perfil_acesso_adicionar', 1);
				if (isset($tipo_permissao['excluir'])) $sql->adInserir('perfil_acesso_excluir', 1);
				$sql->exec();
				$sql->limpar();
				}
			}
		}
	}


$sql->adTabela('usuarios');
$sql->adOnde('usuario_id');
$usuarios=$sql->carregarColuna();
$sql->Limpar();

foreach($usuarios as $usuario_id){
	$usuario_perfis = $perms->getUsuarioPerfis($usuario_id);
	foreach($usuario_perfis as $linha){
		$sql->adTabela('perfil_usuario');
		$sql->adInserir('perfil_usuario_usuario', $usuario_id);
		$sql->adInserir('perfil_usuario_perfil',$linha['id']);
		$sql->exec();
		$sql->limpar();
		}
		
	}		


?>