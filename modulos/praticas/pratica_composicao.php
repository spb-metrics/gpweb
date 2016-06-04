<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$pratica_id = getParam($_REQUEST, 'pratica_id', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$lista_composicao = getParam($_REQUEST, 'lista_composicao', '');

echo estiloTopoCaixa();
echo '<table cellspacing=1 cellpadding=1 border=0 width="100%" class="std">';
echo '<tr><td colspan=20><table><tr><td align=right>'.dica('Selecionar '.$config['organizacao'], 'Selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que deseja exibir os praticas.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="mudar_praticas()">'.imagem('icones/atualizar.png','Atualizar os Indicadores','Clique neste �cone '.imagem('icones/atualizar.png').' para atualizar a lista de praticas.').'</a></td></tr></table></td></tr>';
$peso_selecionados=array();
$praticas_recebidas=array();
$vetor=explode(',',$lista_composicao);
foreach((array)$vetor as $chave => $campo){
	if (isset($campo)&& $campo) $praticas_recebidas[$campo]=$campo;
	}


$sql = new BDConsulta;
$sql->adTabela('praticas');
$sql->esqUnir('cias','cias','pratica_cia=cia_id');
$sql->adCampo('pratica_id, concatenar_tres(pratica_nome, \' - \', cia_nome) AS nome');
if(count($praticas_recebidas)) $sql->adOnde('pratica_id IN ('.implode(',',$praticas_recebidas).')');
else $sql->adOnde('pratica_id=-1');
$sql->adOrdem('pratica_nome');
$lista=$sql->Lista();
$sql->limpar();

$praticas_selecionados=array();

foreach($lista as $linha) {
	$praticas_selecionados[$linha['pratica_id']]=$linha['nome'];
	}



$sql->adTabela('praticas');
$sql->esqUnir('cias','cias','pratica_cia=cia_id');
$sql->adCampo('pratica_id, concatenar_tres(pratica_nome, \' - \', cia_nome) AS nome, pratica_acesso');
$sql->adOnde('pratica_cia='.(int)$cia_id);
$sql->adOnde('pratica_id !='.$pratica_id);
$sql->adOrdem('pratica_nome');
$lista=$sql->Lista();
$sql->limpar();

$praticas=array();

foreach($lista as $linha){
	if (permiteAcessarPratica($linha['pratica_acesso'],$linha['pratica_id'])) $praticas[$linha['pratica_id']]=$linha['nome'];
	}

echo '<tr><td width="50%"><fieldset><legend class=texto style="color: black;">'.dica(ucfirst($config['praticas']).' Dispon�veis', 'Lista de '.$config['praticas'].' que poder�o ser acrescentad'.$config['genero_pratica'].'s � composi��o. D� um clique duplo '.($config['genero_pratica']=='a' ? 'numa das' : 'num dos').' '.$config['praticas'].' nesta lista de sele��o para adiciona-l'.$config['genero_pratica'].' � lista de composi��o.<BR><BR>Outra op��o � selecionar '.$config['genero_pratica'].' '.$config['pratica'].' e clicar no bot�o Adicionar.<BR><BR>Para selecionar m�ltipl'.$config['genero_pratica'].'s '.$config['praticas'].', clique n'.$config['genero_pratica'].'s mesm'.$config['genero_pratica'].'s mantendo a tecla CTRL apertada.').'&nbsp;<b>'.ucfirst($config['praticas']).' Dispon�veis</b>&nbsp</legend>'.dica().'<div id="combo_lista_praticas">'.selecionaVetor($praticas, 'lista', 'style="width:100%;" size="15" class="texto" multiple="multiple" ondblclick="Mover()"').'</div></fieldset></td>';
echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica(ucfirst($config['praticas']).' Selecionad'.$config['genero_pratica'].'s','D� um clique duplo em um d'.$config['genero_pratica'].'s '.$config['praticas'].' nesta lista de sele��o para remove-l'.$config['genero_pratica'].'.<BR><BR>Outra op��o � selecionar '.$config['genero_pratica'].' '.$config['pratica'].' e clicar no bot�o Remover.<BR><BR>Para selecionar m�ltipl'.$config['genero_pratica'].'s '.$config['praticas'].', clique n'.$config['genero_pratica'].'s mesm'.$config['genero_pratica'].'s mantendo a tecla CTRL apertada.').'<b>'.ucfirst($config['praticas']).' Selecionados</b>&nbsp;</legend>'.selecionaVetor($praticas_selecionados, 'selecionados', 'style="width:100%;" size="15" class="texto" multiple="multiple" ondblclick="Remover()"').'</fieldset></td></tr>';
echo '<tr><td colspan="2" align="center"><table width="100%">';
echo '<tr><td align="left"><table cellspacing=0 cellpadding=0><tr><td>'.botao('adicionar', 'Adicionar', 'Utilize este bot�o para adicionar '.($config['genero_pratica']=='a' ? 'uma' : 'um').' '.$config['pratica'].' � lista d'.$config['genero_pratica'].'s selecionad'.$config['genero_pratica'].'s</p>Caso deseja inserir multipl'.$config['genero_pratica'].'s '.$config['praticas'].' de uma �nica vez, mantenha o bot�o <i>CTRL</i> apertado enquanto clica com o bot�o esquerdo do mouse n'.$config['genero_pratica'].'s '.$config['praticas'].' da lista acima.','','Mover()','','',0).'</td></tr></table></td><td>&nbsp;</td><td align="right">'.botao('remover', 'Remover', 'Utilize este bot�o para retirar '.($config['genero_pratica']=='a' ? 'uma' : 'um').' '.$config['pratica'].' da lista d'.$config['genero_pratica'].'s selecionad'.$config['genero_pratica'].'s. </p>Caso deseja remover multipl'.$config['genero_pratica'].'s '.$config['praticas'].' de uma �nica vez, mantenha o bot�o <i>CTRL</i> apertado enquanto clica com o bot�o esquerdo do mouse n'.$config['genero_pratica'].'s '.$config['praticas'].' da lista acima.','','Remover()','','',0).'</td></tr>';
echo '<tr><td>'.botao('aceitar', 'Aceitar', 'Utilize este bot�o para aceitar a edi��o da composi��o.','','Retornar();','','',0).'</td><td>&nbsp;</td>'.(!$Aplic->profissional ? '<td  align="right">'.botao('cancelar', 'Cancelar', 'Utilize este bot�o para cancelar a edi��o de composi��o.','','window.opener = window; window.close()','','',0).'</td>' : '').'</tr>';
echo '</table></td></tr></table></td>';


echo '</table>';
echo estiloFundoCaixa();


?>
<script type="text/javascript">

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


	//checar se j� existe
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

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}

function mudar_praticas(){
	xajax_mudar_praticas_ajax(document.getElementById('cia_id').value, <?php echo $pratica_id ?>); 	
	}	

</script>