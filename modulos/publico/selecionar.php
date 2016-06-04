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


if (!defined('BASE_DIR')) die('Não deveria acessar este arquivo diretamente.');
global $Aplic;
$extra=array();	
$chamarVolta = getParam($_REQUEST, 'chamar_volta', 0);
$tabela = getParam($_REQUEST, 'tabela', 0);
$usuario_id = getParam($_REQUEST, 'usuario_id', 0);

if (isset($_REQUEST['textobusca'])) $Aplic->setEstado('textobusca', getParam($_REQUEST, 'textobusca', null));
$textobusca = ($Aplic->getEstado('textobusca') ? $Aplic->getEstado('textobusca') : '');

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));
	
if (isset($_REQUEST['nao_apenas_superiores'])) $Aplic->setEstado('nao_apenas_superiores', getParam($_REQUEST, 'nao_apenas_superiores', null));
$nao_apenas_superiores = $Aplic->getEstado('nao_apenas_superiores') !== null ? $Aplic->getEstado('nao_apenas_superiores') : 0;

if (isset($_REQUEST['cia_dept']) && $_REQUEST['cia_dept'])	$Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_dept', null));
else if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;
	
if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
if ($dept_id) $ver_subordinadas = null;

$aceita_portfolio=getParam($_REQUEST, 'aceita_portfolio', null);
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);

$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

$edicao=getParam($_REQUEST, 'edicao', 0);


$nao_ha='Não foi encontrado';
$nenhum='Nenhum';
$ok = $chamarVolta & $tabela;
$titulo = 'Seletor Genérico';
$classeModulo = $Aplic->getClasseModulo($tabela);
if ($classeModulo && file_exists($classeModulo)) require_once $classeModulo;
$sql = new BDConsulta;
$sql->adTabela($tabela);
$resultadoConsulta = false;

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="publico" />';
echo '<input name="a" type="hidden" value="selecionar" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input name="dialogo" type="hidden" value="1" />';
echo '<input type="hidden" name="chamarVolta" value="'.$chamarVolta.'" />';
echo '<input type="hidden" name="tabela" value="'.$tabela.'" />';
echo '<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';
echo '<input type="hidden" name="edicao" value="'.$edicao.'" />';
echo '<input type="hidden" name="cia_dept" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="nao_apenas_superiores" value="'.$nao_apenas_superiores.'" />';
echo '<input type="hidden" name="aceita_portfolio" value="'.$aceita_portfolio.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';

echo estiloTopoCaixa();
echo '<table class="std" width="100%" cellspacing=0 cellpadding=0>';

$procurar_om='<tr><td align=right>'.ucfirst($config['organizacao']).':</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.dept_id.value=\'\'; document.env.submit();">'.imagem('icones/organizacao_p.gif').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif').'</a>' : '').'</td>' : '').'</tr>'.($dept_id ? '<tr><td align=right>'.ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif').'</a></td>' : '').'</tr>' : '');
$botao_filtrar='<tr><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png').'</a></td></tr>';

if ($tabela=='projetos'){
	
	$portfolio = getParam($_REQUEST, 'portfolio', null);
	$portfolio_pai = getParam($_REQUEST, 'portfolio_pai', null);
	
	$sql = new BDConsulta;
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'projeto\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

	if (isset($_REQUEST['projeto_tipo'])) $Aplic->setEstado('projeto_tipo', getParam($_REQUEST, 'projeto_tipo', null));
	$projeto_tipo = $Aplic->getEstado('projeto_tipo') !== null ? $Aplic->getEstado('projeto_tipo') : -1;
	
	if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
		
	if (isset($_REQUEST['projetostatus']))	$Aplic->setEstado('projetostatus', getParam($_REQUEST, 'projetostatus', null));
	$projetostatus = $Aplic->getEstado('projetostatus') !== null ? $Aplic->getEstado('projetostatus') : 0;
	
	if (isset($_REQUEST['favorito_id']))	$Aplic->setEstado('projeto_favorito', getParam($_REQUEST, 'favorito_id', null));
	$favorito_id = $Aplic->getEstado('projeto_favorito') !== null ? $Aplic->getEstado('projeto_favorito') : 0;
	
	if (isset($_REQUEST['estado_sigla']))	$Aplic->setEstado('estado_sigla', getParam($_REQUEST, 'estado_sigla', null));
	$estado_sigla = ($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : '');
	
	if (isset($_REQUEST['municipio_id']))	$Aplic->setEstado('municipio_id', getParam($_REQUEST, 'municipio_id', null));
	$municipio_id = ($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '');
	
	if (isset($_REQUEST['responsavel']))	$Aplic->setEstado('responsavel', getParam($_REQUEST, 'responsavel', null));
	$responsavel = $Aplic->getEstado('responsavel') !== null ? $Aplic->getEstado('responsavel') : 0;
	
	if (isset($_REQUEST['supervisor']))	$Aplic->setEstado('supervisor', getParam($_REQUEST, 'supervisor', null));
	$supervisor = $Aplic->getEstado('supervisor') !== null ? $Aplic->getEstado('supervisor') : 0;
	
	if (isset($_REQUEST['autoridade']))	$Aplic->setEstado('autoridade', getParam($_REQUEST, 'autoridade', null));
	$autoridade = $Aplic->getEstado('autoridade') !== null ? $Aplic->getEstado('autoridade') : 0;
	
	if (isset($_REQUEST['projeto_setor']))	$Aplic->setEstado('projeto_setor',getParam($_REQUEST, 'projeto_setor', null));
	$projeto_setor = $Aplic->getEstado('projeto_setor') !== null ? $Aplic->getEstado('projeto_setor') : '';
	
	if (isset($_REQUEST['projeto_segmento']))	$Aplic->setEstado('projeto_segmento',getParam($_REQUEST, 'projeto_segmento', null));
	$projeto_segmento = $Aplic->getEstado('projeto_segmento') !== null ? $Aplic->getEstado('projeto_segmento') : '';
	
	if (isset($_REQUEST['projeto_intervencao']))	$Aplic->setEstado('projeto_intervencao', getParam($_REQUEST, 'projeto_intervencao', null));
	$projeto_intervencao = $Aplic->getEstado('projeto_intervencao') !== null ? $Aplic->getEstado('projeto_intervencao') : '';
	
	if (isset($_REQUEST['projeto_tipo_intervencao']))	$Aplic->setEstado('projeto_tipo_intervencao', getParam($_REQUEST, 'projeto_tipo_intervencao', null));
	$projeto_tipo_intervencao = $Aplic->getEstado('projeto_tipo_intervencao') !== null ? $Aplic->getEstado('projeto_tipo_intervencao') : '';
	
	if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projtextobusca', getParam($_REQUEST, 'projtextobusca', ''));
	$projtextobusca = $Aplic->getEstado('projtextobusca') !== null ? $Aplic->getEstado('projtextobusca') : '';
	
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
		$favoritos='<tr><td align="right" nowrap="nowrap">Favoritos:</td><td width="100%" colspan="2">'.selecionaVetor($vetor_favoritos, 'favorito_id', 'class="texto"'.($Aplic->profissional ? ' multiple' :'').' style="width:250px;"', $favorito_id).'</td></tr>';
		}
	
	$projeto_expandido =getParam($_REQUEST, 'projeto_expandido', 0);
	
	if ($favorito_id) $projeto_expandido=0;
	
	
	if ($Aplic->profissional){
		if (isset($_REQUEST['template'])) $Aplic->setEstado('template', getParam($_REQUEST, 'template', null));
		$template = $Aplic->getEstado('template') !== null ? $Aplic->getEstado('template') : 0;
		$opcoes_modelo=array(0=>'Não', 1=>'Sim');
		$ser_template='<tr><td align="right" nowrap="nowrap">Modelo:</td><td width="100%" colspan="2">'.selecionaVetor($opcoes_modelo, 'template', 'style="width:250px;" class="texto"', $template).'</div></td></tr>';	
		}
	else {
		$template = false;
		$ser_template='';
		}			

	$procurar_estado='<tr><td align="right">Estado:</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:250px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
	$procurar_municipio='<tr><td align="right">Município:</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto"'.($Aplic->profissional ? ' multiple' :'').' style="width:250px;"', '', $municipio_id, true, false).'</div></td></tr>';
	$procurar_status='<tr><td nowrap="nowrap" align="right">Status:</td><td nowrap="nowrap" align="left">'. selecionaVetor($projetos_status, 'projetostatus', 'size="1" style="width:250px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $projetostatus) .'</td></tr>';
	$procura_categoria='<tr><td nowrap="nowrap" align="right">Categoria:</td><td nowrap="nowrap" align="left">'. selecionaVetor($projeto_tipos, 'projeto_tipo', 'size="1" style="width:250px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $projeto_tipo) .'</td></tr>';
	$procura_pesquisa='<tr><td nowrap="nowrap" align="right">Pesquisar:</td><td nowrap="nowrap" align="left"><input type="text" class="texto" style="width:250px;" id="projtextobusca" name="projtextobusca" onChange="document.env.submit();" value="'.$projtextobusca.'" /></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&dialogo=1&projtextobusca=\');">'.imagem('icones/limpar_p.gif').'</a></td></tr>';
	$procurar_responsavel='<tr><td align=right>'.ucfirst($config['gerente']).':</td><td><input type="hidden" id="responsavel" name="responsavel" value="'.$responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($responsavel).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif').'</a></td></tr>';
	$procurar_supervisor='<tr><td align=right>'.ucfirst($config['supervisor']).':</td><td><input type="hidden" id="supervisor" name="supervisor" value="'.$supervisor.'" /><input type="text" id="nome_supervisor" name="nome_supervisor" value="'.nome_usuario($supervisor).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSupervisor();">'.imagem('icones/usuarios.gif').'</a></td></tr>';
	$procurar_autoridade='<tr><td align=right>'.ucfirst($config['autoridade']).':</td><td><input type="hidden" id="autoridade" name="autoridade" value="'.$autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_usuario($autoridade).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif').'</a></td></tr>';
	
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
		
		$procura_setor='<tr><td align="right" nowrap="nowrap">'.ucfirst($config['setor']).':</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'projeto_setor', 'style="width:250px;" class="texto" onchange="mudar_segmento();"', $projeto_setor).'</td></tr>';
		$procura_segmento='<tr><td align="right" nowrap="nowrap">'.ucfirst($config['segmento']).':</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'projeto_segmento', 'style="width:250px;" class="texto" onchange="mudar_intervencao();"', $projeto_segmento).'</div></td></tr>';
	 	$procura_intervencao='<tr><td align="right" nowrap="nowrap">'.ucfirst($config['intervencao']).':</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'projeto_intervencao', 'style="width:250px;" class="texto" onchange="mudar_tipo_intervencao();"', $projeto_intervencao).'</div></td></tr>';
		$procura_tipo_intervencao='<tr><td align="right" nowrap="nowrap">'.ucfirst($config['tipo']).':</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'projeto_tipo_intervencao', 'style="width:250px;" class="texto"', $projeto_tipo_intervencao).'</div></td></tr>';
		}
	
	if (!$projeto_expandido){
		if ($nao_apenas_superiores) $botao_superiores='<tr><td><a href="javascript: void(0);" onclick ="env.nao_apenas_superiores.value=0; env.submit();">'.imagem('icones/projeto_superior.gif').'</a></td></tr>';
		else $botao_superiores='<tr><td><a href="javascript: void(0);" onclick ="env.nao_apenas_superiores.value=1; env.submit();">'.imagem('icones/projeto_superior_cancela.gif').'</a></td></tr>';
		}
	else $botao_superiores='';
	
	

	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$procura_setor.$procura_segmento.$procura_intervencao.$procura_tipo_intervencao.$procurar_estado.$procurar_municipio.$procurar_status.'</table></td>';	
	echo '<td><table cellspacing=0 cellpadding=0 >'.$procurar_om.$procurar_responsavel.$procurar_supervisor.$procurar_autoridade.$procura_categoria.$procura_pesquisa.$favoritos.'</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$botao_filtrar.$botao_superiores.'</table></td>';
	echo '</tr></table></td></tr>';


	
	if($Aplic->profissional){


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


elseif ($tabela=='pratica_indicador'){
	
	$pratica_indicador_projeto=getParam($_REQUEST, 'pratica_indicador_projeto', null);
	$pratica_indicador_tarefa=getParam($_REQUEST, 'pratica_indicador_tarefa', null);
	$pratica_indicador_pratica=getParam($_REQUEST, 'pratica_indicador_pratica', null);
	$pratica_indicador_tema=getParam($_REQUEST, 'pratica_indicador_tema', null);
	$pratica_indicador_objetivo_estrategico=getParam($_REQUEST, 'pratica_indicador_objetivo_estrategico', null);
	$pratica_indicador_estrategia=getParam($_REQUEST, 'pratica_indicador_estrategia', null);
	$pratica_indicador_acao=getParam($_REQUEST, 'pratica_indicador_acao', null);
	$pratica_indicador_fator=getParam($_REQUEST, 'pratica_indicador_fator', null);
	$pratica_indicador_meta=getParam($_REQUEST, 'pratica_indicador_meta', null);
	$pratica_indicador_perspectiva=getParam($_REQUEST, 'pratica_indicador_perspectiva', null);
	$pratica_indicador_canvas=getParam($_REQUEST, 'pratica_indicador_canvas', null);
	$tipos=array(''=>'', 'projeto' => $config['projeto'], 'pratica' => $config['pratica'], 'perspectiva'=> $config['perspectiva'], 'tema'=> $config['tema'],  'objetivo'=> $config['objetivo'], 'fator'=> $config['fator'], 'estrategia'=> $config['iniciativa'],  'meta'=>'meta', 'acao'=> $config['acao']);
	if ($Aplic->profissional) $tipos['canvas']=$config['canvas'];
	if ($pratica_indicador_projeto) $tipo='projeto';
	elseif ($pratica_indicador_pratica) $tipo='pratica';
	elseif ($pratica_indicador_acao) $tipo='acao';
	elseif ($pratica_indicador_objetivo_estrategico) $tipo='objetivo';
	elseif ($pratica_indicador_tema) $tipo='tema';
	elseif ($pratica_indicador_fator) $tipo='fator';
	elseif ($pratica_indicador_estrategia) $tipo='estrategia';
	elseif ($pratica_indicador_meta) $tipo='meta';
	elseif ($pratica_indicador_perspectiva) $tipo='perspectiva';
	elseif ($pratica_indicador_canvas) $tipo='canvas';
	else $tipo='';
	
	$procuraBuffer = '<tr><td align=right nowrap="nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="textobusca" onChange="document.env.submit();" value="'.$textobusca.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr>';
	echo '<td><table cellspacing=0 cellpadding=0>'.$procurar_om.$procuraBuffer.'</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0>'.$botao_filtrar.'</table></td>';
	echo '</tr></table></td></tr>';
	
	
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<tr><td align="right" nowrap="nowrap">Relacionado:</td><td align="left">'.selecionaVetor($tipos, 'tipo', 'class="texto" style="width:230px;" onchange="mostrar()"', $tipo).'<td></tr>';
	echo '<tr '.($pratica_indicador_projeto || $pratica_indicador_tarefa ? '' : 'style="display:none"').' id="projeto" align="right"><td>'.ucfirst($config['projeto']).':</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_projeto" value="'.$pratica_indicador_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($pratica_indicador_projeto).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_projeto || $pratica_indicador_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" nowrap="nowrap">'.ucfirst($config['tarefa']).':</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tarefa" value="'.$pratica_indicador_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($pratica_indicador_tarefa).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right">'.ucfirst($config['pratica']).':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_pratica" value="'.$pratica_indicador_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($pratica_indicador_pratica).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" nowrap="nowrap">'.ucfirst($config['acao']).':</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_acao" value="'.$pratica_indicador_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($pratica_indicador_acao).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_tema ? '' : 'style="display:none"').' id="tema" ><td align="right">'.ucfirst($config['tema']).':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tema" value="'.$pratica_indicador_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($pratica_indicador_tema).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_objetivo_estrategico ? '' : 'style="display:none"').' id="objetivo" ><td align="right">'.ucfirst($config['objetivo']).':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_objetivo_estrategico" value="'.$pratica_indicador_objetivo_estrategico.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($pratica_indicador_objetivo_estrategico).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right">'.ucfirst($config['iniciativa']).':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_estrategia" value="'.$pratica_indicador_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($pratica_indicador_estrategia).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_fator ? '' : 'style="display:none"').' id="fator" ><td align="right">'.ucfirst($config['fator']).':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_fator" value="'.$pratica_indicador_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($pratica_indicador_fator).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_meta ? '' : 'style="display:none"').' id="meta" ><td align="right">'.ucfirst($config['meta']).':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_meta" value="'.$pratica_indicador_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($pratica_indicador_meta).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right">'.ucfirst($config['perspectiva']).':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_perspectiva" value="'.$pratica_indicador_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($pratica_indicador_perspectiva).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png').'</a></td></tr></table></td></tr>';
	if (!$Aplic->profissional) echo '<input type="hidden" name="pratica_indicador_canvas" value="" />';
	else 	echo '<tr '.($pratica_indicador_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right">'.ucfirst($config['canvas']).':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_canvas" value="'.$pratica_indicador_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($pratica_indicador_canvas).'" style="width:230px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png').'</a></td></tr></table></td></tr>';

	echo '</table></td></tr>';
	}

