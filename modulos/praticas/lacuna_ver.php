<?php 
/*
Copyright [2008] - Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$indicador_lacuna_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

$indicador_lacuna_id = intval(getParam($_REQUEST, 'indicador_lacuna_id', 0));

$msg = '';

if (isset($_REQUEST['tab'])) $Aplic->setEstado('LacunaVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('LacunaVerTab') !== null ? $Aplic->getEstado('LacunaVerTab') : 0;


if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$obj = new CLacuna();
$obj->load($indicador_lacuna_id);

$sql = new BDConsulta;
$sql->adTabela('indicador_lacuna_nos_marcadores');
$sql->adCampo('DISTINCT ano');
$sql->adOnde('indicador_lacuna_id='.(int)$indicador_lacuna_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();


$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);
asort($anos);

if (isset($_REQUEST['IdxIndicadorAno'])) $Aplic->setEstado('IdxIndicadorAno', getParam($_REQUEST, 'IdxIndicadorAno', null));
$ano = ($Aplic->getEstado('IdxIndicadorAno') !== null && isset($anos[$Aplic->getEstado('IdxIndicadorAno')]) ? $Aplic->getEstado('IdxIndicadorAno') : $ultimo_ano);


if (!($podeAcessar && permiteAcessarLacuna($obj->indicador_lacuna_acesso,$indicador_lacuna_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$dialogo) $Aplic->salvarPosicao();
$editar=($podeEditar&& permiteEditarLacuna($obj->indicador_lacuna_acesso,$indicador_lacuna_id));

$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="lacuna_ver" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '<input type="hidden" name="indicador_lacuna_id" id="indicador_lacuna_id" value="'.$indicador_lacuna_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';

if (!$dialogo && $Aplic->profissional){	
	$botoesTitulo = new CBlocoTitulo('Detalhes da Lacuna de Indicador', 'lacuna.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="mudar_pauta();" class="texto"', $pratica_modelo_id).'</td></tr><tr><td>'.dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados do indicador inseridos no ano selecionado.').'Ano:'.dicaF().selecionaVetor($anos, 'IdxLacunaAno', 'onchange="mudar_pauta()" class="texto"', $ano).'</td></tr></table>');

	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista_links",dica('Lista de Lacunas de Indicadores','Visualizar a lista de todos as lacunas de indicadores.').'Lista de Lacunas de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=lacuna_lista\");");

	if ($editar) {
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_lacuna",dica('Nova Lacuna de Indicador', 'Criar uma nova lacuna de indicador.').'Nova Lacuna de Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=lacuna_editar\");");
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar) {
		$km->Add("acao","acao_editar",dica('Editar Lacuna de Indicador','Editar os detalhes desta lacuna de indicador.').'Editar Lacuna de Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=lacuna_editar&indicador_lacuna_id=".(int)$indicador_lacuna_id."\");");
		if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir Lacuna de Indicador','Excluir esta lacuna de indicador do sistema.').'Excluir Lacuna de Indicador'.dicaF(), "javascript: void(0);' onclick='excluir()");
		}
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes', 'Imprimir os detalhes deste link.').' Detalhes'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&u=".$u."&a=".$a."&dialogo=1&indicador_lacuna_id=".$indicador_lacuna_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}	
elseif (!$dialogo){
	$botoesTitulo = new CBlocoTitulo('Detalhes da Lacuna de Indicador', 'lacuna.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="mudar_pauta();" class="texto"', $pratica_modelo_id).'</td></tr><tr><td>'.dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados do indicador inseridos no ano selecionado.').'Ano:'.dicaF().selecionaVetor($anos, 'IdxLacunaAno', 'onchange="mudar_pauta()" class="texto"', $ano).'</td></tr></table>');
	if ($podeAdicionar) $botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Nova Lacuna de Indicador', 'Criar um novo indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas& a=lacuna_editar\');" ><span>nova lacuna</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->adicionaBotao('m=praticas& a=lacuna_lista', 'lista','','Lista de Lacunas','Visualizar a lista de todas as lacunas de indicador.');
	if ($editar) {
		$botoesTitulo->adicionaBotao('m=praticas& a=lacuna_editar&indicador_lacuna_id='.$indicador_lacuna_id, 'editar','','Editar esta Lacuna de Indicador','Editar os detalhes desta lacuna de indicador.');
		if ($podeExcluir && $editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir esta Lacuna de Indicador','Excluir esta lacuna de indicador do sistema.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir a Lacuna de Indicador', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o indicador.').'<a href="javascript: void(0);" onclick="url_passar(1, \'m=praticas&a=lacuna_ver&dialogo=1&indicador_lacuna_id='.$indicador_lacuna_id.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	
	echo '</form>';
	echo estiloTopoCaixa();
	}
else {
	echo '<input type="hidden" name="IdxLacunaAno" id="IdxLacunaAno" value="'.$ano.'" />';
	echo '<input type="hidden" name="pratica_modelo_id" id="pratica_modelo_id" value="'.$pratica_modelo_id.'" />';
	}	
	
	
echo '<table cellpadding=0 cellspacing=1 '.(!$dialogo ? 'width="100%" class="std"' : 'width="780"').'>';

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->indicador_lacuna_cor.'" colspan="2"><font color="'.melhorCor($obj->indicador_lacuna_cor).'"><b>'.$obj->indicador_lacuna_nome.'<b></font></td></tr>';


$sql->adTabela('indicador_lacuna_usuarios', 'indicador_lacuna_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=indicador_lacuna_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, contato_dept');
$sql->adOnde('indicador_lacuna_id = '.(int)$indicador_lacuna_id);
$designados = $sql->Lista();
$sql->limpar();

$saida_quem='';

$sql->adTabela('indicador_lacuna_depts');
$sql->adCampo('indicador_lacuna_depts.dept_id');
$sql->adOnde('indicador_lacuna_id = '.(int)$indicador_lacuna_id);
$departamentos = $sql->Lista();
$saida_depts='';

if ($obj->indicador_lacuna_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).':'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->indicador_lacuna_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('indicador_lacuna_cia');
	$sql->adCampo('indicador_lacuna_cia_cia');
	$sql->adOnde('indicador_lacuna_cia_indicador_lacuna = '.(int)$indicador_lacuna_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();	
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= '<table cellpadding=0 cellspacing=0 width=100%>';
		$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		$saida_cias.= '</td></tr></table>';
		}
	if ($saida_cias) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}


if ($obj->indicador_lacuna_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->indicador_lacuna_dept).'</td></tr>';
$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= link_secao($departamentos[0]['dept_id']);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]['dept_id']).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Qual '.strtolower($config['departamento']).' está envolvid'.$config['genero_dept'].'.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

if ($obj->indicador_lacuna_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável', ucfirst($config['usuario']).' responsável por gerenciar.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->indicador_lacuna_responsavel, '','','esquerda').'</td></tr>';		

$saida_quem='';
if ($designados && count($designados)) {
		$saida_quem.= link_usuario($designados[0]['usuario_id'], '','','esquerda').($designados[0]['contato_dept']? ' - '.link_secao($designados[0]['contato_dept']) : '');
		$qnt_designados=count($designados);
		if ($qnt_designados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_designados; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i]['usuario_id'], '','','esquerda').($designados[$i]['contato_dept']? ' - '.link_secao($designados[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'designados\');">(+'.($qnt_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="designados"><br>'.$lista.'</span>';
				}
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';


if ($obj->indicador_lacuna_descricao) echo '<tr><td align="right" width=100 nowrap="nowrap">'.dica('Descrição', 'Descrição do indicador.').'Descrição:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->indicador_lacuna_descricao.'</td></tr>';

echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Nível de Acesso', 'Os indicadores podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o indicador.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para o indicador podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para o indicador podem ver e editar o mesmo</li><li><b>Privado</b> - Somente o responsável e os designados para o indicador podem ver o mesmo, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$indicador_lacuna_acesso[$obj->indicador_lacuna_acesso].'</td></tr>';

echo '<tr><td colspan=2><div id="combo_pauta"></div></td></tr>';

echo '</table></td></tr></table>';
if (!$dialogo) echo estiloFundoCaixa();
else echo '<script>self.print();</script>';
?>

<script language="javascript">

var pauta_atual=document.getElementById('pratica_modelo_id').value; 

function mudar_pauta(){
	xajax_mudar_pauta(document.getElementById('indicador_lacuna_id').value, document.getElementById('pratica_modelo_id').value, document.getElementById('IdxLacunaAno').value);
	}
	

function excluir() {
	if (confirm( "Tem certeza que deseja excluir esta lacuna de indicador?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='lacuna_fazer_sql';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
mudar_pauta();
	
</script>