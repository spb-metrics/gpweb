<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$sql = new BDConsulta;
$data = getParam($_REQUEST, 'data', 0);
$calendario = getParam($_REQUEST, 'calendario', 0);
if (getParam($_REQUEST, 'processar_convites', 0)){
	$convite = getParam($_REQUEST, 'convite', array());
	
	$aceitar = getParam($_REQUEST, 'aceitar', 0);
	$recusar = getParam($_REQUEST, 'recusar', 0);
	
	
	$atualizar=0;
	if (count($convite) && $aceitar){
		//checar se precisa atualizar o calendario para mostrar o compromisso
		$obj_data = new CData($data);
		$sql->adTabela('eventos', 'eventos');
		$sql->esqUnir('evento_usuarios', 'evento_usuarios', 'evento_usuarios.evento_id = eventos.evento_id');
		$sql->adOnde('eventos.evento_id IN ('.implode(',',(array)$convite).')');
		$sql->adOnde('usuario_id='.$Aplic->usuario_id);
		$sql->adCampo('count(eventos.evento_id)');
		
		if ($calendario=='ver_ano'){
			$sql->adOnde('ano(evento_inicio)<='.$obj_data->format('%Y').' AND ano(evento_fim)>='.$obj_data->format('%Y'));
			}
		
		if ($calendario=='ver_mes'){
			$sql->adOnde('ano(evento_inicio)<='.$obj_data->format('%Y').' AND ano(evento_fim)>='.$obj_data->format('%Y'));
			$sql->adOnde('mes(evento_inicio)<='.$obj_data->format('%m').' AND mes(evento_fim)>='.$obj_data->format('%m'));
			}
			
		if ($calendario=='ver_semana'){
			$sql->adOnde('ano(evento_inicio)<='.$obj_data->format('%Y').' AND ano(evento_fim)>='.$obj_data->format('%Y'));
			$sql->adOnde('mes(evento_inicio)<='.$obj_data->format('%m').' AND mes(evento_fim)>='.$obj_data->format('%m'));
			$sql->adOnde('semana_ano(evento_inicio)<='.$obj_data->format('%U').' AND semana_ano(evento_fim)>='.$obj_data->format('%U'));
			}
		$atualizar=(int)$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('evento_usuarios');
		$sql->adAtualizar('aceito', 1);
		$sql->adAtualizar('data', date('Y-m-d g:i'));
		$sql->adOnde('evento_id IN ('.implode(',',(array)$convite).')');
		$sql->adOnde('usuario_id='.$Aplic->usuario_id);
		$sql->exec();
		$sql->limpar();	
	
		}
	
	if (count($convite) && $recusar){
		
		$sql->adTabela('evento_usuarios');
		$sql->adAtualizar('aceito', -1);
		$sql->adAtualizar('data', date('Y-m-d g:i'));
		$sql->adOnde('evento_id IN ('.implode(',',(array)$convite).')');
		$sql->adOnde('usuario_id='.$Aplic->usuario_id);
		$sql->exec();
		$sql->limpar();	
		
		}
	
	if ($Aplic->profissional){
		if ($atualizar)	echo '<script>parent.gpwebApp._popupCallback(); parent.gpwebApp._popupWin.close();</script>';	
		else echo '<script>parent.gpwebApp._popupWin.close();</script>';	
		}
	else {
		if ($atualizar)	echo '<script>opener.location.reload(); window.close();</script>';	
		else echo '<script>window.close();</script>';	
		}
	}




$sql->adTabela('eventos', 'e');
$sql->esqUnir('evento_usuarios', 'evento_usuarios', 'evento_usuarios.evento_id = e.evento_id');
$sql->adCampo('aceito, e.evento_id, evento_titulo, evento_inicio, evento_fim, evento_descricao, evento_nr_recorrencias, evento_recorrencias, evento_lembrar, evento_dono, e.evento_localizacao, e.evento_cor');
$sql->adOrdem('e.evento_inicio, e.evento_fim ASC');
$sql->adOnde('evento_dono != '.$Aplic->usuario_id);
$sql->adOnde('evento_usuarios.usuario_id='.$Aplic->usuario_id);
$sql->adOnde('evento_usuarios.aceito=0');
$convites=$sql->Lista();
$sql->Limpar();

echo '<form method="post" name="env" id="env">'; 
echo '<input type="hidden" name="m" value="calendario" />';
echo '<input type="hidden" name="a" value="convite" />';
echo '<input type="hidden" name="processar_convites" value="1" />';
echo '<input type="hidden" name="aceitar" value="" />';
echo '<input type="hidden" name="recusar" value="" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="data" value="'.$data.'" />';
echo '<input type="hidden" name="calendario" value="'.$calendario.'" />';

