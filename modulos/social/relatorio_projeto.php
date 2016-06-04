<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cabecalho, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $estado_sigla, $estado, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;



//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

$sql->adTabela('tarefas');
$sql->esqUnir('projetos','projetos','tarefa_projeto=projeto_id');
$sql->esqUnir('municipios','municipios','municipio_id=projeto_cidade');
$sql->adCampo('DISTINCT tarefa_projeto, projeto_nome, projeto_estado, municipio_nome');
$sql->adOnde('tarefa_acao='.(int)$acao_id);
if ($municipios_superintendencia) $sql->adOnde('tarefa_cidade IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('tarefa_estado=\''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('tarefa_cidade='.(int)$municipio_id);
if ($social_comunidade_id) $sql->adOnde('tarefa_comunidade='.(int)$social_comunidade_id);
$sql->adOrdem('projeto_estado, municipio_nome, projeto_nome');
$vetor=$sql->lista();
$sql->limpar();
$qnt=0;

echo '<table cellpadding=0 cellspacing=0 align=center class="tbl1">';
echo $cabecalho;
echo '<tr><th align=center colspan=4><h1>Lista de Projetos - Total de '.count($vetor).'</h1></th></tr>';
echo '<tr><th>Nr</th><th>Nome</th><th>Município</th><th>Estado</th></tr>';

foreach($vetor as $linha){
	$qnt++;
	echo '<tr><td width=20 align=right>'.$qnt.'</td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=ver&projeto_id='.$linha['tarefa_projeto'].'\');">'.$linha['projeto_nome'].'</a></td><td>'.($linha['municipio_nome'] ? $linha['municipio_nome'] : '&nbsp;').'</td><td>'.($linha['projeto_estado'] ? $linha['projeto_estado'] : '&nbsp;').'</td><tr>';
	}
echo '</table>';


?>