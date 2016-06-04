<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!($podeAcessar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

include_once BASE_DIR.'/modulos/social/acao.class.php';
$social_acao_id = intval(getParam($_REQUEST, 'social_acao_id', 0));
$editar=($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_acao'));
$sql = new BDConsulta;

$obj = new CAcao;
$obj->load($social_acao_id);

if (!$dialogo) $Aplic->salvarPosicao();
if (isset($_REQUEST['tab'])) $Aplic->setEstado('AcaoVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('AcaoVerTab') !== null ? $Aplic->getEstado('AcaoVerTab') : 0;
$msg = '';


$botoesTitulo = new CBlocoTitulo('Detalhes da Ação Social', '../../../modulos/Social/imagens/acao.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=acao_lista', 'lista','','Lista de Ações Sociais','Clique neste botão para visualizar a lista de ações sociais.');



if ($editar) $botoesTitulo->adicionaBotao('m=social&a=acao_editar&social_acao_id='.$social_acao_id, 'editar','','Editar esta Ação Social','Editar os detalhes desta ação social.');
if ($editar) {
	$lista_atividades=array(
		''=>'',
		'm=social&a=acao_editar_itens&tipo=0&social_acao_id='.$social_acao_id => ucfirst($config['beneficiario']),
		'm=social&a=acao_editar_itens&tipo=1&social_acao_id='.$social_acao_id =>'Comitê Nacional',
		'm=social&a=acao_editar_itens&tipo=2&social_acao_id='.$social_acao_id =>'Coordenações Regionais',
		'm=social&a=acao_editar_itens&tipo=3&social_acao_id='.$social_acao_id =>'Comitês Municipais',
		'm=social&a=acao_editar_itens&tipo=4&social_acao_id='.$social_acao_id =>'Comissões Comunitárias',
		'm=social&a=acao_editar_itens&tipo=5&social_acao_id='.$social_acao_id =>'Superintendências'
		);
	$atividades='<tr><td nowrap="nowrap" align="right">'.dica('Atividades', 'Insira ou edite a lista de atividades.').'Atividades:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_atividades, 'lista_atividades', 'size="1" style="width:160px;" class="texto" onChange="ir_para(\'lista_atividades\');"') .'</td></tr>';
	$lista_problemas=array(
		''=>'',
		'm=social&a=acao_editar_problemas&tipo=0&social_acao_id='.$social_acao_id => ucfirst($config['beneficiario']),
		'm=social&a=acao_editar_problemas&tipo=1&social_acao_id='.$social_acao_id =>'Comitê Nacional',
		'm=social&a=acao_editar_problemas&tipo=2&social_acao_id='.$social_acao_id =>'Coordenações Regionais',
		'm=social&a=acao_editar_problemas&tipo=3&social_acao_id='.$social_acao_id =>'Comitês Municipais',
		'm=social&a=acao_editar_problemas&tipo=4&social_acao_id='.$social_acao_id =>'Comissões Comunitárias',
		'm=social&a=acao_editar_problemas&tipo=5&social_acao_id='.$social_acao_id =>'Superintendências'
		);
	$problemas='<tr><td nowrap="nowrap" align="right">'.dica('Problemas', 'Insira ou edite a lista de possíveis problemas durante a execução da ação social.').'Problemas:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_problemas, 'lista_problemas', 'size="1" style="width:160px;" class="texto" onChange="ir_para(\'lista_problemas\');"') .'</td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$atividades.$problemas.'</table>');
	$botoesTitulo->adicionaBotao('m=social&a=acao_editar_negativas&social_acao_id='.$social_acao_id, 'negativas','','Lista de Negativas','Editar a lista de negativas à concessão desta ação social para uma família.');	
	$botoesTitulo->adicionaBotao('m=social&a=acao_editar_parametros&social_acao_id='.$social_acao_id, 'parâmetros','','Lista de Parâmetros','Editar a lista de parâmetros para que uma família seja uma potencial beneficiária da ação social.');	
	}
if ($editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir esta ação social.');	
$botoesTitulo->mostrar();



echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="social" />';
echo '<input type="hidden" name="a" value="acao_ver" />';
echo '<input type="hidden" name="social_acao_id" value="'.$social_acao_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '</form>';

echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->social_acao_cor.'" colspan="2"><font color="'.melhorCor($obj->social_acao_cor).'"><b>'.$obj->social_acao_nome.'<b></font></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda da Demanda Inicial', 'Para os relatórios, a legenda da demanda inicial.').'Demanda inicial:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_inicial.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total Programado', 'Para os relatórios, a legenda do total programado para ser adiquirido.').'Total Programado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_adquirido.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total Final', 'Para os relatórios, a legenda do total final.').'Total final:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_final.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total Instalado', 'Para os relatórios, a legenda do total instalado.').'Total instalado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_instalado.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total à Instalar', 'Para os relatórios, a legenda do total que falta instalar.').'Total à instalar:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_instalar.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do Produto/Serviço Entregue', 'Para o termo de recebimento, qual a legenda para o produto/serviço entregue.').'Produto/Serviço Entregue:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_produto.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do Órgão', 'Para o termo de recebimento, qual a legenda para o órgão responsável pela entrega.').'Órgão responsável:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_orgao.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do Financiador', 'Para o termo de recebimento, qual a legenda para o órgão financiador da ação social.').'Financiador:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_financiador.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do Código do Produto/Serviço', 'Para o termo de recebimento, qual a legenda para o código produto/serviço entregue.').'Código:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_codigo.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo da Declaração', 'Para o termo de recebimento, qual o texto da declaração de recebimento.').'Declaração:'.dicaF().'</td><td  class="realce">'.$obj->social_acao_declaracao.'</td></tr>';


