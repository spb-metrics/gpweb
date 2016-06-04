<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$projetos = getParam($_REQUEST, 'projeto_id', array());
$sql = new BDConsulta;
//enviar os projetos para outras OM
if (getParam($_REQUEST, 'enviar', 0)){
	
	$ListaPARA = getParam($_REQUEST, 'ListaPARA', array());
	$ListaPraticas = getParam($_REQUEST, 'ListaPraticas', array());
	$ListaIndicadores = getParam($_REQUEST, 'ListaIndicadores', array());

	importar_indicadores($ListaIndicadores, $ListaPARA);
	
	importar_praticas($ListaPraticas, $ListaPARA);
	
	$Aplic->setMsg('Importação concluída', UI_MSG_OK);
	$Aplic->redirecionar('m=sistema&a=index');	
	}


$botoesTitulo = new CBlocoTitulo('Importar '.$config['praticas'].' ou Indicadores do mesmo Servidor', 'importar.gif', $m, "$m.$a");
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();
if (!$dialogo) $Aplic->salvarPosicao();

echo '<form name="frm" id="frm" method="POST">';
echo '<input type="hidden" name="a" id="a" value="indicadores" />';
echo '<input type="hidden" name="m" id="m" value="sistema" />';
echo '<input type="hidden" name="u" id="u" value="importar" />';
echo '<input type="hidden" name="enviar" id="enviar" value="1" />';


echo estiloTopoCaixa();


echo '<table  border=0 cellpadding=0 cellspacing=0 width="100%" class="std">';

echo '<tr><td align=left><table><tr><td>'.dica(ucfirst($config['organizacao']).' d'.$config['genero_pratica'].'s '.ucfirst($config['praticas']).' ou Indicadores à Exportar' , 'Selecione '.$config['organizacao'].' d'.$config['genero_pratica'].'s '.$config['praticas'].' ou indicadores que deseja exportar para outr'.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia2">'.selecionar_om($Aplic->usuario_cia, 'cia_id2', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om2();"').'</div></td><td><a href="javascript:void(0);" onclick="atualizar_praticas_indicadores();">'.imagem('icones/atualizar.png','Mostrar as '.ucfirst($config['praticas']).' e Indicadores d'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/atualizar.png').' para mostrar as '.$config['praticas'].' e indicadores d'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada à esquerda.').'</a></td></tr></table></td></tr>';



