<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (isset($_REQUEST['tab'])) $Aplic->setEstado('msg_tarefaListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('msg_tarefaListaTab') !== null ? $Aplic->getEstado('msg_tarefaListaTab') : 0);
$onde = getParam($_REQUEST, 'onde', '');
$botoesTitulo = new CBlocoTitulo(ucfirst($config['mensagens']).' do Tipo Atividade', 'task.png');


$botoesTitulo->adicionaCelula('<form name="frmPesquisa" method="post"><input type="hidden" name="m" value="email" /><input type="hidden" name="a" value="'.$a.'" /><input type="hidden" name="tab" value="'.$tab.'" /><table><tr><td nowrap="nowrap" align="right">'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" name="onde" class="texto" size="20" onChange="document.frmPesquisa.submit();" value="'.$onde.'" /></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&onde=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></form>');
$botoesTitulo->mostrar();
$caixaTab = new CTabBox('m=email&a=lista_msg_tarefa', BASE_DIR.'/modulos/email/', $tab);
$caixaTab->adicionar('msg_tarefa', 'Recebid'.$config['genero_mensagem'].'s em execução',null,null,'Recebid'.$config['genero_mensagem'].'s em Execução','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade recebid'.$config['genero_mensagem'].'s que ainda não foram marcad'.$config['genero_mensagem'].'s com 100%.');
$caixaTab->adicionar('msg_tarefa', 'Enviad'.$config['genero_mensagem'].'s em execução',null,null,'Enviad'.$config['genero_mensagem'].'s em Execução','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade enviad'.$config['genero_mensagem'].'s que ainda não foram marcad'.$config['genero_mensagem'].'s com 100%.');
$caixaTab->adicionar('msg_tarefa', 'Recebid'.$config['genero_mensagem'].'s encerrad'.$config['genero_mensagem'].'s',null,null,'Recebid'.$config['genero_mensagem'].'s Encerrad'.$config['genero_mensagem'].'s','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade recebid'.$config['genero_mensagem'].'s que estão marcad'.$config['genero_mensagem'].'s com 100%.');
$caixaTab->adicionar('msg_tarefa', 'Enviad'.$config['genero_mensagem'].'s encerrad'.$config['genero_mensagem'].'s',null,null,'Enviad'.$config['genero_mensagem'].'s Encerrad'.$config['genero_mensagem'].'s','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade enviad'.$config['genero_mensagem'].'s que foram marcad'.$config['genero_mensagem'].'s com 100%.');
$caixaTab->adicionar('msg_tarefa', 'Recebid'.$config['genero_mensagem'].'s Ignorad'.$config['genero_mensagem'].'s',null,null,'Recebid'.$config['genero_mensagem'].'s Ignorad'.$config['genero_mensagem'].'s','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade que foram ignorad'.$config['genero_mensagem'].'s.');
$caixaTab->adicionar('msg_tarefa', 'Enviad'.$config['genero_mensagem'].'s Ignorad'.$config['genero_mensagem'].'s',null,null,'Enviad'.$config['genero_mensagem'].'s Ignorad'.$config['genero_mensagem'].'s','Clique nesta aba para visualizar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade enviad'.$config['genero_mensagem'].'s que foram ignorad'.$config['genero_mensagem'].'s pelos destinatários.');
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);
?>