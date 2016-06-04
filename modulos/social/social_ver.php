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

$social_id = intval(getParam($_REQUEST, 'social_id', 0));

$sql = new BDConsulta;

$obj = new CSocial;
$obj->load($social_id);

if (!permiteAcessarSocial($obj->social_acesso,$social_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$dialogo) $Aplic->salvarPosicao();

if (isset($_REQUEST['tab'])) $Aplic->setEstado('SocialVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('SocialVerTab') !== null ? $Aplic->getEstado('SocialVerTab') : 0;
$msg = '';

$editar=($Aplic->usuario_super_admin || ($Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_social') && permiteEditarSocial($obj->social_acesso,$social_id)));

$botoesTitulo = new CBlocoTitulo('Detalhes do Social', '../../../modulos/Social/imagens/social.gif', $m, $m.'.'.$a);
if ($editar)$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Novo Progama Social', 'Criar um novo programa programa social.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=social&a=social_editar\');" ><span>programa&nbsp;social</span></a>'.dicaF().'</td></tr></table>');
$botoesTitulo->adicionaBotao('m=social&a=index', 'lista','','Lista de Programas Sociais','Clique neste bot�o para visualizar a lista de programa social.');
if ($editar) {
	$botoesTitulo->adicionaBotao('m=social&a=social_editar&social_id='.$social_id, 'editar','','Editar este Programa Social','Editar os detalhes deste programa social.');
	$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este programa social do sistema.');
	}
//falta criar relat�rio de programa social
//$botoesTitulo->adicionaCelula(dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir o programa programa social.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=social&a=imprimir_pratica&dialogo=1&sem_cabecalho=1&tipo=1&social_id='.$social_id.'\', \'imprimir_pratica\',\'width=1200, height=600, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
$botoesTitulo->mostrar();



echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="social" />';
echo '<input type="hidden" name="a" value="social_ver" />';
echo '<input type="hidden" name="social_id" value="'.$social_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '</form>';

echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';




echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->social_cor.'" colspan="2"><font color="'.melhorCor($obj->social_cor).'"><b>'.$obj->social_nome.'<b></font></td></tr>';


$sql->adTabela('social_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=social_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('social_id = '.$social_id);
$participantes = $sql->Lista();
$sql->limpar();

$sql->adTabela('social_depts');
$sql->adCampo('dept_id');
$sql->adOnde('social_id = '.$social_id);
$departamentos = $sql->carregarColuna();
$sql->limpar();


if ($obj->social_descricao) echo '<tr><td colspan="2" align="center" >'.dica('Descri��o', 'Descri��o do programa programa social.').'<b>Descri��o</b>'.dicaF().'</td></tr><tr><td colspan="2" class="realce">'.$obj->social_descricao.'</td></tr>';




echo '<tr><td width="50%" valign="top"><table cellspacing="1" cellpadding="2" border=0 width="100%">';



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

echo '</table></td>';
echo '<td width="50%" rowspan="1" valign="top"><table cellspacing="1" cellpadding="2" border=0 width="100%">';


	

if ($obj->social_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Respons�vel pelo Social', ucfirst($config['usuario']).' respons�vel por gerenciar o programa programa social.').'Respons�vel:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->social_responsavel, '','','esquerda').'</td></tr>';		
		
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('social', $obj->social_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				
		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

$caixaTab = new CTabBox('m=social&a=social_ver&social_id='.$social_id, '', $tab);
$texto_consulta = '?m=social&a=social_ver&social_id='.$social_id;
$caixaTab->adicionar(BASE_DIR.'/modulos/social/ver_logs_social', 'Registros das Ocorr�ncias',null,null,'Registros das Ocorr�ncias','Visualizar os registros das ocorr�ncias.<br><br>O registro � a forma padr�o dos participantes das a��es informarem sobre o andamento e avisarem sobre problemas.');
if ($editar) $caixaTab->adicionar(BASE_DIR.'/modulos/social/ver_log_social_atualizar', 'Registrar',null,null,'Registrar','Inserir uma ocorr�ncia.');
//$caixaTab->adicionar(BASE_DIR.'/modulos/social/ver_projetos', ucfirst($config['projetos']),null,null, ucfirst($config['projetos']),'Lista de '.$config['projetos'].' relacionados com este programa social, atrav�s dos recursos.');
$f = 'todos';
$ver_min = true;
$caixaTab->mostrar('','','','',true);

echo estiloFundoCaixa('','', $tab);
?>
<script language="javascript">
function expandir_multipratica(id, tabelaNome) {
  var trs = document.getElementsByTagName('tr');
  for (var i=0, i_cmp=trs.length;i < i_cmp;i++) {
    var tr_nome = trs.item(i).id;
    if (tr_nome.indexOf(id) >= 0) {
     	var tr = document.getElementById(tr_nome);
     	tr.style.visibility = (tr.style.visibility == '' || tr.style.visibility == 'colapsar') ? 'visible' : 'colapsar';
     	var img_expandir = document.getElementById(id+'_expandir');
     	var img_colapsar = document.getElementById(id+'_colapsar');
     	img_colapsar.style.display = (tr.style.visibility == 'visible') ? 'inline' : 'none';
     	img_expandir.style.display = (tr.style.visibility == '' || tr.style.visibility == 'colapsar') ? 'inline' : 'none';
			}
		}
	}

function excluir() {
	if (confirm('Tem certeza que deseja excluir este programa social')) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fazer_sql_social';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>