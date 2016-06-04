<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
if (!$Aplic->checarModulo('relatorios', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

include_once BASE_DIR.'/localidades/pt/relatorios.php';


$portfolio = 0;
$portfolio_pai = 0;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

$self_print = isset($_REQUEST['self_print']) ? (int)getParam($_REQUEST, 'self_print', null) : 1;

$relatorio_tipo=getParam($_REQUEST, 'relatorio_tipo', '');

$sql = new BDConsulta;
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'projeto\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

if (isset($_REQUEST['projeto_id'])) $Aplic->setEstado('projeto_id', getParam($_REQUEST, 'projeto_id', null));
$projeto_id = $Aplic->getEstado('projeto_id', 0);

if (isset($_REQUEST['projtextobusca'])) $Aplic->setEstado('projeto_tipo', getParam($_REQUEST, 'projeto_tipo', null));
$projeto_tipo = $Aplic->getEstado('projeto_tipo') !== null ? $Aplic->getEstado('projeto_tipo') : -1;

if (isset($_REQUEST['nao_apenas_superiores'])) $Aplic->setEstado('nao_apenas_superiores', getParam($_REQUEST, 'nao_apenas_superiores', null));
$nao_apenas_superiores = $Aplic->getEstado('nao_apenas_superiores') !== null ? $Aplic->getEstado('nao_apenas_superiores') : 0;

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ListaProjetoTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ListaProjetoTab') !== null ? $Aplic->getEstado('ListaProjetoTab') : 0;

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projetostatus', getParam($_REQUEST, 'projetostatus', null));
$projetostatus = $Aplic->getEstado('projetostatus') !== null ? $Aplic->getEstado('projetostatus') : 0;

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projeto_favorito', getParam($_REQUEST, 'favorito_id', null));
$favorito_id = $Aplic->getEstado('projeto_favorito') !== null ? $Aplic->getEstado('projeto_favorito') : 0;

if (isset($_REQUEST['estado_sigla']))	$Aplic->setEstado('estado_sigla', getParam($_REQUEST, 'estado_sigla', null));
$estado_sigla = ($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : '');

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('municipio_id', getParam($_REQUEST, 'municipio_id', null));
$municipio_id = ($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '');

if (isset($_REQUEST['responsavel']))	$Aplic->setEstado('responsavel', getParam($_REQUEST, 'responsavel', null));
$responsavel = $Aplic->getEstado('responsavel') !== null ? $Aplic->getEstado('responsavel') : 0;

if (isset($_REQUEST['supervisor']))	$Aplic->setEstado('supervisor', getParam($_REQUEST, 'supervisor', null));
$supervisor = $Aplic->getEstado('supervisor') !== null ? $Aplic->getEstado('supervisor') : 0;

if (isset($_REQUEST['autoridade']))	$Aplic->setEstado('autoridade', getParam($_REQUEST, 'autoridade', null));
$autoridade = $Aplic->getEstado('autoridade') !== null ? $Aplic->getEstado('autoridade') : 0;

if (isset($_REQUEST['designado']))	$Aplic->setEstado('designado', getParam($_REQUEST, 'designado', null));
$designado = $Aplic->getEstado('designado') !== null ? $Aplic->getEstado('designado') : 0;

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projeto_setor',getParam($_REQUEST, 'projeto_setor', null));
$projeto_setor = $Aplic->getEstado('projeto_setor') !== null ? $Aplic->getEstado('projeto_setor') : '';

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projeto_segmento',getParam($_REQUEST, 'projeto_segmento', null));
$projeto_segmento = $Aplic->getEstado('projeto_segmento') !== null ? $Aplic->getEstado('projeto_segmento') : '';

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projeto_intervencao', getParam($_REQUEST, 'projeto_intervencao', null));
$projeto_intervencao = $Aplic->getEstado('projeto_intervencao') !== null ? $Aplic->getEstado('projeto_intervencao') : '';

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projeto_tipo_intervencao', getParam($_REQUEST, 'projeto_tipo_intervencao', null));
$projeto_tipo_intervencao = $Aplic->getEstado('projeto_tipo_intervencao') !== null ? $Aplic->getEstado('projeto_tipo_intervencao') : '';

if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projtextobusca', getParam($_REQUEST, 'projtextobusca', ''));
$pesquisar_texto = $Aplic->getEstado('projtextobusca') !== null ? $Aplic->getEstado('projtextobusca') : '';

if (isset($_REQUEST['filtro_criterio']))	$Aplic->setEstado('filtro_criterio', getParam($_REQUEST, 'filtro_criterio', null));
$filtro_criterio = $Aplic->getEstado('filtro_criterio') !== null ? $Aplic->getEstado('filtro_criterio') : 0;

if (isset($_REQUEST['filtro_perspectiva']))	$Aplic->setEstado('filtro_perspectiva', getParam($_REQUEST, 'filtro_perspectiva', null));
$filtro_perspectiva = $Aplic->getEstado('filtro_perspectiva') !== null ? $Aplic->getEstado('filtro_perspectiva') : 0;

if (isset($_REQUEST['filtro_tema']))	$Aplic->setEstado('filtro_tema', getParam($_REQUEST, 'filtro_tema', null));
$filtro_tema = $Aplic->getEstado('filtro_tema') !== null ? $Aplic->getEstado('filtro_tema') : 0;

if (isset($_REQUEST['filtro_objetivo']))	$Aplic->setEstado('filtro_objetivo', getParam($_REQUEST, 'filtro_objetivo', null));
$filtro_objetivo = $Aplic->getEstado('filtro_objetivo') !== null ? $Aplic->getEstado('filtro_objetivo') : 0;

if (isset($_REQUEST['filtro_fator']))	$Aplic->setEstado('filtro_fator', getParam($_REQUEST, 'filtro_fator', null));
$filtro_fator = $Aplic->getEstado('filtro_fator') !== null ? $Aplic->getEstado('filtro_fator') : 0;

if (isset($_REQUEST['filtro_estrategia']))	$Aplic->setEstado('filtro_estrategia', getParam($_REQUEST, 'filtro_estrategia', null));
$filtro_estrategia = $Aplic->getEstado('filtro_estrategia') !== null ? $Aplic->getEstado('filtro_estrategia') : 0;

if (isset($_REQUEST['filtro_meta']))	$Aplic->setEstado('filtro_meta', getParam($_REQUEST, 'filtro_meta', null));
$filtro_meta = $Aplic->getEstado('filtro_meta') !== null ? $Aplic->getEstado('filtro_meta') : 0;

$projetos_status=array();
if (!$Aplic->profissional) $projetos_status[0]='&nbsp;';
$projetos_status[-1]='Ativos';
$projetos_status[-2]='Inativos';
$projetos_status += getSisValor('StatusProjeto');

$projeto_tipos=array();
if(!$Aplic->profissional) $projeto_tipos[-1] = '';
$projeto_tipos += getSisValor('TipoProjeto');


$estado=array(0 => '&nbsp;');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();

$sql->adTabela('favoritos');
$sql->adCampo('favorito_id, descricao');
$sql->adOnde('projeto=1');
$sql->adOnde('criador_id='.$Aplic->usuario_id);
$vetor_favoritos=$sql->ListaChave();
$sql->limpar();

$favoritos='';
if (count($vetor_favoritos)) {
	if (!$Aplic->profissional) $vetor_favoritos[0]='';
	$favoritos='<tr><td align="right" nowrap="nowrap">'.dica('Favoritos', 'Escolha um grupo de favorit'.$config['genero_projeto'].'s para mostrar '.$config['genero_projeto'].'s '.$config['projeto'].' pertencentes.').'Favoritos:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($vetor_favoritos, 'favorito_id', 'class="texto"'.($Aplic->profissional ? ' multiple' :'').' style="width:200px;"', $favorito_id).'</td></tr>';
	}

$projeto_expandido =getParam($_REQUEST, 'projeto_expandido', 0);

if ($favorito_id) $projeto_expandido=0;

if (isset($_REQUEST['cia_dept']) && $_REQUEST['cia_dept'])	$Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_dept', null));
else if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;


if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
if ($dept_id) $ver_subordinadas = null;

$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

if (isset($_REQUEST['ver_dept_subordinados'])) $Aplic->setEstado('ver_dept_subordinados', getParam($_REQUEST, 'ver_dept_subordinados', null));
$ver_dept_subordinados = ($Aplic->getEstado('ver_dept_subordinados') !== null ? $Aplic->getEstado('ver_dept_subordinados') : (($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) ? $Aplic->usuario_prefs['ver_dept_subordinados'] : 0));
if ($ver_subordinadas) $ver_dept_subordinados=0;

$lista_depts='';
if ($ver_dept_subordinados){
	$vetor_depts=array();
	lista_depts_subordinados($dept_id, $vetor_depts);
	$vetor_depts[]=$dept_id;
	$lista_depts=implode(',',$vetor_depts);
	}

if(!$dialogo){
  echo '<form name="env" id="env" method="post">';
  echo '<input type="hidden" name="m" value="'.$m.'" />';
  echo '<input type="hidden" name="a" value="'.$a.'" />';
  echo '<input type="hidden" name="u" value="" />';
  echo '<input type="hidden" name="dialogo" id="dialogo" value="'.$dialogo.'" />';
  echo '<input type="hidden" name="pdf" id="pdf" value="" />';
  echo '<input type="hidden" name="sem_cabecalho" id="sem_cabecalho" value="" />';
  echo '<input type="hidden" name="page_orientation" value="" />';

  echo '<input type="hidden" name="projeto_expandido" value="'.$projeto_expandido.'" />';
  echo '<input type="hidden" name="nao_apenas_superiores" value="'.$nao_apenas_superiores.'" />';
  echo '<input type="hidden" name="relatorio_tipo" value="'.$relatorio_tipo.'" />';
  echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';

  echo '<input type="hidden" name="cia_dept" value="" />';
  echo '<input type="hidden" id="ver_subordinadas" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
  echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';


  echo '<input type="hidden" name="filtro_criterio" id="filtro_criterio" value="'.$filtro_criterio.'" />';
  echo '<input type="hidden" name="filtro_perspectiva" id="filtro_perspectiva" value="'.$filtro_perspectiva.'" />';
  echo '<input type="hidden" name="filtro_tema" id="filtro_tema" value="'.$filtro_tema.'" />';
  echo '<input type="hidden" name="filtro_objetivo" id="filtro_objetivo" value="'.$filtro_objetivo.'" />';
  echo '<input type="hidden" name="filtro_fator" id="filtro_fator" value="'.$filtro_fator.'" />';
  echo '<input type="hidden" name="filtro_estrategia" id="filtro_estrategia" value="'.$filtro_estrategia.'" />';
  echo '<input type="hidden" name="filtro_meta" id="filtro_meta" value="'.$filtro_meta.'" />';

  echo '<input type="hidden" name="jquery" value="1" />';


	$mudar_projeto='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Filtrar arquivos pel'.$config['genero_projeto'].' '.$config['projeto'].' que estão relacionados.').ucfirst($config['projeto']).':'.dicaF().'</td><td><input type="text" id="nome" name="nome" value="'.nome_projeto($projeto_id).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr>';


	$procurar_estado='<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:200px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
	$procurar_municipio='<tr><td align="right">'.dica('Município', 'Selecione o município d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto"'.($Aplic->profissional ? ' multiple' :'').' style="width:200px;"', '', $municipio_id, true, false).'</div></td></tr>';
	$procurar_status='<tr><td nowrap="nowrap" align="right">'.dica('Status d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Filtre '.$config['genero_projeto'].'s '.$config['projetos'].' pelo status d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').'Status:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($projetos_status, 'projetostatus', 'size="1" style="width:200px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $projetostatus) .'</td></tr>';
	$procura_categoria='<tr><td nowrap="nowrap" align="right">'.dica('Categoria de '.ucfirst($config['projeto']), 'Filtre '.$config['genero_projeto'].'s '.$config['projetos'].' pela categoria  d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').'Categoria:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($projeto_tipos, 'projeto_tipo', 'size="1" style="width:200px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $projeto_tipo) .'</td></tr>';
	$procura_pesquisa='<tr><td nowrap="nowrap" align="right">'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" class="texto" style="width:200px;" id="projtextobusca" name="projtextobusca" onChange="document.env.submit();" value='."'$pesquisar_texto'".'/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&projtextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:200px;" onchange="javascript:mudar_om();"').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:200px;" class="texto" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');
	$procurar_responsavel='<tr><td align=right>'.dica(ucfirst($config['gerente']), 'Filtrar pelo '.$config['gerente'].' escolhido na caixa de seleção à direita.').ucfirst($config['gerente']).':'.dicaF().'</td><td><input type="hidden" id="responsavel" name="responsavel" value="'.$responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($responsavel).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$procurar_supervisor='<tr><td align=right>'.dica(ucfirst($config['supervisor']), 'Filtrar pelo '.$config['supervisor'].' escolhido na caixa de seleção à direita.').ucfirst($config['supervisor']).':'.dicaF().'</td><td><input type="hidden" id="supervisor" name="supervisor" value="'.$supervisor.'" /><input type="text" id="nome_supervisor" name="nome_supervisor" value="'.nome_usuario($supervisor).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSupervisor();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$procurar_autoridade='<tr><td align=right>'.dica(ucfirst($config['autoridade']), 'Filtrar pelo '.$config['autoridade'].' escolhido na caixa de seleção à direita.').ucfirst($config['autoridade']).':'.dicaF().'</td><td><input type="hidden" id="autoridade" name="autoridade" value="'.$autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_usuario($autoridade).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$procurar_designado='<tr><td align=right>'.dica('designado', 'Filtrar pelo designado d'.$config['genero_tarefa'].'s '.$config['tarefas'].' escolhido na caixa de seleção à direita.').'Designado:'.dicaF().'</td><td><input type="hidden" id="designado" name="designado" value="'.$designado.'" /><input type="text" id="nome_designado" name="nome_designado" value="'.nome_usuario($designado).'" style="width:200px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDesignado();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

	if($Aplic->profissional){

		if (is_array($cia_id)) $cia_id=implode(',', $cia_id);
		elseif($cia_id < 0) $cia_id=null;

		if (is_array($dept_id)) $dept_id=implode(',', $dept_id);
		elseif($dept_id < 0) $dept_id=null;

		if (is_array($projeto_tipo)) $projeto_tipo=implode(',', $projeto_tipo);
		elseif($projeto_tipo < 0) $projeto_tipo=null;

		if (is_array($projeto_setor)) $projeto_setor=implode(',', $projeto_setor);
		elseif($projeto_setor < 0) $projeto_setor=null;

		if (is_array($projeto_segmento)) $projeto_segmento=implode(',', $projeto_segmento);
		elseif($projeto_segmento < 0) $projeto_segmento=null;

		if (is_array($projeto_intervencao)) $projeto_intervencao=implode(',', $projeto_intervencao);
		elseif($projeto_intervencao < 0) $projeto_intervencao=null;

		if (is_array($projeto_tipo_intervencao)) $projeto_tipo_intervencao=implode(',', $projeto_tipo_intervencao);
		elseif($projeto_tipo_intervencao < 0) $projeto_tipo_intervencao=null;

		if (is_array($estado_sigla)) $estado_sigla=implode(',', $estado_sigla);
		elseif($estado_sigla < 0) $estado_sigla=null;

		if (is_array($municipio_id)) $municipio_id=implode(',', $municipio_id);
		elseif($municipio_id < 0) $municipio_id=null;

		if (is_array($favorito_id)) $favorito_id=implode(',', $favorito_id);
		elseif($favorito_id < 0) $favorito_id=null;

		if (is_array($projetostatus)) $projetostatus=implode(',', $projetostatus);
		elseif($projetostatus < 0) $projetostatus=null;

		if (is_array($filtro_criterio)) $filtro_criterio=implode(',', $filtro_criterio);
		elseif($filtro_criterio < 0) $filtro_criterio=null;

		if (is_array($filtro_objetivo)) $filtro_objetivo=implode(',', $filtro_objetivo);
		elseif($filtro_objetivo < 0) $filtro_objetivo=null;

		if (is_array($filtro_tema)) $filtro_tema=implode(',', $filtro_tema);
		elseif($filtro_tema < 0) $filtro_tema=null;

		if (is_array($filtro_perspectiva)) $filtro_perspectiva=implode(',', $filtro_perspectiva);
		elseif($filtro_perspectiva < 0) $filtro_perspectiva=null;

		if (is_array($filtro_estrategia)) $filtro_estrategia=implode(',', $filtro_estrategia);
		elseif($filtro_estrategia < 0) $filtro_estrategia=null;

		if (is_array($filtro_fator)) $filtro_fator=implode(',', $filtro_fator);
		elseif($filtro_fator < 0) $filtro_fator=null;

		if (is_array($filtro_meta)) $filtro_meta=implode(',', $filtro_meta);
		elseif($filtro_meta < 0) $filtro_meta=null;
		}


	if ($Aplic->profissional){
		$botao_gestao=($filtro_criterio || $filtro_perspectiva || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta	? '<tr><td><a href="javascript: void(0)" onclick="popFiltroGestao();">'.imagem('icones/ferramentas_nao_p.png', 'Mudar Filtro de Gestão' , 'Clique neste ícone '.imagem('ferramentas_nao_p.png').' para mudar o filtros de gestão.').'</a>'.dicaF().'</td></tr>' : '<tr><td><a href="javascript: void(0)" onclick="popFiltroGestao();">'.imagem('icones/ferramentas_p.png', 'Mostrar Filtros de Gestão' , 'Clique neste ícone '.imagem('ferramentas_p.png').' para a janela de filtros de gestão.').'</a>'.dicaF().'</td></tr>');
		}
	else $botao_gestao='';


	$procura_setor='';
	$procura_segmento='';
	$procura_intervencao='';
	$procura_tipo_intervencao='';
	if ($exibir['setor']){


		$setor = array(0 => '&nbsp;') + getSisValor('Setor');

		$segmento=array(0 => '&nbsp;');
		if ($projeto_setor){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Segmento"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_setor.'"');
			$sql->adOrdem('sisvalor_valor');
			$segmento+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}

		$intervencao=array(0 => '&nbsp;');
		if ($projeto_segmento){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Intervencao"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_segmento.'"');
			$sql->adOrdem('sisvalor_valor');
			$intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}

		$tipo_intervencao=array(0 => '&nbsp;');
		if ($projeto_intervencao){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_intervencao.'"');
			$sql->adOrdem('sisvalor_valor');
			$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}

		$procura_setor='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'projeto_setor', 'style="width:200px;" class="texto" onchange="mudar_segmento();"', $projeto_setor).'</td></tr>';
		$procura_segmento='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'projeto_segmento', 'style="width:200px;" class="texto" onchange="mudar_intervencao();"', $projeto_segmento).'</div></td></tr>';
	 	$procura_intervencao='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'projeto_intervencao', 'style="width:200px;" class="texto" onchange="mudar_tipo_intervencao();"', $projeto_intervencao).'</div></td></tr>';
		$procura_tipo_intervencao='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'projeto_tipo_intervencao', 'style="width:200px;" class="texto"', $projeto_tipo_intervencao).'</div></td></tr>';
		}
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	if (!$projeto_expandido){
		if ($nao_apenas_superiores) $botao_superiores='<tr><td><a href="javascript: void(0);" onclick ="env.nao_apenas_superiores.value=0; env.submit();">'.imagem('icones/projeto_superior.gif','Ver Projetos Superiores', 'Clique neste ícone '.imagem('icones/projeto_superior.gif').' para exibir apenas os projetos superiores.').'</a></td></tr>';
		else $botao_superiores='<tr><td><a href="javascript: void(0);" onclick ="env.nao_apenas_superiores.value=1; env.submit();">'.imagem('icones/projeto_superior_cancela.gif','Ver Todos os Projetos', 'Clique neste ícone '.imagem('icones/projeto_superior_cancela.gif').' para exibir todos os projetos em vez de apenas os projetos superiores.').'</a></td></tr>';
		}
	else $botao_superiores='';


	if ($Aplic->profissional){
		$botoesTitulo = new CBlocoTitulo('Relatórios de '.ucfirst($config['projetos']), 'relatorio.png', $m, $m.'.'.$a);

		$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  	$saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/relatorio_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
	  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
	  $saida.='<table cellspacing=0 cellpadding=0>';
		$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

		$botao_filtrar='<tr><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelos parâmetros selecionados à esquerda.').'</a></td></tr>';

		if ($relatorio_tipo) $botoesTitulo->adicionaBotao('m=relatorios&relatorio_tipo=', 'relatórios','','Relatórios','Visualizar a lista de relatórios.');


		$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procura_setor.$procura_segmento.$procura_intervencao.$procura_tipo_intervencao.$procurar_estado.$procurar_municipio.$procurar_status.$vazio.'</table></td>
		<td><table cellspacing=0 cellpadding=0>'.$procurar_om.$mudar_projeto.$procurar_responsavel.$procurar_supervisor.$procurar_autoridade.$procurar_designado.$procura_categoria.$procura_pesquisa.$favoritos.'</table></td>
		<td><table cellspacing=0 cellpadding=0>'.$botao_filtrar.$botao_superiores.$botao_gestao.'</table></td></tr></table>';
		$saida.= '</div></div>';
		$botoesTitulo->adicionaCelula($saida);
		$botoesTitulo->mostrar();

		}
	elseif (!$Aplic->profissional){
		$botoesTitulo = new CBlocoTitulo('Relatórios de '.ucfirst($config['projetos']), 'relatorio.png', $m, $m.'.'.$a);
		$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0 >'.$procura_setor.$procura_segmento.$procura_intervencao.$procura_tipo_intervencao.$procurar_estado.$procurar_municipio.$procurar_status.$vazio.'</table>');
		$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0 >'.$procurar_om.$mudar_projeto.$procurar_responsavel.$procurar_supervisor.$procurar_autoridade.$procurar_designado.$procura_categoria.$procura_pesquisa.$favoritos.'</table>');
		$botao_filtrar='<tr><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelos parâmetros selecionados à esquerda.').'</a></td></tr>';
		$botoesTitulo->adicionaCelula('<table cellspacing=3 cellpadding=0>'.$botao_filtrar.$botao_superiores.$botao_gestao.'</table>');
		if ($relatorio_tipo) $botoesTitulo->adicionaBotao('m=relatorios&relatorio_tipo=', 'relatórios','','Relatórios','Visualizar a lista de relatórios.');
		$botoesTitulo->mostrar();
		}


	if($Aplic->profissional){
		$Aplic->carregarComboMultiSelecaoJS();
		echo '<script language="javascript">';

		echo 'function criarComboCia(){$jq("#cia_id").multiSelect({multiple:false, onCheck: function(){mudar_om();}});}';

		echo 'function criarComboCidades(){$jq("#municipio_id").multiSelect();}';
		if ($exibir['setor']){
			echo 'function criarComboSegmento(){$jq("#projeto_segmento").multiSelect({multiple:false, onCheck: function(){mudar_intervencao();}});}';
			echo 'function criarComboIntervencao(){$jq("#projeto_intervencao").multiSelect({multiple:false, onCheck: function(){mudar_tipo_intervencao();}});}';
			echo 'function criarComboTipoIntervencao(){$jq("#projeto_tipo_intervencao").multiSelect({multiple:false});}';
			}
		echo '$jq(function(){';
		echo '  $jq("#projeto_tipo").multiSelect();';
		echo '  $jq("#projetostatus").multiSelect();';

		if (count($vetor_favoritos)) echo '  $jq("#favorito_id").multiSelect();';
		echo '  $jq("#estado_sigla").multiSelect({multiple:false, onCheck: function(){mudar_cidades();}});';
		if ($exibir['setor']) echo '  $jq("#projeto_setor").multiSelect({multiple:false, onCheck: function(){mudar_segmento();}});';

		echo 'criarComboCia();';
		echo 'criarComboCidades();';
		if ($exibir['setor']){
			echo 'criarComboSegmento();';
			echo 'criarComboIntervencao();';
			echo 'criarComboTipoIntervencao();';
			}
		echo '});';
		echo '</script>';
		}
	}