echo '<tr><td colspan=20><table width="100%"><tr>';
echo '<td style="text-align:center" width="50%"><fieldset><legend class=texto style="color: black;">'.dica('Seleção de Indicadores','Selecione umum ou mais indicadores para serem copiadas para outr'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'&nbsp;<b>Indicadores</b>&nbsp</legend><div id="combo_indicadores"><select name="ListaIndicadores[]" id="ListaIndicadores" class="texto" size=12 style="width:100%;"></select></div></fieldset></td>';
echo '<td style="text-align:center" width="50%"><fieldset><legend class=texto style="color: black;">'.dica('Seleção de '.ucfirst($config['pratica']),'Selecione um'.($config['genero_pratica']=='a'? 'a' :'').' ou mais '.$config['praticas'].' para serem copiadas para outr'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'&nbsp;<b>'.ucfirst($config['praticas']).'</b>&nbsp</legend><div id="combo_praticas"><select name="ListaPraticas[]" id="ListaPraticas" class="texto" size=12 style="width:100%;"></select></div></fieldset></td>';
echo '</tr></table></td></tr>';







echo '<input type="text" style="display:none">'; //evita que o enter submete o formulário
echo '<tr><td colspan=20><table width="100%"><tr><td style="text-align:center" width="50%">';
echo '<fieldset><legend class=texto style="color: black;">'.dica('Seleção de '.ucfirst($config['organizacao']),'Dê um clique em uma d'.$config['genero_organizacao'].'s '.$config['organizacoes'].' nesta lista de seleção e pressione o botão <b>incluir</b> para adiciona-la à lista de destinatário.').'&nbsp;<b>'.ucfirst($config['organizacoes']).'</b>&nbsp</legend>';
echo '<div id="combo_cia">'.selecionar_om($Aplic->usuario_cia, 'cia_id', 'class="texto" size=12 style="width:100%;" onchange="javascript:mudar_om();"').'</div>';
echo '</fieldset></td>';
echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Destinatárias','Dê um clique duplo em uma d'.$config['genero_organizacao'].'s '.$config['organizacoes'].' nesta lista de seleção para remove-la das destinatárias.<BR><BR>Outra opção é selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_organizacao'].'s '.$config['organizacoes'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Destinatárias</b>&nbsp;</legend><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(\'ListaPARA\', \'cia_id\'); return false;">';
echo '</select></fieldset></td></tr>';

echo '<tr><td class=CampoJanela style="text-align:center"><table><tr>';
echo '<td width="150">'.dica('Incluir','Clique neste botão para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de destinatários.').'<a class="botao" href="javascript:Mover(\'cia_id\', \'ListaPARA\')"><span><b>incluir >></b></span></a></td></tr></table></td><td style="text-align:center"><table width="100%"><tr><td>'.dica("Remover","Clique neste botão para remover os destinatários selecionados da caixa de destinatários.").'<a class="botao" href="javascript:Mover2(\'ListaPARA\', \'cia_id\')"><span><b><< remover</b></span></a></td><td align=right>'.dica('Importar','Clique neste botão para importar para '.$config['genero_organizacao'].'s '.$config['organizacoes'].' selecionadas '.$config['genero_pratica'].'s '.$config['praticas'].' e indicadores destacados.').'<a class="botao" href="javascript:void(0);" onclick="javascript: if(confirm(\'Tem certeza que deseja importar?\'))enviar();"><span><b>importar</b></span></a></td><td width=100>'.dica("Voltar","Clique neste botão para voltar à tela principal.").'<a class="botao" href="javascript:void(0);" onclick="javascript:frm.a.value=\'index\'; frm.m.value=\'sistema\'; frm.u.value=\'\'; frm.submit();"><span><b>voltar</b></span></a></td>';
echo '</tr></table></td></tr>';


echo '</table></td></tr>';	

echo '</table>';
echo estiloFundoCaixa();


echo '</form>';

$vetor_indicador=array();

function importar_indicadores($ListaIndicadores, $ListaPARA){
	global $vetor_indicador,$bd, $Aplic;
	if (!count($ListaIndicadores)) return false;
	
	
	foreach($ListaIndicadores as $chave => $pratica_indicador_id) $vetor_indicador[$pratica_indicador_id]=1;

	achar_filhos_dependentes();
	
	$vetor=array();
	foreach ($vetor_indicador as $pratica_indicador_id => $valor) $vetor[]=$pratica_indicador_id;
	

	$conversao=array();
	$sql = new BDConsulta;
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('*');
	$sql->adOnde('pratica_indicador_id IN ('.implode(',',$vetor).')');
	$lista=$sql->lista();
	$sql->Limpar();
	foreach($lista as $linha){
		foreach($ListaPARA as $cia_receber){
			//checar se a cia à receber já não tem uma prática com o mesmo nome
			/*
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_id');
			$sql->adOnde('pratica_indicador_cia='.(int)$cia_receber);
			$sql->adOnde('pratica_indicador_nome=\''.$linha['pratica_indicador_nome'].'\'');
			$existe=$sql->Resultado();
			*/
			$existe=0;
			if (!$existe)	{
				$sql->adTabela('pratica_indicador');
				$sql->adInserir('pratica_indicador_cia',$cia_receber);
				$sql->adInserir('pratica_indicador_responsavel', null);
				foreach($linha as $chave => $campo)	{
					if ($campo && $chave !='pratica_indicador_id' && $chave !='pratica_indicador_cia' && $chave !='pratica_indicador_responsavel') $sql->adInserir($chave, $campo);
					}
				$sql->sem_chave_estrangeira();	
				$sql->exec();
				$conversao[$linha['pratica_indicador_id']][$cia_receber]=$bd->Insert_ID('pratica_indicador','pratica_indicador_id');
				$sql->Limpar();
				}
			}
		}
	

	$sql->adTabela('pratica_indicador_formula');
	$sql->adCampo('*');
	$sql->adOnde('pratica_indicador_formula_pai IN ('.implode(',',$vetor).')');
	$lista=$sql->lista();
	$sql->Limpar();		
	
	foreach($lista as $linha){
		foreach($ListaPARA as $cia_receber){
			if (isset($conversao[$linha['pratica_indicador_formula_pai']][$cia_receber])){
				$sql->adTabela('pratica_indicador_formula');
				$sql->adInserir('pratica_indicador_formula_pai',$conversao[$linha['pratica_indicador_formula_pai']][$cia_receber]);
				//se for de outra cia só faz referencia ao mesmo
				if (isset($conversao[$linha['pratica_indicador_formula_filho']][$cia_receber])) $sql->adInserir('pratica_indicador_formula_filho', $conversao[$linha['pratica_indicador_formula_filho']][$cia_receber]);
				else $sql->adInserir('pratica_indicador_formula_filho', $linha['pratica_indicador_formula_filho']);
				$sql->adInserir('pratica_indicador_formula_ordem', $linha['pratica_indicador_formula_ordem']);
				$sql->sem_chave_estrangeira();
				$sql->exec();
				}
			}
		}	
		
		
	$sql->adTabela('pratica_indicador_composicao');
	$sql->adCampo('*');
	$sql->adOnde('pratica_indicador_composicao_pai IN ('.implode(',',$vetor).')');
	$lista=$sql->lista();
	$sql->Limpar();		
	foreach($lista as $linha){
		foreach($ListaPARA as $cia_receber){
			if (isset($conversao[$linha['pratica_indicador_composicao_pai']][$cia_receber])){
				$sql->adTabela('pratica_indicador_composicao');
				$sql->adInserir('pratica_indicador_composicao_pai',$conversao[$linha['pratica_indicador_composicao_pai']][$cia_receber]);
				//se for de outra cia só faz referencia ao mesmo
				if (isset($conversao[$linha['pratica_indicador_composicao_filho']][$cia_receber]))  $sql->adInserir('pratica_indicador_composicao_filho', $conversao[$linha['pratica_indicador_composicao_filho']][$cia_receber]);
				else $sql->adInserir('pratica_indicador_composicao_filho', $linha['pratica_indicador_composicao_filho']);
				$sql->adInserir('pratica_indicador_composicao_peso', $linha['pratica_indicador_composicao_peso']);
				$sql->sem_chave_estrangeira();
				$sql->exec();
				}
			}
		}		
		
		
	return true;	
	}


function achar_filhos_dependentes(){
	global $sql, $vetor_indicador;

	foreach ($vetor_indicador as $pratica_indicador_id => $valor) filhos($pratica_indicador_id);
	foreach ($vetor_indicador as $pratica_indicador_id => $valor) composicao($pratica_indicador_id);
	foreach ($vetor_indicador as $pratica_indicador_id => $valor) formula($pratica_indicador_id);
	}

function filhos($pratica_indicador_id){
	global $sql, $vetor_indicador;

	//só buscar os da mesma organização
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_cia');
	$sql->adOnde('pratica_indicador_id='.$pratica_indicador_id);
	$cia=$sql->Resultado();
	$sql->Limpar();


	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id');
	$sql->adOnde('pratica_indicador_superior='.$pratica_indicador_id);
	$sql->adOnde('pratica_indicador_cia='.(int)$cia);
	$lista=$sql->carregarColuna();
	$sql->Limpar();
	foreach ($lista as $chave => $valor) {
		$vetor_indicador[$valor]=1;
		filhos($valor);
		}
	
	return true;
	}


function composicao($pratica_indicador_id){
	global $sql, $vetor_indicador;

	//só buscar os da mesma organização
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_cia');
	$sql->adOnde('pratica_indicador_id='.$pratica_indicador_id);
	$cia=$sql->Resultado();
	$sql->Limpar();


	$sql->adTabela('pratica_indicador_composicao');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador','pratica_indicador_composicao_filho=pratica_indicador_id');
	$sql->adCampo('pratica_indicador_composicao_filho');
	$sql->adOnde('pratica_indicador_composicao_pai='.(int)$pratica_indicador_id);
	$sql->adOnde('pratica_indicador_cia='.(int)$cia);
	$lista=$sql->carregarColuna();
	$sql->Limpar();
	foreach ($lista as $chave => $valor) {
		$vetor_indicador[$valor]=1;
		composicao($valor);
		}
	return true;
	}

function formula($pratica_indicador_id){
	global $sql, $vetor_indicador;

	//só buscar os da mesma organização
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_cia');
	$sql->adOnde('pratica_indicador_id='.$pratica_indicador_id);
	$cia=$sql->Resultado();
	$sql->Limpar();
	
	$sql->adTabela('pratica_indicador_formula');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador','pratica_indicador_formula_filho=pratica_indicador_id');
	$sql->adCampo('pratica_indicador_formula_filho');
	$sql->adOnde('pratica_indicador_formula_pai='.$pratica_indicador_id);
	$sql->adOnde('pratica_indicador_cia='.(int)$cia);
	$lista=$sql->carregarColuna();
	$sql->Limpar();
	foreach ($lista as $chave => $valor) {
		$vetor_indicador[$valor]=1;
		formula($valor);
		}
	return true;
	}


function importar_praticas($ListaPraticas, $ListaPARA){
	if (!count($ListaPraticas)) return false;
	$sql = new BDConsulta;
	$sql->adTabela('praticas');
	$sql->adCampo('*');
	$sql->adOnde('pratica_id IN ('.implode(',',$ListaPraticas).')');
	$lista=$sql->lista();
	$sql->Limpar();
	foreach($lista as $linha){
		foreach($ListaPARA as $cia_receber){
			
			//checar se a cia à receber já não tem uma prática com o mesmo nome
			$sql->adTabela('praticas');
			$sql->adCampo('pratica_id');
			$sql->adOnde('pratica_cia='.(int)$cia_receber);
			$sql->adOnde('pratica_nome=\''.$linha['pratica_nome'].'\'');
			$existe=$sql->Resultado();
			if (!$existe)	{
				$sql->adTabela('praticas');
				$sql->adInserir('pratica_cia',$cia_receber);
				$sql->adInserir('pratica_responsavel',0);
				foreach($linha as $chave => $campo)	{
					if ($chave !='pratica_id' && $chave !='pratica_cia' && $chave !='pratica_responsavel') $sql->adInserir($chave, $campo);
					}
				$sql->sem_chave_estrangeira();	
				$sql->exec();
				$sql->Limpar();
				}
			}
		}
	return true;	
	}
?>
<script>
	
function atualizar_praticas_indicadores(){
	xajax_atualizar_indicadores(document.getElementById('cia_id2').value);
	xajax_atualizar_praticas(document.getElementById('cia_id2').value);
	}
	
function mudar_om2(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id2').value,'cia_id2','combo_cia2', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om2();"'); 	
	}		
	
	
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=12 style="width:100%;" onchange="javascript:mudar_om();"'); 	
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

// Seleciona todos os campos da lista de destinatários e efetua o submit
function enviar() {
	if (frm.ListaPARA.length <= 0) alert("Selecione ao menos uma <?php echo $config['organizacao']?>!");
	else{
		for (var i=0; i < frm.ListaPARA.length ; i++) frm.ListaPARA.options[i].selected = true;
		frm.submit();
		}
	}

function btSelecionarTodos_onclick() {
	ListaDE=document.getElementById('cia_id');
	for (var i=0; i < ListaDE.length ; i++) {
		ListaDE.options[i].selected = true;
	}
	Mover('cia_id', 'ListaPARA');
}
	
</script>