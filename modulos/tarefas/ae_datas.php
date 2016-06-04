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
global $Aplic, $config, $opcoes_tarefa_superior, $carregarDeTab, $localidade_tipo_caract, $obj;
global $tipoDuracao, $tarefa_projeto, $tarefa_id, $tab,$cal_sdf, $wbs_completo, $permite_editar_data;

$cia_id=0;

if ($tarefa_id){
	$q = new BDConsulta;
	$q->adTabela('tarefa_depts','td');
	$q->esqUnir('tarefas', 't', 't.tarefa_id = td.tarefa_id');
	$q->esqUnir('depts', 'd', 'd.dept_id = td.departamento_id');
	$q->adCampo('d.dept_cia');
	$q->adOnde('td.tarefa_id = '.(int)$tarefa_id);
	$cia_id = $q->Resultado();
	$q->limpar();
	}
if(!$cia_id){
	$q = new BDConsulta;
	$q->adTabela('projetos','p');
	$q->adCampo('p.projeto_cia');
	$q->adOnde('p.projeto_id = '.(int)$tarefa_projeto);
	$cia_id = $q->Resultado();
	$q->limpar();
	}
if(!$cia_id) $cia_id =$Aplic->usuario_cia;
$Aplic->carregarCalendarioJS();

$inicio = 0;
$fim = 24;
$inc =1;

$horas = array();

for ($atual = $inicio; $atual < $fim + 1; $atual++) {
	if ($atual < 10) $chave_atual = "0".$atual;
	else $chave_atual = $atual;
	if (stristr($Aplic->getPref('formatohora'), '%p')) $horas[$chave_atual] = ($atual > 12 ? $atual - 12 : $atual);
	else 	$horas[$chave_atual] = $atual;
	}

$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;
$df = '%d/%m/%Y';
if($Aplic->profissional){
	$data_inicio = intval( $obj->tarefa_inicio ) ? new CData( $obj->tarefa_inicio ) : false;
	$data_fim = intval( $obj->tarefa_fim ) ? new CData( $obj->tarefa_fim ) : false;

	if(!$data_inicio || !$data_fim){
		require_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';
		$cache = CTarefaCache::getInstance();

		$exped = $cache->getExpedienteParaHoje((int)$cia_id, (int)$tarefa_projeto);
		if(!$data_inicio){
			$data_inicio = $exped['inicio'];
			}

		if(!$data_fim){
			$desloc = $config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8;
			$data_fim = $cache->deslocaDataPraFrente($data_inicio, $desloc, (int)$cia_id, (int)$tarefa_projeto);
			$data_fim = $cache->ajustaInicioPeriodo($data_fim, (int)$cia_id, (int)$tarefa_projeto);
			}

		$obj->tarefa_duracao = $cache->horasPeriodo($data_inicio, $data_fim, (int)$cia_id, (int)$tarefa_projeto);

		if(is_string($data_inicio)) $data_inicio = new CData( $data_inicio );
		if(is_string($data_fim)) $data_fim = new CData( $data_fim );
		}
	}
else{
	$data_inicio = intval( $obj->tarefa_inicio ) ? new CData( $obj->tarefa_inicio ) : new CData( date( "Y-m-d H:i:s" ) );
	$data_fim = intval( $obj->tarefa_fim ) ? new CData( $obj->tarefa_fim ) : new CData( date( "Y-m-d H:i:s" ) );
	}



echo '<input name="fazerSQL" type="hidden" value="fazer_tarefa_aed" />';
echo '<input name="tarefa_id" type="hidden" value="'.$tarefa_id.'" />';
echo '<table width="100%" border=0 cellpadding=0 cellspacing=1 class="std">';

