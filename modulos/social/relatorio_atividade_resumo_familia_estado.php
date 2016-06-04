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
$sql->adCampo('DISTINCT social_familia_estado');
$sql->adOnde('social_acao_social='.(int)$social_id);
$sql->adOnde('social_familia_estado!=\'\'');
$sql->adOnde('social_familia_estado IS NOT NULL');
$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
$sql->adOrdem('social_familia_estado');
$vetor_estado=$sql->carregarColuna();
$sql->limpar();

$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id, social_acao_lista_descricao');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_tipo=0');
$sql->adOrdem('social_acao_lista_ordem ASC');
$lista=$sql->Lista();


echo '<table cellpadding=0 cellspacing=0>';
echo $cabecalho;
echo '<tr><td align=center><h1>Resumo de Atividades por '.ucfirst($config['beneficiario']).' nos Estados</h1></td></tr>';

$sim=array();
$nao=array();

foreach ($vetor_estado as $estado){
	
	foreach ($lista as $linha) {
		$sim[$estado][$linha['social_acao_lista_id']]=0; 
		$nao[$estado][$linha['social_acao_lista_id']]=0; 
		}
	
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->adCampo('social_familia_id');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_estado="'.$estado.'"');
	$familias=$sql->carregarColuna();
	$sql->limpar();

	foreach($familias as $familia){
		$sql->adTabela('social_familia_lista');
		$sql->adCampo('social_familia_lista_lista AS id');
		$sql->adOnde('social_familia_lista_familia='.(int)$familia);
		$lista_marcados=$sql->listaVetorChave('id', 'id');
		$sql->limpar();
		
		foreach ($lista as $linha) {
			if (isset($lista_marcados[$linha['social_acao_lista_id']])) $sim[$estado][$linha['social_acao_lista_id']]+=1; 
			else $nao[$estado][$linha['social_acao_lista_id']]+=1; 
			}
		}
	}
echo '<tr><td align=center><table cellpadding=0 cellspacing=0 align="center" border=1>';
echo '<tr><th>Ação</th>';
echo '<th colspan=3><table cellpadding=0 cellspacing=0><tr><th colspan=3 align=center>GERAL</th></tr><tr><th style="width:40px;" align=center>TOTAL</th><th style="width:30px;" align=center>OK</th><th style="width:30px;" align=center>&nbsp;NÃO</th></tr></table></th>';
foreach ($vetor_estado as $estado) echo '<th colspan=3><table cellpadding=0 cellspacing=0><tr><th colspan=3 align=center>'.$estado.'</th></tr><tr><th style="width:40px;" align=center>TOTAL</th><th style="width:30px;" align=center>OK</th><th style="width:30px;" align=center>&nbsp;NÃO</th></tr></table></th>';
echo '</tr>';

foreach ($lista as $linha) {
	echo '<tr><td>'.$linha['social_acao_lista_descricao'].'</td>';
	
	$geral_sim=0;
	$geral_nao=0;
	
	foreach ($vetor_estado as $estado){
		$geral_sim+=$sim[$estado][$linha['social_acao_lista_id']];
		$geral_nao+=$nao[$estado][$linha['social_acao_lista_id']];
		}
	
	echo '<td align=center style="width:40px;">'.($geral_sim+$geral_nao).'</td><td align=center style="width:30px;">'.$geral_sim.'</td><td align=center style="width:30px;">'.$geral_nao.'</td>';
	
	foreach ($vetor_estado as $estado) echo '<td align=center style="width:40px;">'.($sim[$estado][$linha['social_acao_lista_id']]+$nao[$estado][$linha['social_acao_lista_id']]).'</td><td align=center style="width:30px;">'.$sim[$estado][$linha['social_acao_lista_id']].'</td><td align=center style="width:30px;">'.$nao[$estado][$linha['social_acao_lista_id']].'</td>';
	
	echo '</tr>';
	}

echo '</table></td></tr>';
echo '</table>';

echo '</table>';	
?>