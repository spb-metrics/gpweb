<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cabecalho,$sql, $perms, $Aplic, $tab, $ordem, $ordenar, $estado, $dialogo, $estado_sigla, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = $config['qnt_projetos'];
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'social_familia_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

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
if ($municipio_id) $sql->adOnde('social_familia_municipio='.(int)$municipio_id);
if ($social_comunidade_id) $sql->adOnde('social_familia_comunidade='.(int)$social_comunidade_id);
$sql->adOrdem('social_familia_estado, social_familia_municipio, social_comunidade_nome');
$vetor_comunidade=$sql->lista();
$sql->limpar();

$sql->adTabela('social_acao_conceder');
$sql->adCampo('social_acao_conceder_campo, social_acao_conceder_situacao');
$sql->adOnde('social_acao_conceder_acao='.(int)$acao_id);
$parametros=$sql->Lista();
$sql->limpar();


$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id, social_acao_lista_descricao');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_tipo=0');
$sql->adOrdem('social_acao_lista_ordem ASC');
$lista=$sql->Lista();

$col_descricao='<table cellpadding=0 cellspacing=0>';
foreach ($lista as $linha) $col_descricao.='<tr><td>'.$linha['social_acao_lista_descricao'].'</td></tr>';
$col_descricao.='</table>';


echo '<table cellpadding=0 cellspacing=0>';
echo $cabecalho;
echo '<tr><td align=center><h1>Resumo de Atividades por '.ucfirst($config['beneficiario']).'</h1></td></tr>';

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

$quantidade=array();
foreach ($vetor_comunidade as $comunidade){
	$sql->adTabela('social_familia');
	$sql->adCampo('count(social_familia_id)');
	foreach($parametros as $parametro) $sql->adOnde($parametro['social_acao_conceder_campo'].' '.$parametro['social_acao_conceder_situacao']);
	$sql->adOnde('social_familia_comunidade='.(int)$comunidade['social_familia_comunidade']);
	$inicial=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('tarefas');
	$sql->adCampo('SUM(tarefa_adquirido)');
	$sql->adOnde('tarefa_acao='.(int)$acao_id);
	$sql->adOnde('tarefa_comunidade='.(int)$comunidade['social_familia_comunidade']);
	$adquirido=$sql->Resultado();
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
	
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_comunidade='.(int)$comunidade['social_familia_comunidade']);
	$completo=$sql->Resultado();
	$sql->limpar();
	$quantidade[$comunidade['social_familia_comunidade']]=array('comunidade' => $comunidade['social_comunidade_nome'], 'comunidade_id' => $comunidade['social_familia_comunidade'], 'municipio' => $comunidade['municipio_nome'], 'uf' => $comunidade['social_familia_estado'] ,'inicial'=> $inicial, 'adquirido' => $adquirido ,'total' => $total, 'feito' => $completo);
	}

$sql->adTabela('social_acao');
$sql->adCampo('social_acao_inicial, social_acao_adquirido, social_acao_final, social_acao_instalado, social_acao_instalar');
$sql->adOnde('social_acao_id='.(int)$acao_id);
$legenda=$sql->Linha();
$sql->limpar();


$antigo_estado='';
$antigo_municipio='';
$soma_inicial=0;
$soma_adquirido=0;
$soma_total=0;
$soma_feito=0;

$estado_inicial=0;
$estado_adquirido=0;
$estado_total=0;
$estado_feito=0;

$geral_inicial=0;
$geral_adquirido=0;
$geral_total=0;
$geral_feito=0;

$saida_estado='';
$saida_municipio='';
$saida_comunidade='';

$saida='';

