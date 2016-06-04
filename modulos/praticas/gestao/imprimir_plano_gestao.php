<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $m, $a;

$ano = getParam($_REQUEST, 'pg_ano', 0);
$cia_id = getParam($_REQUEST, 'pg_cia', 0);

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);

$pg_modelo_id = getParam($_REQUEST, 'pratica_modelo_id', 0);
$pratica_descricao = getParam($_REQUEST, 'pratica_descricao', '');
$indicador_descricao = getParam($_REQUEST, 'indicador_descricao', '');
$pratica_5w2h = getParam($_REQUEST, 'pratica_5w2h', '');
$indicador_5w2h = getParam($_REQUEST, 'indicador_5w2h', '');
$pratica_extra = getParam($_REQUEST, 'pratica_extra', '');
$indicador_extra = getParam($_REQUEST, 'indicador_extra', '');
$pratica_legenda = getParam($_REQUEST, 'pratica_legenda', '');
$indicador_legenda = getParam($_REQUEST, 'indicador_legenda', '');
$mostrado = getParam($_REQUEST, 'mostrado', '');
$letras=array(1=>'a.', 2=>'b.', 3=>'c.', 4=>'d.', 5=>'e.', 6=>'f.', 7=>'g.', 8=>'h.', 9=>'i.', 10=>'j.', 11=>'l.', 12=>'m.', 13=>'n.', 14=>'o.', 15=>'p.', 16=>'q.', 17=>'r.', 18=>'s.');
$romanos=array(1=>'I.', 2=>'II.', 3=>'III.', 4=>'IV.', 5=>'V.', 6=>'VI.', 7=>'VII.', 8=>'VIII.', 9=>'IX.', 10=>'X.', 11=>'XI.', 12=>'XII.', 13=>'XIII.', 14=>'XIC.', 15=>'XV.', 16=>'XVI.', 17=>'XVII.', 18=>'XIII.');
$tipos_imagem=array(0=>'gif', 1=>'jpeg', 2=>'png', 3=>'bmp', 4=>'pjpg');
echo '<html><head><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css"></head><body>';
$indicadores_vistos=array();
$praticas_vistas=array();
$praticas_nomes=array();
$indicadores_nomes=array();
$indicadores_posicao=array();
$indicadores_posicao=array();
$praticas_posicao=array();
$numero=0;
$numero_indicadores=0; 
$numero_praticas=0;  
$imagem=0;

$sql = new BDConsulta;
$sql->adTabela('plano_gestao');
$sql->esqUnir('plano_gestao2','plano_gestao2','plano_gestao2.pg_id=plano_gestao.pg_id');
$sql->esqUnir('cias','cias','cias.cia_id=plano_gestao.pg_cia');
$sql->adCampo('plano_gestao.pg_id, pg_cia, pg_ano, pg_modelo, pg_estrut_org, pg_fornecedores, pg_ultima_alteracao, pg_usuario_ultima_alteracao, pg_processos_apoio, pg_processos_finalistico, pg_produtos_servicos, pg_clientes, pg_posgraduados, pg_graduados, pg_nivelmedio, pg_nivelfundamental, pg_semescolaridade, pg_pessoalinterno, pg_programas_acoes, pg_premiacoes, pg_missao, pg_missao_esc_superior, pg_visao_futuro, pg_visao_futuro_detalhada, pg_ponto_forte, pg_oportunidade_melhoria, pg_oportunidade, pg_ameaca, pg_principio, pg_diretriz_superior, pg_diretriz, pg_objetivo_estrategico, pg_fator_critico, pg_estrategia, pg_meta,cia_cabacalho');
$sql->adOnde('pg_cia='.(int)$cia_id);
if ($ano) $sql->adOnde('pg_inicio<=\''.$ano.'-12-31\' AND pg_fim>=\''.$ano.'-01-01\'');
if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
$pg=$sql->Linha();
$sql->limpar();

$pg_id=$pg['pg_id'];
if (!$pg_id) {
	
	echo '<br><br><center>Não há dados para ser impresso do ano '.$ano.' n'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada!</center><br><br>';
	echo '<center><a href="javascript:void(0);" onclick="self.close()"><b>Fechar esta janela</b></a></center>';
	exit();
	}

include_once(BASE_DIR.'/modulos/praticas/gestao/funcoes_impressao.php');	

