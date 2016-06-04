<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
$Aplic->carregarCKEditorJS();
require_once BASE_DIR.'/modulos/projetos/ata.class.php';
require_once $Aplic->getClasseSistema('CampoCustomizados');
$Aplic->carregarCalendarioJS();
$ata_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

//horas e minutos

$hora_inicio =substr($config['expediente_inicio'],0, 2);
$hora_fim =substr($config['expediente_fim'],0, 2);
$minuto_inicio =substr($config['expediente_inicio'],3, 2);
$minuto_fim =substr($config['expediente_fim'],3, 2);
$inc = 1;
$horas = array();
for ($atual = 0; $atual < (stristr($Aplic->getPref('formatohora'), '%p') ? 12 : 24); $atual++) {
	if ($atual < 10) $chave_atual = "0".$atual;
	else $chave_atual = $atual;
	if (stristr($Aplic->getPref('formatohora'), '%p')) $horas[$chave_atual] = ($atual > 12 ? $atual - 12 : $atual);
	else 	$horas[$chave_atual] = $atual;
	}
$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;


$projeto_id =getParam($_REQUEST, 'projeto_id', null);
$ata_id=getParam($_REQUEST, 'ata_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;


$obj = new CAta();
$obj->load($ata_id);

if (!$projeto_id) $projeto_id=$obj->ata_projeto;


if (!($podeEditar && permiteEditarAta($obj->ata_acesso,$ata_id))) {
	$Aplic->redirecionar('m=publico&a=acesso_negado');
	exit();
	}




$sql = new BDConsulta();

if (!$obj->ata_numero){
	$sql->adTabela('ata');
	$sql->adCampo('max(ata_numero)');
	$sql->adOnde('ata_projeto = '.(int)$projeto_id);
	$numero = $sql->Resultado();
	$sql->limpar();

	$obj->ata_numero=++$numero;
	}


$botoesTitulo = new CBlocoTitulo(($ata_id ? 'Editar' : 'Criar').' Ata de Reuni�o', 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=projetos&a=ata_lista&projeto_id='.$projeto_id, 'lista','','Lista','Ver a lista de atas d'.$config['genero_projeto'].' '.$config['projeto'].'.');

$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes d'.$config['genero_projeto'].' '.$config['projeto'].'.');
if ($ata_id) $botoesTitulo->adicionaBotaoExcluir('excluir', $ata_id, '', 'Excluir Ata de Reuni�o', 'Excluir esta ata de reuni�o.');



$botoesTitulo->mostrar();

$usuarios =array();
if ($ata_id) {
	$sql->adTabela('ata_usuario');
	$sql->adCampo('ata_usuario_usuario');
	$sql->adOnde('ata_usuario_ata = '.(int)$ata_id);
	$usuarios = $sql->carregarColuna();
	$sql->limpar();

	}

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_ata" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="ata_id" value="'.$ata_id.'" />';
echo '<input type="hidden" name="ata_projeto" value="'.$projeto_id.'" />';
echo '<input name="ata_usuarios" type="hidden" value="'.implode(',', $usuarios).'" />';
echo '<input type="hidden" name="ata_numero" value="'.$obj->ata_numero.'" />';


echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';


echo estiloTopoCaixa();
echo '<table cellspacing="1" cellpadding="1" width="100%" class="std">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','env.submit();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($ata_id ? 'edi��o' : 'cria��o').' da ata de reuni�o.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('N�mero', 'N�mero desta ata de reuni�o').'N�mero:'.dicaF().'</td><td width="100%" colspan="2">'.($obj->ata_numero<100 ? '0' : '').($obj->ata_numero<10 ? '0' : '').$obj->ata_numero.'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualiza��o pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo � direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="ata_cor" value="'.($obj->ata_cor ? $obj->ata_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualiza��o dos eventos pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto � esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->ata_cor ? $obj->ata_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Respons�vel', 'Toda ata de reuni�o deve ter um respons�vel.').'Respons�vel:'.dicaF().'</td><td colspan="2"><input type="hidden" id="ata_responsavel" name="ata_responsavel" value="'.($obj->ata_responsavel ? $obj->ata_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om(($obj->ata_responsavel ? $obj->ata_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso a ata de reuni�o seja espec�fica de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo dever� constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="ata_tarefa" value="'.$obj->ata_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($obj->ata_tarefa).'" style="width:450px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste �cone '.imagem('icones/tarefa_p.gif').' escolher � qual '.$config['tarefa'].' a entrega ir� pertencer.<br><br>Caso n�o escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', a entrega ser� d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('N�vel de Acesso', 'A ata de reuni�o pode ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar a ata de reuni�o.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o respons�vel e os designados para a ata de reuni�o podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante</b> - Somente o respons�vel e os designados para a ata de reuni�o ver e editar a ata de reuni�o</li><li><b>Privado</b> - Somente o respons�vel e os designados para a ata de reuni�o podem ver a mesma, e o respons�vel editar.</li></ul>').'N�vel de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($ata_acesso, 'ata_acesso', 'class="texto"', ($ata_id ? $obj->ata_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

$data_inicio = new CData(($obj->ata_data_inicio  ? $obj->ata_data_inicio : date('Y-m-d H:i:s')));
$data_fim = new CData(($obj->ata_data_fim  ? $obj->ata_data_fim : date('Y-m-d H:i:s')));

echo '<tr><td align="right" nowrap="nowrap">'.dica('Data', 'Digite ou escolha no calend�rio a data da reuni�o.').'Data:'.dicaF().'</td><td align="left"><input type="hidden" name="ata_data_inicio" id="ata_data_inicio" value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" name="data" id="data" style="width:70px;" onchange="setData(\'env\', \'data\', \'ata_data_inicio\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data in�cial da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF().dica('Hora de In�cio', 'Selecione na caixa de sele��o a hora do �nicio da reuni�o.').' In�cio:'.dicaF().selecionaVetor($horas, 'inicio_hora', 'size="1" onchange="data_ajax();" class="texto"', ($obj->ata_data_inicio ? $data_inicio->getHour() : $hora_inicio)).' : '.selecionaVetor($minutos, 'inicio_minutos', 'size="1" class="texto" onchange="data_ajax();" ', ($obj->ata_data_inicio ? $data_inicio->getMinute() : $minuto_inicio)).dica('Hora de T�rmino', 'Selecione na caixa de sele��o a hora de t�rmino da reuni�o.').' T�rmino:'.dicaF().selecionaVetor($horas, 'fim_hora', 'size="1" onchange="data_ajax();" class="texto"', ($obj->ata_data_fim ? $data_fim->getHour() : $hora_fim)).' : '.selecionaVetor($minutos, 'fim_minutos', 'size="1" class="texto" onchange="data_ajax();" ', ($obj->ata_data_fim ? $data_fim->getMinute() : $minuto_fim)).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Local', 'Escreva o local da reuni�o.').'Local:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="ata_local" style="width:750px;" class="textarea">'.$obj->ata_local.'</textarea></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Relato', 'Deliberar sobre a aprova��o da mudan�a.').'Relato:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="ata_relato" style="width:750px;" class="textarea">'.$obj->ata_relato.'</textarea></td></tr>';




$sql->adTabela('ata_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=ata_usuario_usuario');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('ata_usuario_ata = '.(int)$ata_id);
$participantes = $sql->Lista();
$sql->limpar();
$saida_quem='';
if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda').($participantes[0]['contato_dept']? ' - '.link_secao($participantes[0]['contato_dept']) : '');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').($participantes[$i]['contato_dept']? ' - '.link_secao($participantes[$i]['contato_dept']) : '').'<br>';
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		}
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estar�o executando esta ata de reuni�o.').'Quem:'.dicaF().'</td><td width="100%" colspan="2">'.$saida_quem.'</td></td></tr>';



echo '<tr><td align="right" nowrap="nowrap"></td><td width="100%" colspan="2"><table><tr><td>'.botao('participantes', 'Participantes','Abrir uma janela onde poder� selecionar quais ser�o os participantes desta ata de reuni�o.<br><br>Os participantes poder�o receber e-mails informando sobre altera��es nesta ata de reuni�o.','','popUsuarios()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nova Reuni�o', 'Marque esta op��o se h� previs�o de uma nova reuni�o.').'Nova Reuni�o:'.dicaF().'</td><td colspan="2"><input type="checkbox" name="tem_proxima" id="tem_proxima" '.($obj->ata_proxima_data_inicio  ? 'checked="checked"' : '').' value="1" onchange="mostrar_proxima();" /></td></tr>';


$proxima_data_inicio = new CData(($obj->ata_proxima_data_inicio  ? $obj->ata_proxima_data_inicio : date('Y-m-d H:i:s')));
$proxima_data_fim = new CData(($obj->ata_proxima_data_fim  ? $obj->ata_proxima_data_fim : date('Y-m-d H:i:s')));
echo '<tr id="ver_proxima" style="display:'.($obj->ata_proxima_data_inicio ? '' : 'none').'"><td align="right" nowrap="nowrap">'.dica('Pr�xima Data', 'Digite ou escolha no calend�rio a data da pr�ximareuni�o.').'Pr�xima Data:'.dicaF().'</td><td align="left"><input type="hidden" name="ata_proxima_data_inicio" id="ata_proxima_data_inicio" value="'.($proxima_data_inicio ? $proxima_data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" name="proxima"  id="proxima" style="width:70px;" onchange="setData(\'env\', \'proxima\', \'ata_proxima_data_inicio\');" value="'.($proxima_data_inicio ? $proxima_data_inicio->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data in�cial da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF().dica('Hora de In�cio', 'Selecione na caixa de sele��o a hora do �nicio da pr�xima reuni�o.').' In�cio:'.dicaF().selecionaVetor($horas, 'proxima_inicio_hora', 'size="1" onchange="data_ajax();" class="texto"', ($obj->ata_proxima_data_inicio ? $proxima_data_inicio->getHour() : $hora_inicio)).' : '.selecionaVetor($minutos, 'proxima_inicio_minutos', 'size="1" class="texto" onchange="data_ajax();" ', ($obj->ata_proxima_data_inicio ? $proxima_data_inicio->getMinute() : '00')).dica('Hora de T�rmino', 'Selecione na caixa de sele��o a hora de t�rmino da pr�xima reuni�o.').' T�rmino:'.dicaF().selecionaVetor($horas, 'proxima_fim_hora', 'size="1" onchange="data_ajax();" class="texto"', ($obj->ata_proxima_data_fim ? $proxima_data_fim->getHour() : $hora_fim)).' : '.selecionaVetor($minutos, 'proxima_fim_minutos', 'size="1" class="texto" onchange="data_ajax();" ', ($obj->ata_proxima_data_fim ? $proxima_data_fim->getMinute() : '00')).'</td></tr>';


echo '<tr id="ver_local_proxima" style="display:'.($obj->ata_proxima_data_inicio ? '' : 'none').'"><td align="right" nowrap="nowrap">'.dica('Local da Pr�xima', 'Escreva o local da pr�xima reuni�o.').'Local da pr�xima:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="ata_proxima_local" style="width:750px;" class="textarea">'.$obj->ata_proxima_local.'</textarea></td></tr>';



$campos_customizados = new CampoCustomizados('ata', $ata_id, 'editar');
$campos_customizados->imprimirHTML();


echo '<tr><td colspan=20><table style="width:800px;"><tr><td>';


echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($ata_id > 0 ? 'modifica��o' : 'cria��o').' da ata de reuni�o.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Respons�vel pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para o respons�vel pel'.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_responsavel">Respons�vel pel'.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os designados para '.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_designados">Designados para '.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de di�logo onde poder� selecionar outras pessoas que ser�o informadas por e-mail sobre este plano de recebimento.','','popEmailContatos()');
echo '</td><td>'.dica('Destinat�rios Extra', 'Preencha neste campo os e-mail, separados por v�rgula, dos destinat�rios extras que ser�o avisados deste plano de recebimento.').'Destinat�rios extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td></tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','env.submit();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($ata_id ? 'edi��o' : 'cria��o').' da ata de reuni�o.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">

function mostrar_proxima(){
	if (document.getElementById('tem_proxima').checked) {
		document.getElementById('ver_proxima').style.display='';
		document.getElementById('ver_local_proxima').style.display='';
		}
	else {
		document.getElementById('ver_proxima').style.display='none';
		document.getElementById('ver_local_proxima').style.display='none';
		}
	}

function popTarefa() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=<?php echo $projeto_id ?>', window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=<?php echo $projeto_id ?>', 'tarefa','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	document.env.ata_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	}

function mostrar(){
	if (document.getElementById('ver_data_entrega').style.display=='none') {
		document.getElementById('ver_data_entrega').style.display='';
		}
	else {
		document.getElementById('ver_data_entrega').style.display='none';
		}
	}

 var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "ata_data_inicio",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) {
	    var date = cal1.selection.get();
	    if (date){
	    	date = Calendario.intToDate(date);
	      document.getElementById("data").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("ata_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
	      document.getElementById("data").style.backgroundColor = '';
	      }
	  	cal1.hide();
	  	}
 	 });


 var cal2 = Calendario.setup({
  	trigger    : "f_btn2",
    inputField : "ata_proxima_data_inicio",
  	date :  <?php echo $proxima_data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $proxima_data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal2) {
	    var date = cal2.selection.get();
	    if (date){
	    	date = Calendario.intToDate(date);
	      document.getElementById("proxima").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("ata_proxima_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
	      document.getElementById("proxima").style.backgroundColor = '';
	      }
	  	cal2.hide();
	  	}
 	 });




function checarData(){
   if (document.env.ata_data_inicio.value == ''){
      alert('Precisa preencher as datas');
      return false;
   		}
	 else {
	 	document.env.mostrarDetalhes.value=1;
	 	document.env.submit();
		}
	}

function setData(frm_nome, f_data, f_real) {
	campo_data = eval('document.' + frm_nome + '.' + f_data);
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_real );
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



function popResponsavel() {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&usuario_id='+document.getElementById('ata_responsavel').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('ata_responsavel').value=usuario_id;
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function popAutoridade() {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&usuario_id='+document.getElementById('ata_autoridade').value, 'Autoridade','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('ata_autoridade').value=usuario_id;
		document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function setCor(cor) {
	var f = document.env;
	if (cor) f.ata_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.ata_cor.value;
	}

function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var objetivo_emails = document.getElementById('viabilidades_usuarios');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir este plano de recebimento?")) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_ata';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios)?>';


function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&usuarios_id_selecionados='+usuarios_id_selecionados, '<?php echo ucfirst($config["usuarios"])?>','height=500,width=500,resizable,scrollbars=yes');
	}


function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.ata_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	}

</script>

