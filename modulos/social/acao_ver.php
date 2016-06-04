<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

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


$botoesTitulo = new CBlocoTitulo('Detalhes da A��o Social', '../../../modulos/Social/imagens/acao.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=acao_lista', 'lista','','Lista de A��es Sociais','Clique neste bot�o para visualizar a lista de a��es sociais.');



if ($editar) $botoesTitulo->adicionaBotao('m=social&a=acao_editar&social_acao_id='.$social_acao_id, 'editar','','Editar esta A��o Social','Editar os detalhes desta a��o social.');
if ($editar) {
	$lista_atividades=array(
		''=>'',
		'm=social&a=acao_editar_itens&tipo=0&social_acao_id='.$social_acao_id => ucfirst($config['beneficiario']),
		'm=social&a=acao_editar_itens&tipo=1&social_acao_id='.$social_acao_id =>'Comit� Nacional',
		'm=social&a=acao_editar_itens&tipo=2&social_acao_id='.$social_acao_id =>'Coordena��es Regionais',
		'm=social&a=acao_editar_itens&tipo=3&social_acao_id='.$social_acao_id =>'Comit�s Municipais',
		'm=social&a=acao_editar_itens&tipo=4&social_acao_id='.$social_acao_id =>'Comiss�es Comunit�rias',
		'm=social&a=acao_editar_itens&tipo=5&social_acao_id='.$social_acao_id =>'Superintend�ncias'
		);
	$atividades='<tr><td nowrap="nowrap" align="right">'.dica('Atividades', 'Insira ou edite a lista de atividades.').'Atividades:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_atividades, 'lista_atividades', 'size="1" style="width:160px;" class="texto" onChange="ir_para(\'lista_atividades\');"') .'</td></tr>';
	$lista_problemas=array(
		''=>'',
		'm=social&a=acao_editar_problemas&tipo=0&social_acao_id='.$social_acao_id => ucfirst($config['beneficiario']),
		'm=social&a=acao_editar_problemas&tipo=1&social_acao_id='.$social_acao_id =>'Comit� Nacional',
		'm=social&a=acao_editar_problemas&tipo=2&social_acao_id='.$social_acao_id =>'Coordena��es Regionais',
		'm=social&a=acao_editar_problemas&tipo=3&social_acao_id='.$social_acao_id =>'Comit�s Municipais',
		'm=social&a=acao_editar_problemas&tipo=4&social_acao_id='.$social_acao_id =>'Comiss�es Comunit�rias',
		'm=social&a=acao_editar_problemas&tipo=5&social_acao_id='.$social_acao_id =>'Superintend�ncias'
		);
	$problemas='<tr><td nowrap="nowrap" align="right">'.dica('Problemas', 'Insira ou edite a lista de poss�veis problemas durante a execu��o da a��o social.').'Problemas:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_problemas, 'lista_problemas', 'size="1" style="width:160px;" class="texto" onChange="ir_para(\'lista_problemas\');"') .'</td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$atividades.$problemas.'</table>');
	$botoesTitulo->adicionaBotao('m=social&a=acao_editar_negativas&social_acao_id='.$social_acao_id, 'negativas','','Lista de Negativas','Editar a lista de negativas � concess�o desta a��o social para uma fam�lia.');	
	$botoesTitulo->adicionaBotao('m=social&a=acao_editar_parametros&social_acao_id='.$social_acao_id, 'par�metros','','Lista de Par�metros','Editar a lista de par�metros para que uma fam�lia seja uma potencial benefici�ria da a��o social.');	
	}
