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
require_once (BASE_DIR.'/modulos/projetos/demanda.class.php');

$Aplic->carregarCKEditorJS();

$Aplic->carregarCalendarioJS();
$demanda_id =getParam($_REQUEST, 'demanda_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

$obj = new CDemanda();

$obj->load($demanda_id);

if (($Aplic->profissional && !$demanda_id) || !$Aplic->profissional){
	$demanda_pratica = getParam($_REQUEST, 'demanda_pratica', null);
	$demanda_indicador = getParam($_REQUEST, 'demanda_indicador', null);
	$demanda_acao = getParam($_REQUEST, 'demanda_acao', null);
	$demanda_objetivo = getParam($_REQUEST, 'demanda_objetivo', null);
	$demanda_tema = getParam($_REQUEST, 'demanda_tema', null);
	$demanda_perspectiva = getParam($_REQUEST, 'demanda_perspectiva', null);
	$demanda_estrategia = getParam($_REQUEST, 'demanda_estrategia', null);
	$demanda_meta = getParam($_REQUEST, 'demanda_meta', null);
	$demanda_fator = getParam($_REQUEST, 'demanda_fator', null);
	}
else {
	$demanda_pratica = null;
	$demanda_indicador = null;
	$demanda_acao = null;
	$demanda_objetivo = null;
	$demanda_tema = null;
	$demanda_perspectiva = null;
	$demanda_estrategia = null;
	$demanda_meta = null;
	$demanda_fator = null;
	}

if ($Aplic->profissional){
	$obj->demanda_pratica=null;
	$obj->demanda_acao=null;
	$obj->demanda_indicador=null;
	$obj->demanda_estrategia=null;
	$obj->demanda_objetivo=null;
	$obj->demanda_perspectiva=null;
	$obj->demanda_tema=null;
	$obj->demanda_fator=null;
	$obj->demanda_meta=null;
	}

if ($demanda_pratica) $obj->demanda_pratica=$demanda_pratica;
if ($demanda_acao) $obj->demanda_acao=$demanda_acao;
if ($demanda_indicador) $obj->demanda_indicador=$demanda_indicador;
if ($demanda_estrategia) $obj->demanda_estrategia=$demanda_estrategia;
if ($demanda_objetivo) $obj->demanda_objetivo=$demanda_objetivo;
if ($demanda_perspectiva) $obj->demanda_perspectiva=$demanda_perspectiva;
if ($demanda_tema) $obj->demanda_tema=$demanda_tema;
if ($demanda_fator) $obj->demanda_fator=$demanda_fator;
if ($demanda_meta) $obj->demanda_meta=$demanda_meta;


if ($demanda_id){
	$cia_id=$obj->demanda_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);



	if (!$demanda_id && ($demanda_acao || $demanda_pratica  || $demanda_indicador || $demanda_objetivo || $demanda_tema || $demanda_estrategia)){
		$sql->adTabela('cias');
		if ($demanda_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
		if ($demanda_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
		if ($demanda_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
		if ($demanda_objetivo) $sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','pg_objetivo_estrategico_cia=cias.cia_id');
		if ($demanda_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
		if ($demanda_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
		if ($demanda_fator) $sql->esqUnir('fatores_criticos','fatores_criticos','pg_fator_critico_cia=cias.cia_id');
		if ($demanda_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');

		if ($demanda_acao) $sql->adOnde('plano_acao_id = '.$demanda_acao);
		elseif ($demanda_indicador) $sql->adOnde('pratica_indicador_id = '.$demanda_indicador);
		elseif ($demanda_objetivo) $sql->adOnde('pg_objetivo_estrategico_id = '.$demanda_objetivo);
		elseif ($demanda_tema) $sql->adOnde('tema_id = '.$demanda_tema);
		elseif ($demanda_estrategia) $sql->adOnde('pg_estrategia_id = '.$demanda_estrategia);
		elseif ($demanda_pratica) $sql->adOnde('pratica_id = '.$demanda_pratica);
		elseif ($demanda_fator) $sql->adOnde('pg_fator_critico_id = '.$demanda_fator);
		elseif ($demanda_meta) $sql->adOnde('pg_meta_id = '.$demanda_meta);
		$sql->adCampo('cia_id');
		$cia_id = $sql->Resultado();
		$sql->limpar();
		}
	}

if($demanda_id && !(permiteEditarDemanda($obj->demanda_acesso,$demanda_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'demanda') || $Aplic->usuario_super_admin))$Aplic->redirecionar('m=publico&a=acesso_negado');

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'demanda\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


$demanda_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

$df = '%d/%m/%Y';
$ttl = ($demanda_id ? 'Editar Demanda' : 'Criar Demanda');
$botoesTitulo = new CBlocoTitulo($ttl, 'demanda.gif', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$usuarios_selecionados=array();
$depts_selecionados=array();
$contatos_selecionados=array();
$cias_selecionadas = array();
if ($demanda_id) {
	$sql->adTabela('demanda_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('demanda_id = '.(int)$demanda_id);
	$usuarios_selecionados=$sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('demanda_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('demanda_id = '.(int)$demanda_id);
	$depts_selecionados=$sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('demanda_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('demanda_id = '.(int)$demanda_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('demanda_cia');
		$sql->adCampo('demanda_cia_cia');
		$sql->adOnde('demanda_cia_demanda = '.(int)$demanda_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_demanda" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input name="demanda_usuarios" id="demanda_usuarios" type="hidden" value="'.implode(',',$usuarios_selecionados).'" />';
echo '<input name="demanda_depts" id="demanda_depts" type="hidden" value="'.implode(',',$depts_selecionados).'" />';
echo '<input name="demanda_contatos" id="demanda_contatos" type="hidden" value="'.implode(',',$contatos_selecionados).'" />';
echo '<input name="demanda_cias"  id="demanda_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="demanda_termo_abertura" value="'.$obj->demanda_termo_abertura.'" />';
echo '<input type="hidden" name="demanda_viabilidade" value="'.$obj->demanda_viabilidade.'" />';
echo '<input type="hidden" name="demanda_caracteristica_projeto" value="'.$obj->demanda_caracteristica_projeto.'" />';
echo '<input type="hidden" name="demanda_id" id="demanda_id" value="'.$demanda_id.'" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($demanda_id ? null : uuid()).'" />';

echo '<input type="hidden" name="demanda_cliente_ativo" value="'.$obj->demanda_supervisor_ativo.'" />';
echo '<input type="hidden" name="demanda_supervisor_data" value="'.$obj->demanda_supervisor_data.'" />';
echo '<input type="hidden" name="demanda_supervisor_aprovado" value="'.$obj->demanda_supervisor_aprovado.'" />';
echo '<input type="hidden" name="demanda_supervisor_obs" value="'.$obj->demanda_supervisor_obs.'" />';

echo '<input type="hidden" name="demanda_cliente_ativo" value="'.$obj->demanda_autoridade_ativo.'" />';
echo '<input type="hidden" name="demanda_autoridade_data" value="'.$obj->demanda_autoridade_data.'" />';
echo '<input type="hidden" name="demanda_autoridade_aprovado" value="'.$obj->demanda_autoridade_aprovado.'" />';
echo '<input type="hidden" name="demanda_autoridade_obs" value="'.$obj->demanda_autoridade_obs.'" />';

echo '<input type="hidden" name="demanda_cliente_ativo" value="'.$obj->demanda_cliente_ativo.'" />';
echo '<input type="hidden" name="demanda_cliente_data" value="'.$obj->demanda_cliente_data.'" />';
echo '<input type="hidden" name="demanda_cliente_aprovado" value="'.$obj->demanda_cliente_aprovado.'" />';
echo '<input type="hidden" name="demanda_cliente_obs" value="'.$obj->demanda_cliente_obs.'" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';
echo '<tr><td align="right">'.dica('Nome', 'Toda demanda necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="demanda_nome" value="'.$obj->demanda_nome.'" style="width:284px;" class="texto" /> *</td></tr>';
echo '<input type="hidden" name="demanda_data" value="'.($obj->demanda_data ? $obj->demanda_data  : date('Y-m-d H:i:s')).'" />';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'Qual '.$config['organizacao'].' é responsável por esta demanda.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'demanda_cia', 'class=texto size=1 style="width:288px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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

if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por esta demanda.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="demanda_dept" id="demanda_dept" value="'.($demanda_id ? $obj->demanda_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($demanda_id ? $obj->demanda_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';



echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pela Demanda', 'Toda demanda deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="demanda_usuario" name="demanda_usuario" value="'.($obj->demanda_usuario ? $obj->demanda_usuario : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->demanda_usuario ? $obj->demanda_usuario : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

if ($exibir['demanda_supervisor']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['supervisor']), 'A demanda poderá ter '.($config['genero_supervisor']=='o' ? 'um' : 'uma').' '.$config['supervisor'].' relacionad'.$config['genero_supervisor'].'.').ucfirst($config['supervisor']).':'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="demanda_supervisor" name="demanda_supervisor" value="'.$obj->demanda_supervisor.'" /><input type="text" id="nome_supervisor" name="nome_supervisor" value="'.nome_om($obj->demanda_supervisor,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popSupervisor();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
if ($exibir['demanda_autoridade']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['autoridade']), 'A demanda poderá ter '.($config['genero_autoridade']=='o' ? 'um' : 'uma').' '.$config['autoridade'].' relacionad'.$config['genero_autoridade'].'.').ucfirst($config['autoridade']).':'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="demanda_autoridade" name="demanda_autoridade" value="'.$obj->demanda_autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_om($obj->demanda_autoridade,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
if ($exibir['demanda_cliente']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['cliente']), 'A demanda poderá ter '.($config['genero_cliente']=='o' ? 'um' : 'uma').' '.$config['cliente'].' relacionad'.$config['genero_cliente'].'.').ucfirst($config['cliente']).':'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="demanda_cliente" name="demanda_cliente" value="'.$obj->demanda_cliente.'" /><input type="text" id="nome_cliente" name="nome_cliente" value="'.nome_om($obj->demanda_cliente,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popCliente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';





$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s Designados', 'Clique para visualizar '.$config['genero_usuario'].'s demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';



$saida_contatos='';
if (count($contatos_selecionados)) {
		$saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_contatos.= '<tr><td>'.link_contato($contatos_selecionados[0],'','','esquerda');
		$qnt_lista_contatos=count($contatos_selecionados);
		if ($qnt_lista_contatos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
				$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.$config['contatos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
				}
		$saida_contatos.= '</td></tr></table>';
		}
else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Contatos', 'Quais '.strtolower($config['contatos']).' estão envolvid'.$config['genero_contato'].'s.').ucfirst($config['contatos']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_contatos">'.$saida_contatos.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popContatos()').'</td></tr></table></td></tr>';


if ($exibir['demanda_codigo']) echo '<tr><td align="right">'.dica('Código', 'Escreva, caso exista, o código da demanda.').'Código:'.dicaF().'</td><td><input type="text" style="width:284px;" class="texto" name="demanda_codigo" value="'.$obj->demanda_codigo.'" size="30" maxlength="255" /></td></tr>';
if ($exibir['demanda_ano']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Ano', 'A qual ano deverá a demanda estar relacionada.').'Ano:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="demanda_ano" value="'.($obj->demanda_ano ? $obj->demanda_ano : date('Y')).'" size="4" class="texto" /></td></tr>';
$setor = array('' => '') + getSisValor('Setor');
if ($exibir['demanda_setor']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce a demanda.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'demanda_setor', 'style="width:288px;" class="texto" onchange="mudar_segmento();"', $obj->demanda_setor).'</td></tr>';
$segmento=array('' => '');
if ($obj->demanda_setor){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="Segmento"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$obj->demanda_setor.'"');
	$sql->adOrdem('sisvalor_valor');
	$segmento+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['demanda_segmento']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce a demanda.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'demanda_segmento', 'style="width:288px;" class="texto" onchange="mudar_intervencao();"', $obj->demanda_segmento).'</div></td></tr>';
$intervencao=array('' => '');
if ($obj->demanda_segmento){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="Intervencao"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$obj->demanda_segmento.'"');
	$sql->adOrdem('sisvalor_valor');
	$intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['demanda_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce a demanda.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'demanda_intervencao', 'style="width:288px;" class="texto" onchange="mudar_tipo_intervencao();"', $obj->demanda_intervencao).'</div></td></tr>';

$tipo_intervencao=array('' => '');
if ($obj->demanda_intervencao){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$obj->demanda_intervencao.'"');
	$sql->adOrdem('sisvalor_valor');
	$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['demanda_tipo_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence a demanda.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'demanda_tipo_intervencao', 'style="width:288px;" class="texto"', $obj->demanda_tipo_intervencao).'</div></td></tr>';




if ($exibir['demanda_identificacao']) echo '<tr><td align="right">'.dica('Identificação', 'O demandante deve descrever a  demanda, escrevendo as informações necessárias para entendimento da necessidade.').'Identificação:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="demanda_identificacao" style="width:800px;" class="textarea">'.$obj->demanda_identificacao.'</textarea></td></tr>';
if ($exibir['demanda_justificativa']) echo '<tr><td align="right">'.dica('Justificativa', 'Descrever de forma clara a justificativa contendo um breve histórico e as motivações da demanda.').'Justificativa:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="demanda_justificativa" style="width:800px;" class="textarea">'.$obj->demanda_justificativa.'</textarea></td></tr>';
if ($exibir['demanda_resultados']) echo '<tr><td align="right">'.dica('Resultados a Serem Alcançados', 'Descrever os resultados a serem alcançadas com o atendimento da demanda.').'Resultados:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="demanda_resultados" style="width:800px;" class="textarea">'.$obj->demanda_resultados.'</textarea></td></tr>';
if ($exibir['demanda_alinhamento']) echo '<tr><td align="right">'.dica('Alinhamento Estratégico', 'Descrever o alinhamento da demanda com os demandas de planejamento institucional.').'Alinhamento:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="demanda_alinhamento" style="width:800px;" class="textarea">'.$obj->demanda_alinhamento.'</textarea></td></tr>';
if ($exibir['demanda_fonte_recurso']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Fonte de Recurso', 'Indicar a fonte de recursos para as despesas da demanda.').'Fonte de recursos:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="demanda_fonte_recurso" style="width:800px;" class="textarea">'.$obj->demanda_fonte_recurso.'</textarea></td></tr>';

if ($exibir['demanda_prazo']) echo '<tr><td align="right">'.dica('Prazo', 'Informações sobre o prazo de execução.').'Prazo:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="demanda_prazo" style="width:800px;" class="textarea">'.$obj->demanda_prazo.'</textarea></td></tr>';
if ($exibir['demanda_custos']) echo '<tr><td align="right">'.dica('Custos', 'Informações sobre o custo de execução.').'Custos:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="demanda_custos" style="width:800px;" class="textarea">'.$obj->demanda_custos.'</textarea></td></tr>';
if ($exibir['demanda_observacao']) echo '<tr><td align="right">'.dica('Observações', 'Informações gerais sobre a demanda').'Observações:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="demanda_observacao" style="width:800px;" class="textarea">'.$obj->demanda_observacao.'</textarea></td></tr>';


if ($Aplic->usuario_super_admin) echo '<tr align="right"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Criad'.$config['genero_projeto'], 'Caso da demanda foi gerad'.$config['genero_projeto'].' um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).' criad'.$config['genero_projeto'].':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="demanda_projeto" id="demanda_projeto" value="'.$obj->demanda_projeto.'" /><input type="text" id="demanda_projeto_nome" name="demanda_projeto_nome" value="'.nome_projeto($obj->demanda_projeto).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemandaProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="demanda_projeto" value="'.$obj->demanda_projeto.'" />';

if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_demanda = '.(int)$demanda_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'demanda_principal_indicador', 'class="texto" style="width:284px;"', $obj->demanda_principal_indicador).'</td></tr>';
	}
	
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'A demanda pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar a demanda.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para a demanda podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para a demanda ver e editar a demanda</li><li><b>Privado</b> - Somente o responsável e os designados para a demanda podem ver a mesma, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($demanda_acesso, 'demanda_acesso', 'class="texto"', ($demanda_id ? $obj->demanda_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="demanda_cor" value="'.($obj->demanda_cor ? $obj->demanda_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->demanda_cor ? $obj->demanda_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" width="100">'.dica('Ativa', 'Caso a demanda esteja ativa deverá estar marcado este campo.').'Ativa:'.dicaF().'</td><td><input type="checkbox" value="1" name="demanda_ativa" '.($obj->demanda_ativa || !$demanda_id ? 'checked="checked"' : '').' /></td></tr>';

$campos_customizados = new CampoCustomizados('demandas', $demanda_id, 'editar');
$campos_customizados->imprimirHTML();

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/demanda_editar_pro.php';

echo '<tr><td style="height:3px;"></td></tr>';
echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_notificar\').style.display) document.getElementById(\'apresentar_notificar\').style.display=\'\'; else document.getElementById(\'apresentar_notificar\').style.display=\'none\';"><a class="aba" href="javascript: void(0);"><b>Notificar</b></a></td></tr>';
echo '<tr id="apresentar_notificar" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
echo '<tr><td align="right" valign="top" nowrap="nowrap" width=50>'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($demanda_id > 0 ? 'modificação' : 'criação').' da demanda.').'Notificar:'.dicaF().'</td><td><input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável pela demanda.').'<label for="email_responsavel">Responsável</label>'.dicaF().'<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para a demanda.').'<label for="email_designados">Designados</label>'.dicaF().'<input type="checkbox" name="email_contatos" id="email_contatos" '.($Aplic->getPref('informa_contatos') ? 'checked="checked"' : '').' />'.dica('Contatos', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os contatos da demanda.').'<label for="email_contatos">Contatos</label>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Texto do E-mail', 'Os dados básicos da demanda são automaticamente acrescentado nos e-mail enviados, porem escreva na caixa de texto caso deseja enviar outras informações junto com o e-mail.').'Texto do e-mail:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="email_comentario" class="textarea" style="width:250px;" rows="1"></textarea></td></tr>';
echo '</table></td></tr>';


echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($demanda_id ? 'edição' : 'criação').' da demanda.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>

<script language="javascript">

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('demanda_cia').value+'&cias_id_selecionadas='+document.getElementById('demanda_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.demanda_cias.value = organizacao_id_string;
	document.getElementById('demanda_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('demanda_cias').value);
	__buildTooltip();
	}


function popDemandaProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('demanda_cia').value, window.setDemandaProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemandaProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('demanda_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemandaProjeto(chave, valor){
	document.env.demanda_projeto.value = chave;
	document.env.demanda_projeto_nome.value = valor;
	}

function enviarDados() {
	var f = document.env;

	if (f.demanda_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.demanda_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}

function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('demanda_dept').value+'&cia_id='+document.getElementById('demanda_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('demanda_dept').value+'&cia_id='+document.getElementById('demanda_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('demanda_cia').value=cia_id;
	document.getElementById('demanda_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}


function popDemandaPortfolio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Demanda", 610, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemandaPortfolio&aceita_portfolio=1&tabela=demandas', window.setDemandaPortfolio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemandaPortfolio&aceita_portfolio=1&tabela=demandas', 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemandaPortfolio(chave, valor){
	if (chave > 0) xajax_incluir_portfolio_ajax(document.getElementById('demanda_id').value, document.getElementById('uuid').value, chave);
	}

function mudar_posicao_portfolio(ordem, demanda_portfolio_filho, direcao){
	xajax_mudar_posicao_portfolio_ajax(ordem, demanda_portfolio_filho, direcao, document.getElementById('demanda_id').value, document.getElementById('uuid').value);
	}

function excluir_portfolio(demanda_portfolio_filho){
	xajax_excluir_portfolio_ajax(demanda_portfolio_filho, document.getElementById('demanda_id').value, document.getElementById('uuid').value);
	}



function popSupervisor() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['supervisor']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('demanda_cia').value+'&usuario_id='+document.getElementById('demanda_supervisor').value, window.setSupervisor, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('demanda_cia').value+'&usuario_id='+document.getElementById('demanda_supervisor').value, 'Supervisor','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setSupervisor(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('demanda_supervisor').value=usuario_id;
		document.getElementById('nome_supervisor').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}

function popAutoridade() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['autoridade']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('demanda_cia').value+'&usuario_id='+document.getElementById('demanda_autoridade').value, window.setAutoridade, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('demanda_cia').value+'&usuario_id='+document.getElementById('demanda_autoridade').value, 'Autoridade','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('demanda_autoridade').value=usuario_id;
		document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function popCliente() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['cliente']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('demanda_cia').value+'&usuario_id='+document.getElementById('demanda_cliente').value, window.setCliente, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCliente&cia_id='+document.getElementById('demanda_cia').value+'&usuario_id='+document.getElementById('demanda_cliente').value, "<?php echo ucfirst($config['cliente']) ?>",'height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setCliente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('demanda_cliente').value=usuario_id;
		document.getElementById('nome_cliente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function mudar_segmento(){
	document.getElementById('demanda_intervencao').length=0;
	document.getElementById('demanda_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('demanda_setor').value, 'Segmento', 'demanda_segmento','combo_segmento', 'style="width:288px;" class="texto" size=1 onchange="mudar_intervencao();"');
	}

function mudar_intervencao(){
	document.getElementById('demanda_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('demanda_segmento').value, 'Intervencao', 'demanda_intervencao','combo_intervencao', 'style="width:288px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"');
	}

function mudar_tipo_intervencao(){
	xajax_mudar_ajax(document.getElementById('demanda_intervencao').value, 'TipoIntervencao', 'demanda_tipo_intervencao','combo_tipo_intervencao', 'style="width:288px;" class="texto" size=1');
	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('demanda_cia').value+'&usuario_id='+document.getElementById('demanda_usuario').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('demanda_cia').value+'&usuario_id='+document.getElementById('demanda_usuario').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('demanda_usuario').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_om(){
	var cia_id=document.getElementById('demanda_cia').value;
	xajax_selecionar_om_ajax(cia_id,'demanda_cia','combo_cia', 'class="texto" size=1 style="width:288px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir esta demanda?")) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql_demanda';
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.demanda_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.demanda_cor.value;
	}






var contatos_id_selecionados = '<?php echo implode(",", $contatos_selecionados)?>';

function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('demanda_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('demanda_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, '<?php echo ucfirst($config["contatos"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.demanda_contatos.value = contato_id_string;
	contatos_id_selecionados = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados);
	__buildTooltip();
	}


var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('demanda_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('demanda_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.demanda_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('demanda_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('demanda_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.demanda_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}

</script>

