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
require_once (BASE_DIR.'/modulos/projetos/viabilidade.class.php');
require_once (BASE_DIR.'/modulos/projetos/demanda.class.php');

$Aplic->carregarCKEditorJS();

$Aplic->carregarCalendarioJS();
$projeto_viabilidade_id =getParam($_REQUEST, 'projeto_viabilidade_id', null);
$demanda_id =getParam($_REQUEST, 'demanda_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

$demanda = new CDemanda();
if ($demanda_id) $demanda->load($demanda_id);


$obj = new CViabilidade();

if ($projeto_viabilidade_id){
	$obj->load($projeto_viabilidade_id);
	$cia_id=$obj->projeto_viabilidade_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
	}


if(!(permiteEditarDemanda($obj->projeto_viabilidade_acesso,$projeto_viabilidade_id) && ($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'viabilidade') || $Aplic->usuario_super_admin))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'viabilidade\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$projeto_viabilidade_acesso = getSisValor('NivelAcesso','','','sisvalor_id');


$df = '%d/%m/%Y';

$botoesTitulo = new CBlocoTitulo(($projeto_viabilidade_id ? 'Editar Estudo de Viabilidade' : 'Criar Estudo de Viabilidade'), 'viabilidade.gif', $m, $m.'.'.$a);

if (!$Aplic->profissional){
	$botoesTitulo->adicionaBotao('m='.$m.'&a=viabilidade_lista', 'estudos de viabilidade','','Lista de Estudos de Viabilidade','Visualizar a lista de todos os estudos de viabilidade.');
	if ($projeto_viabilidade_id != 0) $botoesTitulo->adicionaBotao('m='.$m.'&a=viabilidade_ver&projeto_viabilidade_id='.$projeto_viabilidade_id, 'ver detalhes', '', 'Ver os Detalhes', 'Visualizar os detalhes deste estudo de viabilidade.');
	if ($projeto_viabilidade_id && $podeExcluir)	$botoesTitulo->adicionaBotaoExcluir('excluir', $projeto_viabilidade_id, '', 'Excluir Estudo de Viabilidade', 'Excluir este estudo de viabilidade.' );
	}
$botoesTitulo->mostrar();

$usuarios_selecionados=array();
$patrocinadores_selecionados=array();
$contatos_selecionados=array();
$depts_selecionados=array();
$cias_selecionadas=array();
if ($projeto_viabilidade_id) {
	$sql->adTabela('projeto_viabilidade_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_viabilidade_patrocinadores');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
	$patrocinadores_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_viabilidade_interessados');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_viabilidade_dept');
	$sql->adCampo('projeto_viabilidade_dept_dept');
	$sql->adOnde('projeto_viabilidade_dept_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
	$depts_selecionados=$sql->carregarColuna();
	$sql->limpar();
	
	
	if ($Aplic->profissional){
		$sql->adTabela('projeto_viabilidade_cia');
		$sql->adCampo('projeto_viabilidade_cia_cia');
		$sql->adOnde('projeto_viabilidade_cia_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}
else if($demanda_id){
	$sql->adTabela('demanda_usuarios');
	$sql->adCampo('demanda_usuarios.usuario_id');
	$sql->adOnde('demanda_id = '.(int)$demanda_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('demanda_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('demanda_id = '.(int)$demanda_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('demanda_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('demanda_id = '.(int)$demanda_id);
	$depts_selecionados=$sql->carregarColuna();
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
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_viabilidade" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="projeto_viabilidade_id" id="projeto_viabilidade_id" value="'.$projeto_viabilidade_id.'" />';
echo '<input name="projeto_viabilidade_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="projeto_viabilidade_patrocinadores" type="hidden" value="'.implode(',', $patrocinadores_selecionados).'" />';
echo '<input name="projeto_viabilidade_interessados" type="hidden" value="'.implode(',', $contatos_selecionados).'" />';
echo '<input name="projeto_viabilidade_depts" id="projeto_viabilidade_depts" type="hidden" value="'.implode(',',$depts_selecionados).'" />';
echo '<input name="projeto_viabilidade_cias"  id="projeto_viabilidade_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';

echo '<input type="hidden" name="uuid" id="uuid" value="'.($projeto_viabilidade_id ? null : uuid()).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="projeto_viabilidade_demanda" value="'.($obj->projeto_viabilidade_demanda ? $obj->projeto_viabilidade_demanda : $demanda_id).'" />';
echo '<input type="hidden" name="projeto_viabilidade_projeto" value="'.$obj->projeto_viabilidade_projeto.'" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Possível Nome d'.$config['genero_projeto'].' '.$config['projeto'], 'Tod'.$config['genero_projeto'].' '.$config['projeto'].' necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome d'.$config['genero_projeto'].' '.$config['projeto'].':'.dicaF().'</td><td><input type="text" name="projeto_viabilidade_nome" value="'.($obj->projeto_viabilidade_nome ? $obj->projeto_viabilidade_nome : $demanda->demanda_nome).'" style="width:600px;" class="texto" /> *</td></tr>';
$viavel=array(1=>'Sim', -1=>'Não');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Viável', 'Baseado na análise deste estudo a demanda é viável de ser transformar em um projeto').'<b>Viável</b>:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($viavel, 'projeto_viabilidade_viavel', 'class="texto"', $obj->projeto_viabilidade_viavel).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' do Estudo de Viabilidade', 'A qual '.$config['organizacao'].' pertence este estudo de viabilidade.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'projeto_viabilidade_cia', 'class=texto size=1 style="width:280px;" onchange="javascript:mudar_om();"').'</div></td></tr>';

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


if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="projeto_viabilidade_dept" id="projeto_viabilidade_dept" value="'.($projeto_viabilidade_id ? $obj->projeto_viabilidade_dept : $demanda->demanda_dept).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($projeto_viabilidade_id ? $obj->projeto_viabilidade_dept : $demanda->demanda_dept)).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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

echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pela Demanda', 'Todo estudo de viabilidade deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_viabilidade_responsavel" name="projeto_viabilidade_responsavel" value="'.($obj->projeto_viabilidade_responsavel ? $obj->projeto_viabilidade_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->projeto_viabilidade_responsavel ? $obj->projeto_viabilidade_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';


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

$saida_patrocinadores='';
if (count($patrocinadores_selecionados)) {
		$saida_patrocinadores.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_patrocinadores.= '<tr><td>'.link_contato($patrocinadores_selecionados[0],'','','esquerda');
		$qnt_lista_patrocinadores=count($patrocinadores_selecionados);
		if ($qnt_lista_patrocinadores > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_patrocinadores; $i < $i_cmp; $i++) $lista.=link_contato($patrocinadores_selecionados[$i],'','','esquerda').'<br>';
				$saida_patrocinadores.= dica('Outros Patrocinadores', 'Clique para visualizar os demais patrocinadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_patrocinadores\');">(+'.($qnt_lista_patrocinadores - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_patrocinadores"><br>'.$lista.'</span>';
				}
		$saida_patrocinadores.= '</td></tr></table>';
		}
else $saida_patrocinadores.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Patrocinador', 'Qual contato foi elencado como patrocinador envolvido.').'Patrocinador:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_patrocinadores">'.$saida_patrocinadores.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar patrocinador.','popPatrocinadores()').'</td></tr></table></td></tr>';


$saida_contatos='';
if (count($contatos_selecionados)) {
		$saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_contatos.= '<tr><td>'.link_contato($contatos_selecionados[0],'','','esquerda');
		$qnt_lista_contatos=count($contatos_selecionados);
		if ($qnt_lista_contatos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
				$saida_contatos.= dica('Outros Interessados', 'Clique para visualizar os demais interessados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
				}
		$saida_contatos.= '</td></tr></table>';
		}
else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Parte Interessada', 'Qual contato foi elencado como parte interessada.').'Parte interessada:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_contatos">'.$saida_contatos.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar parte interessada.','popContatos()').'</td></tr></table></td></tr>';







if ($exibir['projeto_viabilidade_codigo'])echo '<tr><td align="right">'.dica('Código', 'Escreva, caso exista, o código do estudo de viabilidade.').'Código:'.dicaF().'</td><td><input type="text" style="width:284px;" class="texto" name="projeto_viabilidade_codigo" value="'.($obj->projeto_viabilidade_codigo ? $obj->projeto_viabilidade_codigo : $demanda->demanda_codigo).'" size="30" maxlength="255" /></td></tr>';
if ($exibir['projeto_viabilidade_ano']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Ano', 'A qual ano deverá o estudo de viabilidade estar relacionada.').'Ano:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="projeto_viabilidade_ano" value="'.($obj->projeto_viabilidade_ano ? $obj->projeto_viabilidade_ano : ($demanda->demanda_ano ? $demanda->demanda_ano : date('Y'))).'" size="4" class="texto" /></td></tr>';
$setor = array('' => '') + getSisValor('Setor');
if ($exibir['projeto_viabilidade_setor']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce o estudo de viabilidade.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'projeto_viabilidade_setor', 'style="width:284px;" class="texto" onchange="mudar_segmento();"', ($obj->projeto_viabilidade_setor ? $obj->projeto_viabilidade_setor : $demanda->demanda_setor)).'</td></tr>';
$segmento=array('' => '');
if (($obj->projeto_viabilidade_setor ? $obj->projeto_viabilidade_setor : $demanda->demanda_setor)){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="Segmento"');
	$sql->adOnde('sisvalor_chave_id_pai="'.($obj->projeto_viabilidade_setor ? $obj->projeto_viabilidade_setor : $demanda->demanda_setor).'"');
	$sql->adOrdem('sisvalor_valor');
	$segmento+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['projeto_viabilidade_segmento']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce o estudo de viabilidade.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'projeto_viabilidade_segmento', 'style="width:284px;" class="texto" onchange="mudar_intervencao();"', ($obj->projeto_viabilidade_segmento ? $obj->projeto_viabilidade_segmento : $demanda->demanda_segmento)).'</div></td></tr>';
$intervencao=array('' => '');
if (($obj->projeto_viabilidade_segmento ? $obj->projeto_viabilidade_segmento : $demanda->demanda_segmento)){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="Intervencao"');
	$sql->adOnde('sisvalor_chave_id_pai="'.($obj->projeto_viabilidade_segmento ? $obj->projeto_viabilidade_segmento : $demanda->demanda_segmento).'"');
	$sql->adOrdem('sisvalor_valor');
	$intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['projeto_viabilidade_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce o estudo de viabilidade.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'projeto_viabilidade_intervencao', 'style="width:284px;" class="texto" onchange="mudar_tipo_intervencao();"', ($obj->projeto_viabilidade_intervencao ? $obj->projeto_viabilidade_intervencao : $demanda->demanda_intervencao)).'</div></td></tr>';

$tipo_intervencao=array('' => '');
if (($obj->projeto_viabilidade_intervencao ? $obj->projeto_viabilidade_intervencao : $demanda->demanda_intervencao)){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
	$sql->adOnde('sisvalor_chave_id_pai="'.($obj->projeto_viabilidade_intervencao ? $obj->projeto_viabilidade_intervencao : $demanda->demanda_intervencao).'"');
	$sql->adOrdem('sisvalor_valor');
	$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['projeto_viabilidade_tipo_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence o estudo de viabilidade.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'projeto_viabilidade_tipo_intervencao', 'style="width:284px;" class="texto"', ($obj->projeto_viabilidade_tipo_intervencao ? $obj->projeto_viabilidade_tipo_intervencao : $demanda->demanda_tipo_intervencao)).'</div></td></tr>';

echo '<input type="hidden" name="projeto_viabilidade_data" value="'.($obj->projeto_viabilidade_data ? $obj->projeto_viabilidade_data  : date('Y-m-d H:i:s')).'" />';
if ($exibir['projeto_viabilidade_necessidade']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Necessidade', 'Descrever o problema que se deseja resolver por meio do projeto. Se possível, apresentar dados numéricos que deem sustentação à demanda (custos, desperdício de recursos etc).').'Necessidade:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_necessidade" style="width:800px;" class="textarea">'.($obj->projeto_viabilidade_necessidade ? $obj->projeto_viabilidade_necessidade : $demanda->demanda_identificacao).'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_alinhamento']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Alinhamento Estratégico', 'Descrever o alinhamento da demanda com os instrumentos de planejamento institucional. Esse item consta no Documento de Oficialização da Demanda (DOD) pode ser complementado/revisado neste documento.').'Alinhamento estratégico:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_alinhamento" style="width:800px;" class="textarea">'.($obj->projeto_viabilidade_alinhamento ? $obj->projeto_viabilidade_alinhamento : $demanda->demanda_alinhamento).'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_requisitos']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Requisitos Básicos', 'Descrever os principais requisitos identificados para o projeto, a partir da requisição da área solicitante. Os requisitos podem ser: de negócio, tecnológico, recursos humanos, legais, segurança, sociais, ambientais, culturais, etc.').'Requisitos básicos:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_requisitos" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_requisitos.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_solucoes']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Soluções Possíveis', 'Listar as possibilidades de atendimento da necessidade, com análise das vantagens e desvantagens de cada opção.').'Soluções possíveis:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_solucoes" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_solucoes.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_viabilidade_tecnica']) echo '<tr align="right" nowrap="nowrap"><td align="right">'.dica('Viabilidade Técnica', 'Avaliar a viabilidade técnica do projeto, observando a capacidade técnica da organização para realizar o projeto, estrutura física (material e estrutural) e de pessoal (conhecimento técnico).').'Viabilidade técnica:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_viabilidade_tecnica" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_viabilidade_tecnica.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_financeira']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Viabilidade Financeira', 'Levantar e avaliar os custos estimados para cada solução possível e verificar a disponibilidade orçamentária para a execução do projeto.').'Viabilidade financeira:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_financeira" style="width:800px;" class="textarea">'.($projeto_viabilidade_id ? $obj->projeto_viabilidade_financeira : $demanda->demanda_fonte_recurso).'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_institucional']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Viabilidade Institucional', 'Avaliar ambiente institucional, o que inclui o clima político e organizacional para a realização do projeto, identificando possíveis entraves e oportunidades, assim como o impacto dos resultados do projeto sobre as rotinas da instituição.').'Viabilidade institucional:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_institucional" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_institucional.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_solucao']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicação de Solução', 'Indicar a solução escolhida estimando o tempo  para implantação da solução e justificá-la, observando o alinhamento da estratégia da organização e a necessidade de negócio.').'Indicação de solução:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_solucao" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_solucao.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_continuidade']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer Sobre a Continuidade', 'Os envolvidos na elaboração deste documento deverão deliberar sobre a continuidade ou não do projeto e justificar. Obs. colocar a data da decisão e descrever o nome dos decisores e seus respectivos cargos.').'Parecer sobre a continuidade:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_continuidade" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_continuidade.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_tempo']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer Sobre o Tempo', 'Os envolvidos na elaboração deste documento deverão deliberar sobre o tempo necessário para a execução do projeto.').'Parecer sobre o tempo:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_tempo" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_tempo.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_custo']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer Sobre o Custo', 'Os envolvidos na elaboração deste documento deverão deliberar sobre o custo do projeto.').'Parecer sobre o custo:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_custo" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_custo.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_observacao']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Observações', 'Observações sobre o estudo de viabilidades.').'Observações:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_observacao" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_observacao.'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O estudo de viabilidade pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o estudo de viabilidade.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para o estudo de viabilidade podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para o estudo de viabilidade ver e editar o estudo de viabilidade</li><li><b>Privado</b> - Somente o responsável e os designados para o estudo de viabilidade podem ver o mesmo, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($projeto_viabilidade_acesso, 'projeto_viabilidade_acesso', 'class="texto"', ($projeto_viabilidade_id ? $obj->projeto_viabilidade_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="projeto_viabilidade_cor" value="'.($obj->projeto_viabilidade_cor ? $obj->projeto_viabilidade_cor : $demanda->demanda_cor).'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->projeto_viabilidade_cor ? $obj->projeto_viabilidade_cor : $demanda->demanda_cor).';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativo', 'Caso o estudo de viabilidade ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="projeto_viabilidade_ativo" '.($obj->projeto_viabilidade_ativo || !$projeto_viabilidade_id ? 'checked="checked"' : '').' /></td></tr>';

$campos_customizados = new CampoCustomizados('viabilidade', $projeto_viabilidade_id, 'editar');
$campos_customizados->imprimirHTML();

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/viabilidade_editar_pro.php';

echo '<tr><td style="height:3px;"></td></tr>';
echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_notificar\').style.display) document.getElementById(\'apresentar_notificar\').style.display=\'\'; else document.getElementById(\'apresentar_notificar\').style.display=\'none\';"><a class="aba" href="javascript: void(0);"><b>Notificar</b></a></td></tr>';
echo '<tr id="apresentar_notificar" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($projeto_viabilidade_id > 0 ? 'modificação' : 'criação').' do estudo de viabilidade.').'Notificar:'.dicaF().'</td>';
echo '<td>';
echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pelo Estudo de Viabilidade', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por este estudo de viabilidade.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para o Estudo de Viabilidade', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para este estudo de viabilidade.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro do estudo de viabilidade.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($projeto_viabilidade_id ? 'edição' : 'criação').' do estudo de viabilidade.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
echo '</table>';
echo '</form>';
echo estiloFundoCaixa();




?>
<script language="javascript">
function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&cias_id_selecionadas='+document.getElementById('projeto_viabilidade_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.projeto_viabilidade_cias.value = organizacao_id_string;
	document.getElementById('projeto_viabilidade_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('projeto_viabilidade_cias').value);
	__buildTooltip();
	}	
	
	
var contatos_id_selecionados = '<?php echo implode(",", $contatos_selecionados)?>';

function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, '<?php echo ucfirst($config["contatos"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.projeto_viabilidade_interessados.value = contato_id_string;
	contatos_id_selecionados = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados);
	__buildTooltip();
	}


var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.projeto_viabilidade_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.projeto_viabilidade_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}

var patrocinadores_id_selecionados = '<?php echo implode(",", $patrocinadores_selecionados)?>';

function popPatrocinadores() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Patrocinador', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setPatrocinadores&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&patrocinadores_id_selecionados='+patrocinadores_id_selecionados, window.setPatrocinadores, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setPatrocinadores&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&patrocinadores_id_selecionados='+patrocinadores_id_selecionados, 'Patrocinador','height=500,width=500,resizable,scrollbars=yes');
	}

function setPatrocinadores(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.projeto_viabilidade_patrocinadores.value = contato_id_string;
	patrocinadores_id_selecionados = contato_id_string;
	xajax_exibir_patrocinadores(patrocinadores_id_selecionados);
	__buildTooltip();
	}


function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('projeto_viabilidade_dept').value+'&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('projeto_viabilidade_dept').value+'&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('projeto_viabilidade_cia').value=cia_id;
	document.getElementById('projeto_viabilidade_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}



function mudar_segmento(){
	document.getElementById('projeto_viabilidade_intervencao').length=0;
	document.getElementById('projeto_viabilidade_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('projeto_viabilidade_setor').value, 'Segmento', 'projeto_viabilidade_segmento','combo_segmento', 'style="width:284px;" class="texto" size=1 onchange="mudar_intervencao();"');
	}

function mudar_intervencao(){
	document.getElementById('projeto_viabilidade_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('projeto_viabilidade_segmento').value, 'Intervencao', 'projeto_viabilidade_intervencao','combo_intervencao', 'style="width:284px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"');
	}

function mudar_tipo_intervencao(){
	xajax_mudar_ajax(document.getElementById('projeto_viabilidade_intervencao').value, 'TipoIntervencao', 'projeto_viabilidade_tipo_intervencao','combo_tipo_intervencao', 'style="width:284px;" class="texto" size=1');
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
	var objetivo_emails = document.getElementById('viabilidades_usuarios');
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
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&usuario_id='+document.getElementById('projeto_viabilidade_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&usuario_id='+document.getElementById('projeto_viabilidade_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('projeto_viabilidade_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_om(){
	var cia_id=document.getElementById('projeto_viabilidade_cia').value;
	xajax_selecionar_om_ajax(cia_id,'projeto_viabilidade_cia','combo_cia', 'class="texto" size=1 style="width:280px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir este estudo de viabilidade?")) {
		var f = document.env;
		f.excluir.value=1;
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.projeto_viabilidade_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.projeto_viabilidade_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.projeto_viabilidade_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.projeto_viabilidade_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}

</script>