if ($editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir esta a��o social.');	
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

echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda da Demanda Inicial', 'Para os relat�rios, a legenda da demanda inicial.').'Demanda inicial:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_inicial.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total Programado', 'Para os relat�rios, a legenda do total programado para ser adiquirido.').'Total Programado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_adquirido.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total Final', 'Para os relat�rios, a legenda do total final.').'Total final:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_final.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total Instalado', 'Para os relat�rios, a legenda do total instalado.').'Total instalado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_instalado.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total � Instalar', 'Para os relat�rios, a legenda do total que falta instalar.').'Total � instalar:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_instalar.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do Produto/Servi�o Entregue', 'Para o termo de recebimento, qual a legenda para o produto/servi�o entregue.').'Produto/Servi�o Entregue:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_produto.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do �rg�o', 'Para o termo de recebimento, qual a legenda para o �rg�o respons�vel pela entrega.').'�rg�o respons�vel:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_orgao.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do Financiador', 'Para o termo de recebimento, qual a legenda para o �rg�o financiador da a��o social.').'Financiador:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_financiador.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do C�digo do Produto/Servi�o', 'Para o termo de recebimento, qual a legenda para o c�digo produto/servi�o entregue.').'C�digo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_codigo.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo da Declara��o', 'Para o termo de recebimento, qual o texto da declara��o de recebimento.').'Declara��o:'.dicaF().'</td><td  class="realce">'.$obj->social_acao_declaracao.'</td></tr>';


if ($obj->social_acao_descricao) echo '<tr><td align="right" >'.dica('Descri��o', 'Descri��o da a��o social.').'Descri��o:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_acao_descricao.'</td></tr>';

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
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estar�o executando este programa social.').'Quem:'.dicaF().'</td><td width="100%" colspan="2" class="realce"><table cellspacing=0 cellpadding=0><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';

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
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']), 'Qual '.strtolower($config['departamento']).' est� relacionad'.$config['genero_dept'].' � este programa social.').ucfirst($config['departamento']).':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

if ($obj->social_acao_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Respons�vel pelo Social', ucfirst($config['usuario']).' respons�vel por gerenciar a a��o social.').'Respons�vel:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->social_acao_responsavel, '','','esquerda').'</td></tr>';		

if ($obj->social_acao_logo) echo '<tr><td align="right" valign="middle">'.dica('Logotipo da A��o Social', 'Logotipo desta a��o social.').'Logotipo:'.dicaF().'</td><td align="left"><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL.'/modulos/social').'/arquivos/acoes_logo/'.$obj->social_acao_logo.'" alt="" border=0 /></td></tr>';





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
if ($saida) echo '<tr><td colspan=20><table><tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Par�metros para Concess�o','Lista de par�metros que necessitam ser atendidos para que uma fam�lia seja uma poss�vel benefici�ria da a��o social.').'&nbsp;<b>Lista de Par�metros para Concess�o</b>&nbsp</legend><table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>&nbsp;'.dica('Campo','Qual o campo da tabela das fam�lias em que ser� feito teste l�gico').'Campo'.dicaF().'&nbsp;</th><th>&nbsp;'.dica('Situa��o','Qual dever� ser a situa��o do campo para que a fam�lia possa receber o benef�cio da a��o social').'Situa��o'.dicaF().'&nbsp;</th></tr>'.$saida.'</table></td></tr></fieldset></td></tr></table></td></tr>';










	
for($i=0; $i<=4 ; $i++){
	
	if ($i==1) $titulo='no Comit� Nacional';
	elseif ($i==2) $titulo='nas Coordena��es Regionais';
	elseif ($i==3) $titulo='nos Comit�s Municipais';
	elseif ($i==4) $titulo='nas Comiss�es Comunit�rias';
	else $titulo='nos Beneficiadoss';
		
	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_peso, social_acao_lista_descricao, social_acao_lista_final, social_acao_lista_parcial');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$social_acao_id);
	$sql->adOnde('social_acao_lista_tipo='.$i);
	$sql->adOrdem('social_acao_lista_ordem ASC');
	$linhas_social_acao=$sql->Lista();
	$saida='';
	foreach ($linhas_social_acao as $linha_social_acao) $saida.='<tr><td align=right>'.number_format($linha_social_acao['social_acao_lista_peso'], 2, ',', '.').'</td><td style="margin-bottom:0cm; margin-top:0cm;">'.$linha_social_acao['social_acao_lista_descricao'].'</td><td>'.($linha_social_acao['social_acao_lista_parcial'] ? 'X' : '&nbsp;').'</td><td>'.($linha_social_acao['social_acao_lista_final'] ? 'X' : '&nbsp;').'</td></tr>';
	if ($saida) echo '<tr><td colspan=20><table><tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Atividades '.$titulo,'Lista de atividades que necessitam ser executadas.').'&nbsp;<b>Lista de Atividades '.$titulo.'</b>&nbsp</legend><table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>&nbsp;'.dica('Peso','O valor destas atividades em rela��o as outras.').'Peso'.dicaF().'&nbsp;</th><th>&nbsp;'.dica('Atividade','Qual o texto das atividades constante na a��o social.').'Atividade'.dicaF().'&nbsp;</th><th>'.dica('Parcial','Marque este campo caso esta atividade sinaliza a execu��o parcial da a��o social.').'P'.dicaF().'</th><th>'.dica('Completo','Marque este campo caso esta atividade sinaliza a execu��o total da a��o social.').'C'.dicaF().'</th></tr>'.$saida.'</table></td></tr></fieldset></td></tr></table></td></tr>';
	}
	
	
