<?php 

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
$sql = new BDConsulta;

$largura = getParam($_REQUEST, 'width', 800);
$familia_id = getParam($_REQUEST, 'familia_id', 0);
$acao_id = getParam($_REQUEST, 'acao_id', 0);
$comunidade_id=getParam($_REQUEST, 'comunidade_id', 0);


$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id, social_acao_lista_descricao');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_tipo=0');
$sql->adOrdem('social_acao_lista_ordem ASC');
$lista=$sql->Lista();

$soma=array();
$total_concluido=0;
$total_problema=0;
$total=0;
foreach ($lista as $linha) {
	$soma[$linha['social_acao_lista_id']]=0;
	$legenda[]=$linha['social_acao_lista_descricao'];
	}

$legenda[]='Finalizado';
$legenda[]='Problema';


$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->adCampo('social_familia_id, social_familia_acao_concluido');
$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
$sql->adOnde('social_familia_comunidade='.(int)$comunidade_id);
$familias=$sql->Lista();
$sql->limpar();

$soma=array();
$total_concluido=0;
$total_problema=0;
$total=0;
foreach ($lista as $linha) $soma[$linha['social_acao_lista_id']]=0;
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

$valores=array();
foreach ($soma as $linha) $valores[]=(int)(($linha*100)/$total);
$valores[]=(int)(($total_concluido*100)/$total);
$valores[]=(int)(($total_problema*100)/$total);


include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_bar'));


$datay=$valores;


$graph = new Graph(600,600,'auto');
$graph->SetScale("textlin");


$graph->Set90AndMargin(250,40,40,40);
$graph->img->SetAngle(90); 


$graph->SetBox(false);


$graph->ygrid->Show(false);
$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels($legenda);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);


$graph->SetBackgroundGradient('#00CED1', '#FFFFFF', GRAD_HOR, BGRAD_PLOT);


$b1plot = new BarPlot($datay);

$graph->Add($b1plot);

$b1plot->SetWeight(0);
$b1plot->SetFillGradient("#808000","#90EE90",GRAD_HOR);
$b1plot->SetWidth(17);

$graph->Stroke();

?>