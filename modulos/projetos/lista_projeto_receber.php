<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $estilo_interface, $tab, $dialogo;
$sql = new BDConsulta;
$Aplic->carregarCKEditorJS();

if (getParam($_REQUEST, 'gravar', 0)){
	$projeto_observado_id=getParam($_REQUEST, 'projeto_observado_id', array());
	
	$Aplic->setMsg((count($projeto_observado_id)>1 ? $config['projetos'].' recebid'.$config['genero_projeto'].'s' : $config['projeto'].' recebid'.$config['genero_projeto']), UI_MSG_OK);
	
	$projeto_observado_id=implode(',', $projeto_observado_id);
	$sql->adTabela('projeto_observado');
	$sql->adAtualizar('usuario_aprovou',$Aplic->usuario_id);
	$sql->adAtualizar('aprovado',getParam($_REQUEST, 'aprovado',0));
	$sql->adAtualizar('data_aprovacao', date('Y-m-d H:i:s'));
	$sql->adAtualizar('obs_destinatario',getParam($_REQUEST, 'obs_destinatario',''));
	$sql->adOnde('projeto_observado_id IN (' .$projeto_observado_id.')');
	$sql->exec();
	$sql->Limpar();
	
	
	$Aplic->redirecionar('m=projetos&a=index');	
	}



$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$pagina = getParam($_REQUEST, 'pagina', 1);
$ordenar = getParam($_REQUEST, 'ordenar', 'data_envio');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('projeto_observado');
$sql->adCampo('projeto_observado.*');

if ($tab==0){
	$sql->adOnde('aprovado = 0');
	$sql->adOnde('cia_para ='.$Aplic->usuario_cia);
	$sql->adOnde('cia_de !='.$Aplic->usuario_cia);
	}
elseif ($tab==1){
	$sql->adOnde('aprovado != 0');
	$sql->adOnde('cia_para ='.$Aplic->usuario_cia);
	$sql->adOnde('cia_de !='.$Aplic->usuario_cia);
	}
elseif ($tab==2){
	$sql->adOnde('aprovado = 0');
	$sql->adOnde('cia_para !='.$Aplic->usuario_cia);
	$sql->adOnde('cia_de ='.$Aplic->usuario_cia);
	}
elseif ($tab==3){
	$sql->adOnde('aprovado != 0');
	$sql->adOnde('cia_para !='.$Aplic->usuario_cia);
	$sql->adOnde('cia_de ='.$Aplic->usuario_cia);
	}
$lista=$sql->Lista();
$sql->limpar();


echo '<form name="frm" id="frm" method="POST">';
echo '<input type="hidden" name="a" id="a" value="lista_projeto_receber" />';
echo '<input type="hidden" name="m" id="m" value="projetos" />';
echo '<input type="hidden" name="gravar" id="gravar" value="1" />';
echo '<input type="hidden" name="aprovado" id="aprovado" value="0" />';




$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$xpg_tamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_projetos']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$xpg_totalregistros = ($lista ? count($lista) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $config['projeto'], $config['projetos'],'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
//projeto
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=projeto_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Neste campo fica o nome d'.$config['genero_projeto'].'s '.$config['projetos']).'Nome'.dicaF().'</th>';
//om
if ($tab < 2) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=cia_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='cia_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['organizacao']).' Remetente', 'Clique para ordenar pelas '.$config['organizacao'].' remetentes d'.$config['genero_projeto'].'s '.$config['projetos']).$config['organizacao'].dicaF().'</th>';
else echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=cia_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='cia_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['organizacao']).' Remetente', 'Clique para ordenar pelas '.$config['organizacao'].' destinatárias d'.$config['genero_projeto'].'s '.$config['projetos']).$config['organizacao'].dicaF().'</th>';
//Remetente'
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=remetente&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='remetente' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['usuario']).' Remetente', 'Clique para ordenar pel'.$config['genero_usuario'].'s '.$config['usuarios'].' remetentes d'.$config['genero_projeto'].'s '.$config['projetos']).'Remetente'.dicaF().'</th>';
//destinatario
if ($tab ==1 || $tab ==3) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=usuario_aprovou&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='usuario_aprovou' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['usuario']).' Que Recebeu', 'Clique para ordenar pel'.$config['genero_usuario'].'s '.$config['usuarios'].' que receberam '.$config['genero_projeto'].'s '.$config['projetos']).'Recebeu'.dicaF().'</th>';

