<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));
$baseline_id = intval(getParam($_REQUEST, 'baseline_id', 0));

echo '<form name="mudar" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';


$botoesTitulo = new CBlocoTitulo('Imprimir Documento d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'impressao.png');
$botoesTitulo->mostrar();
$sql = new BDConsulta;
	
echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=2 width="100%" class="std">';

if ($Aplic->profissional) echo '<tr><td colspan=20><table><tr><td>'.dica('Tela', 'Exibir o relatório na tela do navegador.').'<input type="radio" id="tela" name="exibicao" value="tela" checked />Tela'.dicaF().dica('PDF','Enviar relatório para arquivo PDF.').'<input type="radio" id="pdf" name="exibicao" value="pdf" />PDF'.dicaF().'</td></tr></table></td></tr>';	
else echo '<input type="hidden" name="pdf" id="pdf" value="" />';

echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=ver&imprimir_detalhe=1&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Detalhamento</a></td></tr>';
echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_visao_geral&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Visão geral</a></td></tr>';


if ($Aplic->profissional) {
	
	$lista_projeto=0;
	if ($Aplic->profissional){
		$vetor=array($projeto_id => $projeto_id);
		portfolio_projetos($projeto_id, $vetor);
		$lista_projeto=implode(',',$vetor);
		}
	
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas');
	$sql->adCampo('count(tarefa_id)');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$existe=$sql->resultado();
	$sql->limpar();
	if ($existe) {
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'&jquery=1&m=projetos&a=financeiro_pizza_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Estágios da despesa (empenho, liquidação e pagamento)</a></td></tr>';
		if ($config['projeto_siafi'] && $Aplic->modulo_ativo('financeiro')) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'&jquery=1&m=projetos&a=financeiro_siafi_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Estágios da despesa vs anexos do SIAFI</a></td></tr>';
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_tarefas_atraso_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">'.ucfirst($config['tarefas']).' em atraso</a></td></tr>';
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_geral_tarefas_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Geral d'.$config['genero_tarefa'].'s '.$config['tarefas'].'</a></td></tr>';
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_status_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Status d'.$config['genero_tarefa'].'s '.$config['tarefas'].'</a></td></tr>';
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_inconsistencia_status_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Inconsistência do status d'.$config['genero_tarefa'].'s '.$config['tarefas'].'</a></td></tr>';
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_cha_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Competências dos envolvidos n'.$config['genero_tarefa'].'s '.$config['tarefas'].'</a></td></tr>';
		}

	$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_stakeholder');
	$sql->adCampo('count(projeto_stakeholder_id)');
	$sql->adOnde('projeto_stakeholder_projeto='.(int)$projeto_id);
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$existe=$sql->resultado();
	$sql->limpar();
	if ($existe) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=stakeholder_pro_imprimir&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Matriz de Stakeholders</a></td></tr>';
	echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_consolidacao&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Consolidação d'.$config['genero_projeto'].' '.$config['projeto'].'</a></td></tr>';
	
	
	
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'priorizacao');
	$sql->adCampo('count(priorizacao_projeto)');
	$sql->adOnde('priorizacao_projeto='.(int)$projeto_id);
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$existe=$sql->resultado();
	$sql->limpar();
	
	if ($existe) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=priorizacao_imprimir_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Priorização</a></td></tr>';
	}
	
	