$sql->adTabela('social_acao_negacao');
$sql->adCampo('*');
$sql->adOnde('social_acao_negacao_acao_id='.(int)$social_acao_id);
$sql->adOrdem('social_acao_negacao_ordem ASC');
$linhas_social_acao=$sql->Lista();
$saida='';
foreach ($linhas_social_acao as $linha_social_acao) $saida.='<tr><td>'.$linha_social_acao['social_acao_negacao_justificativa'].'</td></tr>';
if ($saida) echo '<tr><td colspan=20><table><tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Justificaticas para Negar','Lista de justificativas para quando esta a��o social for negada a uma fam�lia.').'&nbsp;<b>Lista de Justificativas para Negar</b>&nbsp</legend><table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>&nbsp;'.dica('Justificativa','Qual o texto das justificativas constante na a��o social.').'Justificativa'.dicaF().'&nbsp;</th></tr>'.$saida.'</table></td></tr></fieldset></td></tr></table></td></tr>';

	
	
	
	
for($tipo=0; $tipo<=4 ; $tipo++){
	
	if ($tipo==1) $titulo='no Comit� Nacional';
	elseif ($tipo==2) $titulo='nas Coordena��es Regionais';
	elseif ($tipo==3) $titulo='nos Comit�s Municipais';
	elseif ($tipo==4) $titulo='nas Comiss�es Comunit�rias';
	else $titulo='nos Beneficiados';
		
	$sql->adTabela('social_acao_problema');
	$sql->adCampo('social_acao_problema_descricao');
	$sql->adOnde('social_acao_problema_acao_id='.(int)$social_acao_id);
	$sql->adOnde('social_acao_problema_tipo='.(int)$tipo);
	$sql->adOrdem('social_acao_problema_ordem ASC');
	$linhas_social_acao=$sql->Lista();
	$saida='';
	foreach ($linhas_social_acao as $linha_social_acao) $saida.='<tr><td>'.$linha_social_acao['social_acao_problema_descricao'].'</td></tr>';
	if ($saida) echo '<tr><td colspan=20><table><tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Poss�veis Problemas '.$titulo,'Lista de poss�veis problemas relacionados a esta a��o social '.strtolower($titulo).'.').'&nbsp;<b>Lista de Poss�veis Problemas '.$titulo.'</b>&nbsp</legend><table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>&nbsp;'.dica('Descri��o','Qual a descri��o do poss�vel problema.').'Descri��o'.dicaF().'&nbsp;</th></tr>'.$saida.'</table></td></tr></fieldset></td></tr></table></td></tr>';
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
$caixaTab->adicionar(BASE_DIR.'/modulos/social/ver_logs_acao', 'Registros das Ocorr�ncias',null,null,'Registros das Ocorr�ncias','Visualizar os registros das ocorr�ncias.<br><br>O registro � a forma padr�o dos participantes das a��es informarem sobre o andamento e avisarem sobre problemas.');
if ($editar) $caixaTab->adicionar(BASE_DIR.'/modulos/social/ver_log_acao_atualizar', 'Registrar',null,null,'Registrar','Inserir uma ocorr�ncia.');
$f = 'todos';
$ver_min = true;

?>
<script language="javascript">
	
function ir_para(campo){
	var endereco=document.getElementById(campo).value;
	if (endereco!='') url_passar(0, endereco);
	}


function excluir() {
	if (confirm('Tem certeza que deseja excluir esta a��o social?')) {
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