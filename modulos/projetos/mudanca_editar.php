<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

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


$botoesTitulo = new CBlocoTitulo(($projeto_mudanca_id ? 'Editar' : 'Criar').' Solicitação de Mudanças ', 'anexo_projeto.png', $m, $m.'.'.$a);
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
echo '<tr><td align="right" nowrap="nowrap" width=100>'.dica('Número', 'Número desta solicitação de mudanças').'Número:'.dicaF().'</td><td colspan="2">'.($obj->projeto_mudanca_numero<100 ? '0' : '').($obj->projeto_mudanca_numero<10 ? '0' : '').$obj->projeto_mudanca_numero.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="projeto_mudanca_cor" value="'.($obj->projeto_mudanca_cor ? $obj->projeto_mudanca_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->projeto_mudanca_cor ? $obj->projeto_mudanca_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Demandante', 'Toda solicitação de mudança deve ter um demandante.').'Demandante:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_mudanca_cliente" name="projeto_mudanca_cliente" value="'.($obj->projeto_mudanca_cliente ? $obj->projeto_mudanca_cliente : '').'" /><input type="text" id="nome_contato" name="nome_contato" value="'.nome_om(($obj->projeto_mudanca_cliente ? $obj->projeto_mudanca_cliente : ''),$Aplic->getPref('om_usuario'),'', true).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popContato();">'.imagem('icones/usuarios.gif','Selecionar Contato','Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar um contato.').'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável', 'Toda solicitação de mudança deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_mudanca_responsavel" name="projeto_mudanca_responsavel" value="'.($obj->projeto_mudanca_responsavel ? $obj->projeto_mudanca_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om(($obj->projeto_mudanca_responsavel ? $obj->projeto_mudanca_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Autoridade', 'Toda solicitação de mudança deve ter um autoridade responsável por aprovar.').'Autoridade:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_mudanca_autoridade" name="projeto_mudanca_autoridade" value="'.$obj->projeto_mudanca_autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_om($obj->projeto_mudanca_autoridade, $Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovada pelo Requisitante', 'Marque esta opção se a requisição de mudança foi aprovada pelo requisitante.').'Aprovada pelo requisitante:'.dicaF().'</td><td colspan="2"><input type="checkbox" name="projeto_mudanca_requisitante_aprovada" id="projeto_mudanca_requisitante_aprovada" '.($obj->projeto_mudanca_requisitante_aprovada  ? 'checked="checked"' : '').' value="1" onchange="if (env.projeto_mudanca_requisitante_aprovada.checked) {env.projeto_mudanca_requisitante_reprovada.checked=false;} else {env.projeto_mudanca_requisitante_reprovada.checked=true;}" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Reprovada pelo Requisitante', 'Marque esta opção se a requisição de mudança foi reprovada pelo requisitante.').'Reprovada pelo requisitante:'.dicaF().'</td><td colspan="2"><input type="checkbox" name="projeto_mudanca_requisitante_reprovada" id="projeto_mudanca_requisitante_reprovada" '.($obj->projeto_mudanca_requisitante_reprovada  ? 'checked="checked"' : '').' value="1" onchange="if (env.projeto_mudanca_requisitante_reprovada.checked) {env.projeto_mudanca_requisitante_aprovada.checked=false;} else {env.projeto_mudanca_requisitante_aprovada.checked=true;}" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovada pela Administração', 'Marque esta opção se a requisição de mudança foi aprovada pela administração.').'Aprovada pela administração:'.dicaF().'</td><td colspan="2"><input type="checkbox" name="projeto_mudanca_administracao_aprovada" id="projeto_mudanca_administracao_aprovada" '.($obj->projeto_mudanca_administracao_aprovada ? 'checked="checked"' : '').' value="1" onchange="if (env.projeto_mudanca_administracao_aprovada.checked) {env.projeto_mudanca_administracao_reprovada.checked=false;} else {env.projeto_mudanca_administracao_reprovada.checked=true;}" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Reprovada pela Administração', 'Marque esta opção se a requisição de mudança foi reprovada pela administração.').'Reprovada pela administração:'.dicaF().'</td><td colspan="2"><input type="checkbox" name="projeto_mudanca_administracao_reprovada" id="projeto_mudanca_administracao_reprovada" '.($obj->projeto_mudanca_administracao_reprovada  ? 'checked="checked"' : '').' value="1" onchange="if (env.projeto_mudanca_administracao_reprovada.checked) {env.projeto_mudanca_administracao_aprovada.checked=false;} else {env.projeto_mudanca_administracao_aprovada.checked=true;}" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso a solicitação de mudanças seja específica de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_mudanca_tarefa" value="'.$obj->projeto_mudanca_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($obj->projeto_mudanca_tarefa).'" style="width:450px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' a entrega irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', a entrega será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'A solicitação de mudanças pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar a solicitação de mudanças.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para a solicitação de mudanças podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para a solicitação de mudanças ver e editar a solicitação de mudanças</li><li><b>Privado</b> - Somente o responsável e os designados para a solicitação de mudanças podem ver a mesma, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td colspan="2">'.selecionaVetor($projeto_mudanca_acesso, 'projeto_mudanca_acesso', 'class="texto"', ($projeto_mudanca_id ? $obj->projeto_mudanca_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

$data_inicio = new CData(($obj->projeto_mudanca_data  ? $obj->projeto_mudanca_data : date('Y-m-d')));
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da Solicitação', 'Digite ou escolha no calendário a data da solicitação de mudança').'Data da solicitação:'.dicaF().'</td><td align="left"><input type="hidden" name="projeto_mudanca_data" id="projeto_mudanca_data" value="'.($data_inicio ? $data_inicio->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="prevista"  id="prevista" style="width:70px;" onchange="setData(\'env\', \'prevista\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data inícial da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Necessidade/Justificativa', 'Descrição de forma clara a necessidade, a motivação, custo e prazo estimado da mudança no projeto.').'Necessidade/justificativa:'.dicaF().'</td><td><textarea name="projeto_mudanca_justificativa" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_justificativa.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer Técnico', 'Avaliar tecnicamente se a mudança é pertinente').'Parecer técnico:'.dicaF().'</td><td><textarea name="projeto_mudanca_parecer_tecnico" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_parecer_tecnico.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Soluções Possíveis', 'Avaliar todas as possíveis soluções para solução da mudança proposta.').'Soluções possíveis:'.dicaF().'</td><td><textarea name="projeto_mudanca_solucoes" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_solucoes.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Impacto no Cronograma', 'Descrever o impacto da mudança no tempo, custo e riscos.').'Impacto no cronograma:'.dicaF().'</td><td><textarea name="projeto_mudanca_impacto_cronograma" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_impacto_cronograma.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Impactos no Custo', 'Descrever o impacto da mudança no tempo, custo e riscos.').'Impactos no custo:'.dicaF().'</td><td><textarea name="projeto_mudanca_impacto_custo" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_impacto_custo.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Novos Riscos', 'Descrever o impacto da mudança no tempo, custo e riscos.').'Novos riscos:'.dicaF().'</td><td><textarea name="projeto_mudanca_novo_risco" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_novo_risco.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Outros Impactos', 'Descrever o impacto da mudança no tempo, custo e riscos.').'Outros impactos:'.dicaF().'</td><td><textarea name="projeto_mudanca_outros_impactos" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_outros_impactos.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Solução Indicada', 'Dentre as soluções possíveis levantada pela equipe de projeto o gerente de projeto deve avaliar o impacto no projeto como um todo e indicar a melhor solução a ser adotada.').'Solução indicada:'.dicaF().'</td><td><textarea name="projeto_mudanca_solucao" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_solucao.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer', 'Deliberar sobre a aprovação da mudança.').'Parecer:'.dicaF().'</td><td><textarea name="projeto_mudanca_parecer" style="width:750px;" class="textarea">'.$obj->projeto_mudanca_parecer.'</textarea></td></tr>';

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
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando esta solicitação de mudanças.').'Quem:'.dicaF().'</td><td colspan="2">'.$saida_quem.'</td></td></tr>';

