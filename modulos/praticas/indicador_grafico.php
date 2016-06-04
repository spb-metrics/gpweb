<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\indicador_grafico.php		

Selecionar qual tipo de gr�fico representar� o indicador. Chama Gantt.php																																									
																																												
********************************************************************************************/
global $pratica_indicador_id, $pratica_indicador, $cal_sdf, $ano; 

if(!isset($pratica_indicador_id) || !$pratica_indicador_id) $pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', 0);

$tipo_grafico = array('linha' => 'Linha', 'barra' => 'Barra', 'barra_sombra' => 'Barra com sombra', 'area' => '�rea da linha');
$tipo_agrupamento=array('dia' => 'Dia', 'semana' => 'Semana', 'mes' => 'M�s','bimestre' => 'Bimestre','trimestre' => 'Trimestre','quadrimestre' => 'Quadrimestre','semestre' => 'Semestre', 'ano' => 'Ano');
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
echo dica('Tipo de Gr�fico', 'Escolha qual a melhor representa��o gr�fica para os valores do indicador.').'Gr�fico:'.dicaF().selecionaVetor($tipo_grafico, 'pratica_indicador_tipografico', 'class="texto"', $pratica_indicador_tipografico);
echo dica('Agrupar Valores', 'Escolha qual a melhor forma de agrupar valores:<ul><li>dia - Faz-se a m�dia dos valores que s�o do mesmo dia</li><li>m�s - Faz-se a m�dia dos valores do mesmo m�s, ap�s ter sido feito a m�dia dos dias</li><li>Ano - Faz-se a m�dia dos valores do mesmo ano, ap�s ter sido feito a m�dia dos meses.</li></ul>').'&nbsp;&nbsp;Agrupar por:'.dicaF().selecionaVetor($tipo_agrupamento, 'pratica_indicador_agrupar', 'class="texto"', $pratica_indicador_agrupar);
echo dica('Mostrar Valores', 'Marque caso queira que o gr�fico mostre legenda com os valores de cada ponto marcado.').'&nbsp;&nbsp;Valores:'.dicaF().'<input type="checkbox" class="texto" name="pratica_indicador_mostrar_valor" value="1" '.($pratica_indicador_mostrar_valor ? 'checked="checked"' : '').' />';
echo dica('Pontua��o', 'Marque caso queira que o gr�fico mostre as pontua��es obtidas em vez dos valores brutos. A pontua��o � a raz�o dos valores brutos pela metas dos per�odos').'&nbsp;&nbsp;Pontua��o:'.dicaF().'<input type="checkbox" class="texto" name="pratica_indicador_mostrar_pontuacao" value="1" '.($pratica_indicador_mostrar_pontuacao ? 'checked="checked"' : '').' />';
echo dica('Mostrar t�tulo', 'Marque caso queira que o gr�fico apresente no t�tulo o nome do indicador.').'&nbsp;&nbsp;T�tulo:'.dicaF().'<input type="checkbox" class="texto" name="pratica_indicador_mostrar_titulo" value="1" '.($pratica_indicador_mostrar_titulo ? 'checked="checked"' : '').' />';
echo dica('Mostrar M�ximos e M�nimos', 'Marque caso queira que o gr�fico apresente os valores extremos para cada ponto.').'&nbsp;&nbsp;Max e Min:'.dicaF().'<input type="checkbox" class="texto" name="pratica_indicador_max_min" value="1" '.($pratica_indicador_max_min ? 'checked="checked"' : '').' />';

if ($Aplic->profissional) echo dica('Mostrar Faixas', 'Marque caso queira que o gr�fico apresente faixas de valores de bom, regular e ruim.').'&nbsp;&nbsp;Faixas:'.dicaF().'<input type="checkbox" class="texto" name="faixas" value="1" '.($faixas ? 'checked="checked"' : '').' />';


