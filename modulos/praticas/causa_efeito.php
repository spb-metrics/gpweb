<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $dialogo;

$sql = new BDConsulta;
$salvar=getParam($_REQUEST, 'salvar',0);
$conteudo=getParam($_REQUEST, 'conteudo',0);
$pode_editar=!$dialogo;
$sem_impressao=getParam($_REQUEST, 'sem_impressao',0);
$acesso=getSisValor('NivelAcesso','','','sisvalor_id');
require_once ($Aplic->getClasseModulo('cias'));
require_once ($Aplic->getClasseModulo('depts'));
$projeto_id=getParam($_REQUEST, 'projeto_id',null);
if (isset($_REQUEST['causa_efeito_id'])) $Aplic->setEstado('causa_efeito_id', getParam($_REQUEST, 'causa_efeito_id', null));
$causa_efeito_id= $Aplic->getEstado('causa_efeito_id') !== null ? $Aplic->getEstado('causa_efeito_id') : null;

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

if (isset($_REQUEST['causaefeito_responsavel'])) $Aplic->setEstado('causaefeito_responsavel', getParam($_REQUEST, 'causaefeito_responsavel', null));
$causaefeito_responsavel = $Aplic->getEstado('causaefeito_responsavel') !== null ? $Aplic->getEstado('causaefeito_responsavel') : null;

if (isset($_REQUEST['causaefeito_textobusca'])) $Aplic->setEstado('causaefeito_textobusca', getParam($_REQUEST, 'causaefeito_textobusca', null));
$pesquisar_texto = ($Aplic->getEstado('causaefeito_textobusca') ? $Aplic->getEstado('causaefeito_textobusca') : '');

if (isset($_REQUEST['projeto_id'])) $Aplic->setEstado('causaefeito_projeto', getParam($_REQUEST, 'projeto_id', null));
$projeto_id = $Aplic->getEstado('causaefeito_projeto') !== null ? $Aplic->getEstado('causaefeito_projeto') : null;


if (isset($_REQUEST['pratica_id'])) $Aplic->setEstado('causaefeito_pratica', getParam($_REQUEST, 'pratica_id', null));
$pratica_id = $Aplic->getEstado('causaefeito_pratica') !== null ? $Aplic->getEstado('causaefeito_pratica') : null;

if (isset($_REQUEST['pratica_indicador_id'])) $Aplic->setEstado('causaefeito_indicador', getParam($_REQUEST, 'pratica_indicador_id', null));
$pratica_indicador_id = $Aplic->getEstado('causaefeito_indicador') !== null ? $Aplic->getEstado('causaefeito_indicador') : null;

if (getParam($_REQUEST, 'del',null) && $causa_efeito_id){
	$sql->setExcluir('causa_efeito');
	$sql->adOnde('causa_efeito_id = '.(int)$causa_efeito_id);
	$sql->exec();
	$sql->limpar();
	
	ver2('Diagrama de Causa-Efeito excluído');
	$causa_efeito_id=null;

	}

