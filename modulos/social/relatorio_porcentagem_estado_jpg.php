<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
$sql = new BDConsulta;

$superintendencia_id = getParam($_REQUEST, 'superintendencia_id', 0);
if ($superintendencia_id){
	$sql->adTabela('social_superintendencia_municipios');
	$sql->adCampo('municipio_id');
	$sql->adOnde('social_superintendencia_id='.(int)$superintendencia_id);
	$municipios_superintendencia=$sql->carregarColuna();
	$sql->limpar();
	$municipios_superintendencia=implode(',',$municipios_superintendencia);
	}
else $municipios_superintendencia='';



include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_bar'));

$largura = getParam($_REQUEST, 'width', 800);
$social_id = getParam($_REQUEST, 'social_id', 0);
$acao_id = getParam($_REQUEST, 'acao_id', 0);
$estado_sigla= getParam($_REQUEST, 'estado_sigla', '');
$municipio_id= getParam($_REQUEST, 'municipio_id', 0);

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

$sql->adTabela('social_acao');
$sql->adCampo('social_acao_inicial, social_acao_adquirido, social_acao_final, social_acao_instalado, social_acao_instalar');
$sql->adOnde('social_acao_id='.(int)$acao_id);
$legenda=$sql->Linha();
$sql->limpar();

$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->adCampo('DISTINCT social_familia_estado');
$sql->adOnde('social_acao_social='.(int)$social_id);
$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
if ($estado_sigla) $sql->adOnde('social_familia_estado=\''.$estado_sigla.'\'');
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.(int)$municipio_id);
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

$quantidade=array();

$sql->adTabela('social_acao_conceder');
$sql->adCampo('social_acao_conceder_campo, social_acao_conceder_situacao');
$sql->adOnde('social_acao_conceder_acao='.(int)$acao_id);
$parametros=$sql->Lista();
$sql->limpar();


if (!count($vetor_estado)){
	//caso não tenha no mínimo de valores para o gráfico requerido
	include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_canvas'));
	$g = new CanvasGraph(300,50,'auto');
	$txt="sem valores suficientes para o grafico";
	$t = new Text($txt,2,20);
	$t->SetFont(FF_ARIAL,FS_BOLD,12);
	$t->Align('left','top');
	$t->ParagraphAlign('center');
	$t->Stroke($g->img);
	$g->Stroke();
	exit();
	}


foreach ($vetor_estado as $estado){

	$sql->adTabela('social_familia');
	$sql->adCampo('count(social_familia_id)');
	foreach($parametros as $parametro) $sql->adOnde($parametro['social_acao_conceder_campo'].' '.$parametro['social_acao_conceder_situacao']);
	$sql->adOnde('social_familia_estado=\''.$estado.'\'');
	$inicial=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('tarefas');
	$sql->adCampo('SUM(tarefa_adquirido)');
	$sql->adOnde('tarefa_acao='.(int)$acao_id);
	$sql->adOnde('tarefa_estado=\''.$estado.'\'');
	$adquirido=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_estado=\''.$estado.'\'');
	$total=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_estado=\''.$estado.'\'');
	$completo=$sql->Resultado();
	$sql->limpar();
	

	$quantidade[$estado]=array('inicial'=> $inicial, 'adquirido' => $adquirido ,'total' => $total, 'feito' => $completo);
	}
$data1y=array();
$data2y=array();
$data3y=array();
$data4y=array();
$data5y=array();
foreach($quantidade as $chave => $linha){
	if ($chave){
		$legenda[]=$chave;
		$data1y[]=$linha['inicial'];
		$data2y[]=$linha['adquirido'];
		$data3y[]=$linha['total'];
		$data4y[]=$linha['feito'];
		$data5y[]=($linha['total']-$linha['feito']);
		}
	}

$grafico = new Graph((int)($largura*0.75),(int)($largura/2),'auto');
$grafico->SetScale("textlin");

$grafico->SetBox(false);

$grafico->ygrid->SetFill(false);
$grafico->xaxis->SetTickLabels($legenda);
$grafico->yaxis->HideLine(false);
$grafico->yaxis->HideTicks(false,false);

$b1plot = new BarPlot($data1y);
$b2plot = new BarPlot($data2y);
$b3plot = new BarPlot($data3y);
$b4plot = new BarPlot($data4y);
$b5plot = new BarPlot($data5y);


$gbplot = new GroupBarPlot(array($b2plot,$b3plot,$b1plot,$b5plot,$b4plot));
$grafico->Add($gbplot);
$grafico->SetMargin(40,0,5,0);

$b1plot->SetColor("white");
$b1plot->SetFillColor("#fe8637");
$b1plot->SetLegend($legenda['social_acao_inicial']);

$b2plot->SetColor("white");
$b2plot->SetFillColor("#7598d9");
$b2plot->SetLegend($legenda['social_acao_adquirido']);

$b3plot->SetColor("white");
$b3plot->SetFillColor("#b32c16");
$b3plot->SetLegend($legenda['social_acao_final']);


$b4plot->SetColor("white");
$b4plot->SetFillColor("#f5cd2d");
$b4plot->SetLegend($legenda['social_acao_instalado']);


$b5plot->SetColor("white");
$b5plot->SetFillColor("#aebad5");
$b5plot->SetLegend($legenda['social_acao_instalar']);


$grafico->legend->SetFrameWeight(1);
$grafico->legend->SetColumns(3);
$grafico->legend->SetColor('#4E4E4E','#00A78A');

$grafico->Stroke();
	
?>