<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (isset($_REQUEST['tab'])) $Aplic->setEstado('DespachosModeloListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('DespachosModeloListaTab') !== null ? $Aplic->getEstado('DespachosModeloListaTab') : 1);



$onde = getParam($_REQUEST, 'onde', '');
$botoesTitulo = new CBlocoTitulo('Despachos de Documentos', 'despacho.gif');
$botoesTitulo->adicionaCelula('<form name="frmPesquisa" method="post"><input type="hidden" name="m" value="email" /><input type="hidden" name="a" value="lista_despacho_modelo" /><input type="hidden" name="'.$tab.'" value="arquivos" /><table><tr><td><input type="text" name="onde" class="texto" size="20" onChange="document.frmPesquisa.submit();" value="'.$onde.'" /></td><td>'.botao('pesquisar&nbsp;despachos', 'Pesquisar Despachos de Documentos', 'Pesquisar os despachos de documentos baseados no texto inserido � esquerda.','','frmPesquisa.submit();').'</td></tr></table></form>');
$botoesTitulo->mostrar();
$caixaTab = new CTabBox('m=email&a=lista_despacho_modelo', BASE_DIR.'/modulos/email/', $tab);
$caixaTab->adicionar('despachos_enviados_modelo', 'Recebidos sem resposta',null,null,'Recebidos Sem Resposta','Clique nesta aba para visualizar os despachos de documentos recebidos que anda n�o foram respondidos.');
$caixaTab->adicionar('despachos_enviados_modelo', 'Enviados sem resposta',null,null,'Enviados Sem Resposta','Clique nesta aba para visualizar os despachos enviados de documentos que anda n�o foram respondidos.');
$caixaTab->adicionar('despachos_enviados_modelo', 'Recebidos j� respondidos',null,null,'Recebidos J� Respondidos','Clique nesta aba para visualizar os despachos recebidos de documentos que j� foram respondidos.');
$caixaTab->adicionar('despachos_enviados_modelo', 'Enviados j� respondidos',null,null,'Enviados J� Respondidos','Clique nesta aba para visualizar os despachos enviados de documentos que j� foram respondidos.');
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);
?>