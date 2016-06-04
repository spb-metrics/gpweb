<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Não deveria acessar este arquivo diretamente.');
global $Aplic;

$extra=array();	
$depurar = false;
$chamarVolta = getParam($_REQUEST, 'chamar_volta', 0);
$tabela = getParam($_REQUEST, 'tabela', 0);
$usuario_id = getParam($_REQUEST, 'usuario_id', 0);
$pratica_id = intval(getParam($_REQUEST, 'pratica_id', 0));

$valores=(getParam($_REQUEST, 'valores', ''));
$edicao=getParam($_REQUEST, 'edicao', 0);

$valores=explode(',',$valores);
$selecionado=array();
foreach($valores as $chave => $valor) $selecionado[$valor]=1;

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
	
	
$nao_ha='Não foi encontrado';
$nenhum='Nenhum';

$ok = $chamarVolta & $tabela;
$titulo = 'Seletor Genérico';

//echo '<script language="javascript">function setFechar(chave, valor){if (chave!=0) window.opener.'.$chamarVolta.'(chave, valor); else window.opener.'.$chamarVolta.'(null, ""); window.close(); }</script>';


echo '<script language="javascript">function setFechar(chave, valor){
if(parent && parent.gpwebApp){if (chave) parent.gpwebApp._popupCallback(chave, valor); else parent.gpwebApp._popupCallback(null, "");} else {
if (chave) window.opener.'.$chamarVolta.'(chave, valor); else window.opener.'.$chamarVolta.'(null, ""); window.close();}}
function cancelarSelecao(){if(parent && parent.gpwebApp && parent.gpwebApp._popupWin) parent.gpwebApp._popupWin.close(); else window.close();}</script>';

echo '<form name="env" method="POST">';
echo '<input type="hidden" name="m" value="publico" />';
echo '<input type="hidden" name="a" value="selecionar_multiplo" />';
echo '<input type="hidden" name="chamar_volta" value="'.$chamarVolta.'" />';
echo '<input type="hidden" name="tabela" value="'.$tabela.'" />';
echo '<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';
echo '<input type="hidden" name="pratica_id" value="'.$pratica_id.'" />';
echo '<input type="hidden" name="cia_id" value="'.$cia_id.'" />';
echo '<input type="hidden" name="enviado" value="0" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
echo '<input type="hidden" name="cia_dept" value="" />';

if (getParam($_REQUEST, 'enviado', 0)){
	
	$qnt=0;
	$campos='';
	foreach(getParam($_REQUEST, 'campos', array()) as $chave => $valor) if ($valor) $campos.=($qnt++ ? ',' : '').$valor;
	echo '<script>setFechar("'.$campos.'", "");</script>';
	}


$classeModulo = $Aplic->getClasseModulo($tabela);
if ($classeModulo && file_exists($classeModulo)) require_once $classeModulo;
$sql = new BDConsulta;
$sql->adTabela($tabela);
$resultadoConsulta = false;

