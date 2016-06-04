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
global $Aplic;

$Aplic->carregarCalendarioJS();
//vindo do adicionar evento no plano de comunicacoes
$projeto_comunicacao_evento_id = getParam($_REQUEST, 'projeto_comunicacao_evento_id', null);

$obj = new CEvento();
$objeto = getParam($_REQUEST, 'objeto', null);
if($objeto){
	$objeto = base64_decode($objeto);
	if(get_magic_quotes_gpc()) $objeto = stripslashes($objeto);
	} 


if (getParam($_REQUEST, 'aceitar', null)){
	$vetor=unserialize($objeto);
	
	$obj->join($vetor);
	
	if ($obj->evento_inicio) {
		$data_inicio = new CData($obj->evento_inicio.$vetor['inicio_hora']);
		$obj->evento_inicio = $data_inicio->format('%Y-%m-%d %H:%M:%S');
		}
	if ($obj->evento_fim) {
		$data_fim = new CData($obj->evento_fim.$vetor['fim_hora']);
		$obj->evento_fim = $data_fim->format('%Y-%m-%d %H:%M:%S');
		}
	
	$obj->armazenar();

	require_once $Aplic->getClasseSistema('CampoCustomizados');
	$campos_customizados = new CampoCustomizados('evento', $obj->evento_id, 'editar');
	$campos_customizados->join($vetor);
	$sql = $campos_customizados->armazenar($obj->evento_id);
	$Aplic->setMsg($vetor['evento_id'] ? 'Evento atualizado' : 'Evento adicionado', UI_MSG_OK, true);
	if (isset($vetor['evento_designado']) && $vetor['evento_designado'] && isset($vetor['evento_designado_porcentagem']) && $vetor['evento_designado_porcentagem']) $obj->atualizarDesignados(explode(',', $vetor['evento_designado']), explode(',', $vetor['evento_designado_porcentagem']) );
	if (($vetor['evento_inicio_antigo'] && $vetor['evento_inicio_antigo']!=$vetor['evento_inicio']) || ($vetor['evento_fim_antigo'] && $vetor['evento_fim_antigo']!=$vetor['evento_fim'])) $obj->atualizarDuracao(explode(',', $vetor['evento_designado']));
	if (isset($vetor['email_convidado'])) $obj->notificar($vetor['evento_designado'], $vetor['evento_id']);
	$obj->adLembrete();
	
	if (isset($vetor['uuid']) && $vetor['uuid']){
			$sql = new BDConsulta;
			$sql->adTabela('evento_gestao');
			$sql->adAtualizar('evento_gestao_evento', (int)$obj->evento_id);
			$sql->adAtualizar('evento_gestao_uuid', null);
			$sql->adOnde('evento_gestao_uuid=\''.$vetor['uuid'].'\'');
			$sql->exec();
			$sql->limpar();
			}	
		
	if ($projeto_comunicacao_evento_id){
		echo '<script language="javascript">parent.gpwebApp._popupCallback('.$projeto_comunicacao_evento_id.', '.$obj->evento_id.');</script>';
		}
		
		
	if ($Aplic->profissional && getParam($_REQUEST, 'uuid', null)){
		$sql = new BDConsulta;
		$sql->adTabela('evento_gestao');
		$sql->adCampo('evento_gestao.*');
		$sql->adOnde('evento_gestao_evento='.(int)(int)$obj->evento_id);
		$sql->adOrdem('evento_gestao_ordem ASC');
		$linha=$sql->linha();
		$sql->limpar();
		
		$sql->adTabela('evento_gestao');
		$sql->adCampo('count(evento_gestao_id)');
		$sql->adOnde('evento_gestao_evento='.(int)$obj->evento_id);
		$qnt=$sql->Resultado();
		$sql->limpar();
		
		if ($linha['evento_gestao_tarefa'] && $qnt==1) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['evento_gestao_tarefa'];
		elseif ($linha['evento_gestao_projeto'] && $qnt==1) $endereco='m=projetos&a=ver&projeto_id='.$linha['evento_gestao_projeto'];
		elseif ($linha['evento_gestao_perspectiva'] && $qnt==1) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['evento_gestao_perspectiva'];
		elseif ($linha['evento_gestao_tema'] && $qnt==1) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['evento_gestao_tema'];
		elseif ($linha['evento_gestao_objetivo'] && $qnt==1) $endereco='m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$linha['evento_gestao_objetivo'];
		elseif ($linha['evento_gestao_fator'] && $qnt==1) $endereco='m=praticas&a=fator_ver&pg_fator_critico_id='.$linha['evento_gestao_fator'];
		elseif ($linha['evento_gestao_estrategia'] && $qnt==1) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['evento_gestao_estrategia'];
		elseif ($linha['evento_gestao_meta'] && $qnt==1) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['evento_gestao_meta'];
		elseif ($linha['evento_gestao_pratica'] && $qnt==1) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['evento_gestao_pratica'];
		elseif ($linha['evento_gestao_indicador'] && $qnt==1) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['evento_gestao_indicador'];
		elseif ($linha['evento_gestao_acao'] && $qnt==1) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['evento_gestao_acao'];
		elseif ($linha['evento_gestao_canvas'] && $qnt==1) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['evento_gestao_canvas'];
		elseif ($linha['evento_gestao_risco'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['evento_gestao_risco'];
		elseif ($linha['evento_gestao_risco_resposta'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['evento_gestao_risco_resposta'];
		elseif ($linha['evento_gestao_calendario'] && $qnt==1) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['evento_gestao_calendario'];
		elseif ($linha['evento_gestao_monitoramento'] && $qnt==1) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['evento_gestao_monitoramento'];
		elseif ($linha['evento_gestao_ata'] && $qnt==1) $endereco='m=atas&a=ata_ver&ata_id='.$linha['evento_gestao_ata'];
		elseif ($linha['evento_gestao_swot'] && $qnt==1) $endereco='m=swot&a=swot_ver&swot_id='.$linha['evento_gestao_swot'];
		elseif ($linha['evento_gestao_operativo'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['evento_operativo'];
		elseif ($linha['evento_gestao_instrumento'] && $qnt==1) $endereco='m=recursos&a=instrumento_ver&instrumento_id='.$linha['evento_gestao_instrumento'];
		elseif ($linha['evento_gestao_recurso'] && $qnt==1) $endereco='m=recursos&a=ver&recurso_id='.$linha['evento_gestao_recurso'];
		elseif ($linha['evento_gestao_problema'] && $qnt==1) $endereco='m=problema&a=problema_ver&problema_id='.$linha['evento_gestao_problema'];
		elseif ($linha['evento_gestao_demanda'] && $qnt==1) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['evento_gestao_demanda'];
		elseif ($linha['evento_gestao_programa'] && $qnt==1) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['evento_gestao_programa'];
		elseif ($linha['evento_gestao_link'] && $qnt==1) $endereco='m=links&a=ver&link_id='.$linha['evento_gestao_link'];
		elseif ($linha['evento_gestao_avaliacao'] && $qnt==1) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['evento_gestao_avaliacao'];
		elseif ($linha['evento_gestao_tgn'] && $qnt==1) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['evento_gestao_tgn'];
		elseif ($linha['evento_gestao_brainstorm'] && $qnt==1) $endereco='m=praticas&a=brainstorm_pro_ver&brainstorm_id='.$linha['evento_gestao_brainstorm'];
		elseif ($linha['evento_gestao_gut'] && $qnt==1) $endereco='m=praticas&a=gut_pro_ver&gut_id='.$linha['evento_gestao_gut'];
		elseif ($linha['evento_gestao_causa_efeito'] && $qnt==1) $endereco='m=praticas&a=causa_efeito_pro_ver&causa_efeito_id='.$linha['evento_gestao_causa_efeito'];
		elseif ($linha['evento_gestao_arquivo'] && $qnt==1) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['evento_gestao_arquivo'];
		elseif ($linha['evento_gestao_forum'] && $qnt==1) $endereco='m=foruns&a=ver&forum_id='.$linha['evento_gestao_forum'];
		elseif ($linha['evento_gestao_checklist'] && $qnt==1) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['evento_gestao_checklist'];
		elseif ($linha['evento_gestao_agenda'] && $qnt==1) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['evento_gestao_agenda'];
		elseif ($linha['evento_gestao_agrupamento'] && $qnt==1) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['evento_gestao_agrupamento'];
		elseif ($linha['evento_gestao_patrocinador'] && $qnt==1) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['evento_gestao_patrocinador'];
		elseif ($linha['evento_gestao_template'] && $qnt==1) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['evento_gestao_template'];
		elseif ($linha['evento_gestao_painel'] && $qnt==1) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['evento_gestao_painel'];
		elseif ($linha['evento_gestao_painel_odometro'] && $qnt==1) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['evento_gestao_painel_odometro'];
		elseif ($linha['evento_gestao_painel_composicao'] && $qnt==1) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['evento_gestao_painel_composicao'];

		else $endereco='m=calendario&a=index&data='.$obj->evento_inicio;
		$Aplic->redirecionar($endereco);
		}			
	else {
		$Aplic->redirecionar('m=calendario&projeto_id='.$obj->evento_projeto.'&pratica_indicador_id='.$obj->evento_indicador.'&pratica_id='.$obj->evento_pratica.'&evento_calendario='.$obj->evento_calendario.'&tema_id='.$obj->evento_tema.'&pg_objetivo_estrategico_id='.$obj->evento_objetivo.'&pg_estrategia_id='.$obj->evento_estrategia.'&plano_acao_id='.$obj->evento_acao.'&pg_fator_critico_id='.$obj->evento_fator.'&pg_meta_id='.$obj->evento_meta);
		exit();
		}
	}



$botoesTitulo = new CBlocoTitulo(($obj->evento_id ? 'Conflito na Edição do Evento' : 'Conflito na Adição do Evento'), 'calendario.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();


echo '<form name="env" method="POST">';
echo '<input type="hidden" name="m" value="calendario" />';
echo '<input type="hidden" name="a" value="conflito" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="aceitar" value="1" />';
echo '<input type="hidden" name="objeto" value="'.base64_encode($objeto).'" />';
echo '<input type="hidden" name="projeto_comunicacao_evento_id" value="'.$projeto_comunicacao_evento_id.'" />';

echo '</form>';

$conflito = getParam($_REQUEST, 'conflito', null);
$conflito =explode(',',$conflito);
$vetor=unserialize($objeto);

echo estiloTopoCaixa();
echo '<table width="100%" class="std"><tr><td><b>Os seguintes '.$config['usuarios'].' não estão disponíveis para a data proposta</b></tr></tr>';
foreach ($conflito as $usuario) echo '<tr><td colspan=20>'.$usuario.'</td></tr>';
echo '<tr><td><table><tr>
<td>'.botao('editar evento', 'Editar evento','Clique neste botão para editar o evento.','','env.a.value=\'editar\'; env.submit();', '','','',0).'</td>
<td>'.botao('registar evento', 'Registar evento','Clique neste botão para registar a '.($vetor['evento_id'] ? 'alteração' : 'inclusão').' deste evento apesar do conflito.','','env.submit();','','',0).'</td>
<td>'.botao('cancelar', 'Cancelar','Clique neste botão para cancelar a '.($vetor['evento_id'] ? 'edição' : 'inclusão').' deste evento.','','url_passar(0, \''.$Aplic->getPosicao().'\');','','',0).'</td>
</tr></table></td></tr>';
echo '</table>';
echo estiloFundoCaixa();
?>