<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\indicador_grafico.php		

Selecionar qual tipo de gráfico representará o indicador. Chama Gantt.php																																									
																																												
********************************************************************************************/
global $pratica_indicador_id, $pratica_indicador, $cal_sdf, $ano; 

if(!isset($pratica_indicador_id) || !$pratica_indicador_id) $pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', 0);

$tipo_grafico = array('linha' => 'Linha', 'barra' => 'Barra', 'barra_sombra' => 'Barra com sombra', 'area' => 'Área da linha');
$tipo_agrupamento=array('dia' => 'Dia', 'semana' => 'Semana', 'mes' => 'Mês','bimestre' => 'Bimestre','trimestre' => 'Trimestre','quadrimestre' => 'Quadrimestre','semestre' => 'Semestre', 'ano' => 'Ano');
$Aplic->carregarCalendarioJS();
$faixas=getParam($_REQUEST, 'faixas', 0);
$pratica_indicador_mostrar_valor=getParam($_REQUEST, 'pratica_indicador_mostrar_valor', (isset($_REQUEST['pratica_indicador_nr_pontos']) ? 0 : $pratica_indicador['pratica_indicador_mostrar_valor']));
$pratica_indicador_mostrar_pontuacao=getParam($_REQUEST, 'pratica_indicador_mostrar_pontuacao', 0);
$pratica_indicador_mostrar_titulo=getParam($_REQUEST, 'pratica_indicador_mostrar_titulo', (isset($_REQUEST['pratica_indicador_nr_pontos']) ? 0 : $pratica_indicador['pratica_indicador_mostrar_titulo']));
$pratica_indicador_max_min=getParam($_REQUEST, 'pratica_indicador_max_min', (isset($_REQUEST['pratica_indicador_nr_pontos']) ? 0 : $pratica_indicador['pratica_indicador_max_min']));
$pratica_indicador_tipografico=getParam($_REQUEST, 'pratica_indicador_tipografico', $pratica_indicador['pratica_indicador_tipografico']); 
$pratica_indicador_agrupar=(isset($_REQUEST['pratica_indicador_agrupar']) ? getParam($_REQUEST, 'pratica_indicador_agrupar', null) : $pratica_indicador['pratica_indicador_agrupar']);
$pratica_indicador_nr_pontos=(isset($_REQUEST['pratica_indicador_nr_pontos']) ? getParam($_REQUEST, 'pratica_indicador_nr_pontos', null) : $pratica_indicador['pratica_indicador_nr_pontos']);



$segundo_indicador=getParam($_REQUEST, 'segundo_indicador', 0);

$df = '%d/%m/%Y';

$data = (isset($_REQUEST['pratica_indicador_data']) ? new CData(getParam($_REQUEST, 'pratica_indicador_data', null)) : new CData());

$data2 = (isset($_REQUEST['pratica_indicador_data2']) ? new CData(getParam($_REQUEST, 'pratica_indicador_data2', null)) : new CData());


echo '<form name="mudar_grafico" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="indicador_ver" />';
echo '<input type="hidden" name="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';

echo '<table cellspacing=0 cellpadding=0 align="center" class="std2" width="100%">';
echo '<tr><td>';
echo dica('Tipo de Gráfico', 'Escolha qual a melhor representação gráfica para os valores do indicador.').'Gráfico:'.dicaF().selecionaVetor($tipo_grafico, 'pratica_indicador_tipografico', 'class="texto"', $pratica_indicador_tipografico);
echo dica('Agrupar Valores', 'Escolha qual a melhor forma de agrupar valores:<ul><li>dia - Faz-se a média dos valores que são do mesmo dia</li><li>mês - Faz-se a média dos valores do mesmo mês, após ter sido feito a média dos dias</li><li>Ano - Faz-se a média dos valores do mesmo ano, após ter sido feito a média dos meses.</li></ul>').'&nbsp;&nbsp;Agrupar por:'.dicaF().selecionaVetor($tipo_agrupamento, 'pratica_indicador_agrupar', 'class="texto"', $pratica_indicador_agrupar);
echo dica('Mostrar Valores', 'Marque caso queira que o gráfico mostre legenda com os valores de cada ponto marcado.').'&nbsp;&nbsp;Valores:'.dicaF().'<input type="checkbox" class="texto" name="pratica_indicador_mostrar_valor" value="1" '.($pratica_indicador_mostrar_valor ? 'checked="checked"' : '').' />';
echo dica('Pontuação', 'Marque caso queira que o gráfico mostre as pontuações obtidas em vez dos valores brutos. A pontuação é a razão dos valores brutos pela metas dos períodos').'&nbsp;&nbsp;Pontuação:'.dicaF().'<input type="checkbox" class="texto" name="pratica_indicador_mostrar_pontuacao" value="1" '.($pratica_indicador_mostrar_pontuacao ? 'checked="checked"' : '').' />';
echo dica('Mostrar título', 'Marque caso queira que o gráfico apresente no título o nome do indicador.').'&nbsp;&nbsp;Título:'.dicaF().'<input type="checkbox" class="texto" name="pratica_indicador_mostrar_titulo" value="1" '.($pratica_indicador_mostrar_titulo ? 'checked="checked"' : '').' />';
echo dica('Mostrar Máximos e Mínimos', 'Marque caso queira que o gráfico apresente os valores extremos para cada ponto.').'&nbsp;&nbsp;Max e Min:'.dicaF().'<input type="checkbox" class="texto" name="pratica_indicador_max_min" value="1" '.($pratica_indicador_max_min ? 'checked="checked"' : '').' />';