echo '<table width="800" cellpadding=2 cellspacing=0>';
echo '<tr><td align="center" colspan=2><h1>'.$pg['cia_cabacalho'].'<br>'.strtoupper($config['relatorio_gestao']).'</h1></td></tr><tr><td>';

//estrutura org
if (tem_anexo('EstruturaOrganizacional') || $pg['pg_estrut_org']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. ESTRUTURA ORGANIZACIONAL</b></font></td></tr>';
	if ($pg['pg_estrut_org']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_estrut_org'].'</font></td></tr>';
	if (tem_anexo('EstruturaOrganizacional')) echo imprimir_anexo('EstruturaOrganizacional');
	}

//fornecedores
if (tem_tabela('plano_gestao_fornecedores','pg_fornecedor_id','pg_fornecedor_pg_id') || tem_anexo('FornecedoreseInsumos') || $pg['pg_fornecedores']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. FORNECEDORES E INSUMOS</b></font></td></tr>';
	if ($pg['pg_fornecedores']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_fornecedores'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('plano_gestao_fornecedores');
	$sql->adCampo('*');
	$sql->adOnde('pg_fornecedor_pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_fornecedor_ordem ASC');
	$fornecedores=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($fornecedores && count($fornecedores)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;Fornecedor'.(count($fornecedores)>1 ? 'es':'').'&nbsp;</th><th>&nbsp;Insumo'.(count($fornecedores)>1 ? 's':'').'&nbsp;</th></tr>';
		foreach ($fornecedores as $fornecedor) $saida.= '<tr><td>'.$fornecedor['pg_fornecedor_nome'].'</td><td>'.$fornecedor['pg_fornecedor_insumo'].'</td></tr>';
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('FornecedoreseInsumos')) echo imprimir_anexo('FornecedoreseInsumos');
	}
	
//processos principais e apoio	
if (tem_anexo('ProcessosProdutosServicos') ||$pg['pg_processos_apoio'] || $pg['pg_processos_apoio'] || $pg['pg_processos_apoio']) {
	$letra=0;
	$romano=0;
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. PROCESSOS E PRODUTOS/SERVIÇOS</b></font></td></tr>';
	if ($pg['pg_processos_apoio']) echo '<tr><td width="30" align="right"></td><td><table><tr><td>'.$letras[++$letra].'</td><td width="100%"><font size=2>PRINCIPAIS PROCESSOS DE APOIO</font></td></tr><tr><td width="30">&nbsp;</td><td><table><tr><td width="10" align="right"></td><td>'.$pg['pg_processos_apoio'].'</td></tr></table></td></tr></table></td></tr>';
	if ($pg['pg_processos_finalistico']) echo '<tr><td width="30" align="right"></td><td><table><tr><td>'.$letras[++$letra].'</td><td width="100%"><font size=2>PRINCIPAIS PROCESSOS FINALÍSTICOS</font></td></tr><tr><td width="30">&nbsp;</td><td><table><tr><td width="10" align="right"></td><td>'.$pg['pg_processos_finalistico'].'</td></tr></table></td></tr></table></td></tr>';
	if ($pg['pg_produtos_servicos']) echo '<tr><td width="30" align="right"></td><td><table><tr><td>'.$letras[++$letra].'</td><td width="100%"><font size=2>PRINCIPAIS PRODUTOS/SERVIÇOS</font></td></tr><tr><td width="30">&nbsp;</td><td><table><tr><td width="10" align="right"></td><td>'.$pg['pg_produtos_servicos'].'</td></tr></table></td></tr></table></td></tr>';
	if (tem_anexo('ProcessosProdutosServicos')) echo imprimir_anexo('ProcessosProdutosServicos');
	}	
	

//clientes
if ($pg['pg_clientes'] || tem_anexo('Clientes')) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. CLIENTES / USUÁRIOS</b></font></td></tr>';
	if ($pg['pg_clientes'])echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_clientes'].'</font></td></tr>';
	if (tem_anexo('Clientes')) echo imprimir_anexo('Clientes');
	}


//pessoal
//fornecedores
if (tem_tabela('plano_gestao_pessoal','pg_pessoal_id','pg_pessoal_pg_id') || tem_anexo('PessoalInterno') || $pg['pg_pessoalinterno']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. QUADRO DE PESSOAL</b></font></td></tr>';
	if ($pg['pg_pessoalinterno']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_pessoalinterno'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('plano_gestao_pessoal');
	$sql->adCampo('*');
	$sql->adOnde('pg_pessoal_pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_pessoal_ordem ASC');
	$pessoas=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($pessoas && count($pessoas)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;'.(count($pessoas)>1 ? 'Funções': 'Função').'&nbsp;</th><th>&nbsp;Previsto&nbsp;</th><th>&nbsp;Existe&nbsp;</th><th>&nbsp;Diferença&nbsp;</th></tr>';
		foreach ($pessoas as $pessoa) {
			$diferença=(int)($pessoa['pg_pessoal_existente']-$pessoa['pg_pessoal_previsto']);
			$saida.= '<tr><td>'.$pessoa['pg_pessoal_posto'].'</td><td align="center">&nbsp;'.(int)$pessoa['pg_pessoal_previsto'].'</td><td align="center">&nbsp;'.(int)$pessoa['pg_pessoal_existente'].'</td><td align="center">&nbsp;'.($diferença>0 ? '+':'').$diferença.'</td></tr>';
			}
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('PessoalInterno')) echo imprimir_anexo('PessoalInterno');
	}



//programas
if (tem_anexo('ProgramasAcoes') || $pg['pg_programas_acoes']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. PROGRAMAS E AÇÕES</b></font></td></tr>';
	if ($pg['pg_programas_acoes']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_programas_acoes'].'</font></td></tr>';
	if (tem_anexo('ProgramasAcoes')) echo imprimir_anexo('ProgramasAcoes');
	}



//premiacoes
if (tem_tabela('plano_gestao_premiacoes','pg_premiacao_id','pg_premiacao_pg_id') || tem_anexo('Premiacoes') || $pg['pg_premiacoes']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. PREMIAÇÕES</b></font></td></tr>';
	if ($pg['pg_premiacoes']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_premiacoes'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('plano_gestao_premiacoes');
	$sql->adCampo('*');
	$sql->adOnde('pg_premiacao_pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_premiacao_ordem ASC');
	$premiacoes=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($premiacoes && count($premiacoes)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;'.(count($premiacoes)>1 ? 'Premiações':'Premiação').'&nbsp;</th><th>&nbsp;Ano&nbsp;</th></tr>';
		foreach ($premiacoes as $premiacao) $saida.= '<tr><td>'.$premiacao['pg_premiacao_nome'].'</td><td>'.$premiacao['pg_premiacao_ano'].'</td></tr>';
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('Premiacoes')) echo imprimir_anexo('Premiacoes');
	}



//missao
if (tem_anexo('Missao') ||$pg['pg_missao'] || $pg['pg_missao_esc_superior']) {
	$letra=0;
	$romano=0;
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. MISSÃO</b></font></td></tr>';
	if ($pg['pg_missao']) echo '<tr><td width="30" align="right"></td><td><table><tr><td>'.$letras[++$letra].'</td><td width="100%"><font size=2>MISSÃO DA '.strtoupper($config['organizacao']).'</font></td></tr><tr><td width="30">&nbsp;</td><td><table><tr><td width="10" align="right"></td><td>'.$pg['pg_missao'].'</td></tr></table></td></tr></table></td></tr>';
	if ($pg['pg_missao_esc_superior']) echo '<tr><td width="30" align="right"></td><td><table><tr><td>'.$letras[++$letra].'</td><td width="100%"><font size=2>MISSÃO DO ESCALÃO SUPERIOR</font></td></tr><tr><td width="30">&nbsp;</td><td><table><tr><td width="10" align="right"></td><td>'.$pg['pg_missao_esc_superior'].'</td></tr></table></td></tr></table></td></tr>';
	if (tem_anexo('Missao')) echo imprimir_anexo('Missao');
	}


//visao futuro
if (tem_anexo('Visao') ||$pg['pg_visao_futuro'] || $pg['pg_visao_futuro_detalhada']) {
	$letra=0;
	$romano=0;
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. VISÃO DE FUTURO</b></font></td></tr>';
	if ($pg['pg_visao_futuro']) echo '<tr><td width="30" align="right"></td><td><table><tr><td>'.$letras[++$letra].'</td><td width="100%"><font size=2>VISÃO DE FUTIRO DA '.strtoupper($config['organizacao']).'</font></td></tr><tr><td width="30">&nbsp;</td><td><table><tr><td width="10" align="right"></td><td>'.$pg['pg_visao_futuro'].'</td></tr></table></td></tr></table></td></tr>';
	if ($pg['pg_visao_futuro_detalhada']) echo '<tr><td width="30" align="right"></td><td><table><tr><td>'.$letras[++$letra].'</td><td width="100%"><font size=2>DETALHAMENTO DA VISÃO DE FUTIRO</font></td></tr><tr><td width="30">&nbsp;</td><td><table><tr><td width="10" align="right"></td><td>'.$pg['pg_visao_futuro_detalhada'].'</td></tr></table></td></tr></table></td></tr>';
	if (tem_anexo('Visao')) echo imprimir_anexo('Visao');
	}



//PONTO FORTE
if (tem_tabela('plano_gestao_pontosfortes','pg_ponto_forte_id','pg_ponto_forte_pg_id') || tem_anexo('PontoForte') || $pg['pg_ponto_forte']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. FORÇAS</b></font></td></tr>';
	if ($pg['pg_ponto_forte']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_ponto_forte'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('plano_gestao_pontosfortes');
	$sql->adCampo('pg_ponto_forte_nome');
	$sql->adOnde('pg_ponto_forte_pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_ponto_forte_ordem ASC');
	$pontos_fortes=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($pontos_fortes && count($pontos_fortes)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;'.(count($pontos_fortes)>1 ? 'Forças':'Força').'&nbsp;</th></tr>';
		foreach ($pontos_fortes as $ponto_forte) $saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$ponto_forte['pg_ponto_forte_nome'].'</td></tr>';
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('PontoForte')) echo imprimir_anexo('PontoForte');
	}


//OPORTUNIDADE DE MELHORIA
if (tem_tabela('plano_gestao_oportunidade_melhorias','pg_oportunidade_melhoria_id','pg_oportunidade_melhoria_pg_id') || tem_anexo('OportunidadeMelhoria') || $pg['pg_oportunidade_melhoria']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. OPORTUNIDADES</b></font></td></tr>';
	if ($pg['pg_oportunidade_melhoria']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_oportunidade_melhoria'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('plano_gestao_oportunidade_melhorias');
	$sql->adCampo('pg_oportunidade_melhoria_nome');
	$sql->adOnde('pg_oportunidade_melhoria_pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_oportunidade_melhoria_ordem ASC');
	$resultados=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($resultados && count($resultados)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;'.(count($resultados)>1 ? 'Oportunidades':'Oportunidade').'&nbsp;</th></tr>';
		foreach ($resultados as $resultado) $saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$resultado['pg_oportunidade_melhoria_nome'].'</td></tr>';
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('OportunidadeMelhoria')) echo imprimir_anexo('OportunidadeMelhoria');
	}



//OPORTUNIDADES EXTERNAS
if (tem_tabela('plano_gestao_oportunidade','pg_oportunidade_id','pg_oportunidade_pg_id') || tem_anexo('Oportunidade') || $pg['pg_oportunidade_melhoria']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. OPORTUNIDADES</b></font></td></tr>';
	if ($pg['pg_oportunidade']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_oportunidade'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('plano_gestao_oportunidade');
	$sql->adCampo('pg_oportunidade_nome');
	$sql->adOnde('pg_oportunidade_pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_oportunidade_ordem ASC');
	$resultados=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($resultados && count($resultados)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;'.(count($resultados)>1 ? 'Oportunidades':'Oportunidade').'&nbsp;</th></tr>';
		foreach ($resultados as $resultado) $saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$resultado['pg_oportunidade_nome'].'</td></tr>';
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('Oportunidade')) echo imprimir_anexo('Oportunidade');
	}


//AMEAÇAS EXTERNAS
if (tem_tabela('plano_gestao_ameacas','pg_ameaca_id','pg_ameaca_pg_id') || tem_anexo('Ameaca') || $pg['pg_ameaca']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. AMEAÇAS</b></font></td></tr>';
	if ($pg['pg_ameaca']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_ameaca'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('plano_gestao_ameacas');
	$sql->adCampo('pg_ameaca_nome');
	$sql->adOnde('pg_ameaca_pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_ameaca_ordem ASC');
	$resultados=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($resultados && count($resultados)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;'.(count($resultados)>1 ? 'Ameaças':'Ameaça').'&nbsp;</th></tr>';
		foreach ($resultados as $resultado) $saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$resultado['pg_ameaca_nome'].'</td></tr>';
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('Ameaca')) echo imprimir_anexo('Ameaca');
	}


//PRINCIPIOS, CRENÇAS E VALORES
if (tem_tabela('plano_gestao_principios','pg_principio_id','pg_principio_pg_id') || tem_anexo('Principio') || $pg['pg_principio']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. PRINCÍPIOS, CRENÇAS E VALORES ORGANIZACIONAIS</b></font></td></tr>';
	if ($pg['pg_principio']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_principio'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('plano_gestao_principios');
	$sql->adCampo('pg_principio_nome');
	$sql->adOnde('pg_principio_pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_principio_ordem ASC');
	$resultados=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($resultados && count($resultados)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;'.(count($resultados)>1 ? 'Princípios, Crenças e Valores':'Princípio, Crença ou Valor').'&nbsp;</th></tr>';
		foreach ($resultados as $resultado) $saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$resultado['pg_principio_nome'].'</td></tr>';
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('Principio')) echo imprimir_anexo('Principio');
	}


//DIRETRIZES DO ESCALÃO SUPERIOR
if (tem_tabela('plano_gestao_diretrizes_superiores','pg_diretriz_superior_id','pg_diretriz_superior_pg_id') || tem_anexo('DiretrizesSuperiores') || $pg['pg_diretriz_superior']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. DIRETRIZES DO ESCALÃO SUPERIOR</b></font></td></tr>';
	if ($pg['pg_diretriz_superior']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_diretriz_superior'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('plano_gestao_diretrizes_superiores');
	$sql->adCampo('pg_diretriz_superior_nome');
	$sql->adOnde('pg_diretriz_superior_pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_diretriz_superior_ordem ASC');
	$resultados=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($resultados && count($resultados)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;'.(count($resultados)>1 ? 'Diretrizes':'Diretriz').'&nbsp;</th></tr>';
		foreach ($resultados as $resultado) $saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$resultado['pg_diretriz_superior_nome'].'</td></tr>';
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('DiretrizesSuperiores')) echo imprimir_anexo('DiretrizesSuperiores');
	}



//DIRETRIZES
if (tem_tabela('plano_gestao_diretrizes','pg_diretriz_id','pg_diretriz_pg_id') || tem_anexo('Diretrizes') || $pg['pg_diretriz']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. DIRETRIZES DA ORGANIZAÇÃO</b></font></td></tr>';
	if ($pg['pg_diretriz']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_diretriz'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('plano_gestao_diretrizes');
	$sql->adCampo('pg_diretriz_nome');
	$sql->adOnde('pg_diretriz_pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_diretriz_ordem ASC');
	$resultados=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($resultados && count($resultados)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;'.(count($resultados)>1 ? 'Diretrizes':'Diretriz').'&nbsp;</th></tr>';
		foreach ($resultados as $resultado) $saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$resultado['pg_diretriz_nome'].'</td></tr>';
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('Diretrizes')) echo imprimir_anexo('Diretrizes');
	}


//OBJETIVOS ESTRATÉGICOS
if (tem_tabela('plano_gestao_objetivos_estrategicos','pg_objetivo_estrategico_id','pg_id') || tem_anexo('ObjEstrategicos') || $pg['pg_objetivo_estrategico']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. '.strtoupper($config['objetivos']).'</b></font></td></tr>';
	if ($pg['pg_objetivo_estrategico']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_objetivo_estrategico'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('objetivos_estrategicos');
	$sql->esqUnir('plano_gestao_objetivos_estrategicos','plano_gestao_objetivos_estrategicos','plano_gestao_objetivos_estrategicos.pg_objetivo_estrategico_id=objetivos_estrategicos.pg_objetivo_estrategico_id');
	$sql->adCampo('pg_objetivo_estrategico_nome');
	$sql->adOnde('pg_id='.(int)$pg_id);
	$sql->adOrdem('plano_gestao_objetivos_estrategicos.pg_objetivo_estrategico_ordem ASC');
	$resultados=$sql->Lista();
	$sql->limpar();
	$saida='';
	if ($resultados && count($resultados)) {
		$saida.='<table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;'.(count($resultados)>1 ? ucfirst($config['objetivos']):ucfirst($config['objetivo'])).'&nbsp;</th></tr>';
		foreach ($resultados as $resultado) $saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$resultado['pg_objetivo_estrategico_nome'].'</td></tr>';
		$saida.= '</table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('ObjEstrategicos')) echo imprimir_anexo('ObjEstrategicos');
	}
	

//FATORES CRÍTICOS PARA O SUCESSO
if (tem_tabela('plano_gestao_fatores_criticos','pg_fator_critico_id','pg_id') || tem_anexo('FatorCritico') || $pg['pg_fator_critico']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. '.strtoupper($config['fatores']).'</b></font></td></tr>';
	if ($pg['pg_fator_critico']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_fator_critico'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('fatores_criticos');
	$sql->esqUnir('plano_gestao_fatores_criticos','plano_gestao_fatores_criticos','plano_gestao_fatores_criticos.pg_fator_critico_id=fatores_criticos.pg_fator_critico_id');
	$sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','objetivos_estrategicos.pg_objetivo_estrategico_id=fatores_criticos.pg_fator_critico_objetivo');
	$sql->adCampo('pg_objetivo_estrategico_nome, pg_fator_critico_nome');
	$sql->adOnde('plano_gestao_fatores_criticos.pg_id='.(int)$pg_id);
	$sql->adOrdem('objetivos_estrategicos.pg_objetivo_estrategico_id, plano_gestao_fatores_criticos.pg_fator_critico_ordem ASC');
	$resultados=$sql->Lista();
	$sql->limpar();
	$saida='';
	$estrategia='';
	$qnt=0;
	if ($resultados && count($resultados)) {
		$saida='<table>';
		foreach ($resultados as $resultado) {
			if ($resultado['pg_objetivo_estrategico_nome'] !=$estrategia){
				$estrategia=$resultado['pg_objetivo_estrategico_nome'];
				$saida.= ($qnt++ ? '</table></td></tr><tr><td colspan=20></td></tr>' : '').'<tr><td>'.$letras[$qnt].'&nbsp;'.$resultado['pg_objetivo_estrategico_nome'].'</td></tr><tr><td><table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;Fatores Críticos&nbsp;</th></tr>';
				}
			$saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$resultado['pg_fator_critico_nome'].'</td></tr>';
			}
		$saida.= '</table></td></tr></table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('FatorCritico')) echo imprimir_anexo('FatorCritico');
	}
	



//ESTRATÉGIAS PARA A CONSECUÇÃO DOS OBJETIVOS ESTRATÉGICOS ORGANIZACIONAIS
if (tem_tabela('plano_gestao_estrategias','pg_estrategia_id','pg_id') || tem_anexo('Estrategia') || $pg['pg_estrategia']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. '.strtoupper($config['iniciativas']).' PARA A CONSECUÇÃO D'.strtoupper($config['genero_objetivo']).'S '.strtoupper($config['objetivos']).'</b></font></td></tr>';
	if ($pg['pg_estrategia']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_estrategia'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('estrategias');
	$sql->esqUnir('plano_gestao_estrategias','plano_gestao_estrategias','plano_gestao_estrategias.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->esqUnir('fatores_criticos','fatores_criticos','fatores_criticos.pg_fator_critico_id=estrategias.pg_estrategia_fator');
	$sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','objetivos_estrategicos.pg_objetivo_estrategico_id=fatores_criticos.pg_fator_critico_objetivo');
	$sql->adCampo('pg_objetivo_estrategico_nome, pg_estrategia_nome');
	$sql->adOnde('plano_gestao_estrategias.pg_id='.(int)$pg_id);
	$sql->adOrdem('objetivos_estrategicos.pg_objetivo_estrategico_id, plano_gestao_estrategias.pg_estrategia_ordem ASC');

	$resultados=$sql->Lista();
	$sql->limpar();
	$saida='';
	$estrategia='';
	$qnt=0;
	if ($resultados && count($resultados)) {
		$saida='<table>';
		foreach ($resultados as $resultado) {
			if ($resultado['pg_objetivo_estrategico_nome'] !=$estrategia){
				$estrategia=$resultado['pg_objetivo_estrategico_nome'];
				$saida.= ($qnt++ ? '</table></td></tr><tr><td colspan=20></td></tr>' : '').'<tr><td>'.$letras[$qnt].'&nbsp;'.$resultado['pg_objetivo_estrategico_nome'].'</td></tr><tr><td><table class="tbl1" cellspacing=0 cellpadding="2" border=0><tr><th>&nbsp;Iniciativas&nbsp;</th></tr>';
				}
			$saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$resultado['pg_estrategia_nome'].'</td></tr>';
			}
		$saida.= '</table></td></tr></table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';		
	if (tem_anexo('Estrategia')) echo imprimir_anexo('Estrategia');
	}




//METAS ORGANIZACIONAIS
if (tem_tabela('plano_gestao_metas','pg_meta_id','pg_id') || tem_anexo('Meta') || $pg['pg_meta']) {
	echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. METAS ORGANIZACIONAIS</b></font></td></tr>';
	if ($pg['pg_meta']) echo '<tr><td width="30">&nbsp;</td><td><font size=2>'.$pg['pg_meta'].'</font></td></tr>';	
	//tabela
	$sql->adTabela('metas');
	$sql->esqUnir('plano_gestao_metas','plano_gestao_metas','plano_gestao_metas.pg_meta_id=metas.pg_meta_id');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=metas.pg_meta_responsavel');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('metas.pg_meta_id, pg_meta_nome, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, pg_meta_prazo');
	$sql->adOnde('pg_id='.(int)$pg_id);
	$sql->adOrdem('plano_gestao_metas.pg_meta_ordem ASC');
	$resultados=$sql->Lista();
	$sql->limpar();

	$vetor_indicadores=array();
	$saida='';
	$qnt=0;
	$qnt_indicadores=0;
	if ($resultados && count($resultados)) {
		$saida='<table>';
		foreach ($resultados as $resultado) {
			$saida.= '<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.$resultado['pg_meta_nome'].'</td><td>'.($resultado['nome_usuario'] ? $resultado['nome_usuario']  : '&nbsp;').'</td><td>'.retorna_data($resultado['pg_meta_prazo'], false).'</td></tr>';
			$sql->adTabela('projetos');
			$sql->adCampo('projeto_nome');
			$sql->adOnde('projeto_meta='.(int)$resultado['pg_meta_id']);
			$lista_projetos=$sql->Lista();
			$sql->limpar();

			$texto_projeto='';
			foreach($lista_projetos as $unico_projeto) $texto_projeto.=($texto_projeto ? '<br>' :'').$unico_projeto['projeto_nome'];
			if ($texto_projeto) $saida.= '<tr><td colspan=20><table cellpadding=0 cellspacing=0 class="tbl5"><tr><td valign=top><b>Projeto'.(count($lista_projetos) > 1 ? 's' :'').':&nbsp;</b></td><td>'.$texto_projeto.'</td></tr></table></td></tr>';
			
			
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
			$sql->adOnde('pratica_indicador_meta='.(int)$resultado['pg_meta_id']);
			$lista_indicadores=$sql->Lista();
			$sql->limpar();
			
			
			$texto_indicador='';
			foreach($lista_indicadores as $unico_indicador) $texto_indicador.=($texto_indicador ? '<br>' :'').$unico_indicador['pratica_indicador_nome'];
			if ($texto_projeto) $saida.= '<tr><td colspan=20><table cellpadding=0 cellspacing=0 class="tbl5"><tr><td valign=top><b>Indicador'.(count($lista_indicadores) > 1 ? 'es' :'').':&nbsp;</b><td><td>'.$texto_indicador.'</td></tr></table></td></tr>';
			
			
			
			foreach($lista_indicadores as $unico_indicador) $vetor_indicadores[$unico_indicador['pratica_indicador_id']]=$unico_indicador['pratica_indicador_id'];
			if (count($lista_indicadores)) $qnt_indicadores=$qnt_indicadores+count($lista_indicadores);
			
			}
		$saida.= '</table></td></tr></table>';
		}
	if ($saida) echo '<tr><td width="30">&nbsp;</td><td>'.$saida.'</td></tr>';	
	
	
	if ($qnt_indicadores) echo '<tr><td width="30">&nbsp;</td><td>'.$letras[++$qnt].' Indicador'.($qnt_indicadores > 1 ? 'es' : '').'</td></tr>';

	foreach ($vetor_indicadores as $chave => $valor) {
		if ($valor && !isset($indicadores_vistos[$valor])){
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_unidade, pratica_indicador_cor, pratica_indicador_nome, pratica_indicador_tipografico, pratica_indicador_agrupar, pratica_indicador_mostrar_valor, pratica_indicador_mostrar_titulo, pratica_indicador_media_movel');
			$sql->adOnde('pratica_indicador_id='.(int)$valor);
			$pratica_indicador=$sql->Linha();
			$sql->limpar();
			$indicadores_vistos[$valor]=++$numero_indicadores;
			$indicadores_nomes[$valor]=$pratica_indicador['pratica_indicador_nome'];
			$indicadores_posicao[$valor]='Indicador '.$indicadores_vistos[$valor].' '.$numero.' '.$letras[$qnt];
			$src = '?m=praticas&a=grafico_free&sem_cabecalho=1&ano='.(int)$ano.'&mostrar_valor='.(int)$pratica_indicador['pratica_indicador_mostrar_valor'].'&mostrar_titulo='.(int)$pratica_indicador['pratica_indicador_mostrar_titulo'].'&media_movel='.(int)$pratica_indicador['pratica_indicador_media_movel'].'&agrupar='.(int)$pratica_indicador['pratica_indicador_agrupar'].'&tipografico='.(int)$pratica_indicador['pratica_indicador_tipografico'].'&pratica_indicador_id='.(int)$valor."&width=750";
			echo "<tr><td width='30'>&nbsp;</td><td><table cellspacing='0' cellpadding='0' align='left'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr><tr><td align='center'>Indicador ".$indicadores_vistos[$valor]."</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table></td></tr>";
			}
		}
		
	if (tem_anexo('Meta')) echo imprimir_anexo('Meta');
	}

	
	

$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
$sql->adOnde('pratica_criterio_modelo='.(int)$pg_modelo_id);
$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
$sql->limpar();


$sql->adTabela('pratica_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs, pratica_item_oculto');
$sql->adOnde('pratica_criterio_modelo='.(int)$pg_modelo_id);
$itens=$sql->ListaChaveSimples('pratica_item_id');
$sql->limpar();


$sql->adTabela('pratica_marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
$sql->adOnde('pratica_criterio_modelo='.(int)$pg_modelo_id);
$sql->adOrdem('pratica_criterio_numero');
$sql->adOrdem('pratica_item_numero');
$sql->adOrdem('pratica_marcador_letra');
$marcadores=$sql->Lista();
$sql->limpar();

$criterio_atual='';
$item_atual='';


echo '<tr><td colspan=2 align="left"><font size=3><b>'.++$numero.'. '.strtoupper($config['relatorio_gestao']).'</b></font></td></tr>';
echo '<tr><td width="30">&nbsp;</td><td><table cellpadding="2" cellspacing=0 width="800">';
foreach($marcadores as $dado){
	if ($dado['pratica_criterio_id']!=$criterio_atual){
		if ($criterio_atual) echo '</table></td></tr>';
		$criterio_atual=$dado['pratica_criterio_id'];
		echo '<tr><td align="left" colspan=2 nowrap="nowrap"><b>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</b></td></tr>';
		echo '<tr id="criterio_'.$criterio_atual.'"><td colspan=2><table cellpadding="2" cellspacing=0 width="100%">';
		}
		
	if ($dado['pratica_item_id']!=$item_atual){
		$item_atual=$dado['pratica_item_id'];
		if (!$itens[$dado['pratica_item_id']]['pratica_item_oculto']) echo '<tr><td align="left" colspan=2 nowrap="nowrap"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].'</b></td></tr>';
		}
	
	echo '<tr><td align="left" nowrap="nowrap" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$dado['pratica_marcador_letra'].'.&nbsp;</b></td><td id="caixa_'.$dado['pratica_marcador_id'].'" width="100%" valign="middle">'.($dado['pratica_marcador_extra'] ? dica('Informações Extras', $dado['pratica_marcador_extra']).$dado['pratica_marcador_texto'].dicaF() : $dado['pratica_marcador_texto']).'</td></tr>';
	if ($dado['pratica_marcador_extra']) echo '<tr><td align="left" nowrap="nowrap" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="100%" valign="left">'.$dado['pratica_marcador_extra'].'</td></tr>';
	
	$resultadoPraticas=imprimir_praticas($dado['pratica_marcador_id']);
	if ($resultadoPraticas) echo '<tr><td width="30">&nbsp;</td><td>'.$resultadoPraticas.'</td></tr>';
	
	$resultadoIndicadores=imprimir_indicador($dado['pratica_marcador_id']);
	if ($resultadoIndicadores) echo '<tr><td width="30">&nbsp;</td><td>'.$resultadoIndicadores.'</td></tr>';
	}
if ($criterio_atual) echo '</table>';	
echo '</table>';
echo '</td></tr>';

echo '</table>';
	

?>