echo '<input type="hidden" id="tarefa_inicio" name="tarefa_inicio" value="" />';
echo '<input type="hidden" id="tarefa_fim" name="tarefa_fim" value="" />';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de In�cio', 'Digite ou escolha no calend�rio a data prov�vel de in�cio d'.$config['genero_tarefa'].' '.$config['tarefa']).'Data de In�cio:'.dicaF().'</td><td nowrap="nowrap" width="100%"><table><tr><td><input type="hidden" id="oculto_data_inicio" name="oculto_data_inicio"  value="'.$data_inicio->format('%Y-%m-%d').'" /><input type="text" '.($wbs_completo || !$permite_editar_data ? 'READONLY': '' ).' onchange="setData(\'frmEditar\', \'data_inicio\'); data_ajax();" class="texto" style="width:70px;" id="data_inicio" name="data_inicio" value="'.$data_inicio->format($df).'" />'.($wbs_completo || !$permite_editar_data ? '' : '<a href="javascript: void(0);">'.dica('Data de In�cio', 'Clique neste �cone '.imagem('icones/calendario.gif').' para abrir um calend�rio onde poder� selecionar a data prov�vel de in�cio d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 />'.dicaF().'</a>').dica('Hora do In�cio', 'Selecione na caixa de sele��o a hora do �nicio d'.$config['genero_tarefa'].' '.$config['tarefa']). selecionaVetor($horas, 'inicio_hora', 'size="1" onchange="data_ajax();" class="texto" '.($wbs_completo || !$permite_editar_data ? 'disabled="disabled"': '' ), $data_inicio->getHour()).' : '.dica('Minutos do In�cio', 'Selecione na caixa de sele��o os minutos do �nicio d'.$config['genero_tarefa'].' '.$config['tarefa']).selecionaVetor($minutos, 'inicio_minutos', 'size="1" class="texto" onchange="data_ajax();" '.($wbs_completo || !$permite_editar_data ? 'disabled="disabled"': '' ), $data_inicio->getMinute()).'</td><td>'.botao('expediente', 'Expediente para '.nome_cia($cia_id),'Visualizar o expediente para '.strtolower($config['organizacao']).' � qual pertence est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.<br><br>Observa��es: Caso haja mais de '.($config['genero_dept']=='o' ? 'um ': 'uma ').strtolower($config['departamento']).' '.($config['genero_dept']=='o' ? 'cadastrado ': 'cadastrada ').' apenas a '.strtolower($config['organizacao']).' da primeira ocorrrencia ser� considerada.<br><br>Quando do cadastro de nov'.$config['genero_tarefa'].' '.$config['tarefa'].', ser� considerado a '.strtolower($config['organizacao']).' '.($config['genero_dept']=='o' ? 'do primeiro ': 'da primeira ').strtolower($config['departamento']).' respons�vel pel'.$config['genero_projeto'].' '.$config['projeto'].'.','','window.open(\'./index.php?m=calendario&a=jornada&projeto_id='.$tarefa_projeto.'&tarefa_id='.$tarefa_id.'&cia_id='.(int)$cia_id.'&sem_selecao=1&dialogo=1\', \'expediente\',\'height=500,width=820,resizable,scrollbars=yes\')').'</td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de T�rmino', 'Digite ou escolha no calend�rio a data prov�vel de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].'.</p>Caso n�o saiba a data prov�vel de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').'Data de T�rmino:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" id="oculto_data_fim" name="oculto_data_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" '.($wbs_completo || !$permite_editar_data ? 'READONLY': '' ).' onchange="setData(\'frmEditar\', \'data_fim\'); horas_ajax();" class="texto" style="width:70px;" id="data_fim" name="data_fim" value="'.($data_fim ? $data_fim->format($df) : '').'" />'.($wbs_completo || !$permite_editar_data ? '' : '<a href="javascript: void(0);">'.dica('Data de T�rmino', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data prov�vel de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 />'.dicaF().'</a>').dica('Hora do T�rmino', 'Selecione na caixa de sele��o a hora do t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].'.</p>Caso n�o saiba a hora prov�vel de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').selecionaVetor($horas, 'hora_fim', 'size="1" onchange="horas_ajax();" class="texto" '.($wbs_completo || !$permite_editar_data ? 'disabled="disabled"': '' ), $data_fim ? $data_fim->getHour() : $fim).' : '.dica('Minutos do T�rmino', 'Selecione na caixa de sele��o os minutos do t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].'. </p>Caso n�o saiba os minutos prov�veis de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').selecionaVetor($minutos, 'minuto_fim', 'size="1" class="texto" onchange="horas_ajax();" '.($wbs_completo || !$permite_editar_data ? 'disabled="disabled"': '' ), $data_fim ? $data_fim->getMinute() : '00').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Dura��o', 'Selecionando o n�mero dias de dura��o far� o sistema calcular a data prov�vel de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Dura��o esperada:'.dicaF().'</td><td nowrap="nowrap"><input type="text" onchange="data_ajax();" onkeypress="return somenteFloat(event)" '.($wbs_completo || !$permite_editar_data ? 'READONLY': '' ).' class="texto" name="tarefa_duracao" id="tarefa_duracao" maxlength="8" size="2" value="'.float_brasileiro(isset($obj->tarefa_duracao) ? $obj->tarefa_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8) : 0).'" />&nbsp;dias</td></tr>';

echo '</table>';
echo selecao_calendarios($data_inicio, $data_fim, (isset($tarefa_projeto) ? $tarefa_projeto :''),'','oculto_data_inicio','oculto_data_fim','CompararDatas();','data_ajax();','horas_ajax();');


function cal_dia_util_conv($val) {
	global $localidade_tipo_caract, $Aplic;
	setlocale(LC_TIME, $Aplic->usuario_linguagem);
	$semana = Data_Calc::getCalendarioSemana(null, null, null, '%a', (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1));
	setlocale(LC_ALL, $Aplic->usuario_linguagem);
	$nome_dia = $semana[($val - (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1)) % 7];
	if ($localidade_tipo_caract == 'utf-8' && function_exists('utf8_encode')) $nome_dia = utf8_encode($nome_dia);
	return htmlentities($nome_dia, ENT_COMPAT, $localidade_tipo_caract);
	}

?>

<script language="javascript">
function somenteFloat(e){
	var tecla=new Number();
	if(window.event) tecla = e.keyCode;
	else if(e.which) tecla = e.which;
	else return true;
	if(((tecla < "48") && tecla !="44") || (tecla > "57")) return false;
	}	
		

function CompararDatas(){
    var str1 = document.getElementById("data_inicio").value;
    var str2 = document.getElementById("data_fim").value;
    var dt1  = parseInt(str1.substring(0,2),10);
    var mon1 = parseInt(str1.substring(3,5),10);
    var yr1  = parseInt(str1.substring(6,10),10);
    var dt2  = parseInt(str2.substring(0,2),10);
    var mon2 = parseInt(str2.substring(3,5),10);
    var yr2  = parseInt(str2.substring(6,10),10);
    var date1 = new Date(yr1, mon1, dt1);
    var date2 = new Date(yr2, mon2, dt2);
    if(date2 < date1){
      document.getElementById("data_fim").value=document.getElementById("data_inicio").value;
      document.getElementById("oculto_data_fim").value=document.getElementById("oculto_data_inicio").value;
    	}
   }


function setData(frm_nome, f_data) {
	campo_data = eval( 'document.'+frm_nome+'.'+f_data );
	campo_data_real = eval( 'document.'+frm_nome+'.'+'oculto_'+f_data );
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

      //data final fazer ao menos no mesmo dia da inicial
      CompararDatas();

			}
		}
	else campo_data_real.value = '';
	}

function horas_ajax(){
	var f=document.frmEditar;
	var inicio=f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minutos.value+':00';
	var fim=f.oculto_data_fim.value+' '+f.hora_fim.value+':'+f.minuto_fim.value+':00';
	xajax_calcular_duracao(inicio, fim, <?php echo $cia_id ?>, <?php echo $tarefa_projeto ?>, <?php echo ($tarefa_id ? $tarefa_id : 'null') ?>);
	}


function data_ajax(){
	var f=document.frmEditar;
	var inicio=f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minutos.value+':00';
	var horas=f.tarefa_duracao.value;
	xajax_data_final_periodo(inicio, horas, <?php echo $cia_id ?>, <?php echo $tarefa_projeto ?>, <?php echo ($tarefa_id ? $tarefa_id : 'null') ?>);
	}


</script>
<?php

?>
