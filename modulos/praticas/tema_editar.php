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
$tema_id = getParam($_REQUEST, 'tema_id', null);

require_once (BASE_DIR.'/modulos/praticas/tema.class.php');
$obj= new CTema();
$obj->load($tema_id);

$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;


if ($tema_id){
	$cia_id=$obj->tema_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
	}


if(!($podeEditar&& permiteEditarTema($obj->tema_acesso,$tema_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');


$tema_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

if ((!$podeEditar && $tema_id) || (!$podeAdicionar && !$tema_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$df = '%d/%m/%Y';
$ttl = ($tema_id ? 'Editar '.ucfirst($config['tema']) : 'Criar '.ucfirst($config['tema']));
$botoesTitulo = new CBlocoTitulo($ttl, 'tema.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$usuarios_selecionados=array();
$indicadores=array();
$depts_selecionados=array();
$cias_selecionadas = array();
$tema_perspectiva_antigo=null;
if ($tema_id) {
	$sql->adTabela('tema_usuarios', 'tema_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('tema_id = '.(int)$tema_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('tema_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('tema_id ='.(int)$tema_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('tema_cia');
		$sql->adCampo('tema_cia_cia');
		$sql->adOnde('tema_cia_tema = '.(int)$tema_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}

	$sql->adTabela('tema_perspectiva');
	$sql->adOnde('tema_perspectiva_tema = '.(int)$obj->tema_id);
	$sql->adCampo('tema_perspectiva_perspectiva');
	$sql->adOrdem('tema_perspectiva_perspectiva');
	$tema_perspectiva_antigo=$sql->carregarColuna();
	$sql->limpar();
	$tema_perspectiva_antigo=implode(',',$tema_perspectiva_antigo);

	}



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="tema_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="tema_id" id="tema_id" value="'.$tema_id.'" />';
echo '<input name="tema_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="tema_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="tema_cias"  id="tema_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';

echo '<input type="hidden" name="uuid" id="uuid" value="'.($tema_id ? null : uuid()).'" />';
echo '<input type="hidden" name="tema_tipo_pontuacao_antigo" value="'.$obj->tema_tipo_pontuacao.'" />';
echo '<input type="hidden" name="tema_percentagem_antigo" value="'.$obj->tema_percentagem.'" />';



echo '<input type="hidden" name="tema_perspectiva_antigo" value="'.$tema_perspectiva_antigo.'" />';

if ($Aplic->profissional) {
	$sql->adTabela('tema_media');
	$sql->adCampo('tema_media_projeto AS projeto, tema_media_acao AS acao, tema_media_peso AS peso, tema_media_ponto AS ponto, tema_media_objetivo AS objetivo');
	$sql->adOnde('tema_media_objetivo='.(int)$tema_id);
	$sql->adOnde('tema_media_tipo=\''.$obj->tema_tipo_pontuacao.'\'');
	$lista=$sql->Lista();
	$sql->limpar();
	echo "<input type='hidden' name='tema_media' value='".serialize($lista)."' />";
	}


$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'tema\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome d'.$config['genero_tema'].' '.ucfirst($config['tema']).'', 'Tod'.$config['genero_tema'].' '.$config['tema'].' necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="tema_nome" value="'.$obj->tema_nome.'" style="width:284px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'A qual '.$config['organizacao'].' pertence '.($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema'].''.'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><div id="combo_cia">'.selecionar_om(($obj->tema_cia ? $obj->tema_cia : $cia_id), 'tema_cia', 'class=texto size=1 style="width:280px;" onchange="javascript:mudar_om();"').'</div></td></tr></table></td></tr>';
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

if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_tema']=='a' ? 'esta' : 'este').' '.$config['tema'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="tema_dept" id="tema_dept" value="'.($tema_id ? $obj->tema_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($tema_id ? $obj->tema_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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


echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pel'.$config['genero_tema'].' '.$config['tema'].'', 'Tod'.$config['genero_tema'].' '.$config['tema'].' deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="tema_usuario" name="tema_usuario" value="'.($obj->tema_usuario ? $obj->tema_usuario : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->tema_usuario ? $obj->tema_usuario : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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



echo '<tr><td align="right" width="100" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']), 'Tod'.$config['genero_tema'].' '.$config['tema'].' deve estar inserido dentro de '.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="tema_perspectiva" value="" /><input type="text" id="nome_perspectiva" name="nome_perspectiva" value="" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
if ($obj->tema_id) {
	$sql->adTabela('tema_perspectiva');
	$sql->adOnde('tema_perspectiva_tema = '.(int)$obj->tema_id);
	$sql->adCampo('tema_perspectiva.*');
	$sql->adOrdem('tema_perspectiva_ordem');
	$perspectivas=$sql->Lista();
	$sql->limpar();
	}
else $perspectivas=null;
echo '<tr><td></td><td colspan=19 align=left><div id="perspectivas">';
if (count($perspectivas)) {
	echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><table cellspacing=0 cellpadding=0 border=0 class="tbl1" align=left><tr><th></th><th>'.dica(ucfirst($config['perspectiva']), ucfirst($config['genero_perspectiva']).' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.').ucfirst($config['perspectiva']).dicaF().'</th><th></th></tr>';
	foreach ($perspectivas as $perspectiva) {
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['tema_perspectiva_ordem'].', '.$perspectiva['tema_perspectiva_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['tema_perspectiva_ordem'].', '.$perspectiva['tema_perspectiva_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['tema_perspectiva_ordem'].', '.$perspectiva['tema_perspectiva_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['tema_perspectiva_ordem'].', '.$perspectiva['tema_perspectiva_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td align="left" nowrap="nowrap">'.link_perspectiva($perspectiva['tema_perspectiva_perspectiva']).'</td>';
		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_perspectiva('.$perspectiva['tema_perspectiva_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}

echo '</div></td></tr>';








echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']).' Superior', 'Caso '.($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema'].' seja um desdobramento de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].' do escalão superior.').ucfirst($config['tema']).' superior:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="tema_superior" id="tema_superior" value="'.$obj->tema_superior.'" /><input type="text" id="nome_tema" name="nome_tema" value="'.nome_tema($obj->tema_superior).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_tema = '.(int)$tema_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}
else{
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_tema = '.(int)$tema_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}

if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores d'.$config['genero_tema'].' '.$config['tema'].' o mais representativo da situação geral do mesmo.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'tema_principal_indicador', 'class="texto" style="width:267px;"', $obj->tema_principal_indicador).'</td></tr>';

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/tema_editar_pro.php');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="tema_cor" value="'.($obj->tema_cor ? $obj->tema_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->tema_cor ? $obj->tema_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'Os '.$config['temas'].' podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar '.$config['genero_tema'].' '.$config['tema'].'.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para '.$config['genero_tema'].' '.$config['tema'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados podem ver e editar '.$config['genero_tema'].' '.$config['tema'].'</li><li><b>Privado</b> - Somente o responsável e os designados para '.$config['genero_tema'].' '.$config['tema'].''.' podem ver o mesmo, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($tema_acesso, 'tema_acesso', 'class="texto"', ($tema_id ? $obj->tema_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right" width="100" nowrap="nowrap">'.dica('Ativ'.$config['genero_tema'], 'Caso '.$config['genero_tema'].' '.$config['tema'].' ainda esteja ativ '.$config['genero_tema'].' deverá estar marcado este campo.').'Ativ'.$config['genero_tema'].':'.dicaF().'</td><td><input type="checkbox" value="1" name="tema_ativo" '.($obj->tema_ativo || !$tema_id ? 'checked="checked"' : '').' /></td></tr>';

if ($exibir['tema_descricao'])  echo '<tr><td align="right" nowrap="nowrap" >'.dica('Descrição', 'Descrição sobre '.($config['genero_tema']=='a' ? 'esta' : 'este').' '.$config['tema'].'.').'Descrição:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="tema_descricao" style="width:284px;" rows="2" class="textarea">'.$obj->tema_descricao.'</textarea></td></tr>';
$cincow2h=($exibir['tema_oque'] && $exibir['tema_quem'] && $exibir['tema_quando'] && $exibir['tema_onde'] && $exibir['tema_porque'] && $exibir['tema_como'] && $exibir['tema_quanto']);
if ($cincow2h){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}
if ($exibir['tema_oque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('O Que', 'Sumário sobre o que se trata '.($config['genero_tema']=='a' ? 'esta' : 'este').' '.$config['tema'].'.').'O Que:'.dicaF().'</td><td colspan="2"><textarea name="tema_oque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->tema_oque.'</textarea></td></tr>';
if ($exibir['tema_quem']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_tema']=='a' ? 'esta' : 'este').' '.$config['tema'].'.').'Quem:'.dicaF().'</td><td colspan="2"><textarea name="tema_quem" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->tema_quem.'</textarea></td></tr>';
if ($exibir['tema_quando']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quando', 'Quando '.($config['genero_tema']=='a' ? 'esta' : 'este').' '.$config['tema'].' é executad'.$config['genero_tema'].'.').'Quando:'.dicaF().'</td><td colspan="2"><textarea name="tema_quando" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->tema_quando.'</textarea></td></tr>';
if ($exibir['tema_onde']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Onde', 'Onde '.($config['genero_tema']=='a' ? 'esta' : 'este').' '.$config['tema'].' é executad'.$config['genero_tema'].'.').'Onde:'.dicaF().'</td><td colspan="2"><textarea name="tema_onde" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->tema_onde.'</textarea></td></tr>';
if ($exibir['tema_porque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Por Que', 'Por que '.($config['genero_tema']=='a' ? 'esta' : 'este').' '.$config['tema'].' será executad'.$config['genero_tema'].'.').'Por que:'.dicaF().'</td><td colspan="2"><textarea name="tema_porque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->tema_porque.'</textarea></td></tr>';
if ($exibir['tema_como']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Como', 'Como '.($config['genero_tema']=='a' ? 'esta' : 'este').' '.$config['tema'].' é executad'.$config['genero_tema'].'.').'Como:'.dicaF().'</td><td colspan="2"><textarea name="tema_como" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->tema_como.'</textarea></td></tr>';
if ($exibir['tema_quanto']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quanto', 'Custo para executar '.($config['genero_tema']=='a' ? 'esta' : 'este').' '.$config['tema'].'.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea name="tema_quanto" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->tema_quanto.'</textarea></td></tr>';
if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}
$bsc=($exibir['tema_desde_quando'] && $exibir['tema_controle'] && $exibir['tema_metodo_aprendizado'] && $exibir['tema_melhorias']);
if ($bsc){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'bsc\').style.display) document.getElementById(\'bsc\').style.display=\'\'; else document.getElementById(\'bsc\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>BSC</b></a></td></tr>';
	echo '<tr id="bsc" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}
if ($exibir['tema_desde_quando']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Desde Quando é Feita', 'Desde quando '.$config['genero_tema'].' '.$config['tema'].' é executad'.$config['genero_tema'].'.').'Desde quando:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="tema_desde_quando" cols="60" rows="2" class="textarea">'.$obj->tema_desde_quando.'</textarea></td></tr>';
if ($exibir['tema_controle'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Método de Controle', 'Como '.$config['genero_tema'].' '.$config['tema'].' é controlad'.$config['genero_tema'].'.').'Controle:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="tema_controle" cols="60" rows="2" class="textarea">'.$obj->tema_controle.'</textarea></td></tr>';
if ($exibir['tema_metodo_aprendizado'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Método de Aprendizado', 'Como é realizado o aprendizado d'.$config['genero_tema'].' '.$config['tema'].'.').'Aprendizado:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="tema_metodo_aprendizado" cols="60" rows="2" class="textarea">'.$obj->tema_metodo_aprendizado.'</textarea></td></tr>';
if ($exibir['tema_melhorias']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Melhorias Efetuadas n'.$config['genero_tema'].' '.ucfirst($config['tema']), 'Quais as melhorias realizadas n'.$config['genero_tema'].' '.$config['tema'].' após girar o círculo PDCA.').'Melhorias:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="tema_melhorias" cols="60" rows="2" class="textarea">'.$obj->tema_melhorias.'</textarea></td></tr>';
if ($bsc) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}



echo '<tr><td align="right" colspan="2" height="25">&nbsp;</td></tr>';

$campos_customizados = new CampoCustomizados('tema', $tema_id, 'editar');
$campos_customizados->imprimirHTML();



echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($tema_id > 0 ? 'modificação' : 'criação').' d'.$config['genero_tema'].' '.$config['tema'].'.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pel'.$config['genero_tema'].' '.$config['tema'].'', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por '.($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema'].'.').'<label for="email_responsavel">Responsável pel'.$config['genero_tema'].' '.$config['tema'].'</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_tema'].' '.$config['tema'].'', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para '.($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema'].'.').'<label for="email_designados">Designados para '.$config['genero_tema'].' '.$config['tema'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro d'.$config['genero_tema'].' '.$config['tema'].'.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td width="100%">'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($tema_id ? 'edição' : 'criação').' d'.$config['genero_tema'].' '.$config['tema'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">
function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('tema_cia').value+'&cias_id_selecionadas='+document.getElementById('tema_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.tema_cias.value = organizacao_id_string;
	document.getElementById('tema_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('tema_cias').value);
	__buildTooltip();
	}


var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('tema_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('tema_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.tema_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('tema_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('tema_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.tema_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('tema_dept').value+'&cia_id='+document.getElementById('tema_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('tema_dept').value+'&cia_id='+document.getElementById('tema_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('tema_cia').value=cia_id;
	document.getElementById('tema_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
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
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('tema_cia').value+'&usuario_id='+document.getElementById('tema_usuario').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('tema_cia').value+'&usuario_id='+document.getElementById('tema_usuario').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('tema_usuario').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}



function mudar_om(){
	var cia_id=document.getElementById('tema_cia').value;
	xajax_selecionar_om_ajax(cia_id,'tema_cia','combo_cia', 'class="texto" size=1 style="width:280px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir <?php echo ($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema']?>?")) {
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
	if (cor) f.tema_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.tema_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.tema_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.tema_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}




function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('tema_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('tema_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	env.tema_perspectiva.value=(chave > 0 ? chave : null);
	//env.nome_perspectiva.value=valor;

	if (chave > 0){
		xajax_incluir_perspectiva(
		document.getElementById('tema_id').value,
		document.getElementById('uuid').value,
		chave
		);
		__buildTooltip();
		}
	}


function mudar_posicao_perspectiva(ordem, tema_perspectiva_id, direcao){
	xajax_mudar_posicao_perspectiva(ordem, tema_perspectiva_id, direcao, document.getElementById('tema_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function excluir_perspectiva(tema_perspectiva_id){
	xajax_excluir_perspectiva(tema_perspectiva_id, document.getElementById('tema_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}





function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('tema_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('tema_cia').value, 'Tema','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	env.tema_superior.value=(chave > 0 ? chave : null);
	env.nome_tema.value=valor;
	}

<?php if ($Aplic->profissional) echo 'mudar_sistema();' ?>
</script>

