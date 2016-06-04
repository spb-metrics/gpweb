<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/artefato_pro.class.php'; 

class Modelo {
	var $campo=array();
	var $modelo_tipo=0;
	var $edicao=false;
	var $impressao=false;
	var $modelo_id=0;
	var $paragrafo=0;
	var $modelo_dados_id=0;
	var $qnt=0;
	var $modelo=null;
	
	function __construct() {
		}
	
	function set_modelo($modelo){
		$this->modelo=$modelo;
		}
	

	function set_modelo_tipo($modelo_tipo){
		$this->modelo_tipo=$modelo_tipo;
		}
	
	function set_modelo_id($modelo_id){
		$this->modelo_id=$modelo_id;
		}
	
	function set_campo($tipo, $dados=null, $posicao=null){
		$this->qnt++;
		if (!$posicao) $pos=count($this->campo)+1;
		else $pos=$posicao;
		$this->campo[$pos]=array('tipo' => $tipo, 'dados' => $dados);
		}
	
	function get_campo($posicao){
		global $config, $Aplic, $sem_assinatura, $dados, $total;
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		$tipo=$this->campo[$posicao]['tipo'];
		$saida='';

		switch ($tipo) {
			case 'lista_especial':
				$lista=$this->campo[$posicao]['dados'];
				
				switch ($lista) {
	
					case 'corpo_cabecalho':
						$saida=corpo_cabecalho();
						break;
					
					case 'data_hoje':
						return date('d/m/Y');
						break;
					
					case 'lista_alinhamento_estrategico':
						$saida=lista_alinhamento_estrategico($dados['projeto_id']);
						break;
					
					case 'lista_eap':
						$saida=lista_eap($dados['projeto_id']);
						break;
						
					case 'lista_gantt':
						$saida=lista_gantt($dados['projeto_id']);
						break;	
						
					case 'lista_gastos':
						$saida=lista_gastos($dados['projeto_id']);
						break;		
					
					case 'lista_inicio_termino':
						$saida=lista_inicio_termino($dados['projeto_id']);
						break;
						
					case 'lista_valor_planejado':
						$saida=lista_valor_planejado($dados['projeto_id']);
						break;	
					
					case 'lista_patrocinador':
						$saida=lista_patrocinador($dados['projeto_id']);
						break;
					
					case 'lista_tipo':
						$saida=lista_tipo($dados['projeto_tipo']);
						break;
					
					case 'arvore_problema':
						global $saida_causa;
						$saida=$saida_causa;
						break;	
					
					case 'tarefas_atraso':
						$saida=tarefas_atraso($dados['projeto_id']);
						break;
					
					case 'lista_status':
						$saida=lista_status($dados['projeto_id']);
						break;	
						
					case 'lista_inconsistencias':
						$saida=lista_inconsistencias($dados['projeto_id']);
						break;	
					
					case 'geral_tarefas':
						$saida=geral_tarefas($dados['projeto_id']);
						break;
					
					case 'matriz_stakeholders':
						$saida=matriz_stakeholders($dados['projeto_id']);
						break;	
					
					case 'projeto_lista_indicadores':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('pratica_indicador');
						$sql->adCampo('pratica_indicador_id');
						$sql->adOnde('pratica_indicador_projeto ='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
						$lista = $sql->carregarColuna();
						$sql->limpar();
						include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
						include_once BASE_DIR.'/modulos/praticas/praticas.class.php';
						$qnt=0;
						foreach ($lista as $pratica_indicador_id) {				
							$qnt++;
							$sql->adTabela('pratica_indicador');
							$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
							$sql->adCampo('pratica_indicador.pratica_indicador_id, pratica_indicador_tipo, pratica_indicador_nome, pratica_indicador_sentido, pratica_indicador_agrupar, pratica_indicador_requisito_quando, pratica_indicador_requisito_oque, pratica_indicador_requisito_como, pratica_indicador_requisito_onde, pratica_indicador_requisito_quanto, pratica_indicador_requisito_porque, pratica_indicador_requisito_quem');
							$sql->adCampo('0 AS pratica_indicador_favoravel, 0 AS pratica_indicador_superior, 0 AS pratica_indicador_referencial');
							$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
							$pratica_indicador=$sql->Linha();
							$sql->limpar();
							$tendencia=0;
							$obj_indicador = new Indicador($pratica_indicador['pratica_indicador_id']);
							if ($obj_indicador->Tendencia()=='positiva') $pratica_indicador['pratica_indicador_favoravel']=1;
							$valores=$obj_indicador->Valor_atual($pratica_indicador['pratica_indicador_agrupar']);
							if($valores && $obj_indicador->pratica_indicador_valor_referencial!=null){
								if ($valores >= $obj_indicador->pratica_indicador_valor_referencial && $pratica_indicador['pratica_indicador_sentido']) $pratica_indicador['pratica_indicador_superior']=1;
								elseif ($valores <= $obj_indicador->pratica_indicador_valor_referencial && !$pratica_indicador['pratica_indicador_sentido']) $pratica_indicador['pratica_indicador_superior']=1;
								}
							if ($obj_indicador->pratica_indicador_valor_referencial!=null) $pratica_indicador['pratica_indicador_referencial']=1;					
							$saida.='<tr><td colspan=2 style="font-family:Times New Roman, Times, serif; font-size:12pt;" bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : '99ccff').'"><b>'.$qnt.'. Indicador '.($qnt < 10 ? '0' : '').$qnt.'</b></td></tr>';
							$saida.='<tr><td colspan=2>&nbsp;</td><tr>';
							$saida.='<tr><td colspan=2 style="border-style:solid; border-width:1px 1px 1px 1px; font-family:Times New Roman, Times, serif; font-size:12pt;" bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'"><b>INDICADOR: '.$pratica_indicador['pratica_indicador_nome'].'</b></td></tr>';
							if ($pratica_indicador['pratica_indicador_tipo']) $saida.='<tr><td width="170" style="border-style:solid; border-width:0px 1px 1px 1px; font-family:Times New Roman, Times, serif; font-size:12pt;">Tipo de Indicador</td><td style="border-style:solid; border-width:0px 1px 1px 0px; font-family:Times New Roman, Times, serif; font-size:12pt;">'.getSisValorCampo('IndicadorTipo',$pratica_indicador['pratica_indicador_tipo']).'</td></tr>';
							if ($pratica_indicador['pratica_indicador_requisito_oque']) $saida.='<tr><td style="border-style:solid; border-width:0px 1px 1px 1px;font-family:Times New Roman, Times, serif; font-size:12pt;">O quê mede</td><td style="border-style:solid; border-width:0px 1px 1px 0px; font-family:Times New Roman, Times, serif; font-size:12pt;">'.$pratica_indicador['pratica_indicador_requisito_oque'].'</td></tr>';
							if ($pratica_indicador['pratica_indicador_requisito_quem']) $saida.='<tr><td style="border-style:solid; border-width:0px 1px 1px 1px; font-family:Times New Roman, Times, serif; font-size:12pt;">Quem mede</td><td style="border-style:solid; border-width:0px 1px 1px 0px; font-family:Times New Roman, Times, serif; font-size:12pt;">'.$pratica_indicador['pratica_indicador_requisito_quem'].'</td></tr>';
							if ($pratica_indicador['pratica_indicador_requisito_quando']) $saida.='<tr><td style="border-style:solid; border-width:0px 1px 1px 1px; font-family:Times New Roman, Times, serif; font-size:12pt;">Quando medir</td><td style="border-style:solid; border-width:0px 1px 1px 0px; font-family:Times New Roman, Times, serif; font-size:12pt;">'.$pratica_indicador['pratica_indicador_requisito_quando'].'</td></tr>';
							if ($pratica_indicador['pratica_indicador_requisito_onde']) $saida.='<tr><td style="border-style:solid; border-width:0px 1px 1px 1px; font-family:Times New Roman, Times, serif; font-size:12pt;">Onde medir</td><td style="border-style:solid; border-width:0px 1px 1px 0px; font-family:Times New Roman, Times, serif; font-size:12pt;">'.$pratica_indicador['pratica_indicador_requisito_onde'].'</td></tr>';
							if ($pratica_indicador['pratica_indicador_requisito_porque']) $saida.='<tr><td style="border-style:solid; border-width:0px 1px 1px 1px; font-family:Times New Roman, Times, serif; font-size:12pt;">Por que medir</td><td style="border-style:solid; border-width:0px 1px 1px 0px; font-family:Times New Roman, Times, serif; font-size:12pt;">'.$pratica_indicador['pratica_indicador_requisito_porque'].'</td></tr>';
							if ($pratica_indicador['pratica_indicador_requisito_como']) $saida.='<tr><td style="border-style:solid; border-width:0px 1px 1px 1px; font-family:Times New Roman, Times, serif; font-size:12pt;">Como medir</td><td style="border-style:solid; border-width:0px 1px 1px 0px; font-family:Times New Roman, Times, serif; font-size:12pt;">'.$pratica_indicador['pratica_indicador_requisito_como'].'</td></tr>';
							$saida.='<tr><td style="border-style:solid; border-width:0px 1px 1px 1px; font-family:Times New Roman, Times, serif; font-size:12pt;">Situação atual</td><td style="border-style:solid; border-width:0px 1px 1px 0px; font-family:Times New Roman, Times, serif; font-size:12pt;">'.$obj_indicador->Pontuacao(null, null, null, true).'%</td></tr>';				
							$saida.='<tr><td style="border-style:solid; border-width:0px 1px 1px 1px; font-family:Times New Roman, Times, serif; font-size:12pt;"bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">META</td><td style="border-style:solid; border-width:0px 1px 1px 0px; font-family:Times New Roman, Times, serif; font-size:12pt;" bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">'.number_format($obj_indicador->pratica_indicador_valor_meta, 2, ',', '.').'</td></tr>';										
							$saida.='<tr><td colspan=2>&nbsp;</td><tr>';
							}
						if ($saida) $saida='<table width="100%" border=0 cellpadding="2" cellspacing="0">'.$saida.'</table>';
						break;	
					
					case 'projeto_codigo':
						if ($dados['projeto_codigo']) $saida=$dados['projeto_codigo'];
						else {
							$objProjeto = new CProjeto();
							$objProjeto->load($dados['projeto_id']);
							$saida=$objProjeto->getCodigo();
							}
						break;
					
					case 'demanda_complexidade':
						$saida=getSisValorCampo('ProjetoComplexidade', $dados['demanda_complexidade']);
						break;
					
					case 'demanda_custo':
						$saida=getSisValorCampo('ProjetoCusto', $dados['demanda_custo']);
						break;
					
					case 'demanda_tempo':
						$saida=getSisValorCampo('ProjetoTempo', $dados['demanda_tempo']);
						break;
					
					case 'demanda_servidores':
						$saida=getSisValorCampo('ProjetoServidores', $dados['demanda_servidores']);
						break;
					
					case 'demanda_recurso_externo':
						$saida=getSisValorCampo('ProjetoRecursoExterno', $dados['demanda_recurso_externo']);
						break;
					
					case 'demanda_interligacao':
						$saida=getSisValorCampo('ProjetoInterligacao', $dados['demanda_interligacao']);
						break;
					
					case 'demanda_tamanho':
						$saida=getSisValorCampo('ProjetoTamanho', $dados['demanda_tamanho']);
						break;
					
					case 'ata_codigo':
						$ata_numero=(isset($dados['ata_numero']) ? $dados['ata_numero'] : '');
						$saida=($ata_numero && $ata_numero<100 ? '0' : '').($ata_numero && $ata_numero<10 ? '0' : '').$ata_numero;
						break;
					
					case 'ata_gestao':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('ata_gestao');
						$sql->adCampo('ata_gestao.*');
						$sql->adOnde('ata_gestao_ata ='.(int)(isset($dados['ata_id']) ? $dados['ata_id'] : 0));
						$sql->adOrdem('ata_gestao_ordem');
						$lista_gestao = $sql->Lista();
						$sql->Limpar();
						if (count($lista)){
							$swot_ativo=$Aplic->modulo_ativo('swot');
							if ($swot_ativo) require_once BASE_DIR.'/modulos/swot/swot.class.php';
							$operativo_ativo=$Aplic->modulo_ativo('operativo');
							if ($operativo_ativo) require_once BASE_DIR.'/modulos/operativo/funcoes.php';
							$problema_ativo=$Aplic->modulo_ativo('problema');
							if ($problema_ativo) require_once BASE_DIR.'/modulos/problema/funcoes.php';
							$usado=0;
							foreach($lista_gestao as $gestao_data){
								if ($gestao_data['ata_gestao_tarefa']) $saida.= ($usado++? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['ata_gestao_tarefa']);
								elseif ($gestao_data['ata_gestao_projeto']) $saida.= ($usado++? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['ata_gestao_projeto']);
								elseif ($gestao_data['ata_gestao_pratica']) $saida.= ($usado++? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['ata_gestao_pratica']);
								elseif ($gestao_data['ata_gestao_acao']) $saida.= ($usado++? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['ata_gestao_acao']);
								elseif ($gestao_data['ata_gestao_perspectiva']) $saida.= ($usado++? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['ata_gestao_perspectiva']);
								elseif ($gestao_data['ata_gestao_tema']) $saida.= ($usado++? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['ata_gestao_tema']);
								elseif ($gestao_data['ata_gestao_objetivo']) $saida.= ($usado++? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['ata_gestao_objetivo']);
								elseif ($gestao_data['ata_gestao_fator']) $saida.= ($usado++? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['ata_gestao_fator']);
								elseif ($gestao_data['ata_gestao_estrategia']) $saida.= ($usado++? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['ata_gestao_estrategia']);
								elseif ($gestao_data['ata_gestao_meta']) $saida.= ($usado++? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['ata_gestao_meta']);
								elseif ($gestao_data['ata_gestao_canvas']) $saida.= ($usado++? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['ata_gestao_canvas']);
								elseif ($gestao_data['ata_gestao_risco']) $saida.= ($usado++? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['ata_gestao_risco']);
								elseif ($gestao_data['ata_gestao_risco_resposta']) $saida.= ($usado++? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['ata_gestao_risco_resposta']);
								elseif ($gestao_data['ata_gestao_indicador']) $saida.= ($usado++? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['ata_gestao_indicador']);
								elseif ($gestao_data['ata_gestao_calendario']) $saida.= ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['ata_gestao_calendario']);
								elseif ($gestao_data['ata_gestao_monitoramento']) $saida.= ($usado++? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['ata_gestao_monitoramento']);
								elseif ($gestao_data['ata_gestao_swot']) $saida.= ($usado++? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['ata_gestao_swot']);
								elseif ($gestao_data['ata_gestao_operativo']) $saida.= ($usado++? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['ata_gestao_operativo']);
								elseif ($gestao_data['ata_gestao_instrumento']) $saida.= ($usado++? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['ata_gestao_instrumento']);
								elseif ($gestao_data['ata_gestao_recurso']) $saida.= ($usado++? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['ata_gestao_recurso']);
								elseif ($gestao_data['ata_gestao_problema']) $saida.= ($usado++? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['ata_gestao_problema']);
								elseif ($gestao_data['ata_gestao_demanda']) $saida.= ($usado++? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['ata_gestao_demanda']);
								elseif ($gestao_data['ata_gestao_programa']) $saida.= ($usado++? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['ata_gestao_programa']);
								elseif ($gestao_data['ata_gestao_licao']) $saida.= ($usado++? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['ata_gestao_licao']);
								elseif ($gestao_data['ata_gestao_evento']) $saida.= ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['ata_gestao_evento']);
								elseif ($gestao_data['ata_gestao_link']) $saida.= ($usado++? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['ata_gestao_link']);
								elseif ($gestao_data['ata_gestao_avaliacao']) $saida.= ($usado++? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['ata_gestao_avaliacao']);
								elseif ($gestao_data['ata_gestao_tgn']) $saida.= ($usado++? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['ata_gestao_tgn']);
								elseif ($gestao_data['ata_gestao_brainstorm']) $saida.= ($usado++? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['ata_gestao_brainstorm']);
								elseif ($gestao_data['ata_gestao_gut']) $saida.= ($usado++? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['ata_gestao_gut']);
								elseif ($gestao_data['ata_gestao_causa_efeito']) $saida.= ($usado++? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['ata_gestao_causa_efeito']);
								elseif ($gestao_data['ata_gestao_arquivo']) $saida.= ($usado++? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['ata_gestao_arquivo']);
								elseif ($gestao_data['ata_gestao_forum']) $saida.= ($usado++? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['ata_gestao_forum']);
								elseif ($gestao_data['ata_gestao_checklist']) $saida.= ($usado++? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['ata_gestao_checklist']);
								elseif ($gestao_data['ata_gestao_agenda']) $saida.= ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['ata_gestao_agenda']);
								elseif ($gestao_data['ata_gestao_agrupamento']) echo ($usado++? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['ata_gestao_agrupamento']);
								elseif ($gestao_data['ata_gestao_patrocinador']) echo ($usado++? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['ata_gestao_patrocinador']);
								elseif ($gestao_data['ata_gestao_template']) echo ($usado++? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['ata_gestao_template']);
								elseif ($gestao_data['ata_gestao_painel']) echo ($usado++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['ata_gestao_painel']);
								elseif ($gestao_data['ata_gestao_painel_odometro']) echo ($usado++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['ata_gestao_painel_odometro']);
								elseif ($gestao_data['ata_gestao_painel_composicao']) echo ($usado++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['ata_gestao_painel_composicao']);
								elseif ($gestao_data['ata_gestao_tr']) echo ($usado++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['ata_gestao_tr']);
								elseif ($gestao_data['ata_gestao_me']) echo ($usado++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['ata_gestao_me']);	
								}
							}
						break;
							
					case 'ata_assinaturas':
						$saida='';
						$assinatura = getParam($_REQUEST, 'assinatura', 0);
						
						$sql = new BDConsulta();
						
						$sql->adTabela('ata_config');
						$sql->adCampo('ata_config.*');
						$configuracao = $sql->linha();
						$sql->Limpar();
						
						
						$sql->adTabela('assinatura');
						$sql->esqUnir('tr_atesta_opcao', 'tr_atesta_opcao', 'tr_atesta_opcao_id=assinatura_atesta_opcao');
						$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=assinatura_usuario');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_funcao');
						$sql->adCampo('assinatura_usuario, assinatura_funcao, tr_atesta_opcao_nome, assinatura_data, formatar_data(assinatura_data, \'%d/%m/%Y\') AS data, assinatura_observacao');		
						$sql->adOnde('assinatura_ata = '.(int)(isset($dados['ata_id']) ? $dados['ata_id'] : 0));
						$sql->adOrdem('assinatura_ordem');
						$lista = $sql->lista();
						$sql->limpar();	
						$col=0;	
						foreach ($lista as $linha) {
							$bloco='<table border=0 cellpadding="0" cellspacing="0">';
							$sql->adTabela('usuarios');
							$sql->adCampo('usuario_assinatura');
							$sql->adOnde('usuario_id = '.(int)$linha['assinatura_usuario']);
							$caminho = $sql->Resultado();
							$sql->limpar();
							$col++;
							if ($linha['assinatura_data']) $bloco.=($assinatura && $caminho && file_exists($base_dir.'/arquivos/assinaturas/'.$caminho) ? '<img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/assinaturas/'.$caminho.'" />' : '</br></br>______________________________________________________________________');	
							else $bloco.='<tr><td valign="bottom" align=center style="height:80px; font-family:Times New Roman, Times, serif; font-size:12pt;">______________________________________________________________________</td></tr>';
							$bloco.='<tr><td align=center style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nome'].'</td></tr>';
							if ($configuracao['ata_config_exibe_funcao']) $bloco.='<tr><td align=center style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['assinatura_funcao'] ? $linha['assinatura_funcao'] : $linha['contato_funcao']).'</td></tr>';
							if ($linha['assinatura_data']) $bloco.='<tr><td align=center style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['data'].'</td></tr>';
							if ($linha['assinatura_observacao']) $bloco.='<tr><td align=center style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['assinatura_observacao'].'</td></tr>';
							$bloco.='</table>';
							if ($col==1) $saida.='<tr><td><table cellpadding="0" cellspacing="0" width="100%"><tr><td width="50%" align="center">'.$bloco.'</td>';
							else $saida.='<td width="50%" align="center">'.$bloco.'</td></tr></table></td></tr>';
							if ($col==2)$col=0; 
							}
						
						if ($Aplic->profissional){	
							$sql->adTabela('ata_externo');
							$sql->adCampo('ata_externo_id, ata_externo_nome, ata_externo_campo2, ata_externo_campo3, ata_externo_campo4');	
							$sql->adOnde('ata_externo_ata = '.(int)(isset($dados['ata_id']) ? $dados['ata_id'] : 0));
							$sql->adOrdem('ata_externo_ordem');
							$lista = $sql->lista();
							$sql->limpar();	
							foreach ($lista as $linha) {
								$bloco='<table border=0 cellpadding="0" cellspacing="0">';
								$col++;
								$bloco.='<tr><td valign="bottom" align=center style="height:80px; font-family:Times New Roman, Times, serif; font-size:12pt;">______________________________________________________________________</td></tr>';
								$bloco.='<tr><td align=center style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['ata_externo_nome'].'</td></tr>';
								if ($configuracao['ata_config_exibe_linha2'] && $linha['ata_externo_campo2']) $bloco.='<tr><td align=center style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['ata_externo_campo2'].'</td></tr>';
								if ($configuracao['ata_config_exibe_linha3'] && $linha['ata_externo_campo3']) $bloco.='<tr><td align=center style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['ata_externo_campo3'].'</td></tr>';
								if ($configuracao['ata_config_exibe_linha4'] && $linha['ata_externo_campo4']) $bloco.='<tr><td align=center style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['ata_externo_campo4'].'</td></tr>';
								$bloco.='</table>';
								if ($col==1) $saida.='<tr><td><table cellpadding="0" cellspacing="0" width="100%"><tr><td width="50%" align="center">'.$bloco.'</td>';
								else $saida.='<td width="50%" align="center">'.$bloco.'</td></tr></table></td></tr>';
								if ($col==2)$col=0; 
								}	
							}
							
							
						if ($saida && $col==1) $saida.='<td>&nbsp;</td></tr></table></td></tr>';
						if ($saida) $saida='<table cellpadding="0" cellspacing="0" width="100%">'.$saida.'</table>';
						break;	
					
					case 'projeto_recebimento_numero':
						$saida=($dados['projeto_recebimento_numero'] < 100 ? '0' : '').($dados['projeto_recebimento_numero'] < 10 ? '0' : '').$dados['projeto_recebimento_numero'];
						break;
					
					case 'ata_participantes':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('assinatura');
						$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=assinatura_usuario');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
						$sql->esqUnir('cias','cias','contato_cia=cia_id');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2, contato_funcao, cia_nome');
						$sql->adOnde('assinatura_ata = '.(int)(isset($dados['ata_id']) ? $dados['ata_id'] : 0));
						$sql->adOrdem('assinatura_ordem');
						$lista = $sql->lista();
						$sql->limpar();	
						foreach ($lista as $linha) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['cia_nome'] ? $linha['cia_nome'] : '&nbsp;').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td></tr>';
						if ($saida) $saida='<table border=1 cellpadding="2" cellspacing="0"  width=100%><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Órgão</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td></tr>'.$saida.'</table>';
						
						
						$sql->adTabela('ata_config');
						$sql->adCampo('ata_config.*');
						$configuracao = $sql->linha();
						$sql->Limpar();
						
						
						$saida2='';
						$sql->adTabela('ata_externo');
						$sql->adCampo('ata_externo_nome, ata_externo_campo2, ata_externo_campo3, ata_externo_campo4');
						$sql->adOnde('ata_externo_ata = '.(int)(isset($dados['ata_id']) ? $dados['ata_id'] : 0));
						$sql->adOrdem('ata_externo_ordem');
						$lista = $sql->lista();
						$sql->limpar();	
						foreach ($lista as $linha) {
							$saida2.= '<tr align="center">';
							$saida2.= '<td align="left" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['ata_externo_nome'].'</td>';
							if ($configuracao['ata_config_exibe_linha2']) $saida2.= '<td align="left" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['ata_externo_campo2'].'</td>';
							if ($configuracao['ata_config_exibe_linha3']) $saida2.= '<td align="left" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['ata_externo_campo3'].'</td>';
							if ($configuracao['ata_config_exibe_linha4']) $saida2.= '<td align="left" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['ata_externo_campo4'].'</td>';
							$saida2.= '</tr>';
							}	
						if ($saida2) $saida2='<table border=1 cellpadding="2" cellspacing="0" width=100%><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td>'.($configuracao['ata_config_exibe_linha2'] ? '<td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$configuracao['ata_config_linha2_legenda'].'</td>' : '').($configuracao['ata_config_exibe_linha3'] ? '<td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$configuracao['ata_config_linha3_legenda'].'</td>' : '').($configuracao['ata_config_exibe_linha4'] ? '<td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$configuracao['ata_config_linha4_legenda'].'</td>' : '').'</tr>'.$saida2.'</table>';

						$saida.=($saida2 ? '<br>' : '').$saida2;

						break;	
					
					case 'projeto_recebimento_numero':
						$saida=($dados['projeto_recebimento_numero'] < 100 ? '0' : '').($dados['projeto_recebimento_numero'] < 10 ? '0' : '').$dados['projeto_recebimento_numero'];
						break;
					
					
					case 'ata_pauta':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('ata_pauta');
						$sql->adCampo('*');
						$sql->adOnde('ata_pauta_ata='.(int)(isset($dados['ata_id']) ? $dados['ata_id'] : 0));
						$sql->adOnde('ata_pauta_tipo!=1');
						$sql->adOrdem('ata_pauta_ordem ASC');
						$pautas=$sql->Lista();
						$sql->limpar();	
						if ($pautas && count($pautas)) $saida.= '<table cellpadding=2 cellspacing=0 border=1 width="100%"><tr><td style="width:30px; background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Item</b></td><td style="background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Pauta</b></td></tr>';
						$qnt=0;
						foreach ($pautas as $pauta) {
							$qnt++;
							$saida.='<tr>';
							$saida.='<td align="right" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$qnt.'</td>';
							$saida.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$pauta['ata_pauta_texto'].'</td>';
							$saida.='</tr>';
							}
						if ($pautas && count($pautas)) $saida.='</table>';		
						break;
					
					
					case 'ata_pauta_proxima':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('ata_pauta');
						$sql->adCampo('*');
						$sql->adOnde('ata_pauta_ata='.(int)(isset($dados['ata_id']) ? $dados['ata_id'] : 0));
						$sql->adOnde('ata_pauta_tipo=1');
						$sql->adOrdem('ata_pauta_ordem ASC');
						$pautas=$sql->Lista();
						$sql->limpar();	
						if ($pautas && count($pautas)) $saida.= '<table cellpadding=2 cellspacing=0 border=1 width="100%"><tr><td style="width:30px; background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Item</b></td><td style="background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Pauta</b></td></tr>';
						$qnt=0;
						foreach ($pautas as $pauta) {
							$qnt++;
							$saida.='<tr>';
							$saida.='<td align="right" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$qnt.'</td>';
							$saida.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$pauta['ata_pauta_texto'].'</td>';
							$saida.='</tr>';
							}
						if ($pautas && count($pautas)) $saida.='</table>';		
						break;
					
					
					case 'ata_acoes':
						$sql = new BDConsulta();
						$sql->adTabela('ata_acao');
						$sql->adCampo('ata_acao_id, ata_acao_responsavel, ata_acao_ordem, ata_acao_texto, ata_acao_percentagem, formatar_data(ata_acao_inicio, \'%d/%m/%Y  %H:%i\') AS inicio, formatar_data(ata_acao_fim, \'%d/%m/%Y  %H:%i\') AS fim, ata_acao_status');
						$sql->adOnde('ata_acao_ata='.(int)(isset($dados['ata_id']) ? $dados['ata_id'] : 0));
						$sql->adOrdem('ata_acao_ordem ASC');
						$acaos=$sql->Lista();
						$sql->limpar();
												
						$status = getSisValor('StatusAcaoAta');
						$qnt=0;
						if ($acaos && count($acaos)) $saida.= '<table cellspacing=0 cellpadding=2 border=1 width="100%"><tr><td style="width:30px; background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Item</b></td><td style="background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Ação</b></td><td style="background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Início</b></td><td style="background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Término</b></td><td style="background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>%</b></td><td style="background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Status</b></td><td style="background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'; font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Responsável</b></td></tr>';
						foreach ($acaos as $acao) {
							$qnt++;
							$saida.='<tr>';
							$saida.='<td align="right">'.$qnt.'</td>';
							$saida.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($acao['ata_acao_texto'] ? $acao['ata_acao_texto'] : '&nbsp;').'</td>';
							$saida.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$acao['inicio'].'</td>';
							$saida.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$acao['fim'].'</td>';
							$saida.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;" align=right>'.number_format($acao['ata_acao_percentagem'], 2, ',', '.').'</td>';
							$saida.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;" align=center>'.(isset($status[$acao['ata_acao_status']]) ? $status[$acao['ata_acao_status']] : '&nbsp;').'</td>';
							$saida.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.nome_funcao('', '', '', '',$acao['ata_acao_responsavel']).'</td>';
							$saida.='</tr>';
							}
						if ($acaos && count($acaos)) $saida.='</table>';
						break;
					
					case 'projeto_recebimento_lista':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_recebimento_lista');
						$sql->adCampo('projeto_recebimento_lista_produto');
						$sql->adOnde('projeto_recebimento_lista_recebimento_id = '.(int)(isset($dados['projeto_recebimento_id']) ? $dados['projeto_recebimento_id'] : 0));
						$lista = $sql->lista();
						$sql->limpar();	
						$qnt=0;
						foreach($lista as $linha)	{
							$qnt++;
							$saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($qnt < 100 ? '0' : '').($qnt < 10 ? '0' : '').$qnt.'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_recebimento_lista_produto'].'</td></tr>';
							}
						if ($saida) $saida='<br><table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Item</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Descrição do produto/serviço</td></tr>'.$saida.'</table>';
						break;
					
					
					case 'projeto_recebimento_tipo':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_recebimento');
						$sql->adCampo('projeto_recebimento_provisorio, projeto_recebimento_definitivo');
						$sql->adOnde('projeto_recebimento_id = '.(int)(isset($dados['projeto_recebimento_id']) ? $dados['projeto_recebimento_id'] : 0));
						$linha = $sql->linha();
						$sql->limpar();	

						$saida='<br><table border=1 cellpadding="2" cellspacing="0">
						<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['projeto_recebimento_provisorio'] ? 'X' : '&nbsp;') .'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Recebimento Provisório</b></td></tr>
						<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['projeto_recebimento_definitivo'] ? 'X' : '&nbsp;') .'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Recebimento Definitivo</b></td></tr>
						</table>';
						break;	
					
					case 'recebimento_dados_cliente':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_recebimento');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=projeto_recebimento_cliente');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2, contato_funcao');
						$sql->adOnde('projeto_recebimento_id = '.(int)(isset($dados['projeto_recebimento_id']) ? $dados['projeto_recebimento_id'] : 0));
						$linha = $sql->linha();
						$sql->limpar();	
						if($linha['nome']) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_funcao']? $linha['contato_funcao'] : '&nbsp.').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;" align=center>'.($dados['projeto_recebimento_data_entrega'] ? retorna_data($dados['projeto_recebimento_data_entrega'], false) : '&nbsp;').'</td></tr>';
						if ($saida) $saida='<br><table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Cargo / Função</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;" >Data de recebimento</td></tr>'.$saida.'</table>';
						break;
					
					case 'recebimento_dados_responsavel':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_recebimento');
						$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=projeto_recebimento_responsavel');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2, contato_funcao');
						$sql->adOnde('projeto_recebimento_id = '.(int)(isset($dados['projeto_recebimento_id']) ? $dados['projeto_recebimento_id'] : 0));
						$linha = $sql->linha();
						$sql->limpar();	
						if($linha['nome']) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_funcao']? $linha['contato_funcao'] : '&nbsp.').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td></tr>';
						if ($saida) $saida='<br><table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Cargo / Função</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td></tr>'.$saida.'</table>';
						break;
					
					

					case 'encerramento_dados_responsavel':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_encerramento');
						$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=projeto_encerramento_responsavel');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2, contato_funcao');
						$sql->adOnde('projeto_encerramento_projeto = '.(int)(isset($dados['projeto_encerramento_projeto']) ? $dados['projeto_encerramento_projeto'] : 0));
						$linha = $sql->linha();
						$sql->limpar();	
						if($linha['nome']) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_funcao']? $linha['contato_funcao'] : '&nbsp.').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td></tr>';
						if ($saida) $saida='<br><table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Cargo / Função</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td></tr>'.$saida.'</table>';
						break;	
						
					case 'projeto_encerramento_decisao':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_encerramento');
						$sql->adCampo('projeto_encerramento_encerrado, projeto_encerramento_encerrado_ressalvas, projeto_encerramento_nao_encerrado');
						$sql->adOnde('projeto_encerramento_projeto = '.(int)(isset($dados['projeto_encerramento_projeto']) ? $dados['projeto_encerramento_projeto'] : 0));
						$linha = $sql->linha();
						$sql->limpar();	

						$saida='<br><table border=1 cellpadding="2" cellspacing="0">
						<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['projeto_encerramento_encerrado'] ? 'X' : '&nbsp;') .'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Projeto encerrado</b></td></tr>
						<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['projeto_encerramento_encerrado_ressalvas'] ? 'X' : '&nbsp;') .'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Projeto encerrado com ressalvas</b></td></tr>
						<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['projeto_encerramento_nao_encerrado'] ? 'X' : '&nbsp;') .'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Projeto não encerrado</b></td></tr>
						</table>';
						break;	
						
					
					case 'licao':
						$saida='';
						$licao_categoria=getSisValor('LicaoCategoria');
						$saida2='';
						$sql = new BDConsulta;
						$sql->adTabela('licao');
						$sql->adCampo('licao.*');
						$sql->adOnde('licao_projeto='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
						$sql->adOrdem('licao_id ASC');
						$licoes=$sql->Lista();
						if ($licoes && count($licoes)) {
							$saida2.= '<tr>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Ocorrência</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Tipo</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Categoria</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Consequências</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Ação Tomada</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Aprendizagem</td>';
							$saida2.='</tr>';
							}
						foreach ($licoes as $licao) {
							$saida2.='<tr>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($licao['licao_ocorrencia'] ? $licao['licao_ocorrencia'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($licao['licao_tipo'] ? 'Positiva' : 'Netativa').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.(isset($licao_categoria[$licao['licao_categoria']]) ? $licao_categoria[$licao['licao_categoria']] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($licao['licao_consequencia'] ? $licao['licao_consequencia'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($licao['licao_acao_tomada'] ? $licao['licao_acao_tomada'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($licao['licao_aprendizado'] ? $licao['licao_aprendizado'] : '&nbsp;').'</td>';
							$saida2.='</tr>';
							}
						if (count($licoes)) $saida='<tr><td><table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="100%">'.$saida2.'</table></td></tr>';
						break;	
					
					
					
					
					case 'projeto_viabilidade_patrocinadores':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_viabilidade_patrocinadores');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=projeto_viabilidade_patrocinadores.contato_id');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2');
						$sql->adOnde('projeto_viabilidade_id = '.(int)(isset($dados['projeto_viabilidade_id']) ? $dados['projeto_viabilidade_id'] : 0));
						$lista = $sql->lista();
						$sql->limpar();	
						foreach ($lista as $linha) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td></tr>';
						if ($saida) $saida='<table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td></tr>'.$saida.'</table>';
						break;	
						
					case 'projeto_viabilidade_interessados':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_viabilidade_interessados');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=projeto_viabilidade_interessados.contato_id');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2');
						$sql->adOnde('projeto_viabilidade_id = '.(int)(isset($dados['projeto_viabilidade_id']) ? $dados['projeto_viabilidade_id'] : 0));
						$lista = $sql->lista();
						$sql->limpar();	
						foreach ($lista as $linha) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td></tr>';
						if ($saida) $saida='<table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td></tr>'.$saida.'</table>';
						break;		
						
					
					case 'projeto_viabilidade_usuarios':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_viabilidade_usuarios');
						$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=projeto_viabilidade_usuarios.usuario_id');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2, contato_funcao');
						$sql->adOnde('projeto_viabilidade_id = '.(int)(isset($dados['projeto_viabilidade_id']) ? $dados['projeto_viabilidade_id'] : 0));
						$lista = $sql->lista();
						$sql->limpar();	
						foreach ($lista as $linha) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_funcao'] ? $linha['contato_funcao'] : '&nbsp;').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td></tr>';
						if ($saida) $saida='<table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Função</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td></tr>'.$saida.'</table>';
						break;	
					
					
					case 'projeto_abertura_patrocinadores':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_abertura_patrocinadores');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=projeto_abertura_patrocinadores.contato_id');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2');
						$sql->adOnde('projeto_abertura_id = '.(int)(isset($dados['projeto_abertura_id']) ? $dados['projeto_abertura_id'] : 0));
						$lista = $sql->lista();
						$sql->limpar();	
						foreach ($lista as $linha) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td></tr>';
						if ($saida) $saida='<table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td></tr>'.$saida.'</table>';
						break;	
						
					case 'projeto_abertura_interessados':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_abertura_interessados');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=projeto_abertura_interessados.contato_id');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2');
						$sql->adOnde('projeto_abertura_id = '.(int)(isset($dados['projeto_abertura_id']) ? $dados['projeto_abertura_id'] : 0));
						$lista = $sql->lista();
						$sql->limpar();	
						foreach ($lista as $linha) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt; white-space: nowrap;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt; white-space: nowrap;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt; white-space: nowrap;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td></tr>';
						if ($saida) $saida='<table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td></tr>'.$saida.'</table>';
						break;		
						
					
					case 'projeto_abertura_usuarios':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_abertura_usuarios');
						$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=projeto_abertura_usuarios.usuario_id');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
						$sql->esqUnir('cias','cias','contatos.contato_cia=cias.cia_id');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, cia_nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2, contato_funcao');
						$sql->adOnde('projeto_abertura_id = '.(int)(isset($dados['projeto_abertura_id']) ? $dados['projeto_abertura_id'] : 0));
						$lista = $sql->lista();
						$sql->limpar();	
						foreach ($lista as $linha) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt; white-space: nowrap;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt; white-space: nowrap;">'.$linha['cia_nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt; white-space: nowrap;">'.($linha['contato_funcao'] ? $linha['contato_funcao'] : '&nbsp;').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td></tr>';
						if ($saida) $saida='<table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Órgão</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Cargo / Função</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td></tr>'.$saida.'</table>';
						break;
						
					case 'projeto_abertura_gerente_projeto':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_abertura');
						$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=projeto_abertura.projeto_abertura_gerente_projeto');
						$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
						$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_email, contato_email2, contato_funcao');
						$sql->adOnde('projeto_abertura_id = '.(int)(isset($dados['projeto_abertura_id']) ? $dados['projeto_abertura_id'] : 0));
						$linha = $sql->linha();
						$sql->limpar();	
						if($linha['nome']) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt; white-space: nowrap;">'.$linha['nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt; white-space: nowrap;">'.($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'].($linha['contato_tel'] && $linha['contato_tel2'] ? '<br>' : '').($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'].(!$linha['contato_tel'] && !$linha['contato_tel2'] ? '&nbsp;' : '').'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt; white-space: nowrap;">'.$linha['contato_email'].($linha['contato_email'] && $linha['contato_email2'] ? '<br>' : '').$linha['contato_email2'].(!$linha['contato_email'] && !$linha['contato_email2'] ? '&nbsp;' : '').'</td></tr>';
						if ($saida) $saida='<table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Nome</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Telefone(s)</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">E-mail</td></tr>'.$saida.'</table>';
						break;	
							
					
					
					case 'projeto_qualidade_entrega':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_qualidade_entrega');
						$sql->adOnde('projeto_qualidade_entrega_projeto = '.(int)(isset($dados['projeto_qualidade_projeto']) ? $dados['projeto_qualidade_projeto'] : 0));
						$lista = $sql->lista();
						$sql->limpar();	
						foreach($lista as $linha)	if($linha['projeto_qualidade_entrega_entrega']) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_qualidade_entrega_entrega'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_qualidade_entrega_criterio'].'</td></tr>';
						if ($saida) $saida='<table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Entrega</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Critérios de qualidade</td></tr>'.$saida.'</table>';
						break;	
					
					case 'projeto_comunicacao_evento':
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_comunicacao_evento');
						$sql->adOnde('projeto_comunicacao_evento_projeto = '.(int)(isset($dados['projeto_comunicacao_projeto']) ? $dados['projeto_comunicacao_projeto'] : 0));
						$lista = $sql->lista();
						$sql->limpar();	
						foreach($lista as $linha)	if($linha['projeto_comunicacao_evento_evento']) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_comunicacao_evento_evento'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_comunicacao_evento_objetivo'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_comunicacao_evento_responsavel'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_comunicacao_evento_publico'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_comunicacao_evento_canal'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_comunicacao_evento_periodicidade'].'</td></tr>';
						if ($saida) $saida='<table border=1 cellpadding="2" cellspacing="0"><tr><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Evento</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Objetivo</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Responsável</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Público alvo</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Canal</td><td bgcolor="#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'" align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">Periodicidade</td></tr>'.$saida.'</table>';
						break;	
					
					
					
					case 'projeto_risco_tipo':
						$saida='';
						$probabilidade=array(1=>'Baixa', 2=>'Média', 3=>'Alta');
						$impacto=array(1=>'Baixo', 2=>'Médio', 3=>'Alto');
						$saida2='';
						$sql = new BDConsulta;
						$sql->adTabela('projeto_risco_tipo');
						$sql->esqUnir('usuarios','usuarios','projeto_risco_tipo_usuario=usuario_id');
						$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
						$sql->adCampo('projeto_risco_tipo.*, IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome');
						$sql->adOnde('projeto_risco_tipo_projeto='.(int)(isset($dados['projeto_risco_projeto']) ? $dados['projeto_risco_projeto'] : 0));
						$sql->adOrdem('projeto_risco_tipo_ordem ASC');
						$tipos=$sql->Lista();
						if ($tipos && count($tipos)) {
							$saida2.= '<tr>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Descrição</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Cat.</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Tipo</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Consequência</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Probab.</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Impacto</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Severidade</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Ação</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Gatilho</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Resposta</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Resp.</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#'.($config['anexo_civil']=='mpog' ? 'cccccc' : 'e6e6e6').'">Status</td>';
							$saida2.='</tr>';
							}
						foreach ($tipos as $tipo) {
							$saida2.='<tr>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_descricao'] ? $tipo['projeto_risco_tipo_descricao'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_categoria'] ? $tipo['projeto_risco_tipo_categoria'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_tipo'] ? $tipo['projeto_risco_tipo_tipo'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_consequencia'] ? $tipo['projeto_risco_tipo_consequencia'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_probabilidade'] ? $probabilidade[$tipo['projeto_risco_tipo_probabilidade']] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_impacto'] ? $impacto[$tipo['projeto_risco_tipo_impacto']] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_severidade'] ? $tipo['projeto_risco_tipo_severidade'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_acao'] ? $tipo['projeto_risco_tipo_acao'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_gatilho'] ? $tipo['projeto_risco_tipo_gatilho'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_resposta'] ? $tipo['projeto_risco_tipo_resposta'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_usuario'] ? $tipo['nome'] : '&nbsp;').'</td>';
							$saida2.='<td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($tipo['projeto_risco_tipo_status'] ? $tipo['projeto_risco_tipo_status'] : '&nbsp;').'</td>';
							$saida2.='</tr>';
							}
						if (count($tipos)) $saida='<tr><td><table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="100%">'.$saida2.'</table></td></tr>';
						break;	
					
					
					
					case 'dicionario_eap':
						global $Aplic;
						$saida='';
						$sql = new BDConsulta();
						$sql->adTabela('projeto_qualidade_entrega');
						$sql->adOnde('projeto_qualidade_entrega_projeto = '.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
						$lista = $sql->lista();
						$sql->limpar();	
						foreach($lista as $linha)	if($linha['projeto_qualidade_entrega_entrega']) $saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_qualidade_entrega_entrega'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['projeto_qualidade_entrega_criterio'].'</td></tr>';
						
						

						$saida= '<tr><td width="100%"><table cellpadding=0 cellspacing=0 class="tbl1" align=center>';
						$saida.='<tr><th  style="font-family:Times New Roman, Times, serif; font-size:12pt;">Pacotes / Atividades</th><th style="font-family:Times New Roman, Times, serif; font-size:12pt;">Definição</th></tr>';
						
						$sql = new BDConsulta;
						$sql->adTabela('tarefas');
						$sql->adCampo('tarefa_id, tarefa_nome, tarefa_superior, tarefa_descricao');
						$sql->adOnde('tarefa_projeto='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
						$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC');
						$linhas=$sql->Lista();
						$sql->limpar();
						$cont=0;
						foreach($linhas as $linha){
							if ($linha['tarefa_id']==$linha['tarefa_superior']){
								$numeracao='1.'.(++$cont);
								$saida.= '<tr><td  style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4">'.$numeracao.' '.$linha['tarefa_nome'].'</td><td  style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4">'.$linha['tarefa_descricao'].'</td></tr>';
								$saida.= eap_filhos($linha['tarefa_id'], $numeracao);
								}
							}
							
						$saida.= '</table></td></tr>';
						break;
					
					
				case 'estrutura_analitica':	
					
					$sql = new BDConsulta;
					
					$sql->adTabela('projetos');
					$sql->esqUnir('cias','cias','cias.cia_id=projeto_cia');
					$sql->adCampo('cia_cabacalho, projeto_responsavel, projeto_supervisor, projeto_nome, cia_cidade, projeto_cia');
					$sql->adOnde('projeto_id='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
					$dados_projeto=$sql->Linha();
					$sql->limpar();
					
					$sql->adTabela('tarefas');
					$sql->adCampo('tarefa_id, tarefa_nome, tarefa_superior');
					$sql->adOnde('tarefa_projeto='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
					$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC');
					$linhas=$sql->Lista();
					$sql->limpar();
					$cont=0;
					$espaco='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					$tab='&nbsp;&nbsp;';
					$saida='<tr><td width="100%"><table cellpadding="2" align=left><tr><td  style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$espaco.'1. '.$dados_projeto['projeto_nome'].'</td></tr>';
					
					foreach($linhas as $linha){
						if ($linha['tarefa_id']==$linha['tarefa_superior']){
							$numeracao='1.'.(++$cont);
							$saida.= '<tr><td  style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$espaco.$tab.$numeracao.' '.$linha['tarefa_nome'].'</td></tr>';
							$saida.= wbs_filhos($linha['tarefa_id'], $numeracao);
							}
						}
						
					
					$saida.= '</table></td></tr>';
					break;
					
					
				case 'aquisicoes_projeto':
					$tab='&nbsp;&nbsp;&nbsp;&nbsp;';
					$sql = new BDConsulta;
					$sql->adTabela('tarefas');
					$sql->adCampo('tarefa_id, tarefa_nome, tarefa_superior, tarefa_descricao');
					$sql->adOnde('tarefa_projeto='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
					$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC');
					$linhas=$sql->Lista();
					$sql->limpar();
					$cont=0;
					
					$saida='<tr><td width="100%"><table border=1 cellpadding=1 cellspacing="0" align=left><tr><th>Nº</th><th>Tarefa</th><th>Item</th><th>ND</th><th>Valor</th></tr>';
					foreach($linhas as $linha){
						if ($linha['tarefa_id']==$linha['tarefa_superior']){
							$numeracao='1.'.(++$cont);
							$saida.='<tr  style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#e4e4e4"><td>'.$numeracao.'</td><td  style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['tarefa_nome'].'</td>'.custos($linha['tarefa_id'], 'gasto').'</tr>';
							$saida.=aquisicao_filhos($linha['tarefa_id'], $numeracao, 'gasto');
							}
						}
					$total_geral=0;	
					foreach($total as $chave => $valor_por_nd){
						$total_geral=$total_geral+$valor_por_nd;
						$saida.='<tr  style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4"><td colspan=3>&nbsp;</td><td align=right  style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$chave.'</td><td align=right  style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.number_format($valor_por_nd, 2, ',', '.').'</td>'.($tipo=='custo' ? '<td>&nbsp;</td>' : '').'</tr>';
						}	
					$saida.='<tr  style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4"><td colspan=4 align=right  style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Total</b></td><td align=right  style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>'.number_format($total_geral, 2, ',', '.').'</b></td>'.($tipo=='custo' ? '<td>&nbsp;</td>' : '').'</tr>';
					$saida.='</table></td></tr>';

					break;

					
				case 'equipe_projeto':
					$saida='';
					$tab='&nbsp;&nbsp;&nbsp;&nbsp;';
					$sql = new BDConsulta;
					
					$sql->adTabela('projetos');
					$sql->esqUnir('cias','cias','cias.cia_id=projeto_cia');
					$sql->esqUnir('municipios','municipios','municipio_id=cia_cidade');
					$sql->adCampo('cia_cabacalho, projeto_responsavel, projeto_supervisor, projeto_nome, municipio_nome AS cia_cidade, projeto_cia');
					$sql->adOnde('projeto_id='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
					$dados_projeto=$sql->Linha();
					$sql->limpar();
					
					$sql->adTabela('projetos');
					$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=projeto_responsavel');
					$sql->esqUnir('contatos','contatos','usuarios.usuario_contato=contatos.contato_id');
					$sql->esqUnir('cias','cias','cias.cia_id=contato_cia');
					$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome_responsavel, cia_nome, contato_email, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2');
					$sql->adOnde('projeto_id='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
					$dados_gerente=$sql->Linha();
					$sql->limpar(); 
					
					$sql->adTabela('projetos');
					$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=projeto_supervisor');
					$sql->esqUnir('contatos','contatos','usuarios.usuario_contato=contatos.contato_id');
					$sql->esqUnir('cias','cias','cias.cia_id=contato_cia');
					$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome_responsavel, cia_nome, contato_email, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2');
					$sql->adOnde('projeto_id='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
					$dados_supervisor=$sql->Linha();
					$sql->limpar(); 
				
					$sql->adTabela('projeto_integrantes');
					$sql->esqUnir('contatos','contatos','projeto_integrantes.contato_id=contatos.contato_id');
					$sql->esqUnir('cias','cias','cias.cia_id=contato_cia');
					$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome_responsavel, projeto_integrante_competencia, cia_nome, contato_email, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2');
					$sql->adOnde('projeto_id='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
					$linhas=$sql->Lista();
					$sql->limpar(); 
				
					$nr=0;
					
					if ($dados_projeto['projeto_responsavel'] || $dados_supervisor['nome_responsavel'] || count($linhas)) {
						$saida.='<tr><td><table cellpadding=0 cellspacing=0><tr><td><table cellpadding=0 cellspacing=0 class="tbl1" width="100%"><tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4">Nome</td><td  style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4">'.$config['organizacao'].'</td><td  style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4">Função na Equipe</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4">E-mail</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4">Telefone</td></tr>';
						if ($dados_projeto['projeto_responsavel'])$saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$dados_gerente['nome_responsavel'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$dados_gerente['cia_nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.ucfirst($config['gerente']).' d'.$config['genero_projeto'].' '.$config['projeto'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$dados_gerente['contato_email'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($dados_gerente['contato_tel'] ? ($dados_gerente['contato_dddtel']? '('.$dados_gerente['contato_dddtel'].')'.$dados_gerente['contato_tel'] : $dados_gerente['contato_tel']) : ($dados_gerente['contato_dddtel2']? '('.$dados_gerente['contato_dddtel2'].')'.$dados_gerente['contato_tel2'] : $dados_gerente['contato_tel2'])).'</td></tr>';
						if ($dados_projeto['projeto_supervisor'])$saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$dados_supervisor['nome_responsavel'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$dados_supervisor['cia_nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.ucfirst($config['supervisor']).' d'.$config['genero_projeto'].' '.$config['projeto'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$dados_supervisor['contato_email'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($dados_supervisor['contato_tel'] ? ($dados_supervisor['contato_dddtel']? '('.$dados_supervisor['contato_dddtel'].')'.$dados_supervisor['contato_tel'] : $dados_supervisor['contato_tel']) : ($dados_supervisor['contato_dddtel2']? '('.$dados_supervisor['contato_dddtel2'].')'.$dados_supervisor['contato_tel2'] : $dados_supervisor['contato_tel2'])).'</td></tr>';
						foreach($linhas as $dados_integrante)$saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$dados_integrante['nome_responsavel'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$dados_integrante['cia_nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$dados_integrante['projeto_integrante_competencia'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$dados_integrante['contato_email'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($dados_integrante['contato_tel'] ? ($dados_integrante['contato_dddtel']? '('.$dados_integrante['contato_dddtel'].')'.$dados_integrante['contato_tel'] : $dados_integrante['contato_tel']) : ($dados_integrante['contato_dddtel2']? '('.$dados_integrante['contato_dddtel2'].')'.$dados_integrante['contato_tel2'] : $dados_integrante['contato_tel2'])).'</td></tr>';
						$saida.='</table></td></tr></table></td></tr>';

						}
					break;	
					
				case 'cronograma_marco':
					
					if(!$Aplic->pdf_print){
						$src = '?m=tarefas&a=gantt&sem_cabecalho=1&projeto_id='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0).'&somente_marco=1'."&width=' + ((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95) + '";
						$saida='<tr><td align="center"><script>document.write(\'<img src=\"'.$src.'\">\')</script></td></tr>';
						}
					else{
						$src = BASE_URL.'/pdfimg.php?m=tarefas&a=gantt&sem_cabecalho=1&projeto_id='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0).'&somente_marco=1';
						$saida='<tr><td align="center"><img src="'.$src.'"></td></tr>';
						}	
					break;	
	
				case 'responsabilidades':	
					$sql = new BDConsulta;
					$sql->adTabela('projeto_integrantes');
					$sql->adCampo('projeto_integrante_competencia, projeto_integrante_atributo, contato_id');
					$sql->adOnde('projeto_id = '.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
					$sql->adOrdem('ordem ASC');
					$contatos = $sql->ListaChave('contato_id');
					$sql->limpar();
					$saida='';
					if (count($contatos)) {
						$saida.='<tr><td><table border=1 cellpadding="2" cellspacing="0">';
						$saida.='<tr align="center"><td align="left" style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4">Nome</font></td><td align="left" style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4">Função na Equipe</td><td align="left" style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4">Responsabilidades</td></tr>';
						foreach ($contatos as $contato_id => $contato_data) {
							$saida.='<tr align="center">';
							$saida.='<td align="left">'.nome_contato($contato_id).'</td>';
							$saida.='<td align="left">'.$contato_data['projeto_integrante_competencia'].'</td>';
							$saida.='<td align="left">'.$contato_data['projeto_integrante_atributo'].'</td>';
							$saida.='</tr>';
							}
						$saida.='</table></td></tr>';
						} 
					break;
					
					
				case 'projeto_orcamento':
					$sql = new BDConsulta;

					$sql->adTabela('projetos');
					$sql->esqUnir('tarefas','tarefas','tarefa_projeto=projeto_id');
					$sql->esqUnir('recurso_tarefas','recurso_tarefas','recurso_tarefas.tarefa_id=tarefas.tarefa_id');
					$sql->esqUnir('recursos','recursos','recurso_tarefas.recurso_id=recursos.recurso_id');
					$sql->adCampo('recurso_nome, recurso_tarefas.recurso_quantidade');
					$sql->adOnde('projetos.projeto_id='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
					$sql->adOnde('recurso_tipo=5');
					$orcamento=$sql->lista();
					$sql->limpar();

					$soma=0;
					$saida=$dados['projeto_orcamento'];
					if (count($orcamento)){
						$saida.='<tr><td><table border=1 cellpadding="2" cellspacing="0"><tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#e4e4e4">Nome do recurso</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#e4e4e4">Valor</td></tr>';
						foreach($orcamento as $linha) {
							$soma+=$linha['recurso_quantidade'];
							$saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['recurso_nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.number_format((float)$linha['recurso_quantidade'], 2, ',', '.').'</td></tr>';			
							}
						$saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">Total</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.number_format($soma, 2, ',', '.').'</td></tr>';
								
						$saida.='</table></td></tr>';
						$saida.='<tr><td>&nbsp;</td></tr>';
						}
											
					$tab='&nbsp;&nbsp;&nbsp;&nbsp;';

					$sql->adTabela('tarefas');
					$sql->adCampo('tarefa_id, tarefa_nome, tarefa_superior, tarefa_descricao');
					$sql->adOnde('tarefa_projeto='.(int)(isset($dados['projeto_id']) ? $dados['projeto_id'] : 0));
					$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC');
					$linhas=$sql->Lista();
					$sql->limpar();
					$cont=0;
					
					$saida.='<tr><td width="100%"><table border=1 cellpadding=1 cellspacing=0 align=left><tr><th>Nº</th><th>Tarefa</th><th>Item</th><th>ND</th><th>Valor</th></tr>';
					foreach($linhas as $linha){
						if ($linha['tarefa_id']==$linha['tarefa_superior']){
							$numeracao='1.'.(++$cont);
							$saida.='<tr  style="font-family:Times New Roman, Times, serif; font-size:12pt;background-color:#e4e4e4"><td>'.$numeracao.'</td><td  style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['tarefa_nome'].'</td>'.custos($linha['tarefa_id'], 'estimado').'</tr>';
							$saida.=aquisicao_filhos($linha['tarefa_id'], $numeracao, 'estimado');
							}
						}
					$total_geral=0;	
					foreach($total as $chave => $valor_por_nd){
						$total_geral=$total_geral+$valor_por_nd;
						$saida.='<tr  style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4"><td colspan=3>&nbsp;</td><td align=right  style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$chave.'</td><td align=right  style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.number_format($valor_por_nd, 2, ',', '.').'</td>'.($tipo=='custo' ? '<td>&nbsp;</td>' : '').'</tr>';
						}	
					$saida.='<tr  style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#e4e4e4"><td colspan=4 align=right  style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>Total</b></td><td align=right  style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>'.number_format($total_geral, 2, ',', '.').'</b></td>'.($tipo=='custo' ? '<td>&nbsp;</td>' : '').'</tr>';
					$saida.='</table></td></tr>';
					
		
					break;		
					}	
								
				if (!$saida) $saida='&nbsp;';
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'data':
				$saida=(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : '');
				if($saida) $saida=retorna_data($saida, false);
				if (!$saida) $saida='&nbsp;';
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'data_hora':
				$saida=(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : '');
				if($saida) $saida=retorna_data($saida);
				if (!$saida) $saida='&nbsp;';
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'hora_de_data':
				$saida=(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : '');
				
				if($saida) {
					$data = new CData($saida);
					$saida=$data->format('%H:%M');
					}
				
				if (!$saida) $saida='&nbsp;';
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'data_extenso':
				$saida=(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : '');
				
				if($saida) {
					$df = '%d/%m/%Y';
					$data = new CData($saida);
					$nome_meses=array('01'=>'janeiro', '02'=>'fevereiro', '03'=>'março', '04'=>'abril', '05'=>'maio', '06'=>'junho', '07'=>'julho', '08'=>'agosto', '09'=>'setembro', '10'=>'outubro', '11'=>'novembro', '12'=>'dezembro');
					$dia_mes=array('01'=>'1º', '02'=>'2', '03'=>'3', '04'=>'4', '05'=>'5', '06'=>'6', '07'=>'7', '08'=>'8', '09'=>'9');
					if ($data->dia < 10) $dia=$dia_mes[$data->dia];
					else  $dia=$data->dia;
					$saida=$dia.' de '.$nome_meses[$data->mes].' de '.$data->ano;
					}

				if (!$saida) $saida='&nbsp;';
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'logo': 
				
				$sql = new BDConsulta();
				$sql->adTabela('cias');
				$sql->adCampo('cia_logo');
				$sql->adOnde('cia_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$saida = $sql->resultado(); 

				if (!$saida || !file_exists(($config['url_arquivo'] ? $config['url_arquivo'] : '.').'/arquivos/organizacoes/'.$saida)) $saida='';
				else $saida='<img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : '.').'/arquivos/organizacoes/'.$saida.'" alt="" border=0 />';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			
		
			case 'brasao': 
				$saida='<img src="'.$Aplic->gpweb_brasao.'" alt="" border=0 />';
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			
			case 'dept_usuario':
				$sql = new BDConsulta();
				$sql->adTabela('usuarios');
				$sql->esqUnir('contatos','contatos','usuario_contato=contato_id');
				$sql->esqUnir('depts','depts','depts.dept_id=contato_dept');
				$sql->adCampo('dept_nome');
				$sql->adOnde('usuario_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$saida = $sql->resultado();
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'telefone_usuario':
				$sql = new BDConsulta();
				$sql->adTabela('usuarios');
				$sql->esqUnir('contatos','contatos','usuario_contato=contato_id');
				$sql->adCampo('contato_dddtel, contato_tel, contato_dddtel2, contato_tel2');
				$sql->adOnde('usuario_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$linha = $sql->linha();
				if ($linha['contato_tel']) $saida=($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'];
				elseif ($linha['contato_tel2']) $saida=($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'];
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'email_usuario':
				$sql = new BDConsulta();
				$sql->adTabela('usuarios');
				$sql->esqUnir('contatos','contatos','usuario_contato=contato_id');
				$sql->adCampo('contato_email');
				$sql->adOnde('usuario_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$saida = $sql->resultado();
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'funcao_usuario':
				$sql = new BDConsulta();
				$sql->adTabela('usuarios');
				$sql->esqUnir('contatos','contatos','usuario_contato=contato_id');
				$sql->adCampo('contato_funcao');
				$sql->adOnde('usuario_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$saida = $sql->resultado();
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'nome_usuario':
				$sql = new BDConsulta();
				$sql->adTabela('usuarios');
				$sql->esqUnir('contatos','contatos','usuario_contato=contato_id');
				$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').')');
				$sql->adOnde('usuario_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$saida = $sql->resultado();
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			
			case 'nome_funcao_usuario':
				$sql = new BDConsulta();
				$sql->adTabela('usuarios');
				$sql->esqUnir('contatos','contatos','usuario_contato=contato_id');
				$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_funcao');
				$sql->adOnde('usuario_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$linha = $sql->linha();
				$saida=$linha['nome'].($linha['contato_funcao'] ? ' - '.$linha['contato_funcao'] : '');
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'dept_contato':
				$sql = new BDConsulta();
				$sql->adTabela('contatos');
				$sql->esqUnir('depts','depts','depts.dept_id=contato_dept');
				$sql->adCampo('dept_nome');
				$sql->adOnde('contato_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$saida = $sql->resultado();
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'telefone_contato':
				$sql = new BDConsulta();
				$sql->adTabela('contatos');
				$sql->adCampo('contato_dddtel, contato_tel, contato_dddtel2, contato_tel2');
				$sql->adOnde('contato_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$linha = $sql->linha();
				if ($linha['contato_tel']) $saida=($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'];
				elseif ($linha['contato_tel2']) $saida=($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'];
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'email_contato':
				$sql = new BDConsulta();
				$sql->adTabela('contatos');
				$sql->adCampo('contato_email');
				$sql->adOnde('contato_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$saida = $sql->resultado();
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'funcao_contato':
				$sql = new BDConsulta();
				$sql->adTabela('contatos');
				$sql->adCampo('contato_funcao');
				$sql->adOnde('contato_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$saida = $sql->resultado();
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'nome_contato':
				$sql = new BDConsulta();
				$sql->adTabela('contatos');
				$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').')');
				$sql->adOnde('contato_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$saida = $sql->resultado();
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'cabecalho':
				$sql = new BDConsulta();
				$sql->adTabela('cias');
				$sql->adCampo('cia_cabacalho');
				$sql->adOnde('cia_id = '.(int)(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : 0));
				$saida = $sql->resultado();
				if (!$saida) $saida='&nbsp;';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			
			
			case 'texto':
				$saida=(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : '');
				if (!$saida) $saida='&nbsp;';
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
				
				
			case 'bloco_simples':
				$texto=(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : '');
				$saida=$texto;
				if (!$saida) $saida='&nbsp;';
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
				
				
			case 'marcar_x':
				$saida=(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : '');
				$saida=($saida ? 'X' : '&nbsp;');
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;	
				
			case 'numero_tres_digitos':
				$saida=(isset($dados[$this->campo[$posicao]['dados']]) ? $dados[$this->campo[$posicao]['dados']] : '');
				$saida=($saida < 100 ? '0' : '').($saida < 10 ? '0' : '').($saida ? $saida : '&nbsp;');
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
				
					
					
				}	
		return $saida;
		}
	
	function quantidade(){
		return count($this->campo);
		}
		
	}
	
	function eap_filhos($pai_id, $pai_numeracao){
		global $Aplic;
		$sql = new BDConsulta;
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_id, tarefa_nome, tarefa_superior, tarefa_descricao');
		$sql->adOnde('tarefa_superior='.$pai_id.' AND tarefa_id !='.$pai_id);
		$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC');
		$linhas=$sql->Lista();
		$sql->limpar();
		$cont_int=0;
		$saida='';
		foreach($linhas as $linha){
				$numeracao=$pai_numeracao.'.'.(++$cont_int);
				$saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$numeracao.' '.$linha['tarefa_nome'].'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['tarefa_descricao'].'</td></tr>';
				$saida.=eap_filhos($linha['tarefa_id'], $numeracao);
			}
		return $saida;		
		}
		
function wbs_filhos($pai_id, $pai_numeracao){
	global $Aplic;
	$espaco='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	$tab='&nbsp;&nbsp;';
	$sql = new BDConsulta;
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_id, tarefa_nome, tarefa_superior');
	$sql->adOnde('tarefa_superior='.$pai_id.' AND tarefa_id !='.$pai_id);
	$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC');
	$linhas=$sql->Lista();
	$sql->limpar();
	$cont_int=0;
	$saida='';
	foreach($linhas as $linha){
			$numeracao=$pai_numeracao.'.'.(++$cont_int);
			$saida.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$espaco.$tab.$tab.$numeracao.' '.$linha['tarefa_nome'].'</td></tr>';
			$saida.=wbs_filhos($linha['tarefa_id'], $numeracao);
		}
	return $saida;		
	}		
		
		
		
function aquisicao_filhos($pai_id, $pai_numeracao, $tipo){
	global $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_id, tarefa_nome, tarefa_superior, tarefa_descricao');
	$sql->adOnde('tarefa_superior='.$pai_id.' AND tarefa_id !='.$pai_id);
	$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC');
	$linhas=$sql->Lista();
	$sql->limpar();
	$cont_int=0;
	$saida='';
	foreach($linhas as $linha){
			$numeracao=$pai_numeracao.'.'.(++$cont_int);
			$saida.='<tr style="font-family:Times New Roman, Times, serif; font-size:12pt; background-color:#ffffff"><td>'.$numeracao.'</td><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['tarefa_nome'].'</td>'.custos($linha['tarefa_id'], $tipo).'</tr>';
			$saida.=aquisicao_filhos($linha['tarefa_id'], $numeracao, $tipo);
		}
	return $saida;		
	}

function custos($tarefa_id, $tipo){
	global $total, $Aplic, $config;
	$sql = new BDConsulta;

	if ($tipo=='estimado'){
		$sql->adTabela('tarefa_custos');
		$sql->adCampo('tarefa_custos_nome as nome, tarefa_custos_quantidade as quantidade, tarefa_custos_custo as custo, tarefa_custos_nd as nd, tarefa_custos_data_limite as data_limite');
		$sql->adOnde('tarefa_custos_tarefa='.$tarefa_id);
		if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
		$sql->adOrdem('tarefa_custos_ordem ASC');
		}
	else{
		$sql->adTabela('tarefa_gastos');
		$sql->adCampo('tarefa_gastos_nome as nome, tarefa_gastos_quantidade as quantidade, tarefa_gastos_custo as custo, tarefa_gastos_nd as nd, 0 as data_limite');
		$sql->adOnde('tarefa_gastos_tarefa='.$tarefa_id);
		if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
		$sql->adOrdem('tarefa_gastos_ordem ASC');
		}
		
	$linhas=$sql->Lista();
	$sql->limpar();
	$cont_int=0;
	$descricao='';
	$nd='';
	$valor='';
	$data='';
	foreach($linhas as $linha){
		$total[$linha['nd']]=(isset($total[$linha['nd']]) ? $total[$linha['nd']] : 0)+($linha['quantidade']*$linha['custo']);
		$descricao.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nome'].'</td></tr>';	
		$nd.='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$linha['nd'].'</td></tr>';
		$valor.='<tr><td align="right" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.number_format(($linha['quantidade']*$linha['custo']), 2, ',', '.').'</td></tr>';
		if ($tipo=='custo') $data='<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.($linha['data_limite']? retorna_data($linha['data_limite'],false):'').'</td></tr>';
		}
	return '<td><table cellspacing=0 cellpadding=0>'.$descricao.'</table></td><td><table cellspacing=0 cellpadding=0 align="right">'.$nd.'</table></td><td><table cellspacing=0 cellpadding=0 >'.$valor.'</table></td>'.($tipo=='custo' ? '<td><table cellspacing=0 cellpadding=0>'.$data.'</table>' : '');		
	}		
		
				
?>
