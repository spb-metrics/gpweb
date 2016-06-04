<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $tab, $bd;

$aceitar = getParam($_REQUEST, 'aceitar', '');

	
$ordenarPor = getParam($_REQUEST, 'ordenar', 'titulo');
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;

if($aceitar){
	$nome_lista= getParam($_REQUEST, 'nome_lista', '');
	$lista_selecionada=getParam($_REQUEST, 'lista_selecionada', '');
	$vetor_atividade = getParam($_REQUEST, 'vetor_atividade', array());
	$lista_atividade =implode(',',$vetor_atividade);
	
	$sql->adTabela('parafazer_usuarios');
	$sql->adAtualizar('aceito', $aceitar);
	$sql->adAtualizar('data', date('Y-m-d H:i:s')); 
	$sql->adOnde('id IN ('.$lista_atividade.')'); 
	$sql->adOnde('usuario_id='.$Aplic->usuario_id); 
	$sql->exec();
	$sql->Limpar();	
	
	
	if ($nome_lista){
		$sql->adTabela('parafazer_listas');
		$sql->adInserir('nome', $nome_lista);
		$sql->adInserir('usuario_id', $Aplic->usuario_id);
		if (!$sql->exec()) die('Não foi possivel inserir na tabela parafazer_listas!'.$bd->stderr(true));
		$lista_selecionada=$bd->Insert_ID('parafazer_listas','id');
		$sql->limpar();
		}
	if ($aceitar==1){
		//criar uma cópia para o usuário
		foreach($vetor_atividade as $individual){
			$sql->adTabela('parafazer_tarefa');
			$sql->adCampo('d, titulo, nota, prio, ow, parafazer_chave, datafinal');
			$sql->adOnde('id='.$individual);
			$linha=$sql->Linha();
			$sql->limpar();

			$sql->adTabela('parafazer_tarefa');
			$sql->adInserir('lista_id', $lista_selecionada);
			$sql->adInserir('d', $linha['d']);
			$sql->adInserir('titulo', $linha['titulo']);
			$sql->adInserir('nota', $linha['nota']);
			$sql->adInserir('prio', $linha['prio']);
			$sql->adInserir('ow', $linha['ow']);
			$sql->adInserir('parafazer_chave', $linha['parafazer_chave']);
			$sql->adInserir('datafinal', $linha['datafinal']);
			if (!$sql->exec()) die('Não foi possivel inserir na tabela parafazer_tarefa!'.$bd->stderr(true));
			$sql->limpar();
			}
		}
	if ($aceitar==1) ver2((count($vetor_atividade)>1 ?'Atividades inseridas' : 'Atividade inserida'));	
	if ($aceitar==-1) ver2((count($vetor_atividade)>1 ? 'Atividades recusadas' : 'Atividade recusada'));	
	}


$sql->adTabela('parafazer_usuarios');
$sql->esqUnir('parafazer_tarefa','parafazer_tarefa','parafazer_usuarios.id=parafazer_tarefa.id');
$sql->esqUnir('parafazer_listas','parafazer_listas','parafazer_listas.id=parafazer_tarefa.lista_id');
$sql->adCampo('parafazer_usuarios.id, titulo, nota, datafinal, data, parafazer_usuarios.usuario_id');
if ($tab<3){	
	$sql->adOnde('parafazer_listas.usuario_id='.$Aplic->usuario_id);
	if($tab==0) $sql->adOnde('parafazer_usuarios.aceito=0');
	if($tab==1) $sql->adOnde('parafazer_usuarios.aceito=-1');
	if($tab==2) $sql->adOnde('parafazer_usuarios.aceito=1');
	}
if ($tab>2){	
	$sql->adOnde('parafazer_usuarios.usuario_id='.$Aplic->usuario_id);
	if($tab==3) $sql->adOnde('parafazer_usuarios.aceito=0');
	if($tab==4) $sql->adOnde('parafazer_usuarios.aceito=-1');
	if($tab==5) $sql->adOnde('parafazer_usuarios.aceito=1');
	}
	
$sql->adOrdem($ordenarPor.($ordem ? ' ASC' : ' DESC'));	
$lista = $sql->Lista();
$sql->limpar();

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="m" id="m" value="parafazer">';
echo '<input type=hidden name="a" id="a" value="controle">';
echo '<input type=hidden name="tab" id="tab" value="'.$tab.'">';
echo '<input type=hidden name="aceitar" id="aceitar" value="">';

