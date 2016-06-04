<?php
$sql = new BDConsulta;
$sql->adTabela('usuario_dept');
$sql->adCampo('usuario_dept.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['usuario_departamento'] && $linha['usuario_id']){
		$sql->adTabela('usuario_grupo');
		$sql->adInserir('usuario_grupo_pai', $linha['usuario_departamento']);
		$sql->adInserir('usuario_grupo_usuario', $linha['usuario_id']);
		$sql->exec();
		$sql->limpar();
		}
	}
	
$sql->adTabela('usuarios');
$sql->adCampo('usuario_id, usuario_grupo_dept');
$sql->adOnde('usuario_grupo_dept >0');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['usuario_id']){
		$sql->adTabela('usuario_grupo');
		$sql->adInserir('usuario_grupo_pai', $linha['usuario_id']);
		$sql->adInserir('usuario_grupo_dept', $linha['usuario_grupo_dept']);
		$sql->exec();
		$sql->limpar();
		}	
	}
?>