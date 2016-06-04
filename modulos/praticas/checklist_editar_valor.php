<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;

$Aplic->carregarCalendarioJS();
$Aplic->carregarCKEditorJS();


$checklist_dados_id = getParam($_REQUEST, 'checklist_dados_id', null);
$pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', null);

$salvar = getParam($_REQUEST, 'salvar', 0);
$excluir = getParam($_REQUEST, 'excluir', 0);
$sql = new BDConsulta;

if (!$pratica_indicador_id && $checklist_dados_id){
	$sql->adTabela('checklist_dados');
	$sql->adCampo('pratica_indicador_valor_indicador');
	$sql->adOnde('checklist_dados_id = '.(int)$checklist_dados_id);
	$pratica_indicador_id = $sql->Resultado();
	$sql->limpar();
	}

$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_acumulacao, pratica_indicador_agrupar, pratica_indicador.pratica_indicador_acesso, pratica_indicador.pratica_indicador_nome, pratica_indicador_checklist_valor');
$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
$pratica_indicador=$sql->Linha();
$sql->limpar();

if (!($podeEditar && permiteAcessarIndicador($pratica_indicador['pratica_indicador_acesso'],$pratica_indicador_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');

if ($excluir && $checklist_dados_id){
	$sql->setExcluir('checklist_dados');
	$sql->adOnde('checklist_dados_id='.(int)$checklist_dados_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela checklist_dados!'.$bd->stderr(true));
	$sql->limpar();
	$Aplic->setMsg('Valor excluído', UI_MSG_OK);
	$Aplic->redirecionar('m=praticas&a=indicador_ver&pratica_indicador_id='.(int)$pratica_indicador_id);
	}



if ($salvar){
	//calcular valor e criar o objeto
	$sql->adTabela('checklist_lista');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_checklist=checklist_lista.checklist_lista_checklist_id');
	$sql->adCampo('checklist_lista_id, checklist_lista_checklist_id, checklist_lista_ordem, checklist_lista_descricao, checklist_lista_peso, checklist_lista_legenda');
	$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
	$sql->adOrdem('checklist_lista_ordem ASC');
	$checklist_lista = $sql->Lista();
	$sql->limpar();	

	$sql->adTabela('checklist_campo');
	$sql->esqUnir('checklist', 'checklist', 'checklist.checklist_modelo=checklist_campo.checklist_modelo_id');
	$sql->adCampo('checklist_campo_campo, checklist_campo_porcentagem');
	$sql->adOnde('checklist.checklist_id = '.(int)$checklist_lista[0]['checklist_lista_checklist_id']);
	$sql->adOrdem('checklist_campo_posicao ASC');
	$campos = $sql->listaVetorChave('checklist_campo_campo','checklist_campo_porcentagem');
	$sql->limpar();
	
	$resultado=array();
	foreach($campos as $chave => $campo) $resultado[$chave]=getParam($_REQUEST, $chave, array());

	$justificativa=getParam($_REQUEST, 'justificativa', array());
	$legenda=getParam($_REQUEST, 'legenda', array());
	
	$vetor=array();
	$total=0;
	$obtido=0;
	$na=0;
	
	foreach ($checklist_lista as $chave => $linha){

		if (!$legenda[$chave]){
			foreach($campos as $chave_tipo => $campo) {
				if ($resultado[$chave_tipo][$chave]==1) {
					if ($campo>0)	$obtido+=($resultado[$chave_tipo][$chave]*$campos[$chave_tipo]*$linha['checklist_lista_peso']);
					elseif ($campo==-1) $na+=$linha['checklist_lista_peso'];
					}
				}
			$total+=$linha['checklist_lista_peso'];	
			}
			
		}
	$total=$total-$na;
	
	foreach ($checklist_lista as $chave => $linha){
		$montagem_vetor=array('checklist_lista_id' => $linha['checklist_lista_id'], 'checklist_lista_checklist_id' => $linha['checklist_lista_checklist_id'], 'checklist_lista_ordem' => $linha['checklist_lista_ordem'], 'checklist_lista_descricao' => $linha['checklist_lista_descricao'], 'checklist_lista_peso' => $linha['checklist_lista_peso'], 'checklist_lista_justificativa' => $justificativa[$chave], 'checklist_lista_legenda' => $legenda[$chave]);
		foreach($campos as $chave_campo => $dados_campo) $montagem_vetor['checklist_lista_'.$chave_campo]=$resultado[$chave_campo][$chave];
		$vetor[]=$montagem_vetor;
		}
		
	if ($total && !$pratica_indicador['pratica_indicador_checklist_valor']) $pontuacao=($obtido/$total)*100;
	elseif ($total) $pontuacao=$obtido;
	else $pontuacao=0;
	}

if ($salvar && $checklist_dados_id){
	$sql->adTabela('checklist_dados');
	$sql->adAtualizar('pratica_indicador_valor_valor',(float)$pontuacao);
	$sql->adAtualizar('pratica_indicador_valor_data', getParam($_REQUEST, 'pratica_indicador_valor_data', ''));
	$sql->adAtualizar('checklist_dados_responsavel', getParam($_REQUEST, 'checklist_dados_responsavel', ''));
	$sql->adAtualizar('checklist_dados_obs', getParam($_REQUEST, 'checklist_dados_obs', ''));
	$sql->adAtualizar('pratica_indicador_valor_indicador', (int)$pratica_indicador_id);
	$sql->adAtualizar('checklist_dados_campos', serialize($vetor));
	$sql->adOnde('checklist_dados_id = '.(int)$checklist_dados_id);
	$retorno=$sql->exec();
	$sql->Limpar();
	}
elseif($salvar){
	$sql->adTabela('checklist_dados');
	$sql->adInserir('pratica_indicador_valor_valor',(float)$pontuacao);
	$sql->adInserir('pratica_indicador_valor_data', getParam($_REQUEST, 'pratica_indicador_valor_data', ''));
	$sql->adInserir('checklist_dados_responsavel', getParam($_REQUEST, 'checklist_dados_responsavel', ''));
	$sql->adInserir('checklist_dados_obs', getParam($_REQUEST, 'checklist_dados_obs', ''));
	$sql->adInserir('pratica_indicador_valor_indicador', (int)$pratica_indicador_id);
	$sql->adInserir('checklist_dados_campos', serialize($vetor));
	$sql->exec();
	$sql->Limpar();
	}


if ($salvar){
	$checklist_dados_id=null;
	}

$df = '%d/%m/%Y';
$ttl = $checklist_dados_id ? 'Editar Valor de Checklist' : 'Preencher Valor de Checklist';
$botoesTitulo = new CBlocoTitulo($ttl, 'todo_list.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();


$sql->adTabela('checklist_dados');
$sql->adCampo('checklist_dados.*');
$sql->adOnde('checklist_dados_id='.(int)$checklist_dados_id);
$pratica_indicado_valor=$sql->Linha();
$sql->limpar();


$data = isset($pratica_indicado_valor['pratica_indicador_valor_data']) ? new CData($pratica_indicado_valor['pratica_indicador_valor_data']) : new CData();
echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="checklist_editar_valor" />';
echo '<input type="hidden" name="checklist_dados_id" value="'.$checklist_dados_id.'" />';
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';


echo '<tr><td colspan=20><tr><td><table cellpadding=0 cellspacing=0 border=1><tr><td><table width="800" cellpadding=0 cellspacing=1>';



echo '<tr><td colspan=20 align=center><h1>'.$pratica_indicador['pratica_indicador_nome'].'</h1></td></tr>';

if (isset($pratica_indicado_valor['checklist_dados_id'])&& $pratica_indicado_valor['checklist_dados_id']){
	$checklist_lista=@unserialize($pratica_indicado_valor['checklist_dados_campos']);
	}
else{
	$sql->adTabela('checklist_lista');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_checklist=checklist_lista.checklist_lista_checklist_id');
	$sql->adCampo('checklist_lista.*');
	$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
	$sql->adOrdem('checklist_lista_ordem ASC');
	$checklist_lista = $sql->Lista();
	$sql->limpar();	

	}	
	
if ($checklist_lista && count($checklist_lista)){
	
	$sql->adTabela('checklist_campo');
	$sql->esqUnir('checklist', 'checklist', 'checklist.checklist_modelo=checklist_campo.checklist_modelo_id');
	$sql->adCampo('checklist_campo.*');
	$sql->adOnde('checklist.checklist_id = '.(int)$checklist_lista[0]['checklist_lista_checklist_id']);
	$sql->adOrdem('checklist_campo_posicao ASC');
	$campos = $sql->Lista();
	$sql->limpar();
	
	
	echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0 class="tbl1" align=left width="800">';
	
	$cabecalho='<tr><th>'.dica('Proposição','O ítem a ser verificado, sendo que a questão deverá estar formulada de uma forma que a resposta esperada seja um SIM.').'Proposição'.dicaF().'</th>';
	$colunas=2;
	foreach($campos as $campo) {
		$cabecalho.='<th>'.dica($campo['checklist_campo_nome'],$campo['checklist_campo_texto']).$campo['checklist_campo_nome'].dicaF().'</th>';
		$colunas++;
		}
	$cabecalho.='<th>'.dica('Evidência/Justificativa','Neste campo poderá constar informações pertinentes que justifiquem a opção marcada.').'Evidência/Justificativa'.dicaF().'</th></tr>';
	

	$qnt=0;
	foreach($checklist_lista as $linha) {
		if (!isset($linha['checklist_lista_na'])) $linha['checklist_lista_na']=0;

		if (!$qnt++ && (!isset($linha['checklist_lista_legenda']) || (isset($linha['checklist_lista_legenda']) && !$linha['checklist_lista_legenda']))) echo $cabecalho;

		if (isset($linha['checklist_lista_legenda']) && $linha['checklist_lista_legenda']) {
			echo '<tr><td'.($linha['checklist_lista_legenda'] ? ' colspan='.$colunas : '').' ><br><b>'.$linha['checklist_lista_descricao'].'</b>';
			foreach($campos as $campo) {
				echo '<input style="display:none" type="checkbox" value="1" name="'.$campo['checklist_campo_campo'].'_'.$linha['checklist_lista_id'].'" /><input type="hidden" name="'.$campo['checklist_campo_campo'].'[]" id="checklist_'.$campo['checklist_campo_campo'].'_'.$linha['checklist_lista_id'].'" value="'.(isset($linha['checklist_lista_'.$campo['checklist_campo_campo']]) && $linha['checklist_lista_'.$campo['checklist_campo_campo']] ? '1' : '0').'" />';
				}
			echo '<td><input type="hidden" name="justificativa[]" value=""><input type="hidden" name="legenda[]" value="1">';
			echo '</td></tr>';
			}
		else {
			echo '<tr><td>'.$linha['checklist_lista_descricao'].'</td>';
			foreach($campos as $campo) {
				echo '<td><input type="checkbox" value="1" name="'.$campo['checklist_campo_campo'].'_'.$linha['checklist_lista_id'].'" '.( isset($linha['checklist_lista_'.$campo['checklist_campo_campo']]) && $linha['checklist_lista_'.$campo['checklist_campo_campo']] ? 'checked="checked"' : '').' onchange="if (env.'.$campo['checklist_campo_campo'].'_'.$linha['checklist_lista_id'].'.checked) {document.getElementById(\'checklist_'.$campo['checklist_campo_campo'].'_'.$linha['checklist_lista_id'].'\').value=1;';
				foreach ($campos as $campo2) {
					if ($campo2['checklist_campo_campo']!=$campo['checklist_campo_campo']) echo 'document.getElementById(\'checklist_'.$campo2['checklist_campo_campo'].'_'.$linha['checklist_lista_id'].'\').value=0; env.'.$campo2['checklist_campo_campo'].'_'.$linha['checklist_lista_id'].'.checked=false;';
					}
				echo '}" /><input type="hidden" name="'.$campo['checklist_campo_campo'].'[]" id="checklist_'.$campo['checklist_campo_campo'].'_'.$linha['checklist_lista_id'].'" value="'.(isset($linha['checklist_lista_'.$campo['checklist_campo_campo']]) && $linha['checklist_lista_'.$campo['checklist_campo_campo']] ? '1' : '0').'" /></td>';
				}
			echo '<td><textarea name="justificativa[]" class="texto" style="width:180px;" rows="1" class="textarea">'.$linha['checklist_lista_justificativa'].'</textarea><input type="hidden" name="legenda[]" value="0"></td></tr>';
			}

		
		if (isset($linha['checklist_lista_legenda']) && $linha['checklist_lista_legenda']) echo $cabecalho;
		}
	echo '</table></td></tr>';
	
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pela Inserção', 'Todo indicador ao ter valor inserido deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="checklist_dados_responsavel" name="checklist_dados_responsavel" value="'.(isset($pratica_indicado_valor['checklist_dados_responsavel']) && $pratica_indicado_valor['checklist_dados_responsavel'] ? $pratica_indicado_valor['checklist_dados_responsavel'] : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om((isset($pratica_indicado_valor['checklist_dados_responsavel']) && $pratica_indicado_valor['checklist_dados_responsavel'] ? $pratica_indicado_valor['checklist_dados_responsavel'] : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da Aferição', 'Quando foi aferido o valor.').'Data da Aferição:'.dicaF().'</td><td width="100%" colspan="2"><input type="hidden" name="pratica_indicador_valor_data" id="pratica_indicador_valor_data" value="'.($data ? $data->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'env\', \'data\', \'pratica_indicador_valor_data\');" value="'.($data ? $data->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data em que foi aferido o valor.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Observação', 'Observação sobre esta inserção.').'Observação:'.dicaF().'</td><td colspan=2><table cellspacing=0 cellpadding=0 style="width:400px;"><tr><td><textarea rows="2" style="width:400px;" name="checklist_dados_obs" id="checklist_dados_obs" data-gpweb-cmp="ckeditor">'.($pratica_indicado_valor['checklist_dados_obs'] ? $pratica_indicado_valor['checklist_dados_obs'] : '').'</textarea></td></tr></table></td></tr>';
	}
else echo '<tr><td align="center" colspan=20><h1>Este checklist não tem nenhuma pergunta cadastrada!</h1></td></tr>';



echo '</table></td><td align=left><table cellpadding=0 cellspacing=0 align=left>';


echo '<tr>';
echo '<td id="adicionar_acao" style="display:'.($checklist_dados_id ? 'none' : '').'"><a href="javascript: void(0);" onclick="enviarDados();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar_g.png').' para o checklist.').'</a></td>';
echo '<td id="confirmar_acao" style="display:'.($checklist_dados_id ? '' : 'none').'"><a href="javascript: void(0);" onclick="enviarDados();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok_g.png').' para confirmar a edição do checklist.').'</a><a href="javascript: void(0);" onclick="limpar(); document.getElementById(\'confirmar_acao\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar_g.png').' para cancelar a edição do checklist.').'</a></td>';
echo '</tr></table></td></tr></table></td></tr>';





echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td align="right">'.botao('voltar', 'Voltar', 'Voltar a tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr></table></td></tr>';









$sql->adTabela('checklist_dados');
$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = checklist_dados_responsavel');
$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS dono, checklist_dados_id, pratica_indicador_valor_valor, formatar_data(pratica_indicador_valor_data, "%d/%m/%Y") AS data, checklist_dados_obs');
$sql->adOnde('pratica_indicador_valor_indicador = '.(int)$pratica_indicador_id);
$sql->adOrdem('pratica_indicador_valor_data DESC');
$valores = $sql->Lista();
$sql->limpar();


echo '<tr><td colspan=20><div id="combo_valores">';
if (count($valores)){
	echo '<table cellspacing=0 cellpadding=2 class="tbl1" width="100%"><tr>';
	echo '<th>'.dica('Data', 'Data de inserção do valor.').'Data'.dicaF().'</th>';
	echo '<th>'.dica('Valor', 'O valor inserido no indicador.').'Valor'.dicaF().'</th>';
	echo '<th>'.dica('Responsável', 'Responsável pela inserção do valor.').'Responsável'.dicaF().'</th>';
	echo '<th>'.dica('Observações', 'Observações neste valor.').'Observações'.dicaF().'</th>';
	echo '<th></th></tr>';
	echo '';
	foreach($valores as $valor){
		echo '<tr><td width="60" nowrap="nowrap" align=center>'.$valor['data'].'</td>';
		echo '<td width="60" nowrap="nowrap" align=right>'.number_format($valor['pratica_indicador_valor_valor'], $config['casas_decimais'], ',', '.').'</td>';
		echo '<td>'.$valor['dono'].'</td>';
		echo '<td>'.($valor['checklist_dados_obs']? $valor['checklist_dados_obs'] : '&nbsp;').'</td>';
		echo '<td width="'.($Aplic->profissional ? '48' : '32').'" align=center>';
		if ($Aplic->profissional) echo '<a href="javascript: void(0);" onclick="anexar_arquivo('.$valor['checklist_dados_id'].');">'.imagem('icones/anexar.png', 'Anexar Arquivo', 'Clique neste ícone '.imagem('icones/anexar.png').' para anexar arquivo junto ao valor.').'</a>';
		echo '<a href="javascript: void(0);" onclick="editar_valor('.$valor['checklist_dados_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o valor.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este valor?\')) {excluir_valor('.$valor['checklist_dados_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este valor.').'</a>';
		echo '</td>';
		echo '</tr>';
		}
	}
echo '</div></table></td></tr>';








echo '</table>';
echo '</form>';








echo estiloFundoCaixa();


?>
<script language="javascript">

function limpar(){
	document.getElementById('adicionar_acao').style.display='';
	

  with(document.getElementById('env')) {
	  for(i=0; i < elements.length; i++) {
			if (elements[i].checked) elements[i].checked = false;
			if (elements[i].name=='justificativa[]') elements[i].value='';
      }
    }
 <?php if ($Aplic->profissional) echo "CKEDITOR.instances['checklist_dados_obs'].setData('');" ?>   
	
	}

function anexar_arquivo(checklist_dados_id){
	parent.gpwebApp.popUp('Anexar Arquivo', 400, 400, 'm=praticas&a=indicador_valor_anexo_pro&dialogo=1&checklist_dados_id='+checklist_dados_id, null, window);
	}

function editar_valor(checklist_dados_id){
	url_passar(0, 'm=praticas&a=checklist_editar_valor&checklist_dados_id='+checklist_dados_id+'&pratica_indicador_id='+document.getElementById('pratica_indicador_id').value);
	}

function excluir_valor(checklist_dados_id){
	xajax_excluir_valor(checklist_dados_id, document.getElementById('pratica_indicador_id').value);
	__buildTooltip();
	}
	


function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode; 
  var unicode1 = event.keyCode; 
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true; 
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true; 
      	return false;
      	}
    	}
  	}
	}
function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&usuario_id='+document.getElementById('checklist_dados_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&usuario_id='+document.getElementById('checklist_dados_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('checklist_dados_responsavel').value=usuario_id;		
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}	

 var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "pratica_indicador_valor_data",
  	date :  <?php echo $data->format("%Y%m%d")?>,
  	selection: <?php echo $data->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pratica_indicador_valor_data").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide(); 
  	}
  });
 
   
function setData( frm_nome, f_data, f_data_real ) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
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

function excluir() {
	if (confirm( "Tem certeza que deseja excluir este valor do indicador?" )) {
		var f = document.env;
		f.excluir.value=1;
		f.submit();
		}
	}

	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	


function enviarDados() {
	var f = document.env;
	f.salvar.value=1;
	f.submit();
	}

</script>

