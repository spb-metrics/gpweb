<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$pg_objetivo_estrategico_id = getParam($_REQUEST, 'pg_objetivo_estrategico_id', null);

$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);

$lista_composicao = getParam($_REQUEST, 'lista_composicao', '');

$sql = new BDConsulta;

if ($pg_objetivo_estrategico_id){
	//recuperar a cia e o ano do plano de gestão
	$sql->adTabela('objetivos_estrategicos');
	$sql->adCampo('pg_objetivo_estrategico_cia');
	$cia_id=$sql->Resultado();
	$sql->limpar();
	}
else{
	$cia_id=$Aplic->usuario_cia;
	}




echo estiloTopoCaixa();
echo '<table cellspacing=1 cellpadding=1 border=0 width="100%" class="std">';
echo '<tr><td colspan=20><table><tr><td align=right>'.dica('Selecionar '.$config['organizacao'], 'Selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que deseja exibir os objetivos.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="mudar_anos()">'.imagem('icones/atualizar.png','Atualizar os Anos','Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de anos.').'</a></td></tr></table></td></tr>';

$peso_selecionados=array();
$objetivos_recebidas=array();
$vetor=explode(',',$lista_composicao);
foreach((array)$vetor as $chave => $campo){
	if (isset($campo)&& $campo) $objetivos_recebidas[$campo]=$campo;
	}
$objetivos_selecionados=array();
if (count($objetivos_recebidas)){
	$sql->adTabela('objetivos_estrategicos');
	$sql->esqUnir('cias','cias','pg_objetivo_estrategico_cia=cia_id');
	$sql->adCampo('pg_objetivo_estrategico_id, concatenar_tres(pg_objetivo_estrategico_nome, \' - \', cia_nome) AS nome');
	$sql->adOnde('pg_objetivo_estrategico_id IN ('.implode(',',$objetivos_recebidas).')');
	$lista=$sql->Lista();
	$sql->limpar();
	
	foreach($lista as $linha) $objetivos_selecionados[$linha['pg_objetivo_estrategico_id']]=$linha['nome'];

	}


$sql->adTabela('objetivos_estrategicos');
$sql->esqUnir('cias','cias','pg_objetivo_estrategico_cia=cia_id');
$sql->adCampo('pg_objetivo_estrategico_id, concatenar_tres(pg_objetivo_estrategico_nome, \' - \', cia_nome) AS nome');
$sql->adOnde('pg_objetivo_estrategico_ativo=1');
if ($pg_objetivo_estrategico_id) $sql->adOnde('pg_objetivo_estrategico_id!='.$pg_objetivo_estrategico_id);
$sql->adOnde('pg_objetivo_estrategico_cia='.(int)$cia_id);
$lista=$sql->Lista();
$sql->limpar();

$objetivos=array();
foreach($lista as $linha) $objetivos[$linha['pg_objetivo_estrategico_id']]=$linha['nome'];
echo '<tr><td width="50%"><fieldset><legend class=texto style="color: black;">'.dica(ucfirst($config['objetivos']).' Disponíveis', 'Lista de '.$config['objetivos'].' que poderão ser acrescentados à composição. Dê um clique duplo em um d'.$config['genero_objetivo'].'s '.$config['objetivos'].' nesta lista de seleção para adiciona-lo à lista de composição.<BR><BR>Outra opção é selecionar '.$config['genero_objetivo'].' '.$config['objetivo'].' e clicar no botão Adicionar.<BR><BR>Para selecionar múltipl'.$config['genero_objetivo'].'s '.$config['objetivos'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;<b>'.ucfirst($config['objetivos']).' Disponíveis</b>&nbsp</legend>'.dica().'<div id="combo_lista_objetivos">'.selecionaVetor($objetivos, 'lista', 'style="width:100%;" size="15" class="texto" multiple="multiple" ondblclick="Mover()"').'</div></fieldset></td>';
echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica(ucfirst($config['objetivos']).' Selecionados','Dê um clique duplo em um d'.$config['genero_objetivo'].'s '.$config['objetivos'].' nesta lista de seleção para remove-lo.<BR><BR>Outra opção é selecionar o '.$config['objetivos'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_objetivo'].'s '.$config['objetivos'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>'.ucfirst($config['objetivos']).' Selecionados</b>&nbsp;</legend>'.selecionaVetor($objetivos_selecionados, 'selecionados', 'style="width:100%;" size="15" class="texto" multiple="multiple" ondblclick="Remover()"').'</fieldset></td></tr>';
echo '<tr><td colspan="2" align="center"><table width="100%">';
echo '<tr><td align="left"><table cellspacing=0 cellpadding=0><tr><td>'.botao('adicionar', 'Adicionar', 'Utilize este botão para adicionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].' à lista dos selecionados</p>Caso deseja inserir multiplos objetivos de uma única vez, mantenha o botão <i>CTRL</i> apertado enquanto clica com o botão esquerdo do mouse n'.$config['genero_objetivo'].'s '.$config['objetivos'].' da lista acima.','','Mover()','','',0).'</td></tr></table></td><td>&nbsp;</td><td align="right">'.botao('remover', 'Remover', 'Utilize este botão para retirar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].' da lista dos selecionados. </p>Caso deseja remover multipl'.$config['genero_objetivo'].'s '.$config['objetivos'].' de uma única vez, mantenha o botão <i>CTRL</i> apertado enquanto clica com o botão esquerdo do mouse n'.$config['genero_objetivo'].'s '.$config['objetivos'].' da lista acima.','','Remover()','','',0).'</td></tr>';
echo '<tr><td>'.botao('aceitar', 'Aceitar', 'Utilize este botão para aceitar a edição da composição.','','Retornar();','','',0).'</td><td>&nbsp;</td>'.(!$Aplic->profissional ? '<td  align="right">'.botao('cancelar', 'Cancelar', 'Utilize este botão para cancelar a edição de composição.','','window.opener = window; window.close()','','',0).'</td>' : '').'</tr>';
echo '</table></td></tr></table></td>';


echo '</table>';
echo estiloFundoCaixa();


?>
<script type="text/javascript">

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}



function Retornar(){
	var saida='';
	var ListaPARA=document.getElementById('selecionados');
	for (var i=0; i < ListaPARA.length ; i++) {
		if (ListaPARA.options[i].value) saida+=(saida ? ',' : '')+ListaPARA.options[i].value;
		}
		
	if(parent && parent.gpwebApp){
			if (saida) parent.gpwebApp._popupCallback(saida); 
			else parent.gpwebApp._popupCallback(null);
			} 
	else{	
		window.opener.SetComposicao(saida);
		window.opener = window; window.close();
		}
	}



function Mover() {
	var ListaDE=document.getElementById('lista');
	var ListaPARA=document.getElementById('selecionados');


	//checar se já existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value != "0") {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text;
			
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) { 
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				ListaPARA.options[ListaPARA.options.length] = no;	
				}
			}
		}
	}

function Remover() {
	var ListaPARA=document.getElementById('selecionados');
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value != "0") {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""	
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	}
	
// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}

// Seleciona todos os campos da lista
function selecionar(nome,campo) {
	var lista=document.getElementById(nome);
	
	var saida='';
	for (var i=0; i < lista.length ; i++) {
		if (lista.options[i].value) saida+=','+lista.options[i].value;
		}
	document.getElementById(campo).value=saida.substr(1);	
	}		




</script>