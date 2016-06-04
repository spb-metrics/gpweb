<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;

require_once BASE_DIR.'/modulos/projetos/mudanca.class.php';
require_once $Aplic->getClasseSistema('CampoCustomizados');
$Aplic->carregarCalendarioJS();
$projeto_mudanca_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

$projeto_id =getParam($_REQUEST, 'projeto_id', null);

$projeto_mudanca_id=getParam($_REQUEST, 'projeto_mudanca_id', null);

$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;


$obj = new CMudanca();
$obj->load($projeto_mudanca_id);

if (!$projeto_id) $projeto_id=$obj->projeto_mudanca_projeto;


if (!($podeEditar && permiteEditarMudanca($obj->projeto_mudanca_acesso,$projeto_mudanca_id))) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}




$sql = new BDConsulta();

if (!$obj->projeto_mudanca_numero){
	$sql->adTabela('projeto_mudanca');
	$sql->adCampo('count(projeto_mudanca_id)');
	$sql->adOnde('projeto_mudanca_projeto = '.(int)$projeto_id);
	$numero = $sql->Resultado();
	$sql->limpar();
	
	$obj->projeto_mudanca_numero=++$numero;
	}


$botoesTitulo = new CBlocoTitulo(($projeto_mudanca_id ? 'Editar' : 'Criar').' Solicita��o de Mudan�as ', 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$usuarios =array();
if ($projeto_mudanca_id) {
	$sql->adTabela('projeto_mudanca_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('projeto_mudanca_id = '.(int)$projeto_mudanca_id);
	$usuarios = $sql->carregarColuna();
	$sql->limpar();

	}

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_mudanca" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="projeto_mudanca_id" value="'.$projeto_mudanca_id.'" />';
echo '<input type="hidden" name="projeto_mudanca_projeto" value="'.$projeto_id.'" />';
echo '<input name="projeto_mudanca_usuarios" type="hidden" value="'.implode(',', $usuarios).'" />';
echo '<input type="hidden" name="projeto_mudanca_data_aprovacao" value="'.$obj->projeto_mudanca_data_aprovacao.'" />';
echo '<input type="hidden" name="projeto_mudanca_numero" value="'.$obj->projeto_mudanca_numero.'" />';


echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';


echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="right" nowrap="nowrap" width=100>'.dica('N�mero', 'N�mero desta solicita��o de mudan�as').'N�mero:'.dicaF().'</td><td colspan="2">'.($obj->projeto_mudanca_numero<100 ? '0' : '').($obj->projeto_mudanca_numero<10 ? '0' : '').$obj->projeto_mudanca_numero.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualiza��o pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo � direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="projeto_mudanca_cor" value="'.($obj->projeto_mudanca_cor ? $obj->projeto_mudanca_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualiza��o dos eventos pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto � esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->projeto_mudanca_cor ? $obj->projeto_mudanca_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Demandante', 'Toda solicita��o de mudan�a deve ter um demandante.').'Demandante:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_mudanca_cliente" name="projeto_mudanca_cliente" value="'.($obj->projeto_mudanca_cliente ? $obj->projeto_mudanca_cliente : '').'" /><input type="text" id="nome_contato" name="nome_contato" value="'.nome_om(($obj->projeto_mudanca_cliente ? $obj->projeto_mudanca_cliente : ''),$Aplic->getPref('om_usuario'),'', true).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popContato();">'.imagem('icones/usuarios.gif','Selecionar Contato','Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar um contato.').'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Respons�vel', 'Toda solicita��o de mudan�a deve ter um respons�vel.').'Respons�vel:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_mudanca_responsavel" name="projeto_mudanca_responsavel" value="'.($obj->projeto_mudanca_responsavel ? $obj->projeto_mudanca_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om(($obj->projeto_mudanca_responsavel ? $obj->projeto_mudanca_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Autoridade', 'Toda solicita��o de mudan�a deve ter um autoridade respons�vel por aprovar.').'Autoridade:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_mudanca_autoridade" name="projeto_mudanca_autoridade" value="'.$obj->projeto_mudanca_autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_om($obj->projeto_mudanca_autoridade, $Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovada pelo Requisitante', 'Marque esta op��o se a requisi��o de mudan�a foi aprovada pelo requisitante.').'Aprovada pelo requisitante:'.dicaF().'</td><td colspan="2"><input type="checkbox" name="projeto_mudanca_requisitante_aprovada" id="projeto_mudanca_requisitante_aprovada" '.($obj->projeto_mudanca_requisitante_aprovada  ? 'checked="checked"' : '').' value="1" onchange="if (env.projeto_mudanca_requisitante_aprovada.checked) {env.projeto_mudanca_requisitante_reprovada.checked=false;} else {env.projeto_mudanca_requisitante_reprovada.checked=true;}" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Reprovada pelo Requisitante', 'Marque esta op��o se a requisi��o de mudan�a foi reprovada pelo requisitante.').'Reprovada pelo requisitante:'.dicaF().'</td><td colspan="2"><input type="checkbox" name="projeto_mudanca_requisitante_reprovada" id="projeto_mudanca_requisitante_reprovada" '.($obj->projeto_mudanca_requisitante_reprovada  ? 'checked="checked"' : '').' value="1" onchange="if (env.projeto_mudanca_requisitante_reprovada.checked) {env.projeto_mudanca_requisitante_aprovada.checked=false;} else {env.projeto_mudanca_requisitante_aprovada.checked=true;}" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovada pela Administra��o', 'Marque esta op��o se a requisi��o de mudan�a foi aprovada pela administra��o.').'Aprovada pela administra��o:'.dicaF().'</td><td colspan="2"><input type="checkbox" name="projeto_mudanca_administracao_aprovada" id="projeto_mudanca_administracao_aprovada" '.($obj->projeto_mudanca_administracao_aprovada ? 'checked="checked"' : '').' value="1" onchange="if (env.projeto_mudanca_administracao_aprovada.checked) {env.projeto_mudanca_administracao_reprovada.checked=false;} else {env.projeto_mudanca_administracao_reprovada.checked=true;}" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Reprovada pela Administra��o', 'Marque esta op��o se a requisi��o de mudan�a foi reprovada pela administra��o.').'Reprovada pela administra��o:'.dicaF().'</td><td colspan="2"><input type="checkbox" name="projeto_mudanca_administracao_reprovada" id="projeto_mudanca_administracao_reprovada" '.($obj->projeto_mudanca_administracao_reprovada  ? 'checked="checked"' : '').' value="1" onchange="if (env.projeto_mudanca_administracao_reprovada.checked) {env.projeto_mudanca_administracao_aprovada.checked=false;} else {env.projeto_mudanca_administracao_aprovada.checked=true;}" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso a solicita��o de mudan�as seja espec�fica de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo dever� constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_mudanca_tarefa" value="'.$obj->projeto_mudanca_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($obj->projeto_mudanca_tarefa).'" style="width:450px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste �cone '.imagem('icones/tarefa_p.gif').' escolher � qual '.$config['tarefa'].' a entrega ir� pertencer.<br><br>Caso n�o escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', a entrega ser� d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('N�vel de Acesso', 'A solicita��o de mudan�as pode ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar a solicita��o de mudan�as.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o respons�vel e os designados para a solicita��o de mudan�as podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante</b> - Somente o respons�vel e os designados para a solicita��o de mudan�as ver e editar a solicita��o de mudan�as</li><li><b>Privado</b> - Somente o respons�vel e os designados para a solicita��o de mudan�as podem ver a mesma, e o respons�vel editar.</li></ul>').'N�vel de acesso:'.dicaF().'</td><td colspan="2">'.selecionaVetor($projeto_mudanca_acesso, 'projeto_mudanca_acesso', 'class="texto"', ($projeto_mudanca_id ? $obj->projeto_mudanca_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

$data_inicio = new CData(($obj->projeto_mudanca_data  ? $obj->projeto_mudanca_data : date('Y-m-d')));
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da Solicita��o', 'Digite ou escolha no calend�rio a data da solicita��o de mudan�a').'Data da solicita��o:'.dicaF().'</td><td align="left"><input type="hidden" name="projeto_mudanca_data" id="projeto_mudanca_data" value="'.($data_inicio ? $data_inicio->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="prevista"  id="prevista" style="width:70px;" onchange="setData(\'env\', \'prevista\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data in�cial da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Necessidade/Justificativa', 'Descri��o de forma clara a necessidade, a motiva��o, custo e prazo estimado da mudan�a no projeto.').'Necessidade/justificativa:'.dicaF().'</td><td><textarea name="projeto_mudanca_justificativa" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_justificativa.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer T�cnico', 'Avaliar tecnicamente se a mudan�a � pertinente').'Parecer t�cnico:'.dicaF().'</td><td><textarea name="projeto_mudanca_parecer_tecnico" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_parecer_tecnico.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Solu��es Poss�veis', 'Avaliar todas as poss�veis solu��es para solu��o da mudan�a proposta.').'Solu��es poss�veis:'.dicaF().'</td><td><textarea name="projeto_mudanca_solucoes" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_solucoes.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Impacto no Cronograma', 'Descrever o impacto da mudan�a no tempo, custo e riscos.').'Impacto no cronograma:'.dicaF().'</td><td><textarea name="projeto_mudanca_impacto_cronograma" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_impacto_cronograma.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Impactos no Custo', 'Descrever o impacto da mudan�a no tempo, custo e riscos.').'Impactos no custo:'.dicaF().'</td><td><textarea name="projeto_mudanca_impacto_custo" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_impacto_custo.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Novos Riscos', 'Descrever o impacto da mudan�a no tempo, custo e riscos.').'Novos riscos:'.dicaF().'</td><td><textarea name="projeto_mudanca_novo_risco" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_novo_risco.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Outros Impactos', 'Descrever o impacto da mudan�a no tempo, custo e riscos.').'Outros impactos:'.dicaF().'</td><td><textarea name="projeto_mudanca_outros_impactos" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_outros_impactos.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Solu��o Indicada', 'Dentre as solu��es poss�veis levantada pela equipe de projeto o gerente de projeto deve avaliar o impacto no projeto como um todo e indicar a melhor solu��o a ser adotada.').'Solu��o indicada:'.dicaF().'</td><td><textarea name="projeto_mudanca_solucao" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_solucao.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer', 'Deliberar sobre a aprova��o da mudan�a.').'Parecer:'.dicaF().'</td><td><textarea name="projeto_mudanca_parecer" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_parecer.'</textarea></td></tr>';