echo '<table width="100%" border=0 cellpadding="0" cellspacing=0 class="std">';
echo '<tr><td><table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if ($tab==3) echo '<th>&nbsp;</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&ordenar=titulo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='titulo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Atividade', 'Nome da atividade.').'Atividade'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&ordenar=nota&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='nota' ? imagem('icones/'.$seta[$ordem]) : '').dica('Anotação da Atividade', 'Anotação da atividade.').'Anotação'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&ordenar=usuario_id&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='usuario_id' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['usuario']), ucfirst($config['usuario']).($tab <3 ? ' para o qual foi enviada a atividade.': ' do qual foi recebida a atividade.')).ucfirst($config['usuario']).dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&ordenar=datafinal&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='datafinal' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data Final', 'Data final estipulada para a execução da atividade.').'Data Final'.dicaF().'</th>';
if ($tab!=0 && $tab!=3) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&ordenar=data&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='data' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data', 'Data em que a atividade foi aceita ou recusada.').'Data'.dicaF().'</th>';
echo '</tr>';
foreach($lista as $linha){
	echo '<tr>';
	if ($tab==3) echo '<td width="16"><input type="checkbox" name="vetor_atividade[]" value="'.$linha['id'].'"></td>';
	echo '<td>'.($linha['titulo']? $linha['titulo'] : '&nbsp;' ).'</td>';
	echo '<td>'.($linha['nota']? $linha['nota'] : '&nbsp;' ).'</td>';
	echo '<td>'.link_usuario($linha['usuario_id'],'','','esquerda').'</td>';
  //EUZ corrige exibição qdo não tem dado  
	echo '<td align=center>'.( $linha['datafinal'] ? retorna_data( $linha['datafinal'], false, true): '&nbsp;' ).'</td>';
	//EUD
	if ($tab!=0 && $tab!=3) echo '<td align=center>'.retorna_data($linha['data']).'</td>';
	echo '</tr>';
	}
if(!count($lista)) echo '<tr><td colspan=20>Nenhuma atividade encontrada</td></tr>';
echo '</table></td></tr>';
if ($tab==3 && count($lista)) {
	
	$sql->adTabela('parafazer_listas');
	$sql->adCampo('id, nome');
	$sql->adOnde('usuario_id='.$Aplic->usuario_id);
	$sql->adOrdem('nome');
	$combo=$sql->listaVetorChave('id','nome');
	$sql->Limpar();
	$combo[0]='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<tr><td colspan=20>'.dica('Lista', 'Selecione a lista em que deseje incluir.').'&nbsp;Lista:'.dicaF().selecionaVetor($combo, 'lista_selecionada', 'size="1" class="texto"',0).'&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Nova Lista', 'Caso queira inserir as tarefas a fazer selecionadas em uma nova lista insira o nome da lista a ser criada.').'Nova lista:'.dicaF().'<input type="text" name="nome_lista" value="" style="width:200px;" class="texto" /></td></tr>';
	echo '<tr><td colspan=20><table width="100%"><tr><td>'.botao('aceitar', 'Aceitar Atividade', 'Clique neste botão para aceitar a atividade endereçada para ti selecionadas.','','enviar(1)').'</td><td align=right>'.botao('recusar', 'Recusar Atividade', 'Clique neste botão para recusar ad atividade endereçada para ti selecionadas.','','enviar(-1)').'</td></tr></table></td></tr>';
	}
echo '</table></form>';
?>

<script language=Javascript>
function enviar(aceito){
 if (verifica_selecao(aceito)){
 		env.aceitar.value=aceito;
		env.submit();
 		}	
	}

function verifica_selecao(aceito){
	if (aceito==1){
		if(env.lista_selecionada.value < 1 && env.nome_lista.value==''){
			alert ('Selecione uma lista ou crie um nome para uma nova lista!'); 
			return 0;
			}
		}
	
	var j=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		if (document.getElementById('env').elements[i].checked) j++;
		}	
	if (j>0) return 1;
	else {
		alert ("Selecione ao menos um!"); 
		return 0;
		}
	} 
</script>