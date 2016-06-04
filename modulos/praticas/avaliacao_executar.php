<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$avaliacao_id = getParam($_REQUEST, 'avaliacao_id', 0);
$avaliacao_indicador_lista_id = getParam($_REQUEST, 'avaliacao_indicador_lista_id', 0);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta();

if ($avaliacao_indicador_lista_id){
	$sql->adTabela('avaliacao_indicador_lista');
	$sql->adCampo('avaliacao_indicador_lista_pratica_indicador_id');
	$sql->adOnde('avaliacao_indicador_lista_id = '.(int)$avaliacao_indicador_lista_id);
	$pratica_indicador_id=$sql->Resultado();
	$sql->limpar();	
	}
else $pratica_indicador_id=0;


if ($pratica_indicador_id){
	//verificar se é checklist ou valor simples
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_checklist');
	$sql->adOnde('pratica_indicador_id='.$pratica_indicador_id);
	$checklist_id=$sql->Resultado();
	$sql->limpar();
	}
else $checklist_id=0;

if ($salvar && $pratica_indicador_id && $checklist_id){
	//calcular valor e criar o objeto
	$sql->adTabela('checklist_lista');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_checklist=checklist_lista.checklist_lista_checklist_id');
	$sql->adCampo('checklist_lista.checklist_lista_id, checklist_lista.checklist_lista_checklist_id, checklist_lista.checklist_lista_ordem, checklist_lista.checklist_lista_descricao, checklist_lista.checklist_lista_peso');
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
		$total=$total-$na;
		}
	
	foreach ($checklist_lista as $chave => $linha){
		$montagem_vetor=array('checklist_lista_id' => $linha['checklist_lista_id'], 'checklist_lista_checklist_id' => $linha['checklist_lista_checklist_id'], 'checklist_lista_ordem' => $linha['checklist_lista_ordem'], 'checklist_lista_descricao' => $linha['checklist_lista_descricao'], 'checklist_lista_peso' => $linha['checklist_lista_peso'], 'checklist_lista_justificativa' => $justificativa[$chave], 'checklist_lista_legenda' => $legenda[$chave]);
		foreach($campos as $chave_campo => $dados_campo) $montagem_vetor['checklist_lista_'.$chave_campo]=$resultado[$chave_campo][$chave];
		$vetor[]=$montagem_vetor;
		}
		
	if ($total) $pontuacao=($obtido/$total)*100;
	else $pontuacao=0;

	
	$sql->adTabela('avaliacao_indicador_lista');
	$sql->adAtualizar('avaliacao_indicador_lista_valor',$pontuacao);
	$sql->adAtualizar('avaliacao_indicador_lista_data', date('Y-m-d H:i:s'));
	$sql->adAtualizar('avaliacao_indicador_lista_checklist_campos', serialize($vetor));
	$sql->adAtualizar('avaliacao_indicador_lista_observacao', getParam($_REQUEST, 'avaliacao_indicador_lista_observacao', ''));
	$sql->adAtualizar('avaliacao_indicador_lista_checklist_dados_id', getParam($_REQUEST, 'checklist_dados_id', null));
	$sql->adOnde('avaliacao_indicador_lista_id='.$avaliacao_indicador_lista_id);
	$sql->exec();
	$sql->Limpar();
	
	if (!$dialogo) ver2('Avaliação efetuada');
	$avaliacao_indicador_lista_id=0;
	$pratica_indicador_id=0;
	}



if ($salvar && $avaliacao_indicador_lista_id && !$checklist_id){
	
	$sql->adTabela('avaliacao_indicador_lista');
	$sql->adAtualizar('avaliacao_indicador_lista_valor',float_americano(getParam($_REQUEST, 'avaliacao_indicador_lista_valor', '')));
	$sql->adAtualizar('avaliacao_indicador_lista_data', date('Y-m-d H:i:s'));
	$sql->adAtualizar('avaliacao_indicador_lista_observacao', getParam($_REQUEST, 'avaliacao_indicador_lista_observacao', ''));
	$sql->adAtualizar('avaliacao_indicador_lista_pratica_indicador_valor_id', getParam($_REQUEST, 'avaliacao_indicador_lista_pratica_indicador_valor_id', null));
	$sql->adOnde('avaliacao_indicador_lista_id='.$avaliacao_indicador_lista_id);
	$sql->exec();
	$sql->Limpar();
	
	if (!$dialogo) ver2('Avaliação efetuada');
	$avaliacao_indicador_lista_id=0;
	$pratica_indicador_id=0;
	}




