<?php
if (!ini_get('safe_mode')) @set_time_limit(0);
$sql = new BDConsulta;
$texto='SHOW COLUMNS FROM baseline_tarefa_gastos;';
$sql->executarScript($texto);
$lista=$sql->Lista();
$sql->limpar();
$achou=0;
foreach($lista as $linha) if ($linha['Field']=='tarefa_custos_data_recebido') {
	$achou=1;
	break;
	}
if ($achou){
	$texto='ALTER TABLE baseline_tarefa_gastos CHANGE tarefa_custos_data_recebido tarefa_gastos_data_recebido DATE DEFAULT NULL;';
	$sql->executarScript($texto);
	$sql->exec();
	$sql->limpar();
	}
?>