<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;

$Aplic->carregarCKEditorJS();


require_once BASE_DIR.'/modulos/projetos/termo_abertura.class.php';
require_once BASE_DIR.'/modulos/projetos/demanda.class.php';
require_once BASE_DIR.'/modulos/projetos/viabilidade.class.php';
require_once $Aplic->getClasseSistema('CampoCustomizados');

$Aplic->carregarCalendarioJS();
$projeto_abertura_id =getParam($_REQUEST, 'projeto_abertura_id', null);
$projeto_viabilidade_id =getParam($_REQUEST, 'projeto_viabilidade_id', null);
$demanda_id =getParam($_REQUEST, 'demanda_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;


$projeto_viabilidade = new CViabilidade();
$demanda = new CDemanda();


$obj = new CTermoAbertura();
if ($projeto_abertura_id){
	$obj->load($projeto_abertura_id);
	$cia_id=$obj->projeto_abertura_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
	}


if (!$demanda_id && $obj->projeto_abertura_demanda)$demanda_id=$obj->projeto_abertura_demanda;
if ($demanda_id) $demanda->load($demanda_id);
if (!$projeto_viabilidade_id) $projeto_viabilidade_id=$demanda->demanda_viabilidade;
$projeto_viabilidade->load($projeto_viabilidade_id);
if (!$demanda_id && $projeto_viabilidade->projeto_viabilidade_demanda) {
	$demanda->load($projeto_viabilidade->projeto_viabilidade_demanda);
	$demanda_id=$projeto_viabilidade->projeto_viabilidade_demanda;
	}

$editar=permiteEditarTermoAbertura($obj->projeto_abertura_acesso, $projeto_abertura_id);

if(!($editar && ($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') || $Aplic->usuario_super_admin))) $Aplic->redirecionar('m=publico&a=acesso_negado');


$projeto_viabilidade_acesso = getSisValor('NivelAcesso','','','sisvalor_id');


$df = '%d/%m/%Y';

$botoesTitulo = new CBlocoTitulo(($projeto_abertura_id ? 'Editar Termo de Abertura' : 'Criar Termo de Abertura'), 'anexo_projeto.png', $m, $m.'.'.$a);

if (!$Aplic->profissional){
	if ($projeto_abertura_id) $botoesTitulo->adicionaBotao('m='.$m.'&a=termo_abertura_ver&projeto_abertura_id='.$projeto_abertura_id, 'ver', '', 'Ver os Detalhes', 'Visualizar os detalhes deste termo de abertura.');
	if ($projeto_abertura_id && $editar && ($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') || $Aplic->usuario_super_admin))	$botoesTitulo->adicionaBotaoExcluir('excluir', $projeto_abertura_id, '', 'Excluir Termo de Abertura', 'Excluir este termo de abertura.' );
	}


$botoesTitulo->mostrar();
$lista_usuarios =array();
$lista_patrocinadores = array();
$lista_interessados = array();
$depts_selecionados=array();
$cias_selecionadas=array();
if ($projeto_abertura_id) {
	$sql->adTabela('projeto_abertura_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$lista_usuarios = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_abertura_patrocinadores');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$lista_patrocinadores = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_abertura_interessados');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$lista_interessados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_abertura_dept');
	$sql->adCampo('projeto_abertura_dept_dept');
	$sql->adOnde('projeto_abertura_dept_projeto_abertura = '.(int)$projeto_abertura_id);
	$depts_selecionados=$sql->carregarColuna();
	$sql->limpar();
	
	
	if ($Aplic->profissional){
		$sql->adTabela('projeto_abertura_cia');
		$sql->adCampo('projeto_abertura_cia_cia');
		$sql->adOnde('projeto_abertura_cia_projeto_abertura = '.(int)$projeto_abertura_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}
else{
	$sql->adTabela('projeto_viabilidade_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
	$lista_usuarios = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_viabilidade_patrocinadores');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
	$lista_patrocinadores = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_viabilidade_interessados');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
	$lista_interessados = $sql->carregarColuna();
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


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_termo_abertura" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="projeto_abertura_id" id="projeto_abertura_id" value="'.$projeto_abertura_id.'" />';
echo '<input name="projeto_abertura_usuarios" type="hidden" value="'.implode(',', $lista_usuarios).'" />';
echo '<input name="projeto_abertura_patrocinadores" type="hidden" value="'.implode(',', $lista_patrocinadores).'" />';
echo '<input name="projeto_abertura_interessados" type="hidden" value="'.implode(',', $lista_interessados).'" />';
echo '<input name="projeto_abertura_depts" id="projeto_abertura_depts" type="hidden" value="'.implode(',',$depts_selecionados).'" />';
echo '<input name="projeto_abertura_cias"  id="projeto_abertura_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input name="projeto_abertura_demanda" type="hidden" value="'.($obj->projeto_abertura_demanda ? $obj->projeto_abertura_demanda : $demanda_id).'" />';
echo '<input type="hidden" name="projeto_abertura_projeto" value="'.$obj->projeto_abertura_projeto.'" />';
echo '<input type="hidden" name="projeto_abertura_aprovado" value="'.$obj->projeto_abertura_aprovado.'" />';

echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';
echo '<tr><td><table cellspacing=0 cellpadding=0 style="width:800px;"><tr><td>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Possível Nome d'.$config['genero_projeto'].' '.$config['projeto'], 'Tod'.$config['genero_projeto'].' '.$config['projeto'].' necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome d'.$config['genero_projeto'].' '.$config['projeto'].':'.dicaF().'</td><td><input type="text" name="projeto_abertura_nome" value="'.($obj->projeto_abertura_nome ? $obj->projeto_abertura_nome : $projeto_viabilidade->projeto_viabilidade_nome).'" style="width:600px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'Qual '.$config['organizacao'].' é responsável por este termo de abertura.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'projeto_abertura_cia', 'class=texto size=1 style="width:280px;" onchange="javascript:mudar_om();"').'</div></td></tr>';

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


if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="projeto_abertura_dept" id="projeto_abertura_dept" value="'.($projeto_abertura_id ? $obj->projeto_abertura_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($projeto_abertura_id ? $obj->projeto_abertura_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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


echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pela Minuta', 'Toda minuta de termo de abertura deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_abertura_responsavel" name="projeto_abertura_responsavel" value="'.($obj->projeto_abertura_responsavel ? $obj->projeto_abertura_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om(($obj->projeto_abertura_responsavel ? $obj->projeto_abertura_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';


$saida_usuarios='';
if (count($lista_usuarios)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($lista_usuarios[0],'','','esquerda');
		$qnt_lista_usuarios=count($lista_usuarios);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($lista_usuarios[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s Designados', 'Clique para visualizar '.$config['genero_usuario'].'s demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';



$saida_contatos='';
if (count($lista_patrocinadores)) {
		$saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_contatos.= '<tr><td>'.link_contato($lista_patrocinadores[0],'','','esquerda');
		$qnt_lista_contatos=count($lista_patrocinadores);
		if ($qnt_lista_contatos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($lista_patrocinadores[$i],'','','esquerda').'<br>';
				$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.$config['contatos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
				}
		$saida_contatos.= '</td></tr></table>';
		}
else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Patrocinadores', 'Quais '.strtolower($config['contatos']).' estão envolvid'.$config['genero_contato'].'s como patrocinadores.').'Patrocinadores:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_patrocinadores">'.$saida_contatos.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popPatrocinadores()').'</td></tr></table></td></tr>';


$saida_contatos2='';
if (count($lista_interessados)) {
		$saida_contatos2.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_contatos2.= '<tr><td>'.link_contato($lista_interessados[0],'','','esquerda');
		$qnt_lista_contatos=count($lista_interessados);
		if ($qnt_lista_contatos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($lista_interessados[$i],'','','esquerda').'<br>';
				$saida_contatos2.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.$config['contatos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
				}
		$saida_contatos2.= '</td></tr></table>';
		}
else $saida_contatos2.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Partes Interessadas', 'Quais '.strtolower($config['contatos']).' estão envolvid'.$config['genero_contato'].'s como partes interessadas.').'Partes interessadas:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_interessados">'.$saida_contatos2.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popInteressados()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Quem Aprova', 'Todo termo de abertura deve ter um responsável pela aprovação da minuta.').'Quem aprova:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_abertura_autoridade" name="projeto_abertura_autoridade" value="'.($obj->projeto_abertura_autoridade ? $obj->projeto_abertura_autoridade : ($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'abertura') ?  $Aplic->usuario_id : '')).'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_om(($obj->projeto_abertura_autoridade ? $obj->projeto_abertura_autoridade : ($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'abertura') ?  $Aplic->usuario_id : '')),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Gerente do Projeto', 'O gerente do projeto a ser criado.').'Gerente:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_abertura_gerente_projeto" name="projeto_abertura_gerente_projeto" value="'.($obj->projeto_abertura_gerente_projeto ? $obj->projeto_abertura_gerente_projeto : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->projeto_abertura_gerente_projeto ? $obj->projeto_abertura_gerente_projeto : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';



$viavel=array(1=>'Sim', -1=>'Não');

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'abertura\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


if ($exibir['projeto_abertura_codigo'])echo '<tr><td align="right" nowrap="nowrap">'.dica('Código', 'Escreva, caso exista, o código do termo de abertura.').'Código:'.dicaF().'</td><td><input type="text" style="width:284px;" class="texto" name="projeto_abertura_codigo" value="'.($obj->projeto_abertura_codigo ? $obj->projeto_abertura_codigo : $projeto_viabilidade->projeto_viabilidade_codigo).'" size="30" maxlength="255" /></td></tr>';
if ($exibir['projeto_abertura_ano']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Ano', 'A qual ano deverá o termo de abertura estar relacionada.').'Ano:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="projeto_abertura_ano" value="'.($obj->projeto_abertura_ano ? $obj->projeto_abertura_ano : ($projeto_viabilidade->projeto_viabilidade_ano ? $projeto_viabilidade->projeto_viabilidade_ano : date('Y'))).'" size="4" class="texto" /></td></tr>';
$setor = array('' => '') + getSisValor('Setor');
if ($exibir['projeto_abertura_setor']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce o termo de abertura.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'projeto_abertura_setor', 'style="width:284px;" class="texto" onchange="mudar_segmento();"', ($obj->projeto_abertura_setor ? $obj->projeto_abertura_setor : $projeto_viabilidade->projeto_viabilidade_setor)).'</td></tr>';
$segmento=array('' => '');
if (($obj->projeto_abertura_setor ? $obj->projeto_abertura_setor : $projeto_viabilidade->projeto_viabilidade_setor)){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="Segmento"');
	$sql->adOnde('sisvalor_chave_id_pai="'.($obj->projeto_abertura_setor ? $obj->projeto_abertura_setor : $projeto_viabilidade->projeto_viabilidade_setor).'"');
	$sql->adOrdem('sisvalor_valor');
	$segmento+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['projeto_abertura_segmento']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce o termo de abertura.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'projeto_abertura_segmento', 'style="width:284px;" class="texto" onchange="mudar_intervencao();"', ($obj->projeto_abertura_segmento ? $obj->projeto_abertura_segmento : $projeto_viabilidade->projeto_viabilidade_segmento)).'</div></td></tr>';
$intervencao=array('' => '');
if (($obj->projeto_abertura_segmento ? $obj->projeto_abertura_segmento : $projeto_viabilidade->projeto_viabilidade_segmento)){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="Intervencao"');
	$sql->adOnde('sisvalor_chave_id_pai="'.($obj->projeto_abertura_segmento ? $obj->projeto_abertura_segmento : $projeto_viabilidade->projeto_viabilidade_segmento).'"');
	$sql->adOrdem('sisvalor_valor');
	$intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['projeto_abertura_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce o termo de abertura.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'projeto_abertura_intervencao', 'style="width:284px;" class="texto" onchange="mudar_tipo_intervencao();"', ($obj->projeto_abertura_intervencao ? $obj->projeto_abertura_intervencao : $projeto_viabilidade->projeto_viabilidade_intervencao)).'</div></td></tr>';

$tipo_intervencao=array('' => '');
if (($obj->projeto_abertura_intervencao ? $obj->projeto_abertura_intervencao : $projeto_viabilidade->projeto_viabilidade_intervencao)){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
	$sql->adOnde('sisvalor_chave_id_pai="'.($obj->projeto_abertura_intervencao ? $obj->projeto_abertura_intervencao : $projeto_viabilidade->projeto_viabilidade_intervencao).'"');
	$sql->adOrdem('sisvalor_valor');
	$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['projeto_abertura_tipo_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence o termo de abertura.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'projeto_abertura_tipo_intervencao', 'style="width:284px;" class="texto"', ($obj->projeto_abertura_tipo_intervencao ? $obj->projeto_abertura_tipo_intervencao : $projeto_viabilidade->projeto_viabilidade_tipo_intervencao)).'</div></td></tr>';

echo '<input type="hidden" name="projeto_abertura_data" value="'.$obj->projeto_abertura_data.'" />';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="projeto_abertura_cor" value="'.($obj->projeto_abertura_cor ? $obj->projeto_abertura_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->projeto_abertura_cor ? $obj->projeto_abertura_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O termo de abertura pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o termo de abertura.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para o termo de abertura podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para o termo de abertura ver e editar o termo de abertura</li><li><b>Privado</b> - Somente o responsável e os designados para o termo de abertura podem ver o mesmo, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($projeto_viabilidade_acesso, 'projeto_abertura_acesso', 'class="texto"', ($projeto_abertura_id ? $obj->projeto_abertura_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
if ($exibir['projeto_abertura_justificativa']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Justificativa', 'Descrever de forma clara a justificativa contendo um breve histórico e as motivações do projeto. .').'Justificativa:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_justificativa" style="width:800px;" class="textarea">'.($obj->projeto_abertura_justificativa ? $obj->projeto_abertura_justificativa : $demanda->demanda_justificativa).'</textarea></td></tr>';
if ($exibir['projeto_abertura_objetivo']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Objetivo', 'Descrever qual o objetivo para a qual órgão está realizando o projeto, que pode ser: descrição concreta de que o projeto quer alcançar, uma posição estratégica a ser alcançada, um resultado a ser obtido, um produto a ser produzido ou um serviço a ser realizado. Os objetivos devem ser específicos, mensuráveis, realizáveis, realísticos, e baseados no tempo.>.').'Objetivo:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_objetivo" style="width:800px;" class="textarea">'.$obj->projeto_abertura_objetivo.'</textarea></td></tr>';
if ($exibir['projeto_abertura_escopo']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Declaração de Escopo', 'Descrever a declaração do escopo, que inclui as principais entregas, fornece uma base documentada para futuras decisões do projeto e para confirmar ou desenvolver um entendimento comum do escopo do projeto entre as partes interessadas.').'Declaração de Escopo:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_escopo" style="width:800px;" class="textarea">'.$obj->projeto_abertura_escopo.'</textarea></td></tr>';
if ($exibir['projeto_abertura_nao_escopo']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Não escopo', 'Descrever de forma explícita o que está excluído do projeto, para evitar que uma parte interessada possa supor que um produto, serviço ou resultado específico é um produto do projeto.').'Não escopo:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_nao_escopo" style="width:800px;" class="textarea">'.$obj->projeto_abertura_nao_escopo.'</textarea></td></tr>';
if ($exibir['projeto_abertura_tempo']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Tempo estimado', 'Descrever a estimativa de tempo para finalizar o projeto.').'Tempo estimado:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_tempo" style="width:800px;" class="textarea">'.$obj->projeto_abertura_tempo.'</textarea></td></tr>';
if ($exibir['projeto_abertura_custo']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Custos Estimado e Fonte de Recurso', 'Descrever a estimativa de custo do projeto e a fonte de recurso.').'Custos estimado e fonte de recurso:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_custo" style="width:800px;" class="textarea">'.($obj->projeto_abertura_custo ? $obj->projeto_abertura_custo : $demanda->demanda_fonte_recurso).'</textarea></td></tr>';
if ($exibir['projeto_abertura_premissas']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Premissas', 'Descrever as premissas do projeto. As premissas são fatores que, para fins de planejamento, são considerados verdadeiros, reais ou certos sem prova ou demonstração. As premissas afetam todos os aspectos do planejamento do projeto e fazem parte da elaboração progressiva do projeto. Frequentemente, as equipes do projeto identificam, documentam e validam as premissas durante o processo de planejamento. Geralmente, as premissas envolvem um grau de risco.').'Premissas:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_premissas" style="width:800px;" class="textarea">'.$obj->projeto_abertura_premissas.'</textarea></td></tr>';
if ($exibir['projeto_abertura_restricoes']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Restrições', 'Descrever as restrições do projeto. Uma restrição é uma limitação aplicável, interna ou externa ao projeto, que afetará o desempenho do projeto ou de um processo. Por exemplo, uma restrição do cronograma é qualquer limitação ou condição colocada em relação ao cronograma do projeto que afeta o momento em que uma atividade do cronograma pode ser agendada e geralmente está na forma de datas impostas fixas.').'Restrições:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_restricoes" style="width:800px;" class="textarea">'.$obj->projeto_abertura_restricoes.'</textarea></td></tr>';
if ($exibir['projeto_abertura_riscos']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Riscos Previamente Identificados', 'Identificar eventos ou condições incertos que, se ocorrerem, provocarão efeitos positivos ou negativos nos objetivos do projeto.').'Riscos previamente identificados:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_riscos" style="width:800px;" class="textarea">'.$obj->projeto_abertura_riscos.'</textarea></td></tr>';








if ($exibir['projeto_abertura_infraestrutura']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Infraestrutura', 'Identificar previamente a infraestrutura para o atingimento dos objetivos do projeto, exemplo, salas, servidores, notebook etc.').'Infraestrutura:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_infraestrutura" style="width:800px;" class="textarea">'.$obj->projeto_abertura_infraestrutura.'</textarea></td></tr>';
if ($exibir['projeto_abertura_observacao']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Observações', 'Observações sobre o termo de abertura.').'Observações:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_observacao" style="width:800px;" class="textarea">'.$obj->projeto_abertura_observacao.'</textarea></td></tr>';

if ($obj->projeto_abertura_recusa) echo '<tr><td align="right" nowrap="nowrap">'.dica('Justificativa', 'Justificativa para a recusa em aprovar o termo de abertura.').'Justificativa da não aprovação:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_recusa" id="projeto_abertura_recusa" style="width:800px;" class="textarea">'.$obj->projeto_abertura_recusa.'</textarea></td></tr>';

echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o termo de abertura esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="projeto_abertura_ativo" '.($obj->projeto_abertura_ativo || !$projeto_abertura_id ? 'checked="checked"' : '').' /></td></tr>';


$campos_customizados = new CampoCustomizados('termo_abertura', $projeto_abertura_id, 'editar');
$campos_customizados->imprimirHTML();


echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($projeto_abertura_id > 0 ? 'modificação' : 'criação').' do termo de abertura.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pelo Termo de Abertura', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por este termo de abertura.').'<label for="email_responsavel">Responsável pelo termo de abertura</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para o Termo de Abertura', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para este termo de abertura.').'<label for="email_designados">Designados para o termo de abertura</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro do termo de abertura.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';


echo '<tr><td><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($projeto_abertura_id ? 'edição' : 'criação').' do termo de abertura.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">
	
function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('projeto_abertura_cia').value+'&cias_id_selecionadas='+document.getElementById('projeto_abertura_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.projeto_abertura_cias.value = organizacao_id_string;
	document.getElementById('projeto_abertura_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('projeto_abertura_cias').value);
	__buildTooltip();
	}		
	
function mudar_segmento(){
	document.getElementById('projeto_abertura_intervencao').length=0;
	document.getElementById('projeto_abertura_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('projeto_abertura_setor').value, 'Segmento', 'projeto_abertura_segmento','combo_segmento', 'style="width:284px;" class="texto" size=1 onchange="mudar_intervencao();"');
	}

function mudar_intervencao(){
	document.getElementById('projeto_abertura_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('projeto_abertura_segmento').value, 'Intervencao', 'projeto_abertura_intervencao','combo_intervencao', 'style="width:284px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"');
	}

function mudar_tipo_intervencao(){
	xajax_mudar_ajax(document.getElementById('projeto_abertura_intervencao').value, 'TipoIntervencao', 'projeto_abertura_tipo_intervencao','combo_tipo_intervencao', 'style="width:284px;" class="texto" size=1');
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



function popResponsavel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('projeto_abertura_cia').value+'&usuario_id='+document.getElementById('projeto_abertura_responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('projeto_abertura_cia').value+'&usuario_id='+document.getElementById('projeto_abertura_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('projeto_abertura_responsavel').value=usuario_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('projeto_abertura_cia').value+'&usuario_id='+document.getElementById('projeto_abertura_gerente_projeto').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('projeto_abertura_cia').value+'&usuario_id='+document.getElementById('projeto_abertura_gerente_projeto').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('projeto_abertura_gerente_projeto').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}



function popAutoridade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["autoridade"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('projeto_abertura_cia').value+'&usuario_id='+document.getElementById('projeto_abertura_autoridade').value, window.setAutoridade, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('projeto_abertura_cia').value+'&usuario_id='+document.getElementById('projeto_abertura_autoridade').value, 'Autoridade','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('projeto_abertura_autoridade').value=usuario_id;
	document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_om(){
	var cia_id=document.getElementById('projeto_abertura_cia').value;
	xajax_selecionar_om_ajax(cia_id,'projeto_abertura_cia','combo_cia', 'class="texto" size=1 style="width:280px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir este termo de abertura?")) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_termo_abertura';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.projeto_abertura_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.projeto_abertura_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.projeto_abertura_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.projeto_abertura_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}




var lista_usuarios = '<?php echo implode(",", $lista_usuarios)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('projeto_abertura_cia').value+'&usuarios_id_selecionados='+lista_usuarios, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('projeto_abertura_cia').value+'&usuarios_id_selecionados='+lista_usuarios, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.projeto_abertura_usuarios.value = usuario_id_string;
	lista_usuarios = usuario_id_string;
	xajax_exibir_usuarios(lista_usuarios);
	__buildTooltip();
	}



var lista_interessados = '<?php echo implode(",", $lista_interessados)?>';

function popInteressados() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('projeto_abertura_cia').value+'&contatos_id_selecionados='+lista_interessados, window.setInteressados, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setInteressados&cia_id='+document.getElementById('projeto_abertura_cia').value+'&contatos_id_selecionados='+lista_interessados, '<?php echo ucfirst($config["contatos"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setInteressados(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.projeto_abertura_interessados.value = contato_id_string;
	lista_interessados = contato_id_string;
	xajax_exibir_contatos(lista_interessados, 'combo_interessados');
	__buildTooltip();
	}



var lista_patrocinadores = '<?php echo implode(",", $lista_patrocinadores)?>';

function popPatrocinadores() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('projeto_abertura_cia').value+'&contatos_id_selecionados='+lista_patrocinadores, window.setPatrocinadores, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setPatrocinadores&cia_id='+document.getElementById('projeto_abertura_cia').value+'&contatos_id_selecionados='+lista_patrocinadores, '<?php echo ucfirst($config["contatos"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setPatrocinadores(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.projeto_abertura_patrocinadores.value = contato_id_string;
	lista_patrocinadores = contato_id_string;
	xajax_exibir_contatos(lista_patrocinadores, 'combo_patrocinadores');
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('projeto_abertura_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('projeto_abertura_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.projeto_abertura_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}


function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('projeto_abertura_dept').value+'&cia_id='+document.getElementById('projeto_abertura_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('projeto_abertura_dept').value+'&cia_id='+document.getElementById('projeto_abertura_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('projeto_abertura_cia').value=cia_id;
	document.getElementById('projeto_abertura_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

</script>

