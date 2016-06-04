<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/embasamento.class.php');
$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);
$podeAcessar=permiteAcessar($objProjeto->projeto_acesso,$objProjeto->projeto_id);

if (!$projeto_id) {
	$Aplic->setMsg('Não foi passado um ID de '.$config['projeto'].' ao tentar ver o embasamento.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeEditar && permiteAcessar($objProjeto->projeto_acesso,$objProjeto->projeto_id))) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}


$obj = new CEmbasamento();
$obj->load($projeto_id);
$sql = new BDConsulta();

$podeEditar=permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id);


if (!$dialogo) $Aplic->salvarPosicao();


$msg = '';
$botoesTitulo = new CBlocoTitulo('Embasamento d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);



if ($podeEditar) {
	$botoesTitulo->adicionaBotao('m=projetos&a=embasamento_editar&projeto_id='.$projeto_id, ($obj->projeto_embasamento_responsavel ? 'editar' : 'inserir'),'',($obj->projeto_embasamento_responsavel ? 'Editar' : 'Inserir').' Embasamento',($obj->projeto_embasamento_responsavel ? 'Editar' : 'Inserir').' os detalhes do embasamento.');
	if (!$obj->projeto_embasamento_responsavel) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este embasamento.');
	}
	
$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
$botoesTitulo->adicionaCelula(dica('Imprimir o Embasamento', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o embasamento.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=embasamento_imprimir&dialogo=1&projeto_id='.$projeto_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
$botoesTitulo->mostrar();



echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_embasamento_projeto" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '</form>';

echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';
if ($obj->projeto_embasamento_justificativa) echo '<tr><td align="right">'.dica('Justificativa', 'Descrever de forma clara a justificativa contendo um breve histórico e as motivações do projeto. .').'Justificativa:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_embasamento_justificativa.'</td></tr>';
if ($obj->projeto_embasamento_objetivo) echo '<tr><td align="right">'.dica('Objetivo', 'Descrever qual o objetivo para a qual órgão está realizando o projeto, que pode ser: descrição concreta de que o projeto quer alcançar, uma posição estratégica a ser alcançada, um resultado a ser obtido, um produto a ser produzido ou um serviço a ser realizado. Os objetivos devem ser específicos, mensuráveis, realizáveis, realísticos, e baseados no tempo.>.').'Objetivo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_embasamento_objetivo.'</td></tr>';
if ($obj->projeto_embasamento_escopo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Declaração de Escopo', 'Descrever a declaração do escopo, que inclui as principais entregas, fornece uma base documentada para futuras decisões do projeto e para confirmar ou desenvolver um entendimento comum do escopo do projeto entre as partes interessadas.').'Declaração de Escopo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_embasamento_escopo.'</td></tr>';
if ($obj->projeto_embasamento_nao_escopo) echo '<tr><td align="right">'.dica('Não escopo', 'Descrever de forma explícita o que está excluído do projeto, para evitar que uma parte interessada possa supor que um produto, serviço ou resultado específico é um produto do projeto.').'Não escopo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_embasamento_nao_escopo.'</td></tr>';
if ($obj->projeto_embasamento_premissas) echo '<tr><td align="right">'.dica('Premissas', 'Descrever as premissas do projeto. As premissas são fatores que, para fins de planejamento, são considerados verdadeiros, reais ou certos sem prova ou demonstração. As premissas afetam todos os aspectos do planejamento do projeto e fazem parte da elaboração progressiva do projeto. Frequentemente, as equipes do projeto identificam, documentam e validam as premissas durante o processo de planejamento. Geralmente, as premissas envolvem um grau de risco.').'Premissas:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_embasamento_premissas.'</td></tr>';
if ($obj->projeto_embasamento_restricoes) echo '<tr><td align="right">'.dica('Restrições', 'Descrever as restrições do projeto. Uma restrição é uma limitação aplicável, interna ou externa ao projeto, que afetará o desempenho do projeto ou de um processo. Por exemplo, uma restrição do cronograma é qualquer limitação ou condição colocada em relação ao cronograma do projeto que afeta o momento em que uma atividade do cronograma pode ser agendada e geralmente está na forma de datas impostas fixas.').'Restrições:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_embasamento_restricoes.'</td></tr>';
if ($obj->projeto_embasamento_orcamento) echo '<tr><td align="right">'.dica('Custos Estimado e Fonte de Recurso', 'Descrever a estimativa de custo do projeto e a fonte de recurso.').'Custos estimado e fonte de recurso:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_embasamento_orcamento.'</td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('projeto_embasamento', $obj->projeto_embasamento_projeto, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				
if ($obj->projeto_embasamento_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data', 'A data em que o embasamento foi criado ou editado').'Data:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_embasamento_data).'</td></tr>';

if (!$obj->projeto_embasamento_responsavel) echo '<tr><td colspan=20 class="realce">Ainda não há dados cadastrados</td></tr>';

		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script language="javascript">
	
function excluir() {
	if (confirm('Tem certeza que deseja excluir este embasamento?')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_embasamento';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}
</script>