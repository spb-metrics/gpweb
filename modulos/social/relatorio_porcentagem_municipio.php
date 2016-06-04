<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cabecalho, $sql, $perms, $Aplic, $tab, $ordem, $estado, $ordenar, $dialogo, $estado_sigla, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;


echo '<table cellpadding=0 cellspacing=0 align=center>';
echo $cabecalho;
echo '<tr><td align=center><h1>Lista dos municípios em que se executou a ação social</h1><br></td></tr>';
$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->esqUnir('municipios', 'municipios', 'municipio_id=social_familia_municipio');
$sql->adCampo('DISTINCT social_familia_municipio, municipio_nome, social_familia_estado');
$sql->adOnde('social_familia_municipio !=0');
$sql->adOnde('social_familia_municipio IS NOT NULL');
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

$soma_inicial=array();
$soma_adquirido=array();
$soma_total=array();
$soma_feito=array();

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

$quantidade=array();
foreach ($vetor_municipio as $municipio){
	if ($municipio['municipio_nome'] && $municipio['social_familia_estado']){
	
		$sql->adTabela('social_familia');
		$sql->adCampo('count(social_familia_id)');
		foreach($parametros as $parametro) $sql->adOnde($parametro['social_acao_conceder_campo'].' '.$parametro['social_acao_conceder_situacao']);
		$sql->adOnde('social_familia_municipio=\''.$municipio['social_familia_municipio'].'\'');
		$inicial=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('tarefas');
		$sql->adCampo('SUM(tarefa_adquirido)');
		$sql->adOnde('tarefa_acao='.(int)$acao_id);
		$sql->adOnde('tarefa_cidade=\''.$municipio['social_familia_municipio'].'\'');
		$adquirido=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('social_familia');
		$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
		$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
		$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
		$sql->adOnde('social_acao_social='.(int)$social_id);
		$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
		$sql->adOnde('social_familia_municipio=\''.$municipio['social_familia_municipio'].'\'');
		$total=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('social_familia');
		$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
		$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
		$sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);
		$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
		$sql->adOnde('social_acao_social='.(int)$social_id);
		$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
		$sql->adOnde('social_familia_municipio=\''.$municipio['social_familia_municipio'].'\'');
		$completo=$sql->Resultado();
		$sql->limpar();
		$quantidade[$municipio['social_familia_estado']][$municipio['social_familia_municipio']]=array('municipio' => $municipio['municipio_nome'], 'uf' => $municipio['social_familia_estado'] ,'inicial'=> $inicial, 'adquirido' => $adquirido ,'total' => $total, 'feito' => $completo);
		
		if (isset($soma_inicial[$municipio['social_familia_estado']])) $soma_inicial[$municipio['social_familia_estado']]+=$inicial;
		else $soma_inicial[$municipio['social_familia_estado']]=$inicial;
		
		if (isset($soma_adquirido[$municipio['social_familia_estado']])) $soma_adquirido[$municipio['social_familia_estado']]+=$adquirido;
		else $soma_adquirido[$municipio['social_familia_estado']]=$adquirido;
		
		if (isset($soma_total[$municipio['social_familia_estado']])) $soma_total[$municipio['social_familia_estado']]+=$total;
		else $soma_total[$municipio['social_familia_estado']]=$total;
		
		if (isset($soma_feito[$municipio['social_familia_estado']])) $soma_feito[$municipio['social_familia_estado']]+=$completo;
		else $soma_feito[$municipio['social_familia_estado']]=$completo;
		}
	}

$sql->adTabela('social_acao');
$sql->adCampo('social_acao_inicial, social_acao_adquirido, social_acao_final, social_acao_instalado, social_acao_instalar');
$sql->adOnde('social_acao_id='.(int)$acao_id);
$legenda=$sql->Linha();
$sql->limpar();



$antigo_uf='';
$geral_inicial=0;
$geral_adquirido=0;
$geral_total=0;
$geral_feito=0;

foreach($soma_inicial as $chave => $valor) $geral_inicial+=$valor;
foreach($soma_adquirido as $chave => $valor) $geral_adquirido+=$valor;
foreach($soma_total as $chave => $valor) $geral_total+=$valor;
foreach($soma_feito as $chave => $valor) $geral_feito+=$valor;
echo '<tr><td align=center><table class="tbl1" cellpadding=2 cellspacing=0 align=center><tr><th>UF</th><th>Município</th><th>&nbsp;'.dica($legenda['social_acao_adquirido'],'Somatório dos campos de quantidade adquirida das tarefas sinalizadas como sendo desta ação social.').$legenda['social_acao_adquirido'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_final'],ucfirst($config['beneficiarios']).' inseridos no programa social independente de preencherem ou não os requisitos para receber a ação social.').$legenda['social_acao_final'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_inicial'],ucfirst($config['beneficiarios']).' que atendem aos requisitos para receberem a ação social independente de terem sido cadastrad'.$config['genero_beneficiario'].'s na mesma.').$legenda['social_acao_inicial'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_instalar'],ucfirst($config['beneficiarios']).' em que ainda não se marcou os itens pré-definido dos checklist para sinalizar a finalização da ação social.').$legenda['social_acao_instalar'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_instalado'],ucfirst($config['beneficiarios']).' em que se marcou os itens pré-definido dos checklist que sinalizam a finalização da ação social.').$legenda['social_acao_instalado'].dicaF().'&nbsp;</th></tr>';

foreach($quantidade as $chaveEstado => $bloco_municipios){
	foreach($bloco_municipios as $linha){
		if($antigo_uf!=$linha['uf']){
			echo '<tr style="font-weight: bold"><td colspan=2>'.$estado[$linha['uf']].'</td><td align=center>'.(int)$soma_adquirido[$linha['uf']].'</td><td align=center>'.(int)$soma_total[$linha['uf']].'</td><td align=center>'.(int)$soma_inicial[$linha['uf']].'</td><td align=center>'.(int)($soma_total[$linha['uf']]-$soma_feito[$linha['uf']]).'</td><td align=center>'.(int)$soma_feito[$linha['uf']].'</td><tr>';
			$antigo_uf=$linha['uf'];
			}
			
		echo '<tr><td align=left colspan=2>&nbsp;&nbsp;'.$linha['municipio'].'</td><td align=center>'.(int)$linha['adquirido'].'</td><td align=center>'.$linha['total'].'</td><td align=center>'.$linha['inicial'].'</td><td align=center>'.($linha['total']-$linha['feito']).'</td><td align=center>'.$linha['feito'].'</td><tr>';
		$antigo_uf=$linha['uf'];
		}
	}
echo '<tr style="font-weight: bold"><td colspan=2>Total Geral</td><td align=center>'.$geral_adquirido.'</td><td align=center>'.$geral_total.'</td><td align=center>'.$geral_inicial.'</td><td align=center>'.($geral_total-$geral_feito).'</td><td align=center>'.$geral_feito.'</td><tr>';
echo '</table></td></tr>';	
echo '</table>';	
?>