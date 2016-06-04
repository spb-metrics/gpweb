<?php
if (!ini_get('safe_mode')) @set_time_limit(0);

$sql = new BDConsulta;
$sql->adTabela('plano_acao');
$sql->adCampo('plano_acao_id, plano_acao_depts, plano_acao_usuarios');
$lista=$sql->Lista();
$sql->Limpar();

foreach ($lista as $linha){
	$plano_acao_usuarios=$linha['plano_acao_usuarios'];
	$plano_acao_usuarios=explode(',', $plano_acao_usuarios);
	if ($plano_acao_usuarios){
		foreach($plano_acao_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('plano_acao_usuarios');
				$sql->adInserir('plano_acao_id', $linha['plano_acao_id']);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		}
	$depts_selecionados=$linha['plano_acao_depts'];
	$depts_selecionados=explode(',', $depts_selecionados);
	if ($depts_selecionados){
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('plano_acao_depts');
				$sql->adInserir('plano_acao_id', $linha['plano_acao_id']);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		}
	}

?>