foreach($quantidade as $chave => $linha){
	if($antigo_municipio!=$linha['municipio'] && $antigo_municipio){
		echo '<tr><td align=left><table class="tbl1" cellpadding=2 cellspacing=0 align=left><tr><th>Município</th><th>&nbsp;'.$legenda['social_acao_inicial'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_adquirido'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_final'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalado'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalar'].'&nbsp;</th></tr>';
		echo '<tr style="font-weight: bold"><td width="200">'.$antigo_municipio.'-'.$antigo_estado.'</td><td align=center>'.$soma_inicial.'</td><td align=center>'.$soma_adquirido.'</td><td align=center>'.$soma_total.'</td><td align=center>'.$soma_feito.'</td><td align=center>'.($soma_total-$soma_feito).'</td><tr>';
		echo '</table></td></tr>';
		echo '<tr><td>&nbsp;</td></tr>';
		
		$antigo_municipio=$linha['municipio'];
		$soma_inicial=0;
		$soma_adquirido=0;
		$soma_total=0;
		$soma_feito=0;
		}	
	
	if($antigo_estado!=$linha['uf'] && $antigo_estado){
		echo '<tr><td align=left><table class="tbl1" cellpadding=2 cellspacing=0 align=left><tr><th>UF</th><th>&nbsp;'.$legenda['social_acao_inicial'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_adquirido'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_final'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalado'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalar'].'&nbsp;</th></tr>';
		echo '<tr style="font-weight: bold"><td width="200">'.$estado[$antigo_estado].'</td><td align=center>'.$estado_inicial.'</td><td align=center>'.$estado_adquirido.'</td><td align=center>'.$estado_total.'</td><td align=center>'.$estado_feito.'</td><td align=center>'.($estado_total-$estado_feito).'</td><tr>';
		echo '</table></td></tr>';
		echo '<tr><td>&nbsp;</td></tr>';
		
		$saida_comunidade='';
		$saida_municipio='';
		
		$antigo_estado=$linha['uf'];
		$estado_inicial=0;
		$estado_adquirido=0;
		$estado_total=0;
		$estado_feito=0;
		}
			
	$soma_inicial+=$linha['inicial'];
	$soma_adquirido+=$linha['adquirido'];
	$soma_total+=$linha['total'];
	$soma_feito+=$linha['feito'];
	
	$antigo_municipio=$linha['municipio'];
	$antigo_estado=$linha['uf'];
	
	$estado_inicial+=$linha['inicial'];
	$estado_adquirido+=$linha['adquirido'];
	$estado_total+=$linha['total'];
	$estado_feito+=$linha['feito'];

	$geral_inicial+=$linha['inicial'];
	$geral_adquirido+=$linha['adquirido'];
	$geral_total+=$linha['total'];
	$geral_feito+=$linha['feito'];
	echo '<tr><td align=left><table class="tbl1" cellpadding=2 cellspacing=0 align=left><tr><th>Comunidade</th><th>&nbsp;'.$legenda['social_acao_inicial'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_adquirido'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_final'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalado'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalar'].'&nbsp;</th></tr>';
	echo '<tr><td align=left width="200">'.$linha['comunidade'].'</td><td align=center>'.$linha['inicial'].'</td><td align=center>'.(int)$linha['adquirido'].'</td><td align=center>'.$linha['total'].'</td><td align=center>'.$linha['feito'].'</td><td align=center>'.($linha['total']-$linha['feito']).'</td><tr>';
	echo '</table></td></tr>';
	
	//fazer cada família
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->adCampo('social_familia_id, social_familia_acao_concluido, social_familia_nome');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_comunidade='.(int)$linha['comunidade_id']);
	$familias=$sql->Lista();
	$sql->limpar();

	$soma=array();
	$total_concluido=0;
	$total_problema=0;
	$total=0;
	foreach ($lista as $linha) $soma[$linha['social_acao_lista_id']]=0;
	echo '<tr><td>&nbsp;</td></tr>';
	foreach($familias as $familia){

		
		$sql->adTabela('social_familia_lista');
		$sql->adCampo('social_familia_lista_lista AS id');
		$sql->adOnde('social_familia_lista_familia='.(int)$familia['social_familia_id']);
		$lista_marcados=$sql->listaVetorChave('id', 'id');
		$sql->limpar();
		
		$sql->adTabela('social_familia_acao');
		$sql->esqUnir('social_acao','social_acao','social_acao_id=social_familia_acao_acao');
		$sql->adCampo('social_acao_id, social_acao_nome, social_familia_acao_concluido');
		$sql->adOnde('social_familia_acao_familia='.(int)$familia['social_familia_id']);
		$sql->adOrdem('social_acao_nome ASC');
		$lista_acoes=$sql->Lista();
		$sql->limpar();
		
		
		$sql->adTabela('social_familia_problema');
		$sql->adCampo('count(social_familia_problema_id)');
		$sql->adOnde('social_familia_problema_acao='.(int)$acao_id);
		$sql->adOnde('social_familia_problema_familia='.(int)$familia['social_familia_id']);
		$problema=$sql->Resultado();
		$sql->limpar();
			
		foreach ($lista as $linha) {
			if (isset($lista_marcados[$linha['social_acao_lista_id']])) $soma[$linha['social_acao_lista_id']]+=1; 
			}
		if ($familia['social_familia_acao_concluido'])$total_concluido+=1;	
		if ($problema) $total_problema+=1;	
		$total++;	
		}
	
	$col2='<table cellpadding=0 cellspacing=0 align="center">';	
	foreach ($soma as $linha) $col2.='<tr><td align="center">'.(int)($total > 0 ? (($linha*100)/$total) : 0).'</td></tr>';
	$col2.='<tr><td align="center">'.(int)($total > 0 ? (($total_concluido*100)/$total) : 0).'</td></tr>';
	$col2.='</table>';
	echo '<tr><td><table cellpadding=0 cellspacing=0 class="tbl1"><tr><th width="50"  style="font-weight: bold">Nr '.ucfirst($config['beneficiario']).'</th><th style="font-weight: bold">Atividades previstas(%)</th><th width="50" style="font-weight: bold">Realizadas(%)</th><th width="50" style="font-weight: bold">Problemas(%)</th></tr>';	
	echo '<tr><td align=center>'.$total.'</td><td>'.$col_descricao.'</td><td>'.$col2.'</td><td align="center">'.(int)($total > 0 ? (($total_problema*100)/$total) : 0).'</td></tr>';
	echo '</table></td></tr>';
	echo '<tr><td><br><br><br></td></tr>';
	}

