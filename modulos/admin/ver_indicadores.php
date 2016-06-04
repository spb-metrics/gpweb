<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $tab, $perms, $cia_id, $usuario_id, $dialogo, $ano, $estilo_interface;
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$ordenar = getParam($_REQUEST, 'ordenar_pratica_indicador', 'pratica_indicador_id');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql = new BDConsulta();

$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
$sql->esqUnir('pratica_indicador_usuarios', 'pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
if ($usuario_id) $sql->adOnde('pratica_indicador_responsavel ='.(int)$usuario_id.' OR pratica_indicador_usuarios.usuario_id ='.(int)$usuario_id);
elseif ($cia_id) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_nome, pratica_indicador_requisito_descricao, pratica_indicador_cor, pratica_indicador_sentido, pratica_indicador_responsavel, pratica_indicador_acesso, pratica_indicador_composicao, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_checklist, pratica_indicador_campo_projeto, pratica_indicador_parametro_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao, pratica_indicador_parametro_tarefa, pratica_indicador_parametro_acao');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$indicadores=$sql->Lista();
$sql->limpar();



$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$pagina = getParam($_REQUEST, 'pagina', 1);

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$xpg_tamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_indicadores']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$xpg_totalregistros = ($indicadores ? count($indicadores) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'indicador', 'indicadores','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$impressao) echo '<th nowrap="nowrap">&nbsp;</th><th nowrap="nowrap">&nbsp;</th>';
echo '<th  width=16"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar_pratica_indicador=pratica_indicador_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_indicador_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor do Indicador', 'Neste campo fica a cor de identifica��o do indicador.').'Cor'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar_pratica_indicador=pratica_indicador_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_indicador_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome do Indicador', 'Neste campo fica o nome para identifica��o do indicador.').'Nome'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar_pratica_indicador=pratica_indicador_requisito_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_indicador_requisito_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descri��o', 'Detalhes sobre do que se trata o indicador.').'<b>Descri��o</b>'.dicaF().'</a></th>';
echo '<th nowrap="nowrap">'.dica('Tend�ncia', 'Tend�ncia apresentada pelos �ltimos 3 valores registrados no indicador.').'<b>Tend�ncia</b>'.dicaF().'</th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $indicadores[$i];
	$qnt++;
	$editar=permiteEditarIndicador($linha['pratica_indicador_acesso'], $linha['pratica_indicador_id']);
	echo '<tr>';
	if (!$impressao && $editar) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar Indicador', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar este indicador.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_editar&pratica_indicador_id='.$linha['pratica_indicador_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	if (!$impressao && $editar) echo '<td nowrap="nowrap" width="16">'.($editar  && !$linha['pratica_indicador_formula'] && !$linha['pratica_indicador_formula_simples'] && !$linha['pratica_indicador_composicao'] && !$linha['pratica_indicador_campo_projeto'] && !$linha['pratica_indicador_campo_tarefa'] && !$linha['pratica_indicador_campo_acao'] ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a='.($linha['pratica_indicador_checklist'] ? 'checklist_editar_valor' : 'indicador_editar_valor').'&pratica_indicador_id='.$linha['pratica_indicador_id'].'\');">'.imagem('icones/adicionar.png','Inserir '.($linha['pratica_indicador_checklist'] ? 'Checklist' : 'Valor'),'Clique neste �cone '.imagem('icones/adicionar.png').' para inserir um novo '.($linha['pratica_indicador_checklist'] ? 'checklist' : 'valor').'.').'</a>' : '&nbsp;').'</td>';
	echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['pratica_indicador_cor'].'"><font color="'.melhorCor($linha['pratica_indicador_cor']).'">&nbsp;&nbsp;</font></td>';
	echo '<td>'.link_indicador($linha['pratica_indicador_id']).'</td>';
	echo '<td>'.($linha['pratica_indicador_requisito_descricao'] ? $linha['pratica_indicador_requisito_descricao'] : '&nbsp;').'</td>';
	echo '<td>'.tendencia($linha['pratica_indicador_id'] , $linha['pratica_indicador_sentido']).'</td>';
	echo '</tr>';

	}
if (!count($indicadores)) echo '<tr><td colspan=20><p>Nenhum indicador encontrado.</p></td></tr>';
echo '</table>';


function tendencia($entrada, $maior_melhor=0){
	$sql = new BDConsulta();
	$sql->adTabela('pratica_indicador_valor');
	$sql->adCampo('pratica_indicador_valor_valor as valor');
	$sql->adOnde('pratica_indicador_valor_indicador = '.$entrada);
	$sql->adOrdem('pratica_indicador_valor_data DESC LIMIT 3');
	$valores = $sql->Lista();	
	$sql->limpar();
	$tendencia='';
	if (!isset($valores[2]['valor']))	$tendencia='sem valores suficientes';
	elseif(($valores[0]['valor'] > $valores[1]['valor']) && ($valores[1]['valor'] > $valores[2]['valor'])) $tendencia=($maior_melhor ? '<font color="green">positiva</font>' : '<font color="red">negativa</font>');
	elseif(($valores[0]['valor'] < $valores[1]['valor']) && ($valores[1]['valor'] < $valores[2]['valor'])) $tendencia=($maior_melhor ? '<font color="red">negativa</font>' : '<font color="green">positiva</font>');
	else $tendencia='sem tend�ncia';	
	return $tendencia;
	}







?>
<script type="text/JavaScript">
function iluminar_tds(linha,alto,id){
	if(document.getElementsByTagName){
		var tcs=linha.getElementsByTagName('td');
		var nome_celula='';
		if(!id)check=false;
		else{
			var f=eval('document.frm');
			var check=eval('f.selecao_projeto_'+id+'.checked')
			}
		for(var j=0,j_cmp=tcs.length;j<j_cmp;j+=1){
			nome_celula=eval('tcs['+j+'].id');
			if(!(nome_celula.indexOf('ignore_td_')>=0)){
				if(alto==3) tcs[j].style.background='#FFFFCC';
				else if(alto==2||check)
				tcs[j].style.background='#FFCCCC';
				else if(alto==1) tcs[j].style.background='#FFFFCC';
				else tcs[j].style.background='#FFFFFF';
				}
			}
		}
	}

var estah_marcado;

function selecionar_projeto(id){
	var f=eval('document.frm');
	var boxObj=eval('f.elements["selecao_projeto_'+id+'"]');
	if(boxObj.checked){
		var linha=document.getElementById('projeto_'+id);
		boxObj.checked=false;
		iluminar_tds(linha,2,id);
		}
	else if(!boxObj.checked){
		var linha=document.getElementById('projeto_'+id);
		boxObj.checked=true;
		iluminar_tds(linha,3,id);
		}
	}


var nomeTab="<?php echo $caixaTab->tabs[$tab][1] ?>";	
	
</script>