if ($obj->social_acao_descricao) echo '<tr><td align="right" >'.dica('Descrição', 'Descrição da ação social.').'Descrição:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_descricao.'</td></tr>';

$sql->adTabela('social_acao_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=social_acao_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('social_acao_id = '.(int)$social_acao_id);
$participantes = $sql->Lista();
$sql->limpar();

$sql->adTabela('social_acao_depts');
$sql->adCampo('dept_id');
$sql->adOnde('social_acao_id = '.(int)$social_acao_id);
$departamentos = $sql->carregarColuna();
$sql->limpar();

$saida_quem='';
if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda').($participantes[0]['contato_dept']? ' - '.link_secao($participantes[0]['contato_dept']) : '');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').($participantes[$i]['contato_dept']? ' - '.link_secao($participantes[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando este programa social.').'Quem:'.dicaF().'</td><td width="100%" colspan="2" class="realce"><table cellspacing=0 cellpadding=0><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';

$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']), 'Qual '.strtolower($config['departamento']).' está relacionad'.$config['genero_dept'].' à este programa social.').ucfirst($config['departamento']).':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

if ($obj->social_acao_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pelo Social', ucfirst($config['usuario']).' responsável por gerenciar a ação social.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->social_acao_responsavel, '','','esquerda').'</td></tr>';		

if ($obj->social_acao_logo) echo '<tr><td align="right" valign="middle">'.dica('Logotipo da Ação Social', 'Logotipo desta ação social.').'Logotipo:'.dicaF().'</td><td align="left"><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL.'/modulos/social').'/arquivos/acoes_logo/'.$obj->social_acao_logo.'" alt="" border=0 /></td></tr>';





$tipos_campos=getSisValor('FamiliaCampo');
$saida='';
$sql->adTabela('social_acao_conceder');
$sql->adCampo('*');
$sql->adOnde('social_acao_conceder_acao='.(int)$social_acao_id);
$linhas_social_acao=$sql->Lista();
foreach ($linhas_social_acao as $linha_social_acao) {
	$saida.='<tr><td>'.$tipos_campos[$linha_social_acao['social_acao_conceder_campo']].'</td>';
	$saida.='<td>'.$linha_social_acao['social_acao_conceder_situacao'].'</td></tr>';
	}
if ($saida) echo '<tr><td colspan=20><table><tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Parâmetros para Concessão','Lista de parâmetros que necessitam ser atendidos para que uma família seja uma possível beneficiária da ação social.').'&nbsp;<b>Lista de Parâmetros para Concessão</b>&nbsp</legend><table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>&nbsp;'.dica('Campo','Qual o campo da tabela das famílias em que será feito teste lógico').'Campo'.dicaF().'&nbsp;</th><th>&nbsp;'.dica('Situação','Qual deverá ser a situação do campo para que a família possa receber o benefício da ação social').'Situação'.dicaF().'&nbsp;</th></tr>'.$saida.'</table></td></tr></fieldset></td></tr></table></td></tr>';










	
for($i=0; $i<=4 ; $i++){
	
	if ($i==1) $titulo='no Comitê Nacional';
	elseif ($i==2) $titulo='nas Coordenações Regionais';
	elseif ($i==3) $titulo='nos Comitês Municipais';
	elseif ($i==4) $titulo='nas Comissões Comunitárias';
	else $titulo='nos Beneficiadoss';
		
	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_peso, social_acao_lista_descricao, social_acao_lista_final, social_acao_lista_parcial');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$social_acao_id);
	$sql->adOnde('social_acao_lista_tipo='.$i);
	$sql->adOrdem('social_acao_lista_ordem ASC');
	$linhas_social_acao=$sql->Lista();
	$saida='';
	foreach ($linhas_social_acao as $linha_social_acao) $saida.='<tr><td align=right>'.number_format($linha_social_acao['social_acao_lista_peso'], 2, ',', '.').'</td><td style="margin-bottom:0cm; margin-top:0cm;">'.$linha_social_acao['social_acao_lista_descricao'].'</td><td>'.($linha_social_acao['social_acao_lista_parcial'] ? 'X' : '&nbsp;').'</td><td>'.($linha_social_acao['social_acao_lista_final'] ? 'X' : '&nbsp;').'</td></tr>';
	if ($saida) echo '<tr><td colspan=20><table><tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Atividades '.$titulo,'Lista de atividades que necessitam ser executadas.').'&nbsp;<b>Lista de Atividades '.$titulo.'</b>&nbsp</legend><table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>&nbsp;'.dica('Peso','O valor destas atividades em relação as outras.').'Peso'.dicaF().'&nbsp;</th><th>&nbsp;'.dica('Atividade','Qual o texto das atividades constante na ação social.').'Atividade'.dicaF().'&nbsp;</th><th>'.dica('Parcial','Marque este campo caso esta atividade sinaliza a execução parcial da ação social.').'P'.dicaF().'</th><th>'.dica('Completo','Marque este campo caso esta atividade sinaliza a execução total da ação social.').'C'.dicaF().'</th></tr>'.$saida.'</table></td></tr></fieldset></td></tr></table></td></tr>';
	}
	
	
$sql->adTabela('social_acao_negacao');
$sql->adCampo('*');
$sql->adOnde('social_acao_negacao_acao_id='.(int)$social_acao_id);
$sql->adOrdem('social_acao_negacao_ordem ASC');
$linhas_social_acao=$sql->Lista();
$saida='';
foreach ($linhas_social_acao as $linha_social_acao) $saida.='<tr><td>'.$linha_social_acao['social_acao_negacao_justificativa'].'</td></tr>';
if ($saida) echo '<tr><td colspan=20><table><tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Justificaticas para Negar','Lista de justificativas para quando esta ação social for negada a uma família.').'&nbsp;<b>Lista de Justificativas para Negar</b>&nbsp</legend><table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>&nbsp;'.dica('Justificativa','Qual o texto das justificativas constante na ação social.').'Justificativa'.dicaF().'&nbsp;</th></tr>'.$saida.'</table></td></tr></fieldset></td></tr></table></td></tr>';

	
	
	
	