else if($Aplic->profissional){
	if (is_array($cia_id)) $cia_id=implode(',', $cia_id);
	if (is_array($dept_id)) $dept_id=implode(',', $dept_id);
	if (is_array($projeto_tipo)) $projeto_tipo=implode(',', $projeto_tipo);
	if (is_array($projeto_setor)) $projeto_setor=implode(',', $projeto_setor);
	if (is_array($projeto_segmento)) $projeto_segmento=implode(',', $projeto_segmento);
	if (is_array($projeto_intervencao)) $projeto_intervencao=implode(',', $projeto_intervencao);
	if (is_array($projeto_tipo_intervencao)) $projeto_tipo_intervencao=implode(',', $projeto_tipo_intervencao);
	if (is_array($estado_sigla)) $estado_sigla=implode(',', $estado_sigla);
	if (is_array($municipio_id)) $municipio_id=implode(',', $municipio_id);
	if (is_array($favorito_id)) $favorito_id=implode(',', $favorito_id);
	if (is_array($projetostatus)) $projetostatus=implode(',', $projetostatus);
	}

if ($dialogo && $self_print && !$Aplic->pdf_print) echo '<script>self.print();</script>';

$df = '%d/%m/%Y';
$relatorios = $Aplic->lerArquivos(BASE_DIR .'/modulos/relatorios/relatorios', '\.php$');