$sql->adTabela('projeto_mudanca_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=projeto_mudanca_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('projeto_mudanca_id = '.(int)$projeto_mudanca_id);
$participantes = $sql->Lista();
$sql->limpar();
$saida_quem='';
if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0>';
		$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda').($participantes[0]['contato_dept']? ' - '.link_secao($participantes[0]['contato_dept']) : '');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').($participantes[$i]['contato_dept']? ' - '.link_secao($participantes[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estar�o executando esta solicita��o de mudan�as.').'Quem:'.dicaF().'</td><td colspan="2">'.$saida_quem.'</td></td></tr>';

echo '<tr><td align="right" nowrap="nowrap"></td><td colspan="2"><table><tr><td>'.botao('participantes', 'Participantes','Abrir uma janela onde poder� selecionar quais ser�o os participantes desta solicita��o de mudan�as.<br><br>Os participantes poder�o receber e-mails informando sobre altera��es nesta solicita��o de mudan�as.','','popUsuarios()').'</td></tr></table></td></tr>';

$campos_customizados = new CampoCustomizados('projeto_mudanca', $projeto_mudanca_id, 'editar');
$campos_customizados->imprimirHTML();


echo '<tr><td colspan=20><table style="width:800px;"><tr><td>'; 


echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($projeto_mudanca_id > 0 ? 'modifica��o' : 'cria��o').' da solicita��o de mudan�a.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Respons�vel pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para o respons�vel pel'.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_responsavel">Respons�vel pel'.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os designados para '.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_designados">Designados para '.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de di�logo onde poder� selecionar outras pessoas que ser�o informadas por e-mail sobre este plano de recebimento.','','popEmailContatos()');
echo '</td><td>'.dica('Destinat�rios Extra', 'Preencha neste campo os e-mail, separados por v�rgula, dos destinat�rios extras que ser�o avisados deste plano de recebimento.').'Destinat�rios extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td></tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','env.submit();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($projeto_mudanca_id ? 'edi��o' : 'cria��o').' da solicita��o de mudan�as.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();


?>
<script language="javascript">

function popTarefa() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=<?php echo $projeto_id ?>', window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=<?php echo $projeto_id ?>', 'tarefa','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}
	
function setTarefa( chave, valor ) {
	document.env.projeto_mudanca_tarefa.value = chave;
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
    inputField : "projeto_mudanca_data",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
	    var date = cal1.selection.get();
	    if (date){
	    	date = Calendario.intToDate(date);
	      document.getElementById("prevista").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("projeto_mudanca_data").value = Calendario.printDate(date, "%Y-%m-%d");
	      document.getElementById("prevista").style.backgroundColor = '';
	      }
	  	cal1.hide(); 
	  	}
 	 });
  

