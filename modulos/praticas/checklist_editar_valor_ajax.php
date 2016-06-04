<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
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

function excluir_valor($checklist_dados_id=null, $pratica_indicador_valor_indicador=null){
	$sql = new BDConsulta;
	$sql->setExcluir('checklist_dados');
	$sql->adOnde('checklist_dados_id='.(int)$checklist_dados_id);
	$sql->exec();
	$sql->Limpar();
	
	$saida=exibir_lista($pratica_indicador_valor_indicador);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_valores',"innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_valor");	


function exibir_lista($indicador_id){
	global $config, $Aplic;
	$sql = new BDConsulta;

	$saida='';
	
	$sql->adTabela('checklist_dados');
	$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = checklist_dados_responsavel');
	$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS dono, checklist_dados_id, pratica_indicador_valor_valor, formatar_data(pratica_indicador_valor_data, "%d/%m/%Y") AS data, checklist_dados_obs');
	$sql->adOnde('pratica_indicador_valor_indicador = '.(int)$indicador_id);
	$sql->adOrdem('pratica_indicador_valor_data DESC');
	$valores = $sql->Lista();
	$sql->limpar();
	
	if (count($valores)){
		$saida.= '<table cellspacing=0 cellpadding=2 class="tbl1" width="100%"><tr>';
		$saida.= '<th>'.dica('Data', 'Data de inserção do valor.').'Data'.dicaF().'</th>';
		$saida.= '<th>'.dica('Valor', 'O valor inserido no indicador.').'Valor'.dicaF().'</th>';
		$saida.= '<th>'.dica('Responsável', 'Responsável pela inserção do valor.').'Responsável'.dicaF().'</th>';
		$saida.= '<th>'.dica('Observações', 'Observações neste valor.').'Observações'.dicaF().'</th>';
		$saida.= '<th></th></tr>';
		$saida.= '';
		foreach($valores as $valor){
			$saida.= '<tr><td width="60" nowrap="nowrap" align=center>'.$valor['data'].'</td>';
			$saida.= '<td width="60" nowrap="nowrap" align=right>'.number_format($valor['pratica_indicador_valor_valor'], $config['casas_decimais'], ',', '.').'</td>';
			$saida.= '<td>'.$valor['dono'].'</td>';
			$saida.= '<td>'.($valor['checklist_dados_obs']? $valor['checklist_dados_obs'] : '&nbsp;').'</td>';
			$saida.= '<td width="'.($Aplic->profissional ? '48' : '32').'" align=center>';
			if ($Aplic->profissional) $saida.= '<a href="javascript: void(0);" onclick="anexar_arquivo('.$valor['checklist_dados_id'].');">'.imagem('icones/anexar.png', 'Anexar Arquivo', 'Clique neste ícone '.imagem('icones/anexar.png').' para anexar arquivo junto ao valor.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="editar_valor('.$valor['checklist_dados_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o valor.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este valor?\')) {excluir_valor('.$valor['checklist_dados_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este valor.').'</a>';
			$saida.= '</td>';
			$saida.= '</tr>';
			}
		}

	return $saida;
	}

$xajax->registerFunction("exibir_lista");	


$xajax->processRequest();

?>