for($tipo=0; $tipo<=4 ; $tipo++){
	
	if ($tipo==1) $titulo='no Comitê Nacional';
	elseif ($tipo==2) $titulo='nas Coordenações Regionais';
	elseif ($tipo==3) $titulo='nos Comitês Municipais';
	elseif ($tipo==4) $titulo='nas Comissões Comunitárias';
	else $titulo='nos Beneficiados';
		
	$sql->adTabela('social_acao_problema');
	$sql->adCampo('social_acao_problema_descricao');
	$sql->adOnde('social_acao_problema_acao_id='.(int)$social_acao_id);
	$sql->adOnde('social_acao_problema_tipo='.(int)$tipo);
	$sql->adOrdem('social_acao_problema_ordem ASC');
	$linhas_social_acao=$sql->Lista();
	$saida='';
	foreach ($linhas_social_acao as $linha_social_acao) $saida.='<tr><td>'.$linha_social_acao['social_acao_problema_descricao'].'</td></tr>';
	if ($saida) echo '<tr><td colspan=20><table><tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Possíveis Problemas '.$titulo,'Lista de possíveis problemas relacionados a esta ação social '.strtolower($titulo).'.').'&nbsp;<b>Lista de Possíveis Problemas '.$titulo.'</b>&nbsp</legend><table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>&nbsp;'.dica('Descrição','Qual a descrição do possível problema.').'Descrição'.dicaF().'&nbsp;</th></tr>'.$saida.'</table></td></tr></fieldset></td></tr></table></td></tr>';
	}	
	

		
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('social_acao', $obj->social_acao_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				
		
echo '</table>';
echo estiloFundoCaixa();

$caixaTab = new CTabBox('m=social&a=acao_ver&social_acao_id='.$social_acao_id, '', $tab);
$texto_consulta = '?m=social&a=acao_ver&social_acao_id='.$social_acao_id;
$caixaTab->adicionar(BASE_DIR.'/modulos/social/ver_logs_acao', 'Registros das Ocorrências',null,null,'Registros das Ocorrências','Visualizar os registros das ocorrências.<br><br>O registro é a forma padrão dos participantes das ações informarem sobre o andamento e avisarem sobre problemas.');
if ($editar) $caixaTab->adicionar(BASE_DIR.'/modulos/social/ver_log_acao_atualizar', 'Registrar',null,null,'Registrar','Inserir uma ocorrência.');
$f = 'todos';
$ver_min = true;

?>
<script language="javascript">
	
function ir_para(campo){
	var endereco=document.getElementById(campo).value;
	if (endereco!='') url_passar(0, endereco);
	}


function excluir() {
	if (confirm('Tem certeza que deseja excluir esta ação social?')) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fazer_sql_acao';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>