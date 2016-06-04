<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $dialogo, $Aplic;

$sql = new BDConsulta;
$salvar=getParam($_REQUEST, 'salvar',0);

$editar=getParam($_REQUEST, 'editar',0);
$criar=getParam($_REQUEST, 'criar',0);
$sem_impressao=getParam($_REQUEST, 'sem_impressao',0);
$conteudo=getParam($_REQUEST, 'conteudo',0);
$pode_editar=!$dialogo;
$acesso=getSisValor('NivelAcesso','','','sisvalor_id');


$gut_linha_id=getParam($_REQUEST, 'conteudo',null);
if (!$gut_linha_id) $gut_linha_id=0;

if (isset($_REQUEST['gut_id'])) $Aplic->setEstado('gut_id', getParam($_REQUEST, 'gut_id', null));
$gut_id=$Aplic->getEstado('gut_id') !== null ? $Aplic->getEstado('gut_id') : null;


if (isset($_REQUEST['gut_textobusca'])) $Aplic->setEstado('gut_textobusca', getParam($_REQUEST, 'gut_textobusca', null));
$pesquisar_texto = ($Aplic->getEstado('gut_textobusca') ? $Aplic->getEstado('gut_textobusca') : '');

if (isset($_REQUEST['projeto_id'])) $Aplic->setEstado('projeto_id', getParam($_REQUEST, 'projeto_id', null));
if (isset($_REQUEST['pratica_id'])) $Aplic->setEstado('pratica_id', getParam($_REQUEST, 'pratica_id', null));
if (isset($_REQUEST['pratica_indicador_id'])) $Aplic->setEstado('pratica_indicador_id', getParam($_REQUEST, 'pratica_indicador_id', null));
if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

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


$projeto_id = $Aplic->getEstado('projeto_id', null);	
$pratica_id = $Aplic->getEstado('pratica_id', null);
$pratica_indicador_id = $Aplic->getEstado('pratica_indicador_id', null);


$sql->adTabela('projetos');
$sql->adCampo('projeto_id, projeto_nome');
if($cia_id) $sql->adOnde('projeto_cia='.(int)$cia_id);
$sql->adOrdem('projeto_nome');
$lista_projetos = $sql->ListaChave();
$sql->limpar();
$lista_projetos[0]='';

$sql->adTabela('tarefas');
$sql->adCampo('tarefa_id, tarefa_nome');
if($cia_id) $sql->adOnde('tarefa_projeto='.(int)$projeto_id);
$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio');
$lista_tarefas = $sql->ListaChave();
$sql->limpar();


if (getParam($_REQUEST, 'del',null) && $gut_id){
	$sql->setExcluir('gut');
	$sql->adOnde('gut_id = '.(int)$gut_id);
	$sql->exec();
	$sql->limpar();
	
	ver2('Matrix G.U.T. excluída');
	$gut_id=null;

	}


