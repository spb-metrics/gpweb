<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/risco.class.php');
$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);
$acessar=permiteAcessar($objProjeto->projeto_acesso,$objProjeto->projeto_id);
$editar=permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id);
if (!$projeto_id) {
	$Aplic->setMsg('N�o foi passado um ID de '.$config['projeto'].' ao tentar ver o plano de risco.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($acessar && $podeAcessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}


$obj = new CRisco();
$obj->load($projeto_id);
$sql = new BDConsulta();




if (!$dialogo) $Aplic->salvarPosicao();


$msg = '';
if ($Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Plano de Gerenciamento de Risco d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de op��es de visualiza��o').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste bot�o para visualizar dest'.($config['genero_projeto']=='o' ? 'e' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$projeto_id."\");");
	if ($editar && $podeEditar){
		$km->Add("root","inserir",dica('Inserir','Menu de op��es').'Inserir'.dicaF(), "javascript: void(0);'");
		if (!$obj->projeto_risco_usuario) $km->Add("inserir","inserir_risco",dica('Inserir Plano de Gerenciamento de Risco','Inserir os detalhes do plano de gerenciamento de risco.').'Plano de Gerenciamento de Risco'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=risco_editar&projeto_id=".$projeto_id."\");");
		if ($obj->projeto_risco_usuario) $km->Add("inserir","inserir_evento",dica('Inserir Eventos de Riscos','Inserir rventos de riscos no plano de gerenciamento de risco.').'Inserir Eventos de Riscos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=risco_tipo&projeto_id=".$projeto_id."\");");
		}	
	if ($obj->projeto_risco_usuario){
		$km->Add("root","acao",dica('A��o','Menu de a��es.').'A��o'.dicaF(), "javascript: void(0);'");
		if ($editar && $podeEditar) $km->Add("acao","editar_risco",dica('Editar Plano de Gerenciamento de Risco','Editar os detalhes do plano de gerenciamento de risco.').'Editar Plano de Gerenciamento de Risco'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=risco_editar&projeto_id=".$projeto_id."\");");
		if ($podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este plano de gerenciamento de risco do sistema.').'Excluir Plano de Gerenciamento de Risco'.dicaF(), "javascript: void(0);' onclick='excluir()");
		$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para visualizar as op��es de relat�rios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
		$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes do Plano de Gerenciamento de Risco', 'Visualize os detalhes deste plano de gerenciamento de risco.').' Detalhes do Plano de Gerenciamento de Risco'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=risco_imprimir&dialogo=1&projeto_id=".$projeto_id."\");");
		}	
	echo $km->Render();
	echo '</td></tr></table>';
	}
else {	
	$botoesTitulo = new CBlocoTitulo('Plano de Gerenciamento de Risco d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	if ($editar && $podeEditar){
		$botoesTitulo->adicionaBotao('m=projetos&a=risco_editar&projeto_id='.$projeto_id, ($obj->projeto_risco_usuario ? 'editar' : 'inserir'),'',($obj->projeto_risco_usuario ? 'Editar' : 'Inserir').' Plano de Gerenciamento de Risco',($obj->projeto_risco_usuario ? 'Editar' : 'Inserir').' os detalhes do plano de gerenciamento de risco.');
		if ($obj->projeto_risco_usuario) {
			$botoesTitulo->adicionaBotao('m=projetos&a=risco_tipo&projeto_id='.$projeto_id, 'inserir eventos de riscos','','Inserir Eventos de Riscos','Inserir lista de eventos que levem a riscos.');	
			$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este gerenciamento de risco.');
			}
		}
	$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
	$botoesTitulo->adicionaCelula(dica('Imprimir o Plano de Gerenciamento de Risco', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir o plano de gerenciamento de risco.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=risco_imprimir&dialogo=1&projeto_id='.$projeto_id.'\', \'imprimir\',\'width=1100, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	
	echo estiloTopoCaixa();
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_risco_projeto" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '</form>';


echo '<table id="tblObjetivos" cellpadding=1 cellspacing=1 width="100%" class="std">';
if ($obj->projeto_risco_descricao) echo '<tr><td align="right">'.dica('Descri��o', 'Descri��o sobre o gerenciamento de risco').'Descri��o:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_risco_descricao.'</td></tr>';

if ($obj->projeto_risco_usuario) echo '<tr><td align="right" nowrap="nowrap">'.dica('Respons�vel', 'O respons�vel pelo gerenciamento de risco.').'Respons�vel:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->projeto_risco_usuario, '','','esquerda').'</td></tr>';



require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('projeto_risco', $obj->projeto_risco_projeto, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				
if ($obj->projeto_risco_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data', 'A data em que o gerenciamento de risco foi criado ou editado').'Data:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_risco_data).'</td></tr>';
if (!$obj->projeto_risco_usuario) echo '<tr><td colspan=20 class="realce">Ainda n�o h� dados cadastrados</td></tr>';




$probabilidade=array(1=>'Baixa', 2=>'M�dia', 3=>'Alta');
$impacto=array(1=>'Baixo', 2=>'M�dio', 3=>'Alto');
$saida='';
$sql = new BDConsulta;
$sql->adTabela('projeto_risco_tipo');
$sql->esqUnir('usuarios','usuarios','projeto_risco_tipo_usuario=usuario_id');
$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
$sql->adCampo('projeto_risco_tipo.*, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome');
$sql->adOnde('projeto_risco_tipo_projeto='.(int)$projeto_id);
$sql->adOrdem('projeto_risco_tipo_ordem ASC');
$tipos=$sql->Lista();

if ($tipos && count($tipos)) {
	$saida.= '<tr>';
	$saida.='<th>Descri��o</th>';
	$saida.='<th>Categoria</th>';
	$saida.='<th>Tipo</th>';
	$saida.='<th>Consequ�ncia</th>';
	$saida.='<th>Probabilidade</th>';
	$saida.='<th>Impacto</th>';
	$saida.='<th>Severidade</th>';
	$saida.='<th>A��o</th>';
	$saida.='<th>Gatilho</th>';
	$saida.='<th>Resposta ao Risco</th>';
	$saida.='<th>Respons�vel</th>';
	$saida.='<th>Status</th>';
	$saida.='</tr>';
	}
foreach ($tipos as $tipo) {
	$saida.='<tr>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_descricao'] ? $tipo['projeto_risco_tipo_descricao'] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_categoria'] ? $tipo['projeto_risco_tipo_categoria'] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_tipo'] ? $tipo['projeto_risco_tipo_tipo'] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_consequencia'] ? $tipo['projeto_risco_tipo_consequencia'] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_probabilidade'] ? $probabilidade[$tipo['projeto_risco_tipo_probabilidade']] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_impacto'] ? $impacto[$tipo['projeto_risco_tipo_impacto']] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_severidade'] ? $tipo['projeto_risco_tipo_severidade'] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_acao'] ? $tipo['projeto_risco_tipo_acao'] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_gatilho'] ? $tipo['projeto_risco_tipo_gatilho'] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_resposta'] ? $tipo['projeto_risco_tipo_resposta'] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_usuario'] ? $tipo['nome'] : '&nbsp;').'</td>';
	$saida.='<td>'.($tipo['projeto_risco_tipo_status'] ? $tipo['projeto_risco_tipo_status'] : '&nbsp;').'</td>';
	$saida.='</tr>';
	}
if (count($tipos)) echo '<tr><td colspan=20><table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="100%">'.$saida.'</table></td></tr>';





		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script language="javascript">
	
function excluir() {
	if (confirm('Tem certeza que deseja excluir este gerenciamento de risco')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_risco';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}
</script>