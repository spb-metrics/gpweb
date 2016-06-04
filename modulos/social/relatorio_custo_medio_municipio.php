<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cabecalho,$sql, $perms, $Aplic, $tab, $ordem, $estado, $ordenar, $dialogo, $estado_sigla, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;


echo '<table cellpadding=0 cellspacing=0 align=center>';
echo $cabecalho;
echo '<tr><td align=center><h1>Custo e gasto médio por município</h1><br></td></tr>';

$sql->adTabela('social_acao');
$sql->adCampo('social_acao_inicial, social_acao_adquirido, social_acao_final, social_acao_instalado, social_acao_instalar');
$sql->adOnde('social_acao_id='.(int)$acao_id);
$legenda=$sql->Linha();
$sql->limpar();


$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->esqUnir('municipios', 'municipios', 'municipio_id=social_familia_municipio');
$sql->adCampo('DISTINCT social_familia_municipio, municipio_nome, social_familia_estado');
$sql->adOnde('social_acao_social='.(int)$social_id);
$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('social_familia_estado=\''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
$sql->adOrdem('social_familia_estado, social_familia_municipio');
$vetor_municipio=$sql->lista();
$sql->limpar();


$lista_municipios='';
foreach($vetor_municipio as $vetor) if ($vetor['social_familia_municipio']) $lista_municipios.=($lista_municipios ? ',' : '').$vetor['social_familia_municipio'];
$sql->adTabela('tarefas');
$sql->esqUnir('municipios', 'municipios', 'municipio_id=tarefa_cidade');
$sql->adCampo('DISTINCT tarefa_cidade AS social_familia_municipio, municipio_nome, estado_sigla AS social_familia_estado');
$sql->adOnde('tarefa_adquirido>0');
$sql->adOnde('tarefa_social='.(int)$social_id);
if ($acao_id) $sql->adOnde('tarefa_acao='.(int)$acao_id);
if ($lista_municipios) $sql->adOnde('tarefa_cidade NOT IN('.$lista_municipios.')');
if ($municipios_superintendencia) $sql->adOnde('tarefa_cidade IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('tarefa_estado=\''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('tarefa_cidade='.$municipio_id);


$sql->adOnde('tarefa_cidade !=0');
$sql->adOnde('tarefa_cidade IS NOT NULL');
$novos_municipios=$sql->lista();
$sql->limpar();
if (count($novos_municipios)) $vetor_municipio=array_merge($vetor_municipio,$novos_municipios);


$sql->adTabela('social_acao_conceder');
$sql->adCampo('social_acao_conceder_campo, social_acao_conceder_situacao');
$sql->adOnde('social_acao_conceder_acao='.(int)$acao_id);
$parametros=$sql->Lista();
$sql->limpar();

$soma_custo=array();
$soma_gasto=array();		 
$soma_adquirido=array();
$soma_completo=array();

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

$quantidade=array();
foreach ($vetor_municipio as $municipio){
	if ($municipio['social_familia_municipio'] && $municipio['social_familia_estado']){	
		$sql->adTabela('tarefas');
		$sql->adCampo('SUM(tarefa_adquirido)');
		$sql->adOnde('tarefa_acao='.(int)$acao_id);
		$sql->adOnde('tarefa_cidade=\''.$municipio['social_familia_municipio'].'\'');
		$adquirido=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('tarefa_custos');
		$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=tarefa_custos_tarefa');
		$sql->adCampo('SUM((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS total');
		$sql->adOnde('tarefa_acao='.(int)$acao_id);
		$sql->adOnde('tarefa_cidade='.$municipio['social_familia_municipio']);
		if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
		$custo=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('tarefa_gastos');
		$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=tarefa_gastos_tarefa');
		$sql->adCampo('SUM((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS total');
		$sql->adOnde('tarefa_acao='.(int)$acao_id);
		$sql->adOnde('tarefa_cidade='.$municipio['social_familia_municipio']);
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
		$sql->adOnde('social_familia_municipio='.(int)$municipio['social_familia_municipio']);
		$completo=$sql->Resultado();
		$sql->limpar();
		
		$quantidade[$municipio['social_familia_estado']][$municipio['social_familia_municipio']]=array('municipio' => $municipio['municipio_nome'], 'uf' => $municipio['social_familia_estado'], 'adquirido' => $adquirido, 'feito' => $completo, 'custo' => $custo, 'gasto' => $gasto);
		
		if (isset($soma_adquirido[$municipio['social_familia_estado']])) $soma_adquirido[$municipio['social_familia_estado']]+=$adquirido;
		else $soma_adquirido[$municipio['social_familia_estado']]=$adquirido;
		
		if (isset($soma_completo[$municipio['social_familia_estado']])) $soma_completo[$municipio['social_familia_estado']]+=$completo;
		else $soma_completo[$municipio['social_familia_estado']]=$completo;

		if (isset($soma_custo[$municipio['social_familia_estado']])) $soma_custo[$municipio['social_familia_estado']]+=$custo;
		else $soma_custo[$municipio['social_familia_estado']]=$custo;
		
		if (isset($soma_gasto[$municipio['social_familia_estado']])) $soma_gasto[$municipio['social_familia_estado']]+=$gasto;
		else $soma_gasto[$municipio['social_familia_estado']]=$gasto;

		}
	}


$antigo_uf='';


$geral_adquirido=0;
$geral_completo=0;
$geral_custo=0;
$geral_gasto=0;

foreach($soma_adquirido as $chave => $valor) $geral_adquirido+=$valor;
foreach($soma_completo as $chave => $valor) $geral_completo+=$valor;
foreach($soma_custo as $chave => $valor) $geral_custo+=$valor;
foreach($soma_gasto as $chave => $valor) $geral_gasto+=$valor;


echo '<tr><td align=center><table class="tbl1" cellpadding=2 cellspacing=0 align=center><tr><th>UF</th><th>Município</th><th>&nbsp;'.$legenda['social_acao_adquirido'].'&nbsp;</th><th>&nbsp;Custo Total (R$)&nbsp;</th><th>&nbsp;Custo Médio (R$)&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalado'].'&nbsp;</th><th>&nbsp;Gasto Total (R$)&nbsp;</th><th>&nbsp;Gasto Médio (R$)&nbsp;</th></tr>';


foreach($quantidade as $chaveEstado => $bloco_municipios){
	foreach($bloco_municipios as $linha){

		if($antigo_uf!=$linha['uf']){
			echo '<tr style="font-weight: bold"><td colspan=2>'.$estado[$chaveEstado].'</td>
			<td align=center>'.(int)$soma_adquirido[$chaveEstado].'</td>
			<td align=right>'.($soma_custo[$chaveEstado] > 0 ?  number_format($soma_custo[$chaveEstado], 2, ',', '.') : '&nbsp;').'</td>
			<td align=right>'.($soma_adquirido[$chaveEstado] > 0 && $soma_custo[$chaveEstado] > 0 ?  number_format($soma_custo[$chaveEstado]/$soma_adquirido[$chaveEstado], 2, ',', '.')  : '&nbsp;').'</td>
			<td align=center>'.(int)$soma_completo[$chaveEstado].'</td>
			<td align=right>'.($soma_gasto[$chaveEstado] > 0 ? number_format($soma_gasto[$chaveEstado], 2, ',', '.') : '&nbsp;').'</td>
			<td align=right>'.($soma_completo[$chaveEstado] > 0 && $soma_gasto[$chaveEstado] > 0 ? number_format($soma_gasto[$chaveEstado]/$soma_completo[$chaveEstado], 2, ',', '.') : '&nbsp;').'</td><tr>';
			$antigo_uf=$linha['uf'];
			}
		echo '<tr><td align=left colspan=2>&nbsp;&nbsp;'.$linha['municipio'].'</td><td align=center>'.(int)$linha['adquirido'].'</td><td align=right>'.($linha['custo']>0 ? number_format($linha['custo'], 2, ',', '.') : '&nbsp;').'</td><td align=right>'.($linha['adquirido']>0 && $linha['custo']>0 ? number_format($linha['custo']/$linha['adquirido'], 2, ',', '.') : '&nbsp;').'</td><td align=center>'.(int)$linha['feito'].'</td><td align=right>'.($linha['gasto']>0 ? number_format($linha['gasto'], 2, ',', '.')  : '&nbsp;').'</td><td align=right>'.($linha['feito']>0 && $linha['gasto']>0 ? number_format($linha['gasto']/$linha['feito'], 2, ',', '.')  : '&nbsp;').'</td><tr>';
		$antigo_uf=$linha['uf'];
		}
	}
	


echo '<tr style="font-weight: bold"><td colspan=2>Total Geral</td><td align=center>'.(int)$geral_adquirido.'</td><td align=right>'.($custo > 0 ? number_format($custo, 2, ',', '.')  : '&nbsp;').'</td><td align=right>'.($geral_adquirido > 0 && $custo > 0 ? number_format($custo/$geral_adquirido, 2, ',', '.')  : '&nbsp;').'</td><td align=center>'.(int)$geral_completo.'</td><td align=right>'.($gasto > 0 ? number_format($gasto, 2, ',', '.')  : '&nbsp;').'</td><td align=right>'.($geral_completo > 0 && $gasto > 0 ? number_format($gasto/$geral_completo, 2, ',', '.')  : '&nbsp;').'</td><tr>';
echo '</table></td></tr></table>';	
?>