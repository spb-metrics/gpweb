<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (isset($_REQUEST['tab'])) $Aplic->setEstado('DespachosModeloListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('DespachosModeloListaTab') !== null ? $Aplic->getEstado('DespachosModeloListaTab') : 1);



$onde = getParam($_REQUEST, 'onde', '');
$botoesTitulo = new CBlocoTitulo('Despachos de Documentos', 'despacho.gif');
$botoesTitulo->adicionaCelula('<form name="frmPesquisa" method="post"><input type="hidden" name="m" value="email" /><input type="hidden" name="a" value="lista_despacho_modelo" /><input type="hidden" name="'.$tab.'" value="arquivos" /><table><tr><td><input type="text" name="onde" class="texto" size="20" onChange="document.frmPesquisa.submit();" value="'.$onde.'" /></td><td>'.botao('pesquisar&nbsp;despachos', 'Pesquisar Despachos de Documentos', 'Pesquisar os despachos de documentos baseados no texto inserido à esquerda.','','frmPesquisa.submit();').'</td></tr></table></form>');
$botoesTitulo->mostrar();
$caixaTab = new CTabBox('m=email&a=lista_despacho_modelo', BASE_DIR.'/modulos/email/', $tab);
$caixaTab->adicionar('despachos_enviados_modelo', 'Recebidos sem resposta',null,null,'Recebidos Sem Resposta','Clique nesta aba para visualizar os despachos de documentos recebidos que anda não foram respondidos.');
$caixaTab->adicionar('despachos_enviados_modelo', 'Enviados sem resposta',null,null,'Enviados Sem Resposta','Clique nesta aba para visualizar os despachos enviados de documentos que anda não foram respondidos.');
$caixaTab->adicionar('despachos_enviados_modelo', 'Recebidos já respondidos',null,null,'Recebidos Já Respondidos','Clique nesta aba para visualizar os despachos recebidos de documentos que já foram respondidos.');
$caixaTab->adicionar('despachos_enviados_modelo', 'Enviados já respondidos',null,null,'Enviados Já Respondidos','Clique nesta aba para visualizar os despachos enviados de documentos que já foram respondidos.');
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);
?>