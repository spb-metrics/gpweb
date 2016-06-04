<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cabecalho, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $estado_sigla, $estado, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;

echo '<table cellpadding=0 cellspacing=0 align=center>';
echo $cabecalho;
echo '<tr><td align=center><h1>Lista das comunidades em que se executou a ação social</h1><br></td></tr>';

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->esqUnir('municipios', 'municipios', 'municipio_id=social_familia_municipio');
$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_comunidade_id=social_familia_comunidade');
$sql->adCampo('DISTINCT social_familia_comunidade, social_familia_municipio, social_familia_estado, municipio_nome, social_comunidade_nome');
$sql->adOnde('social_acao_social='.(int)$social_id);
$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('social_familia_estado=\''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.(int)$municipio_id);
if ($social_comunidade_id) $sql->adOnde('social_familia_comunidade='.(int)$social_comunidade_id);
$sql->adOrdem('social_familia_estado, social_familia_municipio, social_comunidade_nome');
$vetor_comunidade=$sql->lista();
$sql->limpar();


$lista_comunidades='';
foreach($vetor_comunidade as $vetor) if ($vetor['social_familia_comunidade']) $lista_comunidades.=($lista_comunidades ? ',' : '').$vetor['social_familia_comunidade'];
$sql->adTabela('tarefas');
$sql->esqUnir('municipios', 'municipios', 'municipio_id=tarefa_cidade');
$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_comunidade_id=tarefa_comunidade');
$sql->adCampo('DISTINCT tarefa_comunidade AS social_familia_comunidade, social_comunidade_nome, estado_sigla AS social_familia_estado, tarefa_cidade AS social_familia_municipio, municipio_nome');
$sql->adOnde('tarefa_adquirido>0');
$sql->adOnde('tarefa_social='.(int)$social_id);
if ($acao_id) $sql->adOnde('tarefa_acao='.(int)$acao_id);
if ($lista_comunidades) $sql->adOnde('tarefa_comunidade NOT IN('.$lista_comunidades.')');
if ($municipios_superintendencia) $sql->adOnde('tarefa_cidade IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('tarefa_estado=\''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('tarefa_cidade='.$municipio_id);
if ($social_comunidade_id) $sql->adOnde('tarefa_comunidade='.(int)$social_comunidade_id);
$sql->adOnde('tarefa_comunidade !=0');
$sql->adOnde('tarefa_comunidade IS NOT NULL');
$novos_comunidades=$sql->lista();
$sql->limpar();
if (count($novos_comunidades)) $vetor_comunidade=array_merge($vetor_comunidade,$novos_comunidades);


$sql->adTabela('social_acao_conceder');
$sql->adCampo('social_acao_conceder_campo, social_acao_conceder_situacao');
$sql->adOnde('social_acao_conceder_acao='.(int)$acao_id);
$parametros=$sql->Lista();
$sql->limpar();

$soma_estado_inicial=array();
$soma_estado_adquirido=array();
$soma_estado_total=array();
$soma_estado_feito=array();

$soma_municipio_inicial=array();
$soma_municipio_adquirido=array();
$soma_municipio_total=array();
$soma_municipio_feito=array();

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

$quantidade=array();
foreach ($vetor_comunidade as $comunidade){
	
	if ($comunidade['social_comunidade_nome'] && $comunidade['municipio_nome'] && $comunidade['social_familia_estado']){

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
		$quantidade[$comunidade['social_familia_estado']][$comunidade['social_familia_municipio']][$comunidade['social_familia_comunidade']]=array('comunidade' => $comunidade['social_comunidade_nome'], 'municipio' => $comunidade['municipio_nome'], 'uf' => $comunidade['social_familia_estado'] ,'inicial'=> $inicial, 'adquirido' => $adquirido ,'total' => $total, 'feito' => $completo);
		
		
		if (isset($soma_municipio_inicial[$comunidade['social_familia_municipio']])) $soma_municipio_inicial[$comunidade['social_familia_municipio']]+=$inicial;
		else $soma_municipio_inicial[$comunidade['social_familia_municipio']]=$inicial;
		
		if (isset($soma_municipio_adquirido[$comunidade['social_familia_municipio']])) $soma_municipio_adquirido[$comunidade['social_familia_municipio']]+=$adquirido;
		else $soma_municipio_adquirido[$comunidade['social_familia_municipio']]=$adquirido;
		
		if (isset($soma_municipio_total[$comunidade['social_familia_municipio']])) $soma_municipio_total[$comunidade['social_familia_municipio']]+=$total;
		else $soma_municipio_total[$comunidade['social_familia_municipio']]=$total;
		
		if (isset($soma_municipio_feito[$comunidade['social_familia_municipio']])) $soma_municipio_feito[$comunidade['social_familia_municipio']]+=$completo;
		else $soma_municipio_feito[$comunidade['social_familia_municipio']]=$completo;
		
		
		if (isset($soma_estado_inicial[$comunidade['social_familia_estado']])) $soma_estado_inicial[$comunidade['social_familia_estado']]+=$inicial;
		else $soma_estado_inicial[$comunidade['social_familia_estado']]=$inicial;
		
		if (isset($soma_estado_adquirido[$comunidade['social_familia_estado']])) $soma_estado_adquirido[$comunidade['social_familia_estado']]+=$adquirido;
		else $soma_estado_adquirido[$comunidade['social_familia_estado']]=$adquirido;
		
		if (isset($soma_estado_total[$comunidade['social_familia_estado']])) $soma_estado_total[$comunidade['social_familia_estado']]+=$total;
		else $soma_estado_total[$comunidade['social_familia_estado']]=$total;
		
		if (isset($soma_estado_feito[$comunidade['social_familia_estado']])) $soma_estado_feito[$comunidade['social_familia_estado']]+=$completo;
		else $soma_estado_feito[$comunidade['social_familia_estado']]=$completo;
		}
	
	}


$sql->adTabela('social_acao');
$sql->adCampo('social_acao_inicial, social_acao_adquirido, social_acao_final, social_acao_instalado, social_acao_instalar');
$sql->adOnde('social_acao_id='.(int)$acao_id);
$legenda=$sql->Linha();
$sql->limpar();


$antigo_municipio='';
$antigo_uf='';
$geral_inicial=0;
$geral_adquirido=0;
$geral_total=0;
$geral_feito=0;

foreach($soma_estado_inicial as $chave => $valor) $geral_inicial+=$valor;
foreach($soma_estado_adquirido as $chave => $valor) $geral_adquirido+=$valor;
foreach($soma_estado_total as $chave => $valor) $geral_total+=$valor;
foreach($soma_estado_feito as $chave => $valor) $geral_feito+=$valor;
//echo '<tr><td align=center><table class="tbl1" cellpadding=2 cellspacing=0 align=center><tr><th>UF</th><th>Município</th><th>&nbsp;'.$legenda['social_acao_adquirido'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_inicial'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_final'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalar'].'&nbsp;</th><th>&nbsp;'.$legenda['social_acao_instalado'].'&nbsp;</th></tr>';
echo '<tr><td align=center><table class="tbl1" cellpadding=2 cellspacing=0 align=center><tr><th>UF</th><th>Município</th><th>&nbsp;'.dica($legenda['social_acao_final'],ucfirst($config['beneficiarios']).' inseridos no programa social independente de preencherem ou não os requisitos para receber a ação social.').$legenda['social_acao_final'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_inicial'],ucfirst($config['beneficiarios']).' que atendem aos requisitos para receberem a ação social independente de terem sido cadastrad'.$config['genero_beneficiario'].'s na mesma.').$legenda['social_acao_inicial'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_instalar'],ucfirst($config['beneficiarios']).' em que ainda não se marcou os itens pré-definido dos checklist para sinalizar a finalização da ação social.').$legenda['social_acao_instalar'].dicaF().'&nbsp;</th><th>&nbsp;'.dica($legenda['social_acao_instalado'],ucfirst($config['beneficiarios']).' em que se marcou os itens pré-definido dos checklist que sinalizam a finalização da ação social.').$legenda['social_acao_instalado'].dicaF().'&nbsp;</th></tr>';


