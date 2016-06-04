<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
$Aplic->carregarCKEditorJS();

$projetos = getParam($_REQUEST, 'projeto_id', array());
$sql = new BDConsulta;
//enviar os projetos para outras OM
if (getParam($_REQUEST, 'enviar', 0)){
	$obs_remetente = getParam($_REQUEST, 'obs_remetente', '');
	$lista_projetos = getParam($_REQUEST, 'lista_projetos', '');
	$projetos=explode(',', $lista_projetos);
	$ListaPARA = getParam($_REQUEST, 'ListaPARA', array());
	foreach ((array)$ListaPARA as $chave => $cia_id){
		foreach ((array)$projetos as $chave => $projeto_id){
			//para evitar duplica��o
			$sql->adTabela('projeto_observado');
			$sql->adCampo('count(projeto_id)');
			$sql->adOnde('projeto_id ='.$projeto_id);
			$sql->adOnde('cia_para ='.$cia_id);
			$resultado=$sql->Resultado();
			$sql->limpar();
			if (!$resultado){
				
				$sql->adTabela('projetos');
				$sql->adCampo('projeto_cia');
				$sql->adOnde('projeto_id ='.$projeto_id);
				$projeto_cia=$sql->Resultado();
				$sql->limpar();
				
				$sql->adTabela('projeto_observado');
				$sql->adInserir('projeto_id',$projeto_id );
				$sql->adInserir('cia_para',$cia_id);
				$sql->adInserir('cia_de',$projeto_cia);
				$sql->adInserir('remetente',$Aplic->usuario_id);
				$sql->adInserir('data_envio',date('Y-m-d H:i:s'));
				$sql->adInserir('obs_remetente',$obs_remetente);
				$sql->exec();
				$sql->Limpar();
				}
			}
		}
		
	$Aplic->setMsg((count($projetos)>1 ? $config['projetos'].' enviad'.$config['genero_projeto'].'s' : $config['projeto'].' enviad'.$config['genero_projeto']), UI_MSG_OK);
	$Aplic->redirecionar('m=projetos&a=index');	
	}


echo '<form name="frm" id="frm" method="POST" >';
echo '<input type="hidden" name="a" id="a" value="enviar_projeto" />';
echo '<input type="hidden" name="m" id="m" value="projetos" />';
echo '<input type="hidden" name="enviar" id="enviar" value="1" />';
echo '<input type="hidden" name="lista_projetos" id="lista_projetos" value="'.implode(',', $projetos).'" />';


$sql->adTabela('projetos');
$sql->adCampo('projeto_nome');
$sql->adOnde('projeto_id IN ('.implode(',', $projetos).')');
$nomes=$sql->lista();
$sql->limpar();

echo estiloTopoCaixa();




echo '<table  border=0 cellpadding=0 cellspacing=0 width="100%" class="std">';

$lista_nome='';
$i=0;
foreach ($nomes as $nome) $lista_nome.=($i++ ? '<br>' : '').$nome['projeto_nome'];

echo '<tr><td align="center" colspan=20><b>'.ucfirst($config['projeto']).(count($nomes)>1 ? 's' : '').'</b><br>'.$lista_nome.'</td><tr>';