if ($relatorio_tipo) {
	$relatorio_tipo = $Aplic->checarNomeArquivo($relatorio_tipo);
	$relatorio_tipo = str_replace(' ', '_', $relatorio_tipo);
	require BASE_DIR.'/modulos/relatorios/relatorios/'.$relatorio_tipo.'.php';
	}
else {
	echo estiloTopoCaixa();
	echo '<table width="100%" class="std" cellspacing=0 cellpadding=0>';
	echo '<tr><td><h2>Relatórios Disponíveis</h2></td></tr>';
	$tmp_relatorios = array();




	$pular_relatorio=array(
		0=>'status_negapeb_pro.php',
		1=>'dotacao_orcamentaria_pro.php',
		2=>'financeiro_siafi_pro.php'
		);

	$saida_relatorios=array();


	foreach ($relatorios as $v) {
		if (!in_array($v, $pular_relatorio)){
			if (file_exists(BASE_DIR.'/modulos/relatorios/relatorios/legendas/'.$v)) include_once BASE_DIR.'/modulos/relatorios/relatorios/legendas/'.$v;
			$nome = str_replace('.php', '', $v);
			$saida_relatorios[(isset($traducao[$nome.'_titulo']) ? $traducao[$nome.'_titulo'] : $nome )]='<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=relatorios&jquery=1&projeto_id='.$projeto_id.'&relatorio_tipo='.$nome.'\');">'.dica((isset($traducao[$nome.'_titulo']) ? $traducao[$nome.'_titulo'] : $nome ), (isset($traducao[$nome.'_dica']) ? $traducao[$nome.'_dica'] : 'Nenhuma informação adicional' )).(isset($traducao[$nome.'_titulo']) ? $traducao[$nome.'_titulo'] : $nome ).dica().'</a></td><td>'.(isset($traducao[$nome.'_descricao']) ? $traducao[$nome.'_descricao'] : '').'</td></tr>';
			}
		}

		ksort($saida_relatorios);

		foreach($saida_relatorios as $linha_relatorio) echo $linha_relatorio;

	//relatórios manuais
		if ($Aplic->profissional){
			if ($config['anexo_eb']) echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=relatorios&a=index&projeto_id='.$projeto_id.'&relatorio_tipo=status_negapeb_pro\');">'.dica('Documentos d'.$config['genero_anexo_eb_nome'].' '.ucfirst($config['anexo_eb_nome']),'A situação dos documentos dos projetos de acordo com '.$config['genero_anexo_eb_nome'].' '.$config['anexo_eb_nome'].'.').'Documentos d'.$config['genero_anexo_eb_nome'].' '.ucfirst($config['anexo_eb_nome']).dica().'</a></td><td>Situação dos documentos d'.$config['genero_projeto'].'s '.$config['projetos'].' de acordo com '.$config['genero_anexo_eb_nome'].' '.$config['anexo_eb_nome'].'</td></tr>';
			if ($config['mostrar_nd']) echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=relatorios&a=index&projeto_id='.$projeto_id.'&relatorio_tipo=dotacao_orcamentaria_pro\');">'.dica('Orçamento d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']),'Planilha consolidada de custos d'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Orçamento d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']).dica().'</a></td><td>Planilha consolidada de custos d'.$config['genero_projeto'].'s '.$config['projetos'].' para dotação orçamentária</td></tr>';
			if ($config['projeto_siafi'] && $Aplic->modulo_ativo('financeiro')) echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=relatorios&a=index&projeto_id='.$projeto_id.'&relatorio_tipo=financeiro_siafi_pro\');">'.dica('Estágios da Despesa vs Anexos do SIAFI','Planilha correlacionando as fases da despesa com os arquivos anexados de busca no SIAFI.').'Estágios da despesa vs anexos do SIAFI</a></td><td>Planilha correlacionando as fases da despesa com os arquivos anexados de busca no SIAFI</td></tr>';
			}
	echo '</table>';
	echo estiloFundoCaixa();
	}