foreach($quantidade as $chaveEstado => $bloco_municipios){
	foreach($bloco_municipios as $chaveMunicipio => $bloco_comunidades){
		foreach($bloco_comunidades as $chaveComunidade => $linha){		
			if($antigo_uf!=$chaveEstado){
				//echo '<tr style="font-weight: bold"><td colspan=2>'.$estado[$chaveEstado].'</td><td align=center>'.(int)$soma_estado_adquirido[$chaveEstado].'</td><td align=center>'.(int)$soma_estado_inicial[$chaveEstado].'</td><td align=center>'.(int)$soma_estado_total[$chaveEstado].'</td><td align=center>'.(int)($soma_estado_total[$chaveEstado]-$soma_estado_feito[$chaveEstado]).'</td><td align=center>'.(int)$soma_estado_feito[$chaveEstado].'</td><tr>';
				echo '<tr style="font-weight: bold"><td colspan=2>'.$estado[$chaveEstado].'</td><td align=center>'.(int)$soma_estado_total[$chaveEstado].'</td><td align=center>'.(int)$soma_estado_inicial[$chaveEstado].'</td><td align=center>'.(int)($soma_estado_total[$chaveEstado]-$soma_estado_feito[$chaveEstado]).'</td><td align=center>'.(int)$soma_estado_feito[$chaveEstado].'</td><tr>';
				$antigo_uf=$chaveEstado;
				}
			
			if($antigo_municipio!=$chaveMunicipio){
				//echo '<tr style="font-weight: bold"><td colspan=2>&nbsp;&nbsp;'.$linha['municipio'].'</td><td align=center>'.(int)$soma_municipio_adquirido[$chaveMunicipio].'</td><td align=center>'.(int)$soma_municipio_inicial[$chaveMunicipio].'</td><td align=center>'.(int)$soma_municipio_total[$chaveMunicipio].'</td><td align=center>'.(int)($soma_municipio_total[$chaveMunicipio]-$soma_municipio_feito[$chaveMunicipio]).'</td><td align=center>'.(int)$soma_municipio_feito[$chaveMunicipio].'</td><tr>';
				echo '<tr style="font-weight: bold"><td colspan=2>&nbsp;&nbsp;'.$linha['municipio'].'</td><td align=center>'.(int)$soma_municipio_total[$chaveMunicipio].'</td><td align=center>'.(int)$soma_municipio_inicial[$chaveMunicipio].'</td><td align=center>'.(int)($soma_municipio_total[$chaveMunicipio]-$soma_municipio_feito[$chaveMunicipio]).'</td><td align=center>'.(int)$soma_municipio_feito[$chaveMunicipio].'</td><tr>';
				$antigo_municipio=$chaveMunicipio;
				}	
			//echo '<tr><td align=left colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;'.$linha['comunidade'].'</td><td align=center>'.(int)$linha['adquirido'].'</td><td align=center>'.$linha['inicial'].'</td><td align=center>'.$linha['total'].'</td><td align=center>'.($linha['total']-$linha['feito']).'</td><td align=center>'.$linha['feito'].'</td><tr>';
			echo '<tr><td align=left colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;'.$linha['comunidade'].'</td><td align=center>'.$linha['total'].'</td><td align=center>'.$linha['inicial'].'</td><td align=center>'.($linha['total']-$linha['feito']).'</td><td align=center>'.$linha['feito'].'</td><tr>';
			$antigo_uf=$linha['uf'];
			}
		}
	}

//echo '<tr style="font-weight: bold"><td colspan=2>Total Geral</td><td align=center>'.$geral_adquirido.'</td><td align=center>'.$geral_inicial.'</td><td align=center>'.$geral_total.'</td><td align=center>'.($geral_total-$geral_feito).'</td><td align=center>'.$geral_feito.'</td><tr>';
echo '<tr style="font-weight: bold"><td colspan=2>Total Geral</td><td align=center>'.$geral_total.'</td><td align=center>'.$geral_inicial.'</td><td align=center>'.($geral_total-$geral_feito).'</td><td align=center>'.$geral_feito.'</td><tr>';


echo '</table></td></tr>';	
echo '</table>';	
?>