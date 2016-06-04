<?php


include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';

$projeto_id = getParam($_REQUEST, 'projeto_id', 0);
$baseline_id = getParam($_REQUEST, 'baseline_id', 0);

if ($Aplic->profissional) {
	$barra=codigo_barra('projeto', $projeto_id, $baseline_id);
	//$barra['imagem']='<table cellspacing=0 cellpadding=0 align=center ><tr><td align=center><table cellpadding=0 cellspacing=0><tr><td align=center><img src="/gpweb/server/lib/codigobarra/barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=text&qr_btext_text=03.04.05.001.0001&height=50&scale=2&bgcolor=%23FFFFFF&color=%23000000&file=&folder&type=png&Genrate=Create+Barcode&ECLevel=L&margin=1" /></td></tr></table></td></tr></table>';
	if ($barra['cabecalho']) echo $barra['imagem'];
	}

$total=array();

$sql = new BDConsulta();
$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos','projetos');
$sql->esqUnir('projeto_qualidade', 'projeto_qualidade', 'projeto_id=projeto_qualidade_projeto');
$sql->esqUnir('projeto_comunicacao', 'projeto_comunicacao', 'projeto_id=projeto_comunicacao_projeto');
$sql->esqUnir('projeto_risco', 'projeto_risco', 'projeto_id=projeto_risco_projeto');
$sql->adCampo('projetos.*, projeto_risco_descricao, projeto_comunicacao_descricao, projeto_qualidade_descricao, projeto_cia, projeto_nome, projeto_codigo, projeto_id AS projeto_qualidade_projeto, projeto_id AS projeto_comunicacao_projeto, projeto_id AS projeto_risco_projeto');
$sql->adOnde('projeto_id = '.(int)$projeto_id);
if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
$dados = $sql->Linha();
$sql->limpar();

$sql->adTabela('artefatos_tipo');
$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
$sql->adOnde('artefato_tipo_arquivo=\'plano_gerenciamento.html\'');
$linha = $sql->linha();
$sql->limpar();
$campos = unserialize($linha['artefato_tipo_campos']);

$modelo= new Modelo;
$modelo->set_modelo_tipo(1);
foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
$modelo->set_modelo($tpl);


	
echo '<table align="left" cellspacing=0 cellpadding=0 width=1060><tr><td>';
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