echo '<tr><td align="right" nowrap="nowrap"></td><td colspan="2"><table><tr><td>'.botao('participantes', 'Participantes','Abrir uma janela onde poderá selecionar quais serão os participantes desta solicitação de mudanças.<br><br>Os participantes poderão receber e-mails informando sobre alterações nesta solicitação de mudanças.','','popUsuarios()').'</td></tr></table></td></tr>';

$campos_customizados = new CampoCustomizados('projeto_mudanca', $projeto_mudanca_id, 'editar');
$campos_customizados->imprimirHTML();


echo '<tr><td colspan=20><table style="width:800px;"><tr><td>'; 


echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($projeto_mudanca_id > 0 ? 'modificação' : 'criação').' da solicitação de mudança.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável pel'.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_responsavel">Responsável pel'.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para '.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_designados">Designados para '.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este plano de recebimento.','','popEmailContatos()');
echo '</td><td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados deste plano de recebimento.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td></tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','env.submit();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($projeto_mudanca_id ? 'edição' : 'criação').' da solicitação de mudanças.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

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




function popContato() {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&contato=1&chamar_volta=setContato&contato_id='+document.getElementById('projeto_mudanca_cliente').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setContato(contato_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('projeto_mudanca_cliente').value=contato_id;		
		document.getElementById('nome_contato').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}	
	
function popResponsavel() {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&usuario_id='+document.getElementById('projeto_mudanca_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
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

