<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$botoesTitulo = new CBlocoTitulo('Controle dos Lembretes', 'todo_list.png', $m, "$m.$a");

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ParafazerControleTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ParafazerControleTab', 0);


$botoesTitulo->mostrar();

$caixaTab = new CTabBox('m=parafazer&a=controle', BASE_DIR.'/modulos/parafazer/', $tab);

$caixaTab->adicionar('controle_tabela', 'Enviados Pendentes',null,null,'Enviados Pendentes','Visualizar os lembretes enviados que ainda aguardam aceita��o ou recusa.');
$caixaTab->adicionar('controle_tabela', 'Enviados Recusadas',null,null,'Enviados Recusados','Visualizar os lembretes enviados que foram recusados.');
$caixaTab->adicionar('controle_tabela', 'Enviados Aceitas',null,null,'Enviados Aceitos','Visualizar os lembretes enviados que foram aceitos.');

$caixaTab->adicionar('controle_tabela', 'Recebidos Pendentes',null,null,'Recebidos Pendentes','Visualizar os lembretes recebidos que ainda aguardam aceita��o ou recusa.');
$caixaTab->adicionar('controle_tabela', 'Recebidos Recusadas',null,null,'Recebidos Recusados','Visualizar os lembretes recebidos que foram recusados');
$caixaTab->adicionar('controle_tabela', 'Recebidos Aceitas',null,null,'Recebidos Aceitos','Visualizar os lembretes recebidos que foram aceitos');

$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);






?>