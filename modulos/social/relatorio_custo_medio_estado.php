<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cabecalho,$sql, $perms, $Aplic, $tab, $ordem, $estado, $ordenar, $dialogo, $estado_sigla, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;

echo '<table cellpadding=0 cellspacing=0 align=center>';
echo $cabecalho;
echo '<tr><td align=center><h1>Custo e gasto médio por Estado</h1><br></td></tr>';

$sql->adTabela('social_acao');
$sql->adCampo('social_acao_inicial, social_acao_adquirido, social_acao_final, social_acao_instalado, social_acao_instalar');
$sql->adOnde('social_acao_id='.(int)$acao_id);
$legenda=$sql->Linha();
$sql->limpar();


$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->esqUnir('estado', 'estado', 'estado_sigla=social_familia_estado');
$sql->adCampo('DISTINCT social_familia_estado, estado_nome');
$sql->adOnde('social_acao_social='.(int)$social_id);
$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('social_familia_estado=\''.$estado_sigla.'\'');
$sql->adOnde('social_familia_estado IS NOT NULL');
$sql->adOnde('social_familia_estado != \'\'');
$sql->adOrdem('social_familia_estado');
$vetor_estado=$sql->listaVetorChave('social_familia_estado','estado_nome');
$sql->limpar();

$lista_estados='';
foreach($vetor_estado as $chave => $vetor) if ($chave) $lista_estados.=($lista_estados ? ',' : '').'\''.$chave.'\'';

$sql->adTabela('tarefas');
$sql->esqUnir('estado', 'estado', 'estado_sigla=tarefa_estado');
$sql->adCampo('DISTINCT tarefa_estado AS social_familia_estado, estado_nome');
$sql->adOnde('tarefa_adquirido>0');
$sql->adOnde('tarefa_social='.(int)$social_id);
if ($acao_id) $sql->adOnde('tarefa_acao='.(int)$acao_id);
if ($lista_estados) $sql->adOnde('tarefa_estado NOT IN('.$lista_estados.')');
$sql->adOnde('tarefa_estado != \'\'');
if ($estado_sigla) $sql->adOnde('tarefa_estado=\''.$estado_sigla.'\'');
if ($municipios_superintendencia) $sql->adOnde('tarefa_cidade IN ('.$municipios_superintendencia.')');
$novos_estados=$sql->listaVetorChave('social_familia_estado','estado_nome');
$sql->limpar();
if (count($novos_estados)) $vetor_estado=array_merge($vetor_estado,$novos_estados);


$sql->adTabela('social_acao_conceder');
$sql->adCampo('social_acao_conceder_campo, social_acao_conceder_situacao');
$sql->adOnde('social_acao_conceder_acao='.(int)$acao_id);
$parametros=$sql->Lista();
$sql->limpar();

$quantidade=array();

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

foreach ($vetor_estado as $chave => $valor){
	if ($chave){	
		$sql->adTabela('tarefas');
		$sql->adCampo('SUM(tarefa_adquirido)');
		$sql->adOnde('tarefa_acao='.(int)$acao_id);
		$sql->adOnde('tarefa_estado=\''.$chave.'\'');
		$adquirido=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('tarefa_custos');
		$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=tarefa_custos_tarefa');
		$sql->adCampo('SUM((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS total');
		$sql->adOnde('tarefa_acao='.(int)$acao_id);
		$sql->adOnde('tarefa_estado=\''.$chave.'\'');
		if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
		$custo=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('tarefa_gastos');
		$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=tarefa_gastos_tarefa');
		$sql->adCampo('SUM((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS total');
		$sql->adOnde('tarefa_acao='.(int)$acao_id);
		$sql->adOnde('tarefa_estado=\''.$chave.'\'');
		if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
		$gasto=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('social_familia');
		$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
		$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
		$sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);
		$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
		$sql->adOnde('social_acao_social='.(int)$social_id);
		$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
		$sql->adOnde('social_familia_estado=\''.$chave.'\'');
		$completo=$sql->Resultado();
		$sql->limpar();
		
		$quantidade[$chave]=array('estado' => $valor, 'adquirido' => $adquirido, 'feito' => $completo, 'custo' => $custo, 'gasto' => $gasto);
		}
	}


$antigo_uf='';
$soma_adquirido=0;
$soma_feito=0;

$geral_custo=0;
$geral_gasto=0;		 
$geral_adquirido=0;
$geral_feito=0;

echo '<tr><td align=center><table class="tbl1" cellpadding=2 cellspacing=0 align=center><tr><th>Estado</th><th>&nbsp;'.$legenda['social_acao_adquirido'].'&nbsp;</th><th>&nbsp;Custo Total (R$)&nbsp;</th><th>&nbsp;Custo Médio (R$)&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalado'].'&nbsp;</th><th>&nbsp;Gasto Total (R$)&nbsp;</th><th>&nbsp;Gasto Médio (R$)&nbsp;</th></tr>';
foreach($quantidade as $chave => $linha){
	echo '<tr><td align=left>'.$linha['estado'].'</td><td align=center>'.(int)$linha['adquirido'].'</td><td align=right>'.($linha['custo']>0 ? number_format($linha['custo'], 2, ',', '.') : '&nbsp;').'</td><td align=right>'.($linha['adquirido']>0 && $linha['custo']>0 ? number_format($linha['custo']/$linha['adquirido'], 2, ',', '.') : '&nbsp;').'</td><td align=center>'.(int)$linha['feito'].'</td><td align=right>'.($linha['gasto']>0 ? number_format($linha['gasto'], 2, ',', '.')  : '&nbsp;').'</td><td align=right>'.($linha['feito']>0 && $linha['gasto']>0 ? number_format($linha['gasto']/$linha['feito'], 2, ',', '.')  : '&nbsp;').'</td><tr>';
	$geral_adquirido+=$linha['adquirido'];
	$geral_feito+=$linha['feito'];
	}

$sql->adTabela('tarefa_custos');
$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=tarefa_custos_tarefa');
$sql->esqUnir('projetos', 'projetos', 'tarefa_projeto=projeto_id');
$sql->adCampo('SUM((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS total');
$sql->adOnde('projeto_social_acao='.(int)$acao_id);
if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
$custo=$sql->Resultado();
$sql->limpar();

$sql->adTabela('tarefa_gastos');
$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=tarefa_gastos_tarefa');
$sql->esqUnir('projetos', 'projetos', 'tarefa_projeto=projeto_id');
$sql->adCampo('SUM((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS total');
$sql->adOnde('projeto_social_acao='.(int)$acao_id);
if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
$gasto=$sql->Resultado();
$sql->limpar();	

echo '<tr style="font-weight: bold"><td>Total Geral</td><td align=center>'.(int)$geral_adquirido.'</td><td align=right>'.($custo > 0 ? number_format($custo, 2, ',', '.')  : '&nbsp;').'</td><td align=right>'.($geral_adquirido > 0 && $custo > 0 ? number_format($custo/$geral_adquirido, 2, ',', '.')  : '&nbsp;').'</td><td align=center>'.(int)$geral_feito.'</td><td align=right>'.($gasto > 0 ? number_format($gasto, 2, ',', '.')  : '&nbsp;').'</td><td align=right>'.($geral_feito > 0 && $gasto > 0 ? number_format($gasto/$geral_feito, 2, ',', '.')  : '&nbsp;').'</td><tr>';
echo '</table></td></tr></table>';	
?>