if(!$dialogo) echo '</form>';
?>
<script language="javascript">


function popFiltroGestao() {
		parent.gpwebApp.popUp("Filtro de Gestão", 800, 400, 'm=projetos&a=filtro_gestao_pro&dialogo=1&cia_id='+document.getElementById('cia_id').value
		+'&filtro_criterio='+env.filtro_criterio.value
		+'&filtro_perspectiva='+env.filtro_perspectiva.value
		+'&filtro_tema='+env.filtro_tema.value
		+'&filtro_objetivo='+env.filtro_objetivo.value
		+'&filtro_fator='+env.filtro_fator.value
		+'&filtro_estrategia='+env.filtro_estrategia.value
		+'&filtro_meta='+env.filtro_meta.value
		, window.setFiltroGestao, window);
		}

function setFiltroGestao(filtro_criterio, filtro_perspectiva, filtro_tema, filtro_objetivo, filtro_fator, filtro_estrategia, filtro_meta){
	env.filtro_criterio.value=filtro_criterio;
	env.filtro_perspectiva.value=filtro_perspectiva;
	env.filtro_tema.value=filtro_tema;
	env.filtro_objetivo.value=filtro_objetivo;
	env.filtro_fator.value=filtro_fator;
	env.filtro_estrategia.value=filtro_estrategia;
	env.filtro_meta.value=filtro_meta;
	env.submit();
	}