echo '<tr><td align="right" nowrap="nowrap" width="100">'.dica('Observa��es', 'Texto para acompanhar o envio '.(count($nomes)>1 ? 'd'.$config['genero_projeto'].'s '.$config['projetos'] : 'd'.$config['genero_projeto'].' '.$config['projeto'])).'Observa��o:'.dicaF().'</td><td width="100%" colspan="2"><textarea name="obs_remetente" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea"></textarea></td></tr>';
echo '<input type="text" style="display:none">'; //evita que o enter submete o formul�rio
echo '<tr><td colspan=20><table width="100%"><tr><td style="text-align:center" width="50%">';
echo '<fieldset><legend class=texto style="color: black;">'.dica('Sele��o de '.ucfirst($config['organizacao']),'D� um clique em uma d'.$config['genero_organizacao'].'s '.$config['organizacoes'].' nesta lista de sele��o e pressione o bot�o <b>incluir</b> para adiciona-la � lista de destinat�rio.').'&nbsp;<b>'.ucfirst($config['organizacoes']).'</b>&nbsp</legend>';
echo '<div id="combo_cia">'.selecionar_om($Aplic->usuario_cia, 'cia_id', 'class="texto" size=12 style="width:100%;" onchange="javascript:mudar_om();"').'</div>';
echo '</fieldset></td>';
echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Destinat�rias','D� um clique duplo em uma d'.$config['genero_organizacao'].'s '.$config['organizacoes'].' nesta lista de sele��o para remove-la das destinat�rias.<BR><BR>Outra op��o � selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' e clicar no bot�o Remover.<BR><BR>Para selecionar m�ltipl'.$config['genero_organizacao'].'s '.$config['organizacoes'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Destinat�rias</b>&nbsp;</legend><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(\'ListaPARA\', \'cia_id\'); return false;">';
echo '</select></fieldset></td></tr>';

echo '<tr><td class=CampoJanela style="text-align:center"><table><tr><td width="150">'.dica('Incluir','Clique neste bot�o para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de destinat�rios.').'<a class="botao" href="javascript:Mover(\'cia_id\', \'ListaPARA\')"><span><b>incluir >></b></span></a></td></tr></table></td><td style="text-align:center"><table><tr><td>'.dica("Remover","Clique neste bot�o para remover os destinat�rios selecionados da caixa de destinat�rios.").'<a class="botao" href="javascript:Mover2(\'ListaPARA\', \'cia_id\')"><span><b><< remover</b></span></a></td><td width=230>&nbsp;</td><td>'.dica("Voltar","Clique neste bot�o para voltar � tela principal.").'<a class="botao" href="javascript:void(0);" onclick="javascript:frm.a.value=\'index\'; frm.m.value=\'projetos\'; frm.submit();"><span><b>voltar</b></span></a></td>
<td>'.dica('Enviar','Clique neste bot�o para enviar para '.$config['genero_organizacao'].'s '.$config['organizacoes'].' selecionadas.').'<a class="botao" href="javascript:void(0);" onclick="javascript:enviar();"><span><b>enviar</b></span></a></td>
</tr></table></td></tr>';


echo '</table></td></tr>';	

echo '</table>';
echo estiloFundoCaixa();


echo '</form>';


?>
<script>
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=12 style="width:100%;" onchange="javascript:mudar_om();"'); 	
	}	

function pesquisar(){	
	texto=htmlentities(document.getElementById('texto').value);
	xajax_exibir_combo('om_achada','cias','cia_id','cia_nome_completo','cia_nome LIKE \'%'+texto+'%\' OR cia_nome_completo LIKE \'%'+texto+'%\'','cia_nome ASC', 'class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover(\'cia_id\', \'ListaPARA\'); return false;"','cia_id','',false); 	
	}
	
function htmlentities(texto){
  var i,carac,letra,novo='';
  for(i=0;i<texto.length;i++){
    carac = texto[i].charCodeAt(0);
    if( (carac > 47 && carac < 58) || (carac > 62 && carac < 127) )novo += texto[i];
   	else novo += "&#" + texto[i].charCodeAt(0) + ";";
  	}
  return novo;
	}
	

function Mover(de,para) {
	ListaDE=document.getElementById(de);
	ListaPARA=document.getElementById(para);
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value != "-1") {
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
				ListaDE.options[i].value = "";
				ListaDE.options[i].text = "";
				}
			}
		}
	LimpaVazios(ListaDE, ListaDE.options.length);
	}

function Mover2(para,de) {
	ListaDE=document.getElementById(de);
	ListaPARA=document.getElementById(para);
	
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value != "-1") {
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

// Seleciona todos os campos da lista de destinat�rios e efetua o submit
function enviar() {
	if (frm.ListaPARA.length <= 0) { alert("Selecione ao menos uma <?php echo $config['organizacao']?>!");exit; }
	for (var i=0; i < frm.ListaPARA.length ; i++) {
		frm.ListaPARA.options[i].selected = true;
		}
	
	frm.submit();
	}

function btSelecionarTodos_onclick() {
	ListaDE=document.getElementById('cia_id');
	for (var i=0; i < ListaDE.length ; i++) {
		ListaDE.options[i].selected = true;
	}
	Mover('cia_id', 'ListaPARA');
}
	
</script>