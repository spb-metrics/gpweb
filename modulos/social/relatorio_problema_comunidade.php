<?php

global $sql, $perms, $Aplic, $tab, $status_id, $dialogo, $estado_sigla, $estado, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id, $opcao_id;

//problema_tipo==0 nas Familias
//problema_tipo==1 no Comitê Nacional
//problema_tipo==2 no Comitê Estaduais
//problema_tipo==3 no Comitê Municipais
//problema_tipo==4 no Comitê Comunitários


if ($opcao_id=='problema_comunidade_beneficiario') $tipo=0;
elseif ($opcao_id=='problema_comunidade_comunidade') $tipo=4;


echo '<table cellpadding=0 cellspacing=0 align=center>';
echo '<tr><td align=center><h1>Lista dos Problemas '.($tipo ? 'nos Comitê Comunitários' : 'n'.$config['genero_beneficiario'].'s '.ucfirst($config['beneficiarios'])).'</h1><br></td></tr>';

$sql->adTabela('social_acao_problema');
$sql->adCampo('DISTINCT social_acao_problema_id, social_acao_problema_descricao');
$sql->adOnde('social_acao_problema_tipo='.(int)$tipo);
$sql->adOnde('social_acao_problema_acao_id='.(int)$acao_id);
$sql->adOrdem('social_acao_problema_ordem');
$tipos_problema=$sql->lista();
$sql->limpar();


$qnt=count($tipos_problema);
$resultado=array();

foreach($tipos_problema as $problema){
	if ($tipo==0){
		//familia
		$sql->adTabela('social_familia_problema');
		$sql->esqUnir('social_familia','social_familia', 'social_familia_problema_familia=social_familia_id');
		$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
		$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
		$sql->esqUnir('municipios', 'municipios', 'municipio_id=social_familia_municipio');
		$sql->esqUnir('estado', 'estado', 'estado.estado_sigla=social_familia_estado');
		$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_comunidade_id=social_familia_comunidade');
		$sql->adCampo('estado_nome, municipio_nome, social_comunidade_nome, count(social_familia_problema_tipo) AS total');
		$sql->adOnde('social_acao_social='.(int)$social_id);
		$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
		if ($status_id) $sql->adOnde('social_familia_problema_status='.(int)$status_id);
		if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
		if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
		if ($social_comunidade_id) $sql->adOnde('social_familia_comunidade='.(int)$social_comunidade_id);
		$sql->adOnde('social_familia_problema_tipo='.(int)$problema['social_acao_problema_id']);
		$sql->adOrdem('estado_nome, municipio_nome, social_comunidade_nome');
		$sql->adGrupo('social_familia_comunidade');
		$vetor_comunidade=$sql->lista();
		$sql->limpar();
		foreach($vetor_comunidade as $linha) $resultado[$linha['estado_nome']][$linha['municipio_nome']][$linha['social_comunidade_nome']][$problema['social_acao_problema_id']]=$linha['total'];
		}
		


	if ($tipo==4){
		//comitês
		$sql->adTabela('social_comite_problema');
		$sql->esqUnir('social_comite','social_comite', 'social_comite_problema_comite=social_comite_id');
		$sql->esqUnir('social_comite_acao', 'social_comite_acao', 'social_comite_acao_comite=social_comite_id');
		$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_comite_acao_acao');
		$sql->esqUnir('municipios', 'municipios', 'municipio_id=social_comite_municipio');
		$sql->esqUnir('estado', 'estado', 'estado.estado_sigla=social_comite_estado');
		$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_comunidade_id=social_comite_comunidade');
		$sql->adCampo('estado_nome, municipio_nome, social_comunidade_nome, count(social_comite_problema_tipo) AS total');
		$sql->adOnde('social_acao_social='.(int)$social_id);
		$sql->adOnde('social_comite_acao_acao='.(int)$acao_id);
		if ($status_id) $sql->adOnde('social_comite_problema_status='.(int)$status_id);
		if ($estado_sigla) $sql->adOnde('social_comite_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('social_comite_municipio="'.$municipio_id.'"');
		if ($social_comunidade_id) $sql->adOnde('social_comite_comunidade='.(int)$social_comunidade_id);
		$sql->adOnde('social_comite_problema_tipo='.(int)$problema['social_acao_problema_id']);
		$sql->adOrdem('estado_nome, municipio_nome, social_comunidade_nome');
		$sql->adGrupo('social_comite_comunidade');
		$vetor_comunidade=$sql->lista();
		$sql->limpar();
		foreach($vetor_comunidade as $linha) $resultado[$linha['estado_nome']][$linha['municipio_nome']][$linha['social_comunidade_nome']][$problema['social_acao_problema_id']]=$linha['total'];
		}		
	}

if (count($resultado)){
	//cabeçalho
	$cabecalho='';
	foreach($tipos_problema as $chave => $problema) $cabecalho.= '<td>'.($chave+1).'</td>';
	echo '<tr><td><table cellpadding=0 cellspacing=0 class="tbl1" align=center>';
	foreach($tipos_problema as $chave => $problema) echo '<tr><td align=right colspan='.($chave+2).' >'.$problema['social_acao_problema_descricao'].'</td></tr>';
	
	foreach ($resultado as $uf => $linha){
		echo '<tr><td align="left" colspan='.($qnt+1).' ><b>'.$uf.'</b></td></tr>';
		
		foreach ($linha as $municipio_nome => $linha2){
			echo '<tr><td colspan='.($qnt+1).'>&nbsp;&nbsp;&nbsp;<b>'.$municipio_nome.'</b></td></tr>';
			foreach ($linha2 as $comunidade_nome => $linha3){
				echo '<tr><td width="200">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comunidade_nome.'</td>';
				foreach($tipos_problema as $problema) echo '<td width="20" align="center">'.(isset($linha3[$problema['social_acao_problema_id']]) ? $linha3[$problema['social_acao_problema_id']] : '0').'</td>';
				echo '</tr>';
				}	
			}
		}
	}	
else echo '<tr><td align=center>Não foi encontrado nenhum problema baseado nos parâmetros passados</td></tr>';	

echo '</table></td></tr></table>';


function texto_vertical1($legenda){
	$saida='';
	for ($i=0; $i< strlen($legenda); $i++) $saida.=$legenda[$i].'<br>';
	return $saida;
	}
?>