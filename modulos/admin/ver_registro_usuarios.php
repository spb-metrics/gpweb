<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $tabAtualId, $cal_sdf, $cf, $cia_id, $usuario_id, $lista_cias;

$df = '%d/%m/%Y';
$data_inicio = new CData(getParam($_REQUEST, 'reg_data_inicio', date('Y-m-d')));
$data_fim = new CData(getParam($_REQUEST, 'reg_data_fim', date('Y-m-d')));

$Aplic->carregarCalendarioJS();

echo '<form method="post" name="frmData">';
echo '<input type="hidden" name="m" value="admin" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="mostrarDetalhes" value="" />';
echo '<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';


echo '<table width="100%" align="center" class="std"><tr><td>';

	echo '<table align="center">';
	echo '<tr><td colspan="2"><h1>'.dica('Registro dos Acessos dos '.ucfirst($config['usuarios']), 'Ao marcar a data inícial e final da pesquisa, será mostrado a data/hora de entrada e saída d'.$config['genero_usuario'].'s '.$config['usuarios'].', assim como o IP da máquina dos mesmos.').'Registro dos Acessos do '.ucfirst($config['usuario']).dicaF().'</h1></td></tr>';
	echo '<tr><td align="right" width="45%" >'.dica('Data Inícial', 'Digite ou escolha no calendário a data inícial da pesquisa.').'Data Inicial:'.dicaF().'</td><td width="55%" align="left"><input type="hidden" name="reg_data_inicio" id="reg_data_inicio" value="'.($data_inicio ? $data_inicio->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'frmData\', \'data_inicio\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data inícial da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
	echo '<tr align="center"><td align="right" width="45%">'.dica('Data Final', 'Digite ou escolha no calendário a data final da pesquisa.').'Data Final:'.dicaF().'</td><td width="55%" align="left"><input type="hidden" name="reg_data_fim" id="reg_data_fim" value="'.($data_fim ? $data_fim->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'frmData\', \'data_fim\');" value="'.($data_fim ? $data_fim->format($df) : '').'" class="texto" />'.dica('Data Final', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data final da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
	echo '<tr align="center"><td colspan="2">'.botao('Visualizar', 'Visualizar', 'Visualizar os resultados de acordo com as datas fornecidas.','','checarData()').'</td></tr>';
	echo '</table>';

if (getParam($_REQUEST, 'mostrarDetalhes', 0)) {
	$data_inicio1 = date('Y-m-d 00:00:00', strtotime(getParam($_REQUEST, 'reg_data_inicio', date('Y-m-d'))));
	$data_fim1 = date('Y-m-d 23:59:59', strtotime(getParam($_REQUEST, 'reg_data_fim', date('Y-m-d'))));
	$q = new BDConsulta;
	$q->adTabela('usuario_reg_acesso');
	$q->esqUnir('usuarios', 'usuarios','usuario_reg_acesso.usuario_id = usuarios.usuario_id');
	$q->esqUnir('contatos', 'contatos','usuario_contato = contato_id');
	$q->adCampo('usuario_reg_acesso.usuario_id, usuario_ip, entrou, saiu, ultima_atividade');
	if ($cia_id && !$lista_cias) $q->adOnde('contato_cia='.(int)$cia_id);
	elseif ($lista_cias) $q->adOnde('contato_cia IN ('.$lista_cias.')');
	if (isset($usuario_id) && $usuario_id) $q->adOnde('usuarios.usuario_id = '.(int)$usuario_id);
	$q->adOnde('entrou >=\''.$data_inicio1.'\'');
	$q->adOnde('ultima_atividade <= \''.$data_fim1.'\' OR saiu <= \''.$data_fim1.'\'');
	$q->adOrdem('entrou DESC');
	$logs = $q->Lista();

	echo '<table align="center" cellspacing=0 class="tbl1" width="50%">';
	echo '<tr>';
	echo '<th nowrap="nowrap" >'.dica(ucfirst($config['usuario']), 'Nome d'.$config['genero_usuario'].' '.$config['usuario'].' à qual se está verificando as datas e horas de entrada e saída do Sistema.').ucfirst($config['usuario']).dicaF().'</th>';
	echo '<th nowrap="nowrap" >'.dica('Endereço IP', 'O IP (Internet Protocol) da máquina que foi utilizada pel'.$config['genero_usuario'].' '.$config['usuario'].' para entrar no Sistema.').'Endereço IP'.dicaF().'</th>';
	echo '<th nowrap="nowrap" >'.dica('Data/Hora Entrou', 'A Data e hora de entrada d'.$config['genero_usuario'].' '.$config['usuario'].' no Sistema.').'Data/Hora Entrou'.dicaF().'</th>';
	echo '<th nowrap="nowrap" >'.dica('Data/Hora Saiu', 'A Data e hora de saída d'.$config['genero_usuario'].' '.$config['usuario'].' no Sistema.').'Data/Hora Saiu'.dicaF().'</th>';
	echo '</tr>';
	$qnt=0;
	foreach ($logs as $detalhe) {
		$qnt++;
		$entrou = new CData($detalhe['entrou']);
		$saiu = new CData($detalhe['saiu']);
		echo '<tr>';
		echo '<td align="center">'.($detalhe['usuario_id'] ? link_usuario($detalhe['usuario_id']) : 'desconhecido').'</td>';
		echo '<td align="center">'.($detalhe['usuario_ip'] ? $detalhe['usuario_ip'] : '&nbsp;').'</td>';
		echo '<td align="center">'.($detalhe['entrou'] ? $entrou->format($cf) : '&nbsp;').'</td>';
		echo '<td align="center">'.($detalhe['saiu'] ? $saiu->format($cf) : '&nbsp;').'</td>';
		echo '</tr>';
		}
	if (!$qnt) echo '<tr><td colspan="4"><p>Nenhum registro encontrado</p></td></tr>';
	echo '</table>';
	}
echo '</td></tr></table>';

echo '</form>';
?>
<script type="text/javascript">
  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "reg_data_inicio",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) {
	    var date = cal1.selection.get();
	    if (date){
	    	date = Calendario.intToDate(date);
	      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("reg_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
	      }
	  	cal1.hide();
	  	}
 	 });

	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "reg_data_fim",
		date : <?php echo $data_fim->format("%Y%m%d")?>,
		selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) {
	    var date = cal2.selection.get();
	    if (date){
	      date = Calendario.intToDate(date);
	      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("reg_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
	      }
	  	cal2.hide();
	  	}
  	});

function checarData(){
   if (document.frmData.reg_data_inicio.value == '' || document.frmData.reg_data_fim.value== ''){
      alert('Precisa preencher as datas');
      return false;
   		}
	 else {
	 	document.frmData.mostrarDetalhes.value=1;
	 	document.frmData.submit();
		}
	}

function setData( frm_nome, f_data ) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + 'reg_' + f_data );
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
