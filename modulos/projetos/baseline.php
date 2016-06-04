<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$inserir=getParam($_REQUEST, 'inserir', 0);
$alterar=getParam($_REQUEST, 'alterar', 0);

$inserido=getParam($_REQUEST, 'inserido', 0);
$alterado=getParam($_REQUEST, 'alterado', 0);

if (($inserir || $alterar)) {
    $Aplic->carregarCKEditorJS();
	}


$nova_baseline=getParam($_REQUEST, 'nova_baseline', 0);
$excluir_baseline=getParam($_REQUEST, 'excluir_baseline', array());
$baseline_id=getParam($_REQUEST, 'baseline_id', 0);
$baseline_nome=getParam($_REQUEST, 'baseline_nome', '');
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$baseline_descricao=getParam($_REQUEST, 'baseline_descricao', '');

$sql = new BDConsulta;

$sql->adTabela('projetos');
$sql->adCampo('projeto_acesso');
$sql->adOnde('projeto_id='.(int)$projeto_id);
$projeto_acesso = $sql->Resultado();
$sql->Limpar();

if (!permiteEditar($projeto_acesso, $projeto_id) || !$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');

if ($baseline_id && $alterar){
	$sql->adTabela('baseline');
	$sql->adCampo('baseline_nome, baseline_descricao');
	$sql->adOnde('baseline_id='.(int)$baseline_id);
	$linha_alterar = $sql->Linha();
	$sql->Limpar();
  }
if ($alterado && $baseline_id){
	$sql->adTabela('baseline');
	$sql->adAtualizar('baseline_nome', $baseline_nome);
	$sql->adAtualizar('baseline_descricao', $baseline_descricao);
	$sql->adOnde('baseline_id='.(int)$baseline_id);
	$sql->exec();
	$sql->limpar();
	}
if ($excluir_baseline){
	$sql->adTabela('baseline_tarefas');
	$sql->adCampo('tempo_em_segundos(diferenca_tempo(NOW(),MIN(tarefa_inicio)))/3600');
	$sql->adOnde('baseline_id='.(int)$excluir_baseline);
	$resultado=$sql->resultado();
	$sql->limpar();
	if ($resultado > 0){
		$sql->setExcluir('baseline');
		$sql->adOnde('baseline_id='.(int)$excluir_baseline);
		$sql->exec();
		$sql->limpar();
		}
	else {
		$sql->adTabela('baseline');
		$sql->adCampo('baseline_nome');
		$sql->adOnde('baseline_id='.(int)$excluir_baseline);
		$nome=$sql->resultado();
		$sql->limpar();
		ver2('Não é possivel excluir '.$nome.',  pois tem ao menos uma tarefa com data de início anterior a hoje.');
		}
	}

if ($inserido){
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_id');
	$sql->adOnde('tarefa_projeto='.(int)$projeto_id);
	$lista_tarefas = $sql->carregarColuna();
	$sql->Limpar();
	$lista_tarefas=implode(',',$lista_tarefas);
	if (!$lista_tarefas) $lista_tarefas='0';


	$sql->adTabela('eventos');
	$sql->adCampo('evento_id');
	$sql->adOnde('evento_projeto='.(int)$projeto_id.' OR evento_tarefa IN ('.$lista_tarefas.')');
	$lista_eventos = $sql->carregarColuna();
	$sql->Limpar();
	$lista_eventos=implode(',',$lista_eventos);
	if (!$lista_eventos) $lista_eventos='0';

	$sql->adTabela('baseline');
	$sql->adInserir('baseline_projeto_id', $projeto_id);
	$sql->adInserir('baseline_nome', $baseline_nome);
	$sql->adInserir('baseline_descricao', $baseline_descricao);
	$sql->adInserir('baseline_data', date('Y-m-d H:i:s'));
	$sql->exec();
	$baseline_id=$bd->Insert_ID('baseline','baseline_id');
	$sql->limpar();

	if ($Aplic->profissional && $config['anexo_eb']){
														copiar('eb_arquivo', 'eb_arquivo_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_encerramento', 'eb_encerramento_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_situacao', 'eb_situacao_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_aceite', 'eb_aceite_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_mudanca_controle', 'eb_mudanca_controle_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_mudanca', 'eb_mudanca_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_mudanca_item', 'eb_mudanca_item_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_qualidade', 'eb_qualidade_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_qualidade_item', 'eb_qualidade_item_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_risco_item', 'eb_risco_item_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_risco', 'eb_risco_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_interessado', 'eb_interessado_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_comunicacao', 'eb_comunicacao_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_humano', 'eb_humano_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_humano_matriz', 'eb_humano_matriz_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_plano_item', 'eb_plano_item_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_plano', 'eb_plano_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_escopo', 'eb_escopo_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_iniciacao', 'eb_iniciacao_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_iniciacao_envolvido', 'projeto_id='.(int)$projeto_id, $baseline_id);
														copiar('eb_implantacao', 'eb_implantacao_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_viabilidade', 'eb_viabilidade_projeto='.(int)$projeto_id, $baseline_id);
														copiar('eb_viabilidade_envolvido', 'projeto_id='.(int)$projeto_id, $baseline_id);

														copiar('eb_encerramento_campo', 'eb_encerramento_projeto='.(int)$projeto_id, $baseline_id, 'eb_encerramento', 'eb_encerramento_campo_encerramento=eb_encerramento.eb_encerramento_id');
														copiar('eb_situacao_campo', 'eb_situacao_projeto='.(int)$projeto_id, $baseline_id, 'eb_situacao', 'eb_situacao_campo_situacao=eb_situacao.eb_situacao_id');
														copiar('eb_mudanca_controle_campo', 'eb_mudanca_controle_projeto='.(int)$projeto_id, $baseline_id, 'eb_mudanca_controle', 'eb_mudanca_controle_campo_mudanca=eb_mudanca_controle.eb_mudanca_controle_id');
														copiar('eb_mudanca_controle_item', 'eb_mudanca_controle_projeto='.(int)$projeto_id, $baseline_id, 'eb_mudanca_controle', 'eb_mudanca_controle_item_mudanca_controle=eb_mudanca_controle.eb_mudanca_controle_id');
														copiar('eb_mudanca_controle_escopo', 'eb_mudanca_controle_projeto='.(int)$projeto_id, $baseline_id, 'eb_mudanca_controle', 'eb_mudanca_controle_escopo_mudanca_controle=eb_mudanca_controle.eb_mudanca_controle_id');
														copiar('eb_mudanca_controle_custo', 'eb_mudanca_controle_projeto='.(int)$projeto_id, $baseline_id, 'eb_mudanca_controle', 'eb_mudanca_controle_custo_mudanca_controle=eb_mudanca_controle.eb_mudanca_controle_id');
														copiar('eb_mudanca_campo', 'eb_mudanca_projeto='.(int)$projeto_id, $baseline_id, 'eb_mudanca', 'eb_mudanca_campo_mudanca=eb_mudanca.eb_mudanca_id');
														copiar('eb_interessado_campo', 'eb_interessado_projeto='.(int)$projeto_id, $baseline_id, 'eb_interessado', 'eb_interessado_campo_interessado=eb_interessado.eb_interessado_id');
														copiar('eb_comunicacao_campo', 'eb_comunicacao_projeto='.(int)$projeto_id, $baseline_id, 'eb_comunicacao', 'eb_comunicacao_campo_comunicacao=eb_comunicacao.eb_comunicacao_id');
														copiar('eb_plano_item_depts', 'eb_plano_item_projeto='.(int)$projeto_id, $baseline_id, 'eb_plano_item', 'eb_plano_item_depts.eb_plano_item_id=eb_plano_item.eb_plano_item_id');
														copiar('eb_plano_item_custos', 'eb_plano_item_projeto='.(int)$projeto_id, $baseline_id, 'eb_plano_item', 'eb_plano_item_custos_eb_plano_item=eb_plano_item.eb_plano_item_id');
														copiar('eb_plano_item_designados', 'eb_plano_item_projeto='.(int)$projeto_id, $baseline_id, 'eb_plano_item', 'eb_plano_item_designados.eb_plano_item_id=eb_plano_item.eb_plano_item_id');
														copiar('eb_plano_item_gastos', 'eb_plano_item_projeto='.(int)$projeto_id, $baseline_id, 'eb_plano_item', 'eb_plano_item_gastos_eb_plano_item=eb_plano_item.eb_plano_item_id');
														copiar('eb_plano_item_h_custos', 'eb_plano_item_projeto='.(int)$projeto_id, $baseline_id, 'eb_plano_item', 'h_custos_eb_plano_item=eb_plano_item.eb_plano_item_id');
														copiar('eb_plano_item_h_gastos', 'eb_plano_item_projeto='.(int)$projeto_id, $baseline_id, 'eb_plano_item', 'h_gastos_eb_plano_item=eb_plano_item.eb_plano_item_id');
														copiar('eb_escopo_campo', 'eb_escopo_projeto='.(int)$projeto_id, $baseline_id, 'eb_escopo', 'eb_escopo_campo_escopo=eb_escopo.eb_escopo_id');
														copiar('eb_iniciacao_campo', 'eb_iniciacao_projeto='.(int)$projeto_id, $baseline_id, 'eb_iniciacao', 'eb_iniciacao_campo_iniciacao=eb_iniciacao.eb_iniciacao_id');
														copiar('eb_implantacao_campo', 'eb_implantacao_projeto='.(int)$projeto_id, $baseline_id, 'eb_implantacao', 'eb_implantacao_campo_implantacao=eb_implantacao.eb_implantacao_id');
														copiar('eb_viabilidade_campo', 'eb_viabilidade_projeto='.(int)$projeto_id, $baseline_id, 'eb_viabilidade', 'eb_viabilidade_campo_viabilidade=eb_viabilidade.eb_viabilidade_id');
														}

	if ($Aplic->profissional) copiar('evento_gestao', 'evento_gestao_evento IN ('.$lista_eventos.')', $baseline_id);
														copiar('eventos', 'evento_tarefa IN ('.$lista_tarefas.') OR evento_projeto='.(int)$projeto_id, $baseline_id);
	if ($Aplic->profissional) copiar('folha_ponto', 'folha_ponto_tarefa IN ('.$lista_tarefas.') OR folha_ponto_evento IN ('.$lista_eventos.')', $baseline_id);
	if ($Aplic->profissional) copiar('folha_ponto_arquivo', 'folha_ponto_tarefa IN ('.$lista_tarefas.') OR folha_ponto_evento IN ('.$lista_eventos.')', $baseline_id, 'folha_ponto', 'folha_ponto_id=folha_ponto_arquivo_ponto');
	if ($Aplic->profissional) copiar('folha_ponto_gasto', 'folha_ponto_tarefa IN ('.$lista_tarefas.') OR folha_ponto_evento IN ('.$lista_eventos.')', $baseline_id, 'folha_ponto', 'folha_ponto_id=folha_ponto_gasto_folha');
														copiar('municipio_lista', 'municipio_lista_tarefa IN ('.$lista_tarefas.') OR municipio_lista_projeto='.(int)$projeto_id, $baseline_id);
	if ($Aplic->profissional) copiar('pagamento', 'pagamento_tarefa IN ('.$lista_tarefas.') OR pagamento_projeto='.(int)$projeto_id, $baseline_id);
														copiar('projeto_area', 'projeto_area_tarefa IN ('.$lista_tarefas.') OR projeto_area_projeto='.(int)$projeto_id, $baseline_id);
														copiar('projeto_cia', 'projeto_cia_projeto='.(int)$projeto_id, $baseline_id);
														copiar('projeto_contatos', 'projeto_id='.(int)$projeto_id, $baseline_id);
														copiar('projeto_depts', 'projeto_id='.(int)$projeto_id, $baseline_id);
	if ($Aplic->profissional) copiar('projeto_gestao', 'projeto_gestao_projeto='.(int)$projeto_id, $baseline_id);
														copiar('projeto_integrantes', 'projeto_id='.(int)$projeto_id, $baseline_id);
														copiar('projeto_ponto', 'projeto_area_tarefa IN ('.$lista_tarefas.') OR projeto_area_projeto='.(int)$projeto_id, $baseline_id, 'projeto_area', 'projeto_ponto.projeto_area_id=projeto_area.projeto_area_id');
	if ($Aplic->profissional) copiar('projeto_portfolio', 'projeto_portfolio_pai='.(int)$projeto_id, $baseline_id);
	if ($Aplic->profissional) copiar('priorizacao', 'priorizacao_projeto='.(int)$projeto_id, $baseline_id);
	if ($Aplic->profissional) copiar('projeto_stakeholder', 'projeto_stakeholder_projeto='.(int)$projeto_id, $baseline_id);
														copiar('projetos', 'projeto_id='.(int)$projeto_id, $baseline_id);
	if ($Aplic->profissional) copiar('recurso_ponto', 'recurso_ponto_tarefa IN ('.$lista_tarefas.')', $baseline_id);
	if ($Aplic->profissional) copiar('recurso_ponto_arquivo', 'recurso_ponto_tarefa IN ('.$lista_tarefas.')', $baseline_id, 'recurso_ponto', 'recurso_ponto_id=recurso_ponto_arquivo_ponto');
	if ($Aplic->profissional) copiar('recurso_ponto_gasto', 'recurso_ponto_tarefa IN ('.$lista_tarefas.')', $baseline_id, 'recurso_ponto', 'recurso_ponto_id=recurso_ponto_gasto_ponto');
														copiar('recurso_tarefas', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
														copiar('tarefa_contatos', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
														copiar('tarefa_custos', 'tarefa_custos_tarefa IN ('.$lista_tarefas.')', $baseline_id);
														copiar('tarefa_dependencias', 'dependencias_tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
														copiar('tarefa_depts', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
	if ($Aplic->profissional) copiar('tarefa_designado_periodos', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
														copiar('tarefa_designados', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
	if ($Aplic->profissional) copiar('tarefa_entrega', 'tarefa_entrega_tarefa IN ('.$lista_tarefas.')', $baseline_id);
														copiar('tarefa_gastos', 'tarefa_gastos_tarefa IN ('.$lista_tarefas.')', $baseline_id);
														copiar('tarefa_log', 'tarefa_log_tarefa IN ('.$lista_tarefas.')', $baseline_id);
	if ($Aplic->profissional) copiar('tarefa_log_arquivo', 'tarefa_log_tarefa IN ('.$lista_tarefas.')', $baseline_id, 'tarefa_log', 'tarefa_log_id=tarefa_log_arquivo_tarefa_log_id');
														copiar('tarefas', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
	if ($Aplic->profissional) copiar('tarefa_cia', 'tarefa_cia_tarefa IN ('.$lista_tarefas.')', $baseline_id);

	if($Aplic->profissional){
    $sql->adTabela('tarefas');
    $sql->adCampo('tarefa_id, tarefa_inicio');
    $sql->adOnde('tarefa_projeto='.(int)$projeto_id);
    $sql->adOnde('tarefa_data_atualizada IS NULL');
    $sql->exec();
    $lista_tarefas = $sql->Lista();
    $sql->limpar();

    foreach($lista_tarefas as $tarefa){
      $sql->adTabela('tarefas');
      $sql->adAtualizar('tarefa_data_atualizada',$tarefa['tarefa_inicio']);
      $sql->adOnde('tarefa_id='.(int)$tarefa['tarefa_id']);
      $sql->exec();
      $sql->limpar();
      }
    }
	}





function copiar($tabela, $onde='', $baseline_id=0, $esqUnir='', $esqParametro=''){
  global $sql;
  $sql->adTabela($tabela);
  if ($esqUnir) $sql->esqUnir($esqUnir, $esqUnir, $esqParametro);
  $sql->adCampo($tabela.'.*');
  if ($onde) $sql->adOnde($onde);
  $lista = $sql->lista();
  $sql->limpar();
 	if (count($lista)){
 		foreach ($lista as $linha){
 			$sql->adTabela('baseline_'.$tabela);
			$sql->adInserir('baseline_id', $baseline_id);
			foreach ($linha as $chave => $valor) $sql->adInserir($chave, $valor);
	   	$sql->exec();
			$sql->limpar();
			}
		}
	}




echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="m" name="m" value="projetos">';
echo '<input type=hidden id="a" name="a" value="baseline">';
echo '<input type=hidden name="inserir" id="inserir" value="">';
echo '<input type=hidden name="alterar" id="alterar" value="">';
echo '<input type=hidden name="baseline_id" id="baseline_id" value="">';
echo '<input type=hidden name="excluir_baseline" id="excluir_baseline" value="">';
echo '<input type=hidden name="inserido" id="inserido" value="">';
echo '<input type=hidden name="alterado" id="alterado" value="">';
echo '<input type=hidden id="projeto_id" name="projeto_id" value="'.$projeto_id.'">';

echo estiloTopoCaixa(800);
echo '<table width="800" align="center" border=0 class="std" cellspacing=0 cellpadding=0 >';

echo '<tr><td colspan=20><center><h1>'.nome_projeto($projeto_id).'</h1></center></td></tr>';

echo '<tr><td width="200"><fieldset><legend class=texto style="color: black;">&nbsp;Baselines&nbsp;</legend>';
echo '<select name="ListaBaseline" id="ListaBaseline" size=12 style="width:100%;" ondblClick="">';

$sql->adTabela('baseline');
$sql->adCampo('baseline_id, baseline_nome, baseline_data');
$sql->adOnde('baseline_projeto_id='.(int)$projeto_id);
$sql_resultado = $sql->Lista();
$sql->Limpar();
foreach ($sql_resultado as $linha) echo '<option value="'.$linha['baseline_id'].'">'.retorna_data($linha['baseline_data']).' - '.$linha['baseline_nome'].'</option>';
echo '</option></select></fieldset></td></tr>';
echo '<tr><td>';

if (!$inserir && !$alterar) {
		echo '<table><tr>';
		echo '<td>'.botao('inserir','Inserir','Clique neste botão para inserir uma nova baseline.','','env.inserir.value=1; env.submit();').'</td>';
		echo '<td>'.botao('alterar', 'Alterar','Clique neste botão para alterar uma baseline da caixa de seleção acima.','','editar();').'</td>';
		echo '<td>'.botao('excluir', 'Excluir','Clique neste botão para excluir as baselines selecionadas da caixa de seleção acima.<br><br>Para excluir múltiplas baselines, selecione estas com a tecla CTRL pressionada.','','excluir();').'</td>';
		echo '<td>'.botao('voltar', 'Voltar', 'Clique neste botão para voltar à tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr></table>';
		echo '</tr></table>';
		}
elseif ($inserir){
		echo '<table><tr>';
		echo '<td>&nbsp;Nome: <input type=text class="texto" name="baseline_nome" id="baseline_nome" style="width:400px" value="'.date('d/m/Y H:i:s').'"></td>';
		echo '<tr><td colspan=20 align="center">Descrição</td></tr>';
		echo '<tr><td colspan=20 align="left" style="background:#ffffff; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="baseline_descricao" id="baseline_descricao"></textarea></td></tr>';
		echo '<td>'.botao('salvar','Salvar','Clique neste botão para confirmar a inserção da nova baseline.','','if (env.baseline_nome.value.length>0) {env.inserido.value=1; env.submit();} else alert (\'Escreve o nome da baseline!\');').'</td>';
		echo '<td>'.botao('cancelar','Cancelar','Clique neste botão para cancelar a inserção da nova baseline.','','env.submit();').'</td>';
		echo '</tr></table>';
		}
else {
		echo '<table><tr>';
		echo '<td>&nbsp;Nome: <input type=text class="texto" name="baseline_nome" id="baseline_nome" value="'.$linha_alterar['baseline_nome'].'" style="width:400px"></td>';
		echo '<tr><td colspan=20 align="center">Descrição</td></tr>';
		echo '<tr><td colspan=20 align="left" style="background:#ffffff; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="baseline_descricao" id="baseline_descricao">'.$linha_alterar['baseline_descricao'].'</textarea></td></tr>';
		echo '<td>'.botao('salvar','Salvar','Clique neste botão para confirmar a alteração do nome da baseline.','','if (env.baseline_nome.value.length>0) {env.alterado.value=1;  env.baseline_id.value='.(int)$baseline_id.'; env.submit();} else alert (\'Escreve o novo nome da baseline!\');').'</td>';
		echo '<td>'.botao('cancelar','Cancelar','Clique neste botão para cancelar a alteração do nome da baseline.','','env.submit();').'</td>';
		echo '</tr></table>';
		}
echo '</td></tr></table>';
echo estiloFundoCaixa(800);
echo '</form>';

?>

<script type="text/javascript">

function excluir() {
	if (document.getElementById('ListaBaseline').value > 0) {
		env.excluir_baseline.value=document.getElementById('ListaBaseline').value;
		env.submit();
		}
	else alert ('Selecione uma baseline!');
	}

function editar() {
	if (document.getElementById('ListaBaseline').value > 0) {
		env.alterar.value=1;
		env.baseline_id.value=document.getElementById('ListaBaseline').value;
		env.submit();
		}
	else alert ('Selecione uma baseline!');
	}

</script>