function checarData(){
   if (document.env.projeto_mudanca_data.value == ''){
      alert('Precisa preencher as datas');
      return false;
   		}
	 else {
	 	document.env.mostrarDetalhes.value=1;
	 	document.env.submit();
		}
	}
	
function setData(frm_nome, f_data) {
	campo_data = eval('document.' + frm_nome + '.' + f_data);
	campo_data_real = eval( 'document.' + frm_nome + '.' + 'projeto_mudanca_data_' + f_data );
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




function popContato() {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&contato=1&chamar_volta=setContato&contato_id='+document.getElementById('projeto_mudanca_cliente').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setContato(contato_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('projeto_mudanca_cliente').value=contato_id;		
		document.getElementById('nome_contato').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}	
	
function popResponsavel() {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&usuario_id='+document.getElementById('projeto_mudanca_responsavel').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('projeto_mudanca_responsavel').value=usuario_id;		
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}		
	
	
function popAutoridade() {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&usuario_id='+document.getElementById('projeto_mudanca_autoridade').value, 'Autoridade','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('projeto_mudanca_autoridade').value=usuario_id;		
		document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}			
	
	
function setCor(cor) {
	var f = document.env;
	if (cor) f.projeto_mudanca_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.projeto_mudanca_cor.value;
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
		f.fazerSQL.value='fazer_sql_recebimento';
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
	document.env.projeto_mudanca_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	}

</script>

