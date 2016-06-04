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

$pg_fator_critico_id =getParam($_REQUEST, 'pg_fator_critico_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

require_once (BASE_DIR.'/modulos/praticas/fator.class.php');
$obj= new CFator();
$obj->load($pg_fator_critico_id);


$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
$pg_fator_critico_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

if(!($podeEditar && permiteEditarFator($obj->pg_fator_critico_acesso,$pg_fator_critico_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');
if ((!$podeEditar && $pg_fator_critico_id) || (!$podeAdicionar && !$pg_fator_critico_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$df = '%d/%m/%Y';
$ttl = ($pg_fator_critico_id ? 'Editar '.ucfirst($config['fator']) : 'Criar '.ucfirst($config['fator']));
$botoesTitulo = new CBlocoTitulo($ttl, 'fator.gif', $m, $m.'.'.$a);
$botoesTitulo->mostrar();



$usuarios_selecionados =array();
$depts_selecionados = array();
$cias_selecionadas = array();
$fator_objetivo_antigo=null;
if ($pg_fator_critico_id) {
	$sql->adTabela('fatores_criticos_usuarios', 'fatores_criticos_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('pg_fator_critico_id = '.(int)$pg_fator_critico_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('fatores_criticos_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('pg_fator_critico_id ='.(int)$pg_fator_critico_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('fator_objetivo');
	$sql->adOnde('fator_objetivo_fator = '.(int)$obj->pg_fator_critico_id);
	$sql->adCampo('fator_objetivo_objetivo');
	$sql->adOrdem('fator_objetivo_objetivo');
	$fator_objetivo_antigo=$sql->carregarColuna();
	$sql->limpar();
	$fator_objetivo_antigo=implode(',',$fator_objetivo_antigo);

	if ($Aplic->profissional){
		$sql->adTabela('fator_cia');
		$sql->adCampo('fator_cia_cia');
		$sql->adOnde('fator_cia_fator = '.(int)$pg_fator_critico_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fator_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="pg_fator_critico_id" id="pg_fator_critico_id" value="'.$pg_fator_critico_id.'" />';
echo '<input name="fatores_criticos_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="pg_fator_critico_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="fator_cias"  id="fator_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';

echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($pg_fator_critico_id ? null : uuid()).'" />';
echo '<input type="hidden" name="pg_fator_critico_tipo_pontuacao_antigo" value="'.$obj->pg_fator_critico_tipo_pontuacao.'" />';
echo '<input type="hidden" name="pg_fator_critico_percentagem_antigo" value="'.$obj->pg_fator_critico_percentagem.'" />';
echo '<input type="hidden" name="pg_fator_critico_objetivo_antigo" value="'.$fator_objetivo_antigo.'" />';

if ($Aplic->profissional) {
	$sql->adTabela('fator_media');
	$sql->adCampo('fator_media_projeto AS projeto, fator_media_acao AS acao, fator_media_peso AS peso, fator_media_ponto AS ponto, fator_media_estrategia AS estrategia');
	$sql->adOnde('fator_media_fator='.(int)$pg_fator_critico_id);
	$sql->adOnde('fator_media_tipo=\''.$obj->pg_fator_critico_tipo_pontuacao.'\'');
	$lista=$sql->Lista();
	$sql->limpar();
	echo "<input type='hidden' name='fator_media' value='".serialize($lista)."' />";
	}


$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'fator\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="right">'.dica('Nome d'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Tod'.$config['genero_fator'].' '.$config['fator'].' necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="pg_fator_critico_nome" value="'.$obj->pg_fator_critico_nome.'" style="width:400px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap" width="145">'.dica(ucfirst($config['organizacao']).' Responsável', 'A qual '.$config['organizacao'].' pertence '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om(($obj->pg_fator_critico_cia ? $obj->pg_fator_critico_cia : $cia_id), 'pg_fator_critico_cia', 'class=texto size=1 style="width:404px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:404px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}
if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="pg_fator_critico_dept" id="pg_fator_critico_dept" value="'.($pg_fator_critico_id ? $obj->pg_fator_critico_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($pg_fator_critico_id ? $obj->pg_fator_critico_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:401px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:404px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap" width="125">'.dica('Responsável pel'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Tod'.$config['genero_fator'].' '.$config['fator'].' deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="pg_fator_critico_usuario" name="pg_fator_critico_usuario" value="'.($obj->pg_fator_critico_usuario ? $obj->pg_fator_critico_usuario : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->pg_fator_critico_usuario ? $obj->pg_fator_critico_usuario : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:401px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:404px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';


echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Relacionad'.$config['genero_fator'],'A quais áreas '.($config['genero_fator']=='o' ? 'este' : 'esta').' '.$config['fator'].' está relacionad'.$config['genero_objetivo'].'.').'&nbsp;<b>Relacionad'.$config['genero_fator'].'</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';



echo '<tr><td align="right" nowrap="nowrap" width="135">'.dica(ucfirst($config['objetivo']), 'A qual '.$config['objetivo'].' está relacionad'.($config['genero_fator']=='a' ? 'a esta' : 'o este').' '.$config['fator'].'.').''.ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_fator_critico_objetivo" id="pg_fator_critico_objetivo" value="" /><input type="text" id="nome_objetivo" name="nome_objetivo" value="" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
if ($Aplic->profissional && $config['exibe_me']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['me']), 'A qual '.$config['me'].' está relacionad'.($config['genero_fator']=='a' ? 'a esta' : 'o este').' '.$config['fator'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="me" id="me" value="" /><input type="text" id="nome_me" name="nome_me" value="" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['me']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="me" id="me" value="" />';


if ($obj->pg_fator_critico_id) {
	$sql->adTabela('fator_objetivo');
	$sql->adOnde('fator_objetivo_fator = '.(int)$obj->pg_fator_critico_id);
	$sql->adCampo('fator_objetivo.*');
	$sql->adOrdem('fator_objetivo_ordem');
	$objetivos=$sql->Lista();
	$sql->limpar();
	}
else $objetivos=null;

echo '<tr><td></td><td colspan=19 align=left><div id="objetivos">';
if (count($objetivos)) {
	echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><table cellspacing=0 cellpadding=0 border=0 class="tbl1" align=left><tr><th></th><th>'.dica('Nome', 'Mome do objeto relacionado.').'Nome'.dicaF().'</th><th></th></tr>';
	foreach ($objetivos as $objetivo) {
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_objetivo('.$objetivo['fator_objetivo_ordem'].', '.$objetivo['fator_objetivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_objetivo('.$objetivo['fator_objetivo_ordem'].', '.$objetivo['fator_objetivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_objetivo('.$objetivo['fator_objetivo_ordem'].', '.$objetivo['fator_objetivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_objetivo('.$objetivo['fator_objetivo_ordem'].', '.$objetivo['fator_objetivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		if ($objetivo['fator_objetivo_objetivo'])echo '<td align="left" nowrap="nowrap">'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($objetivo['fator_objetivo_objetivo']).'</td>';
		if ($objetivo['fator_objetivo_me']) echo '<td align="left" nowrap="nowrap">'.imagem('icones/me_p.png').link_me($objetivo['fator_objetivo_me']).'</td>';

		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_objetivo('.$objetivo['fator_objetivo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}

echo '</div></td></tr>';

echo '</table></fieldset></td></tr>';



if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_fator = '.(int)$pg_fator_critico_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}
else{
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_fator = '.(int)$pg_fator_critico_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}

if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores d'.$config['genero_tema'].' '.$config['tema'].' o mais representativo da situação geral do mesmo.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'pg_fator_critico_principal_indicador', 'class="texto" style="width:400px;"', $obj->pg_fator_critico_principal_indicador).'</td></tr>';

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/fator_editar_pro.php');

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="pg_fator_critico_cor" value="'.($obj->pg_fator_critico_cor ? $obj->pg_fator_critico_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->pg_fator_critico_cor ? $obj->pg_fator_critico_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', ucfirst($config['genero_fator']).' '.$config['fator'].' pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar '.$config['genero_fator'].' '.$config['fator'].'.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para '.$config['genero_fator'].' '.$config['fator'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para '.$config['genero_fator'].' '.$config['fator'].' ver e editar '.$config['genero_fator'].' '.$config['fator'].'</li><li><b>Privado</b> - Somente o responsável e os designados para '.$config['genero_fator'].' '.$config['fator'].' podem ver a mesma, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($pg_fator_critico_acesso, 'pg_fator_critico_acesso', 'class="texto"', ($pg_fator_critico_id ? $obj->pg_fator_critico_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso '.$config['genero_fator'].' '.$config['fator'].' ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="pg_fator_critico_ativo" '.($obj->pg_fator_critico_ativo || !$pg_fator_critico_id ? 'checked="checked"' : '').' /></td></tr>';


if ($exibir['pg_fator_critico_descricao'])  echo '<tr><td align="right" nowrap="nowrap" >'.dica('Descrição', 'Descrição sobre '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'Descrição:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_fator_critico_descricao" style="width:284px;" rows="2" class="textarea">'.$obj->pg_fator_critico_descricao.'</textarea></td></tr>';
$cincow2h=($exibir['pg_fator_critico_oque'] && $exibir['pg_fator_critico_quem'] && $exibir['pg_fator_critico_quando'] && $exibir['pg_fator_critico_onde'] && $exibir['pg_fator_critico_porque'] && $exibir['pg_fator_critico_como'] && $exibir['pg_fator_critico_quanto']);
if ($cincow2h){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}
if ($exibir['pg_fator_critico_oque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('O Que', 'Sumário sobre o que se trata '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'O Que:'.dicaF().'</td><td colspan="2"><textarea name="pg_fator_critico_oque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_oque.'</textarea></td></tr>';
if ($exibir['pg_fator_critico_quem']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'Quem:'.dicaF().'</td><td colspan="2"><textarea name="pg_fator_critico_quem" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_quem.'</textarea></td></tr>';
if ($exibir['pg_fator_critico_quando']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quando', 'Quando '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' é executad'.$config['genero_fator'].'.').'Quando:'.dicaF().'</td><td colspan="2"><textarea name="pg_fator_critico_quando" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_quando.'</textarea></td></tr>';
if ($exibir['pg_fator_critico_onde']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Onde', 'Onde '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' é executad'.$config['genero_fator'].'.').'Onde:'.dicaF().'</td><td colspan="2"><textarea name="pg_fator_critico_onde" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_onde.'</textarea></td></tr>';
if ($exibir['pg_fator_critico_porque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Por Que', 'Por que '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' será executad'.$config['genero_fator'].'.').'Por que:'.dicaF().'</td><td colspan="2"><textarea name="pg_fator_critico_porque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_porque.'</textarea></td></tr>';
if ($exibir['pg_fator_critico_como']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Como', 'Como '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' é executad'.$config['genero_fator'].'.').'Como:'.dicaF().'</td><td colspan="2"><textarea name="pg_fator_critico_como" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_como.'</textarea></td></tr>';
if ($exibir['pg_fator_critico_quanto']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quanto', 'Custo para executar '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea name="pg_fator_critico_quanto" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_quanto.'</textarea></td></tr>';
if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}
$bsc=($exibir['pg_fator_critico_desde_quando'] && $exibir['pg_fator_critico_controle'] && $exibir['pg_fator_critico_metodo_aprendizado'] && $exibir['pg_fator_critico_melhorias']);
if ($bsc){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'bsc\').style.display) document.getElementById(\'bsc\').style.display=\'\'; else document.getElementById(\'bsc\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>BSC</b></a></td></tr>';
	echo '<tr id="bsc" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}
if ($exibir['pg_fator_critico_desde_quando']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Desde Quando é Feita', 'Desde quando '.$config['genero_fator'].' '.$config['fator'].' é executad'.$config['genero_fator'].'.').'Desde quando:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_fator_critico_desde_quando" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_desde_quando.'</textarea></td></tr>';
if ($exibir['pg_fator_critico_controle'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Método de Controle', 'Como '.$config['genero_fator'].' '.$config['fator'].' é controlad'.$config['genero_fator'].'.').'Controle:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_fator_critico_controle" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_controle.'</textarea></td></tr>';
if ($exibir['pg_fator_critico_metodo_aprendizado'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Método de Aprendizado', 'Como é realizado o aprendizado d'.$config['genero_fator'].' '.$config['fator'].'.').'Aprendizado:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_fator_critico_metodo_aprendizado" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_metodo_aprendizado.'</textarea></td></tr>';
if ($exibir['pg_fator_critico_melhorias']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Melhorias Efetuadas n'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Quais as melhorias realizadas n'.$config['genero_fator'].' '.$config['fator'].' após girar o círculo PDCA.').'Melhorias:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_fator_critico_melhorias" cols="60" rows="2" class="textarea">'.$obj->pg_fator_critico_melhorias.'</textarea></td></tr>';
if ($bsc) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}

$campos_customizados = new CampoCustomizados('fatores', $pg_fator_critico_id, 'editar');
$campos_customizados->imprimirHTML();


echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($pg_fator_critico_id > 0 ? 'modificação' : 'criação').' d'.$config['genero_fator'].' '.$config['fator'].'.').'Notificar:'.dicaF().'</td>';
echo '<td>';


echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pel'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_fator'].' '.ucfirst($config['fator']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro d'.$config['genero_fator'].' '.$config['fator'].'.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($pg_fator_critico_id ? 'edição' : 'criação').' d'.$config['genero_fator'].' '.$config['fator'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">
function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('pg_fator_critico_cia').value+'&cias_id_selecionadas='+document.getElementById('fator_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.fator_cias.value = organizacao_id_string;
	document.getElementById('fator_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('fator_cias').value);
	__buildTooltip();
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pg_fator_critico_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pg_fator_critico_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.fatores_criticos_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pg_fator_critico_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pg_fator_critico_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.pg_fator_critico_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pg_fator_critico_dept').value+'&cia_id='+document.getElementById('pg_fator_critico_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pg_fator_critico_dept').value+'&cia_id='+document.getElementById('pg_fator_critico_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('pg_fator_critico_cia').value=cia_id;
	document.getElementById('pg_fator_critico_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('pg_fator_critico_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('pg_fator_critico_cia').value, 'Objetivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	if (chave > 0){
		xajax_incluir_objetivo(
		document.getElementById('pg_fator_critico_id').value,
		document.getElementById('uuid').value,
		chave,
		null
		);
		__buildTooltip();
		}
	}

function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMe&edicao=1&tabela=me&cia_id='+document.getElementById('pg_fator_critico_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&edicao=1&tabela=me&cia_id='+document.getElementById('pg_fator_critico_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	if (chave > 0){
		xajax_incluir_objetivo(
		document.getElementById('pg_fator_critico_id').value,
		document.getElementById('uuid').value,
		null,
		chave
		);
		__buildTooltip();
		}
	}




function mudar_posicao_objetivo(ordem, fator_objetivo_id, direcao){
	xajax_mudar_posicao_objetivo(ordem, fator_objetivo_id, direcao, document.getElementById('pg_fator_critico_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function excluir_objetivo(fator_objetivo_id){
	xajax_excluir_objetivo(fator_objetivo_id, document.getElementById('pg_fator_critico_id').value, document.getElementById('uuid').value);
	__buildTooltip();
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
	var objetivo_emails = document.getElementById('fatores_criticos_usuarios');
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
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pg_fator_critico_cia').value+'&usuario_id='+document.getElementById('pg_fator_critico_usuario').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pg_fator_critico_cia').value+'&usuario_id='+document.getElementById('pg_fator_critico_usuario').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('pg_fator_critico_usuario').value=usuario_id;
		document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function mudar_om(){
	var cia_id=document.getElementById('pg_fator_critico_cia').value;
	xajax_selecionar_om_ajax(cia_id,'pg_fator_critico_cia','combo_cia', 'class="texto" size=1 style="width:404px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir <?php echo ($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator']?>?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fator_fazer_sql';
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_fator_critico_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_fator_critico_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.pg_fator_critico_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.pg_fator_critico_nome.focus();
		}
	else {
		<?php if ($Aplic->profissional) echo 'f.pg_fator_critico_ponto_alvo.value=moeda2float(f.pg_fator_critico_ponto_alvo.value);' ?>
		f.salvar.value=1;
		f.submit();
		}
	}

<?php if ($Aplic->profissional) echo 'mudar_sistema();' ?>
</script>

