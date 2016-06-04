<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $Aplic, $cia_id, $dept_ids, $secao, $ver_min, $m, $a, $usuario_id, $tab, $cal_sdf, $recurso_id;
$Aplic->carregarCalendarioJS();
$ver_min = defVal($ver_min, false);

$usuario_id = defVal(getParam($_REQUEST, 'usuario_id', null), $Aplic->usuario_id);
$sdata = getParam($_REQUEST, 'projeto_data_inicio', 0);
$edata = getParam($_REQUEST, 'projeto_data_fim', 0);
$mostrarInativo = getParam($_REQUEST, 'mostrarInativo', '0');
if ($mostrarInativo != '0') $mostrarInativo = '1';
$mostrarMarco = getParam($_REQUEST, 'mostrarMarco', '0');
if ($mostrarMarco != '0') $mostrarMarco = '1';
$ordenarTarefasPorNome = getParam($_REQUEST, 'ordenarTarefasPorNome', '0');
$mostrarTarefaGantt = getParam($_REQUEST, 'mostrarTarefaGantt', '0');
$mostrarProjRespPertenceDept = getParam($_REQUEST, 'mostrarProjRespPertenceDept', isset($mostrarProjRespPertenceDept) ? $mostrarProjRespPertenceDept : 0);

$projetoStatus = getSisValor('StatusProjeto');
if (isset($_REQUEST['proFiltro'])) $Aplic->setEstado('ProjetoIdxFiltro', getParam($_REQUEST, 'proFiltro', null));
$proFiltro = $Aplic->getEstado('ProjetoIdxFiltro') !== null ? $Aplic->getEstado('ProjetoIdxFiltro') : '-1';
$projFiltro = unirVetores(array('-1' => 'Tod'.$config['genero_projeto'].'s '.$config['genero_projeto'].'s '.$config['projetos']), $projetoStatus);
if ($Aplic->usuario_id == $usuario_id) $projFiltro = unirVetores(array('-3' => 'Meus '.$config['projetos']), $projFiltro);
else $projFiltro = unirVetores(array('-3' => ucfirst($config['projetos']).' d'.$config['genero_usuario'].'s '.$config['usuarios']), $projFiltro);
natsort($projFiltro);
$rolar_data = 1;
$mostrar_opcao = getParam($_REQUEST, 'mostrar_opcao', 'esteMes');
$df = '%d/%m/%Y';
if ($mostrar_opcao == 'custom') {
	$data_inicio = intval($sdata) ? new CData($sdata) : new CData();
	$data_fim = intval($edata) ? new CData($edata) : new CData();
	} 
else {
	$data_inicio = new CData();
	$data_inicio->dia = 1;
	$data_fim = new CData($data_inicio);
	$data_fim->adMeses($rolar_data);
	}


