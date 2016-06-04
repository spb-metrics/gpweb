<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);


function atualizar_arquivo($evento_id){
	$sql = new BDConsulta;
	$saida='<table cellpadding=0 cellspacing=0>';
	//arquivo anexo
	$sql->adTabela('evento_arquivos');
	$sql->adCampo('evento_arquivo_id, evento_arquivo_usuario, evento_arquivo_data, evento_arquivo_ordem, evento_arquivo_nome, evento_arquivo_endereco');
	$sql->adOnde('evento_arquivo_evento_id='.(int)$evento_id);
	$sql->adOrdem('evento_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	if ($arquivos && count($arquivos))$saida.= '<tr><td colspan=2>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</td></tr>';
	foreach ($arquivos as $arquivo) {
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120">Remetente</td><td>'.nome_funcao('', '', '', '',$arquivo['evento_arquivo_usuario']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;">Anexado em</td><td>'.retorna_data($arquivo['evento_arquivo_data']).'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique neste link para fazer o download do arquivo ou visualizar o mesmo.';
		$saida.= '<tr><td nowrap="nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['evento_arquivo_ordem'].', '.$arquivo['evento_arquivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['evento_arquivo_ordem'].', '.$arquivo['evento_arquivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['evento_arquivo_ordem'].', '.$arquivo['evento_arquivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['evento_arquivo_ordem'].', '.$arquivo['evento_arquivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
		$saida.= '<td><a href="javascript:void(0);" onclick="javascript:env.a.value=\'download_evento\'; env.u.value=\'\'; env.sem_cabecalho.value=1; env.evento_arquivo_id.value='.(int)$arquivo['evento_arquivo_id'].'; env.submit();">'.dica($arquivo['evento_arquivo_nome'],$dentro).$arquivo['evento_arquivo_nome'].'</a></td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {excluir_arquivo('.$arquivo['evento_arquivo_id'].');}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste ícone para excluir o arquivo.').'</a></td>';
		$saida.='</tr>';
		}
	$saida.='</table>';	
	return $saida;
	}
$xajax->registerFunction("atualizar_arquivo");

function mudar_posicao_arquivo($ordem, $evento_arquivo_id, $direcao, $evento_id=0){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $evento_arquivo_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('evento_arquivos');
		$sql->adOnde('evento_arquivo_id != '.(int)$evento_arquivo_id);
		$sql->adOnde('evento_arquivo_evento_id = '.(int)$evento_id);
		$sql->adOrdem('evento_arquivo_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('evento_arquivos');
			$sql->adAtualizar('evento_arquivo_ordem', $novo_ui_ordem);
			$sql->adOnde('evento_arquivo_id = '.(int)$evento_arquivo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('evento_arquivos');
					$sql->adAtualizar('evento_arquivo_ordem', $idx);
					$sql->adOnde('evento_arquivo_id = '.(int)$acao['evento_arquivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('evento_arquivos');
					$sql->adAtualizar('evento_arquivo_ordem', $idx + 1);
					$sql->adOnde('evento_arquivo_id = '.(int)$acao['evento_arquivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_arquivo($evento_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_arquivo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_arquivo");



function excluir_arquivo($evento_id=null, $evento_arquivo_id=null){
	global $Aplic, $config;
	
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	
	$sql = new BDConsulta;
	$sql->adTabela('evento_arquivos');
	$sql->adCampo('evento_arquivo_endereco');
	$sql->adOnde('evento_arquivo_id='.(int)$evento_arquivo_id);
	$caminho=$sql->Resultado();
	$sql->limpar();
	@unlink($base_dir.'/arquivos/eventos/'.$caminho);
	$sql->setExcluir('evento_arquivos');
	$sql->adOnde('evento_arquivo_id='.(int)$evento_arquivo_id);
	$sql->exec();
	$sql->limpar();	
	
	
	$saida=atualizar_arquivo($evento_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_arquivo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("excluir_arquivo");




if ($Aplic->profissional) include_once BASE_DIR.'/modulos/calendario/editar_ajax_pro.php';

function exibir_depts($depts){
	global $config;
	$depts_selecionados=explode(',', $depts);
	$saida_depts='';
	if (count($depts_selecionados)) {
			$saida_depts.= '<table cellpadding=0 cellspacing=0>';
			$saida_depts.= '<tr><td class="texto" style="width:400px;">'.link_secao($depts_selecionados[0]);
			$qnt_lista_depts=count($depts_selecionados);
			if ($qnt_lista_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';		
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
					}
			$saida_depts.= '</td></tr></table>';
			} 
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts");

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");

function mudar_usuario_ajax($cia_id=0, $usuario_id=0, $campo='', $posicao='', $script='', $segunda_tabela='', $condicao=''){
	global $Aplic;
	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$saida=mudar_usuario_em_dept(true, $cia_id, 0, $campo, $posicao, $script);
	$objResposta = New xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_ajax");	
	
	
function mudar_usuario_pesquisa_ajax($pesquisa=''){
	global $Aplic, $config;
	$sql = new BDConsulta;
	
	$pesquisa=previnirXSS(utf8_decode($pesquisa));
	
	$sql->adTabela('grupo');
	$sql->esqUnir('grupo_permissao','gp1','gp1.grupo_id = grupo.grupo_id');
	$sql->esqUnir('grupo_permissao','gp2','gp2.grupo_id=grupo.grupo_id AND gp2.usuario_id = '.$Aplic->usuario_id);
	$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia');
	$sql->adCampo('COUNT(gp1.usuario_id) AS protegido');
	$sql->adCampo('COUNT(gp2.usuario_id) AS pertence');
	$sql->adOnde('grupo_usuario IS NULL');
	$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
	$sql->adOrdem('grupo_descricao ASC');
	$sql->adGrupo('grupo.grupo_id, grupo_descricao, grupo_cia');
	$achados=$sql->Lista();
	$sql->limpar();
	
	$grupos=array();
	$tem_protegido=0;
	foreach($achados as $linha) {
		if ($linha['protegido']) $tem_protegido=1;
		if ($linha['grupo_id'] && (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence'])))$grupos[$linha['grupo_id']]=$linha['grupo_id'];
	}
	
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
	$sql->esqUnir('chaves_publicas','chaves_publicas','chave_publica_usuario = usuarios.usuario_id');
	$sql->adCampo('MAX( chave_publica_id ) AS chave_publica_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo=1');	
	$sql->adOnde('usuariogrupo.grupo_id IN ('.implode(',',$grupos).')'.(!$tem_protegido ? ' OR contato_cia='.(int)$Aplic->usuario_cia : ''));

  if ($pesquisa) $sql->adOnde('UPPER( contato_nomeguerra ) LIKE \'%'.strtoupper($pesquisa).'%\' OR UPPER( contato_nomecompleto ) LIKE \'%'.strtoupper($pesquisa).'%\' OR UPPER( contato_funcao ) LIKE \'%'.strtoupper($pesquisa).'%\'');	
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$sql->adGrupo('contato_posto, contato_nomeguerra, nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');


	$usuarios = $sql->Lista();
	$sql->limpar();
	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) {
 		$nome=nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '');
 		$saida.='<option value="'.$rs['usuario_id'].'" style="color: '.($rs['chave_publica_id']? 'blue': 'black').';">'.utf8_encode($nome).'</option>';
	}
	
	$saida.='</select>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_pesquisa_ajax");	
	
	
function responsavel_ajax($tipo='projeto', $id=0, $evento_id=null, $uuid=null) {
	global $Aplic, $config;
	$sql = new BDConsulta;
	
	if ($Aplic->profissional){
		$sql->adTabela('evento_gestao');
		$sql->adCampo('evento_gestao.*');
		if ($evento_id) $sql->adOnde('evento_gestao_evento ='.(int)$evento_id);	
		else $sql->adOnde('evento_gestao_uuid =\''.$uuid.'\'');	
	  $lista = $sql->Lista();
	  $sql->Limpar();
		$responsavel=array();
		foreach($lista as $linha){
			if ($linha['evento_gestao_tarefa']){
				$sql->adTabela('tarefas');
				$sql->adCampo('tarefa_dono');
				$sql->adOnde('tarefa_id = '.(int)$linha['evento_gestao_tarefa']);
				}
			elseif ($linha['evento_gestao_projeto']){
				$sql->adTabela('projetos');
				$sql->adCampo('projeto_responsavel');
				$sql->adOnde('projeto_id = '.(int)$linha['evento_gestao_projeto']);
				}	
			elseif ($linha['evento_gestao_perspectiva']){
				$sql->adTabela('perspectivas');
				$sql->adCampo('pg_perspectiva_usuario');
				$sql->adOnde('pg_perspectiva_id = '.(int)$linha['evento_gestao_perspectiva']);
				}	
			elseif ($linha['evento_gestao_tema']){
				$sql->adTabela('tema');
				$sql->adCampo('tema_usuario');
				$sql->adOnde('tema_id = '.(int)$linha['evento_gestao_tema']);
				}	
			elseif ($linha['evento_gestao_objetivo']){
				$sql->adTabela('objetivos_estrategicos');
				$sql->adCampo('pg_objetivo_estrategico_usuario');
				$sql->adOnde('pg_objetivo_estrategico_id = '.(int)$linha['evento_gestao_objetivo']);
				}	
			elseif ($linha['evento_gestao_fator']){
				$sql->adTabela('fatores_criticos');
				$sql->adCampo('pg_fator_critico_usuario');
				$sql->adOnde('pg_fator_critico_id = '.(int)$linha['evento_gestao_fator']);
				}		
				elseif ($linha['evento_gestao_estrategia']){
				$sql->adTabela('estrategias');
				$sql->adCampo('pg_estrategia_usuario');
				$sql->adOnde('pg_estrategia_id = '.(int)$linha['evento_gestao_estrategia']);
				}	
			elseif ($linha['evento_gestao_meta']){
				$sql->adTabela('metas');
				$sql->adCampo('pg_meta_responsavel');
				$sql->adOnde('pg_meta_id = '.(int)$linha['evento_gestao_meta']);
				}			
			elseif ($linha['evento_gestao_pratica']){
				$sql->adTabela('praticas');
				$sql->adCampo('pratica_responsavel');
				$sql->adOnde('pratica_id = '.(int)$linha['evento_gestao_pratica']);
				}
			elseif ($linha['evento_gestao_indicador']){
				$sql->adTabela('pratica_indicador');
				$sql->adCampo('pratica_indicador_responsavel');
				$sql->adOnde('pratica_indicador_id = '.(int)$linha['evento_gestao_indicador']);
				}
			elseif ($linha['evento_gestao_acao']){
				$sql->adTabela('plano_acao');
				$sql->adCampo('plano_acao_responsavel');
				$sql->adOnde('plano_acao_id = '.(int)$linha['evento_gestao_acao']);
				}
			elseif ($linha['evento_gestao_canvas']){
				$sql->adTabela('canvas');
				$sql->adCampo('canvas_usuario');
				$sql->adOnde('canvas_id = '.(int)$linha['evento_gestao_canvas']);
				}
			elseif ($linha['evento_gestao_risco']){
				$sql->adTabela('risco');
				$sql->adCampo('risco_usuario');
				$sql->adOnde('risco_id = '.(int)$linha['evento_gestao_risco']);
				}		
			elseif ($linha['evento_gestao_risco_resposta']){
				$sql->adTabela('risco_resposta');
				$sql->adCampo('risco_resposta_usuario');
				$sql->adOnde('risco_resposta_id = '.(int)$linha['evento_gestao_risco_resposta']);
				}
			elseif ($linha['evento_gestao_calendario']){
				$sql->adTabela('calendario');
				$sql->adCampo('calendario_usuario');
				$sql->adOnde('calendario_id = '.(int)$linha['evento_gestao_calendario']);
				}	
			elseif ($linha['evento_gestao_monitoramento']){
				$sql->adTabela('monitoramento');
				$sql->adCampo('monitoramento_usuario');
				$sql->adOnde('monitoramento_id = '.(int)$linha['evento_gestao_monitoramento']);
				}
			elseif ($linha['evento_gestao_ata']){
				$sql->adTabela('ata');
				$sql->adCampo('ata_responsavel');
				$sql->adOnde('ata_id = '.(int)$linha['evento_gestao_ata']);
				}		
			elseif ($linha['evento_gestao_swot']){
				$sql->adTabela('swot');
				$sql->adCampo('swot_responsavel');
				$sql->adOnde('swot_id = '.(int)$linha['evento_gestao_swot']);
				}
			elseif ($linha['evento_gestao_operativo']){
				$sql->adTabela('operativo');
				$sql->adCampo('operativo_usuario');
				$sql->adOnde('operativo_id = '.(int)$linha['evento_gestao_operativo']);
				}		
			elseif ($linha['evento_gestao_instrumento']){
				$sql->adTabela('instrumento');
				$sql->adCampo('instrumento_responsavel');
				$sql->adOnde('instrumento_id = '.(int)$linha['evento_gestao_instrumento']);
				}
			elseif ($linha['evento_gestao_recurso']){
				$sql->adTabela('recursos');
				$sql->adCampo('recurso_responsavel');
				$sql->adOnde('recurso_id = '.(int)$linha['evento_gestao_recurso']);
				}		
			elseif ($linha['evento_gestao_problema']){
				$sql->adTabela('problema');
				$sql->adCampo('problema_responsavel');
				$sql->adOnde('problema_id = '.(int)$linha['evento_gestao_problema']);
				}
			elseif ($linha['evento_gestao_demanda']){
				$sql->adTabela('demandas');
				$sql->adCampo('demanda_usuario');
				$sql->adOnde('demanda_id = '.(int)$linha['evento_gestao_demanda']);
				}		
			elseif ($linha['evento_gestao_programa']){
				$sql->adTabela('programa');
				$sql->adCampo('programa_usuario');
				$sql->adOnde('programa_id = '.(int)$linha['evento_gestao_programa']);
				}
			elseif ($linha['evento_gestao_licao']){
				$sql->adTabela('licao');
				$sql->adCampo('licao_responsavel');
				$sql->adOnde('licao_id = '.(int)$linha['evento_gestao_licao']);
				}		
			elseif ($linha['evento_gestao_link']){
				$sql->adTabela('links');
				$sql->adCampo('link_dono');
				$sql->adOnde('link_id = '.(int)$linha['evento_gestao_link']);
				}
			elseif ($linha['evento_gestao_avaliacao']){
				$sql->adTabela('avaliacao');
				$sql->adCampo('avaliacao_responsavel');
				$sql->adOnde('avaliacao_id = '.(int)$linha['evento_gestao_avaliacao']);
				}		
			elseif ($linha['evento_gestao_tgn']){
				$sql->adTabela('tgn');
				$sql->adCampo('tgn_usuario');
				$sql->adOnde('tgn_id = '.(int)$linha['evento_gestao_tgn']);
				}
			elseif ($linha['evento_gestao_brainstorm']){
				$sql->adTabela('brainstorm');
				$sql->adCampo('brainstorm_responsavel');
				$sql->adOnde('brainstorm_id = '.(int)$linha['evento_gestao_brainstorm']);
				}		
			elseif ($linha['evento_gestao_gut']){
				$sql->adTabela('gut');
				$sql->adCampo('gut_responsavel');
				$sql->adOnde('gut_id = '.(int)$linha['evento_gestao_gut']);
				}		
			elseif ($linha['evento_gestao_causa_efeito']){
				$sql->adTabela('causa_efeito');
				$sql->adCampo('causa_efeito_responsavel');
				$sql->adOnde('causa_efeito_id = '.(int)$linha['evento_gestao_causa_efeito']);
				}		
			elseif ($linha['evento_gestao_arquivo']){
				$sql->adTabela('arquivos');
				$sql->adCampo('arquivo_dono');
				$sql->adOnde('arquivo_id = '.(int)$linha['evento_gestao_arquivo']);
				}		
			elseif ($linha['evento_gestao_forum']){
				$sql->adTabela('foruns');
				$sql->adCampo('forum_dono');
				$sql->adOnde('forum_id = '.(int)$linha['evento_gestao_forum']);
				}		
			elseif ($linha['evento_gestao_checklist']){
				$sql->adTabela('checklist');
				$sql->adCampo('checklist_responsavel');
				$sql->adOnde('checklist_id = '.(int)$linha['evento_gestao_checklist']);
				}		
			elseif ($linha['evento_gestao_agenda']){
				$sql->adTabela('agenda');
				$sql->adCampo('agenda_dono');
				$sql->adOnde('agenda_id = '.(int)$linha['evento_gestao_agenda']);
				}																																																			
			elseif ($linha['evento_gestao_agrupamento']){
				$sql->adTabela('agrupamento');
				$sql->adCampo('agrupamento_usuario');
				$sql->adOnde('agrupamento_id = '.(int)$linha['evento_gestao_agrupamento']);
				}		
			elseif ($linha['evento_gestao_patrocinador']){
				$sql->adTabela('patrocinadores');
				$sql->adCampo('patrocinador_responsavel');
				$sql->adOnde('patrocinador_id = '.(int)$linha['evento_gestao_patrocinador']);
				}		
			elseif ($linha['evento_gestao_template']){
				$sql->adTabela('template');
				$sql->adCampo('template_responsavel');
				$sql->adOnde('template_id = '.(int)$linha['evento_gestao_template']);
				}		
			elseif ($linha['evento_gestao_painel']){
				$sql->adTabela('painel');
				$sql->adCampo('painel_responsavel');
				$sql->adOnde('painel_id = '.(int)$linha['evento_gestao_painel']);
				}		
			elseif ($linha['evento_gestao_painel_odometro']){
				$sql->adTabela('painel_odometro');
				$sql->adCampo('painel_odometro_responsavel');
				$sql->adOnde('painel_odometro_id = '.(int)$linha['evento_gestao_painel_odometro']);
				}		
			elseif ($linha['evento_gestao_painel_composicao']){
				$sql->adTabela('painel_composicao');
				$sql->adCampo('painel_composicao_responsavel');
				$sql->adOnde('painel_composicao_id = '.(int)$linha['evento_gestao_painel_composicao']);
				}		
			elseif ($linha['evento_gestao_tr']){
				$sql->adTabela('tr');
				$sql->adCampo('tr_responsavel');
				$sql->adOnde('tr_id = '.(int)$linha['evento_gestao_tr']);
				}	
			elseif ($linha['evento_gestao_me']){
				$sql->adTabela('me');
				$sql->adCampo('me_usuario');
				$sql->adOnde('me_id = '.(int)$linha['evento_gestao_me']);
				}			
			$resultado=$sql->resultado();
			$sql->Limpar();
			if($resultado) $responsavel[$resultado]=$resultado;
			}
		$responsavel=implode(',', $responsavel);
		}
	else{
		switch ($tipo) {
			case 'projeto':
				$sql->adTabela('projetos');
				$sql->adCampo('projeto_responsavel AS responsavel');
				$sql->adOnde('projeto_id = '.(int)$id);
				break;
			case 'tarefa':
				$sql->adTabela('tarefas');
				$sql->adCampo('tarefa_dono AS responsavel');
				$sql->adOnde('tarefa_id = '.(int)$id);
				break;	
			case 'pratica':
				$sql->adTabela('praticas');
				$sql->adCampo('pratica_responsavel AS responsavel');
				$sql->adOnde('pratica_id = '.(int)$id);
				break;	
			case 'indicador':
				$sql->adTabela('pratica_indicador');
				$sql->adCampo('pratica_indicador_responsavel AS responsavel');
				$sql->adOnde('pratica_indicador_id = '.(int)$id);
				break;	
			case 'estrategia':
				$sql->adTabela('estrategias');
				$sql->adCampo('pg_estrategia_usuario AS responsavel');
				$sql->adOnde('pg_estrategia_id = '.(int)$id);
				break;	
			case 'objetivo':
				$sql->adTabela('objetivos_estrategicos');
				$sql->adCampo('pg_objetivo_estrategico_usuario AS responsavel');
				$sql->adOnde('pg_objetivo_estrategico_id = '.(int)$id);
				break;	
			case 'acao':
				$sql->adTabela('plano_acao');
				$sql->adCampo('plano_acao_responsavel AS responsavel');
				$sql->adOnde('plano_acao_id = '.(int)$id);
				break;	
			}
		$responsavel = $sql->Resultado();
		$sql->limpar();
		}
		
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, cia_nome');
	$sql->adOnde('usuario_id IN ('.($responsavel ? $responsavel : 0).')');	
	$usuarios = $sql->Lista();
	$sql->limpar();
	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) $saida.='<option value="'.$rs['usuario_id'].'">'.utf8_encode(nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '')).'</option>';
	$saida.='</select>';
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("responsavel_ajax");	
	
	
function designados_ajax($tipo='projeto', $id=0, $evento_id=null, $uuid=null) {
	global $Aplic, $config;
	$sql = new BDConsulta;
	
	if ($Aplic->profissional){
		$sql->adTabela('evento_gestao');
		$sql->adCampo('evento_gestao.*');
		if ($evento_id) $sql->adOnde('evento_gestao_evento ='.(int)$evento_id);	
		else $sql->adOnde('evento_gestao_uuid =\''.$uuid.'\'');	
	  $lista = $sql->Lista();
	  $sql->Limpar();
		$designados=array();
		foreach($lista as $linha){
			
			if ($linha['evento_gestao_tarefa']){
				$sql->adTabela('tarefa_designados');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('tarefa_id = '.(int)$linha['evento_gestao_tarefa']);
				}
			elseif ($linha['evento_gestao_projeto']){
				$sql->adTabela('projeto_integrantes');
				$sql->esqUnir('usuarios','usuarios','contato_id=usuario_contato');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('projeto_id = '.(int)$linha['evento_gestao_projeto']);
				}
			elseif ($linha['evento_gestao_perspectiva']){
				$sql->adTabela('perspectivas_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('pg_perspectiva_id = '.(int)$linha['evento_gestao_perspectiva']);
				}	
			elseif ($linha['evento_gestao_tema']){
				$sql->adTabela('tema_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('tema_id = '.(int)$linha['evento_gestao_tema']);
				}	
			elseif ($linha['evento_gestao_objetivo']){
				$sql->adTabela('objetivos_estrategicos_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('pg_objetivo_estrategico_id = '.(int)$linha['evento_gestao_objetivo']);
				}	
			elseif ($linha['evento_gestao_fator']){
				$sql->adTabela('fatores_criticos_usuarios');
				$sql->adCampo('DISTINCT usuario_i');
				$sql->adOnde('pg_fator_critico_id = '.(int)$linha['evento_gestao_fator']);
				}		
			elseif ($linha['evento_gestao_estrategia']){
				$sql->adTabela('estrategias_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('pg_estrategia_id = '.(int)$linha['evento_gestao_estrategia']);
				}	
			elseif ($linha['evento_gestao_meta']){
				$sql->adTabela('metas_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('pg_meta_id = '.(int)$linha['evento_gestao_meta']);
				}			
			elseif ($linha['evento_gestao_pratica']){
				$sql->adTabela('pratica_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('pratica_id = '.(int)$linha['evento_gestao_pratica']);
				}
			elseif ($linha['evento_gestao_indicador']){
				$sql->adTabela('pratica_indicador_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('pratica_indicador_id = '.(int)$linha['evento_gestao_indicador']);
				}
			elseif ($linha['evento_gestao_acao']){
				$sql->adTabela('plano_acao_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('plano_acao_id = '.(int)$linha['evento_gestao_acao']);
				}
			elseif ($linha['evento_gestao_canvas']){
				$sql->adTabela('canvas_usuario');
				$sql->adCampo('DISTINCT canvas_usuario_usuario');
				$sql->adOnde('canvas_usuario_canvas = '.(int)$linha['evento_gestao_canvas']);
				}
			elseif ($linha['evento_gestao_risco']){
				$sql->adTabela('risco_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('risco_id = '.(int)$linha['evento_gestao_risco']);
				}	
			elseif ($linha['evento_gestao_risco_resposta']){
				$sql->adTabela('risco_resposta_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('risco_resposta_id = '.(int)$linha['evento_gestao_risco_resposta']);
				}
			elseif ($linha['evento_gestao_calendario']){
				$sql->adTabela('calendario_usuario');
				$sql->adCampo('DISTINCT calendario_usuario_usuario');
				$sql->adOnde('calendario_usuario_calendario = '.(int)$linha['evento_gestao_calendario']);
				}	
			elseif ($linha['evento_gestao_monitoramento']){
				$sql->adTabela('monitoramento_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('monitoramento_id = '.(int)$linha['evento_gestao_monitoramento']);
				}
			elseif ($linha['evento_gestao_ata']){
				$sql->adTabela('ata_usuario');
				$sql->adCampo('DISTINCT ata_usuario_usuario');
				$sql->adOnde('ata_usuario_ata = '.(int)$linha['evento_gestao_ata']);
				}	
			elseif ($linha['evento_gestao_swot']){
				$sql->adTabela('swot_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('swot_id = '.(int)$linha['evento_gestao_swot']);
				}
			elseif ($linha['evento_gestao_operativo']){
				$sql->adTabela('operativo_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('operativo_id = '.(int)$linha['evento_gestao_operativo']);
				}	
			elseif ($linha['evento_gestao_instrumento']){
				$sql->adTabela('instrumento_designados');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('instrumento_id = '.(int)$linha['evento_gestao_instrumento']);
				}
			elseif ($linha['evento_gestao_recurso']){
				$sql->adTabela('recurso_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('recurso_id = '.(int)$linha['evento_gestao_recurso']);
				}	
			elseif ($linha['evento_gestao_problema']){
				$sql->adTabela('problema_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('problema_id = '.(int)$linha['evento_gestao_problema']);
				}
			elseif ($linha['evento_gestao_demanda']){
				$sql->adTabela('demanda_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('demanda_id = '.(int)$linha['evento_gestao_demanda']);
				}	
			elseif ($linha['evento_gestao_programa']){
				$sql->adTabela('programa_usuario');
				$sql->adCampo('DISTINCT programa_usuario_usuario');
				$sql->adOnde('programa_usuario_programa = '.(int)$linha['evento_gestao_programa']);
				}
			elseif ($linha['evento_gestao_licao']){
				$sql->adTabela('licao_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('licao_id = '.(int)$linha['evento_gestao_licao']);
				}	
			elseif ($linha['evento_gestao_link']){
				$sql->adTabela('link_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('link_id = '.(int)$linha['evento_gestao_link']);
				}
			elseif ($linha['evento_gestao_avaliacao']){
				$sql->adTabela('avaliacao_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('avaliacao_id = '.(int)$linha['evento_gestao_avaliacao']);
				}	
			elseif ($linha['evento_gestao_tgn']){
				$sql->adTabela('tgn_usuario');
				$sql->adCampo('DISTINCT tgn_usuario_usuario');
				$sql->adOnde('tgn_usuario_tgn = '.(int)$linha['evento_gestao_tgn']);
				}
			elseif ($linha['evento_gestao_brainstorm']){
				$sql->adTabela('brainstorm_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('brainstorm_id = '.(int)$linha['evento_gestao_brainstorm']);
				}	
			elseif ($linha['evento_gestao_gut']){
				$sql->adTabela('gut_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('gut_id = '.(int)$linha['evento_gestao_gut']);
				}
			elseif ($linha['evento_gestao_causa_efeito']){
				$sql->adTabela('causa_efeito_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('causa_efeito_id = '.(int)$linha['evento_gestao_causa_efeito']);
				}	
			elseif ($linha['evento_gestao_arquivo']){
				$sql->adTabela('arquivo_usuario');
				$sql->adCampo('DISTINCT arquivo_usuario_usuario');
				$sql->adOnde('arquivo_usuario_arquivo = '.(int)$linha['evento_gestao_arquivo']);
				}
			elseif ($linha['evento_gestao_forum']){
				$sql->adTabela('forum_usuario');
				$sql->adCampo('DISTINCT forum_usuario_usuario');
				$sql->adOnde('forum_usuario_forum = '.(int)$linha['evento_gestao_forum']);
				}	
			elseif ($linha['evento_gestao_checklist']){
				$sql->adTabela('checklist_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('checklist_id = '.(int)$linha['evento_gestao_checklist']);
				}
			elseif ($linha['evento_gestao_agenda']){
				$sql->adTabela('agenda_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('agenda_id = '.(int)$linha['evento_gestao_agenda']);
				}	
			elseif ($linha['evento_gestao_agrupamento']){
				$sql->adTabela('agrupamento_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('agrupamento_id = '.(int)$linha['evento_gestao_agrupamento']);
				}
			elseif ($linha['evento_gestao_patrocinador']){
				$sql->adTabela('patrocinadores_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('patrocinador_id = '.(int)$linha['evento_gestao_patrocinador']);
				}	
			elseif ($linha['evento_gestao_template']){
				$sql->adTabela('template_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('template_id = '.(int)$linha['evento_gestao_template']);
				}
			elseif ($linha['evento_gestao_painel']){
				$sql->adTabela('painel_usuario');
				$sql->adCampo('DISTINCT painel_usuario_usuario');
				$sql->adOnde('painel_usuario_painel = '.(int)$linha['evento_gestao_painel']);
				}	
			elseif ($linha['evento_gestao_painel_odometro']){
				$sql->adTabela('painel_odometro_usuario');
				$sql->adCampo('DISTINCT painel_odometro_usuario_usuario');
				$sql->adOnde('painel_odometro_usuario_painel_odometro = '.(int)$linha['evento_gestao_painel_odometro']);
				}
			elseif ($linha['evento_gestao_painel_composicao']){
				$sql->adTabela('painel_composicao_usuario');
				$sql->adCampo('DISTINCT painel_composicao_usuario_usuario');
				$sql->adOnde('painel_composicao_usuario_painel_composicao = '.(int)$linha['evento_gestao_painel_composicao']);
				}	
			elseif ($linha['evento_gestao_tr']){
				$sql->adTabela('tr_usuario');
				$sql->adCampo('DISTINCT tr_usuario_usuario');
				$sql->adOnde('tr_usuario_tr = '.(int)$linha['evento_gestao_tr']);
				}
			elseif ($linha['evento_gestao_me']){
				$sql->adTabela('me_usuario');
				$sql->adCampo('DISTINCT me_usuario_usuario');
				$sql->adOnde('me_usuario_me = '.(int)$linha['evento_gestao_me']);
				}	
			$equipe=$sql->carregarColuna();
			$sql->Limpar();
			foreach($equipe as $usuario_id) $designados[$usuario_id]=$usuario_id;
			}
		$designados=implode(',', $designados);
		}
	
	else {
		switch ($tipo) {
			case 'projeto':
				$sql->adTabela('projeto_integrantes');
				$sql->esqUnir('usuarios','usuarios','contato_id=usuario_contato');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('projeto_id = '.(int)$id);
				break;
			case 'tarefa':
				$sql->adTabela('tarefa_designados');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('tarefa_id = '.(int)$id);
				break;	
			case 'pratica':
				$sql->adTabela('pratica_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('pratica_id = '.(int)$id);
				break;	
			case 'indicador':
				$sql->adTabela('pratica_indicador_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('pratica_indicador_id = '.(int)$id);
				break;	
			case 'estrategia':
				$sql->adTabela('estrategias_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('pg_estrategia_id = '.(int)$id);
				break;	
			case 'objetivo':
				$sql->adTabela('objetivos_estrategicos_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('pg_objetivo_estrategico_id = '.(int)$id);
				break;	
			case 'acao':
				$sql->adTabela('plano_acao_usuarios');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('plano_acao_id = '.(int)$id);
				break;	
			case 'calendario':
				$sql->adTabela('calendario_usuario');
				$sql->adCampo('DISTINCT usuario_id');
				$sql->adOnde('calendario_id = '.(int)$id);
				break;		
			}
		$designados = $sql->carregarColuna();
		$sql->limpar();
		$designados =implode(',',$designados);
		}

	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, cia_nome');
	$sql->adOnde('usuario_id IN ('.($designados ? $designados : 0).')');	
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$usuarios = $sql->Lista();
	$sql->limpar();
	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) $saida.='<option value="'.$rs['usuario_id'].'">'.utf8_encode(nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '')).'</option>';
	$saida.='</select>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}		
	
$xajax->registerFunction("designados_ajax");	
	
function mudar_usuario_grupo_ajax($grupo_id=0){
	global $Aplic, $config;

	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo=1');	
	if ($grupo_id > 0) $sql->adOnde('usuariogrupo.grupo_id='.(int)$grupo_id);
	elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
  $sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor');	
	$usuarios = $sql->Lista();
	$sql->limpar();

	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) $saida.='<option value="'.$rs['usuario_id'].'">'.utf8_encode(nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '')).'</option>';

	$saida.='</select>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_grupo_ajax");	



$xajax->processRequest();
?>