echo dica('N�mero de Pontos � Plotar', 'Qual a quantidade de valores distintos a plotar no gr�fico.<br><br>Ex: gr�fico mensal de janeiro � dezembro teria 12 pontos, com data final em dezembro.').'&nbsp;&nbsp;Nr pontos:'.dicaF().'<input type="text" name="pratica_indicador_nr_pontos" style="width:30px;" value="'.$pratica_indicador_nr_pontos.'" class="texto" />';
echo dica('Data da Final', '�ltima data a ser pesquisada quando da busca dos valores.').'&nbsp;&nbsp;Data final:'.dicaF().'<input type="hidden" name="pratica_indicador_data"  id="pratica_indicador_data" value="'.($data ? $data->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'mudar_grafico\', \'data\',\'pratica_indicador_data\');" value="'.($data ? $data->format($df) : '').'" class="texto" />'.dica('Data Final', 'Clique neste �cone '.imagem('icones/calendario.gif').' para abrir um calend�rio onde poder� selecionar a data do �ltimo valor aferido.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF();
echo '&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (document.getElementById(\'segundo\').style.display==\'none\') document.getElementById(\'segundo\').style.display=\'\'; else document.getElementById(\'segundo\').style.display=\'none\';">'.imagem('icones/comparar_indicadores.gif','Comparar Indicadores','Clique neste �cone '.imagem('icones/comparar_indicadores.gif').' para comparar os valores do indicador atual com outro a ser selecionado.').'</a>';

if ($Aplic->profissional) {
	echo '&nbsp;&nbsp;&nbsp;<a href="javascript: void(0)" onclick="exportar_excel();">'.imagem('icones/excel_p.gif', 'Exportar para Excel' , 'Clique neste �cone '.imagem('icones/excel_p.gif').' para exportar os valores do indicador para o formato excel.').'</a>'.dicaF();
	echo '&nbsp;&nbsp;&nbsp;<a href="javascript: void(0)" onclick="exportar_link();">'.imagem('icones/indicador_exporta_p.png', 'Exportar Link' , 'Ao clicar neste �cone '.imagem('icones/indicador_exporta_p.png').' ser� exibido o endere�o web para visualiza��o do indicador em ambiente externo.').'</a>'.dicaF();
	}
echo '</td><td>'.botao('exibir', 'Exibir', 'Clique neste bot�o para exibir o gr�fico de acordo com os par�metros entrados � esquerda.','','mudar_grafico.submit();').'</td>';
echo '</tr>';


echo '<tr><td id="segundo" name="segundo" style="display:'.($segundo_indicador ? '' : 'none').'">';
echo dica('Indicador de Compara��o', 'Indicador escolhiodo para comparar os gr�ficos').'Indicador de compara��o:'.dicaF();
echo '<input type="hidden" name="segundo_indicador" value="'.$segundo_indicador.'" /><input type="text" id="nome" name="segundo_indicador_nome" value="'.nome_indicador($segundo_indicador).'" style="width:250px;" class="texto" READONLY />';
echo '<a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste �cone '.imagem('icones/indicador_p.gif').' para selecionar um indicador.').'</a>';
echo dica('Data da Final', '�ltima data a ser pesquisada quando da busca dos valores.').'&nbsp;&nbsp;Data final:'.dicaF().'<input type="hidden" name="pratica_indicador_data2"  id="pratica_indicador_data2" value="'.($data2 ? $data2->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data2" style="width:70px;" id="data2" onchange="setData(\'mudar_grafico\', \'data2\',\'pratica_indicador_data2\');" value="'.($data2 ? $data2->format($df) : '').'" class="texto" />'.dica('Data Final', 'Clique neste �cone '.imagem('icones/calendario.gif').' para abrir um calend�rio onde poder� selecionar a data do �ltimo valor aferido.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF();
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
      alert('A data/hora digitada n�o corresponde ao formato padr�o. Redigite, por favor.');
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
	