if (count($quantidade)){

	echo '<tr><td align=left><table class="tbl1" cellpadding=2 cellspacing=0 align=left><tr><th>Município</th><th>&nbsp;'.$legenda['social_acao_inicial'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_adquirido'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_final'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalado'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalar'].'&nbsp;</th></tr>';
			
	echo '<tr style="font-weight: bold"><td width="200">'.$antigo_municipio.'-'.$antigo_estado.'</td><td align=center>'.$soma_inicial.'</td><td align=center>'.$soma_adquirido.'</td><td align=center>'.$soma_total.'</td><td align=center>'.$soma_feito.'</td><td align=center>'.($soma_total-$soma_feito).'</td><tr>';
	echo '</table></td></tr>';
	echo '<tr><td>&nbsp;</td></tr>';
	
	echo '<tr><td align=left><table class="tbl1" cellpadding=2 cellspacing=0 align=left><tr><th width="200">Estado</th><th>&nbsp;'.$legenda['social_acao_inicial'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_adquirido'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_final'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalado'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalar'].'&nbsp;</th></tr>';
	echo '<tr style="font-weight: bold"><td width="200">'.$estado[$antigo_estado].'</td><td align=center>'.$estado_inicial.'</td><td align=center>'.$estado_adquirido.'</td><td align=center>'.$estado_total.'</td><td align=center>'.$estado_feito.'</td><td align=center>'.($estado_total-$estado_feito).'</td><tr>';
	echo '</table></td></tr>';
	echo '<tr><td>&nbsp;</td></tr>';
	
	echo '<tr><td align=left><table class="tbl1" cellpadding=2 cellspacing=0 align=left><tr><th>Resumo</th><th>&nbsp;'.$legenda['social_acao_inicial'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_adquirido'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_final'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalado'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalar'].'&nbsp;</th></tr>';
	echo '<tr style="font-weight: bold"><td width="200">Geral</td><td align=center>'.$geral_inicial.'</td><td align=center>'.$geral_adquirido.'</td><td align=center>'.$geral_total.'</td><td align=center>'.$geral_feito.'</td><td align=center>'.($geral_total-$geral_feito).'</td><tr>';
	echo '</table></td></tr>';
	}
else echo '<tr><td>Nenhum valor encontrado</td></tr>';
echo '</table>';	
?>