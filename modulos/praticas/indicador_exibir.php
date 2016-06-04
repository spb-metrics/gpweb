<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número RS 11802-5 e protegido pelo direito de autor. 
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
if (!($podeAcessar || $Aplic->checarModulo('praticas', 'acesso', null, 'indicador') || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');


$ano=getParam($_REQUEST, 'ano', 0);
$faixas=getParam($_REQUEST, 'faixas', 0);
$mostrar_valor=getParam($_REQUEST, 'mostrar_valor', null);
$mostrar_pontuacao=getParam($_REQUEST, 'mostrar_pontuacao', 0);
$data = new CData(getParam($_REQUEST, 'data_final', null));
$data2 = new CData(getParam($_REQUEST, 'data_final2', null));
$nr_pontos=getParam($_REQUEST, 'nr_pontos', null);
$mostrar_titulo=getParam($_REQUEST, 'mostrar_titulo',null);
$max_min=getParam($_REQUEST, 'max_min', null);
$agrupar=getParam($_REQUEST, 'agrupar', null);
$tipografico=getParam($_REQUEST, 'tipografico', null); 
$segundo_indicador=getParam($_REQUEST, 'segundo_indicador', 0);
$pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', 0);

$src = '?m=praticas&a=grafico_free&sem_cabecalho=1&ano='.$ano.'&faixas='.$faixas.'&mostrar_valor='.$mostrar_valor.'&mostrar_pontuacao='.$mostrar_pontuacao.'&data_final='.$data->format("%Y-%m-%d").'&data_final2='.$data2->format("%Y-%m-%d").'&nr_pontos='.$nr_pontos.'&mostrar_titulo='.$mostrar_titulo.'&max_min='.$max_min.'&agrupar='.$agrupar.'&tipografico='.$tipografico.'&segundo_indicador='.$segundo_indicador.'&pratica_indicador_id='.$pratica_indicador_id."&width='+((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";
echo "<table cellspacing='0' cellpadding='0' align='center' class='tbl3'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr></table>";

?>