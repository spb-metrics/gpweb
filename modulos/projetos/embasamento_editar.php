<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;

require_once BASE_DIR.'/modulos/projetos/embasamento.class.php';
require_once BASE_DIR.'/modulos/projetos/termo_abertura.class.php';
require_once $Aplic->getClasseSistema('CampoCustomizados');

$projeto_id =getParam($_REQUEST, 'projeto_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;


$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);

if (!$projeto_id) {
	$Aplic->setMsg('Não foi passado um ID de '.$config['projeto'].' ao tentar editar o embasamento.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeEditar && permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id))) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}


$obj = new CEmbasamento();
$obj->load($projeto_id);
$sql = new BDConsulta();



$sql = new BDConsulta();
$sql->adTabela('projeto_abertura');
$sql->adCampo('projeto_abertura_id');
$sql->adOnde('projeto_abertura_projeto = '.(int)$projeto_id);
$projeto_abertura_id=$sql->resultado();
$sql->limpar();

$abertura = new CTermoAbertura();
$abertura->load($projeto_abertura_id);

$projeto_viabilidade_acesso = getSisValor('NivelAcesso','','','sisvalor_id');


$ttl = ($obj->projeto_embasamento_responsavel ? 'Editar Embasamento' : 'Criar Embasamento');
$botoesTitulo = new CBlocoTitulo($ttl, 'anexo_projeto.png', $m, $m.'.'.$a);

$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
if ($obj->projeto_embasamento_responsavel) $botoesTitulo->adicionaBotaoExcluir('excluir', $projeto_id, '', 'Excluir Embasamento', 'Excluir este embasamento.');



$botoesTitulo->mostrar();

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_embasamento" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="projeto_embasamento_projeto" id="projeto_embasamento_projeto" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="projeto_embasamento_responsavel" id="projeto_embasamento_responsavel" value="'.$Aplic->usuario_id.'" />';
echo '<input type="hidden" name="projeto_embasamento_data" id="projeto_embasamento_data" value="'.date('Y-m-d H:i:s').'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';

echo '<input type="hidden" name="antigo" value="'.($obj->projeto_embasamento_responsavel ? 1 : 0).'" />';



echo estiloTopoCaixa();
echo '<table cellspacing="1" cellpadding="1" border=0 width="100%" class="std">';
echo '<tr><td><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','env.submit();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($obj->projeto_embasamento_responsavel ? 'edição' : 'criação').' do embasamento.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
echo '<tr><td><table style="width:800px;"><tr><td>'; 
echo '<tr><td align="right" nowrap="nowrap">'.dica('Justificativa', 'Descrever de forma clara a justificativa contendo um breve histórico e as motivações do projeto. .').'Justificativa:'.dicaF().'</td><td><textarea name="projeto_embasamento_justificativa" style="width:800px;" class="textarea">'.($obj->projeto_embasamento_justificativa ? $obj->projeto_embasamento_justificativa : $abertura->projeto_abertura_justificativa).'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Objetivo', 'Descrever qual o objetivo para a qual órgão está realizando o projeto, que pode ser: descrição concreta de que o projeto quer alcançar, uma posição estratégica a ser alcançada, um resultado a ser obtido, um produto a ser produzido ou um serviço a ser realizado. Os objetivos devem ser específicos, mensuráveis, realizáveis, realísticos, e baseados no tempo.>.').'Objetivo:'.dicaF().'</td><td><textarea name="projeto_embasamento_objetivo" style="width:800px;" class="textarea">'.($obj->projeto_embasamento_objetivo ? $obj->projeto_embasamento_objetivo : $abertura->projeto_abertura_objetivo).'</textarea></td></tr>';
echo '<tr><td align="right">'.dica('Declaração de Escopo', 'Descrever a declaração do escopo, que inclui as principais entregas, fornece uma base documentada para futuras decisões do projeto e para confirmar ou desenvolver um entendimento comum do escopo do projeto entre as partes interessadas.').'Declaração de Escopo:'.dicaF().'</td><td><textarea name="projeto_embasamento_escopo" style="width:800px;" class="textarea">'.($obj->projeto_embasamento_escopo ? $obj->projeto_embasamento_escopo : $abertura->projeto_abertura_escopo).'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Não escopo', 'Descrever de forma explícita o que está excluído do projeto, para evitar que uma parte interessada possa supor que um produto, serviço ou resultado específico é um produto do projeto.').'Não escopo:'.dicaF().'</td><td><textarea name="projeto_embasamento_nao_escopo" style="width:800px;" class="textarea">'.($obj->projeto_embasamento_nao_escopo ? $obj->projeto_embasamento_nao_escopo : $abertura->projeto_abertura_nao_escopo).'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Premissas', 'Descrever as premissas do projeto. As premissas são fatores que, para fins de planejamento, são considerados verdadeiros, reais ou certos sem prova ou demonstração. As premissas afetam todos os aspectos do planejamento do projeto e fazem parte da elaboração progressiva do projeto. Frequentemente, as equipes do projeto identificam, documentam e validam as premissas durante o processo de planejamento. Geralmente, as premissas envolvem um grau de risco.').'Premissas:'.dicaF().'</td><td><textarea name="projeto_embasamento_premissas" style="width:800px;" class="textarea">'.($obj->projeto_embasamento_premissas ? $obj->projeto_embasamento_premissas : $abertura->projeto_abertura_premissas).'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Restrições', 'Descrever as restrições do projeto. Uma restrição é uma limitação aplicável, interna ou externa ao projeto, que afetará o desempenho do projeto ou de um processo. Por exemplo, uma restrição do cronograma é qualquer limitação ou condição colocada em relação ao cronograma do projeto que afeta o momento em que uma atividade do cronograma pode ser agendada e geralmente está na forma de datas impostas fixas.').'Restrições:'.dicaF().'</td><td><textarea name="projeto_embasamento_restricoes" style="width:800px;" class="textarea">'.($obj->projeto_embasamento_restricoes ? $obj->projeto_embasamento_restricoes : $abertura->projeto_abertura_restricoes).'</textarea></td></tr>';
echo '<tr><td align="right" >'.dica('Custos Estimado e Fonte de Recurso', 'Descrever a estimativa de custo do projeto e a fonte de recurso.').'Custos estimado e fonte de recurso:'.dicaF().'</td><td><textarea name="projeto_embasamento_orcamento" style="width:800px;" class="textarea">'.($obj->projeto_embasamento_orcamento ? $obj->projeto_embasamento_orcamento : $abertura->projeto_abertura_custo).'</textarea></td></tr>';


$campos_customizados = new CampoCustomizados('projeto_embasamento', $projeto_id, 'editar');
$campos_customizados->imprimirHTML();


echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre o embasamento.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável pel'.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_responsavel">Responsável pel'.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para '.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_designados">Designados para '.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este embasamento.','','popEmailContatos()');
echo '</td><td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados deste embasamento.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td></tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';


echo '<tr><td><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','env.submit();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($obj->projeto_embasamento_responsavel ? 'edição' : 'criação').' do embasamento.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();


?>
<script language="javascript">
	
		
function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var objetivo_emails = document.getElementById('viabilidades_usuarios');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}




function excluir() {
	if (confirm( "Tem certeza que deseja excluir este embasamento?")) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_embasamento';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}

</script>

