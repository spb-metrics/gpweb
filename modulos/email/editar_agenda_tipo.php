<?php  
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$inserir=getParam($_REQUEST, 'inserir', 0);
$alterar=getParam($_REQUEST, 'alterar', 0);
$retornar=getParam($_REQUEST, 'retornar', '');
$nova_agenda_tipo=getParam($_REQUEST, 'nova_agenda_tipo', null);
$excluir_agenda_tipo=getParam($_REQUEST, 'excluir_agenda_tipo', array());
$agenda_tipo_id=getParam($_REQUEST, 'agenda_tipo_id', null);
$novo_nome=getParam($_REQUEST, 'novo_nome', '');
$idnome=getParam($_REQUEST, 'idnome', 0);
$data=getParam($_REQUEST, 'data', null);
$agenda_cor=getParam($_REQUEST, 'agenda_cor', null);

$sql = new BDConsulta;

if ($idnome && $alterar){
	$sql->adTabela('agenda_tipo');
	$sql->adCampo('nome, cor');
	$sql->adOnde('usuario_id='.$Aplic->usuario_id);
	$sql->adOnde('agenda_tipo_id='.$idnome);
	$modificar = $sql->Linha();
	$sql->Limpar();
  }
if ($novo_nome && $agenda_tipo_id){
	$sql->adTabela('agenda_tipo');
	$sql->adAtualizar('nome', $novo_nome);
	$sql->adAtualizar('cor', $agenda_cor);
	$sql->adOnde('usuario_id='.$Aplic->usuario_id);
	$sql->adOnde('agenda_tipo_id='.$agenda_tipo_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela agenda_tipo!'.$bd->stderr(true));
	$sql->limpar();	
	}
if ($excluir_agenda_tipo){
	$sql->setExcluir('agenda');
	$sql->adOnde('agenda_tipo IN ('.implode(',',(array)$excluir_agenda_tipo).')');
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela agenda!'.$bd->stderr(true));
	$sql->limpar();	

	$sql->setExcluir('agenda_tipo');
	$sql->adOnde('usuario_id='.$Aplic->usuario_id);
	$sql->adOnde('agenda_tipo_id IN ('.implode(',',(array)$excluir_agenda_tipo).')');
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela agenda_tipo!'.$bd->stderr(true));
	$sql->limpar();
	}
if ($nova_agenda_tipo){
	$sql->adTabela('agenda_tipo');
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('cor', $agenda_cor);
	$sql->adInserir('nome', $nova_agenda_tipo);
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela agenda_tipo');
	$sql->limpar();
	}	

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="m" name="m" value="email">';
echo '<input type=hidden id="a" name="a" value="editar_agenda_tipo">';		
echo '<input type=hidden name="inserir" id="inserir" value="">';
echo '<input type=hidden name="alterar" id="alterar" value="">';
echo '<input type=hidden name="nova_agenda_tipo" id="nova_agenda_tipo" value="">';
echo '<input type=hidden name="agenda_tipo_id" id="agenda_tipo_id" value="">';
echo '<input type=hidden name="idnome" id="idnome" value="">';
echo '<input type=hidden name="excluir_agenda_tipo" id="excluir_agenda_tipo" value="">';
echo '<input type="hidden" name="data" value="'.$data.'" />';
echo '<input type="hidden" name="retornar" value="'.$retornar.'" />';

echo estiloTopoCaixa(600);
echo '<table width="600" align="center" border=0 class="std" cellspacing=0 cellpadding=0 >';
echo '<tr><td width="200"><fieldset><legend class=texto style="color: black;">&nbsp;<b>Agendas</b>&nbsp;</legend>';
echo '<select name=ListaPasta[] id="ListaPasta" size=12 style="width:100%;" ondblClick="">';

$sql->adTabela('agenda_tipo');
$sql->adCampo('agenda_tipo_id, nome');
$sql->adOnde('usuario_id='.$Aplic->usuario_id);
$sql_resultado = $sql->Lista();
$sql->Limpar();
foreach ($sql_resultado as $linha) echo '<option value="'.$linha['agenda_tipo_id'].'">'.$linha['nome'].'</option>';
echo '</option></select></fieldset></td></tr>';
echo '<tr><td>'; 
if (!$inserir && !$alterar) {	
		echo '<table><tr>';
		echo '<td style="width:50pt">'.dica('Excluir','Clique neste botão para excluir as agendas selecionadas da caixa de seleção acima.<br><br>Para excluir múltiplas agendas, selecione estas com a tecla CTRL pressionada.').'<a class="botao" href="javascript:void(0);" onclick="excluir()"><span><b>excluir</b></span></a>'.dicaF().'</td>';
		echo '<td style="width:50pt">'.dica('Inserir','Clique neste botão para inserir uma nova agenda.').'<a class="botao" href="javascript:void(0);" onclick="env.inserir.value=1; env.submit();"><span><b>inserir</b></span></a>'.dicaF().'</td>';
		echo '<td style="width:125pt">'.dica('Editar','Clique neste botão para editar uma agenda da caixa de seleção acima.').'<a class="botao" href="javascript:void(0);" onclick="editar();"><span><b>editar</b></span></a></td>';
		echo '<td >'.dica('Voltar','Clique neste botão para voltar à tela principal.').'<a class="botao" href="javascript:void(0);" onclick="env.a.value=\''.$retornar.'\'; env.submit();"><span><b>voltar</b></span></a>'.dicaF().'</td></tr></table>';
		} 
else if ($inserir){
		echo '<table><tr><td>';
		echo '&nbsp;<b>Nome: </b><input type=text name="nome_nova_agenda_tipo" id="nome_nova_agenda_tipo" style="width:108pt"></td>';
		
		echo '<td>'.dica("OK","Clique neste botão para confirmar a inserção da nova agenda.").'<a class="botao" href="javascript:void(0);" onclick="if (env.nome_nova_agenda_tipo.value.length>0) {env.nova_agenda_tipo.value=env.nome_nova_agenda_tipo.value; env.submit();} else alert (\'Escreve o nome da agenda!\');"><span><b>OK</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica("Cancelar","Clique neste botão para cancelar a inserção da nova agenda.").'<a class="botao" href="javascript:void(0);" onclick="env.submit();"><span><b>Cancelar</b></span></a>'.dicaF().'</td>';
		echo '<td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização dos compromissos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="agenda_cor" value="fff0b0" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" /> *&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização d'.$config['genero_projeto'].'s '.$config['projetos'].' pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#fff0b0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td>';
		echo '</tr></table>';
		} 
else {
		echo '<table><tr>';
		echo '<td>&nbsp;<b>Nome:</b> <input type=text name="novo_nome" id="novo_nome" value='.$modificar['nome'].' style="width:100pt"></td>';
		echo '<td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização dos compromissos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="agenda_cor" value="'.$modificar['cor'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" /> *&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização d'.$config['genero_projeto'].'s '.$config['projetos'].' pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.$modificar['cor'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td>';
		echo '<td>'.dica("OK","Clique neste botão para confirmar a alteração do nome da agenda.").'<a class="botao" href="javascript:void(0);" onclick="if (env.novo_nome.value.length>0) {env.agenda_tipo_id.value='.$idnome.'; env.submit();} else alert (\'Escreve o novo nome da agenda!\');"><span><b>OK</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica("CANCELAR","Clique neste botão para cancelar a alteração do nome da agenda.").'<a class="botao" href="javascript:void(0);" onclick="env.submit();"><span><b>Cancelar</b></span></a>'.dicaF().'</td></tr></table>';
		} 
echo '</td></tr></table>';
echo estiloFundoCaixa(600);
echo '</form></BODY></html>';
?>

<script LANGUAGE="javascript">

function excluir() {
	var qnt=0;
	var excluido = new Array();
	for(var i=0; i<env.ListaPasta.options.length; i++) {
			if (env.ListaPasta.options[i].selected && env.ListaPasta.options[i].value >0){ 
			excluido[qnt++]=env.ListaPasta.options[i].value;
			}	
		if (qnt>0) {
			env.excluir_agenda_tipo.value=excluido;
			env.submit();
			} 
		else alert ('Selecione uma agenda!');
		}
	}

function editar() {
	var idnome;
	var qnt=0;
	for(var i=0; i< env.ListaPasta.options.length; i++) {
			if (env.ListaPasta.options[i].selected && env.ListaPasta.options[i].value >0) {
				idnome=env.ListaPasta.options[i].value;
				++qnt;
				}
			}	
	if (qnt>0) {
		env.alterar.value=1;
		env.idnome.value=idnome;
		env.submit();
		} 
	else alert ('Selecione uma agenda!');
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.agenda_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.agenda_cor.value;
	}
</script>