//data envio
echo '<th nowrap="nowrap" style="width:60px;"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=data_envio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='data_envio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data do Envio', 'Clique para ordenar pelas datas de envio  d'.$config['genero_projeto'].'s '.$config['projetos']).'Envio'.dicaF().'</th>';
//data recebimento
if ($tab ==1 || $tab == 3) echo '<th nowrap="nowrap" style="width:60px;"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=data_envio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='data_envio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data do Recebimento', 'Clique para ordenar pelas datas de recebimento d'.$config['genero_projeto'].'s '.$config['projetos']).'Receb.'.dicaF().'</th>';

//obs
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=obs_remetente&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='obs_remetente' ? imagem('icones/'.$seta[$ordem]) : '').dica('Observação no Envio', 'Clique para ordenar pelas observações no envio d'.$config['genero_projeto'].'s '.$config['projetos']).'Obs. envio'.dicaF().'</th>';
if ($tab==1 || $tab==3) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=obs_destinatario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='obs_destinatario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Observação no Recebimento', 'Clique para ordenar pelas observações no recebimento d'.$config['genero_projeto'].'s '.$config['projetos']).'Obs. receb.'.dicaF().'</th>';

if ($tab==0) echo '<th nowrap="nowrap" width="16">'.dica('Selecionar', 'Clique nas caixas para selecionar os '.$config['projeto'].' abaixo.').'&nbsp;'.dicaF().'</th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;

for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $lista[$i];
	$qnt++;
	$editar=true;
	echo '<tr>';
	echo '<td>'.link_projeto($linha['projeto_id']).'</td>';
	
	if ($tab < 2) echo '<td>'.link_cia($linha['cia_de']).'</td>';
	else echo '<td>'.link_cia($linha['cia_para']).'</td>';
	
	echo '<td>'.link_usuario($linha['remetente'],'','','esquerda').'</td>';
	if($tab==1 || $tab == 3) echo '<td>'.link_usuario($linha['usuario_aprovou'],'','','esquerda').'</td>';
	
	echo '<td>'.($linha['data_envio'] ? retorna_data($linha['data_envio'], false) : '&nbsp;').'</td>';
	if($tab==1 || $tab == 3) echo '<td>'.($linha['data_aprovacao'] ? retorna_data($linha['data_aprovacao'], false) : '&nbsp;').'</td>';
	
	echo '<td>'.($linha['obs_remetente'] ? $linha['obs_remetente'] : '&nbsp;').'</td>';
	if ($tab==1 || $tab==3)  echo '<td>'.($linha['obs_destinatario'] ? $linha['obs_destinatario'] : '&nbsp;').'</td>';
	
	
	if ($tab==0) echo '<td><input type="checkbox" name="projeto_observado_id[]" value="'.$linha['projeto_observado_id'].'"></td>';
	
	echo '</tr>';
	}
if (!count($lista)) echo '<tr><td colspan="20"><p>Nenhum'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].' encontrad'.$config['genero_projeto'].'.</p></td></tr></table>';
elseif ($tab==0){
	echo '</table><table width="100%" class="std2">';
	echo '<tr><td>'.botao('aceitar', 'Aceitar', 'Clique neste botão para aceitar '.$config['genero_projeto'].'s '.$config['projetos'].' recebidos marcados acima.','','if (verifica_selecao()){frm.aprovado.value=1; frm.submit();}').'</td><td align="right" nowrap="nowrap">'.dica('Observações', 'Texto para acompanhar o recebimeneto '.(count($lista)>1 ? 'd'.$config['genero_projeto'].'s '.$config['projetos'] : 'd'.$config['genero_projeto'].' '.$config['projeto'])).'Observação:'.dicaF().'</td><td width="100%" colspan="2"><textarea name="obs_destinatario"  data-gpweb-cmp="ckeditor" style="width:284px;" rows="2" class="textarea"></textarea></td><td>'.botao('recusar', 'Recusar', 'Clique neste botão para recusar '.$config['genero_projeto'].'s '.$config['projetos'].' recebidos marcados acima.','','if (verifica_selecao()){frm.aprovado.value=-1; frm.submit();}').'</td></tr></table>';
	}
else echo '</table>';	

echo '</form>';
?>


<script>
	
function verifica_selecao(){
	var j=0;
	for(i=0;i < document.getElementById('frm').elements.length;i++) {
		if (document.getElementById('frm').elements[i].checked) j++;
		}	
	if (j>0) return 1;
	else {
		alert ("Selecione ao menos um <?php echo $config['projeto']?>!"); 
		return 0;
		}
	} 




</script>	