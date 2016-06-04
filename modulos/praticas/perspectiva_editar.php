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

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

$pg_perspectiva_id =getParam($_REQUEST, 'pg_perspectiva_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

require_once (BASE_DIR.'/modulos/praticas/perspectiva.class.php');
$obj= new CPerspectiva();
$obj->load($pg_perspectiva_id);

$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if(!($podeEditar&& permiteEditarPerspectiva($obj->pg_perspectiva_acesso,$pg_perspectiva_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');


$pg_perspectiva_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

if ((!$podeEditar && $pg_perspectiva_id) || (!$podeAdicionar && !$pg_perspectiva_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$df = '%d/%m/%Y';
$ttl = ($pg_perspectiva_id ? 'Editar '.ucfirst($config['perspectiva']) : 'Criar '.ucfirst($config['perspectiva']));
$botoesTitulo = new CBlocoTitulo($ttl, 'perspectiva.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$usuarios_selecionados=array();
$depts_selecionados=array();
$cias_selecionadas = array();
if ($pg_perspectiva_id) {
	$sql->adTabela('perspectivas_usuarios', 'perspectivas_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('pg_perspectiva_id = '.(int)$pg_perspectiva_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('perspectivas_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('pg_perspectiva_id ='.(int)$pg_perspectiva_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('perspectiva_cia');
		$sql->adCampo('perspectiva_cia_cia');
		$sql->adOnde('perspectiva_cia_perspectiva = '.(int)$pg_perspectiva_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}

if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_perspectiva = '.(int)$pg_perspectiva_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}
else{
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_perspectiva = '.(int)$pg_perspectiva_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}
echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="perspectiva_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="pg_perspectiva_id" id="pg_perspectiva_id" value="'.$pg_perspectiva_id.'" />';
echo '<input name="perspectivas_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="pg_perspectiva_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="perspectiva_cias"  id="perspectiva_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';

echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';

echo '<input type="hidden" name="uuid" id="uuid" value="'.($pg_perspectiva_id ? null : uuid()).'" />';
echo '<input type="hidden" name="pg_perspectiva_tipo_pontuacao_antigo" value="'.$obj->pg_perspectiva_tipo_pontuacao.'" />';
echo '<input type="hidden" name="pg_perspectiva_percentagem_antigo" value="'.$obj->pg_perspectiva_percentagem.'" />';

if ($Aplic->profissional) {
	$sql->adTabela('perspectiva_media');
	$sql->adCampo('perspectiva_media_projeto AS projeto, perspectiva_media_acao AS acao, perspectiva_media_peso AS peso, perspectiva_media_ponto AS ponto, perspectiva_media_objetivo AS objetivo, perspectiva_media_tema as tema');
	$sql->adOnde('perspectiva_media_perspectiva='.(int)$pg_perspectiva_id);
	$sql->adOnde('perspectiva_media_tipo=\''.$obj->pg_perspectiva_tipo_pontuacao.'\'');
	$lista=$sql->Lista();
	$sql->limpar();
	echo "<input type='hidden' name='perspectiva_media' value='".serialize($lista)."' />";
	}


$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'perspectiva\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';
echo '<tr><td align="right" width="125">'.dica('Nome d'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']).'', 'Tod'.$config['genero_perspectiva'].' '.$config['perspectiva'].' necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="pg_perspectiva_nome" value="'.$obj->pg_perspectiva_nome.'" style="width:284px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'A qual '.$config['organizacao'].' pertence a '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om(($obj->pg_perspectiva_cia ? $obj->pg_perspectiva_cia : $cia_id), 'pg_perspectiva_cia', 'class=texto size=1 style="width:284px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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
if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="pg_perspectiva_dept" id="pg_perspectiva_dept" value="'.($pg_perspectiva_id ? $obj->pg_perspectiva_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($pg_perspectiva_id ? $obj->pg_perspectiva_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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


echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pel'.$config['genero_perspectiva'].' '.$config['perspectiva'].'', 'Tod'.$config['genero_perspectiva'].' '.$config['perspectiva'].' deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="pg_perspectiva_usuario" name="pg_perspectiva_usuario" value="'.($obj->pg_perspectiva_usuario ? $obj->pg_perspectiva_usuario : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->pg_perspectiva_usuario ? $obj->pg_perspectiva_usuario : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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



if ($exibir['pg_perspectiva_superior']) echo '<tr><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['perspectiva']).' Superior', 'Selecione, se for o caso, de qual '.$config['perspectiva'].', de uma '.$config['organizacao'].' superior, esta é desdobrada.').'Superior:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_perspectiva_superior" value="'.$obj->pg_perspectiva_superior.'" /><input type="text" id="nome_perspectiva" name="nome_perspectiva" value="'.nome_perspectiva($obj->pg_perspectiva_superior).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';

if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores d'.$config['genero_perspectiva'].' '.$config['perspectiva'].' o mais representativo da situação geral do mesmo.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'pg_perspectiva_principal_indicador', 'class="texto" style="width:284px;"', $obj->pg_perspectiva_principal_indicador).'</td></tr>';


if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/perspectiva_editar_pro.php');



echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="pg_perspectiva_cor" value="'.($obj->pg_perspectiva_cor ? $obj->pg_perspectiva_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->pg_perspectiva_cor ? $obj->pg_perspectiva_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O perspectiva pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar '.$config['genero_perspectiva'].' '.$config['perspectiva'].'.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para '.$config['genero_perspectiva'].' '.$config['perspectiva'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participantes</b> - Somente o responsável e os designados para '.$config['genero_perspectiva'].' '.$config['perspectiva'].' ver e editar '.$config['genero_perspectiva'].' '.$config['perspectiva'].'</li><li><b>Privado</b> - Somente o responsável e os designados para '.$config['genero_perspectiva'].' '.$config['perspectiva'].' podem ver a mesma, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($pg_perspectiva_acesso, 'pg_perspectiva_acesso', 'class="texto"', ($pg_perspectiva_id ? $obj->pg_perspectiva_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right" width="100">'.dica('Ativ'.$config['genero_perspectiva'], 'Caso '.$config['genero_perspectiva'].' '.$config['perspectiva'].' ainda esteja ativ'.$config['genero_perspectiva'].' deverá estar marcado este campo.').'Ativ'.$config['genero_perspectiva'].':'.dicaF().'</td><td><input type="checkbox" value="1" name="pg_perspectiva_ativo" '.($obj->pg_perspectiva_ativo || !$pg_perspectiva_id ? 'checked="checked"' : '').' /></td></tr>';

if ($exibir['pg_perspectiva_descricao'])  echo '<tr><td align="right" nowrap="nowrap" >'.dica('Descrição', 'Descrição sobre '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].'.').'Descrição:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_perspectiva_descricao" style="width:284px;" rows="2" class="textarea">'.$obj->pg_perspectiva_descricao.'</textarea></td></tr>';
$cincow2h=($exibir['pg_perspectiva_oque'] && $exibir['pg_perspectiva_quem'] && $exibir['pg_perspectiva_quando'] && $exibir['pg_perspectiva_onde'] && $exibir['pg_perspectiva_porque'] && $exibir['pg_perspectiva_como'] && $exibir['pg_perspectiva_quanto']);
if ($cincow2h){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}
if ($exibir['pg_perspectiva_oque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('O Que', 'Sumário sobre o que se trata '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].'.').'O Que:'.dicaF().'</td><td colspan="2"><textarea name="pg_perspectiva_oque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_oque.'</textarea></td></tr>';
if ($exibir['pg_perspectiva_quem']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].'.').'Quem:'.dicaF().'</td><td colspan="2"><textarea name="pg_perspectiva_quem" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_quem.'</textarea></td></tr>';
if ($exibir['pg_perspectiva_quando']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quando', 'Quando '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].' é executad'.$config['genero_perspectiva'].'.').'Quando:'.dicaF().'</td><td colspan="2"><textarea name="pg_perspectiva_quando" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_quando.'</textarea></td></tr>';
if ($exibir['pg_perspectiva_onde']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Onde', 'Onde '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].' é executad'.$config['genero_perspectiva'].'.').'Onde:'.dicaF().'</td><td colspan="2"><textarea name="pg_perspectiva_onde" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_onde.'</textarea></td></tr>';
if ($exibir['pg_perspectiva_porque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Por Que', 'Por que '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].' será executad'.$config['genero_perspectiva'].'.').'Por que:'.dicaF().'</td><td colspan="2"><textarea name="pg_perspectiva_porque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_porque.'</textarea></td></tr>';
if ($exibir['pg_perspectiva_como']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Como', 'Como '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].' é executad'.$config['genero_perspectiva'].'.').'Como:'.dicaF().'</td><td colspan="2"><textarea name="pg_perspectiva_como" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_como.'</textarea></td></tr>';
if ($exibir['pg_perspectiva_quanto']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quanto', 'Custo para executar '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].'.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea name="pg_perspectiva_quanto" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_quanto.'</textarea></td></tr>';
if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}
$bsc=($exibir['pg_perspectiva_desde_quando'] && $exibir['pg_perspectiva_controle'] && $exibir['pg_perspectiva_metodo_aprendizado'] && $exibir['pg_perspectiva_melhorias']);
if ($bsc){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'bsc\').style.display) document.getElementById(\'bsc\').style.display=\'\'; else document.getElementById(\'bsc\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>BSC</b></a></td></tr>';
	echo '<tr id="bsc" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}
if ($exibir['pg_perspectiva_desde_quando']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Desde Quando é Feita', 'Desde quando '.$config['genero_perspectiva'].' '.$config['perspectiva'].' é executad'.$config['genero_perspectiva'].'.').'Desde quando:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_perspectiva_desde_quando" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_desde_quando.'</textarea></td></tr>';
if ($exibir['pg_perspectiva_controle'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Método de Controle', 'Como '.$config['genero_perspectiva'].' '.$config['perspectiva'].' é controlad'.$config['genero_perspectiva'].'.').'Controle:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_perspectiva_controle" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_controle.'</textarea></td></tr>';
if ($exibir['pg_perspectiva_metodo_aprendizado'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Método de Aprendizado', 'Como é realizado o aprendizado d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Aprendizado:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_perspectiva_metodo_aprendizado" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_metodo_aprendizado.'</textarea></td></tr>';
if ($exibir['pg_perspectiva_melhorias']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Melhorias Efetuadas n'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Quais as melhorias realizadas n'.$config['genero_perspectiva'].' '.$config['perspectiva'].' após girar o círculo PDCA.').'Melhorias:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_perspectiva_melhorias" cols="60" rows="2" class="textarea">'.$obj->pg_perspectiva_melhorias.'</textarea></td></tr>';
if ($bsc) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}

$campos_customizados = new CampoCustomizados('perspectivas', $pg_perspectiva_id, 'editar');
$campos_customizados->imprimirHTML();
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($pg_perspectiva_id > 0 ? 'modificação' : 'criação').' d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável', 'Caso esta caixa esteja selecionada, uma mensagem será enviada para o responsável por '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].'.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados', 'Caso esta caixa esteja selecionada, uma mensagem será enviada para os designados para '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].'.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas sobre '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'].'.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td width="100%">'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($pg_perspectiva_id ? 'edição' : 'criação').' d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('pg_perspectiva_cia').value+'&cias_id_selecionadas='+document.getElementById('perspectiva_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.perspectiva_cias.value = organizacao_id_string;
	document.getElementById('perspectiva_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('perspectiva_cias').value);
	__buildTooltip();
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pg_perspectiva_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pg_perspectiva_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.perspectivas_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pg_perspectiva_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pg_perspectiva_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.pg_perspectiva_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}

function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pg_perspectiva_dept').value+'&cia_id='+document.getElementById('pg_perspectiva_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pg_perspectiva_dept').value+'&cia_id='+document.getElementById('pg_perspectiva_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('pg_perspectiva_cia').value=cia_id;
	document.getElementById('pg_perspectiva_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}



function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('pg_perspectiva_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('pg_perspectiva_cia').value, 'Perspectiva','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	env.pg_perspectiva_superior.value=(chave > 0 ? chave : null);
	env.nome_perspectiva.value=valor;
	}


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
	var objetivo_emails = document.getElementById('perspectivas_usuarios');
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



function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pg_perspectiva_cia').value+'&usuario_id='+document.getElementById('pg_perspectiva_usuario').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pg_perspectiva_cia').value+'&usuario_id='+document.getElementById('pg_perspectiva_usuario').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('pg_perspectiva_usuario').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_om(){
	var cia_id=document.getElementById('pg_perspectiva_cia').value;
	xajax_selecionar_om_ajax(cia_id,'pg_perspectiva_cia','combo_cia', 'class="texto" size=1 style="width:284px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir?")) {
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
	if (cor) f.pg_perspectiva_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_perspectiva_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.pg_perspectiva_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.pg_perspectiva_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}

<?php if ($Aplic->profissional) echo 'mudar_sistema();' ?>
</script>

