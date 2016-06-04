<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $ver_min, $m, $a, $usuario_id, $tab, $tarefas, $cal_sdf, $tarefas_afazer, $baseline_id;
$Aplic->carregarCalendarioJS();
$ver_min = defVal($ver_min, false);
$projeto_id = defVal(getParam($_REQUEST, 'projeto_id', null), 0);
$caminho_critico =getParam($_REQUEST, 'caminho_critico', 0);
$somente_marco =getParam($_REQUEST, 'somente_marco', 0);
$q = new BDConsulta;
		
if (!isset($tarefa_id)) $tarefa_id= getParam($_REQUEST, 'tarefa_id', 0);
if (!$projeto_id){
	if ($tarefa_id){
		$q->adTabela('tarefas');
		$q->adCampo('tarefa_projeto');
		$q->adOnde('tarefa_id = '.$tarefa_id);
		$projeto_id = $q->Resultado();
		$q->limpar();
		}
	}

$q->adTabela('baseline');
$q->adCampo('baseline_id, concatenar_tres(formatar_data(baseline_data,\'%d/%m/%Y %H:%i\'), \' - \', baseline_nome) as nome');
$q->adOnde('baseline_projeto_id = '.$projeto_id);
$lista_baseline = $q->listaVetorChave('baseline_id', 'nome');
$q->limpar();
$lista_baseline[0]='';

	
$sdata = getParam($_REQUEST, 'projeto_data_inicio', 0);
$edata = getParam($_REQUEST, 'projeto_data_fim', 0);

$mostrarTrabalho = getParam($_REQUEST, 'mostrarTrabalho', '0');
$mostrarTrabalho = (($mostrarTrabalho != '0') ? '1' : '0');
$ordenarPorNome = getParam($_REQUEST, 'ordenarPorNome', '0');
$ordenarPorNome = (($ordenarPorNome != '0') ? '1' : '0');

$rolar_data = 1;
$mostrar_opcao = getParam($_REQUEST, 'mostrar_opcao', 'todos');
$df = '%d/%m/%Y';

if (!$sdata){
	$q->adTabela('tarefas');
	$q->adCampo('formatar_data(min(tarefa_inicio),\'%Y%m%d\')');
	$q->adOnde('tarefa_projeto = '.$projeto_id);
	$sdata = $q->Resultado();
	$q->limpar();
	}

if (!$edata){
	$q->adTabela('tarefas');
	$q->adCampo('formatar_data(max(tarefa_fim),\'%Y%m%d\')');
	$q->adOnde('tarefa_projeto = '.$projeto_id);
	$edata = $q->Resultado();
	$q->limpar();
	}

$data_inicio = intval($sdata) ? new CData($sdata) : new CData();
$data_fim = intval($edata) ? new CData($edata) : new CData();

if (!$ver_min && !($m=='tarefas' && $a=='ver') && !($m=='projetos' && $a=='ver')) {
	$botoesTitulo = new CBlocoTitulo('Gr�ficos Gantt', 'tarefa.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=tarefas', 'lista de  '.$config['tarefas'], '', 'Lista de '.ucfirst($config['tarefa']), 'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.');
	$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, 'ver '.($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto'], '', 'Ver este '.ucfirst($config['projeto']), 'Visualizar os detalhes '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.');
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}

