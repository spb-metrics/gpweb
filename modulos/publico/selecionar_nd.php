<?php
$nd_id = getParam($_REQUEST, 'nd_id', null);
$chamarVolta = getParam($_REQUEST, 'chamar_volta', null);

$sql = new BDConsulta;
$sql->adTabela('nd');
$sql->adCampo('nd_grupo, nd_subgrupo, nd_elemento_subelemento, nd_item_subitem');
$sql->adOnde('nd_id='.(int)$nd_id);
$linha=$sql->linha();
$sql->limpar();

$nd_grupo=$linha['nd_grupo'];
$nd_subgrupo=$linha['nd_subgrupo'];
$nd_elemento_subelemento=$linha['nd_elemento_subelemento'];
$nd_item_subitem=$linha['nd_item_subitem'];

$vetor_nd_grupo=array(''=>'')+getSisValor('CategoriaEconomica');
$GrupoND=array(''=>'')+getSisValor('GrupoND');
$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
if (substr($nd_item_subitem, 3, 2)!='00') $nd_passar=substr($nd_item_subitem, 0, 2).'.00';
else $nd_passar=null;
$vetor_nd=vetor_nd($nd_passar, null, null, 3, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento, false);

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="u" value="'.$u.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';

echo '<table cellspacing=0 cellpadding=0 class="std" width="100%">';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria Econômica', 'Escolha a categoria econômica deste item.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($vetor_nd_grupo, 'nd_grupo', 'class=texto size=1 style="width:395px;" onchange="env.nd_item_subitem.value=\'\'; mudar_nd();"', $nd_grupo).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'Escolha o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'nd_subgrupo', 'class=texto size=1 style="width:395px;"  onchange="env.nd_item_subitem.value=\'\'; mudar_nd();"', $nd_subgrupo).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplicação', 'Escolha a modalidade de aplicação deste item.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'nd_elemento_subelemento', 'class=texto size=1 style="width:395px;"  onchange="env.nd_item_subitem.value=\'\'; mudar_nd();"', $nd_elemento_subelemento).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Elemento de Despesa', 'Escolha o elemento de despesa (ED) deste item.').'Elemento de despesa:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($vetor_nd, 'nd_item_subitem', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"', $nd_passar).'</div></td></tr>';

echo '<tr><td colspan=20><div id="corpo"><table cellpadding=0 cellspacing=0 width="100%" class="tbl1"><tr><td>Nenhum dado</td></tr></table></div></td></tr>';

echo '</table>';
echo '</form>';

?>
<script language="javascript">

function retornar(nd_id, nome) {
	if(parent && parent.gpwebApp){
		parent.gpwebApp._popupCallback(nd_id, nome);
		return;
		}
	window.opener.<?php echo $chamarVolta?>(nd_id, nome);
	self.close();
	}


function mudar_nd(){
	xajax_mudar_nd_ajax(env.nd_item_subitem.value, 'nd_item_subitem', 'combo_nd','class=texto size=1 style="width:395px;" onchange="mudar_nd();"', 3, env.nd_grupo.value, env.nd_subgrupo.value, env.nd_elemento_subelemento.value);
	}

mudar_nd();
</script>


