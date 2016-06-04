<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


global $dialogo, $Aplic, $m;
$sql = new BDConsulta;
$salvar=getParam($_REQUEST, 'salvar',0);
$conteudo=getParam($_REQUEST, 'conteudo',0);
$sem_impressao=getParam($_REQUEST, 'sem_impressao',0);
$pode_editar=!$dialogo;
$acesso=getSisValor('NivelAcesso','','','sisvalor_id');
require_once ($Aplic->getClasseModulo('cias'));
require_once ($Aplic->getClasseModulo('depts'));
$projeto_id=getParam($_REQUEST, 'projeto_id',null);
if (isset($_REQUEST['brainstorm_id'])) $Aplic->setEstado('brainstorm_id', getParam($_REQUEST, 'brainstorm_id', null));
$brainstorm_id= $Aplic->getEstado('brainstorm_id') !== null ? $Aplic->getEstado('brainstorm_id') : null;
if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id= $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;

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



if (isset($_REQUEST['brainstorm_responsavel'])) $Aplic->setEstado('brainstorm_responsavel', getParam($_REQUEST, 'brainstorm_responsavel', null));
$brainstorm_responsavel = $Aplic->getEstado('brainstorm_responsavel') !== null ? $Aplic->getEstado('brainstorm_responsavel') : null;

if (isset($_REQUEST['brainstorm_textobusca'])) $Aplic->setEstado('brainstorm_textobusca', getParam($_REQUEST, 'brainstorm_textobusca', null));
$pesquisar_texto = ($Aplic->getEstado('brainstorm_textobusca') ? $Aplic->getEstado('brainstorm_textobusca') : '');

if (isset($_REQUEST['projeto_id'])) $Aplic->setEstado('brainstorm_projeto', getParam($_REQUEST, 'projeto_id', null));
$projeto_id = $Aplic->getEstado('brainstorm_projeto') !== null ? $Aplic->getEstado('brainstorm_projeto') : null;


if (isset($_REQUEST['pratica_id'])) $Aplic->setEstado('brainstorm_pratica', getParam($_REQUEST, 'pratica_id', null));
$pratica_id = $Aplic->getEstado('brainstorm_pratica') !== null ? $Aplic->getEstado('brainstorm_pratica') : null;

if (isset($_REQUEST['pratica_indicador_id'])) $Aplic->setEstado('brainstorm_indicador', getParam($_REQUEST, 'pratica_indicador_id', null));
$pratica_indicador_id = $Aplic->getEstado('brainstorm_indicador') !== null ? $Aplic->getEstado('brainstorm_indicador') : null;

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

if (getParam($_REQUEST, 'del',null) && $brainstorm_id){
	$sql->setExcluir('brainstorm');
	$sql->adOnde('brainstorm_id = '.(int)$brainstorm_id);
	$sql->exec();
	$sql->limpar();
	
	ver2('Brainstorm excluído');
	$brainstorm_id=null;

	}
	