switch ($tabela) {
	
	case 'depts':
		$titulo = $config['departamento'];
		$nao_ha='Não foi encontrad'.($config['genero_dept']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['departamento'];
		$nenhum='Nenhum'.($config['genero_dept']=='a' ? 'a' : '').' '.$config['departamento'];
		$cia_id = getParam($_REQUEST, 'cia_id', 0);
		$esconder_cia = getParam($_REQUEST, 'esconder_cia', 0);
		$sql->esqUnir('cias', 'cias','cias.cia_id=dept_cia');
		$sql->adCampo('dept_id, dept_acesso');
		if ($esconder_cia == 1) $sql->adCampo('dept_nome');
		else $sql->adCampo('concatenar_tres(cia_nome, \': \', dept_nome) AS dept_nome');
		$sql->adOnde('dept_cia = '.(int)$cia_id);
		$sql->adOrdem('dept_ordem, dept_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarDept($linha['dept_acesso'], $linha['dept_id'])) $lista[$linha['dept_id']]=$linha['dept_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarDept($linha['dept_acesso'], $linha['dept_id'])) $lista[$linha['dept_id']]=$linha['dept_nome']; 
			}
		break;
		
	case 'arquivos':
		$titulo = 'Arquivo';
		$nao_ha='Não foi encontrado nenhum arquivo';
		$nenhum='Nenhum arquivo';
		$sql->adCampo('arquivo_id,arquivo_nome, arquivo_acesso, arquivo_projeto, arquivo_tarefa, arquivo_pratica, arquivo_instrumento, arquivo_demanda, arquivo_acao, arquivo_indicador');
		$sql->adOrdem('arquivo_nome');
		$sql->adOnde('arquivo_cia = '.$cia_id);
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
		
	case 'eventos':
		$titulo = 'Evento';
		$nao_ha='Não foi encontrado nenhum evento';
		$nenhum='Nenhum evento';
		$sql->adCampo('evento_id,concatenar_tres(evento_titulo, \': \', evento_inicio) as evento_nome, evento_acesso, evento_projeto, evento_tarefa, evento_pratica, evento_acao, evento_indicador, evento_calendario');
		$sql->adOrdem('evento_inicio');
		$sql->adOnde('evento_cia = '.$cia_id);
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarEvento($linha['evento_acesso'], $linha['evento_id'])) $lista[$linha['evento_id']]=$linha['evento_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarEvento($linha['evento_acesso'], $linha['evento_id'])) $lista[$linha['evento_id']]=$linha['evento_nome'];
			}
		break;	
		
	case 'foruns':
		$titulo = 'Fórum';
		$nao_ha='Não foi encontrado nenhum fórum';
		$nenhum='Nenhum fórum';
		$sql->adCampo('forum_id,forum_nome, forum_acesso');
		$sql->adOrdem('forum_nome');
		$sql->adOnde('forum_cia = '.$cia_id);
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
		$titulo = 'Agendas';
		$sql->adCampo('agenda_tipo_id, nome');
		$sql->adOnde('usuario_id='.$Aplic->usuario_id);
		$sql->adOrdem('nome');
		$nao_ha='Não foi encontrado nenhuma agenda';
		$nenhum='Nenhuma agenda';
		$lista = unirVetores(array('' => $nenhum), $sql->ListaChave());
		break;	
		
	case 'objetivos_estrategicos':
		$titulo = ucfirst($config['objetivos']);
		$sql->adCampo('pg_objetivo_estrategico_id, pg_objetivo_estrategico_nome');
		$sql->adOnde('pg_objetivo_estrategico_cia = '.$cia_id);
		$sql->adOrdem('pg_objetivo_estrategico_ordem');
		$nao_ha='Não foi encontrado nenh'.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'';
		$nenhum='Nenh'.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'';
		
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarObjetivo($linha['forum_acesso'],  $linha['forum_id'])) $lista[$linha['forum_id']]=$linha['forum_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarObjetivo($linha['forum_acesso'],  $linha['forum_id'])) $lista[$linha['forum_id']]=$linha['forum_nome'];
		
		
			}
		break;		
		
	case 'calendario':
		$titulo = 'Agenda';
		$sql->adCampo('calendario_id,calendario_nome, calendario_acesso');
		$sql->adOrdem('calendario_nome');
		if ($cia_id && !$lista_cias) $sql->adOnde('calendario_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('calendario_cia IN ('.$lista_cias.')');

		$achados=$sql->Lista();
		$nao_ha='Não foi encontrada nenhuma agenda';
		$nenhum='Nenhuma agenda';
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarCalendario($linha['calendario_acesso'], $linha['calendario_id'])) $lista[$linha['calendario_id']]=$linha['calendario_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarCalendario($linha['calendario_acesso'], $linha['calendario_id'])) $lista[$linha['calendario_id']]=$linha['calendario_nome'];
			}
		break;	
		
	case 'recursos':
		$titulo = 'Recurso';
		$nao_ha='Não foi encontrado nenhum recurso';
		$nenhum='Nenhum recurso';
		$sql->adCampo('recurso_id,recurso_nome, recurso_nivel_acesso');
		$sql->adOrdem('recurso_nome');
		$sql->adOnde('recurso_cia = '.$cia_id);
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])) $lista[$linha['recurso_id']]=$linha['recurso_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])) $lista[$linha['recurso_id']]=$linha['recurso_nome']; 
			}
		break;
		
	case 'risco':
		$titulo = ucfirst($config['riscos']);
		$nao_ha='Não foi encontrad'.($config['genero_risco']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['risco'];
		$nenhum='Nenhum'.($config['genero_risco']=='a' ? 'a' : '').' '.$config['risco'];
		$sql->adCampo('risco_id, risco_nome, risco_acesso');
		$sql->adOnde('risco_cia = '.$cia_id);
		$sql->adOrdem('risco_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarRisco($linha['risco_acesso'], $linha['risco_id'])) $lista[$linha['risco_id']]=$linha['risco_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarRisco($linha['risco_acesso'], $linha['risco_id'])) $lista[$linha['risco_id']]=$linha['risco_nome']; 
			}
		break;	
			
	case 'praticas':
		$titulo = ucfirst($config['praticas']);
		$nao_ha='Não foi encontrad'.($config['genero_pratica']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['pratica'];
		$nenhum='Nenhum'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'];
		$sql->adCampo('pratica_id, pratica_nome, pratica_acesso');
		$sql->adOnde('pratica_cia = '.$cia_id);
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
		
	case 'pratica_indicador':
		$titulo = 'Indicador';
		$nao_ha='Não foi encontrado nenhum indicador';
		$nenhum='Nenhum indicador';
		$sql->adCampo('pratica_indicador_id, pratica_indicador_nome, pratica_indicador_acesso');
		$sql->adOnde('pratica_indicador_cia = '.$cia_id);
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
		
	case 'instrumento':
		$titulo = 'Instrumentos';
		$nao_ha='Não foi encontrado nenhum instrumento';
		$nenhum='Nenhum instrumento';
		$sql->adCampo('instrumento_id, instrumento_nome, instrumento_acesso');
		$sql->adOnde('instrumento_cia = '.$cia_id);
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
		
	case 'recursos':
		$titulo = 'Recursos';
		$nao_ha='Não foi encontrado nenhum recurso';
		$nenhum='Nenhum recurso';
		$sql->adCampo('recurso_id, recurso_nome, recurso_acesso');
		$sql->adOnde('recurso_cia = '.$cia_id);
		$sql->adOrdem('recurso_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarRecurso($linha['recurso_acesso'], $linha['recurso_id'])) $lista[$linha['recurso_id']]=$linha['recurso_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarRecurso($linha['recurso_acesso'], $linha['recurso_id'])) $lista[$linha['recurso_id']]=$linha['recurso_nome']; 
			}
		break;		
					
	case 'plano_acao':
		$titulo = 'Plano de Ação';
		$nao_ha='Não foi encontrad'.($config['genero_acao']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['acao'];
		$nenhum='Nenhum'.($config['genero_acao']=='a' ? 'a' : '').' '.$config['acao'];
		$sql->adCampo('plano_acao_id,plano_acao_nome, plano_acao_acesso');
		$sql->adOnde('plano_acao_id = '.$plano_acao_id);
		$sql->adOrdem('plano_acao_ordem');
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
		$sql->adCampo('link_id,link_nome, link_acesso, link_projeto, link_tarefa, link_pratica, link_acao, link_indicador');
		$sql->adOrdem('link_nome');
		$sql->adOnde('link_cia = '.$cia_id);
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
		$nenhum='Nenhum G.U.T.';
		$sql->adCampo('gut_id,gut_nome, gut_acesso');
		$sql->adOrdem('gut_nome');
		$sql->adOnde('gut_cia = '.$cia_id);
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
		$sql->adCampo('causa_efeito_id,causa_efeito_nome, causa_efeito_acesso');
		$sql->adOrdem('causa_efeito_nome');
		$sql->adOnde('causa_efeito_cia = '.$cia_id);
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarCausa_efeito($linha['causa_efeito_acesso'], $linha['causa_efeito_id'])) $lista[$linha['causa_efeito_id']]=$linha['causa_efeito_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarCausa_efeito($linha['causa_efeito_acesso'], $linha['causa_efeito_id'])) $lista[$linha['causa_efeito_id']]=$linha['causa_efeito_nome']; 
			}
		break;	
					
	case 'projetos':
		$titulo = ucfirst($config['projeto']);
		$nenhum='Nenhum'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'];
		$sql->adCampo('projeto_id, projeto_nome, projeto_acesso');
		if ($cia_id) $sql->adOnde('projeto_cia = '.(int)$cia_id);
		$sql->adOrdem('projeto_nome');
		$achados=$sql->Lista();
		$lista=array('' => $nenhum);
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditar($linha['projeto_acesso'], $linha['projeto_id'])) $lista[$linha['projeto_id']]=$linha['projeto_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessar($linha['projeto_acesso'], $linha['projeto_id'])) $lista[$linha['projeto_id']]=$linha['projeto_nome']; 
			}
		break;
		
	case 'tarefas':
		$tarefa_projeto = getParam($_REQUEST, 'tarefa_projeto', 0);
		$titulo = ucfirst($config['tarefa']);
		$sql->esqUnir('projetos','projetos','tarefa_projeto=projeto_id');
		$sql->adCampo('tarefa_id, tarefa_nome, tarefa_superior, tarefa_projeto, projeto_nome, tarefa_acesso, tarefa_projeto');
		$sql->adOrdem('tarefa_projeto, tarefa_superior ASC, '.($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_id ASC');
		if ($tarefa_projeto) $sql->adOnde('tarefa_projeto = '.(int)$tarefa_projeto);
		if ($usuario_id > 0) {
			$sql->esqUnir('tarefa_contatos', 'tarefa_contatos','tarefa_contatos.tarefa_id=tarefas.tarefa_id');
			$sql->adOnde('tarefa_contatos.contato_id = '.(int)$usuario_id);
			}
		$lista_tarefas = $sql->ListaChave('tarefa_id');
		$nivel = 0;
		$resultadoConsulta = array();
		$ultima_superior = 0;
		$ultimo_priojeto = 0;
		foreach ($lista_tarefas as $tarefa) {
			if ($tarefa['tarefa_projeto']!=$ultimo_priojeto) $extra[$tarefa['tarefa_id']]='<br><br>'.$tarefa['projeto_nome'];
			if ($tarefa['tarefa_superior'] != $tarefa['tarefa_id']) {
				if (($ultima_superior != $tarefa['tarefa_superior'])  && ($tarefa['tarefa_projeto']==$ultimo_priojeto)) {
					$ultima_superior = $tarefa['tarefa_superior'];
					$nivel++;
					}
				elseif (($ultima_superior != $tarefa['tarefa_superior'])  && ($tarefa['tarefa_projeto']!=$ultimo_priojeto)) {
					$ultima_superior = $tarefa['tarefa_superior'];
					$nivel=1;
					} 	
				} 
			else {
				$ultima_superior = 0;
				$nivel = 0;
				$ultimo_priojeto = 0;
				}
			if ($tarefa['tarefa_projeto']!=$ultimo_priojeto) $ultimo_priojeto =$tarefa['tarefa_projeto'];
			$resultadoConsulta[$tarefa['tarefa_id']] = ($nivel ? str_repeat('&nbsp;&nbsp;&nbsp;', $nivel) : '').($nivel ? imagem('icones/subnivel.gif'):'').$tarefa['tarefa_nome'];
			if ($edicao) if (permiteEditar($tarefa['tarefa_acesso'], $tarefa['tarefa_projeto'], $tarefa['tarefa_id'])) $resultadoConsulta[$tarefa['tarefa_id']] = ($nivel ? str_repeat('&nbsp;&nbsp;&nbsp;', $nivel) : '').($nivel ? imagem('icones/subnivel.gif'):'').$tarefa['tarefa_nome'];
			elseif (permiteAcessar($tarefa['tarefa_acesso'], $tarefa['tarefa_projeto'], $tarefa['tarefa_id'])) $resultadoConsulta[$tarefa['tarefa_id']] = ($nivel ? str_repeat('&nbsp;&nbsp;&nbsp;', $nivel) : '').($nivel ? imagem('icones/subnivel.gif'):'').$tarefa['tarefa_nome'];
			}
		$nao_ha='Não foi encontrad'.($config['genero_tarefa']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['tarefa'];
		$nenhum='Nenhum'.($config['genero_tarefa']=='a' ? 'a' : '').' '.$config['tarefa'];
		$lista = $resultadoConsulta;
		break;
		
	case 'usuarios':
		$titulo = ucfirst($config['usuario']);
		$sql->adCampo('usuario_id,'.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$sql->adTabela('contatos', 'b');
		$sql->adOnde('usuario_contato = contato_id');
		$sql->adOnde('contato_cia = '.$cia_id);
		$nao_ha='Não foi encontrado nenh'.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'];
		$nenhum='Nenh'.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'];
		$lista = unirVetores(array('' => $nenhum), $sql->ListaChave());
		break;
		
	case 'contatos':
		$titulo = 'Contatos';
		$sql->adCampo('contato_id,'.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
		$sql->adOnde('contato_cia = '.$cia_id);
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$nao_ha='Não foi encontrado nenhum contato';
		$nenhum='Nenhum contato';
		$lista = unirVetores(array('' => $nenhum), $sql->ListaChave());
		break;	
	default:
		$ok = false;
		break;
	}

if (!$ok) {
	echo estiloTopoCaixa();
	echo 'Parâmetros incorretos foram passados'."\n";
	if ($depurar) {
		echo '<br />chamar_volta = '.$chamarVolta."\n";
		echo '<br />tabela = '.$tabela."\n";
		echo '<br />ok = '.$ok."\n";
		}
	} 
else {
	echo '<b>Selecionar '.$titulo.':</b>';
	echo estiloTopoCaixa();
	echo '<table class="std" width="100%" cellspacing=0 cellpadding=0>';
	
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.dept_id.value=\'\';  document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=1; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');

	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>'.$procurar_om.'</table></td></tr>';

	
	
	if (count($lista) > 0) foreach ($lista as $chave => $val) {
		if ($tabela=='tarefas') echo (isset($extra[$chave]) ? $extra[$chave] : '').'<tr><td style="margin-bottom:0cm; margin-top:0cm;"><a href="javascript:setFechar('.$chave.', \''.(isset($lista_tarefas[$chave]['tarefa_nome']) ? $lista_tarefas[$chave]['tarefa_nome'] : '').'\');">'.$val.'</a></td></tr>';
		else echo (isset($extra[$chave]) ? $extra[$chave] : '').'<tr><td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$chave.'" value="'.$chave.'" '.(isset($selecionado[$chave]) ? 'checked="checked"' : '').' /></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$val.'</td></tr>';
		}
	else 	echo '<tr><td><a href="javascript:setFechar(0, \'\');">'.$nao_ha.'</a></td></tr>';
	
	
	echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', '', '','','env.enviado.value=1; env.submit();').'</td>'.($Aplic->profissional ? '' : '<td>'.botao('cancelar', '', '','','javascript:cancelarSelecao()').'</td>').'</tr></table></td></tr>';
	
	
	echo '</table></form>';
	echo estiloFundoCaixa();
	} 
	

?>

<script language="javascript">
	
function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	env.submit();
	}	
	
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om();"'); 	
	}	
</script>