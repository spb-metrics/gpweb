<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $opcao_id,  $dialogo, $estado_sigla, $estado, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;

if ($opcao_id=='bolsa_comunidade') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas que recebem bolsa família</h1><br></td></tr>';
elseif ($opcao_id=='mulher_comunidade') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas com mulheres chefes de família</h1><br></td></tr>';
elseif ($opcao_id=='crianca_comunidade') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas com criança de 0 a 6 anos</h1><br></td></tr>';
elseif ($opcao_id=='escola_comunidade') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas com crianças e adolecentes na escola</h1><br></td></tr>';
elseif ($opcao_id=='idoso_comunidade') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas com adultos com idade igual ou superior a 65 anos</h1><br></td></tr>';
elseif ($opcao_id=='deficiente_comunidade') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas com portadores de deficiência física e mental</h1><br></td></tr>';

$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->esqUnir('municipios', 'municipios', 'municipio_id=social_familia_municipio');
$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_comunidade_id=social_familia_comunidade');
$sql->adCampo('DISTINCT social_familia_comunidade, social_familia_municipio, social_familia_estado, municipio_nome, social_comunidade_nome');
$sql->adOnde('social_acao_social='.(int)$social_id);
$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
if ($social_comunidade_id) $sql->adOnde('social_familia_comunidade='.(int)$social_comunidade_id);
$sql->adOrdem('social_familia_estado, municipio_nome, social_comunidade_nome');
$vetor_comunidade=$sql->lista();
$sql->limpar();

$sql->adTabela('social_acao_conceder');
$sql->adCampo('social_acao_conceder_campo, social_acao_conceder_situacao');
$sql->adOnde('social_acao_conceder_acao='.(int)$acao_id);
$parametros=$sql->Lista();
$sql->limpar();

$quantidade=array();
foreach ($vetor_comunidade as $comunidade){

	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_comunidade='.(int)$comunidade['social_familia_comunidade']);
	
	if ($opcao_id=='bolsa_comunidade') $sql->adOnde('social_familia_bolsa=1');
	elseif ($opcao_id=='mulher_comunidade') $sql->adOnde('social_familia_sexo_chefe=2 OR (social_familia_sexo=2 AND social_familia_chefe=1)');
	elseif ($opcao_id=='crianca_comunidade') $sql->adOnde('social_familia_crianca_seis > 0');
	elseif ($opcao_id=='escola_comunidade') $sql->adOnde('social_familia_crianca_escola > 0');
	elseif ($opcao_id=='idoso_comunidade') $sql->adOnde('social_familia_sessenta_cinco > 0');
	elseif ($opcao_id=='deficiente_comunidade') $sql->adOnde('social_familia_deficiente_mental > 0');
	$parcial=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_comunidade='.(int)$comunidade['social_familia_comunidade']);
	$total=$sql->Resultado();
	$sql->limpar();
	
	$quantidade[$comunidade['social_familia_comunidade']]=array('comunidade' => $comunidade['social_comunidade_nome'], 'municipio' => $comunidade['municipio_nome'], 'uf' => $comunidade['social_familia_estado'], 'total' => $total , 'parcial' => $parcial);
	}


$antigo_estado='';
$antigo_municipio='';
$soma_total=0;
$estado_total=0;
$geral_total=0;

$soma_parcial=0;
$estado_parcial=0;
$geral_parcial=0;

$saida_estado='';
$saida_municipio='';
$saida_comunidade='';
$saida='';

echo '<tr><td align=center><table class="tbl1" cellpadding=2 cellspacing=0 align=center><tr><th>Comunidade</th><th>&nbsp;Percentual&nbsp;</th></tr>';
foreach($quantidade as $chave => $linha){
	if($antigo_municipio!=$linha['municipio'] && $antigo_municipio){
		
		$porcentagem=($soma_total > 0 ? number_format((100*$soma_parcial)/$soma_total, 2, ',', '.') : 0);
		$saida_municipio.='<tr style="font-weight: bold"><td>'.$antigo_municipio.'-'.$antigo_estado.'</td><td align=right>'.$porcentagem.'</td><tr>';
		$saida_municipio.=$saida_comunidade;
		$saida_comunidade='';
		$antigo_municipio=$linha['municipio'];
		$soma_total=0;
		$soma_parcial=0;
		}	
	
	if($antigo_estado!=$linha['uf'] && $antigo_estado){
		
		$porcentagem=($estado_total > 0 ? number_format((100*$estado_parcial)/$estado_total, 2, ',', '.') : 0);
		
		$saida.='<tr style="font-weight: bold"><td>'.$estado[$antigo_estado].'</td><td align=right>'.$porcentagem.'</td><tr>';
		
		$saida.=$saida_municipio;
		$saida_comunidade='';
		$saida_municipio='';
		
		$antigo_estado=$linha['uf'];
		$estado_total=0;
		$estado_parcial=0;
		}
		
	$porcentagem=($linha['total'] > 0 ? number_format((100*$linha['parcial'])/$linha['total'], 2, ',', '.') : 0);		
	$saida_comunidade.='<tr><td align=left>'.$linha['comunidade'].'</td><td align=right>'.$porcentagem.'</td><tr>';
	
	$soma_total+=$linha['total'];
	$soma_parcial+=$linha['parcial'];
	$antigo_municipio=$linha['municipio'];
	$antigo_estado=$linha['uf'];
	$estado_total+=$linha['total'];
	$geral_total+=$linha['total'];
	$estado_parcial+=$linha['parcial'];
	$geral_parcial+=$linha['parcial'];
	}
	
$porcentagem=($soma_total > 0 ? number_format((100*$soma_parcial)/$soma_total, 2, ',', '.') : 0);
$saida_municipio.='<tr style="font-weight: bold"><td>'.$antigo_municipio.'-'.$antigo_estado.'</td><td align=right>'.$porcentagem.'</td><tr>';
$saida_municipio.=$saida_comunidade;

$porcentagem=($estado_total > 0 ? number_format((100*$estado_parcial)/$estado_total, 2, ',', '.') : 0);
$saida.='<tr style="font-weight: bold"><td>'.$estado[$antigo_estado].'</td><td align=right>'.$porcentagem.'</td><tr>';
$saida.=$saida_municipio;

$porcentagem=($geral_total > 0 ? number_format((100*$geral_parcial)/$geral_total, 2, ',', '.') : 0);
echo '<tr style="font-weight: bold"><td>Geral</td><td align=right>'.$porcentagem.'</td><tr>';
echo $saida;

echo '</table></td></tr>';	
echo '</table></td></tr>';	
?>