if ($salvar){
	$gut_id=getParam($_REQUEST, 'gut_id',0);	
	if(!$gut_id){
		$sql->adTabela('gut');
		$sql->adInserir('gut_nome', getParam($_REQUEST, 'gut_nome',''));
		$sql->adInserir('gut_responsavel', getParam($_REQUEST, 'gut_responsavel',null));
		$sql->adInserir('gut_acesso', getParam($_REQUEST, 'gut_acesso',0));
		$sql->adInserir('gut_cia', getParam($_REQUEST, 'gut_cia',null));
		$sql->adInserir('gut_datahora', date('Y-m-d H:i:s'));
		$sql->exec();
		$gut_id=$bd->Insert_ID('gut','gut_id');
		$sql->Limpar();
		}
	else{
		$sql->adTabela('gut');
		$sql->adAtualizar('gut_nome', getParam($_REQUEST, 'gut_nome',''));
		$sql->adAtualizar('gut_responsavel', getParam($_REQUEST, 'gut_responsavel',null));
		$sql->adAtualizar('gut_cia', getParam($_REQUEST, 'gut_cia',null));
		$sql->adAtualizar('gut_acesso', getParam($_REQUEST, 'gut_acesso',0));
		$sql->adAtualizar('gut_datahora', date('Y-m-d H:i:s'));
		$sql->adOnde('gut_id='.(int)$gut_id);
		$retorno=$sql->exec();
		$sql->Limpar();
		}	
	

	$gut_usuarios=getParam($_REQUEST, 'gut_usuarios', null);
	$gut_usuarios=explode(',', $gut_usuarios);
	$sql->setExcluir('gut_usuarios');
	$sql->adOnde('gut_id = '.(int)$gut_id);
	$sql->exec();
	$sql->limpar();
	foreach($gut_usuarios as $chave => $usuario_id){
		if($usuario_id){
			$sql->adTabela('gut_usuarios');
			$sql->adInserir('gut_id', (int)$gut_id);
			$sql->adInserir('usuario_id', (int)$usuario_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	
	$gut_depts=getParam($_REQUEST, 'gut_depts', null);
	$gut_depts=explode(',', $gut_depts);
	$sql->setExcluir('gut_depts');
	$sql->adOnde('gut_id = '.(int)$gut_id);
	$sql->exec();
	$sql->limpar();
	foreach($gut_depts as $chave => $dept_id){
		if($dept_id){
			$sql->adTabela('gut_depts');
			$sql->adInserir('gut_id', (int)$gut_id);
			$sql->adInserir('dept_id', (int)$dept_id);
			$sql->exec();
			$sql->limpar();
			}
		}
		
	$projetos_ocultos=getParam($_REQUEST, 'projetos_ocultos', array());
	$projetos_ocultos=explode(',', $projetos_ocultos);
	$sql->setExcluir('gut_projetos');
	$sql->adOnde('gut_id = '.(int)$gut_id);
	$sql->exec();
	$sql->limpar();
	foreach($projetos_ocultos as $chave => $projeto_id){
		if($projeto_id){
			$sql->adTabela('gut_projetos');
			$sql->adInserir('gut_id', (int)$gut_id);
			$sql->adInserir('projeto_id', (int)$projeto_id);
			$sql->exec();
			$sql->limpar();
			}
		}	
		
	$tarefas_ocultas=getParam($_REQUEST, 'tarefas_ocultas', array());
	$tarefas_ocultas=explode(',', $tarefas_ocultas);
	$sql->setExcluir('gut_tarefas');
	$sql->adOnde('gut_id = '.(int)$gut_id);
	$sql->exec();
	$sql->limpar();
	foreach($tarefas_ocultas as $chave => $tarefa_id){
		if($tarefa_id){
			$sql->adTabela('gut_tarefas');
			$sql->adInserir('gut_id', (int)$gut_id);
			$sql->adInserir('tarefa_id', (int)$tarefa_id);
			$sql->exec();
			$sql->limpar();
			}
		}
		
	$praticas_ocultas=getParam($_REQUEST, 'praticas_ocultas', array());
	$praticas_ocultas=explode(',', $praticas_ocultas);
	$sql->setExcluir('gut_praticas');
	$sql->adOnde('gut_id = '.(int)$gut_id);
	$sql->exec();
	$sql->limpar();
	foreach($praticas_ocultas as $chave => $pratica_id){
		if($pratica_id){
			$sql->adTabela('gut_praticas');
			$sql->adInserir('gut_id', (int)$gut_id);
			$sql->adInserir('pratica_id', (int)$pratica_id);
			$sql->exec();
			$sql->limpar();
			}
		}		
	
	$indicadores_ocultos=getParam($_REQUEST, 'indicadores_ocultos', array());
	$indicadores_ocultos=explode(',', $indicadores_ocultos);
	$sql->setExcluir('gut_indicadores');
	$sql->adOnde('gut_id = '.(int)$gut_id);
	$sql->exec();
	$sql->limpar();
	foreach($indicadores_ocultos as $chave => $pratica_indicador_id){
		if($pratica_indicador_id){
			$sql->adTabela('gut_indicadores');
			$sql->adInserir('gut_id', (int)$gut_id);
			$sql->adInserir('pratica_indicador_id', (int)$pratica_indicador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
		
	ver2('A matriz G.U.T. foi salva.');
	$editar=1;
	}


//carregar lista de GUTs
$sql->adTabela('gut');
$sql->adCampo('gut.gut_id, gut_nome, gut_acesso');
if($cia_id) $sql->adOnde('gut_cia='.(int)$cia_id);
if($projeto_id) {
	$sql->esqUnir('gut_projetos','gut_projetos','gut.gut_id=gut_projetos.gut_id');
	$sql->adOnde('gut_projetos.projeto_id='.(int)$projeto_id);
	}
if($pratica_id) {
	$sql->esqUnir('gut_praticas','gut_praticas','gut.gut_id=gut_praticas.gut_id');
	$sql->adOnde('gut_praticas.pratica_id='.(int)$pratica_id);
	}
if($pratica_indicador_id) {
	$sql->esqUnir('gut_indicadores','gut_indicadores','gut.gut_id=gut_indicadores.gut_id');
	$sql->adOnde('gut_indicadores.pratica_indicador_id='.(int)$pratica_indicador_id);
	}
if($pesquisar_texto) $sql->adOnde('gut_descricao LIKE \'%'.$pesquisar_texto.'%\' OR gut_nome LIKE \'%'.$pesquisar_texto.'%\'');
$lista_guts=$sql->Lista();
$guts[0]='';
foreach($lista_guts as $linha) if(permiteAcessarGUT($linha['gut_acesso'],$linha['gut_id']))$guts[$linha['gut_id']]=$linha['gut_nome'];
$sql->limpar();


$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
if($cia_id) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
$indicadores = $sql->ListaChave();
$indicadores[0]='';
$sql->limpar();

$sql->adTabela('praticas');
$sql->adCampo('pratica_id, pratica_nome');
if($cia_id) $sql->adOnde('pratica_cia='.(int)$cia_id);
$praticas = $sql->ListaChave();
$praticas[0]='';
$sql->limpar();



if(!$gut_id){
	//Criar
	
	$gut_projeto_id=$projeto_id;
	$gut_nome='';
	$gut_cia=$cia_id;
	$gut_responsavel=null;
	$gut_dept='';
	$gut_acesso=0;
	
	$projetos_escolhidos=array();
	$tarefas_escolhidas=array();
	$indicadores_escolhidos=array();
	$praticas_escolhidas=array();
	echo '<script>var usuarios_id_selecionados = ""; var depts_id_selecionados = "";</script>';	
	
	}
else{
	$sql->adTabela('gut');
	$sql->adOnde('gut_id='.(int)$gut_id);
	$linha=$sql->Linha();
	$sql->limpar();
	
	$pode_editar=$pode_editar && permiteEditarGUT($linha['gut_acesso'],$linha['gut_id']);
	
	$gut_nome=$linha['gut_nome'];
	$gut_responsavel=$linha['gut_responsavel'];
	$gut_cia=$linha['gut_cia'];
	$gut_acesso=$linha['gut_acesso'];

	$sql->adTabela('gut_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('gut_id='.(int)$gut_id);
	$lista_usuarios=$sql->Lista();
	$sql->limpar();
	$usuarios=array();
	foreach($lista_usuarios as $usuario) $usuarios[]=$usuario['usuario_id'];

	$sql->adTabela('gut_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('gut_id='.(int)$gut_id);
	$lista_depts=$sql->Lista();
	$sql->limpar();
	$depts=array();
	foreach($lista_depts as $dept) $depts[]=$dept['dept_id'];
	
	
	$sql->adTabela('gut_projetos');
	$sql->esqUnir('projetos','projetos','projetos.projeto_id=gut_projetos.projeto_id');
	$sql->adCampo('projetos.projeto_id, projeto_nome');
	$sql->adOnde('gut_id='.(int)$gut_id);
	$vetor_projetos=$sql->Lista();
	$sql->limpar();
	$projetos_escolhidos=array();
	foreach($vetor_projetos as $projeto) $projetos_escolhidos[$projeto['projeto_id']]=$projeto['projeto_nome'];
	
	
	$gut_projeto_id=(isset($vetor_projetos[0]['projeto_id']) ? $vetor_projetos[0]['projeto_id'] : 0);
	
	//atualizar o vetor do campo tarefas
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_id, tarefa_nome');
	if($cia_id) $sql->adOnde('tarefa_projeto='.(int)$gut_projeto_id);
	$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio');
	
	$lista_tarefas += $sql->ListaChave();
	$sql->limpar();
	
	
	$sql->adTabela('gut_tarefas');
	$sql->esqUnir('tarefas','tarefas','tarefas.tarefa_id=gut_tarefas.tarefa_id');
	$sql->adCampo('tarefas.tarefa_id, tarefa_nome');
	$sql->adOnde('gut_id='.(int)$gut_id);
	$vetor_tarefas=$sql->Lista();
	$sql->limpar();
	$tarefas_escolhidas=array();
	foreach($vetor_tarefas as $tarefa) $tarefas_escolhidas[$tarefa['tarefa_id']]=$tarefa['tarefa_nome'];
	
	$sql->adTabela('gut_praticas');
	$sql->esqUnir('praticas','praticas','praticas.pratica_id=gut_praticas.pratica_id');
	$sql->adCampo('praticas.pratica_id, pratica_nome');
	$sql->adOnde('gut_id='.(int)$gut_id);
	$vetor_praticas=$sql->Lista();
	$sql->limpar();
	$praticas_escolhidas=array();
	foreach($vetor_praticas as $pratica) $praticas_escolhidas[$pratica['pratica_id']]=$pratica['pratica_nome'];

	$sql->adTabela('gut_indicadores');
	$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador.pratica_indicador_id=gut_indicadores.pratica_indicador_id');
	$sql->adCampo('pratica_indicador.pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('gut_id='.(int)$gut_id);
	$vetor_indicadores=$sql->Lista();
	$sql->limpar();
	$indicadores_escolhidos=array();
	foreach($vetor_indicadores as $indicador) $indicadores_escolhidos[$indicador['pratica_indicador_id']]=$indicador['pratica_indicador_nome'];
	}


echo '<form name="frm_filtro" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="projeto_id" value="0" />';
echo '<input type="hidden" name="pratica_id" value="0" />';
echo '<input type="hidden" name="pratica_indicador_id" value="0" />';
echo '<input type="hidden" name="criar" value="0" />';
echo '<input type="hidden" name="editar" value="0" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';

$botoesTitulo = new CBlocoTitulo('Matriz G.U.T.', (!$dialogo ? 'gut.gif' : ''));
if (!$dialogo){
	$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap" align="right">'.dica('Seleção de Matriz', 'Utilize esta opção para selecionar qual matriz G.U.T. deseja visualizar.').'Matriz:'. dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($guts, 'gut_id', 'class="text" style="width:200px;" onchange="document.frm_filtro.submit()" class="texto"', $gut_id). '</td></tr><tr><td>'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:200px;" name="gut_textobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=gut&gut_textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table>', '', '', '');
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');

	$icone_projeto=($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso') ? '<td><a href="javascript: void(0);" onclick="popProjeto_filtro();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td>' : '');
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso')){
		$icone_pratica='<td><a href="javascript: void(0);" onclick="popPratica_filtro();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.$config['genero_pratica'].' '.$config['pratica'].'.').'</a></td>'; 
		$icone_indicador='<td><a href="javascript: void(0);" onclick="popIndicador_filtro();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um indicador.').'</a></td>';
		$icone_objetivo='<td><a href="javascript: void(0);" onclick="popObjetivo_filtro();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td>';
		$icone_estrategia='<td><a href="javascript: void(0);" onclick="popEstrategia_filtro();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td>';
		}
	else {
		$icone_pratica='';
		$icone_indicador='';
		$icone_objetivo='';
		$icone_estrategia='';
		}
	
	if($pratica_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Filtrar arquivos pel'.$config['genero_pratica'].' '.$config['pratica'].' a qual estão relacionados.').ucfirst($config['pratica']).':'.dicaF();
		$nome=nome_pratica($pratica_id);
		}
	elseif($projeto_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Filtrar arquivos pel'.$config['genero_projeto'].' '.$config['projeto'].' que estão relacionados.').ucfirst($config['projeto']).':'.dicaF();
		$nome=nome_projeto($projeto_id);
		}	
	elseif($pratica_indicador_id){
		$legenda_filtro=dica('Filtrar pelo Indicador', 'Filtrar arquivos pelo indicador a qual estão relacionados.').'Indicador:'.dicaF();
		$nome=nome_indicador($pratica_indicador_id);
		}		
	else{
		$nome='';
		$legenda_filtro=dica('Filtrar', 'Selecione um campo para filtrar os arquivos.').'Filtro:'.dicaF();
		}
		
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.'<tr><td align="right">'.$legenda_filtro.'</td><td><input type="text" id="nome" name="nome" value="'.$nome.'" style="width:250px;" class="texto" READONLY /></td></tr><tr><td>&nbsp;</td><td><table cellspacing=0 cellpadding=0><tr>'.$icone_pratica.$icone_projeto.$icone_indicador.'</td></tr></table></td></tr></table>');
	$botoesTitulo->adicionaCelula('<table width="90"><tr><td>'.dica('Nova G.U.T.','Criar uma nova matriz G.U.T.').'<a class="botao" href="javascript: void(0)" onclick="frm_filtro.gut_id.value=0; frm_filtro.criar.value=1; frm_filtro.submit();"><span>nova G.U.T.</span></a>'.dicaF().'</td></tr>'.($podeEditar && $gut_id && !$editar ? '<tr><td nowrap="nowrap">'.dica('Editar Matriz G.U.T.', 'Edite a matriz G.U.T exibida.').'<a class="botao" href="javascript: void(0)" onclick="javascript:frm_filtro.editar.value=1; frm_filtro.submit();" ><span>editar</span></a>'.dicaF().'</td></tr>' : '').'</table>');
	if ($gut_id) {
		$botoesTitulo->adicionaCelula(dica('Imprimir a Matriz G.U.T.', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a matriz G.U.T.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m='.$m.'&a='.$a.'&dialogo=1&gut_id='.$gut_id.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
		if ($pode_editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $pode_editar, '','Excluir','Excluir esta matriz G.U.T.');
		}
	}
$botoesTitulo->mostrar();

echo '</form>';

	
echo '<form id="frm_gut" name="frm_gut" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="gut" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="editar" value="'.$editar.'" />';

echo '<input type="hidden" name="inserir_linha" value="" />';

echo '<input type="hidden" name="conteudo" value="" />';
echo '<input type="hidden" name="gut_id" value="'.$gut_id.'" />';
echo '<input type="hidden" name="gut_linha_id" value="" />';
echo '<input type="hidden" name="projetos_ocultos" id="projetos_ocultos" value="" />';
echo '<input type="hidden" name="tarefas_ocultas" id="tarefas_ocultas" value="" />';
echo '<input type="hidden" name="indicadores_ocultos" id="indicadores_ocultos" value="" />';
echo '<input type="hidden" name="praticas_ocultas" id="praticas_ocultas" value="" />';
echo '<input type="hidden" name="gut_usuarios" id="gut_usuarios" value="'.($gut_id ? implode(",", $usuarios) : '').'" />';
echo '<input type="hidden" name="gut_depts" id="gut_depts" value="'.($gut_id ? implode(",", $depts) : '').'" />';
echo '<script>var usuarios_id_selecionados = "'.($gut_id ? implode(",", $usuarios) : '').'"; var depts_id_selecionados = "'.($gut_id ? implode(",", $depts):'').'";</script>';	


//exibir o G.U.T.
if ($gut_id || $criar){
	echo '<table><tr><td>';
	echo '<table><tr><td><b>'.$gut_nome.'</b></td></tr>';
	echo '<tr><td>';
	
	echo '<div id="campo_matriz">';
	
		
	echo '<script>xajax_exibir_matriz_ajax('.$gut_id.',0, '.$editar.')</script>';
	
	echo '</div>';
	
	echo '</td></tr></table>';
	echo '</td><td><table><tr><td>';
	
	
	
	echo '<table cellspacing=0 cellpadding=0>';
	echo '<tr><td align=center style="background-color:#4f81bd"><b>Gravidade</b></td></tr>';
	echo '<tr><td style="background-color:#b8cce4">1 Sem gravidade</td></tr>';
	echo '<tr><td style="background-color:#b8cce4">2 Pouco grave</td></tr>';
	echo '<tr><td style="background-color:#b8cce4">3 Grave</td></tr>';
	echo '<tr><td style="background-color:#b8cce4">4 Muito grave</td></tr>';
	echo '<tr><td style="background-color:#b8cce4">5 Extremamente grave</td></tr>';
	echo '<tr><td align=center style="background-color:#c0504d"><b>Urgência</b></td></tr>';
	echo '<tr><td style="background-color:#e6b8b7">1 Não tem pressa</td></tr>';
	echo '<tr><td style="background-color:#e6b8b7">2 Pode esperar um pouco</td></tr>';
	echo '<tr><td style="background-color:#e6b8b7">3 O mais cedo possível</td></tr>';
	echo '<tr><td style="background-color:#e6b8b7">4 Com alguma urgência</td></tr>';
	echo '<tr><td style="background-color:#e6b8b7">5 Ação imediata</td></tr>';
	echo '<tr><td align=center style="background-color:#f79646"><b>Tendência</b></td></tr>';
	echo '<tr><td style="background-color:#fcd5b4">1 Não vai piorar</td></tr>';
	echo '<tr><td style="background-color:#fcd5b4">2 Vai piorar em longo prazo</td></tr>';
	echo '<tr><td style="background-color:#fcd5b4">3 Vai piorar em médio prazo</td></tr>';
	echo '<tr><td style="background-color:#fcd5b4">4 Vai piorar em pouco tempo</td></tr>';
	echo '<tr><td style="background-color:#fcd5b4">5 Vai piorar rapidamente</td></tr></table>';
	echo '</td></tr></table></td></tr></table>';
	}





//nova tabela

if ($editar || $criar){
	echo '<table id="geral" width="100%" cellspacing=0 cellpadding=0">';
	echo '<tr><td colspan=20>'.estiloTopoCaixa().'</td></tr>';
	echo '<tr><td width="100%"><table width="100%" id="dados_diagrama" style="display:" class="std2">';
	echo '<tr><td align="right">Nome:</td><td align="left"><input class="texto" value="'.$gut_nome.'" id="gut_nome" name="gut_nome" type="text" style="width:200px;"/></td></tr>';
	echo '<tr><td align="right">'.ucfirst($config['organizacao']).':</td><td align="left"><table><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'gut_cia', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om_gut();"','',1).'</div></td></tr></table></td></tr>';
	echo '<tr><td align="right">'.dica('Responsável pelo Diagrama', 'Todo diagrama de causa-efeito deve ter um responsável. O '.$config['usuario'].' responsável pelo diagrama deverá, preferencialmente, ser o encarregado de atualizar os dados no mesmo.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="gut_responsavel" name="gut_responsavel" value="'.$gut_responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om($gut_responsavel,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	echo '<tr><td nowrap="nowrap">'.dica('Nível de Acesso', 'Os diagramas de causa-efeito podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os participantes podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os participantes podem ver e editar </li><li><b>Privado</b> - Somente o responsável e os participantes podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($acesso, 'gut_acesso', 'class="texto"', ($gut_id ? $gut_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
	echo '<tr><td align="right">Designados:</td><td width="100%"><table><tr><td>'.botao($config['usuarios'], ucfirst($config['usuarios']),'Abrir uma janela onde poderá selecionar quais serão '.$config['genero_usuario'].'s '.$config['usuarios'].' designados deste diagrama.','','popUsuarios();').'</td><td>'.botao(strtolower($config['departamentos']), $config['departamentos'],'Abrir uma janela onde poderá selecionar quais serão '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' encarregad'.$config['genero_dept'].'s '.($config['genero_pratica']=='a' ? 'desta ': 'deste ').$config['pratica'].'.','','popDepts()').'</td></tr></table></td></tr>';
	echo '<tr><td align="right">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso a matriz G.U.T. seja específica de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table><tr><td>'.selecionaVetor($projetos_escolhidos, 'projetos_escolhidos', 'style="width:250px" size="3" multiple="multiple" class="texto" ondblclick="Mover2(document.frm_gut.projetos_escolhidos)"', null).'</td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr><td align="right">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso a matriz G.U.T. seja específica de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left"><table><tr><td>'.selecionaVetor($tarefas_escolhidas, 'tarefas_escolhidas', 'style="width:250px" size="3" class="texto" multiple="multiple" ondblclick="Mover2(document.frm_gut.tarefas_escolhidas)"', null).'</td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' a matriz G.U.T. irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', a matriz G.U.T. será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr><td align="right">'.dica('Indicador Relacionado', 'Caso a matriz G.U.T. seja específica de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table><tr><td>'.selecionaVetor($indicadores_escolhidos, 'indicadores_escolhidos', 'style="width:250px" size="3" class="texto" multiple="multiple" ondblclick="Mover2(document.frm_gut.indicadores_escolhidos)"', null).'</td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
	echo '<tr><td align="right">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso a matriz G.U.T. seja específica de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table><tr><td>'.selecionaVetor($praticas_escolhidas, 'praticas_escolhidas', 'style="width:250px" size="3" class="texto" multiple="multiple" ondblclick="Mover2(document.frm_gut.praticas_escolhidas)"', null).'</td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr><td>'.botao('salvar','Salvar','Salvar a matriz G.U.T.','','salvar_gut();').'</td><td align=right>'.botao('cancelar','Cancelar','Cancelar a '.($gut_id ? 'edição' : 'criação').' da matriz G.U.T.','','frm_filtro.criar.value=0; frm_filtro.editar.value=0; frm_filtro.submit();').'</td>';
	echo '</table></td></tr>';
	echo '<tr><td colspan=20>'.estiloFundoCaixa().'</td></tr>';
	echo '</table>';
	}
	
echo '</form>';

?>	

<script type="text/javascript">

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	frm_filtro.submit();
	}

function excluir() {
	if (confirm('Tem certeza que deseja excluir esta matriz G.U.T.')) {
		var f = document.frm_filtro;
		f.del.value=1;
		f.submit();
		}
	}


function checar_existe(lista, chave){
	//checar se já existe
	var existe=0;
	for(var j=0; j <lista.options.length; j++) { 
		if (lista.options[j].value==chave) {
			existe=1;
			break;
			}
		}
	return existe;
	}
	

function Mover2(ListaPARA) {
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value != "0") {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""	
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	}

// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}

// Seleciona todos os campos da lista
function selecionar(nome,campo) {
	var lista=document.getElementById(nome);
	
	var saida='';
	for (var i=0; i < lista.length ; i++) {
		if (lista.options[i].value) saida+=','+lista.options[i].value;
		}
	document.getElementById(campo).value=saida.substr(1);	
	}



var vetor_observacao=Array();

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&usuarios_id_selecionados='+usuarios_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.getElementById('gut_usuarios').value = usuario_id_string;
	contatos_id_selecionados = usuario_id_string;
	}
function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.getElementById('gut_depts').value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	}

function mudar_dept(){
	xajax_exibir_dept('gut_dept', 'dept_cia='+document.getElementById('gut_cia').value, 'style=\'width:200px;\' size=\'1\' class=\'texto\'', 'gut_dept', '', true);
	}


function popPratica_filtro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica_filtro&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica_filtro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica_filtro&tabela=praticas&cia_id='+document.getElementById('cia_id').value, 'Prática','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function popIndicador_filtro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Indicador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador_filtro&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, window.setIndicador_filtro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador_filtro&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, 'Indicador','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}
	
function popProjeto_filtro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto_filtro&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto_filtro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto_filtro&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}
	

		
function calcular(){
	gut_g=parseInt(document.getElementById('gut_g').value);
	gut_u=parseInt(document.getElementById('gut_u').value);
	gut_t=parseInt(document.getElementById('gut_t').value);
	document.getElementById('prioridade').value=gut_g+gut_u+gut_t;
	}	
	
function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('gut_cia').value+'&usuario_id='+document.getElementById('gut_responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('gut_cia').value+'&usuario_id='+document.getElementById('gut_responsavel').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('gut_responsavel').value=usuario_id;		
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}


function mudar_campos_combo(){	
	xajax_mudar_campos_ajax(document.getElementById('gut_cia').value); 	
	}

		
function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('gut_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('gut_cia').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function popTarefa() {
	if (!frm_gut.projetos_escolhidos.value) alert('Necessário selecionar projeto primeiro');
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&projeto_id='+document.getElementById('projetos_escolhidos').value+'&cia_id='+document.getElementById('gut_cia').value, 'Tarefas','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('gut_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('gut_cia').value, 'Prática','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Indicador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('gut_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('gut_cia').value, 'Indicador','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}
	
function setPratica_filtro(chave, valor){
	frm_filtro.pratica_id.value=chave;
	frm_filtro.submit();
	}

function setProjeto_filtro(chave, valor){
	frm_filtro.projeto_id.value=chave;
	frm_filtro.submit();
	}

function setIndicador_filtro(chave, valor){
	frm_filtro.pratica_indicador_id.value=chave;
	frm_filtro.submit();
	}


function setProjeto(chave, valor){
	if (!checar_existe(document.getElementById("projetos_escolhidos"), chave)){
		var opcao = document.createElement("OPTION");
		opcao.text = valor
		opcao.value = chave;
		document.getElementById("projetos_escolhidos").options.add(opcao);
		}
	}

function setTarefa(chave, valor){
	if (!checar_existe(document.getElementById("tarefas_escolhidas"), chave)){
		var opcao = document.createElement("OPTION");
		opcao.text = valor
		opcao.value = chave;
		document.getElementById("tarefas_escolhidas").options.add(opcao);
		}
	}

function setIndicador(chave, valor){
	if (!checar_existe(document.getElementById("indicadores_escolhidos"), chave)){
		var opcao = document.createElement("OPTION");
		opcao.text = valor
		opcao.value = chave;
		document.getElementById("indicadores_escolhidos").options.add(opcao);
		}
	}

function setPratica(chave, valor){
	if (!checar_existe(document.getElementById("praticas_escolhidas"), chave)){
		var opcao = document.createElement("OPTION");
		opcao.text = valor
		opcao.value = chave;
		document.getElementById("praticas_escolhidas").options.add(opcao);
		}
	}


function mudar_om_gut(){	
	xajax_selecionar_om_ajax(document.getElementById('gut_cia').value,'gut_cia','combo_cia', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om_gut();"','',1); 	
	}

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
	
function salvar_gut(){
	if(!frm_gut.gut_nome.value) alert('A matriz G.U.T. necessita ter um nome!');
	else {
		selecionar('projetos_escolhidos','projetos_ocultos');
		selecionar('tarefas_escolhidas','tarefas_ocultas');
		selecionar('indicadores_escolhidos','indicadores_ocultos');
		selecionar('praticas_escolhidas','praticas_ocultas');
		frm_gut.salvar.value=1;
		frm_gut.submit();
		} 
	}	


<?php if ($dialogo && !$sem_impressao) echo 'self.print();'; ?>

</script>



