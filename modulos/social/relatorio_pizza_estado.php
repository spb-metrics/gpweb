<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $opcao_id,  $dialogo, $estado_sigla, $estado, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;

if ($opcao_id=='bolsa_estado') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas que recebem bolsa família</h1><br></td></tr>';
elseif ($opcao_id=='mulher_estado') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas com mulheres chefes de família</h1><br></td></tr>';
elseif ($opcao_id=='crianca_estado') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas com criança de 0 a 6 anos</h1><br></td></tr>';
elseif ($opcao_id=='escola_estado') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas com crianças e adolecentes na escola</h1><br></td></tr>';
elseif ($opcao_id=='idoso_estado') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas com adultos com idade igual ou superior a 65 anos</h1><br></td></tr>';
elseif ($opcao_id=='deficiente_estado') echo '<table width="100%"><tr><td align=center><h1>Percentual de famílias beneficiadas com portadores de deficiência física e mental</h1><br></td></tr>';

$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->esqUnir('estado', 'estado', 'estado_sigla=social_familia_estado');
$sql->adCampo('DISTINCT social_familia_estado, estado_nome');
$sql->adOnde('social_acao_social='.(int)$social_id);
$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
$sql->adOrdem('estado_nome');
$vetor_estado=$sql->lista();
$sql->limpar();

$sql->adTabela('social_acao_conceder');
$sql->adCampo('social_acao_conceder_campo, social_acao_conceder_situacao');
$sql->adOnde('social_acao_conceder_acao='.(int)$acao_id);
$parametros=$sql->Lista();
$sql->limpar();

$quantidade=array();
foreach ($vetor_estado as $estado){

	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_estado="'.$estado['social_familia_estado'].'"');
	if ($opcao_id=='bolsa_estado') $sql->adOnde('social_familia_bolsa=1');
	elseif ($opcao_id=='mulher_estado') $sql->adOnde('social_familia_sexo_chefe=2 OR (social_familia_sexo=2 AND social_familia_chefe=1)');
	elseif ($opcao_id=='crianca_estado') $sql->adOnde('social_familia_crianca_seis > 0');
	elseif ($opcao_id=='escola_estado') $sql->adOnde('social_familia_crianca_escola > 0');
	elseif ($opcao_id=='idoso_estado') $sql->adOnde('social_familia_sessenta_cinco > 0');
	elseif ($opcao_id=='deficiente_estado') $sql->adOnde('social_familia_deficiente_mental > 0');
	$parcial=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_estado="'.$estado['social_familia_estado'].'"');
	$total=$sql->Resultado();
	$sql->limpar();
	$quantidade[$estado['social_familia_estado']]=array('estado' => $estado['estado_nome'], 'total' => $total , 'parcial' => $parcial);
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
	
	$porcentagem=($linha['total'] > 0 ? number_format((100*$linha['parcial'])/$linha['total'], 2, ',', '.') : 0);		
	$saida.='<tr><td align=left>'.$linha['estado'].'</td><td align=right>'.$porcentagem.'</td><tr>';
	
	$geral_total+=$linha['total'];
	$geral_parcial+=$linha['parcial'];
	}


$porcentagem=($geral_total > 0 ? number_format((100*$geral_parcial)/$geral_total, 2, ',', '.') : 0);
echo '<tr style="font-weight: bold"><td>Geral</td><td align=right>'.$porcentagem.'</td><tr>';
echo $saida;

echo '</table></td></tr>';	
echo '</table></td></tr>';	
?>