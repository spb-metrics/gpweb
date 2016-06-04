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
if (!$dialogo) $Aplic->salvarPosicao();

$usuario_id=$Aplic->usuario_id;

$projeto_status_aguardando = 4;
if (isset($_REQUEST['tab'])) $Aplic->setEstado('TabParaFazerTarefa', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('TabParaFazerTarefa') !== null ? $Aplic->getEstado('TabParaFazerTarefa') : 0;

//$evento_filtro = $Aplic->getEstado('IdxFiltro' , $Aplic->usuario_prefs['filtroevento']);
if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('IdxDept', getParam($_REQUEST, 'dept_id', null));

$escolhe_projeto='';

//$evento_filtro_lista = array('meu' => 'Meus eventos', 'dono' => 'Eventos que eu criei', 'todos' => 'Todos os eventos');
$evento_filtro='todos';

$sql = new BDConsulta;

if (!isset($ver_min) || !$ver_min) {
	$botoesTitulo = new CBlocoTitulo('A Fazer', 'afazer.png', $m, $m.'.'.$a);
	//$botoesTitulo->adicionaCelula(dica('Filtrar Eventos', 'Filtrar os eventos por uma das três opções:</br></br><li><b>Meus Eventos</b> - eventos em que sejas um dos endereçados, como reunião, '.$config['tarefas'].' e outras atividades.</li><li><b>Eventos que Eu Criei</b> - eventos que tenha inserido neste Sistema, com'.$config['genero_usuario'].' '.$config['usuario'].'.</li><li><b>Todos os Eventos</b> - Não será aplicado nenhum tipo de filtro.').'Eventos:'.dicaF().'<form method="post" name="frmFiltroEvento"><input type="hidden" name="m" value="tarefas" /><input type="hidden" name="a" value="parafazer" />'.selecionaVetor($evento_filtro_lista, 'evento_filtro', 'onchange="document.frmFiltroEvento.submit()" class="texto"', $evento_filtro).'</form>'.$escolhe_projeto, '', '', '');
	$botoesTitulo->mostrar();
	}
if ($a == 'parafazer') {
	$podeAcessar_email=$Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso');
	$podeAcessar_calendario=$Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso');
	$podeAcessar_tarefas=$Aplic->modulo_ativo('tarefas') && $Aplic->checarModulo('tarefas', 'acesso');
	$podeAcessar_praticas=$Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso');
	$caixaTab = new CTabBox('m=tarefas&a=parafazer', '', $tab);
	
	
	
	//quantidade Eventos
	$data_inicio =  new CData();
	
	$sql->adTabela('eventos', 'e');
	$sql->esqUnir('evento_usuarios', 'eu', 'eu.evento_id = e.evento_id');
	$sql->adOnde('(evento_dono IN ('.$Aplic->usuario_lista_grupo.') OR (eu.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND (eu.aceito=1 || eu.aceito=0)))');		
	$sql->adOnde('evento_inicio >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'');
	$sql->adCampo('count(DISTINCT e.evento_id)');
	$qnt = $sql->Resultado();
	$sql->limpar();
	if ($podeAcessar_calendario) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/tab_usuario.ver.eventos', 'Eventos ('.$qnt.')',null,null,'Eventos','Visualizar os eventos em que esteja envolvido.');
	
	
	$sql->adTabela('agenda');
	$sql->esqUnir('agenda_usuarios', 'agenda_usuarios', 'agenda_usuarios.agenda_id = agenda.agenda_id');
	$sql->adOnde('(agenda_dono IN ('.$Aplic->usuario_lista_grupo.') OR (agenda_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND (agenda_usuarios.aceito=1 || agenda_usuarios.aceito=0)))');		
	$sql->adOnde('agenda_inicio >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'');
	$sql->adCampo('count(DISTINCT agenda.agenda_id)');
	$qnt = $sql->Resultado();
	$sql->limpar();
	
	if ($podeAcessar_email) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/tab_usuario.ver.compromissos', 'Compromissos ('.$qnt.')',null,null,'Compromissos','Visualizar os compromissos em que esteja envolvido.');
	
	if ($podeAcessar_tarefas){
		
		
		$sql->adTabela('tarefas', 'ta');
		$sql->esqUnir('projetos', 'pr','pr.projeto_id=tarefa_projeto');
		$sql->esqUnir('tarefa_designados', 'td','td.tarefa_id = ta.tarefa_id');
		$sql->adCampo('count(DISTINCT ta.tarefa_id)');
		$sql->adOnde('projeto_template = 0 OR projeto_template IS NULL');
		$sql->adOnde('ta.tarefa_percentagem < 100 OR ta.tarefa_percentagem IS NULL');
		$sql->adOnde('projeto_ativo = 1');
		$sql->adOnde('td.usuario_id IN ('.$Aplic->usuario_lista_grupo.') OR tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
		$qnt = $sql->Resultado();
		$sql->limpar();
		
		
		$caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/parafazer_tarefas_sub', ucfirst($config['tarefas']).' ('.$qnt.')',null,null,ucfirst($config['tarefas']),'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' que seja responsável ou foi designado.');
		}
		
	
		
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) {
		$sql->adTabela('pratica_indicador');
		$sql->adCampo('count(DISTINCT pratica_indicador.pratica_indicador_id)');
		$sql->esqUnir('pratica_indicador_usuarios','pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR pratica_indicador_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$qnt = $sql->Resultado();
		$sql->limpar();
		
		$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores ('.$qnt.')',null,null,'Indicadores','Visualizar os indicadores que seja responsável ou foi designado.');
		}
		
		
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'pratica')) {
		$sql->adTabela('praticas');
		$sql->esqUnir('pratica_usuarios', 'pratica_usuarios', 'pratica_usuarios.pratica_id=praticas.pratica_id');
		$sql->adOnde('pratica_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR pratica_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adCampo('count(DISTINCT praticas.pratica_id)');
		$qnt = $sql->Resultado();
		$sql->limpar();
				
		
		$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_praticas', ucfirst($config['praticas']).' ('.$qnt.')',null,null,ucfirst($config['praticas']),'Visualizar '.$config['genero_pratica'].'s '.$config['praticas'].' que seja responsável ou foi designado.');
		}
	
	
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) {
		
		$sql->adTabela('plano_acao');
		$sql->adCampo('count(DISTINCT plano_acao.plano_acao_id) as soma');
		$sql->esqUnir('plano_acao_usuarios', 'plano_acao_usuarios', 'plano_acao_usuarios.plano_acao_id = plano_acao.plano_acao_id');
		$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR plano_acao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('plano_acao_percentagem < 100');
		$sql->adOnde('plano_acao_ativo = 1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']).' ('.$qnt.')',null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' que seja responsável ou foi designado.');
		
		
		
		$sql->adTabela('plano_acao_item');
		$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id = plano_acao_item_acao');
		$sql->adCampo('count(DISTINCT plano_acao_item.plano_acao_item_id) as soma');
		$sql->esqUnir('plano_acao_item_designados', 'plano_acao_item_designados', 'plano_acao_item_designados.plano_acao_item_id = plano_acao_item.plano_acao_item_id');
		$sql->adOnde('plano_acao_item_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR plano_acao_item_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('plano_acao_item_percentagem < 100');
		$sql->adOnde('plano_acao_ativo = 1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_itens_idx', 'Itens de '.ucfirst($config['acoes']).' ('.$qnt.')',null,null,'Itens de '.ucfirst($config['acoes']),'Visualizar os itens de '.$config['genero_acao'].'s '.$config['acoes'].' que seja responsável ou foi designado.');
		
		
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) {
		$sql->adTabela('problema');
		$sql->adCampo('count(DISTINCT problema.problema_id)');
		$sql->esqUnir('problema_usuarios','problema_usuarios','problema_usuarios.problema_id=problema.problema_id');
		$sql->adOnde('problema_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR problema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('problema_percentagem < 100');
		$sql->adOnde('problema_ativo=1');
		$qnt = $sql->Resultado();
		$sql->limpar();
	
		
		$caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']).' ('.$qnt.')',null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' que seja responsável ou foi designado.');
		}
	
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) {
		$sql->adTabela('ata_acao');
		$sql->esqUnir('ata','ata','ata_acao_ata = ata.ata_id');
		$sql->adCampo('count(DISTINCT ata_acao.ata_acao_id)');
		$sql->esqUnir('ata_acao_usuario','ata_acao_usuario','ata_acao_usuario_acao=ata_acao.ata_acao_id');	
	 	$sql->adOnde('ata_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR ata_acao_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')'); 	
		$sql->adOnde('ata_acao_percentagem < 100');
		$sql->adOnde('ata_ativo=1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		
		
		$caixaTab->adicionar(BASE_DIR.'/modulos/atas/acao_tabela', 'Ações de Atas'.' ('.$qnt.')',null,null,'Ações de Atas de Reunião','Visualizar as ações de atas de reunião que seja responsável ou foi designado.');
	
		}
	
	if ($Aplic->profissional) {
		
		$sql->adTabela('assinatura');
		$sql->adCampo('count(assinatura_id)');
		$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
		$sql->adOnde('assinatura_data IS NULL');
		$qnt = $sql->Resultado();
		$sql->limpar();
		
		$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_assinaturas_pro', 'Assinaturas'.' ('.$qnt.')',null,null,'Assinaturas','Visualizar os módulos em que necessita ainda aprovar.');
		}
	$caixaTab->mostrar('','','','',true);
	echo '</td></tr></table>';
	}
else include BASE_DIR.'/modulos/tarefas/parafazer_tarefas_sub.php';
if ($m !='calendario') echo estiloFundoCaixa();
?>
<script type="text/javascript">

function popLog(tarefa_id) {
	if(window.parent && window.parent.gpwebApp)	window.parent.gpwebApp.popUp('Registro',800, 465,'m=tarefas&a=ver_log_atualizar_pro&dialogo=1&tarefa_id='+tarefa_id,window.retornoLog, window);
	else window.open('./index.php?m=tarefas&a=ver_log_atualizar&dialogo=1&tarefa_id='+tarefa_id, 'Registro','height=322,width=800px,resizable,scrollbars=no');
	}

function retornoLog(update){
	if(update){
		url_passar(false,'m=tarefas&a=parafazer');
	}
}

function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	document.escolherFiltro.submit();
	}
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
</script>