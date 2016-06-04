<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
    
gpweb\modulos\praticas\exportar_grafico_indicador.php    

Exportação do gráfico dos valores do indicador                                                                                  
                                                                                        
********************************************************************************************/
global $config, $Aplic;
include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';

ini_set('memory_limit', $config['resetar_limite_memoria']);
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_canvas'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_iconplot'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_line'));

function exportarGraficoIndicador(
$pratica_indicador_id, 
$tipo_grafico, 
$largura, 
$mostrar_valor, 
$mostrar_titulo,
$media_movel, 
$agrupar, 
$mostrar_max_min, 
$nr_pontos,
$data_final, 
$data_final2, 
$segundo_indicador,
$mostrar_pontuacao, 
$faixas, 
$ano, 
$arquivo = ''){

  global $config, $Aplic;
  $sql = new BDConsulta;

  $sql->adTabela('pratica_indicador');
  $sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
  $sql->adCampo('pratica_indicador_requisito_referencial, pratica_indicador_nr_pontos, pratica_indicador_max_min, pratica_indicador_sentido, pratica_indicador_unidade, pratica_indicador_cor, pratica_indicador_nome, pratica_indicador_tipografico, pratica_indicador_agrupar, pratica_indicador_mostrar_valor, pratica_indicador_mostrar_titulo, pratica_indicador_media_movel');
  $sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
  $pratica_indicador=$sql->Linha();
  $sql->limpar();
  
  $tipo_grafico= $tipo_grafico ? $tipo_grafico : $pratica_indicador['pratica_indicador_tipografico'];
  $mostrar_valor= $mostrar_valor ? $mostrar_valor : $pratica_indicador['pratica_indicador_mostrar_valor'];
  $mostrar_titulo= $mostrar_titulo ? $mostrar_titulo :$pratica_indicador['pratica_indicador_mostrar_titulo'];
  $media_movel= $media_movel ? $media_movel : $pratica_indicador['pratica_indicador_media_movel'];
  $agrupar= $agrupar ? $agrupar : $pratica_indicador['pratica_indicador_agrupar'];
  $mostrar_max_min= $mostrar_max_min ? $mostrar_max_min : $pratica_indicador['pratica_indicador_max_min'];
  $nr_pontos= $nr_pontos ? $nr_pontos : $pratica_indicador['pratica_indicador_nr_pontos'];

  $sql->adTabela('pratica_indicador_meta');
  $sql->adCampo('pratica_indicador_meta_indicador, pratica_indicador_meta_valor_meta, pratica_indicador_meta_valor_referencial, pratica_indicador_meta_data, pratica_indicador_meta_valor_meta_boa, pratica_indicador_meta_valor_meta_regular, pratica_indicador_meta_valor_meta_ruim, pratica_indicador_meta_proporcao');
  $sql->adOnde('pratica_indicador_meta_indicador='.(int)$pratica_indicador_id);
  $sql->adOrdem('pratica_indicador_meta_data DESC');
  $metas=$sql->lista();
  $sql->limpar();


  if (strtolower($pratica_indicador['pratica_indicador_cor'])=='ffffff') $pratica_indicador['pratica_indicador_cor']='dadada';

  if ($mostrar_max_min) include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_error'));

  if ($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra') include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_bar'));

  $a=array();
  $ydata =array();
  $max_min=array();
  $max=array();
  $min=array();
  $yreferencia=array();

  $ymeta=array();
  $ymeta_boa=array();
  $ymeta_regular=array();
  $ymeta_ruim=array();

  if ($agrupar=='bimestre') $mes=array('01' =>'1 B', '02' =>'1 B', '03' =>'2 B', '04' =>'2 B', '05' =>'3 B', '06' =>'3 B', '07' =>'4 B', '08' =>'4 B', '09' =>'5 B', '10' =>'5 B', '11' =>'6 B', '12' =>'6 B');
  elseif ($agrupar=='trimestre') $mes=array('01' =>'1 T', '02' =>'1 T', '03' =>'1 T', '04' =>'2 T', '05' =>'2 T', '06' =>'2 T', '07' =>'3 T', '08' =>'3 T', '09' =>'3 T', '10' =>'4 T', '11' =>'4 T', '12' =>'4 T');
  elseif ($agrupar=='quadrimestre') $mes=array('01' =>'1 Q', '02' =>'1 Q', '03' =>'1 Q', '04' =>'1 Q', '05' =>'2 Q', '06' =>'2 Q', '07' =>'2 Q', '08' =>'2 Q', '09' =>'3 Q', '10' =>'3 Q', '11' =>'3 Q', '12' =>'3 Q');
  elseif ($agrupar=='semestre') $mes=array('01' =>'1 S', '02' =>'1 S', '03' =>'1 S', '04' =>'1 S', '05' =>'1 S', '06' =>'1 S', '07' =>'2 S', '08' =>'2 S', '09' =>'2 S', '10' =>'2 S', '11' =>'2 S', '12' =>'2 S');
  else $mes=array('01' =>'Jan', '02' =>'Fev', '03' =>'Mar', '04' =>'Abr', '05' =>'Mai', '06' =>'Jun', '07' =>'Jul', '08' =>'Ago', '09' =>'Set', '10' =>'Out', '11' =>'Nov', '12' =>'Dez');


  if ($segundo_indicador){
    $sql->adTabela('pratica_indicador');
    $sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
    $sql->adCampo('pratica_indicador_requisito_referencial, pratica_indicador_nr_pontos, pratica_indicador_max_min, pratica_indicador_sentido, pratica_indicador_unidade, pratica_indicador_cor, pratica_indicador_nome, pratica_indicador_tipografico, pratica_indicador_agrupar, pratica_indicador_mostrar_valor, pratica_indicador_mostrar_titulo, pratica_indicador_media_movel');
    $sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$segundo_indicador);
    $pratica_indicador2=$sql->Linha();
    $sql->limpar();
    $obj_indicador2 = new Indicador($segundo_indicador);
    $vetor_valores2=$obj_indicador2->Pontos($nr_pontos, $agrupar, null, null, null, !$mostrar_pontuacao, $data_final2);
    krsort($vetor_valores2);
    $tamanho2=count($vetor_valores2);
    foreach($vetor_valores2 as $chave=> $valor){
      if($agrupar=='ano') $data=$chave;
      elseif ($agrupar=='mes'){
        $ano=substr($chave, 0, 4);
        $mes1=substr($chave, 4, 2);
        
        $data=$mes[$mes1].' '.$ano;
        }
      else{
        $ano=substr($chave, 0, 4);
        $mes=substr($chave, 4, 2);
        $dia=substr($chave, 6, 2);
        $data=$dia.'/'.$mes.'/'.$ano;
        }
      $valores2[]=array('valor' => $valor['valor'], 'min' => $valor['min'], 'max' => $valor['max'], 'data'=> $data);
      }
    }


  $obj_indicador = new Indicador($pratica_indicador_id);
  $vetor_valores=$obj_indicador->Pontos($nr_pontos, $agrupar, null, null, null, !$mostrar_pontuacao, $data_final);

  $tamanho=count($vetor_valores);
  krsort($vetor_valores);


  if ($tamanho < 1 || $nr_pontos < 1 || (($nr_pontos < 2 || $tamanho < 2) && $tipo_grafico=='linha') || (($nr_pontos < 2 || $tamanho < 2) && $tipo_grafico=='area')){
    //caso não tenha no mínimo de pontos para o gráfico requerido
    $g = new CanvasGraph(300,50,'auto');
    $txt="sem valores suficientes para o grafico";
    $t = new Text($txt,2,20);
    $t->SetFont(FF_ARIAL,FS_BOLD,12);
    $t->Align('left','top');
    $t->ParagraphAlign('center');
    $t->Stroke($g->img);
    return $g->Stroke($arquivo);
    }

  foreach($vetor_valores as $chave=> $valor){
    if($agrupar=='ano') $data=$chave;
    elseif ($agrupar=='mes' || $agrupar=='bimestre' || $agrupar=='trimestre' || $agrupar=='quadrimestre' || $agrupar=='semestre'){
      $ano=substr($chave, 0, 4);
      $mes1=substr($chave, 4, 2);
      $data=$mes[$mes1].' '.$ano;
      }
    elseif ($agrupar=='semana'){
      $ano=substr($chave, 0, 4);
      $mes=substr($chave, 4, 2);
      $dia=substr($chave, 6, 2);
      $data=date("W", mktime(0,0,0,$mes,$dia,$ano)).' Sem. '.$ano;
      }  
    else{
      $ano=substr($chave, 0, 4);
      $mes=substr($chave, 4, 2);
      $dia=substr($chave, 6, 2);
      $data=$dia.'/'.$mes.'/'.$ano;
      }
    $valores[]=array('valor' => $valor['valor'], 'min' => $valor['min'], 'max' => $valor['max'], 'data' => $data, 'chave' => $chave);
    }
    

  $tamanho=count($valores)-1;
  for($i=$tamanho; $i>=0; $i--){
    $ydata[$tamanho-$i]=$valores[$i]['valor'];  
    $max[$tamanho-$i]=$valores[$i]['max'];  
    $min[$tamanho-$i]=$valores[$i]['min'];
    
    
    if ($segundo_indicador && isset($valores2[$i]['valor'])) $ydata2[$tamanho-$i]=$valores2[$i]['valor'];  
    elseif ($segundo_indicador) $ydata2[$tamanho-$i]=0;
    
    
    //legendas

    $meta=vetor_meta($metas, $valores[$i]['chave']);
    
    if ($meta['pratica_indicador_meta_proporcao']) $meta['pratica_indicador_meta_valor_meta']=meta_periodo_anterior($meta['pratica_indicador_meta_indicador'], (float)$meta['pratica_indicador_meta_valor_meta'], $meta['pratica_indicador_meta_data']);
    
    $a[$tamanho-$i]=($segundo_indicador && isset($valores2[$i]['data']) && $valores2[$i]['data']!=$valores[$i]['data'] ? $valores2[$i]['data'].'/' : '').$valores[$i]['data'];

    $yreferencia[$tamanho-$i]=$meta['pratica_indicador_meta_valor_referencial'];
    if ($faixas) $ymeta_boa[$tamanho-$i]=$meta['pratica_indicador_meta_valor_meta_boa'];
    if ($faixas) $ymeta_regular[$tamanho-$i]=$meta['pratica_indicador_meta_valor_meta_regular'];
    if ($faixas) $ymeta_ruim[$tamanho-$i]=$meta['pratica_indicador_meta_valor_meta_ruim'];
    $ymeta[$tamanho-$i]=$meta['pratica_indicador_meta_valor_meta'];
    }

  //if ($meta['pratica_indicador_meta_valor_referencial']!=null && ($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra')) $yreferencia[$tamanho+1]=$meta['pratica_indicador_meta_valor_referencial'];
  if (($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra')) $yreferencia[$tamanho+1]=$meta['pratica_indicador_meta_valor_referencial'];
  if ($faixas && ($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra')) $ymeta_boa[$tamanho+1]=$meta['pratica_indicador_meta_valor_meta_boa'];
  if ($faixas && ($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra')) $ymeta_regular[$tamanho+1]=$meta['pratica_indicador_meta_valor_meta_regular'];
  if ($faixas && ($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra')) $ymeta_ruim[$tamanho+1]=$meta['pratica_indicador_meta_valor_meta_ruim'];

  if ($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra') $ymeta[$tamanho+1]=$meta['pratica_indicador_meta_valor_meta'];


  if (($meta['pratica_indicador_meta_valor_referencial'] || $meta['pratica_indicador_meta_valor_meta']) && ($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra')) {
    $a[]='';
    }



  if ($valores && ((count($valores)>1 && ($tipo_grafico=='linha' || $tipo_grafico=='area'))   ||  (count($valores) && ($tipo_grafico!='linha' && $tipo_grafico!='area'))) ){ 
    $grafico = new Graph($largura,600);
    $grafico->SetScale('textlin');
    $grafico->ygrid->Show(true,true);
    $grafico->xgrid->Show(true,false);
    $grafico->xaxis->SetTickLabels($a);
    $grafico->xaxis->SetLabelAngle(90);
    $grafico->img->SetMargin(70,15, 45, 75);
    $grafico->SetFrame(false);
    if ($tipo_grafico=='area') {
      $grafico_indicador=new LinePlot($ydata);
      $grafico_indicador->SetFillGradient('#'.$pratica_indicador['pratica_indicador_cor'].'@0.3','white@0.3');
      }
    if ($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra') {
      $grafico_indicador = new BarPlot($ydata);
      $grafico_indicador->SetFillGradient('#'.$pratica_indicador['pratica_indicador_cor'].'@0.3','white@0.3',GRAD_VERT);
      if ($tipo_grafico=='barra_sombra') $grafico_indicador->SetShadow(); 
      }
    if ($tipo_grafico=='linha') {
      $grafico_indicador=new LinePlot($ydata);
      $grafico_indicador->SetColor('#1b12cf');
      $grafico_indicador->SetWeight(2);
      
      $grafico_indicador->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
      $grafico_indicador->mark->SetColor('#67acf3');
      $grafico_indicador->mark->SetFillColor('#67acf3');
      $grafico_indicador->SetCenter();
      }
      
    if ($mostrar_max_min) {
      for($i=0; $i< count($max); $i++){
        $max_min[]=$min[$i];  
        $max_min[]=$max[$i];    
        }
      $graf_max_min=new ErrorPlot($max_min);
      $graf_max_min->SetColor("green@0.5");
      $graf_max_min->SetWeight(2);
      }    
    if ($mostrar_valor){
      $grafico_indicador->value->Show();
      $grafico_indicador->value->SetFont(FF_ARIAL,FS_BOLD,10);
      $grafico_indicador->value->SetAngle(90);
      if ($config['casas_decimais'] > 0) $grafico_indicador->value->SetFormat('%0.'.$config['casas_decimais'].'f'.($mostrar_pontuacao ? '%%' : ''));
      else $grafico_indicador->value->SetFormat('%0.0f'.($mostrar_pontuacao ? '%%' : ''));
      }
    
    if ($mostrar_titulo) $grafico->title->Set($pratica_indicador['pratica_indicador_nome'].($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].')' : ''));
    if ($mostrar_valor) $grafico->yaxis->scale->SetGrace(20);  
    
    //adicionar o 2o grafico
    if ($segundo_indicador){
      if ($tipo_grafico=='area') {
        $grafico_indicador2=new LinePlot($ydata2);
        $grafico_indicador2->SetFillGradient('#2aab24@0.3','white@0.3');
        }
      if ($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra') {
        $grafico_indicador2 = new BarPlot($ydata2);
        $grafico_indicador2->SetFillGradient('#2aab24@0.3','white@0.3',GRAD_VERT);
        if ($tipo_grafico=='barra_sombra') $grafico_indicador2->SetShadow(); 
        }
      if ($tipo_grafico=='linha') {
        $grafico_indicador2=new LinePlot($ydata2);
        $grafico_indicador2->SetColor('#2aab24@0.3');
        $grafico_indicador2->SetWeight(2);
        }
      
      if ($tipo_grafico=='barra' || $tipo_grafico=='barra_sombra'){
        $gbplot = new GroupBarPlot(array($grafico_indicador2,$grafico_indicador));
        $grafico->Add($gbplot);
        }
      else {
        $grafico->Add($grafico_indicador2);
        $grafico->Add($grafico_indicador);
        }
      }  
    else $grafico->Add($grafico_indicador);
    

    if ($mostrar_max_min) $grafico->Add($graf_max_min);
    
    
    if ($meta['pratica_indicador_meta_valor_referencial']!=null && !$mostrar_pontuacao){
      $linha_horizontal=new LinePlot($yreferencia);
      $linha_horizontal->SetLegend("Referencial".($pratica_indicador['pratica_indicador_requisito_referencial'] ? "\n".substr($pratica_indicador['pratica_indicador_requisito_referencial'],0,25).(strlen($pratica_indicador['pratica_indicador_requisito_referencial'])>26 ? '...' :'') : ''));
      $linha_horizontal->SetColor('#'.$config['cor_referencial']);
      $linha_horizontal->SetWeight(2);
      $grafico->Add($linha_horizontal);
      }

    //meta
   if(!$mostrar_pontuacao){
	    $linha_horizontal=new LinePlot($ymeta);
	    $linha_horizontal->SetLegend("Meta");
	    $linha_horizontal->SetColor("#68e4ad");
	    $linha_horizontal->SetWeight(2);
	    $linha_horizontal->SetCenter();
	    $grafico->Add($linha_horizontal);
	
	    if ($faixas && (count($ymeta_boa)==count($ymeta))){
	      $linha_horizontal_bom=new LinePlot($ymeta_boa);
	      $linha_horizontal_bom->SetLegend("Bom");
	      $linha_horizontal_bom->SetColor('#'.$config['cor_bom']);
	      $linha_horizontal_bom->SetWeight(2);
	      $grafico->Add($linha_horizontal_bom);
	      }
	
	    if ($faixas && (count($ymeta_regular)==count($ymeta))){
	      $linha_horizontal_regular=new LinePlot($ymeta_regular);
	      $linha_horizontal_regular->SetLegend("Regular");
	      $linha_horizontal_regular->SetColor('#'.$config['cor_regular']);
	      $linha_horizontal_regular->SetWeight(2);
	      $grafico->Add($linha_horizontal_regular);
	      }
	      
	    if ($faixas && (count($ymeta_ruim)==count($ymeta))){
	      $linha_horizontal_ruim=new LinePlot($ymeta_ruim);
	      $linha_horizontal_ruim->SetLegend("Ruim");
	      $linha_horizontal_ruim->SetColor('#'.$config['cor_ruim']);
	      $linha_horizontal_ruim->SetWeight(2);
	      $grafico->Add($linha_horizontal_ruim);
	      }
			}
			
    $grafico->legend->Pos(0,0);
    $icone = new IconPlot('./estilo/rondon/imagens/icones/'.($pratica_indicador['pratica_indicador_sentido'] ? 'acima' : 'abaixo').'.gif',0.02,0.001,1,30);
    $icone->SetAnchor('left','top');
    $grafico->Add($icone);
    return $grafico->Stroke($arquivo);
    }
  else{
    $grafico = new CanvasGraph(360,30);  
    $t1 = new Text("Não há valores suficientes registrados neste indicador");
    $t1->SetPos(16,5);
    $t1->SetOrientation("h");
    $grafico->AddText($t1);
    return $grafico->Stroke($arquivo);
    }
  }  
  
function vetor_meta($metas, $data){
  if (strlen($data)==6) $data=substr($data, 0, 4).'-'.substr($data, 4, 2);
  elseif (strlen($data)==8) $data=substr($data, 0, 4).'-'.substr($data, 4, 2).'-'.substr($data, 6, 2);
  foreach ($metas as $chave => $linha) {
    if (strlen($data)==4)  $data_comparar=substr($linha['pratica_indicador_meta_data'], 0, 4);
    else if (strlen($data)==7)  $data_comparar=substr($linha['pratica_indicador_meta_data'], 0, 7);
    else $data_comparar=$linha['pratica_indicador_meta_data'];
    if ($data >= $data_comparar) return $linha;
    }
  return null;  
  }  
  
function meta_periodo_anterior($pratica_indicador_meta_indicador, $multiplo, $data){
  global $sql;
  $sql->adTabela('pratica_indicador_meta');
  $sql->adCampo('pratica_indicador_meta_valor_meta, pratica_indicador_meta_data, pratica_indicador_meta_proporcao');
  $sql->adOnde('pratica_indicador_meta_indicador='.(int)$pratica_indicador_meta_indicador);
  $sql->adOnde('pratica_indicador_meta_data <\''.$data.'\'');
  $sql->adOrdem('pratica_indicador_meta_data DESC');
  
  $meta_achada=$sql->linha();
  
  $sql->limpar();

  if ($meta_achada && !(int)$meta_achada['pratica_indicador_meta_proporcao']) {
    $saida=(float)$meta_achada['pratica_indicador_meta_valor_meta']*(float)$multiplo;
    }
  else if ($meta_achada) {
    $saida=meta_periodo_anterior((int)$pratica_indicador_meta_indicador, (float)$multiplo*(float)$meta_achada['pratica_indicador_meta_valor_meta'], $meta_achada['pratica_indicador_meta_data']);
    }
  else {
    $saida=0;
    }
  return $saida;
  }
?>