function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['projeto']) ?>", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, 'Projetos','left=0,top=0,height=600,width=620,scrollbars=yes, resizable=yes');
	}
function setProjeto(chave, valor){
	env.projeto_id.value=chave;
	env.submit();
	}





var usuarios_gerente = '<?php echo $responsavel?>';
var usuarios_supervisor = '<?php echo $supervisor?>';
var usuarios_autoridade = '<?php echo $autoridade?>';
var usuarios_designado = '<?php echo $designado?>';


<?php if ($Aplic->profissional){ ?>


function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Gerente", 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_gerente, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_gerente, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setResponsavel(usuario_id_string){
	if(!usuario_id_string) usuarios_gerente = '';
	document.getElementById('responsavel').value = usuario_id_string;
	usuarios_gerente = usuario_id_string;
	xajax_lista_nome(usuario_id_string, 'nome_responsavel');
	}

function popSupervisor(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['supervisor']) ?>", 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_supervisor, window.setSupervisor, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_supervisor, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setSupervisor(usuario_id_string){
	if(!usuario_id_string) usuarios_gerente = '';
	document.getElementById('supervisor').value = usuario_id_string;
	usuarios_gerente = usuario_id_string;
	xajax_lista_nome(usuario_id_string, 'nome_supervisor');
	}

