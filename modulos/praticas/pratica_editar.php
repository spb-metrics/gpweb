<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
require_once ($Aplic->getClasseModulo('praticas'));

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();
$pratica_id = getParam($_REQUEST, 'pratica_id', null);

$obj= new CPratica();
$obj->load($pratica_id);

$salvar = getParam($_REQUEST, 'salvar', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);

$sql = new BDConsulta;
$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();

if(!($podeEditar && permiteEditarPratica($obj->pratica_acesso,$pratica_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');


//lista de anos existentes
$sql->adTabela('pratica_requisito');
$sql->adCampo('DISTINCT ano');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$qnt_anos=count($ultimo_ano);
$ultimo_ano=array_pop($ultimo_ano);

for ($i=((int)date('Y'))-15; $i<=(int)date('Y')+5; $i++) $anos[$i]=$i;
asort($anos);

if (isset($_REQUEST['IdxPraticaAno'])) $Aplic->setEstado('IdxPraticaAno', getParam($_REQUEST, 'IdxPraticaAno', null));
$ano = ($Aplic->getEstado('IdxPraticaAno') !== null && isset($anos[$Aplic->getEstado('IdxPraticaAno')]) ? $Aplic->getEstado('IdxPraticaAno') : ($ultimo_ano ? $ultimo_ano : date('Y')));

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$pratica_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
if ((!$podeEditar && $pratica_id ) || (!$podeAdicionar && !$pratica_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$df = '%d/%m/%Y';

$sql->adTabela('pratica_requisito');
$sql->adCampo('pratica_requisito.*');
$sql->adOnde('pratica_id = '.(int)$pratica_id);
$sql->adOnde('ano = '.(int)$ano);
$requisito = $sql->linha();
$sql->limpar();

$usuarios_selecionados =array();
$depts_selecionados = array();
$cias_selecionadas = array();
if ($pratica_id) {
	$sql->adTabela('pratica_usuarios', 'pratica_usuarios');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('usuarios.usuario_id');
	$sql->adOnde('pratica_id = '.(int)$pratica_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('pratica_depts', 'pd');
	$sql->adTabela('depts', 'deps');
	$sql->adCampo('deps.dept_id');
	$sql->adOnde('pratica_id ='.(int)$pratica_id);
	$sql->adOnde('pd.dept_id = deps.dept_id');
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('pratica_cia');
		$sql->adCampo('pratica_cia_cia');
		$sql->adOnde('pratica_cia_pratica = '.(int)$pratica_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}


$sql->adTabela('pratica_composicao');
$sql->adCampo('pc_pratica_filho');
$sql->adOnde('pc_pratica_pai='.(int)$pratica_id);
$lista=$sql->Lista();
$sql->limpar();
$composicao=array();
foreach($lista as $linha) $composicao[]=$linha['pc_pratica_filho'];

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="pratica_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="pratica_id" id="pratica_id" value="'.$pratica_id.'" />';
echo '<input name="pratica_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="pratica_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="pratica_cias"  id="pratica_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" id="lista_composicao" name="lista_composicao" value="'.implode(',',$composicao).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($pratica_id ? null : uuid()).'" />';


$botoesTitulo = new CBlocoTitulo(($pratica_id ? 'Editar '.ucfirst($config['pratica']) : 'Criar '.ucfirst($config['pratica'])), 'pratica.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaCelula(dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados d'.$config['genero_pratica'].' '.$config['pratica'].' inseridos no ano selecionado.').'Ano:'.dicaF().selecionaVetor($anos, 'IdxPraticaAno', 'onchange="mudar_ano();" class="texto"', $ano));
if ($Aplic->profissional && $qnt_anos) $botoesTitulo->adicionaCelula('<a href="javascript: void(0)" onclick="popImportar();">'.imagem('icones/importar_p.png', 'Importar' , 'Clique neste ícone '.imagem('importar_p.png').' para importar os dados d'.$config['genero_pratica'].' '.$config['pratica'].' registrados noutro ano.').'</a>'.dicaF());
$botoesTitulo->mostrar();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'pratica\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td><table cellspacing=0 cellpadding=0 width="100%"><tr><td width="50%" valign="top"><table cellspacing=0 cellpadding=0>';
echo '<tr><td align="right" width="100">'.dica('Nome d'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Tod'.$config['genero_pratica'].' '.$config['pratica'].' necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="pratica_nome" value="'.$obj->pratica_nome.'" size="50" class="texto" /> *</td></tr>';
echo '<tr><td align="right" style="width:150px">'.dica(ucfirst($config['organizacao']).' Responsável', 'A qual '.$config['organizacao'].' pertence '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om(($obj->pratica_cia ? $obj->pratica_cia : $cia_id), 'pratica_cia', 'class=texto size=1 style="width:280px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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

if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="pratica_dept" id="pratica_dept" value="'.($pratica_id ? $obj->pratica_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($pratica_id ? $obj->pratica_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';
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

echo '<tr><td align="right" nowrap="nowrap" width="125">'.dica('Responsável pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Tod'.$config['genero_pratica'].' '.$config['pratica'].' deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="pratica_responsavel" name="pratica_responsavel" value="'.($obj->pratica_responsavel ? $obj->pratica_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->pratica_responsavel ? $obj->pratica_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
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





if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_pratica = '.(int)$pratica_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}
else{
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_pratica = '.(int)$pratica_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}

if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores d'.$config['genero_pratica'].' '.$config['pratica'].' o mais representativo da situação geral d'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.').'Indicador principal:'.dicaF().'</td><td colspan="2">'.selecionaVetor($indicadores, 'pratica_principal_indicador', 'class="texto" style="width:267px;"', $obj->pratica_principal_indicador).'</td></tr>';


if ($exibir['pratica_descricao']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Descrição', 'Descrição sobre o que se trata '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'Descrição:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_descricao" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_descricao']) ? $requisito['pratica_descricao'] : '').'</textarea></td></tr>';


$cincow2h=($exibir['pratica_oque'] && $exibir['pratica_quem'] && $exibir['pratica_quando'] && $exibir['pratica_onde'] && $exibir['pratica_porque'] && $exibir['pratica_como'] && $exibir['pratica_quanto']);
if ($cincow2h){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}

if ($exibir['pratica_oque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('O Que Fazer', 'Sumário sobre o que se trata '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'O Que:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_oque" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_oque']) ? $requisito['pratica_oque'] : '').'</textarea></td></tr>';
if ($exibir['pratica_porque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Por Que Fazer', 'Por que '.$config['genero_pratica'].' '.$config['pratica'].' será executad'.$config['genero_pratica'].'.').'Por que:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_porque" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_porque']) ? $requisito['pratica_porque'] : '').'</textarea></td></tr>';
if ($exibir['pratica_onde']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Onde Fazer', 'Onde '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Onde:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_onde" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_onde']) ? $requisito['pratica_onde'] : '').'</textarea></td></tr>';
if ($exibir['pratica_quando']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quando Fazer', 'Quando '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Quando:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_quando" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_quando']) ? $requisito['pratica_quando'] : '').'</textarea></td></tr>';
if ($exibir['pratica_como']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Como Fazer', 'Como '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Como:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_como" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_como']) ? $requisito['pratica_como'] : '').'</textarea></td></tr>';
if ($exibir['pratica_quanto']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quanto Custa', 'Custo para executar '.$config['genero_pratica'].' '.$config['pratica'].'.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_quanto" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_quanto']) ? $requisito['pratica_quanto'] : '').'</textarea></td></tr>';
if ($exibir['pratica_quem']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'Quem:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_quem" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_quem']) ? $requisito['pratica_quem'] : '').'</textarea></td></tr>';

if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="pratica_cor" value="'.($obj->pratica_cor ? $obj->pratica_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->pratica_cor ? $obj->pratica_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', ($config['genero_pratica']=='a' ? 'As ': 'Os ').$config['praticas'].' podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar '.$config['genero_pratica'].' '.$config['pratica'].'.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para '.($config['genero_pratica']=='a' ? 'a ': 'o ').$config['pratica'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados par'.($config['genero_pratica']=='a' ? 'a a ': 'a o ').$config['pratica'].' ver e editar '.$config['genero_pratica'].' '.$config['pratica'].'</li><li><b>Privado</b> - Somente o responsável e os designados par'.($config['genero_pratica']=='a' ? 'a a ': 'a o ').$config['pratica'].' podem ver '.$config['genero_pratica'].' mesm'.$config['genero_pratica'].', e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td colspan="2">'.selecionaVetor($pratica_acesso, 'pratica_acesso', 'class="texto"', ($pratica_id ? $obj->pratica_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

if ($exibir['pratica_composicao'])echo '<tr><td align="right" nowrap="nowrap">'.dica('Composição de '.ucfirst($config['praticas']), ($config['genero_pratica']=='a' ? 'As ': 'Os ').$config['praticas'].' compostas de outr'.$config['genero_pratica'].'s, o que comporia um sistema de práticas.').'Composição:'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" onclick="if (env.pratica_composicao.checked) {document.getElementById(\'botao_composicao\').style.display=\'\';} else {document.getElementById(\'botao_composicao\').style.display=\'none\';}" class="texto" name="pratica_composicao" value="1" '.($obj->pratica_composicao ? 'checked="checked"' : '').' /></td><td id="botao_composicao" '.($obj->pratica_composicao ? 'style="display:"' : 'style="display:none"').'>'.botao('composição', 'Composição','Abrir uma janela onde poderá selecionar quais são '.$config['genero_pratica'].'s '.$config['praticas'].' que compoem est'.($config['genero_pratica']=='a' ? 'a': 'e').' ora selecionad'.$config['genero_pratica'].'.','','popComposicao()').'</td></tr></table></td></tr>';

echo '<tr><td align="right">'.dica('Ativ'.$config['genero_pratica'], 'Caso '.$config['genero_pratica'].' '.$config['pratica'].' ainda esteja ativ'.$config['genero_pratica'].' deverá estar marcado este campo.').'Ativ'.$config['genero_pratica'].':'.dicaF().'</td><td><input type="checkbox" value="1" name="pratica_ativa" '.($obj->pratica_ativa || !$pratica_id ? 'checked="checked"' : '').' /></td></tr>';


$campos_customizados = new CampoCustomizados('praticas', $pratica_id, 'editar');
$campos_customizados->imprimirHTML();

echo '</td></tr></table></td>';
echo '<td width="50%" valign="top">';

echo '<table cellspacing=0 cellpadding=0 width="100%">';
echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'bsc\').style.display) document.getElementById(\'bsc\').style.display=\'\'; else document.getElementById(\'bsc\').style.display=\'none\';"><a class="aba" href="javascript: void(0);">'.dica('Balanced Score Card','Clique para exibir ou esconder a lista de requisitos atendidos de acordo com BSC.').'<b>Balanced Score Card</b>'.dicaF().'</a></td></tr>';
echo '<tr id="bsc" style="display:none"><td colspan=20><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right" nowrap="nowrap" width=100><div id="legenda_pratica_controlada">'.dica('Controlad'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' é controlad'.$config['genero_pratica'].'.').'Controlad'.$config['genero_pratica'].':'.dicaF().'</div></td><td width=16><input type="checkbox"  class="texto" name="pratica_controlada" value="1" '.(isset($requisito['pratica_controlada']) && $requisito['pratica_controlada'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_controlada" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_controlada']) ? $requisito['pratica_justificativa_controlada']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_proativa">'.dica('Proativ'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem a capacidade de antecipar-se aos fatos, a fim de prevenir a ocorrência de situações potencialmente indesejáveis e aumentar a confiança e a previsibilidade dos processos gerenciais.').'Proativ'.$config['genero_pratica'].':'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_proativa" value="1" '.(isset($requisito['pratica_proativa']) && $requisito['pratica_proativa'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_proativa" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_proativa']) ? $requisito['pratica_justificativa_proativa']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_abrange_pertinentes">'.dica('Abrangente',($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem cobertura ou escopo suficientes, horizontal ou vertical, conforme pertinente a cada processo gerencial requerido pelas áreas, processos, produtos ou partes interessadas, considerando-se o perfil d'.$config['genero_organizacao'].' '.$config['organizacao'].' e estratégias.').'Abrangente:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_abrange_pertinentes" value="1" '.(isset($requisito['pratica_abrange_pertinentes']) && $requisito['pratica_abrange_pertinentes'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_abrangencia" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_abrangencia']) ? $requisito['pratica_justificativa_abrangencia']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_continuada">'.dica('Uso Continuado', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem utilização periódica e ininterrupta, considerando-se a realização de pelo menos um ciclo completo.').'Uso Continuado:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_continuada" value="1" '.(isset($requisito['pratica_continuada']) && $requisito['pratica_continuada'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_continuada" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_continuada']) ? ($requisito['pratica_justificativa_continuada'] || !$pratica_id ? $requisito['pratica_justificativa_continuada'] : ''): '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_refinada">'.dica('Refinad'.$config['genero_pratica'], ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' apresenta aperfeiçoamento decorrente dos processos de melhoria e inovação.<br><br>Em estágios avançados de refinamento, esse subfator exige processos gerenciais atendidos por '.$config['praticas'].' no estado da arte e que incorporam alguma inovação.').'Refinad'.$config['genero_pratica'].':'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_refinada" value="1" '.(isset($requisito['pratica_refinada']) && $requisito['pratica_refinada'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_refinada" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_refinada']) ? $requisito['pratica_justificativa_refinada']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_refinada_implantacao">'.dica('Aperfeiçoamento em Implantação','<p>'.$config['genero_pratica'].' '.$config['pratica'].' incorpora ou representa um aperfeiçoamento em implantação.').'Aperfeiçoamento em implantação:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_refinada_implantacao" value="1" '.(isset($requisito['pratica_refinada_implantacao']) && $requisito['pratica_refinada_implantacao'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_refinada_implantacao" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_refinada_implantacao']) ? $requisito['pratica_justificativa_refinada_implantacao']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_melhoria_aprendizado">'.dica('Melhorias Decorrentes do Aprendizado', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' apresenta melhorias decorrentes do aprendizado.').'Melhorias pelo aprendizado:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_melhoria_aprendizado" value="1" '.(isset($requisito['pratica_melhoria_aprendizado']) && $requisito['pratica_melhoria_aprendizado'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_melhoria_aprendizado" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_melhoria_aprendizado']) ? $requisito['pratica_justificativa_melhoria_aprendizado']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_coerente">'.dica('Coerente', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem relação harmônica com as estratégias e objetivos d'.$config['genero_organizacao'].' '.$config['organizacao'].', incluindo valores e princípios.').'Coerente:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_coerente" value="1" '.(isset($requisito['pratica_coerente']) && $requisito['pratica_coerente'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_coerente" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_coerente']) ? $requisito['pratica_justificativa_coerente']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_incoerente">'.dica('Incoerência Grave','Existe incoerência grave entre os valores, princípios, estratégias e objetivos organizacionais, na realização d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Incoerência grave:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_incoerente" value="1" '.(isset($requisito['pratica_incoerente']) && $requisito['pratica_incoerente'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_incoerente" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_incoerente']) ? $requisito['pratica_justificativa_incoerente']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_interrelacionada">'.dica('Inter-relacionad'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem implementação de modo complementar com outr'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' d'.$config['genero_organizacao'].' '.$config['organizacao'].', onde apropriado.').'Inter-relacionad'.$config['genero_pratica'].':'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_interrelacionada" value="1" '.(isset($requisito['pratica_interrelacionada']) && $requisito['pratica_interrelacionada'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_interrelacionada" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_interrelacionada']) ? $requisito['pratica_justificativa_interrelacionada']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_cooperacao">'.dica('Cooperativ'.$config['genero_pratica'],'Há colaboração entre as áreas d'.$config['genero_organizacao'].' '.$config['organizacao'].' na implementação – planejamento, execução, controle ou aperfeiçoamento – n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Cooperativ'.$config['genero_pratica'].':'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_cooperacao" value="1" '.(isset($requisito['pratica_cooperacao']) && $requisito['pratica_cooperacao'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_cooperacao" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_cooperacao']) ? $requisito['pratica_justificativa_cooperacao']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_cooperacao_partes">'.dica('Cooperação com as Partes Interessadas','Há colaboração com as partes interessadas pertinentes a cada processo gerencial requerido.').'Cooperação com interessados:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_cooperacao_partes" value="1" '.(isset($requisito['pratica_cooperacao_partes']) && $requisito['pratica_cooperacao_partes'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_cooperacao_partes" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_cooperacao_partes']) ? $requisito['pratica_justificativa_cooperacao_partes']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_arte">'.dica('Estado-de-Arte',($config['genero_pratica']=='a' ? 'Esta ': 'Este ').$config['pratica'].' espelha o estado-da-arte.').'Estado-de-arte:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_arte" value="1" '.(isset($requisito['pratica_arte']) && $requisito['pratica_arte'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_arte" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_arte']) ? $requisito['pratica_justificativa_arte']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_inovacao">'.dica('Inovador'.($config['genero_pratica']=='a' ? 'a': ''),($config['genero_pratica']=='a' ? 'Esta ': 'Este ').$config['pratica'].' apresenta uma inovação de ruptura representando um novo benchmark.').'Inovador'.($config['genero_pratica']=='a' ? 'a': '').':'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_inovacao" value="1" '.(isset($requisito['pratica_inovacao']) && $requisito['pratica_inovacao'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_inovacao" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_inovacao']) ? $requisito['pratica_justificativa_inovacao']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_gerencial">'.dica('Padrão gerencial','Há padrão gerencial suficiente que oriente a execução adequada d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Padrão gerencial:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_gerencial" value="1" '.(isset($requisito['pratica_gerencial']) && $requisito['pratica_gerencial'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_gerencial" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_gerencial']) ? $requisito['pratica_justificativa_gerencial']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_agil">'.dica('Agilidade','Há agilidade suficiente nos processos gerenciais exigidos no Critério, incorporados n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Agilidade:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_agil" value="1" '.(isset($requisito['pratica_agil']) && $requisito['pratica_agil'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_agil" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_agil']) ? $requisito['pratica_justificativa_agil']: '').'</textarea></td></tr>';