echo '<form name="frmEditar" method="post" >';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
if(isset($recurso_id)) echo '<input type="hidden" name="recurso_id" value="'.$recurso_id.'" />';
echo '<input type="hidden" name="tab" value="'.$tab.'" />';
echo '<input type="hidden" name="mostrar_opcao" value="'.$mostrar_opcao.'" />';
echo '<table class="std" width="100%" border=0 cellpadding="3" cellspacing=0>';
echo '<tr><td><table border=0 width="100%" cellpadding="3" cellspacing=0>';
echo '<tr>';
echo '<td align="left" valign="middle" width="20">'.($mostrar_opcao != 'todos' ? '<a href="javascript:rolarAnterior()">'.dica('M�s Anterior', 'Clique neste �cone '.imagem('icones/anterior.gif').' para exibir o m�s anterior.').'<img src="'.acharImagem('anterior.gif').'" width="16" height="16"  border=0>'.dicaF().'</a>' : '').'</td>';
echo '<td align="left" nowrap="nowrap">';
echo dica('Data Inicial', 'O Gr�fico Gantt ser� mostrado a partir da data � direita.').'De:'.dicaF().'<input type="hidden" name="projeto_data_inicio" id="projeto_data_inicio" value="'.($data_inicio ? $data_inicio->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" style="width:70px;" name="data_inicio" id="data_inicio" onchange="setData(\'frmEditar\', \'data_inicio\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Escolher a Data Inicial', 'Clique neste �cone '.imagem('icones/calendario.gif').' para escolher a data inicial em que o gr�fico Gantt ser� visualizado.').'<img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="" border=0 />'.dicaF().'</a>'.dica('Data Final', 'O Gr�fico Gantt ser� mostrado at� a data � direita.').'At�:'.dicaF().'<input type="hidden" name="projeto_data_fim" id="projeto_data_fim" value="'.($data_fim ? $data_fim->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'frmEditar\', \'data_fim\');" value="'.($data_fim ? $data_fim->format($df) : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Escolher a Data Final', 'Clique neste �cone '.imagem('icones/calendario.gif').' para escolher a data final em que o gr�fico Gantt ser� visualizado.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="" border=0 />'.dicaF().'</a>';
echo dica('Filtro', 'Selecione na caixa de op��o � direita, quais os tipos de '.$config['projetos'].' que deseja visualizar.').'Filtro:'.dicaF().selecionaVetor($projFiltro, 'proFiltro', 'size="1" class="texto"', $proFiltro);
echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="vertical-align:middle" value="1" name="ordenarTarefasPorNome" id="ordenarTarefasPorNome" '.(($ordenarTarefasPorNome) ? 'checked="checked"' : "").' />'.dica('Ordenar por Nome das '.ucfirst($config['tarefa']), 'Clique para ordenar '.$config['genero_tarefa'].'s '.$config['tarefas'].' pelo nome das mesmas.').'Ordenar por nome d'.$config['genero_tarefa'].'s '.$config['tarefas'].dicaF();
echo '</td>'; 
echo '<td align="right">'.botao('confirmar', 'Confirmar Op��es', 'Ao fazer escolhas nas caixas de op��o � esquerda, faz-se necess�rio clicar neste bot�o para que o gr�fico Gantt seja atualizado','','document.frmEditar.mostrar_opcao.value=\'custom\'; frmEditar.submit()').'</td>';
echo '<td align="right" valign="middle" width="20">'.($mostrar_opcao != 'todos' ? '<a href="javascript:rolarProximo()">'.dica('Pr�ximo M�s', 'Clique neste �cone '.imagem('icones/proximo.gif').' para exibir o pr�ximo m�s.').'<img src="'.acharImagem('proximo.gif').'" width="16" height="16"  border=0 />'.dicaF().'</a>' :'').'</td>';
echo '</tr>';
echo '<tr><td>&nbsp;</td><td colspan="8">';
echo '</td></tr>';
echo '<tr><td align="center" valign="bottom" colspan="4">'."<a href='javascript:mostrarEsteMes()'>".dica('Mostrar apenas este m�s', 'Clique para mostrar no gr�fico Gantt apenas este M�s')."mostre este m�s".dicaF()."</a> : <a href='javascript:mostrarTodoProjeto()'>".dica('Mostrar Todo', 'Clique para mostrar no gr�fico Gantt do recurso do in�cio ao fim da aloca��o.')."mostrar todo".dicaF()."</a><br>".'</td></tr>';
echo '</table>';
$src = '?m=recursos&a=gantt&sem_cabecalho=1'.($mostrarMarco ? '&mostrarMarco=1':''). ($mostrar_opcao == 'todos' ? '' : '&data_inicio='.$data_inicio->format('%Y-%m-%d').'&data_fim='.$data_fim->format('%Y-%m-%d'))."&ordenarTarefasPorNome=$ordenarTarefasPorNome&proFiltro=$proFiltro&mostrarInativo=$mostrarInativo&recurso_id=$recurso_id&width=' + ((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95) + '";
echo '<table cellspacing=0 cellpadding=0 border="1" align="center" class="tbl1">';
echo "<tr><td><script>document.write('<img src=\"$src\">')</script>";
echo '</td></tr></table></td></tr></table></form>';
?>

<script type="text/javascript">
	var INFO_DATA = {
		 	};

  function getInfoData(date, wantsClassName) {
    var como_numero = Calendario.dateToInt(date);
    return INFO_DATA[como_numero];
  	};	

  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "projeto_data_inicio",
  	dateInfo : getInfoData,
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("projeto_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide(); 
  	}
  });
  
	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "projeto_data_fim",
		dateInfo : getInfoData,
		date : <?php echo $data_fim->format("%Y%m%d")?>,
		selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) { 
    var date = cal2.selection.get();
    if (date){
      date = Calendario.intToDate(date);
      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("projeto_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal2.hide(); 
  	}
  });



function setData( frm_nome, f_data ) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + 'projeto_' + f_data );
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

function rolarAnterior() {
	f = document.frmEditar;
	<?php
	$novo_inicio = new CData($data_inicio);
	$novo_inicio->dia = 1;
	$novo_fim = new CData($data_fim);
	$novo_inicio->adMeses(-$rolar_data);
	$novo_fim->adMeses(-$rolar_data);
	echo "f.projeto_data_inicio.value='".$novo_inicio->format(FMT_TIMESTAMP_DATA)."';";
	echo "f.projeto_data_fim.value='".$novo_fim->format(FMT_TIMESTAMP_DATA)."';";
	?>
	document.frmEditar.mostrar_opcao.value = 'custom';
	f.submit();
	}

function rolarProximo() {
	f = document.frmEditar;
	<?php
	$novo_inicio = new CData($data_inicio);
	$novo_inicio->dia = 1;
	$novo_fim = new CData($data_fim);
	$novo_inicio->adMeses($rolar_data);
	$novo_fim->adMeses($rolar_data);
	echo "f.projeto_data_inicio.value='".$novo_inicio->format(FMT_TIMESTAMP_DATA)."';";
	echo "f.projeto_data_fim.value='".$novo_fim->format(FMT_TIMESTAMP_DATA)."';";
	?>
	document.frmEditar.mostrar_opcao.value = 'custom';
	f.submit();
	}

function mostrarEsteMes() {
	document.frmEditar.mostrar_opcao.value = 'esteMes';
	document.frmEditar.submit();
	}

function mostrarTodoProjeto() {
	document.frmEditar.mostrar_opcao.value = 'todos';
	document.frmEditar.submit();
	}
</script>