function popAutoridade(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['autoridade']) ?>", 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_autoridade, window.setAutoridade, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_autoridade, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setAutoridade(usuario_id_string){
	if(!usuario_id_string) usuarios_gerente = '';
	document.getElementById('autoridade').value = usuario_id_string;
	usuarios_gerente = usuario_id_string;
	xajax_lista_nome(usuario_id_string, 'nome_autoridade');
	}

function popDesignado(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Designado", 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setDesignado&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_gerente, window.setDesignado, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setDesignado&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_gerente, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setDesignado(usuario_id_string){
	if(!usuario_id_string) usuarios_gerente = '';
	document.getElementById('designado').value = usuario_id_string;
	usuarios_gerente = usuario_id_string;
	xajax_lista_nome(usuario_id_string, 'nome_designado');
	}



<?php } else { ?>

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('responsavel').value=usuario_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function popSupervisor(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["supervisor"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('supervisor').value, window.setSupervisor, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('supervisor').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setSupervisor(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('supervisor').value=usuario_id;
	document.getElementById('nome_supervisor').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function popAutoridade(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["autoridade"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('autoridade').value, window.setAutoridade, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('autoridade').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('autoridade').value=usuario_id;
	document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function popDesignado(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Designado', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setDesignado&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('designado').value, window.setDesignado, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setDesignado&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('designado').value, 'Designado','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDesignado(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('designado').value=usuario_id;
	document.getElementById('nome_designado').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

<?php } ?>





function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:200px;" onchange="javascript:mudar_om();"');
	}


function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:200px;"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>));
	}

function mudar_segmento(){
	<?php
	if($Aplic->profissional){
		echo '$jq.fn.multiSelect.clear("#projeto_tipo_intervencao");';
		echo '$jq.fn.multiSelect.clear("#projeto_intervencao");';
		}
	else{
		echo 'document.getElementById("projeto_intervencao").length=0;';
		echo 'document.getElementById("projeto_tipo_intervencao").length=0;';
		}
	?>
	xajax_mudar_ajax(document.getElementById('projeto_setor').value, 'Segmento', 'projeto_segmento','combo_segmento', 'style="width:200px;" class="texto" size=1 onchange="mudar_intervencao();"');
	}

function mudar_intervencao(){
	<?php
	if($Aplic->profissional) echo '$jq.fn.multiSelect.clear("#projeto_tipo_intervencao");';
	else echo 'document.getElementById("projeto_tipo_intervencao").length=0;';
	?>
	xajax_mudar_ajax(document.getElementById('projeto_segmento').value, 'Intervencao', 'projeto_intervencao','combo_intervencao', 'style="width:200px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"');
	}

function mudar_tipo_intervencao(){
	xajax_mudar_ajax(document.getElementById('projeto_intervencao').value, 'TipoIntervencao', 'projeto_tipo_intervencao','combo_tipo_intervencao', 'style="width:200px;" class="texto" size=1');
	}

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia, deptartamento){
	env.cia_dept.value=cia;
	env.dept_id.value=deptartamento;
	env.submit();
	}

</script>