<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cabecalho, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $estado_sigla, $relatorio_id, $estado, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;

echo '<table cellpadding=0 cellspacing=0 align=center>';
echo $cabecalho;
echo '<tr><td align=center><h1>Lista dos Estados em que se executou a ação social</h1><br></td></tr>';

$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->adCampo('DISTINCT social_familia_estado');
$sql->adOnde('social_acao_social='.(int)$social_id);
if ($acao_id) $sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
if ($estado_sigla) $sql->adOnde('social_familia_estado=\''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.(int)$municipio_id);
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
$sql->adOnde('social_familia_estado IS NOT NULL');
$sql->adOnde('social_familia_estado != \'\'');
$vetor_estado=$sql->carregarColuna();
$sql->limpar();

$lista_estados='';
foreach($vetor_estado as $vetor) if ($vetor) $lista_estados.=($lista_estados ? ',' : '').'\''.$vetor.'\'';
$sql->adTabela('tarefas');
$sql->adCampo('DISTINCT tarefa_estado');
$sql->adOnde('tarefa_adquirido>0');
$sql->adOnde('tarefa_social='.(int)$social_id);
if ($acao_id) $sql->adOnde('tarefa_acao='.(int)$acao_id);
if ($lista_estados) $sql->adOnde('tarefa_estado NOT IN('.$lista_estados.')');
if ($estado_sigla) $sql->adOnde('tarefa_estado=\''.$estado_sigla.'\'');
if ($municipios_superintendencia) $sql->adOnde('tarefa_cidade IN ('.$municipios_superintendencia.')');
if ($municipio_id) $sql->adOnde('tarefa_cidade='.(int)$municipio_id);
$sql->adOnde('tarefa_estado != \'\'');
$novos_estados=$sql->carregarColuna();
$sql->limpar();
if (count($novos_estados)) $vetor_estado=array_merge($vetor_estado,$novos_estados);



$sql->limpar();
$sql->adTabela('social_acao_conceder');
$sql->adCampo('social_acao_conceder_campo, social_acao_conceder_situacao');
$sql->adOnde('social_acao_conceder_acao='.(int)$acao_id);
$parametros=$sql->Lista();
$sql->limpar();

$geral_inicial=0;
$geral_adquirido=0;
$geral_total=0;
$geral_feito=0;

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();



$quantidade=array();
foreach ($vetor_estado as $estado_unico){
	$sql->adTabela('social_familia');
	$sql->adCampo('count(social_familia_id)');
	foreach($parametros as $parametro) $sql->adOnde($parametro['social_acao_conceder_campo'].' '.$parametro['social_acao_conceder_situacao']);
	$sql->adOnde('social_familia_estado=\''.$estado_unico.'\'');
	$inicial=$sql->Resultado();
	$sql->limpar();
	
	
	$sql->adTabela('tarefas');
	$sql->adCampo('SUM(tarefa_adquirido)');
	$sql->adOnde('tarefa_acao='.(int)$acao_id);
	$sql->adOnde('tarefa_estado=\''.$estado_unico.'\'');
	$adquirido=$sql->Resultado();
	$sql->limpar();
	
	
	
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	if ($acao_id) $sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_estado=\''.$estado_unico.'\'');
	$total=$sql->Resultado();
	$sql->limpar();
	
	
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	if ($acao_id) $sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_estado=\''.$estado_unico.'\'');
	$completo=$sql->Resultado();
	$sql->limpar();
	
	
	$quantidade[$estado_unico]=array('inicial'=> $inicial, 'adquirido' => $adquirido ,'total' => $total, 'feito' => $completo);
	
	$geral_inicial+=$inicial;
	$geral_adquirido+=$adquirido;
	$geral_total+=$total;
	$geral_feito+=$completo;
	
	}

$sql->adTabela('social_acao');
$sql->adCampo('social_acao_inicial, social_acao_adquirido, social_acao_final, social_acao_instalado, social_acao_instalar');
$sql->adOnde('social_acao_id='.(int)$acao_id);
$legenda=$sql->Linha();
$sql->limpar();



echo '<tr><td align=center><table class="tbl1" cellpadding=2 cellspacing=0 align=center><tr><th>UF</th><th>&nbsp;'.dica($legenda['social_acao_adquirido'],'Somatório dos campos de quantidade adquirida das tarefas sinalizadas como sendo desta ação social.').$legenda['social_acao_adquirido'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_final'],ucfirst($config['beneficiarios']).' inseridos no programa social independente de preencherem ou não os requisitos para receber a ação social.').$legenda['social_acao_final'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_inicial'],ucfirst($config['beneficiarios']).' que atendem aos requisitos para receberem a ação social independente de terem sido cadastrad'.$config['genero_beneficiario'].'s na mesma.').$legenda['social_acao_inicial'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_instalar'],ucfirst($config['beneficiarios']).' em que ainda não se marcou os itens pré-definido dos checklist para sinalizar a finalização da ação social.').$legenda['social_acao_instalar'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_instalado'],ucfirst($config['beneficiarios']).' em que se marcou os itens pré-definido dos checklist que sinalizam a finalização da ação social.').$legenda['social_acao_instalado'].dicaF().'&nbsp;</th></tr>';
foreach($quantidade as $chave => $linha){
	//evitar bug de estado em branco
	if ($chave){
		echo '<tr><td align=center>'.$estado[$chave].'</td><td align=center>'.(int)$linha['adquirido'].'</td><td align=center>'.$linha['total'].'</td><td align=center>'.$linha['inicial'].'</td><td align=center>'.($linha['total']-$linha['feito']).'</td><td align=center>'.$linha['feito'].'</td><tr>';
		}
	}
echo '<tr style="font-weight: bold"><td align=center>Total</td><td align=center>'.$geral_adquirido.'</td><td align=center>'.$geral_total.'</td><td align=center>'.$geral_inicial.'</td><td align=center>'.($geral_total-$geral_feito).'</td><td align=center>'.$geral_feito.'</td><tr>';
echo '</table></td></tr>';	
echo '<tr><td>&nbsp;</td></tr>';
$src = '?m=social&a=relatorio_porcentagem_estado_jpg&sem_cabecalho=1&superintendencia_id='.(int)$superintendencia_id.'&municipio_id='.$municipio_id.'&estado_sigla='.$estado_sigla.'&acao_id='.$acao_id.'&social_id='.$social_id."&width='+((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";
echo "<tr><td><table cellspacing=0 cellpadding=0 align='center'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr></table></td></tr>";
echo '</table></td></tr>';	
echo '</table>';		
?>