if ($salvar){
	$vetor=array();
	$vetor_utf=mjson_decode($conteudo);
	$i=0;
	foreach($vetor_utf as $campo){
		foreach($campo as $chave => $valor) $vetor[$i][$chave]=utf8_decode($valor);
		$i++;
		}
	$brainstorm_id=getParam($_REQUEST, 'brainstorm_id',null);	
	if(!$brainstorm_id){
		$sql->adTabela('brainstorm');
		$sql->adInserir('brainstorm_nome', getParam($_REQUEST, 'brainstorm_nome',''));
		$sql->adInserir('brainstorm_responsavel', getParam($_REQUEST, 'brainstorm_responsavel',null));
		$sql->adInserir('brainstorm_acesso', getParam($_REQUEST, 'brainstorm_acesso',0));
		$sql->adInserir('brainstorm_cia', getParam($_REQUEST, 'brainstorm_cia',null));
		$sql->adInserir('brainstorm_datahora', date('Y-m-d H:i:s'));
		$sql->adInserir('brainstorm_objeto', $conteudo);
		$retorno=$sql->exec();
		$brainstorm_id=$bd->Insert_ID('brainstorm','brainstorm_id');
		$sql->Limpar();
		}
	else{
		$sql->adTabela('brainstorm');
		$sql->adAtualizar('brainstorm_nome', getParam($_REQUEST, 'brainstorm_nome',''));
		$sql->adAtualizar('brainstorm_responsavel', getParam($_REQUEST, 'brainstorm_responsavel',null));
		$sql->adAtualizar('brainstorm_cia', getParam($_REQUEST, 'brainstorm_cia',null));
		$sql->adAtualizar('brainstorm_acesso', getParam($_REQUEST, 'brainstorm_acesso',0));
		$sql->adAtualizar('brainstorm_datahora', date('Y-m-d H:i:s'));
		$sql->adAtualizar('brainstorm_objeto', $conteudo);
		$sql->adOnde('brainstorm_id='.(int)$brainstorm_id);
		$retorno=$sql->exec();
		$sql->Limpar();
		}	
	
	
	
	$brainstorm_usuarios=getParam($_REQUEST, 'brainstorm_usuarios', null);
	$brainstorm_usuarios=explode(',', $brainstorm_usuarios);
	$sql->setExcluir('brainstorm_usuarios');
	$sql->adOnde('brainstorm_id = '.$brainstorm_id);
	$sql->exec();
	$sql->limpar();
	foreach($brainstorm_usuarios as $chave => $usuario_id){
		if($usuario_id){
			$sql->adTabela('brainstorm_usuarios');
			$sql->adInserir('brainstorm_id', $brainstorm_id);
			$sql->adInserir('usuario_id', $usuario_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	
	$brainstorm_depts=getParam($_REQUEST, 'brainstorm_depts', null);
	$brainstorm_depts=explode(',', $brainstorm_depts);
	$sql->setExcluir('brainstorm_depts');
	$sql->adOnde('brainstorm_id = '.$brainstorm_id);
	$sql->exec();
	$sql->limpar();
	foreach($brainstorm_depts as $chave => $dept_id){
		if($dept_id){
			$sql->adTabela('brainstorm_depts');
			$sql->adInserir('brainstorm_id', $brainstorm_id);
			$sql->adInserir('dept_id', $dept_id);
			$sql->exec();
			$sql->limpar();
			}
		}
		
	
	
	
	$projetos_ocultos=getParam($_REQUEST, 'projetos_ocultos', array());
	$projetos_ocultos=explode(',', $projetos_ocultos);
	$sql->setExcluir('brainstorm_projetos');
	$sql->adOnde('brainstorm_id = '.$brainstorm_id);
	$sql->exec();
	$sql->limpar();
	foreach($projetos_ocultos as $chave => $projeto_id){
		if($projeto_id){
			$sql->adTabela('brainstorm_projetos');
			$sql->adInserir('brainstorm_id', $brainstorm_id);
			$sql->adInserir('projeto_id', $projeto_id);
			$sql->exec();
			$sql->limpar();
			}
		}	
		
	$tarefas_ocultas=getParam($_REQUEST, 'tarefas_ocultas', array());
	$tarefas_ocultas=explode(',', $tarefas_ocultas);
	$sql->setExcluir('brainstorm_tarefas');
	$sql->adOnde('brainstorm_id = '.$brainstorm_id);
	$sql->exec();
	$sql->limpar();
	foreach($tarefas_ocultas as $chave => $tarefa_id){
		if($tarefa_id){
			$sql->adTabela('brainstorm_tarefas');
			$sql->adInserir('brainstorm_id', $brainstorm_id);
			$sql->adInserir('tarefa_id', $tarefa_id);
			$sql->exec();
			$sql->limpar();
			}
		}
		
	$praticas_ocultas=getParam($_REQUEST, 'praticas_ocultas', array());
	$praticas_ocultas=explode(',', $praticas_ocultas);
	$sql->setExcluir('brainstorm_praticas');
	$sql->adOnde('brainstorm_id = '.$brainstorm_id);
	$sql->exec();
	$sql->limpar();
	foreach($praticas_ocultas as $chave => $pratica_id){
		if($pratica_id){
			$sql->adTabela('brainstorm_praticas');
			$sql->adInserir('brainstorm_id', $brainstorm_id);
			$sql->adInserir('pratica_id', $pratica_id);
			$sql->exec();
			$sql->limpar();
			}
		}		
	
	$indicadores_ocultos=getParam($_REQUEST, 'indicadores_ocultos', array());
	$indicadores_ocultos=explode(',', $indicadores_ocultos);
	$sql->setExcluir('brainstorm_indicadores');
	$sql->adOnde('brainstorm_id = '.$brainstorm_id);
	$sql->exec();
	$sql->limpar();
	foreach($indicadores_ocultos as $chave => $pratica_indicador_id){
		if($pratica_indicador_id){
			$sql->adTabela('brainstorm_indicadores');
			$sql->adInserir('brainstorm_id', $brainstorm_id);
			$sql->adInserir('pratica_indicador_id', $pratica_indicador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
		
	ver2('O brainstorm foi salvo.');
	}




$sql->adTabela('brainstorm');
$sql->adCampo('brainstorm.brainstorm_id, brainstorm_nome, brainstorm_acesso');
if($cia_id) $sql->adOnde('brainstorm_cia='.(int)$cia_id);
if($projeto_id) {
	$sql->esqUnir('brainstorm_projetos','brainstorm_projetos','brainstorm.brainstorm_id=brainstorm_projetos.brainstorm_id');
	$sql->adOnde('brainstorm_projetos.projeto_id='.(int)$projeto_id);
	}
if($pratica_id) {
	$sql->esqUnir('brainstorm_praticas','brainstorm_praticas','brainstorm.brainstorm_id=brainstorm_praticas.brainstorm_id');
	$sql->adOnde('brainstorm_praticas.pratica_id='.(int)$pratica_id);
	}
if($pratica_indicador_id) {
	$sql->esqUnir('brainstorm_indicadores','brainstorm_indicadores','brainstorm.brainstorm_id=brainstorm_indicadores.brainstorm_id');
	$sql->adOnde('brainstorm_indicadores.pratica_indicador_id='.(int)$pratica_indicador_id);
	}
if($pesquisar_texto) $sql->adOnde('brainstorm_objeto LIKE \'%'.$pesquisar_texto.'%\' OR brainstorm_nome LIKE \'%'.$pesquisar_texto.'%\'');
$lista_causas_efeitos=$sql->Lista();
$causas_efeitos[0]='';
foreach($lista_causas_efeitos as $linha) if(permiteAcessarBrainstorm($linha['brainstorm_acesso'],$linha['brainstorm_id']))$causas_efeitos[$linha['brainstorm_id']]=$linha['brainstorm_nome'];
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


echo '<form name="frm_filtro" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="projeto_id" value="0" />';
echo '<input type="hidden" name="pratica_id" value="0" />';
echo '<input type="hidden" name="pratica_indicador_id" value="0" />';
echo '<input type="hidden" name="brainstorm_id" value="'.$brainstorm_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';

$botoesTitulo = new CBlocoTitulo('Brainstorm', 'brainstorm.gif');
if (!$dialogo){
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" align="right">'.dica('Seleção do Brainstorm', 'Utilize esta opção para selecionar qual diagrama deseja visualizar.').'Brainstorm:'. dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($causas_efeitos, 'brainstorm_id', 'class="text" style="width:200px;" onchange="document.frm_filtro.submit()" class="texto"', $brainstorm_id). '</td></tr><tr><td align=right>'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:200px;" name="brainstorm_textobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=brainstorm&brainstorm_textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table>', '', '', '');
	
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
	if ($brainstorm_id) {
		if ($pode_editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $pode_editar, '','Excluir','Excluir este brainstorm.');
		$botoesTitulo->adicionaCelula(dica('Imprimir o Brainstorm', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o brainstorm.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m='.$m.'&a='.$a.'&dialogo=1&brainstorm_id='.$brainstorm_id.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
		}
	}
$botoesTitulo->mostrar();

echo '</form>';

require "lib/coolcss/CoolControls/CoolTreeView/cooltreeview.php";

$arvore = new CoolTreeView("treeview");
$arvore->scriptFolder = "lib/coolcss/CoolControls/CoolTreeView";
$arvore->imageFolder="lib/coolcss/CoolControls/CoolTreeView/icons";
$arvore->styleFolder="default";
$arvore->showLines = true;
$arvore->EditNodeEnable = true;
$arvore->DragAndDropEnable=true;
$arvore->multipleSelectEnable = true;


if(!$brainstorm_id){
	//Criar
	$root = $arvore->getRootNode();
	$root->text = "Ideia central";
	$root->expand=true;
	$root->image="ball_glass_redS.gif";

	
	$brainstorm_projeto_id=$projeto_id;
	$brainstorm_nome='';
	$brainstorm_cia=$cia_id;
	$brainstorm_responsavel=null;
	$brainstorm_dept='';
	$brainstorm_acesso=0;
	
	$projetos_escolhidos=array();
	$tarefas_escolhidas=array();
	$indicadores_escolhidos=array();
	$praticas_escolhidas=array();
	
	echo '<script>var _count = 0; var usuarios_id_selecionados = ""; var depts_id_selecionados = "";</script>';	
	}
else{
	$sql->adTabela('brainstorm');
	$sql->adOnde('brainstorm_id='.(int)$brainstorm_id);
	$linha=$sql->Linha();
	$sql->limpar();
	
	$pode_editar=$pode_editar && permiteEditarBrainstorm($linha['brainstorm_acesso'],$linha['brainstorm_id']);
	
	
	
	$brainstorm_nome=$linha['brainstorm_nome'];
	$brainstorm_responsavel=$linha['brainstorm_responsavel'];
	$brainstorm_cia=$linha['brainstorm_cia'];
	$brainstorm_acesso=$linha['brainstorm_acesso'];
	
	$vetor=array();
	$vetor_utf=mjson_decode($linha['brainstorm_objeto']);
	$i=0;
	foreach((array)$vetor_utf as $campo){
		foreach($campo as $chave => $valor) $vetor[$i][$chave]=($chave!='obs' ? utf8_decode($valor) : $valor);
		$i++;
		}

	$root = $arvore->getRootNode();
	$root->text=$vetor[0]['texto'];
	$root->addData("observacao", $vetor[0]['obs']);
	$root->expand=true;
	$root->image="ball_glass_redS.gif";
	
	$maiorfilho=0;

	$sql->adTabela('brainstorm_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('brainstorm_id='.(int)$brainstorm_id);
	$lista_usuarios=$sql->Lista();
	$sql->limpar();
	$usuarios=array();
	foreach($lista_usuarios as $usuario) $usuarios[]=$usuario['usuario_id'];

	$sql->adTabela('brainstorm_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('brainstorm_id='.(int)$brainstorm_id);
	$lista_depts=$sql->Lista();
	$sql->limpar();
	$depts=array();
	foreach($lista_depts as $dept) $depts[]=$dept['dept_id'];
	
	$imagens=array();
	foreach ($vetor as $chave => $campo){
		if($chave>0){
			
			$imagens[$campo['filho']]=(isset($imagens[$campo['pai']])&& $imagens[$campo['pai']]=='ball_glass_greenS.gif' ? 'ball_glass_redS.gif' : 'ball_glass_greenS.gif');
			
			if (substr($campo['filho'],0,6)=='nodulo' && (int)substr($campo['filho'],6)> $maiorfilho) $maiorfilho=(int)substr($campo['filho'],6);
			
			$nodulo=$arvore->Add($campo['pai'],$campo['filho'],$campo['texto'],false,$imagens[$campo['filho']]);
			if ($campo['obs']) $nodulo->addData("observacao", $campo['obs']);
			}
		}
	
	echo '<script>var _count = '.$maiorfilho.'; var usuarios_id_selecionados = "'.implode(",", $usuarios).'"; var depts_id_selecionados = "'.implode(",", $depts).'";</script>';	
	
	
	
	
	$sql->adTabela('brainstorm_projetos');
	$sql->esqUnir('projetos','projetos','projetos.projeto_id=brainstorm_projetos.projeto_id');
	$sql->adCampo('projetos.projeto_id, projeto_nome');
	$sql->adOnde('brainstorm_id='.(int)$brainstorm_id);
	$vetor_projetos=$sql->Lista();
	$sql->limpar();
	$projetos_escolhidos=array();
	foreach($vetor_projetos as $projeto) $projetos_escolhidos[$projeto['projeto_id']]=$projeto['projeto_nome'];
	
	
	$brainstorm_projeto_id=(isset($vetor_projetos[0]['projeto_id']) ? $vetor_projetos[0]['projeto_id'] : 0);
	
	//atualizar o vetor do campo tarefas
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_id, tarefa_nome');
	if($cia_id) $sql->adOnde('tarefa_projeto='.(int)$brainstorm_projeto_id);
	$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio');
	
	$lista_tarefas += $sql->ListaChave();
	$sql->limpar();
	
	
	$sql->adTabela('brainstorm_tarefas');
	$sql->esqUnir('tarefas','tarefas','tarefas.tarefa_id=brainstorm_tarefas.tarefa_id');
	$sql->adCampo('tarefas.tarefa_id, tarefa_nome');
	$sql->adOnde('brainstorm_id='.(int)$brainstorm_id);
	$vetor_tarefas=$sql->Lista();
	$sql->limpar();
	$tarefas_escolhidas=array();
	foreach($vetor_tarefas as $tarefa) $tarefas_escolhidas[$tarefa['tarefa_id']]=$tarefa['tarefa_nome'];
	
	$sql->adTabela('brainstorm_praticas');
	$sql->esqUnir('praticas','praticas','praticas.pratica_id=brainstorm_praticas.pratica_id');
	$sql->adCampo('praticas.pratica_id, pratica_nome');
	$sql->adOnde('brainstorm_id='.(int)$brainstorm_id);
	$vetor_praticas=$sql->Lista();
	$sql->limpar();
	$praticas_escolhidas=array();
	foreach($vetor_praticas as $pratica) $praticas_escolhidas[$pratica['pratica_id']]=$pratica['pratica_nome'];

	$sql->adTabela('brainstorm_indicadores');
	$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador.pratica_indicador_id=brainstorm_indicadores.pratica_indicador_id');
	$sql->adCampo('pratica_indicador.pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('brainstorm_id='.(int)$brainstorm_id);
	$vetor_indicadores=$sql->Lista();
	$sql->limpar();
	$indicadores_escolhidos=array();
	foreach($vetor_indicadores as $indicador) $indicadores_escolhidos[$indicador['pratica_indicador_id']]=$indicador['pratica_indicador_nome'];
	}

if (!$brainstorm_id) echo '<table id="criar_brainstorm" style="display:" cellspacing="0" cellpadding="0" style="display:none"><tr><td>'.botao('criar brainstorm','Criar Brainstorm','criar um novo brainstorm','','document.getElementById(\'criar_brainstorm\').style.display=\'none\'; document.getElementById(\'geral\').style.display=\'\'; document.getElementById(\'botaos_criar\').style.display=\'\'; document.getElementById(\'inserir\').style.display=\'\';').'</td></tr></table>';


echo '<form id="codigo" name="codigo" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="brainstorm" />';
echo '<input type="hidden" name="salvar" value="1" />';
echo '<input type="hidden" name="conteudo" value="" />';
echo '<input type="hidden" name="ajax" value="1" />';
echo '<input type="hidden" name="brainstorm_id" value="'.$brainstorm_id.'" />';
echo '<input type="hidden" name="projetos_ocultos" id="projetos_ocultos" value="" />';
echo '<input type="hidden" name="tarefas_ocultas" id="tarefas_ocultas" value="" />';
echo '<input type="hidden" name="indicadores_ocultos" id="indicadores_ocultos" value="" />';
echo '<input type="hidden" name="praticas_ocultas" id="praticas_ocultas" value="" />';
echo '<input type="hidden" name="brainstorm_usuarios" id="brainstorm_usuarios" value="'.($brainstorm_id ? implode(",", $usuarios) : '').'" />';
echo '<input type="hidden" name="brainstorm_depts" id="brainstorm_depts" value="'.($brainstorm_id ? implode(",", $depts) : '').'" />';

//nova tabela
echo '<table id="geral" width="100%" cellspacing="0" cellpadding="0" style="display:'.($brainstorm_id ? '' : 'none').'">';
	echo '<tr><td colspan=20>'.$arvore->Render().'</td></tr>';
	
	echo '<tr><td colspan=20><div id="observacao"></div></td></tr>';
	

		//botoes para navegar
		echo '<tr id="botaos_navegar" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0>';
			echo '<tr>';
			echo '<td>'.botao('expandir','Expandir','Expandir nódulo selecionado','','expandir_nodulos_selecionados();').'</td>';
			echo '<td>'.botao('expandir tudo','Expandir Tudo','Expandir todos os nódulos','','expandir_nodulos();').'</td>';
			echo '</tr>';
		echo '</table></td></tr>';

	
	//botoes para criar
	echo '<tr id="botaos_criar" style="display:'.($brainstorm_id  && !$dialogo ? '' : 'none').'"><td><table cellspacing=0 cellpadding=0>';
		echo '<tr>';
		echo '<td>'.botao('expandir','Expandir','Expandir nódulo selecionado','','expandir_nodulos_selecionados();').'</td>';
		echo '<td>'.botao('expandir tudo','Expandir Tudo','Expandir todos os nódulos','','expandir_nodulos();').'</td>';
		if ($pode_editar) {
			echo '<td>'.botao('remover','Remover','Remover nódulo selecionado','','remover_nodulos_selecionados();').'</td>';
			echo '<td>'.botao('adicionar','Adicionar','Adicionar nódulo','','adicionar();').'</td>';
			echo '<td>'.botao('editar','Editar','Editar o nódulo','','editar();').'</td>';
			echo '<td>'.botao('salvar','Salvar','Salvar o brainstorm','','salvar_brainstorm();').'</td>';
			}
		echo '</tr></table>';
	echo '</td></tr>';
	
	
	
	echo '<tr><td colspan=20><table id="inserir" style="display:'.($brainstorm_id && $pode_editar ? '' : 'none').'" width="100%" cellspacing="0" cellpadding="0" class="std2">';
		echo '<tr><td colspan=20>'.estiloTopoCaixa().'</td></tr>';
	
	
		echo '<tr id="inserir_nodulo" style="display:none"><td width="100%"><table width="100%">';
		echo '<tr><td align="right"></td><td align="left"><b>Nódulo</b></td></tr>';
			echo '<tr><td align="right">texto:</td><td align="left"><input class="texto" id="nome" name="nome" type="text" style="width:300px;"/></td></tr>';
			echo '<tr><td align="right">Obs:</td><td align="left"><textarea name="campo_observacao" id="campo_observacao" cols="60" rows="2" class="textarea"></textarea></td></tr>';
			echo '<tr id="confirmar_adicao" style="display:none"><td colspan=2><table width=100%><tr><td>'.botao('confirmar','Confirmar','Confirmar a adição do nódulo','','adicionar_nodulo(); document.getElementById(\'dados_diagrama\').style.display=\'\';').'</td><td align=right>'.botao('cancelar','Cancelar','Cancelar a adição do nódulo','','esconder(); document.getElementById(\'dados_diagrama\').style.display=\'\';').'</td></tr></table></td></tr>';
			echo '<tr id="confirmar_alteracao" style="display:none"><td colspan=2><table width=100%><tr><td style="width:100px;"></td><td>'.botao('confirmar','Confirmar','Confirmar a alteração do nódulo','','alterar_nodulo(); atualizar_obs(); document.getElementById(\'dados_diagrama\').style.display=\'\';').'</td><td align=right>'.botao('cancelar','Cancelar','Cancelar a alteração do nódulo','','esconder(); document.getElementById(\'dados_diagrama\').style.display=\'\';').'</td></tr></table></td></tr>';
		echo '</table></td></tr>';
	
	
		echo '<tr><td width="100%"><table width="100%" id="dados_diagrama" style="display:">';
			echo '<tr><td align="right">nome:</td><td align="left"><input class="texto" value="'.$brainstorm_nome.'" id="brainstorm_nome" name="brainstorm_nome" type="text" style="width:200px;"/></td></tr>';
					
			echo '<tr><td align="right" width="80">'.dica(ucfirst($config['organizacao']).' do Diagrama', 'A qual '.$config['organizacao'].' pertence este diagrama.').ucfirst($config['organizacao']).':'.dicaF().'</td><td width="100%" nowrap="nowrap" colspan="2"><div id="combo_cia2_id">'.selecionar_om($brainstorm_cia, 'brainstorm_cia', 'class=texto size=1 style="width:280px;" onchange="javascript:mudar_om2();mudar_projeto(); mudar_indicador(); mudar_pratica(); mudar_tarefa(); mudar_dept();"').'</div></td></tr>';

			
			echo '<tr><td align="right">'.dica('Responsável pelo Brainstorm', 'Todo brainstorm deve ter um responsável. O '.$config['usuario'].' responsável pelo brainstorm deverá, preferencialmente, ser o encarregado de atualizar os dados no mesmo.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="brainstorm_responsavel" name="brainstorm_responsavel" value="'.$brainstorm_responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om($brainstorm_responsavel,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
			echo '<tr><td nowrap="nowrap">'.dica('Nível de Acesso', 'Os brainstorms  podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os participantes podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os participantes podem ver e editar </li><li><b>Privado</b> - Somente o responsável e os participantes podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($acesso, 'brainstorm_acesso', 'class="texto"', ($brainstorm_id ? $brainstorm_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
			echo '<tr><td align="right">Designados:</td><td width="100%"><table cellspacing=0 cellpadding=0><tr><td>'.botao($config['usuarios'], ucfirst($config['usuarios']),'Abrir uma janela onde poderá selecionar quais serão '.$config['genero_usuario'].'s '.$config['usuarios'].' designados deste brainstorm.','','popUsuarios();').'</td><td>'.botao(strtolower($config['departamentos']), $config['departamentos'],'Abrir uma janela onde poderá selecionar quais serão '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' encarregad'.$config['genero_dept'].'s '.($config['genero_pratica']=='a' ? 'desta ': 'deste ').$config['pratica'].'.','','popDepts()').'</td></tr></table></td></tr>';
			
			
			echo '<tr><td align="right">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso o brainstorm seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td>'.selecionaVetor($projetos_escolhidos, 'projetos_escolhidos', 'style="width:250px" size="3" multiple="multiple" class="texto" ondblclick="Mover2(document.codigo.projetos_escolhidos)"', null).'</td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
			echo '<tr><td align="right">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso o brainstorm seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td>'.selecionaVetor($tarefas_escolhidas, 'tarefas_escolhidas', 'style="width:250px" size="3" class="texto" multiple="multiple" ondblclick="Mover2(document.codigo.tarefas_escolhidas)"', null).'</td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o brainstorm irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o brainstorm será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
			echo '<tr><td align="right">'.dica('Indicador Relacionado', 'Caso o brainstorm seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td>'.selecionaVetor($indicadores_escolhidos, 'indicadores_escolhidos', 'style="width:250px" size="3" class="texto" multiple="multiple" ondblclick="Mover2(document.codigo.indicadores_escolhidos)"', null).'</td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
			echo '<tr><td align="right">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso o brainstorm seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td>'.selecionaVetor($praticas_escolhidas, 'praticas_escolhidas', 'style="width:250px" size="3" class="texto" multiple="multiple" ondblclick="Mover2(document.codigo.praticas_escolhidas)"', null).'</td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
			
			
			
			
			echo '</table></td></tr>';
	echo '<tr><td colspan=20>'.estiloFundoCaixa().'</td></tr>';
echo '</table>';
echo '</form>';

include_once(BASE_DIR.'/modulos/praticas/brainstorm_js.php');
?>
<script language="javascript">

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
	if (confirm('Tem certeza que deseja excluir este brainstorm')) {
		var f = document.frm_filtro;
		f.del.value=1;
		f.submit();
		}
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('brainstorm_cia').value+'&usuario_id='+document.getElementById('brainstorm_responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('brainstorm_cia').value+'&usuario_id='+document.getElementById('brainstorm_responsavel').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}	

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('brainstorm_responsavel').value=usuario_id;		
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}

function mudar_om2(){	
	var cia_id=document.getElementById('brainstorm_cia').value;
	xajax_selecionar_om_ajax(cia_id,'brainstorm_cia','combo_cia2_id', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om2();mudar_projeto(); mudar_indicador(); mudar_pratica(); mudar_tarefa(); mudar_dept();"'); 	
	}


function mudar_om(){	
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}
	
<?php if ($dialogo) {
	echo 'expandir_nodulos();'; 
	if (!$sem_impressao) echo 'self.print();';
	}
?>	

</script>