else{
	
	
	$procuraBuffer = '<tr><td align=right nowrap="nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="textobusca" onChange="document.env.submit();" value="'.$textobusca.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	
	echo '<tr><td colspan=20 align=right><table cellspacing=0 cellpadding=0><tr>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$procurar_om.$procuraBuffer.'</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$botao_filtrar.'</table></td>';
	echo '</tr></table></td></tr>';
	
	$responsavel=0;	
	$supervisor=0;	
	$autoridade=0;
	$municipio_id=0;
	}



switch ($tabela) {
	case 'depts':
		$titulo = $config['departamento'];
		$nao_ha='Não foi encontrad'.($config['genero_dept']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['departamento'];
		$nenhum='Nenhum'.($config['genero_dept']=='a' ? 'a' : '').' '.$config['departamento'];
		$esconder_cia = getParam($_REQUEST, 'esconder_cia', 0);
		$sql->esqUnir('cias', 'cias','cias.cia_id=dept_cia');
		$sql->adCampo('dept_id, dept_acesso');
		if ($esconder_cia == 1) $sql->adCampo('dept_nome');
		else $sql->adCampo('concatenar_tres(cia_nome, \': \', dept_nome) AS dept_nome');
		if ($cia_id && !$lista_cias) $sql->adOnde('dept_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('dept_cia IN ('.$lista_cias.')');
		$sql->adOrdem('dept_ordem, dept_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarDept($linha['dept_acesso'], $linha['dept_id'])) $lista[$linha['dept_id']]=$linha['dept_nome']; 
				}
			}
		else {
			foreach($achados as $linha) {
				if (permiteAcessarDept($linha['dept_acesso'], $linha['dept_id'])) $lista[$linha['dept_id']]=$linha['dept_nome']; 
				}
			}
		break;
	
	case 'checklist':
		$titulo = 'Checklist';
		$nao_ha='Não foi encontrado nenhum checklist';
		$nenhum='Nenhum checklist';
		if (trim($textobusca)) $sql->adOnde('checklist_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('checklist.checklist_id, checklist_nome, checklist_acesso');
		$sql->adOrdem('checklist_nome');
			
		if ($dept_id) {
			$sql->esqUnir('checklist_depts','checklist_depts', 'checklist_depts.checklist_id=checklist.checklist_id');
			$sql->adOnde('checklist_dept='.(int)$dept_id.' OR checklist_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('checklist_cia', 'checklist_cia', 'checklist.checklist_id=checklist_cia_checklist');
			$sql->adOnde('checklist_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR checklist_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('checklist_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('checklist_cia IN ('.$lista_cias.')');
				
		$sql->adOrdem('checklist_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarChecklist($linha['checklist_acesso'], $linha['checklist_id'])) $lista[$linha['checklist_id']]=$linha['checklist_nome'];
				}
			}
		else {
			foreach($achados as $linha)	if (permiteAcessarChecklist($linha['checklist_acesso'], $linha['checklist_id'])) $lista[$linha['checklist_id']]=$linha['checklist_nome'];
			}
		break;
	
	case 'painel_composicao':
		include_once BASE_DIR.'/modulos/praticas/painel_pro.class.php'; 
		$titulo = 'Composição de Painéis';
		$nao_ha='Não foi encontrada nenhuma composição de painéis';
		$nenhum='Nenhuma composição de painéis';
		if (trim($textobusca)) $sql->adOnde('painel_composicao_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('painel_composicao_id, painel_composicao_nome, painel_composicao_acesso');
		if ($dept_id) {
			$sql->esqUnir('painel_composicao_dept','painel_composicao_dept', 'painel_composicao_dept.painel_composicao_dept_painel_composicao=painel_composicao.painel_composicao_id');
			$sql->adOnde('painel_composicao_dept='.(int)$dept_id.' OR painel_composicao_dept.painel_composicao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('painel_composicao_cia', 'painel_composicao_cia', 'painel_composicao.painel_composicao_id=painel_composicao_cia_painel_composicao');
			$sql->adOnde('painel_composicao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR painel_composicao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('painel_composicao_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('painel_composicao_cia IN ('.$lista_cias.')');
		$sql->adOrdem('painel_composicao_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarPainelSlideShow($linha['painel_composicao_acesso'], $linha['painel_composicao_id'])) $lista[$linha['painel_composicao_id']]=$linha['painel_composicao_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPainelSlideShow($linha['painel_composicao_acesso'], $linha['painel_composicao_id'])) $lista[$linha['painel_composicao_id']]=$linha['painel_composicao_nome'];
			}
		break;
		
	case 'painel':
		include_once BASE_DIR.'/modulos/praticas/painel_pro.class.php'; 
		$titulo = 'Painel';
		$nao_ha='Não foi encontrado nenhum painel';
		$nenhum='Nenhum painel';
		if (trim($textobusca)) $sql->adOnde('painel_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('painel_id, painel_nome, painel_acesso');
		if ($dept_id) {
			$sql->esqUnir('painel_dept','painel_dept', 'painel_dept.painel_dept_painel=painel.painel_id');
			$sql->adOnde('painel_dept='.(int)$dept_id.' OR painel_dept.painel_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('painel_cia', 'painel_cia', 'painel.painel_id=painel_cia_painel');
			$sql->adOnde('painel_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR painel_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('painel_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('painel_cia IN ('.$lista_cias.')');
		$sql->adOrdem('painel_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPainel($linha['painel_acesso'], $linha['painel_id'])) $lista[$linha['painel_id']]=$linha['painel_nome'];
			}
		else {
			foreach($achados as $linha)	if (permiteAcessarPainel($linha['painel_acesso'], $linha['painel_id'])) $lista[$linha['painel_id']]=$linha['painel_nome'];
			}
		break;	
		
	case 'painel_odometro':
		include_once BASE_DIR.'/modulos/praticas/painel_pro.class.php'; 
		$titulo = 'Odômetro';
		$nao_ha='Não foi encontrado nenhum odômetro';
		$nenhum='Nenhum odômetro';
		if (trim($textobusca)) $sql->adOnde('painel_odometro_nome LIKE \'%'.$textobusca.'%\'');
		$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_id=painel_odometro_indicador');
		$sql->adCampo('painel_odometro_id, painel_odometro_nome, painel_odometro_acesso, pratica_indicador_nome');
		if ($dept_id) {
			$sql->esqUnir('painel_odometro_dept','painel_odometro_dept', 'painel_odometro_dept.painel_odometro_dept_painel_odometro=painel_odometro.painel_odometro_id');
			$sql->adOnde('painel_odometro_dept='.(int)$dept_id.' OR painel_odometro_dept.painel_odometro_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('painel_odometro_cia', 'painel_odometro_cia', 'painel_odometro.painel_odometro_id=painel_odometro_cia_painel_odometro');
			$sql->adOnde('painel_odometro_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR painel_odometro_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('painel_odometro_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('painel_odometro_cia IN ('.$lista_cias.')');
		$sql->adOrdem('painel_odometro_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha)	if (permiteEditarOdometro($linha['painel_odometro_acesso'], $linha['painel_odometro_id'])) $lista[$linha['painel_odometro_id']]=($linha['painel_odometro_nome']? $linha['painel_odometro_nome'] : $linha['pratica_indicador_nome']);
			}
		else {
			foreach($achados as $linha) if (permiteAcessarOdometro($linha['painel_odometro_acesso'], $linha['painel_odometro_id'])) $lista[$linha['painel_odometro_id']]=($linha['painel_odometro_nome']? $linha['painel_odometro_nome'] : $linha['pratica_indicador_nome']);
			}
		break;	
	case 'tr':
		$titulo = ucfirst($config['tr']);
		$nao_ha='Não foi encontrad'.($config['genero_tr']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['tr'];
		$nenhum='Nenhum'.($config['genero_tr']=='a' ? 'a' : '').' '.$config['tr'];
		if (trim($textobusca)) $sql->adOnde('tr_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('tr_id, tr_nome, tr_acesso');
		if ($dept_id) {
			$sql->esqUnir('tr_dept','tr_dept', 'tr_dept.tr_dept_tr=tr.tr_id');
			$sql->adOnde('tr_dept='.(int)$dept_id.' OR tr_dept.tr_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('tr_cia', 'tr_cia', 'tr.tr_id=tr_cia_tr');
			$sql->adOnde('tr_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tr_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('tr_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('tr_cia IN ('.$lista_cias.')');
		$sql->adOrdem('tr_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarTR($linha['tr_acesso'], $linha['tr_id'])) $lista[$linha['tr_id']]=$linha['tr_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTR($linha['tr_acesso'], $linha['tr_id'])) $lista[$linha['tr_id']]=$linha['tr_nome'];
			}
		break;	
	case 'agrupamento':
		include_once BASE_DIR.'/modulos/agrupamento/funcoes.php'; 
		$titulo = 'Agrupamento';
		$nao_ha='Não foi encontrado nenhum agrupamento';
		$nenhum='Nenhum agrupamento';
		if (trim($textobusca)) $sql->adOnde('agrupamento_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('agrupamento.agrupamento_id, agrupamento_nome, agrupamento_acesso');
		if ($dept_id) {
			$sql->esqUnir('agrupamento_dept','agrupamento_dept', 'agrupamento_dept.agrupamento_dept_agrupamento=agrupamento.agrupamento_id');
			$sql->adOnde('agrupamento_dept='.(int)$dept_id.' OR agrupamento_dept.agrupamento_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('agrupamento_cia', 'agrupamento_cia', 'agrupamento.agrupamento_id=agrupamento_cia_agrupamento');
			$sql->adOnde('agrupamento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR agrupamento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('agrupamento_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('agrupamento_cia IN ('.$lista_cias.')');
		$sql->adOrdem('agrupamento_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarAgrupamento($linha['agrupamento_acesso'], $linha['agrupamento_id'])) $lista[$linha['agrupamento_id']]=$linha['agrupamento_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarAgrupamento($linha['agrupamento_acesso'], $linha['agrupamento_id'])) $lista[$linha['agrupamento_id']]=$linha['agrupamento_nome'];
			}
		break;	
	
	case 'template':
		include_once BASE_DIR.'/modulos/projetos/template_pro.class.php'; 
		$titulo = 'Modelo';
		$nao_ha='Não foi encontrado nenhum modelo';
		$nenhum='Nenhum modelo';
		if (trim($textobusca)) $sql->adOnde('template_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('template.template_id, template_nome, template_acesso');
		if ($dept_id) {
			$sql->esqUnir('template_depts','template_depts', 'template_depts.template_id=template.template_id');
			$sql->adOnde('template_dept='.(int)$dept_id.' OR template_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('template_cia', 'template_cia', 'template.template_id=template_cia_template');
			$sql->adOnde('template_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR template_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('template_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('template_cia IN ('.$lista_cias.')');
		$sql->adOrdem('template_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarTemplate($linha['template_acesso'], $linha['template_id'])) $lista[$linha['template_id']]=$linha['template_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTemplate($linha['template_acesso'], $linha['template_id'])) $lista[$linha['template_id']]=$linha['template_nome'];
			}
		break;
		
	case 'arquivos':
		$titulo = 'Arquivo';
		$nao_ha='Não foi encontrado nenhum arquivo';
		$nenhum='Nenhum arquivo';
		if (trim($textobusca)) $sql->adOnde('arquivo_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('arquivos.arquivo_id, arquivo_nome, arquivo_acesso');
		$sql->adOrdem('arquivo_nome');
		
		if ($dept_id) {
			$sql->esqUnir('arquivo_dept','arquivo_dept', 'arquivo_dept_arquivo=arquivos.arquivo_id');
			$sql->adOnde('arquivo_dept='.(int)$dept_id.' OR arquivo_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('arquivo_cia', 'arquivo_cia', 'arquivos.arquivo_id=arquivo_cia_arquivo');
			$sql->adOnde('arquivo_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR arquivo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('arquivo_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('arquivo_cia IN ('.$lista_cias.')');
		
		
		$sql->adOrdem('arquivo_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarArquivo($linha['arquivo_acesso'], $linha['arquivo_id'])) $lista[$linha['arquivo_id']]=$linha['arquivo_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarArquivo($linha['arquivo_acesso'], $linha['arquivo_id'])) $lista[$linha['arquivo_id']]=$linha['arquivo_nome']; 
			}
		break;
	
	case 'plano_gestao':
		$titulo = 'Planejamento Estratégico';
		$nao_ha='Não foi encontrado nenhum planejamento estratégico';
		$nenhum='Nenhum planejamento estratégico';
		if (trim($textobusca)) $sql->adOnde('pg_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('plano_gestao.pg_id, pg_nome, pg_acesso');
		$sql->adOrdem('pg_nome');

		if ($dept_id) {
			$sql->esqUnir('plano_gestao_dept', 'plano_gestao_dept', 'plano_gestao_dept_plano=plano_gestao.pg_id');
			$sql->adOnde('pg_dept='.(int)$dept_id.' OR plano_gestao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('plano_gestao_cia', 'plano_gestao_cia', 'plano_gestao_cia_plano=plano_gestao.pg_id');
			$sql->adOnde('pg_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_gestao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_cia IN ('.$lista_cias.')');	
		
		
		$sql->adOrdem('pg_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarPlanoGestao($linha['pg_acesso'], $linha['pg_id'])) $lista[$linha['pg_id']]=$linha['pg_nome'];
				} 
			}
		else {
			foreach($achados as $linha) {
				if (permiteAcessarPlanoGestao($linha['pg_acesso'], $linha['pg_id'])) $lista[$linha['pg_id']]=$linha['pg_nome']; 
				}
			}
		break;
	
	case 'patrocinadores':
		$titulo = 'Patrocinador';
		$nao_ha='Não foi encontrado nenhum patrocinador';
		$nenhum='Nenhum patrocinador';
		if (trim($textobusca)) $sql->adOnde('patrocinador_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('patrocinadores.patrocinador_id, patrocinador_nome, patrocinador_acesso');
		$sql->adOrdem('patrocinador_nome');
		
		if ($dept_id && !$lista_depts) {
			$sql->esqUnir('patrocinadores_depts', 'patrocinadores_depts', 'patrocinadores_depts.patrocinador_id=patrocinadores.patrocinador_id');
			$sql->adOnde('dept_id='.(int)$dept_id.' OR patrocinador_dept='.(int)$dept_id);
			}
		elseif ($lista_depts) {
			$sql->esqUnir('patrocinadores_depts', 'patrocinadores_depts', 'patrocinadores_depts.patrocinador_id=patrocinadores.patrocinador_id');
			$sql->adOnde('dept_id IN ('.$lista_depts.') OR patrocinador_dept IN ('.$lista_depts.')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('patrocinador_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('patrocinador_cia IN ('.$lista_cias.')');
		$sql->adOrdem('patrocinador_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarPatrocinador($linha['patrocinador_acesso'], $linha['patrocinador_id'])) $lista[$linha['patrocinador_id']]=$linha['patrocinador_nome'];
				} 
			}
		else {
			foreach($achados as $linha) {
				if (permiteAcessarPatrocinador($linha['patrocinador_acesso'], $linha['patrocinador_id'])) $lista[$linha['patrocinador_id']]=$linha['patrocinador_nome']; 
				}
			}
		break;
	
	
	case 'eventos':
		$titulo = 'Evento';
		$nao_ha='Não foi encontrado nenhum evento';
		$nenhum='Nenhum evento';
		if (trim($textobusca)) $sql->adOnde('evento_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('eventos.evento_id, concatenar_tres(evento_titulo, \': \', formatar_data(evento_inicio, \'%d/%m/%Y\')) AS evento_nome, evento_acesso, evento_projeto, evento_tarefa, evento_pratica, evento_acao, evento_indicador, evento_calendario');
		$sql->adOrdem('evento_inicio');
		
		if ($dept_id) {
			$sql->esqUnir('evento_depts','evento_depts', 'evento_depts.evento_id=eventos.evento_id');
			$sql->adOnde('evento_dept='.(int)$dept_id.' OR evento_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('evento_cia', 'evento_cia', 'eventos.evento_id=evento_cia_evento');
			$sql->adOnde('evento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR evento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('evento_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('evento_cia IN ('.$lista_cias.')');

		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarEvento($linha['evento_acesso'], $linha['evento_indicador'])) $lista[$linha['evento_id']]=$linha['evento_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarEvento($linha['evento_acesso'], $linha['evento_projeto'], $linha['evento_tarefa'])) $lista[$linha['evento_id']]=$linha['evento_nome'];
			}
		break;	
	
	case 'foruns':
		$titulo = 'Fórum';
		$nao_ha='Não foi encontrado nenhum fórum';
		$nenhum='Nenhum fórum';
		if (trim($textobusca)) $sql->adOnde('forum_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('foruns.forum_id, forum_nome, forum_acesso');
		$sql->adOrdem('forum_nome');
		
		if ($dept_id) {
			$sql->esqUnir('forum_dept','forum_dept', 'forum_dept_forum=foruns.forum_id');
			$sql->adOnde('forum_dept='.(int)$dept_id.' OR forum_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('forum_cia', 'forum_cia', 'foruns.forum_id=forum_cia_forum');
			$sql->adOnde('forum_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR forum_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('forum_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('forum_cia IN ('.$lista_cias.')');
		
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarForum($linha['forum_acesso'],  $linha['forum_id'])) $lista[$linha['forum_id']]=$linha['forum_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarForum($linha['forum_acesso'],  $linha['forum_id'])) $lista[$linha['forum_id']]=$linha['forum_nome'];
			}
		break;
		
	case 'agenda_tipo':
		$titulo = 'Agenda';
		$nao_ha='Não foi encontrado nenhuma agenda';
		$nenhum='Nenhuma agenda';
		if (trim($textobusca)) $sql->adOnde('nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('agenda_tipo_id, nome');
		$sql->adOnde('usuario_id='.(int)$Aplic->usuario_id);
		$sql->adOrdem('nome');
		
		$lista = unirVetores(array('' => $nenhum), $sql->ListaChave());
		$sql->limpar();
		break;	
	
	case 'calendario':
		$titulo = 'Agenda';
		$nao_ha='Não foi encontrado nenhuma agenda';
		$nenhum='Nenhuma agenda';	
		if (trim($textobusca)) $sql->adOnde('calendario_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('calendario.calendario_id, calendario_nome, calendario_acesso');
		$sql->adOrdem('calendario_nome');

		if ($dept_id) {
			$sql->esqUnir('calendario_dept','calendario_dept', 'calendario_dept_calendario=calendario.calendario_id');
			$sql->adOnde('calendario_dept='.(int)$dept_id.' OR calendario_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('calendario_cia', 'calendario_cia', 'calendario.calendario_id=calendario_cia_calendario');
			$sql->adOnde('calendario_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR calendario_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('calendario_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('calendario_cia IN ('.$lista_cias.')');
		
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarCalendario($linha['calendario_acesso'], $linha['calendario_id'])) $lista[$linha['calendario_id']]=$linha['calendario_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarCalendario($linha['calendario_acesso'], $linha['calendario_id'])) $lista[$linha['calendario_id']]=$linha['calendario_nome'];
			}
		
		break;
	
	case 'ata':
		$titulo = 'Ata de Reunião';
		$nao_ha='Não foi encontrado nenhuma ata';
		$nenhum='Nenhuma ata';
		if (trim($textobusca)) $sql->adOnde('ata_titulo LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('ata.ata_id, ata_titulo, ata_numero, ata_acesso');
		$sql->adOrdem('ata_titulo');
		
		if ($dept_id) {
			$sql->esqUnir('ata_dept','ata_dept', 'ata_dept_ata=ata.ata_id');
			$sql->adOnde('ata_dept='.(int)$dept_id.' OR ata_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('ata_cia', 'ata_cia', 'ata.ata_id=ata_cia_ata');
			$sql->adOnde('ata_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR ata_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('ata_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('ata_cia IN ('.$lista_cias.')');
		
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarAta($linha['ata_acesso'], $linha['ata_id'])) $lista[$linha['ata_id']]=($linha['ata_numero'] < 10 ? '00' : ($linha['ata_numero'] < 100 ? '0' : '')).$linha['ata_numero'].($linha['ata_titulo'] ? ' - '.$linha['ata_titulo'] : ''); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarAta($linha['ata_acesso'], $linha['ata_id'])) $lista[$linha['ata_id']]=($linha['ata_numero'] < 10 ? '00' : ($linha['ata_numero'] < 100 ? '0' : '')).$linha['ata_numero'].($linha['ata_titulo'] ? ' - '.$linha['ata_titulo'] : '');
			}
		break;
		
	case 'monitoramento':
		$titulo = 'Reunião de Monitoramento';
		$nao_ha='Não foi encontrado nenhuma reunião de monitoramento';
		$nenhum='Nenhuma reunião de monitoramento';
		if (trim($textobusca)) $sql->adOnde('monitoramento_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('monitoramento.monitoramento_id, monitoramento_nome, monitoramento_acesso');
		$sql->adOrdem('monitoramento_nome');
		
		if ($dept_id) {
			$sql->esqUnir('monitoramento_depts','monitoramento_depts', 'monitoramento_depts.monitoramento_id=monitoramento.monitoramento_id');
			$sql->adOnde('monitoramento_dept='.(int)$dept_id.' OR monitoramento_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('monitoramento_cia', 'monitoramento_cia', 'monitoramento.monitoramento_id=monitoramento_cia_monitoramento');
			$sql->adOnde('monitoramento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR monitoramento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('monitoramento_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('monitoramento_cia IN ('.$lista_cias.')');

		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarMonitoramento($linha['monitoramento_acesso'], $linha['monitoramento_id'])) $lista[$linha['monitoramento_id']]=$linha['monitoramento_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarMonitoramento($linha['monitoramento_acesso'], $linha['monitoramento_id'])) $lista[$linha['monitoramento_id']]=$linha['monitoramento_nome']; 
			}
		break;	
	
	case 'operativo':
		include_once BASE_DIR.'/modulos/operativo/funcoes.php'; 
		$titulo = 'Plano Operativo';
		$nao_ha='Não foi encontrado nenhum plano de operativo';
		$nenhum='Nenhum  plano operativo';
		if (trim($textobusca)) $sql->adOnde('operativo_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('operativo.operativo_id, operativo_nome, operativo_acesso');
		$sql->adOrdem('operativo_nome');
		if ($dept_id) {
			$sql->esqUnir('operativo_depts','operativo_depts', 'operativo_depts.operativo_id=operativo.operativo_id');
			$sql->adOnde('operativo_dept='.(int)$dept_id.' OR operativo_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('operativo_cia', 'operativo_cia', 'operativo.operativo_id=operativo_cia_operativo');
			$sql->adOnde('operativo_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR operativo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('operativo_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('operativo_cia IN ('.$lista_cias.')');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarOperativo($linha['operativo_acesso'], $linha['operativo_id'])) $lista[$linha['operativo_id']]=$linha['operativo_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarOperativo($linha['operativo_acesso'], $linha['operativo_id'])) $lista[$linha['operativo_id']]=$linha['operativo_nome']; 
			}
		break;	
	
	case 'recursos':
		$titulo = 'Recurso';
		$nao_ha='Não foi encontrado nenhum recurso';
		$nenhum='Nenhum recurso';
		if (trim($textobusca)) $sql->adOnde('recurso_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('recursos.recurso_id, recurso_nome, recurso_nivel_acesso');
		$sql->adOrdem('recurso_nome');

		if ($dept_id) {
			$sql->esqUnir('recurso_depts','recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
			$sql->adOnde('recurso_dept='.(int)$dept_id.' OR recurso_depts.departamento_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('recurso_cia', 'recurso_cia', 'recursos.recurso_id=recurso_cia_recurso');
			$sql->adOnde('recurso_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR recurso_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('recurso_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');
		
		
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])) $lista[$linha['recurso_id']]=$linha['recurso_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])) $lista[$linha['recurso_id']]=$linha['recurso_nome']; 
			}
		break;
	
		case 'jornada':
		$titulo = 'Calendário';
		$nao_ha='Não foi encontrado nenhum calendário';
		$nenhum='Nenhum calendário';
		if (trim($textobusca)) $sql->adOnde('jornada_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('jornada_id, jornada_nome');
		$sql->adOrdem('jornada_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		foreach($achados as $linha) $lista[$linha['jornada_id']]=$linha['jornada_nome'];
		break;
	
	
	case 'praticas':
		$titulo = ucfirst($config['pratica']);
		$nao_ha='Não foi encontrad'.($config['genero_pratica']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['pratica'];
		$nenhum='Nenhum'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'];
		if (trim($textobusca)) $sql->adOnde('pratica_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('praticas.pratica_id, pratica_nome, pratica_acesso');
		if ($dept_id) {
			$sql->esqUnir('pratica_depts','pratica_depts', 'pratica_depts.pratica_id=praticas.pratica_id');
			$sql->adOnde('pratica_dept='.(int)$dept_id.' OR pratica_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('pratica_cia', 'pratica_cia', 'praticas.pratica_id=pratica_cia_pratica');
			$sql->adOnde('pratica_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR pratica_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pratica_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pratica_cia IN ('.$lista_cias.')');
		
		$sql->adOrdem('pratica_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPratica($linha['pratica_acesso'], $linha['pratica_id'])) $lista[$linha['pratica_id']]=$linha['pratica_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPratica($linha['pratica_acesso'], $linha['pratica_id'])) $lista[$linha['pratica_id']]=$linha['pratica_nome']; 
			}
		break;
	
	case 'problema':
		$titulo = ucfirst($config['problema']);
		$nao_ha='Não foi encontrad'.($config['genero_problema']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['problema'];
		$nenhum='Nenhum'.($config['genero_problema']=='a' ? 'a' : '').' '.$config['problema'];
		if (trim($textobusca)) $sql->adOnde('problema_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('problema.problema_id, problema_nome, problema_acesso');
		if ($dept_id) {
			$sql->esqUnir('problema_depts','problema_depts', 'problema_depts.problema_id=problema.problema_id');
			$sql->adOnde('problema_dept='.(int)$dept_id.' OR problema_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('problema_cia', 'problema_cia', 'problema.problema_id=problema_cia_problema');
			$sql->adOnde('problema_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR problema_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('problema_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('problema_cia IN ('.$lista_cias.')');
		
		$sql->adOrdem('problema_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarProblema($linha['problema_acesso'], $linha['problema_id'])) $lista[$linha['problema_id']]=$linha['problema_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarProblema($linha['problema_acesso'], $linha['problema_id'])) $lista[$linha['problema_id']]=$linha['problema_nome']; 
			}
		break;
	
	case 'instrumento':
		$titulo = ucfirst($config['instrumento']);
		$nao_ha='Não foi encontrad'.($config['genero_instrumento']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['instrumento'];
		$nenhum='Nenhum'.($config['genero_instrumento']=='a' ? 'a' : '').' '.$config['instrumento'];
		if (trim($textobusca)) $sql->adOnde('instrumento_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('instrumento.instrumento_id, instrumento_nome, instrumento_acesso');
		if ($dept_id) {
			$sql->esqUnir('instrumento_depts','instrumento_depts', 'instrumento_depts.instrumento_id=instrumento.instrumento_id');
			$sql->adOnde('instrumento_dept='.(int)$dept_id.' OR instrumento_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('instrumento_cia', 'instrumento_cia', 'instrumento.instrumento_id=instrumento_cia_instrumento');
			$sql->adOnde('instrumento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR instrumento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('instrumento_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('instrumento_cia IN ('.$lista_cias.')');
		$sql->adOrdem('instrumento_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarInstrumento($linha['instrumento_acesso'], $linha['instrumento_id'])) $lista[$linha['instrumento_id']]=$linha['instrumento_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarInstrumento($linha['instrumento_acesso'], $linha['instrumento_id'])) $lista[$linha['instrumento_id']]=$linha['instrumento_nome']; 
			}
		break;
		
	case 'objetivos_estrategicos':
		if (isset($_REQUEST['pg_ano'])) $Aplic->setEstado('pg_ano', getParam($_REQUEST, 'pg_ano', null));
		$titulo = ucfirst($config['objetivo']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'';
		$nenhum='Nenh'.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'';
		if (trim($textobusca)) $sql->adOnde('pg_objetivo_estrategico_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('objetivos_estrategicos.pg_objetivo_estrategico_id, pg_objetivo_estrategico_nome, pg_objetivo_estrategico_acesso');
		if ($dept_id) {
			$sql->esqUnir('objetivos_estrategicos_depts','objetivos_estrategicos_depts', 'objetivos_estrategicos_depts.pg_objetivo_estrategico_id=objetivos_estrategicos.pg_objetivo_estrategico_id');
			$sql->adOnde('pg_objetivo_estrategico_dept='.(int)$dept_id.' OR objetivos_estrategicos_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('objetivo_cia', 'objetivo_cia', 'objetivos_estrategicos.pg_objetivo_estrategico_id=objetivo_cia_objetivo');
			$sql->adOnde('pg_objetivo_estrategico_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR objetivo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_objetivo_estrategico_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_objetivo_estrategico_cia IN ('.$lista_cias.')');
		$sql->adOnde('pg_objetivo_estrategico_ativo = 1');
		$sql->adOrdem('pg_objetivo_estrategico_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarObjetivo($linha['pg_objetivo_estrategico_acesso'], $linha['pg_objetivo_estrategico_id'])) $lista[$linha['pg_objetivo_estrategico_id']]=converte_texto_grafico($linha['pg_objetivo_estrategico_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarObjetivo($linha['pg_objetivo_estrategico_acesso'], $linha['pg_objetivo_estrategico_id'])) $lista[$linha['pg_objetivo_estrategico_id']]=converte_texto_grafico($linha['pg_objetivo_estrategico_nome']); 
			}
		break;	
	
	case 'beneficio':
		$titulo = ucfirst($config['beneficio']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].'';
		$nenhum='Nenh'.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].'';
		if (trim($textobusca)) $sql->adOnde('beneficio_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('beneficio.beneficio_id, beneficio_nome, beneficio_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('beneficio_dept','beneficio_dept', 'beneficio_dept_beneficio=beneficio.beneficio_id');
			$sql->adOnde('beneficio_dept='.(int)$dept_id.' OR beneficio_dept_dept='.(int)$dept_id);
			}	
		elseif ($cia_id || $lista_cias) {
			$sql->esqUnir('beneficio_cia', 'beneficio_cia', 'beneficio_cia_beneficio=beneficio.beneficio_id');
			$sql->adOnde('beneficio_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR beneficio_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}		
			
		$sql->adOnde('beneficio_ativo = 1');
		$sql->adOrdem('beneficio_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarBeneficio($linha['beneficio_acesso'], $linha['beneficio_id'])) $lista[$linha['beneficio_id']]=converte_texto_grafico($linha['beneficio_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarBeneficio($linha['beneficio_acesso'], $linha['beneficio_id'])) $lista[$linha['beneficio_id']]=converte_texto_grafico($linha['beneficio_nome']); 
			}
		break;	
	
	case 'programa':
		$titulo = ucfirst($config['programa']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'';
		$nenhum='Nenh'.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'';
		if (trim($textobusca)) $sql->adOnde('programa_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('programa.programa_id, programa_nome, programa_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('programa_dept','programa_dept', 'programa_dept_programa=programa.programa_id');
			$sql->adOnde('programa_dept='.(int)$dept_id.' OR programa_dept_dept='.(int)$dept_id);
			}	
		elseif ($cia_id || $lista_cias) {
			$sql->esqUnir('programa_cia', 'programa_cia', 'programa_cia_programa=programa.programa_id');
			$sql->adOnde('programa_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR programa_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}		
		
		$sql->adOnde('programa_ativo = 1');
		$sql->adOrdem('programa_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPrograma($linha['programa_acesso'], $linha['programa_id'])) $lista[$linha['programa_id']]=converte_texto_grafico($linha['programa_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPrograma($linha['programa_acesso'], $linha['programa_id'])) $lista[$linha['programa_id']]=converte_texto_grafico($linha['programa_nome']); 
			}
		break;	
	
	case 'canvas':
		$titulo = ucfirst($config['canvas']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'';
		$nenhum='Nenh'.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'];
		if (trim($textobusca)) $sql->adOnde('canvas_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('canvas.canvas_id, canvas_nome, canvas_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('canvas_dept','canvas_dept', 'canvas_dept_canvas=canvas.canvas_id');
			$sql->adOnde('canvas_dept='.(int)$dept_id.' OR canvas_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('canvas_cia', 'canvas_cia', 'canvas.canvas_id=canvas_cia_canvas');
			$sql->adOnde('canvas_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR canvas_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('canvas_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('canvas_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('canvas_ativo = 1');
		$sql->adOrdem('canvas_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarCanvas($linha['canvas_acesso'], $linha['canvas_id'])) $lista[$linha['canvas_id']]=converte_texto_grafico($linha['canvas_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarCanvas($linha['canvas_acesso'], $linha['canvas_id'])) $lista[$linha['canvas_id']]=converte_texto_grafico($linha['canvas_nome']); 
			}
		break;		
	
	case 'risco':
		$titulo = ucfirst($config['risco']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'';
		$nenhum='Nenh'.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'';
		if (trim($textobusca)) $sql->adOnde('risco_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('risco.risco_id, risco_nome, risco_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('risco_depts','risco_depts', 'risco_depts.risco_id=risco.risco_id');
			$sql->adOnde('risco_dept='.(int)$dept_id.' OR risco_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('risco_cia', 'risco_cia', 'risco.risco_id=risco_cia_risco');
			$sql->adOnde('risco_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR risco_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('risco_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('risco_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('risco_ativo = 1');
		$sql->adOrdem('risco_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarRisco($linha['risco_acesso'], $linha['risco_id'])) $lista[$linha['risco_id']]=converte_texto_grafico($linha['risco_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarRisco($linha['risco_acesso'], $linha['risco_id'])) $lista[$linha['risco_id']]=converte_texto_grafico($linha['risco_nome']); 
			}
		break;	

	case 'risco_resposta':
		$titulo = ucfirst($config['risco_resposta']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'';
		$nenhum='Nenh'.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'';
		if (trim($textobusca)) $sql->adOnde('risco_resposta_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('risco_resposta.risco_resposta_id, risco_resposta_nome, risco_resposta_acesso');

		if ($dept_id) {
			$sql->esqUnir('risco_resposta_depts','risco_resposta_depts', 'risco_resposta_depts.risco_resposta_id=risco_resposta.risco_resposta_id');
			$sql->adOnde('risco_resposta_dept='.(int)$dept_id.' OR risco_resposta_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('risco_resposta_cia', 'risco_resposta_cia', 'risco_resposta.risco_resposta_id=risco_resposta_cia_risco_resposta');
			$sql->adOnde('risco_resposta_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR risco_resposta_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('risco_resposta_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('risco_resposta_cia IN ('.$lista_cias.')');
		
		$sql->adOrdem('risco_resposta_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarRiscoResposta($linha['risco_resposta_acesso'], $linha['risco_resposta_id'])) $lista[$linha['risco_resposta_id']]=converte_texto_grafico($linha['risco_resposta_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarRiscoResposta($linha['risco_resposta_acesso'], $linha['risco_resposta_id'])) $lista[$linha['risco_resposta_id']]=converte_texto_grafico($linha['risco_resposta_nome']); 
			}
		break;	

	case 'tgn':
		$titulo = ucfirst($config['tgn']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'';
		$nenhum='Nenh'.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'';
		if (trim($textobusca)) $sql->adOnde('tgn_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('tgn.tgn_id, tgn_nome, tgn_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('tgn_dept','tgn_dept', 'tgn_dept_tgn=tgn.tgn_id');
			$sql->adOnde('tgn_dept='.(int)$dept_id.' OR tgn_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('tgn_cia', 'tgn_cia', 'tgn.tgn_id=tgn_cia_tgn');
			$sql->adOnde('tgn_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tgn_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('tgn_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('tgn_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('tgn_ativo = 1');
		$sql->adOrdem('tgn_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarTgn($linha['tgn_acesso'], $linha['tgn_id'])) $lista[$linha['tgn_id']]=converte_texto_grafico($linha['tgn_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTgn($linha['tgn_acesso'], $linha['tgn_id'])) $lista[$linha['tgn_id']]=converte_texto_grafico($linha['tgn_nome']); 
			}
		break;	

	case 'estrategias':
		$titulo = 'Iniciativa';
		$nao_ha='Não foi encontrado nenhuma iniciativa';
		$nenhum='Nenhuma iniciativa';
		if (trim($textobusca)) $sql->adOnde('pg_estrategia_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('estrategias.pg_estrategia_id, pg_estrategia_nome, pg_estrategia_acesso');
		if ($dept_id) {
			$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
			$sql->adOnde('pg_estrategia_dept='.(int)$dept_id.' OR estrategias_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('estrategia_cia', 'estrategia_cia', 'estrategias.pg_estrategia_id=estrategia_cia_estrategia');
			$sql->adOnde('pg_estrategia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR estrategia_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_estrategia_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_estrategia_cia IN ('.$lista_cias.')');
		$sql->adOnde('pg_estrategia_ativo = 1');
		$sql->adOrdem('pg_estrategia_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarEstrategia($linha['pg_estrategia_acesso'], $linha['pg_estrategia_id'])) $lista[$linha['pg_estrategia_id']]=converte_texto_grafico($linha['pg_estrategia_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarEstrategia($linha['pg_estrategia_acesso'], $linha['pg_estrategia_id'])) $lista[$linha['pg_estrategia_id']]=converte_texto_grafico($linha['pg_estrategia_nome']); 
			}
		break;		
		
	case 'fatores_criticos':
		$titulo = 'Fator crítico de sucesso';
		$nao_ha='Não foi encontrado nenhum'.($config['genero_fator']=='a' ? 'a' : '').' '.$config['fator'];
		$nenhum='Nenhum'.($config['genero_fator']=='a' ? 'a' : '').' '.$config['fator'];
		if (trim($textobusca)) $sql->adOnde('pg_fator_critico_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('fatores_criticos.pg_fator_critico_id, pg_fator_critico_nome, pg_fator_critico_acesso');
		if ($dept_id) {
			$sql->esqUnir('fatores_criticos_depts','fatores_criticos_depts', 'fatores_criticos_depts.pg_fator_critico_id=fatores_criticos.pg_fator_critico_id');
			$sql->adOnde('pg_fator_critico_dept='.(int)$dept_id.' OR fatores_criticos_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('fator_cia', 'fator_cia', 'fatores_criticos.pg_fator_critico_id=fator_cia_fator');
			$sql->adOnde('pg_fator_critico_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR fator_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_fator_critico_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_fator_critico_cia IN ('.$lista_cias.')');
		$sql->adOnde('pg_fator_critico_ativo = 1');
		$sql->adOrdem('pg_fator_critico_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarFator($linha['pg_fator_critico_acesso'], $linha['pg_fator_critico_id'])) $lista[$linha['pg_fator_critico_id']]=converte_texto_grafico($linha['pg_fator_critico_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarFator($linha['pg_fator_critico_acesso'], $linha['pg_fator_critico_id'])) $lista[$linha['pg_fator_critico_id']]=converte_texto_grafico($linha['pg_fator_critico_nome']); 
			}
		break;	
		
	case 'perspectivas':
		$titulo = ucfirst($config['perspectivas']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'];
		$nenhum='Nenh'.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'];
		if (trim($textobusca)) $sql->adOnde('pg_perspectiva_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('perspectivas.pg_perspectiva_id, pg_perspectiva_nome, pg_perspectiva_acesso');
		if ($dept_id) {
			$sql->esqUnir('perspectivas_depts','perspectivas_depts', 'perspectivas_depts.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
			$sql->adOnde('pg_perspectiva_dept='.(int)$dept_id.' OR perspectivas_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('perspectiva_cia', 'perspectiva_cia', 'perspectivas.pg_perspectiva_id=perspectiva_cia_perspectiva');
			$sql->adOnde('pg_perspectiva_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR perspectiva_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_perspectiva_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_perspectiva_cia IN ('.$lista_cias.')');
		$sql->adOnde('pg_perspectiva_ativo = 1');
		$sql->adOrdem('pg_perspectiva_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarPerspectiva($linha['pg_perspectiva_acesso'], $linha['pg_perspectiva_id'])) $lista[$linha['pg_perspectiva_id']]=converte_texto_grafico($linha['pg_perspectiva_nome']); 
				}
			}
		else {
			foreach($achados as $linha) {
				if (permiteAcessarPerspectiva($linha['pg_perspectiva_acesso'], $linha['pg_perspectiva_id'])) $lista[$linha['pg_perspectiva_id']]=converte_texto_grafico($linha['pg_perspectiva_nome']); 
				}
			}
		break;			
	
	case 'licao':
		$titulo = ucfirst($config['licoes']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_licao']=='a' ? 'uma' : 'um').' '.$config['licao'];
		$nenhum='Nenh'.($config['genero_licao']=='a' ? 'uma' : 'um').' '.$config['licao'];
		if (trim($textobusca)) $sql->adOnde('licao_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('licao.licao_id, licao_nome, licao_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('licao_dept','licao_dept', 'licao_dept_licao=licao.licao_id');
			$sql->adOnde('licao_dept='.(int)$dept_id.' OR licao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('licao_cia', 'licao_cia', 'licao.licao_id=licao_cia_licao');
			$sql->adOnde('licao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR licao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('licao_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('licao_cia IN ('.$lista_cias.')');
		
		$sql->adOrdem('licao_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarLicao($linha['licao_acesso'], $linha['licao_id'])) $lista[$linha['licao_id']]=converte_texto_grafico($linha['licao_nome']); 
			}	
		else {
			foreach($achados as $linha) if (permiteAcessarLicao($linha['licao_acesso'], $linha['licao_id'])) $lista[$linha['licao_id']]=converte_texto_grafico($linha['licao_nome']); 
			}
		break;
	
	case 'tema':
		$titulo = ucfirst($config['tema']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'';
		$nenhum='Nenh'.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'';
		if (trim($textobusca)) $sql->adOnde('tema_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('tema.tema_id, tema_nome, tema_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('tema_depts','tema_depts', 'tema_depts.tema_id=tema.tema_id');
			$sql->adOnde('tema_dept='.(int)$dept_id.' OR tema_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('tema_cia', 'tema_cia', 'tema.tema_id=tema_cia_tema');
			$sql->adOnde('tema_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tema_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('tema_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('tema_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('tema_ativo = 1');
		$sql->adOrdem('tema_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarTema($linha['tema_acesso'], $linha['tema_id'])) $lista[$linha['tema_id']]=converte_texto_grafico($linha['tema_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTema($linha['tema_acesso'], $linha['tema_id'])) $lista[$linha['tema_id']]=converte_texto_grafico($linha['tema_nome']); 
			}
		break;	
	
	case 'me':
		$titulo = ucfirst($config['me']);
		$nao_ha='Não foi encontrado nenh'.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'';
		$nenhum='Nenh'.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'';
		if (trim($textobusca)) $sql->adOnde('me_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('me.me_id, me_nome, me_acesso');
		if ($dept_id) {
			$sql->esqUnir('me_dept','me_dept', 'me_dept_me=me.me_id');
			$sql->adOnde('me_dept='.(int)$dept_id.' OR me_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('me_cia', 'me_cia', 'me.me_id=me_cia_me');
			$sql->adOnde('me_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR me_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('me_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('me_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('me_ativo = 1');
		$sql->adOrdem('me_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarMe($linha['me_acesso'], $linha['me_id'])) $lista[$linha['me_id']]=converte_texto_grafico($linha['me_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarMe($linha['me_acesso'], $linha['me_id'])) $lista[$linha['me_id']]=converte_texto_grafico($linha['me_nome']); 
			}
		break;	
	
	case 'demandas':
		$titulo = 'Demanda';
		$nao_ha='Não foi encontrado nenhuma demanda';
		$nenhum='Nenhuma demanda';
		$clausula = getParam($_REQUEST, 'clausula', '');
		if (trim($textobusca)) $sql->adOnde('demanda_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('demandas.demanda_id, demanda_nome, demanda_acesso');
		if ($dept_id) {
			$sql->esqUnir('demanda_depts','demanda_depts', 'demanda_depts.demanda_id=demandas.demanda_id');
			$sql->adOnde('demanda_dept='.(int)$dept_id.' OR demanda_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('demanda_cia', 'demanda_cia', 'demandas.demanda_id=demanda_cia_demanda');
			$sql->adOnde('demanda_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR demanda_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('demanda_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('demanda_cia IN ('.$lista_cias.')');
		
		if ($clausula) $sql->adOnde($clausula);
		$sql->adOrdem('demanda_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarDemanda($linha['demanda_acesso'], $linha['demanda_id'])) $lista[$linha['demanda_id']]=converte_texto_grafico($linha['demanda_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarDemanda($linha['demanda_acesso'], $linha['demanda_id'])) $lista[$linha['demanda_id']]=converte_texto_grafico($linha['demanda_nome']); 
			}
		break;	
	
	case 'brainstorm':
		$titulo = 'Brainstorm';
		$nao_ha='Não foi encontrado nenhum brainstorm';
		$nenhum='Nenhum brainstorm';
		$clausula = getParam($_REQUEST, 'clausula', '');
		if (trim($textobusca)) $sql->adOnde('brainstorm_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('brainstorm.brainstorm_id, brainstorm_nome, brainstorm_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('brainstorm_depts','brainstorm_depts', 'brainstorm_depts.brainstorm_id=brainstorm.brainstorm_id');
			$sql->adOnde('brainstorm_dept='.(int)$dept_id.' OR brainstorm_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('brainstorm_cia', 'brainstorm_cia', 'brainstorm.brainstorm_id=brainstorm_cia_brainstorm');
			$sql->adOnde('brainstorm_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR brainstorm_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('brainstorm_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('brainstorm_cia IN ('.$lista_cias.')');

		if ($clausula) $sql->adOnde($clausula);
		$sql->adOrdem('brainstorm_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarBrainstorm($linha['brainstorm_acesso'], $linha['brainstorm_id'])) $lista[$linha['brainstorm_id']]=converte_texto_grafico($linha['brainstorm_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarBrainstorm($linha['brainstorm_acesso'], $linha['brainstorm_id'])) $lista[$linha['brainstorm_id']]=converte_texto_grafico($linha['brainstorm_nome']); 
			}
		break;	
				
	case 'avaliacao':
		$titulo = 'Avaliação';
		$nao_ha='Não foi encontrado nenhuma avaliação';
		$nenhum='Nenhuma avaliação';
		$clausula = getParam($_REQUEST, 'clausula', '');
		if (trim($textobusca)) $sql->adOnde('avaliacao_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('avaliacao_id, avaliacao_nome, avaliacao_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('avaliacao_dept','avaliacao_dept', 'avaliacao_dept_avaliacao=avaliacao.avaliacao_id');
			$sql->adOnde('avaliacao_dept='.(int)$dept_id.' OR avaliacao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('avaliacao_cia', 'avaliacao_cia', 'avaliacao.avaliacao_id=avaliacao_cia_avaliacao');
			$sql->adOnde('avaliacao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR avaliacao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('avaliacao_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('avaliacao_cia IN ('.$lista_cias.')');
		
		if ($clausula) $sql->adOnde($clausula);
		$sql->adOrdem('avaliacao_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarAvaliacao($linha['avaliacao_acesso'], $linha['avaliacao_id'])) $lista[$linha['avaliacao_id']]=converte_texto_grafico($linha['avaliacao_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarAvaliacao($linha['avaliacao_acesso'], $linha['avaliacao_id'])) $lista[$linha['avaliacao_id']]=converte_texto_grafico($linha['avaliacao_nome']); 
			}
		break;		
		
	case 'agenda':
		$titulo = 'Compromisso';
		$nao_ha='Não foi encontrado nenhum compromisso';
		$nenhum='Nenhum compromisso';
		$clausula = getParam($_REQUEST, 'clausula', '');
		if (trim($textobusca)) $sql->adOnde('agenda_titulo LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('agenda_id, agenda_titulo, agenda_acesso');
		$sql->adOnde('agenda_dono='.(int)$Aplic->usuario_id);
		if ($clausula) $sql->adOnde($clausula);
		$sql->adOrdem('agenda_titulo');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) $lista[$linha['agenda_id']]=converte_texto_grafico($linha['agenda_titulo']); 
			}
		else{ 
			foreach($achados as $linha) $lista[$linha['agenda_id']]=converte_texto_grafico($linha['agenda_titulo']); 
			}
		break;		
	
	case 'metas':
		$titulo = ucfirst($config['meta']);
		$nao_ha='Não foi encontrado nenhum'.($config['genero_meta']=='a' ? 'a' : '').' '.$config['meta'];
		$nenhum='Nenhum'.($config['genero_meta']=='a' ? 'a' : '').' '.$config['meta'];
		if (trim($textobusca)) $sql->adOnde('pg_meta_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('metas.pg_meta_id, pg_meta_nome, pg_meta_acesso');
		if ($dept_id) {
			$sql->esqUnir('metas_depts','metas_depts', 'metas_depts.pg_meta_id=metas.pg_meta_id');
			$sql->adOnde('pg_meta_dept='.(int)$dept_id.' OR metas_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('meta_cia', 'meta_cia', 'metas.pg_meta_id=meta_cia_meta');
			$sql->adOnde('pg_meta_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR meta_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_meta_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_meta_cia IN ('.$lista_cias.')');
		$sql->adOrdem('pg_meta_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarMeta($linha['pg_meta_acesso'], $linha['pg_meta_id'])) $lista[$linha['pg_meta_id']]=converte_texto_grafico($linha['pg_meta_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarMeta($linha['pg_meta_acesso'], $linha['pg_meta_id'])) $lista[$linha['pg_meta_id']]=converte_texto_grafico($linha['pg_meta_nome']); 
			}
		break;	
			
	case 'pratica_indicador':
		$titulo = 'Indicador';
		$nao_ha='Não foi encontrado nenhum indicador';
		$nenhum='Nenhum indicador';
		if (trim($textobusca)) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('pratica_indicador.pratica_indicador_id, pratica_indicador_nome, pratica_indicador_acesso');
		if ($Aplic->profissional) {
			$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
			if ($pratica_indicador_projeto)$sql->adOnde('pratica_indicador_gestao_projeto='.(int)$pratica_indicador_projeto);
			if ($pratica_indicador_tarefa)$sql->adOnde('pratica_indicador_gestao_tarefa='.(int)$pratica_indicador_tarefa);
			if ($pratica_indicador_pratica)$sql->adOnde('pratica_indicador_gestao_pratica='.(int)$pratica_indicador_pratica);
			if ($pratica_indicador_tema)$sql->adOnde('pratica_indicador_gestao_tema='.(int)$pratica_indicador_tema);
			if ($pratica_indicador_objetivo_estrategico)$sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$pratica_indicador_objetivo_estrategico);
			if ($pratica_indicador_estrategia)$sql->adOnde('pratica_indicador_gestao_estrategia='.(int)$pratica_indicador_estrategia);
			if ($pratica_indicador_acao)$sql->adOnde('pratica_indicador_gestao_acao='.(int)$pratica_indicador_acao);
			if ($pratica_indicador_fator)$sql->adOnde('pratica_indicador_gestao_fator='.(int)$pratica_indicador_fator);
			if ($pratica_indicador_meta)$sql->adOnde('pratica_indicador_gestao_meta='.(int)$pratica_indicador_meta);
			}
		else {	
			if ($pratica_indicador_projeto)$sql->adOnde('pratica_indicador_projeto='.(int)$pratica_indicador_projeto);
			if ($pratica_indicador_tarefa)$sql->adOnde('pratica_indicador_tarefa='.(int)$pratica_indicador_tarefa);
			if ($pratica_indicador_pratica)$sql->adOnde('pratica_indicador_pratica='.(int)$pratica_indicador_pratica);
			if ($pratica_indicador_tema)$sql->adOnde('pratica_indicador_tema='.(int)$pratica_indicador_tema);
			if ($pratica_indicador_objetivo_estrategico)$sql->adOnde('pratica_indicador_objetivo_estrategico='.(int)$pratica_indicador_objetivo_estrategico);
			if ($pratica_indicador_estrategia)$sql->adOnde('pratica_indicador_estrategia='.(int)$pratica_indicador_estrategia);
			if ($pratica_indicador_acao)$sql->adOnde('pratica_indicador_acao='.(int)$pratica_indicador_acao);
			if ($pratica_indicador_fator)$sql->adOnde('pratica_indicador_fator='.(int)$pratica_indicador_fator);
			if ($pratica_indicador_meta)$sql->adOnde('pratica_indicador_meta='.(int)$pratica_indicador_meta);
			}
		
		if ($dept_id) {
			$sql->esqUnir('pratica_indicador_depts','pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
			$sql->adOnde('pratica_indicador_dept='.(int)$dept_id.' OR pratica_indicador_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('indicador_cia', 'indicador_cia', 'pratica_indicador.pratica_indicador_id=indicador_cia_indicador');
			$sql->adOnde('pratica_indicador_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR indicador_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pratica_indicador_cia IN ('.$lista_cias.')');
		
		$sql->adOrdem('pratica_indicador_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarIndicador($linha['pratica_indicador_acesso'], $linha['pratica_indicador_id'])) $lista[$linha['pratica_indicador_id']]=$linha['pratica_indicador_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarIndicador($linha['pratica_indicador_acesso'], $linha['pratica_indicador_id'])) $lista[$linha['pratica_indicador_id']]=$linha['pratica_indicador_nome']; 
			}
		break;					

	case 'plano_acao':
		$plano_acao_id = intval(getParam($_REQUEST, 'plano_acao_id', 0));
		$titulo = 'Plano de Ação';
		$nao_ha='Não foi encontrad'.($config['genero_acao']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['acao'];
		$nenhum='Nenhum'.($config['genero_acao']=='a' ? 'a' : '').' '.$config['acao'];
		if (trim($textobusca)) $sql->adOnde('plano_acao_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('plano_acao.plano_acao_id, plano_acao_nome, plano_acao_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('plano_acao_depts','plano_acao_depts', 'plano_acao_depts.plano_acao_id=plano_acao.plano_acao_id');
			$sql->adOnde('plano_acao_dept='.(int)$dept_id.' OR plano_acao_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('plano_acao_cia', 'plano_acao_cia', 'plano_acao.plano_acao_id=plano_acao_cia_plano_acao');
			$sql->adOnde('plano_acao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_acao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('plano_acao_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('plano_acao_cia IN ('.$lista_cias.')');
		
		$sql->adOrdem('plano_acao_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPlanoAcao($linha['plano_acao_acesso'], $linha['plano_acao_id'])) $lista[$linha['plano_acao_id']]=$linha['plano_acao_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPlanoAcao($linha['plano_acao_acesso'], $linha['plano_acao_id'])) $lista[$linha['plano_acao_id']]=$linha['plano_acao_nome']; 
			}
		break;			

	case 'links':
		$titulo = 'Link';
		$nao_ha='Não foi encontrado nenhum link';
		$nenhum='Nenhum link';
		if (trim($textobusca)) $sql->adOnde('link_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('links.link_id, link_nome, link_acesso');
		$sql->adOrdem('link_nome');
		
		if ($dept_id) {
			$sql->esqUnir('link_dept','link_dept', 'link_dept_link=links.link_id');
			$sql->adOnde('link_dept='.(int)$dept_id.' OR link_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('link_cia', 'link_cia', 'links.link_id=link_cia_link');
			$sql->adOnde('link_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR link_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('link_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('link_cia IN ('.$lista_cias.')');
		
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarLink($linha['link_acesso'], $linha['link_id'])) $lista[$linha['link_id']]=$linha['link_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarLink($linha['link_acesso'], $linha['link_id'])) $lista[$linha['link_id']]=$linha['link_nome'];
			}
		break;	
	
	case 'gut':
		$titulo = 'G.U.T.';
		$nao_ha='Não foi encontrado nenhuma matriz G.U.T.';
		$nenhum='Nenhuma matriz G.U.T.';
		if (trim($textobusca)) $sql->adOnde('gut_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('gut.gut_id, gut_nome, gut_acesso');
		$sql->adOrdem('gut_nome');
		
		if ($dept_id) {
			$sql->esqUnir('gut_depts','gut_depts', 'gut_depts.gut_id=gut.gut_id');
			$sql->adOnde('gut_dept='.(int)$dept_id.' OR gut_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('gut_cia', 'gut_cia', 'gut.gut_id=gut_cia_gut');
			$sql->adOnde('gut_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR gut_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('gut_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('gut_cia IN ('.$lista_cias.')');
		
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarGUT($linha['gut_acesso'], $linha['gut_id'])) $lista[$linha['gut_id']]=$linha['gut_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarGUT($linha['gut_acesso'], $linha['gut_id'])) $lista[$linha['gut_id']]=$linha['gut_nome']; 
			}
		break;	
	
	case 'causa_efeito':
		$titulo = 'Diagrama de Causa-Efeito';
		$nao_ha='Não foi encontrado nenhum diagrama de causa-efeito';
		$nenhum='Nenhum diagrama de causa-efeito';
		if (trim($textobusca)) $sql->adOnde('causa_efeito_nome LIKE \'%'.$textobusca.'%\' OR causa_efeito_objeto LIKE \'%'.$textobusca.'%\' OR causa_efeito_descricao LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('causa_efeito.causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
		$sql->adOrdem('causa_efeito_nome');
		if ($dept_id) {
			$sql->esqUnir('causa_efeito_depts','causa_efeito_depts', 'causa_efeito_depts.causa_efeito_id=causa_efeito.causa_efeito_id');
			$sql->adOnde('causa_efeito_dept='.(int)$dept_id.' OR causa_efeito_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('causa_efeito_cia', 'causa_efeito_cia', 'causa_efeito.causa_efeito_id=causa_efeito_cia_causa_efeito');
			$sql->adOnde('causa_efeito_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR causa_efeito_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('causa_efeito_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('causa_efeito_cia IN ('.$lista_cias.')');
		
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarCausa_efeito($linha['causa_efeito_acesso'], $linha['causa_efeito_id'])) $lista[$linha['causa_efeito_id']]=$linha['causa_efeito_nome']; 
			}
		else{
			foreach($achados as $linha) if (permiteAcessarCausa_efeito($linha['causa_efeito_acesso'], $linha['causa_efeito_id'])) $lista[$linha['causa_efeito_id']]=$linha['causa_efeito_nome']; 
			}
		break;				
	
	case 'projetos':
		$titulo = ucfirst($config['projeto']).($aceita_portfolio ? ' / '.ucfirst($config['portfolio']) : '');
		$nenhum='Nenhum'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].($aceita_portfolio ? ' / portfólio' : '');
		$sql->adTabela('projetos', 'pr');
		$sql->esqUnir('usuarios', 'u', 'pr.projeto_responsavel = u.usuario_id');
		$sql->esqUnir('cias', 'cias', 'pr.projeto_cia = cias.cia_id');
		$sql->esqUnir('contatos', 'ct', 'ct.contato_id = u.usuario_contato');
		$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_projeto = pr.projeto_id');
		if ($estado_sigla) $sql->adOnde('pr.projeto_estado=\''.$estado_sigla.'\'');
		if ($municipio_id) $sql->adOnde('pr.projeto_cidade IN ('.$municipio_id.')');
		if (!$portfolio && !$portfolio_pai && !$aceita_portfolio) $sql->adOnde('pr.projeto_portfolio IS NULL OR pr.projeto_portfolio=0');
		elseif($portfolio && !$portfolio_pai)  $sql->adOnde('pr.projeto_portfolio=1 AND (pr.projeto_plano_operativo=0 OR pr.projeto_plano_operativo IS NULL)');
		elseif ($portfolio_pai){
			$sql->esqUnir('projeto_portfolio', 'projeto_portfolio', 'projeto_portfolio_filho = pr.projeto_id');
			$sql->adOnde('projeto_portfolio_pai = '.(int)$portfolio_pai);
			}
		if ($favorito_id){
			$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'pr.projeto_id=favoritos_lista.campo_id');
			$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
			$sql->adOnde('favoritos.favorito_id IN ('.$favorito_id.')');
			}
		
		if (!$nao_apenas_superiores) $sql->adOnde('pr.projeto_superior IS NULL OR pr.projeto_superior=0 OR pr.projeto_superior=pr.projeto_id');		
		if ($projetostatus){
			if ($projetostatus == -1) $sql->adOnde('projeto_ativo = 1');
			elseif ($projetostatus == -2) $sql->adOnde('projeto_ativo = 0');
			elseif ($projetostatus > 0) $sql->adOnde('projeto_status IN ('.$projetostatus.')');
			}	
		
		if ($dept_id && !$favorito_id) {
			$sql->esqUnir('projeto_depts','projeto_depts', 'projeto_depts.projeto_id=pr.projeto_id');
			$sql->adOnde('projeto_dept='.(int)$dept_id.' OR projeto_depts.departamento_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias) && !$favorito_id) {
			$sql->esqUnir('projeto_cia', 'projeto_cia', 'pr.projeto_id=projeto_cia_projeto');
			$sql->adOnde('projeto_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias && !$favorito_id) $sql->adOnde('projeto_cia='.(int)$cia_id);
		elseif ($lista_cias && !$favorito_id) $sql->adOnde('projeto_cia IN ('.$lista_cias.')');
		
		if ($projeto_tipo > -1)	$sql->adOnde('pr.projeto_tipo IN ('.$projeto_tipo.')');
		if ($projeto_setor) $sql->adOnde('pr.projeto_setor = '.(int)$projeto_setor);
		if ($projeto_segmento) $sql->adOnde('pr.projeto_segmento = '.(int)$projeto_segmento);
		if ($projeto_intervencao) $sql->adOnde('pr.projeto_intervencao = '.(int)$projeto_intervencao);
		if ($projeto_tipo_intervencao) $sql->adOnde('pr.projeto_tipo_intervencao = '.(int)$projeto_tipo_intervencao);
		if ($supervisor) $sql->adOnde('pr.projeto_supervisor IN ('.$supervisor.')');
		if ($autoridade) $sql->adOnde('pr.projeto_autoridade IN ('.$autoridade.')');
		if ($responsavel) $sql->adOnde('pr.projeto_responsavel IN ('.$responsavel.')');
		if (trim($projtextobusca)) $sql->adOnde('pr.projeto_nome LIKE \'%'.$projtextobusca.'%\' OR pr.projeto_descricao LIKE \'%'.$projtextobusca.'%\' OR pr.projeto_objetivos LIKE \'%'.$projtextobusca.'%\' OR pr.projeto_como LIKE \'%'.$projtextobusca.'%\' OR pr.projeto_codigo LIKE \'%'.$projtextobusca.'%\'');
		
		$sql->adCampo('pr.projeto_id, pr.projeto_nome, pr.projeto_acesso, projeto_portfolio');
		$sql->adOnde('pr.projeto_template='.($template ? '1' : '0 OR pr.projeto_template IS NULL'));
		if ($projeto_id)	$sql->adOnde('pr.projeto_id!='.(int)$projeto_id);
		$sql->adOrdem('pr.projeto_nome');
		
		$achados=$sql->Lista();
		$vetor_icone=array();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditar($linha['projeto_acesso'], $linha['projeto_id'])) {
				$lista[$linha['projeto_id']]=$linha['projeto_nome'];
				$vetor_icone[$linha['projeto_id']]=($linha['projeto_portfolio'] ? imagem('icones/portfolio_p.gif') : imagem('icones/vazio16.gif'));
				} 
			}
		else {
			foreach($achados as $linha) if (permiteAcessar($linha['projeto_acesso'], $linha['projeto_id'])) {
				$lista[$linha['projeto_id']]=$linha['projeto_nome']; 
				$vetor_icone[$linha['projeto_id']]=($linha['projeto_portfolio'] ? imagem('icones/portfolio_p.gif') : imagem('icones/vazio16.gif'));
				}
			}
		break;
	
	case 'tarefas':
		$tarefa_projeto = getParam($_REQUEST, 'tarefa_projeto', 0);
		if ($tarefa_projeto) $projeto_id=$tarefa_projeto;
		$titulo = ucfirst($config['tarefa']);
		$sql->adCampo('tarefa_id, tarefa_nome, tarefa_acesso');
		$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC, tarefa_nome ASC');
		$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
		$sql->adOnde('tarefa_superior = tarefa_id OR tarefa_superior IS NULL');
		$lista_tarefas = $sql->Lista();
		$sql->limpar();
		$saida='';
		$saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;"><a href="javascript:setFechar(null, \'\');">Nenhuma</a></td></tr><tr><td>&nbsp;</td></tr>';
		foreach($lista_tarefas as $tarefa){
			if ($edicao) {
				if (permiteEditar($tarefa['tarefa_acesso'], $projeto_id, $tarefa['tarefa_id'])) $saida.='<tr><td style="margin-bottom:0cm; margin-top:0cm;"><a href="javascript:setFechar('.$tarefa['tarefa_id'].', \''.$tarefa['tarefa_nome'].'\');">'.$tarefa['tarefa_nome'].'</a></td></tr>';
				else $saida.='<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$tarefa['tarefa_nome'].'</td></tr>';
				}
			else{
				if (permiteAcessar($tarefa['tarefa_acesso'], $projeto_id, $tarefa['tarefa_id'])) $saida.='<tr><td style="margin-bottom:0cm; margin-top:0cm;"><a href="javascript:setFechar('.$tarefa['tarefa_id'].', \''.$tarefa['tarefa_nome'].'\');">'.$tarefa['tarefa_nome'].'</a></td></tr>';
				else $saida.='<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$tarefa['tarefa_nome'].'</td></tr>';
				}
			tarefa_subordinada($projeto_id, $tarefa['tarefa_id'], $saida, '');
			}
	
		break;
	
	case 'usuarios':
		$titulo = ucfirst($config['usuario']);
		$sql->adCampo('usuario_id,'.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$sql->adTabela('contatos', 'b');
		$sql->adOnde('usuario_contato = contato_id');
		
		
		if (trim($textobusca)) $sql->adOnde('contato_nome LIKE \'%'.$textobusca.'%\' OR contato_nomeguerra LIKE \'%'.$textobusca.'%\'');
		
		
		if ($dept_id) $sql->adOnde('contato_dept='.(int)$dept_id);
		elseif ($cia_id && !$lista_cias) $sql->adOnde('contato_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('contato_cia IN ('.$lista_cias.')');
		$nao_ha='Não foi encontrado nenh'.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'];
		$nenhum='Nenh'.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'];
		$lista = unirVetores(array('' => $nenhum), $sql->ListaChave());
		$sql->limpar();
		break;
	
	case 'contatos':
		$titulo = 'Contato';
		$sql->adCampo('contato_id,'.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
		if ($dept_id) $sql->adOnde('contato_dept='.(int)$dept_id);
		elseif ($cia_id && !$lista_cias) $sql->adOnde('contato_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('contato_cia IN ('.$lista_cias.')');
		
		if (trim($textobusca)) $sql->adOnde('contato_nome LIKE \'%'.$textobusca.'%\' OR contato_nomeguerra LIKE \'%'.$textobusca.'%\'');
		
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$nao_ha='Não foi encontrado nenhum contato';
		$nenhum='Nenhum contato';
		$lista = unirVetores(array('' => $nenhum), $sql->ListaChave());
		$sql->limpar();
		break;
		
	default:
		$ok = false;
		break;
	}
	
	
function tarefa_subordinada($projeto_id, $tarefa_id, &$saida, $espaco=''){
	global $q, $edicao, $Aplic;
	$q->adTabela('tarefas');
	$q->adCampo('tarefa_id, tarefa_nome, tarefa_acesso');
	$q->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC, tarefa_nome ASC');
	$q->adOnde('tarefa_superior = '.$tarefa_id);
	$q->adOnde('tarefa_id != '.$tarefa_id);
	$lista_tarefas = $q->Lista();
	$q->limpar();
	foreach($lista_tarefas as $tarefa){
		if ($edicao) {
			if (permiteEditar($tarefa['tarefa_acesso'], $projeto_id, $tarefa['tarefa_id'])) $saida.='<tr><td style="margin-bottom:0cm; margin-top:0cm;"><a href="javascript:setFechar('.$tarefa['tarefa_id'].', \''.$tarefa['tarefa_nome'].'\');">'.$espaco.imagem('icones/subnivel.gif').$tarefa['tarefa_nome'].'</a></td></tr>';
			else $saida.='<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$espaco.imagem('icones/subnivel.gif').$tarefa['tarefa_nome'].'</td></tr>';
			}
		else{
			if (permiteAcessar($tarefa['tarefa_acesso'], $projeto_id, $tarefa['tarefa_id'])) $saida.='<tr><td style="margin-bottom:0cm; margin-top:0cm;"><a href="javascript:setFechar('.$tarefa['tarefa_id'].', \''.$tarefa['tarefa_nome'].'\');">'.$espaco.imagem('icones/subnivel.gif').$tarefa['tarefa_nome'].'</a></td></tr>';
			else $saida.='<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$espaco.imagem('icones/subnivel.gif').$tarefa['tarefa_nome'].'</td></tr>';
			}
		tarefa_subordinada($projeto_id, $tarefa['tarefa_id'], $saida, '&nbsp;&nbsp;'.$espaco);
		}
	}	

	
if (!$ok) {
	echo '<tr><td colspan=20>Parâmetros incorretos foram passados'."\n";
	} 
else {
	?>
	<script language="javascript">
	
	function setFechar(chave, valor){
		if(parent && parent.gpwebApp){
			if (chave) parent.gpwebApp._popupCallback(chave, valor); 
			else parent.gpwebApp._popupCallback(null, "");
			} 
		else {
			if (chave!=0) <?php echo 'window.opener.'.$chamarVolta.'(chave, valor);'?> 
			else <?php echo 'window.opener.'.$chamarVolta.'(null, "");'?>  
			window.close();
			}
		}
  function cancelarSelecao(){
  	if(parent && parent.gpwebApp && parent.gpwebApp._popupWin) parent.gpwebApp._popupWin.close(); 
  	else window.close();
  	}
  	
  </script>
	
	<?php
	
	
	echo '<tr><td colspan=20 align=center ><b>Selecionar '.$titulo.'</b></td></tr>';

	if ($tabela!='tarefas'){
		if (count($lista) > 1) foreach ($lista as $chave => $val) {
			echo (isset($extra[$chave]) ? $extra[$chave] : '').'<tr><td style="margin-bottom:0cm; margin-top:0cm;"><a href="javascript:setFechar('.($chave ? $chave : '\'\'').', \''.($chave > 0 ? $val : '').'\');">'.(isset($vetor_icone[$chave]) ? $vetor_icone[$chave] : '').$val.'</a></td></tr>';
			}
		else 	echo '<tr><td><a href="javascript:setFechar(null, \'\');">'.$nenhum.'</a></td></tr>';
		}
	else echo $saida;

	echo '<tr><td>'.botao('cancelar', '', '','','javascript:cancelarSelecao()').'</td></tr>';
	} 
	
echo '</table>';	
echo '</form>';
echo estiloFundoCaixa();	
	
?>
<script language="javascript">

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 
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
		

function enviar(){
	document.env.submit();
	}		
		
		
<?php if ($tabela=='pratica_indicador') { ?>

	function mostrar(){
		limpar_tudo();
		esconder_tipo();
		if (document.getElementById('tipo').value) document.getElementById(document.getElementById('tipo').value).style.display='';
		if (document.getElementById('tipo').value=='projeto') document.getElementById('tarefa').style.display='';
		}	
		
	function esconder_tipo(){
		document.getElementById('projeto').style.display='none';
		document.getElementById('tarefa').style.display='none';
		document.getElementById('pratica').style.display='none';
		document.getElementById('acao').style.display='none';
		document.getElementById('objetivo').style.display='none';
		document.getElementById('estrategia').style.display='none';
		document.getElementById('fator').style.display='none';
		document.getElementById('meta').style.display='none';
		document.getElementById('tema').style.display='none';
		}	
			
	function limpar_tudo(){
		if (document.getElementById('tipo').value!='projeto'){ 
			document.env.projeto_nome.value = '';
			document.env.pratica_indicador_projeto.value = null;
			}
		document.env.pratica_indicador_pratica.value = null;
		document.env.pratica_nome.value = '';
		document.env.pratica_indicador_tarefa.value = null;
		document.env.tarefa_nome.value = '';
		document.env.pratica_indicador_acao.value = null;
		document.env.acao_nome.value = '';
		document.env.pratica_indicador_objetivo_estrategico.value = null;
		document.env.objetivo_nome.value = '';
		document.env.pratica_indicador_estrategia.value = null;
		document.env.estrategia_nome.value = '';	
		document.env.pratica_indicador_fator.value = null;
		document.env.fator_nome.value = '';
		document.env.pratica_indicador_meta.value = null;
		document.env.meta_nome.value = '';
		document.env.pratica_indicador_tema.value = null;
		document.env.tema_nome.value = '';
		}	
		
	function popIndicador() {
		/*
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&valor='+document.getElementById('pratica_indicador_superior').value+'&cia_id='+document.getElementById('cia_id').value, window.setIndicador, window);
		else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&valor='+document.getElementById('pratica_indicador_superior').value+'&cia_id='+document.getElementById('cia_id').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		*/
		window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&valor='+document.getElementById('pratica_indicador_superior').value+'&cia_id='+document.getElementById('cia_id').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');

		
		}
		
	function setIndicador(chave, valor){
		document.env.pratica_indicador_superior.value = chave;
		document.env.indicador_nome.value = valor;
		document.env.submit();
		}	
		
	function popTema() {
		/*
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['tema']) ?>", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
		else 
		*/
		window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}		
	function setTema(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_tema.value = chave;
		document.env.tema_nome.value = valor;
		document.env.submit();
		}	
	
	
	function popPratica() {
		/*
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['pratica']) ?>", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica, window);
		else 
		*/
		window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, 'Prática','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
		}
	
	function setPratica(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_pratica.value = chave;
		document.env.pratica_nome.value = valor;
		document.env.submit();
		}
	
	function popEstrategia() {
		/*
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
		else 
		*/
		window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, 'Iniciativas','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	
	function setEstrategia(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_estrategia.value = chave;
		document.env.estrategia_nome.value = valor;
		document.env.submit();
		}
		
	function popObjetivo() {
		/*
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['objetivo']) ?>", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
		else 
		*/
		
		window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('cia_id').value, 'Objetivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');

		
		
		}		
	
	function setObjetivo(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_objetivo_estrategico.value = chave;
		document.env.objetivo_nome.value = valor;
		document.env.submit();
		}
		
	function popProjeto() {
		/*
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
		else 
		*/
		window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=<?php echo $aceita_portfolio ?>&tabela=projetos&cia_id='+document.getElementById('cia_id').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setProjeto(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_projeto.value = chave;
		document.env.projeto_nome.value = valor;
		document.env.submit();
		}
	
	function popTarefa() {
		var f = document.env;
		if (f.pratica_indicador_projeto.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.pratica_indicador_projeto.value, 'tarefa','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
		/*
		else if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['tarefa']) ?>", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.pratica_indicador_projeto.value, window.setTarefa, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.pratica_indicador_projeto.value, 'tarefa','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
		*/
		}
		
	function setTarefa( chave, valor ) {
		limpar_tudo();
		document.env.pratica_indicador_tarefa.value = chave;
		document.env.tarefa_nome.value = valor;
		document.env.submit();
		}
		
	function popAcao() {
		var f = document.env;
		/*
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['acao']) ?>", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
		else 
		*/
		window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, 'acao','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
		}
	
	function setAcao(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_acao.value = chave;
		document.env.acao_nome.value = valor;
		document.env.submit();
		}
		
	function popFator() {
		/*
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['fator']) ?>", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('cia_id').value, window.setFator, window);
		else 
		*/
		window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('cia_id').value, 'Fator','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setFator(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_fator.value = chave;
		document.env.fator_nome.value = valor;
		document.env.submit();
		}
			
	function popMeta() {
		/*
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['meta']) ?>", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
		else 
		*/
		window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}		
	
	function setMeta(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_meta.value = chave;
		document.env.meta_nome.value = valor;
		document.env.submit();
		}	

<?php } ?>	
	
<?php if ($tabela=='projetos') { ?>

	function mudar_cidades(){
		xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:250px;"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>)); 	
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
		xajax_mudar_ajax(document.getElementById('projeto_setor').value, 'Segmento', 'projeto_segmento','combo_segmento', 'style="width:250px;" class="texto" size=1 onchange="mudar_intervencao();"'); 	
		}
	
	function mudar_intervencao(){
		<?php
		if($Aplic->profissional) echo '$jq.fn.multiSelect.clear("#projeto_tipo_intervencao");';
		else echo 'document.getElementById("projeto_tipo_intervencao").length=0;';		
		?>
		xajax_mudar_ajax(document.getElementById('projeto_segmento').value, 'Intervencao', 'projeto_intervencao','combo_intervencao', 'style="width:250px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"'); 	
		}
	
	function mudar_tipo_intervencao(){
		xajax_mudar_ajax(document.getElementById('projeto_intervencao').value, 'TipoIntervencao', 'projeto_tipo_intervencao','combo_tipo_intervencao', 'style="width:250px;" class="texto" size=1'); 	
		}	
		
	var usuarios_gerente = '<?php echo $responsavel?>';	
	var usuarios_supervisor = '<?php echo $supervisor?>';	
	var usuarios_autoridade = '<?php echo $autoridade?>';		
		
		
	<?php if ($Aplic->profissional){ ?>
	
	
	function popResponsavel(campo) {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, window.setResponsavel, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
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
	
	<?php } else { ?>
	function popResponsavel(campo) {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, window.setResponsavel, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}
		
	function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('responsavel').value=usuario_id;		
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}		
	
	function popSupervisor(campo) {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['supervisor']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('supervisor').value, window.setSupervisor, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('supervisor').value, 'Supervisor','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}
	
	
	function setSupervisor(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('supervisor').value=usuario_id;		
		document.getElementById('nome_supervisor').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}	
		
	
	function popAutoridade(campo) {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['autoridade']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('autoridade').value, window.setAutoridade, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('autoridade').value, 'Autoridade','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}
	
	function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('autoridade').value=usuario_id;		
		document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}	
	
	<?php } ?>	
	
	

<?php } ?>


		
			
</script>