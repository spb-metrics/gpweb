<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

$indicador_lacuna_id = getParam($_REQUEST, 'indicador_lacuna_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$excluir = getParam($_REQUEST, 'excluir', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);


$sql = new BDConsulta;

//lista de anos existentes
$sql->adTabela('indicador_lacuna_nos_marcadores');
$sql->adCampo('DISTINCT ano');
$sql->adOnde('indicador_lacuna_id='.(int)$indicador_lacuna_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);

for ($i=((int)date('Y'))-15; $i<=(int)date('Y')+5; $i++) $anos[$i]=$i;
asort($anos);

if (isset($_REQUEST['IdxLacunaAno'])) $Aplic->setEstado('IdxLacunaAno', getParam($_REQUEST, 'IdxLacunaAno', null));
$ano = ($Aplic->getEstado('IdxLacunaAno') !== null && isset($anos[$Aplic->getEstado('IdxLacunaAno')]) ? $Aplic->getEstado('IdxLacunaAno') : ($ultimo_ano ? $ultimo_ano : date('Y')));




$sql->adTabela('indicador_lacuna');
$sql->adCampo('indicador_lacuna.indicador_lacuna_acesso');
$sql->adOnde('indicador_lacuna_id='.(int)$indicador_lacuna_id);
$acesso=$sql->resultado();
$sql->limpar();
if (!($podeEditar && permiteAcessarIndicador($acesso,$indicador_lacuna_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();

$indicador_lacuna_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

if ((!$podeEditar && $indicador_lacuna_id) || (!$podeAdicionar && !$indicador_lacuna_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');



$df = '%d/%m/%Y';

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="lacuna_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';

$botoesTitulo = new CBlocoTitulo(($indicador_lacuna_id ? 'Editar lacuna de indicador' : 'Criar lacuna de indicador'), 'lacuna.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaCelula(dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados da lacuna de indicador inseridos no ano selecionado.').'Ano:'.dicaF().selecionaVetor($anos, 'IdxLacunaAno', 'onchange="mudar_ano(this.value);" class="texto"', $ano));
$botoesTitulo->mostrar();


$obj = new CLacuna();
$obj->load($indicador_lacuna_id);

$usuarios_selecionados =array();
$depts_selecionados = array();
$cias_selecionadas = array();
if ($indicador_lacuna_id) {
	$sql->adTabela('indicador_lacuna_usuarios', 'indicador_lacuna_usuarios');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=indicador_lacuna_usuarios.usuario_id');
	$sql->adCampo('usuarios.usuario_id');
	$sql->adOnde('indicador_lacuna_id = '.(int)$indicador_lacuna_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('indicador_lacuna_depts', 'pd');
	$sql->adTabela('depts', 'deps');
	$sql->adCampo('deps.dept_id');
	$sql->adOnde('indicador_lacuna_id ='.(int)$indicador_lacuna_id);
	$sql->adOnde('pd.dept_id = deps.dept_id');
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();
	
	if ($Aplic->profissional){
		$sql->adTabela('indicador_lacuna_cia');
		$sql->adCampo('indicador_lacuna_cia_cia');
		$sql->adOnde('indicador_lacuna_cia_indicador_lacuna = '.(int)$indicador_lacuna_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	
	}

echo '<input type="hidden" name="indicador_lacuna_id" id="indicador_lacuna_id" value="'.$indicador_lacuna_id.'" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($indicador_lacuna_id ? null : uuid()).'" />';
echo '<input name="indicador_lacuna_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="indicador_lacuna_cias"  id="indicador_lacuna_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input name="indicador_lacuna_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';



echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';


echo '<tr><td align="right">'.dica('Nome da Lacuna de Indicador', 'Toda lacuna de indicador necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="indicador_lacuna_nome" value="'.$obj->indicador_lacuna_nome.'" size="50" maxlength="512" class="texto" /> *</td></tr>';



echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' da Lacuna de Indicador', 'A qual '.$config['organizacao'].' pertence esta lacuna de indicador.').ucfirst($config['organizacao']).':'.dicaF().'</td><td width="100%" nowrap="nowrap" colspan="2"><div id="combo_cia">'.selecionar_om(($obj->indicador_lacuna_cia ? $obj->indicador_lacuna_cia : $cia_id), 'indicador_lacuna_cia', 'class=texto size=1 style="width:280px;" onchange="javascript:mudar_om();"').'</div></td></tr>';

if ($Aplic->profissional) {
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			}
	else $saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}

if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por esta lacuna de indicador.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="indicador_lacuna_dept" id="indicador_lacuna_dept" value="'.($indicador_lacuna_id ? $obj->indicador_lacuna_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($indicador_lacuna_id ? $obj->indicador_lacuna_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';
$saida_depts='';
if (count($depts_selecionados)) {
		$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_depts.= '<tr><td>'.link_secao($depts_selecionados[0]);
		$qnt_lista_depts=count($depts_selecionados);
		if ($qnt_lista_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		}
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';


echo '<tr><td align="right" nowrap="nowrap" width=100>'.dica('Responsável', 'Toda lacuna de indicador deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="indicador_lacuna_responsavel" name="indicador_lacuna_responsavel" value="'.($obj->indicador_lacuna_responsavel ? $obj->indicador_lacuna_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->indicador_lacuna_responsavel ? $obj->indicador_lacuna_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';


$sql->adTabela('indicador_lacuna_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=indicador_lacuna_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('indicador_lacuna_id = '.(int)$indicador_lacuna_id);
$participantes = $sql->Lista();


$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';


echo '<tr><td align="right" nowrap="nowrap" >'.dica('Descrição', 'Descrição sobre esta lacuna de indicador.').'Descrição:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="indicador_lacuna_descricao" style="width:284px;" rows="2" class="textarea">'.$obj->indicador_lacuna_descricao.'</textarea></td></tr>';

//echo '<tr><td colspan=2 align="left"><table style="width:800px;"><td colspan=2 align="center">Descrição</td></tr><tr><td><textarea data-gpweb-cmp="ckeditor" style="width:800px;" rows="10" name="indicador_lacuna_descricao" id="indicador_lacuna_descricao">'.$obj->indicador_lacuna_descricao.'</textarea></td></tr></table></td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'Os indicadores podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar a lacuna de indicador.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para a lacuna de indicador podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para a lacuna de indicador podem ver e editar o mesmo</li><li><b>Privado</b> - Somente o responsável e os designados para a lacuna de indicador podem ver o mesmo, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicador_lacuna_acesso, 'indicador_lacuna_acesso', 'class="texto"', ($indicador_lacuna_id ? $obj->indicador_lacuna_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="indicador_lacuna_cor" value="'.($obj->indicador_lacuna_cor ? $obj->indicador_lacuna_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->indicador_lacuna_cor ? $obj->indicador_lacuna_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right">'.dica('Ativo', 'Caso a lacuna de indicador ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="indicador_lacuna_ativo" '.($obj->indicador_lacuna_ativo || !$indicador_lacuna_id ? 'checked="checked"' : '').' /></td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Notificar por e-mail', 'Um aviso da '.($indicador_lacuna_id > 0 ? 'modificação' : 'criação').' desta lacuna de indicador poderá ser enviados por e-mail').'Notificar por e-mail:'.dicaF().'</td><td width="100%" colspan="2" valign="top">';
echo dica('Responsável pela Lacuna de Indicador', 'Ao selecionar esta opção, o responsável pela lacuna de indicador será informado '.($indicador_lacuna_id > 0 ? 'das alterações realizadas no mesmo.' : 'da criação do mesmo.')).'Responsável'.dicaF().'<input type="checkbox" name="email_indicador_responsavel_box" id="email_indicador_responsavel_box" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'": '').' />&nbsp;&nbsp;&nbsp;&nbsp;';
echo dica('Designados para a Lacuna de Indicador', 'Ao selecionar esta opção, os designados para atualizar a lacuna de indicador serão informado '.($indicador_lacuna_id > 0 ? 'das alterações realizadas no mesmo.' : 'da criação do mesmo.')).'Designados'.dicaF().'<input type="checkbox" name="email_indicador_designados_box" id="email_indicador_designados_box" '.($Aplic->getPref('informa_designados') ? "checked='checked'" : '').' />';
echo '</td></tr>';


echo '<tr><td nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().'</td><td align="left">'.selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="mudar_pauta();" class="texto"', $pratica_modelo_id).'</td></tr>';
echo '<tr><td colspan=2><div id="combo_pauta"></div></td></tr>';
echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($indicador_lacuna_id > 0 ? 'edição' : 'criação').' da lacuna de indicador.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table></form>';

echo estiloFundoCaixa();

?>
<script language="javascript">

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('indicador_lacuna_cia').value+'&cias_id_selecionadas='+document.getElementById('indicador_lacuna_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.indicador_lacuna_cias.value = organizacao_id_string;
	document.getElementById('indicador_lacuna_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('indicador_lacuna_cias').value);
	__buildTooltip();
	}


function marcar_marcador(pratica_marcador_id){
	if (document.getElementById('checagem_'+pratica_marcador_id).checked) document.getElementById('caixa_'+pratica_marcador_id).style.backgroundColor='#FFFF00';
	else document.getElementById('caixa_'+pratica_marcador_id).style.backgroundColor='#f2f0ec';
	xajax_marcar_marcador(document.getElementById('indicador_lacuna_id').value, document.getElementById('uuid').value, pratica_marcador_id, document.getElementById('checagem_'+pratica_marcador_id).checked, document.getElementById('IdxLacunaAno').value);
	}


function mudar_ano(ano){
	document.env.a.value='lacuna_editar';
	document.env.fazerSQL.value='';
	document.env.submit();
	}




var pauta_atual=document.getElementById('pratica_modelo_id').value;

function mudar_pauta(){
	xajax_mudar_pauta(document.getElementById('indicador_lacuna_id').value, document.getElementById('uuid').value, document.getElementById('pratica_modelo_id').value, document.getElementById('IdxLacunaAno').value);
	}

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('indicador_lacuna_cia').value+'&usuario_id='+document.getElementById('indicador_lacuna_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('indicador_lacuna_cia').value+'&usuario_id='+document.getElementById('indicador_lacuna_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('indicador_lacuna_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function mudar_om(){
	var indicador_lacuna_cia=document.getElementById('indicador_lacuna_cia').value;
	xajax_selecionar_om_ajax(indicador_lacuna_cia,'indicador_lacuna_cia','combo_cia', 'class="texto" size=1 style="width:280px;" onchange="javascript:mudar_om();"');
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

function setCor(cor) {
	var f = document.env;
	if (cor) f.indicador_lacuna_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.indicador_lacuna_cor.value;
	}


function enviarDados(){
	var f = document.env;

	if (f.indicador_lacuna_nome.value.length < 3) {
		alert('Escreva um nome para a lacuna de indicador válido');
		f.indicador_lacuna_nome.focus();
		}
	else if (f.indicador_lacuna_cia.options[f.indicador_lacuna_cia.selectedIndex].value < 1) {
		alert('Necessário escolher <?php echo $config["genero_organizacao"]." ".$config["organizacao"]?> responsável');
		f.indicador_lacuna_cia.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('indicador_lacuna_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('indicador_lacuna_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.indicador_lacuna_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('indicador_lacuna_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('indicador_lacuna_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.indicador_lacuna_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('indicador_lacuna_dept').value+'&cia_id='+document.getElementById('indicador_lacuna_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('indicador_lacuna_dept').value+'&cia_id='+document.getElementById('indicador_lacuna_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('indicador_lacuna_cia').value=cia_id;
	document.getElementById('indicador_lacuna_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

mudar_pauta();
</script>

