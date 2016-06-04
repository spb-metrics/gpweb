<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
$impressao = getParam($_REQUEST, 'impressao', null);
$pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', null);
$checklist_dados_id = getParam($_REQUEST, 'checklist_dados_id', null);
$sql = new BDConsulta;

$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_checklist');
$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
$checklist_id=$sql->resultado(); 
$sql->limpar();


$sql->adTabela('checklist');
$sql->adCampo('checklist.*');
$sql->adOnde('checklist_id='.(int)$checklist_id);
$checklist=$sql->Linha();
$sql->limpar();


if (!permiteAcessarChecklist($checklist['checklist_acesso'],$checklist_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$impressao) echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" '.(!$impressao ? 'class="std"' : '').'>';

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$checklist['checklist_cor'].'" colspan="2"><font color="'.melhorCor($checklist['checklist_cor']).'"><b>'.$checklist['checklist_nome'].'<b></font>'.(!$impressao ? '<a href="javascript: void(0);" onclick ="url_passar(0, \'m='.$m.'&a='.$a.'&dialogo=1&impressao=1&checklist_dados_id='.$checklist_dados_id.'&pratica_indicador_id='.$pratica_indicador_id.'\');">'.imagem('impressora_p.gif','Imprir', 'Clique neste �cone '.imagem('impressora_p.gif').' para imprimir este resulrado do checklist.').'</a>'.dicaF() : '').'</td></tr>';

if ($checklist['checklist_descricao']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Descri��o', 'Descri��o do checklist.').'Descri��o:'.dicaF().'</td><td class="realce">'.$checklist['checklist_descricao'].'</td></tr>';


//checar se tem valor
$sql->adTabela('checklist_dados');
$sql->adCampo('checklist_dados_campos, pratica_indicador_valor_data, checklist_dados_obs, checklist_dados_responsavel');
if ($checklist_dados_id) $sql->adOnde('checklist_dados_id = '.(int)$checklist_dados_id);
else {
	$sql->adOnde('pratica_indicador_valor_indicador = '.(int)$pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_valor_data DESC');
	}
$campos_salvos=$sql->linha(); 
$sql->limpar();

if ($campos_salvos['pratica_indicador_valor_data']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Data', 'A data de preenchimento do checklist.').'Data:'.dicaF().'</td><td class="realce" width="100%">'.retorna_data($campos_salvos['pratica_indicador_valor_data'], false).'</td></tr>';		
if ($campos_salvos['checklist_dados_obs']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Descri��o', 'Descri��o do checklist.').'Observa��o:'.dicaF().'</td><td class="realce">'.$campos_salvos['checklist_dados_obs'].'</td></tr>';
if ($campos_salvos['checklist_dados_responsavel']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Respons�vel', ucfirst($config['usuario']).' respons�vel por marcar o checklist.').'Respons�vel:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($campos_salvos['checklist_dados_responsavel'], '','','esquerda').'</td></tr>';		



	

$sql->adTabela('checklist_campo');
$sql->esqUnir('checklist', 'checklist', 'checklist.checklist_modelo=checklist_campo.checklist_modelo_id');
$sql->adCampo('checklist_campo.*');
$sql->adOnde('checklist.checklist_id = '.(int)$checklist_id);
$sql->adOrdem('checklist_campo_posicao ASC');
$campos = $sql->Lista();
$sql->limpar();

$colunas=2;
$cabecalho='<tr><th>'.dica('Proposi��o','O �tem a ser verificado, sendo que a quest�o dever� estar formulada de uma forma que a resposta esperada seja um SIM.').'Proposi��o'.dicaF().'</th>';
foreach($campos as $campo) {
	$cabecalho.='<th>'.dica($campo['checklist_campo_nome'],$campo['checklist_campo_texto']).$campo['checklist_campo_nome'].dicaF().'</th>';
	$colunas++;
	}
$cabecalho.='<th>'.dica('Evid�ncia/Justificativa','Neste campo poder� constar informa��es pertinentes que justifiquem a op��o marcada.').'Evid�ncia/Justificativa'.dicaF().'</th></tr>';



echo '<tr><td colspan=20><table class="tbl1" align=center cellpadding=0 cellspacing=0>';


$checklist_lista=@unserialize($campos_salvos['checklist_dados_campos']);

$qnt=0;

if ($campos_salvos['checklist_dados_campos']){
	foreach((array)$checklist_lista as $linha) {
		
		
		
		if (!$qnt++ && (!isset($linha['checklist_lista_legenda']) || (isset($linha['checklist_lista_legenda']) && !$linha['checklist_lista_legenda']))) echo $cabecalho;
		
		if (isset($linha['checklist_lista_legenda']) && $linha['checklist_lista_legenda']) echo '<tr><td'.($linha['checklist_lista_legenda'] ? ' colspan='.$colunas : '').' ><br><b>'.$linha['checklist_lista_descricao'].'</b></td></tr>';
		else echo '<tr><td>'.$linha['checklist_lista_descricao'].'</td>';
		
		if ((!isset($linha['checklist_lista_legenda']) || (isset($linha['checklist_lista_legenda']) && !$linha['checklist_lista_legenda']))){
			foreach ($campos as $campo) echo '<td align=center>'.(isset($linha['checklist_lista_'.$campo['checklist_campo_campo']]) && $linha['checklist_lista_'.$campo['checklist_campo_campo']] ? 'X' : '&nbsp;').'</td>';
			echo '<td>'.($linha['checklist_lista_justificativa'] ? $linha['checklist_lista_justificativa'] : '&nbsp;').'</td>';
			}
		echo '</tr>';	
		
		if (isset($linha['checklist_lista_legenda']) && $linha['checklist_lista_legenda']) echo $cabecalho;
		}
	}
else{		
	$sql->adTabela('checklist_lista');
	$sql->adCampo('checklist_lista_descricao, checklist_lista_legenda');
	$sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_id);
	$sql->adOrdem('checklist_lista_ordem ASC');
	$checklist_lista = $sql->Lista();
	$sql->limpar();	
	$saida='';
	foreach ($campos as $campo) $saida.='<td>&nbsp;</td>';
		
	if (count($checklist_lista)){
		

		
		foreach($checklist_lista as $linha) {
			if (!$qnt++ && !$linha['checklist_lista_legenda']) echo $cabecalho;
			
			if ($linha['checklist_lista_legenda']) echo '<tr><td'.($linha['checklist_lista_legenda'] ? ' colspan='.$colunas : '').' ><br><b>'.$linha['checklist_lista_descricao'].'</b></td></tr>';
			else echo '<tr><td>'.$linha['checklist_lista_descricao'].'</td>'.$saida.'<td>&nbsp;</td></tr>';
			
			if ($linha['checklist_lista_legenda']) echo $cabecalho;
			}
		}
	}
echo '</table></td></tr>';		
echo '</table></td></tr></table>';
if (!$impressao) echo estiloFundoCaixa();
else echo '<script>self.print();</script>';

?>
<script language="javascript">

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>