if ($config['anexo_mpog']){

	$sql->adTabela('demandas');
	$sql->adCampo('demanda_id, demanda_viabilidade, demanda_termo_abertura, demanda_caracteristica_projeto');
	$sql->adOnde('demanda_projeto='.(int)$projeto_id);
	$linha1=$sql->linha();
	$sql->limpar();
	if ($linha1['demanda_id']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=demanda_imprimir&demanda_id='.$linha1['demanda_id'].'\');">Documento de Oficialização da Demanda (DOD)</a></td></tr>';
	if ($linha1['demanda_caracteristica_projeto']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=demanda_analise_imprimir&demanda_id='.$linha1['demanda_id'].'\');">Mensuração do Tamanho d'.$config['genero_projeto'].' '.$config['projeto'].' (MTP)</a></td></tr>';
	if ($linha1['demanda_viabilidade']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=viabilidade_imprimir&projeto_viabilidade_id='.$linha1['demanda_viabilidade'].'\');">Análise de Viabilidade d'.$config['genero_projeto'].' '.$config['projeto'].' (AVP)</a></td></tr>';
	if ($linha1['demanda_termo_abertura']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=termo_abertura_imprimir&projeto_abertura_id='.$linha1['demanda_termo_abertura'].'\');">Termo de Abertura d'.$config['genero_projeto'].' '.$config['projeto'].' (TAP)</a></td></tr>';
	
	echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=gerenciamento_imprimir&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Plano de Gerenciamento d'.$config['genero_projeto'].' '.$config['projeto'].' (PGP)</a></td></tr>';
	
	$sql->adTabela('projeto_qualidade');
	$sql->adCampo('projeto_qualidade_usuario');
	$sql->adOnde('projeto_qualidade_projeto='.(int)$projeto_id);
	$linha3=$sql->linha();
	$sql->limpar();
	if ($linha3['projeto_qualidade_usuario']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=qualidade_imprimir&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Plano de Qualidade (PQ)</a></td></tr>';
	
	$sql->adTabela('projeto_comunicacao');
	$sql->adCampo('projeto_comunicacao_usuario');
	$sql->adOnde('projeto_comunicacao_projeto='.(int)$projeto_id);
	$linha4=$sql->linha();
	$sql->limpar();
	if ($linha4['projeto_comunicacao_usuario']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=comunicacao_imprimir&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Plano de Comunicação (PC)</a></td></tr>';

	$sql->adTabela('projeto_risco');
	$sql->adCampo('projeto_risco_usuario');
	$sql->adOnde('projeto_risco_projeto='.(int)$projeto_id);
	$linha5=$sql->linha();
	$sql->limpar();
	if ($linha5['projeto_risco_usuario']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=risco_imprimir&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Plano de Gerenciamento de Riscos (PGR)</a></td></tr>';
	
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('count(pratica_indicador_id)');
	$sql->adOnde('pratica_indicador_projeto 	='.(int)$projeto_id);
	$indicadores = $sql->Resultado();
	$sql->limpar();
	if ($indicadores) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=indicadores_imprimir&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Matriz de Controle de Indicadores</a></td></tr>';

	$sql->adTabela('projeto_encerramento');
	$sql->adCampo('projeto_encerramento_responsavel');
	$sql->adOnde('projeto_encerramento_projeto='.(int)$projeto_id);
	$linha7=$sql->linha();
	$sql->limpar();
	if ($linha7['projeto_encerramento_responsavel']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=encerramento_imprimir&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Termo de Encerramento de Projeto (TEP)</a></td></tr>';
	
	$sql->adTabela('causa_efeito');
	$sql->esqUnir('causa_efeito_projetos','causa_efeito_projetos','causa_efeito_projetos.causa_efeito_id=causa_efeito.causa_efeito_id');
	$sql->adCampo('causa_efeito.causa_efeito_id, causa_efeito_nome');
	$sql->adOnde('projeto_id='.(int)$projeto_id);
	$vetor_mudanca = array(0 => '')+$sql->listaVetorChave('causa_efeito_id','causa_efeito_nome');
	$sql->limpar();
	if (count($vetor_mudanca)>1) echo '<tr><td colspan=20>Árvore de Problemas:'.selecionaVetor($vetor_mudanca,'causa_efeito_id','class="texto" onchange="imprimir_causa_efeito()"').'</td></tr>';
	
	$sql->adTabela('projeto_mudanca');
	$sql->adCampo('projeto_mudanca_id, projeto_mudanca_numero');
	$sql->adOnde('projeto_mudanca_projeto='.(int)$projeto_id);
	$vetor_mudanca = array(0 => '')+$sql->listaVetorChave('projeto_mudanca_id','projeto_mudanca_numero');
	$sql->limpar();
	if (count($vetor_mudanca)>1) echo '<tr><td colspan=20>Formulário de Solicitação de Mudanças (FSM):'.selecionaVetor($vetor_mudanca,'projeto_mudanca_id','class="texto" onchange="imprimir_solicitacao_mudanca();"').'</td></tr>';
	
	$sql->adTabela('projeto_recebimento');
	$sql->adCampo('projeto_recebimento_id, projeto_recebimento_numero');
	$sql->adOnde('projeto_recebimento_projeto='.(int)$projeto_id);
	$vetor_recebimento = array(0 => '')+$sql->listaVetorChave('projeto_recebimento_id','projeto_recebimento_numero');
	$sql->limpar();
	if (count($vetor_recebimento)>1) echo '<tr><td colspan=20>Termo de Recebimento de Produto/Serviço (TRPS):'.selecionaVetor($vetor_recebimento,'projeto_recebimento_id','class="texto" onchange="imprimir_termo_recebimento();"').'</td></tr>';
	
		
	$sql->adTabela('ata');
	$sql->adCampo('ata_id, ata_numero');
	$sql->adOnde('ata_projeto='.(int)$projeto_id);
	$vetor_ata = array(0 => '')+$sql->listaVetorChave('ata_id','ata_numero');
	$sql->limpar();
	if (count($vetor_ata)>1) echo '<tr><td colspan=20>Ata de Reunião:'.selecionaVetor($vetor_ata,'ata_id','class="texto" onchange="imprimir_ata_reuniao();"').'</td></tr>';
	
	$sql->adTabela('licao');
	$sql->adCampo('licao_id, licao_nome');
	$sql->adOnde('licao_projeto='.(int)$projeto_id);
	$vetor_licao = array(0 => '')+$sql->listaVetorChave('licao_id','licao_nome');
	$sql->limpar();
	if (count($vetor_licao)>1) echo '<tr><td colspan=20>Lições Aprendidas (LA):'.selecionaVetor($vetor_licao,'licao_id','class="texto" onchange="imprimir_licao_aprendida();"').'</td></tr>';
	
	}
	
if ($Aplic->profissional) {
	$dias=array(''=>'');
	for ($i = 1; $i <= 60; $i++)$dias[$i]=$i;
	echo '<tr><td colspan=20>Resumo d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).' com eventos no período:'.selecionaVetor($dias,'dias','class="texto" onchange="imprimir_resumo_evento();"').' dias</td></tr>';
	

	$sql->adTabela('projeto_area');
	$sql->adCampo('projeto_area_id, projeto_area_nome, projeto_area_obs');
	$sql->adOnde('projeto_area_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$sql->adOrdem('projeto_area_tarefa ASC');
	$lista_areas = array(0=>'', -1=>'Áreas d'.$config['genero_tarefa'].'s '.$config['tarefas'].' e '.$config['projeto'], -2=>'Áreas d'.$config['genero_tarefa'].'s '.$config['tarefas'], -3=>'Áreas d'.$config['genero_projeto'].' '.$config['projeto'])+$sql->listaVetorChave('projeto_area_id','projeto_area_nome');
	$sql->limpar();
	$tipo_area=array(
		'cor'=>'Cor das  áreas', 
		'fisico_tarefa'=>'Físico executado d'.$config['genero_tarefa'].'s '.$config['tarefas'], 
		'fisico_projeto'=>'Físico executado d'.$config['genero_projeto'].' '.$config['projeto'], 
		'status_tarefa'=>'Status d'.$config['genero_tarefa'].'s '.$config['tarefas'], 
		'status_projeto'=>'Status d'.$config['genero_projeto'].' '.$config['projeto']
		);
	if (count($lista_areas)>2) echo '<tr><td colspan=20>Áreas:'.selecionaVetor($lista_areas,'lista_areas','class="texto" onchange="imprimir_area();"').selecionaVetor($tipo_area,'tipo_area','class="texto"').'</td></tr>';
	
	
	
	$sql->adTabela('municipio_lista');
	$sql->adCampo('count(municipio_lista_municipio)');
	$sql->adOnde('municipio_lista_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$municipios_projeto=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('municipio_lista');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=municipio_lista_tarefa');
	$sql->adCampo('count(municipio_lista_municipio)');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));

	$municipios_tarefa=$sql->Resultado();
	$sql->limpar();
	
	$lista_municipios = array(0=>'');
	if ($municipios_projeto > 0 && $municipios_tarefa > 0) $lista_municipios['projeto_tarefa']='Municípios d'.$config['genero_tarefa'].'s '.$config['tarefas'].' e '.$config['projeto'];
	if ($municipios_tarefa> 0) $lista_municipios['tarefa']='Municípios d'.$config['genero_tarefa'].'s '.$config['tarefas'];
	if ($municipios_projeto > 0) $lista_municipios['projeto']='Municípios d'.$config['genero_projeto'].' '.$config['projeto'];
	
	$tipo_area_municipio=array();
	if ($municipios_projeto > 0) {
		$tipo_area_municipio['fisico_projeto']='Físico executado d'.$config['genero_projeto'].' '.$config['projeto'];
		$tipo_area_municipio['status_projeto']='Status d'.$config['genero_projeto'].' '.$config['projeto'];
		}
	if ($municipios_tarefa > 0) {
		$tipo_area_municipio['fisico_tarefa']='Físico executado d'.$config['genero_tarefa'].'s '.$config['tarefas'];
		$tipo_area_municipio['status_tarefa']='Status d'.$config['genero_tarefa'].'s '.$config['tarefas'];
		}
	

	if (count($lista_municipios)>1) echo '<tr><td colspan=20>Municípios:'.selecionaVetor($lista_municipios,'lista_municipios','class="texto" onchange="imprimir_municipios();"').selecionaVetor($tipo_area_municipio,'tipo_area_municipio','class="texto" onchange="imprimir_municipios();"').'</td></tr>';
	

	
	if ($config['anexo_eb']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=relatorios&a=index&relatorio_tipo=status_negapeb_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Situação dos documentos d'.$config['genero_anexo_eb_nome'].' '.$config['anexo_eb_nome'].'</a></td></tr>';
	
	echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="url_passar(0, \'m=relatorios&a=index&dialogo=1&jquery=1&self_print=0&veio_projeto=1&relatorio_tipo=grafico_financeiro_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Curva S do financeiro</a></td></tr>';
	echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="url_passar(0, \'m=relatorios&a=index&dialogo=1&jquery=1&self_print=1&veio_projeto=1&relatorio_tipo=grafico_fisico_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Curva S do físico</a></td></tr>';
	}
		


echo '</form>';

echo '</table>';
echo estiloFundoCaixa();
?>
<script type="text/JavaScript">

function imprimir_area(){
	var elmId = document.getElementById('lista_areas');
	var tipo = document.getElementById('tipo_area').value;
	if(!elmId.selectedIndex) return;
	var url = 'm=projetos&a=imprimir_area_pro&tipo='+tipo+'&projeto_area_id='+ elmId.value+"<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>";
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}


function imprimir_municipios(){
	var opcao_municipio = document.getElementById('lista_municipios');
	var tipo = document.getElementById('tipo_area_municipio').value;
	if(!opcao_municipio.selectedIndex) return;
	if ((tipo=='fisico_tarefa' || tipo=='status_tarefa') && opcao_municipio.value!='tarefa'){
		alert('Combinação inválida!');
		return;
		}
	var url = 'm=projetos&a=imprimir_ municipios_pro&tipo='+tipo+'&opcao_municipio='+opcao_municipio.value+"<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>";
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}


function ir_para2(url){
	var pdf=document.getElementById('pdf').checked;
	if (pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	url_passar(0, url);
	}


function ir_para(m, a, u){
	url_passar(0, 'm='+m+'&a='+a+'&u='+u+'<?php echo "&baseline_id=".$baseline_id."&projeto_id=".$projeto_id ?>');
	}
	
function imprimir_causa_efeito(){
	var elmId = document.getElementById('causa_efeito_id');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=causa_efeito_imprimir&causa_efeito_id='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}
         
function imprimir_solicitacao_mudanca(){
	var elmId = document.getElementById('projeto_mudanca_id');
	if(!elmId.selectedIndex) return;
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=mudanca_imprimir&projeto_mudanca_id='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}
	
function imprimir_termo_recebimento(){
	var elmId = document.getElementById('projeto_recebimento_id');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=recebimento_imprimir&projeto_recebimento_id='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}
	
function imprimir_ata_reuniao(){
	var elmId = document.getElementById('ata_id');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=ata_imprimir&ata_id='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}	
	     
function imprimir_licao_aprendida(){
	var elmId = document.getElementById('licao_id');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=licao_imprimir&licao_id='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}	
	     
function imprimir_resumo_evento(){
	var elmId = document.getElementById('dias');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=resumo_evento_imprimir_pro&dias='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}
</script>