if ($salvar){
	$vetor=array();
	$vetor_utf=mjson_decode($conteudo);
	$i=0;
	foreach($vetor_utf as $campo){
		foreach($campo as $chave => $valor) $vetor[$i][$chave]=utf8_decode($valor);
		$i++;
		}
	$causa_efeito_id=getParam($_REQUEST, 'causa_efeito_id',null);	
	if(!$causa_efeito_id){
		$sql->adTabela('causa_efeito');
		$sql->adInserir('causa_efeito_nome', getParam($_REQUEST, 'causa_efeito_nome',''));
		$sql->adInserir('causa_efeito_responsavel', getParam($_REQUEST, 'causa_efeito_responsavel',null));
		$sql->adInserir('causa_efeito_acesso', getParam($_REQUEST, 'causa_efeito_acesso',0));
		$sql->adInserir('causa_efeito_cia', getParam($_REQUEST, 'causa_efeito_cia',null));
		$sql->adInserir('causa_efeito_datahora', date('Y-m-d H:i:s'));
		$sql->adInserir('causa_efeito_objeto', $conteudo);
		$retorno=$sql->exec();
		$causa_efeito_id=$bd->Insert_ID('causa_efeito','causa_efeito_id');
		$sql->Limpar();
		}
	else{
		$sql->adTabela('causa_efeito');
		$sql->adAtualizar('causa_efeito_nome', getParam($_REQUEST, 'causa_efeito_nome',''));
		$sql->adAtualizar('causa_efeito_responsavel', getParam($_REQUEST, 'causa_efeito_responsavel',null));
		$sql->adAtualizar('causa_efeito_cia', getParam($_REQUEST, 'causa_efeito_cia',null));
		$sql->adAtualizar('causa_efeito_acesso', getParam($_REQUEST, 'causa_efeito_acesso',0));
		$sql->adAtualizar('causa_efeito_datahora', date('Y-m-d H:i:s'));
		$sql->adAtualizar('causa_efeito_objeto', $conteudo);
		$sql->adOnde('causa_efeito_id='.(int)$causa_efeito_id);
		$retorno=$sql->exec();
		$sql->Limpar();
		}	
	
	
	
	$causa_efeito_usuarios=getParam($_REQUEST, 'causa_efeito_usuarios', null);
	$causa_efeito_usuarios=explode(',', $causa_efeito_usuarios);
	$sql->setExcluir('causa_efeito_usuarios');
	$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
	$sql->exec();
	$sql->limpar();
	foreach($causa_efeito_usuarios as $chave => $usuario_id){
		if($usuario_id){
			$sql->adTabela('causa_efeito_usuarios');
			$sql->adInserir('causa_efeito_id', $causa_efeito_id);
			$sql->adInserir('usuario_id', $usuario_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	
	$causa_efeito_depts=getParam($_REQUEST, 'causa_efeito_depts', null);
	$causa_efeito_depts=explode(',', $causa_efeito_depts);
	$sql->setExcluir('causa_efeito_depts');
	$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
	$sql->exec();
	$sql->limpar();
	foreach($causa_efeito_depts as $chave => $dept_id){
		if($dept_id){
			$sql->adTabela('causa_efeito_depts');
			$sql->adInserir('causa_efeito_id', $causa_efeito_id);
			$sql->adInserir('dept_id', $dept_id);
			$sql->exec();
			$sql->limpar();
			}
		}
		
	
	
	
	$projetos_ocultos=getParam($_REQUEST, 'projetos_ocultos', array());
	$projetos_ocultos=explode(',', $projetos_ocultos);
	$sql->setExcluir('causa_efeito_projetos');
	$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
	$sql->exec();
	$sql->limpar();
	foreach($projetos_ocultos as $chave => $projeto_id){
		if($projeto_id){
			$sql->adTabela('causa_efeito_projetos');
			$sql->adInserir('causa_efeito_id', $causa_efeito_id);
			$sql->adInserir('projeto_id', $projeto_id);
			$sql->exec();
			$sql->limpar();
			}
		}	
		
	$tarefas_ocultas=getParam($_REQUEST, 'tarefas_ocultas', array());
	$tarefas_ocultas=explode(',', $tarefas_ocultas);
	$sql->setExcluir('causa_efeito_tarefas');
	$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
	$sql->exec();
	$sql->limpar();
	foreach($tarefas_ocultas as $chave => $tarefa_id){
		if($tarefa_id){
			$sql->adTabela('causa_efeito_tarefas');
			$sql->adInserir('causa_efeito_id', $causa_efeito_id);
			$sql->adInserir('tarefa_id', $tarefa_id);
			$sql->exec();
			$sql->limpar();
			}
		}
		
	$praticas_ocultas=getParam($_REQUEST, 'praticas_ocultas', array());
	$praticas_ocultas=explode(',', $praticas_ocultas);
	$sql->setExcluir('causa_efeito_praticas');
	$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
	$sql->exec();
	$sql->limpar();
	foreach($praticas_ocultas as $chave => $pratica_id){
		if($pratica_id){
			$sql->adTabela('causa_efeito_praticas');
			$sql->adInserir('causa_efeito_id', $causa_efeito_id);
			$sql->adInserir('pratica_id', $pratica_id);
			$sql->exec();
			$sql->limpar();
			}
		}		
	
	$indicadores_ocultos=getParam($_REQUEST, 'indicadores_ocultos', array());
	$indicadores_ocultos=explode(',', $indicadores_ocultos);
	$sql->setExcluir('causa_efeito_indicadores');
	$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
	$sql->exec();
	$sql->limpar();
	foreach($indicadores_ocultos as $chave => $pratica_indicador_id){
		if($pratica_indicador_id){
			$sql->adTabela('causa_efeito_indicadores');
			$sql->adInserir('causa_efeito_id', $causa_efeito_id);
			$sql->adInserir('pratica_indicador_id', $pratica_indicador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
		
	ver2('O diagrama de causa-efeito foi salvo.');
	}




$sql->adTabela('causa_efeito');
$sql->adCampo('causa_efeito.causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
if($cia_id) $sql->adOnde('causa_efeito_cia='.(int)$cia_id);
if($projeto_id) {
	$sql->esqUnir('causa_efeito_projetos','causa_efeito_projetos','causa_efeito.causa_efeito_id=causa_efeito_projetos.causa_efeito_id');
	$sql->adOnde('causa_efeito_projetos.projeto_id='.(int)$projeto_id);
	}
if($pratica_id) {
	$sql->esqUnir('causa_efeito_praticas','causa_efeito_praticas','causa_efeito.causa_efeito_id=causa_efeito_praticas.causa_efeito_id');
	$sql->adOnde('causa_efeito_praticas.pratica_id='.(int)$pratica_id);
	}
if($pratica_indicador_id) {
	$sql->esqUnir('causa_efeito_indicadores','causa_efeito_indicadores','causa_efeito.causa_efeito_id=causa_efeito_indicadores.causa_efeito_id');
	$sql->adOnde('causa_efeito_indicadores.pratica_indicador_id='.(int)$pratica_indicador_id);
	}
if($pesquisar_texto) $sql->adOnde('causa_efeito_objeto LIKE \'%'.$pesquisar_texto.'%\' OR causa_efeito_nome LIKE \'%'.$pesquisar_texto.'%\'');
$lista_causas_efeitos=$sql->Lista();
$causas_efeitos[0]='';
foreach($lista_causas_efeitos as $linha) if(permiteAcessarCausa_efeito($linha['causa_efeito_acesso'],$linha['causa_efeito_id']))$causas_efeitos[$linha['causa_efeito_id']]=$linha['causa_efeito_nome'];
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
echo '<input type="hidden" name="causa_efeito_id" value="'.$causa_efeito_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';


$botoesTitulo = new CBlocoTitulo('Diagrama de Causa-Efeito', 'causaefeito.png');
if (!$dialogo){
	$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap" align="right">'.dica('Seleção do Diagrama', 'Utilize esta opção para selecionar qual diagrama deseja visualizar.').'Diagrama:'. dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($causas_efeitos, 'causa_efeito_id', 'class="text" style="width:200px;" onchange="document.frm_filtro.submit()" class="texto"', $causa_efeito_id). '</td></tr><tr><td>'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:200px;" name="causaefeito_textobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=causa_efeito&causaefeito_textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table>', '', '', '');
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
	if ($causa_efeito_id) {
		if ($pode_editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $pode_editar, '','Excluir','Excluir este diagrama de causa-efeito.');
		
		$botoesTitulo->adicionaCelula(dica('Imprimir o Diagrama de Causa-Efeito', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o diagrama de causa-efeito.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m='.$m.'&a='.$a.'&dialogo=1&causa_efeito_id='.$causa_efeito_id.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());	
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


if(!$causa_efeito_id){
	//Criar
	$root = $arvore->getRootNode();
	$root->text = "Problemática";
	$root->expand=true;
	$root->image="ball_glass_redS.gif";

	$arvore->Add("root","materia","Matéria-prima",false,"ball_glass_greenS.gif");
	$arvore->Add("root","maquinario","Maquinário",false,"ball_glass_greenS.gif");
	$arvore->Add("root","maodeobra","Mão de Obra",false,"ball_glass_greenS.gif");
	$arvore->Add("root","metodo","Método",false,"ball_glass_greenS.gif");
	$arvore->Add("root","medicao","Medição",false,"ball_glass_greenS.gif");
	$arvore->Add("root","meioambiente","Meio-Ambiente",false,"ball_glass_greenS.gif");
	
	$causa_efeito_projeto_id=$projeto_id;
	$causa_efeito_nome='';
	$causa_efeito_cia=$cia_id;
	$causa_efeito_responsavel=null;
	$causa_efeito_dept='';
	$causa_efeito_acesso=0;
	
	$projetos_escolhidos=array();
	$tarefas_escolhidas=array();
	$indicadores_escolhidos=array();
	$praticas_escolhidas=array();
	
	echo '<script>var _count = 0; var usuarios_id_selecionados = ""; var depts_id_selecionados = "";</script>';	
	}
else{
	$sql->adTabela('causa_efeito');
	$sql->adOnde('causa_efeito_id='.(int)$causa_efeito_id);
	$linha=$sql->Linha();
	$sql->limpar();
	
	$pode_editar=$pode_editar && permiteEditarCausa_efeito($linha['causa_efeito_acesso'],$linha['causa_efeito_id']);
	
	
	
	$causa_efeito_nome=$linha['causa_efeito_nome'];
	$causa_efeito_responsavel=$linha['causa_efeito_responsavel'];
	$causa_efeito_cia=$linha['causa_efeito_cia'];
	$causa_efeito_acesso=$linha['causa_efeito_acesso'];
	
	$vetor=array();
	$vetor_utf=mjson_decode($linha['causa_efeito_objeto']);
	$i=0;
	foreach($vetor_utf as $campo){
		foreach($campo as $chave => $valor) $vetor[$i][$chave]=($chave!='obs' ? utf8_decode($valor) : $valor);
		$i++;
		}

	$root = $arvore->getRootNode();
	$root->text=$vetor[0]['texto'];
	$root->addData("observacao", $vetor[0]['obs']);
	$root->expand=true;
	$root->image="ball_glass_redS.gif";
	
	$maiorfilho=0;

	$sql->adTabela('causa_efeito_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('causa_efeito_id='.(int)$causa_efeito_id);
	$lista_usuarios=$sql->Lista();
	$sql->limpar();
	$usuarios=array();
	foreach($lista_usuarios as $usuario) $usuarios[]=$usuario['usuario_id'];

	$sql->adTabela('causa_efeito_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('causa_efeito_id='.(int)$causa_efeito_id);
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
	
	
	
	
	$sql->adTabela('causa_efeito_projetos');
	$sql->esqUnir('projetos','projetos','projetos.projeto_id=causa_efeito_projetos.projeto_id');
	$sql->adCampo('projetos.projeto_id, projeto_nome');
	$sql->adOnde('causa_efeito_id='.(int)$causa_efeito_id);
	$vetor_projetos=$sql->Lista();
	$sql->limpar();
	$projetos_escolhidos=array();
	foreach($vetor_projetos as $projeto) $projetos_escolhidos[$projeto['projeto_id']]=$projeto['projeto_nome'];
	
	
	$causa_efeito_projeto_id=(isset($vetor_projetos[0]['projeto_id']) ? $vetor_projetos[0]['projeto_id'] : 0);
	
	$sql->adTabela('causa_efeito_tarefas');
	$sql->esqUnir('tarefas','tarefas','tarefas.tarefa_id=causa_efeito_tarefas.tarefa_id');
	$sql->adCampo('tarefas.tarefa_id, tarefa_nome');
	$sql->adOnde('causa_efeito_id='.(int)$causa_efeito_id);
	$vetor_tarefas=$sql->Lista();
	$sql->limpar();
	$tarefas_escolhidas=array();
	foreach($vetor_tarefas as $tarefa) $tarefas_escolhidas[$tarefa['tarefa_id']]=$tarefa['tarefa_nome'];
	
	$sql->adTabela('causa_efeito_praticas');
	$sql->esqUnir('praticas','praticas','praticas.pratica_id=causa_efeito_praticas.pratica_id');
	$sql->adCampo('praticas.pratica_id, pratica_nome');
	$sql->adOnde('causa_efeito_id='.(int)$causa_efeito_id);
	$vetor_praticas=$sql->Lista();
	$sql->limpar();
	$praticas_escolhidas=array();
	foreach($vetor_praticas as $pratica) $praticas_escolhidas[$pratica['pratica_id']]=$pratica['pratica_nome'];

	$sql->adTabela('causa_efeito_indicadores');
	$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador.pratica_indicador_id=causa_efeito_indicadores.pratica_indicador_id');
	$sql->adCampo('pratica_indicador.pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('causa_efeito_id='.(int)$causa_efeito_id);
	$vetor_indicadores=$sql->Lista();
	$sql->limpar();
	$indicadores_escolhidos=array();
	foreach($vetor_indicadores as $indicador) $indicadores_escolhidos[$indicador['pratica_indicador_id']]=$indicador['pratica_indicador_nome'];
	}

if (!$causa_efeito_id) echo '<table id="criar_causa_efeito" style="display:" cellspacing="0" cellpadding="0" style="display:none"><tr><td>'.botao('criar diagrama','Criar Diagrama','criar um novo diagrama  de causa-efeito','','document.getElementById(\'criar_causa_efeito\').style.display=\'none\'; document.getElementById(\'geral\').style.display=\'\'; document.getElementById(\'botaos_criar\').style.display=\'\'; document.getElementById(\'inserir\').style.display=\'\';').'</td></tr></table>';


echo '<form id="codigo" name="codigo" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="causa_efeito" />';
echo '<input type="hidden" name="salvar" value="1" />';
echo '<input type="hidden" name="conteudo" value="" />';
echo '<input type="hidden" name="ajax" value="1" />';
echo '<input type="hidden" name="causa_efeito_id" value="'.$causa_efeito_id.'" />';


echo '<input type="hidden" name="projetos_ocultos" id="projetos_ocultos" value="" />';
echo '<input type="hidden" name="tarefas_ocultas" id="tarefas_ocultas" value="" />';
echo '<input type="hidden" name="indicadores_ocultos" id="indicadores_ocultos" value="" />';
echo '<input type="hidden" name="praticas_ocultas" id="praticas_ocultas" value="" />';



echo '<input type="hidden" name="causa_efeito_usuarios" id="causa_efeito_usuarios" value="'.($causa_efeito_id ? implode(",", $usuarios) : '').'" />';
echo '<input type="hidden" name="causa_efeito_depts" id="causa_efeito_depts" value="'.($causa_efeito_id ? implode(",", $depts) : '').'" />';

//nova tabela
echo '<table id="geral" width="100%" cellspacing="0" cellpadding="0" style="display:'.($causa_efeito_id ? '' : 'none').'">';
	echo '<tr><td colspan=20>'.$arvore->Render().'</td></tr>';
	
	echo '<tr><td colspan=20><div id="observacao"></div></td></tr>';
	
	//botoes para navegar
	echo '<tr id="botaos_navegar" style="display:none"><td colspan=20><table>';
		echo '<tr>';
		echo '<td>'.botao('expandir','Expandir','Expandir nódulo selecionado','','expandir_nodulos_selecionados();').'</td>';
		echo '<td>'.botao('expandir tudo','Expandir Tudo','Expandir todos os nódulos','','expandir_nodulos();').'</td>';
		echo '</tr>';
	echo '</table></td></tr>';
	
	
	//botoes para criar
	echo '<tr id="botaos_criar" style="display:'.($causa_efeito_id && !$dialogo ? '' : 'none').'"><td><table>';
		echo '<tr>';
		echo '<td>'.botao('expandir','Expandir','Expandir nódulo selecionado','','expandir_nodulos_selecionados();').'</td>';
		echo '<td>'.botao('expandir tudo','Expandir Tudo','Expandir todos os nódulos','','expandir_nodulos();').'</td>';
		if ($pode_editar) {
			echo '<td>'.botao('remover','Remover','Remover nódulo selecionado','','remover_nodulos_selecionados();').'</td>';
			echo '<td>'.botao('adicionar','Adicionar','Adicionar nódulo','','adicionar();').'</td>';
			echo '<td>'.botao('editar','Editar','Editar o nódulo','','editar();').'</td>';
			echo '<td>'.botao('salvar','Salvar','Salvar o diagrama de causa-efeito','','salvar_causa_efeito();').'</td>';
			}
		echo '</tr></table>';
	echo '</td></tr>';
	
	
	
	echo '<tr><td colspan=20><table id="inserir" style="display:'.($causa_efeito_id && $pode_editar ? '' : 'none').'" width="100%" cellspacing="0" cellpadding="0" class="std2">';
		echo '<tr><td colspan=20>'.estiloTopoCaixa().'</td></tr>';

		echo '<tr id="inserir_nodulo" style="display:none"><td width="100%"><table width="100%">';
		echo '<tr><td align="right"></td><td align="left"><b>Nódulo</b></td></tr>';
			echo '<tr><td align="right">texto:</td><td align="left"><input class="texto" id="nome" name="nome" type="text" style="width:300px;"/></td></tr>';
			echo '<tr><td align="right">Obs:</td><td align="left"><textarea name="campo_observacao" id="campo_observacao" cols="60" rows="2" class="textarea"></textarea></td></tr>';
			echo '<tr id="confirmar_adicao" style="display:none"><td colspan=2><table width=100%><tr><td>'.botao('confirmar','Confirmar','Confirmar a adição do nódulo','','adicionar_nodulo(); document.getElementById(\'dados_diagrama\').style.display=\'\';').'</td><td align=right>'.botao('cancelar','Cancelar','Cancelar a adição do nódulo','','esconder(); document.getElementById(\'dados_diagrama\').style.display=\'\';').'</td></tr></table></td></tr>';
			echo '<tr id="confirmar_alteracao" style="display:none"><td colspan=2><table width=100%><tr><td style="width:100px;"></td><td>'.botao('confirmar','Confirmar','Confirmar a alteração do nódulo','','alterar_nodulo(); atualizar_obs(); document.getElementById(\'dados_diagrama\').style.display=\'\';').'</td><td align=right>'.botao('cancelar','Cancelar','Cancelar a alteração do nódulo','','esconder(); document.getElementById(\'dados_diagrama\').style.display=\'\';').'</td></tr></table></td></tr>';
		echo '</table></td></tr>';
	
	
		echo '<tr><td width="100%"><table width="100%" id="dados_diagrama" style="display:">';
			echo '<tr><td align="right">nome:</td><td align="left"><input class="texto" value="'.$causa_efeito_nome.'" id="causa_efeito_nome" name="causa_efeito_nome" type="text" style="width:200px;"/></td></tr>';
					
			echo '<tr><td align="right" width="80">'.dica(ucfirst($config['organizacao']).' do Diagrama', 'A qual '.$config['organizacao'].' pertence este diagrama.').ucfirst($config['organizacao']).':'.dicaF().'</td><td width="100%" nowrap="nowrap" colspan="2"><div id="combo_cia2_id">'.selecionar_om($causa_efeito_cia, 'causa_efeito_cia', 'class=texto size=1 style="width:280px;" onchange="javascript:mudar_om2();mudar_projeto(); mudar_indicador(); mudar_pratica(); mudar_tarefa(); mudar_dept();"').'</div></td></tr>';

			
			
			echo '<tr><td align="right">'.dica('Responsável pelo Diagrama', 'Todo diagrama de causa-efeito deve ter um responsável. O '.$config['usuario'].' responsável pelo diagrama deverá, preferencialmente, ser o encarregado de atualizar os dados no mesmo.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="causa_efeito_responsavel" name="causa_efeito_responsavel" value="'.$causa_efeito_responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om($causa_efeito_responsavel,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
			echo '<tr><td nowrap="nowrap">'.dica('Nível de Acesso', 'Os diagramas de causa-efeito podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os participantes podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os participantes podem ver e editar </li><li><b>Privado</b> - Somente o responsável e os participantes podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($acesso, 'causa_efeito_acesso', 'class="texto"', ($causa_efeito_id ? $causa_efeito_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
			echo '<tr><td align="right">Designados:</td><td width="100%"><table><tr><td>'.botao($config['usuarios'], ucfirst($config['usuarios']),'Abrir uma janela onde poderá selecionar quais serão '.$config['genero_usuario'].'s '.$config['usuarios'].' designados deste diagrama.','','popUsuarios();').'</td><td>'.botao(strtolower($config['departamentos']), $config['departamentos'],'Abrir uma janela onde poderá selecionar quais serão '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' encarregad'.$config['genero_dept'].'s '.($config['genero_pratica']=='a' ? 'desta ': 'deste ').$config['pratica'].'.','','popDepts()').'</td></tr></table></td></tr>';
			
			
			echo '<tr><td align="right">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso o diagrama de causa-efeito seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table><tr><td>'.selecionaVetor($projetos_escolhidos, 'projetos_escolhidos', 'style="width:250px" size="3" multiple="multiple" class="texto" ondblclick="Mover2(document.codigo.projetos_escolhidos)"', null).'</td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
			echo '<tr><td align="right">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso o diagrama de causa-efeito seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left"><table><tr><td>'.selecionaVetor($tarefas_escolhidas, 'tarefas_escolhidas', 'style="width:250px" size="3" class="texto" multiple="multiple" ondblclick="Mover2(document.codigo.tarefas_escolhidas)"', null).'</td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher '.($config['genero_tarefa']=='a' ? 'a' : 'ao').' qual '.$config['tarefa'].' o diagrama de causa-efeito irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o diagrama de causa-efeito será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
			echo '<tr><td align="right">'.dica('Indicador Relacionado', 'Caso o diagrama de causa-efeito seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table><tr><td>'.selecionaVetor($indicadores_escolhidos, 'indicadores_escolhidos', 'style="width:250px" size="3" class="texto" multiple="multiple" ondblclick="Mover2(document.codigo.indicadores_escolhidos)"', null).'</td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
			echo '<tr><td align="right">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso o diagrama de causa-efeito seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table><tr><td>'.selecionaVetor($praticas_escolhidas, 'praticas_escolhidas', 'style="width:250px" size="3" class="texto" multiple="multiple" ondblclick="Mover2(document.codigo.praticas_escolhidas)"', null).'</td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para escolher '.($config['genero_pratica']=='a' ? 'a' : 'ao').' qual '.$config['pratica'].' o diagrama de causa-efeito irá pertencer.').'</a></td></tr></table></td></tr>';
			
			
			
			
			echo '</table></td></tr>';
	echo '<tr><td colspan=20>'.estiloFundoCaixa().'</td></tr>';
echo '</table>';

echo '</form>';

include_once(BASE_DIR.'/modulos/praticas/causa_efeito_js.php');
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
	if (confirm('Tem certeza que deseja excluir este diagrama de causa-efeito')) {
		var f = document.frm_filtro;
		f.del.value=1;
		f.submit();
		}
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('causa_efeito_cia').value+'&usuario_id='+document.getElementById('causa_efeito_responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('causa_efeito_cia').value+'&usuario_id='+document.getElementById('causa_efeito_responsavel').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}	

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('causa_efeito_responsavel').value=usuario_id;		
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}

function mudar_om(){	
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}

function mudar_om2(){	
	var cia_id=document.getElementById('causa_efeito_cia').value;
	xajax_selecionar_om_ajax(cia_id,'causa_efeito_cia','combo_cia2_id', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om2();mudar_projeto(); mudar_indicador(); mudar_pratica(); mudar_tarefa(); mudar_dept();"'); 	
	}
	
<?php if ($dialogo) {
	echo 'expandir_nodulos();'; 
	if (!$sem_impressao) echo 'self.print();';
	}
?>	
</script>

