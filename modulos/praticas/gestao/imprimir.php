<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);


if (isset($_REQUEST['pg_id'])) $Aplic->setEstado('pg_id', getParam($_REQUEST, 'pg_id', null));
$pg_id = ($Aplic->getEstado('pg_id') !== null ? $Aplic->getEstado('pg_id') :  null);


$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;

$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);


$botoesTitulo = new CBlocoTitulo('Impress�o d'.$config['genero_relatorio_gestao'].' '.ucfirst($config['relatorio_gestao']), 'impressao.png', $m, $m.'.'.$a);

$botoesTitulo->mostrar();


$sql = new BDConsulta();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="index" />';
echo '<input type="hidden" name="u" value="" />';

echo estiloTopoCaixa();
echo '<table class="std" width="100%" border=0 cellpadding=0 cellspacing=0>';

$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();


$sql->adTabela('pratica_requisito');
$sql->esqUnir('praticas','praticas', 'praticas.pratica_id=pratica_requisito.pratica_id');
$sql->adCampo('DISTINCT ano');
if ($cia_id) $sql->adOnde('pratica_cia='.(int)$cia_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);
asort($anos);

if (isset($_REQUEST['IdxPraticaAno'])) $Aplic->setEstado('IdxPraticaAno', getParam($_REQUEST, 'IdxPraticaAno', null));
$ano = ($Aplic->getEstado('IdxPraticaAno') !== null && isset($anos[$Aplic->getEstado('IdxPraticaAno')]) ? $Aplic->getEstado('IdxPraticaAno') : $ultimo_ano);

echo '<tr><td colspan=2></td></tr>';
echo '<tr><td align="right" width="150">'.dica('Campos das '.ucfirst($config['praticas']), 'Selecione quais campos das '.$config['praticas'].' deseja imprimir.').'Campos das '.$config['praticas'].':'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="checkbox" name="pratica_descricao" value="1" CHECKED> '.dica('Descri��o','Exibir o campo descri��o quando da listagem das pr�ticas por '.$config['marcador'].'.').'Descri��o'.dicaF().'&nbsp;&nbsp;<input type="checkbox" name="pratica_5w2h" value="1" > '.dica('5W2H','Exibir os campos 5W2H quando da listagem das pr�ticas por '.$config['marcador'].'.').'5W2H'.dicaF().' &nbsp;&nbsp;<input type="checkbox" name="pratica_extra" value="1" > '.dica('Extras','Exibir os campos extras (desde quando, controle, aprendizado e melhorias) quando da listagem das pr�ticas por '.$config['marcador'].'.').'Extras'.dicaF().' &nbsp;&nbsp;<input type="checkbox" name="pratica_legenda" value="1" > '.dica('Legendas','Exibir as legendas dos campos (ex: Como, Quando, etc).').'Legendas'.dicaF().'</td></tr>';
echo '<tr><td colspan=2></td></tr>';
echo '<tr><td align="right" width="150">'.dica('Campos dos Indicadores', 'Selecione quais campos dos indicadores deseja imprimir.').'Campos dos indicadores:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="checkbox" name="indicador_descricao" value="1" CHECKED> '.dica('Descri��o','Exibir o campo descri��o quando da listagem dos indicadores por '.$config['marcador'].'.').'Descri��o'.dicaF().'&nbsp;&nbsp;<input type="checkbox" name="indicador_5w2h" value="1" > '.dica('5W2H','Exibir os campos 5W2H quando da listagem dos indicadores por '.$config['marcador'].'.').'5W2H'.dicaF().' &nbsp;&nbsp;<input type="checkbox" name="indicador_extra" value="1" > '.dica('Extras','Exibir os campos extras (desde quando, melhorias, referencial comparativo e meta de valor) quando da listagem dos indicadores por '.$config['marcador'].'.').'Extras'.dicaF().' &nbsp;&nbsp;<input type="checkbox" name="indicador_legenda" value="1" > '.dica('Legendas','Exibir as legendas dos campos (ex: Como, Quando, etc).').'Legendas'.dicaF().'</td></tr>';
echo '<tr><td colspan=2></td></tr>';

echo '<tr><td align="right" width="150">'.dica('Campos J� Mostrados', 'Selecione o que fazer se um indicador ou '.$config['pratica'].' j� tiver sido mostrado anteriormente.').'Dados repetidos:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="radio" name="mostrado" value="numero"> N�mero&nbsp;&nbsp;<input type="radio" name="mostrado" value="nome_numero" checked> Nome e N�mero</td></tr>';
echo '<tr><td colspan=2></td></tr>';

echo '<tr><td></td><td>'.botao('imprimir', 'Imprimir', 'Clique neste bot�o imprimir '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'.','','imprimir()').'</td></tr>';

echo '</table>';
echo '</form>';
echo estiloFundoCaixa();
?>

<script language="javascript">


function getValorRadio(radioObj) {
	if(!radioObj)	return "";
	var tamanhoRadio = radioObj.length;
	if(tamanhoRadio == undefined) {
		if(radioObj.checked) return radioObj.value;	
		else return "";
		}
	for(var i = 0; i < tamanhoRadio; i++) {
		if(radioObj[i].checked) return radioObj[i].value;
		}
	return "";
	}
	
	
function imprimir(){
	var pg_cia=frm_filtro.cia_id.value;
	var ano=frm_filtro.IdxPraticaAno.value;
	var pratica_modelo_id=frm_filtro.pratica_modelo_id.value;
	var pratica_descricao=(env.pratica_descricao.checked ? 1 : 0);
	var indicador_descricao=(env.pratica_descricao.checked ? 1 : 0);
	var pratica_5w2h=(env.pratica_5w2h.checked ? 1 : 0);
	var indicador_5w2h=(env.indicador_5w2h.checked ? 1 : 0);
	var pratica_extra=(env.pratica_extra.checked ? 1 : 0);
	var indicador_extra=(env.indicador_extra.checked ? 1 : 0);
	var pratica_legenda=(env.pratica_legenda.checked ? 1 : 0);
	var indicador_legenda=(env.indicador_legenda.checked ? 1 : 0);
	var mostrado=getValorRadio(document.forms['env'].elements['mostrado']);
	
	if (pratica_modelo_id>0) url_passar(0, 'm=praticas&u=gestao&a=imprimir_plano_gestao&dialogo=1&pg_ano='+ano+'&pg_cia='+pg_cia+'&pratica_modelo_id='+pratica_modelo_id+'&pratica_descricao='+pratica_descricao+'&indicador_descricao='+indicador_descricao+'&pratica_5w2h='+pratica_5w2h+'&indicador_5w2h='+indicador_5w2h+'&pratica_extra='+pratica_extra+'&indicador_extra='+indicador_extra+'&mostrado='+mostrado+'&pratica_legenda='+pratica_legenda+'&indicador_legenda='+indicador_legenda);
	else alert('N�o h� r�gua de pontua��o selecionada.');
	}
</script>