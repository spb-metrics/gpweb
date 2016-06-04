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

$Aplic->carregarCKEditorJS();

$recurso_id = intval(getParam($_REQUEST, 'recurso_id', null));
$obj = new CRecurso;
$obj->load($recurso_id);

if (!$podeAdicionar && !$recurso_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$podeEditar && $recurso_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
if ($recurso_id &&!permiteEditarRecurso($obj->recurso_nivel_acesso, $recurso_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

for($i=0; $i<=100; $i++) $percentual[$i]=$i;
$recurso_acesso = getSisValor('NivelAcesso','','','sisvalor_id');


$nd=array(0 => '');
$nd+= getSisValorND();
$unidade=array(0 => '');
$unidade+=getSisValor('TipoUnidade');

$botoesTitulo = new CBlocoTitulo(($recurso_id ? 'Editar Recurso' : 'Adicionar Recurso'), 'recursos.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
$listaTipo=getSisValor('TipoRecurso');

$sql = new BDConsulta;

$usuarios_selecionados = array();
$depts_selecionados = array();
$cias_selecionadas = array();

if ($recurso_id) {

	$sql->adTabela('recurso_depts', 'rd');
	$sql->adTabela('depts', 'deps');
	$sql->adCampo('rd.departamento_id');
	$sql->adOnde('recurso_id = '.(int)$recurso_id);
	$sql->adOnde('rd.departamento_id = deps.dept_id');
	$depts_selecionados = $sql->carregarcoluna();
	$sql->limpar();

	$sql->adTabela('recurso_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('recurso_id = '.(int)$recurso_id);
	$usuarios_selecionados=$sql->carregarcoluna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('recurso_cia');
		$sql->adCampo('recurso_cia_cia');
		$sql->adOnde('recurso_cia_recurso = '.(int)$recurso_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="recursos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_recurso_aed" />';
echo '<input type="hidden" name="recurso_id" value="'.$recurso_id.'" />';
echo '<input name="recurso_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="recurso_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="recurso_cias"  id="recurso_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';


echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr><td align="right">'.dica('Nome do Recurso', 'Preencha neste campo um nome para identificação deste recurso.').'Nome:'.dicaF().'</td><td align="left"><input type="text" class="texto" size="30" maxlength="255" name="recurso_nome" value="'.(isset($obj->recurso_nome) ? $obj->recurso_nome : '').'" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' que é detentora do recurso.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td align="left"><div id="combo_cia">'.selecionar_om((isset($obj->recurso_cia) && $obj->recurso_cia ? $obj->recurso_cia :$Aplic->usuario_cia), 'recurso_cia', 'class=texto size=1 style="width:500px;" onchange="javascript:mudar_om();"').'</div>'.'</td></tr>';
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
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:486px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}

if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por este recurso.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="recurso_dept" id="recurso_dept" value="'.$obj->recurso_dept.'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($obj->recurso_dept).'" style="width:484px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:486px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';



echo '<tr><td align="right">'.dica('Responsável', 'Todo recurso deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="recurso_responsavel" name="recurso_responsavel" value="'.($obj->recurso_responsavel ? $obj->recurso_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om(($obj->recurso_responsavel ? $obj->recurso_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:484px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:486px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';


echo '<tr><td align="right">'.dica('Código do Recurso', 'Preencha neste campo um código de identificação deste recurso.').'Código:'.dicaF().'</td><td align="left"><input type="text" class="texto" size="15" maxlength="64" name="recurso_chave" value="'.(isset($obj->recurso_chave) ? $obj->recurso_chave : '').'" /></td></tr>';


echo '<tr><td align="right">'.dica('Tipo', 'Selecione qual o tipo de recurso.').'Tipo:'.dicaF().'</td><td align="left">'.selecionaVetor($listaTipo, 'recurso_tipo', 'class="texto" onchange="mudar_tipo();"', (isset($obj->recurso_tipo) ? $obj->recurso_tipo : '')).'</td></tr>';

echo '<tr id="linha_alocacao"><td align="right" width=200>'.dica('Máxima Percentagem de Alocação', 'Selecione qual a máxima capacidade de alocação, por tarefa.').'Máx. Alocação:'.dicaF().'</td><td>'.selecionaVetor($percentual, 'recurso_max_alocacao', 'size="1" class="texto"', ((isset($obj->recurso_max_alocacao) && $obj->recurso_max_alocacao) ? (int)$obj->recurso_max_alocacao :'100')).'%</td></tr>';
echo '<tr id="linha_unidade"><td align="right" width=200>'.dica('Unidade de Medida', 'Escolha a unidade de medida deste recurso.').'Unidade de medida:'.dicaF().'</td><td>'.selecionaVetor($unidade, 'recurso_unidade', 'class=texto size=1', $obj->recurso_unidade).'</td></tr>';
echo '<tr id="linha_quantidade"><td align="right" width=200>'.dica('Montante', 'Insira o montante total deste recurso.').'Montante ('.$config['simbolo_moeda'].'):'.dicaF().'</td><td><span id="cifrao" style="display:none"></span><input type="text" onkeypress="return entradaNumerica(event, this, true, true);" class="texto" onchange="javascript:valor();" onclick="javascript:valor();" name="recurso_quantidade" id="recurso_quantidade" value="'.(isset($obj->recurso_quantidade) && $obj->recurso_quantidade ? number_format($obj->recurso_quantidade, 2, ',', '.') :'').'" size="40" /></td></tr>';
echo '<tr id="linha_valor_hora"><td align="right" width=200>'.dica('Valor da Hora', 'Insira o valor da hora de alocação deste recurso.').'Valor da hora ('.$config['simbolo_moeda'].'):'.dicaF().'</td><td><input type="text" onkeypress="return entradaNumerica(event, this, true, true);" class="texto" name="recurso_hora_custo" id="recurso_hora_custo" value="'.(isset($obj->recurso_hora_custo) && $obj->recurso_hora_custo ? number_format($obj->recurso_hora_custo, 2, ',', '.') :'').'" size="40" /></td></tr>';
echo '<tr id="linha_valor"><td align="right" width=200>'.dica('Valor Unitário', 'Insira o valor unitário deste recurso.').'Valor unitário ('.$config['simbolo_moeda'].'):'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="recurso_custo" id="recurso_custo" value="'.(isset($obj->recurso_custo) && $obj->recurso_custo ? number_format($obj->recurso_custo, 2, ',', '.') :'').'" maxlength="255" size="10" /></td></tr>';



echo '<tr id="linha_natureza"><td colspan=20><table cellspacing=0 cellpadding=0>';
$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
echo '<tr><td align="right" nowrap="nowrap" width=200>'.dica('Categoria Econômica', 'Caso seja monetário, seleciona a categoria econômica deste recurso.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'recurso_categoria_economica', 'style="width:500px;" class=texto size=1 onchange="env.recurso_nd.value=\'\'; mudar_nd();"', (isset($obj->recurso_categoria_economica) ? $obj->recurso_categoria_economica :'')).'</td></tr>';
$GrupoND=array(''=>'')+getSisValor('GrupoND');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'Caso seja monetário, seleciona o grupo de despesa deste recurso.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'recurso_grupo_despesa', 'style="width:500px;" class=texto size=1 onchange="env.recurso_nd.value=\'\'; mudar_nd();"', (isset($obj->recurso_grupo_despesa) ? $obj->recurso_grupo_despesa :'')).'</td></tr>';
$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplicação', 'Caso seja monetário, seleciona a modalidade de aplicação deste recurso.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'recurso_modalidade_aplicacao', 'class=texto size=1 style="width:500px;" onchange="env.recurso_nd.value=\'\'; mudar_nd();"', (isset($obj->recurso_modalidade_aplicacao) ? $obj->recurso_modalidade_aplicacao :'')).'</td></tr>';
$nd=vetor_nd((isset($obj->recurso_nd) ? $obj->recurso_nd : ''), null, null, 3 ,(isset($obj->recurso_categoria_economica) ?  $obj->recurso_categoria_economica : ''), (isset($obj->recurso_grupo_despesa) ?  $obj->recurso_grupo_despesa : ''), (isset($obj->recurso_modalidade_aplicacao) ?  $obj->recurso_modalidade_aplicacao : ''));
echo '<tr><td align="right" style="width:90px;">'.dica('Elemento de Despesa', 'Escolha o elemento de despesa (ED) deste recurso.').'Elemento de despesa:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'recurso_nd', 'class=texto size=1 style="width:500px;" onchange="mudar_nd();"', (isset($obj->recurso_nd) && $obj->recurso_nd ? $obj->recurso_nd :'')).'</div></td></tr>';
echo '</table></td></tr>';


echo '<tr id="linha_credito"><td colspan=20><table cellspacing=0 cellpadding=0>';
echo '<tr><td align="right" width=200>'.dica('Ano', 'Insira o ano deste recurso.').'Ano:'.dicaF().'</td><td><input type="text" class="texto" name="recurso_ano" id="recurso_ano" value="'.(isset($obj->recurso_ano) && $obj->recurso_ano ? $obj->recurso_ano : date('Y')).'" maxlength="4" style="width:40px;" /></td></tr>';
$EsferaOrcamentaria=array(''=>'')+getSisValor('EsferaOrcamentaria');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Esfera Orcamentária', 'Caso seja monetário, seleciona a esfera orcamentária deste recurso.').'Esfera orcamentária:'.dicaF().'</td><td>'.selecionaVetor($EsferaOrcamentaria, 'recurso_esf', 'style="width:500px;" class=texto size=1', (isset($obj->recurso_esf) ? $obj->recurso_esf :'')).'</td></tr>';
$OrigemRecurso=array(''=>'')+getSisValor('OrigemRecurso');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Origem do Recurso', 'Caso seja monetário, seleciona a origem do recurso deste recurso.').'Origem do recurso:'.dicaF().'</td><td>'.selecionaVetor($OrigemRecurso, 'recurso_origem', 'style="width:500px;" class=texto size=1', (isset($obj->recurso_origem) ? $obj->recurso_origem :'')).'</td></tr>';
$CreditoAdicional=array(''=>'')+getSisValor('CreditoAdicional');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Crédito Adicional', 'Caso seja monetário, seleciona o crédito adicional deste recurso, se for o caso.').'Crédito adicional:'.dicaF().'</td><td>'.selecionaVetor($CreditoAdicional, 'recurso_credito_adicional', 'style="width:500px;" class=texto size=1', (isset($obj->recurso_credito_adicional) ? $obj->recurso_credito_adicional :'')).'</td></tr>';
$MovimentacaoOrcamentaria=array(''=>'')+getSisValor('MovimentacaoOrcamentaria');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Movimentação Orcamentária', 'Caso seja monetário, seleciona a movimentação orcamentária deste recurso, se for o caso.').'Movimentação:'.dicaF().'</td><td>'.selecionaVetor($MovimentacaoOrcamentaria, 'recurso_movimentacao_orcamentaria', 'style="width:500px;" class=texto size=1', (isset($obj->recurso_movimentacao_orcamentaria) ? $obj->recurso_movimentacao_orcamentaria :'')).'</td></tr>';
echo '<tr id="linha_quantidade"><td align="right">'.dica('Montante Liberado', 'Insira o montante liberado deste recurso.').'Montante liberado ('.$config['simbolo_moeda'].'):'.dicaF().'</td><td><input type="text" onkeypress="return entradaNumerica(event, this, true, true);" class="texto" name="recurso_liberado" id="recurso_liberado" value="'.(isset($obj->recurso_liberado) && $obj->recurso_liberado ? number_format($obj->recurso_liberado, 2, ',', '.') :'').'" size="40" /></td></tr>';
$ResultadoPrimario=array(''=>'')+getSisValor('ResultadoPrimario');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Resultado Primário', 'Caso seja monetário, seleciona o resultado primário deste recurso.').'Resultado primário:'.dicaF().'</td><td>'.selecionaVetor($ResultadoPrimario, 'recurso_resultado_primario', 'class=texto size=1 style="width:500px;"', (isset($obj->recurso_resultado_primario) ? $obj->recurso_resultado_primario :'')).'</td></tr>';
$IdentificadorUso=array(''=>'')+getSisValor('IdentificadorUso');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Identificador de Uso', 'Caso seja monetário, seleciona o identificador de uso deste recurso.').'Identificador de uso:'.dicaF().'</td><td>'.selecionaVetor($IdentificadorUso, 'recurso_identificador_uso', 'class=texto size=1 style="width:500px;"', (isset($obj->recurso_identificador_uso) ? $obj->recurso_identificador_uso :'')).'</td></tr>';
echo '<tr><td align="right">'.dica('Quem Liberou','Quem foi responsável pela liberaçãodo crédito.').'Quem liberou:'.dicaF().'</td><td><input type="hidden" id="recurso_contato" name="recurso_contato" value="'.(isset($obj->recurso_contato) ? $obj->recurso_contato : 0).'" /><input type="text" id="nome_contato" name="nome_contato" value="'.nome_contato((isset($obj->recurso_contato) ? $obj->recurso_contato : 0),'','',true).'" style="width:484px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popContato();">'.imagem('icones/usuarios.gif','Selecionar Contato','Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar um contato.').'</a></td></tr>';
echo '<tr><td align="right" >'.dica('Evento', 'Insira o código do evento deste recurso.').'Evento:'.dicaF().'</td><td><input type="text" class="texto" name="recurso_ev" id="recurso_ev" value="'.(isset($obj->recurso_ev) && $obj->recurso_ev ? $obj->recurso_ev :'').'" maxlength="20" size="6" /></td></tr>';
$Fonte=array(''=>'')+getSisValor('Fonte');
echo '<tr><td align="right" >'.dica('Fonte do Recurso', 'Insira o código do evento deste recurso.').'Fonte:'.dicaF().'</td><td>'.selecionaVetor($Fonte, 'recurso_fonte', 'style="width:500px;" class=texto size=1"', (isset($obj->recurso_fonte) ? $obj->recurso_fonte :'')).'</td></tr>';
echo '<tr><td align="right" >'.dica('SB', 'Insira o código SB deste recurso.').'SB:'.dicaF().'</td><td><input type="text" class="texto" name="recurso_sb" id="recurso_sb" value="'.(isset($obj->recurso_sb) && $obj->recurso_sb ? $obj->recurso_sb :'').'" maxlength="20" size="6" /></td></tr>';
echo '<tr><td align="right" >'.dica('Unidade Gestora do Recurso', 'Insira o código da unidade gestora do recurso.').'UGR:'.dicaF().'</td><td><input type="text" class="texto" name="recurso_ugr" id="recurso_ugr" value="'.(isset($obj->recurso_ugr) && $obj->recurso_ugr ? $obj->recurso_ugr :'').'" maxlength="20" size="6" /></td></tr>';
echo '<tr><td align="right" >'.dica('Plano Interno', 'Insira o plano interno deste recurso.').'PI:'.dicaF().'</td><td><input type="text" class="texto" name="recurso_pi" id="recurso_pi" value="'.(isset($obj->recurso_pi) && $obj->recurso_pi ? $obj->recurso_pi :'').'" maxlength="20" size="11" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Plano de Trabalho Resumido', 'Insira o plano de trabalho resumido deste recurso.').'PTRES:'.dicaF().'</td><td><input type="text" class="texto" size="10" maxlength="30" name="recurso_ptres" value="'.(isset($obj->recurso_ptres) ? $obj->recurso_ptres :'').'"></td></tr>';
echo '</table></td></tr>';




echo '<tr id="linha_total"><td align="right">'.dica('Total', 'O valor total do recurso.').'Total:'.dicaF().'</td><td><div id="total"></div></td></tr>';
echo '<tr><td align="right">'.dica('Nível de Acesso', 'Os recursos podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o recurso.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os usuários do recurso podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os usuários do recurso podem ver e editar o mesmo</li><li><b>Privado</b> - Somente o responsável e os usuários do recurso podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td colspan="2">'.selecionaVetor($recurso_acesso, 'recurso_nivel_acesso', 'class="texto"', ($recurso_id ? $obj->recurso_nivel_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right">'.dica('Notas', 'Preencha neste espaço informações a respeito deste recurso que sejam de interesse geral.').'Notas:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="recurso_nota" style="width:500px;" rows="3">'.(isset($obj->recurso_nota) ? $obj->recurso_nota : '').'</textarea></td></tr>';





if ($Aplic->ModuloAtivo('fpti')){
	include_once (BASE_DIR.'/modulos/fpti/fpti.class.php');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Centro de Custo', 'O centro de custo ao qual este recurso está vinculado.').'Centro de Custo:'.dicaF().'</td><td><input type="hidden" id="recurso_centro_custo" name="recurso_centro_custo" value="'.$obj->recurso_centro_custo.'" /><input type="text" id="nome_centro_custo" name="nome_centro_custo" value="'.nome_centro_custo($obj->recurso_centro_custo).'" style="width:484px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popCentro();">'.imagem('../../../modulos/fpti/imagens/centro_custo_p.gif','Selecionar Centro de Custo','Clique neste ícone '.imagem('../../../modulos/fpti/imagens/centro_custo_p.gif').' para selecionar um centro de custo.').'</a></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Conta Orçamentária', 'A conta orçamentária a qual este recurso está vinculado.').'Conta Orçamentária:'.dicaF().'</td><td><input type="hidden" id="recurso_conta_orcamentaria" name="recurso_conta_orcamentaria" value="'.$obj->recurso_conta_orcamentaria.'" /><input type="text" id="nome_conta_orcamentaria" name="nome_conta_orcamentaria" value="'.nome_conta_orcamentaria($obj->recurso_conta_orcamentaria).'" style="width:484px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popContaOrcamentaria();">'.imagem('../../../modulos/fpti/imagens/conta_orcamentaria_p.gif','Selecionar Conta Orçamentária','Clique neste ícone '.imagem('../../../modulos/fpti/imagens/conta_orcamentaria_p.gif').' para selecionar uma conta orçamentária.').'</a></td></tr>';
	}


if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_recurso = '.(int)$recurso_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'recurso_principal_indicador', 'class="texto" style="width:284px;"', $obj->recurso_principal_indicador).'</td></tr>';
	}


echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o recurso ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="recurso_ativo" '.($obj->recurso_ativo || !$recurso_id ? 'checked="checked"' : '').' /></td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('recursos', $recurso_id, 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td style="width:143px;">'.botao('salvar', 'Salvar', 'Salvar as informações do recurso.','','enviarDados(document.env)').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr>';
echo '</table></form>';
echo estiloFundoCaixa();
?>
<script language="javascript">

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('recurso_cia').value+'&cias_id_selecionadas='+document.getElementById('recurso_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.recurso_cias.value = organizacao_id_string;
	document.getElementById('recurso_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('recurso_cias').value);
	__buildTooltip();
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('recurso_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('recurso_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.recurso_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('recurso_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('recurso_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.recurso_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}

function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('recurso_dept').value+'&cia_id='+document.getElementById('recurso_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('recurso_dept').value+'&cia_id='+document.getElementById('recurso_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('recurso_cia').value=cia_id;
	document.getElementById('recurso_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function popContato(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contato"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setContato&contato=1&contato_id='+document.getElementById('recurso_contato').value, window.setContato, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setContato&contato=1&contato_id='+document.getElementById('recurso_contato').value, '<?php echo ucfirst($config["contato"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setContato(contato_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('recurso_contato').value=contato_id;
	document.getElementById('nome_contato').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_nd(){
	xajax_mudar_nd_ajax(env.recurso_nd.value, 'recurso_nd', 'combo_nd','class=texto size=1 style="width:500px;" onchange="mudar_nd();"', 3, env.recurso_categoria_economica.value, env.recurso_grupo_despesa.value, env.recurso_modalidade_aplicacao.value);
	}

function enviarDados(f) {
	if (f.recurso_nome.value.length == 0) {
		alert('Você precisa digitar um nome para este recurso');
		return false;
		}
	document.getElementById('recurso_hora_custo').value=converteMoedaFloat(document.getElementById('recurso_hora_custo').value);
	document.getElementById('recurso_custo').value=converteMoedaFloat(document.getElementById('recurso_custo').value);
	document.getElementById('recurso_liberado').value=converteMoedaFloat(document.getElementById('recurso_liberado').value);
	document.getElementById('recurso_quantidade').value=converteMoedaFloat(document.getElementById('recurso_quantidade').value);
	f.submit();
	return true;
	}


 function converteMoedaFloat(valor){
   if(valor === "") valor =  0;
   else{
     	valor = valor.replace(".","");
			valor = valor.replace(".","");
			valor = valor.replace(".","");
			valor = valor.replace(".","");
			valor = valor.replace(".","");
			valor = valor.replace(".","");
			valor = valor.replace(".","");
     	valor = valor.replace(",",".");

     	valor = parseFloat(valor);
  		}
    return valor;
 		}

 function converteFloatMoeda(valor){
      var inteiro = null, decimal = null, c = null, j = null;
      var aux = new Array();
      valor = ""+valor;
      c = valor.indexOf(".",0);
      //encontrou o ponto na string
      if(c > 0){
         //separa as partes em inteiro e decimal
         inteiro = valor.substring(0,c);
         decimal = valor.substring(c+1,valor.length);
      }else{
         inteiro = valor;
      }

      //pega a parte inteiro de 3 em 3 partes
      for (j = inteiro.length, c = 0; j > 0; j-=3, c++){
         aux[c]=inteiro.substring(j-3,j);
      }

      //percorre a string acrescentando os pontos
      inteiro = "";
      for(c = aux.length-1; c >= 0; c--){
         inteiro += aux[c]+'.';
      }
      //retirando o ultimo ponto e finalizando a parte inteiro

      inteiro = inteiro.substring(0,inteiro.length-1);

      decimal = parseInt(decimal);
      if(isNaN(decimal)){
         decimal = "00";
      }else{
         decimal = ""+decimal;
         if(decimal.length === 1){
            decimal = decimal+"0";
         }
      }
      valor = inteiro+","+decimal;
      return valor;

   }



function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}

function popResponsavel(campo) {

		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('recurso_cia').value+'&usuario_id='+document.getElementById('recurso_responsavel').value, 'Remetente','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('recurso_responsavel').value=usuario_id;
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('recurso_cia').value,'recurso_cia','combo_cia', 'class="texto" size=1 style="width:500px;" onchange="javascript:mudar_om();"');
	}


var usuarios_id_selecionados = '<?php echo implode(',', $usuarios_selecionados)?>';
var depts_id_selecionados = '<?php echo implode(',', $depts_selecionados)?>';



function mudar_tipo(){
	var tipo=document.getElementById('recurso_tipo').value;
	if (tipo==1 || tipo==2) {
		document.getElementById('linha_alocacao').style.display='';
		document.getElementById('linha_unidade').style.display='';
		document.getElementById('linha_valor').style.display='none';
		document.getElementById('linha_natureza').style.display='none';
		document.getElementById('linha_credito').style.display='none';
		document.getElementById('linha_total').style.display='none';
		document.getElementById('recurso_custo').value='0';
		document.getElementById('recurso_nd').value='';
		document.getElementById('cifrao').style.display='none';
		document.getElementById('linha_valor_hora').style.display='';
		}

	if (tipo==3) {
		document.getElementById('linha_alocacao').style.display='';
		document.getElementById('linha_unidade').style.display='none';
		document.getElementById('linha_valor').style.display='none';
		document.getElementById('linha_natureza').style.display='none';
		document.getElementById('linha_credito').style.display='none';
		document.getElementById('linha_total').style.display='none';
		document.getElementById('recurso_unidade').value='0';
		document.getElementById('recurso_custo').value='0';
		document.getElementById('recurso_nd').value='';
		document.getElementById('cifrao').style.display='none';
		document.getElementById('linha_valor_hora').style.display='';
		}
	if (tipo==4) {
		document.getElementById('linha_alocacao').style.display='none';
		document.getElementById('linha_unidade').style.display='';
		document.getElementById('linha_valor').style.display='';
		document.getElementById('linha_natureza').style.display='';
		document.getElementById('linha_credito').style.display='none';
		document.getElementById('linha_total').style.display='';
		document.getElementById('cifrao').style.display='none';
		document.getElementById('cifrao').style.display='none';
		document.getElementById('recurso_max_alocacao').value='100';
		document.getElementById('linha_valor_hora').style.display='none';
		}
	if (tipo==5) {
		document.getElementById('linha_alocacao').style.display='none';
		document.getElementById('linha_unidade').style.display='none';
		document.getElementById('linha_valor').style.display='none';
		document.getElementById('linha_natureza').style.display='';
		document.getElementById('linha_credito').style.display='';
		document.getElementById('linha_total').style.display='none';
		document.getElementById('cifrao').style.display='';
		document.getElementById('recurso_custo').value='1';
		document.getElementById('recurso_unidade').value='0';
		document.getElementById('recurso_max_alocacao').value='100';
		document.getElementById('linha_valor_hora').style.display='none';
		}
	}


function valor(){
	var custo=converteMoedaFloat(document.getElementById('recurso_custo').value);
	var qnt=converteMoedaFloat(document.getElementById('recurso_quantidade').value);
	var total=custo*qnt;
	document.getElementById('total').innerHTML ='<b><?php echo $config["simbolo_moeda"]?>'+converteFloatMoeda(total)+'</b>';
	}

mudar_tipo();


<?php if ($Aplic->ModuloAtivo('fpti')){ ?>
function popCentro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Centro de Custo', 500, 500, 'm=fpti&a=selecionar&dialogo=1&chamar_volta=setCentro&tabela=fpti_centro_custo&cia_id='+document.getElementById('recurso_cia').value, window.setCentro, window);
	else window.open('./index.php?m=fpti&a=selecionar&dialogo=1&chamar_volta=setCentro&tabela=fpti_centro_custo&cia_id='+document.getElementById('recurso_cia').value, 'Centro de Custo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCentro(chave, valor){
	document.getElementById('recurso_centro_custo').value=chave;
	document.getElementById('nome_centro_custo').value=valor;
	}


function popContaOrcamentaria() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Conta', 500, 500, 'm=fpti&a=selecionar&dialogo=1&tabela=fpti_conta_orcamentaria&chamar_volta=setContaOrcamentaria&cia_id='+document.getElementById('recurso_cia').value, window.setContaOrcamentaria, window);
	else window.open('./index.php?m=fpti&a=selecionar&dialogo=1&tabela=fpti_conta_orcamentaria&chamar_volta=setContaOrcamentaria&cia_id='+document.getElementById('recurso_cia').value, 'Conta','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setContaOrcamentaria(chave, valor){
	document.getElementById('recurso_conta_orcamentaria').value=chave;
	document.getElementById('nome_conta_orcamentaria').value=valor;
	}

<?php } ?>

</script>