echo '<form name="frmEditar" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="tab" value="'.$tab.'" />';
if ($projeto_id) echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
if ($tarefa_id) echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="mostrar_opcao" value="'.$mostrar_opcao.'" />';
echo '<input type="hidden" name="tarefa_id" value="'.(isset($tarefa_id) ? $tarefa_id : '').'" />';
echo '<table border=0 cellpadding=0 cellspacing=0 class="std" width="100%">';
echo '<tr><td align="left" valign="middle" width="20">';
if ($mostrar_opcao != "todos") echo '<a href="javascript:rolarAnterior()">'.dica('M�s Anterior', 'Clique neste �cone '.imagem('icones/anterior.gif').' para exibir o m�s anterior.').'<img src="'.acharImagem('anterior.gif').'" width="16" height="16" border=0>'.dicaF().'</a>';
echo '</td><td align="left" nowrap="nowrap">';
echo dica('Data Inicial', 'O Gr�fico Gantt ser� mostrado a partir da data � direita.').'De:'.dicaF().'<input type="hidden" name="projeto_data_inicio" id="projeto_data_inicio" value="'.($data_inicio ? $data_inicio->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" style="width:70px;" name="data_inicio" id="data_inicio" onchange="setData(\'frmEditar\', \'data_inicio\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Escolher a Data Inicial', 'Clique neste �cone '.imagem('icones/calendario.gif').' para escolher a data inicial em que o gr�fico Gantt ser� visualizado.').'<img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="" border=0 />'.dicaF().'</a>';
echo '&nbsp;&nbsp;'.dica('Data Final', 'O Gr�fico Gantt ser� mostrado at� a data � direita.').'At�:'.dicaF().'<input type="hidden" name="projeto_data_fim" id="projeto_data_fim" value="'.($data_fim ? $data_fim->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" style="width:70px;" name="data_fim" id="data_fim" onchange="setData(\'frmEditar\', \'data_fim\');" value="'.($data_fim ? $data_fim->format($df) : '').'" class="texto" /><a href="javascript: void(0);">'.dica('Escolher a Data Final', 'Clique neste �cone '.imagem('icones/calendario.gif').' para escolher a data final em que o gr�fico Gantt ser� visualizado.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="" border=0 />'.dicaF().'</a>';

if (count($lista_baseline)>1) echo '&nbsp;&nbsp;'.dica('Baseline','Baselines do '.$config['projeto'].'.<br>Baseline � um instant�neo que � tirado do projeto para posterior compara��o com as modifica��es realizadas, ao longo do tempo.').'Baseline: '.selecionaVetor($lista_baseline, 'baseline_id', 'style="width:400px;" class="texto"', $baseline_id);

echo '&nbsp;&nbsp;&nbsp;<input type="checkbox" style="vertical-align:middle" class="texto" name="caminho_critico" '.($caminho_critico ? 'checked="checked"' : '').' value="1" />Caminho cr�tico';
echo '&nbsp;&nbsp;&nbsp;<input type="checkbox" style="vertical-align:middle" class="texto" name="somente_marco" '.($somente_marco ? 'checked="checked"' : '').' value="1" />Somente marcos';


echo '</td>';
echo '<td align="right">'.botao('confirmar', 'Confirmar Op��es', 'Ao fazer escolhas nas caixas de op��o � esquerda, faz-se necess�rio clicar neste bot�o para que o gr�fico Gantt seja atualizado','','document.frmEditar.mostrar_opcao.value=\'custom\';frmEditar.submit();').'</td>';
echo '<td align="right" valign="middle" width="20">';
if ($mostrar_opcao != 'todos') echo '<a href="javascript:rolarProximo()">'.dica('Pr�ximo M�s', 'Clique neste �cone '.imagem('icones/proximo.gif').' para exibir o pr�ximo m�s.').'<img src="'.acharImagem('proximo.gif').'" width="16" height="16" border=0 />'.dicaF().'</a>';

echo '</form>';

echo '<tr><td align="center" valign="bottom" colspan="9">'.dica('Mostrar apenas este m�s', 'Clique para mostrar no gr�fico Gantt apenas este M�s')."<a href='javascript:mostrarEsteMes()'>mostrar este m�s</a>".dicaF()." : ".dica('Mostrar '.$config['projeto'].' complet'.$config['genero_projeto'], 'Clique para mostrar no gr�fico Gantt d'.$config['genero_projeto'].' '.$config['projeto'].' complet'.$config['genero_projeto'].'.')."<a href='javascript:mostrarTodoProjeto()'>".'mostrar projeto completo</a>'.dicaF().'<br></td></tr>';


$q->adTabela('tarefas');
$q->adCampo('COUNT(tarefa_id)');
$q->adOnde('tarefa_projeto='.(int)$projeto_id);
$cnt = $q->Resultado();
$q->limpar();


if ($cnt > 0) {
	$src = '?m=tarefas&a=gantt&sem_cabecalho=1&projeto_id='.$projeto_id.($mostrar_opcao == 'todos' ? '' : '&data_inicio='.$data_inicio->format('%Y-%m-%d').'&data_fim='.$data_fim->format('%Y-%m-%d')).(isset($a)? '&chamador='.$a : '').(isset($usuario_id)? '&usuario_id='.$usuario_id : '').($caminho_critico ? '&caminho_critico=1' :'').($somente_marco ? '&somente_marco=1' :'')."&width=' + ((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95) + '";
	echo '<tr><td colspan="20" align="center"><script>document.write(\'<img src=\"'.$src.'\">\')</script></td></tr>';
	if ($baseline_id){
		$src = '?m=tarefas&a=gantt&sem_cabecalho=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.($mostrar_opcao == 'todos' ? '' : '&data_inicio='.$data_inicio->format('%Y-%m-%d').'&data_fim='.$data_fim->format('%Y-%m-%d')).(isset($a)? '&chamador='.$a : '').(isset($usuario_id)? '&usuario_id='.$usuario_id : '').($caminho_critico ? '&caminho_critico=1' :'')."&width=' + ((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95) + '";
		echo '<tr><td colspan="20" align="center"><script>document.write(\'<img src=\"'.$src.'\">\')</script></td></tr>';	
		}
	} 
else echo '<tr><td colspan="20" align="center">nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' para mostrar</td></tr>';
echo '</table>';



if (!$ver_min && !($m=='tarefas' && $a=='ver') && !($m=='projetos' && $a=='ver')) {
	echo estiloFundoCaixa();
	}
	


?>
<script type="text/javascript">

  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "projeto_data_inicio",
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
	document.frmEditar.mostrar_opcao.value = "esteMes";
	document.frmEditar.submit();
	}

function mostrarTodoProjeto() {
	document.frmEditar.mostrar_opcao.value = "todos";
	document.frmEditar.submit();
	}
</script>

 
 
 