if ($Aplic->profissional) echo dica('Mostrar Faixas', 'Marque caso queira que o gráfico apresente faixas de valores de bom, regular e ruim.').'&nbsp;&nbsp;Faixas:'.dicaF().'<input type="checkbox" class="texto" name="faixas" value="1" '.($faixas ? 'checked="checked"' : '').' />';


echo dica('Número de Pontos à Plotar', 'Qual a quantidade de valores distintos a plotar no gráfico.<br><br>Ex: gráfico mensal de janeiro à dezembro teria 12 pontos, com data final em dezembro.').'&nbsp;&nbsp;Nr pontos:'.dicaF().'<input type="text" name="pratica_indicador_nr_pontos" style="width:30px;" value="'.$pratica_indicador_nr_pontos.'" class="texto" />';
echo dica('Data da Final', 'Última data a ser pesquisada quando da busca dos valores.').'&nbsp;&nbsp;Data final:'.dicaF().'<input type="hidden" name="pratica_indicador_data"  id="pratica_indicador_data" value="'.($data ? $data->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'mudar_grafico\', \'data\',\'pratica_indicador_data\');" value="'.($data ? $data->format($df) : '').'" class="texto" />'.dica('Data Final', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data do último valor aferido.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF();
echo '&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (document.getElementById(\'segundo\').style.display==\'none\') document.getElementById(\'segundo\').style.display=\'\'; else document.getElementById(\'segundo\').style.display=\'none\';">'.imagem('icones/comparar_indicadores.gif','Comparar Indicadores','Clique neste ícone '.imagem('icones/comparar_indicadores.gif').' para comparar os valores do indicador atual com outro a ser selecionado.').'</a>';

if ($Aplic->profissional) {
	echo '&nbsp;&nbsp;&nbsp;<a href="javascript: void(0)" onclick="exportar_excel();">'.imagem('icones/excel_p.gif', 'Exportar para Excel' , 'Clique neste ícone '.imagem('icones/excel_p.gif').' para exportar os valores do indicador para o formato excel.').'</a>'.dicaF();
	echo '&nbsp;&nbsp;&nbsp;<a href="javascript: void(0)" onclick="exportar_link();">'.imagem('icones/indicador_exporta_p.png', 'Exportar Link' , 'Ao clicar neste ícone '.imagem('icones/indicador_exporta_p.png').' será exibido o endereço web para visualização do indicador em ambiente externo.').'</a>'.dicaF();
	}
echo '</td><td>'.botao('exibir', 'Exibir', 'Clique neste botão para exibir o gráfico de acordo com os parâmetros entrados à esquerda.','','mudar_grafico.submit();').'</td>';
echo '</tr>';


echo '<tr><td id="segundo" name="segundo" style="display:'.($segundo_indicador ? '' : 'none').'">';
echo dica('Indicador de Comparação', 'Indicador escolhiodo para comparar os gráficos').'Indicador de comparação:'.dicaF();
echo '<input type="hidden" name="segundo_indicador" value="'.$segundo_indicador.'" /><input type="text" id="nome" name="segundo_indicador_nome" value="'.nome_indicador($segundo_indicador).'" style="width:250px;" class="texto" READONLY />';
echo '<a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um indicador.').'</a>';
echo dica('Data da Final', 'Última data a ser pesquisada quando da busca dos valores.').'&nbsp;&nbsp;Data final:'.dicaF().'<input type="hidden" name="pratica_indicador_data2"  id="pratica_indicador_data2" value="'.($data2 ? $data2->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data2" style="width:70px;" id="data2" onchange="setData(\'mudar_grafico\', \'data2\',\'pratica_indicador_data2\');" value="'.($data2 ? $data2->format($df) : '').'" class="texto" />'.dica('Data Final', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data do último valor aferido.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF();
echo '</td></tr>';



echo '<tr><td colspan=20>';
$src = '?m=praticas&a=grafico_free&sem_cabecalho=1&ano='.$ano.'&faixas='.$faixas.'&mostrar_valor='.$pratica_indicador_mostrar_valor.'&mostrar_pontuacao='.$pratica_indicador_mostrar_pontuacao.'&data_final='.$data->format("%Y-%m-%d").'&data_final2='.$data2->format("%Y-%m-%d").'&nr_pontos='.$pratica_indicador_nr_pontos.'&mostrar_titulo='.$pratica_indicador_mostrar_titulo.'&max_min='.$pratica_indicador_max_min.'&agrupar='.$pratica_indicador_agrupar.'&tipografico='.$pratica_indicador_tipografico.'&segundo_indicador='.$segundo_indicador.'&pratica_indicador_id='.$pratica_indicador_id."&width='+((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";

echo "<table cellspacing='0' cellpadding='0' align='center' class='tbl3'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr></table>";
echo '</td></tr></table>';
echo '</form>';


echo '<script language="javascript">function exportar_excel(){url_passar(1, \'m=praticas&a=indicador_exportar_excel_pro&sem_cabecalho=1&ano='.$ano.'&faixas='.$faixas.'&mostrar_valor='.$pratica_indicador_mostrar_valor.'&mostrar_pontuacao='.$pratica_indicador_mostrar_pontuacao.'&data_final='.$data->format("%Y-%m-%d").'&data_final2='.$data2->format("%Y-%m-%d").'&nr_pontos='.$pratica_indicador_nr_pontos.'&mostrar_titulo='.$pratica_indicador_mostrar_titulo.'&max_min='.$pratica_indicador_max_min.'&agrupar='.$pratica_indicador_agrupar.'&tipografico='.$pratica_indicador_tipografico.'&segundo_indicador='.$segundo_indicador.'&pratica_indicador_id='.$pratica_indicador_id.'\');}</script>';	

?>
<script language="javascript">

function exportar_link(){

	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link&dialogo=1&tipo=indicador&ano=<?php echo $ano ?>&faixas=<?php echo $faixas ?>&mostrar_valor=<?php echo $pratica_indicador_mostrar_valor ?>&mostrar_pontuacao=<?php echo $pratica_indicador_mostrar_pontuacao ?>&data_final=<?php echo $data->format("%Y-%m-%d") ?>&data_final2=<?php echo $data2->format("%Y-%m-%d") ?>&nr_pontos=<?php echo $pratica_indicador_nr_pontos ?>&mostrar_titulo=<?php echo $pratica_indicador_mostrar_titulo ?>&max_min=<?php echo $pratica_indicador_max_min ?>&agrupar=<?php echo $pratica_indicador_agrupar ?>&tipografico=<?php echo $pratica_indicador_tipografico ?>&segundo_indicador=<?php echo $segundo_indicador ?>&pratica_indicador_id=<?php echo $pratica_indicador_id ?>', null, window);

	}



function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Indicador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+<?php echo $pratica_indicador['pratica_indicador_cia'] ?>, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+<?php echo $pratica_indicador['pratica_indicador_cia'] ?>, 'Indicador','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setIndicador(chave, valor){
	mudar_grafico.segundo_indicador.value=chave;
	mudar_grafico.segundo_indicador_nome.value=valor;
	}

 var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "pratica_indicador_data",
  	date :  <?php echo $data->format("%Y%m%d")?>,
  	selection: <?php echo $data->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pratica_indicador_data").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide(); 
  	}
  });
 
 
 var cal2 = Calendario.setup({
  	trigger    : "f_btn2",
    inputField : "pratica_indicador_data2",
  	date2 :  <?php echo $data2->format("%Y%m%d")?>,
  	selection: <?php echo $data2->format("%Y%m%d")?>,
    onSelect: function(cal2) { 
    var date2 = cal2.selection.get();
    if (date2){
    	date2 = Calendario.intToDate(date2);
      document.getElementById("data2").value = Calendario.printDate(date2, "%d/%m/%Y");
      document.getElementById("pratica_indicador_data2").value = Calendario.printDate(date2, "%Y-%m-%d");
      }
  	cal2.hide(); 
  	}
  }); 
 
 
   
function setData( frm_nome, f_data , f_data_real) {
	campo_data = eval( 'document.'+frm_nome+ '.'+f_data);
	campo_data_real = eval( 'document.'+ frm_nome+'.'+f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
    	} 
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		} 
	else campo_data_real.value = '';
	} 
	
</script>	
	
