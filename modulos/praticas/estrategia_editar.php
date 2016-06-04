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
$pg_estrategia_id = getParam($_REQUEST, 'pg_estrategia_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

require_once (BASE_DIR.'/modulos/praticas/estrategia.class.php');

$obj= new CEstrategia();
$obj->load($pg_estrategia_id);


if(!($podeEditar&& permiteEditarEstrategia($obj->pg_estrategia_acesso,$pg_estrategia_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');


$pg_estrategia_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

if ((!$podeEditar && $pg_estrategia_id) || (!$podeAdicionar && !$pg_estrategia_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$ttl = ($pg_estrategia_id ? 'Editar ' : 'Criar ').ucfirst($config['iniciativa']);
$botoesTitulo = new CBlocoTitulo($ttl, 'estrategia.gif', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
$cias_selecionadas = array();
$usuarios_selecionados=array();
$depts_selecionados=array();
$estrategia_perspectiva_antigo=null;
$estrategia_fator_antigo=null;
$estrategia_tema_antigo=null;
$estrategia_objetivo_antigo=null;
if ($pg_estrategia_id) {
	$sql->adTabela('estrategias_usuarios', 'estrategias_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('pg_estrategia_id = '.(int)$pg_estrategia_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('estrategias_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('pg_estrategia_id ='.(int)$pg_estrategia_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('estrategia_fator');
	$sql->adCampo('estrategia_fator_perspectiva');
	$sql->adOnde('estrategia_fator_perspectiva IS NOT NULL');
	$sql->adOnde('estrategia_fator_estrategia = '.(int)$pg_estrategia_id);
	$sql->adOrdem('estrategia_fator_perspectiva');
	$estrategia_perspectiva_antigo=$sql->carregarColuna();
	$sql->limpar();
	$estrategia_perspectiva_antigo=implode(',',$estrategia_perspectiva_antigo);

	$sql->adTabela('estrategia_fator');
	$sql->adCampo('estrategia_fator_tema');
	$sql->adOnde('estrategia_fator_tema IS NOT NULL');
	$sql->adOnde('estrategia_fator_estrategia = '.(int)$pg_estrategia_id);
	$sql->adOrdem('estrategia_fator_tema');
	$estrategia_tema_antigo=$sql->carregarColuna();
	$sql->limpar();
	$estrategia_tema_antigo=implode(',',$estrategia_tema_antigo);

	$sql->adTabela('estrategia_fator');
	$sql->adCampo('estrategia_fator_objetivo');
	$sql->adOnde('estrategia_fator_objetivo IS NOT NULL');
	$sql->adOnde('estrategia_fator_estrategia = '.(int)$pg_estrategia_id);
	$sql->adOrdem('estrategia_fator_objetivo');
	$estrategia_objetivo_antigo=$sql->carregarColuna();
	$sql->limpar();
	$estrategia_objetivo_antigo=implode(',',$estrategia_objetivo_antigo);

	$sql->adTabela('estrategia_fator');
	$sql->adCampo('estrategia_fator_fator');
	$sql->adOnde('estrategia_fator_fator IS NOT NULL');
	$sql->adOnde('estrategia_fator_estrategia = '.(int)$pg_estrategia_id);
	$sql->adOrdem('estrategia_fator_fator');
	$estrategia_fator_antigo=$sql->carregarColuna();
	$sql->limpar();
	$estrategia_fator_antigo=implode(',',$estrategia_fator_antigo);

	if ($Aplic->profissional){
		$sql->adTabela('estrategia_cia');
		$sql->adCampo('estrategia_cia_cia');
		$sql->adOnde('estrategia_cia_estrategia = '.(int)$pg_estrategia_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="estrategia_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="pg_estrategia_id" id="pg_estrategia_id" value="'.$pg_estrategia_id.'" />';
echo '<input name="estrategias_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="pg_estrategia_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="estrategia_cias"  id="estrategia_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';

echo '<input type="hidden" name="uuid" id="uuid" value="'.($pg_estrategia_id ? null : uuid()).'" />';
echo '<input type="hidden" name="pg_estrategia_tipo_pontuacao_antigo" value="'.$obj->pg_estrategia_tipo_pontuacao.'" />';
echo '<input type="hidden" name="pg_estrategia_percentagem_antigo" value="'.$obj->pg_estrategia_percentagem.'" />';

echo '<input type="hidden" name="estrategia_perspectiva_antigo" value="'.$estrategia_perspectiva_antigo.'" />';
echo '<input type="hidden" name="estrategia_tema_antigo" value="'.$estrategia_tema_antigo.'" />';
echo '<input type="hidden" name="estrategia_objetivo_antigo" value="'.$estrategia_objetivo_antigo.'" />';
echo '<input type="hidden" name="estrategia_fator_antigo" value="'.$estrategia_fator_antigo.'" />';

if ($Aplic->profissional) {
	$sql->adTabela('estrategia_media');
	$sql->adCampo('estrategia_media_projeto AS projeto, estrategia_media_acao AS acao, estrategia_media_peso AS peso, estrategia_media_ponto AS ponto');
	$sql->adOnde('estrategia_media_estrategia='.(int)$pg_estrategia_id);
	$sql->adOnde('estrategia_media_tipo=\''.$obj->pg_estrategia_tipo_pontuacao.'\'');
	$lista=$sql->Lista();
	$sql->limpar();
	echo "<input type='hidden' name='estrategia_media' value='".serialize($lista)."' />";
	}

$sql->adTabela('estrategias_composicao');
$sql->adCampo('estrategia_filho');
$sql->adOnde('estrategia_pai='.(int)$pg_estrategia_id);
$lista=$sql->Lista();
$sql->limpar();
$composicao=array();
foreach($lista as $linha) $composicao[]=$linha['estrategia_filho'];
if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_estrategia = '.(int)$pg_estrategia_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}
else{
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_estrategia = '.(int)$pg_estrategia_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}
$percentual=getSisValor('TarefaPorcentagem','','','sisvalor_id');

echo '<input type="hidden" id="lista_composicao" name="lista_composicao" value="'.implode(',',$composicao).'" />';

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'estrategia\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="right" width="100">'.dica('Nome d'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Tod'.$config['genero_iniciativa'].' '.$config['iniciativa'].' necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="pg_estrategia_nome" value="'.$obj->pg_estrategia_nome.'" style="width:284px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'A qual '.$config['organizacao'].' pertence '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om(($obj->pg_estrategia_cia ? $obj->pg_estrategia_cia : $Aplic->usuario_cia), 'pg_estrategia_cia', 'class=texto size=1 style="width:284px;" onchange="javascript:mudar_om();"').'</div></td></tr>';

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
if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="pg_estrategia_dept" id="pg_estrategia_dept" value="'.($pg_estrategia_id ? $obj->pg_estrategia_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($pg_estrategia_id ? $obj->pg_estrategia_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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

echo '<tr><td align="right" nowrap="nowrap" width="100">'.dica('Responsável pel'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Tod'.$config['genero_iniciativa'].' '.$config['iniciativa'].' deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="pg_estrategia_usuario" name="pg_estrategia_usuario" value="'.($obj->pg_estrategia_usuario ? $obj->pg_estrategia_usuario : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->pg_estrategia_usuario ? $obj->pg_estrategia_usuario : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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


echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Relacionad'.$config['genero_iniciativa'],'A quais áreas '.($config['genero_iniciativa']=='o' ? 'este' : 'esta').' '.$config['iniciativa'].' está relacionad'.$config['genero_iniciativa'].'.').'&nbsp;<b>Relacionad'.$config['genero_iniciativa'].'</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']), 'Caso '.($config['genero_iniciativa']=='o' ? 'este' : 'esta').' '.$config['iniciativa'].' esteja relacionad'.$config['genero_iniciativa'].' a '.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'].' selecione '.$config['genero_perspectiva'].' mesm'.$config['genero_perspectiva'].'.').''.ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_objetivo_estrategico_perspectiva" value="" /><input type="text" id="nome_perspectiva" name="nome_perspectiva" value="" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']), 'Caso '.($config['genero_iniciativa']=='o' ? 'este' : 'esta').' '.$config['iniciativa'].' esteja relacionad'.$config['genero_iniciativa'].' a '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', selecione '.$config['genero_tema'].' mesm'.$config['genero_tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_objetivo_estrategico_tema" id="pg_objetivo_estrategico_tema" value="" /><input type="text" id="nome_tema" name="nome_tema" value="" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['objetivo']), 'Caso '.($config['genero_iniciativa']=='o' ? 'este' : 'esta').' '.$config['iniciativa'].' esteja relacionad'.$config['genero_iniciativa'].' a '.($config['genero_objetivo']=='a' ? 'uma' : 'um').' '.$config['objetivo'].' selecione '.$config['genero_objetivo'].' mesm'.$config['genero_objetivo'].'.').''.ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_fator_critico_objetivo" id="pg_fator_critico_objetivo" value="" /><input type="text" id="nome_objetivo" name="nome_objetivo" value="" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
if ($Aplic->profissional && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['me']), 'Caso '.($config['genero_iniciativa']=='o' ? 'este' : 'esta').' '.$config['iniciativa'].' esteja relacionad'.$config['genero_iniciativa'].' a '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', selecione '.$config['genero_me'].' mesm'.$config['genero_me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="me" id="me" value="" /><input type="text" id="nome_me" name="nome_me" value="" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['me']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="me" id="me" value="" />';
if (!$Aplic->profissional || ($Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator'))) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']), 'Caso '.($config['genero_iniciativa']=='o' ? 'este' : 'esta').' '.$config['iniciativa'].' esteja relacionad'.$config['genero_iniciativa'].' a '.($config['genero_fator']=='a' ? 'uma' : 'um').' '.$config['fator'].' selecione '.$config['genero_fator'].' mesm'.$config['genero_fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_estrategia_fator" value="" /><input type="text" id="nome_objetivo" name="nome_fator" value="" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pg_estrategia_fator" id="pg_estrategia_fator" value="" />';

if ($obj->pg_estrategia_id) {
	$sql->adTabela('estrategia_fator');
	$sql->adOnde('estrategia_fator_estrategia = '.(int)$obj->pg_estrategia_id);
	$sql->adCampo('estrategia_fator.*');
	$sql->adOrdem('estrategia_fator_ordem');
	$estrategias=$sql->Lista();
	$sql->limpar();
	}
else $estrategias=null;


echo '<tr><td>&nbsp;</td><td colspan=19 align=left><div id="perspectivas">';
if (count($estrategias)) {
	echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><table cellspacing=0 cellpadding=0 border=0 class="tbl1" align=left><tr>'.($Aplic->profissional ? '<th></th>' : '').'<th>Nome</th><th></th></tr>';
	foreach ($estrategias as $estrategia) {
		echo '<tr align="center">';
		if ($Aplic->profissional){
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$estrategia['estrategia_fator_ordem'].', '.$estrategia['estrategia_fator_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$estrategia['estrategia_fator_ordem'].', '.$estrategia['estrategia_fator_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$estrategia['estrategia_fator_ordem'].', '.$estrategia['estrategia_fator_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$estrategia['estrategia_fator_ordem'].', '.$estrategia['estrategia_fator_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
		if ($estrategia['estrategia_fator_perspectiva']) echo '<td align="left" nowrap="nowrap">'.imagem('icones/perspectiva_p.png').link_perspectiva($estrategia['estrategia_fator_perspectiva']).'</td>';
		elseif ($estrategia['estrategia_fator_tema']) echo '<td align="left" nowrap="nowrap">'.imagem('icones/tema_p.png').link_tema($estrategia['estrategia_fator_tema']).'</td>';
		elseif ($estrategia['estrategia_fator_objetivo']) echo '<td align="left" nowrap="nowrap">'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($estrategia['estrategia_fator_objetivo']).'</td>';
		elseif ($estrategia['estrategia_fator_me']) echo '<td align="left" nowrap="nowrap">'.imagem('icones/me_p.png').link_me($estrategia['estrategia_fator_me']).'</td>';
		elseif ($estrategia['estrategia_fator_fator']) echo '<td align="left" nowrap="nowrap">'.imagem('icones/fator_p.gif').link_fator($estrategia['estrategia_fator_fator']).'</td>';
		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_perspectiva('.$estrategia['estrategia_fator_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}

echo '</div></td></tr>';

echo '</table></fieldset></td></tr>';









if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores d'.$config['genero_iniciativa'].' '.$config['iniciativa'].' o mais representativo da situação geral d'.$config['genero_iniciativa'].' mesm'.$config['genero_iniciativa'].'.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'pg_estrategia_principal_indicador', 'class="texto" style="width:284px;"', $obj->pg_estrategia_principal_indicador).'</td></tr>';

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/estrategia_editar_pro.php');

if (!$Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica('Progresso', ucfirst($config['genero_iniciativa']).' '.$config['iniciativa'].' pode ir de 0% (não iniciada) até 100% (completada).').'Progresso:'.dicaF().'</td><td nowrap="nowrap">'.selecionaVetor($percentual, 'pg_estrategia_percentagem', 'size="1" class="texto"', (int)$obj->pg_estrategia_percentagem).'% </td></tr>';
$data_inicio = intval($obj->pg_estrategia_inicio) ? new CData($obj->pg_estrategia_inicio) :  new CData(date("Y-m-d H:i:s"));
$data_fim = intval($obj->pg_estrategia_fim) ? new CData($obj->pg_estrategia_fim) : new CData(date("Y-m-d H:i:s"));
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Início', 'Digite ou escolha no calendário a data provável de início.').'Data de início:'.dicaF().'</td><td nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_estrategia_inicio" id="pg_estrategia_inicio" value="'.($data_inicio ? $data_inicio->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'pg_estrategia_inicio\');" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data de Início', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data provável de início.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn1" style="vertical-align:middle" width="18" height="12" alt="Calendário" />'.dicaF().'</a></td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Meta de término', 'Digite ou escolha no calendário a data provável de término').'Data de término:</td><td nowrap="nowrap"><input type="hidden" name="pg_estrategia_fim" id="pg_estrategia_fim" value="'.($data_fim ? $data_fim->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\', \'pg_estrategia_fim\');" value="'.($data_fim ? $data_fim->format('%d/%m/%Y') : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Meta de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data provável de término.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" />'.dicaF().'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Ano', 'A qual ano  deverá '.$config['genero_iniciativa'].' '.$config['iniciativa'].' estar relacionad'.$config['genero_iniciativa'].'.').'Ano:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="pg_estrategia_ano" value="'.($obj->pg_estrategia_ano ? $obj->pg_estrategia_ano : date('Y')).'" size="4" class="texto" /></td></tr>';
if ($exibir['pg_estrategia_codigo'])echo '<tr><td align="right" nowrap="nowrap">'.dica('Código', 'O  código d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Código:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="pg_estrategia_codigo" value="'.$obj->pg_estrategia_codigo.'" size="20" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="pg_estrategia_cor" value="'.($obj->pg_estrategia_cor ? $obj->pg_estrategia_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->pg_estrategia_cor ? $obj->pg_estrategia_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', ucfirst($config['genero_iniciativa']).' '.$config['iniciativa'].' podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar '.$config['genero_iniciativa'].' '.$config['iniciativa'].'.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para '.$config['genero_iniciativa'].' '.$config['iniciativa'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para '.$config['genero_iniciativa'].' '.$config['iniciativa'].' ver e editar '.$config['genero_iniciativa'].' '.$config['iniciativa'].'</li><li><b>Privado</b> - Somente o responsável e os designados para '.$config['genero_iniciativa'].' '.$config['iniciativa'].' podem ver '.$config['genero_iniciativa'].' mesm'.$config['genero_iniciativa'].', e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($pg_estrategia_acesso, 'pg_estrategia_acesso', 'class="texto"', ($pg_estrategia_id ? $obj->pg_estrategia_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';


echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso '.$config['genero_iniciativa'].' '.$config['iniciativa'].' ainda esteja ativ'.$config['genero_iniciativa'].' deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="pg_estrategia_ativo" '.($obj->pg_estrategia_ativo || !$pg_estrategia_id ? 'checked="checked"' : '').' /></td></tr>';

if ($exibir['pg_estrategia_descricao'])  echo '<tr><td align="right" nowrap="nowrap" >'.dica('Descrição', 'Descrição sobre '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].'.').'Descrição:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_estrategia_descricao" style="width:284px;" rows="2" class="textarea">'.$obj->pg_estrategia_descricao.'</textarea></td></tr>';

$cincow2h=($exibir['pg_estrategia_oque'] && $exibir['pg_estrategia_quem'] && $exibir['pg_estrategia_quando'] && $exibir['pg_estrategia_onde'] && $exibir['pg_estrategia_porque'] && $exibir['pg_estrategia_como'] && $exibir['pg_estrategia_quanto']);

if ($cincow2h){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}

if ($exibir['pg_estrategia_oque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('O Que', 'Sumário sobre o que se trata '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].'.').'O Que:'.dicaF().'</td><td colspan="2"><textarea name="pg_estrategia_oque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_oque.'</textarea></td></tr>';
if ($exibir['pg_estrategia_quem']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].'.').'Quem:'.dicaF().'</td><td colspan="2"><textarea name="pg_estrategia_quem" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_quem.'</textarea></td></tr>';
if ($exibir['pg_estrategia_quando']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quando', 'Quando '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].' é executad'.$config['genero_iniciativa'].'.').'Quando:'.dicaF().'</td><td colspan="2"><textarea name="pg_estrategia_quando" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_quando.'</textarea></td></tr>';
if ($exibir['pg_estrategia_onde']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Onde', 'Onde '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].' é executad'.$config['genero_iniciativa'].'.').'Onde:'.dicaF().'</td><td colspan="2"><textarea name="pg_estrategia_onde" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_onde.'</textarea></td></tr>';
if ($exibir['pg_estrategia_porque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Por Que', 'Por que '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].' será executad'.$config['genero_iniciativa'].'.').'Por que:'.dicaF().'</td><td colspan="2"><textarea name="pg_estrategia_porque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_porque.'</textarea></td></tr>';
if ($exibir['pg_estrategia_como']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Como', 'Como '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].' é executad'.$config['genero_iniciativa'].'.').'Como:'.dicaF().'</td><td colspan="2"><textarea name="pg_estrategia_como" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_como.'</textarea></td></tr>';
if ($exibir['pg_estrategia_quanto']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quanto', 'Custo para executar '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].'.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea name="pg_estrategia_quanto" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_quanto.'</textarea></td></tr>';

if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}

$bsc=($exibir['pg_estrategia_desde_quando'] && $exibir['pg_estrategia_controle'] && $exibir['pg_estrategia_metodo_aprendizado'] && $exibir['pg_estrategia_melhorias']);

if ($bsc){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'bsc\').style.display) document.getElementById(\'bsc\').style.display=\'\'; else document.getElementById(\'bsc\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>BSC</b></a></td></tr>';
	echo '<tr id="bsc" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}

if ($exibir['pg_estrategia_desde_quando'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Desde Quando é Feita', 'Desde quando '.$config['genero_iniciativa'].' '.$config['iniciativa'].' é executad'.$config['genero_iniciativa'].'.').'Desde quando:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_estrategia_desde_quando" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_desde_quando.'</textarea></td></tr>';
if ($exibir['pg_estrategia_controle'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Método de Controle', 'Como '.$config['genero_iniciativa'].' '.$config['iniciativa'].' é controlad'.$config['genero_iniciativa'].'.').'Controle:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_estrategia_controle" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_controle.'</textarea></td></tr>';
if ($exibir['pg_estrategia_metodo_aprendizado'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Método de Aprendizado', 'Como é realizado o aprendizado d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Aprendizado:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_estrategia_metodo_aprendizado" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_metodo_aprendizado.'</textarea></td></tr>';
if ($exibir['pg_estrategia_melhorias']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Melhorias Efetuadas n'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Quais as melhorias realizadas n'.$config['genero_iniciativa'].' '.$config['iniciativa'].' após girar o círculo PDCA.').'Melhorias:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_estrategia_melhorias" cols="60" rows="2" class="textarea">'.$obj->pg_estrategia_melhorias.'</textarea></td></tr>';

if ($bsc) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}

if ($exibir['pg_estrategia_composicao']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Composição de '.ucfirst($config['iniciativas']), 'Marque caso '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].' seja compost'.$config['genero_iniciativa'].' de outr'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Composição:'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" onclick="if (env.pg_estrategia_composicao.checked) {document.getElementById(\'botao_composicao\').style.display=\'\';} else {document.getElementById(\'botao_composicao\').style.display=\'none\';}" class="texto" name="pg_estrategia_composicao" value="1" '.($obj->pg_estrategia_composicao ? 'checked="checked"' : '').' /></td><td id="botao_composicao" '.($obj->pg_estrategia_composicao ? 'style="display:"' : 'style="display:none"').'>'.botao('composição', 'Composição','Abrir uma janela onde poderá selecionar quais são '.$config['genero_iniciativa'].'s '.$config['iniciativas'].' que compoem esta ora selecionada.','','popComposicao()').'</td></tr></table></td></tr>';

$campos_customizados = new CampoCustomizados('estrategias', $pg_estrategia_id, 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($pg_estrategia_id > 0 ? 'modificação' : 'criação').' d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pel'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].'.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].'.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';

echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($pg_estrategia_id ? 'edição' : 'criação').' d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();


?>
<script language="javascript">
function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('pg_estrategia_cia').value+'&cias_id_selecionadas='+document.getElementById('estrategia_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.estrategia_cias.value = organizacao_id_string;
	document.getElementById('estrategia_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('estrategia_cias').value);
	__buildTooltip();
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pg_estrategia_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pg_estrategia_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.estrategias_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pg_estrategia_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pg_estrategia_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.pg_estrategia_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pg_estrategia_dept').value+'&cia_id='+document.getElementById('pg_estrategia_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pg_estrategia_dept').value+'&cia_id='+document.getElementById('pg_estrategia_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('pg_estrategia_cia').value=cia_id;
	document.getElementById('pg_estrategia_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "pg_estrategia_inicio",
	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
  onSelect: function(cal1) {
	  var date = cal1.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("pg_estrategia_inicio").value = Calendario.printDate(date, "%Y%m%d");
	    CompararDatas();
	    }
		cal1.hide();
		}
	});

var cal2 = Calendario.setup({
	trigger : "f_btn2",
  inputField : "pg_estrategia_fim",
	date : <?php echo $data_fim->format("%Y%m%d")?>,
	selection : <?php echo $data_fim->format("%Y%m%d")?>,
  onSelect : function(cal2) {
	  var date = cal2.selection.get();
	  if (date){
	    date = Calendario.intToDate(date);
	    document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("pg_estrategia_fim").value = Calendario.printDate(date, "%Y%m%d");
	    CompararDatas();
	    }
		cal2.hide();
		}
	});


function setData( frm_nome, f_data, f_data_real ){
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		}
		else {
	  	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
	  	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
	    campo_data.style.backgroundColor = '';

			//data final fazer ao menos no mesmo dia da inicial
			CompararDatas();
			}
		}
	else campo_data_real.value = '';
	}

function CompararDatas(){
    var str1 = document.getElementById("data_inicio").value;
    var str2 = document.getElementById("data_fim").value;
    var dt1  = parseInt(str1.substring(0,2),10);
    var mon1 = parseInt(str1.substring(3,5),10);
    var yr1  = parseInt(str1.substring(6,10),10);
    var dt2  = parseInt(str2.substring(0,2),10);
    var mon2 = parseInt(str2.substring(3,5),10);
    var yr2  = parseInt(str2.substring(6,10),10);
    var date1 = new Date(yr1, mon1, dt1);
    var date2 = new Date(yr2, mon2, dt2);
    if(date2 < date1){
      document.getElementById("data_fim").value=document.getElementById("data_inicio").value;
      document.getElementById("pg_estrategia_fim").value=document.getElementById("pg_estrategia_inicio").value;
    	}
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
	var objetivo_emails = document.getElementById('estrategias_usuarios');
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

function SetComposicao(valores){
	document.getElementById('lista_composicao').value=valores;
	}

function popComposicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição', 500, 500, 'm=praticas&a=estrategia_composicao&dialogo=1&cia_id='+document.getElementById('pg_estrategia_cia').value+'&pg_estrategia_id=<?php echo $pg_estrategia_id ?>&lista_composicao='+document.getElementById('lista_composicao').value, null, window);
	else window.open('./index.php?m=praticas&a=estrategia_composicao&dialogo=1&cia_id='+document.getElementById('pg_estrategia_cia').value+'&pg_estrategia_id=<?php echo $pg_estrategia_id ?>&lista_composicao='+document.getElementById('lista_composicao').value, 'Composição','height=500,width=800,resizable,scrollbars=yes, left=0, top=0');
	}

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pg_estrategia_cia').value+'&usuario_id='+document.getElementById('pg_estrategia_usuario').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pg_estrategia_cia').value+'&usuario_id='+document.getElementById('pg_estrategia_usuario').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('pg_estrategia_usuario').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function mudar_om(){
	var cia_id=document.getElementById('pg_estrategia_cia').value;
	xajax_selecionar_om_ajax(cia_id,'pg_estrategia_cia','combo_cia', 'class="texto" size=1 style="width:280px;" onchange="javascript:mudar_om();"');
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir <?php echo ($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa']?>?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='estrategia_fazer_sql';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_estrategia_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_estrategia_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.pg_estrategia_nome.value.length < 3) {
		alert('Escreva um nome para <?php echo $config["genero_iniciativa"]." ".$config["iniciativa"]?> válido');
		f.pg_estrategia_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}

function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('pg_estrategia_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('pg_estrategia_cia').value, 'Perspectiva','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	if (chave > 0){
		xajax_incluir_perspectiva(
		document.getElementById('pg_estrategia_id').value,
		document.getElementById('uuid').value,
		chave,
		null,
		null,
		null,
		null
		);
		__buildTooltip();
		}
	}


function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('pg_estrategia_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('pg_estrategia_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	if (chave > 0){
		xajax_incluir_perspectiva(
		document.getElementById('pg_estrategia_id').value,
		document.getElementById('uuid').value,
		null,
		chave,
		null,
		null,
		null
		);
		__buildTooltip();
		}
	}


function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('pg_estrategia_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('pg_estrategia_cia').value, 'Objetivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	if (chave > 0){
		xajax_incluir_perspectiva(
		document.getElementById('pg_estrategia_id').value,
		document.getElementById('uuid').value,
		null,
		null,
		chave,
		null,
		null
		);
		__buildTooltip();
		}
	}


<?php  if ($Aplic->profissional) { ?>
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMe&edicao=1&tabela=me&cia_id='+document.getElementById('pg_estrategia_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&edicao=1&tabela=me&cia_id='+document.getElementById('pg_estrategia_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	if (chave > 0){
		xajax_incluir_perspectiva(
		document.getElementById('pg_estrategia_id').value,
		document.getElementById('uuid').value,
		null,
		null,
		null,
		chave,
		null
		);
		__buildTooltip();
		}
	}
<?php } ?>



function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('pg_estrategia_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('pg_estrategia_cia').value, 'Fator','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	if (chave > 0){
		xajax_incluir_perspectiva(
		document.getElementById('pg_estrategia_id').value,
		document.getElementById('uuid').value,
		null,
		null,
		null,
		null,
		chave
		);
		__buildTooltip();
		}
	}


function mudar_posicao_perspectiva(ordem, estrategia_fator_id, direcao){
	xajax_mudar_posicao_perspectiva(ordem, estrategia_fator_id, direcao, document.getElementById('pg_estrategia_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function excluir_perspectiva(estrategia_fator_id){
	xajax_excluir_perspectiva(estrategia_fator_id, document.getElementById('pg_estrategia_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

<?php if ($Aplic->profissional) echo 'mudar_sistema();' ?>
</script>