if (!$dialogo){
	$botoesTitulo = new CBlocoTitulo('Executar Avaliação', 'avaliacao.gif', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=praticas&a=avaliacao_lista', 'lista','','Lista de Avaliaçãos','Clique neste botão para visualizar a lista de avaliacao.');
	$botoesTitulo->mostrar();
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="dialogo" value="'.$dialogo.'" />';
echo '<input type="hidden" name="salvar" value="" />';


$sql = new BDConsulta;


if (!$dialogo)echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';


$sql->adTabela('avaliacao');
$sql->esqUnir('avaliacao_indicador_lista','avaliacao_indicador_lista','avaliacao_indicador_lista_avaliacao=avaliacao_id');
$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_id=avaliacao_indicador_lista_pratica_indicador_id');
$sql->adCampo('DISTINCT avaliacao_id, avaliacao_nome');
$sql->adOnde('avaliacao_indicador_lista_usuario='.(int)$Aplic->usuario_id);
$sql->adOnde('avaliacao_indicador_lista_data IS NULL');
$sql->adOnde('pratica_indicador_composicao=0');
$sql->adOnde('pratica_indicador_formula=0');
$sql->adOnde('pratica_indicador_formula_simples=0');
$sql->adOnde('pratica_indicador_campo_projeto=0');
$sql->adOnde('pratica_indicador_campo_tarefa=0');
$sql->adOnde('pratica_indicador_campo_acao=0');
$sql->adOrdem('avaliacao_nome');
$lista_avaliacoes=array(''=>'selecione uma avaliação')+$sql->listaVetorChave('avaliacao_id','avaliacao_nome');
$sql->limpar();
echo '<tr><td colspan=20>'.selecionaVetor($lista_avaliacoes, 'avaliacao_id', 'style="width:380px;" size="1" class="texto" onchange="mudar_avaliacao();"',$avaliacao_id).'</td></tr>';

$sql->adTabela('pratica_indicador');
$sql->esqUnir('cias','cias','pratica_indicador_cia=cia_id');
$sql->esqUnir('avaliacao_indicador_lista','avaliacao_indicador_lista','avaliacao_indicador_lista_pratica_indicador_id=pratica_indicador_id');
$sql->adCampo('avaliacao_indicador_lista_id, pratica_indicador_nome');
$sql->adOnde('avaliacao_indicador_lista_usuario='.(int)$Aplic->usuario_id);
$sql->adOnde('avaliacao_indicador_lista_data IS NULL');
$sql->adOnde('pratica_indicador_composicao=0');
$sql->adOnde('pratica_indicador_formula=0');
$sql->adOnde('pratica_indicador_formula_simples=0');
$sql->adOnde('pratica_indicador_campo_projeto=0');
$sql->adOnde('pratica_indicador_campo_tarefa=0');
$sql->adOnde('pratica_indicador_campo_acao=0');
$sql->adOnde('avaliacao_indicador_lista_avaliacao='.(int)$avaliacao_id);
$sql->adOrdem('pratica_indicador_nome');
$lista=array(''=>'selecione um indicador')+$sql->listaVetorChave('avaliacao_indicador_lista_id','pratica_indicador_nome');
$sql->limpar();

echo '<tr><td colspan=20><div id="combo_indicadores">'.selecionaVetor($lista, 'avaliacao_indicador_lista_id', 'style="width:380px;" size="1" class="texto" onchange="env.submit();"',$avaliacao_indicador_lista_id).'</div></td></tr>';








if ($pratica_indicador_id){

	if ($checklist_id){
		$sql->adTabela('pratica_indicador');
		$sql->esqUnir('checklist_dados','checklist_dados','pratica_indicador_valor_indicador=pratica_indicador_id');
		$sql->adCampo('checklist_dados_id, checklist_dados_nome_usuario, checklist_dados_funcao_usuario, pratica_indicador_valor_data, checklist_dados_campos, checklist_dados_obs');
		$sql->adOnde('pratica_indicador_id='.$pratica_indicador_id);
		$sql->adOrdem('pratica_indicador_valor_data DESC');
		$sql->setLimite(1);
		$checklist=$sql->linha();
		$sql->limpar();

		if ($checklist['checklist_dados_id']){
			echo '<input type="hidden" name="checklist_dados_id" value="'.$checklist['checklist_dados_id'].'" />';
			$checklist_lista=unserialize($checklist['checklist_dados_campos']);
			if (count($checklist_lista)){
				echo '<tr><td colspan=20>'.botao('salvar', ($dialogo ? '' : 'Salvar'), ($dialogo ? '' : 'Salvar os dados.'),'','enviarDados()').'</td></tr>';
				$sql->adTabela('checklist_campo');
				$sql->esqUnir('checklist', 'checklist', 'checklist.checklist_modelo=checklist_campo.checklist_modelo_id');
				$sql->adCampo('checklist_campo.*');
				$sql->adOnde('checklist.checklist_id = '.(int)$checklist_lista[0]['checklist_lista_checklist_id']);
				$sql->adOrdem('checklist_campo_posicao ASC');
				$campos = $sql->Lista();
				$sql->limpar();
				echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0 class="tbl1" align=center width="800">';		
				
				$colunas=2;
				$cabecalho='<tr><th>'.dica('Proposição','O ítem a ser verificado, sendo que a questão deverá estar formulada de uma forma que a resposta esperada seja um SIM.').'Proposição'.dicaF().'</th>';
				foreach($campos as $campo) {
					$cabecalho.='<th>'.dica($campo['checklist_campo_nome'],$campo['checklist_campo_texto']).$campo['checklist_campo_nome'].dicaF().'</th>';
					$colunas++;
					}
				$cabecalho.='<th>'.dica('Evidência/Justificativa','Neste campo poderá constar informações pertinentes que justifiquem a opção marcada.').'Evidência/Justificativa'.dicaF().'</th></tr>';
				
								
				
				
				
				
				$qnt=0;
				foreach($checklist_lista as $linha) {
					
					
					if (!$qnt++ && (!isset($linha['checklist_lista_legenda']) || (isset($linha['checklist_lista_legenda']) && !$linha['checklist_lista_legenda']))) echo $cabecalho;
						
					if (!isset($linha['checklist_lista_na'])) $linha['checklist_lista_na']=0;
					
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
				echo '<tr><td colspan=20><table><tr><td align="right" nowrap="nowrap" width="100">'.dica('Observação', 'Observação sobre esta inserção.').'Observação:'.dicaF().'</td><td colspan=2 align="left"><textarea style="width:400px;" rows="3" name="avaliacao_indicador_lista_observacao" id="avaliacao_indicador_lista_observacao">'.($checklist['checklist_dados_obs']).'</textarea></td></tr></table></td></tr>';
				echo '<tr><td colspan=20>'.botao('salvar', ($dialogo ? '' : 'Salvar'), ($dialogo ? '' : 'Salvar os dados.'),'','enviarDados()').'</td></tr>';
				}
			else echo '<tr><td align="center" colspan=20><h1>Este checklist não tem nenhuma pergunta cadastrada!</h1></td></tr>';
			}
		}
	else{
		//tipo valor simples
		$sql->adTabela('pratica_indicador');
		$sql->esqUnir('pratica_indicador_valor','pratica_indicador_valor','pratica_indicador_valor_indicador=pratica_indicador_id');
		$sql->adCampo('pratica_indicador_valor_id, pratica_indicador_valor_responsavel, pratica_indicador_valor_valor, pratica_indicador_valor_obs');
		$sql->adOnde('pratica_indicador_id='.$pratica_indicador_id);
		$sql->adOrdem('pratica_indicador_valor_data DESC');
		$sql->setLimite(1);
		$valor=$sql->linha();
		$sql->limpar();
		
		if ($valor['pratica_indicador_valor_id']){
			
			echo '<input type="hidden" name="avaliacao_indicador_lista_pratica_indicador_valor_id" value="'.$valor['pratica_indicador_valor_id'].'" />';
			
			echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor', 'O valor aferido para este indicador.').'Valor:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="avaliacao_indicador_lista_valor" onkeypress="return entradaNumerica(event, this, true, true);" value="'.($valor['pratica_indicador_valor_valor'] ? number_format($valor['pratica_indicador_valor_valor'], 2, ',', '.') : '').'" class="texto" />
			<a href="javascript:void(0);" onclick="env.avaliacao_indicador_lista_valor.value=\'\';">'.imagem('icones/limpar_p.gif',($dialogo ? '' : 'Limpar Valor'), ($dialogo ? '' : 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar o valor do indicador.')).'</a>
			
			</td></tr>';
			echo '<tr><td align="right" nowrap="nowrap">'.dica('Observação', 'Observação sobre esta inserção.').'Observação:'.dicaF().'</td><td colspan=2 align="left"><textarea style="width:400px;" rows="3" name="avaliacao_indicador_lista_observacao" id="avaliacao_indicador_lista_observacao">'.($valor['pratica_indicador_valor_obs']).'</textarea></td></tr>';
			echo '<tr><td colspan=20>'.botao('salvar', ($dialogo ? '' : 'Salvar'), ($dialogo ? '' : 'Salvar os dados.'),'','enviarDados()').'</td></tr>';
			}
		else 	echo '<tr><td align="center" colspan=20><h1>Este indicador não tem nenhum valor inserido!</h1></td></tr>';
		}
	
	}




echo '</table>';
if (!$dialogo)echo estiloFundoCaixa();
echo '</form>';

?>
<script language="javascript">
	
function mudar_avaliacao(){
	xajax_mudar_avaliacao(document.getElementById('avaliacao_id').value);
	}	
	
function enviarDados() {
	var f = document.env;
		f.salvar.value=1;
		f.submit();
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
	
	
</script>