echo '</table></td></tr>';


if ($Aplic->profissional){
	echo '<tr><td colspan=2>&nbsp;</td><tr>';
	echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'legislacao\').style.display) document.getElementById(\'legislacao\').style.display=\'\'; else document.getElementById(\'legislacao\').style.display=\'none\';"><a class="aba" href="javascript: void(0);">'.dica('Legislação','Clique para exibir ou esconder a lista de legislação relacionada.').'<b>Legislação</b>'.dicaF().'</a></td></tr>';
	echo '<tr id="legislacao" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<tr><td align="right" style="width:85px;">'.dica('Norma ', 'O nome da norma.').'Norma:'.dicaF().'</td><td><input type="text" id="pratica_legislacao_norma" name="pratica_legislacao_norma" value="" style="width:350px;" class="texto" /></td></tr>';
	$PraticaLegislacaoEsfera=getSisValor('PraticaLegislacaoEsfera','','','sisvalor_id');
	echo '<tr><td align="right" style="width:85px;">'.dica('Esfera', 'A esfera de criação da legislação.').'Esfera:'.dicaF().'</td><td>'.selecionaVetor($PraticaLegislacaoEsfera, 'pratica_legislacao_esfera', 'size="1" class="texto"').'</td></tr>';
	echo '<tr><td align="right">'.dica('Descrição', 'O detalhamento do legislacao de recurso.').'Descrição:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" rows="2" class="texto" name="pratica_legislacao_detalhe" id="pratica_legislacao_detalhe" style="width:550px;"></textarea></td></tr>';
	$PraticaLegislacaoImpacto=getSisValor('PraticaLegislacaoImpacto','','','sisvalor_id');
	echo '<tr><td align="right" style="width:85px;">'.dica('Impacto', 'O Impacto da legislação n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Impacto:'.dicaF().'</td><td>'.selecionaVetor($PraticaLegislacaoImpacto, 'pratica_legislacao_impacto', 'size="1" class="texto"').'</td></tr>';
	echo '</table></td>';
	echo '<input type="hidden" id="pratica_legislacao_id" name="pratica_legislacao_id" value="" /></td><td id="adicionar_legislacao" style="display:"><a href="javascript: void(0);" onclick="incluir_legislacao();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir o legislacao de recurso.').'</a></td>';
	echo '<td id="confirmar_legislacao" style="display:none"><a href="javascript: void(0);" onclick="cancelar_legislacao();">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do legislacao de recurso.').'</a><a href="javascript: void(0);" onclick="incluir_legislacao();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do legislacao de recurso.').'</a></td></tr>';
	echo '</table></td></tr>';
	$sql->adTabela('pratica_legislacao');
	$sql->adOnde('pratica_legislacao_pratica = '.(int)$pratica_id);
	$sql->adCampo('pratica_legislacao.*');
	$sql->adOrdem('pratica_legislacao_ordem');
	$legislacoes=$sql->ListaChave('pratica_legislacao_id');
	$sql->limpar();
	echo '<tr><td colspan=20 align=left><table cellspacing=0 cellpadding=0><tr><td style="width:85px;"></td><td><div id="legislacoes">';
	if (count($legislacoes)) {
		echo '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>Norma</th><th>Esfera</th><th>Detalhamento</th><th>Impacto</th><th></th></tr>';
		foreach ($legislacoes as $pratica_legislacao_id => $linha) {
			echo '<tr>';
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_legislacao('.$linha['pratica_legislacao_ordem'].', '.$linha['pratica_legislacao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_legislacao('.$linha['pratica_legislacao_ordem'].', '.$linha['pratica_legislacao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_legislacao('.$linha['pratica_legislacao_ordem'].', '.$linha['pratica_legislacao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_legislacao('.$linha['pratica_legislacao_ordem'].', '.$linha['pratica_legislacao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			echo '<td align="left">'.$linha['pratica_legislacao_norma'].'</td>';
			echo '<td align="left">'.(isset($PraticaLegislacaoEsfera[$linha['pratica_legislacao_esfera']]) && $PraticaLegislacaoEsfera[$linha['pratica_legislacao_esfera']] ? $PraticaLegislacaoEsfera[$linha['pratica_legislacao_esfera']] : '&nbsp;').'</td>';
			echo '<td align="left">'.($linha['pratica_legislacao_detalhe'] ? $linha['pratica_legislacao_detalhe'] : '&nbsp;').'</td>';
			echo '<td align="left">'.(isset($PraticaLegislacaoImpacto[$linha['pratica_legislacao_impacto']]) && $PraticaLegislacaoImpacto[$linha['pratica_legislacao_impacto']] ? $PraticaLegislacaoImpacto[$linha['pratica_legislacao_impacto']] : '&nbsp;').'</td>';
			echo '<td><a href="javascript: void(0);" onclick="editar_legislacao('.$linha['pratica_legislacao_id'].');">'.imagem('icones/editar.gif', 'Editar legislacao', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o legislacao de recurso.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este legislacao?\')) {excluir_legislacao('.$linha['pratica_legislacao_id'].');}">'.imagem('icones/remover.png', 'Excluir legislacao', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este legislacao de recurso.').'</a></td>';
			echo '</tr>';
			}
		echo '</table></td></tr></table>';
		}
	echo '</div></td></tr>';
	echo '</table></td></tr>';
	}

echo '<tr><td colspan=2>&nbsp;</td><tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Notificar por e-mail', 'Um aviso da '.($pratica_id ? 'modificação' : 'criação').' '.($config['genero_pratica']=='a' ? 'desta ': 'deste ').$config['pratica'].' poderá ser enviados por e-mail').'Notificar por e-mail:'.dicaF().'</td><td width="100%" colspan="2" valign="top"><table cellspacing=0 cellpadding=0><tr>';
echo '<td>'.dica('Responsável pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Ao selecionar esta opção, o responsável pel'.$config['genero_pratica'].' '.$config['pratica'].' será informado '.($pratica_id ? 'das alterações realizadas n'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.' : 'da criação d'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.')).'Responsável'.dicaF().'</td><td><input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" /></td>';
echo '<td>'.dica('Designados d'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Ao selecionar esta opção, os designados '.($config['genero_pratica']=='a' ? 'desta ': 'deste ').$config['pratica'].' serão informado '.($pratica_id ? 'das alterações realizadas n'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.' : 'da criação d'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.')).'Designados'.dicaF().'</td><td><input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' value="1" /></td>';
echo '</table></td></tr>';

echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<tr><td></td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td valign="top">'.($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso') ?  botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail.','','popEmailContatos()') : '').'</td>'.($config['email_ativo'] ? ''.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Extra:'.dicaF().'</td><td><input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';

echo '</table></td></tr>';

echo '</td></tr></table>';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.<br><br>Caso esteja editando '.$config['genero_pratica'].' '.$config['pratica'].', ao mudar de uma pauta para outra '.$config['genero_marcador'].'s '.$config['marcadores'].' selecionad'.$config['genero_marcador'].'s serão salv'.$config['genero_marcador'].'s.<br><br>No caso de ser '.$config['genero_pratica'].' '.$config['pratica'].' nov'.$config['genero_pratica'].' é necessário primeiramente salvar para poder selecionar '.$config['marcadores'].' de multiplas pautas.').'Pauta:'.dicaF().'</td><td>'.selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="mudar_pauta();" class="texto"', $pratica_modelo_id).'</td></tr></table></td></tr>';

echo '<tr><td colspan=20><div id="combo_pauta"></div></td></tr>';
echo '<tr><td><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($pratica_id ? 'edição' : 'criação').' do pratica.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';

echo '</form>';


echo estiloFundoCaixa();

?>
<script language="javascript">


function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('pratica_cia').value+'&cias_id_selecionadas='+document.getElementById('pratica_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.pratica_cias.value = organizacao_id_string;
	document.getElementById('pratica_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('pratica_cias').value);
	__buildTooltip();
	}


var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pratica_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pratica_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.pratica_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pratica_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pratica_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.pratica_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pratica_dept').value+'&cia_id='+document.getElementById('pratica_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pratica_dept').value+'&cia_id='+document.getElementById('pratica_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('pratica_cia').value=cia_id;
	document.getElementById('pratica_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}



function cancelar_legislacao(){
	document.getElementById('pratica_legislacao_id').value=0;
	document.getElementById('pratica_legislacao_detalhe').value='';
	document.getElementById('pratica_legislacao_norma').value='';
	document.getElementById('adicionar_legislacao').style.display='';
	document.getElementById('confirmar_legislacao').style.display='none';

	}


function mudar_posicao_legislacao(pratica_legislacao_ordem, pratica_legislacao_id, direcao){
	xajax_mudar_posicao_legislacao_ajax(pratica_legislacao_ordem, pratica_legislacao_id, direcao, document.getElementById('pratica_id').value, document.getElementById('uuid').value);
	}

function editar_legislacao(pratica_legislacao_id){
	xajax_editar_legislacao(pratica_legislacao_id);
	document.getElementById('adicionar_legislacao').style.display="none";
	document.getElementById('confirmar_legislacao').style.display="";
	}

function incluir_legislacao(){
	if (document.getElementById('pratica_legislacao_norma').value!=''){
		xajax_incluir_legislacao_ajax(document.getElementById('pratica_id').value, document.getElementById('uuid').value, document.getElementById('pratica_legislacao_id').value, document.getElementById('pratica_legislacao_norma').value, document.getElementById('pratica_legislacao_detalhe').value, document.getElementById('pratica_legislacao_impacto').value, document.getElementById('pratica_legislacao_esfera').value);
		document.getElementById('pratica_legislacao_id').value=null;
		document.getElementById('pratica_legislacao_norma').value='';
		document.getElementById('pratica_legislacao_detalhe').value='';
		document.getElementById('adicionar_legislacao').style.display='';
		document.getElementById('confirmar_legislacao').style.display='none';
		}
	else alert('Insira um nome para o legislacao de recurso.');
	}

function excluir_legislacao(pratica_legislacao_id){
	xajax_excluir_legislacao_ajax(pratica_legislacao_id, document.getElementById('pratica_id').value, document.getElementById('uuid').value);
	}



function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Contatos', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
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




function popImportar(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Importar', 500, 500, 'm=praticas&a=pratica_importar_pro&dialogo=1&pratica_id='+document.getElementById('pratica_id').value+'&ano='+document.getElementById('IdxPraticaAno').value, null, window);
	else window.open('./index.php?m=praticas&a=pratica_importar_pro&dialogo=1&pratica_id='+document.getElementById('pratica_id').value+'&ano='+document.getElementById('IdxPraticaAno').value, 'Importar','left=0,top=0,height=250,width=250,scrollbars=no, resizable=no');
	}

var pauta_atual=document.getElementById('pratica_modelo_id').value;

function mudar_ano(){
	env.a.value='pratica_editar';
	env.fazerSQL.value='';
	env.submit();
	}



function marcar_marcador(chave){
 	if (document.getElementById('checagem_'+chave).checked) document.getElementById('caixa_'+chave).style.backgroundColor='#FFFF00';
 	else document.getElementById('caixa_'+chave).style.backgroundColor='#f8f7f5';
 	xajax_marcar_marcador(document.getElementById('pratica_id').value, document.getElementById('uuid').value, chave, document.getElementById('checagem_'+chave).checked, document.getElementById('IdxPraticaAno').value);
	}

function marcar_verbo(chave, marcador_id){
 	if (document.getElementById('verbo_'+chave).checked) document.getElementById('caixa2_'+chave).style.backgroundColor='#ffddab';
 	else document.getElementById('caixa2_'+chave).style.backgroundColor='#f8f7f5';
 	xajax_marcar_verbo(document.getElementById('pratica_id').value, document.getElementById('uuid').value, marcador_id, chave, document.getElementById('verbo_'+chave).checked, document.getElementById('IdxPraticaAno').value);
	}


function marcar_complemento(marcador_id){
 	if (document.getElementById('complemento_'+marcador_id).checked) document.getElementById('caixa3_'+marcador_id).style.backgroundColor='#abfeff';
 	else document.getElementById('caixa3_'+marcador_id).style.backgroundColor='#f8f7f5';
 	xajax_marcar_complemento(document.getElementById('pratica_id').value, document.getElementById('uuid').value, marcador_id, document.getElementById('complemento_'+marcador_id).checked, document.getElementById('IdxPraticaAno').value);
	}


function marcar_evidencia(marcador_id){
 	if (document.getElementById('evidencia_'+marcador_id).checked) document.getElementById('caixa4_'+marcador_id).style.backgroundColor='#abffaf';
 	else document.getElementById('caixa4_'+marcador_id).style.backgroundColor='#f8f7f5';
 	xajax_marcar_evidencia(document.getElementById('pratica_id').value, document.getElementById('uuid').value, marcador_id, document.getElementById('evidencia_'+marcador_id).checked, document.getElementById('IdxPraticaAno').value);
	}


function SetComposicao(valores){
	document.getElementById('lista_composicao').value=valores;
	}

function popComposicao() {
		window.open('./index.php?m=praticas&a=pratica_composicao&dialogo=1&cia_id='+document.getElementById('pratica_cia').value+'&pratica_id=<?php echo $pratica_id ?>&lista_composicao='+document.getElementById('lista_composicao').value, 'Composicao','height=500,width=800,resizable,scrollbars=yes, left=0, top=0');
		}



function mudar_pauta(){
	xajax_mudar_pauta(document.getElementById('pratica_id').value, document.getElementById('uuid').value, document.getElementById('pratica_modelo_id').value, document.getElementById('IdxPraticaAno').value);
	__buildTooltip();
	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pratica_cia').value+'&usuario_id='+document.getElementById('pratica_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pratica_cia').value+'&usuario_id='+document.getElementById('pratica_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('pratica_responsavel').value=usuario_id;
		document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function mudar_om(){
	var pratica_cia=document.getElementById('pratica_cia').value;
	xajax_selecionar_om_ajax(pratica_cia,'pratica_cia','combo_cia', 'class="texto" size=1 style="width:280px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir <?php echo ($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica']?>?" )) {
		var f = document.env;
		f.del.value=1;
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.pratica_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pratica_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.pratica_nome.value.length < 3) {
		alert('Escreva um nome para a <?php echo $config["pratica"]?> válido');
		f.pratica_nome.focus();
		}
	else if (f.pratica_cia.options[f.pratica_cia.selectedIndex].value < 1) {
		alert('Necessário escolher uma <?php echo $config["om"]?>');
		f.pratica_cia.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}


function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Contatos', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pratica_cia').value+'&usuarios_id_selecionados='+contatos_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pratica_cia').value+'&usuarios_id_selecionados='+contatos_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

mudar_pauta();



</script>