echo estiloTopoCaixa();	
echo '<table class="std" cellspacing=0 cellpadding="2" width="100%">';
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$recorrencia = array(0 =>'Nunca', 2=> 'Diaria', 3=>'Semanalmente', 4=>'Quinzenal', 5=>'Mensal', 9=>'Bimestral', 10=>'Trimestral', 6=>'Quadrimestral', 7=>'Semestral', 8=>'Anual');
echo '<tr style="background-color:#a6a6a6; font-weight:bold; text-align:center; font-size:20pt;"><td colspan=20 style="font-size:12pt;">Convite para evento</td></tr>';
echo '<tr style="background-color:#a6a6a6; font-weight:bold; text-align:center;"><td><input type="checkbox" name="sel_todas" value="1" onclick="marca_sel_todas();"></td><td>Criador</td><td align="left">Título</td><td align="left">Descrição</td><td>Início</td><td>Término</td><td>Convidados</td><td>Recorrência</td></tr>';
foreach ($convites as $convite) {
	$data_inicio = $convite['evento_inicio'] ? new CData($convite['evento_inicio']) : new CData();
	$data_fim = $convite['evento_fim'] ? new CData($convite['evento_fim']) : new CData();	
	echo '<tr style="background-color:#ffffff;" align="center"><td><input type="checkbox" name="convite[]" value="'.$convite['evento_id'].'"></td>';
	echo '<td nowrap="nowrap">'.link_usuario($convite['evento_dono']).'</td>';
	echo '<td align="left">'.$convite['evento_titulo'].'</td>';
	echo '<td align="left">'.$convite['evento_descricao'].'</td>';
	echo '<td nowrap="nowrap">'.($data_inicio ? $data_inicio->format($df.' '.$tf) : '&nbsp;').'</td>';
	echo '<td nowrap="nowrap">'.($data_fim ? ($data_inicio->format($df)==$data_fim->format($df) ? $data_fim->format($tf) : $data_fim->format($df.' '.$tf)) : '&nbsp;').'</td>';
	$sql->adTabela('evento_usuarios', 'e');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=e.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
	$sql->adOnde('evento_id='.$convite['evento_id']);
	$sql->adOnde('aceito!= -1');
	$participantes=$sql->Lista();
	$sql->Limpar();


	$saida_quem='';
	if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td nowrap="nowrap">'.link_usuario($participantes[0]['usuario_id'], '','','esquerda').($participantes[0]['contato_dept']? ' - '.link_secao($participantes[0]['contato_dept']) : '');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').($participantes[$i]['contato_dept']? ' - '.link_secao($participantes[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$convite['evento_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$convite['evento_id'].'"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
	echo '<td nowrap="nowrap">'.$saida_quem.'</td>';

	echo '<td nowrap="nowrap">'.(isset($recorrencia[$convite['evento_recorrencias']]) ? $recorrencia[$convite['evento_recorrencias']] : '').($convite['evento_recorrencias'] ? ' ('.$convite['evento_nr_recorrencias'].' vez'.((int)$convite['evento_nr_recorrencias'] > 1 ? 'es':''). ')' : '').'</td>';
	echo '</tr>';
	}
echo '<tr><td colspan=20><table width="100%"><tr><td align="left">'.botao('aceitar os marcados', 'Aceitar os Marcados', 'Aceitar os compromissos marcados.','','if (verifica_selecao()) {env.aceitar.value=1; env.submit();}').'</td><td width="100%">&nbsp;</td><td align="left">'.botao('recusar os marcados', 'Recusar os Marcados', 'Recusar os compromissos marcados.','','if (verifica_selecao()) {env.recusar.value=1; env.submit();}').'</td></tr></table></td></tr>';	
echo '</table>';	
echo estiloFundoCaixa();	
echo '</form>';	
?>
	
<script language="javascript">
	
function marca_sel_todas() {
  with(document.getElementById('env')) {
	  for(i=0; i < elements.length; i++) {
			thiselm = elements[i];
			thiselm.checked = !thiselm.checked
      }
    }
  }		
	

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
function verifica_selecao(){
	var j=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		if (document.getElementById('env').elements[i].checked) j++;
		}
	if (j>0) return 1;
	else {
		alert ("Selecione ao menos um convite!");
		return 0;
		}
	}
		
</script>