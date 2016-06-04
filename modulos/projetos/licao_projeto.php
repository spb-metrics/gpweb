<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));

$licao_categoria=getSisValor('LicaoCategoria');

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);

$editar=permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id);
$acessar=permiteAcessar($objProjeto->projeto_acesso,$objProjeto->projeto_id);

if (!$projeto_id) {
	$Aplic->setMsg('Não foi passado um ID de '.$config['projeto'].' ao tentar ver o encerramento.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeAcessar && $acessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}


$botoesTitulo = new CBlocoTitulo('Lições Aprendidas', 'licoes.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
if ($podeAdicionar && $editar)  $botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Nova Lição Aprendida', 'Criar um nova licao aprendida.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=licao_editar&projeto_id='.$projeto_id.'\');" ><span>nova lição</span></a>'.dicaF().'</td></tr><tr><td nowrap="nowrap"></td></tr></table>');
$botoesTitulo->mostrar();



echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';


$saida='';
$saida2='';
$sql = new BDConsulta;
$sql->adTabela('licao');
$sql->adCampo('licao.*');
$sql->adOnde('licao_projeto='.(int)$projeto_id);
$sql->adOrdem('licao_id ASC');
$licoes=$sql->Lista();


if ($licoes && count($licoes)) {
	$saida2.= '<tr>';
	$saida2.='<td style="background-color:#cccccc">Nome</td>';
	$saida2.='<td style="background-color:#cccccc">Ocorrência</td>';
	$saida2.='<td style="background-color:#cccccc">Tipo</td>';
	$saida2.='<td style="background-color:#cccccc">Categoria</td>';
	$saida2.='<td style="background-color:#cccccc">Consequências</td>';
	$saida2.='<td style="background-color:#cccccc">Ação Tomada</td>';
	$saida2.='<td style="background-color:#cccccc">Aprendizagem</td>';
	$saida2.='</tr>';
	}
foreach ($licoes as $licao) {
	$saida2.='<tr>';
	$saida2.='<td>'.($licao['licao_ocorrencia'] ? link_licao($licao['licao_id']) : '&nbsp;').'</td>';
	$saida2.='<td>'.($licao['licao_ocorrencia'] ? $licao['licao_ocorrencia'] : '&nbsp;').'</td>';
	$saida2.='<td>'.($licao['licao_tipo'] ? 'Positiva' : 'Netativa').'</td>';
	$saida2.='<td>'.(isset($licao_categoria[$licao['licao_categoria']]) ? $licao_categoria[$licao['licao_categoria']] : '&nbsp;').'</td>';
	$saida2.='<td>'.($licao['licao_consequencia'] ? $licao['licao_consequencia'] : '&nbsp;').'</td>';
	$saida2.='<td>'.($licao['licao_acao_tomada'] ? $licao['licao_acao_tomada'] : '&nbsp;').'</td>';
	$saida2.='<td>'.($licao['licao_aprendizado'] ? $licao['licao_aprendizado'] : '&nbsp;').'</td>';
	$saida2.='</tr>';
	}
if (count($licoes)) echo '<tr><td colspan=20><table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="100%">'.$saida2.'</table></td></tr>';
else echo '<tr><td colspan=20>Nenhuma lição aprendida encontrada</table></td></tr>';


		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
