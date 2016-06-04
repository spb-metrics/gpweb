<?php


include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';

$projeto_abertura_id = getParam($_REQUEST, 'projeto_abertura_id', 0);
$baseline_id = getParam($_REQUEST, 'baseline_id', 0);


$sql = new BDConsulta();
$sql->adTabela('projeto_abertura');
$sql->adCampo('projeto_abertura.*, projeto_abertura_projeto AS projeto_id');
$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
$dados = $sql->Linha();
$sql->limpar();

if ($Aplic->profissional){
	$barra=codigo_barra('projeto', $dados['projeto_id']);
	if ($barra['cabecalho']) echo $barra['imagem'];
	}

$sql->adTabela('artefatos_tipo');
$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
$sql->adOnde('artefato_tipo_arquivo=\'termo_abertura.html\'');
$linha = $sql->linha();
$sql->limpar();
$campos = unserialize($linha['artefato_tipo_campos']);

$modelo= new Modelo;
$modelo->set_modelo_tipo(1);
foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
$modelo->set_modelo($tpl);


	
echo '<table align="left" cellspacing=0 cellpadding=0 width=750><tr><td>';
for ($i=1; $i <= $modelo->quantidade(); $i++){
	$campo='campo_'.$i;
	$tpl->$campo = $modelo->get_campo($i);
	} 
echo $tpl->exibir($modelo->edicao); 
echo '</td></tr>';
if ($Aplic->profissional && $barra['rodape']) echo '<tr><td>'.$barra['imagem'].'</td></tr>';
echo '</table>';


if ($dialogo && !$Aplic->pdf_print) echo '<script>self.print();</script>';
?>